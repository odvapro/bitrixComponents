# Компонент портфолио

- пример подключения
```php
$APPLICATION->IncludeComponent("odva:profile",//имся пространстви имен;имя коспонента
                               "profile.info",//шаблон копонента
                               []
                           );
```
- примерные шаблоны
	- "profile.info" - шаблон вывода и изменения информации
	- "chenge.password" - шаблон смены пароля
- переменные которые нужня для ajax
```php
	$arResult['SAVE_PROFILE_PATH']// - изменения изменеия профиля
	$arResult['SAVE_PASSWORD_PATH']// - изменения пароля
	$arResult['ADD_SOCIAL_NETWORK_PATH']// - изменить соцсети
```