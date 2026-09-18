<?php
/*
* 	Listado general de todas las semillas registradas
* 	@author 		Edwin Olivera
* 	@date 			2023-09-12
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

$cod_granjas_usuario = str_replace(['[', '"', ']'], '', $_SESSION['cod_info_empresa']);


$fecha_actual = date('Y-m-d 23:59:00');
$fecha_hace_30_dias = date('Y-m-d 00:00:00', strtotime('-30 days', strtotime($fecha_actual)));

$fechaInicial = $fecha_hace_30_dias;
$fechaFinal = $fecha_actual;

if (isset($_POST['fecha_inicial']) && isset($_POST['fecha_final'])) {
	$fechaInicial = $_POST['fecha_inicial'] . " 00:00:00";
	$fechaFinal = $_POST['fecha_final'] . " 23:59:00";
}

$SEMILLAS = [];

$cod_info_empresa = "";
$cod_variedad = "";
$flag_activacion = 2;

if (isset($_POST['cod_info_empresa']) && $_POST['cod_info_empresa'] != '') {
	$cod_info_empresa = $_POST['cod_info_empresa'];
	$cod_info_empresa = implode(',', $cod_info_empresa);
}
if (isset($_POST['cod_variedad']) && $_POST['cod_variedad'] != '') {
	$cod_variedad = $_POST['cod_variedad'];
	$cod_variedad = implode(',', $cod_variedad);
}

if (isset($_POST['flag_activacion']) && $_POST['flag_activacion'] != '') {
	$flag_activacion = $_POST['flag_activacion'];
}

$SEMILLAS = $DB_INV->inv_listado_semillas_por_granjas_filtrado_por_fecha(
	$cod_info_empresa,
	$cod_variedad,
	$flag_activacion
);
// var_dump($SEMILLAS);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>Listado de semillas</title>

	<style>
		/*  Los estilos se colocan aquí para forzar su aplicación
		unicamente en esta página
		 */
		.btnCantidadSemillas,
		.btnEdad
     {
			width:
				100% !important;
			text-align:
				right;
		}

		.input_cantidad_semillas {
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
	</style>
</head>

<script type="text/javascript">
	jQuery.ajaxSetup({
		async: false
	});
	grl_overlay_loading('');
	var arrDatosInvernadero = {}
	var cantidadNuevaGuardada = false;
	var resultadoPrevio = 0;

	$(document).ready(function() {
		$('.selectpicker').selectpicker({
			dropupAuto: 'true',
			container: 'body',
			size: '5',
			width: '100%',
			style: 'btn-sm btn-info',
			tickIcon: 'fa fa-check'
		});
		$('.cod_info_empresa2').selectpicker({
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
			$('.cod_info_empresa2').selectpicker('mobile');
		}
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

									var no_results = $('<tr class="filterTable_no_results"><td colspan="4">No results.</td></tr>')

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
		inv_constructor_listado_variedades_productos();
		conf_constructor_listado_granjas_para_listados_multiples();

		$('#dev-table').DataTable({
			"pageLength": 100,
			"dom": 'Bfrtip',
			"buttons": []
		});

		//Máscaras
		//$('.monto').mask("#,##0.000", {reverse: false, maxlength: false});
		$('.monto').mask("9999999.999");
		$('.lote').mask("999999999999999");
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


		$('#modal_seleccionar_invernadero').on('shown.bs.modal', function() {
			$('#modal_seleccionar_invernadero').addClass("modal_centrado")
			arrDatosInvernadero = inv_lisatod_invernaderos_por_nombre_de_semilla($('#nombre_de_la_semilla').val(), 'cod_info_empresa2');
			$("#mensaje_al_cambiar_cantidad").text("");
			cantidadNuevaGuardada = false;
		});
		$('#modal_seleccionar_invernadero').on('hidden.bs.modal', function(e) {
			// Código a ejecutar cuando el modal se cierra
			let idSemilla = $('#codigo_inventario_semilla_para_modal').val();
			$('#modal_seleccionar_invernadero').removeClass("modal_centrado")
			$("#mensaje_al_cambiar_cantidad").text("");
			if (!cantidadNuevaGuardada) {
				valorCantidadInicialDeSemillas = $("#input_cantidad_inicial_semilla_" + idSemilla).val();
				let btnActivarInput = $('#btn_semilla_' + idSemilla);

				let miInput = $('#input_cantidad_semilla_' + idSemilla);
				let valorParaBoton = parseInt(valorCantidadInicialDeSemillas)
				btnActivarInput.text(valorParaBoton.toLocaleString());
				miInput.val(valorCantidadInicialDeSemillas)
			} else {

				valorCantidadInicialDeSemillas = resultadoPrevio;
				$("#input_cantidad_inicial_semilla_" + idSemilla).val(valorCantidadInicialDeSemillas)
			}
		});
		$('#modal_loading').modal('hide');
		jQuery.ajaxSetup({
			async: true
		});
	});

	$('#btn_buscar').click(function(event) {
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
				error = 1;
			} else {
				$(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');
				$(this).selectpicker('setStyle', 'btn-info');
				$(this).removeClass('campo-vacio');
			}
			$(this).selectpicker('refresh');
		});
		if ($('#checkbox_habilitar_sumar_restar').attr('checked')) {
			if ($('#cantidad_sumar_restar').val() <= 0) {
				error = 2;
			}
			if ($('#razon_sumar_restar').val() == '') {
				error = 3;
			}
		}
		if (error == 0) {
			grl_overlay_loading('');
			$('#modal_loading').modal('hide');
			$('#modal_loading').on('hidden.bs.modal', function() {

				$.ajax({
						url: 'mod_inventario/ui/inv_listado_semillas.php',
						type: 'POST',
						dataType: 'html',
						data: {
							fecha_inicial: $('#fecha_inicial').val(),
							fecha_final: $('#fecha_final').val(),
							cod_info_empresa: $('#cod_info_empresa').val(),
							cod_variedad: $('#cod_variedad').val(),
							flag_activacion: $('#flag_activacion').val(),
						},
					})
					.done(function(data) {
						$('#div_cuerpo_menu').empty();
						$('#div_cuerpo_menu').html(data);
					})
					.fail(function() {
						console.log("error al filtrar por fecha la busqueda de semillas");
					})

			});
		} else {
			if (error == 2)
				grl_mensaje('Debe ingresar una cantidad mayor que 0', 'You must enter an amount greater than 0', 'warning');
			if (error == 1)
				grl_mensaje('Debe llenar todos los campos marcados', 'You must fill in all the marked fields', 'warning');
			if (error == 3)
				grl_mensaje('Debe ingresar una razón de cambio', 'You must enter a reason for change', 'warning');
		}
	});
	$('#cuerpo_tabla').on('change', '.checkbox', function(event) {
		/* Act on the event */
		event.preventDefault();
		event.stopPropagation();

		inv_cambiar_estado_semilla($(this).data('id'), ($(this).attr('checked') ? 1 : 0));
	});

	$('#div_paginacion').on('click', '.paginate_button', function(event) {
		event.preventDefault();
		jQuery.ajaxSetup({
			async: false
		});
		inv_vista_listado_semillas($(this).data('inicio'), $(this).data('limite'));
		$('.current').removeClass('current');
		$(this).addClass('current');
		jQuery.ajaxSetup({
			async: true
		});
	});
	$('.cod_info_empresa2.requerido').change(function(event) {
		var objeto = $(this);
		if (objeto.val() == null || objeto.val() == '-b' || objeto.val() == '') {
			$(this).parent('div').addClass('has-error');

		} else {
			$(this).parent('div').removeClass('has-error');
		}

		resultadoPrevio = $('#cantidad_ingresada').val();
		$("#mensaje_al_cambiar_cantidad").text("The final quantity of seeds will be: " + resultadoPrevio);

		if (resultadoPrevio <= 0) {
			$("#mensaje_al_cambiar_cantidad").css("color", "red");
		} else {
			$("#mensaje_al_cambiar_cantidad").css("color", "green");
		}
		objeto.selectpicker('refresh');
	});
	$('#btn_asignar_cantdad_semillas').click(function(event) {
		let error = 0;
		if ($("#cod_info_empresa2").val() == '' || $("#cod_info_empresa2").val() == "-b") {
			$("#cod_info_empresa2").parent('div').addClass('has-error');
			error = 1;

		} else {
			$("#cod_info_empresa2").parent('div').removeClass('has-error');
		}

		if (error == 0) {
			/* Act on the event */
			jQuery.ajaxSetup({
				async: false
			});
			$('#modal_seleccionar_invernadero').modal('hide');
			// *******************************************************************************************************************************
			let codigo_inventario_semilla_para_modal = $('#cod_info_empresa2').val(); // En la opcion de la empresa, esta el codigo de inventario para la semilla
			let nombre_de_la_semilla = $('#nombre_de_la_semilla').val();
			let cantidadSumar = $('#cantidadSumar').val();
			let cantidadRestar = $('#cantidadRestar').val();
			let cantidadIngresada = $('#cantidad_ingresada').val();

			razon = "Movimiento desde el listado de semillas"
			const fechaActual = moment().format('MM-DD-YYYY HH:mm:ss');
			// cantidadIngresada = parseInt(cantidadIngresada);
			cantidadIngresada = parseFloat(cantidadIngresada.replace(/,/g, ''));

			inv_guardar_los_cambios_de_cantidades_de_semillas(codigo_inventario_semilla_para_modal, cantidadSumar, cantidadRestar, razon, fechaActual, cantidadIngresada)


			cantidadNuevaGuardada = true;
			let btnActivarInput = $('#btn_semilla_' + $('#codigo_inventario_semilla_para_modal').val());
			let miInput = $('#input_cantidad_semilla_' + $('#codigo_inventario_semilla_para_modal').val());

			var valorAntesDeEditar = $("#input_cantidad_inicial_semilla_" + $('#codigo_inventario_semilla_para_modal').val()).val();

			valorAntesDeEditar = parseInt(valorAntesDeEditar); // Quitamos los ceros despues de la coma (,)

			// * Cambiando el valor a la empresa seleccionada en la tabla que se muestran en la pantalla
			var resultadoNuevoParaLaEmpresaSeleccionada = $('#cantidad_ingresada').val().replace(/,/g, '');

			console.log({
				arrDatosInvernadero
			})
			console.log({
				dato: arrDatosInvernadero["cod_empresa_" + $("#cod_info_empresa2").val()]
			})
			var fechaActualizacionEmpresa = $(".fecha_empresa_actualizacion_" + arrDatosInvernadero["cod_empresa_" + $("#cod_info_empresa2").val()]); //fecha_empresa_actualizacion_236_33
			console.log({
				fechaActualizacionEmpresa
			});

			fechaActualizacionEmpresa.each(function() {
				$(this).contents().first().replaceWith(fechaDeActualizacion());
			});
			// fechaActualizacionEmpresa.contents().first().replaceWith(fechaDeActualizacion());

			var datosEmpresaEnLista = $("#cant_empresa_unico_" + arrDatosInvernadero["ids_de_empresa_para_listado_en_tabla_" + $("#cod_info_empresa2").val()]); //cant_empresa_unico_236_33
			var valorNumericoDeEmpresa = datosEmpresaEnLista.find(".numerico");

			let arrDiferenciasCantidades = {};
			valorNumericoDeEmpresa.text(valorNumericoDeEmpresa.text().replace(',', ''))
			arrDiferenciasCantidades = calcularDiferenciaDeValores(valorNumericoDeEmpresa.text(), resultadoNuevoParaLaEmpresaSeleccionada)
			valorNumericoDeEmpresa.text(parseInt(resultadoNuevoParaLaEmpresaSeleccionada).toLocaleString());
			//*

			// * Cambiando el valor a la celda de edicion de cantidad

			valorAntesDeEditar = valorAntesDeEditar + parseInt(arrDiferenciasCantidades["adicion"]) - parseInt(arrDiferenciasCantidades["sustraccion"]);

			resultadoPrevio = valorAntesDeEditar;
			let nuevoValorFinal = resultadoPrevio;

			miInput.val(nuevoValorFinal);
			let valorParaBoton = nuevoValorFinal
			btnActivarInput.text(valorParaBoton.toLocaleString());
			valorCantidadInicialDeSemillas = nuevoValorFinal;

			$("#input_cantidad_inicial_semilla_" + $('#codigo_inventario_semilla_para_modal').val()).val(resultadoPrevio)
			$('#cod_info_empresa2').empty();
			$('.cod_info_empresa2').selectpicker('refresh');

			jQuery.ajaxSetup({
				async: true
			});
		} else {
			grl_mensaje('You must select at least one greenhouse', '', 'warning');

		}


	});

	function fechaDeActualizacion() {
		// Obtener la fecha actual
		let fechaActual = new Date();

		// Obtener los componentes de la fecha
		let mes = fechaActual.getMonth() + 1; // Los meses comienzan desde 0
		let dia = fechaActual.getDate();
		let anio = fechaActual.getFullYear();

		// Formatear la fecha como MM/DD/YYYY
		let fechaFormateada = anio + '/' + mes + '/' + dia;
		return fechaFormateada;
	}

	function calcularDiferenciaDeValores(valorInicial, valorNuevo) {
		let arrDiferenciasCantidades = {};
		arrDiferenciasCantidades["adicion"] = 0;
		arrDiferenciasCantidades["sustraccion"] = 0;
		if (valorInicial == valorNuevo) return arrDiferenciasCantidades;

		console.log({
			valorInicial
		})
		console.log({
			valorNuevo
		})

		if (valorInicial > valorNuevo) {

			arrDiferenciasCantidades["sustraccion"] = valorInicial - valorNuevo;

		} else {

			arrDiferenciasCantidades["adicion"] = valorNuevo - valorInicial;

		}

		return arrDiferenciasCantidades;
	}
	var valorCantidadInicialDeSemillas = 0;

	function activarEdicionDeCantidad(idSemilla) {
		let btnActivarInput = $('#btn_semilla_' + idSemilla);
		let miInput = $('#input_cantidad_semilla_' + idSemilla);
		miInput.toggle(); // Alterna la visibilidad del input
		miInput.prop('disabled', false);
		btnActivarInput.toggle(); // Alterna la visibilidad del input
		miInput.focus();
		valorCantidadInicialDeSemillas = miInput.val();
		let valorParaBoton = parseInt(valorCantidadInicialDeSemillas)
		btnActivarInput.text(valorParaBoton.toLocaleString());
	}

	//Este método se ejecuta cada vez que se sale de un input de cantidad de semillas
	function salirDelInputDeCantidad(idSemilla, nombre_de_la_semilla) {

		let btnActivarInput = $('#btn_semilla_' + idSemilla);
		let miInput = $('#input_cantidad_semilla_' + idSemilla);
		if (btnActivarInput == undefined || miInput == undefined) return;

		guardarCantidadNuevaDeCantidadDeSemillas(parseInt(valorCantidadInicialDeSemillas), parseInt(miInput.val()), idSemilla, nombre_de_la_semilla);
		valorCantidadInicialDeSemillas = 0

		// Deshabilitar el input cuando se pierde el foco
		miInput.toggle(0);
		miInput.prop('disabled', true);
		btnActivarInput.toggle(200); // Alterna la visibilidad del input
	}

	function detectarDatosEntradaDeTeclados(event, idSemilla) {

		let btnActivarInput = $('#btn_semilla_' + idSemilla);
		let miInput = $('#input_cantidad_semilla_' + idSemilla);
		var valorInput = miInput.val();
		var nuevoValor = "";
		var nuevoValorBoton = "";
		var puntoEncontrado = false;
		var numerosDespuesDelPunto = 0;

		for (var i = 0; i < valorInput.length; i++) {
			var caracter = valorInput.charAt(i);

			nuevoValor += caracter;
			// Si el caracter es un número y aún no se han ingresado 4 números después del punto, agregarlo al nuevo valor
			if (/^\d$/.test(caracter) && (!puntoEncontrado || numerosDespuesDelPunto < 4)) {
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
			// nuevoValor = 0;
		}

		miInput.val(valorInput);
		let valorParaBoton = parseInt(valorInput)
		btnActivarInput.text(valorParaBoton.toLocaleString());
	}

	function detectarTeclasDeSalida(event, idSemilla, nombre_de_la_semilla) {
		let btnActivarInput = $('#btn_semilla_' + idSemilla);
		let miInput = $('#input_cantidad_semilla_' + idSemilla);

		var valorInput = miInput.val();

		if (event.keyCode === 13) { //Tecla "Enter"/"Entrar"
			miInput.prop('disabled', true);
			valorCantidadInicialDeSemillas = parseInt(miInput.val());
			guardarCantidadNuevaDeCantidadDeSemillas(parseInt(valorCantidadInicialDeSemillas), parseInt(valorCantidadInicialDeSemillas), idSemilla, nombre_de_la_semilla, true);

		}


		if (event.keyCode === 27) { // Tecla "ESC"
			// Aquí puedes ejecutar el código que deseas cuando se presiona "ESC"
			valorCantidadInicialDeSemillas = $("#input_cantidad_inicial_semilla_" + idSemilla).val();
			let valorParaBoton = parseInt(valorCantidadInicialDeSemillas)
			btnActivarInput.text(valorParaBoton.toLocaleString());
			miInput.val(valorCantidadInicialDeSemillas);
			miInput.prop('disabled', true);

		}
	}

	function guardarCantidadNuevaDeCantidadDeSemillas(valorInicial, valorNuevo, idSemilla, nombre_de_la_semilla, duplicarValores = false) {

		valorInicial = parseInt(valorInicial);
		valorNuevo = parseInt(valorNuevo);
		if (!duplicarValores) {

			var cantidadSumar = 0;
			var cantidadRestar = 0;

			if (valorInicial === valorNuevo) return;


			var razon = "N/A";
			if (valorInicial > valorNuevo) {
				// Sustracción de cantidades
				// 50 (valorInicial) - 45 (valorNuevo) = 5
				cantidadRestar = valorInicial - valorNuevo;
				cantidadSumar = 0
			}
			if (valorInicial < valorNuevo) {
				// Adición de cantidades
				// 50 (valorInicial) - 65 (valorNuevo) = 15
				cantidadSumar = valorNuevo - valorInicial;
				cantidadRestar = 0
			}
		} else {
			cantidadSumar = valorInicial
			cantidadRestar = 0

		}

		$('#nombre_de_la_semilla').val(nombre_de_la_semilla);
		$('#valorInicial').val(valorInicial);
		$('#codigo_inventario_semilla_para_modal').val(idSemilla);
		$('#cantidadSumar').val(cantidadSumar);
		$('#cantidadRestar').val(cantidadRestar);
		$('#cantidad_ingresada').val(parseInt(valorNuevo).toLocaleString());
		$('#cantidad_modal').val(parseInt(valorNuevo).toLocaleString()); // Valor que se se muestran en la parte inferior del modal
		$('#modal_seleccionar_invernadero').modal('show');

	}
</script>

<body>
	<div id="overlay_loading"></div>
	<div id="message_box"></div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="panel-header">
					<h1 class="translate" data-traducir_english="Seeds Inventory" data-traducir_spanish="Inventario de Semillas">Inventario de Semillas</h1>
				</div>
			</div>
			<div class="col-md-12">
				<div class="row ">
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="cod_info_empresa" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</label>
							<select multiple="multiple" data-actions-box="true" class="selectpicker show-menu-arrow requerido_envio cod_info_empresa" data-live-search="true" title="Select" id="cod_info_empresa" name="cod_info_empresa">
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="cod_variedad" class="translate" data-traducir_english="Variety" data-traducir_spanish="Variedad">Variedad</label>
							<select multiple="multiple" data-actions-box="true" class="selectpicker show-menu-arrow" title="Select" id="cod_variedad" name="cod_variedad">
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="flag_activacion" class="translate" data-traducir_english="Activation status" data-traducir_spanish="Estado de activación">Estado de activación</label>
							<select class="selectpicker show-menu-arrow" title="Select" id="flag_activacion" name="flag_activacion">
								<option value="1">Active</option>
								<option value="0">Inactive</option>
							</select>
						</div>
					</div>

					<div class="col-md-3 " style="margin-top: 25px;">
						<button class="btn btn-sm btn-primary translate" data-traducir_english="Search" data-traducir_spanish="Buscar" type="button" id="btn_buscar">Buscar</button>
					</div>

				</div>
				<hr>
			</div>
		</div>

		<div class="busqueda_contenedor " style="margin-left: 10px; margin-right: 10px;">
			<div class="row" style="min-width:100px;">
				<div class="col-sm-12">
					<div class="panel panel-primary" id="panel_itemschecklist">
						<div class="panel-heading btncollapsepaso panel_cabecera" id="a_itemschecklist" role="button" data-toggle="collapse" href="#collapseitemschecklist" data-target="#collapseitemschecklist" aria-expanded="true" data-parent="#panel_itemschecklist" aria-controls="collapseitemschecklist">
							<h3 class="panel-title translate" data-traducir_english="Seeds Inventory" data-traducir_spanish="Seeds Inventory">Seeds Inventory</h3>
							<div class="pull-right">
								<span class="clickable filter" data-toggle="tooltip" title="Search" data-container="body">
									<i class="fa fa-search"></i>
								</span>
							</div>
						</div>
						<div class="panel-collapse collapse" id="collapseitemschecklist" role="tabpanel" aria-labelledby="collapseitemschecklist">
							<div class="panel-body panel_cuerpo">
								<input type="text" class="form-control" id="tabla_itemschecklist-filter" data-action="filter" data-filters="#tabla_itemschecklist" placeholder=Search />
							</div>
						</div>
						<div class="responsive_table_container">
							<table class="table display row-border table-responsive table-hover table-condensed" id="tabla_itemschecklist">
								<thead>
									<tr class="active info">
										<th width="1%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
										<th width="25%" class="translate" data-traducir_english="Name" data-traducir_spanish="Nombre">Nombre</th>
										<th width="5%" class="translate" data-traducir_english="Item #" data-traducir_spanish="Ítem #">Ítem #</th>
										<th width="10%" class="translate" data-traducir_english="Abbreviation" data-traducir_spanish="Abreviatura">Abreviatura</th>
										<th width="5%" class="translate" data-traducir_english="Last update" data-traducir_spanish="Ultima actualización">Ultima actualización</th>
										<th width="15%" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernaderos">Invernaderos</th>
										<th width="10%" class="translate" data-traducir_english="Stock" data-traducir_spanish="Cantidad">Cantidad</th>
										<th width="5%" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Acciones</th>
									</tr>
								</thead>
								<tbody id="cuerpo_tabla">
									<?php
									if (count($SEMILLAS)) {
										$correlativo = 1;
										foreach ($SEMILLAS as $semilla) {
											$idSemilla = $semilla["cod_inventario"];
											$nombreSemilla = $semilla['nombre_semilla'];
									?>
											<tr class="">
												<td><?php echo $correlativo; ?></td>
												<td><?php echo utf8_encode($semilla['nombre_semilla']); ?></td>
												<td><?php echo $semilla['codigo_semilla']; ?></td>
												<td><?php echo utf8_encode($semilla['abreviatura_semilla']); ?></td>
												<td><?php echo $semilla['fechas_de_actualizaciones']; ?></td>
												<td><?php echo utf8_encode($semilla['nombres_empresas_vinculadas']); ?></td>
												<td class="fila_seleccionable">
													<button class="btnCantidadSemillas" onclick="activarEdicionDeCantidad(<?php echo $idSemilla ?>)" id="btn_semilla_<?php echo $idSemilla ?>"><?php echo number_format($semilla['suma_cantidad_semillas_individual'], 0, ".", ","); ?></button>
													<input class="input_cantidad_semillas" hidden type="number" id="input_cantidad_semilla_<?php echo $idSemilla ?>" onblur="salirDelInputDeCantidad(<?php echo $idSemilla ?>, '<?php echo utf8_encode($nombreSemilla) ?>')" value="<?php echo  $semilla['suma_cantidad_semillas_individual']; ?>" onkeydown="detectarTeclasDeSalida(event,<?php echo $idSemilla ?>,'<?php echo utf8_encode($nombreSemilla) ?>')" oninput="detectarDatosEntradaDeTeclados(event,<?php echo $idSemilla ?>)" placeholder="">
													<input class="input_oculto" hidden type="number" id="input_cantidad_inicial_semilla_<?php echo $idSemilla ?>" value="<?php echo  $semilla['suma_cantidad_semillas_individual']; ?>">

												</td>
												<td>
													<div class="material-switch pull-right">
														<input class="checkbox" id="checkbox_<?php echo ($semilla['cod_inventario']); ?>" data-id="<?php echo ($semilla['cod_inventario']); ?>" name="checkbox_<?php echo ($semilla['cod_inventario']); ?>" type="checkbox" <?php echo ($semilla['activo'] == 1 ? 'checked="checked"' : ''); ?> />
														<label for="checkbox_<?php echo ($semilla['cod_inventario']); ?>" class=""></label>
													</div>
												</td>
											</tr>
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

			<!-- Modal -->
			<div class="modal" id="modal_seleccionar_invernadero" tabindex="-1" role="dialog" aria-labelledby="modal_seleccionar" aria-hidden="true" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header" style="padding-bottom: 25px;">
							<!-- <h5 class="modal-title translate" style="margin-bottom: 10px;" id="modal_seleccionar" data-traducir_english="Select Greenhouse" data-traducir_spanish="Seleccionar invernadero">Copiar inventario</h5> -->
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">

							<div class="row">
								<div class="col-md-12" style="margin-bottom: 10px; padding-bottom: 10px;">

									<p>Select Greenhouse to add seeds</p>

								</div>
								<div class="col-md-12">
									<div class="form-group input-group-sm">
										<label for="cod_info_empresa2" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</label>
										<select class="show-menu-arrow cod_info_empresa2 requerido " title="Select" id="cod_info_empresa2" name="cod_info_empresa2">
										</select>
									</div>
									<input type="text" id="cantidad_modal" class="form-control" disabled name="cantidad_modal">
									<div id="mensaje_al_cambiar_cantidad"> </div>
									<input type="text" hidden id="nombre_de_la_semilla" name="nombre_de_la_semilla">
									<input type="text" hidden id="valorInicial">
									<input type="text" hidden id="codigo_inventario_semilla_para_modal" name="codigo_inventario_semilla_para_modal">
									<input type="text" hidden id="cantidadSumar" name="cantidadSumar">
									<input type="text" hidden id="cantidadRestar" name="cantidadRestar">
									<input type="text" hidden id="cantidad_ingresada" name="cantidad_ingresada">

								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>
							<button type="button" class="btn btn-primary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_asignar_cantdad_semillas">Guardar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
</body>
