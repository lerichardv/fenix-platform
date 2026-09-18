<?php
/*
* 	Registro de información de los transplantes,
* 	@author 		Edwin Olivera
* 	@date 			2023-09-17
*/
session_start();
if (!isset($_SESSION['cod_usuario'])) {
	echo "<script>window.location.href = '" . $_SESSION['úrl_inicio'] . "';</script>"; // Usando JS para redireccionar
	// header('Location: index.php'); // Método anterior (No funcional)
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_inventario.php");
/*INSTANCIAMIENTOS*/
$DB_INV = new db_inventario();
$cod_plantacion = 0;
$cod_trasplante = 0;
$numero_ticket = 0;
$cod_granjas_usuario = str_replace(['[', '"', ']'], '', $_SESSION['cod_info_empresa']);
$datosGeneralesTrasplante = [];
$datosPlantacionEncontrados = false;
$datosTrasplantacionEncontrados = false;

if (isset($_POST['numero_ticket'])) {
	$numero_ticket = $_POST['numero_ticket'];

	$datosGeneralesTrasplante = $DB_INV->inv_buscar_trasplante($numero_ticket);

	// var_dump($datosGeneralesTrasplante);
	if (count($datosGeneralesTrasplante) > 0) {
		$datosPlantacionEncontrados = true;
	}
	// var_dump($datosGeneralesTrasplante);
	// var_dump($datosPlantacionEncontrados);
}

$TOTAL = $DB_INV->inv_total_semillas_por_granjas($cod_granjas_usuario);

$cod_semilla = 0;
if (isset($_POST['cod_semilla'])) {
	$cod_semilla = $_POST['cod_semilla'];
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>Nueva plantación</title>
	<style>
		.input_cantidad_semillas {
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
	</style>
</head>

<script type="text/javascript">
	jQuery.ajaxSetup({
		async: false
	});
	//Variables para cuando se ingrese desde el listado de plantaciones
	var datosExtraidos;
	var pNumeroTicket = 0;
	var actualizarPlantancion = false;
	var plantacionCompletada = false;
	// --------------------------------------------------------------
	// Clases necesaria: dentro-de-rango, fuera-de-rango

	var trCuerpoTablaSemillasEditables;

	var arrDatosGeneralPlantancion = {};

	var arrDatosDePlantas = {};
	var arrIndiceDatosSemillas = [];

	var indexGeneralSemillasEditables = 0;
	var indexInternoSemillasEditables = 0;

	var fecha_hoy = new Date(<?php echo time() * 1000; ?>);
	var hoy = (fecha_hoy.getMonth() + 1) + "/" + fecha_hoy.getDate() + "/" + fecha_hoy.getFullYear();
	var hoy_default = fecha_hoy.getFullYear() + "-" + (fecha_hoy.getMonth() + 1) + "-" + fecha_hoy.getDate();
	var valorInicialCaptado = 0;
	var guardadoHabilitado = false;
	var guardadoHabilitadoNuevo = false;
	var cantidadDentroDeRango = true;
	var germinacionAutomatica = false;
	grl_overlay_loading('');

	$(document).ready(function() {
		trCuerpoTablaSemillasEditables = document.getElementById("cuerpo_tabla_editable")
		pNumeroTicket = '<?php echo $numero_ticket; ?>';

		if (pNumeroTicket != 0) {
			datosExtraidos = inv_buscar_trasplante(pNumeroTicket);
		}
		var cantidad_total_de_semillas = $("#total_semillas")
		cantidad_total_de_semillas.val(0);
		codigo_inventario_semilla = 0;
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
		$('.selectpicker_numero_orden').selectpicker({
			dropupAuto: 'true',
			container: 'body',
			size: '5',
			width: '100%',
			style: 'btn-sm btn-info',
			tickIcon: 'fa fa-check'
		});
		//Habilita los selects para mobile
		if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
			$('.selectpicker').selectpicker('mobile');
		}
		$('.datetime').datetimepicker({
			minDate: new Date(),
			format: 'MM-DD-YYYY',
			icons: {
				time: "fas fa-clock",
				date: "fa fa-calendar",
				up: "fa fa-arrow-up",
				down: "fa fa-arrow-down",
				previous: 'fa fa-arrow-left',
				next: 'fa fa-arrow-right',
			}
		});
		$('.datetime_delivery').datetimepicker({
			defaultDate: hoy,

			format: 'MM-DD-YYYY',
			icons: {
				time: "fas fa-clock",
				date: "fa fa-calendar",
				up: "fa fa-arrow-up",
				down: "fa fa-arrow-down",
				previous: 'fa fa-arrow-left',
				next: 'fa fa-arrow-right',
			}
		});
		$('.datetime_ship').datetimepicker({
			defaultDate: hoy,
			format: 'MM-DD-YYYY',
			icons: {
				time: "fas fa-clock",
				date: "fa fa-calendar",
				up: "fa fa-arrow-up",
				down: "fa fa-arrow-down",
				previous: 'fa fa-arrow-left',
				next: 'fa fa-arrow-right',
			}
		}).on('dp.hide', function(e) {
			$('.datetime_delivery').data("DateTimePicker").minDate(moment(e.date));
			$('.datetime_delivery').data("DateTimePicker").date(moment(e.date));
		});


		$(".fecha_inicial").datetimepicker({
			locale: moment.locale('en', {
				week: {
					dow: 0
				}
			}),
			minDate: hoy,
			format: 'MM-DD-YYYY',
			defaultDate: hoy,
			icons: {
				time: "fa fa-clock-o",
				date: "fa fa-calendar",
				up: "fa fa-arrow-up",
				down: "fa fa-arrow-down",
				previous: 'fa fa-arrow-left',
				next: 'fa fa-arrow-right',
			}
		}).on('dp.hide', function(e) {
			$('.fecha_final').data("DateTimePicker").minDate(moment(e.date));
		})

		$(".fecha_final").datetimepicker({
			locale: moment.locale('en', {
				week: {
					dow: 0
				}
			}),
			minDate: hoy,
			format: 'MM-DD-YYYY',
			icons: {
				time: "fa fa-clock-o",
				date: "fa fa-calendar",
				up: "fa fa-arrow-up",
				down: "fa fa-arrow-down",
				previous: 'fa fa-arrow-left',
				next: 'fa fa-arrow-right',
			}
		});

		//Constructores
		conf_constructor_listado_granjas_para_listados_multiples();
		// nuevos
		inv_constructor_listado_numero_de_orden();
		inv_constructor_listado_localizaciones();


		$('#dev-table').DataTable({
			"pageLength": 12,

			"dom": 'Bfrtip',
			"buttons": []
		});
		$('#dev-table-editable').DataTable({
			searching: false, // Deshabilita la función de búsqueda
			pagingType: 'full_numbers',
			ordering: false,
			"pageLength": 12,
			"language": {
				"sZeroRecords": "",
				"sEmptyTable": "",
				"sInfo": "",
				"sInfoEmpty": "",
				"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
				"sInfoPostFix": "",
				"oPaginate": {
					"sFirst": "",
					"sLast": "",
					"sNext": "",
					"sPrevious": ""
				},
			},
			"dom": 'Bfrtip',
			"buttons": []
		});

		//Máscaras
		//$('.monto').mask("#,##0.000", {reverse: false, maxlength: false});
		$('.monto').mask("9999999.999");
		$('.numeros').mask("999.999");
		$('.numeros_germinacion').mask("9.9999", {
			placeholder: "0"
		});
		// $('.procentaje').mask("999.99");
		// $('.lote').mask("999999999999999");
		$('.letras').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {
			translation: {
				'S': {
					pattern: /[A-Za-z-¡!¿?,.() 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/,
					optional: false
				}
			}
		});
		$('.letras2').mask('SS', {
			translation: {
				'S': {
					pattern: /[A-Za-z-¡!¿?,.() 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/,
					optional: false
				}
			}
		});

		/*----------------------------------------------------------------------------------
    								Validando listboxs
	    ----------------------------------------------------------------------------------*/
		$('.selectpicker.requerido').change(function(event) {
			var objeto = $(this);
			if (objeto.val() == null || objeto.val() == '-b' || objeto.val() == '') {
				objeto.selectpicker('setStyle', 'btn-info', 'remove');
				objeto.selectpicker('setStyle', 'btn-danger');
			} else {
				objeto.selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');
				objeto.selectpicker('setStyle', 'btn-info');
				objeto.removeClass('campo-vacio');
			}
			objeto.selectpicker('refresh');
		});
		$('.selectpicker_semillas.requerido').change(function(event) {
			var objeto = $(this);
			if (objeto.val() == null || objeto.val() == '-b' || objeto.val() == '') {
				objeto.selectpicker('setStyle', 'btn-info', 'remove');
				objeto.selectpicker('setStyle', 'btn-danger');
			} else {
				objeto.selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');
				objeto.selectpicker('setStyle', 'btn-info');
				objeto.removeClass('campo-vacio');
			}
			objeto.selectpicker('refresh');
		});
		$('#cod_numeros_de_orden').change(function(event) {
			arrDatosDePlantas = inv_listado_de_semillas_con_line_item();
		});

		/*----------------------------------------------------------------------------------
	    					Validación input, textarea requeridos
	    ----------------------------------------------------------------------------------*/
		$('.input.requerido').keyup(function(event) {
			if ($(this).val().trim() != "") {
				$(this).parent('div').removeClass('has-error');
			} else {
				$(this).parent('div').addClass('has-error');
			}
		});
		arrDatosGeneralPlantancion["total_trays_plantas_" + 0] = 0;
		arrDatosGeneralPlantancion["total_plantas_" + 0] = 0;

		$('#modal_loading').modal('hide');

		jQuery.ajaxSetup({
			async: true
		});
		if (pNumeroTicket != 0) {
			var jsonDatosTrasplante = JSON.parse(datosExtraidos);

			if (Object.keys(jsonDatosTrasplante).length > 0) {

				generarTabla(jsonDatosTrasplante)
			}
		}


	});

	$('#btn_guardar_plantacion').click(function(event) {
		if (guardadoHabilitadoNuevo && !plantacionCompletada) {

			/* Act on the event */
			var error = 0;
			$(".input.requerido").map(function() {
				if (!$(this).val()) {
					error = 1;
					$(this).parent('div').addClass('has-error');
					return false;
				} else {
					$(this).parent('div').removeClass('has-error');
				}
			});
			$(".selectpicker.requerido").map(function() {
				if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '') {
					$(this).selectpicker('setStyle', 'btn-info', 'remove');
					$(this).selectpicker('setStyle', 'btn-danger');
					error = 2;
				} else {
					$(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');
					$(this).selectpicker('setStyle', 'btn-info');
					$(this).removeClass('campo-vacio');
				}
				$(this).selectpicker('refresh');
			});
			if (error == 0) {
				grl_overlay_loading('');
				$('#modal_loading').modal('hide');
				$('#modal_loading').on('hidden.bs.modal', function() {
					const cantidadDeSemillasRegistradas = arrIndiceDatosSemillas.length;
					let cantidadPropiedadesRecorridas = 0;
					let cantidadDeBloquesRecorridos = 0;
					let arrDatosGeneralPlantancionParcial = {};

					if (!actualizarPlantancion) {

						for (var clave in arrDatosGeneralPlantancion) {
							if (arrDatosGeneralPlantancion.hasOwnProperty(clave)) {
								cantidadPropiedadesRecorridas += 1;
								var valor = arrDatosGeneralPlantancion[clave];
								arrDatosGeneralPlantancionParcial[clave] = valor;

								if (cantidadPropiedadesRecorridas >= 11) {
									cantidadPropiedadesRecorridas = 0;
									cantidadDeBloquesRecorridos += 1;
								}
								if (cantidadDeBloquesRecorridos >= 50) {

									inv_guardar_trasplante(arrDatosGeneralPlantancionParcial, cantidadDeSemillasRegistradas, false);
									cantidadDeBloquesRecorridos = 0;
									arrDatosGeneralPlantancionParcial = {};
								}
							}
						}

						if (cantidadDeBloquesRecorridos > 0) {

							cantidadDeBloquesRecorridos = 0;
							inv_guardar_trasplante(arrDatosGeneralPlantancionParcial, cantidadDeSemillasRegistradas, true);
							// inv_guardar_trasplante(arrDatosGeneralPlantancion, arrIndiceDatosSemillas); //Primera versión de la invocación de la llamada
						}

					} else {
						for (var clave in arrDatosGeneralPlantancion) {
							if (arrDatosGeneralPlantancion.hasOwnProperty(clave)) {
								cantidadPropiedadesRecorridas += 1;
								var valor = arrDatosGeneralPlantancion[clave];
								arrDatosGeneralPlantancionParcial[clave] = valor;

								if (cantidadPropiedadesRecorridas >= 11) {
									cantidadPropiedadesRecorridas = 0;
									cantidadDeBloquesRecorridos += 1;
								}
								if (cantidadDeBloquesRecorridos >= 50) {

									inv_actualizar_trasplante(pNumeroTicket, arrDatosGeneralPlantancionParcial, cantidadDeSemillasRegistradas, false);
									cantidadDeBloquesRecorridos = 0;
									arrDatosGeneralPlantancionParcial = {};
								}
							}
						}

						if (cantidadDeBloquesRecorridos > 0) {

							cantidadDeBloquesRecorridos = 0;
							inv_actualizar_trasplante(pNumeroTicket, arrDatosGeneralPlantancionParcial, cantidadDeSemillasRegistradas);
							// inv_actualizar_trasplante(pNumeroTicket, arrDatosGeneralPlantancion, cantidadDeSemillasRegistradas); //Primera versión de la invocación de la llamada
						}

					}
				});
			} else {
				grl_mensaje('You must fill out all marked fields', 'Please check', 'warning');
			}
		} else {
			if (plantacionCompletada) {

				grl_mensaje('The transplant is now complete. No further edits allowed.', '', 'warning');
			} else {
			if(guardadoHabilitadoNuevo){

			}
				grl_mensaje('You must add plants for transplanting.', '', 'warning');

			}


		}

	});
	$('#btn_mostrarTabla').click(function(event) {


	});


	$('#cantidad_semillas').on('input', function() {
		const cantSemillas = $(this).val();
		if (cantSemillas > 0 && cantSemillas != '') {
			var cantTotalSemillas = parseInt(cantSemillas) + (($('#cantidad_resembrar').val() / 100) * cantSemillas);
			// cantTotalSemillas = cantTotalSemillas.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
			$('#total_semillas').val(parseInt(cantTotalSemillas).toFixed(0));
		} else {
			$('#total_semillas').val(0);
		}
	});

	$('#cantidad_resembrar').on('input', function() {
		var cantResembrar = $(this).val();

		var cantTotalSemillas = parseInt($('#cantidad_semillas').val()) + ((cantResembrar / 100) * $('#cantidad_semillas').val());
		// cantTotalSemillas = cantTotalSemillas.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
		$('#total_semillas').val(parseInt(cantTotalSemillas).toFixed(0));

	});

	$('#btn_agregar_plantas').click(function(event) {

		/* Act on the event */
		var error = 0;

		$(".input.requerido_plantas").map(function() {
			if (!$(this).val()) {
				error = 1;
				$(this).parent('div').addClass('has-error');
				return false;
			} else {
				$(this).parent('div').removeClass('has-error');
			}
		});
		$(".selectpicker_semillas.requerido").map(function() {
			if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '') {
				$(this).selectpicker('setStyle', 'btn-info', 'remove');
				$(this).selectpicker('setStyle', 'btn-danger');
				error = 2;
			} else {
				$(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');
				$(this).selectpicker('setStyle', 'btn-info');
				$(this).removeClass('campo-vacio');
			}
			$(this).selectpicker('refresh');
		});
		$(".selectpicker_numero_orden.requerido").map(function() {
			if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '') {
				$(this).selectpicker('setStyle', 'btn-info', 'remove');
				$(this).selectpicker('setStyle', 'btn-danger');
				error = 3;
			} else {
				$(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');
				$(this).selectpicker('setStyle', 'btn-info');
				$(this).removeClass('campo-vacio');
			}
			$(this).selectpicker('refresh');
		});
		error = plantacionCompletada ? 4 : error

		if (error == 0 && !plantacionCompletada) {
			jQuery.ajaxSetup({
				async: false
			});

			let numero_orden = $("#cod_numeros_de_orden").val()
			let cod_line_items = $("#cod_line_item").val() //Posible grupo
			let cantidad_en_bandeja = $("#cantidad_en_bandeja").val()
			let cantidad_plantas = $("#cantidad_plantas").val()
			let cantidad_germinacion = $("#cantidad_germinacion").val()

			let nombresSemillas = extraerNombreElemenosSeleccionados("cod_line_item");
			var textoPlanta = nombresSemillas[0];
			var partesDelTextoPlanta = textoPlanta.split("-");


			let fechaInicialRegistrada
			let fechaFinalRegistrada;

			cod_line_items.forEach((cod_plantacion, index) => {
				arrDatosGeneralPlantancion["total_trays_plantas_" + 0] += parseInt(cantidad_en_bandeja);
				arrDatosGeneralPlantancion["total_plantas_" + 0] += parseInt(cantidad_plantas);

				textoPlanta = nombresSemillas[index];
				partesDelTextoPlanta = textoPlanta.split("-");



				arrDatosGeneralPlantancion["cod_plantacion_" + indexGeneralSemillasEditables] = cod_plantacion;

				arrDatosGeneralPlantancion["cod_inventario_" + indexGeneralSemillasEditables] = arrDatosDePlantas["cod_inventario_" + cod_plantacion];
				arrDatosGeneralPlantancion["germinacion_automatica_" + indexGeneralSemillasEditables] = arrDatosDePlantas["germinacion_automatica_" + cod_plantacion];
				arrDatosGeneralPlantancion["numero_orden_planta_" + indexGeneralSemillasEditables] = numero_orden;

				arrDatosGeneralPlantancion["item_planta_" + indexGeneralSemillasEditables] = parseInt(partesDelTextoPlanta[2].split(")")[1]);
				// arrDatosGeneralPlantancion["nombre_planta_" + indexGeneralSemillasEditables] = partesDelTextoPlanta[1].trim();
				arrDatosGeneralPlantancion["nombre_planta_" + indexGeneralSemillasEditables] = nombresSemillas[index];
				arrDatosGeneralPlantancion["tray_planta_" + indexGeneralSemillasEditables] = cantidad_en_bandeja; // Se suma "+1" para que se muestre desde el 1 en la tabla
				arrDatosGeneralPlantancion["cantidad_de_plantas_" + indexGeneralSemillasEditables] = cantidad_plantas;

				let calculoGerminacionAutomatica = (cantidad_plantas / arrDatosDePlantas["cantidad_total_" + cod_plantacion]) * 100;

				arrDatosGeneralPlantancion["cantidad_de_plantas_plantacion_" + indexGeneralSemillasEditables] = arrDatosDePlantas["cantidad_total_" + cod_plantacion];
				arrDatosGeneralPlantancion["overseed_plantacion_" + indexGeneralSemillasEditables];

				arrDatosGeneralPlantancion["numero_germinacion_planta_" + indexGeneralSemillasEditables] = parseFloat(calculoGerminacionAutomatica);
				arrDatosGeneralPlantancion["producto_activo_" + indexGeneralSemillasEditables] = 1;
				arrIndiceDatosSemillas.push(indexGeneralSemillasEditables)

				indexGeneralSemillasEditables = indexGeneralSemillasEditables + 1;

			});

			$('.selectpicker_numero_orden').selectpicker('refresh');
			$('.selectpicker_semillas').selectpicker('refresh');

			$("#total_plantas").val(parseInt(arrDatosGeneralPlantancion["total_plantas_" + 0]).toLocaleString())
			$("#total_trays").val(parseInt(arrDatosGeneralPlantancion["total_trays_plantas_" + 0]).toLocaleString())

			trCuerpoTablaSemillasEditables.innerHTML = '';
			for (const indiceSemillas of arrIndiceDatosSemillas) {
				if (arrDatosGeneralPlantancion["producto_activo_" + indiceSemillas] == 1) {


					trCuerpoTablaSemillasEditables.innerHTML += `
						<tr>
							<td>
							${arrDatosGeneralPlantancion["numero_orden_planta_" + indiceSemillas]}
							</td>
							<td>
								<button onclick="activarEdicionDeCelda(
										'#input_indice_item_${indiceSemillas}',
										'#btn_cantindad_indice_item_${indiceSemillas}'
										)" class="btnCantidadSemillas"
										id="btn_cantindad_indice_item_${indiceSemillas}"  > ${arrDatosGeneralPlantancion["item_planta_"  + indiceSemillas]}
									</button>

									<input class="input_corto" hidden type="text"
										id="input_indice_item_${indiceSemillas}"
										onblur="salirDelInputSeleccionado('#input_indice_item_${indiceSemillas}', '#btn_cantindad_indice_item_${indiceSemillas}')"
										value="${arrDatosGeneralPlantancion["item_planta_"  + indiceSemillas]}"
										onkeydown="detectarTeclasDeSalida(event, '#input_indice_item_${indiceSemillas}', '#btn_cantindad_indice_item_${indiceSemillas}')"
										oninput="detectarDatosEntradaDeTecladosNumerosITEM('#input_indice_item_${indiceSemillas}', '#btn_cantindad_indice_item_${indiceSemillas}','item_planta_${indiceSemillas}')"
										placeholder="Name of seeds"/>
							</td>
							<td>
								${arrDatosGeneralPlantancion["nombre_planta_" + indiceSemillas]}
							</td>

							<td>
								<button onclick="activarEdicionDeCelda(
										'#input_tray_planta_${indiceSemillas}',
										'#btn_tray_planta_${indiceSemillas}'
										)" class="btnCantidadSemillas"
										id="btn_tray_planta_${indiceSemillas}"  > ${parseInt(arrDatosGeneralPlantancion["tray_planta_" + indiceSemillas]).toLocaleString()}
									</button>

									<input class="input_cantidad_semillas" hidden type="number"
										id="input_tray_planta_${indiceSemillas}"
										onblur="salirDelInputSeleccionado('#input_tray_planta_${indiceSemillas}', '#btn_tray_planta_${indiceSemillas}')"
										value="${parseInt(arrDatosGeneralPlantancion["tray_planta_" + indiceSemillas]).toLocaleString()}"
										onkeydown="detectarTeclasDeSalida(event, '#input_tray_planta_${indiceSemillas}', '#btn_tray_planta_${indiceSemillas}')"
										oninput="detectarDatosEntradaDeTecladosNumeroTray('#input_tray_planta_${indiceSemillas}', '#btn_tray_planta_${indiceSemillas}', 'tray_planta_${indiceSemillas}')"
										placeholder="Name of seeds"/>
							</td>
							<td>
								<button onclick="activarEdicionDeCeldaPorcentaje(
										'#input_cantida_plantas_${indiceSemillas}',
										'#btn_cantidad_plantas_${indiceSemillas}'
										)" class="btnCantidadSemillas"
										id="btn_cantidad_plantas_${indiceSemillas}"  > ${parseInt(arrDatosGeneralPlantancion["cantidad_de_plantas_" + indiceSemillas]).toLocaleString()}
								</button>

								<input class="input_cantidad_semillas" hidden type="number"
										id="input_cantida_plantas_${indiceSemillas}"
										onblur="salirDelInputSeleccionado('#input_cantida_plantas_${indiceSemillas}', '#btn_cantidad_plantas_${indiceSemillas}')"
										value="${arrDatosGeneralPlantancion["cantidad_de_plantas_" + indiceSemillas]}"
										onkeydown="detectarTeclasDeSalidaCantidad(event, '#input_cantida_plantas_${indiceSemillas}', '#btn_cantidad_plantas_${indiceSemillas}',${indiceSemillas})"
										oninput="detectarDatosEntradaDeTecladosCantidadPlantas('#input_cantida_plantas_${indiceSemillas}',
										'#btn_cantidad_plantas_${indiceSemillas}',
										'#btn_cantidad_semillas_editable_${indiceSemillas}',
										${indiceSemillas}, 'cantidad_de_plantas_${indiceSemillas}', ${indiceSemillas})"
								placeholder="Name of seeds"/>
							</td>
							<td>
								<button onclick="activarEdicionDeCeldaGerminacion(
										'#input_germiancion_planta_${indiceSemillas}',
										'#btn_germinacion_planta_${indiceSemillas}'
										)" class="btnCantidadSemillas"
										id="btn_germinacion_planta_${indiceSemillas}"  > ${parseFloat(arrDatosGeneralPlantancion["numero_germinacion_planta_" + indiceSemillas]).toLocaleString(undefined, {
			minimumFractionDigits: 2
		})}
								</button>

								<input class="input_cantidad_semillas numeros_germinacion" hidden type="text"
										id="input_germiancion_planta_${indiceSemillas}"
										onblur="salirDelInputSeleccionado('#input_germiancion_planta_${indiceSemillas}', '#btn_germinacion_planta_${indiceSemillas}')"
										value="${arrDatosGeneralPlantancion["numero_germinacion_planta_" + indiceSemillas]}"
										onkeydown="detectarTeclasDeSalidaGerminacion(event, '#input_germiancion_planta_${indiceSemillas}', '#btn_germinacion_planta_${indiceSemillas}',${indiceSemillas})"
										oninput="detectarDatosEntradaDeTecladosGerminacion('#input_germiancion_planta_${indiceSemillas}',
										'#btn_germinacion_planta_${indiceSemillas}',
										'#btn_cantidad_semillas_editable_${indiceSemillas}',
										${indiceSemillas}, 'numero_germinacion_planta_${indiceSemillas}')"
								placeholder="Example: 0.95"/>
							</td>
							<td>
								<button class="btn_eliminar_semilla_editable"onclick="eliminarSemilla(${indiceSemillas})" >
										<i class="fas fa-trash-alt"></i>
									</button>
							</td>
						</tr>
						`;

				}

			}
			guardadoHabilitadoNuevo = true;
			$("#btn_guardar_plantacion").removeClass('btn-secondary');
			$("#btn_guardar_plantacion").addClass('btn-success');
			jQuery.ajaxSetup({
				async: true
			});

		} else {
			switch (error) {
				case 1:
					grl_mensaje('You must fill out all marked fields', '', 'warning');
					break;
				case 2:
					grl_mensaje('You must select at least one Line item', '', 'warning');
					break;
				case 3:
					grl_mensaje('You must select at least one order number', '', 'warning');
					break;
				case 4:
					grl_mensaje('The transplant is now complete. No further edits allowed', '', 'warning');
					break;
			}
		}
	});
	$('#btn_eliminar_registro').click(function(event) {
		$('#modal_confirmar_eliminacion').modal('show');
	});
	$('#btn_confirmar_eliminacion').click(function(event) {
		$('#modal_confirmar_eliminacion').modal('hide');
		grl_overlay_loading('');
		$('#modal_loading').modal('hide');
		$('#modal_loading').on('hidden.bs.modal', function() {
			inv_eliminar_trasplante(pNumeroTicket);
		});

	});

	function agregarComas(numero) {
		// return numero.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		return parseInt(numero).toLocaleString();
	}

	function agregarComasNumerosConDecimales(numero) {
		// return numero.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		return parseFloat(numero).toLocaleString(undefined, {
			minimumFractionDigits: 2
		});
	}

	function sumarCantidadGenerales() {
		arrDatosGeneralPlantancion["total_plantas_" + 0] = 0;
		arrDatosGeneralPlantancion["total_trays_plantas_" + 0] = 0;
		for (const indiceSemillas of arrIndiceDatosSemillas) {
			if (arrDatosGeneralPlantancion["producto_activo_" + indiceSemillas] == 1) {

				arrDatosGeneralPlantancion["total_plantas_" + 0] += parseInt($("#input_cantida_plantas_" + indiceSemillas).val());
				arrDatosGeneralPlantancion["total_trays_plantas_" + 0] += parseInt($("#input_tray_planta_" + indiceSemillas).val());
			}
		}
		if (
			arrDatosGeneralPlantancion["total_plantas_" + 0] > 0 
		) {
			guardadoHabilitadoNuevo = true;
			$("#btn_guardar_plantacion").removeClass('btn-secondary');
			$("#btn_guardar_plantacion").addClass('btn-success');
		} else {
			guardadoHabilitadoNuevo = false;
			$("#btn_guardar_plantacion").addClass('btn-secondary');
			$("#btn_guardar_plantacion").removeClass('btn-success');
		}
		$("#total_plantas").val(parseInt(arrDatosGeneralPlantancion["total_plantas_" + 0]).toLocaleString())
		$("#total_trays").val(parseInt(arrDatosGeneralPlantancion["total_trays_plantas_" + 0]).toLocaleString())
	}

	function activarEdicionDeCelda(idInput, idBoton) {
		let btnActivarInput = $(idBoton);

		let miInput = $(idInput);
		miInput.toggle(); // Alterna la visibilidad del input
		miInput.prop('disabled', false);
		btnActivarInput.toggle(); // Alterna la visibilidad del input
		miInput.focus();
		valorInicialCaptado = miInput.val();

		btnActivarInput.text(agregarComas(valorInicialCaptado)); // Agregamos el valor del input+
	}

	function activarEdicionDeCeldaPorcentaje(idInput, idBoton) {
		let btnActivarInput = $(idBoton);

		let miInput = $(idInput);
		miInput.toggle(); // Alterna la visibilidad del input
		miInput.prop('disabled', false);
		btnActivarInput.toggle(); // Alterna la visibilidad del input
		miInput.focus();
		valorInicialCaptado = miInput.val();
		btnActivarInput.text(agregarComas(valorInicialCaptado));
	}

	function activarEdicionDeCeldaGerminacion(idInput, idBoton) {
		let btnActivarInput = $(idBoton);

		let miInput = $(idInput);
		miInput.toggle(); // Alterna la visibilidad del input
		miInput.prop('disabled', false);
		btnActivarInput.toggle(); // Alterna la visibilidad del input
		miInput.focus();
		valorInicialCaptado = miInput.val();
		btnActivarInput.text(agregarComasNumerosConDecimales(valorInicialCaptado));
	}
	//Este método se ejecuta cada vez que se sale de un input de cantidad de semillas
	function salirDelInputSeleccionado(idInput, idBoton) {

		let btnActivarInput = $(idBoton);
		let miInput = $(idInput);
		if (btnActivarInput == undefined || miInput == undefined) return;

		valorInicialCaptado = 0

		// Deshabilitar el input cuando se pierde el foco
		miInput.toggle(0);
		miInput.prop('disabled', true);
		btnActivarInput.toggle(200); // Alterna la visibilidad del input
		sumarCantidadGenerales();
	}

	function detectarDatosEntradaDeTecladosTexto(idInput, idBoton) {

		let btnActivarInput = $(idBoton);
		let miInput = $(idInput);
		var valorInput = miInput.val();
		var valorInputInicial = miInput.val();
		if (valorInput == '') {
			valorInput = valorInputInicial;
		}
		miInput.val(valorInput);
		btnActivarInput.text(agregarComas(valorInput));
	}

	function detectarDatosEntradaDeTecladosNumeros(idInput, idBoton, elemenArr) {

		let btnActivarInput = $(idBoton);
		let miInput = $(idInput);
		var valorInput = miInput.val();
		var nuevoValor = "";
		var puntoEncontrado = false;
		var numerosDespuesDelPunto = 0;

		for (var i = 0; i < valorInput.length; i++) {
			var caracter = valorInput.charAt(i);

			// Si el caracter es un número y aún no se han ingresado 4 números después del punto, agregarlo al nuevo valor
			if (/^\d$/.test(caracter) && (!puntoEncontrado || numerosDespuesDelPunto < 4)) {
				nuevoValor += caracter;
				if (puntoEncontrado) {
					numerosDespuesDelPunto++;
				}
			}

			// Si el caracter es un punto y aún no se ha encontrado un punto, agregarlo al nuevo valor
			if (caracter === "." && !puntoEncontrado) {
				nuevoValor += caracter;
				puntoEncontrado = true;
			}
		}
		if (nuevoValor < 0.1) {
			nuevoValor = 0;
		}

		miInput.val(nuevoValor);
		btnActivarInput.text(agregarComas(nuevoValor));
	}

	function detectarDatosEntradaDeTecladosNumerosCantidadSemillas(idInput, idBoton, codigoInventarioIndice, elemenArr) {

		let btnActivarInput = $(idBoton);
		let miInput = $(idInput);

		var valorInputInicial = miInput.val();

		let nuevoValor = "";
		let puntoEncontrado = false;
		let numerosDespuesDelPunto = 0;
		let dentroRango
		const valorInput = miInput.val();

		for (var i = 0; i < valorInput.length; i++) {
			var caracter = valorInput.charAt(i);

			// Si el caracter es un número y aún no se han ingresado 4 números después del punto, agregarlo al nuevo valor
			if (/^\d$/.test(caracter) && (!puntoEncontrado || numerosDespuesDelPunto < 4)) {
				nuevoValor += caracter;
				if (puntoEncontrado) {
					numerosDespuesDelPunto++;
				}
			}

			// Si el caracter es un punto y aún no se ha encontrado un punto, agregarlo al nuevo valor
			if (caracter === "." && !puntoEncontrado) {
				nuevoValor += caracter;
				puntoEncontrado = true;
			}
		}
		if (nuevoValor < 0.1) {
			nuevoValor = valorInputInicial;
		}
		cantidadDentroDeRango

		dentroRango = calcularTotalPorMedioDeCantidad(nuevoValor, codigoInventarioIndice);
		cambiarEstadoBotonGuardarOrden(dentroRango, btnActivarInput);
		arrDatosGeneralPlantancion[elemenArr] = nuevoValor
		miInput.val(nuevoValor);
		btnActivarInput.text(agregarComas(nuevoValor));
	}

	function detectarDatosEntradaDeTecladosCantidadPlantas(idInput, idBoton, idBtnCantidadSemilla, codigoInventarioIndice, elemenArr, indiceSemillas) {

		let btnActivarInput = $(idBoton);
		let btnCantidadSemillas = $(idBtnCantidadSemilla);


		let btnGerminacion = $("#btn_germinacion_planta_" + indiceSemillas);
		let inputGerminacion = $("#input_germiancion_planta_" + indiceSemillas);

		let miInput = $(idInput);
		let valorInput = miInput.val();
		let nuevoValor = "";
		for (var i = 0; i < valorInput.length; i++) {
			var caracter = valorInput.charAt(i);
			// Si el caracter es un número y aún no se han ingresado 4 números después del punto, agregarlo al nuevo valor
			if (/^\d$/.test(caracter)) {
				nuevoValor += caracter;
			}
		}

		if (nuevoValor < 0.1 || nuevoValor == "" || nuevoValor == undefined) {
			nuevoValor = 0;
		}



		// (cantidad_plantas / arrDatosDePlantas["cantidad_total_" + cod_plantacion]) * 100;
		arrDatosGeneralPlantancion["numero_germinacion_planta_" + indiceSemillas] = (parseInt(nuevoValor) / arrDatosGeneralPlantancion["cantidad_de_plantas_plantacion_" + indiceSemillas]) * 100
		let nuevoValorGerminacio = arrDatosGeneralPlantancion["numero_germinacion_planta_" + indiceSemillas]

		nuevoValorGerminacio = parseFloat(nuevoValorGerminacio).toLocaleString(undefined, {
			minimumFractionDigits: 2
		})

		btnGerminacion.text(nuevoValorGerminacio);
		inputGerminacion.val(nuevoValorGerminacio);

		nuevoValor = parseInt(nuevoValor);
		arrDatosGeneralPlantancion[elemenArr] = nuevoValor
		miInput.val(nuevoValor);

		btnActivarInput.text(nuevoValor);
	}

	function detectarTeclasDeSalida(event, idInput, idBoton) {
		let btnActivarInput = $(idBoton);
		let miInput = $(idInput);

		var valorInput = miInput.val();

		if (event.keyCode === 13) { //Tecla "Enter"/"Entrar"
			miInput.prop('disabled', true);
		}


		if (event.keyCode === 27) { // Tecla "ESC"
			// Aquí puedes ejecutar el código que deseas cuando se presiona "ESC"
			btnActivarInput.text(agregarComas(valorInicialCaptado));
			miInput.val(valorInicialCaptado);
			miInput.prop('disabled', true);

		}
	}

	function detectarTeclasDeSalidaCantidad(event, idInput, idBoton, codigoInventarioIndice = 1) {
		let btnActivarInput = $(idBoton);
		let miInput = $(idInput);

		var valorInput = miInput.val();

		if (event.keyCode === 13) { //Tecla "Enter"/"Entrar"
			btnActivarInput.text(agregarComas(valorInput));
			miInput.prop('disabled', true);

		}


		if (event.keyCode === 27) { // Tecla "ESC"
			// Aquí puedes ejecutar el código que deseas cuando se presiona "ESC"

			btnActivarInput.text(agregarComas(valorInicialCaptado));
			miInput.val(valorInicialCaptado);
			miInput.prop('disabled', true);

		}
	}

	function detectarTeclasDeSalidaGerminacion(event, idInput, idBoton, codigoInventarioIndice = 1) {
		let btnActivarInput = $(idBoton);
		let miInput = $(idInput);

		var valorInput = miInput.val();

		if (event.keyCode === 13) { //Tecla "Enter"/"Entrar"
			btnActivarInput.text(agregarComasNumerosConDecimales(valorInput));
			miInput.prop('disabled', true);

		}


		if (event.keyCode === 27) { // Tecla "ESC"
			// Aquí puedes ejecutar el código que deseas cuando se presiona "ESC"
			btnActivarInput.text(agregarComasNumerosConDecimales(valorInicialCaptado));
			miInput.val(valorInicialCaptado);
			miInput.prop('disabled', true);

		}
	}

	function detectarTeclasDeSalidaCantidadSemillas(event, idInput, idBoton, indiceSemillas) {
		let btnActivarInput = $(idBoton);
		let miInput = $(idInput);

		var valorInput = miInput.val();

		if (event.keyCode === 13) { //Tecla "Enter"/"Entrar"
			miInput.prop('disabled', true);
		}


		if (event.keyCode === 27) { // Tecla "ESC"
			// Aquí puedes ejecutar el código que deseas cuando se presiona "ESC"
			btnActivarInput.text(agregarComas(valorInicialCaptado));
			miInput.val(valorInicialCaptado);
			miInput.prop('disabled', true);


		}
	}

	function cambiarEstadoBotonGuardarOrden(dentroRango, btnCantidadSemillas) {
		if (dentroRango) {
			btnCantidadSemillas.removeClass('fuera-de-rango');
			btnCantidadSemillas.addClass('dentro-de-rango');

		} else {
			guardadoHabilitado = false;
			btnCantidadSemillas.removeClass('dentro-de-rango');
			btnCantidadSemillas.addClass('fuera-de-rango');
		}
	}


	// Funciones para Germinacion
	function detectarDatosEntradaDeTecladosGerminacion(idInput, idBoton, idBtnCantidadSemilla, codigoInventarioIndice, elemenArr) {

		let btnActivarInput = $(idBoton);
		let btnCantidadSemillas = $(idBtnCantidadSemilla);
		let miInput = $(idInput);
		var valorInput = miInput.val();
		var nuevoValor = "";
		for (var i = 0; i < valorInput.length; i++) {
			var caracter = valorInput.charAt(i);
			// Si el caracter es un número y aún no se han ingresado 4 números después del punto, agregarlo al nuevo valor
			nuevoValor += caracter;

		}

		if (nuevoValor < 0.1 || nuevoValor == "" || nuevoValor == undefined) {
			nuevoValor = 0;
		}

		// nuevoValor = parseInt(nuevoValor);
		arrDatosGeneralPlantancion[elemenArr] = parseFloat(nuevoValor).toLocaleString(undefined, {
			minimumFractionDigits: 2
		})
		miInput.val(parseFloat(nuevoValor).toLocaleString(undefined, {
			minimumFractionDigits: 2
		}));
		btnActivarInput.text(agregarComasNumerosConDecimales(nuevoValor));
	}

	// Funciones para Tray
	function detectarDatosEntradaDeTecladosNumeroTray(idInput, idBoton, elemenArr) {

		let btnActivarInput = $(idBoton);
		let miInput = $(idInput);
		var valorInput = miInput.val();
		var valorInputInicial = miInput.val();

		var nuevoValor = "";
		var puntoEncontrado = false;
		var numerosDespuesDelPunto = 0;

		for (var i = 0; i < valorInput.length; i++) {
			var caracter = valorInput.charAt(i);

			// Si el caracter es un número y aún no se han ingresado 4 números después del punto, agregarlo al nuevo valor
			if (/^\d$/.test(caracter)) {
				nuevoValor += caracter;
			}

			// Si el caracter es un punto y aún no se ha encontrado un punto, agregarlo al nuevo valor
			if (caracter === "." && !puntoEncontrado) {
				nuevoValor += caracter;
				puntoEncontrado = true;
			}
		}
		if (nuevoValor < 0.1) {

			nuevoValor = 0;
		}
		nuevoValor = parseInt(nuevoValor);

		arrDatosGeneralPlantancion[elemenArr] = nuevoValor
		miInput.val(nuevoValor);
		btnActivarInput.text(agregarComas(nuevoValor));
	}
	// Funciones para ITEM
	function detectarDatosEntradaDeTecladosNumerosITEM(idInput, idBoton, elemenArr) {

		let btnActivarInput = $(idBoton);
		let miInput = $(idInput);
		var valorInput = miInput.val();
		var nuevoValor = "";


		for (var i = 0; i < valorInput.length; i++) {
			var caracter = valorInput.charAt(i);

			// Si el caracter es un número y aún no se han ingresado 4 números después del punto, agregarlo al nuevo valor
			if (/^\d$/.test(caracter)) {
				nuevoValor += caracter;

			}

		}
		if (nuevoValor < 0.1) {
			nuevoValor = 0;
		}
		nuevoValor = parseInt(nuevoValor);
		arrDatosGeneralPlantancion[elemenArr] = nuevoValor
		miInput.val(nuevoValor);
		btnActivarInput.text(agregarComas(nuevoValor));
	}
	// Genera la tabla tomando como base los datos de una plantación ya existente
	async function generarTabla(jsonDatosTrasplante) {
		// await sleep(2000); // Pausa la ejecución los milisegundos que se envien como parametro
		jQuery.ajaxSetup({
			async: false
		});

		// $("#div_fecha_recibida").toggle();
		// Asignado y desactivando los inputs generales
		$("#fecha_entrega").val(jsonDatosTrasplante[0].fecha_entrega);
		$("#fecha_entrega").prop("disabled", true);
		$("#fecha_recibo").prop("disabled", false);

		$("#numero_ticket").val(jsonDatosTrasplante[0].numero_ticket);
		$("#numero_ticket").prop("disabled", true);

		$("#cod_info_empresa").val(jsonDatosTrasplante[0].cod_info_empresa);
		$("#cod_info_empresa").prop("disabled", true);

		$("#cod_localizacion").val(jsonDatosTrasplante[0].cod_localizacion);
		$("#cod_localizacion").prop("disabled", true);

		$("#cod_sembradores").val(jsonDatosTrasplante[0].cod_sembrador);
		$("#cod_sembradores").prop("disabled", true);
		$('.selectpicker').selectpicker('refresh');

		actualizarPlantancion = true;
		arrDatosGeneralPlantancion["total_trays_plantas_" + 0] = 0;
		arrDatosGeneralPlantancion["total_plantas_" + 0] = 0;

		for (var i = 0; i < jsonDatosTrasplante.length; i++) {
			var jsonTrasplante = jsonDatosTrasplante[i];


			arrDatosGeneralPlantancion["total_trays_plantas_" + 0] += parseInt(jsonTrasplante.tray);
			arrDatosGeneralPlantancion["total_plantas_" + 0] += parseInt(jsonTrasplante.cantidad_plantas);
			if (jsonTrasplante.completado == 1) {
				plantacionCompletada = true;
			}
			arrDatosGeneralPlantancion["cod_trasplante_" + indexGeneralSemillasEditables] = jsonTrasplante.cod_trasplante;
			arrDatosGeneralPlantancion["cod_plantacion_" + indexGeneralSemillasEditables] = jsonTrasplante.cod_plantacion;
			arrDatosGeneralPlantancion["cod_inventario_" + indexGeneralSemillasEditables] = jsonTrasplante.cod_inventario;
			arrDatosGeneralPlantancion["numero_orden_planta_" + indexGeneralSemillasEditables] = jsonTrasplante.numero_orden;
			arrDatosGeneralPlantancion["item_planta_" + indexGeneralSemillasEditables] = parseInt(jsonTrasplante.item);

			jsonTrasplante.nombre_semilla = "(" + jsonTrasplante.fecha_final + ") " + jsonTrasplante.item + "- " + jsonTrasplante.nombre_semilla + " (" + parseInt(jsonTrasplante.total).toLocaleString() + ")"
			arrDatosGeneralPlantancion["nombre_planta_" + indexGeneralSemillasEditables] = jsonTrasplante.nombre_semilla;

			arrDatosGeneralPlantancion["tray_planta_" + indexGeneralSemillasEditables] = jsonTrasplante.tray;
			arrDatosGeneralPlantancion["cantidad_de_plantas_" + indexGeneralSemillasEditables] = jsonTrasplante.cantidad_plantas;


			// arrDatosGeneralPlantancion["cantidad_de_plantas_plantacion_" + indexGeneralSemillasEditables] = jsonTrasplante.cantidad * ((jsonTrasplante.overseed / 100) + 1);
			arrDatosGeneralPlantancion["cantidad_de_plantas_plantacion_" + indexGeneralSemillasEditables] = jsonTrasplante.total_del_momento;

			arrDatosGeneralPlantancion["numero_germinacion_planta_" + indexGeneralSemillasEditables] = jsonTrasplante.germinacion.toLocaleString(undefined, {
				minimumFractionDigits: 2
			});
			arrDatosGeneralPlantancion["producto_activo_" + indexGeneralSemillasEditables] = 1;
			arrIndiceDatosSemillas.push(indexGeneralSemillasEditables)
			indexGeneralSemillasEditables = indexGeneralSemillasEditables + 1;

		}

		// Generar la nueva tabla con los datos recibidos
		// trCuerpoTablaSemillasEditables.innerHTML = '';
		if (plantacionCompletada) {
			redibujarTablaDeshabilitada();
			$("#total_plantas").val(parseInt(arrDatosGeneralPlantancion["total_plantas_" + 0]).toLocaleString())
			$("#total_trays").val(parseInt(arrDatosGeneralPlantancion["total_trays_plantas_" + 0]).toLocaleString())
		} else {
			for (const indiceSemillas of arrIndiceDatosSemillas) {

				trCuerpoTablaSemillasEditables.innerHTML +=
					`
				<tr>
					<td>
					${arrDatosGeneralPlantancion["numero_orden_planta_" + indiceSemillas]}
					</td>
					<td>
						<button onclick="activarEdicionDeCelda(
								'#input_indice_item_${indiceSemillas}',
								'#btn_cantindad_indice_item_${indiceSemillas}'
								)" class="btnCantidadSemillas"
								id="btn_cantindad_indice_item_${indiceSemillas}"  > ${arrDatosGeneralPlantancion["item_planta_"  + indiceSemillas]}
							</button>

							<input class="input_corto" hidden type="text"
								id="input_indice_item_${indiceSemillas}"
								onblur="salirDelInputSeleccionado('#input_indice_item_${indiceSemillas}', '#btn_cantindad_indice_item_${indiceSemillas}')"
								value="${arrDatosGeneralPlantancion["item_planta_"  + indiceSemillas]}"
								onkeydown="detectarTeclasDeSalida(event, '#input_indice_item_${indiceSemillas}', '#btn_cantindad_indice_item_${indiceSemillas}')"
								oninput="detectarDatosEntradaDeTecladosNumerosITEM('#input_indice_item_${indiceSemillas}', '#btn_cantindad_indice_item_${indiceSemillas}','item_planta_${indiceSemillas}')"
								placeholder="Name of seeds"/>
					</td>
					<td>
						${arrDatosGeneralPlantancion["nombre_planta_" + indiceSemillas]}
					</td>

					<td>
						<button onclick="activarEdicionDeCelda(
								'#input_tray_planta_${indiceSemillas}',
								'#btn_tray_planta_${indiceSemillas}'
								)" class="btnCantidadSemillas"
								id="btn_tray_planta_${indiceSemillas}"  > ${parseInt(arrDatosGeneralPlantancion["tray_planta_" + indiceSemillas]).toLocaleString()}
							</button>

							<input class="input_cantidad_semillas" hidden type="number"
								id="input_tray_planta_${indiceSemillas}"
								onblur="salirDelInputSeleccionado('#input_tray_planta_${indiceSemillas}', '#btn_tray_planta_${indiceSemillas}')"
								value="${parseInt(arrDatosGeneralPlantancion["tray_planta_" + indiceSemillas]).toLocaleString()}"
								onkeydown="detectarTeclasDeSalida(event, '#input_tray_planta_${indiceSemillas}', '#btn_tray_planta_${indiceSemillas}')"
								oninput="detectarDatosEntradaDeTecladosNumeroTray('#input_tray_planta_${indiceSemillas}', '#btn_tray_planta_${indiceSemillas}', 'tray_planta_${indiceSemillas}')"
								placeholder="Name of seeds"/>
					</td>
					<td>
						<button onclick="activarEdicionDeCeldaPorcentaje(
								'#input_cantida_plantas_${indiceSemillas}',
								'#btn_cantidad_plantas_${indiceSemillas}'
								)" class="btnCantidadSemillas"
								id="btn_cantidad_plantas_${indiceSemillas}"  > ${parseInt(arrDatosGeneralPlantancion["cantidad_de_plantas_" + indiceSemillas]).toLocaleString()}
						</button>

						<input class="input_cantidad_semillas" hidden type="number"
								id="input_cantida_plantas_${indiceSemillas}"
								onblur="salirDelInputSeleccionado('#input_cantida_plantas_${indiceSemillas}', '#btn_cantidad_plantas_${indiceSemillas}')"
								value="${arrDatosGeneralPlantancion["cantidad_de_plantas_" + indiceSemillas]}"
								onkeydown="detectarTeclasDeSalidaCantidad(event, '#input_cantida_plantas_${indiceSemillas}', '#btn_cantidad_plantas_${indiceSemillas}',${indiceSemillas})"
								oninput="detectarDatosEntradaDeTecladosCantidadPlantas('#input_cantida_plantas_${indiceSemillas}',
								'#btn_cantidad_plantas_${indiceSemillas}',
								'#btn_cantidad_semillas_editable_${indiceSemillas}',
								${indiceSemillas}, 'cantidad_de_plantas_${indiceSemillas}', ${indiceSemillas})"
						placeholder="Name of seeds"/>
					</td>
					<td>
						<button onclick="activarEdicionDeCeldaGerminacion(
								'#input_germiancion_planta_${indiceSemillas}',
								'#btn_germinacion_planta_${indiceSemillas}'
								)" class="btnCantidadSemillas"
								id="btn_germinacion_planta_${indiceSemillas}"  > ${parseFloat(arrDatosGeneralPlantancion["numero_germinacion_planta_" + indiceSemillas]).toLocaleString(undefined, {
																						minimumFractionDigits: 2
																					})}
						</button>

						<input class="input_cantidad_semillas numeros_germinacion" hidden type="text"
								id="input_germiancion_planta_${indiceSemillas}"
								onblur="salirDelInputSeleccionado('#input_germiancion_planta_${indiceSemillas}', '#btn_germinacion_planta_${indiceSemillas}')"
								value="${arrDatosGeneralPlantancion["numero_germinacion_planta_" + indiceSemillas]}"
								onkeydown="detectarTeclasDeSalidaGerminacion(event, '#input_germiancion_planta_${indiceSemillas}', '#btn_germinacion_planta_${indiceSemillas}',${indiceSemillas})"
								oninput="detectarDatosEntradaDeTecladosGerminacion('#input_germiancion_planta_${indiceSemillas}',
								'#btn_germinacion_planta_${indiceSemillas}',
								'#btn_cantidad_semillas_editable_${indiceSemillas}',
								${indiceSemillas}, 'numero_germinacion_planta_${indiceSemillas}')"
						placeholder="Example: 0.95"/>
					</td>
					<td>
						<button class="btn_eliminar_semilla_editable"onclick="eliminarSemilla(${indiceSemillas})" >
								<i class="fas fa-trash-alt"></i>
							</button>
					</td>
				</tr>
				`;

			}
			$("#btn_eliminar_registro").removeClass("hide");

			guardadoHabilitado = true;
			$("#btn_guardar_plantacion").removeClass('btn-secondary');
			$("#btn_guardar_plantacion").addClass('btn-success');
			sumarCantidadGenerales();
		}


		jQuery.ajaxSetup({
			async: true
		});
	}

	function redibujarTabla() {
		// await sleep(2000); // Pausa la ejecución los milisegundos que se envien como parametro
		jQuery.ajaxSetup({
			async: false
		});

		// Generar la nueva tabla con los datos recibidos
		trCuerpoTablaSemillasEditables.innerHTML = '';

		for (const indiceSemillas of arrIndiceDatosSemillas) {
			if (arrDatosGeneralPlantancion["producto_activo_" + indiceSemillas] == 1) {

				trCuerpoTablaSemillasEditables.innerHTML += `
					<tr>
							<td>
							${arrDatosGeneralPlantancion["numero_orden_planta_" + indiceSemillas]}
							</td>
							<td>
								<button onclick="activarEdicionDeCelda(
										'#input_indice_item_${indiceSemillas}',
										'#btn_cantindad_indice_item_${indiceSemillas}'
										)" class="btnCantidadSemillas"
										id="btn_cantindad_indice_item_${indiceSemillas}"  > ${arrDatosGeneralPlantancion["item_planta_"  + indiceSemillas]}
									</button>

									<input class="input_corto" hidden type="text"
										id="input_indice_item_${indiceSemillas}"
										onblur="salirDelInputSeleccionado('#input_indice_item_${indiceSemillas}', '#btn_cantindad_indice_item_${indiceSemillas}')"
										value="${arrDatosGeneralPlantancion["item_planta_"  + indiceSemillas]}"
										onkeydown="detectarTeclasDeSalida(event, '#input_indice_item_${indiceSemillas}', '#btn_cantindad_indice_item_${indiceSemillas}')"
										oninput="detectarDatosEntradaDeTecladosNumerosITEM('#input_indice_item_${indiceSemillas}', '#btn_cantindad_indice_item_${indiceSemillas}','item_planta_${indiceSemillas}')"
										placeholder="Name of seeds"/>
							</td>
							<td>
								${arrDatosGeneralPlantancion["nombre_planta_" + indiceSemillas]}
							</td>

							<td>
								<button onclick="activarEdicionDeCelda(
										'#input_tray_planta_${indiceSemillas}',
										'#btn_tray_planta_${indiceSemillas}'
										)" class="btnCantidadSemillas"
										id="btn_tray_planta_${indiceSemillas}"  > ${arrDatosGeneralPlantancion["tray_planta_" + indiceSemillas]}
									</button>

									<input class="input_cantidad_semillas" hidden type="number"
										id="input_tray_planta_${indiceSemillas}"
										onblur="salirDelInputSeleccionado('#input_tray_planta_${indiceSemillas}', '#btn_tray_planta_${indiceSemillas}')"
										value="${parseInt(arrDatosGeneralPlantancion["tray_planta_" + indiceSemillas]).toLocaleString()}"
										onkeydown="detectarTeclasDeSalida(event, '#input_tray_planta_${indiceSemillas}', '#btn_tray_planta_${indiceSemillas}')"
										oninput="detectarDatosEntradaDeTecladosNumeroTray('#input_tray_planta_${indiceSemillas}', '#btn_tray_planta_${indiceSemillas}', 'tray_planta_${indiceSemillas}')"
										placeholder="Name of seeds"/>
							</td>
							<td>
								<button onclick="activarEdicionDeCeldaPorcentaje(
										'#input_cantida_plantas_${indiceSemillas}',
										'#btn_cantidad_plantas_${indiceSemillas}'
										)" class="btnCantidadSemillas"
										id="btn_cantidad_plantas_${indiceSemillas}"  > ${arrDatosGeneralPlantancion["cantidad_de_plantas_" + indiceSemillas]}
								</button>

								<input class="input_cantidad_semillas" hidden type="number"
										id="input_cantida_plantas_${indiceSemillas}"
										onblur="salirDelInputSeleccionado('#input_cantida_plantas_${indiceSemillas}', '#btn_cantidad_plantas_${indiceSemillas}')"
										value="${arrDatosGeneralPlantancion["cantidad_de_plantas_" + indiceSemillas]}"
										onkeydown="detectarTeclasDeSalidaCantidad(event, '#input_cantida_plantas_${indiceSemillas}', '#btn_cantidad_plantas_${indiceSemillas}',${indiceSemillas})"
										oninput="detectarDatosEntradaDeTecladosCantidadPlantas('#input_cantida_plantas_${indiceSemillas}',
										'#btn_cantidad_plantas_${indiceSemillas}',
										'#btn_cantidad_semillas_editable_${indiceSemillas}',
										${indiceSemillas}, 'cantidad_de_plantas_${indiceSemillas}', ${indiceSemillas})"
								placeholder="Name of seeds"/>
							</td>
							<td>
								<button onclick="activarEdicionDeCeldaGerminacion(
										'#input_germiancion_planta_${indiceSemillas}',
										'#btn_germinacion_planta_${indiceSemillas}'
										)" class="btnCantidadSemillas"
										id="btn_germinacion_planta_${indiceSemillas}"  > ${parseFloat(arrDatosGeneralPlantancion["numero_germinacion_planta_" + indiceSemillas]).toLocaleString(undefined, {
																																																	minimumFractionDigits: 2
																																																})}
								</button>

								<input class="input_cantidad_semillas numeros_germinacion" hidden type="text"
										id="input_germiancion_planta_${indiceSemillas}"
										onblur="salirDelInputSeleccionado('#input_germiancion_planta_${indiceSemillas}', '#btn_germinacion_planta_${indiceSemillas}')"
										value="${arrDatosGeneralPlantancion["numero_germinacion_planta_" + indiceSemillas]}"
										onkeydown="detectarTeclasDeSalidaGerminacion(event, '#input_germiancion_planta_${indiceSemillas}', '#btn_germinacion_planta_${indiceSemillas}',${indiceSemillas})"
										oninput="detectarDatosEntradaDeTecladosGerminacion('#input_germiancion_planta_${indiceSemillas}',
										'#btn_germinacion_planta_${indiceSemillas}',
										'#btn_cantidad_semillas_editable_${indiceSemillas}',
										${indiceSemillas}, 'numero_germinacion_planta_${indiceSemillas}')"
								placeholder="Example: 0.95"/>
							</td>
							<td>
								<button class="btn_eliminar_semilla_editable"onclick="eliminarSemilla(${indiceSemillas})" >
										<i class="fas fa-trash-alt"></i>
									</button>
							</td>
						</tr>
						`;
			}
		}
		sumarCantidadGenerales();
		guardadoHabilitado = true;
		jQuery.ajaxSetup({
			async: true
		});
	}

	function redibujarTablaDeshabilitada() {
		// await sleep(2000); // Pausa la ejecución los milisegundos que se envien como parametro
		jQuery.ajaxSetup({
			async: false
		});
		$("#cod_numeros_de_orden").prop("disabled", true);
		$("#cod_line_item").prop("disabled", true);
		$("#cantidad_en_bandeja").prop("disabled", true);
		$("#cantidad_plantas").prop("disabled", true);

		for (const indiceSemillas of arrIndiceDatosSemillas) {

			trCuerpoTablaSemillasEditables.innerHTML +=
				`
				<tr>
					<td>
					${arrDatosGeneralPlantancion["numero_orden_planta_" + indiceSemillas]}
					</td>
					<td>
						<input class="input_corto" disabled type="text"
						value="${arrDatosGeneralPlantancion["item_planta_"  + indiceSemillas]}"/>
					</td>
					<td>
						${arrDatosGeneralPlantancion["nombre_planta_" + indiceSemillas]}
					</td>

					<td>
						<input class="input_cantidad_semillas" disabled type="text"
							value="${parseInt(arrDatosGeneralPlantancion["tray_planta_" + indiceSemillas]).toLocaleString()}"/>
					</td>
					<td>
						<input class="input_cantidad_semillas" disabled type="text"
								value="${parseInt(arrDatosGeneralPlantancion["cantidad_de_plantas_" + indiceSemillas]).toLocaleString()}"/>
					</td>
					<td>
						<input class="input_cantidad_semillas numeros_germinacion" disabled type="text"
								value="${parseFloat(arrDatosGeneralPlantancion["numero_germinacion_planta_" + indiceSemillas]).toLocaleString(undefined, {
											minimumFractionDigits: 2
										})}"/>
					</td>
					<td>
						<button class="btn_eliminar_semilla_no_editable">
								<i class="fas fa-trash-alt"></i>
							</button>
					</td>
				</tr>
				`;
		}
		guardadoHabilitado = false;
		$("#btn_guardar_plantacion").addClass('btn-secondary');
		$("#btn_guardar_plantacion").removeClass('btn-success');

		$("#btn_agregar_plantas").addClass('btn-secondary');
		$("#btn_agregar_plantas").removeClass('btn-success');
		jQuery.ajaxSetup({
			async: true
		});
	}

	function eliminarSemilla(indiceSemillas) {
		arrDatosGeneralPlantancion["producto_activo_" + indiceSemillas] = 0;
		redibujarTabla();

	}

	function extraerNombreElemenosSeleccionados(idSelect = "cod_semillas") {

		var selectElement = document.getElementById(idSelect);
		var nombresExtraidos = [];

		for (var i = 0; i < selectElement.selectedOptions.length; i++) {
			nombresExtraidos.push(selectElement.selectedOptions[i].text);
		}

		return nombresExtraidos;
	}

	function sleep(milliseconds) {
		return new Promise(resolve => setTimeout(resolve, milliseconds));
	}

	function inv_eliminar_trasplante_local(pNumeroTicket) {
		$.ajax({
			url: site_url + 'mod_inventario/funciones/inv_eliminar_trasplante.php',
			type: 'POST',
			data: {
				x1: pNumeroTicket

			},
		}).done(function(data) {

			var info = data.split("|");
			var mensaje = info[1];
			info[0] == 1 ? tipo = 'danger' : tipo = 'success';
			grl_mensaje('', mensaje, tipo);
			codigo_orden_compra = info[2];
			inv_vista_listado_trasplantes_local();
		}).fail(function() {
			grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
		}).always(function() {});
	}
	/*
	 * Función que permite cargar vista para ver la sección de plantacion nueva
	 */
	function inv_vista_listado_trasplantes_local() {

		$.ajax({
			type: 'POST',
			url: site_url + 'mod_inventario/ui/inv_listado_trasplantes.php',

			success: function(data) {
				$('#div_cuerpo_menu').empty();
				$('#div_cuerpo_menu').html(data);
			}
		});
	}
</script>

<body>
	<div id="overlay_loading"></div>
	<div id="message_box"></div>

	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="panel-header">
					<h1 class="translate" data-traducir_english="New Transplanting" data-traducir_spanish="Nuevo transplante">Nuevo transplante</h1>
				</div>
			</div>
			<div class="col-md-12">
				<!-- Fila #1 -->
				<div class="row">
					<div class="form-group input-group-sm col-md-3">
						<label for="fecha_entrega" class="translate" data-traducir_english="Ship Date" data-traducir_spanish="Fecha de llegada">Fecha de llegada</label>
						<div class='input-group input-group-sm fecha-planeada datetime_ship' id='date_picker_date_order'>
							<span class="input-group-addon">
								<span class="fa fa-calendar"></span>
							</span>
							<input type='text' class="form-control input requerido_harvesting_worksheet" id="fecha_entrega" />
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="numero_ticket" class="translate" data-traducir_english="Ticket Number" data-traducir_spanish="Número de ticket">Número de ticket</label>
							<input type="text" class="form-control letras input requerido" id="numero_ticket" name="numero_ticket" autocomplete="new-password" >
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="cod_localizacion" class="translate" data-traducir_english="Location" data-traducir_spanish="Localización">Localización</label>
							<select class="selectpicker show-menu-arrow requerido" data-live-search="true" title="Select" id="cod_localizacion" name="cod_localizacion">
							</select>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="cod_info_empresa" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</label>
							<select class="selectpicker show-menu-arrow requerido_envio cod_info_empresa" data-live-search="true" title="Select" id="cod_info_empresa" name="cod_info_empresa">
							</select>
						</div>
					</div>
				</div>
				<hr>
				<!-- Fila #2 -->
				<div class="row">

					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="cod_numeros_de_orden" class="translate" data-traducir_english="Order Number" data-traducir_spanish="Números de orden">Números de orden</label>
							<select class="selectpicker_numero_orden show-menu-arrow requerido" data-live-search="true" title="Select" id="cod_numeros_de_orden" name="cod_numeros_de_orden">
							</select>
						</div>
					</div>
					<div class="col-md-9">
						<div class="form-group input-group-sm">
							<label for="cod_line_item" class="translate" data-traducir_english="Line Item" data-traducir_spanish="Ítem">Ítem</label>
							<select class="selectpicker_semillas show-menu-arrow requerido" data-live-search="true" title="Select" id="cod_line_item" name="cod_line_item" multiple="multiple" data-actions-box="true">
							</select>
						</div>
					</div>



				</div>
				<!-- Fila #3 -->
				<div class="row">

					<!-- <div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="cantidad_germinacion" class="translate" data-traducir_english="Germination" data-traducir_spanish="Germinación">Germinación</label>
							<input type="text" value="1" class="form-control numeros input requerido_plantas" id="cantidad_germinacion" name="cantidad_germinacion">
						</div>
					</div> -->
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="cantidad_en_bandeja" class="translate" data-traducir_english="Tray" data-traducir_spanish="Bandeja">Bandeja</label>
							<input type="number" value="1" class="form-control  input requerido_plantas" id="cantidad_en_bandeja" name="cantidad_en_bandeja">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="cantidad_plantas" class="translate" data-traducir_english="Plants" data-traducir_spanish="Plantas">Plantas</label>
							<input type="number" value="1" class="form-control  input requerido_plantas" id="cantidad_plantas" name="cantidad_plantas">
						</div>
					</div>
					<div id="div_fecha_recibida" class="form-group input-group-sm col-md-3">
						<label for="fecha_recibo" class="translate" data-traducir_english="Date delivery" data-traducir_spanish="Fecha recibida">Fecha recibida</label>
						<div class='input-group input-group-sm  datetime_delivery' id='date_picker_fecha_recibo'>
							<span class="input-group-addon">
								<span class="fa fa-calendar"></span>
							</span>
							<input disabled type='text' class="form-control input requerido_harvesting_worksheet" id="fecha_recibo" />
						</div>
					</div>
					<div class="col-md-3" style="padding-top: 25px;padding-bottom: 25px;">
						<button id="btn_agregar_plantas" class="btn btn-sm btn-primary translate" data-traducir_english="Add Plants" data-traducir_spanish="Agregar plantas" type="button">Agregar plantas</button>
					</div>


				</div>
				<div class="row justify-content-md-center ">

					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="total_trays" class="translate" data-traducir_english="Total Trays" data-traducir_spanish="Total en bandeja">Total en bandeja</label>
							<input type="text" disabled value="0" class="form-control input  " id="total_trays" name="total_trays">

						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm">

							<label for="total_plantas" class="translate" data-traducir_english="Plants" data-traducir_spanish="Plantas">Plantas</label>
							<input type="text" disabled value="0" class="form-control input  " id="total_plantas" name="total_plantas">
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

	<div class="panel-footer" align="right">
		<button id="btn_eliminar_registro" class="btn btn-sm btn-primary translate hide" data-traducir_english="Delete Transplanting" data-traducir_spanish="Borrar trasplante" type="button" style="background-color: red !important; border-color: blue !important;">Borrar trasplante</button>
		<button class="btn btn-sm btn-secondary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" type="button" id="btn_guardar_plantacion">Guardar</button>
	</div>

	<!-- Tabla de registro nueva -->
	<div class="busqueda_contenedor">
		<div class="row" style="min-width:100px;">
			<div class="col-sm-12">

				<h3 class="display-4 titulo translate" align="center" data-traducir_english="Transplanting" data-traducir_spanish="Trasplante">Trasplante</h3>
				<div class="panel-body panel_cuerpo input-group-sm">
					<input autocomplete="off" type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table-editable" placeholder="Search" />
				</div>
				<div style="overflow-x:auto; overflow-y: visible;">
					<table class="table table-striped table-hover table-sm" id="dev-table-editable">
						<thead>
							<tr class="active info">
								<th width="1px" class="translate" data-traducir_english="Order Number" data-traducir_spanish="Número de orden">Número de orden</th>
								<th width="50px" class="translate" data-traducir_english="Line item" data-traducir_spanish="Ítem">Ítem</th>
								<th width="450px" class="translate" data-traducir_english="Seed" data-traducir_spanish="Finca">Seed</th>
								<th width="12px" class="translate" data-traducir_english="Tray" data-traducir_spanish="Bandeja">Bandeja</th>
								<th width="80px" class="translate" data-traducir_english="Plants" data-traducir_spanish="Plantas">Plantas</th>
								<th width="10px" class="translate" data-traducir_english="Germination" data-traducir_spanish="Germinación">Germinación</th>
								<th width="10px" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Acciones</th>
							</tr>
						</thead>
						<tbody id="cuerpo_tabla_editable">

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Modals -->
	<div class="modal fade" id="modal_confirmar_eliminacion" tabindex="-1" role="dialog" aria-labelledby="modal_confirm_box" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content modal-warning">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="modal_confirm_box"> Delete Transplanting </h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 msg-box-container">
							<i class="fa fa-exclamation-triangle fa-2x"></i>
							<span> Are you sure you want to eliminate the current Transplanting?</span>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Cancel" data-traducir_spanish="Cancelar" data-dismiss="modal">Cancelar</button>
					<button type="button" class="btn btn-sm btn-primary smooth-transition translate" data-traducir_english="Confirm" data-traducir_spanish="Confirmar" data-cod_bloque="0" id="btn_confirmar_eliminacion">Confirmar</button>
				</div>
			</div>
		</div>
	</div>
</body>