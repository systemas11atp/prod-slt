<?php 
require_once '/var/www/vhosts/' . $_SERVER['HTTP_HOST'] . '/httpdocs/Automatizacion/database/dbSelectors.php';
require_once('/var/www/vhosts/' . $_SERVER['HTTP_HOST'] . '/httpdocs/logs_locales.php');
$selectBDD = selectBDD();
$dbname    = $selectBDD[dbname];
$username  = $selectBDD[username];
$password  = $selectBDD[password];
$Completeurl = "https://tes-ayt.sandbox.operations.dynamics.com";
//$Completeurl = "https://ayt.operations.dynamics.com";
include '../token.php';

set_time_limit(0);
$token = new Token(); // Dynamic Token 
$tokenTemp = $token->getToken("LIN","prue"); // Dynamic Token 
$token = $tokenTemp[0]->Token; // Dynamic Token 

$db_index = "prstshp_";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$sql =  "SELECT po.id_order, po.orden_venta, pc.customerID ";
$sql .= "FROM {$db_index}orders po ";
$sql .= "INNER JOIN {$db_index}customer pc ON pc.id_customer = po.id_customer ";
$sql .= "WHERE po.orden_venta IS NOT NULL AND cot= 1 AND ov = 0"; 
//LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	$contador=0;
	while($row = $result->fetch_assoc()) {
		$nsql = "SELECT psa.id_stock_available, psa.id_product,psa.id_product_attribute, pcp.quantity as comprado, psa.physical_quantity as inventario, psa.chihuahua , psa.lideart as inventario_lid, IF(psa.id_product_attribute > 0, ppa.reference,pp.reference) as referencia ";
		$nsql .= "FROM prstshp_orders po  ";
		$nsql .= "INNER JOIN prstshp_cart_product pcp ON pcp.id_cart = po.id_cart  ";
		$nsql .= "INNER JOIN prstshp_stock_available psa ON pcp.id_product = psa.id_product AND pcp.id_product_attribute = psa.id_product_attribute  ";
		$nsql .= "INNER JOIN prstshp_product_attribute ppa ON ppa.id_product_attribute = pcp.id_product_attribute AND ppa.id_product = pcp.id_product ";
		$nsql .= "INNER JOIN prstshp_product pp ON pp.id_product = pcp.id_product ";
		$nsql .= "WHERE po.id_order = {$row['id_order']} AND pcp.quantity > psa.lideart ";
		$nresult = $conn->query($nsql);
		if($nresult->num_rows > 0){
			$productos = array();
			$cuentale = 0;
			$mensaje = "";
			$espacio = "";
			while($nrow = $nresult->fetch_assoc()) {
				$mensaje .= $espacio . "Producto {$nrow['referencia']}, solicitado {$nrow['comprado']}, en existencia {$nrow['inventario_lid']}, disponible en chihuahua {$nrow['chihuahua']}.";
				$espacio = "<br>";
				$cuentale ++;
			}
			$registro = array("paso" => false, "cotizacion" =>$row['orden_venta'],"orden" =>$row['id_order'], "mensaje" => $mensaje);
			$sql_update = "UPDATE {$db_index}orders  SET ov = 2 WHERE id_order = {$row[id_order]}";
			$conn->query($sql_update);
			echo json_encode($registro);
		}else{
			$POSTFIELDS = "{\n";
			$POSTFIELDS .= "\t\"quotationId\": \"{$row[orden_venta]}\",\n";
			$POSTFIELDS .= "\t\"_AccountNum\": \"{$row[customerID]}\",\n";
			$POSTFIELDS .= "\t\"dataAreaId\": \"lin\"\n";
			$POSTFIELDS .= "}";
			$myUrl = $Completeurl."/api/services/STF_INAX/STF_Cotizacion/SetSalesQuotationToSalesOrder";
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => $myUrl,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => $POSTFIELDS,
				CURLOPT_HTTPHEADER => array(
					"authorization: Bearer " . $token."",
					"content-type: application/json"),
			));
			$response = curl_exec($curl);
			$err = curl_error($curl);
			if($err){
				capuraLogs::nuevo_log("traerCotozaciones POSTFIELDS : {$POSTFIELDS}");
				capuraLogs::nuevo_log("traerCotozaciones myUrl : {$myUrl}");

			}else{
				$orventa=json_decode($response);
				$sql_update = "UPDATE {$db_index}orders  SET orden_venta = '{$orventa}', ov = 1 WHERE id_order = {$row[id_order]}";
				capuraLogs::nuevo_log("traerCotozaciones sql_update : {$sql_update}");
				$registro = array("paso" => true, "cotizacion" =>$orventa,"orden" =>$row['id_order'], "mensaje" => "");
				echo json_encode($registro);
				$conn->query($sql_update);
			}
			curl_close($curl);
		}
	}
}
//oxxo, transferencia, webpay y paypal
?>