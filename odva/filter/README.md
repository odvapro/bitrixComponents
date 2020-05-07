# Компонент смарт фильтр

- Пример подключения
```php
	$smartFilterResult = $APPLICATION->IncludeComponent(
		"odva:smart.filter",
		"",
		[
			'IBLOCK_ID'    => 2,
			'SECTION_CODE' => $_REQUEST['section'],
			'FILTER_URL'   => $_REQUEST['filter'],
			'SORT'         => $sortArr,
			'FIELDS_MAP'   => $fieldsMap,
			'PRICE'        => [
				'FIELD' => 'CATALOG_PRICE_1',
				'TITLE' => 'Цена'
			]
		]
	);
```