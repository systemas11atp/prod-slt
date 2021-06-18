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

/**
TRUNCATE TABLE prstshp_image;
TRUNCATE TABLE prstshp_image_lang;
TRUNCATE TABLE prstshp_image_shop;
TRUNCATE TABLE prstshp_product_attribute_image;
**/

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
$sql = "SELECT * FROM imagene WHERE sku LIKE '%-%' AND actualizado = 0 GROUP BY sku limit 150 ";
$result = $conn->query($sql);
$cuentaleperro = 0;
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$skus = explode(",", $row[sku]);
		foreach ($skus as $sku) {
			$sql2 = "SELECT * FROM {$db_index}product WHERE reference = '{$sku}'";
			$result2 = $conn2->query($sql2);
			if ($result2->num_rows > 0) {
				print_r("{$cuentaleperro}) sql2 :: {$sql2}<br>");
				while($row2 = $result2->fetch_assoc()) {
					$id_product = $row2[id_product];
					$sql3 = "SELECT * FROM imagene WHERE sku = '{$row[sku]}' ORDER BY orden_agrupamiento asc, orden asc";
					$result3 = $conn->query($sql3);
					if ($result3->num_rows > 0) {
						print_r("--- sql3 :: {$sql3}<br>");
						$orden = 1;
						while($row3 = $result3->fetch_assoc()) {
							$id_image = $row3[id_image];
							$cover = "NULL";
							if($orden == 1){
								$cover = 1;
							}
							$sql_image = "INSERT INTO prstshp_image VALUES ({$id_image},{$id_product},{$orden},{$cover})";
							$conn->query($sql_image);
							$sql_image_lang = "INSERT INTO prstshp_image_lang VALUES ({$id_image},1,''),({$id_image},2,''),({$id_image},3,'')";
							$conn->query($sql_image_lang);
							$sql_image_shop = "INSERT INTO prstshp_image_shop VALUES ({$id_product},{$id_image},1,{$cover})";
							$conn->query($sql_image_shop);
							$sql_update_imagene = "UPDATE imagene  SET actualizado = 1 WHERE id_image = {$id_image}";
							$conn->query($sql_update_imagene);
							//print_r("------------ sql_image :: {$sql_image}<br>");
							//print_r("------------ sql_image_lang :: {$sql_image_lang}<br>");
							//print_r("------------ sql_image_shop :: {$sql_image_shop}<br>");
							//print_r("------------ sql_update_imagene :: {$sql_update_imagene}<br>");
							$orden++;
						}
					}
				}
				print_r("<br>");
				$cuentaleperro++;
			}

			$sql2 = "SELECT * FROM prstshp_product_attribute WHERE reference = '{$sku}'";
			$result2 = $conn2->query($sql2);
			if ($result2->num_rows > 0) {
				print_r("{$cuentaleperro}) sql2 :: {$sql2}<br>");
				while($row2 = $result2->fetch_assoc()) {
					$id_product = $row2[id_product_attribute];
					$sql3 = "SELECT * FROM imagene WHERE sku = '{$row[sku]}' ORDER BY orden_agrupamiento asc, orden asc";
					$result3 = $conn->query($sql3);
					if ($result3->num_rows > 0) {
						print_r("--- sql3 :: {$sql3}<br>");
						$orden = 1;
						while($row3 = $result3->fetch_assoc()) {
							$id_image = $row3[id_image];
							$sql_product_attribute_image = "INSERT INTO prstshp_product_attribute_image VALUES ({$id_product},{$id_image})";
							$conn->query($sql_product_attribute_image);
							//print_r("------------ sql_product_attribute_image :: {$sql_product_attribute_image}<br>");
							$orden++;
						}
					}
				}
				print_r("<br>");
				$cuentaleperro++;
			}
		}
		$sql_update_imagene = "UPDATE imagene  SET actualizado = 1 WHERE sku = '{$row[sku]}'";
		print_r("------------ sql_update_imagene :: {$sql_update_imagene}<br>");
		$conn->query($sql_update_imagene);	

	}
}
?>
