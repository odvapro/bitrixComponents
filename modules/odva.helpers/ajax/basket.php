<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if(empty($_GET['action']) || !in_array($_GET['action'], ['add', 'chg', 'get', 'delete']))
{
	echo json_encode([
		'success' => false,
		'error'   => [
			'type' => 'param',
			'msg'  => 'Необходимо передать параметр action. Возможные значения add'
		]
	]);
	return;
}

if($_GET['action'] == 'add')
{
	if(empty($_GET['productId']) || $_GET['productId'] <= 0)
	{
		echo json_encode(['success' => false, 'error' => ['type' => 'param', 'msg' => 'Не передан параметр productId']]);
		exit();
	}

	if(empty(intval($_GET['quantity'])) || intval($_GET['quantity']) <= 0)
		$quantity = 1;
	else
		$quantity = intval($_GET['quantity']);

	$adding = \Odva\Helpers\Basket::addItem($productId, $quantity);

	if(!$adding->isSuccess())
	{
		echo json_encode([
			'success' => false,
			'error' => [
				'type' => 'bitrix',
				'msg' => 'Не удалось добавить товар в корзину'
			]
		]);
		exit();
	}

	echo json_encode(['success' => true, 'count' => \Odva\Helpers\Basket::getCount(),'productId' => $productId]);
}

if($_GET['action'] == 'chg')
{
	if(empty($_GET['productId']) || $_GET['productId'] <= 0)
	{
		echo json_encode(['success' => false, 'error' => ['type' => 'param', 'msg' => 'Не передан параметр productId']]);
		exit();
	}

	if(empty(intval($_GET['quantity'])) || intval($_GET['quantity']) <= 0)
		$quantity = 1;
	else
		$quantity = intval($_GET['quantity']);

	$adding = \Odva\Helpers\Basket::changeItem($productId, $quantity);

	if(!$adding->isSuccess())
	{
		echo json_encode([
			'success' => false,
			'error' => [
				'type' => 'bitrix',
				'msg' => 'Не удалось изменить кооличество товара в корзине'
			]
		]);
		exit();
	}

	echo json_encode(['success' => true, 'count' => \Odva\Helpers\Basket::getCount()]);
}

if($_GET['action'] == 'delete')
{
	if(empty($_GET['itemId']) || $_GET['itemId'] <= 0)
	{
		echo json_encode(['success' => false, 'error' => ['type' => 'param', 'msg' => 'Не передан параметр itemId']]);
		exit();
	}

	$adding = \Odva\Helpers\Basket::deleteItem($itemId);

	if(!$adding->isSuccess())
	{
		echo json_encode([
			'success' => false,
			'error' => [
				'type' => 'bitrix',
				'msg' => 'Не удалось удалить товар из корзины'
			]
		]);
		exit();
	}

	echo json_encode(['success' => true, 'count' => \Odva\Helpers\Basket::getCount()]);
}