<?php

require_once 'BaseField.php';

class LField extends BaseField
{
	private $valuesData = [];

	public function loadValuesData()
	{
		$rsPropertyEnums = CIBlockPropertyEnum::GetList([], ['PROPERTY_ID' => $this->id]);
		while($enumField = $rsPropertyEnums->Fetch())
			$this->valuesData[$enumField['ID']] = $enumField;
	}

	public function getDisplayValue($facetValue)
	{
		return $this->valuesData[$facetValue]['VALUE'];
	}

	public function getFilterValue($facetValue)
	{
		return $this->valuesData[$facetValue]['VALUE'];
	}

	public function getFilterData()
	{
		$filter = ['propertyCode' => "PROPERTY_{$this->code}_VALUE", 'filter' => []];

		foreach ($this->filter as $filterValue)
		{
			if(!$this->hasValueInFilter($filterValue))
				continue;

			$filter['filter'][] = $filterValue;
		}

		return $filter;
	}
}