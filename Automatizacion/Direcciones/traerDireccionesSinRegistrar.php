<?php 
require_once('/var/www/vhosts/lideart.net/httpdocs/logs_locales.php');
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

$id_address = $decodedT[id_address];

$activeStore = explode("/",$_SERVER['REQUEST_URI'])[1];
$dbname = "prestashop_3";
$username = "admin_lideart";
$password = "Avanceytec_2022";

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
$clientes = [];

$nsql = "SELECT pc.customerID  FROM prstshp_address pa  ";
$nsql .= "INNER JOIN prstshp_customer pc ON pc.id_customer = pa.id_customer ";
$nsql .= "WHERE pa.id_customer = (SELECT id_customer FROM prstshp_address WHERE id_address = {$id_address} AND addressID is null) ";
capuraLogs::nuevo_log("traerDireccionesSinRegistrar nsql : {$nsql}");
$result = $conn->query($nsql);
$cuantos = $result->num_rows;
capuraLogs::nuevo_log("traerDireccionesSinRegistrar cuantos : {$cuantos}");
if($cuantos == 1){
	while($row = $result->fetch_assoc()) {
		$customerID = $row[customerID];
	}

	$token = new Token();$tokenTemp = $token->getToken("LIN","prue"); 
	$token = $tokenTemp[0]->Token; 
	$curl = curl_init();
	$POSTFIELDS =  "{}";
	$url = "https://tes-ayt.sandbox.operations.dynamics.com/data/CustomerPostalAddresses?%24filter=CustomerAccountNumber%20eq%20'{$customerID}'";
	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_POSTFIELDS => $POSTFIELDS,
		CURLOPT_HTTPHEADER => array(
			"authorization: Bearer " . $token."",
			"content-type: application/json"
		),
	));

	$responseP = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
		echo "cURL Error #:" . $err;
	} else {
		$responseP= json_decode($responseP);
		capuraLogs::nuevo_log("traerDireccionesSinRegistrar count($responseP->value) : ".count($responseP->value));
		if(count($responseP->value) == 1){
			$addressID = $responseP->value[0]->AddressLocationId;
			$sql = "UPDATE prstshp_address SET addressID = '{$addressID}' WHERE id_address = {$id_address}";
			
			$conn->query($sql);
			$url = "https://prod-56.westus.logic.azure.com:443/workflows/f65aedeefaa54a9690ac5938504267b1/triggers/manual/paths/invoke?api-version=2016-06-01&sp=%2Ftriggers%2Fmanual%2Frun&sv=1.0&sig=I4W5b2CCqpE_G_c4zzZM69fQGJUvUHdoMXfnv4amy8g";
			$POSTFIELDS = "{\"id_address\":\"{$id_address}\", \"accion\": \"actualizar\"}";
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => $POSTFIELDS,
				CURLOPT_HTTPHEADER => array("content-type: application/json"),
			));

			$responseP = curl_exec($curl);
			$err = curl_error($curl);
		}
	}
	echo json_encode($clientes);
}else{
	$sql =  "SELECT pa.id_customer, pc.customerID, pa.id_address, pa.alias, pcl.name as pais, pcy.iso_code  as iso_pais, ps.name as estado, ps.iso_code  as iso_estado, pa.city as ciudad, pa.colony as colonia, pa.address1 as calle, pa.postcode as codigo_postal, cpg.id_codigo_postal, cpg.CountyId ";
	$sql .= "FROM prstshp_address pa  ";
	$sql .= "INNER JOIN codigos_postales_generales cpg ON cpg.asentamiento = pa.colony AND  cpg.codigo_postal = pa.postcode ";
	$sql .= "INNER JOIN prstshp_country_lang pcl ON pcl.id_country = pa.id_country AND pcl.id_lang = 2 ";
	$sql .= "INNER JOIN prstshp_country pcy ON pcy.id_country = pa.id_country ";
	$sql .= "INNER JOIN prstshp_state ps ON ps.id_state = pa.id_state   ";
	$sql .= "INNER JOIN prstshp_customer pc ON pc.id_customer = pa.id_customer AND pc.customerID IS NOT NULL ";
	$sql .= "WHERE pa.addressID IS NULL AND pa.id_address = {$id_address} ";

	$result = $conn->query($sql);
	capuraLogs::nuevo_log("traerDireccionesSinRegistrar sql : {$sql}");
	capuraLogs::nuevo_log("traerDireccionesSinRegistrar result->num_rows : {$result->num_rows}");
	if ($result->num_rows > 0) {
		$contador=0;
		while($row = $result->fetch_assoc()) {
			$id_customer = $row[id_customer];
			$customerID = $row[customerID];
			$id_address = $row[id_address];
			$alias = utf8_encode($row[alias]);
			$pais = utf8_encode($row[pais]);
			$iso_pais = $row[iso_pais];
			$estado = utf8_encode($row[estado]);
			$iso_estado = $row[iso_estado];
			$ciudad = utf8_encode($row[ciudad]);
			$colonia = utf8_encode($row[colonia]);
			$calle = utf8_encode($row[calle]);
			$codigo_postal = $row[codigo_postal];
			$id_codigo_postal = $row[id_codigo_postal];
			$CountyId = $row[CountyId];
		// 22A No. 2200\nCOLONIA SANTA RITA\nCHIHUAHUA,CHH,MEX\n31020
			$FormattedAddress = "{$calle}\n{$colonia}\n{$ciudad}\n{$iso_estado}\n{$iso_pais}\n{$codigo_postal}";
			$clientes[$contador] = array( 
				"id_customer" =>(int)$id_customer,
				"customerID" => $customerID,
				"id_address" => (int)$id_address,
				"alias" => $alias,
				"pais" => $pais,
				"iso_pais" => $iso_pais,
				"estado" => $estado,
				"iso_estado" => $iso_estado,
				"ciudad" => $ciudad,
				"colonia" => $colonia,
				"calle" => $calle,
				"codigo_postal" => $codigo_postal,
				"id_codigo_postal" => $id_codigo_postal,
				"CountyId" => $CountyId,
				"cuantos" => $cuantos,
				"FormattedAddress" => $FormattedAddress

			);
			$contador++;
		}
	}
	echo json_encode($clientes);
}

?>