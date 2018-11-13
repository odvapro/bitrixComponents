<div class="catalog-products__item"><?php
	if ($product['PREVIEW_PICTURE']['SIZES']['mini']['src'])
	{
		?><div class="catalog-products__item-img">
			<img src="<?=$product['PREVIEW_PICTURE']['SIZES']['mini']['src']?>" alt="<?=$product['NAME']?>"/>
		</div><?php
	}
	?><div class="catalog-products__item-descr"><?php
		if ($product['SECTION'])
		{
			?><div class="catalog-products__item-category"><?=$product['SECTION']['NAME']?></div><?php
		}
		?><div class="catalog-products__item-name">
			<a href="<?=$product['DETAIL_PAGE_URL']?>"><?=$product['NAME']?></a>
		</div>
		<div class="catalog-products__item-bot clearfix">
			<div class="catalog-products__item-price">
				<?=$product['PRICE']['FORMAT_PRICE']?><div class="svg-rouble svg-rouble-dims catalog__slider-svg"></div>
			</div>
			<div class="catalog-products__item-size">
				<?=$product["PROPERTIES"]["LITERS"]["VALUE"]?>
			</div>
		</div>
	</div>
</div>