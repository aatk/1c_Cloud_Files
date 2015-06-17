<?php
session_start();
define("PATH", $_SERVER['DOCUMENT_ROOT'].'/filebank/FILES/');
$autoload = 'autoload.php';
require_once ($autoload);

$SQL = new sql_connect();
$mysqli = $SQL->mysqli;
$Mygate = new Proxy($mysqli);

$METHOD = $_SERVER['REQUEST_METHOD'];
$param = $_REQUEST;
$res = array();
    
if ($METHOD == 'GET') {
    //Чтение данных выполняется GET-запросом.
    $res = $Mygate->GET($param);
} 
elseif ($METHOD == 'POST') {
    //Создание нового элемента данных выполняется POST-запросом.
    //Методы объектов встроенного языка выполняются POST-запросами. Например, проведение документа
    $res = $Mygate->POST($param);
}
elseif ($METHOD == 'PUT') {
    //Модификация существующих данных выполняется PATCH-запросом.
    $res = $Mygate->PUT($param);
}
elseif ($METHOD == 'PATCH') {
    //Модификация существующих данных выполняется PATCH-запросом.
    $res = $Mygate->PATCH($param);
}
elseif ($METHOD == 'DELETE') {
    //Для удаления данных используется DELETE-запрос
    $res = $Mygate->DELETE($param);
};
    
echo json_encode( $res );
?>