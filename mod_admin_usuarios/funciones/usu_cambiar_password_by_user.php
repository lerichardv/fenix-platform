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
$nuevo_pass    = trim(utf8_decode($_POST['x3']));
$usuario = trim($_POST['x1']);
$pass    = trim($_POST['x2']);
//traemos el usuario nuevamente para confirmar que la contraseña actual ingresada es correcta
$flag    = $DB_USUARIO->get_login_passPending($usuario,$pass);
if (isset($flag[0]['cod_usuario'])){
  $cod_usuario = $_SESSION['cod_usuario'];
  $passwords = $DB_USUARIO->get_historial_password($cod_usuario, $nuevo_pass);
  if (empty($passwords)){
    $result = $DB_USUARIO->send_cambiar_pass_by_cod_usuario($nuevo_pass,$cod_usuario);
    if ($result == 1) {
      $mensaje='0|The password has been updated';
      $result = $DB_USUARIO->insert_historial_password($cod_usuario,$old_pass,$nuevo_pass);
    }else{
      $mensaje='1|The password could not be updated';
    }
  }else{
    $mensaje = '1|Use a password that you have not used in the last 3 months.';
  }
}else{
  $mensaje='1|The current password entered is incorrect.';
}
echo $mensaje;
?>
