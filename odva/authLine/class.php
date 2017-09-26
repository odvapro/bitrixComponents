<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class AuthLine extends CBitrixComponent
{
	public static function getAvatar()
	{
		global $USER;
		$url = 'https://www.gravatar.com/avatar/';
		$url .= md5( strtolower( trim($USER->GetEmail()) ) );
		$url .= "?s=32&d=mm&r=g";
		return $url;
	}
}
