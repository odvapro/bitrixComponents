<ul>
	<?php
	foreach ($arResult['ITEMS'] as $item)
	{
		?>
		<li><pre><?php print_r($item); ?></pre></li>
		<?php
	}
	?>
</ul>
<?= $arResult['PAGINATION'] ?>