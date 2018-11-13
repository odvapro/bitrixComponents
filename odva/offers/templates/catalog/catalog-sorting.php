<div class="catalog-sorting clearfix">
	<button onclick="o2.popups.showFilter('._filterPopup');" class="filter-btn-small">
		Фильтр
	</button>
	<button class="filter-btn-small filter-btn__sorting">
		Сортировка
	</button>
	<div class="catalog__sort-text">Отображать товары </div>
		<div class="catalog__sort-items" onclick="o2.sortSelect.toggleState(this, event);">
			<div class="sort-items__selected"><?php
				foreach ($arResult['SORTINGS'] as $sorting)
				{
					if (!empty($sorting['selected']))
					{
						?><a href="javascript:void(0);"><?=$sorting['name']?></a><?php
					}
				}
				?><img src="/html/images/svg/breadcrumbs.svg" alt="">
			</div>
			<ul class="sort-items__list-item"><?php
				foreach ($arResult['SORTINGS'] as $sortingKey => $sorting)
				{
					if (!empty($sorting['selected']))
					{
						?><li><a href="javascript:void(0);" onclick="cSection.setSort('<?=$sortingKey?>');"><?=$sorting['name']?></a></li><?php
					}
				}
			?></ul>
		</div>
</div>