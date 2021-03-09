<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include 'class.php';

$errors      = [];
$iblockId    = false;
$elementName = false;

if(array_key_exists('PARAMS', $_POST))
{
	$signedParams = Form::getParamsFromSignedString($_POST['PARAMS']);

	if($signedParams === false)
	{
		echo json_encode(['success' => false, 'errors' => ['global' => 'Не корректный запрос.']]);
		exit();
	}

	if(array_key_exists('REQUIRED_FIELDS', $signedParams))
		foreach ($signedParams['REQUIRED_FIELDS'] as $requiredField)
			if(empty($_POST[$requiredField]))
				$errors[$requiredField] = 'Заполните поле.';

	if(!empty($errors))
	{
		echo json_encode(['success' => false, 'errors' => $errors]);
		exit();
	}

	$iblockId    = $signedParams['IBLOCK_ID'];
	$elementName = $signedParams['ELEMENT_NAME'];
}

\Bitrix\Main\Loader::includeModule("iblock");

$arFields = [
	"ACTIVE"            => "Y",
	"IBLOCK_ID"         => $iblockId,
	"IBLOCK_SECTION_ID" => false,
	"NAME"              => $elementName,
	"PROPERTY_VALUES"   => $_POST
];

$el = new CIBlockElement;
$id = $el->Add($arFields);

if(!$id)
{
	echo json_encode(['success' => false, 'errors' => ['global' => $el->LAST_ERROR]]);
	exit();
}

if(!empty($_POST['MAIL_EVENT_CODE']))
	CEvent::Send($_POST['MAIL_EVENT_CODE'], SITE_ID, $_POST);

echo json_encode(['success' => true]);
