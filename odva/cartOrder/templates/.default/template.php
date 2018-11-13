<section class="order-section">
	<div class="container">
		<form
			action="<?=$arResult['MAKE_ORDER_PATH']?>"
			onsubmit="return o2.cart.makeOrder(this)"
		>
			<div class="order-section-block">
				<div class="row">
					<div class="order-section-title">Оформление заказа</div>
					<div class="col-lg-6">
						<div class="inputs inputs-order _required">
							<div class="order-input-title">Ваше ФИО</div>
							<input
								placeholder="Укажите ваши личные данные для получения"
								type="text"
								class="input"
								name="name"
								value="<?=$arResult['USER']['NAME']?>"
							>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="inputs inputs-order _required">
							<div class="order-input-title">E-mail для подтверждения</div>
							<input
								placeholder="Укажите ваш email"
								type="text"
								class="input"
								name="email"
								value="<?=$arResult['USER']['EMAIL']?>"
							>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-3">
						<div class="inputs inputs-order _required">
							<div class="order-input-title">Контактный телефон</div>
							<input
								placeholder="+7 (___) ___ - __ - __"
								type="text"
								class="input _phone-mask"
								name="phone"
								value="<?=$arResult['USER']['PHONE']?>"
							>
						</div>
					</div>
					<div class="col-lg-3">
						<div class="inputs inputs-order _required">
							<div class="order-input-title">Город доставки</div>
							<div class="order-list-select">
								<select name="city">
									<option value="" disabled selected>Выберите город</option><?php
									foreach($arResult['CITIES'] as $city)
									{
									 ?><option value="<?=$city?>"><?=$city?></option><?php
									}
								?></select>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="inputs inputs-order _required">
							<div class="order-input-title">Адрес доставки</div>
							<input
								placeholder="Укажите адресс доставки"
								type="text"
								class="input"
								name="address"
								value="<?=$arResult['USER']['ADDRESS']?>"
							>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6">
						<div class="order-radio-title">Укажите удобный способ доставки</div>
						<div class="filter__block">
							<label class="radio-label filter-radio-label">
								<input type="radio" name="delivery" value="1" class="radio" checked>
								<span></span>
								Курьерская доставка по Москве и Московской области
							</label>
							<label class="radio-label filter-radio-label">
								<input type="radio" name="delivery" class="radio" value="2">
								<span></span>
								Перевозчиком ПЭК (<a class="order-input-link" href="/abuot/delivery/">Читать подробности доставки</a>)
							</label>
							<label class="radio-label filter-radio-label">
								<input type="radio" name="delivery" class="radio" value="3">
								<span></span>
								Перевозчиком Деловые Линии (<a class="order-input-link" href="/abuot/delivery/">Читать подробности доставки</a>)
							</label>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="order-radio-title">Укажите удобный способ оплаты</div>
						<div class="filter__block">
							<label class="radio-label filter-radio-label">
								<input type="radio" name="payment" class="radio" checked value="2">
								<span></span>
								Оплата налиными при получении
							</label>
							<label class="radio-label filter-radio-label">
								<input type="radio" name="payment" class="radio" value="3">
								<span></span>
								Оплата с помощью банковского перевода (Юр. лицам и СПД)
							</label>
							<label class="radio-label filter-radio-label">
								<input type="radio" name="payment" class="radio" value="4">
								<span></span>
								Оплатить картой онлайн
							</label>
						</div>
					</div>
				</div>
				<div class="order-buttons-block">
					<button type="submit" class="t-button-text t-button-orangegradient order-button">Оформить заказ</button> <!-- для появления прелоадера добавить класс waiting -->
					<a href="/" class="t-button-text t-button-blue-border">Вернуться в покупкам</a>
				</div>
			</div>
		</form>
	</div>
</section>
<div id="TPL" style="display:none;">
	<div class="successAdding">
		<h2 class="text-center">Заказ успешно оформлен</h2>
		<h3 class="text-center">Номер вашего заказа <b>№ #number#</b></h3>
	</div>
</div>