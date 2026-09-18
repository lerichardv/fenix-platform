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

$INGREDIENTES = $DB_INV->inv_listado_ingredientes_activos();

$cod_ingrediente_activo = $_POST['cod_ingrediente_activo'];
if (!isset($_POST['cod_ingrediente_activo'])) {
	$cod_ingrediente_activo = 0;
}
$INGREDIENTE = $DB_INV->inv_obtener_info_ingrediente_activo($cod_ingrediente_activo);
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
		codigo_ingrediente_activo = 0;
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
		//Máscaras
	    //$('.monto').mask("#,##0.000", {reverse: false, maxlength: false});
	    $('.monto').mask("9999999.999");
	    $('.letras').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z-¡!¿?,.()% 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/, optional: false}}});

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
	    if (count($INGREDIENTE)) 
	    {
	    	?>
	    	$('#ingrediente_activo').val("<?php echo utf8_encode($INGREDIENTE[0]['ingrediente_activo']); ?>");
	    	<?php
	    }
	    ?>
	    codigo_ingrediente_activo = <?php echo $cod_ingrediente_activo;?>;
	    $('#modal_loading').modal('hide');
	    jQuery.ajaxSetup({async:true});
	});

	$('#btn_guardar_ingrediente').click(function(event) {
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
                inv_guardar_ingrediente_activo(codigo_ingrediente_activo);
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
		inv_cambiar_estado_ingrediente_activo($(this).data('id'),($(this).attr('checked') ? 0 : 1));
	});
</script>

<body>
	<div id="overlay_loading"></div>
	<div id="message_box"></div>	
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="panel-header">
	                <h1 class="translate" data-traducir_english="Active Ingredient" data-traducir_spanish="Ingrediente Activo">Ingrediente Activo</h1>
	            </div>				
			</div>
            <div class="col-md-12">
            	<div class="row">
	            	<div class="col-md-12">
	            		<div class="form-group input-group-sm" id="div_nombre_capacitacion">
                            <label for="ingrediente_activo" class="translate" data-traducir_english="Ingredient" data-traducir_spanish="Ingrediente">Ingrediente</label>
                            <input type="text"class="form-control letras input requerido requerido_nueva_capacitacion"  id="ingrediente_activo" name="ingrediente_activo"> 
                        </div>
	            	</div>
				</div>
            </div>
		</div> 
		<div class="panel-footer" align="right">
			<button class="btn btn-sm btn-primary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" type="button" id="btn_guardar_ingrediente">Guardar</button>
		</div>               
	</div>
	<div class="busqueda_contenedor">
	    <div class="row" style="min-width:100px;">
	        <div class="col-sm-12">
	                <div class="panel panel-primary" style="overflow:auto;">
	                <div class="panel-heading panel_cabecera"> 
	                        <h3 class="panel-title translate" data-traducir_english="Active Ingredients List" data-traducir_spanish="Listado de Ingredientes Activos">Listado de Ingredientes Activos</h3>		
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
	                                        <th width="90%" class="translate" data-traducir_english="Active Ingredient" data-traducir_spanish="Ingrediente Activo">Ingrediente Activo</th>
	                                        <th width="5%" class="translate" data-traducir_english="Active" data-traducir_spanish="Activo">Activo</th>
	                                </tr>
	                        </thead>
	                        <tbody id="cuerpo_tabla">
	                            <?php
	                            if(count($INGREDIENTES))
	                            {
	                            	$correlativo = 1;
	                            	foreach($INGREDIENTES as $ingrediente)
	                            	{
	                            		?>
	                            		<tr>
	                            			<td class="hide"><?php echo utf8_encode($ingrediente['cod_ingrediente_activo']);?></td>
	                            			<td onclick="inv_vista_ingrediente_activo(<?php echo utf8_encode($ingrediente['cod_ingrediente_activo']);?>)"><?php echo $correlativo;?></td>
	                            			<td onclick="inv_vista_ingrediente_activo(<?php echo utf8_encode($ingrediente['cod_ingrediente_activo']);?>)"><?php echo utf8_encode($ingrediente['ingrediente_activo']);?></td>
	                            			<td>	                            				
	                            				<div class="material-switch pull-right">
						                            <input class="checkbox" id="checkbox_<?php echo utf8_encode($ingrediente['cod_ingrediente_activo']);?>" data-id="<?php echo utf8_encode($ingrediente['cod_ingrediente_activo']);?>" name="checkbox_<?php echo utf8_encode($ingrediente['cod_ingrediente_activo']);?>" type="checkbox" <?php echo ($ingrediente['activo'] == 1 ? 'checked="checked"':'');?>/>
						                            <label for="checkbox_<?php echo utf8_encode($ingrediente['cod_ingrediente_activo']);?>" class=""></label>
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