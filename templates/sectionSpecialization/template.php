<section class="specialization">
	<div class="spec__block">
	<?php
	foreach ($arResult['SECTIONS'] as $sectionKey => $section)
	{
		if($sectionKey == 0)
			$class = 'spec__left';
		elseif ($sectionKey == 1)
			$class = 'spec__middle';
		elseif ($sectionKey == 2)
			$class = 'spec__right';
	?>
		<div class="<?=$class?>" style="background-image:url(<?=$section['PICTURE']['src']?>)">
			<div class="spec__textblock">
				<div class="spec__pretitle">
					<?=$section['ELEMENTS_COUNT']?>
				</div>
				<div class="spec__title">
					<a href="<?=$section['SECTION_PAGE_URL']?>"><?=$section['NAME']?></a>
				</div>
			</div>
		</div>
	<?php
	}
	?>
	</div>
	<div class="spec__block-slider">
		<div class="spec__block-slick">
		<?php
		foreach ($arResult['SECTIONS'] as $section)
		{
		?>
			<div class="spec__block-slick-picture">
				<img src="<?=$section['PICTURE']['src']?>" alt="">
				<div class="spec__block-slick-text">
					<div class="spec__pretitle">
						<?=$section['ELEMENTS_COUNT']?>
					</div>
					<div class="spec__title">
						<a href="<?=$section['SECTION_PAGE_URL']?>"><?=$section['NAME']?></a>
					</div>
				</div>
			</div>
		<?php
		}
		?>
		</div>
	</div>
</section>