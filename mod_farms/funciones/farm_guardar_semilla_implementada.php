<?PHP
/*
 * Crea o actualiza un registro de un campo.
 * @author      Edwin Olivera
 * @date        2023-02-05
*/

ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
session_start();
if (!isset($_SESSION['cod_usuario'])) {
	// header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_farms.php");
/*INSTANCIAMIENTOS*/
$DB_FARM 	= new db_farms();

$cod_bloque					= $_POST['x0'];
$cod_bloque_implementado	= $_POST['x1'];
$cod_inventario				= $_POST['x2'];
$cod_plantacion				= $_POST['x3'];
$cod_trasplante				= $_POST['x4'];
$acres_usados				= $_POST['x5'];
$porcentaje_acre_usado		= $_POST['x6'];

$existencia_semilla_asociada = $DB_FARM->farm_comprobar_semilla_asocida_a_bloque_implementado(
	$cod_bloque,
	$cod_inventario
);
$listadoTemporada = $DB_FARM->farm_listado_temporadas_activas();
// echo json_encode($listadoTemporada);
// die();
$cod_temporada = $listadoTemporada[0]["cod_temporada"];
if (isset($existencia_semilla_asociada) && !empty($existencia_semilla_asociada)) {

	$resultNuevo = $DB_FARM->farm_crear_semilla_asociada_bloque_implementado(
		$cod_bloque_implementado,
		$cod_inventario,
		$cod_plantacion,
		$cod_trasplante,
		$acres_usados,
		$porcentaje_acre_usado,
		$existencia_semilla_asociada[0]["cod_unificacion"],
		$cod_temporada,
		$_SESSION['cod_usuario'] ?? 1
	);
	$DB_FARM->farm_activar_unificacion_semilla_bloque($cod_bloque, $cod_inventario);
	echo $resultNuevo;
	die();
} else {
	$nuevo_cod_unficacion = $DB_FARM->farm_registrar_unificacion_semilla_bloque(
		$cod_bloque,
		$cod_inventario
	);
	$result = $DB_FARM->farm_crear_semilla_asociada_bloque_implementado(
		$cod_bloque_implementado,
		$cod_inventario,
		$cod_plantacion,
		$cod_trasplante,
		$acres_usados,
		$porcentaje_acre_usado,
		$nuevo_cod_unficacion,
		$cod_temporada,
		$_SESSION['cod_usuario'] ?? 1
	);
	echo $result;

	// echo "segundo";
	// die();
	// echo "No existia el registro de unificacion, result: " . $result;
	// die();
}
