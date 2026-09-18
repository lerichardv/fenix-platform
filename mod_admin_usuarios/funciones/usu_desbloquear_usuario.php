<?PHP

/*

 * Listado de los cargos disponibles para los usuarios.

 * @author

 * @date        2016-06-12

*/



session_start();

if(!isset($_SESSION['cod_usuario'])){

	header('Location: index.php');

}



/*CONEXION CON BASE DE DATOS*/

include_once("../../libs/db_classes/db_mysql_conn.php");

include_once("../../libs/db_classes/db_usuario.php");

/*INSTANCIAMIENTOS*/

$DB_USUARIO = new db_usuario();

$cod_usuario = utf8_decode(trim(($_POST['x1'])));



//actualizamos los intentos en tabla usu_login a activo = 0 y ponemos el pass_pending en 0 para que el usuario vuelva a intentarlo

$result     = $DB_USUARIO->desbloquear_intentos_usuario($cod_usuario);

$result     = $DB_USUARIO->desbloquear_usuario($cod_usuario);

echo $result;

?>

