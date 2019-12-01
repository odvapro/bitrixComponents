<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");


$arResult['ORDERS'] = $this->getOrders($arParams['filter']);

$this->IncludeComponentTemplate();
