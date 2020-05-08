<?php

//обозначаем простарство имен модуля
namespace Odva\Helpers;

//подключаем классы работы с модулями и модуль для работы с интернет-магазином
use \Bitrix\Sale;
use \Bitrix\Main\Loader;

//подключаем моудли для работы с интернет-магазином и моудль для работы с каталогом
Loader::includeModule("sale");
Loader::includeModule("catalog");

//объявляем класс для работы с корзиной
class Basket
{
	//свойство класса которое будет хранить в себе объект корзины
	public static $basket = false;

	//метод класса который возвращает елементы внутри корзины
	public static function getItems()
	{
		$basket = self::getBasket();
		return $basket->getBasketItems();
	}

	// метод класса который возвращает количество елементов в корзине
	public function getCount()
	{
		$basket = self::getBasket();
		return [
			'ITEMS'    => array_sum(array_values($basket->getQuantityList())),
			'PRODUCTS' => $basket->count(),
		];
	}
	//метод класса который достает цену корзины
	public function getPrice()
	{
		$basket = self::getBasket();
		return [
			'BASE' => $basket->getBasePrice(),
			'PRICE' => $basket->getPrice()
		];
	}

	//метод класса котоый добавляет товар в корзину
	//принимаемые параметры
	//$productId - id товара (елемента инфоблока)
	//$quantity - добавляемое количество
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

	//метод класса котоый изменяет количество товара в корзине
	//принимаемые параметры
	//$productId - id товара (елемента инфоблока)
	//$quantity - на какакое количество изменить количество товара
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

	//метод класса котоый удаляет товар из корзины
	//принимаемые параметры
	//$itemId - id елемента корзины
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

	//метод который возвращает корзину текущего пользователя
	public function getBasket()
	{
		if(self::$basket === false)
			self::$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), SITE_ID);
		return self::$basket;
	}

	//метод проверяющий существование товара в корзине
	//принимаемые параметры
	//$productId - id товара (елемента инфоблока)
	public function hasInBasket($productId)
	{
		$basket = self::getBasket();
		return $basket->getExistsItem('catalog', $productId);
	}
}