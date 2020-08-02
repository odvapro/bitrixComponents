<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class Elements extends CBitrixComponent
{
	public $pages    = [];
	public $sections = [];

	public function onPrepareComponentParams($arParams)
	{
		if(!\Bitrix\Main\Loader::includeModule("iblock"))
			return;

		$defaultParams = [
			'sort' => ['SORT' => 'ASC'],
			'filter' => [],
			'group' => false
		];

		foreach($defaultParams as $paramKey => $paramValue)
			if(!array_key_exists($paramKey, $arParams) || !is_array($arParams[$paramKey]))
				$arParams[$paramKey] = $paramValue;

		if(
			!array_key_exists('props', $arParams)
			||
			empty($arParams['props'])
			||
			!is_array($arParams['props'])
		)
			$arParams['props'] = false;

		if(
			!array_key_exists('load_price', $arParams)
			||
			empty($arParams['load_price'])
		)
			$arParams['load_price'] = false;

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
		if(!\Bitrix\Main\Loader::includeModule("iblock"))
			return;

		$this->makeResult();

		$this->IncludeComponentTemplate();
	}

	public function makeResult()
	{
		$nav = new \Bitrix\Main\UI\PageNavigation($this->arParams['pagn_id']);
		$nav->initFromUri();

		if(!empty(intval($this->arParams['page'])))
			if(empty($this->arParams['pagn_id']))
				$nav->setCurrentPage(intval($this->arParams['page']));

		if(!empty(intval($this->arParams['count'])))
			$nav->setPageSize(intval($this->arParams['count']));
		else
			$nav->setPageSize(0);

		$params = [
			'filter' => $this->arParams['filter'],
			'order'  => $this->arParams['sort'],
			'count_total' => true,
			'offset' => $nav->getOffset(),
			'limit' => $nav->getLimit()
		];

		$rsElements = \Bitrix\Iblock\ElementTable::getList($params);

		$nav->setRecordCount($rsElements->getCount());

		if(empty($nav->getPageSize()))
				$nav->setPageSize($rsElements->getCount());

		$this->arResult['NAV_OBJECT'] = $nav;

		$this->arResult['ITEMS']      = [];

		while($element = $rsElements->Fetch())
		{
			$element['PROPERTIES'] = [];

			if(!empty($element['IBLOCK_SECTION_ID']) && !empty($this->arParams['load_section']))
				$element['SECTION'] = $this->getSection($element['IBLOCK_SECTION_ID'], $element['IBLOCK_ID']);

			$this->arResult['ITEMS'][$element['ID']] = $element;
		}

		// подгрузка свойств
		$this->loadProperties();

		// обработка изображений
		$this->processImages();

		// подгрузка цен
		$this->loadPrices();
	}

	public function loadPrices()
	{
		if(!$this->arParams['load_price'])
			return false;

		foreach ($this->arResult['ITEMS'] as $itemId => $item)
		{
			$price = CCatalogProduct::GetOptimalPrice($item['ID']);

			if(empty($price))
				$this->arResult['ITEMS'][$itemId]['PRICE'] = false;
			else
				$this->arResult['ITEMS'][$itemId]['PRICE'] = $price['RESULT_PRICE'];
		}
	}

	public function processImages()
	{
		if(!$this->arParams['images'])
			return;

		foreach ($this->arResult['ITEMS'] as $itemId => $item)
		{
			foreach ($this->arParams['images'] as $propCode => $variants)
			{
				if($propCode == 'DETAIL_PICTURE' || $propCode == 'PREVIEW_PICTURE')
					$imageId = $item[$propCode];
				else
					$imageId = $item['PROPERTIES'][$propCode]['VALUE'];

				if(is_array($imageId))
				{
					$imageData = [];

					foreach ($imageId as $id)
						$imageData[] = $this->getImageData($itemId, $id, $propCode, $variants);
				}
				else
				{
					$imageData = $this->getImageData($itemId, $imageId, $propCode, $variants);
				}

				$this->setImageValue($itemId, $propCode, $imageData);
			}
		}
	}

	private function getImageData($itemId, $imageId, $propCode, $variants)
	{
		if(empty($imageId) || !is_numeric($imageId))
			return false;

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

			if(empty($settigns[2]) || !in_array($settings[2], $resizeModeList))
				$resizeMode = BX_RESIZE_IMAGE_PROPORTIONAL;
			else
				$resizeMode = $settings[2];

			$prop[$label] = CFile::ResizeImageGet($imageId, $sizes, $resizeMode)['src'];
		}

		if(empty($prop))
			$prop = false;

		return $prop;
	}

	private function setImageValue($itemId, $propCode, $value)
	{
		if($propCode == 'DETAIL_PICTURE' || $propCode == 'PREVIEW_PICTURE')
			$this->arResult['ITEMS'][$itemId][$propCode] = $value;
		else
			$this->arResult['ITEMS'][$itemId]['PROPERTIES'][$propCode]['VALUE'] = $value;
	}

	public function getSection($id, $iblockId)
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

	public function loadProperties()
	{
		if(
			is_array($this->arParams['props'])
			&&
			!empty($this->arParams['props'])
			&&
			!empty($this->arParams['filter']['IBLOCK_ID'])
		)
		{
			CIBlockElement::GetPropertyValuesArray(
				$this->arResult['ITEMS'],
				$this->arParams['filter']['IBLOCK_ID'],
				$this->arParams['filter'],
				['CODE' => $this->arParams['props']]
			);
		}
	}
}
