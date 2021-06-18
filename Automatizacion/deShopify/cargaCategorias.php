<?php 
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Captura de productos  y equipos relacionados </title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
	<script src="https://kit.fontawesome.com/a097d63864.js" crossorigin="anonymous"></script>
	<style type="text/css" media="screen">
		.boton-accion{
			width: 1.75rem;
			background: #f8f9fa;
			height: 1.75rem;
			text-align: center;
			position: relative;
			border: 1px solid #a9a9a9;
			border-radius: 5px;
			color: #343a40;
			margin: 0;
			padding: 0;
			cursor: pointer;
			display: inline-block;
		}
		.boton-accion:hover{
			background: #343a40;
			border: 1px solid #f8f9fa;
			color: #f8f9fa;
		}
		.input-group:focus{
			outline: -webkit-focus-ring-color auto 0px;
			background: rgba(255,255,255,0.25);
		}
		.input-group{
			border-radius: 2px;
			border-bottom: 2px solid #f8f9fa;
			border-left: 0px;
			border-right: 0px;
			border-top: 0px;
			background: transparent;
			color:#FFF;
			display: inline;
		}
		.fondo-carga{
			position: absolute;
			top:0;
			left:0;
			bottom:0;
			right:0;
			background: rgba(0,0,0,0.75);
		}
		.fondo-carga div{
			position: fixed;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
		}

		.activo{
			z-index: 10;
			display: initial;
			cursor: wait;
		}
		.inactivo{
			z-index: -1;
			display: none;
		}

		.selectorTitulo:hover{
			color: #343a40;
		}
		.selectorTitulo{
			cursor: pointer;
			font-size: 1.75rem;
			border-radius: 0px 0px 100px 100px;
			border-left: 2px solid;
			border-right: 2px solid;
			border-bottom: 2px solid;
			font-weight: 600;
			background: #efefef;
			color: #343a40;
			border-color: #343a40;
		}
		.selectorTitulo.activo{
			background: #343a40;
			color: #efefef;
			border-color: #efefef;
		}
	</style>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
</head>
<body class="bg-dark text-light">
	<div class="fondo-carga inactivo" id="loader">
		<div>
			<h3>SUBIENDO ARCHIVO...</h3>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-10">
				<div class="row justify-content-center">
					<div class="col-6 text-center text-capitalize py-2 selectorTitulo activo">capturar</div> 
					<a href="editaRelacionados.php" class="col-6 text-center text-capitalize py-2 selectorTitulo">editar</a> 
				</div>
			</div>
			<div class="col-10 ">
				<div class="row my-5">
					<div class="col-12 text-center my-2">
						<h2>Captura de Productos y equipos relacionados</h2>
					</div>
					<div class="col-12 my-3 px-0 ">
						<div class="form-group">
							<label for="exampleFormControlFile1">Selecciona tu archivo XLSX</label>
							<input type="file" id="file-selector"  onchange="onChange(event)" class="form-control-file" style="cursor: pointer;" multiple accept=".xlsx">
						</div>
						<div class="row container-fluid py-3 mx-0" style="border:2px solid #FFF;" id="archivosCapturados">
							
						</div>
					</div>
					<div class="col-12">
						<div class="row" style="border-left: 1px solid #d5d5d5; border-right: 1px solid #d5d5d5;">
							<div class="col-3 px-0 mx-0" style="border-left: 1px solid #d5d5d5;"><h5 class="text-center">SKU</h5></div>
							<div class="col-3 px-0 mx-0" style="border-left: 1px solid #d5d5d5;"><h5 class="text-center">POR TIPO DE PRODUCTO</h5></div>
							<div class="col-3 px-0 mx-0" style="border-left: 1px solid #d5d5d5;"><h5 class="text-center">POR MARCA</h5></div>
							<div class="col-2 px-0 mx-0" style="border-left: 1px solid #d5d5d5;"><h5 class="text-center">POR TÉCNICA</h5></div>
							<div class="col-1 px-0 mx-0" style="border-left: 1px solid #d5d5d5;"><h5 class="text-center">QUEDA</h5></div>
						</div>
					</div>
					<div class="col-12" id="lineas">
						<div class="row" style="border-left: 1px solid #d5d5d5; border-right: 1px solid #d5d5d5;">
							<div class="col-3 px-1 mx-0 py-2" style="border-left: 1px solid #d5d5d5;"><input class="w-100 input-group" type="text" name="sku"></div>
							<div class="col-3 px-1 mx-0 py-2" style="border-left: 1px solid #d5d5d5;"><input class="w-100 input-group" type="text" name="producto"></div>
							<div class="col-3 px-1 mx-0 py-2" style="border-left: 1px solid #d5d5d5;"><input class="w-100 input-group" type="text" name="marca"></div>
							<div class="col-2 px-1 mx-0 py-2" style="border-left: 1px solid #d5d5d5;"><input class="w-100 input-group" type="text" name="tecnica"></div>
							<div class="col-1 px-1 mx-0 py-2" style="border-left: 1px solid #d5d5d5;"><input class="w-100 input-group" type="text" name="queda"></div>
						</div>
						<div onclick="guardarLineas()" class="boton-accion" title="Guardar Todo" style="position: absolute; top: 0.5rem; right: 0; transform: translate(110%, 0px); width: 100px; ">
							<i class="far fa-save" aria-hidden="true"></i>
						</div>
						<div onclick="nuevaLinea()"  id="lineaNueva" class="boton-accion" title="Agregar linea nueva" style="position: absolute; top: 3.0rem; right: 0; transform: translate(110%, 0px); width: 100px; ">
							<i class="fas fa-plus-circle" aria-hidden="true"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

	<script  type="text/javascript" charset="utf-8" async defer>
		elarreglo = []
		contador = 0
		function onChange(event) {
			for (var i = 0 ; i < event.target.files.length; i++) {
				equipo = false;
				var file = event.target.files[i];
				var reader = new FileReader();
				texto = ""
				actualSku = ""
				actualCarpeta = ""
				actualNombreImagen = ""
				actualUrl = ""
				//reader.readAsText(file, 'ISO-8859-1');
				//reader.readAsText(file, 'XLSX');
				var reader = new FileReader();

				reader.onload = function(e) {
					var data = e.target.result;
					var workbook = XLSX.read(data, {
						type: 'binary'
					});
					workbook.SheetNames.forEach(function(sheetName) {
						console.log("sheetName = " + sheetName )
						if(sheetName == "PRODUCTOS"){
							var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
							recorrele(XL_row_object)
							var json_object = JSON.stringify(XL_row_object);
						}

					})

				};

				reader.onerror = function(ex) {
					console.log(ex);
				};

				reader.readAsBinaryString(file);
			}
		}
		arregloFinal =[]
		cuentele = -1
		prev_handle = ""
		sku=[]
		src=[]
		position=[]
		alt=[]
		prestashop =  {"3m" :67, 
		"accesorios para planchas" :91, 
		"accesorios para plotters" :92, 
		"acribend" :71, 
		"adhesivos" :41, 
		"armour etch" :70, 
		"bisuteria" :25, 
		"costura" :117, 
		"costura" :23, 
		"cricut" :61, 
		"cubiertas, totes" :31, 
		"cursos" :54, 
		"decoración de papel" :84, 
		"decoración de rígidos" :86, 
		"decoración textil" :85, 
		"eclectic" :65, 
		"embossing" :22, 
		"engargolado" :58, 
		"engargolado" :90, 
		"foil quill" :46, 
		"foil reactivo impresión laser" :93, 
		"forever" :78, 
		"fotobotones" :48, 
		"glitter" :94, 
		"glue dots" :66, 
		"grabado cristal" :26, 
		"grabado" :118, 
		"graphtec" :76, 
		"gütermann" :69, 
		"heidi swapp" :72, 
		"herramientas de corte manual" :28, 
		"herramientas de troquelado" :57, 
		"herramientas para acrílico" :122,
		"herramientas para acrílico" :52, 
		"herramientas para depilación y aplicación de vinil" :95, 
		"hoja transportadora - tapetes" :33, 
		"hojas para laminar" :40, 
		"impresión 3d equipos y accesorios" :96, 
		"impresión 3d filamentos" :99, 
		"impresión 3d" :88, 
		"impresión de tarjetas" :121, 
		"impresión de tarjetas" :51, 
		"impresoras directo a prendas" :100, 
		"janome" :68, 
		"laminadoras" :37, 
		"listones" :42, 
		"metalix" :79, 
		"mochilas, morrales y gorras" :101, 
		"moldes de plástico" :102, 
		"moldes de plástico" :120, 
		"moxy" :74, 
		"navajas y accesorios para plotter de corte" :103, 
		"ojillos" :104, 
		"oracal" :63, 
		"otro" :80, 
		"papeles de transferencia" :27, 
		"papeles, cartulinas y más" :105, 
		"paquetes" :19, 
		"perforadoras" :32, 
		"pintura y pinceles" :107, 
		"planchas de calor" :35, 
		"plotter de corte" :20, 
		"plumas quill   " :108, 
		"plumas y marcadores" :53, 
		"por marca" :11, 
		"por tipo de producto" :10, 
		"por técnica" :12, 
		"productos aplicación foil reactivo laser" :109, 
		"pulseras para eventos" :45, 
		"repujado" :119, 
		"resina" :110, 
		"resina" :44, 
		"rotulación de paredes" :82, 
		"sawgrass" :62, 
		"sellos" :21, 
		"serigrafía casera" :24, 
		"silhouette" :59, 
		"siser" :64, 
		"sizzix" :81, 
		"software y digital" :36, 
		"sublimación blancos" :112, 
		"sublimación blancos" :116, 
		"sublimación de rígidos" :83, 
		"sublimación equipos" :113, 
		"sublimación impresora" :13, 
		"sublimación papel" :16, 
		"sublimación textiles" :87, 
		"tintas para sublimacion" :15, 
		"sublimación" :111, 
		"sublimarts" :75, 
		"tabletas para trazado y depilado" :114, 
		"tape decorativo" :115, 
		"troquelado" :89, 
		"troqueladoras" :56, 
		"troqueles" :55, 
		"vinil textil" :17, 
		"vinil y papel para rotulación" :18, 
		"wer memory keepers" :60, 
		"zap" :73, 
		"zebra" :77
	}
	function recorrele(elarreglo) {
		arregloFinal = elarreglo
		for (var i = 0; i < elarreglo.length ; i++) {
			if(!(typeof elarreglo[i]["ACTIVO"]  === 'undefined')){
				if(elarreglo[i]["POR TIPO DE PRODUCTO"]  != '0' && elarreglo[i]["POR MARCA"]  != '0'){
					table_sku 		= document.getElementsByName('sku')
					table_producto 	= document.getElementsByName('producto')
					table_marca 	= document.getElementsByName('marca')
					table_tecnica 	= document.getElementsByName('tecnica')
					table_queda 	= document.getElementsByName('queda')
					ls = table_sku.length-1

					sku = ""
					por_tipo_de_producto = ""
					por_marca = ""
					por_tecnica = ""

					coma = ""

					rotulacion = ""
					sublimacion = ""
					arte_en_papel = ""
					decoracion_textil = ""
					decoracion_rigidos = ""
					impresion_3d = ""
					grabado = ""
					impresion_en_tarjetas = ""
					promocionales = ""
					repujado = ""
					decoracion_e_acrilico = ""
					troquelado = ""
					engargolado = ""
					moldes = ""
					queda = true
					if(!(typeof elarreglo[i][" SKU "]  === 'undefined')){
						sku = elarreglo[i][" SKU "].toLowerCase()
					}
					if(!(typeof elarreglo[i]["POR TIPO DE PRODUCTO"]  === 'undefined')){
						por_tipo_de_producto = elarreglo[i]["POR TIPO DE PRODUCTO"].toLowerCase()
						if(!(typeof prestashop[por_tipo_de_producto]  === 'undefined')){
							por_tipo_de_producto = prestashop[por_tipo_de_producto]
						}else{
							queda = false
						}
					}
					if(!(typeof elarreglo[i]["POR MARCA"]  === 'undefined')){
						por_marca = elarreglo[i]["POR MARCA"].toLowerCase()
						if(!(typeof prestashop[por_marca]  === 'undefined')){
							por_marca = prestashop[por_marca]
						}else{
							por_marca = 80
							
						}
					}
					if(!(typeof elarreglo[i]["Grabado"]  === 'undefined')){
						grabado = 118
						por_tecnica += coma + grabado
						coma=", "
					}
					if(!(typeof elarreglo[i]["Repujado"]  === 'undefined')){
						repujado = 119
						por_tecnica += coma + repujado
						coma=", "
					}
					if(!(typeof elarreglo[i]["Moldes"]  === 'undefined')){
						moldes = 120
						por_tecnica += coma + moldes
						coma=", "
					}
					if(!(typeof elarreglo[i]["impresión en tarjetas"]  === 'undefined')){
						impresion_en_tarjetas = 121
						por_tecnica += coma + impresion_en_tarjetas
						coma=", "
					}
					if(!(typeof elarreglo[i]["Decoración e acrilico"]  === 'undefined')){
						decoracion_e_acrilico = 122
						por_tecnica += coma + decoracion_e_acrilico
						coma=", "
					}
					if(!(typeof elarreglo[i]["Rotulación"]  === 'undefined')){
						rotulacion = 82
						por_tecnica += coma + rotulacion
						coma=", "
					}
					if(!(typeof elarreglo[i]["Sublimación"]  === 'undefined')){
						sublimacion = 83
						por_tecnica += coma + sublimacion
						coma=", "
					}
					if(!(typeof elarreglo[i]["Arte en papel"]  === 'undefined')){
						arte_en_papel = 84
						por_tecnica += coma + arte_en_papel
						coma=", "
					}
					if(!(typeof elarreglo[i]["Decoración textil"]  === 'undefined')){
						decoracion_textil = 85
						por_tecnica += coma + decoracion_textil
						coma=", "
					}
					if(!(typeof elarreglo[i]["Decoración Rígidos"]  === 'undefined')){
						decoracion_rigidos = 86
						por_tecnica += coma + decoracion_rigidos
						coma=", "
					}
					if(!(typeof elarreglo[i]["Impresión 3D"]  === 'undefined')){
						impresion_3d = 88
						por_tecnica += coma + impresion_3d
						coma=", "
					}
					if(!(typeof elarreglo[i]["Troquelado"]  === 'undefined')){
						troquelado = 89
						por_tecnica += coma + troquelado
						coma=", "
					}
					if(!(typeof elarreglo[i]["Engargolado"]  === 'undefined')){
						engargolado = 90
						por_tecnica += coma + engargolado
						coma=", "
					}
					if(!(typeof elarreglo[i]["Promocionales"]  === 'undefined')){
						promocionales = 999
						coma=", "
					}
					table_sku[ls].value = sku
					table_producto[ls].value = por_tipo_de_producto
					table_marca[ls].value = por_marca
					table_tecnica[ls].value = por_tecnica
					table_queda[ls].value = "NO QUEDA "
					if(queda){
					table_queda[ls].value = "SI QUEDA"
						elJson = {
							"sku":sku, 
							"producto" : por_tipo_de_producto, 
							"marca" : por_marca,
							"tecnica" : por_tecnica
						}
						data = JSON.stringify(elJson)
						url = "https://silhouettemexico.com.mx/Automatizacion/deShopify/guardaCategorias.php"
						var recargaCarrito = {
							"async": true,
							"crossDomain": true,
							"url": url,
							"method": "POST",
							"headers": {
								"content-type": "application/json"
							},
							"processData": false,
							"data": data
						}
						$.ajax(recargaCarrito).done(function (response) {
							console.log("response = " + response )
						});
						
					}
					nuevaLinea()
				}
			}
		}

	}

	function nuevaLinea() {
		newDiv = document.createElement('div')
		newDiv.className="row"

		a = document.getElementById('lineas')
		currentDiv = a.children[a.childElementCount-3]

		newDiv.style.borderLeft = currentDiv.style.borderLeft
		newDiv.style.borderRight = currentDiv.style.borderRight
		newDiv.innerHTML = currentDiv.innerHTML
		LastDiv = a.children[a.childElementCount-2]
		document.getElementById('lineas').insertBefore(newDiv, LastDiv)
	}

	function utf8_decode(strData){
		const tmpArr = []
		let i = 0
		let c1 = 0
		let seqlen = 0
		strData += ''
		while (i < strData.length) {
			c1 = strData.charCodeAt(i) & 0xFF
			seqlen = 0
			if (c1 <= 0xBF) {
				c1 = (c1 & 0x7F)
				seqlen = 1
			} else if (c1 <= 0xDF) {
				c1 = (c1 & 0x1F)
				seqlen = 2
			} else if (c1 <= 0xEF) {
				c1 = (c1 & 0x0F)
				seqlen = 3
			} else {
				c1 = (c1 & 0x07)
				seqlen = 4
			}
			for (let ai = 1; ai < seqlen; ++ai) {
				c1 = ((c1 << 0x06) | (strData.charCodeAt(ai + i) & 0x3F))
			}
			if (seqlen === 4) {
				c1 -= 0x10000
				tmpArr.push(String.fromCharCode(0xD800 | ((c1 >> 10) & 0x3FF)))
				tmpArr.push(String.fromCharCode(0xDC00 | (c1 & 0x3FF)))
			} else {
				tmpArr.push(String.fromCharCode(c1))
			}
			i += seqlen
		}
		return tmpArr.join('')
	}

</script>	
</body>
</html>