<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("iblock");
$arResult['PATH_ADD_SUBSCRIBERS'] = "$componentPath/addSubcscribers.php";
$this->IncludeComponentTemplate();