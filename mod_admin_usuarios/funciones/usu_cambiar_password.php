<?PHP
/*
 * Funcion que actualiza password
 * @author 	Linda Zelaya
 * @date 	2016-07-23
*/

session_start();
if(!isset($_SESSION['cod_usuario'])){
        header('Location: ../../index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_usuario.php");
/*INSTANCIAMIENTOS*/
$DB_USUARIO     = new db_usuario();
$nuevo_pass    = trim(utf8_decode($_POST['x1']));
$cod_usuario = $_SESSION['cod_usuario'];
$INFO = $DB_USUARIO->get_info_usuario_by_cod_usuario($cod_usuario);
$old_pass = $INFO[0]['pass'];
$result = $DB_USUARIO->send_cambiar_pass_by_cod_usuario($nuevo_pass,$cod_usuario);
if ($result == 1) {
  $mensaje='0|The password has been updated';
  $result = $DB_USUARIO->insert_historial_password($cod_usuario,$old_pass,$nuevo_pass);
}else{
  $mensaje='1|The password could not be updated';
}
echo $mensaje;
?>
