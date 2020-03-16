<div class="pagination__wr">
	<?php
	if($arResult["CURRENT_PAGE"] > 1)
	{
		if($arResult["CURRENT_PAGE"] > 2)
			$prev = $component->replaceUrlTemplate($arResult["CURRENT_PAGE"] - 1);
		else
			$prev = $arResult["URL"];
		?>
		<a href="<?=$prev?>" class="g-pagination-button">
			<svg role="img" class="ic-left-arrow ic-pagination">
				<use xlink:href="#ic-left-arrow"></use>
			</svg>
			<svg role="img" class="left-arrow-hover">
				<use xlink:href="#left-arrow-hover"></use>
			</svg>
			Назад
		</a>
		<?php
	}
	?>
	<div class="pagination__numbers">
		<a
			href="<?=$arResult["URL"]?>"
			class="g-pagination-button pagination__item<?=($arResult['CURRENT_PAGE'] == 1) ? ' pagination__item--active' : ''?>"
		>1</a>


		<?php
		// 1-е многоточие
		if($arResult["CURRENT_PAGE"] >= 5)
		{
			$morePage = $arResult["START_PAGE"] - 3;
			if($morePage <= 1)
				$morePage = 2;
			?>
			<a
				href="<?=$component->replaceUrlTemplate($morePage)?>"
				class="g-pagination-button pagination__item<?=($arResult['CURRENT_PAGE'] == $morePage) ? ' pagination__item--active' : ''?>"
			>...</a>
			<?php
		}
		?>

		<?php
		// обычные страницы
		for($page = $arResult["START_PAGE"]; $page <= $arResult["END_PAGE"]; $page++)
		{
			if($page == 1 || $page == $arResult['PAGE_COUNT'])
				continue;
			?>
			<a
				href="<?=$component->replaceUrlTemplate($page)?>"
				class="g-pagination-button pagination__item<?=($arResult['CURRENT_PAGE'] == $page) ? ' pagination__item--active' : ''?>"
			><?=$page?></a>
			<?php
		}
		?>

		<?php
		// 2-е многоточие
		if($arResult["PAGE_COUNT"] - $arResult["END_PAGE"] >= 2)
		{
			$morePage = $arResult["END_PAGE"] + 3;
			if($morePage >= $arResult['PAGE_COUNT'])
				$morePage = $arResult['PAGE_COUNT'] - 1;
			?>
			<a
				href="<?=$component->replaceUrlTemplate($morePage)?>"
				class="g-pagination-button pagination__item"
			>...</a>
			<?php
		}
		?>

		<a
			href="<?=$component->replaceUrlTemplate($arResult["PAGE_COUNT"])?>"
			class="g-pagination-button pagination__item<?=($arResult['CURRENT_PAGE'] == $arResult["PAGE_COUNT"]) ? ' pagination__item--active' : ''?>"
		><?=$arResult["PAGE_COUNT"]?></a>
	</div>
	<?php
	if($arResult["PAGE_COUNT"] > 1 && $arResult["CURRENT_PAGE"] < $arResult["PAGE_COUNT"])
	{
		?>
		<a href="<?=$component->replaceUrlTemplate($arResult["CURRENT_PAGE"] + 1)?>" class="g-pagination-button g-pagination-button_next">
			Вперед
			<svg role="img" class="ic-right-arrow">
				<use xlink:href="#ic-right-arrow"></use>
			</svg>
			<svg role="img" class="right-arrow-hover">
				<use xlink:href="#right-arrow-hover"></use>
			</svg>
		</a>
		<?php
	}
	?>
</div>