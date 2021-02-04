<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");

foreach ($_POST as $key => $value)
{
	if(empty($value))
	{
		echo json_encode(['success'=>false]);
		exit();
	}
}

$arFields = array(
   "ACTIVE" => "Y",
   "IBLOCK_ID" => $_POST['IBLOCK_ID'],
   "IBLOCK_SECTION_ID" => false,
   "NAME" => $_POST['NAME_FORM_RESULT'],
   "CODE" => "NEWRESULT",
   "PROPERTY_VALUES" => $_POST
);
$oElement  = new CIBlockElement();
$idElement = $oElement->Add($arFields, false, false, true);
$result    = CEvent::Send($_POST['MAIL_EVENT_CODE'], "s1", $_POST);
echo json_encode(['success'=>true]);