<?php
/*
* 	Registro de información de las sembradoras,
* 	@author 		Jairo Bonilla
* 	@date 			2015-07-28
*/
session_start();
if(!isset($_SESSION['cod_usuario'])){
	echo "<script>window.location.href = '" . $_SESSION['úrl_inicio'] . "';</script>"; // Usando JS para redireccionar
	// header('Location: index.php'); // Método anterior (No funcional)
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_inventario.php");
/*INSTANCIAMIENTOS*/
$DB_INV = new db_inventario();

$cod_granjas_usuario = str_replace(['[','"',']'], '', $_SESSION['cod_info_empresa']);

$inicio = $_POST['inicio'];
if (!isset($_POST['inicio'])) {
    $inicio = 0;
}
$limite = $_POST['limite'];
if (!isset($_POST['limite'])) {
    $limite = 50;
}
//$QUIMICOS = $DB_INV->inv_listado_quimicos();
$QUIMICOS = $DB_INV->inv_listado_quimicos_por_granjas($cod_granjas_usuario);
//$QUIMICOS = $DB_INV->inv_listado_quimicos_por_granjas_paginacion($cod_granjas_usuario,$inicio,$limite);
$TOTAL = $DB_INV->inv_total_quimicos_por_granjas($cod_granjas_usuario);

$cod_quimico = $_POST['cod_quimico'];
if (!isset($_POST['cod_quimico'])) {
	$cod_quimico = 0;
}
$QUIMICO = $DB_INV->inv_obtener_info_quimico($cod_quimico);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Nueva capacitación</title>
</head>
<script type="text/javascript">
	jQuery.ajaxSetup({async:false});
	grl_overlay_loading('');
    var array_file_list = [];
    array_file_list.push({id:0,file:'',file_ext:''});
    var $container_upload_box = $("#container_upload_box");
    var files_foto = '';
    var nombre_foto = '';
    var files_label = '';
    var nombre_label = '';
    var ext = '';



    function update_list_item_interface_foto($li,nombre_archivo){
        $li.unbind('click');
        if(nombre_archivo != ''){
            $li.addClass('row-with-attachment');
            $li.find("span.state-icon").addClass('fa-paperclip').removeClass('fa-check-square-o');
            $li.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file with-attachment').data('disabled',false);
            $li.find('i.icon-file').addClass('hide');
            $li.find('span.msj-btn-file').text('Fotografia');
            $li.find(".btn-upload-file").addClass('hide').data('disabled',true);
            $li.find(".container-upload-box").append('<a tabindex="0" target="_blank" href="'+nombre_archivo+'" class="smooth-transition btn-view-file">' +
                                                        '<i class="fa fa-eye"></i>' +
                                                    '</a>');
        }
    }

    function update_list_item_interface2_foto($li){
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
        $li.on('change','input[type=file]',prepare_upload2);
    }
    function prepare_upload(event)
    {
        var $container_upload_box = $(this).parent();
        files_label = event.target.files;
        var cancel_button_is_clicked = files_label[0];
        if( cancel_button_is_clicked == undefined ){
                constructor_file_input($(this).parent());
        }
        else
        {
            var id = $container_upload_box.find('.btn-select-file').data('id');
            var filesize =  files_label[0].size/1024/1024;
            if(filesize > 10){
                grl_mensaje('Tamaño de archivo no permitido. ', 'Solo se permiten archivos menores de 10 MB. ', 'warning');
                cambio_adjunto = false;
                constructor_file_input($container_upload_box);
            }
            else
            {
                ext = $(this).val().match(/\.([^\.]+)$/)[1];
                ext = ext.toLowerCase ();
                update_array_file_list(id, files_label, ext);
                switch(ext)
                {
                    case 'jpg':
                    case 'jpeg':
                    case 'bmp':
                    case 'png':
                    case 'tif':
                    case 'tiff':
                    case 'doc':
                    case 'docx':
                    case 'xls':
                    case 'xlxs':
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
                        grl_mensaje('Tipo de archivo no permitido', 'solo se permiten imágenes, documentos de Word, Excel y PDF.', 'warning');
                        constructor_file_input($(this).parent());
                    }
                }
           }
       }
    }
    function prepare_upload2(event)
    {
        var $container_upload_box = $(this).parent();
        files_foto = event.target.files;
        var cancel_button_is_clicked = files_foto[0];
        if( cancel_button_is_clicked == undefined ){
                constructor_file_input($(this).parent());
        }
        else
        {
            var id = $container_upload_box.find('.btn-select-file').data('id');
            var filesize =  files_foto[0].size/1024/1024;
            if(filesize > 10){
                grl_mensaje('Tamaño de archivo no permitido. ', 'Solo se permiten archivos menores de 10 MB. ', 'warning');
                cambio_adjunto = false;
                constructor_file_input($container_upload_box);
            }
            else
            {
                ext = $(this).val().match(/\.([^\.]+)$/)[1];
                ext = ext.toLowerCase ();
                update_array_file_list(id, files_foto, ext);
                switch(ext)
                {
                    case 'jpg':
                    case 'jpeg':
                    case 'bmp':
                    case 'png':
                    case 'tif':
                    case 'tiff':
                    case 'doc':
                    case 'docx':
                    case 'xls':
                    case 'xlxs':
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
                        grl_mensaje('Tipo de archivo no permitido', 'solo se permiten imágenes, documentos de Word, Excel y PDF.', 'warning');
                        constructor_file_input($(this).parent());
                    }
                }
           }
       }
    }


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
        files       = '';
        file_ext    = '';
        var id      = $container_upload_box.find('.btn-select-file').data('id');
        update_array_file_list(id, files, file_ext);
    }

    function update_array_file_list(id, file, file_ext) {
        //alert(id + file_ext);
        for (var i in array_file_list) {
                if (array_file_list[i].id == id) {
                        array_file_list[i].file     = file;
                        array_file_list[i].file_ext = file_ext;
                        break;
                }
         }
    }
	$(document).ready(function() {
		codigo_inventario_quimico = 0;
        $('.selectpicker').selectpicker({
            dropupAuto: 'true',
            container: 'body',
            size: '5',
            width: '100%',
            style: 'btn-sm btn-info', 
            tickIcon: 'fa fa-check'
        });
        //Habilita los selects para mobile
	    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	        $('.selectpicker').selectpicker('mobile');
	    }
        $('.datetime').datetimepicker({
            //disabledHours: true,
            locale: 'es',
            //minDate: hoy,
            //keepOpen: true,
            format: 'MM-DD-YYYY HH:mm:ss',
            //defaultDate: '12:00',
            //direction: 'auto',
            icons: {
                        time: "fas fa-clock",
                        date: "fa fa-calendar",
                        up: "fa fa-arrow-up",
                        down: "fa fa-arrow-down",
                        previous: 'fa fa-arrow-left',
                        next: 'fa fa-arrow-right',
                    }
        });
        //$('html, body').animate({ scrollTop: 0 }, 0);
        /*
		 * Función que realiza la "busqueda" dentro de la tabla con información
		 */
		/*(function(){
			'use strict';
			var $ = jQuery;
			$.fn.extend({
				filterTable: function(){
					return this.each(function(){
						$(this).on('keyup', function(e){
							$('.filterTable_no_results').remove();
							var $this = $(this), search = $this.val().toLowerCase(), target = $this.attr('data-filters'), $target = $(target), $rows = $target.find('tbody tr');
							if(search == '') {
								$rows.show();
							} else {
								$rows.each(function(){
									var $this = $(this);
									$this.text().toLowerCase().indexOf(search) === -1 ? $this.hide() : $this.show();
								})
								if($target.find('tbody tr:visible').size() === 0) {
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

		$(function(){
			// attach table filter plugin to inputs
			$('[data-action="filter"]').filterTable();

			$('.busqueda_contenedor').on('click', '.panel_cabecera span.filter', function(e){
				var $this = $(this),
						$panel = $this.parents('.panel');

				$panel.find('.panel_cuerpo').slideToggle();
				if($this.css('display') != 'none') {
					$panel.find('.panel_cuerpo input').focus();
				}
			});
			$('[data-toggle="tooltip"]').tooltip();
		});*/
		//Constructores
		inv_constructor_listado_variedades_productos();
		conf_constructor_listado_granjas();
		inv_constructor_listado_unidades_medida();
		inv_constructor_listado_ingredientes_activos();
		inv_constructor_listado_tipos_quimicos();
		inv_constructor_listado_tipos_periodo('cod_tipo_periodo_reingreso');
		inv_constructor_listado_tipos_periodo('cod_tipo_periodo_precosecha');
        $('#dev-table').DataTable({
            /*"language":
            {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },*/
            "dom": 'Bfrtip',
            "buttons": [
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    title: 'Chemicals Inventory',
                },
                'print',
            ]
        });
		//Máscaras
        //$('.monto').mask("#,##0.000", {reverse: false, maxlength: false});
        $('.monto').mask("9999999.999");
	    $('.letras').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z-¡!¿?,.()% 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/, optional: false}}});
	    $('.letras15').mask('SSSSSSSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z.\-_ 0-9]/, optional: false}}});

	    /*----------------------------------------------------------------------------------
    								Validando listboxs
	    ----------------------------------------------------------------------------------*/
		$('.selectpicker.requerido').change(function(event) {
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
		/*----------------------------------------------------------------------------------
	    					Validación input, textarea requeridos
	    ----------------------------------------------------------------------------------*/
	    $('.input.requerido').keyup(function(event) {
	    	if( $(this).val().trim() != "" ){
				$(this).parent('div').removeClass('has-error');
			}
			else{
				$(this).parent('div').addClass('has-error');
			}
	    });


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
        $('#archivo-foto').on('change', prepare_upload2 );
        $('#archivo-label').on('change', prepare_upload );
        $('#checkbox_habilitar_sumar_restar').change(function(event) {
            /* Act on the event */
            event.preventDefault();
            event.stopPropagation();
            if($('#checkbox_habilitar_sumar_restar').attr('checked'))
            {
                $('#div_sumar_restar').addClass('hide');
                $('#checkbox_habilitar_sumar_restar').removeAttr('checked');
            }
            else
            {
                $('#div_sumar_restar').removeClass('hide');
                $('#checkbox_habilitar_sumar_restar').attr('checked','checked');
            }
        });
	    <?php
	    if (count($QUIMICO))
	    {
	    	?>
	    	$('#cod_quimico').val("<?php echo utf8_encode($QUIMICO[0]['cod_quimico']); ?>");
	    	$('#nombre_quimico').val("<?php echo utf8_encode($QUIMICO[0]['nombre_quimico']); ?>");
	    	$('#cantidad_quimico').val("<?php echo utf8_encode($QUIMICO[0]['cantidad_quimico']); ?>");
	    	$('#cantidad_fisica_quimico').val("<?php echo utf8_encode($QUIMICO[0]['cantidad_fisica_quimico']); ?>");
	    	$('#cantidad_fisica_semilla').val("<?php echo utf8_encode($QUIMICO[0]['cantidad_fisica_semilla']); ?>");
	    	$('#registro_ambiental').val("<?php echo utf8_encode($QUIMICO[0]['registro_ambiental']); ?>");
	    	$('#periodo_reingreso').val("<?php echo utf8_encode($QUIMICO[0]['periodo_reingreso']); ?>");
	    	$('#periodo_precosecha').val("<?php echo utf8_encode($QUIMICO[0]['periodo_precosecha']); ?>");
	    	$('#dosis_minima').val("<?php echo utf8_encode($QUIMICO[0]['dosis_minima']); ?>");
	    	$('#dosis_maxima').val("<?php echo utf8_encode($QUIMICO[0]['dosis_maxima']); ?>");
	    	$('#cantidad_minima_alerta').val("<?php echo utf8_encode($QUIMICO[0]['cantidad_minima_alerta']); ?>");
	    	$('#precio_quimico').val("<?php echo utf8_encode($QUIMICO[0]['precio_quimico']); ?>");
	    	$('#razon_aplicacion').val("<?php echo utf8_encode($QUIMICO[0]['razon_aplicacion']); ?>");
	    	$('#etiqueta').val("<?php echo utf8_encode($QUIMICO[0]['etiqueta']); ?>");
	    	$('#cod_info_empresa').selectpicker('val',"<?php echo utf8_encode($QUIMICO[0]['cod_info_empresa']); ?>");
	    	$('#cod_ingrediente_activo').selectpicker('val',"<?php echo utf8_encode($QUIMICO[0]['cod_ingrediente_activo']); ?>");
	    	$('#cod_unidad_medida').selectpicker('val',"<?php echo utf8_encode($QUIMICO[0]['cod_unidad_medida']); ?>");
	    	$('#cod_tipo_quimico').selectpicker('val',"<?php echo utf8_encode($QUIMICO[0]['cod_tipo_quimico']); ?>");
	    	$('#cod_tipo_periodo_reingreso').selectpicker('val',"<?php echo utf8_encode($QUIMICO[0]['cod_tipo_periodo_reingreso']); ?>");
	    	$('#cod_tipo_periodo_precosecha').selectpicker('val',"<?php echo utf8_encode($QUIMICO[0]['cod_tipo_periodo_precosecha']); ?>");
	    	$('.selectpicker').selectpicker('refresh');
            $('#div_habilitar_sumar_restar').removeClass('hide');
            $('#btn_copiar_inventario').removeClass('hide');
            $('#cantidad_quimico').attr('disabled', 'disabled');
	    	<?php
            if($QUIMICO[0]['hoja_seguridad'] != null || $QUIMICO[0]['hoja_seguridad'] != '')
            {
            ?>
                update_list_item_interface_foto($('#container_upload_box_foto'),"<?php echo '../../mod_inventario/adjuntos/'.$QUIMICO[0]['hoja_seguridad']; ?>")
            <?php
            }
            if($QUIMICO[0]['etiqueta'] != null || $QUIMICO[0]['etiqueta'] != '')
            {
            ?>
                update_list_item_interface_foto($('#container_upload_box_label'),"<?php echo '../../mod_inventario/adjuntos/'.$QUIMICO[0]['etiqueta']; ?>")
	    	<?php
            }

	    }
	    ?>
	    codigo_inventario_quimico = <?php echo $cod_quimico;?>;
	    $('#modal_loading').modal('hide');
	    jQuery.ajaxSetup({async:true});
	});

	$('#btn_guardar_inventario').click(function(event) {
         /* Act on the event */
        var error = 0;
        $(".input.requerido").map(function(){
            if( !$(this).val() )
            {
                error = 1;
                $(this).parent('div').addClass('has-error');
                return false;
            }
            else
            {
                $(this).parent('div').removeClass('has-error');
            }
        });
        $(".selectpicker.requerido").map(function(){
            if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '')
            {
                $(this).selectpicker('setStyle', 'btn-info', 'remove');
                $(this).selectpicker('setStyle', 'btn-danger');
                error = 1;
            }
            else
            {
                $(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');
                $(this).selectpicker('setStyle', 'btn-info');
                $(this).removeClass('campo-vacio');
            }
            $(this).selectpicker('refresh');
        });
        if($('#checkbox_habilitar_sumar_restar').attr('checked'))
        {
            if($('#cantidad_sumar_restar').val() <= 0)
            {
                error = 2;
            }
            if($('#razon_sumar_restar').val() == '')
            {
                error = 3;
            }
        }
        if (error == 0)
        {
            grl_overlay_loading('');
            $('#modal_loading').modal('hide');
            $('#modal_loading').on('hidden.bs.modal', function () {
            	jQuery.ajaxSetup({async:false});
                if(files_foto.length)
                {
                    var ext = files_foto['0']['name'].match(/\.([^\.]+)$/)[1];
                }
                else
                {
                    var ext = '';
                }
                if(files_label.length)
                {
                    var ext_label = files_label['0']['name'].match(/\.([^\.]+)$/)[1];
                }
                else
                {
                    var ext_label = '';
                }
                var cod_inventario = inv_guardar_inventario_quimico(codigo_inventario_quimico, ext, ext_label);
                if(files_foto.length)
                {
                    nombre_foto = 'security_sheet_' + cod_inventario + '.' + ext;
                    var data = new FormData();
                    $.each(files_foto, function(key, value){data.append(key, value);});
                    var ext = files_foto['0']['name'].match(/\.([^\.]+)$/)[1];
                    $.ajax({
                        url:  'mod_inventario/funciones/inv_subir_adjunto.php?files_foto&x1='+nombre_foto,
                        type: 'POST',
                        data: data,
                        cache: false,
                        dataType: 'json',
                        processData: false, // No procesa los archivos
                        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                        error: function(){
                            //$container_upload_box.find('progress_bar').remove();
                            grl_mensaje('Lo sentimos. ', 'Ha habido un inconveniente al subir archivo, favor intentarlo más tarde.', 'danger');
                                                            flag_foto = 1;
                        },
                        success: function(data, textStatus, jqXHR)
                        {
                            if(typeof data.error === 'undefined'){
                                // Todo bien, así que copia definitivamente los archivos al servidor.
                                grl_mensaje('Archivo agregado correctamente. ', '', 'success');
                            }
                            else{
                                grl_mensaje('Lo sentimos. ', 'Ha habido un inconveniente al subir archivo, favor intentarlo más tarde.', 'danger');
                                                                    flag_foto = 1;
                            }
                        }
                    });
                }
                if(files_label.length)
                {
                    nombre_label = 'label_' + cod_inventario + '.' + ext;
                    var data = new FormData();
                    $.each(files_label, function(key, value){data.append(key, value);});
                    var ext = files_label['0']['name'].match(/\.([^\.]+)$/)[1];
                    $.ajax({
                        url:  'mod_inventario/funciones/inv_subir_adjunto.php?files_label&x1='+nombre_label,
                        type: 'POST',
                        data: data,
                        cache: false,
                        dataType: 'json',
                        processData: false, // No procesa los archivos
                        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                        error: function(){
                            //$container_upload_box.find('progress_bar').remove();
                            grl_mensaje('Lo sentimos. ', 'Ha habido un inconveniente al subir archivo, favor intentarlo más tarde.', 'danger');
                                                            flag_foto = 1;
                        },
                        success: function(data, textStatus, jqXHR)
                        {
                            if(typeof data.error === 'undefined'){
                                // Todo bien, así que copia definitivamente los archivos al servidor.
                                grl_mensaje('Archivo agregado correctamente. ', '', 'success');
                            }
                            else{
                                grl_mensaje('Lo sentimos. ', 'Ha habido un inconveniente al subir archivo, favor intentarlo más tarde.', 'danger');
                                                                    flag_foto = 1;
                            }
                        }
                    });
                }
                inv_vista_inventario_quimico(cod_inventario);
                jQuery.ajaxSetup({async:true});
            });
        }
        else
        {
            if(error == 2)
                grl_mensaje('Debe ingresar una cantidad mayor que 0','You must enter an amount greater than 0','warning');
            if(error == 1)
                grl_mensaje('Debe llenar todos los campos marcados','You must fill in all the marked fields','warning');
            if(error == 3)
                grl_mensaje('Debe ingresar una razón de cambio','You must enter a reason for change','warning');

        }
     });
    $('#cuerpo_tabla').on('change', '.checkbox', function(event) {
        event.preventDefault();
        /* Act on the event */
        event.preventDefault();
        event.stopPropagation();
        inv_cambiar_estado_inventario_quimico($(this).data('id'),($(this).attr('checked') ? 0 : 1));
    });
	/*$('.checkbox').on('change', function(event) {
		event.preventDefault();
		event.stopPropagation();
		inv_cambiar_estado_inventario_quimico($(this).data('id'),($(this).attr('checked') ? 0 : 1));
	});*/
    /*$('#div_paginacion').on('click', '.paginate_button', function(event) {
        event.preventDefault();
        jQuery.ajaxSetup({async:false});
        inv_vista_listado_quimicos($(this).data('inicio'),$(this).data('limite'));
        $('.current').removeClass('current');
        $(this).addClass('current');
        jQuery.ajaxSetup({async:true});
    });*/

    $('#btn_copiar_inventario').click(function(event) {
        /* Act on the event */
        jQuery.ajaxSetup({async:false});
        conf_constructor_listado_granjas('cod_info_empresa2');
        $('#modal_copiar_inventario').modal('show');
        jQuery.ajaxSetup({async:true});
    });

    $('#btn_guardar_copiar_inventario').click(function(event) {
        /* Act on the event */
        jQuery.ajaxSetup({async:false});
        $('#modal_copiar_inventario').modal('hide');
        //$('#modal_copiar_inventario').on('hidden.bs.modal', function () {
            inv_copiar_inventario_quimico(codigo_inventario_quimico, $('#cod_info_empresa2').val());
        //});
        jQuery.ajaxSetup({async:true});
    });
</script>

<body>
	<div id="overlay_loading"></div>
	<div id="message_box"></div>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-md-12">
                <div class="panel-header">
                    <h1 class="translate" data-traducir_english="Chemical" data-traducir_spanish="Químico">Químico</h1>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group input-group-sm">
                            <label for="cod_info_empresa" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</label>
                            <select class="selectpicker show-menu-arrow requerido" title="Select" id="cod_info_empresa" name="cod_info_empresa">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group input-group-sm">
                            <label for="cod_quimico" class="translate" data-traducir_english="Product Code" data-traducir_spanish="Código">Código</label>
                            <input type="text"class="form-control letras input requerido"  id="cod_quimico" name="cod_quimico">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group input-group-sm">
                            <label for="nombre_quimico" class="translate" data-traducir_english="Product Name" data-traducir_spanish="Name">Nombre</label>
                            <input type="text"class="form-control letras input requerido"  id="nombre_quimico" name="nombre_quimico">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group input-group-sm">
                            <label for="cod_unidad_medida" class="translate" data-traducir_english="Unit of Measure" data-traducir_spanish="Unidad de Medida">Unidad de Medida</label>
                            <select class="selectpicker show-menu-arrow requerido" title="Select" id="cod_unidad_medida" name="cod_unidad_medida">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group input-group-sm">
                            <label for="cantidad_quimico" class="translate" data-traducir_english="Stock" data-traducir_spanish="Cantidad">Cantidad</label>
                            <input type="text"class="form-control monto input requerido"  id="cantidad_quimico" name="cantidad_quimico">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group input-group-sm">
                            <label for="cantidad_fisica_quimico" class="translate" data-traducir_english="Physical Amount" data-traducir_spanish="Cantidad Física">Cantidad Física</label>
                            <input type="text"class="form-control monto input requerido"  id="cantidad_fisica_quimico" name="cantidad_fisica_quimico">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group input-group-sm">
                            <label for="precio_quimico" class="translate" data-traducir_english="Price per Unit" data-traducir_spanish="Precio Unidad">Precio Unidad</label>
                            <input type="text"class="form-control monto input"  id="precio_quimico" name="precio_quimico">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group input-group-sm">
                            <label for="cod_ingrediente_activo" class="translate" data-traducir_english="Active Ingredient" data-traducir_spanish="Ingrediente Activo">Ingrediente Activo</label>
                            <select class="selectpicker show-menu-arrow requerido" title="Select" id="cod_ingrediente_activo" name="cod_ingrediente_activo">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group input-group-sm">
                            <label for="registro_ambiental" class="translate" data-traducir_english="Reg. EPA" data-traducir_spanish="Registro Ambiental">Registro Ambiental</label>
                            <input type="text"class="form-control letras input"  id="registro_ambiental" name="registro_ambiental">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group input-group-sm">
                            <label for="periodo_reingreso" class="translate" data-traducir_english="REI" data-traducir_spanish="Período Reingreso">Período Reingreso</label>
                            <input type="text"class="form-control monto input"  id="periodo_reingreso" name="periodo_reingreso">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group input-group-sm">
                            <label for="cod_tipo_periodo_reingreso" class="translate" data-traducir_english="Period Type" data-traducir_spanish="Tipo Período">Tipo Período</label>
                            <select class="selectpicker show-menu-arrow" title="Select" id="cod_tipo_periodo_reingreso" name="cod_tipo_periodo_reingreso">
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group input-group-sm">
                            <label for="periodo_precosecha" class="translate" data-traducir_english="PHI" data-traducir_spanish="Período Precosecha">Período Precosecha</label>
                            <input type="text"class="form-control monto input"  id="periodo_precosecha" name="periodo_precosecha">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group input-group-sm">
                            <label for="cod_tipo_periodo_precosecha" class="translate" data-traducir_english="Period Type" data-traducir_spanish="Tipo Período">Tipo Período</label>
                            <select class="selectpicker show-menu-arrow" title="Select" id="cod_tipo_periodo_precosecha" name="cod_tipo_periodo_precosecha">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group input-group-sm">
                            <label for="dosis_minima" class="translate" data-traducir_english="Low Rate" data-traducir_spanish="Dósis Mínima">Dósis Mínima</label>
                            <input type="text"class="form-control monto input requerido"  id="dosis_minima" name="dosis_minima">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group input-group-sm">
                            <label for="dosis_maxima" class="translate" data-traducir_english="High Rate" data-traducir_spanish="Dósis Máxima">Dósis Máxima</label>
                            <input type="text"class="form-control monto input requerido"  id="dosis_maxima" name="dosis_maxima">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group input-group-sm">
                            <label for="cod_tipo_quimico" class="translate" data-traducir_english="Chemical Type" data-traducir_spanish="Tipo Químico">Tipo Químico</label>
                            <select class="selectpicker show-menu-arrow requerido" title="Select" id="cod_tipo_quimico" name="cod_tipo_quimico">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group input-group-sm">
                            <label for="cantidad_minima_alerta" class="translate" data-traducir_english="Alert Amount" data-traducir_spanish="Cantidad Alerta">Cantidad Alerta</label>
                            <input type="text"class="form-control monto input requerido"  id="cantidad_minima_alerta" name="cantidad_minima_alerta">
                        </div>
                    </div>
                    <!-- <div class="col-md-3">
                        <div class="form-group input-group-sm">
                            <label for="etiqueta" class="translate" data-traducir_english="Label" data-traducir_spanish="Etiqueta">Etiqueta</label>
                            <input type="text"class="form-control letras15 input requerido"  id="etiqueta" name="etiqueta">
                        </div>
                    </div>  -->
                    <div id="container_upload_box_label">
                        <div class="col-md-3">
                            <label for="etiqueta" class="translate" data-traducir_english="Label" data-traducir_spanish="Etiqueta">Etiqueta</label>
                            <div class="container-upload-box">
                                <button data-disabled="true" data-id="foto" class="smooth-transition btn-select-file">
                                        <i class="icon-file fa fa-file"> </i>
                                        <span id="msj-btn-file-select-foto" class="msj-btn-file translate" data-traducir_english="Upload file" data-traducir_spanish="Seleccione archivo"> Seleccione archivo</span>
                                </button>
                                <input type="file" title="Seleccione Archivo..." class="input-file-hidden" id="archivo-label" accept=".jpg,.jpeg,.png,.bmp,.gif,.tiff,.pdf,.docx,.doc,.xls,.xlsx">
                            </div>
                        </div>
                    </div>
                    <div id="container_upload_box_foto">
                        <div class="col-md-3">
                            <label class="translate" data-traducir_english="SMDS" data-traducir_spanish="Hoja de Seguridad">Hoja de Seguridad</label>
                            <div class="container-upload-box">
                                <button data-disabled="true" data-id="foto" class="smooth-transition btn-select-file">
                                        <i class="icon-file fa fa-file"> </i>
                                        <span id="msj-btn-file-select-foto" class="msj-btn-file translate" data-traducir_english="Upload file" data-traducir_spanish="Seleccione archivo"> Seleccione archivo</span>
                                </button>
                                <input type="file" title="Seleccione Archivo..." class="input-file-hidden" id="archivo-foto" accept=".jpg,.jpeg,.png,.bmp,.gif,.tiff,.pdf,.docx,.doc,.xls,.xlsx">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="razon_aplicacion" class="translate" data-traducir_english="Reason for Application" data-traducir_spanish="Razón de Aplicación">Razón de Aplicación</label>
                        <textarea maxlength="3000" class="input requerido" id="razon_aplicacion"  align="left" style="height:100px; width:100%; resize: none;" ></textarea>
                    </div>
                </div>
                <hr>
                <div class="row hide" id="div_habilitar_sumar_restar" style="margin-bottom: 20px;">
                    <div class="col-md-4 col-md-offset-4">
                        <label class="translate" data-traducir_spanish="Habilitar Sumar/Restar" data-traducir_english="Enable Add/Substract">Habilitar Sumar/Restar</label>
                        <div class="material-switch pull-right">
                            <input class="checkbox_habilitar_sumar_restar" id="checkbox_habilitar_sumar_restar" name="checkbox_habilitar_sumar_restar" type="checkbox"/>
                            <label for="checkbox_habilitar_sumar_restar" class=""></label>
                        </div>
                    </div>
                </div>
                <div class="row hide" id="div_sumar_restar">
                    <div class="form-group input-group-sm col-md-3">
                        <label for="fecha_sumar_restar" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</label>
                        <div class='input-group input-group-sm fecha-planeada datetime' id='datetimepicker1'>
                            <span class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </span>
                            <input type='text' class="form-control input requerido_harvesting_worksheet" id="fecha_sumar_restar" />
                        </div>
                    </div>
                    <!-- <div class="col-md-3">
                        <div class="form-group input-group-sm">
                            <label for="checkbox_sumar_restar" class="translate" data-traducir_english="Add/Substract" data-traducir_spanish="Sumar/Restar">Sumar/Restar</label>
                            <select class="selectpicker show-menu-arrow" title="Select" id="checkbox_sumar_restar" name="checkbox_sumar_restar">
                                <option value="1">Sumar - Add</option>
                                <option value="0">Restar - Substract</option>
                            </select>
                        </div>
                    </div> -->
                    <div class="col-md-3">
                        <div class="form-group input-group-sm">
                            <label for="cantidad_sumar" class="translate" data-traducir_english="Add Stock" data-traducir_spanish="Sumar Inventario">Sumar Inventario</label>
                            <input type="text"class="form-control monto input" value="0"  id="cantidad_sumar" name="cantidad_sumar">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group input-group-sm">
                            <label for="cantidad_restar" class="translate" data-traducir_english="Substract Stock" data-traducir_spanish="Restar Inventario">Restar Inventario</label>
                            <input type="text"class="form-control monto input" value="0"  id="cantidad_restar" name="cantidad_restar">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="razon_sumar_restar" class="translate" data-traducir_english="Reason for Change" data-traducir_spanish="Razón de Cambio">Razón de Cambio</label>
                        <textarea maxlength="3000" class="input" id="razon_sumar_restar"  align="left" style="height:100px; width:100%; resize: none;" ></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer" align="right">
            <button class="btn btn-sm btn-success translate hide" data-traducir_english="Copy" data-traducir_spanish="Copiar" type="button" id="btn_copiar_inventario">Copiar</button>
            <button class="btn btn-sm btn-primary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" type="button" id="btn_guardar_inventario">Guardar</button>
        </div>
    </div>
	<div class="busqueda_contenedor">
	    <div class="row" style="min-width:100px;">
	        <div class="col-sm-12">
                <!-- <div class="panel-heading panel_cabecera">
                        <h3 class="panel-title translate" data-traducir_english="Chemical Inventory" data-traducir_spanish="Inventario de Químico">Inventario de Químico</h3>
						<div class="pull-right">
							<span class="clickable filter" data-toggle="tooltip" title="Realizar busqueda" data-container="body">
								<i class="fa fa-search"></i>
							</span>
						</div>
                </div>
				<div class="panel-body panel_cuerpo input-group-sm">
					<input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Busqueda" />
				</div> -->
                <h3 class="display-4 titulo translate" align="center" data-traducir_english="Chemical Inventory" data-traducir_spanish="Inventario de Químico">Inventario de Químico</h3>
                <div style="overflow-x:auto;">
                    <table class="table table-striped table-hover table-sm" id="dev-table" >
                        <thead>
                            <tr class="active info">
                                <th width="1%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
                                <th width="20%" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</th>
                                <th width="12%" class="translate" data-traducir_english="Code" data-traducir_spanish="Código">Código</th>
                                <th width="15%" class="translate" data-traducir_english="Name" data-traducir_spanish="Nombre">Nombre</th>
                                <th width="15%" class="translate" data-traducir_english="Active Ingredient" data-traducir_spanish="Ingrediente Activo">Ingrediente Activo</th>
                                <th width="13%" class="translate" data-traducir_english="Type" data-traducir_spanish="Tipo">Tipo</th>
                                <th width="10%" class="translate" data-traducir_english="Stock" data-traducir_spanish="Cantidad">Cantidad</th>
                                <th width="15%" class="translate" data-traducir_english="Unit of Measure" data-traducir_spanish="Unidad Medida">Unidad Medida</th>
                                <th width="15%" class="translate" data-traducir_english="REI" data-traducir_spanish="Período Reingreso">Período Reingreso</th>
                                <th width="15%" class="translate" data-traducir_english="PHI" data-traducir_spanish="Período Precosecha">Período Precosecha</th>
                                <th width="10%"></th>
                            </tr>
                        </thead>
                        <tbody id="cuerpo_tabla">
                            <?php
                            if(count($QUIMICOS))
                            {
                            	$correlativo = 1;
                            	foreach($QUIMICOS as $quimico)
                            	{
                            		?>
                            		<tr>
                            			<td onclick="inv_vista_inventario_quimico(<?php echo utf8_encode($quimico['cod_inventario']);?>)"><?php echo $correlativo;?></td>
                            			<td onclick="inv_vista_inventario_quimico(<?php echo utf8_encode($quimico['cod_inventario']);?>)"><?php echo utf8_encode($quimico['nombre_empresa']);?></td>
                            			<td onclick="inv_vista_inventario_quimico(<?php echo utf8_encode($quimico['cod_inventario']);?>)"><?php echo utf8_encode($quimico['cod_quimico']);?></td>
                            			<td onclick="inv_vista_inventario_quimico(<?php echo utf8_encode($quimico['cod_inventario']);?>)"><?php echo utf8_encode($quimico['nombre_quimico']);?></td>
                            			<td onclick="inv_vista_inventario_quimico(<?php echo utf8_encode($quimico['cod_inventario']);?>)"><?php echo utf8_encode($quimico['ingrediente_activo']);?></td>
                            			<td onclick="inv_vista_inventario_quimico(<?php echo utf8_encode($quimico['cod_inventario']);?>)"><?php echo utf8_encode($quimico['tipo_quimico']);?></td>
                            			<td onclick="inv_vista_inventario_quimico(<?php echo utf8_encode($quimico['cod_inventario']);?>)"><?php echo utf8_encode($quimico['cantidad_quimico']);?></td>
                            			<td onclick="inv_vista_inventario_quimico(<?php echo utf8_encode($quimico['cod_inventario']);?>)"><?php echo utf8_encode($quimico['unidad_medida']);?></td>
                                        <td onclick="inv_vista_inventario_quimico(<?php echo utf8_encode($quimico['cod_inventario']);?>)"><?php echo utf8_encode($quimico['periodo_reingreso'].' '.$quimico['tipo_periodo_reingreso']);?></td>
                                        <td onclick="inv_vista_inventario_quimico(<?php echo utf8_encode($quimico['cod_inventario']);?>)"><?php echo utf8_encode($quimico['periodo_precosecha'].' '.$quimico['tipo_periodo_precosecha']);?></td>
                            			<td>
                            				<div class="material-switch pull-right">
					                            <input class="checkbox" id="checkbox_<?php echo utf8_encode($quimico['cod_inventario']);?>" data-id="<?php echo utf8_encode($quimico['cod_inventario']);?>" name="checkbox_<?php echo utf8_encode($quimico['cod_inventario']);?>" type="checkbox" <?php echo ($quimico['activo'] == 1 ? 'checked="checked"':'');?>/>
					                            <label for="checkbox_<?php echo utf8_encode($quimico['cod_inventario']);?>" class=""></label>
					                        </div>
                            			</td>
                            		</tr>
                            		<?php
                            		$correlativo++;
                            	}
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- <div class="div_pagination" id="div_paginacion">
                    <?php
                        $cuerpo_paginacion = "";
                        if(count($TOTAL) > 0)
                        {
                            $total          = $TOTAL[0]['total'];
                            $num_reg        = 1;
                            $paginacion     = 1;
                            $inicio         = 0;
                            $limite         = 50;
                            $cuerpo_paginacion   .= '<a class="paginate_button current" aria-controls="example" data-pagina="'.$paginacion.'" data-inicio="0" data-limite="'.$limite.'" tabindex="0">'.$paginacion.'</a>';
                            $total          = $total - $limite;
                            while ($total > 1)
                            {
                                $total      = $total - $limite;
                                $num_reg    = $num_reg + $limite;
                                $inicio     = $inicio + $limite;
                                $paginacion++;
                                $cuerpo_paginacion .= '<a class="paginate_button" aria-controls="example" data-pagina="'.$paginacion.'" data-inicio="'.$num_reg.'" data-limite="'.$limite.'" tabindex="0">'.$paginacion.'</a>';
                            }
                        }
                        echo $cuerpo_paginacion;
                    ?>
                </div> -->
	        </div>
	    </div>
	</div>
    <!-- Modal -->
    <div class="modal" id="modal_copiar_inventario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title translate" id="exampleModalLabel" data-traducir_english="Copy Inventory" data-traducir_spanish="Copiar inventario">Copiar inventario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="form-group input-group-sm">
                            <label for="cod_info_empresa2" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</label>
                            <select class="selectpicker show-menu-arrow" title="Select" multiple="multiple" id="cod_info_empresa2" name="cod_info_empresa2">
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_guardar_copiar_inventario">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</body>