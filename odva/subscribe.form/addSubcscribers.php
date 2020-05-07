<?php
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
global $USER;
//елементы массива $_POST которые объязательно должны быть заполены
$needFields = ['format','email','rub'];
//проверака на то что бы все елементы массива $_POST были заполены
foreach ($needFields as $fieldCode)
{
	if(empty($_POST[$fieldCode]))
	{
		echo json_encode(['success'=>false,'msg'=>'not all fields']);
		die();
	}
}
//подключение модуля управляющего подписками
if(CModule::IncludeModule("subscribe"))
{
	//заполняем массив для подписки
	$arFields =
	[
        "USER_ID" => ($USER->IsAuthorized()? $USER->GetID():false),//id пользователя
        "FORMAT" => ($_POST['format'] <> "html"? "text":"html"),//тип подписки
        "EMAIL" => $_POST['email'],//email - пользователя
        "ACTIVE" => "Y",//подписка всегда активна
        "RUB_ID" => $_POST['rub_id']//id рассылки
    ];
    //создаю объект класса рассылки
    $subscr = new CSubscription;
    //сохраняю результат подписки
    $ID = $subscr->Add($arFields);
    //если подписка совершилась успешно то авторизую пользвователя как подписчика иначе вернуть ошибку
    if($ID>0)
    {
        CSubscription::Authorize($ID);
        echo json_encode(['success'=>true,'msg'=>'subscribed']);
    }
    else
    {
    	echo json_encode(['success'=>false,'msg'=>'unsubscribed']);
    }
}