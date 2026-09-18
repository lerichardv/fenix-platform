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
include_once("../../libs/db_classes/db_plantaciones.php");
/*INSTANCIAMIENTOS*/
$DB_PLANT = new db_plantaciones();
$cod_info_empresa = $_POST['x1'];
$ZONAS       = $DB_PLANT-> bw_listado_zonas_por_finca($cod_info_empresa);
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($ZONAS as $ZONA){
	$data[]=array_map('utf8_encode', $ZONA);
}
echo json_encode($data);
?>
