<?PHP
/*
 * Trae todas las noticias que llenaran el calendario inicial.
 * @author      Dan Urquia
 * @date        2014-09-24
 */
session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}
date_default_timezone_set('America/New_York');
/*CONEXION CON BASE DE DATOS*/
include_once ("../../libs/db_classes/db_mysql_conn.php"); 
include_once ("../../libs/db_classes/db_general.php");
/*INSTANCIAMIENTOS   */
$DB_GENERAL =	new db_general();
$NOTICIAS	= $DB_GENERAL->get_noticia_activa($_SESSION['cod_info_empresa']);
$NOTICIAS2	= $DB_GENERAL->get_noticia_activa_formularios($_SESSION['cod_info_empresa']);
$out = array();
foreach ($NOTICIAS as $NOTICIA){
	$out[]  = array('id' => $NOTICIA['cod_noticia'],
					'title' => utf8_encode($NOTICIA['titulo']),
					'url' => 'mod_noticias/ui/not_modal_noticia.php?x1='.$NOTICIA['cod_noticia'],
					'class' => $NOTICIA['clase'],
					'start' => strtotime($NOTICIA['fecha_inicio']).'000',
					'end' => strtotime($NOTICIA['fecha_fin']).'000'); // Milliseconds);
}
foreach ($NOTICIAS2 as $NOTICIA){
	$out[]  = array('id' => $NOTICIA['cod_noticia'],
					'title' => utf8_encode($NOTICIA['titulo']),
					'url' => 'mod_noticias/ui/not_modal_noticia.php?x1='.$NOTICIA['cod_noticia'],
					'class' => $NOTICIA['clase'],
					'start' => strtotime($NOTICIA['fecha_inicio']).'000',
					'end' => strtotime($NOTICIA['fecha_fin']).'000'); // Milliseconds);
}	
echo json_encode(array('success' => 1, 'result' => $out));
?>