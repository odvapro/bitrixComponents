В этой папке хранятся контроллеры, к которым можно обращаться по ajax или обычным запросом через браузер.

Например, если есть контроллер ```Test```, в котором есть экшн ```add```, то этот экшн можно запустить, отправив запрос по адресу ```/bitrix/services/main/ajax.php?action=odva:helpers.api.test.add```

### Некоторые правила работы с контроллерами

В экшне можно указать параметры, которые приходят через GET или POST. Если их указывать без значений по умолчанию,
срабатывает битриксовское исключение, которое добавляет в ответ ошибку и выполнение экшна прекращается.

Нам это чаще всего не нужно, потому что лучше собрать все ошибки вместе и отдать их в нужном нам формате данных,
поэтому лучше всегда указывать значение по умолчанию.