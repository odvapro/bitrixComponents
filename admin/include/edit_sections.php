<?php

use \Odva\Module\Model\OptionSectionsTable;

if(!empty($_POST['edit_sections']))
{
	// удаление элементов
	if(array_key_exists('delete', $_POST['edit_sections']))
	{
		foreach ($_POST['edit_sections']['delete'] as $sectionId)
		{
			OptionSectionsTable::delete($sectionId);

			foreach ($arOptions[$sectionId]['OPTIONS'] as $optionId => $option)
				$option->delete();

			unset($sections[$sectionId]);
			unset($arOptions[$sectionId]);
			unset($_POST['edit_sections']['edit'][$sectionId]);
		}
	}

	// редактирование существующих
	if(array_key_exists('edit', $_POST['edit_sections']))
	{
		foreach ($_POST['edit_sections']['edit'] as $sectionId => $sectionName)
		{
			if($sectionName == $sections[$sectionId])
				continue;

			if(empty($sectionName))
				$errors['edit_sections']['edit'][$sectionId] = 'Название раздела не может быть пустым';

			if(OptionSectionsTable::getList(['filter' => ['NAME' => $sectionName]])->fetch())
			{
				$errors['edit_sections']['edit'][$sectionId] = "Раздел с названием {$sectionName} уже существует.";
				$sections[$sectionId] = $sectionName;
			}

			if(!empty($errors['edit_sections']['edit'][$sectionId]))
				continue;

			OptionSectionsTable::update($sectionId, ['NAME' => $sectionName]);

			$sections[$sectionId] = $sectionName;
		}
	}

	if(array_key_exists('add', $_POST['edit_sections']))
	{
		$num = 0;

		foreach ($_POST['edit_sections']['add'] as $sectionName)
		{
			if(empty($sectionName))
				continue;

			$num++;

			if(OptionSectionsTable::getList(['filter' => ['NAME' => $sectionName]])->fetch())
			{
				$errors['edit_sections']['add'][$num] = "Раздел с названием {$sectionName} уже существует.";
				continue;
			}

			$result = OptionSectionsTable::add(['NAME' => $sectionName]);

			$sections[$result->getId()] = $sectionName;
		}
	}
}