# Компонент избранное

- пример подключения
```php
//подключения компонента с вариантом шаблонка избранное на страницу
   $APPLICATION->IncludeComponent('odva:favorites', 'page', []);
//подключения компонента с вариантом шаблонка избранное для линий
//(ссылка показывающая количество избранных товаров и ведущая на деатльную избранных товаров)
$APPLICATION->IncludeComponent('odva:favorites', '', []);
//подключения компонента с вариантом шаблонка избранное для линий(мобильная версия)
//(ссылка показывающая количество избранных товаров и ведущая на деатльную избранных товаров)
$APPLICATION->IncludeComponent('odva:favorites', '', ['MOBILE' => 'Y']);
```
- примерные шаблоны
    page - шаблон страницы избранное
	'' или .default - шаблон линий избранное

Примечение:
	компонент не работает без модуля odva.helpers