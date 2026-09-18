<?PHP
/*
 * Ingreso de noticias a la base de datos.
 * @author      Oscar Raudales
 * @date        2014-09-24
/*CONEXION CON BASE DE DATOS*/
session_start();
include_once("../../libs/db_classes/mysql_conn.php");
include_once("../../libs/db_classes/db_general.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Contáctenos</title>
<script type="text/javascript" src="../../libs/bootstrap/js/bootstrap-wysiwyg.js"></script>
<script type="text/javascript" src="../../libs/jQuery/jquery.hotkeys.js"></script>
</head>
<script>
$(document).ready(function(e) {
	$('.selectpicker').selectpicker({
		dropupAuto: 'true',
		container: 'body',
		size: 'auto',
		width: '100%',
		style: 'btn-sm btn-default',
        tickIcon: 'fa fa-check'
	});
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
    	$('.selectpicker').selectpicker('mobile');
    }
	//Editor.
	$('#editor').wysiwyg();
	//Contructor listboxs.
	grl_constructor_tipo_noticias();
	conf_constructor_listado_granjas();
	var fecha_hoy   = new Date();
	var hoy 		= (fecha_hoy.getMonth()+1) + "/" + fecha_hoy.getDate() + "/" + fecha_hoy.getFullYear();
	var hoy_default = (fecha_hoy.getFullYear() + "-" + fecha_hoy.getMonth()+1) + "-" + fecha_hoy.getDate();
	//Constructor del calendario para fechas
	$('#div_fecha_inicial').datetimepicker({
		/*pickTime: false,
		language: 'es',
		//defaultDate: '1994-12-31',
		icons: {
					time: "fa fa-clock-o",
					date: "fa fa-calendar",
					up: "fa fa-arrow-up",
					down: "fa fa-arrow-down"
				}*/
		locale: 'es',
		format: 'YYYY-MM-DD',
		ignoreReadonly: true,
		icons: {
					time: "fa fa-clock-o",
					date: "fa fa-calendar",
					up: "fa fa-arrow-up",
					down: "fa fa-arrow-down",
					previous: 'fa fa-arrow-left',
		            next: 'fa fa-arrow-right',
				}
	}).on('dp.hide',function (e){
		$('#div_fecha_final').data("DateTimePicker").minDate(e.date);
	});
	$('#div_fecha_final').datetimepicker({
		/*pickTime: false,
		language: 'es',
		//defaultDate: '1994-12-31',
		icons: {
					time: "fa fa-clock-o",
					date: "fa fa-calendar",
					up: "fa fa-arrow-up",
					down: "fa fa-arrow-down"
				}*/
		locale: 'es',
		format: 'YYYY-MM-DD',
		ignoreReadonly: true,
		icons: {
					time: "fa fa-clock-o",
					date: "fa fa-calendar",
					up: "fa fa-arrow-up",
					down: "fa fa-arrow-down",
					previous: 'fa fa-arrow-left',
		            next: 'fa fa-arrow-right',
				}
	}).on('dp.hide',function (e){
		$('#div_fecha_inicial').data("DateTimePicker").maxDate(e.date);
	});
// Función que valida la extención del archivo a subir, sino esta en la lista del case borra el  boton y lo crea de nuevo
		estilo_file_input();
	/*Validaciones en inputs*/
	$( "#titulo" ).keyup(function() {
		if ($( "#titulo" ).val() != '') $( "#div_titulo" ).removeClass('has-error');
	});
	$( "#editor" ).keyup(function() {
		$("#editor").css({"border-color": "#CCC",
						 "border-weight":"1px",
						 "border-style":"solid"});
	});

	$( ".selectpicker" ).change(function() {
	 	var objeto = $(this);
		if (objeto.val() == '-b'){
			objeto.selectpicker('setStyle', 'btn-danger');
		} else {
			objeto.selectpicker('setStyle', 'btn-danger', 'remove');
		}
		objeto.selectpicker('refresh');
	});
}); // fin document ready

	$( '#enviar' ).on('click',function() {
		if ($( '#adjunto' ).val()==''){
			enviar_noticia();
      	 	console.log('sin adjunto:' );
		}
	});// fin (click)
function estilo_file_input(){
	$('input[type=file]').bootstrapFileInput();
	$('.file-inputs').bootstrapFileInput();

}
function recrear_file_input(){
	$( '#div_adjunto' ).empty();
	$( '#div_adjunto' ).append(	'<label for="adjunto_noticia">Adjuntar archivos</label><br/>'+
                                '<input type="file"  class="btn btn-sm " data-filename-placement="inside" title="Seleccione..."id="adjunto" name="adjunto">');
	$( '#adjunto').bootstrapFileInput();
}
function enviar_noticia(){
	var btn 				= $("#enviar");
	btn.button('loading');
	var tipo 				= '';
	var cod_usuario		 	= <?PHP echo $_SESSION['cod_usuario']; ?>;
	var titulo    			= $("#titulo").val();
	var noticia   			= $("#editor").html();
	var fecha_inicial       = $("#fecha_inicial").val();
	var fecha_final   		= $("#fecha_final").val();
	var cod_tipo_noticia	= $("#cod_tipo_noticia").val();

	var error = 0;
	if ($("#adjunto").val()!='' )var adjunto  =$("#adjunto").val();
	else  var adjunto ='';
	//Validación de los Selects
	if (cod_tipo_noticia == '-b'){
		$("#cod_tipo_noticia").selectpicker('setStyle', 'btn-info', 'remove');
		$("#cod_tipo_noticia").selectpicker('setStyle', 'btn-danger');
		if (error != 1) error = 1;
	}
	$('.selectpicker').selectpicker('refresh');
	//Validación de los inputs
	if (titulo == ''){
		$( '#div_titulo' ).addClass('has-error');
		if (error != 1) error = 1;
	}
	if (fecha_inicial == ''){
		$( '#div_fecha_inicial' ).addClass('has-error');
		if (error != 1) error = 1;
	}
	if (fecha_final == ''){
		$( '#div_fecha_final' ).addClass('has-error');
		if (error != 1) error = 1;
	}
	if ($.trim(noticia) == ''){
		$("#editor").css({"border-color": "#A94442",
						 "border-weight":"1px",
						 "border-style":"solid"});
		if (error != 1) error = 1;
	}
	if (error != 1) {
		$.ajax({
			type: 'POST',
			url: 'mod_noticias/funciones/not_subir_noticia.php',
			data: {x1: cod_usuario,
				   x2: titulo,
				   x3: adjunto,
				   x4: noticia,
				   x5: fecha_inicial,
				   x6: fecha_final,
				   x7: cod_tipo_noticia,
				   x8: $('#cod_info_empresa').val().toString()
				   },
			error: function(){
				grl_mensaje('Error al enviar el contenido', 'favor intentar nuevamente', 'danger');
			   },
			success: function (data) {
				var info = data.split("|");
				info[0] == 1 ? tipo = 'danger' : tipo = 'success';
				grl_mensaje('', info[1], tipo);
				//Restablece los valores por defecto de la forma.
				//Contructor listboxs.
				grl_obtener_cuerpo_menu(6,'mod_noticias/ui/not_nueva_noticia.php');			}
		}); //Ajax*/
	} else {
		//grl_mensaje('Campos vacios', 'favor llenar los marcados', 'warning');
	}
	btn.button('reset');
}
/*
 *	Sube archivos al servidor por medio de ajax y PHP
 *
 */
$(function subir_archivo ()
{
			// Variable para almacenar el archivo
			var files ='';
			// Evento al seleccionar un archivo
			$('#div_adjunto').on('change','input[type=file]', prepareUpload );
			// Evento al hacer submit del formulario.
			$('form').on('submit', uploadFiles);
			// Toma los archivos y los asigna a la variable
				function prepareUpload(event)
				{
					files = event.target.files;
					//valida el tipo de archivo
					var ext = this.value.match(/\.([^\.]+)$/)[1];
					ext =ext.toLowerCase();
					switch(ext)
				    {
				        case 'jpg':{
				        	ext = 'Si';
				        }
						case 'jpeg':{
							ext = 'Si';
						}
				        case 'bmp':{
				        	ext = 'Si';
				        }
				        case 'png':{
				        	ext = 'Si';
				        }
				        case 'tif':{
				        	ext = 'Si';
				        }
				        default:{
				            ext1 = 'No';
						}
				    }
					if(ext == 'Si')
					{
						$('#div_adjunto > a').addClass('btn-success');
					}
					else
					{
						grl_mensaje('Tipo de archivo no permitido', 'Solo se permiten imágenes.', 'warning');
						recrear_file_input();
						$( '#div_adjunto > a' ).removeClass('btn-success');
					}
				//valida la dimensión de la imagen
				var _URL = window.URL || window.webkitURL;
				var image, file;
				var file = document.getElementById('adjunto');
				if ((file = this.files[0])) {
				image = new Image();
				image.onload = function() {
					if (this.width!=200 ||  this.height!=200)
					{
						grl_mensaje('La imagen sobrepasa el tamaño permitido', 'Tamaño máximo 200x200 pixeles.', 'warning');
						recrear_file_input();
					}
				};
				image.src = _URL.createObjectURL(file);
  			  }
				}
			// Evita el submit form normal y envía los archivos al servidor.
		function uploadFiles(event)
		{
			event.stopPropagation(); // Evita que sucedan acciones
			event.preventDefault(); // Evita que todas las acciones del formulario se realicen
			//Validación de los inputs
			if ($("#titulo").val() == ''){
				$( '#div_titulo' ).addClass('has-error');
				if (error != 1) error = 1;
			}
			if ($("#noticia").val() == ''){
				$("#div_noticia").addClass('has-error');
				if (error != 1) error = 1;
			}
			if ( $("#titulo").val()!='' && $("#noticia").val()!=''  ){
				if ( $( '#adjunto' ).val() !='') {
				// Crea un objeto  formdata y le agrega los archivos.
					var data = new FormData();
								$.each(files, function(key, value)
								{
									data.append(key, value);
								});
						$.ajax({
								url:  'mod_noticias/funciones/not_subir_adjunto.php?files&x1=',
								type: 'POST',
								data: data,
								cache: false,
								dataType: 'json',
								processData: false, // No procesa los archivos
								contentType: false, // Set content type to false as jQuery will tell the server its a query string request
								success: function(data, textStatus, jqXHR)
								{
								 if(typeof data.error === 'undefined')
								 {
									 // Todo bien, así que copia definitivamente los archivos al servidor.
									enviar_noticia();
//									 submitForm(event, data);
								 }
								 else
								 {
									 // Manejo de errores
									  	grl_mensaje('Error al enviar el archivo ', data.error, 'warning');
										grl_obtener_cuerpo_menu(6,'mod_noticias/ui/not_nueva_noticia.php');
								 }
								},
								error: function(jqXHR, textStatus, errorThrown)
								{
									 console.log('Noticia NO enviado en ajax1: '+errorThrown+' '+textStatus);
								}
						}); //fin ajax
				}//fin de if de adjunto.
			} //fin de if los otros.
				else
				grl_mensaje('Campos vacios', 'favor llenar los marcados', 'warning');

		}//fin de upload files
});//fin de subir_archivo ()
	function submitForm(event, data)
		{
			// Crea un objeto jQuery con el formulario
				$form = $(event.target);
			// Serialize the form data
			var formData = $form.serialize();
			$.each(data.files, function(key, value)
			{
				formData = formData + '&filenames[]=' + value;
			});
			$.ajax({
			url: 'mod_soporte/funciones/sop_subir_adjunto.php',
						type: 'POST',
						data: formData,
						cache: false,
						dataType: 'json',
						success: function(data, textStatus, jqXHR)
						{
							 if(typeof data.error === 'undefined')
						{
						 // Todo bien, así que se procesa el formulario.
							enviar_noticia();
						 }
						 else
						 {
							 // Manejo de errores
							 grl_mensaje('Error al enviar el archivo ', data.error, 'warning');

						 }
						},//fin success
						error: function(jqXHR, textStatus, errorThrown)
						{
							 // Manejo de errores
							 grl_mensaje('Error al enviar la noticia ', data.error, 'warning');
						},
							complete: function(){
							grl_obtener_cuerpo_menu(3,'mod_soporte/ui/sop_contactenos.php');
								}
		});//fin de ajax
	}//fin function submitForm
</script>
<style>
#noticia {
  padding: 5px 10px;
  font-size: 12px;
  line-height: 1.5;
  border-radius: 3px;
}

#editor {
	max-height: 250px;
	height: 250px;
	background-color: white;
	border-collapse: separate;
	border: 1px solid rgb(204, 204, 204);
	padding: 4px;
	box-sizing: content-box;
	-webkit-box-shadow: rgba(0, 0, 0, 0.0745098) 0px 1px 1px 0px inset;
	box-shadow: rgba(0, 0, 0, 0.0745098) 0px 1px 1px 0px inset;
	border-top-right-radius: 3px; border-bottom-right-radius: 3px;
	border-bottom-left-radius: 3px; border-top-left-radius: 3px;
	overflow: auto;
	outline: none;
}

div[data-role="editor-toolbar"] {
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

</style>
<body>
	<form enctype="multipart/form-data"  role="form" method="post" id="formulario" name="formulario">
    <div class="panel panel-default">
  	<div class="panel-body">
        <div class="page-header">
        	<h1 class="translate" data-traducir_english="News" data-traducir_spanish="Noticias">Noticias</h1>
        </div> <!-- fin page-header -->
        <div  class="center-block" >
        <div class="well col-md-12" style="background-color:transparent" >
				<div class="row">
                    <div class="col-md-6">
                          <div class="form-group input-group-sm " id="div_titulo" >
                            <label for="titulo" class="translate" data-traducir_english="News" data-traducir_spanish="Noticias">Título</label>
                            <input type="text" class="form-control letras" id="titulo" name="titulo" placeholder="Title">
                          </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group input-group-sm">
                            <label for="cod_info_empresa" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</label>
                            <select class="selectpicker show-menu-arrow requerido" multiple="" title="Select" id="cod_info_empresa" name="cod_info_empresa">
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group input-group-sm date" id="">
                        	<label for="fecha_inicial" class="translate" data-traducir_english="Initial date" data-traducir_spanish="Fecha inicial">Fecha inicial</label>
                        	<div class='input-group input-group-sm date' id='div_fecha_inicial'>
                        		<span class="input-group-addon"><span class="fa fa-calendar">
                       				</span>
                        		</span>
                        		<input type='text' class="form-control" id="fecha_inicial"/>
                        	</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group input-group-sm date" id="">
                        	<label for="fecha_final" class="translate" data-traducir_english="End date" data-traducir_spanish="Fecha final">Fecha final</label>
                        	<div class='input-group input-group-sm date' id='div_fecha_final'>
                        		<span class="input-group-addon"><span class="fa fa-calendar">
                       				</span>
                        		</span>
                        		<input type='text' class="form-control" id="fecha_final"/>
                        	</div>
                        </div>
                    </div>
                </div> <!-- fin row -->
                <div class="row">
                      <!--<div class="col-md-12" >
                          <div class="form-group " id="div_noticia"  >
                            <label for="noticia">Cuerpo</label>
                            <textarea class="form-control" rows="4" id="noticia" name="noticia" style="resize:none"/>
                          </div>
                      </div> -->
                      <div class="well" style="background:#FFF; border:hidden; padding-bottom:0px;">
                      <div class="btn-toolbar" data-role="editor-toolbar" data-target="#editor">
                          <div class="btn-group">
                            <a class="btn btn-default" data-edit="bold" title="Bold - Negritas (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
                            <a class="btn btn-default" data-edit="italic" title="Italic - Cursiva (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
                            <a class="btn btn-default" data-edit="strikethrough" title="Strike Through - Tachada"><i class="fa fa-strikethrough"></i></a>
                            <a class="btn btn-default" data-edit="underline" title="Underline - Subrayado (Ctrl/Cmd+U)"><i class="fa fa-underline"></i></a>
                          </div>
                          <div class="btn-group">
                            <a class="btn btn-default" data-edit="insertunorderedlist" title="Bullets - Viñetas"><i class="fa fa-list"></i></a>
                            <a class="btn btn-default" data-edit="insertorderedlist" title="Numeration - Numeración"><i class="fa fa-list-ol"></i></a>
                            <a class="btn btn-default" data-edit="outdent" title="Increase Indent - Aumentar sangría (Shift+Tab)"><i class="fa fa-indent"></i></a>
                            <a class="btn btn-default" data-edit="indent" title="Decrease Indent - Disminuir sangría"><i class="fa fa-outdent"></i></a>
                          </div>
                          <div class="btn-group">
                            <a class="btn btn-default" data-edit="justifyleft" title="Left - Alinear izquierda (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
                            <a class="btn btn-default" data-edit="justifycenter" title="Center - Centrar (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
                            <a class="btn btn-default" data-edit="justifyright" title="Right - Alinear derecha (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
                            <a class="btn btn-default" data-edit="justifyfull" title="Justify - Justificar (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
                          </div>
                          <div class="btn-group">
                            <a class="btn btn-default" data-edit="undo" title="Undo - Deshacer (Ctrl/Cmd+Z)"><i class="fas fa-undo"></i></a>
                            <a class="btn btn-default" data-edit="redo" title="Redo - Repetir (Ctrl/Cmd+Y)"><i class="fas fa-redo"></i></a>
                          </div>
                        </div>
                    <div id="editor">

                    </div>
                  </div>
                </div> <!-- fin row -->
                <div class="row">
                        <div class="col-md-8">
							<div class="form-group input-group-sm" id="div_adjunto">
                                <label for="adjunto_noticia" class="translate" data-traducir_english="Attachment" data-traducir_spanish="Adjuntar archivo">Adjuntar archivo</label><br/>
                                <input type="file"  class="btn btn-sm " data-filename-placement="inside" title="Seleccione..."id="adjunto" name="adjunto">
	                        </div>
                  		</div>
                      <div class="col-md-4">
                          <label class="translate" data-traducir_english="New's Type" data-traducir_spanish="Tipo de Noticia">Tipo de Noticia</label>
                          <div class="form-group">
                            <select class="selectpicker show-menu-arrow" id="cod_tipo_noticia" name="cod_tipo_noticia">
                            </select>
                          </div>
                      </div>
                </div> <!-- fin de row-->
        </div> <!-- fin well -->
        </div> <!-- fin de contenedor del well -->
	</div> <!-- fin panel-body -->
    <div class="panel-footer" align="right">
        <input id="enviar" name="enviar" class="btn btn-sm btn-primary" type="submit" data-loading-text="Enviando..." value="Enviar"/>
        </div>
    </div>
    </form>
</body>
</html>