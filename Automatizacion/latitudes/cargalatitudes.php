<?php 
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>las latitudes ? </title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
	<script src="https://kit.fontawesome.com/a097d63864.js" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
	<script src="https://apis.google.com/js/api.js" type="text/javascript"></script>
	<style type="text/css" media="screen">
		p{
			text-align: center;
		}
		p a {
			color: #ffffff;
			text-decoration: none;
			background-color: transparent;
		}
		#elnegro h1{position: absolute;
			top: 50%;
			left: 50%;
			font-size: 5rem;
			transform: translate(-50%,-50%);
		}
		#elnegro{
			position: fixed;
			top: 0px;
			left: 0px;
			width: 100%;
			height: 100%;
			background: #000;
			opacity: 0.55;
			z-index: 3;
		}
		.activo{
			display: inline;
		}
		.inactivo{
			display: none;
		}
	</style>
</head>
<body class="bg-dark text-light">
	<div id="elnegro" class="inactivo">
		<h1>CÁMARA</h1>
	</div>
	<div class="container">
		<div class="my-5 row justify-content-between">
			<select id="estados" onchange="traerCiudades(this)" class="col-4">
				<option value="">Estados...</option>
			</select>		
			<select id="ciudades" onchange="traerColonias()" class="col-4">
				<option value="">ciudades...</option>
			</select>		
			<select id="codigos_postales" onchange="muestraLinks(this)" class="col-12 mt-3">
				<option value="">codigos postales...</option>
			</select>		
			<div id="links" class="col-12 my-5">

			</div>
		</div>
	</div>
	
	
	

	<script>
		aPerraLlave ="AIzaSyDq-2h6eKI8khBlUeHlqY7L0e6p4XKRAP8"
		loslinks = document.getElementById('links')
		estado_options = document.getElementById('estados')
		ciudad_options = document.getElementById('ciudades')
		elnegro = document.getElementById('elnegro')
		codigos_postales_options = document.getElementById('codigos_postales')
		aja = ""
		$(document).ready(function(){
			url = "https://silhouettemexico.com.mx/Automatizacion/latitudes/traerurls.php"
			var cargaurls = {
				"async": true,
				"crossDomain": true,
				"url": url,
				"method": "POST",
				"headers": {
					"content-type": "application/json"
				},
				"processData": false,
				"data": "{\"info\" : \"estados\"}"
			}
			$.ajax(cargaurls).done(function (response) {
				estados = JSON.parse(response)
				for (var i = 0; i < estados.length; i++) {
					estado_options.innerHTML +=  '<option value="'+estados[i]["estado"]+'">'+estados[i]["estado"]+'</option>'
				}
			});
		});

		function traerCiudades(valor){
			estado = valor.value
			url = "https://silhouettemexico.com.mx/Automatizacion/latitudes/traerurls.php"
			data = "{\"info\" : \"ciudades\", \"estado\" : \""+estado+"\"}"
			console.log("data = " + data )
			var cargaurls = {
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
			$.ajax(cargaurls).done(function (response) {
				ciudad = JSON.parse(response)
				ciudad_options.innerHTML = '<option value="">ciudades...</option>'
				for (var i = 0; i < ciudad.length; i++) {
					ciudad_options.innerHTML +=  '<option value="'+ciudad[i]["ciudad"]+'">'+ciudad[i]["ciudad"]+'</option>'
				}
			});
		}
		losDatos = {}
		function traerColonias(){
			estado = estado_options.value
			ciudad = ciudad_options.value
			data = "{\"info\" : \"codigos\", \"estado\" : \""+estado+"\", \"ciudad\" : \""+ciudad+"\"}"
			console.log("data = " + data )
			console.log("traerColonias = 0")
			elnegro.className ="activo"
			url = "https://silhouettemexico.com.mx/Automatizacion/latitudes/traerurls.php"
			var cargaurls = {
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
			$.ajax(cargaurls).done(function (response) {
				aja  = JSON.parse(response)
				losDatos = aja
				codigos_postales_options.innerHTML = '<option value="">codigos postales...</option>'
				for (var i = 0; i < aja.length; i++) {
					valor = JSON.stringify(aja[i]).split(":")[0].replace(/\"/g,"").replace(/\{/g,"")
					codigos_postales_options.innerHTML += '<option value="'+i+','+valor+',">'+valor+'</option>'
				}
				console.log("traerColonias = 1")
				elnegro.className ="inactivo"
			});
			console.log("traerColonias = 2")
		}
		function muestraLinks(valor){
			cp = valor.value.split(',')[1]
			indice = parseInt(valor.value.split(',')[0])
			loslinks.innerHTML =  ''
			for (var i = 0; i < losDatos[indice][cp].length; i++) {
				a1 = losDatos[indice][cp][i]["asentamiento"].toLocaleLowerCase();
				a2 = losDatos[indice][cp][i]["ciudad"].toLocaleLowerCase();
				a3 = losDatos[indice][cp][i]["estado"].toLocaleLowerCase();
				a4 = losDatos[indice][cp][i]["municipio"].toLocaleLowerCase();
				nName = a1+"+"+cp+"+"+a2+"+"+a3
				laurl = "https://www.google.com/maps/place/"+nName.replace(/ /g,'+')
				nombre = cp+" : ("+a4+") "+a1+", "+a2+", "+a3
				loslinks.innerHTML +=  '<p><a href="'+laurl+'" target="_blank" title="'+nombre+'">'+nombre+'</a></p>'
			}

		}

	</script>

</body>
</html>