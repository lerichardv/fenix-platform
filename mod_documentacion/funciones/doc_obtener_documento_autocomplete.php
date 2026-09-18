<?PHP
/*
 * Obtiene el documento descrito en la busqueda.
 * @author Dan Urquía
 * @date 	 2017-06-28
 */

session_start();
if(!isset($_SESSION['cod_usuario'])){
        header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_documentacion.php");
/*INSTANCIAMIENTOS*/
$DB_DOCUMENTOS  = new db_documentacion();
$cod_documento = $_POST['x1'];

$DOCUMENTOS	= $DB_DOCUMENTOS->get_info_documento($cod_documento);

//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
if (count($DOCUMENTOS) == 0){
        $data = '';
} else {
        foreach($DOCUMENTOS as $DOCUMENTO){
                $data[]=array_map('utf8_encode', $DOCUMENTO);
        }
}
echo json_encode($data);
?>
