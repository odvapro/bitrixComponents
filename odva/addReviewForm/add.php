<?php
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");

if(empty($_POST['stars']) || empty($_POST['name']) || empty($_POST['message']))
{
	echo json_encode(['success'=>false]);
	die();
}

$el           = new CIBlockElement;
$PROP         = [];
$PROP['RAIT'] = $_POST['stars'];

$arLoadProductArray = [
	"MODIFIED_BY"    => $USER->GetID(),
	"IBLOCK_ID"      => 6,
	"PROPERTY_VALUES"=> $PROP,
	"NAME"           => $_POST['name'],
	"ACTIVE"         => "N",
	"PREVIEW_TEXT"   => $_POST['message'],
];
$el->Add($arLoadProductArray);

echo json_encode(['success'=>true]);
