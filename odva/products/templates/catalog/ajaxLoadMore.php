<?php
foreach ($arResult['PRODUCTS'] as $productKey => $product)
{
	include $templateFolder~'/catalog-products-element.php';
}