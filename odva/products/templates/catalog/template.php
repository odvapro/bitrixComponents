<div class="col-lg-9 col-md-9 col-sm-12 catalog-col"><?php
	include $templateFolder~'/catalog-sorting.php';
?></div>

<div class="col-lg-9 col-md-9 col-sm-12 catalog-col">
	<div class="catalog__items clearfix"><?php
				for ($arResult['PRODUCTS'] as $productKey => $product)
				{
					if ($productKey < 6)
					{
						include $templateFolder~'/catalog-products-element.php';
					}
				}
	?></div>
</div><?php
if (!empty($result['PRODUCTS'][6]))
{
	$product = $arResult['PRODUCTS'][6];
	?><div class="col-lg-9 col-lg-offset-3 col-md-9 col-md-offset-3 col-sm-12 big-product-col"><?php
		include $templateFolder~'/catalog-product.php';
	?></div><?php
}
if (count($arResult['PRODUCTS']) > 6)
{
	?><div class="col-lg-9 col-lg-offset-3 col-md-9 col-md-offset-3 col-sm-12 catalog-col">
		<div class="catalog__items clearfix"><?php
				for ($arResult['PRODUCTS'] as $productKey => $product)
				{
					if ($productKey > 6)
					{
						include $templateFolder~'/catalog-products-element.php';
					}
				}
			?><div class="_moreProductsPlace"></div>
		</div>
		<div class="catalog__right-button">
			<button data-nexpage="2" onclick="cSection.loadMore(this)" class="t-button-text t-button-transparenr catalog__right-refresh">
				<span class="catalog__refresh"><?php include '/html/images/svg/refresh.php'; ?></span>
				Показать еще
			</button>
		</div>
	</div><?php
}