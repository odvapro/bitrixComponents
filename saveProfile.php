<?php
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
include 'class.php';
global $USER;
if(!$USER->IsAuthorized())
{
	echo json_encode(['success'=>false,'msg'=>'need auth']);
	die();
}

$code = explode('.', $_POST['NEED_FIELDS']);
$hash = hash('sha256', $code[0] . Profile::SECRET);

if ($code[1] != $hash)
{
	echo json_encode(['success'=>false,'msg'=>'not all fields']);
	die();
}

$arParams = (array)json_decode(base64_decode($code[0]));


foreach ($arParams['NEED_FIELDS'] as $fieldCode)
{
	if(empty($_POST[$fieldCode]))
	{
		echo json_encode(['success'=>false,'msg'=>'not all fields']);
		die();
	}
}

if($arParams['LOGIN_IS_EMAIL'] == 'Y')
{
	$fields = [
		'LOGIN'  => $_POST['EMAIL'],
		"ACTIVE" => "Y",
	];
	$fields = array_merge($fields, $_POST);
}
else
{
	$fields = $_POST;
}

unset($_POST['NEED_FIELDS']);

if(!$USER->Update($USER->GetID(), $fields))
{
	echo json_encode(['success'=>false,'msg'=>'something goes wrong']);
	die();
}

echo json_encode(['success' =>true, ]);
