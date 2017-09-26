<?php
class listFilterField extends FilterField
{
	public function getValues()
	{
		$props = [];
		$db_enum_list = CIBlockProperty::GetPropertyEnum(
			$this->settings['propName'],
			[],
			["IBLOCK_ID"=>$this->settings['iBclockId']]
		);
		while($enumList = $db_enum_list->GetNext())
		{
			if(in_array($enumList['ID'], $this->value))
				$enumList['SELECTED'] = 1;
			$props[] = $enumList;
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
