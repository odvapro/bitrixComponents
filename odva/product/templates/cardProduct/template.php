<section class="product-slider-section">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-12__no-padding">
				<h2 class="product__slider-caption">
					<?=$arParams["heading"]?>
				</h2>
				<div class="product__slider-slick">
					<div class="product__slider clearfix">
						<div class="product__slider-left">
							<div class="product__slider-pretitle">
								<?=$arResult['SECTION']['NAME']?>
							</div>
							<div class="product__slider-title">
								<a href="<?=$arResult['DETAIL_PAGE_URL']?>"><?=$arResult['NAME']?></a>
							</div>
							<div class="product__slider-text">
								<?=$arResult["PREVIEW_TEXT"]?>
							</div>
							<div class="product__slider-price clearfix">
								<div class="product__slider-buy">
									от <span class="product__slider-money"><?=$arResult['MIN_PRICE']?></span><div class="svg-rouble svg-rouble-dims product__slider-svg"></div>
								</div>
								<div class="product__slider-pricetext">
									Мы производим продукцию в разных вариациях тары. Начальная стоимость указана за пробную версию.
								</div>
							</div>
							<a href="<?=$arResult['DETAIL_PAGE_URL']?>" class="t-button-text t-button-bluegradient">Подробнее о продукции</a>
						</div>
						<div class="product__slider-right">
							<div class="product__slider-right-slick"><?php
								if (!empty($arResult["PREVIEW_PICTURE"]['SIZES']['mini']['src']))
								{
									?><div class="product__slider-image">
										<img src="<?=$arResult["PREVIEW_PICTURE"]['SIZES']['mini']['src'] }}" alt="<?=$arResult['NAME']?>">
									</div><?php
								}
								foreach($arResult['PROPERTIES']['PICTURES']['SIZES'] as $picture)
								{
									?><div class="product__slider-image">
										<img src="<?=$picture['mini']['src']?>" alt="<?=$arResult['NAME']?>">
									</div><?php
								}
							?></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>