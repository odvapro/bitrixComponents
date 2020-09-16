# Битрикс компоненты компании O₂
Компоненты которые мы заслужили

- [Список элементов](/odva/elements/)
- [Детальная элемента](/odva/element/)
- [Список разделов](/odva/sections/)
- [Детальная раздела](/odva/section/)
- [Список заказов](/odva/orders/)
- [Личный кабинет](/odva/profile/)
- [Регистрация/авторизация/регситрация через соцсети/востановления пароля](/odva/auth/)
- [Линия авторизации/регистрации](/odva/authLine/)
- [Линия авторизации/регистрации](/odva/authLine/)
- [Хлебные крошки](/odva/breadcrumbs/)
- [Корзина](/odva/cart/)
- [Избранное](/odva/favorites/)
- [Смарт фильтр](/odva/filter/)
- [Интстанграм](/odva/instagram/)
- [Форма odva](/odva/odva.form/)
- [Продукт](/odva/product/)
- [Продукты](/odva/products/)
- [Форма подписки на рассылки](/odva/subscribe.form/)
- [Оформление заказа](/odva/order.make/)

## Git modules

Каждый уважающий себя компонент вынесен в отдельную ветку (ссылки выше на них не действительны).
И подключаться в проект они будут с помощью gitmodules.

Это сделано для того, чтобы можно было нужные модули подключать по отдельности, и не тащить весь репозиторий в каждый проект.

Для добавления нового компонента надо в папке проекта выполнить команду

```git submodule add -b <component> -f https://github.com/odvapro/bitrixComponents.git local/components/odva/<component>```

где ```<component>``` - название компонента, например, ```elements```

Если попытаться склонировать проект, в котором уже есть добавленные модули, обычной командой git clone,
то модули склонируются как пустые папки. Для того, чтобы скачать содержимое папок, надо будет в корне проекта запустить две команды:

```
git submodule init
git submodule update
```

Если надо склонировать проект сразу с содержимым модулей, можно в команду ```git clone``` добавить флаг ```--recursive```

***Внимание! При добавлении модуля его надо загрузить на сервер тоже. Либо спулить на сервере и запустить две команды, описанные выше.***