<?PHP
/*
 * Pantalla donde se realiza la administración de nombramientos por juzgado
 * @author      Dan Urquía
 * @date        2017-01-19
 */
session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: ../index.php');
	echo "<script>window.location.href = '" . $_SESSION['úrl_inicio'] . "';</script>"; // Usando JS para redireccionar
	// header('Location: ../index.php'); // Método anterior (No funcional)
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_usuario.php");

/*INSTANCIAMIENTOS*/
?>

<script type="text/javascript">
		/*funcion que pone la primera letra en mayuscula*/
		function toTitleCase(str)
		{
		    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
		}
    registro     = 0;
		flag_ins_act = 0; //flag para saber si se actualizara o se insertara registro
		flag_check   = 0; //flag para checkbox
		flag_gerencia = 0; // flag para saber que se escogio gerencia del selectpicker

    $(document).ready(function(){
        jQuery.ajaxSetup({async:false});
        grl_overlay_loading('Cargando información');
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
		usu_constructor_gerencias();

		$('#cod_gerencia').change(function(event) {
				usu_constructor_cargos_x_gerencia($('#cod_gerencia').val());
				$('#nombre_registro').val('');
				$('#iniciales_registro').val('');
				flag_gerencia = 1;
		});

        /* Validación input, textarea requeridos */
        $(".input.requerido, .input.requerido-modal").keyup(function(event) {
          if( $(this).val().trim() != "" ){
              $(this).removeClass('input-has-error campo-vacio campo-vacio-modal');
          } else {
              $(this).addClass('input-has-error');
          }
        });

        	jQuery.ajaxSetup({async: true});

        /* Click en botón */
        $( "#btn_crear" ).click(function() {
          if (flag_ins_act != 1){
              insertar_registro();
          } else {
            actualizar_registro(registro, $("#nombre_registro").val(), flag_check, $("#iniciales_registro").val());
          }
        });
        $('#modal_loading').modal('hide');
        jQuery.ajaxSetup({async:true});

    });

    /*
     * Función que permite insertar un nuevo registro en la base de datos.
     * @param {int} x1
     * @param {var} x2
     * @returns {Nuevo registro}
     */
    function insertar_registro(){
        jQuery.ajaxSetup({async: false});
        var x1 = $( "#cod_gerencia" ).val();
        var x2 = $( "#nombre_registro" ).val();
        var x3 = $( "#iniciales_registro" ).val();
        grl_overlay_loading('Guardando información');
        $.ajax({
                type: 'POST',
                url: 'mod_admin_usuarios/funciones/usu_insertar_cargos.php',
                beforeSend: function () {
                    if (x2 == '' || x3 == '' || flag_gerencia == 0){
                        $(".input.requerido").addClass('input-has-error').removeClass('input-is-ok');
                        grl_mensaje('Información incompleta', ' favor revisar', 'warning');
                        $("#modal_loading").modal("hide");
                        return false;
                    } else {
                        return true;
                    }
                },
                data: {
                    x1: x1,
                    x2: x2,
										x3: x3
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
										$('#cod_gerencia').val(x1).change();
                }
        }); //Ajax
        jQuery.ajaxSetup({async: true});
    }

		function actualizar_registro(x2, x3, x4, x5){//cod_cargo, cargo, activo, descripcion
				jQuery.ajaxSetup({async: false});
        var x1 = $('#cod_gerencia').val();
				//grl_overlay_loading('Actualizando información');
				$.ajax({
								type: 'POST',
								url: 'mod_admin_usuarios/funciones/usu_actualizar_cargo.php',
								data: {
										x1: x1,
										x2: x2,
                    x3: x3,
                    x4: x4,
                    x5: x5
								},
								error: function () {
										grl_mensaje('Error. ', 'Favor intentar nuevamente.', 'danger');
										$("#modal_loading").modal("hide");
								},
								success: function (data) {
										var info = data.split("|");
										var mensaje = info[1];
										info[0] == 1 ? tipo = 'danger' : tipo = 'success';
										grl_mensaje('', info[1], tipo, 6);
										$('#cod_gerencia').val(x1).change();
										//$("#modal_loading").modal("hide");
										//flag_ins_act = 0;
										//grl_obtener_cuerpo_menu(2, 'mod_admin_usuarios/ui/usu_cargos.php');
								}
				}); //Ajax*/
				jQuery.ajaxSetup({async: true});
		}

		$("#btn_ir_al_listado").on('click',function (){
				grl_obtener_cuerpo_menu(1,'mod_admin_usuarios/ui/usu_listado_usuarios.php');
		});
</script>
<style type="text/css">
    /*----------------- CHECKBOX -----------------*/
    .material-switch > .checkbox_cargo {
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
    .material-switch > .checkbox_cargo:checked + label::before {
        background: inherit;
        opacity: 0.5;
    }
    .material-switch > .checkbox_cargo:checked + label::after {
        background: inherit;
        left: 20px;
    }

    /* Listado scroll */
    #listado_items {
        height:300px;
        overflow:auto;
				padding-bottom: 5px;
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
	<title>Cargos</title>
</head>

<body>
    <div id="overlay_loading"></div>
    <div id="message_box"></div>
    <div class="panel panel-default">
        <div class="panel-body" >
            <div class="page-header">
                <h1 class="translate" data-traducir_english="Assign positions to companies" data-traducir_spanish="Asignación de cargos a gerencias">Asignación de cargos a gerencias</small></h1>
            </div>
            <div class="row" style="overflow:auto; padding-bottom:10px ">
                <div class="col-md-3">

									<label class="translate" data-traducir_english="Company Name" data-traducir_spanish="Gerencia">Gerencia</label>
									<div class="form-group show-tick">
										<select class="selectpicker show-menu-arrow requerido" id="cod_gerencia" name="cod_gerencia" data-live-search="true">
										</select>
									</div>

                    <div class="form-group input-group-sm">
                        <label for="nombre_registro" class="translate" data-traducir_english="Job Title" data-traducir_spanish="Nombre del Cargo">Nombre del Cargo</label>
                        <input type="text" class="form-control" placeholder="Nombre del cargo" id="nombre_registro">
                    </div>

                  <label for="iniciales_registro" class="translate" data-traducir_english="Job Description" data-traducir_spanish="Descripción del Cargo">Descripción del Cargo</label>
                    <div class="input-group input-group-sm">
                      <input type="text" class="form-control" placeholder="Descripción del cargo" id="iniciales_registro" style="padding-bottom:10px ">
                        <span class="input-group-btn">
                          <button class="btn btn-primary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" type="button" id="btn_crear">Guardar</button>
                        </span>
                    </div><!-- /input-group -->
                </div>
								<br>
                <div class="col-md-9">
                    <div class="panel panel-primary">
                        <!-- Titulo del panel -->
                        <div class="panel-heading translate" data-traducir_english="List of charges for company" data-traducir_spanish="Listado de cargos por gerencia">Listado de cargos por gerencia</div>

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
