<?PHP
/*
 * Llama a la función que actualizará los accesos del usuario seleccionado.
 * @author 	Dan Urquía
 * @date 	2017-01-15
 */

session_start();
if(!isset($_SESSION['cod_usuario'])){
        header('Location: ../../index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_usuario.php");
/*INSTANCIAMIENTOS*/
$DB_USUARIO = new db_usuario();

$cod_tipo_perfil = trim(utf8_decode($_POST['x1']));
$cod_modulo      = trim(utf8_decode($_POST['x2']));
$cod_menu        = trim(utf8_decode($_POST['x3']));
$flag            = trim(utf8_decode($_POST['x4']));
$user_insert     = $_SESSION['cod_usuario'];

if ($flag == 1){ //insert
  $result = $DB_USUARIO->send_insertar_accesos($cod_tipo_perfil,
                                              $cod_modulo,
                                              $cod_menu,
                                              $user_insert);
} else { //delete
  $result = $DB_USUARIO->send_eliminar_accesos($cod_tipo_perfil,
                                             $cod_modulo,
                                             $cod_menu);
}


$mensaje = ($result == 1) ? '0|Se ha actualizado el registro.' : '1|No se pudo actualizar el registro';
echo $mensaje;
?>
