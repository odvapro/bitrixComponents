<?php
if (empty($arResult['AUTH']))
{
	?><li class="dropdown__authtorize">
		<a onclick="o2.popups.showPopup('._loginPopup')" href="javascript:void(0)" class="preheader__authtorize">
			<span class="svg-entire svg-entire-dims preheader__svg"></span>
			<span class="preheader__authtorize-text">Авторизация</span>
		</a>
	</li><?php
}
else
{
	?><li class="dropdown__authtorize dropdown__authtorize--true active clearfix">
		<div class="dropdown__authtorize--true__photo">
			<img src="<?=$arResult['USER']['PIC']?>" alt="<?=$arResult['USER']['NAME']?>">
		</div>
		<div class="preheader__authtorize-text dropdown__authtorize--true__name">
			<?=$arResult['USER']['NAME']?>
		</div>
		<ul class="authtorize-sublist">
			<li><a href="/profile/">Личный кабинет</a></li>
			<li><a href="/profile/loguot.php">Выход</a></li>
		</ul>
	</li><?php
}?>