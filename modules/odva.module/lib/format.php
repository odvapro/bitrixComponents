<?php

namespace Odva\Module;
//класс для форматирования
class Format
{
	//форматирования цены
	public function price($price, int $decimals = 2, string $dec_point = "." , string $thousands_sep = " ")
	{
		//достаем число из строки
		$price = preg_replace('/[^\d\.]/i', '', $price);
		//конвертируем его в число с плавающей запятой
		$price = floatval($price);
		//групирую число
		$price = number_format($price, $decimals, $dec_point, $thousands_sep);
		//округляем его до челого и возращаем
		return str_replace('.00', '', $price);
	}
}