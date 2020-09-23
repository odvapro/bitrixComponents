import Observer from './core/observer';

class OdvaBasket extends Observer
{
	constructor()
	{
		super();

		this.eventScope = 'basket';
	}

	async getCount()
	{
		let response = await this.getResponse('getCount', false, {method: 'GET'});
		this.notify('getCount', response);
		return response;
	}

	async clear()
	{
		let response = await this.getResponse('clear', false, {method: 'GET'});
		this.notify('clear', response);
		return response;
	}

	async addItem(productId, quantity)
	{
		let response = await this.getResponse('addItem', {productId: productId, quantity: quantity});
		this.notify('addItem', response);
		return response;
	}

	async deleteItem(productId)
	{
		let response = await this.getResponse('deleteItem', {productId: productId});
		this.notify('deleteItem', response);
		return response;
	}

	async changeItemQuantity(productId, quantity)
	{
		let response = await this.getResponse('changeItemQuantity', {productId: productId, quantity: quantity});
		this.notify('changeItemQuantity', response);
		return response;
	}

	async applyCoupon(coupon)
	{
		let response = await this.getResponse('applyCoupon', {coupon: coupon});
		this.notify('applyCoupon', response);
		return response;
	}

	async deleteCoupon(coupon)
	{
		let response = await this.getResponse('deleteCoupon', {coupon: coupon});
		this.notify('deleteCoupon', response);
		return response;
	}

	async getInfo()
	{
		let response = await this.getResponse('getInfo');
		this.notify('getInfo', response);
		return response;
	}

	async getResponse(action, data = {}, config)
	{
		let defaultConfig = {
			dataType : 'json',
			method   : 'POST'
		};

		config = config || {};
		config = $.extend(defaultConfig, config);

		config.url  = this.makeApiUrl(action);
		config.data = config.data || data;

		return await $.ajax(config);
	}

	makeApiUrl(action)
	{
		return `/bitrix/services/main/ajax.php?action=odva:module.api.${this.eventScope}.${action}`;
	}
}

export default new OdvaBasket();