<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("iblock");
//проверка на то авторизован пользователь или нет, если нет то компоннт не подгружается
global $USER;
if($USER->IsAuthorized())
	return false;
//путь к файлу для авотризации/регистрации через соцсети
$arResult['AUTH_SOCIAL_PATH'] = "$componentPath/authSocial.php";
//путь к файлу для стандартной регистрации
$arResult['REG_BITRIX_PATH'] = "$componentPath/regBitrix.php";
//путь к файлу для стандартной авторизации
$arResult['AUTH_BITRIX_PATH'] = "$componentPath/authBitrix.php";
//путь к файлу для смены пароля и отправки его на почту
$arResult['SEND_PASSWORD_BITRIX_PATH'] = "$componentPath/sendPassword.php";
//вызов указаного в подклчючении компонента шаблона
$this->IncludeComponentTemplate();
