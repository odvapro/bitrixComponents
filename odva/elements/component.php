<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("iblock");

// section code condition
if(!empty($arParams['filter']['SECTION_CODE']))
{
	$section = $this->getSectionByCode($arParams['filter']['SECTION_CODE']);
	if($section !== false)
		$arParams['filter']['SECTION_ID'] = $section['ID'];
}
$codeIndex = array_search("SECTION_CODE",array_keys($arParams['filter']));
if($codeIndex !== false)
	array_splice($arParams['filter'], $codeIndex,1);
$arResult = $this->getElements($arParams['sort'],$arParams['filter'],$arParams['count'],$arParams['PAGE'],$arParams["TEMPLATE_PAGN"]);

$this->IncludeComponentTemplate();
