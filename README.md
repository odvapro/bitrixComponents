# Модуль вспомогательных классов и библиотек для упрощения работы с Bitrix API

Подключение в виде git submodule:

```git submodule add -b odva.module -f https://github.com/odvapro/bitrixComponents.git local/modules/odva.module/```

При установке модуля устанавливается также JS-модуль, который доступен как ```BX.Odva```. В этом модуле есть набор классов для работы с Bitrix API через AJAX.

На данный момент в модуле доступны:

- методы для работы с корзиной (php: ```\Odva\Module\Basket```, js: ```BX.Odva.Basket```)

## Установка

- Добавить файлы модуля в /local/modules (желательно через submodules)
- В админке битрикс зайти на страницу ```Marketplace => Установленные решения```
- В списке модулей найти "модуль odva.module" и в его выпадающем меню выбрать "установить"
- подключить модуль к сайту в нужном месте (чаще всего в init.php):
  ```
  \Bitrix\Main\Loader::includeModule('odva.module');
  ```