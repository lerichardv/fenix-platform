<?php
/*
* 	Adminsitración de las localizaciones en las que se haran los transplantes
* 	@author 		Edwin Olivera
* 	@date 			2023-10-08
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

$LOCALIZACIONES = $DB_INV->inv_listado_localizaciones();

$cod_localizacion = $_POST['cod_localizacion'];
if (!isset($_POST['cod_localizacion'])) {
	$cod_localizacion = 0;
}
$localizacion = $DB_INV->inv_obtener_info_localizacion($cod_localizacion);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>Rasgo</title>
</head>

<script type="text/javascript">
	jQuery.ajaxSetup({
		async: false
	});
	grl_overlay_loading('');

	$(document).ready(function() {
		codigo_localizacion = 0;
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
		if (count($localizacion)) {
		?>
			$('#nombre_localizacion').val("<?php echo utf8_encode(($localizacion[0]['nombre'])); ?>");
			$('#abreviatura_localizacion').val("<?php echo utf8_encode($localizacion[0]['abreviatura']); ?>");
			$('#descripcion_localizacion').val("<?php echo utf8_encode($localizacion[0]['descripcion']); ?>");

			// Verificacion sobre el activador
			<?php
			if (
				isset($localizacion[0]['activo'])
				&& $localizacion[0]['activo'] == 1
			) {
			?>
				console.log("Se activo");
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
		codigo_localizacion = <?php echo $cod_localizacion; ?>;
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
				inv_guardar_localizacion(codigo_localizacion);
			});
		} else {
			grl_mensaje('You must fill out all marked fields', 'Please check', 'warning');
		}
	});
	$('.checkbox').change(function(event) {
		/* Act on the event */
		event.preventDefault();
		event.stopPropagation();

		inv_cambiar_estado_localizacion($(this).data('id'), ($(this).attr('checked') ? 0 : 1), event);
	});
</script>

<body>
	<div id="overlay_loading"></div>
	<div id="message_box"></div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="panel-header">
					<h1 class="translate" data-traducir_english="Locations" data-traducir_spanish="Localizaciones">Localizaciones</h1>
				</div>
			</div>
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group input-group-sm" id="div_nombre_capacitacion">
							<label for="nombre_localizacion" class="translate" data-traducir_english="Name" data-traducir_spanish="Nombre">Nombre</label>
							<input type="text" class="form-control letras input requerido requerido_nueva_capacitacion" id="nombre_localizacion" name="nombre_localizacion">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm" id="div_nombre_capacitacion">
							<label for="abreviatura_localizacion" class="translate" data-traducir_english="Abbreviation" data-traducir_spanish="Abreviatura">Abreviatura</label>
							<input type="text" class="form-control letras input requerido requerido_nueva_capacitacion" id="abreviatura_localizacion" name="abreviatura_localizacion">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm" id="div_nombre_capacitacion">
							<label for="descripcion_localizacion" class="translate" data-traducir_english="Description" data-traducir_spanish="Descripción">Descripción</label>
							<input type="text" class="form-control letras input requerido requerido_nueva_capacitacion" id="descripcion_localizacion" name="descripcion_localizacion">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="activo" class="translate" data-traducir_english="Actived?" data-traducir_spanish="¿Activa?">¿Activa?</label><br>
							<div class="material-switch pull-left">
								<input class="checkbox_componente_activo" id="activo" name="activo" type="checkbox" checked />
								<label for="activo" class=""></label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel-footer" align="right">
			<button class="btn btn-sm btn-primary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" type="button" id="btn_guardar">Guardar</button>
		</div>
	</div>
	<div class="busqueda_contenedor">
		<div class="row" style="min-width:100px;">
			<div class="col-sm-12">
				<div class="panel panel-primary" style="overflow:auto;">
					<div class="panel-heading panel_cabecera">
						<h3 class="panel-title translate" data-traducir_english="Locations" data-traducir_spanish="Localizaciones">Localizaciones</h3>
						<div class="pull-right">
							<span class="clickable filter" data-toggle="tooltip" title="Search" data-container="body">
								<i class="fa fa-search"></i>
							</span>
						</div>
					</div>
					<div class="panel-body panel_cuerpo input-group-sm">
						<input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder=Search />
					</div>
					<div style="overflow-x:auto;">
						<table class="table table-condensed display table-striped" id="dev-table">
							<thead>
								<tr class="active info">
									<th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
									<th width="30%" class="translate" data-traducir_english="Trait" data-traducir_spanish="Rasgo">Rasgo</th>
									<th width="10%" class="translate" data-traducir_english="abbreviation" data-traducir_spanish="Abreviatura">Abreviatura</th>
									<th width="45%" class="translate" data-traducir_english="Description" data-traducir_spanish="Descripción">Descripción</th>
									<th width="10%" class="translate" data-traducir_english="Active" data-traducir_spanish="Activo">Activo</th>
								</tr>
							</thead>
							<tbody id="cuerpo_tabla">
								<?php
								if (count($LOCALIZACIONES)) {
									$correlativo = 1;
									foreach ($LOCALIZACIONES as $localizacion) {
								?>
										<tr class="fila_seleccionable">
											<td onclick="inv_vista_localizaciones(<?php echo utf8_encode($localizacion['cod_localizacion']); ?>)"><?php echo $correlativo; ?></td>
											<td onclick="inv_vista_localizaciones(<?php echo utf8_encode($localizacion['cod_localizacion']); ?>)"><?php echo utf8_encode($localizacion['nombre']); ?></td>
											<td onclick="inv_vista_localizaciones(<?php echo utf8_encode($localizacion['cod_localizacion']); ?>)"><?php echo utf8_encode($localizacion['abreviatura']); ?></td>
											<td onclick="inv_vista_localizaciones(<?php echo utf8_encode($localizacion['cod_localizacion']); ?>)"><?php echo utf8_encode($localizacion['descripcion']); ?></td>
											<td>
												<div class="material-switch pull-right">
													<input class="checkbox" id="checkbox_<?php echo utf8_encode($localizacion['cod_localizacion']); ?>" data-id="<?php echo utf8_encode($localizacion['cod_localizacion']); ?>" name="checkbox_<?php echo utf8_encode($localizacion['cod_localizacion']); ?>" type="checkbox" <?php echo ($localizacion['activo'] == 1 ? 'checked="checked"' : ''); ?> />
													<label for="checkbox_<?php echo utf8_encode($localizacion['cod_localizacion']); ?>" class=""></label>
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