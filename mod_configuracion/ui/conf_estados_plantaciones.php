<?php 
/*
* 	Registro de información de los formularios
* 	@author 		Jairo Bonilla
* 	@date 			2015-07-28
*/
session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php"); 
include_once("../../libs/db_classes/db_plantaciones.php"); 

/*INSTANCIAMIENTOS*/
$DB_PLANT = new db_plantaciones();
$ESTADOS       = $DB_PLANT->bw_listado_estados_plantaciones();
$PLANTA       = $DB_PLANT->bw_listado_plantaciones();

$cod_estado = $_POST['cod_estado'];
if (!isset($_POST['cod_estado'])) {
	$cod_estado = 0;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Nueva capacitación</title>
</head>
<style type="text/css">
</style>

<script type="text/javascript">
	jQuery.ajaxSetup({async:false});
	grl_overlay_loading('');
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
								var no_results = $('<tr class="filterTable_no_results"><td colspan="4">No hay resultados.</td></tr>')
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
	});
	$(document).ready(function() {
	    $('#modal_loading').modal('hide');
	    jQuery.ajaxSetup({async:true});
		
	});
</script>

<body>
	<div id="overlay_loading"></div>
	<div id="message_box"></div>
	<div class="busqueda_contenedor">
	    <div class="row" style="min-width:100px;">
	        <div class="col-sm-12">
	                <div class="panel panel-primary" style="overflow:auto;">
	                <div class="panel-heading panel_cabecera"> 
	                        <h3 class="panel-title translate" data-traducir_english="Stages List" data-traducir_spanish="Lista de Estados">Listado de Estados</h3>
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
	                    <table class="table table-condensed display" id="dev-table" >	                        
	                        <thead>
	                                <tr class="active info">
	                                        <th width="5%" class="translate" data-traducir_english="No" data-traducir_spanish="Nro.">Nro.</th>
	                                        <th width="35%" class="translate" data-traducir_english="Stage" data-traducir_spanish="Estado">Estado</th>
	                                        <th width="15%" class="translate" data-traducir_english="Time" data-traducir_spanish="Tiempo">Tiempo</th>
	                                </tr>
	                        </thead>
	                        <tbody id="cuerpo_tabla">
	                            <?php
	                            if(count($ESTADOS))
	                            {
	                            	foreach ($ESTADOS as $estado) {
	                            		?>
	                            		<tr class="estado<?php echo $estado['cod_estado_plantacion']; ?>">
	                            			<td><?php echo $estado['cod_estado_plantacion']; ?></td>
	                            			<td><?php echo utf8_encode($estado['estado_plantacion'].' - '.$estado['estado_plantacion_english']); ?></td>
	                            			<!-- <td><?php echo utf8_encode($estado['estado_padre']); ?></td> -->
	                            			<td><?php echo $estado['tiempo_espera']; ?></td>
	                            			<!-- <?php
	                            			if ($PLANTA[0]['cod_estado'] == $estado['cod_estado_plantacion']) {
	                            				?>
	                            				<td>
	                            					<button id="btn_ok" class="btn btn-sm btn-primary"><i class="fa fa-check"></i></button>	                            					
	                            					<button id="btn_cancel" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></button>
	                            				</td>
	                            				<?php	                            				
	                            			}
	                            			else
	                            			{
	                            				?>
	                            				<td></td>
	                            				<?php
	                            			}
	                            			?>	 -->                            			
	                            		</tr>
	                            		<?php
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