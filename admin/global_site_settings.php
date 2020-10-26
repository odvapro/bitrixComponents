<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\Page\Asset;

use \Odva\Module\Model\OptionsTable;
use \Odva\Module\Model\OptionSectionsTable;

require $_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_admin.php';

Asset::getInstance()->addJs('/bitrix/js/iblock/iblock_edit.js');

$APPLICATION->SetTitle('Модуль глобальных настроек сайта');

$MODULE_ID = 'odva.module';

Loader::includeModule($MODULE_ID);

$sections  = OptionSectionsTable::getList()->fetchAll();
$sections  = array_combine(array_column($sections, 'ID'), array_column($sections, 'NAME'));
$options   = OptionsTable::getList(['select' => ['*', 'S_' => 'SECTION']]);
$arOptions = [];

while ($option = $options->fetchObject())
{
	if(!array_key_exists($option->getSection()->getId(), $arOptions))
		$arOptions[$option->getSection()->getId()] = [
			'NAME'    => $option->getSection()->getName(),
			'OPTIONS' => []
		];

	$arOptions[$option->getSection()->getId()]['OPTIONS'][$option->getId()] = $option;
}

if($REQUEST_METHOD == "POST" && check_bitrix_sessid())
{
	CUtil::JSPostUnescape();
	$errors = [];
	include "include/{$_POST['global_settings_active_tab']}.php";

	if(empty($errors))
	{
		$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
		$uri     = new Bitrix\Main\Web\Uri($request->getRequestUri());

		$uri->deleteParams(['global_settings_active_tab']);
		$uri->addParams(['global_settings_active_tab' => $_POST['global_settings_active_tab']]);

		LocalRedirect($uri->getUri());
	}
}

$aTabs = [
	[
		'DIV'     => 'edit_values',
		'TAB'     => 'Значения'
	],
	[
		'DIV'     => 'edit_sections',
		'TAB'     => 'Редактирование разделов',
		'ICON'    => 'main_user_edit'
	],
	[
		'DIV'     => 'edit_options',
		'TAB'     => 'Редактирование свойств'
	],
	[
		'DIV'     => 'add_option',
		'TAB'     => 'Добавить свойство'
	],
];

$tabControl = new CAdminTabControl('global_settings', $aTabs);
?>
<form method="post" action="<?= $APPLICATION->GetCurPage() ?>" enctype="multipart/form-data" name="post_form">
	<?php
	echo bitrix_sessid_post();

	$tabControl->Begin();


	// установка значений свойств
	$tabControl->BeginNextTab();

	foreach ($sections as $sectionId => $sectionName)
	{
		?>
		<tr class="heading">
			<td colspan="2">
				<span><?= $sectionName ?></span>
				<a href="javascript:void(0)" class="adm-btn" onclick="global_settings.SelectTab('edit_sections');" style="float: right;">редактировать</a>
			</td>
		</tr>
		<?php
		foreach ($arOptions[$sectionId]['OPTIONS'] as $optionId => $option)
		{
			?>
			<tr>
				<td width="40%"><?= $option->getName() ?>:</td>
				<td width="60%">
					<input type="text" name="edit_values[<?=$sectionId?>][<?=$optionId?>]" value="<?=$option->getValue()?>">
					<a href="javascript:void(0)" class="adm-btn" onclick="global_settings.SelectTab('edit_options');">настройки</a>
				</td>
			</tr>
			<?php
		}

		?>
		<tr>
			<td width="40%"></td>
			<td width="60%">
				<a href="javascript:void(0)" onclick="BX('new_option_section').value = <?=$sectionId?>;global_settings.SelectTab('add_option');" class="adm-btn adm-btn-save adm-btn-add">Добавить свойство</a>
			</td>
		</tr>
		<?php
	}


	// редактирование разделов
	$tabControl->BeginNextTab();
	?>
	<tr>
		<td colspan="2">
			<?=BeginNote()?>
			Для удаления <strong>уже сохраненного</strong> раздела надо поставить рядом с ней галочку "удалить".
			Для удаления только что добавленного раздела - очистить текстовое поле. Пустые текстовые поля не создают разделов.<br><br>
			<span class="required">Внимание! При удалении раздела удаляются все настройки, привязанные к разделу.</span>
			<?=EndNote()?>
		</td>
	</tr>
	<tr>
		<td width="20%"></td>
		<td width="60%">
			<table id="tb-edit-sections">
				<?php
				foreach ($sections as $sectionId => $sectionName)
				{
					?>
					<tr>
						<td>
							<input type="text" name="edit_sections[edit][<?= $sectionId ?>]" value="<?= $sectionName ?>">
							<label><input type="checkbox" name="edit_sections[delete][]" value="<?= $sectionId ?>"> удалить</label>
							<?php
							if(array_key_exists($sectionId, $errors['edit_sections']['edit']))
							{
								?>
								<font class="errortext"><?=$errors['edit_sections']['edit'][$sectionId]?></font>
								<?php
							}
							?>
						</td>
					</tr>
					<?php
				}

				$num = 0;

				foreach ($_POST['edit_sections']['add'] as $sname)
				{
					if(empty($sname))
						continue;

					$num++;

					if(empty($errors['edit_sections']['add'][$num]))
						continue;
					?>
					<tr>
						<td>
							<input type="text" name="edit_sections[add][]" value="<?=$sname?>">
							<font class="errortext"><?=$errors['edit_sections']['add'][$num]?></font>
							<a href="javascript:void(0)" onclick="BX(this).closest('tr').remove()">удалить</a>
						</td>
					</tr>
					<?php
				}
				?>
				<tr>
					<td>
						<input type="text" name="edit_sections[add][]" value="">
					</td>
				</tr>
				<tr><td><input type="button" value="Добавить" onClick="BX.IBlock.Tools.addNewRow('tb-edit-sections')"></td></tr>
			</table>
		</td>
	</tr>
	<?php
	// редактирование свойств
	$tabControl->BeginNextTab();

	if(!empty($arOptions))
	{
		foreach ($arOptions as $sectionId => $section)
		{
			?>
			<tr class="heading">
				<td colspan="3">
					<span><?= $section['NAME'] ?></span>
				</td>
			</tr>
			<?php
			foreach ($section['OPTIONS'] as $optionId => $option)
			{
				?>
				<tr>
					<td width="20%"></td>
					<td width="20%">
						<table class="adm-detail-content-table edit-table">
							<tr class="heading">
								<td>
									<span><?=$option->getName()?></span>
								</td>
							</tr>
						</table>
					</td>
					<td width="40%" style="vertical-align: middle; padding-left: 20px;">
						<input type="checkbox" name="edit_options[<?=$optionId?>][delete]">
						удалить
					</td>
				</tr>
				<tr>
					<td width="20%"></td>
					<td width="20%">Имя:</td>
					<td width="40%">
						<input type="text" name="edit_options[<?=$optionId?>][NAME]" value="<?=$option->getName()?>">
						<?php
						if(array_key_exists('NAME', $errors['edit_options'][$optionId]))
						{
							?>
							<br><font class="errortext"><?=$errors['edit_options'][$optionId]['NAME']?></font>
							<?php
						}
						?>
					</td>
				</tr>
				<tr>
					<td width="20%"></td>
					<td width="20%">Код:</td>
					<td width="40%">
						<input type="text" name="edit_options[<?=$optionId?>][CODE]" value="<?=$option->getCode()?>">
						<?php
						if(array_key_exists('CODE', $errors['edit_options'][$optionId]))
						{
							?>
							<br><font class="errortext"><?=$errors['edit_options'][$optionId]['CODE']?></font>
							<?php
						}
						?>
					</td>
				</tr>
				<tr>
					<td width="20%"></td>
					<td width="20%">Раздел:</td>
					<td width="40%">
						<select name="edit_options[<?=$optionId?>][SECTION_ID]">
							<?php
							foreach ($sections as $sid => $sname)
							{
								?>
								<option value="<?=$sid?>" <?=($sid == $sectionId ? ' selected' : '')?>><?=$sname?></option>
								<?php
							}
							?>
						</select>
						<?php
						if(array_key_exists('SECTION_ID', $errors['edit_options'][$optionId]))
						{
							?>
							<br><font class="errortext"><?=$errors['edit_options'][$optionId]['SECTION_ID']?></font>
							<?php
						}
						?>
					</td>
				</tr>
				<?php
			}
		}
	}
	else
	{
		?>
		Свойства еще не созданы.<br><br>
		<a href="javascript:void(0)" onclick="global_settings.SelectTab('add_option');" class="adm-btn adm-btn-save adm-btn-add">Добавить свойство</a>
		<?php
	}


	// добавление свойств
	$tabControl->BeginNextTab();
	?>
	<tr>
		<td width="40%">Имя:</td>
		<td width="60%">
			<input type="text" name="add_option[NAME]" value="<?=$_POST['add_option']['NAME']?>" style="width: 200px; box-sizing: border-box;">
			<?php
			if(!empty($errors['add_option']['NAME']))
			{
				?>
				<br><font class="errortext"><?=$errors['add_option']['NAME']?></font>
				<?php
			}
			?>
		</td>
	</tr>
	<tr>
		<td width="40%">Код:</td>
		<td width="60%">
			<input type="text" name="add_option[CODE]" value="<?=$_POST['add_option']['CODE']?>" style="width: 200px; box-sizing: border-box;">
			<?php
			if(!empty($errors['add_option']['CODE']))
			{
				?>
				<br><font class="errortext"><?=$errors['add_option']['CODE']?></font>
				<?php
			}
			?>
		</td>
	</tr>
	<tr>
		<td width="40%">Раздел:</td>
		<td width="60%">
			<select name="add_option[SECTION_ID]" id="new_option_section" style="width: 200px; box-sizing: border-box;">
				<option value=""></option>
				<?php
				foreach ($sections as $sectionId => $sectionName)
				{
					$selected = $sectionId == $_POST['add_option']['SECTION_ID'] ? ' selected' : '';
					?>
					<option value="<?=$sectionId?>"<?=$selected?>><?=$sectionName?></option>
					<?php
				}
				?>
			</select>
			<?php
			if(!empty($errors['add_option']['SECTION_ID']))
			{
				?>
				<br><font class="errortext"><?=$errors['add_option']['SECTION_ID']?></font>
				<?php
			}
			?>
		</td>
	</tr>
	<?php
	$tabControl->Buttons(['btnApply' => false]);
	$tabControl->End();
	?>
</form>
<?php
require $_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/epilog_admin.php';