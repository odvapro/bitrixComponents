<?php
foreach ($arResult['PRODUCTS'] as $productKey => $product)
{
	include $_SERVER["DOCUMENT_ROOT"] . $templateFolder . '/catalog-products-element.php';
}