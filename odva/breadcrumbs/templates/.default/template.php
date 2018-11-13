<section class="breadcrumbs__section">
	<div class="container">
		<div class="breadcrumbs">
			<ul><?php
				foreach ($arParams['LINKS'] as $link)
				{
					?><li><a href="<?=$link['url']?>"><?=$link['text']?></a></li><?php
				}
			?></ul>
		</div>
	</div>
</section>