<?
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");
use Bitrix\Main,
Bitrix\Main\Localization\Loc as Loc,
Bitrix\Main\Loader,
Bitrix\Main\Config\Option,
Bitrix\Sale\Delivery,
Bitrix\Sale\PaySystem,
Bitrix\Sale,
Bitrix\Sale\Order,
Bitrix\Sale\DiscountCouponsManager,
Bitrix\Main\Context;
//класс для работы с оформлением заказа
class OrderMake extends CBitrixComponent
{
	//метод класс достает способы доставки
	public function getDeliveres()
	{
		$db = \Bitrix\Sale\Delivery\Services\Table::getList(array(
		    'filter' => array('ACTIVE'=>'Y')
		));
		while($delivery = $db->fetch())
		{
		    $result[] = $delivery;
		}
		return $result;
	}
	//метод класса достает пересчитаную цену для товаров
	public static function getPrice($items)
	{
		$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
		$context = new \Bitrix\Sale\Discount\Context\Fuser($basket->getFUserId());
		$discounts = \Bitrix\Sale\Discount::buildFromBasket($basket, $context);
		$r = $discounts->calculate();
		$result = $r->getData();
		foreach ($result['BASKET_ITEMS'] as $idProduct => $itemPrice)
		{
			$value += ($itemPrice['PRICE'] * $items[$idProduct]['QUANTITY']);
			$priceDiscount += ($itemPrice['DISCOUNT_PRICE'] * $items[$idProduct]['QUANTITY']);
		}
		return ['VALUE' => $value,'DISCOUNT'=>$priceDiscount];

	}
	//метод достает товары из корзины
	public static function getProducts()
	{
		$dbBasketItems = CSaleBasket::GetList(
			[],
			[
				"FUSER_ID" => CSaleBasket::GetBasketUserID(),
                "LID" => SITE_ID,
                "ORDER_ID" => "NULL"
            ],
			false,
			false,
			[]
		);
		while ($arItems = $dbBasketItems->Fetch())
		{
		    if (strlen($arItems["CALLBACK_FUNC"]) > 0)
		    {
		        CSaleBasket::UpdatePrice(
		        	$arItems["ID"],
		            $arItems["CALLBACK_FUNC"],
		            $arItems["MODULE"],
		            $arItems["PRODUCT_ID"],
		            $arItems["QUANTITY"]
		        );
		        $arItems = CSaleBasket::GetByID($arItems["ID"]);
		    }

		    $arBasketItems[$arItems["ID"]] = $arItems;
		}
		return $arBasketItems;
	}
	//метод достает данные о товаре из инфоблока
	public function getProduct($filter)
	{
		$res	 = CIBlockElement::GetList([], $filter, false, [], []);
		$product = false;
		if($ob   = $res->GetNextElement())
		{
			$product = $ob->GetFields();
			$product['PROPERTIES'] = $ob->getProperties();
		}
		return $product;
	}
	//метод является аналогом component.php
	public function executeComponent()
	{
		//DiscountCouponsManager::clear(true); // удалить купон
		$arBasketItems = $this->getProducts();
		$this->arResult['ITEMS'] = [];
		foreach ($arBasketItems as $product)
		{
			$item = $this->getProduct(['IBLOCK_ID' => $this->arParams['filter']['IBLOCK_ID'],'ID' => $product['PRODUCT_ID']]);
			$subProcduct['NAME'] = $item['NAME'];
			$subProcduct['PICTURE'] = $item['DETAIL_PICTURE'];
			$subProcduct['QUANTITY'] = $product['QUANTITY'];
			$this->arResult['COUNT_PRODUCTS'] += $product['QUANTITY'];
			$this->arResult['ITEMS'][$product['ID']] = $subProcduct;
		}
		if(!empty($this->arResult['ITEMS']))
		{
			$this->arResult['PRICE'] = $this->getPrice($this->arResult['ITEMS']);
		}
		$this->arResult['PATH_ACTIVATE_RPOMOCOD'] = $this->GetPAth().'/promocod.php';
		$this->arResult['PATH_MAKE_ORDER'] = $this->GetPAth().'/makeOrder.php';
		$this->arResult['DELIVERES'] = $this->getDeliveres();
		$this->IncludeComponentTemplate();
	}
}
