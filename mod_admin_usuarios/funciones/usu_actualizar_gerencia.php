<?PHP
/*
 * Llama a la función que actualizará el perfil seleccionado.
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

$cod_gerencia   = trim(utf8_decode($_POST['x1']));
$gerencia       = trim(utf8_decode($_POST['x2']));
$descripcion  = trim(utf8_decode($_POST['x4']));
$activo       = trim(utf8_decode($_POST['x3']));
$user_insert  = trim($_POST['x5']);

$result = $DB_USUARIO->send_actualizar_gerencia($cod_gerencia,
                                             $gerencia,
                                             $descripcion,
                                             $activo,
                                             $user_insert);

$mensaje = ($result == 1) ? '0|The record has been updated.' : '1|Could not update the record.';
echo $mensaje;
?>
