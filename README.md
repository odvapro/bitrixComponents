# Список разделов

- ___filter___ - параметр **arFilter** для метода ```CIBlockElement::GetList```
- ___count___ - количество доставаемых секций (по умолчанию count = 10)

пример использования
```php
$APPLICATION->IncludeComponent(
	'odva:sections',// odva - простараства имен в котором находится компонент, sections - имя компонента
	'sections',// имя шаблона компонента
	[
		'filter' => [
			'IBLOCK_ID' => 5,// id - инфоблока
		],
		'count' => 100 // количество доставаемых секций
	]
);
```
