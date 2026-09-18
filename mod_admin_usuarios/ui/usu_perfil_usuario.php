<?PHP
/*
 * Pantalla donde se visualiza la información basica del usuario en sesión.
 * @author      Dan Urquía
 * @date        2014-02-09
 */

session_start();
if (!isset($_SESSION['cod_usuario'])) {
    	echo "<script>window.location.href = '" . $_SESSION['úrl_inicio'] . "';</script>"; // Usando JS para redireccionar
	// header('Location: ../index.php'); // Método anterior (No funcional)
}
/*CONEXION CON BASE DE DATOS*/
include_once "../../libs/db_classes/db_mysql_conn.php";
include_once "../../libs/db_classes/db_usuario.php";

/*INSTANCIAMIENTOS*/
$DB_USUARIO   = new db_usuario();
$INFO_USUARIO = $DB_USUARIO->usu_get_info_usuario($_SESSION['cod_usuario']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <title>Usuario</title>
    <style>
        .contenedor_cuerpo,
        .container {
            height: 100%;
        }

        .popover-content {

            font-size: 15px;
            font-family: "Times New Roman", Times, serif;
        }

        #confirm_pass,
        #nuevo_pass,
        #pass_actual,
        #usuario {
            border-bottom-right-radius: 5px;
            border-top-right-radius: 5px;
        }

        .pad_bot {
            padding-bottom: 20px;
        }

        .popover-content {
            font-size: 15px;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

        #confirm_pass,
        #nuevo_pass {
            border-bottom-right-radius: 5px;
            border-top-right-radius: 5px;
        }
    </style>

</head>

<body>
    <div id="overlay_loading"></div>
    <div class="container">
        <div class="well well-lg">
            <!--header -->
            <div class="page-header">
                <h1 class="translate" data-traducir_english="General info of the user" data-traducir_spanish="Información general del usuario">Informaci&oacuten general del usuario</h1>
            </div>

            <div class="panel panel-default">

                <!--body-->
                <div class="panel-body">
                    <center>
                        <!--1parte-body-->
                        <img onerror="this.src='../../libs/imgs/usuario.jpg';" src="<?PHP echo $INFO_USUARIO[0]['fotografia']; ?>" width="200" height="200" class="img-circle">
                        </br>
                        <!--2parte-body-->
                        <div class="row" align="center" style="overflow:auto; padding-bottom:5px ">
                            <div class="col-md-3">
                                <label for="nombre" class="translate" data-traducir_english="User name" data-traducir_spanish="Nombre del usuario">Nombre del usuario</label>
                                <h4><span class="label label-warning">
                                        <?PHP echo ($INFO_USUARIO[0]['nombre_1']) . ' ' .
                                            utf8_encode($INFO_USUARIO[0]['nombre_2']) . ' ' .
                                            utf8_encode($INFO_USUARIO[0]['apellido_1']) . ' ' .
                                            utf8_encode($INFO_USUARIO[0]['apellido_2']); ?>
                                    </span></h4>
                            </div>
                            <div class="col-md-3" align="center">
                                <label for="correo" name="correo" id="correo" class="translate" data-traducir_english="Email" data-traducir_spanish="Correo electrónico">Correo electr&oacutenico </label>
                                <h4><span class="label label-info" style="alignment-adjust:central">
                                        <?PHP echo $INFO_USUARIO[0]['email']; ?>
                                    </span></h4>
                            </div>
                            <div class="col-md-3" align="center">
                                <label for="gerencia" class="translate" data-traducir_english="Company" data-traducir_spanish="Gerencia">Gerencia</label>
                                <h4><span class="label label-primary" style="alignment-adjust:central">
                                        <?PHP echo utf8_encode($INFO_USUARIO[0]['gerencia']); ?>
                                    </span></h4>
                            </div>
                            <div class="col-md-3" align="center">
                                <label for="cargo" class="translate" data-traducir_english="Charges" data-traducir_spanish="Cargo">Cargo</label>
                                <h4><span class="label label-danger" style="alignment-adjust:central">
                                        <?PHP echo utf8_encode($INFO_USUARIO[0]['cargo']); ?>
                                    </span></h4>
                            </div>
                            <!-- <div class="col-md-2" style="width:auto"  align="center">
                                             <label for="jefe"  align="center" class="translate" data-traducir_english="Inventory" data-traducir_spanish="Inventario">Jefe Inmediato </label>
                                             <h4><span class="label label-success" style="alignment-adjust:central">
                                             <?PHP echo $INFO_USUARIO[0]['nombre_jefe']; ?>
                                             </span></h4>
                                    </div> -->
                            <!-- <div class="col-md-1">
                                    </div> -->
                        </div>

                        <div class="col-12 col-sm-12 col-lg-12">
                            <hr>
                        </div>

                        <div class="row" align="center">
                            <div class="form-group">
                                <label class="translate" data-traducir_english="Change Password" data-traducir_spanish="Cambiar contraseña">Cambiar contrase&ntilde;a</label>
                            </div>
                        </div>
                        </br>

                        <!--tercera parte-body-->

                        <!-- <div class="row" align="center">

                        <!--<div class="form-group" > -->
                        <div class="col-md-3 pad_bot">
                            <div id="div_pass_actual" class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                <input id="usuario" name="usuario" readonly value="<?PHP echo $INFO_USUARIO[0]['usuario']; ?>" type="text" class="form-control placeholder_translate" data-placeholder_en="User" data-placeholder_es="Usuario" placeholder="Usuario">
                            </div>
                        </div>
                        <!--</div> -->

                        <!--<div class="form-group">-->
                        <div class="col-md-3 pad_bot">
                            <div id="div_pass_actual" class="input-group">
                                <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
                                <input id="pass_actual" name="pass_actual" type="password" autocomplete="off" class="form-control limpiar placeholder_translate" data-placeholder_en="Current password" data-placeholder_es="Contraseña actual" placeholder="Contrase&ntilde;a actual">
                            </div>
                        </div>
                        <!--</div>-->

                        <!--<div class="form-group"> -->
                        <div class="col-md-3 pad_bot">
                            <div id="div_nuevo_pass" class="input-group">
                                <span class="input-group-addon"><i class="fa fa-unlock fa-fw"></i></span>
                                <input id="nuevo_pass" name="nuevo_pass" type="password" class="form-control igualar_pass limpiar placeholder_translate" data-placeholder_en="New password" data-placeholder_es="Nueva contraseña" placeholder="Nueva contrase&ntilde;a">
                            </div>
                        </div>
                        <!--</div>-->

                        <!--<div class="form-group"> -->
                        <div class="col-md-3 pad_bot">
                            <div id="div_confirm_pass" class="input-group">
                                <span class="input-group-addon"><i class="fa fa-lock fa-fw"></i></span>
                                <input id="confirm_pass" name="confirm_pass" type="password" class="form-control igualar_pass limpiar placeholder_translate" data-placeholder_en="Confirm password" data-placeholder_es="Confirmar contraseña" style="align:middle; padding-bottom:200px" placeholder="Confirmar contrase&ntilde;a">
                            </div>
                        </div>
                        <!--</div> -->

                        <!--</div>-->

                    </center>
                </div>

                <!--footer -->
                <div class="panel-footer" align="right">
                    <button id="guardar" name="guardar" class="btn btn-sm btn-primary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" type="button" data-loading-text="Guardando...">Guardar</button>
                </div>

            </div> <!-- panel default-->

        </div><!--     Well -->
    </div><!--  Container -->


    <script>
        jQuery.ajaxSetup({
            async: false
        });
        grl_overlay_loading('Cargando información');
        error = false;
        noigual = false;
        pass = $("#nuevo_pass").val();
        passc = $("#confirm_pass").val();

        $(document).ready(function() {
            $("#nuevo_pass").change(function() {
                if ($("#nuevo_pass").val() != "") {
                    $("#div_nuevo_pass").removeClass("has-error").addClass("has-success");
                    error = false;
                } else {
                    $("#div_nuevo_pass").removeClass("has-success").addClass("has-error");
                    error = true;
                }
            });

            $("#confirm_pass").change(function() {
                if ($("#confirm_pass").val() != "") {
                    $("#div_confirm_pass").removeClass("has-error").addClass("has-success");
                    error = false;
                } else {
                    $("#div_confirm_pass").removeClass("has-success").addClass("has-error");
                    error = true;
                }
            });

            function grl_verificar_contrasenia(objeto, objeto) {
                //Valida que los campos sean iguales
                pass = $("#nuevo_pass").val();
                passc = $("#confirm_pass").val();

                // Valida que la contraseña no sea menor a 8 caracteres.
                if (pass.length > 8) {
                    $("#length").removeClass("fa-times").addClass("fa-check");
                    $("#length").css("color", "green");
                    error = false; //=0
                } else {
                    $("#length").removeClass("fa-check").addClass("fa-times");
                    $("#length").css("color", "red");
                    error = true;
                }

                // Valida que exista una letra.
                if (pass.match(/[a-zñ]/)) {
                    $("#letter").removeClass("fa-times").addClass("fa-check");
                    $("#letter").css("color", "green");
                    error = false; //=0
                } else {
                    $("#letter").removeClass("fa-check").addClass("fa-times");
                    $("#letter").css("color", "red");
                    error = true;
                }

                // Valida que exista una letra mayúscula.
                if (pass.match(/[A-ZÑ]/)) {
                    $("#capital").removeClass("fa-times").addClass("fa-check");
                    $("#capital").css("color", "green");
                    error = false;
                } else {
                    $("#capital").removeClass("fa-check").addClass("fa-times");
                    $("#capital").css("color", "red");
                    error = true;
                }

                // Valida un número.
                if (pass.match(/\d/)) {
                    $("#number").removeClass("fa-times").addClass("fa-check");
                    $("#number").css("color", "green");
                    error = false;
                } else {
                    $("#number").removeClass("fa-check").addClass("fa-times");
                    $("#number").css("color", "red");
                    error = true;
                }

                //Valida que los campos sean iguales
                if (passc.length < 8 && !passc.match(/[a-zñ]/) && !passc.match(/[A-ZÑ]/) && !passc.match(/\d/)) {
                    $("#igual").removeClass("fa-check").addClass("fa-times");
                    $("#igual").css("color", "red");
                    noigual = true;

                } else if (passc.length > 8 && passc.match(/[a-zñ]/) && passc.match(/[A-ZÑ]/) && passc.match(/\d/)) {
                    if (pass == passc) {
                        $("#igual").removeClass("fa-times").addClass("fa-check");
                        $("#igual").css("color", "green");
                        noigual = false;
                    } else {
                        $("#igual").removeClass("fa-check").addClass("fa-times");
                        $("#igual").css("color", "red");
                        noigual = true;
                    }
                }

                if (pass.length > 8 && pass.match(/[a-zñ]/) && pass.match(/[A-ZÑ]/) && pass.match(/\d/)) {
                    error = false;
                } else if (pass.length < 8 && !pass.match(/[a-zñ]/) && !pass.match(/[A-ZÑ]/) && !pass.match(/\d/)) {
                    error = true;
                }
            }

            $('#guardar').click(function() {
                var btn = $("#guardar");
                var tipo = '';
                btn.button('loading');
                var usuario = $("#usuario").val();
                var pass_actual = $("#pass_actual").val();
                var nuevo_pass = $("#nuevo_pass").val();
                var confirm_pass = $("#confirm_pass").val();
                var pass = $("#nuevo_pass").val();
                var passc = $("#confirm_pass").val()
                if (pass.length < 8) {
                    $("#length").removeClass("fa-check").addClass("fa-times");
                    $("#length").css("color", "red");
                    error = true;
                }

                if (!pass.match(/[a-zñ]/)) {
                    $("#letter").removeClass("fa-check").addClass("fa-times");
                    $("#letter").css("color", "red");
                    error = true;
                }

                if (!pass.match(/[A-ZÑ]/)) {
                    $("#capital").removeClass("fa-check").addClass("fa-times");
                    $("#capital").css("color", "red");
                    error = true;
                }

                if (!pass.match(/\d/)) {
                    $("#number").removeClass("fa-check").addClass("fa-times");
                    $("#number").css("color", "red");
                    error = true;
                }

                if (pass != passc) {
                    $("#igual").removeClass("fa-check").addClass("fa-times");
                    $("#igual").css("color", "red");
                    noigual = true;
                }

                /* En caso que no hay error, que lo guarde*/
                if (error == false && noigual == false) {
                    $.ajax({
                        type: "POST",
                        url: "mod_admin_usuarios/funciones/usu_cambiar_password_by_user.php",
                        data: {
                            x1: usuario,
                            x2: pass_actual,
                            x3: nuevo_pass
                        },
                        error: function() {
                            alert("Se ha detectado un error");
                        },
                        success: function(data) {
                            var info = data.split("|");
                            if (info[0] == 1) {
                                tipo = "danger";
                                grl_mensaje(info[1], "Intentar de nuevo.", "danger");
                                $("#div_confirm_pass").removeClass("has-success").removeClass("add-error");
                                $("#div_nuevo_pass").removeClass("has-success").removeClass("add-error");
                            } else {
                                grl_mensaje(info[1], "Favor verificar", "success");
                                window.setTimeout(function() {
                                    window.location.href = "../../index.php";
                                }, 5000);
                            }
                            $(".limpiar").val("");
                            $("#div_confirm_pass").removeClass("has-success").removeClass("has-error");
                            $("#div_nuevo_pass").removeClass("has-success").removeClass("has-error");
                            $("#confirm_pass").popover("hide");
                            btn.button("reset");
                        }
                    }); //Ajax
                } else if (noigual == true) {
                    $("#confirm_pass").popover("show");
                    $("#div_confirm_pass").removeClass("has-success").addClass("has-error");
                    $("#div_nuevo_pass").removeClass("has-success").addClass("has-error");
                    setTimeout(function() {
                        $("#confirm_pass").popover("hide");
                    }, 5000);
                    /*si no que despliegue un mensaje, viene de las funciones generales*/
                } else {
                    grl_mensaje("La contraseña no cumple los requisitos", "favor verificar", "danger");
                    $("#nuevo_pass").popover("show");
                    grl_verificar_contrasenia(this, this);
                    $("#div_confirm_pass").removeClass("has-success").addClass("add-error");
                    $("#div_nuevo_pass").removeClass("has-success").addClass("add-error");
                    setTimeout(function() {
                        $("#nuevo_pass").popover("hide");
                    }, 5000);
                }
                btn.button("reset");
            }); //Guardar Click

            /*
             * Crea el popover que ira en la verificación de la contraseña.
             */
            $("#nuevo_pass").popover({
                placement: "top",
                animation: "true",
                title: "<h4 class=\'translate\' data-traducir_english=\'Password requirements\' data-traducir_spanish=\'Requisitos de contraseña\'>Password requirements</h4>",
                content: "<div><i id=\'letter\' class=\'fa fa-check valid\'><strong class=\'translate\' data-traducir_english=\'Minimum one lower case letter\' data-traducir_spanish=\'Mínimo una letra minúscula\'>Minimum one lower case letter</strong></i><i id='\capital\' class=\'fa fa-check\'><strong class=\'translate\' data-traducir_english=\'Minimum one capital letter\' data-traducir_spanish=\'Mínimo una letra mayúscula\'>Mínimo una letra mayúscula</strong></i><i id=\'number\' class=\'fa fa-check \'><strong class=\'translate\' data-traducir_english=\'Minimum one number\' data-traducir_spanish=\'Mínimo un número\'>Minimum one number</strong></i><i id=\'length\' class=\'fa fa-check\ '><strong class=\'translate\' data-traducir_english=\'No less than 8 characters\' data-traducir_spanish=\'No menor de 8 carácteres\'>No less than 8 characters</strong></i></div>",
                container: "body",
                html: "true",
                trigger: "click"
            });

            /*
             * Crea el popover que se visualizará si las contraseñas no son iguales.
             */
            $("#confirm_pass").popover({
                placement: "top",
                animation: "true",
                title: "<h4>Verificar contraseñas</h4>",
                content: "<div><i id=\'igual\' class=\'fa fa-check valid\'><strong class='translate' data-traducir_english='The new password and its verification are not the same.' data-traducir_spanish='La nueva contraseña y su verificación no son iguales.'>La nueva contraseña y su verificación no son iguales.</strong>",
                container: "body",
                html: "true",
                trigger: "click"
            });

            /*
             * Clase verifica que la contraseña y su confirmación sean iguales y que no esten vacios.
             */
            $(".igualar_pass").on("blur", function() {
                var pass = $("#nuevo_pass").val();
                var passc = $("#confirm_pass").val();
                grl_verificar_contrasenia(this, this);

                //Verifica que el campo tenga información y sea igual que su confirmación.
                if (pass.length > 8 && pass.match(/[a-zñ]/) && pass.match(/[A-ZÑ]/) && pass.match(/\d/)) {
                    error = false;
                    $("#div_nuevo_pass").removeClass("has-error").addClass("has-success");
                } else {
                    error = true;
                }

                if (passc.length > 8 && passc.match(/[a-zñ]/) && passc.match(/[A-ZÑ]/) && passc.match(/\d/)) {
                    error = false;
                    $("#div_confirm_pass").removeClass("has-error").addClass("has-success");
                } else {
                    error = true;
                }

                if ($("#confirm_pass").val() != "") {
                    if ($("#nuevo_pass").val() == $("#confirm_pass").val()) {
                        noigual = false;
                        $("#div_confirm_pass").removeClass("has-error").addClass("has-success");
                    } else {
                        $("#div_confirm_pass").removeClass("has-success").addClass("has-error");
                        noigual = true;
                    }
                }

                if ($("#nuevo_pass").val() != "") {
                    if (pass.length > 8 && pass.match(/[a-zñ]/) && pass.match(/[A-ZÑ]/) && pass.match(/\d/)) {
                        $("#div_nuevo_pass").removeClass("has-error").addClass("has-success");
                        error = false;
                    } else {
                        $("#div_nuevo_pass").removeClass("has-success").addClass("has-error");
                        error = true;
                    }
                }
            }).on("keyup", function() {
                var pass = $("#nuevo_pass").val();
                var passc = $("#confirm_pass").val();
                grl_verificar_contrasenia(this, this);

                if ($("#nuevo_pass").val() != "") {
                    if (pass.length > 8 && pass.match(/[a-zñ]/) && pass.match(/[A-ZÑ]/) && pass.match(/\d/)) {
                        $("#div_nuevo_pass").removeClass("has-error").addClass("has-success");
                        error = false;
                    } else {
                        error = true;
                    }
                }

                if ($("#confirm_pass").val() != "") {
                    if (passc.length > 8) {
                        if (passc.match(/[a-zñ]/) && passc.match(/[A-ZÑ]/) && passc.match(/\d/)) {
                            if ($("#confirm_pass").val() == $("#nuevo_pass").val()) {
                                $("#div_confirm_pass").removeClass("has-error").addClass("has-success");
                                error = false;
                            } else {
                                $("#div_nuevo_pass").removeClass("has-success").addClass("has-error");
                                $("#div_confirm_pass").removeClass("has-success").addClass("has-error");
                                error = true;
                            }
                        }
                    }
                }
            }).on("click", function() {
                grl_verificar_contrasenia(this, this);
            })
            $('#modal_loading').modal('hide');
            jQuery.ajaxSetup({
                async: true
            });
        }); // Ready Funtion
    </script>
</body>