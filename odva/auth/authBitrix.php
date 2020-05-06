<?php
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
global $USER;
include 'class.php';
//проверка на то заполнены ли все поля
$needFields = ['email','password'];
foreach ($needFields as $fieldCode)
{
	if(empty($_POST[$fieldCode]))
	{
		echo json_encode(['success'=>false,'msg'=>'not all fields']);
		die();
	}
}
//если успещно
$arAuthResult = $USER->Login($_POST['email'], $_POST['password']);
if($arAuthResult['MESSAGE'])
{
	echo json_encode(['success'=>false,'msg'=>strip_tags($arAuthResult['MESSAGE'])]);
}
else
{
	//если неты
	echo json_encode(['success'=>true,'msg'=>'Вы успешно авторизовались']);
}