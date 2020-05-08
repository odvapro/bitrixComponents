<?php
if(empty($_POST))
{
	echo json_encode(['success' => false]);
	exit();
}
foreach ($_POST as $key => $value)
{
	if(empty($value))
	{
		echo json_encode(['success' => false]);
		exit();
	}
}
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");
global $USER;
use Bitrix\Main,
Bitrix\Main\Localization\Loc as Loc,
Bitrix\Main\Loader,
Bitrix\Main\Config\Option,
Bitrix\Sale\Delivery,
Bitrix\Sale\PaySystem,
Bitrix\Sale,
Bitrix\Sale\Order,
Bitrix\Sale\DiscountCouponsManager,
Bitrix\Main\Context;

function getPropertyByCode($propertyCollection, $code)  {
	foreach ($propertyCollection as $property)
	{
		if($property->getField('CODE') == $code)
			return $property;
	}
}
$userId = ($USER->GetID() != null)?$USER->GetID():1;
$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
$order = Bitrix\Sale\Order::create(SITE_ID, $userId);
$order->setPersonTypeId(1);
$order->setBasket($basket);


$propertyCollection = $order->getPropertyCollection();
$emailProperty = getPropertyByCode($propertyCollection, 'EMAIL');
$emailProperty->setValue($_POST["EMAIL"]);

$nameProperty = getPropertyByCode($propertyCollection, 'FIO');
$nameProperty->setValue($_POST['NAME']);

$phoneProperty = getPropertyByCode($propertyCollection, 'PHONE');
$phoneProperty->setValue($_POST['PHONE']);

$emailProperty = getPropertyByCode($propertyCollection, 'ADDRESS');
$emailProperty->setValue($_POST["ADDRESS"]);

$shipmentCollection = $order->getShipmentCollection();
$shipment = $shipmentCollection->createItem(
	Bitrix\Sale\Delivery\Services\Manager::getObjectById((int)$_POST['DOST_ID'])
);
$shipmentItemCollection = $shipment->getShipmentItemCollection();

foreach ($basket as $basketItem)
{
	$item = $shipmentItemCollection->createItem($basketItem);
	$item->setQuantity($basketItem->getQuantity());
};

$paymentCollection = $order->getPaymentCollection();
$payment = $paymentCollection->createItem(
	Bitrix\Sale\PaySystem\Manager::getObjectById(1)
);

$payment->setField("SUM", $order->getPrice());
$payment->setField("CURRENCY", $order->getCurrency());


$result = $order->save();
if (!$result->isSuccess())
{
   echo json_encode(['success' => false]);
}
else
{
	echo json_encode(['success' => true,'id' => $order->getId()]);
};