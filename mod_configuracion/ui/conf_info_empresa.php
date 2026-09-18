<?php
/*
* 	Registro de información de la empresa y sus sucursales,
* 	@author 		Jairo Bonilla
* 	@date 			2015-07-28
*/
session_start();
if (!isset($_SESSION['cod_usuario'])) {
	header('Location: index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_configuracion.php");
/*INSTANCIAMIENTOS*/
$DB_CONF = new db_configuracion();

//$GRANJAS = $DB_CONF->conf_listado_granjas_activas();

$cod_granjas_usuario = str_replace(['[', '"', ']'], '', $_SESSION['cod_info_empresa']);

$GRANJAS = $DB_CONF->conf_listado_granjas_activas_por_granjas($cod_granjas_usuario);

$cod_granja = $_POST['cod_granja'];
if (!isset($_POST['cod_granja'])) {
	$cod_granja = 0;
}

$GRANJA = $DB_CONF->conf_obtener_info_granja($cod_granja);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>Nueva capacitación</title>
</head>
<script type="text/javascript">
	jQuery.ajaxSetup({
		async: false
	});
	grl_overlay_loading('');

	var fecha_hoy = new Date(<?php echo time() * 1000; ?>);
	var hoy = new Date((fecha_hoy.getMonth() + 1) + "/" + fecha_hoy.getDate() + "/" + fecha_hoy.getFullYear());
	var hoy_18_anios_atras = new Date((fecha_hoy.getMonth() + 1) + "/" + fecha_hoy.getDate() + "/" + (fecha_hoy.getFullYear() - 18));
	var hoy_default = new Date(fecha_hoy.getFullYear() + "-" + (fecha_hoy.getMonth() + 1) + "-" + fecha_hoy.getDate());
	var array_file_list = [];
	array_file_list.push({
		id: 0,
		file: '',
		file_ext: ''
	});
	var $container_upload_box = $("#container_upload_box");
	var files_foto = '';
	var nombre_foto = '';
	var ext_foto = '';

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

	function prepare_upload2(event) {
		var $container_upload_box = $(this).parent();
		files_foto = event.target.files;
		var cancel_button_is_clicked = files_foto[0];
		if (cancel_button_is_clicked == undefined) {
			constructor_file_input($(this).parent());
		} else {
			var id = $container_upload_box.find('.btn-select-file').data('id');
			var filesize = files_foto[0].size / 1024 / 1024;
			if (filesize > 10) {
				grl_mensaje('Tamaño de archivo no permitido. ', 'Solo se permiten archivos menores de 10 MB. ', 'warning');
				cambio_adjunto = false;
				constructor_file_input($container_upload_box);
			} else {
				ext_foto = $(this).val().match(/\.([^\.]+)$/)[1];
				ext_foto = ext_foto.toLowerCase();
				update_array_file_list(id, files_foto, ext_foto);
				switch (ext_foto) {
					case 'jpg':
					case 'jpeg':
					case 'bmp':
					case 'png':
					case 'tif':
					case 'tiff':
					case 'svg':
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

	function update_list_item_interface($li, nombre_archivo) {
		$li.unbind('click');
		if (nombre_archivo != '') {
			$li.addClass('row-with-attachment');
			$li.find("span.state-icon").addClass('fa-paperclip').removeClass('fa-check-square-o');
			$li.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file with-attachment').data('disabled', false);
			$li.find('i.icon-file').addClass('hide');
			$li.find('span.msj-btn-file').text(nombre_archivo);
			$li.find(".btn-upload-file").addClass('hide').data('disabled', true);
			$li.find(".container-upload-box").append('<a tabindex="0" target="_blank" href="/mod_usuarios/foto_usuarios/' + nombre_archivo + '" class="smooth-transition btn-view-file">' +
				'<i class="fa fa-eye"></i>' +
				'</a>');
		}
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

	// Evento al seleccionar un archivo
	$('#archivo-foto').on('change', prepare_upload2);

	$(document).ready(function() {
		codigo_capacitacion = 0;
		$('.selectpicker').selectpicker({
			dropupAuto: 'true',
			container: 'body',
			size: '5',
			width: '100%',
			style: 'btn-sm btn-info'
		});
		//Habilita los selects para mobile
		if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
			$('.selectpicker').selectpicker('mobile');
		}
		//$('html, body').animate({ scrollTop: 0 }, 0);
		//Contructor listboxs
		/*cap_constructor_tipos_capacitaciones();
		cap_constructor_capacitaciones();
		cap_constructor_lugares_capacitaciones();*/
		usu_constructor_gerencias();
		/*--------------------------------------------------------------------------
		Validación de las fechas de inicio y final del curso
		--------------------------------------------------------------------------*/
		var fecha_hoy = new Date(<?php echo time() * 1000; ?>);
		var hoy = (fecha_hoy.getMonth() + 1) + "/" + fecha_hoy.getDate() + "/" + fecha_hoy.getFullYear();
		var hoy_18_anios_atras = (fecha_hoy.getMonth() + 1) + "/" + fecha_hoy.getDate() + "/" + (fecha_hoy.getFullYear() - 18);
		var hoy_default = fecha_hoy.getFullYear() + "-" + (fecha_hoy.getMonth() + 1) + "-" + fecha_hoy.getDate();
		$('.date').datetimepicker({
			//disabledHours: true,
			locale: 'es',
			maxDate: hoy,
			//keepOpen: true,
			format: 'MM-DD-YYYY',
			//defaultDate: hoy_18_anios_atras,
			//direction: 'auto',
			icons: {
				time: "fa fa-clock-o",
				date: "fa fa-calendar",
				up: "fa fa-arrow-up",
				down: "fa fa-arrow-down",
				previous: 'fa fa-arrow-left',
				next: 'fa fa-arrow-right',
			}
		});
		/*
		 *	Ejecuta el evento onClick en toda la fila enfocada
		 */
		var table = document.getElementById("dev-table");
		var rows = table.getElementsByTagName("tr");
		for (i = 1; i < rows.length; i++) {
			var currentRow = table.rows[i];
			if (currentRow.getElementsByTagName("td").length > 1) {
				var createClickHandler =
					function(row) {
						return function() {
							var cell = row.getElementsByTagName("td")[0];
							var cod_granja = cell.innerHTML;
							conf_vista_granja(cod_granja);
						};
					};
				currentRow.onclick = createClickHandler(currentRow);
			}
		}

		/*
		 * Función que realiza la "busqueda" dentro de la tabla con información
		 */
		(function() {
			'use strict';
			var $ = jQuery;
			$.fn.extend({
				filterTable: function() {
					return this.each(function() {
						$(this).on('keyup', function(e) {
							$('.filterTable_no_results').remove();
							var $this = $(this),
								search = $this.val().toLowerCase(),
								target = $this.attr('data-filters'),
								$target = $(target),
								$rows = $target.find('tbody tr');
							if (search == '') {
								$rows.show();
							} else {
								$rows.each(function() {
									var $this = $(this);
									$this.text().toLowerCase().indexOf(search) === -1 ? $this.hide() : $this.show();
								})
								if ($target.find('tbody tr:visible').size() === 0) {
									var col_count = $target.find('tr').first().find('td').size();
									var no_results = $('<tr class="filterTable_no_results"><td colspan="4">No hay resultados.</td></tr>')
									$target.find('tbody').append(no_results);
								}
							}
						});
					});
				}
			});
			$('[data-action="filter"]').filterTable();
		})(jQuery);

		$(function() {
			// attach table filter plugin to inputs
			$('[data-action="filter"]').filterTable();

			$('.busqueda_contenedor').on('click', '.panel_cabecera span.filter', function(e) {
				var $this = $(this),
					$panel = $this.parents('.panel');

				$panel.find('.panel_cuerpo').slideToggle();
				if ($this.css('display') != 'none') {
					$panel.find('.panel_cuerpo input').focus();
				}
			});
			$('[data-toggle="tooltip"]').tooltip();
		});
		//Máscaras
		//$('#lugar_curso').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z-¡!¿?,.() 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/, optional: false}}});
		$('.letras').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {
			translation: {
				'S': {
					pattern: /[A-Za-z-¡!¿?,.() 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/,
					optional: false
				}
			}
		});
		$('.telefono').mask('(999) 999-9999');

		$('#descripcion_empresa').attr('maxlength', 1000);

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
		/*----------------------------------------------------------------------------------
    								Validando listboxs
	    ----------------------------------------------------------------------------------*/
		$('.selectpicker.requerido').change(function(event) {
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
		$('.input.requerido').keyup(function(event) {
			if ($(this).val().trim() != "") {
				$(this).parent('div').removeClass('has-error');
			} else {
				$(this).parent('div').addClass('has-error');
			}
		});
		<?php
		if (count($GRANJA)) {
		?>
			$('#cod_gerencia').selectpicker('val', "<?php echo utf8_encode($GRANJA[0]['cod_gerencia']); ?>");
			$('#nombre_empresa').val("<?php echo utf8_encode($GRANJA[0]['nombre_empresa']); ?>");
			$('#lema_empresa').val("<?php echo utf8_encode($GRANJA[0]['lema_empresa']); ?>");
			$('#direccion_linea_1').val("<?php echo utf8_encode($GRANJA[0]['direccion_linea_1']); ?>");
			$('#direccion_linea_2').val("<?php echo utf8_encode($GRANJA[0]['direccion_linea_2']); ?>");
			$('#telefono_empresa').val("<?php echo utf8_encode($GRANJA[0]['telefono_empresa']); ?>");
			$('#correo_empresa').val("<?php echo utf8_encode($GRANJA[0]['correo_empresa']); ?>");
			$('#descripcion_empresa').val("<?php echo utf8_encode($GRANJA[0]['descripcion_empresa']); ?>");
			$('#img_usuario').attr('src', "../../mod_configuracion/adjuntos/<?php echo utf8_encode($GRANJA[0]['logo_empresa']); ?>");
			$('.selectpicker').selectpicker('refresh');
		<?php
		}
		?>
		codigo_granja = <?php echo $cod_granja; ?>;
		grl_traducir_interfaz(($('#checkbox_translate').attr('checked') ? 1 : 0));
		$('#modal_loading').modal('hide');
		jQuery.ajaxSetup({
			async: true
		});
	});

	$("#correo_empresa").blur(function() {
		if (!grl_validarEmail($(this).val())) {
			$(this).val('');
			grl_mensaje('Correo electrónico no valido', 'favor verificar', 'danger');
		}
	});

	$('#btn_nueva_granja').click(function(event) {
		/* Act on the event */
		var error = 0;
		$(".input.requerido").map(function() {
			if (!$(this).val()) {
				error = 1;
				$(this).addClass('input-has-error campo-vacio campo-vacio-modal');
				return false;
			} else {
				$(this).removeClass('input-has-error campo-vacio campo-vacio-modal');
			}
		});
		$(".selectpicker.requerido").map(function() {
			if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '') {
				$(this).selectpicker('setStyle', 'btn-info', 'remove');
				$(this).selectpicker('setStyle', 'btn-danger');
				error = 1;
			} else {
				$(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');
				$(this).selectpicker('setStyle', 'btn-info');
				$(this).removeClass('campo-vacio');
			}
			$(this).selectpicker('refresh');
		});
		if (error == 0) {
			jQuery.ajaxSetup({
				async: false
			});
			grl_overlay_loading('');
			var nombre_foto = conf_guardar_granja(codigo_granja, ext_foto);
			if (files_foto.length) {
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
				var data = new FormData();
				$.each(files_foto, function(key, value) {
					data.append(key, value);
				});
				var ext = files_foto['0']['name'].match(/\.([^\.]+)$/)[1];
				$.ajax({
					url: 'mod_configuracion/funciones/conf_subir_adjunto.php?files_foto&x1=' + nombre_foto,
					type: 'POST',
					data: data,
					cache: false,
					dataType: 'json',
					processData: false, // No procesa los archivos
					contentType: false, // Set content type to false as jQuery will tell the server its a query string request
					error: function() {
						//$container_upload_box.find('progress_bar').remove();
						grl_mensaje('Lo sentimos. ', 'Ha habido un inconveniente al subir archivo, favor intentarlo más tarde.', 'danger');
						flag_foto = 1;
					},
					success: function(data, textStatus, jqXHR) {
						if (typeof data.error === 'undefined') {
							// Todo bien, así que copia definitivamente los archivos al servidor.
							grl_mensaje('Archivo agregado correctamente. ', '', 'success');
						} else {
							grl_mensaje('Lo sentimos. ', 'Ha habido un inconveniente al subir archivo, favor intentarlo más tarde.', 'danger');
							flag_foto = 1;
						}
					}
				});
			}
			$('#modal_loading').modal('hide');
			$('#modal_loading').on('hidden.bs.modal', function(e) {
				conf_vista_granja(codigo_granja);
			});
			jQuery.ajaxSetup({
				async: true
			});
		} else {
			grl_mensaje('Debe llenar todos los campos marcados', 'favor verificar', 'warning');
		}
	});
</script>

<body>
	<div id="overlay_loading"></div>
	<div id="message_box"></div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="panel-header">
					<h1 class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</h1>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12" align="center">
					<div id="div_fotografia_file">
						<p class="bubble-timeline">
							<span class="icon-timeline-container">
								<img src="../../libs/imgs/Logo.png" id="img_usuario" onerror="this.src='../../libs/imgs/Logo.png';" class="img-circle" width="200" height="200">
							</span>
						</p>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group input-group-sm" id="div_tipo_curso">
							<label class="translate" data-traducir_english="Company Name" data-traducir_spanish="Gerencia" for="cod_gerencia">Gerencia</label>
							<select class="selectpicker show-menu-arrow requerido" title="Select" id="cod_gerencia" name="cod_gerencia">
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group input-group-sm" id="div_nombre_capacitacion">
							<label class="translate" data-traducir_english="Name" data-traducir_spanish="Nombre" for="nombre_empresa">Nombre</label>
							<input type="text" class="form-control letras input requerido" id="nombre_empresa" name="nombre_empresa">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm" id="div_nombre_capacitacion">
							<label class="translate" data-traducir_english="Phone No." data-traducir_spanish="Teléfono" for="telefono_empresa">Teléfono</label>
							<input type="text" class="form-control telefono input requerido" id="telefono_empresa" name="telefono_empresa">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group input-group-sm" id="div_nombre_capacitacion">
							<label class="translate" data-traducir_english="Email" data-traducir_spanish="Correo" for="correo_empresa">Correo</label>
							<input type="text" class="form-control input requerido" id="correo_empresa" name="correo_empresa">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group input-group-sm" id="div_nombre_capacitacion">
							<label class="translate" data-traducir_english="Motto" data-traducir_spanish="Lema" for="lema_empresa">Lema</label>
							<input type="text" class="form-control letras input requerido" id="lema_empresa" name="lema_empresa">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group input-group-sm" id="div_nombre_capacitacion">
							<label class="translate" data-traducir_english="Fax No." data-traducir_spanish="Fax" for="fax_empresa">Fax</label>
							<input type="text" class="form-control telefono input" id="fax_empresa" name="fax_empresa">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group input-group-sm" id="div_nombre_capacitacion">
							<label class="translate" data-traducir_english="Address 1" data-traducir_spanish="Dirección 1" for="direccion_linea_1">Dirección 1</label>
							<input type="text" class="form-control letras input requerido" id="direccion_linea_1" name="direccion_linea_1">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group input-group-sm" id="div_nombre_capacitacion">
							<label class="translate" data-traducir_english="Address 2" data-traducir_spanish="Dirección 2" for="direccion_linea_2">Dirección 2</label>
							<input type="text" class="form-control letras input" id="direccion_linea_2" name="direccion_linea_2">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12" align="center">
						<div class="form-group input-group-sm" id="div_descripcion_empresa">
							<label class="translate" data-traducir_english="Description" data-traducir_spanish="Descripción" for="descripcion_empresa">Descripción</label>
							<textarea class="form-control" id="descripcion_empresa" maxlength="600" align="left" style="height:100px; width:100%; resize: none;"></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<label class="translate" data-traducir_english="Logo" data-traducir_spanish="Logo">Logo</label>
						<div class="container-upload-box" id="div_fotografia">
							<button data-disabled="true" data-id="foto" class="smooth-transition btn-select-file">
								<i class="icon-file fa fa-camera fa-lg"> </i>
								<span id="msj-btn-file-select-foto" class="msj-btn-file translate" data-traducir_english="Upload Logo" data-traducir_spanish="Seleccione un Logo"> Seleccione un logo</span>
							</button>
							<input type="file" title="Seleccione Archivo..." class="input-file-hidden" id="archivo-foto" accept=".jpg,.png,.bmp,.gif,.tiff,.svg">
							<!--<a data-disabled="true" data-id="foto" class="smooth-transition btn-upload-file" tabindex="0">
                                    <i class="fa fa-cloud-upload"> </i>
                            </a>-->
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel-footer" align="right">
			<button class="btn btn-sm btn-primary translate" type="button" id="btn_nueva_granja" data-traducir_english="Save" data-traducir_spanish="Guardar">Guardar</button>
		</div>
	</div>
	<div class="busqueda_contenedor">
		<div class="row" style="min-width:100px;">
			<div class="col-sm-12">
				<div class="panel panel-primary" style="overflow:auto;">
					<div class="panel-heading panel_cabecera">
						<h3 class="panel-title translate" data-traducir_english="Greenhouse" data-traducir_spanish="Listado de invernaderos">Listado de invernaderos</h3>
						<div class="pull-right">
							<span class="clickable filter" data-toggle="tooltip" title="Realizar busqueda" data-container="body">
								<i class="fa fa-search"></i>
							</span>
						</div>
					</div>
					<div class="panel-body panel_cuerpo input-group-sm">
						<input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Busqueda" />
					</div>
					<div style="overflow-x:auto;">
						<table class="table table-condensed display" id="dev-table">
							<thead>
								<tr class="active info">
									<th width="1%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
									<th width="20%" class="translate" data-traducir_english="Company Name" data-traducir_spanish="Gerencia">Gerencia</th>
									<th width="20%" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</th>
									<th width="30%" class="translate" data-traducir_english="Motto" data-traducir_spanish="Lema">Lema</th>
									<th width="10%" class="translate" data-traducir_english="Address" data-traducir_spanish="Dirección">Dirección</th>
									<th width="10%" class="translate" data-traducir_english="Phone No." data-traducir_spanish="Teléfono">Teléfono</th>
									<th width="10%" class="translate" data-traducir_english="Email" data-traducir_spanish="Correo">Correo</th>
								</tr>
							</thead>
							<tbody id="cuerpo_tabla">
								<!-- <tr>
	                        		<td>1</td>
	                        		<td>B&W</td>
	                        		<td>Florida Watercress</td>
	                        		<td>Family Owned and Operated Since 1870</td>
	                        		<td>7798 County Road #512 Fellsmere, FL 32948</td>
	                        		<td>(772) 571-0800</td>
	                        		<td>bwfarm@watercress.com</td>
	                        	</tr> -->
								<?php
								if (count($GRANJAS)) {
									$correlativo = 1;
									foreach ($GRANJAS as $granja) {
								?>
										<tr>
											<td class="hide"><?php echo utf8_encode($granja['cod_info_empresa']); ?></td>
											<td><?php echo $correlativo; ?></td>
											<td><?php echo utf8_encode($granja['gerencia']); ?></td>
											<td><?php echo utf8_encode($granja['nombre_empresa']); ?></td>
											<td><?php echo utf8_encode($granja['lema_empresa']); ?></td>
											<td><?php echo utf8_encode($granja['direccion_linea_1'] . '<br>' . $granja['direccion_linea_2']); ?></td>
											<td><?php echo utf8_encode($granja['telefono_empresa']); ?></td>
											<td><?php echo utf8_encode($granja['correo_empresa']); ?></td>
										</tr>
								<?php
										$correlativo++;
									}
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>