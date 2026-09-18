<?php
/*
* 	Registro de información de las semillas,
* 	@author 		Jairo Bonilla
* 	@date 			2015-07-28
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

$inicio = $_POST['inicio'];
if (!isset($_POST['inicio'])) {
	$inicio = 0;
}
$limite = $_POST['limite'];
if (!isset($_POST['limite'])) {
	$limite = 50;
}

$SEMILLAS = $DB_INV->inv_listado_semillas_por_granjas(
	$cod_granjas_usuario,
);


$cod_semilla = 0;
if (isset($_POST['cod_semilla'])) {
	$cod_semilla = $_POST['cod_semilla'];
}
$SEMILLA = [];
$DATOS_INVERNADEROS_ASOCIADOS = [];
if ($cod_semilla != 0) {
	$SEMILLA = $DB_INV->inv_obtener_info_inventario_semilla($cod_semilla);
	$DATOS_INVERNADEROS_ASOCIADOS = $DB_INV->inv_obtener_info_invernaderos_asociados($SEMILLA[0]["cod_empresa_asociados"], $SEMILLA[0]["cod_inventario"]);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>Inventario</title>
</head>

<script type="text/javascript">
	var codigo_inventario_semilla = 0;

	jQuery.ajaxSetup({
		async: false
	});
	grl_overlay_loading('');



	$(document).ready(function() {
		// $('#cantidad_semilla').attr('disabled', 'disabled');
		codigo_inventario_semilla = <?php echo $cod_semilla; ?>;

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
		$('.datetime').datetimepicker({
			minDate: new Date(), // Establece la fecha mínima como la fecha actual
			format: 'MM-DD-YYYY HH:mm:ss',
			icons: {
				time: "fas fa-clock",
				date: "fa fa-calendar",
				up: "fa fa-arrow-up",
				down: "fa fa-arrow-down",
				previous: 'fa fa-arrow-left',
				next: 'fa fa-arrow-right',
			}
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
		inv_constructor_listado_variedades_productos();
		// conf_constructor_listado_granjas();
		conf_constructor_listado_granjas_para_listados_multiples();

		inv_constructor_listado_unidades_medida();

		// NUEVO
		conf_constructor_listado_categorias();
		conf_constructor_listado_grupo_de_siembra();
		conf_constructor_listado_rasgo();
		conf_constructor_listado_familias_de_semillas();
		//inv_constructor_tipo_semilla();

		$('#dev-table').DataTable({
			"ordering": false,
			"dom": 'Bfrtip',
			"buttons": [{
					extend: 'pdfHtml5',
					orientation: 'landscape',
					pageSize: 'LEGAL',
					title: 'Seeds Inventory',
				},
				'print',
			]
		});

		//Máscaras
		//$('.monto').mask("#,##0.000", {reverse: false, maxlength: false});
		$('.monto').mask("9999999.999");
		$('.lote').mask("999999999999999");
		$('.acres').mask("9999999999");
		$('.codigoSemilla').mask("SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS");
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
		$('.letras10').mask('SSSSSSSSSS', {
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
		<?php

		if (count($SEMILLA)) {
			if (
				isset($SEMILLA[0]['paletizado'])
				&& $SEMILLA[0]['paletizado'] == 1
			) {
		?>
				// $('#flag_watercress').attr('checked', 'checked');
				$('#paletizado').attr('checked', 'checked');

			<?php
			} else {
			?>
				// $('#flag_watercress').removeAttr('checked');
				$('#paletizado').removeAttr('checked');
			<?php
			}

			if (
				isset($SEMILLA[0]['semilla_activa'])
				&& $SEMILLA[0]['semilla_activa'] == 1
			) {
			?>
				$('#semilla_activa').attr('checked', 'checked');
			<?php
			} else {
			?>
				$('#semilla_activa').removeAttr('checked');
			<?php
			}

			if (
				isset($SEMILLA[0]['germinacion_automatica'])
				&& $SEMILLA[0]['germinacion_automatica'] == 1
			) {
			?>
				// $('#flag_germinacion_automatica').attr('checked', 'checked');
			<?php
			} else {
			?>
				// $('#flag_germinacion_automatica').removeAttr('checked');
			<?php
			}
			?>


			$('#codigo_semilla').val("<?php echo utf8_encode($SEMILLA[0]['codigo_semilla']); ?>");

			$('#cod_categoria').val("<?php echo utf8_encode($SEMILLA[0]['cod_categoria']); ?>");
			$('#cod_grupo_siembra').val("<?php echo utf8_encode($SEMILLA[0]['cod_grupo_siembra']); ?>");
			$('#cod_rasgo').val("<?php echo utf8_encode($SEMILLA[0]['cod_rasgo']); ?>");
			$('#cod_familia').val("<?php echo utf8_encode($SEMILLA[0]['cod_familia']); ?>");
			// $('#cod_vendedores').val("<?php
											// echo utf8_encode($SEMILLA[0]['cod_vendedores']);
											?>");

			$('#plants_acre').val("<?php echo utf8_encode($SEMILLA[0]['plants_acre']); ?>");
			$('#red_zone').val("<?php echo utf8_encode($SEMILLA[0]['red_zone']); ?>");
			$('#over_seed').val("<?php echo utf8_encode($SEMILLA[0]['over_seed']); ?>");
			$('#semillas_por_plantaciones').val("<?php echo utf8_encode($SEMILLA[0]['semillas_por_plantaciones']); ?>");
			$('#notas').val("<?php echo utf8_encode($SEMILLA[0]['notas']); ?>");
			$('#og_supply').val("<?php echo utf8_encode($SEMILLA[0]['og_supply']); ?>");

			$('#nombre_semilla').val("<?php echo utf8_encode($SEMILLA[0]['nombre_semilla']); ?>");
			$('#abreviatura_semilla').val("<?php echo utf8_encode($SEMILLA[0]['abreviatura_semilla']); ?>");
			// $('#cantidad_semilla').val("<?php echo  $SEMILLA[0]['cantidad_semilla']; ?>");
			$('#cantidad_semilla').val("<?php echo  number_format($SEMILLA[0]['suma_cantidad_semillas_individual']); ?>");
			$('#cantidad_fisica_semilla').val("<?php echo ($SEMILLA[0]['cantidad_fisica_semilla']); ?>");
			$('#precio_unidad').val("<?php echo  $SEMILLA[0]['precio_unidad']; ?>");
			$('#numero_lote').val("<?php echo  $SEMILLA[0]['numero_lote']; ?>");
			$('#cod_info_empresa').selectpicker('val', [<?php echo ($SEMILLA[0]['cod_empresa_asociados']); ?>]);

			$('#cod_variedad').selectpicker('val', "<?php echo ($SEMILLA[0]['cod_variedad']); ?>");
			$('#cod_unidad_medida').selectpicker('val', "<?php echo ($SEMILLA[0]['cod_unidad_medida']); ?>");

			//$('#cod_tipo_semilla').val("<?php echo ($SEMILLA[0]['cod_tipo_semilla']); ?>");

			$('.selectpicker').selectpicker('refresh');
			$('#div_habilitar_sumar_restar').removeClass('hide');
			$('#btn_copiar_inventario').removeClass('hide');
			$('#cantidad_semilla').attr('disabled', 'disabled');
		<?php
		}
		?>
		datos_invernaderos_asociados = <?php echo json_encode($DATOS_INVERNADEROS_ASOCIADOS); ?>;

		$('#modal_loading').modal('hide');
		jQuery.ajaxSetup({
			async: true
		});
	});

	const opciones = {
		style: 'decimal', // Puedes usar 'currency' para formato de moneda
		maximumFractionDigits: 0, // Número máximo de decimales
	};
	var datos_invernaderos_asociados = {};
	var permitirGuarda = true;

	$('#cod_info_empresa').change(function(event) {
		/* Act on the event */
		if (codigo_inventario_semilla > 0) {
			let arrCodInfoEmpresa = $('#cod_info_empresa').val();

			if (Array.isArray(datos_invernaderos_asociados) && datos_invernaderos_asociados.length > 0 && Array.isArray(arrCodInfoEmpresa) && arrCodInfoEmpresa.length > 0) {

				datos_invernaderos_asociados.forEach((value, index) => {
					if (!arrCodInfoEmpresa.includes(String(value.cod_empresa))) {
						value.cantidad_semilla = parseInt(value.cantidad_semilla);
						if (value.cantidad_semilla > 0) {
							permitirGuarda = false;
							grl_mensaje('The GreenHouse ' + value.nombre_empresa + ' has ' + value.cantidad_semilla.toLocaleString(undefined, opciones) + ' associated seeds. Transfer them before unlinking from the current seed', '', 'warning');

						}
					}
				})
			}
			if (arrCodInfoEmpresa == null) {
				grl_mensaje('You must select at least one greenhouse', '', 'warning');

			}
		}
	});

	$('#btn_guardar_inventario').click(function(event) {
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
				$(this).parent('div').addClass('has-error');
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
				if (codigo_inventario_semilla > 0) {
					if (Array.isArray(datos_invernaderos_asociados) && datos_invernaderos_asociados.length > 0) {
						let arrCodInfoEmpresa = $('#cod_info_empresa').val();

						datos_invernaderos_asociados.forEach((value, index) => {
							if (!arrCodInfoEmpresa.includes(String(value.cod_empresa))) {
								if (parseInt(value.cantidad_semilla) > 0) {
									permitirGuarda = false;
									grl_mensaje('The GreenHouse ' + value.nombre_empresa + ' has ' + parseInt(value.cantidad_semilla).toLocaleString(undefined, opciones) + ' associated seeds. Transfer them before unlinking from the current seed', '', 'warning');

								}
							}
						})
					}
				}
				if (permitirGuarda) {
					inv_guardar_inventario_semilla(codigo_inventario_semilla);
				}
			});
		} else {
			grl_mensaje('You must fill in all the marked fields', '', 'warning');
			// if (error == 2)
			// 	grl_mensaje('Debe ingresar una cantidad mayor que 0', 'You must enter an amount greater than 0', 'warning');
			// if (error == 1)
			if (error == 3)
				grl_mensaje('You must enter a reason for change', '', 'warning');
		}
	});


	$('#cuerpo_tabla').on('change', '.checkbox', function(event) {
		event.preventDefault();
		event.stopPropagation();
		console.log($(this).data('id'))
		console.log($(this).attr('checked'))
		console.log(($(this).attr('checked') ? 1 : 0))
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

	$('#btn_copiar_inventario').click(function(event) {
		/* Act on the event */
		jQuery.ajaxSetup({
			async: false
		});
		conf_constructor_listado_granjas('cod_info_empresa2');

		$('#modal_copiar_inventario').modal('show');
		jQuery.ajaxSetup({
			async: true
		});
	});

	$('#btn_guardar_copiar_inventario').click(function(event) {
		/* Act on the event */
		jQuery.ajaxSetup({
			async: false
		});
		$('#modal_copiar_inventario').modal('hide');
		//$('#modal_copiar_inventario').on('hidden.bs.modal', function () {
		inv_copiar_inventario_semilla(codigo_inventario_semilla, $('#cod_info_empresa2').val());
		//});
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
					<h1 class="translate" data-traducir_english="Seed" data-traducir_spanish="Semilla">Semilla</h1>
				</div>
			</div>
			<div class="col-md-12">
				<!-- Fila #1 -->
				<div class="row">
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="nombre_semilla" class="translate" data-traducir_english="Name" data-traducir_spanish="Nombre">Nombre</label>
							<input type="text" class="form-control letras input requerido" id="nombre_semilla" name="nombre_semilla">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="abreviatura_semilla" class="translate" data-traducir_english="Abbreviation" data-traducir_spanish="Abreviatura">Abreviatura</label>
							<input type="text" class="form-control letras10 input" id="abreviatura_semilla" name="abreviatura_semilla">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="numero_lote" class="translate" data-traducir_english="Lot No" data-traducir_spanish="Nro. Lote">Nro. Lote</label>
							<input type="text" class="form-control lote input" id="numero_lote" name="precio_unidad">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="precio_unidad" class="translate" data-traducir_english="Price per Unit" data-traducir_spanish="Precio Unidad">Precio Unidad</label>
							<input type="text" class="form-control monto input" min="0" id="precio_unidad" name="precio_unidad">
						</div>
					</div>
				</div>
				<!-- Fila #2 -->

				<div class="row">
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="cantidad_semilla" class="translate" data-traducir_english="Stock" data-traducir_spanish="Cantidad">Cantidad</label>
							<input type="text" class="form-control monto input requerido" min="0" value="0" id="cantidad_semilla" name="cantidad_semilla">
						</div>
					</div>
					<div class="col-md-3"><!-- Nuevo -->
						<div class="form-group input-group-sm">
							<label for="cod_categoria" class="translate" data-traducir_english="Commodity" data-traducir_spanish="Mercancía">Commodity</label>
							<select class="selectpicker show-menu-arrow requerido" data-live-search="true" title="Select" id="cod_categoria" name="cod_categoria">
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="cod_variedad" class="translate" data-traducir_english="Variety" data-traducir_spanish="Variedad">Variedad</label>
							<select class="selectpicker show-menu-arrow requerido" data-live-search="true" title="Select" id="cod_variedad" name="cod_variedad">
							</select>
						</div>
					</div>
					<div class="col-md-3"><!-- Nuevo -->
						<div class="form-group input-group-sm">
							<label for="cod_grupo_siembra" class="translate" data-traducir_english="Sow group" data-traducir_spanish="Grupo de siembra">Grupo de cultivo</label>
							<select class="selectpicker show-menu-arrow requerido" data-live-search="true" title="Select" id="cod_grupo_siembra" name="cod_grupo_siembra">
							</select>
						</div>
					</div>

				</div>

				<!-- Fila #3 -->
				<div class="row">
					<div class="col-md-3"><!-- Nuevo -->
						<div class="form-group input-group-sm">
							<label for="cod_rasgo" class="translate" data-traducir_english="Trait" data-traducir_spanish="Rasgo">Rasgo</label>
							<select class="selectpicker show-menu-arrow requerido" data-live-search="true" title="Select" id="cod_rasgo" name="cod_rasgo">
							</select>
						</div>
					</div>
					<div class="col-md-3"><!-- Nuevo -->
						<div class="form-group input-group-sm">
							<label for="plants_acre" class="translate" data-traducir_english="Plants/Acre" data-traducir_spanish="Plantaciones/Acres">Plantaciones/Acres</label>
							<input type="text" class="form-control acres input requerido" id="plants_acre" name="plants_acre">
						</div>
					</div>
					<div class="col-md-3"><!-- Nuevo -->
						<div class="form-group input-group-sm">
							<label for="cod_familia" class="translate" data-traducir_english="Family" data-traducir_spanish="Familia">Familia</label>
							<select class="selectpicker show-menu-arrow requerido" data-live-search="true" title="Select" id="cod_familia" name="cod_familia">
							</select>
						</div>
					</div>
					<div class="col-md-3"><!-- Nuevo -->
						<div class="form-group input-group-sm">
							<label for="codigo_semilla" class="translate" data-traducir_english="Item #" data-traducir_spanish="Ítem #">Ítem #</label>
							<input type="text" maxlength="40" class="form-control input requerido" id="codigo_semilla" name="codigo_semilla">
						</div>
					</div>

				</div>
				<!-- Fila #4 -->
				<div class="row">
					<div class="col-md-3"><!-- Nuevo -->
						<div class="form-group input-group-sm">
							<label for="red_zone" class="translate" data-traducir_english="RedZone" data-traducir_spanish="RedZone">RedZone</label>
							<input type="text" class="form-control letras input requerido" id="red_zone" name="red_zone">
						</div>
					</div>
					<div class="col-md-3"><!-- Nuevo -->
						<div class="form-group input-group-sm">
							<label for="over_seed" class="translate" data-traducir_english="Default Over-Seed" data-traducir_spanish="Sembrado extra predeterminado">Sembrado extra predeterminado</label>
							<input type="text" class="form-control letras input requerido" id="over_seed" name="over_seed">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="cod_info_empresa" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</label>
							<select class="selectpicker show-menu-arrow requerido" multiple="multiple" data-actions-box="true" data-live-search="true" data-action="" title="Select" id="cod_info_empresa" name="cod_info_empresa">
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="cod_unidad_medida" class="translate" data-traducir_english="Unit of Measure" data-traducir_spanish="Unidad de Medida">Unidad de Medida</label>
							<select class="selectpicker show-menu-arrow requerido" data-live-search="true" title="Select" id="cod_unidad_medida" name="cod_unidad_medida">
							</select>
						</div>
					</div>
				</div>


				<!-- Fila #5 -->
				<div class="row">
					<div class="col-md-3"><!-- Nuevo -->
						<div class="form-group input-group-sm">
							<label for="semillas_por_plantaciones" class="translate" data-traducir_english="Seeds per plantings" data-traducir_spanish="Semillas por plantaciones">Semillas por plantaciones</label>
							<input type="text" value="0" class="form-control monto input requerido" id="semillas_por_plantaciones" name="semillas_por_plantaciones">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="cantidad_fisica_semilla" class="translate" data-traducir_english="Physical Amount" data-traducir_spanish="Cantidad Física">Cantidad Física</label>
							<input type="text" value="0" class="form-control monto input" min="0" id="cantidad_fisica_semilla" name="cantidad_fisica_semilla">
						</div>
					</div>
					<div class="col-md-1" style="padding-bottom: 10px;">
						<div class="form-group input-group-sm">
							<label for="paletizado" class="translate" data-traducir_english="Pelleted?" data-traducir_spanish="¿Paletizado?">¿Paletizado?</label><br>
							<div class="material-switch pull-left">
								<input class="checkbox_paletizado" id="paletizado" name="paletizado" type="checkbox" />
								<label for="paletizado" class=""></label>
							</div>
						</div>
					</div>
					<div class="col-md-1" style="padding-bottom: 10px;">
						<div class="form-group input-group-sm">
							<label for="semilla_activa" class="translate" data-traducir_english="Actived?" data-traducir_spanish="¿Activa?">¿Activa?</label><br>
							<div class="material-switch pull-left">
								<input class="checkbox_semilla_activa" id="semilla_activa" name="semilla_activa" type="checkbox" />
								<label for="semilla_activa" class=""></label>
							</div>
						</div>
					</div>
					<!-- <div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="cod_tipo_semilla" class="translate" data-traducir_english="Seed type" data-traducir_spanish="Tipo de semilla">Tipo de semilla</label>
							<select class="selectpicker show-menu-arrow requerido" data-live-search="true" title="Select" id="cod_tipo_semilla" name="cod_tipo_semilla">
							</select>
						</div>
					</div> -->
				</div>

				<!-- Fila #6 (textarea) -->
				<div class="row">
					<div class="col-md-12"><!-- Nuevo -->
						<div class="form-group input-group-sm">
							<label for="notas" class="translate" data-traducir_english="NOTES" data-traducir_spanish="NOTAS">NOTAS</label>
							<textarea class="form-control input requerido-revisar" required="" id="notas" maxlength="600" align="left" style="height:100px; width:100%; resize: none;"></textarea>
						</div>
					</div>
				</div>
				<!-- Fila #7 (textarea) -->
				<div class="row">
					<div class="col-md-12"><!-- Nuevo -->
						<div class="form-group input-group-sm">
							<label for="og_supply" class="translate" data-traducir_english="O.G. SUPPLY" data-traducir_spanish="Suministro de O.G.">Suministro de O.G.</label>
							<textarea class="form-control input requerido-revisar" required="" id="og_supply" maxlength="600" align="left" style="height:100px; width:100%; resize: none;"></textarea>
						</div>
					</div>
				</div>

				<hr>
				<div id="seccion_oculta" hidden>

					<div class="row hide" id="div_habilitar_sumar_restar" style="margin-bottom: 20px;">
						<div class="col-md-4 col-md-offset-4">
							<label class="translate" data-traducir_spanish="Habilitar Sumar/Restar" data-traducir_english="Enable Add/Substract">Habilitar Sumar/Restar</label>
							<div class="material-switch pull-right">
								<input class="checkbox_habilitar_sumar_restar" id="checkbox_habilitar_sumar_restar" name="checkbox_habilitar_sumar_restar" type="checkbox" />
								<label for="checkbox_habilitar_sumar_restar" class=""></label>
							</div>
						</div>
					</div>
					<div class="row hide" id="div_sumar_restar">
						<div class="form-group input-group-sm col-md-3">
							<label for="fecha_sumar_restar" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</label>
							<div class='input-group input-group-sm fecha-planeada datetime' id='datetimepicker1'>
								<span class="input-group-addon">
									<span class="fa fa-calendar"></span>
								</span>
								<input type='text' class="form-control input requerido_harvesting_worksheet" id="fecha_sumar_restar" />
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group input-group-sm">
								<label for="cantidad_sumar" class="translate" data-traducir_english="Add Stock" data-traducir_spanish="Sumar Inventario">Sumar Inventario</label>
								<input type="text" class="form-control monto input" value="0" id="cantidad_sumar" name="cantidad_sumar">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group input-group-sm">
								<label for="cantidad_restar" class="translate" data-traducir_english="Substract Stock" data-traducir_spanish="Restar Inventario">Restar Inventario</label>
								<input type="text" class="form-control monto input" value="0" id="cantidad_restar" name="cantidad_restar">
							</div>
						</div>
						<div class="col-md-3">
							<label for="razon_sumar_restar" class="translate" data-traducir_english="Reason for Change" data-traducir_spanish="Razón de Cambio">Razón de Cambio</label>
							<textarea maxlength="3000" class="input" id="razon_sumar_restar" align="left" style="height:100px; width:100%; resize: none;"></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel-footer" align="right">
			<button class="btn btn-sm btn-success translate hide" data-traducir_english="Copy" data-traducir_spanish="Copiar" type="button" id="btn_copiar_inventario">Copiar</button>
			<button class="btn btn-sm btn-primary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" type="button" id="btn_guardar_inventario">Guardar</button>
		</div>
	</div>

	<div class="busqueda_contenedor " style="margin-left: 10px; margin-right: 10px;">
		<div class="row" style="min-width:100px;">
			<div class="col-sm-12">
				<div class="panel panel-primary" id="panel_itemschecklist">
					<div class="panel-heading btncollapsepaso panel_cabecera" id="a_itemschecklist" role="button" data-toggle="collapse" href="#collapseitemschecklist" data-target="#collapseitemschecklist" aria-expanded="true" data-parent="#panel_itemschecklist" aria-controls="collapseitemschecklist">
						<h3 class="panel-title translate" data-traducir_english="Seed Inventory" data-traducir_spanish="Inventario de Semillas">Inventario de Semillas</h3>
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
									<th width="30%" class="translate" data-traducir_english="Name" data-traducir_spanish="Nombre">Nombre</th>
									<th width="10%" class="translate" data-traducir_english="Item #" data-traducir_spanish="Ítem #">Ítem #</th>
									<th width="20%" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernaderos">Invernaderos</th>
									<th width="6%" class="translate" data-traducir_english="Abbreviation" data-traducir_spanish="Abreviatura">Abreviatura</th>
									<th width="6%" class="translate" data-traducir_english="Lot No" data-traducir_spanish="Nro. Lote">Nro. Lote</th>
									<th width="15%" class="translate" data-traducir_english="Stock" data-traducir_spanish="Cantidad">Cantidad</th>
									<th width="2%"></th>
								</tr>
							</thead>
							<tbody id="cuerpo_tabla">
								<?php
								if (count($SEMILLAS)) {
									$correlativo = 1;
									foreach ($SEMILLAS as $semilla) {
								?>
										<tr class="fila_seleccionable">
											<td onclick="inv_vista_inventario_semilla(<?php echo utf8_encode($semilla['cod_inventario']); ?>)"><?php echo $correlativo; ?></td>
											<td onclick="inv_vista_inventario_semilla(<?php echo utf8_encode($semilla['cod_inventario']); ?>)"><?php echo utf8_encode($semilla['nombre_semilla']); ?></td>
											<td onclick="inv_vista_inventario_semilla(<?php echo utf8_encode($semilla['cod_inventario']); ?>)"><?php echo utf8_encode($semilla['codigo_semilla']); ?></td>
											<td onclick="inv_vista_inventario_semilla(<?php echo utf8_encode($semilla['cod_inventario']); ?>)"><?php echo utf8_encode($semilla['nombres_empresas_vinculadas']); ?></td>
											<td onclick="inv_vista_inventario_semilla(<?php echo utf8_encode($semilla['cod_inventario']); ?>)"><?php echo utf8_encode($semilla['abreviatura_semilla']); ?></td>
											<td onclick="inv_vista_inventario_semilla(<?php echo utf8_encode($semilla['cod_inventario']); ?>)"><?php echo utf8_encode($semilla['numero_lote']); ?></td>
											<td onclick="inv_vista_inventario_semilla(<?php echo utf8_encode($semilla['cod_inventario']); ?>)"><?php echo number_format($semilla['suma_cantidad_semillas_individual']); ?></td>
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
	</div>
	<!-- Modal -->
	<div class="modal" id="modal_copiar_inventario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title translate" id="exampleModalLabel" data-traducir_english="Copy Inventory" data-traducir_spanish="Copiar inventario">Copiar inventario</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="col-md-12">
						<div class="form-group input-group-sm">
							<label for="cod_info_empresa2" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</label>
							<select class="selectpicker show-menu-arrow" title="Select" multiple="multiple" id="cod_info_empresa2" name="cod_info_empresa2">
							</select>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_guardar_copiar_inventario">Guardar</button>
				</div>
			</div>
		</div>
	</div>
</body>

<script>

</script>