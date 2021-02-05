<?php

namespace Odva\Module;

use \Bitrix\Main\Error;

class Favorites
{
	// поле, в котором у пользователя хранятся ID избранных товаров
	public static $favoritesField = 'PERSONAL_NOTES';

	public static function add($id = 0)
	{
		$id = intval($id);

		if($id <= 0)
			return new Error('Не правильно передан параметр id');

		global $USER;

		if(!$USER->IsAuthorized())
			return new Error('Для добавления товара в избранное необходимо авторизоваться');

		$favorites = self::get();

		if(in_array($id, $favorites))
			return ['favorites' => $favorites];

		$favorites[] = $id;

		$update = $USER->Update(
			$USER->GetParam('USER_ID'),
			[
				self::$favoritesField => implode(',', $favorites)
			]
		);

		if(!$update)
			return new Error($USER->LAST_ERROR);

		$_SESSION['FAVORITES'][] = $id;

		return ['favorites' => $_SESSION['FAVORITES']];
	}

	public static function get()
	{
		global $USER;

		if(!$USER->IsAuthorized())
			return [];

		if(!empty($_SESSION['FAVORITES']))
			return $_SESSION['FAVORITES'];

		$rsUser = \Bitrix\Main\UserTable::GetByID($USER->GetParam('USER_ID'));
		$arUser = $rsUser->Fetch();

		$favorites = explode(',', $arUser[self::$favoritesField]);
		$favorites = array_filter($favorites);

		$_SESSION['FAVORITES'] = $favorites;

		return $favorites;
	}

	public static function delete($id = 0)
	{
		global $USER;

		$favorites = self::get();

		if(!$USER->IsAuthorized())
			return ['favorites' => $favorites];

		$id    = intval($id);
		$index = array_search($id, $favorites);

		if($index === false)
			return ['favorites' => $favorites];

		array_splice($favorites, $index, 1);

		$update = $USER->Update(
			$USER->GetParam('USER_ID'),
			[
				self::$favoritesField => implode(',', $favorites)
			]
		);

		if(!$update)
			return new Error($USER->LAST_ERROR);

		$_SESSION['FAVORITES'] = $favorites;

		return ['favorites' => $_SESSION['FAVORITES']];
	}

	public static function deleteAll()
	{
		global $USER;

		$favorites = self::get();

		if(!$USER->IsAuthorized())
			return ['favorites' => $favorites];

		$update = $USER->Update(
			$USER->GetParam('USER_ID'),
			[
				self::$favoritesField => ""
			]
		);

		if(!$update)
			return new Error($USER->LAST_ERROR);

		$_SESSION['FAVORITES'] = [];

		return ['favorites' => $_SESSION['FAVORITES']];
	}
}
