<?PHP
/*
 * Actualizamos el listado de usuarios favoritos por granja y locacion
 * @author 	Edwin Olivera
 * @date 	2025-03-23
 */
ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
try {
  session_start();
  if (!isset($_SESSION['cod_usuario'])) {
    header('Location: ../../index.php');
  }

  /*CONEXION CON BASE DE DATOS*/
  include_once("../../libs/db_classes/db_mysql_conn.php");
  include_once("../../libs/db_classes/db_usuario.php");

  /*INSTANCIAMIENTOS*/
  $DB_USUARIO = new db_usuario();
  $usuarios = (array) json_decode($_POST['x1']);
  $cods_farms = $_POST["x2"];
  $cods_locations = $_POST["x3"];
  $dataUsuarioFavoritosOriginal = $_POST["x4"];

  $cod_usuarios = [];
  foreach ($usuarios as $usuario) {
    array_push($cod_usuarios, $usuario->id);
  }

  if (!is_array($cods_farms)) {
    $cods_farms = explode(',', $cods_farms);
  }

  if (!is_array($cods_locations)) {
    $cods_locations = explode(',', $cods_locations);
  }
  // $dataUsuarioFavoritosOriginal = json_decode($dataUsuarioFavoritosOriginal, true);
  $codsUsuariosRemovidos = [];

  foreach ($dataUsuarioFavoritosOriginal as $favorito) {
    if (!in_array($favorito['cod_usuario'], $cod_usuarios)) {
      $codsUsuariosRemovidos[] = $favorito["cod_usuario"];
    }
  }

  $codigosGranjas = [];
  $codigosLocaciones = [];
  if (is_array($codsUsuariosRemovidos)) {
    $codsUsuariosRemovidos = implode(',', $codsUsuariosRemovidos);
  }
  if (is_array($cods_farms)) {
    $codigosGranjas = implode(',', $cods_farms);
  }

  if (is_array($cods_locations)) {
    $codigosLocaciones = implode(',', $cods_locations);
  }
// echo json_encode( $codigosGranjas).'<br>';
// echo json_encode($codigosLocaciones);
 $DB_USUARIO->usu_remover_usuarios_favoritos_granjas_locaciones($codsUsuariosRemovidos, $codigosGranjas, $codigosLocaciones);

  // // echo  json_encode($codsUsuariosRemovidos);
  // // die();
  echo $DB_USUARIO->usu_actualizar_usuarios_favoritos_granjas_locaciones($cod_usuarios,  $cods_farms, $cods_locations, $_SESSION['cod_usuario'])
    ? "0|Se han registrado los favoritos correctamente."
    : "1|No se pudo procesar la acción.";
} catch (Exception $e) {
  echo "1|Error: " . $e->getMessage();
}
