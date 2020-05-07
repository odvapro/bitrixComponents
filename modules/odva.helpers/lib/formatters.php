<?php

namespace Odva\Helpers;

class Formatters
{
	public function price($price, int $decimals = 2, string $dec_point = "." , string $thousands_sep = " ")
	{
		$price = preg_replace('/[^\d\.]/i', '', $price);
		$price = floatval($price);
		$price = number_format($price, $decimals, $dec_point, $thousands_sep);
		return str_replace('.00', '', $price);
	}
}