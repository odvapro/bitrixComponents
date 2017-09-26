<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("iblock");

global $USER;
if($USER->IsAuthorized())
	return false;

$arResult['AUTH_PATH'] = "$componentPath/auth.php";

$this->IncludeComponentTemplate();
