# Компонент формы

- пример подключения
```php
//подключения компонента
   $APPLICATION->IncludeComponent(
			'odva:odva.form',
			'form.help',// имя шаблона
			[
				"IBLOCK_ID" => 7,//id инфоблока куда сохраняется результат
				"NAME_FORM_RESULT" => "QUESTION",//название результат(тут может быть что угодно)
				"MAIL_EVENT_CODE" => "SEND_EMAIL_TEST"//почтовое событие которое срабатывает по выполнени сохранения результат в инфоблок
			]
		);
```
- примерные шаблоны
    form.help - простой шаблон