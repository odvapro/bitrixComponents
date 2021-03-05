<?php

use \Bitrix\Main\ErrorCollection;
use \Bitrix\Main\Security\Sign\Signer;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class Form extends CBitrixComponent
{
	public static $SIGNER_SECRET    = "huieMMJ24Vy6";
	public static $SIGNER_SEPARATOR = "||";

	public static $ERROR_TEXT = 1;
	public static $ERROR_404 = 2;

	public function __construct($component = null)
	{
		parent::__construct($component);
		$this->errorCollection = new ErrorCollection();
	}

	public function onPrepareComponentParams($params)
	{
		$params['IBLOCK_ID'] = (int) $params['IBLOCK_ID'];

		if($params['IBLOCK_ID'] <= 0)
		{
			$this->errorCollection->setError(
				new Error('Параметр "IBLOCK_ID" должен иметь числовое значение!', self::$ERROR_TEXT)
			);
		}

		if(!is_string($params['ELEMENT_NAME']) || empty($params['ELEMENT_NAME']))
			$params['ELEMENT_NAME'] = 'Новый элемент';

		if(!is_array($params['PROTECTED_PARAMS']) || empty($params['PROTECTED_PARAMS']))
			$params['PROTECTED_PARAMS'] = false;

		return $params;
	}

	public function executeComponent()
	{
		if ($this->hasErrors())
			return $this->processErrors();

		$arResult['PATH_AJAX'] = dirname(__FILE__).'/ajax.php';

		$this->IncludeComponentTemplate();
	}

	public function getSignedParamsToken()
	{
		if(!$this->arParams['PROTECTED_PARAMS'])
			return false;

		$params = [];

		foreach ($this->arParams['PROTECTED_PARAMS'] as $paramKey)
			if(array_key_exists($paramKey, $this->arParams))
				$params[$paramKey] = $this->arParams[$paramKey];

		if(!count($params))
			return false;

		$signer = self::getSigner();
	}

	public static function getSigner()
	{
		$signer = new Signer;

		$signer->setKey(self::$SIGNER_SECRET);
		$signer->setSeparator(self::$SIGNER_SEPARATOR);

		return $signer;
	}

	private function hasErrors()
	{
		return (bool)count($this->errorCollection);
	}

	private function processErrors()
	{
		if (!empty($this->errorCollection))
			ShowError($error->getMessage());

		return false;
	}
}
