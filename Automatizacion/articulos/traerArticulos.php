<?php 
require_once '/var/www/vhosts/'. $_SERVER['HTTP_HOST'].'/httpdocs/Automatizacion/database/dbSelectors.php';

$selectBDD = selectBDD();
$dbname    = $selectBDD[dbname];
$username  = $selectBDD[username];
$password  = $selectBDD[password];

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

//print_r("{$sql}<br>");
$contador=0;
$productos = array();

$sql =  "SELECT id_product, 0 as id_product_attribute, reference, unitId FROM prstshp_product WHERE reference != '' AND reference LIKE '%-%'  AND (unitId IS NOT NULL AND unitId != '')";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		//print_r("id_product :: {$row[id_product]}<br>");
		$reference = str_replace(":",'', $row[reference]);
		$reference = str_replace("_P",'', $reference);
		$productos[$contador] = array( "id_product" => (int)$row[id_product], 
			"id_product_attribute" => (int)$row[id_product_attribute], 
			"reference" =>"{$reference}",
			"unitId" =>"{$row[unitId]}"
		);
		$contador++;
		$sql_att =  "SELECT id_product, id_product_attribute, reference, unitId  FROM prstshp_product_attribute WHERE id_product = {$row[id_product]} AND (unitId IS NOT NULL AND unitId != '')";
		$result_att = $conn->query($sql_att);
		if ($result_att->num_rows > 0) {
			while($row_att = $result_att->fetch_assoc()) {
				$reference = str_replace(":",'', $row[reference]);
				$reference = str_replace("_P",'', $reference);
				$productos[$contador] = array( "id_product" => (int)$row_att[id_product], 
					"id_product_attribute" => (int)$row_att[id_product_attribute], 
					"reference" =>"{$reference}",
					"unitId" =>"{$row_att[unitId]}"
				);
				$contador++;
			}
		}else{
			capuraLogs::nuevo_log("traerArticulos sql : {$sql}");
		}
		//print_r(" ------------------------------------------------------------ <br>");
	}
}else{
	capuraLogs::nuevo_log("traerArticulos sql : {$sql}");
}
echo json_encode($productos);
/*
$sql =  "SELECT id_product, id_product_attribute, reference, unitId  FROM prstshp_product_attribute WHERE reference != '' AND reference LIKE '%-%' AND unitId IS NOT NULL";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$id_order = $row[id_order];
		$id_customer = $row[id_customer];
		$id_cart = $row[id_cart];
		$productos[$contador] = array( "id_product" => (int)$row[id_product], 
			"id_product_attribute" => (int)$row[id_product_attribute], 
			"reference" =>"{$row[reference]}",
			"unitId" =>"{$row[unitId]}"
		);
		$contador++;
	}
}else{
	capuraLogs::nuevo_log("traerArticulos sql : {$sql}");
}

/*
ALTER TABLE `prstshp_product_attribute` ADD `unitId` VARCHAR(20) NULL AFTER `available_date`;
ALTER TABLE `prstshp_product` ADD `unitId` VARCHAR(20) NULL AFTER `state`;
*/
?>