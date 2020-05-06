<?php
require_once 'function.php';
$arResult['RECOMMEND'] = [];
foreach ($arResult['PRODUCTS'] as &$item)
{
	$res = CIBlockElement::GetList([], ['ID'=>$item['PRODUCT_ID']], false, [], []);
	$ob = $res->GetNextElement();
	$arResult['RECOMMEND'] = array_merge($result['RECOMMEND'],$ob->getProperties()['RECOMMEND']['VALUE']);
	$item['IMG'] = $ob->GetFields()['DETAIL_PICTURE'];
	$item['IMG'] = CFile::ResizeImageGet($item['IMG'], ['width' => 240, 'height' => 240])['src'];
	$item['PRODUCTS_IN_STORAGE'] = CCatalogProduct::GetByID($item['PRODUCT_ID'])['QUANTITY'];
	$item['DETAIL_PAGE_URL'] = $ob->GetFields()['DETAIL_PAGE_URL'];
}
$arResult['STR_COUNT'] = declension($arResult['COUNT']['ITEMS'],['товар','товара','товаров']);
\Odva\Helpers\JsLib::registerExt($this);
?>