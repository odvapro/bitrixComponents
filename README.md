# Модуль вспомогательных классов и библиотек для упрощения работы с Bitrix API

> Подключение в виде git submodule:
> ```git submodule add -b odva.module -f https://github.com/odvapro/bitrixComponents.git local/modules/odva.module/```

При установке модуля устанавливается также JS-модуль, который доступен как ```BX.Odva```. В этом модуле есть набор классов для работы с Bitrix API через AJAX.

На данный момент в модуле доступны:

- методы для работы с корзиной (php: ```\Odva\Module\Basket```, js: ```BX.Odva.Basket```)