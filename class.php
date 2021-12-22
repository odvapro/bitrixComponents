<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Error;
use \Bitrix\Main\ErrorCollection;

class Element extends \CBitrixComponent
{
	const ERROR_TEXT = 1;
	const ERROR_404 = 2;

	public function __construct($component = null)
	{
		parent::__construct($component);
		$this->errorCollection = new ErrorCollection();
	}

	public function onPrepareComponentParams($params)
	{
		if(!Loader::includeModule('iblock'))
			return false;

		if(!array_key_exists('id', $params) && !array_key_exists('code', $params))
		{
			$this->errorCollection->setError(
				new Error('Необходимо передать один из параметров [id, code]!', self::ERROR_TEXT)
			);
			return $params;
		}

		if(array_key_exists('id', $params) && ((int)$params['id'] <= 0 || (int)$params['id'] != $params['id']))
		{
			$this->errorCollection->setError(
				new Error('Параметр "id" должен иметь числовое значение!', self::ERROR_TEXT)
			);
			return $params;
		}

		if(
			array_key_exists('iblock_id', $params)
			&&
			((int)$params['iblock_id'] <= 0 || (int)$params['iblock_id'] != $params['iblock_id'])
		)
		{
			$this->errorCollection->setError(
				new Error('Параметр "iblock_id" должен иметь числовое значение!', self::ERROR_TEXT)
			);
			return $params;
		}

		if(!is_array($params['sort']) || empty($params['sort']))
			$params['sort'] = ['SORT' => 'ASC'];

		if(
			!array_key_exists('id', $params)
			&&
			array_key_exists('code', $params)
			&&
			(!is_string($params['code']) || empty($params['code']))
		)
		{
			$this->errorCollection->setError(
				new Error('Параметр "code" должен иметь строковое значение!', self::ERROR_TEXT)
			);
			return $params;
		}

		if(!array_key_exists('iblock_id', $params) && empty($params['iblock_id']))
		{
			$this->errorCollection->setError(new Error('Необходимо передать параметр "iblock_id"!', self::ERROR_TEXT));
			return $params;
		}

		if(!array_key_exists('id', $params))
			$params['id'] = false;

		if(!array_key_exists('code', $params))
			$params['code'] = false;

		if(!array_key_exists('props', $params) || !is_array($params['props']))
			$params['props'] = [];

		if(!array_key_exists('show_element_cnt', $params))
			$params['show_element_cnt'] = false;

		return $params;
	}
	public function executeComponent()
	{
		if ($this->hasErrors())
			return $this->processErrors();

		$filter = ['IBLOCK_ID' => $this->arParams['iblock_id']];
		$elementCnt = $this->arParams['show_element_cnt'];
		$sort = $this->arParams['sort'];

		if(!empty($this->arParams['id']))
			$filter['ID'] = $this->arParams['id'];
		else
			$filter['CODE'] = $this->arParams['code'];

		$select = array_merge(['*'], $this->arParams['props']);

		$element = CIBlockSection::GetList($sort, $filter, $elementCnt, $select, false)->GetNext();

		if(!$element)
		{
			if(empty($this->arParams['set_code_404']) || $this->arParams['set_code_404'] != 'N')
				$this->errorCollection->setError(new Error("Елемент не найден.", self::ERROR_404));
			else
				$this->errorCollection->setError(new Error("Елемент не найден.", self::ERROR_TEXT));

			$this->arResult = false;
		}

		$this->arResult = $element;

		$this->IncludeComponentTemplate();
	}

	private function hasErrors()
	{
		return (bool)count($this->errorCollection);
	}

	private function processErrors()
	{
		if (!empty($this->errorCollection))
		{
			/** @var Error $error */
			foreach ($this->errorCollection as $error)
			{
				$code = $error->getCode();

				if ($code == self::ERROR_404)
				{
					\Bitrix\Iblock\Component\Tools::process404(
						trim($this->arParams['MESSAGE_404']) ?: $error->getMessage(),
						true,
						true,
						false
					);
				}
				elseif (
					$code == self::ERROR_TEXT
					&&
					(empty($this->arParams['show_errors']) || $this->arParams['show_errors'] != 'N')
				)
				{
					ShowError($error->getMessage());
				}
			}
		}

		return false;
	}
}
