<?php
	if($arResult['AUTH'])
	{
		?>
		<div class="auth-line">
			<div class="auth-line__item auth-line__item_user" onclick="o2.spoiler.toggle(this)">
				<div class="auth-line__icon auth-line__icon_registr">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
						<path fill="#FFF" fill-rule="evenodd" d="M10.005 10.009c2.27 0 4.121-1.908 4.121-4.23a4.094 4.094 0 0 0-4.121-4.112 4.133 4.133 0 0 0-4.122 4.13c.01 2.313 1.851 4.212 4.122 4.212zm0-1.359c-1.393 0-2.586-1.26-2.586-2.853 0-1.566 1.174-2.771 2.586-2.771 1.421 0 2.585 1.187 2.585 2.753 0 1.593-1.183 2.871-2.585 2.871zm5.991 9.683c1.584 0 2.337-.45 2.337-1.44 0-2.357-3.157-5.768-8.328-5.768-5.18 0-8.338 3.41-8.338 5.768 0 .99.753 1.44 2.337 1.44h11.992zm.458-1.359H3.556c-.248 0-.353-.063-.353-.252 0-1.475 2.423-4.238 6.802-4.238 4.37 0 6.792 2.763 6.792 4.238 0 .19-.095.252-.343.252z" />
					</svg>
				</div>
				<span class="auth-line__text auth-line__reg"><?=$arResult['USER']['EMAIL']?></span>
				<svg role="img" class="ic-triangles-down">
					<use xlink:href="#ic-triangles-down"></use>
				</svg>
			</div>
			<ul class="auth-line__reg-dropdown">
				<li><a class="auth-line__reg-dropdown-item" href="/personal/">Мои данные</a></li>
				<li><a class="auth-line__reg-dropdown-item" href="/personal/orders/">Мои заказы</a></li>
				<li><a class="auth-line__reg-dropdown-item" href="/personal/security/">Безопасность</a></li>
				<li><a class="auth-line__reg-dropdown-item" href="" data-url-ajax="<?=$arResult['LOGOUT_AJAX_PATH']?>" onclick="authLine.logout(event,this)">Выйти</a></li>
			</ul>
		</div>
		<?
	}
	else
	{
		?>
		<div class="top-info__item top-info__item_registr">
			<div class="top-info__icon top-info__icon_registr">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
					<path fill-rule="evenodd" d="M10.005 10.009c2.27 0 4.121-1.908 4.121-4.23a4.094 4.094 0 0 0-4.121-4.112 4.133 4.133 0 0 0-4.122 4.13c.01 2.313 1.851 4.212 4.122 4.212zm0-1.359c-1.393 0-2.586-1.26-2.586-2.853 0-1.566 1.174-2.771 2.586-2.771 1.421 0 2.585 1.187 2.585 2.753 0 1.593-1.183 2.871-2.585 2.871zm5.991 9.683c1.584 0 2.337-.45 2.337-1.44 0-2.357-3.157-5.768-8.328-5.768-5.18 0-8.338 3.41-8.338 5.768 0 .99.753 1.44 2.337 1.44h11.992zm.458-1.359H3.556c-.248 0-.353-.063-.353-.252 0-1.475 2.423-4.238 6.802-4.238 4.37 0 6.792 2.763 6.792 4.238 0 .19-.095.252-.343.252z" />
				</svg>
			</div>
			<a
				class="top-info__text top-info__log"
				href="javascript:void(0);"
				onclick="o2.popups.showPopup('.popup-auth')"
				>Войти</a>
			<a
				class="top-info__text top-info__reg"
				href="javascript:void(0);"
				onclick="o2.popups.showPopup('.popup-registr')"
				>Регистрация</a>
		</div>
		<?
	}