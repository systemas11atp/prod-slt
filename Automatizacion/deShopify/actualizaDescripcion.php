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


$sql_total = "SELECT ds.id_descripcion, ds.descripcion_corta, ds.descripcion, pp.id_product, ds.sku, pp.reference FROM  prstshp_product pp 
INNER JOIN descripciones_shopify ds ON ds.sku = pp.reference  OR ds.sku = pp.supplier_reference  OR ds.sku = CONCAT(pp.reference,'_P')
WHERE ds.actualizado = 0 
ORDER BY `pp`.`reference` LIMIT 1";
$result = $conn->query($sql_total);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$id_descripcion = $row[id_descripcion];
		$id_product = $row[id_product];
		$reference = $row[sku];
		$descripcion = $row[descripcion];
		$descripcion_corta = $row[descripcion_corta];
		$descripcion = str_replace("'", "\'", $descripcion);
		$descripcion_corta = str_replace("'", "\'", $descripcion_corta);
		$sql_update = "UPDATE prstshp_product_lang SET description = '{$descripcion}', description_short = '{$descripcion_corta}' WHERE id_product = {$id_product}";
		if($conn->query($sql_update)){
			echo "success {$reference}";
			$sql_update_descripciones = "UPDATE descripciones_shopify SET actualizado = 1 WHERE sku = '{$reference}'";
			$conn->query($sql_update_descripciones);
			capuraLogs::nuevo_log("actualizaDescripcion (actualizado) ::: id_descripcion ::: {$id_descripcion}, id_product ::: {$id_product}, reference ::: {$reference}");
		}else{
			echo "error {$reference}";
		}
		

	}	
	
}
$sql_total = "SELECT ds.id_descripcion, ds.descripcion_corta, ds.descripcion, pp.id_product, pp.reference,  ds.sku  FROM  prstshp_product_attribute pp 
INNER JOIN descripciones_shopify ds ON ds.sku = pp.reference  OR ds.sku = pp.supplier_reference OR ds.sku = CONCAT(pp.reference,'_P')
WHERE ds.actualizado_att = 0 AND ds.actualizado = 0
ORDER BY `pp`.`reference` LIMIT 1";
$result = $conn->query($sql_total);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$id_descripcion = $row[id_descripcion];
		$id_product = $row[id_product];
		$reference = $row[sku];
		$descripcion = $row[descripcion];
		$descripcion = str_replace("'", "\'", $descripcion);
		$sql_update = "UPDATE prstshp_product_lang SET description = '{$descripcion}', description_short = '{$descripcion_corta}' WHERE id_product = {$id_product}";
		if($conn->query($sql_update)){
			echo "success {$reference}";
			$sql_update_descripciones = "UPDATE descripciones_shopify SET actualizado_att = 1 WHERE sku = '{$reference}'";
			$conn->query($sql_update_descripciones);
			capuraLogs::nuevo_log("actualizaDescripcion (actualizado_att) ::: id_descripcion ::: {$id_descripcion}, id_product ::: {$id_product}, reference ::: {$reference}");
		}else{
			echo "error {$reference}";
		}
	}	
}
?>
