# Детальная раздела

Компонент работает на основе метода [CIBlockSection::GetList](https://dev.1c-bitrix.ru/api_help/iblock/classes/ciblocksection/getlist.php).


## Принимаемые параметры

Можно передать некоторые стандартные параметры ```CIBlockSection::GetList```:

- ***sort***   - параметр **arOrder** для метода ```CIBlockSection::GetList```
- ***filter*** - параметр **arFilter** для метода ```CIBlockSection::GetList```

<ins>Если в фильтре есть свойства, их надо обязательно включать в параметр ```props```</ins>

Так же можно передать параметры:

- ___id___ _или_ ___code___ - соответственно, ID или CODE раздела. ___Один из этих параметров надо передать обязательно.___ Если переданы несколько,то отбираются по приоритету id -> code.
- ___IBLOCK_ID___           - ID инфоблока, в котором находится раздел. ___Обязателен.
- ___props___               - массив CODE свойств, которые нужно достать (_если в массиве будет элемент *, то загрузятся все поля_)
- ___show_element_cnt___    - количество элементов у раздела

Пример использования
```php
$APPLICATION->IncludeCOmponent(
	'odva:elements',
	'',
	[
		'id' => 2,
		'code' => $_GET['SECTION_ELEMENT_CODE'],
		'iblock_id' => 3,
		'props' => ['UF_CASES_CONSULTATION', 'UF_TREATING_DISEASES'],
		'show_element_cnt' => true,
	]
);
```