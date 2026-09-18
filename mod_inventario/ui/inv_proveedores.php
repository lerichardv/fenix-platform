<?php 
/*
* 	Registro de información de los formularios
* 	@author 		Jairo Bonilla
* 	@date 			2015-07-28
*/
session_start();
if(!isset($_SESSION['cod_usuario'])){
	echo "<script>window.location.href = '" . $_SESSION['úrl_inicio'] . "';</script>"; // Usando JS para redireccionar
	// header('Location: index.php'); // Método anterior (No funcional)
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php"); 
include_once("../../libs/db_classes/db_inventario.php"); 
/*INSTANCIAMIENTOS*/
$DB_INV = new db_inventario();

$cod_granjas_usuario = str_replace(['[','"',']'], '', $_SESSION['cod_info_empresa']);

//$PROVEEDORES = $DB_INV->inv_listado_proveedores();
$PROVEEDORES = $DB_INV->inv_listado_proveedores_por_granjas($cod_granjas_usuario);

$cod_proveedor = $_POST['cod_proveedor'];
if (!isset($_POST['cod_proveedor'])) {
	$cod_proveedor = 0;
}
$PROVEEDOR = $DB_INV->inv_obtener_info_proveedor($cod_proveedor);
$PRODUCTOS = $DB_INV->inv_obtener_listado_productos_proveedor($cod_proveedor);
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

	$(document).ready(function() {
		codigo_proveedor = 0;
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
	    //$('.monto').mask("#,##0.000", {reverse: false, maxlength: false});
	    $('.monto').mask("9999999.999");
	    $('.letras').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z-¡!¿?,.() 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/, optional: false}}});
	    $('.telefono').mask('(999) 999-9999');
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
	    $( "#correo_contacto" ).on('blur',function(event) {
	        if( !grl_validarEmail($(this).val())) {
	                $(this).val('');
	                grl_mensaje('Correo electrónico no valido. ', 'Favor verificar.', 'danger');
	        }
	    });
	    <?php
	    if (count($PROVEEDOR)) 
	    {
	    	?>
	    	$('#nombre_empresa').val("<?php echo utf8_encode($PROVEEDOR[0]['nombre_empresa']); ?>");
	    	$('#nombre_contacto').val("<?php echo utf8_encode($PROVEEDOR[0]['nombre_contacto']); ?>");
	    	$('#correo_contacto').val("<?php echo utf8_encode($PROVEEDOR[0]['correo_contacto']); ?>");
	    	$('#telefono_contacto').val("<?php echo utf8_encode($PROVEEDOR[0]['telefono_contacto']); ?>");
	    	$('#observaciones').val("<?php echo utf8_encode($PROVEEDOR[0]['observaciones']); ?>");
	    	$('#cod_info_empresa').selectpicker('val',"<?php echo utf8_encode($PROVEEDOR[0]['cod_info_empresa']); ?>");	    	
	    	$('.selectpicker').selectpicker('refresh');
	    	<?php
	    }
	    ?>
	    codigo_proveedor = <?php echo $cod_proveedor;?>;
	    $('#modal_loading').modal('hide');
	    jQuery.ajaxSetup({async:true});
	});

	$('#btn_guardar_proveedor').click(function(event) {
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
                inv_guardar_proveedor(codigo_proveedor);
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
		inv_cambiar_estado_proveedor($(this).data('id'),($(this).attr('checked') ? 0 : 1));
	});

	$('.checkbox_producto').change(function(event) {
		/* Act on the event */
		event.preventDefault();
		event.stopPropagation();
		inv_cambiar_estado_producto($(this).data('id'),($(this).attr('checked') ? 0 : 1), codigo_proveedor);
	});

	$('#btn_add_producto').click(function(event) {
		/* Act on the event */
		$('#modal_producto').modal('show');
		inv_constructor_listado_quimicos('cod_inventario_quimico',$('#cod_info_empresa').val());
		inv_constructor_listado_maquinarias('cod_inventario_maquinaria',$('#cod_info_empresa').val());
		inv_constructor_listado_semillas('cod_inventario_semilla',$('#cod_info_empresa').val());
	});	

	$('#btn_guardar_producto').click(function(event) {
         /* Act on the event */
        if (($('#cod_inventario_quimico').val() != null && $('#cod_inventario_quimico').val() != '-b' && $('#cod_inventario_quimico').val() != '')
        	||
        	($('#cod_inventario_maquinaria').val() != null && $('#cod_inventario_maquinaria').val() != '-b' && $('#cod_inventario_maquinaria').val() != '')
        	||
        	($('#cod_inventario_semilla').val() != null && $('#cod_inventario_semilla').val() != '-b' && $('#cod_inventario_semilla').val() != '')) 
        {
        	$('#modal_producto').modal('hide');
            grl_overlay_loading('');
            $('#modal_loading').modal('hide');
            $('#modal_loading').on('hidden.bs.modal', function () {            
                inv_guardar_producto(codigo_proveedor);
            });
        }
        else
        {
            grl_mensaje('Debe seleccionar al menos un producto','favor verificar','warning');
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
	                <h1 class="translate" data-traducir_english="Vendor" data-traducir_spanish="Proveedor">Proveedor</h1>
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
                            <label for="nombre_empresa" class="translate" data-traducir_english="Company Name" data-traducir_spanish="Nombre Empresa">Nombre Empresa</label>
                            <input type="text"class="form-control letras input requerido"  id="nombre_empresa" name="nombre_empresa"> 
                        </div>
	            	</div>
	            	<div class="col-md-4">
	            		<div class="form-group input-group-sm">
                            <label for="nombre_contacto" class="translate" data-traducir_english="Contact Name" data-traducir_spanish="Nombre Contacto">Nombre Contacto</label>
                            <input type="text"class="form-control letras input requerido"  id="nombre_contacto" name="nombre_contacto"> 
                        </div>
	            	</div>
				</div>
				<div class="row">					
	            	<div class="col-md-4">
	            		<div class="form-group input-group-sm">
                            <label for="correo_contacto" class="translate" data-traducir_english="Contact Email" data-traducir_spanish="Correo Contacto">Correo Contacto</label>
                            <input type="text"class="form-control email input requerido"  id="correo_contacto" name="correo_contacto"> 
                        </div>
	            	</div>
	            	<div class="col-md-4">
	            		<div class="form-group input-group-sm">
                            <label for="telefono_contacto" class="translate" data-traducir_english="Contact Phone No" data-traducir_spanish="Teléfono Contacto">Teléfono Contacto</label>
                            <input type="text"class="form-control telefono input"  id="telefono_contacto" name="telefono_contacto"> 
                        </div>
	            	</div>
	            	<div class="col-md-4">
	            		<div class="form-group input-group-sm">
                            <label for="observaciones" class="translate" data-traducir_english="Observation" data-traducir_spanish="Observación">Observación</label>
                            <input type="text"class="form-control letras input"  id="observaciones" name="observaciones"> 
                        </div>
	            	</div>
				</div>
				<div class="row">
	            	<div class="col-md-12">
	            		<div class="panel panel-primary" id="panel_itemschecklist">	            			 
		                    <div class="panel-heading btncollapsepaso"id="a_itemschecklist" role="button" data-toggle="collapse" href="#collapseitemschecklist" aria-expanded="true" data-parent="#accordion" aria-controls="collapseitemschecklist">
		                        <h3 class="panel-title translate" data-traducir_english='Vendor Products' data-traducir_spanish='Productos del Proveedor'>Productos del Proveedor</h3>
		                    </div>
		                    <div class="panel-collapse collapse" id="collapseitemschecklist" role="tabpanel" aria-labelledby="collapseitemschecklist">
		                        <div class="panel-body">
		                            <div class="responsive_table_container">
		                                <table class="table display row-border responsive" id="tabla_itemschecklist">
		                                    <thead>
		                                         <tr class="active info">
		                                            <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
		                                            <th width="20%" class="translate" data-traducir_english="Product Code" data-traducir_spanish="Código">Código</th>
		                                            <th width="20%" class="translate" data-traducir_english="Product Name" data-traducir_spanish="Nombre">Nombre</th>
		                                            <th width="20%" class="translate" data-traducir_english="Product Type" data-traducir_spanish="Tipo">Tipo</th>
		                                            <th width="10%" class="translate" data-traducir_english="Active" data-traducir_spanish="Activo">Activo</th>
		                                         </tr>
		                                    </thead>
		                                    <tbody>
				                            <?php
				                            if(count($PRODUCTOS))
				                            {
				                            	$correlativo = 1;
				                            	foreach($PRODUCTOS as $producto)
				                            	{
				                            		// if($producto['flag_tipo_inventario'] == 1) //Producto Químico
				                            		// {
					                            		?>
					                            		<!-- <tr>
					                            			<td><?php echo $correlativo;?></td>
					                            			<td><?php echo utf8_encode($producto['cod_quimico']);?></td>
					                            			<td><?php echo utf8_encode($producto['nombre_quimico']);?></td>
					                            			<td>Chemical - Químico</td>
					                            			<td>	                            				
					                            				<div class="material-switch pull-right">
										                            <input class="checkbox_producto" id="checkboxp_<?php echo utf8_encode($producto['cod_detalle']);?>" data-id="<?php echo utf8_encode($producto['cod_detalle']);?>" name="checkboxp_<?php echo utf8_encode($producto['cod_detalle']);?>" type="checkbox" <?php echo ($producto['activo'] == 1 ? 'checked="checked"':'');?>/>
										                            <label for="checkboxp_<?php echo utf8_encode($producto['cod_detalle']);?>" class=""></label>
										                        </div>
					                            			</td>
					                            		</tr> -->
					                            		<?php
					                            	// }
					                            	// if($producto['flag_tipo_inventario'] == 0) //Producto Maquinaría
					                            	// {
					                            		?>
					                            		<!-- <tr>
					                            			<td><?php echo $correlativo;?></td>
					                            			<td><?php echo utf8_encode($producto['codigo_maquinaria']);?></td>
					                            			<td><?php echo utf8_encode($producto['nombre_maquinaria']);?></td>
					                            			<td>Machinery - Maquinaria</td>
					                            			<td>	                            				
					                            				<div class="material-switch pull-right">
										                            <input class="checkbox_producto" id="checkboxp_<?php echo utf8_encode($producto['cod_detalle']);?>" data-id="<?php echo utf8_encode($producto['cod_detalle']);?>" name="checkboxp_<?php echo utf8_encode($producto['cod_detalle']);?>" type="checkbox" <?php echo ($producto['activo'] == 1 ? 'checked="checked"':'');?>/>
										                            <label for="checkboxp_<?php echo utf8_encode($producto['cod_detalle']);?>" class=""></label>
										                        </div>
					                            			</td>
					                            		</tr> -->
					                            		<?php					                            		
					                            	// }
					                            	if($producto['flag_tipo_inventario'] == 2) //Producto Semilla
					                            	{
					                            		?>
					                            		<tr>
					                            			<td><?php echo $correlativo;?></td>
					                            			<td><?php echo utf8_encode($producto['codigo_semilla']);?></td>
					                            			<td><?php echo utf8_encode($producto['nombre_semilla']);?></td>
					                            			<td>Seed - Semilla</td>
					                            			<td>	                            				
					                            				<div class="material-switch pull-right">
										                            <input class="checkbox_producto" id="checkboxp_<?php echo utf8_encode($producto['cod_detalle']);?>" data-id="<?php echo utf8_encode($producto['cod_detalle']);?>" name="checkboxp_<?php echo utf8_encode($producto['cod_detalle']);?>" type="checkbox" <?php echo ($producto['activo'] == 1 ? 'checked="checked"':'');?>/>
										                            <label for="checkboxp_<?php echo utf8_encode($producto['cod_detalle']);?>" class=""></label>
										                        </div>
					                            			</td>
					                            		</tr>
					                            		<?php					                            		
					                            	}
				                            		$correlativo++;
				                            	}
				                            }
				                            ?>
		                                    </tbody>
		                                 </table>
		                            </div>
		                            <div class="row nomargin row-add-content">
		                                <div class="col-xs-12 text-center">
		                                    <button type="button" id="btn_add_producto" class="btn btn-sm btn-success truncated-text btn_add" data-action="true">
		                                        <i class="fa fa-plus-circle"> </i>
		                                        <span class="translate" data-traducir_english="Add Product" data-traducir_spanish="Añadir Producto">Añadir Producto</span>
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
			<button class="btn btn-sm btn-primary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" type="button" id="btn_guardar_proveedor">Guardar</button>
		</div>               
	</div>	
	<div class="busqueda_contenedor">
	    <div class="row" style="min-width:100px;">
	        <div class="col-sm-12">
	                <div class="panel panel-primary" style="overflow:auto;">
	                <div class="panel-heading panel_cabecera"> 
	                        <h3 class="panel-title translate" data-traducir_english="Vendors List" data-traducir_spanish="Listado de Proveedores">Listado de Proveedores</h3>		
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
	                                        <th width="20%" class="translate" data-traducir_english="Company Name" data-traducir_spanish="Empresa">Empresa</th>
	                                        <th width="20%" class="translate" data-traducir_english="Contact" data-traducir_spanish="Contacto">Contacto</th>
	                                        <th width="20%" class="translate" data-traducir_english="Phone No" data-traducir_spanish="Teléfono">Teléfono</th>
	                                        <th width="20%" class="translate" data-traducir_english="Email" data-traducir_spanish="Correo">Correo</th>
	                                        <th width="10%" class="translate" data-traducir_english="Active" data-traducir_spanish="Activo">Activo</th>
	                                </tr>
	                        </thead>
	                        <tbody id="cuerpo_tabla">
	                            <?php
	                            if(count($PROVEEDORES))
	                            {
	                            	$correlativo = 1;
	                            	foreach($PROVEEDORES as $proveedor)
	                            	{
	                            		?>
	                            		<tr>
	                            			<td onclick="inv_vista_proveedor(<?php echo utf8_encode($proveedor['cod_proveedor']);?>)"><?php echo $correlativo;?></td>
	                            			<td onclick="inv_vista_proveedor(<?php echo utf8_encode($proveedor['cod_proveedor']);?>)"><?php echo utf8_encode($proveedor['nombre_finca']);?></td>
	                            			<td onclick="inv_vista_proveedor(<?php echo utf8_encode($proveedor['cod_proveedor']);?>)"><?php echo utf8_encode($proveedor['nombre_empresa']);?></td>
	                            			<td onclick="inv_vista_proveedor(<?php echo utf8_encode($proveedor['cod_proveedor']);?>)"><?php echo utf8_encode($proveedor['nombre_contacto']);?></td>
	                            			<td onclick="inv_vista_proveedor(<?php echo utf8_encode($proveedor['cod_proveedor']);?>)"><?php echo utf8_encode($proveedor['telefono_contacto']);?></td>
	                            			<td onclick="inv_vista_proveedor(<?php echo utf8_encode($proveedor['cod_proveedor']);?>)"><?php echo utf8_encode($proveedor['correo_contacto']);?></td>
	                            			<td>	                            				
	                            				<div class="material-switch pull-right">
						                            <input class="checkbox" id="checkbox_<?php echo utf8_encode($proveedor['cod_proveedor']);?>" data-id="<?php echo utf8_encode($proveedor['cod_proveedor']);?>" name="checkbox_<?php echo utf8_encode($proveedor['cod_proveedor']);?>" type="checkbox" <?php echo ($proveedor['activo'] == 1 ? 'checked="checked"':'');?>/>
						                            <label for="checkbox_<?php echo utf8_encode($proveedor['cod_proveedor']);?>" class=""></label>
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
	<div class="modal fade modal_producto" id="modal_producto" tabindex="-1" role="dialog" aria-labelledby="ModalResponsables" aria-hidden="true">
		<div class="modal-dialog modal-success modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title"><span id="titulo_modal_responsables" class="translate" data-traducir_english="Product" data-traducir_spanish="Producto">Producto</span> <small class="subtitulo" id="subtitulo_modal_responsables"></small></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<!-- <div class="form-group input-group-sm col-xs-12" id="">
							<label for="cod_inventario_quimico" class="translate" data-traducir_english="Chemical" data-traducir_spanish="Químico">Químico</label>
							<div class="form-group show-tick">
								<select class="selectpicker show-menu-arrow" id="cod_inventario_quimico" name="cod_inventario_quimico" data-live-search="true" title="Select">
								</select>
							</div>
						</div> -->
						<!-- <div class="form-group input-group-sm col-xs-12" id="">
							<label for="cod_inventario_maquinaria" class="translate" data-traducir_english="Machinery" data-traducir_spanish="Maquinaria">Maquinaria</label>
							<div class="form-group show-tick">
								<select class="selectpicker show-menu-arrow" id="cod_inventario_maquinaria" name="cod_inventario_maquinaria" data-live-search="true" title="Select">
								</select>
							</div>
						</div> -->
						<div class="form-group input-group-sm col-xs-12" id="">
							<label for="cod_inventario_semilla" class="translate" data-traducir_english="Seed" data-traducir_spanish="Semilla">Semilla</label>
							<div class="form-group show-tick">
								<select class="selectpicker show-menu-arrow" id="cod_inventario_semilla" name="cod_inventario_semilla" data-live-search="true" title="Select">
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-sm btn-primary btn_guardar translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_guardar_producto">Guardar</button>
				</div>
			</div>
		</div>
	</div>
</body>