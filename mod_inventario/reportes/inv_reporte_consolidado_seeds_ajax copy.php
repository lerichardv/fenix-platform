<?php
/*
* 	Genera el listado del porcentaje de deducciones segun fechas indicadas
* 	@author 	Dan Urquía
* 	@date 		2016-08-18
*/

session_start();
if(!isset($_SESSION['cod_usuario'])){
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
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
			/*
			 * Función que realiza la "busqueda" dentro de la tabla con información
			 */
			(function(){
				'use strict';
				var $ = jQuery;
				$.fn.extend({
					filterTable: function(){
						return this.each(function(){
							$(this).on('keyup', function(e){
								$('.filterTable_no_results').remove();
								var $this = $(this), search = $this.val().toLowerCase(), target = $this.attr('data-filters'), $target = $(target), $rows = $target.find('tbody tr');
								if(search == '') {
									$rows.show();
								} else {
									$rows.each(function(){
										var $this = $(this);
										$this.text().toLowerCase().indexOf(search) === -1 ? $this.hide() : $this.show();
									})
									if($target.find('tbody tr:visible').size() === 0) {
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

			$(function(){
				// attach table filter plugin to inputs
				$('[data-action="filter"]').filterTable();

				$('.busqueda_contenedor').on('click', '.panel_cabecera span.filter', function(e){
					var $this = $(this),
							$panel = $this.parents('.panel');

					$panel.find('.panel_cuerpo').slideToggle();
					if($this.css('display') != 'none') {
						$panel.find('.panel_cuerpo input').focus();
					}
				});
				$('[data-toggle="tooltip"]').tooltip();
			})
	});

        $( "#excel" ).click(function() {
            var url = "../../mod_inventario/reportes/inv_reporte_consolidado_seeds_excel.php?x1=<?php echo $fecha_inicial; ?>&x2=<?php echo $fecha_final; ?>&x3=<?php echo $cod_greenhouse; ?>&x4=<?php echo $cod_proveedor; ?>&x5=<?php echo $cod_tipo_semilla; ?>";
            $(location).attr('href',url);
        });

				//CHARTS
	$(function () {
		$('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Monthly Average Rainfall'
        },
        subtitle: {
            text: 'Source: WorldClimate.com'
        },
        xAxis: {
            categories: [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'May',
                'Jun',
                'Jul',
                'Aug',
                'Sep',
                'Oct',
                'Nov',
                'Dec'
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Rainfall (mm)'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
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
            name: 'Tokyo',
            data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]

        }, {
            name: 'New York',
            data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3]

        }, {
            name: 'London',
            data: [48.9, 38.8, 39.3, 41.4, 47.0, 48.3, 59.0, 59.6, 52.4, 65.2, 59.3, 51.2]

        }, {
            name: 'Berlin',
            data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1, 46.8, 51.1]

        }]
    });

</script>


<style>
	.row{
		padding: 0 10px;
	}
	.panel_cabecera div {
		margin-top: -18px;
		font-size: 15px;
	}
	.panel_cabecera div span{
		margin-left:5px;
	}
	.panel_cuerpo{
		display: none;
	}
	#dev-table {
		font-size: 12px;
	}
</style>

<div class="row" style="overflow:auto; min-width:100px;">
    <div class="col-md-12"><div id="container"></div>  </div>
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
	                      <th width="20%">Greenhouse</th>
	                      <th width="20%">Vendor</th>
	                      <th width="40%">Seed</th>
	                      <th width="10%">Quantity</th>
	                      <th width="10%">Type</th>
	                    </tr>
	                </thead>
					<tbody>
						<?php
            $SOWS = $DB_REPORTE->get_resumen_semillas($fecha_inicial, 
													  $fecha_final, 
													  $cod_greenhouse, 
													  $cod_proveedor, 
													  $cod_tipo_semilla);

							if(count($SOWS) > 0)
							{
								$correlativo = 1;
								foreach ($SOWS as $SOW)
								{
									
									$COLOR  = $SOW['COD_TIPO_SEMILLA'] == 1 ? 'class="success"' : 'class="warning"';
									$cuerpo_tabla .= '<tr ' . $COLOR . '>
                                          <td>'.utf8_encode($SOW['GREENHOUSE']).'</td>
                                          <td>'.utf8_encode($SOW['VENDOR']).'</td>
                                          <td>'.utf8_encode($SOW['SEED']).'</td>
                                          <td>'.utf8_encode($SOW['CANTIDAD']).'</td>
                                          <td>'.utf8_encode($SOW['TIPO_SEED']).'</td>
                                    </tr>';
									$correlativo++;
								}
							} 
							else
								$cuerpo_tabla .= '<tr><td colspan="5" align="center">No data</td></tr>';
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
