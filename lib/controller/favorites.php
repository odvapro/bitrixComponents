<?php

namespace Odva\Module\Controller;

use \Bitrix\Main\Error;
use \Bitrix\Main\Engine\Controller;

class Favorites extends Controller
{
	public function configureActions()
	{
		return [
			'add'       => ['prefilters' => []],
			'get'       => ['prefilters' => []],
			'delete'    => ['prefilters' => []],
			'deleteAll' => ['prefilters' => []],
		];
	}

	public function addAction($id = 0)
	{
		$result = \Odva\Module\Favorites::add($id);

		if(is_a($result, '\Bitrix\Main\Error'))
		{
			$this->addError($result);
			return false;
		}

		return $result;
	}

	public function getAction()
	{
		return \Odva\Module\Favorites::get($id);
	}

	public function deleteAction($id = 0)
	{
		$result = \Odva\Module\Favorites::delete($id);

		if(is_a($result, '\Bitrix\Main\Error'))
		{
			$this->addError($result);
			return false;
		}

		return $result;
	}

	public function deleteAllAction()
	{
		return \Odva\Module\Favorites::deleteAll();
	}
}
