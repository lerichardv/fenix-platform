<?PHP
/*
 * Pantalla donde se realiza la administración de los perfiles del sistema.
 * @author      Dan Urquía
 * @date        2017-01-14
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
$DB_GERENCIAS  = $DB_USUARIO->get_listado_gerencias();
?>

<script type="text/javascript">
    $("#modal_loading").modal("hide");
	/*funcion que pone la primera letra en mayuscula*/
	function toTitleCase(str)
	{
			return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
	}
    flag_check   = 0; //flag para checkbox
    flag_ins_act = 0; //flag para saber si se actualizara o se insertara registro
    registro     = 0; //código del donante
    $(document).ready(function(){
        jQuery.ajaxSetup({async:false});
        grl_overlay_loading('');
        /* Validación input, textarea requeridos */
        $(".input.requerido, .input.requerido-modal").keyup(function(event) {
            if( $(this).val().trim() != "" ){
                $(this).removeClass('input-has-error campo-vacio campo-vacio-modal');
            } else {
                $(this).addClass('input-has-error');
            }
        });

        /* Checkboxs */
        $( "input[type=checkbox]" ).on( "click", function() {
            flag_check   = 0;
            flag_ins_act = 0;
            if($(this).is(':checked')) flag_check = 1; else flag_check = 0;
            actualizar_registro($(this).attr('id'), $(this).val(), flag_check, $(this).attr('initials'));
        });

        /* Click on listgroup */
        $( ".registro" ).on( "click", function() {
            flag_check   = 0;
            flag_ins_act = 1;
            if($( this ).parent().find("input[type=checkbox]").is(':checked')) flag_check = 1; else flag_check = 0;
            registro = $( this ).parent().find("input[type=checkbox]").attr('id');
            $("#nombre_registro").val($( this ).parent().find("input[type=checkbox]").val());
            $("#iniciales_registro").val($( this ).parent().find("input[type=checkbox]").attr( 'initials' ));
        });

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
    function insertar_registro(x1, x2){
        jQuery.ajaxSetup({async: false});
        var x1 = $( "#nombre_registro" ).val();
        var x2 = $( "#iniciales_registro" ).val();
        grl_overlay_loading('');
        $.ajax({
                type: 'POST',
                url: 'mod_admin_usuarios/funciones/usu_insertar_gerencia.php',
                beforeSend: function () {
                    if (x1 == '' || x2 == ''){
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
                    $("#modal_loading").modal("hide");
                    flag_ins_act = 0;
                    $('#modal_loading').on('hidden.bs.modal', function () {
                        grl_obtener_cuerpo_menu(2, 'mod_admin_usuarios/ui/usu_gerencias.php');
                    });
                }
        }); //Ajax
        jQuery.ajaxSetup({async: true});
    }

    /*
     * Función que permite actualizar un donante en la base de datos.
     * @param {int} x1
     * @param {var} x2
     * @param {int} x3
     * @returns {Registro actualizado}
     */
    function actualizar_registro(x1, x2, x3, x4){
        jQuery.ajaxSetup({async: false});
        grl_overlay_loading('');
        var x5 = '<?PHP echo $_SESSION["cod_usuario"]; ?>';

        $.ajax({
                type: 'POST',
                url: 'mod_admin_usuarios/funciones/usu_actualizar_gerencia.php',
                beforeSend: function () {
                    if (flag_ins_act != 0){
                        if ($( "#nombre_registro" ).val() == ''){
                            $(".input.requerido").addClass('input-has-error').removeClass('input-is-ok');
                            grl_mensaje('Información incompleta', ' favor revisar', 'warning');
                            $("#modal_loading").modal("hide");
                            return false;
                        } else {
                            return true;
                        }
                    } else {
                        return true;
                    }
                },
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
                    $( "#nombre_registro" ).val('');
                    $("#modal_loading").modal("hide");
                    if (flag_ins_act != 0){
                        $('#modal_loading').on('hidden.bs.modal', function () {
                            grl_obtener_cuerpo_menu(2, 'mod_admin_usuarios/ui/usu_gerencias.php');
                        });
                    }
                }
        }); //Ajax
        jQuery.ajaxSetup({async: true});
    }

		$("#btn_ir_al_listado").on('click',function (){
				grl_obtener_cuerpo_menu(1,'mod_admin_usuarios/ui/usu_listado_usuarios.php');
		});
</script>
<style type="text/css">
    /*----------------- CHECKBOX -----------------*/
    .material-switch > input[type="checkbox"] {
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
    .material-switch > input[type="checkbox"]:checked + label::before {
        background: inherit;
        opacity: 0.5;
    }
    .material-switch > input[type="checkbox"]:checked + label::after {
        background: inherit;
        left: 20px;
    }

    /* Listado scroll */
    #listado_items {
        height:300px;
        overflow:auto;
    }
</style>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<title>Gerencias</title>
</head>

<body>
    <div id="overlay_loading"></div>
    <div id="message_box"></div>
    <div class="panel panel-default">
        <div class="panel-body" >
            <div class="page-header">
                <h1 class="translate" data-traducir_english="Companies Administration" data-traducir_spanish="Administración de Gerencias">Administración de Gerencias</h1>
            </div>
            <div class="row">
                <div class="col-md-3">
									<div class="row" style="overflow:auto; padding-bottom:10px ">
											<div class="col-md-12">
													<div class="form-group input-group-sm">
			                        <label for="nombre_registro" class="translate" data-traducir_english="Company Name" data-traducir_spanish="Nombre de la Gerencia">Nombre de la Gerencia</label>
			                        <input type="text" class="form-control" placeholder="Nombre del Gerencia" id="nombre_registro">
			                    </div>
											</div>
											<div class="col-md-12">
												<label for="iniciales_registro" class="translate" data-traducir_english="Description of Company" data-traducir_spanish="Descripción de Gerencia">Descripción de Gerencia</label>
													<div class="input-group input-group-sm">
											      <input type="text" class="form-control" placeholder="Descripción del Gerencia" id="iniciales_registro">
												      <span class="input-group-btn">
												        <button class="btn btn-primary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" type="button" id="btn_crear">Guardar</button>
												      </span>
											    </div><!-- /input-group -->
											</div>
									</div>
                </div>
                <div class="col-md-9">
                    <div class="panel panel-primary">
                        <!-- Titulo del panel -->
                        <div class="panel-heading translate" data-traducir_english="Companies List" data-traducir_spanish="Listado de Gerencias">Listado de Gerencias Registradas</div>

                        <!-- listado -->
                        <ul class="list-group" id="listado_items">
                            <?PHP
                                $cuerpo_lista = '';
                                if(count($DB_GERENCIAS) > 0){
                                    $check = 'checked';
                                    foreach($DB_GERENCIAS as $INFO){
                                        $check = ($INFO['activo'] == 1) ? 'checked' : '';
                                        $cuerpo_lista .= '
                                            <li class="list-group-item" style="padding-bottom:30px">
                                                <label class="registro">' . utf8_encode($INFO['gerencia']) . ' <small>'.utf8_encode($INFO['descripcion']).'</small></label>
                                                <div class="material-switch pull-right">
                                                    <input id="'.utf8_encode($INFO['cod_gerencia']).'" name="'.utf8_encode($INFO['cod_gerencia']).'" value="'.utf8_encode($INFO['gerencia']).'" initials="'.utf8_encode($INFO['descripcion']).'" type="checkbox" ' . $check . '/>
                                                    <label for="'.utf8_encode($INFO['cod_gerencia']).'" class="label-primary"></label>
                                                </div>
                                            </li>';
                                    }
                                } else { //Si no hay registros informará al usuario
                                    $cuerpo_lista .= '<li class="list-group-item">
                                                        <label>No records</label>
                                                      </li>';
                                }
                                echo $cuerpo_lista;
                            ?>
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
