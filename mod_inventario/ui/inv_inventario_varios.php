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
//$VARIOS = $DB_INV->inv_listado_articulos_varios();
$VARIOS = $DB_INV->inv_listado_articulos_varios_por_granjas($cod_granjas_usuario);
//$VARIOS = $DB_INV->inv_listado_articulos_varios_por_granjas_paginacion($cod_granjas_usuario,$inicio,$limite);
$TOTAL = $DB_INV->inv_total_articulos_varios_por_granjas($cod_granjas_usuario);

$cod_vario = $_POST['cod_vario'];
if (!isset($_POST['cod_vario'])) {
	$cod_vario = 0;
}
$BITACORA = $DB_INV->inv_obtener_info_articulo_vario_bitacora($cod_vario);
$VARIO = $DB_INV->inv_obtener_info_articulo_vario($cod_vario);
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
	var fecha_hoy   = new Date(<?php echo time()*1000; ?>);
    var hoy         = (fecha_hoy.getMonth()+1) + "/" + fecha_hoy.getDate() + "/" + fecha_hoy.getFullYear();
    //var hoy_18_anios_atras        = (fecha_hoy.getMonth()+1) + "/" + fecha_hoy.getDate() + "/" + (fecha_hoy.getFullYear()-18);
    var hoy_default = fecha_hoy.getFullYear() + "-" + (fecha_hoy.getMonth()+1) + "-" + fecha_hoy.getDate();
	grl_overlay_loading('');

	$(document).ready(function() {
		codigo_inventario_vario = 0;
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
	    $('.date').datetimepicker({
	        //disabledHours: true,
	        //locale: 'es',
	        locale:  moment.locale('en', {
	            week: { dow: 0 }
	        }),
	        //minDate: hoy,
	        //keepOpen: true,
	        format: 'MM-DD-YYYY',
	        defaultDate: hoy,
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
		inv_constructor_listado_unidades_medida();
		$('#dev-table,#dev-table2').DataTable({
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
        });
		//Máscaras
	    $('.monto').mask("#,##0.000", {reverse: true, maxlength: false});
	    $('.num_entero').mask("###0", {reverse: true, maxlength: false});
	    $('.anio').mask("9999");
	    $('.letras').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z-¡!¿?,.() 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/, optional: false}}});
	    $('.orden_compra').mask('SSSSSSSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z-¡!¿?,.() 0-9]/, optional: false}}});

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
	    if (count($VARIO))
	    {
	    	?>
	    	$('#codigo_producto').val("<?php echo utf8_encode($VARIO[0]['codigo_producto']); ?>");
	    	$('#nombre_producto').val("<?php echo utf8_encode($VARIO[0]['nombre_producto']); ?>");
	    	$('#orden_compra').val("<?php echo utf8_encode($VARIO[0]['orden_compra']); ?>");
	    	$('#cantidad_producto').val("<?php echo utf8_encode($VARIO[0]['cantidad_producto']); ?>");
	    	$('#fecha_inventario').val("<?php echo utf8_encode($VARIO[0]['fecha_inventario']); ?>");
	    	$('#cod_info_empresa').selectpicker('val',"<?php echo utf8_encode($VARIO[0]['cod_info_empresa']); ?>");
	    	$('#cod_unidad_medida').selectpicker('val',"<?php echo utf8_encode($VARIO[0]['cod_unidad_medida']); ?>");
	    	$('.selectpicker').selectpicker('refresh');
	    	<?php
	    }
	    ?>
	    codigo_inventario_vario = <?php echo $cod_vario;?>;
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
                inv_guardar_inventario_vario(codigo_inventario_vario);
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
		inv_cambiar_estado_inventario_maquinaria($(this).data('id'),($(this).attr('checked') ? 0 : 1));
	});
	$('#div_paginacion').on('click', '.paginate_button', function(event) {
		event.preventDefault();
		jQuery.ajaxSetup({async:false});
		inv_vista_listado_varios($(this).data('inicio'),$(this).data('limite'));
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
	                <h1 class="translate" data-traducir_english="Item" data-traducir_spanish="Artículo">Artículo</h1>
	            </div>
			</div>
            <div class="col-md-12">
            	<div class="row">
					<div class="col-md-3">
	                    <div class="form-group input-group-sm">
	                        <label for="cod_info_empresa" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</label>
	                        <select class="selectpicker show-menu-arrow requerido" data-live-search="true" title="Select" id="cod_info_empresa" name="cod_info_empresa">
	                        </select>
	                    </div>
	            	</div>
	            	<div class="col-md-3">
	            		<div class="form-group input-group-sm">
                            <label for="codigo_producto" class="translate" data-traducir_english="Product Code" data-traducir_spanish="Código">Código</label>
                            <input type="text"class="form-control num_entero input requerido"  id="codigo_producto" name="codigo_producto">
                        </div>
	            	</div>
	            	<div class="col-md-6">
	            		<div class="form-group input-group-sm">
                            <label for="nombre_producto" class="translate" data-traducir_english="Product Name" data-traducir_spanish="Nombre">Nombre</label>
                            <input type="text"class="form-control letras input requerido"  id="nombre_producto" name="nombre_producto">
                        </div>
	            	</div>
				</div>
            	<div class="row">
	            	<div class="col-md-3">
	            		<div class="form-group input-group-sm">
                            <label for="cantidad_producto" class="translate" data-traducir_english="Quantity" data-traducir_spanish="Cantidad">Cantidad</label>
                            <input type="text"class="form-control num_entero input requerido"  id="cantidad_producto" name="cantidad_producto">
                        </div>
	            	</div>
					<div class="col-md-3">
	                    <div class="form-group input-group-sm">
	                        <label for="cod_unidad_medida" class="translate" data-traducir_english="Unit of Measure" data-traducir_spanish="Unidad de Medida">Unidad de Medida</label>
	                        <select class="selectpicker show-menu-arrow requerido" data-live-search="true" title="Select" id="cod_unidad_medida" name="cod_unidad_medida">
	                        </select>
	                    </div>
	            	</div>
	            	<div class="col-md-3">
	            		<div class="form-group input-group-sm">
                            <label for="orden_compra" class="translate" data-traducir_english="PO#" data-traducir_spanish="Orden Compra">Orden Compra</label>
                            <input type="text"class="form-control orden_compra input"  id="orden_compra" name="orden_compra">
                        </div>
	            	</div>
	            	<div class='col-md-3'>
                        <div class="form-group">
                            <label for="fecha_inventario" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</label>
                            <div class='input-group input-group-sm date' id='datetimepicker2'>
                                <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </span>
                                <input type='text' class="form-control input requerido" id="fecha_inventario" />
                            </div>
                        </div>
                    </div>
				</div>
            </div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="busqueda_contenedor">
				    <div class="row" style="min-width:100px;">
				        <div class="col-sm-12">
				            <!-- <div class="panel panel-primary" style="overflow:auto;">
				                <div class="panel-heading panel_cabecera">
				                        <h3 class="panel-title translate" data-traducir_english="Binnacle" data-traducir_spanish="Bitácora">Bitácora</h3>
										<div class="pull-right">
											<span class="clickable filter" data-toggle="tooltip" title="Realizar busqueda" data-container="body">
												<i class="fa fa-search"></i>
											</span>
										</div>
				                </div>
								<div class="panel-body panel_cuerpo input-group-sm">
									<input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Busqueda" />
								</div> -->
								<h3 class="display-4 titulo translate" align="center" data-traducir_english="Binnacle" data-traducir_spanish="Bitácora">Bitácora</h3>
				                <div style="overflow-x:auto;">
				                    <table class="table table-striped table-hover table-sm" id="dev-table" >
				                        <thead>
				                                <tr class="active info">
				                                        <th width="1%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
				                                        <th width="10%" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</th>
				                                        <th width="20%" class="translate" data-traducir_english="Product Code" data-traducir_spanish="Código">Código</th>
				                                        <th width="30%" class="translate" data-traducir_english="Product Name" data-traducir_spanish="Nombre">Nombre</th>
				                                        <th width="20%" class="translate" data-traducir_english="Quantity" data-traducir_spanish="Cantidad">Cantidad</th>
				                                        <th width="20%" class="translate" data-traducir_english="Unit of Measure" data-traducir_spanish="Unidad de Medida">Unidad de Medida</th>
				                                        <th width="30%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>
				                                        <!-- <th width="10%"></th> -->
				                                </tr>
				                        </thead>
				                        <tbody id="cuerpo_tabla">
				                            <?php
				                            if(count($BITACORA))
				                            {
				                            	$correlativo = 1;
				                            	foreach($BITACORA as $bitacora)
				                            	{
				                            		?>
				                            		<tr>
				                            			<td><?php echo $correlativo;?></td>
				                            			<td><?php echo utf8_encode($bitacora['nombre_empresa']);?></td>
				                            			<td><?php echo utf8_encode($bitacora['codigo_producto']);?></td>
				                            			<td><?php echo utf8_encode($bitacora['nombre_producto']);?></td>
				                            			<td><?php echo utf8_encode($bitacora['cantidad_producto']);?></td>
				                            			<td><?php echo utf8_encode($bitacora['unidad_medida']);?></td>
				                            			<td><?php echo utf8_encode($bitacora['usuario_actualizo']);?></td>
				                            			<!-- <td>
				                            				<div class="material-switch pull-right">
									                            <input class="checkbox" id="checkbox_<?php echo utf8_encode($vario['cod_inventario']);?>" data-id="<?php echo utf8_encode($vario['cod_inventario']);?>" name="checkbox_<?php echo utf8_encode($vario['cod_inventario']);?>" type="checkbox" <?php echo ($vario['activo'] == 1 ? 'checked="checked"':'');?>/>
									                            <label for="checkbox_<?php echo utf8_encode($vario['cod_inventario']);?>" class=""></label>
									                        </div>
				                            			</td> -->
				                            		</tr>
				                            		<?php
				                            		$correlativo++;
				                            	}
				                            }
				                            ?>
				                        </tbody>
				                    </table>
				                </div>
				            <!-- </div> -->
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
	                        <h3 class="panel-title translate" data-traducir_english="Miscellaneous Inventory" data-traducir_spanish="Inventario Varios">Inventario Varios</h3>
							<div class="pull-right">
								<span class="clickable filter" data-toggle="tooltip" title="Realizar busqueda" data-container="body">
									<i class="fa fa-search"></i>
								</span>
							</div>
	                </div>
					<div class="panel-body panel_cuerpo input-group-sm">
						<input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Busqueda" />
					</div> -->
					<h3 class="display-4 titulo translate" align="center" data-traducir_english="Miscellaneous Inventory" data-traducir_spanish="Inventario Varios">Inventario Varios</h3>
	                <div style="overflow-x:auto;">
	                    <table class="table table-striped table-hover table-sm" id="dev-table2" >
	                        <thead>
	                                <tr class="active info">
	                                        <th width="1%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
	                                        <th width="20%" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</th>
	                                        <th width="15%" class="translate" data-traducir_english="Product Code" data-traducir_spanish="Código">Código</th>
	                                        <th width="20%" class="translate" data-traducir_english="Product Name" data-traducir_spanish="Nombre">Nombre</th>
	                                        <th width="15%" class="translate" data-traducir_english="PO#" data-traducir_spanish="Orden de Compra">Orden de Compra</th>
	                                        <th width="15%" class="translate" data-traducir_english="Quantity" data-traducir_spanish="Cantidad">Cantidad</th>
	                                        <th width="20%" class="translate" data-traducir_english="Unit of Measure" data-traducir_spanish="Unidad de Medida">Unidad de Medida</th>
	                                        <!-- <th width="10%"></th> -->
	                                </tr>
	                        </thead>
	                        <tbody id="cuerpo_tabla">
	                            <?php
	                            if(count($VARIOS))
	                            {
	                            	$correlativo = 1;
	                            	foreach($VARIOS as $vario)
	                            	{
	                            		?>
	                            		<tr>
	                            			<td onclick="inv_vista_inventario_vario(<?php echo utf8_encode($vario['cod_inventario']);?>)"><?php echo $correlativo;?></td>
	                            			<td onclick="inv_vista_inventario_vario(<?php echo utf8_encode($vario['cod_inventario']);?>)"><?php echo utf8_encode($vario['nombre_empresa']);?></td>
	                            			<td onclick="inv_vista_inventario_vario(<?php echo utf8_encode($vario['cod_inventario']);?>)"><?php echo utf8_encode($vario['codigo_producto']);?></td>
	                            			<td onclick="inv_vista_inventario_vario(<?php echo utf8_encode($vario['cod_inventario']);?>)"><?php echo utf8_encode($vario['nombre_producto']);?></td>
	                            			<td onclick="inv_vista_inventario_vario(<?php echo utf8_encode($vario['cod_inventario']);?>)"><?php echo utf8_encode($vario['orden_compra']);?></td>
	                            			<td onclick="inv_vista_inventario_vario(<?php echo utf8_encode($vario['cod_inventario']);?>)"><?php echo utf8_encode($vario['cantidad_producto']);?></td>
	                            			<td onclick="inv_vista_inventario_vario(<?php echo utf8_encode($vario['cod_inventario']);?>)"><?php echo utf8_encode($vario['unidad_medida']);?></td>
	                            			<!-- <td>
	                            				<div class="material-switch pull-right">
						                            <input class="checkbox" id="checkbox_<?php echo utf8_encode($vario['cod_inventario']);?>" data-id="<?php echo utf8_encode($vario['cod_inventario']);?>" name="checkbox_<?php echo utf8_encode($vario['cod_inventario']);?>" type="checkbox" <?php echo ($vario['activo'] == 1 ? 'checked="checked"':'');?>/>
						                            <label for="checkbox_<?php echo utf8_encode($vario['cod_inventario']);?>" class=""></label>
						                        </div>
	                            			</td> -->
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
	            </div>
	        </div> -->
	    </div>
	</div>
</body>