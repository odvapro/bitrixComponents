<div class="col-lg-3 col-md-3 filter-col-md-3">
	<div class="_catalogOverlay _catalogFilter" onclick="o2.popups.closeFilter('.popup');">
		<div class="catalog__left _filterMobile" onclick="event.stopPropagation();">
			<div onclick="o2.popups.closeFilter();" class="popup-close _popupClose">
				<div class="svg-close svg-close-dims svg-close__icon"></div>
			</div><?php
			foreach ($arResult['FIELDS'] as $fieldName => $field)
			{
				include $_SERVER["DOCUMENT_ROOT"] . $templateFolder . '/fieldTypes/' . $field['TYPE'] . '.php'
			}
			?><div class="filter__butoon-block">
				<button
					class="t-button-text t-button-bluegradient filter-btn"
					onclick="cSection.filterCatalog()"
				>Показать результаты</button>
				<button
					class="t-button-text t-button-transparenr filter-btn"
					onclick="cSection.clearFilter()"
				>Сбросить</button>
			</div>
		</div>
	</div>
</div>