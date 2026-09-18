<?PHP
/*
 * Listado de los cargos disponibles para los usuarios.
 * @author      Jairo Bonilla
 * @date        2016-06-12


session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_usuario.php");
/*INSTANCIAMIENTOS*/
$DB_USUARIO = new db_usuario();
$flag_biblioteca = trim(utf8_decode($_POST['x1']));
$GERENCIAS       = $DB_USUARIO->usu_listado_gerencias($flag_biblioteca);
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($GERENCIAS as $GERENCIA){
	$data[]=array_map('utf8_encode', $GERENCIA);
}
echo json_encode($data);
?>
