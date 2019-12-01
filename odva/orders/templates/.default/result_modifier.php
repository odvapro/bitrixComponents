<?php
foreach ($arResult['ORDERS'] as &$order)
{
	foreach ($order['PRODUCTS'] as &$product)
	{
		$sostavArr = [];
		foreach ($product['PROPERTIES'] as $property)
		{
			if(strpos($property['CODE'], 'LANCH_') !== false)
				$sostavArr[] = $property['NAME'].": ".$property['VALUE'];
		}

		$product['SOSTAV'] = $sostavArr;
	}
}