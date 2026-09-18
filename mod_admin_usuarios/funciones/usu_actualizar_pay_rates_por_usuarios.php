<?PHP
/*
 * Ejecuta el proceso de actualizar los pay rates para los usuarios dados
 * @author 	Ricardo Valladares
 * @date 	2024-10-23
 */

session_start();
if(!isset($_SESSION['cod_usuario'])){
  header('Location: ../../index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_usuario.php");

/*INSTANCIAMIENTOS*/
$DB_USUARIO = new db_usuario();
$pay_rate = trim($_POST['x1']);
$usuarios = (array) json_decode($_POST['x2']);
$cod_usuarios = [];
foreach($usuarios as $usuario){
  array_push($cod_usuarios, $usuario->id);
}

echo $DB_USUARIO->usu_actualizar_pay_rates_por_usuario($pay_rate, $cod_usuarios) 
  ? "0|Se ha procesado el pay_rate en los usuarios." 
  : "1|No se pudo procesar la acción.";
