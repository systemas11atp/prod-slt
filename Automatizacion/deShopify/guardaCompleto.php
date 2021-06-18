<?php

require_once('/var/www/vhosts/'.$_SERVER['HTTP_HOST'].'/httpdocs/logs_locales.php');

$activeStore = explode("/",$_SERVER['REQUEST_URI'])[1];
$dbname = "prestashop_8";
$username = "prestashop_1";
$password = "L7&yoy33";

if($activeStore == 'testdemo'){
	$username = "ps_test";
	$dbname = "prestashop_test";
	$password = "En93#eq0";
}else if ($activeStore == 'devdemo'){
	$username = "ps_dev";
	$dbname = "prestashop_dev";
	$password = "En93#eq0";
}
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
$titulo = $decodedT[titulo];
$titulo = $decodedT[titulo];
$descripcion_corta = $decodedT[descripcion_corta];
$descripcion = $decodedT[descripcion];
$marca = $decodedT[marca];
$tags = $decodedT[tags];
$Option1 = $decodedT[Option1];
$Option2 = $decodedT[Option2];
$Option3 = $decodedT[Option3];
$Status = $decodedT[Status];
$skus = $decodedT[skus];
$imagenes = $decodedT[imagenes];

print_r("");
if (count($skus) > 1) {
//	print_r("skus : ".count($skus)."\n");
}
if (count($Option1[1]) == 1) {
	if($Option1[1][0] != "Default Title"){
		print_r("Option1 NAME: ".$Option1[0]."\n");
		print_r("Option1 VALU: ".$Option1[1][0]."\n");
		print_r("titulo : {$titulo}");
	}
}else{

}
exit();

function file_get_contents_curl($url) {
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);

	$data = curl_exec($ch);
	curl_close($ch);

	return $data;
}



?>
