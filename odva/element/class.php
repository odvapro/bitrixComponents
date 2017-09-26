<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class Element extends CBitrixComponent
{
	/**
	 * getElemet
	 * @return element article
	 */
	public function getElement($filter,$propertiesSettings = [])
	{
		$arSelect = [];
		$res      = CIBlockElement::GetList([], $filter, false, ["nTopCount" => $count], $arSelect);
		if($ob = $res->GetNextElement())
		{
			$arFields               = $ob->GetFields();
			$arFields["PROPERTIES"] = $ob->getProperties();
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
			if(array_key_exists('DETAIL_PICTURE', $propertiesSettings))
			{
				$arFields['DETAIL_PICTURE'] = ['VALUE' => $arFields['DETAIL_PICTURE']];
				$arFields['DETAIL_PICTURE'] = $this->applyImageSettings($propertiesSettings['DETAIL_PICTURE'], $arFields['DETAIL_PICTURE']);
			}
			$arFields["SECTION"] = $this->getSection($arFields['IBLOCK_SECTION_ID']);

			return $arFields;
		}
		return false;
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



}
