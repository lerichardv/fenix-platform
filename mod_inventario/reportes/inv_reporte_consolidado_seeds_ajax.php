<?php
/*
* 	Genera el listado del porcentaje de deducciones segun fechas indicadas
* 	@author 	Dan Urquía
* 	@date 		2016-08-18
*/

ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
try {

	session_start();
	if (!isset($_SESSION['cod_usuario'])) {
		header('Location: index.php');
	}
	/*CONEXION CON BASE DE DATOS*/
	include_once("../../libs/db_classes/db_mysql_conn.php");
	include_once("../../libs/db_classes/db_reportes_inventario.php");

	$fecha_inicial 	  = $_POST['x1'];
	$fecha_final 	  = $_POST['x2'];
	$cod_greenhouse	  = implode(',', $_POST['x3']);
	$cod_proveedor 	  = implode(',', $_POST['x4']);
	$cod_tipo_semilla = implode(',', $_POST['x5']);

	/*INSTANCIAMIENTOS*/
	$DB_REPORTE = new db_rep_inv();

	$SOWS = $DB_REPORTE->get_resumen_semillas(
		$fecha_inicial,
		$fecha_final,
		$cod_greenhouse,
		$cod_proveedor,
		$cod_tipo_semilla
	);


	$U = 0;
	$O = 0;
	$UN = 0;
	$OR = 0;
	$TOT = 0;
	$titulo = "Untreated-Organic Seed Percentage Chart from " . $_POST['x1'] . ' to ' . $_POST['x2'];
	if (count($SOWS) > 0) {
		$correlativo = 1;
		foreach ($SOWS as $CHART) {

			$TOT = $TOT + $CHART['CANTIDAD'];

			if ($CHART['COD_TIPO_SEMILLA'] == 1) {
				$O = $O + $CHART['CANTIDAD'];
			}

			if ($CHART['COD_TIPO_SEMILLA'] == 2) {
				$U = $U + $CHART['CANTIDAD'];
			}
		}

		$UN = round(($U / $TOT) * 100);
		$OR = round(($O / $TOT) * 100);
	}
	//code...
} catch (\Throwable $th) {
	//throw $th;
	echo $th;
	die;
}
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
		});


		//CHARTS
		$(function() {
			$('#container').highcharts({
				chart: {
					type: 'column'
				},
				title: {
					text: '<?PHP echo $titulo ?>',
					fontSize: '25px'
				},
				subtitle: {
					text: ''
				},
				xAxis: {
					labels: {
						style: {
							fontSize: '15px'
						}
					},
					categories: [
						'Seeds Percentage'
					],
					crosshair: true
				},
				yAxis: {
					min: 0,
					title: {
						text: 'Percentage (%)',
						style: {
							fontSize: '15px'
						}
					}
				},
				tooltip: {
					headerFormat: '<span style="font-size:15px">{point.key}</span><table>',
					pointFormat: '<tr>' +
						'<td style="color:{series.color};padding:0;font-size:15px">{series.name}:  </td>' +
						'<td style="padding:0;font-size:15px"><b>{point.y:.0f} %</b></td>' +
						'</tr>',
					footerFormat: '</table>',
					shared: true,
					useHTML: true
				},
				plotOptions: {
					column: {
						pointPadding: 0.2,
						borderWidth: 0
					}
				},
				series: [{
					name: 'Organic',
					data: [<?PHP echo $OR; ?>],
					color: "#87d56d"

				}, {
					name: 'Untreated',
					data: [<?PHP echo $UN; ?>],
					color: "#dbcd5e"

				}]
			});
		});
	});

	$("#excel").click(function() {
		var url = "../../mod_inventario/reportes/inv_reporte_consolidado_seeds_excel.php?x1=<?php echo $fecha_inicial; ?>&x2=<?php echo $fecha_final; ?>&x3=<?php echo $cod_greenhouse; ?>&x4=<?php echo $cod_proveedor; ?>&x5=<?php echo $cod_tipo_semilla; ?>";
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
		<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
	</div>
</div>
<div class="row" style="overflow:auto; min-width:100px;">

	<div class="col-md-12">
		<div class="panel panel-primary" style="overflow:auto;">
			<div class="panel-heading panel_cabecera">
				<h3 class="panel-title">Untreated-Organic Seed List</h3>
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
							<th width="15%">Greenhouse</th>
							<th width="15%">Vendor</th>
							<th width="25%">Seed</th>
							<th width="30%">OG Supply</th>
							<th width="10%">Quantity</th>
							<th width="10%">Type</th>
						</tr>
					</thead>
					<tbody>
						<?php

						if (count($SOWS) > 0) {
							$correlativo = 1;
							foreach ($SOWS as $SOW) {

								$COLOR  = $SOW['COD_TIPO_SEMILLA'] == 1 ? 'class="success"' : 'class="warning"';
								$cuerpo_tabla .= '<tr ' . $COLOR . '>
                                          <td>' . utf8_encode($SOW['GREENHOUSE']) . '</td>
                                          <td>' . utf8_encode($SOW['VENDOR']) . '</td>
                                          <td>' . utf8_encode($SOW['SEED']) . '</td>
                                          <td>' . utf8_encode($SOW['OGSUPPLY']) . '</td>
                                          <td>' . utf8_encode($SOW['CANTIDAD']) . '</td>
                                          <td>' . utf8_encode($SOW['TIPO_SEED']) . '</td>
                                    </tr>';
								$correlativo++;
							}
						} else
							$cuerpo_tabla .= '<tr><td colspan="6" align="center">No data</td></tr>';
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