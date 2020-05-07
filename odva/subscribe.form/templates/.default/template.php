<form action="<?=$arResult['PATH_ADD_SUBSCRIBERS']?>">
	<input type="text" name="email" placeholder="email">
	<?php
		foreach ($arParams as $inputName => $inputValue)
		{
			?><input type="hidden" name="<?=$inputName?>" value="<?=$inputValue?>"><?
		}
	?>
	<button type="submit">Подписатся</button>
</form>