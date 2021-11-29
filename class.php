<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class OdvaSections extends CBitrixComponent
{
	public function onPrepareComponentParams($params)
	{
		$params = parent::onPrepareComponentParams($params);

		if(!is_array($params['filter']) || empty($params['filter']))
			$params['filter'] = [];

		if(!is_array($params['sort']) || empty($params['sort']))
			$params['sort'] = ['SORT' => 'ASC'];

		$params['element_cnt'] = !empty($params['elements_count']);

		if(!is_array($params['select']) || empty($params['select']))
			$params['select'] = [];

		if(empty($params['count']) || intval($params['count']) <= 0)
			$params['nav_params'] = ['nTopCount' => false];
		else
			$params['nav_params'] = ['nTopCount' => intval($params['count'])];

		return $params;
	}

	public function executeComponent()
	{
		if(!\Bitrix\Main\Loader::includeModule("iblock"))
			return;

		$res = CIBlockSection::GetList(
			$this->arParams['sort'],
			$this->arParams['filter'],
			$this->arParams['element_cnt'],
			$this->arParams['select'],
			$this->arParams['nav_params']
		);

		$this->arResult['SECTIONS'] = [];

		while($section = $res->Fetch())
			$this->arResult['SECTIONS'][] = $section;

		$this->IncludeComponentTemplate();
	}
}
