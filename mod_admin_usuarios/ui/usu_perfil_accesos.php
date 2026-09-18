<?PHP
/*
 * Pantalla donde se realiza la administración de nombramientos por juzgado
 * @author      Dan Urquía
 * @date        2017-01-19
 */
session_start();
if(!isset($_SESSION['cod_usuario'])){
		echo "<script>window.location.href = '" . $_SESSION['úrl_inicio'] . "';</script>"; // Usando JS para redireccionar
	// header('Location: ../index.php'); // Método anterior (No funcional)
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_usuario.php");

/*INSTANCIAMIENTOS*/
$DB_USUARIO     = new db_usuario();
?>

<script type="text/javascript">
		flag_perfil = 0;
		flag_usuario = 0;
    registro     = 0;
    $(document).ready(function(){
        jQuery.ajaxSetup({async:false});
        grl_overlay_loading('');
		//Habilitación de listboxs
		$('.selectpicker').selectpicker({
			dropupAuto: 'true',
			container: 'body',
			size: '10',
			width: '100%',
			style: 'btn-sm btn-info'
		});

		//Habilita los selects para mobile
		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
				$('.selectpicker').selectpicker('mobile');
		}

		//Constructor de listboxs
		usu_constructor_usuarios_activos();
		usu_constructor_perfiles_activos();
		$('#cod_usuario').change(function(event) {
            grl_overlay_loading('');
			usu_constructor_menus_por_usuario($('#cod_usuario').val());
			flag_usuario = 1;
            $('#modal_loading').modal('hide');
		});
        //$('#cod_usuario').trigger('change');
        /* Validación input, textarea requeridos */
        $(".input.requerido, .input.requerido-modal").keyup(function(event) {
            if( $(this).val().trim() != "" ){
                $(this).removeClass('input-has-error campo-vacio campo-vacio-modal');
            } else {
                $(this).addClass('input-has-error');
            }
        });

        /* Cambiar el perfil al usuario */
        $('#cod_perfil').change(function(event) {
    		if (flag_usuario == 1){
    			actualizar_registro($('#cod_usuario').val(), $('#cod_perfil').val());
    			usu_constructor_menus_por_usuario($('#cod_usuario option:selected').val());
    		}
    		flag_perfil = 1;
        });
        $('#modal_loading').modal('hide');
        jQuery.ajaxSetup({async:true});
    });

		function actualizar_registro(x1, x2){
				jQuery.ajaxSetup({async: false});
				//grl_overlay_loading('Actualizando información');
				$.ajax({
								type: 'POST',
								url: 'mod_admin_usuarios/funciones/usu_actualizar_perfil_usuario.php',
								data: {
										x1: x1,
										x2: x2
								},
								error: function () {
										grl_mensaje('Error', 'favor intentar nuevamente', 'danger');
										$("#modal_loading").modal("hide");
								},
								success: function (data) {
										var info = data.split("|");
										var mensaje = info[1];
										info[0] == 1 ? tipo = 'danger' : tipo = 'success';
										grl_mensaje('', info[1], tipo, 6);
								}
				}); //Ajax*/
				jQuery.ajaxSetup({async: true});
		}
		/*
     * Función que permite insertar un nuevo registro en la base de datos.
     * @param {int} x1 Código del perfil
     * @param {var} x2 Código del módulo
     * @param {int} x3 Código del menú
     * @param {var} x4 flag_activo
     * @returns {Nuevo registro}
     */
    function acciones_registro(x0){
        jQuery.ajaxSetup({async: false});
				var x = x0.split("|");
				if (x.length == 4){
	        var x1 = x[0];
	        var x2 = x[1];
	        var x3 = x[2];
	        var x4 = x[3];
	        var x5 = '<?PHP echo $_SESSION["cod_usuario"]; ?>';
	        grl_overlay_loading('Guardando información');
	        $.ajax({
                type: 'POST',
                url: 'mod_admin_usuarios/funciones/usu_actualizar_accesos_perfiles.php',
                data: {
                    x1: x1,
                    x2: x2,
                    x3: x3,
                    x4: x4,
                    x5: x5
                },
                error: function () {
                    grl_mensaje('Error', 'favor intentar nuevamente', 'danger');
                    $("#modal_loading").modal("hide");
                },
                success: function (data) {
                    var info = data.split("|");
                    var mensaje = info[1];
                    info[0] == 1 ? tipo = 'danger' : tipo = 'success';
                    grl_mensaje('', info[1], tipo, 6);
                    $("#modal_loading").modal("hide");
                    flag_ins_act = 0;
                }
         }); //Ajax
         jQuery.ajaxSetup({async: true});
			  }else{
					var x1 = x[0];//modulo
 				  var x2 = x[2];//flag checked
					codigo_perfil = 0;
					if (flag_perfil==1){
						codigo_perfil = $('#cod_perfil option:selected').val();
					}else{
						codigo_perfil = x[1];//perfil
					}
					var x3 = x[1];
					var x4 = $('#cod_usuario option:selected').val();
 				  grl_overlay_loading('Guardando información');
 				  $.ajax({
 						type: 'POST',
 						url: 'mod_admin_usuarios/funciones/usu_actualizar_accesos_perfiles_por_modulo.php',
 						data: {
 							x1: x1,
 							x2: x2,
 							x3: x3,
							x4: x4
 						},
 							error: function () {
 								grl_mensaje('Error', 'Favor intentar nuevamente', 'danger');
 								$("#modal_loading").modal("hide");
 						 },
 							success: function (data) {
 								var info = data.split("|");
 								var mensaje = info[1];
 								info[0] == 1 ? tipo = 'danger' : tipo = 'success';
 								grl_mensaje('', info[1], tipo, 6);
 								$("#modal_loading").modal("hide");
 								flag_ins_act = 0;
 							}
 				   }); //Ajax
 				   jQuery.ajaxSetup({async: true});
					 usu_constructor_menus_por_usuario($('#cod_usuario option:selected').val());
			  }
    }

		$("#btn_ir_al_listado").on('click',function (){
				grl_obtener_cuerpo_menu(1,'mod_admin_usuarios/ui/usu_listado_usuarios.php');
		});
</script>
<style type="text/css">
    /*----------------- CHECKBOX -----------------*/
    .material-switch > .checkbox_acceso {
        display: none;
    }

    .material-switch > label {
        cursor: pointer;
        height: 0px;
        position: relative;
        width: 40px;
    }

    .material-switch > label::before {
        background: rgb(0, 0, 0);
        box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
        border-radius: 8px;
        content: '';
        height: 16px;
        margin-top: 0px;
        position:absolute;
        opacity: 0.3;
        transition: all 0.4s ease-in-out;
        width: 40px;
    }
    .material-switch > label::after {
        background: rgb(255, 255, 255);
        border-radius: 16px;
        box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
        content: '';
        height: 24px;
        left: -4px;
        margin-top: 0px;
        position: absolute;
        top: -4px;
        transition: all 0.3s ease-in-out;
        width: 24px;
    }
    .material-switch > .checkbox_acceso:checked + label::before {
        background: inherit;
        opacity: 0.5;
    }
    .material-switch > .checkbox_acceso:checked + label::after {
        background: inherit;
        left: 20px;
    }

    /* Listado scroll */
    #listado_items {
        height:300px;
        overflow:auto;
    }

		.registro {
        max-width: 90%;
    }
</style>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<title>Accesos</title>
</head>

<body>
    <div id="overlay_loading"></div>
    <div id="message_box"></div>
    <div class="panel panel-default">
        <div class="panel-body" >
            <div class="page-header">
                <h1 class="translate" data-traducir_english="Assigning profiles and accesses in modules to users" data-traducir_spanish="Asignación de perfiles y accesos en módulos a usuarios">Asignación de perfiles y accesos en módulos a usuarios</h1>
            </div>
            <div class="row" style="overflow:auto; padding-bottom:10px ">
                <div class="col-md-3">

									<label class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</label>
									<div class="form-group show-tick">
										<select class="selectpicker show-menu-arrow requerido" id="cod_usuario" name="cod_usuario" data-live-search="true">
										</select>
									</div>

									<label class="translate" data-traducir_english="Profile" data-traducir_spanish="Perfil">Perfil</label>
									<div class="form-group show-tick">
										<select class="selectpicker show-menu-arrow requerido" id="cod_perfil" name="cod_perfil" data-live-search="true">
										</select>
									</div><!-- /input-group -->
                </div>
                <div class="col-md-9">
                    <div class="panel panel-primary">
                        <!-- Titulo del panel -->
                        <div class="panel-heading translate" data-traducir_english="List of menus by modules" data-traducir_spanish="Listado de menús por módulo">Listado de menús por módulo</div>

                        <!-- listado -->
                        <ul class="list-group" id="listado_items">

                        </ul>
                    </div>
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
					</div>
			</div> <!-- panel-footer -->
</body>
