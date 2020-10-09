<?php
if (!function_exists('declension'))
{
	function declension($number, array $data)
	{
		$rest = array($number % 10, $number % 100);
		if($rest[1] > 10 && $rest[1] < 20)
		{
	    	return $data[2];
	 	}
		elseif ($rest[0] > 1 && $rest[0] < 5)
	 	{
	 		return $data[1];
		}
		else if ($rest[0] == 1)
		{
			return $data[0];
		}
		return $data[2];
	};
};
if (!function_exists('mb_ucfirst')) {
	function mb_ucfirst($str, $encoding = "UTF-8", $lower_str_end = false) {
		$first_letter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
		$str_end = "";
		if ($lower_str_end)
		{
			$str_end = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
		}
		else
		{
			$str_end = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
		}
  		$str = $first_letter . $str_end;
  		return $str;
	}
}