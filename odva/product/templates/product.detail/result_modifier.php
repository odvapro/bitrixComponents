<?php

use Odva\Helpers\Formatters;


// маштабирование картинок
$arResult['DETAIL_PICTURE'] = CFile::ResizeImageGet($arResult['DETAIL_PICTURE'], ['width' => 828, 'height' => 828])['src'];
foreach ($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'] as &$img)
{
	$img = CFile::ResizeImageGet($img, ['width' => 480, 'height' => 480])['src'];
}
// формирование хлебных крошек
$nav = CIBlockSection::GetNavChain(false,$arResult['IBLOCK_SECTION_ID']);
$arResult['SECTION_NAV'] = [['text' => 'Главная','url'  => '/'],['text' => 'Каталог','url'  => '/catalog/']];

while($arSectionPath = $nav->GetNext())
{
	$arResult['SECTION_NAV'][] = ['text' => $arSectionPath['NAME'],'url' => $arSectionPath['SECTION_PAGE_URL']];
}

$arResult['SECTION_NAV'][] = ['text' => $arResult['NAME'],'url' => $arResult['DETAIL_PAGE_URL']];

// установка title
$APPLICATION->SetTitle($arResult['NAME']." | ".CSite::GetByID("s1")->Fetch()['NAME']);

//установить товар как промотренный
\Bitrix\Catalog\CatalogViewedProductTable::refresh(
	$arResult['ID'],
 	CSaleBasket::GetBasketUserID()
);

//функция для склонения
function declension($number, array $data)
{
	$rest = array($number % 10, $number % 100);

	if($rest[1] > 10 && $rest[1] < 20)
	{
		return $data[2];
	}
	elseif ($rest[0] > 1 && $rest[0] < 5)
	{
		return $data[1];
	}
	else if ($rest[0] == 1)
	{
		return $data[0];
	}
	return $data[2];
}

//склонить возраст
$minAge = (int)$arResult['PROPERTIES']['MIN_AGE']['VALUE'];
if($minAge)
{
	$arResult['PROPERTIES']['MIN_AGE']['VALUE'] = $minAge." ".declension($minAge, ['год', 'года', 'лет']);
}
else
{
	$arResult['PROPERTIES']['MIN_AGE']['VALUE'] = false;
}

//склонить количество
$countItem = (int)$arResult['CATALOG_QUANTITY'];
if($countItem)
{
	$arResult['CATALOG_QUANTITY'] = $countItem." ".declension($countItem, ['штука', 'штуки', 'штук']);
}
else
{
	$arResult['CATALOG_QUANTITY'] = false;
}

//форматировать дату срока годности
if($arResult['PROPERTIES']['SHELF_LIFE']['VALUE'])
{
	$arDATE = ParseDateTime($arResult['PROPERTIES']['SHELF_LIFE']['VALUE'], FORMAT_DATETIME);
	$arResult['SHELF_LIFE'] = $arDATE;
	$arResult['SHELF_LIFE']['MM'] = ToLower(GetMessage("MONTH_".intval($arDATE["MM"])."_S"));
}
else
{
	$arResult['SHELF_LIFE'] = false;
}

//проверка на то есть ли товар в избранном и добавлен ли он в корзину
$favorites = \Odva\Helpers\Favorites::get();

$arResult['IS_FAVORITE'] = in_array($arResult['ID'], $favorites);
$arResult['HAS_BASKET'] = \Odva\Helpers\Basket::hasInBasket($arResult['ID']);

$arResult['PRICE']['VALUE']    = Formatters::price($arResult['PRICE']['VALUE']);
$arResult['PRICE']['DISCOUNT'] = false;

if(!empty($arResult['PROPERTIES']['PRICE_OLD']['VALUE']) && !empty($arResult['PROPERTIES']['DISCOUNT']['VALUE']))
{
	$arResult['PRICE'] = [
		'VALUE'    => Formatters::price($arResult['PROPERTIES']['PRICE_OLD']['VALUE']),
		'DISCOUNT' => [
			'PRICE'   => Formatters::price($arResult['PRICE']['VALUE']),
			'PERCENT' => $arResult['PROPERTIES']['DISCOUNT']['VALUE'],
			'VALUE'   => Formatters::price($arResult['PROPERTIES']['PRICE_OLD']['VALUE'] - $arResult['PRICE']['VALUE'])
		]
	];
}

//приготовить дату для доставки
//дата для самовывоза
$data = date('d.m.Y');
$arDATE = ParseDateTime($data, FORMAT_DATETIME);
$dataNow = $arDATE;
$dataNow['MM'] = ToLower(GetMessage("MONTH_".intval($arDATE["MM"])."_S"));
$arResult['TAKE_YOURSELF_DATE'] = "{$dataNow['DD']} {$dataNow['MM']}";

//дата для доставки
$dataLastStart = date('d.m.Y', strtotime("+3 days"));
$arDATE = ParseDateTime($dataLastStart, FORMAT_DATETIME);
$dataLastStart = $arDATE;
$dataLastStart['MM'] = ToLower(GetMessage("MONTH_".intval($arDATE["MM"])."_S"));

$dataLastEnd = date('d.m.Y', strtotime("+5 days"));
$arDATE = ParseDateTime($dataLastEnd, FORMAT_DATETIME);
$dataLastEnd = $arDATE;
$dataLastEnd['MM'] = ToLower(GetMessage("MONTH_".intval($arDATE["MM"])."_S"));

$arResult['DELIVERY_DATE'] = "";
if($dataLastStart['MM'] != $dataLastEnd['MM'])
{
	$arResult['DELIVERY_DATE'] = "{$dataLastStart['DD']} {$dataLastStart['MM']} - {$dataLastEnd['DD']} {$dataLastEnd['MM']}";
}
else
{
	$arResult['DELIVERY_DATE'] = "{$dataLastStart['DD']} - {$dataLastEnd['DD']} {$dataLastStart['MM']}";
}

if(!empty($arResult['PROPERTIES']['BRAND_REF']['VALUE']))
{
	$arResult['PROPERTIES']['BRAND_REF']['LINK'] = "{$arResult['SECTION']['SECTION_PAGE_URL']}filter/brand/{$arResult['PROPERTIES']['BRAND_REF']['VALUE']}/apply/";
}