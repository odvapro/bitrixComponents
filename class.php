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
		if(!Loader::includeModule('iblock') || !Loader::includeModule("catalog"))
			return false;

		if(!array_key_exists('id', $params) && !array_key_exists('code', $params))
		{
			$this->errorCollection->setError(
				new Error('Параметр "id" должен иметь числовое значение!', self::ERROR_TEXT)
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

		if(!array_key_exists('id', $params))
			$params['id'] = false;

		if(!array_key_exists('code', $params))
			$params['code'] = false;

		if(!array_key_exists('props', $params) || !is_array($params['props']))
			$params['props'] = false;

		if(!array_key_exists('load_section', $params) || empty($params['load_section']))
			$params['load_section'] = false;
		else
			$params['load_section'] = true;

		if(!array_key_exists('price_ids', $params) || !is_array($params['price_ids']))
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
			$element = CIBlockElement::GetByID($this->arParams['id'])->Fetch();
		else
			$element = CIBlockElement::GetList([], ['CODE' => $this->arParams['code']])->Fetch();

		if(!$element)
		{
			$this->errorCollection->setError(new Error("Елемент не найден.", self::ERROR_404));
			return $this->processErrors();
		}

		if($this->arParams['images'])
			foreach ($this->arParams['images'] as $imagePropCode => $variants)
				if(array_key_exists($imagePropCode, $element))
					$element[$imagePropCode] = $this->processImageProp($imagePropCode, $element[$imagePropCode]);

		if($this->arParams['props'])
			$element['PROPERTIES'] = $this->loadProperties($element['ID'], $element['IBLOCK_ID']);

		if($this->arParams['load_section'] && !empty($element['IBLOCK_SECTION_ID']))
			$element['SECTION'] = $this->loadSection($element['IBLOCK_SECTION_ID'], $element['IBLOCK_ID']);

		$element['PRICE'] = $this->getPrices($element['ID']);

		$this->arResult = $element;

		$this->includeComponentTemplate();
	}

	private function getPrices($elementId)
	{
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
		$properties = [$elementId => false];

		CIBlockElement::GetPropertyValuesArray($properties, $iblockId, [], ['CODE' => $this->arParams['props']]);

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
				elseif ($code == self::ERROR_TEXT)
				{
					ShowError($error->getMessage());
				}
			}
		}

		return false;
	}
}
