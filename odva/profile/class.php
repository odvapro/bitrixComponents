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
}
