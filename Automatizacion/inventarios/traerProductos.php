<?php 
require_once '/var/www/vhosts/' . $_SERVER['HTTP_HOST'] . '/httpdocs/Automatizacion/database/dbSelectors.php';

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

$sql =  "SELECT psa.id_stock_available, psa.reserved_quantity, pp.id_product, pp.id_product_attribute, pp.reference FROM prstshp_stock_available psa
INNER JOIN prstshp_product_attribute pp on psa.id_product = pp.id_product and psa.id_product_attribute = pp.id_product_attribute 
WHERE pp.reference != '' AND pp.reference LIKE '%-%'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$reference = str_replace(":",'', $row[reference]);
		$reference = str_replace("_P",'', $reference);
		$productos[$contador] = array( 
			"id_stock_available" => (int)$row[id_stock_available], 
			"id_product" => (int)$row[id_product], 
			"id_product_attribute" => (int)$row[id_product_attribute], 
			"reserved_quantity" => (int)$row[reserved_quantity], 
			"reference" =>"{$reference}",
		);
		$contador++;
		
	}
}else{
	capuraLogs::nuevo_log("traerArticulos sql : {$sql}");
}
echo json_encode($productos);

?>