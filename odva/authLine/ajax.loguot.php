<?php
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
global $USER;

//разлогинить пользователя
$USER->Logout();
//распечатать ответ в виде json объекта
echo json_encode(['success' =>true, ]);
?>