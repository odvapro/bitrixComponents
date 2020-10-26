<?php

namespace Odva\Module;

use \Odva\Module\Model\OptionsTable;

class Option
{
	public static function get($code)
	{
		$option = OptionsTable::getList(['filter' => ['CODE' => $code]])->fetch();

		if(!$option)
			return false;

		return $option['VALUE'];
	}

	public static function set($code, $value)
	{
		$option = OptionsTable::getList(['filter' => ['CODE' => $code]])->fetch();

		if(!$option)
			return false;

		$result = OptionsTable::update($option['ID'], ['VALUE' => $value]);

		return $result->isSuccess();
	}
}