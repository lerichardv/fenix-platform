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
include_once("../../libs/db_classes/db_configuracion.php"); 
/*INSTANCIAMIENTOS*/
$DB_CONF = new db_configuracion();

$cod_granjas_usuario = str_replace(['[','"',']'], '', $_SESSION['cod_info_empresa']);

//$TEMPORADAS = $DB_CONF->conf_listado_temporadas();
$TEMPORADAS = $DB_CONF->conf_listado_temporadas_por_granjas($cod_granjas_usuario);

$cod_temporada = $_POST['cod_temporada'];
if (!isset($_POST['cod_temporada'])) {
	$cod_temporada = 0;
}

$TEMPORADA = $DB_CONF->conf_obtener_info_temporada($cod_temporada);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Nueva capacitación</title>
</head>
<script type="text/javascript">
	jQuery.ajaxSetup({async:false});
	grl_overlay_loading('');
	var fecha_hoy   = new Date(<?php echo time()*1000; ?>);   
    var hoy         = (fecha_hoy.getMonth()+1) + "/" + fecha_hoy.getDate() + "/" + fecha_hoy.getFullYear();
    //var hoy_18_anios_atras        = (fecha_hoy.getMonth()+1) + "/" + fecha_hoy.getDate() + "/" + (fecha_hoy.getFullYear()-18);
    var hoy_default = fecha_hoy.getFullYear() + "-" + (fecha_hoy.getMonth()+1) + "-" + fecha_hoy.getDate(); 
    
    $('.fecha-inicio').datetimepicker({
        //disabledHours: true,
        locale: 'es',
        //minDate: hoy,
        //keepOpen: true,
        format: 'MM-DD-YYYY',
        defaultDate: hoy + ' 08:00 am',
        //direction: 'auto',
        icons: {
                    time: "fa fa-clock-o",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down",
                    previous: 'fa fa-arrow-left',
                    next: 'fa fa-arrow-right',
                }
    }).on('dp.hide',function (e){
        $('.fecha-final').data("DateTimePicker").minDate(moment(e.date));      
    })
    $('.fecha-final').datetimepicker({
        //disabledHours: true,
        locale: 'es',
        //minDate: hoy,
        //keepOpen: true,
        format: 'MM-DD-YYYY',
        defaultDate: hoy,
        //direction: 'auto',
        //disabledHours: 
        icons: {
                    time: "fa fa-clock-o",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down",
                    previous: 'fa fa-arrow-left',
                    next: 'fa fa-arrow-right',
                }
    }).on('dp.hide',function (e){
        $('.fecha-inicio').data("DateTimePicker").maxDate(moment(e.date));
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
		codigo_temporada = 0;
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
	    conf_constructor_listado_tipos_temporada_activos();
	    conf_constructor_listado_granjas();
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
	    <?php
	    if (count($TEMPORADA)) 
	    {
	    	?>
	    	$('#codigo_temporada').val("<?php echo utf8_encode($TEMPORADA[0]['codigo_temporada']); ?>");
	    	$('#fecha_inicio').val("<?php echo utf8_encode($TEMPORADA[0]['fecha_inicio']); ?>");
	    	$('#fecha_final').val("<?php echo utf8_encode($TEMPORADA[0]['fecha_final']); ?>");
	    	$('#cod_info_empresa').selectpicker('val',"<?php echo utf8_encode($TEMPORADA[0]['cod_info_empresa']); ?>");
	    	$('#cod_tipo_temporada').selectpicker('val',"<?php echo utf8_encode($TEMPORADA[0]['cod_tipo_temporada']); ?>");
	    	$('.selectpicker').selectpicker('refresh');
	    	<?php
	    }
	    ?>
	    codigo_temporada = <?php echo $cod_temporada;?>;
	    grl_traducir_interfaz(($('#checkbox_translate').attr('checked') ? 1 : 0));
	    $('#modal_loading').modal('hide');
	    jQuery.ajaxSetup({async:true});
	});

	$('#btn_guardar_temporada').click(function(event) {
		/* Act on the event */
		jQuery.ajaxSetup({async:false});
		conf_guardar_temporada(codigo_temporada);
		jQuery.ajaxSetup({async:true});
	});
	$('.checkbox').change(function(event) {
		/* Act on the event */
		event.preventDefault();
		event.stopPropagation();
		conf_cambiar_estado_temporada($(this).data('id'),($(this).attr('checked') ? 0 : 1));
	});
</script>

<body>
	<div id="overlay_loading"></div>
	<div id="message_box"></div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="panel-header">
	                <h1 class="translate" data-traducir_english="Season" data-traducir_spanish="Temporada">Temporada</h1>
	            </div>				
			</div>
            <div class="col-md-12">
            	<div class="row">
					<div class="col-md-4">
	                    <div class="form-group input-group-sm">
	                        <label for="cod_info_empresa" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</label>
	                        <select class="selectpicker show-menu-arrow requerido" title="Select" id="cod_info_empresa" name="cod_info_empresa">
	                        </select>
	                    </div>
	            	</div>
					<div class="col-md-4">
	                    <div class="form-group input-group-sm">
	                        <label for="cod_tipo_temporada" class="translate" data-traducir_english="Type" data-traducir_spanish="Tipo">Tipo</label>
	                        <select class="selectpicker show-menu-arrow requerido" title="Select" id="cod_tipo_temporada" name="cod_tipo_temporada">
	                        </select>
	                    </div>
	            	</div>
	            	<div class="col-md-4">
	            		<div class="form-group input-group-sm">
                            <label for="codigo_temporada" class="translate" data-traducir_english="Season Code" data-traducir_spanish="Código">Código</label>
                            <input type="text"class="form-control letras input requerido requerido_nueva_capacitacion"  id="codigo_temporada" name="codigo_temporada"> 
                        </div>
	            	</div>
	            </div>
	            <div class="row">
                    <div class='col-md-4'>
			            <div class="form-group">
		                	<label for="fecha_inicio" class="translate" data-traducir_english="Initial Date" data-traducir_spanish="Fecha Inicio">Fecha Inicio</label>
			                <div class='input-group input-group-sm fecha-inicio date' id='datetimepicker1'>
			                    <span class="input-group-addon">
			                        <span class="fa fa-calendar"></span>
			                    </span>
			                    <input type='text' class="form-control input requerido" id="fecha_inicio" />
			                </div>
			            </div>
			        </div>
                    <div class='col-md-4'>
			            <div class="form-group">
		                	<label for="fecha_final" class="translate" data-traducir_english="Final Date" data-traducir_spanish="Fecha Final">Fecha Final</label>
			                <div class='input-group input-group-sm fecha-final date' id='datetimepicker1'>
			                    <span class="input-group-addon">
			                        <span class="fa fa-calendar"></span>
			                    </span>
			                    <input type='text' class="form-control input requerido" id="fecha_final" />
			                </div>
			            </div>
			        </div>
				</div>
            </div>
		</div> 
		<div class="panel-footer" align="right">
			<button class="btn btn-sm btn-primary translate" type="button" id="btn_guardar_temporada" data-traducir_english="Save" data-traducir_spanish="Guardar">Guardar</button>
		</div>               
	</div>
	<div class="busqueda_contenedor">
	    <div class="row" style="min-width:100px;">
	        <div class="col-sm-12">
	                <div class="panel panel-primary" style="overflow:auto;">
	                <div class="panel-heading panel_cabecera"> 
	                        <h3 class="panel-title translate" data-traducir_english="List of Seasons" data-traducir_spanish="Listado de Temporadas">Listado de Temporadas</h3>		
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
	                                        <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
	                                        <th width="20%" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</th>
	                                        <th width="20%" class="translate" data-traducir_english="Code" data-traducir_spanish="Código">Código</th>
	                                        <th width="20%" class="translate" data-traducir_english="Type" data-traducir_spanish="Tipo">Tipo</th>	                                        
	                                        <th width="15%" class="translate" data-traducir_english="Initial Date" data-traducir_spanish="Fecha Inicio">Fecha Inicio</th>                                        
	                                        <th width="15%" class="translate" data-traducir_english="Final Date" data-traducir_spanish="Fecha Final">Fecha Final</th>
	                                        <th width="15%"></th>
	                                </tr>
	                        </thead>
	                        <tbody id="cuerpo_tabla">
	                            <?php
	                            if(count($TEMPORADAS))
	                            {
	                            	$correlativo = 1;
	                            	foreach($TEMPORADAS as $temporada)
	                            	{
	                            		?>
	                            		<tr>
	                            			<td class="hide"><?php echo utf8_encode($temporada['cod_temporada']);?></td>
	                            			<td onclick="conf_vista_temporada(<?php echo utf8_encode($temporada['cod_temporada']);?>)"><?php echo $correlativo;?></td>
	                            			<td onclick="conf_vista_temporada(<?php echo utf8_encode($temporada['cod_temporada']);?>)"><?php echo utf8_encode($temporada['nombre_empresa']);?></td>
	                            			<td onclick="conf_vista_temporada(<?php echo utf8_encode($temporada['cod_temporada']);?>)"><?php echo utf8_encode($temporada['codigo_temporada']);?></td>
	                            			<td onclick="conf_vista_temporada(<?php echo utf8_encode($temporada['cod_temporada']);?>)"><?php echo utf8_encode($temporada['tipo_temporada']);?></td>
	                            			<td onclick="conf_vista_temporada(<?php echo utf8_encode($temporada['cod_temporada']);?>)"><?php echo utf8_encode($temporada['fecha_inicio']);?></td>
	                            			<td onclick="conf_vista_temporada(<?php echo utf8_encode($temporada['cod_temporada']);?>)"><?php echo utf8_encode($temporada['fecha_final']);?></td>
	                            			<td>	                            				
	                            				<div class="material-switch pull-right">
						                            <input class="checkbox" id="checkbox_<?php echo utf8_encode($temporada['cod_temporada']);?>" data-id="<?php echo utf8_encode($temporada['cod_temporada']);?>" name="checkbox_<?php echo utf8_encode($temporada['cod_temporada']);?>" type="checkbox" <?php echo ($temporada['activo'] == 1 ? 'checked="checked"':'');?>/>
						                            <label for="checkbox_<?php echo utf8_encode($temporada['cod_temporada']);?>" class=""></label>
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