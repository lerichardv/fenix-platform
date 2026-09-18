<?PHP
/*
 * Listado de los empleados activos en la base de datos.
 * @author      Dan Urquía
 * @date        2016-07-24
*/

session_start();
if(!isset($_SESSION['cod_usuario'])){
    header('Location: index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_usuario.php");
/*INSTANCIAMIENTOS*/
$DB_USUARIO  = new db_usuario();
$cod_gerencia   = trim(utf8_decode($_POST['x1']));

$USUARIOS = $DB_USUARIO->usu_listado_usuarios_por_gerencia($cod_gerencia);
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($USUARIOS as $USUARIO){
    $data[]=array_map('utf8_encode', $USUARIO);
}
echo json_encode($data);
?>
