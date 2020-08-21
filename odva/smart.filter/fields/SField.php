<?php

require_once 'BaseField.php';

class SField extends BaseField
{
	public function getDisplayValue($facetValue)
	{
		return $this->dictionary[$facetValue];
	}

	public function getFilterValue($facetValue)
	{
		return htmlspecialcharsEx($this->dictionary[$facetValue]);
	}
}