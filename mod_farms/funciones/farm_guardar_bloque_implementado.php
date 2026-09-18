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
	echo "1|Expired Session|";
	die();
	// header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_farms.php");
/*INSTANCIAMIENTOS*/
$DB_FARM 	= new db_farms();

$cod_bloque	= $_POST['x1'];

$existencia_bloque = $DB_FARM->farm_comprobar_bloque_implementado(
	$cod_bloque
);
if (!isset($existencia_bloque) || empty($existencia_bloque)) {

	$datos_del_bloque = $DB_FARM->farm_buscar_datos_bloque(
		$cod_bloque
	);

	if (isset($datos_del_bloque) && !empty($datos_del_bloque)) {

		$cod_bloque = $datos_del_bloque[0]["cod_bloque"];
		$bloque = $datos_del_bloque[0]["bloque"];
		$cod_farm = $datos_del_bloque[0]["cod_farm"];
		$cod_field = $datos_del_bloque[0]["cod_field"];
		$acres_originales = $datos_del_bloque[0]["acres_originales"];

		$result = $DB_FARM->farm_crear_campo_implementado(
			$cod_bloque,
			$cod_field,
			$cod_farm,
			1,
			0,
			0,
			$acres_originales,
			0,
			$acres_originales,
			0,
			$_SESSION['cod_usuario'] ?? 1
		);
	}
} else {
	echo "0|Ya existia|".$existencia_bloque[0]["cod_bloque_implementado"];
	die();
}

echo $result;
