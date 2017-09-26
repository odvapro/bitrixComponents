<?php
class offersLIstFilterField extends FilterField
{
	public function getValues()
	{
		$props = [];
		$arSelect = ["ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*"];
		$arFilter = ["IBLOCK_ID"=>$this->settings['iBclockId'], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y"];
		$res = CIBlockElement::GetList(
			[],
			$arFilter,
			["PROPERTY_{$this->settings['propName']}"],
			["nPageSize"=>50],
			$arSelect
		);
		while($ob = $res->GetNextElement())
		{
			$prop = [];
			$arFields = $ob->GetFields();
			$prop['ID'] = $arFields["PROPERTY_{$this->settings['propName']}_ENUM_ID"];
			$prop['NAME'] = $arFields["PROPERTY_{$this->settings['propName']}_VALUE"];
			if(in_array($prop['ID'], $this->value))
				$prop['SELECTED'] = 1;
			$props[] = $prop;
		}

		return $props;
	}

	public function getFilter()
	{
		if(count($this->value) < 1) return [];
		return ["PROPERTY_{$this->settings['propName']}" => $this->value];
	}

	public $value = [];
	public function setValue($value)
	{
		if(!empty($value))
			$this->value = explode('-',$value);
	}
}
