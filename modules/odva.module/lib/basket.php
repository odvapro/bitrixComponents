<?php

//обозначаем простарство имен модуля
namespace Odva\Module;

//подключаем классы работы с модулями и модуль для работы с интернет-магазином
use \Bitrix\Sale;
use \Bitrix\Main\Error;
use \Bitrix\Main\Loader;

//подключаем моудли для работы с интернет-магазином и моудль для работы с каталогом
Loader::includeModule("sale");
Loader::includeModule("catalog");

//объявляем класс для работы с корзиной
class Basket
{
	public static $basket = false;

	/**
	 * возвращает коллекцию элементов корзины в виде объекта
	 */
	public static function getItemsObject()
	{
		$basket = self::getBasket();
		return $basket->getBasketItems();
	}

	/**
	 * возвращает количество елементов в корзине
	 */
	public function getCount()
	{
		$basket = self::getBasket();
		return [
			'ITEMS'    => array_sum(array_values($basket->getQuantityList())),
			'PRODUCTS' => $basket->count(),
		];
	}

	/**
	 * достает цену корзины
	 */
	public function getPrice()
	{
		$basket = self::getBasket();
		return [
			'BASE'  => $basket->getBasePrice(),
			'PRICE' => $basket->getPrice()
		];
	}

	/**
	 * метод класса котоый добавляет количество товара в корзине
	 *
	 * @param int $productId id продукта
	 * @param int $quantity количество, может быть как положительным, так и отрицательным
	 */
	public function addItem($productId, $quantity)
	{
		$basket = self::getBasket();

		if ($item = $basket->getExistsItem('catalog', $productId))
		{
			$newQuantity = $item->getQuantity() + $quantity;

			if($newQuantity <= 0)
				$item->delete();
			else
				$item->setField('QUANTITY', $newQuantity);
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

	/**
	 * увеличение / уменьшение количества товара в корзине
	 * если количество будет <= 0, товар удалится из корзины
	 *
	 * @param int $productId id продукта
	 * @param int $quantity количество, может быть как положительным, так и отрицательным
	 */
	public function changeItemQuantity($productId, $quantity)
	{
		$basket = self::getBasket();
		$item   = $basket->getExistsItem('catalog', $productId);

		if(!$item)
			return new Error("Продукт #{$productId} в корзине не найден.");

		$newQuantity = $item->getQuantity() + $quantity;

		if($newQuantity <= 0)
			$item->delete();
		else
			$item->setField('QUANTITY', $newQuantity);

		return $basket->save();
	}

	/**
	 * метод класса котоый удаляет товар из корзины
	 *
	 * @param int $itemId id продукта, который надо удалить
	 */
	public function deleteItem($itemId)
	{
		$basket = self::getBasket();

		$item = $basket->getExistsItem('catalog', $itemId);

		if (!$item)
			return new Error("Продукт #{$itemId} в корзине не найден.");

		$item->delete();

		return $basket->save();
	}

	public function clear()
	{
		$basket = self::getBasket();
		$basket->clearCollection();
		return $basket->save();
	}

	public function getBasket()
	{
		if(self::$basket === false)
			self::$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), SITE_ID);
		return self::$basket;
	}

	/**
	 * проверка существования товара в корзине
	 *
	 * @param int $productId id товара
	 */
	public function hasInBasket($productId)
	{
		$basket = self::getBasket();
		return $basket->getExistsItem('catalog', $productId);
	}
}