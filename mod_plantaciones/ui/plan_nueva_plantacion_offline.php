<?php

/*

 *     Registro de información de los formularios

 *     @author         Jairo Bonilla

 *     @date             2015-07-28

 */

session_start();

/*if (!isset($_SESSION['cod_usuario'])) {

    header('Location: index.php');

}*/



/*CONEXION CON BASE DE DATOS*/

include_once "../../libs/db_classes/db_mysql_conn.php";

include_once "../../libs/db_classes/db_plantaciones.php";

include_once("../../libs/db_classes/db_configuracion.php");

/*INSTANCIAMIENTOS*/

$DB_PLANT   = new db_plantaciones();

$DB_CONG    = new db_configuracion();



$cod_plantacion = $_GET['x1'];



/*if (!isset($_GET['x1'])) {

    $cod_plantacion = 0;

}*/



$PLANTACION     = $DB_PLANT->plan_obtener_info_plantacion($cod_plantacion);

$BLOQUES        = $DB_PLANT->plan_listado_bloques_plantacion($cod_plantacion);

$OBSERVACIONES  = $DB_PLANT->plan_listado_observaciones_plantacion($cod_plantacion);

$APLICACIONES   = $DB_PLANT->plan_listado_aplicaciones_quimicos_plantacion($cod_plantacion);

$BLOQUES_TODOS   = $DB_PLANT->plan_listado_bloques_plantaciones();



$fertilizantes = [ '',

            'ON',

            'OFF',

            'Only Water - Sólo Agua'];

$etapas = [ '',

            'Stubble',

            'Leaf Up Stubbles',

            'High Cress'];

$loose_bunches = [ 'Bunches',

            'Loose'];

$conventional_organic = [ 'Organic',

            'Conventional'];

$question = [ 'No',

            'Yes',

            'NA'];

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <title><?php echo utf8_encode($PLANTACION[0]['nombre_empresa'].'-'.$PLANTACION[0]['anio_plantacion'].'-'.$PLANTACION[0]['num_plantacion'].'-'.$PLANTACION[0]['codigo_temporada']); ?></title>

    <link rel="stylesheet" href="../../libs/css/style.css"> <!-- Resource style -->

    <!-- jQuery -->

    <script type="text/javascript" src="../../libs/jQuery/jquery-2.1.3.js"></script>

    <script type="text/javascript" src="../../libs/jQuery/jquery.mask.min.js"></script>

    <script type="text/javascript" src="../../libs/jQuery/date.js"></script>

    <script type="text/javascript" src="../../libs/jQuery/jquery-ui.js"></script>

    <script type="text/javascript" src="../../libs/jQuery/accounting.min.js"></script>

    <script type="text/javascript" src="../../libs/jQuery/jquery.PrintArea.js"></script>

    <script type="text/javascript" src="../../libs/jQuery/jquery.sticky.js"></script>



    <!-- Bootstrap core CSS -->

    <script src="../../libs/bootstrap/js/bootstrap.min.js"></script>

    <link href="../../libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="../../libs/css/fontAwesome/css/fontAwesome.min.css">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <!-- <script src="../../libs/js/modernizr.js"></script> --> <!-- Modernizr -->

    <!-- <script type="text/javascript" src="../../libs/bootstrap/js/utils.js"></script>

    <script type="text/javascript" src="../../libs/bootstrap/js/tether.js"></script> -->

    <script type="text/javascript" src="../../libs/bootstrap/js//bootstrap.js"></script>

    <script type="text/javascript" src="../../libs/bootstrap/js/jquery.bootstrap-growl.min.js"></script>

    <script type="text/javascript" src="../../libs/bootstrap/js/collapse.js"></script>

    <script type="text/javascript" src="../../libs/bootstrap/js/transition.js"></script>

    <script type="text/javascript" src="../../libs/bootstrap/js/moment.js"></script>

    <script type="text/javascript" src="../../libs/bootstrap/js/moment-range.js"></script>

    <script type="text/javascript" src="../../libs/bootstrap/js/spin.min.js"></script>

    <script type="text/javascript" src="../../libs/bootstrap/js/ladda.min.js"></script>

    <script type="text/javascript" src="../../libs/bootstrap/js/bootstrap-switch.js"></script>



    <!-- Bootstrap core CSS -->

    <!-- <link rel="stylesheet" type="text/css" href="../../libs/bootstrap/css/bootstrap.css"> -->

    <link rel="stylesheet" type="text/css" href="../../libs/bootstrap/css/bootstrap-select.css">

    <link rel="stylesheet" type="text/css" href="../../libs/bootstrap/css/bootstrapValidator.min.css">

    <link rel="stylesheet" type="text/css" href="../../libs/bootstrap/css/bootstrap-datetimepicker.min.css">

    <link rel="stylesheet" type="text/css" href="../../libs/bootstrap/css/bootstrap-switch.css">

    <!--<link rel="stylesheet" type="text/css" href="../../libs/bootstrap/css/ladda-theme.min.css">-->

    <link rel="stylesheet" type="text/css" href="../../libs/DataTables/media/css/jquery.dataTables.css">

    <link rel="stylesheet" type="text/css" href="../../libs/DataTables/extensions/integration/bootstrap/3/dataTables.bootstrap.css">

    <link rel="stylesheet" type="text/css" href="../../libs/DataTables/extensions/Responsive/css/dataTables.responsive.css">

    <link rel="stylesheet" type="text/css" href="../../libs/DataTables/extensions/TableTools/css/dataTables.tableTools.css">

    <link rel="stylesheet" type="text/css" href="../../libs/calendar_master/css/calendar.css">

    <link rel="stylesheet" type="text/css" href="../../libs/css/table_responsive.css">



    <script type="text/javascript" src="../../libs/bootstrap/js/bootstrap.js"></script>

    <script type="text/javascript" src="../../libs/bootstrap/js/bootstrap-select.js"></script>

    <script type="text/javascript" src="../../libs/bootstrap/js/bootstrap3-typeahead.js"></script>

    <script type="text/javascript" src="../../libs/bootstrap/js/bootstrapValidator.min.js"></script>

    <script type="text/javascript" src="../../libs/bootstrap/js/bootstrap-datetimepicker.js"></script>

    <script type="text/javascript" src="../../libs/bootstrap/js/bootstrap-datetimepicker.es.js"></script>

    <script type="text/javascript" src="../../libs/DataTables/media/js/jquery.dataTables.min.js"></script>

    <script type="text/javascript" src="../../libs/DataTables/extensions/integration/bootstrap/3/dataTables.bootstrap.js"></script>

    <script type="text/javascript" src="../../libs/DataTables/extensions/Responsive/js/dataTables.responsive.js"></script>

    <script type="text/javascript" src="../../libs/DataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>

    <!-- <script type="text/javascript" src="../../libs/DataTables/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script> -->

    <script type="text/javascript" src="../../libs/DataTables/extensions/FixedHeader/js/dataTables.fixedHeader.js"></script>

    <!-- Font Awesome -->

    <!-- <link rel="stylesheet" type="text/css" href="../../libs/font-awesome/css/font-awesome.min.css"> -->

    <!-- <link rel="stylesheet" type="text/css" href="../../libs/DataTables/extensions/integration/font-awesome/dataTables.fontAwesome.css"> -->

    <!-- HighCharts -->

    <script src="https://code.highcharts.com/highcharts.js"></script>

    <script src="https://code.highcharts.com/highcharts-more.js"></script>

    <script type="text/javascript" src="../../libs/Highcharts/js/adapters/standalone-framework.js"></script>

    <script type="text/javascript" src="../../libs/Highcharts/js/modules/exporting.js"></script>

    <script type="text/javascript" src="../../libs/Highcharts/js/modules/heatmap.js"></script>

    <!-- Librerias propias -->

    <script type="text/javascript" src="../../libs/funciones/func_generales.js"></script>

    <script type="text/javascript" src="../../libs/funciones/func_usuarios.js"></script>

    <script type="text/javascript" src="../../libs/funciones/func_geografias.js"></script>

    <script type="text/javascript" src="../../libs/funciones/func_plantaciones.js"></script>

    <script type="text/javascript" src="../../libs/funciones/func_configuracion.js"></script>

    <script type="text/javascript" src="../../libs/funciones/func_inventario.js"></script>

    <script type="text/javascript" src="../../libs/funciones/func_control_calidad.js"></script>

    <!-- Calendar -->

    <!-- <script src="../../libs/calendar_master/components/underscore/underscore-min.js"></script>

    <script src="../../libs/calendar_master/js/calendar.js"></script>

    <script src="../../libs/calendar_master/js/language/es-MX.js"></script>

    <link rel="stylesheet" type="text/css" href="../../libs/calendar_master/css/calendar.css"> -->





    <link rel="stylesheet" type="text/css" href="../../libs/css/form.css">

    <link rel="stylesheet" type="text/css" href="../../libs/css/modal_styles.css">

    <link rel="stylesheet" type="text/css" href="../../libs/css/general.css">

    <link rel="stylesheet" type="text/css" href="../../libs/css/planificacion.css">

    <link rel="stylesheet" type="text/css" href="../../libs/css/styles.css">

</head>

<style type="text/css">

    .table

    {

        font-size: 14px;

    }

    /* Loader */

    .loader {

        border: 16px solid #f3f3f3; /* Light grey */

        border-top: 16px solid #3498db; /* Blue */

        border-radius: 50%;

        width: 100px;

        height: 100px;

        animation: spin 1s linear infinite;

        margin: auto;

    }



    @keyframes spin {

        0% { transform: rotate(0deg); }

        100% { transform: rotate(360deg); }

    }



    .row{

        padding: 0 10px;

    }

    .no-margin{

        margin: 0px;

    }

    .row-timeline{

        margin-bottom: 15px;

        margin-right: 0px;

        /*padding-left: 15px;*/

        margin-left: 0;

    }

    .bubble-timeline{

        display: inline-block;

        background-color: rgb(77, 178, 208);

        border-radius: 50%;

        line-height: 2em;

        max-width: 100px;

        /*border: 2px solid rgb(255, 255, 255);*/

        padding: 2px;

        margin-bottom: 15px;

        margin-top: 0;

    }

    .bubble-timeline-0{

        display: inline-block;

        background-color: rgb(77, 178, 208);

        border-radius: 50%;

        line-height: 2em;

        min-width: 25px;

        /*border: 2px solid rgb(255, 255, 255);*/

        padding: 2px;

        margin-bottom: 15px;

    }

    .bubble-timeline-1{

        display: inline-block;

        background-color: rgb(90, 208, 77);

        border-radius: 50%;

        line-height: 2em;

        min-width: 25px;

        /*border: 2px solid rgb(255, 255, 255);*/

        padding: 2px;

        margin-bottom: 15px;

    }

    .sin-margen-abajo

    {

        margin-bottom: -20px;

        padding-top: 5px;

    }

    .icon-timeline-container{

        background-color: rgb(77, 178, 208);

        border: 3px solid rgb(255, 255, 255);

        border-radius: 50%;

        display: inline-block;

    }

    .icon-timeline{

        padding: 10px;

        font-size: 1.5em;

        line-height: 1em;

        color: rgb(255, 255, 255);

    }

    .container-post-timeline{

        border: 1px solid rgb(45, 131, 156);

        border-radius: 6px;

        padding: 10px;

        min-height: 65px;

    }

    .container-post-timeline:after, .container-post-timeline:before {

        right: 100%;

        top: 25px;

        border: solid transparent;

        content: " ";

        height: 0;

        width: 0;

        position: absolute;

        pointer-events: none;

    }



    .container-post-timeline:after {

        border-color: rgba(185, 185, 185, 0);

        border-right-color: rgb(255,255,255);

        border-width: 10px;

        margin-top: -10px;

    }

    .container-post-timeline:before {

        border-color: rgba(176, 211, 85, 0);

        border-right-color: rgb(45, 131, 156);

        border-width: 11px;

        margin-top: -11px;

    }

    @media (max-width: 768px){

        .text-center-for-sall-only{

            text-align: center;

        }

        .extra-data-timeline{

            margin-top: 5px;

        }

    }

    .texto-timeline

    {

        font-weight: bold;

    }

    .texto-justificado

    {

        text-align: justify;

    }

    .fecha-timeline{

        font-size: 12px;

        color: rgb(80, 80, 80);

    }

    .text-autor-timeline{

        font-size: 12px;

    }

    .autor-timeline{

        color: rgb(30, 150, 185);

    }

    .nivel{

        border-radius: 4px;

        border-right: 3px solid rgb(120,120,120);

    }



    .nivel-inverso{

        border-radius: 4px;

        border-left: 3px solid rgb(120,120,120);

    }

    .autor-timeline-1

    {

        border: 1px solid rgb(161,160,159);

        background-color: rgb(161,160,159);

        border-radius: 5px;

        color: white;

        font-weight: bold;

        padding: 2px;

    }

    .autor-timeline-2

    {

        border: 1px solid rgb(79,185,216);

        background-color: rgb(79,185,216);

        border-radius: 5px;

        color: white;

        font-weight: bold;

        padding: 2px;

    }

    .autor-timeline-3

    {

        border: 1px solid rgb(204, 165, 27);

        background-color: rgb(204, 165, 27);

        border-radius: 5px;

        color: white;

        font-weight: bold;

        padding: 2px;

    }

    .autor-timeline-4

    {

        border: 1px solid rgb(170,50,105);

        background-color: rgb(170,50,105);

        border-radius: 5px;

        color: white;

        font-weight: bold;

        padding: 2px;

    }

    .autor-timeline-5

    {

        border: 1px solid rgb(51, 122, 183);

        background-color: rgb(51, 122, 183);

        border-radius: 5px;

        color: white;

        font-weight: bold;

        padding: 2px;

    }

    .autor-timeline-6

    {

        border: 1px solid rgb(134,184,114);

        background-color: rgb(134,184,114);

        border-radius: 5px;

        color: white;

        font-weight: bold;

        padding: 2px;

    }

    .nivel-1{

        border-right-color: rgb(161,160,159);

    }

    .nivel-2{

        border-right-color: rgb(79,185,216);

    }

    .nivel-3{

        border-right-color: rgb(204, 165, 27);

    }

    .nivel-4{

        border-right-color: rgb(170,50,105);

    }

    .nivel-5{

        border-right-color: rgb(51, 122, 183);

    }

    .nivel-6{

        border-right-color: rgb(134,184,114);

    }



    .nivel-1-inverso{

        border-left-color: rgb(161,160,159);

    }

    .nivel-2-inverso{

        border-left-color: rgb(79,185,216);

    }

    .nivel-3-inverso{

        border-left-color: rgb(204, 165, 27);

    }

    .nivel-4-inverso{

        border-left-color: rgb(170,50,105);

    }

    .nivel-5-inverso{

        border-left-color: rgb(51, 122, 183);

    }

    .nivel-6-inverso{

        border-left-color: rgb(134,184,114);

    }

    .label-nombre-1

    {

        background-color: rgb(161,160,159)!important;

        border: rgb(161,160,159)!important;

    }

    .label-nombre-2

    {

        background-color: rgb(79,185,216)!important;

        border: rgbrgb(79,185,216)!important;

    }

    .label-nombre-3

    {

        background-color: rgb(204, 165, 27)!important;

        border: rgb(204, 165, 27)!important;

    }

    .label-nombre-4

    {

        background-color: rgb(170,50,105)!important;

        border: rgb(170,50,105)!important;

    }

    .label-nombre-5

    {

        background-color: rgb(51, 122, 183)!important;

        border: rgb(51, 122, 183)!important;

    }

    .label-nombre-6

    {

        background-color: rgb(134,184,114)!important;

        border: rgb(134,184,114)!important;

    }

    .img-timeline{

        width: 100%;

        /*background-color: rgb(77, 178, 208);

        border: 3px solid rgb(255, 255, 255);*/

        border-radius: 50%;

        display: inline-block;

        height: 50px;

    }

    .img-timeline-2{

        width: 25px;

        /*background-color: rgb(77, 178, 208);

        border: 3px solid rgb(255, 255, 255);*/

        border-radius: 50%;

        display: inline-block;

    }

    .label-estado

    {

        border-radius: 0.25em !important;

    }

    .label-estado-1

    {

        background-color: #F9F19A;

        color: black;

    }

    .label-estado-2

    {

        background-color: #EDCCE3;

        color: black;

    }

    .label-estado-3

    {

        background-color: #FBCB9A;

        color: black;

    }

    .label-estado-4

    {

        background-color: #A6D4BA;

        color: black;

    }

    .label-estado-5

    {

        background-color: #F4EDCA;

        color: black;

    }

    .label-estado-6

    {

        background-color: #dff0d8;

        color: black;

    }

    .label-01

    {

        background-color: #5cb85c;

        margin-left: 5px;

    }

    .label-02

    {

        background-color: #337ab7;

        margin-left: 5px;

    }

    .label-nivel-1{

        background-color: rgb(161,160,159);

    }

    .label-nivel-2{

        background-color: rgb(79,185,216);

    }

    .label-nivel-3{

        background-color: rgb(204, 165, 27);

    }

    .label-nivel-4{

       background-color: rgb(170,50,105);

    }

    .label-nivel-5{

       background-color: rgb(51, 122, 183);

    }

    .label-nivel-6{

       background-color: rgb(134,184,114);

    }

    .label.label-info

    {

        font-size: 11px;

        display: inline;

        border-radius: 0;

        margin:2px;

    }



    .row-label

    {

        margin-bottom: 5px;

    }



    .container-post-timeline-inverso{

        border: 1px solid rgb(45, 131, 156);

        border-radius: 6px;

        padding: 10px;

        min-height: 65px;

    }

    .container-post-timeline-inverso:after, .container-post-timeline-inverso:before {

        left: 100%;

        top: 25px;

        border: solid transparent;

        content: " ";

        height: 0;

        width: 0;

        position: absolute;

        pointer-events: none;

    }



    .container-post-timeline-inverso:after {

        border-color: rgba(185, 185, 185, 0);

        border-left-color: rgb(255,255,255);

        border-width: 10px;

        margin-top: -10px;

    }

    .container-post-timeline-inverso:before {

        border-color: rgba(176, 211, 85, 0);

        border-left-color: rgb(45, 131, 156);

        border-width: 11px;

        margin-top: -11px;

    }



    @media (max-width: 768px){

        .label-estado

        {

            border-radius: 0px !important;

        }

        .img-timeline {

            width: 75px;

        }

        .container-post-timeline-inverso:after, .container-post-timeline-inverso:before{

            left: 50%;

            bottom: 100%;

            top: -10px !important;

            border: solid transparent;

            content: " ";

            height: 0;

            width: 0;

            position: absolute;

            pointer-events: none;

        }

        .container-post-timeline-inverso:after {

            border-color: rgba(185, 185, 185, 0);

            border-bottom-color: rgb(255,255,255);

            border-width: 10px;

            margin-left: -10px;

            top: -10px !important;

        }

        .container-post-timeline-inverso:before {

            border-color: rgba(176, 211, 85, 0);

            border-bottom-color: rgb(45, 131, 156);

            border-width: 11px;

            margin-left: -11px;

            top: -11px !important;

        }

        .container-post-timeline:after, .container-post-timeline:before{

            left: 50%;

            bottom: 100%;

            top: -10px !important;

            border: solid transparent;

            content: " ";

            height: 0;

            width: 0;

            position: absolute;

            pointer-events: none;

        }

        .container-post-timeline:after {

            border-color: rgba(185, 185, 185, 0);

            border-bottom-color: rgb(255,255,255);

            border-width: 10px;

            margin-left: -10px;

            top: -10px !important;

        }

        .container-post-timeline:before {

            border-color: rgba(176, 211, 85, 0);

            border-bottom-color: rgb(45, 131, 156);

            border-width: 11px;

            margin-left: -11px;

            top: -11px !important;

        }

        .col-xs-4.col-sm-2.col-md-2.text-center {

            float: right;

            padding-left: 25%;

            padding-right: 25%;

            text-align: center;

            width: 100%;

            -webkit-box-ordinal-group: 2;

            -moz-box-ordinal-group: 2;

            box-ordinal-group: 2;

        }

        .icon-timeline-container{

            width: 100%;

            background-color: rgb(77, 178, 208);

            border: 3px solid rgb(255, 255, 255);

            border-radius: 50%;

            display: inline-block

        }

        .icon-timeline-container-2{

            background-color: rgb(77, 178, 208);

            border: 3px solid rgb(255, 255, 255);

            border-radius: 50%;

            display: inline-block

        }

        .col-xs-8.col-sm-10.col-md-10.text-left.container-post-timeline-inverso

        {

            width: 100% !important;

            -webkit-box-ordinal-group: 3;

            -moz-box-ordinal-group: 3;

            box-ordinal-group: 3;

        }

        .col-xs-8.col-sm-10.col-md-10.text-left.container-post-timeline

        {

            width: 100% !important;

            -webkit-box-ordinal-group: 3;

            -moz-box-ordinal-group: 3;

            box-ordinal-group: 3;

        }

        .row-timeline

        {

            width: 100%;

            display: -webkit-box;

            display: -moz-box;

            display: box;



            -webkit-box-orient: vertical;

            -moz-box-orient: vertical;

            box-orient: vertical;

        }

        .label.label-info

        {

            display: block;

        }

        .label-bordes-abajo

        {

            border-radius: 0 0 5px 5px !important;

        }

        .label-bordes-arriba

        {

            border-radius: 5px 5px 0 0 !important;

        }

        .textarea_observacion

        {

            resize: none;

        }

        .texto-timeline

        {

            /*background: rgb(240, 240, 240) none repeat scroll 0 0;

            border: 1px solid rgb(210, 210, 210);

            border-radius: 3px;

            padding: 5px 10px;

            display: inline-block;*/

        }







    }

</style>

<script type="text/javascript">

    jQuery.ajaxSetup({async:false});

    grl_overlay_loading('');

    var array_file_list = [];

    array_file_list.push({id:0,file:'',file_ext:''});

    var $container_upload_box = $("#container_upload_box");

    var files_form = '';

    var nombre_file = '';

    var ext_adjunto = '';

    codigo_estado_plantacion = 0;

    codigo_plantacion = 0;

    codigo_detalle = 0;

    var acres_plantados = 0;

    var flag_exploracion = 0;

    var cod_exploracion = 0;

    var num_fila = 1;

    var nombre_quimico = '';

    var fecha_hoy   = new Date(<?php echo time() * 1000; ?>);

    var hoy         = (fecha_hoy.getMonth()+1) + "/" + fecha_hoy.getDate() + "/" + fecha_hoy.getFullYear();

    var hoy2         = fecha_hoy.getFullYear() + "/" +(fecha_hoy.getMonth()+1) + "/" + fecha_hoy.getDate() ;

    //var hoy_18_anios_atras        = (fecha_hoy.getMonth()+1) + "/" + fecha_hoy.getDate() + "/" + (fecha_hoy.getFullYear()-18);

    var hoy_default = fecha_hoy.getFullYear() + "-" + (fecha_hoy.getMonth()+1) + "-" + fecha_hoy.getDate();

    var estados = [ 'Pre-Planting',

                    'Pre-Planting',

                    'Planted',

                    'Trimmed',

                    'Growing',

                    'Ready to harvest',

                    'Harvested',

                    'QA',

                    'Packaged product',

                    'Removed',

                    'Finished'];

    var array_bloques_plantaciones = [];

    $(document).ready(function() {

        jQuery.ajaxSetup({ async: false });

        //Inicializar la barra de botones

        init_button_bar();

        //Constructores

        conf_constructor_listado_granjas();

        inv_constructor_listado_unidades_medida();

        inv_constructor_listado_tipos_quimicos();

        plan_constructor_listado_plantaciones('cod_plantacion');

        $('.selectpicker').selectpicker({

            dropupAuto: 'true',

            container: 'body',

            size: '5',

            width: '100%',

            style: 'btn-sm btn-info',

            tickIcon: 'fa fa-check'

        });

        //Habilita los selects para mobile

        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {

            $('.selectpicker').selectpicker('mobile');

        }

        $('.selectpicker').selectpicker('refresh');

        $('.letras').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z-¡!¿?,.() 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/, optional: false}}});

        $('.letras10').mask('SSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z 0-9]/, optional: false}}});

        $('.numeros4').mask('9999');

        $('.numero_carga').mask('99999999');

        $('.monto').mask("#,##0.00", {reverse: true, maxlength: false});

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

            format: 'YYYY/MM/DD',

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

            //defaultDate: '12:00',

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



        $('#cod_info_empresa').change(function(event) {

            /* Act on the event */

            conf_constructor_listado_temporadas($(this).val());

            bw_constructor_zonas($(this).val());

        });

        var cod_plantacion_seleccionada = 0;



        $('#cod_plantacion').change(function(event) {

            //bw_constructor_bloques_plantacion_todos('cod_bloques_plantacion2', $(this).val());

            /*jQuery("#cod_bloques_plantacion2 > option").each(function() {

                if(jQuery.inArray(jQuery(this).data('cod_plantacion'), $('#cod_plantacion option:selected').val() )===1){

                    //console.log('item: ' + jQuery(this).val());

                    jQuery(this).attr('disabled', 'disabled');

                }

            });

            $('#cod_bloques_plantacion2').selectpicker('refresh');*/

            cod_plantacion_seleccionada = $('#cod_plantacion').val();

            $('#cod_bloques_plantacion2').selectpicker('deselectAll');

            $('#cod_bloques_plantacion2 option').each(function(index, el) {

                if ($(this).data('cod_plantacion') != cod_plantacion_seleccionada) 

                {

                    $(this).attr('disabled', 'disabled');

                }

                else

                {

                    $(this).removeAttr('disabled');

                }

            });

            $('#cod_bloques_plantacion2').selectpicker('render');

            $('#cod_bloques_plantacion2').selectpicker('refresh');

        });



        /*$('#cod_bloques_plantacion2').change(function(event) {

            if($('#cod_bloques_plantacion2 option:selected').size() == 1)

            {

                cod_plantacion_seleccionada = $('#cod_bloques_plantacion2 option:selected').data('cod_plantacion');

            }

            if($('#cod_bloques_plantacion2 option:selected').size() > 0)

            {

                $('#cod_bloques_plantacion2 option').each(function(index, el) {

                    if ($(this).data('cod_plantacion') != cod_plantacion_seleccionada) 

                    {

                        $(this).attr('disabled', 'disabled');

                    }

                });

            }

            else

            {

                $("#cod_bloques_plantacion2 option").removeAttr('disabled');

            }

            $('#cod_bloques_plantacion2').selectpicker('refresh');

        });*/



        $('#cod_inventario_quimico').change(function(event) {

            /* Act on the event */

            jQuery.ajaxSetup({async:false});

            inv_constructor_listado_unidades_medida_relacionadas($('#cod_inventario_quimico option:selected').data('cod_unidad_medida'));

            acres_plantados = 0;

            /*if(codigo_detalle == 0)

            {*/

                $('#cod_bloques_aplicar_quimico option:selected').each(function() {

                    acres_plantados += Number($(this).data('cantidad_acres'));

                });

                //acres_plantados = parseFloat($('#acres_plantados').val());

                var dosis_minima = parseFloat($('#cod_inventario_quimico option:selected').data('dosis_minima'));

                var dosis_maxima = parseFloat($('#cod_inventario_quimico option:selected').data('dosis_maxima'));

                var cantidad_sugerida = parseFloat(acres_plantados*((dosis_maxima+dosis_minima)/2));

                //console.log('acres_plantados: ' + acres_plantados);

                //console.log('dosis_minima: ' + dosis_minima);

                //console.log('dosis_maxima: ' + dosis_maxima);

                //console.log('cantidad_sugerida: ' + cantidad_sugerida);

                $('#cantidad_sugerida').val(cantidad_sugerida);

            /*}

            else

            {

                var dosis_minima = parseFloat($('#cod_inventario_quimico option:selected').data('dosis_minima'));

                var dosis_maxima = parseFloat($('#cod_inventario_quimico option:selected').data('dosis_maxima'));

                var cantidad_sugerida = parseFloat(acres_plantados*((dosis_maxima+dosis_minima)/2));

                //console.log('acres_plantados: ' + acres_plantados);

                //console.log('dosis_minima: ' + dosis_minima);

                //console.log('dosis_maxima: ' + dosis_maxima);

                //console.log('cantidad_sugerida: ' + cantidad_sugerida);

                $('#cantidad_sugerida').val(cantidad_sugerida);

            }*/

            $('#cod_unidad_medida').selectpicker('val',$('#cod_inventario_quimico option:selected').data('cod_unidad_medida'));

            $('.selectpicker').selectpicker('refresh');

            jQuery.ajaxSetup({async:true});

        });

        <?php

        if (count($PLANTACION)) {

        ?>

            $('#anio_plantacion').val("<?php echo utf8_encode($PLANTACION[0]['anio_plantacion']); ?>");

            $('#num_plantacion').val("<?php echo utf8_encode($PLANTACION[0]['num_plantacion']); ?>");

            $('#fecha_plantacion_planeada').val("<?php echo utf8_encode($PLANTACION[0]['fecha_plantacion_planeada']); ?>");

            $('#acres_plantados').val("<?php echo utf8_encode($PLANTACION[0]['acres_plantados']); ?>");

            $('#cod_info_empresa').selectpicker('val',"<?php echo utf8_encode($PLANTACION[0]['cod_info_empresa']); ?>");

            $('#cod_temporada').selectpicker('val',"<?php echo utf8_encode($PLANTACION[0]['cod_temporada']); ?>");

            $('.selectpicker').selectpicker('refresh');

            codigo_estado_plantacion = <?php echo $PLANTACION[0]['cod_estado']; ?>;

            codigo_plantacion = <?php echo $PLANTACION[0]['cod_plantacion']; ?>;

            $('#badge_estado').removeClass();

            $('#badge_estado').addClass('badge estado' + codigo_estado_plantacion);

            $('#badge_estado2').removeClass();

            $('#badge_estado2').addClass('badge estado' + codigo_estado_plantacion);

            $('#badge_estado').text("<?php echo utf8_encode($PLANTACION[0]['estado_plantacion']); ?>");

            $('#badge_estado2').text(estados[codigo_estado_plantacion]);

            $('#cod_plantacion').trigger('change');

            //inv_constructor_listado_quimicos('cod_inventario_quimico',$('#cod_info_empresa').val());

            inv_constructor_listado_maquinarias('cod_maquinaria',$('#cod_info_empresa').val());

            <?php

            switch ($_SESSION['cod_cargo']) {

                case 1: // Administrador

                    ?>

                    $('.btn_operador,.btn_explorador,.btn_supervisor').addClass('hide');

                    $('.btn_administrador').removeClass('hide');

                    <?php

                    break;

                case 2: // Operador

                    ?>

                    $('.btn_operador,.btn_explorador,.btn_supervisor').addClass('hide');

                    $('.btn_operador').removeClass('hide');

                    <?php

                    break;

                case 3: // Explorador

                    ?>

                    $('.btn_operador,.btn_explorador,.btn_supervisor').addClass('hide');

                    $('.btn_explorador').removeClass('hide');

                    <?php

                    break;

                case 4: // Supervisor

                    ?>

                    $('.btn_operador,.btn_explorador,.btn_supervisor').addClass('hide');

                    $('.btn_supervisor').removeClass('hide');

                    <?php

                    break;

                case 5: // Supervisor

                    ?>

                    $('.btn_operador,.btn_explorador,.btn_supervisor').addClass('hide');

                    $('.btn_supervisor').removeClass('hide');

                    <?php

                    break;

                case 6: // Miscellneous

                    ?>

                    $('.btn_operador,.btn_explorador,.btn_supervisor').addClass('hide');

                    //$('.btn_administrador').removeClass('hide');

                    <?php

                    break;



                default:

                    // code...

                    break;

            }

            

        }

        if(isset($_POST['nombre_panel']))

        {

            ?>

            $('#<?php echo $_POST['nombre_panel']; ?>').trigger('click');

            <?php

        }

        ?>

        //console.log('cod_cargo: ' + <?php echo $_SESSION['cod_cargo'];?>);

        

        

        bw_constructor_estados_plantaciones();

        //conf_cargar_formularios_por_estado('div_formularios', codigo_estado_plantacion);

        //conf_cargar_formularios_por_plantacion('div_formularios', codigo_plantacion);

        bw_constructor_bloques_plantacion('cod_bloques_plantacion', codigo_plantacion);

        bw_constructor_bloques_plantacion_todos('cod_bloques_aplicar_quimico', codigo_plantacion);

        bw_constructor_bloques_plantacion_todos('cod_bloques_cleaning', codigo_plantacion);

        bw_constructor_bloques_plantacion_todos('cod_bloques_harvesting_checklist', codigo_plantacion);

        bw_constructor_bloques_plantacion_todos('cod_bloques_harvesting_worksheet', codigo_plantacion);

        bw_constructor_bloques_plantacion_todos('cod_bloques_trasplante', codigo_plantacion);

        inv_constructor_listado_semillas('cod_inventario_semilla',$('#cod_info_empresa').val());

        //inv_constructor_listado_maquinarias_estado('cod_inventario_maquinaria',$('#cod_info_empresa').val(), codigo_estado_plantacion);

        inv_constructor_listado_maquinarias_tipo_aplicacion('cod_inventario_maquinaria',$('#cod_info_empresa').val(), 4);

        inv_constructor_listado_tipos_aplicacion('cod_tipo_aplicacion');

        /*$('#badge_estado').removeClass('');

        $('#badge_estado').addClass('badge estado' + codigo_estado_plantacion);

        $('#badge_estado').text(estados[codigo_estado_plantacion]);

        console.log('Estado Plantación: ' + codigo_estado_plantacion);*/

        $('.selectpicker').selectpicker({

            dropupAuto: 'true',

            container: 'body',

            size: '5',

            width: '100%',

            style: 'btn-sm btn-info',

            tickIcon: 'fa fa-check'

        });

        $('#cod_bloques_plantacion,#cod_bloques_plantacion2,#cod_bloque,#cod_bloques_aplicar_quimico,#cod_bloques_trasplante').selectpicker({

            dropupAuto: 'true',

            container: 'body',

            size: '10',

            width: '85%',

            style: 'btn-sm btn-info has-btn-all',

            tickIcon: 'fa fa-check'

        });

        $(".check-all").click(function(event){

            event.stopPropagation();

            id = $(this).parent().children('.selectpicker').attr('id');

            if ($(this).hasClass('allselected')) {

                $(this).removeClass('allselected btn-info').addClass('btn-danger');

                $('#'+id+' option').prop('selected', false);

                $('#'+id).selectpicker('setStyle', 'btn-danger');

                $('#'+id).selectpicker('setStyle', 'btn-info', 'remove');

            }

            else{

                $(this).addClass('allselected btn-info').removeClass('btn-danger');

                if (!$('#'+id+' option').attr('disabled'))

                {

                    $('#'+id+' option').prop('selected', true);

                }

                $('#'+id).selectpicker('setStyle', 'btn-danger', 'remove');

                $('#'+id).selectpicker('setStyle', 'btn-info');

            }

            $('#'+id).selectpicker('refresh');

        });

        //Habilita los selects para mobile

        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {

            $('.selectpicker').selectpicker('mobile');

        }

        $('.selectpicker').selectpicker('refresh');

        $('.date').datetimepicker({

            //disabledHours: true,

            locale: 'es',

            //minDate: hoy,

            //keepOpen: true,

            format: 'YYYY/MM/DD',

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

        if($('#tbody_semillas tr').length > 0)

        {

            $('#cod_info_empresa').attr('disabled', 'disabled');

        }

        console.log('url: ' + window.location.hostname);

        console.log('protocol: ' + window.location.protocol);



        $('#checkbox_translate').change(function(event) {

            /* Act on the event */

            event.preventDefault();

            event.stopPropagation();

            ($('#checkbox_translate').attr('checked') ? $('#checkbox_translate').removeAttr('checked') : $('#checkbox_translate').attr('checked','checked'))

            grl_traducir_interfaz(($('#checkbox_translate').attr('checked') ? 1 : 0));

        });

        grl_traducir_interfaz(($('#checkbox_translate').attr('checked') ? 1 : 0));





        /*FUNCIÓN PARA CREAR SELECTPICKER CON TODOS LOS BLOQUES AGRUPADOS POR SU PLANTACIÓN*/



        <?PHP

        if (count($BLOQUES_TODOS)) 

        {

            $codigo_plantacion = $BLOQUES_TODOS[0]['cod_plantacion'];

            ?>

            $('#cod_bloques_plantacion2').empty().append('<optgroup data-cod_plantacion="<?php echo $BLOQUES_TODOS[0]['cod_plantacion']; ?>" label="<?php echo utf8_encode($BLOQUES_TODOS[0]['nombre_empresa'].'-'.$BLOQUES_TODOS[0]['anio_plantacion'].'-'.$BLOQUES_TODOS[0]['num_plantacion'].'-'.$BLOQUES_TODOS[0]['codigo_temporada']); ?>" >');

            <?php

            foreach ($BLOQUES_TODOS as $bloque) {

                if ($bloque['cod_plantacion'] == $codigo_plantacion) 

                {

                    ?>

                    $('#cod_bloques_plantacion2').append('<option value="<?php echo $bloque['cod_bloque']; ?>" data-cod_plantacion="<?php echo $bloque['cod_plantacion']; ?>" ><?php echo utf8_encode($bloque['nombre_empresa'].'-'.$bloque['anio_plantacion'].'-'.$bloque['num_plantacion'].'-'.$bloque['codigo_temporada'].' - '.$bloque['zona'].'-'.($bloque['clave_bloque'] == NULL || $bloque['clave_bloque'] == '' ? '':$bloque['clave_bloque'].'-').$bloque['nombre_bloque']); ?></option>');

                    <?php

                }

                else

                {

                    ?>

                    $('#cod_bloques_plantacion2').append('</optgroup><optgroup label="<?php echo utf8_encode($bloque['nombre_empresa'].'-'.$bloque['anio_plantacion'].'-'.$bloque['num_plantacion'].'-'.$bloque['codigo_temporada']); ?>" >');

                    <?php

                    $codigo_plantacion = $bloque['cod_plantacion'];

                }

            }

            ?>

            $('#cod_bloques_plantacion2').append('</optgroup>');

            $('#cod_bloques_plantacion2').selectpicker('refresh');

            <?php

        }

        

        ?>



        /*FUNCIONES PARA ALMACENAR EN MODO OFFLINE*/





        $('#btn_guardar_cleaning').click(function(event) {

            /* Act on the event */

            var error = 0;

            $(".input.requerido_limpieza").map(function(){

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

            $(".selectpicker.requerido_limpieza").map(function(){

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

                $('#modal_cleaning_sanitizing').modal('hide');

                $('#tbody_cleaning_offline').append('<tr id="fila_'+num_fila+'">'+

                    '<td class="">' + codigo_detalle + '</td>'+

                    '<td class="hide">' + codigo_plantacion + '</td>'+

                    '<td class="hide">' + $('#fecha_limpieza').val() + '</td>'+

                    '<td class="hide">' + $('#vez_limpieza').val() + '</td>'+

                    '<td class="hide">' + $('#equipo_limpieza').val() + '</td>'+

                    '<td class="hide">' + $('#cleaning_tools option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#cleaning_potable_water option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#cleaning_detergent option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#scrubbing option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#rinse_potable_water option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#sanitizing_chlorine option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#post_sanitizing option:selected').val() + '</td>'+

                    '<td>' + $('#fecha_limpieza').val() + '</td>'+

                    '<td>' + $('#vez_limpieza').val() + '</td>'+

                    '<td>' + $('#equipo_limpieza').val() + '</td>'+

                    '<td>' + $('#cleaning_tools option:selected').text() + '</td>'+

                    '<td>' + $('#cleaning_potable_water option:selected').text() + '</td>'+

                    '<td>' + $('#cleaning_detergent option:selected').text() + '</td>'+

                    '<td>' + $('#scrubbing option:selected').text() + '</td>'+

                    '<td>' + $('#rinse_potable_water option:selected').text() + '</td>'+

                    '<td>' + $('#sanitizing_chlorine option:selected').text() + '</td>'+

                    '<td>' + $('#post_sanitizing option:selected').text() + '</td>'+

                    '<td><a title="Delete - Eliminar" onclick="$(\'#fila_'+num_fila+'\').remove();" class="smooth-transition btn btn-sm btn_delete_file btn-danger">Delete</a></td>'+

                    '</tr>');

                num_fila++;

                $(".input.requerido_limpieza").val('');

            }

            else

            {

                grl_mensaje('You must fill the empty fields','Debe llenar los campos vacíos','warning');

            }

        });



        $('#btn_guardar_exploracion_plantacion').click(function(event) {

            /* Act on the event */

            var error = 0;

            $(".input.requerido_explorar_plantacion").map(function(){

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

            $(".selectpicker.requerido_explorar_plantacion").map(function(){

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

                $('#modal_explorar_plantacion').modal('hide');

                $('#tbody_exploraciones_offline').append('<tr id="fila_'+num_fila+'">'+

                    '<td class="">' + codigo_detalle + '</td>'+

                    '<td class="hide">' + codigo_plantacion + '</td>'+

                    '<td class="hide">' + $('#etapa_crecimiento option:selected').val() + '</td>'+

                    '<td>' + $('#etapa_crecimiento option:selected').text() + '</td>'+

                    '<td>' + $('#fecha_exploracion').val() + '</td>'+

                    '<td>' + $('#worms').val() + '</td>'+

                    '<td>' + $('#eggs').val() + '</td>'+

                    '<td>' + $('#leafhoppers').val() + '</td>'+

                    '<td>' + $('#aphids').val() + '</td>'+

                    '<td>' + $('#stink_bugs').val() + '</td>'+

                    '<td>' + $('#gnats').val() + '</td>'+

                    '<td>' + $('#flea_beetles').val() + '</td>'+

                    '<td>' + $('#cyclaman_mites').val() + '</td>'+

                    '<td>' + $('#cercospora_leaf_spot').val() + '</td>'+

                    '<td>' + $('#pythium').val() + '</td>'+

                    '<td>' + $('#rhizoctonia_aerial_blight').val() + '</td>'+

                    '<td>' + $('#bacteria').val() + '</td>'+

                    '<td>' + $('#sclerotinia').val() + '</td>'+

                    '<td>' + $('#alternaria_specks').val() + '</td>'+

                    '<td>' + $('#mildew').val() + '</td>'+

                    '<td>' + $('#virus').val() + '</td>'+

                    '<td>' + $('#dollarweed').val() + '</td>'+

                    '<td>' + $('#frogs_bit').val() + '</td>'+

                    '<td>' + $('#mud_plantain').val() + '</td>'+

                    '<td>' + $('#tube_weed').val() + '</td>'+

                    '<td>' + $('#grass').val() + '</td>'+

                    '<td>' + $('#damaged_leaves').val() + '</td>'+

                    '<td>' + $('#purple_stem').val() + '</td>'+

                    '<td>' + $('#watercress_rooter').val() + '</td>'+

                    '<td>' + $('#observaciones_exploracion').val() + '</td>'+

                    '<td><a title="Delete - Eliminar" onclick="$(\'#fila_'+num_fila+'\').remove();" class="smooth-transition btn btn-sm btn_delete_file btn-danger">Delete</a></td>'+

                    '</tr>');

                num_fila++;

                $(".input.requerido_explorar_plantacion").val('0');

                $('#fecha_exploracion').val(hoy2);

            }

            else

            {

                grl_mensaje('You must fill the empty fields','Debe llenar los campos vacíos','warning');

            }

        });



        $('#btn_guardar_harvesting_worksheet').click(function(event) {

            /* Act on the event */

            var error = 0;

            $(".input.requerido_harvesting_worksheet").map(function(){

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

            $(".selectpicker.requerido_harvesting_worksheet").map(function(){

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

                $('#modal_harvesting_worksheet').modal('hide');

                $('#tbody_harvesting_worksheet').append('<tr id="fila_'+num_fila+'">'+

                    '<td class="">' + codigo_detalle + '</td>'+

                    '<td class="hide">' + codigo_plantacion + '</td>'+

                    '<td>' + $('#harvest_date').val() + '</td>'+

                    '<td class="hide">' + $('#phi option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#cellos option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#increment_bunch_cello option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#area_finished option:selected').val() + '</td>'+

                    '<td>' + $('#phi option:selected').text() + '</td>'+

                    '<td>' + $('#cellos option:selected').text() + '</td>'+

                    '<td>' + $('#increment_bunch_cello option:selected').text() + '</td>'+

                    '<td>' + $('#area_finished option:selected').text() + '</td>'+

                    '<td>' + $('#acres_harvested').val() + '</td>'+

                    '<td>' + $('#orden_compra').val() + '</td>'+

                    '<td>' + $('#cantidad_cosechada_worksheet').val() + '</td>'+

                    '<td>' + $('#cantidad_empacada_worksheet').val() + '</td>'+

                    '<td>' + $('#commments_harvesting_worksheet').val() + '</td>'+

                    '<td><a title="Delete - Eliminar" onclick="$(\'#fila_'+num_fila+'\').remove();" class="smooth-transition btn btn-sm btn_delete_file btn-danger">Delete</a></td>'+

                    '</tr>');

                num_fila++;

                $(".input.requerido_harvesting_worksheet").val('');

            }

            else

            {

                grl_mensaje('You must fill the empty fields','Debe llenar los campos vacíos','warning');

            }

        });



        $('#btn_calibrar_bloque').click(function(event) {

            /* Act on the event */

            var error = 0;

            $(".input.requerido_calibrar").map(function(){

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

            $(".selectpicker.requerido_calibrar").map(function(){

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

                $('#modal_calibrar').modal('hide');

                $('#tbody_calibraciones').append('<tr id="fila_'+num_fila+'">'+

                    '<td class="">' + codigo_detalle + '</td>'+

                    '<td class="hide">' + codigo_plantacion + '</td>'+

                    '<td class="hide">' + $('#cod_fertilizante option:selected').val() + '</td>'+

                    '<td>' + $('#cod_fertilizante option:selected').text() + '</td>'+

                    '<td>' + $('#horas_aplicacion_calibrar').val() + '</td>'+

                    '<td>' + $('#fecha_calibracion').val() + '</td>'+

                    '<td>' + $('#observaciones_calibrar').val() + '</td>'+

                    '<td><a title="Delete - Eliminar" onclick="$(\'#fila_'+num_fila+'\').remove();" class="smooth-transition btn btn-sm btn_delete_file btn-danger">Delete</a></td>'+

                    '</tr>');

                num_fila++;

                $(".input.requerido_calibrar").val('');

            }

            else

            {

                grl_mensaje('You must fill the empty fields','Debe llenar los campos vacíos','warning');

            }

        });



        $('#btn_guardar_harvesting_checklist').click(function(event) {

            /* Act on the event */

            var error = 0;

            $(".input.requerido_harvesting_checklist").map(function(){

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

            $(".selectpicker.requerido_harvesting_checklist").map(function(){

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

                $('#modal_harvesting_checklist').modal('hide');

                $('#tbody_harvesting_checklist').append('<tr id="fila_'+num_fila+'">'+

                    '<td class="">' + codigo_detalle + '</td>'+

                    '<td class="hide">' + codigo_plantacion + '</td>'+

                    '<td class="hide">' + $('#fecha_checklist').val() + '</td>'+

                    '<td class="hide">' + $('#loose_bunches option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#conventional_organic option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#question1 option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#question2 option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#question3 option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#question4 option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#question5 option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#question6 option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#question7 option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#question8 option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#question9 option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#question10 option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#question11 option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#question12 option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#question13 option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#question14 option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#question15 option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#question16 option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#question17 option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#question18').val() + '</td>'+

                    '<td class="hide">' + $('#question19').val() + '</td>'+

                    '<td class="hide">' + $('#question20 option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#question21 option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#question22 option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#actions').val() + '</td>'+

                    '<td>' + $('#fecha_checklist').val() + '</td>'+

                    '<td>' + $('#loose_bunches option:selected').text() + '</td>'+

                    '<td>' + $('#conventional_organic option:selected').text() + '</td>'+

                    '<td>' + $('#question1 option:selected').text() + '</td>'+

                    '<td>' + $('#question2 option:selected').text() + '</td>'+

                    '<td>' + $('#question3 option:selected').text() + '</td>'+

                    '<td>' + $('#question4 option:selected').text() + '</td>'+

                    '<td>' + $('#question5 option:selected').text() + '</td>'+

                    '<td>' + $('#question6 option:selected').text() + '</td>'+

                    '<td>' + $('#question7 option:selected').text() + '</td>'+

                    '<td>' + $('#question8 option:selected').text() + '</td>'+

                    '<td>' + $('#question9 option:selected').text() + '</td>'+

                    '<td>' + $('#question10 option:selected').text() + '</td>'+

                    '<td>' + $('#question11 option:selected').text() + '</td>'+

                    '<td>' + $('#question12 option:selected').text() + '</td>'+

                    '<td>' + $('#question13 option:selected').text() + '</td>'+

                    '<td>' + $('#question14 option:selected').text() + '</td>'+

                    '<td>' + $('#question15 option:selected').text() + '</td>'+

                    '<td>' + $('#question16 option:selected').text() + '</td>'+

                    '<td>' + $('#question17 option:selected').text() + '</td>'+

                    '<td>' + $('#question18').val() + '</td>'+

                    '<td>' + $('#question19').val() + '</td>'+

                    '<td>' + $('#question20 option:selected').text() + '</td>'+

                    '<td>' + $('#question21 option:selected').text() + '</td>'+

                    '<td>' + $('#question22 option:selected').text() + '</td>'+

                    '<td>' + $('#actions').val() + '</td>'+

                    '<td><a title="Delete - Eliminar" onclick="$(\'#fila_'+num_fila+'\').remove();" class="smooth-transition btn btn-sm btn_delete_file btn-danger">Delete</a></td>'+

                    '</tr>');

                num_fila++;cccccccccc

            }

            else

            {

                grl_mensaje('You must fill the empty fields','Debe llenar los campos vacíos','warning');

            }

        });



        $('#btn_agregar_cantidad_aplicada').click(function(event) {

            /* Act on the event */

            console.log('codigo_detalle: ' + codigo_detalle);

            //plan_ingresar_cantidad_aplicada_quimico(codigo_detalle,$('#cantidad_aplicada').val());

            $('#div_cantidad_aplicada').addClass('hide');

            $('#tbody_cantidades_aplicadas').append('<tr id="fila_'+num_fila+'">'+

                    '<td class="">' + codigo_detalle + '</td>'+

                    '<td>' + nombre_quimico + '</td>'+

                    '<td>' + $('#cantidad_aplicada').val() + '</td>'+

                    '<td><a title="Delete - Eliminar" onclick="$(\'#fila_'+num_fila+'\').remove();" class="smooth-transition btn btn-sm btn_delete_file btn-danger">Delete</a></td>'+

                    '</tr>');

                num_fila++;

                $("#cantidad_aplicada").val('');

        });





        $('#btn_guardar_aplicacion').click(function(event) {

            /* Act on the event */

            var error = 0;

            $(".input.requerido_aplicacion_operador").map(function(){

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

            $(".selectpicker.requerido_aplicacion_operador").map(function(){

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

                $('#modal_aplicar_quimico').modal('hide');

                $('#tbody_aplicaciones_qumicas_operador').append('<tr id="fila_'+num_fila+'">'+

                    '<td class="hide">' + cod_aplicacion_quimico + '</td>'+

                    '<td class="hide">' + codigo_plantacion + '</td>'+

                    '<td class="hide">' + $('#cod_tipo_aplicacion option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#cod_maquinaria option:selected').val() + '</td>'+

                    '<td class="hide">' + $('#fecha_aplicacion_operador').val() + '</td>'+

                    '<td class="hide">' + $('#hora_inicial').val() + '</td>'+

                    '<td class="hide">' + $('#hora_final').val() + '</td>'+

                    '<td class="hide">' + $('#viento').val() + '</td>'+

                    '<td class="hide">' + $('#temperatura').val() + '</td>'+

                    '<td class="hide">' + $('#descripcion_aplicar_quimico').val() + '</td>'+

                    '<td>' + $('#cod_tipo_aplicacion option:selected').text() + '</td>'+

                    '<td>' + $('#cod_maquinaria option:selected').text() + '</td>'+

                    '<td>' + $('#fecha_aplicacion_operador').val() + '</td>'+

                    '<td>' + $('#hora_inicial').val() + '</td>'+

                    '<td>' + $('#hora_final').val() + '</td>'+

                    '<td>' + $('#viento').val() + '</td>'+

                    '<td>' + $('#temperatura').val() + '</td>'+

                    '<td>' + $('#descripcion_aplicar_quimico').val() + '</td>'+

                    '<td><a title="Delete - Eliminar" onclick="$(\'#fila_'+num_fila+'\').remove();" class="smooth-transition btn btn-sm btn_delete_file btn-danger">Delete</a></td>'+

                    '</tr>');

                num_fila++;

                $(".input.requerido_aplicacion_operador").val('');

            }

            else

            {

                grl_mensaje('You must fill the empty fields','Debe llenar los campos vacíos','warning');

            }

        });





        $('#btn_guardar_trasplantar').click(function(event) {

            /* Act on the event */

            var error = 0;

            $(".input.requerido_trasplantar").map(function(){

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

            $(".selectpicker.requerido_trasplantar").map(function(){

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

                $('#modal_trasplantar').modal('hide');

                $('#tbody_trasplantes').append('<tr id="fila_'+num_fila+'">'+

                    '<td class="hide">' + codigo_plantacion + '</td>'+

                    '<td class="hide">' + $('#cod_bloques_plantacion2 option:selected').data('cod_plantacion') + '</td>'+

                    '<td class="hide">' + $('#cod_bloques_plantacion2').val() + '</td>'+

                    '<td class="hide">' + $('#cantidad').val() + '</td>'+

                    '<td class="hide">' + $('#cod_bloques_trasplante').val() + '</td>'+

                    '<td class="hide">' + $('#numero_carga').val() + '</td>'+

                    '<td class="hide">' + $('#fecha_trasplante').val() + '</td>'+

                    '<td class="hide">' + $('#observacion_trasplantar').val() + '</td>'+

                    '<td>' + $('#cod_bloques_plantacion2 option:selected').text() + '</td>'+

                    '<td>' + $('#cantidad').val() + '</td>'+

                    '<td>' + $('#cod_bloques_trasplante option:selected').text() + '</td>'+

                    '<td>' + $('#numero_carga').val() + '</td>'+

                    '<td>' + $('#fecha_trasplante').val() + '</td>'+

                    '<td>' + $('#observacion_trasplantar').val() + '</td>'+

                    '<td><a title="Delete - Eliminar" onclick="$(\'#fila_'+num_fila+'\').remove();" class="smooth-transition btn btn-sm btn_delete_file btn-danger">Delete</a></td>'+

                    '</tr>');

                num_fila++;

                $(".input.requerido_trasplantar").val('');

                //plan_guardar_trasplantar(codigo_plantacion);

                //plan_guardar_aplicar_quimico(cod_aplicacion_quimico, codigo_plantacion);

            }

            else

            {

                grl_mensaje('You must fill the empty fields','Debe llenar los campos vacíos','warning');

            }

        });



        /*Permite ingresar todos los valores agregados a la bdd*/

        $('#btn_sincronizar_todo').click(function(event) {

            /* Act on the event */

            try

            {

                $('#tbody_cleaning_offline > tr').each(function() {

                    plan_guardar_formulario_limpieza_offline($(this).find('td').eq(0).text(), $(this).find('td').eq(1).text(),$(this).find('td').eq(2).text(),$(this).find('td').eq(3).text(),$(this).find('td').eq(4).text(),$(this).find('td').eq(5).text(),$(this).find('td').eq(6).text(),$(this).find('td').eq(7).text(),$(this).find('td').eq(8).text(),$(this).find('td').eq(9).text(),$(this).find('td').eq(10).text(),$(this).find('td').eq(11).text());

                });



                $('#tbody_calibraciones > tr').each(function() {

                    plan_ingresar_calibracion_plantacion_offline($(this).find('td').eq(0).text(), $(this).find('td').eq(1).text(), $(this).find('td').eq(2).text(), $(this).find('td').eq(4).text(), $(this).find('td').eq(6).text(), $(this).find('td').eq(5).text());

                });



                $('#tbody_harvesting_checklist > tr').each(function() {

                    plan_guardar_formulario_harvesting_checklist_offline($(this).find('td').eq(0).text(), $(this).find('td').eq(1).text(), $(this).find('td').eq(2).text(), $(this).find('td').eq(3).text(), $(this).find('td').eq(4).text(), $(this).find('td').eq(5).text(), $(this).find('td').eq(6).text(), $(this).find('td').eq(7).text(), $(this).find('td').eq(8).text(), $(this).find('td').eq(9).text(), $(this).find('td').eq(10).text(), $(this).find('td').eq(11).text(), $(this).find('td').eq(12).text(), $(this).find('td').eq(13).text(), $(this).find('td').eq(14).text(), $(this).find('td').eq(15).text(), $(this).find('td').eq(16).text(), $(this).find('td').eq(17).text(), $(this).find('td').eq(18).text(), $(this).find('td').eq(19).text(), $(this).find('td').eq(20).text(), $(this).find('td').eq(21).text(), $(this).find('td').eq(22).text(), $(this).find('td').eq(23).text(), $(this).find('td').eq(24).text(), $(this).find('td').eq(25).text(), $(this).find('td').eq(26).text(), $(this).find('td').eq(27).text());

                });



                $('#tbody_exploraciones_offline > tr').each(function() {

                    plan_guardar_exploracion_offline($(this).find('td').eq(0).text(), $(this).find('td').eq(1).text(),$(this).find('td').eq(2).text(),$(this).find('td').eq(5).text(),$(this).find('td').eq(6).text(),$(this).find('td').eq(7).text(),$(this).find('td').eq(8).text(),$(this).find('td').eq(9).text(),$(this).find('td').eq(10).text(),$(this).find('td').eq(11).text(),$(this).find('td').eq(12).text(),$(this).find('td').eq(13).text(),$(this).find('td').eq(14).text(),$(this).find('td').eq(15).text(),$(this).find('td').eq(16).text(),$(this).find('td').eq(17).text(),$(this).find('td').eq(18).text(),$(this).find('td').eq(19).text(),$(this).find('td').eq(20).text(),$(this).find('td').eq(21).text(),$(this).find('td').eq(22).text(),$(this).find('td').eq(23).text(),$(this).find('td').eq(24).text(),$(this).find('td').eq(25).text(),$(this).find('td').eq(26).text(),$(this).find('td').eq(27).text(),$(this).find('td').eq(28).text(),$(this).find('td').eq(29).text(),$(this).find('td').eq(4).text());

                });



                $('#tbody_harvesting_worksheet > tr').each(function() {

                    plan_guardar_formulario_harvesting_worksheet_offline($(this).find('td').eq(0).text(), $(this).find('td').eq(1).text(),$(this).find('td').eq(2).text(),$(this).find('td').eq(3).text(),$(this).find('td').eq(4).text(),$(this).find('td').eq(5).text(),$(this).find('td').eq(6).text(),$(this).find('td').eq(11).text(),$(this).find('td').eq(12).text(),$(this).find('td').eq(13).text(),$(this).find('td').eq(14).text(),$(this).find('td').eq(15).text());

                });



                $('#tbody_cantidades_aplicadas > tr').each(function() {

                    plan_ingresar_cantidad_aplicada_quimico($(this).find('td').eq(0).text(), $(this).find('td').eq(2).text());

                });



                $('#tbody_aplicaciones_qumicas_operador > tr').each(function() {

                    plan_guardar_aplicar_quimico_offline($(this).find('td').eq(0).text(), $(this).find('td').eq(1).text(),$(this).find('td').eq(2).text(),$(this).find('td').eq(3).text(),$(this).find('td').eq(4).text(),$(this).find('td').eq(5).text(),$(this).find('td').eq(6).text(),$(this).find('td').eq(7).text(),$(this).find('td').eq(8).text(),$(this).find('td').eq(9).text());

                });



                $('#tbody_trasplantes > tr').each(function() {

                    plan_guardar_trasplantar_offline($(this).find('td').eq(0).text(),$(this).find('td').eq(1).text(),$(this).find('td').eq(2).text().split(','),$(this).find('td').eq(3).text(),$(this).find('td').eq(4).text().split(','),$(this).find('td').eq(5).text(),$(this).find('td').eq(6).text(),$(this).find('td').eq(7).text());

                });

                grl_mensaje('Your information was successfully synchronized.','Su información fue sincronizada con éxito.','success');

                $('#tbody_cleaning_offline,#tbody_calibraciones,#tbody_harvesting_checklist,#tbody_exploraciones_offline,#tbody_harvesting_worksheet,#tbody_cantidades_aplicadas,#tbody_aplicaciones_qumicas_operador').empty();

            }



            catch(err)

            {

                grl_mensaje('Error: ' + err.message,'Error on sync','warning');

            }

        });

        

        $('#btn_eliminar_todo').click(function(event) {

            /* Act on the event */

            $('#tbody_cleaning_offline,#tbody_calibraciones,#tbody_harvesting_checklist,#tbody_exploraciones_offline,#tbody_harvesting_worksheet,#tbody_cantidades_aplicadas,#tbody_aplicaciones_qumicas_operador,#tbody_trasplantes').empty();

        });

        $('#modal_loading').modal('hide');

        jQuery.ajaxSetup({async:true});

    });



</script>



<body>

    <div id="overlay_loading"></div>

    <div id="message_box"></div>

    <div class="panel panel-default">

        <div class="panel-body">

            <div class="col-md-12">

                <label class="label-translate translate" data-traducir_english="Traducir" data-traducir_spanish="Translate">Translate </label>

                <div class="material-switch pull-right">

                    <input id="checkbox_translate" data-id="checkbox_translate" name="checkbox_translate" type="checkbox" <?php echo ($USUARIO[0]['flag_traducir'] == 1 ? 'checked="checked"':'');?>/>

                    <label for="checkbox_translate" class=""></label>

                </div>

                <div class="panel-header">

                    <h1><span class="translate" data-traducir_english="Information about planting" data-traducir_spanish="Información sobre la plantación">Información sobre la plantación </span><span class="badge estado1" id="badge_estado2">Waiting to save</span><span class="badge estado1" id="badge_estado">En espera de guardado</span></h1>

                </div>

            </div>

            <div class="col-md-12">

                <div class="row">

                    <div class="col-md-4">

                        <div class="form-group input-group-sm">

                            <label for="cod_info_empresa" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</label>

                            <select class="selectpicker show-menu-arrow requerido" title="Seleccione" id="cod_info_empresa" name="cod_info_empresa">

                            </select>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group input-group-sm">

                            <label for="anio_plantacion" class="translate" data-traducir_english="Year" data-traducir_spanish="Año">Año</label>

                            <input type="text"class="form-control anio input requerido"  id="anio_plantacion" name="anio_plantacion">

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group input-group-sm">

                            <label for="num_plantacion" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</label>

                            <input type="text"class="form-control anio input requerido"  id="num_plantacion" name="num_plantacion">

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class='col-md-4'>

                        <div class="form-group">

                            <label for="fecha_plantacion_planeada" class="translate" data-traducir_english="Planned Planting Date" data-traducir_spanish="Fecha Plantación Planeada">Fecha Plantación Planeada</label>

                            <div class='input-group input-group-sm fecha-planeada date' id='datetimepicker1'>

                                <span class="input-group-addon">

                                    <span class="fa fa-calendar"></span>

                                </span>

                                <input type='text' class="form-control input requerido" id="fecha_plantacion_planeada" />

                            </div>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group input-group-sm">

                            <label for="acres_plantados" class="translate" data-traducir_english="Acres Planted" data-traducir_spanish="Acres Plantados">Acres Plantados</label>

                            <input type="text"class="form-control monto input" value="0" readonly="true" id="acres_plantados" name="acres_plantados">

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group input-group-sm" id="div_tipo_curso">

                            <label for="cod_temporada" class="translate" data-traducir_english="Season" data-traducir_spanish="Temporada">Temporada</label>

                            <select class="selectpicker show-menu-arrow requerido" title="Seleccione" id="cod_temporada" name="cod_temporada">

                            </select>

                        </div>

                    </div>



                </div>

                <div class="busqueda_contenedor">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="panel panel-primary" id="panel_itemschecklist">

                                <div class="panel-heading btncollapsepaso panel_cabecera" id="a_itemschecklist" role="button" data-toggle="collapse" href="#collapseitemschecklist" data-target="#collapseitemschecklist" aria-expanded="true" data-parent="#panel_itemschecklist" aria-controls="collapseitemschecklist">

                                    <h3 class="panel-title translate" data-traducir_english="Planting Zones" data-traducir_spanish="Zonas de la Plantación">Zonas de la Plantación</h3>

                                    <div class="pull-right">

                                        <span class="clickable filter" data-toggle="tooltip" title="Realizar busqueda" data-container="body">

                                            <i class="fa fa-search"></i>

                                        </span>

                                    </div>

                                </div>

                                <div class="panel-collapse collapse" id="collapseitemschecklist" role="tabpanel" aria-labelledby="collapseitemschecklist">

                                    <div class="panel-body panel_cuerpo">

                                        <input type="text" class="form-control" id="tabla_itemschecklist-filter" data-action="filter" data-filters="#tabla_itemschecklist" placeholder="Busqueda" />

                                    </div>

                                    <div class="responsive_table_container">

                                        <table class="table display row-border table-responsive" id="tabla_itemschecklist">

                                            <thead>

                                                 <tr class="active info">

                                                    <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>

                                                    <th width="5%" class="translate" data-traducir_english="Block Code" data-traducir_spanish="Código Bloque">Código Bloque</th>

                                                    <th width="15%" class="translate" data-traducir_english="Zone" data-traducir_spanish="Zona">Zona</th>

                                                    <th width="15%" class="translate" data-traducir_english="Block" data-traducir_spanish="Bloque">Bloque</th>

                                                    <th width="15%" class="translate" data-traducir_english="Acres" data-traducir_spanish="Acres">Acres</th>

                                                    <th width="15%" class="translate" data-traducir_english="Stage" data-traducir_spanish="Estado">Estado</th>

                                                    <th width="10%" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Acciones</th>

                                                 </tr>

                                            </thead>

                                            <tbody>

                                                <?php

                                                if (count($BLOQUES)) {

                                                    $correlativo = 1;

                                                    $estados = [ 'Waiting to save',

                                                                    'Pre-Planted',

                                                                    'Planted',

                                                                    'Trimmed',

                                                                    'Growing',

                                                                    'Ready to harvest',

                                                                    'Harvested',

                                                                    'QA',

                                                                    'Packaged product',

                                                                    'Removed',

                                                                    'Finished'];

                                                    foreach ($BLOQUES as $bloque) {

                                                        ?>

                                                        <tr>

                                                            <td><?php echo $correlativo; ?></td>

                                                            <td><?php echo utf8_encode($bloque['cod_detalle']);?></td>

                                                            <td><a role="button" data-toggle="collapse" data-parent="#table-container" href="#collapse<?php echo $correlativo; ?>" aria-expanded="true" aria-controls="collapseOne"><?php echo utf8_encode($bloque['zona']); ?></a></td>

                                                            <?php

                                                            if($bloque['clave_bloque'] != '' && $bloque['clave_bloque'] != null)

                                                            {

                                                                ?>

                                                                <td><?php echo utf8_encode($bloque['clave_bloque'].'-'.$bloque['nombre_bloque']);?></td>

                                                                <?php

                                                            }

                                                            else

                                                            {

                                                                ?>

                                                                <td><?php echo utf8_encode($bloque['nombre_bloque']);?></td>

                                                                <?php

                                                            }

                                                            ?>

                                                            <td><?php echo utf8_encode($bloque['cantidad_acres']); ?></td>

                                                            <td><span class="badge estado<?php echo utf8_encode($bloque['cod_estado_plantacion']); ?>" id="badge_estado2"><?php echo utf8_encode($estados[$bloque['cod_estado_plantacion']]); ?></span><span class="badge estado<?php echo utf8_encode($bloque['cod_estado_plantacion']); ?>" id="badge_estado"><?php echo utf8_encode($bloque['estado_plantacion']); ?></span></td>

                                                            

                                                            <td align="center" style="display: flex;">

                                                                <?php 

                                                                switch ($bloque['cod_estado_plantacion']) {

                                                                    case 1:

                                                                    case 2:

                                                                    case 3:

                                                                    case 4://CRECIMIENTO

                                                                        ?>

                                                                        <a title="Cleaning and Sanitizing of harvesting equipment" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_cleaning_sanitizing').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-left btn-sm btn-warning" style="width: 20%!important;"><i class="fa fa-snowplow"></i></a>

                                                                        <a title="Scouting - Explorar" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_explorar_plantacion').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-center btn-sm btn-success" style="width: 20%!important;"><i class="fa fa-leaf"></i></a>

                                                                        <a title="Calibrate - Calibrar" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_calibrar').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-right btn-sm btn-primary" style="width: 20%!important;"><i class="fa fa-adjust"></i></a>

                                                                        <?php

                                                                        break;

                                                                    case 6: //COSECHADO

                                                                        ?>

                                                                        <a title="Cleaning and Sanitizing of harvesting equipment" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_cleaning_sanitizing').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-left btn-sm btn-warning" style="width: 20%!important;"><i class="fa fa-snowplow"></i></a>

                                                                        <a title="Harvesting Worksheet" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_harvesting_worksheet').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-center-left btn-sm btn-default" style="width: 20%!important;"><i class="fab fa-pagelines"></i></a>

                                                                        <a title="Scouting - Explorar" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_explorar_plantacion').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-center-right btn-sm btn-success" style="width: 20%!important;"><i class="fa fa-leaf"></i></a>

                                                                        <a title="Calibrate - Calibrar" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_calibrar').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-right btn-sm btn-primary" style="width: 20%!important;"><i class="fa fa-adjust"></i></a>

                                                                        <?php

                                                                        break;

                                                                    case 5: //LISTO PARA COSECHAR

                                                                        ?>

                                                                        <a title="Pre-Operational/Harvesting Checklist" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_harvesting_checklist').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-left btn-sm btn-info" style="width: 20%!important;"><i class="fa fa-list-ol"></i></a>

                                                                        <a title="Cleaning and Sanitizing of Harvesting Equipment" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_cleaning_sanitizing').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-center-left btn-sm btn-warning" style="width: 20%!important;"><i class="fa fa-snowplow"></i></a>

                                                                        <a title="Scouting - Explorar" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_explorar_plantacion').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-center-right btn-sm btn-success" style="width: 20%!important;"><i class="fa fa-leaf"></i></a>

                                                                        <a title="Calibrate - Calibrar" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_calibrar').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-right btn-sm btn-primary" style="width: 20%!important;"><i class="fa fa-adjust"></i></a>

                                                                        <?php

                                                                        break;

                                                                    case 9:

                                                                    case 10:

                                                                        ?>

                                                                        <?php

                                                                        break;

                                                                    default:

                                                                        ?>

                                                                        <?php

                                                                        break;

                                                                }

                                                                ?>

                                                            </td>

                                                        </tr>

                                                        <tr>

                                                            <td colspan="7" class="no-padding">

                                                                <div id="collapse<?php echo $correlativo; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">

                                                                    <div class="panel-body">

                                                                        <div class="row">

                                                                            <div class="col-md-12 col-sm-12" id="div_historial<?php echo $correlativo; ?>">

                                                                            </div>

                                                                        </div>

                                                                    </div>

                                                                </div>

                                                            </td>

                                                        </tr>

                                                        <?php

                                                        $correlativo++;

                                                    }

                                                }

                                                else

                                                {

                                                    ?>

                                                    <tr>

                                                        <td colspan="5" class="translate" data-traducir_english="No blocks" data-traducir_spanish="No hay bloques">No hay bloques</td>

                                                    </tr>

                                                    <?php

                                                }

                                                ?>

                                            </tbody>

                                         </table>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                

                <div class="busqueda_contenedor">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="panel panel-primary" id="panel_historial_plantacion">

                                <div class="panel-heading btncollapsepaso panel_cabecera" id="a_historial_plantacion" role="button" data-toggle="collapse" href="#collapsehistorial_plantacion" aria-expanded="true" data-parent="#accordion" aria-controls="collapsehistorial_plantacion">

                                    <h3 class="panel-title translate" data-traducir_english="Planting History" data-traducir_spanish="Historial de la Plantación">Historial de la Plantación</h3>

                                    <div class="pull-right">

                                        <span class="clickable filter" data-toggle="tooltip" title="Realizar busqueda" data-container="body">

                                            <i class="fa fa-search"></i>

                                        </span>

                                    </div>

                                </div>

                                <div class="panel-collapse collapse" id="collapsehistorial_plantacion" role="tabpanel" aria-labelledby="collapsehistorial_plantacion">

                                    <div class="panel-body panel_cuerpo input-group-sm">

                                        <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#" placeholder="Busqueda" />

                                    </div>

                                    <?php                                      

                                    if (count($APLICACIONES)) {

                                    ?>

                                    <div class="responsive_table_container">

                                        <h3 class="translate" data-traducir_english="Chemical Applies" data-traducir_spanish="Aplicación químicos">Aplicación químicos</h3>

                                        <table class="table display row-border table-responsive" id="table_historial_aplicaciones_plantacion">

                                            <thead>

                                                 <tr class="active info">

                                                    <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>

                                                    <th width="15%" class="translate" data-traducir_english="Blocks" data-traducir_spanish="Bloques">Bloques</th>

                                                    <th width="10%" class="translate" data-traducir_english="Supervisor" data-traducir_spanish="Supervisor">Supervisor</th>

                                                    <th width="10%" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</th>

                                                    <th width="15%" class="translate" data-traducir_english="Machinery" data-traducir_spanish="Maquinaria">Maquinaria</th>

                                                    <th width="20%" class="translate" data-traducir_english="Chemical" data-traducir_spanish="Químico">Químico</th>

                                                    <th width="15%" class="translate" data-traducir_english="Suggested Amount" data-traducir_spanish="Cantidad Sugerida">Cantidad Sugerida</th>

                                                    <th width="15%" class="translate" data-traducir_english="Applied Amount" data-traducir_spanish="Cantidad Aplicada">Cantidad Aplicada</th>

                                                    <th width="15%" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Acciones</th>

                                                 </tr>

                                            </thead>

                                            <tbody>

                                                <?php

                                                $correlativo = 1;

                                                foreach ($APLICACIONES as $aplicacion)

                                                {

                                                    $QUIMICOS       = $DB_PLANT->plan_listado_quimicos_aplicacion_quimico($aplicacion['cod_aplicacion']);

                                                    foreach ($QUIMICOS as $quimico) 

                                                    {

                                                        ?>

                                                        <tr>

                                                            <td class="hide"><?php echo $quimico['cod_detalle']; ?></td>

                                                            <td><?php echo $correlativo; ?></td>

                                                            <td><?php echo utf8_encode($aplicacion['bloques']); ?></td>

                                                            <td><?php echo utf8_encode($aplicacion['usuario_supervisor']); ?></td>

                                                            <td><?php echo utf8_encode($aplicacion['fecha_aplicacion_supervisor']); ?></td>

                                                            <td><?php echo utf8_encode($aplicacion['nombre_maquinaria']); ?></td>

                                                            <td><?php echo utf8_encode($quimico['nombre_quimico']); ?></td>

                                                            <td><?php echo utf8_encode($quimico['cantidad_sugerida']); ?></td>

                                                            <td><?php echo utf8_encode($quimico['cantidad_aplicada']); ?></td>

                                                            <td align="center" style="display: flex;">

                                                                <a title="Add Quantity Applied - Agregar Cantidad Aplicada" onclick="nombre_quimico = '<?php echo utf8_encode($quimico['nombre_quimico']); ?>';plan_abrir_div_cantidad_aplicada(<?php echo utf8_encode($quimico['cod_detalle'].','.$quimico['cod_aplicacion'].','.$quimico['cantidad_sugerida'].','.$quimico['cantidad_aplicada']); ?>);" class="btn btn-eliminar btn-left btn-sm btn-success" style="width: 50%;"><i class="fas fa-pen-square"></i></a>

                                                                <a title="Apply Chemical - Aplicar Quimico" onclick="cod_aplicacion_quimico = '<?php echo utf8_encode($aplicacion['cod_aplicacion']); ?>';codigo_detalle = 0;$('#modal_aplicar_quimico').modal('show');" id="btn_aplicar_quimico_plantacion" class="btn btn-eliminar btn-right btn-sm btn-primary" style="width: 50%;"><i class="fa fa-flask"></i></a>

                                                            </td>

                                                        </tr>

                                                        <?php

                                                        $correlativo++;

                                                    }

                                                }

                                                ?>

                                            </tbody>

                                            <div id="div_cantidad_aplicada" class="hide">

                                                <div class="form-group input-group-sm col-xs-9">

                                                    <div class="form-group input-group-sm">

                                                        <label for="cantidad_aplicada">Cantidad Aplicada</label>

                                                        <input type="text"class="form-control monto input" id="cantidad_aplicada" name="cantidad_aplicada">

                                                    </div>

                                                </div>

                                                <div class="input-group-sm col-xs-3">

                                                    <div class="form-group">

                                                        <label for="">&nbsp;</label></br>

                                                        <button type="button" class="btn btn-sm btn-primary btn_guardar translate" data-traducir_spanish="Guardar" data-traducir_english="Save" data-cod_detalle="0" id="btn_agregar_cantidad_aplicada">Guardar</button>

                                                    </div>

                                                </div>

                                            </div>

                                        </table>

                                    </div>

                                    <?php

                                    }

                                    ?>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                <div class="busqueda_contenedor">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="panel panel-primary" id="panel_historial_plantacion_offline">

                                <div class="panel-heading btncollapsepaso panel_cabecera" id="a_historial_plantacion_offline" role="button" data-toggle="collapse" href="#collapsehistorial_plantacion_offline" aria-expanded="true" data-parent="#accordion" aria-controls="collapsehistorial_plantacion">

                                    <h3 class="panel-title translate" data-traducir_english="Offline Planting History" data-traducir_spanish="Historial de la Plantación Fuera de Línea">Historial de la Plantación Fuera de Línea</h3>

                                    <div class="pull-right">

                                        <span class="clickable filter" data-toggle="tooltip" title="Realizar busqueda" data-container="body">

                                            <i class="fa fa-search"></i>

                                        </span>

                                    </div>

                                </div>

                                <div class="panel-collapse collapse" id="collapsehistorial_plantacion_offline" role="tabpanel" aria-labelledby="collapsehistorial_plantacion_offline">

                                    <div class="panel-body panel_cuerpo input-group-sm">

                                        <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#" placeholder="Busqueda" />

                                    </div>

                                        <div class="responsive_table_container">

                                            <h3 class="translate" data-traducir_english="Cleaning and Sanitizing of Harvesting equipment" data-traducir_spanish="Limpieza y Desinfección del Equipo de Cosecha">Limpieza y Desinfección del Equipo de Cosecha</h3>

                                            <table class="table display row-border table-responsive" id="table_cleaning_offline">

                                                <thead>

                                                     <tr class="active info">

                                                        <th width="5%" class="translate" data-traducir_english="Block Code" data-traducir_spanish="Código Bloque">Código Bloque</th>

                                                        <th width="8%" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</th>

                                                        <th width="8%" class="translate" data-traducir_english="Time" data-traducir_spanish="Tiempo">Tiempo</th>

                                                        <th width="8%" class="translate" data-traducir_english="Equipment #" data-traducir_spanish="# Equipo"># Equipo</th>

                                                        <th width="8%" class="translate" data-traducir_english="Cleaning tools/materials" data-traducir_spanish="Limpieza de Herramientas/Materiales">Limpieza de Herramientas/Materiales</th>

                                                        <th width="8%" class="translate" data-traducir_english="Cleaning with potable water" data-traducir_spanish="Limpieza con agua potable">Limpieza con agua potable</th>

                                                        <th width="8%" class="translate" data-traducir_english="Cleaning with detergent" data-traducir_spanish="Limpieza con detergente">Limpieza con detergente</th>

                                                        <th width="8%" class="translate" data-traducir_english="Scrubbing" data-traducir_spanish="Fregado">Fregado</th>

                                                        <th width="8%" class="translate" data-traducir_english="Rinse with potable water" data-traducir_spanish="Enjuague con agua potable.">Enjuague con agua potable.</th>

                                                        <th width="8%" class="translate" data-traducir_english="Sanitizing with chlorine" data-traducir_spanish="Desinfección con cloro">Desinfección con cloro</th>

                                                        <th width="8%" class="translate" data-traducir_english="Post sanitizing rinse" data-traducir_spanish="Enjuague después de desinfectante">Enjuague después de desinfectante</th>

                                                        <th width="5%" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Acciones</th>

                                                     </tr>

                                                </thead>

                                                <tbody id="tbody_cleaning_offline">

                                                </tbody>

                                            </table>

                                        </div>

                                        <div class="responsive_table_container">

                                            <h3 class="translate" data-traducir_english="Explorations" data-traducir_spanish="Exploraciones">Exploraciones</h3>

                                            <table class="table display row-border table-responsive" id="table_historial_exploraciones">

                                                <thead>

                                                     <tr class="active info">

                                                        <th width="5%" class="translate" data-traducir_english="Block Code" data-traducir_spanish="Código Bloque">Código Bloque</th>

                                                        <th width="10%" class="translate" data-traducir_english="Growth stage" data-traducir_spanish="Etapa crecimiento">Etapa crecimiento</th>

                                                        <th width="10%" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</th>

                                                        <th width="15%" class="translate" data-traducir_english="Worms" data-traducir_spanish="Gusanos">Gusanos</th>

                                                        <th width="15%" class="translate" data-traducir_english="Eggs" data-traducir_spanish="Huevos">Huevos</th>

                                                        <th width="15%" class="translate" data-traducir_english="Leaf Hoppers" data-traducir_spanish="Saltahojas">Saltahojas</th>

                                                        <th width="15%" class="translate" data-traducir_english="Aphids" data-traducir_spanish="Afidos">Afidos</th>

                                                        <th width="15%" class="translate" data-traducir_english="Stink Bugs" data-traducir_spanish="Chinches">Chinches</th>

                                                        <th width="15%" class="translate" data-traducir_english="Gnats" data-traducir_spanish="Moscos">Moscos</th>

                                                        <th width="15%" class="translate" data-traducir_english="Flea Beetles" data-traducir_spanish="Escarabajos">Escarabajos</th>

                                                        <th width="15%" class="translate" data-traducir_english="Cyclaman Mites" data-traducir_spanish="Acaros">Acaros</th>

                                                        <th width="15%" class="translate" data-traducir_english="Cercospora Leaf Spot" data-traducir_spanish="Cercospora Leaf Spot">Cercospora Leaf Spot</th>

                                                        <th width="15%" class="translate" data-traducir_english="Pythium/Damp Off" data-traducir_spanish="Pythium/Damp Off">Pythium/Damp Off</th>

                                                        <th width="15%" class="translate" data-traducir_english="Rhizoctonia Aerial Blight" data-traducir_spanish="Rhizoctonia Aerial Blight">Rhizoctonia Aerial Blight</th>

                                                        <th width="15%" class="translate" data-traducir_english="Bacteria" data-traducir_spanish="Bacteria">Bacteria</th>

                                                        <th width="15%" class="translate" data-traducir_english="Sclerotinia" data-traducir_spanish="Sclerotinia">Sclerotinia</th>

                                                        <th width="15%" class="translate" data-traducir_english="Alternaria Specks" data-traducir_spanish="Alternaria Specks">Alternaria Specks</th>

                                                        <th width="15%" class="translate" data-traducir_english="Mildew" data-traducir_spanish="Mildew">Mildew</th>

                                                        <th width="15%" class="translate" data-traducir_english="Virus" data-traducir_spanish="Virus">Virus</th>

                                                        <th width="15%" class="translate" data-traducir_english="Dollarweed" data-traducir_spanish="Dollarweed">Dollarweed</th>

                                                        <th width="15%" class="translate" data-traducir_english="Frogs Bit" data-traducir_spanish="Frogs Bit">Frogs Bit</th>

                                                        <th width="15%" class="translate" data-traducir_english="Mud Plantain" data-traducir_spanish="Mud Plantain">Mud Plantain</th>

                                                        <th width="15%" class="translate" data-traducir_english="Tube Weed" data-traducir_spanish="Tube Weed">Tube Weed</th>

                                                        <th width="15%" class="translate" data-traducir_english="Grass" data-traducir_spanish="Grass">Grass</th>

                                                        <th width="15%" class="translate" data-traducir_english="Damaged Leaves" data-traducir_spanish="Damaged Leaves">Damaged Leaves</th>

                                                        <th width="15%" class="translate" data-traducir_english="Purple Stem" data-traducir_spanish="Purple Stem">Purple Stem</th>

                                                        <th width="15%" class="translate" data-traducir_english="Watercress rooted" data-traducir_spanish="Watercress rooted">Watercress rooted</th>

                                                        <th width="20%" class="translate" data-traducir_english="Observation" data-traducir_spanish="Observación">Observación</th>

                                                        <th width="10%" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</th>

                                                        <th width="5%" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Acciones</th>

                                                     </tr>

                                                </thead>

                                                <tbody id="tbody_exploraciones_offline">

                                                </tbody>

                                            </table>

                                        </div>

                                        <div class="responsive_table_container">

                                            <h3 class="translate" data-traducir_english="Harvesting Worksheet" data-traducir_spanish="Hoja de trabajo de la cosecha">Hoja de trabajo de la cosecha</h3>

                                            <table class="table display row-border table-responsive" id="table_historial_harvesting_worksheet">

                                                <thead>

                                                     <tr class="active info">

                                                        <th width="5%" class="translate" data-traducir_english="Block Code" data-traducir_spanish="Código Bloque">Código Bloque</th>

                                                        <th width="12%" class="translate" data-traducir_english="Date" data-traducir_spanish="Date">Date</th>

                                                        <th width="12%" class="translate" data-traducir_english="PHI" data-traducir_spanish="PHI">PHI</th>

                                                        <th width="12%" class="translate" data-traducir_english="Loose" data-traducir_spanish="Loose">Loose</th>

                                                        <th width="12%" class="translate" data-traducir_english="Increment (Bunch-Cello)" data-traducir_spanish="Increment (Bunch-Cello)">Increment (Bunch-Cello)</th>

                                                        <th width="12%" class="translate" data-traducir_english="Area FINISHED" data-traducir_spanish="Area FINISHED">Area FINISHED</th>

                                                        <th width="12%" class="translate" data-traducir_english="Acres Harvested" data-traducir_spanish="Acres Harvested">Acres Harvested</th>

                                                        <th width="12%" class="translate" data-traducir_english="PO# Request" data-traducir_spanish="PO# Request">PO# Request</th>

                                                        <th width="12%" class="translate" data-traducir_english="Quantity Harvested" data-traducir_spanish="Quantity Harvested">Quantity Harvested</th>

                                                        <th width="12%" class="translate" data-traducir_english="Quantity Packaged" data-traducir_spanish="Quantity Packaged">Quantity Packaged</th>

                                                        <th width="12%" class="translate" data-traducir_english="Comments" data-traducir_spanish="Comments">Comments</th>

                                                        <th width="5%" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Acciones</th>

                                                     </tr>

                                                </thead>

                                                <tbody id="tbody_harvesting_worksheet">

                                                </tbody>

                                            </table>

                                        </div>

                                        <div class="responsive_table_container">

                                            <h3 class="translate" data-traducir_english="Calibrations" data-traducir_spanish="Calibraciones">Calibraciones</h3>

                                            <table class="table display row-border table-responsive" id="table_historial_calibraciones_plantacion">

                                                <thead>

                                                     <tr class="active info">

                                                        <th width="5%" class="translate" data-traducir_english="Block Code" data-traducir_spanish="Código Bloque">Código Bloque</th>

                                                        <th width="20%" class="translate" data-traducir_english="Fertilizer" data-traducir_spanish="Fertilizante">Fertilizante</th>

                                                        <th width="20%" class="translate" data-traducir_english="Hours" data-traducir_spanish="Horas">Horas</th>

                                                        <th width="20%" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</th>

                                                        <th width="40%" class="translate" data-traducir_english="Observation" data-traducir_spanish="Observación">Observación</th>

                                                        <th width="5%" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Acciones</th>

                                                     </tr>

                                                </thead>

                                                <tbody id="tbody_calibraciones">

                                                </tbody>

                                            </table>

                                        </div>

                                        <div class="responsive_table_container">

                                            <h3 class="translate" data-traducir_english="Pre-Operational/Harvesting Checklist" data-traducir_spanish="Lista de items para revisar en la cosecha">Lista de items para revisar en la cosecha</h3>

                                            <table class="table display row-border table-responsive" id="table_historial_harvesting_checklist">

                                                <thead>

                                                     <tr class="active info">

                                                        <th width="5%" class="translate" data-traducir_english="Block Code" data-traducir_spanish="Código Bloque">Código Bloque</th>

                                                        <th width="12%" class="translate" data-traducir_english="Date" data-traducir_spanish="Date">Date</th>

                                                        <th width="12%" class="translate" data-traducir_english="Loose Bunches" data-traducir_spanish="Suelto o manojo">Suelto o manojo</th>

                                                        <th width="12%" class="translate" data-traducir_english="Conventional Organic" data-traducir_spanish="Convencional Organico">Convencional Organico</th>

                                                        <th width="12%" class="translate" data-traducir_english="Are harvest crews training records up to date?" data-traducir_spanish="¿Están actualizados los registros de entrenamiento de los cosechadores?">¿Están actualizados los registros de entrenamiento de los cosechadores?</th>

                                                        <th width="12%" class="translate" data-traducir_english="Are there sick workers?" data-traducir_spanish="¿Hay trabajadores enfermos?">¿Hay trabajadores enfermos?</th>

                                                        <th width="12%" class="translate" data-traducir_english="Have sick workers been reassigned to non-food contactjobs?" data-traducir_spanish="¿Se ha reasignado a los trabajadores enfermos a trabajos sin contacto con alimentos?">¿Se ha reasignado a los trabajadores enfermos a trabajos sin contacto con alimentos?</th>

                                                        <th width="12%" class="translate" data-traducir_english="Have harvesters properly covered open wounds, lesions, boils, etc.?" data-traducir_spanish="¿Los cosechadores cubren adecuadamente heridas abiertas, lesiones, forúnos,etc?">¿Los cosechadores cubren adecuadamente heridas abiertas, lesiones, forúnos,etc?</th>

                                                        <th width="12%" class="translate" data-traducir_english="Has the crew been instructed on cornpany policies regarding eating, drinking, tobacco use, iewelrv and other safety rules?" data-traducir_spanish=" Se ha instruido a los empleados sobre las políticas de la empresa con respecto a comer, beber, consumir tabaco, usar joyas y acerca de otras normas de seguridad?"> Se ha instruido a los empleados sobre las políticas de la empresa con respecto a comer, beber, consumir tabaco, usar joyas y acerca de otras normas de seguridad?</th>

                                                        <th width="12%" class="translate" data-traducir_english="Is the harvesting crew wearing clean and proper clothing?" data-traducir_spanish="¿El equipo de recolección lleva ropa limpia y adecuada?">¿El equipo de recolección lleva ropa limpia y adecuada?</th>

                                                        <th width="12%" class="translate" data-traducir_english="Are harvesting employees wearing hairnets, hats, cap, etc.?" data-traducir_spanish="¿Los empleados estan usando mallas, sombreros, gorro, etc?">¿Los empleados estan usando mallas, sombreros, gorro, etc?</th>

                                                        <th width="12%" class="translate" data-traducir_english="Are harvesting hands clean and sanítized?" data-traducir_spanish="¿Están limpias y desinfectadas las manos de los cosechadores?">¿Están limpias y desinfectadas las manos de los cosechadores?</th>

                                                        <th width="12%" class="translate" data-traducir_english="Are the portable toilets and sanitation station located at 1/4 mile or less from the harvestinz area?" data-traducir_spanish="¿Los retretes portátiles y la estación de saneamiento se encuentran a ¼ de milla o menos del área de recolección?">¿Los retretes portátiles y la estación de saneamiento se encuentran a ¼ de milla o menos del área de recolección?</th>

                                                        <th width="12%" class="translate" data-traducir_english="Have all harvesting tools been cleaned and sanitized?" data-traducir_spanish="Have all harvesting tools been cleaned and sanitized?">Have all harvesting tools been cleaned and sanitized?</th>

                                                        <th width="12%" class="translate" data-traducir_english="Are haul trucks properly cleaned and, if necessary sanitized?" data-traducir_spanish="¿Se limpian adecuadamente los camiones  y, si es necesario, se desinfectan?">¿Se limpian adecuadamente los camiones  y, si es necesario, se desinfectan?</th>

                                                        <th width="12%" class="translate" data-traducir_english="Has the truck used for harvesting-hauling produce and themechanical harvesting machine been inspected for oil-diesel/gasoline leaking, loose metal parts, or any other defect posing potential contamination issues?" data-traducir_spanish="Has the truck used for harvesting-hauling produce and themechanical harvesting machine been inspected for oil-diesel/gasoline leaking, loose metal parts, or any other defect posing potential contamination issues?">Has the truck used for harvesting-hauling produce and themechanical harvesting machine been inspected for oil-diesel/gasoline leaking, loose metal parts, or any other defect posing potential contamination issues?</th>

                                                        <th width="12%" class="translate" data-traducir_english="Has the truck used for harvesting-hauling produce fit for use?" data-traducir_spanish="Has the truck used for harvesting-hauling produce fit for use?">Has the truck used for harvesting-hauling produce fit for use?</th>

                                                        <th width="12%" class="translate" data-traducir_english="Has the mechanical harvesting rnachinery been cleaned and sanitized?" data-traducir_spanish="Has the mechanical harvesting rnachinery been cleaned and sanitized?">Has the mechanical harvesting rnachinery been cleaned and sanitized?</th>

                                                        <th width="12%" class="translate" data-traducir_english="Is the Mechanical Harvesting machine fit for use?" data-traducir_spanish="Is the Mechanical Harvesting machine fit for use?">Is the Mechanical Harvesting machine fit for use?</th>

                                                        <th width="12%" class="translate" data-traducir_english="Has all the harvesting equipment been inspected for glass breakage?" data-traducir_spanish="Has all the harvesting equipment been inspected for glass breakage?">Has all the harvesting equipment been inspected for glass breakage?</th>

                                                        <th width="12%" class="translate" data-traducir_english="Have all harvesting totes been cleaned and sanitized?" data-traducir_spanish="Have all harvesting totes been cleaned and sanitized?">Have all harvesting totes been cleaned and sanitized?</th>

                                                        <th width="12%" class="translate" data-traducir_english="Quantity of knives/hooks issued" data-traducir_spanish="Cantidad de cuchillas/herraminetas usadas">Cantidad de cuchillas/herraminetas usadas</th>

                                                        <th width="12%" class="translate" data-traducir_english="Quantity of knives/hooks returned" data-traducir_spanish="Cantidad de cuchillas/herramientas devueltas">Cantidad de cuchillas/herramientas devueltas</th>

                                                        <th width="12%" class="translate" data-traducir_english="Are there evidence of animal intrusion (fecal material), pest ínfestation, etc. that can pose a risk of contamination on the crop to harvest?" data-traducir_spanish="¿Esta la evidencia de intrusión animal (material fecal), infestación de plagas, etc., que pueden poner un riesgo de contaminación en el cultivo para cosechar?">¿Esta la evidencia de intrusión animal (material fecal), infestación de plagas, etc., que pueden poner un riesgo de contaminación en el cultivo para cosechar?</th>

                                                        <th width="12%" class="translate" data-traducir_english="Have buffer zones being implemented in the event of a contamination? 30ft (9.1 m) from flooded areas and 5ft (1.5m) from evidence of pest activity." data-traducir_spanish="¿Se han implementado zonas de amortiguamiento en caso de contaminación? 30 pies (9.1 m) de las áreas inundadas y 5 pies (1.5) de la evidencia de la plaga?">¿Se han implementado zonas de amortiguamiento en caso de contaminación? 30 pies (9.1 m) de las áreas inundadas y 5 pies (1.5) de la evidencia de la plaga?</th>

                                                        <th width="12%" class="translate" data-traducir_english="Has the crop-block/seetion been cleared for harvest?" data-traducir_spanish="¿Se ha limpiado la sección/bloque para la cosecha?">¿Se ha limpiado la sección/bloque para la cosecha?</th>

                                                        <th width="12%" class="translate" data-traducir_english="Preventive/corrective actions" data-traducir_spanish="Acciones preventivas/correctivas">Acciones preventivas/correctivas</th>

                                                        <th width="5%" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Acciones</th>

                                                     </tr>

                                                </thead>

                                                <tbody id="tbody_harvesting_checklist">

                                                </tbody>

                                            </table>

                                        </div>

                                        <div class="responsive_table_container">

                                            <h3 class="translate" data-traducir_english="Applied Amounts" data-traducir_spanish="Cantidades Aplicadas">Cantidades Aplicadas</h3>

                                            <table class="table display row-border table-responsive" id="table_historial_cantidad_aplicada_plantacion">

                                                <thead>

                                                     <tr class="active info">

                                                        <th width="5%" class="translate" data-traducir_english="Block Code" data-traducir_spanish="Código Bloque">Código Bloque</th>

                                                        <th width="20%" class="translate" data-traducir_english="Chemical" data-traducir_spanish="Quimico">Quimico</th>

                                                        <th width="20%" class="translate" data-traducir_english="Applied Amount" data-traducir_spanish="Cantidad Aplicada">Cantidad Aplicada</th>

                                                        <th width="5%" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Acciones</th>

                                                     </tr>

                                                </thead>

                                                <tbody id="tbody_cantidades_aplicadas">

                                                </tbody>

                                            </table>

                                        </div>

                                        <div class="responsive_table_container">

                                            <h3 class="translate" data-traducir_english="Chemical Applications by Operator" data-traducir_spanish="Aplicaciones Químicas por Operador">Aplicaciones Químicas por Operador</h3>

                                            <table class="table display row-border table-responsive" id="table_historial_cantidad_aplicada_plantacion">

                                                <thead>

                                                     <tr class="active info">

                                                        <th width="15%" class="translate" data-traducir_english="Application Type" data-traducir_spanish="Tipo Aplicación">Tipo Aplicación</th>

                                                        <th width="15%" class="translate" data-traducir_english="Machinery" data-traducir_spanish="Maquinaria">Maquinaria</th>

                                                        <th width="15%" class="translate" data-traducir_english="Applied chemical date" data-traducir_spanish="Fecha aplicada">Fecha aplicada</th>

                                                        <th width="15%" class="translate" data-traducir_english="Start time" data-traducir_spanish="Hora inicio">Hora inicio</th>

                                                        <th width="15%" class="translate" data-traducir_english="End time" data-traducir_spanish="Hora final">Hora final</th>

                                                        <th width="15%" class="translate" data-traducir_english="Wind (Mi)" data-traducir_spanish="Viento (Mi)">Viento (Mi)</th>

                                                        <th width="15%" class="translate" data-traducir_english="Temperature (°F)" data-traducir_spanish="Temperatura (°F)">Temperatura (ºF)</th>

                                                        <th width="20%" class="translate" data-traducir_english="Description" data-traducir_spanish="Descripción">Descripción</th>

                                                        <th width="10%" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Acciones</th>

                                                     </tr>

                                                </thead>

                                                <tbody id="tbody_aplicaciones_qumicas_operador">

                                                </tbody>

                                            </table>

                                        </div>

                                        <div class="responsive_table_container">

                                            <h3 class="translate" data-traducir_english="Transplant" data-traducir_spanish="Trasplantes">Trasplantes</h3>

                                            <table class="table display row-border table-responsive" id="table_historial_trasplantes_plantacion">

                                                <thead>

                                                     <tr class="active info">

                                                        <th width="15%" class="translate" data-traducir_english="Blocks" data-traducir_spanish="Bloques">Bloques</th>

                                                        <th width="15%" class="translate" data-traducir_english="Quantity" data-traducir_spanish="Cantidad">Cantidad</th>

                                                        <th width="15%" class="translate" data-traducir_english="Blocks" data-traducir_spanish="Bloques">Bloques</th>

                                                        <th width="15%" class="translate" data-traducir_english="Load Number" data-traducir_spanish="Número de Carga">Número de Carga</th>

                                                        <th width="15%" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</th>

                                                        <th width="15%" class="translate" data-traducir_english="Observation" data-traducir_spanish="Observación">Observación</th>

                                                        <th width="10%" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Acciones</th>

                                                     </tr>

                                                </thead>

                                                <tbody id="tbody_trasplantes">

                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    

    <div class="modal fade" id="modal_explorar_plantacion" tabindex="-1" role="dialog" aria-labelledby="modal_confirm_box" aria-hidden="true">

        <div class="modal-dialog modal-lg">

            <div class="modal-content modal-primary">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    <h4 class="modal-title translate" data-traducir_english="Scouting" data-traducir_spanish="Explorar" id="modal_confirm_box"> Explorar </h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="input-group-sm col-xs-12" id="">

                            <label for="etapa_crecimiento">Growth Stage</label>

                            <div class="show-tick">

                                <select class="selectpicker show-menu-arrow requerido_explorar_plantacion" id="etapa_crecimiento" name="etapa_crecimiento" data-live-search="true" title="Seleccione">

                                    <option value="-b">Select - Seleccione</option>

                                    <option value="1">Stubble</option>

                                    <option value="2">Leaf Up Stubbles</option>

                                    <option value="3">Highcress</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="fecha_exploracion" class="translate" data-traducir_english="Exploration date" data-traducir_spanish="Fecha exploración">Fecha exploración</label>

                            <div class='input-group input-group-sm fecha-planeada date' id='datetimepicker1'>

                                <span class="input-group-addon">

                                    <span class="fa fa-calendar"></span>

                                </span>

                                <input type='text' class="form-control input requerido_explorar_plantacion" id="fecha_exploracion" />

                            </div>

                        </div>

                        <div class="col-xs-12"><h3>Insects - Insectos</h3></div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="worms">Worms - Gusanos</label>

                                <input value="0" type="text"class="form-control monto input requerido_explorar_plantacion" id="worms" name="worms">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="eggs">Eggs - Huevos</label>

                                <input value="0" type="text"class="form-control monto input requerido_explorar_plantacion" id="eggs" name="eggs">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="leafhoppers">Leaf Hoppers - Saltahojas</label>

                                <input value="0" type="text"class="form-control monto input requerido_explorar_plantacion" id="leafhoppers" name="leafhoppers">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="aphids">Aphids - Afidos</label>

                                <input value="0" type="text"class="form-control monto input requerido_explorar_plantacion" id="aphids" name="aphids">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="stink_bugs">Stink Bugs - Chinches</label>

                                <input value="0" type="text"class="form-control monto input requerido_explorar_plantacion" id="stink_bugs" name="stink_bugs">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="gnats">Gnats - Moscos</label>

                                <input value="0" type="text"class="form-control monto input requerido_explorar_plantacion" id="gnats" name="gnats">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="flea_beetles">Flea Beetles - Escarabajos</label>

                                <input value="0" type="text"class="form-control monto input requerido_explorar_plantacion" id="flea_beetles" name="flea_beetles">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="cyclaman_mites">Cyclaman Mites - Acaros</label>

                                <input value="0" type="text"class="form-control monto input requerido_explorar_plantacion" id="cyclaman_mites" name="cyclaman_mites">

                            </div>

                        </div>

                        <div class="col-xs-12"><h3>Diseases - Enfermedades</h3></div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="cercospora_leaf_spot">Cercospora Leaf Spot</label>

                                <input value="0" type="text"class="form-control monto input requerido_explorar_plantacion" id="cercospora_leaf_spot" name="cercospora_leaf_spot">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="pythium">Pythium/Damp Off</label>

                                <input value="0" type="text"class="form-control monto input requerido_explorar_plantacion" id="pythium" name="pythium">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="rhizoctonia_aerial_blight">Rhizoctonia Aerial Blight</label>

                                <input value="0" type="text"class="form-control monto input requerido_explorar_plantacion" id="rhizoctonia_aerial_blight" name="rhizoctonia_aerial_blight">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="bacteria">Bacteria</label>

                                <input value="0" type="text"class="form-control monto input requerido_explorar_plantacion" id="bacteria" name="bacteria">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="sclerotinia">Sclerotinia</label>

                                <input value="0" type="text"class="form-control monto input requerido_explorar_plantacion" id="sclerotinia" name="sclerotinia">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="alternaria_specks">Alternaria Specks</label>

                                <input value="0" type="text"class="form-control monto input requerido_explorar_plantacion" id="alternaria_specks" name="alternaria_specks">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="mildew">Mildew</label>

                                <input value="0" type="text"class="form-control monto input requerido_explorar_plantacion" id="mildew" name="mildew">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="virus">Virus</label>

                                <input value="0" type="text"class="form-control monto input requerido_explorar_plantacion" id="virus" name="virus">

                            </div>

                        </div>

                        <div class="col-xs-12"><h3>Weeds - Malas Hierbas</h3></div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="dollarweed">Dollarweed</label>

                                <input value="0" type="text"class="form-control monto input requerido_explorar_plantacion" id="dollarweed" name="dollarweed">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="frogs_bit">Frogs Bit</label>

                                <input value="0" type="text"class="form-control monto input requerido_explorar_plantacion" id="frogs_bit" name="frogs_bit">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="mud_plantain">Mud Plantain</label>

                                <input value="0" type="text"class="form-control monto input requerido_explorar_plantacion" id="mud_plantain" name="mud_plantain">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="tube_weed">Tube Weed</label>

                                <input value="0" type="text"class="form-control monto input requerido_explorar_plantacion" id="tube_weed" name="tube_weed">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="grass">Grass</label>

                                <input value="0" type="text"class="form-control monto input requerido_explorar_plantacion" id="grass" name="grass">

                            </div>

                        </div>

                        <div class="col-xs-12"><h3>Other Damage</h3></div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="damaged_leaves">Damaged Leaves - Hojas Dañadas</label>

                                <input value="0" type="text"class="form-control monto input requerido_explorar_plantacion" id="damaged_leaves" name="damaged_leaves">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="purple_stem">Purple Stem - Tallos purpuras</label>

                                <input value="0" type="text"class="form-control monto input requerido_explorar_plantacion" id="purple_stem" name="purple_stem">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="watercress_rooter">Watercress Rooted - Berro Enraizado</label>

                                <input value="0" type="text"class="form-control monto input requerido_explorar_plantacion" id="watercress_rooter" name="watercress_rooter">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="observaciones_exploracion">Observaciones</label>

                            <div class="form-group show-tick">

                                <textarea maxlength="2500" align="left" style="height:100px; width:100%; resize: none;" class="form-control input " rows="2" id="observaciones_exploracion" style="width: 100%"></textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancelar</button>

                    <button type="button" class="btn btn-sm btn-primary smooth-transition" id="btn_guardar_exploracion_plantacion" >Aceptar</button>

                </div>

            </div>

        </div>

    </div>

    <div class="modal fade" id="modal_calibrar" tabindex="-1" role="dialog" aria-labelledby="modal_confirm_box" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content modal-primary">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    <h4 class="modal-title translate" data-traducir_english="Calibrate" data-traducir_spanish="Calibrar" id="modal_confirm_box"> Calibrar </h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="cod_fertilizante" class="translate" data-traducir_english="Fertilizer" data-traducir_spanish="Fertilizante">Fertilizante</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_calibrar" id="cod_fertilizante" name="cod_fertilizante" data-live-search="true" title="Seleccione">

                                    <option value="-b">Select - Seleccione</option>

                                    <option value="1">ON</option>

                                    <option value="2">OFF</option>

                                    <option value="3">Only Water - Sólo Agua</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="horas_aplicacion_calibrar" class="translate" data-traducir_english="Hours of application" data-traducir_spanish="Horas de aplicación">Horas de aplicación</label>

                                <input type="text"class="form-control monto input requerido_calibrar" id="horas_aplicacion_calibrar" name="horas_aplicacion_calibrar">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="fecha_calibracion" class="translate" data-traducir_english="Calibration date" data-traducir_spanish="Fecha calibración">Fecha calibración</label>

                            <div class='input-group input-group-sm fecha-planeada date' id='datetimepicker1'>

                                <span class="input-group-addon">

                                    <span class="fa fa-calendar"></span>

                                </span>

                                <input type='text' class="form-control input requerido_calibrar" id="fecha_calibracion" />

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="observaciones_calibrar" class="translate" data-traducir_english="Observations" data-traducir_spanish="Observaciones">Observaciones</label>

                            <div class="form-group show-tick">

                                <textarea maxlength="2500" align="left" style="height:100px; width:100%; resize: none;" class="form-control input requerido_calibrar" rows="2" id="observaciones_calibrar" style="width: 100%"></textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cancelar</button>

                    <button type="button" class="btn btn-sm btn-primary smooth-transition translate" data-traducir_english="Accept" data-traducir_spanish="Aceptar" id="btn_calibrar_bloque" >Aceptar</button>

                </div>

            </div>

        </div>

    </div>

    <div class="modal fade" id="modal_estado" tabindex="-1" role="dialog" aria-labelledby="modal_confirm_box" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content modal-primary">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    <h4 class="modal-title translate" data-traducir_english="Block Stage" data-traducir_spanish="Estado de Bloque" id="modal_confirm_box"> Estado de Bloque </h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="cod_estado_plantacion" class="translate" data-traducir_english="Stage" data-traducir_spanish="Estado">Estado</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_estado_bloque" id="cod_estado_plantacion" name="cod_estado_plantacion" data-live-search="true" title="Seleccione">

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="motivo_estado_plantacion" class="translate" data-traducir_english="Reason" data-traducir_spanish="Motivo">Motivo</label>

                            <div class="form-group show-tick">

                                <textarea maxlength="2500" align="left" style="height:100px; width:100%; resize: none;" class="form-control input requerido_estado_bloque" rows="2" id="motivo_estado_plantacion" style="width: 100%"></textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Cancel" data-traducir_spanish="Cancelar" data-dismiss="modal">Cancelar</button>

                    <button type="button" class="btn btn-sm btn-primary smooth-transition translate" data-traducir_english="Acept" data-traducir_spanish="Aceptar" id="btn_estado_bloque" >Aceptar</button>

                </div>

            </div>

        </div>

    </div>



    <div class="modal fade modal_cleaning_sanitizing" id="modal_cleaning_sanitizing" tabindex="-1" role="dialog" aria-labelledby="ModalResponsables" aria-hidden="true">

        <div class="modal-dialog modal-success modal-md">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                    <h4 class="modal-title"><span id="titulo_modal_responsables" class="translate" data-traducir_english="Cleaning and Sanitizing of Harvesting equipment" data-traducir_spanish="Limpieza y Desinfección del Equipo de Cosecha">Limpieza y Desinfección del Equipo de Cosecha</span> <small class="subtitulo" id="subtitulo_modal_responsables"></small></h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="fecha_limpieza" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</label>

                            <div class='input-group input-group-sm fecha-planeada date' id='datetimepicker1'>

                                <span class="input-group-addon">

                                    <span class="fa fa-calendar"></span>

                                </span>

                                <input type='text' class="form-control input requerido_limpieza" id="fecha_limpieza" />

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="vez_limpieza" class="translate" data-traducir_english="Time" data-traducir_spanish="Tiempo">Tiempo</label>

                            <div class='input-group input-group-sm time' id=''>

                                <span class="input-group-addon">

                                    <span class="fa fa-calendar"></span>

                                </span>

                                <input type='text' class="form-control input requerido_limpieza" id="vez_limpieza" />

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="equipo_limpieza" class="translate" data-traducir_english="Equipment #" data-traducir_spanish="# Equipo"># Equipo</label>

                                <input type="text"class="form-control letras input requerido_limpieza" id="equipo_limpieza" name="equipo_limpieza">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="cleaning_tools" class="translate" data-traducir_english="Cleaning tools/material" data-traducir_spanish="Limpiar herramientas/materiales">Limpiar herramientas/materiales</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_limpieza" id="cleaning_tools" name="cleaning_tools" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="cleaning_potable_water" class="translate" data-traducir_english="Cleaning with potable water" data-traducir_spanish="Limpiar con agua potable">Limpiar con agua potable</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_limpieza" id="cleaning_potable_water" name="cleaning_potable_water" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="cleaning_detergent" class="translate" data-traducir_english="Cleaning with detergent" data-traducir_spanish="Limpiar con detergente">Limpiar con detergente</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_limpieza" id="cleaning_detergent" name="cleaning_detergent" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="scrubbing" class="translate" data-traducir_english="Scrubbing" data-traducir_spanish="Restregar">Restregar</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_limpieza" id="scrubbing" name="scrubbing" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="rinse_potable_water" class="translate" data-traducir_english="Rinse with potable water" data-traducir_spanish="Enjuagar con agua potable">Enjuagar con agua potable</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_limpieza" id="rinse_potable_water" name="rinse_potable_water" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="sanitizing_chlorine" class="translate" data-traducir_english="Sanitizing with chlorine" data-traducir_spanish="Desinfectar con cloro">Desinfectar con cloro</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_limpieza" id="sanitizing_chlorine" name="sanitizing_chlorine" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="post_sanitizing" class="translate" data-traducir_english="Post sanitizing rinse with potable water" data-traducir_spanish="Desinfectar despues de enjuagar con agua potable">Desinfectar despues de enjuagar con agua potable</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_limpieza" id="post_sanitizing" name="post_sanitizing" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>

                    <button type="button" class="btn btn-sm btn-primary btn_guardar translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_guardar_cleaning">Guardar</button>

                </div>

            </div>

        </div>

    </div>



    <div class="modal fade modal_harvesting_worksheet" id="modal_harvesting_worksheet" tabindex="-1" role="dialog" aria-labelledby="ModalResponsables" aria-hidden="true">

        <div class="modal-dialog modal-success modal-md">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                    <h4 class="modal-title"><span id="titulo_modal_responsables" class="translate" data-traducir_english="Harvesting Worksheet" data-traducir_spanish="Hoja de Cosecha">Hoja de Cosecha</span> <small class="subtitulo" id="subtitulo_modal_responsables"></small></h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="harvest_date" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</label>

                            <div class='input-group input-group-sm fecha-planeada date' id='datetimepicker1'>

                                <span class="input-group-addon">

                                    <span class="fa fa-calendar"></span>

                                </span>

                                <input type='text' class="form-control input requerido_harvesting_worksheet" id="harvest_date" />

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="phi">PHI</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_worksheet" id="phi" name="phi" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="cellos">Loose</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_worksheet" id="cellos" name="cellos" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="increment_bunch_cello" class="translate" data-traducir_english="Increment (Bunch-Loose)" data-traducir_spanish="Incrementar (Suelto-Manojo)">Incrementar (Suelto-Manojo)</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_worksheet" id="increment_bunch_cello" name="increment_bunch_cello" data-live-search="true" title="Seleccione">

                                    <option value="1">Loose</option>

                                    <option value="0">Bunch</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="area_finished" class="translate" data-traducir_english="Area FINISHED?" data-traducir_spanish="¿Area Terminada?">¿Area Terminada?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_worksheet" id="area_finished" name="area_finished" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="acres_harvested" class="translate" data-traducir_english="Acres Harvested" data-traducir_spanish="Acres Cosechados">Acres Cosechados</label>

                                <input type="text"class="form-control monto input requerido_harvesting_worksheet" id="acres_harvested" name="acres_harvested">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="orden_compra" class="translate" data-traducir_english="PO#" data-traducir_spanish="Orden de Compra">Orden de Compra</label>

                                <input type="text"class="form-control letras10 input requerido_harvesting_worksheet" id="orden_compra" name="orden_compra">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="cantidad_cosechada_worksheet" class="translate" data-traducir_english="Harvested quantity" data-traducir_spanish="Cantidad Cosechada">Cantidad Cosechada</label>

                                <input type="text"class="form-control monto input requerido_harvesting_worksheet" id="cantidad_cosechada_worksheet" name="cantidad_cosechada_worksheet">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="cantidad_empacada_worksheet" class="translate" data-traducir_english="Packaged Quantity" data-traducir_spanish="Cantidad Empacada">Cantidad Empacada</label>

                                <input type="text"class="form-control monto input requerido_harvesting_worksheet" id="cantidad_empacada_worksheet" name="cantidad_empacada_worksheet">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="commments_harvesting_worksheet" class="translate" data-traducir_english="Comments" data-traducir_spanish="Comentarios">Comentarios</label>

                            <div class="form-group show-tick">

                                <textarea maxlength="2500" align="left" style="height:100px; width:100%; resize: none;" class="form-control limpiar" rows="2" id="commments_harvesting_worksheet" style="width: 100%"></textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>

                    <button type="button" class="btn btn-sm btn-primary btn_guardar translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_guardar_harvesting_worksheet">Guardar</button>

                </div>

            </div>

        </div>

    </div>

    <div class="modal fade modal_harvesting_checklist" id="modal_harvesting_checklist" tabindex="-1" role="dialog" aria-labelledby="ModalResponsables" aria-hidden="true">

        <div class="modal-dialog modal-success modal-md">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                    <h4 class="modal-title"><span id="titulo_modal_responsables" class="translate" data-traducir_english="Pre-Operational/Harvesting Checklist" data-traducir_spanish="Pre-Operacional/Formulario de Cosecha">Pre-Operacional/Formulario de Cosecha</span> <small class="subtitulo" id="subtitulo_modal_responsables"></small></h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="fecha_checklist" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</label>

                            <div class='input-group input-group-sm fecha-planeada date' id='datetimepicker1'>

                                <span class="input-group-addon">

                                    <span class="fa fa-calendar"></span>

                                </span>

                                <input type='text' class="form-control input requerido_harvesting_checklist" id="fecha_checklist" />

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="loose_bunches" class="translate" data-traducir_english="Loose or Bunch" data-traducir_spanish="Suelto o Manojo">Suelto o Manojo</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="loose_bunches" name="loose_bunches" data-live-search="true" title="Seleccione">

                                    <option value="1">Loose</option>

                                    <option value="0">Bunch</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="conventional_organic" class="translate" data-traducir_english="Conventional or Organic" data-traducir_spanish="Convencional u Organico">Conventional or Organic</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="conventional_organic" name="conventional_organic" data-live-search="true" title="Seleccione">

                                    <option value="1">Conventional</option>

                                    <option value="0">Organic</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question1" class="translate" data-traducir_english="Are harvest crews training records up to date?" data-traducir_spanish="¿Están actualizados los registros de entrenamiento de los cosechadores?">¿Están actualizados los registros de entrenamiento de los cosechadores?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question1" name="question1" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question2" class="translate" data-traducir_english="Are there sick workers?" data-traducir_spanish="¿Hay trabajadores enfermos?">¿Hay trabajadores enfermos?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question2" name="question2" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question3" class="translate" data-traducir_english="Have sick workers been reassigned to non-food contact jobs?" data-traducir_spanish="¿Se ha reasignado a los trabajadores enfermos a trabajos sin contacto con alimentos?">¿Se ha reasignado a los trabajadores enfermos a trabajos sin contacto con alimentos?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question3" name="question3" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question4" class="translate" data-traducir_english="Have harvesters properly covered open wounds, lesion, boils, etc?" data-traducir_spanish="¿Los cosechadores cubren adecuadamente heridas abiertas, lesiones, forúnos,etc?">¿Los cosechadores cubren adecuadamente heridas abiertas, lesiones, forúnos,etc?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question4" name="question4" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question5" class="translate" data-traducir_english="Has the crew been instructed on company policies regarding eating, drinking, tobacco use, jewelry and other safety rules?" data-traducir_spanish="¿Se ha instruido a los empleados sobre las políticas de la empresa con respecto a comer, beber, consumir tabaco, usar joyas y acerca de otras normas de seguridad?">¿Se ha instruido a los empleados sobre las políticas de la empresa con respecto a comer, beber, consumir tabaco, usar joyas y acerca de otras normas de seguridad?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question5" name="question5" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question6" class="translate" data-traducir_english="Is the harvesting crew wearing clean and proper clothing?" data-traducir_spanish="¿El equipo de recolección lleva ropa limpia y adecuada?">¿El equipo de recolección lleva ropa limpia y adecuada?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question6" name="question6" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question7" class="translate" data-traducir_english="Are harvesting employees wearing hairnets, hats, cap, etc?" data-traducir_spanish="¿Los empleados estan usando mallas, sombreros, gorro, etc?">¿Los empleados estan usando mallas, sombreros, gorro, etc?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question7" name="question7" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question8" class="translate" data-traducir_english="Are harvesters’  hands clean and sanitized?" data-traducir_spanish="¿Están limpias y desinfectadas las manos de los cosechadores?">¿Están limpias y desinfectadas las manos de los cosechadores?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question8" name="question8" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question9" class="translate" data-traducir_english="Are the portable toilets and sanitation station located at 1/4 mile or less from the harvesting area?" data-traducir_spanish="¿Los retretes portátiles y la estación de saneamiento se encuentran a ¼ de milla o menos del área de recolección?">¿Los retretes portátiles y la estación de saneamiento se encuentran a ¼ de milla o menos del área de recolección?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question9" name="question9" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="desde_aqui_no_traducido">

                            <label for="question10" class="translate" data-traducir_english="Have all harvesting tools been cleaned and sanitized?" data-traducir_spanish="¿Se han limpiado y desinfectado todas las herramientas de cosecha?">¿Se han limpiado y desinfectado todas las herramientas de cosecha?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question10" name="question10" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question11" class="translate" data-traducir_english="Are haul truck properly cleaned and, if necessary sanitized?" data-traducir_spanish="¿Se limpian adecuadamente los camiones de acarreo y, si es necesario, se desinfectan?">¿Se limpian adecuadamente los camiones de acarreo y, si es necesario, se desinfectan?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question11" name="question11" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question12" class="translate" data-traducir_english="Has the truck used for harvesting-hauling produce and the mechanical harvesting machine been inspected for oil-diesel/gasoline leaking, loose metal parts, or any other defectposing potential contamination issues?" data-traducir_spanish="¿Se ha inspeccionado la carretilla utilizada para cosechar y transportar productos y la máquina mecánica de recolección para detectar fugas de aceite, diesel o gasolina, piezas metálicas sueltas o cualquier otro problema de posible contaminación potencial?">¿Se ha inspeccionado la carretilla utilizada para cosechar y transportar productos y la máquina mecánica de recolección para detectar fugas de aceite, diesel o gasolina, piezas metálicas sueltas o cualquier otro problema de posible contaminación potencial?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question12" name="question12" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question13" class="translate" data-traducir_english="Is the truck used for harvesting-hauling produce fit for use?" data-traducir_spanish="¿Es el camión usado para cosechar y transportar productos aptos para el uso?">¿Es el camión usado para cosechar y transportar productos aptos para el uso?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question13" name="question13" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question14" class="translate" data-traducir_english="Has the mechanical harvesting machinery been cleaned and sanitized?" data-traducir_spanish="¿Se ha limpiado y desinfectado la maquinaria de recolección mecánica?">¿Se ha limpiado y desinfectado la maquinaria de recolección mecánica?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question14" name="question14" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question15" class="translate" data-traducir_english="Is the Mechanical Harvesting machine fit for use?" data-traducir_spanish="¿La máquina de cosecha mecánica es apta para el uso?">¿La máquina de cosecha mecánica es apta para el uso?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question15" name="question15" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question16" class="translate" data-traducir_english="Has all the harvesting equipment been inspected for glass breakage?" data-traducir_spanish="¿Se han inspeccionado todos los equipos de cosecha para detectar roturas de vidrio?">¿Se han inspeccionado todos los equipos de cosecha para detectar roturas de vidrio?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question16" name="question16" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question17" class="translate" data-traducir_english="Have all harvesting totes been cleaned and sanitized?" data-traducir_spanish="¿Se han limpiado y desinfectado todas las bolsas de cosecha?">¿Se han limpiado y desinfectado todas las bolsas de cosecha?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question17" name="question17" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="question18" class="translate" data-traducir_english="Quantity of knives/hooks issued" data-traducir_spanish="Cantidad de cuchillas/herraminetas usadas">Cantidad de cuchillas/herraminetas usadas</label>

                                <input type="text"class="form-control numeros4 input requerido_harvesting_checklist" id="question18" name="question18">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="question19" class="translate" data-traducir_english="Quantity of knives/hooks returned" data-traducir_spanish="Cantidad de cuchillas/herramientas devueltas">Cantidad de cuchillas/herramientas devueltas</label>

                                <input type="text"class="form-control numeros4 input requerido_harvesting_checklist" id="question19" name="question19">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question20" class="translate" data-traducir_english="Are the evidence of animal intrusion (fecal material), pest infestation, etc. that can pose a risk of contamination on the crop to harvest?" data-traducir_spanish="¿Hay evidencia de intrusión animal (materia fecal), infestación de plagas, etc. que pueda representar un riesgo de contaminación en el cultivo para cosechar?">¿Hay evidencia de intrusión animal (materia fecal), infestación de plagas, etc. que pueda representar un riesgo de contaminación en el cultivo para cosechar?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question20" name="question20" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question21" class="translate" data-traducir_english="Have buffer zones being implemented in the event of a contamination? 30ft (9.1m) from flooded ares and 5ft (1.5m) from evidence of pest activity" data-traducir_spanish="¿Se han implementado zonas de amortiguamiento en caso de contaminación? 30 pies (9.1 m) de la zona inundada y 5 pies (1.5 m) de la evidencia de actividad de plagas">¿Se han implementado zonas de amortiguamiento en caso de contaminación? 30 pies (9.1 m) de la zona inundada y 5 pies (1.5 m) de la evidencia de actividad de plagas</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question21" name="question21" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question22" class="translate" data-traducir_english="Has the crop-block/section been cleared for harvest?" data-traducir_spanish="¿Se ha limpiado el bloque / sección de cultivo para la cosecha?">¿Se ha limpiado el bloque / sección de cultivo para la cosecha?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question22" name="question22" data-live-search="true" title="Seleccione">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="actions" class="translate" data-traducir_english="Preventive/corrective actions" data-traducir_spanish="Acciones preventivas/correctivas">Acciones preventivas/correctivas</label>

                            <div class="form-group show-tick">

                                <textarea maxlength="2500" align="left" style="height:100px; width:100%; resize: none;" class="form-control limpiar" rows="2" id="actions" style="width: 100%"></textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>

                    <button type="button" class="btn btn-sm btn-primary btn_guardar translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_guardar_harvesting_checklist">Guardar</button>

                </div>

            </div>

        </div>

    </div>





    <div class="modal fade modal_aplicar_quimico" id="modal_aplicar_quimico" tabindex="-1" role="dialog" aria-labelledby="ModalResponsables" aria-hidden="true">

        <div class="modal-dialog modal-success modal-lg">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                    <h4 class="modal-title"><span id="titulo_modal_responsables" class="translate" data-traducir_english="Apply Chemical to Planting" data-traducir_spanish="Aplicar Químico a Plantación">Aplicar Químico a Plantación</span> <small class="subtitulo" id="subtitulo_modal_responsables"></small></h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-xs-12">

                            <h3 class="translate" data-traducir_english="For Operator" data-traducir_spanish="Para Operador">Para Operador</h3>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="cod_tipo_aplicacion" class="translate" data-traducir_english="Application Type" data-traducir_spanish="Tipo Aplicación">Tipo Aplicación</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_aplicacion_operador" id="cod_tipo_aplicacion" name="cod_tipo_aplicacion" data-live-search="true" title="Select">

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="cod_maquinaria" class="translate" data-traducir_english="Machinery" data-traducir_spanish="Maquinaria">Maquinaria</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_aplicacion_operador" id="cod_maquinaria" name="cod_maquinaria" data-live-search="true" title="Select">

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="fecha_aplicacion_operador" class="translate" data-traducir_english="Applied chemical date" data-traducir_spanish="Fecha aplicada">Fecha aplicada</label>

                            <div class='input-group input-group-sm fecha-planeada date' id='datetimepicker1'>

                                <span class="input-group-addon">

                                    <span class="fa fa-calendar"></span>

                                </span>

                                <input type='text' class="form-control input requerido_aplicacion_operador" id="fecha_aplicacion_operador" />

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="hora_inicial" class="translate" data-traducir_english="Start time" data-traducir_spanish="Hora inicio">Hora inicio</label>

                            <div class='input-group input-group-sm time' id='datetimepicker1'>

                                <span class="input-group-addon">

                                    <span class="fa fa-calendar"></span>

                                </span>

                                <input type='text' class="form-control input requerido_aplicacion_operador" id="hora_inicial" />

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="hora_final" class="translate" data-traducir_english="End time" data-traducir_spanish="Hora final">Hora final</label>

                            <div class='input-group input-group-sm time' id='datetimepicker1'>

                                <span class="input-group-addon">

                                    <span class="fa fa-calendar"></span>

                                </span>

                                <input type='text' class="form-control input requerido_aplicacion_operador" id="hora_final" />

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="viento" class="translate" data-traducir_english="Wind (Mi)" data-traducir_spanish="Viento (Mi)">Viento (Mi)</label>

                                <input type="text"class="form-control monto input requerido_aplicacion_operador" id="viento" name="viento">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="temperatura" class="translate" data-traducir_english="Temperature (°F)" data-traducir_spanish="Temperatura (°F)">Temperatura (ºF)</label>

                                <input type="text"class="form-control monto input requerido_aplicacion_operador" id="temperatura" name="temperatura">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="descripcion_aplicar_quimico" class="translate" data-traducir_english="Description" data-traducir_spanish="Descripción">Descripción</label>

                            <div class="form-group show-tick">

                                <textarea maxlength="2500" align="left" style="height:100px; width:100%; resize: none;" class="form-control limpiar" rows="2" id="descripcion_aplicar_quimico" style="width: 100%"></textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>

                    <button type="button" class="btn btn-sm btn-primary btn_guardar translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_guardar_aplicacion">Guardar</button>

                </div>

            </div>

        </div>

    </div>



    <div class="modal fade modal_trasplantar" id="modal_trasplantar" tabindex="-1" role="dialog" aria-labelledby="ModalResponsables" aria-hidden="true">

        <div class="modal-dialog modal-success modal-md">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                    <h4 class="modal-title"><span id="titulo_modal_responsables" class="translate" data-traducir_english="Trasplant" data-traducir_spanish="Trasplantar">Trasplantar</span> <small class="subtitulo" id="subtitulo_modal_responsables"></small></h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="cod_plantacion" class="translate" data-traducir_english="Planting" data-traducir_spanish="Plantación">Plantación</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_trasplantar" id="cod_plantacion" name="cod_plantacion" data-live-search="true" title="Select">

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="cod_bloques_plantacion2" class="translate" data-traducir_english="Blocks sent" data-traducir_spanish="Bloques enviados">Bloques enviados</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_trasplantar" multiple="multiple" data-actions-box="true" title="Select" id="cod_bloques_plantacion2" name="cod_bloques_plantacion2">

                                </select>

                                <button class="btn btn-info btn-all-stick check-all has-tooltip" id="todos_bloques2" title="Seleccionar todos" data-toggle="tooltip" type="button">

                                    <i class="fa fa-check"></i>

                                </button>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="cantidad" class="translate" data-traducir_english="Amount (Pounds)" data-traducir_spanish="Cantidad (Lb)">Cantidad (Lb)</label>

                                <input type="text"class="form-control monto input requerido_trasplantar" id="cantidad" name="cantidad">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="cod_bloques_trasplante" class="translate" data-traducir_english="Blocks received" data-traducir_spanish="Bloques recibidos">Bloques recibidos</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_trasplantar" multiple="multiple" data-actions-box="true" title="Select" id="cod_bloques_trasplante" name="cod_bloques_trasplante">

                                </select>

                                <button class="btn btn-info btn-all-stick check-all has-tooltip" id="todos_bloques" title="Seleccionar todos" data-toggle="tooltip" type="button">

                                    <i class="fa fa-check"></i>

                                </button>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="fecha_checklist" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</label>

                            <div class='input-group input-group-sm fecha-planeada date' id='datetimepicker1'>

                                <span class="input-group-addon">

                                    <span class="fa fa-calendar"></span>

                                </span>

                                <input type='text' class="form-control input requerido_trasplantar" id="fecha_trasplante" />

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="cantidad" class="translate" data-traducir_english="Load Number" data-traducir_spanish="No. Carga">No. Carga</label>

                                <input type="text"class="form-control numero_carga input requerido_trasplantar" id="numero_carga" name="numero_carga">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="observacion_trasplantar" class="translate" data-traducir_english="Observation" data-traducir_spanish="Observación">Observación</label>

                            <div class="form-group show-tick">

                                <textarea maxlength="2500" align="left" style="height:100px; width:100%; resize: none;" class="form-control limpiar" rows="2" id="observacion_trasplantar" style="width: 100%"></textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>

                    <button type="button" class="btn btn-sm btn-primary btn_guardar translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_guardar_trasplantar">Trasplantar</button>

                </div>

            </div>

        </div>

    </div>



    <div class="panel-footer-actions text-right smooth-transition actions-container-is-closed" id="panel_footer_actions">

        <div class="close-actions-container">

            <button class="btn btn-xs btn-close-actions" id="btn_close_actions">

                <i class="fa fa-chevron-circle-down fa-lg"></i>

            </button>

        </div>

        <div class="row actions">

            <!-- <div class="col-xs-9 col-md-3 nopadding text-left smooth-transition" id="container_back_button">

                <button data-loading-text="Volviendo" class="btn btn-sm btn-primary btn-fullwidth main-actions smooth-transition" type="button" id="btn_ir_al_listado" name="btn_ir_al_listado">

                    <i class="fa fa-arrow-left"> </i> <span id="btn_regresar_listado" class="translate" data-traducir_english="Back to list" data-traducir_spanish="Regresar al listado">Regresar al listado</span>

                </button>

            </div> -->



            <div class="col-xs-12 col-md-12 nopadding smooth-transition" id="div_acciones" style="text-align: center;">

                <button class="btn btn-sm btn-primary btn-open-actions main-actions smooth-transition" type="button" id="btn_open_actions" name="btn_open_actions">

                    <i class="fa fa-ellipsis-v"></i>

                </button>

                <div class="col-md-12" align="right" style="display: flex;">

                    <?php 

                    switch ($PLANTACION[0]['cod_estado']) {

                        case 2:

                            ?>

                            <!-- <a title="Apply Chemical - Aplicar Quimico" onclick="cod_aplicacion_quimico = 0;codigo_detalle = 0;$('#modal_aplicar_quimico').modal('show');" id="btn_aplicar_quimico_plantacion" class="btn btn-eliminar btn_administrador btn_operador btn-left btn-sm btn-primary"><i class="fa fa-flask"></i></a> -->

                            <a title="Cleaning and Sanitizing of harvesting equipment" onclick="acres_plantados = 0;codigo_detalle = 0;$('#modal_cleaning_sanitizing').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-left btn-sm btn-warning" style="width: 20%!important;"><i class="fa fa-snowplow"></i></a>

                            <a title="Scouting - Explorar" onclick="acres_plantados = 0;codigo_detalle = 0;$('#modal_explorar_plantacion').modal('show');" id="btn_explorar_plantacion" class="btn btn-eliminar btn-center-left btn_administrador btn_explorador btn-sm btn-success"><i class="fa fa-leaf"></i></a>

                            <a title="Calibrate - Calibrar" onclick="acres_plantados = 0;codigo_detalle = 0;$('#modal_calibrar').modal('show');" id="btn_calibrar_plantacion" class="btn btn-eliminar btn_administrador btn_explorador btn_supervisor btn-center btn-sm btn-primary"><i class="fa fa-adjust"></i></a>

                            <a title="Transplant - Trasplantar" onclick="$('#modal_trasplantar').modal('show');" class="btn btn-eliminar btn_administrador btn_supervisor btn_explorador btn-center btn-sm btn-default"><i class="fas fa-spa"></i></a>

                            <a title="Delete All - Eliminar Todo" id="btn_eliminar_todo" class="btn btn-eliminar btn_administrador btn_explorador btn_supervisor btn_operador btn-center-right btn-sm btn-danger"><i class="fa fa-trash-alt"></i></a></td"></i></a>

                            <a title="Sync up - Sincronizar" id="btn_sincronizar_todo" class="btn btn-eliminar btn_administrador btn_explorador btn_supervisor btn_operador btn-right btn-sm btn-default"><i class="fas fa-sync-alt"></i></a>

                            <?php

                            break;

                        case 1:

                            ?>

                            <!-- <a title="Apply Chemical - Aplicar Quimico" onclick="cod_aplicacion_quimico = 0;codigo_detalle = 0;$('#modal_aplicar_quimico').modal('show');" id="btn_aplicar_quimico_plantacion" class="btn btn-eliminar btn_administrador btn_operador btn-left btn-sm btn-primary"><i class="fa fa-flask"></i></a> -->

                            <a title="Cleaning and Sanitizing of harvesting equipment" onclick="codigo_detalle = 0;$('#modal_cleaning_sanitizing').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-left btn-sm btn-warning" style="width: 20%!important;"><i class="fa fa-snowplow"></i></a>

                            <a title="Scouting - Explorar" onclick="acres_plantados = 0;codigo_detalle = 0;$('#modal_explorar_plantacion').modal('show');" id="btn_explorar_plantacion" class="btn btn-eliminar btn-center-left btn_administrador btn_explorador btn-sm btn-success"><i class="fa fa-leaf"></i></a>

                            <a title="Calibrate - Calibrar" onclick="acres_plantados = 0;codigo_detalle = 0;$('#modal_calibrar').modal('show');" id="btn_calibrar_plantacion" class="btn btn-eliminar btn_administrador btn_explorador btn_supervisor btn-center btn-sm btn-primary"><i class="fa fa-adjust"></i></a>

                            <a title="Transplant - Trasplantar" onclick="$('#modal_trasplantar').modal('show');" class="btn btn-eliminar btn_administrador btn_supervisor btn_explorador btn-center btn-sm btn-default"><i class="fas fa-spa"></i></a>

                            <a title="Delete All - Eliminar Todo" id="btn_eliminar_todo" class="btn btn-eliminar btn_administrador btn_explorador btn_supervisor btn_operador btn-center-right btn-sm btn-danger"><i class="fa fa-trash-alt"></i></a></td"></i></a>

                            <a title="Sync up - Sincronizar" id="btn_sincronizar_todo" class="btn btn-eliminar btn_administrador btn_explorador btn_supervisor btn_operador btn-right btn-sm btn-default"><i class="fas fa-sync-alt"></i></a>

                            <?php

                            break;

                        case 3:

                        case 4:

                            ?>

                            <!-- <a title="Apply Chemical - Aplicar Quimico" onclick="cod_aplicacion_quimico = 0;codigo_detalle = 0;$('#modal_aplicar_quimico').modal('show');" id="btn_aplicar_quimico_plantacion" class="btn btn-eliminar btn_administrador btn_operador btn-left btn-sm btn-primary"><i class="fa fa-flask"></i></a> -->

                            <a title="Cleaning and Sanitizing of harvesting equipment" onclick="codigo_detalle = 0;$('#modal_cleaning_sanitizing').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-left btn-sm btn-warning" style="width: 20%!important;"><i class="fa fa-snowplow"></i></a>

                            <a title="Scouting - Explorar" onclick="acres_plantados = 0;codigo_detalle = 0;$('#modal_explorar_plantacion').modal('show');" id="btn_explorar_plantacion" class="btn btn-eliminar btn-center-left btn_administrador btn_explorador btn-sm btn-success"><i class="fa fa-leaf"></i></a>

                            <a title="Calibrate - Calibrar" onclick="acres_plantados = 0;codigo_detalle = 0;$('#modal_calibrar').modal('show');" id="btn_calibrar_plantacion" class="btn btn-eliminar btn_administrador btn_explorador btn_supervisor btn-center btn-sm btn-primary"><i class="fa fa-adjust"></i></a>

                            <a title="Transplant - Trasplantar" onclick="$('#modal_trasplantar').modal('show');" class="btn btn-eliminar btn_administrador btn_supervisor btn_explorador btn-center btn-sm btn-default"><i class="fas fa-spa"></i></a>

                            <a title="Delete All - Eliminar Todo" id="btn_eliminar_todo" class="btn btn-eliminar btn_administrador btn_explorador btn_supervisor btn_operador btn-center-right btn-sm btn-danger"><i class="fa fa-trash-alt"></i></a></td"></i></a>

                            <a title="Sync up - Sincronizar" id="btn_sincronizar_todo" class="btn btn-eliminar btn_administrador btn_explorador btn_supervisor btn_operador btn-right btn-sm btn-default"><i class="fas fa-sync-alt"></i></a>

                            <?php

                            break;

                        case 6: //CRECIMIENTO

                            ?>

                            <a title="Cleaning and Sanitizing of harvesting equipment" onclick="codigo_detalle = 0;$('#modal_cleaning_sanitizing').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-left btn-sm btn-warning" style="width: 20%!important;"><i class="fa fa-snowplow"></i></a>

                            <a title="Harvesting Worksheet" onclick="codigo_detalle = 0;$('#modal_harvesting_worksheet').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-center-left btn-sm btn-default" style="width: 20%!important;"><i class="fab fa-pagelines"></i></a>

                            <a title="Calibrate - Calibrar" onclick="acres_plantados = 0;codigo_detalle = 0;$('#modal_calibrar').modal('show');" id="btn_calibrar_plantacion" class="btn btn-eliminar btn_administrador btn_explorador btn_supervisor btn-center btn-sm btn-primary"><i class="fa fa-adjust"></i></a>

                            <a title="Transplant - Trasplantar" onclick="$('#modal_trasplantar').modal('show');" class="btn btn-eliminar btn_administrador btn_supervisor btn_explorador btn-center btn-sm btn-default"><i class="fas fa-spa"></i></a>

                            <a title="Delete All - Eliminar Todo" id="btn_eliminar_todo" class="btn btn-eliminar btn_administrador btn_explorador btn_supervisor btn_operador btn-center-right btn-sm btn-danger"><i class="fa fa-trash-alt"></i></a></td"></i></a>

                            <a title="Sync up - Sincronizar" id="btn_sincronizar_todo" class="btn btn-eliminar btn_administrador btn_explorador btn_supervisor btn_operador btn-right btn-sm btn-default"><i class="fas fa-sync-alt"></i></a>

                            <?php

                            break;

                        case 5: //LISTO PARA COSECHAR

                            ?>

                            <a title="Pre-Operational/Harvesting Checklist" onclick="codigo_detalle = 0;$('#modal_harvesting_checklist').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-left btn-sm btn-info" style="width: 20%!important;"><i class="fa fa-list-ol"></i></a>

                            <a title="Cleaning and Sanitizing of harvesting equipment" onclick="codigo_detalle = 0;$('#modal_cleaning_sanitizing').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-center-left btn-sm btn-warning" style="width: 20%!important;"><i class="fa fa-snowplow"></i></a>

                            <a title="Calibrate - Calibrar" onclick="acres_plantados = 0;codigo_detalle = 0;$('#modal_calibrar').modal('show');" id="btn_calibrar_plantacion" class="btn btn-eliminar btn_administrador btn_operador btn_supervisor btn-center btn-sm btn-primary"><i class="fa fa-adjust"></i></a>

                            <a title="Transplant - Trasplantar" onclick="$('#modal_trasplantar').modal('show');" class="btn btn-eliminar btn_administrador btn_supervisor btn_explorador btn-center btn-sm btn-default"><i class="fas fa-spa"></i></a>

                            <a title="Delete All - Eliminar Todo" id="btn_eliminar_todo" class="btn btn-eliminar btn_administrador btn_explorador btn_supervisor btn_operador btn-center-right btn-sm btn-danger"><i class="fa fa-trash-alt"></i></a></td"></i></a>

                            <a title="Sync up - Sincronizar" id="btn_sincronizar_todo" class="btn btn-eliminar btn_administrador btn_explorador btn_supervisor btn_operador btn-right btn-sm btn-default"><i class="fas fa-sync-alt"></i></a>

                            <?php

                            break;

                        default:

                            break;

                    }

                    ?>

                </div>

            </div>

        </div> <!-- panel-footer -->

    </div>

</body>