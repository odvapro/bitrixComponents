<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");

global $USER;
if(!$USER->IsAuthorized())
	LocalRedirect('/');

$rsUser = CUser::GetByID($USER->GetID());
$arUser = $rsUser->Fetch();

$arResult = $arUser;

$arResult['SAVE_PROFILE_PATH']       = "$componentPath/saveProfile.php";
$arResult['SAVE_PASSWORD_PATH']       = "$componentPath/savePassword.php";
$arResult['ADD_SOCIAL_NETWORK_PATH'] = "$componentPath/addSocialNetwork.php";

$this->IncludeComponentTemplate();
