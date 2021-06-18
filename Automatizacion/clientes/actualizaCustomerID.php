<?php

require_once '/var/www/vhosts/' . $_SERVER['HTTP_HOST'] . '/httpdocs/Automatizacion/database/dbSelectors.php';
require_once('/var/www/vhosts/'.$_SERVER['HTTP_HOST'].'/httpdocs/logs_locales.php');
$selectBDD = selectBDD();
$dbname    = $selectBDD[dbname];
$username  = $selectBDD[username];
$password  = $selectBDD[password];

$db_index = "prstshp_";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
$content = trim(file_get_contents("php://input"));
$decodedT = json_decode($content, true);
if(!is_array($decodedT)){
	throw new Exception('Received content contained invalid JSON!');
}

$customerID = $decodedT[customerID];
$id_customer = $decodedT[id_customer];

print_r ($customerID.'-'.$id_customer);

$sql_customerID_update = "UPDATE {$db_index}customer SET customerID = '{$customerID}' WHERE id_customer = {$id_customer}";
capuraLogs::nuevo_log("actualizaCustomerID sql_customerID_update : {$sql_customerID_update}");
if($conn->query($sql_customerID_update)){
	print_r("true");
}else{
	print_r("false");

}


?>
