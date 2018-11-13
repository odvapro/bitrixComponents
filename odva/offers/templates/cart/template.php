<section class="cart-section">
	<div class="container">
		<div class="cart-section__title">
			Корзина товаров
		</div>
		<div class="cart-section__items-head clearfix">
			<div class="cart-section__items-head__item">Наименование товара</div>
			<div class="cart-section__items-head__item">Размеры тары</div>
			<div class="cart-section__items-head__item">Цена</div>
			<div class="cart-section__items-head__item">Количество</div>
			<div class="cart-section__items-head__item">Сумма</div>
		</div>
		<div class="cart-section__items"><?php
			 foreach ($arResult['OFFERS'] as $offer)
			 {
				$product = $offer['PRODUCT']
				?><div
					class="cart-section__item clearfix _item"
					data-id="<?=$offer['ID']?>"
					data-price="<?=$offer['PRICE']['INT_PRICE']?>"
					data-unic-id="<?=$offer['ID']?>"
				>
					<div
						class="svg-cart-item-close svg-cart-item-close-dims cart-item-close__icon"
						onclick="o2.cart.deleteItem(this)"
					></div>
					<div class="cart-section__item-product clearfix">
							<div class="cart-section__item-image"><?php
								if (!empty($product['PREVIEW_PICTURE']['SIZES']['mini']['src']))
								{
									?><a href="<?=$product['DETAIL_PAGE_URL']?>"><img src="<?=$product['PREVIEW_PICTURE']['SIZES']['mini']['src']?>" alt="<?=$product['NAME']?>"></a><?php
								}
								else
								{
									?><a href="<?=$product['DETAIL_PAGE_URL']?>"><img src="/html/images/noproduct.png" alt="<?=$product['NAME']?>"></a><?php
								}
							?></div>
						<div class="cart-section__item-category">Полусинтетические СОЖ</div>
						<div class="cart-section__item-name">
							<a href="<?=$product['DETAIL_PAGE_URL']?>"><?=$product['NAME']?></a>
						</div>
					</div>
					<div class="cart-section__item-characteristics clearfix">
						<div class="cart-section__item-size"><?=$offer["PROPERTIES"]["VALUME"]["VALUE"]?></div>
						<div class="cart-section__item-price"><?=$offer['PRICE']['FORMAT_PRICE']?></div>
						<div class="cart-section__item-quantity clearfix">
							<div class="cart-section__item-title-xs">Количество</div>
							<input
								type="number"
								min="1"
								value="<?=$offer['QUANTITY']?>"
								onchange="o2.cart.recalcElement(this)"
							>
						</div>
						<div class="cart-section__item-size cart-section__item-size__xs">
							<div class="cart-section__item-title-xs">Размеры тары</div>
							<?=$offer["PROPERTIES"]["VALUME"]["VALUE"]?>
						</div>
					</div>
					<div class="cart-section__item-sum">
						<div class="cart-section__item-title-xs">Сумма</div>
						<span class="_productSum"><?=$offer['CART_SUMM_FORMAT']?></span>
					</div>
				</div><?php
			}
		?></div>
		<div class="cart-section__items-foot clearfix">
			<div class="cart-section__foot-block">
				<div class="cart-section__full-price clearfix">
					<div class="cart-section__full-price-left">Всего:</div>
					<div class="cart-section__full-price-right _total-price"><?=$arResult['CART_SUMM_FORMAT']?></div>
				</div>
				<div class="cart-section__delivery clearfix">
					<div class="cart-section__delivery-left">Доставка:</div>
					<div class="cart-section__delivery-right">Бесплатно</div>
				</div>
				<div class="cart-section__to-pay clearfix">
					<div class="cart-section__to-pay-left">К оплате:</div>
					<div class="cart-section__to-pay-right _total-price"><?=$arResult['CART_SUMM_FORMAT']?></div>
				</div>
				<div class="cart-section__buttons clearfix">
					<a href="/cart/make/" class="t-button-text t-button-orangegradient cart-section__make-order">Оформить заказ</a>
					<a href="/" class="t-button-text t-button-blue-border cart-section__return">Вернуться в покупкам</a>
				</div>
			</div>
		</div>
	</div>
</section>