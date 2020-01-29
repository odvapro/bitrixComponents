<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class Profile extends CBitrixComponent
{
	public function getCities()
	{
		$cities = [];
		$dbVars = CSaleLocation::GetList(
			["SORT" => "ASC", "COUNTRY_NAME_LANG" => "ASC", "CITY_NAME_LANG" => "ASC"],
			["LID" => LANGUAGE_ID,'!CITY_NAME'=>false],
			false,
			false,
			[]
		);
		while ($vars = $dbVars->Fetch())
		{
			$cities[] = $vars['CITY_NAME'];
		}
		return $cities;
	}
	public function isUserPassword($userId, $password)
	{
	    $userData = CUser::GetByID($userId)->Fetch();

	    $salt = substr($userData['PASSWORD'], 0, (strlen($userData['PASSWORD']) - 32));

	    $realPassword = substr($userData['PASSWORD'], -32);
	    $password = md5($salt.$password);

	    return ($password == $realPassword);
	}
}
