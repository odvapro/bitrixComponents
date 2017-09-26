<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("iblock");
/**
 * Get params
 * filter   - bitrix arfilter
 * propertiesSettings - properties settings
 * count    - count elements on page
 */

$arResult["PRODUCTS"] = $this->getProducts($arParams["filter"],$arParams["propertiesSettings"],[
	'count' =>$arParams["count"],
	'page'  =>$arParams["page"],
	'sort'  =>$arParams["sort"],
]);

$arResult["SORTINGS"] = $this->sortings;

if(!empty($arParams['ajax']))
{
	ob_start();
	if(!empty($arParams['loadMore']))
		$this->IncludeComponentTemplate('ajaxLoadMore');
	else
		$this->IncludeComponentTemplate('ajaxFilter');
	$productsHtml = ob_get_clean();
	$nextPage     = (count($arResult["PRODUCTS"]) < $arParams["count"])?0:$arParams["page"]+1;
	echo json_encode(['success' => true, 'html'=>$productsHtml, 'nextpage'=>$nextPage]);
}
else
	$this->IncludeComponentTemplate();