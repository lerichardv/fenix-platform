<?PHP
/*
 * Guardar usuario Recomendado.
 * @author      Edwin Olivera
 * @date        2021-11-02
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

$cod_info_empresa			= trim(($_POST['x1']));
$usuario_finca_recomendado  	= trim(utf8_decode($_POST['x2']));
$motivo_recomendado  	= trim(utf8_decode($_POST['x3']));
 

$result = $DB_PLANT->plan_guardar_recomendado($cod_info_empresa,
										$usuario_finca_recomendado,
										$motivo_recomendado,
										$_SESSION['cod_usuario']);
// echo utf8_encode($result[0]['mensaje']);
echo utf8_encode($result);
?>
