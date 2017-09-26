var detailProduct =
{
	addCurrentOfferToCart:function(instance)
	{
		var offer = this.getActiveOffer();
		var count = this.getQuantity();
		var product = {
			unicId    : offer.id,
			productId : offer.id,
			price     : offer.price,
			count     : Number(count)
		};

		o2.cart.add(product);
		$(instance).find('._inCartHtml').html('В корзине');
	},
	showOneClickOrder:function()
	{
		var offer = this.getActiveOffer();
		var count = this.getQuantity();
		var product = {
			unicId    : offer.id,
			productId : offer.id,
			price     : offer.price,
			count     : Number(count)
		};
		window.oneclickProduct = product;
		o2.popups.showPopup('._onclickOrderPopup');
	},
	sendOneClickOrder:function(instance)
	{
		if(!this.validaetOneClick(instance)) return false;
		var actionUrl = $(instance).attr('action');
		var formData = {
			product:window.oneclickProduct,
			name:$(instance).find('[name="name"]').val(),
			phone:$(instance).find('[name="phone"]').val()
		}
		$.ajax({
			url      : actionUrl,
			type     : 'POST',
			dataType : 'json',
			data     : formData
		}).done(function(e)
		{
			if(typeof e.success != 'undefined' && e.success === true )
			{
				o2.popups.showStandartPopup('Ваш зкакз успешно оформлен!');
			}
		});
		return false;
	},
	validaetOneClick:function(instance)
	{
		var formGroup = $(instance).find('._required'),
		fields        = formGroup.find('input'),
		valid         = true;
		fields.each(function()
		{
			if($(this).val() == '')
			{
				$(this).parents('._required').addClass('inp-error');
				valid = false;
			}
		});

		return valid;
	},
	selectOffer:function(offerId)
	{
		$('._offer').removeClass('active');
		$('._offer[data-offerid="'+offerId+'"]').addClass('active');
		$('._offer[data-offerid="'+offerId+'"]').prop('selected',true);
		this.updatePrice();
	},
	changeQuantity:function(instance)
	{
		$('input._offerQuantity').val($(instance).val());
		this.updatePrice();
	},
	getQuantity:function()
	{
		return parseInt($('input._offerQuantity').val());
	},
	updatePrice:function()
	{
		var offer = this.getActiveOffer();
		var quantity = this.getQuantity();
		var realPrice = offer.price*quantity;
		$('._offerPrice').html(realPrice.formatMoney(0, '.', ' '));
	},
	getActiveOffer:function()
	{
		var offerId = $('._offer.active').data('offerid');
		if(typeof detailOffers[offerId] == 'undefined')
			return false;
		return detailOffers[offerId];
	}
}