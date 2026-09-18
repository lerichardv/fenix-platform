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

$cod_aplicacion	= trim(($_POST['x1']));


$QUIMICOS 		= $DB_PLANT->plan_listado_quimicos_aplicacion_quimico($cod_aplicacion);

if (count($QUIMICOS)) {
	$correlativo = 1;
	foreach ($QUIMICOS as $quimico)
	{
		?>
		<tr>
			<td><?php echo $correlativo; ?></td>
			<td><?php echo utf8_encode($quimico['tipo_quimico']); ?></td>
            <td><?php echo utf8_encode($quimico['nombre_quimico']); ?></td>
            <td><?php echo utf8_encode($quimico['unidad_medida']); ?></td>
            <td><?php echo utf8_encode($quimico['dosis_minima'].' - '.$quimico['dosis_maxima']); ?></td>
            <td><?php echo utf8_encode($quimico['cantidad_sugerida']); ?></td>
            <td><?php echo utf8_encode($quimico['cantidad_aplicada']); ?></td>
            <td><?php echo utf8_encode($quimico['razon_aplicacion']); ?></td>
            <td><?php echo utf8_encode($quimico['nombre_usuario']); ?></td>
            <td><?php echo utf8_encode($quimico['date_insert']); ?></td>
            <td>
                <a title="Add Quantity Applied - Agregar Cantidad Aplicada" onclick="plan_abrir_div_cantidad_aplicada(<?php echo utf8_encode($quimico['cod_detalle'].','.$quimico['cod_aplicacion'].','.$quimico['cantidad_sugerida'].','.$quimico['cantidad_aplicada']); ?>);" class="btn btn-eliminar btn_administrador btn_supervisor btn-sm btn-success"><i class="fas fa-pen-square"></i></a>
                <a title="Delete - Eliminar" onclick="plan_eliminar_quimico_aplicacion_quimico(<?php echo utf8_encode($quimico['cod_detalle'].','.$quimico['cod_aplicacion']); ?>);" class="smooth-transition btn_administrador btn_supervisor btn-eliminar btn btn-sm btn_delete_file btn-danger"><i class="far fa-trash-alt"></i></a>
            </td>
		</tr>
		<?php
		$correlativo++;		
	}
}
?>
