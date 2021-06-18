<?php

require_once('/var/www/vhosts/'.$_SERVER['HTTP_HOST'].'/httpdocs/logs_locales.php');

$activeStore = explode("/",$_SERVER['REQUEST_URI'])[1];
$dbname = "shopify";
$username = "admin_shopify";
$password = "oof@deek1LUFT!prex";

//$dbname = "prestashop_8";
//$username = "prestashop_1";
//$password = "L7&yoy33";
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
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
/* oof@deek1LUFT!prex */
$elmayor = 3;
$sql = "SELECT * FROM imagene WHERE carpeta_act = 0 GROUP BY orden_agrupamiento ORDER BY orden_agrupamiento asc";
print_r("sql :: {$sql}<br><br>");
$result = $conn->query($sql);
$cuentale = 0;
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		if($row[carpeta_act] == 0){
			$cuentale++;
		}
		$elmayor = $row[orden_agrupamiento];
		if($cuentale == 1){
			break;
		}
	}
}

$sql = "SELECT * FROM imagene WHERE orden_agrupamiento <= {$elmayor} AND carpeta_act = 0 ORDER BY orden_agrupamiento asc ,orden asc";
print_r("sql :: {$sql}<br><br>");
$result = $conn->query($sql);
$cuentale = 0;
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$cuentale++;
		$imagen = $row[imagen];
		$orden_agrupamiento = $row[orden_agrupamiento];
		$id_image = (string)$row[id_image];
		$id_image_arr = str_split((string)$row[id_image]);
		$data = file_get_contents_curl("https://{$imagen}");
		//print_r("https://{$imagen}<br>");
		$directorio = "/var/www/vhosts/mundocricut.com.mx/httpdocs/img_test/p/";
		//print_r("directorio :: {$directorio}<br>");
		//print_r("id_image :: {$id_image}<br>");
		//print_r("id_image_arr :: ".count($id_image_arr)."<br>");
		foreach ($id_image_arr as $nid) {
			$directorio .= "/{$nid}";
			//print_r("directorio :: {$directorio}<br>");
			# code...
			if(!file_exists($directorio)){
				mkdir($directorio, 0777, true);
			}
		}

		$partesImagen = explode("/", $imagen);
		$partesImagen = explode("?", $partesImagen[count($partesImagen)-1]);
		$nombreImagen = $partesImagen[0];
		//print_r("nombreImagen :: {$nombreImagen}<br>");
		$partesImagen = explode(".", $nombreImagen);
		$extencion = $partesImagen[count($partesImagen)-1];
		$fp = "{$directorio}/{$id_image}.{$extencion}";
		//print_r("fp :: {$fp}<br>");

		if(!file_exists($fp)){
			file_put_contents($fp,$data);
		}
		/*
		*/
		////print_r("uploadedFile ::: {$uploadedFile}<br>");
		$uploadedFile = $fp; 
		$sourceProperties = getimagesize($fp);
		//print_r("sourceProperties ::: ".implode(", ", $sourceProperties)."<br>");
		$newFileName = $id_image;
		//print_r("newFileName ::: {$newFileName}<br>");
		$dirPath = $directorio."/";
		//print_r("dirPath ::: {$dirPath}<br>");
		
		// original 		=	1500*1500
		// cart_default 	=	125*125
		// home_default 	=	270*270
		// large_default 	=	600*600
		// medium_default 	=	270*270
		// small_default 	=	98*98
		$ext = $extencion;
		//print_r("ext ::: {$ext}<br>");
		$imageType = $sourceProperties[2];
		//print_r("imageType ::: {$imageType}<br>");
		switch ($imageType) {


			case IMAGETYPE_PNG:
			$imageSrc = imagecreatefrompng($uploadedFile); 
			$original = imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1],1500,1500);
			$cart_default = imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1],125,125);
			$home_default = imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1],270,270);
			$large_default = imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1],600,500);
			$medium_default = imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1],270,270);
			$small_default = imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1],98,98);
			imagepng($original,$dirPath. $newFileName. ".". $ext);
			imagepng($cart_default,$dirPath. $newFileName. "-cart_default.". $ext);
			imagepng($home_default,$dirPath. $newFileName. "-home_default.". $ext);
			imagepng($large_default,$dirPath. $newFileName. "-large_default.". $ext);
			imagepng($medium_default,$dirPath. $newFileName. "-medium_default.". $ext);
			imagepng($small_default,$dirPath. $newFileName. "-small_default.". $ext);
			break;           

			case IMAGETYPE_JPEG:
			$imageSrc = imagecreatefromjpeg($uploadedFile); 
			$original = imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1],1500,1500);
			$cart_default = imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1],125,125);
			$home_default = imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1],270,270);
			$large_default = imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1],600,500);
			$medium_default = imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1],270,270);
			$small_default = imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1],98,98);
			imagejpeg($original,$dirPath. $newFileName. ".". $ext);
			imagejpeg($cart_default,$dirPath. $newFileName. "-cart_default.". $ext);
			imagejpeg($home_default,$dirPath. $newFileName. "-home_default.". $ext);
			imagejpeg($large_default,$dirPath. $newFileName. "-large_default.". $ext);
			imagejpeg($medium_default,$dirPath. $newFileName. "-medium_default.". $ext);
			imagejpeg($small_default,$dirPath. $newFileName. "-small_default.". $ext);
			break;

			case IMAGETYPE_GIF:
			$imageSrc = imagecreatefromgif($uploadedFile); 
			$original = imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1],1500,1500);
			$cart_default = imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1],125,125);
			$home_default = imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1],270,270);
			$large_default = imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1],600,500);
			$medium_default = imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1],270,270);
			$small_default = imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1],98,98);
			imagegif($original,$dirPath. $newFileName. ".". $ext);
			imagegif($cart_default,$dirPath. $newFileName. "-cart_default.". $ext);
			imagegif($home_default,$dirPath. $newFileName. "-home_default.". $ext);
			imagegif($large_default,$dirPath. $newFileName. "-large_default.". $ext);
			imagegif($medium_default,$dirPath. $newFileName. "-medium_default.". $ext);
			imagegif($small_default,$dirPath. $newFileName. "-small_default.". $ext);
			break;

			default:
			echo "Invalid Image type.";
			exit;
			break;
		}


		/*
		move_uploaded_file($uploadedFile, $dirPath. $newFileName. ".". $ext);
		//print_r("Image Resize Successfully.<br>");
		*/
		$txtIndex = '<?php'.PHP_EOL;
		$txtIndex .= '/**'.PHP_EOL;
		$txtIndex .= '* 2007-2018 PrestaShop'.PHP_EOL;
		$txtIndex .= '*'.PHP_EOL;
		$txtIndex .= '* NOTICE OF LICENSE'.PHP_EOL;
		$txtIndex .= '*'.PHP_EOL;
		$txtIndex .= '* This source file is subject to the Open Software License (OSL 3.0)'.PHP_EOL;
		$txtIndex .= '* that is bundled with this package in the file LICENSE.txt.'.PHP_EOL;
		$txtIndex .= '* It is also available through the world-wide-web at this URL:'.PHP_EOL;
		$txtIndex .= '* https://opensource.org/licenses/OSL-3.0'.PHP_EOL;
		$txtIndex .= '* If you did not receive a copy of the license and are unable to'.PHP_EOL;
		$txtIndex .= '* obtain it through the world-wide-web, please send an email'.PHP_EOL;
		$txtIndex .= '* to license@prestashop.com so we can send you a copy immediately.'.PHP_EOL;
		$txtIndex .= '*'.PHP_EOL;
		$txtIndex .= '* DISCLAIMER'.PHP_EOL;
		$txtIndex .= '*'.PHP_EOL;
		$txtIndex .= '* Do not edit or add to this file if you wish to upgrade PrestaShop to newer'.PHP_EOL;
		$txtIndex .= '* versions in the future. If you wish to customize PrestaShop for your'.PHP_EOL;
		$txtIndex .= '* needs please refer to http://www.prestashop.com for more information.'.PHP_EOL;
		$txtIndex .= '*'.PHP_EOL;
		$txtIndex .= '* @author    PrestaShop SA <contact@prestashop.com>'.PHP_EOL;
		$txtIndex .= '* @copyright 2007-2018 PrestaShop SA'.PHP_EOL;
		$txtIndex .= '* @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)'.PHP_EOL;
		$txtIndex .= '* International Registered Trademark & Property of PrestaShop SA'.PHP_EOL;
		$txtIndex .= '*/'.PHP_EOL;
		$txtIndex .=' '.PHP_EOL;
		$txtIndex .= 'header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");'.PHP_EOL;
		$txtIndex .= 'header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");'.PHP_EOL;
		$txtIndex .=' '.PHP_EOL;
		$txtIndex .= 'header("Cache-Control: no-store, no-cache, must-revalidate");'.PHP_EOL;
		$txtIndex .= 'header("Cache-Control: post-check=0, pre-check=0", false);'.PHP_EOL;
		$txtIndex .= 'header("Pragma: no-cache");'.PHP_EOL;
		$txtIndex .=' '.PHP_EOL;
		$txtIndex .= 'header("Location: ../");'.PHP_EOL;
		$txtIndex .= 'exit;'.PHP_EOL;
		$txtIndex .= '?>';
		file_put_contents("{$dirPath}fileType","{$ext}");
		file_put_contents("{$dirPath}index.php","{$txtIndex}");
		//print_r(" -------------------------------------------------- <br>");
		//print_r(" -------------------------------------------------- <br><br>");
		$update ="UPDATE imagene SET carpeta_act = 1 WHERE id_image = {$row[id_image]}";
		print_r("{$cuentale}) update :: {$update}<br>");
		print_r(" -------------------------------------------------- <br><br><br>");
		$conn->query($update);

	}
}
/*
$imagenes = explode(",-,", $decodedT[img]);
$sku = $decodedT[sku];
$sql_total = "SELECT * FROM descripciones_shopify WHERE sku = '{$sku}'";
$result = $conn->query($sql_total);
if ($result->num_rows == 0) {
	
	$descripcion = $decodedT[descripcion];
	$descripcion_corta = $decodedT[descripcion_corta];
	if(!file_exists("/var/www/vhosts/mundocricut.com.mx/httpdocs/img_desc/{$sku}")){
		mkdir("/var/www/vhosts/mundocricut.com.mx/httpdocs/img_desc/{$sku}", 0777, true);
	}
	foreach ($imagenes as $imagen){
		$imagen = "{$imagen}";
		$partesImagen = explode("/", $imagen);
		$partesImagen = explode("?", $partesImagen[count($partesImagen)-1]);
		$nombreImagen = $partesImagen[0];
		$fullName = $partesImagen[0]."?".$partesImagen[1];

		/*************************************************
		$data = file_get_contents_curl("https://{$imagen}");

		$fp = "/var/www/vhosts/mundocricut.com.mx/httpdocs/img_desc/{$sku}/{$nombreImagen}";
		$nuevo_nombreImagen = "mundocricut.com.mx/img_desc/{$sku}/{$nombreImagen}";
		if(!file_exists($fp)){
			file_put_contents( $fp, $data );
		}
		$descripcion = str_replace($imagen, $nuevo_nombreImagen, $descripcion);
		$descripcion_corta = str_replace($imagen, $nuevo_nombreImagen, $descripcion_corta);
		/*************************************************


	}
	$descripcion = utf8_decode($descripcion);
	$descripcion = str_replace("'", "\'", $descripcion);
	$descripcion_corta = utf8_decode($descripcion_corta);
	$descripcion_corta = str_replace("'", "\'", $descripcion_corta);
	$sql_descripciones = "INSERT INTO `descripciones_shopify`(`sku`, `descripcion_corta`, `descripcion`) VALUES ('{$sku}','{$descripcion_corta}','{$descripcion}')";
	
	if($conn->query($sql_descripciones)){
		echo "success {$sku}";
	}else{
		echo "error {$sku}";
	}
}else{
	echo "repetido";
}
*/
function file_get_contents_curl($url) {
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);

	$data = curl_exec($ch);
	curl_close($ch);

	return $data;
}


function imageResize($imageSrc,$imageWidth,$imageHeight, $newImageWidth, $newImageHeight) {
	
	
	$newImageLayer=imagecreatetruecolor($newImageWidth,$newImageHeight);
	imagecopyresampled($newImageLayer,$imageSrc,0,0,0,0,$newImageWidth,$newImageHeight,$imageWidth,$imageHeight);
	return $newImageLayer;
}


?>
