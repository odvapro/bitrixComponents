<?php
foreach ($arResult['OFFERS'] as $offerKey => $offer)
{
	$product = $offer['PRODUCT'];
	include $templateFolder~'/catalog-products-element.php';
}