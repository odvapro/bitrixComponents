<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");

global $USER;
if(!$USER->IsAuthorized())
	LocalRedirect('/');

$rsUser = CUser::GetByID($USER->GetID());
$arUser = $rsUser->Fetch();

$arResult = [
	'NAME'          => $USER->GetFullName(),
	'PIC'           => AuthLine::getAvatar(),
	'EMAIL'         => $USER->GetEmail(),
	'PHONE'         => $arUser['PERSONAL_PHONE'],
	'ADDRESS'       => $arUser['PERSONAL_STREET'],
	'FACEBOOK'      => $arUser['UF_FACEBOOK'],
	'VKONTAKTE'     => $arUser['UF_VK'],
	'ODNOKLASSNIKI' => $arUser['UF_OK'],
	'CITY'          => $arUser['PERSONAL_CITY'],
	'CITIES'        => $this->getCities(),
];

$arResult['SAVE_PROFILE_PATH']       = "$componentPath/saveProfile.php";
$arResult['ADD_SOCIAL_NETWORK_PATH'] = "$componentPath/addSocialNetwork.php";

$this->IncludeComponentTemplate();
