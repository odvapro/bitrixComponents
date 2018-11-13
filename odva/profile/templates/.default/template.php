<h3 class="personal-data__title">Личные данные</h3>
<div class="personal-data-text">
	Мы используем личные данные, что бы связываться с вами по вопросам ваших заказов. Мы не передаем данные третьим лицам.
</div>
<form
	class="personal-data__form clearfix"
	action="<?=$arResult['SAVE_PROFILE_PATH']?>"
	onsubmit="return profile.save(this);"
>
	<label class="personal-page__label _required">
		<div class="input-title">Ваше ФИО</div>
		<input
			value="<?=$arResult['NAME']?>"
			type="text"
			class="input personal-page__input"
			name="name"
		>
	</label>
	<label class="personal-page__label _required">
		<div class="input-title">E-mail для подтвреждения</div>
		<input
			value="<?=$arResult['EMAIL']?>"
			type="text"
			class="input personal-page__input"
			name="email"
		>
	</label>
	<label class="personal-page__label _required">
		<div class="input-title">Контактный телефон</div>
		<input
			value="<?=$arResult['PHONE']?>"
			type="text"
			class="input personal-page__input _phone-mask"
			name="phone"
		>
	</label>
	<label class="personal-page__label personal-page__label__select _required">
		<div class="input-title">Город доставки</div>
		<div class="order-list-select">
			<select name="city"><?php
				for ($arResult['CITIES'] as $city)
				{
					?><option<?php
					if ($city == $arResult['CITY']) {?> selected <?php}
					?>><?=$city?></option><?php
				}
			?></select>
		</div>
	</label>
	<label class="personal-page__label personal-page__label__wide _required">
		<div class="input-title input-title__socials">Адрес доставки</div>
		<input
			placeholder="Укажите адресс доставки"
			value="<?=$arResult['ADDRESS']?>"
			type="text"
			class="input personal-page__input"
			name="address"
		>
	</label>
	<div class="personal-page-socials clearfix _required">
		<div class="input-title">Связанные аккаунты в социальных сетях</div>
		<script src="//ulogin.ru/js/ulogin.js"></script>
		<div id="uLogin" data-ulogin="display=buttons;redirect_uri=<?=$arResult['ADD_SOCIAL_NETWORK_PATH']?>">
			<div
				class="personal-page-socials-icons"<?php
				if (!$arResult['FACEBOOK'])
				{
					?>data-uloginbutton="facebook"<?php
				}
				?>>
				<div class="social-icon <?=($arResult['FACEBOOK'])?'social-icon-fb-active':'' ?>">
					<div class="social-icon__svg"><?php
						include '/html/images/svg/facebook.svg';
					?></div>
					<div class="<?=($arResult['FACEBOOK'])?'social-icon__status-fb':'social-icon__status-vk' ?>">
						<div class="social-icon__status-bcg"><?php
							if ($arResult['FACEBOOK'])
							{
								?><img src="/html/images/svg/personal-page-check.svg" alt=""><?php
							}
							else
							{
								?><img src="/html/images/svg/personal-page-plus.svg" alt=""><?php
							}
						?></div>
					</div>
				</div>
			</div>
			<div
				class="personal-page-socials-icons"<?php
				if (!$arResult['VKONTAKTE'])
				{
					?>data-uloginbutton="vkontakte"<?php
				}
				?>>
				<div class="social-icon <?=($arResult['VKONTAKTE'])?'social-icon-vk-active':''?>">
					<div class="social-icon__svg social-icon-vk"><?php
						include '/html/images/svg/vk.svg';
					?></div>
					<div class="<?=($arResult['VKONTAKTE'])?'social-icon__status-fb':'social-icon__status-vk'?>">
						<div class="social-icon__status-bcg"><?php
							if ($arResult['VKONTAKTE'])
							{
								?><img src="/html/images/svg/personal-page-check.svg" alt=""><?php
							}
							else
							{
								?><img src="/html/images/svg/personal-page-plus.svg" alt=""><?php
							}
						?></div>
					</div>
				</div>
			</div>
			<div
				class="personal-page-socials-icons"<?php
				if (!$arResult['ODNOKLASSNIKI'])
				{
					?>data-uloginbutton="odnoklassniki"<?php
				}
				?>>
				<div class="social-icon <?=($arResult['ODNOKLASSNIKI'])?'social-icon-ok-active':''?>">
					<div class="social-icon__svg social-icon-ok"><?php
						include '/html/images/svg/ok.svg';
					?></div>
					<div class="<?=($arResult['ODNOKLASSNIKI'])?'social-icon__status-fb':'social-icon__status-vk'?>">
						<div class="social-icon__status-bcg"><?php
							if ($arResult['ODNOKLASSNIKI'])
							{
								?><img src="/html/images/svg/personal-page-check.svg" alt=""><?php
							}
							else
							{
								?><img src="/html/images/svg/personal-page-plus.svg" alt=""><?php
							}
						?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="personal-page__buttons">
		<button type="submit" class="t-button-text t-button-bluegradient personal-page__save">Сохранить изменения</button>
		<button onclick="return profile.cancelEditing(event)" class="t-button-text t-button-blue-border personal-page__cancel">Отменить изменения</button>
	</div>
</form>
