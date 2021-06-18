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

if($activeStore == 'testdemo'){
	$username = "ps_test";
	$dbname = "prestashop_test";
	$password = "En93#eq0";
}else if ($activeStore == 'devdemo'){
	$username = "ps_dev";
	$dbname = "prestashop_dev";
	$password = "En93#eq0";
}

if($activeStore == 'teststore'){
	$username = "ps_user_test";
	$dbname = "prestashop_test";
}else if ($activeStore == 'devstore'){
	$username = "ps_user_dev";
	$dbname = "prestashop_dev";
}else if ($activeStore == 'storejorge'){
	$username = "ps_user_jg";
	$dbname = "prestashop_jg";
}else if ($activeStore == 'store'){
	$username = "ps_user";
	$dbname = "prestashop";
}
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$id_product = $decodedT[id_product];
$id_product_attribute = $decodedT[id_product_attribute];
$unitId = $decodedT[unitId];
if($id_product_attribute == 0){
	$sql = "UPDATE prstshp_product SET unitId = '{$unitId}' WHERE id_product = {$id_product}";
}else{
	$sql = "UPDATE prstshp_product_attribute SET unitId = '{$unitId}' WHERE id_product = {$id_product} AND id_product_attribute = {$id_product_attribute}";
}
if($conn->query($sql)){
	echo "true";
}else{
	capuraLogs::nuevo_log("capturaArticulosUnidadesDeVenta sql : {$sql}");
	echo "false";
}
?>
