<?PHP
/*
 * Listado de los proveedores.
 * @author      Jairo Bonilla
 * @date        2018-10-25
*/

ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_inventario.php");
/*INSTANCIAMIENTOS*/
$DB_INV = new db_inventario();
$cod_detalle	= trim($_POST['x1']);
$DETALLE       = $DB_INV->inv_cargar_detalle_entrega_producto($cod_detalle);
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
$correlativo = 1;
foreach($DETALLE as $detalle){
	?>
	<tr>
		<td><?php echo $correlativo; ?></td>
		<td><?php echo $detalle['cantidad']; ?></td>
		<td><?php echo $detalle['fecha_entrega']; ?></td>
		<td><?php echo $detalle['observaciones']; ?></td>
	</tr>
	<?php
	$correlativo++;
}
?>
