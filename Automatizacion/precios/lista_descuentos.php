<?php 
require_once '/var/www/vhosts/' . $_SERVER['HTTP_HOST'] . '/httpdocs/Automatizacion/database/dbSelectors.php';

include '../token.php';


include(dirname(__FILE__).'/config/config.inc.php');
include_once(dirname(__FILE__).'/config/settings.inc.php');
include_once('/classes/Cookie.php');
include('/init.php');
if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0){
	throw new Exception('Request method must be POST!');
}
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

$content = trim(file_get_contents("php://input"));

$decodedT = json_decode($content, true);

if(!is_array($decodedT)){
	throw new Exception('Received content contained invalid JSON!');
}

$selectBDD = selectBDD();
$dbname    = $selectBDD[dbname];
$username  = $selectBDD[username];
$password  = $selectBDD[password];

$di = "prstshp_";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$nombre_lista = $decodedT['nombre_lista'];
$sku = $decodedT['sku'];
$unidad_precio = $decodedT['unidad_precio'];
$unidad = $decodedT['unidad'];
$precio = $decodedT['precio'];
$Moneda = $decodedT['Moneda'];
$recidayt = $decodedT['recidayt'];
$cantidad_desde = $decodedT['cantidad_desde'];
$cantidad_hasta = $decodedT['cantidad_hasta'];
$fecha_desde = $decodedT['fecha_desde'];
$fecha_hasta = $decodedT['fecha_hasta'];





$sql = "SELECT * FROM lista_precios WHERE recidayt = {$recidayt}";
$result = $conn->query($sql);
$nsql = "";
if($result->num_rows >  0){
	while($row = $result->fetch_assoc()) {
		$id_lista = $row[id_lista];
		$nsql = "UPDATE lista_precios SET nombre_lista = '{$nombre_lista}',sku = '{$sku}',unidad_precio = {$unidad_precio},unidad = '{$unidad}',precio = {$precio},Moneda = '{$Moneda}',recidayt = {$recidayt},cantidad_desde = {$cantidad_desde},cantidad_hasta = {$cantidad_hasta},fecha_desde = '{$fecha_desde}',fecha_hasta = '{$fecha_hasta}' WHERE  id_lista = {$id_lista}";
	}
}else{
	$nsql = "INSERT INTO lista_precios VALUES (null, '{$nombre_lista}','{$sku}',{$unidad_precio},'{$unidad}',{$precio},'{$Moneda}',{$recidayt},{$cantidad_desde},{$cantidad_hasta},'{$fecha_desde}','{$fecha_hasta}')";
}
if($conn->query($nsql)){
	print_r("1 ::: {$nsql}");
}else{
	print_r("2 ::: {$nsql}");
}

/*

SELECT lp.sku, lp.unidad, lp.precio, ppa.reference, ppa.id_product, ppa.id_product_attribute
FROM lista_precios lp
INNER JOIN prstshp_product_attribute ppa ON ppa.reference = lp.sku

SELECT lp.sku, lp.unidad, lp.precio, pp.reference, pp.id_product
FROM lista_precios lp
INNER JOIN prstshp_product pp ON pp.reference = CONCAT(lp.sku,'_P')


*/
?>


