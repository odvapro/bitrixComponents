<form>
	<?php
	foreach ($arResult['ITEMS'] as $item)
	{
		switch ($item->code)
		{
			case $arParams['PRICE']['FIELD']:
				$values = $item->getValues();
				?>
				<div>
					min: <?=$values['MIN']?>
					<input type="text" name="filter[PROPERTY_<?=$item->code?>][]" value="<?=$values['FROM']?>">
					<input type="text" name="filter[PROPERTY_<?=$item->code?>][]" value="<?=$values['TO']?>">
					max: <?=$values['MAX']?>
				</div>
				<?php
				break;

			default:
				?>
				<div>
					<div><strong><?=$item->name?></strong></div>
					<?php
					foreach ($item->getValues() as $facetId => $value)
					{
						?>
						<div style="margin-left: 10px;">
							<label style="cursor: pointer;">
								<input
									type="checkbox"
									name="filter[PROPERTY_<?=$item->code?>][]"
									value="<?=$value['FILTER_VALUE']?>"
									<?= $value['CHECKED'] ? 'checked' : '' ?>
								>
								(<?=$value['ELEMENT_COUNT']?>) <?=$value['DISPLAY_VALUE']?>
							</label>
						</div>
						<?php
					}
					?>
				</div>
				<?php
				break;
		}
	}
	?>
	<input type="submit" value="Отправить">
</form>