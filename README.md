# Элемент детально

Компонент для вывода детальной информации элемента или простого продукта (***торговые предложения не поддерживаются***)

Принимаемые параметры:

- ___id___ _или_ ___code___ или ___filter___  - соответственно, ID или CODE элемента или массив для фильтрования. ___Один из этих параметров надо передать обязательно.___ Если переданы несколько,то отбираются по приоритету id -> code -> filter.
- ___IBLOCK_ID___                             - ID инфоблока, в котором лежит элемент. ___Обязателен, если не передается в параметре filter___
- ___props___                                 - массив CODE свойств, которые нужно достать (_если в массиве будет элемент *, то загрузятся все поля_)
- ___load_product_fields___                   - подгружать / не подгружать [поля товара](https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=12183#iblock_18_6_200)
- ___load_section___                          - подгружать / не подгружать информацию о разделе
- ___price_ids___                             - массив ID типов цен, которые нужно достать. Посмотреть ID можно в админке: ***Магазин - Цены - Типы цен***
- ___images___                                - массив [настроек для обработки изображений](#настройка-обработки-изображений).
- ___set_code_404___                          - если значение ___отличается___ от "N" или не задано, то при неудачном поиске будет отдаваться 404 код
- ___show_errors___                           - если значение не установлено или не равно "N", при неудачном поиске будет показываться текст "Элемент не найден", иначе будет подключаться шаблон
- ___SET_CANONICAL_URL___                     - установить канонический URL для страницы
- ___SET_TITLE___                             - установить заголовок страницы
- ___SET_BROWSER_TITLE___                     - установить заголовок окна браузера
- ___ADD_ELEMENT_CHAIN___                     - добавить элемент в breadcrumb
- ___SET_META_KEYWORDS___                     - вывести meta-тег keywords
- ___SET_META_DESCRIPTION___                  - вывести meta-тег description

## Настройка обработки изображений

Массив images - ***обязательно*** ассоциативный, где

- ключ - код поля, которое надо обработать. Ключи DETAIL_PICTURE и PREVIEW_PICTURE автоматически воспринимаются как стандартные анонс
и детальная картинка. Остальные ищутся в массиве свойств.
- значение - массив настроек для изображения. Это тоже ***обязательно*** ассоциативный массив, где
	- ключ - код, с которым будет добавлена картинка в результирующий массив картинок
	- значение - массив, в котором ***первые два значения*** - ширина и высота для обрезания картинки - их нужно ***обязательно*** передать.
	Кроме того можно передать третий параметр - тип обрезания resizeType
	(см. [документацию по обрезанию картинок](https://dev.1c-bitrix.ru/api_help/main/reference/cfile/resizeimageget.php)).

*Коды свойств, которые передаются в images, *обязательно* нужно передать так же в *props*, иначе они не будут подгруженны и соответственно не обработаны.*