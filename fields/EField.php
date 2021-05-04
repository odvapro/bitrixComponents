<?php
require_once 'BaseField.php';

class EField extends BaseField
{
	private $valuesData = [];

	public function loadValuesData()
	{
		$propertiesId = [];

		foreach ($this->facet['facetsList'] as $facet)
		{
			$propertyId = $this->facet['facet']->getStorage()->facetIdToPropertyId($facet['FACET_ID']);
			if($propertyId == $this->id)
				$propertiesId[] = $facet['VALUE'];
		}

		$iblock_link = $this->link_iblock_id;

		$rsPropertyEnums = CIBlockElement::GetList([], ['IBLOCK_ID' => $iblock_link, 'ID' => $propertiesId], false, false,['NAME', 'ID']);
		while($enumField = $rsPropertyEnums->Fetch())
			$this->valuesData[$enumField['ID']] = $enumField;
	}

	public function getDisplayValue($facetValue)
	{
		return $this->valuesData[$facetValue]['NAME'];
	}

	public function getFilterValue($facetValue)
	{
		return $this->valuesData[$facetValue]['NAME'];
	}

	public function getFilterData()
	{
		$filter = ['propertyCode' => "PROPERTY_{$this->code}.NAME", 'filter' => []];

		foreach ($this->filter as $filterValue)
		{
			if(!$this->hasValueInFilter($filterValue))
				continue;

			$filter['filter'][] = $filterValue;
		}

		return $filter;
	}
}
