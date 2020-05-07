<?php

namespace Odva\Helpers;

class Favorites
{
	// поле, в котором у пользователя хранятся ID избранных товаров
	public static $favoritesField = 'PERSONAL_NOTES';

	public static function add($id = 0)
	{
		$id = intval($id);

		if($id <= 0)
			return [
				'success' => false,
				'error'   => [
					'type' => 'param',
					'msg'  => 'Не правильно передан параметр id'
				]
			];

		global $USER;

		if(!$USER->IsAuthorized())
			return [
				'success' => false,
				'error'   => [
					'type' => 'auth',
					'msg'  => 'Для добавления товара в избранное необходимо авторизоваться'
				]
			];

		$favorites = self::get();

		if(in_array($id, $favorites))
			return ['success' => true, 'count' => count($favorites)];

		$favorites[] = $id;

		$update = $USER->Update(
			$USER->GetParam('USER_ID'),
			[
				self::$favoritesField => implode(',', $favorites)
			]
		);

		if(!$update)
			return [
				'success' => false,
				'error'   => [
					'type' => 'update',
					'msg'  => $USER->LAST_ERROR
				]
			];

		$_SESSION['FAVORITES'][] = $id;

		return ['success' => true, 'count' => count($_SESSION['FAVORITES'])];
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

		if(!$USER->IsAuthorized())
			return ['success' => true];

		$id = intval($id);

		$favorites = self::get();

		$index = array_search($id, $favorites);
		if($index !== false)
			array_splice($favorites, $index, 1);

		$update = $USER->Update(
			$USER->GetParam('USER_ID'),
			[
				self::$favoritesField => implode(',', $favorites)
			]
		);

		if(!$update)
			return [
				'success' => false,
				'error'   => [
					'type' => 'update',
					'msg'  => $USER->LAST_ERROR
				]
			];

		$_SESSION['FAVORITES'] = $favorites;

		return ['success' => true, 'count' => count($_SESSION['FAVORITES'])];
	}

	public static function deleteAll()
	{
		global $USER;

		if(!$USER->IsAuthorized())
			return ['success' => true];

		$update = $USER->Update(
			$USER->GetParam('USER_ID'),
			[
				self::$favoritesField => ""
			]
		);

		if(!$update)
			return [
				'success' => false,
				'error'   => [
					'type' => 'update',
					'msg'  => $USER->LAST_ERROR
				]
			];

		$_SESSION['FAVORITES'] = [];

		return ['success' => true, 'count' => count($_SESSION['FAVORITES'])];
	}
}