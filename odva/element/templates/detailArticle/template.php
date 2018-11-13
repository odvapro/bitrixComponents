<?php
$bredcrumbs = $APPLICATION->IncludeComponent('odva:breadcrumbs', '', {
	'LINKS' : [
		{'text' : 'Главная страница','url':'/'},
		{'text' : 'Полезная информация','url':'/information/'},
		{'text' : $arResult['NAME'], },
	]
})
?><section class="article-detail">
	<div class="article-container">
		<div class="article-detail__title"> <?=$arResult['NAME']?> </div>
		<div class="article-detail-content clearfix"><?php
			if (!empty($arResult['DETAIL_PICTURE']['SIZES']['medium']))
			{
				?><div class="article-detail__picture">
					<img src="<?=$arResult['DETAIL_PICTURE']['SIZES']['medium']['src']?>" alt="<?=$arResult['NAME']?>">
					<div class="article-detail__picture-text">
						<div class="article-detail__picture-text-date">
							<?=$arResult['DATE_FORMAT']?>
						</div><?php
						if (!empty($arResult['SECTION']['NAME']))
						{
							?><div class="article-detail__picture-text-title">
								<?=$arResult['SECTION']['NAME']?>
							</div><?php
						}
					?></div>
				</div><?php
			}
			?><?=$arResult['DETAIL_TEXT']?>
		</div>
	</div>
</section>