<?php 
/*
* 	Registro de periodos fiscales,
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

$PERIODOS = $DB_CONF->conf_listado_periodos_fiscales();

$cod_periodo_fiscal = $_POST['cod_periodo_fiscal'];
if (!isset($_POST['cod_periodo_fiscal'])) {
    $cod_periodo_fiscal = 0;
}

$PERIODO = $DB_CONF->conf_obtener_info_periodo_fiscal($cod_periodo_fiscal); 

function getDatesFromRange($start, $end, $format = 'Y-m-d') {
    $array = array();
    $interval = new DateInterval('P1D');

    $realEnd = new DateTime($end);
    $realEnd->add($interval);

    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

    foreach($period as $date) { 
        $array[] = $date->format($format); 
    }

    return $array;
}

if(count($PERIODOS))
{
    $array_fechas = '"';
    foreach ($PERIODOS as $periodo) 
    {
        if($periodo['activo'] == 1 && $periodo['cod_periodo_fiscal'] != $cod_periodo_fiscal)
        {
            $array_fechas .= implode('","', getDatesFromRange($periodo['fecha_inicio'],$periodo['fecha_final'])).'","';
        }
    }
    $array_fechas .= '"';
}
?>

<script type="text/javascript">

    //Máscaras
    //$('#lugar_curso').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z-¡!¿?,.() 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/, optional: false}}});
    $('.letras').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z-¡!¿?,.() 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/, optional: false}}});
	$('.anio').mask('9999');
    $('.numero').mask('9999');
    $(document).ready(function() {
        jQuery.ajaxSetup({async:false});
        grl_overlay_loading('');
        codigo_periodo_fiscal = 0;
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
        conf_constructor_listado_granjas();
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
        $('.date').datetimepicker({
            inline: true,
            sideBySide: true,
            format: 'MM-DD-YYYY',
            viewMode:"days",
            //locale: 'es',
            locale:  moment.locale('en', {
                week: { dow: 0 }
            }),
            defaultDate: $(this).data('fecha')
        });
        $.each($('.date'), function(index, val) {
            /* iterate through array or object */
            //$(this).data("DateTimePicker").defaultDate(moment($(this).data('fecha')));
            $(this).data("DateTimePicker").minDate(moment($(this).data('fecha_inicio')));
            $(this).data("DateTimePicker").maxDate(moment($(this).data('fecha_final')));
        });
        $(".picker-switch").addClass('disable').on('click', function(e) {
          e.stopPropagation();
        });
        <?php
        if (count($PERIODO)) 
        {
            ?>
            $('#anio_periodo').val("<?php echo utf8_encode($PERIODO[0]['anio_periodo']); ?>");
            $('#num_periodo').val("<?php echo utf8_encode($PERIODO[0]['num_periodo']); ?>");
            $('#fecha_inicio').val("<?php echo utf8_encode($PERIODO[0]['fecha_inicio']); ?>");
            $('#fecha_final').val("<?php echo utf8_encode($PERIODO[0]['fecha_final']); ?>");
            <?php
        }
        ?>
        codigo_periodo_fiscal = <?php echo $cod_periodo_fiscal;?>;
        grl_traducir_interfaz(($('#checkbox_translate').attr('checked') ? 1 : 0));
        $('#modal_loading').modal('hide');
        jQuery.ajaxSetup({async:true});
	});
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
        defaultDate: hoy,
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

    $('#btn_guardar_periodo_fiscal').click(function(event) {
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
        if($('#anio_periodo').val() < <?php echo date('Y'); ?>)
        {
            error = 2;
            $('#anio_periodo').parent('div').addClass('has-error');
        }
        if (error == 0) 
        {
            jQuery.ajaxSetup({async:false});
            conf_guardar_periodo_fiscal(codigo_periodo_fiscal);
            jQuery.ajaxSetup({async:true});
        }
        else
        {
            if(error == 2)
            {
                grl_mensaje('Debe ingresar año actual o mayor','favor verificar','warning');
            }
            else
            {
                grl_mensaje('Debe llenar todos los campos marcados','favor verificar','warning');
            }
        }
    });

    $('.checkbox_anio').change(function(event) {
        /* Act on the event */
        event.preventDefault();
        event.stopPropagation();
        conf_cambiar_estado_periodo_fiscal_anio($(this).data('id'),($(this).attr('checked') ? 0 : 1));
    });

    $('.checkbox').change(function(event) {
        /* Act on the event */
        event.preventDefault();
        event.stopPropagation();
        conf_cambiar_estado_periodo_fiscal($(this).data('id'),($(this).attr('checked') ? 0 : 1));
    });
</script>
<style type="text/css">
    .material-switch-fiscal > label{
        width: 100%;
    }
    .texto_centrado
    {
        text-align: center;
    }
    .span-dia-su
    {
        width: 14.28%;
        left: 0;
    }
    .span-dia-su
    {
        width: 14.28%;
        left: 14.28%;
    }
    .table-periodos>thead>tr>td
    {
        font-weight: bold;
    }
    .table-periodos>thead>tr>td, .table-periodos>tbody>tr>td, .table-periodos>tfoot>tr>td
    {
        padding: 4px !important;
    }
    .num-semana
    {
        width: 15px!important;
        font-size: 8px;
    }
</style>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <title>Períodos Fiscales</title>
</head>
<body>
    <div id="overlay_loading"></div>
    <div id="message_box"></div>
    <div class="panel panel-default">
        <div class="panel-body" >
            <div class="page-header">
                <h1 class="translate" data-traducir_english="Fiscal Periods" data-traducir_spanish="Períodos Fiscales">Períodos Fiscales </h1><h1><small class="translate" data-traducir_english="Administration of fiscal periods" data-traducir_spanish="Administración de períodos fiscales">Administración de períodos fiscales</small></h1>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group input-group-sm">
                                <label for="anio_periodo" class="translate" data-traducir_english="Year" data-traducir_spanish="Año">Año</label>
                                <input type="text" class="form-control input anio requerido placeholder_translate" data-placeholder_en="Period year" data-placeholder-es="Año de periodo" placeholder="Año de periodo" id="anio_periodo">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group input-group-sm">
                                <label for="num_periodo" class="translate" data-traducir_english="Number" data-traducir_spanish="Número">Número</label>
                                <input type="text" class="form-control input numero requerido placeholder_translate" data-placeholder_en="Period number" data-placeholder_es="Número de periodo" placeholder="Número de periodo" id="num_periodo">
                            </div>
                        </div>
                        <div class='col-md-12'>
				            <div class="form-group">
			                	<label for="fecha_inicio" class="translate" data-traducir_english="Initial Date" data-traducir_spanish="Fecha Inicio">Fecha Inicio</label>
				                <div class='input-group input-group-sm fecha-inicio' id='datetimepicker1'>
				                    <span class="input-group-addon">
				                        <span class="fa fa-calendar"></span>
				                    </span>
				                    <input type='text' class="form-control input requerido" id="fecha_inicio" />
				                </div>
				            </div>
				        </div>
                        <div class='col-md-12'>
				            <div class="form-group">
			                	<label for="fecha_final" class="translate" data-traducir_english="Final Date" data-traducir_spanish="Fecha Final">Fecha Final</label>
				                <div class='input-group input-group-sm fecha-final' id='datetimepicker1'>
				                    <span class="input-group-addon">
				                        <span class="fa fa-calendar"></span>
				                    </span>
				                    <input type='text' class="form-control input requerido" id="fecha_final" />
				                </div>
				            </div>
				        </div>
                        <div class="col-md-12">
                            <button class="btn btn-primary btn-fullwidth translate" type="button" id="btn_guardar_periodo_fiscal" data-traducir_english="Save" data-traducir_spanish="Guardar">Guardar</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 texto_centrado">
                    <div class="panel panel-primary">
                        <!-- Titulo del panel -->
                        <div class="panel-heading translate" data-traducir_english="Registered fiscal period list" data-traducir_spanish="Listado de períodos fiscales registrados">Listado de períodos fiscales registrados</div>
                        <div class="form-group" style="overflow-y: auto;max-height: 800px;overflow-x: hidden;">
                            <!-- listado -->
                            <?php
                            if(count($PERIODOS))
                            {
                                $anio_anterior = $PERIODOS[0]['anio_periodo'];
                                $num_semana = 1;
                                ?>
                                <div class="row">
                                    <div class="col-md-6 col-md-offset-3">
                                        <div class="form-group">
                                            <div class='input-group input-group-sm'>
                                                <span class="input-group-addon">
                                                    <h3><?php echo $PERIODOS[0]['anio_periodo']; ?></h3>
                                                </span>
                                                <span class="input-group-addon">
                                                    <div class="material-switch material-switch-fiscal pull-left">
                                                        <input class="checkbox_anio" id="checkbox_anio_<?php echo utf8_encode($PERIODOS[0]['anio_periodo']);?>" data-id="<?php echo utf8_encode($PERIODOS[0]['anio_periodo']);?>" name="checkbox_anio_<?php echo utf8_encode($PERIODOS[0]['anio_periodo']);?>" type="checkbox" <?php echo ($PERIODOS[0]['activo'] == 1 ? 'checked="checked"':'');?>/>
                                                        <label for="checkbox_anio_<?php echo utf8_encode($PERIODOS[0]['anio_periodo']);?>" class="label-info"></label>
                                                    </div>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                foreach ($PERIODOS as $periodo) {
                                    if ($anio_anterior != $periodo['anio_periodo']) {
                                        ?>
                                        <div class="row">
                                            <div class="col-md-6 col-md-offset-3">
                                                <div class="form-group">
                                                    <div class='input-group input-group-sm'>
                                                        <span class="input-group-addon">
                                                            <h3><?php echo $periodo['anio_periodo']; ?></h3>
                                                        </span>
                                                        <span class="input-group-addon">
                                                            <div class="material-switch material-switch-fiscal pull-left">
                                                                <input class="checkbox_anio" id="checkbox_anio_<?php echo utf8_encode($periodo['anio_periodo']);?>" data-id="<?php echo utf8_encode($periodo['anio_periodo']);?>" name="checkbox_anio_<?php echo utf8_encode($periodo['anio_periodo']);?>" type="checkbox" <?php echo ($periodo['activo'] == 1 ? 'checked="checked"':'');?>/>
                                                                <label for="checkbox_anio_<?php echo utf8_encode($periodo['anio_periodo']);?>" class="label-info"></label>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        $anio_anterior = $periodo['anio_periodo'];
                                    }
                                    ?>
                                    <div class="col-md-3 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class="col-md-6 co-sm-6 col-xs-6">
                                                <div class="material-switch material-switch-fiscal pull-right">
                                                    <input class="checkbox" id="checkbox_<?php echo utf8_encode($periodo['cod_periodo_fiscal']);?>" data-id="<?php echo utf8_encode($periodo['cod_periodo_fiscal']);?>" name="checkbox_<?php echo utf8_encode($periodo['cod_periodo_fiscal']);?>" type="checkbox" <?php echo ($periodo['activo'] == 1 ? 'checked="checked"':'');?>/>
                                                    <label for="checkbox_<?php echo utf8_encode($periodo['cod_periodo_fiscal']);?>" class="label-info"></label>
                                                </div>
                                            </div>
                                        </div>       
                                        <div class="row">
                                            <div class="col-md-12">
                                            <!-- <div id="datetimepicker1" class="date" data-fecha_inicio="<?php echo utf8_encode($periodo['fecha_inicio']);?>" data-fecha_final="<?php echo utf8_encode($periodo['fecha_final']);?>"></div> -->
                                            <!-- <div class="col-md-12">
                                                <span class="span-dia-su">Su</span>
                                                <span class="span-dia-mo">Mo</span>
                                                <span class="span-dia-tu">Tu</span>
                                                <span class="span-dia-we">We</span>
                                                <span class="span-dia-th">Th</span>
                                                <span class="span-dia-fr">Fr</span>
                                                <span class="span-dia-sa">Sa</span>
                                            </div> -->
                                                <?php
                                                    $dates = getDatesFromRange($periodo['fecha_inicio'], $periodo['fecha_final']);
                                                    $contador = 0;
                                                    $limite = count($dates);
                                                    //echo $periodo['fecha_inicio'].' a '.$periodo['fecha_final'].' días: '.$limite;
                                                    //echo '<h4>Period '.$periodo['num_periodo'].'</h4>';
                                                ?>
                                                <table class="table table-bordered table-responsive table-periodos">
                                                    <thead>
                                                        <tr>
                                                            <td colspan="7" align="center"><?php echo 'Period '.$periodo['num_periodo'];?></td>
                                                        </tr>
                                                        <tr>
                                                            <!-- <td class="num-semana"></td> -->
                                                            <td>Su</td>
                                                            <td>Mo</td>
                                                            <td>Tu</td>
                                                            <td>We</td>
                                                            <td>Th</td>
                                                            <td>Fr</td>
                                                            <td>Sa</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <!-- <td class="num-semana"><?php echo $num_semana; $num_semana++;?></td> -->
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Sun' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Mon' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Tue' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Wed' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Thu' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Fri' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Sat' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <!-- <td class="num-semana"><?php echo $num_semana; $num_semana++;?></td> -->
                                                            <td>
                                                                
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Sun' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Mon' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Tue' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Wed' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Thu' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Fri' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Sat' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <!-- <td class="num-semana"><?php echo $num_semana; $num_semana++;?></td> -->
                                                            <td>
                                                                
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Sun' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Mon' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Tue' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Wed' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Thu' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Fri' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Sat' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <!-- <td class="num-semana"><?php echo $num_semana; $num_semana++;?></td> -->
                                                            <td>
                                                                
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Sun' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Mon' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Tue' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Wed' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Thu' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Fri' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Sat' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <!-- <td class="num-semana"><?php echo $num_semana; $num_semana++;?></td> -->
                                                            <td>
                                                                
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Sun' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Mon' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Tue' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Wed' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Thu' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Fri' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                            <td>
                                                                <?php if(date('D',strtotime($dates[$contador])) == 'Sat' && $contador <$limite)
                                                                {
                                                                    echo date('d',strtotime($dates[$contador]));
                                                                    $contador++;
                                                                }
                                                                else
                                                                    echo '&nbsp;';
                                                                ?>                                                            
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button class="btn btn-xs btn-primary btn-fullwidth translate" onclick="conf_vista_periodo_fiscal(<?php echo utf8_encode($periodo['cod_periodo_fiscal']);?>)" type="button" data-traducir_spanish="Actualizar" data-traducir_english="Update">Actualizar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>