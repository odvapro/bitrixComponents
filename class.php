<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Config\ConfigurationException;
use Bitrix\Iblock\PropertyIndex\Facet;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class SmartFilter extends CBitrixComponent
{
	/**
	 * параметры, которые обязательно надо передать в arParams
	 */
	private $requiredParams = ['IBLOCK_ID'];

	private $SECTION_ID;

	public function executeComponent()
	{
		if(!$this->checkRequiredParams())
			return;

		if(!Loader::includeModule("iblock") || !Loader::includeModule('catalog'))
			return;

		$this->urlString = "";
		$this->urlArray = [];
		
		if(!empty($this->arParams['FILTER_URL']))	
		{
			if(is_array($this->arParams['FILTER_URL']))
				$this->urlArray = $this->arParams['FILTER_URL'];
			else
				$this->urlString = trim($this->arParams['FILTER_URL'], '/');
		}

		$this->IBLOCK_ID = $this->arParams['IBLOCK_ID'];
		$this->SECTION_ID = $this->arParams['SECTION_ID']? $this->arParams['SECTION_ID']: 0 ;

		$this->arResult = [
			'ITEMS'  => [],
		];
		$this->arResult['PARSED_FILTER'] = $this->getParsedFilterFromUrl();
		$this->initSectionId();
		$this->initFacet();

		$this->fillIBlockPropsInfo($this->IBLOCK_ID, $this->SECTION_ID);

		if(!empty($this->facet->getSkuIblockId()))
			$this->fillIBlockPropsInfo($this->facet->getSkuIblockId(), $this->SECTION_ID, true);

		$this->makeFilter();

		$this->includeComponentTemplate();

		return $this->getFilterForElements();
	}

	public function getFilterForElements()
	{
		$filter = [
			'products' => [],
			'offers'   => []
		];

		foreach ($this->arResult['ITEMS'] as $propertyId => $property)
		{
			$filterKey = $property->isSkuProperty() ? 'offers' : 'products';
			$filterData = $property->getFilterData();

			if(!empty($filterData['filter']))
				$filter[$filterKey][$filterData['propertyCode']] = $filterData['filter'];
		}

		$parsedFilter = $this->arResult['PARSED_FILTER'];
		foreach ($this->arParams['ADDITIONAL_PROPERTIES'] as $properties)
		{
			$propertiesName = html_entity_decode($properties);

			if(!empty($parsedFilter[$properties]))
				$filter['products'][$propertiesName] = $parsedFilter[$properties];
		}

		if(!empty($this->arParams['PREFILTER']))
			$filter['products'] = array_merge($this->arParams['PREFILTER'], $filter['products']);

		return $filter;
	}

	public function makeFilter()
	{
		$parsedFilter = $this->arResult['PARSED_FILTER'];

		$facetFilter = [
			"ACTIVE_DATE"       => "Y",
			"CHECK_PERMISSIONS" => "Y",
		];
		if(!empty($this->arParams['PREFILTER']))
			$facetFilter = array_merge($this->arParams['PREFILTER'], $facetFilter);

		$facets = $this->getFacets($facetFilter);

		$propertyValuesDictionary = $this->getPropertyValuesDictionary($facets);

		foreach ($this->arResult['ITEMS'] as $propertyId => $property)
		{
			$this->arResult['ITEMS'][$propertyId]->setFilter($parsedFilter);
			$this->arResult['ITEMS'][$propertyId]->setDictionary($propertyValuesDictionary);
			$this->arResult['ITEMS'][$propertyId]->setFacet($this->facet, $facets);
			$this->arResult['ITEMS'][$propertyId]->loadValuesData();
		}

		foreach ($facets as $facet)
		{
			$propertyId = $this->facet->getStorage()->facetIdToPropertyId($facet['FACET_ID']);

			if(empty($this->arResult['ITEMS'][$propertyId]))
				continue;

			$this->arResult['ITEMS'][$propertyId]->addValueFromFacet($facet);
			if(!$this->arResult['ITEMS'][$propertyId]->hasFacetValueInFilter($facet))
				continue;

			$this->setFacetFilter($propertyId, $facet['VALUE']);
		}

		$facets = $this->getFacets($facetFilter);

		foreach ($facets as $facet)
		{
			$propertyId = $this->facet->getStorage()->facetIdToPropertyId($facet['FACET_ID']);

			if(empty($this->arResult['ITEMS'][$propertyId]))
				continue;

			$this->arResult['ITEMS'][$propertyId]->setElementsCountFromFacet($facet);
		}

		$filter = [];
		foreach ($this->arResult['ITEMS'] as $propertyId => $property)
			$filter[$property->getFilterCode()] = $property;

		$this->arResult['ITEMS'] = $filter;
		unset($filter);
	}

	public function setFacetFilter($propertyId, $facetValue)
	{
		$this->facet->addDictionaryPropertyFilter($propertyId, '=', $facetValue);
	}

	public function getPropertyValuesDictionary($facets)
	{
		$facetValues = array_column($facets, 'VALUE');
		return $this->facet->getDictionary()->getStringByIds($facetValues);
	}

	public function getFacets(Array $filter)
	{
		$query = $this->facet->query($filter);

		$facets = [];

		while ($facet = $query->Fetch())
			$facets[] = $facet;

		return $facets;
	}

	public function fillIBlockPropsInfo($IBLOCK_ID, $SECTION_ID = 0, $isSkuProperty = false)
	{
		foreach(CIBlockSectionPropertyLink::GetArray($IBLOCK_ID, $SECTION_ID) as $propertyId => $arLink)
		{
			if ($arLink["SMART_FILTER"] !== "Y")
				continue;

			if ($arLink["ACTIVE"] === "N")
				continue;

			$arProperty = CIBlockProperty::GetByID($propertyId)->Fetch();

			if(!$arProperty || empty($arProperty['CODE']))
				continue;

			$fieldClassName = ucfirst($arProperty["PROPERTY_TYPE"]) . ucfirst($arProperty["USER_TYPE"]) . "Field";

			$this->addFieldClass($fieldClassName, $arProperty, $arLink, $isSkuProperty);
		}

		$parsedFilter = $this->arResult['PARSED_FILTER'];

		if(
			!empty($this->arParams['PRICE'])
			&&
			!empty($this->arParams['PRICE']['FIELD'])
		)
		{
			$this->addFieldClass(
				'PriceField',
				[
					'ID' => 1,
					'NAME' => $this->arParams['PRICE']['TITLE'],
					'CODE' => $this->arParams['PRICE']['FIELD']
				],
				[],
				false,
				$IBLOCK_ID
			);
		}
	}

	public function addFieldClass($fieldClassName, $arProperty, $arLink, $isSkuProperty)
	{
		if(!file_exists(__DIR__ . "/fields/{$fieldClassName}.php"))
			return;

		require_once __DIR__ . "/fields/{$fieldClassName}.php";
		$this->arResult['ITEMS'][$arProperty["ID"]] = new $fieldClassName($arProperty, $arLink, $isSkuProperty);
	}

	public function initSectionId()
	{
		if(!empty($this->arParams['SECTION_ID']))
		{
			$this->SECTION_ID = $this->arParams['SECTION_ID'];
			return;
		}

		if(empty($this->arParams['SECTION_CODE']))
			return;

		$rsSection = CIBlockSection::GetList([], ['CODE' => $this->arParams['SECTION_CODE']]);

		if(!($arSection = $rsSection->Fetch()))
			return;

		$this->SECTION_ID = $arSection['ID'];
	}

	public function initFacet()
	{
		$this->facet = new Facet($this->IBLOCK_ID);

		if(empty($this->SECTION_ID))
			return;

		$this->facet->setSectionId($this->SECTION_ID);
	}

	public function getParsedFilterFromUrl()
	{
		$filter = [];

		if(!empty($this->urlArray))
			return $this->urlArray;

		if(empty($this->urlString))
			return $filter;

		return $this->parseUrlString($this->urlString);
	}

	public function parseUrlString($strUrl)
	{
		$filter = [];

		if(!is_string($strUrl))
			return $filter;

		$filterParts = array_chunk(explode('/', trim($strUrl, '/')), 2);
		$filterParts = array_combine(array_column($filterParts, 0), array_column($filterParts, 1));

		foreach ($filterParts as $filterItemCode => $filterItemValues)
		{
			$filterItemValues = explode(':', $filterItemValues);
			$items = [];
			foreach ($filterItemValues as $item)
				$items[] = urldecode($item);

			$filter[$filterItemCode] = $items;
		}

		return $filter;
	}

	public function checkRequiredParams()
	{
		foreach ($this->requiredParams as $param)
		{
			if(empty($this->arParams[$param]))
			{
				ShowError("Необходимо передать в компонент обязательный параметр {$param}");
				return false;
			}
		}
		return true;
	}
}