# Компонент Хлебные крошки

- пример подключения
```php
    $APPLICATION->IncludeComponent("odva:breadcrumbs", "", [ // "" - шаблок хдебных крошек или .default
			'LINKS' => [
				['text' => 'Главная', 'url'  => '/'],//каждый подмассив это елемент хлебных крошек
				['text' => 'Контакты', 'url'  => '']
			]
		]);
```
- примерные шаблоны
    "" или .default - актульный шаблон хлебных крошек
