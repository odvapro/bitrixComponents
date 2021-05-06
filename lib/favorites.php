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

		$favorites = self::get();

		if(in_array($id, $favorites))
			return ['favorites' => $favorites];

		$favorites[] = $id;

		global $USER;

		if($USER->IsAuthorized())
		{
			$update = $USER->Update(
				$USER->GetParam('USER_ID'),
				[
					self::$favoritesField => implode(',', $favorites)
				]
			);

			if(!$update)
				return new Error($USER->LAST_ERROR);
		}

		$_SESSION['FAVORITES'][] = $id;

		return ['favorites' => $_SESSION['FAVORITES']];
	}

	public static function get()
	{
		if(!array_key_exists('FAVORITES', $_SESSION))
			$_SESSION['FAVORITES'] = [];

		global $USER;

		if(!empty($_SESSION['FAVORITES']) || !$USER->IsAuthorized())
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
		$favorites = self::get();

		if(empty($favorites) || !in_array($id, $favorites))
			return ['favorites' => $favorites];

		$id    = intval($id);
		$index = array_search($id, $favorites);

		array_splice($favorites, $index, 1);

		global $USER;

		if($USER->IsAuthorized())
		{
			$update = $USER->Update(
				$USER->GetParam('USER_ID'),
				[
					self::$favoritesField => implode(',', $favorites)
				]
			);

			if(!$update)
				return new Error($USER->LAST_ERROR);
		}

		$_SESSION['FAVORITES'] = $favorites;

		return ['favorites' => $_SESSION['FAVORITES']];
	}

	public static function deleteAll()
	{

		$favorites = self::get();

		if(empty($favorites))
			return ['favorites' => []];

		global $USER;

		if($USER->IsAuthorized())
		{
			$update = $USER->Update(
				$USER->GetParam('USER_ID'),
				[
					self::$favoritesField => ""
				]
			);

			if(!$update)
				return new Error($USER->LAST_ERROR);
		}

		$_SESSION['FAVORITES'] = [];

		return ['favorites' => $_SESSION['FAVORITES']];
	}
}
