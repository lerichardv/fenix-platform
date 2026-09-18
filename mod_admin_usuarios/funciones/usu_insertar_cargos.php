<?PHP
/*
 * Llama a la función que insertará un nuevo cargo.
 * @author 	Linda Zelaya
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
$cod_gerencia = trim(utf8_decode($_POST['x1']));
$cargo       = trim(utf8_decode($_POST['x2']));
$descripcion  = trim(utf8_decode($_POST['x3']));
$user_insert  = $_SESSION['cod_usuario'];
$result = $DB_USUARIO->get_last_cargo_inserted($cod_gerencia);
$cod_cargo = $result[0]['id_cargo']+1;
$result = $DB_USUARIO->send_insertar_cargo($cod_gerencia,
                                          $cod_cargo,
                                          $cargo,
                                          $descripcion,
                                          $user_insert);

$mensaje = ($result == 1) ? '0|A new position has been entered.' : '1|The new charge could not be entered';
echo $mensaje;

?>
