<?PHP

/*

 * Pantalla donde se realiza el registro de los casos.

 * @author      Kevin Fúnez

 * @date        2017-03-26

 */



session_start();

if (!isset($_SESSION['cod_usuario'])) {

	echo "<script>window.location.href = '" . $_SESSION['úrl_inicio'] . "';</script>"; // Usando JS para redireccionar
	// header('Location: ../index.php'); // Método anterior (No funcional)
}

/*CONEXION CON BASE DE DATOS*/

include_once "../../libs/db_classes/db_mysql_conn.php";

include_once "../../libs/db_classes/db_usuario.php";



/*INSTANCIAMIENTOS*/

$DB_USUARIO  = new db_usuario();

$cod_usuario = $_POST['x2'];

$actualizar  = 0;
if ($cod_usuario != null) {

	$INFO_USU   = $DB_USUARIO->usu_get_info_usuario($cod_usuario);
} else {
	$INFO_USU = [];
}

if (!empty($INFO_USU)) {
	$actualizar = 1;
} else {

	$cod_usuario = 0;
}

?>

<script type="text/javascript">
	/*funcion que pone la primera letra en mayuscula*/

	function toTitleCase(str)

	{

		return str.replace(/\w\S*/g, function(txt) {
			return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
		});

	}



	var actualizar = "<?php echo ($actualizar); ?>";
	var error_registro = 0;

	var array_file_list = [];

	array_file_list.push({
		id: 0,
		file: '',
		file_ext: ''
	});

	var $container_upload_box = $("#container_upload_box");

	var files_foto = '';

	var nombre_foto = '';

	var ext = '';

	if (actualizar == 1) {

		jQuery.ajaxSetup({
			async: false
		});

		$('#modal_loading').modal('hide');

		grl_overlay_loading('');

		jQuery.ajaxSetup({
			async: true
		});

	}



	function update_list_item_interface_foto($li, nombre_archivo) {

		$li.unbind('click');

		if (nombre_archivo != '') {

			$li.addClass('row-with-attachment');

			$li.find("span.state-icon").addClass('fa-paperclip').removeClass('fa-check-square-o');

			$li.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file with-attachment').data('disabled', false);

			$li.find('i.icon-file').addClass('hide');

			$li.find('span.msj-btn-file').text('Fotografia');

			$li.find(".btn-upload-file").addClass('hide').data('disabled', true);

			$li.find(".container-upload-box").append('<a tabindex="0" target="_blank" href="' + nombre_archivo + '" class="smooth-transition btn-view-file">' +

				'<i class="fa fa-eye"></i>' +

				'</a>');

		}

	}



	function update_list_item_interface2_foto($li) {

		$li.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file').data('disabled', false);

		$li.find('i.icon-file').removeClass().addClass('icon-file fa fa-file');

		$li.find('span.msj-btn-file').text('Seleccione archivo');

		$li.find(".btn-upload-file").removeClass().addClass('smooth-transition btn-upload-file').data('disabled', true);

		$li.find(".btn-view-file").remove();

		$li.find(".btn-view-file-delete").parent('div').remove();

		$li.find("input[type=file]").replaceWith(

			$li.find("input[type=file]").val('').clone(true)

		);

		$li.find("input[type=file]").attr('title', 'Seleccione un Archivo');

		$li.on('change', 'input[type=file]', prepare_upload2);

	}





	$(document).ready(function() {

		//Inicializar la barra de botones

		init_button_bar();

		jQuery.ajaxSetup({
			async: false
		});

		//Habilitación de listboxs

		$('.selectpicker').selectpicker({

			dropupAuto: 'true',

			container: 'body',

			size: '10',

			width: '100%',

			style: 'btn-sm btn-info',

			tickIcon: 'fa fa-check'

		});



		$('#cod_activo').selectpicker({

			dropupAuto: 'true',

			container: 'body',

			size: '10',

			width: '100%',

			style: 'btn-sm btn-warning'

		});



		if (actualizar == 0) {

			grl_overlay_loading('');

		}

		//Habilita los selects para mobile

		if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {

			$('.selectpicker').selectpicker('mobile');

		}



		/*Constructores de selectpickers*/

		usu_constructor_gerencias();

		usu_constructor_tipos_de_usuarios();

		usu_constructor_jefe_inmediato();

		grl_constructor_paises();

		grl_constructor_departamentos_por_pais();

		conf_constructor_listado_granjas();

		// farm_constructor_listado_granjas();

		inv_constructor_listado_estados();

		farm_constructor_listado_granjas_por_estado_con_datos('cod_granja', false);

		// usu_constructor_granjas(); // No terminada


		$("#cod_gerencia").change(function() {

			$('#cod_cargo').empty();

			usu_constructor_cargos_por_gerencia();

		});

		$("#cod_estado").change(function() {

			$('#cod_granja').empty();
			// usu_constructor_cargos_por_gerencia();
			farm_constructor_listado_granjas_por_estado_con_datos('cod_granja', false);

		});



		$("#cod_pais").change(function() {

			$("#cod_estado").empty();

			grl_constructor_departamentos_por_pais();

		});



		$("#cod_estado").change(function() {

			$("#cod_municipio").empty();

			grl_constructor_municipios_por_pais();

		});



		/*Máscaras de formato de ingreso de datos*/

		$('.nombre').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {
			translation: {
				'S': {
					pattern: /[A-Za-z ÁÉÍÓÚáéúíóúÑñ]/,
					optional: true
				}
			}
		});

		$('.telefono').mask('(999) 999-9999');





		$("#email").on('blur', function(event) {

			if (!grl_validarEmail($(this).val())) {

				$(this).val('');

				grl_mensaje('E-mail not valid ', 'Please check.', 'danger');

			}

		});

		/*----------------------------------------------------------------------------------

	                                    Validando listboxs

	    ----------------------------------------------------------------------------------*/

		$(".selectpicker.requerido").change(function() {

			var objeto = $(this);

			if (objeto.val() == null || objeto.val() == '-b' || objeto.val() == '') {

				objeto.selectpicker('setStyle', 'btn-info', 'remove');

				objeto.selectpicker('setStyle', 'btn-danger');

			} else {

				objeto.selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');

				objeto.selectpicker('setStyle', 'btn-info');

				objeto.removeClass('campo-vacio');

			}

			objeto.selectpicker('refresh');

		});



		/*----------------------------------------------------------------------------------

		                    Validación input, textarea requeridos

		----------------------------------------------------------------------------------*/

		$(".input.requerido, .input.requerido-modal").keyup(function(event) {

			if ($(this).val().trim() != "") {

				$(this).removeClass('input-has-error campo-vacio campo-vacio-modal');

			} else {

				$(this).addClass('input-has-error');

			}

		});



		/* Clic botón seleccionar archivo */

		$(".btn-select-file").on('click', function(e) {

			e.stopPropagation();

			$(this).parent().find('input[type=file]').click();

			return false;

		});

		/* Clic botón subir archivo */

		$('.btn-upload-file').on('click', function(e) {

			e.stopPropagation();

			if ($(this).data('disabled') == false) {

				var $container_upload_box = $(this).parent();

				var id = $(this).data('id');

				var $li = $container_upload_box.closest("li.list-group-item");

				// Busca id dentro del objeto [array_file_list]

				var temp_array = array_file_list.filter(function(attr) {

					return attr.id == id;

				});

				$li.unbind('click');

			}

		});



		/* Disparador input seleccionador de archivo */

		$('input[type=file]').on('click', function(e) {

			e.stopPropagation();

		});

		// Evento al seleccionar un archivo

		$('#archivo-foto').on('change', prepare_upload2);





		<?php

		if ($actualizar == 1) {

		?>
			jQuery.ajaxSetup({
				async: false
			});

			$('#nombre_1').val("<?php echo utf8_encode($INFO_USU[0]['nombre_1']); ?>");

			$('#nombre_2').val("<?php echo utf8_encode($INFO_USU[0]['nombre_2']); ?>");

			$('#apellido_1').val("<?php echo utf8_encode($INFO_USU[0]['apellido_1']); ?>");

			$('#apellido_2').val("<?php echo utf8_encode($INFO_USU[0]['apellido_2']); ?>");

			$('#identidad').val("<?php echo $INFO_USU[0]['identidad']; ?>");

			$('#telefono_1').val("<?php echo $INFO_USU[0]['telefono_1']; ?>");

			$('#telefono_2').val("<?php echo $INFO_USU[0]['telefono_2']; ?>");

			$('#email').val("<?php echo utf8_encode($INFO_USU[0]['email']); ?>");

			$('#input_pin').val("<?php echo utf8_encode($INFO_USU[0]['pin']); ?>");

			$('#input_qcpin').val("<?php echo utf8_encode($INFO_USU[0]['qcpin']); ?>");

			$('#direccion').val("<?php echo utf8_encode($INFO_USU[0]['direccion']); ?>");

			$('#cod_gerencia').selectpicker('val', <?php echo $INFO_USU[0]['cod_gerencia']; ?>);

			$('#cod_cargo').selectpicker('val', <?php echo $INFO_USU[0]['cod_cargo']; ?>);

			$('#cod_activo').selectpicker('val', <?php echo $INFO_USU[0]['activo']; ?>);

			$('#cod_jefe_inmediato').selectpicker('val', <?php echo $INFO_USU[0]['cod_jefe_inmediato']; ?>);

			$('#cod_pais').selectpicker('val', <?php echo $INFO_USU[0]['cod_pais']; ?>);

			$('#cod_estado').selectpicker('val', <?php echo $INFO_USU[0]['cod_estado']; ?>);

			$('#cod_municipio').selectpicker('val', <?php echo $INFO_USU[0]['cod_municipio']; ?>);

			$('#cod_info_empresa').selectpicker('val', <?php echo $INFO_USU[0]['cod_info_empresa']; ?>);

			$('#cod_estado').selectpicker('val', <?php echo $INFO_USU[0]['cod_estado']; ?>);

			$('#cod_categoria_empleado').selectpicker('val', <?php echo $INFO_USU[0]['es_veterano']; ?>);

			$('#cod_tipo_usuario').selectpicker('val', <?php echo $INFO_USU[0]['cod_tipo_usuario']; ?>);

			farm_constructor_listado_granjas_por_estado_con_datos('cod_granja', false);

			$('#cod_granja').selectpicker('val', [<?php echo $INFO_USU[0]["granjas_vinculadas"]; ?>]);

			$('#input_payrate').val(<?php echo $INFO_USU[0]["pay_rate"]; ?>);

			update_list_item_interface_foto($('#container_upload_box_foto'), "<?php echo $INFO_USU[0]['fotografia']; ?>")

			jQuery.ajaxSetup({
				async: true
			});

		<?php

		}

		?>

		$('#modal_loading').modal('hide');



	});



	function prepare_upload2(event)

	{

		var $container_upload_box = $(this).parent();

		files_foto = event.target.files;

		var cancel_button_is_clicked = files_foto[0];

		if (cancel_button_is_clicked == undefined) {

			constructor_file_input($(this).parent());

		} else

		{

			var id = $container_upload_box.find('.btn-select-file').data('id');

			var filesize = files_foto[0].size / 1024 / 1024;

			if (filesize > 10) {

				grl_mensaje('File size not allowed. ', 'Only files smaller than 10 MB are allowed.', 'warning');

				cambio_adjunto = false;

				constructor_file_input($container_upload_box);

			} else

			{

				ext = $(this).val().match(/\.([^\.]+)$/)[1];

				ext = ext.toLowerCase();

				update_array_file_list(id, files_foto, ext);

				switch (ext)

				{

					case 'jpg':

					case 'jpeg':

					case 'bmp':

					case 'png':

					case 'tif':

					case 'tiff':

						$container_upload_box.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file ready-to-upload');

						$container_upload_box.find('.btn-upload-file').removeClass().addClass('smooth-transition btn-upload-file ready-to-upload');

						$container_upload_box.find('.btn-upload-file').data('disabled', true);

						$container_upload_box.find('i.icon-file').removeClass().addClass('icon-file fa fa-check');

						$container_upload_box.find('span.msj-btn-file').text('Archivo listo para subir');

						$container_upload_box.find('.btn-view-file').remove();

						$container_upload_box.find(".btn-view-file-delete").parent('div').remove();

						break;

					default: {

						grl_mensaje('Tipo de archivo no permitido', 'solo se permiten imágenes.', 'warning');

						constructor_file_input($(this).parent());

					}

				}

			}

		}

	}





	function constructor_file_input($container_upload_box) {

		$container_upload_box.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file');

		$container_upload_box.find('.btn-delete-file').removeClass().addClass('smooth-transition btn-delete-file ready-to-upload');

		$container_upload_box.find('.btn-delete-file').data('disabled', false);

		$container_upload_box.find('i.icon-file').removeClass().addClass('icon-file fa fa-file');

		$container_upload_box.find('span.msj-btn-file').text(' Seleccione archivo');

		$container_upload_box.find('.btn-view-file').addClass('hide');

		$container_upload_box.find("input[type=file]").replaceWith(

			$container_upload_box.find("input[type=file]").val('').clone(true));

		$container_upload_box.find("input[type=file]").attr('title', 'Seleccione un Archivo');

		files = '';

		file_ext = '';

		var id = $container_upload_box.find('.btn-select-file').data('id');

		update_array_file_list(id, files, file_ext);

	}



	function update_array_file_list(id, file, file_ext) {

		//alert(id + file_ext);

		for (var i in array_file_list) {

			if (array_file_list[i].id == id) {

				array_file_list[i].file = file;

				array_file_list[i].file_ext = file_ext;

				break;

			}

		}

	}



	$('#btn_crear_usuario').click(function() {


		var todo_bien = 0;

		if ($('#nombre_1').val() == '' || $('#nombre_1').val() == null)

		{

			todo_bien = 1;

			$('#nombre_1').addClass('input-has-error');

		} else

		{

			$('#nombre_1').removeClass('input-has-error');

		}



		if ($('#apellido_1').val() == '' || $('#apellido_1').val() == null)

		{

			todo_bien = 1;

			$('#apellido_1').addClass('input-has-error');

		} else

		{

			$('#apellido_1').removeClass('input-has-error');

		}



		// if ($('#identidad').val() == '' || $('#identidad').val() == null)

		// {

		// 	todo_bien = 1;

		// 	$('#identidad').addClass('input-has-error');

		// } else

		// {

		// 	$('#identidad').removeClass('input-has-error');

		// }



		// if ($('#telefono_2').val() == '' || $('#telefono_2').val() == null)

		// {

		// 	todo_bien = 1;

		// 	$('#telefono_2').addClass('input-has-error');

		// } else

		// {

		// 	$('#telefono_2').removeClass('input-has-error');

		// }



		// if ($('#email').val() == '' || $('#email').val() == null)

		// {

		// 	todo_bien = 1;

		// 	$('#email').addClass('input-has-error');

		// } else

		// {

		// 	$('#email').removeClass('input-has-error');

		// }



		// if ($('#direccion').val() == '' || $('#direccion').val() == null)

		// {

		// 	todo_bien = 1;

		// 	$('#direccion').addClass('input-has-error');

		// } else

		// {

		// 	$('#direccion').removeClass('input-has-error');

		// }



		// if ($('#cod_gerencia option:selected').val() == '-b')

		// {

		// 	todo_bien = 1;

		// 	$('#cod_gerencia').selectpicker('setStyle', 'btn-info', 'remove');

		// 	$('#cod_gerencia').selectpicker('setStyle', 'btn-danger');

		// } else

		// {

		// 	$('#cod_gerencia').selectpicker('setStyle', 'btn-danger', 'remove');

		// 	$('#cod_gerencia').selectpicker('setStyle', 'btn-info');

		// }



		if ($('#cod_cargo option:selected').val() == '-b')

		{

			todo_bien = 1;

			$('#cod_cargo').selectpicker('setStyle', 'btn-info', 'remove');

			$('#cod_cargo').selectpicker('setStyle', 'btn-danger');

		} else

		{

			$('#cod_cargo').selectpicker('setStyle', 'btn-danger', 'remove');

			$('#cod_cargo').selectpicker('setStyle', 'btn-info');

		}



		if ($('#cod_pais option:selected').val() == '-b')

		{

			todo_bien = 1;

			$('#cod_pais').selectpicker('setStyle', 'btn-info', 'remove');

			$('#cod_pais').selectpicker('setStyle', 'btn-danger');

		} else

		{

			$('#cod_pais').selectpicker('setStyle', 'btn-danger', 'remove');

			$('#cod_pais').selectpicker('setStyle', 'btn-info');

		}



		if ($('#cod_estado option:selected').val() == '-b')

		{

			todo_bien = 1;

			$('#cod_estado').selectpicker('setStyle', 'btn-info', 'remove');

			$('#cod_estado').selectpicker('setStyle', 'btn-danger');

		} else

		{

			$('#cod_estado').selectpicker('setStyle', 'btn-danger', 'remove');

			$('#cod_estado').selectpicker('setStyle', 'btn-info');

		}



		if ($('#cod_municipio option:selected').val() == '-b')

		{

			todo_bien = 1;

			$('#cod_municipio').selectpicker('setStyle', 'btn-info', 'remove');

			$('#cod_municipio').selectpicker('setStyle', 'btn-danger');

		} else

		{

			$('#cod_municipio').selectpicker('setStyle', 'btn-danger', 'remove');

			$('#cod_municipio').selectpicker('setStyle', 'btn-info');

		}





		if ($('#cod_jefe_inmediato option:selected').val() == '-b')

		{

			todo_bien = 1;

			$('#cod_jefe_inmediato').selectpicker('setStyle', 'btn-info', 'remove');

			$('#cod_jefe_inmediato').selectpicker('setStyle', 'btn-danger');

		} else

		{

			$('#cod_jefe_inmediato').selectpicker('setStyle', 'btn-danger', 'remove');

			$('#cod_jefe_inmediato').selectpicker('setStyle', 'btn-info');

		}



		if ($('#cod_info_empresa option:selected').val() == '-b') {

			todo_bien = 1;

			$('#cod_info_empresa').selectpicker('setStyle', 'btn-info', 'remove');

			$('#cod_info_empresa').selectpicker('setStyle', 'btn-danger');

		} else {

			$('#cod_info_empresa').selectpicker('setStyle', 'btn-danger', 'remove');

			$('#cod_info_empresa').selectpicker('setStyle', 'btn-info');

		}
		if ($('#cod_categoria_empleado option:selected').val() == '-b') {

			todo_bien = 1;

			$('#cod_categoria_empleado').selectpicker('setStyle', 'btn-info', 'remove');

			$('#cod_categoria_empleado').selectpicker('setStyle', 'btn-danger');

		} else {

			$('#cod_categoria_empleado').selectpicker('setStyle', 'btn-danger', 'remove');

			$('#cod_categoria_empleado').selectpicker('setStyle', 'btn-info');

		}

		if ($('#cod_granja option:selected').val() == '-b') {

			todo_bien = 1;

			$('#cod_granja').selectpicker('setStyle', 'btn-info', 'remove');

			$('#cod_granja').selectpicker('setStyle', 'btn-danger');

		} else {

			$('#cod_granja').selectpicker('setStyle', 'btn-danger', 'remove');

			$('#cod_granja').selectpicker('setStyle', 'btn-info');

		}
		if ($('#cod_tipo_usuario option:selected').val() == '-b') {

			todo_bien = 1;

			$('#cod_tipo_usuario').selectpicker('setStyle', 'btn-info', 'remove');

			$('#cod_tipo_usuario').selectpicker('setStyle', 'btn-danger');

		} else {

			$('#cod_tipo_usuario').selectpicker('setStyle', 'btn-danger', 'remove');

			$('#cod_tipo_usuario').selectpicker('setStyle', 'btn-info');

		}


		if ($('#input_pin').val() == '' || $('#input_pin').val() == null)

		{

			todo_bien = 1;

			$('#input_pin').addClass('input-has-error');

		} else

		{

			$('#input_pin').removeClass('input-has-error');

		}

		if ($('#input_qcpin').val() == '' || $('#input_qcpin').val() == null)

		{

			todo_bien = 1;

			$('#input_qcpin').addClass('input-has-error');

		} else

		{

			$('#input_qcpin').removeClass('input-has-error');

		}


		$('.selectpicker').selectpicker('refresh');

		flag_foto = 0;



		if (todo_bien == 0)

		{

			jQuery.ajaxSetup({
				async: false
			});

			var btn = $('#btn_crear_usuario');

			btn.button('loading');

			if (actualizar == 1) {

				grl_overlay_loading('');

			} else {

				grl_overlay_loading('');

			}

			jQuery.ajaxSetup({
				async: false
			});

			var nombre_1 = toTitleCase($('#nombre_1').val());

			var apellido_1 = toTitleCase($('#apellido_1').val());

			var nombre_2 = toTitleCase($('#nombre_2').val());

			var apellido_2 = toTitleCase($('#apellido_2').val());

			var identidad = $('#identidad').val();

			var telefono_2 = $('#telefono_2').val();

			var telefono_1 = $('#telefono_1').val();

			var email = $('#email').val();

			var direccion = $('#direccion').val();

			var cod_gerencia = $('#cod_gerencia option:selected').val();

			var cod_cargo = $('#cod_cargo option:selected').val();

			var cod_jefe_inmediato = $('#cod_jefe_inmediato option:selected').val();

			var cod_pais = $('#cod_pais option:selected').val();

			var cod_estado = $('#cod_estado option:selected').val();

			var cod_municipio = $('#cod_municipio option:selected').val();

			var cod_info_empresa = $('#cod_info_empresa').val();

			var cods_granjas = $('#cod_granja').val();

			var input_pin = $('#input_pin').val();

			var input_qcpin = $('#input_qcpin').val();

			var input_payrate = $('#input_payrate').val();

			var cod_categoria_empleado = $('#cod_categoria_empleado').val();

			var cod_tipo_usuario = $('#cod_tipo_usuario').val();

			var activo = $('#cod_activo').val();

			if (actualizar == 1) {

				nombre_foto = nombre_1 + '_' + apellido_1 + '_foto.' + ext;

				nombre_foto = nombre_foto.replace(/á/gi, "a");

				nombre_foto = nombre_foto.replace(/é/gi, "e");

				nombre_foto = nombre_foto.replace(/í/gi, "i");

				nombre_foto = nombre_foto.replace(/ó/gi, "o");

				nombre_foto = nombre_foto.replace(/ú/gi, "u");

				nombre_foto = nombre_foto.replace(/ñ/gi, "n");

				nombre_foto = nombre_foto.replace(/Á/gi, "A");

				nombre_foto = nombre_foto.replace(/É/gi, "E");

				nombre_foto = nombre_foto.replace(/Í/gi, "I");

				nombre_foto = nombre_foto.replace(/Ó/gi, "O");

				nombre_foto = nombre_foto.replace(/Ú/gi, "U");

				nombre_foto = nombre_foto.replace(/Ñ/gi, "N");

				if (files_foto.length)

				{

					ruta_archivo = '../../mod_admin_usuarios/fotos_usuarios/' + nombre_foto

				} else

				{

					ruta_archivo = '';

				}

			} else {

				if (files_foto.length)

				{

					nombre_foto = nombre_1 + '_' + apellido_1 + '_foto.' + ext;

					nombre_foto = nombre_foto.replace(/á/gi, "a");

					nombre_foto = nombre_foto.replace(/é/gi, "e");

					nombre_foto = nombre_foto.replace(/í/gi, "i");

					nombre_foto = nombre_foto.replace(/ó/gi, "o");

					nombre_foto = nombre_foto.replace(/ú/gi, "u");

					nombre_foto = nombre_foto.replace(/ñ/gi, "n");

					nombre_foto = nombre_foto.replace(/Á/gi, "A");

					nombre_foto = nombre_foto.replace(/É/gi, "E");

					nombre_foto = nombre_foto.replace(/Í/gi, "I");

					nombre_foto = nombre_foto.replace(/Ó/gi, "O");

					nombre_foto = nombre_foto.replace(/Ú/gi, "U");

					nombre_foto = nombre_foto.replace(/Ñ/gi, "N");

				} else

				{

					nombre_foto = 'libs/imgs/usuario.jpg';

					flag_foto = 1;

				}

			}



			if (actualizar == 1) {

				nombre_foto = ruta_archivo;

			} else {

				if (flag_foto == 0) {

					var temporal_foto = '../../mod_admin_usuarios/fotos_usuarios/' + nombre_foto;

					nombre_foto = temporal_foto;

				}

			}


			flag_foto = 0;

			flag_identidad = 0;

			$.ajax({

				type: 'POST',

				url: 'mod_admin_usuarios/funciones/usu_crear_nuevo_usuario.php',

				data: ({

					x1: actualizar,

					x2: nombre_1,

					x3: nombre_2,

					x4: apellido_1,

					x5: apellido_2,

					x6: telefono_1,

					x7: telefono_2,

					x8: email,

					x9: direccion,

					x10: cod_gerencia == "-b" ? null : cod_gerencia,

					x11: cod_cargo,

					x12: cod_jefe_inmediato,

					x13: nombre_foto,

					x14: activo,

					x15: <?php echo $cod_usuario; ?>,

					x16: cod_pais,

					x17: cod_estado,

					x18: cod_municipio,

					x19: identidad,

					x20: cod_info_empresa,

					x21: cods_granjas,

					x22: input_pin,

					x23: input_qcpin,

					x24: cod_categoria_empleado,

					x25: cod_tipo_usuario,

          x26: input_payrate

				}),

				error: function(valueError)

				{
					console.log({
						valueError
					})

					if (actualizar == 1) {

						grl_mensaje('The user could not be updated. ', 'Please try later.', 'warning');

					} else {

						grl_mensaje('Unable to create user. ', ' Please try later.', 'warning');

					}

					btn.button('reset');

				},

				success: function(data) {
					console.log({
						data
					});
					error_registro = 0;
					var info = data.split("|");
					//si la identidad ya esta registrada, mostrar error que devuelve el ajax
					if (info[0] == 0) {

						flag_identidad = 1;

					} else if (info[0] == 3) {
						error_registro = 1;
						grl_mensaje('The PIN entered is already in use.', 'Please change it to another one', 'warning');

						$('#input_pin').addClass('input-has-error');

						btn.button('reset');
						return;
					} else if (info[0] == 4) {
						error_registro = 1;
						grl_mensaje('The QCPIN entered is already in use.', 'Please change it to another one', 'warning');

						$('#input_qcpin').addClass('input-has-error');

						btn.button('reset');
						return;
					} else {

						if (actualizar == 1) {

							grl_mensaje('Successfully updated user. ', '', 'success');

						} else {

							grl_mensaje('User created successfully. ', '', 'success');

						}

						//Código para subir el adjunto de fotografia

						if (files_foto.length)

						{

							var data = new FormData();

							$.each(files_foto, function(key, value) {
								data.append(key, value);
							});

							var ext = files_foto['0']['name'].match(/\.([^\.]+)$/)[1];

							$.ajax({

								url: 'mod_admin_usuarios/funciones/usu_subir_adjunto.php?files_foto&x1=' + nombre_foto,

								type: 'POST',

								data: data,

								cache: false,

								dataType: 'json',

								processData: false, // No procesa los archivos

								contentType: false, // Set content type to false as jQuery will tell the server its a query string request

								error: function() {

									//$container_upload_box.find('progress_bar').remove();

									grl_mensaje('Sorry. ', 'There was an inconvenience when uploading file, please try again later.', 'danger');

									flag_foto = 1;

								},

								success: function(data, textStatus, jqXHR)

								{

									if (typeof data.error === 'undefined') {

										// Todo bien, así que copia definitivamente los archivos al servidor.

										grl_mensaje('File added correctly. ', '', 'success');

									} else {

										grl_mensaje('Sorry. ', 'There was an inconvenience when uploading file, please try again later.', 'danger');

										flag_foto = 1;

									}

								}

							});

						}

					}

				}

			})



			if (flag_foto == 1) { //hubo un error al cargar la foto y se manda a actualizar al usuario con la fotografia default

				actualizar = 2;

				nombre_foto = 'libs/imgs/usuario.jpg';

				$.ajax({

					type: 'POST',

					url: 'mod_admin_usuarios/funciones/usu_crear_nuevo_usuario.php',

					data: ({

						x1: actualizar,

						x2: nombre_1,

						x3: nombre_2,

						x4: apellido_1,

						x5: apellido_2,

						x6: telefono_1,

						x7: telefono_2,

						x8: email,

						x9: direccion,

						x10: cod_gerencia,

						x11: cod_cargo,

						x12: cod_jefe_inmediato,

						x13: nombre_foto,

						x14: activo,

						x15: <?php echo $cod_usuario; ?>,

						x16: cod_pais,

						x17: cod_estado,

						x18: cod_municipio,

						x19: identidad,

						x20: cod_info_empresa

					}),

					error: function()

					{

						btn.button('reset');

					},

					success: function(data) {

					}

				})

			}



			if (flag_identidad == 0) {

				$('#modal_loading').modal('hide');

				$('#modal_loading').on('hidden.bs.modal', function() {
					btn.button('reset');

					if (error_registro == 0) {
						grl_obtener_cuerpo_menu(1, 'mod_admin_usuarios/ui/usu_listado_usuarios.php');
					}
				});

				jQuery.ajaxSetup({
					async: true
				});

			} else {

				$('#modal_loading').modal('hide');

				grl_mensaje('The entered identity is registered to another user ', 'Please check.', 'warning');

				jQuery.ajaxSetup({
					async: true
				});

				$('#identidad').addClass('input-has-error');

				btn.button('reset');

			}

		} else

		{

			grl_mensaje('You must fill in the marked fields.', 'Please check.', 'warning');

		}

	});



	$("#email").on('blur', function(event) {

    if($(this).val() == ''){
      return false;
    }

		if (!grl_validarEmail($(this).val())) {

			$(this).val('');

			grl_mensaje('E-mail not valid ', 'Please check.', 'danger');

		}

	});

	//Botón para poder ir/regresar al listado en la barra de acciones

	$("#btn_ir_al_listado").on('click', function() {

		grl_obtener_cuerpo_menu(1, 'mod_admin_usuarios/ui/usu_listado_usuarios.php');

	});

	//Botón para poder desbloquear usuario

	$("#btn_desbloquear").on('click', function() {

		var x1 = '<?PHP echo $INFO_USU[0]['cod_usuario']; ?>';
		$.ajax({

			type: 'POST',

			url: 'mod_admin_usuarios/funciones/usu_desbloquear_usuario.php',

			data: {

				x1: x1

			},

			error: function() {

				grl_mensaje('Error unlocking user', 'please try again', 'danger');

			},

			success: function(data) {

				grl_mensaje('Successfully unlocked user', '', 'success');

			}

		}); //Ajax

	});
</script>



<style type="text/css">
	.row-with-attachment {

		background-color: rgb(42, 160, 148);

		border-color: rgb(23, 121, 111);

		background-color: rgb(255, 255, 255) !important;

		border-color: rgb(35, 35, 35) !important;

	}

	.container-upload-box {

		width: 100%;
		display: table;

		border-collapse: separate;

		background-color: rgb(255, 255, 255);

	}

	.btn-select-file {

		display: table-cell;

		width: 100%;

		height: 30px;

		border-top-right-radius: 0px;

		border-bottom-right-radius: 0px;

		background: rgb(37, 105, 162);

		border: 1px solid rgb(24, 90, 146);

		color: rgb(255, 255, 255);

	}

	.btn-select-file.ready-to-upload {

		background: rgb(140, 197, 245);

		border: 1px solid rgb(24, 90, 146);

		color: rgb(14, 68, 113);

	}

	.btn-select-file.with-attachment {

		background: rgb(20, 128, 140);

		border: 1px solid rgb(24, 90, 146);

		border: 1px solid rgb(31, 149, 162);

		color: rgb(255, 255, 255);

	}

	.btn-select-file.disabled {

		background: rgb(212, 212, 212);

		border: 1px solid rgb(161, 161, 161);

		color: rgb(100, 100, 100);

		cursor: not-allowed;

	}

	.btn-delete-file {

		display: table-cell;

		width: 30px;

		background: rgb(37, 105, 162);

		color: rgb(24, 90, 146);

		border: 1px solid rgb(24, 90, 146);

		border-left: none;

		text-align: center;

		font-size: 16px;

		padding-left: 4px;

		border-top-right-radius: 4px;

		border-bottom-right-radius: 4px;

		vertical-align: middle;

	}

	.btn-delete-file:hover,

	.btn-delete-file:focus {

		background: rgb(37, 105, 162);

		color: rgb(24, 90, 146);

		border-color: rgb(24, 90, 146);

		cursor: not-allowed;

	}

	.btn-delete-file.ready-to-upload {

		background: rgb(37, 105, 162);

		border-color: rgb(24, 90, 146);

		color: rgb(255, 255, 255);

	}

	.btn-delete-file:hover.ready-to-upload,

	.btn-delete-file:focus.ready-to-upload {

		background-color: rgb(21, 79, 129);

		cursor: pointer;

	}



	.btn-delete-file.with-attachment {

		background: rgb(20, 128, 140);

		border-color: rgb(24, 90, 146);

		border-color: rgb(31, 149, 162);

		color: rgb(255, 255, 255);

	}

	.btn-delete-file:hover.with-attachment,

	.btn-delete-file:focus.with-attachment {

		background-color: rgb(16, 101, 110);

		cursor: pointer;

	}



	.btn-delete-file.disabled {

		background: rgb(212, 212, 212);

		color: rgb(100, 100, 100);

		border-color: rgb(161, 161, 161);

	}

	.btn-delete-file:hover.disabled,

	.btn-delete-file:focus.disabled {

		background: rgb(212, 212, 212);

		color: rgb(100, 100, 100);

		border-color: rgb(161, 161, 161);

		cursor: not-allowed;

	}



	.btn-view-file {

		display: table-cell;

		width: 30px;

		border-left: none;

		text-align: center;

		font-size: 16px;

		padding-left: 4px;

		border-top-right-radius: 4px;

		border-bottom-right-radius: 4px;

		vertical-align: middle;

		background: rgb(20, 128, 140);

		border-color: rgb(24, 90, 146);

		border-color: rgb(31, 149, 162);

		color: rgb(255, 255, 255);

	}

	.btn-view-file:hover,

	.btn-view-file:focus {

		background-color: rgb(16, 101, 110);

		color: rgb(255, 255, 255);

		cursor: pointer;

	}



	.input-file-hidden {

		visibility: hidden;

		position: absolute;

		left: -99999px;

	}

	/*--------------------------------------------------------------------*/

	.btn-fileupload-incrustated {

		width: 80%;

	}

	.btn-img {

		position: absolute;

		bottom: 15px;

		width: 10%;

		height: 30px;

		right: 15px;

		z-index: 1;

		padding: 5px;

	}

	@media (max-width: 991px) {

		.btn-img {

			bottom: 0px;

		}

	}

	.btn-upload {

		position: absolute;

		bottom: 15px;

		width: 10%;

		height: 30px;

		right: 45px;

		z-index: 1;

		padding: 5px;

	}

	@media (max-width: 991px) {

		.btn-upload {

			bottom: 0px;

		}

	}

	.container-upload-box {

		width: 100%;
		display: table;

		border-collapse: separate;

	}

	.btn-select-file {

		display: table-cell;

		width: 100%;

		height: 30px;

		border-top-right-radius: 0px;

		border-bottom-right-radius: 0px;

		background: rgb(37, 105, 162);

		border: 1px solid rgb(24, 90, 146);

		color: rgb(255, 255, 255);

	}

	.btn-select-file.ready-to-upload {

		background: rgb(140, 197, 245);

		border: 1px solid rgb(24, 90, 146);

		color: rgb(14, 68, 113);

	}

	.btn-select-file.with-attachment {

		background: rgb(20, 128, 140);

		border: 1px solid rgb(24, 90, 146);

		border: 1px solid rgb(31, 149, 162);

		color: rgb(255, 255, 255);

	}

	.btn-select-file.disabled {

		background: rgb(212, 212, 212);

		border: 1px solid rgb(161, 161, 161);

		color: rgb(100, 100, 100);

		cursor: not-allowed;

	}

	.btn-upload-file {

		display: table-cell;

		width: 30px;

		background: rgb(37, 105, 162);

		color: rgb(24, 90, 146);

		border: 1px solid rgb(24, 90, 146);

		border-left: none;

		text-align: center;

		font-size: 16px;

		padding-left: 4px;

		border-top-right-radius: 4px;

		border-bottom-right-radius: 4px;

		vertical-align: middle;

	}

	.btn-upload-file:hover,

	.btn-upload-file:focus {

		background: rgb(37, 105, 162);

		color: rgb(24, 90, 146);

		border-color: rgb(24, 90, 146);

		cursor: not-allowed;

	}

	.btn-upload-file.ready-to-upload {

		background: rgb(37, 105, 162);

		border-color: rgb(24, 90, 146);

		color: rgb(255, 255, 255);

	}

	.btn-upload-file:hover.ready-to-upload,

	.btn-upload-file:focus.ready-to-upload {

		background-color: rgb(21, 79, 129);

		cursor: pointer;

	}



	.btn-upload-file.with-attachment {

		background: rgb(20, 128, 140);

		border-color: rgb(24, 90, 146);

		border-color: rgb(31, 149, 162);

		color: rgb(255, 255, 255);

	}

	.btn-upload-file:hover.with-attachment,

	.btn-upload-file:focus.with-attachment {

		background-color: rgb(16, 101, 110);

		cursor: pointer;

	}



	.btn-upload-file.disabled {

		background: rgb(212, 212, 212);

		color: rgb(100, 100, 100);

		border-color: rgb(161, 161, 161);

	}

	.btn-upload-file:hover.disabled,

	.btn-upload-file:focus.disabled {

		background: rgb(212, 212, 212);

		color: rgb(100, 100, 100);

		border-color: rgb(161, 161, 161);

		cursor: not-allowed;

	}



	.btn-view-file {

		display: table-cell;

		width: 30px;

		border-left: none;

		text-align: center;

		font-size: 16px;

		padding-left: 4px;

		vertical-align: middle;

		background: rgb(20, 128, 140);

		border-color: rgb(24, 90, 146);

		border-color: rgb(31, 149, 162);

		color: rgb(255, 255, 255);

	}

	.btn-view-file:hover,

	.btn-view-file:focus {

		background-color: rgb(16, 101, 110);

		color: rgb(255, 255, 255);

		cursor: pointer;

	}



	.btn-view-file-delete {

		display: table-cell;

		width: 100%;

		height: 30px;

		border-top-right-radius: 4px;

		border-bottom-right-radius: 4px;

		border: 1px solid rgb(24, 90, 146);

		color: rgb(255, 255, 255);

		background: #d9534f;

		border-color: #ac2925;

	}



	.btn-view-file-delete:hover,

	.btn-view-file-delete:focus {

		background-color: #c9302c;

		color: #fff;

		cursor: pointer;

	}



	.input-file-hidden {

		visibility: hidden;

		position: absolute;

		left: -99999px;

	}

	#tabla_datos_beca_activa {

		font-size: 12px;

	}

	.full-width {

		width: 100%;

	}

	#container_fotografia input.file {

		visibility: hidden;

		display: none;

	}

	.bubble-timeline {

		display: inline-block;

		background-color: rgb(77, 178, 208);

		border-radius: 50%;

		line-height: 2em;

		max-width: 220px;

		/*border: 2px solid rgb(255, 255, 255);*/

		padding: 2px;

		margin-bottom: 15px;

		margin-top: 15px;

	}

	.icon-timeline-container {

		background-color: rgb(77, 178, 208);

		border: 3px solid rgb(255, 255, 255);

		border-radius: 50%;

		display: inline-block;

	}

	.img-timeline {

		width: 100%;

		/*background-color: rgb(77, 178, 208);

		border: 3px solid rgb(255, 255, 255);*/

		border-radius: 50%;

		display: inline-block;

	}
</style>

<!DOCTYPE html>

<html lang="es">

<head>

	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

	<title>Registro de Usuario</title>

</head>

<body>

	<div id="overlay_loading"></div>

	<div id="message_box"></div>

	<div class="row">

		<div class="panel panel-default">

			<div class="panel-body">

				<?PHP

				if ($actualizar == 1) {

				?>

					<div class="page-header">

						<h1 class="translate" data-traducir_english="User Update" data-traducir_spanish="Actualización de Usuario">Actualización de Usuario</h1>

					</div>

					<div class="row">

						<div class="col-md-3" style="text-align: center">

							<div id="div_fotografia">

								<p class="bubble-timeline" style="background-color: '.$p_color2.' !important">

									<span class="icon-timeline-container" style="background-color: '.$p_color2.' !important">

										<img src="<?PHP echo $INFO_USU[0]['fotografia']; ?>" onerror="this.src='../../libs/imgs/usuario.jpg';" class="img-circle" width="200" height="200">

									</span>

								</p>

							</div>

						</div>

						<div class="col-md-9">

							<div class="row">

								<div class="col-md-3">

									<div class="form-group input-group-sm">

										<label for="nombre_1" class="translate" data-traducir_english="First name" data-traducir_spanish="Primer nombre">Primer nombre</label>

										<input type="text" class="form-control input requerido nombre" id="nombre_1" placeholder="Primer Nombre">

									</div>

								</div>

								<div class="col-md-3">

									<div class="form-group input-group-sm">

										<label for="nombre_2" class="translate" data-traducir_english="Middle name" data-traducir_spanish="Segundo nombre">Segundo nombre</label>

										<input type="text" class="form-control input nombre" id="nombre_2" placeholder="Segundo Nombre">

									</div>

								</div>

								<div class="col-md-3">

									<div class="form-group input-group-sm">

										<label for="apellido_1" class="translate" data-traducir_english="Surname" data-traducir_spanish="Primer apellido">Primer apellido</label>

										<input type="text" class="form-control input requerido nombre" id="apellido_1" placeholder="Primer Apellido">

									</div>

								</div>

								<div class="col-md-3">

									<div class="form-group input-group-sm">

										<label for="apellido_2" class="translate" data-traducir_english="Second surname" data-traducir_spanish="Segundo apellido">Segundo apellido</label>

										<input type="text" class="form-control input nombre" id="apellido_2" placeholder="Segundo Apellido">

									</div>

								</div>

							</div>

							<div class="row">

								<div class="col-md-3">

									<div class="form-group input-group-sm">

										<label for="identidad" class="translate" data-traducir_english="ID" data-traducir_spanish="Identidad">Identidad</label>

										<input type="text" class="form-control input requerido identidad" id="identidad" placeholder="Identidad">

									</div>

								</div>

                <div class="col-md-3">

									<div class="form-group input-group-sm">

										<label for="email" class="translate" data-traducir_english="Email" data-traducir_spanish="Correo">Correo</label>

										<input type="text" class="form-control input" id="email" placeholder="Correo">

									</div>

								</div>

								<div class="col-md-3">

									<div class="form-group input-group-sm">

										<label for="telefono_1" class="translate" data-traducir_english="Phone Number" data-traducir_spanish="Teléfono">Teléfono</label>

										<input type="text" class="form-control input telefono" id="telefono_1" placeholder="Telefono">

									</div>

								</div>

								<div class="col-md-3">

									<div class="form-group input-group-sm">

										<label for="telefono_2" class="translate" data-traducir_english="Cell Number" data-traducir_spanish="Celular">Celular</label>

										<input type="text" class="form-control input telefono" id="telefono_2" placeholder="Celular">

									</div>

								</div>

							</div>

							<div class="row">
                <div class="col-md-3">

                  <div class="form-group input-group-sm">

                  <label for="input_payrate" class="translate" data-traducir_english="Pay Rate" data-traducir_spanish="Ratio de pago">Pay Rate</label>

                  <input type="text" class="form-control input" id="input_payrate" placeholder="0.00">

                  </div>

                </div>

								<div class="col-md-9">

									<div class="form-group input-group-sm">

										<label for="direccion" class="translate" data-traducir_english="Address" data-traducir_spanish="Dirección">Dirección</label>

										<input type="text" class="form-control input " id="direccion" placeholder="Direccion">

									</div>

								</div>

							</div>

						</div>

					</div>



					<!-- NUEVOS REGISTROS DE PAYROLL -->

					<div class="row">
						<div class="col-md-3">
							<div class="form-group input-group-sm">
								<label for="input_pin" class="translate" data-traducir_english="PIN" data-traducir_spanish="PIN">PIN</label>
								<input type="text" class="form-control input requerido" id="input_pin" placeholder="PIN">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group input-group-sm">
								<label for="input_qcpin" class="translate" data-traducir_english="QCPIN" data-traducir_spanish="QCPIN">QCPIN</label>
								<input type="text" class="form-control input requerido" id="input_qcpin" placeholder="QCPIN">
							</div>
						</div>





						<div class="col-md-3">

							<label for="cod_estado" class="translate" data-traducir_english="State" data-traducir_spanish="Estado">Estado</label>

							<div class="form-group show-tick">

								<select class="selectpicker show-menu-arrow requerido" id="cod_estado" name="cod_estado" data-live-search="true" title="Seleccione">

								</select>

							</div>

						</div>

						<div class="col-md-3">
							<label for="cod_granja" class="translate" data-traducir_english="Farm" data-traducir_spanish="Finca">Finca</label>
							<div class="form-group show-tick">
								<select class="selectpicker show-menu-arrow requerido" id="cod_granja" name="cod_granja" data-actions-box="true" multiple="multiple" data-live-search="true"></select>
							</div>
						</div>
					</div>

					<!-- =========================== -->

					<div class="row">
						<div class="col-md-3">
							<label for="cod_categoria_empleado" class="translate" data-traducir_english="Employee Category" data-traducir_spanish="Categoría de empleado">Employee Category</label>
							<div class="form-group show-tick">
								<select class="selectpicker show-menu-arrow requerido" id="cod_categoria_empleado" name="cod_categoria_empleado" data-live-search="true">
									<option value="0">Standard</option>
									<option value="2">H2A</option>
									<option value="1">Veteran</option>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<label for="cod_jefe_inmediato" class="translate" data-traducir_english="Immediate Boss" data-traducir_spanish="Jefe Inmediato">Jefe Inmediato</label>
							<div class="form-group show-tick">
								<select class="selectpicker show-menu-arrow requerido" id="cod_jefe_inmediato" name="cod_jefe_inmediato" data-live-search="true"></select>
							</div>
						</div>
						<div class="col-md-3">
							<label for="cod_gerencia" class="translate" data-traducir_english="Company Name" data-traducir_spanish="Gerencia">Gerencia</label>

							<div class="form-group show-tick">

								<select class="selectpicker show-menu-arrow requerido" id="cod_gerencia" name="cod_gerencia" data-live-search="true"></select>

							</div>

						</div>

						<div class="col-md-3">

							<label for="cod_cargo" class="translate" data-traducir_english="Position" data-traducir_spanish="Cargo">Cargo</label>

							<div class="form-group show-tick">

								<select class="selectpicker show-menu-arrow requerido" id="cod_cargo" name="cod_cargo" data-live-search="true"></select>

							</div>

						</div>

						<!-- <div class="col-md-3">

							<label for="cod_pais" class="translate" data-traducir_english="Country" data-traducir_spanish="País">País</label>

							<div class="form-group show-tick">

								<select class="selectpicker show-menu-arrow requerido" id="cod_pais" name="cod_pais" data-live-search="true" title="Seleccione">

								</select>

							</div>

						</div> -->


						<!-- <div class="col-md-3">

							<label for="cod_municipio" class="translate" data-traducir_english="County" data-traducir_spanish="Condado">Condado</label>

							<div class="form-group show-tick">

								<select class="selectpicker show-menu-arrow requerido" id="cod_municipio" name="cod_municipio" data-live-search="true" title="Seleccione">

								</select>

							</div>

						</div> -->

					</div>



					<div class="row">

						<div class="col-md-3">

							<div class="form-group input-group-sm">

								<label for="cod_tipo_usuario" class="translate" data-traducir_english="User type (For App)" data-traducir_spanish="Tipo de usuario">User type (For App)</label>

								<select class="selectpicker show-menu-arrow requerido" title="Seleccione" id="cod_tipo_usuario" name="cod_tipo_usuario">

								</select>

							</div>

						</div>
						<div class="col-md-3">

							<div class="form-group input-group-sm">

								<label for="cod_info_empresa" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</label>

								<select class="selectpicker show-menu-arrow requerido" multiple="multiple" title="Seleccione" id="cod_info_empresa" name="cod_info_empresa">

								</select>

							</div>

						</div>

						<div class="col-md-3">

							<label for="cod_activo" class="translate" data-traducir_english="Active" data-traducir_spanish="Activo">Activo</label>

							<div class="form-group show-tick">

								<select class="selectpicker show-menu-arrow requerido" id="cod_activo" name="cod_activo">

									<option value="0">No</option>

									<option value="1">Si</option>

								</select>

							</div>

						</div>

						<div id="container_upload_box_foto">

							<div class="col-md-3">

								<label class="translate" data-traducir_english="Photography" data-traducir_spanish="Fotografía">Fotografía</label>

								<div class="container-upload-box">

									<button data-disabled="true" data-id="foto" class="smooth-transition btn-select-file">

										<i class="icon-file fa fa-file"> </i>

										<span id="msj-btn-file-select-foto" class="msj-btn-file translate" data-traducir_english="Upload file" data-traducir_spanish="Seleccione archivo"> Seleccione archivo</span>

									</button>

									<input type="file" title="Seleccione Archivo..." class="input-file-hidden" id="archivo-foto" accept=".jpg, .jpeg,.png,.bmp,.gif,.tiff">

								</div>

							</div>

						</div>

					</div>

				<?PHP

				} else {

				?>

					<div class="page-header">

						<h1 class="translate" data-traducir_english="User Register" data-traducir_spanish="Registro de Usuario">Registro de Usuario</h1>

					</div>

					<div class="row">

						<div class="col-md-3">

							<div class="form-group input-group-sm">

								<label for="nombre_1" class="translate" data-traducir_english="Fist name" data-traducir_spanish="Primer nombre">Primer nombre</label>

								<input type="text" class="form-control input requerido nombre" id="nombre_1" placeholder="Primer Nombre">

							</div>

						</div>

						<div class="col-md-3">

							<div class="form-group input-group-sm">

								<label for="nombre_2" class="translate" data-traducir_english="Middle name" data-traducir_spanish="Segundo nombre">Segundo nombre</label>

								<input type="text" class="form-control input nombre" id="nombre_2" placeholder="Segundo Nombre">

							</div>

						</div>

						<div class="col-md-3">

							<div class="form-group input-group-sm">

								<label for="apellido_1" class="translate" data-traducir_english="Surname" data-traducir_spanish="Primer apellido">Primer apellido</label>

								<input type="text" class="form-control input requerido nombre" id="apellido_1" placeholder="Primer Apellido">

							</div>

						</div>

						<div class="col-md-3">

							<div class="form-group input-group-sm">

								<label for="apellido_2" class="translate" data-traducir_english="Second surname" data-traducir_spanish="Segundo apellido">Segundo apellido</label>

								<input type="text" class="form-control input nombre" id="apellido_2" placeholder="Segundo Apellido">

							</div>

						</div>

					</div>

					<div class="row">

						<div class="col-md-3">

							<div class="form-group input-group-sm">

								<label for="identidad" class="translate" data-traducir_english="ID" data-traducir_spanish="Identidad">Identidad</label>

								<input type="text" class="form-control input requerido identidad" id="identidad" placeholder="Identidad">

							</div>

						</div>

            <div class="col-md-3">

							<div class="form-group input-group-sm">

								<label for="email" class="translate" data-traducir_english="Email" data-traducir_spanish="Correo">Correo</label>

								<input type="text" class="form-control input" id="email" placeholder="Correo">

							</div>

						</div>

						<div class="col-md-3">

							<div class="form-group input-group-sm">

								<label for="telefono_1" class="translate" data-traducir_english="Phone Number" data-traducir_spanish="Teléfono">Teléfono</label>

								<input type="text" class="form-control input telefono" id="telefono_1" placeholder="Telefono">

							</div>

						</div>

						<div class="col-md-3">

							<div class="form-group input-group-sm">

								<label for="telefono_2" class="translate" data-traducir_english="Cell Number" data-traducir_spanish="Celular">Celular</label>

								<input type="text" class="form-control input requerido telefono" id="telefono_2" placeholder="Celular">

							</div>

						</div>

					</div>



					<!-- NUEVOS REGISTROS DE PAYROLL -->

					<div class="row">
						<div class="col-md-3">
							<div class="form-group input-group-sm">
								<label for="input_pin" class="translate" data-traducir_english="PIN" data-traducir_spanish="PIN">PIN</label>
								<input type="number" class="form-control input requerido" id="input_pin" placeholder="PIN">
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group input-group-sm">
								<label for="input_qcpin" class="translate" data-traducir_english="QCPIN" data-traducir_spanish="QCPIN">QCPIN</label>
								<input type="number" class="form-control input requerido" id="input_qcpin" placeholder="QCPIN">
							</div>
						</div>

            <div class="col-md-3">
              <div class="form-group input-group-sm">
                <label for="input_payrate" class="translate" data-traducir_english="Pay Rate" data-traducir_spanish="Ratio de pago">Pay Rate</label>
                <input type="number" class="form-control input" id="input_payrate" placeholder="0.00">
              </div>
            </div>

						<div class="col-md-3">
							<label for="cod_categoria_empleado" class="translate" data-traducir_english="Employee Category" data-traducir_spanish="Categoría de empleado">Employee Category</label>
							<div class="form-group show-tick">
								<select class="selectpicker show-menu-arrow requerido" id="cod_categoria_empleado" name="cod_categoria_empleado" data-live-search="true">
									<option value="0">Standard</option>
									<option value="2">H2A</option>
									<option value="1">Veteran</option>
								</select>
							</div>
						</div>


					</div>

					<!-- =========================== -->

					<div class="row">

						<div class="col-md-6">

							<div class="form-group input-group-sm">

								<label for="direccion" class="translate" data-traducir_english="Address" data-traducir_spanish="Dirección">Dirección</label>

								<input type="text" class="form-control input " id="direccion" placeholder="Direccion">

							</div>

						</div>

						<!-- <div class="col-md-2">

							<label class="translate" data-traducir_english="Country" data-traducir_spanish="País">País</label>

							<div class="form-group show-tick">

								<select class="selectpicker show-menu-arrow requerido" id="cod_pais" name="cod_pais" data-live-search="true" title="Seleccione">

								</select>

							</div>

						</div> -->

						<div class="col-md-3">

							<label class="translate" data-traducir_english="State" data-traducir_spanish="Estado">Estado</label>

							<div class="form-group show-tick">

								<select class="selectpicker show-menu-arrow requerido" id="cod_estado" name="cod_estado" data-live-search="true" title="Seleccione">

								</select>

							</div>

						</div>

						<div class="col-md-3">
							<label for="cod_granja" class="translate" data-traducir_english="Farm" data-traducir_spanish="Finca">Finca</label>
							<div class="form-group show-tick">
								<select class="selectpicker show-menu-arrow requerido" id="cod_granja" name="cod_granja" data-actions-box="true" multiple="multiple" data-live-search="true"></select>
							</div>
						</div>
						<!-- <div class="col-md-2">

							<label class="translate" data-traducir_english="County" data-traducir_spanish="Condado">Condado</label>

							<div class="form-group show-tick">

								<select class="selectpicker show-menu-arrow requerido" id="cod_municipio" name="cod_municipio" data-live-search="true" title="Seleccione">

								</select>

							</div>

						</div> -->

					</div>

					<div class="row">

						<div class="col-md-4">

							<label for="cod_gerencia" class="translate" data-traducir_english="Company Name" data-traducir_spanish="Gerencia">Gerencia</label>

							<div class="form-group show-tick">

								<select class="selectpicker show-menu-arrow requerido" id="cod_gerencia" name="cod_gerencia" data-live-search="true"></select>

							</div>

						</div>

						<div class="col-md-4">

							<label for="cod_cargo" class="translate" data-traducir_english="Position" data-traducir_spanish="Cargo">Cargo</label>

							<div class="form-group show-tick">

								<select class="selectpicker show-menu-arrow requerido" id="cod_cargo" name="cod_cargo" data-live-search="true"></select>

							</div>

						</div>

						<div class="col-md-4">

							<label for="cod_jefe_inmediato" class="translate" data-traducir_english="Immediate Boss" data-traducir_spanish="Jefe Inmediato">Jefe Inmediato</label>

							<div class="form-group show-tick">

								<select class="selectpicker show-menu-arrow requerido" id="cod_jefe_inmediato" name="cod_jefe_inmediato" data-live-search="true"></select>

							</div>

						</div>

					</div>

					<div class="row">

						<div class="col-md-3">

							<div class="form-group input-group-sm">

								<label for="cod_tipo_usuario" class="translate" data-traducir_english="User type (For App)" data-traducir_spanish="Tipo de usuario">User type (For App)</label>

								<select class="selectpicker show-menu-arrow requerido" title="Seleccione" id="cod_tipo_usuario" name="cod_tipo_usuario">

								</select>

							</div>

						</div>
						<div class="col-md-3">

							<div class="form-group input-group-sm">

								<label for="cod_info_empresa" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</label>

								<select class="selectpicker show-menu-arrow requerido" multiple="multiple" title="Seleccione" id="cod_info_empresa" name="cod_info_empresa">

								</select>

							</div>

						</div>

						<div class="col-md-3">

							<label for="cod_activo" class="translate" data-traducir_english="Active" data-traducir_spanish="Activo">Activo</label>

							<div class="form-group show-tick">

								<select class="selectpicker show-menu-arrow requerido" id="cod_activo" name="cod_activo">

									<option value="0">No</option>

									<option value="1">Si</option>

								</select>

							</div>

						</div>

						<div id="container_upload_box_foto">

							<div class="col-md-3">

								<label class="translate" data-traducir_english="Photography" data-traducir_spanish="Fotografía">Fotografía</label>

								<div class="container-upload-box">

									<button data-disabled="true" data-id="foto" class="smooth-transition btn-select-file">

										<i class="icon-file fa fa-file"> </i>

										<span id="msj-btn-file-select-foto" class="msj-btn-file translate" data-traducir_english="Upload file" data-traducir_spanish="Seleccione archivo"> Seleccione archivo</span>

									</button>

									<input type="file" title="Seleccione Archivo..." class="input-file-hidden" id="archivo-foto" accept=".jpg, .jpeg, .png,.bmp,.gif,.tiff">

								</div>

							</div>

						</div>

					</div>

				<?PHP

				}

				?>

			</div>

		</div>

	</div>

	<div class="panel-footer-actions text-right smooth-transition actions-container-is-closed" id="panel_footer_actions">

		<div class="close-actions-container">

			<button class="btn btn-xs btn-close-actions" id="btn_close_actions">

				<i class="fa fa-chevron-circle-down fa-lg"></i>

			</button>

		</div>

		<div class="row actions">

			<div class="col-xs-9 col-md-3 nopadding text-left smooth-transition" id="container_back_button">

				<button data-loading-text="Volviendo" class="btn btn-sm btn-primary btn-fullwidth main-actions smooth-transition" type="button" id="btn_ir_al_listado" name="btn_ir_al_listado">

					<i class="fa fa-arrow-left"> </i> <span id="btn_regresar_listado" class="translate" data-traducir_english="Back to list" data-traducir_spanish="Regresar al listado">Regresar al listado</span>

				</button>

			</div>



			<div class="col-xs-3 col-md-9 nopadding smooth-transition" id="div_acciones">

				<button class="btn btn-sm btn-primary btn-open-actions main-actions smooth-transition" type="button" id="btn_open_actions" name="btn_open_actions">

					<i class="fa fa-ellipsis-v"></i>

				</button>

				<button class="btn btn-sm btn-primary translate" data-traducir_english="Register" data-traducir_spanish="Registrar" type="button" id="btn_crear_usuario">Registrar</button>

				<?PHP

				if ($actualizar == 1) {

					if ($INFO_USU[0]['pass_pending'] == 3) {

				?>

						<button class="btn btn-sm btn-danger translate" data-traducir_english="Unlock User" data-traducir_spanish="Desbloquer Usuario" type="button" id="btn_desbloquear">Desbloquear Usuario</button>

				<?PHP

					}
				}

				?>

			</div>

		</div>

	</div> <!-- panel-footer -->

</body>
