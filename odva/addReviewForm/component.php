<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("iblock");

$arResult['ADD_PATH'] = "$componentPath/add.php";

$this->IncludeComponentTemplate();
