<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class Elements extends CBitrixComponent
{
	public $pages = [];
	public function getElements($sort,$filter,$count,$page = 1,$tempPagn = "")
	{
		if(!isset($page))
			$page = 1;
		if(!isset($count))
			$count = 10;
		$res      = CIBlockElement::GetList($sort, $filter, false, ["nPageSize"=>$count,"iNumPage"=>$page], []);
		$elements = [];
		while($ob = $res->GetNextElement())
		{
			$arFields                    = $ob->GetFields();
			$arFields["PROPERTIES"]      = $ob->getProperties();
			$arFields["PREVIEW_PICTURE"] = CFile::GetPath($arFields["PREVIEW_PICTURE"]);
			$arFields["SECTION"]         = $this->getSection($arFields['IBLOCK_SECTION_ID']);
			$elements[]                  = $arFields;
		}
		$result["ITEMS"] = $elements;
		if(isset($tempPagn))
			$result['PAGINATOR'] = $res->GetPageNavStringEx($navComponentObject, 'Заголовок',$tempPagn, 'Y');
		return $result;
	}

	/**s
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
