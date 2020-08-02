<div>
	<?php
	if ($arResult["CURRENT_PAGE"] > 1)
	{
		if ($arResult["CURRENT_PAGE"] > 2)
		{
			?>
			<a href="<?=htmlspecialcharsbx($this->getComponent()->replaceUrlTemplate($arResult["CURRENT_PAGE"]-1))?>">&larr;</a>
			<?php
		}
		else
		{
			?>
			<a href="<?=htmlspecialcharsbx($arResult["URL"])?>">&larr;</a>
			<?php
		}

		if ($arResult["START_PAGE"] > 1)
		{
			?>
			<a href="<?=htmlspecialcharsbx($arResult["URL"])?>">1</a>
			<?php
			if ($arResult["START_PAGE"] > 2)
			{
				?>
				<a href="<?=htmlspecialcharsbx($this->getComponent()->replaceUrlTemplate(round($arResult["START_PAGE"] / 2)))?>">...</a>
				<?php
			}
		}
	}

	for($page = $arResult["START_PAGE"]; $page <= $arResult["END_PAGE"]; $page++)
	{
		if ($page == $arResult["CURRENT_PAGE"])
		{
			?>
			<span><?=$page?></span>
			<?php
		}
		elseif($page == 1)
		{
			?>
			<a href="<?=htmlspecialcharsbx($arResult["URL"])?>">1</a>
			<?php
		}
		else
		{
			?>
			<a href="<?=htmlspecialcharsbx($this->getComponent()->replaceUrlTemplate($page))?>"><?=$page?></a>
			<?php
		}
	}

	if($arResult["CURRENT_PAGE"] < $arResult["PAGE_COUNT"])
	{
		if ($arResult["END_PAGE"] < $arResult["PAGE_COUNT"])
		{
			if ($arResult["END_PAGE"] < ($arResult["PAGE_COUNT"] - 1))
			{
				?>
				<a href="<?=htmlspecialcharsbx($this->getComponent()->replaceUrlTemplate(round($arResult["END_PAGE"] + ($arResult["PAGE_COUNT"] - $arResult["END_PAGE"]) / 2)))?>">...</a>
				<?php
			}
			?>
			<a href="<?=htmlspecialcharsbx($this->getComponent()->replaceUrlTemplate($arResult["PAGE_COUNT"]))?>"><?=$arResult["PAGE_COUNT"]?></a>
			<?php
		}
		?>
		<a href="<?=htmlspecialcharsbx($this->getComponent()->replaceUrlTemplate($arResult["CURRENT_PAGE"]+1))?>">&rarr;</a>
		<?php
	}
	?>
</div>