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
$fields = [
  "ACTIVE"            => "Y",
  "PASSWORD"          => $password,
  "CONFIRM_PASSWORD"  => $password,
];
$USER->Update($resultUser['ID'], $fields);

$result = false;

if($_POST['send_type'] == 'phone' && !empty(SMS_API_KEY))
{
	$phone = $resultUser['PERSONAL_PHONE'];

	if(empty($phone))
		echo json_encode(['success' => false, 'msg' => 'У вас не указан номер телефона']);

	include 'sms.ru.php';

	$number = trim($phone);

	$smsru = new SMSRU(SMS_API_KEY);

	$data = new stdClass();
	$data->to = $number;
	$data->text = strval($password);
	$sms = $smsru->send_one($data);

	if ($sms->status == "OK")
		$result = true;
	else
	{
		echo json_encode(
			[
				'success' => false,
				'errorCode' => $sms->status_code,
				'msg' => $sms->status_text
			]
		);
		return;
	}
}
else
{
	$fields = ["EMAIL"=>$_POST['email'],'PASSWORD'=>$password];
	$result  = CEvent::Send($_POST['m_event'], "s1", $fields);
}

if($result)
{
	echo json_encode(['success' => true,'msg' => 'Ваш новый пароль отпавлен']);
}
else
{
	echo json_encode(['success' => false,'msg' => 'Произошла ошибка повторите отправку вновь']);
}
