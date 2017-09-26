<?php
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
include 'class.php';
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");

// проверить валидность данных
$needFields = ['name', 'email', 'phone', 'city', 'address', 'delivery', 'payment'];
foreach ($needFields as $field)
{
	if(empty($_POST[$field]))
	{
		echo json_encode(['success'=>false,'msg'=>'not all fields']);
		die();
	}
}

// достать все товары корзины
$cartArray = (!empty($_COOKIE['cart']))?json_decode($_COOKIE['cart'],true):[];
if(!count($cartArray))
{
	echo json_encode(['success'=>false,'msg'=>'no products']);
	die();
}

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
	['id'=>3,'name'=>'Email','code'=>'EMAIL','value'=>$_POST['email']],
	['id'=>4,'name'=>'Город','code'=>'CITY','value'=>$_POST['city']],
	['id'=>5,'name'=>'Город','code'=>'ADDRESS','value'=>$_POST['address']],
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

// updateOrderParams

echo json_encode([
	'success' =>true,
	'orderId' =>$orderId
]);
