<?php
/*
* 	Administración listado de todas las plantaciones en granja que se hayan registrado
* 	@author 		Edwin Olivera
* 	@date 			2024-02-15
*/
session_start();
if (!isset($_SESSION['cod_usuario'])) {
	echo "<script>window.location.href = '" . $_SESSION['úrl_inicio'] . "';</script>"; // Usando JS para redireccionar
	// header('Location: index.php'); // Método anterior (No funcional)
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_inventario.php");
include_once("../../libs/db_classes/db_farms.php");
/*INSTANCIAMIENTOS*/
$tituloPagina = "List of Transplant Ages";

$DB_INV = new db_inventario();
$DB_FARM 	= new db_farms();

$cod_estado = '';
$cod_granja = '';
$cods_campos = '';

$codigosEstados = $_POST['codigosEstados'];
$codigosGranjas = $_POST['codigosGranjas'];
$codigosCampos = $_POST['codigosCampos'];
$codigoBloquesUsados = $_POST['codigoBloquesUsados'];
$codigosBloques = $_POST['codigosBloques'];
$codigosBloquesImplementados = $_POST['codigosBloquesImplementados'];


$fecha_actual = "";
$fechaInicial = "";

$fecha_actual = date('Y-m-d 23:59:00');
$fecha_hace_30_dias = date('Y-m-d 00:00:00', strtotime('-30 days', strtotime($fecha_actual)));

$fechaInicial = $fecha_hace_30_dias;
$fechaFinal = $fecha_actual;
if (isset($_POST['fecha_inicial']) && isset($_POST['fecha_final'])) {
	$fechaInicial = $_POST['fecha_inicial'] . " 00:00:00";
	$fechaFinal = $_POST['fecha_final'] . " 23:59:00";
}



if (!isset($_POST['codigosEstados'])) {
	$codigosEstados = 0;
}

if (!isset($_POST['codigosGranjas'])) {
	$codigosGranjas = 0;
}

if (!isset($_POST['codigosCampos'])) {
	$codigosCampos = 0;
}

if (!isset($_POST['codigoBloquesUsados'])) {
	$codigoBloquesUsados = 0;
}
if (!isset($_POST['codigosBloquesImplementados'])) {
	$codigosBloquesImplementados = 0;
}

$BLOQUES = [];
// echo "</br>";
if ($codigosBloquesImplementados == 0) {

	// echo $codigosBloquesImplementados;
	// echo "</br>";

	// echo $codigoBloquesUsados;
	// echo "</br>";

	$BLOQUES = $DB_FARM->farm_listado_semillas_plantadas_registro_permanente(
		$fechaInicial,
		$fechaFinal,
		$codigosEstados,
		$codigosGranjas,
		$codigosCampos,
		$codigoBloquesUsados
	);
	// echo var_dump($BLOQUES);
	// echo "</br>";

	foreach ($BLOQUES as &$bloque) {
		$datosSemillas = $DB_FARM->farm_listado_semillas_implementadas_no_registro_permanente(
			$bloque["cod_unificacion"],
			$bloque["cod_inventario"],
			$bloque["cod_bloque_implementado"],
			$bloque["edad_semilla_en_plantacion"],
			$bloque["cod_farm"]
		);

		$bloque["estado_de_proceso"] = "Terminated";
		$bloque["clases_estado"] = "badge estado8";
		if (count($datosSemillas)) {
			// echo "</br>----------------------------------</br>";
			// echo "</br> cod_unificacion: " . $bloque["cod_unificacion"];
			// echo "</br> cod_inventario: " . $bloque["cod_inventario"];
			// echo "</br> cod_bloque_implementado: " . $bloque["cod_bloque_implementado"];
			// echo "</br> edad_semilla_en_plantacion: " . $bloque["edad_semilla_en_plantacion"];
			// echo "</br> cod_farm: " . $bloque["cod_farm"];
			// echo "</br>";


			$bloque["estado_de_proceso"] = "In Progress";
			$bloque["clases_estado"] = "badge estado2";
			// echo var_dump($datosSemillas);
			// echo "</br>";
			// echo var_dump($bloque);
			// echo "</br>";
			foreach ($datosSemillas as &$datoSemilla) {
				$bloque["suma_acres_usados"] = $bloque["suma_acres_usados"] + $datoSemilla["acres_usados"];
			}
		}
	}
	// $json_resultado = json_encode($BLOQUES, JSON_PRETTY_PRINT);
	// echo $json_resultado;
} else {
	// echo "</br>";

	$BLOQUES = $DB_FARM->farm_listado_semillas_plantadas_no_completadas(
		$fechaInicial,
		$fechaFinal,
		$codigosGranjas,
		$codigosCampos,
		$codigosBloquesImplementados
	);

	foreach ($BLOQUES as &$bloque) {

		$datosSemillas = $DB_FARM->farm_suma_acres_usados_en_semillas_registradas($bloque["cod_unificacion"], $bloque["cod_inventario"], $bloque["cod_bloque_implementado"], $bloque["edad_semilla_en_plantacion"], $bloque["cod_farm"]);
		$bloque["estado_de_proceso"] = "In Progress";
		$bloque["clases_estado"] = "badge estado2";
		if (count($datosSemillas)) {
			foreach ($datosSemillas as &$datoSemilla) {
				$bloque["suma_acres_usados"] = $bloque["suma_acres_usados"] + $datoSemilla["acres_usados"];
			}
		}
	}
}


// var_dump($BLOQUES_IMPLEMENTADOS);
// var_dump($BLOQUES);
// echo "codigosBloquesImplementados: " . $codigosBloquesImplementados;
// echo "</br>";
// echo "codigosGranjas: ".$codigosGranjas;
// echo "</br>";
// echo "codigosCampos: ".$codigosCampos;
// echo "</br>";
// echo "codigoBloquesUsados: ".$codigoBloquesUsados;
// echo "</br>";
// echo "fechaInicial: " . $fechaInicial;
// echo "</br>";
// echo "fechaFinal: " . $fechaFinal;
// echo "</br>";
// var_dump($BLOQUES);
// $json_resultado = json_encode($BLOQUES, JSON_PRETTY_PRINT);
// echo $json_resultado;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title><?php echo $tituloPagina; ?></title>
	<style>
		.boton_no_seleccionable {
			cursor: default !important;
		}

		.semilla_en_plantacion {
			border: 2px solid blue;
		}

		.semilla_completada {
			border: 2px solid green;
		}


		.semilla_en_plantacion,
		.semilla_completada {
			display: flex;
			align-items: center;
			text-align: center;
			justify-content: center;
		}

		p .semilla_completada,
		p .semilla_en_plantacion {
			text-align: center;

		}
	</style>
</head>

<script type="text/javascript">
	jQuery.ajaxSetup({
		async: false
	});
	grl_overlay_loading('');
	var indicesEstadoUnificados = 0;
	var indicesGranjasUnificados = 0;
	var indicesCamposUnificados = 0;
	var indicesBloquesUnificados = 0;
	var indicesBloquesUsadosUnificados = 0;
	var indicesBloquesImplementados = 0;

	var datosAcresBloques = {};
	var estadoDespliegueBloque = {};

	var valorCantidadInicialDeSemillas = 0;
	var valorCantidadInicialPorcentaje = 0;
	var correlativoFilaNueva = 1;
	var cantidadFechasRegistradas = 0;
	var arrFechasEditables = {};
	$(document).ready(function() {

		$('.selectpicker').selectpicker({
			dropupAuto: 'true',
			container: 'body',
			size: '5',
			width: '100%',
			style: 'btn-sm btn-info',
			tickIcon: 'fa fa-check'
		});
		$('.selectpicker_semillas').selectpicker({
			dropupAuto: 'true',
			container: 'body',
			size: '5',
			width: '100%',
			style: 'btn-sm btn-info',
			tickIcon: 'fa fa-check'
		});
		$('.selectpicker').selectpicker({
			dropupAuto: 'true',
			container: 'body',
			size: '5',
			width: '100%',
			style: 'btn-sm btn-info'
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
		//Habilita los selects para mobile
		if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
			$('.selectpicker').selectpicker('mobile');
		}
		//$('html, body').animate({ scrollTop: 0 }, 0);
		/*
		 * Función que realiza la "busqueda" dentro de la tabla con información
		 */
		(function() {
			'use strict';
			var $ = jQuery;
			$.fn.extend({
				filterTable: function() {
					return this.each(function() {
						$(this).on('keyup', function(e) {
							$('.filterTable_no_results').remove();
							var $this = $(this),
								search = $this.val().toLowerCase(),
								target = $this.attr('data-filters'),
								$target = $(target),
								$rows = $target.find('tbody tr');
							if (search == '') {
								$rows.show();
							} else {
								$rows.each(function() {
									var $this = $(this);
									$this.text().toLowerCase().indexOf(search) === -1 ? $this.hide() : $this.show();
								})
								if ($target.find('tbody tr:visible').size() === 0) {
									var col_count = $target.find('tr').first().find('td').size();
									var no_results = $('<tr class="filterTable_no_results"><td colspan="4">No Results.</td></tr>')
									$target.find('tbody').append(no_results);
								}
							}
						});
					});
				}
			});
			$('[data-action="filter"]').filterTable();
		})(jQuery);

		$(function() {
			// attach table filter plugin to inputs
			$('[data-action="filter"]').filterTable();

			$('.busqueda_contenedor').on('click', '.panel_cabecera span.filter', function(e) {
				var $this = $(this),
					$panel = $this.parents('.panel');

				$panel.find('.panel_cuerpo').slideToggle();
				if ($this.css('display') != 'none') {
					$panel.find('.panel_cuerpo input').focus();
				}
			});
			$('[data-toggle="tooltip"]').tooltip();
		});


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

		// Obtiene la fecha actual
		var fechaTreitaDiasPrevios = new Date();
		// Resta 30 días a la fecha actual
		fechaTreitaDiasPrevios.setDate(fechaTreitaDiasPrevios.getDate() - 30);
		$('#div_fecha_inicial').datetimepicker({
			defaultDate: fechaTreitaDiasPrevios,
			format: 'YYYY-MM-DD',
			maxDate: new Date(),
			icons: {
				time: "fas fa-clock",
				date: "fa fa-calendar",
				up: "fa fa-arrow-up",
				down: "fa fa-arrow-down",
				previous: 'fa fa-arrow-left',
				next: 'fa fa-arrow-right',
			}
		}).on('dp.hide', function(e) {
			$('#div_fecha_final').data("DateTimePicker").minDate(e.date);
		});
		$('#div_fecha_final').datetimepicker({
			minDate: fechaTreitaDiasPrevios,
			defaultDate: new Date(),
			format: 'YYYY-MM-DD',
			// maxDate: new Date(),
			icons: {
				time: "fas fa-clock",
				date: "fa fa-calendar",
				up: "fa fa-arrow-up",
				down: "fa fa-arrow-down",
				previous: 'fa fa-arrow-left',
				next: 'fa fa-arrow-right',
			},

		}).on('dp.hide', function(e) {
			$('#div_fecha_inicial').data("DateTimePicker").maxDate(e.date);
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
					url: 'mod_farms/ui/farm_listado_granjas_plantacion.php',
					type: 'POST',
					dataType: 'html',
					data: {
						fecha_inicial: $('#fecha_inicial').val(),
						fecha_final: $('#fecha_final').val(),
						codigosEstados: indicesEstadoUnificados,
						codigosGranjas: indicesGranjasUnificados,
						codigosCampos: indicesCamposUnificados,
						codigoBloquesUsados: indicesBloquesUsadosUnificados,
						codigosBloques: indicesBloquesUnificados,
						codigosBloquesImplementados: indicesBloquesImplementados,
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
			$('#cods_campos').empty();
			$('#cod_bloques').empty();
			$('#cod_bloques_usados').empty();
		} else {
			indicesEstadoUnificados = 0;
			indicesGranjasUnificados = 0;
			indicesCamposUnificados = 0;
			indicesBloquesUnificados = 0;
			indicesBloquesUsadosUnificados = 0;
			$('#cods_campos').empty();
			$('#cod_bloques').empty();
			$('#cod_bloques_usados').empty();
			indicesBloquesImplementados = 0;
			indicesBloquesUsadosUnificados = 0;
			$('.selectpicker').selectpicker('refresh');
		}

		$("#cod_bloques_implementados").prop("disabled", false).css("color", "black");
		$("#cod_bloques_usados").prop("disabled", false).css("color", "black");

	});

	$('#cod_granja').change(function(event) {
		if ($(this).val() != undefined && $(this).val() != null && $(this).val() != '-b') {

			var indicesSeleccionados = $(this).val().join(); // Esto unirá los elementos del array con una coma por defecto
			indicesGranjasUnificados = indicesSeleccionados.toString(); // Convertir el resultado en una cadena de texto
			indicesGranjasUnificados = indicesGranjasUnificados.replaceAll("-b", "0");
			farm_listado_campo_por_granja("cods_campos", indicesGranjasUnificados, false);
		} else {
			indicesGranjasUnificados = 0;
			indicesCamposUnificados = 0;
			indicesBloquesUnificados = 0;
			indicesBloquesUsadosUnificados = 0;
			$('#cods_campos').empty();
			$('#cod_bloques').empty();
			$('#cod_bloques_usados').empty();
			indicesBloquesImplementados = 0;
			indicesBloquesUsadosUnificados = 0;
			$('.selectpicker').selectpicker('refresh');
		}
		$("#cod_bloques_implementados").prop("disabled", false).css("color", "black");
		$("#cod_bloques_usados").prop("disabled", false).css("color", "black");
	});

	$('#cods_campos').change(function(event) {
		if ($(this).val() != undefined && $(this).val() != null && $(this).val() != '-b') {

			var indicesSeleccionados = $(this).val().join(); // Esto unirá los elementos del array con una coma por defecto
			indicesCamposUnificados = indicesSeleccionados.toString(); // Convertir el resultado en una cadena de texto
			indicesCamposUnificados = indicesCamposUnificados.replaceAll("-b", "0");
			farm_listado_bloques_usados_por_campos("cod_bloques_usados", indicesCamposUnificados, false);
			console.log("indicesCamposUnificados: " + indicesCamposUnificados);
			console.log({
				indicesCamposUnificados
			})
			farm_listado_bloques_implementados_por_campos("cod_bloques_implementados", indicesCamposUnificados, false);

		} else {
			indicesCamposUnificados = 0;
			indicesBloquesUnificados = 0;
			indicesBloquesUsadosUnificados = 0;
			indicesBloquesImplementados = 0;

			$('#cod_bloques').empty();
			$('#cod_bloques_usados').empty();
			$('#cod_bloques_implementados').empty();
			$('.selectpicker').selectpicker('refresh');
		}
		$("#cod_bloques_implementados").prop("disabled", false).css("color", "black");
		$("#cod_bloques_usados").prop("disabled", false).css("color", "black");
	});

	$('#cod_bloques_usados').change(function(event) {
		if ($(this).val() != undefined && $(this).val() != null && $(this).val() != '-b') {

			var indicesSeleccionados = $(this).val().join(); // Esto unirá los elementos del array con una coma por defecto
			indicesBloquesUsadosUnificados = indicesSeleccionados.toString(); // Convertir el resultado en una cadena de texto
			indicesBloquesUsadosUnificados = indicesBloquesUsadosUnificados.replaceAll("-b", "0");
			$("#cod_bloques_implementados").prop("disabled", true).css("color", "gray");
			indicesBloquesImplementados = 0;

		} else {
			indicesBloquesUsadosUnificados = "";
			$("#cod_bloques_implementados").prop("disabled", false).css("color", "black");

		}
		$('#cod_bloques_implementados').val(null);

		$('.selectpicker').selectpicker('refresh');
	});

	$('#cod_bloques_implementados').change(function(event) {
		if ($(this).val() != undefined && $(this).val() != null && $(this).val() != '-b') {

			var indicesSeleccionados = $(this).val().join(); // Esto unirá los elementos del array con una coma por defecto
			indicesBloquesImplementados = indicesSeleccionados.toString(); // Convertir el resultado en una cadena de texto
			indicesBloquesImplementados = indicesBloquesImplementados.replaceAll("-b", "0");
			$("#cod_bloques_usados").prop("disabled", true).css("color", "gray");
			indicesBloquesUsadosUnificados = 0;

		} else {
			indicesBloquesImplementados = "";
			$("#cod_bloques_usados").prop("disabled", false).css("color", "black");
		}

		$('#cod_bloques_usados').val(null);
		$('.selectpicker').selectpicker('refresh');
	});

	function buscarSemillasDeBloque(identificadorFila,
		cod_bloque,
		codigos_registros,
		nombre_bloque_usado,
		nombre_campo,
		cod_unificacion,
		cod_semilla,
		cod_bloque_implementado,
		edad,
		cod_farm,
		codigos_bloque, codigos_bloque_implementado) {

		document.getElementById("cuerpo_tabla_detalle" + identificadorFila).innerHTML = "";

		if (!datosAcresBloques[identificadorFila]["bloque_desplegado" + identificadorFila]) {

			datosAcresBloques[identificadorFila]["bloque_desplegado" + identificadorFila] = true;
			let listadoSemillasAsociadas = [];
			listadoSemillasAsociadas = farm_listado_semillas_registradas_completadas(
				codigos_registros,
				cod_unificacion,
				cod_semilla,
				cod_bloque_implementado,
				edad,
				cod_farm,
				codigos_bloque,
				codigos_bloque_implementado
			)
			console.log({
				listadoSemillasAsociadas
			});
			if (listadoSemillasAsociadas != null && listadoSemillasAsociadas.length > 0) {
				document.getElementById("cuerpo_tabla_detalle" + identificadorFila).innerHTML = "";
				datosAcresBloques[identificadorFila]["datos_interno_de_bloque_porcentaje_" + identificadorFila] = [];
				datosAcresBloques[identificadorFila]["datos_interno_de_bloque_acres_" + cod_bloque] = [];
				listadoSemillasAsociadas.forEach(function(semillaAsociada) {
					/**
					ini_acres,:"0.33"
					acres_disponibles,:"0.23"
					acres_usados,:"0.05"
					cod_bloque,:"376"
					cod_semilla_bloque,:"1"
					cod_bloque_implementado,:"1"
					cod_inventario,:"8"
					cod_plantacion,:"5168"
					cod_semilla,:"8"
					cod_trasplante,:"6"
					edad,:"3"
					fecha_entrega,:"2023-07-14 06:00:00"
					fecha_inicial,:"2023-09-14 00:00:00"
					fecha_plantacion,:"31-03-2024"
					fecha_plantacion_original,:"2024-03-31 23:20:23"
					nombre_semilla,:"BOK CHOI - Joi Choi"
					numero_orden,:"11332"
					numero_ticket,:"59051"
					porcentaje_acre_usado,:"15.15"
					 * 1 - Etiqueta con los siguientes datos: Nombre de la semilla, fecha de plantacion, numero de orden, numero de etiqueta
					 * 2 - Etiqueta para la edad
					 * 3 - Campo de selección de fecha, con la fecha registrada como valor inicial
					 * 4 - Porcentaje usado
					 * 5 - Acres usados
					 */

					let idUnicoFila = cod_bloque + "" + correlativoFilaNueva;
					let cod_semilla_bloque = semillaAsociada["cod_semilla_bloque"];
					let estado_completacion = semillaAsociada["completada"];
					let cod_bloque_implementado = semillaAsociada["cod_bloque_implementado"];
					let cod_inventario = semillaAsociada["cod_inventario"];
					// Valores a almacenar en variables
					var porcentajeAcresUsados = semillaAsociada["porcentaje_acre_usado"];
					var edadSemilla = semillaAsociada["edad"];
					var cantidadMaximaDeAcres = semillaAsociada["ini_acres"];
					var cantidadAcresUsados = semillaAsociada["acres_usados"];
					let datoSemillas = semillaAsociada["nombre_semilla"] + "|" + semillaAsociada["fecha_final_plantacion"] + "|" + semillaAsociada["numero_orden"] + "-" + semillaAsociada["numero_ticket"];

					nombre_campo = semillaAsociada["field"];
					nombre_bloque_usado = semillaAsociada["nombre_bloque_usado"];

					// Crear la fila
					var nuevaFila = document.createElement("tr");
					nuevaFila.id = "fila_semilla_" + idUnicoFila;
					// Crear el primer td vacío
					// var primerTD = document.createElement("td");
					// nuevaFila.appendChild(primerTD);

					// Crear el el td del nombre del campo
					var tdNombreCampo = document.createElement("td");
					tdNombreCampo.className = "nombre_campo";
					tdNombreCampo.textContent = nombre_campo;
					tdNombreCampo.id = "nombre_campo_registrado_" + idUnicoFila;
					nuevaFila.appendChild(tdNombreCampo);

					// Crear el cuarto td con el div de fecha
					var tdNombreBloque = document.createElement("td");
					tdNombreBloque.className = "nombre_bloque_registrado";
					tdNombreBloque.textContent = nombre_bloque_usado;
					tdNombreBloque.id = "nombre_bloque_usado_" + idUnicoFila;
					nuevaFila.appendChild(tdNombreBloque);


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
					cuartoTD.className = "fecha_registrada";
					cuartoTD.textContent = semillaAsociada["fecha_plantacion"];
					cuartoTD.id = "fecha_registrada_plantaciones_" + cantidadFechasRegistradas;
					nuevaFila.appendChild(cuartoTD);


					// Crear el quinto td con los botones y el icono de porcentaje
					var quintoTD = document.createElement("td");
					quintoTD.className = "boton_no_seleccionable";
					var btnPorcentaje = document.createElement("button");
					btnPorcentaje.className = "btnPorcentajeAcres boton_no_seleccionable";
					btnPorcentaje.textContent = porcentajeAcresUsados + "%";
					btnPorcentaje.id = "btn_porcentaje_acres_registrado_" + idUnicoFila;

					// btnPorcentaje.setAttribute("onclick", "activarEdicionPorcentaje(" + idUnicoFila + ")");
					// btnPorcentaje.setAttribute("onclick", "pruebaInteraccion()");
					quintoTD.appendChild(btnPorcentaje);
					var inputPorcentaje = document.createElement("input");
					inputPorcentaje.type = "number";
					inputPorcentaje.id = "input_porcentaje_acres_registrado_" + idUnicoFila;
					inputPorcentaje.className = "input_porcentaje_acres";
					inputPorcentaje.min = "0";
					inputPorcentaje.step = "1";
					inputPorcentaje.value = porcentajeAcresUsados;
					inputPorcentaje.setAttribute("hidden", true);
					quintoTD.appendChild(inputPorcentaje);
					nuevaFila.appendChild(quintoTD);

					// Crear el sexto td con los botones y el icono de cantidad de semillas
					var sextoTD = document.createElement("td");
					sextoTD.className = "boton_no_seleccionable";
					var btnCantidadAcresUsados = document.createElement("button");
					btnCantidadAcresUsados.className = "btnCantidadAcresUsados boton_no_seleccionable";
					btnCantidadAcresUsados.id = "btn_acres_registrado_" + idUnicoFila;
					btnCantidadAcresUsados.textContent = cantidadAcresUsados;
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
					nuevaFila.appendChild(sextoTD);

					// Crear el TD de la columna del estado de completación
					var tdCompletacion = document.createElement("td");
					var spanMensajeCompletacion = document.createElement("span");
					spanMensajeCompletacion.id = "estado_completacion_" + idUnicoFila;
					var pEstadoSemilla = document.createElement("p");
					if (estado_completacion == 1) {

						// spanMensajeCompletacion.className = "semilla_completada";
						spanMensajeCompletacion.className = "badge estado8";
						spanMensajeCompletacion.textContent = "Terminated";

					} else {
						// spanMensajeCompletacion.className = "semilla_en_plantacion";
						spanMensajeCompletacion.className = "badge estado2";
						spanMensajeCompletacion.textContent = "In Progress";
					}


					// spanMensajeCompletacion.appendChild(pEstadoSemilla);
					tdCompletacion.appendChild(spanMensajeCompletacion);
					nuevaFila.appendChild(tdCompletacion);

					document.getElementById("cuerpo_tabla_detalle" + identificadorFila).appendChild(nuevaFila);
					cantidadFechasRegistradas++;
					correlativoFilaNueva++;

					datosAcresBloques[identificadorFila]["datos_interno_de_bloque_porcentaje_" + identificadorFila]["porcentaje_" + idUnicoFila] = porcentajeAcresUsados;
					datosAcresBloques[identificadorFila]["datos_interno_de_bloque_acres_" + identificadorFila]["acres_" + idUnicoFila] = cantidadAcresUsados;
					datosAcresBloques[identificadorFila]["cantidad_semillas_" + identificadorFila] = datosAcresBloques[identificadorFila]["cantidad_semillas_" + identificadorFila] + 1;
				});
			}
		} else {
			datosAcresBloques[identificadorFila]["bloque_desplegado" + identificadorFila] = false;
			datosAcresBloques[identificadorFila]["habilitar_agregar_fila" + identificadorFila] = true;
		}
	}


	// Función para agregar un bloque con valores específicos
	function registrarDatosBloque(identificadorFila, cantidadAcresMaximo, acresUsados) {
		// Verificar si el bloque ya existe, si no, crear un nuevo array vacío
		if (!datosAcresBloques.hasOwnProperty(identificadorFila)) {
			datosAcresBloques[identificadorFila] = [];
		}
		if (!estadoDespliegueBloque.hasOwnProperty(identificadorFila)) {
			estadoDespliegueBloque[identificadorFila] = [];
		}
		acresUsados = parseFloat(acresUsados);
		cantidadAcresMaximo = parseFloat(cantidadAcresMaximo);
		let porcentajeInicial = parseInt((acresUsados / cantidadAcresMaximo) * 100);
		let procentajeDisponible = 100 - porcentajeInicial;

		//Registro de las cantidades de Acres
		datosAcresBloques[identificadorFila]["acreas_maximo_" + identificadorFila] = cantidadAcresMaximo;
		datosAcresBloques[identificadorFila]["acres_usados_inicial_" + identificadorFila] = acresUsados;
		datosAcresBloques[identificadorFila]["acres_disponibles_" + identificadorFila] = cantidadAcresMaximo - acresUsados;
		datosAcresBloques[identificadorFila]["acres_usados_" + identificadorFila] = acresUsados;

		//Registro de los procentajes
		datosAcresBloques[identificadorFila]["porcentaje_inicial_" + identificadorFila] = porcentajeInicial;
		datosAcresBloques[identificadorFila]["porcentaje_usado_inicial_" + identificadorFila] = porcentajeInicial;
		datosAcresBloques[identificadorFila]["porcentaje_disponible_" + identificadorFila] = procentajeDisponible;
		datosAcresBloques[identificadorFila]["porcentaje_usado_" + identificadorFila] = porcentajeInicial;
		datosAcresBloques[identificadorFila]["porcentaje_maximo_" + identificadorFila] = porcentajeInicial;

		datosAcresBloques[identificadorFila]["datos_interno_de_bloque_porcentaje_" + identificadorFila] = [];
		datosAcresBloques[identificadorFila]["datos_interno_de_bloque_acres_" + identificadorFila] = [];

		datosAcresBloques[identificadorFila]["bloque_desplegado" + identificadorFila] = false;
		datosAcresBloques[identificadorFila]["habilitar_agregar_fila" + identificadorFila] = true;

		datosAcresBloques[identificadorFila]["cantidad_semillas_" + identificadorFila] = 0;

	}
</script>

<body>

	<div id="overlay_loading"></div>
	<div id="message_box"></div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="panel-header">
					<h1 class="translate" data-traducir_english="List of Transplant Ages" data-traducir_spanish="Listado de Edades">List of Transplant Ages</h1>
				</div>
			</div>
			<!-- Fila #1 -->
			<div class="row">
				<div class="col-md-2 form-group input-group-sm">
					<label for="fecha_sumar_restar" class="translate" data-traducir_english="Initial date" data-traducir_spanish="Fecha inicial">Fecha inicial</label>
					<div class='input-group input-group-sm fecha-planeada' id='div_fecha_inicial'>
						<span class="input-group-addon">
							<span class="fa fa-calendar"></span>
						</span>
						<input type='text' class="form-control" id="fecha_inicial" />
					</div>
				</div>
				<div class="col-md-2 form-group input-group-sm">
					<label for="fecha_sumar_restar" class="translate" data-traducir_english="Final date" data-traducir_spanish="Fecha final">Final date</label>
					<div class='input-group input-group-sm fecha-planeada' id='div_fecha_final'>
						<span class="input-group-addon">
							<span class="fa fa-calendar"></span>
						</span>
						<input type='text' class="form-control" id="fecha_final" />
					</div>
				</div>

			</div>
			<!-- Fila #2 -->
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
						<label for="cods_campos" class="translate" data-traducir_english="Fields" data-traducir_spanish="Granja">Fields</label>
						<select class="selectpicker show-menu-arrow" data-live-search="true" multiple="multiple" data-actions-box="true" title="Select" id="cods_campos" name="cods_campos">
						</select>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group input-group-sm">
						<label for="cod_bloques_usados" class="translate" data-traducir_english="Terminated" data-traducir_spanish="Bloques completados">Terminated</label>
						<select class="selectpicker show-menu-arrow" data-live-search="true" multiple="multiple" data-actions-box="true" title="Select" id="cod_bloques_usados" name="cod_bloques_usados">
						</select>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group input-group-sm">
						<label for="cod_bloques_implementados" class="translate" data-traducir_english="In Progress" data-traducir_spanish="Bloques en progreso">In Progress</label>
						<select class="selectpicker show-menu-arrow" data-live-search="true" multiple="multiple" data-actions-box="true" title="Select" id="cod_bloques_implementados" name="cod_bloques_implementados">
						</select>
					</div>
				</div>
				<!-- Botón de Filtrar -->
				<div class="col-md-2" style=" margin-top: 25px;">
					<button class="btn btn-sm btn-primary translate" data-traducir_english="Filter" data-traducir_spanish="Filtrar" type="button" id="btn_filtrar">Filter</button>
				</div>
			</div>

		</div>
	</div>
	<div class="busqueda_contenedor">
		<div class="row" style="min-width:100px;">
			<div class="col-sm-12">
				<div class="panel panel-primary" style="overflow:auto;">
					<div class="panel-heading panel_cabecera">
						<h3 class="panel-title translate" data-traducir_english="List of Transplant Ages" data-traducir_spanish="Listado de plantaciones en granjas">List of Transplant Ages</h3>
						<div class="pull-right">
							<span class="clickable filter" data-toggle="tooltip" title="Realizar busqueda" data-container="body">
								<i class="fa fa-search"></i>
							</span>
						</div>
					</div>
					<div class="panel-body panel_cuerpo input-group-sm">
						<input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Search" />
					</div>
					<div style="overflow-x:auto;">
						<table class="table table-condensed display table-striped" id="dev-table">
							<thead>
								<tr class="active info">
									<th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">No.</th>
									<th width="10%" class="translate" data-traducir_english="Farm" data-traducir_spanish="Granja">Farm</th>
									<th width="8%" class="translate" data-traducir_english="State" data-traducir_spanish="Estado">State</th>
									<th width="35%" class="translate" data-traducir_english="Seed" data-traducir_spanish="Semillas">Seed</th>
									<th width="7%" class="translate" data-traducir_english="Age" data-traducir_spanish="Edad">Age</th>
									<th width="10%" class="translate" data-traducir_english="Total Acres" data-traducir_spanish="Acres totales">Total Acres</th>
									<th width="10%" class="translate" data-traducir_english="Status" data-traducir_spanish="Proceso">Status</th>
									<th width="10%" class="translate" data-traducir_english="Action" data-traducir_spanish="Acciones">Action</th>
								</tr>
							</thead>
							<tbody id="cuerpo_tabla">
								<?php
								$sumaAcres = 1;
								if (count($BLOQUES)) {
									$semillaActual = $BLOQUES[0]["cod_semilla"];
									$edadActual = $BLOQUES[0]["edad"];
									$cantidadAcresActuales = $BLOQUES[0]["use_acres"];
									$sumaAcres = $sumaAcres + $cantidadAcresActuales;
									$correlativo = 1;
									$identificadorFila = 0;
									foreach ($BLOQUES as &$bloque) {
										if ($semillaActual == $bloque["cod_semilla"] && $edadActual == $bloque["edad"]) {
											$cantidadAcresActuales = $cantidadAcresActuales + $bloque["use_acres"];
										} else {
											$edadActual = $bloque["edad"];
											$semillaActual = $bloque["cod_semilla"];
											$cantidadAcresActuales = $bloque["use_acres"];
										}
										if (isset($bloque['cod_registro_semilla_implementada'])) {

											$cod_registro_semilla_implementada = $bloque['cod_registro_semilla_implementada'];
											$identificadorFila = $bloque['cod_registro_semilla_implementada'];
										} else {
											$cod_registro_semilla_implementada = 0;
											$identificadorFila = $correlativo;
										}

										if (isset($bloque['codigos_registros'])) {

											$codigos_registros = $bloque['codigos_registros'];
										} else {
											$codigos_registros = 0;
										}
										// $codigos_registros = $bloque['codigos_registros'];

										$nombre_bloque_usado = $bloque['nombre_bloque_usado'];
										$field = $bloque['field'];

										//$semillaActual = $bloque["cod_semilla"];
										// $edadActual = $bloque["edad"];
										// $cantidadAcresActuales = $bloque["use_acres"];

										$idSemilla = $bloque['cod_rotations'];
										$edad = $bloque['edad_semilla_en_plantacion'];
										$cod_farm = $bloque['cod_farm'];

										$idElemento = $bloque['cod_crop_bloques'];
										$idPrincipalElemento = $bloque['cod_rotations'];

										$cod_unificacion = $bloque['cod_unificacion'];
										$cod_semilla = $bloque['cod_inventario'];
										$cod_bloque_implementado = $bloque['cod_bloque_implementado'];
										$codigos_bloque_implementado = $bloque['codigos_bloque_implementado'];


										$idsUnificados = ($bloque['cod_rotations'] . $bloque['cod_crop_bloques']);
								?>
										<tr>
											<td><?php echo $correlativo; ?></td>
											<td><?php echo  utf8_encode($bloque['granja_asociada']); ?></td>
											<td><?php echo  utf8_encode($bloque['nombre_estado']); ?></td>
											<td><?php echo  utf8_encode($bloque['datos_semilla']); ?></td>
											<td><?php echo $edad; ?></td>
											<td><?php echo $bloque['suma_acres_usados']; ?></td>
											<td> <span class="<?php echo $bloque['clases_estado']; ?>"> <?php echo $bloque['estado_de_proceso']; ?></span></td>

											<td>
												<a title="Expand - Expandir" onclick="buscarSemillasDeBloque(
													<?php echo $identificadorFila; ?>,
													<?php echo $cod_registro_semilla_implementada; ?>,
												'<?php echo $codigos_registros; ?>',
												'<?php echo $nombre_bloque_usado; ?>',
												'<?php echo $field; ?>',
												<?php echo $cod_unificacion; ?>,
												<?php echo  $cod_semilla; ?>,
												<?php echo  $cod_bloque_implementado; ?>,
												<?php echo  $edad; ?>,<?php echo  $cod_farm; ?>,
												'<?php echo  $codigosBloquesImplementados; ?>',
												'<?php echo  $codigos_bloque_implementado; ?>'
												)" data-toggle="collapse" href="#collapse<?php echo $identificadorFila; ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-center-left btn-sm btn-success"><i class="fas fa-chevron-down"></i></a>
											</td>
											<!-- <td>
												<div class="material-switch pull-right">
													<input class="checkbox" id="checkbox_<?php echo $idSemilla . "-" . $idElemento; ?>" data-id="<?php echo $idSemilla . "-" . $idElemento; ?>" name="checkbox_<?php echo $idSemilla . "-" . $idElemento; ?>" type="checkbox" <?php echo ($bloque['completado'] == 1 ? 'checked="checked"' : ''); ?> />
													<label for="checkbox_<?php echo $idSemilla . "-" . $idElemento; ?>" class=""></label>
												</div>
											</td> -->
										</tr>
										<tr>
											<td colspan="11" class="no-padding">
												<div id="collapse<?php echo $identificadorFila; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
													<div class="panel-body">
														<div class="row">
															<div class="col-md-12 col-sm-12" id="div_historial<?php echo $identificadorFila; ?>">
																<table class="table display row-border table-responsive" id="tabla_itemschecklist">

																	<thead>
																		<tr class="active info">
																			<!-- <th width="10%" class="translate" data-traducir_english="" data-traducir_spanish=""></th> -->
																			<th width="5%" class="translate" data-traducir_english="Field" data-traducir_spanish="Campo">Field</th>
																			<th width="5%" class="translate" data-traducir_english="Block" data-traducir_spanish="Bloque">Block</th>
																			<th width="45%" class="translate" data-traducir_english="Seed" data-traducir_spanish="Semilla">Seed</th>
																			<th width="8%" class="translate" data-traducir_english="Age" data-traducir_spanish="Edad">Age</th>
																			<th width="15%" class="translate" data-traducir_english="P Date" data-traducir_spanish="Ultima entrega">P Date</th>
																			<th width="7%" class="translate" data-traducir_english="Percentage" data-traducir_spanish="Porcentaje">Percentage</th>
																			<th width="6%" class="translate" data-traducir_english="Use Acres" data-traducir_spanish="Acres Usados">Use Acres</th>
																			<th width="17%" class="translate" data-traducir_english="State" data-traducir_spanish="Estado">State</th>
																			<!-- <th width="10%" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Actions</th> -->
																		</tr>
																	</thead>
																	<tbody id="cuerpo_tabla_detalle<?php echo $identificadorFila; ?>" style="border-bottom: 2px solid #32a1ce;">

																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</div>
											</td>
										</tr>
										<script>
											registrarDatosBloque(<?php echo $identificadorFila; ?>, 0, 0);
										</script>
								<?php
										$correlativo++;
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
</body>