<?php
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
global $USER;
include 'class.php';
//проверка на то заполнены ли все поля
$needFields = ['email','password','confirm'];
foreach ($needFields as $fieldCode)
{
	if(empty($_POST[$fieldCode]))
	{
		echo json_encode(['success'=>false,'msg'=>'not all fields']);
		die();
	}
}
//попытка зарегистрироватся
$arResult = $USER->Register($_POST['email'], "", "", $_POST['password'], $_POST['confirm'], $_POST['email']);
//если успещно
if($arResult['MESSAGE'] == "Вы были успешно зарегистрированы.")
{
	echo json_encode(['success'=>true,'msg'=>strip_tags($arResult['MESSAGE'])]);
}
else
{
	//если не успещно
	echo json_encode(['success'=>false,'msg'=>strip_tags($arResult['MESSAGE'])]);
}
