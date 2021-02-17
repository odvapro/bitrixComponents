<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

require 'ElementsParamsValidator.php';

class Elements extends CBitrixComponent
{
	public $sections        = [];
	public $defaultPageSize = 20;

	public function onPrepareComponentParams($arParams)
	{
		$paramsValidatorRules = [
			'sort'      => ['is_key_exists', 'is_array', '!empty'],
			'filter'    => ['is_key_exists', 'is_array'],
			'show_all'  => ['boolval'],
			'pagn_id'   => ['is_key_exists', 'is_string', '!empty'],
			'count'     => ['is_key_exists', 'is_numeric'],
			'page'      => ['is_key_exists', 'is_numeric'],
			'props'     => ['is_key_exists', 'is_array', '!empty'],
			'price_ids' => ['is_key_exists', 'is_array', '!empty'],
			'images'    => ['is_key_exists', 'is_assoc', '!empty'],
		];

		$paramsValidatorDefaults = [
			'sort'   => ['SORT' => 'ASC'],
			'filter' => []
		];

		foreach ($paramsValidatorRules as $field => $rules)
		{
			$defaults = array_key_exists($field, $paramsValidatorDefaults) ? $paramsValidatorDefaults[$field] : false;
			$arParams[$field] = ElementsParamsValidator::validate($field, $arParams, $rules, $defaults);
		}

		return $arParams;
	}

	public function executeComponent()
	{
		if(!\Bitrix\Main\Loader::includeModule("iblock"))
			return;

		$this->initParams();
		$this->makeResult();

		$this->IncludeComponentTemplate();
	}

	private function initParams()
	{
		$this->arResult['ITEMS'] = [];

		$this->initSort();
		$this->initFilter();
		$this->initNavParams();
		$this->initSelect();
	}

	private function initSelect()
	{
		$selectParams = ['*'];

		if(!empty($this->arParams['product']['props']))
		{
			$selectParams[] = "PROPERTY_CML2_LINK.ID";
			$selectParams[] = "PROPERTY_CML2_LINK.IBLOCK_ID";

			foreach ($this->arParams['product']['props'] as $prop)
				if(!preg_match('/^PROPERTY_/', $prop))
					$selectParams[] = "PROPERTY_CML2_LINK.{$prop}";
		}

		if($this->arParams['price_ids'])
			foreach ($this->arParams['price_ids'] as $prop)
				$selectParams = array_merge($selectParams, ["PRICE_{$prop}", "CURRENCY_{$prop}"]);

		$this->arResult['SELECT'] = $selectParams;
	}

	private function initFilter()
	{
		$filterParams = [];

		foreach ($this->arParams['filter'] as $paramName => $paramValue)
		{
			if(in_array($paramName, $this->arParams['props']))
				$filterParams["PROPERTY_{$paramName}"] = $paramValue;
			else
				$filterParams[$paramName] = $paramValue;
		}

		if(empty($this->arParams['product']['filter']))
		{
			$this->arResult['FILTER'] = $filterParams;
			return;
		}

		foreach ($this->arParams['product']['filter'] as $paramName => $paramValue)
			$filterParams["PROPERTY_CML2_LINK.{$paramName}"] = $paramValue;

		$this->arResult['FILTER'] = $filterParams;
	}

	private function initSort()
	{
		$sortParams   = [];

		foreach ($this->arParams['sort'] as $paramName => $paramValue)
		{
			if(in_array($paramName, $this->arParams['props']))
				$sortParams["PROPERTY_{$paramName}"] = $paramValue;
			else
				$sortParams[$paramName] = $paramValue;
		}

		$this->arResult['SORT'] = $sortParams;
	}

	private function initNavParams()
	{
		$this->arResult['NAV_OBJECT'] = false;
		$this->arResult['NAV_PARAMS'] = [];

		if($this->arParams['pagn_id'])
		{
			$this->arResult['NAV_OBJECT'] = new \Bitrix\Main\UI\PageNavigation($this->arParams['pagn_id']);
			$this->arResult['NAV_OBJECT']->initFromUri();

			if($this->arParams['count'])
				$this->arResult['NAV_OBJECT']->setPageSize($this->arParams['count']);
			else
				$this->arResult['NAV_OBJECT']->setPageSize($this->defaultPageSize);

			$this->arResult['NAV_PARAMS'] = [
				'iNumPage'  => $this->arResult['NAV_OBJECT']->getCurrentPage(),
				'nPageSize' => $this->arResult['NAV_OBJECT']->getPageSize(),
			];
		}
		else
		{
			if($this->arParams['page'])
			{
				$this->arResult['NAV_PARAMS'] = [
					'iNumPage'  => $this->arParams['page'],
					'nPageSize' => $this->arParams['count'] ?: 20,
				];
			}
			else
			{
				if($this->arParams['count'])
					$this->arResult['NAV_PARAMS'] = ['nTopCount' => $this->arParams['count']];
				else
				{
					if($this->arParams['show_all'])
						$this->arResult['NAV_PARAMS'] = [];
					else
						$this->arResult['NAV_PARAMS'] = ['nTopCount' => 20];
				}
			}
		}
	}

	public function makeResult()
	{
		$rsElements = CIBlockElement::GetList(
			$this->arResult['SORT'],
			$this->arResult['FILTER'],
			false,
			$this->arResult['NAV_PARAMS'],
			$this->arResult['SELECT']
		);

		if($this->arParams['pagn_id'])
			$this->arResult['NAV_OBJECT']->setRecordCount($rsElements->SelectedRowsCount());

		while($element = $rsElements->Fetch())
			$this->arResult['ITEMS'][$element['ID']] = $this->processElement($element);

		unset($this->sections);

		$this->processProperties();
		$this->processImages();
	}

	private function processImages()
	{
		if(empty($this->arParams['images']))
			return;

		foreach ($this->arResult['ITEMS'] as $elementId => &$element)
		{
			foreach ($this->arParams['images'] as $imagePropCode => $variants)
			{
				if(array_key_exists($imagePropCode, $element))
				{
					$element[$imagePropCode] = $this->getImagePropValue(
						$variants,
						$element[$imagePropCode]
					);
				}

				if(array_key_exists($imagePropCode, $element['PROPERTIES']))
				{
					$element['PROPERTIES'][$imagePropCode] = $this->getImagePropValue(
						$variants,
						$element['PROPERTIES'][$imagePropCode]['VALUE']
					);
				}

				if(array_key_exists($imagePropCode, $element['PRODUCT']))
				{
					$element['PRODUCT'][$imagePropCode] = $this->getImagePropValue(
						$variants,
						$element['PRODUCT'][$imagePropCode]['VALUE']
					);
				}
			}
		}
	}

	private function processProperties()
	{
		if(empty($this->arResult['ITEMS']))
			return;

		if(!empty($this->arParams['props']))
			$this->processElementProperties();

		if(!empty($this->arParams['product']['props']))
			$this->processProductProperties();
	}

	private function processProductProperties()
	{
		$firstItem   = reset($this->arResult['ITEMS']);
		$iblockId    = $firstItem['PROPERTY_CML2_LINK_IBLOCK_ID'];
		$elementsIds = array_unique(array_column($this->arResult['ITEMS'], 'PROPERTY_CML2_LINK_ID'));
		$props       = [];

		foreach ($this->arParams['product']['props'] as $prop)
			if(preg_match('/^PROPERTY_(.*)$/', $prop, $matches))
				$props[] = $matches[1];

		$elementsProps = $this->getPropValues($iblockId, $elementsIds, $props);

		foreach ($this->arResult['ITEMS'] as $itemId => $item)
		{
			foreach ($this->arParams['product']['props'] as $prop)
			{
				if(preg_match('/^PROPERTY_(.*)$/', $prop))
					continue;

				$elementsProps[$item['PROPERTY_CML2_LINK_ID']][$prop] = $item["PROPERTY_CML2_LINK_{$prop}"];

				unset($this->arResult['ITEMS'][$itemId]["PROPERTY_CML2_LINK_{$prop}"]);
			}

			$elementsProps[$item['PROPERTY_CML2_LINK_ID']]['ID']        = $item["PROPERTY_CML2_LINK_ID"];
			$elementsProps[$item['PROPERTY_CML2_LINK_ID']]['IBLOCK_ID'] = $item["PROPERTY_CML2_LINK_IBLOCK_ID"];

			unset($this->arResult['ITEMS'][$itemId]["PROPERTY_CML2_LINK_ID"]);
			unset($this->arResult['ITEMS'][$itemId]["PROPERTY_CML2_LINK_IBLOCK_ID"]);

			$this->arResult['ITEMS'][$itemId]['PRODUCT'] = $elementsProps[$item['PROPERTY_CML2_LINK_ID']];
		}
	}

	private function processElementProperties()
	{
		$firstItem   = reset($this->arResult['ITEMS']);
		$iblockId    = $firstItem['IBLOCK_ID'];
		$elementsIds = array_column($this->arResult['ITEMS'], 'ID');
		$props       = $this->arParams['props'];

		$elementsProps = $this->getPropValues($iblockId, $elementsIds, $props);

		foreach ($this->arResult['ITEMS'] as $itemId => $item)
			$this->arResult['ITEMS'][$itemId]['PROPERTIES'] = $elementsProps[$itemId];
	}

	private function processElement($element)
	{
		if(
			!empty($element['IBLOCK_SECTION_ID'])
			&&
			(
				!empty($this->arParams['load_section'])
				||
				!empty($this->arParams['load_urls'])
			)
		)
		{
			$element['SECTION'] = $this->getSection($element['IBLOCK_SECTION_ID'], $element['IBLOCK_ID']);
		}

		// перенос цен в общий массив, при необходимости подгрузка скидок
		$this->processPrices($element);

		$this->processUrls($element);

		return $element;
	}

	public function processUrls(&$element)
	{
		if(empty($this->arParams['load_urls']))
			return;

		$element['LIST_PAGE_URL']   = \CIBlock::ReplaceDetailUrl(
			$element['SECTION']['SECTION_PAGE_URL'],
			$element['SECTION'],
			true,
			false
		);

		$element['DETAIL_PAGE_URL'] = \CIBlock::ReplaceDetailUrl(
			$element['DETAIL_PAGE_URL'],
			$element,
			true,
			false
		);
	}

	public function processPrices(&$element)
	{
		if(!$this->arParams['price_ids'])
			return;

		$arDiscounts = false;

		foreach ($this->arParams['price_ids'] as $priceId)
		{
			$priceKey    = "PRICE_{$priceId}";
			$currencyKey = "CURRENCY_{$priceId}";

			if(!array_key_exists($priceKey, $element))
				continue;

			$price = [
				'PRICE'    => $element[$priceKey],
				'CURRENCY' => $element[$currencyKey]
			];

			unset($element[$priceKey], $element[$currencyKey]);

			if(!$this->arParams['load_discounts'])
			{
				$element['PRICES'][$priceKey] = $price;
				continue;
			}

			global $USER;

			if($arDiscounts === false)
				$arDiscounts = CCatalogDiscount::GetDiscountByProduct($element['ID'], $USER->GetUserGroupArray(), "N");

			if(empty($arDiscounts))
			{
				$element['PRICES'][$priceKey] = $price;
				continue;
			}

			$discountPrice = CCatalogProduct::CountPriceWithDiscount(
				$price['PRICE'],
				$price['CURRENCY'],
				$arDiscounts
			);

			$price['OLD_PRICE'] = $price['PRICE'];
			$price['PRICE']     = $discountPrice;
			$price['DISCOUNT']  = [
				'PERCENT' => ($price['OLD_PRICE'] - $price['PRICE']) / $price['OLD_PRICE'] * 100,
				'VALUE'   => $price['OLD_PRICE'] - $price['PRICE']
			];

			$element['PRICES'][$priceKey] = $price;
		}
	}

	private function getPropValues($iblockId, array $elementIds, array $propCodes)
	{
		$result = array_fill_keys($elementIds, []);

		CIBlockElement::GetPropertyValuesArray($result, $iblockId, [], ['CODE' => $propCodes]);

		return $result;
	}

	private function getImagePropValue($variants, $realValue)
	{
		if(empty($realValue))
			return $realValue;

		if(is_array($realValue))
		{
			$imageData = [];

			foreach ($realValue as $id)
				$imageData[] = $this->getImageData($id, $variants);
		}
		else
			$imageData = $this->getImageData($realValue, $variants);

		return $imageData;
	}

	private function getImageData($imageId, $variants)
	{
		// $variants обязательно ассоциативный массив
		if(array_keys($variants) === range(0, count($variants) - 1))
			return false;

		$prop = [];

		foreach ($variants as $label => $settings)
		{
			if(count($settings) < 2 || !is_numeric($settings[0]) || !is_numeric($settings[1]))
				continue;

			$sizes = ['width' => $settings[0], 'height' => $settings[1]];

			$resizeModeList = [BX_RESIZE_IMAGE_EXACT, BX_RESIZE_IMAGE_PROPORTIONAL, BX_RESIZE_IMAGE_PROPORTIONAL_ALT];

			if(empty($settings[2]) || !in_array($settings[2], $resizeModeList))
				$resizeMode = BX_RESIZE_IMAGE_PROPORTIONAL;
			else
				$resizeMode = $settings[2];

			$prop[$label] = CFile::ResizeImageGet($imageId, $sizes, $resizeMode)['src'];
		}

		if(empty($prop))
			$prop = false;

		return $prop;
	}

	private function getSection($id, $iblockId)
	{
		if(array_key_exists($id, $this->sections))
			return $this->sections[$id];

		$this->sections[$id] = CIBlockSection::GetList(
			[],
			[
				'ID'        => $id,
				'IBLOCK_ID' => $iblockId,
			],
			false,
			['UF_*']
		)->Fetch();

		return $this->sections[$id];
	}
}
