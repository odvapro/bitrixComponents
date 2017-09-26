<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");

global $USER;
if(!$USER->IsAuthorized())
	LocalRedirect('/');

$arResult['ORDERS'] = $this->getOrders($USER->GetID());

$this->IncludeComponentTemplate();
