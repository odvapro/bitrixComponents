import Observer from './core/observer';

class OdvaOrder extends Observer
{
	constructor()
	{
		super();

		this.eventScope = 'order';
	}

	async getBasket(iblockId)
	{
		let response = await this.getResponse('getBasket', {iblockId: iblockId});
		this.notify('getBasket', response);
		return response;
	}

	async getUser()
	{
		let response = await this.getResponse('getUser');
		this.notify('getUser', response);
		return response;
	}

	async getDeliveries()
	{
		let response = await this.getResponse('getDeliveries');
		this.notify('getDeliveries', response);
		return response;
	}

	async getPaySystems()
	{
		let response = await this.getResponse('getPaySystems');
		this.notify('getPaySystems', response);
		return response;
	}

	async getUserProfiles(name, count)
	{
		let response = await this.getResponse('getUserProfiles',{name: name, 'count': count});
		this.notify('getUserProfiles', response);
		return response;
	}

	async getOrderCalculate(deliveryId, paySystemId, cityCode, personTypeId)
	{
		let response = await this.getResponse('getOrderCalculate',{deliveryId: deliveryId, paySystemId: paySystemId, cityCode: cityCode, personTypeId: personTypeId});
		this.notify('getOrderCalculate', response);
		return response;
	}

	async getLocationsByName(name, count)
	{
		let response = await this.getResponse('getLocationsByName',{name: name, count: count});
		this.notify('getLocationsByName', response);
		return response;
	}

	async makeOrder(deliveryId, paySystemId, props, cityCode, personTypeId)
	{
		let response = await this.getResponse('makeOrder',{deliveryId: deliveryId, paySystemId: paySystemId, props: props, cityCode: cityCode, personTypeId: personTypeId});
		this.notify('makeOrder', response);
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

export default new OdvaOrder();
