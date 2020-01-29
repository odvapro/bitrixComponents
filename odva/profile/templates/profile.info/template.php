<form action="<?=$arResult['SAVE_PROFILE_PATH']?>" onsubmit="ProfileInfo.chenge(event,this)">
	<div class="line">
		<span>Имя</span>
		<input type="text" name="name" value="<?=$arResult['NAME']?>">
	</div>
	<div class="line">
		<span>Фамилия</span>
		<input type="text" name="lastname" value="<?=$arResult['LAST_NAME']?>">
	</div>
	<div class="line">
		<span>Дата рождения</span>
		<input type="text" name="birthday" value="<?=$arResult['PERSONAL_BIRTHDAY']?>">
	</div>
	<div class="line">
		<span>Телефон</span>
		<input type="text" name="phone" value="<?=$arResult['PERSONAL_PHONE']?>">
	</div>
	<div class="line">
		<span>Город</span>
		<input type="text" name="city" value="<?=$arResult['PERSONAL_CITY']?>">
	</div>
	<div class="line">
		<span>Адрес</span>
		<input type="text" name="address" value="<?=$arResult['PERSONAL_STREET']?>">
	</div>
	<button type="submit">Изменить</button>
</form>