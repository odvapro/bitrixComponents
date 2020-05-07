<div class="product-detail container">
	<?php
		$APPLICATION->IncludeComponent(
			"odva:breadcrumbs",
			"",
			[
				'LINKS' => $arResult['SECTION_NAV']
			]
		);
	?>
	<h2><?=$arResult['NAME']?></h2>
	<div class="product-detail__code">
		<span>Код товара:</span>
		<span><?=$arResult['PROPERTIES']['ARTNUMBER']['VALUE']?></span>
	</div>
	<div class="product-detail__wrapper">
		<div class="product-detail__left">
			<div class="product-detail__slider">
				<div class="product-detail__slider-left">
					<img class="product-detail__slider-left-img lazy-image" src="<?=$arResult['DETAIL_PICTURE']?>" alt="">
					<?php
					if(empty($arResult['CATALOG_QUANTITY']))
					{
						?>
						<div class="product-item__action product-item__action_stick product-item__action_stick_stock">
							Нет в наличии
						</div>
						<?
					}
					else if(!empty($arResult['PRICE']['DISCOUNT']))
					{
						?>
						<div class="product-item__action product-item__action_stick product-item__action_stick_sale">
							Экономия <?=$arResult['PRICE']['DISCOUNT']['PERCENT']?>%
						</div>
						<?php
					}
					?>
				</div>
				<div class="product-detail__slider-right-wrapper">
					<div class="product-detail__slider-right">
						<img
							class="product-detail__slider-right-img lazy-image"
						 	src="<?=$arResult['DETAIL_PICTURE']?>"
							alt=""
							onclick="o2ProductItem.openProductImages(this)"
						>
						<?php
							foreach ($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'] as $img)
							{
								?>
								<img class="product-detail__slider-right-img lazy-image"
									 src="<?=$img?>"
									 alt=""
									 onclick="o2ProductItem.openProductImages(this)"
								>
								<?php
							}
						?>
					</div>
					<div class="product-detail__mob">
						<?php
							if(!empty($arResult['PRICE']['DISCOUNT']))
							{
								?><div class="g-sale">-<?=$arResult['PRICE']['DISCOUNT']['PERCENT']?>%</div><?php
							}
						?>
					</div>
				</div>
			</div>
			<div class="product-detail__desktop">
				<div class="detail-description _tabs-container">
					<ul class="detail-description__menu ">
						<?php
							if(
								!empty($arResult['PROPERTIES']['DESCRIPTION']['~VALUE']['TEXT']) ||
								!empty($arResult['PROPERTIES']['TYPE_DRUG']['VALUE']) ||
								!empty($arResult['PROPERTIES']['MIN_AGE']['VALUE']) ||
								!empty($arResult['PROPERTIES']['REALEASE_FORM']['VALUE'])
							)
							{
								?>
								<li class="detail-description__item detail-description__item_active" onclick="o2.tabs.open(this, 1)">
									<a class="detail-description__link" href="javascript:void(0);">Описание</a>
								</li>
								<?php
							}
						?>
						<?php
							if(!empty($arResult['PROPERTIES']['HOW_USE']['~VALUE']['TEXT']))
							{
								?>
								<li class="detail-description__item" onclick="o2.tabs.open(this, 2)">
									<a class="detail-description__link" href="javascript:void(0);">Способы применения</a>
								</li>
								<?php
							}
						?>
						<?php
							if(!empty($arResult['PROPERTIES']['INDICATIONS']['~VALUE']['TEXT']))
							{
								?>
								<li class="detail-description__item" onclick="o2.tabs.open(this, 3)">
									<a class="detail-description__link" href="javascript:void(0);">Показания</a>
								</li>
								<?
							}
						?>
						<?php
							if(!empty($arResult['PROPERTIES']['MORE']['~VALUE']['TEXT']))
							{
								?>
								<li class="detail-description__item" onclick="o2.tabs.open(this, 4)">
									<a class="detail-description__link" href="javascript:void(0);">Ещё</a>
								</li>
								<?php
							}
						?>
					</ul>
					<div class="tab tab_open" data-tab-id="1">
						<div class="detail-description__info">
							<ul class="detail-description__view">
								<?php
									if($arResult['PROPERTIES']['TYPE_DRUG']['VALUE'])
									{
										?>
										<li class="detail-description__view-item">
											<span class="detail-description__view-name">Тип препарата: </span>
											<span class="detail-description__view-value"><?=$arResult['PROPERTIES']['TYPE_DRUG']['VALUE']?></span>
										</li>
										<?php
									}
									if($arResult['PROPERTIES']['MIN_AGE']['VALUE'])
									{
										?>
										<li class="detail-description__view-item">
											<span class="detail-description__view-name">Возраст применения:</span>
											<span class="detail-description__view-value"><?=$arResult['PROPERTIES']['MIN_AGE']['VALUE']?></span>
										</li>
										<?php
									}
									if($arResult['PROPERTIES']['REALEASE_FORM']['VALUE'])
									{
										?>
										<li class="detail-description__view-item">
											<span class="detail-description__view-name">Форма выпуска:</span>
											<span class="detail-description__view-value"><?=$arResult['PROPERTIES']['REALEASE_FORM']['VALUE']?></span>
										</li>
										<?php
									}
								?>
							</ul>
							<?=$arResult['PROPERTIES']['DESCRIPTION']['~VALUE']['TEXT']?>
						</div>
						<?php
							if($arResult['PROPERTIES']['DESCRIPTION']['~VALUE']['TEXT'])
							{
								?>
								<div class="detail-description__more-btn detail-description__more-btn--hide" onclick="o2.popups.detailInfo(this)">
									<span class="detail-description__more-btn--text-show">Смотреть все</span>
									<span class="detail-description__more-btn--text-hide">Скрыть</span>
									<svg role="img" class="ic-down-arrow">
										<use xlink:href="#ic-down-arrow"></use>
									</svg>
								</div>
								<?
							}
						?>
					</div>
					<div class="tab" data-tab-id="2">
						<?=$arResult['PROPERTIES']['HOW_USE']['~VALUE']['TEXT']?>
					</div>
					<div class="tab" data-tab-id="3">
						<?=$arResult['PROPERTIES']['INDICATIONS']['~VALUE']['TEXT']?>
					</div>
					<div class="tab" data-tab-id="4">
						<?=$arResult['PROPERTIES']['MORE']['~VALUE']['TEXT']?>
					</div>
				</div>
			</div>
		</div>

		<div class="product-detail__right">
			<div class="product-detail__price ">
				<div class="product-detail__value">
					<?php
					if($arResult['PRICE']['DISCOUNT'])
					{
						?>
						<div class="product-detail__newprice">
							<span><?=$arResult['PRICE']['DISCOUNT']['PRICE']?></span>
							<span>₽</span>
						</div>
						<div class="product-detail__lastprice">
							<span><?=$arResult['PRICE']['VALUE']?></span>
							<span>₽</span>
						</div>
						<?php
					}
					else
					{
						?>
						<div class="product-detail__newprice product-detail__newprice_black">
							<span><?=$arResult['PRICE']['VALUE']?></span>
							<span>₽</span>
						</div>
						<?php
					}
					?>
				</div>
			</div>
			<div class="product-detail__brand">
				<?php
					if($arResult['PROPERTIES']['MANUFACTURER']['VALUE'])
					{
						?>
						<div class="product-detail__brand-title">
							<?=$arResult['PROPERTIES']['MANUFACTURER']['VALUE']?>
						</div>
						<?
					}
					if($arResult['PROPERTIES']['BRAND_REF']['VALUE'])
					{
						?>
						<div class="product-detail__brand-name">
							<span class="product-detail__brand-text">Все товары бренда:</span>
							<a href="<?=$arResult['PROPERTIES']['BRAND_REF']['LINK']?>" class="product-detail__brand-text product-detail__brand-text_green"><?=$arResult['PROPERTIES']['BRAND_REF']['VALUE']?></a>
						</div>
						<?
					}
				?>
			</div>

			<ul class="product-detail__list">
				<?php
					if($arResult['PROPERTIES']['NEED_RECIPE']['VALUE_XML_ID'] == 'Y')
					{
						?>
						<li class="product-detail__list-item">
							<svg role="img" class="ic-info">
								<use xlink:href="#ic-info"></use>
							</svg>
							<span>Необходим рецепт</span>
						</li>
						<?php
					}
					if($arResult['CATALOG_QUANTITY'])
					{
						?>
						<li class="product-detail__list-item">
							<svg role="img" class="ic-box">
								<use xlink:href="#ic-box"></use>
							</svg>
							<span>Осталось <?=$arResult['CATALOG_QUANTITY']?></span>
						</li>
						<?php
					}
					if($arResult['SHELF_LIFE'])
					{
						?>
						<li class="product-detail__list-item">
							<svg role="img" class="ic-calendar">
								<use xlink:href="#ic-calendar"></use>
							</svg>
							<span>Годен до <?=$arResult['SHELF_LIFE']['DD']?> <?=$arResult['SHELF_LIFE']['MM']?> <?=$arResult['SHELF_LIFE']['YYYY']?></span>
						</li>
						<?php
					}
				?>
			</ul>

			<div class="product-detail__buttons">
				<div class="product-detail__desktop">
					<button
						class="product-detail__btn g-primary-button <?=($arResult['HAS_BASKET'])?'product-item__button-in-cart':''?>"
						onclick="catalogElement.addToBasket(this, event, <?=$arResult['ID']?>)"
						data-to-cart-button="<?=$arResult['ID']?>"
						>
						<?=($arResult['HAS_BASKET'])?'В корзине':'Добавить в корзину'?>
					</button>
				</div>
				<div class="product-detail__mob">
					<button
						class="product-detail__btn g-primary-button <?=($arResult['HAS_BASKET'])?'product-item__button-in-cart':''?>"
						onclick="catalogElement.addToBasket(this, event, <?=$arResult['ID']?>)"
						data-to-cart-button="<?=$arResult['ID']?>"
						>
						<?=($arResult['HAS_BASKET'])?'В корзине':'В корзину'?>
					</button>
				</div>
				<div
					class="product-detail__favorites <?=$arResult['IS_FAVORITE'] ? 'active' : ''?>"
					onclick="catalogElement.toggleFavorite(this, event, <?=$arResult['ID']?>)"
					data-favorite-id="<?=$arResult['ID']?>"
					>
					<svg role="img" class="ic-favorites">
						<use xlink:href="#ic-favorites"></use>
					</svg>
					<svg role="img" class="ic-favorites-red">
						<use xlink:href="#ic-favorites-red"></use>
					</svg>
					<span>
						<?=$arResult['IS_FAVORITE'] ? 'В избранном' : 'В избранное'?>
					</span>
				</div>
			</div>

			<ul class="product-detail__list">
				<li class="product-detail__list-item">
					<svg role="img" class="ic-location">
						<use xlink:href="#ic-location"></use>
					</svg>
					<div class="product-detail__list-wr">
						<span>Ваш город:</span>
						<span class="product-detail__list-item_green">Владикавказ</span>
					</div>
				</li>
				<li class="product-detail__list-item">
					<svg role="img" class="ic-truck">
						<use xlink:href="#ic-truck"></use>
					</svg>
					<span>Доставка <?=$arResult['DELIVERY_DATE']?> — 390₽</span>
				</li>
				<li class="product-detail__list-item">
					<svg role="img" class="ic-store">
						<use xlink:href="#ic-store"></use>
					</svg>
					<span>Самовывоз <?=$arResult['TAKE_YOURSELF_DATE']?> — бесплатно</span>
				</li>
			</ul>
			<a href="/delivery/" class="product-detail__more">Подробнее про условия доставки</a>
			<div class="product-detail__desktop">
				<?php
					$APPLICATION->IncludeComponent('odva:elements', 'news.product'
						,[
							'filter' =>
							[
								'IBLOCK_ID' => 1,
								'ACTIVE'    => 'Y',
							],
							'sort'=>
							[
								'ID'=>'desc'
							],
							"count" => 3
						]
					);
				?>
			</div>
			<div class="product-detail__mob">

			</div>
		</div>
	</div>
	<div class="product-detail__mob">
		<div class="detail-description _tabs-container">
			<ul class="detail-description__menu detail-description__menu-mob-slider">
				<?php
					if(
						!empty($arResult['PROPERTIES']['DESCRIPTION']['~VALUE']['TEXT']) ||
						!empty($arResult['PROPERTIES']['TYPE_DRUG']['VALUE']) ||
						!empty($arResult['PROPERTIES']['MIN_AGE']['VALUE']) ||
						!empty($arResult['PROPERTIES']['REALEASE_FORM']['VALUE'])
					)
					{
						?>
						<li class="detail-description__item detail-description__item_active" onclick="o2.tabs.open(this, 1)">
							<a class="detail-description__link" href="javascript:void(0);">Описание</a>
						</li>
						<?php
					}
				?>
				<?php
					if(!empty($arResult['PROPERTIES']['HOW_USE']['~VALUE']['TEXT']))
					{
						?>
						<li class="detail-description__item" onclick="o2.tabs.open(this, 2)">
							<a class="detail-description__link" href="javascript:void(0);">Способы применения</a>
						</li>
						<?php
					}
				?>
				<?php
					if(!empty($arResult['PROPERTIES']['INDICATIONS']['~VALUE']['TEXT']))
					{
						?>
						<li class="detail-description__item" onclick="o2.tabs.open(this, 3)">
							<a class="detail-description__link" href="javascript:void(0);">Показания</a>
						</li>
						<?php
					}
				?>
				<?php
					if(!empty($arResult['PROPERTIES']['MORE']['~VALUE']['TEXT']))
					{
						?>
						<li class="detail-description__item" onclick="o2.tabs.open(this, 4)">
							<a class="detail-description__link" href="javascript:void(0);">Ещё</a>
						</li>
						<?php
					}
				?>
			</ul>
			<div class="tab tab_open" data-tab-id="1">
				<div class="detail-description__info">
					<ul class="detail-description__view">
						<?php
							if($arResult['PROPERTIES']['TYPE_DRUG']['VALUE'])
							{
								?>
								<li class="detail-description__view-item">
									<span class="detail-description__view-name">Тип препарата: </span>
									<span class="detail-description__view-value"><?=$arResult['PROPERTIES']['TYPE_DRUG']['VALUE']?></span>
								</li>
								<?php
							}
							if($arResult['PROPERTIES']['MIN_AGE']['VALUE'])
							{
								?>
								<li class="detail-description__view-item">
									<span class="detail-description__view-name">Возраст применения:</span>
									<span class="detail-description__view-value"><?=$arResult['PROPERTIES']['MIN_AGE']['VALUE']?></span>
								</li>
								<?php
							}
							if($arResult['PROPERTIES']['REALEASE_FORM']['VALUE'])
							{
								?>
								<li class="detail-description__view-item">
									<span class="detail-description__view-name">Форма выпуска:</span>
									<span class="detail-description__view-value"><?=$arResult['PROPERTIES']['REALEASE_FORM']['VALUE']?></span>
								</li>
								<?php
							}
						?>
					</ul>
					<?=$arResult['PROPERTIES']['DESCRIPTION']['~VALUE']['TEXT']?>
				</div>
				<?php
					if($arResult['PROPERTIES']['DESCRIPTION']['~VALUE']['TEXT'])
					{
						?>
						<div class="detail-description__more-btn detail-description__more-btn--hide" onclick="o2.popups.detailInfo(this)">
							<span class="detail-description__more-btn--text-show">Смотреть все</span>
							<span class="detail-description__more-btn--text-hide">Скрыть</span>
							<svg role="img" class="ic-down-arrow">
								<use xlink:href="#ic-down-arrow"></use>
							</svg>
						</div>
						<?
					}
				?>
			</div>
			<div class="tab" data-tab-id="2">
				<?=$arResult['PROPERTIES']['HOW_USE']['~VALUE']['TEXT']?>
			</div>
			<div class="tab" data-tab-id="3">
				<?=$arResult['PROPERTIES']['INDICATIONS']['~VALUE']['TEXT']?>
			</div>
			<div class="tab" data-tab-id="4">
				<?=$arResult['PROPERTIES']['MORE']['~VALUE']['TEXT']?>
			</div>
		</div>
	</div>
	<?php
		$APPLICATION->IncludeComponent(
			'odva:products',
			'saleleader',
			[
				'filter' => [
					'IBLOCK_ID' => 2,
					'ACTIVE'    => 'Y',
					'ID' => $arResult['PROPERTIES']['RECOMMEND']['VALUE'],
				],
				'count' => 50,
				'heading' => 'C этим товаром покупают'
			]
		);
	?>
	<div class="product-detail__mob">
		<?php
			$APPLICATION->IncludeComponent('odva:elements', 'news.product'
				,[
					'filter' =>
					[
						'IBLOCK_ID' => 1,
						'ACTIVE'    => 'Y',
					],
					'sort'=>
					[
						'ID'=>'desc'
					],
					"count" => 3
				]
			);
		?>
	</div>
</div>