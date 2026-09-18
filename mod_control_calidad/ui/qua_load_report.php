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

$REPORTES = $CONTROL->qua_listado_load_report();

$cod_reporte = $_POST['cod_reporte'];
if (!isset($_POST['cod_reporte'])) {
	$cod_reporte = 0;
}
$REPORTE = $CONTROL->plan_cargar_load_repor($cod_reporte);
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
	//Inicializar la barra de botones
    init_button_bar();

    $('#dark_green_color').change(function(event) {
        console.log('option:' + $('#dark_green_color option:selected').val());
        if ($('#dark_green_color option:selected').val() == 5)
        {
            $('#dark_green_color').css('background', '#E6E326');
            $('#dark_green_color').selectpicker('setStyle', 'btn-danger btn-warning btn-success btn-info', 'remove');
            $('#dark_green_color').selectpicker('setStyle', 'btn-sm btn-warning');
        }
        if ($('#dark_green_color option:selected').val() > 5)
        {
            $('#dark_green_color').css('background', '#139400');
            $('#dark_green_color').selectpicker('setStyle', 'btn-danger btn-warning btn-success btn-info', 'remove');
            $('#dark_green_color').selectpicker('setStyle', 'btn-sm btn-success');
        }
        if ($('#dark_green_color option:selected').val() < 5)
        {
            $('#dark_green_color').css('background', '#E62626');
            $('#dark_green_color').selectpicker('setStyle', 'btn-danger btn-warning btn-success btn-info', 'remove');
            $('#dark_green_color').selectpicker('setStyle', 'btn-sm btn-danger');
        }
    });

    $('#yellow_leaves').change(function(event) {
        console.log('option:' + $('#yellow_leaves option:selected').val());
        if ($('#yellow_leaves option:selected').val() == 5)
        {
            $('#yellow_leaves').css('background', '#E6E326');
            $('#yellow_leaves').selectpicker('setStyle', 'btn-danger btn-warning btn-success btn-info', 'remove');
            $('#yellow_leaves').selectpicker('setStyle', 'btn-sm btn-warning');
        }
        if ($('#yellow_leaves option:selected').val() > 5)
        {
            $('#yellow_leaves').css('background', '#139400');
            $('#yellow_leaves').selectpicker('setStyle', 'btn-danger btn-warning btn-success btn-info', 'remove');
            $('#yellow_leaves').selectpicker('setStyle', 'btn-sm btn-success');
        }
        if ($('#yellow_leaves option:selected').val() < 5)
        {
            $('#yellow_leaves').css('background', '#E62626');
            $('#yellow_leaves').selectpicker('setStyle', 'btn-danger btn-warning btn-success btn-info', 'remove');
            $('#yellow_leaves').selectpicker('setStyle', 'btn-sm btn-danger');
        }
    });

    $('#weeds').change(function(event) {
        console.log('option:' + $('#weeds option:selected').val());
        if ($('#weeds option:selected').val() == 5)
        {
            $('#weeds').css('background', '#E6E326');
            $('#weeds').selectpicker('setStyle', 'btn-danger btn-warning btn-success btn-info', 'remove');
            $('#weeds').selectpicker('setStyle', 'btn-sm btn-warning');
        }
        if ($('#weeds option:selected').val() > 5)
        {
            $('#weeds').css('background', '#139400');
            $('#weeds').selectpicker('setStyle', 'btn-danger btn-warning btn-success btn-info', 'remove');
            $('#weeds').selectpicker('setStyle', 'btn-sm btn-success');
        }
        if ($('#weeds option:selected').val() < 5)
        {
            $('#weeds').css('background', '#E62626');
            $('#weeds').selectpicker('setStyle', 'btn-danger btn-warning btn-success btn-info', 'remove');
            $('#weeds').selectpicker('setStyle', 'btn-sm btn-danger');
        }
    });

    $('#optimal_soil_water_capacity').change(function(event) {
        console.log('option:' + $('#optimal_soil_water_capacity option:selected').val());
        if ($('#optimal_soil_water_capacity option:selected').val() == 5)
        {
            $('#optimal_soil_water_capacity').css('background', '#E6E326');
            $('#optimal_soil_water_capacity').selectpicker('setStyle', 'btn-danger btn-warning btn-success btn-info', 'remove');
            $('#optimal_soil_water_capacity').selectpicker('setStyle', 'btn-sm btn-warning');
        }
        if ($('#optimal_soil_water_capacity option:selected').val() > 5)
        {
            $('#optimal_soil_water_capacity').css('background', '#139400');
            $('#optimal_soil_water_capacity').selectpicker('setStyle', 'btn-danger btn-warning btn-success btn-info', 'remove');
            $('#optimal_soil_water_capacity').selectpicker('setStyle', 'btn-sm btn-success');
        }
        if ($('#optimal_soil_water_capacity option:selected').val() < 5)
        {
            $('#optimal_soil_water_capacity').css('background', '#E62626');
            $('#optimal_soil_water_capacity').selectpicker('setStyle', 'btn-danger btn-warning btn-success btn-info', 'remove');
            $('#optimal_soil_water_capacity').selectpicker('setStyle', 'btn-sm btn-danger');
        }
    });

    $('#right_density').change(function(event) {
        console.log('option:' + $('#right_density option:selected').val());
        if ($('#right_density option:selected').val() == 5)
        {
            $('#right_density').css('background', '#E6E326');
            $('#right_density').selectpicker('setStyle', 'btn-danger btn-warning btn-success btn-info', 'remove');
            $('#right_density').selectpicker('setStyle', 'btn-sm btn-warning');
        }
        if ($('#right_density option:selected').val() > 5)
        {
            $('#right_density').css('background', '#139400');
            $('#right_density').selectpicker('setStyle', 'btn-danger btn-warning btn-success btn-info', 'remove');
            $('#right_density').selectpicker('setStyle', 'btn-sm btn-success');
        }
        if ($('#right_density option:selected').val() < 5)
        {
            $('#right_density').css('background', '#E62626');
            $('#right_density').selectpicker('setStyle', 'btn-danger btn-warning btn-success btn-info', 'remove');
            $('#right_density').selectpicker('setStyle', 'btn-sm btn-danger');
        }
    });


    $('#average_tote_weight').focus(function(event) {
        /* Act on the event */
        $(this).val($('#real_average_tote_weight').val());
    });

    $('#num_orden_compra').focus(function(event) {
        /* Act on the event */
        if($(this).val() != '')
        {
            $(this).val('<?php echo $WORKSHEETS[0]['orden_compra'];?>');
        }
    });

    $('#tlc').focus(function(event) {
        /* Act on the event */
        var mins = parseFloat(moment.utc(moment($('#time_receiving').val(), "MM-DD-YYYY HH:mm:ss").diff(moment($('#inicial_time_harvest').val(), "MM-DD-YYYY HH:mm:ss"))).format("mm"));
        var hours = parseFloat(moment.utc(moment($('#time_receiving').val(), "MM-DD-YYYY HH:mm:ss").diff(moment($('#inicial_time_harvest').val(), "MM-DD-YYYY HH:mm:ss"))).format("HH"));
        var tlc = parseFloat((hours + (mins/60))*$('#temperature_product').val()).toFixed(2);
        console.log('hours: ' + hours);
        console.log('mins: ' + mins);
        console.log('tlc: ' + tlc);
        console.log('td: ' + $('#temperature_product').val());
        $(this).val(tlc);
    });

    $('#vacuum_cooler').focus(function(event) {
        /* Act on the event */
        var mins = parseFloat(moment.utc(moment($('#time_pickup').val(), "MM-DD-YYYY HH:mm:ss").diff(moment($('#time_vacuum_cooler').val(), "MM-DD-YYYY HH:mm:ss"))).format("mm"));
        var hours = parseFloat(moment.utc(moment($('#time_pickup').val(), "MM-DD-YYYY HH:mm:ss").diff(moment($('#time_vacuum_cooler').val(), "MM-DD-YYYY HH:mm:ss"))).format("HH"));
        var mins2 = parseFloat(moment.utc(moment($('#time_vacuum_cooler').val(), "MM-DD-YYYY HH:mm:ss").diff(moment($('#time_receiving').val(), "MM-DD-YYYY HH:mm:ss"))).format("mm"));
        var hours2 = parseFloat(moment.utc(moment($('#time_vacuum_cooler').val(), "MM-DD-YYYY HH:mm:ss").diff(moment($('#time_receiving').val(), "MM-DD-YYYY HH:mm:ss"))).format("HH"));
        //var vc = parseFloat(parseFloat(hours + (mins/60))+parseFloat($('#temperature_receiving').val()*parseFloat(hours + (mins/60)))).toFixed(2);
        var vc = parseFloat((parseFloat(hours + (mins/60))*parseFloat($('#temperature_vacuum_cooler').val()))+parseFloat(hours2 + (mins2/60))*$('#temperature_receiving').val()).toFixed(2);
        console.log('hours: ' + parseFloat(hours2 + (mins2/60)));
        console.log('hours2: ' + parseFloat(hours + (mins/60)));
        console.log('vc: ' + vc);
        $(this).val(vc);
    });

    $('#total_temperature').focus(function(event) {
        /* Act on the event */
        $(this).val(parseFloat(parseFloat($('#tlc').val())+parseFloat($('#vacuum_cooler').val())).toFixed(2));
    });


	$(document).ready(function() {
	    jQuery.ajaxSetup({async:false});
		codigo_producto = 0;
        $('.selectpicker').selectpicker({
            dropupAuto: 'true',
            container: 'body',
            size: '5',
            width: '100%',
            style: 'btn-sm btn-info'
        });
        $('.validar_color').selectpicker({
            dropupAuto: 'true',
            container: 'body',
            size: '5',
            width: '100%',
            style: 'btn-sm btn-default',
            tickIcon: 'fa fa-check'
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
	    var fecha_hoy   = new Date(<?php echo time() * 1000; ?>);
	    var hoy         = (fecha_hoy.getMonth()+1) + "/" + fecha_hoy.getDate() + "/" + fecha_hoy.getFullYear();
	    //var hoy_18_anios_atras        = (fecha_hoy.getMonth()+1) + "/" + fecha_hoy.getDate() + "/" + (fecha_hoy.getFullYear()-18);
	    var hoy_default = fecha_hoy.getFullYear() + "-" + (fecha_hoy.getMonth()+1) + "-" + fecha_hoy.getDate();

	    $('.date').datetimepicker({
	        //disabledHours: true,
	        locale: 'es',
	        //minDate: hoy,
	        //keepOpen: true,
	        format: 'MM-DD-YYYY HH:mm:ss',
	        //defaultDate: hoy + ' 08:00 am',
	        //direction: 'auto',
	        icons: {
	                    time: "fas fa-clock",
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
	        format: 'MM-DD-YYYY HH:mm:ss',
	        //defaultDate: '12:00',
	        //direction: 'auto',
	        icons: {
	                    time: "fas fa-clock",
	                    date: "fa fa-calendar",
	                    up: "fa fa-arrow-up",
	                    down: "fa fa-arrow-down",
	                    previous: 'fa fa-arrow-left',
	                    next: 'fa fa-arrow-right',
	                }
	    });
	    $('#div_inicial_time_harvest').datetimepicker({
	        //disabledHours: true,
	        locale: 'es',
	        //minDate: hoy,
	        //keepOpen: true,
	        format: 'MM-DD-YYYY HH:mm:ss',
	        //defaultDate: '12:00',
	        //direction: 'auto',
	        icons: {
	                    time: "fas fa-clock",
	                    date: "fa fa-calendar",
	                    up: "fa fa-arrow-up",
	                    down: "fa fa-arrow-down",
	                    previous: 'fa fa-arrow-left',
	                    next: 'fa fa-arrow-right',
	                }
	    }).on('dp.hide',function (e){
	        $('#div_final_time_harvest').data("DateTimePicker").minDate(e.date);
	    });
	    $('#div_final_time_harvest').datetimepicker({
	        //disabledHours: true,
	        locale: 'es',
	        //minDate: hoy,
	        //keepOpen: true,
	        format: 'MM-DD-YYYY HH:mm:ss',
	        //defaultDate: '12:00',
	        //direction: 'auto',
	        icons: {
	                    time: "fas fa-clock",
	                    date: "fa fa-calendar",
	                    up: "fa fa-arrow-up",
	                    down: "fa fa-arrow-down",
	                    previous: 'fa fa-arrow-left',
	                    next: 'fa fa-arrow-right',
	                }
	    }).on('dp.hide',function (e){
	        $('#div_inicial_time_harvest').data("DateTimePicker").maxDate(e.date);
	    });
	    $('#div_time_vacuum_cooler').datetimepicker({
	        //disabledHours: true,
	        locale: 'es',
	        //minDate: hoy,
	        //keepOpen: true,
	        format: 'MM-DD-YYYY HH:mm:ss',
	        //defaultDate: '12:00',
	        //direction: 'auto',
	        icons: {
	                    time: "fas fa-clock",
	                    date: "fa fa-calendar",
	                    up: "fa fa-arrow-up",
	                    down: "fa fa-arrow-down",
	                    previous: 'fa fa-arrow-left',
	                    next: 'fa fa-arrow-right',
	                }
	    }).on('dp.hide',function (e){
	        $('#div_time_pickup').data("DateTimePicker").minDate(e.date);
	    });
	    $('#div_time_pickup').datetimepicker({
	        //disabledHours: true,
	        locale: 'es',
	        //minDate: hoy,
	        //keepOpen: true,
	        format: 'MM-DD-YYYY HH:mm:ss',
	        //defaultDate: '12:00',
	        //direction: 'auto',
	        icons: {
	                    time: "fas fa-clock",
	                    date: "fa fa-calendar",
	                    up: "fa fa-arrow-up",
	                    down: "fa fa-arrow-down",
	                    previous: 'fa fa-arrow-left',
	                    next: 'fa fa-arrow-right',
	                }
	    }).on('dp.hide',function (e){
	        $('#div_time_vacuum_cooler').data("DateTimePicker").maxDate(e.date);
	    });
		//Máscaras
        $('.letras').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z-¡!¿?,.() 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/, optional: false}}});
        $('.letras10').mask('SSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z 0-9]/, optional: false}}});
        $('.letras45').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z 0-9]/, optional: false}}});
        $('.numeros4').mask('9999');
        $('.numero_carga').mask('99999999');
        $('.monto').mask("#,##0.000", {reverse: true, maxlength: false});
        $('.decimal7_3').mask('9999.999');
        $('.decimal6_3').mask('999.999');
        $('.decimal6_2').mask('9999.99');
        $('.decimal5_2').mask('999.99');
        $('.decimal11_3').mask('99999999.999');
        qua_constructor_listado_productos_por_pais_departamento('cod_producto',<?php echo $_SESSION['cod_pais']; ?>, <?php echo $_SESSION['cod_departamento']; ?>);
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
	    if (count($REPORTE))
	    {
	    	?>
	    	jQuery.ajaxSetup({async:false});
	    	$('#num_orden_compra').val("<?php echo utf8_encode($REPORTE[0]['num_orden_compra']); ?>");
	    	$('#yellow_leaves2').val("<?php echo utf8_encode($REPORTE[0]['yellow_leaves2']); ?>");
	    	$('#fecha_orden').val("<?php echo utf8_encode($REPORTE[0]['fecha_orden']); ?>");
	    	$('#num_lote').val("<?php echo utf8_encode($REPORTE[0]['num_lote']); ?>");
	    	$('#num_lote_ranch').val("<?php echo utf8_encode($REPORTE[0]['num_lote_ranch']); ?>");
	    	$('#inicial_size_harvest').val("<?php echo utf8_encode($REPORTE[0]['inicial_size_harvest']); ?>");
	    	$('#final_size_harvest').val("<?php echo utf8_encode($REPORTE[0]['final_size_harvest']); ?>");
	    	$('#other_defects').val("<?php echo utf8_encode($REPORTE[0]['other_defects']); ?>");
	    	$('#size_range_porcentage').val("<?php echo utf8_encode($REPORTE[0]['size_range_porcentage']); ?>");
	    	$('#inicial_size_range').val("<?php echo utf8_encode($REPORTE[0]['inicial_size_range']); ?>");
	    	$('#final_size_range').val("<?php echo utf8_encode($REPORTE[0]['final_size_range']); ?>");
	    	$('#size_range1').val("<?php echo utf8_encode($REPORTE[0]['size_range1']); ?>");
	    	$('#size_range2').val("<?php echo utf8_encode($REPORTE[0]['size_range2']); ?>");
	    	$('#other_defects_ha').val("<?php echo utf8_encode($REPORTE[0]['other_defects_ha']); ?>");
	    	$('#inicial_time_harvest').val("<?php echo utf8_encode($REPORTE[0]['inicial_time_harvest']); ?>");
	    	$('#final_time_harvest').val("<?php echo utf8_encode($REPORTE[0]['final_time_harvest']); ?>");
	    	$('#temperature_product').val("<?php echo utf8_encode($REPORTE[0]['temperature_product']); ?>");
	    	/*$('#inicial_average_tote_weight_reported').val("<?php echo utf8_encode($REPORTE[0]['inicial_average_tote_weight_reported']); ?>");
	    	$('#final_average_tote_weight_reported').val("<?php echo utf8_encode($REPORTE[0]['final_average_tote_weight_reported']); ?>");
	    	$('#real_average_tote_weight').val("<?php echo utf8_encode($REPORTE[0]['real_average_tote_weight']); ?>");*/
	    	$('#time_receiving').val("<?php echo utf8_encode($REPORTE[0]['time_receiving']); ?>");
	    	$('#total_load_lbs_goal').val("<?php echo utf8_encode($REPORTE[0]['total_load_lbs_goal']); ?>");
	    	$('#load_weight_received').val("<?php echo utf8_encode($REPORTE[0]['load_weight_received']); ?>");
	    	$('#average_tote_weight').val("<?php echo utf8_encode($REPORTE[0]['average_tote_weight']); ?>");
	    	$('#temperature_receiving').val("<?php echo utf8_encode($REPORTE[0]['temperature_receiving']); ?>");
	    	$('#time_vacuum_cooler').val("<?php echo utf8_encode($REPORTE[0]['time_vacuum_cooler']); ?>");
	    	$('#temperature_vacuum_cooler').val("<?php echo utf8_encode($REPORTE[0]['temperature_vacuum_cooler']); ?>");
	    	$('#time_pickup').val("<?php echo utf8_encode($REPORTE[0]['time_pickup']); ?>");
	    	$('#tlc').val("<?php echo utf8_encode($REPORTE[0]['tlc']); ?>");
	    	$('#vacuum_cooler').val("<?php echo utf8_encode($REPORTE[0]['vacuum_cooler']); ?>");
	    	$('#total_temperature').val("<?php echo utf8_encode($REPORTE[0]['total_temperature']); ?>");
	    	/*$('#pickup_truck_checkin').val("<?php echo utf8_encode($REPORTE[0]['pickup_truck_checkin']); ?>");*/
	    	$('#acres_cosechados').val("<?php echo utf8_encode($REPORTE[0]['acres_cosechados']); ?>");
	    	$('#comentarios').val("<?php echo utf8_encode($REPORTE[0]['comentarios']); ?>");
	    	$('#cod_producto').selectpicker('val',"<?php echo utf8_encode($REPORTE[0]['cod_producto']); ?>");
	    	$('#dark_green_color').selectpicker('val',"<?php echo utf8_encode($REPORTE[0]['dark_green_color']); ?>");
	    	$('#yellow_leaves').selectpicker('val',"<?php echo utf8_encode($REPORTE[0]['yellow_leaves']); ?>");
	    	$('#weeds').selectpicker('val',"<?php echo utf8_encode($REPORTE[0]['weeds']); ?>");
	    	$('#optimal_soil_water_capacity').selectpicker('val',"<?php echo utf8_encode($REPORTE[0]['optimal_soil_water_capacity']); ?>");
	    	$('#right_density').selectpicker('val',"<?php echo utf8_encode($REPORTE[0]['right_density']); ?>");
	    	$('#select_size_range').selectpicker('val',"<?php echo utf8_encode($REPORTE[0]['select_size_range']); ?>");
	    	$('#select_size_range1').selectpicker('val',"<?php echo utf8_encode($REPORTE[0]['select_size_range1']); ?>");
	    	$('#select_size_range2').selectpicker('val',"<?php echo utf8_encode($REPORTE[0]['select_size_range2']); ?>");
	    	$('#select_yellow_leaves').selectpicker('val',"<?php echo utf8_encode($REPORTE[0]['select_yellow_leaves']); ?>");
	    	$('#select_other_defects_ha').selectpicker('val',"<?php echo utf8_encode($REPORTE[0]['select_other_defects_ha']); ?>");
	    	$('#dew_leaf').selectpicker('val',"<?php echo utf8_encode($REPORTE[0]['dew_leaf']); ?>");
	    	$('#hydrocooling').selectpicker('val',"<?php echo utf8_encode($REPORTE[0]['hydrocooling']); ?>");
	    	$('#number_cut').selectpicker('val',"<?php echo utf8_encode($REPORTE[0]['number_cut']); ?>");
	    	$('.selectpicker').selectpicker('refresh');
	    	//$('#total_temperature').trigger('focus');
	    	$('#total_temperature').val(parseFloat(parseFloat($('#tlc').val())+parseFloat($('#vacuum_cooler').val())).toFixed(2));
	    	jQuery.ajaxSetup({async:true});
	    	<?php
	    }
	    if (count($REPORTES)) 
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
	    codigo_reporte = <?php echo $cod_reporte;?>;
	    $('#modal_loading').modal('hide');
	    jQuery.ajaxSetup({async:true});
	});

    $('#btn_guardar_load_report').click(function(event) {
        /* Act on the event */
        var error = 0;
        $(".input.requerido_load_report").map(function(){
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
        $(".selectpicker.requerido_load_report").map(function(){
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
        if(error == 0)
        {
            $('#modal_load_report').modal('hide');
            qua_guardar_load_report(codigo_reporte);
            //plan_guardar_aplicar_quimico(cod_aplicacion_quimico, codigo_plantacion);
        }
        else
        {
            grl_mensaje('You must fill the empty fields','Debe llenar los campos vacíos','warning');
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
                                        <th width="12%" class="translate" data-traducir_english="Product" data-traducir_spanish="Producto">Producto</th>
                                        <th width="12%" class="translate" data-traducir_english="Order #" data-traducir_spanish="Nro. Orden">Nro. Orden</th>
                                        <th width="12%" class="translate" data-traducir_english="Order Date" data-traducir_spanish="Fecha Orden">Fecha Orden</th>
                                        <th width="12%" class="translate" data-traducir_english="Lot Code" data-traducir_spanish="Código Lote">Código Lote</th>
                                        <th width="12%" class="translate" data-traducir_english="Ranch Lot Code" data-traducir_spanish="código lote rancho">código lote rancho</th>
                                        <th width="12%" class="translate" data-traducir_english="Acres Harvested" data-traducir_spanish="Acres Cosechados">Acres Cosechados</th>
                                        <th width="12%" class="translate" data-traducir_english="Comments" data-traducir_spanish="Comentarios">Comentarios</th>
                                        <th width="12%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>
                                        <th width="12%" class="translate" data-traducir_english="Entry" data-traducir_spanish="Ingreso">Ingreso</th>
	                                </tr>
	                        </thead>
	                        <tbody id="cuerpo_tabla">
	                            <?php
	                            if(count($REPORTES))
	                            {
	                            	$correlativo = 1;
                                    foreach ($REPORTES as $reporte)
                                    {
                                        ?>
                                        <tr>
                                            <td onclick="qua_vista_load_report(<?php echo utf8_encode($reporte['cod_reporte']);?>)"><?php echo $correlativo; ?></td>
                                            <td onclick="qua_vista_load_report(<?php echo utf8_encode($reporte['cod_reporte']);?>)"><?php echo utf8_encode($reporte['nombre_producto']); ?></td>
                                            <td onclick="qua_vista_load_report(<?php echo utf8_encode($reporte['cod_reporte']);?>)"><?php echo utf8_encode($reporte['num_orden_compra']); ?></td>
                                            <td onclick="qua_vista_load_report(<?php echo utf8_encode($reporte['cod_reporte']);?>)"><?php echo utf8_encode($reporte['fecha_orden']); ?></td>
                                            <td onclick="qua_vista_load_report(<?php echo utf8_encode($reporte['cod_reporte']);?>)"><?php echo utf8_encode($reporte['num_lote']); ?></td>
                                            <td onclick="qua_vista_load_report(<?php echo utf8_encode($reporte['cod_reporte']);?>)"><?php echo utf8_encode($reporte['num_lote_ranch']); ?></td>
                                            <td onclick="qua_vista_load_report(<?php echo utf8_encode($reporte['cod_reporte']);?>)"><?php echo utf8_encode($reporte['acres_cosechados']); ?></td>
                                            <td onclick="qua_vista_load_report(<?php echo utf8_encode($reporte['cod_reporte']);?>)"><?php echo utf8_encode($reporte['comentarios']); ?></td>
                                            <td onclick="qua_vista_load_report(<?php echo utf8_encode($reporte['cod_reporte']);?>)"><?php echo utf8_encode($reporte['nombre_usuario']); ?></td>
                                            <td onclick="qua_vista_load_report(<?php echo utf8_encode($reporte['cod_reporte']);?>)"><?php echo utf8_encode($reporte['date_insert']); ?></td>
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
	                <h1 class="translate" data-traducir_english="Load Order Assesment" data-traducir_spanish="Evaluación de Orden de Carga">Evaluación de Orden de Carga</h1>
	            </div>
			</div>
            <div class="col-md-12">
            	<div class="row">
	            	<div class="form-group input-group-sm col-md-3" id="">
                        <label for="cod_producto" class="translate" data-traducir_english="Product" data-traducir_spanish="Producto">Producto</label>
                        <select class="selectpicker show-menu-arrow requerido_load_report" title="Select" id="cod_producto" name="cod_producto">
                        </select>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="num_orden_compra" class="translate" data-traducir_english="Order #" data-traducir_spanish="No. Orden Compra">No. Orden Compra</label>
                            <input type="text"class="form-control letras10 input" id="num_orden_compra" name="num_orden_compra">
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3">
                        <label for="fecha_orden" class="translate" data-traducir_english="Order Date" data-traducir_spanish="Fecha Orden">Fecha Orden</label>
                        <div class='input-group input-group-sm fecha-planeada date' id='datetimepicker1'>
                            <span class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </span>
                            <input type='text' class="form-control input requerido_load_report" id="fecha_orden" />
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="num_lote" class="translate" data-traducir_english="Lot Code" data-traducir_spanish="Codigo Lote">Codigo Lote</label>
                            <input type="text"class="form-control letras10 input requerido_load_report" id="num_lote" name="num_lote">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="num_lote_ranch" class="translate" data-traducir_english="Ranch Lot Code" data-traducir_spanish="Codigo Lote Rancho">Codigo Lote Rancho</label>
                            <input type="text"class="form-control letras10 input requerido_load_report" id="num_lote_ranch" name="num_lote_ranch">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <h3 class="translate" data-traducir_english="Pre-harvest Assessment" data-traducir_spanish="Pre-Asignación de cosecha">Pre-Asignación de cosecha</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="inicial_size_harvest" class="translate" data-traducir_english="Min Size" data-traducir_spanish="Tamaño mínimo">Tamaño mínimo</label>
                            <input type="text"class="form-control decimal7_3 input requerido_load_report" id="inicial_size_harvest" name="inicial_size_harvest">
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="final_size_harvest" class="translate" data-traducir_english="Max Size" data-traducir_spanish="Tamaño máximo">Tamaño máximo</label>
                            <input type="text"class="form-control decimal7_3 input requerido_load_report" id="final_size_harvest" name="final_size_harvest">
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <label for="dark_green_color" class="translate" data-traducir_english="Dark Green Color" data-traducir_spanish="Color verde oscuro">Color verde oscuro</label>
                        <div class="form-group show-tick">
                            <select class="selectpicker show-menu-arrow validar_color requerido_load_report" id="dark_green_color" name="dark_green_color" data-live-search="true" title="Select">
                                <option value="-b">Select - Seleccione</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <label for="weeds" class="translate" data-traducir_english="Weeds" data-traducir_spanish="Malezas">Malezas</label>
                        <div class="form-group show-tick">
                            <select class="selectpicker show-menu-arrow validar_color requerido_load_report" id="weeds" name="weeds" data-live-search="true" title="Select">
                                <option value="-b">Select - Seleccione</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group input-group-sm col-md-3" id="">
                        <label for="yellow_leaves" class="translate" data-traducir_english="Yellow Leaves" data-traducir_spanish="Hojas amarillas">Hojas amarillas</label>
                        <div class="form-group show-tick">
                            <select class="selectpicker show-menu-arrow validar_color requerido_load_report" id="yellow_leaves" name="yellow_leaves" data-live-search="true" title="Select">
                                <option value="-b">Select - Seleccione</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <label for="optimal_soil_water_capacity" class="translate" data-traducir_english="Optimal Soil Water Capacity" data-traducir_spanish="Capacidad de campo">Capacidad de campo</label>
                        <div class="form-group show-tick">
                            <select class="selectpicker show-menu-arrow validar_color requerido_load_report" id="optimal_soil_water_capacity" name="optimal_soil_water_capacity" data-live-search="true" title="Select">
                                <option value="-b">Select - Seleccione</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <label for="right_density" class="translate" data-traducir_english="Right Density" data-traducir_spanish="Densidad adecuada">Densidad adecuada</label>
                        <div class="form-group show-tick">
                            <select class="selectpicker show-menu-arrow validar_color requerido_load_report" id="right_density" name="right_density" data-live-search="true" title="Select">
                                <option value="-b">Select - Seleccione</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group input-group-sm col-md-12" id="">
                        <label for="other_defects" class="translate" data-traducir_english="Other Defects" data-traducir_spanish="Otros defectos">Otros defectos</label>
                        <div class="form-group show-tick">
                            <textarea maxlength="2500" align="left" style="height:100px; width:100%; resize: none;" class="form-control limpiar input requerido_load_report" rows="2" id="other_defects" style="width: 100%"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <h3 class="translate" data-traducir_english="Harvest Assessment" data-traducir_spanish="Asignación de cosecha">Asignación de cosecha</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="size_range_porcentage" class="translate" data-traducir_english="Percentage Size Range on Spec" data-traducir_spanish="Porcentaje promedio de tamaño">Porcentaje promedio de tamaño</label>
                            <input type="text"class="form-control decimal6_3 input " id="size_range_porcentage" name="size_range_porcentage">
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="inicial_size_range" class="translate" data-traducir_english="Initial Size Range" data-traducir_spanish="Rango Inicial de tamaño">Rango Inicial de tamaño</label>
                            <input type="text"class="form-control decimal6_3 input " id="inicial_size_range" name="inicial_size_range">
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="final_size_range" class="translate" data-traducir_english="Final Size Range" data-traducir_spanish="Rango final de tamaño">Rango final de tamaño</label>
                            <input type="text"class="form-control decimal6_3 input " id="final_size_range" name="final_size_range">
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <label for="select_size_range" class="translate" data-traducir_english="Select Size Range" data-traducir_spanish="Seleccionar rango de tamaño">Seleccionar rango de tamaño</label>
                        <div class="form-group show-tick">
                            <select class="selectpicker show-menu-arrow " id="select_size_range" name="select_size_range" data-live-search="true" title="Select">
                                <option value="-b">Select - Seleccione</option>
                                <option value="1">% on spec</option>
                                <option value="0">% out of spec</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="size_range1" class="translate" data-traducir_english="% Above Size Range" data-traducir_spanish="Rango de tamaño">Rango de tamaño</label>
                            <input type="text"class="form-control decimal5_2 input " id="size_range1" name="size_range1">
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <label for="select_size_range1" class="translate" data-traducir_english="Select Size Range" data-traducir_spanish="Seleccionar rango de tamaño">Seleccionar rango de tamaño</label>
                        <div class="form-group show-tick">
                            <select class="selectpicker show-menu-arrow " id="select_size_range1" name="select_size_range1" data-live-search="true" title="Select">
                                <option value="-b">Select - Seleccione</option>
                                <option value="1">% on spec</option>
                                <option value="0">% out of spec</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="size_range2" class="translate" data-traducir_english="% Below Size Range" data-traducir_spanish="Rango de tamaño">Rango de tamaño</label>
                            <input type="text"class="form-control decimal5_2 input " id="size_range2" name="size_range2">
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <label for="select_size_range2" class="translate" data-traducir_english="Select Size Range" data-traducir_spanish="Seleccione rango de tamaño">Seleccione rango de tamaño</label>
                        <div class="form-group show-tick">
                            <select class="selectpicker show-menu-arrow " id="select_size_range2" name="select_size_range2" data-live-search="true" title="Select">
                                <option value="-b">Select - Seleccione</option>
                                <option value="1">% on spec</option>
                                <option value="0">% out of spec</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="other_defects_ha" class="translate" data-traducir_english="% Other Defects" data-traducir_spanish="% Otros defectos">% Otros defectos</label>
                            <input type="text"class="form-control decimal5_2 input " id="other_defects_ha" name="other_defects_ha">
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <label for="select_other_defects_ha" class="translate" data-traducir_english="Select Other Defects" data-traducir_spanish="Seleccionar Otros defectos">Seleccionar Otros defectos</label>
                        <div class="form-group show-tick">
                            <select class="selectpicker show-menu-arrow " id="select_other_defects_ha" name="select_other_defects_ha" data-live-search="true" title="Select">
                                <option value="-b">Select - Seleccione</option>
                                <option value="1">% on spec</option>
                                <option value="0">% out of spec</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <label for="dew_leaf" class="translate" data-traducir_english="Dew of Leaf" data-traducir_spanish="Rocio de la hoja">Rocio de la hoja</label>
                        <div class="form-group show-tick">
                            <select class="selectpicker show-menu-arrow " id="dew_leaf" name="dew_leaf" data-live-search="true" title="Select">
                                <option value="-b">Select - Seleccione</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3">
                        <label for="inicial_time_harvest" class="translate" data-traducir_english="Initial Time Harvest" data-traducir_spanish="Tiempo inicial de cosecha">Tiempo inicial de cosecha</label>
                        <div class='input-group input-group-sm fecha-planeada time' id='div_inicial_time_harvest'>
                            <span class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </span>
                            <input type='text' class="form-control input" id="inicial_time_harvest" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group input-group-sm col-md-3">
                        <label for="final_time_harvest" class="translate" data-traducir_english="Final Time Harvest" data-traducir_spanish="Tiempo final de cosecha">Tiempo final de cosecha</label>
                        <div class='input-group input-group-sm fecha-planeada time' id='div_final_time_harvest'>
                            <span class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </span>
                            <input type='text' class="form-control input" id="final_time_harvest" />
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="temperature_product" class="translate" data-traducir_english="Temperature of Product" data-traducir_spanish="Temperatura del producto">Temperatura del producto</label>
                            <input type="text"class="form-control decimal6_2 input " id="temperature_product" name="temperature_product">
                        </div>
                    </div>
                    <!-- <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="inicial_average_tote_weight_reported" class="translate" data-traducir_english="Initial Average Tote Weight Reported" data-traducir_spanish="Peso Promedio inicial de la caja reportado">Peso Promedio inicial de la caja reportado</label>
                            <input type="text"class="form-control monto input " id="inicial_average_tote_weight_reported" name="inicial_average_tote_weight_reported">
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="final_average_tote_weight_reported" class="translate" data-traducir_english="Final Average Tote Weight Reported" data-traducir_spanish="Peso promedio final de la caja reportada">Peso promedio final de la caja reportada</label>
                            <input type="text"class="form-control monto input " id="final_average_tote_weight_reported" name="final_average_tote_weight_reported">
                        </div>
                    </div>
                </div>
                <div class="row">
                     <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="real_average_tote_weight" class="translate" data-traducir_english="Real Average Tote Weight" data-traducir_spanish="Porcentaje real del peso de la caja">Porcentaje real del peso de la caja</label>
                            <input type="text"class="form-control monto input " id="real_average_tote_weight" name="real_average_tote_weight">
                        </div>
                    </div> -->
                    <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="acres_cosechados" class="translate" data-traducir_english="Acres Harvested" data-traducir_spanish="Acres Cosechados">Acres Cosechados</label>
                            <input type="text"class="form-control monto input " id="acres_cosechados" name="acres_cosechados">
                        </div>
                    </div>
                </div>
                <div class="row">                	
                    <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="yellow_leaves2" class="translate" data-traducir_english="Yellow Leaves" data-traducir_spanish="Hojas Amarillas">Hojas Amarillas</label>
                            <input type="text"class="form-control decimal5_2 input " id="yellow_leaves2" name="yellow_leaves2">
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <label for="select_yellow_leaves" class="translate" data-traducir_english="Select Yellow Leaves" data-traducir_spanish="Seleccionar hoja amarilla">Seleccionar hoja amarilla</label>
                        <div class="form-group show-tick">
                            <select class="selectpicker show-menu-arrow " id="select_yellow_leaves" name="select_yellow_leaves" data-live-search="true" title="Select">
                                <option value="-b">Select - Seleccione</option>
                                <option value="1">% on spec</option>
                                <option value="0">% out of spec</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group input-group-sm col-md-12" id="">
                        <label for="comentarios" class="translate" data-traducir_english="Comments" data-traducir_spanish="Comentarios">Comentarios</label>
                        <div class="form-group show-tick">
                            <textarea maxlength="2500" align="left" style="height:100px; width:100%; resize: none;" class="form-control limpiar" rows="2" id="comentarios" style="width: 100%"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <h3 class="translate" data-traducir_english="Vacuum Cooler" data-traducir_spanish="Enfriador al vacío">Enfriador al vacío</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group input-group-sm col-md-3">
                        <label for="time_receiving" class="translate" data-traducir_english="Time of Receiving" data-traducir_spanish="Tiempo al recibo">Tiempo al recibo</label>
                        <div class='input-group input-group-sm fecha-planeada time' id='datetimepicker1'>
                            <span class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </span>
                            <input type='text' class="form-control input" id="time_receiving" />
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="total_load_lbs_goal" class="translate" data-traducir_english="Total Load Lbs Goal" data-traducir_spanish="Objetivo de libras totales">Objetivo de libras totales</label>
                            <input type="text"class="form-control peso input " id="total_load_lbs_goal" name="total_load_lbs_goal">
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="load_weight_received" class="translate" data-traducir_english="Load Weight Received" data-traducir_spanish="Peso de la carga al recibo">Peso de la carga al recibo</label>
                            <input type="text"class="form-control peso input " id="load_weight_received" name="load_weight_received">
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="average_tote_weight" class="translate" data-traducir_english="Average Tote Weight" data-traducir_spanish="Peso de la caja promedio">Peso de la caja promedio</label>
                            <input type="text"class="form-control peso input " id="average_tote_weight" name="average_tote_weight">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="temperature_receiving" class="translate" data-traducir_english="Temperature of Receiving" data-traducir_spanish="Temperatura al recibo">Temperatura al recibo</label>
                            <input type="text"class="form-control decimal6_2 input " id="temperature_receiving" name="temperature_receiving">
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3">
                        <label for="time_vacuum_cooler" class="translate" data-traducir_english="Time of VC" data-traducir_spanish="Tiempo del enfriador al vacío">Tiempo del enfriador al vacío</label>
                        <div class='input-group input-group-sm fecha-planeada time' id='div_time_vacuum_cooler'>
                            <span class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </span>
                            <input type='text' class="form-control input" id="time_vacuum_cooler" />
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="temperature_vacuum_cooler" class="translate" data-traducir_english="Temperature of VC" data-traducir_spanish="Temperatura del enfriador al vacío">Temperatura del enfriador al vacío</label>
                            <input type="text"class="form-control decimal6_2 input " id="temperature_vacuum_cooler" name="temperature_vacuum_cooler">
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <label for="hydrocooling" class="translate" data-traducir_english="Hydrocooling" data-traducir_spanish="Enfriado en agua">Enfriado en agua</label>
                        <div class="form-group show-tick">
                            <select class="selectpicker show-menu-arrow " id="hydrocooling" name="hydrocooling" data-live-search="true" title="Select">
                                <option value="-b">Select - Seleccione</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group input-group-sm col-md-3">
                        <label for="time_pickup" class="translate" data-traducir_english="Time of PickUp" data-traducir_spanish="Tiempo a la colecta">Tiempo a la colecta</label>
                        <div class='input-group input-group-sm fecha-planeada time' id='div_time_pickup'>
                            <span class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </span>
                            <input type='text' class="form-control input" id="time_pickup" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <h3 class="translate" data-traducir_english="Hours Temperature" data-traducir_spanish="Horas de temperatura">Horas de temperatura</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="tlc" class="translate" data-traducir_english="TLC" data-traducir_spanish="TLC">TLC</label>
                            <input type="text"class="form-control decimal6_2 input " id="tlc" name="tlc">
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="vacuum_cooler" class="translate" data-traducir_english="Vacuum Cooler" data-traducir_spanish="Enfriador al vacío">Enfriador al vacío</label>
                            <input type="text"class="form-control decimal6_2 input " id="vacuum_cooler" name="vacuum_cooler">
                        </div>
                    </div>
                    <div class="form-group input-group-sm col-md-3" id="">
                        <div class="form-group input-group-sm">
                            <label for="total_temperature" class="translate" data-traducir_english="Total Temperature" data-traducir_spanish="Temperatura total">Temperatura total</label>
                            <input type="text"class="form-control decimal6_2 input " id="total_temperature" name="total_temperature">
                        </div>
                    </div>
                    <!-- <div class="form-group input-group-sm col-md-3">
                        <label for="pickup_truck_checkin" class="translate" data-traducir_english="PickUp Truck CheckIn" data-traducir_spanish="Hora de llegada del camión">Hora de llegada del camión</label>
                        <div class='input-group input-group-sm fecha-planeada time' id='datetimepicker1'>
                            <span class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </span>
                            <input type='text' class="form-control input" id="pickup_truck_checkin" />
                        </div>
                    </div> -->
                </div>
                <div class="row">
                    <div class="form-group input-group-sm col-md-3" id="">
                        <label for="number_cut" class="translate" data-traducir_english="Cut" data-traducir_spanish="Corte">Corte</label>
                        <div class="form-group show-tick">
                            <select class="selectpicker show-menu-arrow " id="number_cut" name="number_cut" data-live-search="true" title="Select">
                                <option value="-b">Select - Seleccione</option>
                                <option value="1">First - Primero</option>
                                <option value="2">Second - Segundo</option>
                                <option value="3">Third - Tercero</option>
                            </select>
                        </div>
                    </div>
				</div>
            </div>
		</div>
		<div class="panel-footer" align="right">
			<button class="btn btn-sm btn-primary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" type="button" id="btn_guardar_load_report">Guardar</button>
		</div>
	</div>
</body>