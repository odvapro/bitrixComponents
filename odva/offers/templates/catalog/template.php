<?php
if (count($arResult['OFFERS']) > 0)
{
	?><div class="col-lg-9 col-md-9 col-sm-12 catalog-col"><?php
		include $_SERVER["DOCUMENT_ROOT"] . $templateFolder . '/catalog-sorting.php';
	?></div>

	<div class="col-lg-9 col-md-9 col-sm-12 catalog-col">
		<div class="preloader-catalog _catalogPreloader"><?php
			include $_SERVER["DOCUMENT_ROOT"] . '/html/images/svg/loading.svg';
		?></div>
		<div class="catalog__items clearfix"><?php
			foreach ($arResult['OFFERS'] as $offerKey => $offer)
			{
				if ($offerKey < 6)
				{
					$product = $offer['PRODUCT'];
					include $_SERVER["DOCUMENT_ROOT"] . $templateFolder . '/catalog-products-element.php';
				}
			}
		?></div>
	</div><?php
	if (!empty($arResult['SEASON_OFFER']) and $arResult['SEASON_OFFER'] != false)
	{
		$offer = $arResult['SEASON_OFFER'];
		$product = $offer['PRODUCT'];
		?><div class="col-lg-9 col-lg-offset-3 col-md-9 col-md-offset-3 col-sm-12 big-product-col"><?php
			include $_SERVER["DOCUMENT_ROOT"] . $templateFolder . '/catalog-product.php';
		?></div><?php
	}
	if (count($arResult['OFFERS']) > 6)
	{
		?><div class="col-lg-9 col-lg-offset-3 col-md-9 col-md-offset-3 col-sm-12 catalog-col">
			<div class="catalog__items clearfix"><?php
			foreach ($arResult['OFFERS'] as $offerKey => $offer)
			{
				if ($offerKey > 5)
				{
					$product = $offer['PRODUCT'];
					include $_SERVER["DOCUMENT_ROOT"] . $templateFolder . '/catalog-products-element.php';
				}
			}
			?><div class="_moreProductsPlace"></div>
			</div><?php
			if (count($arResult['OFFERS']) > 11)
			{
				?><div class="catalog__right-button">
					<button data-nexpage="2" onclick="cSection.loadMore(this)" class="t-button-text t-button-transparenr catalog__right-refresh">
						<span class="catalog__refresh"><?php include $_SERVER["DOCUMENT_ROOT"] . '/html/images/svg/refresh.svg'?></span>
						Показать еще
					</button>
				</div><?php
			}
		?></div><?php
	}
}
else
{
	?><div class="col-lg-9 col-md-9 col-sm-12 catalog-col">
		<h2 class="text-center">Ничего не найдено</h2>
	</div><?php
}?>