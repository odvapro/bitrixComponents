<?php
$bredcrumbs = $APPLICATION->IncludeComponent('odva:breadcrumbs', '', [
	'LINKS' => [
		['text' => 'Главная страница','url' => '/'],
		['text' => 'Новости','url' => '/information/'],
		['text' => $arResult['DETAIL']['NAME'], ],
	]
]);
?><section class="article-detail">
	<div class="article-container">
		<div class="article-detail__title">
		<?=$arResult['DETAIL']['NAME']?>
		</div>
		<div class="article-detail-content clearfix">
			<div class="article-detail__picture">
				<img src="<?=$arResult['DETAIL']['DETAIL_PICTURE']?>" alt="<?=$arResult['DETAIL']['NAME']?>">
				<div class="article-detail__picture-text">
					<div class="article-detail__picture-text-date">
						<?=$arResult['DETAIL']['DATE_CREATE_FORMAT']?>
					</div>
					<div class="article-detail__picture-text-title">
						Рекомендации в подборе
					</div>
				</div>
			</div>
			<div class="article-detail__text">
				<?=$arResult['DETAIL']['DETAIL_TEXT']?>
			</div>
		</div>
	</div>
</section>