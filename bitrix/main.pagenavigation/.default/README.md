# Шаблон для пагинации

Как использовать:

- создать экземпляр класса ```\Bitrix\Main\UI\PageNavigation```
- установить значения количества элементов, страниц, текущую страницу и т.д. для этого экземпляра (https://dev.1c-bitrix.ru/api_d7/bitrix/main/ui/pagenavigation/index.php)
- подключить компонент ```bitrix:main.pagenavigation```. В текущем шаблоне уже реализована логика, надо просто поменять html

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