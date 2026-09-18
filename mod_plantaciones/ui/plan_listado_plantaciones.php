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
$DB_PLANT 	= new db_plantaciones();

$cod_granjas_usuario = str_replace(['[','"',']'], '', $_SESSION['cod_info_empresa']);

$inicio = $_POST['inicio'];
if (!isset($_POST['inicio'])) {
	$inicio = 0;
}
$limite = $_POST['limite'];
if (!isset($_POST['limite'])) {
	$limite = 50;
}

//$PLANTACIONES = $DB_PLANT->plan_listado_plantaciones();
$PLANTACIONES = $DB_PLANT->plan_listado_plantaciones_por_granjas($cod_granjas_usuario, "1,2,3,4,5,6,7,8");
//$PLANTACIONES = $DB_PLANT->plan_listado_plantaciones_por_granjas_paginacion($cod_granjas_usuario,$inicio,$limite);
//$TOTAL = $DB_PLANT->plan_total_plantaciones_por_granjas($cod_granjas_usuario);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Nueva capacitación</title>
</head>
<style type="text/css">
	.row-with-attachment{
			background-color: rgb(42, 160, 148);
			border-color: rgb(23, 121, 111);
			background-color: rgb(255, 255, 255) !important;
			border-color: rgb(35, 35, 35) !important;
	}
	.container-upload-box{
			width: 100%;display: table;
			border-collapse: separate;
			background-color: rgb(255, 255, 255);
			padding-bottom: 15px;
	}
	.btn-select-file{
			display: table-cell;
			width: 100%;
			height: 30px;
			border-top-right-radius: 0px;
			border-bottom-right-radius: 0px;
			background: rgb(37, 105, 162);
			border: 1px solid rgb(24, 90, 146);
			color: rgb(255,255,255);
	}
	.btn-select-file.ready-to-upload{
			background: rgb(140, 197, 245);
			border: 1px solid rgb(24, 90, 146);
			color: rgb(14, 68, 113);
	}
	.btn-select-file.with-attachment{
			background: rgb(20,128,140);
			border: 1px solid rgb(24,90,146);
			border: 1px solid rgb(31, 149, 162);
			color: rgb(255,255,255);
	}
	.btn-select-file.disabled{
			background: rgb(212, 212, 212);
			border: 1px solid rgb(161, 161, 161);
			color: rgb(100,100,100);
			cursor: not-allowed;
	}
	.btn-delete-file{
			display: table-cell;
			width: 30px;
			background: rgb(37, 105, 162);
			color: rgb(24, 90, 146);
			border: 1px solid rgb(24, 90, 146);
			border-left: none;
			text-align: center;
			font-size: 16px;
			padding-left: 4px;
			border-top-right-radius: 4px;
			border-bottom-right-radius: 4px;
			vertical-align: middle;
	}
	.btn-delete-file:hover,
	.btn-delete-file:focus{
			background: rgb(37, 105, 162);
			color: rgb(24, 90, 146);
			border-color: rgb(24, 90, 146);
			cursor: not-allowed;
	}
	.btn-delete-file.ready-to-upload{
			background: rgb(37, 105, 162);
			border-color: rgb(24, 90, 146);
			color: rgb(255,255,255);
	}
	.btn-delete-file:hover.ready-to-upload,
	.btn-delete-file:focus.ready-to-upload{
			background-color: rgb(21, 79, 129);
			cursor: pointer;
	}

	.btn-delete-file.with-attachment{
			background: rgb(20,128,140);
			border-color: rgb(24,90,146);
			border-color: rgb(31, 149, 162);
			color: rgb(255,255,255);
	}
	.btn-delete-file:hover.with-attachment,
	.btn-delete-file:focus.with-attachment{
			background-color: rgb(16, 101, 110);
			cursor: pointer;
	}

	.btn-delete-file.disabled{
			background: rgb(212, 212, 212);
			color: rgb(100,100,100);
			border-color: rgb(161, 161, 161);
	}
	.btn-delete-file:hover.disabled,
	.btn-delete-file:focus.disabled{
			background: rgb(212, 212, 212);
			color: rgb(100,100,100);
			border-color: rgb(161, 161, 161);
			cursor: not-allowed;
	}

	.btn-view-file{
			display: table-cell;
			width: 30px;
			border-left: none;
			text-align: center;
			font-size: 16px;
			padding-left: 4px;
			border-top-right-radius: 4px;
			border-bottom-right-radius: 4px;
			vertical-align: middle;
			background: rgb(20,128,140);
			border-color: rgb(24,90,146);
			border-color: rgb(31, 149, 162);
			color: rgb(255,255,255);
	}
	.btn-view-file:hover,
	.btn-view-file:focus{
			background-color: rgb(16, 101, 110);
			color: rgb(255,255,255);
			cursor: pointer;
	}

	.input-file-hidden{
			visibility: hidden;
			position: absolute;
			left: -99999px;
	}
	/*--------------------------------------------------------------------*/
	.btn-fileupload-incrustated{
			width: 80%;
	}
	.btn-img{
			position: absolute;
			bottom: 15px;
			width: 10%;
			height: 30px;
			right: 15px;
			z-index: 1;
			padding: 5px;
	}
	@media (max-width: 991px){
			.btn-img{
					bottom: 0px;
			}
	}
	.btn-upload{
			position: absolute;
			bottom: 15px;
			width: 10%;
			height: 30px;
			right: 45px;
			z-index: 1;
			padding: 5px;
	}
	@media (max-width: 991px){
			.btn-upload{
					bottom: 0px;
			}
	}
	.container-upload-box{
			width: 100%;display: table;
			border-collapse: separate;
	}
	.btn-select-file{
			display: table-cell;
			width: 100%;
			height: 30px;
			border-top-right-radius: 0px;
			border-bottom-right-radius: 0px;
			background: rgb(37, 105, 162);
			border: 1px solid rgb(24, 90, 146);
			color: rgb(255,255,255);
	}
	.btn-select-file.ready-to-upload{
			background: rgb(140, 197, 245);
			border: 1px solid rgb(24, 90, 146);
			color: rgb(14, 68, 113);
	}
	.btn-select-file.with-attachment{
			background: rgb(20,128,140);
			border: 1px solid rgb(24,90,146);
			border: 1px solid rgb(31, 149, 162);
			color: rgb(255,255,255);
	}
	.btn-select-file.disabled{
			background: rgb(212, 212, 212);
			border: 1px solid rgb(161, 161, 161);
			color: rgb(100,100,100);
			cursor: not-allowed;
	}
	.btn-upload-file{
			display: table-cell;
			width: 30px;
			background: rgb(37, 105, 162);
			color: rgb(24, 90, 146);
			border: 1px solid rgb(24, 90, 146);
			border-left: none;
			text-align: center;
			font-size: 16px;
			padding-left: 4px;
			border-top-right-radius: 4px;
			border-bottom-right-radius: 4px;
			vertical-align: middle;
	}
	.btn-upload-file:hover,
	.btn-upload-file:focus{
			background: rgb(37, 105, 162);
			color: rgb(24, 90, 146);
			border-color: rgb(24, 90, 146);
			cursor: not-allowed;
	}
	.btn-upload-file.ready-to-upload{
			background: rgb(37, 105, 162);
			border-color: rgb(24, 90, 146);
			color: rgb(255,255,255);
	}
	.btn-upload-file:hover.ready-to-upload,
	.btn-upload-file:focus.ready-to-upload{
			background-color: rgb(21, 79, 129);
			cursor: pointer;
	}

	.btn-upload-file.with-attachment{
			background: rgb(20,128,140);
			border-color: rgb(24,90,146);
			border-color: rgb(31, 149, 162);
			color: rgb(255,255,255);
	}
	.btn-upload-file:hover.with-attachment,
	.btn-upload-file:focus.with-attachment{
			background-color: rgb(16, 101, 110);
			cursor: pointer;
	}

	.btn-upload-file.disabled{
			background: rgb(212, 212, 212);
			color: rgb(100,100,100);
			border-color: rgb(161, 161, 161);
	}
	.btn-upload-file:hover.disabled,
	.btn-upload-file:focus.disabled{
			background: rgb(212, 212, 212);
			color: rgb(100,100,100);
			border-color: rgb(161, 161, 161);
			cursor: not-allowed;
	}

	.btn-view-file{
			display: table-cell;
			width: 30px;
			border-left: none;
			text-align: center;
			font-size: 16px;
			padding-left: 4px;
			vertical-align: middle;
			background: rgb(20,128,140);
			border-color: rgb(24,90,146);
			border-color: rgb(31, 149, 162);
			color: rgb(255,255,255);
	}
	.btn-view-file:hover,
	.btn-view-file:focus{
			background-color: rgb(16, 101, 110);
			color: rgb(255,255,255);
			cursor: pointer;
	}

	.btn-view-file-delete{
			display: table-cell;
			width: 100%;
			height: 30px;
			border-top-right-radius: 4px;
			border-bottom-right-radius: 4px;
			border: 1px solid rgb(24, 90, 146);
			color: rgb(255,255,255);
			background: #d9534f;
			border-color: #ac2925;
	}

	.btn-view-file-delete:hover,
	.btn-view-file-delete:focus{
			background-color: #c9302c;
			color: #fff;
			cursor: pointer;
	}

	.input-file-hidden{
			visibility: hidden;
			position: absolute;
			left: -99999px;
	}
	#tabla_datos_beca_activa {
			font-size: 12px;
	}
	.full-width{
			width: 100%;
	}
	#container_fotografia input.file{
			visibility: hidden;
			display: none;
	}
	.bubble-timeline{
			display: inline-block;
			background-color: rgb(77, 178, 208);
			border-radius: 50%;
			line-height: 2em;
			max-width: 220px;
			/*border: 2px solid rgb(255, 255, 255);*/
			padding: 2px;
			margin-bottom: 15px;
			margin-top: 15px;
	}
	.icon-timeline-container{
			background-color: rgb(77, 178, 208);
			border: 3px solid rgb(255, 255, 255);
			border-radius: 50%;
			display: inline-block;
	}
	.img-timeline{
			width: 100%;
			/*background-color: rgb(77, 178, 208);
			border: 3px solid rgb(255, 255, 255);*/
			border-radius: 50%;
			display: inline-block;
	}
</style>

<script type="text/javascript">
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
		jQuery.ajaxSetup({async:false});
		grl_overlay_loading('');
		init_button_bar();
        $('.selectpicker').selectpicker({
            dropupAuto: 'true',
            container: 'body',
            size: '5',
            width: '100%',
            style: 'btn-sm btn-info'
        });
        //Habilita los selects para mobile
	    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	        $('.selectpicker').selectpicker('mobile');
	    }
	    usu_constructor_gerencias();
	    $('.letras').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z-¡!¿?,.() 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/, optional: false}}});
	    $('.numeros4').mask('9999');
	    $('.monto').mask("#,##0.000", {reverse: true, maxlength: false});
	    /*----------------------------------------------------------------------------------
    								Validando listboxs
	    ----------------------------------------------------------------------------------*/
		$('.selectpicker.requerido').change(function(event) {
			var objeto = $(this);
			if (objeto.val() == null || objeto.val() == '-b' || objeto.val() == ''){
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
	    					Validación input, textarea requeridos
	    ----------------------------------------------------------------------------------*/
	    $('.input.requerido').keyup(function(event) {
	    	if( $(this).val().trim() != "" ){
				$(this).parent('div').removeClass('has-error');
			}
			else{
				$(this).parent('div').addClass('has-error');
			}
	    });
	    $('[data-toggle="popover"]').popover();
	    <?php if (count($PLANTACIONES)) 
	    {
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
		                pageSize: 'LEGAL',
		                title: 'Planting List',
		            },
		            'print',
		        ]
	        });
	    	<?php
	    }
	    ?>
	    $('#modal_loading').modal('hide');
	    jQuery.ajaxSetup({async:true});
	});

	function plan_seleccionar_plantacion(id)
	{
		if($('#' + id).hasClass('fa-square-o'))
		{
			$('#' + id).removeClass('fa-square-o').addClass('fa-check-square-o');
		}
		else
		{
			$('#' + id).removeClass('fa-check-square-o').addClass('fa-square-o');
		}
	}

	$('#btn_aplicar_quimico').click(function(event) {
		/* Act on the event */
		$('#modal_aplicar_quimico').modal('show');
	});

	$('#btn_cosechar').click(function(event) {
		/* Act on the event */
		$('#modal_cosechar').modal('show');
	});
	$('#div_paginacion').on('click', '.paginate_button', function(event) {
		event.preventDefault();
		jQuery.ajaxSetup({async:false});
		plan_vista_listado_plantacion($(this).data('inicio'),$(this).data('limite'));
		$('.current').removeClass('current');
		$(this).addClass('current');
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
	                        <h3 class="panel-title translate" data-traducir_english="Planting List" data-traducir_spanish="Listado de Plantaciones">Listado de Plantaciones</h3>
							<div class="pull-right">
								<span class="clickable filter" data-toggle="tooltip" title="Realizar busqueda" data-container="body">
									<i class="fa fa-search"></i>
								</span>
							</div>
	                </div>
					<div class="panel-body panel_cuerpo input-group-sm">
						<input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Busqueda" />
					</div>
	                <div class="responsive_table_container">
	                    <table class="table table-responsive table-condensed table-striped" id="dev-table" >
	                        <thead>
                                <tr class="active info">
                                    <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
                                    <th width="30%" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</th>
                                    <th width="5%" class="translate" data-traducir_english="Year" data-traducir_spanish="Año">Año</th>
                                    <th width="5%" class="translate" data-traducir_english="Number" data-traducir_spanish="Número">Número</th>
                                    <th width="15%" class="translate" data-traducir_english="Season" data-traducir_spanish="Temporada">Temporada</th>
                                    <th width="15%" class="translate" data-traducir_english="Zones" data-traducir_spanish="Zonas">Zonas</th>
                                    <th width="10%" class="translate" data-traducir_english="Acres Planted" data-traducir_spanish="Acres Plantados">Acres Plantados</th>
                                    <th width="30%" class="translate" data-traducir_english="Stage" data-traducir_spanish="Estado">Estado</th>
                                </tr>
	                        </thead>
	                        <tbody id="cuerpo_tabla">
	                        	<?php
	                        	if (count($PLANTACIONES)) {
	                        		$correlativo = 1;
	                        		$estados = [ 'Pre-Planting',
								                    'Pre-Planting',
								                    'Planted',
								                    'Trimmed',
								                    'Growing',
								                    'Ready to harvest',
								                    'Harvested',
								                    'QA',
								                    'Packaged product',
								                    'Removed',
								                    'Finished'];

	                        		foreach ($PLANTACIONES as $plantacion) {
									    $date1 = strtotime($plantacion['date_insert']);
									    $date2 = strtotime(date('Y-m-d h:i:s', time()));
									    $difference = abs($date2 - $date1)/3600;
	                        			?>
	                        			<tr style="cursor: pointer;">
	                        				<td>
	                        					<?php echo $correlativo; ?>	                        					
	                        					<?php 
	                        					if($difference > $plantacion['tiempo_espera'])
	                        					{
	                        						?>
	                        						<a href="#" data-toggle="popover" data-trigger="hover" title="Planting exceeded waiting time" data-content="Planting has exceeded the waiting time in it stage."><i class="fas fa-exclamation-triangle fa-has-popover"></i></a>
	                        						<?php
	                        					}
	                        					?>
	                        				</td>
	                        				<td onclick="plan_vista_plantacion(<?php echo $plantacion['cod_plantacion']; ?>)"><?php echo utf8_encode($plantacion['nombre_empresa']); ?></td>
	                        				<td onclick="plan_vista_plantacion(<?php echo $plantacion['cod_plantacion']; ?>)"><?php echo utf8_encode($plantacion['anio_plantacion']); ?></td>
	                        				<td onclick="plan_vista_plantacion(<?php echo $plantacion['cod_plantacion']; ?>)"><?php echo utf8_encode($plantacion['num_plantacion']); ?></td>
	                        				<td onclick="plan_vista_plantacion(<?php echo $plantacion['cod_plantacion']; ?>)"><?php echo utf8_encode($plantacion['codigo_temporada']); ?></td>
	                        				<td onclick="plan_vista_plantacion(<?php echo $plantacion['cod_plantacion']; ?>)"><?php echo utf8_encode($plantacion['zonas']); ?></td>
	                        				<td onclick="plan_vista_plantacion(<?php echo $plantacion['cod_plantacion']; ?>)"><?php echo utf8_encode($plantacion['acres_plantados']); ?></td>
	                        				<td onclick="plan_vista_plantacion(<?php echo $plantacion['cod_plantacion']; ?>)"><span class="badge estado<?php echo utf8_encode($plantacion['cod_estado']); ?>" id="badge_estado2"><?php echo utf8_encode($estados[$plantacion['cod_estado']]); ?></span><span class="badge estado<?php echo utf8_encode($plantacion['cod_estado']); ?>" id="badge_estado"><?php echo utf8_encode($plantacion['estado_plantacion']); ?></span></td>
	                        			</tr>
	                        			<?php
	                        			$correlativo++;
	                        		}
	                        	}
	                        	?>
	                        </tbody>
	                    </table>
	                </div>
	                <!-- <div class="div_pagination" id="div_paginacion">
	                	<?php
		                	$cuerpo_paginacion = "";
							if(count($TOTAL) > 0)
							{
							    $total          = $TOTAL[0]['total'];
							    $num_reg        = 1;
							    $paginacion     = 1;
							    $inicio 		= 0;
							    $limite 		= 50;
							    $cuerpo_paginacion   .= '<a class="paginate_button current" aria-controls="example" data-pagina="'.$paginacion.'" data-inicio="0" data-limite="'.$limite.'" tabindex="0">'.$paginacion.'</a>';
							    $total          = $total - $limite;
							    while ($total > 1) 
							    {
							        $total      = $total - $limite; 
							        $num_reg    = $num_reg + $limite;
							        $inicio 	= $inicio + $limite;
							        $paginacion++;
							        $cuerpo_paginacion .= '<a class="paginate_button" aria-controls="example" data-pagina="'.$paginacion.'" data-inicio="'.$num_reg.'" data-limite="'.$limite.'" tabindex="0">'.$paginacion.'</a>';
							    }														  
							}
							echo $cuerpo_paginacion;
						?>
	                </div> -->
	            </div>
	        </div>
	    </div>
	</div>

	<div class="panel-footer-actions text-right smooth-transition actions-container-is-closed" id="panel_footer_actions">
        <div class="close-actions-container">
            <button class="btn btn-xs btn-close-actions" id="btn_close_actions">
                <i class="fa fa-chevron-circle-down fa-lg"></i>
            </button>
        </div>
        <div class="row actions">
            <!-- <div class="col-xs-9 col-md-3 nopadding text-left smooth-transition" id="container_back_button">
                <button data-loading-text="Volviendo" class="btn btn-sm btn-primary btn-fullwidth main-actions smooth-transition" type="button" id="btn_ir_al_listado" name="btn_ir_al_listado">
                    <i class="fa fa-arrow-left"> </i> <span id="btn_regresar_listado">Regresar a mis diligencias</span>
                </button>
            </div> -->

            <div class="col-xs-12 col-md-12 nopadding smooth-transition" id="div_acciones" style="text-align: center;">
                <button class="btn btn-sm btn-primary btn-open-actions main-actions smooth-transition" type="button" id="btn_open_actions" name="btn_open_actions">
                    <i class="fa fa-ellipsis-v"></i>
                </button>
                <!-- <button class="btn btn-sm btn-success" type="button" id="btn_aplicar_quimico">Aplicar Químico</button>
                <button class="btn btn-sm btn-primary" type="button" id="btn_cosechar">Cosechar</button> -->
        </div>
    </div> <!-- panel-footer -->
</body>