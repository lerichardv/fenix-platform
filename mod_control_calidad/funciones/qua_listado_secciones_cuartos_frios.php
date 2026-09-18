<?PHP
/*
 * Listado de los estados de plantaciones.
 * @author      Jairo Bonilla
 * @date        2018-10-25
*/

session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_control_calidad.php");
/*INSTANCIAMIENTOS*/
$CONTROL = new db_control_calidad();
$cod_cuarto_frio = $_POST['x1'];
$SECCIONS       = $CONTROL->qua_obtener_listado_secciones_cuarto_frio($cod_cuarto_frio);
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($SECCIONS as $SECCION){
	$data[]=array_map('utf8_encode', $SECCION);
}
echo json_encode($data);
?>
