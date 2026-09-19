<?PHP
/*
 * Dasboard de la aplicación demo.
 * @author      Dan Urquía
 * @date        2016-25-5
 */

session_start();
if (!isset($_SESSION['cod_usuario'])) {
    header('Location: index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once("libs/db_classes/db_mysql_conn.php");
include_once("libs/db_classes/db_usuario.php");
include_once("libs/db_classes/db_general.php");
include_once("libs/db_classes/db_reportes.php");
/*INSTANCIAMIENTOS*/
$DB_USUARIO = new db_usuario();
$DB_GENERAL = new db_general();
$DB_REP     = new db_reportes();

date_default_timezone_set('America/New_York');
//$DB_REGISTRO  = new db_registro_oficiales();

$MODULOS_GENERALES  = $DB_GENERAL->get_modulos_por_perfil($_SESSION['cod_perfil'], $_SESSION['cod_usuario']);
$USUARIO            = $DB_USUARIO->get_info_usuario_by_cod_usuario($_SESSION['cod_usuario']);
$NOTICIAS           = (array) $DB_GENERAL->get_noticias_por_fecha(date("Y-m-d" . ' 08:00:00'));
// First day of this month
/*$d = Date('Y-m-d');
$primer_dia_mes = date('Y-m-01', strtotime($d));
$ultimo_dia_mes = date('Y-m-t', strtotime($d));

$primer_dia_semana = date("Y-m-d", strtotime('monday this week'));
$ultimo_dia_semana = date("Y-m-d", strtotime('sunday this week'));

//echo $primer_dia_semana. ' al '.$ultimo_dia_semana;

$INVENTARIO = $DB_REP->rep_reporte_inventario($primer_dia_mes,$ultimo_dia_mes);
$ACRES_PRODUCCION = $DB_REP->rep_reporte_acres_produccion($primer_dia_mes,$ultimo_dia_mes);
$PLANTACIONES_TIERRA = $DB_REP->rep_reporte_plantaciones_tierra($primer_dia_mes,$ultimo_dia_mes);
$PLANTACIONES_WATERCRESS = $DB_REP->rep_reporte_plantaciones_watercress($primer_dia_mes,$ultimo_dia_mes);*/
//echo date("Y-m-d H:i:s");

?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb18030">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, height=device-height, viewport-fit=cover">

    <!-- Favicon & Icons -->
    <link rel="icon" type="image/x-icon" href="libs/imgs/icons/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="libs/imgs/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="libs/imgs/icons/favicon-16x16.png">
    <link rel="shortcut icon" href="libs/imgs/icons/favicon.ico">

    <!-- Android & PWA Mobile Shortcuts -->
    <meta name="theme-color" content="#ffffff">
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="manifest" href="manifest.json">

    <!-- Apple iOS Safari (Add to Home Screen) -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Fenix">
    <link rel="apple-touch-icon" href="libs/imgs/icons/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="180x180" href="libs/imgs/icons/apple-touch-icon.png">

    <!-- SEO & Social Meta (Open Graph / Twitter) -->
    <meta name="description" content="Fenix Platform">
    <meta property="og:title" content="Fenix">
    <meta property="og:description" content="Fenix Platform">
    <meta property="og:type" content="website">
    <meta property="og:image" content="libs/imgs/icons/logo.png">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Fenix">
    <meta name="twitter:description" content="Fenix Platform">
    <meta name="twitter:image" content="libs/imgs/icons/logo.png">

    <title>Fenix</title>
    <link rel="stylesheet" href="libs/css/style.css"> <!-- Resource style -->
    <!-- jQuery -->
    <script type="text/javascript" src="libs/jQuery/jquery-2.1.3.js"></script>
    <script type="text/javascript" src="libs/jQuery/jquery.mask.min.js"></script>
    <script type="text/javascript" src="libs/jQuery/date.js"></script>
    <script type="text/javascript" src="libs/jQuery/jquery-ui.js"></script>
    <script type="text/javascript" src="libs/jQuery/accounting.min.js"></script>
    <script type="text/javascript" src="libs/jQuery/jquery.PrintArea.js"></script>
    <script type="text/javascript" src="libs/jQuery/jquery.sticky.js"></script>

    <!-- Bootstrap core CSS -->
    <script src="libs/bootstrap/js/bootstrap.min.js"></script>
    <link href="libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <!-- <script src="libs/js/modernizr.js"></script> --> <!-- Modernizr -->
    <!-- <script type="text/javascript" src="libs/bootstrap/js/utils.js"></script>
    <script type="text/javascript" src="libs/bootstrap/js/tether.js"></script> -->
    <script type="text/javascript" src="libs/bootstrap/js/bootstrap.js"></script>
    <script type="text/javascript" src="libs/bootstrap/js/jquery.bootstrap-growl.min.js"></script>
    <script type="text/javascript" src="libs/bootstrap/js/collapse.js"></script>
    <script type="text/javascript" src="libs/bootstrap/js/transition.js"></script>
    <script type="text/javascript" src="libs/bootstrap/js/moment.js"></script>
    <script type="text/javascript" src="libs/bootstrap/js/moment-range.js"></script>
    <script type="text/javascript" src="libs/bootstrap/js/spin.min.js"></script>
    <script type="text/javascript" src="libs/bootstrap/js/ladda.min.js"></script>
    <script type="text/javascript" src="libs/bootstrap/js/bootstrap-switch.js"></script>
    <script type="text/javascript" src="libs/bootstrap/js/bootstrap.file-input.js"></script>

    <!-- Bootstrap core CSS -->
    <!-- <link rel="stylesheet" type="text/css" href="libs/bootstrap/css/bootstrap.css"> -->
    <link rel="stylesheet" type="text/css" href="libs/bootstrap/css/bootstrap-select.css">
    <link rel="stylesheet" type="text/css" href="libs/bootstrap/css/bootstrapValidator.min.css">
    <link rel="stylesheet" type="text/css" href="libs/bootstrap/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="libs/bootstrap/css/bootstrap-switch.css">
    <!--<link rel="stylesheet" type="text/css" href="libs/bootstrap/css/ladda-theme.min.css">-->
    <link rel="stylesheet" type="text/css" href="libs/DataTables/media/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="libs/DataTables/extensions/integration/bootstrap/3/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="libs/DataTables/extensions/Responsive/css/dataTables.responsive.css">
    <link rel="stylesheet" type="text/css" href="libs/DataTables/extensions/TableTools/css/dataTables.tableTools.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="libs/css/table_responsive.css">

    <script type="text/javascript" src="libs/bootstrap/js/bootstrap.js"></script>
    <script type="text/javascript" src="libs/bootstrap/js/bootstrap-select.js"></script>
    <script type="text/javascript" src="libs/bootstrap/js/bootstrap3-typeahead.js"></script>
    <script type="text/javascript" src="libs/bootstrap/js/bootstrapValidator.min.js"></script>
    <script type="text/javascript" src="libs/bootstrap/js/bootstrap-datetimepicker.js"></script>
    <script type="text/javascript" src="libs/bootstrap/js/bootstrap-datetimepicker.es.js"></script>
    <script type="text/javascript" src="libs/DataTables/media/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="libs/DataTables/extensions/integration/bootstrap/3/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="libs/DataTables/extensions/Responsive/js/dataTables.responsive.js"></script>
    <script type="text/javascript" src="libs/DataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>
    <!-- <script type="text/javascript" src="libs/DataTables/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script> -->
    <script type="text/javascript" src="libs/DataTables/extensions/FixedHeader/js/dataTables.fixedHeader.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
    <!-- Font Awesome -->
    <!-- <link rel="stylesheet" type="text/css" href="libs/font-awesome/css/font-awesome.min.css"> -->
    <!-- <link rel="stylesheet" type="text/css" href="libs/DataTables/extensions/integration/font-awesome/dataTables.fontAwesome.css"> -->
    <!-- HighCharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script type="text/javascript" src="libs/Highcharts/js/adapters/standalone-framework.js"></script>
    <script type="text/javascript" src="libs/Highcharts/js/modules/exporting.js"></script>
    <script type="text/javascript" src="libs/Highcharts/js/modules/heatmap.js"></script>
    <!-- Librerias propias -->
    <script type="text/javascript" src="libs/funciones/func_generales.js"></script>
    <script type="text/javascript" src="libs/funciones/func_usuarios.js"></script>
    <script type="text/javascript" src="libs/funciones/func_geografias.js"></script>
    <script type="text/javascript" src="libs/funciones/func_plantaciones.js"></script>
    <script type="text/javascript" src="libs/funciones/func_configuracion.js"></script>
    <script type="text/javascript" src="libs/funciones/func_inventario.js"></script>
    <script type="text/javascript" src="libs/funciones/func_granjas.js"></script>
    <script type="text/javascript" src="libs/funciones/func_control_calidad.js"></script>
    <script type="text/javascript" src="libs/funciones/func_documentacion.js"></script>
    <!-- Calendar -->
    <script src="libs/calendar_master/components/underscore/underscore-min.js"></script>
    <script src="libs/calendar_master/js/calendar.js"></script>
    <!-- <script src="libs/calendar_master/js/language/es-MX.js"></script> -->
    <link rel="stylesheet" type="text/css" href="libs/calendar_master/css/calendar.css">

    <link rel="stylesheet" type="text/css" href="libs/css/modal_styles.css">
    <link rel="stylesheet" type="text/css" href="libs/css/general.css">
    <link rel="stylesheet" type="text/css" href="libs/css/planificacion.css">
    <link rel="stylesheet" type="text/css" href="libs/css/styles.css">

    <!-- ListPicker -->
    <link rel="stylesheet" href="libs/list-picker/list-picker.css">
    <script src="libs/list-picker/list-picker.js" ></script>

    <!-- Font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<script>
    function quitLoading() {
        $("#loader").hide();
        $("#cuerpo").show();
    }
    /* Deshabilita el click derecho */
    $(document).ready(function() {
        // $(document).bind("contextmenu",function(e){
        //     return false;
        // });
        $(".dropdown-menu a").click(function(event) {
            if ($(window).width() < 768) {
                $(".navbar-collapse").collapse('hide');
            }
        });
        $('#checkbox_translate').change(function(event) {
            /* Act on the event */
            event.preventDefault();
            event.stopPropagation();
            ($('#checkbox_translate').attr('checked') ? $('#checkbox_translate').removeAttr('checked') : $('#checkbox_translate').attr('checked', 'checked'))
            grl_traducir_interfaz(($('#checkbox_translate').attr('checked') ? 1 : 0));
        });
        $('#div_cuerpo').on('hidden.bs.modal', '#modal_loading', function() {
            grl_traducir_interfaz(($('#checkbox_translate').attr('checked') ? 1 : 0));
            if (1 != <?php echo $_SESSION['cod_perfil']; ?>) {
                jQuery("#cod_info_empresa > option").each(function() {
                    if (jQuery.inArray(jQuery(this).val(), <?php echo $_SESSION['cod_info_empresa']; ?>) === -1) {
                        //console.log('item: ' + jQuery(this).val());
                        jQuery(this).attr('disabled', 'disabled');
                    }
                });
                $('#cod_info_empresa').selectpicker('refresh');
            }
        });
        grl_traducir_interfaz(($('#checkbox_translate').attr('checked') ? 1 : 0));
        //inv_verificar_notificar_inventario_alerta();
        var $loading = $('#loader').hide();
        $(document)
            .ajaxStart(function() {
                jQuery.ajaxSetup({
                    async: false
                });
                if (!$('body').hasClass('modal-open')) {
                    // $loading.show();
                    // $("#cuerpo").hide();
                    return false;
                }
                jQuery.ajaxSetup({
                    async: true
                });
            })
            .ajaxStop(function() {
                jQuery.ajaxSetup({
                    async: false
                });
                $loading.hide();
                $("#cuerpo").show();
                return false;
                jQuery.ajaxSetup({
                    async: true
                });
            });
    });
</script>


<body onload="quitLoading()" class="green-theme">
    <!-- Static navbar -->
    <div id="loader"><img class="col-md-12 col-xs-6 col-xs-offset-3" src="/libs/imgs/Loading.gif" alt="" width="150" height="150"></div>
    <div id="cuerpo" style="display:none;" class="animate-bottom">
        <nav class="navbar navbar-fixed-top top_nav" align="center">
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="li_height"><a class="icono_blanco" href="./dashboard.php"><i class="fa fa-home" aria-hidden="true"></i></a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle icono_blanco" data-toggle="dropdown"><i class="fa fa-bars" aria-hidden="true"></i> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <?PHP
                            $TIPOS_MODULOS = (array)$DB_GENERAL->get_tipos_modulos();
                            foreach ($TIPOS_MODULOS as $TIPO_MODULO) {
                                echo '<div class="menu-label-wrapper"><span class="tipo-modulo translate" data-traducir_english="' . utf8_encode($TIPO_MODULO['module_type']) . '" data-traducir_spanish="' . utf8_encode($TIPO_MODULO['tipo_modulo']) . '">' . $TIPO_MODULO['tipo_modulo'] . '</span></div>';
                                $MODULOS = (array) $DB_GENERAL->get_modulos_por_perfil_por_tipo($_SESSION['cod_perfil'], $_SESSION['cod_usuario'], $TIPO_MODULO['cod_tipo_modulo']);
                                foreach ($MODULOS as $ROW) {
                                    echo '<li value="' . $ROW['cod_modulo'] . '" onClick="grl_obtener_modulo(' . $ROW['cod_modulo'] . ');"><a href="#" class="translate" data-traducir_english="' . utf8_encode($ROW['modulo_english']) . '" data-traducir_spanish="' . utf8_encode($ROW['modulo']) . '">' . utf8_encode($ROW['modulo']) . '</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </li>
                    <!-- <li class="dropdown " onclick="grl_obtener_perfil_usuario();">
                        <a href="#" class="dropdown-toggle icono_blanco" data-toggle="dropdown"><?PHP echo utf8_encode($_SESSION['nombre']); ?></b></a>
                    </li> -->
                    <li class="navbar-translate">
                        <label class="label-translate translate" data-traducir_english="Traducir" data-traducir_spanish="Translate">Translate </label>
                        <div class="material-switch pull-right">
                            <input id="checkbox_translate" data-id="checkbox_translate" name="checkbox_translate" type="checkbox" <?php echo ($USUARIO[0]['flag_traducir'] == 1 ? 'checked="checked"' : ''); ?> />
                            <label for="checkbox_translate" class="checkbox-translate"></label>
                        </div>
                    </li>
                    <?php
                    if ($_SESSION['cod_usuario'] == 1 || $_SESSION['cod_usuario'] == 2 || $_SESSION['cod_usuario'] == 3 || $_SESSION['cod_usuario'] == 4) {
                    ?>

                        <!-- <li class="navbar-translate">
                            <label>
                                <button style="margin-left: 50px;border:none;color:white;background-color:transparent" id="access-to-payrollapp"> Time Card Detail </button>
                                <a href="/redirect.php" style="margin-left: 50px;" target="_self" rel=""> Time Card Detail</a>
                            </label>
                        </li> -->
                    <?php
                    }
                    ?>
                    <li class="dropdown navbar-right">
                        <a href="#" class="dropdown-toggle icono_blanco" data-toggle="dropdown">
                            <i class="fa fa-user" aria-hidden="true"><b class="caret"></b></i> <?PHP echo utf8_encode($INFO_EMPLEADO[0]['nombre_1'] . ' ' . $INFO_EMPLEADO[0]['apellido_1']); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a onclick="grl_obtener_perfil_usuario();"><?php echo utf8_encode($_SESSION['nombre']); ?></a></li>
                            <?php if ($_SESSION['cod_perfil'] == 1) {
                            ?>
                                <!--<li><a onclick="grl_obtener_manejo_usuarios();">Manejo de usuarios</a></li>-->
                            <?php
                            }
                            ?>
                            <li><a href="libs/funciones/destruir_sesion.php" class="translate" data-traducir_english="Logout" data-traducir_spanish="Cerrar sesión">Cerrar sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="container contenedor_cuerpo" id="div_cuerpo" name="div_cuerpo">
            <!--<div class="row">
                <div class="col-md-2 col-md-offset-5 col-sm-6 col-sm-offset-3 col-xs-6 col-xs-offset-3">
                    <img src="libs/imgs/Lion Farming.png" class="logo-dashboard" width="100%">
                </div>
            </div>
             <div class="row">
                <div class="col-md-12">
                    <form method="post" id="form_vdd" action="http://dev-vdd.unah.edu.hn/index.php">
                        <input type="text" name="j" id="j">
                        <button type="submit" class="btn btn-sm btn-primary">Ir</button>
                    </form>
                </div>
            </div> -->
            <div class="jumbotron">
                <h2 class="translate" data-traducir_english="Internal Agenda" data-traducir_spanish="Agenda Interna">Agenda Interna</h2>
                <hr>
                <div class="row">
                    <?PHP
                    foreach ($NOTICIAS as $ROW) {
                        if (!empty($ROW['titulo'])) {
                            $fecha = explode(" ", $ROW['fecha_insert']);
                    ?>
                            <div class="col-md-4 col-4 col-lg-4 col-sm-12 col-xs-12 table-bordered">
                                <div class="row">
                                    <div class="col-6 col-sm-6 col-lg-6 col-xs-6" align="center">
                                        <img src="<?PHP echo $ROW['imagen']; ?>" onerror="this.src='libs/imgs/Logo.png';" width="200" height="200" class="img-circle img-responsive">
                                    </div>
                                    <div class="col-6 col-sm-6 col-lg-6 col-xs-6" align="justify">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h3><span><?PHP echo utf8_encode($ROW['titulo']); ?></span></h3>
                                                <!-- <span class="label label-info"><?PHP echo $fecha[0]; ?></span> -->
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p class="text-justify"><?PHP echo utf8_encode($ROW['contenido']); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?PHP
                        }
                    }
                    ?>
                </div>
            </div>
            <!-- -------------------------CALENDARIO---------------------------------------- -->
            <div class="col-md-12">
                <div class="page-header">
                    <div class="pull-right form-inline">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-primary" data-calendar-nav="prev"><i class="fa fa-angle-double-left"></i> Prev</button>
                            <button class="btn btn-sm " data-calendar-nav="today">Today</button>
                            <button class="btn btn-sm btn-primary" data-calendar-nav="next">Next <i class="fa fa-angle-double-right"></i></button>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-warning" data-calendar-view="year">Year</button>
                            <button class="btn btn-sm btn-warning active" data-calendar-view="month">Month</button>
                            <button class="btn btn-sm btn-warning" data-calendar-view="week">Week</button>
                            <button class="btn btn-sm btn-warning" data-calendar-view="day">Day</button>
                        </div>
                    </div>

                    <h3></h3>
                    <small class="translate" data-traducir_english="News, events and announcements" data-traducir_spanish="Noticias, eventos hasta la fecha de hoy">Noticias, eventos hasta la fecha de hoy</small>
                </div>
                <div class="modal fade" id="events-modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h3>Event</h3>
                            </div>
                            <div class="modal-body" style="height: 400px">
                            </div>
                            <div class="modal-footer">
                                <a href="#" data-dismiss="modal" class="btn">Cerrar</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="calendar"></div>
                <script src="libs/calendar_master/components/underscore/underscore-min.js"></script>
                <script src="libs/calendar_master/js/calendar.js"></script>
                <!-- <script src="libs/calendar_master/js/language/es-MX.js"></script> -->
                <script type="text/javascript">
                    jQuery.ajaxSetup({
                        async: false
                    });
                    var calendar = $("#calendar").calendar({
                        tmpl_path: "libs/calendar_master/tmpls/",
                        //language: 'es-MX',
                        events_source: 'mod_noticias/funciones/not_llenar_calendario.php',
                        modal: "#events-modal",
                        modal_type: "ajax",
                        modal_title: function(e) {
                            return e.title
                        },
                        onAfterEventsLoad: function(events) {
                            if (!events) {
                                return;
                            }
                            var list = $('#eventlist');
                            list.html('');

                            $.each(events, function(key, val) {
                                $(document.createElement('li'))
                                    .html('<a href="' + val.url + '">' + val.title + '</a>')
                                    .appendTo(list);
                            });
                        },
                        onAfterViewLoad: function(view) {
                            $('.page-header h3').text(this.getTitle());
                            $('.btn-group button').removeClass('active');
                            $('button[data-calendar-view="' + view + '"]').addClass('active');
                        },
                        classes: {
                            months: {
                                general: 'label'
                            }
                        }
                    });


                    $('.btn-group button[data-calendar-nav]').each(function() {
                        var $this = $(this);
                        $this.click(function() {
                            calendar.navigate($this.data('calendar-nav'));
                        });
                    });

                    $('.btn-group button[data-calendar-view]').each(function() {
                        var $this = $(this);
                        $this.click(function() {
                            calendar.view($this.data('calendar-view'));
                        });
                    });

                    $('#first_day').change(function() {
                        var value = $(this).val();
                        value = value.length ? parseInt(value) : null;
                        calendar.setOptions({
                            first_day: value
                        });
                        calendar.view();
                    });

                    $('#language').change(function() {
                        calendar.setLanguage($(this).val());
                        calendar.view();
                    });

                    $('#events-in-modal').change(function() {
                        var val = $(this).is(':checked') ? $(this).val() : null;
                        calendar.setOptions({
                            modal: val
                        });
                    });
                    jQuery.ajaxSetup({
                        async: true
                    });
                </script>
                <div class="col-md-9">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</body>
