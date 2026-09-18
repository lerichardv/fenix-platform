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
include_once("../../libs/db_classes/db_configuracion.php");
/*INSTANCIAMIENTOS*/
$DB_CONG 			= new db_configuracion();
$cod_info_empresa 	= $_POST['x1'];
$cod_location 		= $_POST['x2'];
$cod_type_test 		= $_POST['x3'];
$cod_source_phase	= $_POST['x4'];
$cod_sample 		= $_POST['x5'];
$sample_date 		= $_POST['x6'];

$sample_date = DateTime::createFromFormat("m-d-Y" , $sample_date);

$sample_date = $sample_date->format('Y-m-d');
$REGISTROS	 		= $DB_CONG->conf_obtener_listado_detallado_environmental_test($cod_info_empresa, $sample_date, $cod_location, $cod_type_test, $cod_source_phase, $cod_sample);
if (count($REGISTROS)) {
	$correlativo = 1;
	?>
	<table class="table table-responsive table-condensed table-striped" id="dev-table">
		<thead>
			<tr class="active info">
				<th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
				<th width="15%" class="translate" data-traducir_english="Country" data-traducir_spanish="Pais">Pais</th>
				<th width="15%" class="translate" data-traducir_english="State" data-traducir_spanish="Estado">Estado</th>
				<th width="15%" class="translate" data-traducir_english="County" data-traducir_spanish="Condado">Condado</th>
				<th width="15%" class="translate" data-traducir_english="Sample Date" data-traducir_spanish="Fecha Muestra">Fecha Muestra</th>
				<th width="15%" class="translate" data-traducir_english="Sample Time" data-traducir_spanish="Hora Muestra">Hora Muestra</th>
				<th width="15%" class="translate" data-traducir_english="Result" data-traducir_spanish="Resultado">Resultado</th>
				<th width="20%" class="translate" data-traducir_english="Attachments" data-traducir_spanish="Adjuntos">Adjuntos</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($REGISTROS as $registro)
			{
				$ADJUNTOS = $DB_CONG->conf_obtener_listado_adjuntos_environmental_test($registro['cod_test']);
				?>
				<tr>
					<td><?php echo $correlativo; ?></td>
					<td><?php echo $registro['pais']; ?></td>
					<td><?php echo $registro['departamento']; ?></td>
					<td><?php echo $registro['municipio']; ?></td>
					<td><?php echo $registro['sample_date']; ?></td>
					<td><?php echo $registro['sample_time']; ?></td>
					<td><?php echo $registro['result']; ?></td>
					<td>
						<?php
						foreach ($ADJUNTOS as $adjunto) {
							?>
							<a class="btn btn-sm btn-block btn-info" href="../../mod_configuracion/adjuntos/<?php echo $adjunto['nombre_adjunto']; ?>" target="_blank"><?php echo $adjunto['nombre_adjunto']; ?></a>
							<?php
						}
						?>
					</td>
				</tr>
				<?php
				$correlativo++;
			}
			?>
		</tbody>
	</table>
	<?php
}
?>