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

$needFields = ['password','newpassword','confirm'];
foreach ($needFields as $fieldCode)
{
	if(empty($_POST[$fieldCode]))
	{
		echo json_encode(['success'=>false,'msg'=>'not all fields']);
		die();
	}
}

$rsUser = CUser::GetByID($USER->GetID());
$arUser = $rsUser->Fetch();
$profile = new Profile;

if(!$profile->isUserPassword($USER->GetID(),$_POST['password']))
{
	echo json_encode(['success'=>false,'msg'=>'not orirginal password']);
	exit();
}
$fields = Array(
  "ACTIVE"            => "Y",
  "PASSWORD"          => $_POST['newpassword'],
  "CONFIRM_PASSWORD"  => $_POST['confirm'],
  );
$USER->Update($USER->GetID(), $fields);
if(!$USER->LAST_ERROR)
{
	echo json_encode(['success' => true,'msg' => 'пароль успешно изменен']);
}
else
{
	echo json_encode(['success' => false,'msg' => $USER->LAST_ERROR]);
}
