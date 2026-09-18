<?PHP
/*
 *funcion que valida que la contraseña no haya sido utilizada en los ultimos tres meses
 * @author 	Linda Zelaya
 * @date 	2017-03-15
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
$cod_usuario    = $_SESSION['cod_usuario'];
$nuevo_pass     = trim(utf8_decode($_POST['x1']));
$passwords = $DB_USUARIO->get_historial_password($cod_usuario, $nuevo_pass);
if (!empty($passwords)){
  $mensaje = "0|Es igual";
}else{
  $mensaje = "1|No es igual";
}
echo $mensaje;
?>
