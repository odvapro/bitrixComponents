<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class Cart extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->arResult['PRODUCTS'] = \Odva\Module\Basket::getItemsArray();
		$this->arResult['COUNT']    = \Odva\Module\Basket::getCount();
		$this->arResult['PRICE']    = \Odva\Module\Basket::getPrice();

		$this->IncludeComponentTemplate();
	}
}
