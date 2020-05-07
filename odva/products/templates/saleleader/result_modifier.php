<?php

use Odva\Helpers\Formatters;

$favorites = \Odva\Helpers\Favorites::get();

foreach ($arResult['PRODUCTS'] as &$product)
{
	$product['IS_FAVORITE'] = in_array($product['ID'], $favorites);
	$product['HAS_BASKET'] = \Odva\Helpers\Basket::hasInBasket($product['ID']);

	$product['PRICE']['VALUE']    = Formatters::price($product['PRICE']['VALUE']);
	$product['PRICE']['DISCOUNT'] = false;

	if(!empty($product['PROPERTIES']['PRICE_OLD']['VALUE']) && !empty($product['PROPERTIES']['DISCOUNT']['VALUE']))
	{
		$product['PRICE'] = [
			'VALUE'    => Formatters::price($product['PROPERTIES']['PRICE_OLD']['VALUE']),
			'DISCOUNT' => [
				'PRICE'   => Formatters::price($product['PRICE']['VALUE']),
				'PERCENT' => $product['PROPERTIES']['DISCOUNT']['VALUE'],
				'VALUE'   => Formatters::price($product['PROPERTIES']['PRICE_OLD']['VALUE'] - $product['PRICE']['VALUE'])
			]
		];
	}
}