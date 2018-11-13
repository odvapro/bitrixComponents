<div class="catalog__left-filter">
	<div class="filter__title"><?=$arFields['SETTINGS']['heading']?></div>
	<div class="filter__block _filterField"
		data-fieldtype="list"
		data-fieldname="<?=$fieldName?>"
	><?php
			foreach ($arFields['VALUES'] as $fieldItem)
			{
			?><label class="checkbox-label filter-checkbox-label">
				<input type="checkbox" class="checkbox" value="<?=$fieldItem['ID']?>" <?php (!empty($fieldItem['SELECTED']))?'checked':'' ?>>
				<span></span>
				<?=$fieldItem['VALUE']?>
			</label><?php
			}
	?></div>
</div>