<?php

namespace Odva\Module\Model;

use Bitrix\Main\Entity;

class OptionsTable extends Entity\DataManager
{
	public static function getMap()
	{
		return [
			new Entity\IntegerField('ID', ['primary' => true, 'autocomplete' => true]),
			new Entity\TextField('NAME', ['required' => true]),
			new Entity\TextField('CODE', ['required' => true]),
			new Entity\TextField('VALUE'),
			new Entity\IntegerField('SECTION_ID'),
			new Entity\ReferenceField(
                'SECTION',
                \Odva\Module\Model\OptionSectionsTable::class,
                ['=this.SECTION_ID' => 'ref.ID']
            )
		];
	}
}