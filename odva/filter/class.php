<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class Filter extends CBitrixComponent
{
	/**
	 * [explodeUrlToParams description]
	 * @return array [filter,sort,page]
	 */
	public function explodeUrlToParams($url)
	{
		$resultArray = [];
		$filter      = [];
		$filterArray = explode('/',$url);
		$filterArray = array_chunk($filterArray, 2);
		foreach ($filterArray as $filterFieldKey=>$filterField)
		{
			if(in_array($filterField[0], ['sort','page']))
			{
				$resultArray[$filterField[0]] = $filterField[1];
				unset($filterArray[$filterFieldKey]);
			}
			else
				$filter[$filterField[0]] = $filterField[1];
		}
		$resultArray['filter'] = $filter;
		return $resultArray;
	}
}
