<?php

/*

 *     Registro de información de los formularios

 *     @author         Jairo Bonilla

 *     @date             2015-07-28

 */

session_start();

if (!isset($_SESSION['cod_usuario'])) {

    header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/

include_once "../../libs/db_classes/db_mysql_conn.php";

include_once "../../libs/db_classes/db_plantaciones.php";

include_once("../../libs/db_classes/db_configuracion.php");

/*INSTANCIAMIENTOS*/

$DB_PLANT   = new db_plantaciones();

$DB_CONG    = new db_configuracion();



$cod_plantacion = $_POST['cod_plantacion'];

if (!isset($_POST['cod_plantacion'])) {

    $cod_plantacion = 0;
}

$PLANTACION     = (array) $DB_PLANT->plan_obtener_info_plantacion($cod_plantacion);

$BLOQUES        = (array) $DB_PLANT->plan_listado_bloques_plantacion($cod_plantacion);

$PLANTADOS      = (array) $DB_PLANT->plan_listado_bloques_plantados_plantacion($cod_plantacion);

$OBSERVACIONES  = (array) $DB_PLANT->plan_listado_observaciones_plantacion($cod_plantacion);

$APLICACIONES   = (array) $DB_PLANT->plan_listado_aplicaciones_quimicos_plantacion($cod_plantacion);

$CALIBRACIONES  = (array) $DB_PLANT->plan_listado_calibracion_plantacion($cod_plantacion);

$CAMBIOS        = (array) $DB_PLANT->plan_listado_cambios_plantacion($cod_plantacion);

$EXPLORACIONES  = (array) $DB_PLANT->plan_listado_exploraciones_plantacion($cod_plantacion);

$FORMULARIOS    = (array) $DB_PLANT->plan_listado_formularios_respuestas_plantacion($cod_plantacion);

$LIMPIEZAS      = (array) $DB_PLANT->plan_listado_formularios_limpieza_plantacion($cod_plantacion);

$WORKSHEETS     = (array) $DB_PLANT->plan_listado_formularios_harvesting_worksheet_plantacion($cod_plantacion);

$CHECKLISTS     = (array) $DB_PLANT->plan_listado_formularios_harvesting_checklist_plantacion($cod_plantacion);

$TRASPLANTES    = (array) $DB_PLANT->plan_listado_trasplantes_plantaciones($cod_plantacion);

$REPORTE_CARGA  = (array) $DB_PLANT->plan_listado_reporte_carga_plantacion($cod_plantacion);

$ZONAS_ROCIADAS = (array) $DB_PLANT->plan_listado_zonas_rociado_plantacion($cod_plantacion);



$fertilizantes = [
    '',

    'ON',

    'OFF',

    'Only Water - Sólo Agua'
];

$etapas = [
    '',

    'Stubble',

    'Leaf Up Stubbles',

    'High Cress'
];

$loose_bunches = [
    'Bunch',

    'Loose',

    'Unused',

    'Pounds'
];

$conventional_organic = [
    'Organic',

    'Conventional'
];

$question = [
    'No',

    'Yes',

    'NA'
];

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <title>Nueva capacitación</title>

</head>

<style type="text/css">
    .table {

        font-size: 14px;

    }

    /* Loader */

    .loader {

        border: 16px solid #f3f3f3;
        /* Light grey */

        border-top: 16px solid #3498db;
        /* Blue */

        border-radius: 50%;

        width: 100px;

        height: 100px;

        animation: spin 1s linear infinite;

        margin: auto;

    }



    @keyframes spin {

        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }

    }



    .row {

        padding: 0 10px;

    }

    .no-margin {

        margin: 0px;

    }

    .row-timeline {

        margin-bottom: 15px;

        margin-right: 0px;

        /*padding-left: 15px;*/

        margin-left: 0;

    }

    .bubble-timeline {

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

    .bubble-timeline-0 {

        display: inline-block;

        background-color: rgb(77, 178, 208);

        border-radius: 50%;

        line-height: 2em;

        min-width: 25px;

        /*border: 2px solid rgb(255, 255, 255);*/

        padding: 2px;

        margin-bottom: 15px;

    }

    .bubble-timeline-1 {

        display: inline-block;

        background-color: rgb(90, 208, 77);

        border-radius: 50%;

        line-height: 2em;

        min-width: 25px;

        /*border: 2px solid rgb(255, 255, 255);*/

        padding: 2px;

        margin-bottom: 15px;

    }

    .sin-margen-abajo {

        margin-bottom: -20px;

        padding-top: 5px;

    }

    .icon-timeline-container {

        background-color: rgb(77, 178, 208);

        border: 3px solid rgb(255, 255, 255);

        border-radius: 50%;

        display: inline-block;

    }

    .icon-timeline {

        padding: 10px;

        font-size: 1.5em;

        line-height: 1em;

        color: rgb(255, 255, 255);

    }

    .container-post-timeline {

        border: 1px solid rgb(45, 131, 156);

        border-radius: 6px;

        padding: 10px;

        min-height: 65px;

    }

    .container-post-timeline:after,
    .container-post-timeline:before {

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

        border-right-color: rgb(255, 255, 255);

        border-width: 10px;

        margin-top: -10px;

    }

    .container-post-timeline:before {

        border-color: rgba(176, 211, 85, 0);

        border-right-color: rgb(45, 131, 156);

        border-width: 11px;

        margin-top: -11px;

    }

    @media (max-width: 768px) {

        .text-center-for-sall-only {

            text-align: center;

        }

        .extra-data-timeline {

            margin-top: 5px;

        }

    }

    .texto-timeline {

        font-weight: bold;

    }

    .texto-justificado {

        text-align: justify;

    }

    .fecha-timeline {

        font-size: 12px;

        color: rgb(80, 80, 80);

    }

    .text-autor-timeline {

        font-size: 12px;

    }

    .autor-timeline {

        color: rgb(30, 150, 185);

    }

    .nivel {

        border-radius: 4px;

        border-right: 3px solid rgb(120, 120, 120);

    }



    .nivel-inverso {

        border-radius: 4px;

        border-left: 3px solid rgb(120, 120, 120);

    }

    .autor-timeline-1 {

        border: 1px solid rgb(161, 160, 159);

        background-color: rgb(161, 160, 159);

        border-radius: 5px;

        color: white;

        font-weight: bold;

        padding: 2px;

    }

    .autor-timeline-2 {

        border: 1px solid rgb(79, 185, 216);

        background-color: rgb(79, 185, 216);

        border-radius: 5px;

        color: white;

        font-weight: bold;

        padding: 2px;

    }

    .autor-timeline-3 {

        border: 1px solid rgb(204, 165, 27);

        background-color: rgb(204, 165, 27);

        border-radius: 5px;

        color: white;

        font-weight: bold;

        padding: 2px;

    }

    .autor-timeline-4 {

        border: 1px solid rgb(170, 50, 105);

        background-color: rgb(170, 50, 105);

        border-radius: 5px;

        color: white;

        font-weight: bold;

        padding: 2px;

    }

    .autor-timeline-5 {

        border: 1px solid rgb(51, 122, 183);

        background-color: rgb(51, 122, 183);

        border-radius: 5px;

        color: white;

        font-weight: bold;

        padding: 2px;

    }

    .autor-timeline-6 {

        border: 1px solid rgb(134, 184, 114);

        background-color: rgb(134, 184, 114);

        border-radius: 5px;

        color: white;

        font-weight: bold;

        padding: 2px;

    }

    .nivel-1 {

        border-right-color: rgb(161, 160, 159);

    }

    .nivel-2 {

        border-right-color: rgb(79, 185, 216);

    }

    .nivel-3 {

        border-right-color: rgb(204, 165, 27);

    }

    .nivel-4 {

        border-right-color: rgb(170, 50, 105);

    }

    .nivel-5 {

        border-right-color: rgb(51, 122, 183);

    }

    .nivel-6 {

        border-right-color: rgb(134, 184, 114);

    }



    .nivel-1-inverso {

        border-left-color: rgb(161, 160, 159);

    }

    .nivel-2-inverso {

        border-left-color: rgb(79, 185, 216);

    }

    .nivel-3-inverso {

        border-left-color: rgb(204, 165, 27);

    }

    .nivel-4-inverso {

        border-left-color: rgb(170, 50, 105);

    }

    .nivel-5-inverso {

        border-left-color: rgb(51, 122, 183);

    }

    .nivel-6-inverso {

        border-left-color: rgb(134, 184, 114);

    }

    .label-nombre-1 {

        background-color: rgb(161, 160, 159) !important;

        border: rgb(161, 160, 159) !important;

    }

    .label-nombre-2 {

        background-color: rgb(79, 185, 216) !important;

        border: rgbrgb(79, 185, 216) !important;

    }

    .label-nombre-3 {

        background-color: rgb(204, 165, 27) !important;

        border: rgb(204, 165, 27) !important;

    }

    .label-nombre-4 {

        background-color: rgb(170, 50, 105) !important;

        border: rgb(170, 50, 105) !important;

    }

    .label-nombre-5 {

        background-color: rgb(51, 122, 183) !important;

        border: rgb(51, 122, 183) !important;

    }

    .label-nombre-6 {

        background-color: rgb(134, 184, 114) !important;

        border: rgb(134, 184, 114) !important;

    }

    .img-timeline {

        width: 100%;

        /*background-color: rgb(77, 178, 208);

        border: 3px solid rgb(255, 255, 255);*/

        border-radius: 50%;

        display: inline-block;

        height: 50px;

    }

    .img-timeline-2 {

        width: 25px;

        /*background-color: rgb(77, 178, 208);

        border: 3px solid rgb(255, 255, 255);*/

        border-radius: 50%;

        display: inline-block;

    }

    .label-estado {

        border-radius: 0.25em !important;

    }

    .label-estado-1 {

        background-color: #F9F19A;

        color: black;

    }

    .label-estado-2 {

        background-color: #EDCCE3;

        color: black;

    }

    .label-estado-3 {

        background-color: #FBCB9A;

        color: black;

    }

    .label-estado-4 {

        background-color: #A6D4BA;

        color: black;

    }

    .label-estado-5 {

        background-color: #F4EDCA;

        color: black;

    }

    .label-estado-6 {

        background-color: #dff0d8;

        color: black;

    }

    .label-01 {

        background-color: #5cb85c;

        margin-left: 5px;

    }

    .label-02 {

        background-color: #337ab7;

        margin-left: 5px;

    }

    .label-nivel-1 {

        background-color: rgb(161, 160, 159);

    }

    .label-nivel-2 {

        background-color: rgb(79, 185, 216);

    }

    .label-nivel-3 {

        background-color: rgb(204, 165, 27);

    }

    .label-nivel-4 {

        background-color: rgb(170, 50, 105);

    }

    .label-nivel-5 {

        background-color: rgb(51, 122, 183);

    }

    .label-nivel-6 {

        background-color: rgb(134, 184, 114);

    }

    .label.label-info {

        font-size: 11px;

        display: inline;

        border-radius: 0;

        margin: 2px;

    }



    .row-label {

        margin-bottom: 5px;

    }



    .container-post-timeline-inverso {

        border: 1px solid rgb(45, 131, 156);

        border-radius: 6px;

        padding: 10px;

        min-height: 65px;

    }

    .container-post-timeline-inverso:after,
    .container-post-timeline-inverso:before {

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

        border-left-color: rgb(255, 255, 255);

        border-width: 10px;

        margin-top: -10px;

    }

    .container-post-timeline-inverso:before {

        border-color: rgba(176, 211, 85, 0);

        border-left-color: rgb(45, 131, 156);

        border-width: 11px;

        margin-top: -11px;

    }



    @media (max-width: 768px) {

        .label-estado {

            border-radius: 0px !important;

        }

        .img-timeline {

            width: 75px;

        }

        .container-post-timeline-inverso:after,
        .container-post-timeline-inverso:before {

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

            border-bottom-color: rgb(255, 255, 255);

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

        .container-post-timeline:after,
        .container-post-timeline:before {

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

            border-bottom-color: rgb(255, 255, 255);

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

        .icon-timeline-container {

            width: 100%;

            background-color: rgb(77, 178, 208);

            border: 3px solid rgb(255, 255, 255);

            border-radius: 50%;

            display: inline-block
        }

        .icon-timeline-container-2 {

            background-color: rgb(77, 178, 208);

            border: 3px solid rgb(255, 255, 255);

            border-radius: 50%;

            display: inline-block
        }

        .col-xs-8.col-sm-10.col-md-10.text-left.container-post-timeline-inverso {

            width: 100% !important;

            -webkit-box-ordinal-group: 3;

            -moz-box-ordinal-group: 3;

            box-ordinal-group: 3;

        }

        .col-xs-8.col-sm-10.col-md-10.text-left.container-post-timeline {

            width: 100% !important;

            -webkit-box-ordinal-group: 3;

            -moz-box-ordinal-group: 3;

            box-ordinal-group: 3;

        }

        .row-timeline {

            width: 100%;

            display: -webkit-box;

            display: -moz-box;

            display: box;



            -webkit-box-orient: vertical;

            -moz-box-orient: vertical;

            box-orient: vertical;

        }

        .label.label-info {

            display: block;

        }

        .label-bordes-abajo {

            border-radius: 0 0 5px 5px !important;

        }

        .label-bordes-arriba {

            border-radius: 5px 5px 0 0 !important;

        }

        .textarea_observacion {

            resize: none;

        }

        .texto-timeline {

            /*background: rgb(240, 240, 240) none repeat scroll 0 0;

            border: 1px solid rgb(210, 210, 210);

            border-radius: 3px;

            padding: 5px 10px;

            display: inline-block;*/

        }

        .fa-clock {

            color: #121212 !important;

        }







    }
</style>

<script type="text/javascript">
    jQuery.ajaxSetup({
        async: false
    });

    $('#modal_loading').modal('hide');

    grl_overlay_loading('');

    var array_file_list = [];

    array_file_list.push({
        id: 0,
        file: '',
        file_ext: ''
    });

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



    function constructor_file_input($container_upload_box) {

        $container_upload_box.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file');

        $container_upload_box.find('.btn-delete-file').removeClass().addClass('smooth-transition btn-delete-file ready-to-upload');

        $container_upload_box.find('.btn-delete-file').data('disabled', false);

        $container_upload_box.find('i.icon-file').removeClass().addClass('icon-file fa fa-file');

        $container_upload_box.find('span.msj-btn-file').text(' Select archivo');

        $container_upload_box.find('.btn-view-file').addClass('hide');

        $container_upload_box.find("input[type=file]").replaceWith(

            $container_upload_box.find("input[type=file]").val('').clone(true));

        $container_upload_box.find("input[type=file]").attr('title', 'Select un Archivo');

        files = '';

        file_ext = '';

        var id = $container_upload_box.find('.btn-select-file').data('id');

        update_array_file_list(id, files, file_ext);

    }



    function prepare_upload2(event)

    {

        var $container_upload_box = $(this).parent();

        files_form = event.target.files;

        var cancel_button_is_clicked = files_form[0];

        if (cancel_button_is_clicked == undefined) {

            constructor_file_input($(this).parent());

        } else

        {

            var id = $container_upload_box.find('.btn-select-file').data('id');

            var filesize = files_form[0].size / 1024 / 1024;

            if (filesize > 10) {

                grl_mensaje('File size not allowed. Only files smaller than 10 MB are allowed.', 'Tamaño de archivo no permitido. Solo se permiten archivos menores de 10 MB. ', 'warning');

                cambio_adjunto = false;

                constructor_file_input($container_upload_box);

            } else

            {

                ext_adjunto = $(this).val().match(/\.([^\.]+)$/)[1];

                ext_adjunto = ext_adjunto.toLowerCase();

                update_array_file_list(id, files_form, ext_adjunto);

                switch (ext_adjunto)

                {

                    case 'jpg':

                    case 'jpeg':

                    case 'bmp':

                    case 'png':

                    case 'tif':

                    case 'tiff':

                    case 'svg':

                    case 'pdf':

                    case 'docx':

                    case 'doc':

                    case 'xls':

                    case 'xlsx':

                        $container_upload_box.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file ready-to-upload');

                        $container_upload_box.find('.btn-upload-file').removeClass().addClass('smooth-transition btn-upload-file ready-to-upload');

                        $container_upload_box.find('.btn-upload-file').data('disabled', true);

                        $container_upload_box.find('i.icon-file').removeClass().addClass('icon-file fa fa-check');

                        $container_upload_box.find('span.msj-btn-file').text('Archivo listo para subir');

                        $container_upload_box.find('.btn-view-file').remove();

                        $container_upload_box.find(".btn-view-file-delete").parent('div').remove();

                        break;

                    default: {

                        grl_mensaje('File type not allowed, only images are allowed.', 'Tipo de archivo no permitido, solo se permiten imágenes.', 'warning');

                        constructor_file_input($(this).parent());

                    }

                }

            }

        }

    }



    function update_list_item_interface($li, nombre_archivo) {

        $li.unbind('click');

        if (nombre_archivo != '') {

            $li.addClass('row-with-attachment');

            $li.find("span.state-icon").addClass('fa-paperclip').removeClass('fa-check-square-o');

            $li.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file with-attachment').data('disabled', false);

            $li.find('i.icon-file').addClass('hide');

            $li.find('span.msj-btn-file').text(nombre_archivo);

            $li.find(".btn-upload-file").addClass('hide').data('disabled', true);

            $li.find(".container-upload-box").append('<a tabindex="0" target="_blank" href="/mod_usuarios/foto_usuarios/' + nombre_archivo + '" class="smooth-transition btn-view-file">' +

                '<i class="fa fa-eye"></i>' +

                '</a>');

        }

    }



    function update_array_file_list(id, file, file_ext) {

        //alert(id + file_ext);

        for (var i in array_file_list) {

            if (array_file_list[i].id == id) {

                array_file_list[i].file = file;

                array_file_list[i].file_ext = file_ext;

                break;

            }

        }

    }



    // Evento al seleccionar un archivo

    $('.input-file-hidden').on('change', prepare_upload2);

    /* Clic botón seleccionar archivo */

    $(".btn-select-file").on('click', function(e) {

        e.stopPropagation();

        $(this).parent().find('input[type=file]').click();

        return false;

    });

    /* Clic botón subir archivo */

    $('.btn-upload-file').on('click', function(e) {

        e.stopPropagation();

        if ($(this).data('disabled') == false) {

            var $container_upload_box = $(this).parent();

            var id = $(this).data('id');

            var $li = $container_upload_box.closest("li.list-group-item");

            // Busca id dentro del objeto [array_file_list]

            var temp_array = array_file_list.filter(function(attr) {

                return attr.id == id;

            });

            $li.unbind('click');

        }

    });



    /* Disparador input seleccionador de archivo */

    $('input[type=file]').on('click', function(e) {

        e.stopPropagation();

    });

    var fecha_hoy = new Date(<?php echo time() * 1000; ?>);

    var hoy = (fecha_hoy.getMonth() + 1) + "/" + fecha_hoy.getDate() + "/" + fecha_hoy.getFullYear();

    //var hoy_18_anios_atras        = (fecha_hoy.getMonth()+1) + "/" + fecha_hoy.getDate() + "/" + (fecha_hoy.getFullYear()-18);

    var hoy_default = fecha_hoy.getFullYear() + "-" + (fecha_hoy.getMonth() + 1) + "-" + fecha_hoy.getDate();

    var estados = ['Pre-Planting',

        'Pre-Planting',

        'Planted',

        'Trimmed',

        'Growing',

        'Ready to harvest',

        'Harvested',

        'QA',

        'Packaged product',

        'Removed',

        'Finished'
    ]





    //Construcción de fechas

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



    $('.datetime').datetimepicker({

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

    }).on('dp.hide', function(e) {

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

    }).on('dp.hide', function(e) {

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

    }).on('dp.hide', function(e) {

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

    }).on('dp.hide', function(e) {

        $('#div_time_vacuum_cooler').data("DateTimePicker").maxDate(e.date);

    });

    /*

     * Función que realiza la "busqueda" dentro de la tabla con información

     */

    (function() {

        'use strict';

        var $ = jQuery;

        $.fn.extend({

            filterTable: function() {

                return this.each(function() {

                    $(this).on('keyup', function(e) {

                        $('.filterTable_no_results').remove();

                        var $this = $(this),
                            search = $this.val().toLowerCase(),
                            target = $this.attr('data-filters'),
                            $target = $(target),
                            $rows = $target.find('tbody tr');

                        if (search == '') {

                            $rows.show();

                        } else {

                            $rows.each(function() {

                                var $this = $(this);

                                $this.text().toLowerCase().indexOf(search) === -1 ? $this.hide() : $this.show();

                            })

                            if ($target.find('tbody tr:visible').size() === 0) {

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



    $(function() {

        // attach table filter plugin to inputs

        $('[data-action="filter"]').filterTable();



        $('.busqueda_contenedor').on('click', '.panel_cabecera span.filter', function(e) {

            var $this = $(this),

                $panel = $this.parents('.panel');



            $panel.find('.panel_cuerpo').slideToggle();

            if ($this.css('display') != 'none') {

                $panel.find('.panel_cuerpo input').focus();

            }

        });

        $('[data-toggle="tooltip"]').tooltip();

    });

    $(document).ready(function() {

        jQuery.ajaxSetup({
            async: false
        });

        //Inicializar la barra de botones

        init_button_bar();

        //Constructores

        conf_constructor_listado_granjas();



        //conf_cargar_formularios_por_estado('div_formularios', codigo_estado_plantacion);





        //bw_constructor_bloques_plantacion_todos('cod_bloques_cleaning', codigo_plantacion);

        //bw_constructor_bloques_plantacion_todos('cod_bloques_harvesting_checklist', codigo_plantacion);

        //bw_constructor_bloques_plantacion_todos('cod_bloques_harvesting_worksheet', codigo_plantacion);





        //inv_constructor_listado_maquinarias_estado('cod_inventario_maquinaria',$('#cod_info_empresa').val(), codigo_estado_plantacion);





        $('.selectpicker').selectpicker({

            dropupAuto: 'true',

            container: 'body',

            size: '5',

            width: '100%',

            style: 'btn-sm btn-info',

            tickIcon: 'fa fa-check'

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

        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {

            $('.selectpicker').selectpicker('mobile');

        }

        $('.selectpicker').selectpicker('refresh');

        $('.letras').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {
            translation: {
                'S': {
                    pattern: /[A-Za-z-¡!¿?,.() 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/,
                    optional: false
                }
            }
        });

        $('.letras10').mask('SSSSSSSSSS', {
            translation: {
                'S': {
                    pattern: /[A-Za-z 0-9]/,
                    optional: false
                }
            }
        });

        $('.letras11').mask('SSSSSSSSSSS', {
            translation: {
                'S': {
                    pattern: /[A-Za-z 0-9]/,
                    optional: false
                }
            }
        });

        $('.letras45').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {
            translation: {
                'S': {
                    pattern: /[A-Za-z 0-9]/,
                    optional: false
                }
            }
        });

        $('.numeros4').mask('9999');

        $('.numero_carga').mask('99999999');

        //$('.monto').mask("#,##0.000", {reverse: false, maxlength: false});

        $('.monto').mask("9999999.999");

        $('.monto6_3').mask("999.999");

        $('.decimal6_2').mask('9999.999');

        $('.decimal5_2').mask('999.999');

        $('.decimal5_1').mask('9999.99');

        $('.decimal10_2').mask('99999999.999');

        /*----------------------------------------------------------------------------------

                                    Validando listboxs

        ----------------------------------------------------------------------------------*/

        $('.selectpicker.requerido').change(function(event) {

            var objeto = $(this);

            if (objeto.val() == null || objeto.val() == '-b' || objeto.val() == '') {

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

            if ($(this).val().trim() != "") {

                $(this).parent('div').removeClass('has-error');

            } else {

                $(this).parent('div').addClass('has-error');

            }

        });



        $('#cod_info_empresa').change(function(event) {

            /* Act on the event */

            conf_constructor_listado_temporadas($(this).val());

            bw_constructor_zonas($(this).val(), 'cod_zona');

            // plan_constructor_listado_usuarios_recomendado_por_finca($(this).val());

        });



        $('#cod_plantacion').change(function(event) {

            /* Act on the event */

            bw_constructor_bloques_plantacion_todos('cod_bloques_plantacion2', $(this).val());

        });



        $('#cod_inventario_quimico').change(function(event) {

            /* Act on the event */

            jQuery.ajaxSetup({
                async: false
            });

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

            var cantidad_sugerida = parseFloat(acres_plantados * ((dosis_maxima + dosis_minima) / 2));

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

            $('#cod_unidad_medida').selectpicker('val', $('#cod_inventario_quimico option:selected').data('cod_unidad_medida'));

            $('.selectpicker').selectpicker('refresh');

            jQuery.ajaxSetup({
                async: true
            });

        });

        $('#cod_zona').change(function(event) {

            /* Act on the event */

            bw_constructor_bloques($(this).val());

            $('#cantidad_acres').val($('#cod_zona option:selected').data('cantidad_acres'));

        });

        $('#cod_bloque').change(function(event) {

            /* Act on the event */

            var cantidad_acres = 0.00;

            $("#cod_bloque :selected").map(function(i, el) {

                cantidad_acres = cantidad_acres + parseFloat($(el).data('num_acres'));

            });

            $('#cantidad_acres').val(parseFloat(cantidad_acres).toFixed(2));

        });

        <?php

        if (count($PLANTACION)) {

        ?>

            $('#anio_plantacion').val("<?php echo utf8_encode($PLANTACION[0]['anio_plantacion']); ?>");

            $('#num_plantacion').val("<?php echo utf8_encode($PLANTACION[0]['num_plantacion']); ?>");

            $('#fecha_plantacion_planeada').val("<?php echo utf8_encode($PLANTACION[0]['fecha_plantacion_planeada']); ?>");

            $('#fecha_plantacion_ejecutada').val("<?php echo utf8_encode($PLANTACION[0]['fecha_plantacion_ejecutada']); ?>");

            $('#acres_plantados').val("<?php echo utf8_encode($PLANTACION[0]['acres_plantados']); ?>");

            $('#cod_info_empresa').selectpicker('val', "<?php echo utf8_encode($PLANTACION[0]['cod_info_empresa']); ?>");

            $('#cod_temporada').selectpicker('val', "<?php echo utf8_encode($PLANTACION[0]['cod_temporada']); ?>");

            $('.selectpicker').selectpicker('refresh');

            codigo_estado_plantacion = <?php echo $PLANTACION[0]['cod_estado']; ?>;

            codigo_plantacion = <?php echo $PLANTACION[0]['cod_plantacion']; ?>;

            $('#badge_estado').removeClass();

            $('#badge_estado').addClass('badge estado' + codigo_estado_plantacion);

            $('#badge_estado2').removeClass();

            $('#badge_estado2').addClass('badge estado' + codigo_estado_plantacion);

            $('#badge_estado').text("<?php echo utf8_encode($PLANTACION[0]['estado_plantacion']); ?>");

            $('#badge_estado2').text(estados[codigo_estado_plantacion]);

            //inv_constructor_listado_quimicos('cod_inventario_quimico',$('#cod_info_empresa').val());

            inv_constructor_listado_maquinarias('cod_maquinaria', $('#cod_info_empresa').val());

            <?php

            switch ($_SESSION['cod_perfil']) {

                case 1: // Administrador

            ?>

                    $('.btn_operador,.btn_explorador,.btn_supervisor').addClass('hide');

                    $('.btn_administrador').removeClass('hide');

                <?php

                    break;

                case 6: // Operador

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

                case 8: // Supervisor

                ?>

                    $('.btn_operador,.btn_explorador,.btn_supervisor').addClass('hide');

                    $('.btn_administrador').removeClass('hide');

                <?php

                    break;

                case 5: // Supervisor

                ?>

                    $('.btn_operador,.btn_explorador,.btn_supervisor').addClass('hide');

                    $('.btn_administrador').removeClass('hide');

                <?php

                    break;

                case 11: // Supervisor

                ?>

                    $('.btn_operador,.btn_explorador,.btn_supervisor').addClass('hide');

                    $('.btn_administrador').removeClass('hide');

                <?php

                    break;

                case 9: // Vice President

                ?>

                    $('.btn_operador,.btn_explorador,.btn_supervisor').addClass('hide');

                    $('.btn_administrador').removeClass('hide');

                <?php

                    break;

                    /*case 6: // Miscellneous

                    ?>

                    $('.btn_operador,.btn_explorador,.btn_supervisor').addClass('hide');

                    //$('.btn_administrador').removeClass('hide');

                    <?php

                    break;*/



                default:

                ?>

                    $('.btn_operador,.btn_explorador,.btn_supervisor').addClass('hide');

                    //$('.btn_administrador').removeClass('hide');

            <?php

                    break;
            }
        }

        if (isset($_POST['nombre_panel'])) {

            ?>

            $('#<?php echo $_POST['nombre_panel']; ?>').trigger('click');

        <?php

        }

        ?>

        conf_cargar_formularios_por_plantacion('div_formularios', codigo_plantacion);



        cargar_vista("<?php echo $_SESSION['descripcion']; ?>");

        //console.log('cod_cargo: ' + <?php echo $_SESSION['cod_cargo']; ?>);

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

        $('.validar_color').selectpicker({

            dropupAuto: 'true',

            container: 'body',

            size: '5',

            width: '100%',

            style: 'btn-sm btn-default',

            tickIcon: 'fa fa-check'

        });

        $('#cod_bloques_plantacion,#cod_bloques_plantacion2,#cod_bloque,#cod_bloques_aplicar_quimico,#cod_bloques_trasplante,#cod_bloques_load_report,#cod_zona_rociado,#cod_tipo_zona,#cod_bloques_rociado').selectpicker({

            dropupAuto: 'true',

            container: 'body',

            size: '10',

            width: '85%',

            style: 'btn-sm btn-info has-btn-all',

            tickIcon: 'fa fa-check'

        });

        $(".check-all").click(function(event) {

            event.stopPropagation();

            id = $(this).data('id');

            if ($(this).hasClass('allselected')) {

                $(this).removeClass('allselected btn-info').addClass('btn-danger');

                $('#' + id + ' option').prop('selected', false);

                $('#' + id + ' option').attr('selected', false);

                $('#' + id).selectpicker('deselectAll');

                $('#' + id).selectpicker('setStyle', 'btn-danger');

                $('#' + id).selectpicker('setStyle', 'btn-info', 'remove');

            } else {

                $(this).addClass('allselected btn-info').removeClass('btn-danger');

                if (!$('#' + id + ' option').attr('disabled'))

                {

                    $('#' + id + ' option').prop('selected', 'selected');

                    $('#' + id + ' option').attr('selected', 'selected');

                }

                $('#' + id).selectpicker('setStyle', 'btn-danger', 'remove');

                $('#' + id).selectpicker('setStyle', 'btn-info');

                $('#' + id).selectpicker('selectAll');

            }

            $('#' + id).selectpicker('render');

            $('.selectpicker').selectpicker('refresh');

        });

        //Habilita los selects para mobile

        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {

            $('.selectpicker').selectpicker('mobile');

        }

        $('.selectpicker').selectpicker('refresh');

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

        if ($('#tbody_semillas tr').length > 0)

        {

            $('#cod_info_empresa').attr('disabled', 'disabled');

        }

        $("#cod_tipo_aplicacion option").removeAttr('disabled').filter("[value='4']").attr('disabled', 'disabled');



        $('#dark_green_color').change(function(event) {

            console.log('option:' + $('#dark_green_color option:selected').val());

            if ($('#dark_green_color option:selected').val() == 5)

            {

                $('#dark_green_color').css('background', '#E6E326');

                $('#dark_green_color').selectpicker('setStyle', 'btn-danger btn-warning btn-success', 'remove');

                $('#dark_green_color').selectpicker('setStyle', 'btn-sm btn-warning');

            }

            if ($('#dark_green_color option:selected').val() > 5)

            {

                $('#dark_green_color').css('background', '#139400');

                $('#dark_green_color').selectpicker('setStyle', 'btn-danger btn-warning btn-success', 'remove');

                $('#dark_green_color').selectpicker('setStyle', 'btn-sm btn-success');

            }

            if ($('#dark_green_color option:selected').val() < 5)

            {

                $('#dark_green_color').css('background', '#E62626');

                $('#dark_green_color').selectpicker('setStyle', 'btn-danger btn-warning btn-success', 'remove');

                $('#dark_green_color').selectpicker('setStyle', 'btn-sm btn-danger');

            }

        });



        $('#yellow_leaves').change(function(event) {

            console.log('option:' + $('#yellow_leaves option:selected').val());

            if ($('#yellow_leaves option:selected').val() == 5)

            {

                $('#yellow_leaves').css('background', '#E6E326');

                $('#yellow_leaves').selectpicker('setStyle', 'btn-danger btn-warning btn-success', 'remove');

                $('#yellow_leaves').selectpicker('setStyle', 'btn-sm btn-warning');

            }

            if ($('#yellow_leaves option:selected').val() > 5)

            {

                $('#yellow_leaves').css('background', '#139400');

                $('#yellow_leaves').selectpicker('setStyle', 'btn-danger btn-warning btn-success', 'remove');

                $('#yellow_leaves').selectpicker('setStyle', 'btn-sm btn-success');

            }

            if ($('#yellow_leaves option:selected').val() < 5)

            {

                $('#yellow_leaves').css('background', '#E62626');

                $('#yellow_leaves').selectpicker('setStyle', 'btn-danger btn-warning btn-success', 'remove');

                $('#yellow_leaves').selectpicker('setStyle', 'btn-sm btn-danger');

            }

        });



        $('#weeds').change(function(event) {

            console.log('option:' + $('#weeds option:selected').val());

            if ($('#weeds option:selected').val() == 5)

            {

                $('#weeds').css('background', '#E6E326');

                $('#weeds').selectpicker('setStyle', 'btn-danger btn-warning btn-success', 'remove');

                $('#weeds').selectpicker('setStyle', 'btn-sm btn-warning');

            }

            if ($('#weeds option:selected').val() > 5)

            {

                $('#weeds').css('background', '#139400');

                $('#weeds').selectpicker('setStyle', 'btn-danger btn-warning btn-success', 'remove');

                $('#weeds').selectpicker('setStyle', 'btn-sm btn-success');

            }

            if ($('#weeds option:selected').val() < 5)

            {

                $('#weeds').css('background', '#E62626');

                $('#weeds').selectpicker('setStyle', 'btn-danger btn-warning btn-success', 'remove');

                $('#weeds').selectpicker('setStyle', 'btn-sm btn-danger');

            }

        });



        $('#optimal_soil_water_capacity').change(function(event) {

            console.log('option:' + $('#optimal_soil_water_capacity option:selected').val());

            if ($('#optimal_soil_water_capacity option:selected').val() == 5)

            {

                $('#optimal_soil_water_capacity').css('background', '#E6E326');

                $('#optimal_soil_water_capacity').selectpicker('setStyle', 'btn-danger btn-warning btn-success', 'remove');

                $('#optimal_soil_water_capacity').selectpicker('setStyle', 'btn-sm btn-warning');

            }

            if ($('#optimal_soil_water_capacity option:selected').val() > 5)

            {

                $('#optimal_soil_water_capacity').css('background', '#139400');

                $('#optimal_soil_water_capacity').selectpicker('setStyle', 'btn-danger btn-warning btn-success', 'remove');

                $('#optimal_soil_water_capacity').selectpicker('setStyle', 'btn-sm btn-success');

            }

            if ($('#optimal_soil_water_capacity option:selected').val() < 5)

            {

                $('#optimal_soil_water_capacity').css('background', '#E62626');

                $('#optimal_soil_water_capacity').selectpicker('setStyle', 'btn-danger btn-warning btn-success', 'remove');

                $('#optimal_soil_water_capacity').selectpicker('setStyle', 'btn-sm btn-danger');

            }

        });



        $('#right_density').change(function(event) {

            console.log('option:' + $('#right_density option:selected').val());

            if ($('#right_density option:selected').val() == 5)

            {

                $('#right_density').css('background', '#E6E326');

                $('#right_density').selectpicker('setStyle', 'btn-danger btn-warning btn-success', 'remove');

                $('#right_density').selectpicker('setStyle', 'btn-sm btn-warning');

            }

            if ($('#right_density option:selected').val() > 5)

            {

                $('#right_density').css('background', '#139400');

                $('#right_density').selectpicker('setStyle', 'btn-danger btn-warning btn-success', 'remove');

                $('#right_density').selectpicker('setStyle', 'btn-sm btn-success');

            }

            if ($('#right_density option:selected').val() < 5)

            {

                $('#right_density').css('background', '#E62626');

                $('#right_density').selectpicker('setStyle', 'btn-danger btn-warning btn-success', 'remove');

                $('#right_density').selectpicker('setStyle', 'btn-sm btn-danger');

            }

        });

        $('#modal_loading').modal('hide');

        jQuery.ajaxSetup({
            async: true
        });

    });



    $('#btn_add_zona').click(function(event) {

        /* Act on the event */

        $('#modal_zona').modal('show');

    });

    $('#btn_add_zona_rociado').click(function(event) {

        /* Act on the event */

        jQuery.ajaxSetup({
            async: false
        });

        bw_constructor_zonas($('#cod_info_empresa').val(), 'cod_zona_rociado', 1);

        bw_constructor_bloques_plantacion_todos('cod_bloques_rociado', codigo_plantacion);

        inv_constructor_listado_tipos_quimicos('cod_tipo_quimico_rociadores');

        $('#modal_zona_rociado').modal('show');

        jQuery.ajaxSetup({
            async: true
        });

    });



    $('#btn_guardar_zona').click(function(event) {

        /* Act on the event */

        var error = 0;

        $(".input.requerido_zona").map(function() {

            if (!$(this).val())

            {

                error = 1;

                $(this).parent('div').addClass('has-error');

                return false;

            } else

            {

                $(this).parent('div').removeClass('has-error');

            }

        });

        $(".selectpicker.requerido_zona").map(function() {

            if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '')

            {

                $(this).selectpicker('setStyle', 'btn-info', 'remove');

                $(this).selectpicker('setStyle', 'btn-danger');

                error = 1;

            } else

            {

                $(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');

                $(this).selectpicker('setStyle', 'btn-info');

                $(this).removeClass('campo-vacio');

            }

            $(this).selectpicker('refresh');

        });

        if (error == 0)

        {

            $('#modal_zona').modal('hide');

            grl_overlay_loading('');

            $('#modal_loading').modal('hide');

            $('#modal_loading').on('hidden.bs.modal', function() {

                plan_agregar_zona_plantacion(codigo_plantacion);

            });

        } else

        {

            grl_mensaje('You must fill the empty fields', 'Debe llenar los campos vacíos', 'warning');

        }

    });



    function cargar_vista(descripcion) {

        //console.log('Estado Plantación: ' + codigo_estado_plantacion);

        if (descripcion.search('planting-access') >= 0)

        {

            switch (codigo_estado_plantacion)

            {

                case 0: //0. Nueva plantación

                    $('.btn-guardar').addClass('hide');

                    $('#btn_guardar_plantacion').removeClass('hide');

                    $('#btn_plantar').addClass('hide');

                    $('#btn_podar').addClass('hide');

                    $('#btn_crecimiento').addClass('hide');

                    $('#btn_exploraciones').addClass('hide');

                    $('#btn_calibrar_fertilizante').addClass('hide');

                    $('#btn_aplicar_quimicos').addClass('hide');

                    $('#btn_cosechar').addClass('hide');

                    $('#btn_cosechado').addClass('hide');

                    $('#btn_eliminado').addClass('hide');

                    $('#btn_control_calidad').addClass('hide');

                    $('#btn_producto_empacado').addClass('hide');

                    $('#btn_reclamaciones').addClass('hide');

                    $('#btn_terminado').addClass('hide');

                    $('#fecha_plantacion_ejecutada').attr("readonly", false);

                    $('#fecha_plantacion_planeada').attr("readonly", true);

                    $('#fecha_plantacion_planeada').removeClass('requerido');

                    break;

                case 1: //1. Guardada

                    $('.btn-guardar').removeClass('hide');

                    $('#btn_guardar_plantacion').addClass('hide');

                    $('#btn_plantar').removeClass('hide');

                    $('#btn_podar').addClass('hide');

                    $('#btn_crecimiento').addClass('hide');

                    $('#btn_exploraciones').addClass('hide');

                    $('#btn_calibrar_fertilizante').addClass('hide');

                    $('#btn_aplicar_quimicos').addClass('hide');

                    $('#btn_cosechar').addClass('hide');

                    $('#btn_cosechado').addClass('hide');

                    $('#btn_eliminado').removeClass('hide');

                    $('#btn_control_calidad').addClass('hide');

                    $('#btn_producto_empacado').addClass('hide');

                    $('#btn_reclamaciones').addClass('hide');

                    $('#btn_terminado').addClass('hide');

                    $('#fecha_plantacion_ejecutada').attr("readonly", false);

                    $('#fecha_plantacion_planeada').attr("readonly", true);

                    $('#fecha_plantacion_planeada').removeClass('requerido');

                    break;

                case 2: //2. Plantado

                    $('#btn_guardar_plantacion').addClass('hide');

                    $('#btn_plantar').addClass('hide');

                    $('#btn_podar').removeClass('hide');

                    $('#btn_crecimiento').removeClass('hide');

                    $('#btn_exploraciones').addClass('hide');

                    $('#btn_calibrar_fertilizante').addClass('hide');

                    $('#btn_aplicar_quimicos').addClass('hide');

                    $('#btn_cosechar').addClass('hide');

                    $('#btn_cosechado').addClass('hide');

                    $('#btn_eliminado').removeClass('hide');

                    $('#btn_control_calidad').addClass('hide');

                    $('#btn_producto_empacado').addClass('hide');

                    $('#btn_reclamaciones').addClass('hide');

                    $('#btn_terminado').addClass('hide');

                    $('#fecha_plantacion_ejecutada').attr("readonly", true);

                    break;

                case 3: //3. Poda

                    $('#btn_guardar_plantacion').addClass('hide');

                    $('#btn_plantar').addClass('hide');

                    $('#btn_podar').addClass('hide');

                    $('#btn_crecimiento').removeClass('hide');

                    $('#btn_exploraciones').addClass('hide');

                    $('#btn_calibrar_fertilizante').addClass('hide');

                    $('#btn_aplicar_quimicos').addClass('hide');

                    $('#btn_cosechar').addClass('hide');

                    $('#btn_cosechado').addClass('hide');

                    $('#btn_eliminado').removeClass('hide');

                    $('#btn_control_calidad').addClass('hide');

                    $('#btn_producto_empacado').addClass('hide');

                    $('#btn_reclamaciones').addClass('hide');

                    $('#btn_terminado').addClass('hide');

                    break;

                case 4: //4. Crecimiento

                    $('#btn_guardar_plantacion').addClass('hide');

                    $('#btn_plantar').addClass('hide');

                    $('#btn_podar').addClass('hide');

                    $('#btn_crecimiento').addClass('hide');

                    $('#btn_exploraciones').addClass('hide');

                    $('#btn_calibrar_fertilizante').addClass('hide');

                    $('#btn_aplicar_quimicos').addClass('hide');

                    $('#btn_cosechar').removeClass('hide');

                    $('#btn_cosechado').addClass('hide');

                    $('#btn_eliminado').removeClass('hide');

                    $('#btn_control_calidad').addClass('hide');

                    $('#btn_producto_empacado').addClass('hide');

                    $('#btn_reclamaciones').addClass('hide');

                    $('#btn_terminado').addClass('hide');

                    break;

                case 5: //5. Listo para cosechar

                    $('#btn_guardar_plantacion').addClass('hide');

                    $('#btn_plantar').addClass('hide');

                    $('#btn_podar').addClass('hide');

                    $('#btn_crecimiento').addClass('hide');

                    $('#btn_exploraciones').addClass('hide');

                    $('#btn_calibrar_fertilizante').addClass('hide');

                    $('#btn_aplicar_quimicos').addClass('hide');

                    $('#btn_cosechar').addClass('hide');

                    $('#btn_cosechado').removeClass('hide');

                    $('#btn_eliminado').removeClass('hide');

                    $('#btn_control_calidad').addClass('hide');

                    $('#btn_producto_empacado').addClass('hide');

                    $('#btn_reclamaciones').addClass('hide');

                    $('#btn_terminado').addClass('hide');

                    break;

                case 6: //6. Cosechado

                    $('#btn_guardar_plantacion').addClass('hide');

                    $('#btn_plantar').addClass('hide');

                    $('#btn_podar').addClass('hide');

                    $('#btn_crecimiento').addClass('hide');

                    $('#btn_exploraciones').addClass('hide');

                    $('#btn_calibrar_fertilizante').addClass('hide');

                    $('#btn_aplicar_quimicos').addClass('hide');

                    $('#btn_cosechar').addClass('hide');

                    $('#btn_cosechado').addClass('hide');

                    $('#btn_eliminado').removeClass('hide');

                    $('#btn_control_calidad').removeClass('hide');

                    $('#btn_producto_empacado').addClass('hide');

                    $('#btn_reclamaciones').addClass('hide');

                    $('#btn_terminado').addClass('hide');

                    break;

                case 7: //7. Control de calidad

                    $('#btn_guardar_plantacion').addClass('hide');

                    $('#btn_plantar').addClass('hide');

                    $('#btn_podar').addClass('hide');

                    $('#btn_crecimiento').addClass('hide');

                    $('#btn_exploraciones').addClass('hide');

                    $('#btn_calibrar_fertilizante').addClass('hide');

                    $('#btn_aplicar_quimicos').addClass('hide');

                    $('#btn_cosechar').addClass('hide');

                    $('#btn_cosechado').addClass('hide');

                    $('#btn_eliminado').removeClass('hide');

                    $('#btn_control_calidad').addClass('hide');

                    $('#btn_producto_empacado').removeClass('hide');

                    $('#btn_reclamaciones').addClass('hide');

                    $('#btn_terminado').addClass('hide');

                    break;

                case 8: //8. Producto empacado

                    $('#btn_guardar_plantacion').addClass('hide');

                    $('#btn_plantar').addClass('hide');

                    $('#btn_podar').addClass('hide');

                    $('#btn_crecimiento').addClass('hide');

                    $('#btn_exploraciones').addClass('hide');

                    $('#btn_calibrar_fertilizante').addClass('hide');

                    $('#btn_aplicar_quimicos').addClass('hide');

                    $('#btn_cosechar').addClass('hide');

                    $('#btn_cosechado').addClass('hide');

                    $('#btn_eliminado').removeClass('hide');

                    $('#btn_control_calidad').addClass('hide');

                    $('#btn_producto_empacado').addClass('hide');

                    $('#btn_reclamaciones').addClass('hide');

                    $('#btn_terminado').removeClass('hide');

                    break;

                case 9: //9. Eliminado

                    $('#btn_guardar_plantacion').addClass('hide');

                    $('#btn_plantar').addClass('hide');

                    $('#btn_podar').addClass('hide');

                    $('#btn_crecimiento').addClass('hide');

                    $('#btn_exploraciones').addClass('hide');

                    $('#btn_calibrar_fertilizante').addClass('hide');

                    $('#btn_aplicar_quimicos').addClass('hide');

                    $('#btn_cosechar').addClass('hide');

                    $('#btn_cosechado').addClass('hide');

                    $('#btn_eliminado').addClass('hide');

                    $('#btn_control_calidad').addClass('hide');

                    $('#btn_producto_empacado').addClass('hide');

                    $('#btn_reclamaciones').addClass('hide');

                    $('#btn_terminado').addClass('hide');

                    $('.btn-eliminar').addClass('hide');

                    break;

                case 10: //10. Terminado

                    $('#btn_guardar_plantacion').addClass('hide');

                    $('#btn_plantar').addClass('hide');

                    $('#btn_podar').addClass('hide');

                    $('#btn_crecimiento').addClass('hide');

                    $('#btn_exploraciones').addClass('hide');

                    $('#btn_calibrar_fertilizante').addClass('hide');

                    $('#btn_aplicar_quimicos').addClass('hide');

                    $('#btn_cosechar').addClass('hide');

                    $('#btn_cosechado').addClass('hide');

                    $('#btn_eliminado').addClass('hide');

                    $('#btn_control_calidad').addClass('hide');

                    $('#btn_producto_empacado').addClass('hide');

                    $('#btn_reclamaciones').addClass('hide');

                    $('#btn_terminado').addClass('hide');

                    $('.btn-eliminar').addClass('hide');

                    break;

            }

        }

    }





    /*----------------Flujo de plantación-----------------------------*/

    $('#btn_guardar_plantacion').click(function(event) {

        /* Act on the event */

        var error = 0;

        $(".input.requerido").map(function() {

            if (!$(this).val())

            {

                error = 1;

                $(this).parent('div').addClass('has-error');

                return false;

            } else

            {

                $(this).parent('div').removeClass('has-error');

            }

        });

        $(".selectpicker.requerido").map(function() {

            if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '')

            {

                $(this).selectpicker('setStyle', 'btn-info', 'remove');

                $(this).selectpicker('setStyle', 'btn-danger');

                error = 1;

            } else

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

            $('#modal_loading').on('hidden.bs.modal', function() {

                plan_guardar_plantacion(codigo_plantacion);

            });

        } else

        {

            grl_mensaje('You must fill the empty fields', 'Debe llenar los campos vacíos', 'warning');

        }

        //codigo_estado_plantacion = 1;



    });

    $('#btn_plantar').click(function(event) {

        /* Act on the event */

        if ($('#tbody_semillas tr').length > 0 || <?php echo count($TRASPLANTES); ?> > 0)

        {

            jQuery.ajaxSetup({
                async: false
            });

            bw_cambiar_flujo_plantacion(codigo_plantacion, 0, 0);

            //plan_vista_plantacion(codigo_plantacion);

            jQuery.ajaxSetup({
                async: true
            });

        } else

        {

            grl_mensaje('You must enter planted seeds to advance', 'Debe ingresar semillas plantadas para poder avanzar', 'warning');

        }

    });

    $('#btn_podar').click(function(event) {

        /* Act on the event */

        //codigo_estado_plantacion = 3;

        $('#modal_confirmar_poda').modal('show');

        /*bw_cambiar_flujo_plantacion(codigo_plantacion,0,1);

        plan_vista_plantacion(codigo_plantacion);

*/
    });

    $('#btn_confirmar_poda').click(function(event) {

        /* Act on the event */

        //codigo_estado_plantacion = 3;

        /*$('#modal_confirmar_poda').modal('show');*/

        jQuery.ajaxSetup({
            async: false
        });

        $('#modal_confirmar_poda').modal('hide');

        $('#modal_confirmar_poda').on('hidden.bs.modal', function() {

            bw_cambiar_flujo_plantacion(codigo_plantacion, 0, 1);

            //plan_vista_plantacion(codigo_plantacion);

        });

        jQuery.ajaxSetup({
            async: true
        });

    });

    $('#btn_crecimiento').click(function(event) {

        /* Act on the event */

        //codigo_estado_plantacion = 4;

        $('#modal_confirmar_crecimiento').modal('show');

        /*bw_cambiar_flujo_plantacion(codigo_plantacion,0,0);

        plan_vista_plantacion(codigo_plantacion);*/

    });

    $('#btn_confirmar_crecimiento').click(function(event) {

        /* Act on the event */

        //codigo_estado_plantacion = 4;

        jQuery.ajaxSetup({
            async: false
        });

        $('#modal_confirmar_crecimiento').modal('hide');

        $('#modal_confirmar_crecimiento').on('hidden.bs.modal', function() {

            bw_cambiar_flujo_plantacion(codigo_plantacion, 0, 0);

            //plan_vista_plantacion(codigo_plantacion);

        });

        jQuery.ajaxSetup({
            async: true
        });

    });

    $('#btn_exploraciones').click(function(event) {

        /* Act on the event */

        codigo_estado_plantacion = 4;

        plan_vista_plantacion(1);

    });

    $('#btn_calibrar_fertilizante').click(function(event) {

        /* Act on the event */

        codigo_estado_plantacion = 5;

        plan_vista_plantacion(1);

    });

    $('#btn_aplicar_quimicos').click(function(event) {

        /* Act on the event */

        codigo_estado_plantacion = 6;

        plan_vista_plantacion(1);

    });

    $('#btn_cosechar').click(function(event) {

        /* Act on the event */

        //codigo_estado_plantacion = 5;

        $('#modal_confirmar_cosechar').modal('show');

        /*bw_cambiar_flujo_plantacion(codigo_plantacion,0,0);

        plan_vista_plantacion(codigo_plantacion);*/

    });

    $('#btn_confirmar_cosechar').click(function(event) {

        /* Act on the event */

        //codigo_estado_plantacion = 5;

        jQuery.ajaxSetup({
            async: false
        });

        $('#modal_confirmar_cosechar').modal('hide');

        $('#modal_confirmar_cosechar').on('hidden.bs.modal', function() {

            bw_cambiar_flujo_plantacion(codigo_plantacion, 0, 0);

            //plan_vista_plantacion(codigo_plantacion);

        });

        jQuery.ajaxSetup({
            async: true
        });

    });

    $('#btn_cosechado').click(function(event) {

        /* Act on the event */

        //codigo_estado_plantacion = 6;

        $('#modal_confirmar_cosechado').modal('show');

        /*bw_cambiar_flujo_plantacion(codigo_plantacion,0,0);

        plan_vista_plantacion(codigo_plantacion);*/

    });

    $('#btn_confirmar_cosechado').click(function(event) {

        /* Act on the event */

        //codigo_estado_plantacion = 6;

        jQuery.ajaxSetup({
            async: false
        });

        $('#modal_confirmar_cosechado').modal('hide');

        $('#modal_confirmar_cosechado').on('hidden.bs.modal', function() {

            bw_cambiar_flujo_plantacion(codigo_plantacion, 0, 0);

            //plan_vista_plantacion(codigo_plantacion);

        });

        jQuery.ajaxSetup({
            async: true
        });

    });

    $('#btn_eliminado').click(function(event) {

        /* Act on the event */

        //codigo_estado_plantacion = 9;

        //bw_cambiar_flujo_plantacion(codigo_plantacion,1,0);

        //plan_vista_plantacion(codigo_plantacion);

        msg_box('Eliminar Plantación - Delete Planting', '¿Esta seguro que desea eliminar la plantación? <br> Are you sure you want to delete planting?');

    });

    $('#btn_control_calidad').click(function(event) {

        /* Act on the event */

        //codigo_estado_plantacion = 7;

        $('#modal_confirmar_control_calidad').modal('show');

        /*bw_cambiar_flujo_plantacion(codigo_plantacion,0,0);

        plan_vista_plantacion(codigo_plantacion);*/

    });

    $('#btn_confirmar_control_calidad').click(function(event) {

        /* Act on the event */

        //codigo_estado_plantacion = 7;

        jQuery.ajaxSetup({
            async: false
        });

        $('#modal_confirmar_control_calidad').modal('hide');

        $('#modal_confirmar_control_calidad').on('hidden.bs.modal', function() {

            bw_cambiar_flujo_plantacion(codigo_plantacion, 0, 0);

            //plan_vista_plantacion(codigo_plantacion);

        });

        jQuery.ajaxSetup({
            async: true
        });

    });

    $('#btn_producto_empacado').click(function(event) {

        /* Act on the event */

        //codigo_estado_plantacion = 8;

        $('#modal_confirmar_producto_empacado').modal('show');

        /*bw_cambiar_flujo_plantacion(codigo_plantacion,0,0);

        plan_vista_plantacion(codigo_plantacion);*/

    });

    $('#btn_confirmar_producto_empacado').click(function(event) {

        /* Act on the event */

        //codigo_estado_plantacion = 8;

        jQuery.ajaxSetup({
            async: false
        });

        $('#modal_confirmar_producto_empacado').modal('hide');

        $('#modal_confirmar_producto_empacado').on('hidden.bs.modal', function() {

            bw_cambiar_flujo_plantacion(codigo_plantacion, 0, 0);

            //plan_vista_plantacion(codigo_plantacion);

        });

        jQuery.ajaxSetup({
            async: true
        });

    });

    $('#btn_reclamaciones').click(function(event) {

        /* Act on the event */

        codigo_estado_plantacion = 12;

        plan_vista_plantacion(1);

    });

    $('#btn_terminado').click(function(event) {

        /* Act on the event */

        //codigo_estado_plantacion = 10;

        $('#modal_confirmar_terminar').modal('show');

        /*bw_cambiar_flujo_plantacion(codigo_plantacion,0,0);

        plan_vista_plantacion(codigo_plantacion);*/

    });

    $('#btn_confirmar_terminar').click(function(event) {

        /* Act on the event */

        //codigo_estado_plantacion = 10;

        jQuery.ajaxSetup({
            async: false
        });

        $('#modal_confirmar_terminar').modal('hide');

        $('#modal_confirmar_terminar').on('hidden.bs.modal', function() {

            bw_cambiar_flujo_plantacion(codigo_plantacion, 0, 0);

            //plan_vista_plantacion(codigo_plantacion);

        });

        jQuery.ajaxSetup({
            async: true
        });

    });

    $('#message_box').on('click', '#btn_ok', function(event) {

        event.preventDefault();

        /* Act on the event */
        $("#confirm_box").modal("hide");

        $('#confirm_box').on('hidden.bs.modal', function() {

            jQuery.ajaxSetup({
                async: false
            });

            bw_cambiar_flujo_plantacion(codigo_plantacion, 1, 0);

            //plan_vista_plantacion(codigo_plantacion);

            jQuery.ajaxSetup({
                async: true
            });

        });

    });

    $('#btn_eliminar_bloque').click(function(event) {

        /* Act on the event */

        $('#modal_eliminar_bloque').modal('hide');

        $('#modal_eliminar_bloque').on('hidden.bs.modal', function() {

            console.log('codigo_detalle: ' + codigo_detalle);

            plan_cambiar_estado_bloque_plantacion(codigo_detalle, 9);

            //bw_eliminar_bloque_plantacion(codigo_plantacion, codigo_detalle);

        });

    });

    /*$('.checkbox_bloque').change(function(event) {

        event.preventDefault();

        event.stopPropagation();

        if(codigo_estado_plantacion == 1)

            plan_cambiar_estado_bloque_plantacion($(this).data('id'),($(this).attr('checked') ? 0 : 1));

        else

            grl_mensaje('No se puede desactivar','por estado de plantación','warning');

    });*/



    $('#btn_add_semillas').click(function(event) {

        /* Act on the event */

        $('#modal_semillas').modal('show');

    });

    $('#cod_bloques_plantacion').change(function(event) {

        /* Act on the event */

        var acres_bloques = 0.00;

        $("#cod_bloques_plantacion :selected").map(function(i, el) {

            acres_bloques = acres_bloques + parseFloat($(el).data('cantidad_acres'));

        });

        $('#acres_bloques').val(acres_bloques);

    });



    $('#btn_guardar_semilla').click(function(event) {

        /* Act on the event */

        var error = 0;

        $(".input.requerido_semilla").map(function() {

            if (!$(this).val())

            {

                error = 1;

                $(this).parent('div').addClass('has-error');

                return false;

            } else

            {

                $(this).parent('div').removeClass('has-error');

            }

        });

        $(".selectpicker.requerido_semilla").map(function() {

            if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '')

            {

                $(this).selectpicker('setStyle', 'btn-info', 'remove');

                $(this).selectpicker('setStyle', 'btn-danger');

                error = 1;

            } else

            {

                $(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');

                $(this).selectpicker('setStyle', 'btn-info');

                $(this).removeClass('campo-vacio');

            }

            $(this).selectpicker('refresh');

        });

        //console.log('cantidad_usada: '+parseFloat($('#cantidad_usada').val().replace(',','')));

        //console.log('cantidad_semilla: '+parseFloat($('#cod_inventario_semilla option:selected').data('cantidad_semilla')));

        if (parseFloat($('#cantidad_usada').val().replace(',', '')) > parseFloat($('#cod_inventario_semilla option:selected').data('cantidad_semilla')))

        {

            error = 2;

            $('#cantidad_usada').parent('div').addClass('has-error');

        }

        if (error == 0)

        {

            $('#modal_semillas').modal('hide');

            grl_overlay_loading('');

            $('#modal_loading').modal('hide');

            $('#modal_loading').on('hidden.bs.modal', function() {

                plan_agregar_semilla_plantacion(codigo_plantacion);

            });

        } else

        {

            if (error == 2)

                grl_mensaje('Amount of seed used exceeds that existing in selected inventory', 'Cantidad de semilla usada excede la que existe en inventario seleccionado', 'warning');

            else

                grl_mensaje('You must fill the empty fields', 'Debe llenar los campos vacíos', 'warning');

        }

        //codigo_estado_plantacion = 1;



    });

    //1. En espera de exploración

    //2. Exploración inicial realizada

    //3. Sembradora plantada

    //4. Aplicación de químico

    //5. Control de Calidad

    //6. Lista para cosechar

    //7. Cosechada

    //8. Suelo en mal estado

    //9. Plantación en mal estado

    $('#btn_estado_bloque').click(function(event) {

        /* Act on the event */

        var error = 0;

        $(".input.requerido_estado_bloque").map(function() {

            if (!$(this).val())

            {

                error = 1;

                $(this).parent('div').addClass('has-error');

                return false;

            } else

            {

                $(this).parent('div').removeClass('has-error');

            }

        });

        $(".selectpicker.requerido_estado_bloque").map(function() {

            if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '')

            {

                $(this).selectpicker('setStyle', 'btn-info', 'remove');

                $(this).selectpicker('setStyle', 'btn-danger');

                error = 1;

            } else

            {

                $(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');

                $(this).selectpicker('setStyle', 'btn-info');

                $(this).removeClass('campo-vacio');

            }

            $(this).selectpicker('refresh');

        });

        if (error == 0)

        {

            $('#modal_estado').modal('hide');

            grl_overlay_loading('');

            $('#modal_loading').modal('hide');

            $('#modal_loading').on('hidden.bs.modal', function() {

                plan_cambiar_estado_bloque_plantacion(codigo_detalle, $('#cod_estado_plantacion').val(), $('#motivo_estado_plantacion').val());

            });

        } else

        {

            grl_mensaje('You must fill the empty fields', 'Debe llenar los campos vacíos', 'warning');

        }

    });

    //Botón para poder ir/regresar al listado en la barra de acciones

    $("#btn_ir_al_listado").on('click', function() {

        grl_obtener_cuerpo_menu(4, 'mod_plantaciones/ui/plan_listado_plantaciones.php');

    });



    $('#btn_calibrar_bloque').click(function(event) {

        /* Act on the event */

        var error = 0;

        $(".input.requerido_calibrar").map(function() {

            if (!$(this).val())

            {

                error = 1;

                $(this).parent('div').addClass('has-error');

                return false;

            } else

            {

                $(this).parent('div').removeClass('has-error');

            }

        });

        $(".selectpicker.requerido_calibrar").map(function() {

            if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '')

            {

                $(this).selectpicker('setStyle', 'btn-info', 'remove');

                $(this).selectpicker('setStyle', 'btn-danger');

                error = 1;

            } else

            {

                $(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');

                $(this).selectpicker('setStyle', 'btn-info');

                $(this).removeClass('campo-vacio');

            }

            $(this).selectpicker('refresh');

        });

        if (error == 0)

        {

            $('#modal_calibrar').modal('hide');

            grl_overlay_loading('');

            $('#modal_loading').modal('hide');

            $('#modal_loading').on('hidden.bs.modal', function() {

                plan_ingresar_calibracion_plantacion(codigo_detalle, codigo_plantacion);

            });

        } else

        {

            grl_mensaje('You must fill the empty fields', 'Debe llenar los campos vacíos', 'warning');

        }

    });



    $('#btn_nueva_observacion').click(function(event) {

        /* Act on the event */

        var error = 0;

        $(".input.requerido_observacion").map(function() {

            if (!$(this).val())

            {

                error = 1;

                $(this).parent('div').addClass('has-error');

                return false;

            } else

            {

                $(this).parent('div').removeClass('has-error');

            }

        });

        $(".selectpicker.requerido_observacion").map(function() {

            if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '')

            {

                $(this).selectpicker('setStyle', 'btn-info', 'remove');

                $(this).selectpicker('setStyle', 'btn-danger');

                error = 1;

            } else

            {

                $(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');

                $(this).selectpicker('setStyle', 'btn-info');

                $(this).removeClass('campo-vacio');

            }

            $(this).selectpicker('refresh');

        });

        if (error == 0)

        {

            $('.plan_modal_nueva_observacion').modal('hide');

            grl_overlay_loading('');

            $('#modal_loading').modal('hide');

            $('#modal_loading').on('hidden.bs.modal', function() {

                plan_ingresar_observacion_plantacion(codigo_plantacion);

            });

        } else

        {

            grl_mensaje('You must fill the empty fields', 'Debe llenar los campos vacíos', 'warning');

        }

    });



    $('#btn_guardar_aplicacion').click(function(event) {

        /* Act on the event */

        var error = 0;

        if (cod_aplicacion_quimico == 0) //Información que llena el supervisor

        {

            $(".input.requerido_aplicacion_supervisor").map(function() {

                if (!$(this).val())

                {

                    error = 1;

                    $(this).parent('div').addClass('has-error');

                    return false;

                } else

                {

                    $(this).parent('div').removeClass('has-error');

                }

            });

            $(".selectpicker.requerido_aplicacion_supervisor").map(function() {

                if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '')

                {

                    $(this).selectpicker('setStyle', 'btn-info', 'remove');

                    $(this).selectpicker('setStyle', 'btn-danger');

                    error = 1;

                } else

                {

                    $(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');

                    $(this).selectpicker('setStyle', 'btn-info');

                    $(this).removeClass('campo-vacio');

                }

                $(this).selectpicker('refresh');

            });

        } else //Información que llena el operador

        {

            $(".input.requerido_aplicacion_operador").map(function() {

                if (!$(this).val())

                {

                    error = 1;

                    $(this).parent('div').addClass('has-error');

                    return false;

                } else

                {

                    $(this).parent('div').removeClass('has-error');

                }

            });

            $(".selectpicker.requerido_aplicacion_operador").map(function() {

                if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '')

                {

                    $(this).selectpicker('setStyle', 'btn-info', 'remove');

                    $(this).selectpicker('setStyle', 'btn-danger');

                    error = 1;

                } else

                {

                    $(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');

                    $(this).selectpicker('setStyle', 'btn-info');

                    $(this).removeClass('campo-vacio');

                }

                $(this).selectpicker('refresh');

            });

        }

        if (error == 0)

        {

            $('#modal_aplicar_quimico').modal('hide');

            grl_overlay_loading('');

            $('#modal_loading').modal('hide');

            $('#modal_loading').on('hidden.bs.modal', function() {

                plan_guardar_aplicar_quimico(cod_aplicacion_quimico, codigo_plantacion);

            });

        } else

        {

            grl_mensaje('You must fill the empty fields', 'Debe llenar los campos vacíos', 'warning');

        }

    });



    $('#btn_guardar_exploracion_plantacion').click(function(event) {

        /* Act on the event */

        var error = 0;

        $(".input.requerido_explorar_plantacion").map(function() {

            if (!$(this).val())

            {

                error = 1;

                $(this).parent('div').addClass('has-error');

                return false;

            } else

            {

                $(this).parent('div').removeClass('has-error');

            }

        });

        $(".selectpicker.requerido_explorar_plantacion").map(function() {

            if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '')

            {

                $(this).selectpicker('setStyle', 'btn-info', 'remove');

                $(this).selectpicker('setStyle', 'btn-danger');

                error = 1;

            } else

            {

                $(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');

                $(this).selectpicker('setStyle', 'btn-info');

                $(this).removeClass('campo-vacio');

            }

            $(this).selectpicker('refresh');

        });

        if (error == 0)

        {

            $('#modal_explorar_plantacion').modal('hide');

            grl_overlay_loading('');

            $('#modal_loading').modal('hide');

            $('#modal_loading').on('hidden.bs.modal', function() {

                plan_guardar_exploracion(codigo_detalle, codigo_plantacion);

            });

        } else

        {

            grl_mensaje('You must fill the empty fields', 'Debe llenar los campos vacíos', 'warning');

        }

    });



    $('#btn_guardar_observacion_exploracion').click(function(event) {

        /* Act on the event */

        if ($('#observacion_exploracion').val() != '' && $('#observacion_exploracion').val() != null)

        {

            jQuery.ajaxSetup({
                async: false
            });

            plan_guardar_observacion_exploracion(flag_exploracion, cod_exploracion);

            $('#observacion_exploracion').val('');

            plan_cargar_observaciones_exploracion(flag_exploracion, cod_exploracion, 'div_observaciones_exploracion');

            jQuery.ajaxSetup({
                async: true
            });

        } else

        {

            grl_mensaje('You must enter an observation', 'Debe ingresar una observación', 'warning');

        }

    });

    $('#cod_tipo_quimico').change(function(event) {

        /* Act on the event */

        inv_constructor_listado_quimicos_por_tipo_quimico_granja('cod_inventario_quimico', $('#cod_info_empresa').val(), $(this).val());

    });

    $('#cod_tipo_quimico_rociadores').change(function(event) {

        /* Act on the event */

        inv_constructor_listado_quimicos_por_tipo_quimico_granja('cod_quimico_rociadores', $('#cod_info_empresa').val(), $(this).val());

    });

    $('#cod_quimico_rociadores').change(function(event) {

        /* Act on the event */

        //inv_constructor_listado_quimicos_por_tipo_quimico_granja('cod_quimico_rociadores',$('#cod_info_empresa').val(), $(this).val());

        inv_constructor_listado_unidades_medida_relacionadas($('#cod_quimico_rociadores option:selected').data('cod_unidad_medida'), 'cod_unidad_medida2');

    });



    $('#btn_guardar_cleaning').click(function(event) {

        /* Act on the event */

        var error = 0;

        $(".input.requerido_limpieza").map(function() {

            if (!$(this).val())

            {

                error = 1;

                $(this).parent('div').addClass('has-error');

                return false;

            } else

            {

                $(this).parent('div').removeClass('has-error');

            }

        });

        $(".selectpicker.requerido_limpieza").map(function() {

            if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '')

            {

                $(this).selectpicker('setStyle', 'btn-info', 'remove');

                $(this).selectpicker('setStyle', 'btn-danger');

                error = 1;

            } else

            {

                $(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');

                $(this).selectpicker('setStyle', 'btn-info');

                $(this).removeClass('campo-vacio');

            }

            $(this).selectpicker('refresh');

        });

        if (error == 0)

        {

            $('#modal_cleaning_sanitizing').modal('hide');

            $('#modal_cleaning_sanitizing').on('hidden.bs.modal', function() {

                plan_guardar_formulario_limpieza(codigo_detalle, codigo_plantacion);

            });

        } else

        {

            grl_mensaje('You must fill the empty fields', 'Debe llenar los campos vacíos', 'warning');

        }

    });



    $('#btn_guardar_harvesting_worksheet').click(function(event) {

        /* Act on the event */

        var error = 0;

        $(".input.requerido_harvesting_worksheet").map(function() {

            if (!$(this).val())

            {

                error = 1;

                $(this).parent('div').addClass('has-error');

                return false;

            } else

            {

                $(this).parent('div').removeClass('has-error');

            }

        });

        $(".selectpicker.requerido_harvesting_worksheet").map(function() {

            if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '')

            {

                $(this).selectpicker('setStyle', 'btn-info', 'remove');

                $(this).selectpicker('setStyle', 'btn-danger');

                error = 1;

            } else

            {

                $(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');

                $(this).selectpicker('setStyle', 'btn-info');

                $(this).removeClass('campo-vacio');

            }

            $(this).selectpicker('refresh');

        });

        if (error == 0)

        {

            jQuery.ajaxSetup({
                async: false
            });

            $('#modal_harvesting_worksheet').modal('hide');

            //$('#modal_harvesting_worksheet').on('hidden.bs.modal', function () {

            plan_guardar_formulario_harvesting_worksheet(codigo_detalle, codigo_plantacion);

            //});

            jQuery.ajaxSetup({
                async: true
            });

        } else

        {

            grl_mensaje('You must fill the empty fields', 'Debe llenar los campos vacíos', 'warning');

        }

    });



    $('#btn_guardar_harvesting_checklist').click(function(event) {

        /* Act on the event */

        var error = 0;

        $(".input.requerido_harvesting_checklist").map(function() {

            if (!$(this).val())

            {

                error = 1;

                $(this).parent('div').addClass('has-error');

                return false;

            } else

            {

                $(this).parent('div').removeClass('has-error');

            }

        });

        $(".selectpicker.requerido_harvesting_checklist").map(function() {

            if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '')

            {

                $(this).selectpicker('setStyle', 'btn-info', 'remove');

                $(this).selectpicker('setStyle', 'btn-danger');

                error = 1;

            } else

            {

                $(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');

                $(this).selectpicker('setStyle', 'btn-info');

                $(this).removeClass('campo-vacio');

            }

            $(this).selectpicker('refresh');

        });

        if (error == 0)

        {

            $('#modal_harvesting_checklist').modal('hide');

            plan_guardar_formulario_harvesting_checklist(codigo_detalle, codigo_plantacion);

        } else

        {

            grl_mensaje('You must fill the empty fields', 'Debe llenar los campos vacíos', 'warning');

        }

    });



    $('#btn_agregar_quimico').click(function(event) {

        /* Act on the event */

        var error = 0;

        $(".input.requerido_agregar_quimico").map(function() {

            if (!$(this).val())

            {

                error = 1;

                $(this).parent('div').addClass('has-error');

                return false;

            } else

            {

                $(this).parent('div').removeClass('has-error');

            }

        });

        $(".selectpicker.requerido_agregar_quimico").map(function() {

            if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '')

            {

                $(this).selectpicker('setStyle', 'btn-info', 'remove');

                $(this).selectpicker('setStyle', 'btn-danger');

                error = 1;

            } else

            {

                $(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');

                $(this).selectpicker('setStyle', 'btn-info');

                $(this).removeClass('campo-vacio');

            }

            $(this).selectpicker('refresh');

        });

        if (error == 0)

        {

            $('#modal_harvesting_checklist').modal('hide');

            plan_agregar_quimico_aplicacion_quimico(cod_aplicacion_quimico, codigo_plantacion);

            //plan_guardar_aplicar_quimico(cod_aplicacion_quimico, codigo_plantacion);

        } else

        {

            grl_mensaje('You must fill the empty fields', 'Debe llenar los campos vacíos', 'warning');

        }

    });



    $('#modal_aplicar_quimico').on('show.bs.modal', function(event) {

        /* Act on the event */

        jQuery.ajaxSetup({
            async: false
        });

        //usu_constructor_usuarios_por_cargos("2,3,5,7,16", 'cod_operador');

        //usu_constructor_usuarios_activos('cod_operador');/

        plan_constructor_listado_usuarios_por_finca($('#cod_info_empresa').val());

        inv_constructor_listado_unidades_medida();

        inv_constructor_listado_tipos_quimicos();

        bw_constructor_bloques_plantacion_todos('cod_bloques_aplicar_quimico', codigo_plantacion);

        inv_constructor_listado_tipos_aplicacion('cod_tipo_aplicacion');

        if (cod_aplicacion_quimico > 0)

        {

            $('#div_agregar_quimicos').removeClass('hide');

            $('#div_supervisor').addClass('hide');

            plan_listado_quimicos_aplicacion_quimico(cod_aplicacion_quimico, 'tbody_quimicos');

            $('html, body').animate({

                scrollTop: $("#table_historial_aplicaciones_plantacion").offset().top

            }, 2000);

        } else

        {

            $('#div_agregar_quimicos').addClass('hide');

            $('#div_supervisor').removeClass('hide');

        }

        $('.selectpicker').selectpicker('refresh');

        $('#cod_operador').selectpicker('val', '<?php echo $_SESSION['cod_usuario']; ?>');

        $('.selectpicker').selectpicker('refresh');

        var exists = false;

        $('#cod_operador option').each(function() {

            if (this.value == '<?php echo $_SESSION['cod_usuario']; ?>') {

                exists = true;

                return false;

            }

        });

        if (exists == true)

        {

            //$('#cod_operador').attr('disabled', 'disabled');

        }

        <?php

        /*if($_SESSION['cod_cargo'] == 2 || $_SESSION['cod_cargo'] == 5 || $_SESSION['cod_cargo'] == 7)

            {

                ?>

                $('#cod_operador').attr('disabled', 'disabled');

                <?php

            }*/

        ?>

        $('#div_cantidad_aplicada').addClass('hide');

        $("#loader").hide();

        $("#cuerpo").show();

        jQuery.ajaxSetup({
            async: true
        });

    });

    $('#btn_agregar_cantidad_aplicada').click(function(event) {

        /* Act on the event */

        console.log('codigo_detalle: ' + codigo_detalle);

        plan_ingresar_cantidad_aplicada_quimico(codigo_detalle, $('#cantidad_aplicada').val());

        $('#div_cantidad_aplicada').addClass('hide');

    });



    $('#btn_guardar_trasplantar').click(function(event) {

        /* Act on the event */

        var error = 0;

        $(".input.requerido_trasplantar").map(function() {

            if (!$(this).val())

            {

                error = 1;

                $(this).parent('div').addClass('has-error');

                return false;

            } else

            {

                $(this).parent('div').removeClass('has-error');

            }

        });

        $(".selectpicker.requerido_trasplantar").map(function() {

            if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '')

            {

                $(this).selectpicker('setStyle', 'btn-info', 'remove');

                $(this).selectpicker('setStyle', 'btn-danger');

                error = 1;

            } else

            {

                $(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');

                $(this).selectpicker('setStyle', 'btn-info');

                $(this).removeClass('campo-vacio');

            }

            $(this).selectpicker('refresh');

        });

        if (error == 0)

        {

            $('#modal_trasplantar').modal('hide');

            plan_guardar_trasplantar(codigo_plantacion);

            //plan_guardar_aplicar_quimico(cod_aplicacion_quimico, codigo_plantacion);

        } else

        {

            grl_mensaje('You must fill the empty fields', 'Debe llenar los campos vacíos', 'warning');

        }

    });



    $('#btn_excel').click(function(event) {

        /* Act on the event */

        x1 = "<?php echo $cod_plantacion; ?>";

        var url = "mod_plantaciones/funciones/plan_plantacion_excel.php?x1=" + x1;

        $(location).attr('href', url);

    });



    $('#btn_offline').click(function(event) {

        /* Act on the event */

        x1 = "<?php echo $cod_plantacion; ?>";

        window.open("mod_plantaciones/ui/plan_nueva_plantacion_offline.php?x1=" + x1, "_blank");

        /*var url = "mod_plantaciones/ui/plan_nueva_plantacion_offline.php?x1="+x1;

        $(location).attr('href',url);*/

    });



    $('#btn_actualizar_harvesting_worksheet').click(function(event) {

        /* Act on the event */

        var error = 0;

        $(".input.requerido_actualizar_harvesting_worksheet").map(function() {

            if (!$(this).val())

            {

                error = 1;

                $(this).parent('div').addClass('has-error');

                return false;

            } else

            {

                $(this).parent('div').removeClass('has-error');

            }

        });

        $(".selectpicker.requerido_actualizar_harvesting_worksheet").map(function() {

            if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '')

            {

                $(this).selectpicker('setStyle', 'btn-info', 'remove');

                $(this).selectpicker('setStyle', 'btn-danger');

                error = 1;

            } else

            {

                $(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');

                $(this).selectpicker('setStyle', 'btn-info');

                $(this).removeClass('campo-vacio');

            }

            $(this).selectpicker('refresh');

        });

        if (error == 0)

        {

            jQuery.ajaxSetup({
                async: false
            });

            $('#modal_actualizar_harvesting_worksheet').modal('hide');

            $('#modal_actualizar_harvesting_worksheet').on('hidden.bs.modal', function() {

                plan_actualizar_harvesting_worksheet($('#codigo_harvesting_worksheet').val());

            });

            //plan_guardar_aplicar_quimico(cod_aplicacion_quimico, codigo_plantacion);

            jQuery.ajaxSetup({
                async: true
            });

        } else

        {

            grl_mensaje('You must fill the empty fields', 'Debe llenar los campos vacíos', 'warning');

        }

    });

    $('#modal_trasplantar').on('show.bs.modal', function(event) {

        plan_constructor_listado_plantaciones('cod_plantacion');

        bw_constructor_bloques_plantacion_todos('cod_bloques_trasplante', codigo_plantacion);

    });

    $('#modal_semillas').on('show.bs.modal', function(event) {

        bw_constructor_bloques_plantacion('cod_bloques_plantacion', codigo_plantacion);

        inv_constructor_listado_semillas('cod_inventario_semilla', $('#cod_info_empresa').val());

        inv_constructor_listado_maquinarias_tipo_aplicacion('cod_inventario_maquinaria', $('#cod_info_empresa').val(), 4);

    });

    $('#modal_estado').on('show.bs.modal', function(event) {

        bw_constructor_estados_plantaciones();

    });

    $('#modal_load_report').on('show.bs.modal', function(event) {

        bw_constructor_bloques_plantacion_todos('cod_bloques_load_report', codigo_plantacion);

        plan_constructor_listado_semillas_plantacion('cod_inventario_load_report', codigo_plantacion);

        $('#total_temperature').trigger('focus');

    });





    $('#btn_guardar_load_report').click(function(event) {

        /* Act on the event */

        var error = 0;

        $(".input.requerido_load_report").map(function() {

            if (!$(this).val())

            {

                error = 1;

                $(this).parent('div').addClass('has-error');

                return false;

            } else

            {

                $(this).parent('div').removeClass('has-error');

            }

        });

        $(".selectpicker.requerido_load_report").map(function() {

            if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '')

            {

                $(this).selectpicker('setStyle', 'btn-info', 'remove');

                $(this).selectpicker('setStyle', 'btn-danger');

                error = 1;

            } else

            {

                $(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');

                $(this).selectpicker('setStyle', 'btn-info');

                $(this).removeClass('campo-vacio');

            }

            $(this).selectpicker('refresh');

        });

        if (error == 0)

        {

            $('#modal_load_report').modal('hide');

            plan_guardar_load_report_plantacion(codigo_plantacion, codigo_reporte);

            //plan_guardar_aplicar_quimico(cod_aplicacion_quimico, codigo_plantacion);

        } else

        {

            grl_mensaje('You must fill the empty fields', 'Debe llenar los campos vacíos', 'warning');

        }

    });





    $('#average_tote_weight').focus(function(event) {

        /* Act on the event */

        $(this).val($('#real_average_tote_weight').val());

    });



    $('#num_orden_compra').focus(function(event) {

        /* Act on the event */

        if ($(this).val() != '')

        {

            $(this).val('<?php echo $WORKSHEETS[0]['orden_compra']; ?>');

        }

    });



    $('#tlc').focus(function(event) {

        /* Act on the event */

        var mins = parseFloat(moment.utc(moment($('#time_receiving').val(), "HH:mm:ss").diff(moment($('#inicial_time_harvest').val(), "HH:mm:ss"))).format("mm"));

        var hours = parseFloat(moment.utc(moment($('#time_receiving').val(), "HH:mm:ss").diff(moment($('#inicial_time_harvest').val(), "HH:mm:ss"))).format("HH"));

        var tlc = parseFloat((hours + (mins / 60)) * $('#temperature_receiving').val());

        console.log('hours: ' + hours);

        console.log('mins: ' + mins);

        console.log('tlc: ' + tlc);

        $(this).val(tlc);

    });



    $('#vacuum_cooler').focus(function(event) {

        /* Act on the event */

        var mins = parseFloat(moment.utc(moment($('#time_pickup').val(), "HH:mm:ss").diff(moment($('#time_vacuum_cooler').val(), "HH:mm:ss"))).format("mm"));

        var hours = parseFloat(moment.utc(moment($('#time_pickup').val(), "HH:mm:ss").diff(moment($('#time_vacuum_cooler').val(), "HH:mm:ss"))).format("HH"));

        var vc = parseFloat(parseFloat(hours + (mins / 60)) + parseFloat($('#temperature_receiving').val()));

        console.log('hours: ' + hours);

        console.log('mins: ' + mins);

        console.log('vc: ' + vc);

        $(this).val(vc);

    });



    $('#total_temperature').focus(function(event) {

        /* Act on the event */

        $(this).val(parseFloat(parseFloat($('#tlc').val()) + parseFloat($('#vacuum_cooler').val())));

    });



    $('#btn_guardar_zona_rociado').click(function(event) {

        /* Act on the event */

        var error = 0;

        $(".input.requerido_zona_rociado").map(function() {

            if (!$(this).val())

            {

                error = 1;

                $(this).parent('div').addClass('has-error');

                return false;

            } else

            {

                $(this).parent('div').removeClass('has-error');

            }

        });

        $(".selectpicker.requerido_zona_rociado").map(function() {

            if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '')

            {

                $(this).selectpicker('setStyle', 'btn-info', 'remove');

                $(this).selectpicker('setStyle', 'btn-danger');

                error = 1;

            } else

            {

                $(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');

                $(this).selectpicker('setStyle', 'btn-info');

                $(this).removeClass('campo-vacio');

            }

            $(this).selectpicker('refresh');

        });

        if (error == 0)

        {
            console.log("Agregando datos");

            $('#modal_zona_rociado').modal('hide');

            grl_overlay_loading('');

            $('#modal_loading').modal('hide');

            $('#modal_loading').on('hidden.bs.modal', function() {

                plan_agregar_zona_rociado_plantacion(codigo_plantacion);
                // console.log("Aqui");
                // console.log($('#cod_quimico_rociadores option:selected').val());

                // console.log($('#cod_zona_rociado').val());

                // console.log($('#cod_bloques_rociado').val());

                // console.log($('#cod_tipo_zona').val());

                // console.log($('#cantidad_quimico').val());

                // console.log($('#cod_unidad_medida2 option:selected').val());

                // console.log($('#fecha_rociado').val());

            });

        } else

        {

            grl_mensaje('You must fill the empty fields', 'Debe llenar los campos vacíos', 'warning');

        }

    });
</script>



<body>

    <div id="overlay_loading"></div>

    <div id="message_box"></div>

    <div class="panel panel-default">

        <div class="panel-body">

            <div class="col-md-12">

                <div class="panel-header">

                    <h1><span class="translate" data-traducir_english="Information about planting" data-traducir_spanish="Información sobre la plantación">Información sobre la plantación </span><span class="badge estado1" id="badge_estado2">Waiting to save</span><span class="badge estado1" id="badge_estado">En espera de guardado</span></h1>

                </div>

            </div>

            <div class="col-md-12">

                <div class="row">

                    <div class="col-md-4">

                        <div class="form-group input-group-sm">

                            <label for="cod_info_empresa" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</label>

                            <select disable class="selectpicker show-menu-arrow requerido" title="Select" id="cod_info_empresa" name="cod_info_empresa">

                            </select>

                        </div>

                    </div>

                    <div class="col-md-2">

                        <div class="form-group input-group-sm">

                            <label for="anio_plantacion" class="translate" data-traducir_english="Year" data-traducir_spanish="Año">Año</label>

                            <input type="text" class="form-control anio input requerido" id="anio_plantacion" name="anio_plantacion">

                        </div>

                    </div>

                    <div class="col-md-2">

                        <div class="form-group input-group-sm">

                            <label for="num_plantacion" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</label>

                            <input type="text" class="form-control letras11 input requerido" id="num_plantacion" name="num_plantacion">

                        </div>

                    </div>

                    <div class='col-md-4'>

                        <div class="form-group">

                            <label for="fecha_plantacion_ejecutada" class="translate" data-traducir_english="Pre-Planting Date" data-traducir_spanish="Fecha Pre-Plantación Planeada">Fecha Pre-Plantación Planeada</label>

                            <div class='input-group input-group-sm fecha-planeada date' id='datetimepicker1'>

                                <span class="input-group-addon">

                                    <span class="fa fa-calendar"></span>

                                </span>

                                <input type='text' class="form-control input requerido" readonly="true" id="fecha_plantacion_ejecutada" />

                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class='col-md-4'>

                        <div class="form-group">

                            <label for="fecha_plantacion_planeada" class="translate" data-traducir_english="Planting Date" data-traducir_spanish="Fecha Plantación Planeada">Fecha Plantación Planeada</label>

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

                            <input type="text" class="form-control monto input" value="0" readonly="true" id="acres_plantados" name="acres_plantados">

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group input-group-sm" id="div_tipo_curso">

                            <label for="cod_temporada" class="translate" data-traducir_english="Season" data-traducir_spanish="Temporada">Temporada</label>

                            <select class="selectpicker show-menu-arrow requerido" title="Select" id="cod_temporada" name="cod_temporada">

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

                                                    <th width="15%" class="translate" data-traducir_english="Zone" data-traducir_spanish="Zona">Zona</th>

                                                    <th width="15%" class="translate" data-traducir_english="Block" data-traducir_spanish="Bloque">Bloque</th>

                                                    <th width="15%" class="translate" data-traducir_english="Acres" data-traducir_spanish="Acres">Acres</th>

                                                    <th width="15%" class="translate" data-traducir_english="Stage" data-traducir_spanish="Estado">Estado</th>

                                                    <!-- <th width="20%" class="translate" data-traducir_english="Reason" data-traducir_spanish="Motivo">Motivo</th> -->

                                                    <th width="20%" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Acciones</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                <?php

                                                if (count($BLOQUES)) {

                                                    $correlativo = 1;

                                                    $estados = [
                                                        'Waiting to save',

                                                        'Pre-Planted',

                                                        'Planted',

                                                        'Trimmed',

                                                        'Growing',

                                                        'Ready to harvest',

                                                        'Harvested',

                                                        'QA',

                                                        'Packaged product',

                                                        'Removed',

                                                        'Finished'
                                                    ];

                                                    foreach ($BLOQUES as $bloque) {

                                                ?>

                                                        <tr>

                                                            <td><?php echo $correlativo; ?></td>

                                                            <td><a role="button" onclick="plan_cargar_historial_bloque(<?php echo utf8_encode($bloque['cod_detalle']); ?>,'div_historial<?php echo $correlativo; ?>');" data-toggle="collapse" data-parent="#table-container" href="#collapse<?php echo $correlativo; ?>" aria-expanded="true" aria-controls="collapseOne" style="color: #000 !important;"><?php echo utf8_encode($bloque['zona']); ?></a></td>

                                                            <?php

                                                            if ($bloque['clave_bloque'] != '' && $bloque['clave_bloque'] != null) {

                                                            ?>

                                                                <td><?php echo utf8_encode($bloque['clave_bloque'] . '-' . $bloque['nombre_bloque']); ?></td>

                                                            <?php

                                                            } else {

                                                            ?>

                                                                <td><?php echo utf8_encode($bloque['nombre_bloque']); ?></td>

                                                            <?php

                                                            }

                                                            ?>

                                                            <td><?php echo utf8_encode($bloque['cantidad_acres']); ?></td>

                                                            <td><span class="badge estado<?php echo utf8_encode($bloque['cod_estado_plantacion']); ?>" id="badge_estado2"><?php echo utf8_encode($estados[$bloque['cod_estado_plantacion']]); ?></span><span class="badge estado<?php echo utf8_encode($bloque['cod_estado_plantacion']); ?>" id="badge_estado"><?php echo utf8_encode($bloque['estado_plantacion']); ?></span></td>

                                                            <!-- <td><?php echo utf8_encode($bloque['motivo_estado_plantacion']); ?></td> -->

                                                            <td align="center" style="display: flex;">

                                                                <!-- <div class="material-switch pull-right">

                                                                    <input class="checkbox_bloque" id="checkbox_<?php echo utf8_encode($bloque['cod_detalle']); ?>" data-id="<?php echo utf8_encode($bloque['cod_detalle']); ?>" name="checkbox_<?php echo utf8_encode($bloque['cod_detalle']); ?>" type="checkbox" <?php echo ($bloque['activo'] == 1 ? 'checked="checked"' : ''); ?>/>

                                                                    <label for="checkbox_<?php echo utf8_encode($bloque['cod_detalle']); ?>" class=""></label>

                                                                </div> -->

                                                                <?php /*if($bloque['cod_estado_plantacion'] < 6)

                                                                {

                                                                    ?>

                                                                    <!-- <a title="Apply Chemical - Aplicar Quimico" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_aplicar_quimico').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_operador btn-left btn-sm btn-primary"><i class="fa fa-flask"></i></a> -->

                                                                    <a title="Scouting - Explorar" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_explorar_plantacion').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-left btn-sm btn-success"><i class="fa fa-leaf"></i></a>

                                                                    <a title="Calibrate - Calibrar" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_calibrar').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_operador btn-center-left btn-sm btn-primary"><i class="fa fa-adjust"></i></a>

                                                                    <a title="Stage - Estado" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_estado').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_supervisor btn-center-right btn-sm btn-success"><i class="fa fa-cog"></i></a>

                                                                    <a title="Delete - Eliminar" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_eliminar_bloque').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="smooth-transition btn_administrador btn_supervisor btn-eliminar btn btn-sm btn_delete_file btn-right btn-danger"><i class="far fa-trash-alt"></i></a>

                                                                    <?php

                                                                }

                                                                else

                                                                {

                                                                    ?>

                                                                    <a title="Stage - Estado" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_estado').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_supervisor btn-left btn-sm btn-success"><i class="fa fa-cog"></i></a>

                                                                    <!-- <a title="Delete - Eliminar" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_eliminar_bloque').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="smooth-transition btn_administrador btn_supervisor btn-eliminar btn btn-sm btn_delete_file btn-right btn-danger"><i class="far fa-trash-alt"></i></a> -->

                                                                    <?php

                                                                }*/

                                                                switch ($bloque['cod_estado_plantacion']) {

                                                                    case 1:

                                                                    case 2:

                                                                    case 3:

                                                                    case 4: //CRECIMIENTO

                                                                ?>

                                                                        <a title="Cleaning and Sanitizing of harvesting equipment" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_cleaning_sanitizing').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_supervisor btn_administrador btn_explorador btn-left btn-sm btn-warning"><i class="fa fa-snowplow"></i></a>

                                                                        <a title="Scouting - Explorar" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_explorar_plantacion').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-center-left btn-sm btn-success"><i class="fa fa-leaf"></i></a>

                                                                        <a title="Calibrate - Calibrar" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_calibrar').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-center-right btn-sm btn-primary"><i class="fa fa-adjust"></i></a>

                                                                        <a title="Stage - Estado" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_estado').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_supervisor btn-right btn-sm btn-success"><i class="fa fa-cog"></i></a>

                                                                        <!-- <a title="Delete - Eliminar" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_eliminar_bloque').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="smooth-transition btn_administrador btn_supervisor btn-eliminar btn btn-sm btn_delete_file btn-right btn-danger"><i class="far fa-trash-alt"></i></a> -->

                                                                    <?php

                                                                        break;

                                                                    case 6: //COSECHADO

                                                                    ?>

                                                                        <a title="Cleaning and Sanitizing of harvesting equipment" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_cleaning_sanitizing').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_supervisor btn_administrador btn_explorador btn-left btn-sm btn-warning"><i class="fa fa-snowplow"></i></a>

                                                                        <a title="Harvesting Worksheet" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_harvesting_worksheet').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_supervisor btn_administrador btn_explorador btn-center-left btn-sm btn-default"><i class="fab fa-pagelines"></i></a>

                                                                        <a title="Scouting - Explorar" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_explorar_plantacion').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-center btn-sm btn-success"><i class="fa fa-leaf"></i></a>

                                                                        <a title="Calibrate - Calibrar" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_calibrar').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-center-right btn-sm btn-primary"><i class="fa fa-adjust"></i></a>

                                                                        <a title="Stage - Estado" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_estado').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_supervisor btn-right btn-sm btn-success"><i class="fa fa-cog"></i></a>

                                                                        <!-- <a title="Delete - Eliminar" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_eliminar_bloque').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="smooth-transition btn_administrador btn_supervisor btn-eliminar btn btn-sm btn_delete_file btn-right btn-danger"><i class="far fa-trash-alt"></i></a> -->

                                                                    <?php

                                                                        break;

                                                                    case 5: //LISTO PARA COSECHAR

                                                                    ?>

                                                                        <a title="Pre-Operational/Harvesting Checklist" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_harvesting_checklist').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_supervisor btn_administrador btn_explorador btn-left btn-sm btn-info"><i class="fa fa-list-ol"></i></a>

                                                                        <a title="Cleaning and Sanitizing of Harvesting Equipment" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_cleaning_sanitizing').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_supervisor btn_administrador btn_explorador btn-center-left btn-sm btn-warning"><i class="fa fa-snowplow"></i></a>

                                                                        <a title="Scouting - Explorar" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_explorar_plantacion').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-center btn-sm btn-success"><i class="fa fa-leaf"></i></a>

                                                                        <a title="Calibrate - Calibrar" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_calibrar').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-center-right btn-sm btn-primary"><i class="fa fa-adjust"></i></a>

                                                                        <a title="Stage - Estado" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_estado').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_supervisor btn-right btn-sm btn-success"><i class="fa fa-cog"></i></a>

                                                                        <!-- <a title="Delete - Eliminar" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_eliminar_bloque').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="smooth-transition btn_administrador btn_supervisor btn-eliminar btn btn-sm btn_delete_file btn-right btn-danger"><i class="far fa-trash-alt"></i></a> -->

                                                                    <?php

                                                                        break;

                                                                    case 9:

                                                                    case 10:

                                                                    ?>

                                                                        <a title="Stage - Estado" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_estado').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_supervisor btn-left btn-sm btn-success"><i class="fa fa-cog"></i></a>

                                                                    <?php

                                                                        break;

                                                                    default:

                                                                    ?>

                                                                        <a title="Stage - Estado" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_estado').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_supervisor btn-center btn-sm btn-success"><i class="fa fa-cog"></i></a>

                                                                        <!-- <a title="Delete - Eliminar" onclick="acres_plantados = <?php echo utf8_encode($bloque['cantidad_acres']); ?>;codigo_detalle = <?php echo utf8_encode($bloque['cod_detalle']); ?>;$('#modal_eliminar_bloque').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="smooth-transition btn_administrador btn_supervisor btn-eliminar btn btn-sm btn_delete_file btn-right btn-danger"><i class="far fa-trash-alt"></i></a> -->

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
                                                } else {

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

                                    <div class="row nomargin row-add-content" id="div_btn_add_zona">

                                        <div class="col-xs-12 text-center">

                                            <button type="button" id="btn_add_zona" class="btn btn-eliminar btn-sm btn-success truncated-text btn_add btn-guardar" data-action="true">

                                                <i class="fa fa-plus-circle"> </i>

                                                <span class="translate" data-traducir_english="Add Zone" data-traducir_spanish="Añadir Zona">Añadir Zona</span>

                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="busqueda_contenedor">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="panel panel-primary" id="panel_itemschecklist">

                                <div class="panel-heading btncollapsepaso panel_cabecera" id="zonas_rociado" role="button" data-toggle="collapse" href="#collapseitems_zonas_rociado" data-target="#collapseitems_zonas_rociado" aria-expanded="true" data-parent="#panel_itemschecklist" aria-controls="collapseitems_zonas_rociado">

                                    <h3 class="panel-title translate" data-traducir_english="Surrounding Areas" data-traducir_spanish="Zonas aledañas">Zonas aledañas</h3>

                                    <div class="pull-right">

                                        <span class="clickable filter" data-toggle="tooltip" title="Realizar busqueda" data-container="body">

                                            <i class="fa fa-search"></i>

                                        </span>

                                    </div>

                                </div>

                                <div class="panel-collapse collapse" id="collapseitems_zonas_rociado" role="tabpanel" aria-labelledby="collapseitems_zonas_rociado">

                                    <div class="panel-body panel_cuerpo">

                                        <input type="text" class="form-control" id="tabla_zonas_rociado-filter" data-action="filter" data-filters="#tabla_zonas_rociado" placeholder="Busqueda" />

                                    </div>

                                    <div class="responsive_table_container">

                                        <table class="table display row-border table-responsive" id="tabla_zonas_rociado">

                                            <thead>

                                                <tr class="active info">

                                                    <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>

                                                    <th width="15%" class="translate" data-traducir_english="Zones" data-traducir_spanish="Zonas">Zonas</th>

                                                    <th width="15%" class="translate" data-traducir_english="Blocks" data-traducir_spanish="Bloques">Bloques</th>

                                                    <th width="15%" class="translate" data-traducir_english="Area" data-traducir_spanish="Area">Area</th>

                                                    <th width="15%" class="translate" data-traducir_english="Chemical" data-traducir_spanish="Químico">Químico</th>

                                                    <th width="15%" class="translate" data-traducir_english="Quantity" data-traducir_spanish="Cantidad">Cantidad</th>

                                                    <th width="20%" class="translate" data-traducir_english="Unit of Measure" data-traducir_spanish="Unidad Medida">Unidad Medida</th>

                                                    <th width="20%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>

                                                    <th width="20%" class="translate" data-traducir_english="System Date" data-traducir_spanish="Fecha Sistema">Fecha Sistema</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                <?php

                                                if (count($ZONAS_ROCIADAS) > 0) {

                                                    $correlativo = 1;

                                                    foreach ($ZONAS_ROCIADAS as $zona) {

                                                ?>

                                                        <tr>

                                                            <td><?php echo $correlativo; ?></td>

                                                            <td><?php echo utf8_encode($zona['zonas']); ?></td>

                                                            <td><?php echo utf8_encode($zona['bloques']); ?></td>

                                                            <td><?php echo utf8_encode($zona['zonas_rociado']); ?></td>

                                                            <td><?php echo utf8_encode($zona['nombre_quimico']); ?></td>

                                                            <td><?php echo utf8_encode($zona['cantidad_quimico']); ?></td>

                                                            <td><?php echo utf8_encode($zona['unidad_medida']); ?></td>

                                                            <td><?php echo utf8_encode($zona['nombre_usuario']); ?></td>

                                                            <td><?php echo utf8_encode($zona['date_insert']); ?></td>

                                                        </tr>

                                                <?php

                                                        $correlativo++;
                                                    }
                                                }

                                                ?>

                                            </tbody>

                                        </table>

                                    </div>

                                    <div class="row nomargin row-add-content" id="div_btn_add_zona">

                                        <div class="col-xs-12 text-center">

                                            <button type="button" id="btn_add_zona_rociado" class="btn btn-eliminar btn-sm btn-success truncated-text btn_add btn-guardar" data-action="true">

                                                <i class="fa fa-plus-circle"> </i>

                                                <span class="translate" data-traducir_english="Add Chemical to Areas" data-traducir_spanish="Añadir Químico a Areas">Añadir Químico a Areas</span>

                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="busqueda_contenedor">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="panel panel-primary" id="panel_semillas">

                                <div class="panel-heading btncollapsepaso panel_cabecera" id="a_semillas" role="button" data-toggle="collapse" href="#collapsesemillas" aria-expanded="true" data-parent="#accordion" aria-controls="collapsesemillas">

                                    <h3 class="panel-title translate" data-traducir_english="Seed Planted" data-traducir_spanish="Semillas Plantadas">Semillas Plantadas</h3>

                                    <div class="pull-right">

                                        <span class="clickable filter" data-toggle="tooltip" title="Realizar busqueda" data-container="body">

                                            <i class="fa fa-search"></i>

                                        </span>

                                    </div>

                                </div>

                                <div class="panel-collapse collapse" id="collapsesemillas" role="tabpanel" aria-labelledby="collapsesemillas">

                                    <div class="panel-body panel_cuerpo">

                                        <input type="text" class="form-control" id="tabla_semillas-filter" data-action="filter" data-filters="#tabla_semillas" placeholder="Busqueda" />

                                    </div>

                                    <div class="responsive_table_container">

                                        <table class="table display row-border responsive" id="tabla_semillas">

                                            <thead>

                                                <tr class="active info">

                                                    <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>

                                                    <th width="15%" class="translate" data-traducir_english="Seed" data-traducir_spanish="Semilla">Semilla</th>

                                                    <th width="5%" class="translate" data-traducir_english="Lot No." data-traducir_spanish="Nro. Lote">Nro. Lote</th>

                                                    <th width="10%" class="translate" data-traducir_english="Used Amount" data-traducir_spanish="Cantidad Usada">Cantidad Usada</th>

                                                    <th width="15%" class="translate" data-traducir_english="Blocks" data-traducir_spanish="Bloques">Bloques</th>

                                                    <th width="15%" class="translate" data-traducir_english="Machinery" data-traducir_spanish="Maquinaria">Maquinaria</th>

                                                    <th width="30%" class="translate" data-traducir_english="Description" data-traducir_spanish="Descripción">Descripción</th>

                                                    <th width="15%" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Acciones</th>

                                                </tr>

                                            </thead>

                                            <tbody id="tbody_semillas">

                                                <?php

                                                if (count($PLANTADOS)) {

                                                    $correlativo = 1;

                                                    foreach ($PLANTADOS as $plantado) {

                                                ?>

                                                        <tr>

                                                            <td><?php echo $correlativo; ?></td>

                                                            <td><?php echo utf8_encode($plantado['nombre_semilla']); ?></td>

                                                            <td><?php echo utf8_encode($plantado['numero_lote']); ?></td>

                                                            <td><?php echo utf8_encode($plantado['cantidad_usada']); ?></td>

                                                            <td><?php echo utf8_encode($plantado['bloques']); ?></td>

                                                            <td><?php echo utf8_encode($plantado['maquinarias']); ?></td>

                                                            <td><?php echo utf8_encode($plantado['descripcion']); ?></td>

                                                            <td align="center" style="display: flex;">

                                                                <!-- <a title="Delete - Eliminar" class="smooth-transition btn-eliminar btn btn-sm btn_delete_file btn-fullwidth btn-danger" ><i class="far fa-trash-alt"></i></a> -->

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

                                    <div class="row nomargin row-add-content" id="div_btn_add_semillas">

                                        <div class="col-xs-12 text-center">

                                            <button type="button" id="btn_add_semillas" class="btn btn-eliminar btn-sm btn-success truncated-text btn_add btn-guardar" data-action="true">

                                                <i class="fa fa-plus-circle"> </i>

                                                <span class="translate" data-traducir_english="Add Seeds" data-traducir_spanish="Añadir Semillas">Añadir Semillas</span>

                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                <div class="panel panel-default">

                    <div class="panel-body">

                        <div class="col-md-12">

                            <div class="panel-header">

                                <h1 class="translate" data-traducir_english="Forms" data-traducir_spanish="Formularios">Formularios</h1>

                            </div>

                        </div>

                        <div class="col-md-12" id="div_formularios">

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

                                        <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#table_historial_estados_plantacion,#table_historial_calibraciones_plantacion,#table_historial_aplicaciones_plantacion,#table_historial_exploraciones,#table_historial_limpiezas,#table_historial_harvesting_worksheet,#table_historial_harvesting_checklist,#table_historial_trasplantes" placeholder="Busqueda" />

                                    </div>

                                    <!-- <div class="responsive_table_container">

                                            <table class="table display row-border responsive" id="tabla_historial">

                                                <thead>

                                                     <tr class="active info">

                                                        <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>

                                                        <th width="20%" class="translate" data-traducir_english="Previous Stage" data-traducir_spanish="Estado Anterior">Estado Anterior</th>

                                                        <th width="20%" class="translate" data-traducir_english="Actual Stage" data-traducir_spanish="Estado Actual">Estado Actual</th>

                                                        <th width="30%" class="translate" data-traducir_english="Description" data-traducir_spanish="Descripción">Descripción</th>

                                                        <th width="15%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>

                                                        <th width="15%" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</th>

                                                     </tr>

                                                </thead>

                                                <tbody id="tbody_historial_plantacion">

                                                </tbody>

                                             </table>

                                        </div> -->

                                    <?php

                                    if (count($CAMBIOS)) {

                                    ?>

                                        <h3 class="translate" data-traducir_english="Stage changes" data-traducir_spanish="Cambios de estado">Cambios de estado</h3>

                                        <div class="responsive_table_container">

                                            <table class="table display row-border table-responsive" id="table_historial_estados_plantacion">

                                                <thead>

                                                    <tr class="active info">

                                                        <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>

                                                        <th width="25%" class="translate" data-traducir_english="Stage" data-traducir_spanish="Estado">Estado</th>

                                                        <th width="25%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>

                                                        <th width="25%" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</th>

                                                    </tr>

                                                </thead>

                                                <tbody>

                                                    <?php

                                                    $correlativo = 1;

                                                    foreach ($CAMBIOS as $cambio) {

                                                    ?>

                                                        <tr>

                                                            <td><?php echo $correlativo; ?></td>

                                                            <td><?php echo utf8_encode($cambio['estado_plantacion_english']); ?></td>

                                                            <td><?php echo utf8_encode($cambio['nombre_usuario']); ?></td>

                                                            <td><?php echo utf8_encode($cambio['date_update']); ?></td>

                                                        </tr>

                                                    <?php

                                                        $correlativo++;
                                                    }

                                                    ?>

                                                </tbody>

                                            </table>

                                        </div>

                                    <?php

                                    }

                                    ?>

                                    <?php

                                    if (count($CALIBRACIONES)) {

                                    ?>

                                        <h3 class="translate" data-traducir_english="Calibrations" data-traducir_spanish="Calibraciones">Calibraciones</h3>

                                        <div class="responsive_table_container">

                                            <table class="table display row-border table-responsive" id="table_historial_calibraciones_plantacion">

                                                <thead>

                                                    <tr class="active info">

                                                        <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>

                                                        <th width="15%" class="translate" data-traducir_english="Fertilizer" data-traducir_spanish="Fertilizante">Fertilizante</th>

                                                        <th width="20%" class="translate" data-traducir_english="Hours" data-traducir_spanish="Horas">Horas</th>

                                                        <th width="20%" class="translate" data-traducir_english="Observation" data-traducir_spanish="Observación">Observación</th>

                                                        <th width="20%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>

                                                        <th width="20%" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</th>

                                                        <th width="20%" class="translate" data-traducir_english="Entry" data-traducir_spanish="Ingreso">Ingreso</th>

                                                    </tr>

                                                </thead>

                                                <tbody>

                                                    <?php

                                                    $correlativo = 1;

                                                    foreach ($CALIBRACIONES as $calibracion) {

                                                    ?>

                                                        <tr>

                                                            <td><?php echo $correlativo; ?></td>

                                                            <td><?php echo utf8_encode($fertilizantes[$calibracion['cod_fertilizante']]); ?></td>

                                                            <td><?php echo utf8_encode($calibracion['horas_aplicacion_calibrar']); ?></td>

                                                            <td><?php echo utf8_encode($calibracion['observaciones_calibrar']); ?></td>

                                                            <td><?php echo utf8_encode($calibracion['nombre_ingresa']); ?></td>

                                                            <td><?php echo utf8_encode($calibracion['fecha_calibracion']); ?></td>

                                                            <td><?php echo utf8_encode($calibracion['date_insert']); ?></td>

                                                        </tr>

                                                    <?php

                                                        $correlativo++;
                                                    }

                                                    ?>

                                                </tbody>

                                            </table>

                                        </div>

                                    <?php

                                    }

                                    if (count($APLICACIONES)) {

                                    ?>

                                        <h3 class="translate" data-traducir_english="Chemical Applies" data-traducir_spanish="Aplicación químicos">Aplicación químicos</h3>

                                        <div class="responsive_table_container">

                                            <table class="table display row-border table-responsive" id="table_historial_aplicaciones_plantacion">

                                                <thead>

                                                    <tr class="active info">

                                                        <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>

                                                        <th width="10%" class="translate" data-traducir_english="Blocks" data-traducir_spanish="Bloques">Bloques</th>

                                                        <th width="10%" class="translate" data-traducir_english="Supervisor" data-traducir_spanish="Supervisor">Supervisor</th>

                                                        <th width="10%" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</th>

                                                        <th width="15%" class="translate" data-traducir_english="Machinery" data-traducir_spanish="Maquinaria">Maquinaria</th>

                                                        <th width="15%" class="translate" data-traducir_english="Chemicals" data-traducir_spanish="Químicos">Químicos</th>

                                                        <th width="20%" class="translate" data-traducir_english="Description" data-traducir_spanish="Descripción">Descripción</th>

                                                        <th width="10%" class="translate" data-traducir_english="Update by" data-traducir_spanish="Actualizado por">Actualizado por</th>

                                                        <th width="14%" class="translate" data-traducir_english="Update at" data-traducir_spanish="Actualizado">Actualizado</th>

                                                        <th width="10%" class="translate" data-traducir_english="Entry" data-traducir_spanish="Ingreso">Ingreso</th>

                                                        <th width="5%" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Acciones</th>

                                                    </tr>

                                                </thead>

                                                <tbody>

                                                    <?php

                                                    $correlativo = 1;

                                                    foreach ($APLICACIONES as $aplicacion) {
                                                        // $output = $aplicacion;
                                                        // if (is_array($output))
                                                        //     $output = implode(',', $output);
                                                        // if (isset($aplicacion['usuario_actualizacion'])) {
                                                        //     echo "<script>console.log('usuario_actualizacion Object: " . $aplicacion['usuario_actualizacion'] . "' );</script>";
                                                        // } else {

                                                        //     echo "<script>console.log('usuario_actualizacion Object: " . "Usuario no encontrado" . "' );</script>";
                                                        //     echo "<script>console.log('Lo que hay es: " . $aplicacion['usuario_actualizacion'] . "' );</script>";
                                                        // }
                                                    ?>

                                                        <tr>

                                                            <td><?php echo $correlativo; ?></td>

                                                            <td><?php echo utf8_encode($aplicacion['bloques']); ?></td>

                                                            <td><?php echo utf8_encode($aplicacion['usuario_supervisor']); ?></td>

                                                            <td><?php echo utf8_encode($aplicacion['fecha_aplicacion_supervisor']); ?></td>

                                                            <td><?php echo utf8_encode($aplicacion['nombre_maquinaria']); ?></td>

                                                            <td><?php echo utf8_encode($aplicacion['nombre_quimicos']); ?></td>

                                                            <td><?php echo utf8_encode($aplicacion['descripcion_aplicar_quimico']); ?></td>

                                                            <td><?php
                                                                // echo utf8_encode($aplicacion['actualizacion']);
                                                                if (isset($aplicacion['usuario_actualizacion'])) {
                                                                    echo utf8_encode($aplicacion['usuario_actualizacion']);
                                                                } else {
                                                                    echo utf8_encode('N/D');
                                                                }

                                                                ?></td>

                                                            <td><?php echo utf8_encode($aplicacion['actualizacion']); ?></td>

                                                            <td><?php echo utf8_encode($aplicacion['date_insert']); ?></td>

                                                            <td>

                                                                <button onclick="plan_cargar_modal_aplicar_quimico(<?php echo utf8_encode($aplicacion['cod_aplicacion']); ?>)" class="btn btn-sm btn-primary" type="button"><i class="fa fa-list"></i></button>

                                                                <?php

                                                                if ($aplicacion['contador_quimicos'] == 0) {

                                                                ?>

                                                                    <button onclick="plan_eliminar_aplicar_quimico(<?php echo utf8_encode($aplicacion['cod_aplicacion'] . ',' . $aplicacion['cod_plantacion']); ?>)" class="btn btn-sm btn-danger" type="button"><i class="far fa-trash-alt"></i></button>

                                                                <?php

                                                                }

                                                                ?>

                                                            </td>

                                                        </tr>

                                                    <?php

                                                        $correlativo++;
                                                    }

                                                    ?>

                                                </tbody>

                                            </table>

                                        </div>

                                    <?php

                                    }

                                    if (count($EXPLORACIONES)) {

                                    ?>

                                        <h3 class="translate" data-traducir_english="Explorations" data-traducir_spanish="Exploraciones">Exploraciones</h3>

                                        <div class="responsive_table_container">

                                            <table class="table display row-border table-responsive" id="table_historial_exploraciones">

                                                <thead>

                                                    <tr class="active info">

                                                        <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>

                                                        <th width="10%" class="translate" data-traducir_english="Growth stage" data-traducir_spanish="Etapa crecimiento">Etapa crecimiento</th>

                                                        <th width="15%" class="translate" data-traducir_english="Insects" data-traducir_spanish="Insectos">Insectos</th>

                                                        <th width="15%" class="translate" data-traducir_english="Diseases" data-traducir_spanish="Enfermedades">Enfermedades</th>

                                                        <th width="15%" class="translate" data-traducir_english="Weeds" data-traducir_spanish="Malas hierbas">Malas hierbas</th>

                                                        <th width="15%" class="translate" data-traducir_english="Others" data-traducir_spanish="Otros">Otros</th>

                                                        <th width="20%" class="translate" data-traducir_english="Observation" data-traducir_spanish="Observación">Observación</th>

                                                        <th width="15%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>

                                                        <th width="10%" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</th>

                                                        <th width="10%" class="translate" data-traducir_english="Entry" data-traducir_spanish="Ingreso">Ingreso</th>

                                                        <th width="5%" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Acciones</th>

                                                    </tr>

                                                </thead>

                                                <tbody>

                                                    <?php

                                                    $correlativo = 1;

                                                    foreach ($EXPLORACIONES as $exploracion) {

                                                    ?>

                                                        <tr>

                                                            <td><?php echo $correlativo; ?></td>

                                                            <td><?php echo utf8_encode($etapas[$exploracion['etapa_crecimiento']]); ?></td>

                                                            <td>

                                                                Worms - Gusanos: <?php echo utf8_encode($exploracion['gusanos']); ?><br>

                                                                Eggs - Huevos: <?php echo utf8_encode($exploracion['huevos']); ?><br>

                                                                Leaf Hoppers - Saltahojas: <?php echo utf8_encode($exploracion['saltahojas']); ?><br>

                                                                Aphids - Afidos: <?php echo utf8_encode($exploracion['afidos']); ?><br>

                                                                Stink Bugs - Chinches: <?php echo utf8_encode($exploracion['chinches']); ?><br>

                                                                Gnats - Moscos: <?php echo utf8_encode($exploracion['moscos']); ?><br>

                                                                Flea Beetles - Escarabajos: <?php echo utf8_encode($exploracion['escarabajos']); ?><br>

                                                                Cyclaman Mites - Ácaros del ciclamen: <?php echo utf8_encode($exploracion['acaros']); ?><br>

                                                                Spidermites - Ácaros rojos: <?php echo utf8_encode($exploracion['spidermites']); ?><br>

                                                                Mealybugs - Cochinillas: <?php echo utf8_encode($exploracion['mealybugs']); ?><br>

                                                                Thrips - Trips: <?php echo utf8_encode($exploracion['thrips']); ?><br>

                                                            </td>

                                                            <td>

                                                                Cercospora Leaf Spot: <?php echo utf8_encode($exploracion['cercospora_leaf_spot']); ?><br>

                                                                Pythium/Damp Off: <?php echo utf8_encode($exploracion['pythium']); ?><br>

                                                                Rhizoctonia Aerial Blight: <?php echo utf8_encode($exploracion['rhizoctonia']); ?><br>

                                                                Bacteria: <?php echo utf8_encode($exploracion['bacteria']); ?><br>

                                                                Sclerotinia: <?php echo utf8_encode($exploracion['sclerotinia']); ?><br>

                                                                Alternaria Specks: <?php echo utf8_encode($exploracion['alternaria_specks']); ?><br>

                                                                Mildew: <?php echo utf8_encode($exploracion['mildew']); ?><br>

                                                                Virus: <?php echo utf8_encode($exploracion['virus']); ?><br>

                                                                White Rust: <?php echo utf8_encode($exploracion['white_rust']); ?><br>

                                                                Salt Accumulation: <?php echo utf8_encode($exploracion['salt_accumulation']); ?><br>

                                                            </td>

                                                            <td>

                                                                Dollarweed: <?php echo utf8_encode($exploracion['dolar']); ?><br>

                                                                Frogs Bit: <?php echo utf8_encode($exploracion['frogs_bit']); ?><br>

                                                                Mud Plantain: <?php echo utf8_encode($exploracion['plantas_lodo']); ?><br>

                                                                Tube Weed: <?php echo utf8_encode($exploracion['tripa_pollo']); ?><br>

                                                                Grass: <?php echo utf8_encode($exploracion['zacate']); ?><br>

                                                                Nutsedge: <?php echo utf8_encode($exploracion['nutsedge']); ?><br>

                                                            </td>

                                                            <td>

                                                                Damaged Leaves: <?php echo utf8_encode($exploracion['hojas_danadas']); ?><br>

                                                                Purple Stem: <?php echo utf8_encode($exploracion['tallos_purpuras']); ?><br>

                                                                Watercress rooted: <?php echo utf8_encode($exploracion['berro_enraizado']); ?><br>

                                                                Buds: <?php echo utf8_encode($exploracion['buds']); ?><br>

                                                                Zizgat Stems: <?php echo utf8_encode($exploracion['zigzag_stems']); ?><br>

                                                                Nutrient Deficiency: <?php echo utf8_encode($exploracion['nutrient_deficiency']); ?><br>

                                                                Rround Up: <?php echo utf8_encode($exploracion['round_up']); ?><br>

                                                                Light Color: <?php echo utf8_encode($exploracion['light_color']); ?><br>

                                                            </td>

                                                            <td><?php echo utf8_encode($exploracion['observacion']); ?></td>

                                                            <td><?php echo utf8_encode($exploracion['nombre_usuario']); ?></td>

                                                            <td><?php echo utf8_encode($exploracion['fecha_exploracion']); ?></td>

                                                            <td><?php echo utf8_encode($exploracion['date_insert']); ?></td>

                                                            <td><button onclick="flag_exploracion= 1;cod_exploracion = <?php echo utf8_encode($exploracion['cod_exploracion']); ?>;plan_cargar_observaciones_exploracion(flag_exploracion,cod_exploracion, 'div_observaciones_exploracion');plan_cargar_adjuntos_exploracion(flag_exploracion,cod_exploracion, 'div_adjuntos_exploracion');$('#modal_observaciones_exploracion').modal('show');" class="btn btn-sm btn-primary" type="button"><i class="fa fa-list"></i></button></td>

                                                        </tr>

                                                    <?php

                                                        $correlativo++;
                                                    }

                                                    ?>

                                                </tbody>

                                            </table>

                                        </div>

                                    <?php

                                    }

                                    ?>



                                    <?php

                                    if (count($FORMULARIOS)) {

                                        $correlativo = 1;

                                        $cod_formulario = $FORMULARIOS[0]['cod_formulario'];

                                        $user_insert = utf8_encode($FORMULARIOS[0]['user_insert']);

                                    ?>

                                        <div class="row">

                                            <div class="col-md-12">

                                                <h3><?php echo utf8_encode($FORMULARIOS[0]['nombre_formulario']); ?>

                                                    <small><strong>User:</strong> <?php echo utf8_encode($FORMULARIOS[0]['nombre_usuario']); ?></small>

                                                    <small><strong>Date:</strong> <?php echo utf8_encode($FORMULARIOS[0]['date_insert']); ?></small>

                                                </h3>

                                            </div>

                                        </div>

                                        <div class="responsive_table_container">

                                            <div class="row">

                                                <div class="col-md-12">

                                                    <div class="responsive_table_container">

                                                        <table class="table display row-border responsive">

                                                            <thead>

                                                                <tr class="active info">

                                                                    <th width="10%" class="translate" data-traducir_english="No" data-traducir_spanish="Nro.">Nro.</th>

                                                                    <th width="25%" class="translate" data-traducir_english="Item Name" data-traducir_spanish="Nombre Item">Nombre Item</th>

                                                                    <th width="25%" class="translate" data-traducir_english="Item Text" data-traducir_spanish="Texto Item">Texto Item</th>

                                                                    <th width="25%" class="translate" data-traducir_english="Item Value" data-traducir_spanish="Valor Item">Valor Item</th>

                                                                    <th width="15%" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</th>

                                                                </tr>

                                                            </thead>

                                                            <tbody>

                                                                <?php

                                                                foreach ($FORMULARIOS as $formulario) {

                                                                    $cierre = 0;

                                                                    if ($user_insert != $formulario['user_insert'] || $cod_formulario != $formulario['cod_formulario']) {

                                                                ?>

                                                            </tbody>

                                                        </table>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-md-12">

                                                <h3><?php echo utf8_encode($formulario['nombre_formulario']); ?>

                                                    <small><strong>User:</strong> <?php echo utf8_encode($formulario['nombre_usuario']); ?></small>

                                                    <small><strong>Date:</strong> <?php echo utf8_encode($formulario['date_insert']); ?></small>

                                                </h3>

                                            </div>

                                        </div>

                                        <div class="responsive_table_container">

                                            <div class="row">

                                                <div class="col-md-12">

                                                    <div class="">

                                                        <table class="table display row-border responsive">

                                                            <thead>

                                                                <tr class="active info">

                                                                    <th width="10%" class="translate" data-traducir_english="No" data-traducir_spanish="Nro.">Nro.</th>

                                                                    <th width="25%" class="translate" data-traducir_english="Item Name" data-traducir_spanish="Nombre Item">Nombre Item</th>

                                                                    <th width="25%" class="translate" data-traducir_english="Item Text" data-traducir_spanish="Texto Item">Texto Item</th>

                                                                    <th width="25%" class="translate" data-traducir_english="Item Value" data-traducir_spanish="Valor Item">Valor Item</th>

                                                                    <th width="15%" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</th>

                                                                </tr>

                                                            </thead>

                                                            <tbody>

                                                            <?php

                                                                        $cod_formulario = $formulario['cod_formulario'];

                                                                        $user_insert = utf8_encode($formulario['user_insert']);

                                                                        $cierre = 1;

                                                                        $correlativo = 1;
                                                                    }

                                                            ?>

                                                            <tr>

                                                                <td><?php echo $correlativo; ?></td>

                                                                <td><?php echo utf8_encode($formulario['nombre_item']); ?></td>

                                                                <?php

                                                                    if ($formulario['cod_tipo_item'] == 1) {

                                                                ?>

                                                                    <td><?php echo utf8_encode($formulario['texto_item']); ?></td>

                                                                    <td><?php echo utf8_encode($formulario['observacion'] == 1 ? 'Yes' : 'No'); ?></td>

                                                                <?php

                                                                    }

                                                                    if ($formulario['cod_tipo_item'] == 2) {

                                                                ?>

                                                                    <td><?php echo utf8_encode($formulario['texto_item']); ?></td>

                                                                    <td><?php echo utf8_encode($formulario['observacion']); ?></td>

                                                                <?php

                                                                    }

                                                                    if ($formulario['cod_tipo_item'] == 3) {

                                                                ?>

                                                                    <td><?php echo utf8_encode($formulario['nombre_item']); ?></td>

                                                                    <td><?php echo utf8_encode($formulario['respuesta_selectpicker']); ?></td>

                                                                <?php

                                                                    }

                                                                    if ($formulario['cod_tipo_item'] == 4) {

                                                                ?>

                                                                    <td><?php echo utf8_encode($formulario['nombre_item']); ?></td>

                                                                    <td><a target="_blank" href="../../mod_plantaciones/adjuntos/<?php echo utf8_encode($formulario['observacion']); ?>" class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a></td>

                                                                <?php

                                                                    }

                                                                    if ($formulario['cod_tipo_item'] == 5) {

                                                                ?>

                                                                    <td><?php echo utf8_encode($formulario['texto_item']); ?></td>

                                                                    <td><?php echo utf8_encode($formulario['observacion']); ?></td>

                                                                <?php

                                                                    }

                                                                    if ($formulario['cod_tipo_item'] == 6) {

                                                                ?>

                                                                    <td><?php echo utf8_encode($formulario['texto_item']); ?></td>

                                                                    <td><?php echo utf8_encode($formulario['observacion']); ?></td>

                                                                <?php

                                                                    }

                                                                ?>

                                                                <td><?php echo utf8_encode($formulario['date_insert']); ?></td>

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

                                        <?php

                                        if (count($LIMPIEZAS)) {

                                        ?>

                                            <h3 class="translate" data-traducir_english="Cleaning and sanitizing of harvesting equipment" data-traducir_spanish="Limpieza y saneamiento de equipo de cosecha">Limpieza y saneamiento de equipo de cosecha</h3>

                                            <div class="responsive_table_container">

                                                <table class="table display row-border table-responsive" id="table_historial_limpiezas">

                                                    <thead>

                                                        <tr class="active info">

                                                            <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>

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

                                                            <th width="8%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>

                                                            <th width="8%" class="translate" data-traducir_english="Entry" data-traducir_spanish="Ingreso">Ingreso</th>

                                                        </tr>

                                                    </thead>

                                                    <tbody>

                                                        <?php

                                                        $correlativo = 1;

                                                        foreach ($LIMPIEZAS as $limpieza) {

                                                        ?>

                                                            <tr>

                                                                <td><?php echo $correlativo; ?></td>

                                                                <td><?php echo utf8_encode($limpieza['fecha_limpieza']); ?></td>

                                                                <td><?php echo utf8_encode($limpieza['vez_limpieza']); ?></td>

                                                                <td><?php echo utf8_encode($limpieza['equipo_limpieza']); ?></td>

                                                                <td><?php echo utf8_encode($limpieza['cleaning_tools'] == 1 ? 'Yes' : 'No'); ?></td>

                                                                <td><?php echo utf8_encode($limpieza['cleaning_potable_water'] == 1 ? 'Yes' : 'No'); ?></td>

                                                                <td><?php echo utf8_encode($limpieza['cleaning_detergent'] == 1 ? 'Yes' : 'No'); ?></td>

                                                                <td><?php echo utf8_encode($limpieza['scrubbing'] == 1 ? 'Yes' : 'No'); ?></td>

                                                                <td><?php echo utf8_encode($limpieza['rinse_potable_water'] == 1 ? 'Yes' : 'No'); ?></td>

                                                                <td><?php echo utf8_encode($limpieza['sanitizing_chlorine'] == 1 ? 'Yes' : 'No'); ?></td>

                                                                <td><?php echo utf8_encode($limpieza['post_sanitizing'] == 1 ? 'Yes' : 'No'); ?></td>

                                                                <td><?php echo utf8_encode($limpieza['nombre_usuario']); ?></td>

                                                                <td><?php echo utf8_encode($limpieza['date_insert']); ?></td>

                                                            </tr>

                                                        <?php

                                                            $correlativo++;
                                                        }

                                                        ?>

                                                    </tbody>

                                                </table>

                                            </div>

                                        <?php

                                        }

                                        if (count($WORKSHEETS)) {

                                        ?>

                                            <h3 class="translate" data-traducir_english="Harvesting Worksheet" data-traducir_spanish="Hoja de trabajo de la cosecha">Hoja de trabajo de la cosecha</h3>

                                            <div class="responsive_table_container">

                                                <table class="table display row-border table-responsive" id="table_historial_harvesting_worksheet">

                                                    <thead>

                                                        <tr class="active info">

                                                            <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>

                                                            <th width="12%" class="translate" data-traducir_english="Date" data-traducir_spanish="Date">Date</th>

                                                            <th width="12%" class="translate" data-traducir_english="PHI" data-traducir_spanish="PHI">PHI</th>

                                                            <th width="12%" class="translate" data-traducir_english="Loose" data-traducir_spanish="Loose">Loose</th>

                                                            <th width="12%" class="translate" data-traducir_english="Increment (Bunch-Cello)" data-traducir_spanish="Increment (Bunch-Cello)">Increment (Bunch-Cello)</th>

                                                            <th width="12%" class="translate" data-traducir_english="Area FINISHED" data-traducir_spanish="Area FINISHED">Area FINISHED</th>

                                                            <th width="12%" class="translate" data-traducir_english="Acres Harvested" data-traducir_spanish="Acres Harvested">Acres Harvested</th>

                                                            <th width="12%" class="translate" data-traducir_english="PO# Request" data-traducir_spanish="PO# Request">PO# Request</th>

                                                            <th width="12%" class="translate" data-traducir_english="Crop #" data-traducir_spanish="Crop #">Crop #</th>

                                                            <th width="12%" class="translate" data-traducir_english="Quantity Harvested" data-traducir_spanish="Quantity Harvested">Quantity Harvested</th>

                                                            <th width="12%" class="translate" data-traducir_english="Comments" data-traducir_spanish="Comments">Comments</th>

                                                            <th width="12%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>

                                                            <th width="12%" class="translate" data-traducir_english="Entry" data-traducir_spanish="Ingreso">Ingreso</th>

                                                            <th width="5%" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Acciones</th>

                                                        </tr>

                                                    </thead>

                                                    <tbody>

                                                        <?php

                                                        $correlativo = 1;

                                                        foreach ($WORKSHEETS as $worksheet) {

                                                        ?>

                                                            <tr>

                                                                <td><?php echo $correlativo; ?></td>

                                                                <td><?php echo utf8_encode($worksheet['harvest_date']); ?></td>

                                                                <td><?php echo utf8_encode($worksheet['phi'] == 1 ? 'Yes' : 'No'); ?></td>

                                                                <td><?php echo utf8_encode($worksheet['cellos'] == 1 ? 'Yes' : 'No'); ?></td>

                                                                <td><?php echo utf8_encode($loose_bunches[$worksheet['increment_bunch_cello']]); ?></td>

                                                                <td><?php echo utf8_encode($worksheet['area_finished'] == 1 ? 'Yes' : 'No'); ?></td>

                                                                <td><?php echo utf8_encode($worksheet['acres_harvested']); ?></td>

                                                                <td><?php echo utf8_encode($worksheet['orden_compra']); ?></td>

                                                                <td><?php echo utf8_encode($worksheet['crop_number']); ?></td>

                                                                <td><?php echo utf8_encode($worksheet['cantidad_cosechada']); ?></td>

                                                                <td><?php echo utf8_encode($worksheet['commments_harvesting_worksheet']); ?></td>

                                                                <td><?php echo utf8_encode($worksheet['nombre_usuario']); ?></td>

                                                                <td><?php echo utf8_encode($worksheet['date_insert']); ?></td>

                                                                <td align="center" style="display: flex;">

                                                                    <a title="Edit - Editar" onclick="plan_cargar_modal_worksheet_plantacion(<?php echo utf8_encode($worksheet['cod_formulario']); ?>);" data-cod_formulario="<?php echo utf8_encode($worksheet['cod_formulario']); ?>" class="btn btn-eliminar btn_administrador btn_supervisor btn-sm btn-success"><i class="fas fa-edit"></i></a>

                                                                    <a title="Delete - Eliminar" onclick="plan_eliminar_worksheet(<?php echo utf8_encode($worksheet['cod_formulario']); ?>);" data-cod_formulario="<?php echo utf8_encode($worksheet['cod_formulario']); ?>" class="btn btn-eliminar btn_administrador btn_supervisor btn-sm btn-danger"><i class="fas fa-trash"></i></a>

                                                                </td>

                                                            </tr>

                                                        <?php

                                                            $correlativo++;
                                                        }

                                                        ?>

                                                    </tbody>

                                                </table>

                                            </div>

                                        <?php

                                        }

                                        if (count($CHECKLISTS)) {

                                        ?>

                                            <h3 class="translate" data-traducir_english="Pre-Operational/Harvesting Checklist" data-traducir_spanish="Lista de items para revisar en la cosecha">Lista de items para revisar en la cosecha</h3>

                                            <div class="responsive_table_container">

                                                <table class="table display row-border table-responsive" id="table_historial_harvesting_checklist">

                                                    <thead>

                                                        <tr class="active info">

                                                            <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>

                                                            <th width="12%" class="translate" data-traducir_english="Date" data-traducir_spanish="Date">Date</th>

                                                            <th width="12%" class="translate" data-traducir_english="Loose Bunch" data-traducir_spanish="Suelto o manojo">Suelto o manojo</th>

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

                                                            <th width="12%" class="translate" data-traducir_english="Are haul trucks properly cleaned and, if necessary sanitized?" data-traducir_spanish="¿Se limpian adecuadamente los camiones  y, si es necesario, se desinfectan?">¿Se limpian adecuadamente los camiones y, si es necesario, se desinfectan?</th>

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

                                                            <th width="12%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>

                                                            <th width="12%" class="translate" data-traducir_english="Entry" data-traducir_spanish="Ingreso">Ingreso</th>

                                                        </tr>

                                                    </thead>

                                                    <tbody>

                                                        <?php

                                                        $correlativo = 1;

                                                        foreach ($CHECKLISTS as $checklist) {

                                                        ?>

                                                            <tr>

                                                                <td><?php echo $correlativo; ?></td>

                                                                <td><?php echo utf8_encode($checklist['fecha_checklist']); ?></td>

                                                                <td><?php echo utf8_encode($loose_bunches[$checklist['loose_bunches']]); ?></td>

                                                                <td><?php echo utf8_encode($conventional_organic[$checklist['conventional_organic']]); ?></td>

                                                                <td><?php echo utf8_encode($question[$checklist['question1']]); ?></td>

                                                                <td><?php echo utf8_encode($question[$checklist['question2']]); ?></td>

                                                                <td><?php echo utf8_encode($question[$checklist['question3']]); ?></td>

                                                                <td><?php echo utf8_encode($question[$checklist['question4']]); ?></td>

                                                                <td><?php echo utf8_encode($question[$checklist['question5']]); ?></td>

                                                                <td><?php echo utf8_encode($question[$checklist['question6']]); ?></td>

                                                                <td><?php echo utf8_encode($question[$checklist['question7']]); ?></td>

                                                                <td><?php echo utf8_encode($question[$checklist['question8']]); ?></td>

                                                                <td><?php echo utf8_encode($question[$checklist['question9']]); ?></td>

                                                                <td><?php echo utf8_encode($question[$checklist['question10']]); ?></td>

                                                                <td><?php echo utf8_encode($question[$checklist['question11']]); ?></td>

                                                                <td><?php echo utf8_encode($question[$checklist['question12']]); ?></td>

                                                                <td><?php echo utf8_encode($question[$checklist['question13']]); ?></td>

                                                                <td><?php echo utf8_encode($question[$checklist['question14']]); ?></td>

                                                                <td><?php echo utf8_encode($question[$checklist['question15']]); ?></td>

                                                                <td><?php echo utf8_encode($question[$checklist['question16']]); ?></td>

                                                                <td><?php echo utf8_encode($question[$checklist['question17']]); ?></td>

                                                                <td><?php echo utf8_encode($checklist['question18']); ?></td>

                                                                <td><?php echo utf8_encode($checklist['question19']); ?></td>

                                                                <td><?php echo utf8_encode($question[$checklist['question20']]); ?></td>

                                                                <td><?php echo utf8_encode($question[$checklist['question21']]); ?></td>

                                                                <td><?php echo utf8_encode($question[$checklist['question22']]); ?></td>

                                                                <td><?php echo utf8_encode($checklist['actions']); ?></td>

                                                                <td><?php echo utf8_encode($checklist['nombre_usuario']); ?></td>

                                                                <td><?php echo utf8_encode($checklist['date_insert']); ?></td>

                                                            </tr>

                                                        <?php

                                                            $correlativo++;
                                                        }

                                                        ?>

                                                    </tbody>

                                                </table>

                                            </div>

                                        <?php

                                        }

                                        if (count($TRASPLANTES)) {

                                        ?>

                                            <h3 class="translate" data-traducir_english="Transplants" data-traducir_spanish="Trasplantes">Trasplantes</h3>

                                            <div class="responsive_table_container">

                                                <table class="table display row-border table-responsive" id="table_historial_trasplantes">

                                                    <thead>

                                                        <tr class="active info">

                                                            <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>

                                                            <th width="12%" class="translate" data-traducir_english="Grower Send" data-traducir_spanish="Finca Envía">Finca Envía</th>

                                                            <th width="12%" class="translate" data-traducir_english="Grower Receive" data-traducir_spanish="Finca Recibe">Finca Recibe</th>

                                                            <th width="12%" class="translate" data-traducir_english="Blocks" data-traducir_spanish="Bloques">Bloques</th>

                                                            <th width="12%" class="translate" data-traducir_english="Amount" data-traducir_spanish="Cantidad">Cantidad</th>

                                                            <th width="12%" class="translate" data-traducir_english="Blocks" data-traducir_spanish="Bloques">Bloques</th>

                                                            <th width="12%" class="translate" data-traducir_english="Load Number" data-traducir_spanish="No. Carga">No. Carga</th>

                                                            <th width="12%" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</th>

                                                            <th width="12%" class="translate" data-traducir_english="Observation" data-traducir_spanish="Observación">Observación</th>

                                                            <th width="12%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>

                                                            <th width="12%" class="translate" data-traducir_english="Entry" data-traducir_spanish="Ingreso">Ingreso</th>

                                                        </tr>

                                                    </thead>

                                                    <tbody>

                                                        <?php

                                                        $correlativo = 1;

                                                        foreach ($TRASPLANTES as $trasplante) {

                                                        ?>

                                                            <tr>

                                                                <td><?php echo $correlativo; ?></td>

                                                                <td><?php echo utf8_encode($trasplante['nombre_empresa_envia'] . '-' . $trasplante['anio_plantacion_envia'] . '-' . $trasplante['num_plantacion_envia'] . '-' . $trasplante['codigo_temporada_envia']); ?></td>

                                                                <td><?php echo utf8_encode($trasplante['nombre_empresa_recibe'] . '-' . $trasplante['anio_plantacion_recibe'] . '-' . $trasplante['num_plantacion_recibe'] . '-' . $trasplante['codigo_temporada_recibe']); ?></td>

                                                                <td><?php echo utf8_encode($trasplante['bloques']); ?></td>

                                                                <td><?php echo utf8_encode($trasplante['cantidad']); ?></td>

                                                                <td><?php echo utf8_encode($trasplante['bloques_trasplante']); ?></td>

                                                                <td><?php echo utf8_encode($trasplante['numero_carga']); ?></td>

                                                                <td><?php echo utf8_encode($trasplante['fecha_trasplante']); ?></td>

                                                                <td><?php echo utf8_encode($trasplante['observacion']); ?></td>

                                                                <td><?php echo utf8_encode($trasplante['nombre_usuario']); ?></td>

                                                                <td><?php echo utf8_encode($trasplante['date_insert']); ?></td>

                                                            </tr>

                                                        <?php

                                                            $correlativo++;
                                                        }

                                                        ?>

                                                    </tbody>

                                                </table>

                                            </div>

                                        <?php

                                        }

                                        if (count($REPORTE_CARGA)) {

                                        ?>

                                            <h3 class="translate" data-traducir_english="Load Report" data-traducir_spanish="Reporte de Carga">Reporte de Carga</h3>

                                            <div class="responsive_table_container">

                                                <table class="table display row-border table-responsive" id="table_historial_load_report">

                                                    <thead>

                                                        <tr class="active info">

                                                            <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>

                                                            <th width="12%" class="translate" data-traducir_english="Product" data-traducir_spanish="Producto">Producto</th>

                                                            <th width="12%" class="translate" data-traducir_english="Order #" data-traducir_spanish="Nro. Orden">Nro. Orden</th>

                                                            <th width="12%" class="translate" data-traducir_english="Order Date" data-traducir_spanish="Fecha Orden">Fecha Orden</th>

                                                            <th width="12%" class="translate" data-traducir_english="Lot Code" data-traducir_spanish="Código Lote">Código Lote</th>

                                                            <th width="12%" class="translate" data-traducir_english="Ranch Lot Code" data-traducir_spanish="Código Lote de Rancho">Código Lote de Rancho</th>

                                                            <th width="12%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>

                                                            <th width="12%" class="translate" data-traducir_english="Entry" data-traducir_spanish="Ingreso">Ingreso</th>

                                                            <th width="5%" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Acciones</th>

                                                        </tr>

                                                    </thead>

                                                    <tbody>

                                                        <?php

                                                        $correlativo = 1;

                                                        foreach ($REPORTE_CARGA as $reporte) {

                                                        ?>

                                                            <tr>

                                                                <td><?php echo $correlativo; ?></td>

                                                                <td><?php echo utf8_encode($reporte['nombre_semilla']); ?></td>

                                                                <td><?php echo utf8_encode($reporte['num_orden_compra']); ?></td>

                                                                <td><?php echo utf8_encode($reporte['fecha_orden']); ?></td>

                                                                <td><?php echo utf8_encode($reporte['num_lote']); ?></td>

                                                                <td><?php echo utf8_encode($reporte['num_lote_ranch']); ?></td>

                                                                <td><?php echo utf8_encode($reporte['nombre_usuario']); ?></td>

                                                                <td><?php echo utf8_encode($reporte['date_insert']); ?></td>

                                                                <td align="center" style="display: flex;">

                                                                    <a title="Edit - Editar" onclick="plan_cargar_modal_load_repor_plantacion(<?php echo utf8_encode($reporte['cod_reporte']); ?>);" data-cod_reporte="<?php echo utf8_encode($reporte['cod_reporte']); ?>" class="btn btn-eliminar btn_administrador btn_supervisor btn-sm btn-success"><i class="fas fa-edit"></i></a>

                                                                </td>

                                                            </tr>

                                                        <?php

                                                            $correlativo++;
                                                        }

                                                        ?>

                                                    </tbody>

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

            </div>

            <?php

            /*IMPRIME EN PANTALLA LAS OBSERVACIONES INGRESADAS EN EL DOCUMENTO*/

            if (count($OBSERVACIONES) > 0) {

            ?>

                <div class="col-md-12">

                    <div class="row">

                        <div class="col-md-12">
                            <h2 class="translate" data-traducir_english="Observations" data-traducir_spanish="Observaciones">Observaciones</h2>
                        </div>

                    </div>



                    <div class="row" id="div_observaciones">

                        <div class="col-md-12">

                            <?php

                            foreach ($OBSERVACIONES as $observacion) {



                                $etiquetas  = '<span class="label label-info label-bordes-arriba label-nombre-' . $observacion['cod_estado'] . '">' . utf8_encode($observacion['nombre_usuario']) . '</span>'

                                    . '<span class="label label-info">Fecha: ' . date('Y-m-d', strtotime($observacion['date_insert'])) . '</span>'

                                    . '<span class="label label-info label-bordes-abajo">Hora: ' . date('h:i A', strtotime($observacion['date_insert'])) . '</span>';



                                if ($_SESSION['cod_usuario'] == $observacion['user_insert']) {

                            ?>

                                    <div class="row row-timeline">

                                        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 text-center">

                                            <p class="bubble-timeline">

                                                <span class="icon-timeline-container">

                                                    <img src="../../mod_admin_usuarios/fotos_usuarios/<?php echo utf8_encode($observacion['fotografia']); ?>" class="img-timeline">

                                                </span>

                                            </p>

                                        </div>

                                        <div class="col-xs-8 col-sm-10 col-md-10 col-lg-11 text-left container-post-timeline">

                                            <div class="row no-margin nivel nivel-<?php echo $observacion['cod_estado']; ?>">

                                                <div class="row row-label">

                                                    <div class="col-xs-12">

                                                        <?php echo $etiquetas; ?>

                                                    </div>

                                                </div>

                                                <div class="row row-label">

                                                    <div class="col-xs-12 texto-justificado">

                                                        <span class="texto-timeline"><?php echo utf8_encode($observacion['observacion']); ?></span>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                <?php

                                } else {

                                ?>

                                    <div class="row row-timeline">

                                        <div class="col-xs-8 col-sm-10 col-md-10 col-lg-11 text-left container-post-timeline-inverso">

                                            <div class="row no-margin nivel-inverso nivel-<?php echo $observacion['cod_estado']; ?>-inverso">

                                                <div class="row row-label">

                                                    <div class="col-xs-12">

                                                        <?php echo $etiquetas; ?>

                                                    </div>

                                                </div>

                                                <div class="row row-label">

                                                    <div class="col-xs-12 texto-justificado">

                                                        <span class="texto-timeline"><?php echo utf8_encode($observacion['observacion']); ?></span>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 text-center">

                                            <p class="bubble-timeline">

                                                <span class="icon-timeline-container">

                                                    <img src="../../mod_admin_usuarios/fotos_usuarios/<?php echo utf8_encode($observacion['fotografia']); ?>" class="img-timeline">

                                                </span>

                                            </p>

                                        </div>

                                    </div>

                            <?php

                                }
                            }

                            ?>

                        </div>

                    </div>

                </div>

            <?php

            }

            ?>

        </div>

    </div>

    </div>

    </div>



    <div class="modal fade modal_observaciones_exploracion" id="modal_observaciones_exploracion" tabindex="-1" role="dialog" aria-labelledby="ModalResponsables" aria-hidden="true">

        <div class="modal-dialog modal-success modal-lg">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                    <h4 class="modal-title"><span id="titulo_modal_responsables">Observaciones</span> <small class="subtitulo" id="subtitulo_modal_responsables"></small></h4>

                </div>

                <div class="modal-body">

                    <div id="div_observaciones_exploracion"></div>

                    <div id="div_adjuntos_exploracion"></div>

                    <div class="row">

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="observacion_exploracion" class="translate" data-traducir_english="Observation" data-traducir_spanish="Observación">Observación</label>

                            <div class="form-group show-tick">

                                <textarea maxlength="2500" align="left" style="height:100px; width:100%; resize: none;" class="form-control limpiar" rows="2" id="observacion_exploracion" style="width: 100%"></textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>

                    <button type="button" class="btn btn-sm btn-primary btn_guardar translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_guardar_observacion_exploracion">Guardar</button>

                </div>

            </div>

        </div>

    </div>





    <div class="modal fade modal_zona" id="modal_zona" tabindex="-1" role="dialog" aria-labelledby="ModalResponsables" aria-hidden="true">

        <div class="modal-dialog modal-success modal-md">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                    <h4 class="modal-title"><span id="titulo_modal_responsables" class="translate" data-traducir_english="Block" data-traducir_spanish="Bloque">Bloque</span> <small class="subtitulo" id="subtitulo_modal_responsables"></small></h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="cod_zona" class="translate" data-traducir_english="Zone" data-traducir_spanish="Zona">Zona</label>

                                <select class="selectpicker show-menu-arrow requerido_zona" title="Select" id="cod_zona" name="cod_zona">

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="cod_bloque" class="translate" data-traducir_english="Blocks" data-traducir_spanish="Bloques">Bloques</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_zona" multiple="multiple" data-actions-box="true" title="Select" id="cod_bloque" name="cod_bloque">

                                </select>

                                <button class="btn btn-info btn-all-stick check-all has-tooltip" data-id="cod_bloque" id="todos_bloques2" title="Seleccionar todos" data-toggle="tooltip" type="button">

                                    <i class="fa fa-check"></i>

                                </button>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="cantidad_acres" class="translate" data-traducir_english="Acres amount" data-traducir_spanish="Cantidad acres">Cantidad acres</label>

                                <input type="text" class="form-control monto input requerido_zona" readonly="true" id="cantidad_acres" name="cantidad_acres">

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>

                    <button type="button" class="btn btn-sm btn-primary btn_guardar translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_guardar_zona">Guardar</button>

                </div>

            </div>

        </div>

    </div>

    <!-- MODAL DE Zonas aledañas  -->
    <div class="modal fade modal_zona_rociado" id="modal_zona_rociado" tabindex="-1" role="dialog" aria-labelledby="ModalResponsables" aria-hidden="true">

        <div class="modal-dialog modal-success modal-md">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                    <h4 class="modal-title"><span id="titulo_modal_responsables" class="translate" data-traducir_english="Surrounding Areas" data-traducir_spanish="Zonas aledañas">Zonas aledañas</span> <small class="subtitulo" id="subtitulo_modal_responsables"></small></h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="cod_zona_rociado" class="translate" data-traducir_english="Zone" data-traducir_spanish="Zonas">Zonas</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_zona_rociado" multiple="multiple" data-actions-box="true" title="Select" id="cod_zona_rociado" name="cod_zona_rociado">

                                </select>

                                <button class="btn btn-info btn-all-stick check-all has-tooltip" data-id="cod_zona_rociado" id="todos_bloques3" title="Seleccionar todos" data-toggle="tooltip" type="button">

                                    <i class="fa fa-check"></i>

                                </button>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="cod_bloques_rociado" class="translate" data-traducir_english="Blocks" data-traducir_spanish="Bloques">Bloques</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow" multiple="multiple" data-actions-box="true" title="Select" id="cod_bloques_rociado" name="cod_bloques_rociado">

                                </select>

                                <button class="btn btn-info btn-all-stick check-all has-tooltip" data-id="cod_bloques_rociado" id="todos_bloques" title="Seleccionar todos" data-toggle="tooltip" type="button">

                                    <i class="fa fa-check"></i>

                                </button>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="cod_tipo_zona" class="translate" data-traducir_english="Area" data-traducir_spanish="Area">Area</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_zona_rociado" multiple="multiple" data-actions-box="true" title="Select" id="cod_tipo_zona" name="cod_tipo_zona">

                                    <option value="1">Ditches</option>

                                    <option value="2">Canals</option>

                                    <option value="3">Sprinkle Rows</option>

                                    <option value="4">Beds</option>

                                    <option value="5">Spots</option>

                                    <option value="6">Grass Outside Production Area</option>

                                </select>

                                <button class="btn btn-info btn-all-stick check-all has-tooltip" data-id="cod_tipo_zona" id="todos_bloques4" title="Seleccionar todos" data-toggle="tooltip" type="button">

                                    <i class="fa fa-check"></i>

                                </button>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="cod_tipo_quimico_rociadores" class="translate" data-traducir_english="Chemical Type" data-traducir_spanish="Tipo Químico">Tipo Químico</label>

                            <select class="selectpicker show-menu-arrow requerido_zona_rociado" title="Select" id="cod_tipo_quimico_rociadores" name="cod_tipo_quimico_rociadores">

                            </select>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="cod_quimico_rociadores" class="translate" data-traducir_english="Chemical" data-traducir_spanish="Químico">Químico</label>

                                <select class="selectpicker show-menu-arrow requerido_zona_rociado" title="Select" id="cod_quimico_rociadores" name="cod_quimico_rociadores">

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="cantidad_quimico" class="translate" data-traducir_english="Chemical amount" data-traducir_spanish="Cantidad químico">Cantidad químico</label>

                                <input type="text" class="form-control monto input requerido_zona_rociado" id="cantidad_quimico" name="cantidad_quimico">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <div class="form-group input-group-sm" id="div_tipo_curso">

                                <label for="cod_unidad_medida2">Unidad de Medida</label>

                                <select class="selectpicker show-menu-arrow requerido_zona_rociado" title="Select" id="cod_unidad_medida2" name="cod_unidad_medida2">

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="fecha_rociado" class="translate" data-traducir_english="Sprayed date" data-traducir_spanish="Fecha rociado">Fecha rociado</label>

                            <div class='input-group input-group-sm fecha-rociado datetime'>

                                <span class="input-group-addon">

                                    <span class="fa fa-calendar"></span>

                                </span>

                                <input type='text' class="form-control input requerido_zona_rociado" id="fecha_rociado" />

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>

                    <button type="button" class="btn btn-sm btn-primary btn_guardar translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_guardar_zona_rociado">Guardar</button>

                </div>

            </div>

        </div>

    </div>

    <div class="modal fade modal_semillas" id="modal_semillas" tabindex="-1" role="dialog" aria-labelledby="ModalResponsables" aria-hidden="true">

        <div class="modal-dialog modal-success modal-md">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                    <h4 class="modal-title"><span id="titulo_modal_responsables" class="translate" data-traducir_english="Seeds" data-traducir_spanish="Semillas">Semillas</span> <small class="subtitulo" id="subtitulo_modal_responsables"></small></h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="cod_bloques_plantacion" class="translate" data-traducir_english="Blocks" data-traducir_spanish="Bloques">Bloques</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_semilla" multiple="multiple" data-actions-box="true" title="Select" id="cod_bloques_plantacion" name="cod_bloques_plantacion">

                                </select>

                                <button class="btn btn-info btn-all-stick check-all has-tooltip" data-id="cod_bloques_plantacion" id="todos_bloques" title="Seleccionar todos" data-toggle="tooltip" type="button">

                                    <i class="fa fa-check"></i>

                                </button>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="acres_bloques" class="translate" data-traducir_english="Acres in blocks" data-traducir_spanish="Acres en bloques">Acres en bloques</label>

                                <input type="text" class="form-control monto input " disabled="disabled" id="acres_bloques" name="acres_bloques">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="cod_inventario_semilla" class="translate" data-traducir_english="Seed" data-traducir_spanish="Semilla">Semilla</label>

                                <select class="selectpicker show-menu-arrow requerido_semilla" title="Select" id="cod_inventario_semilla" name="cod_inventario_semilla">

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="cantidad_usada" class="translate" data-traducir_english="Used amount" data-traducir_spanish="Cantidad usada">Cantidad usada</label>

                                <input type="text" class="form-control monto input requerido_semilla" id="cantidad_usada" name="cantidad_usada">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="cod_inventario_maquinaria" class="translate" data-traducir_english="Machinery" data-traducir_spanish="Maquinaria">Maquinaria</label>

                                <select class="selectpicker show-menu-arrow " multiple="multiple" data-actions-box="true" title="Select" id="cod_inventario_maquinaria" name="cod_inventario_maquinaria">

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="descripcion_semilla" class="translate" data-traducir_english="Description" data-traducir_spanish="Descripción">Descripción</label>

                            <div class="form-group show-tick">

                                <textarea maxlength="2500" align="left" style="height:100px; width:100%; resize: none;" class="form-control limpiar requerido_semilla" rows="2" id="descripcion_semilla" style="width: 100%"></textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>

                    <button type="button" class="btn btn-sm btn-primary btn_guardar translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_guardar_semilla">Guardar</button>

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

                        <div id="div_supervisor">

                            <div class="col-xs-12">
                                <!-- <h3 class="translate" data-traducir_english="For Supervisor" data-traducir_spanish="Para Supervisor">Para Supervisor</h3> -->
                                <div class="col-md-12">
                                    <div class="form-group input-group-sm">
                                        <label for="usuario_finca_recomendado" class="translate" data-traducir_english="Recommended" data-traducir_spanish="Recomendado">Recomendado</label>
                                        <select class="selectpicker show-menu-arrow requerido_supervisor" title="Select" id="usuario_finca_recomendado" name="usuario_finca_recomendado">
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group input-group-sm col-xs-12">

                                <label for="cod_bloques_aplicar_quimico" class="translate" data-traducir_english="Blocks" data-traducir_spanish="Bloques">Bloques</label>

                                <div class="form-group show-tick">

                                    <select class="selectpicker show-menu-arrow requerido_aplicacion_supervisor" multiple="multiple" data-actions-box="true" title="Select" id="cod_bloques_aplicar_quimico" name="cod_bloques_aplicar_quimico">

                                    </select>

                                    <button class="btn btn-info btn-all-stick check-all has-tooltip" data-id="cod_bloques_aplicar_quimico" id="todos_bloques_aplicar_quimico" title="Seleccionar todos" data-toggle="tooltip" type="button">

                                        <i class="fa fa-check"></i>

                                    </button>

                                </div>

                            </div>

                            <div class="form-group input-group-sm col-xs-12">

                                <label for="fecha_aplicacion_supervisor" class="translate" data-traducir_english="Apply chemical date" data-traducir_spanish="Fecha aplicación">Fecha aplicación</label>

                                <div class='input-group input-group-sm fecha-planeada date' id='datetimepicker1'>

                                    <span class="input-group-addon">

                                        <span class="fa fa-calendar"></span>

                                    </span>

                                    <input type='text' class="form-control input requerido_aplicacion_supervisor" id="fecha_aplicacion_supervisor" />

                                </div>

                            </div>

                        </div>

                        <div id="div_agregar_quimicos" class="hide">

                            <div class="form-group input-group-sm col-xs-12" id="">

                                <label for="cod_tipo_quimico" class="translate" data-traducir_english="Chemical Type" data-traducir_spanish="Tipo Químico">Tipo Químico</label>

                                <select class="selectpicker show-menu-arrow requerido_agregar_quimico" title="Select" id="cod_tipo_quimico" name="cod_tipo_quimico">

                                </select>

                            </div>

                            <div class="form-group input-group-sm col-xs-12" id="">

                                <label for="cod_inventario_quimico" class="translate" data-traducir_english="Product" data-traducir_spanish="Producto">Producto</label>

                                <div class="form-group show-tick">

                                    <select class="selectpicker show-menu-arrow requerido_agregar_quimico" id="cod_inventario_quimico" name="cod_inventario_quimico" data-live-search="true" title="Select">

                                    </select>

                                </div>

                            </div>

                            <div class="form-group input-group-sm col-xs-12" id="">

                                <div class="form-group input-group-sm" id="div_tipo_curso">

                                    <label for="cod_unidad_medida" class="translate" data-traducir_english="Unit of Measure" data-traducir_spanish="Unidad de Medida">Unidad de Medida</label>

                                    <select class="selectpicker show-menu-arrow requerido_agregar_quimico" title="Select" id="cod_unidad_medida" name="cod_unidad_medida">

                                    </select>

                                </div>

                            </div>

                            <div class="form-group input-group-sm col-xs-12" id="">

                                <div class="form-group input-group-sm">

                                    <label for="cantidad_sugerida" class="translate" data-traducir_english="Suggested Amount" data-traducir_spanish="Cantidad Sugerida">Cantidad Sugerida</label>

                                    <input type="text" class="form-control monto input requerido_agregar_quimico" id="cantidad_sugerida" name="cantidad_sugerida">

                                </div>

                            </div>

                            <div class="form-group input-group-sm col-xs-4 col-xs-offset-4">

                                <div class="form-group input-group-sm">

                                    <button type="button" class="btn btn-sm btn-fullwidth btn-primary btn_guardar translate" data-traducir_english="Add" data-traducir_spanish="Agregar" id="btn_agregar_quimico">Agregar</button>

                                </div>

                            </div>

                            <div class="col-xs-12">

                                <div class="responsive_table_container">

                                    <table class="table display row-border responsive" id="tabla_quimicos">

                                        <thead>

                                            <tr class="active info">

                                                <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>

                                                <th width="15%" class="translate" data-traducir_english="Type" data-traducir_spanish="Tipo">Tipo</th>

                                                <th width="5%" class="translate" data-traducir_english="Chemycal" data-traducir_spanish="Químico">Químico</th>

                                                <th width="10%" class="translate" data-traducir_english="Unit of Measure" data-traducir_spanish="Unidad de Medida">Unidad de Medida</th>

                                                <th width="10%" class="translate" data-traducir_english="Application Rate" data-traducir_spanish="Rango de Aplicación">Rango de Aplicación</th>

                                                <th width="15%" class="translate" data-traducir_english="Suggested Amount" data-traducir_spanish="Cantidad Sugerida">Cantidad Sugerida</th>

                                                <th width="15%" class="translate" data-traducir_english="Applied Amount" data-traducir_spanish="Cantidad Aplicada">Cantidad Aplicada</th>

                                                <th width="15%" class="translate" data-traducir_english="Reason for Application" data-traducir_spanish="Razón de Aplicación">Razón de Aplicación</th>

                                                <th width="15%" class="translate" data-traducir_english="User Insert" data-traducir_spanish="Usuario">Usuario</th>

                                                <th width="15%" class="translate" data-traducir_english="Date Insert" data-traducir_spanish="Fecha">Fecha</th>

                                                <th width="15%" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Acciones</th>

                                            </tr>

                                        </thead>

                                        <tbody id="tbody_quimicos">

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                        <hr>

                        <div class="col-xs-12">

                            <h3 class="translate" data-traducir_english="For Operator" data-traducir_spanish="Para Operador">Para Operador</h3>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="cod_operador" class="translate" data-traducir_english="Operator" data-traducir_spanish="Operador">Operador</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_aplicacion_operador" id="cod_operador" name="cod_operador" data-live-search="true" title="Select">

                                    <option value="-b">Select</option>

                                    <option value="45">Antonio Reyes</option>

                                    <option value="34">Baldomero Garcia</option>

                                    <option value="64">Eduardo Garibay</option>

                                    <option value="15">Efrain Modesto</option>

                                    <option value="56">Enrique Flores</option>

                                    <option value="62">Fernando Vazquez</option>

                                    <option value="58">Gerardo Nieto</option>

                                    <option value="48">Gerardo Valdivia</option>

                                    <option value="19">Gustavo Trujillo</option>

                                    <option value="53">Heliodoro Gonzalez</option>

                                    <option value="61">Honorio Cano</option>

                                    <option value="55">Jaime Ramirez</option>

                                    <option value="18">Jeff Silano</option>

                                    <option value="33">Jesus Arellano</option>

                                    <option value="17">Jose Cardenas</option>

                                    <option value="13">Juan Gonzalez</option>

                                    <option value="20">Obdom Almanza </option>

                                    <option value="49">Pascual Rivera</option>

                                    <option value="63">Ramon Arreola</option>

                                    <option value="54">Ramon Villasenor</option>

                                    <option value="60">Raúl Pantoja</option>

                                    <option value="4">Rene Amezquita</option>

                                    <option value="46">Rodrigo Maldonado</option>

                                    <option value="57">Rodrigo Rios</option>

                                </select>

                            </div>

                        </div>

                        <div id="div_cantidad_aplicada" class="hide">

                            <div class="form-group input-group-sm col-xs-9">

                                <div class="form-group input-group-sm">

                                    <label for="cantidad_aplicada" class="translate" data-traducir_english="Applied Amount" data-traducir_spanish="Cantidad Aplicada">Cantidad Aplicada</label>

                                    <input type="text" class="form-control monto input" id="cantidad_aplicada" name="cantidad_aplicada">

                                </div>

                            </div>

                            <div class="input-group-sm col-xs-3">

                                <div class="form-group">

                                    <label for="">&nbsp;</label></br>

                                    <button type="button" class="btn btn-sm btn-primary btn_guardar translate" data-traducir_spanish="Guardar" data-traducir_english="Save" data-cod_detalle="0" id="btn_agregar_cantidad_aplicada">Guardar</button>

                                </div>

                            </div>

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

                                <input type="text" class="form-control monto input requerido_aplicacion_operador" id="viento" name="viento">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="temperatura" class="translate" data-traducir_english="Temperature (°F)" data-traducir_spanish="Temperatura (°F)">Temperatura (ºF)</label>

                                <input type="text" class="form-control monto input requerido_aplicacion_operador" id="temperatura" name="temperatura">

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

    <div class="modal fade modal_load_report" id="modal_load_report" tabindex="-1" role="dialog" aria-labelledby="ModalResponsables" aria-hidden="true">

        <div class="modal-dialog modal-success modal-lg">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                    <h4 class="modal-title"><span id="titulo_modal_responsables" class="translate" data-traducir_english="Load Report Assesment" data-traducir_spanish="Cargar Informe de Evaluación">Cargar Informe de Evaluación</span> <small class="subtitulo" id="subtitulo_modal_responsables"></small></h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-xs-12">

                            <h3 class="translate" data-traducir_english="Load Order Assesment" data-traducir_spanish="Evaluación de Orden de Carga">Evaluación de Orden de Carga</h3>

                        </div>

                        <!-- <div class="form-group input-group-sm col-xs-12">

                            <label for="cod_bloques_load_report" class="translate" data-traducir_english="Blocks" data-traducir_spanish="Bloques">Bloques</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_load_report" multiple="multiple" data-actions-box="true" title="Select" id="cod_bloques_load_report" name="cod_bloques_load_report">

                                </select>

                                <button class="btn btn-info btn-all-stick check-all has-tooltip" id="todos_bloques_aplicar_quimico" title="Seleccionar todos" data-toggle="tooltip" type="button">

                                    <i class="fa fa-check"></i>

                                </button>

                            </div>

                        </div> -->

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="cod_inventario_load_report" class="translate" data-traducir_english="Product" data-traducir_spanish="Producto">Producto</label>

                            <select class="selectpicker show-menu-arrow requerido_load_report" title="Select" id="cod_inventario_load_report" name="cod_inventario_load_report">

                            </select>

                        </div>

                        <!-- <div class="form-group input-group-sm col-xs-12" id="">

                            <div class="form-group input-group-sm">

                                <label for="num_orden_compra" class="translate" data-traducir_english="Order #" data-traducir_spanish="No. Orden Compra">No. Orden Compra</label>

                                <input type="text"class="form-control letras10 input requerido_load_report" id="num_orden_compra" name="num_orden_compra">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="fecha_orden" class="translate" data-traducir_english="Order Date" data-traducir_spanish="Fecha Orden">Fecha Orden</label>

                            <div class='input-group input-group-sm fecha-planeada date' id='datetimepicker1'>

                                <span class="input-group-addon">

                                    <span class="fa fa-calendar"></span>

                                </span>

                                <input type='text' class="form-control input requerido_load_report" id="fecha_orden" />

                            </div>

                        </div> -->

                        <!-- <div class="form-group input-group-sm col-xs-12" id="">

                            <div class="form-group input-group-sm">

                                <label for="num_lote" class="translate" data-traducir_english="Lot Code" data-traducir_spanish="Codigo Lote">Codigo Lote</label>

                                <input type="text"class="form-control letras10 input requerido_load_report" id="num_lote" name="num_lote">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <div class="form-group input-group-sm">

                                <label for="num_lote_ranch" class="translate" data-traducir_english="Ranch Lot Code" data-traducir_spanish="Codigo Lote Rancho">Codigo Lote Rancho</label>

                                <input type="text"class="form-control letras10 input requerido_load_report" id="num_lote_ranch" name="num_lote_ranch">

                            </div>

                        </div> -->



                        <div class="col-xs-12">

                            <h3 class="translate" data-traducir_english="Pre-harvest Assessment" data-traducir_spanish="Pre-Asignación de cosecha">Pre-Asignación de cosecha</h3>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <div class="form-group input-group-sm">

                                <label for="inicial_size_harvest" class="translate" data-traducir_english="Min Size" data-traducir_spanish="Tamaño inicial a cosecha">Tamaño inicial a cosecha</label>

                                <input type="text" class="form-control decimal6_2 input requerido_load_report" id="inicial_size_harvest" name="inicial_size_harvest">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <div class="form-group input-group-sm">

                                <label for="final_size_harvest" class="translate" data-traducir_english="Max Size" data-traducir_spanish="Tamaño final a cosecha">Tamaño final a cosecha</label>

                                <input type="text" class="form-control decimal6_2 input requerido_load_report" id="final_size_harvest" name="final_size_harvest">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

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

                        <div class="form-group input-group-sm col-xs-12" id="">

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

                        <div class="form-group input-group-sm col-xs-12" id="">

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

                        <div class="form-group input-group-sm col-xs-12" id="">

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

                        <div class="form-group input-group-sm col-xs-12" id="">

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

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="other_defects" class="translate" data-traducir_english="Other Defects" data-traducir_spanish="Otros defectos">Otros defectos</label>

                            <div class="form-group show-tick">

                                <textarea maxlength="2500" align="left" style="height:100px; width:100%; resize: none;" class="form-control limpiar input requerido_load_report" rows="2" id="other_defects" style="width: 100%"></textarea>

                            </div>

                        </div>



                        <div class="col-xs-12">

                            <h3 class="translate" data-traducir_english="Harvest Assessment" data-traducir_spanish="Asignación de cosecha">Asignación de cosecha</h3>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <div class="form-group input-group-sm">

                                <label for="size_range_porcentage" class="translate" data-traducir_english="Percentage Size Range" data-traducir_spanish="Porcentaje promedio de tamaño">Porcentaje promedio de tamaño</label>

                                <input type="text" class="form-control decimal5_2 input " id="size_range_porcentage" name="size_range_porcentage">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <div class="form-group input-group-sm">

                                <label for="inicial_size_range" class="translate" data-traducir_english="Initial Size Range" data-traducir_spanish="Rango Inicial de tamaño">Rango Inicial de tamaño</label>

                                <input type="text" class="form-control decimal5_2 input " id="inicial_size_range" name="inicial_size_range">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <div class="form-group input-group-sm">

                                <label for="final_size_range" class="translate" data-traducir_english="Final Size Range" data-traducir_spanish="Rango final de tamaño">Rango final de tamaño</label>

                                <input type="text" class="form-control decimal5_2 input " id="final_size_range" name="final_size_range">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="select_size_range" class="translate" data-traducir_english="Select Size Range" data-traducir_spanish="Seleccione el rango de tamaño">Seleccione el rango de tamaño</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow " id="select_size_range" name="select_size_range" data-live-search="true" title="Select">

                                    <option value="-b">Select - Seleccione</option>

                                    <option value="1">% on spec</option>

                                    <option value="0">% out of spec</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <div class="form-group input-group-sm">

                                <label for="size_range1" class="translate" data-traducir_english="% Above Size Range" data-traducir_spanish="Size Range">Size Range</label>

                                <input type="text" class="form-control letras45 input " id="size_range1" name="size_range1">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="select_size_range1" class="translate" data-traducir_english="Select Size Range" data-traducir_spanish="Select Size Range">Select Size Range</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow " id="select_size_range1" name="select_size_range1" data-live-search="true" title="Select">

                                    <option value="-b">Select - Seleccione</option>

                                    <option value="1">% on spec</option>

                                    <option value="0">% out of spec</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <div class="form-group input-group-sm">

                                <label for="size_range2" class="translate" data-traducir_english="% Below Size Range" data-traducir_spanish="Rango de tamaño">Rango de tamaño</label>

                                <input type="text" class="form-control letras45 input " id="size_range2" name="size_range2">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="select_size_range2" class="translate" data-traducir_english="Select Size Range" data-traducir_spanish="Seleccione el rango de tamaño">Seleccione el rango de tamaño</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow " id="select_size_range2" name="select_size_range2" data-live-search="true" title="Select">

                                    <option value="-b">Select - Seleccione</option>

                                    <option value="1">% on spec</option>

                                    <option value="0">% out of spec</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <div class="form-group input-group-sm">

                                <label for="other_defects_ha" class="translate" data-traducir_english="Other Defects" data-traducir_spanish="Otros defectos">Otros defectos</label>

                                <input type="text" class="form-control letras45 input " id="other_defects_ha" name="other_defects_ha">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="select_other_defects_ha" class="translate" data-traducir_english="Select Other Defects" data-traducir_spanish="Seleccione otros defectos">Seleccione otros defectos</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow " id="select_other_defects_ha" name="select_other_defects_ha" data-live-search="true" title="Select">

                                    <option value="-b">Select - Seleccione</option>

                                    <option value="1">% on spec</option>

                                    <option value="0">% out of spec</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="dew_leaf" class="translate" data-traducir_english="Dew of Leaf" data-traducir_spanish="Rocio de la hoja">Rocio de la hoja</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow " id="dew_leaf" name="dew_leaf" data-live-search="true" title="Select">

                                    <option value="-b">Select - Seleccione</option>

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="inicial_time_harvest" class="translate" data-traducir_english="Initial Time Harvest" data-traducir_spanish="Tiempo inicial de cosecha">Tiempo inicial de cosecha</label>

                            <div class='input-group input-group-sm fecha-planeada datetime' id='div_inicial_time_harvest'>

                                <span class="input-group-addon">

                                    <span class="fa fa-calendar"></span>

                                </span>

                                <input type='text' class="form-control input" id="inicial_time_harvest" />

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="final_time_harvest" class="translate" data-traducir_english="Final Time Harvest" data-traducir_spanish="Tiempo final de cosecha">Tiempo final de cosecha</label>

                            <div class='input-group input-group-sm fecha-planeada datetime' id='div_final_time_harvest'>

                                <span class="input-group-addon">

                                    <span class="fa fa-calendar"></span>

                                </span>

                                <input type='text' class="form-control input" id="final_time_harvest" />

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <div class="form-group input-group-sm">

                                <label for="temperature_product" class="translate" data-traducir_english="Temperature of Product" data-traducir_spanish="Temperatura del producto">Temperatura del producto</label>

                                <input type="text" class="form-control decimal5_1 input " id="temperature_product" name="temperature_product">

                            </div>

                        </div>

                        <!-- <div class="form-group input-group-sm col-xs-12" id="">

                            <div class="form-group input-group-sm">

                                <label for="inicial_average_tote_weight_reported" class="translate" data-traducir_english="Inicial Average Tote Weight Reported" data-traducir_spanish="Peso Promedio inicial de la caja reportado">Peso Promedio inicial de la caja reportado</label>

                                <input type="text"class="form-control monto input " id="inicial_average_tote_weight_reported" name="inicial_average_tote_weight_reported">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <div class="form-group input-group-sm">

                                <label for="final_average_tote_weight_reported" class="translate" data-traducir_english="Final Average Tote Weight Reported" data-traducir_spanish="Peso promedio final de la caja reportada">Peso promedio final de la caja reportada</label>

                                <input type="text"class="form-control monto input " id="final_average_tote_weight_reported" name="final_average_tote_weight_reported">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <div class="form-group input-group-sm">

                                <label for="real_average_tote_weight" class="translate" data-traducir_english="Real Average Tote Weight" data-traducir_spanish="Porcentaje real del peso de la caja">Porcentaje real del peso de la caja</label>

                                <input type="text"class="form-control monto input " id="real_average_tote_weight" name="real_average_tote_weight">

                            </div>

                        </div> -->



                        <!-- <div class="col-xs-12">

                            <h3 class="translate" data-traducir_english="Vacuum Cooler" data-traducir_spanish="Vacuum Cooler">Vacuum Cooler</h3>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="time_receiving" class="translate" data-traducir_english="Time of Receiving" data-traducir_spanish="Time of Receiving">Time of Receiving</label>

                            <div class='input-group input-group-sm fecha-planeada time' id='datetimepicker1'>

                                <span class="input-group-addon">

                                    <span class="fa fa-calendar"></span>

                                </span>

                                <input type='text' class="form-control input" id="time_receiving" />

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <div class="form-group input-group-sm">

                                <label for="total_load_lbs_goal" class="translate" data-traducir_english="Total Load Lbs Goal" data-traducir_spanish="Total Load Lbs Goal">Total Load Lbs Goal</label>

                                <input type="text"class="form-control peso input " id="total_load_lbs_goal" name="total_load_lbs_goal">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <div class="form-group input-group-sm">

                                <label for="load_weight_received" class="translate" data-traducir_english="Load Weight Received" data-traducir_spanish="Load Weight Received">Load Weight Received</label>

                                <input type="text"class="form-control peso input " id="load_weight_received" name="load_weight_received">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <div class="form-group input-group-sm">

                                <label for="average_tote_weight" class="translate" data-traducir_english="Average Tote Weight" data-traducir_spanish="Average Tote Weight">Average Tote Weight</label>

                                <input type="text"class="form-control peso input " id="average_tote_weight" name="average_tote_weight">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <div class="form-group input-group-sm">

                                <label for="temperature_receiving" class="translate" data-traducir_english="Temperature of Receiving" data-traducir_spanish="Temperature of Receiving">Temperature of Receiving</label>

                                <input type="text"class="form-control decimal5_1 input " id="temperature_receiving" name="temperature_receiving">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="time_vacuum_cooler" class="translate" data-traducir_english="Time of VC" data-traducir_spanish="Time of VC">Time of VC</label>

                            <div class='input-group input-group-sm fecha-planeada time' id='div_time_vacuum_cooler'>

                                <span class="input-group-addon">

                                    <span class="fa fa-calendar"></span>

                                </span>

                                <input type='text' class="form-control input" id="time_vacuum_cooler" />

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <div class="form-group input-group-sm">

                                <label for="temperature_vacuum_cooler" class="translate" data-traducir_english="Temperature of VC" data-traducir_spanish="Temperature of VC">Temperature of VC</label>

                                <input type="text"class="form-control decimal5_1 input " id="temperature_vacuum_cooler" name="temperature_vacuum_cooler">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="hydrocooling" class="translate" data-traducir_english="Hydrocooling" data-traducir_spanish="Hydrocooling">Hydrocooling</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow " id="hydrocooling" name="hydrocooling" data-live-search="true" title="Select">

                                    <option value="-b">Select - Seleccione</option>

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="time_pickup" class="translate" data-traducir_english="Time of PickUp" data-traducir_spanish="Time of PickUp">Time of PickUp</label>

                            <div class='input-group input-group-sm fecha-planeada time' id='div_time_pickup'>

                                <span class="input-group-addon">

                                    <span class="fa fa-calendar"></span>

                                </span>

                                <input type='text' class="form-control input" id="time_pickup" />

                            </div>

                        </div>



                        <div class="col-xs-12">

                            <h3 class="translate" data-traducir_english="Hours Temperature" data-traducir_spanish="Hours Temperature">Hours Temperature</h3>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <div class="form-group input-group-sm">

                                <label for="tlc" class="translate" data-traducir_english="TLC" data-traducir_spanish="TLC">TLC</label>

                                <input type="text"class="form-control decimal5_1 input " id="tlc" name="tlc">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <div class="form-group input-group-sm">

                                <label for="vacuum_cooler" class="translate" data-traducir_english="Vacuum Cooler" data-traducir_spanish="Vacuum Cooler">Vacuum Cooler</label>

                                <input type="text"class="form-control decimal5_1 input " id="vacuum_cooler" name="vacuum_cooler">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <div class="form-group input-group-sm">

                                <label for="total_temperature" class="translate" data-traducir_english="Total Temperature" data-traducir_spanish="Total Temperature">Total Temperature</label>

                                <input type="text"class="form-control decimal5_1 input " id="total_temperature" name="total_temperature">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="pickup_truck_checkin" class="translate" data-traducir_english="PickUp Truck CheckIn" data-traducir_spanish="PickUp Truck CheckIn">PickUp Truck CheckIn</label>

                            <div class='input-group input-group-sm fecha-planeada time' id='datetimepicker1'>

                                <span class="input-group-addon">

                                    <span class="fa fa-calendar"></span>

                                </span>

                                <input type='text' class="form-control input" id="pickup_truck_checkin" />

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="number_cut" class="translate" data-traducir_english="Cut" data-traducir_spanish="Cut">Cut</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow " id="number_cut" name="number_cut" data-live-search="true" title="Select">

                                    <option value="-b">Select - Seleccione</option>

                                    <option value="1">First</option>

                                    <option value="2">Second</option>

                                    <option value="3">Third</option>

                                </select>

                            </div>

                        </div> -->

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>

                    <button type="button" class="btn btn-sm btn-primary btn_guardar translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_guardar_load_report">Guardar</button>

                </div>

            </div>

        </div>

    </div>

    <div class="modal fade" id="modal_eliminar_bloque" tabindex="-1" role="dialog" aria-labelledby="modal_confirm_box" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content modal-warning">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    <h4 class="modal-title" id="modal_confirm_box"> Delete Block - Eliminar Bloque </h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-xs-12 msg-box-container">

                            <i class="fa fa-exclamation-triangle fa-2x"></i>

                            <span> Are you sure you want to delete the block? <br> ¿Está seguro que desea eliminar el bloque? </span>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Cancel" data-traducir_spanish="Cancelar" data-dismiss="modal">Cancelar</button>

                    <button type="button" class="btn btn-sm btn-primary smooth-transition translate" data-traducir_english="Delete" data-traducir_spanish="Eliminar" data-cod_bloque="0" id="btn_eliminar_bloque">Eliminar</button>

                </div>

            </div>

        </div>

    </div>

    <div class="modal fade" id="modal_explorar" tabindex="-1" role="dialog" aria-labelledby="modal_confirm_box" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content modal-success">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    <h4 class="modal-title translate" data-traducir_english="Scouting" data-traducir_spanish="Explorar" id="modal_confirm_box"> Explorar </h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-xs-12 msg-box-container">

                            <i class="fa fa-exclamation-triangle fa-2x"></i>



                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Cancel" data-traducir_spanish="Cancelar" data-dismiss="modal">Cancelar</button>

                    <button type="button" class="btn btn-sm btn-primary smooth-transition translate" data-traducir_english="Accept" data-traducir_spanish="Aceptar" id="btn_explorar_bloque">Aceptar</button>

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

                                <select class="selectpicker show-menu-arrow requerido_explorar_plantacion" id="etapa_crecimiento" name="etapa_crecimiento" data-live-search="true" title="Select">

                                    <option value="-b">Select - Select</option>

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

                        <div class="col-xs-12">
                            <h3>Insects - Insectos</h3>
                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="worms">Worms - Gusanos</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="worms" name="worms">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="eggs">Eggs - Huevos</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="eggs" name="eggs">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="leafhoppers">Leaf Hoppers - Saltahojas</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="leafhoppers" name="leafhoppers">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="aphids">Aphids - Afidos</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="aphids" name="aphids">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="stink_bugs">Stink Bugs - Chinches</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="stink_bugs" name="stink_bugs">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="gnats">Gnats - Moscos</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="gnats" name="gnats">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="flea_beetles">Flea Beetles - Escarabajos</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="flea_beetles" name="flea_beetles">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="cyclaman_mites">Cyclaman Mites - Ácaros del ciclamen</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="cyclaman_mites" name="cyclaman_mites">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="spidermites">Spidermites - Ácaros rojos (New)</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="spidermites" name="spidermites">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="mealybugs">Mealybugs - Cochinillas (New)</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="mealybugs" name="mealybugs">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="thrips">Thrips - Trips (New)</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="thrips" name="thrips">

                            </div>

                        </div>

                        <div class="col-xs-12">
                            <h3>Diseases - Enfermedades</h3>
                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="cercospora_leaf_spot">Cercospora Leaf Spot</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="cercospora_leaf_spot" name="cercospora_leaf_spot">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="pythium">Pythium/Damp Off</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="pythium" name="pythium">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="rhizoctonia_aerial_blight">Rhizoctonia Aerial Blight</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="rhizoctonia_aerial_blight" name="rhizoctonia_aerial_blight">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="bacteria">Bacteria</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="bacteria" name="bacteria">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="sclerotinia">Sclerotinia</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="sclerotinia" name="sclerotinia">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="alternaria_specks">Alternaria Specks</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="alternaria_specks" name="alternaria_specks">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="mildew">Mildew</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="mildew" name="mildew">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="virus">Virus</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="virus" name="virus">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="white_rust">White Rust - Roya (new)</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="white_rust" name="white_rust">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="salt_accumulation">Salt Accumulation - Acumulación de sal (new)</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="salt_accumulation" name="salt_accumulation">

                            </div>

                        </div>

                        <div class="col-xs-12">
                            <h3>Weeds - Malas Hierbas</h3>
                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="dollarweed">Dollarweed</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="dollarweed" name="dollarweed">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="frogs_bit">Frogs Bit</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="frogs_bit" name="frogs_bit">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="mud_plantain">Mud Plantain</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="mud_plantain" name="mud_plantain">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="tube_weed">Tube Weed</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="tube_weed" name="tube_weed">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="grass">Grass</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="grass" name="grass">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="nutsedge">Nutsedge (new)</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="nutsedge" name="nutsedge">

                            </div>

                        </div>

                        <div class="col-xs-12">
                            <h3>Other Damage</h3>
                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="damaged_leaves">Damaged Leaves - Hojas Dañadas</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="damaged_leaves" name="damaged_leaves">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="purple_stem">Purple Stem - Tallos purpuras</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="purple_stem" name="purple_stem">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="watercress_rooter">Watercress Rooted - Berro Enraizado</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="watercress_rooter" name="watercress_rooter">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="buds">Buds - Brotes (new)</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="buds" name="buds">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="zigzag_stems">Zigzag stems - Tallos en Zigzag (new)</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="zigzag_stems" name="zigzag_stems">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="nutrient_deficiency">Nutrient Deficiency - Deficiencia de nutrientes (new)</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="nutrient_deficiency" name="nutrient_deficiency">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="round_up">Round Up (new)</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="round_up" name="round_up">

                            </div>

                        </div>

                        <div class="input-group-sm col-xs-6">

                            <div class="input-group-sm">

                                <label for="light_color">Light Color - Color Claro (new)</label>

                                <input value="0" type="text" class="form-control monto6_3 input requerido_explorar_plantacion" id="light_color" name="light_color">

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

                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Cancel" data-traducir_spanish="Cancelar" data-dismiss="modal">Cancelar</button>

                    <button type="button" class="btn btn-sm btn-primary smooth-transition translate" data-traducir_english="Accept" data-traducir_spanish="Aceptar" id="btn_guardar_exploracion_plantacion">Aceptar</button>

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

                                <select class="selectpicker show-menu-arrow requerido_calibrar" id="cod_fertilizante" name="cod_fertilizante" data-live-search="true" title="Select">

                                    <option value="-b">Select - Select</option>

                                    <option value="1">ON</option>

                                    <option value="2">OFF</option>

                                    <option value="3">Only Water - Sólo Agua</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="horas_aplicacion_calibrar" class="translate" data-traducir_english="Hours of application" data-traducir_spanish="Horas de aplicación">Horas de aplicación</label>

                                <input type="text" class="form-control monto input requerido_calibrar" id="horas_aplicacion_calibrar" name="horas_aplicacion_calibrar">

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

                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>

                    <button type="button" class="btn btn-sm btn-primary smooth-transition translate" data-traducir_english="Accept" data-traducir_spanish="Aceptar" id="btn_calibrar_bloque">Aceptar</button>

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

                                <select class="selectpicker show-menu-arrow requerido_estado_bloque" id="cod_estado_plantacion" name="cod_estado_plantacion" data-live-search="true" title="Select">

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

                    <button type="button" class="btn btn-sm btn-primary smooth-transition translate" data-traducir_english="Accept" data-traducir_spanish="Aceptar" id="btn_estado_bloque">Aceptar</button>

                </div>

            </div>

        </div>

    </div>

    <div class="row" style="min-width:100px;" align="right" id="">

        <div class="modal fade plan_modal_nueva_observacion" id="mymodal" tabindex="-1" role="dialog" aria-labelledby="Modalacciones" aria-hidden="true">

            <div class="modal-dialog modal-md modal-success">

                <div class="modal-content">

                    <div class="modal-header" align="left">

                        <button type="button" class="close" data-dismiss="modal">

                            <span aria-hidden="true">&times;</span>

                            <span class="sr-only">Cerrar</span>

                        </button>

                        <h4 class="modal-title translate" data-traducir_english="New observation" data-traducir_spanish="Nueva observación" id="titulo_modal">Nueva observación</h4>

                    </div>

                    <div class="modal-body">

                        <div class="form-group input-group-sm" align="left">

                            <div class="row">

                                <div class="col-md-12">

                                    <label for="nueva_observacion" class="translate" data-traducir_english="Observations" data-traducir_spanish="Observaciones">Observaciones</label>

                                    <textarea maxlength="3000" class="input requerido_observacion" id="nueva_observacion" align="left" style="height:100px; width:100%; resize: none;"></textarea>

                                </div>

                            </div>

                        </div>

                    </div> <!-- Row -->

                    <div class="modal-footer">

                        <div class="form-group input-group-sm">

                            <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>

                            <button type="button" class="btn btn-sm btn-primary btn_guardar translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_nueva_observacion">Guardar</button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div> <!-- panel-footer -->

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

                                <input type="text" class="form-control letras input requerido_limpieza" id="equipo_limpieza" name="equipo_limpieza">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="cleaning_tools" class="translate" data-traducir_english="Cleaning tools/material" data-traducir_spanish="Limpiar herramientas/materiales">Limpiar herramientas/materiales</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_limpieza" id="cleaning_tools" name="cleaning_tools" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="cleaning_potable_water" class="translate" data-traducir_english="Cleaning with potable water" data-traducir_spanish="Limpiar con agua potable">Limpiar con agua potable</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_limpieza" id="cleaning_potable_water" name="cleaning_potable_water" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="cleaning_detergent" class="translate" data-traducir_english="Cleaning with detergent" data-traducir_spanish="Limpiar con detergente">Limpiar con detergente</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_limpieza" id="cleaning_detergent" name="cleaning_detergent" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="scrubbing" class="translate" data-traducir_english="Scrubbing" data-traducir_spanish="Restregar">Restregar</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_limpieza" id="scrubbing" name="scrubbing" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="rinse_potable_water" class="translate" data-traducir_english="Rinse with potable water" data-traducir_spanish="Enjuagar con agua potable">Enjuagar con agua potable</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_limpieza" id="rinse_potable_water" name="rinse_potable_water" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="sanitizing_chlorine" class="translate" data-traducir_english="Sanitizing with chlorine" data-traducir_spanish="Desinfectar con cloro">Desinfectar con cloro</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_limpieza" id="sanitizing_chlorine" name="sanitizing_chlorine" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="post_sanitizing" class="translate" data-traducir_english="Post sanitizing rinse with potable water" data-traducir_spanish="Desinfectar despues de enjuagar con agua potable">Desinfectar despues de enjuagar con agua potable</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_limpieza" id="post_sanitizing" name="post_sanitizing" data-live-search="true" title="Select">

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

                            <div class='input-group input-group-sm fecha-planeada datetime' id='datetimepicker1'>

                                <span class="input-group-addon">

                                    <span class="fa fa-calendar"></span>

                                </span>

                                <input type='text' class="form-control input requerido_harvesting_worksheet" id="harvest_date" />

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="phi">PHI</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_worksheet" id="phi" name="phi" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="cellos">Loose</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_worksheet" id="cellos" name="cellos" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="increment_bunch_cello" class="translate" data-traducir_english="Increment (Bunch-Loose)" data-traducir_spanish="Incrementar (Suelto-Manojo)">Incrementar (Suelto-Manojo)</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_worksheet" id="increment_bunch_cello" name="increment_bunch_cello" data-live-search="true" title="Select">

                                    <option value="3">Pounds</option>

                                    <option value="2">Unused</option>

                                    <option value="1">Loose</option>

                                    <option value="0">Bunch</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="area_finished" class="translate" data-traducir_english="Area FINISHED?" data-traducir_spanish="¿Area Terminada?">¿Area Terminada?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_worksheet" id="area_finished" name="area_finished" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="acres_harvested" class="translate" data-traducir_english="Acres Harvested" data-traducir_spanish="Acres Cosechados">Acres Cosechados</label>

                                <input type="text" class="form-control monto input requerido_harvesting_worksheet" id="acres_harvested" name="acres_harvested">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="orden_compra" class="translate" data-traducir_english="PO#" data-traducir_spanish="Orden de Compra">Orden de Compra</label>

                                <input type="text" class="form-control letras10 input requerido_harvesting_worksheet" id="orden_compra" name="orden_compra">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="crop_number" class="translate" data-traducir_english="Crop #" data-traducir_spanish="Cultivo No.">Cultivo No.</label>

                                <input type="text" class="form-control letras10 input" id="crop_number" name="crop_number">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="cantidad_cosechada_worksheet" class="translate" data-traducir_english="Harvested quantity" data-traducir_spanish="Cantidad Cosechada">Cantidad Cosechada</label>

                                <input type="text" class="form-control monto input requerido_harvesting_worksheet" id="cantidad_cosechada_worksheet" name="cantidad_cosechada_worksheet">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="cantidad_empacada_worksheet" class="translate" data-traducir_english="Packaged Quantity" data-traducir_spanish="Cantidad Empacada">Cantidad Empacada</label>

                                <input type="text" class="form-control monto input" id="cantidad_empacada_worksheet" name="cantidad_empacada_worksheet">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="date_packed" class="translate" data-traducir_english="Date Packed" data-traducir_spanish="Fecha Empacado">Fecha Empacado</label>

                            <div class='input-group input-group-sm fecha-planeada date' id='datetimepicker1'>

                                <span class="input-group-addon">

                                    <span class="fa fa-calendar"></span>

                                </span>

                                <input type='text' class="form-control input" id="date_packed" />

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="totes_harvested" class="translate" data-traducir_english="Totes Harvested" data-traducir_spanish="Bolsas Cosechadas">Bolsas Cosechadas</label>

                                <input type="text" class="form-control monto input" id="totes_harvested" name="totes_harvested">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="totes_packed" class="translate" data-traducir_english="Totes Packed" data-traducir_spanish="Bolsas Empacadas">Bolsas Empacadas</label>

                                <input type="text" class="form-control monto input" id="totes_packed" name="totes_packed">

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



    <div class="modal fade modal_actualizar_harvesting_worksheet" id="modal_actualizar_harvesting_worksheet" tabindex="-1" role="dialog" aria-labelledby="ModalResponsables" aria-hidden="true">

        <div class="modal-dialog modal-success modal-md">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                    <h4 class="modal-title"><span id="titulo_modal_responsables" class="translate" data-traducir_english="Harvesting Worksheet" data-traducir_spanish="Hoja de Cosecha">Hoja de Cosecha</span> <small class="subtitulo" id="subtitulo_modal_responsables"></small></h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="form-group input-group-sm col-xs-12 hide">

                            <div class="form-group input-group-sm">

                                <input type="text" class="form-control input" id="codigo_harvesting_worksheet" name="codigo_harvesting_worksheet">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="actualizar_harvest_date" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</label>

                            <div class='input-group input-group-sm fecha-planeada datetime' id='datetimepicker1'>

                                <span class="input-group-addon">

                                    <span class="fa fa-calendar"></span>

                                </span>

                                <input type='text' class="form-control input requerido_actualizar_harvesting_worksheet" id="actualizar_harvest_date" />

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="actualizar_phi">PHI</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_actualizar_harvesting_worksheet" id="actualizar_phi" name="phi" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="actualizar_cellos">Loose</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_actualizar_harvesting_worksheet" id="actualizar_cellos" name="cellos" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="actualizar_increment_bunch_cello" class="translate" data-traducir_english="Increment (Bunch-Loose)" data-traducir_spanish="Incrementar (Suelto-Manojo)">Incrementar (Suelto-Manojo)</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_actualizar_harvesting_worksheet" id="actualizar_increment_bunch_cello" name="actualizar_increment_bunch_cello" data-live-search="true" title="Select">

                                    <option value="3">Pounds</option>

                                    <option value="2">Unused</option>

                                    <option value="1">Loose</option>

                                    <option value="0">Bunch</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="actualizar_area_finished" class="translate" data-traducir_english="Area FINISHED?" data-traducir_spanish="¿Area Terminada?">¿Area Terminada?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_actualizar_harvesting_worksheet" id="actualizar_area_finished" name="actualizar_area_finished" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="actualizar_acres_harvested" class="translate" data-traducir_english="Acres Harvested" data-traducir_spanish="Acres Cosechados">Acres Cosechados</label>

                                <input type="text" class="form-control monto input requerido_actualizar_harvesting_worksheet" id="actualizar_acres_harvested" name="actualizar_acres_harvested">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="actualizar_orden_compra" class="translate" data-traducir_english="PO#" data-traducir_spanish="Orden de Compra">Orden de Compra</label>

                                <input type="text" class="form-control letras10 input requerido_actualizar_harvesting_worksheet" id="actualizar_orden_compra" name="actualizar_orden_compra">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="actualizar_crop_number" class="translate" data-traducir_english="Crop #" data-traducir_spanish="Cultivo No.">Cultivo No.</label>

                                <input type="text" class="form-control letras10 input" id="actualizar_crop_number" name="actualizar_crop_number">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="actualizar_cantidad_cosechada_worksheet" class="translate" data-traducir_english="Harvested quantity" data-traducir_spanish="Cantidad Cosechada">Cantidad Cosechada</label>

                                <input type="text" class="form-control monto input requerido_actualizar_harvesting_worksheet" id="actualizar_cantidad_cosechada_worksheet" name="actualizar_cantidad_cosechada_worksheet">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="actualizar_cantidad_empacada_worksheet" class="translate" data-traducir_english="Packaged Quantity" data-traducir_spanish="Cantidad Empacada">Cantidad Empacada</label>

                                <input type="text" class="form-control monto input requerido_actualizar_harvesting_worksheet" id="actualizar_cantidad_empacada_worksheet" name="actualizar_cantidad_empacada_worksheet">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="actualizar_date_packed" class="translate" data-traducir_english="Date Packed" data-traducir_spanish="Fecha Empacado">Fecha Empacado</label>

                            <div class='input-group input-group-sm fecha-planeada date' id='datetimepicker1'>

                                <span class="input-group-addon">

                                    <span class="fa fa-calendar"></span>

                                </span>

                                <input type='text' class="form-control input requerido_actualizar_harvesting_worksheet" id="actualizar_date_packed" />

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="actualizar_totes_harvested" class="translate" data-traducir_english="Totes Harvested" data-traducir_spanish="Bolsas Cosechadas">Bolsas Cosechadas</label>

                                <input type="text" class="form-control monto input requerido_actualizar_harvesting_worksheet" id="actualizar_totes_harvested" name="actualizar_totes_harvested">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="actualizar_totes_packed" class="translate" data-traducir_english="Totes Packed" data-traducir_spanish="Bolsas Empacadas">Bolsas Empacadas</label>

                                <input type="text" class="form-control monto input requerido_actualizar_harvesting_worksheet" id="actualizar_totes_packed" name="actualizar_totes_packed">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="actualizar_commments_harvesting_worksheet" class="translate" data-traducir_english="Comments" data-traducir_spanish="Comentarios">Comentarios</label>

                            <div class="form-group show-tick">

                                <textarea maxlength="2500" align="left" style="height:100px; width:100%; resize: none;" class="form-control limpiar input requerido_actualizar_harvesting_worksheet" rows="2" id="actualizar_commments_harvesting_worksheet" style="width: 100%"></textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>

                    <button type="button" class="btn btn-sm btn-primary btn_guardar translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_actualizar_harvesting_worksheet">Guardar</button>

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

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="loose_bunches" name="loose_bunches" data-live-search="true" title="Select">

                                    <option value="1">Loose</option>

                                    <option value="0">Bunch</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="conventional_organic" class="translate" data-traducir_english="Conventional or Organic" data-traducir_spanish="Convencional u Organico">Conventional or Organic</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="conventional_organic" name="conventional_organic" data-live-search="true" title="Select">

                                    <option value="1">Conventional</option>

                                    <option value="0">Organic</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question1" class="translate" data-traducir_english="Are harvest crews training records up to date?" data-traducir_spanish="¿Están actualizados los registros de entrenamiento de los cosechadores?">¿Están actualizados los registros de entrenamiento de los cosechadores?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question1" name="question1" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question2" class="translate" data-traducir_english="Are there sick workers?" data-traducir_spanish="¿Hay trabajadores enfermos?">¿Hay trabajadores enfermos?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question2" name="question2" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question3" class="translate" data-traducir_english="Have sick workers been reassigned to non-food contact jobs?" data-traducir_spanish="¿Se ha reasignado a los trabajadores enfermos a trabajos sin contacto con alimentos?">¿Se ha reasignado a los trabajadores enfermos a trabajos sin contacto con alimentos?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question3" name="question3" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question4" class="translate" data-traducir_english="Have harvesters properly covered open wounds, lesion, boils, etc?" data-traducir_spanish="¿Los cosechadores cubren adecuadamente heridas abiertas, lesiones, forúnos,etc?">¿Los cosechadores cubren adecuadamente heridas abiertas, lesiones, forúnos,etc?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question4" name="question4" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question5" class="translate" data-traducir_english="Has the crew been instructed on company policies regarding eating, drinking, tobacco use, jewelry and other safety rules?" data-traducir_spanish="¿Se ha instruido a los empleados sobre las políticas de la empresa con respecto a comer, beber, consumir tabaco, usar joyas y acerca de otras normas de seguridad?">¿Se ha instruido a los empleados sobre las políticas de la empresa con respecto a comer, beber, consumir tabaco, usar joyas y acerca de otras normas de seguridad?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question5" name="question5" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question6" class="translate" data-traducir_english="Is the harvesting crew wearing clean and proper clothing?" data-traducir_spanish="¿El equipo de recolección lleva ropa limpia y adecuada?">¿El equipo de recolección lleva ropa limpia y adecuada?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question6" name="question6" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question7" class="translate" data-traducir_english="Are harvesting employees wearing hairnets, hats, cap, etc?" data-traducir_spanish="¿Los empleados estan usando mallas, sombreros, gorro, etc?">¿Los empleados estan usando mallas, sombreros, gorro, etc?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question7" name="question7" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question8" class="translate" data-traducir_english="Are harvesters’  hands clean and sanitized?" data-traducir_spanish="¿Están limpias y desinfectadas las manos de los cosechadores?">¿Están limpias y desinfectadas las manos de los cosechadores?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question8" name="question8" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question9" class="translate" data-traducir_english="Are the portable toilets and sanitation station located at 1/4 mile or less from the harvesting area?" data-traducir_spanish="¿Los retretes portátiles y la estación de saneamiento se encuentran a ¼ de milla o menos del área de recolección?">¿Los retretes portátiles y la estación de saneamiento se encuentran a ¼ de milla o menos del área de recolección?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question9" name="question9" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="desde_aqui_no_traducido">

                            <label for="question10" class="translate" data-traducir_english="Have all harvesting tools been cleaned and sanitized?" data-traducir_spanish="¿Se han limpiado y desinfectado todas las herramientas de cosecha?">¿Se han limpiado y desinfectado todas las herramientas de cosecha?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question10" name="question10" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question11" class="translate" data-traducir_english="Are haul truck properly cleaned and, if necessary sanitized?" data-traducir_spanish="¿Se limpian adecuadamente los camiones de acarreo y, si es necesario, se desinfectan?">¿Se limpian adecuadamente los camiones de acarreo y, si es necesario, se desinfectan?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question11" name="question11" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question12" class="translate" data-traducir_english="Has the truck used for harvesting-hauling produce and the mechanical harvesting machine been inspected for oil-diesel/gasoline leaking, loose metal parts, or any other defectposing potential contamination issues?" data-traducir_spanish="¿Se ha inspeccionado la carretilla utilizada para cosechar y transportar productos y la máquina mecánica de recolección para detectar fugas de aceite, diesel o gasolina, piezas metálicas sueltas o cualquier otro problema de posible contaminación potencial?">¿Se ha inspeccionado la carretilla utilizada para cosechar y transportar productos y la máquina mecánica de recolección para detectar fugas de aceite, diesel o gasolina, piezas metálicas sueltas o cualquier otro problema de posible contaminación potencial?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question12" name="question12" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question13" class="translate" data-traducir_english="Is the truck used for harvesting-hauling produce fit for use?" data-traducir_spanish="¿Es el camión usado para cosechar y transportar productos aptos para el uso?">¿Es el camión usado para cosechar y transportar productos aptos para el uso?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question13" name="question13" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question14" class="translate" data-traducir_english="Has the mechanical harvesting machinery been cleaned and sanitized?" data-traducir_spanish="¿Se ha limpiado y desinfectado la maquinaria de recolección mecánica?">¿Se ha limpiado y desinfectado la maquinaria de recolección mecánica?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question14" name="question14" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question15" class="translate" data-traducir_english="Is the Mechanical Harvesting machine fit for use?" data-traducir_spanish="¿La máquina de cosecha mecánica es apta para el uso?">¿La máquina de cosecha mecánica es apta para el uso?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question15" name="question15" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question16" class="translate" data-traducir_english="Has all the harvesting equipment been inspected for glass breakage?" data-traducir_spanish="¿Se han inspeccionado todos los equipos de cosecha para detectar roturas de vidrio?">¿Se han inspeccionado todos los equipos de cosecha para detectar roturas de vidrio?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question16" name="question16" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question17" class="translate" data-traducir_english="Have all harvesting totes been cleaned and sanitized?" data-traducir_spanish="¿Se han limpiado y desinfectado todas las bolsas de cosecha?">¿Se han limpiado y desinfectado todas las bolsas de cosecha?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question17" name="question17" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="question18" class="translate" data-traducir_english="Quantity of knives/hooks issued" data-traducir_spanish="Cantidad de cuchillas/herraminetas usadas">Cantidad de cuchillas/herraminetas usadas</label>

                                <input type="text" class="form-control numeros4 input requerido_harvesting_checklist" id="question18" name="question18">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="question19" class="translate" data-traducir_english="Quantity of knives/hooks returned" data-traducir_spanish="Cantidad de cuchillas/herramientas devueltas">Cantidad de cuchillas/herramientas devueltas</label>

                                <input type="text" class="form-control numeros4 input requerido_harvesting_checklist" id="question19" name="question19">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question20" class="translate" data-traducir_english="Are the evidence of animal intrusion (fecal material), pest infestation, etc. that can pose a risk of contamination on the crop to harvest?" data-traducir_spanish="¿Hay evidencia de intrusión animal (materia fecal), infestación de plagas, etc. que pueda representar un riesgo de contaminación en el cultivo para cosechar?">¿Hay evidencia de intrusión animal (materia fecal), infestación de plagas, etc. que pueda representar un riesgo de contaminación en el cultivo para cosechar?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question20" name="question20" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question21" class="translate" data-traducir_english="Have buffer zones being implemented in the event of a contamination? 30ft (9.1m) from flooded ares and 5ft (1.5m) from evidence of pest activity" data-traducir_spanish="¿Se han implementado zonas de amortiguamiento en caso de contaminación? 30 pies (9.1 m) de la zona inundada y 5 pies (1.5 m) de la evidencia de actividad de plagas">¿Se han implementado zonas de amortiguamiento en caso de contaminación? 30 pies (9.1 m) de la zona inundada y 5 pies (1.5 m) de la evidencia de actividad de plagas</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question21" name="question21" data-live-search="true" title="Select">

                                    <option value="1">Yes</option>

                                    <option value="0">No</option>

                                    <option value="2">NA</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12" id="">

                            <label for="question22" class="translate" data-traducir_english="Has the crop-block/section been cleared for harvest?" data-traducir_spanish="¿Se ha limpiado el bloque / sección de cultivo para la cosecha?">¿Se ha limpiado el bloque / sección de cultivo para la cosecha?</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_harvesting_checklist" id="question22" name="question22" data-live-search="true" title="Select">

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

                                <button class="btn btn-info btn-all-stick check-all has-tooltip" data-id="cod_bloques_plantacion2" id="todos_bloques2" title="Seleccionar todos" data-toggle="tooltip" type="button">

                                    <i class="fa fa-check"></i>

                                </button>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <div class="form-group input-group-sm">

                                <label for="cantidad" class="translate" data-traducir_english="Amount (Pounds)" data-traducir_spanish="Cantidad (Lb)">Cantidad (Lb)</label>

                                <input type="text" class="form-control monto input requerido_trasplantar" id="cantidad" name="cantidad">

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="cod_bloques_trasplante" class="translate" data-traducir_english="Blocks received" data-traducir_spanish="Bloques recibidos">Bloques recibidos</label>

                            <div class="form-group show-tick">

                                <select class="selectpicker show-menu-arrow requerido_trasplantar" multiple="multiple" data-actions-box="true" title="Select" id="cod_bloques_trasplante" name="cod_bloques_trasplante">

                                </select>

                                <button class="btn btn-info btn-all-stick check-all has-tooltip" data-id="cod_bloques_trasplante" id="todos_bloques" title="Seleccionar todos" data-toggle="tooltip" type="button">

                                    <i class="fa fa-check"></i>

                                </button>

                            </div>

                        </div>

                        <div class="form-group input-group-sm col-xs-12">

                            <label for="fecha_checklist" class="translate" data-traducir_english="Planning Date" data-traducir_spanish="Fecha Planificación">Fecha Planificación</label>

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

                                <input type="text" class="form-control numero_carga input requerido_trasplantar" id="numero_carga" name="numero_carga">

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

    <!-- Modales de confirmación de cambio de estado -->



    <div class="modal fade" id="modal_confirmar_poda" tabindex="-1" role="dialog" aria-labelledby="modal_confirm_box" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content modal-warning">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    <h4 class="modal-title" id="modal_confirm_box"> Stage Change - Cambiar Estado </h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-xs-12 msg-box-container">

                            <i class="fa fa-exclamation-triangle fa-2x"></i>

                            <span> This action changes stage of all planting blocks. Are you sure you want to change the stage?</span>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Cancel" data-traducir_spanish="Cancelar" data-dismiss="modal">Cancelar</button>

                    <button type="button" class="btn btn-sm btn-primary smooth-transition translate" data-traducir_english="Confirm" data-traducir_spanish="Confirmar" data-cod_bloque="0" id="btn_confirmar_poda">Confirmar</button>

                </div>

            </div>

        </div>

    </div>



    <div class="modal fade" id="modal_confirmar_crecimiento" tabindex="-1" role="dialog" aria-labelledby="modal_confirm_box" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content modal-warning">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    <h4 class="modal-title" id="modal_confirm_box"> Stage Change - Cambiar Estado </h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-xs-12 msg-box-container">

                            <i class="fa fa-exclamation-triangle fa-2x"></i>

                            <span> This action changes stage of all planting blocks. Are you sure you want to change the stage?</span>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Cancel" data-traducir_spanish="Cancelar" data-dismiss="modal">Cancelar</button>

                    <button type="button" class="btn btn-sm btn-primary smooth-transition translate" data-traducir_english="Confirm" data-traducir_spanish="Confirmar" data-cod_bloque="0" id="btn_confirmar_crecimiento">Confirmar</button>

                </div>

            </div>

        </div>

    </div>



    <div class="modal fade" id="modal_confirmar_cosechar" tabindex="-1" role="dialog" aria-labelledby="modal_confirm_box" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content modal-warning">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    <h4 class="modal-title" id="modal_confirm_box"> Stage Change - Cambiar Estado </h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-xs-12 msg-box-container">

                            <i class="fa fa-exclamation-triangle fa-2x"></i>

                            <span> This action changes stage of all planting blocks. Are you sure you want to change the stage?</span>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Cancel" data-traducir_spanish="Cancelar" data-dismiss="modal">Cancelar</button>

                    <button type="button" class="btn btn-sm btn-primary smooth-transition translate" data-traducir_english="Confirm" data-traducir_spanish="Confirmar" data-cod_bloque="0" id="btn_confirmar_cosechar">Confirmar</button>

                </div>

            </div>

        </div>

    </div>



    <div class="modal fade" id="modal_confirmar_cosechado" tabindex="-1" role="dialog" aria-labelledby="modal_confirm_box" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content modal-warning">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    <h4 class="modal-title" id="modal_confirm_box"> Stage Change - Cambiar Estado </h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-xs-12 msg-box-container">

                            <i class="fa fa-exclamation-triangle fa-2x"></i>

                            <span> This action changes stage of all planting blocks. Are you sure you want to change the stage?</span>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Cancel" data-traducir_spanish="Cancelar" data-dismiss="modal">Cancelar</button>

                    <button type="button" class="btn btn-sm btn-primary smooth-transition translate" data-traducir_english="Confirm" data-traducir_spanish="Confirmar" data-cod_bloque="0" id="btn_confirmar_cosechado">Confirmar</button>

                </div>

            </div>

        </div>

    </div>



    <div class="modal fade" id="modal_confirmar_control_calidad" tabindex="-1" role="dialog" aria-labelledby="modal_confirm_box" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content modal-warning">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    <h4 class="modal-title" id="modal_confirm_box"> Stage Change - Cambiar Estado </h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-xs-12 msg-box-container">

                            <i class="fa fa-exclamation-triangle fa-2x"></i>

                            <span> This action changes stage of all planting blocks. Are you sure you want to change the stage?</span>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Cancel" data-traducir_spanish="Cancelar" data-dismiss="modal">Cancelar</button>

                    <button type="button" class="btn btn-sm btn-primary smooth-transition translate" data-traducir_english="Confirm" data-traducir_spanish="Confirmar" data-cod_bloque="0" id="btn_confirmar_control_calidad">Confirmar</button>

                </div>

            </div>

        </div>

    </div>



    <div class="modal fade" id="modal_confirmar_producto_empacado" tabindex="-1" role="dialog" aria-labelledby="modal_confirm_box" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content modal-warning">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    <h4 class="modal-title" id="modal_confirm_box"> Stage Change - Cambiar Estado </h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-xs-12 msg-box-container">

                            <i class="fa fa-exclamation-triangle fa-2x"></i>

                            <span> This action changes stage of all planting blocks. Are you sure you want to change the stage?</span>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Cancel" data-traducir_spanish="Cancelar" data-dismiss="modal">Cancelar</button>

                    <button type="button" class="btn btn-sm btn-primary smooth-transition translate" data-traducir_english="Confirm" data-traducir_spanish="Confirmar" data-cod_bloque="0" id="btn_confirmar_producto_empacado">Confirmar</button>

                </div>

            </div>

        </div>

    </div>



    <div class="modal fade" id="modal_confirmar_terminar" tabindex="-1" role="dialog" aria-labelledby="modal_confirm_box" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content modal-warning">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    <h4 class="modal-title" id="modal_confirm_box"> Stage Change - Cambiar Estado </h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-xs-12 msg-box-container">

                            <i class="fa fa-exclamation-triangle fa-2x"></i>

                            <span> This action changes stage of all planting blocks. Are you sure you want to change the stage?</span>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Cancel" data-traducir_spanish="Cancelar" data-dismiss="modal">Cancelar</button>

                    <button type="button" class="btn btn-sm btn-primary smooth-transition translate" data-traducir_english="Confirm" data-traducir_spanish="Confirmar" data-cod_bloque="0" id="btn_confirmar_terminar">Confirmar</button>

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

            <div class="col-xs-9 col-md-3 nopadding text-left smooth-transition" id="container_back_button">

                <button data-loading-text="Volviendo" class="btn btn-sm btn-primary btn-fullwidth main-actions smooth-transition" type="button" id="btn_ir_al_listado" name="btn_ir_al_listado">

                    <i class="fa fa-arrow-left"> </i> <span id="btn_regresar_listado" class="translate" data-traducir_english="Back to list" data-traducir_spanish="Regresar al listado">Regresar al listado</span>

                </button>

            </div>



            <div class="col-xs-3 col-md-9 nopadding smooth-transition" id="div_acciones" style="text-align: center;">

                <button class="btn btn-sm btn-primary btn-open-actions main-actions smooth-transition" type="button" id="btn_open_actions" name="btn_open_actions">

                    <i class="fa fa-ellipsis-v"></i>

                </button>

                <div class="col-md-3 col-md-offset-3" align="right" style="display: flex;">

                    <?php /*if($PLANTACION[0]['cod_estado'] == 2)

                    {

                        ?>

                        <a title="Apply Chemical - Aplicar Quimico" onclick="cod_aplicacion_quimico = 0;codigo_detalle = 0;$('#modal_aplicar_quimico').modal('show');" id="btn_aplicar_quimico_plantacion" class="btn btn-eliminar btn_administrador btn_operador btn-left btn-sm btn-primary"><i class="fa fa-flask"></i></a>

                        <a title="Scouting - Explorar" onclick="acres_plantados = 0;codigo_detalle = 0;$('#modal_explorar_plantacion').modal('show');" id="btn_explorar_plantacion" class="btn btn-eliminar btn-center-left btn_administrador btn_explorador btn-sm btn-success"><i class="fa fa-leaf"></i></a>

                        <a title="Observations - Observaciones" id="btn_modal_nueva_observacion" onclick="$('.plan_modal_nueva_observacion').modal('show');" class="btn btn-eliminar btn_administrador btn_operador btn_explorador btn_supervisor btn-center-right btn-sm btn-success"><i class="fa fa-comment"></i></a>

                        <a title="Calibrate - Calibrar" onclick="acres_plantados = 0;codigo_detalle = 0;$('#modal_calibrar').modal('show');" id="btn_calibrar_plantacion" class="btn btn-eliminar btn_administrador btn_operador btn_supervisor btn-right btn-sm btn-primary"><i class="fa fa-adjust"></i></a>

                        <?php

                    }*/

                    switch ($PLANTACION[0]['cod_estado']) {

                        case 2:

                    ?>

                            <a title="Apply Chemical - Aplicar Quimico" onclick="cod_aplicacion_quimico = 0;codigo_detalle = 0;$('#modal_aplicar_quimico').modal('show');" id="btn_aplicar_quimico_plantacion" class="btn btn-eliminar btn_supervisor btn_administrador btn_operador btn-left btn-sm btn-primary"><i class="fa fa-flask"></i></a>

                            <a title="Cleaning and Sanitizing of harvesting equipment" onclick="acres_plantados = 0;codigo_detalle = 0;$('#modal_cleaning_sanitizing').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_supervisor btn_administrador btn_explorador btn-center-left btn-sm btn-warning"><i class="fa fa-snowplow"></i></a>

                            <a title="Scouting - Explorar" onclick="acres_plantados = 0;codigo_detalle = 0;$('#modal_explorar_plantacion').modal('show');" id="btn_explorar_plantacion" class="btn btn-eliminar btn-center btn_administrador btn_explorador btn-sm btn-success"><i class="fa fa-leaf"></i></a>

                            <a title="Transplant - Trasplantar" onclick="$('#modal_trasplantar').modal('show');" class="btn btn-eliminar btn_administrador btn_supervisor btn_explorador btn-center btn-sm btn-default"><i class="fas fa-spa"></i></a>

                            <a title="Load Report" onclick="$('#modal_load_report').modal('show');codigo_reporte = 0;" class="btn btn-eliminar btn_administrador btn_supervisor btn_explorador btn-center btn-sm btn-default"><i class="fas fa-truck-loading"></i></a>

                            <a title="Calibrate - Calibrar" onclick="acres_plantados = 0;codigo_detalle = 0;$('#modal_calibrar').modal('show');" id="btn_calibrar_plantacion" class="btn btn-eliminar btn_administrador btn_explorador btn-center-right btn-sm btn-primary"><i class="fa fa-adjust"></i></a>

                            <a title="Observations - Observaciones" id="btn_modal_nueva_observacion" onclick="$('.plan_modal_nueva_observacion').modal('show');" class="btn btn-eliminar btn_administrador btn_operador btn_explorador btn_supervisor btn-right btn-sm btn-success"><i class="fa fa-comment"></i></a>

                        <?php

                            break;

                        case 1:

                        ?>

                            <a title="Apply Chemical - Aplicar Quimico" onclick="cod_aplicacion_quimico = 0;codigo_detalle = 0;$('#modal_aplicar_quimico').modal('show');" id="btn_aplicar_quimico_plantacion" class="btn btn-eliminar btn_administrador btn_operador btn-left btn-sm btn-primary"><i class="fa fa-flask"></i></a>

                            <a title="Cleaning and Sanitizing of harvesting equipment" onclick="codigo_detalle = 0;$('#modal_cleaning_sanitizing').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_supervisor btn_administrador btn_explorador btn-left-center btn-sm btn-warning"><i class="fa fa-snowplow"></i></a>

                            <a title="Scouting - Explorar" onclick="acres_plantados = 0;codigo_detalle = 0;$('#modal_explorar_plantacion').modal('show');" id="btn_explorar_plantacion" class="btn btn-eliminar btn-center btn_administrador btn_explorador btn-sm btn-success"><i class="fa fa-leaf"></i></a>

                            <a title="Calibrate - Calibrar" onclick="acres_plantados = 0;codigo_detalle = 0;$('#modal_calibrar').modal('show');" id="btn_calibrar_plantacion" class="btn btn-eliminar btn_administrador btn_explorador btn_supervisor btn-center btn-sm btn-primary"><i class="fa fa-adjust"></i></a>

                            <a title="Load Report" onclick="$('#modal_load_report').modal('show');codigo_reporte = 0;" class="btn btn-eliminar btn_administrador btn_supervisor btn_explorador btn-center btn-sm btn-default"><i class="fas fa-truck-loading"></i></a>

                            <a title="Transplant - Trasplantar" onclick="$('#modal_trasplantar').modal('show');" class="btn btn-eliminar btn_administrador btn_supervisor btn_explorador btn-center-right btn-sm btn-default"><i class="fas fa-spa"></i></a>

                            <a title="Observations - Observaciones" id="btn_modal_nueva_observacion" onclick="$('.plan_modal_nueva_observacion').modal('show');" class="btn btn-eliminar btn_administrador btn_operador btn_explorador btn_supervisor btn-right btn-sm btn-success"><i class="fa fa-comment"></i></a>

                        <?php

                            break;

                        case 3:

                        case 4:

                        ?>

                            <a title="Apply Chemical - Aplicar Quimico" onclick="cod_aplicacion_quimico = 0;codigo_detalle = 0;$('#modal_aplicar_quimico').modal('show');" id="btn_aplicar_quimico_plantacion" class="btn btn-eliminar btn_administrador btn_operador btn-left btn-sm btn-primary"><i class="fa fa-flask"></i></a>

                            <a title="Cleaning and Sanitizing of harvesting equipment" onclick="codigo_detalle = 0;$('#modal_cleaning_sanitizing').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-center-left btn-sm btn-warning"><i class="fa fa-snowplow"></i></a>

                            <a title="Scouting - Explorar" onclick="acres_plantados = 0;codigo_detalle = 0;$('#modal_explorar_plantacion').modal('show');" id="btn_explorar_plantacion" class="btn btn-eliminar btn-center btn_administrador btn_explorador btn-sm btn-success"><i class="fa fa-leaf"></i></a>

                            <a title="Calibrate - Calibrar" onclick="acres_plantados = 0;codigo_detalle = 0;$('#modal_calibrar').modal('show');" id="btn_calibrar_plantacion" class="btn btn-eliminar btn_administrador btn_explorador btn_supervisor btn-center btn-sm btn-primary"><i class="fa fa-adjust"></i></a>

                            <a title="Load Report" onclick="$('#modal_load_report').modal('show');codigo_reporte = 0;" class="btn btn-eliminar btn_administrador btn_supervisor btn_explorador btn-center btn-sm btn-default"><i class="fas fa-truck-loading"></i></a>

                            <a title="Transplant - Trasplantar" onclick="$('#modal_trasplantar').modal('show');" class="btn btn-eliminar btn_administrador btn_supervisor btn_explorador btn-center-right btn-sm btn-default"><i class="fas fa-spa"></i></a>

                            <a title="Observations - Observaciones" id="btn_modal_nueva_observacion" onclick="$('.plan_modal_nueva_observacion').modal('show');" class="btn btn-eliminar btn_administrador btn_operador btn_explorador btn_supervisor btn-right btn-sm btn-success"><i class="fa fa-comment"></i></a>

                        <?php

                            break;

                        case 6: //CRECIMIENTO

                        ?>

                            <a title="Cleaning and Sanitizing of harvesting equipment" onclick="codigo_detalle = 0;$('#modal_cleaning_sanitizing').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-left btn-sm btn-warning"><i class="fa fa-snowplow"></i></a>

                            <a title="Harvesting Worksheet" onclick="codigo_detalle = 0;$('#modal_harvesting_worksheet').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-center-left btn-sm btn-default"><i class="fab fa-pagelines"></i></a>

                            <a title="Transplant - Trasplantar" onclick="$('#modal_trasplantar').modal('show');" class="btn btn-eliminar btn_administrador btn_supervisor btn_explorador btn-center btn-sm btn-default"><i class="fas fa-spa"></i></a>

                            <a title="Load Report" onclick="$('#modal_load_report').modal('show');codigo_reporte = 0;" class="btn btn-eliminar btn_administrador btn_supervisor btn_explorador btn-center btn-sm btn-default"><i class="fas fa-truck-loading"></i></a>

                            <a title="Calibrate - Calibrar" onclick="acres_plantados = 0;codigo_detalle = 0;$('#modal_calibrar').modal('show');" id="btn_calibrar_plantacion" class="btn btn-eliminar btn_administrador btn_explorador btn_supervisor btn-center-right btn-sm btn-primary"><i class="fa fa-adjust"></i></a>

                            <a title="Observations - Observaciones" id="btn_modal_nueva_observacion" onclick="$('.plan_modal_nueva_observacion').modal('show');" class="btn btn-eliminar btn_administrador btn_operador btn_explorador btn_supervisor btn-right btn-sm btn-success"><i class="fa fa-comment"></i></a>

                        <?php

                            break;

                        case 5: //LISTO PARA COSECHAR

                        ?>

                            <a title="Pre-Operational/Harvesting Checklist" onclick="codigo_detalle = 0;$('#modal_harvesting_checklist').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-left btn-sm btn-info"><i class="fa fa-list-ol"></i></a>

                            <a title="Cleaning and Sanitizing of harvesting equipment" onclick="codigo_detalle = 0;$('#modal_cleaning_sanitizing').modal('show');" data-cod_bloque="<?php echo utf8_encode($bloque['cod_detalle']); ?>" class="btn btn-eliminar btn_administrador btn_explorador btn-center-left btn-sm btn-warning"><i class="fa fa-snowplow"></i></a>

                            <a title="Transplant - Trasplantar" onclick="$('#modal_trasplantar').modal('show');" class="btn btn-eliminar btn_administrador btn_supervisor btn_explorador btn-center btn-sm btn-default"><i class="fas fa-spa"></i></a>

                            <a title="Load Report" onclick="$('#modal_load_report').modal('show');codigo_reporte = 0;" class="btn btn-eliminar btn_administrador btn_supervisor btn_explorador btn-center btn-sm btn-default"><i class="fas fa-truck-loading"></i></a>

                            <a title="Calibrate - Calibrar" onclick="acres_plantados = 0;codigo_detalle = 0;$('#modal_calibrar').modal('show');" id="btn_calibrar_plantacion" class="btn btn-eliminar btn_administrador btn_operador btn_supervisor btn-center-right btn-sm btn-primary"><i class="fa fa-adjust"></i></a>

                            <a title="Observations - Observaciones" id="btn_modal_nueva_observacion" onclick="$('.plan_modal_nueva_observacion').modal('show');" class="btn btn-eliminar btn_administrador btn_explorador btn_explorador btn_supervisor btn-right btn-sm btn-success"><i class="fa fa-comment"></i></a>

                        <?php

                            break;

                        case 7: //QA

                        ?>

                            <a title="Load Report" onclick="$('#modal_load_report').modal('show');codigo_reporte = 0;" class="btn btn-eliminar btn_administrador btn_supervisor btn_explorador btn-left btn-sm btn-default"><i class="fas fa-truck-loading"></i></a>

                            <a title="Observations - Observaciones" id="btn_modal_nueva_observacion" onclick="$('.plan_modal_nueva_observacion').modal('show');" class="btn btn-eliminar btn_administrador btn_explorador btn_explorador btn_supervisor btn-right btn-sm btn-success"><i class="fa fa-comment"></i></a>

                    <?php

                            break;

                        default:

                            break;
                    }

                    ?>

                </div>

                <div class="col-md-6" align="right">

                    <button class="btn hide btn-sm btn-primary translate" data-traducir_english="Save Planting" data-traducir_spanish="Guardar Plantación" type="button" id="btn_guardar_plantacion">Guardar Plantación</button>

                    <button class="btn hide btn-sm btn-success translate" data-traducir_english="Plant" data-traducir_spanish="Plantar" type="button" id="btn_plantar">Plantar</button>

                    <button class="btn hide btn-sm btn-success translate" data-traducir_english="Trim" data-traducir_spanish="Podar" type="button" id="btn_podar">Podar</button>

                    <button class="btn hide btn-sm btn-success translate" data-traducir_english="Growth" data-traducir_spanish="Crecimiento" type="button" id="btn_crecimiento">Crecimiento</button>

                    <button class="btn hide btn-sm btn-success translate" data-traducir_english="Exploration" data-traducir_spanish="Exploraciones" type="button" id="btn_exploraciones">Exploraciones</button>

                    <button class="btn hide btn-sm btn-success translate" data-traducir_english="Fertilizer calibration" data-traducir_spanish="Calibración fertilizante" type="button" id="btn_calibrar_fertilizante">Calibración fertilizante</button>

                    <button class="btn hide btn-sm btn-success translate" data-traducir_english="Spraysheet" data-traducir_spanish="Aplicaciones químicas" type="button" id="btn_aplicar_quimicos">Aplicaciones químicas</button>

                    <button class="btn hide btn-sm btn-success translate" data-traducir_english="Harvest" data-traducir_spanish="Cosechar" type="button" id="btn_cosechar">Cosechar</button>

                    <button class="btn hide btn-sm btn-success translate" data-traducir_english="Harvested" data-traducir_spanish="Cosechado" type="button" id="btn_cosechado">Cosechado</button>

                    <button class="btn hide btn-sm btn-success translate" data-traducir_english="Quality control" data-traducir_spanish="Control de calidad" type="button" id="btn_control_calidad">Control de calidad</button>

                    <button class="btn hide btn-sm btn-success translate" data-traducir_english="Packed product" data-traducir_spanish="Producto empacado" type="button" id="btn_producto_empacado">Producto empacado</button>

                    <button class="btn hide btn-sm btn-success translate" data-traducir_english="Claims" data-traducir_spanish="Reclamaciones" type="button" id="btn_reclamaciones">Reclamaciones</button>

                    <button class="btn hide btn-sm btn-success translate" data-traducir_english="Finish" data-traducir_spanish="Terminar" type="button" id="btn_terminado">Terminar</button>

                    <button class="btn hide btn-sm btn-danger translate" data-traducir_english="Delete" data-traducir_spanish="Eliminar" type="button" id="btn_eliminado">Eliminar</button>

                    <button class="btn hide btn_administrador btn_supervisor btn-sm btn-success translate" data-traducir_english="Excel" data-traducir_spanish="Excel" type="button" id="btn_excel">Excel</button>

                    <button class="btn hide btn_administrador btn_supervisor btn_operador btn_explorador btn-sm btn-default translate" data-traducir_english="Offline" data-traducir_spanish="Offline" type="button" id="btn_offline">Offline</button>

                </div>

                <!-- <button class="btn btn-sm btn-success" type="button" id="btn_aplicar_quimico">Aplicar Químico</button>

                <button class="btn btn-sm btn-primary" type="button" id="btn_cosechar">Cosechar</button>

                <button class="btn btn-sm btn-warning" type="button" id="btn_suelo_malo">Suelo en mal estado</button>

                <button class="btn btn-sm btn-success" type="button" id="btn_aprobar_suelo">Aprobar suelo</button>

                <button class="btn btn-sm btn-warning" type="button" id="btn_cultivo_malo">Cultivo en mal estado</button>

                <button class="btn btn-sm btn-success" type="button" id="btn_aprobar_cultivo">Aprobar cultivo</button> -->

            </div>

        </div> <!-- panel-footer -->

    </div>

</body>