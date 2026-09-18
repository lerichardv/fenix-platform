<?php
/*
* 	Registro de información de los cuartos fríos
* 	@author 		Jairo Bonilla
* 	@date 			2019-01-10
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


//$CONTROLES = $CONTROL->inv_listado_proveedores();
$CONTROLES = $CONTROL->qua_listado_control_calidad_por_pais_departamento($_SESSION['cod_pais'],$_SESSION['cod_departamento']);

$cod_control_calidad = $_POST['cod_control_calidad'];
if (!isset($_POST['cod_control_calidad'])) {
	$cod_control_calidad = 0;
}
$CALIDAD = $CONTROL->qua_obtener_info_control_calidad($cod_control_calidad);
$DETALLES = $CONTROL->qua_obtener_listado_detalle_control_calidad($cod_control_calidad);
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

	$(document).ready(function() {
		codigo_control_calidad = 0;
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
	    $('.time').datetimepicker({
	        //disabledHours: true,
	        locale: 'es',
	        //minDate: hoy,
	        //keepOpen: true,
	        format: 'HH:mm',
	        stepping: 60,
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
		//Constructores
	    grl_constructor_paises();
		//Máscaras
	    $('.monto').mask("#,##0.000", {reverse: true, maxlength: false});
	    $('.letras').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z-¡!¿?,.() 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/, optional: false}}});
	    $('.letras30').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z0-9\-\/]/, optional: false}}});
	    $('.temperatura').mask('9999.99');
	    $('.temperatura_media').mask('9999.9');
	    $('.valor').mask('99.99');
	    $('.dias').mask('999');
	    $( "#cod_pais" ).change(function() {
			$("#cod_departamento").empty();
			grl_constructor_departamentos_por_pais();
		});
		$('#cod_pais').trigger('change');
	    $( "#cod_departamento" ).change(function() {
			$("#cod_producto").empty();
			qua_constructor_listado_productos_por_pais_departamento('cod_producto',$('#cod_pais').val(), $('#cod_departamento').val());
			qua_constructor_listado_cuartos_frios_por_pais_departamento('cod_cuarto_frio',$('#cod_pais').val(), $('#cod_departamento').val());
		});
		$( "#cod_cuarto_frio" ).change(function() {
			$("#cod_seccion").empty();
			qua_constructor_listado_secciones_cuartos_frios('cod_seccion',$('#cod_cuarto_frio').val());
		});
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
	    if (count($CALIDAD))
	    {
	    	?>
	    	jQuery.ajaxSetup({async:false});
	    	$('#fecha').val("<?php echo utf8_encode($CALIDAD[0]['fecha']); ?>");
	    	$('#num_orden_compra').val("<?php echo utf8_encode($CALIDAD[0]['num_orden_compra']); ?>");
	    	$('#tiempo').val("<?php echo utf8_encode($CALIDAD[0]['tiempo']); ?>");
	    	$('#temperatura_actual').val("<?php echo utf8_encode($CALIDAD[0]['temperatura_actual']); ?>");
	    	$('#temperatura_establecida').val("<?php echo utf8_encode($CALIDAD[0]['temperatura_establecida']); ?>");
	    	$('#temperatura_minima').val("<?php echo utf8_encode($CALIDAD[0]['temperatura_minima']); ?>");
	    	$('#temperatura_maxima').val("<?php echo utf8_encode($CALIDAD[0]['temperatura_maxima']); ?>");
	    	$('#temperatura_media').val("<?php echo utf8_encode($CALIDAD[0]['temperatura_media']); ?>");
	    	$('#tiempo_preshipment').val("<?php echo utf8_encode($CALIDAD[0]['tiempo_preshipment']); ?>");
	    	$('#num_lote').val("<?php echo utf8_encode($CALIDAD[0]['num_lote']); ?>");
	    	$('#dias').val("<?php echo utf8_encode($CALIDAD[0]['dias']); ?>");
	    	$('#middle_temp1').val("<?php echo utf8_encode($CALIDAD[0]['middle_temp1']); ?>");
	    	/*$('#middle_temp2').val("<?php echo utf8_encode($CALIDAD[0]['middle_temp2']); ?>");
	    	$('#middle_temp3').val("<?php echo utf8_encode($CALIDAD[0]['middle_temp3']); ?>");
	    	$('#middle_temp4').val("<?php echo utf8_encode($CALIDAD[0]['middle_temp4']); ?>");*/
	    	$('#cod_pais').selectpicker('val',"<?php echo utf8_encode($CALIDAD[0]['cod_pais']); ?>");
	    	$('#cod_pais').trigger('change');
	    	$('#cod_departamento').selectpicker('val',"<?php echo utf8_encode($CALIDAD[0]['cod_departamento']); ?>");
	    	$('#cod_producto').selectpicker('val',"<?php echo utf8_encode($CALIDAD[0]['cod_producto']); ?>");
	    	$('.selectpicker').selectpicker('refresh');
	    	$('#div_preshipment').removeClass('hide');
	    	$('#div_detalle_control_calidad').removeClass('hide');
	    	<?php
	    }
	    if (count($CONTROLES)) 
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
		                title: 'Quality Control',
		            },
		            'print',
		        ]
	        });
	        <?php
	    }
	    ?>
	    codigo_control_calidad = <?php echo $cod_control_calidad;?>;
	    $('#modal_loading').modal('hide');
	    jQuery.ajaxSetup({async:true});
	});

	$('#btn_guardar_control_calidad').click(function(event) {
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
                qua_guardar_control_calidad(codigo_control_calidad);
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
		qua_cambiar_estado_control_calidad($(this).data('id'),($(this).attr('checked') ? 0 : 1));
	});

	$('#btn_add_detalle').click(function(event) {
		/* Act on the event */
		$('#modal_detalle').modal('show');
	});

	$('#btn_guardar_detalle').click(function(event) {
         /* Act on the event */        
        var error = 0;
        $(".input.requerido_detalle").map(function(){
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
        $(".selectpicker.requerido_detalle").map(function(){
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
        	$('#modal_detalle').modal('hide');
            grl_overlay_loading('');
            $('#modal_loading').modal('hide');
            $('#modal_loading').on('hidden.bs.modal', function () {
                qua_guardar_detalle_control_calidad(codigo_control_calidad);
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
	<div class="busqueda_contenedor">
	    <div class="row" style="min-width:100px;">
	        <div class="col-sm-12">
	                <div class="panel panel-primary" style="overflow:auto;">
	                <div class="panel-heading panel_cabecera">
	                        <h3 class="panel-title translate" data-traducir_english="Quality Controls List" data-traducir_spanish="Listado de Controles de Calidad">Listado de Controles de Calidad</h3>
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
	                                        <th width="20%" class="translate" data-traducir_english="Product Name" data-traducir_spanish="Nombre Producto">Nombre Producto</th>
	                                        <th width="15%" class="translate" data-traducir_english="PO#" data-traducir_spanish="Orden de Compra">Orden de Compra</th>
	                                        <th width="15%" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</th>
	                                        <th width="15%" class="translate" data-traducir_english="Time" data-traducir_spanish="Tiempo">Tiempo</th>
	                                        <th width="15%" class="translate" data-traducir_english="Country" data-traducir_spanish="País">País</th>
	                                        <th width="15%" class="translate" data-traducir_english="State" data-traducir_spanish="Estado">Estado</th>
	                                        <th width="15%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>
	                                        <th width="5%" class="translate" data-traducir_english="Active" data-traducir_spanish="Activo">Activo</th>
	                                </tr>
	                        </thead>
	                        <tbody id="cuerpo_tabla">
	                            <?php
	                            if(count($CONTROLES))
	                            {
	                            	$correlativo = 1;
	                            	foreach($CONTROLES as $calidad)
	                            	{
	                            		?>
	                            		<tr>
	                            			<!-- <td class="hide"><?php echo utf8_encode($calidad['cod_control_calidad']);?></td> -->
	                            			<td onclick="qua_vista_control_calidad(<?php echo utf8_encode($calidad['cod_control_calidad']);?>)"><?php echo $correlativo;?></td>
	                            			<td onclick="qua_vista_control_calidad(<?php echo utf8_encode($calidad['cod_control_calidad']);?>)"><?php echo utf8_encode($calidad['nombre_producto']);?></td>
	                            			<td onclick="qua_vista_control_calidad(<?php echo utf8_encode($calidad['cod_control_calidad']);?>)"><?php echo utf8_encode($calidad['num_orden_compra']);?></td>
	                            			<td onclick="qua_vista_control_calidad(<?php echo utf8_encode($calidad['cod_control_calidad']);?>)"><?php echo utf8_encode($calidad['fecha']);?></td>
	                            			<td onclick="qua_vista_control_calidad(<?php echo utf8_encode($calidad['cod_control_calidad']);?>)"><?php echo utf8_encode($calidad['tiempo']);?></td>
	                            			<td onclick="qua_vista_control_calidad(<?php echo utf8_encode($calidad['cod_control_calidad']);?>)"><?php echo utf8_encode($calidad['pais']);?></td>
	                            			<td onclick="qua_vista_control_calidad(<?php echo utf8_encode($calidad['cod_control_calidad']);?>)"><?php echo utf8_encode($calidad['departamento']);?></td>
	                            			<td onclick="qua_vista_control_calidad(<?php echo utf8_encode($calidad['cod_control_calidad']);?>)"><?php echo utf8_encode($calidad['nombre_usuario']);?></td>
	                            			<td>
	                            				<div class="material-switch pull-right">
						                            <input class="checkbox" id="checkbox_<?php echo utf8_encode($calidad['cod_control_calidad']);?>" data-id="<?php echo utf8_encode($calidad['cod_control_calidad']);?>" name="checkbox_<?php echo utf8_encode($calidad['cod_control_calidad']);?>" type="checkbox" <?php echo ($calidad['activo'] == 1 ? 'checked="checked"':'');?>/>
						                            <label for="checkbox_<?php echo utf8_encode($calidad['cod_control_calidad']);?>" class=""></label>
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
	                <h1 class="translate" data-traducir_english="Quality Control" data-traducir_spanish="Control de Calidad">Control de Calidad</h1>
	            </div>
			</div>
            <div class="col-md-12">
            	<div class="row">
	            	<div class="col-md-3">
						<label for="cod_pais" class="translate" data-traducir_english="Country" data-traducir_spanish="País">País</label>
						<div class="form-group show-tick">
							<select class="selectpicker show-menu-arrow requerido" id="cod_pais" name="cod_pais" data-live-search="true" title="Select">
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<label for="cod_departamento" class="translate" data-traducir_english="State" data-traducir_spanish="Estado">Estado</label>
						<div class="form-group show-tick">
							<select class="selectpicker show-menu-arrow requerido" id="cod_departamento" name="cod_departamento" data-live-search="true" title="Select">
							</select>
						</div>
					</div>
            		<div class="col-md-3">
						<label for="cod_producto" class="translate" data-traducir_english="Product" data-traducir_spanish="Producto">Producto</label>
						<div class="form-group show-tick">
							<select class="selectpicker show-menu-arrow requerido" id="cod_producto" name="cod_producto" data-live-search="true" title="Select">
							</select>
						</div>
					</div>
					<div class="col-md-3">
	            		<label for="fecha" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</label>
                        <div class='input-group input-group-sm fecha-planeada date' id='datetimepicker1'>
                            <span class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </span>
                            <input type='text' class="form-control input requerido" id="fecha" />
                        </div>
	            	</div>
				</div>
				<div class="row">
	            	<div class="col-md-3">
	            		<div class="form-group input-group-sm">
                            <label for="num_orden_compra" class="translate" data-traducir_english="PO#" data-traducir_spanish="Orden de Compra">Orden de Compra</label>
                            <input type="text"class="form-control letras input requerido"  id="num_orden_compra" name="num_orden_compra">
                        </div>
	            	</div>
					<div class="col-md-3">
	            		<label for="tiempo" class="translate" data-traducir_english="Time" data-traducir_spanish="Tiempo">Tiempo</label>
                        <div class='input-group input-group-sm fecha-planeada time' id='datetimepicker2'>
                            <span class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </span>
                            <input type='text' class="form-control input requerido" id="tiempo" />
                        </div>
	            	</div>
	            	<div class="col-md-3">
	            		<div class="form-group input-group-sm">
                            <label for="temperatura_actual" class="translate" data-traducir_english="Actual Temperature" data-traducir_spanish="Temperatura Actual">Temperatura Actual</label>
                            <input type="text"class="form-control temperatura input requerido"  id="temperatura_actual" name="temperatura_actual">
                        </div>
	            	</div>
	            	<div class="col-md-3">
	            		<div class="form-group input-group-sm">
                            <label for="temperatura_establecida" class="translate" data-traducir_english="Set Temperature" data-traducir_spanish="Temperatura Establecida">Temperatura Establecida</label>
                            <input type="text"class="form-control temperatura input requerido"  id="temperatura_establecida" name="temperatura_establecida">
                        </div>
	            	</div>
				</div>
				<div class="row">
	            	<div class="col-md-3">
	            		<div class="form-group input-group-sm">
                            <label for="temperatura_minima" class="translate" data-traducir_english="Min Temp" data-traducir_spanish="Temperatura Mínima">Temperatura Mínima</label>
                            <input type="text"class="form-control temperatura input requerido"  id="temperatura_minima" name="temperatura_minima">
                        </div>
	            	</div>
	            	<div class="col-md-3">
	            		<div class="form-group input-group-sm">
                            <label for="temperatura_maxima" class="translate" data-traducir_english="Max Temp" data-traducir_spanish="Temperatura Máxima">Temperatura Máxima</label>
                            <input type="text"class="form-control temperatura input requerido"  id="temperatura_maxima" name="temperatura_maxima">
                        </div>
	            	</div>
	            	<div class="col-md-3">
	            		<div class="form-group input-group-sm">
                            <label for="temperatura_media" class="translate" data-traducir_english="Average Temp" data-traducir_spanish="Temperatura Media">Temperatura Media</label>
                            <input type="text"class="form-control temperatura input requerido"  id="temperatura_media" name="temperatura_media">
                        </div>
	            	</div>					
				</div>
				<div class="row hide" id="div_detalle_control_calidad">
	            	<div class="col-md-12">
	            		<div class="panel panel-primary" id="panel_itemschecklist">
		                    <div class="panel-heading btncollapsepaso"id="a_itemschecklist" role="button" data-toggle="collapse" href="#collapseitemschecklist" aria-expanded="true" data-parent="#accordion" aria-controls="collapseitemschecklist">
		                        <h3 class="panel-title translate" data-traducir_english='Quality Control Detail' data-traducir_spanish='Detalle Control de Calidad'>Detalle Control de Calidad</h3>
		                    </div>
		                    <div class="panel-collapse collapse" id="collapseitemschecklist" role="tabpanel" aria-labelledby="collapseitemschecklist">
		                        <div class="panel-body">
		                            <div class="responsive_table_container">
		                                <table class="table display row-border responsive" id="tabla_itemschecklist">
		                                    <thead>
		                                         <tr class="active info">
		                                            <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
		                                            <th width="20%" class="translate" data-traducir_english="Room Name" data-traducir_spanish="Nombre Cuarto Frío">Nombre Cuarto Frío</th>
		                                            <th width="20%" class="translate" data-traducir_english="Section Name" data-traducir_spanish="Nombre Sección">Nombre Sección</th>
		                                            <th width="10%" class="translate" data-traducir_english="Time" data-traducir_spanish="Tiempo">Tiempo</th>
		                                            <th width="10%" class="translate" data-traducir_english="Value" data-traducir_spanish="Valor">Valor</th>
		                                            <th width="20%" class="translate" data-traducir_english="Observations" data-traducir_spanish="Observaciones">Observaciones</th>
		                                            <th width="15%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>
		                                         </tr>
		                                    </thead>
		                                    <tbody>
				                            <?php
				                            if(count($DETALLES))
				                            {
				                            	$correlativo = 1;
				                            	foreach($DETALLES as $detalle)
				                            	{
				                            		?>
				                            		<tr>
				                            			<td><?php echo $correlativo;?></td>
				                            			<td><?php echo utf8_encode($detalle['nombre_cuarto']);?></td>
				                            			<td><?php echo utf8_encode($detalle['nombre_seccion']);?></td>
				                            			<td><?php echo utf8_encode($detalle['tiempo']);?></td>
				                            			<td><?php echo utf8_encode($detalle['valor']);?></td>
				                            			<td><?php echo utf8_encode($detalle['observaciones']);?></td>
				                            			<td><?php echo utf8_encode($detalle['nombre_usuario']);?></td>
				                            			<!-- <td>
				                            				<div class="material-switch pull-right">
									                            <input class="checkbox_seccion" id="checkboxp_<?php echo utf8_encode($detalle['cod_seccion']);?>" data-id="<?php echo utf8_encode($detalle['cod_seccion']);?>" name="checkboxp_<?php echo utf8_encode($detalle['cod_seccion']);?>" type="checkbox" <?php echo ($detalle['activo'] == 1 ? 'checked="checked"':'');?>/>
									                            <label for="checkboxp_<?php echo utf8_encode($detalle['cod_seccion']);?>" class=""></label>
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
		                            <div class="row nomargin row-add-content">
		                                <div class="col-xs-12 text-center">
		                                    <button type="button" id="btn_add_detalle" class="btn btn-sm btn-success truncated-text btn_add" data-action="true">
		                                        <i class="fa fa-plus-circle"> </i>
		                                        <span class="translate" data-traducir_english="Add Detail" data-traducir_spanish="Añadir Detalle">Añadir Detalle</span>
		                                    </button>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
	            	</div>
	            </div>
	            <div id="div_preshipment" class="hide">
	            	<div class="row">
	            		<div class="col-md-12">
	            			<h3 class="translate" data-traducir_english="Pre-Shipment" data-traducir_spanish="Pre-Envío">Pre-Envío</h3>
	            		</div>
	            	</div>
		            <div class="row">
		            	<div class="col-md-3">
		            		<label for="tiempo" class="translate" data-traducir_english="Time Pre-Shipment" data-traducir_spanish="Tiempo Pre-Envío">Tiempo Pre-Envío</label>
	                        <div class='input-group input-group-sm fecha-planeada time'>
	                            <span class="input-group-addon">
	                                <span class="fa fa-calendar"></span>
	                            </span>
	                            <input type='text' class="form-control input" id="tiempo_preshipment" />
	                        </div>
		            	</div>
		            	<div class="col-md-3">
		            		<div class="form-group input-group-sm">
	                            <label for="num_lote" class="translate" data-traducir_english="Lot #" data-traducir_spanish="# Lote"># Lote</label>
	                            <input type="text"class="form-control letras30 input"  id="num_lote" name="num_lote">
	                        </div>
		            	</div>
		            	<div class="col-md-3">
		            		<div class="form-group input-group-sm">
	                            <label for="dias" class="translate" data-traducir_english="Days Old" data-traducir_spanish="Días">Días</label>
	                            <input type="text"class="form-control dias input"  id="dias" name="dias">
	                        </div>
		            	</div><!-- 
		            </div>
		            <div class="row">	 -->	            	
		            	<div class="col-md-3">
		            		<div class="form-group input-group-sm">
	                            <label for="middle_temp1" class="translate" data-traducir_english="Middle Temp" data-traducir_spanish="Temperatura Media">Temperatura Media</label>
	                            <input type="text"class="form-control temperatura_media input"  id="middle_temp1" name="middle_temp1">
	                        </div>
		            	</div>	
		            </div>
		            <!-- <div class="row">		            	
		            	<div class="col-md-3">
		            		<div class="form-group input-group-sm">
	                            <label for="middle_temp2" class="translate" data-traducir_english="Middle Temp" data-traducir_spanish="Temperatura Media">Temperatura Media</label>
	                            <input type="text"class="form-control temperatura_media input"  id="middle_temp2" name="middle_temp2">
	                        </div>
		            	</div>		            	
		            	<div class="col-md-3">
		            		<div class="form-group input-group-sm">
	                            <label for="middle_temp3" class="translate" data-traducir_english="Middle Temp" data-traducir_spanish="Temperatura Media">Temperatura Media</label>
	                            <input type="text"class="form-control temperatura_media input"  id="middle_temp3" name="middle_temp3">
	                        </div>
		            	</div>		            	
		            	<div class="col-md-3">
		            		<div class="form-group input-group-sm">
	                            <label for="middle_temp4" class="translate" data-traducir_english="Middle Temp" data-traducir_spanish="Temperatura Media">Temperatura Media</label>
	                            <input type="text"class="form-control temperatura_media input"  id="middle_temp4" name="middle_temp4">
	                        </div>
		            	</div>
		            </div> -->
	            </div>
            </div>
		</div>
		<div class="panel-footer" align="right">
			<button class="btn btn-sm btn-primary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" type="button" id="btn_guardar_control_calidad">Guardar</button>
		</div>
	</div>
	<div class="modal fade modal_detalle" id="modal_detalle" tabindex="-1" role="dialog" aria-labelledby="ModalResponsables" aria-hidden="true">
		<div class="modal-dialog modal-success modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title"><span id="titulo_modal_responsables" class="translate" data-traducir_english="Detail" data-traducir_spanish="Detalle">Detalle</span> <small class="subtitulo" id="subtitulo_modal_responsables"></small></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="form-group input-group-sm col-xs-12">
							<label for="cod_cuarto_frio" class="translate" data-traducir_english="Room Temp" data-traducir_spanish="Cuarto Frío">Cuarto Frío</label>
							<div class="form-group show-tick">
								<select class="selectpicker show-menu-arrow requerido_detalle" id="cod_cuarto_frio" name="cod_cuarto_frio" data-live-search="true" title="Select">
								</select>
							</div>
		            	</div>
						<div class="form-group input-group-sm col-xs-12">
							<label for="cod_seccion" class="translate" data-traducir_english="Room Temp Section" data-traducir_spanish="Sección del Cuarto Frío">Sección del Cuarto Frío</label>
							<div class="form-group show-tick">
								<select class="selectpicker show-menu-arrow requerido_detalle" id="cod_seccion" name="cod_seccion" data-live-search="true" title="Select">
								</select>
							</div>
		            	</div>
						<div class="form-group input-group-sm col-xs-12">
                            <label for="tiempo_detalle" class="translate" data-traducir_english="Time" data-traducir_spanish="Tiempo">Tiempo</label>
	                        <div class='input-group input-group-sm fecha-planeada time' id='datetimepicker2'>
	                            <span class="input-group-addon">
	                                <span class="fa fa-calendar"></span>
	                            </span>
	                            <input type='text' class="form-control input requerido_detalle" id="tiempo_detalle" />
	                        </div>
		            	</div>
						<div class="form-group input-group-sm col-xs-12">
                            <label for="valor" class="translate" data-traducir_english="Value" data-traducir_spanish="Valor">Valor</label>
                            <input type="text"class="form-control valor input requerido_detalle"  id="valor" name="valor">
		            	</div>
		            	<div class="form-group input-group-sm col-xs-12" id="">
                            <label for="observaciones" class="translate" data-traducir_english="Observations" data-traducir_spanish="Observaciones">Observaciones</label>
                            <div class="form-group show-tick">
                                <textarea maxlength="2500" align="left" style="height:100px; width:100%; resize: none;" class="form-control limpiar" rows="2" id="observaciones" style="width: 100%"></textarea>
                            </div>
                        </div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-sm btn-primary btn_guardar translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_guardar_detalle">Guardar</button>
				</div>
			</div>
		</div>
	</div>
</body>