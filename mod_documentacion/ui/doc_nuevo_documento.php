<?php
/*
 * Registro de un nuevo documento dentro del repositorio interno
 * @author      Dan Urquía
 * @date        2017-06-22
 */

 session_start();
 if(!isset($_SESSION['cod_usuario'])){
 	header('Location: ../index.php');
 }
 /*CONEXION CON BASE DE DATOS*/
 include_once("../../libs/db_classes/db_mysql_conn.php");
 include_once("../../libs/db_classes/db_documentacion.php");
 /*INSTANCIAMIENTOS*/
 $DB_DOCUMENTACION    = new db_documentacion();
 ?>

<script type="application/javascript">
//Variables globales
var array_file_list = [];
array_file_list.push({id:0,file:'',file_ext:''});
var $container_upload_box = $("#container_upload_box");
var files_PDF = '';
var nombre_PDF = '';
var ext = '';
selectedDocumento = '';
selectedcodDocumento = 0;
flag_update = 0;

jQuery.ajaxSetup({async:false});
$('#modal_loading').modal('hide');
grl_overlay_loading('');

$(document).ready(function(){
  //Habilita los selects para mobile
  if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
      $('.selectpicker').selectpicker('mobile');
  }
  init_button_bar();
  /*Constructores de selectpickers*/
  usu_constructor_gerencias('../../', 1);
  doc_constructor_tipo_documentos();
  //Habilitación de listboxs
  $('.selectpicker').selectpicker({
    dropupAuto: 'true',
    container: 'body',
    size: '10',
    width: '100%',
    style: 'btn-sm btn-info'
  });
  $('.selectpicker').selectpicker('refresh');
  //Validaciones
  //Listboxs
  $( ".selectpicker.requerido" ).change(function() {
    var objeto = $(this);
    if (objeto.val() == null || objeto.val() == '-b' || objeto.val() == ''){
        objeto.selectpicker('setStyle', 'btn-info', 'remove');
        objeto.selectpicker('setStyle', 'btn-danger');
    } else {
        objeto.selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');
        objeto.selectpicker('setStyle', 'btn-info');
        objeto.removeClass('campo-vacio');
    }
    objeto.selectpicker('refresh');
  });
  //inputs
  $(".input.requerido, .input.requerido-modal").keyup(function(event) {
      if( $(this).val().trim() != "" ){
          $(this).removeClass('input-has-error campo-vacio campo-vacio-modal');
      } else {
          $(this).addClass('input-has-error');
      }
  });

  //Adjuntos
  /* Clic botón seleccionar archivo */
  $(".btn-select-file").on('click',function (e){
      e.stopPropagation();
      $(this).parent().find('input[type=file]').click();
      return false;
  });
  /* Clic botón subir archivo */
  $('.btn-upload-file').on('click', function (e){
    e.stopPropagation();
    if( $(this).data('disabled') == false){
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
  $('input[type=file]').on('click',function (e){
    e.stopPropagation();
  });
  // Evento al seleccionar un archivo
    $('#archivo_PDF').on('change', prepare_upload );

    $('#modal_loading').modal('hide');
    jQuery.ajaxSetup({async:true});
  }); //Fin document ready

  //evento clic del boton guardar
	$('#btn_crear_registro').click(function(){
    flag_validar_inputs   = grl_validar_inputs('titulo_documento|descripcion|palabras_claves');
    flag_validar_listboxs = grl_validar_listboxs('cod_gerencia|cod_tipo_documento');
    //if(files_PDF.length) {
    if (flag_validar_inputs != 0 || flag_validar_listboxs != 0){
      grl_mensaje('Empty fields, please verify. - ','Campos vacios, favor verificar.','danger');
    } else {
      //Guardara la información en pantalla
      jQuery.ajaxSetup({async:false});
			var btn 	= $('#btn_crear_registro');
			btn.button('Guardando');
      grl_overlay_loading('Guardando información');
      //Obtener información en Variables
      var cod_gerencia 		   = $('#cod_gerencia option:selected').val();
			var cod_tipo_documento = $('#cod_tipo_documento option:selected').val();
      var titulo_documento   = $('#titulo_documento').val();
      var descripcion 			 = $('#descripcion').val();
      var palabras_claves 	 = $('#palabras_claves').val();
      //Obtener el nombre del archivo a subir
      if (ext != '') {
        nombre_PDF = cod_gerencia + '_' + cod_tipo_documento + '_' + titulo_documento + '.' + ext;
        nombre_PDF = nombre_PDF.replace(/á/gi,"a");
        nombre_PDF = nombre_PDF.replace(/é/gi,"e");
        nombre_PDF = nombre_PDF.replace(/í/gi,"i");
        nombre_PDF = nombre_PDF.replace(/ó/gi,"o");
        nombre_PDF = nombre_PDF.replace(/ú/gi,"u");
        nombre_PDF = nombre_PDF.replace(/Á/gi,"A");
        nombre_PDF = nombre_PDF.replace(/É/gi,"E");
        nombre_PDF = nombre_PDF.replace(/Í/gi,"I");
        nombre_PDF = nombre_PDF.replace(/Ó/gi,"O");
        nombre_PDF = nombre_PDF.replace(/Ú/gi,"U");
      } else {
        var pdf_final = nombre_PDF.split("/");
        nombre_PDF = pdf_final[2];
      }
      //Se comienza el proceso de gaurdar información
      $.ajax({
        type: 'POST',
        url: 'mod_documentacion/funciones/doc_insertar_documento.php',
        data: ({
          x1: cod_gerencia,
          x2: cod_tipo_documento,
          x3: titulo_documento,
          x4: descripcion,
          x5: palabras_claves,
          x6: nombre_PDF,
          x7: flag_update,
          x8: selectedcodDocumento
        }),
        beforeSend: function(){
          //Verifica el flag y dependiendo de eso verifica que haya adjunto o no
          if(flag_update != 1) {
            if (!files_PDF.length) {
              grl_mensaje('There is no document attachment, please verify. - ', 'No hay adjunto del documento, favor verificar.', 'danger');
              $('#modal_loading').modal('hide');
              return false;
            }
          }
        },
        error: function() {
           grl_mensaje('Document could not be saved, please try again later. - ','Documento no pudo ser guardado, favor intentarlo más tarde.','danger');
            btn.button('reset');
        },
        success: function(data) {
          var info = data.split("|");
          //Una vez que el ingreso de la información en la base de datos
          //fue exitosa, se comienza la validación para subir archivo adjunto
          if(files_PDF.length) {
            var data = new FormData();
            $.each(files_PDF, function(key, value){data.append(key, value);});
            var ext = files_PDF['0']['name'].match(/\.([^\.]+)$/)[1];
            $.ajax({
              url:  'mod_documentacion/funciones/doc_subir_adjunto.php?files_foto&x1='+nombre_PDF,
              type: 'POST',
              data: data,
              cache: false,
              dataType: 'json',
              processData: false, // No procesa los archivos
              contentType: false, // Set content type a false como jQuery le dira al servidor que es una cadena de caracteres
              error: function() {
                  //grl_mensaje('The attachment could not be saved, please try again later in the record that was created. - ', 'No se ha podido guardar el adjunto , favor intentarlo despues en el registro que se creo.', 'danger');
                  flag_PDF = 1;
              },
              success: function(data, textStatus, jqXHR) {
                if(typeof data.error === 'undefined'){
                  //Notifica que el archivo ha sido subido correctamente al servidor.
                  grl_mensaje('File saved in repository. - ', 'Archivo guardado en repositorio.', 'success');
                } else {
                  //grl_mensaje('The attachment could not be saved, please try again later in the record that was created. - ', 'No se ha podido guardar el adjunto, favor intentarlo despues en el registro que se creo.', 'danger');
                  flag_PDF = 1;
                }
              }
            });
          } //File length final
          $('#modal_loading').modal('hide');
    			$('#modal_loading').on('hidden.bs.modal', function () {
          	grl_obtener_cuerpo_menu(1,'mod_documentacion/ui/doc_nuevo_documento.php');
          });
          grl_mensaje('Document saved successfully. - ','Documento guardado correctamente.','success');
        }
      }); //Fin del ajax
			jQuery.ajaxSetup({async:false});
      //Fin de guardar información en pantalla
    }
  //} else {
  //  grl_mensaje('No hay archivo para adjuntar. ','favor verificar.','danger');
  //};
  }); //Fin evento click

  /*
   * Prepara el archivo para ser subido al servidor
   */
  function prepare_upload(event){
    var $container_upload_box = $(this).parent();
    files_PDF = event.target.files;
    var cancel_button_is_clicked = files_PDF[0];
    if( cancel_button_is_clicked == undefined ){
            constructor_file_input($(this).parent());
    } else {
      var id = $container_upload_box.find('.btn-select-file').data('id');
      var filesize =  files_PDF[0].size/1024/1024;
      if(filesize > 50){
          grl_mensaje('File size not allowed. Only files smaller than 50 MB are allowed. - ', 'Tamaño de archivo no permitido. Solo se permiten archivos menores de 50 MB. ', 'warning');
          cambio_adjunto = false;
          constructor_file_input($container_upload_box);
      } else {
            ext = $(this).val().match(/\.([^\.]+)$/)[1];
            ext = ext.toLowerCase ();
            update_array_file_list(id, files_PDF, ext);
            switch(ext) {
                case 'jpg':
                case 'jpeg':
                case 'bmp':
                case 'png':
                case 'tif':
                case 'xls':
                case 'docx':
                case 'doc':
                case 'xlsx':
                case 'pptx':
                case 'ppt':
                case 'pdf':
                    $container_upload_box.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file ready-to-upload');
                    $container_upload_box.find('.btn-upload-file').removeClass().addClass('smooth-transition btn-upload-file ready-to-upload');
                    $container_upload_box.find('.btn-upload-file').data('disabled',true);
                    $container_upload_box.find('i.icon-file').removeClass().addClass('icon-file fa fa-check');
                    $container_upload_box.find('span.msj-btn-file').text('Archivo listo para subir');
                    $container_upload_box.find('.btn-view-file').remove();
                    $container_upload_box.find(".btn-view-file-delete").parent('div').remove();
                    break;
                default:{
                    grl_mensaje('File Type Not Allowed. - ', 'Tipo de archivo no permitido.', 'warning');
                    constructor_file_input($(this).parent());
                }
            }
       }
    }
  }

  /*
   * Construye el input que sera el contenedor del archivo
   */
	function constructor_file_input($container_upload_box){
	    $container_upload_box.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file');
	    $container_upload_box.find('.btn-delete-file').removeClass().addClass('smooth-transition btn-delete-file ready-to-upload');
	    $container_upload_box.find('.btn-delete-file').data('disabled',false);
	    $container_upload_box.find('i.icon-file').removeClass().addClass('icon-file fa fa-file');
	    $container_upload_box.find('span.msj-btn-file').text(' Seleccione archivo');
	    $container_upload_box.find('.btn-view-file').addClass('hide');
	    $container_upload_box.find("input[type=file]").replaceWith(
	    $container_upload_box.find("input[type=file]").val('').clone( true ));
	    $container_upload_box.find("input[type=file]").attr('title', 'Seleccione un Archivo');
	    files 		= '';
	    file_ext 	= '';
	    var id 		= $container_upload_box.find('.btn-select-file').data('id');
	    update_array_file_list(id, files, file_ext);
	}

  function update_list_item_interface_PDF($li,nombre_archivo){
    $li.unbind('click');
    if(nombre_archivo != ''){
        $li.addClass('row-with-attachment');
        $li.find("span.state-icon").addClass('fa-paperclip').removeClass('fa-check-square-o');
        $li.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file with-attachment').data('disabled',false);
        $li.find('i.icon-file').addClass('hide');
        $li.find('span.msj-btn-file').text('Subir otro documento');
        $li.find(".btn-upload-file").addClass('hide').data('disabled',true);
        $li.find(".container-upload-box").append('<a tabindex="0" target="_blank" href="../../'+nombre_archivo+'" class="smooth-transition btn-view-file">' +
                                                    '<i class="fa fa-eye"></i>' +
                                                '</a>');
    }
  }

  function update_list_item_interface2_PDF($li){
    $li.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file').data('disabled',false);
    $li.find('i.icon-file').removeClass().addClass('icon-file fa fa-file');
    $li.find('span.msj-btn-file').text('Seleccione archivo');
    $li.find(".btn-upload-file").removeClass().addClass('smooth-transition btn-upload-file').data('disabled',true);
    $li.find(".btn-view-file").remove();
    $li.find(".btn-view-file-delete").parent('div').remove();
    $li.find("input[type=file]").replaceWith(
        $li.find("input[type=file]").val('').clone( true )
    );
    $li.find("input[type=file]").attr('title', 'Seleccione un Archivo');
    $li.on('change','input[type=file]',prepare_upload);
  }

  /*
   * Actualiza el listado de archivos a subir
   */
	function update_array_file_list(id, file, file_ext) {
    for (var i in array_file_list) {
        if (array_file_list[i].id == id) {
          array_file_list[i].file   	= file;
          array_file_list[i].file_ext = file_ext;
          break;
        }
     }
	}

  //Busqueda de investigadores
  $('#busqueda').typeahead({
     source: function (query, process) {
         nombres = [];
         map = {};

         $.ajax({
             type: 'POST',
             url: 'mod_documentacion/funciones/doc_autocomplete_documentos.php',
             data: ({
                 x1: query
             }),
             dataType: 'json',
             success: function (data) {

                 if (data != null) {
                     $.each(data, function (i, item) {
                         map[item.gerencia + ' - ' + item.tipo_documento + ' - ' + item.titulo_documento] = item;
                         nombres.push(item.gerencia + ' - ' + item.tipo_documento + ' - ' + item.titulo_documento);
                         console.log('nombres: ' + nombres);
                     });
                 }
                 process(nombres);
             }
         });
     },
     matcher: function (item) {
         if (item.toLowerCase().indexOf(this.query.trim().toLowerCase()) != -1) {
             return true;
         }
     },
     sorter: function (items) {
         return items.sort();
     },
     highlighter: function (item) {
         var regex = new RegExp('(' + this.query + ')', 'gi');
         return item.replace(regex, "<strong>$1</strong>");
     },
     updater: function (item) {
         selectedcodDocumento    = map[item].cod_documento;
         selectedDocumento   = map[item].cod_documento;
         $('#busqueda').empty();
         $('#busqueda').val(selectedcodDocumento);
         console.log('selectedcodDocumento: ' + selectedcodDocumento);
         return item;
     },
     items: 10
  });/*Fin busqueda Typeahead*/

   //Evento onClick
  $( "#buscar" ).click(function() {
    if ($("#busqueda").val() != ''){
           jQuery.ajaxSetup({async:false});
           grl_overlay_loading('Cargando documento');
           jQuery.ajaxSetup({async:true});
           $('#modal_loading').on('shown.bs.modal', function () {
                   jQuery.ajaxSetup({async:false});
                   doc_obtener_documentos_autocomplete(selectedcodDocumento);
                   jQuery.ajaxSetup({async:true});
           });
    } else {
           grl_mensaje('The search could not be generated, please write what you want to search. - ', 'No se ha podido generar la busqueda, favor escribir lo que se desea buscar.', 'info');
    }
  });

  /*
   * Función que obtiene el documento en la busqueda por medio de su código.
   *
   * x1 int Código del documento
   */
  function doc_obtener_documentos_autocomplete(x1){
      $.ajax({
        type: 'POST',
        dataType: 'json',
        url: 'mod_documentacion/funciones/doc_obtener_documento_autocomplete.php',
        data: ({
          x1: x1
        }),
        error: function(){
          grl_mensaje('Error trying to get information, please try again later. - ', 'Error al tratar de obetener información, favor intentarlo más tarde.', 'danger');
          $("#modal_loading").modal("hide");
        },
        success: function(data) {
        jQuery.ajaxSetup({async:false});
        // Validando listboxs
        $( ".selectpicker" ).change(function() {
          var objeto = $(this);
          if (objeto.val() == '-b'){
              objeto.selectpicker('setStyle', 'btn-danger');
          } else {
              objeto.selectpicker('setStyle', 'btn-danger', 'remove');
          }
          objeto.selectpicker('refresh');
        });
            /*Verifica si el arreglo trae mas de un registro,
              en caso contrario lo llenara automaticamente*/
        if(data.length >= 1){
            //grl_reiniciar_campos_requeridos();

            flag_update = 1;
            selectedDocumento = data[0].cod_documento;
            $('#cod_gerencia').selectpicker('val',data[0].cod_gerencia);
            $('#cod_tipo_documento').selectpicker('val',data[0].cod_tipo_documento);
            $('#titulo_documento').val(data[0].titulo_documento);
            $('#descripcion').val(data[0].descripcion);
            $('#palabras_claves').val(data[0].palabras_claves);
            update_list_item_interface2_PDF($('#container_upload_box_PDF'));
            update_list_item_interface_PDF($('#container_upload_box_PDF'),data[0].archivo_PDF);
            nombre_PDF = data[0].archivo_PDF;
            $('.selectpicker').selectpicker('refresh');
            //Ocula el aviso de que esta cargando
            $("#modal_loading").modal("hide");
            jQuery.ajaxSetup({async:true});
          } else {
              //Ocula el aviso de que esta cargando
              $("#modal_loading").modal("hide");
              grl_mensaje('Document information not found. - ', 'No se encontro al información del documento. ', 'danger');
          }
        } //success
      }); //ajax
  }

  //Botón para poder ir/regresar al listado en la barra de acciones
  $("#btn_ir_al_listado").on('click',function (){
      grl_obtener_cuerpo_menu(1,'mod_documentacion/ui/doc_listado_documentos.php');
  });
</script>

<style type="text/css">
.row-with-attachment{
		background-color: rgb(42, 160, 148);
		border-color: rgb(23, 121, 111);
		background-color: rgb(255, 255, 255) !important;
		border-color: rgb(35, 35, 35) !important;
}
.container-upload-box{
		width: 100%;display: table;
		border-collapse: separate;
		background-color: rgb(255, 255, 255);
}
.btn-select-file{
		display: table-cell;
		width: 100%;
		height: 30px;
		border-top-right-radius: 0px;
		border-bottom-right-radius: 0px;
		background: rgb(37, 105, 162);
		border: 1px solid rgb(24, 90, 146);
		color: rgb(255,255,255);
}
.btn-select-file.ready-to-upload{
		background: rgb(140, 197, 245);
		border: 1px solid rgb(24, 90, 146);
		color: rgb(14, 68, 113);
}
.btn-select-file.with-attachment{
		background: rgb(20,128,140);
		border: 1px solid rgb(24,90,146);
		border: 1px solid rgb(31, 149, 162);
		color: rgb(255,255,255);
}
.btn-select-file.disabled{
		background: rgb(212, 212, 212);
		border: 1px solid rgb(161, 161, 161);
		color: rgb(100,100,100);
		cursor: not-allowed;
}
.btn-delete-file{
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
.btn-delete-file:focus{
		background: rgb(37, 105, 162);
		color: rgb(24, 90, 146);
		border-color: rgb(24, 90, 146);
		cursor: not-allowed;
}
.btn-delete-file.ready-to-upload{
		background: rgb(37, 105, 162);
		border-color: rgb(24, 90, 146);
		color: rgb(255,255,255);
}
.btn-delete-file:hover.ready-to-upload,
.btn-delete-file:focus.ready-to-upload{
		background-color: rgb(21, 79, 129);
		cursor: pointer;
}

.btn-delete-file.with-attachment{
		background: rgb(20,128,140);
		border-color: rgb(24,90,146);
		border-color: rgb(31, 149, 162);
		color: rgb(255,255,255);
}
.btn-delete-file:hover.with-attachment,
.btn-delete-file:focus.with-attachment{
		background-color: rgb(16, 101, 110);
		cursor: pointer;
}

.btn-delete-file.disabled{
		background: rgb(212, 212, 212);
		color: rgb(100,100,100);
		border-color: rgb(161, 161, 161);
}
.btn-delete-file:hover.disabled,
.btn-delete-file:focus.disabled{
		background: rgb(212, 212, 212);
		color: rgb(100,100,100);
		border-color: rgb(161, 161, 161);
		cursor: not-allowed;
}

.btn-view-file{
		display: table-cell;
		width: 30px;
		border-left: none;
		text-align: center;
		font-size: 16px;
		padding-left: 4px;
		border-top-right-radius: 4px;
		border-bottom-right-radius: 4px;
		vertical-align: middle;
		background: rgb(20,128,140);
		border-color: rgb(24,90,146);
		border-color: rgb(31, 149, 162);
		color: rgb(255,255,255);
}
.btn-view-file:hover,
.btn-view-file:focus{
		background-color: rgb(16, 101, 110);
		color: rgb(255,255,255);
		cursor: pointer;
}

.input-file-hidden{
		visibility: hidden;
		position: absolute;
		left: -99999px;
}
/*--------------------------------------------------------------------*/
.btn-fileupload-incrustated{
		width: 80%;
}
.btn-img{
		position: absolute;
		bottom: 15px;
		width: 10%;
		height: 30px;
		right: 15px;
		z-index: 1;
		padding: 5px;
}
@media (max-width: 991px){
		.btn-img{
				bottom: 0px;
		}
}
.btn-upload{
		position: absolute;
		bottom: 15px;
		width: 10%;
		height: 30px;
		right: 45px;
		z-index: 1;
		padding: 5px;
}
@media (max-width: 991px){
		.btn-upload{
				bottom: 0px;
		}
}
.container-upload-box{
		width: 100%;display: table;
		border-collapse: separate;
}
.btn-select-file{
		display: table-cell;
		width: 100%;
		height: 30px;
		border-top-right-radius: 0px;
		border-bottom-right-radius: 0px;
		background: rgb(37, 105, 162);
		border: 1px solid rgb(24, 90, 146);
		color: rgb(255,255,255);
}
.btn-select-file.ready-to-upload{
		background: rgb(140, 197, 245);
		border: 1px solid rgb(24, 90, 146);
		color: rgb(14, 68, 113);
}
.btn-select-file.with-attachment{
		background: rgb(20,128,140);
		border: 1px solid rgb(24,90,146);
		border: 1px solid rgb(31, 149, 162);
		color: rgb(255,255,255);
}
.btn-select-file.disabled{
		background: rgb(212, 212, 212);
		border: 1px solid rgb(161, 161, 161);
		color: rgb(100,100,100);
		cursor: not-allowed;
}
.btn-upload-file{
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
.btn-upload-file:focus{
		background: rgb(37, 105, 162);
		color: rgb(24, 90, 146);
		border-color: rgb(24, 90, 146);
		cursor: not-allowed;
}
.btn-upload-file.ready-to-upload{
		background: rgb(37, 105, 162);
		border-color: rgb(24, 90, 146);
		color: rgb(255,255,255);
}
.btn-upload-file:hover.ready-to-upload,
.btn-upload-file:focus.ready-to-upload{
		background-color: rgb(21, 79, 129);
		cursor: pointer;
}

.btn-upload-file.with-attachment{
		background: rgb(20,128,140);
		border-color: rgb(24,90,146);
		border-color: rgb(31, 149, 162);
		color: rgb(255,255,255);
}
.btn-upload-file:hover.with-attachment,
.btn-upload-file:focus.with-attachment{
		background-color: rgb(16, 101, 110);
		cursor: pointer;
}

.btn-upload-file.disabled{
		background: rgb(212, 212, 212);
		color: rgb(100,100,100);
		border-color: rgb(161, 161, 161);
}
.btn-upload-file:hover.disabled,
.btn-upload-file:focus.disabled{
		background: rgb(212, 212, 212);
		color: rgb(100,100,100);
		border-color: rgb(161, 161, 161);
		cursor: not-allowed;
}

.btn-view-file{
		display: table-cell;
		width: 30px;
		border-left: none;
		text-align: center;
		font-size: 16px;
		padding-left: 4px;
		vertical-align: middle;
		background: rgb(20,128,140);
		border-color: rgb(24,90,146);
		border-color: rgb(31, 149, 162);
		color: rgb(255,255,255);
}
.btn-view-file:hover,
.btn-view-file:focus{
		background-color: rgb(16, 101, 110);
		color: rgb(255,255,255);
		cursor: pointer;
}

.btn-view-file-delete{
		display: table-cell;
		width: 100%;
		height: 30px;
		border-top-right-radius: 4px;
		border-bottom-right-radius: 4px;
		border: 1px solid rgb(24, 90, 146);
		color: rgb(255,255,255);
		background: #d9534f;
		border-color: #ac2925;
}

.btn-view-file-delete:hover,
.btn-view-file-delete:focus{
		background-color: #c9302c;
		color: #fff;
		cursor: pointer;
}

.input-file-hidden{
		visibility: hidden;
		position: absolute;
		left: -99999px;
}
#tabla_datos_beca_activa {
		font-size: 12px;
}
.full-width{
		width: 100%;
}
#container_fotografia input.file{
		visibility: hidden;
		display: none;
}
.bubble-timeline{
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
.icon-timeline-container{
		background-color: rgb(77, 178, 208);
		border: 3px solid rgb(255, 255, 255);
		border-radius: 50%;
		display: inline-block;
}
.img-timeline{
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
   <title></title>
</head>

<body>
  <!-- Carga de loagind y los mensajes de alerta -->
  <div id="overlay_loading"></div>
  <div id="message_box"></div>

	<div class="row">
    <div class="col-md-12">
      <div class="page-header">
        <h1 class="translate" data-traducir_english="New document for Repository" data-traducir_spanish="Ingreso de documento para Repositorio">Ingreso de documento <small>Registro de un nuevo documento en la Biblioteca Virtual</small></h1>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
      <div class="form-group input-group custom-search-form input-group-sm">
        <input type="text" class="form-control limpiar placeholder_translate"data-placeholder_en="Search document in repository" data-placeholder_es="Buscar documento en repositorio" data-provide="typeahead" id="busqueda" placeholder="Buscar documento en repositorio" style="background-color:#b1e1ef;">
        <span class="input-group-btn">
          <button class="btn btn-info" type="button" id="buscar" name="buscar">
            <i class="fa fa-search"></i>
          </button>
        </span>
      </div><!-- /input-group -->
    </div>
    <div class="col-md-3"></div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <label class="translate" data-traducir_english="Program" data-traducir_spanish="Programa" for="cod_gerencia">Programa</label>
        <div class="form-group show-tick">
        <select class="selectpicker show-menu-arrow requerido" data-size="auto" id="cod_gerencia" name="cod_gerencia" data-live-search="true"></select>
      </div>
    </div>
      <div class="col-md-3">
        <label class="translate" data-traducir_english="Document type" data-traducir_spanish="Tipo de documento" for="cod_tipo_documento">Tipo de documento</label>
          <div class="form-group show-tick">
          <select class="selectpicker show-menu-arrow requerido" data-size="auto" id="cod_tipo_documento" name="cod_tipo_documento" data-live-search="true"></select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group input-group-sm">
          <label class="translate" data-traducir_english="Document Title" data-traducir_spanish="Título del documento" for="titulo_documento">Título del documento</label>
          <input type="text" class="form-control input requerido placeholder_translate" data-placeholder_en="Enter the title that will carry the document" data-placeholder_es="Ingrese el título que llevará el documento" id="titulo_documento" placeholder="Ingrese el título que llevará el documento">
        </div>
      </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="form-group input-group-sm">
        <label class="translate" data-traducir_english="Description" data-traducir_spanish="Descripción" for="descripcion">Descripción</label>
        <textarea id="descripcion" class="form-control input requerido placeholder_translate" data-placeholder_en="Briefly describe the purpose of the document" data-placeholder_es="Describa brevemente la finalidad del documento" style="overflow:auto;resize:none; height: 80px; max-height: 80px;" rows="4" placeholder="Describa brevemente la finalidad del documento"></textarea>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="form-group input-group-sm">
        <label class="translate" data-traducir_english="Keywords" data-traducir_spanish="Palabras claves" for="descripcion">Palabras claves <small class="translate" data-traducir_english="Write the words separated by commas (,)" data-traducir_spanish="Escriba las palabras separadas por comas (,)">Escriba las palabras separadas por comas (,)</small></label>
        <input type="text" class="form-control input requerido placeholder_translate" data-placeholder_en="Enter keywords to search the document. Example: manual, form, report, farm, policies" data-placeholder_es="Ingrese palabras claves para busqueda del documento. Ej. manual, formulario, reporte, granja, políticaso" id="palabras_claves" placeholder="Ingrese palabras claves para busqueda del documento. Ej. manual, formulario, reporte, granja, políticas">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12" id="container_upload_box_PDF">
      <div class="form-group input-group-sm">
        <label class="translate" data-traducir_english="Document attachment" data-traducir_spanish="Adjunto de documento" for="descripcion">Adjunto de documento <small>Debe de estar en formato PDF</small></label>
        <div class="container-upload-box">
            <button data-disabled="true" data-id="foto" class="smooth-transition btn-select-file translate" data-traducir_english="Select file" data-traducir_spanish="Seleccione archivo">
                <i class="icon-file fa fa-file"> </i>
                <span id="msj-btn-file-select-PDF" class="msj-btn-file"> Seleccione archivo</span>
            </button>
            <input type="file" title="Seleccione Archivo..." class="input-file-hidden" id="archivo_PDF" accept=".pdf, .jpeg, .jpg, .png, .xls, .xlsx, .docx, .doc, .pptx, .ppt">
        </div>
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
                <button data-loading-text="Volviendo" class="btn btn-sm btn-warning btn-fullwidth main-actions smooth-transition" type="button" id="btn_ir_al_listado" name="btn_ir_al_listado">
                    <i class="fa fa-arrow-left"> </i> <span id="btn_regresar_listado" class="translate" data-traducir_english="Back to file list" data-traducir_spanish="Regresar al listado" for="descripcion">Regresar al listado</span>
                </button>
            </div>

            <div class="col-xs-3 col-md-9 nopadding smooth-transition" id="div_acciones">
                <button class="btn btn-sm btn-primary btn-open-actions main-actions smooth-transition" type="button" id="btn_open_actions" name="btn_open_actions">
                    <i class="fa fa-ellipsis-v"></i>
                </button>
    			<button class="btn btn-sm btn-primary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" type="button" id="btn_crear_registro">Guardar</button>
            </div>
        </div>
    </div> <!-- panel-footer -->
</body>
