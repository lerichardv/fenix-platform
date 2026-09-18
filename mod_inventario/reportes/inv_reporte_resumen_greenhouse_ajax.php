<?php
/*
* Genera el ajax del Resumen de Seeds y Proyeccciones por Greenhouse
* 	@author 	Dan Urquía
* 	@date 		2023-12-18
*/

session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_reportes_inventario.php");
$fecha_inicial    	= $_POST['x1'];
$fecha_final 	    = $_POST['x2'];
$cod_info_empresa 	= $_POST['x3'];

/*INSTANCIAMIENTOS*/
$DB_REPORTE = new db_rep_inv();
$SOWS = $DB_REPORTE->get_resumen_greenhouse($fecha_inicial, $fecha_final, $cod_info_empresa);

if ($SOWS[0]['GREENHOUSE'] == ''){
	$greenshouse = '';
} else {
	$greenshouse = $SOWS[0]['GREENHOUSE'] . "'s ";
}
?>
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
            var url = "../../mod_inventario/reportes/inv_reporte_resumen_greenhouse_excel.php?x1=<?php echo $fecha_inicial; ?>&x2=<?php echo $fecha_final; ?>&x3=<?php echo $cod_info_empresa; ?>";
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
                <h3 class="panel-title"><?PHP echo $greenshouse; ?>Seed Shortage-Surplus List</h3>
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
                          <th width="20%">Seed</th>
	                      <th width="5%">Sows</th>
	                      <th width="10%">Frequency</th>
	                      <th width="10%">Expected Plants</th>
	                      <th width="10%">Total Seed Needed</th>
	                      <th width="10%">Inventory</th>
	                      <th width="10%">Shortage-Surplus</th>
	                    </tr>
	                </thead>
					<tbody>
						<?PHP
							if(count($SOWS) > 0){

								$correlativo = 1;
								foreach ($SOWS as $SOW){

									$cuerpo_tabla .= '<tr>
                                          <td>'.utf8_encode($SOW['SEED']).'</td>
                                          <td>'.utf8_encode($SOW['SOWS']).'</td>
                                          <td>'.utf8_encode($SOW['FREQUENCY']).'</td>
                                          <td>'.utf8_encode($SOW['EXPECTED_PLANTS']).'</td>
                                          <td>'.utf8_encode($SOW['TOTAL_SEED_NEEDED']).'</td>
                                          <td>'.utf8_encode($SOW['INVENTARIO']).'</td>
                                          <td>'.utf8_encode($SOW['SHORTAGESURPLUS']).'</td>
                                    </tr>';

									$correlativo++;
								}
							} 
							else
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
