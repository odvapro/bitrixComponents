var odvaHelpers = {
	//свойство объекта в котором содержатся подписчикики на события
	subscribers: {},
	//метод полписывающий объекты на события
	subscribe: function(object, events)
	{
		for(let event of events)
		{
			if(!this.subscribers.hasOwnProperty(event))
				this.subscribers[event] = [];
			this.subscribers[event].push(object);
		}
	},
	//метод который отрабатвает после каждого события и отправляет сообщения подписчикам с результатом выполнения
	notify: function(event, data)
	{
		for(let i in this.subscribers[event])
			this.subscribers[event][i].notify(event, data);
	},
	//объект который отвечает за работу с корзиной
	basket:
	{
		//путь к файлу в котором выполняются проверка данных и действия с корзиной, куда будут выполнятся запросы
		ajaxFile:'/local/modules/odva.helpers/ajax/basket.php',

		// метод события добавления в корзину
		// принимаемые параметры:
		// productId - id продукта(елемента инфоблока)
		// quantity - количество товаров
		add: function(productId, quantity)
		{
			//проверка на то передали ли необходимые параметры
			if(!productId || !quantity)
			{
				//если нет послать подписчикам ответ о некудачном добавлений в корзину и причину ошибки
				odvaHelpers.notify(`basket:add`, {success: false, error: {msg: 'Параметры productId, quantity обязательные'}});
				return;
			}
			// формируем данные для ajax запроса
			let data = {
				productId : productId,//productId - id продукта(елемента инфоблока)
				quantity  : quantity,//quantity - количество товаров
				action    : 'add'//событие которое должно выполнится
			};
			// выполняем ajax запрос
			$.ajax({
				url      : this.ajaxFile,//путь к файлу куда будет выполнятся запрос
				data     : data,//передаваемые данные
				dataType : 'json',//тип данных в котором будет ответ с файла
				success  : function(response)
				{
					//в случае успеха отправляем подписчикам результат
					odvaHelpers.notify(`basket:add`, response);
				},
				error    : function(xhr, status, msg)
				{
					//в случае ошибки, сообщаю это подписчиткам
					odvaHelpers.notify(`basket:add`, {success: false, error: {status: status, msg: msg}});
				}
			});
		},
		//метод события изменения количества товара в корзине
		//принимаемые параметры:
		//productId - id продукта(елемента инфоблока)
		//quantity - количество товаров
		chg: function(productId, quantity)
		{
			//проверка на то передали ли необходимые параметры
			if(!productId || !quantity)
			{
				//если нет послать подписчикам ответ о некудачном изменений товара в корзине и причину ошибки
				odvaHelpers.notify(`basket:chg`, {success: false, error: {msg: 'Параметры productId, quantity обязательные'}});
				return;
			}
			// формируем данные для ajax запроса
			let data = {
				productId : productId,//productId - id продукта(елемента инфоблока)
				quantity  : quantity,//quantity - количество товаров
				action    : 'chg'//событие которое должно выполнится
			};
			// выполняем ajax запрос
			$.ajax({
				url      : this.ajaxFile,//путь к файлу куда будет выполнятся запрос
				data     : data,//передаваемые данные
				dataType : 'json',//тип данных в котором будет ответ с файла
				success  : function(response)
				{
					//в случае успеха отправляем подписчикам результат
					odvaHelpers.notify(`basket:chg`, response);
				},
				error    : function(xhr, status, msg)
				{
					//в случае ошибки, сообщаю это подписчиткам
					odvaHelpers.notify(`basket:chg`, {success: false, error: {status: status, msg: msg}});
				}
			});
		},
		//метод события удаления товара из корзины
		//принимаемые параметры:
		//itemId - id продукта в корзине(это не id елемента инфоблока)
		delete: function(itemId)
		{
			//проверка на то передали ли необходимые параметры
			if(!itemId)
			{
				//если нет послать подписчикам ответ о некудачном удалении товара из корзины и причину ошибки
				odvaHelpers.notify(`basket:delete`, {success: false, error: {msg: 'Параметры itemId обязательный'}});
				return;
			}
			// формируем данные для ajax запроса
			let data = {
				itemId    : itemId,//itemId - id продукта в корзине(это не id елемента инфоблока)
				action    : 'delete'//событие которое должно выполнится
			};

			$.ajax({
				url      : this.ajaxFile,//путь к файлу куда будет выполнятся запрос
				data     : data,//передаваемые данные
				dataType : 'json',//тип данных в котором будет ответ с файла
				success  : function(response)
				{
					//в случае успеха отправляем подписчикам результат
					odvaHelpers.notify(`basket:delete`, response);
				},
				error    : function(xhr, status, msg)
				{
					//в случае ошибки, сообщаю это подписчиткам
					odvaHelpers.notify(`basket:delete`, {success: false, error: {status: status, msg: msg}});
				}
			});
		}
	},
	//объек который отвечает за работу с избранными товарами
	favorites:
	{
		//метод позволяющий достать id всех избранных елементов инфоблока
		get: function()
		{
			//вызываю метод формирования и выполняющего запрос к файлу который работает с избранными товарами
			this.send('get');
		},
		//метод добавляющий id елемента инфоблока в избранное
		//принимаемые параметры
		//id - id елемента инфоблока
		add: function(id)
		{
			//вызываю метод формирования и выполняющего запрос к файлу который работает с избранными елементами ифоблока
			this.send('add', id);
		},
		//метод удаляющий id елемента инфоблока из избранного
		//принимаемые параметры
		//id - id елемента инфоблока
		delete: function(id)
		{
			//вызываю метод формирования и выполняющего запрос к файлу который работает с избранными елементами ифоблока
			this.send('delete', id);
		},
		//метод удаляющий все id елементов инфоблока из избранного
		deleteAll: function()
		{
			//вызываю метод формирования и выполняющего запрос к файлу который работает с избранными елементами ифоблока
			this.send('deleteAll');
		},
		//метод который формирует запрос к файлу который работает с избранными елементами ифоблока
		send: function(action, id)
		{
			//формирую данные которые хочу передать в файл
			let data = {
				action: action
			};
			//если текущее действие не доставание всех id то добавляю свойство id в передаваемые данные
			if(action != 'get')
				data['id'] = id;
			//выполняю запрос
			$.ajax({
				url      : '/local/modules/odva.helpers/ajax/favorites.php',//пусть к файлу для проверки данных и работы с избранным
				data     : data,//передаваемые данные
				dataType : 'json',//тип данных в котором будет ответ с файла
				success  : function(response)
				{
					//в случае успеха отправляем подписчикам результат
					odvaHelpers.notify(`favorites:${data.action}`, response);
				},
				error    : function(xhr, status, msg)
				{
					//в случае ошибки, сообщаю это подписчиткам
					odvaHelpers.notify(`favorites:${data.action}`, {success: false, error: {status: status, msg: msg}});
				}
			});
		}
	}
};