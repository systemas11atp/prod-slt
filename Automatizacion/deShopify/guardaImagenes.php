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
$imagenes = $decodedT[src];
$position = $decodedT[position];
$alt = $decodedT[alt];
$orden = $decodedT[orden];
//print_r("sku ::: ".implode(" ,-, ", $sku)."<br>");
//print_r("imagenes ::: ".implode(" ,-, ", $imagenes)."<br>");
//print_r("position ::: ".implode(" ,-, ", $position)."<br>");
//print_r("alt ::: ".implode(" ,-, ", $alt)."<br>");
$cuenta = 0;
$sql_total = "SELECT * FROM imagene WHERE sku = '".implode(",", $sku)."'";
$result = $conn->query($sql_total);
if ($result->num_rows == 0) {
	foreach ($imagenes as $imagen){
		$sql = "INSERT INTO imagene VALUES ({$orden}, '".implode(",", $sku)."', '{$imagen}',{$position[$cuenta]},'{$alt[$cuenta]}')";
		$conn->query($sql);
		$cuenta++;
	}
}
print_r("{$cuenta} registros\n");
/*
$imagenes = explode(",-,", $decodedT[img]);
$sku = $decodedT[sku];
$sql_total = "SELECT * FROM descripciones_shopify WHERE sku = '{$sku}'";
$result = $conn->query($sql_total);
if ($result->num_rows == 0) {
	
	$descripcion = $decodedT[descripcion];
	$descripcion_corta = $decodedT[descripcion_corta];
	if(!file_exists("/var/www/vhosts/mundocricut.com.mx/httpdocs/img_desc/{$sku}")){
		mkdir("/var/www/vhosts/mundocricut.com.mx/httpdocs/img_desc/{$sku}", 0777, true);
	}
	foreach ($imagenes as $imagen){
		$imagen = "{$imagen}";
		$partesImagen = explode("/", $imagen);
		$partesImagen = explode("?", $partesImagen[count($partesImagen)-1]);
		$nombreImagen = $partesImagen[0];
		$fullName = $partesImagen[0]."?".$partesImagen[1];

		/*************************************************
		$data = file_get_contents_curl("https://{$imagen}");

		$fp = "/var/www/vhosts/mundocricut.com.mx/httpdocs/img_desc/{$sku}/{$nombreImagen}";
		$nuevo_nombreImagen = "mundocricut.com.mx/img_desc/{$sku}/{$nombreImagen}";
		if(!file_exists($fp)){
			file_put_contents( $fp, $data );
		}
		$descripcion = str_replace($imagen, $nuevo_nombreImagen, $descripcion);
		$descripcion_corta = str_replace($imagen, $nuevo_nombreImagen, $descripcion_corta);
		/*************************************************


	}
	$descripcion = utf8_decode($descripcion);
	$descripcion = str_replace("'", "\'", $descripcion);
	$descripcion_corta = utf8_decode($descripcion_corta);
	$descripcion_corta = str_replace("'", "\'", $descripcion_corta);
	$sql_descripciones = "INSERT INTO `descripciones_shopify`(`sku`, `descripcion_corta`, `descripcion`) VALUES ('{$sku}','{$descripcion_corta}','{$descripcion}')";
	
	if($conn->query($sql_descripciones)){
		echo "success {$sku}";
	}else{
		echo "error {$sku}";
	}
}else{
	echo "repetido";
}
*/
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
