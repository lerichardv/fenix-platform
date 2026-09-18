<?PHP
/*
 * Listado de los cargos disponibles para los usuarios.
 * @author
 * @date        2016-06-12
*/

session_start();
if(!isset($_SESSION['cod_usuario'])){
		echo "<script>window.location.href = '" . $_SESSION['úrl_inicio'] . "';</script>"; // Usando JS para redireccionar
	// header('Location: index.php'); // Método anterior (No funcional)
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_usuario.php");
/*INSTANCIAMIENTOS*/
$DB_USUARIO = new db_usuario();
//query que obtiene la ultima fecha por usuario del historial de passwords y revisa si esa fecha es menor a tres messes.
//Si encuentra alguno, actualiza el pass_pending = 2 para solicitarle nueva contraseña
$result     = $DB_USUARIO->get_expired_passwords();
echo $result;
?>
