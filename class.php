<?php

use \Bitrix\Main\Error;
use \Bitrix\Main\ErrorCollection;
use \Bitrix\Main\Security\Sign\Signer;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class Form extends CBitrixComponent
{
	public static $SIGNER_SECRET    = "huieMMJ24Vy6";
	public static $SIGNER_SEPARATOR = "---";

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

		$this->arResult['PATH_AJAX'] = $this->getPath() . '/ajax.php';
		$this->arResult['TOKEN']     = $this->getSignedParamsToken();

		$this->IncludeComponentTemplate();
	}

	public function getSignedParamsToken()
	{
		if(!$this->arParams['PROTECTED_PARAMS'])
			return false;

		$params = [
			'IBLOCK_ID'    => $this->arParams['IBLOCK_ID'],
			'ELEMENT_NAME' => $this->arParams['ELEMENT_NAME'],
		];

		if(!empty($this->arParams['PROTECTED_PARAMS']))
		{
			$params['PROTECTED_PARAMS'] = [];

			foreach ($this->arParams['PROTECTED_PARAMS'] as $paramKey)
				if(array_key_exists($paramKey, $this->arParams))
					$params[$paramKey] = $this->arParams[$paramKey];
		}

		$data = base64_encode(json_encode($params));

		return base64_encode($data . self::$SIGNER_SEPARATOR . hash('sha256', $data . self::$SIGNER_SECRET));
	}

	public static function getParamsFromSignedString($signedString)
	{
		if(empty($signedString))
			return false;

		$signedString = base64_decode($signedString);

		list($params, $hash) = explode(self::$SIGNER_SEPARATOR, $signedString);

		if(empty($params) || empty($hash))
			return false;

		$newHash = hash('sha256', $params . self::$SIGNER_SECRET);

		if($hash !== $newHash)
			return false;

		$params = json_decode(base64_decode($params), true);

		return $params;
	}

	private function hasErrors()
	{
		return (bool)count($this->errorCollection);
	}

	private function processErrors()
	{
		if (!empty($this->errorCollection))
			ShowError($this->errorCollection->current()->getMessage());

		return false;
	}
}
