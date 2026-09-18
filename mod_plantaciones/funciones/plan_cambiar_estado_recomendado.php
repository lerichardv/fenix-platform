<?PHP
/*
 * Actualizando estado de un usuario recomendado.
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

$cod_recomendado  	= trim(($_POST['x1']));
$flag_activo 		= trim(($_POST['x2']));

$result = $DB_PLANT->plan_cambiar_estado_recomendado($cod_recomendado,
													$flag_activo);
echo utf8_encode($result);
?>
