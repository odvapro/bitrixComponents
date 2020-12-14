<?php

class ElementsParamsValidator
{
	public static function validate($key, $array, $validators, $default = false)
	{
		$condition = true;

		$res = [];

		foreach ($validators as $validator)
		{
			$invert = preg_match('/^!.*/', $validator);

			$validatorMethod = str_replace('!', '', $validator);

			if(!method_exists(self::class, $validatorMethod))
				continue;

			$validate = self::$validatorMethod($key, $array);

			if($invert)
				$validate = !$validate;

			$condition &= $validate;

			$res[$validator] = $validate;
		}

		if($condition)
			return $array[$key];

		return $default;
	}

	private static function is_numeric($key, $array)
	{
		return is_numeric($array[$key]);
	}

	private static function boolval($key, $array)
	{
		return (array_key_exists($key, $array) && $array[$key]);
	}

	private static function not($key, $array)
	{
		return !$array[$key];
	}

	private static function empty($key, $array)
	{
		return empty($array[$key]);
	}

	private static function is_key_exists($key, $array)
	{
		return array_key_exists($key, $array);
	}

	private static function is_array($key, $array)
	{
		return is_array($array[$key]);
	}

	private static function is_string($key, $array)
	{
		return is_string($array[$key]);
	}

	private static function is_assoc($key, $array)
	{
		if(!is_array($array[$key]))
			return false;

		return (array_keys($array[$key]) !== range(0, count($array[$key]) - 1));
	}
}
