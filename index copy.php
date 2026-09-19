<?php
/*
 * Inicio de sesion DEMO|BlueLionSoft.
 * @author      Jairo Bonilla, Dan Urquía
 * @date        2016-04-26
 */
session_start();
$flag = '0';
$USUARIO[0]['flag_traducir'] = 1;
/*WHITELIST*/
include_once("libs/funciones/func_whitelist.php");
$WHITELIST  = new db_whitelist();
$mensaje_error = "1|Error, favor verificar que la información ingresada cumpla con los estandares de seguridad.";

/*CONEXION CON BASE DE DATOS*/
include_once("libs/db_classes/db_mysql_conn.php");
include_once("libs/db_classes/db_usuario.php");
include_once("libs/db_classes/db_general.php");
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, height=device-height, viewport-fit=cover">

    <link rel="shortcut icon" type="image/ico" href="libs/imgs/icons/favicon.ico" />
    <link rel="manifest" href="manifest.json">

    <!-- Android-->
    <meta name="theme-color" content="#3498db">


    <!-- IOS-->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="apple-touch-icon" href="libs/img/icons/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="152x152" href="libs/img/icons/apple-icon-180x180.png">
    <link rel="apple-touch-icon" sizes="180x180" href="libs/img/icons/apple-icon-180x180.png">
    <link rel="apple-touch-icon" sizes="167x167" href="libs/img/icons/apple-icon-180x180.png">

    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <meta name="apple-mobile-web-app-title" content="Fenix">
    <title>Fenix</title>
    <link rel="stylesheet" href="libs/css/style.css"> <!-- Resource style -->
    <!-- jQuery -->
    <script type="text/javascript" src="libs/jQuery/jquery-2.1.3.js"></script>
    <!-- Bootstrap core CSS -->
    <script src="libs/bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link href="libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="libs/bootstrap/js//bootstrap.min.js"></script>
    <script type="text/javascript" src="libs/bootstrap/js/jquery.bootstrap-growl.min.js"></script>
    <script type="text/javascript" src="libs/funciones/func_generales.js"></script>
    <script type="text/javascript" src="libs/funciones/func_configuracion.js"></script>
    <link rel="stylesheet" type="text/css" href="libs/css/modal_styles.css">
    <link rel="stylesheet" type="text/css" href="libs/css/general.css">
    <link rel="stylesheet" type="text/css" href="libs/css/styles.css">

    <script src="libs/js/app.js"></script>

</head>
<?php
/*INSTANCIAMIENTOS*/
$DB_USUARIO   = new db_usuario();
$DB_GENERAL   = new db_general();

$INFO_SISTEMA = $DB_GENERAL->get_info_sistema();

$ip = $_SERVER['HTTP_CLIENT_IP'] ? $_SERVER['HTTP_CLIENT_IP'] : ($_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
if (isset($_POST['usuario'])) {
    //get login intentos. Se leen los intentos primero para evitar que un robot llene la tabla de intentos con registros infinitos
    $intentos = $DB_USUARIO->get_login_intentos($_POST['usuario'], $ip);
    if ($intentos[0]['intento'] < 5) {
        $usuario = trim($_POST['usuario']);
        $pass    = trim($_POST['password']);
        $flag    = $DB_USUARIO->get_login_passPending($usuario, $pass);
        if (isset($flag[0]['cod_usuario'])) {
            $_SESSION['cod_usuario']          = $flag[0]['cod_usuario'];
            $_SESSION['úrl_inicio'] = "https://lmfdata.com/index.php";
            $cod_usuario = $flag[0]['cod_usuario'];
            $_SESSION['usuario']               = $flag[0]['usuario'];
            $_SESSION['nombre']         = $flag[0]['nombre'];
            $_SESSION['cod_perfil']         = $flag[0]['cod_perfil'];
            $_SESSION['cod_cargo']         = $flag[0]['cod_cargo'];
            $_SESSION['cod_gerencia']         = $flag[0]['cod_gerencia'];
            $_SESSION['descripcion']         = $flag[0]['descripcion'];
            $_SESSION['cod_pais']         = $flag[0]['cod_pais'];
            $_SESSION['cod_departamento']         = $flag[0]['cod_departamento'];
            $_SESSION['pass_pending'] = $flag[0]['pass_pending'];
            $_SESSION['cod_info_empresa'] = $flag[0]['cod_info_empresa'];
            if ($flag[0]['pass_pending'] == 1) {
                $flag = '0';
                echo "<script>	$.ajax({
  					type: 'POST',
  					url: 'mod_admin_usuarios/ui/usu_cambio_password.php',
  					success: function(data) {
  						$( \"#div_login\" ).empty().append(data);
  					}
  				});</script>";
            } else if ($flag[0]['pass_pending'] == 2) {
                $flag = '0';
                echo "<script>    $.ajax({
  			      type: 'POST',
  			      url: 'mod_admin_usuarios/ui/usu_password_expired.php',
  			      success: function(data) {
  			        $( \"#div_login\" ).empty().append(data);
  			      }
  			    });</script>";
            } else if ($flag[0]['pass_pending'] == 3) {
                //usuario fue bloqueado por demasiados intentos fallidos en menos de una hora.
                $flag = "Usuario fue bloqueado por intentos fallidos continuos. Por favor espere 30 minutos para volver a intentarlo o contacte a su administrador.";
            } else {
                //ingresar en historial de ingresos
                $result = $DB_USUARIO->insert_historial_ingresos($cod_usuario, $ip);
                echo "<script>window.location = 'dashboard.php'</script>";
            }
        } else {
            //se registra el intento fallido en tabla usu_login para control de intentos de acceso
            $result = $DB_USUARIO->insert_login_intento($_POST['usuario'], $ip);
            $intentos = $DB_USUARIO->get_login_intentos($_POST['usuario'], $ip);
            if ($intentos[0]['intento']  >= 5) {
                //seteamos el pass_pending en 3 para bloquear el usuario.
                $result = $DB_USUARIO->bloquear_usuario($_POST['usuario']); //cambiamos el pass_pending = 3 para que el usuario no lo pueda seguir intentando desde otros dispositivos
                $result = $DB_USUARIO->insert_login_bloqueo($_POST['usuario'], $ip); //insertamos en historial de bloqueao para reportes
                $flag = "Usuario fue bloqueado por intentos fallidos continuos. Por favor espere 30 minutos para volver a intentarlo o contacte a su administrador.";
            }
        }
    } else {
        $flag = "Usuario fue bloqueado por intentos fallidos continuos. Por favor espere 30 minutos para volver a intentarlo o contacte a su administrador.";
    }
}
?>
<script>
    $('#login_form').submit(function() {
        $('#loader').css('visibility', 'visible');
    });

    function quitLoading() {
        $("#loader").hide();
        $("#cuerpo").show();
    }
    $(document).ready(function() {
        traduccionPredeterminada();
        $flag_envio_correo = 0;
        var flag_alert = <?PHP echo "'" . $flag . "'"; ?>;
        if (flag_alert != 0) {
            $('.alert').alert().show();
        } else {
            $('.alert').hide();
        }

        /* Click en botón olvide contraseña */
        $("#olvide").click(function() {
            //Manda a traer el formulario de Olivde contraseña
            $.ajax({
                type: 'POST',
                url: 'mod_admin_usuarios/ui/usu_olvide_password.php',
                success: function(data) {
                    $("#div_login").empty().append(data);
                }
            });
        });
        $('#checkbox_translate').change(function(event) {
            /* Act on the event */
            event.preventDefault();
            event.stopPropagation();
            ($('#checkbox_translate').attr('checked') ? $('#checkbox_translate').removeAttr('checked') : $('#checkbox_translate').attr('checked', 'checked'))
            grl_traducir_interfaz(($('#checkbox_translate').attr('checked') ? 1 : 0));
        });

    }); //Fin ready
    function traduccionPredeterminada() {
        event.preventDefault();
        event.stopPropagation();
        $('#checkbox_translate').attr('checked', 'checked');
        grl_traducir_interfaz(1);
    }
</script>


<body onload="quitLoading()" class="green-theme">
    <div id="loader"><img class="col-md-12 col-xs-6 col-xs-offset-3" src="/libs/imgs/Loading.gif" alt="" width="150" height="150"></div>
    <div class="container" id="cuerpo" style="display:none;">
        <?php
        if ($INFO_SISTEMA[0]['flag_mantenimiento'] == 0) {
        ?>

            <div class="row">
                <div class="col-md-12" align="center">
                    <img src="libs/imgs/Logo.png" width="50%" style="max-width: 250px;">
                </div>
            </div>
            <hr>
            <div id="div_login">
                <div class="row">
                    <div class="alert alert-danger alert-dismissable  col-md-6 col-md-offset-3" align="center">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?PHP echo $flag; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-md-offset-4"><!-- Form -->
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="nabvar-translate">
                                    <label class="label-translate translate" style="color: black;" data-traducir_english="Traducir" data-traducir_spanish="Translate">Translate</label>
                                    <div class="material-switch pull-right">
                                        <input id="checkbox_translate" data-id="checkbox_translate" name="checkbox_translate" type="checkbox" <?php echo ($USUARIO[0]['flag_traducir'] == 1 ? 'checked="checked"' : ''); ?> />
                                        <label for="checkbox_translate" class=""></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form class="form" id="login_form" role="form" method="post">
                            <div class="form-group input-group-sm">
                                <label for="usuario" style="color: black;" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</label>
                                <input type="text" class="form-control" id="usuario" name="usuario" placeholder="User">
                            </div>
                            <div class="form-group input-group-sm">
                                <label for="password" style="color: black;" class="translate" data-traducir_english="Password" data-traducir_spanish="Contraseña">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                            </div>
                            <button id="ingresar" name="ingresar" class="btn btn-sm btn-primary btn-block translate" data-traducir_english="Login" data-traducir_spanish="Ingresar" type="submit">Ingresar</button>
                            <button id="olvide" name="olvide" class="btn btn-link btn-block translate" data-traducir_english="Forgot Password?" data-traducir_spanish="Olvidé mi contraseña" type="button">Olvidé mi contraseña</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php
        } else {
        ?>

            <div class="row">
                <div class="col-md-12" align="center">
                    <img src="libs/imgs/Logo.png" width="50%" style="max-width: 250px;">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="nabvar-translate">
                        <label class="label-translate translate" data-traducir_english="Traducir" data-traducir_spanish="Translate">Translate</label>
                        <div class="material-switch pull-right">
                            <input id="checkbox_translate" data-id="checkbox_translate" name="checkbox_translate" type="checkbox" <?php echo ($USUARIO[0]['flag_traducir'] == 1 ? 'checked="checked"' : ''); ?> />
                            <label for="checkbox_translate" class=""></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3 text-center">
                    <i class="fas fa-spin fa-cog icon-under-maintenance"></i>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="translate" data-traducir_english="Under Maintenance" data-traducir_spanish="Estamos en Mantenimiento">Estamos en Mantenimiento</h2>
                </div>
            </div>
        <?php
        }
        ?>
    </div> <!-- /container -->
    <footer class="footer">
        <div class="container">
            <p class="text-muted">
            <h6 style="color: white;">&copy; Fenix <?php echo date("Y"); ?>.</h6>
            </p>
        </div>
    </footer>
</body>