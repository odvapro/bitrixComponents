# Список заказов
Пример использования
```php
<?$APPLICATION->IncludeComponent('odva:orders', 'template'//template -- имя шаблона компонента
    ,[
	'filter' => [
		'@ID' => [1,2,3],// массив ид заказов
		//параметры передаются в GetList филтр
	]
]);?>
```