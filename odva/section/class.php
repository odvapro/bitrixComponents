<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class Section extends CBitrixComponent
{
	public function getSection($filter)
	{
		$arSelect = ['UF_PREVIEW_TEXT'];
		$section  = false;
		$res      = CIBlockSection::GetList([], $filter, false, $arSelect);
		if($ob = $res->GetNextElement())
		{
			$arFields = $ob->GetFields();
			$section  = $arFields;
		}
		return $section;
	}
}
