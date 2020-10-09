<?php
require_once 'function.php';
global $USER;

if(count($arResult['ITEMS']) == 0)
{
	LocalRedirect("/cart/");
}

$res = CIBlockElement::GetList([], ['IBLOCK_ID' => 5], false, [], []);
$pharmacies = [];

while($ob = $res->GetNextElement())
{
	$pharmacy = $ob->GetFields();
	$pharmacy['PROPERTIES'] = $ob->getProperties();
	if($pharmacy['PROPERTIES']['GIVE_TODAY']['VALUE'] == 'да')
	{
		$pharmacy['WHEN_GIVE'] = 'Можно забрать через 10 минут';
	}
	else
	{
		if(Date("H") >= 7 || Date("H") <= 17)
		{
			$pharmacy['WHEN_GIVE'] = 'Забрать можно через 2 часа';
		}
		else
		{
			$pharmacy['WHEN_GIVE'] = 'Забрать можно через 8 часа';
		}
	}
	$pharmacies[] = $pharmacy;
}

$arResult['STR_COUNT'] = declension($arResult['COUNT_PRODUCTS'],['товар','товара','товаров']);
$arResult['PHARMACIES'] = $pharmacies;

if($USER->IsAuthorized())
{
	$rsUser = CUser::GetByID($USER->GetID());
	$arResult['USER'] = $rsUser->Fetch();
}
