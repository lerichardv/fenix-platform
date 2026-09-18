<?PHP
/*
 * Desde aqui se desplegará cada módulo con sus respectivos menús que el usuario haya seleccioado.
 * @author      Dan Urquía
 * @date        2014-03-19
 */

session_start();
if (!isset($_SESSION['cod_usuario'])) {
	header('Location: index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once("libs/db_classes/db_mysql_conn.php");
include_once("libs/db_classes/db_general.php");
/*INSTANCIAMIENTOS*/
$DB_GENERAL   		 	 = new db_general();
$_SESSION['cod_modulo']  = $_POST['x1'];/*
if ($_SESSION['cod_modulo'] == 3 || $_SESSION['cod_modulo'] == 8){
	$MENUS = $DB_GENERAL->get_menus_por_perfil_todos($_SESSION['cod_perfil'],$_SESSION['cod_usuario'],$_SESSION['cod_modulo']);
} else {*/
$MENUS = $DB_GENERAL->get_menus_por_perfil($_SESSION['cod_perfil'], $_SESSION['cod_usuario'], $_SESSION['cod_modulo']);
//}
$ruta = "";
foreach ($MENUS as $ROW) {
	if ($ROW['principal'] == 1) {
		$ruta = $ROW['ruta'];
		$cod_menu = $ROW['cod_menu'];
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Módulo</title>
</head>
<script>
	$(document).ready(function(e) {
		/*
		 * Llama al cuerpo del menú que se designo como principal.
		 */
		grl_obtener_cuerpo_menu(<?PHP echo $_SESSION['cod_modulo']; ?>, <?PHP echo "'" . $ruta . "'"; ?>, '', 'div_contenido_form', <?PHP echo $cod_menu; ?>);
		//conf_cargar_formularios_por_menu('div_contenido_form', <?PHP echo $_SESSION['cod_modulo']; ?>, <?PHP echo $cod_menu; ?>);
	});
</script>
<style>
	.cuerpo_invisible {
		background-color: #FFF;
		border: hidden;
	}
</style>

<body>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="bs-example">
				<ul class="nav nav-pills">
					<?PHP foreach ($MENUS as $ROW) {
						//Se crea la función con sus parametros aparte por el uso de comillas en
						//construcción del objeto.
						$funcion = "grl_obtener_cuerpo_menu(" . $_SESSION['cod_modulo'] . ",'" . $ROW['ruta'] . "','','div_contenido_form'," . $ROW['cod_menu'] . ");";
						//Verifica que menu es el principal para agregarle la clase active.
						$principal = ($ROW['principal'] == 1 ? 'class="active"' : '');
						if ($ROW['sub_menu'] != 1 && $ROW['cod_menu_depende'] == ''  && $ROW['cod_menu_depende'] == '') {
							echo '<li ' . $principal . '
								   onclick="' . $funcion . '">
								   		<a href="#" class="translate" data-traducir_english="' . utf8_encode($ROW['menu_english']) . '" data-traducir_spanish="' . utf8_encode($ROW['menu']) . '">' . utf8_encode($ROW['menu']) . '</a>
							  </li>';
							//onclick="grl_obtener_cuerpo_menu('.$_SESSION['cod_modulo'].','.$ROW['ruta'].');">
						} else {
							if ($ROW['sub_menu'] == 1) {
								$menu_desplegable = '';
								$menu_desplegable = '<li class="dropdown">
												<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="translate"data-traducir_english="' . utf8_encode($ROW['menu_english']) . '" data-traducir_spanish="' . utf8_encode($ROW['menu']) . '">
												  ' . utf8_encode($ROW['menu']) . ' </span><span class="caret"></span>
												</a>
													<ul class="dropdown-menu" role="menu">';
								$SUB_MENUS = $DB_GENERAL->get_sub_menus_por_perfil(
									$_SESSION['cod_perfil'],
									$_SESSION['cod_usuario'],
									$_SESSION['cod_modulo'],
									$ROW['cod_menu']
								);
								foreach ($SUB_MENUS as $SUB_ROW) {
									$funcion = "grl_obtener_cuerpo_menu(" . $_SESSION['cod_modulo'] . ",'" . $SUB_ROW['ruta'] . "','','div_contenido_form'," . $SUB_ROW['cod_menu'] . ");";
									if ($SUB_ROW['sub_menu'] == 2) {
										$menu_desplegable .= '<li class="dropdown-submenu">';
										$menu_desplegable .= '<a class="translate test" data-traducir_english="' . utf8_encode($SUB_ROW['menu_english']) . '" data-traducir_spanish="' . utf8_encode($SUB_ROW['menu']) . '" tabindex="-1" href="#">' . utf8_encode($SUB_ROW['menu']) . ' <span class="caret"></span></a>';
										$menu_desplegable .= '<ul class="dropdown-menu">';
										$SUB_MENUS = $DB_GENERAL->get_sub_menus_por_perfil(
											$_SESSION['cod_perfil'],
											$_SESSION['cod_usuario'],
											$_SESSION['cod_modulo'],
											$SUB_ROW['cod_menu']
										);
										foreach ($SUB_MENUS as $SUB_SUB_ROW) {
											$funcion = "grl_obtener_cuerpo_menu(" . $_SESSION['cod_modulo'] . ",'" . $SUB_SUB_ROW['ruta'] . "','','div_contenido_form'," . $SUB_SUB_ROW['cod_menu'] . ");";
											$menu_desplegable .= '<li onclick="' . $funcion . '"><a href="#" class="translate2" data-traducir_english="' . utf8_encode($SUB_SUB_ROW['menu_english']) . '" data-traducir_spanish="' . utf8_encode($SUB_SUB_ROW['menu']) . '">' . utf8_encode($SUB_SUB_ROW['menu']) . '</a></li>';
										}
										$menu_desplegable .= '</ul>';
										$menu_desplegable .= '</li>';
									} else {
										$menu_desplegable .= '<li onclick="' . $funcion . '"><a href="#" class="translate" data-traducir_english="' . utf8_encode($SUB_ROW['menu_english']) . '" data-traducir_spanish="' . utf8_encode($SUB_ROW['menu']) . '">' . utf8_encode($SUB_ROW['menu']) . '</a></li>';
									}
								}
								$menu_desplegable .= '	</ul>
												 </li>';
								echo $menu_desplegable;
							}
						}
					} ?>
				</ul>
			</div>
		</div>
		<div class="panel-body">
			<div class="well cuerpo_invisible" id="div_cuerpo_menu" name="div_cuerpo_menu">
				<!-- Cuerpo del menú seleccionado. -->
				<div class="panel panel-default">
					<div class="panel-body" align="center" style="/*background-image:url(libs/imgs/pics/bg_login.jpg)*/background-color: white;">
						<img src="libs/imgs/Logo.png" class="img-responsive" width="200" height="200">
					</div>
				</div>
			</div>
			<div id="div_contenido_form"></div>
		</div>
	</div>

	<script>
		$(document).ready(function() {
			$('.dropdown-submenu a.test').on("click", function(e) {
				$(this).next('ul').toggle();
				e.stopPropagation();
				e.preventDefault();
			});
		});
	</script>
</body>

</html>