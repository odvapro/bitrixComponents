<?php
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
include 'class.php';
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");

// проверить валидность данных
$needFields = ['name',  'phone', 'product'];
foreach ($needFields as $field)
{
	if(empty($_POST[$field]))
	{
		echo json_encode(['success'=>false,'msg'=>'not all fields']);
		die();
	}
}

// достать все товары корзины
$cartArray = [$_POST['product']];

CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());
global $USER;
$orderId = CartOrder::makeEmptyOrder([
	'DELIVERY_ID'   => $_POST['delivery'],
	'PAY_SYSTEM_ID' => $_POST['payment'],
	'USER_ID'       => ($USER->IsAuthorized())?$USER->GetID():1,
]);
if(empty($orderId))
{
	echo json_encode(['success'=>false,'msg'=>'faild order']);
	die();
}

// set order props
CartOrder::setOrderProps($orderId,[
	['id'=>1,'name'=>'ФИО','code'=>'NAME','value'=>$_POST['name']],
	['id'=>2,'name'=>'Телефон','code'=>'PHONE','value'=>$_POST['phone']],
]);

// add order products
foreach ($cartArray as $product)
{
	if(!CartOrder::addProductToOrder($orderId,[
		'id'       =>$product['productId'],
		'quantity' =>$product['count']
	]))
	{
		echo json_encode(['success'=>false,'msg'=>'cant add product']);
		die();
	}

}

echo json_encode([
	'success' =>true,
	'orderId' =>$orderId
]);
