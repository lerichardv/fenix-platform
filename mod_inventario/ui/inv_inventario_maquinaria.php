<?php
/*
* 	Registro de información de las sembradoras,
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

$inicio = $_POST['inicio'];
if (!isset($_POST['inicio'])) {
	$inicio = 0;
}
$limite = $_POST['limite'];
if (!isset($_POST['limite'])) {
	$limite = 50;
}
//$MAQUINARIAS = $DB_INV->inv_listado_maquinaria();
$MAQUINARIAS = $DB_INV->inv_listado_maquinaria_por_granjas($cod_granjas_usuario);
//$MAQUINARIAS 	= $DB_INV->inv_listado_maquinaria_por_granjas_paginacion($cod_granjas_usuario,$inicio,$limite);
$TOTAL 			= $DB_INV->inv_total_maquinaria_por_granjas($cod_granjas_usuario);

$cod_maquinaria = $_POST['cod_maquinaria'];
if (!isset($_POST['cod_maquinaria'])) {
	$cod_maquinaria = 0;
}
$MAQUINARIA = $DB_INV->inv_obtener_info_maquinaria($cod_maquinaria);
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
		codigo_inventario_maquinaria = 0;
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
		/*(function(){
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
		});*/
		//Constructores
		conf_constructor_listado_granjas();
		inv_constructor_listado_tipos_aplicacion('cod_tipo_aplicacion');
		bw_constructor_estados_plantaciones();

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
            }*/
        });
		//Máscaras
	    //$('.monto').mask("#,##0.000", {reverse: false, maxlength: false});
	    $('.monto').mask("9999999.999");
	    $('.anio').mask("9999");
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
	    <?php
	    if (count($MAQUINARIA))
	    {
	    	?>
	    	$('#codigo_maquinaria').val("<?php echo utf8_encode($MAQUINARIA[0]['codigo_maquinaria']); ?>");
	    	$('#nombre_maquinaria').val("<?php echo utf8_encode($MAQUINARIA[0]['nombre_maquinaria']); ?>");
	    	$('#anio_vencimiento').val("<?php echo utf8_encode($MAQUINARIA[0]['anio_vencimiento']); ?>");
	    	$('#precio_unidad').val("<?php echo utf8_encode($MAQUINARIA[0]['precio_unidad']); ?>");
	    	$('#cod_info_empresa').selectpicker('val',"<?php echo utf8_encode($MAQUINARIA[0]['cod_info_empresa']); ?>");
	    	$('#cod_tipo_aplicacion').selectpicker('val',"<?php echo utf8_encode($MAQUINARIA[0]['cod_tipo_aplicacion']); ?>");
	    	$('#cod_estado_plantacion').selectpicker('val',"<?php echo utf8_encode($MAQUINARIA[0]['cod_estado_plantacion']); ?>");
	    	$('.selectpicker').selectpicker('refresh');
	    	<?php
	    }
	    ?>
	    codigo_inventario_maquinaria = <?php echo $cod_maquinaria;?>;
	    $('#modal_loading').modal('hide');
	    jQuery.ajaxSetup({async:true});
	});

	$('#btn_guardar_inventario').click(function(event) {
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
                inv_guardar_inventario_maquinaria(codigo_inventario_maquinaria);
            });
        }
        else
        {
            grl_mensaje('Debe llenar todos los campos marcados','favor verificar','warning');
        }
     });
	$('#cuerpo_tabla').on('change', '.checkbox', function(event) {
		event.preventDefault();
		event.stopPropagation();
		inv_cambiar_estado_inventario_maquinaria($(this).data('id'),($(this).attr('checked') ? 0 : 1));
	});
	/*$('.checkbox').change(function(event) {
		event.preventDefault();
		event.stopPropagation();
		inv_cambiar_estado_inventario_maquinaria($(this).data('id'),($(this).attr('checked') ? 0 : 1));
	});*/
	$('#div_paginacion').on('click', '.paginate_button', function(event) {
		event.preventDefault();
		jQuery.ajaxSetup({async:false});
		inv_vista_listado_maquinaria($(this).data('inicio'),$(this).data('limite'));
		$('.current').removeClass('current');
		$(this).addClass('current');
		jQuery.ajaxSetup({async:true});
	});
</script>

<body>
	<div id="overlay_loading"></div>
	<div id="message_box"></div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="panel-header">
	                <h1 class="translate" data-traducir_english="Machinery" data-traducir_spanish="Maquinaria">Maquinaria</h1>
	            </div>
			</div>
            <div class="col-md-12">
            	<div class="row">
					<div class="col-md-3">
	                    <div class="form-group input-group-sm" id="div_tipo_curso">
	                        <label for="cod_info_empresa" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</label>
	                        <select class="selectpicker show-menu-arrow requerido" data-live-search="true" title="Select" id="cod_info_empresa" name="cod_info_empresa">
	                        </select>
	                    </div>
	            	</div>
	            	<div class="col-md-3">
	            		<div class="form-group input-group-sm" id="div_nombre_capacitacion">
                            <label for="codigo_maquinaria" class="translate" data-traducir_english="Product Code" data-traducir_spanish="Código">Código</label>
                            <input type="text"class="form-control letras input requerido"  id="codigo_maquinaria" name="codigo_maquinaria">
                        </div>
	            	</div>
	            	<div class="col-md-6">
	            		<div class="form-group input-group-sm" id="div_nombre_capacitacion">
                            <label for="nombre_maquinaria" class="translate" data-traducir_english="Product Name" data-traducir_spanish="Nombre">Nombre</label>
                            <input type="text"class="form-control letras input requerido"  id="nombre_maquinaria" name="nombre_maquinaria">
                        </div>
	            	</div>
				</div>
            	<div class="row">
					<div class="col-md-3">
	                    <div class="form-group input-group-sm" id="div_tipo_curso">
	                        <label for="cod_tipo_aplicacion" class="translate" data-traducir_english="Aplication Type" data-traducir_spanish="Tipo Aplicación">Tipo Aplicación</label>
	                        <select class="selectpicker show-menu-arrow requerido" data-live-search="true" title="Select" id="cod_tipo_aplicacion" name="cod_tipo_aplicacion">
	                        </select>
	                    </div>
	            	</div>
	            	<div class="col-md-3">
	            		<div class="form-group input-group-sm" id="div_nombre_capacitacion">
                            <label for="precio_unidad" class="translate" data-traducir_english="Price per Unit" data-traducir_spanish="Precio Unidad">Precio Unidad</label>
                            <input type="text"class="form-control monto input"  id="precio_unidad" name="precio_unidad">
                        </div>
	            	</div>
	            	<div class="col-md-3">
	            		<div class="form-group input-group-sm" id="div_nombre_capacitacion">
                            <label for="anio_vencimiento" class="translate" data-traducir_english="Expiration Year" data-traducir_spanish="Año Vencimiento">Año Vencimiento</label>
                            <input type="text"class="form-control anio input"  id="anio_vencimiento" name="anio_vencimiento">
                        </div>
	            	</div>
					<div class="col-md-3">
	                    <div class="form-group input-group-sm" id="div_tipo_curso">
	                        <label for="cod_estado_plantacion" class="translate" data-traducir_english="Plant Cycle Stage" data-traducir_spanish="Estado Plantación">Estado Plantación</label>
	                        <select class="selectpicker show-menu-arrow requerido" data-live-search="true" title="Select" id="cod_estado_plantacion" name="cod_estado_plantacion">
	                        </select>
	                    </div>
	            	</div>
				</div>
            </div>
		</div>
		<div class="panel-footer" align="right">
			<button class="btn btn-sm btn-primary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" type="button" id="btn_guardar_inventario">Guardar</button>
		</div>
	</div>
	<div class="busqueda_contenedor">
	    <div class="row" style="min-width:100px;">
	        <div class="col-sm-12">
	            <!-- <div class="panel panel-primary" style="overflow:auto;">
	                <div class="panel-heading panel_cabecera">
	                        <h3 class="panel-title translate" data-traducir_english="Machinery Inventory" data-traducir_spanish="Inventario de Maquinaria">Inventario de Maquinaria</h3>
							<div class="pull-right">
								<span class="clickable filter" data-toggle="tooltip" title="Realizar busqueda" data-container="body">
									<i class="fa fa-search"></i>
								</span>
							</div>
	                </div>
					<div class="panel-body panel_cuerpo input-group-sm">
						<input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Busqueda" />
					</div> -->
					<h3 class="display-4 titulo translate" align="center" data-traducir_english="Machinery Inventory" data-traducir_spanish="Inventario de Maquinaria">Inventario de Maquinaria</h3>
	                <div style="overflow-x:auto;">
	                    <table class="table table-striped table-hover table-sm" id="dev-table" >
	                        <thead>
	                                <tr class="active info">
	                                        <th width="1%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
	                                        <th width="10%" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</th>
	                                        <th width="20%" class="translate" data-traducir_english="Product Code" data-traducir_spanish="Código">Código</th>
	                                        <th width="30%" class="translate" data-traducir_english="Product Name" data-traducir_spanish="Nombre">Nombre</th>
	                                        <th width="20%" class="translate" data-traducir_english="Aplication Type" data-traducir_spanish="Tipo Aplicación">Tipo Aplicación</th>
	                                        <th width="20%" class="translate" data-traducir_english="Price per Unit" data-traducir_spanish="Precio">Precio</th>
	                                        <th width="10%"></th>
	                                </tr>
	                        </thead>
	                        <tbody id="cuerpo_tabla">
	                            <?php
	                            if(count($MAQUINARIAS))
	                            {
	                            	$correlativo = 1;
	                            	foreach($MAQUINARIAS as $maquinaria)
	                            	{
	                            		?>
	                            		<tr>
	                            			<td onclick="inv_vista_inventario_maquinaria(<?php echo utf8_encode($maquinaria['cod_inventario']);?>)"><?php echo $correlativo;?></td>
	                            			<td onclick="inv_vista_inventario_maquinaria(<?php echo utf8_encode($maquinaria['cod_inventario']);?>)"><?php echo utf8_encode($maquinaria['nombre_empresa']);?></td>
	                            			<td onclick="inv_vista_inventario_maquinaria(<?php echo utf8_encode($maquinaria['cod_inventario']);?>)"><?php echo utf8_encode($maquinaria['codigo_maquinaria']);?></td>
	                            			<td onclick="inv_vista_inventario_maquinaria(<?php echo utf8_encode($maquinaria['cod_inventario']);?>)"><?php echo utf8_encode($maquinaria['nombre_maquinaria']);?></td>
	                            			<td onclick="inv_vista_inventario_maquinaria(<?php echo utf8_encode($maquinaria['cod_inventario']);?>)"><?php echo utf8_encode($maquinaria['tipo_aplicacion']);?></td>
	                            			<td onclick="inv_vista_inventario_maquinaria(<?php echo utf8_encode($maquinaria['cod_inventario']);?>)"><?php echo utf8_encode($maquinaria['precio_unidad']);?></td>
	                            			<td>
	                            				<div class="material-switch pull-right">
						                            <input class="checkbox" id="checkbox_<?php echo utf8_encode($maquinaria['cod_inventario']);?>" data-id="<?php echo utf8_encode($maquinaria['cod_inventario']);?>" name="checkbox_<?php echo utf8_encode($maquinaria['cod_inventario']);?>" type="checkbox" <?php echo ($maquinaria['activo'] == 1 ? 'checked="checked"':'');?>/>
						                            <label for="checkbox_<?php echo utf8_encode($maquinaria['cod_inventario']);?>" class=""></label>
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
	                </div>
	            </div> -->
	        </div>
	    </div>
	</div>
</body>