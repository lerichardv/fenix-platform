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
$cod_pais = $_POST['x1'];
$cod_departamento = $_POST['x2'];
$CUARTOS       = $CONTROL->qua_listado_cuartos_frios_por_pais_departamento($cod_pais, $cod_departamento);
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($CUARTOS as $CUARTO){
	$data[]=array_map('utf8_encode', $CUARTO);
}
echo json_encode($data);
?>
