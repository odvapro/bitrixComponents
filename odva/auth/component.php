<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("iblock");

global $USER;
if($USER->IsAuthorized())
	return false;

$arResult['AUTH_SOCIAL_PATH'] = "$componentPath/authSocial.php";
$arResult['REG_BITRIX_PATH'] = "$componentPath/regBitrix.php";
$arResult['AUTH_BITRIX_PATH'] = "$componentPath/authBitrix.php";
$arResult['SEND_PASSWORD_BITRIX_PATH'] = "$componentPath/sendPassword.php";
$this->IncludeComponentTemplate();
