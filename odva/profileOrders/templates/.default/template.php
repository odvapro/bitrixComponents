<h3 class="personal-data__title">История заказов</h3>
<div class="order-history__items"><?php
	for ($arResult['ORDERS'] as $order)
	{
		?><div class="order-history__item clearfix">
			<div class="order-history__item-wrapper clearfix">
				<div class="order-history__item-title">
					<span class="order-num">Заказ № <?=$order['ID']?></span>
					<!-- <span class="order-date">от 28.07.2017 09:27</span> -->
					<span class="order-date">от <?=$order['FORMAT_DATE_INSERT']?></span>
				</div>
				<div class="order-history__item-status <?=($order['STATUS_ID'] == 'F')?'order-history__item-status__green':''?>"><?=$order['STATUS_NAME']?></div>
			</div>
			<div class="order-history__item-descr">
				<div class="order-history__item-descr-top clearfix">
					<div class="order-history__item-descr-top--left">Сумма:</div>
					<div class="order-history__item-descr-top--right"><?=$order['PRICE_FORMAT']?></div>
				</div>
				<div class="order-history__item-descr-middle clearfix">
				<div class="order-history__item-descr-middle--left">Доставка:</div>
					<div class="order-history__item-descr-middle--right"><?=$order['DELIVERY_NAME']?></div>
				</div>
				<div class="order-history__item-descr-bot clearfix">
					<div class="order-history__item-descr-bot--left">Способ оплаты:</div>
					<div class="order-history__item-descr-bot--right"><?=$order['PAYMENT_NAME']?></div>
				</div>
			</div><?php
			if (count($order['PRODUCT_IDS']))
			{
				$products = $APPLICATION->IncludeComponent('odva:offers', 'orderOffers', [
					'productsFilter' => [],
					'offersFilter' => [
						'ID' => $order['PRODUCT_IDS']
					],
					'offerPropertiesSettings' => [],
					'productPropertiesSettings' => [
						'PREVIEW_PICTURE' => [
							'type' => 'image',
							'sizes' => [
								'mini' => ['height' => 364, 'width' => 364 ],
								'medium' => ['height' => 616, 'width' => 626 ]
							]
						]
					],
					'orderProducts' => $order['PRODUCTS'],
					'count' => 100,
				]);
			}
		?></div><?php
	}
	else
	{
		?><h3 class="text-center">Нет заказов</h3><?php
	}
?></div>
