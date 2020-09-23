export default class Observer
{
	constructor()
	{
		this.subscribers = [];
		this.eventScope  = 'default';
	}

	subscribe(obj)
	{
		for(let subscriber of this.subscribers)
			if(subscriber == obj)
				return;

		this.subscribers.push(obj);
	}

	unsubscribe(obj)
	{
		this.subscribers = this.subscribers.filter(subscriber => subscriber !== obj);
	}

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
}