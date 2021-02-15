<?php
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
global $USER;
// пытаемс найти пользователя с нужным uid
// если его нет, то регистрируем его
// выполняем вход
// редирект на главную
$s    = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
$user = json_decode($s, true);
if(!empty($user['error']))
	echo json_encode(['success'=>false,'msg'=>$user['error']]);

if(!empty($user['network']))
{
	$propertyName = "UF_".strtoupper($user['network']);
	$networkField = [$propertyName => $user['uid']];
}

$filter  = ["ACTIVE" => "Y"];
$filter  = array_merge($filter,$networkField);
$rsUsers = CUser::GetList($by, $order, $filter);
if($arUser = $rsUsers->Fetch())
{
	// auth
	$USER->Authorize($arUser['ID']);
	echo json_encode(['success'=>true,'msg'=>"Вы успешно авторизовались"]);
}
else
{
	// register
	$newUser = new CUser;
	$arFields = [
		"NAME"              => "{$user['first_name']} {$user['last_name']}",
		"LAST_NAME"         => "",
		"EMAIL"             => $user['email'],
		"LOGIN"             => $user['email'],
		"LID"               => SITE_ID,
		"ACTIVE"            => "Y",
		"GROUP_ID"          => [2],
		"PASSWORD"          => md5($user['email']),
		"CONFIRM_PASSWORD"  => md5($user['email'])
	];
	$arFields = array_merge($arFields,$networkField);
	$ID = $newUser->Add($arFields);
	if($ID)
	{
		$USER->Authorize($ID);
		echo json_encode(['success'=>true,'msg'=>"Вы успешно Зарегестрировались"]);
	}
	else
	{
		echo json_encode(['success'=>false,'msg'=>$newUser->LAST_ERROR]);
	}
}
