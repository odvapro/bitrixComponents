var odvaCart =
{
	CartQuatityOld:null,
	CartQuatityContainer:null,
	notify: function(event, data)
	{
		if(event == "basket:chg" || event == "basket:delete")
		{
			if(data.success)
			{
				this.setActualPrice();
				this.recountItemPrice();
				this.recountCountItems();
				this.isReloadPage();
			}
			else
			{
				this.CartQuatityContainer.html(this.CartQuatityOld);
			}
		}
	},
	isReloadPage:function()
	{
		if($(".cart__detail-item").length == 0)
		{
			document.location.reload(true);
		}
	},
	ucfirst:function(word)
	{
		return word.charAt(0).toUpperCase() + word.slice(1);
	},
	declension:function(number, data)
	{
		let rest = [number % 10, number % 100];
		if(rest[1] > 10 && rest[1] < 20)
		{
	    	return data[2];
	 	}
		else if (rest[0] > 1 && rest[0] < 5)
	 	{
	 		return data[1];
		}
		else if (rest[0] == 1)
		{
			return data[0];
		}
		return data[2];
	},
	recountCountItems:function()
	{
		let count = 0;
		$(".cart__detail-count-number").each((index,element)=>
			{
				count += parseInt($(element).html());
			}
		)
		let word = this.declension(count,['товар','товара','товаров']);
		$(".checkout-summary__line--count").html(this.ucfirst(word)+" ("+count+")");
		$("h3.title").html("В корзине <span>"+count+"</span> "+word);
	},
	deleteItem:function(e,init)
	{
		odvaHelpers.basket.delete($(init).data("item-id"));
		$(init).closest('.cart__detail-item').remove();
	},
	setActualPrice:function()
	{
		$.post(
		    BX.message('PATH_GET_ACTUAL_PRICES'),
		    {},
		    (data)=>
		    {
		    	let result = JSON.parse(data);
		    	if(result.success)
		    	{
		    		result = result.result;
			    	let discount = parseFloat(result.PRICE.DISCOUNT);
			    	let price = parseFloat(result.PRICE.VALUE);
			    	let fullPrice = discount + price;
			    	if(discount == 0)
			    	{
			    		$(".checkout-summary__line--discount").parent().addClass('__hide-elememt');
			    	}
			    	else
			    	{
			    		$(".checkout-summary__line--discount").parent().removeClass('__hide-elememt');
			    	}
			    	$(".checkout-summary__line--price").html(fullPrice.toFixed(0)+"₽")
			    	$(".checkout-summary__line--discount").html("-"+discount.toFixed(2)+"₽")
			    	$(".checkout-summary__line-sum").html(price.toFixed(2))
		    	}
		    	else
		    	{
		    		$(".checkout-summary__line--price").html(0+"₽")
	    			$(".checkout-summary__line--discount").html("-"+0+"₽")
	    			$(".checkout-summary__line-sum").html(0)
		    	}
		    }
		)
	},
	recountItemPrice:function()
	{
		$(".cart__detail-qn").each((index,element)=>
			{
				let count = parseFloat($(element).find(".cart__detail-count-number").html());
				let onePrice = parseFloat($(element).find(".cart__detail-price-one span").html());
				let result = count * onePrice;
				$(element).find(".cart__detail-price span").html(result.toFixed(0));
			}
		)
	},
	canUpChange:function(init,count)
	{
		let storage = $(init).parent().data('storage');
		storage = parseInt(storage);
		count = parseInt(count);
		return count+1 > storage;
	},
	cartCount(instance)
	{

		let CartProductQuatity = $(instance).siblings('.cart__detail-count-number'),
			priceForOne = $(instance).parents('.cart__detail-qn').find('.cart__detail-price-one span'),
			priceForOneVal = +(priceForOne.text().replace(',', '.')),
			productId = $(instance).closest('.cart__detail-count').data('product-id');
			this.CartQuatityOld = CartProductQuatity.html();
			this.CartQuatityContainer = CartProductQuatity;
		if(this.canUpChange(instance,this.CartQuatityOld) && $(instance).hasClass('cart__detail-count-plus'))
		{
			alert("Вы не можете заказать больше!");
			return;
		}
		if(+CartProductQuatity.text() > 1)
		{

		}else
		{
			priceForOne.parent().removeClass('cart__detail-price-one--show')
		}

		if ($(instance).hasClass('cart__detail-count-plus'))
		{
			priceForOne.parent().addClass('cart__detail-price-one--show');
			CartProductQuatity.text(+CartProductQuatity.text() + 1);
			odvaHelpers.basket.chg(productId,CartProductQuatity.text());
		}
		else if(+CartProductQuatity.text() > 1)
		{
			if($(instance).hasClass('cart__detail-count-minus') && CartProductQuatity.text() == 2)
			{
				priceForOne.parent().removeClass('cart__detail-price-one--show');
			}
			CartProductQuatity.text(+CartProductQuatity.text() - 1);
			odvaHelpers.basket.chg(productId,CartProductQuatity.text());
		}
	},
	setPromocod:function(e,init)
	{
		e.preventDefault();
		$.post(
				BX.message('PATH_ACTIVATE_RPOMOCOD'),
				$(init).serialize(),
				(data)=>{
					let result = JSON.parse(data);
					if(result.success == true)
					{
						let price = parseFloat(result.PRICE.VALUE);
						let discount = parseFloat(result.PRICE.DISCOUNT);
						$(".checkout-summary__line--discount").html("-"+(discount.toFixed(2))+"₽");
						this.setActualPrice();
						this.recountItemPrice();
					}
				}
		);
	}
}

odvaHelpers.subscribe(odvaCart, ['basket:add', 'basket:delete','basket:chg']);