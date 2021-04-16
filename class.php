<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class OrderMake extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->arResult['BASKET'] = \Odva\Module\Order::getBasket();

		if(empty($this->arResult['BASKET']['PRODUCTS']))
		{
			$this->IncludeComponentTemplate();
			return;
		}

		$this->arResult['USER'] = \Odva\Module\Order::getUser();
		$this->arResult['DELIVERIES'] = \Odva\Module\Order::getDeliveries();
		$this->arResult['PAY_SYSTEMS'] = \Odva\Module\Order::getPaySystems();
		$this->arResult['PROFILES'] = \Odva\Module\Order::getUserProfiles();

		$this->IncludeComponentTemplate();
	}
}
