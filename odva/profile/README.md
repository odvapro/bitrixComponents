# Компонент личный кабинет

- пример подключения
```php
$APPLICATION->IncludeComponent("odva:profile",//имя пространства имен:имя компонента
                               "profile.info",//шаблон копонента
                               []
                           );
```
- примерные шаблоны
	- "profile.info" - шаблон вывода и изменения личной информации
	- "chenge.password" - шаблон смены пароля
- переменные которые нужня для ajax
```php
	$arResult['SAVE_PROFILE_PATH']// - путь к файлу для изменения профиля
	$arResult['SAVE_PASSWORD_PATH']// - путь к файлу для изменения пароля
	$arResult['ADD_SOCIAL_NETWORK_PATH']// - путь к файлу для изменить соцсети
```