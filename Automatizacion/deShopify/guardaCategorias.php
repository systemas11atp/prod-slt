<?php

require_once('/var/www/vhosts/'.$_SERVER['HTTP_HOST'].'/httpdocs/logs_locales.php');

$activeStore = explode("/",$_SERVER['REQUEST_URI'])[1];
$dbname = "shopify";
$username = "admin_shopify";
$password = "oof@deek1LUFT!prex";

//$dbname = "prestashop_8";
//$username = "prestashop_1";
//$password = "L7&yoy33";
//if($activeStore == 'testdemo'){
//$username = "ps_test";
//$dbname = "prestashop_test";
//$password = "En93#eq0";
//}else if ($activeStore == 'devdemo'){
//$username = "ps_dev";
//$dbname = "prestashop_dev";
//$password = "En93#eq0";
//}
$db_index = "prstshp_";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
/* oof@deek1LUFT!prex */
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
$content = trim(file_get_contents("php://input"));
$decodedT = json_decode($content, true);
if(!is_array($decodedT)){
	throw new Exception('Received content contained invalid JSON!');
}
$sku = $decodedT[sku];
$producto = $decodedT[producto];
$marca = $decodedT[marca];
$tecnica = $decodedT[tecnica];
$cuenta = 0;
$sql_total = "SELECT * FROM categorias WHERE sku = '{$sku}'";
$result = $conn->query($sql_total);
if ($result->num_rows == 0) {
	

	$sql = "INSERT INTO categorias VALUES (null, '{$sku}','{$producto}','{$marca}','{$tecnica}')";
	print_r("sql_total : : {$sql_total}<br>");
	print_r("result->num_rows : : {$result->num_rows}<br>");
	print_r("sql : : {$sql}<br>");
	$conn->query($sql);
}

function file_get_contents_curl($url) {
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);

	$data = curl_exec($ch);
	curl_close($ch);

	return $data;
}


function imageResize($imageSrc,$imageWidth,$imageHeight) {
	$newImageWidth =200;
	$newImageHeight =200;
	$newImageLayer=imagecreatetruecolor($newImageWidth,$newImageHeight);
	imagecopyresampled($newImageLayer,$imageSrc,0,0,0,0,$newImageWidth,$newImageHeight,$imageWidth,$imageHeight);
	return $newImageLayer;
}


?>
