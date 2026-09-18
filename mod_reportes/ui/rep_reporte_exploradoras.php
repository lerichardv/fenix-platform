<?PHP

/*

 * Reporte de Exploraciones, muestra listado de plantaciones con los resultados de las exploraciones.

 * @author      Jairo Bonilla

 * @date        2019-01-20

 */

session_start();

if (!isset($_SESSION['cod_usuario'])) {

	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/

include_once "../../libs/db_classes/db_mysql_conn.php";

include_once "../../libs/db_classes/db_reportes.php";

/*INSTANCIAMIENTOS*/

$DB_REP             = new db_reportes();

$fecha_inicial       = $_POST['fecha_inicial'];

$fecha_final         = $_POST['fecha_final'];

if (isset($_POST['cod_info_empresa'])) {
	$cod_info_empresa    = implode(',', $_POST['cod_info_empresa']);
} else {
	$cod_info_empresa    = "";
}

$cod_granjas_usuario = str_replace(['[', '"', ']'], '', $_SESSION['cod_info_empresa']);
$PLANTACIONES = [];

// echo $fecha_inicial."<br>";
// echo $fecha_final."<br>";
// echo $cod_granjas_usuario;


if (isset($fecha_final)) {


	$PLANTACIONES = $DB_REP->rep_reporte_exploradoras($fecha_inicial, $fecha_final, $cod_granjas_usuario);
	// $PLANTACIONES = $DB_REP->rep_reporte_exploradoras($fecha_inicial,$fecha_final,$cod_granjas_usuario);

}



?>

<script>
	$(document).ready(function() {

		jQuery.ajaxSetup({
			async: false
		});

		//Inicializar la barra de botones

		init_button_bar();

		grl_overlay_loading('');

		//Habilitación de listboxs

		$('.selectpicker').selectpicker({

			dropupAuto: 'true',

			container: 'body',

			size: '10',

			width: '85%',

			style: 'btn-sm btn-info has-btn-all',

			tickIcon: 'fa fa-check'

		});

		//Habilita los selects para mobile

		if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {

			$('.selectpicker').selectpicker('mobile');

		}

		var fecha_hoy = new Date();

		var hoy = (fecha_hoy.getMonth() + 1) + "/" + fecha_hoy.getDate() + "/" + fecha_hoy.getFullYear();

		var hoy_default = fecha_hoy.getFullYear() + "-" + (fecha_hoy.getMonth() + 1) + "-" + fecha_hoy.getDate();



		//Construcción de fechas

		$('#div_fecha_inicial').datetimepicker({

			/*pickTime: false,

			language: 'es',

			//defaultDate: '1994-12-31',

			icons: {

						time: "fa fa-clock-o",

						date: "fa fa-calendar",

						up: "fa fa-arrow-up",

						down: "fa fa-arrow-down"

					}*/

			locale: 'es',

			format: 'MM-DD-YYYY',

			ignoreReadonly: true,

			icons: {

				time: "fa fa-clock-o",

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

			/*pickTime: false,

			language: 'es',

			//defaultDate: '1994-12-31',

			icons: {

						time: "fa fa-clock-o",

						date: "fa fa-calendar",

						up: "fa fa-arrow-up",

						down: "fa fa-arrow-down"

					}*/

			locale: 'es',

			format: 'MM-DD-YYYY',

			ignoreReadonly: true,

			icons: {

				time: "fa fa-clock-o",

				date: "fa fa-calendar",

				up: "fa fa-arrow-up",

				down: "fa fa-arrow-down",

				previous: 'fa fa-arrow-left',

				next: 'fa fa-arrow-right',

			}

		}).on('dp.hide', function(e) {

			$('#div_fecha_inicial').data("DateTimePicker").maxDate(e.date);

		});

		conf_constructor_listado_granjas('cod_info_empresa', 0);



		/*----------------------------------------------------------------------------------

		                                Validando listboxs

		----------------------------------------------------------------------------------*/

		$(".selectpicker.requerido").change(function() {

			var objeto = $(this);

			id = $(this).parent().children('.check-all').attr('id');

			if (objeto.val() == null || objeto.val() == '-b' || objeto.val() == '') {

				objeto.selectpicker('setStyle', 'btn-info', 'remove');

				objeto.selectpicker('setStyle', 'btn-danger');

				$("#" + id).addClass('btn-danger').removeClass('btn-info allselected');

			} else {

				objeto.selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');

				objeto.selectpicker('setStyle', 'btn-info');

				objeto.removeClass('campo-vacio');

				$("#" + id).removeClass('btn-danger').addClass('btn-info');

			}

			objeto.selectpicker('refresh');

		});



		/*----------------------------------------------------------------------------------

		                    Validación input, textarea requeridos

		----------------------------------------------------------------------------------*/

		$(".input.requerido, .input.requerido-modal").keyup(function(event) {

			if ($(this).val().trim() != "") {

				$('#btn_registrar_denuncia').removeAttr('disabled');

				$(this).removeClass('input-has-error campo-vacio campo-vacio-modal');

			} else {

				$(this).addClass('input-has-error');

			}

		});

		$('#fecha_inicial').val('<?php echo $fecha_inicial; ?>');

		$('#fecha_final').val('<?php echo $fecha_final; ?>');

		$('#cod_info_empresa').selectpicker('val', [<?php echo $cod_info_empresa; ?>]);



		$('.selectpicker').selectpicker('refresh');

		<?php if (count($PLANTACIONES) && isset($fecha_final)) {

		?>

			$('#excel').removeClass('hide');

			/*$('#dev-table').DataTable({

	  			"scrollY":        "400px",

		        "scrollCollapse": true,

		        "paging":         false

			});*/

			$('#dev-table').DataTable({

				/*"language":

				{

				    "sProcessing":     "Procesando...",

				    "sLengthMenu":     "Mostrar _MENU_ registros",

				    "sZeroRecords":    "No se encontraron resultados",

				    "sEmptyTable":     "Ningún dato disponible en esta tabla",

				    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",

				    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",

				    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",

				    "sInfoPostFix":    "",

				    "sSearch":         "Buscar:",

				    "sUrl":            "",

				    "sInfoThousands":  ",",

				    "sLoadingRecords": "Cargando...",

				    "oPaginate": {

				        "sFirst":    "Primero",

				        "sLast":     "Último",

				        "sNext":     "Siguiente",

				        "sPrevious": "Anterior"

				    },

				    "oAria": {

				        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",

				        "sSortDescending": ": Activar para ordenar la columna de manera descendente"

				    }

				},*/

				"dom": 'Bfrtip',

				"buttons": [

					{

						extend: 'pdfHtml5',

						orientation: 'landscape',

						pageSize: 'A0',

						title: 'Scouting Report',

					},

					'print',

				]

			});

		<?php

		}

		?>



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

										var no_results = $('<tr class="filterTable_no_results"><td colspan="7">No hay resultados.</td></tr>')

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

		})

		$(".check-all").click(function(event) {

			event.stopPropagation();

			id = $(this).parent().children('.selectpicker').attr('id');

			if ($(this).hasClass('allselected')) {

				$(this).removeClass('allselected btn-info').addClass('btn-danger');

				$('#' + id + ' option').prop('selected', false);

				$('#' + id).selectpicker('setStyle', 'btn-danger');

				$('#' + id).selectpicker('setStyle', 'btn-info', 'remove');

			} else {

				$(this).addClass('allselected btn-info').removeClass('btn-danger');

				$('#' + id + ' option').prop('selected', true);

				$('#' + id).selectpicker('setStyle', 'btn-danger', 'remove');

				$('#' + id).selectpicker('setStyle', 'btn-info');

			}

			$('#' + id).selectpicker('refresh');

		});

		$("#excel").click(function() {

			var error = 0;

			x1 = "<?php echo $fecha_inicial; ?>";

			x2 = "<?php echo $fecha_final; ?>";

			x3 = "<?php echo $cod_info_empresa; ?>";



			//Validación de los inputs

			if ($("#fecha_inicial").val() == '' && x1 == '') {

				$('#div_fecha_inicial').addClass('has-error');

				if (error != 1) error = 1;

			} else

				$('#div_fecha_inicial').removeClass('has-error');

			if ($("#fecha_final").val() == '' && x2 == '') {

				$('#div_fecha_final').addClass('has-error');

				if (error != 1) error = 1;

			} else

				$('#div_fecha_final').removeClass('has-error');



			if (error != 1) {

				//x1 = $("#fecha_inicial").val()+" 00:00:00";

				//x2 = $("#fecha_final").val()+" 23:59:59";

				//x3 = $("#estado").val();

				//x4 = $("#tipo_documento").val();

				var url = "mod_reportes/funciones/rep_reporte_exploradoras_excel.php?x1=" + x1 + "&x2=" + x2 + "&x3=" + x3;

				$(location).attr('href', url);

			} else {

				grl_mensaje('You must fill the empty fields', 'Debe llenar los campos vacíos', 'warning');

			}

		});

		$('#buscar').click(function(event) {

			/* Act on the event */

			var error = 0;

			$(".input.requerido").map(function() {

				if (!$(this).val())

				{

					error = 1;

					$(this).addClass('input-has-error campo-vacio campo-vacio-modal');

					return false;

				} else

				{

					$(this).removeClass('input-has-error campo-vacio campo-vacio-modal');

				}

			});

			$(".selectpicker.requerido").map(function() {

				if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '')

				{

					$(this).selectpicker('setStyle', 'btn-info', 'remove');

					$(this).selectpicker('setStyle', 'btn-danger');

					error = 1;

				} else

				{

					$(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');

					$(this).selectpicker('setStyle', 'btn-info');

					$(this).removeClass('campo-vacio');

				}

				$(this).selectpicker('refresh');

			});

			if (error == 0)

			{

				grl_overlay_loading('');

				$('#modal_loading').modal('hide');

				$('#modal_loading').on('hidden.bs.modal', function() {
					console.log("------------------------------------------------------------>");
					console.log($('#fecha_inicial').val());
					console.log($('#fecha_final').val());
					console.log($('#cod_info_empresa').val());
					console.log("<------------------------------------------------------------");
					$.ajax({

							url: 'mod_reportes/ui/rep_reporte_exploradoras.php',

							type: 'POST',

							dataType: 'html',

							data: {

								fecha_inicial: $('#fecha_inicial').val(),

								fecha_final: $('#fecha_final').val(),

								cod_info_empresa: $('#cod_info_empresa').val()

							},

						})

						.done(function(data) {

							$('#div_cuerpo_menu').empty();

							$('#div_cuerpo_menu').html(data);

						})

						.fail(function() {

							console.log("error");

						})

						.always(function() {

							console.log("complete");

						});



				});

			} else

			{

				grl_mensaje('You must fill the empty fields', 'Debe llenar los campos vacíos', 'warning');

			}

		});

		$('#modal_loading').modal('hide');

		jQuery.ajaxSetup({
			async: true
		});

	});
</script>

<style type="text/css">
	.row {

		padding: 0 10px;

	}

	.panel_cabecera div {

		margin-top: -18px;

		font-size: 15px;

	}

	.panel_cabecera div span {

		margin-left: 5px;

	}

	.panel_cuerpo {

		display: none;

	}

	#dev-table {

		font-size: 12px;

	}



	.resumen-bitacora {

		margin-right: 10px;

		display: inline-block;

		padding: 2px;

		border-radius: 5px;

	}



	.badge.bitacora {

		background: rgb(30, 30, 30);

	}

	.resumen-bitacora.nivel-3 {

		background: rgb(205, 244, 205) !important;

	}

	.resumen-bitacora.nivel-4 {

		background: rgb(255, 150, 150) !important;

	}



	.badge.bitacora.nivel-3 {

		background: rgb(76, 174, 76);

	}

	.badge.bitacora.nivel-4 {

		background: rgb(255, 105, 105);

	}



	.nivel-3 {

		border-right-color: rgb(76, 174, 76);

	}

	.nivel-4 {

		border-right-color: rgb(255, 150, 150);

	}

	#dev-table>tbody tr {

		cursor: pointer;

	}

	.donacion_0 {

		background-color: #D6858F;

	}

	.no-padding {

		padding: 0px !important;

	}



	.no-margin {

		margin: 0px !important;

	}

	h4 {

		color: #01579B !important;

	}

	.tr_finalizada {

		background-color: rgb(76, 174, 76);

	}

	.tr_encurso {

		background-color: #fff;

	}
</style>

<div id="overlay_loading"></div>

<div id="message_box"></div>

<div class="panel panel-default">

	<div class="panel-body">

		<div class="page-header">

			<h1 class="translate" data-traducir_english="Planting Scouting Report" data-traducir_spanish="Reporte de Exploraciones de Plantación">Reporte de Exploraciones de Plantación</h1>

		</div>

		<div class="row">

			<div class="col-md-3">

				<div class="form-group input-group-sm date" id="">

					<label for="fecha_inicial" class="translate" data-traducir_english="Initial Date" data-traducir_spanish="Fecha Inicial">Fecha Inicial</label>

					<div class='input-group input-group-sm date' id='div_fecha_inicial'>

						<span class="input-group-addon">

							<span class="fa fa-calendar">

							</span>

						</span>

						<input type='text' class="form-control input requerido" id="fecha_inicial" readonly="" />

					</div>

				</div>

			</div>

			<div class="col-md-3">

				<div class="form-group input-group-sm date" id="">

					<label for="fecha_final" class="translate" data-traducir_english="Final Date" data-traducir_spanish="Fecha Final">Fecha Final</label>

					<div class='input-group input-group-sm date' id='div_fecha_final'>

						<span class="input-group-addon">

							<span class="fa fa-calendar">

							</span>

						</span>

						<input type='text' class="form-control input requerido" id="fecha_final" readonly="" />

					</div>

				</div>

			</div>

			<div class="col-md-3">

				<label for="cod_info_empresa" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</label>

				<div class="form-group show-tick">

					<select class="selectpicker show-menu-arrow requerido" title="Seleccione" multiple="multiple" data-actions-box="true" id="cod_info_empresa" name="cod_info_empresa">

					</select>

				</div>

			</div>

			<div class="col-md-1 col-md-offset-2">

				<div class="form-group input-group-sm" id="div_buscar">

					<center>

						<label for="buscar" class="translate" data-traducir_english="Search" data-traducir_spanish="Buscar">Buscar</label>

						<div class="form-group input-group-sm" id="div_buscar">

							<button class="btn btn-info" type="button" id="buscar" name="buscar">

								<i class="fa fa-search"></i>

							</button>

						</div>

					</center>

				</div>

			</div>

		</div>

		<div class="row" style="overflow:auto; min-width:100px;">

			<div class="col-md-12">

				<div class="panel panel-info">

					<div class="panel-heading">

						<h3 class="panel-title translate" data-traducir_english="Planting List" data-traducir_spanish="Listado de Plantaciones">Listado de Plantaciones</h3>

					</div>

					<!-- Table -->

					<div class="responsive_table_container">

						<table class="table table-responsive table-condensed table-striped" id="dev-table">

							<thead>

								<tr class="active info">

									<th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>

									<th width="15%" class="translate" data-traducir_english="Planting" data-traducir_spanish="Plantación">Plantación</th>

									<th width="15%" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</th>

									<th width="15%" class="translate" data-traducir_english="Watercress" data-traducir_spanish="Berro de Agua">Berro de Agua</th>

									<th width="15%" class="translate" data-traducir_english="Crop #" data-traducir_spanish="Cultivo #">Cultivo #</th>

									<th width="10%" class="translate" data-traducir_english="Zones" data-traducir_spanish="Zonas">Zonas</th>

									<th width="10%" class="translate" data-traducir_english="Blocks" data-traducir_spanish="Bloques">Bloques</th>

									<th width="10%" class="translate" data-traducir_english="Scouting Date" data-traducir_spanish="Fecha Exploración">Fecha Exploración</th>

									<th width="10%" class="translate" data-traducir_english="Insect(s)" data-traducir_spanish="Insecto(s)">Insecto(s)</th>

									<th width="15%" class="translate" data-traducir_english="Disease(s)" data-traducir_spanish="Enfermedad(es)">Enfermedad(es)</th>

									<th width="10%" class="translate" data-traducir_english="Weed(s)" data-traducir_spanish="Hierba(s)">Hierba(s)</th>

									<th width="10%" class="translate" data-traducir_english="Other(s) Damage(s)" data-traducir_spanish="Otro(s) Daño(s)">Otro(s) Daño(s)</th>

									<th width="10%" class="translate" data-traducir_english="Worms" data-traducir_spanish="Gusanos">Gusanos</th>

									<th width="10%" class="translate" data-traducir_english="Eggs" data-traducir_spanish="Huevos">Huevos</th>

									<th width="10%" class="translate" data-traducir_english="Leaf Hoppers" data-traducir_spanish="Salta Montes">Salta Montes</th>

									<th width="10%" class="translate" data-traducir_english="Aphids" data-traducir_spanish="Afidos">Afidos</th>

									<th width="10%" class="translate" data-traducir_english="Stink Bugs" data-traducir_spanish="Chinches">Chinches</th>

									<th width="10%" class="translate" data-traducir_english="Gnats" data-traducir_spanish="Moscos">Moscos</th>

									<th width="10%" class="translate" data-traducir_english="Flea Beetles" data-traducir_spanish="Escarabajos">Escarabajos</th>

									<th width="10%" class="translate" data-traducir_english="Cyclaman Mites" data-traducir_spanish="Acaros">Acaros</th>

									<th width="10%" class="translate" data-traducir_english="Cercospora Leaf Spot" data-traducir_spanish="Cercospora Leaf Spot">Cercospora Leaf Spot</th>

									<th width="10%" class="translate" data-traducir_english="Pythium" data-traducir_spanish="Pythium">Pythium</th>

									<th width="10%" class="translate" data-traducir_english="Rhizoctonia Aerial Blight" data-traducir_spanish="Rhizoctonia Aerial Blight">Rhizoctonia Aerial Blight</th>

									<th width="10%" class="translate" data-traducir_english="Bacteria" data-traducir_spanish="Bacteria">Bacteria</th>

									<th width="10%" class="translate" data-traducir_english="Sclerotinia" data-traducir_spanish="Sclerotinia">Sclerotinia</th>

									<th width="10%" class="translate" data-traducir_english="Alternaria Specks" data-traducir_spanish="Alternaria Specks">Alternaria Specks</th>

									<th width="10%" class="translate" data-traducir_english="Mildew" data-traducir_spanish="Mildew">Mildew</th>

									<th width="10%" class="translate" data-traducir_english="Virus" data-traducir_spanish="Virus">Virus</th>

									<th width="10%" class="translate" data-traducir_english="Dollarweed" data-traducir_spanish="Dollarweed">Dollarweed</th>

									<th width="10%" class="translate" data-traducir_english="Frogs Bit" data-traducir_spanish="Frogs Bit">Frogs Bit</th>

									<th width="10%" class="translate" data-traducir_english="Mud Plantain" data-traducir_spanish="Plantas con Lodo">Plantas con Lodo</th>

									<th width="10%" class="translate" data-traducir_english="Tube Weed" data-traducir_spanish="Tripa de Pollo">Tripa de Pollo</th>

									<th width="10%" class="translate" data-traducir_english="Grass" data-traducir_spanish="Zacate">Zacate</th>

									<th width="10%" class="translate" data-traducir_english="Damaged Leaves" data-traducir_spanish="Hojas Dañadas">Hojas Dañadas</th>

									<th width="10%" class="translate" data-traducir_english="Purple Stem" data-traducir_spanish="Tallos Púrpuras">Tallos Púrpuras</th>

									<th width="10%" class="translate" data-traducir_english="Watercress Rooted" data-traducir_spanish="Berro Enraizado">Berro Enraizado</th>

									<th width="15%" class="translate" data-traducir_english="System Date" data-traducir_spanish="Fecha Sistema">Fecha Sistema</th>

									<th width="15%" class="translate" data-traducir_english="System Time" data-traducir_spanish="Hora Sistema">Hora Sistema</th>

								</tr>

							</thead>

							<tbody>

								<?PHP

								$cuerpo_tabla = '';

								if (count($PLANTACIONES)) {

									$correlativo = 1;

									foreach ($PLANTACIONES as $plantacion) {

										$date_insert = DateTime::createFromFormat('m-d-Y H:i:s', $plantacion['date_insert']);

										$fecha = $date_insert->format('m-d-Y');

										$hora = $date_insert->format('h:i:sa');

										$cuerpo_tabla .= '<tr>

	                            									<td>' . $correlativo . '</td>

	                            									<td>' . utf8_encode($plantacion['anio_plantacion'] . '-' . $plantacion['num_plantacion'] . '-' . $plantacion['codigo_temporada']) . '</td>

	                            									<td>' . utf8_encode($plantacion['nombre_empresa']) . '</td>

	                            									<td>' . utf8_encode($plantacion['flag_watercress'] == 1 ? 'Yes' : 'No') . '</td>

	                            									<td>' . utf8_encode($plantacion['crop_number']) . '</td>

	                            									<td>' . utf8_encode($plantacion['zonas']) . '</td>

	                            									<td>' . utf8_encode($plantacion['bloques']) . '</td>

	                            									<td>' . utf8_encode($plantacion['fecha_exploracion']) . '</td>

	                            									<td>' . utf8_encode('Worms: ' . $plantacion['gusanos'] . '<br>Eggs: ' . $plantacion['huevos'] . '<br>Leaf Hoopers: ' . $plantacion['saltahojas'] . '<br>Aphids: ' . $plantacion['afidos'] . '<br>Stink Bugs: ' . $plantacion['chinches'] . '<br>Gnats: ' . $plantacion['moscos'] . '<br>Flea Beetles: ' . $plantacion['escarabajos'] . '<br>Cyclaman Mites: ' . $plantacion['acaros'] . '<br>Spidermites: ' . $plantacion['spidermites']) . '</td>

	                            									<td>' . utf8_encode('Cercospora Leaf Spot: ' . $plantacion['cercospora_leaf_spot'] . '<br>Pythium/Damp Off: ' . $plantacion['pythium'] . '<br>Rhizoctonia Aerial Blight: ' . $plantacion['rhizoctonia'] . '<br>Bacteria: ' . $plantacion['bacteria'] . '<br>Sclerotinia: ' . $plantacion['sclerotinia'] . '<br>Alternaria Specks: ' . $plantacion['alternaria_specks'] . '<br>Mildew: ' . $plantacion['mildew'] . '<br>Virus: ' . $plantacion['virus'] . '<br>White Rust: ' . $plantacion['white_rust'] . '<br>Salt Accumulation: ' . $plantacion['salt_accumulation']) . '</td>

	                            									<td>' . utf8_encode('Dollarweed: ' . $plantacion['dolar'] . '<br>Frogs Bit: ' . $plantacion['frogs_bit'] . '<br>Mud Plantain: ' . $plantacion['plantas_lodo'] . '<br>Tube Weed: ' . $plantacion['tripa_pollo'] . '<br>Grass: ' . $plantacion['zacate'] . '<br>Nutsedge: ' . $plantacion['nutsedge']) . '</td>

	                            									<td>' . utf8_encode('Damaged Leaves: ' . $plantacion['hojas_danadas'] . '<br>Purple Stem: ' . $plantacion['tallos_purpuras'] . '<br>Watercress rooted: ' . $plantacion['berro_enraizado'] . '<br>Buds: ' . $plantacion['buds'] . '<br>Zigzag Stems: ' . $plantacion['zigzag_stems'] . '<br>Nutrient Deficiency: ' . $plantacion['nutrient_deficiency'] . '<br>Round Up: ' . $plantacion['round_up'] . '<br>Light Color: ' . $plantacion['light_color'] . '<br>Mealybugs: ' . $plantacion['mealybugs'] . '<br>Thrips: ' . $plantacion['thrips']) . '</td>

	                            									<td>' . utf8_encode($plantacion['gusanos']) . '</td>

	                            									<td>' . utf8_encode($plantacion['huevos']) . '</td>

	                            									<td>' . utf8_encode($plantacion['saltahojas']) . '</td>

	                            									<td>' . utf8_encode($plantacion['afidos']) . '</td>

	                            									<td>' . utf8_encode($plantacion['chinches']) . '</td>

	                            									<td>' . utf8_encode($plantacion['moscos']) . '</td>

	                            									<td>' . utf8_encode($plantacion['escarabajos']) . '</td>

	                            									<td>' . utf8_encode($plantacion['acaros']) . '</td>

	                            									<td>' . utf8_encode($plantacion['cercospora_leaf_spot']) . '</td>

	                            									<td>' . utf8_encode($plantacion['pythium']) . '</td>

	                            									<td>' . utf8_encode($plantacion['rhizoctonia']) . '</td>

	                            									<td>' . utf8_encode($plantacion['bacteria']) . '</td>

	                            									<td>' . utf8_encode($plantacion['sclerotinia']) . '</td>

	                            									<td>' . utf8_encode($plantacion['alternaria_specks']) . '</td>

	                            									<td>' . utf8_encode($plantacion['mildew']) . '</td>

	                            									<td>' . utf8_encode($plantacion['virus']) . '</td>

	                            									<td>' . utf8_encode($plantacion['dolar']) . '</td>

	                            									<td>' . utf8_encode($plantacion['frogs_bit']) . '</td>

	                            									<td>' . utf8_encode($plantacion['plantas_lodo']) . '</td>

	                            									<td>' . utf8_encode($plantacion['tripa_pollo']) . '</td>

	                            									<td>' . utf8_encode($plantacion['zacate']) . '</td>

	                            									<td>' . utf8_encode($plantacion['hojas_danadas']) . '</td>

	                            									<td>' . utf8_encode($plantacion['tallos_purpuras']) . '</td>

	                            									<td>' . utf8_encode($plantacion['berro_enraizado']) . '</td>

	                            									<td>' . $fecha . '</td>

	                            									<td>' . $hora . '</td>'

											. '</tr>';

										$correlativo++;
									}
								} else { //Si no hay registros informará al diligencia

									$cuerpo_tabla .= '<tr>

																<td align="center" colspan="8">No records</td>

	                                                      </tr>';
								}

								echo $cuerpo_tabla;

								?>

							</tbody>

						</table>

					</div>

				</div> <!-- Row -->

			</div>

		</div>

		<!-- <div class="row">

			<div class="col-md-12" id="div_grafico" align="center">

				<i class="fa fa-signal" style="font-size: 300px;"></i>

			</div>

		</div> -->

	</div>

</div>

<div class="panel-footer-actions text-right smooth-transition actions-container-is-closed" id="panel_footer_actions">

	<div class="close-actions-container">

		<button class="btn btn-xs btn-close-actions" id="btn_close_actions">

			<i class="fa fa-chevron-circle-down fa-lg"></i>

		</button>

	</div>

	<div class="row actions">

		<div class="col-xs-12 col-md-12 col-sm-12 nopadding smooth-transition" id="div_acciones">

			<button class="btn btn-sm btn-primary btn-open-actions main-actions smooth-transition" type="button" id="btn_open_actions" name="btn_open_actions">

				<i class="fa fa-ellipsis-v"></i>

			</button>

		</div>

		<div class="col-md-10 col-sm-10 col-xs-8 text-center smooth-transition">

		</div>

		<div class="col-md-2 col-sm-2 col-xs-4 text-center nopadding smooth-transition">

			<button id="excel" name="excel" class="btn btn-sm btn-info main-actions hide smooth-transition" type="button" data-loading-text="Convirtiendo...">Excel</button>

		</div>

	</div>

</div> <!-- panel-footer -->

<script type="text/javascript">
	<?php

	/*if (count($PLANTACIONES)) {

		?>

		Highcharts.chart('div_grafico', {

		    chart: {

		        type: 'line'

		    },

		    title: {

		        text: 'Planting Scouting Report'

		    },

		    subtitle: {

		        text: 'Planting Scouting Report'

		    },

		    xAxis: {

		        categories: ['Insects', 'Diseases', 'Weeds', 'Other Damage']

		    },

		    yAxis: {

		        title: {

		            text: 'Amount'

		        }

		    },

		    plotOptions: {

		        series: {

		            label: {

		                connectorAllowed: false

		            },

		        }

		    },

		    legend: {

		        adjustChartSize: true,

		            layout: 'vertical',

		            align: 'right',

		            verticalAlign: 'top',

		            y: 30,

		            navigation: {

		            	enabled: true,

		                activeColor: '#3E576F',

		                animation: true,

		                arrowSize: 12,

		                inactiveColor: '#CCC',

		                style: {

		                    fontWeight: 'bold',

		                    color: '#333',

		                    fontSize: '12px'

		                }

		            }

		    },

		    exporting: {

	        	chartOptions: {

	            	chart: {

	                	height: 3000,

	                	width: 3000

	                },

	            	legend: {

	                	navigation: {

	                    	enabled: false

	                    }

	                }

	            }

	        },

		    series: [

		    <?php

        	foreach ($PLANTACIONES as $plantacion) {

        		if ($plantacion['flag_watercress'] == 0)

        		{

	        		echo '{ '.

		        			'name: "'.utf8_encode($plantacion['anio_plantacion'].'-'.$plantacion['num_plantacion'].'-'.$plantacion['codigo_temporada'].'-'.$plantacion['zonas'].'-'.$plantacion['bloques']).'",'.

		        			'data: ['.$plantacion['insectos'].','.$plantacion['enfermedades'].','.$plantacion['hierbas'].','.$plantacion['otros_danos'].'],'.

		        			'tooltip: {'.

		        				'pointFormatter: function() {'.

		        					'var point = this;'.

		        					'return \'<span style="color:\' + point.color + \'">\u25CF</span>  Insects : <br>'.

					        			'Worms: '.$plantacion['gusanos'].'<br>'.

					        			'Eggs: '.$plantacion['huevos'].'<br>'.

					        			'Leaf Hoppers: '.$plantacion['saltahojas'].'<br>'.

					        			'Aphids: '.$plantacion['afidos'].'<br>'.

					        			'Stink Bugs: '.$plantacion['chinches'].'<br>'.

					        			'Gnats: '.$plantacion['moscos'].'<br>'.

					        			'Flea Beetles: '.$plantacion['escarabajos'].'<br>'.

					        			'Cyclaman Mites: '.$plantacion['acaros'].'<br>'.

					        			'<span style="color:\' + point.color + \'">\u25CF</span> Diseases : <br>'.

					        			'Cercospora Leaf Spot (Blight): '.$plantacion['cercospora_leaf_spot'].'<br>'.

					        			'Pythium/Damp Off: '.$plantacion['pythium'].'<br>'.

					        			'Rhizoctonia Aerial Blight: '.$plantacion['rhizoctonia'].'<br>'.

					        			'Bacteria: '.$plantacion['bacteria'].'<br>'.

					        			'Sclerotinia: '.$plantacion['sclerotinia'].'<br>'.

					        			'Alternaria Specks: '.$plantacion['alternaria_specks'].'<br>'.

					        			'Mildew: '.$plantacion['mildew'].'<br>'.

					        			'Virus: '.$plantacion['virus'].'<br>'.

					        			'<span style="color:\' + point.color + \'">\u25CF</span> Weeds : <br>'.

					        			'Dollarweed: '.$plantacion['dolar'].'<br>'.

					        			'Frogs Bit: '.$plantacion['frogs_bit'].'<br>'.

					        			'Mud Plantain: '.$plantacion['plantas_lodo'].'<br>'.

					        			'Tube Weed: '.$plantacion['tripa_pollo'].'<br>'.

					        			'Grass: '.$plantacion['zacate'].'<br>'.

					        			'<span style="color:\' + point.color + \'">\u25CF</span> Other Damages : <br>'.

					        			'Damaged Leaves: '.$plantacion['hojas_danadas'].'<br>'.

					        			'Purple Stem: '.$plantacion['tallos_purpuras'].'<br>'.

					        			'Watercress rooted: '.$plantacion['berro_enraizado'].'\';'.

		        				'}'.

		        			'},'.

		        			'visible: false'.

	        			'},';

        		}

        		else

        		{

	        		echo '{ '.

		        			'name: "'.utf8_encode($plantacion['anio_plantacion'].'-'.$plantacion['num_plantacion'].'-'.$plantacion['codigo_temporada'].'-'.$plantacion['zonas'].'-'.$plantacion['bloques']).'",'.

		        			'data: ['.$plantacion['insectos'].','.$plantacion['enfermedades'].','.$plantacion['hierbas'].','.$plantacion['otros_danos'].'],'.

		        			'tooltip: {'.

		        				'pointFormatter: function() {'.

		        					'var point = this;'.

		        					'return \'<span style="color:\' + point.color + \'">\u25CF</span>  Insects : <br>'.

					        			'Worms: '.$plantacion['gusanos'].'<br>'.

					        			'Eggs: '.$plantacion['huevos'].'<br>'.

					        			'Leaf Hoppers: '.$plantacion['saltahojas'].'<br>'.

					        			'Aphids: '.$plantacion['afidos'].'<br>'.

					        			'Stink Bugs: '.$plantacion['chinches'].'<br>'.

					        			'Gnats: '.$plantacion['moscos'].'<br>'.

					        			'Flea Beetles: '.$plantacion['escarabajos'].'<br>'.

					        			'Cyclaman Mites: '.$plantacion['acaros'].'<br>'.

					        			'<span style="color:\' + point.color + \'">\u25CF</span> Diseases : <br>'.

					        			'Cercospora Leaf Spot (Blight): '.$plantacion['cercospora_leaf_spot'].'<br>'.

					        			'Pythium/Damp Off: '.$plantacion['pythium'].'<br>'.

					        			'Rhizoctonia Aerial Blight: '.$plantacion['rhizoctonia'].'<br>'.

					        			'Bacteria: '.$plantacion['bacteria'].'<br>'.

					        			'Sclerotinia: '.$plantacion['sclerotinia'].'<br>'.

					        			'Alternaria Specks: '.$plantacion['alternaria_specks'].'<br>'.

					        			'Mildew: '.$plantacion['mildew'].'<br>'.

					        			'Virus: '.$plantacion['virus'].'<br>'.

					        			'<span style="color:\' + point.color + \'">\u25CF</span> Weeds : <br>'.

					        			'Dollarweed: '.$plantacion['dolar'].'<br>'.

					        			'Frogs Bit: '.$plantacion['frogs_bit'].'<br>'.

					        			'Mud Plantain: '.$plantacion['plantas_lodo'].'<br>'.

					        			'Tube Weed: '.$plantacion['tripa_pollo'].'<br>'.

					        			'Grass: '.$plantacion['zacate'].'<br>'.

					        			'<span style="color:\' + point.color + \'">\u25CF</span> Other Damages : <br>'.

					        			'Damaged Leaves: '.$plantacion['hojas_danadas'].'<br>'.

					        			'Purple Stem: '.$plantacion['tallos_purpuras'].'<br>'.

					        			'Watercress rooted: '.$plantacion['berro_enraizado'].'\';'.

		        				'}'.

		        			'},'.

		        			'visible: false'.

	        			'},';

	        	}

        	}

        	?>

		    ]

		});

		<?php

	}*/

	?>
</script>