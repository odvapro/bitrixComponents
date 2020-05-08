<?php
//обозначаем простарство имен модуля
namespace Odva\Helpers;

//объявляем класс для работы с избранными елементами
class Favorites
{
	// поле, в котором у пользователя хранятся ID избранных елементов
	public static $favoritesField = 'PERSONAL_NOTES';

	//метод класса котоый добавляет елемент в избранное и возращает результат выполнения
	//принимаемые параметры
	//$id - id елемента инфоблока
	public static function add($id = 0)
	{
		//конвертируем пришедший параметр $id в целое число
		$id = intval($id);

		//проверяю меньше или равен 0 пришещий паметр $id
		if($id <= 0)
		{
			//если да то возвращаем ошибку
			return [
				'success' => false,
				'error'   => [
					'type' => 'param',
					'msg'  => 'Не правильно передан параметр id'
				]
			];
		}

		//достаем глобальный объект пользователя
		global $USER;

		//проверяем авторизован ли пользователь
		if(!$USER->IsAuthorized())
		{
			//если нет то возвращаем ошибку
			return [
				'success' => false,
				'error'   => [
					'type' => 'auth',
					'msg'  => 'Для добавления елементов в избранное необходимо авторизоваться'
				]
			];
		}
		//достаем все избранные елементы
		$favorites = self::get();

		//проверяем есть ли добавляемый $id в избранных елементов
		if(in_array($id, $favorites))
		{
			//если есть вернуть сообщение об успешном добавлениий и количество избранных елементов
			return ['success' => true, 'count' => count($favorites)];
		}

		//добавляем $id елемента в избранные елементы
		$favorites[] = $id;

		//сохраняем текущеее состтояние избранных елементов(все id соединены в строчку разделены запятой)
		$update = $USER->Update(
			$USER->GetParam('USER_ID'),
			[
				self::$favoritesField => implode(',', $favorites)
			]
		);

		if(!$update)
		{
			//если изменение состояния избранных елементов не сохранилось то вернуть ощибку
			return [
				'success' => false,
				'error'   => [
					'type' => 'update',
					'msg'  => $USER->LAST_ERROR
				]
			];
		}

		//сохраняет избранный елемент в ссесию
		$_SESSION['FAVORITES'][] = $id;

		//возвращаем результат успешного сохранения в сесию и количество елементов в массиве избранных елементов в сессии
		return ['success' => true, 'count' => count($_SESSION['FAVORITES'])];
	}

	//метод класса котоый достает и возращает все елементы из избранного
	public static function get()
	{
		//достаем глобальный объект пользователя
		global $USER;

		//проверяем авторизован ли пользователь
		if(!$USER->IsAuthorized())
		{
			//если нет вернуть пустой массив избранных елементов
			return [];
		}

		//если сессия избраных елементов не пустая то вернуть ссесию избраных елементов
		if(!empty($_SESSION['FAVORITES']))
			return $_SESSION['FAVORITES'];

		//достаем данные о текущем пользователе
		$rsUser = \Bitrix\Main\UserTable::GetByID($USER->GetParam('USER_ID'));
		$arUser = $rsUser->Fetch();

		//формируем массив избранных елементов из строки пользователя
		$favorites = explode(',', $arUser[self::$favoritesField]);
		$favorites = array_filter($favorites);

		//сохраняем массив избранных елементов в сессий
		$_SESSION['FAVORITES'] = $favorites;

		return $favorites;
	}

	//метод класса котоый удаляет елемент из избранного и возращает результат выполнения
	//принимаемые параметры
	//$id - id елемента инфоблока
	public static function delete($id = 0)
	{
		//достаем глобальный объект пользователя
		global $USER;

		//проверяем авторизован ли пользователь
		if(!$USER->IsAuthorized())
		{
			//если нет вернуть пустой массив избранных елементов
			return ['success' => true];
		}

		//конвертируем пришедший параметр $id в целое число
		$id = intval($id);

		//достаем все избранные елементы
		$favorites = self::get();

		//ищем текущий елемент в избранных
		$index = array_search($id, $favorites);
		if($index !== false)
		{
			//если находим то удаляем его из массива избранных елементов
			array_splice($favorites, $index, 1);
		}

		//сохраняем текущеее состтояние избранных елементов(все id соединены в строчку и разделены запятой)
		$update = $USER->Update(
			$USER->GetParam('USER_ID'),
			[
				self::$favoritesField => implode(',', $favorites)
			]
		);

		if(!$update)
		{
			//если изменение состояния избранных елементов не сохранилось то вернуть ощибку
			return [
				'success' => false,
				'error'   => [
					'type' => 'update',
					'msg'  => $USER->LAST_ERROR
				]
			];
		}
		//сохраняем массив избранных елементов в сессий
		$_SESSION['FAVORITES'] = $favorites;
		//возвращаем результат успешного сохранения в сесию и количество елементов в массиве избранных елементов в сессии
		return ['success' => true, 'count' => count($_SESSION['FAVORITES'])];
	}

	//метод класса котоый удаляет все елементы из избранного и возращает результат выполнения
	public static function deleteAll()
	{
		//достаем глобальный объект пользователя
		global $USER;

		//проверяем авторизован ли пользователь
		if(!$USER->IsAuthorized())
		{
			//если нет то возвращаем ошибку
			return [
				'success' => false,
				'error'   => [
					'type' => 'auth',
					'msg'  => 'Для удаления елементов из избранного необходимо авторизоваться'
				]
			];
		}

		//очищаем строку у пользователя в которой хранились избранные елементы
		$update = $USER->Update(
			$USER->GetParam('USER_ID'),
			[
				self::$favoritesField => ""
			]
		);

		//если изменить строку не удалось то возвращаем ошибку
		if(!$update)
			return [
				'success' => false,
				'error'   => [
					'type' => 'update',
					'msg'  => $USER->LAST_ERROR
				]
			];

		//стираем все данные в массиве избранных елементов
		$_SESSION['FAVORITES'] = [];

		//возращаем результат успешного удаления всех избранных елементов
		return ['success' => true, 'count' => count($_SESSION['FAVORITES'])];
	}
}