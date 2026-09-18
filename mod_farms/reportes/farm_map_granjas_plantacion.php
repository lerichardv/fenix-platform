<?php
/*
* 	Descarga del farming planting map
* 	@author 		Dan Urquia
* 	@date 			2025-03-06
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

		.boton_desc {
			width: 100%;
			font-size: 20px;
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

		// INICIALIZANDO LAS FECHAS
		// Obtiene la fecha actual
		var fechaTreitaDiasPrevios = new Date();
		var fechaSieteDias = new Date();
		// Resta 30 días a la fecha actual
		fechaTreitaDiasPrevios.setDate(fechaTreitaDiasPrevios.getDate() - 30);
		fechaSieteDias.setDate(fechaSieteDias.getDate() + 7);
		$('#div_fecha_inicial').datetimepicker({
			defaultDate: fechaTreitaDiasPrevios,
			format: 'YYYY-MM-DD',
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
			defaultDate: fechaSieteDias,
			format: 'YYYY-MM-DD',
			icons: {
				time: "fas fa-clock",
				date: "fa fa-calendar",
				up: "fa fa-arrow-up",
				down: "fa fa-arrow-down",
				previous: 'fa fa-arrow-left',
				next: 'fa fa-arrow-right',
			}
		}).on('dp.hide', function(e) {
			$('#div_fecha_inicial').data("DateTimePicker").maxDate(e.date);
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
					url: 'mod_farms/ui/farm_map_granjas_plantacion.php',
					type: 'POST',
					dataType: 'html',
					data: {
						codigosEstados: indicesEstadoUnificados,
						codigosGranjas: indicesGranjasUnificados,
						codigosCampos: indicesCamposUnificados,
						codigoBloquesLibres: indicesBloquesLibreUnificados,
						codigosBloques: indicesBloquesUnificados,
						fechaInicial: $('#fecha_inicial').val(),
						fechaFinal: $('#fecha_inicial').val(),
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
 
				  var url = "../../mod_farms/reportes/farm_map_granjas_plantacion_excel.php?x1=" + $('#fecha_inicial').val() + "&x2=" + $('#fecha_final').val() + "&x3=" + $('#cod_estados').val() + "&x4=" + $('#cod_granja').val() + "&x5=" + $('#cod_campo').val() + "&x6=" + $('#cod_bloques').val();
		          $(location).attr('href',url);

			/*grl_overlay_loading('');
			$('#modal_loading').modal('hide');
			$('#modal_loading').on('hidden.bs.modal', function() {
			
				$.ajax({
					url: 'mod_farms/reportes/farm_map_granjas_plantaciones_excel.php',
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
			});*/
		} else {
			grl_mensaje('You must fill out all marked fields', 'Please check', 'warning');
			jQuery.ajaxSetup({
				async: true
			});
		}

	});


	$( "#btn_excel" ).click(function() {
		alert("Test");
		//var url = "../../mod_admin_usuarios/reportes/usu_reporte_detallado_ingresos_excel.php?x1=<?php echo $fecha_inicial; ?>&x2=<?php echo $fecha_final; ?>&x3=<?php echo $cods_user; ?>";
		//$(location).attr('href',url);
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

				<div class="col-md-2 form-group input-group-sm">
						<label for="fecha_sumar_restar" class="translate" data-traducir_english="Initial date" data-traducir_spanish="Fecha inicial">Initial date</label>
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

					 

					<div class="d-grid gap-2">
						<button class="btn btn-sm btn-success translate boton_desc" data-traducir_english="Download" data-traducir_spanish="Descargar" type="button" id="btn_filtrar">Download</button>
					</div>
				</div>
			</div>
		</div>

	</div>
	
</body>