<?PHP
/*
 * Listado de usuarios por finca.
 * @author      Edwin Olivera
 * @date        2021-10-31
*/

session_start();
if(!isset($_SESSION['cod_usuario'])){
    header('Location: index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_plantaciones.php");
/*INSTANCIAMIENTOS*/
$DB_PLAN  = new db_plantaciones();
$cod_info_empresa   = trim(utf8_decode($_POST['x1']));

// $USUARIOS = (array) $DB_PLAN->usu_listado_usuarios_por_empresa($cod_info_empresa);
$USUARIOS = (array) $DB_PLAN->plan_listado_recomendado_por_finca($cod_info_empresa);

//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
$data = [];
foreach($USUARIOS as $USUARIO){
    $data[]=array_map('utf8_encode', $USUARIO);
}
// echo $codigosFinalesInfoEmpresa;
echo json_encode($data) ;
?>
