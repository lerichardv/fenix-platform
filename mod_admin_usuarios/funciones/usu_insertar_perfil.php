<?PHP
/*
 * Llama a la función que insertará un nuevo perfil.
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

$perfil       = trim(utf8_decode($_POST['x1']));
$descripcion  = trim(utf8_decode($_POST['x2']));
$user_insert  = $_SESSION['cod_usuario'];

$result = $DB_USUARIO->send_insertar_perfil($perfil,
                                          $descripcion,
                                          $user_insert);

$mensaje = ($result == 1) ? '0|It has been entered correctly.' : '1|It has been entered incorrectly.';
echo $mensaje;
?>
