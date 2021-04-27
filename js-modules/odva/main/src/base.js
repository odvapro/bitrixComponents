import Observer from './core/observer';
import Request from './core/request';

export default class Base extends Observer
{
	constructor(scope)
	{
		super(scope);
		this.request = new Request(scope);
	}
}