<?php
$bredcrumbs = $APPLICATION->IncludeComponent('odva:breadcrumbs', '', [
	'LINKS' => [
		['text' => 'Главная страница', 'url' => '/'],
		['text' => $arResult['SECTION']['NAME'],'url' => $arResult['SECTION']['SECTION_PAGE_URL']]
	]
]);
?><section class="detail">
	<div class="container">
		<div class="row">
			<div class="detail__product-block clearfix">
				<div class="detail__product-pretitle detail__product-display"><?=$arResult['SECTION']['NAME']?></div>
				<div class="detail__product-title detail__product-display"><?=$arResult['NAME']?></div>
				<div class="detail-product-left-col">
					<div class="detail__product-slidershadow">
						<div class="detail__product-slider"><?php
							foreach($arResult['PROPERTIES']['PICTURES']['SIZES'] as $picture)
							{
								?><div class="detail__product-item">
									<div class="detail__product-img">
										<img src="<?=$picture['mini']['src']?>" />
									</div>
								</div><?php
							}
						?></div>
					</div>
				</div>
				<div class="detail-product-right-col">
					<div class="detail__product-pretitle"><?=$arResult['SECTION']['NAME']?></div>
					<div class="detail__product-title"><?=$arResult['NAME']?></div>
					<div class="detail__product-text"><?=$arResult['PREVIEW_TEXT']?></div>
					<div class="detail__product-list">
						<div class="detail__product-list-title detail__product-list-title-size">
							Размеры тары
						</div>
						<div class="detail__product-list-size">
							<ul class="clearfix"><?php
								foreach ($arResult['OFFERS'] as $offer)
								{
									?><li
										data-offerid="<?=$offer['ID']?>"
										class="_offer <?php if (!empty($offer['SELECTED'])) ?>active<?php } ?>"
										onclick="detailProduct.selectOffer(<?=$offer['ID']?>)"
										><a href="javascript:void(0)"><?=$offer['PROPERTIES']['VALUME']['VALUE']?></a>
									</li><?php
								}
							?></ul>
						</div>
						<div class="detail__product-list-select">
							<div class="detail__product-list-hidden-title">
								Размеры тары
							</div>
							<select><?php
								foreach ($arResult['OFFERS'] as $offer)
								{
									?><option class="_offer" data-offerid="<?=$offer['ID']?>" value=""><?=$offer['PROPERTIES']['VALUME']['VALUE']?></option><?php
								}
							?></select>
						</div>
						<div class="product-count-768">
							<div class="detail__product-list-title">
								Количество
							</div>
							<div class="detail__product-list-input">
								<input
									onchange="detailProduct.changeQuantity(this)"
									type="number"
									class="_offerQuantity"
									min="1"
									value="1"
								/>
							</div>
						</div>
						<div class="detail__product-properties clearfix">
							<div class="detail__product-price">
								<div class="detail__product-list-title">
									Цена
								</div>
								<div class="detail__product-list-price _offerPrice"> <?=$arResult['SELECTED_PRICE']?> </div>
								<div class="detail__product-list-rouble">
									<div class="svg-rouble svg-rouble-dims product__slider-svg"></div>
								</div>
							</div>
							<div class="detail__product-count">
								<div class="detail__product-list-title">
									Количество
								</div>
								<div class="detail__product-list-input">
									<input
										onchange="detailProduct.changeQuantity(this)"
										type="number"
										class="_offerQuantity"
										min="1"
										value="1"
									/>
								</div>
							</div>
							<div class="detail__product-available">
								<div class="detail__product-list-title">
									Наличие
								</div>
								<div class="detail__product-list-svg">
									<div class="svg-available svg-available-dims"></div>
								</div>
								<div class="detail__product-list-avtext">
									Наша компания является производителем, поэтому весь товар всегда есть в наличии.
								</div>
							</div>
						</div>
						<div class="detail__product-bottom">
							<div class="detail__product-addtocart">
								<div class="detail__product-cartbutton">
									<button onclick="detailProduct.addCurrentOfferToCart(this)" class="t-button-text t-button-orangegradient">
										<div class="detail__product-svg"><?php
											include $_SERVER["DOCUMENT_ROOT"] . '/html/src/inc/svg/cart.html';
										?></div>
										<span class="_inCartHtml">Добавить в корзину</span>
									</button>
								</div>
							</div>
							<div class="detail__product-oneclick">
								<button onclick="detailProduct.showOneClickOrder()" class="t-button-text t-button-blue-border">Купить в 1 клик</button>
							</div>
						</div>
					</div>
				</div>
				<div class="detail__product-bottom-hidden">
					<div class="detail__product-addtocart">
						<div class="detail__product-cartbutton">
							<button onclick="detailProduct.addCurrentOfferToCart(this)" class="t-button-text t-button-orangegradient">
								<div class="detail__product-svg"><?php
									include $_SERVER["DOCUMENT_ROOT"] . '/html/src/inc/svg/cart.html';
								?></div>
								<span class="_inCartHtml">Добавить в корзину</span>
							</button>
						</div>
					</div>
					<div class="detail__product-oneclick">
						<button onclick="detailProduct.showOneClickOrder()" class="t-button-text t-button-blue-border">Купить в 1 клик</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="detail-descr">
	<div class="container">
			<div class="detail-descr-content clearfix">
				<div class="detail-descr__left">
					<div class="detail__product-characteristics">
						<div class="detail__product-characteristics-title">
							Описание технических характеристик
						</div>
						<div class="detail-descr__hidden-text">
							<?=$arResult['DETAIL_TEXT']?>
						</div>
						<div class="characteristics__button">
							<a onclick="o2.showDescr('.detail-descr__hidden-text', this);" href="javascript:void(0)">Читать подробное описание</a>
						</div><?php
						if (count($arResult['SHOW_PROPERTIES']))
						{
							?><div class="characteristics__table"><?php
								for ($arResult['SHOW_PROPERTIES'] as $showProp)
								{
									?><div class="characteristics__table-item clearfix">
										<div class="characteristics__table-item-name">
											<?=$showProp['name']?>
										</div>
										<div class="characteristics__table-item-value">
											<?=$showProp['value']?>
										</div>
									</div><?php
								}
							?></div><?php
						}
					?></div>
				</div><?php
				if (count($arResult['SHOW_PROPERTIES']))
				{
					?><div class="characteristics__table__320-row row">
						<div class="characteristics__table characteristics__table__320"><?php
							foreach ($arResult['SHOW_PROPERTIES'] as $showProp)
							{
								?><div class="characteristics__table-item clearfix">
									<div class="characteristics__table-item-name">
										<?=$showProp['name']?>
									</div>
									<div class="characteristics__table-item-value">
										<?=$showProp['value']?>
									</div>
								</div><?php
							}
						?></div>
					</div><?php
				}
				?><div class="detail-descr__right">
					<div class="detail__product-testimonials clearfix">
						<div class="detail__product-testimonials__top clearfix">
							<div class="detail__product-testimonials-title">
								Отзывы о продукте
							</div>
							<div class="detail__product-testimonials-stars">
								<div class="svg-star svg-star-dims testimonials-star"></div>
								<div class="svg-star svg-star-dims testimonials-star"></div>
								<div class="svg-star svg-star-dims testimonials-star"></div>
								<div class="svg-star svg-star-dims testimonials-star"></div>
								<div class="svg-star svg-star-dims testimonials-star"></div>
							</div>
						</div><?php
						if ($arResult['PROPERTIES']['KEIS']['VALUE'])
						{
							$keis = $APPLICATION->IncludeComponent('odva:element', 'keisDetail', [
								'filter' => ['IBLOCK_ID' => 7, 'ID' => $arResult['PROPERTIES']['KEIS']['VALUE'] ],
								'propertiesSettings' => [
									'PREVIEW_PICTURE' => [
										'type'  => 'image',
										'sizes' => [
											'mini' => ['height' => 308, 'width' => 313 ]
										]
									]
								],
							]);
						}
					?></div><?php
					$reviews = $APPLICATION->IncludeComponent('odva:elements', 'reviewsProduct', [
						'filter' => [
							'IBLOCK_ID'	=> 6,
							'PROPERTY_PRODUCT' => $arResult['ID']
						],
						'count' => 10
					]);
				?></div>
			</div>
	</div>
</section><?php
// advantages block
include $_SERVER["DOCUMENT_ROOT"] . $templateFolder . '/advantages.php';
?><script>
	var detailOffers = <?=$arResult['JSON_OFFERS']?>
</script>