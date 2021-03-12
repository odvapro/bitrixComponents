var favoritePage =
{
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
	isReloadPage:function()
	{
		if($(".product-item").length == 0)
		{
			document.location.reload(true);
		}
	},
	favoritesDelete:function(data)
	{
		if(!data.success)
			return;
		$(`[data-favorit-id=${this.target}]`).closest('.product-item').remove();

		this.isReloadPage();
	},
	deletingFavorite:function(e,init)
	{
		e.preventDefault();
		this.target = $(init).data("favorit-id");
		odvaHelpers.favorites.delete(this.target);
	},
	getAlso:function(init)
	{
		this.conteiner = $(".favorites__items");
		let idfavorite = $(this.conteiner).find(".product-item").last().data("id-product");
		$.post(
		   	BX.message('PATH_GET_ALSO'),
		   	{
		   		ID:idfavorite
		   	},
		   	(data)=>
		   	{
		   		$(this.conteiner).append(data);
		   		if($('div[data-is-last="Y"]').length !=0 )
		   		{
		   			$(init).hide();
		   		}
		   	}
		);
	},
	favoritesDeleteAll:function(data)
	{
		if(!data.success)
			return;
		$(".product-item__action[data-favorit-id]").click();
		$(".product-item").remove();
		$(".simple-button").hide();
		$(".favorites__сlean").css("display","block");
	},
	deleteAll:function()
	{
		odvaHelpers.favorites.deleteAll();
	},
	notify: function(event, data)
	{
		let callback = event.split(':');
		callback[1] = callback[1].charAt(0).toUpperCase() + callback[1].slice(1);
		callback = callback.join('');
		this[callback](data);
	}
}
odvaHelpers.subscribe(favoritePage, ['favorites:delete','favorites:deleteAll', 'basket:add']);