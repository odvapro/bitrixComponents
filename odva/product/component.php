<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("iblock");

$arResult = $this->getProduct($arParams["filter"],$arParams['propertiesSettings']);

if($arResult === false)
	LocalRedirect('/404.php');

if(!empty($arParams['setTitle']))
	$APPLICATION->SetTitle($arResult['NAME']);

if(!empty($arParams['offersFilter']))
{
	$offersFilter = array_merge(['PROPERTY_CML2_LINK'=> $arResult['ID']],$arParams["offersFilter"]);
	$arResult['OFFERS'] = $this->getOffers($offersFilter);
}

// select default offer
if(count($arResult['OFFERS']))
{
	$arResult['OFFERS'][0]['SELECTED'] = true;
	$arResult['SELECTED_PRICE'] = $arResult['OFFERS'][0]['PRICE']['FORMAT_PRICE'];
}

$this->IncludeComponentTemplate();
