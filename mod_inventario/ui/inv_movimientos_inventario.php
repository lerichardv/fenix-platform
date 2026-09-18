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

//$MOVIMIENTOS = $DB_INV->inv_listado_movimientos_inventario();
$MOVIMIENTOS = $DB_INV->inv_listado_movimientos_inventario_por_granjas($cod_granjas_usuario);

$cod_movimiento = $_POST['cod_movimiento'];
if (!isset($_POST['cod_movimiento'])) {
	$cod_movimiento = 0;
}
$MOVIMIENTO = $DB_INV->inv_obtener_info_movimiento_inventario($cod_movimiento);
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
        //locale: 'es',
        locale:  moment.locale('en', {
            week: { dow: 0 }
        }),
        //minDate: hoy,
        //keepOpen: true,
        format: 'MM-DD-YYYY',
        defaultDate: hoy,
        //direction: 'auto',
        disabledDates: [<?php print_r($array_fechas); ?>],
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
        //locale: 'es',
        locale:  moment.locale('en', {
            week: { dow: 0 }
        }),

        //minDate: hoy,
        //keepOpen: true,
        format: 'MM-DD-YYYY',
        //defaultDate: hoy,
        //direction: 'auto',
        //disabledHours:
        disabledDates: [<?php print_r($array_fechas); ?>],
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

	$(document).ready(function() {
		codigo_movimiento = 0;
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
		conf_constructor_listado_granjas('cod_info_empresa_envia');
		conf_constructor_listado_granjas('cod_info_empresa_recibe');
		inv_constructor_listado_tipos_aplicacion('cod_tipo_aplicacion');
		inv_constructor_listado_unidades_medida();
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
        });
		//Máscaras
        //$('.monto').mask("#,##0.000", {reverse: false, maxlength: false});
        $('.monto').mask("9999999.999");
	    $('.lote').mask("999999");
	    $('.letras').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z-¡!¿?,.() 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/, optional: false}}});

	    /*----------------------------------------------------------------------------------
    								Validando listboxs
	    ----------------------------------------------------------------------------------*/
		$('.selectpicker.requerido_envio').change(function(event) {
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
		$('.selectpicker.requerido_recibido').change(function(event) {
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
	    $('.input.requerido_envio').keyup(function(event) {
	    	if( $(this).val().trim() != "" ){
				$(this).parent('div').removeClass('has-error');
			}
			else{
				$(this).parent('div').addClass('has-error');
			}
	    });
	    $('.input.requerido_recibido').keyup(function(event) {
	    	if( $(this).val().trim() != "" ){
				$(this).parent('div').removeClass('has-error');
			}
			else{
				$(this).parent('div').addClass('has-error');
			}
	    });
		$('#cod_tipo_inventario').change(function(event) {
			/* Act on the event */
			if ($(this).val() == 1)
			{
				inv_constructor_listado_semillas('cod_inventario',$('#cod_info_empresa_envia').val());
			}
			if ($(this).val() == 2)
			{
				inv_constructor_listado_quimicos('cod_inventario',$('#cod_info_empresa_envia').val());
			}
			if ($(this).val() == 3)
			{
				inv_constructor_listado_maquinarias('cod_inventario',$('#cod_info_empresa_envia').val());
			}

		});
	    <?php
	    if (count($MOVIMIENTO))
	    {
	    	$cod_info_empresa = str_replace(['[',']','"'], '', $_SESSION['cod_info_empresa']);
	    	$cod_info_empresa = explode(',', $cod_info_empresa);
	    	?>
	    	$('#cod_info_empresa_envia').val("<?php echo utf8_encode($MOVIMIENTO[0]['cod_info_empresa_envia']); ?>");
	    	$('#cod_info_empresa_recibe').val("<?php echo utf8_encode($MOVIMIENTO[0]['cod_info_empresa_recibe']); ?>");
	    	$('#fecha_envia').val("<?php echo utf8_encode($MOVIMIENTO[0]['fecha_envia']); ?>");
	    	$('#fecha_recibe').val("<?php echo utf8_encode($MOVIMIENTO[0]['fecha_recibe']); ?>");
	    	$('#num_lote').val("<?php echo utf8_encode($MOVIMIENTO[0]['num_lote']); ?>");
	    	$('#cantidad_enviada').val("<?php echo utf8_encode($MOVIMIENTO[0]['cantidad_enviada']); ?>");
	    	$('#cantidad_recibida').val("<?php echo utf8_encode($MOVIMIENTO[0]['cantidad_recibida']); ?>");
	    	$('#cod_tipo_inventario').selectpicker('val',"<?php echo utf8_encode($MOVIMIENTO[0]['cod_tipo_inventario']); ?>");
	    	$('#cod_inventario').selectpicker('val',"<?php echo utf8_encode($MOVIMIENTO[0]['cod_inventario']); ?>");
	    	$('#cod_unidad_medida').selectpicker('val',"<?php echo utf8_encode($MOVIMIENTO[0]['cod_unidad_medida']); ?>");
	    	$('.selectpicker').selectpicker('refresh');
	    	$('#fecha_recibe').removeAttr('disabled');
	    	$('#cantidad_recibida').removeAttr('disabled');
	    	$('#btn_enviar_inventario').addClass('hide');
	    	<?php
	    	if(in_array($MOVIMIENTO[0]['cod_info_empresa_recibe'],$cod_info_empresa)
	    		&& ($MOVIMIENTO[0]['user_recibe'] == null || $MOVIMIENTO[0]['user_recibe'] == ''))
	    	{
	    	?>
		    	$('#btn_recibir_inventario').removeClass('hide');
				$('#btn_perdida_inventario').removeClass('hide');
	    	<?php
	    	}
	    }
	    ?>
	    codigo_movimiento = <?php echo $cod_movimiento;?>;
	    $('#modal_loading').modal('hide');
	    jQuery.ajaxSetup({async:true});
	});

	$('#btn_enviar_inventario').click(function(event) {
         /* Act on the event */
        var error = 0;
        $(".input.requerido_envio").map(function(){
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
        $(".selectpicker.requerido_envio").map(function(){
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
                inv_guardar_movimiento_inventario(codigo_movimiento);
            });
        }
        else
        {
            grl_mensaje('Debe llenar todos los campos marcados','favor verificar','warning');
        }
    });


	$('#btn_recibir_inventario').click(function(event) {
         /* Act on the event */
        var error = 0;
        $(".input.requerido_recibido").map(function(){
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
        $(".selectpicker.requerido_recibido").map(function(){
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
                inv_guardar_movimiento_inventario(codigo_movimiento);
            });
        }
        else
        {
            grl_mensaje('Debe llenar todos los campos marcados','favor verificar','warning');
        }
    });

	$('#btn_perdida_inventario').click(function(event) {
		/* Act on the event */
		$('#modal_perdida').modal('show');
	});

	$('#btn_guardar_perdida').click(function(event) {
         /* Act on the event */
        var error = 0;
        $(".input.requerido_perdida").map(function(){
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
        $(".selectpicker.requerido_perdida").map(function(){
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
        	$('#modal_perdida').modal('hide');
            grl_overlay_loading('');
            $('#modal_loading').modal('hide');
            $('#modal_loading').on('hidden.bs.modal', function () {
                inv_guardar_movimiento_inventario(codigo_movimiento);
            });
        }
        else
        {
            grl_mensaje('Debe llenar todos los campos marcados','favor verificar','warning');
        }
    });

    $('#cod_info_empresa_envia').change(function(event) {
        /* Act on the event */
        jQuery("#cod_info_empresa_recibe > option").each(function() {
            if(jQuery(this).val() == $('#cod_info_empresa_envia option:selected').val()){
                console.log('item: ' + jQuery(this).val());
                console.log('item2: ' + $('#cod_info_empresa_envia option:selected').val());
                jQuery(this).attr('disabled', 'disabled');
                if(jQuery(this).val() == $('#cod_info_empresa_recibe option:selected').val())
                {
                    $('#cod_info_empresa_recibe').val('-b');
                }
            }
            else
            {
                jQuery(this).removeAttr('disabled');
            }
        });
        $('#cod_info_empresa_recibe').selectpicker('refresh');
    });
</script>

<body>
	<div id="overlay_loading"></div>
	<div id="message_box"></div>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-md-12">
                <div class="panel-header">
                    <h1 class="translate" data-traducir_english="Movement" data-traducir_spanish="Movimiento">Movimiento</h1>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group input-group-sm" id="div_tipo_curso">
                            <label for="cod_info_empresa_envia" class="translate" data-traducir_english="Grower sends" data-traducir_spanish="Finca Envía">Finca Envía</label>
                            <select class="selectpicker show-menu-arrow requerido_envio cod_info_empresa" data-live-search="true" title="Select" id="cod_info_empresa_envia" name="cod_info_empresa_envia">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group input-group-sm" id="div_tipo_curso">
                            <label for="cod_info_empresa_recibe" class="translate" data-traducir_english="Grower receives" data-traducir_spanish="Finca Recibe">Finca Recibe</label>
                            <select class="selectpicker show-menu-arrow requerido_envio cod_info_empresa" data-live-search="true" title="Select" id="cod_info_empresa_recibe" name="cod_info_empresa_recibe">
                            </select>
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class="form-group">
                            <label for="fecha_envia" class="translate" data-traducir_english="Initial Date" data-traducir_spanish="Fecha Envío">Fecha Envío</label>
                            <div class='input-group input-group-sm fecha-inicio' id='datetimepicker1'>
                                <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </span>
                                <input type='text' class="form-control input requerido_envio" id="fecha_envia" />
                            </div>
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class="form-group">
                            <label for="fecha_recibe" class="translate" data-traducir_english="Final Date" data-traducir_spanish="Fecha Recibido">Fecha Recibido</label>
                            <div class='input-group input-group-sm fecha-final' id='datetimepicker1'>
                                <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </span>
                                <input type='text' class="form-control input requerido_recibido" disabled="disabled" id="fecha_recibe" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group input-group-sm" id="div_tipo_curso">
                            <label for="cod_tipo_inventario" class="translate" data-traducir_english="Inventory type" data-traducir_spanish="Tipo Inventario">Tipo Inventario</label>
                            <select class="selectpicker show-menu-arrow requerido_envio" data-live-search="true" title="Select" id="cod_tipo_inventario" name="cod_tipo_inventario">
                                <option value="-b">Select</option>
                                <option value="1">Seeds - Semillas</option>
                                <option value="2">Chemicals - Químicos</option>
                                <option value="3">Machinery - Maquinaria</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group input-group-sm" id="div_tipo_curso">
                            <label for="cod_inventario" class="translate" data-traducir_english="Inventory" data-traducir_spanish="Inventario">Inventario</label>
                            <select class="selectpicker show-menu-arrow requerido_envio" data-live-search="true" title="Select" id="cod_inventario" name="cod_inventario">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group input-group-sm" id="div_tipo_curso">
                            <label for="cod_unidad_medida" class="translate" data-traducir_english="Unit of measure" data-traducir_spanish="Unidad de Medida">Unidad de Medida</label>
                            <select class="selectpicker show-menu-arrow" data-live-search="true" title="Select" id="cod_unidad_medida" name="cod_unidad_medida">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group input-group-sm" id="div_nombre_capacitacion">
                            <label for="num_lote" class="translate" data-traducir_english="No. Lot" data-traducir_spanish="Nro. Lote">Nro. Lote</label>
                            <input type="text"class="form-control lote input"  id="num_lote" name="num_lote">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group input-group-sm" id="div_nombre_capacitacion">
                            <label for="cantidad_enviada" class="translate" data-traducir_english="Amount sent" data-traducir_spanish="Cantidad Enviada">Cantidad Enviada</label>
                            <input type="text"class="form-control monto input requerido_envio"  id="cantidad_enviada" name="cantidad_enviada">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group input-group-sm" id="div_nombre_capacitacion">
                            <label for="cantidad_recibida" class="translate" data-traducir_english="Received amount" data-traducir_spanish="Cantidad Recibida">Cantidad Recibida</label>
                            <input disabled="disabled" type="text"class="form-control monto input requerido_recibido"  id="cantidad_recibida" name="cantidad_recibida">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer" align="right">
            <button class="btn btn-sm btn-primary translate" data-traducir_english="Send" data-traducir_spanish="Enviar" type="button" id="btn_enviar_inventario">Enviar</button>
            <button class="btn btn-sm btn-success hide translate" data-traducir_english="Received" data-traducir_spanish="Recibido" type="button" id="btn_recibir_inventario">Recibido</button>
            <button class="btn btn-sm btn-warning hide translate" data-traducir_english="Lost" data-traducir_spanish="Pérdida" type="button" id="btn_perdida_inventario">Pérdida</button>
        </div>
    </div>
	<div class="busqueda_contenedor">
	    <div class="row" style="min-width:100px;">
	        <div class="col-sm-12">
	           <!-- <div class="panel panel-primary" style="overflow:auto;">
	                <div class="panel-heading panel_cabecera">
	                        <h3 class="panel-title translate" data-traducir_english="Inventory Movements" data-traducir_spanish="Movimientos de Inventario">Movimientos de Inventario</h3>
							<div class="pull-right">
								<span class="clickable filter" data-toggle="tooltip" title="Realizar busqueda" data-container="body">
									<i class="fa fa-search"></i>
								</span>
							</div>
	                </div>
					<div class="panel-body panel_cuerpo input-group-sm">
						<input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Busqueda" />
					</div> -->
                    <h3 class="display-4 titulo translate" align="center" data-traducir_english="Inventory Movements" data-traducir_spanish="Movimientos de Inventario">Movimientos de Inventario</h3>
	                <div style="overflow-x:auto;">
	                    <table class="table table-striped table-hover table-sm" id="dev-table" >
	                        <thead>
	                                <tr class="active info">
	                                        <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
	                                        <th width="10%" class="translate" data-traducir_english="Grower sends" data-traducir_spanish="Finca envía">Finca envía</th>
	                                        <th width="10%" class="translate" data-traducir_english="Grower receives" data-traducir_spanish="Finca recibe">Finca recibe</th>
	                                        <th width="15%" class="translate" data-traducir_english="Amount sent" data-traducir_spanish="Cantidad enviada">Cantidad enviada</th>
	                                        <th width="15%" class="translate" data-traducir_english="Amount received" data-traducir_spanish="Cantidad recibida">Cantidad recibida</th>
	                                        <th width="10%" class="translate" data-traducir_english="Date sent" data-traducir_spanish="Fecha enviada">Fecha enviada</th>
	                                        <th width="10%" class="translate" data-traducir_english="Date received" data-traducir_spanish="Fecha recibida">Fecha recibida</th>
	                                        <th width="10%" class="translate" data-traducir_english="User sends" data-traducir_spanish="Usuario envía">Usuario envía</th>
	                                        <th width="10%" class="translate" data-traducir_english="User receives" data-traducir_spanish="Usuario recibe">Usuario recibe</th>
	                                </tr>
	                        </thead>
	                        <tbody id="cuerpo_tabla">
	                            <?php
	                            if(count($MOVIMIENTOS))
	                            {
	                            	$correlativo = 1;
	                            	foreach($MOVIMIENTOS as $movimiento)
	                            	{
	                            		?>
	                            		<tr>
	                            			<td onclick="inv_vista_movimiento_inventario(<?php echo utf8_encode($movimiento['cod_movimiento']);?>)"><?php echo $correlativo;?></td>
	                            			<td onclick="inv_vista_movimiento_inventario(<?php echo utf8_encode($movimiento['cod_movimiento']);?>)"><?php echo utf8_encode($movimiento['nombre_empresa_envia']);?></td>
	                            			<td onclick="inv_vista_movimiento_inventario(<?php echo utf8_encode($movimiento['cod_movimiento']);?>)"><?php echo utf8_encode($movimiento['nombre_empresa_recibe']);?></td>
	                            			<td onclick="inv_vista_movimiento_inventario(<?php echo utf8_encode($movimiento['cod_movimiento']);?>)"><?php echo utf8_encode($movimiento['cantidad_enviada']);?></td>
	                            			<td onclick="inv_vista_movimiento_inventario(<?php echo utf8_encode($movimiento['cod_movimiento']);?>)"><?php echo utf8_encode($movimiento['cantidad_recibida']);?></td>
	                            			<td onclick="inv_vista_movimiento_inventario(<?php echo utf8_encode($movimiento['cod_movimiento']);?>)"><?php echo utf8_encode($movimiento['fecha_envia']);?></td>
	                            			<td onclick="inv_vista_movimiento_inventario(<?php echo utf8_encode($movimiento['cod_movimiento']);?>)"><?php echo utf8_encode($movimiento['fecha_recibe']);?></td>
	                            			<td onclick="inv_vista_movimiento_inventario(<?php echo utf8_encode($movimiento['cod_movimiento']);?>)"><?php echo utf8_encode($movimiento['nombre_user_envia']);?></td>
	                            			<td onclick="inv_vista_movimiento_inventario(<?php echo utf8_encode($movimiento['cod_movimiento']);?>)"><?php echo utf8_encode($movimiento['nombre_user_recibe']);?></td>
	                            			<!-- <td>
	                            				<div class="material-switch pull-right">
						                            <input class="checkbox" id="checkbox_<?php echo utf8_encode($movimiento['cod_movimiento']);?>" data-id="<?php echo utf8_encode($movimiento['cod_movimiento']);?>" name="checkbox_<?php echo utf8_encode($movimiento['cod_movimiento']);?>" type="checkbox" <?php echo ($movimiento['activo'] == 1 ? 'checked="checked"':'');?>/>
						                            <label for="checkbox_<?php echo utf8_encode($movimiento['cod_movimiento']);?>" class=""></label>
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
	<div class="modal fade modal_perdida" id="modal_perdida" tabindex="-1" role="dialog" aria-labelledby="ModalResponsables" aria-hidden="true">
		<div class="modal-dialog modal-success modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title"><span id="titulo_modal_responsables">Motivo</span> <small class="subtitulo" id="subtitulo_modal_responsables"></small></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="form-group input-group-sm col-xs-12" id="">
                            <label for="motivo_perdida">Motivo</label>
                            <div class="form-group show-tick">
                                <textarea maxlength="2500"  align="left" style="height:100px; width:100%; resize: none;" class="form-control limpiar requerido_perdida" rows="2" id="motivo_perdida" placeholder="Ingrese el motivo" style="width: 100%"></textarea>
                            </div>
                        </div>
		            	<div class="form-group input-group-sm col-xs-12">
		            		<div class="form-group input-group-sm" id="div_nombre_capacitacion">
	                            <label for="cantidad_perdida">Cantidad Pérdida</label>
	                            <input type="text"class="form-control monto input requerido_perdida"  id="cantidad_perdida" name="cantidad_perdida">
	                        </div>
		            	</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-sm btn-primary btn_guardar" id="btn_guardar_perdida">Guardar</button>
				</div>
			</div>
		</div>
	</div>
</body>