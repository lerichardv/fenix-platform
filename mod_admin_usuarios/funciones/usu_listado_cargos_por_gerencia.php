<?PHP
/*
 * Listado de los módulos disponibles.
 * @author      Dan Urquia
 * @date        2017-03-13


session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_usuario.php");
/*INSTANCIAMIENTOS*/
$DB_USUARIO = new db_usuario();
$cod_gerencia 	= $_POST['x1'];
$CARGOS = $DB_USUARIO->get_listado_cargos($cod_gerencia);
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
$cuerpo_lista = '';
$contador_lista = 1;
if(count($CARGOS) > 0){
		$check = 'checked';
		foreach($CARGOS as $INFO){
			$check = ($INFO['activo'] == 1) ? 'checked' : '';
              $cuerpo_lista .= '
                  <li class="list-group-item" style="padding-bottom:30px">
                      <label class="registro">' . utf8_encode($INFO['cargo']) . ' <small>'.utf8_encode($INFO['descripcion']).'</small></label>
                      <div class="material-switch pull-right">
                          <input class="checkbox_cargo" id="'.utf8_encode($INFO['cod_cargo']).'" name="'.utf8_encode($INFO['cod_cargo']).'" value="'.utf8_encode($INFO['cargo']).'" initials="'.utf8_encode($INFO['descripcion']).'" type="checkbox" ' . $check . '/>
                          <label for="'.utf8_encode($INFO['cod_cargo']).'" class="label-primary"></label>
                      </div>
                  </li>';

					$contador_lista += 1;
    }
} else { //Si no hay registros informará al usuario
		$cuerpo_lista .= 'No hay cargos para este programa.';
}



$cuerpo_lista .= '<script type="text/javascript">
										// Checkboxs
										$( "i.checkbox_cargo" ).on( "click", function() {
											flag_check   = 0;
											flag_ins_act = 0;
												if($(this).is(":checked")) flag_check = 1; else flag_check = 0;
												actualizar_registro($(this).attr("id"), $(this).val(), flag_check, $(this).attr("initials"));
											});

											/* Click on listgroup */
											$( ".registro" ).on( "click", function() {
													flag_check   = 0;
													flag_ins_act = 1;
													if($( this ).parent().find("input[type=checkbox]").is(":checked")) flag_check = 1; else flag_check = 0;
													registro = $( this ).parent().find("input[type=checkbox]").attr("id");
													$("#nombre_registro").val($( this ).parent().find("input[type=checkbox]").val());
													$("#iniciales_registro").val($( this ).parent().find("input[type=checkbox]").attr( "initials" ));
											});
								</script>';
echo $cuerpo_lista;
?>
