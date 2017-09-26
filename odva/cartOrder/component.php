<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");


// проверим пустоту корзины
if($this->isCartEmpty() && empty($arParams['no_redirect']))
	LocalRedirect('/cart/');

$arResult['CITIES']                   = $this->getCities();
$arResult['MAKE_ORDER_PATH']          = "$componentPath/makeOrder.php";
$arResult['MAKE_ONECLICK_ORDER_PATH'] = "$componentPath/makeOneclickOrder.php";

// user info
$arResult['USER'] = ['NAME' => '', 'PIC' => '', 'EMAIL' => '', 'PHONE' => '', 'ADDRESS' => ''];
global $USER;
if($USER->IsAuthorized())
{
	$rsUser = CUser::GetByID($USER->GetID());
	$arUser = $rsUser->Fetch();
	$arResult['USER'] = [
		'NAME'          => $USER->GetFullName(),
		'EMAIL'         => $USER->GetEmail(),
		'PHONE'         => $arUser['PERSONAL_PHONE'],
		'ADDRESS'       => $arUser['PERSONAL_STREET']
	];
}

$this->IncludeComponentTemplate();
