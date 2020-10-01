<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Error;
use \Bitrix\Main\ErrorCollection;

class OrderDetail extends \CBitrixComponent
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
		if(!Loader::includeModule('iblock') || !Loader::includeModule("sale"))
			return false;

		if(!array_key_exists('id', $params))
		{
			$this->errorCollection->setError(new Error('Параметр "id" должен иметь числовое значение!', self::ERROR_TEXT));
			return $params;
		}

		if(array_key_exists('id', $params) && ((int)$params['id'] <= 0 || (int)$params['id'] != $params['id']))
		{
			$this->errorCollection->setError(new Error('Параметр "id" должен иметь числовое значение!', self::ERROR_TEXT));
			return $params;
		}

		if(!array_key_exists('id', $params))
			$params['id'] = false;

		if(!array_key_exists('product_fields', $params) || !is_array($params['product_fields']))
			$params['product_fields'] = false;

		if(!array_key_exists('product_properties', $params) || !is_array($params['product_properties']))
			$params['product_properties'] = false;

		if(
			!array_key_exists('images', $arParams)
			||
			empty($arParams['images'])
			||
			array_keys($arParams['images']) === range(0, count($arParams['images']) - 1)
		)
			$arParams['images'] = false;


		return $params;
	}

	public function executeComponent()
	{
		if ($this->hasErrors())
			return $this->processErrors();

		global $USER;
		$order=\Bitrix\Sale\Order::load($this->arParams['id']);

		if(empty($order))
		{
			$this->errorCollection->setError(new Error("Елемент не найден.", self::ERROR_404));
			return $this->processErrors();
		}

		if($order->getUserId() !== $USER->GetID())
		{
			$this->errorCollection->setError(new Error("Елемент не найден.", self::ERROR_404));
			return $this->processErrors();
		}

		$basket=$order->getBasket();

		if(empty($basket->getBasketItems()))
		{
			$this->errorCollection->setError(new Error("Елемент не найден.", self::ERROR_404));
			return $this->processErrors();
		}

		$basketItems = $basket->getBasketItems();

		$this->arResult['ORDER_ID']= $order->getId();
		$this->arResult['STATUS_ID'] = $order->getField("STATUS_ID");
		$this->arResult['BASE_PRICE']	= $basket->getBasePrice();
		$this->arResult['DISCOUNT_PRICE'] = $basket->getPrice();
		$this->arResult['DISCOUNT'] = $this->arResult['BASE_PRICE'] - $this->arResult['DISCOUNT_PRICE'];

		foreach ($basketItems as $item)
		{
			$product['ID']                  = $item->getField("ID");
			$product['NAME']                = $item->getField("NAME");
			$product['PRODUCT_ID']          = $item->getField("PRODUCT_ID");
			$product['QUANTITY']            = $item->getField("QUANTITY");
			$product['BASE_PRICE']          = $item->getField("BASE_PRICE");
			$product['BASE_PRICE_SUMM']     = $product['BASE_PRICE']*$product['QUANTITY'];
			$product['DISCOUNT_PRICE']      = $item->getField("PRICE");
			$product['DISCOUNT_PRICE_SUMM'] = $product['DISCOUNT_PRICE']*$product['QUANTITY'];
			$product['DETAIL_PAGE_URL']     = $item->getField("DETAIL_PAGE_URL");
			$productId[$product['ID']]      = $product['PRODUCT_ID'];

			$this->arResult['PRODUCTS'][$product['ID']] = $product;
		}

		if($this->arParams['product_fields'] || $this->arParams['product_properties'])
			$this->loadProperties($productId);

		$this->includeComponentTemplate();
	}

	private function loadProperties($productId)
	{
		$selectProperties =[];
		$selectFields = [];
		$selectImages = [];
		if($this->arParams['product_fields'])
			$selectFields = $this->arParams['product_fields'];

		if($this->arParams['images'])
			$selectImages = array_keys($this->arParams['images']);

		foreach ($this->arParams['product_properties'] as $value)
		{
			$selectProperties[] = "PROPERTY_{$value}";
		}
		$arSelect = array_merge(['ID'], $selectProperties, $selectFields,$selectImages);
		$properties = CIBlockElement::GetList([], ['ID' => $productId], false, [], $arSelect);
		while($property = $properties->GetNextElement())
		{
			$arFields = $property->GetFields();
			$this->arResult['PRODUCTS'][array_keys($productId, $arFields['ID'])[0]]['PROPERTY'] = $this->processElement($arFields);
		}
	}
	private function processElement($element)
	{
		// подгрузка путей изображений в поля типа DETAIL_PICTURE и PREVIEW_PICTURE
		if($this->arParams['images'])
			foreach ($this->arParams['images'] as $imagePropCode => $variants)
				if(array_key_exists($imagePropCode, $element))
					$element[$imagePropCode] = $this->processImageProp($imagePropCode, $element[$imagePropCode]);
		return $element;
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
