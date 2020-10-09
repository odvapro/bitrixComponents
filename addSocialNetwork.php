<?php
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
global $USER;
if(!$USER->IsAuthorized())
	LocalRedirect('/');

$s    = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
$user = json_decode($s, true);

if($user['network'] == 'facebook')
	$fields = ["UF_FACEBOOK" => $user['uid']];
if($user['network'] == 'vkontakte')
	$fields = ["UF_VK" => $user['uid']];
if($user['network'] == 'odnoklassniki')
	$fields = ["UF_OK" => $user['uid']];

$USER->Update($USER->GetID(), $fields);
LocalRedirect('/profile/');


?>