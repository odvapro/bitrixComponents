<?php

namespace Odva\Module\Controller;

use \Bitrix\Main\Error;
use \Bitrix\Main\Engine\Controller;

class Sender extends Controller
{
	public function configureActions()
	{
		return [
			'subscribeEmail' => ['prefilters' => []],
		];
	}

	public function subscribeEmailAction($email, $rubrics = [])
	{
		$result = \Odva\Module\Sender::subscribe($email, $rubrics);

		if(!is_a($result, '\Bitrix\Main\Error'))
			return true;

		$this->addError($result);

		return false;
	}
}
