<div class="order-register__tabs _tabs-container">
	<h3 class="order-register__subtitle">Выбрите способ получения</h3>
	<div class="order-register__tabs-wr">
		<label class="order-register__tab" onclick="o2.tabs.open(this, 1)">
			<input type="radio" name="order-tab" checked onclick="makeOrder.setDeliveryAndPriceBlock(2,100)">
			<div class="order-register__tab-box">
				<svg role="img" class="ic-truck">
					<use xlink:href="#ic-truck"></use>
				</svg>
				<div class="order-register__tab-text">
					<span>Доставка</span>
					<span>В удобное для вас время</span>
				</div>
			</div>
		</label>
		<label class="order-register__tab order-register__tab--pickup" onclick="o2.tabs.open(this, 2)">
			<input type="radio" name="order-tab" onclick="makeOrder.setDeliveryAndAdressBlock(3,0,'<?=$arResult["PHARMACIES"][0]["PROPERTIES"]["ADRESS"]["~VALUE"]?>')">
			<div class="order-register__tab-box">
				<svg role="img" class="ic-place">
					<use xlink:href="#ic-place"></use>
				</svg>
				<div class="order-register__tab-text">
					<span>Самовывоз</span>
					<span>Забрать можно будет через 5 минут</span>
				</div>
			</div>
		</label>
	</div>
	<div class="checkout-addresses__wr tab" data-tab-id="2">
		<div class="checkout-addresses__top">
			<h3 class="order-register__subtitle">Адреса аптек</h3>
			<div class="checkout-addresses__show-map" onclick="o2.popups.showHideMap()">
				<div>
					<span>Показать списком</span>
					<span>Списком</span>
				</div>
				<svg role="img" class="ic-list">
					<use xlink:href="#ic-list"></use>
				</svg>
			</div>
		</div>
		<div class="checkout-addresses__body">
			<?php
				foreach ($arResult['PHARMACIES'] as $key => $pharmacies)
				{
					?>
					<div class="checkout-addresses__item">
						<label class="g-radio catalog-filter__input-line">
							<input
								type="radio"
								name="boom1"
								class="g-radio__input <?=($key == 0)?'radio__input--adress-first':''?>"
								onclick="makeOrder.setAdress('<?=$pharmacies['PROPERTIES']['ADRESS']['~VALUE']?>')"
							>
							<div class="g-radio__body">
								<span class="g-radio__box"></span>
								<span class="g-radio__text"><?=$pharmacies['~NAME']?></span>
							</div>
						</label>
						<div class="checkout-addresses__text">
							<span><?=$pharmacies['PROPERTIES']['ADRESS']['~VALUE']?></span>
							<span><?=$pharmacies['WHEN_GIVE']?></span>
						</div>
						<div class="checkout-addresses__timetable">
							<div class="checkout-addresses__timetable-line checkout-addresses__timetable-line--show">
								<span>Круглосуточно</span>
							</div>
						</div>
					</div>
					<?php
				}
			?>
			<div class="checkout-addresses__more-btn" onclick="o2.popups.showItems(this)">Выбрать другой <span>(2)</span></div>
			<div class="checkout-addresses__more-btn" onclick="o2.popups.showHideMap()">Показать на карте <span>(3)</span></div>
		</div>
		<div class="checkout-addresses__map">
			<div id="map"></div>
		</div>
	</div>
	<script src="https://api-maps.yandex.ru/2.1/?apikey=7613024e-ec5b-4903-aaa2-fde6827deb98&lang=ru_RU" type="text/javascript"></script>

	<div class="order-registr__delivery-type tab tab_open" data-tab-id="1">
		<h3 class="order-register__subtitle">Адрес получателя</h3>
		<div class="order-register__form order-register__form--delivery">
			<form action="">
				<div class="order-register__form-item _field" data-call="empty">
					<span>Город *</span>
					<input
						type="text"
						class="order-register__form-item--city l-input__input"
						name="city"
						placeholder="Введите название города"
					>
					<div class="l-input__sub-text _error-msg"></div>
				</div>
				<div class="order-register__form-item _field" data-call="empty">
					<span>Улица *</span>
					<input
						type="text"
						class="order-register__form-item--street l-input__input"
						name="street"
						placeholder="Введите название улицы"
					>
					<div class="l-input__sub-text _error-msg"></div>
				</div>
				<div class="order-register__form-item _field" data-call="empty">
					<span>Дом *</span>
					<input
						type="text"
						class="order-register__form-item--home l-input__input"
						name="home"
						placeholder="Введите номер дома"
					>
					<div class="l-input__sub-text _error-msg"></div>
				</div>
				<div class="order-register__form-item _field" data-call="empty">
					<span>Квартира *</span>
					<input
						type="email"
						class="order-register__form-item--floor l-input__input"
						name="apartment"
						placeholder="Введите номер квартры"
					>
					<div class="l-input__sub-text _error-msg"></div>
				</div>
			</form>
		</div>
		<h3 class="order-register__subtitle">Выберите тип доставки</h3>
		<div class="order-register__checkboxes">
			<div class="order-register__checkboxes-line">
				<label class="g-radio">
					<input type="radio" name="boom1" class="g-radio__input radio__input--delivery-first" checked onclick="makeOrder.setDeliveryAndPrice(2,100)">
					<div class="g-radio__body">
						<span class="g-radio__box"></span>
						<div class="order-register__checkboxes-text">
							<div>
								<span>Срочная сегодня</span>
								<span>— 100 ₽</span>
							</div>
						</div>
					</div>
				</label>
			</div>
			<div class="order-register__checkboxes-line">
				<label class="g-radio">
					<input type="radio" name="boom1" class="g-radio__input" onclick="makeOrder.setDeliveryAndPrice(16,0)">
					<div class="g-radio__body">
						<span class="g-radio__box"></span>
						<div class="order-register__checkboxes-text">
							<div>
								<span>Сегодня в любое время</span>
								<span>— Бесплатно</span>
							</div>
							<div class="order-register__checkboxes-subtext">Оператор согласует с вами время доставки заказа</div>
						</div>
					</div>
				</label>
			</div>
			<div class="order-register__checkboxes-line">
				<label class="g-radio">
					<input type="radio" name="boom1" class="g-radio__input" onclick="makeOrder.setDeliveryAndPrice(17,0)">
					<div class="g-radio__body">
						<span class="g-radio__box"></span>
						<div class="order-register__checkboxes-text">
							<div>
								<span>Завтра в любое время</span>
								<span>— Бесплатно</span>
							</div>
						</div>
					</div>
				</label>
			</div>
		</div>
	</div>
</div>