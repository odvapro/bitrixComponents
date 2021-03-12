<?php
if(!isset($_POST['ID']))
{
	echo json_encode(
		['success' => false,
			'error'   => [
				'type' => 'param',
				'msg'  => 'Не правильно передан параметр ID'
			]
		]
	);
	exit();
}
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
use Odva\Helpers\Favorites;
$favorites = Favorites::get();
sort($favorites);
$lastFavorite = (int)$_POST['ID'];
$position = array_search($lastFavorite,$favorites);
$newItems = array_slice($favorites,$position+1,4);
$APPLICATION->IncludeComponent(
	'odva:products',
	'favorites',
	[
		 'filter' => [
				'IBLOCK_ID' => 2,
				'ACTIVE'    => 'Y',
				'ID' => $newItems,
			],
			'IS_LAST' => ($newItems[count($newItems)-1] == $favorites[count($favorites)-1])?'Y':'N',
			'count' => count($newItems),
	]
);

