<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class Product extends CBitrixComponent
{
	/**
	 * get one product
	 * @param  array $filter
	 * @param  array  $propertiesSettings
	 * @return product array or false
	 */
	public function getProduct($filter,$propertiesSettings = [])
	{
		$arSelect = [];
		$res      = CIBlockElement::GetList([], $filter, false, [], $arSelect);
		$product = false;
		if($ob = $res->GetNextElement())
		{
			$product               = $ob->GetFields();
			$product["PROPERTIES"] = $ob->getProperties();

			foreach ($product["PROPERTIES"] as $propertyKey => &$porpertyArray)
			{
				if(!array_key_exists($propertyKey, $propertiesSettings)) continue;

				$methodName = "apply{$propertiesSettings[$propertyKey]['type']}Settings";
				if(!method_exists($this,$methodName)) continue;

				$porpertyArray = $this->{$methodName}($propertiesSettings[$propertyKey],$porpertyArray);
			}

			// settings for preview picture
			if(array_key_exists('PREVIEW_PICTURE', $propertiesSettings))
			{
				$product['PREVIEW_PICTURE'] = ['VALUE' => $product['PREVIEW_PICTURE']];
				$product['PREVIEW_PICTURE'] = $this->applyImageSettings($propertiesSettings['PREVIEW_PICTURE'], $product['PREVIEW_PICTURE']);
			}

			$product["PRICE"]     = $this->getProductBasePrice($product["ID"]);
			$product["MIN_PRICE"] =  FormatCurrency($product["PROPERTIES"]['MIN_PRICE']['VALUE'], 'RUB');
			$product["SECTION"]   = $this->getSection($product['IBLOCK_SECTION_ID']);
		}

		return $product;
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
	 * applyImageSettings
	 * @param  array $settings
	 * @param  array $propertyArray
	 * @return array
	 */
	public function applyImageSettings($settings,$propertyArray)
	{
		if(empty($propertyArray['VALUE'])) return $propertyArray;
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

	/**
	 * applyImagesSettings
	 * @param  array $settings
	 * @param  array $propertyArray
	 * @return array
	 */
	public function applyImagesSettings($settings,$propertyArray)
	{
		if(empty($propertyArray['VALUE'])) return $propertyArray;
		if(!empty($settings['sizes']))
		{
			// path throu pictures
			$pictures = [];
			foreach ($propertyArray['VALUE'] as $pictureId)
			{
				$sizes = [];
				foreach ($settings['sizes'] as $sizeName => $sizeArray)
				{
					$sizes[$sizeName] = CFile::ResizeImageGet(
						$pictureId,
						['width' => $sizeArray['width'], 'height' => $sizeArray['height']],
						BX_RESIZE_IMAGE_PROPORTIONAL,
						true
					);
				}
				$pictures[] = $sizes;
			}
			$propertyArray['SIZES'] = $pictures;
		}
		return $propertyArray;
	}

	public function getOffers($filter)
	{
		$arSelect = [];
		$res      = CIBlockElement::GetList([], $filter, false, [], $arSelect);
		$offers = [];
		while($ob = $res->GetNextElement())
		{
			$arFields               = $ob->GetFields();
			$arFields["PROPERTIES"] = $ob->getProperties();
			$arFields["PRICE"]      = $this->getProductBasePrice($arFields['ID']);

			$offers[] = $arFields;
		}
		return $offers;
	}
}
