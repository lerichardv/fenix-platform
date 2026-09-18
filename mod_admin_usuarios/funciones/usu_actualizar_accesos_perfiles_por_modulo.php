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
$cod_modulo      = trim(utf8_decode($_POST['x1']));
$flag            = trim(utf8_decode($_POST['x2']));
$cod_perfil      = trim(utf8_decode($_POST['x3']));
$cod_usuario     = trim(utf8_decode($_POST['x4']));
$user_insert     = $_SESSION['cod_usuario'];
$MENUS = $DB_USUARIO->get_listado_menus_modulo_perfil($cod_usuario, $cod_modulo);
foreach($MENUS as $MENU){
    if ($flag == 1){ //insert
      //primero se elimina por si existe en base de datos y luego se agrega
      $result = $DB_USUARIO->send_eliminar_accesos($cod_perfil,
                                                 $cod_modulo,
                                                 $MENU['cod_menu']);
      $result = $DB_USUARIO->send_insertar_accesos($cod_perfil,
                                                  $cod_modulo,
                                                  $MENU['cod_menu'],
                                                  $user_insert);
    } else { //delete
      $result = $DB_USUARIO->send_eliminar_accesos($cod_perfil,
                                                 $cod_modulo,
                                                 $MENU['cod_menu']);
    }
}
if ($flag == 1){
  $DB_USUARIO->actualizar_modulo_opciones($cod_modulo, $cod_usuario);
}


$mensaje = ($result == 1) ? '0|The record has been updated.' : '1|Could not update the record.';
echo $mensaje;
?>
