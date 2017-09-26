var callBack =
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
				o2.popups.showStandartPopup('Заявка успешно отправлена!');
		});
		return false;
	},
	validate:function(instance)
	{
		var formGroup = $(instance).find('._required');
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
	}
}