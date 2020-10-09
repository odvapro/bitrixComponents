<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class Auth extends CBitrixComponent
{
	public function executeComponent()
	{
		if(!\Bitrix\Main\Loader::includeModule("iblock"))
			return;

		global $USER;
		if($USER->IsAuthorized())
			return false;
		//путь к файлу для авотризации/регистрации через соцсети
		$this->arResult['AUTH_SOCIAL_PATH'] = $this->getPath()."/authSocial.php";
		//путь к файлу для стандартной регистрации
		$this->arResult['REG_BITRIX_PATH'] = $this->getPath()."/regBitrix.php";
		//путь к файлу для стандартной авторизации
		$this->arResult['AUTH_BITRIX_PATH'] = $this->getPath()."/authBitrix.php";
		//путь к файлу для смены пароля и отправки его на почту
		$this->arResult['SEND_PASSWORD_BITRIX_PATH'] = $this->getPath()."/sendPassword.php";
		//вызов указаного в подклчючении компонента шаблона
		$this->IncludeComponentTemplate();
	}
	public function generatePassword($length = 8)
	{
	    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	    $count = mb_strlen($chars);

	    for ($i = 0, $result = ''; $i < $length; $i++)
	    {
	        $index = rand(0, $count - 1);
	        $result .= mb_substr($chars, $index, 1);
	    }

	    return $result;
	}
}
