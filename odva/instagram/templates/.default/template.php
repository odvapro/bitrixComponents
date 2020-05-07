<div class="simple-slider__wrapper">
	<div class="simple-slider__top">
		<h2 class="simple-slider__title">
			<span>Мы в</span>
			<svg role="img" class="logo-instagram">
				<use xlink:href="#logo-instagram"></use>
			</svg>
		</h2>
		<div class="simple-slider__arrows">
			<div class="simple-slider__prev slider-prev">
				<svg role="img" class="left-normal">
					<use xlink:href="#left-normal"></use>
				</svg>
				<svg role="img" class="left-hover">
					<use xlink:href="#left-hover"></use>
				</svg>
			</div>
			<div class="simple-slider__next slider-next">
				<svg role="img" class="right-normal">
					<use xlink:href="#right-normal"></use>
				</svg>
				<svg role="img" class="right-hover">
					<use xlink:href="#right-hover"></use>
				</svg>
			</div>
		</div>
	</div>
	<div class="simple-slider">
		<?php
		foreach ($arResult['ITEMS'] as $src)
		{
			?>
			<div class="simple-slider__item-wr simple-slider_mobile">
				<div class="simple-slider__item test">
					<picture>
						<img class="lazy-image" alt="" data-src="<?=$src?>">
					</picture>
				</div>
			</div>
			<?php
		}
		?>
	</div>
	<button class="simple-button" onclick="o2.popups.simpleSliderImages(this)">Показать ещё</button>
</div>