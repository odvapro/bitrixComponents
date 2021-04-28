<?php

namespace Odva\Module\Controller;

use \Bitrix\Main\Error;
use \Bitrix\Main\Engine\Controller;

class User extends Controller
{
	public function configureActions()
	{
		return [
			'login'    => ['prefilters' => []],
			'ulogin'   => ['prefilters' => []],
			'register' => ['prefilters' => []],
		];
	}

	public function uloginAction($token='')
	{
		if(empty($token))
		{
			$this->addError(new Error('Необходимо передать токен', 'global'));
			return false;
		}

		global $USER;

		$response = file_get_contents('http://ulogin.ru/token.php?token=' . $token . '&host=' . $_SERVER['HTTP_HOST']);
		$user     = json_decode($response, true);

		if(!empty($user['error']))
		{
			$this->addError(new Error($user['error'], 'global'));
			return false;
		}

		if(empty($user['network']))
		{
			$this->addError(new Error('Неправильный ответ от сервиса', 'global'));
			return false;
		}

		global $USER_FIELD_MANAGER;

		$propertyName = 'UF_' . strtoupper($user['network']);
		$userFields   = $USER_FIELD_MANAGER->GetUserFields("USER");

		if(!array_key_exists($propertyName, $userFields))
		{
			$this->addError(new Error('Данный тип авторизации не поддерживается', 'global'));
			return false;
		}

		$filter  = [$propertyName => $user['uid']];
		$rsUsers = \CUser::GetList($by, $order, $filter);

		if($arUser = $rsUsers->Fetch())
		{
			$USER->Authorize($arUser['ID']);
			return $arUser;
		}

		$newUser  = new \CUser;
		$arFields = [
			"NAME"              => $user['first_name'],
			"LAST_NAME"         => $user['last_name'],
			"EMAIL"             => $user['email'],
			"LOGIN"             => $user['email'],
			"LID"               => SITE_ID,
			"ACTIVE"            => "Y",
			"GROUP_ID"          => [2],
			"PASSWORD"          => md5($user['email']),
			"CONFIRM_PASSWORD"  => md5($user['email']),
			$propertyName       => $user['uid']
		];

		$ID       = $newUser->Add($arFields);

		if($ID)
		{
			$USER->Authorize($ID);
			return true;
		}

		$errors = explode('<br>', $newUser->LAST_ERROR);
		$this->addError(new Error($errors[0], 'global'));

		return [$arFields, $user];
	}

	public function registerAction()
	{
		$login    = $this->getRequest()->getPost('login');
		$name     = $this->getRequest()->getPost('name');
		$lastname = $this->getRequest()->getPost('lastname');
		$password = $this->getRequest()->getPost('password');
		$confirm  = $this->getRequest()->getPost('confirm');
		$email    = $this->getRequest()->getPost('email');

		global $USER;

		if($USER->IsAuthorized())
		{
			$this->addError(new Error('Вы уже авторизованы', 'global'));
			return false;
		}

		if(empty($email))
			$this->addError(new Error('Заполните это поле', 'email'));
		else if(!check_email($email, true))
			$this->addError(new Error('Неверный email', 'email'));

		if(empty($login))
			$this->addError(new Error('Заполните это поле', 'login'));

		$securityPolicy = \CUser::GetGroupPolicy([2]);
		$errors         = (new \CUser)->CheckPasswordAgainstPolicy($password, $securityPolicy);

		if(!empty($errors))
			$this->addError(new Error($errors[0], 'password'));

		if($password != $confirm)
			$this->addError(new Error('Пароли не совпадают', 'confirm'));

		if(!empty($this->getErrors()))
			return false;

		$result = $USER->Register($login, $name, $lastname, $password, $confirm, $email);

		if($result['TYPE'] == 'ERROR')
		{
			$errors = explode('<br>', $result['MESSAGE']);
			$this->addError(new Error($errors[0], 'global'));
			return false;
		}

		$additional = $this->getRequest->getPost('additional');

		if(empty($additional) || !is_array($additional))
			return true;

		$update = $USER->Update($USER->GetID(), $additional);

		if($update)
			return true;

		$errors = explode('<br>', $USER->LAST_ERROR);

		$this->addError(new Error($errors[0], 'global'));

		return false;
	}

	public function loginAction($login='', $password='', $remember='N')
	{
		if(empty($login))
			$this->addError(new Error('Заполните это поле', 'login'));

		if(empty($password))
			$this->addError(new Error('Заполните это поле', 'password'));

		if(!empty($this->getErrors()))
			return [];

		global $USER;
		if (!is_object($USER)) $USER = new CUser;

		if($remember !== 'N' && $remember !== 'Y')
			$remember = 'N';

		$result = $USER->Login($login, $password, $remember);

		if($result === true)
			return true;

		$errors = explode('<br>', $result['MESSAGE']);
		$this->addError(new Error($errors[0], 'global'));

		return false;
	}
}
