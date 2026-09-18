<?PHP 
/*
 * Destruye la sesión del usuario logueado en el momento.
 * @author      Jairo Bonilla
 * @date        2016-05-26
 */
ob_start();
session_start();
/*CONEXION CON BASE DE DATOS*/
include_once("../db_classes/db_mysql_conn.php");
include_once("../db_classes/db_usuario.php");
// Expirar el token usado para la sesión
$DB_USUARIO = new db_usuario();
$DB_USUARIO->usu_expirar_tokens_sesion_usuario($_SESSION['cod_usuario']);
session_destroy(); 
header('Location: ../../index.php');
?>
