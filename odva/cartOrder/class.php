<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class CartOrder extends CBitrixComponent
{
	public static function isCartEmpty()
	{
		$cartArray = (!empty($_COOKIE['cart']))?json_decode($_COOKIE['cart'],true):[];
		return (count($cartArray))?false:true;
	}

	/**
	 * Return empty order
	 * @param  array $params
	 * @return order id or 0
	 */
	public static function makeEmptyOrder($params)
	{
		global $USER;
		$arFields = [
			"LID"              => SITE_ID,
			"PERSON_TYPE_ID"   => 1,
			"PAYED"            => "N",
			"CANCELED"         => "N",
			"STATUS_ID"        => "N",
			"PRICE"            => 0,
			"CURRENCY"         => "RUB",
			"USER_ID"          => 1,
			"PAY_SYSTEM_ID"    => 2,
			"PRICE_DELIVERY"   => 0,
			"DELIVERY_ID"      => 2,
			"DISCOUNT_VALUE"   => 0,
			"TAX_VALUE"        => 0,
			"USER_DESCRIPTION" => ""
		];
		$arFields = array_merge($arFields,$params);

		if (CModule::IncludeModule("statistic"))
		$arFields["STAT_GID"] = CStatistic::GetEventParam();

		$orderId = CSaleOrder::Add($arFields);
		return intval($orderId);
	}

	public static function setOrderProps($orderId,$props)
	{
		foreach ($props as $prop)
		{
			CSaleOrderPropsValue::Add([
				"ORDER_ID"       => $orderId,
				"ORDER_PROPS_ID" => $prop['id'],
				"NAME"           => $prop['name'],
				"CODE"           => $prop['bitrixCode'],
				"VALUE"          => $prop['value']
			]);
		}
	}

	/**
	 * Add product to the order
	 * @param int $orderID order id
	 * @param array $product products array [prduct required fields id,quantity,props =['code','name','value'] ]
	 */
	public static function addProductToOrder($orderID, $product)
	{
		$basketProps = [];
		if(!empty($product['props']))
			foreach ($product['props'] as $prop)
			{
				$basketProps[] = [
					"NAME"  => $prop['name'],
					"CODE"  => $prop['code'],
					"VALUE" => $prop['value']
				];
			}
		if (Add2BasketByProductID($product['id'], $product['quantity'], ['ORDER_ID' => $orderID], $basketProps))
			return true;

		return false;
	}

	public function getCities()
	{
		$cities = [];
		$dbVars = CSaleLocation::GetList(
			["SORT" => "ASC", "COUNTRY_NAME_LANG" => "ASC", "CITY_NAME_LANG" => "ASC"],
			["LID" => LANGUAGE_ID,'!CITY_NAME'=>false],
			false,
			false,
			[]
		);
		while ($vars = $dbVars->Fetch())
		{
			$cities[] = $vars['CITY_NAME'];
		}
		return $cities;
	}


}
