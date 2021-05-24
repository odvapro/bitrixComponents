<?php

namespace Odva\Module\Controller;

use \Bitrix\Main\Error;
use \Bitrix\Main\Engine\Controller;

class User extends Controller
{
	public function configureActions()
	{
		return [
			'login'          => ['prefilters' => []],
			'ulogin'         => ['prefilters' => []],
			'register'       => ['prefilters' => []],
			'logout'         => ['prefilters' => []],
			'forgotPassword' => ['prefilters' => []],
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

		$userData = [
			'LOGIN'            => $login,
			'NAME'             => $name,
			'LAST_NAME'        => $lastname,
			'PASSWORD'         => $password,
			'CONFIRM_PASSWORD' => $confirm,
			'EMAIL'            => $email
		];

		$additional = $this->getRequest()->getPost('additional');

		if(!empty($additional) && is_array($additional))
			foreach ($additional as $field => $value)
				$userData[$field] = $value;

		$userId = $USER->Add($userData);

		if(intval($userId) > 0)
		{
			$authorize = $this->getRequest()->getPost('authorize');

			if(!empty($authorize))
				$USER->Authorize($userId);

			return true;
		}

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
		if (!is_object($USER)) $USER = new \CUser;

		$dbUser = \CUser::GetByLogin($login)->Fetch();

		if(empty($dbUser))
		{
			$this->addError(new Error('Пользователь не найден', 'login'));
			return [];
		}

		if(!\Bitrix\Main\Security\Password::equals($dbUser['PASSWORD'], $password))
		{
			$this->addError(new Error('Не правильный логин или пароль', 'login'));
			$this->addError(new Error('Не правильный логин или пароль', 'password'));
			return [];
		}

		if($remember !== 'N' && $remember !== 'Y')
			$remember = 'N';

		$remember = $remember === 'Y';

		$USER->Authorize($dbUser['ID'], $remember);

		return true;
	}

	public function logoutAction()
	{
		global $USER;

		if(!$USER->IsAuthorized())
			return true;

		$USER->Logout();

		return true;
	}

	public function forgotPasswordAction($email)
	{
		global $USER;

		if($USER->IsAuthorized())
		{
			$this->addError(new Error('Вы уже авторизованы', 'email'));
			return false;
		}

		if(!check_email($email))
		{
			$this->addError(new Error('Некорректный email', 'email'));
			return false;
		}

		$user = CUser::GetList([], ['EMAIL' => $email])->Fetch();

		if(!$user)
		{
			$this->addError(new Error('Пользователь не найден', 'email'));
			return false;
		}

		$res = CUser::SendPassword($user['LOGIN'], $user['EMAIL']);

		if($res['TYPE'] == 'ERROR')
		{
			$errors = explode('<br>', $res['MESSAGE']);
			$this->addError(new Error($errors[0], 'email'));
			return false;
		}

		return true;
	}
}
