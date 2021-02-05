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
		return \Odva\Module\Favorites::add($id);
	}

	public function getAction()
	{
		return \Odva\Module\Favorites::get($id);
	}

	public function deleteAction($id = 0)
	{
		return \Odva\Module\Favorites::delete($id);
	}

	public function deleteAllAction()
	{
		return \Odva\Module\Favorites::deleteAll();
	}
}
