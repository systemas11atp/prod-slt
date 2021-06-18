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

$contador=0;
$productos = array();

$sql =  "SELECT id_reference, sku FROM stock_sku WHERE actualizado = 0 AND error = 0 ORDER BY id_reference asc";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$productos[$contador] = array( "id_reference" => (int)$row[id_reference], 
			"sku" => "{$row[sku]}"
		);
		$contador++;
	}
}else{
	capuraLogs::nuevo_log("traerArticulos sql : {$sql}");
}
echo json_encode($productos);

?>