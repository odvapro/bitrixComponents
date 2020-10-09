<?php
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
$res = CIBlockElement::GetList([], ['IBLOCK_ID' => 5], false, [], []);
$pharmacies = [];
while($ob = $res->GetNextElement())
{
	$pharmacy = $ob->GetFields();
	$pharmacy['PROPERTIES'] = $ob->getProperties();
	$pharmacies[] =
	[
		'ID' => $pharmacy['ID'],
		'NAME' => $pharmacy['NAME'],
		'ADRES' => trim($pharmacy['PROPERTIES']['ADRESS']['~VALUE']),
		'X' => explode(",",$pharmacy['PROPERTIES']['LOCATION']['~VALUE'])[0],
		'Y' => explode(",",$pharmacy['PROPERTIES']['LOCATION']['~VALUE'])[1]
	];
}
echo json_encode($pharmacies);
