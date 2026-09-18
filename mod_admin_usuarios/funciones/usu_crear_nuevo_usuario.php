<?PHP
/*
 * Llama a la función que insertara un registro nuevo para usuario o actualizarlo
 * @author 	Linda Zelaya
 * @date 	2017-04-10
*/
ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución

session_start();
if (!isset($_POST['test'])) {
  if (!isset($_SESSION['cod_usuario'])) {
    // header('Location: ../../index.php');
  }
}

// /*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_usuario.php");
// include_once("../../libs/funciones/emailer.php");

/*INSTANCIAMIENTOS*/
$DB_USUARIO         = new db_usuario();
// $EMAILER = new emailer();

$actualizar               = utf8_decode(trim(($_POST['x1'])));
$nombre_1                 = utf8_decode(trim(($_POST['x2'])));
$nombre_2                 = utf8_decode(trim(($_POST['x3'])));
$apellido_1               = utf8_decode(trim(($_POST['x4'])));
$apellido_2               = utf8_decode(trim(($_POST['x5'])));
$telefono_1               = (trim(($_POST['x6'])));
$telefono_2               = (trim(($_POST['x7'])));
$email                    = utf8_decode(trim(($_POST['x8'])));
$direccion                = utf8_decode(($_POST['x9']));
$cod_gerencia             = (trim(($_POST['x10'])));
$cod_cargo                = (trim($_POST['x11']));
$cod_jefe_inmediato       = (trim($_POST['x12']));
$nombre_foto              = utf8_decode(trim($_POST['x13']));
$activo                   = (trim(($_POST['x14'])));
$cod_usuario              = (trim(($_POST['x15'])));
$cod_pais                 = (trim(($_POST['x16'])));
$cod_departamento         = (trim(($_POST['x17'])));
$cod_municipio            = (trim(($_POST['x18'])));
$identidad                = (trim(($_POST['x19'])));
$cod_info_empresa         = json_encode($_POST['x20']);
$cods_granjas             = $_POST['x21'];
$pin                      = (trim(($_POST['x22'])));
$qcpin                    = (trim(($_POST['x23'])));
$cod_categoria_empleado   = (trim(($_POST['x24'])));
$cod_tipo_usuario         = (trim(($_POST['x25'])));
$payrate                  = (trim(($_POST['x26'])));
$user_insert              = $_SESSION['cod_usuario'];

if(empty($email)){
  $temp = $email;
  $email = $DB_USUARIO->generar_email_aleatorio();
  error_log("Email generado para el usuario: " . $email . ", valor de correo enviado: " . $temp);
}


if (isset($_POST['test'])) {
  $user_insert        = $_POST['cod_usuario']; // Usado para realizar pruebas con POSTMAN
}

$validate = $DB_USUARIO->usu_validar_pin_unico($pin , $cod_usuario);
if ($validate[0]['usuario'] != 0) {
  $mensaje = "3|Pin";
  echo $mensaje;
  die();
}
$validate = $DB_USUARIO->usu_validar_qcpin_unico($qcpin, $cod_usuario);
if ($validate[0]['usuario'] != 0) {
  $mensaje = "4|QCPin";
  echo $mensaje;
  die();
}
$validate = $DB_USUARIO->usu_validar_email_unico($email, $cod_usuario);
if (isset($validate[0]['usuario']) && $validate[0]['usuario'] != 0) {
  $mensaje = "5|Email";
  echo $mensaje;
  die();
}

$usuario = strtolower(substr($nombre_1, 0, 1) . $apellido_1);
$password_string = 'abcdefghijklmnpqrstuwxyzABCDEFGHJKLMNPQRSTUWXYZ23456789';
$pass = substr(str_shuffle($password_string), 0, 8);
// if ($actualizar == 1) {
//   $validate = $DB_USUARIO->usu_validar_identidad_actualizar($identidad, $cod_usuario);
// } else {
//   $validate = $DB_USUARIO->usu_validar_identidad($identidad);
// }
// if ($validate[0]['usuario'] != 0) {
//   /**
//    * Este caso es cuando el usuario ya existe en la base de datos
//    * y por lo tanto no se puede crear uno nuevo con el mismo DNI
//    */
//   $mensaje = "0|Identidad";
// } else {
if ($actualizar == 1) {
  $result = $DB_USUARIO->usu_actualizar_usuario(
    $cod_usuario,
    $nombre_1,
    $nombre_2,
    $apellido_1,
    $apellido_2,
    $identidad,
    $telefono_1,
    $telefono_2,
    $email,
    $direccion,
    $cod_gerencia,
    $cod_cargo,
    $cod_jefe_inmediato,
    $nombre_foto,
    $activo,
    $user_insert,
    null,//$cod_pais,
    $cod_departamento,
    null,//$cod_municipio,
    $cod_info_empresa,
    $pin,
    $qcpin,
    $cod_categoria_empleado,
    $cod_tipo_usuario,
    $payrate,
  );
  if (strpos($result, 'Successfully updated user.') === false) {
    if (strpos($result, 'Duplicate entry') !== false && (strpos($result, 'email') !== false || strpos($result, 'email_UNIQUE') !== false)) {
      $mensaje = "5|Email";
      echo $mensaje;
      die();
    } else {
      $mensaje = "2|" . $result;
      echo $mensaje;
      die();
    }
  }
  $mensaje = "1|Exito al actualizar".$result;
  //Proceso para desvincular las granjas que no estan en el array de granjas
  $granjas_vinculadas = $DB_USUARIO->usu_get_granjas_vinculados_a_usuario($cod_usuario);
  $granja_vinculada = 0;
  foreach ($granjas_vinculadas as $granja) {
    $granja_vinculada = 0;

    foreach ($cods_granjas as $cod_granja) {
      if ($cod_granja == $granja["cod_granja"]) {
        $granja_vinculada = 1;
        break;
      }
    }
    if ($granja_vinculada == 0) {
      $DB_USUARIO->usu_eliminar_vinculo_usuario_granja($granja['cod_usuario_farm']);
    }
  }


  //Proceso para vincular las granjas que no estan vinculadas
  foreach ($cods_granjas as $cod_granja) {
    $granja_vinculada = 0;
    foreach ($granjas_vinculadas as $granja) {
      if ($cod_granja == $granja["cod_granja"]) {
        $granja_vinculada = 1;
        break;
      }
    }
    if ($granja_vinculada == 0) {
      $DB_USUARIO->usu_crear_vincular_usuario_granja(
        $cod_usuario,
        $cod_granja
      );
    }
  }
} else if ($actualizar == 2) {
  //hubo un error al cargar la fotografia y se manda actualizar con la fotografia default
  if ($cod_usuario)
    if (empty($cod_usuario)) {
      $id = $DB_USUARIO->get_last_user_inserted();
      $cod_usuario = $id[0]['id_usuario'];
    }
  $result = $DB_USUARIO->usu_actualizar_usuario(
    $cod_usuario,
    $nombre_1,
    $nombre_2,
    $apellido_1,
    $apellido_2,
    $identidad,
    $telefono_1,
    $telefono_2,
    $email,
    $direccion,
    $cod_gerencia,
    $cod_cargo,
    $cod_jefe_inmediato,
    $nombre_foto,
    $activo,
    $user_insert,
    null,//$cod_pais,
    $cod_departamento,
    null,//$cod_municipio,
    $cod_info_empresa,
    $pin,
    $qcpin,
    $cod_categoria_empleado,
    $cod_tipo_usuario,
    $payrate
  );


  //Proceso para desvincular las granjas que no estan en el array de granjas
  $granjas_vinculadas = $DB_USUARIO->usu_get_granjas_vinculados_a_usuario($cod_usuario);
  $granja_vinculada = 0;
  foreach ($granjas_vinculadas as $granja) {
    $granja_vinculada = 0;

    foreach ($cods_granjas as $cod_granja) {
      if ($cod_granja == $granja["cod_granja"]) {
        $granja_vinculada = 1;
        break;
      }
    }
    if ($granja_vinculada == 0) {
      $DB_USUARIO->usu_eliminar_vinculo_usuario_granja($granja['cod_usuario_farm']);
    }
  }


  //Proceso para vincular las granjas que no estan vinculadas
  foreach ($cods_granjas as $cod_granja) {
    $granja_vinculada = 0;
    foreach ($granjas_vinculadas as $granja) {
      if ($cod_granja == $granja["cod_granja"]) {
        $granja_vinculada = 1;
        break;
      }
    }
    if ($granja_vinculada == 0) {
      $DB_USUARIO->usu_crear_vincular_usuario_granja(
        $cod_usuario,
        $cod_granja
      );
    }
  }

  $mensaje = "1|Exito con foto predeterminada";
} else {
  $validate = $DB_USUARIO->usu_validar_usuario($usuario);
  while ($validate[0]['usuario'] != 0) {
    $addendum = rand(1, 9);
    $usuario = $usuario . $addendum;
    $validate = $DB_USUARIO->usu_validar_usuario($usuario);
  }

  $result = $DB_USUARIO->usu_crear_nuevo_usuario(
    $nombre_1,
    empty($nombre_2) ? null : $nombre_2,
    $apellido_1,
    empty($apellido_2) ? null : $apellido_2,
    empty($identidad) ? 'Not defined' : $identidad,
    $usuario,
    $pass,
    empty($telefono_1) ? null : $telefono_1,
    empty($telefono_2) ? null : $telefono_2,
    $email,
    empty($direccion) ? null : $direccion,
    empty($cod_gerencia) ? null : $cod_gerencia,
    empty($cod_cargo) ? null : $cod_cargo,
    $cod_jefe_inmediato,
    $nombre_foto,
    $activo,
    $user_insert,
    null,//$cod_pais,
    $cod_departamento,
    null,//$cod_municipio,
    empty($cod_info_empresa) ? null : $cod_info_empresa,
    $pin,
    $qcpin,
    $cod_categoria_empleado,
    $cod_tipo_usuario,
    $payrate,
  );
  if (strpos($result, 'User successfully entered.') === false) {
    if (strpos($result, 'Duplicate entry') !== false && (strpos($result, 'email') !== false || strpos($result, 'email_UNIQUE') !== false)) {
      $mensaje = "5|Email";
      echo $mensaje;
      die();
    } else {
      $mensaje = "2|" . $result;
      echo $mensaje;
      die();
    }
  }
  $cadena = $result; // 'User successfully entered.|' . $this->db_conexion->lastInsertId()
  $partes = explode("|", $cadena);
  $cod_usuario = $partes[1];

  //enivar correo electronico
  $nombre_completo = $nombre_1 . " " . $apellido_1;
  //echo $nombre_completo;
  // $mensaje = $EMAILER->enviar_mail_password($usuario,$nombre_completo,$email, $pass,3,1);
  $mensaje = "1"."|cod_tipo_usuario: ".$cod_tipo_usuario."|Exito al crear el usuario | pass: " . $pass . " | usuario: " . $cod_usuario . " | result: " . $result;
  foreach ($cods_granjas as $cod_granja) {
    $DB_USUARIO->usu_crear_vincular_usuario_granja(
      $cod_usuario,
      $cod_granja
    );
  }
}
// }
echo $mensaje;
