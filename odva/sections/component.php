<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("iblock");

$arResult["SECTIONS"] = $this->getSections($arParams["filter"],$arParams["count"]);

$this->IncludeComponentTemplate();
