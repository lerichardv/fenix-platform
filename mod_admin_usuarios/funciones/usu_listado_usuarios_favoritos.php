<?PHP
/*
 * Obtenemos el listado de usuarios favoritos por granja y locacion
 * @author 	Edwin Olivera
 * @date 	2025-03-23
*/

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_usuario.php");
include_once("../../libs/funciones/emailer.php");

/*INSTANCIAMIENTOS*/
$DB_USUARIO     = new db_usuario();
$cod_farms = $_POST["x1"];
$cod_locations = $_POST["x2"];


try {
  if (is_array($cod_farms)) {
    $cod_farms = implode(',', $cod_farms);
  }

  if (is_array($cod_locations)) {
    $cod_locations = implode(',', $cod_locations);
  }


  $USUARIOS_FAVORITOS = $DB_USUARIO->usu_buscar_favoritos_granja_locacion($cod_farms, $cod_locations);

  //Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
  foreach ($USUARIOS_FAVORITOS as $locacion) {
    $data[] = array_map('utf8_encode', $locacion);
  }

  echo json_encode($data);
  // echo json_encode($cod_locations);
} catch (\Throwable $th) {
  //throw $th;
  $mensaje = $th;
}

echo ($mensaje);
