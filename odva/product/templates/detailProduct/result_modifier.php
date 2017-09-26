<?php
if(!count($arResult['OFFERS'])) return false;
$jsonOffers = [];
foreach ($arResult['OFFERS'] as $offer)
{
	$jsonOffers[$offer['ID']] = [
		'id'          => $offer['ID'],
		'price'       => intval($offer['PRICE']['PRICE']),
		'priceFormat' => $offer['PRICE']['FORMAT_PRICE'],
	];
}
$jsonOffers = json_encode($jsonOffers);
$arResult['JSON_OFFERS'] = $jsonOffers;

// properties
$propertiesArr  = [
	['name'=>'Шлифование','code'=>'SHLIFOVANIE'],
	['name'=>'Точение','code'=>'TOCHENIE'],
	['name'=>'Сверление','code'=>'SVERLENIE'],
	['name'=>'Фрезерование','code'=>'FREZEROVANIE'],
	['name'=>'Отрезка дисковыми пилами','code'=>'OTREZKA_DISKOVIMI_PLITAMI'],
	['name'=>'Развертывание','code'=>'RAZVERTIVANIE'],
	['name'=>'Нарезание резьбы','code'=>'NAREZNIE_RESBI'],
	['name'=>'Глубинное сверление','code'=>'GLUBINNOE_SVERLENIE'],
	['name'=>'Отрезка ленточными пилами','code'=>'OTREZKA_LENTOCHNIMY_PILAMI'],
	['name'=>'Волочение проволоки','code'=>'VOLOCHENIE_PROVOLOKI'],
	['name'=>'Волочение труб','code'=>'VOLOCHENIE_TRUB'],
	['name'=>'Волочение профилей','code'=>'VOLOCHENIE_PROFILEY'],
	['name'=>'Прокатка труб','code'=>'PROKATKA_TRUB'],
	['name'=>'Прокатка листов','code'=>'PROKATKA_LISTOV'],
	['name'=>'Прокатка профилей','code'=>'PROKATKA_PROFILEY'],
	['name'=>'Гидроиспытания','code'=>'GIDROISPITANIA'],
	['name'=>'Штамповка','code'=>'SHTAMPOVKA'],
	['name'=>'Резьбонакатка','code'=>'RESBONAKATKA'],
];

$showProperties = [];
foreach ($propertiesArr as $propArr)
{
	if(!array_key_exists($propArr['code'], $arResult['PROPERTIES']) || empty($arResult['PROPERTIES'][$propArr['code']]['VALUE']))
		continue;
	$showProperties[] = [
		'name'  => $propArr['name'],
		'value' => $arResult['PROPERTIES'][$propArr['code']]['VALUE']
	];
}
$arResult['SHOW_PROPERTIES'] = $showProperties;
