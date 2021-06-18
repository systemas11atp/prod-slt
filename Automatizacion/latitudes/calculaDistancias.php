<?php 
require_once '/var/www/vhosts/' . $_SERVER['HTTP_HOST'] . '/httpdocs/Automatizacion/database/dbSelectors.php';

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
$content = trim(file_get_contents("php://input"));
$decodedT = json_decode($content, true);
if(!is_array($decodedT)){
	throw new Exception('Received content contained invalid JSON!');
}
$lat0 = $decodedT[lat0];
$lng0 = $decodedT[lng0];
$lat1 = $decodedT[lat1];
$lng1 = $decodedT[lng1];

$rlat0 = deg2rad($lat0);
$rlng0 = deg2rad($lng0);
$rlat1 = deg2rad($lat1);
$rlng1 = deg2rad($lng1);


$latDelta = $rlat1 - $rlat0;
$lonDelta = $rlng1 - $rlng0;


$distance = (6371 * acos( cos($rlat0) * cos($rlat1) * cos($lonDelta) + sin($rlat0) * sin($rlat1) ) );
$distance = number_format($distance,4,'.',',');
print_r("<h1 style='text-align: center'>La distancia entre los puntos es : {$distance} Kms</h1>");


?>