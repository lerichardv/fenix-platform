<?php
/*
* 	Genera el listado del porcentaje de deducciones segun fechas indicadas
* 	@author 	Dan Urquía
* 	@date 		2016-08-18
*/
// ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
// error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
session_start();
if (!isset($_SESSION['cod_usuario'])) {
	header('Location: index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_reportes_inventario.php");
$fecha_inicial 	= $_POST['x1'];
$fecha_final 	= $_POST['x2'];
$estados     	= implode(',', $_POST['x3']);

/*INSTANCIAMIENTOS*/
$DB_REPORTE = new db_rep_inv();
?>

<script type="text/javascript">
	$(document).ready(function() {
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
	});

	$("#excel").click(function() {
		var url = "../../mod_inventario/reportes/inv_reporte_sow_excel.php?x1=<?php echo $fecha_inicial; ?>&x2=<?php echo $fecha_final; ?>&x3=<?php echo $estados; ?>";
		$(location).attr('href', url);
	});
</script>


<style>
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
</style>

<div class="row" style="overflow:auto; min-width:100px;">
	<div class="col-md-12">
		<div id="container1"></div>
	</div>
</div>
<div class="row" style="overflow:auto; min-width:100px;">
	<div class="col-md-12">
		<div class="panel panel-primary" style="overflow:auto;">
			<div class="panel-heading panel_cabecera">
				<h3 class="panel-title">List of Sows and Transplants</h3>
				<div class="pull-right">
					<span class="clickable filter" data-toggle="tooltip" title="Search" data-container="body">
						<i class="fa fa-search"></i>
					</span>
				</div>
			</div>

			<div class="panel-body panel_cuerpo input-group-sm">
				<input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Search" />
			</div>
			<div style="overflow-x:auto;">
				<table class="table table-condensed display" id="dev-table">
					<thead>
						<tr class="active info">
							<th width="2%">Nro.</th>
							<th width="10%">Order Number</th>
							<th width="2%">Item</th>
							<th width="8%">Sow Group</th>
							<th width="15%">Seed</th>
							<th width="7%">Greenhouse</th>
							<th width="2%">State</th>
							<th width="7%">Plants Ordered</th>
							<th width="4%">Overseed</th>
							<th width="8%">Expected</th>
							<th width="8%">Expected Sow</th>
							<th width="8%">Expected Delivery</th>
							<th width="7%">Transplant Ticket</th>
							<th width="7%">Location</th>
							<th width="7%">Transplant delivery</th>
							<th width="4%">Germ</th>
							<th width="7%">Delivered Plants</th>
							<th width="7%">Balance</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$SOWS = $DB_REPORTE->get_siembras_transplantes($fecha_inicial, $fecha_final, $estados);
						if (count($SOWS) > 0) {
							$correlativo = 1;
							$j              = count($SOWS);
							// $numero_orden   = $SOWS[0]['numero_orden'];
							// $cod_seed       = $SOWS[0]['COD_SEED'];
							// $item           = $SOWS[0]['item'];
							// $actual_plants  = $SOWS[0]['cantidad_plantas'];
							// $exp_plants     = 0; //$SOWS[0]['EXP_PLANTS'];
							// $exp_delivery   = $SOWS[0]['EXP_DELIVERY'];
							// $last_delivery  = $SOWS[0]['LAST_DELIVERY'];
							// $comple         = $SOWS[0]['COMP'];
							// $correlativo    = 0;
							// $clases			= array();
							foreach ($SOWS as $SOW) {

								$ticket = ($SOW['ticket_tras'] == NULL) ? '-' : utf8_encode($SOW['ticket_tras']);
								$location = ($SOW['location'] == NULL) ? '-' : utf8_encode($SOW['location']);
								$fecha_entrega_trasplante = ($SOW['fecha_entrega_trasplante'] == NULL) ? '-' : utf8_encode($SOW['fecha_entrega_trasplante']);
								$germ = ($SOW['germ'] == NULL) ? '-' : utf8_encode($SOW['germ']) . '%';
								$cantidad_plantas = ($SOW['cantidad_plantas_recibidas_sumadas'] == NULL) ? '-' : utf8_encode($SOW['cantidad_plantas_recibidas_sumadas']); //Cantidad de plantas registradas en el Trasplante

								// $datos_cantidades =	$DB_REPORTE->get_cantidad_plantacion($ticket, $SOW['cod_plantacion']);

								// if (isset($datos_cantidades[0]['total_plantas'])) {
								// 	if ($datos_cantidades[0]['cantidad_plantaciones'] > 1) {
								// 		$datos_suma_cantidades_plantas_por_plantacion =	$DB_REPORTE->get_cantidad_sumada_semillas_plantacion($SOW['cod_plantacion']);
								// 		$cantidad_plantas = $datos_suma_cantidades_plantas_por_plantacion[0]['cantidad_plantas'];
								// 	} else {
								// 		if($ticket=='58570'){
								// 			$cantidad_plantas = $ticket;

								// 		}else{
								// 			$cantidad_plantas = $datos_cantidades[0]['total_plantas'];

								// 		}
								// 	}
								// }
								if ($SOW['cantidad_2'] == NULL) {
									// Caso: no se tiene un trasplante
									$cantidad    = $SOW['cantidad']; //Cantidad ordenada en las plantación
									$exp_plants  = $SOW['exp_plants'];
									$balance     = ($SOW['balance'] == NULL) ? '-' : utf8_encode($SOW['balance']);
								} else {
									$cantidad    = $SOW['cantidad'];
									// $exp_plants  = $SOW['exp_plants_2'];
									$exp_plants  = $SOW['exp_plants'];
									$balance     = ($SOW['balance_2'] == NULL) ? '-' : utf8_encode($SOW['balance_2']);
								}

								// $cantidad_plantas = $cantidad_plantas;
								$cantidad_plantas_sin_formato = str_replace(",", "", $cantidad_plantas);
								$exp_plants_sin_formato = str_replace(",", "", $exp_plants);


								if ($cantidad_plantas_sin_formato == '-') {
									$balance     = '-';
									$germ = '-';
								} else {
									$balance     = $cantidad_plantas_sin_formato - $exp_plants_sin_formato;
									$balance     = number_format($balance);
									$germ = ($cantidad_plantas_sin_formato / $exp_plants_sin_formato)  * 100;
									$germ = round($germ, 2) . '%';
								}
							//	$cantidad_plantas = floatval($cantidad_plantas);
								$cuerpo_tabla .= '<tr>
                                          <td>' . $correlativo . '</td>
                                          <td>' . utf8_encode($SOW['numero_orden']) . '</td>
                                          <td>' . utf8_encode($SOW['item']) . '</td>
                                          <td>' . utf8_encode($SOW['sow_group']) . '</td>
                                          <td>' . utf8_encode($SOW['nombre_semilla']) . '</td>
                                          <td>' . utf8_encode($SOW['nombre_empresa']) . '</td>
                                          <td>' . utf8_encode($SOW['estado']) . '</td>
                                          <td>' . $cantidad . '</td>
                                          <td>' . utf8_encode($SOW['overseed']) . '%</td>
                                          <td>' . $exp_plants . '</td>
                                          <td>' . utf8_encode($SOW['expected_sow']) . '</td>
                                          <td>' . utf8_encode($SOW['expected_delivery']) . '</td>
                                          <td>' . $ticket . '</td>
                                          <td>' . $location . '</td>
                                          <td>' . $fecha_entrega_trasplante . '</td>
                                          <td>' . $germ . '</td>
                                          <td>' .  $cantidad_plantas . '</td>
                                          <td>' . $balance . '</td>
                                    </tr>';
								$correlativo++;
							}
						} else
							$cuerpo_tabla .= '<tr><td colspan="17" align="center">No data</td></tr>';
						echo $cuerpo_tabla;
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-5"></div>
	<div class="col-md-5"></div>
	<div class="col-md-2" style="text-align: right"><a href="#" id="excel" class="btn btn-sm btn-success" role="button">Excel</a></div>
</div>
<div class="row">
	<div class="col-md-12">&nbsp;</div>
</div>