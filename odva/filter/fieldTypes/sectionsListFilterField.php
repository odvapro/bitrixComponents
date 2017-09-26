<?php
class sectionsListFilterField extends FilterField
{
	public function getValues()
	{
		$sections = [];
		$res = CIBlockSection::GetList([], ['IBLOCK_ID'=>1,'UF_INFILTER'=>1], true, ["nPageSize" => 10], []);
		while($ob = $res->GetNextElement())
		{
			$arFields = $ob->GetFields();
			if(in_array($arFields['ID'], $this->value))
				$arFields['SELECTED'] = 1;
			$sections[]  = $arFields;
		}
		return $sections;
	}

	public function getFilter()
	{
		if(count($this->value) < 1) return [];
		return ['SECTION_ID' => $this->value];
	}

	public $value = [];
	public function setValue($value)
	{
		if(!empty($value))
			$this->value = explode('-',$value);
	}
}
