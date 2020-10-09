<?php
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
global $USER;
include 'class.php';
//проверка на то заполнены ли все поля
$needFields = ['email'];
foreach ($needFields as $fieldCode)
{
	if(empty($_POST[$fieldCode]))
	{
		echo json_encode(['success'=>false,'msg'=>'not all fields']);
		die();
	}
}
$userIt = CUser::GetList($by,$order,['EMAIL'=>$_POST['email']]);
$resultUser = $userIt->Fetch();
if(!$resultUser)
{
	echo json_encode(['success'=>false,'msg'=>'user not found']);
}
$password = Auth::generatePassword(8);
$fields = Array(
  "ACTIVE"            => "Y",
  "PASSWORD"          => $password,
  "CONFIRM_PASSWORD"  => $password,
  );
$USER->Update($resultUser['ID'], $fields);
$fields = ["EMAIL"=>$_POST['email'],'PASSWORD'=>$password];
$result  = CEvent::Send($_POST['m_event'], "s1", $fields);

if($result)
{
	echo json_encode(['success' => true,'msg' => 'Ваш новый пароль отпавлен']);
}
else
{
	echo json_encode(['success' => false,'msg' => 'Произошла ошибка повторите отправку вновь']);
}
