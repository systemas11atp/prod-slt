<?php

require_once('/var/www/vhosts/'.$_SERVER['HTTP_HOST'].'/httpdocs/logs_locales.php');

$activeStore = explode("/",$_SERVER['REQUEST_URI'])[1];
$dbname = "shopify";
$username = "admin_shopify";
$password = "oof@deek1LUFT!prex";

$db_index = "prstshp_";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$dbname_prestashop = "prestashop_8";
$username_prestashop = "prestashop_1";
$password_prestashop = "L7&yoy33";
if($activeStore == 'testdemo'){
	$dbname_prestashop = "prestashop_test";
	$username_prestashop = "ps_test";
	$password_prestashop = "En93#eq0";
}else if ($activeStore == 'devdemo'){
	$dbname_prestashop = "prestashop_dev";
	$username_prestashop = "ps_dev";
	$password_prestashop = "En93#eq0";
}
$conn_prestashop = new mysqli($servername, $username_prestashop, $password_prestashop, $dbname_prestashop);
if ($conn_prestashop->connect_error) {
	die("Connection failed: " . $conn_prestashop->connect_error);
}
/* oof@deek1LUFT!prex */

$sql = "SELECT * FROM categorias WHERE actualizado = 0 LIMIT 400";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$id_categoria = $row[id_categoria];
		$sku = $row[sku];
		$producto = $row[producto];
		$marca = $row[marca];
		$tecnica = $row[tecnica];
		$arreglo = array();
		if($producto != ""){
			array_push($arreglo,10);
			array_push($arreglo,$producto);
		}
		if($marca != ""){
			array_push($arreglo,11);
			array_push($arreglo,$marca);
		}
		if($tecnica != ""){
			$tecnicas = explode(",", $tecnica);
			array_push($arreglo,12);
			foreach ($tecnicas as $tec) {
				array_push($arreglo,str_replace(" ", "", $tec));
			}

		}
		$sql_prestashop = "SELECT * FROM {$db_index}product WHERE reference = '{$sku}'";
		$result_prestashop = $conn_prestashop->query($sql_prestashop);
		if ($result_prestashop->num_rows > 0){
			while($row_prestashop = $result_prestashop->fetch_assoc()) {
				$id_product = $row_prestashop[id_product];
				$sql_category_prestashop = "SELECT * FROM {$db_index}category_product WHERE id_product = {$id_product} AND id_category IN (".implode(",", $arreglo).")";
				$result_prestashop_category = $conn_prestashop->query($sql_category_prestashop);
				if($result_prestashop_category->num_rows != count($arreglo)){
					print_r("- - - Se agregan categorias al  id_product ::: {$id_product} - {$result_prestashop_category->num_rows}, ".count($arreglo)." - {$sku}<br>");
					$sql_delete = "DELETE FROM {$db_index}category_product WHERE id_product = {$id_product}";
					$sql_update = "UPDATE {$db_index}product_shop SET id_category_default = {$arreglo[0]} WHERE id_product = {$id_product}";
					$conn_prestashop->query($sql_delete);
					$conn_prestashop->query($sql_update);
					print_r("---------- {$sql_delete}<br>");
					print_r("---------- {$sql_update}<br>");
					foreach ($arreglo as $nc) {
						$sql_insert = "INSERT INTO {$db_index}category_product VALUES ({$nc}, {$id_product}, 100);";
						$conn_prestashop->query($sql_insert);
						print_r("---------- {$sql_insert}<br>");
					}
				}else{
					print_r("- - - No se agregan categorias al  id_product ::: {$id_product} - {$result_prestashop_category->num_rows}, ".count($arreglo)." - {$sku}<br>");
				}
			}
		}

		$sql_prestashop = "SELECT * FROM {$db_index}product_attribute WHERE reference = '{$sku}'";
		$result_prestashop = $conn_prestashop->query($sql_prestashop);
		if ($result_prestashop->num_rows > 0){
			while($row_prestashop = $result_prestashop->fetch_assoc()) {
				$id_product = $row_prestashop[id_product];
				$sql_category_prestashop = "SELECT * FROM {$db_index}category_product WHERE id_product = {$id_product} AND id_category IN (".implode(",", $arreglo).")";
				$result_prestashop_category = $conn_prestashop->query($sql_category_prestashop);
				if($result_prestashop_category->num_rows != count($arreglo)){
					print_r("- - - Se agregan categorias al  id_product ::: {$id_product} - {$result_prestashop_category->num_rows}, ".count($arreglo)." - {$sku}<br>");
					$sql_delete = "DELETE FROM {$db_index}category_product WHERE id_product = {$id_product}";
					$sql_update = "UPDATE {$db_index}product_shop SET id_category_default = {$arreglo[0]} WHERE id_product = {$id_product}";
					$conn_prestashop->query($sql_delete);
					$conn_prestashop->query($sql_update);
					print_r("---------- {$sql_delete}<br>");
					print_r("---------- {$sql_update}<br>");
					foreach ($arreglo as $nc) {
						$sql_insert = "INSERT INTO {$db_index}category_product VALUES ({$nc}, {$id_product}, 100);";
						$conn_prestashop->query($sql_insert);
						print_r("---------- {$sql_insert}<br>");
					}
				}else{
					print_r("- - - No se agregan categorias al  id_product ::: {$id_product} - {$result_prestashop_category->num_rows}, ".count($arreglo)." - {$sku}<br>");
				}
			}
		}
		$sql_update = "UPDATE categorias SET actualizado = 1 WHERE id_categoria = {$id_categoria}";
		$conn->query($sql_update);
		print_r("---------- {$sql_update}<br>");

	}
}else{
	$id_categoria = "";
	$sql_category_prestashop = "SELECT * FROM {$db_index}category_product ORDER BY id_category, position, id_product";
	$result_prestashop = $conn_prestashop->query($sql_category_prestashop);
	$posicion = 0;
	if ($result_prestashop->num_rows > 0){
		while($row_prestashop = $result_prestashop->fetch_assoc()) {
			$id_c = $row_prestashop[id_category];
			$id_p = $row_prestashop[id_product];
			if($id_c != $id_category){
				$id_category = $id_c;
				$posicion = 0;
			}
			$sql_update ="UPDATE {$db_index}category_product SET position = {$posicion} WHERE id_product = {$id_p} AND id_category = {$id_c}";
			$posicion++;
			$conn_prestashop->query($sql_update);
			print_r("{$sql_update}<br>");

		}
	}

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


function imageResize($imageSrc,$imageWidth,$imageHeight, $newImageWidth, $newImageHeight) {
	
	
	$newImageLayer=imagecreatetruecolor($newImageWidth,$newImageHeight);
	imagecopyresampled($newImageLayer,$imageSrc,0,0,0,0,$newImageWidth,$newImageHeight,$imageWidth,$imageHeight);
	return $newImageLayer;
}


?>
