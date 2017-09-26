<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("iblock");

$arResult = $this->getSection($arParams['filter']);
if($arResult === false)
	LocalRedirect("/404.php", "404 Not Found");

$APPLICATION->SetTitle($arResult['NAME']);

if(!empty($arParams['ajax']))
	$this->IncludeComponentTemplate('ajax');
else
	$this->IncludeComponentTemplate();
