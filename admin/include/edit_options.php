<?php

use \Odva\Module\Model\OptionsTable;
use \Odva\Module\Model\OptionSectionsTable;

$checker = new EditOptions($_POST['edit_options']);
$errors  = $checker->check();

class EditOptions
{
	private $data   = [];
	private $errors = [];

	public function __construct($data)
	{
		$this->data = $data;
	}

	public function check()
	{
		if(empty($this->data))
			return;

		foreach ($this->data as $optionId => $optionData)
		{
			if(array_key_exists('delete', $optionData) && $optionData['delete'] == 'on')
			{
				OptionsTable::delete($optionId);
				continue;
			}

			if(empty($optionData['NAME']))
				$this->errors[$optionId]['NAME'] = 'Это поле не может быть пустым';

			if(empty($optionData['CODE']))
				$this->errors[$optionId]['CODE'] = 'Это поле не может быть пустым';

			if(empty($optionData['SECTION_ID']))
				$this->errors[$optionId]['SECTION_ID'] = 'Это поле не может быть пустым';

			$option = OptionsTable::getById($optionId)->fetch();

			if(!$option)
				$this->errors[$optionId]['ID'] = 'Такой настройки не существует';

			if(!empty($this->errors[$optionId]))
				continue;

			$section = OptionSectionsTable::getById($optionData['SECTION_ID'])->fetch();

			if(!$section)
			{
				$this->errors[$optionId]['SECTION_ID'] = 'Такого раздела не существует';
				continue;
			}

			if($option['CODE'] != $optionData['CODE'] && OptionsTable::getList(['filter' => ['CODE' => $optionData['CODE']]])->fetch())
				$this->errors[$optionId]['CODE'] = "Свойство с кодом {$optionData['CODE']} уже существует";

			if($option['NAME'] != $optionData['NAME'] && OptionsTable::getList(['filter' => ['NAME' => $optionData['NAME']]])->fetch())
				$this->errors[$optionId]['NAME'] = "Свойство с названием {$optionData['NAME']} уже существует";

			if(!empty($this->errors[$optionId]))
				continue;

			if(
				$option['NAME'] == $optionData['NAME']
				&&
				$option['CODE'] == $optionData['CODE']
				&&
				$option['SECTION_ID'] == $optionData['SECTION_ID']
			)
				continue;

			OptionsTable::update(
				$option['ID'],
				[
					'NAME'       => $optionData['NAME'],
					'CODE'       => $optionData['CODE'],
					'SECTION_ID' => $optionData['SECTION_ID'],
				]
			);
		}

		if(empty($this->errors))
			return false;

		return ['edit_options' => $this->errors];
	}
}