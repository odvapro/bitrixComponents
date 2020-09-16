<?php

require_once 'BaseField.php';

class PriceField extends BaseField
{
	public function getFilterData()
	{
		$values = $this->getValues();

		$filter = [
			'propertyCode' => "><{$this->code}",
			'filter'       => [
				$values['FROM'], $values['TO']
			]
		];
		return $filter;
	}

	public function getValues()
	{
		return current($this->values);
	}

	public function addValueFromFacet($facet)
	{
		if($facet['MIN_VALUE_NUM'] == $facet['MAX_VALUE_NUM'])
			$facet['MIN_VALUE_NUM'] = 0;

		$this->values[$facet['VALUE']] = [
			'MIN'           => $facet['MIN_VALUE_NUM'],
			'MAX'           => $facet['MAX_VALUE_NUM'],
			'ELEMENT_COUNT' => 0
		];

		$prices = $this->filter;

		if(empty($prices))
		{
			$this->values[$facet['VALUE']]['FROM'] = $facet['MIN_VALUE_NUM'];
			$this->values[$facet['VALUE']]['TO']   = $facet['MAX_VALUE_NUM'];
			return;
		}

		if(count($prices) > 2)
			$prices = array_slice($prices, 0, 2);

		$prices = array_map('floatval', $prices);

		sort($prices);

		$this->values[$facet['VALUE']]['FROM'] = $prices[0];

		if(count($prices) == 1)
			$this->values[$facet['VALUE']]['TO']   = $this->values[$facet['VALUE']]['MAX'];
		else
			$this->values[$facet['VALUE']]['TO']   = $prices[1];
	}

	public function getDisplayValue($facetValue)
	{
		return false;
	}

	public function getFilterValue($facetValue)
	{
		return false;
	}

	public function getFilterCode()
	{
		return $this->code;
	}
}