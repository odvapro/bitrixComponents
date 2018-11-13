<div class="catalog__left-filter">
	<div class="filter__title">Сфера применения</div>
	<div class="filter__block _filterField"
		data-fieldtype="list"
		data-fieldname="sphere"
	><?php
		foreach ($arFields['VALUES'] as $fieldItem)
		{
			?><label class="checkbox-label filter-checkbox-label">
				<input type="checkbox" class="checkbox" value="<?=$fieldItem['ID']?>" <?=(!empty($fieldItem['SELECTED']))?'checked':''?>>
				<span></span>
				<?=$fieldItem['NAME']?>
			</label><?php
		}
	?></div>
</div>
