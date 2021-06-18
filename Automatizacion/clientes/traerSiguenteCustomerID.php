<?php 
include '../token.php';
require_once '/var/www/vhosts/' . $_SERVER['HTTP_HOST'] . '/httpdocs/Automatizacion/database/dbSelectors.php';
set_time_limit(0);
$token = new Token(); 
$tokenTemp = $token->getToken("LIN","prue"); 
$token = $tokenTemp[0]->Token; 
/*
$POSTFIELDS =  "{\"_dataAreaId\": \"lin\"}";
$urlProd = "https://ayt.operations.dynamics.com";
$urlPrue = "https://tes-ayt.sandbox.operations.dynamics.com";
$api = "/api/services/STF_INAX/STF_Cotizacion/getNextCustAccountNum";
$fullUrl = "{$urlProd}{$api}";
$curl = curl_init();
curl_setopt_array($curl, array(
	CURLOPT_URL => $fullUrl,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => $POSTFIELDS,
	CURLOPT_HTTPHEADER => array(
		"authorization: Bearer {$token}",
		"content-type: application/json"
	),
));

$nCustomerID = curl_exec($curl);
$err = curl_error($curl);
if ($err) {
	capuraLogs::nuevo_log("traerClientesSinCustomerID token : {$token}");
	capuraLogs::nuevo_log("traerClientesSinCustomerID POSTFIELDS : {$POSTFIELDS}");
	capuraLogs::nuevo_log("traerClientesSinCustomerID fullUrl : {$fullUrl}");
	capuraLogs::nuevo_log("traerClientesSinCustomerID err : {$err}");
	echo "cURL Error #:" . $err;
} else {
	
	$nCustomerID = str_replace("\"","",$nCustomerID);
	$nCustomerID = str_replace("\n","",$nCustomerID);
	$nCustomerID = str_replace("\r","",$nCustomerID);
	$some = array("n_cid" => "{$nCustomerID}");
	echo json_encode($some);
}
*/
?>
