<?php 
require_once '/var/www/vhosts/' . $_SERVER['HTTP_HOST'] . '/httpdocs/Automatizacion/database/dbSelectors.php';

$selectBDD = selectBDD();
$dbname    = $selectBDD[dbname];
$username  = $selectBDD[username];
$password  = $selectBDD[password];

$di = "prstshp_";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$fecha = date("Y-m-d");

$sql = "SELECT lp.id_lista, lp.sku, lp.unidad, lp.precio, ppa.reference, ppa.id_product, ppa.id_product_attribute ";
$sql .= "FROM lista_precios lp ";
$sql .= "INNER JOIN prstshp_product_attribute ppa ON ppa.reference = lp.sku ";
$sql .= "WHERE  lp.actualizado < '{$fecha}' ";
$sql .= "LIMIT 300 ";
$result = $conn->query($sql);
print_r("sql ::: {$sql}<br><br>");
print_r("result->num_rows ::: {$result->num_rows}<br><br>");
if($result->num_rows >  0){
	while($row = $result->fetch_assoc()) {
		$sql_insert = "";
		$id_lista = $row[id_lista];
		$sku = $row[sku];
		$unidad = $row[unidad];
		$precio = $row[precio];
		$reference = $row[reference];
		$id_product = $row[id_product];
		$id_product_attribute = $row[id_product_attribute];
		$sql_existencia = "SELECT id_specific_price FROM  {$di}specific_price WHERE id_product = {$id_product} AND id_product_attribute = {$id_product_attribute}";
		//print_r("sql_existencia :: {$sql_existencia}<br>");
		$result_existencia = $conn->query($sql_existencia);
		if($result_existencia->num_rows >  0){
			while($row_existencia = $result_existencia->fetch_assoc()) {
				$id_specific_price = $row_existencia['id_specific_price'];
				$sql_insert = "UPDATE {$di}specific_price SET price = {$precio} WHERE id_specific_price = {$id_specific_price}";
			}
		}else{
			$sql_insert = "INSERT INTO {$di}specific_price VALUES (null,0, 0, {$id_product}, 1, 0, 0, 0, 5, 0, {$id_product_attribute}, {$precio}, 1, '0.000000', 1, 'amount', '0000-00-00 00:00:00', '0000-00-00 00:00:00')";
		}
		if($conn->query($sql_insert)){
			//print_r("1 ::: sql_insert :: {$sql_insert}<br>");
			$sql_update= "UPDATE lista_precios SET actualizado = '{$fecha}' WHERE id_lista = {$id_lista}";
			if($conn->query($sql_update)){
				//print_r("1 ::: sql_update :: {$sql_update}<br>");
			}else{
				print_r("2 ::: sql_update :: {$sql_update}<br>");
			}
		}else{
			print_r("2 ::: sql_insert :: {$sql_insert}<br>");
		}
	}
}
?>