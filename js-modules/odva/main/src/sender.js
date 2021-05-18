import Base from './base';

/**
 * класс для работы с подписками
 * работает с модулем "E-mail маркетинг"
 */
class OdvaSender extends Base
{
	async subscribeEmail(email, rubrics = [])
	{
		let response = await this.request.send('subscribeEmail', {email: email, rubrics: rubrics});
		this.notify('subscribeEmail', response);
		return response;
	}
}

export default new OdvaSender('sender');