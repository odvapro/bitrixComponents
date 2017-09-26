var reviewForm =
{
	send:function(instance)
	{
		if(!this.validate(instance)) return false;
		var actionUrl = $(instance).attr('action');
		$.ajax({
			url      : actionUrl,
			type     : 'POST',
			dataType : 'json',
			data     : $(instance).serialize()
		}).done(function(e)
		{
			if(e.success === true)
				o2.popups.showStandartPopup('Отзыв успешно отправлен!');
		});
		return false;
	},
	validate:function(instance)
	{
		var formGroup = $(instance).find('._required');
		fields        = formGroup.find('input, textarea, select'),
		valid         = true;

		fields.each(function()
		{
			if($(this).val() == '')
			{
				$(this).parents('._required').addClass('inp-error');
				valid = false;
			}
			else if($(this).is('select') && $(this).find('option:selected').val() == '')
			{
				$(this).parents('._required').addClass('inp-error');
				valid = false;
			}
			else
			{
				$(this).parents('._required').removeClass('inp-error');
			}
		});

		return valid;
	},
	hoverStar:function(instance)
	{
		$('._revStar')
			.removeClass('svg-feedback-star-orange svg-feedback-star-orange-dims')
			.addClass('svg-feedback-star svg-feedback-star-dims');
		var cIndex = $(instance).index();
		$('._revStar').slice(0,cIndex+1).addClass('svg-feedback-star-orange svg-feedback-star-orange-dims');
	},
	setStar:function(instance)
	{
		var cIndex = $(instance).index();
		$('._reviewStars').val(cIndex+1);
	},
	updateStars:function()
	{
		$('._revStar')
			.removeClass('svg-feedback-star-orange svg-feedback-star-orange-dims')
			.addClass('svg-feedback-star svg-feedback-star-dims');
		var cIndex = $('._reviewStars').val();
		$('._revStar').slice(0,cIndex).addClass('svg-feedback-star-orange svg-feedback-star-orange-dims');
	}

}