<?PHP

/*CONEXION CON BASE DE DATOS*/
include_once("Conn.php"); 
include_once("Consultas.php");
/*INSTANCIAMIENTOS*/
$DB_USUARIOS  = new Consultas();

$USUARIOS = $DB_USUARIOS->Ver_usuarios();


//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($USUARIOS as $USUARIO){
	$data[]=array_map('utf8_encode', $USUARIO);
}
echo json_encode($data);

?>