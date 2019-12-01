<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class Orders extends CBitrixComponent
{
	public function getOrders($arFilter = [])
	{
		$dbSales = CSaleOrder::GetList(["DATE_INSERT" => "DESC"], $arFilter);
		$orders = [];
		while ($arSales = $dbSales->Fetch())
		{
			$arSales['PRICE_FORMAT']       = FormatCurrency($arSales["PRICE"], $arSales["CURRENCY"]);
			$arSales['DELIVERY_NAME']      = $this->getDeliveryName($arSales["DELIVERY_ID"]);
			$arSales['PAYMENT_NAME']       = $this->getPaymentName($arSales["PAY_SYSTEM_ID"]);
			$arSales['STATUS_NAME']        = $this->getStatusName($arSales["STATUS_ID"]);
			$arSales['FORMAT_DATE_INSERT'] = FormatDate("d.m.Y H:i", MakeTimeStamp($arSales["DATE_INSERT"]));
			$arSales['PRODUCTS']           = $this->getProducts($arSales['ID']);
			$arSales['PRODUCT_IDS']        = array_column($arSales['PRODUCTS'], 'PRODUCT_ID');
			$arSales['PROPERTIES']         = $this->getOrderProps($arSales['ID']);
			$orders[] = $arSales;
		}
		return $orders;
	}

	/**
	 * Gets order products
	 * @param  int $orderId order id
	 * @return array
	 */
	public function getProducts($orderId)
	{
		$dbItemsInOrder = CSaleBasket::GetList(["ID" => "ASC"], ["ORDER_ID" => $orderId]);
		$products = [];
		while ($arItem = $dbItemsInOrder->Fetch())
		{
			$arItem['PROPERTIES'] = $this->getProductProps($arItem['ID']);
			$products[$arItems['PRODUCT_ID']] = $arItem;
		}
		return $products;
	}

	/**
	 * Delivaery name
	 * @param  string $deliveryId
	 * @return string
	 */
	public function getDeliveryName($deliveryId)
	{
		$dbResult = CSaleDeliveryHandler::GetBySID($deliveryId);
		if ($deliveryArr = $dbResult->GetNext())
			return $deliveryArr['NAME'];
		return '';
	}

	/**
	 * Gets PaySistem name
	 * @param  int $paymentId pay sistem id
	 * @return string
	 */
	public function getPaymentName($paymentId)
	{
		if($arPaySys = CSalePaySystem::GetByID($paymentId))
			return $arPaySys['NAME'];
		return '';
	}

	/**
	 * Gets orderStatus name
	 * @param  strign $statusCode status code
	 * @return string
	 */
	public function getStatusName($statusCode)
	{
		if ($arStatus = CSaleStatus::GetByID($statusCode))
			return $arStatus['NAME'];
		return '';
	}

	/**
	 * Возвращает все свойства заказа
	 * @param  int $orderId
	 * @return array
	 */
	public function getOrderProps($orderId)
	{
		$orderProps = [];
		$orderPropsObj = CSaleOrderPropsValue::GetList([], ["ORDER_ID" => $orderId] );
		while($arVals = $orderPropsObj->Fetch())
			$orderProps[$arVals['CODE']] = $arVals;
		return $orderProps;
	}

	public function getProductProps($orderProductId)
	{
		$productProps = [];
		$db_res = CSaleBasket::GetPropsList(
			["SORT" => "ASC", "NAME" => "ASC"],
			["BASKET_ID" => $orderProductId,]
		);
		while($ar_res = $db_res->Fetch())
			$productProps[] = $ar_res;
		return $productProps;
	}
}
