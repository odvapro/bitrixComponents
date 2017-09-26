<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("iblock");
/**
 * Get params
 * filter   - bitrix arfilter
 * propertiesSettings - properties settings
 * count    - count elements on page
 */

// формируем фиьлтр протудктов
// филтруем продукты
// формируем список ид продуктов
// передаем его в фильтр офферов
$productIds = $this->getProducIds($arParams["productsFilter"]);
$offers     = [];
if(count($productIds) > 0)
{
	$offersFilter = array_merge(['PROPERTY_CML2_LINK'=>$productIds],$arParams['offersFilter']);
	$offers = $this->getOffers($offersFilter,[
		'count'                     =>$arParams["count"],
		'page'                      =>$arParams["page"],
		'sort'                      =>$arParams["sort"],
		'productPropertiesSettings' =>$arParams['productPropertiesSettings'],
		'offerPropertiesSettings'   =>$arParams['offerPropertiesSettings']
	]);
}
$arResult["OFFERS"] = $offers;

$arResult["SORTINGS"] = $this->sortings;
if(!empty($arParams['ajax']))
{
	ob_start();
	if(!empty($arParams['loadMore']))
		$this->IncludeComponentTemplate('ajaxLoadMore');
	else
		$this->IncludeComponentTemplate('ajaxFilter');
	$offersHtml = ob_get_clean();
	$nextPage     = (count($arResult["OFFERS"]) < $arParams["count"])?0:$arParams["page"]+1;
	echo json_encode(['success' => true, 'html'=>$offersHtml, 'nextpage'=>$nextPage]);
}
else
	$this->IncludeComponentTemplate();