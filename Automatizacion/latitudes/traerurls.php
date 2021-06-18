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
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
$content = trim(file_get_contents("php://input"));
$decodedT = json_decode($content, true);
if(!is_array($decodedT)){
	throw new Exception('Received content contained invalid JSON!');
}

$info = $decodedT[info];

$listado = [];
$cuentele = 0;
if($info == "estados"){
	$sql_total = "SELECT estado FROM codigos_postales_generales GROUP BY estado ORDER BY estado asc";
	$result = $conn->query($sql_total);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$estado = utf8_encode($row[estado]);
			$eljson = array( 
				"estado"=> "{$estado}"
			);
			$listado[$cuentele] = $eljson;
			$cuentele++;
		}
	}
}else if($info == "ciudades"){
	$estado = utf8_decode($decodedT[estado]);
	$sql_total = "SELECT ciudad FROM codigos_postales_generales WHERE estado = '{$estado}' GROUP BY ciudad ORDER BY ciudad asc";
	$result = $conn->query($sql_total);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$ciudad = utf8_encode($row[ciudad]);
			$eljson = array( 
				"ciudad"=> "{$ciudad}"
			);
			$listado[$cuentele] = $eljson;
			$cuentele++;
		}
	}
}else{
	$estado = utf8_decode($decodedT[estado]);
	$ciudad = utf8_decode($decodedT[ciudad]);
	$sql_total = "SELECT * FROM codigos_postales_generales WHERE estado = '{$estado}' AND ciudad = '{$ciudad}' GROUP BY codigo_postal ORDER BY codigo_postal asc";
	$result = $conn->query($sql_total);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$cp = $row[codigo_postal];
			$sql = "SELECT * FROM codigos_postales_generales WHERE estado = '{$estado}' AND ciudad = '{$ciudad}' AND codigo_postal = {$cp} ORDER BY asentamiento asc";
			$result2 = $conn->query($sql);
			$eljson = [];
			$cuentaJson = 0;
			if ($result2->num_rows > 0) {
				while($row2 = $result2->fetch_assoc()) {
					$as =  utf8_encode($row2[asentamiento]);
					$as =  str_replace("á", "a", $as);
					$as =  str_replace("Á", "A", $as);
					$as =  str_replace("é", "e", $as);
					$as =  str_replace("É", "E", $as);
					$as =  str_replace("í", "i", $as);
					$as =  str_replace("Í", "I", $as);
					$as =  str_replace("ó", "o", $as);
					$as =  str_replace("Ó", "O", $as);
					$as =  str_replace("ú", "u", $as);
					$as =  str_replace("Ú", "U", $as);
					$as =  strtolower($as);
					//$as = urlencode($as);

					$mp =  utf8_encode($row2[municipio]);
					$mp =  str_replace("á", "a", $mp);
					$mp =  str_replace("Á", "A", $mp);
					$mp =  str_replace("é", "e", $mp);
					$mp =  str_replace("É", "E", $mp);
					$mp =  str_replace("í", "i", $mp);
					$mp =  str_replace("Í", "I", $mp);
					$mp =  str_replace("ó", "o", $mp);
					$mp =  str_replace("Ó", "O", $mp);
					$mp =  str_replace("ú", "u", $mp);
					$mp =  str_replace("Ú", "U", $mp);
					$mp =  strtolower($mp);
					//$mp = urlencode($mp);

					$ed =  utf8_encode($row2[estado]);
					$ed =  str_replace("á", "a", $ed);
					$ed =  str_replace("Á", "A", $ed);
					$ed =  str_replace("é", "e", $ed);
					$ed =  str_replace("É", "E", $ed);
					$ed =  str_replace("í", "i", $ed);
					$ed =  str_replace("Í", "I", $ed);
					$ed =  str_replace("ó", "o", $ed);
					$ed =  str_replace("Ó", "O", $ed);
					$ed =  str_replace("ú", "u", $ed);
					$ed =  str_replace("Ú", "U", $ed);
					$ed =  strtolower($ed);
					//$ed = urlencode($ed);

					$cd =  utf8_encode($row2[ciudad]);
					$cd =  str_replace("á", "a", $cd);
					$cd =  str_replace("Á", "A", $cd);
					$cd =  str_replace("é", "e", $cd);
					$cd =  str_replace("É", "E", $cd);
					$cd =  str_replace("í", "i", $cd);
					$cd =  str_replace("Í", "I", $cd);
					$cd =  str_replace("ó", "o", $cd);
					$cd =  str_replace("Ó", "O", $cd);
					$cd =  str_replace("ú", "u", $cd);
					$cd =  str_replace("Ú", "U", $cd);
					$cd =  strtolower($cd);
					//$cd = urlencode($cd);

					$eljson[$cuentaJson] = array( 
						"asentamiento"=> "{$as}",
						"municipio"=> "{$mp}",
						"estado"=> "{$ed}",
						"ciudad"=> "{$cd}"
					);
					$cuentaJson++;
				}
			}
			$njson = array( 
				"{$cp}"=> $eljson
			);
			$listado[$cuentele] = $njson;
			$cuentele++;
		}
	}
}
print_r(json_encode($listado));
?>
