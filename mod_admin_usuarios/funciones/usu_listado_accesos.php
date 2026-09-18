<?PHP
/*
 * Listado de los módulos disponibles.
 * @author      Dan Urquia
 * @date        2017-03-13
 */

session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_usuario.php");
/*INSTANCIAMIENTOS*/
$DB_USUARIO = new db_usuario();
$cod_usuario 	= $_POST['x1'];
$MODULOS = $DB_USUARIO->get_listado_modulos();
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
$cuerpo_lista = '';
$contador_lista = 1;
if(count($MODULOS) > 0){
		$check = 'checked';
		foreach($MODULOS as $MODULO){
			  $check = '';
				$OPCIONES = $DB_USUARIO->get_opciones_modulo_perfil($MODULO['cod_modulo'],$cod_usuario);
				$PERFIL = $DB_USUARIO->usu_get_info_usuario($cod_usuario);
				$codigo_menu = utf8_encode($MODULO['cod_modulo']). "|". utf8_encode($PERFIL[0]['cod_perfil']) . "|" ;
				$check = ($OPCIONES[0]['todas_opciones'] == 1) ? 'checked' : '';
				$cuerpo_lista .= '
						<li class="list-group-item list-group-item-info" style="padding-bottom:30px">
								<label class="titulo_modulo translate" data-traducir_english="' . utf8_encode($MODULO['nombre_english']) . '" data-traducir_spanish="' . utf8_encode($MODULO['nombre']) . '">' . utf8_encode($MODULO['nombre']) . '</label>
								<div class="material-switch pull-right">
										<input class="checkbox_acceso" id="'.$codigo_menu.'" name="'.utf8_encode($MODULO['nombre']).'" value="'.$codigo_menu.'" type="checkbox" ' . $check . '/>
										<label for="'.$codigo_menu.'" class="label-warning"></label>
								</div>
						</li>';
				$MENUS = $DB_USUARIO->get_listado_menus_modulo_perfil($cod_usuario, $MODULO['cod_modulo']);
				if(count($MENUS) > 0){
						$check = 'checked';
						foreach($MENUS as $MENU){
								$check = ($MENU['activo'] == 1) ? 'checked' : '';
								$codigo_menu = utf8_encode($MENU['cod_perfil']) . "|" . utf8_encode($MENU['cod_modulo']) . "|" . utf8_encode($MENU['cod_menu']) . "|";
								$cuerpo_lista .= '
										<li class="list-group-item" style="padding-bottom:30px">
												<label class="registro translate" data-traducir_english="' . utf8_encode($MENU['menu_english']) . '" data-traducir_spanish="' . utf8_encode($MENU['menu']) . '">' . utf8_encode($MENU['menu']) . '</label>
												<div class="material-switch pull-right">
														<input class="checkbox_acceso" id="'.$codigo_menu.'" name="'.utf8_encode($MENU['menu']).'" value="'.$codigo_menu.'" type="checkbox" ' . $check . '/>
														<label for="'.$codigo_menu.'" class="label-primary"></label>
												</div>
										</li>';
								$contador_lista += 1;
						}
				} else { //Si no hay registros informará al usuario
						$cuerpo_lista .= 'Error menú';
				}
		}
} else { //Si no hay registros informará al usuario
		$cuerpo_lista .= 'Error módulo';
}



$cuerpo_lista .= '<script type="text/javascript">
										// Checkboxs
										$( ".checkbox_acceso" ).on( "click", function() {
												if($(this).is(":checked")) flag_check = 1; else flag_check = 0;
												acciones_registro($(this).val()+flag_check);
										});
								</script>';
echo $cuerpo_lista;
?>
