<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Error;
use \Bitrix\Main\ErrorCollection;

class Element extends \CBitrixComponent
{
	const ERROR_TEXT = 1;
	const ERROR_404 = 2;

	public function __construct($component = null)
	{
		parent::__construct($component);
		$this->errorCollection = new ErrorCollection();
	}

	public function onPrepareComponentParams($params)
	{
		if(!Loader::includeModule('iblock'))
			return false;

		if(
			!array_key_exists('id', $params)
			&&
			!array_key_exists('code', $params)
			&&
			!array_key_exists('filter', $params)
		)
		{
			$this->errorCollection->setError(
				new Error('Необходимо передать один из параметров [id, code, filter]!', self::ERROR_TEXT)
			);
			return $params;
		}

		if(array_key_exists('id', $params) && ((int)$params['id'] <= 0 || (int)$params['id'] != $params['id']))
		{
			$this->errorCollection->setError(
				new Error('Параметр "id" должен иметь числовое значение!', self::ERROR_TEXT)
			);
			return $params;
		}

		if(
			!array_key_exists('id', $params)
			&&
			array_key_exists('code', $params)
			&&
			(!is_string($params['code']) || empty($params['code']))
		)
		{
			$this->errorCollection->setError(
				new Error('Параметр "code" должен иметь строковое значение!', self::ERROR_TEXT)
			);
			return $params;
		}

		if(
			!array_key_exists('id', $params)
			&&
			!array_key_exists('code', $params)
			&&
			!is_array($params['filter'])
		)
		{
			$this->errorCollection->setError(
				new Error('Параметр "filter" должен быть массивом!', self::ERROR_TEXT)
			);
			return $params;
		}

		if(!array_key_exists('IBLOCK_ID', $params) && empty($params['filter']['IBLOCK_ID']))
		{
			$this->errorCollection->setError(new Error('Необходимо передать параметр "IBLOCK_ID"!', self::ERROR_TEXT));
			return $params;
		}

		if(array_key_exists('IBLOCK_ID', $params) && ((int)$params['IBLOCK_ID'] <= 0 || (int)$params['IBLOCK_ID'] != $params['IBLOCK_ID']))
		{
			$this->errorCollection->setError(new Error('Параметр "IBLOCK_ID" должен иметь числовое значение!', self::ERROR_TEXT));
			return $params;
		}

		if(!array_key_exists('id', $params))
			$params['id'] = false;

		if(!array_key_exists('code', $params))
			$params['code'] = false;

		if(!array_key_exists('filter', $params))
			$params['filter'] = false;

		if(!array_key_exists('props', $params) || !is_array($params['props']))
			$params['props'] = false;

		if(!array_key_exists('load_section', $params) || empty($params['load_section']))
			$params['load_section'] = false;
		else
			$params['load_section'] = true;

		if(!array_key_exists('price_ids', $params) || !is_array($params['price_ids']) || empty($params['price_ids']))
			$params['price_ids'] = false;

		if(
			!array_key_exists('images', $params)
			||
			empty($params['images'])
			||
			array_keys($params['images']) === range(0, count($params['images']) - 1)
		)
			$params['images'] = false;

		return $params;
	}

	public function executeComponent()
	{
		if ($this->hasErrors())
			return $this->processErrors();

		if($this->arParams['id'])
			$arFilter = ['IBLOCK_ID'=>$this->arParams['IBLOCK_ID'], 'ID'=> $this->arParams['id']];
		else if($this->arParams['code'])
			$arFilter = ['IBLOCK_ID'=>$this->arParams['IBLOCK_ID'], 'CODE'=> $this->arParams['code']];
		else
		{
			if(!empty($this->arParams['IBLOCK_ID']))
				$this->arParams['filter']['IBLOCK_ID'] = $this->arParams['IBLOCK_ID'];

			$arFilter = $this->arParams['filter'];
		}

		if($this->arParams['load_product_fields'])
		{
			$arSelect = [
				'*',
				'TYPE',
				'AVAILABLE',
				'QUANTITY',
				'QUANTITY_RESERVED',
				'CAN_BUY_ZERO',
			];
		}
		else
			$arSelect = ['*'];

		$element = CIBlockElement::GetList([], $arFilter, false, false, $arSelect)->GetNext();

		if(!$element)
		{
			if(empty($this->arParams['set_code_404']) || $this->arParams['set_code_404'] != 'N')
				$this->errorCollection->setError(new Error("Елемент не найден.", self::ERROR_404));
			else
				$this->errorCollection->setError(new Error("Елемент не найден.", self::ERROR_TEXT));

			$this->arResult = false;

			if(empty($this->arParams['show_errors']) || $this->arParams['show_errors'] != 'N')
				return $this->processErrors();
			else
				return $this->includeComponentTemplate();
		}

		if($this->arParams['images'])
			foreach ($this->arParams['images'] as $imagePropCode => $variants)
				if(array_key_exists($imagePropCode, $element))
					$element[$imagePropCode] = $this->processImageProp($imagePropCode, $element[$imagePropCode]);

		if($this->arParams['props'])
			$element['PROPERTIES'] = $this->loadProperties($element['ID'], $element['IBLOCK_ID']);

		if($this->arParams['load_section'] && !empty($element['IBLOCK_SECTION_ID']))
			$element['SECTION'] = $this->loadSection($element['IBLOCK_SECTION_ID'], $element['IBLOCK_ID']);

		$element['PRICES'] = $this->getPrices($element['ID']);

		$this->arResult = $element;

		$this->processSeoData();

		$this->includeComponentTemplate();
	}

	public function processSeoData()
	{
		global $APPLICATION;

		if ($this->arParams['SET_CANONICAL_URL'] === 'Y' && $this->arResult["CANONICAL_PAGE_URL"])
			$APPLICATION->SetPageProperty('canonical', $this->arResult["CANONICAL_PAGE_URL"]);

		// говнокод скопирован из компонента news.detail
		if(
			$this->arParams["SET_TITLE"] !== 'Y' && $this->arParams["ADD_ELEMENT_CHAIN"] !== 'Y'&&
			$this->arParams["SET_BROWSER_TITLE"] !== 'Y' && $this->arParams["SET_META_KEYWORDS"] !== 'Y'&&
			$this->arParams["SET_META_DESCRIPTION"] === 'Y'
		)
			return;

		$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues(
			$this->arResult["IBLOCK_ID"],
			$this->arResult["ID"]
		);

		$this->arResult["IPROPERTY_VALUES"] = $ipropValues->getValues();

		if ($this->arParams["SET_TITLE"] === 'Y')
		{
			if(!empty($this->arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]))
				$title = $this->arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"];
			else
				$title = $this->arResult["NAME"];

			$APPLICATION->SetTitle($title);

			unset($title);
		}

		if ($this->arParams["ADD_ELEMENT_CHAIN"] === 'Y')
		{
			if(!empty($this->arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]))
				$elementChain = $this->arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"];
			else
				$elementChain = $this->arResult["NAME"];

			$APPLICATION->AddChainItem($elementChain);
		}

		if ($this->arParams["SET_BROWSER_TITLE"] === 'Y')
		{
			$browserTitle = \Bitrix\Main\Type\Collection::firstNotEmpty(
				$this->arResult["PROPERTIES"],
				[$this->arParams["BROWSER_TITLE"], "VALUE"],
				$this->arResult,
				$this->arParams["BROWSER_TITLE"],
				$this->arResult["IPROPERTY_VALUES"],
				"ELEMENT_META_TITLE"
			);

			if(!empty($browserTitle))
				$APPLICATION->SetPageProperty("title", $browserTitle);

			unset($browserTitle);
		}

		if ($this->arParams["SET_META_KEYWORDS"] === 'Y')
		{
			$metaKeywords = \Bitrix\Main\Type\Collection::firstNotEmpty(
				$this->arResult["PROPERTIES"],
				[$this->arParams["META_KEYWORDS"], "VALUE"],
				$this->arResult["IPROPERTY_VALUES"],
				"ELEMENT_META_KEYWORDS"
			);

			if(!empty($metaKeywords))
				$APPLICATION->SetPageProperty("keywords", $metaKeywords);

			unset($metaKeywords);
		}

		if ($this->arParams["SET_META_DESCRIPTION"] === 'Y')
		{
			$metaDescription = \Bitrix\Main\Type\Collection::firstNotEmpty(
				$this->arResult["PROPERTIES"],
				[$this->arParams["META_DESCRIPTION"], "VALUE"],
				$this->arResult["IPROPERTY_VALUES"],
				"ELEMENT_META_DESCRIPTION"
			);

			if(!empty($metaDescription))
				$APPLICATION->SetPageProperty("description", $metaDescription);

			unset($metaDescription);
		}
	}

	private function getPrices($elementId)
	{
		if(!$this->arParams['price_ids'])
			return;

		global $USER;

		$prices = \Bitrix\Catalog\PriceTable::getList([
			'select' => ['*'],
			'filter' => [
				'=PRODUCT_ID'      => $elementId,
				'CATALOG_GROUP_ID' => $this->arParams['price_ids']
			]
		])->fetchAll();

		$pricesWithDiscounts = [];

		foreach ($prices as $price)
		{
			$priceWithDiscounts = CCatalogProduct::GetOptimalPrice(
				$elementId,
				1,
				$USER->GetUserGroupArray(),
				'N',
				[
					[
						'ID'               => $price['ID'],
						'PRICE'            => $price['PRICE'],
						'CURRENCY'         => $price['CURRENCY'],
						'CATALOG_GROUP_ID' => $price['CATALOG_GROUP_ID'],
					]
				]
			);

			$pricesWithDiscounts[$price['CATALOG_GROUP_ID']] = $priceWithDiscounts['RESULT_PRICE'];
		}

		return $pricesWithDiscounts;
	}

	private function loadSection($id, $iblockId)
	{
		return CIBlockSection::GetList(
			[],
			[
				'ID'        => $id,
				'IBLOCK_ID' => $iblockId,
			],
			false,
			['UF_*']
		)->GetNext();
	}

	private function loadProperties($elementId, $iblockId)
	{
		$properties       = [$elementId => false];
		$propertiesFilter = in_array('*', $this->arParams['props']) ? [] : ['CODE' => $this->arParams['props']];

		CIBlockElement::GetPropertyValuesArray($properties, $iblockId, [], $propertiesFilter);

		$properties = $properties[$elementId];

		if(empty($properties) || !$this->arParams['images'])
			return $properties;

		if($this->arParams['images'])
		{
			foreach ($properties as $propCode => $prop)
			{
				if(array_key_exists($propCode, $this->arParams['images']))
				{
					$properties[$propCode]['VALUE'] = $this->processImageProp(
						$propCode, $properties[$propCode]['VALUE']
					);
				}
			}
		}

		return $properties;
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

	private function hasErrors()
	{
		return (bool)count($this->errorCollection);
	}

	private function processErrors()
	{
		if (!empty($this->errorCollection))
		{
			/** @var Error $error */
			foreach ($this->errorCollection as $error)
			{
				$code = $error->getCode();

				if ($code == self::ERROR_404)
				{
					\Bitrix\Iblock\Component\Tools::process404(
						trim($this->arParams['MESSAGE_404']) ?: $error->getMessage(),
						true,
						true,
						false
					);
				}
				elseif (
					$code == self::ERROR_TEXT
					&&
					(empty($this->arParams['show_errors']) || $this->arParams['show_errors'] != 'N')
				)
				{
					ShowError($error->getMessage());
				}
			}
		}

		return false;
	}
}
