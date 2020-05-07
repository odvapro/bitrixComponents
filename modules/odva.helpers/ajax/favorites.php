<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use \Odva\Helpers\Favorites;

if(empty($_GET['action']) || !in_array($_GET['action'], ['add', 'get', 'delete','deleteAll']))
{
	echo json_encode([
		'success' => false,
		'error'   => [
			'type' => 'param',
			'msg'  => 'Необходимо передать параметр action. Возможные значения add, get, delete'
		]
	]);
	return;
}

switch ($_GET['action'])
{
	case 'add':
		$result = Favorites::add($_GET['id']);
		break;

	case 'delete':
		$result = Favorites::delete($_GET['id']);
		break;

	case 'get':
		$result = Favorites::get();
		break;
	case 'deleteAll':
		$result = Favorites::deleteAll();
		break;

	default:
		$result = [
			'success' => false,
			'error'   => [
				'type' => 'param',
				'msg'  => 'Не правильно передан параметр action'
			]
		];
		break;
}

echo json_encode($result);