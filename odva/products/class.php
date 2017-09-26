<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class Products extends CBitrixComponent
{
	public function getProducts($filter,$propertiesSettings,$params)
	{
		$arSelect = [];
		$arOrder  = $this->getSorting($params['sort']);
		$res      = CIBlockElement::GetList($arOrder, $filter, false, ["nPageSize"=>$params['count'],'iNumPage'=>$params['page']], $arSelect);
		$products = [];
		while($ob = $res->GetNextElement())
		{
			$arFields                                          = $ob->GetFields();
			$arFields["PROPERTIES"]                            = $ob->getProperties();

			// apply field setings by handling teir methods
			foreach ($arFields["PROPERTIES"] as $propertyKey => &$porpertyArray)
			{
				if(!array_key_exists($propertyKey, $propertiesSettings)) continue;

				$methodName = "apply{$propertiesSettings[$propertyKey]['type']}Settings";
				if(!method_exists($this,$methodName)) continue;

				$porpertyArray = $this->{$methodName}($propertiesSettings[$propertyKey],$porpertyArray);
			}

			// settings for preview picture
			if(array_key_exists('PREVIEW_PICTURE', $propertiesSettings))
			{
				$arFields['PREVIEW_PICTURE'] = ['VALUE' => $arFields['PREVIEW_PICTURE']];
				$arFields['PREVIEW_PICTURE'] = $this->applyImageSettings($propertiesSettings['PREVIEW_PICTURE'], $arFields['PREVIEW_PICTURE']);
			}

			$arFields["PRICE"]   = $this->getProductBasePrice($arFields["ID"]);
			$arFields["SECTION"] = $this->getSection($arFields['IBLOCK_SECTION_ID']);
			$products[]          = $arFields;
		}
		return $products;
	}

	/**
	 * getSection  by id
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
	 * get products price
	 * @param  int $productId
	 * @return price array or false
	 */
	public function getProductBasePrice($productId)
	{
		$price = CPrice::GetBasePrice($productId);
		if(!$price) return false;
		$price["FORMAT_PRICE"] =  FormatCurrency($price["PRICE"], $price["CURRENCY"]);
		return $price;
	}

	/**
	 * applyImagesSettings
	 * @param  array $settings
	 * @param  array $propertyArray
	 * @return array
	 */
	public function applyImageSettings($settings,$propertyArray)
	{
		if(!empty($settings['sizes']))
		{
			$sizes = [];
			foreach ($settings['sizes'] as $sizeName => $sizeArray)
			{
				$sizes[$sizeName] = CFile::ResizeImageGet(
					$propertyArray['VALUE'],
					['width' => $sizeArray['width'], 'height' => $sizeArray['height']],
					BX_RESIZE_IMAGE_PROPORTIONAL,
					true
				);
			}
			$propertyArray['SIZES'] = $sizes;
		}
		return $propertyArray;
	}

	public $sortings = [
		'id_desc' => [
			'name'      => 'по популярности',
			'filedName' => 'ID',
			'direction' => 'desc',
			'default'   => 1
		],
		'date_desc' => [
			'name'      => 'по дате',
			'filedName' => 'PROPERTY_DATE',
			'direction' => 'desc'
		],
		'price_desc' => [
			'name'      => 'по цене (по убыванию)',
			'filedName' => 'PROPERTY_DATE',
			'direction' => 'desc'
		],
		'price_asc' => [
			'name'      => 'по цене (по возрастанию)',
			'filedName' => 'PROPERTY_DATE',
			'direction' => 'desc'
		],
	];

	/**
	 * [getSorting description]
	 * @param  string $sortString <sortName>-<sortDirection>
	 * @return sort array
	 */
	public function getSorting($sortString)
	{
		if(empty($sortString))
		{
			foreach ($this->sortings as $sortingKey => &$sorting)
				if($sorting['default'] == 1)
				{
					$sortString = $sortingKey;
					break;
				}
		}

		if(!array_key_exists($sortString, $this->sortings)) return [];
		$sortArray = $this->sortings[$sortString];
		$this->sortings[$sortString]['selected'] = 1;

		return ["{$sortArray['filedName']}"=>$sortArray['direction']];
	}
}
