<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("iblock");

global $USER;
$arResult['AUTH'] = $USER->IsAuthorized();
if ($arResult['AUTH'])
{
	$arResult['USER'] = [
		'NAME' => $USER->GetFullName(),
		'PIC'  => AuthLine::getAvatar()
	];
}
$this->IncludeComponentTemplate();
