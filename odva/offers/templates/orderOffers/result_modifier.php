<?php
if(empty($arParams['orderProducts'])) return true;

foreach ($arResult['OFFERS'] as &$offer)
{
	$productInOrder = $arParams['orderProducts'][$offer['ID']];
	$offer['QUANTITY'] = $productInOrder['QUANTITY'];
}
