<?php

namespace Odva\Module\Controller;

use \Bitrix\Main\Error;
use \Bitrix\Main\Engine\Controller;

class Order extends Controller
{
	public function configureActions()
	{
		return [
			'getBasket'           => ['prefilters' => []],
			'getUser'         	  => ['prefilters' => []],
			'getDeliveries' 	  => ['prefilters' => []],
			'getPaySystems'       => ['prefilters' => []],
			'getUserProfiles'     => ['prefilters' => []],
			'getOrderCalculate'   => ['prefilters' => []],
			'getLocationsByName'  => ['prefilters' => []],
			'makeOrder'           => ['prefilters' => []],
		];
	}

	public function getBasketAction()
	{
		$result = \Odva\Module\Order::getBasket();

		if(empty($result['PRODUCTS']))
			return $this->addError(new Error('Корзина пуста', 'global'));

		return $result;
	}

	public function getUserAction()
	{
		$result = \Odva\Module\Order::getUser();

		return $result;
	}

	public function getDeliveriesAction()
	{
		$result = \Odva\Module\Order::getDeliveries();

		return $result;
	}

	public function getPaySystemsAction()
	{
		$result = \Odva\Module\Order::getPaySystems();

		return $result;
	}

	public function getUserProfilesAction($name = '', $count = 10)
	{
		$result = \Odva\Module\Order::getUserProfiles($name, $count);

		return $result;
	}

	public function getOrderCalculateAction($deliveryId = '', $paySystemId = '', $cityCode = '', $personTypeId = 1)
	{
		if(empty($deliveryId))
			$this->addError(new Error('Заполните это поле', 'deliveryId'));

		if(empty($paySystemId))
			$this->addError(new Error('Заполните это поле', 'paySystemId'));

		if(!empty($this->getErrors()))
			return;

		$result = \Odva\Module\Order::getOrderCalculate($deliveryId, $paySystemId, $cityCode, $personTypeId);

		return $result;
	}

	public function getLocationsByNameAction($name = '', $count = 10)
	{
		if(empty($name))
			$this->addError(new Error('Заполните это поле', 'name'));

		if(!empty($this->getErrors()))
			return;

		$result = \Odva\Module\Order::getLocationsByName($name, $count);

		return $result;
	}

	public function makeOrderAction($deliveryId = '', $paySystemId = '', $props = '', $cityCode = '', $personTypeId = 1)
	{
		if(empty($deliveryId))
			$this->addError(new Error('Заполните это поле', 'deliveryId'));

		if(empty($paySystemId))
			$this->addError(new Error('Заполните это поле', 'paySystemId'));

		if(empty($props))
			$this->addError(new Error('Заполните это поле', 'props'));

		if(!empty($this->getErrors()))
			return;

		$result = \Odva\Module\Order::makeOrder($deliveryId, $paySystemId, $props, $cityCode, $personTypeId);

		if(!$result['success'])
		{
			if($result['code'] == 'validator')
			{
				foreach ($result['empty'] as $value)
					$this->addError(new Error('Заполните это поле', $value));
			}
			elseif($result['code'] == 'basket')
				$this->addError(new Error('Корзина пуста', 'global'));
			elseif($result['code'] == 'order')
				$this->addError(new Error('Ошибка сохранения заказа', 'global'));

			return;
		}

		return ['ID' => $result['id']];
	}
}
