<?PHP
/*
 * Subir archivos adjuntos del formulario "Contáctenos"
 * @author      Oscar Raudales
 * @date        2014-06-19
 */
session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php"); 
include_once ("../../libs/db_classes/db_general.php");

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
    }*/
			if($_FILES['0']['name'])
			{	
				$data = array();
				$error = false;
				$files = array();
				$uploaddir = '../../mod_noticias/media/';				
					if(move_uploaded_file($_FILES[0]['tmp_name'], $uploaddir.$_FILES[0]['name']))
						{
							$files[] = $uploaddir .$file['name'];
						}
						else
						{
							$error = true;
						}
					
				$data = ($error) ? array('error' => 'Tipo de archivo no permitido'):array('files'=>$files);
			}	
			else{
				$data = array('success' => 'Archivo enviado', 'formData' => $_POST);
			}
					
		echo json_encode($data);
?> 
