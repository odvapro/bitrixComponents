<?php
if (!empty($arResult['PREVIEW_PICTURE']['SIZES']['mini']['src']))
{
	?><div class="detail__product-testimonials-img">
		<a href="<?=$arResult['DETAIL_PAGE_URL']?>">
			<img src="<?$arResult['PREVIEW_PICTURE']['SIZES']['mini']['src']?>" alt="<?=$arResult['NAME']?>">
		</a>
	</div><?php
}
if ($arResult['PROPERTIES']['COMPANY_NAME']['VALUE'])
{
	?><div class="detail__product-testimonials-pretitle"><?=$arResult['PROPERTIES']['COMPANY_NAME']['VALUE']?></div><?php
}
?><div class="detail__product-testimonials-header">
	<a href="<?=$arResult['DETAIL_PAGE_URL']?>"><?=$arResult['NAME']?></a>
</div>
<div class="detail__product-testimonials-text"><?=$arResult['PREVIEW_TEXT']?></div>