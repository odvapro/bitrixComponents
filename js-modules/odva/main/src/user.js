import Base from './base.js';

class OdvaUser extends Base
{
	async login(login, password, remember='N')
	{
		let response = await this.request.send(
			'login',
			{login: login, password: password, remember: remember},
			{method: 'POST'}
		);
		this.notify('login', response);
		return response;
	}

	async logout()
	{
		let response = await this.request.send('logout');
		this.notify('logout', response);
		return response;
	}

	async forgotPassword(email)
	{
		let response = await this.request.send(
			'forgotPassword',
			{email: email},
			{method: 'POST'}
		);
		this.notify('forgotPassword', response);
		return response;
	}

	async register(login, name, lastname, password, confirm, email, additional=[], authorize=true)
	{
		let response = await this.request.send(
			'register',
			{
				login      : login,
				name       : name,
				lastname   : lastname,
				password   : password,
				confirm    : confirm,
				email      : email,
				additional : additional,
				authorize  : authorize
			},
			{method: 'POST'}
		);
		this.notify('register', response);
		return response;
	}

	async ulogin(token)
	{
		let response = await this.request.send(
			'ulogin',
			{token: token},
			{method: 'POST'}
		);
		this.notify('ulogin', response);
		return response;
	}
}

export default new OdvaUser('user');