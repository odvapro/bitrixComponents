<div onclick="event.stopPropagation();" class="popup feedback-popup _feedbackPopup">
	<div onclick="o2.popups.closePopup();" class="popup-close _popupClose">
		<div class="svg-close svg-close-dims svg-close__icon"></div>
	</div>
	<h3 class="feedback-popup__title">Оставить отзыв</h3>
	<div class="feedback-popup__text">
		Все отзвывы проходят модерацию. Мы опубликуем ваш отзыв после проверки его модератором
	</div>
	<form action="<?=$arResult['ADD_PATH']?>" onsubmit="return reviewForm.send(this)">
		<div class="feedback-popup__stars clearfix" onmouseleave="reviewForm.updateStars()"><?php
			for ($i = 0; $i < 5; $i++)
			{
				?><div
					onmousemove="reviewForm.hoverStar(this)"
					onclick="reviewForm.setStar(this)"
					class="_revStar svg-feedback-star svg-feedback-star-dims feedback-star__icon"
				></div><?php
			}
			?><input type="hidden" name="stars" class="_reviewStars">
		</div>
		<div class="inputs inputs-feedback _required">
			<div class="callback-input-title">Ваше Имя</div>
			<input name="name" placeholder="Как к вам обращаться?" type="text" class="input">
		</div>
		<div class="inputs inputs-feedback _required">
			<div class="callback-input-title">Текст отзыва</div>
			<textarea name="message" placeholder="Опишите свое мнение максимально подробно"></textarea>
		</div>
		<div class="feedback-popup__btn-block">
			<button class="t-button-text t-button-bluegradient feedback-popup__btn" type="submit">Оставить отзвы</button>
		</div>
	</form>
</div>