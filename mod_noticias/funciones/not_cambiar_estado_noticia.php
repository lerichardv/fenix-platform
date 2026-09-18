<?PHP
/*
 * Inserta una noticia en la base de datos.
 * @author      Oscar Raudales
 * @date        2014-09-26
 */
session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once ("../../libs/db_classes/db_mysql_conn.php"); 
include_once ("../../libs/db_classes/db_noticias.php");
/*INSTANCIAMIENTOS  */ 
$DB_NOTICIA    	    =	new db_noticia();
$cod_noticia		= ($_POST['x1']);
$estado				= ($_POST['x2']);
$estado_noticia = $DB_NOTICIA->send_update_estado_noticia($cod_noticia,$estado);
if (count($estado_noticia) == 0){
		$mensaje = ('1|Error al actualizar el estado');
} else 	{
		$mensaje =utf8_encode($estado_noticia[0]['mensaje']);
		}
echo json_encode($mensaje);		
?>