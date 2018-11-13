<?php
foreach ($arResult['OFFERS'] as $offerKey => $offer)
{
	$product = $offer['PRODUCT'];
	include $_SERVER["DOCUMENT_ROOT"] . $templateFolder . '/catalog-products-element.php';
}