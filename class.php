<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class Profile extends CBitrixComponent
{
	const SECRET = 'huieMMJ24Vy6';
	public function executeComponent()
	{
		if(!\Bitrix\Main\Loader::includeModule("iblock") || !\Bitrix\Main\Loader::includeModule("sale"))
			return;

		global $USER;
		if(!$USER->IsAuthorized())
			LocalRedirect('/');
		$rsUser = CUser::GetByID($USER->GetID());
		$arUser = $rsUser->Fetch();

		$this->arResult = $arUser;

		if(!empty($this->arParams['NEED_FIELDS']))
		{
			$code = base64_encode(json_encode($this->arParams));
			$this->arResult['NEED_FIELDS'] = $code . '.' . hash('sha256', $code . Profile::SECRET);

		}

		$this->arResult['SAVE_PROFILE_PATH']       = $this->getPath()."/saveProfile.php";
		$this->arResult['SAVE_PASSWORD_PATH']      = $this->getPath()."/savePassword.php";
		$this->arResult['ADD_SOCIAL_NETWORK_PATH'] = $this->getPath()."/addSocialNetwork.php";

		$this->IncludeComponentTemplate();
	}
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
	public function isUserPassword($login, $password)
	{
		$user = new \CUser;
		return $user->Login($login, $password, 'N', 'Y');
	}
}
