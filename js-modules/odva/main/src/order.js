import Base from './base.js';

class OdvaOrder extends Base
{
	async getBasket()
	{
		let response = await this.request.send('getBasket');
		this.notify('getBasket', response);
		return response;
	}

	async getUser()
	{
		let response = await this.request.send('getUser');
		this.notify('getUser', response);
		return response;
	}

	async getDeliveries()
	{
		let response = await this.request.send('getDeliveries');
		this.notify('getDeliveries', response);
		return response;
	}

	async getPaySystems()
	{
		let response = await this.request.send('getPaySystems');
		this.notify('getPaySystems', response);
		return response;
	}

	async getUserProfiles(name, count)
	{
		let response = await this.request.send('getUserProfiles',{name: name, 'count': count});
		this.notify('getUserProfiles', response);
		return response;
	}

	async getOrderCalculate(deliveryId, paySystemId, cityCode, personTypeId)
	{
		let response = await this.request.send('getOrderCalculate',{deliveryId: deliveryId, paySystemId: paySystemId, cityCode: cityCode, personTypeId: personTypeId});
		this.notify('getOrderCalculate', response);
		return response;
	}

	async getLocationsByName(name, count)
	{
		let response = await this.request.send('getLocationsByName',{name: name, count: count});
		this.notify('getLocationsByName', response);
		return response;
	}

	async makeOrder(deliveryId, paySystemId, props, cityCode, personTypeId)
	{
		let response = await this.request.send('makeOrder',{deliveryId: deliveryId, paySystemId: paySystemId, props: props, cityCode: cityCode, personTypeId: personTypeId});
		this.notify('makeOrder', response);
		return response;
	}
}

export default new OdvaOrder('order');
