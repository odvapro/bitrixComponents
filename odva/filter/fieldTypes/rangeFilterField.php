<?php
class rangeFilterField extends FilterField
{
	public function getValues()
	{
		// define products
		// define offers price range
		$arSelect = ["ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM"];
		$arFilter = array_merge($this->filter,["ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y"]);
		$res = CIBlockElement::GetList(['PROPERTY_MIN_PRICE'=>'desc'], $arFilter, false, [], $arSelect);
		$minPrice = 0;
		if($ob = $res->GetNextElement())
		{
			$arFields = $ob->GetFields();
			$minPrice = intval($arFields['PROPERTY_MIN_PRICE_VALUE']);
		}
		$res = CIBlockElement::GetList(['PROPERTY_MAX_PRICE'=>'desc'], $arFilter, false, [], $arSelect);
		$maxPrice = 0;
		if($ob = $res->GetNextElement())
		{
			$arFields = $ob->GetFields();
			$maxPrice = intval($arFields['PROPERTY_MAX_PRICE_VALUE']);
		}
		$currentValues = (empty($this->value))?[$minPrice,$maxPrice]:$this->value;
		return ['maximum'=>[$minPrice,$maxPrice],'current'=>$currentValues];
	}

	public function getFilter()
	{
		if(count($this->value) < 1) return [];
		return [
			">={$this->settings['propName']}" => $this->value[0],
			"<={$this->settings['propName']}" => $this->value[1]
		];
	}

	public $value = [];
	public function setValue($value)
	{
		if(!empty($value))
			$this->value = explode('_',$value);
	}
}
