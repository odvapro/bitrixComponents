<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require_once 'BaseField.php';

use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;


class SDirectoryField extends BaseField
{
	private $userTypeSettings;
	private $valuesData = [];

	public function __construct($property, $propertyLink)
	{
		parent::__construct($property, $propertyLink);

		Loader::includeModule("highloadblock");

		$this->userTypeSettings = $property['USER_TYPE_SETTINGS'];
	}

	public function getDisplayValue($facetValue)
	{
		$valueKey = $this->dictionary[$facetValue];
		return $this->valuesData[$valueKey]['UF_NAME'];
	}

	public function getFilterValue($facetValue)
	{
		return $this->dictionary[$facetValue];
	}

	public function loadValuesData()
	{
		$entityData  = HL\HighloadBlockTable::getList([
			'filter'=> ['TABLE_NAME'=>$this->userTypeSettings['TABLE_NAME']]
		])->fetch();

		$entity      = HL\HighloadBlockTable::compileEntity($entityData);
		$hlDataClass = $entity->getDataClass();

		$rsValues = $hlDataClass::getList();

		while($arValue = $rsValues->Fetch())
			$this->valuesData[$arValue['UF_XML_ID']] = $arValue;
	}
}