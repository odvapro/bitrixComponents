<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class Cart extends CBitrixComponent
{
	public function getCartFromCookies()
	{
		$cartArray = (!empty($_COOKIE['cart']))?json_decode($_COOKIE['cart'],true):[];
		return $cartArray;
	}
	public function getCartProductIds()
	{
		$cartArray = $this->getCartFromCookies();
		$offerIds = [];
		foreach ($cartArray as $cartOffer)
		{
			$offerIds[] = $cartOffer['productId'];
		}
		return $offerIds;
	}

	public function getSumm()
	{
		$cartArray = $this->getCartFromCookies();
		$cartSumm = 0;
		foreach ($cartArray as $cartOffer)
		{
			$cartSumm += intval($cartOffer['price'])*intval($cartOffer['count']);
		}
		return $cartSumm;
	}

	public function getCount()
	{
		$cartArray = $this->getCartFromCookies();
		$productsCount = 0;
		foreach ($cartArray as $cartOffer)
		{
			$productsCount += $cartOffer['count'];
		}
		return $productsCount;
	}
}
