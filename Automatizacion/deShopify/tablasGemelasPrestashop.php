<?php

require_once('/var/www/vhosts/'.$_SERVER['HTTP_HOST'].'/httpdocs/logs_locales.php');

$activeStore = explode("/",$_SERVER['REQUEST_URI'])[1];
$dbname = "shopify";
$username = "admin_shopify";
$password = "oof@deek1LUFT!prex";

$dbname2 = "prestashop_8";
$username2 = "prestashop_1";
$password2 = "L7&yoy33";
//if($activeStore == 'testdemo'){
//$username = "ps_test";
//$dbname = "prestashop_test";
//$password = "En93#eq0";
//}else if ($activeStore == 'devdemo'){
//$username = "ps_dev";
//$dbname = "prestashop_dev";
//$password = "En93#eq0";
//}
$db_index = "prstshp_";
$conn = new mysqli($servername, $username, $password, $dbname);
$conn2 = new mysqli($servername, $username2, $password2, $dbname2);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
if ($conn2->connect_error) {
	die("Connection failed: " . $conn2->connect_error);
}
/* oof@deek1LUFT!prex */

$sql = "SELECT * FROM imagene ORDER BY orden_agrupamiento asc, orden asc ";
$result = $conn->query($sql);
$nid = 4531;
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$orden_agrupamiento = $row[orden_agrupamiento];
		$orden = $row[orden];
		$update = "UPDATE imagene set id_image = {$nid} WHERE orden_agrupamiento = {$orden_agrupamiento} AND orden = {$orden}";
		$nid++;
		$conn->query($update);
		print_r("update ::: {$update}<br>");
		
	}
}
?>
