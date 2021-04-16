<?php

namespace Odva\Module;

use \Bitrix\Main\Loader;
use \Bitrix\Main\Error;

Loader::includeModule("sale");
Loader::includeModule("catalog");

class Order
{
	public function getBasketObject()
	{
		$basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());
		return $basket;
	}

	public function getBasket()
	{
		$info = \Odva\Module\Basket::getInfo();
		return $info;
	}

	public function getPaySystems()
	{
		$result = [];

		$dbPay = \CSalePaySystem::GetList(["SORT"=>"ASC", "PSA_NAME"=>"ASC"], ["ACTIVE"=>"Y"]);

		while ($item = $dbPay->Fetch())
			$result[$item['ID']] = $item;

		return $result;
	}

	public function getDeliveries()
	{
		$result = [];

		$dbDelivery = \Bitrix\Sale\Delivery\Services\Table::getList(['filter' => ['ACTIVE'=>'Y']]);

		while($delivery = $dbDelivery->fetch())
			$result[] = $delivery;

		return $result;
	}

	public function getLocationsByName($name, $count)
	{
		$result = [];

		$dbLocation = \Bitrix\Sale\Location\LocationTable::getList([
			'filter' => ['%NAME_RU' => $name,'=NAME.LANGUAGE_ID' => 'ru'],
			'select' => ['*', 'NAME_RU' => 'NAME.NAME', 'TYPE_CODE' => 'TYPE.CODE'],
			'limit'  =>	$count
		]);

		if(empty($dbLocation))
			return [];

		while ($location = $dbLocation->Fetch())
			$result[$location['ID']] = $location;

		return $result;
	}

	public function getUser()
	{
		global $USER;

		$favorit = \CUser::GetByID($USER->GetID());

		return $favorit->Fetch();
	}

	public function getUserProfiles($name = '', $count = 10)
	{
		global $USER;

		if(empty($USER->GetID()))
			return [];

		$result = [];

		$dbUserProps = \CSaleOrderUserProps::GetList(
			['DATE_UPDATE' => 'DESC'],
			[
				"USER_ID" => (int) $USER->GetID(),
				'NAME' => "%{$name}%"
			],
			false,
			['nTopCount' => $count],
			['*']
		);

		while ($arUserProps = $dbUserProps->Fetch())
		{
			$arResultTmp = $arUserProps;

			$orderPropertiesList = \CSaleOrderUserPropsValue::GetList([],['USER_PROPS_ID' => $arResultTmp['ID']]);
			while ($orderProperty = $orderPropertiesList->Fetch())
				$arResultTmp['USER_PROPS'][$orderProperty['PROP_ID']] = $orderProperty;

			$result[$arResultTmp['ID']] = $arResultTmp;
		}

		return $result;
	}

	public function getOrderProps($order)
	{
		return $order->getPropertyCollection()->getArray();
	}

	public function getOrderPrice($order)
	{
		$result = [];

		$result['PRICE_ORDER'] = $order->getPrice();
		$result['DELIVERY'] = $order->getDeliveryPrice();

		return $result;
	}

	protected function getDeliveryIds($order)
	{
		$result = [];

		foreach ($order->getShipmentCollection() as $shipment)
		{
			if (!$shipment->isSystem())
			{
				$deliveryIds = \Bitrix\Sale\Delivery\Services\Manager::getRestrictedObjectsList($shipment);

				foreach ($deliveryIds as $item)
				{
					$result[$item->getId()]['NAME'] = $item->getName();
					$result[$item->getId()]['ID'] = $item->getId();
					$result[$item->getId()]['CODE'] = $item->getCode();
					$result[$item->getId()]['DESCRIPTION'] = $item->getDescription();
					$result[$item->getId()]['CONFIG'] = $item->getConfigValues();
				}

				return $result;
			}
		}

		return $result;
	}

	public function getOrderCalculate($deliveryId, $paySystemId, $cityCode, $personTypeId)
	{
		global $USER;

		$userId = ($USER->GetID() != null)?$USER->GetID():1;
		$basket = self::getBasketObject();

		$order = \Bitrix\Sale\Order::create(SITE_ID, $userId);
		$order->setPersonTypeId((int)$personTypeId);
		$order->setBasket($basket);

		$propertyCollection = $order->getPropertyCollection();

		if($cityCode)
		{
			$orderDeliveryLocation = $propertyCollection->getDeliveryLocation();
			$orderDeliveryLocation->setValue($cityCode);
		}

		$shipmentCollection = $order->getShipmentCollection();

		$shipment = $shipmentCollection->createItem(
			\Bitrix\Sale\Delivery\Services\Manager::getObjectById((int)$deliveryId)
		);

		$shipmentItemCollection = $shipment->getShipmentItemCollection();

		foreach ($basket as $basketItem)
		{
			$item = $shipmentItemCollection->createItem($basketItem);
			$item->setQuantity($basketItem->getQuantity());
		};

		$paymentCollection = $order->getPaymentCollection();

		$payment = $paymentCollection->createItem(
			\Bitrix\Sale\PaySystem\Manager::getObjectById((int)$paySystemId)
		);

		$payment->setField("SUM", $order->getPrice());
		$payment->setField("CURRENCY", $order->getCurrency());

		$orderProps = self::getOrderProps($order);
		$price = self::getOrderPrice($order);
		$deliveries = self::getDeliveryIds($order);

		return ['ORDER_PROPS' => $orderProps, 'DELIVERIES'=> $deliveries, 'PRICE' => $price];
	}

	public function getPropertyByCode($propertyCollection, $code)
	{
		foreach ($propertyCollection as $property)
			if($property->getField('CODE') == $code)
				return $property;

		return false;
	}

	public function orderValidator($order, $props)
	{
		$errors = [];

		$orderProps = self::getOrderProps($order)['properties'];

		foreach ($orderProps as $item)
			if($item['REQUIRED'] == 'Y' && empty($item['DEFAULT_VALUE']) && empty($props[$item['CODE']]))
				$errors[] = $item['CODE'];

		return $errors;
	}

	public function makeOrder($deliveryId, $paySystemId, $props, $cityCode, $personTypeId)
	{
		global $USER;

		$userId = ($USER->GetID() != null)?$USER->GetID():1;
		$basket = self::getBasketObject();

		if(count($basket->getQuantityList()) < 1)
			return ['success' => false, 'code' => 'basket'];

		$order = \Bitrix\Sale\Order::create(SITE_ID, $userId);
		$order->setPersonTypeId((int)$personTypeId);
		$order->setBasket($basket);

		$propertyCollection = $order->getPropertyCollection();

		$orderDeliveryLocation = $propertyCollection->getDeliveryLocation();
		$orderDeliveryLocation->setValue($cityCode);

		foreach ($props as $key => $value)
		{
			$property = self::getPropertyByCode($propertyCollection, $key);

			if(!empty($property))
				$property->setValue($value);
		}

		$shipmentCollection = $order->getShipmentCollection();

		$shipment = $shipmentCollection->createItem(
			\Bitrix\Sale\Delivery\Services\Manager::getObjectById((int)$deliveryId)
		);

		$shipmentItemCollection = $shipment->getShipmentItemCollection();

		foreach ($basket as $basketItem)
		{
			$item = $shipmentItemCollection->createItem($basketItem);
			$item->setQuantity($basketItem->getQuantity());
		};

		$paymentCollection = $order->getPaymentCollection();

		$payment = $paymentCollection->createItem(
			\Bitrix\Sale\PaySystem\Manager::getObjectById((int)$paySystemId)
		);

		$payment->setField("SUM", $order->getPrice());
		$payment->setField("CURRENCY", $order->getCurrency());

		$errors = self::orderValidator($order, $props);

		if(!empty($errors))
			return ['success' => false, 'code' => 'validator', 'empty' => $errors];

		$result = $order->save();

		if (!$result->isSuccess())
			return ['success' => false, 'code' => 'order', $result];
		else
			return ['success' => true, 'id' => $order->getId()];
	}
}
