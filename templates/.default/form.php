<div class="order-register__form order-register__form--person">
	<h3 class="order-register__subtitle">Личные данные</h3>
	<form action="<?=$arResult['PATH_MAKE_ORDER']?>">
		<div class="order-register__form-item _field" data-call="empty">
			<span>Имя *</span>
			<input
				type="text"
				class="order-register__form-item--fname l-input__input"
				placeholder="Введите ваше имя"
				value="<?=$arResult['USER']['NAME']?>"
			>
			<div class="l-input__sub-text _error-msg"></div>
		</div>
		<div class="order-register__form-item _field" data-call="empty">
			<span>Фамилия *</span>
			<input
				type="text"
				class="order-register__form-item--lname l-input__input"
				placeholder="Введите вашу Фамилию"
				value="<?=$arResult['USER']['LAST_NAME']?>"
			>
			<div class="l-input__sub-text _error-msg"></div>
		</div>
		<div class="order-register__form-item _field" data-call="empty phone">
			<span>Номер телефона *</span>
			<input
				type="text"
				class="order-register__form-item--phone l-input__input"
				placeholder="+7(000)000-00-00"
				value="<?=$arResult['USER']['PERSONAL_PHONE']?>"
			>
			<div class="l-input__sub-text _error-msg"></div>
		</div>
		<div class="order-register__form-item _field" data-call="empty">
			<span>Email *</span>
			<input
				type="email"
				class="order-register__form-item--email l-input__input"
				placeholder="Введите ваш email"
				value="<?=$arResult['USER']['EMAIL']?>"
			>
			<div class="l-input__sub-text _error-msg"></div>
		</div>
		<div class="order-register__form-item order-register__form-item--promo">
			<span>Промокод</span>
			<div>
				<input type="text" placeholder="Введите промокод">
				<input type="text" placeholder="Промокод">
				<button class="button-activate" onclick="makeOrder.setPromocod(event,this)">Активировать</button>
			</div>
		</div>
	</form>
</div>
<div class="order-register__bottom">
	<div class="order-register__bottom-offer">
		<div>
			<label class="g-checkbox catalog-menu__item">
				<input type="checkbox" class="g-checkbox__input">
				<div class="g-checkbox__body">
					<span class="g-checkbox__box"></span>
				</div>
			</label>
		</div>
		<span>
			Нажимая на кнопку оформить заказ я соглашаюсь с политикой конфиденциальности, публичной офертой и регистрируюсь на сайте.
		</span>
	</div>
	<button class="button button--order-register" onclick="makeOrder.preSendOrder(event,this)">Оформить заказ</button>
</div>