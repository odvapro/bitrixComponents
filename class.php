<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class Sections extends CBitrixComponent
{
	public function executeComponent()
	{
		if(!\Bitrix\Main\Loader::includeModule("iblock"))
			return;

		$this->getSections($this->arParams['filter'], $this->arParams['sort'], $this->arParams["count"]);

		$this->IncludeComponentTemplate();
	}
	public function getSections($filter, $sort, $count = 10)
	{
		$arSelect = [];
		$sections = [];

		if(!is_array($sort) || empty($sort))
			$sort = [];

		$res = CIBlockSection::GetList($sort, $filter, true, $arSelect,["nPageSize" => $count]);
		while($ob = $res->GetNextElement())
		{
			$arFields            = $ob->GetFields();
			$arFields["PICTURE"] = CFile::ResizeImageGet($arFields["PICTURE"], ['width'=>639, 'height'=>551], BX_RESIZE_IMAGE_PROPORTIONAL, true);
			$arFields['ELEMENTS_COUNT'] = $this->formatProductsCount($arFields['ELEMENT_CNT']);
			$sections[]          = $arFields;
		}
		$this->arResult["SECTIONS"] = $sections;
	}

	public function formatProductsCount($count)
	{
		if($count == 1)
			return "{$count} продукт";
		elseif($count > 1 and $count < 5)
			return "{$count} продукта";
		elseif($count > 4)
			return "{$count} продуктов";
		else
			return "Нет продуктов";
	}

}
