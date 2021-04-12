<?php

namespace Odva\Module\Controller;

use \Bitrix\Main\Error;
use \Bitrix\Main\Engine\Controller;

class Basket extends Controller
{
	public function configureActions()
	{
		return [
			'addItem'            => ['prefilters' => []],
			'deleteItem'         => ['prefilters' => []],
			'changeItemQuantity' => ['prefilters' => []],
			'clear'              => ['prefilters' => []],
			'getCount'           => ['prefilters' => []],
			'applyCoupon'        => ['prefilters' => []],
			'deleteCoupon'       => ['prefilters' => []],
			'getInfo'            => ['prefilters' => []],
		];
	}

	public function applyCouponAction($coupon='')
	{
		$result = \Odva\Module\Basket::applyCoupon($coupon);

		if(!$result)
			$this->addError(new Error('Промо-код введен неправильно', 'promocode'));

		return \Odva\Module\Basket::getInfo();
	}

	public function deleteCouponAction($coupon='')
	{
		\Odva\Module\Basket::deleteCoupon($coupon);
		return \Odva\Module\Basket::getInfo();
	}

	public function getInfoAction()
	{
		return \Odva\Module\Basket::getInfo();
	}

	public function getCountAction()
	{
		return \Odva\Module\Basket::getCount();
	}

	public function clearAction()
	{
		$del = \Odva\Module\Basket::clear();
		return \Odva\Module\Basket::getCount();
	}

	public function addItemAction($productId = 0, $quantity = 0)
	{
		$productId = intval($productId);
		$quantity  = intval($quantity);

		if($productId <= 0)
			$this->addError(new Error('Заполните это поле', 'productId'));

		if($quantity == 0)
			$this->addError(new Error('Заполните это поле', 'quantity'));

		if(!empty($this->getErrors()))
			return \Odva\Module\Basket::getCount();

		$result = \Odva\Module\Basket::addItem($productId, $quantity);

		if($result->isSuccess())
			return \Odva\Module\Basket::getCount();

		$this->addErrors($result->getErrors());

		return \Odva\Module\Basket::getCount();
	}

	public function deleteItemAction($productId = 0)
	{
		$productId = intval($productId);

		if($productId <= 0)
			$this->addError(new Error('Заполните это поле', 'productId'));

		if(!empty($this->getErrors()))
			return \Odva\Module\Basket::getInfo();

		$result = \Odva\Module\Basket::deleteItem($productId);

		if(!is_a($result, '\Bitrix\Main\Error'))
			return \Odva\Module\Basket::getInfo();

		$this->addError($result);

		return \Odva\Module\Basket::getInfo();
	}

	public function changeItemQuantityAction($productId = 0, $quantity = 0)
	{
		$productId = intval($productId);
		$quantity  = intval($quantity);

		if($productId <= 0)
			$this->addError(new Error('Заполните это поле', 'productId'));

		if($quantity == 0)
			$this->addError(new Error('Заполните это поле', 'quantity'));

		if(!empty($this->getErrors()))
			return \Odva\Module\Basket::getInfo();

		$result = \Odva\Module\Basket::changeItemQuantity($productId, $quantity);

		if(is_a($result, '\Bitrix\Main\Error'))
		{
			$this->addError($result);
			return \Odva\Module\Basket::getInfo();
		}

		if(!$result)
			$this->addError(new Error('Не удалось изменить количество'));

		return \Odva\Module\Basket::getInfo();
	}
}
