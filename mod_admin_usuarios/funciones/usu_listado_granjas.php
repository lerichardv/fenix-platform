<?PHP
/*
 * Listado de las granjas para ser agregadas en registro de usuarios.
 * @author      Dan Urquía
 * @date        2024-08-29
 
*/
session_start();
if(!isset($_SESSION['cod_usuario'])){
    header('Location: index.php');
}
echo 1;
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_usuario.php");
/*INSTANCIAMIENTOS*/ 
$DB_USUARIO  = new db_usuario();
$GRANJAS = $DB_USUARIO->usu_listado_granjas1();
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($GRANJAS as $GRANJA){
    $data[]=array_map('utf8_encode', $GRANJA);
}
echo json_encode($data);
//var_dump($data);
?>
