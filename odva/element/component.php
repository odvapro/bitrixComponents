<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("iblock");

$arResult = $this->getElement($arParams['filter'],$arParams['propertiesSettings']);
if($arResult === false)
{
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
	return;
}

$this->IncludeComponentTemplate();
