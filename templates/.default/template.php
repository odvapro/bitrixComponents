<?php
$bubbleClasses = [
	'header__bubble',
	'header__bubble_favorites',
	'_favorites_count'
];

if(empty($arResult['COUNT']))
	$bubbleClasses[] = 'header__bubble_hide';

$bubbleClasses = implode(' ', $bubbleClasses);

$bubbleCount = empty($arResult['COUNT']) ? '' : $arResult['COUNT'];
?>
<a href="<?=$arResult['URL']?>" class="<?=(!empty($arParams['MOBILE']) ? 'header__mobile-icon' : 'header__bottom-item')?>">
	<div class="<?=$bubbleClasses?>"><?=$bubbleCount?></div>
	<svg role="img" class="ic-black-favorites">
		<use xlink:href="#ic-black-favorites"></use>
	</svg>
	<?php
	if(empty($arParams['MOBILE']))
	{
		?>Избранное<?php
	}
	?>
</a>