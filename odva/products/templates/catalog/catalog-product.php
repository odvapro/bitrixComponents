<div class="catalog__rec-block clearfix">
	<div class="catalog__rec-left">
		<div class="catalog__rec-category">
			<a href="jsvsascript:void()">Сезонное предложение</a>
		</div><?php
		if ($product['SECTION'])
		{
			?><div class="catalog__rec-pretitle"><?php $product['SECTION']['NAME'] ?></div><?php
		}
		?><div class="catalog__rec-title">
			<a href="<?=$product['DETAIL_PAGE_URL']?>"><?=$product['NAME']?></a>
		</div>
		<div class="catalog__rec-text">
			<?=$product['PREVIEW_TEXT']?>
			<div class="catalog-product-hidden">Чистые минеральные масла без присадок имеют ограниченное применение и чаще всего применяются при простых работах.</div>
		</div>
		<div class="catalog__rec-price clearfix">
			<div class="catalog__rec-buy">
				<span class="catalog__rec-money"><?=$product['PRICE']['FORMAT_PRICE']?></span><div class="svg-rouble svg-rouble-dims catalog__rec-svg"></div>
			</div>
			<div class="catalog__rec-pricetext">
				<?=$product["PROPERTIES"]["LITERS"]["VALUE"]?>
			</div>
		</div>
		<button class="t-button-text t-button-bluegradient">Подробнее о продукции</button>
	</div>
	<div class="catalog__rec-right">
		<div class="catalog__rec-image"><?php
			if (!empty($product['PREVIEW_PICTURE']['SIZES']['medium']['src']))
			{
				?><img src="<?=$product['PREVIEW_PICTURE']['SIZES']['medium']['src']?>" alt="<?=$product['NAME']?>"/><?php
			}
		?></div>
	</div>
</div>