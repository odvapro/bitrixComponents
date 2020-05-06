<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");

$arResult['SUMM']        = $this->getSumm();
$arResult['SUMM_FORMAT'] = FormatCurrency($arResult['SUMM'], 'RUB');
$arResult['COUNT']       = $this->getCount();
$arResult['PRODUCT_IDS'] = $this->getCartProductIds();
$arResult['CART_ARRAY']  = $this->getCartFromCookies();

$arResult['PRODUCT_COUNT_NAME'] = '';
if($arResult['COUNT'] == 1)
	$arResult['PRODUCT_COUNT_NAME'] = 'продукт';
elseif($arResult['COUNT'] < 5)
	$arResult['PRODUCT_COUNT_NAME'] = 'продукта';
else
	$arResult['PRODUCT_COUNT_NAME'] = 'продуктов';

$this->IncludeComponentTemplate();
