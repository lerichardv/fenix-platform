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
include_once("../../libs/db_classes/db_usuario.php");
$fecha_inicial 	= $_POST['x1'].'-01';
$fecha_final 	= $_POST['x2'].'-31';
/*INSTANCIAMIENTOS*/
$DB_USUARIO = new db_usuario();
$TENDENCIAS = $DB_USUARIO->get_ingresos_tendencia($fecha_inicial, $fecha_final);
$titulo = "Gráfico de tendencia de ingresos al sistema del " . $_POST['x1'] . ' a ' . $_POST['x2'];
$cuerpo = "";
if(count($TENDENCIAS) > 0){
        foreach ($TENDENCIAS as $TENDENCIA) {
                $cuerpo .= "{
                                name: '".utf8_encode($TENDENCIA['mes']).' | '.utf8_encode($TENDENCIA['ingresos']) ."',
                                y: ".utf8_encode($TENDENCIA['Porcentaje']).",
                                drilldown: 'null'
                            },";
        }
}
rtrim($cuerpo, ",");
?>
<script type="text/javascript">
$(document).ready(function() {
$(function () {
    // Create the chart
    $('#container1').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: '<?PHP echo $titulo ?>'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                text: 'Porcentaje total'
            }

        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.2f}%'
                }
            }
        },

        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b><br/>'
        },

        series: [{
            name: 'Ingresos',
            colorByPoint: true,
            data: [<?PHP echo $cuerpo; ?>]
        }]
    });
});

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
            var url = "../../mod_admin_usuarios/reportes/usu_reporte_general_ingresos_excel.php?x1=<?php echo $fecha_inicial; ?>&x2=<?php echo $fecha_final; ?>";
            $(location).attr('href',url);
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
    <div class="col-md-12"><div id="container1"></div>  </div>
</div>
    <div class="row" style="overflow:auto; min-width:100px;">
			<div class="col-md-12">
				<div class="panel panel-primary" style="overflow:auto;">
            <div class="panel-heading panel_cabecera">
                <h3 class="panel-title">Listado General de Ingresos al Sistema Mensual</h3>
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
							<table class="table table-condensed display" id="dev-table">
								<thead>
	                	<tr class="active info">
                          <th width="10%">Nro.</th>
	                        <th width="30%">Usuario</th>
                          <th width="30%">Mes</th>
                          <th width="30%">Ingresos</th>
	                    </tr>
	                </thead>
					<tbody>
						<?php
            $TENDENCIAS = $DB_USUARIO->get_ingresos_general($fecha_inicial, $fecha_final);
							if(count($TENDENCIAS) > 0)
							{
								$correlativo = 1;
								foreach ($TENDENCIAS as $TENDENCIA)
								{
									$cuerpo_tabla .= '<tr>
                                          <td>'.$correlativo.'</td>
                                          <td>'.utf8_encode($TENDENCIA['nombre']).'</td>
                                          <td>'.utf8_encode($TENDENCIA['mes']).'</td>
                                          <td>'.utf8_encode($TENDENCIA['ingresos']).'</td>
                                    </tr>';
									$correlativo++;
								}
							}
							else
								$cuerpo_tabla .= '<tr><td colspan="4" align="center">No hay registros</td></tr>';
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
