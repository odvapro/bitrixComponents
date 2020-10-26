<?php

use \Odva\Module\Model\OptionsTable;
use \Odva\Module\Model\OptionSectionsTable;

$checker = new AddOption($_POST['add_option'], $sections, $arOptions);
$errors  = $checker->check();

class AddOption
{
	private $data   = [];
	private $errors = [];

	public function __construct($data, &$sections, &$options)
	{
		$this->data     = $data;
		$this->sections = $sections;
		$this->options  = $options;
	}

	public function check()
	{
		if(empty($this->data))
			return;

		if(empty($this->data['NAME']))
			$this->errors['NAME'] = 'Заполните это поле';

		if(empty($this->data['CODE']))
			$this->errors['CODE'] = 'Заполните это поле';

		if(empty($this->data['SECTION_ID']))
			$this->errors['SECTION_ID'] = 'Заполните это поле';

		if(empty($this->errors['NAME']) && OptionsTable::getList(['filter' => ['NAME' => $this->data['NAME']]])->fetch())
			$this->errors['NAME'] = "Свойство с именем {$this->data['NAME']} уже существует.";

		if(empty($this->errors['CODE']) && OptionsTable::getList(['filter' => ['CODE' => $this->data['CODE']]])->fetch())
			$this->errors['CODE'] = "Свойство с кодом {$this->data['CODE']} уже существует.";

		if(empty($this->errors['SECTION_ID']) && !OptionSectionsTable::getList(['filter' => ['ID' => $this->data['SECTION_ID']]])->fetch())
			$this->errors['SECTION_ID'] = "Раздел #{$this->data['SECTION_ID']} не существует.";

		if(!empty($this->errors))
			return ['add_option' => $this->errors];

		$result = OptionsTable::add([
			'NAME'       => $this->data['NAME'],
			'CODE'       => $this->data['CODE'],
			'SECTION_ID' => $this->data['SECTION_ID'],
		]);

		if($result->isSuccess())
			$this->options[$this->data['SECTION_ID']]['OPTIONS'][$result->getId()] = $result->getObject();

		return [];
	}
}