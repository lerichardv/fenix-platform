<?php
/*
* 	Administración de todas las actividades miscelaneas
* 	@author 		Edwin Olivera
* 	@date 			2024-09-13
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

$ACTIVIDADES = $DB_FARM->farm_listado_actividades();



$codigo_actividad_miscelanea = $_POST['codigo_actividad_miscelanea'];
if (!isset($_POST['codigo_actividad_miscelanea'])) {
	$codigo_actividad_miscelanea = 0;
}
$ACTIVIDAD_MISCELANEA = $DB_FARM->farm_obtener_una_actividad_miscelanea_especifica($codigo_actividad_miscelanea);
// var_dump($ACTIVIDAD_MISCELANEA);
if (count($ACTIVIDAD_MISCELANEA)) {
	// $codigo_actividad_miscelanea = $ACTIVIDAD_MISCELANEA[0]['cod_tipo_pack'];
	// $ACTIVIDAD_MISCELANEA = $DB_FARM->farm_obtener_datos_un_tipo_paquete_especifico($ACTIVIDAD_MISCELANEA[0]["cod_tipo_pack"], $ACTIVIDAD_MISCELANEA[0]["cod_farm"]);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title><?php echo $tituloPagina; ?></title>
</head>

<script type="text/javascript">
	jQuery.ajaxSetup({
		async: false
	});
	grl_overlay_loading('');

	$(document).ready(function() {
		codigo_actividad_miscelanea = 0;
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
		//Máscaras
		$('.monto').mask("#,##0.000", {
			reverse: true,

			maxlength: false
		});
		$('.letras').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {
			translation: {
				'S': {
					pattern: /[A-Za-z-¡!¿?,.() 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/,
					optional: false
				}
			}
		});
		$('#dev-table').DataTable({
			lengthChange: false,
			searching: false,
			"pageLength": 10000,
			// "order": [
			// 	[8, 'desc']
			// ],
			"dom": 'Bfrtip',
			"buttons": []
		});
		//Constructores
		farm_constructor_listado_granjas();
		farm_constructor_listado_localizaciones_en_granja();


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
									Validación input
		----------------------------------------------------------------------------------*/
		$('.input.requerido').keyup(function(event) {
			if ($(this).val().trim() != "") {
				$(this).parent('div').removeClass('has-error');
			} else {
				$(this).parent('div').addClass('has-error');
			}
		});

		<?php
		if (count($ACTIVIDAD_MISCELANEA)) {
		?>
			$('#nombre_tipo_paquete').val("<?php echo ($ACTIVIDAD_MISCELANEA[0]['tipo_pack']); ?>");
			$('#codigo_identificacion_actividad').val("<?php echo ($ACTIVIDAD_MISCELANEA[0]['codigo']); ?>");
			$('#nombre_actividad').val("<?php echo ($ACTIVIDAD_MISCELANEA[0]['activity']); ?>");
			$('#precio_pieza').val("<?php echo  $ACTIVIDAD_MISCELANEA[0]['piece_rate']; ?>");
			$('#cod_granja').val("<?php echo  $ACTIVIDAD_MISCELANEA[0]['cod_farms']; ?>");
			farm_constructor_listado_localizaciones_en_granja();

			$('#cod_localizacion').selectpicker('val', [<?php echo (($ACTIVIDAD_MISCELANEA[0]['cod_location'])); ?>]);
			$('.selectpicker').selectpicker('refresh');

			// Verificacion sobre el activador
			<?php
			if (
				isset($ACTIVIDAD_MISCELANEA[0]['activo'])
				&& $ACTIVIDAD_MISCELANEA[0]['activo'] == 1
			) {
			?>
				$('#activo').attr('checked', 'checked');
			<?php
			} else {
			?>
				$('#activo').removeAttr('checked');

			<?php
			}
			?>
		<?php
		}
		?>

		$('#cod_granja').change(function(event) {
			farm_constructor_listado_localizaciones_en_granja();

		});
		codigo_actividad_miscelanea = <?php echo $codigo_actividad_miscelanea; ?>;
		$('#modal_loading').modal('hide');
		jQuery.ajaxSetup({
			async: true
		});
	});


	$('#btn_guardar').click(function(event) {
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
				farm_guardar_actividad_miscelaneas(codigo_actividad_miscelanea);
			});
		} else {
			grl_mensaje('You must fill out all marked fields', 'Please check', 'warning');
		}
	});

	$('#btn_recargar').click(function(event) {
		/* Act on the event */
		farm_vista_actividades_miscelaneas();
	});
	$('.checkbox').change(function(event) {
		/* Act on the event */
		event.preventDefault();
		event.stopPropagation();
		farm_cambiar_estado_actividad_miscelanea($(this).data('id'), ($(this).attr('checked') ? 0 : 1));
	});
</script>

<body>
	<div id="overlay_loading"></div>
	<div id="message_box"></div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="panel-header">
					<h1 class="translate" data-traducir_english="Activities for Miscellaneous" data-traducir_spanish="Actividades para miscelaneos">Activities for Miscellaneous</h1>
				</div>
			</div>
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-2">
						<div class="form-group input-group-sm">
							<label for="cod_granja" class="translate" data-traducir_english="Farm" data-traducir_spanish="Granja">Farm</label>
							<select class="selectpicker show-menu-arrow requerido" <?php echo count($ACTIVIDAD_MISCELANEA) ? 'disabled' :  '' ?> data-live-search="true" title="Select" id="cod_granja" name="cod_granja">
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group input-group-sm">
							<label for="cod_localizacion" class="translate" data-traducir_english="Location" data-traducir_spanish="Localización">Localización</label>
							<select class="selectpicker show-menu-arrow requerido" <?php echo count($ACTIVIDAD_MISCELANEA) ? '' :  'multiple="multiple" data-actions-box="true"' ?> data-live-search="true" title="Select" id="cod_localizacion" name="cod_localizacion">
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group input-group-sm" id="div_nombre_capacitacion">
							<label for="codigo_identificacion_actividad" class="translate" data-traducir_english="Code" data-traducir_spanish="Código">Code</label>
							<input type="text" class="form-control input requerido " id="codigo_identificacion_actividad" name="codigo_identificacion_actividad" autocomplete="off">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm" id="div_nombre_capacitacion">
							<label for="nombre_actividad" class="translate" data-traducir_english="Activity" data-traducir_spanish="Actividad">Activity</label>
							<input type="text" class="form-control input requerido " id="nombre_actividad" name="nombre_actividad" autocomplete="off">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group input-group-sm" id="div_nombre_capacitacion">
							<label for="precio_pieza" class="translate" data-traducir_english="Piece Rate" data-traducir_spanish="Precio por pieza">Piece Rate</label>
							<input type="number" min="0.01" step="0.01" max="100" value="0.01" class="form-control input requerido " id="precio_pieza" name="precio_pieza" autocomplete="off">
						</div>
					</div>

					<!-- <div <?php echo count($ACTIVIDAD_MISCELANEA) ? "hidden" : "" ?> class="col-md-2">
						<div class="form-group input-group-sm">
							<label for="activo" class="translate" data-traducir_english="Actived?" data-traducir_spanish="¿Activa?">Actived?</label><br>
							<div class="material-switch pull-left">
								<input class="checkbox_componente_activo" id="activo" name="activo" type="checkbox" />
								<label for="activo" class=""></label>
							</div>
						</div>
					</div> -->
				</div>
			</div>
		</div>
		<div class="panel-footer" align="right">
		<button class="btn btn-sm btn-success translate" data-traducir_english="Refresh" data-traducir_spanish="Recargar" type="button" id="btn_recargar">Refresh</button>
		<button class="btn btn-sm btn-primary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" type="button" id="btn_guardar">Save</button>
		</div>
	</div>
	<div class="busqueda_contenedor">
		<div class="row" style="min-width:100px;">
			<div class="col-sm-12">
				<div class="panel panel-primary" style="overflow:auto;">
					<div class="panel-heading panel_cabecera">
						<h3 class="panel-title translate" data-traducir_english="Activities List" data-traducir_spanish="Lista de actividades">Activities List</h3>
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
									<th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
									<th width="20%" class="translate" data-traducir_english="Farm" data-traducir_spanish="Granja">Farm</th>
									<th width="20%" class="translate" data-traducir_english="Location" data-traducir_spanish="Locación">Location</th>
									<th width="20%" class="translate" data-traducir_english="Code" data-traducir_spanish="Código">Code</th>
									<th width="10%" class="translate" data-traducir_english="Activity" data-traducir_spanish="Actividad">Activity</th>
									<th width="40%" class="translate" data-traducir_english="Piece Rate" data-traducir_spanish="Precio por pieza">Piece Rate</th>
									<th width="10%" class="translate" data-traducir_english="Active" data-traducir_spanish="Activo">Active</th>
								</tr>
							</thead>
							<tbody id="cuerpo_tabla">
								<?php
								if (count($ACTIVIDADES)) {
									$correlativo = 1;
									foreach ($ACTIVIDADES as $actividad) {
										$idBloque = $actividad['cod_tipo_pack'];
										$nombreSemilla = $actividad['bloque'];
								?>
										<tr class="fila_seleccionable">
											<td onclick="farm_vista_actividades_miscelaneas(<?php echo  $actividad['cod_activity']; ?>)"><?php echo $correlativo; ?></td>
											<td onclick="farm_vista_actividades_miscelaneas(<?php echo $actividad['cod_activity']; ?>)"><?php echo ($actividad['nombre_granja']); ?></td>
											<td onclick="farm_vista_actividades_miscelaneas(<?php echo $actividad['cod_activity']; ?>)"><?php echo ($actividad['nombre_locacion']); ?></td>
											<td onclick="farm_vista_actividades_miscelaneas(<?php echo $actividad['cod_activity']; ?>)"><?php echo ($actividad['codigo']); ?></td>
											<td onclick="farm_vista_actividades_miscelaneas(<?php echo $actividad['cod_activity']; ?>)"><?php echo ($actividad['activity']); ?></td>
											<td onclick="farm_vista_actividades_miscelaneas(<?php echo $actividad['cod_activity']; ?>)">$ <?php echo  $actividad['piece_rate']; ?></td>

											<td>
												<div class="material-switch pull-right">
													<input class="checkbox" id="checkbox_<?php echo $actividad['cod_activity']; ?>" data-id="<?php echo $actividad['cod_activity']; ?>" name="checkbox_<?php echo $actividad['cod_activity']; ?>" type="checkbox" <?php echo ($actividad['activo'] == 1 ? 'checked="checked"' : ''); ?> />
													<label for="checkbox_<?php echo $actividad['cod_activity']; ?>" class=""></label>
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