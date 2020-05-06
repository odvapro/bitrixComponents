var odvaFavorites = {
	notify: function(event, data)
	{
		if(!data.success)
		{
			if(data.error.type == "auth")
			{
				location.hash = "#login";
			}
			else
			{
				alert(data.error.msg);
			}
			return;
		}

		if(data.count > 0)
			$('._favorites_count').removeClass('header__bubble_hide').html(data.count);
		else
			$('._favorites_count').addClass('header__bubble_hide').empty();
	}
};

odvaHelpers.subscribe(odvaFavorites, ['favorites:add', 'favorites:delete']);