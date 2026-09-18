<?PHP
/*
 * Listado de todos los tipos de usuarios.
 * @author      Edwin Olivera
 * @date        2024-09-18
 
*/
ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución

session_start();
if(!isset($_SESSION['cod_usuario'])){
    // header('Location: index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_usuario.php");

/*INSTANCIAMIENTOS*/ 
$DB_USUARIO  = new db_usuario();
$TIPOS_DE_USUARIOS = $DB_USUARIO->usu_listado_tipos_de_usuarios();

//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($TIPOS_DE_USUARIOS as $TIPO_USUARIO){
    $data[]=array_map('utf8_encode', $TIPO_USUARIO);
}
echo json_encode($data);
?>
