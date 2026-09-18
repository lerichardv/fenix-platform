<?PHP
/*
 *funcion que valida que el usuario exista en base de datos y devuelva el correo electronico
 * @author 	Linda Zelaya
 * @date 	2017-03-15
*/

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_usuario.php");
include_once("../../libs/funciones/emailer.php");

/*INSTANCIAMIENTOS*/
$DB_USUARIO     = new db_usuario();
$credenciales     = trim(utf8_decode($_POST['x1']));
$usuarios = $DB_USUARIO->get_info_usuario_by_usuario($credenciales);
$password_string = '!@#$%*&abcdefghijklmnpqrstuwxyzABCDEFGHJKLMNPQRSTUWXYZ23456789';
$nuevo_pass = substr(str_shuffle($password_string), 0, 8);
$EMAILER = new emailer();
$mensaje = "No se hizo prueba";
$mensaje = $usuarios;

try {
  if (!empty($usuarios)) {
    if ($usuarios[0]['pass_pending'] != 3) {
      $old_pass    = $usuarios[0]['pass'];
      $cod_usuario = $usuarios[0]['cod_usuario'];
      $user_user   = $usuarios[0]['usuario'];
      $user_email  = $usuarios[0]['email'];
      $user_nombre = $usuarios[0]['nombre'];
      $result = 1;
      $result = $DB_USUARIO->send_cambiar_pass($cod_usuario, $nuevo_pass);
      if ($result == 1) {
        $DB_USUARIO->insert_historial_password($cod_usuario,$old_pass,$nuevo_pass);
        //enivar correo electronico
        $mensaje =  $EMAILER->enviar_mail_password($user_user, $user_nombre, $user_email, $nuevo_pass, 3, 1);
        // $mensaje =  $EMAILER->enviar_mail_password_NUEVA_VERSION();
      } else {
        $mensaje = '0|The temporary password could not be updated.';
      }
    } else {
      $mensaje = '0|The user is blocked. Please contact your administrator.';
    }
  } else {
    $mensaje = "0|User not found in database.";
  }
} catch (\Throwable $th) {
  //throw $th;
  $mensaje = $th;
}
// Vieja contraseña: 4297f44b13955235245b2497399d7a93
// DATOS QUE SE ENVIAN:
// [{"cod_usuario":71,"usuario":"Edwin_dev","pass":"29b1582614abe27b61ffee5f9c8e1cca","pass_pending":1,"nombre":"Edwin
//   Olivera","email":"edwinolivera33@gmail.com"},{"cod_usuario":73,"usuario":"Edwin","pass":"a5f48e65c5e0740649a8daad6be0878f","pass_pending":0,"nombre":"Edwin
//   Test","email":"edwinolivera33@gmail.com"}]
// echo json_encode($mensaje);
echo ($mensaje);
