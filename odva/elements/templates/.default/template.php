<ul>
	<?php
	foreach ($arResult['ITEMS'] as $item)
	{
		?>
		<li><pre><?=$item['ID']?></pre></li>
		<?php
	}
	?>
</ul>
<?php
$APPLICATION->IncludeComponent(
	"bitrix:main.pagenavigation",
	"",
	[
		"NAV_OBJECT"  => $arResult['NAV_OBJECT'],
		"SEF_MODE"    => "Y",
		"PAGE_WINDOW" => 3,
	],
	false
);
?>