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
							<div class="col-9 px-0 mx-0" style="border-left: 1px solid #d5d5d5;"><h5 class="text-center">descripcion</h5></div>
						</div>
					</div>
					<div class="col-12" id="lineas">
						<div class="row" style="border-left: 1px solid #d5d5d5; border-right: 1px solid #d5d5d5;">
							<div class="col-3 px-1 mx-0 py-2" style="border-left: 1px solid #d5d5d5;"><input class="w-100 input-group" type="text" name="sku"></div>
							<div class="col-9 px-1 mx-0 py-2" style="border: 3px solid #d5d5d5; text-align: center;" name="descripciones"></div>
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
						var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
						recorrele(XL_row_object)
						var json_object = JSON.stringify(XL_row_object);

					})

				};

				reader.onerror = function(ex) {
					console.log(ex);
				};

				reader.readAsBinaryString(file);
			}
		}
		arregloFinal =[]
		cuentele = 0
		function recorrele(elarreglo) {
			titulo = ""
			descripcion_corta = ""
			descripcion = ""
			marca = ""
			tags = ""
			
			option1 = ""
			option2 = ""
			option3 = ""
			
			option1V = []
			option2V = []
			option3V = []
			
			Status = ""
			fueActivo = false
			skus=[]
			imagenes=[]
			actual = 0
			for (var i = 0; i < elarreglo.length; i++) {

				if(!(typeof  elarreglo[i]["Status"] === 'undefined')){
					if(elarreglo[i]["Status"].toLowerCase() == "active"){
						if(fueActivo){
							if(cuentele > 0)					{
								elJson = {
									"titulo" : utf8_decode(titulo),
									"descripcion_corta" : utf8_decode(descripcion_corta),
									"descripcion" : utf8_decode(descripcion),
									"marca" : utf8_decode(marca),
									"tags" : utf8_decode(tags),
									"Option1" : [option1,option1V],
									"Option2" : [option2,option2V],
									"Option3" : [option3,option3V],
									"Status" : Status,
									"skus" : skus,
									"imagenes" : imagenes
								}
								arregloFinal.push(elJson)
							}
						}
						fueActivo = true
						titulo 				= elarreglo[i]["Title"]
						descripcion_corta = ""
						descripcion = ""
						if(!(typeof  elarreglo[i]["Body (HTML)"] === 'undefined')){
							descripcion_corta	= elarreglo[i]["Body (HTML)"].replace(/\[\/TABS\]/g,'').split('\[TABS\]')[0]
							descripcion 		= elarreglo[i]["Body (HTML)"].replace(/\[\/TABS\]/g,'').split('\[TABS\]')[1]
						}
						marca 				= elarreglo[i]["Vendor"]
						tags 				= elarreglo[i]["Tags"]
						option1V = []
						option1 = ""
						if(!(typeof  elarreglo[i]["Option1 Value"] === 'undefined')){
							option1 = elarreglo[i]["Option1 Name"]
							option1V.push(utf8_decode(elarreglo[i]["Option1 Value"]))
						}
						option2V = []
						option2 = ""
						if(!(typeof  elarreglo[i]["Option2 Value"] === 'undefined')){
							option2 = elarreglo[i]["Option2 Name"]
							option2V.push(utf8_decode(elarreglo[i]["Option2 Value"]))
						}
						option3V = []
						option3 = ""
						if(!(typeof  elarreglo[i]["Option3 Value"] === 'undefined')){
							option3 = elarreglo[i]["Option3 Name"]
							option3V.push(utf8_decode(elarreglo[i]["Option3 Value"]))
						}
						Status 				= elarreglo[i]["Status"]
						elJsonSKU = {sku : elarreglo[i]["Variant SKU"], imagen : elarreglo[i]["Variant Image"]}
						skus =[]
						skus.push(elJsonSKU)
						imagenes =[]
						imagenes.push([elarreglo[i]["Image Src"],elarreglo[i]["Image Position"],elarreglo[i]["Variant Weight Unit"]])
						actual = cuentele
						cuentele++
					}else{
						fueActivo = false
					}
				}else{
					if(fueActivo){
						if(!(typeof  elarreglo[i]["Option1 Value"] === 'undefined')){
							option1V.push(utf8_decode(elarreglo[i]["Option1 Value"]))
						}
						if(!(typeof  elarreglo[i]["Option2 Value"] === 'undefined')){
							option2V.push(utf8_decode(elarreglo[i]["Option2 Value"]))
						}
						if(!(typeof  elarreglo[i]["Option3 Value"] === 'undefined')){
							option3V.push(utf8_decode(elarreglo[i]["Option3 Value"]))
						}
						if(!(typeof  elarreglo[i]["Variant SKU"] === 'undefined')){
							elJsonSKU = {sku : elarreglo[i]["Variant SKU"], imagen : elarreglo[i]["Variant Image"]}
							skus.push(elJsonSKU)
						}
						imagenes.push([elarreglo[i]["Image Src"],elarreglo[i]["Image Position"]])
					}
				}
				
			}
			if(fueActivo){

				elJson = {
					"titulo" : utf8_decode(titulo),
					"descripcion_corta" : utf8_decode(descripcion_corta),
					"descripcion" : utf8_decode(descripcion),
					"marca" : utf8_decode(marca),
					"tags" : utf8_decode(tags),
					"Option1" : [option1,option1V],
					"Option2" : [option2,option2V],
					"Option3" : [option3,option3V],
					"Status" : Status,
					"skus" : skus,
					"imagenes" : imagenes
				}
				arregloFinal.push(elJson)
			}
			elUltimoYNosVamos(arregloFinal)
		}
		function elUltimoYNosVamos(elarreglo) {
			for (var i = 0; i < elarreglo.length; i++) {
				data = JSON.stringify(elarreglo[i])
				url = "https://silhouettemexico.com.mx/Automatizacion/deShopify/guardaCompleto.php"
				var cargaProducto = {
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
				$.ajax(cargaProducto).done(function (response) {
					if(response != ""){
						console.log(response)
					}
				});
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

			newDiv.children[0].children[0].value = ""
			newDiv.children[1].innerHTML = ""
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