<div class="articles-category-item">
	<div class="articles-category-title">
		Категории
	</div>
	<div class="articles-category-list">
		<ul><?php
			for ($arResult["SECTIONS"] as $section)
			{
				?><li><a href="<?=$section['SECTION_PAGE_URL']?>"><?=$section['NAME']?></a></li><?php
			}
		?></ul>
	</div>
</div>