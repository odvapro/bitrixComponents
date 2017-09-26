<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class Sections extends CBitrixComponent
{
	public function getSections($filter, $count = 10)
	{
		$arSelect = [];
		$sections = [];
		$res = CIBlockSection::GetList([], $filter, true, $arSelect,["nPageSize" => $count]);
		while($ob = $res->GetNextElement())
		{
			$arFields            = $ob->GetFields();
			$arFields["PICTURE"] = CFile::ResizeImageGet($arFields["PICTURE"], ['width'=>639, 'height'=>551], BX_RESIZE_IMAGE_PROPORTIONAL, true);
			$arFields['ELEMENTS_COUNT'] = $this->formatProductsCount($arFields['ELEMENT_CNT']);
			$sections[]          = $arFields;
		}
		return $sections;
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
