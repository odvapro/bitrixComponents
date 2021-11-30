# Список разделов

- ___sort___        - параметр **arOrder** для метода ```CIBlockSection::GetList```
- ___filter___      - параметр **arFilter** для метода ```CIBlockSection::GetList```
- ___element_cnt___ - параметр **bIncCnt** для метода ```CIBlockSection::GetList```
- ___select___      - параметр **Select** для метода ```CIBlockSection::GetList```
- ___count___       - количество доставаемых секций (по умолчанию достаются все)
- ___load_urls___   - если параметр указан, то будут заполняться ссылки ```LIST_PAGE_URL``` и ```SECTION_PAGE_URL```

пример использования
```php
$APPLICATION->IncludeComponent(
	'odva:sections',
	'sections',
	[
		'sort'        => ['SORT' => 'ASC'],
		'filter'      => [],
		'element_cnt' => true,
		'select'      => [],
		'count'       => 100,
	]
);
```
