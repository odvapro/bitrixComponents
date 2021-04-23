export default class Request
{
	constructor(scope)
	{
		this.scope = scope;
	}

	async send(action, data = {}, config)
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
		return `/bitrix/services/main/ajax.php?action=odva:module.api.${this.scope}.${action}`;
	}
}