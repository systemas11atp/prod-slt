
<?php 
require_once '/var/www/vhosts/' . $_SERVER['HTTP_HOST'] . '/httpdocs/Automatizacion/database/dbSelectors.php';

$selectBDD = selectBDD();
$dbname    = $selectBDD[dbname];
$username  = $selectBDD[username];
$password  = $selectBDD[password];

$db_index = "prstshp_";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
$sql =  "SELECT po.id_order, po.reference as referencia_compra, po.total_shipping_tax_excl, poc.weight,  po.id_cart, po.id_customer, pc.customerID, pc.firstname, pc.lastname, po.payment, pca.id_reference as carrier ";
$sql .= "FROM {$db_index}orders po ";
$sql .= "INNER JOIN {$db_index}customer pc ON pc.id_customer = po.id_customer ";
$sql .= "INNER JOIN {$db_index}carrier pca ON pca.id_carrier = po.id_carrier ";
$sql .= "INNER JOIN {$db_index}order_carrier poc ON poc.id_order = po.id_order ";
$sql .= "WHERE po.cot = 0 AND pc.customerID IS NOT NULL AND pc.customerID != ''  AND po.valid = 1";
//print_r("sql : {$sql}<br>");
$compras = array();
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	$contador = 0;
	while($row = $result->fetch_assoc()) {
		$id_order = $row[id_order];
		$id_customer = $row[id_customer];
		$id_cart = $row[id_cart];
		$sqlProductos =  "SELECT pcp.id_cart, pcp.id_product, pcp.id_product_attribute,  pcp.quantity, ";
		
		$sqlProductos .= "(CASE WHEN pcp.id_product_attribute = 0 THEN pp.reference ELSE  ";
		$sqlProductos .= "    (CASE WHEN ppa.reference = '' THEN pp.reference ELSE ppa.reference END)  ";
		$sqlProductos .= "END) AS referencia,  ";
		
		$sqlProductos .= "(CASE WHEN pcp.id_product_attribute = 0 THEN pp.price ELSE  ";
		$sqlProductos .= "    (CASE WHEN ppa.price = 0 THEN pp.price ELSE ppa.price END)  ";
		$sqlProductos .= "END) AS precio, ";
		
		$sqlProductos .= "(CASE WHEN (psp.from < NOW() AND psp.to > NOW()) OR (psp.from = '0000-00-00 00:00:00' AND psp.to = '0000-00-00 00:00:00') THEN  ";
		$sqlProductos .= "    (CASE WHEN pcp.quantity >= psp.from_quantity THEN  ";
		$sqlProductos .= "        (CASE WHEN {$id_customer} = psp.id_customer OR psp.id_customer = 0 THEN 1 ELSE 0 END) ";
		$sqlProductos .= "    ELSE 0 END) ";
		$sqlProductos .= "ELSE 0 END) as conDescuento,  ";
		
		$sqlProductos .= "psp.reduction,  ";
		$sqlProductos .= "psp.reduction_tax,  ";
		$sqlProductos .= "psp.reduction_type  ";
		
		$sqlProductos .= "FROM {$db_index}cart_product pcp  ";
		$sqlProductos .= "LEFT JOIN {$db_index}product pp ON pp.id_product = pcp.id_product  ";
		$sqlProductos .= "LEFT JOIN {$db_index}product_attribute  ppa ON ppa.id_product_attribute = pcp.id_product_attribute AND ppa.id_product = pcp.id_product  ";
		$sqlProductos .= "LEFT JOIN {$db_index}specific_price psp ON pcp.id_product = psp.id_product AND (pcp.id_product_attribute = psp.id_product_attribute OR psp.id_product_attribute = 0) ";
		$sqlProductos .= "WHERE pcp.id_cart  = {$id_cart}";
		//print_r("sqlProductos ::: {$sqlProductos}<br><br><br>");
		$resultProductos = $conn->query($sqlProductos);
		if ($resultProductos->num_rows > 0) {
			$contadorProductos=0;
			$Lineascompras = array();
			while($productos = $resultProductos->fetch_assoc()) {
				$descuento = 0;
				if($productos[reduction_type] == "percentage"){
					$descuento = (float)($productos[reduction] * 100);
				}else if($productos[reduction_type] == "amount"){
					if($productos[reduction_tax] == 1){
						$descuento = $productos[reduction]/(($productos[precio] *1.16)/100);
					}else{
						$descuento = $productos[reduction]/($productos[precio]/100);
					}
					$descuento = number_format($descuento, 2);
				}
				$Lineascompras[$contadorProductos] = array( "id_cart" => (int)$productos[id_cart],
					"id_product" =>  (int)$productos[id_product],
					"id_product_attribute" => (int)$productos[id_product_attribute],
					"referencia" => "{$productos[referencia]}",
					"precio" => (float)$productos[precio],
					"cantidad" => (int)$productos[quantity],
					"total" => (float)($productos[precio]*$productos[quantity]),
					"conDescuento" =>  (int)$productos[conDescuento],
					"descuento" => (float)$descuento,
					"reduction_tax" => (int)$productos[reduction_tax],
					"reduction_type" => "{$productos[reduction_type]}"
				);
				$contadorProductos++;
			}
			if(count($Lineascompras) > 0){
				//CustomerPaymentMethodName
				//Conekta oxxo_cash
				//Conekta spei
				/*
				1 Lideart
				2 Estafeta Estándar
				7 Estafeta Express
				8 PaqueteExpress
				*/

				$payment = $row[payment];
				$carrier = $row[carrier];
				$weight = (float)$row[weight];
				if($payment == "Conekta Prestashop" ){
					$payment = "03";
				}else{
					$payment = "28";
				}
				$codigoPaq="";
				if($carrier == 1){
					$carrier = "PASA";
				}else if($carrier == 2){
					$carrier = "PAQUETERIA";
					$codigoPaq="9999-0850";
				}else if($carrier == 7){
					$carrier = "PAQUETERIA";
					$codigoPaq="9999-0550";
				}else if($carrier == 8){
					$carrier = "PAQUETERIA";
					$codigoPaq="9999-1300";
					if($weight > 30 ){
						$codigoPaq="9999-1400";
					}
				}
				$firstname = utf8_encode($row[firstname]);
				$lastname = utf8_encode($row[lastname]);
				$compras[$contador] = array( "id_customer" =>(int)$row[id_customer],  
					"customerID" =>"{$row[customerID]}",
					"ref_compra" =>"{$row[referencia_compra]}", 
					"firstname" =>"{$firstname}", 
					"lastname" =>"{$lastname}", 
					"payment" => "{$payment}", 
					"id_order" => (int)$id_order, 
					"id_cart" => (int)$row[id_cart],
					"carrier" => "{$carrier}", 
					"shipping" => (float)$row[total_shipping_tax_excl],
					"codigoPaq" => "{$codigoPaq}",
					"carrier_weight" => (float)$row[weight],
					"Lineascompras" => $Lineascompras
				);
				$contador++;
			}
		}
	}
}
echo json_encode($compras);

?>