<?PHP
/*
 * Llama a la función que insertara un registro nuevo para documentos en repositorio
 * @author 	Dan Urquía
 * @date 	  2017-06-24
*/

session_start();
if(!isset($_SESSION['cod_usuario'])){
 header('Location: ../../index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_documentacion.php");
/*INSTANCIAMIENTOS*/
$DB_DOCUMENTOS = new db_documentacion();
//Declaración de variables y llenado
$cod_documento        = utf8_decode(trim(($_POST['x1'])));
$cod_tipo_dispositivo = utf8_decode(trim(($_POST['x2'])));
$user_insert          = $_SESSION['cod_usuario'];
//Envido de daos a la clase que insertara en la BD
$result = $DB_DOCUMENTOS->send_insertar_historial_descarga($cod_documento,
                                                           $cod_tipo_dispositivo,
                                                           $user_insert);
//Se regresa el estado de la consulta
echo $result;
?>
