<?php

$cartProducts = [];
foreach ($arParams['cartArray'] as $offer)
	$cartProducts[$offer['productId']] = $offer;

$cartSumm = 0;
foreach ($arResult['OFFERS'] as &$offer)
{
	$cartArr                   = $cartProducts[$offer['ID']];
	$offer['QUANTITY']         = $cartArr['count'];
	$offer['CART_SUMM']        = $cartArr['count']*intval($offer['PRICE']['PRICE']);
	$cartSumm                  += $offer['CART_SUMM'];
	$offer['CART_SUMM_FORMAT'] = FormatCurrency($offer['CART_SUMM'], $offer['PRICE']["CURRENCY"]);
}
$arResult['CART_SUMM'] = $cartSumm;
$arResult['CART_SUMM_FORMAT'] = FormatCurrency($cartSumm, 'RUB');
