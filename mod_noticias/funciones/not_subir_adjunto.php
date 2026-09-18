<?PHP
/*
 * Subir archivos adjuntos del formulario "Noticias"
 * @author      Oscar Raudales
 * @date        2014-09-24
 */
session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php"); 
include_once ("../../libs/db_classes/db_general.php");
$fecha			= date("Y-m-d");
$cod_usuario	= $_SESSION['cod_usuario'];
  /*  switch(strtolower($_FILES['0']['type']))
        {
            //allowed file types
            case 'image/png':
            case 'image/gif':
            case 'image/jpeg':
            case 'image/bmp':
            case 'application/pdf':
            break;
            default:{
				$data = array('error' => 'Tipo de archivo no válido', 'formData' => $_POST);
                echo json_encode($data);
			    die(); //output error
			}
    }
	if ($adjunto != '') $adjunto=$uploaddir.'_'.$cod_usuario.'_'.$fecha.'_'.$adjunto;
	*/
			if($_FILES['0']['name'])
			{	
				$data = array();
				$error = false;
				$files = array();
				$uploaddir = '../../mod_noticias/adjuntos/';				
					if(move_uploaded_file($_FILES[0]['tmp_name'], $uploaddir.'_'.$cod_usuario.'_'.$fecha.'_'.$_FILES[0]['name']))
						{
							$files[] = $uploaddir .$file['name'];
						}
						else
						{
							$error = true;
						}
				$data = ($error) ? array('error' => 'Error al copiar los archivos'):array('files'=>$files);
			}	
			else{
				$data = array('success' => 'Archivo enviado', 'formData' => $_POST);
			}
		echo json_encode($data);
?> 
