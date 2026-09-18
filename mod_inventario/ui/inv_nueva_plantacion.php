<?php
/*
* 	Registro de información de las semillas,
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
$numero_orden = 0;
$cod_granjas_usuario = str_replace(['[', '"', ']'], '', $_SESSION['cod_info_empresa']);
$datosGeneralesPlantacion = [];
$datosPlantacionEncontrados = false;
if (isset($_POST['cod_plantacion'])) {
	$cod_plantacion = $_POST['cod_plantacion'];
	$datosGeneralesPlantacion = $DB_INV->inv_total_semillas_por_granjas($cod_plantacion);
}
if (isset($_POST['numero_orden'])) {
	$numero_orden = $_POST['numero_orden'];

	$datosGeneralesPlantacion = $DB_INV->inv_buscar_plantacion_por_numero_de_orden($numero_orden);
	if (count($datosGeneralesPlantacion) > 0) {
		$datosPlantacionEncontrados = true;
	}
	// print(json_encode($datosGeneralesPlantacion));
}




$TOTAL = $DB_INV->inv_total_semillas_por_granjas($cod_granjas_usuario);

$cod_semilla = 0;
if (isset($_POST['cod_semilla'])) {
	$cod_semilla = $_POST['cod_semilla'];
}
$SEMILLA = $DB_INV->inv_obtener_info_inventario_semilla($cod_semilla);

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
    .input_muy_corto {
			/* text-align: right; */
			max-width: 45px;
		}

		.btn_eliminar_semilla_editable {
			background-color: red;
			color: white;
			border: none;
			font-weight: bold;
			text-transform: uppercase;
			width: 45%;
			padding: 5px 0px;
			margin: 0 auto;
		}

		.btn_eliminar_semilla_editable i {
			/* Estilos para el ícono de eliminación */
			color: white;
			margin-right: 5px;

			/* Agrega espacio entre el ícono y el texto si lo tienes */
		}


		.btn_eliminar_semilla_editable:hover {
			background-color: darkred;
			cursor: pointer;
			/* Cambia el cursor a una mano para indicar que es interactivo */
		}



		.btn_reducion_habilitado {
			background-color: #64a377;
			color: white;
			border: none;
			font-weight: bold;
			text-transform: uppercase;
			width: 50%;
			padding: 5px 0px;
		}

		.btn_reducion_deshabilitado {
			background-color: #a2a8a4;
			color: white;
			border: none;
			font-weight: bold;
			text-transform: uppercase;
			width: 50%;
			padding: 5px 0px;
		}
	</style>
</head>

<script type="text/javascript">
	jQuery.ajaxSetup({
		async: false
	});

	//Variables para cuando se ingrese desde el listado de plantaciones
	var datosExtraidos;
	var pNumeroOrden;
	var actualizarPlantancion = false;
	var registrosPreviamenteGuardados = false;
	// --------------------------------------------------------------
	// Clases necesaria: dentro-de-rango, fuera-de-rango
	var claseRangoCantidadSemillas = "fuera-de-rango btnCantidadSemillas"
	var clasesReducirDeInventario = "btn_reducion_deshabilitado"

	var trCuerpoTablaSemillasEditables;

	var arrDatosGeneralPlantancion = {};
	var arrDatosDeSemilla = [];
	var arrIndiceDatosSemillas = [];

	var indiceSemillasEditables = 0;
	var indexGeneralSemillasEditables = 0;
	var indexInternoSemillasEditables = 0;

	var fecha_hoy = new Date(<?php echo time() * 1000; ?>);
	var hoy = (fecha_hoy.getMonth() + 1) + "/" + fecha_hoy.getDate() + "/" + fecha_hoy.getFullYear();
	var hoy_default = fecha_hoy.getFullYear() + "-" + (fecha_hoy.getMonth() + 1) + "-" + fecha_hoy.getDate();
	var valorInicialCaptado = 0;
	var guardadoHabilitado = false;
	var cantidadDentroDeRango = true;

	grl_overlay_loading('');

	$(document).ready(function() {

		$(".btn-group.bootstrap-select.show-tick.show-menu-arrow.requerido").remove();
		$(".btn-group.bootstrap-select.show-tick.show-menu-arrow.requerido_envio").remove();
		$(window).scroll(function() {
			var scrollPos = $(window).scrollTop();

			if (scrollPos > 1) {
				$(".requerido").removeClass("open");
				$(".requerido_envio").removeClass("open");
			}
		});
		trCuerpoTablaSemillasEditables = document.getElementById("cuerpo_tabla_editable")
		pNumeroOrden = "<?php echo $numero_orden; ?>";
		if (pNumeroOrden != 0) {
			datosExtraidos = inv_buscar_plantacion("", pNumeroOrden);
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


		$(".fecha_inicial").datetimepicker({
			locale: moment.locale('en', {
				week: {
					dow: 0
				}
			}),
			// minDate: hoy,
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
		conf_constructor_listado_estados_de_plantacion();
		arrDatosDeSemilla = inv_constructor_listado_semillas_por_invernadero_activas($("#cod_info_empresa").val(), 'cod_semillas')


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
		$('#checkbox_habilitar_sumar_restar').change(function(event) {
			/* Act on the event */
			event.preventDefault();
			event.stopPropagation();
			if ($('#checkbox_habilitar_sumar_restar').attr('checked')) {
				$('#div_sumar_restar').addClass('hide');
				$('#checkbox_habilitar_sumar_restar').removeAttr('checked');
			} else {
				$('#div_sumar_restar').removeClass('hide');
				$('#checkbox_habilitar_sumar_restar').attr('checked', 'checked');
			}
		});


		$('#modal_loading').modal('hide');

		jQuery.ajaxSetup({
			async: true
		});
		if (pNumeroOrden != 0) {
			var jsonObject = JSON.parse(datosExtraidos);

			if (Object.keys(jsonObject).length > 0) {
				registrosPreviamenteGuardados = true
				$("#btn_eliminar_plantancion").removeClass("hide");
				generarTabla(jsonObject)
			}
		}

	});

	$('#btn_guardar_plantacion').click(function(event) {
		if (guardadoHabilitado) {
			
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

			// Validar los campos de Expected Sow y Expected Delivery
			// if(){
				
			// }
			
			if (error == 0) {
				grl_overlay_loading('');
				$('#modal_loading').modal('hide');
				$('#modal_loading').on('hidden.bs.modal', function() {

					recopilarFechasEditables();
					const cantidadDeSemillasRegistradas = arrIndiceDatosSemillas.length;
					let cantidadPropiedadesRecorridas = 0;
					let cantidadDeBloquesRecorridos = 1; ///FIXED
					let arrDatosGeneralPlantancionParcial = {};
					if (!actualizarPlantancion) {

					// Guardar por lotes de 50 en 50
					// Tomar en cuenta que si se agrega otro parametro a la semilla debe coincidir la cantidad con el 12 que está quemado en 
					// la condicional de cantidadPropiedadesRecorridas
						for (var clave in arrDatosGeneralPlantancion) {
							if (arrDatosGeneralPlantancion.hasOwnProperty(clave)) {
								cantidadPropiedadesRecorridas += 1;
								var valor = arrDatosGeneralPlantancion[clave];
								arrDatosGeneralPlantancionParcial[clave] = valor;

								if (cantidadPropiedadesRecorridas > 12) {
									cantidadPropiedadesRecorridas = 0;
									cantidadDeBloquesRecorridos += 1;
								}
								if (cantidadDeBloquesRecorridos >= 50) {

									inv_guardar_plantacion(arrDatosGeneralPlantancionParcial, cantidadDeSemillasRegistradas, false);
									cantidadDeBloquesRecorridos = 0;
									arrDatosGeneralPlantancionParcial = {};
								}
							}
						}
						
						if (cantidadDeBloquesRecorridos > 0) {

							cantidadDeBloquesRecorridos = 0;
							inv_guardar_plantacion(arrDatosGeneralPlantancionParcial, cantidadDeSemillasRegistradas);
							// inv_guardar_plantacion(arrDatosGeneralPlantancion, arrIndiceDatosSemillas); //Primera versión de la invocación de la llamada
						}

					} else {

						// console.log({
						// 	arrDatosGeneralPlantancion
						// });
						// Guardar por lotes de 50 en 50
						// Tomar en cuenta que si se agrega otro parametro a la semilla debe coincidir la cantidad con el 12 que está quemado en 
						// la condicional de cantidadPropiedadesRecorridas
						// console.log(arrDatosGeneralPlantancion);
						for (var clave in arrDatosGeneralPlantancion) {
							if (arrDatosGeneralPlantancion.hasOwnProperty(clave)) {
                cantidadPropiedadesRecorridas += 1;
                var valor = arrDatosGeneralPlantancion[clave];
                arrDatosGeneralPlantancionParcial[clave] = valor;

                if (cantidadPropiedadesRecorridas > 12) {
                  cantidadPropiedadesRecorridas = 0;
                  cantidadDeBloquesRecorridos += 1;
                }
                if (cantidadDeBloquesRecorridos >= 50) {
                  console.log(arrDatosGeneralPlantancionParcial);
                  inv_actualizar_plantacion(pNumeroOrden, arrDatosGeneralPlantancionParcial, cantidadDeSemillasRegistradas, false);
                  cantidadDeBloquesRecorridos = 0;
                  arrDatosGeneralPlantancionParcial = {};
                }
							}
						}

						if (cantidadDeBloquesRecorridos > 0) {


							inv_actualizar_plantacion(pNumeroOrden, arrDatosGeneralPlantancionParcial, cantidadDeSemillasRegistradas);
							// inv_actualizar_plantacion(pNumeroOrden, arrDatosGeneralPlantancion, cantidadDeSemillasRegistradas); //Primera versión de la invocación de la llamada
						}

					}
				});
			} else {
				grl_mensaje('You must fill out all marked fields', 'Please check', 'warning');
			}
		} else {
			grl_mensaje('You must load seeds for planting.', '', 'warning');
		}

	});
	$('#cod_info_empresa').change(function(event) {
		/* Act on the event */

		// inv_constructor_listado_semillas('cod_semillas', $(this).val());
		arrDatosDeSemilla = inv_constructor_listado_semillas_por_invernadero_activas($(this).val(), 'cod_semillas')
	});

	$('#cod_semillas').change(function(event) {
		arrDatosDeSemilla
		let idSemiilas = $('#cod_semillas').val()
		if (Array.isArray(idSemiilas) && idSemiilas.length === 1) {

			$("#cantidad_resembrar").val(arrDatosDeSemilla["over_seed_" + idSemiilas[0]])
		} else {
			$("#cantidad_resembrar").val("")
		}
	});



	$('#cantidad_semillas').on('input', function() {
		const cantSemillas = $(this).val();
		if (cantSemillas > 0 && cantSemillas != '') {
			var cantTotalSemillas = parseInt(cantSemillas) + (($('#cantidad_resembrar').val() / 100) * cantSemillas);
			// cantTotalSemillas = cantTotalSemillas.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
			$('#total_semillas').val(parseInt(cantTotalSemillas).toLocaleString());
			$('#total_semillas_para_guardar').val(parseInt(cantTotalSemillas));
		} else {
			$('#total_semillas').val(0);
			$('#total_semillas_para_guardar').val(0);
		}
	});

	$('#cantidad_resembrar').on('input', function() {
		var cantResembrar = $(this).val();
		if ($('#cantidad_semillas').val() > 0) {

			var cantTotalSemillas = parseInt($('#cantidad_semillas').val()) + ((cantResembrar / 100) * $('#cantidad_semillas').val());
			// cantTotalSemillas = cantTotalSemillas.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
			$('#total_semillas').val(parseInt(cantTotalSemillas).toLocaleString());
			$('#total_semillas_para_guardar').val(cantTotalSemillas);
		}

	});
	$('#btn_eliminar_plantancion').click(function(event) {
		$('#modal_confirmar_eliminar_plantacion').modal('show');
	});

	$('#btn_confirmar_eliminar_plantancion').click(function(event) {
		$('#modal_confirmar_eliminar_plantacion').modal('hide');
		grl_overlay_loading('');
		$('#modal_loading').modal('hide');
		$('#modal_loading').on('hidden.bs.modal', function() {
			inv_eliminar_plantacion(pNumeroOrden);
		});

	});
	$('#btn_agregar_semillas').click(function(event) {

		/* Act on the event */
		var error = 0;
		$(".input.requerido_semilla").map(function() {
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

		if ($("#fecha_inicial").val() == '' || $("#fecha_final").val() == '') {
			error = 3;
		}

		if (error == 0) {
			jQuery.ajaxSetup({
				async: false
			});


			let codigos_semillas = $("#cod_semillas").val()
			let fecha_inicial = $("#fecha_inicial").val()
			let fecha_final = $("#fecha_final").val()
			let cantidad_por_plantacion = $("#cantidad_por_plantacion").val()
			let cantidad_semillas = $("#cantidad_semillas").val()
			let cantidad_resembrar = $("#cantidad_resembrar").val()
			let total_semillas = $("#total_semillas_para_guardar").val()
			let habilitarReducirDeInventario = $('#habilitar_reducir_de_inventario').is(':checked') ? 1 : 0;


			let nombresSemillas = extraerNombreElemenosSeleccionados("cod_semillas");

			var arrFechaPreviamenteRegistrada = [];

			let fechaInicialRegistrada
			let fechaFinalRegistrada



			indiceSemillasEditables = indiceSemillasEditables + 1;


			codigos_semillas.forEach((cod_inventario_indice, index) => {
				arrDatosGeneralPlantancion["item_semilla_" + indexGeneralSemillasEditables] = indexGeneralSemillasEditables + 1; // Se suma "+1" para que se muestre desde el 1 en la tabla
				arrDatosGeneralPlantancion["cod_inventario_semilla_" + indexGeneralSemillasEditables] = cod_inventario_indice;
				arrDatosGeneralPlantancion["cantidad_por_plantacion_semillas_" + indexGeneralSemillasEditables] = arrDatosDeSemilla["semillas_pre_plantadas_" + cod_inventario_indice];
				arrDatosGeneralPlantancion["cantidad_semilla_" + indexGeneralSemillasEditables] = cantidad_semillas;
				arrDatosGeneralPlantancion["overseed_semilla_" + indexGeneralSemillasEditables] = parseInt(cantidad_resembrar);
				arrDatosGeneralPlantancion["total_semilla_" + indexGeneralSemillasEditables] = total_semillas;
				arrDatosGeneralPlantancion["nombre_semilla_" + indexGeneralSemillasEditables] = nombresSemillas[index];
				arrDatosGeneralPlantancion["fecha_inicial_" + indexGeneralSemillasEditables] = fecha_inicial;
				arrDatosGeneralPlantancion["fecha_final_" + indexGeneralSemillasEditables] = fecha_final;
				arrDatosGeneralPlantancion["producto_activo_" + indexGeneralSemillasEditables] = 1;
				arrDatosGeneralPlantancion["edad_" + indexGeneralSemillasEditables] = 0;
				arrDatosGeneralPlantancion["habilitar_reducir_de_inventario_" + indexGeneralSemillasEditables] = habilitarReducirDeInventario;
				arrIndiceDatosSemillas.push(indexGeneralSemillasEditables);

				indexGeneralSemillasEditables = indexGeneralSemillasEditables + 1;
			});

			trCuerpoTablaSemillasEditables.innerHTML = '';

			for (const indiceSemillas of arrIndiceDatosSemillas) {
				if (arrDatosGeneralPlantancion["producto_activo_" + indiceSemillas] == 1) {
					if (arrDatosGeneralPlantancion["habilitar_reducir_de_inventario_" + indiceSemillas] == 1) {
						clasesReducirDeInventario = "btn_reducion_habilitado";
					} else {
						clasesReducirDeInventario = "btn_reducion_deshabilitado"

					}
					if (parseFloat(arrDatosDeSemilla["cantidad_semilla_" + arrDatosGeneralPlantancion["cod_inventario_semilla_" + indiceSemillas]]) >= parseFloat(arrDatosGeneralPlantancion["cantidad_semilla_" + indiceSemillas])) {
						claseRangoCantidadSemillas = "dentro-de-rango btnCantidadSemillas";
					} else {
						cantidadDentroDeRango = false;
					}

					trCuerpoTablaSemillasEditables.innerHTML += `
				<tr id="fila_semilla_${indiceSemillas}">
					<td>
						<button onclick="activarEdicionDeCelda(
								'#input_indice_item_${indiceSemillas}',
								'#btn_cantindad_indice_item_${indiceSemillas}'
								)" class="btnCantidadSemillas"
								id="btn_cantindad_indice_item_${indiceSemillas}"  > ${arrDatosGeneralPlantancion["item_semilla_" + indiceSemillas]}
							</button>

							<input class="input_corto" hidden type="text"
								id="input_indice_item_${indiceSemillas}"
								onblur="salirDelInputDeCantidad('#input_indice_item_${indiceSemillas}', '#btn_cantindad_indice_item_${indiceSemillas}')"
								value="${arrDatosGeneralPlantancion["item_semilla_" + indiceSemillas]}"
								onkeydown="detectarTeclasDeSalida(event, '#input_indice_item_${indiceSemillas}', '#btn_cantindad_indice_item_${indiceSemillas}')"
								oninput="detectarDatosEntradaDeTecladosNumerosITEM('#input_indice_item_${indiceSemillas}', '#btn_cantindad_indice_item_${indiceSemillas}','item_semilla_${indiceSemillas}')"
								placeholder="Name of seeds"/>
					</td>
					<td>${arrDatosGeneralPlantancion["nombre_semilla_" + indiceSemillas]}</td>
					<td>
							<div class='input-group input-group-sm fecha-planeada fecha_registrada_requerida fecha_inicial_registrada_${indiceSemillas}'>
									<span class="input-group-addon">
										<span class="fa fa-calendar"></span>
									</span>
									<input type='text' class="form-control input requerido_harvesting_worksheet" id="fecha_inicial_registrada_${indiceSemillas}" />
							</div>
					</td>
					<td>
						<div class='input-group input-group-sm fecha-planeada fecha_registrada_requerida fecha_final_registrada_${indiceSemillas}' >
								<span class="input-group-addon">
									<span class="fa fa-calendar"></span>
								</span>
								<input type='text' class="form-control input requerido_harvesting_worksheet" id="fecha_final_registrada_${indiceSemillas}" />
						</div>
					</td>
					<td>
						<button onclick="activarEdicionDeCelda(
								'#input_cantidad_plantacion_previa_${indiceSemillas}',
								'#btn_cantindad_por_plantacion${indiceSemillas}'
								)" class="btnCantidadSemillas"
								id="btn_cantindad_por_plantacion${indiceSemillas}"  > ${arrDatosGeneralPlantancion["cantidad_por_plantacion_semillas_" + indiceSemillas]}
							</button>

							<input class="input_cantidad_semillas" hidden type="text"
								id="input_cantidad_plantacion_previa_${indiceSemillas}"
								onblur="salirDelInputDeCantidad('#input_cantidad_plantacion_previa_${indiceSemillas}', '#btn_cantindad_por_plantacion${indiceSemillas}')"
								value="${arrDatosGeneralPlantancion["cantidad_por_plantacion_semillas_" + indiceSemillas]}"
								onkeydown="detectarTeclasDeSalida(event, '#input_cantidad_plantacion_previa_${indiceSemillas}', '#btn_cantindad_por_plantacion${indiceSemillas}')"
								oninput="detectarDatosEntradaDeTecladosNumerosPerPlanting('#input_cantidad_plantacion_previa_${indiceSemillas}', '#btn_cantindad_por_plantacion${indiceSemillas}', 'cantidad_por_plantacion_semillas_${indiceSemillas}')"
								placeholder="Name of seeds"/>
					</td>
						<td>
						  <button onclick="activarEdicionDeCelda(
								'#input_cantidad_semillas_editable_${indiceSemillas}',
								'#btn_cantidad_semillas_editable_${indiceSemillas}'
								)" class="${claseRangoCantidadSemillas}"
								id="btn_cantidad_semillas_editable_${indiceSemillas}"> ${parseInt(arrDatosGeneralPlantancion["cantidad_semilla_" + indiceSemillas]).toLocaleString()}
							</button>

							<input class="input_cantidad_semillas" hidden type="text"
								id="input_cantidad_semillas_editable_${indiceSemillas}"
								onblur="salirDelInputDeCantidad('#input_cantidad_semillas_editable_${indiceSemillas}', '#btn_cantidad_semillas_editable_${indiceSemillas}')"
								value="${arrDatosGeneralPlantancion["cantidad_semilla_" + indiceSemillas]}"

								onkeydown="detectarTeclasDeSalidaCantidadSemillas(event,
								'#input_cantidad_semillas_editable_${indiceSemillas}',
								'#btn_cantidad_semillas_editable_${indiceSemillas}',
								${indiceSemillas})"

								oninput="detectarDatosEntradaDeTecladosNumerosCantidadSemillas('#input_cantidad_semillas_editable_${indiceSemillas}',
								'#btn_cantidad_semillas_editable_${indiceSemillas}',
								${indiceSemillas}, 'cantidad_semilla_${indiceSemillas}')"
								placeholder="Name of seeds"/>
					</td>
					<td>
						<button onclick="activarEdicionDeCeldaPorcentaje(
              '#input_cantidad_porcentual_${indiceSemillas}',
              '#btn_cantidad_porcentual_${indiceSemillas}'
              )" class="btnCantidadSemillas"
              id="btn_cantidad_porcentual_${indiceSemillas}"  > ${parseInt(arrDatosGeneralPlantancion["overseed_semilla_" + indiceSemillas]).toLocaleString()}%
            </button>

            <input class="input_cantidad_semillas" hidden type="number"
              id="input_cantidad_porcentual_${indiceSemillas}"
              onblur="salirDelInputDeCantidad('#input_cantidad_porcentual_${indiceSemillas}', '#btn_cantidad_porcentual_${indiceSemillas}')"
              value="${arrDatosGeneralPlantancion["overseed_semilla_" + indiceSemillas]}"
              onkeydown="detectarTeclasDeSalidaPorcentaje(event, '#input_cantidad_porcentual_${indiceSemillas}', '#btn_cantidad_porcentual_${indiceSemillas}',${indiceSemillas})"
              oninput="detectarDatosEntradaDeTecladosPorcentaje('#input_cantidad_porcentual_${indiceSemillas}',
              '#btn_cantidad_porcentual_${indiceSemillas}',
              '#btn_cantidad_semillas_editable_${indiceSemillas}',
              ${indiceSemillas}, 'overseed_semilla_${indiceSemillas}')"
              placeholder="Name of seeds"/>
					</td>
          <td>
						<button onclick="activarEdicionDeCeldaEdad(
              '#input_edad_${indiceSemillas}',
              '#btn_edad_${indiceSemillas}'
              )" class="btnEdad"
              id="btn_edad_${indiceSemillas}">0
            </button>

            <input class="input_muy_corto" hidden type="number"
              id="input_edad_${indiceSemillas}"
              onblur="salirDelInputDeEdad('#input_edad_${indiceSemillas}', '#btn_edad_${indiceSemillas}')"
              value=""
              onkeydown="detectarTeclasDeSalida(event, '#input_edad_${indiceSemillas}', '#btn_edad_${indiceSemillas}')"
              oninput="detectarDatosDeEntradaEdadITEM('#input_edad_${indiceSemillas}', '#btn_edad_${indiceSemillas}','edad_${indiceSemillas}')"
              placeholder="Age"/>
					</td>
					<td id="total_semillas_${indiceSemillas}" > 
						${parseInt(arrDatosGeneralPlantancion["total_semilla_" + indiceSemillas]).toLocaleString()}
						<input hidden type="text" value="${arrDatosGeneralPlantancion["total_semilla_" + indiceSemillas]}" id="input_total_semillas_${indiceSemillas}"/>
					</td>
					<td >
						<button class="btn_eliminar_semilla_editable"onclick="eliminarSemilla(${indiceSemillas})" >
							<i class="fas fa-trash-alt"></i>
						</button>
						<button ${registrosPreviamenteGuardados == true ? "style=\"display: none\"": ""} id="btn_alternar_reducir_de_inventario_${indiceSemillas}" class="${clasesReducirDeInventario}"onclick="alternarReducirDeInventario(${indiceSemillas})" >
							<i class="fas fa-dolly"></i>
						</button>
						
					</td>
				</tr>
				`;
					claseRangoCantidadSemillas = "fuera-de-rango btnCantidadSemillas"
				}

			}

			guardadoHabilitado = true;
			$("#btn_guardar_plantacion").removeClass('btn-secondary');
			$("#btn_guardar_plantacion").addClass('btn-success');
			establecerFechaSemillaEditable();


			$("#fecha_inicial").val()
			$("#fecha_final").val("")
			$("#cantidad_por_plantacion").val("")
			$("#cantidad_semillas").val("")
			$("#cantidad_resembrar").val(0)
			$("#total_semillas").val("")

			$('.selectpicker_semillas').val('');
			$('.selectpicker_semillas').selectpicker('refresh');
			// inv_constructor_listado_semillas_activas('cod_semillas')
			jQuery.ajaxSetup({
				async: true
			});

		} else {
			switch (error) {
				case 1:
					grl_mensaje('', 'You must enter all necessary amounts', 'warning');
					break;
				case 2:
					grl_mensaje('', 'You must select at least one seed', 'warning');
					break;
				case 3:
					grl_mensaje('', 'You need to select the end date', 'warning');
					break;
			}
		}
	});

	async function establecerFechaSemillaEditable() {
		// await sleep(1000); // Pausa la ejecución los milisegundos que se envien como parametro
		for (const indiceSemillas of arrIndiceDatosSemillas) {

			try {


				$(".fecha_inicial_registrada_" + indiceSemillas).datetimepicker({
					format: 'MM-DD-YYYY',
					defaultDate: arrDatosGeneralPlantancion["fecha_inicial_" + indiceSemillas],
					// defaultDate: '09-30-2023 08:30:04',
					locale: moment.locale('en', {
						week: {
							dow: 0
						}
					}),
					// minDate: hoy,
					icons: {
						time: "fa fa-clock-o",
						date: "fa fa-calendar",
						up: "fa fa-arrow-up",
						down: "fa fa-arrow-down",
						previous: 'fa fa-arrow-left',
						next: 'fa fa-arrow-right',
					}
				});

				// await sleep(1000);
				$(".fecha_final_registrada_" + indiceSemillas).datetimepicker({
					format: 'MM-DD-YYYY',
					defaultDate: arrDatosGeneralPlantancion["fecha_final_" + indiceSemillas],
					locale: moment.locale('en', {
						week: {
							dow: 0
						}
					}),
					// minDate: hoy,
					icons: {
						time: "fa fa-clock-o",
						date: "fa fa-calendar",
						up: "fa fa-arrow-up",
						down: "fa fa-arrow-down",
						previous: 'fa fa-arrow-left',
						next: 'fa fa-arrow-right',
					}
				});
			} catch (error) {
				continue;
			}
		}
	}
	async function establecerFechaPreviamenteRegistradasSemillaEditable() {
		// await sleep(1000); // Pausa la ejecución los milisegundos que se envien como parametro

		for (const indiceSemillas of arrIndiceDatosSemillas) {
			try {


				$(".fecha_inicial_registrada_" + indiceSemillas).datetimepicker({
					format: 'MM-DD-YYYY',
					defaultDate: arrDatosGeneralPlantancion["fecha_inicial_" + indiceSemillas],
					// defaultDate: '09-30-2023 08:30:04',
					locale: moment.locale('en', {
						week: {
							dow: 0
						}
					}),
					// minDate: hoy,
					icons: {
						time: "fa fa-clock-o",
						date: "fa fa-calendar",
						up: "fa fa-arrow-up",
						down: "fa fa-arrow-down",
						previous: 'fa fa-arrow-left',
						next: 'fa fa-arrow-right',
					}
				});

				// await sleep(1000);
				$(".fecha_final_registrada_" + indiceSemillas).datetimepicker({
					format: 'MM-DD-YYYY',
					defaultDate: arrDatosGeneralPlantancion["fecha_final_" + indiceSemillas],
					locale: moment.locale('en', {
						week: {
							dow: 0
						}
					}),
					// minDate: arrDatosGeneralPlantancion["fecha_inicial_" + indiceSemillas],
					icons: {
						time: "fa fa-clock-o",
						date: "fa fa-calendar",
						up: "fa fa-arrow-up",
						down: "fa fa-arrow-down",
						previous: 'fa fa-arrow-left',
						next: 'fa fa-arrow-right',
					}
				});
			} catch (error) {
				continue;
			}
		}
	}

	async function recopilarFechasEditables() {

		for (const indiceSemillas of arrIndiceDatosSemillas) {
			arrDatosGeneralPlantancion["fecha_inicial_" + indiceSemillas] = $("#fecha_inicial_registrada_" + indiceSemillas).val();
			arrDatosGeneralPlantancion["fecha_final_" + indiceSemillas] = $("#fecha_final_registrada_" + indiceSemillas).val();
		}
	}


	function activarEdicionDeCelda(idInput, idBoton) {
		let btnActivarInput = $(idBoton);

		let miInput = $(idInput);
		miInput.toggle(); // Alterna la visibilidad del input
		miInput.prop('disabled', false);
		btnActivarInput.toggle(); // Alterna la visibilidad del input
		miInput.focus();
		valorInicialCaptado = miInput.val();

		btnActivarInput.text(parseInt(valorInicialCaptado).toLocaleString()); // Agregamos el valor del input+
	}

  function activarEdicionDeCeldaEdad(idInput, idBoton) {
		let btnActivarInput = $(idBoton);

		let miInput = $(idInput);
		miInput.toggle(); // Alterna la visibilidad del input
		miInput.prop('disabled', false);
		btnActivarInput.toggle(); // Alterna la visibilidad del input
		miInput.focus();
		valorInicialCaptado = miInput.val();

    if(isNaN(parseInt(valorInicialCaptado).toLocaleString())){
      valorInicialCaptado = 0;
    }

		btnActivarInput.text(parseInt(valorInicialCaptado).toLocaleString()); // Agregamos el valor del input+
	}

	function activarEdicionDeCeldaPorcentaje(idInput, idBoton) {
		let btnActivarInput = $(idBoton);

		let miInput = $(idInput);
		miInput.toggle(); // Alterna la visibilidad del input
		miInput.prop('disabled', false);
		btnActivarInput.toggle(); // Alterna la visibilidad del input
		miInput.focus();
		valorInicialCaptado = miInput.val();



		btnActivarInput.text(parseInt(valorInicialCaptado).toLocaleString() + "%"); // Agregamos el valor del input+
	}
	//Este método se ejecuta cada vez que se sale de un input de cantidad de semillas
	function salirDelInputDeCantidad(idInput, idBoton) {

		let btnActivarInput = $(idBoton);
		let miInput = $(idInput);
		if (btnActivarInput == undefined || miInput == undefined) return;

		valorInicialCaptado = 0

		// Deshabilitar el input cuando se pierde el foco
		miInput.toggle(0);
		miInput.prop('disabled', true);
		btnActivarInput.toggle(200); // Alterna la visibilidad del input
	}

  //Este método se ejecuta cada vez que se sale de un input de cantidad de edades
	function salirDelInputDeEdad(idInput, idBoton) {
    let btnActivarInput = $(idBoton);
    let miInput = $(idInput);
    if (btnActivarInput == undefined || miInput == undefined) return;

    valorInicialCaptado = 0;

    // Deshabilitar el input cuando se pierde el foco
    miInput.toggle(0);
    miInput.prop('disabled', true);
    btnActivarInput.toggle(200); // Alterna la visibilidad del input
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
		btnActivarInput.text(parseInt(valorInput).toLocaleString());
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
		btnActivarInput.text(parseInt(nuevoValor).toLocaleString());
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
		cambiarColorCeldaCantidadSemilla(dentroRango, btnActivarInput);

		arrDatosGeneralPlantancion[elemenArr] = nuevoValor
		miInput.val(nuevoValor);
		btnActivarInput.text(parseInt(nuevoValor).toLocaleString());
	}

	function detectarDatosEntradaDeTecladosPorcentaje(idInput, idBoton, idBtnCantidadSemilla, codigoInventarioIndice, elemenArr) {

		let btnActivarInput = $(idBoton);
		let btnCantidadSemillas = $(idBtnCantidadSemilla);
		let miInput = $(idInput);
		var valorInput = miInput.val();
		var valorInputInicial = miInput.val();
		var nuevoValor = "";
		var puntoEncontrado = false;
		var numerosDespuesDelPunto = 0;
		let dentroRango

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
		dentroRango = calcularTotalPorMedioDePorcentaje(nuevoValor, codigoInventarioIndice);

		// cambiarColorCeldaCantidadSemilla(dentroRango, btnCantidadSemillas);

		arrDatosGeneralPlantancion[elemenArr] = nuevoValor
		miInput.val(nuevoValor);
		btnActivarInput.text(parseInt(nuevoValor).toLocaleString() + "%");
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
			btnActivarInput.text(parseInt(valorInicialCaptado).toLocaleString());
			miInput.val(valorInicialCaptado);
			miInput.prop('disabled', true);
		}
	}

	function detectarTeclasDeSalidaPorcentaje(event, idInput, idBoton, codigoInventarioIndice = 1) {
		let btnActivarInput = $(idBoton);
		let miInput = $(idInput);

		var valorInput = miInput.val();

		if (event.keyCode === 13) { //Tecla "Enter"/"Entrar"
			miInput.prop('disabled', true);
		}


		if (event.keyCode === 27) { // Tecla "ESC"
			// Aquí puedes ejecutar el código que deseas cuando se presiona "ESC"
			calcularTotalPorMedioDePorcentaje(valorInicialCaptado, codigoInventarioIndice);

			btnActivarInput.text(parseInt(valorInicialCaptado).toLocaleString() + "%");
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
			calcularTotalPorMedioDeCantidad(valorInicialCaptado, indiceSemillas);
			btnActivarInput.text(parseInt(valorInicialCaptado).toLocaleString());
			miInput.val(valorInicialCaptado);
			miInput.prop('disabled', true);


		}
	}


	function extraerNombreElemenosSeleccionados(idSelect = "cod_semillas") {

		var selectElement = document.getElementById(idSelect);
		var nombresExtraidos = [];

		for (var i = 0; i < selectElement.selectedOptions.length; i++) {
			nombresExtraidos.push(selectElement.selectedOptions[i].text);
		}

		return nombresExtraidos;
	}

	function calcularTotalPorMedioDeCantidad(nuevoValor, indiceSemillas) {
		let totalSemillasCorrelativo = document.getElementById('total_semillas_' + indiceSemillas);
		let dentroRango = true;

		let cantTotalSemillas = parseInt(nuevoValor) + (($('#input_cantidad_porcentual_' + indiceSemillas).val() / 100) * nuevoValor);
		totalSemillasCorrelativo.innerHTML = `${(parseInt(cantTotalSemillas).toLocaleString())}`;
		$("#input_total_semillas_" + indiceSemillas).val(parseInt(cantTotalSemillas))
		// dentroRango = arrDatosDeSemilla["cantidad_semilla_" + arrDatosGeneralPlantancion["cod_inventario_semilla_" + indiceSemillas]] >= parseFloat(cantTotalSemillas)

		dentroRango = parseFloat(arrDatosDeSemilla["cantidad_semilla_" + arrDatosGeneralPlantancion["cod_inventario_semilla_" + indiceSemillas]]) >= parseFloat(nuevoValor)

		arrDatosGeneralPlantancion["total_semilla_" + indiceSemillas] = parseInt(cantTotalSemillas)

		if (!dentroRango) {
			cantidadDentroDeRango = false;
		}


		return dentroRango;
	}

	function calcularTotalPorMedioDePorcentaje(nuevoValor, indiceSemillas) {
		var totalSemillasCorrelativo = document.getElementById('total_semillas_' + indiceSemillas);
		let dentroRango = true;

		var cantTotalSemillas = parseInt($('#input_cantidad_semillas_editable_' + indiceSemillas).val()) + ((nuevoValor / 100) * $('#input_cantidad_semillas_editable_' + indiceSemillas).val());
		totalSemillasCorrelativo.innerHTML = `${(parseInt(cantTotalSemillas).toLocaleString())}`;

		$("#input_total_semillas_" + indiceSemillas).val(parseInt(cantTotalSemillas))

		// dentroRango = arrDatosDeSemilla["cantidad_semilla_" + arrDatosGeneralPlantancion["cod_inventario_semilla_" + indiceSemillas]] >= parseFloat(cantTotalSemillas)
		dentroRango = arrDatosDeSemilla["cantidad_semilla_" + arrDatosGeneralPlantancion["cod_inventario_semilla_" + indiceSemillas]] >= $('#input_cantidad_semillas_editable_' + indiceSemillas).val()
		arrDatosGeneralPlantancion["total_semilla_" + indiceSemillas] = parseInt(cantTotalSemillas)

		if (!dentroRango) {
			cantidadDentroDeRango = false;
		}

		return dentroRango;

	}

	function cambiarColorCeldaCantidadSemilla(dentroRango, btnCantidadSemillas) {
		if (dentroRango) {
			btnCantidadSemillas.removeClass('fuera-de-rango');
			btnCantidadSemillas.addClass('dentro-de-rango');

		} else {
			btnCantidadSemillas.removeClass('dentro-de-rango');
			btnCantidadSemillas.addClass('fuera-de-rango');
		}
	}

	// Funciones para Per planting
	function detectarDatosEntradaDeTecladosNumerosPerPlanting(idInput, idBoton, elemenArr) {

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
		arrDatosGeneralPlantancion[elemenArr] = nuevoValor
		miInput.val(nuevoValor);
		btnActivarInput.text(parseInt(nuevoValor).toLocaleString());
	}

	// Funciones para ITEM
	function detectarDatosEntradaDeTecladosNumerosITEM(idInput, idBoton, elemenArr) {

		let btnActivarInput = $(idBoton);
		let miInput = $(idInput);
		var valorInput = miInput.val();
		var valorInputInicial = miInput.val();
		var nuevoValor = "";
		var puntoEncontrado = false;
		var numerosDespuesDelPunto = 0;
		console.log(elemenArr);

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
		arrDatosGeneralPlantancion[elemenArr] = nuevoValor
		miInput.val(nuevoValor);
		btnActivarInput.text(parseInt(nuevoValor).toLocaleString());
	}

  function detectarDatosDeEntradaEdadITEM(idInput, idBoton, elemenArr){
    // var definitions
    let miInput = $(idInput);
    let btnActivarInput = $(idBoton);
    // processing
    arrDatosGeneralPlantancion[elemenArr] = isNaN(miInput.val()) ? 0 : miInput.val();
		miInput.val(isNaN(miInput.val()) ? 0 : miInput.val());
		btnActivarInput.text(parseInt(isNaN(miInput.val()) ? 0 : miInput.val()).toLocaleString());
  }

	async function generarTabla(jsonObject) {
		jQuery.ajaxSetup({
			async: false
		});


		// Asignado y desactivando los inputs generales
		$("#numero_orden").val(jsonObject[0].numero_orden);
		$("#numero_orden").prop("disabled", true);

		$("#cod_info_empresa").val(jsonObject[0].cod_info_empresa);
		$("#cod_info_empresa").prop("disabled", true);

		$("#fecha_orden").val(jsonObject[0].fecha_de_orden);
		$("#fecha_orden").prop("disabled", true);

		$("#cod_estados").val(jsonObject[0].cod_estado);
		$("#cod_estados").prop("disabled", true);
		$('.selectpicker').selectpicker('refresh');

		actualizarPlantancion = true;
		for (var i = 0; i < jsonObject.length; i++) {
			var jsonPlantacion = jsonObject[i];


			arrDatosGeneralPlantancion["cod_plantacion_" + indexGeneralSemillasEditables] = jsonPlantacion.cod_plantacion;
			arrDatosGeneralPlantancion["item_semilla_" + indexGeneralSemillasEditables] = jsonPlantacion.item;
			arrDatosGeneralPlantancion["cod_inventario_semilla_" + indexGeneralSemillasEditables] = jsonPlantacion.cod_inventario;

			arrDatosGeneralPlantancion["cantidad_por_plantacion_semillas_" + indexGeneralSemillasEditables] = jsonPlantacion.per_planting;
			arrDatosGeneralPlantancion["cantidad_semilla_" + indexGeneralSemillasEditables] = jsonPlantacion.cantidad;
			arrDatosGeneralPlantancion["overseed_semilla_" + indexGeneralSemillasEditables] = jsonPlantacion.overseed;
			arrDatosGeneralPlantancion["total_semilla_" + indexGeneralSemillasEditables] = jsonPlantacion.total;
			arrDatosGeneralPlantancion["nombre_semilla_" + indexGeneralSemillasEditables] = jsonPlantacion.datos_semillas;

			arrDatosGeneralPlantancion["fecha_inicial_" + indexGeneralSemillasEditables] = jsonPlantacion.fecha_inicial;
			arrDatosGeneralPlantancion["fecha_final_" + indexGeneralSemillasEditables] = jsonPlantacion.fecha_final;
			arrDatosGeneralPlantancion["edad_" + indexGeneralSemillasEditables] = jsonPlantacion.edad;
			arrDatosGeneralPlantancion["habilitar_reducir_de_inventario_" + indexGeneralSemillasEditables] = 0;

			arrDatosGeneralPlantancion["producto_activo_" + indexGeneralSemillasEditables] = 1;

			arrIndiceDatosSemillas.push(indexGeneralSemillasEditables)

			indexGeneralSemillasEditables = indexGeneralSemillasEditables + 1;
		}
		// Generar la nueva tabla con los datos recibidos
		trCuerpoTablaSemillasEditables.innerHTML = '';
		claseRangoCantidadSemillas = "dentro-de-rango btnCantidadSemillas";
		registrosPreviamenteGuardados = true;
		for (const indiceSemillas of arrIndiceDatosSemillas) {

			trCuerpoTablaSemillasEditables.innerHTML += `
						<tr id="fila_semilla_${indiceSemillas}">
							<td>
								<button onclick="activarEdicionDeCelda(
										'#input_indice_item_${indiceSemillas}',
										'#btn_cantindad_indice_item_${indiceSemillas}'
										)" class="btnCantidadSemillas"
										id="btn_cantindad_indice_item_${indiceSemillas}"  > ${arrDatosGeneralPlantancion["item_semilla_" + indiceSemillas]}
									</button>

									<input class="input_corto" hidden type="text"
										id="input_indice_item_${indiceSemillas}"
										onblur="salirDelInputDeCantidad('#input_indice_item_${indiceSemillas}', '#btn_cantindad_indice_item_${indiceSemillas}')"
										value="${arrDatosGeneralPlantancion["item_semilla_" + indiceSemillas]}"
										onkeydown="detectarTeclasDeSalida(event, '#input_indice_item_${indiceSemillas}', '#btn_cantindad_indice_item_${indiceSemillas}')"
										oninput="detectarDatosEntradaDeTecladosNumerosITEM('#input_indice_item_${indiceSemillas}', '#btn_cantindad_indice_item_${indiceSemillas}','item_semilla_${indiceSemillas}')"
										placeholder="Name of seeds"/>
							</td>
							<td>${arrDatosGeneralPlantancion["nombre_semilla_" + indiceSemillas]}</td>
							<td>
									<div class='input-group input-group-sm fecha-planeada fecha_registrada_requerida fecha_inicial_registrada_${indiceSemillas}'>
									<span class="input-group-addon">
										<span class="fa fa-calendar"></span>
									</span>
									<input type='text' class="form-control input requerido requerido_harvesting_worksheet" id="fecha_inicial_registrada_${indiceSemillas}" />
									</div>
							</td>
							<td>
								<div class='input-group input-group-sm fecha-planeada fecha_registrada_requerida fecha_final_registrada_${indiceSemillas}' >
										<span class="input-group-addon">
											<span class="fa fa-calendar"></span>
										</span>
										<input type='text' class="form-control input requerido requerido_harvesting_worksheet" id="fecha_final_registrada_${indiceSemillas}" />
								</div>
							</td>
							<td>
								<button onclick="activarEdicionDeCelda(
										'#input_cantidad_plantacion_previa_${indiceSemillas}',
										'#btn_cantindad_por_plantacion${indiceSemillas}'
										)" class="btnCantidadSemillas"
										id="btn_cantindad_por_plantacion${indiceSemillas}"  > ${arrDatosGeneralPlantancion["cantidad_por_plantacion_semillas_" + indiceSemillas]}
									</button>

									<input class="input_cantidad_semillas" hidden type="text"
										id="input_cantidad_plantacion_previa_${indiceSemillas}"
										onblur="salirDelInputDeCantidad('#input_cantidad_plantacion_previa_${indiceSemillas}', '#btn_cantindad_por_plantacion${indiceSemillas}')"
										value="${arrDatosGeneralPlantancion["cantidad_por_plantacion_semillas_" + indiceSemillas]}"
										onkeydown="detectarTeclasDeSalida(event, '#input_cantidad_plantacion_previa_${indiceSemillas}', '#btn_cantindad_por_plantacion${indiceSemillas}')"
										oninput="detectarDatosEntradaDeTecladosNumerosPerPlanting('#input_cantidad_plantacion_previa_${indiceSemillas}', '#btn_cantindad_por_plantacion${indiceSemillas}', 'cantidad_por_plantacion_semillas_${indiceSemillas}')"
										placeholder="Name of seeds"/>
							</td>
								<td>
								<button onclick="activarEdicionDeCelda(
										'#input_cantidad_semillas_editable_${indiceSemillas}',
										'#btn_cantidad_semillas_editable_${indiceSemillas}'
										)" class="${claseRangoCantidadSemillas}"
										id="btn_cantidad_semillas_editable_${indiceSemillas}"> ${parseInt(arrDatosGeneralPlantancion["cantidad_semilla_" + indiceSemillas]).toLocaleString()}
									</button>

									<input class="input_cantidad_semillas" hidden type="text"
										id="input_cantidad_semillas_editable_${indiceSemillas}"
										onblur="salirDelInputDeCantidad('#input_cantidad_semillas_editable_${indiceSemillas}', '#btn_cantidad_semillas_editable_${indiceSemillas}')"
										value="${arrDatosGeneralPlantancion["cantidad_semilla_" + indiceSemillas]}"

										onkeydown="detectarTeclasDeSalidaCantidadSemillas(event,
										'#input_cantidad_semillas_editable_${indiceSemillas}',
										'#btn_cantidad_semillas_editable_${indiceSemillas}',
										${indiceSemillas})"

										oninput="detectarDatosEntradaDeTecladosNumerosCantidadSemillas('#input_cantidad_semillas_editable_${indiceSemillas}',
										'#btn_cantidad_semillas_editable_${indiceSemillas}',
										${indiceSemillas}, 'cantidad_semilla_${indiceSemillas}')"
										placeholder="Name of seeds"/>
							</td>
							<td>
								<button onclick="activarEdicionDeCeldaPorcentaje(
										'#input_cantidad_porcentual_${indiceSemillas}',
										'#btn_cantidad_porcentual_${indiceSemillas}'
										)" class="btnCantidadSemillas"
										id="btn_cantidad_porcentual_${indiceSemillas}"  > ${parseInt(arrDatosGeneralPlantancion["overseed_semilla_" + indiceSemillas]).toLocaleString()}%
									</button>

									<input class="input_cantidad_semillas" hidden type="number"
										id="input_cantidad_porcentual_${indiceSemillas}"
										onblur="salirDelInputDeCantidad('#input_cantidad_porcentual_${indiceSemillas}', '#btn_cantidad_porcentual_${indiceSemillas}')"
										value="${arrDatosGeneralPlantancion["overseed_semilla_" + indiceSemillas]}"
										onkeydown="detectarTeclasDeSalidaPorcentaje(event, '#input_cantidad_porcentual_${indiceSemillas}', '#btn_cantidad_porcentual_${indiceSemillas}',${indiceSemillas})"
										oninput="detectarDatosEntradaDeTecladosPorcentaje('#input_cantidad_porcentual_${indiceSemillas}',
										'#btn_cantidad_porcentual_${indiceSemillas}',
										'#btn_cantidad_semillas_editable_${indiceSemillas}',
										${indiceSemillas}, 'overseed_semilla_${indiceSemillas}')"
										placeholder="Name of seeds"/>
							</td>
							<td>
								<button onclick="activarEdicionDeCeldaEdad(
								'#input_edad_${indiceSemillas}',
								'#btn_edad_${indiceSemillas}'
								)" class="btnEdad"
								id="btn_edad_${indiceSemillas}">${isNaN(arrDatosGeneralPlantancion["edad_" + indiceSemillas]) ? 0 : arrDatosGeneralPlantancion["edad_" + indiceSemillas]}
								</button>

								<input class="input_muy_corto" hidden type="number"
								id="input_edad_${indiceSemillas}"
								onblur="salirDelInputDeEdad('#input_edad_${indiceSemillas}', '#btn_edad_${indiceSemillas}')"
								value="${isNaN(arrDatosGeneralPlantancion["edad_" + indiceSemillas]) ? 0 : arrDatosGeneralPlantancion["edad_" + indiceSemillas]}"
								onkeydown="detectarTeclasDeSalida(event, '#input_edad_${indiceSemillas}', '#btn_edad_${indiceSemillas}')"
								oninput="detectarDatosDeEntradaEdadITEM('#input_edad_${indiceSemillas}', '#btn_edad_${indiceSemillas}','edad_${indiceSemillas}')"
								placeholder="Age"/>
							</td>
							<td id="total_semillas_${indiceSemillas}" > 
								${parseInt(arrDatosGeneralPlantancion["total_semilla_" + indiceSemillas]).toLocaleString()}
								<input hidden type="text" value="${arrDatosGeneralPlantancion["total_semilla_" + indiceSemillas]}" id="input_total_semillas_${indiceSemillas}"/>
							</td>
							<td >

								<button class="btn_eliminar_semilla_editable"onclick="eliminarSemilla(${indiceSemillas})" >
									<i class="fas fa-trash-alt"></i>
								</button>

							</td>

						</tr>
						`;

		}
		establecerFechaPreviamenteRegistradasSemillaEditable();
		guardadoHabilitado = true;
		$("#btn_guardar_plantacion").removeClass('btn-secondary');
		$("#btn_guardar_plantacion").addClass('btn-success');
		jQuery.ajaxSetup({
			async: true
		});
	}

	async function redibujarTabla() {
		// await sleep(2000); // Pausa la ejecución los milisegundos que se envien como parametro
		jQuery.ajaxSetup({
			async: false
		});
		let cantidadDibujada = 0;
		// Generar la nueva tabla con los datos recibidos
		trCuerpoTablaSemillasEditables.innerHTML = '';

		for (const indiceSemillas of arrIndiceDatosSemillas) {
			if (arrDatosGeneralPlantancion["producto_activo_" + indiceSemillas] == 1) {
				cantidadDibujada++;
				if (parseFloat(arrDatosDeSemilla["cantidad_semilla_" + arrDatosGeneralPlantancion["cod_inventario_semilla_" + indiceSemillas]]) >= parseFloat(arrDatosGeneralPlantancion["cantidad_semilla_" + indiceSemillas])) {
					claseRangoCantidadSemillas = "dentro-de-rango btnCantidadSemillas";
				} else {
					cantidadDentroDeRango = false;
				}

				trCuerpoTablaSemillasEditables.innerHTML += `
						<tr id="fila_semilla_${indiceSemillas}">
							<td>
								<button onclick="activarEdicionDeCelda(
										'#input_indice_item_${indiceSemillas}',
										'#btn_cantindad_indice_item_${indiceSemillas}'
										)" class="btnCantidadSemillas"
										id="btn_cantindad_indice_item_${indiceSemillas}"  > ${arrDatosGeneralPlantancion["item_semilla_" + indiceSemillas]}
									</button>

									<input class="input_corto" hidden type="text"
										id="input_indice_item_${indiceSemillas}"
										onblur="salirDelInputDeCantidad('#input_indice_item_${indiceSemillas}', '#btn_cantindad_indice_item_${indiceSemillas}')"
										value="${arrDatosGeneralPlantancion["item_semilla_" + indiceSemillas]}"
										onkeydown="detectarTeclasDeSalida(event, '#input_indice_item_${indiceSemillas}', '#btn_cantindad_indice_item_${indiceSemillas}')"
										oninput="detectarDatosEntradaDeTecladosNumerosITEM('#input_indice_item_${indiceSemillas}', '#btn_cantindad_indice_item_${indiceSemillas}','item_semilla_${indiceSemillas}')"
										placeholder="Name of seeds"/>
							</td>
							<td>${arrDatosGeneralPlantancion["nombre_semilla_" + indiceSemillas]}</td>
							<td>
									<div class='input-group input-group-sm fecha-planeada fecha_registrada_requerida fecha_inicial_registrada_${indiceSemillas}'>
											<span class="input-group-addon">
												<span class="fa fa-calendar"></span>
											</span>
											<input type='text' class="form-control input requerido_harvesting_worksheet" id="fecha_inicial_registrada_${indiceSemillas}" />
									</div>
							</td>
							<td>
								<div class='input-group input-group-sm fecha-planeada fecha_registrada_requerida fecha_final_registrada_${indiceSemillas}' >
										<span class="input-group-addon">
											<span class="fa fa-calendar"></span>
										</span>
										<input type='text' class="form-control input requerido_harvesting_worksheet" id="fecha_final_registrada_${indiceSemillas}" />
								</div>
							</td>
							<td>
								<button onclick="activarEdicionDeCelda(
										'#input_cantidad_plantacion_previa_${indiceSemillas}',
										'#btn_cantindad_por_plantacion${indiceSemillas}'
										)" class="btnCantidadSemillas"
										id="btn_cantindad_por_plantacion${indiceSemillas}"  > ${parseInt(arrDatosGeneralPlantancion["cantidad_por_plantacion_semillas_" + indiceSemillas]).toLocaleString()}
									</button>

									<input class="input_cantidad_semillas" hidden type="text"
										id="input_cantidad_plantacion_previa_${indiceSemillas}"
										onblur="salirDelInputDeCantidad('#input_cantidad_plantacion_previa_${indiceSemillas}', '#btn_cantindad_por_plantacion${indiceSemillas}')"
										value="${arrDatosGeneralPlantancion["cantidad_por_plantacion_semillas_" + indiceSemillas]}"
										onkeydown="detectarTeclasDeSalida(event, '#input_cantidad_plantacion_previa_${indiceSemillas}', '#btn_cantindad_por_plantacion${indiceSemillas}')"
										oninput="detectarDatosEntradaDeTecladosNumerosPerPlanting('#input_cantidad_plantacion_previa_${indiceSemillas}', '#btn_cantindad_por_plantacion${indiceSemillas}', 'cantidad_por_plantacion_semillas_${indiceSemillas}')"
										placeholder="Name of seeds"/>
							</td>
								<td>
								<button onclick="activarEdicionDeCelda(
										'#input_cantidad_semillas_editable_${indiceSemillas}',
										'#btn_cantidad_semillas_editable_${indiceSemillas}'
										)" class="${claseRangoCantidadSemillas}"
										id="btn_cantidad_semillas_editable_${indiceSemillas}"> ${parseInt(arrDatosGeneralPlantancion["cantidad_semilla_" + indiceSemillas]).toLocaleString()}
									</button>

									<input class="input_cantidad_semillas" hidden type="text"
										id="input_cantidad_semillas_editable_${indiceSemillas}"
										onblur="salirDelInputDeCantidad('#input_cantidad_semillas_editable_${indiceSemillas}', '#btn_cantidad_semillas_editable_${indiceSemillas}')"
										value="${arrDatosGeneralPlantancion["cantidad_semilla_" + indiceSemillas]}"

										onkeydown="detectarTeclasDeSalidaCantidadSemillas(event,
										'#input_cantidad_semillas_editable_${indiceSemillas}',
										'#btn_cantidad_semillas_editable_${indiceSemillas}',
										${indiceSemillas})"

										oninput="detectarDatosEntradaDeTecladosNumerosCantidadSemillas('#input_cantidad_semillas_editable_${indiceSemillas}',
										'#btn_cantidad_semillas_editable_${indiceSemillas}',
										${indiceSemillas}, 'cantidad_semilla_${indiceSemillas}')"
										placeholder="Name of seeds"/>
							</td>
							<td>
								<button onclick="activarEdicionDeCeldaPorcentaje(
										'#input_cantidad_porcentual_${indiceSemillas}',
										'#btn_cantidad_porcentual_${indiceSemillas}'
										)" class="btnCantidadSemillas"
										id="btn_cantidad_porcentual_${indiceSemillas}"  > ${parseInt(arrDatosGeneralPlantancion["overseed_semilla_" + indiceSemillas]).toLocaleString()}%
									</button>

									<input class="input_cantidad_semillas" hidden type="number"
										id="input_cantidad_porcentual_${indiceSemillas}"
										onblur="salirDelInputDeCantidad('#input_cantidad_porcentual_${indiceSemillas}', '#btn_cantidad_porcentual_${indiceSemillas}')"
										value="${arrDatosGeneralPlantancion["overseed_semilla_" + indiceSemillas]}"
										onkeydown="detectarTeclasDeSalidaPorcentaje(event, '#input_cantidad_porcentual_${indiceSemillas}', '#btn_cantidad_porcentual_${indiceSemillas}',${indiceSemillas})"
										oninput="detectarDatosEntradaDeTecladosPorcentaje('#input_cantidad_porcentual_${indiceSemillas}',
										'#btn_cantidad_porcentual_${indiceSemillas}',
										'#btn_cantidad_semillas_editable_${indiceSemillas}',
										${indiceSemillas}, 'overseed_semilla_${indiceSemillas}')"
										placeholder="Name of seeds"/>
							</td>
							<td id="total_semillas_${indiceSemillas}" > 
								${parseInt(arrDatosGeneralPlantancion["total_semilla_" + indiceSemillas]).toLocaleString()}
								<input hidden type="text" value="${arrDatosGeneralPlantancion["total_semilla_" + indiceSemillas]}" id="input_total_semillas_${indiceSemillas}"/>
							</td>
							<td >

								<button class="btn_eliminar_semilla_editable" onclick="eliminarSemilla(${indiceSemillas})" >
									<i class="fas fa-trash-alt"></i>
								</button>
								<button  ${registrosPreviamenteGuardados == true ? "style=\"display: none\"": ""} id="btn_alternar_reducir_de_inventario_${indiceSemillas}" class="${clasesReducirDeInventario}"onclick="alternarReducirDeInventario(${indiceSemillas})" >
									<i class="fas fa-dolly"></i>
								</button>								
							</td>
						</tr>
						`;
				claseRangoCantidadSemillas = "fuera-de-rango btnCantidadSemillas"
			}
		}

		establecerFechaPreviamenteRegistradasSemillaEditable();
		if (cantidadDibujada < 1) {
			guardadoHabilitado = false;
			$("#btn_guardar_plantacion").addClass('btn-secondary');
			$("#btn_guardar_plantacion").removeClass('btn-success');
		} else {
			guardadoHabilitado = true;
			$("#btn_guardar_plantacion").removeClass('btn-secondary');
			$("#btn_guardar_plantacion").addClass('btn-success');
		}
		jQuery.ajaxSetup({
			async: true
		});
	}

	function eliminarSemilla(indiceSemillas) {
		arrDatosGeneralPlantancion["producto_activo_" + indiceSemillas] = 0;
		$("#fila_semilla_" + indiceSemillas).hide();
		console.log("Indice: " + indiceSemillas);
		console.log("ID de la fila: " + "fila_semilla_" + indiceSemillas);
		console.log("Codigo de plantacion desactivado: " +
			arrDatosGeneralPlantancion["cod_plantacion_" + indiceSemillas]);
		// redibujarTabla();
	}

	function alternarReducirDeInventario(indiceSemillas) {
		if (arrDatosGeneralPlantancion["habilitar_reducir_de_inventario_" + indiceSemillas] == 1) {

			arrDatosGeneralPlantancion["habilitar_reducir_de_inventario_" + indiceSemillas] = 0;
			$("#btn_alternar_reducir_de_inventario_" + indiceSemillas).removeClass("btn_reducion_habilitado");
			$("#btn_alternar_reducir_de_inventario_" + indiceSemillas).addClass("btn_reducion_deshabilitado");
			grl_mensaje('Inventory quantity will not be reduced', '', 'warning');
		} else {

			arrDatosGeneralPlantancion["habilitar_reducir_de_inventario_" + indiceSemillas] = 1;
			$("#btn_alternar_reducir_de_inventario_" + indiceSemillas).removeClass("btn_reducion_deshabilitado");
			$("#btn_alternar_reducir_de_inventario_" + indiceSemillas).addClass("btn_reducion_habilitado");
			grl_mensaje('Inventory quantity will be reduced', '', 'warning');
		}

	}

	function sleep(milliseconds) {
		return new Promise(resolve => setTimeout(resolve, milliseconds));
	}
</script>

<body>
	<div id="overlay_loading"></div>
	<div id="message_box"></div>

	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="panel-header">
					<h1 class="translate" data-traducir_english="New Planting" data-traducir_spanish="Nueva plantación">Nueva plantación</h1>
				</div>
			</div>
			<div class="col-md-12 ">
				<!-- Fila #1 -->
				<div class="row">
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="numero_orden" autocomplete="off" class="translate" data-traducir_english="Order number" data-traducir_spanish="Número de orden">Número de orden</label>
							<input type="text" class="form-control letras input requerido" id="numero_orden" name="numero_orden">
						</div>
					</div>
					<div class="col-md-3">
						<!-- <div class="form-group input-group-sm">
							<label for="cod_sembradores" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</label>
							<select class="selectpicker show-menu-arrow requerido" title="Select" id="cod_sembradores" name="cod_sembradores">
							</select>
						</div> -->
						<div class="form-group input-group-sm">
							<label for="cod_info_empresa" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</label>
							<select class="selectpicker show-menu-arrow requerido_envio cod_info_empresa" data-live-search="true" title="Select" id="cod_info_empresa" name="cod_info_empresa">
							</select>
						</div>
					</div>
					<div class="form-group input-group-sm col-md-3">
						<label for="fecha_orden" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</label>
						<div class='input-group input-group-sm fecha-planeada datetime' id='date_picker_date_order'>
							<span class="input-group-addon">
								<span class="fa fa-calendar"></span>
							</span>
							<input type='text' class="form-control input requerido_harvesting_worksheet" id="fecha_orden" />
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="cod_estados" class="translate" data-traducir_english="State" data-traducir_spanish="Estado">Estado</label>
							<select class="selectpicker show-menu-arrow requerido" data-live-search="true" title="Select" id="cod_estados" name="cod_estados">
							</select>
						</div>
					</div>

				</div>
				<hr>
				<!-- Fila #2 -->
				<div class="row">
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="cod_semillas" class="translate" data-traducir_english="Seed" data-traducir_spanish="Semillas">Semillas</label>
							<select class="selectpicker_semillas show-menu-arrow requerido" data-live-search="true" title="Select" id="cod_semillas" name="cod_semillas" multiple="multiple" data-actions-box="true">
							</select>
						</div>
					</div>
					<div class="form-group input-group-sm col-md-3">
						<label for="fecha_inicial" class="translate" data-traducir_english="Expected Sow" data-traducir_spanish="Fecha inicial">Fecha inicial</label>
						<div class='input-group input-group-sm fecha-planeada fecha_requerida fecha_inicial' id='date_picker_initial_data'>
							<span class="input-group-addon">
								<span class="fa fa-calendar"></span>
							</span>
							<input type='text' class="form-control input requerido_harvesting_worksheet" id="fecha_inicial" />
						</div>
					</div>
					<div class="form-group input-group-sm col-md-3">
						<label for="fecha_final" class="translate" data-traducir_english="Expected delivery" data-traducir_spanish="Fecha final">Fecha final</label>
						<div class='input-group input-group-sm fecha-planeada fecha_requerida fecha_final' id='date_picker_final_date'>
							<span class="input-group-addon">
								<span class="fa fa-calendar"></span>
							</span>
							<input type='text' class="form-control input requerido_harvesting_worksheet" id="fecha_final" />
						</div>
					</div>
					<!-- <div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="cantidad_por_plantacion" class="translate" data-traducir_english="Per planting" data-traducir_spanish="Cantidad plantacion previa">Cantidad plantacion previa</label>
							<input type="text" class="form-control numeros input requerido_semilla" id="cantidad_por_plantacion" name="cantidad_por_plantacion">
						</div>
					</div> -->
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="cantidad_semillas" class="translate" data-traducir_english="Quantity" data-traducir_spanish="Cantidad de semillas">Cantidad de semillas</label>
							<input autocomplete="off" type="number" class="form-control input requerido_semilla" id="cantidad_semillas" name="cantidad_semillas">
						</div>
					</div>
				</div>
				<!-- Fila #3 -->
				<div class="row">

					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="cantidad_resembrar" class="translate" data-traducir_english="Overseed (%)" data-traducir_spanish="Siembra extra (%)">Siembra extra (%)</label>
							<input type="number" max="1000" min="0" value="0" class="form-control   procentaje input requerido_semilla" id="cantidad_resembrar" name="cantidad_resembrar">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="total_semillas" class="translate" data-traducir_english="Total" data-traducir_spanish="Total">Total</label>
							<input type="text" disabled class="form-control   numeros input requerido_semilla" id="total_semillas" name="total_semillas">
							<input style="display: none;" type="text" disabled class="form-control numeros input requerido_semilla" id="total_semillas_para_guardar" name="total_semillas_para_guardar">
						</div>
					</div>
					<div class="col-md-3"> <!-- Nuevo -->
						<div class="form-group input-group-sm">
							<label for="habilitar_reducir_de_inventario" class="translate" data-traducir_english="Reduce inventory?" data-traducir_spanish="Reducir de inventario">Reducir de inventario</label><br>
							<div class="material-switch pull-left">
								<input type="checkbox" class="habilitar_checkbox_reducir_de_inventario" id="habilitar_reducir_de_inventario" name="habilitar_reducir_de_inventario" />
								<label for="habilitar_reducir_de_inventario"></label>
							</div>
						</div>
					</div>
					<div class="col-md-3" style="padding-top: 15px;">
						<button class="btn btn-sm btn-primary translate" data-traducir_english="Add Seeds" data-traducir_spanish="Agregar Semillas" type="button" id="btn_agregar_semillas">ADD</button>
					</div>
				</div>

			</div>
		</div>
	</div>

	<div class="panel-footer" align="right">
		<button id="btn_eliminar_plantancion" class="btn btn-sm btn-primary translate hide" data-traducir_english="Delete planting" data-traducir_spanish="Borrar plantación" type="button" style="background-color: red !important; border-color: blue !important;">Borrar plantación</button>
		<button class="btn btn-sm btn-secondary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" type="button" id="btn_guardar_plantacion">Guardar</button>
	</div>

	<!-- Tabla de registro nueva -->
	<div class="busqueda_contenedor">
		<div class="row" style="min-width:100px;">
			<div class="col-sm-12">

				<h3 class="display-4 titulo translate" align="center" data-traducir_english="Planting" data-traducir_spanish="Plantación">Plantanción</h3>
				<div class="panel-body panel_cuerpo input-group-sm">
					<input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table-editable" placeholder="Search" />
				</div>
				<div style="overflow-x:auto; overflow-y: visible;">
					<table class="table table-striped table-hover table-sm" id="dev-table-editable">
						<thead>
							<tr class="active info">
								<th width="1%" class="translate" data-traducir_english="Line item" data-traducir_spanish="Ítem">Ítem</th>
								<th width="25%" class="translate" data-traducir_english="Seed" data-traducir_spanish="Finca">Seed</th>
								<th width="20%" class="translate" data-traducir_english="Expected Sow" data-traducir_spanish="Ítem #">Expected Sow</th>
								<th width="20%" class="translate" data-traducir_english="Expected Delivery" data-traducir_spanish="Nombre">Expected Deliver</th>
								<th width="10%" class="translate" data-traducir_english="Per-planting" data-traducir_spanish="Variedad">Per-planting</th>
								<th width="10%" class="translate" data-traducir_english="QTY" data-traducir_spanish="Cantidad">QTY</th>
								<th width="15%" class="translate" data-traducir_english="Overseed" data-traducir_spanish="Nro. Lote">Overseed</th>
								<th width="10%" class="translate" data-traducir_english="Age" data-traducir_spanish="Edad">Age</th>
								<th width="10%" class="translate" data-traducir_english="Total" data-traducir_spanish="Total">Total</th>
								<th width="10%" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Acciones</th>
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
	<div class="modal fade" id="modal_confirmar_eliminar_plantacion" tabindex="-1" role="dialog" aria-labelledby="modal_confirm_box" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content modal-warning">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="modal_confirm_box"> Delete plantation </h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 msg-box-container">
							<i class="fa fa-exclamation-triangle fa-2x"></i>
							<span> Are you sure you want to eliminate the current plantation?</span>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Cancel" data-traducir_spanish="Cancelar" data-dismiss="modal">Cancelar</button>
					<button type="button" class="btn btn-sm btn-primary smooth-transition translate" data-traducir_english="Confirm" data-traducir_spanish="Confirmar" data-cod_bloque="0" id="btn_confirmar_eliminar_plantancion">Confirmar</button>
				</div>
			</div>
		</div>
	</div>
</body>

<script>

</script>
