# Детальная раздела
пример использования
```php
$APPLICATION->IncludeComponent(
	'odva:section',// odva - простараства имен в котором находится компонент, sections - имя компонента
	'sections',// имя шаблона компонента
	[
		'filter' => [
			'IBLOCK_ID' => 5,// id - инфоблока
			'ID' => 5,// id - секции
		]
	]
);
```