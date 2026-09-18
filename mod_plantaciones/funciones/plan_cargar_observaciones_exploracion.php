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

$flag_exploracion   = trim(($_POST['x1']));
$cod_exploracion    = trim(($_POST['x2']));


$OBSERVACIONES	= $DB_PLANT->plan_listado_observaciones_exploraciones($flag_exploracion,$cod_exploracion);

if (count($OBSERVACIONES)) {
	?>
    <div class="row">
        <div class="col-md-12"><h2 class="translate" data-traducir_english="Observations" data-traducir_spanish="Observaciones">Observaciones</h2></div>
    </div>

    <div class="row">
    <?php
    foreach ($OBSERVACIONES as $observacion)
    {

        $etiquetas  = '<span class="label label-info label-bordes-arriba label-nombre-2">'.utf8_encode($observacion['nombre_usuario']).'</span>'
                    .'<span class="label label-info">Fecha: '.date('Y-m-d',strtotime($observacion['date_insert'])).'</span>'
                    .'<span class="label label-info label-bordes-abajo">Hora: '.date('h:i A',strtotime($observacion['date_insert'])).'</span>';

        if($_SESSION['cod_usuario'] == $observacion['user_insert'])
        {
            ?>
            <div class="row row-timeline">
                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 text-center">
                    <p class="bubble-timeline">
                        <span class="icon-timeline-container">
                            <img src="../../mod_admin_usuarios/fotos_usuarios/<?php echo utf8_encode($observacion['fotografia']); ?>" class="img-timeline">
                        </span>
                    </p>
                </div>
                <div class="col-xs-8 col-sm-10 col-md-10 col-lg-11 text-left container-post-timeline">
                    <div class="row no-margin nivel nivel-<?php echo $observacion['cod_estado']; ?>">
                        <div class="row row-label">
                            <div class="col-xs-12">
                                <?php echo $etiquetas; ?>
                            </div>
                        </div>
                        <div class="row row-label">
                            <div class="col-xs-12 texto-justificado">
                                <span class="texto-timeline"><?php echo utf8_encode($observacion['observacion']);?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        else
        {
            ?>
            <div class="row row-timeline">
                <div class="col-xs-8 col-sm-10 col-md-10 col-lg-11 text-left container-post-timeline-inverso">
                    <div class="row no-margin nivel-inverso nivel-<?php echo $observacion['cod_estado']; ?>-inverso">
                        <div class="row row-label">
                            <div class="col-xs-12">
                                <?php echo $etiquetas; ?>
                            </div>
                        </div>
                        <div class="row row-label">
                            <div class="col-xs-12 texto-justificado">
                                <span class="texto-timeline"><?php echo utf8_encode($observacion['observacion']);?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 text-center">
                    <p class="bubble-timeline">
                        <span class="icon-timeline-container">
                            <img src="../../mod_admin_usuarios/fotos_usuarios/<?php echo utf8_encode($observacion['fotografia']); ?>" class="img-timeline">
                        </span>
                    </p>
                </div>
            </div>
            <?php
        }
    }	
}
?>
