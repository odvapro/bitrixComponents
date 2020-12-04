<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class Elements extends CBitrixComponent
{
	public $sections        = [];
	public $defaultPageSize = 20;

	public function onPrepareComponentParams($arParams)
	{
		$defaultParams = [
			'sort' => ['SORT' => 'ASC'],
			'filter' => []
		];

		foreach($defaultParams as $paramKey => $paramValue)
			if(!array_key_exists($paramKey, $arParams) || !is_array($arParams[$paramKey]))
				$arParams[$paramKey] = $paramValue;

		if(!array_key_exists('show_all', $arParams) || !$arParams['show_all'])
			$arParams['show_all'] = false;
		else
			$arParams['show_all'] = true;

		if(!array_key_exists('pagn_id', $arParams) || !is_string($arParams['pagn_id']) || empty($arParams['pagn_id']))
			$arParams['pagn_id'] = false;

		if(!array_key_exists('count', $arParams) || !is_integer($arParams['count']) || empty($arParams['count']))
			$arParams['count'] = false;

		if(!array_key_exists('page', $arParams) || !is_integer($arParams['page']) || empty($arParams['page']))
			$arParams['page'] = false;

		if(
			!array_key_exists('props', $arParams)
			||
			empty($arParams['props'])
			||
			!is_array($arParams['props'])
		)
			$arParams['props'] = false;

		if(
			!array_key_exists('price_ids', $arParams)
			||
			empty($arParams['price_ids'])
			||
			!is_array($arParams['price_ids'])
		)
			$arParams['price_ids'] = false;

		if(
			!array_key_exists('images', $arParams)
			||
			empty($arParams['images'])
			||
			array_keys($arParams['images']) === range(0, count($arParams['images']) - 1)
		)
			$arParams['images'] = false;

		return $arParams;
	}

	public function executeComponent()
	{
		if(!\Bitrix\Main\Loader::includeModule("iblock") || !\Bitrix\Main\Loader::includeModule("catalog"))
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

		$this->loadProperties();
	}

	private function processElement($element)
	{
		if(!empty($element['IBLOCK_SECTION_ID']) && !empty($this->arParams['load_section']))
			$element['SECTION'] = $this->getSection($element['IBLOCK_SECTION_ID'], $element['IBLOCK_ID']);

		// подгрузка путей изображений в поля типа DETAIL_PICTURE и PREVIEW_PICTURE
		if($this->arParams['images'])
			foreach ($this->arParams['images'] as $imagePropCode => $variants)
				if(array_key_exists($imagePropCode, $element))
					$element[$imagePropCode] = $this->processImageProp($imagePropCode, $element[$imagePropCode]);

		// перенос цен в общий массив, при необходимости подгрузка скидок
		$this->processPrices($element);

		return $element;
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

	private function loadProperties()
	{
		if(empty($this->arParams['props']) || empty($this->arResult['ITEMS']))
			return;

		$properties = array_fill_keys(array_column($this->arResult['ITEMS'], 'ID'), []);

		$firstItem = current(array_values($this->arResult['ITEMS']));

		if(!array_key_exists('IBLOCK_ID', $firstItem))
			return;

		CIBlockElement::GetPropertyValuesArray(
			$properties,
			$firstItem['IBLOCK_ID'],
			[],
			['CODE' => $this->arParams['props']]
		);

		foreach ($properties as $elementId => $propsArr)
		{
			if($this->arParams['images'])
			{
				foreach ($propsArr as $propCode => $prop)
				{
					if(array_key_exists($propCode, $this->arParams['images']))
					{
						$propsArr[$propCode]['VALUE'] = $this->processImageProp(
							$propCode, $propsArr[$propCode]['VALUE']
						);
					}
				}
			}

			$this->arResult['ITEMS'][$elementId]['PROPERTIES'] = $propsArr;
		}
	}

	private function processImageProp($propCode, $realValue)
	{
		if(!array_key_exists($propCode, $this->arParams['images']))
			return false;

		if(empty($realValue))
			return $realValue;

		$variants = $this->arParams['images'][$propCode];

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
