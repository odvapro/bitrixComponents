# Детальная элемента
пример использования
```php
$APPLICATION->IncludeComponent('demo:element',  'template'//template -- имя шаблона компонента
    ,[
	'filter' => [
		'IBLOCK_ID' => 3,//id инфоблока
		'ID' =>	3,// id элемента в инфоблоке
		//параметры передаются в GetList филтр
		//компонент достает все свойства
	]
]);
```