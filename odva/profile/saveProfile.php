<?php
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
include 'class.php';
CModule::IncludeModule("catalog");
global $USER;
if(!$USER->IsAuthorized())
{
	echo json_encode(['success'=>false,'msg'=>'need auth']);
	die();
}

$needFields = ['name','lastname','birthday', 'phone', 'city', 'address'];
foreach ($needFields as $fieldCode)
{
	if(empty($_POST[$fieldCode]))
	{
		echo json_encode(['success'=>false,'msg'=>'not all fields']);
		die();
	}
}

$fields = [
	"NAME"            => $_POST['name'],
	"LAST_NAME"       => $_POST['lastname'],
	"PERSONAL_BIRTHDAY" => $_POST['birthday'],
	"ACTIVE"          => "Y",
	"PERSONAL_PHONE"  => $_POST['phone'],
	"PERSONAL_STREET" => $_POST['address'],
	"PERSONAL_CITY"   => $_POST['city'],
];
if(!$USER->Update($USER->GetID(), $fields))
{
	echo json_encode(['success'=>false,'msg'=>'something goes wrong']);
	die();
}

echo json_encode(['success' =>true, ]);
