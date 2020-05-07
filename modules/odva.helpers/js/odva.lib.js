var odvaHelpers = {
	subscribers: {},
	subscribe: function(object, events)
	{
		for(let event of events)
		{
			if(!this.subscribers.hasOwnProperty(event))
				this.subscribers[event] = [];
			this.subscribers[event].push(object);
		}
	},
	notify: function(event, data)
	{
		for(let i in this.subscribers[event])
			this.subscribers[event][i].notify(event, data);
	},
	basket:
	{
		ajaxFile:'/local/modules/odva.helpers/ajax/basket.php',
		add: function(productId, quantity)
		{
			if(!productId || !quantity)
			{
				odvaHelpers.notify(`basket:add`, {success: false, error: {msg: 'Параметры productId, quantity обязательные'}});
				return;
			}

			let data = {
				productId : productId,
				quantity  : quantity,
				action    : 'add'
			};

			$.ajax({
				url      : this.ajaxFile,
				data     : data,
				dataType : 'json',
				success  : function(response)
				{
					odvaHelpers.notify(`basket:add`, response);
				},
				error    : function(xhr, status, msg)
				{
					odvaHelpers.notify(`basket:add`, {success: false, error: {status: status, msg: msg}});
				}
			});
		},
		chg: function(productId, quantity)
		{
			if(!productId || !quantity)
			{
				odvaHelpers.notify(`basket:chg`, {success: false, error: {msg: 'Параметры productId, quantity обязательные'}});
				return;
			}

			let data = {
				productId : productId,
				quantity  : quantity,
				action    : 'chg'
			};

			$.ajax({
				url      : this.ajaxFile,
				data     : data,
				dataType : 'json',
				success  : function(response)
				{
					odvaHelpers.notify(`basket:chg`, response);
				},
				error    : function(xhr, status, msg)
				{
					odvaHelpers.notify(`basket:chg`, {success: false, error: {status: status, msg: msg}});
				}
			});
		},
		delete: function(itemId)
		{
			if(!itemId)
			{
				odvaHelpers.notify(`basket:delete`, {success: false, error: {msg: 'Параметры itemId обязательный'}});
				return;
			}

			let data = {
				itemId    : itemId,
				action    : 'delete'
			};

			$.ajax({
				url      : this.ajaxFile,
				data     : data,
				dataType : 'json',
				success  : function(response)
				{
					odvaHelpers.notify(`basket:delete`, response);
				},
				error    : function(xhr, status, msg)
				{
					odvaHelpers.notify(`basket:delete`, {success: false, error: {status: status, msg: msg}});
				}
			});
		}
	},
	favorites:
	{
		get: function()
		{
			this.send('get');
		},
		add: function(id)
		{
			this.send('add', id);
		},
		delete: function(id)
		{
			this.send('delete', id);
		},
		deleteAll: function()
		{
			this.send('deleteAll');
		},
		send: function(action, id)
		{
			let data = {
				action: action
			};

			if(action != 'get')
				data['id'] = id;

			$.ajax({
				url      : '/local/modules/odva.helpers/ajax/favorites.php',
				data     : data,
				dataType : 'json',
				success  : function(response)
				{
					odvaHelpers.notify(`favorites:${data.action}`, response);
				},
				error    : function(xhr, status, msg)
				{
					odvaHelpers.notify(`favorites:${data.action}`, {success: false, error: {status: status, msg: msg}});
				}
			});
		}
	}
};