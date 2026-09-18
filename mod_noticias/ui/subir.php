<?php
  //Como no sabemos cuantos archivos van a llegar, iteramos la variable $_FILES
  $ruta="archivos/";
  foreach ($_FILES as $key) {
    if($key['error'] == UPLOAD_ERR_OK ){//Verificamos si se subio correctamente
      $nombre = $key['name'];//Obtenemos el nombre del archivo
      $temporal = $key['tmp_name']; //Obtenemos el nombre del archivo temporal
      $tamano= ($key['size'] / 1000)."Kb"; //Obtenemos el tamaño en KB
      move_uploaded_file($temporal, $ruta . $nombre); //Movemos el archivo temporal a la ruta especificada
      //El echo es para que lo reciba jquery y lo ponga en el div "cargados"
      echo "
        <div id='subido'>
        <h12><strong>Nombre del archivo: $nombre</strong></h2><br />
        <h12><strong>Tamaño del archivo: $tamano</strong></h2><br />
        <hr>
        </div>
      ";
    }else{
      echo $key['error']; //Si no se cargo mostramos el error
    }
  }
?>