# Шаблон для пагинации

Как использовать (можно почитать [тут](https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=2741&LESSON_PATH=3913.5062.5748.2741)):

- создать экземпляр класса ```\Bitrix\Main\UI\PageNavigation```
- установить значения количества элементов, страниц, текущую страницу и т.д. для этого экземпляра (https://dev.1c-bitrix.ru/api_d7/bitrix/main/ui/pagenavigation/index.php)
- подключить компонент ```bitrix:main.pagenavigation```. В текущем шаблоне уже реализована логика, надо просто поменять html

## Параметры (которые я понял =))

- NAV_OBJECT - объект пагинации (экземпляр класса ```\Bitrix\Main\UI\PageNavigation```)
- SEF_MODE - строить ссылки по ЧПУ или с GET-параметрами
- PAGE_WINDOW - количество страниц, которое будет отображаться между многоточиями (1 ... ***7 8 9*** ... 20)

## Пример

```php
$nav = new \Bitrix\Main\UI\PageNavigation('nav');
// устанавливаем значения, не зависящие от количества элементов
$nav->allowAllRecords(false)->setPageSize(9)->initFromUri();

// устанавливаем общее количество записей, от него будет строиться пагинация
// $productsCount можно достать каким нибудь из доступных в Bitrix методом.
// например, в объекте CDBResult (результат выполнения запроса CIBLockElement::GetList) есть переменная NavRecordCount
$nav->setRecordCount($productsCount);

// подключаем компонент
$APPLICATION->IncludeComponent(
	"bitrix:main.pagenavigation",
	"",
	[
		"NAV_OBJECT" => $nav,
		"SEF_MODE"   => "Y",
	],
	false
);
```

*При подключении пагинации в наших компонентах, например, odva:elements, уже есть NAV_OBJECT, его и надо передать*