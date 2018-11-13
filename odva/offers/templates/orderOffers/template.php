<div class="order-history__item-products"><?php
	foreach ($arResult['OFFERS'] as $offer)
	{
		$product = $offer['PRODUCT'];
		?><div class="order-history__item--product clearfix"><?php
			if (!empty($product['PREVIEW_PICTURE']['SIZES']['mini']['src']))
			{
				?><div class="order-history__item--product-img">
					<img src="<?=$product['PREVIEW_PICTURE']['SIZES']['mini']['src']?>" alt="<?=$product['NAME']?>">
				</div><?php
			}
			?><div class="order-history__item--product-descr"><?php
				if ($product['SECTION'])
				{
					?><div class="order-history__item--product-category">
						<?=$product['SECTION']['NAME']?>
					</div><?php
				}
				?><div class="order-history__item--product-name">
					<a href="<?=$product['DETAIL_PAGE_URL']?>"><?=$product['NAME']?></a>
				</div>
				<div class="order-history__item--product-characteristics">
				<div class="order-history__item--product-size"><?=$offer["PROPERTIES"]["VALUME"]["VALUE"]?></div>
					<div class="order-history__item--product-quantity"><?=$offer['QUANTITY']?> шт.</div>
				</div>
			</div>
		</div><?php
	}
?></div>