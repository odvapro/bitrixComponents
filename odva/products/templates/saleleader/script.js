var odvaProducts = {
	target: false,
	addToBasket: function(instance, event, productId)
	{
		event.preventDefault();
		this.target = productId;

		odvaHelpers.basket.add(productId, 1);
	},
	basketAdd: function(data)
	{
		if(!data.success)
			return;
		$(`[data-to-cart-button=${this.target}]`).addClass('product-item__button-in-cart').text('В корзине');
	},
	toggleFavorite: function(instance, event, id)
	{
		event.preventDefault();
		this.target = instance;

		if($(this.target).hasClass('product-item__action_fav_added'))
			odvaHelpers.favorites.delete(id);
		else
			odvaHelpers.favorites.add(id);
	},
	favoritesAdd: function(data)
	{
		if(!data.success)
			return;

		let id = $(this.target).data('favorite-id');

		$(`[data-favorite-id=${id}]`).addClass('product-item__action_fav_added');

		let heart = $(this.target).find('.heart');
		$(heart).addClass('is_animating')
		setTimeout(() =>
		{
			$(heart).addClass('g-dnone')
		}, 500);
	},
	favoritesDelete: function(data)
	{
		if(!data.success)
			return;

		let id = $(this.target).data('favorite-id');

		$(`[data-favorite-id=${id}]`).removeClass('product-item__action_fav_added');

		let heart = $(this.target).find('.heart');
		$(heart).removeClass('is_animating g-dnone');
	},
	notify: function(event, data)
	{
		let callback = event.split(':');
		callback[1] = callback[1].charAt(0).toUpperCase() + callback[1].slice(1);
		callback = callback.join('');

		this[callback](data);
	}
};

odvaHelpers.subscribe(odvaProducts, ['favorites:add', 'favorites:delete', 'basket:add']);