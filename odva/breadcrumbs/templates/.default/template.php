<ul class="breadcrumbs">
	<?php
	foreach ($arParams['LINKS'] as $key => $link)
	{
		?>
		<li class="breadcrumbs__item <?=((count($arParams['LINKS'])-1)==$key)?'breadcrumbs__item_reverse':''?>">
			<a href="<?=$link['url']?>" class="breadcrumbs__link <?=($arParams['white']=='Y')?'breadcrumbs__link_white':''?>">
				<?=$link['text']?>
			</a>
		</li>
		<?php
	}
	?>
</ul>