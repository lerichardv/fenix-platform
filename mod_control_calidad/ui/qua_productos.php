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
include_once("../../libs/db_classes/db_control_calidad.php");
/*INSTANCIAMIENTOS*/
$CONTROL = new db_control_calidad();

$PRODUCTOS = $CONTROL->qua_listado_productos_por_pais_departamento($_SESSION['cod_pais'],$_SESSION['cod_departamento']);

$cod_producto = $_POST['cod_producto'];
if (!isset($_POST['cod_producto'])) {
	$cod_producto = 0;
}
$TIPO = $CONTROL->qua_obtener_info_producto($cod_producto);
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
		codigo_producto = 0;
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
	    $('.monto').mask("#,##0.000", {reverse: true, maxlength: false});
	    $('.letras').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z-¡!¿?,.() 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/, optional: false}}});

	    grl_constructor_paises();
	    $( "#cod_pais" ).change(function() {
			$("#cod_departamento").empty();
			grl_constructor_departamentos_por_pais();
		});
		$('#cod_pais').trigger('change');
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
	    	$('#nombre_producto').val("<?php echo utf8_encode($TIPO[0]['nombre_producto']); ?>");
	    	$('#cod_pais').selectpicker('val',"<?php echo utf8_encode($TIPO[0]['cod_pais']); ?>");
	    	$('#cod_pais').trigger('change');
	    	$('#cod_departamento').selectpicker('val',"<?php echo utf8_encode($TIPO[0]['cod_departamento']); ?>");
	    	$('.selectpicker').selectpicker('refresh');
	    	<?php
	    }
	    if (count($PRODUCTOS)) 
	    {
	    	?>
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
		                pageSize: 'Letter',
		                title: 'Products',
		            },
		            'print',
		        ]
	        });
	    	<?php
	    }
	    ?>
	    codigo_producto = <?php echo $cod_producto;?>;
	    $('#modal_loading').modal('hide');
	    jQuery.ajaxSetup({async:true});
	});

	$('#btn_guardar_producto').click(function(event) {
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
                qua_guardar_producto(codigo_producto);
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
		qua_cambiar_estado_producto($(this).data('id'),($(this).attr('checked') ? 0 : 1));
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
	                        <h3 class="panel-title translate" data-traducir_english="Product List" data-traducir_spanish="Listado de Productos">Listado de Productos</h3>
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
	                                        <th width="40%" class="translate" data-traducir_english="Product Name" data-traducir_spanish="Nombre Producto">Nombre Producto</th>
	                                        <th width="15%" class="translate" data-traducir_english="Country" data-traducir_spanish="País">País</th>
	                                        <th width="15%" class="translate" data-traducir_english="State" data-traducir_spanish="Estado">Estado</th>
	                                        <th width="15%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>
	                                        <th width="5%" class="translate" data-traducir_english="Active" data-traducir_spanish="Activo">Activo</th>
	                                </tr>
	                        </thead>
	                        <tbody id="cuerpo_tabla">
	                            <?php
	                            if(count($PRODUCTOS))
	                            {
	                            	$correlativo = 1;
	                            	foreach($PRODUCTOS as $producto)
	                            	{
	                            		?>
	                            		<tr>
	                            			<!-- <td class="hide"><?php echo utf8_encode($producto['cod_producto']);?></td> -->
	                            			<td onclick="qua_vista_producto(<?php echo utf8_encode($producto['cod_producto']);?>)"><?php echo $correlativo;?></td>
	                            			<td onclick="qua_vista_producto(<?php echo utf8_encode($producto['cod_producto']);?>)"><?php echo utf8_encode($producto['nombre_producto']);?></td>
	                            			<td onclick="qua_vista_producto(<?php echo utf8_encode($producto['cod_producto']);?>)"><?php echo utf8_encode($producto['pais']);?></td>
	                            			<td onclick="qua_vista_producto(<?php echo utf8_encode($producto['cod_producto']);?>)"><?php echo utf8_encode($producto['departamento']);?></td>
	                            			<td onclick="qua_vista_producto(<?php echo utf8_encode($producto['cod_producto']);?>)"><?php echo utf8_encode($producto['nombre_usuario']);?></td>
	                            			<td>
	                            				<div class="material-switch pull-right">
						                            <input class="checkbox" id="checkbox_<?php echo utf8_encode($producto['cod_producto']);?>" data-id="<?php echo utf8_encode($producto['cod_producto']);?>" name="checkbox_<?php echo utf8_encode($producto['cod_producto']);?>" type="checkbox" <?php echo ($producto['activo'] == 1 ? 'checked="checked"':'');?>/>
						                            <label for="checkbox_<?php echo utf8_encode($producto['cod_producto']);?>" class=""></label>
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
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="panel-header">
	                <h1 class="translate" data-traducir_english="Product" data-traducir_spanish="Producto">Producto</h1>
	            </div>
			</div>
            <div class="col-md-12">
            	<div class="row">
	            	<div class="col-md-4">
	            		<div class="form-group input-group-sm">
                            <label for="nombre_producto" class="translate" data-traducir_english="Product Name" data-traducir_spanish="Nombre Producto">Nombre Producto</label>
                            <input type="text"class="form-control letras input requerido"  id="nombre_producto" name="nombre_producto">
                        </div>
	            	</div>
	            	<div class="col-md-4">
						<label for="cod_pais" class="translate" data-traducir_english="Country" data-traducir_spanish="País">País</label>
						<div class="form-group show-tick">
							<select class="selectpicker show-menu-arrow requerido" id="cod_pais" name="cod_pais" data-live-search="true" title="Select">
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<label for="cod_departamento" class="translate" data-traducir_english="State" data-traducir_spanish="Estado">Estado</label>
						<div class="form-group show-tick">
							<select class="selectpicker show-menu-arrow requerido" id="cod_departamento" name="cod_departamento" data-live-search="true" title="Select">
							</select>
						</div>
					</div>
				</div>
            </div>
		</div>
		<div class="panel-footer" align="right">
			<button class="btn btn-sm btn-primary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" type="button" id="btn_guardar_producto">Guardar</button>
		</div>
	</div>
</body>