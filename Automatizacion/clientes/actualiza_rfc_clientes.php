<?php
set_time_limit(6000000);
require_once('/var/www/vhosts/'.$_SERVER['HTTP_HOST'].'/httpdocs/logs_locales.php');
$paginas = 5;
$limite = 250; //max 250
$cuentaTodo = 0;
$dbname = "shopify";
$username = "admin_shopify";
$password = "oof@deek1LUFT!prex";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
$sql_total = "SELECT cs.*, ad.address2, ad.company FROM customers cs ";
$sql_total .= "INNER JOIN address ad ON ad.id_customer_shopify = cs.id_shopify ";
$sql_total .= "WHERE ad.con_rfc = 1 AND cs.con_rfc = 0";
$result = $conn->query($sql_total);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$address2 = $row[address2];
		$company = $row[company];
		$id_shopify = $row[id_shopify];
		$pos = strpos(strtolower($address2), "rfc");
		if ($pos === false) {
		}else{
			$separados = explode("RFC:", $address2);
			if(sizeof($separados) == 1){
				$separados = explode("RFC", $address2);
			}
		}
		$pos = strpos(strtolower($company), "rfc");
		if ($pos === false) {
		}else{
			$separados = explode("RFC:", $company);
			if(sizeof($separados) == 1){
				$separados = explode("RFC", $company);
			}
		}
		$rfc = str_replace(" ", "", $separados[1]);
		$sql_rfc_update = "UPDATE customers SET con_rfc = 1, RFC = '{$rfc}' WHERE id_shopify = {$id_shopify}";
		capuraLogs::nuevo_log("actualiza_rfc_clientes sql_rfc_update : {$sql_rfc_update}");
		$conn->query($sql_rfc_update);

	}
}
$sql_total = "SELECT * FROM customers WHERE con_rfc = 1 AND RFC IS NULL";
$result = $conn->query($sql_total);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$note = $row[note];
		$id_customer = $row[id_customer];
		$id_shopify = $row[id_shopify];
		$note = str_replace("RFC:", "RFC", $note);
		$tags = $row[tags];
		$tags = str_replace("RFC:", "RFC", $tags);
		$pos = strpos(strtolower($note), "rfc");
		if ($pos === false) {
		}else{
			$separados = explode("RFC:", $note);
			if(sizeof($separados) == 1){
				$separados = explode("RFC ", $note);
			}
			$nota ="Viene en note {$note}";
		}
		$pos = strpos(strtolower($tags), "rfc");
		if ($pos === false) {
		}else{
			$separados = explode("RFC:", $tags);
			if(sizeof($separados) == 1){
				$separados = explode("RFC ", $tags);
			}
			$nota ="Viene en tags {$tags}";
		}
		$separados[1] = utf8_encode($separados[1]);
		$elRFC = preg_split('/\s+/', $separados[1])[0];
		$regex = '/^(([ÑA-Z|ña-z|&]{3}|[A-Z|a-z]{4})\d{2}((0[1-9]|1[012])(0[1-9]|1\d|2[0-8])|(0[13456789]|1[012])(29|30)|(0[13578]|1[02])31)(\w{2})([A|a|0-9]{1}))$|^(([ÑA-Z|ña-z|&]{3}|[A-Z|a-z]{4})([02468][048]|[13579][26])0229)(\w{2})([A|a|0-9]{1})$/';
		if(preg_match($regex, $elRFC) && $elRFC != "XAXX010101000"){
			$sql_rfc_update = "UPDATE customers SET RFC = '{$elRFC}' WHERE id_shopify = {$id_shopify}";
		}else{
			$sql_rfc_update = "UPDATE customers SET con_rfc = 0 WHERE id_shopify = {$id_shopify}";
		}
		capuraLogs::nuevo_log("actualiza_rfc_clientes sql_rfc_update : {$sql_rfc_update}");
		$conn->query($sql_rfc_update);
	}

}
?>
