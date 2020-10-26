<?php

if(!empty($_POST['edit_values']))
{
	foreach ($_POST['edit_values'] as $sectionId => $options)
	{
		foreach ($options as $optionId => $option)
		{
			if($arOptions[$sectionId]['OPTIONS'][$optionId]->getValue() == $option)
				continue;

			$arOptions[$sectionId]['OPTIONS'][$optionId]->setValue($option)->save();
		}
	}
}