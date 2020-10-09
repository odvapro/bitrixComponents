var profile =
{
	validate:function(instance)
	{
		var formGroup = $(instance).find('._required'),
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
	save:function(instance)
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
			if(typeof e.success != 'undefined' && e.success === true )
				o2.popups.showStandartPopup('Изменения сохранены');
		});
		return false;
	},
	cancelEditing:function()
	{
		event.preventDefault();
		window.location.reload();
		return false;
	}
}