<form action="<?=$arResult['SAVE_PASSWORD_PATH']?>" onsubmit="ProfilePassword.chenge(event,this)">
	<div>
		<span>
			старый пароль
		</span>
		<input type="text" name="password">
	</div>
	<div>
		<span>
			новый пароль
		</span>
		<input type="text" name="newpassword">
	</div>
	<div>
		<span>
			повторите новый пароль
		</span>
		<input type="text" name="confirm">
	</div>
	<button type="submit">Поменять</button>
</form>