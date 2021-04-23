import Base from './base';

/**
 * класс для работы с корзиной
 */
class OdvaBasket extends Base
{
	/**
	 * получение количества товаров в корзине
	 *
	 * @return {Array} ['PRODUCTS' => number, 'ITEMS' => number]
	 */
	async getCount()
	{
		let response = await this.request.send('getCount', false, {method: 'GET'});
		this.notify('getCount', response);
		return response;
	}

	/**
	 * удаление всех товаров из корзины
	 */
	async clear()
	{
		let response = await this.request.send('clear', false, {method: 'GET'});
		this.notify('clear', response);
		return response;
	}

	/**
	 * добавление товара в корзину
	 *
	 * @param {number} productId ID товара
	 * @param {number} quantity количество товара
	 */
	async addItem(productId, quantity)
	{
		let response = await this.request.send('addItem', {productId: productId, quantity: quantity});
		this.notify('addItem', response);
		return response;
	}

	/**
	 * удаление товара из корзины
	 *
	 * @param {number} productId ID товара
	 */
	async deleteItem(productId)
	{
		let response = await this.request.send('deleteItem', {productId: productId});
		this.notify('deleteItem', response);
		return response;
	}

	/**
	 * изменение количества товара в корзине
	 *
	 * @param {number} productId ID товара
	 * @param {number} quantity количество товара (может быть как положительным, так и отрицательным)
	 */
	async changeItemQuantity(productId, quantity)
	{
		let response = await this.request.send('changeItemQuantity', {productId: productId, quantity: quantity});
		this.notify('changeItemQuantity', response);
		return response;
	}

	async applyCoupon(coupon)
	{
		let response = await this.request.send('applyCoupon', {coupon: coupon});
		this.notify('applyCoupon', response);
		return response;
	}

	async deleteCoupon(coupon)
	{
		let response = await this.request.send('deleteCoupon', {coupon: coupon});
		this.notify('deleteCoupon', response);
		return response;
	}

	async getInfo()
	{
		let response = await this.request.send('getInfo');
		this.notify('getInfo', response);
		return response;
	}
}

export default new OdvaBasket('basket');