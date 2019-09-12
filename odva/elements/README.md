# Список элементов
Пример использования
```
<?$APPLICATION->IncludeComponent('odva:elements', '', [
	'filter' => [
		'IBLOCK_ID' => 3,
		'ACTIVE'    => 'Y'
		# параметры передаются в GetList филтр
	],
	"count" => 4
]);?>
```