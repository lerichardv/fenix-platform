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

$TIPOS = $DB_CONF->conf_listado_tipos_temporada();

$cod_tipo_temporada = $_POST['cod_tipo_temporada'];
if (!isset($_POST['cod_tipo_temporada'])) {
	$cod_tipo_temporada = 0;
}
$TIPO = $DB_CONF->conf_obtener_info_tipo_temporada($cod_tipo_temporada);
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
	 *	Ejecuta el evento onClick en toda la fila enfocada
	 */
	/*var table = document.getElementById("dev-table");
	var rows = table.getElementsByTagName("tr");
	for (i = 1; i < rows.length; i++) {
		var currentRow = table.rows[i];
		if(currentRow.getElementsByTagName("td").length > 1 )
		{
			var createClickHandler = 
				function(row) 
				{
					return function() { 
									   	var cell = row.getElementsByTagName("td")[0];
										var cod_tipo_temporada = cell.innerHTML;
										conf_vista_tipo_temporada(cod_tipo_temporada);
									  };
				};
			currentRow.onclick = createClickHandler(currentRow);
		}
	}*/

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
	//$('#lugar_curso').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z-¡!¿?,.() 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/, optional: false}}});
    $('.letras').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z-¡!¿?,.() 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/, optional: false}}});
	$(document).ready(function() {
		codigo_tipo_temporada = 0;
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
	    if (count($TIPO)) 
	    {
	    	?>
	    	$('#tipo_temporada').val("<?php echo utf8_encode($TIPO[0]['tipo_temporada']); ?>");
	    	<?php
	    }
	    ?>
	    codigo_tipo_temporada = <?php echo $cod_tipo_temporada;?>;
	    grl_traducir_interfaz(($('#checkbox_translate').attr('checked') ? 1 : 0));
	    $('#modal_loading').modal('hide');
	    jQuery.ajaxSetup({async:true});
	});
	$('.checkbox').change(function(event) {
		/* Act on the event */
		event.preventDefault();
		event.stopPropagation();
		conf_cambiar_estado_tipo_temporada($(this).data('id'),($(this).attr('checked') ? 0 : 1));
	});

	$('#btn_guardar_tipo').click(function(event) {
		/* Act on the event */
		var error = 0;
        $(".input.requerido").map(function(){
            if( !$(this).val() ) 
            {
                error = 1;
                $(this).addClass('input-has-error campo-vacio campo-vacio-modal');
                return false;
            } 
            else 
            {
                $(this).removeClass('input-has-error campo-vacio campo-vacio-modal');
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
        	jQuery.ajaxSetup({async:false});
        	grl_overlay_loading('');
        	conf_guardar_tipo_temporada(codigo_tipo_temporada);
        	$('#modal_loading').modal('hide');
            $('#modal_loading').on('hidden.bs.modal', function (e) {
            	conf_vista_tipo_temporada(codigo_tipo_temporada);
            });
            jQuery.ajaxSetup({async:true});
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
			<div class="col-md-6 col-md-offset-3">
				<div class="panel-header">
	                <h1 class="translate" data-traducir_english="Season Type" data-traducir_spanish="Tipo de Temporada">Tipo de Temporada</h1>
	            </div>				
			</div>
            <div class="col-md-6 col-md-offset-3">
            	<div class="row">
	            	<div class="col-md-12">
	            		<div class="form-group input-group-sm" id="div_nombre_capacitacion">
                            <label for="tipo_temporada" class="translate" data-traducir_english="Type" data-traducir_spanish="Tipo">Tipo</label>
                            <input type="text"class="form-control letras input requerido" id="tipo_temporada" name="cod_tipo_temporada"> 
                        </div>
	            	</div>
				</div>
            </div>
		</div> 
		<div class="panel-footer" align="right">
			<button class="btn btn-sm btn-primary translate" type="button" id="btn_guardar_tipo" data-traducir_english="Save" data-traducir_spanish="Guardar">Guardar</button>
		</div>               
	</div>
	<div class="busqueda_contenedor">
	    <div class="row" style="min-width:100px;">
	        <div class="col-sm-12">
	                <div class="panel panel-primary" style="overflow:auto;">
	                <div class="panel-heading panel_cabecera"> 
	                        <h3 class="panel-title translate" data-traducir_english="List of Season Types" data-traducir_spanish="Listado de Tipos de Temporada">Listado de Tipos de Temporada</h3>		
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
	                                        <th width="80%" class="translate" data-traducir_english="Type" data-traducir_spanish="Tipo">Tipo</th>
	                                        <th width="15%"></th>
	                                </tr>
	                        </thead>
	                        <tbody id="cuerpo_tabla">
	                            <?php
	                            if(count($TIPOS))
	                            {
	                            	$correlativo = 1;
	                            	foreach($TIPOS as $tipo)
	                            	{
	                            		?>
	                            		<tr>
	                            			<td class="hide"><?php echo utf8_encode($tipo['cod_tipo_temporada']);?></td>
	                            			<td onclick="conf_vista_tipo_temporada(<?php echo utf8_encode($tipo['cod_tipo_temporada']);?>)"><?php echo $correlativo;?></td>
	                            			<td onclick="conf_vista_tipo_temporada(<?php echo utf8_encode($tipo['cod_tipo_temporada']);?>)"><?php echo utf8_encode($tipo['tipo_temporada']);?></td>
	                            			<td>	                            				
	                            				<div class="material-switch pull-right">
						                            <input class="checkbox" id="checkbox_<?php echo utf8_encode($tipo['cod_tipo_temporada']);?>" data-id="<?php echo utf8_encode($tipo['cod_tipo_temporada']);?>" name="checkbox_<?php echo utf8_encode($tipo['cod_tipo_temporada']);?>" type="checkbox" <?php echo ($tipo['activo'] == 1 ? 'checked="checked"':'');?>/>
						                            <label for="checkbox_<?php echo utf8_encode($tipo['cod_tipo_temporada']);?>" class=""></label>
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