<?php
/*
* 	Administración de todas las granjas que se usaran en las plantaciones de las granjas
* 	@author 		Edwin Olivera
* 	@date 			2024-02-15
*/
session_start();
if (!isset($_SESSION['cod_usuario'])) {
	echo "<script>window.location.href = '" . $_SESSION['úrl_inicio'] . "';</script>"; // Usando JS para redireccionar
	// header('Location: index.php'); // Método anterior (No funcional)
}
/*CONEXION CON BASE DE DATOS*/
try {
	include_once("../../libs/db_classes/db_mysql_conn.php");
	include_once("../../libs/db_classes/db_farms.php");
	//code...
} catch (\Throwable $th) {
	//throw $th;
	echo $th;
}
/*INSTANCIAMIENTOS*/
$tituloPagina = "Bloques";


$DB_FARM = new db_farms();

$codigosEstados = $_POST['codigosEstados'];
$codigosGranjas = $_POST['codigosGranjas'];
$codigosCampos = $_POST['codigosCampos'];
$codigoBloquesLibres = $_POST['codigoBloquesLibres'];
$codigosBloques = $_POST['codigosBloques'];



if (!isset($_POST['codigosEstados'])) {
	$codigosEstados = 0;
}
if (!isset($_POST['codigosGranjas'])) {
	$codigosGranjas = 0;
}

if (!isset($_POST['codigosCampos'])) {
	$codigosCampos = 0;
}

if (!isset($_POST['codigoBloquesLibres']) || $codigoBloquesLibres == "") {
	$codigoBloquesLibres = 0;
}
if (!isset($_POST['codigosBloques'])) {
	$codigosBloques = 0;
}



$BLOQUES = $DB_FARM->farm_listado_de_bloques_filtrados_por_granjas_y_campos($codigosBloques, $codigoBloquesLibres);
$json_resultado = json_encode($BLOQUES, JSON_PRETTY_PRINT);
foreach ($BLOQUES as &$bloque) {

	$datosSemilla =	$DB_FARM->farm_semilla_asociada_a_bloque($bloque["cod_bloque"]);
	if (count($datosSemilla)) {
		if ($datosSemilla[0]["cantidad_semillas"] == 1) {
			$cantidadSemillasImplementadasEnUnificacion = $DB_FARM->farm_cantidad_semillas_en_unificacion_bloque($datosSemilla[0]["cod_unificacion"]);
			if ($cantidadSemillasImplementadasEnUnificacion[0]["cantidad_semilla_implementadas"] == 1) {

				$datosSemillaUnica = $DB_FARM->farm_datos_semilla_unica_asociada_bloque_implementado($bloque["cod_bloque_implementado"]);
				// $bloque["nombre_semilla"] = $datosSemillaUnica[0]["datos_semilla"]."..".$bloque["cod_bloque_implementado"] . ' . '.$datosSemilla[0]["cod_unificacion"];
				if (!empty($datosSemillaUnica[0]["datos_semilla"])) {
					$bloque["nombre_semilla"] = $datosSemillaUnica[0]["datos_semilla"];
				} else {
					$bloque["nombre_semilla"] = "Seed data lost";
				}
				// $bloque["nombre_semilla"] = $datosSemillaUnica[0]["datos_semilla"]."..".$bloque["cod_bloque_implementado"] . ' . '.$datosSemilla[0]["cod_unificacion"];

			} else {
				// echo $cantidadSemillasImplementadasEnUnificacion[0]["cantidad_semilla_implementadas"];
				// echo '<br>';
				// echo $datosSemilla[0]["cod_unificacion"];

				// $bloque["nombre_semilla"] = $datosSemilla[0]["cod_unificacion"] . "Multiple seeds_" . $cantidadSemillasImplementadasEnUnificacion[0]["cantidad_semilla_implementadas"]." , ".$bloque["cod_bloque"];

				$bloque["nombre_semilla"] = "Multiple seeds";
			}
		} else {
			// $bloque["nombre_semilla"] = "..Multiple seeds - ".$datosSemilla[0]["cantidad_semillas"].", ".$bloque["cod_bloque"];

			$bloque["nombre_semilla"] = "Multiple seeds.";
		}
	} else {
		// echo $bloque["cod_bloque"];
		$bloque["nombre_semilla"] = "No seeds";
	}
}

// Imprimir el JSON resultante
// echo $json_resultado;
// $BLOQUES = [];
//$BLOQUE = $DB_FARM->farm_obtener_plantaciones_granja($cod_rotations);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title><?php echo $tituloPagina; ?></title>
	<style>
		.boton_desactivado,
		.btn_agrega_fila_desactivada {
			background-color: #595959;
			color: #D3D3D3;
		}

		.input_cantidad_acres,
		.input_porcentaje_acres {
			/* text-align: right; */
			max-width: 150px;
		}

		.input_corto {
			/* text-align: right; */
			max-width: 100px;
		}



		.btn_eliminar_semilla_no_editable {
			background-color: gray;
			color: white;
			border: none;
			font-weight: bold;
			text-transform: uppercase;
			width: 100%;
			padding: 5px 0px;
		}

		.btn_eliminar_semilla_no_editable i {
			color: black;
			margin-right: 5px;
		}

		.btn_eliminar_semilla_editable {
			background-color: red;
			color: white;
			border: none;
			font-weight: bold;
			text-transform: uppercase;
			width: 100%;
			padding: 5px 0px;
		}

		.btn_eliminar_semilla_editable i {
			color: white;
			margin-right: 5px;
		}

		.btn_eliminar_semilla_editable:hover {
			background-color: darkred;
			cursor: pointer;
			/* Cambia el cursor a una mano para indicar que es interactivo */
		}

		.btn_guardar_semillia {
			background-color: green;
			color: white;
			border: none;
			font-weight: bold;
			text-transform: uppercase;
			width: 100%;
			padding: 5px 0px;
		}

		.btn_guardar_semillia i {
			color: white;
			margin-right: 5px;
		}

		.btn_guardar_semillia:hover {
			background-color: darkgreen;
			cursor: pointer;
			/* Cambia el cursor a una mano para indicar que es interactivo */
		}

		.btnCantidadAcresUsados,
		.btnCantidadSemillas,
		.btnPorcentajeAcres {
			width:
				100% !important;
			text-align:
				right;
		}

		.input_cantidad_semillas,
		.input_porcentaje_acres {
			text-align:
				right;
		}

		.modal_centrado {
			display: flex !important;
			align-items: center;
			justify-content: center;
		}

		.input_oculto {
			display: none !important;
		}

		.botones_no_seleccionables {
			cursor: default;
		}

		.semilla_asociada_cargada {
			background-color: #280058;
			color: white;
			border-radius: 10px;
			border: 1px solid white;
			text-align: center;
			padding-left: 5px;
		}

		.semilla_asociada_eliminada {
			background-color: #595959;
			color: #D3D3D3;
			border-radius: 10px;
			border: 1px solid white;
			text-align: center;
			padding-left: 5px;
		}
	</style>

</head>

<script type="text/javascript">
	jQuery.ajaxSetup({
		async: false
	});
	var indicesEstadoUnificados = 0;
	var indicesGranjasUnificados = 0;
	var indicesCamposUnificados = 0;
	var indicesBloquesUnificados = 0;
	var indicesBloquesLibreUnificados = 0;

	var datosAcresBloques = {};
	var estadoDespliegueBloque = {};

	var valorCantidadInicialDeSemillas = 0;
	var valorCantidadInicialPorcentaje = 0;
	var correlativoFilaNueva = 1;
	var cantidadFechasRegistradas = 0;
	var arrFechasEditables = {};

	grl_overlay_loading('');

	$(document).ready(function() {

		codigo_granjas_plantacion = 0;
		$('.selectpicker').selectpicker({
			dropupAuto: 'true',
			container: 'body',
			size: '5',
			width: '100%',
			style: 'btn-sm btn-info',
			tickIcon: 'fa fa-check'
		});


		var fecha_hoy = new Date(<?php echo time() * 1000; ?>);
		var hoy = (fecha_hoy.getMonth() + 1) + "/" + fecha_hoy.getDate() + "/" + fecha_hoy.getFullYear();
		var hoy_default = fecha_hoy.getFullYear() + "-" + (fecha_hoy.getMonth() + 1) + "-" + fecha_hoy.getDate();

		$('#date_picker_farm_plating').datetimepicker({
			//nDate: new Date(),

			format: 'MM-DD-YYYY',
			defaultDate: hoy,
			icons: {
				time: "fas fa-clock",
				date: "fa fa-calendar",
				up: "fa fa-arrow-up",
				down: "fa fa-arrow-down",
				previous: 'fa fa-arrow-left',
				next: 'fa fa-arrow-right',
			}
		});
		$('.fecha_previamente_registrada').datetimepicker({
			//nDate: new Date(),

			format: 'MM-DD-YYYY',
			defaultDate: hoy,
			icons: {
				time: "fas fa-clock",
				date: "fa fa-calendar",
				up: "fa fa-arrow-up",
				down: "fa fa-arrow-down",
				previous: 'fa fa-arrow-left',
				next: 'fa fa-arrow-right',
			}
		});
		//Habilita los selects para mobile
		if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
			$('.selectpicker').selectpicker('mobile');
		}

		//Constructores
		farm_constructor_listado_estados_de_plantacion("cod_estados", false);
		// farm_constructor_listado_granjas("cod_granja", false);
		$('.selectpicker').selectpicker('refresh');

		/*----------------------------------------------------------------------------------
    								Validando listboxs
	    ----------------------------------------------------------------------------------*/
		$('.selectpicker.requerido').change(function(event) {
			var objeto = $(this);
			if (objeto.val() == null || objeto.val() == '-b' || objeto.val() == '') {
				objeto.selectpicker('setStyle', 'btn-info', 'remove');
				objeto.selectpicker('setStyle', 'btn-danger');
				$(this).parent('div').addClass('has-error');
			} else {
				objeto.selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');
				objeto.selectpicker('setStyle', 'btn-info');
				objeto.removeClass('campo-vacio');
				$(this).parent('div').removeClass('has-error');
			}
			objeto.selectpicker('refresh');
		});
		/*----------------------------------------------------------------------------------
									Validación input
		----------------------------------------------------------------------------------*/
		$('.input.requerido').keyup(function(event) {
			if ($(this).val().trim() != "") {
				$(this).parent('div').removeClass('has-error');
			} else {
				$(this).parent('div').addClass('has-error');
			}
		});


		$('#modal_loading').modal('hide');
		jQuery.ajaxSetup({
			async: true
		});
	});


	$('#btn_filtrar').click(function(event) {

		jQuery.ajaxSetup({
			async: false
		});
		var error = 0;
		$(".selectpicker.requerido").map(function() {
			if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '') {
				$(this).selectpicker('setStyle', 'btn-info', 'remove');
				$(this).selectpicker('setStyle', 'btn-danger');
				$(this).parent('div').addClass('has-error');
				error = 1;
			} else {
				$(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');
				$(this).selectpicker('setStyle', 'btn-info');
				$(this).parent('div').removeClass('has-error');
				$(this).removeClass('campo-vacio');
			}
			$(this).selectpicker('refresh');
		});
		if (error == 0) {
			grl_overlay_loading('');
			$('#modal_loading').modal('hide');
			$('#modal_loading').on('hidden.bs.modal', function() {

				$.ajax({
					url: 'mod_farms/ui/farm_granjas_plantacion.php',
					type: 'POST',
					dataType: 'html',
					data: {
						codigosEstados: indicesEstadoUnificados,
						codigosGranjas: indicesGranjasUnificados,
						codigosCampos: indicesCamposUnificados,
						codigoBloquesLibres: indicesBloquesLibreUnificados,
						codigosBloques: indicesBloquesUnificados,
					},
				}).done(function(data) {
					$('#div_cuerpo_menu').empty();
					$('#div_cuerpo_menu').html(data);
				}).fail(function() {
					console.log("Failed to filter");
				});

				jQuery.ajaxSetup({
					async: true
				});
			});
		} else {
			grl_mensaje('You must fill out all marked fields', 'Please check', 'warning');
			jQuery.ajaxSetup({
				async: true
			});
		}


	});

	$('#cod_estados').change(function(event) {
		if ($(this).val() != undefined && $(this).val() != null && $(this).val() != '-b') {

			var indicesSeleccionados = $(this).val().join(); // Esto unirá los elementos del array con una coma por defecto
			indicesEstadoUnificados = indicesSeleccionados.toString(); // Convertir el resultado en una cadena de texto
			indicesEstadoUnificados = indicesEstadoUnificados.replaceAll("-b", "0");

			farm_listado_granjas_por_estados("cod_granja", indicesEstadoUnificados, false);
		} else {
			indicesEstadoUnificados = "";
			indicesGranjasUnificados = "";
			indicesCamposUnificados = "";
			indicesBloquesUnificados = "";
			indicesBloquesUsadosUnificados = "";
			$('#cods_campos').empty();
			$('#cod_bloques').empty();
			$('#cod_bloques_usados').empty();
			indicesBloquesImplementados = "";
			indicesBloquesUsadosUnificados = "";
			$('.selectpicker').selectpicker('refresh');
		}
	});


	$('#cod_granja').change(function(event) {
		if ($(this).val() != undefined && $(this).val() != null && $(this).val() != '-b') {

			var indicesSeleccionados = $(this).val().join(); // Esto unirá los elementos del array con una coma por defecto
			indicesGranjasUnificados = indicesSeleccionados.toString(); // Convertir el resultado en una cadena de texto
			indicesGranjasUnificados = indicesGranjasUnificados.replaceAll("-b", "0");
			farm_listado_campo_por_granja("cod_campo", indicesGranjasUnificados, false);
		} else {
			indicesGranjasUnificados = "";
			indicesCamposUnificados = "";
			indicesBloquesUnificados = "";
			indicesBloquesLibreUnificados = "";
			$('#cod_campo').empty();
			$('#cod_bloques').empty();
			$('#cod_bloques_libres').empty();
			$('.selectpicker').selectpicker('refresh');
		}

	});

	$('#cod_campo').change(function(event) {
		if ($(this).val() != undefined && $(this).val() != null && $(this).val() != '-b') {

			var indicesSeleccionados = $(this).val().join(); // Esto unirá los elementos del array con una coma por defecto
			indicesCamposUnificados = indicesSeleccionados.toString(); // Convertir el resultado en una cadena de texto
			indicesCamposUnificados = indicesCamposUnificados.replaceAll("-b", "0");
			farm_listado_bloques_para_granja_por_campos("cod_bloques", indicesCamposUnificados);
			farm_listado_bloques_libres_por_campos("cod_bloques_libres", indicesCamposUnificados, false);

		} else {
			indicesCamposUnificados = "";
			indicesBloquesUnificados = "";
			indicesBloquesLibreUnificados = "";
			$('#cod_bloques').empty();
			$('#cod_bloques_libres').empty();
			$('.selectpicker').selectpicker('refresh');
		}
	});

	$('#cod_bloques').change(function(event) {
		if ($(this).val() != undefined && $(this).val() != null && $(this).val() != '-b') {

			var indicesSeleccionados = $(this).val().join(); // Esto unirá los elementos del array con una coma por defecto
			indicesBloquesUnificados = indicesSeleccionados.toString(); // Convertir el resultado en una cadena de texto
			indicesBloquesUnificados = indicesBloquesUnificados.replaceAll("-b", "0");
		} else {
			indicesBloquesUnificados = "";
		}

	});

	$('#cod_bloques_libres').change(function(event) {
		if ($(this).val() != undefined && $(this).val() != null && $(this).val() != '-b') {

			var indicesSeleccionados = $(this).val().join(); // Esto unirá los elementos del array con una coma por defecto
			indicesBloquesLibreUnificados = indicesSeleccionados.toString(); // Convertir el resultado en una cadena de texto
			indicesBloquesLibreUnificados = indicesBloquesLibreUnificados.replaceAll("-b", "0");
		} else {
			indicesBloquesLibreUnificados = "";
		}

	});

	//FUNCIONES PARA LA EDICIÓN DE LOS ACRES REGISTRADOS
	function activarEdicion(idElemento) {
		let btnActivarInput = $('#btn_acres_registrado_' + idElemento);
		let miInput = $('#input_acres_registrado_' + idElemento);
		miInput.toggle(); // Alterna la visibilidad del input
		miInput.prop('disabled', false);
		btnActivarInput.toggle(); // Alterna la visibilidad del input
		miInput.focus();
		valorCantidadInicialDeSemillas = miInput.val();

		let valorParaBoton = parseFloat(valorCantidadInicialDeSemillas)
		btnActivarInput.text(valorParaBoton);
	}

	function salirDelInput(cod_bloque_implementado, cod_semilla_bloque, idElemento, porcentaje_acres_inicial, acres_por_usar_inciales, cod_bloque, registrar = true) {

		let btnActivarInput = $('#btn_acres_registrado_' + idElemento);
		let miInput = $('#input_acres_registrado_' + idElemento);
		if (btnActivarInput == undefined || miInput == undefined) return;
		if (valorCantidadInicialDeSemillas != parseFloat(miInput.val()) && registrar) {
			//guardarCantidadIngresada(parseFloat(miInput.val()), idElemento);
			registrarCantidades(cod_bloque, idElemento, cod_semilla_bloque, cod_bloque_implementado);

		}

		var valorInput = miInput.val();
		if (valorInput < 0.01) {
			valorInput = 0.01;
			miInput.val(valorInput);

		}
		// Deshabilitar el input cuando se pierde el foco
		miInput.toggle(0);
		miInput.prop('disabled', true);
		btnActivarInput.toggle(200); // Alterna la visibilidad del input
		$('.selectpicker').selectpicker('refresh');

	}

	function detectarDatosEntradaDeTeclados(event, idElemento, maximoPermitido, acres_iniciales, porcentaje_inicial, cod_bloque) {

		let btnActivarInput = $('#btn_acres_registrado_' + idElemento);

		let miInput = $('#input_acres_registrado_' + idElemento);

		let diferecianEncontrada = 0;
		// let btnActivarInput = $('#btn_bloque_' + cod_bloque);
		// let miInput = $('#input_cantidad_acres_' + cod_bloque);
		var valorInput = miInput.val();
		var puntoEncontrado = false;
		var numerosDespuesDelPunto = 0;

		valorInput = parseFloat(valorInput);

		let cantidadAcres = $('#acres_iniciales_' + cod_bloque).text();
		cantidadAcres = parseFloat(cantidadAcres);
		valorInput = parseFloat(valorInput);
		maximoPermitido = parseFloat(maximoPermitido);
		if (cantidadAcres >= maximoPermitido) {
			diferecianEncontrada = cantidadAcres - maximoPermitido;
			diferecianEncontrada = parseFloat(diferecianEncontrada).toFixed(2)
			diferecianEncontrada = parseFloat(diferecianEncontrada) + 0.01


			valorInput = valorInput - diferecianEncontrada;
			valorInput = valorInput.toFixed(2);
			valorInput = parseFloat(valorInput);

		}

		miInput.val(valorInput);

		let valorParaBoton = valorInput
		btnActivarInput.text(valorParaBoton);

		// Actualizar el porcentaje
		let nuevoPorcentaje = (valorInput / maximoPermitido) * 100
		let numeroRedondeado = nuevoPorcentaje.toFixed(0);

		let btnActivarInputPorcentaje = $('#btn_porcentaje_acres_registrado_' + idElemento);
		let miInputPorcentaje = $('#input_porcentaje_acres_registrado_' + idElemento);
		miInputPorcentaje.val(numeroRedondeado);
		btnActivarInputPorcentaje.text(numeroRedondeado + "%");

		//ASIGNAR LOS VALORES A LOS CAMPOS GENERALES
		datosAcresBloques[cod_bloque]["datos_interno_de_bloque_porcentaje_" + cod_bloque]["porcentaje_" + idElemento] = numeroRedondeado
		datosAcresBloques[cod_bloque]["datos_interno_de_bloque_acres_" + cod_bloque]["acres_" + idElemento] = valorParaBoton
		sumarCantidadesGenerales(cod_bloque);

	}

	function detectarTeclasDeSalida(event, idElemento, porcentajeAcresUsados, cod_bloque) {
		let btnActivarInput = $('#btn_acres_registrado_' + idElemento);
		let miInput = $('#input_acres_registrado_' + idElemento);

		var valorInput = miInput.val();

		if (event.keyCode === 13) { //Tecla "Enter"/"Entrar"
			miInput.prop('disabled', true);
			valorCantidadInicialDeSemillas = parseFloat(miInput.val());
		}


		if (event.keyCode === 27) { // Tecla "ESC"
			// Aquí puedes ejecutar el código que deseas cuando se presiona "ESC"
			// $("#input_acres_registrado_" + idElemento).val();
			// let valorParaBoton = parseFloat(valorCantidadInicialDeSemillas)
			// btnActivarInput.text(valorParaBoton);
			// miInput.val(valorCantidadInicialDeSemillas);
			// miInput.prop('disabled', true);
		}
	}
	// ----------------------------------------------------
	//FUNCIONES PARA LA EDICIÓN DEL PORCENTAJE DE LOS ACRES REGISTRADOS
	function activarEdicionPorcentaje(idElemento) {
		let btnActivarInput = $('#btn_porcentaje_acres_registrado_' + idElemento);
		let miInput = $('#input_porcentaje_acres_registrado_' + idElemento);
		miInput.toggle(); // Alterna la visibilidad del input
		miInput.prop('disabled', false);
		btnActivarInput.toggle(); // Alterna la visibilidad del input
		miInput.focus();
		valorCantidadInicialPorcentaje = miInput.val();

		let valorParaBoton = parseInt(valorCantidadInicialPorcentaje)
		btnActivarInput.text(valorParaBoton + "%");
	}

	function salirDelInputPorcentaje(cod_bloque_implementado, cod_semilla_bloque, idElemento, porcentaje_acres_inicial, acres_por_usar_inciales, cod_bloque, registrar = true) {

		let btnActivarInput = $('#btn_porcentaje_acres_registrado_' + idElemento);
		let miInput = $('#input_porcentaje_acres_registrado_' + idElemento);
		if (btnActivarInput == undefined || miInput == undefined) return;
		var valorInput = miInput.val();
		let valorParaBoton = parseInt(valorInput)
		let cantidadAcresNuevo = (parseFloat(valorParaBoton) / (porcentaje_acres_inicial)) * acres_por_usar_inciales

		let numeroRedondeado = cantidadAcresNuevo.toFixed(2);
		if (valorCantidadInicialPorcentaje != parseInt(miInput.val()) && registrar) {
			registrarCantidades(cod_bloque, idElemento, cod_semilla_bloque, cod_bloque_implementado);
		}

		if (valorInput < 0.01) {
			valorInput = 0.01;
			miInput.val(valorInput);

		}
		// Deshabilitar el input cuando se pierde el foco
		miInput.toggle(0);
		miInput.prop('disabled', true);
		btnActivarInput.toggle(200); // Alterna la visibilidad del input
		$('.selectpicker').selectpicker('refresh');


	}

	function detectarDatosEntradaDeTecladosPorcentaje(event, idElemento, maximoPermitido, porcentaje_acres_inicial, acres_por_usar_inciales, cod_bloque) {

		let btnActivarInput = $('#btn_porcentaje_acres_registrado_' + idElemento);

		let miInput = $('#input_porcentaje_acres_registrado_' + idElemento);
		let diferecianEncontrada = 0;
		var valorInput = miInput.val();
		var puntoEncontrado = false;
		var numerosDespuesDelPunto = 0;

		// if (valorInput < 1) {
		// 	valorInput = 1;
		// }
		let PorcentajeAcresPrincipal = $('#porcentaje_acres_iniciales_' + cod_bloque).text();
		PorcentajeAcresPrincipal = PorcentajeAcresPrincipal.replace("%", "")
		PorcentajeAcresPrincipal = parseInt(PorcentajeAcresPrincipal);

		valorInput = parseInt(valorInput);
		if ((PorcentajeAcresPrincipal) >= 100) {

			diferecianEncontrada = PorcentajeAcresPrincipal - 100;
			valorInput = valorInput - diferecianEncontrada - 1;
		}

		miInput.val(valorInput);

		let valorParaBoton = parseInt(valorInput)
		btnActivarInput.text(valorParaBoton + "%");

		let cantidadAcresNuevo = (parseFloat(valorParaBoton) / (porcentaje_acres_inicial)) * acres_por_usar_inciales

		let numeroRedondeado = cantidadAcresNuevo.toFixed(2);
		let BtnAcresRegistrados = $('#btn_acres_registrado_' + idElemento);
		let InputAcresRegistrados = $('#input_acres_registrado_' + idElemento);
		numeroRedondeado = parseFloat(numeroRedondeado);

		InputAcresRegistrados.val(numeroRedondeado);
		BtnAcresRegistrados.text(numeroRedondeado);





		//ASIGNAR LOS VALORES A LOS CAMPOS GENERALES
		datosAcresBloques[cod_bloque]["datos_interno_de_bloque_porcentaje_" + cod_bloque]["porcentaje_" + idElemento] = valorParaBoton
		datosAcresBloques[cod_bloque]["datos_interno_de_bloque_acres_" + cod_bloque]["acres_" + idElemento] = numeroRedondeado
		sumarCantidadesGenerales(cod_bloque);
	}

	function detectarTeclasDeSalidaPorcentaje(event, idElemento, cod_bloque) {
		let btnActivarInput = $('#btn_porcentaje_acres_registrado_' + idElemento);
		let miInput = $('#input_porcentaje_acres_registrado_' + idElemento);

		var valorInput = miInput.val();

		if (event.keyCode === 13) { //Tecla "Enter"/"Entrar"
			miInput.prop('disabled', true);
			valorCantidadInicialPorcentaje = parseInt(miInput.val());
		}


		if (event.keyCode === 27) { // Tecla "ESC"
			// Aquí puedes ejecutar el código que deseas cuando se presiona "ESC"
			// $("#input_porcentaje_acres_registrado_" + idElemento).val();
			// let valorParaBoton = parseInt(valorCantidadInicialPorcentaje)
			// btnActivarInput.text(valorParaBoton + "%");
			// miInput.val(valorCantidadInicialPorcentaje);
			// miInput.prop('disabled', true);

			// datosAcresBloques[cod_bloque]["datos_interno_de_bloque_porcentaje_" + cod_bloque]["porcentaje_" + idElemento] = valorParaBoton
			// datosAcresBloques[cod_bloque]["datos_interno_de_bloque_acres_" + cod_bloque]["acres_" + idElemento] = numeroRedondeado
			// sumarCantidadesGenerales(cod_bloque);
		}
	}
	// ----------------------------------------------------

	function guardarCantidadIngresada(valorIngresado, cod_bloque) {

		farm_guardar_cantidad_acres_plantacion_granja(cod_bloque, parseFloat(valorIngresado));
	}

	function guardarCantidadIngresadaPorSemilla(cod_semila_bloque, cantidad_acres, porcentaje_acres) {

		farm_guardar_cantidad_acres_plantacion_granja_por_semilla(cod_semila_bloque, cantidad_acres, porcentaje_acres);
	}

	function guardarCantidadEnBloquesImpelementados(cod_bloque_implementado, cantidad_acres, porcentaje_acres) {

		farm_guardar_cantidad_acres_en_bloque_implementado(cod_bloque_implementado, cantidad_acres, porcentaje_acres);
	}

	function sumarCantidadesGenerales(cod_bloque) {
		let PorcentajeAcresPrincipal = $('#porcentaje_acres_iniciales_' + cod_bloque);
		let AcresPrincipal = $('#acres_iniciales_' + cod_bloque);
		let cantidadMaximaAcres = $('#acres_maximo_' + cod_bloque);
		let maximoAcresPermitido = parseFloat(cantidadMaximaAcres.text())

		let sumaAcreas = 0;
		let sumaPorcentaje = 0;
		let arrayAcres = datosAcresBloques[cod_bloque]["datos_interno_de_bloque_acres_" + cod_bloque];
		let arrayPorcentaje = datosAcresBloques[cod_bloque]["datos_interno_de_bloque_porcentaje_" + cod_bloque];

		for (var key in arrayAcres) {
			sumaAcreas += parseFloat(arrayAcres[key]);
		}
		for (var key in arrayPorcentaje) {
			sumaPorcentaje += parseInt(arrayPorcentaje[key]);
		}

		if (maximoAcresPermitido <= sumaAcreas) {
			sumaPorcentaje = 100;
			sumaAcreas = maximoAcresPermitido;
		}

		if (sumaPorcentaje >= 100 || maximoAcresPermitido <= sumaAcreas || !datosAcresBloques[cod_bloque]["habilitar_agregar_fila" + cod_bloque]) {

			$("#btn_agregar_fila_" + cod_bloque).removeClass("btn-primary");
			$("#btn_agregar_fila_" + cod_bloque).addClass("btn_agrega_fila_desactivada");
		} else {

			$("#btn_agregar_fila_" + cod_bloque).addClass("btn-primary");
			$("#btn_agregar_fila_" + cod_bloque).removeClass("btn_agrega_fila_desactivada");
		}

		// Asignaciones
		PorcentajeAcresPrincipal.text(sumaPorcentaje + "%"); //Porcentaje
		AcresPrincipal.text(sumaAcreas.toFixed(2)); //Acres

	}

	function eliminarSemilla(cod_bloque, cod_semilla_bloque, idElemento, idFecha, cod_bloque_implementado, cod_inventario) {
		datosAcresBloques[cod_bloque]["datos_interno_de_bloque_porcentaje_" + cod_bloque]["porcentaje_" + idElemento] = 0
		datosAcresBloques[cod_bloque]["datos_interno_de_bloque_acres_" + cod_bloque]["acres_" + idElemento] = 0
		sumarCantidadesGenerales(cod_bloque);
		$('#btn_acres_registrado_' + idElemento).removeAttr("onclick");
		$('#btn_porcentaje_acres_registrado_' + idElemento).removeAttr("onclick");

		$('#semilla_cargada_' + idElemento).removeClass("semilla_asociada_cargada");
		$('#semilla_cargada_' + idElemento).addClass("semilla_asociada_eliminada");

		$('#btn_acres_registrado_' + idElemento).addClass("boton_desactivado");
		$('#btn_porcentaje_acres_registrado_' + idElemento).addClass("boton_desactivado");

		$('.fecha_registrada_plantaciones_' + idFecha).datetimepicker("destroy")
		 
		farm_eliminar_semilla_de_plantacion(cod_semilla_bloque, cod_inventario, cod_bloque)
		registrarCantidades(cod_bloque, idElemento, cod_semilla_bloque, cod_bloque_implementado);
		datosAcresBloques[cod_bloque]["cantidad_semillas_" + cod_bloque] = datosAcresBloques[cod_bloque]["cantidad_semillas_" + cod_bloque] - 1;

	}

	function nuevoCampoDeFecha(idsUnificados, cod_bloque_implementado, cod_semilla_bloque, fecha_plantacion) {

		var fecha_hoy = new Date(<?php echo time() * 1000; ?>);
		var hoy = fecha_hoy.getFullYear() + "-" + (fecha_hoy.getMonth() + 1) + "-" + fecha_hoy.getDate();
		var fechaUsar = fecha_plantacion;

		if (fecha_plantacion == undefined || fecha_plantacion == null) {
			fechaUsar = hoy;
		}
		$('.fecha_registrada_plantaciones_' + idsUnificados).datetimepicker({
			format: 'YYYY-MM-DD',
			// defaultDate: hoy,
			defaultDate: fechaUsar,
			icons: {
				time: "fas fa-clock",
				date: "fa fa-calendar",
				up: "fa fa-arrow-up",
				down: "fa fa-arrow-down",
				previous: 'fa fa-arrow-left',
				next: 'fa fa-arrow-right',
			}
		}).on('dp.hide', function(datoFecha) {
			const FECHAFORMATEADA = datoFecha.date.format('YYYY-MM-DD');
			farm_guardar_fecha_de_plantacion_granja_semilla(cod_semilla_bloque, FECHAFORMATEADA)
		});


	}

	function registrarCantidades(cod_bloque, idElemento, cod_semilla_bloque, cod_bloque_implementado) {
		let cantidadAcres = datosAcresBloques[cod_bloque]["datos_interno_de_bloque_acres_" + cod_bloque]["acres_" + idElemento]
		let porcentajeAcres = datosAcresBloques[cod_bloque]["datos_interno_de_bloque_porcentaje_" + cod_bloque]["porcentaje_" + idElemento]
		let arrayAcres = datosAcresBloques[cod_bloque]["datos_interno_de_bloque_acres_" + cod_bloque];
		let arrayPorcentaje = datosAcresBloques[cod_bloque]["datos_interno_de_bloque_porcentaje_" + cod_bloque];

		let cantidadMaximaAcres = $('#acres_maximo_' + cod_bloque);
		let maximoAcresPermitido = parseFloat(cantidadMaximaAcres.text())

		let sumaAcreas = 0;
		let sumaPorcentaje = 0;
		for (var key in arrayAcres) {
			sumaAcreas += parseFloat(arrayAcres[key]);
		}
		for (var key in arrayPorcentaje) {
			sumaPorcentaje += parseInt(arrayPorcentaje[key]);
		}
		if (maximoAcresPermitido <= sumaAcreas) {
			sumaPorcentaje = 100;
			sumaAcreas = maximoAcresPermitido;
		}
		guardarCantidadIngresadaPorSemilla(cod_semilla_bloque, parseFloat(cantidadAcres), parseFloat(porcentajeAcres));
		guardarCantidadEnBloquesImpelementados(cod_bloque_implementado, sumaAcreas, sumaPorcentaje);

	}
	//--------------------------------------


	function buscarSemillasDeBloque(cod_bloque) {
		jQuery.ajaxSetup({
			async: false
		});
		document.getElementById("cuerpo_tabla_detalle" + cod_bloque).innerHTML = "";

		if (!datosAcresBloques[cod_bloque]["bloque_desplegado" + cod_bloque]) {

			datosAcresBloques[cod_bloque]["bloque_desplegado" + cod_bloque] = true;

			let listadoSemillasAsociadas = [];
			listadoSemillasAsociadas = farm_listado_semillas_implementadas_en_bloque(cod_bloque)

			if (listadoSemillasAsociadas != null && listadoSemillasAsociadas.length > 0) {
				recopilarFechasEditables();
				document.getElementById("cuerpo_tabla_detalle" + cod_bloque).innerHTML = "";
				datosAcresBloques[cod_bloque]["datos_interno_de_bloque_porcentaje_" + cod_bloque] = [];
				datosAcresBloques[cod_bloque]["datos_interno_de_bloque_acres_" + cod_bloque] = [];
				listadoSemillasAsociadas.forEach(function(semillaAsociada) {
					/**
					ini_acres:"0.33"
					acres_disponibles:"0.23"
					acres_usados:"0.05"
					cod_bloque:"376"
					cod_semilla_bloque:"1"
					cod_bloque_implementado:"1"
					cod_inventario:"8"
					cod_plantacion:"5168"
					cod_semilla:"8"
					cod_trasplante:"6"
					edad:"3"
					fecha_entrega:"2023-07-14 06:00:00"
					fecha_inicial:"2023-09-14 00:00:00"
					fecha_plantacion:"31-03-2024"
					fecha_plantacion_original:"2024-03-31 23:20:23"
					nombre_semilla:"BOK CHOI - Joi Choi"
					numero_orden:"11332"
					numero_ticket:"59051"
					porcentaje_acre_usado:"15.15"
					 * 1 - Etiqueta con los siguientes datos: Nombre de la semilla, fecha de plantacion, numero de orden, numero de etiqueta
					 * 2 - Etiqueta para la edad
					 * 3 - Campo de selección de fecha, con la fecha registrada como valor inicial
					 * 4 - Porcentaje usado
					 * 5 - Acres usados
					 */

					let idUnicoFila = cod_bloque + "" + correlativoFilaNueva;
					let cod_semilla_bloque = semillaAsociada["cod_semilla_bloque"];
					let cod_bloque_implementado = semillaAsociada["cod_bloque_implementado"];
					let cod_inventario = semillaAsociada["cod_inventario"];
					let cod_temporada = semillaAsociada["cod_temporada"];
					// Valores a almacenar en variables
					var porcentajeAcresUsados = semillaAsociada["porcentaje_acre_usado"];
					var edadSemilla = semillaAsociada["edad"];
					var cantidadMaximaDeAcres = semillaAsociada["ini_acres"];
					var cantidadAcresUsados = semillaAsociada["acres_usados"];
					let datoSemillas = semillaAsociada["nombre_semilla"] + "|" + semillaAsociada["fecha_plantacion"] + "|" + semillaAsociada["numero_orden"] + "-" + semillaAsociada["numero_ticket"];



					// Crear la fila
					var nuevaFila = document.createElement("tr");
					nuevaFila.id = "fila_semilla_" + idUnicoFila;
					// Crear el primer td vacío
					var primerTD = document.createElement("td");
					nuevaFila.appendChild(primerTD);

					// Crear el segundo td con el div
					var segundoTD = document.createElement("td");
					var divSemillaAsociada = document.createElement("div");
					divSemillaAsociada.className = "semilla_asociada_cargada";
					divSemillaAsociada.id = "semilla_cargada_" + idUnicoFila;
					var pNombreSemilla = document.createElement("p");
					pNombreSemilla.textContent = datoSemillas;
					divSemillaAsociada.appendChild(pNombreSemilla);
					segundoTD.appendChild(divSemillaAsociada);
					nuevaFila.appendChild(segundoTD);

					// Crear el tercer td con la clase "edad_semilla_cargada"
					var tercerTD = document.createElement("td");
					tercerTD.className = "edad_semilla_cargada";
					tercerTD.textContent = edadSemilla;
					tercerTD.id = "edad_semilla_" + idUnicoFila;
					nuevaFila.appendChild(tercerTD);

					// Crear el cuarto td con el div de fecha
					var cuartoTD = document.createElement("td");
					var divInputGroupFecha = document.createElement("div");
					divInputGroupFecha.className = "input-group input-group-sm fecha-planeada datetime_ship fecha_registrada_plantaciones_" + cantidadFechasRegistradas;
					var spanAddonFecha = document.createElement("span");
					spanAddonFecha.className = "input-group-addon";
					var spanCalendar = document.createElement("span");
					spanCalendar.className = "fa fa-calendar";
					spanAddonFecha.appendChild(spanCalendar);
					var inputFecha = document.createElement("input");
					inputFecha.type = "text";
					inputFecha.className = "form-control input requerido_harvesting_worksheet";
					inputFecha.id = "fecha_registrada_plantaciones_" + cantidadFechasRegistradas;
					divInputGroupFecha.appendChild(spanAddonFecha);
					divInputGroupFecha.appendChild(inputFecha);
					cuartoTD.appendChild(divInputGroupFecha);
					nuevaFila.appendChild(cuartoTD);

					// Crear el quinto td con los botones y el icono de porcentaje
					var quintoTD = document.createElement("td");
					quintoTD.className = "boto_seleccionable";
					var btnPorcentaje = document.createElement("button");
					btnPorcentaje.className = "btnPorcentajeAcres";
					btnPorcentaje.textContent = porcentajeAcresUsados + "%";
					btnPorcentaje.id = "btn_porcentaje_acres_registrado_" + idUnicoFila;

					btnPorcentaje.setAttribute("onclick", "activarEdicionPorcentaje(" + idUnicoFila + ")");
					// btnPorcentaje.setAttribute("onclick", "pruebaInteraccion()");
					quintoTD.appendChild(btnPorcentaje);
					var inputPorcentaje = document.createElement("input");
					inputPorcentaje.type = "number";
					inputPorcentaje.id = "input_porcentaje_acres_registrado_" + idUnicoFila;
					inputPorcentaje.className = "input_porcentaje_acres";
					inputPorcentaje.min = "0";
					inputPorcentaje.step = "1";
					// inputPorcentaje.max = porcentajeAcresUsados;
					inputPorcentaje.value = porcentajeAcresUsados;
					inputPorcentaje.setAttribute("hidden", true);
					inputPorcentaje.setAttribute("onblur", "salirDelInputPorcentaje(" + cod_bloque_implementado + "," + cod_semilla_bloque + "," + idUnicoFila + "," + porcentajeAcresUsados + "," + cantidadAcresUsados + "," + cod_bloque + ")");
					inputPorcentaje.setAttribute("onkeydown", "detectarTeclasDeSalidaPorcentaje(event," + idUnicoFila + "," + porcentajeAcresUsados + "," + cod_bloque + ")");
					inputPorcentaje.setAttribute("oninput", "detectarDatosEntradaDeTecladosPorcentaje(event," + idUnicoFila + "," + porcentajeAcresUsados + "," + porcentajeAcresUsados + "," + cantidadAcresUsados + "," + cod_bloque + ")");
					quintoTD.appendChild(inputPorcentaje);
					var inputOcultoPorcentaje = document.createElement("input");
					inputOcultoPorcentaje.type = "number";
					inputOcultoPorcentaje.className = "input_oculto";
					inputOcultoPorcentaje.id = "input_cantidad_inicial_porcentaje_acres_registrado_" + idUnicoFila;
					inputOcultoPorcentaje.value = porcentajeAcresUsados;
					quintoTD.appendChild(inputOcultoPorcentaje);
					nuevaFila.appendChild(quintoTD);

					// Crear el sexto td con los botones y el icono de cantidad de semillas
					var sextoTD = document.createElement("td");
					sextoTD.className = "boto_seleccionable";
					var btnCantidadAcresUsados = document.createElement("button");
					btnCantidadAcresUsados.className = "btnCantidadAcresUsados";
					btnCantidadAcresUsados.id = "btn_acres_registrado_" + idUnicoFila;
					btnCantidadAcresUsados.textContent = cantidadAcresUsados;
					btnCantidadAcresUsados.setAttribute("onclick", "activarEdicion(" + idUnicoFila + ")");
					sextoTD.appendChild(btnCantidadAcresUsados);

					var inputCantidadSemillas = document.createElement("input");
					inputCantidadSemillas.type = "number";
					inputCantidadSemillas.className = "input_cantidad_semillas";
					inputCantidadSemillas.min = "0";
					inputCantidadSemillas.step = "0.01";
					inputCantidadSemillas.max = cantidadMaximaDeAcres;
					inputCantidadSemillas.value = cantidadAcresUsados;
					inputCantidadSemillas.id = "input_acres_registrado_" + idUnicoFila;
					inputCantidadSemillas.setAttribute("hidden", true);

					inputCantidadSemillas.setAttribute("onblur", "salirDelInput(" + cod_bloque_implementado + "," + cod_semilla_bloque + "," + idUnicoFila + "," + porcentajeAcresUsados + "," + cantidadAcresUsados + "," + cod_bloque + ")");
					inputCantidadSemillas.setAttribute("onkeydown", "detectarTeclasDeSalida(event," + idUnicoFila + "," + porcentajeAcresUsados + "," + cod_bloque + ")");
					inputCantidadSemillas.setAttribute("oninput", "detectarDatosEntradaDeTeclados(event," + idUnicoFila + "," + cantidadMaximaDeAcres + "," + cantidadAcresUsados + "," + porcentajeAcresUsados + "," + cod_bloque + ")");
					sextoTD.appendChild(inputCantidadSemillas);
					var inputOcultoCantidadAcres = document.createElement("input");
					inputOcultoCantidadAcres.type = "number";
					inputOcultoCantidadAcres.className = "input_oculto";
					inputOcultoCantidadAcres.id = "input_cantidad_inicial_acres_registrado_" + idUnicoFila;
					inputOcultoCantidadAcres.value = cantidadAcresUsados;
					sextoTD.appendChild(inputOcultoCantidadAcres);
					nuevaFila.appendChild(sextoTD);

					// Crear el segundo td con el select de las temporadas
					var septimoTD = document.createElement("td");
					var divFormGroup = document.createElement("div");
					divFormGroup.className = "form-group input-group-sm";
					var select = document.createElement("select");
					select.className = "selectpicker show-menu-arrow";
					select.setAttribute("data-live-search", "true");
					select.setAttribute("title", "Select");
					select.id = "cod_temporada_" + idUnicoFila;
					select.name = "cod_temporada_" + idUnicoFila;
					divFormGroup.appendChild(select);
					septimoTD.appendChild(divFormGroup);
					nuevaFila.appendChild(septimoTD);

					// Crear el séptimo td con el botón de eliminar semilla
					var octavoTD = document.createElement("td");
					octavoTD.className = "boto_seleccionable";
					var botonEliminar = document.createElement("button");
					botonEliminar.className = "btn_eliminar_semilla_editable";
					botonEliminar.setAttribute("onclick", "eliminarSemilla(" + cod_bloque + "," + cod_semilla_bloque + "," + idUnicoFila + "," + cantidadFechasRegistradas + "," + cod_bloque_implementado + "," + cod_inventario + ")");
					var iconoEliminar = document.createElement("i");
					iconoEliminar.className = "fas fa-trash-alt";
					botonEliminar.appendChild(iconoEliminar);
					octavoTD.appendChild(botonEliminar);
					nuevaFila.appendChild(octavoTD);

					document.getElementById("cuerpo_tabla_detalle" + cod_bloque).appendChild(nuevaFila);


					// Listado de las temporadas
					farm_constructor_listado_temporada("cod_temporada_" + idUnicoFila)

					$('.selectpicker').selectpicker('refresh');
					$('#cod_temporada_' + idUnicoFila).selectpicker('val', cod_temporada);

					$('.selectpicker').selectpicker('refresh');
					detectarCambioSeleccionTemporada(idUnicoFila, cod_semilla_bloque);

					restablecerFechas();
					nuevoCampoDeFecha(cantidadFechasRegistradas, cod_bloque_implementado, cod_semilla_bloque, semillaAsociada["fecha_plantacion_registrada"])
					cantidadFechasRegistradas++;
					correlativoFilaNueva++;

					datosAcresBloques[cod_bloque]["datos_interno_de_bloque_porcentaje_" + cod_bloque]["porcentaje_" + idUnicoFila] = porcentajeAcresUsados;
					datosAcresBloques[cod_bloque]["datos_interno_de_bloque_acres_" + cod_bloque]["acres_" + idUnicoFila] = cantidadAcresUsados;
					datosAcresBloques[cod_bloque]["cantidad_semillas_" + cod_bloque] = datosAcresBloques[cod_bloque]["cantidad_semillas_" + cod_bloque] + 1;
				});
			}
		} else {
			datosAcresBloques[cod_bloque]["bloque_desplegado" + cod_bloque] = false;
			datosAcresBloques[cod_bloque]["habilitar_agregar_fila" + cod_bloque] = true;
		}
		jQuery.ajaxSetup({
			async: true
		});
	}

	function agregarNuevaFila(cod_bloque, cod_bloque_implementado, cod_estado) {
		jQuery.ajaxSetup({
			async: false
		});
		let PorcentajeAcresPrincipal = $('#porcentaje_acres_iniciales_' + cod_bloque).text();
		PorcentajeAcresPrincipal = PorcentajeAcresPrincipal.replace("%", "")
		PorcentajeAcresPrincipal = parseInt(PorcentajeAcresPrincipal);
		let cantidadMaximaAcres = $('#acres_maximo_' + cod_bloque);
		let maximoAcresPermitido = parseFloat(cantidadMaximaAcres.text())
		let cantidadAcresPrincipal = $('#acres_iniciales_' + cod_bloque).text();
		cantidadAcresPrincipal = parseFloat(cantidadAcresPrincipal);

		if (cod_bloque_implementado == 0) {
			cod_bloque_implementado = farm_registrar_bloque_implementado(cod_bloque);
		}

		if (PorcentajeAcresPrincipal < 100 &&
			cod_bloque_implementado != 0 &&
			cod_estado != 0 && maximoAcresPermitido > cantidadAcresPrincipal &&
			datosAcresBloques[cod_bloque]["habilitar_agregar_fila" + cod_bloque]) {
			datosAcresBloques[cod_bloque]["habilitar_agregar_fila" + cod_bloque] = false;
			let edadSemillaSeleccionada = 0;
			let cod_trasplante = 0;
			let cod_inventario = 0;
			let cod_plantacion = 0;
			// if ($("#numero_orden_" + cod_bloque).val() != null && cod_bloque_implementado != 0) {

			let porcentaje_acre_usado = 100 - PorcentajeAcresPrincipal;
			let acres_usados = parseFloat(datosAcresBloques[cod_bloque]["acreas_maximo_" + cod_bloque]) - cantidadAcresPrincipal;

			acres_usados = parseFloat(acres_usados.toFixed(2));

			let idUnicoFila = cod_bloque + "" + correlativoFilaNueva;
			let tablaInterna = document.getElementById("cuerpo_tabla_detalle" + cod_bloque)

			recopilarFechasEditables();
			var nuevaFila = document.createElement("tr");
			nuevaFila.id = "fila_semilla_" + idUnicoFila;

			// Crear el primer td vacío
			var primerTD = document.createElement("td");
			nuevaFila.appendChild(primerTD);

			// Crear el segundo td con el select
			var segundoTD = document.createElement("td");
			var divFormGroup = document.createElement("div");
			divFormGroup.className = "form-group input-group-sm";
			var select = document.createElement("select");
			select.className = "selectpicker show-menu-arrow";
			select.setAttribute("data-live-search", "true");
			select.setAttribute("title", "Select");
			select.id = "cod_semilla_" + idUnicoFila;
			select.name = "cod_semilla_" + idUnicoFila;
			divFormGroup.appendChild(select);
			segundoTD.appendChild(divFormGroup);
			nuevaFila.appendChild(segundoTD);

			// Crear el tercer td con el id específico
			var tercerTD = document.createElement("td");
			tercerTD.id = "edad_semilla_" + idUnicoFila;
			tercerTD.textContent = correlativoFilaNueva;
			nuevaFila.appendChild(tercerTD);

			// Crear el séptimo td con el botón de guardar semilla
			var septimoTD = document.createElement("td");
			septimoTD.setAttribute("colspan", "4");
			septimoTD.className = "boto_seleccionable";
			var btnGuardarSemilla = document.createElement("button");
			btnGuardarSemilla.className = "btn_guardar_semillia";
			var iconoEliminar = document.createElement("i");
			iconoEliminar.className = "fas fa-save";
			btnGuardarSemilla.appendChild(iconoEliminar);
			septimoTD.appendChild(btnGuardarSemilla);
			nuevaFila.appendChild(septimoTD);


			// Agregar la nueva fila a la tabla
			document.getElementById("cuerpo_tabla_detalle" + cod_bloque).appendChild(nuevaFila);


			// CARGAMOS EL LISTADO DE SEMILLAS
			farm_listado_semillasplantaciones_completadas_por_estado("cod_semilla_" + idUnicoFila, cod_estado, false)

			$('.selectpicker').selectpicker('refresh');
			edadSemillaSeleccionada = $("#cod_semilla_" + idUnicoFila + ' option:selected').data('edad');
			cod_trasplante = $("#cod_semilla_" + idUnicoFila + ' option:selected').data('cod_trasplante');
			cod_inventario = $("#cod_semilla_" + idUnicoFila + ' option:selected').data('cod_inventario');
			cod_plantacion = $("#cod_semilla_" + idUnicoFila + ' option:selected').data('cod_plantacion');

			var edadSemillaElement = document.getElementById("edad_semilla_" + idUnicoFila);

			btnGuardarSemilla.setAttribute("onclick", "guardarSemilla(" + cod_bloque + ", " + cod_bloque_implementado + ", " + cod_inventario + ", " + cod_plantacion + ", " + cod_trasplante + ", " + acres_usados + ", " + porcentaje_acre_usado + "," + idUnicoFila + "," + edadSemillaSeleccionada + ")");

			// Verificar si el elemento existe antes de intentar cambiar su contenido
			if (edadSemillaElement) {
				// Cambiar el valor de edad_semilla_
				edadSemillaElement.textContent = edadSemillaSeleccionada;
			}
			// $("#cod_semilla_" + idUnicoFila)
			$("#cod_semilla_" + idUnicoFila).change(function(event) {
				edadSemillaSeleccionada = $(this).find('option:selected').data('edad');

				if (edadSemillaElement) {
					edadSemillaElement.textContent = edadSemillaSeleccionada;
				}
			});

			$('.selectpicker' + correlativoFilaNueva).selectpicker({
				dropupAuto: 'true',
				container: 'body',
				size: '5',
				width: '100%',
				style: 'btn-sm btn-info',
				tickIcon: 'fa fa-check'
			});


			restablecerFechas()
			correlativoFilaNueva++;
			jQuery.ajaxSetup({
				async: true
			});

		} else {
			if (!datosAcresBloques[cod_bloque]["habilitar_agregar_fila" + cod_bloque]) {
				grl_mensaje('You must save the current seed first.', '', 'warning');

			}
			if (PorcentajeAcresPrincipal >= 100 || maximoAcresPermitido <= cantidadAcresPrincipal) {
				grl_mensaje('There are no acres available.', 'Reduce from other seeds', 'warning');
			}


			$("#btn_agregar_fila_" + cod_bloque).removeClass("btn-primary");
			$("#btn_agregar_fila_" + cod_bloque).addClass("btn_agrega_fila_desactivada");
		}


	}

	function guardarSemilla(cod_bloque, cod_bloque_implementado, cod_inventario, cod_plantacion, cod_trasplante, acres_usados, porcentaje_acre_usado, idUnicoFila, edadSemillaSeleccionada) {
		jQuery.ajaxSetup({
			async: false
		});
		if ($("#cod_semilla_" + idUnicoFila).val() != '-b' && $("#cod_semilla_" + idUnicoFila).val() != undefined) {

			let cantidadAcresPrincipal = $('#acres_iniciales_' + cod_bloque).text();
			let PorcentajeAcresPrincipal = $('#porcentaje_acres_iniciales_' + cod_bloque).text();
			PorcentajeAcresPrincipal = PorcentajeAcresPrincipal.replace("%", "")
			PorcentajeAcresPrincipal = parseInt(PorcentajeAcresPrincipal);

			if (PorcentajeAcresPrincipal < 100 || !datosAcresBloques[cod_bloque]["habilitar_agregar_fila" + cod_bloque]) {
				recopilarFechasEditables();
				var cantidadMaximaDeAcres = parseFloat(datosAcresBloques[cod_bloque]["acreas_maximo_" + cod_bloque]);
				cantidadAcresPrincipal = parseFloat(cantidadAcresPrincipal);
				porcentaje_acre_usado = 100 - PorcentajeAcresPrincipal;
				acres_usados = parseFloat(datosAcresBloques[cod_bloque]["acreas_maximo_" + cod_bloque]) - cantidadAcresPrincipal;

				acres_usados = parseFloat(acres_usados.toFixed(2));
				edadSemillaSeleccionada = $("#cod_semilla_" + idUnicoFila + ' option:selected').data('edad');

				cod_trasplante = $("#cod_semilla_" + idUnicoFila + ' option:selected').data('cod_trasplante');
				cod_inventario = $("#cod_semilla_" + idUnicoFila + ' option:selected').data('cod_inventario');
				cod_plantacion = $("#cod_semilla_" + idUnicoFila + ' option:selected').data('cod_plantacion');

				let cod_semilla_bloque = farm_registrar_semilla_asociada_a_bloque(cod_bloque, cod_bloque_implementado, cod_inventario, cod_plantacion, cod_trasplante, acres_usados, porcentaje_acre_usado);

				if (cod_semilla_bloque != null && cod_semilla_bloque != 0) {
					datosAcresBloques[cod_bloque]["habilitar_agregar_fila" + cod_bloque] = true;
					// Crear la fila
					var nuevaFila = document.createElement("tr");
					nuevaFila.id = "fila_semilla_" + idUnicoFila;
					// Crear el primer td vacío
					var primerTD = document.createElement("td");
					nuevaFila.appendChild(primerTD);

					var textoSeleccionado = $("#cod_semilla_" + idUnicoFila + ' option:selected').text();

					edadSemillaSeleccionada = $("#cod_semilla_" + idUnicoFila + ' option:selected').data('edad');

					let datoSemillas = textoSeleccionado;
					let edadSemilla = edadSemillaSeleccionada;
					let porcentajeAcresUsados = porcentaje_acre_usado;
					let cantidadAcresUsados = acres_usados;


					var filaAEliminar = document.getElementById("fila_semilla_" + idUnicoFila);

					// Verificamos si la fila existe antes de intentar eliminarla
					if (filaAEliminar) {
						// Utilizamos el método remove() para eliminar la fila
						filaAEliminar.remove();
					} else {}
					// Crear el segundo td con el div

					var segundoTD = document.createElement("td");
					var divSemillaAsociada = document.createElement("div");
					divSemillaAsociada.className = "semilla_asociada_cargada";
					divSemillaAsociada.id = "semilla_cargada_" + idUnicoFila;
					var pNombreSemilla = document.createElement("p");
					pNombreSemilla.textContent = datoSemillas;
					divSemillaAsociada.appendChild(pNombreSemilla);
					segundoTD.appendChild(divSemillaAsociada);
					nuevaFila.appendChild(segundoTD);

					// Crear el tercer td con la clase "edad_semilla_cargada"
					var tercerTD = document.createElement("td");
					tercerTD.className = "edad_semilla_cargada";
					tercerTD.textContent = edadSemilla;
					tercerTD.id = "edad_semilla_" + idUnicoFila;
					nuevaFila.appendChild(tercerTD);

					// Crear el cuarto td con el div de fecha
					var cuartoTD = document.createElement("td");
					var divInputGroupFecha = document.createElement("div");
					divInputGroupFecha.className = "input-group input-group-sm fecha-planeada datetime_ship fecha_registrada_plantaciones_" + cantidadFechasRegistradas;
					var spanAddonFecha = document.createElement("span");
					spanAddonFecha.className = "input-group-addon";
					var spanCalendar = document.createElement("span");
					spanCalendar.className = "fa fa-calendar";
					spanAddonFecha.appendChild(spanCalendar);
					var inputFecha = document.createElement("input");
					inputFecha.type = "text";
					inputFecha.className = "form-control input requerido_harvesting_worksheet";
					inputFecha.id = "fecha_registrada_plantaciones_" + cantidadFechasRegistradas;
					divInputGroupFecha.appendChild(spanAddonFecha);
					divInputGroupFecha.appendChild(inputFecha);
					cuartoTD.appendChild(divInputGroupFecha);
					nuevaFila.appendChild(cuartoTD);

					// Crear el quinto td con los botones y el icono de porcentaje
					var quintoTD = document.createElement("td");
					quintoTD.className = "boto_seleccionable";
					var btnPorcentaje = document.createElement("button");
					btnPorcentaje.className = "btnPorcentajeAcres";
					btnPorcentaje.textContent = porcentajeAcresUsados + "%";
					btnPorcentaje.id = "btn_porcentaje_acres_registrado_" + idUnicoFila;

					btnPorcentaje.setAttribute("onclick", "activarEdicionPorcentaje(" + idUnicoFila + ")");
					// btnPorcentaje.setAttribute("onclick", "pruebaInteraccion()");
					quintoTD.appendChild(btnPorcentaje);
					var inputPorcentaje = document.createElement("input");
					inputPorcentaje.type = "number";
					inputPorcentaje.id = "input_porcentaje_acres_registrado_" + idUnicoFila;
					inputPorcentaje.className = "input_porcentaje_acres";
					inputPorcentaje.min = "0";
					inputPorcentaje.step = "1";
					// inputPorcentaje.max = porcentajeAcresUsados;
					inputPorcentaje.value = porcentajeAcresUsados;
					inputPorcentaje.setAttribute("hidden", true);
					inputPorcentaje.setAttribute("onblur", "salirDelInputPorcentaje(" + cod_bloque_implementado + "," + cod_semilla_bloque + "," + idUnicoFila + "," + porcentajeAcresUsados + "," + cantidadAcresUsados + "," + cod_bloque + ")");
					inputPorcentaje.setAttribute("onkeydown", "detectarTeclasDeSalidaPorcentaje(event," + idUnicoFila + "," + porcentajeAcresUsados + "," + cod_bloque + ")");
					inputPorcentaje.setAttribute("oninput", "detectarDatosEntradaDeTecladosPorcentaje(event," + idUnicoFila + "," + porcentajeAcresUsados + "," + porcentajeAcresUsados + "," + cantidadAcresUsados + "," + cod_bloque + ")");
					quintoTD.appendChild(inputPorcentaje);
					var inputOcultoPorcentaje = document.createElement("input");
					inputOcultoPorcentaje.type = "number";
					inputOcultoPorcentaje.className = "input_oculto";
					inputOcultoPorcentaje.id = "input_cantidad_inicial_porcentaje_acres_registrado_" + idUnicoFila;
					inputOcultoPorcentaje.value = porcentajeAcresUsados;
					quintoTD.appendChild(inputOcultoPorcentaje);
					nuevaFila.appendChild(quintoTD);

					// Crear el sexto td con los botones y el icono de cantidad de semillas
					var sextoTD = document.createElement("td");
					sextoTD.className = "boto_seleccionable";
					var btnCantidadAcresUsados = document.createElement("button");
					btnCantidadAcresUsados.className = "btnCantidadAcresUsados";
					btnCantidadAcresUsados.id = "btn_acres_registrado_" + idUnicoFila;
					btnCantidadAcresUsados.textContent = cantidadAcresUsados;
					btnCantidadAcresUsados.setAttribute("onclick", "activarEdicion(" + idUnicoFila + ")");
					sextoTD.appendChild(btnCantidadAcresUsados);

					var inputCantidadSemillas = document.createElement("input");
					inputCantidadSemillas.type = "number";
					inputCantidadSemillas.className = "input_cantidad_semillas";
					inputCantidadSemillas.min = "0";
					inputCantidadSemillas.step = "0.01";
					inputCantidadSemillas.max = cantidadMaximaDeAcres;
					inputCantidadSemillas.value = cantidadAcresUsados;
					inputCantidadSemillas.id = "input_acres_registrado_" + idUnicoFila;
					inputCantidadSemillas.setAttribute("hidden", true);

					inputCantidadSemillas.setAttribute("onblur", "salirDelInput(" + cod_bloque_implementado + "," + cod_semilla_bloque + "," + idUnicoFila + "," + porcentajeAcresUsados + "," + cantidadAcresUsados + "," + cod_bloque + ")");
					inputCantidadSemillas.setAttribute("onkeydown", "detectarTeclasDeSalida(event," + idUnicoFila + "," + porcentajeAcresUsados + "," + cod_bloque + ")");
					inputCantidadSemillas.setAttribute("oninput", "detectarDatosEntradaDeTeclados(event," + idUnicoFila + "," + cantidadMaximaDeAcres + "," + cantidadAcresUsados + "," + porcentajeAcresUsados + "," + cod_bloque + ")");
					sextoTD.appendChild(inputCantidadSemillas);
					var inputOcultoCantidadAcres = document.createElement("input");
					inputOcultoCantidadAcres.type = "number";
					inputOcultoCantidadAcres.className = "input_oculto";
					inputOcultoCantidadAcres.id = "input_cantidad_inicial_acres_registrado_" + idUnicoFila;
					inputOcultoCantidadAcres.value = cantidadAcresUsados;
					sextoTD.appendChild(inputOcultoCantidadAcres);
					nuevaFila.appendChild(sextoTD);

					// Crear el segundo td con el select de las temporadas
					var septimoTD = document.createElement("td");
					var divFormGroup = document.createElement("div");
					divFormGroup.className = "form-group input-group-sm";
					var select = document.createElement("select");
					select.className = "selectpicker show-menu-arrow";
					select.setAttribute("data-live-search", "true");
					select.setAttribute("title", "Select");
					select.id = "cod_temporada_" + idUnicoFila;
					select.name = "cod_temporada_" + idUnicoFila;
					divFormGroup.appendChild(select);
					septimoTD.appendChild(divFormGroup);
					nuevaFila.appendChild(septimoTD);


					// Crear el séptimo td con el botón de eliminar semilla
					var octavoTD = document.createElement("td");
					octavoTD.className = "boto_seleccionable";
					var botonEliminar = document.createElement("button");
					botonEliminar.className = "btn_eliminar_semilla_editable";
					botonEliminar.setAttribute("onclick", "eliminarSemilla(" + cod_bloque + "," + cod_semilla_bloque + "," + idUnicoFila + "," + cantidadFechasRegistradas + "," + cod_bloque_implementado + "," + cod_inventario + ")");
					var iconoEliminar = document.createElement("i");
					iconoEliminar.className = "fas fa-trash-alt";
					botonEliminar.appendChild(iconoEliminar);
					octavoTD.appendChild(botonEliminar);
					nuevaFila.appendChild(octavoTD);

					document.getElementById("cuerpo_tabla_detalle" + cod_bloque).appendChild(nuevaFila);
					restablecerFechas();
					nuevoCampoDeFecha(cantidadFechasRegistradas, cod_bloque_implementado, cod_semilla_bloque, null)
					cantidadFechasRegistradas++;
					correlativoFilaNueva++;

					datosAcresBloques[cod_bloque]["datos_interno_de_bloque_acres_" + cod_bloque]["acres_" + idUnicoFila] = cantidadAcresUsados;
					datosAcresBloques[cod_bloque]["datos_interno_de_bloque_porcentaje_" + cod_bloque]["porcentaje_" + idUnicoFila] = porcentajeAcresUsados;


					// Listado de las temporadas
					farm_constructor_listado_temporada("cod_temporada_" + idUnicoFila)
					$('.selectpicker').selectpicker('refresh');

					detectarCambioSeleccionTemporada(idUnicoFila, cod_semilla_bloque);

					$("#btn_agregar_fila_" + cod_bloque).addClass("btn-primary");
					$("#btn_agregar_fila_" + cod_bloque).removeClass("btn_agrega_fila_desactivada");
					sumarCantidadesGenerales(cod_bloque);
					registrarCantidades(cod_bloque, idUnicoFila, cod_semilla_bloque, cod_bloque_implementado);
					datosAcresBloques[cod_bloque]["cantidad_semillas_" + cod_bloque] = datosAcresBloques[cod_bloque]["cantidad_semillas_" + cod_bloque] + 1;

				} else {}
			} else {
				grl_mensaje('There are no acres available.', 'Reduce from other seeds', 'warning');

			}
		} else {
			grl_mensaje('There are no seeds selected.', 'Or not available', 'warning');

		}
		jQuery.ajaxSetup({
			async: true
		});

	}

	function detectarCambioSeleccionTemporada(idUnicoFila, cod_semilla_bloque) {

		$('#cod_temporada_' + idUnicoFila).change(function(event) {

			if ($(this).val() != undefined && $(this).val() != null && $(this).val() != '-b') {
				farm_actualizar_temporada_seleccionada_semilla_implementada($(this).val(), cod_semilla_bloque);
			}

		});
	}

	function recopilarFechasEditables() {

		for (let indiceFecha = 0; indiceFecha < correlativoFilaNueva; indiceFecha++) {

			arrFechasEditables["fecha_registrada_plantaciones_" + indiceFecha] = $("#fecha_registrada_plantaciones_" + indiceFecha).val();
		}

	}

	function restablecerFechas() {
		// await sleep(1000); // Pausa la ejecución los milisegundos que se envien como parametro
		for (let indiceFecha = 0; indiceFecha < correlativoFilaNueva; indiceFecha++) {
			if (arrFechasEditables["fecha_registrada_plantaciones_" + indiceFecha] != undefined) {

				$('.fecha_registrada_plantaciones_' + indiceFecha).datetimepicker({
					format: 'MM-DD-YYYY',
					defaultDate: arrFechasEditables["fecha_registrada_plantaciones_" + indiceFecha],
					icons: {
						time: "fas fa-clock",
						date: "fa fa-calendar",
						up: "fa fa-arrow-up",
						down: "fa fa-arrow-down",
						previous: 'fa fa-arrow-left',
						next: 'fa fa-arrow-right',
					}
				}).on('dp.hide', function(datoFecha) {

					// farm_guardar_fecha_seleccionada_plantacion_granja(cod_rotations, datoFecha.date.format('YYYY-MM-DD'));
				});
			}

		}
	}

	// Función para agregar un bloque con valores específicos
	function registrarDatosBloque(cod_bloque, cantidadAcresMaximo, acresUsados) {
		// Verificar si el bloque ya existe, si no, crear un nuevo array vacío
		if (!datosAcresBloques.hasOwnProperty(cod_bloque)) {
			datosAcresBloques[cod_bloque] = [];
		}
		if (!estadoDespliegueBloque.hasOwnProperty(cod_bloque)) {
			estadoDespliegueBloque[cod_bloque] = [];
		}
		acresUsados = parseFloat(acresUsados);
		cantidadAcresMaximo = parseFloat(cantidadAcresMaximo);
		let porcentajeInicial = parseInt((acresUsados / cantidadAcresMaximo) * 100);
		let procentajeDisponible = 100 - porcentajeInicial;

		//Registro de las cantidades de Acres
		datosAcresBloques[cod_bloque]["acreas_maximo_" + cod_bloque] = cantidadAcresMaximo;
		datosAcresBloques[cod_bloque]["acres_usados_inicial_" + cod_bloque] = acresUsados;
		datosAcresBloques[cod_bloque]["acres_disponibles_" + cod_bloque] = cantidadAcresMaximo - acresUsados;
		datosAcresBloques[cod_bloque]["acres_usados_" + cod_bloque] = acresUsados;

		//Registro de los procentajes
		datosAcresBloques[cod_bloque]["porcentaje_inicial_" + cod_bloque] = porcentajeInicial;
		datosAcresBloques[cod_bloque]["porcentaje_usado_inicial_" + cod_bloque] = porcentajeInicial;
		datosAcresBloques[cod_bloque]["porcentaje_disponible_" + cod_bloque] = procentajeDisponible;
		datosAcresBloques[cod_bloque]["porcentaje_usado_" + cod_bloque] = porcentajeInicial;
		datosAcresBloques[cod_bloque]["porcentaje_maximo_" + cod_bloque] = porcentajeInicial;

		datosAcresBloques[cod_bloque]["datos_interno_de_bloque_porcentaje_" + cod_bloque] = [];
		datosAcresBloques[cod_bloque]["datos_interno_de_bloque_acres_" + cod_bloque] = [];

		datosAcresBloques[cod_bloque]["bloque_desplegado" + cod_bloque] = false;
		datosAcresBloques[cod_bloque]["habilitar_agregar_fila" + cod_bloque] = true;

		datosAcresBloques[cod_bloque]["cantidad_semillas_" + cod_bloque] = 0;

	}

	function mostrarModalParaCompletar(cod_bloque_implementado, cod_bloque, cantidad_maxima_acres) {

		$('#cod_bloque_implementado').val(cod_bloque_implementado);
		$('#cod_bloque').val(cod_bloque);
		$('#cantidad_maxima_acres').val(cantidad_maxima_acres);

		$('#modal_completar_bloque').modal('show');
	}

	$('#btn_completar_bloque').click(function(event) {

		jQuery.ajaxSetup({
			async: false
		});
		let cod_bloque_implementado = $('#cod_bloque_implementado').val();
		let cod_bloque = $('#cod_bloque').val();
		let cantidad_maxima_acres = $('#cantidad_maxima_acres').val();

		$('#modal_completar_bloque').modal('hide');

		if (cod_bloque_implementado == 0) {
			cod_bloque_implementado = farm_registrar_bloque_implementado(cod_bloque);
		}

		let respuestaCompletacion = farm_completar_bloque_implementado(cod_bloque_implementado, cod_bloque);

		if (respuestaCompletacion != 1 && respuestaCompletacion != undefined) {
			datosAcresBloques[cod_bloque]["datos_interno_de_bloque_porcentaje_" + cod_bloque] = []
			datosAcresBloques[cod_bloque]["datos_interno_de_bloque_acres_" + cod_bloque] = [];
			sumarCantidadesGenerales(cod_bloque);
			datosAcresBloques[cod_bloque]["habilitar_agregar_fila" + cod_bloque] = true;
			document.getElementById("cuerpo_tabla_detalle" + cod_bloque).innerHTML = "";
			$('#acres_iniciales_' + cod_bloque).text('0.0');
			$('#porcentaje_acres_iniciales_' + cod_bloque).text('0%');
		}
		jQuery.ajaxSetup({
			async: true
		});
	});
</script>

<body>

	<div id="overlay_loading"></div>
	<div id="message_box"></div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="panel-header">
					<h1 class="translate" data-traducir_english="Farm Planting Map" data-traducir_spanish="Mapa de plantación en granja">Farm Planting Map</h1>
				</div>
			</div>
			<div class="col-md-12 clearfix">
				<div class="row">
					<div class="col-md-2">
						<div class="form-group input-group-sm">
							<label for="cod_estados" class="translate" data-traducir_english="State" data-traducir_spanish="Estado">State</label>
							<select class="selectpicker show-menu-arrow requerido" data-live-search="true" title="Select" multiple="multiple" data-actions-box="true" id="cod_estados" name="cod_estados">
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group input-group-sm">
							<label for="cod_granja" class="translate" data-traducir_english="Farm" data-traducir_spanish="Granja">Farm</label>
							<select class="selectpicker show-menu-arrow" data-live-search="true" title="Select" multiple="multiple" data-actions-box="true" id="cod_granja" name="cod_granja">
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group input-group-sm">
							<label for="cod_campo" class="translate" data-traducir_english="Fields" data-traducir_spanish="Granja">Fields</label>
							<select class="selectpicker show-menu-arrow requerido" data-live-search="true" multiple="multiple" data-actions-box="true" title="Select" id="cod_campo" name="cod_campo">
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group input-group-sm">
							<label for="cod_bloques" class="translate" data-traducir_english="Blocks" data-traducir_spanish="Bloques">Blocks</label>
							<select class="selectpicker show-menu-arrow requerido" data-live-search="true" multiple="multiple" data-actions-box="true" title="Select" id="cod_bloques" name="cod_bloques">
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group input-group-sm">
							<label for="cod_bloques_libres" class="translate" data-traducir_english="Unused Blocks" data-traducir_spanish="Bloques sin usar">Unused Blocks</label>
							<select class="selectpicker show-menu-arrow" data-live-search="true" multiple="multiple" data-actions-box="true" title="Select" id="cod_bloques_libres" name="cod_bloques_libres">
							</select>
						</div>
					</div>

					<div class="col-md-1" style=" margin-top: 25px;">
						<button class="btn btn-sm btn-primary translate" data-traducir_english="Filter" data-traducir_spanish="Filtrar" type="button" id="btn_filtrar">Filter</button>
					</div>
				</div>
			</div>
		</div>

	</div>
	<div class="busqueda_contenedor">
		<div class="row" style="min-width:100px;">
			<div class="col-sm-12">
				<div class="panel panel-primary">
					<div class="panel-heading panel_cabecera">
						<h3 class="panel-title translate" data-traducir_english="Farm Planting Map" data-traducir_spanish="Mapa de plantaciones en granja">Farm Planting Map</h3>
					</div>

					<div>
						<table class="table table-condensed display table-striped" id="dev-table">
							<thead>
								<tr class="active info">
									<th width="10%" class="translate" data-traducir_english="Farm" data-traducir_spanish="Granja">Farm</th>
									<th width="8%" class="translate" data-traducir_english="Field" data-traducir_spanish="Campo">Field</th>
									<th width="5%" class="translate" data-traducir_english="Block" data-traducir_spanish="Bloque">Block</th>
									<th width="35%" class="translate" data-traducir_english="Seed" data-traducir_spanish="Semilla">Seed</th>
									<th width="8%" class="translate" data-traducir_english="Percentage" data-traducir_spanish="Porcentaje">Percentage</th>
									<th width="8%" class="translate" data-traducir_english="Used acres" data-traducir_spanish="Acres usados">Used acres</th>
									<th width="8%" class="translate" data-traducir_english="Initial Acres" data-traducir_spanish="Acres iniciales">Initial Acres</th>
									<th width="10%" class="translate" data-traducir_english="Action" data-traducir_spanish="Acciones">Action</th>
								</tr>
							</thead>
							<tbody id="cuerpo_tabla">
								<?php
								if (count($BLOQUES)) {
									$correlativo = 1;
									$correlativoFilaNueva = 1;
									foreach ($BLOQUES as &$bloque) {
										$cod_bloque = $bloque['cod_bloque'];
										$cantidadAcresMaximo = $bloque['ini_acres'];
										$acresUsados = $bloque['use_acres'];
										$cod_bloque_implementado = $bloque['cod_bloque_implementado'] ?? 0;
										$cod_estado = $bloque['cod_estado'];
										$idElemento = $bloque['cod_crop_bloques'];
										$idPrincipalElemento = $bloque['cod_rotations'];
										$idsUnificados = ($bloque['cod_rotations'] . $bloque['cod_crop_bloques']);
								?>
										<tr>
											<td><?php echo  utf8_encode($bloque['nombre_granja']); ?></td>
											<td><?php echo utf8_encode($bloque['nombre_campo']); ?></td>
											<td><?php echo utf8_encode($bloque['nombre_bloque']); ?></td>
											<td>
												<div class="form-gro up input-group-sm">
													<p><?php echo utf8_encode($bloque['nombre_semilla']); ?></p>
												</div>
											</td>
											<td class="">
												<button class="btnPorcentajeAcres botones_no_seleccionables" id="porcentaje_acres_iniciales_<?php echo $cod_bloque ?>"><?php echo $bloque['porcentaje_acres_usados']; ?>%</button>
											</td>

											<td class="">
												<button class="btnCantidadSemillas botones_no_seleccionables" id="acres_iniciales_<?php echo $cod_bloque ?>"><?php echo $bloque['use_acres']; ?></button>
											</td>
											<td id="acres_maximo_<?php echo $cod_bloque ?>"><?php echo ($bloque['ini_acres']); ?></td>
											<td>
												<a title="Scouting - Explorar" onclick="buscarSemillasDeBloque(<?php echo $cod_bloque; ?>)" data-toggle="collapse" href="#collapse<?php echo $cod_bloque; ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-center-left btn-sm btn-success"><i class="fas fa-chevron-down"></i></a>
												<a title="Check" onclick="mostrarModalParaCompletar(<?php echo $cod_bloque_implementado; ?>,<?php echo $cod_bloque; ?>,<?php echo $bloque['ini_acres']; ?>)" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn   btn-right btn-sm btn-warning"><i class="fas fa-check"></i></a>
											</td>
										</tr>
										<tr>
											<td colspan="11" class="no-padding">
												<div id="collapse<?php echo $cod_bloque; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
													<div class="panel-body">
														<div class="row">
															<div class="col-md-12 col-sm-12" id="div_historial<?php echo $cod_bloque; ?>">
																<table class="table display row-border table-responsive" id="tabla_itemschecklist">

																	<thead>
																		<tr class="active info">
																			<th width="10%" class="translate" data-traducir_english="" data-traducir_spanish=""></th>
																			<th width="45%" class="translate" data-traducir_english="Seed" data-traducir_spanish="Semilla">Seed</th>
																			<th width="8%" class="translate" data-traducir_english="Age" data-traducir_spanish="Edad">Age</th>
																			<th width="15%" class="translate" data-traducir_english="P Date" data-traducir_spanish="Ultima entrega">P Date</th>
																			<th width="10%" class="translate" data-traducir_english="Percentage" data-traducir_spanish="Porcentaje">Percentage</th>
																			<th width="10%" class="translate" data-traducir_english="Use Acres" data-traducir_spanish="Acres Usados">Use Acres</th>
																			<th width="10%" class="translate" data-traducir_english="Season" data-traducir_spanish="Temporadas">Season</th>
																			<th width="10%" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Actions</th>
																		</tr>
																	</thead>
																	<tbody id="cuerpo_tabla_detalle<?php echo $cod_bloque; ?>">

																	</tbody>
																</table>
															</div>
															<div class="row nomargin row-add-content" id="div_btn_add_zona">

																<div class="col-xs-12 text-center">

																	<button type="button" id="btn_agregar_fila_<?php echo $cod_bloque; ?>" onclick="agregarNuevaFila(<?php echo $cod_bloque; ?>,<?php echo $cod_bloque_implementado; ?>,<?php echo $cod_estado; ?>); <?php $correlativoFilaNueva++; ?>" class="btn btn-sm btn btn-primary truncated-text btn_add" data-action="true">

																		<i class="fa fa-plus-circle"> </i>

																		<span class="translate" data-traducir_english="Add new child row" data-traducir_spanish="Agregar nueva fila">Add new child row</span>

																	</button>

																</div>

															</div>
														</div>
													</div>
												</div>
											</td>
										</tr>
										<script>
											registrarDatosBloque(<?php echo $cod_bloque; ?>, <?php echo $cantidadAcresMaximo; ?>, <?php echo $acresUsados; ?>);
										</script>
								<?php
										$correlativo++;
										//$correlativoFilaNueva++;
									}
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- MODAL -->
	<div class="modal fade" id="modal_completar_bloque" tabindex="-1" role="dialog" aria-labelledby="modal_confirm_box" aria-hidden="true">
		<div id="inputs_ocultos" hidden>
			<input type="text" name="cod_bloque_implementado" id="cod_bloque_implementado">
			<input type="text" name="cod_bloque" id="cod_bloque">
			<input type="text" name="cantidad_maxima_acres" id="cantidad_maxima_acres">
		</div>
		<div class="modal-dialog">

			<div class="modal-content modal-warning">

				<div class="modal-header">

					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

					<h4 class="modal-title" id="modal_confirm_box"> Block completed? </h4>

				</div>

				<div class="modal-body">

					<div class="row">

						<div class="col-xs-12 msg-box-container">

							<i class="fa fa-exclamation-triangle fa-2x"></i>

							<span> Are you sure you want to complete this block? <br><span style="color: red; font-weight: bold;"> This action cannot be reversed.</span></span>

						</div>

					</div>

				</div>

				<div class="modal-footer">

					<button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Cancel" data-traducir_spanish="Cancelar" data-dismiss="modal">Cancelar</button>

					<button type="button" class="btn btn-sm btn-primary smooth-transition translate" data-traducir_english="To complete" data-traducir_spanish="Completar" data-cod_bloque="0" id="btn_completar_bloque">To complete</button>

				</div>

			</div>

		</div>

	</div>
</body>