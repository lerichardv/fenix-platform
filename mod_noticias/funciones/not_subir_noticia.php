<?PHP
/*
 * Inserta una noticia en la base de datos.
 * @author      Oscar Raudales
 * @date        2014-09-24
 */
session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once ("../../libs/db_classes/db_mysql_conn.php");
include_once ("../../libs/db_classes/db_noticias.php");

/*INSTANCIAMIENTOS   */
$DB_NOTICIA    	    =	new db_noticia();
$fecha			 	= date("Y-m-d");
$uploaddir 			= '../../mod_noticias/adjuntos/';
$cod_usuario		= ($_POST['x1']);
$titulo				= utf8_decode(trim($_POST['x2']));
$adjunto			= trim($_POST['x3']);
$noticia			= utf8_decode(trim($_POST['x4']));
$fecha_inicial		= trim($_POST['x5']). ' 05:00:00';
$fecha_final	    = trim($_POST['x6']). ' 20:00:00';
$cod_tipo_noticia   = trim($_POST['x7']);
$cod_info_empresa   = trim($_POST['x8']);
if ($adjunto != '') $adjunto=$uploaddir.'_'.$cod_usuario.'_'.$fecha.'_'.$adjunto;
$nueva_noticia = $DB_NOTICIA->send_insert_nueva_noticia($cod_usuario,
														$titulo,
														$adjunto,
														$noticia,
														$fecha_inicial,
														$fecha_final,
														$cod_tipo_noticia,
														$cod_info_empresa);
if (count($nueva_noticia) == 0){
	$mensaje = ('1|Error al enviar la noticia');
} else 	{
	$mensaje = utf8_encode($nueva_noticia[0]['mensaje']);
		}
echo $mensaje;
?>