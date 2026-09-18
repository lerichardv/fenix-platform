<?PHP
/*
 * Listado de empleados registrados.
 * @author      Jairo Bonilla
 * @date        2016-07-14
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
$USUARIOS       = $DB_USUARIO->get_listado_usuarios();
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($USUARIOS as $USUARIO){
	$data[]=array_map('utf8_encode', $USUARIO);
}
echo json_encode($data);
?>
