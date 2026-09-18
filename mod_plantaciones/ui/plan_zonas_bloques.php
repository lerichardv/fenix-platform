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

//$ZONAS 		= $DB_PLANT->bw_listado_zonas();
$ZONAS 		= $DB_PLANT->bw_listado_zonas_por_granjas($cod_granjas_usuario);

$cod_zona 	= $_POST['cod_zona'];
if (!isset($_POST['cod_zona'])) {
	$cod_zona = 0;
}
$ZONA 		= $DB_PLANT->bw_obtener_info_zona($cod_zona);
$BLOQUES 	= $DB_PLANT->bw_listado_bloques_zona($cod_zona);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Nueva capacitación</title>
</head>
<script type="text/javascript">	

	$(document).ready(function() {
		jQuery.ajaxSetup({async:false});
		grl_overlay_loading('');
		codigo_zona = 0;
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
        //$('html, body').animate({ scrollTop: 0 }, 0);
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
		//Constructores
		conf_constructor_listado_granjas();
		//Máscaras
	    $('.monto').mask("#,##0.000", {reverse: true, maxlength: false});
	    $('.letras').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z-¡!¿?,.() 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/, optional: false}}});
	    $('.letras4').mask('SSSS', {translation: {'S': {pattern: /[A-Za-z-¡!¿?,.() 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/, optional: false}}});
	    $('.letras10').mask('SSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z]/, optional: false}}});
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
	    if (count($ZONA)) 
	    {
	    	?>
	    	$('#zona').val("<?php echo utf8_encode($ZONA[0]['zona']); ?>");
	    	$('#abreviatura').val("<?php echo utf8_encode($ZONA[0]['abreviatura']); ?>");
	    	$('#ubicacion').val("<?php echo utf8_encode($ZONA[0]['ubicacion']); ?>");
	    	$('#bloque_inicial').val("<?php echo utf8_encode($ZONA[0]['bloque_inicial']); ?>");
	    	$('#bloque_final').val("<?php echo utf8_encode($ZONA[0]['bloque_final']); ?>");
	    	$('#cantidad_acres').val("<?php echo utf8_encode($ZONA[0]['cantidad_acres']); ?>");
	    	$('#cod_info_empresa').selectpicker('val',"<?php echo utf8_encode($ZONA[0]['cod_info_empresa']); ?>");	    	
	    	$('.selectpicker').selectpicker('refresh');
	    	$('#div_bloques').removeClass('hide');
	    	<?php
	    }
	    ?>
	    codigo_zona = <?php echo $cod_zona;?>;
	    $('#modal_loading').modal('hide');
	    jQuery.ajaxSetup({async:true});
	});

	$('#btn_guardar_zona').click(function(event) {
         /* Act on the event */
        var error = 0;
        $(".input.requerido").map(function(){
            if( !$(this).val() ) 
            {
                error = 1;
                $(this).parent('div').addClass('has-error');
                return false;
            } 
            else 
            {
                $(this).parent('div').removeClass('has-error');
            }
        });
        $(".selectpicker.requerido").map(function(){
            if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '')
            {
                $(this).selectpicker('setStyle', 'btn-info', 'remove');
                $(this).selectpicker('setStyle', 'btn-danger');
                error = 1;
            } 
            else 
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
            $('#modal_loading').on('hidden.bs.modal', function () {            
                bw_guardar_zona(codigo_zona);
            });
        }
        else
        {
            grl_mensaje('Debe llenar todos los campos marcados','favor verificar','warning');
        }   
    });

	$('.checkbox').change(function(event) {
		/* Act on the event */
		event.preventDefault();
		event.stopPropagation();
		bw_cambiar_estado_zona($(this).data('id'),($(this).attr('checked') ? 0 : 1));
	});

	$('.checkbox_bloque').change(function(event) {
		/* Act on the event */
		event.preventDefault();
		event.stopPropagation();
		bw_cambiar_estado_bloque($(this).data('id'),($(this).attr('checked') ? 0 : 1), codigo_zona);
	});

	$('#btn_add_bloque').click(function(event) {
		/* Act on the event */
		$('#modal_bloque').modal('show');
	});	

	$('#btn_guardar_bloque').click(function(event) {
         /* Act on the event */
        var error = 0;
        $(".input.requerido_modal").map(function(){
            if( !$(this).val() ) 
            {
                error = 1;
                $(this).parent('div').addClass('has-error');
                return false;
            } 
            else 
            {
                $(this).parent('div').removeClass('has-error');
            }
        });
        $(".selectpicker.requerido_modal").map(function(){
            if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '')
            {
                $(this).selectpicker('setStyle', 'btn-info', 'remove');
                $(this).selectpicker('setStyle', 'btn-danger');
                error = 1;
            } 
            else 
            {
                $(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');
                $(this).selectpicker('setStyle', 'btn-info');
                $(this).removeClass('campo-vacio');
            }
            $(this).selectpicker('refresh');
        });
        if (error == 0) 
        {
        	$('#modal_bloque').modal('hide');
            grl_overlay_loading('');
            $('#modal_loading').modal('hide');
            $('#modal_loading').on('hidden.bs.modal', function () {            
                bw_guardar_bloque(codigo_zona);
            });
        }
        else
        {
            grl_mensaje('Debe llenar todos los campos marcados','favor verificar','warning');
        }        
    });
</script>

<body>
	<div id="overlay_loading"></div>
	<div id="message_box"></div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="panel-header">
	                <h1 class="translate" data-traducir_english="Zone" data-traducir_spanish="Zona">Zona</h1>
	            </div>				
			</div>
            <div class="col-md-12">
            	<div class="row">
            		<div class="col-md-3">
	                    <div class="form-group input-group-sm">
	                        <label for="cod_info_empresa" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</label>
	                        <select class="selectpicker show-menu-arrow requerido" title="Select" id="cod_info_empresa" name="cod_info_empresa">
	                        </select>
	                    </div>
	            	</div>
	            	<div class="col-md-3">
	            		<div class="form-group input-group-sm">
                            <label for="zona" class="translate" data-traducir_english="Zone Name" data-traducir_spanish="Nombre Zona">Nombre Zona</label>
                            <input type="text"class="form-control letras input requerido"  id="zona" name="zona"> 
                        </div>
	            	</div>
	            	<div class="col-md-3">
	            		<div class="form-group input-group-sm">
                            <label for="abreviatura" class="translate" data-traducir_english="Abbreviation" data-traducir_spanish="Abreviatura">Abreviatura</label>
                            <input type="text"class="form-control letras4 input requerido"  id="abreviatura" name="abreviatura"> 
                        </div>
	            	</div>
	            	<div class="col-md-3">
	            		<div class="form-group input-group-sm">
                            <label for="ubicacion" class="translate" data-traducir_english="Location" data-traducir_spanish="Ubicación">Ubicación</label>
                            <input type="text"class="form-control letras input "  id="ubicacion" name="ubicacion"> 
                        </div>
	            	</div>
				</div>
				<div class="row">
	            	<div class="col-md-3">
	            		<div class="form-group input-group-sm">
                            <label for="bloque_inicial" class="translate" data-traducir_english="Initial Block" data-traducir_spanish="Bloque Inicial">Bloque Inicial</label>
                            <input type="text"class="form-control numeros4 input requerido"  id="bloque_inicial" name="bloque_inicial"> 
                        </div>
	            	</div>
	            	<div class="col-md-3">
	            		<div class="form-group input-group-sm">
                            <label for="bloque_final" class="translate" data-traducir_english="Final Block" data-traducir_spanish="Bloque Final">Bloque Final</label>
                            <input type="text"class="form-control numeros4 input requerido"  id="bloque_final" name="bloque_final"> 
                        </div>
	            	</div>
	            	<div class="col-md-3">
	            		<div class="form-group input-group-sm">
                            <label for="cantidad_acres" class="translate" data-traducir_english="Acres Amount" data-traducir_spanish="Cantidad Acres">Cantidad Acres</label>
                            <input type="text"class="form-control monto input requerido"  id="cantidad_acres" name="cantidad_acres"> 
                        </div>
	            	</div>
				</div>
				<div class="row hide" id="div_bloques">
	            	<div class="col-md-12">
	            		<div class="panel panel-primary" id="panel_itemschecklist">
		                    <div class="panel-heading btncollapsepaso"id="a_itemschecklist" role="button" data-toggle="collapse" href="#collapseitemschecklist" aria-expanded="true" data-parent="#accordion" aria-controls="collapseitemschecklist">
		                        <h3 class="panel-title translate" data-traducir_english="Zone Block's" data-traducir_spanish="Bloques de la Zona">Bloques de la zona</h3>
		                    </div>
		                    <div class="panel-collapse collapse" id="collapseitemschecklist" role="tabpanel" aria-labelledby="collapseitemschecklist">
		                        <div class="panel-body">
		                            <div class="responsive_table_container">
		                                <table class="table display row-border responsive" id="tabla_itemschecklist">
		                                    <thead>
		                                         <tr class="active info">
		                                            <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
		                                            <th width="40%" class="translate" data-traducir_english="Block" data-traducir_spanish="Bloque">Bloque</th>
		                                            <th width="40%" class="translate" data-traducir_english="Acres" data-traducir_spanish="Acres">Acres</th>
		                                            <th width="10%" class="translate" data-traducir_english="Active" data-traducir_spanish="Activo">Activo</th>
		                                         </tr>
		                                    </thead>
		                                    <tbody>
				                            <?php
				                            if(count($BLOQUES))
				                            {
				                            	$correlativo = 1;
				                            	foreach($BLOQUES as $bloque)
				                            	{
				                            		?>
				                            		<tr>
				                            			<td><?php echo $correlativo;?></td>
				                            			<?php
				                            			if($bloque['clave_bloque'] != '' && $bloque['clave_bloque'] != null)
				                            			{
					                            			?>
					                            			<td><?php echo utf8_encode($bloque['clave_bloque'].'-'.$bloque['nombre_bloque']);?></td>
					                            			<?php
				                            			}
				                            			else
				                            			{
				                            				?>
					                            			<td><?php echo utf8_encode($bloque['nombre_bloque']);?></td>
					                            			<?php
				                            			}
				                            			?>
				                            			<td><?php echo utf8_encode($bloque['num_acres']);?></td>
				                            			<td>	                            				
				                            				<div class="material-switch pull-right">
									                            <input class="checkbox_bloque" id="checkboxb_<?php echo utf8_encode($bloque['cod_bloque']);?>" data-id="<?php echo utf8_encode($bloque['cod_bloque']);?>" name="checkboxb_<?php echo utf8_encode($bloque['cod_bloque']);?>" type="checkbox" <?php echo ($bloque['activo'] == 1 ? 'checked="checked"':'');?>/>
									                            <label for="checkboxb_<?php echo utf8_encode($bloque['cod_bloque']);?>" class=""></label>
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
		                            <div class="row nomargin row-add-content">
		                                <div class="col-xs-12 text-center">
		                                    <button type="button" id="btn_add_bloque" class="btn btn-sm btn-success truncated-text btn_add" data-action="true">
		                                        <i class="fa fa-plus-circle"> </i>
		                                        <span class="translate" data-traducir_english="Add Block" data-traducir_spanish="Añadir Bloque">Añadir Bloque</span>
		                                    </button>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
	            	</div>		                
	            </div>
            </div>
		</div> 
		<div class="panel-footer" align="right">
			<button class="btn btn-sm btn-primary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" type="button" id="btn_guardar_zona">Guardar</button>
		</div>               
	</div>
	<div class="busqueda_contenedor">
	    <div class="row" style="min-width:100px;">
	        <div class="col-sm-12">
	                <div class="panel panel-primary" style="overflow:auto;">
	                <div class="panel-heading panel_cabecera"> 
	                        <h3 class="panel-title translate" data-traducir_english="Zone and Block List" data-traducir_spanish="Listado de Zonas y Bloques">Listado de Zonas y Bloques</h3>
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
	                    <table class="table table-condensed display table-striped" id="dev-table" >	                        
	                        <thead>
	                                <tr class="active info">
	                                        <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
	                                        <th width="20%" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</th>
	                                        <th width="20%" class="translate" data-traducir_english="Zone" data-traducir_spanish="Zona">Zona</th>
	                                        <th width="20%" class="translate" data-traducir_english="Abbreviation" data-traducir_spanish="Abreviatura">Abreviatura</th>
	                                        <th width="10%" class="translate" data-traducir_english="Block Key" data-traducir_spanish="Clave Bloque">Clave Bloque</th>
	                                        <th width="10%" class="translate" data-traducir_english="Initial Block" data-traducir_spanish="Bloque Inicial">Bloque Inicial</th>
	                                        <th width="10%" class="translate" data-traducir_english="Final Block" data-traducir_spanish="Bloque Final">Bloque Final</th>
	                                        <th width="10%" class="translate" data-traducir_english="Acres" data-traducir_spanish="Acres">Acres</th>
	                                        <th width="10%" class="translate" data-traducir_english="Active" data-traducir_spanish="Activo">Activo</th>
	                                </tr>
	                        </thead>
	                        <tbody id="cuerpo_tabla">
	                            <?php
	                            if(count($ZONAS))
	                            {
	                            	$correlativo = 1;
	                            	foreach($ZONAS as $zona)
	                            	{
	                            		?>
	                            		<tr>
	                            			<td onclick="bw_vista_zona(<?php echo utf8_encode($zona['cod_zona']);?>)"><?php echo $correlativo;?></td>
	                            			<td onclick="bw_vista_zona(<?php echo utf8_encode($zona['cod_zona']);?>)"><?php echo utf8_encode($zona['nombre_empresa']);?></td>
	                            			<td onclick="bw_vista_zona(<?php echo utf8_encode($zona['cod_zona']);?>)"><?php echo utf8_encode($zona['zona']);?></td>
	                            			<td onclick="bw_vista_zona(<?php echo utf8_encode($zona['cod_zona']);?>)"><?php echo utf8_encode($zona['abreviatura']);?></td>
	                            			<td onclick="bw_vista_zona(<?php echo utf8_encode($zona['cod_zona']);?>)"><?php echo utf8_encode($zona['clave_bloque']);?></td>
	                            			<td onclick="bw_vista_zona(<?php echo utf8_encode($zona['cod_zona']);?>)"><?php echo utf8_encode($zona['bloque_inicial']);?></td>
	                            			<td onclick="bw_vista_zona(<?php echo utf8_encode($zona['cod_zona']);?>)"><?php echo utf8_encode($zona['bloque_final']);?></td>
	                            			<td onclick="bw_vista_zona(<?php echo utf8_encode($zona['cod_zona']);?>)"><?php echo utf8_encode($zona['cantidad_acres']);?></td>
	                            			<td>	                            				
	                            				<div class="material-switch pull-right">
						                            <input class="checkbox" id="checkbox_<?php echo utf8_encode($zona['cod_zona']);?>" data-id="<?php echo utf8_encode($zona['cod_zona']);?>" name="checkbox_<?php echo utf8_encode($zona['cod_zona']);?>" type="checkbox" <?php echo ($zona['activo'] == 1 ? 'checked="checked"':'');?>/>
						                            <label for="checkbox_<?php echo utf8_encode($zona['cod_zona']);?>" class=""></label>
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
	<div class="modal fade modal_bloque" id="modal_bloque" tabindex="-1" role="dialog" aria-labelledby="ModalResponsables" aria-hidden="true">
		<div class="modal-dialog modal-success modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title"><span id="titulo_modal_responsables" class="translate" data-traducir_english="Block" data-traducir_spanish="Bloque">Bloque</span> <small class="subtitulo" id="subtitulo_modal_responsables"></small></h4>
				</div>
				<div class="modal-body">
					<div class="row">
		            	<div class="form-group input-group-sm col-xs-6">
		            		<div class="form-group input-group-sm">
	                            <label for="modal_bloque_inicial" class="translate" data-traducir_english="Initial Block" data-traducir_spanish="Bloque Inicial">Bloque Inicial</label>
	                            <input type="text"class="form-control monto input requerido_modal" id="modal_bloque_inicial" name="modal_bloque_inicial"> 
	                        </div>
		            	</div>
		            	<div class="form-group input-group-sm col-xs-6">
		            		<div class="form-group input-group-sm">
	                            <label for="modal_bloque_final" class="translate" data-traducir_english="Final Block" data-traducir_spanish="Bloque Final">Bloque Final</label>
	                            <input type="text"class="form-control monto input" id="modal_bloque_final" name="modal_bloque_final"> 
	                        </div>
		            	</div>
		            	<div class="col-xs-12">
		            		<i class="fa fa-exclamation fa-2x"></i><span class="h4 translate" data-traducir_english="To add only one block, enter only the initial block." data-traducir_spanish="Para agregar sólo un bloque, ingrese solamente bloque inicial."> Para agregar sólo un bloque, ingrese solamente bloque inicial.</span>
		            	</div>
		            	<div class="form-group input-group-sm col-xs-12">
		            		<div class="form-group input-group-sm">
	                            <label for="clave_bloque" class="translate" data-traducir_english="Block Key" data-traducir_spanish="Clave Bloque">Clave bloque</label>
	                            <input type="text"class="form-control letras10 input" id="clave_bloque" name="clave_bloque"> 
	                        </div>
		            	</div>
		            	<div class="form-group input-group-sm col-xs-12">
		            		<div class="form-group input-group-sm">
	                            <label for="num_acres">Acres</label>
	                            <input type="text"class="form-control monto input requerido_modal" id="num_acres" name="num_acres"> 
	                        </div>
		            	</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-sm btn-primary btn_guardar translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_guardar_bloque">Guardar</button>
				</div>
			</div>
		</div>
	</div>
</body>