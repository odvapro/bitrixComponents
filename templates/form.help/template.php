<div class="container">
	<form action="" onsubmit="formHelp.send(event,this)">
		<div class="questions">
			<h3 class="title title--questions">Остались вопросы? — Напишите нам</h3>
			<div class="questions__form">
				<div class="questions__wr">
					<div class="questions__item _field" data-call="empty">
						<span>Имя</span>
						<input class="l-input__input" type="text" placeholder="Ваше имя" name="NAME">
						<div class="l-input__sub-text _error-msg"></div>
					</div>
					<div class="questions__item _field" data-call="empty">
						<span>Email</span>
						<input class="l-input__input" type="email" placeholder="Электронная почта" name="EMAIL">
						<div class="l-input__sub-text _error-msg"></div>
					</div>
				</div>
				<div class="questions__item _field" data-call="empty">
					<span>Тема</span>
					<input class="l-input__input" type="text" placeholder="Тема вопроса" name="TYPE">
					<div class="l-input__sub-text _error-msg"></div>
				</div>
				<div class="questions__item questions__item--textarea _field" data-call="textareaEmpty">
					<span>Вопрос</span>
					<textarea class="questions__item-area l-input__input" name="QUESTION"></textarea>
					<div class="l-input__sub-text _error-msg"></div>
				</div>
				<div class="questions__button">
					<button type="submit" class="simple-button">Отправить</button>
				</div>
			</div>
		</div>
	</form>
</div>
<script>
BX.message({
	IBLOCK_ID: '<?=$arParams['IBLOCK_ID']?>',
	NAME_FORM_RESULT: '<?=$arParams['NAME_FORM_RESULT']?>',
	MAIL_EVENT_CODE: '<?=$arParams['MAIL_EVENT_CODE']?>',
	PATH_AJAX:'<?=$arResult['PATH_AJAX']?>'
});
</script>