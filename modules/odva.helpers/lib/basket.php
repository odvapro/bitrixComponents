<?php

namespace Odva\Helpers;

use \Bitrix\Sale;
use \Bitrix\Main\Loader;

Loader::includeModule("sale");
Loader::includeModule("catalog");

class Basket
{
	public static $basket = false;

	public static function getItems()
	{
		$basket = self::getBasket();
		return $basket->getBasketItems();
	}

	public function getCount()
	{
		$basket = self::getBasket();
		return [
			'ITEMS'    => array_sum(array_values($basket->getQuantityList())),
			'PRODUCTS' => $basket->count(),
		];
	}

	public function getPrice()
	{
		$basket = self::getBasket();
		return [
			'BASE' => $basket->getBasePrice(),
			'PRICE' => $basket->getPrice()
		];
	}

	public function addItem($productId, $quantity)
	{
		$basket = self::getBasket();

		if ($item = $basket->getExistsItem('catalog', $productId))
		{
			$item->setField('QUANTITY', $item->getQuantity() + $quantity);
		}
		else
		{
			$item = $basket->createItem('catalog', $productId);
			$item->setFields([
				'QUANTITY'               => $quantity,
				'CURRENCY'               => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
				'LID'                    => \Bitrix\Main\Context::getCurrent()->getSite(),
				'PRODUCT_PROVIDER_CLASS' => \Bitrix\Catalog\Product\Basket::getDefaultProviderName() ,
			]);
		}

		return $basket->save();
	}

	public function changeItem($productId, $quantity)
	{
		$basket = self::getBasket();
		$result = false;
		if ($item = $basket->getExistsItem('catalog', $productId))
		{
			$result = $item->setField('QUANTITY', $quantity);
		}
		else
		{
			$item = $basket->createItem('catalog', $productId);
			$item->setFields([
				'QUANTITY'               => $quantity,
				'CURRENCY'               => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
				'LID'                    => \Bitrix\Main\Context::getCurrent()->getSite(),
				'PRODUCT_PROVIDER_CLASS' => \Bitrix\Catalog\Product\Basket::getDefaultProviderName() ,
			]);
		}
		$basket->save();
		return $result;
	}

	public function deleteItem($itemId)
	{
		$basket = self::getBasket();
		$result = false;
		if ($item = $basket->getItemById($itemId))
		{
			$result = $item->delete();
		}
		$basket->save();
		return $result;
	}

	public function getBasket()
	{
		if(self::$basket === false)
			self::$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), SITE_ID);
		return self::$basket;
	}

	public function hasInBasket($productId)
	{
		$basket = self::getBasket();
		return $basket->getExistsItem('catalog', $productId);
	}
}