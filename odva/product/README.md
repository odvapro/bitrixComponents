#Компонент продукт(можно использовать для детальной каталога)

- Пример подключения
```php
	$APPLICATION->IncludeComponent(
		'odva:product',
		'product.detail',//шаблон компоненета
		[
			'filter' => [
				//параметры передаются в getlist параметр filter
				'IBLOCK_ID'    => 2, //id каталога
				'ACTIVE'       => 'Y', //достает товар только если он активный
				'SECTION_CODE' => $_GET['section_code'], //код секции
				'CODE'		   => $_GET['product_code'] //код товара
			],
		]
	);
```
- product.detail - акутуальный шаблон выводящий всю детальную страницy