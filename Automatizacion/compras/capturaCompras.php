<?php 
require_once '/var/www/vhosts/' . $_SERVER['HTTP_HOST'] . '/httpdocs/Automatizacion/database/dbSelectors.php';
include(dirname(__FILE__).'/config/config.inc.php');
include_once(dirname(__FILE__).'/config/settings.inc.php');
include_once('/classes/Cookie.php');
include('/init.php');
require_once('/var/www/vhosts/'.$_SERVER['HTTP_HOST'].'/httpdocs/logs_locales.php');

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
$db_index = "prstshp_";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$id_order = $decodedT[id_order];
$orden_venta = $decodedT[orden_venta];

if($orden_venta != ""){
	$sql = "UPDATE {$db_index}orders SET orden_venta = '{$orden_venta}', cot = 1 WHERE id_order = {$id_order}";
	capuraLogs::nuevo_log("capturaCompras sql : {$sql}");
	if($conn->query($sql)){
		echo "true";
	}else{
		capuraLogs::nuevo_log("capturaCompras sql : {$sql}");
		echo "false";
	}
	/*
	*/
}else{
	echo "false";

}
?>
