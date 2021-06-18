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
INNER JOIN descripciones_shopify ds ON ds.sku = pp.reference  
WHERE ds.actualizado = 0 
ORDER BY `pp`.`reference` LIMIT 1";
$result = $conn->query($sql_total);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$id_descripcion = $row[id_descripcion];
		$id_product = $row[id_product];
		$reference = $row[reference];
		$descripcion = $row[descripcion];
		$descripcion_corta = $row[descripcion_corta];
		$descripcion = str_replace("'", "\'", $descripcion);
		$descripcion_corta = str_replace("'", "\'", $descripcion_corta);
		$sql_update = "UPDATE prstshp_product_lang SET description = '{$descripcion}', description_short = '{$descripcion_corta}' WHERE id_product = {$id_product}";
		print_r("descripcion_corta :: {$descripcion_corta} <br>------------------------------<br>");
		print_r("descripcion :: {$descripcion} <br>------------------------------<br>");
		print_r("id_descripcion :: {$id_descripcion}		<br>------------------------------<br>");
		capuraLogs::nuevo_log("actualizaDescripcion ::: id_descripcion :: {$id_descripcion}");
		print_r("id_product :: {$id_product}<br>");
		capuraLogs::nuevo_log("actualizaDescripcion ::: id_product :: {$id_product}");
		print_r("reference :: {$reference}<br>");
		capuraLogs::nuevo_log("actualizaDescripcion ::: reference :: {$reference}");
		//print_r("descripcion :: {$descripcion}<br>");
		//print_r("sql_update :: {$sql_update}<br>");
		if($conn->query($sql_update)){
			echo "success {$reference}";
			$sql_update_descripciones = "UPDATE descripciones_shopify SET actualizado = 1 WHERE sku = '{$reference}'";
			print_r("<br>{$sql_update_descripciones}");
			$conn->query($sql_update_descripciones);
		}else{
			echo "error {$reference}";
		}
		print_r("<br>-----------------------------------<br><br>");

	}	
	
}
$sql_total = "SELECT ds.id_descripcion, ds.descripcion_corta, ds.descripcion, pp.id_product, pp.reference FROM  prstshp_product_attribute pp 
INNER JOIN descripciones_shopify ds ON ds.sku = pp.reference  
WHERE ds.actualizado_att = 0 
ORDER BY `pp`.`reference` LIMIT 1";
$result = $conn->query($sql_total);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$id_descripcion = $row[id_descripcion];
		$id_product = $row[id_product];
		$reference = $row[reference];
		$descripcion = $row[descripcion];
		$descripcion = str_replace("'", "\'", $descripcion);
		$sql_update = "UPDATE prstshp_product_lang SET description = '{$descripcion}', description_short = '{$descripcion_corta}' WHERE id_product = {$id_product}";
		print_r("descripcion_corta :: {$descripcion_corta} <br>------------------------------<br>");
		print_r("descripcion :: {$descripcion} <br>------------------------------<br>");
		print_r("id_descripcion :: {$id_descripcion}		<br>------------------------------<br>");
		capuraLogs::nuevo_log("actualizaDescripcion ::: id_descripcion :: {$id_descripcion}");
		print_r("id_product :: {$id_product}<br>");
		capuraLogs::nuevo_log("actualizaDescripcion ::: id_product :: {$id_product}");
		print_r("reference :: {$reference}<br>");
		capuraLogs::nuevo_log("actualizaDescripcion ::: reference :: {$reference}");
		//print_r("descripcion :: {$descripcion}<br>");
		//print_r("sql_update :: {$sql_update}<br>");
		if($conn->query($sql_update)){
			echo "success {$reference}";
		print_r("<br>{$sql_update_descripciones}");
			$sql_update_descripciones = "UPDATE descripciones_shopify SET actualizado_att = 1 WHERE sku = '{$reference}'";
			$conn->query($sql_update_descripciones);
		}else{
			echo "error {$reference}";
		}
		print_r("<br>-----------------------------------<br><br>");

	}	
	
}
?>
