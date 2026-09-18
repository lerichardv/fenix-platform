<?PHP
/*
 * Inserta un mensaje en la base de datos.
 * @author      Oscar Raudales
 * @date        2014-06-16
 */
session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php"); 
include_once ("../../libs/db_classes/db_noticias.php");

/*INSTANCIAMIENTOS   */
$DB_NOTICIA    	    =	new db_noticia();
$cod_noticia		=	utf8_decode(trim('x1'));
$contenido     		=	utf8_decode(trim('x2'));
$imagen				=	trim('x3');

$nuevo_noticia = $DB_NOTICIA->send_insert_nuevo_noticia($cod_noticia,$contenido,$imagen);
if (count($nuevo_noticia) == 0){
	$noticia = ('1|Error al enviar la noticia');
} else 	{
	$noticia = utf8_encode	($nuevo_noticia[0]['noticia']);
		}
echo $noticia;



?>