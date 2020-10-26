<?php

class odva_module extends CModule
{
	public function __construct()
	{
		$this->MODULE_ID           = "odva.module";
		$this->MODULE_VERSION      = "0.0.2";
		$this->MODULE_VERSION_DATE = "2020-09-10 12:00:00";
		$this->MODULE_NAME         = "модуль odva.module";
		$this->MODULE_DESCRIPTION  = "модуль odva.module";

		$this->PARTNER_NAME        = "https://odva.pro/";
		$this->PARTNER_URI         = "https://odva.pro/";
	}

	public function DoInstall()
	{
		RegisterModule($this->MODULE_ID);
		\Bitrix\Main\Loader::includeModule($this->MODULE_ID);

		CopyDirFiles(__DIR__ . "/../js-modules/", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/js/", true, true);
		CopyDirFiles(__DIR__ . "/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");

		\Odva\Module\Model\OptionsTable::getEntity()->createDbTable();
		\Odva\Module\Model\OptionSectionsTable::getEntity()->createDbTable();
	}

	public function DoUninstall()
	{
		\Bitrix\Main\Loader::includeModule($this->MODULE_ID);

		$connection = \Bitrix\Main\Application::getConnection();

		$ruleTable = \Odva\Module\Model\OptionsTable::getEntity()->getDBTableName();
		$sql       = "DROP TABLE IF EXISTS `{$ruleTable}`;";
		$connection->query($sql);

		$ruleTable = \Odva\Module\Model\OptionSectionsTable::getEntity()->getDBTableName();
		$sql       = "DROP TABLE IF EXISTS `{$ruleTable}`;";
		$connection->query($sql);

		Bitrix\Main\IO\Directory::deleteDirectory("{$_SERVER["DOCUMENT_ROOT"]}/bitrix/js/odva/");
		DeleteDirFiles(__DIR__ . "/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");

		UnRegisterModule($this->MODULE_ID);
	}
}