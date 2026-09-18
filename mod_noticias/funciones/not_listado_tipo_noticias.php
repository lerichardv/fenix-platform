<?PHP
/*
 * Listado de los tipos de jornadas que pueden ser seleccionados.
 * @author      Dan Urquía
 * @date        2014-10-13
 */

session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php"); 
include_once("../../libs/db_classes/db_general.php");
/*INSTANCIAMIENTOS*/
$DB_GENERALES  = new db_general();
$TIPO_NOTICIAS = $DB_GENERALES->get_tipo_noticias();
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
if (count($TIPO_NOTICIAS) == 0){
	$data = array(array('cod_tipo_noticia' => '-b',
						'tipo_noticia' => 'No hay datos',
						'clase' => 'No hay datos'));
} else {
	foreach($TIPO_NOTICIAS as $TIPO_NOTICIA){
		$data[]=array_map('utf8_encode', $TIPO_NOTICIA);
	}
}
echo json_encode($data);
?>