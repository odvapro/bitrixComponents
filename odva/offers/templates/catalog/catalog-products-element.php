<div class="catalog-products__item clearfix">
	<div class="catalog-products__relative">
			<div class="catalog-products__item-img"><?php
				if (!empty($product['PREVIEW_PICTURE']['SIZES']['mini']['src']))
					?><img src="<?=$product['PREVIEW_PICTURE']['SIZES']['mini']['src']?>" alt="<?=$product['NAME']?>"/><?php
				}
				else
				{
					?><img src="/html/images/noproduct.png" alt="<?=$product['NAME']?>"/><?php
				}
			?></div>
		<div class="catalog-products__item-descr"><?php
			if ($product['SECTION'])
			{
				?><div class="catalog-products__item-category"><?=$product['SECTION']['NAME']?></div><?php
			}
			?><div class="catalog-products__item-name">
				<a href="<?=$product['DETAIL_PAGE_URL']?>"><?=$product['NAME']?></a>
			</div>
			<div class="catalog-products__item-bot clearfix">
				<div class="catalog-products__item-price">
					<?=$offer['PRICE']['FORMAT_PRICE']?><div class="svg-rouble svg-rouble-dims catalog__slider-svg"></div>
				</div>
				<div class="catalog-products__item-size">
					<?=$offer["PROPERTIES"]["VALUME"]["VALUE"]?>
				</div>
				<div class="rec__product-addtocart">
					<button
						class="t-button-text t-button-orangegradient rec-button-text"
						onclick="o2.product.addToCart(this,event)"
						data-id="<?=$offer['ID']?>"
						data-price="<?=$offer['PRICE']['INT_PRICE']?>"
					>
						<div class="rec__product-svg"><?php
							include '/html/inc/svg/cart.html'
						?></div>
						<span class="_inCartText">Добавить в корзину</span>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>