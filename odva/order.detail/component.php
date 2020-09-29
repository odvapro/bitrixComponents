<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
use Bitrix\Sale;

$order=\Bitrix\Sale\Order::load($arParams['ID']);
$basket=$order->getBasket();
$productImtem= $basket->getBasketItems();

$arResult['ORDER_ID']= $order->getId();
$arResult['BASE_PRICE']	= $basket->getBasePrice();
$arResult['PRICE'] = $basket->getPrice();
$arResult['STATUS_ID'] = $order->getField("STATUS_ID");
$result = [];
		foreach ($productImtem as $productImtem)
		{
			$item['ID'] 			= $productImtem->getField("ID");
			$item['NAME'] 			= $productImtem->getField("NAME");
			$item['PRODUCT_ID'] 	= $productImtem->getField("PRODUCT_ID");
			$item['PRICE'] 			= $productImtem->getField("PRICE");
			$item['QUANTITY'] 		= $productImtem->getField("QUANTITY");
			$item['BASE_PRICE'] 	= $productImtem->getField("BASE_PRICE");
			$item['DETAIL_PAGE_URL']= $productImtem->getField("DETAIL_PAGE_URL");
			$res = CIBlockElement::GetList([], ['ID'=>$item['PRODUCT_ID']], false, [], []);
			$ob = $res->GetNextElement();
			$item['IMG'] 			= CFile::GetPath($ob->GetFields()['PREVIEW_PICTURE']);
			if(!$item['IMG'])
				$item['IMG']		= '/front/src/assets/img/no_photo.png';
			$result[$item['ID']] 	= $item;

			$arResult['DISCOUNT_PRICE'] += $productImtem->getField('DISCOUNT_PRICE')*$item['QUANTITY'];
			$arResult['QUANTITY']  += $item['QUANTITY'];
		}
$arResult['PRODUCTS']=$result;

if($order->getUserId() !== $USER->GetID() || empty($arResult['PRODUCTS']) )
	LocalRedirect('/404.php');

$this->IncludeComponentTemplate();