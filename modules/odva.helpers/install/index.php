<?php

class odva_helpers extends CModule
{
	public function __construct()
	{
		$this->MODULE_ID           = "odva.helpers";
		$this->MODULE_VERSION      = "1.0.0";
		$this->MODULE_VERSION_DATE = "2020-02-26 12:00:00";
		$this->MODULE_NAME         = "модуль odva.helpers";
		$this->MODULE_DESCRIPTION  = "модуль odva.helpers";

		$this->PARTNER_NAME        = "https://odva.pro/";
		$this->PARTNER_URI         = "https://odva.pro/";
	}

	public function DoInstall()
	{
		RegisterModule($this->MODULE_ID);
		CopyDirFiles(__DIR__."/../js/odva.lib.js", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/js/odva.lib.js");
	}

	public function DoUninstall()
	{
		UnRegisterModule($this->MODULE_ID);
		DeleteDirFiles(__DIR__."/../js/odva.lib.js", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/js/odva.lib.js");
	}
}