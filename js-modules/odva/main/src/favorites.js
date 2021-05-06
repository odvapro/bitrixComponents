import Base from './base.js';

class OdvaFavorites extends Base
{
	async get()
	{
		let response = await this.request.send('get', {}, {method: 'GET'});
		this.notify('get', response);
		return response;
	}

	async add(id)
	{
		let response = await this.request.send('add', {id: id});
		this.notify('add', response);
		return response;
	}

	async delete(id)
	{
		let response = await this.request.send('delete', {id: id});
		this.notify('delete', response);
		return response;
	}

	async deleteAll()
	{
		let response = await this.request.send('deleteAll');
		this.notify('deleteAll', response);
		return response;
	}
}

export default new OdvaFavorites('favorites');