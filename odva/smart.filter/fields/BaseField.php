<?php

abstract class BaseField
{
	public $id;
	public $name;
	public $code;

	protected $settings;
	protected $values;
	protected $dictionary;
	protected $filter;
	protected $isSkuProperty;
	protected $iblockId;

	abstract public function getDisplayValue($facetValue);
	abstract public function getFilterValue($facetValue);

	public function __construct($property, $propertyLink, $isSkuProperty = false, $params=[])
	{
		$this->id            = $property['ID'];
		$this->name          = $property["NAME"];
		$this->code          = $property["CODE"];
		$this->values        = [];
		$this->dictionary    = [];
		$this->filter        = [];
		$this->isSkuProperty = $isSkuProperty;
		$this->settings      = [
			'USER_TYPE'          => $property["USER_TYPE"],
			'USER_TYPE_SETTINGS' => $property["USER_TYPE_SETTINGS"],
			'DISPLAY_TYPE'       => $propertyLink['DISPLAY_TYPE'],
			'DISPLAY_EXPANDED'   => $propertyLink["DISPLAY_EXPANDED"] ?? 'N',
		];

		$this->params        = $params;
	}

	public function isSkuProperty()
	{
		return $this->isSkuProperty;
	}

	public function getFilterData()
	{
		$filter = ['propertyCode' => $this->getFilterCode(), 'filter' => []];

		foreach ($this->filter as $filterValue)
		{
			if(!$this->hasValueInFilter($filterValue))
				continue;

			$filter['filter'][] = $filterValue;
		}

		return $filter;
	}

	public function addValueFromFacet($facet)
	{
		$filterValue = $this->getFilterValue($facet['VALUE']);

		$this->values[$facet['VALUE']] = [
			'DISPLAY_VALUE' => $this->getDisplayValue($facet['VALUE']),
			'FILTER_VALUE'  => $filterValue,
			'ELEMENT_COUNT' => 0,
			'CHECKED'       => $this->hasValueInFilter($filterValue),
			'DISABLED'      => true
		];
	}

	/**
	 * предварительная подгрузка данных значений, например, данные из HL-блока
	 * для некоторых полей может быть не нужна, поэтому не абстрактная функция
	 */
	public function loadValuesData()
	{}

	public function getValues()
	{
		return $this->values;
	}

	public function getSettings()
	{
		return $this->settings;
	}

	public function setDictionary($dictionary)
	{
		$this->dictionary = $dictionary;
	}

	public function getDictionary()
	{
		return $this->dictionary;
	}

	public function setFilter($filter)
	{
		$this->filter = $filter[$this->getFilterCode()];
	}

	public function getFilterCode()
	{
		return "PROPERTY_{$this->code}";
	}

	public function hasValueInFilter($value)
	{
		return in_array(htmlspecialcharsBack($value), $this->filter);
	}

	public function hasFacetValueInFilter($facet)
	{
		$filterValue = $this->getFilterValue($facet['VALUE']);
		return $this->hasValueInFilter($filterValue);
	}

	public function setElementsCountFromFacet($facet)
	{
		$this->values[$facet['VALUE']]['ELEMENT_COUNT'] = $facet['ELEMENT_COUNT'];
	}
}

/*
возможные значения DISPLAY_TYPE

A - Число от-до, с ползунком;
B - Число от-до;
F - Флажки;
G - Флажки с картинками;
H - Флажки с названиями и картинками;
K - Радиокнопки;
P - Выпадающий список;
R - Выпадающий список с названиями и картинками.
*/

/*
возможные значения PROPERTY_TYPE ( S:directory означает, что PROPERTY_TYPE = S, USER_TYPE = directory )

S					Строка
N					Число
L					Список
F					Файл
E					Привязка к элементам ИБ
G					Привязка к разделам ИБ

S:HTML				HTML/текст
S:video				Видео
S:Date				Дата
S:DateTime			Дата/Время
S:Money				Деньги
S:map_yandex		Привязка к Яндекс.Карте
S:map_google		Привязка к карте Google Maps
S:UserID			Привязка к пользователю
S:TopicID			Привязка к теме форума
S:FileMan			Привязка к файлу (на сервере)
S:ElementXmlID		Привязка к элементам по XML_ID
S:directory			Справочник

G:SectionAuto		Привязка к разделам с автозаполнением

E:SKU				Привязка к товарам (SKU)
E:EList				Привязка к элементам в виде списка
E:EAutocomplete		Привязка к элементам с автозаполнением

N:Sequence			Счетчик
*/