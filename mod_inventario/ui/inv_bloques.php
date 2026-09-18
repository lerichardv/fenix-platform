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
$tituloPagina = "Bloques";

$DB_INV = new db_inventario();

$BLOQUES = $DB_INV->inv_listado_bloques();

$cod_bloque = $_POST['cod_bloque'];
if (!isset($_POST['cod_bloque'])) {
	$cod_bloque = 0;
}

$BLOQUE = $DB_INV->inv_obtener_bloque($cod_bloque);


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
		codigo_bloque = 0;
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
		//Constructores
		inv_constructor_listado_granjas();
		inv_constructor_listado_campos();
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
		if (count($BLOQUE)) {
		?>
			$('#nombre_bloque').val("<?php echo utf8_encode($BLOQUE[0]['bloque']); ?>");
			$('#cod_granja').val("<?php echo utf8_encode(($BLOQUE[0]['cod_farm'])); ?>");
			$('#cod_field').val("<?php echo utf8_encode(($BLOQUE[0]['cod_field'])); ?>");
			$('.selectpicker').selectpicker('refresh');

			// Verificacion sobre el activador
			<?php
			if (
				isset($BLOQUE[0]['activo'])
				&& $BLOQUE[0]['activo'] == 1
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
		codigo_bloque = <?php echo $cod_bloque; ?>;
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
				inv_guardar_bloque(codigo_bloque);
			});
		} else {
			grl_mensaje('You must fill out all marked fields', 'Please check', 'warning');
		}
	});
	$('.checkbox').change(function(event) {
		/* Act on the event */
		event.preventDefault();
		event.stopPropagation();
		inv_cambiar_estado_bloque($(this).data('id'), ($(this).attr('checked') ? 0 : 1));
	});

	function activarEdicionDeCantidad(idBloque) {
		let btnActivarInput = $('#btn_bloque_' + idBloque);
		let miInput = $('#input_cantidad_acres_' + idBloque);

		miInput.toggle(); // Alterna la visibilidad del input
		miInput.prop('disabled', false);

		btnActivarInput.toggle(); // Alterna la visibilidad del input
		miInput.focus();

		valorCantidadInicialDeSemillas = miInput.val();
		let valorParaBoton = parseFloat(valorCantidadInicialDeSemillas)
		btnActivarInput.text(valorParaBoton.toLocaleString());
	}

	//Este método se ejecuta cada vez que se sale de un input de cantidad
	function salirDelInputDeCantidad(idBloque, nombre_bloque) {

		let btnActivarInput = $('#btn_bloque_' + idBloque);
		let miInput = $('#input_cantidad_acres_' + idBloque);
		if (btnActivarInput == undefined || miInput == undefined) return;

		guardarCantidadIngresada(parseFloat(valorCantidadInicialDeSemillas), parseFloat(miInput.val()), idBloque, nombre_bloque);
		valorCantidadInicialDeSemillas = 0

		// Deshabilitar el input cuando se pierde el foco
		miInput.toggle(0);
		miInput.prop('disabled', true);
		btnActivarInput.toggle(200); // Alterna la visibilidad del input
	}

	function detectarDatosEntradaDeTeclados(event, idBloque) {

		let btnActivarInput = $('#btn_bloque_' + idBloque);
		let miInput = $('#input_cantidad_acres_' + idBloque);
		var valorInput = miInput.val();
		var puntoEncontrado = false;
		var numerosDespuesDelPunto = 0;

		 
		if (valorInput <= 0.0) {
			valorInput = 0;
		}
		if (valorInput >= 9.99) {
			valorInput = 9.99;
		}
		 

		miInput.val(valorInput);
		let valorParaBoton = parseFloat(valorInput)
		btnActivarInput.text(valorParaBoton.toLocaleString());
	}

	function detectarTeclasDeSalida(event, idBloque, nombre_bloque) {
		let btnActivarInput = $('#btn_bloque_' + idBloque);
		let miInput = $('#input_cantidad_acres_' + idBloque);

		var valorInput = miInput.val();

		if (event.keyCode === 13) { //Tecla "Enter"/"Entrar"
			miInput.prop('disabled', true);
			valorCantidadInicialDeSemillas = parseFloat(miInput.val());
			//guardarCantidadIngresada(parseFloat(valorCantidadInicialDeSemillas), parseFloat(valorCantidadInicialDeSemillas), idBloque, nombre_bloque, true);

		}


		if (event.keyCode === 27) { // Tecla "ESC"
			// Aquí puedes ejecutar el código que deseas cuando se presiona "ESC"
			valorCantidadInicialDeSemillas = $("#input_cantidad_inicial_bloque_" + idBloque).val();
			let valorParaBoton = parseFloat(valorCantidadInicialDeSemillas)
			btnActivarInput.text(valorParaBoton.toLocaleString());
			miInput.val(valorCantidadInicialDeSemillas);
			miInput.prop('disabled', true);
		}
	}

	function guardarCantidadIngresada(valorInicial, valorNuevo, idBloque, nombre_bloque, duplicarValores = false) {
		inv_guardar_cantidad_acres(idBloque, parseFloat(valorNuevo));
	}
</script>

<body>
	<div id="overlay_loading"></div>
	<div id="message_box"></div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="panel-header">
					<h1 class="translate" data-traducir_english="Blocks" data-traducir_spanish="Granjas">Blocks</h1>
				</div>
			</div>
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="cod_granja" class="translate" data-traducir_english="Farm" data-traducir_spanish="Granja">Farm</label>
							<select class="selectpicker show-menu-arrow requerido" data-live-search="true" title="Select" id="cod_granja" name="cod_granja">
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm">
							<label for="cod_field" class="translate" data-traducir_english="Field" data-traducir_spanish="Campo">Field</label>
							<select class="selectpicker show-menu-arrow requerido" data-live-search="true" title="Select" id="cod_field" name="cod_field">
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm" id="div_nombre_capacitacion">
							<label for="nombre_bloque" class="translate" data-traducir_english="Block" data-traducir_spanish="Bloque">Block</label>
							<input type="text" class="form-control letras input requerido requerido_nueva_capacitacion" id="nombre_bloque" name="nombre_bloque">
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
						<h3 class="panel-title translate" data-traducir_english="Blocks List" data-traducir_spanish="Lista de granjas">Blocks List</h3>
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
									<th width="40%" class="translate" data-traducir_english="Block" data-traducir_spanish="Bloque">Block</th>
									<th width="20%" class="translate" data-traducir_english="Farm" data-traducir_spanish="Granja">Farm</th>
									<th width="40%" class="translate" data-traducir_english="Field" data-traducir_spanish="Campo">Field</th>
									<th width="10%" class="translate" data-traducir_english="Acres" data-traducir_spanish="Acres">Acres</th>
									<th width="10%" class="translate" data-traducir_english="Active" data-traducir_spanish="Activo">Active</th>
								</tr>
							</thead>
							<tbody id="cuerpo_tabla">
								<?php
								if (count($BLOQUES)) {
									$correlativo = 1;
									foreach ($BLOQUES as $bloquet) {
										$idBloque = $bloquet['cod_bloque'];
										$nombreSemilla = $bloquet['bloque'];
								?>
										<tr class="fila_seleccionable">
											<td onclick="inv_vista_bloque(<?php echo  $bloquet['cod_bloque']; ?>)"><?php echo $correlativo; ?></td>
											<td onclick="inv_vista_bloque(<?php echo $bloquet['cod_bloque']; ?>)"><?php echo utf8_encode($bloquet['bloque']); ?></td>
											<td onclick="inv_vista_bloque(<?php echo $bloquet['cod_bloque']; ?>)"><?php echo utf8_encode($bloquet['nombre_granja']); ?></td>
											<td onclick="inv_vista_bloque(<?php echo $bloquet['cod_bloque']; ?>)"><?php echo utf8_encode($bloquet['nombre_campo']); ?></td>
											<!-- <td onclick="inv_vista_bloque(<?php echo $bloquet['cod_bloque']; ?>)"><?php echo $bloquet['acres']; ?></td> -->
											<td class="fila_seleccionable">
												<button class="btnCantidadSemillas" onclick="activarEdicionDeCantidad(<?php echo $idBloque ?>)" id="btn_bloque_<?php echo $idBloque ?>"><?php echo number_format($bloquet['acres'], 2, ".", ","); ?></button>
												<input hidden step="0.01" max="9.99" min="0" type="number" id="input_cantidad_acres_<?php echo $idBloque ?>" onblur="salirDelInputDeCantidad(<?php echo $idBloque ?>, '<?php echo utf8_encode($nombreSemilla) ?>')" value="<?php echo   $bloquet['acres']; ?>" onkeydown="detectarTeclasDeSalida(event,<?php echo $idBloque ?>,'<?php echo utf8_encode($nombreSemilla) ?>')" oninput="detectarDatosEntradaDeTeclados(event,<?php echo $idBloque ?>)" placeholder="">
												<input class="input_oculto" hidden type="number" id="input_cantidad_inicial_bloque_<?php echo $idBloque ?>" value="<?php echo   $bloquet['acres']; ?>">

											</td>
											<td>
												<div class="material-switch pull-right">
													<input class="checkbox" id="checkbox_<?php echo $bloquet['cod_bloque']; ?>" data-id="<?php echo $bloquet['cod_bloque']; ?>" name="checkbox_<?php echo $bloquet['cod_bloque']; ?>" type="checkbox" <?php echo ($bloquet['activo'] == 1 ? 'checked="checked"' : ''); ?> />
													<label for="checkbox_<?php echo $bloquet['cod_bloque']; ?>" class=""></label>
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