Востановление пароля<br>
<form action="<?=$arResult['SEND_PASSWORD_BITRIX_PATH']?>" onsubmit="sendPasswordBitrix.send(event,this)">
	email:<br><input type="text" name="email"><br>
	<input type="hidden" name="m_event" value="<?=$arParams['m_event']?>">
	<button type="submit">Послать на почту</button>
</form>