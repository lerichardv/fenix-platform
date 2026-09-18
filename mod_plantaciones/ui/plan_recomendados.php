<?php
/*
* 	Registro de información de los formularios
* 	@author 		Jairo Bonilla
* 	@date 			2015-07-28
*/
session_start();
if (!isset($_SESSION['cod_usuario'])) {
	header('Location: index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_plantaciones.php");
/*INSTANCIAMIENTOS*/
$DB_PLANT 	= new db_plantaciones();

$cod_granjas_usuario = str_replace(['[', '"', ']'], '', $_SESSION['cod_info_empresa']);

//$RECOMENDADO 		= $DB_PLANT->bw_listado_zonas();
$RECOMENDADO 		= (array) $DB_PLANT->plan_listado_recomendado();

$cod_zona 	= $_POST['cod_zona'];
if (!isset($_POST['cod_zona'])) {
	$cod_zona = 0;
}
$ZONA 		= (array) $DB_PLANT->bw_obtener_info_zona($cod_zona);
$BLOQUES 	= (array) $DB_PLANT->bw_listado_bloques_zona($cod_zona);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>Recomendaciones</title>

</head>
<script type="text/javascript">
	$(document).ready(function() {



		jQuery.ajaxSetup({
			async: false
		});
		grl_overlay_loading('');
		codigo_zona = 0;
		$('.selectpicker').selectpicker({
			dropupAuto: 'true',
			container: 'body',
			size: '5',
			width: '100%',
			style: 'btn-sm btn-info'
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
									var no_results = $('<tr class="filterTable_no_results"><td colspan="4">No hay resultados.</td></tr>')
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
		conf_constructor_listado_granjas();
		//Máscaras

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

		$('#cod_info_empresa').change(function(event) {
			/* Act on the event */
			console.log($(this).val());
			plan_constructor_listado_usuarios_por_finca($(this).val());
		});

		<?php
		if (count($ZONA)) {
		?>
			$('#zona').val("<?php echo utf8_encode($ZONA[0]['zona']); ?>");
			$('#abreviatura').val("<?php echo utf8_encode($ZONA[0]['abreviatura']); ?>");
			$('#ubicacion').val("<?php echo utf8_encode($ZONA[0]['ubicacion']); ?>");
			$('#bloque_inicial').val("<?php echo utf8_encode($ZONA[0]['bloque_inicial']); ?>");
			$('#bloque_final').val("<?php echo utf8_encode($ZONA[0]['bloque_final']); ?>");
			$('#cantidad_acres').val("<?php echo utf8_encode($ZONA[0]['cantidad_acres']); ?>");
			$('#cod_info_empresa').selectpicker('val', "<?php echo utf8_encode($ZONA[0]['cod_info_empresa']); ?>");
			$('.selectpicker').selectpicker('refresh');
			$('#div_bloques').removeClass('hide');
		<?php
		}
		?>
		codigo_zona = <?php echo $cod_zona; ?>;
		$('#modal_loading').modal('hide');
		jQuery.ajaxSetup({
			async: true
		});
	});

	$('#btn_guardar_recomendado').click(function(event) {

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

		if (error == 0) {
			grl_overlay_loading('');
			$('#modal_loading').modal('hide');
			$('#modal_loading').on('hidden.bs.modal', function() {
				plan_guardar_recomendado();
			});
		} else {
			grl_mensaje('Debe llenar todos los campos marcados', 'favor verificar', 'warning');
		}
	});

	$('.checkbox_cambiar_estado_recomendado').change(function(event) {
		/* Act on the event */
		event.preventDefault();
		event.stopPropagation();
		plan_cambiar_estado_recomendado($(this).data('id'), ($(this).attr('checked') ? 0 : 1));


		/*
		Funcion que permite cambiar estado activo/des de una zona
		*/

		function plan_cambiar_estado_recomendado(cod_recomendado, valor) {

			var respuesta;
			console.log(cod_recomendado);
			console.log(valor);
			$.ajax({

					url: site_url + 'mod_plantaciones/funciones/plan_cambiar_estado_recomendado.php',

					type: 'POST',

					data: {

						x1: cod_recomendado,

						x2: valor

					},

				})

				.done(function(data) {

					jQuery.ajaxSetup({
						async: false
					});

					var info = data.split("|");

					var mensaje = info[1];

					info[0] == 1 ? tipo = 'danger' : tipo = 'success';

					$('#modal_loading').modal('hide');

					grl_mensaje('', info[1], tipo);

					//codigo_tipo_temporada = info[2];

					//grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

					// bw_vista_zona(0);

					jQuery.ajaxSetup({
						async: true
					});

				})

				.fail(function() {

					grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

				})

				.always(function() {});

			return false;

		}
	});



	$('#btn_add_bloque').click(function(event) {
		/* Act on the event */
		$('#modal_bloque').modal('show');
	});

	$('#btn_guardar_bloque').click(function(event) {
		/* Act on the event */
		var error = 0;
		$(".input.requerido_modal").map(function() {
			if (!$(this).val()) {
				error = 1;
				$(this).parent('div').addClass('has-error');
				return false;
			} else {
				$(this).parent('div').removeClass('has-error');
			}
		});
		$(".selectpicker.requerido_modal").map(function() {
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
		if (error == 0) {
			$('#modal_bloque').modal('hide');
			grl_overlay_loading('');
			$('#modal_loading').modal('hide');
			$('#modal_loading').on('hidden.bs.modal', function() {
				bw_guardar_bloque(codigo_zona);
			});
		} else {
			grl_mensaje('Debe llenar todos los campos marcados', 'favor verificar', 'warning');
		}
	});
</script>

<body>
	<div id="overlay_loading"></div>
	<div id="message_box"></div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="panel-header">
					<h1 class="translate" data-traducir_english="Recommended" data-traducir_spanish="Recomendados">Recomendados</h1>
				</div>
			</div>
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="cod_info_empresa" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</label>
							<select class="selectpicker show-menu-arrow requerido" title="Select" id="cod_info_empresa" name="cod_info_empresa">
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="usuario_finca_recomendado" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</label>
							<select class="selectpicker show-menu-arrow requerido" title="Select" id="usuario_finca_recomendado" name="usuario_finca_recomendado">
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12" align="center">
						<div class="form-group input-group-sm" id="div_motivo">
							<label class="translate text-start" data-traducir_english="Reason" data-traducir_spanish="Motivo" for="descripcion_empresa">Motivo</label>
							<textarea class="form-control" id="motivo_recomendado" maxlength="600" align="left" style="height:100px; width:100%; resize: none;"></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel-footer" align="right">
			<button class="btn btn-sm btn-primary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" type="button" id="btn_guardar_recomendado">Guardar</button>
		</div>
	</div>
	<div class="busqueda_contenedor">
		<div class="row" style="min-width:100px;">
			<div class="col-sm-12">
				<div class="panel panel-primary" style="overflow:auto;">
					<div class="panel-heading panel_cabecera">
						<h3 class="panel-title translate" data-traducir_english="Recommended Listing" data-traducir_spanish="Listado de recomendados">Listado de recomendados</h3>
						<div class="pull-right">
							<span class="clickable filter" data-toggle="tooltip" title="Realizar busqueda" data-container="body">
								<i class="fa fa-search"></i>
							</span>
						</div>
					</div>
					<div class="panel-body panel_cuerpo input-group-sm">
						<input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Busqueda" />
					</div>
					<div style="overflow-x:auto;">
						<table class="table table-condensed display table-striped" id="dev-table">
							<thead>
								<tr class="active info">
									<th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
									<th width="20%" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</th>
									<th width="20%" class="translate" data-traducir_english="Name" data-traducir_spanish="Nombre">Nombre</th>
									<th width="20%" class="translate" data-traducir_english="Reason" data-traducir_spanish="Razon">Razon</th>
									<!-- <th width="10%" class="translate" data-traducir_english="Block Key" data-traducir_spanish="Clave Bloque">Clave Bloque</th>
									<th width="10%" class="translate" data-traducir_english="Initial Block" data-traducir_spanish="Bloque Inicial">Bloque Inicial</th>
									<th width="10%" class="translate" data-traducir_english="Final Block" data-traducir_spanish="Bloque Final">Bloque Final</th>
									<th width="10%" class="translate" data-traducir_english="Acres" data-traducir_spanish="Acres">Acres</th> -->
									<th width="10%" class="translate" data-traducir_english="Active" data-traducir_spanish="Activo">Activo</th>
								</tr>
							</thead>
							<tbody id="cuerpo_tabla">
								<?php
								if (count($RECOMENDADO)) {
									$correlativo = 1;
									foreach ($RECOMENDADO as $zona) {
								?>
										<tr>
											<td><?php echo $correlativo; ?></td>
											<td><?php echo utf8_encode($zona['finca']); ?></td>
											<td><?php echo utf8_encode($zona['nombre_usuario_recomendado']); ?></td>
											<td><?php echo utf8_encode($zona['motivo']); ?></td>
											<td>
												<div class="material-switch pull-right">
													<input class="checkbox_cambiar_estado_recomendado" id="checkbox_<?php echo utf8_encode($zona['cod_recomendado']); ?>" data-id="<?php echo utf8_encode($zona['cod_recomendado']); ?>" name="checkbox_<?php echo utf8_encode($zona['cod_recomendado']); ?>" type="checkbox" <?php echo ($zona['activo'] == 1 ? 'checked="checked"' : ''); ?> />
													<label for="checkbox_<?php echo utf8_encode($zona['cod_recomendado']); ?>" class=""></label>
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
</body>