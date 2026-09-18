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

$FORMULARIOS = $DB_CONF->conf_listado_formularios_activos();

$cod_formulario = $_POST['cod_formulario'];
if (!isset($_POST['cod_formulario'])) {
	$cod_formulario = 0;
}

$FORMULARIO = $DB_CONF->conf_obtener_info_formulario($cod_formulario);
$ITEMS = $DB_CONF->conf_obtener_items_formulario($cod_formulario);
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
		codigo_formulario = 0;
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
		/*Máscaras de formato de ingreso de datos*/
        $('.monto').mask("#,##0.00", {reverse: true});
	    $('.letras').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z-¡!¿?,.() 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/, optional: false}}});
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
        bw_constructor_estados_plantaciones();
        conf_constructor_tipos_items_formularios();      
	    <?php
	    if (count($FORMULARIO)) 
	    {
	    	?>
	    	$('#nombre_formulario').val("<?php echo utf8_encode($FORMULARIO[0]['nombre_formulario']); ?>");
	    	$('#puntuacion_minima').val("<?php echo utf8_encode($FORMULARIO[0]['puntuacion_minima']); ?>");
	    	$('#puntuacion_maxima').val("<?php echo utf8_encode($FORMULARIO[0]['puntuacion_maxima']); ?>");
	    	$('#descripcion_formulario').val("<?php echo utf8_encode($FORMULARIO[0]['descripcion_formulario']); ?>");
	    	$('#cod_estado_plantacion').selectpicker('val',"<?php echo utf8_encode($FORMULARIO[0]['cod_estado']); ?>");	    	
	    	$('.selectpicker').selectpicker('refresh');
	    	$('#div_add_item').removeClass('hide');
	    	/*Cambia la vista de formulario*/
	    	conf_cargar_vista_previa_formulario('div_vista_previa_formulario',<?php echo utf8_encode($FORMULARIO[0]['cod_formulario']); ?>);
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
	    	$('.selectpicker').selectpicker('refresh');
	    	$('.date').datetimepicker({
		        //disabledHours: true,
		        locale: 'es',
		        //minDate: hoy,
		        //keepOpen: true,
		        format: 'MM-DD-YYYY',
		        //defaultDate: hoy + ' 08:00 am',
		        //direction: 'auto',
		        icons: {
		                    time: "fa fa-clock-o",
		                    date: "fa fa-calendar",
		                    up: "fa fa-arrow-up",
		                    down: "fa fa-arrow-down",
		                    previous: 'fa fa-arrow-left',
		                    next: 'fa fa-arrow-right',
		                }
		    });
	    	<?php
	    }
	    ?>  
        codigo_formulario = <?php echo $cod_formulario; ?>;  
	    $('#modal_loading').modal('hide');
	    jQuery.ajaxSetup({async:true});      
	});

	$('#btn_add_item').click(function(event) {
		/* Act on the event */
		$('#modal_formulario').modal('show');
	});

	$('#cod_tipo_item').change(function(event) {
		/* Act on the event */
		switch($(this).val())
		{
			case '3':
				$('#div_opciones').removeClass('hide');
				$('#div_opciones_no_adjunto').removeClass('hide');
				break;
			case '4':
			case '5':
			case '6':
				$('#div_opciones_no_adjunto').addClass('hide');
				break;
			default:
				$('#div_opciones').addClass('hide');
				$('#div_opciones_no_adjunto').removeClass('hide');
				break;
		}
	});


	$('#btn_crear_nuevo_formulario').click(function(event) {
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
                conf_guardar_formulario(codigo_formulario);
            });
        }
        else
        {
            grl_mensaje('Debe llenar todos los campos marcados','favor verificar','warning');
        }        
    });

	$('#btn_guardar_item').click(function(event) {
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
        	jQuery.ajaxSetup({async:false});
			$('#modal_formulario').modal('hide');
        	grl_overlay_loading('');
            $('#modal_loading').modal('hide');
            $('#modal_loading').on('hidden.bs.modal', function () {  
				conf_guardar_item_formulario(codigo_formulario);
		        conf_vista_formulario(codigo_formulario);
            });
            jQuery.ajaxSetup({async:true});
	    }
        else
        {
            grl_mensaje('Debe llenar todos los campos marcados','favor verificar','warning');
        } 
	});

	$('#btn_editar_item').click(function(event) {
		/* Act on the event */
		var error = 0;
        $(".input.requerido_modal_editar_item").map(function(){
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
        $(".selectpicker.requerido_modal_editar_item").map(function(){
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
        	jQuery.ajaxSetup({async:false});
			$('#modal_editar_item').modal('hide');
        	grl_overlay_loading('');
            $('#modal_loading').modal('hide');
            $('#modal_loading').on('hidden.bs.modal', function () {  
				conf_actualizar_item_formulario(codigo_item);
		        //conf_vista_formulario(codigo_formulario);
            });
            jQuery.ajaxSetup({async:true});
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
		conf_cambiar_activo_item_formulario($(this).data('id'),($(this).attr('checked') ? 0 : 1),codigo_formulario);
	});
	$('.checkbox_form').change(function(event) {
		/* Act on the event */
		event.preventDefault();
		event.stopPropagation();
		conf_cambiar_activo_formulario($(this).data('id'),($(this).attr('checked') ? 0 : 1));
	});
</script>

<body>
	<div id="overlay_loading"></div>
	<div id="message_box"></div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="panel-header">
	                <h1 class="translate" data-traducir_english="Form" data-traducir_spanish="Formulario">Formulario</h1>
	            </div>				
			</div>
            <div class="col-md-12">
            	<div class="row">
	            	<div class="col-md-3">
	            		<div class="form-group input-group-sm" id="div_nombre_capacitacion">
                            <label for="nombre_formulario" class="translate" data-traducir_english="Name" data-traducir_spanish="Nombre">Nombre</label>
                            <input type="text"class="form-control letras input requerido"  id="nombre_formulario" name="nombre_formulario"> 
                        </div>
	            	</div>
	            	<div class="col-md-3">
	            		<div class="form-group input-group-sm" id="div_nombre_capacitacion">
                            <label for="puntuacion_minima" class="translate" data-traducir_english="Minimal score" data-traducir_spanish="Puntuación mínima">Puntuación mínima</label>
                            <input type="text"class="form-control monto" maxlength="3" id="puntuacion_minima" name="nombre_formulario"> 
                        </div>
	            	</div>
	            	<div class="col-md-3">
	            		<div class="form-group input-group-sm" id="div_nombre_capacitacion">
                            <label for="puntuacion_maxima" class="translate" data-traducir_english="Maximun score" data-traducir_spanish="Puntuación máxima">Puntuación máxima</label>
                            <input type="text"class="form-control monto" maxlength="3" id="puntuacion_maxima" name="nombre_formulario"> 
                        </div>
	            	</div>
	            	<div class="col-md-3" id="">
						<label for="cod_estado_plantacion" class="translate" data-traducir_english="Stage" data-traducir_spanish="Estado">Estado</label>
						<div class="form-group show-tick">
							<select class="selectpicker show-menu-arrow requerido" id="cod_estado_plantacion" name="cod_estado_plantacion" data-live-search="true" title="Select">
							</select>
						</div>
					</div>
				</div>				
            	<div class="row">
					<div class="col-md-12">
                    	<div class="form-group input-group-sm" id="div_descripcion_formulario">
                            <label for="descripcion_formulario" class="translate" data-traducir_english="Description" data-traducir_spanish="Descripción">Descripción</label>
                            <textarea class="form-control input" id="descripcion_formulario" maxlength="600" align="left" style="height:100px; width:100%; resize: none;"></textarea>
                        </div>
                    </div>
				</div>
				<div class="row">
	            	<div class="col-md-12">
	            		<div class="panel panel-primary" id="panel_itemschecklist">
		                    <div class="panel-heading btncollapsepaso"id="a_itemschecklist" role="button" data-toggle="collapse" href="#collapseitemschecklist" aria-expanded="true" data-parent="#accordion" aria-controls="collapseitemschecklist">
		                        <h3 class="panel-title"><i class="fa fa-step-forward" aria-hidden="true"></i> Items Form</h3>
		                    </div>
		                    <div class="panel-collapse collapse" id="collapseitemschecklist" role="tabpanel" aria-labelledby="collapseitemschecklist">
		                        <div class="panel-body">
		                            <div class="responsive_table_container">
		                                <table class="table display row-border responsive" id="tabla_itemschecklist">
		                                    <thead>
		                                         <tr class="active info">
		                                            <th width="5%"></th>
		                                            <th width="20%" class="translate" data-traducir_english="Item Name" data-traducir_spanish="Nombre Item">Nombre Item</th>
		                                            <th width="20%" class="translate" data-traducir_english="Item Type" data-traducir_spanish="Tipo Item">Tipo Item</th>
		                                            <th width="30%" class="translate" data-traducir_english="Options" data-traducir_spanish="Opciones">Opciones</th>
		                                            <th width="10%" class="translate" data-traducir_english="Active" data-traducir_spanish="Activo">Activo</th>
		                                         </tr>
		                                    </thead>
		                                    <tbody>
		                                        <?php
		                                        if (count($ITEMS)) {
		                                        	$correlativo = 1;
		                                            foreach ($ITEMS as $item) {
		                                                ?>
		                                                <tr>
		                                                    <td class="hide"><?php echo utf8_encode($item['cod_item']); ?></td>
		                                                    <td><?php echo $correlativo; ?></td>
		                                                    <td><?php echo utf8_encode($item['nombre_item']); ?></td>
		                                                    <td><?php echo utf8_encode($item['tipo_item']); ?></td>
		                                                    <td><?php echo utf8_encode($item['opciones']); ?></td>
		                                                    <td>
		                                                        <button class="btn btn-sm btn-edit smooth-transition" onclick="conf_editar_item_formulario(<?php echo $item['cod_item']; ?>,'<?php echo utf8_encode($item['nombre_item']); ?>');" title="Edit - Editar" type="button"><i class="fa fa-edit fa-lg"></i></button>
		                                                        <!-- <button class="btn btn-sm btn-grid btn-delete smooth-transition" onclick="dgc_eliminar_responsable(<?php echo $responsable['cod_responsable']; ?>, <?php echo $responsable['cod_diligencia']; ?>);" title="Eliminar responsable" type="button"><i class="far fa-trash-alt fa-lg"></i></button> -->
		                                                        <div class="material-switch">
										                            <input class="checkbox" id="checkbox_<?php echo utf8_encode($item['cod_item']);?>" data-id="<?php echo utf8_encode($item['cod_item']);?>" name="checkbox_<?php echo utf8_encode($item['cod_item']);?>" type="checkbox" <?php echo ($item['activo'] == 1 ? 'checked="checked"':'');?>/>
										                            <label for="checkbox_<?php echo utf8_encode($item['cod_item']);?>" class=""></label>
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
		                            <div class="row nomargin row-add-content hide" id="div_add_item">
		                                <div class="col-xs-12 text-center">
		                                    <button type="button" id="btn_add_item" class="btn btn-sm btn-success truncated-text btn_add" data-action="true">
		                                        <i class="fa fa-plus-circle"> </i>
		                                        <span class="translate" data-traducir_english="Add Item" data-traducir_spanish="Añadir Item">Añadir Item</span>
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
			<button class="btn btn-sm btn-primary translate" type="button" id="btn_crear_nuevo_formulario" data-traducir_english="Save" data-traducir_spanish="Guardar">Guardar</button>
		</div>               
	</div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="panel-header">
	                <h1 class="translate" data-traducir_english="Preliminary View" data-traducir_spanish="Vista Previa">Vista Previa</h1>
	            </div>				
			</div>
            <div class="col-md-12" id="div_vista_previa_formulario">
            	<div class="row">
            		<div class="col-md-12">
            			<h3 id="txt_nombre_formulario" class="translate" data-traducir_english="Form Name" data-traducir_spanish="Nombre Formulario">Nombre Formulario</h3>
            		</div>
            	</div>
            	<div class="row">
            		<div class="col-md-12">
            			<div class="responsive_table_container">
                            <table class="table display row-border responsive" id="tabla_itemschecklist">
                                <thead>
                                     <tr class="active info">
                                        <th width="10%"></th>
                                        <th width="30%" class="translate" data-traducir_english="Item Name" data-traducir_spanish="Nombre Item">Nombre Item</th>
                                        <th width="30%" class="translate" data-traducir_english="Item Type" data-traducir_spanish="Tipo Item">Tipo Item</th>
                                        <th width="30%">Item</th>
                                     </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
            		</div>
            	</div>
            </div>
        </div>
    </div>
	<div class="busqueda_contenedor">
	    <div class="row" style="min-width:100px;">
	        <div class="col-sm-12">
	                <div class="panel panel-primary" style="overflow:auto;">
	                <div class="panel-heading panel_cabecera"> 
	                        <h3 class="panel-title translate" data-traducir_english="Forms List" data-traducir_spanish="Listado de Formularios">Listado de Formularios</h3>
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
	                                        <th width="30%" class="translate" data-traducir_english="Form" data-traducir_spanish="Formulario">Formulario</th>
	                                        <th width="45%" class="translate" data-traducir_english="Description" data-traducir_spanish="Descripción">Descripción</th>
	                                        <th width="20%" class="translate" data-traducir_english="Scores" data-traducir_spanish="Puntuaciones">Puntuaciones</th>
	                                        <th width="20%" class="translate" data-traducir_english="Stages" data-traducir_spanish="Estado Plantación">Estado Plantación</th>
	                                        <th width="10%" class="translate" data-traducir_english="Active" data-traducir_spanish="Activo">Activo</th>
	                                </tr>
	                        </thead>
	                        <tbody id="cuerpo_tabla">
	                            <?php
	                            if(count($FORMULARIOS))
	                            {
	                            	$correlativo = 1;
	                            	foreach($FORMULARIOS as $formulario)
	                            	{
	                            		?>
	                            		<tr>
	                            			<td class="hide"><?php echo utf8_encode($formulario['cod_formulario']);?></td>
	                            			<td onclick="conf_vista_formulario(<?php echo utf8_encode($formulario['cod_formulario']); ?>)"><?php echo $correlativo;?></td>
	                            			<td onclick="conf_vista_formulario(<?php echo utf8_encode($formulario['cod_formulario']); ?>)"><?php echo utf8_encode($formulario['nombre_formulario']);?></td>
	                            			<td onclick="conf_vista_formulario(<?php echo utf8_encode($formulario['cod_formulario']); ?>)"><?php echo utf8_encode($formulario['descripcion_formulario']);?></td>
	                            			<td onclick="conf_vista_formulario(<?php echo utf8_encode($formulario['cod_formulario']); ?>)"><?php echo utf8_encode($formulario['puntuacion_minima']).' - '.utf8_encode($formulario['puntuacion_maxima']);?></td>
	                            			<td onclick="conf_vista_formulario(<?php echo utf8_encode($formulario['cod_formulario']); ?>)"><?php echo utf8_encode($formulario['estado_plantacion']);?></td>
	                            			<td>
	                            				<div class="material-switch">
						                            <input class="checkbox_form" id="checkbox_form_<?php echo utf8_encode($formulario['cod_formulario']);?>" data-id="<?php echo utf8_encode($formulario['cod_formulario']);?>" name="checkbox_form_<?php echo utf8_encode($formulario['cod_formulario']);?>" type="checkbox" <?php echo ($formulario['activo'] == 1 ? 'checked="checked"':'');?>/>
						                            <label for="checkbox_form_<?php echo utf8_encode($formulario['cod_formulario']);?>" class=""></label>
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
	<div class="modal fade modal_formulario" id="modal_formulario" tabindex="-1" role="dialog" aria-labelledby="ModalResponsables" aria-hidden="true">
		<div class="modal-dialog modal-success modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title"><span id="titulo_modal_responsables">Item</span> <small class="subtitulo" id="subtitulo_modal_responsables"></small></h4>
				</div>
				<div class="modal-body">
					<div class="row">
                    	<div class="form-group input-group-sm col-xs-12">
		            		<div class="form-group input-group-sm">
	                            <label for="nombre_item" class="translate" data-traducir_english="Name" data-traducir_spanish="Nombre">Nombre</label>
	                            <input type="text"class="form-control letras input requerido_modal"  id="nombre_item" name="nombre_item"> 
	                        </div>
		            	</div>
                    	<div class="form-group input-group-sm col-xs-12">
	                    	<div class="form-group input-group-sm" id="div_tipo_curso">
	                        	<label for="cod_tipo_item" class="translate" data-traducir_english="Type" data-traducir_spanish="Tipo">Tipo</label>
		                        <select class="selectpicker show-menu-arrow requerido_modal" title="Select" id="cod_tipo_item" name="cod_tipo_item">
		                        </select>
		                     </div>
	                    </div>
	                    <div class="form-group input-group-sm col-xs-12">
		                    <div class="form-group input-group-sm" id="div_descripcion_item">
	                            <label for="descripcion_item" class="translate" data-traducir_english="Description" data-traducir_spanish="Descripción">Descripción</label>
	                            <textarea class="form-control input" id="descripcion_item" maxlength="600" align="left" style="height:100px; width:100%; resize: none;"></textarea>
	                        </div>
	                    </div>
                    	<div class="form-group input-group-sm col-xs-6">
		            		<div class="form-group input-group-sm">
	                            <label for="opcion1" class="translate" data-traducir_english="Option 1" data-traducir_spanish="Opción 1">Opción 1</label>
	                            <input type="text"class="form-control letras input requerido_modal"  id="opcion1" name="opcion1"> 
	                        </div>
		            	</div>
                    	<div class="form-group input-group-sm col-xs-6">
		            		<div class="form-group input-group-sm">
	                            <label for="valor1" class="translate" data-traducir_english="Value 1" data-traducir_spanish="Valor 1">Valor 1</label>
	                            <input type="text"class="form-control monto input"  id="valor1" name="valor1"> 
	                        </div>
		            	</div>
		            	<div id="div_opciones_no_adjunto">
	                    	<div class="form-group input-group-sm col-xs-6">
			            		<div class="form-group input-group-sm">
		                            <label for="opcion2" class="translate" data-traducir_english="Option 2" data-traducir_spanish="Opción 2">Opción 2</label>
		                            <input type="text"class="form-control letras input"  id="opcion2" name="opcion2"> 
		                        </div>
			            	</div>
	                    	<div class="form-group input-group-sm col-xs-6">
			            		<div class="form-group input-group-sm">
		                            <label for="valor2" class="translate" data-traducir_english="Value 2" data-traducir_spanish="Valor 2">Valor 2</label>
		                            <input type="text"class="form-control monto input"  id="valor2" name="valor2"> 
		                        </div>
			            	</div>
		                    <div id="div_opciones" class="hide">
		                    	<div class="form-group input-group-sm col-xs-6">
				            		<div class="form-group input-group-sm">
			                            <label for="opcion3" class="translate" data-traducir_english="Option 3" data-traducir_spanish="Opción 3">Opción 3</label>
			                            <input type="text"class="form-control letras input"  id="opcion3" name="opcion3"> 
			                        </div>
				            	</div>
		                    	<div class="form-group input-group-sm col-xs-6">
				            		<div class="form-group input-group-sm">
			                            <label for="valor3" class="translate" data-traducir_english="Value 3" data-traducir_spanish="Valor 3">Valor 3</label>
			                            <input type="text"class="form-control monto input"  id="valor3" name="valor3"> 
			                        </div>
				            	</div>
		                    	<div class="form-group input-group-sm col-xs-6">
				            		<div class="form-group input-group-sm">
			                            <label for="opcion4" class="translate" data-traducir_english="Option 4" data-traducir_spanish="Opción 4">Opción 4</label>
			                            <input type="text"class="form-control letras input"  id="opcion4" name="opcion4"> 
			                        </div>
				            	</div>
		                    	<div class="form-group input-group-sm col-xs-6">
				            		<div class="form-group input-group-sm">
			                            <label for="valor4" class="translate" data-traducir_english="Value 4" data-traducir_spanish="Valor 4">Valor 4</label>
			                            <input type="text"class="form-control monto input"  id="valor4" name="valor4"> 
			                        </div>
				            	</div>
		                    	<div class="form-group input-group-sm col-xs-6">
				            		<div class="form-group input-group-sm">
			                            <label for="opcion5" class="translate" data-traducir_english="Option 5" data-traducir_spanish="Opción 5">Opción 5</label>
			                            <input type="text"class="form-control letras input"  id="opcion5" name="opcion5"> 
			                        </div>
				            	</div>
		                    	<div class="form-group input-group-sm col-xs-6">
				            		<div class="form-group input-group-sm">
			                            <label for="valor5" class="translate" data-traducir_english="Value 5" data-traducir_spanish="Valor 5">Valor 5</label>
			                            <input type="text"class="form-control monto input"  id="valor5" name="valor5"> 
			                        </div>
				            	</div>
		                    	<div class="form-group input-group-sm col-xs-6">
				            		<div class="form-group input-group-sm">
			                            <label for="opcion6" class="translate" data-traducir_english="Option 6" data-traducir_spanish="Opción 6">Opción 6</label>
			                            <input type="text"class="form-control letras input"  id="opcion6" name="opcion6"> 
			                        </div>
				            	</div>
		                    	<div class="form-group input-group-sm col-xs-6">
				            		<div class="form-group input-group-sm">
			                            <label for="valor6" class="translate" data-traducir_english="Value 6" data-traducir_spanish="Valor 6">Valor 6</label>
			                            <input type="text"class="form-control monto input"  id="valor6" name="valor6"> 
			                        </div>
				            	</div>
		                    	<div class="form-group input-group-sm col-xs-6">
				            		<div class="form-group input-group-sm">
			                            <label for="opcion7" class="translate" data-traducir_english="Option 7" data-traducir_spanish="Opción 7">Opción 7</label>
			                            <input type="text"class="form-control letras input"  id="opcion7" name="opcion7"> 
			                        </div>
				            	</div>
		                    	<div class="form-group input-group-sm col-xs-6">
				            		<div class="form-group input-group-sm">
			                            <label for="valor7" class="translate" data-traducir_english="Value 7" data-traducir_spanish="Valor 7">Valor 7</label>
			                            <input type="text"class="form-control monto input"  id="valor7" name="valor7"> 
			                        </div>
				            	</div>
		                    	<div class="form-group input-group-sm col-xs-6">
				            		<div class="form-group input-group-sm">
			                            <label for="opcion8" class="translate" data-traducir_english="Option 8" data-traducir_spanish="Opción 8">Opción 8</label>
			                            <input type="text"class="form-control letras input"  id="opcion8" name="opcion8"> 
			                        </div>
				            	</div>
		                    	<div class="form-group input-group-sm col-xs-6">
				            		<div class="form-group input-group-sm">
			                            <label for="valor8" class="translate" data-traducir_english="Value 8" data-traducir_spanish="Valor 8">Valor 8</label>
			                            <input type="text"class="form-control monto input"  id="valor8" name="valor8"> 
			                        </div>
				            	</div>
		                    	<div class="form-group input-group-sm col-xs-6">
				            		<div class="form-group input-group-sm">
			                            <label for="opcion9" class="translate" data-traducir_english="Option 9" data-traducir_spanish="Opción 9">Opción 9</label>
			                            <input type="text"class="form-control letras input"  id="opcion9" name="opcion9"> 
			                        </div>
				            	</div>
		                    	<div class="form-group input-group-sm col-xs-6">
				            		<div class="form-group input-group-sm">
			                            <label for="valor9" class="translate" data-traducir_english="Value 9" data-traducir_spanish="Valor 9">Valor 9</label>
			                            <input type="text"class="form-control monto input"  id="valor9" name="valor9"> 
			                        </div>
				            	</div>
		                    	<div class="form-group input-group-sm col-xs-6">
				            		<div class="form-group input-group-sm">
			                            <label for="opcion10" class="translate" data-traducir_english="Option 10" data-traducir_spanish="Option 10">Opción 10</label>
			                            <input type="text"class="form-control letras input"  id="opcion10" name="opcion10"> 
			                        </div>
				            	</div>
		                    	<div class="form-group input-group-sm col-xs-6">
				            		<div class="form-group input-group-sm">
			                            <label for="valor10" class="translate" data-traducir_english="Value 10" data-traducir_spanish="Valor 10">Valor 10</label>
			                            <input type="text"class="form-control monto input"  id="valor10" name="valor10"> 
			                        </div>
				            	</div>
		                    </div>
			            </div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-sm btn-primary btn_guardar translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_guardar_item">Guardar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade modal_editar_item" id="modal_editar_item" tabindex="-1" role="dialog" aria-labelledby="ModalResponsables" aria-hidden="true">
		<div class="modal-dialog modal-success modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title"><span id="titulo_modal_responsables">Item</span> <small class="subtitulo" id="subtitulo_modal_responsables"></small></h4>
				</div>
				<div class="modal-body">
					<div class="row">
                    	<div class="form-group input-group-sm col-xs-12">
		            		<div class="form-group input-group-sm">
	                            <label for="nombre_editar_item" class="translate" data-traducir_english="Name" data-traducir_spanish="Nombre">Nombre</label>
	                            <input type="text"class="form-control letras input requerido_modal_editar_item"  id="nombre_editar_item" name="nombre_editar_item"> 
	                        </div>
		            	</div>

					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-sm btn-primary btn_guardar translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_editar_item">Guardar</button>
				</div>
			</div>
		</div>
	</div>
</body>