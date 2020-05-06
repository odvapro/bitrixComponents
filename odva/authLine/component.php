<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("iblock");

global $USER;
// достаю статуст авторизации пользователя
$arResult['AUTH'] = $USER->IsAuthorized();
// путь к файлу для разлогинизации
$arResult['LOGOUT_AJAX_PATH'] = "$componentPath/ajax.loguot.php";
//если авторизован достать его(пользователя) email и аватарку из gravatar
if ($arResult['AUTH'])
{
	$arResult['USER'] = [
		'EMAIL' => $USER->GetEmail(),
		'PIC'  => AuthLine::getAvatar()
	];
}
//вызов указанного в подключении шаблона
$this->IncludeComponentTemplate();
