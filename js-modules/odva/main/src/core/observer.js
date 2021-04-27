/**
 * класс для реализации шаблона проектирования "Наблюдатель"
 */
class Observer
{
	constructor(scope)
	{
		this.subscribers = [];
		this.eventScope  = scope || 'default';
	}

	/**
	 * метод подписки на события
	 */
	subscribe(obj)
	{
		for(let subscriber of this.subscribers)
			if(subscriber == obj)
				return;

		this.subscribers.push(obj);
	}

	/**
	 * метод отписки от событий
	 */
	unsubscribe(obj)
	{
		this.subscribers = this.subscribers.filter(subscriber => subscriber !== obj);
	}

	/**
	 * оповещение о новом событии подписчиков
	 */
	notify(eventType, data)
	{
		let event = this.getEventHandlerName(eventType);

		this.subscribers.forEach(subscriber => subscriber[event] ? subscriber[event](data) : false);
	}

	getEventHandlerName(eventType)
	{
		eventType = eventType.charAt(0).toUpperCase() + eventType.slice(1);

		return `${this.eventScope}${eventType}Event`;
	}
};

export default Observer;