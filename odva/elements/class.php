<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class Elements extends CBitrixComponent
{
	public function getElements($filter,$count = 10)
	{
		$arSelect = [];
		$res      = CIBlockElement::GetList([], $filter, false, ["nPageSize"=>$count], $arSelect);
		$elements = [];
		while($ob = $res->GetNextElement())
		{
			$arFields                    = $ob->GetFields();
			$arFields["PROPERTIES"]      = $ob->getProperties();
			$arFields["PREVIEW_PICTURE"] = CFile::ResizeImageGet($arFields["PREVIEW_PICTURE"], ['width'=>304, 'height'=>224], BX_RESIZE_IMAGE_PROPORTIONAL, true)['src'];
			$arFields["SECTION"]         = $this->getSection($arFields['IBLOCK_SECTION_ID']);
			$elements[]                  = $arFields;
		}
		return $elements;
	}

	/**
	 * getSection by id
	 * @param  int $sectionId section id
	 * @return section info array or false
	 */
	public function getSection($sectionId)
	{
		$res = CIBlockSection::GetByID($sectionId);
		if($sectionRes = $res->GetNext())
			return $sectionRes;
		return false;
	}

	/**
	 * getSection by code
	 * @param  string $sectionCode
	 * @return setion
	 */
	public function getSectionByCode($sectionCode)
	{
		$dbList = CIBlockSection::GetList([], ['CODE'=>$sectionCode], true);
		if($aResult = $dbList->GetNext())
			return $aResult;
		return false;
	}
}
