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

$codigo_detalle  	= trim(($_POST['x1']));
$estados = [ 'Waiting to save',
            'Pre-Planted',
            'Planted',
            'Trimmed',
            'Growing',
            'Ready to harvest',
            'Harvested',
            'QA',
            'Packaged product',
            'Removed',
            'Finished'];
$fertilizantes = [ '',
            'ON',
            'OFF',
            'Only Water - Sólo Agua'];
$etapas = [ '',
            'Stubble',
            'Leaf Up Stubbles',
            'High Cress'];
$loose_bunches = [ 'Bunch',
            'Loose',
            'Unused',
            'Pounds'];
$conventional_organic = [ 'Organic',
            'Conventional'];
$question = [ 'No',
            'Yes',
            'NA'];

$ESTADOS 		= $DB_PLANT->plan_listado_cambios_estados_bloques($codigo_detalle);
$CALIBRACIONES  = $DB_PLANT->plan_listado_calibracion_bloques($codigo_detalle);
$APLICACIONES   = $DB_PLANT->plan_listado_aplicaciones_quimicos_bloques($codigo_detalle);
$EXPLORACIONES  = $DB_PLANT->plan_listado_exploraciones_bloques($codigo_detalle);
$LIMPIEZAS      = $DB_PLANT->plan_listado_formularios_limpieza_bloques($codigo_detalle);
$WORKSHEETS     = $DB_PLANT->plan_listado_formularios_harvesting_worksheet_bloques($codigo_detalle);
$CHECKLISTS     = $DB_PLANT->plan_listado_formularios_harvesting_checklist_bloques($codigo_detalle);
$TRASPLANTES    = $DB_PLANT->plan_listado_trasplantes_bloques($codigo_detalle);

if (count($ESTADOS)) {
	?>
    <div class="responsive_table_container">
        <h3 class="translate" data-traducir_english="Stage changes" data-traducir_spanish="Cambios de estado">Cambios de estado</h3>
    	<table class="table display row-border table-responsive" id="table_historial_estados">
            <thead>
                 <tr class="active info">
                    <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
                    <th width="15%" class="translate" data-traducir_english="Stage" data-traducir_spanish="Estado">Estado</th>
                    <th width="20%" class="translate" data-traducir_english="Reason" data-traducir_spanish="Motivo">Motivo</th>
                    <th width="20%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>
                    <th width="20%" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</th>
                 </tr>
            </thead>
            <tbody>
    			<?php
    			$correlativo = 1;
    			foreach ($ESTADOS as $estado)
    			{
    				?>
    				<tr>
    					<td><?php echo $correlativo; ?></td>
    					<td><span class="badge estado<?php echo utf8_encode($estado['cod_estado_plantacion']); ?>" id="badge_estado2"><?php echo utf8_encode($estados[$estado['cod_estado_plantacion']]); ?></span><span class="badge estado<?php echo utf8_encode($estado['cod_estado_plantacion']); ?>" id="badge_estado"><?php echo utf8_encode($estado['estado_plantacion']); ?></span></td>
                        <td><?php echo utf8_encode($estado['motivo_estado_plantacion']); ?></td>
                        <td><?php echo utf8_encode($estado['usuario_actualiza']); ?></td>
                        <td><?php echo utf8_encode($estado['date_update']); ?></td>
    				</tr>
    				<?php
    				$correlativo++;		
    			}
    			?>
    		</tbody>
    	</table>
    </div>
<?php	
}
if (count($APLICACIONES)) {
    ?>
    <div class="responsive_table_container">
        <h3 class="translate" data-traducir_english="Chemycal Applies" data-traducir_spanish="Aplicación químicos">Aplicación químicos</h3>
        <table class="table display row-border table-responsive" id="table_historial_calibraciones">
            <thead>
                 <tr class="active info">
                    <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
                    <th width="15%" class="translate" data-traducir_english="Blocks" data-traducir_spanish="Bloques">Bloques</th>
                    <th width="15%" class="translate" data-traducir_english="Chemicals" data-traducir_spanish="Químicos">Químicos</th>
                    <th width="10%" class="translate" data-traducir_english="Supervisor" data-traducir_spanish="Supervisor">Supervisor</th>
                    <th width="10%" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</th>
                    <th width="15%" class="translate" data-traducir_english="Machinery" data-traducir_spanish="Maquinaria">Maquinaria</th>
                    <th width="20%" class="translate" data-traducir_english="Description" data-traducir_spanish="Descripción">Descripción</th>
                    <th width="10%" class="translate" data-traducir_english="Entry" data-traducir_spanish="Ingreso">Ingreso</th>
                 </tr>
            </thead>
            <tbody>
                <?php
                $correlativo = 1;
                foreach ($APLICACIONES as $aplicacion)
                {
                    ?>
                    <tr>
                        <td><?php echo $correlativo; ?></td>
                        <td><?php echo utf8_encode($aplicacion['bloques']); ?></td>
                        <td><?php echo utf8_encode($aplicacion['quimicos']); ?></td>
                        <td><?php echo utf8_encode($aplicacion['usuario_supervisor']); ?></td>
                        <td><?php echo utf8_encode($aplicacion['fecha_aplicacion_supervisor']); ?></td>
                        <td><?php echo utf8_encode($aplicacion['nombre_maquinaria']); ?></td>
                        <td><?php echo utf8_encode($aplicacion['descripcion_aplicar_quimico']); ?></td>
                        <td><?php echo utf8_encode($aplicacion['date_insert']); ?></td>
                    </tr>
                    <?php
                    $correlativo++;     
                }
                ?>
            </tbody>
        </table>
    </div>
<?php   
}
if (count($EXPLORACIONES)) {
    ?>
    <div class="responsive_table_container">
        <h3 class="translate" data-traducir_english="Explorations" data-traducir_spanish="Exploraciones">Exploraciones</h3>
        <table class="table display row-border table-responsive" id="table_historial_exploraciones">
            <thead>
                 <tr class="active info">
                    <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
                    <th width="10%" class="translate" data-traducir_english="Growth stage" data-traducir_spanish="Etapa crecimiento">Etapa crecimiento</th>
                    <th width="15%" class="translate" data-traducir_english="Insects" data-traducir_spanish="Insectos">Insectos</th>
                    <th width="15%" class="translate" data-traducir_english="Diseases" data-traducir_spanish="Enfermedades">Enfermedades</th>
                    <th width="15%" class="translate" data-traducir_english="Weeds" data-traducir_spanish="Malas hierbas">Malas hierbas</th>
                    <th width="15%" class="translate" data-traducir_english="Others" data-traducir_spanish="Otros">Otros</th>
                    <th width="20%" class="translate" data-traducir_english="Observation" data-traducir_spanish="Observación">Observación</th>
                    <th width="15%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>
                    <th width="10%" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</th>
                    <th width="10%" class="translate" data-traducir_english="Entry" data-traducir_spanish="Ingreso">Ingreso</th>
                    <th width="5%" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Acciones</th>
                 </tr>
            </thead>
            <tbody>
                <?php
                $correlativo = 1;
                foreach ($EXPLORACIONES as $exploracion)
                {
                    ?>
                    <tr>
                        <td><?php echo $correlativo; ?></td>
                        <td><?php echo utf8_encode($etapas[$exploracion['etapa_crecimiento']]); ?></td>
                        <td>
                            <strong>Worms - Gusanos:</strong></strong> <?php echo utf8_encode($exploracion['gusanos']); ?><br>
                            <strong>Eggs - Huevos:</strong> <?php echo utf8_encode($exploracion['huevos']); ?><br>
                            <strong>Leaf Hoppers - Saltahojas:</strong> <?php echo utf8_encode($exploracion['saltahojas']); ?><br>
                            <strong>Aphids - Afidos:</strong> <?php echo utf8_encode($exploracion['afidos']); ?><br>
                            <strong>Stink Bugs - Chinches:</strong> <?php echo utf8_encode($exploracion['chinches']); ?><br>
                            <strong>Gnats - Moscos:</strong> <?php echo utf8_encode($exploracion['moscos']); ?><br>
                            <strong>Flea Beetles - Escarabajos:</strong> <?php echo utf8_encode($exploracion['escarabajos']); ?><br>
                            <strong>Cyclaman Mites - Acaros:</strong> <?php echo utf8_encode($exploracion['acaros']); ?><br>
                        </td>
                        <td>
                            <strong>Cercospora Leaf Spot:</strong> <?php echo utf8_encode($exploracion['cercospora_leaf_spot']); ?><br>
                            <strong>Pythium/Damp Off:</strong> <?php echo utf8_encode($exploracion['pythium']); ?><br>
                            <strong>Rhizoctonia Aerial Blight:</strong> <?php echo utf8_encode($exploracion['rhizoctonia']); ?><br>
                            <strong>Bacteria:</strong> <?php echo utf8_encode($exploracion['bacteria']); ?><br>
                            <strong>Sclerotinia:</strong> <?php echo utf8_encode($exploracion['sclerotinia']); ?><br>
                            <strong>Alternaria Specks:</strong> <?php echo utf8_encode($exploracion['alternaria_specks']); ?><br>
                            <strong>Mildew:</strong> <?php echo utf8_encode($exploracion['mildew']); ?><br>
                            <strong>Virus:</strong> <?php echo utf8_encode($exploracion['virus']); ?><br>
                        </td>
                        <td>
                            <strong>Dollarweed:</strong> <?php echo utf8_encode($exploracion['dolar']); ?><br>
                            <strong>Frogs Bit:</strong> <?php echo utf8_encode($exploracion['frogs_bit']); ?><br>
                            <strong>Mud Plantain:</strong> <?php echo utf8_encode($exploracion['plantas_lodo']); ?><br>
                            <strong>Tube Weed:</strong> <?php echo utf8_encode($exploracion['tripa_pollo']); ?><br>
                            <strong>Grass:</strong> <?php echo utf8_encode($exploracion['zacate']); ?><br>
                        </td>
                        <td>                        
                            <strong>Damaged Leaves:</strong> <?php echo utf8_encode($exploracion['hojas_danadas']); ?><br>
                            <strong>Purple Stem:</strong> <?php echo utf8_encode($exploracion['tallos_purpuras']); ?><br>
                            <strong>Watercress rooted:</strong> <?php echo utf8_encode($exploracion['berro_enraizado']); ?><br>
                        </td>
                        <td><?php echo utf8_encode($exploracion['observacion']); ?></td>
                        <td><?php echo utf8_encode($exploracion['nombre_usuario']); ?></td>
                        <td><?php echo utf8_encode($exploracion['fecha_exploracion']); ?></td>
                        <td><?php echo utf8_encode($exploracion['date_insert']); ?></td>
                        <td><button onclick="flag_exploracion = 0;cod_exploracion = <?php echo utf8_encode($exploracion['cod_exploracion']); ?>;plan_cargar_observaciones_exploracion(flag_exploracion,cod_exploracion, 'div_observaciones_exploracion');plan_cargar_adjuntos_exploracion(flag_exploracion,cod_exploracion, 'div_adjuntos_exploracion');$('#modal_observaciones_exploracion').modal('show');" class="btn btn-sm btn-primary" type="button"><i class="fa fa-list"></i></button></td>
                    </tr>
                    <?php
                    $correlativo++;     
                }
                ?>
            </tbody>
        </table>
    </div>
<?php   
}
if (count($LIMPIEZAS)) {
    ?>
    <div class="responsive_table_container">
        <h3 class="translate" data-traducir_english="Cleaning and sanitizing of harvesting equipment" data-traducir_spanish="Limpieza y saneamiento de equipo de cosecha">Limpieza y saneamiento de equipo de cosecha</h3>
        <table class="table display row-border table-responsive" id="table_historial_limpiezas">
            <thead>
                 <tr class="active info">
                    <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
                    <th width="8%" class="translate" data-traducir_english="Date" data-traducir_spanish="Date">Date</th>
                    <th width="8%" class="translate" data-traducir_english="Time" data-traducir_spanish="Time">Time</th>
                    <th width="8%" class="translate" data-traducir_english="Equipment #" data-traducir_spanish="Equipment #">Equipment #</th>
                    <th width="8%" class="translate" data-traducir_english="Cleaning tools/materials" data-traducir_spanish="Cleaning tools/materials">Cleaning tools/materials</th>
                    <th width="8%" class="translate" data-traducir_english="Cleaning with potable water" data-traducir_spanish="Cleaning with potable water">Cleaning with potable water</th>
                    <th width="8%" class="translate" data-traducir_english="Cleaning with detergent" data-traducir_spanish="Cleaning with detergent">Cleaning with detergent</th>
                    <th width="8%" class="translate" data-traducir_english="Scrubbing" data-traducir_spanish="Scrubbing">Scrubbing</th>
                    <th width="8%" class="translate" data-traducir_english="Rinse with potable water" data-traducir_spanish="Rinse with potable water">Rinse with potable water</th>
                    <th width="8%" class="translate" data-traducir_english="Sanitizing with chlorine" data-traducir_spanish="Sanitizing with chlorine">Sanitizing with chlorine</th>
                    <th width="8%" class="translate" data-traducir_english="Post sanitizing rinse" data-traducir_spanish="Post sanitizing rinse">Post sanitizing rinse</th>
                    <th width="8%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>
                    <th width="8%" class="translate" data-traducir_english="Entry" data-traducir_spanish="Ingreso">Ingreso</th>
                 </tr>
            </thead>
            <tbody>
                <?php
                $correlativo = 1;
                foreach ($LIMPIEZAS as $limpieza)
                {
                    ?>
                    <tr>
                        <td><?php echo $correlativo; ?></td>
                        <td><?php echo utf8_encode($limpieza['fecha_limpieza']); ?></td>
                        <td><?php echo utf8_encode($limpieza['vez_limpieza']); ?></td>
                        <td><?php echo utf8_encode($limpieza['equipo_limpieza']); ?></td>
                        <td><?php echo utf8_encode($limpieza['cleaning_tools'] == 1 ? 'Yes':'No'); ?></td>
                        <td><?php echo utf8_encode($limpieza['cleaning_potable_water'] == 1 ? 'Yes':'No'); ?></td>
                        <td><?php echo utf8_encode($limpieza['cleaning_detergent'] == 1 ? 'Yes':'No'); ?></td>
                        <td><?php echo utf8_encode($limpieza['scrubbing'] == 1 ? 'Yes':'No'); ?></td>
                        <td><?php echo utf8_encode($limpieza['rinse_potable_water'] == 1 ? 'Yes':'No'); ?></td>
                        <td><?php echo utf8_encode($limpieza['sanitizing_chlorine'] == 1 ? 'Yes':'No'); ?></td>
                        <td><?php echo utf8_encode($limpieza['post_sanitizing'] == 1 ? 'Yes':'No'); ?></td>
                        <td><?php echo utf8_encode($limpieza['nombre_usuario']); ?></td>
                        <td><?php echo utf8_encode($limpieza['date_insert']); ?></td>
                    </tr>
                    <?php
                    $correlativo++;     
                }
                ?>
            </tbody>
        </table>
    </div>
<?php   
}
if (count($WORKSHEETS)) {
    ?>
    <div class="responsive_table_container">
        <h3 class="translate" data-traducir_english="Harvesting Worksheet" data-traducir_spanish="Hoja de trabajo de la cosecha">Hoja de trabajo de la cosecha</h3>
        <table class="table display row-border table-responsive" id="table_historial_harvesting_worksheet">
            <thead>
                 <tr class="active info">
                    <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
                    <th width="12%" class="translate" data-traducir_english="Date" data-traducir_spanish="Date">Date</th>
                    <th width="12%" class="translate" data-traducir_english="PHI" data-traducir_spanish="PHI">PHI</th>
                    <th width="12%" class="translate" data-traducir_english="Loose" data-traducir_spanish="Loose">Loose</th>
                    <th width="12%" class="translate" data-traducir_english="Increment (Bunch-Cello)" data-traducir_spanish="Increment (Bunch-Cello)">Increment (Bunch-Cello)</th>
                    <th width="12%" class="translate" data-traducir_english="Area FINISHED" data-traducir_spanish="Area FINISHED">Area FINISHED</th>
                    <th width="12%" class="translate" data-traducir_english="Acres Harvested" data-traducir_spanish="Acres Harvested">Acres Harvested</th>
                    <th width="12%" class="translate" data-traducir_english="PO Request" data-traducir_spanish="PO Request">PO Request</th>
                    <th width="12%" class="translate" data-traducir_english="Quantity Harvested" data-traducir_spanish="Quantity Harvested">Quantity Harvested</th>
                    <th width="12%" class="translate" data-traducir_english="Comments" data-traducir_spanish="Comments">Comments</th>
                    <th width="12%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>
                    <th width="12%" class="translate" data-traducir_english="Entry" data-traducir_spanish="Ingreso">Ingreso</th>
                    <th width="5%" class="translate" data-traducir_english="ACtions" data-traducir_spanish="Acciones">Acciones</th>
                 </tr>
            </thead>
            <tbody>
                <?php
                $correlativo = 1;
                foreach ($WORKSHEETS as $worksheet)
                {
                    ?>
                    <tr>
                        <td><?php echo $correlativo; ?></td>
                        <td><?php echo utf8_encode($worksheet['harvest_date']); ?></td>
                        <td><?php echo utf8_encode($worksheet['phi'] == 1 ? 'Yes':'No'); ?></td>
                        <td><?php echo utf8_encode($worksheet['cellos'] == 1 ? 'Yes':'No'); ?></td>
                        <td><?php echo utf8_encode($loose_bunches[$worksheet['increment_bunch_cello']]); ?></td>
                        <td><?php echo utf8_encode($worksheet['area_finished'] == 1 ? 'Yes':'No'); ?></td>
                        <td><?php echo utf8_encode($worksheet['acres_harvested']); ?></td>
                        <td><?php echo utf8_encode($worksheet['orden_compra']); ?></td>
                        <td><?php echo utf8_encode($worksheet['cantidad_cosechada']); ?></td>
                        <td><?php echo utf8_encode($worksheet['commments_harvesting_worksheet']); ?></td>
                        <td><?php echo utf8_encode($worksheet['nombre_usuario']); ?></td>
                        <td><?php echo utf8_encode($worksheet['date_insert']); ?></td>
                        <td align="center" style="display: flex;">
                            <a title="Edit - Editar" onclick="plan_cargar_modal_worksheet_bloque(<?php echo utf8_encode($worksheet['cod_formulario']); ?>);" data-cod_formulario="<?php echo utf8_encode($worksheet['cod_formulario']); ?>" class="btn btn-eliminar btn_administrador btn_supervisor btn-sm btn-success"><i class="fas fa-edit"></i></a>
                            <a title="Delete - Eliminar" onclick="plan_eliminar_worksheet(<?php echo utf8_encode($worksheet['cod_formulario']); ?>);" data-cod_formulario="<?php echo utf8_encode($worksheet['cod_formulario']); ?>" class="btn btn-eliminar btn_administrador btn_supervisor btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php
                    $correlativo++;     
                }
                ?>
            </tbody>
        </table>
    </div>
<?php   
}
if (count($CHECKLISTS)) {
    ?>
    <div class="responsive_table_container">
        <h3 class="translate" data-traducir_english="Pre-Operational/Harvesting Checklist" data-traducir_spanish="Lista de items para revisar en la cosecha">Lista de items para revisar en la cosecha</h3>
        <table class="table display row-border table-responsive" id="table_historial_harvesting_checklist">
            <thead>
                 <tr class="active info">
                    <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
                    <th width="12%" class="translate" data-traducir_english="Date" data-traducir_spanish="Date">Date</th>
                    <th width="12%" class="translate" data-traducir_english="Loose Bunches" data-traducir_spanish="Loose Bunches">Loose Bunches</th>
                    <th width="12%" class="translate" data-traducir_english="Conventional Organic" data-traducir_spanish="Conventional Organic">Conventional Organic</th>
                    <th width="12%" class="translate" data-traducir_english="Are harvest crews training records up to date?" data-traducir_spanish="Are harvest crews training records up to date?">Are harvest crews training records up to date?</th>
                    <th width="12%" class="translate" data-traducir_english="Are there sick workers?" data-traducir_spanish="Are there sick workers?">Are there sick workers?</th>
                    <th width="12%" class="translate" data-traducir_english="Have sick workers been reassigned to non-food contactjobs? " data-traducir_spanish="Have sick workers been reassigned to non-food contactjobs? ">Have sick workers been reassigned to non-food contactjobs? </th>
                    <th width="12%" class="translate" data-traducir_english="Have harvesters properly covered open wounds, lesions, boils, etc.?" data-traducir_spanish="Have harvesters properly covered open wounds, lesions, boils, etc.?">Have harvesters properly covered open wounds, lesions, boils, etc.?</th>
                    <th width="12%" class="translate" data-traducir_english="Has the crew been instructed on cornpany policies regarding eating, drinking, tobacco use, iewelrv and other safety rules?" data-traducir_spanish="Has the crew been instructed on cornpany policies regarding eating, drinking, tobacco use, iewelrv and other safety rules?">Has the crew been instructed on cornpany policies regarding eating, drinking, tobacco use, iewelrv and other safety rules?</th>
                    <th width="12%" class="translate" data-traducir_english="Is the harvesting crew wearing clean and proper clothing?" data-traducir_spanish="Is the harvesting crew wearing clean and proper clothing?">Is the harvesting crew wearing clean and proper clothing?</th>
                    <th width="12%" class="translate" data-traducir_english="Are harvesting employees wearing hairnets, hats, cap, etc.?" data-traducir_spanish="Are harvesting employees wearing hairnets, hats, cap, etc.?">Are harvesting employees wearing hairnets, hats, cap, etc.?</th>
                    <th width="12%" class="translate" data-traducir_english="Are harvesting hands clean and sanítized?" data-traducir_spanish="Are harvesting hands clean and sanítized?">Are harvesting hands clean and sanítized?</th>
                    <th width="12%" class="translate" data-traducir_english="Are the portable toilets and sanitation station located at 1/.i mile or less from the harvestinz area?" data-traducir_spanish="Are the portable toilets and sanitation station located at 1/.i mile or less from the harvestinz area?">Are the portable toilets and sanitation station located at 1/.i mile or less from the harvestinz area?</th>
                    <th width="12%" class="translate" data-traducir_english="Have all harvesting tools been cleaned and sanitized?" data-traducir_spanish="Have all harvesting tools been cleaned and sanitized?">Have all harvesting tools been cleaned and sanitized?</th>
                    <th width="12%" class="translate" data-traducir_english="Are haul trucks properly cleaned and, if necessary sanitized? " data-traducir_spanish="Are haul trucks properly cleaned and, if necessary sanitized? ">Are haul trucks properly cleaned and, if necessary sanitized? </th>
                    <th width="12%" class="translate" data-traducir_english="Has the truck used for harvesting-hauling produce and themechanical harvesting machine been inspected for oil-diesel/gasoline leaking, loose metal parts, or any other defect posing potential contamination issues?" data-traducir_spanish="Has the truck used for harvesting-hauling produce and themechanical harvesting machine been inspected for oil-diesel/gasoline leaking, loose metal parts, or any other defect posing potential contamination issues?">Has the truck used for harvesting-hauling produce and themechanical harvesting machine been inspected for oil-diesel/gasoline leaking, loose metal parts, or any other defect posing potential contamination issues?</th>
                    <th width="12%" class="translate" data-traducir_english="Has the truck used for harvesting-hauling produce fit for use?" data-traducir_spanish="Has the truck used for harvesting-hauling produce fit for use?">Has the truck used for harvesting-hauling produce fit for use?</th>
                    <th width="12%" class="translate" data-traducir_english="Has the mechanical harvesting rnachinery been cleaned and sanitized?" data-traducir_spanish="Has the mechanical harvesting rnachinery been cleaned and sanitized?">Has the mechanical harvesting rnachinery been cleaned and sanitized?</th>
                    <th width="12%" class="translate" data-traducir_english="Is the Mechanical Harvesting machine fit for use?" data-traducir_spanish="Is the Mechanical Harvesting machine fit for use?">Is the Mechanical Harvesting machine fit for use?</th>
                    <th width="12%" class="translate" data-traducir_english="Has ali the harvesting equipment been inspected for glass breakage?" data-traducir_spanish="Has ali the harvesting equipment been inspected for glass breakage?">Has ali the harvesting equipment been inspected for glass breakage?</th>
                    <th width="12%" class="translate" data-traducir_english="Have ali harvesting totes been cleaned and sanitized?" data-traducir_spanish="Have ali harvesting totes been cleaned and sanitized?">Have ali harvesting totes been cleaned and sanitized?</th>
                    <th width="12%" class="translate" data-traducir_english="Quantity of knives/hooks issued" data-traducir_spanish="Quantity of knives/hooks issued">Quantity of knives/hooks issued</th>
                    <th width="12%" class="translate" data-traducir_english="Quantity of k.nives/hooks returned" data-traducir_spanish="Quantity of k.nives/hooks returned">Quantity of k.nives/hooks returned</th>
                    <th width="12%" class="translate" data-traducir_english="Are there evidence of animal intrusion (fecal material), pest ínfestation, etc. that can pose a risk of contamination on the crop to harvest?" data-traducir_spanish="Are there evidence of animal intrusion (fecal material), pest ínfestation, etc. that can pose a risk of contamination on the crop to harvest?">Are there evidence of animal intrusion (fecal material), pest ínfestation, etc. that can pose a risk of contamination on the crop to harvest?</th>
                    <th width="12%" class="translate" data-traducir_english="Have buffer zones being implemented in the event of a contamination? 30ft (9.1 m) from flooded areas and 5ft (1.5m) from evidence of pest activity. " data-traducir_spanish="Have buffer zones being implemented in the event of a contamination? 30ft (9.1 m) from flooded areas and 5ft (1.5m) from evidence of pest activity. ">Have buffer zones being implemented in the event of a contamination? 30ft (9.1 m) from flooded areas and 5ft (1.5m) from evidence of pest activity. </th>
                    <th width="12%" class="translate" data-traducir_english="Has the crop-block/seetion been cleared for harvest? " data-traducir_spanish="Has the crop-block/seetion been cleared for harvest? ">Has the crop-block/seetion been cleared for harvest? </th>
                    <th width="12%" class="translate" data-traducir_english="Preventive/corrective actions" data-traducir_spanish="Preventive/corrective actions">Preventive/corrective actions</th>
                    <th width="12%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>
                    <th width="12%" class="translate" data-traducir_english="Entry" data-traducir_spanish="Ingreso">Ingreso</th>
                 </tr>
            </thead>
            <tbody>
                <?php
                $correlativo = 1;
                foreach ($CHECKLISTS as $checklist)
                {
                    ?>
                    <tr>
                        <td><?php echo $correlativo; ?></td>
                        <td><?php echo utf8_encode($checklist['fecha_checklist']); ?></td>
                        <td><?php echo utf8_encode($loose_bunches[$checklist['loose_bunches']]); ?></td>
                        <td><?php echo utf8_encode($conventional_organic[$checklist['conventional_organic']]); ?></td>
                        <td><?php echo utf8_encode($question[$checklist['question1']]); ?></td>
                        <td><?php echo utf8_encode($question[$checklist['question2']]); ?></td>
                        <td><?php echo utf8_encode($question[$checklist['question3']]); ?></td>
                        <td><?php echo utf8_encode($question[$checklist['question4']]); ?></td>
                        <td><?php echo utf8_encode($question[$checklist['question5']]); ?></td>
                        <td><?php echo utf8_encode($question[$checklist['question6']]); ?></td>
                        <td><?php echo utf8_encode($question[$checklist['question7']]); ?></td>
                        <td><?php echo utf8_encode($question[$checklist['question8']]); ?></td>
                        <td><?php echo utf8_encode($question[$checklist['question9']]); ?></td>
                        <td><?php echo utf8_encode($question[$checklist['question10']]); ?></td>
                        <td><?php echo utf8_encode($question[$checklist['question11']]); ?></td>
                        <td><?php echo utf8_encode($question[$checklist['question12']]); ?></td>
                        <td><?php echo utf8_encode($question[$checklist['question13']]); ?></td>
                        <td><?php echo utf8_encode($question[$checklist['question14']]); ?></td>
                        <td><?php echo utf8_encode($question[$checklist['question15']]); ?></td>
                        <td><?php echo utf8_encode($question[$checklist['question16']]); ?></td>
                        <td><?php echo utf8_encode($question[$checklist['question17']]); ?></td>
                        <td><?php echo utf8_encode($checklist['question18']); ?></td>
                        <td><?php echo utf8_encode($checklist['question19']); ?></td>
                        <td><?php echo utf8_encode($question[$checklist['question20']]); ?></td>
                        <td><?php echo utf8_encode($question[$checklist['question21']]); ?></td>
                        <td><?php echo utf8_encode($question[$checklist['question22']]); ?></td>
                        <td><?php echo utf8_encode($checklist['actions']); ?></td>
                        <td><?php echo utf8_encode($checklist['nombre_usuario']); ?></td>
                        <td><?php echo utf8_encode($checklist['date_insert']); ?></td>
                    </tr>
                    <?php
                    $correlativo++;     
                }
                ?>
            </tbody>
        </table>
    </div>
<?php   
}
if (count($TRASPLANTES)) {
?>
<div class="responsive_table_container">
    <h3 class="translate" data-traducir_english="Transplants" data-traducir_spanish="Trasplantes">Trasplantes</h3>
    <table class="table display row-border table-responsive" id="table_historial_trasplantes">
        <thead>
             <tr class="active info">
                <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
                <th width="12%" class="translate" data-traducir_english="Grower Send" data-traducir_spanish="Finca Envía">Finca Envía</th>
                <th width="12%" class="translate" data-traducir_english="Grower Receive" data-traducir_spanish="Finca Recibe">Finca Recibe</th>
                <th width="12%" class="translate" data-traducir_english="Blocks Send" data-traducir_spanish="Bloques Envían">Bloques Envían</th>
                <th width="12%" class="translate" data-traducir_english="Amount" data-traducir_spanish="Cantidad">Cantidad</th>
                <th width="12%" class="translate" data-traducir_english="Blocks Receive" data-traducir_spanish="Bloques Reciben">Bloques Reciben</th>
                <th width="12%" class="translate" data-traducir_english="Observation" data-traducir_spanish="Observación">Observación</th>
                <th width="12%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>
                <th width="12%" class="translate" data-traducir_english="Entry" data-traducir_spanish="Ingreso">Ingreso</th>
             </tr>
        </thead>
        <tbody>
            <?php
            $correlativo = 1;
            foreach ($TRASPLANTES as $trasplante)
            {
                ?>
                <tr>
                    <td><?php echo $correlativo; ?></td>
                    <td><?php echo utf8_encode($trasplante['nombre_empresa_envia'].'-'.$trasplante['anio_plantacion_envia'].'-'.$trasplante['num_plantacion_envia'].'-'.$trasplante['codigo_temporada_envia']); ?></td>
                    <td><?php echo utf8_encode($trasplante['nombre_empresa_recibe'].'-'.$trasplante['anio_plantacion_recibe'].'-'.$trasplante['num_plantacion_recibe'].'-'.$trasplante['codigo_temporada_recibe']); ?></td>
                    <td><?php echo utf8_encode($trasplante['bloques']); ?></td>
                    <td><?php echo utf8_encode($trasplante['cantidad']); ?></td>
                    <td><?php echo utf8_encode($trasplante['bloques_trasplante']); ?></td>
                    <td><?php echo utf8_encode($trasplante['observacion']); ?></td>
                    <td><?php echo utf8_encode($trasplante['nombre_usuario']); ?></td>
                    <td><?php echo utf8_encode($trasplante['date_insert']); ?></td>
                </tr>
                <?php
                $correlativo++;
            }
            ?>
        </tbody>
    </table>
</div>
<?php
}

if (count($CALIBRACIONES)) {
    ?>
    <div class="responsive_table_container">
        <h3 class="translate" data-traducir_english="Calibrations" data-traducir_spanish="Calibraciones">Calibraciones</h3>
        <table class="table display row-border table-responsive" id="table_historial_calibraciones">
            <thead>
                 <tr class="active info">
                    <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
                    <th width="15%" class="translate" data-traducir_english="Fertilizer" data-traducir_spanish="Fertilizante">Fertilizante</th>
                    <th width="20%" class="translate" data-traducir_english="Hours" data-traducir_spanish="Horas">Horas</th>
                    <th width="20%" class="translate" data-traducir_english="Observation" data-traducir_spanish="Observación">Observación</th>
                    <th width="20%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>
                    <th width="20%" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</th>
                    <th width="20%" class="translate" data-traducir_english="Entry" data-traducir_spanish="Ingreso">Ingreso</th>
                 </tr>
            </thead>
            <tbody>
                <?php
                $correlativo = 1;
                foreach ($CALIBRACIONES as $calibracion)
                {
                    ?>
                    <tr>
                        <td><?php echo $correlativo; ?></td>
                        <td><?php echo ($fertilizantes[$calibracion['cod_fertilizante']]); ?></td>
                        <td><?php echo utf8_encode($calibracion['horas_aplicacion_calibrar']); ?></td>
                        <td><?php echo utf8_encode($calibracion['observaciones_calibrar']); ?></td>
                        <td><?php echo utf8_encode($calibracion['nombre_ingresa']); ?></td>
                        <td><?php echo utf8_encode($calibracion['fecha_calibracion']); ?></td>
                        <td><?php echo utf8_encode($calibracion['date_insert']); ?></td>
                    </tr>
                    <?php
                    $correlativo++;     
                }
                ?>
            </tbody>
        </table>
    </div>
<?php   
}
?>
