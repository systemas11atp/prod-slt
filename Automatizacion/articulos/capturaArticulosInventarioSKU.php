<?php 
require_once '/var/www/vhosts/' . $_SERVER['HTTP_HOST'] . '/httpdocs/Automatizacion/database/dbSelectors.php';

include(dirname(__FILE__).'/config/config.inc.php');
include_once(dirname(__FILE__).'/config/settings.inc.php');
include_once('/classes/Cookie.php');
include('/init.php');
if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0){
	throw new Exception('Request method must be POST!');
}
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

$content = trim(file_get_contents("php://input"));

$decodedT = json_decode($content, true);

if(!is_array($decodedT)){
	throw new Exception('Received content contained invalid JSON!');
}

$selectBDD = selectBDD();
$dbname    = $selectBDD[dbname];
$username  = $selectBDD[username];
$password  = $selectBDD[password];

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$id_reference = $decodedT[id_reference];
$stock = $decodedT[stock];
$sql = "UPDATE stock_sku  SET OnHandQuantity = {$stock}, actualizado = 1 WHERE id_reference = {$id_reference}";
if($stock < 0){
	$sql = "UPDATE stock_sku  SET OnHandQuantity = -1, actualizado = 0, error = 1 WHERE id_reference = {$id_reference}";
}

if($conn->query($sql)){
	print_r("sql :: {$sql}<br>");
	echo "true";
}else{
	capuraLogs::nuevo_log("capturaArticulosInventario sql : {$sql}");
	echo "false";
}
?>
