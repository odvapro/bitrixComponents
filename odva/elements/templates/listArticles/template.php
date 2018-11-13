<div class="articles-section-items"><?php
	foreach ($arResult as $element)
	{
		?><div class="help__item articles-item">
			<div class="help__item-picture">
				<a href="<?=$element["DETAIL_PAGE_URL"]?>"><img src="<?=$element['PREVIEW_PICTURE']?>" alt=""></a>
			</div>
			<div class="help__item-textarea">
				<div class="help__item-pretitle">
					<?=$element["PROPERTIES"]["INDUSTRY"]["VALUE"]?>
				</div>
				<div class="help__item-title">
					<a href="<?=$element["DETAIL_PAGE_URL"]?>"><?=$element["NAME"]?></a>
				</div>
				<div class="help__item-text">
					<a href="<?=$element["DETAIL_PAGE_URL"]?>"><?=$element['PREVIEW_TEXT']?></a>
				</div>
			</div>
		</div><?php
	}
	else
	{
		?><h3 class="text-center">Ничего не найдено</h3><?php
	}
?></div>