<?php

namespace Odva\Module\Model;

use Bitrix\Main\Entity;

class OptionSectionsTable extends Entity\DataManager
{
	public static function getMap()
	{
		return [
			new Entity\IntegerField('ID', ['primary' => true,'autocomplete' => true]),
			new Entity\TextField('NAME', ['required' => true])
		];
	}
}