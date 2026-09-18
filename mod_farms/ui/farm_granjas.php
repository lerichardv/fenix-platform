<?php
/*
* 	Administración de todas las granjas que se usaran en las plantaciones de las granjas
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
/*INSTANCIAMIENTOS*/
$tituloPagina = "Granja";

$DB_INV = new db_inventario();

$GRANJAS = $DB_INV->inv_listado_granjas();

$codigo_granja = $_POST['cod_granja'];
if (!isset($_POST['cod_granja'])) {
	$codigo_granja = 0;
}

$GRANJA = $DB_INV->inv_obtener_granja($codigo_granja);


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
		codigo_grupo_de_siembra = 0;
		$('.selectpicker').selectpicker({
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
									$this.text().toLowerCase().indexOf(
											search) === -1 ? $this.hide() :
										$this.show();
								})
								if ($target.find('tbody tr:visible').size() === 0) {
									var col_count = $target.find('tr').first().find(
										'td').size();
									var no_results = $(
										'<tr class="filterTable_no_results"><td colspan="4">No Results.</td></tr>'
									)
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
		$('.letras').mask(
			'SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {
				translation: {
					'S': {
						pattern: /[A-Za-z-¡!¿?,.() 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/,
						optional: false
					}
				}
			});
		//Constructores
		inv_constructor_listado_estados();

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
		if (count($GRANJA)) {
		?>
			$('#nombre_granja').val("<?php echo utf8_encode(($GRANJA[0]['farm'])); ?>");
			$('#cod_estado').val("<?php echo utf8_encode($GRANJA[0]['cod_estado']); ?>");
            $('.selectpicker').selectpicker('refresh');

			// Verificacion sobre el activador
			<?php
			if (
				isset($GRANJA[0]['activo'])
				&& $GRANJA[0]['activo'] == 1
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
		codigo_grupo_de_siembra = <?php echo $codigo_granja; ?>;
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
				inv_guardar_granja(codigo_grupo_de_siembra);
			});
		} else {
			grl_mensaje('You must fill out all marked fields', 'Please check', 'warning');
		}
	});
	$('.checkbox').change(function(event) {
		/* Act on the event */
		event.preventDefault();
		event.stopPropagation();
		inv_cambiar_estado_granja($(this).data('id'), ($(this).attr('checked') ? 0 : 1));
	});
</script>

<body>
	<div id="overlay_loading"></div>
	<div id="message_box"></div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="panel-header">
					<h1 class="translate" data-traducir_english="Farms" data-traducir_spanish="Granjas">Farms</h1>
				</div>
			</div>
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="cod_estado" class="translate" data-traducir_english="State" data-traducir_spanish="Estado">State</label>
							<select class="selectpicker show-menu-arrow requerido" data-live-search="true" title="Select" id="cod_estado" name="cod_estado">
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm" id="div_nombre_capacitacion">
							<label for="nombre_granja" class="translate" data-traducir_english="Farm" data-traducir_spanish="Granja">Farm</label>
							<input type="text" class="form-control letras input requerido requerido_nueva_capacitacion" id="nombre_granja" name="nombre_granja">
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="activo" class="translate" data-traducir_english="Actived?" data-traducir_spanish="¿Activa?">Actived?</label><br>
							<div class="material-switch pull-left">
								<input class="checkbox_componente_activo" id="activo" name="activo" type="checkbox" />
								<label for="activo" class=""></label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel-footer" align="right">
			<button class="btn btn-sm btn-primary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" type="button" id="btn_guardar">Save</button>
		</div>
	</div>
	<div class="busqueda_contenedor">
		<div class="row" style="min-width:100px;">
			<div class="col-sm-12">
				<div class="panel panel-primary" style="overflow:auto;">
					<div class="panel-heading panel_cabecera">
						<h3 class="panel-title translate" data-traducir_english="Farms List" data-traducir_spanish="Lista de granjas">Farms List</h3>
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
									<th width="45%" class="translate" data-traducir_english="State" data-traducir_spanish="Estado">State</th>
									<th width="40%" class="translate" data-traducir_english="Farm" data-traducir_spanish="Granja">Farm</th>
									<th width="10%" class="translate" data-traducir_english="Active" data-traducir_spanish="Activo">Active</th>
								</tr>
							</thead>
							<tbody id="cuerpo_tabla">
								<?php
								if (count($GRANJAS)) {
									$correlativo = 1;
									foreach ($GRANJAS as $temporada) {
								?>
										<tr class="fila_seleccionable">
											<td onclick="inv_vista_granja(<?php echo utf8_encode($temporada['cod_farms']); ?>)">
												<?php echo $correlativo; ?></td>
												<td onclick="inv_vista_granja(<?php echo utf8_encode($temporada['cod_farms']); ?>)">
													<?php echo utf8_encode($temporada['nombre_estado']); ?></td>
											<td onclick="inv_vista_granja(<?php echo utf8_encode($temporada['cod_farms']); ?>)">
												<?php echo utf8_encode($temporada['farm']); ?></td>
											<td>
												<div class="material-switch pull-right">
													<input class="checkbox" id="checkbox_<?php echo utf8_encode($temporada['cod_farms']); ?>" data-id="<?php echo utf8_encode($temporada['cod_farms']); ?>" name="checkbox_<?php echo utf8_encode($temporada['cod_farms']); ?>" type="checkbox" <?php echo ($temporada['activo'] == 1 ? 'checked="checked"' : ''); ?> />
													<label for="checkbox_<?php echo utf8_encode($temporada['cod_farms']); ?>" class=""></label>
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