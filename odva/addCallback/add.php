<?php
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");

if(empty($_POST['phone']) || empty($_POST['name']))
{
	echo json_encode(['success'=>false]);
	die();
}

$el            = new CIBlockElement;
$PROP          = [];
$PROP['PHONE'] = $_POST['phone'];

$arLoadProductArray = [
	"MODIFIED_BY"    => $USER->GetID(),
	"IBLOCK_ID"      => 8,
	"PROPERTY_VALUES"=> $PROP,
	"NAME"           => $_POST['name'],
	"ACTIVE"         => "N",
	"PREVIEW_TEXT"   => $_POST['message'],
];
$el->Add($arLoadProductArray);

echo json_encode(['success'=>true]);
