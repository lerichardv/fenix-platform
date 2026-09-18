<?php
/*
* 	Listado general de todas las semillas registradas
* 	@author 		Edwin Olivera
* 	@date 			2023-09-12
*/
ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
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


$cod_info_empresa = "";
$cod_estados = "";
$cod_semillas = "";

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

if (isset($_POST['cod_info_empresa']) && $_POST['cod_info_empresa'] != '' && $_POST['cod_info_empresa'] != '-b') {
	$cod_info_empresa = $_POST['cod_info_empresa'];
}
if (isset($_POST['cod_estados']) && $_POST['cod_estados'] != '') {
	$cod_estados = $_POST['cod_estados'];
	$cod_estados = implode(',', $cod_estados);
}
if (isset($_POST['cod_semillas']) && $_POST['cod_semillas'] != '') {
	$cod_semillas = $_POST['cod_semillas'];
	$cod_semillas = implode(',', $cod_semillas);
}



$PLANTACIONES = [];

$PLANTACIONES = $DB_INV->inv_listado_plantaciones(
	$fechaInicial,
	$fechaFinal,
	$cod_info_empresa,
	$cod_estados,
	$cod_semillas
);
if (!is_array($PLANTACIONES)) {
	$PLANTACIONES = [];
}
// var_dump($PLANTACIONES);

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
	<title>Inventario</title>
	<!-- Los estilos se colocan aquí para forzar su aplicación
		unicamente en esta página -->
	<style>
		.btnCantidadSemillas,
		.btnEdad
     {
			width: 100% !important;
			text-align: right;

		}

		.input_cantidad_semillas {
			text-align: right;
		}
	</style>
</head>

<script type="text/javascript">
	jQuery.ajaxSetup({
		async: false
	});
	grl_overlay_loading('');



	$(document).ready(function() {
		codigo_inventario_semilla = 0;

		$(".btn-group.bootstrap-select.show-tick.show-menu-arrow.requerido").remove();
		$(window).scroll(function() {
			// Obtener la posición actual de desplazamiento vertical
			var scrollPos = $(window).scrollTop();

			// Verificar si el usuario ha hecho scroll hacia abajo (puedes ajustar el valor según tus necesidades)
			if (scrollPos > 1) {
				$(".requerido").removeClass("open");
			}
		});
		$('.selectpicker').selectpicker({
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


		// Obtiene la fecha actual
		var fechaTreitaDiasPrevios = new Date();
		// Resta 30 días a la fecha actual
		fechaTreitaDiasPrevios.setDate(fechaTreitaDiasPrevios.getDate() - 30);
		$('#div_fecha_inicial').datetimepicker({
			defaultDate: fechaTreitaDiasPrevios,
			format: 'YYYY-MM-DD',
			// maxDate: new Date(),
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
		conf_constructor_listado_granjas_sin_seleccionar();
		conf_constructor_listado_estados_de_plantacion();
		inv_constructor_listado_semillas_activas('cod_semillas')

		$('#dev-table').DataTable({

			"pageLength": 100,
			"order": [
				[8, 'desc']
			],
			"dom": 'Bfrtip',
			"buttons": []
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
		<?php
		if (isset($_POST['fecha_inicial']) && isset($_POST['fecha_final'])) {
		?>
			$('#fecha_inicial').val('<?php echo  $_POST['fecha_inicial']; ?>');
			$('#fecha_final').val('<?php echo  $_POST['fecha_final']; ?>');
		<?php
		}
		?>

		<?php
		if (isset($_POST['cod_estados']) && $_POST['cod_estados'] != '') {
		?>
			$('#cod_estados').val([<?php echo  implode(",", $_POST['cod_estados']); ?>]);
			$('.selectpicker').selectpicker('refresh');

		<?php
		}
		?>

		<?php
		if (isset($_POST['cod_semillas']) && $_POST['cod_semillas'] != '') {
		?>
			$('#cod_semillas').val([<?php echo  implode(',', $_POST['cod_semillas']); ?>]);
			$('.selectpicker_semillas').selectpicker('refresh');

		<?php
		}
		?>

		<?php
		if (isset($_POST['cod_info_empresa']) && $_POST['cod_info_empresa'] != '') {
		?>
			$('#cod_info_empresa').val('<?php echo  $_POST['cod_info_empresa']; ?>');
			$('.selectpicker').selectpicker('refresh');
		<?php
		}
		?>
		codigo_inventario_semilla = <?php echo $cod_semilla; ?>;
		$('#modal_loading').modal('hide');
		jQuery.ajaxSetup({
			async: true
		});
	});

	$('#btn_filtrar').click(function(event) {
		$.ajax({
				url: 'mod_inventario/ui/inv_listado_plantaciones.php',
				type: 'POST',
				dataType: 'html',
				data: {
					fecha_inicial: $('#fecha_inicial').val(),
					fecha_final: $('#fecha_final').val(),
					cod_info_empresa: $('#cod_info_empresa').val(),
					cod_estados: $('#cod_estados').val(),
					cod_semillas: $('#cod_semillas').val(),
				},
			})
			.done(function(data) {
				$('#div_cuerpo_menu').empty();
				$('#div_cuerpo_menu').html(data);
			})
			.fail(function() {
				console.log("error al filtrar por fecha la busqueda de semillas");
			});

	});
	$('#cuerpo_tabla').on('change', '.checkbox', function(event) {
		/* Act on the event */
		event.preventDefault();
		event.stopPropagation();

		inv_cambiar_estado_semilla($(this).data('id'), ($(this).attr('checked') ? 0 : 1));
	});


	$('#cantidad_semilla').on('input', function() {
		var valor = $(this).val();

		// Verificar si el campo está vacío
		if (valor === '' || parseFloat(valor) <= 10) {
			$('#semillas_por_plantaciones').val('Stock is empty. Add amount to enable');
			$('#cantidad_fisica_semilla').val('Stock is empty. Add amount to enable');
			$('#semillas_por_plantaciones').prop('disabled', true);
			$('#cantidad_fisica_semilla').prop('disabled', true);

		} else {
			$('#semillas_por_plantaciones').prop('disabled', false);
			$('#cantidad_fisica_semilla').prop('disabled', false);
			$('#semillas_por_plantaciones').val(0);
			$('#cantidad_fisica_semilla').val(0);
		}
	});

	var algo = "miInput";
	var boton = "habilitarInput";
	// Cuando se hace clic en el botón "Habilitar Input"
	$("#" + boton).click(function() {
		// Habilitar el input
		$("#" + algo).prop("disabled", false);
	});

	// Validar que solo se ingresen números y puntos en el input
	$("#" + algo).on("input", function() {
		var valorInput = $(this).val();
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

		$(this).val(nuevoValor);
	});
	var valorCantidadInicialDeSemillas = 0;

	function activarEdicionDeCantidad(idInput, idBoton) {
		let btnActivarInput = $('#btn_semilla_' + idBoton);
		let miInput = $('#input_cantidad_semilla_' + idInput);
		miInput.toggle(); // Alterna la visibilidad del input
		miInput.prop('disabled', false);
		btnActivarInput.toggle(); // Alterna la visibilidad del input
		miInput.focus();
		valorCantidadInicialDeSemillas = miInput.val();

		valorCantidadInicialDeSemillas = miInput.val();
		btnActivarInput.text(valorCantidadInicialDeSemillas); // Agregamos el valor del input+
	}

	//Este método se ejecuta cada vez que se sale de un input de cantidad de semillas
	function salirDelInputDeCantidad(idInput, idBoton) {

		let btnActivarInput = $('#btn_semilla_' + idBoton);
		let miInput = $('#input_cantidad_semilla_' + idInput);
		if (btnActivarInput == undefined || miInput == undefined) return;

		guardarCantidadNuevaDeCantidadDeSemillas(parseFloat(valorCantidadInicialDeSemillas), parseFloat(miInput.val()), idBoton);
		valorCantidadInicialDeSemillas = 0

		// Deshabilitar el input cuando se pierde el foco
		miInput.toggle(0);
		miInput.prop('disabled', true);
		btnActivarInput.toggle(200); // Alterna la visibilidad del input
	}

	function detectarDatosEntradaDeTeclados(event, idInput, idBoton) {

		let btnActivarInput = $('#btn_semilla_' + idBoton);
		let miInput = $('#input_cantidad_semilla_' + idInput);
		var valorInput = miInput.val();
		var nuevoValor = "";
		var nuevoValorBoton = "";
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
		btnActivarInput.text(nuevoValor);
	}

	function detectarTeclasDeSalida(event, idInput, idBoton) {
		let btnActivarInput = $('#btn_semilla_' + idBoton);
		let miInput = $('#input_cantidad_semilla_' + idInput);

		var valorInput = miInput.val();

		if (event.keyCode === 13) { //Tecla "Enter"/"Entrar"
			// alert("Zelda");

			miInput.prop('disabled', true);
		}


		if (event.keyCode === 27) { // Tecla "ESC"
			// Aquí puedes ejecutar el código que deseas cuando se presiona "ESC"
			btnActivarInput.text(valorCantidadInicialDeSemillas);
			miInput.val(valorCantidadInicialDeSemillas);
			miInput.prop('disabled', true);

		}
	}

	function guardarCantidadNuevaDeCantidadDeSemillas(valorInicial, valorNuevo, codigo_inventario_semilla) {

		if (valorInicial === valorNuevo) return;

		var cantidadSumar = 0;
		var cantidadRestar = 0;
		var razon = "N/A";
		if (valorInicial > valorNuevo) {
			// Sustracción de cantidades
			// 50 (valorInicial) - 45 (valorNuevo) = 5
			cantidadRestar = valorInicial - valorNuevo;
			cantidadSumar = 0
		};
		if (valorInicial < valorNuevo) {
			// Adición de cantidades
			// 50 (valorInicial) - 65 (valorNuevo) = 15
			cantidadSumar = valorNuevo - valorInicial;
			cantidadRestar = 0
		};

		var fechaActual = moment().format('MM-DD-YYYY HH:mm:ss');
		inv_guardar_los_cambios_de_cantidades_de_semillas(codigo_inventario_semilla, cantidadSumar, cantidadRestar, razon, fechaActual)
	}
</script>

<body>
	<div id="overlay_loading"></div>
	<div id="message_box"></div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="panel-header">
					<h1 class="translate" data-traducir_english="Planting List" data-traducir_spanish="Listado de plantaciones">Listado de plantaciones</h1>
				</div>
			</div>
			<div class="col-md-12">
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
						<label for="fecha_sumar_restar" class="translate" data-traducir_english="Final date" data-traducir_spanish="FEcha final">FEcha final</label>
						<div class='input-group input-group-sm fecha-planeada' id='div_fecha_final'>
							<span class="input-group-addon">
								<span class="fa fa-calendar"></span>
							</span>
							<input type='text' class="form-control" id="fecha_final" />
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group input-group-sm">
							<label for="cod_info_empresa" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</label>
							<select class="selectpicker show-menu-arrow requerido_envio cod_info_empresa" data-live-search="true" title="Select" id="cod_info_empresa" name="cod_info_empresa">
							</select>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group input-group-sm">
							<label for="cod_estados" class="translate" data-traducir_english="State" data-traducir_spanish="Estado">Estado</label>
							<select multiple="multiple" data-actions-box="true" class="selectpicker show-menu-arrow requerido" title="Select" id="cod_estados" name="cod_estados">
							</select>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group input-group-sm">
							<label for="cod_semillas" class="translate" data-traducir_english="Seed" data-traducir_spanish="Semilla">Semilla</label>
							<select multiple="multiple" data-actions-box="true" class="selectpicker selectpicker_semillas show-menu-arrow requerido" title="Select" id="cod_semillas" name="cod_semillas">
							</select>
						</div>
					</div>

					<div class="col-md-2 " style="margin-top: 25px;">
						<button type="button" id="btn_filtrar" class="btn btn-sm btn-primary translate" data-traducir_english="Filter" data-traducir_spanish="Filtrar">Filtrar</button>
					</div>

				</div>

				<hr>
			</div>
		</div>
	</div>
	<div class="busqueda_contenedor " style="margin-left: 10px; margin-right: 10px;">
		<div class="row" style="min-width:100px;">
			<div class="col-sm-12">
				<div class="panel panel-primary" id="panel_itemschecklist">
					<div class="panel-heading btncollapsepaso panel_cabecera" id="a_itemschecklist" role="button" data-toggle="collapse" href="#collapseitemschecklist" data-target="#collapseitemschecklist" aria-expanded="true" data-parent="#panel_itemschecklist" aria-controls="collapseitemschecklist">
						<h3 class="panel-title translate" data-traducir_english="Planting List" data-traducir_spanish="Listado de Plantaciones">Listado de Plantaciones</h3>
						<div class="pull-right">
							<span class="clickable filter" data-toggle="tooltip" title="Search" data-container="body">
								<i class="fa fa-search"></i>
							</span>
						</div>
					</div>
					<div class="panel-collapse collapse" id="collapseitemschecklist" role="tabpanel" aria-labelledby="collapseitemschecklist">
						<div class="panel-body panel_cuerpo">
							<input type="text" class="form-control" id="tabla_itemschecklist-filter" data-action="filter" data-filters="#tabla_itemschecklist" placeholder="Busqueda" />
						</div>
					</div>
					<div class="responsive_table_container">
						<table class="table display row-border table-responsive table-hover table-condensed" id="tabla_itemschecklist">
							<thead>
								<tr class="active info">
									<th width="10%" class="translate" data-traducir_english="Order number" data-traducir_spanish="Número de orden">Número de orden</th>
									<th width="10%" class="translate" data-traducir_english="Line item" data-traducir_spanish="Ítem">Ítem</th>
									<th width="25%" class="translate" data-traducir_english="Seed" data-traducir_spanish="Semilla">Semilla</th>
									<th width="10%" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</th>
									<th width="5%" class="translate" data-traducir_english="State" data-traducir_spanish="Estado">Estado</th>
									<th width="10%" class="translate" data-traducir_english="Quantity" data-traducir_spanish="Cantidad">Cantidad</th>
									<th width="10%" class="translate" data-traducir_english="Overseed " data-traducir_spanish="Overseed ">Overseed </th>
									<th width="10%" class="translate" data-traducir_english="Total" data-traducir_spanish="Total">Total</th>
									<th width="10%" class="translate" data-traducir_english="Expected Sow" data-traducir_spanish="Fecha de siembra">Fecha de siembra</th>
								</tr>
							</thead>
							<tbody id="cuerpo_tabla">
								<?php
								if (count($PLANTACIONES)) {
									$correlativo = 1;
									foreach ($PLANTACIONES as $semilla) {
										$idSemilla = $semilla["cod_inventario"];
										$semilla['total'] = (int)$semilla['total']; // Forzamos a que todo los valores se vuelan INT para su posterior conversión a números con división de miles
										$semilla['cantidad'] = (int)$semilla['cantidad']; // Forzamos a que todo los valores se vuelan INT para su posterior conversión a números con división de miles
								?>
										<tr class="fila_seleccionable" onclick="inv_vista_nueva_plantacion('','<?php echo utf8_encode($semilla['numero_orden']); ?>')">
											<!-- <td><?php echo $correlativo; ?></td> -->
											<td><?php echo $semilla['numero_orden']; ?></td>
											<td><?php echo $semilla['item']; ?></td>
											<td><?php echo utf8_decode($semilla['nombre_semilla']); ?></td>
											<td><?php echo $semilla['nombre_empresa']; ?></td>
											<td><?php echo $semilla['abreviatura']; ?></td>
											<td><?php echo number_format($semilla['cantidad'], 0); ?></td>
											<td><?php echo  (int)$semilla['overseed'] . "%"; ?></td>
											<td><?php echo number_format($semilla['total'], 0); ?></td>
											<td><?php echo $semilla['fecha_inicial']; ?></td>

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
	</div>
</body>
