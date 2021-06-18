<?php 



require_once('/var/www/vhosts/'.$_SERVER['HTTP_HOST'].'/httpdocs/Automatizacion/database/dbSelectors.php');
require_once('/var/www/vhosts/'.$_SERVER['HTTP_HOST'].'/httpdocs/logs_locales.php');
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



$precio = $decodedT[precio];
$referencia = $decodedT[referencia];
$id_product = $decodedT[id_product];
$id_product_attribute = $decodedT[id_product_attribute];
if($id_product_attribute == 0){
	$sql = "UPDATE prstshp_product SET price = $precio WHERE id_product = {$id_product}";
	$sql_shop = "UPDATE prstshp_product_shop SET price = $precio WHERE id_product = {$id_product}";
}else{
	$sql_padre = "SELECT price FROM prstshp_product WHERE id_product = {$id_product}";
	$result = $conn->query($sql_padre);
	$precio_padre =0;
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$precio_padre =$row[price];
		}
	}
	$precio = $precio - $precio_padre;
	$sql = "UPDATE prstshp_product_attribute SET price = $precio WHERE id_product = {$id_product} AND id_product_attribute = {$id_product_attribute}";
	$sql_shop = "UPDATE prstshp_product_attribute_shop SET price = $precio WHERE id_product = {$id_product} AND id_product_attribute = {$id_product_attribute}";
}



capuraLogs::nuevo_log("capturaArticulos sql : {$sql}");
capuraLogs::nuevo_log("capturaArticulos sql_shop : {$sql_shop}");
if($conn->query($sql)){
	if($conn->query($sql_shop)){
		echo "true";
	}else{
		capuraLogs::nuevo_log("capturaArticulos sql : {$sql}");
		capuraLogs::nuevo_log("capturaArticulos sql_shop : {$sql_shop}");
		echo "false";
	}
}else{
	if($conn->query($sql_shop)){
		echo "true";
	}else{
		capuraLogs::nuevo_log("capturaArticulos sql : {$sql}");
		capuraLogs::nuevo_log("capturaArticulos sql_shop : {$sql_shop}");
		echo "false";
	}
}
?>