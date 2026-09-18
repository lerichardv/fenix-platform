<?PHP
/*
 * Subir archivo de formulario
 * @author      Jairo Bonilla
 * @date        2018-10-28
 */
session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php"); 
/*INSTANCIAMIENTOS*/

$nombre_archivo		= $_GET['x1'];

$data = array();
if($_FILES)
{
	$error = false;
	$files = array();
	$uploaddir = '../adjuntos/';
	foreach ($_FILES as $FILES) 
	{
		if(move_uploaded_file($FILES['tmp_name'], $uploaddir.$nombre_archivo))
		{
			$files[] = $uploaddir.$nombre_archivo;
		}
		else
		{
			$error = true;
			$mensaje = '1|No se pudo subir el archivo, favor intentar más tarde';
		}
	}
	$data = ($error) ? array('error' => $FILES['tmp_name'].$uploaddir.$nombre_archivo):array('files'=>$files);
}	
else
{
	$data = array('success' => 'Archivo enviado', 'formData' => $_POST);
	$mensaje = '1|No se pudo subir el archivo';
}

echo $mensaje;
?>
