<?php
/*
* 	Registro de información de las sembradoras,
* 	@author 		Jairo Bonilla
* 	@date 			2015-07-28
* 	@editor 		Edwin Olivera
* 	@update 		2023-09-16
*/
session_start();
if (!isset($_SESSION['cod_usuario'])) {
    echo "<script>window.location.href = '" . $_SESSION['úrl_inicio'] . "';</script>"; // Usando JS para redireccionar
    // header('Location: index.php'); // Método anterior (No funcional)
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_inventario.php");
/*INSTANCIAMIENTOS*/
$DB_INV = new db_inventario();

$cod_granjas_usuario = str_replace(['[', '"', ']'], '', $_SESSION['cod_info_empresa']);
$cantidadTotalAPagar = 0;


$cod_orden_compra = $_POST['cod_orden_compra'];
if (!isset($_POST['cod_orden_compra'])) {
    $cod_orden_compra = 0;
}
$inicio = $_POST['inicio'];
if (!isset($_POST['inicio'])) {
    $inicio = 0;
}
$limite = $_POST['limite'];
if (!isset($_POST['limite'])) {
    $limite = 10;
}
//$ORDENES = $DB_INV->inv_listado_ordenes_compra();
$ORDENES = $DB_INV->inv_listado_ordenes_compra_por_granjas($cod_granjas_usuario);

$ORDEN = $DB_INV->inv_obtener_info_orden_compra($cod_orden_compra);
$PRODUCTOS = $DB_INV->inv_listado_ordenes_compra_productos($cod_orden_compra);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Ordenes de compras</title>

    <style>
        .div_btn_nueva_orden {
            width: 20%;
            /* Ancho del contenedor padre */
        }

        #btn_agregar_producto_inicial {
            /* width: 30%; */
            /* Limita el ancho del botón al 30% del contenedor */
            max-width: 100%;
            /* Asegura que el botón no se desborde del contenedor */
            padding: 5px;
            border: none;
            cursor: pointer;
        }


        .btn_eliminar_semilla_editable {
            background-color: red;
            color: white;
            border: none;
            font-weight: bold;
            text-transform: uppercase;
            width: 100%;
            padding: 5px 0px;
        }

        .btn_eliminar_semilla_editable i {
            color: white;
            margin-right: 5px;
        }

        .btn_eliminar_semilla_editable:hover {
            background-color: darkred;
            cursor: pointer;
            /* Cambia el cursor a una mano para indicar que es interactivo */
        }
    </style>
</head>

<script type="text/javascript">
    jQuery.ajaxSetup({
        async: false
    });
    grl_overlay_loading('');

    $(document).ready(function() {
        codigo_movimiento = 0;
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

                                    var no_results = $('<tr class="filterTable_no_results"><td colspan="4">No results.</td></tr>')

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


        //Constructores
        conf_constructor_listado_granjas('cod_info_empresa');
        conf_constructor_listado_sembradores();
        inv_constructor_tipo_semilla();

        $('#dev-table').DataTable({
            order: [
                [0, "desc"]
            ]
        });
        //inv_constructor_listado_proveedores();
        //Máscaras
        //$('.monto').mask("#,##0.000", {reverse: false, maxlength: false});
        $('.monto').mask("9999999.99999");
        $('.cantidad_semillas').mask("9999999999999");
        $('.lote').mask("999999");
        $('.letras').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {
            translation: {
                'S': {
                    pattern: /[A-Za-z-¡!¿?,.() 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/,
                    optional: false
                }
            }
        });
        $('.letras10').mask('SSSSSSSSSS', {
            translation: {
                'S': {
                    pattern: /[A-Za-z 0-9]/,
                    optional: false
                }
            }
        });
        /*----------------------------------------------------------------------------------
    								Validando listboxs
	    ----------------------------------------------------------------------------------*/
        $('.selectpicker.requerido_envio').change(function(event) {
            var objeto = $(this);
            if (objeto.val() == null || objeto.val() == '-b' || objeto.val() == '') {
                objeto.selectpicker('setStyle', 'btn-info', 'remove');
                objeto.selectpicker('setStyle', 'btn-danger');
                $(this).parent('div').addClass('has-error');
            } else {
                objeto.selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');
                objeto.selectpicker('setStyle', 'btn-info');
                objeto.removeClass('campo-vacio');
                $(this).parent('div').removeClass('has-error');
            }
            objeto.selectpicker('refresh');
        });
        $('.selectpicker.requerido_recibido').change(function(event) {
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
        $('.input.requerido_envio').keyup(function(event) {
            if ($(this).val().trim() != "") {
                $(this).parent('div').removeClass('has-error');
            } else {
                $(this).parent('div').addClass('has-error');
            }
        });
        $('.input.requerido_recibido').keyup(function(event) {
            if ($(this).val().trim() != "") {
                $(this).parent('div').removeClass('has-error');
            } else {
                $(this).parent('div').addClass('has-error');
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

        $('#cod_info_empresa').change(function(event) {
            /* Act on the event */
            inv_constructor_listado_proveedores($(this).val());
        });

        $('#btn_nueva_orden').click(function(event) {
            $('#modal_confirmar_limpiar_orden').modal('show');
        });

        $('#btn_confirmar_nueva_orden').click(function(event) {
            /* Act on the event */

            $('#modal_confirmar_limpiar_orden').modal('hide');
            grl_overlay_loading('');
            $('#modal_loading').modal('hide');
            $('#modal_loading').on('hidden.bs.modal', function() {
                inv_vista_orden_compra(0);
            });
        });
        <?php
        if (count($ORDEN)) {
            $cod_info_empresa = str_replace(['[', ']', '"'], '', $_SESSION['cod_info_empresa']);
            $cod_info_empresa = explode(',', $cod_info_empresa);
        ?>
            $('#fecha_orden').val("<?php echo utf8_encode($ORDEN[0]['fecha_orden']); ?>");
            $('#fecha_recibido_pedido').val("<?php echo utf8_encode($ORDEN[0]['fecha_recibido_pedido']); ?>");
            $('#observaciones').val("<?php echo utf8_encode($ORDEN[0]['observaciones']); ?>");
            $('#num_orden_compra').val("<?php echo utf8_encode($ORDEN[0]['num_orden_compra']); ?>");
            $('#cod_info_empresa').selectpicker('val', "<?php echo utf8_encode($ORDEN[0]['cod_info_empresa']); ?>");
            $('#cod_proveedor').selectpicker('val', "<?php echo utf8_encode($ORDEN[0]['cod_proveedor']); ?>");
            $('.selectpicker').selectpicker('refresh');
            //$('#fecha_recibido_pedido').removeAttr('disabled');
            //$('#fecha_orden').attr('disabled','true');
            $('#cod_info_empresa').attr('disabled', 'true');
            $('#cod_proveedor').attr('disabled', 'true');
            $('#div_productos').removeClass('hide');
            <?php
            if ($ORDEN[0]['adjunto_orden_compra'] != null || $ORDEN[0]['adjunto_orden_compra'] != '') {
            ?>
                update_list_item_interface_foto($('#container_upload_box_foto'), "<?php echo '../../mod_inventario/adjuntos/' . $ORDEN[0]['adjunto_orden_compra']; ?>")
            <?php
            }
            if (
                $ORDEN[0]['user_insert'] == $_SESSION['cod_usuario']
                && $ORDEN[0]['cod_estado_orden_compra'] == 1
            ) {
            ?>
                $('.btn-eliminar').removeClass('hide');
            <?php
            }
            if ($ORDEN[0]['cod_estado_orden_compra'] == 1) {
            ?>
                $('#btn_guardar_orden_compra').removeClass('hide');
                $('#btn_enviar_orden_compra').removeClass('hide');
                // $('#btn_cancelar_orden_compra').removeClass('hide');
            <?php
            }
            if ($ORDEN[0]['cod_estado_orden_compra'] == 2) {
            ?>
                $('.btn-recibir').removeClass('hide');
                $('#btn_recibir_orden_compra').removeClass('hide');
                //$('#btn_cancelar_orden_compra').removeClass('hide');
                $('#btn_cancelar_orden_compra').addClass('hide');
                $('#btn_enviar_orden_compra').addClass('hide');

                $('#fecha_recibido_pedido').removeAttr('disabled');
            <?php
            }
            if ($ORDEN[0]['cod_estado_orden_compra'] >= 2) {
            ?>
                $('#btn_guardar_orden_compra').addClass('hide');
                $('#btn_enviar_orden_compra').addClass('hide');

        <?php
            }
        }
        ?>
        codigo_orden_compra = <?php echo $cod_orden_compra; ?>;
        $('#modal_loading').modal('hide');
        jQuery.ajaxSetup({
            async: true
        });
    });

    var array_file_list = [];
    var arrProductosIniciales = {};
    arrProductosIniciales["monto_a_pagar_total_inicial_" + 0] = 0;
    array_file_list.push({
        id: 0,
        file: '',
        file_ext: ''
    });
    var $container_upload_box = $("#container_upload_box");
    var files_foto = '';
    var nombre_foto = '';
    var ext = '';
    var fecha_hoy = new Date(<?php echo time() * 1000; ?>);
    var hoy = (fecha_hoy.getMonth() + 1) + "/" + fecha_hoy.getDate() + "/" + fecha_hoy.getFullYear();
    //var hoy_18_anios_atras        = (fecha_hoy.getMonth()+1) + "/" + fecha_hoy.getDate() + "/" + (fecha_hoy.getFullYear()-18);
    var hoy_default = fecha_hoy.getFullYear() + "-" + (fecha_hoy.getMonth() + 1) + "-" + fecha_hoy.getDate();
    var cod_detalle = 0;
    var flag_tipo_inventario = 0;
    var cantidad_producto = 0;
    var cod_unidad_medida = 0;
    var cod_detalle_producto = 0;

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
                ext = $(this).val().match(/\.([^\.]+)$/)[1];
                ext = ext.toLowerCase();
                update_array_file_list(id, files_foto, ext);
                switch (ext) {
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
                        $container_upload_box.find('.btn-upload-file').data('disabled', true);
                        $container_upload_box.find('i.icon-file').removeClass().addClass('icon-file fa fa-check');
                        $container_upload_box.find('span.msj-btn-file').text('Archivo listo para subir');
                        $container_upload_box.find('.btn-view-file').remove();
                        $container_upload_box.find(".btn-view-file-delete").parent('div').remove();
                        break;
                    default: {
                        grl_mensaje('Tipo de archivo no permitido', 'solo se permiten imágenes, documentos de Word, Excel y PDF.', 'warning');
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

    //Construcción de fechas
    $('.date').datetimepicker({
        //disabledHours: true,
        locale: 'es',
        //minDate: hoy,
        //keepOpen: true,
        format: 'MM-DD-YYYY',
        //defaultDate: hoy + ' 08:00 am',
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
    $('.datetime').datetimepicker({
        locale: 'es',
        format: 'MM-DD-YYYY HH:mm:ss',
        icons: {
            time: "fas fa-clock",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down",
            previous: 'fa fa-arrow-left',
            next: 'fa fa-arrow-right',
        }
    });

    $('.fecha-inicio').datetimepicker({
        locale: moment.locale('en', {
            week: {
                dow: 0
            }
        }),
        //minDate: hoy,
        format: 'MM-DD-YYYY',
        defaultDate: hoy,
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down",
            previous: 'fa fa-arrow-left',
            next: 'fa fa-arrow-right',
        }
    }).on('dp.hide', function(e) {
        $('.fecha-final').data("DateTimePicker").minDate(moment(e.date));
    });
    $('.fecha-final').datetimepicker({
        locale: moment.locale('en', {
            week: {
                dow: 0
            }
        }),
        //minDate: hoy,
        format: 'MM-DD-YYYY',
        defaultDate: hoy,
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down",
            previous: 'fa fa-arrow-left',
            next: 'fa fa-arrow-right',
        }
    }).on('dp.hide', function(e) {
        $('.fecha-inicio').data("DateTimePicker").maxDate(moment(e.date));
    });


    $('#btn_enviar_orden_compra').click(function(event) {
        /* Act on the event */
        var error = 0;
        if (Object.keys(arrProductosIniciales).length == 0) {
            error = 3;
        }
        $(".input.requerido_envio").map(function() {
            let comprobar = false;

            if (!$(this).val() || $(this).val() == '') {
                error = 1;
                $(this).parent('div').addClass('has-error');
                comprobar = true;
                return false;
            } else {
                $(this).parent('div').removeClass('has-error');
            }
        });
        $(".selectpicker.requerido_envio").map(function() {
            if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '') {
                $(this).selectpicker('setStyle', 'btn-info', 'remove');
                $(this).selectpicker('setStyle', 'btn-danger');
                error = 1;
                $(this).parent('div').addClass('has-error');

            } else {
                $(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');
                $(this).selectpicker('setStyle', 'btn-info');
                $(this).removeClass('campo-vacio');
                $(this).parent('div').removeClass('has-error');

            }
            $(this).selectpicker('refresh');
        });
        if (error == 0) {
            grl_overlay_loading('');
            $('#modal_loading').modal('hide');
            $('#modal_loading').on('hidden.bs.modal', function() {
                jQuery.ajaxSetup({
                    async: false
                });
                if (files_foto.length) {
                    var ext = files_foto['0']['name'].match(/\.([^\.]+)$/)[1];
                } else {
                    var ext = '';
                }

                // var cod_orden = inv_guardar_orden_compra(codigo_orden_compra, ext, 2);

                if (Object.keys(arrProductosIniciales).length > 0) {
                    inv_guardar_y_registrar_orden_compra(codigo_orden_compra, ext, 2, arrProductosIniciales)
                }
                if (files_foto.length) {
                    nombre_foto = 'orden_compra_' + cod_orden + '.' + ext;
                    var data = new FormData();
                    $.each(files_foto, function(key, value) {
                        data.append(key, value);
                    });
                    var ext = files_foto['0']['name'].match(/\.([^\.]+)$/)[1];
                    $.ajax({
                        url: 'mod_inventario/funciones/inv_subir_adjunto.php?files_foto&x1=' + nombre_foto,
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
                inv_vista_orden_compra(0);

                jQuery.ajaxSetup({
                    async: true
                });
            });
        } else if (error == 3) {
            grl_mensaje('You must select product for the purchase request', 'Please select at least one', 'warning');

        } else {

            grl_mensaje('You must fill out all marked fields', 'Please check', 'warning');
        }
    });

    $('#btn_guardar_orden_compra').click(function(event) {
        /* Act on the event */
        var error = 0;
        $(".input.requerido_envio").map(function() {
            if (!$(this).val()) {
                error = 1;
                $(this).parent('div').addClass('has-error');
                return false;
            } else {
                $(this).parent('div').removeClass('has-error');
            }
        });
        $(".selectpicker.requerido_envio").map(function() {
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
            grl_overlay_loading('');
            $('#modal_loading').modal('hide');
            $('#modal_loading').on('hidden.bs.modal', function() {
                jQuery.ajaxSetup({
                    async: false
                });
                if (files_foto.length) {
                    var ext = files_foto['0']['name'].match(/\.([^\.]+)$/)[1];
                } else {
                    var ext = '';
                }
                var cod_orden = inv_guardar_orden_compra(codigo_orden_compra, ext, 2);
                if (files_foto.length) {
                    nombre_foto = 'orden_compra_' + cod_orden + '.' + ext;
                    var data = new FormData();
                    $.each(files_foto, function(key, value) {
                        data.append(key, value);
                    });
                    var ext = files_foto['0']['name'].match(/\.([^\.]+)$/)[1];
                    $.ajax({
                        url: 'mod_inventario/funciones/inv_subir_adjunto.php?files_foto&x1=' + nombre_foto,
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
                //inv_notificar_orden_de_compra(cod_orden,$('#cod_info_empresa').val());
                inv_vista_orden_compra(cod_orden);
                jQuery.ajaxSetup({
                    async: true
                });
            });
        } else {
            grl_mensaje('Debe llenar todos los campos marcados', 'favor verificar', 'warning');
        }
    });

    $('#btn_recibir_orden_compra').click(function(event) {
        /* Act on the event */
        var error = 0;
        $(".input.requerido_recibido").map(function() {
            if (!$(this).val()) {
                error = 1;
                $(this).parent('div').addClass('has-error');
                return false;
            } else {
                $(this).parent('div').removeClass('has-error');
            }
        });
        $(".selectpicker.requerido_recibido").map(function() {
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
            $('#modal_confirmar_cerrar').modal('show');
        } else {
            grl_mensaje('Debe llenar todos los campos marcados', 'favor verificar', 'warning');
        }
    });

    $('#btn_add_producto').click(function(event) {
        /* Act on the event */
        cod_detalle = 0;
        $('#modal_producto').modal('show');
        inv_constructor_listado_productos_proveedor($('#cod_proveedor').val());
        inv_constructor_listado_unidades_medida();
    });

    $('#btn_guardar_producto').click(function(event) {
        /* Act on the event */
        var error = 0;
        $(".input.requerido_producto").map(function() {
            if (!$(this).val()) {
                error = 1;
                $(this).parent('div').addClass('has-error');
                return false;
            } else {
                $(this).parent('div').removeClass('has-error');
            }
        });
        $(".selectpicker.requerido_producto").map(function() {
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
            $('#modal_producto').modal('hide');
            grl_overlay_loading('');
            $('#modal_loading').modal('hide');
            $('#modal_loading').on('hidden.bs.modal', function() {
                inv_guardar_producto_orden_compra(codigo_orden_compra, cod_detalle);
            });
        } else {
            grl_mensaje('Debe llenar todos los campos marcados', 'favor verificar', 'warning');
        }
    });

    $('#div_paginacion').on('click', '.paginate_button', function(event) {
        event.preventDefault();
        jQuery.ajaxSetup({
            async: false
        });
        inv_vista_orden_compra(0, $(this).data('inicio'), $(this).data('limite'));
        $('.current').removeClass('current');
        $(this).addClass('current');
        jQuery.ajaxSetup({
            async: true
        });
    });

    $('#modal_recibir_entrega').on('shown.bs.modal', function(event) {
        jQuery.ajaxSetup({
            async: false
        });
        inv_constructor_listado_productos_proveedor($('#cod_proveedor').val(), 'cod_producto_recibido');
        inv_constructor_listado_unidades_medida('cod_unidad_medida_recibido');
        $('.selectpicker').selectpicker('refresh');
        $('#cod_producto_recibido').selectpicker('val', cod_detalle_producto);
        $('#cod_unidad_medida_recibido').selectpicker('val', cod_unidad_medida);
        $('#cantidad_recibido').val(cantidad_producto);
        jQuery.ajaxSetup({
            async: true
        });
    });

    $('#btn_guardar_producto_recibido').click(function(event) {
        /* Act on the event */
        var error = 0;
        $(".input.requerido_recibir_entrega").map(function() {
            if (!$(this).val()) {
                error = 1;
                $(this).parent('div').addClass('has-error');
                return false;
            } else {
                $(this).parent('div').removeClass('has-error');
            }
        });
        $(".selectpicker.requerido_recibir_entrega").map(function() {
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
            $('#modal_recibir_entrega').modal('hide');
            grl_overlay_loading('');
            $('#modal_loading').modal('hide');
            $('#modal_loading').on('hidden.bs.modal', function() {
                inv_guardar_producto_recibido_orden_compra(codigo_orden_compra, cod_detalle);
            });
        } else {
            grl_mensaje('Debe llenar todos los campos marcados', 'favor verificar', 'warning');
        }
    });

    $('#modal_detalle_entrega').on('shown.bs.modal', function(event) {
        jQuery.ajaxSetup({
            async: false
        });
        inv_constructor_listado_productos_proveedor($('#cod_proveedor').val(), 'cod_producto_entregado');
        inv_constructor_listado_unidades_medida('cod_unidad_medida_entregado');
        $('.selectpicker').selectpicker('refresh');
        $('#cod_producto_entregado').selectpicker('val', cod_detalle_producto);
        $('#cod_unidad_medida_entregado').selectpicker('val', cod_unidad_medida);
        inv_cargar_detalle_entrega_producto(cod_detalle);
        jQuery.ajaxSetup({
            async: true
        });
    });

    $('#btn_cancelar_orden_compra').click(function(event) {
        /* Act on the event */
        var error = 0;
        $(".input.requerido_envio").map(function() {
            if (!$(this).val()) {
                error = 1;
                $(this).parent('div').addClass('has-error');
                return false;
            } else {
                $(this).parent('div').removeClass('has-error');
            }
        });
        $(".selectpicker.requerido_envio").map(function() {
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
            grl_overlay_loading('');
            $('#modal_loading').modal('hide');
            $('#modal_loading').on('hidden.bs.modal', function() {
                jQuery.ajaxSetup({
                    async: false
                });
                var cod_orden = inv_guardar_orden_compra(codigo_orden_compra, ext, 4);
                inv_vista_orden_compra(cod_orden);
                jQuery.ajaxSetup({
                    async: true
                });
            });
        } else {
            grl_mensaje('Debe llenar todos los campos marcados', 'favor verificar', 'warning');
        }
    });

    $('#btn_confirmar_cerrar').click(function(event) {
        /* Act on the event */
        jQuery.ajaxSetup({
            async: false
        });
        $('#modal_confirmar_cerrar').modal('hide');
        grl_overlay_loading('');
        $('#modal_loading').modal('hide');
        $('#modal_loading').on('hidden.bs.modal', function() {
            if (files_foto.length) {
                var ext = files_foto['0']['name'].match(/\.([^\.]+)$/)[1];
            } else {
                var ext = '';
            }
            inv_guardar_orden_compra(codigo_orden_compra, ext, 3);
            if (files_foto.length) {
                nombre_foto = 'orden_compra_' + codigo_orden_compra + '.' + ext;
                var data = new FormData();
                $.each(files_foto, function(key, value) {
                    data.append(key, value);
                });
                var ext = files_foto['0']['name'].match(/\.([^\.]+)$/)[1];
                $.ajax({
                    url: 'mod_inventario/funciones/inv_subir_adjunto.php?files_foto&x1=' + nombre_foto,
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
            inv_vista_orden_compra(codigo_orden_compra);
            jQuery.ajaxSetup({
                async: true
            });
        });
    });

    $('#cod_producto_iniciales').change(function(event) {
        $("#precio_actual_semilla").val(parseFloat($("#cod_producto_iniciales option:selected").data("precio")).toLocaleString(undefined, {
            minimumFractionDigits: 5
        }));

    });

    var selectListaInvernadero = $("#cod_info_empresa");
    var selectListaProveedores = $("#cod_proveedor");
    var selectListaProductosInicales = $("#cod_producto_iniciales");
    var divFormularioProductosIniciales = $("#formulario_productos_iniciales");

    var divTablaProductosIniciales = $("#div_productos_iniciales");
    var btnAgregarNuevoProducto = $("#btn_agregar_producto_inicial");

    var trCuerpoTablaProductosIniciales = document.getElementById("cuerpo_interno_productos_iniciales");
    var productosInicialesAgregados = new Map();
    var indiceProductoInicial = 0;
    selectListaProveedores.change(function() {

        if (selectListaProveedores.val() === "-b") {
            divFormularioProductosIniciales.hide(200);

            // Limpiar registro de productos
            selectListaProductosInicales.empty();
            selectListaProductosInicales.append(new Option('Select', '-b'))
        } else {
            inv_constructor_listado_productos_proveedor($('#cod_proveedor').val(), "cod_producto_iniciales");
            inv_constructor_listado_unidades_medida();

            divFormularioProductosIniciales.show(200);
            // alert("No se seleccionó la opción 1");
        }
        trCuerpoTablaProductosIniciales.innerHTML = "";
        divTablaProductosIniciales.hide(200);
        arrProductosIniciales = {};
        arrProductosIniciales["monto_a_pagar_total_inicial_" + 0] = 0;
        indiceProductoInicial = 0;
        $('.selectpicker').selectpicker('refresh');

    });
    selectListaInvernadero.change(function() {

        divFormularioProductosIniciales.hide(0);

        // Limpiar registro de productos
        selectListaProductosInicales.empty();
        selectListaProductosInicales.append(new Option('Select', '-b'))

        trCuerpoTablaProductosIniciales.innerHTML = "";
        divTablaProductosIniciales.hide(0);
        arrProductosIniciales = {};
        arrProductosIniciales["monto_a_pagar_total_inicial_" + 0] = 0;
        indiceProductoInicial = 0;
        $('.selectpicker').selectpicker('refresh');

    });

    btnAgregarNuevoProducto.on("click", function() {

        /* Act on the event */
        var error = 0;
        $(".input.requerido_cantidad_inicial").map(function() {
            if (!$(this).val()) {
                error = 1;
                $(this).parent('div').addClass('has-error');
                return false;
            } else {
                $(this).parent('div').removeClass('has-error');
            }
        });
        $(".selectpicker.requerido_producto_inicial").map(function() {
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
        $(".selectpicker.requerido").map(function() {
            if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '') {
                $(this).selectpicker('setStyle', 'btn-info', 'remove');
                $(this).selectpicker('setStyle', 'btn-danger');
                $(this).parent('div').addClass('has-error');

                error = 1;
            } else {
                $(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');
                $(this).selectpicker('setStyle', 'btn-info');
                $(this).removeClass('campo-vacio');
                $(this).parent('div').removeClass('has-error');

            }
            $(this).selectpicker('refresh');
        });
        $(".selectpicker.requerido_unidad_medida_inicial").map(function() {
            if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '') {
                $(this).selectpicker('setStyle', 'btn-info', 'remove');
                $(this).selectpicker('setStyle', 'btn-danger');
                error = 1;
                $(this).parent('div').addClass('has-error');
            } else {
                $(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');
                $(this).selectpicker('setStyle', 'btn-info');
                $(this).removeClass('campo-vacio');
                $(this).parent('div').removeClass('has-error');
            }
            $(this).selectpicker('refresh');
        });

        if (error == 0) {
            arrProductosIniciales["codigo_producto_inicial_" + indiceProductoInicial] = selectListaProductosInicales.val();

            // Guardando los valores
            arrProductosIniciales["codigo_unida_medida_inicial_" + indiceProductoInicial] = $("#cod_unidad_medida").val();
            arrProductosIniciales["codigo_tipo_semilla_inicial_" + indiceProductoInicial] = $("#cod_tipo_semilla").val();
            arrProductosIniciales["cantidad_inicial_" + indiceProductoInicial] = $("#cantidad").val();
            arrProductosIniciales["precio_actual_unidad_inicial_" + indiceProductoInicial] = parseFloat($("#precio_actual_semilla").val());

            arrProductosIniciales["monto_a_pagar_inicial_" + indiceProductoInicial] = $("#cantidad").val() * parseFloat($("#precio_actual_semilla").val());
            arrProductosIniciales["monto_a_pagar_total_inicial_" + 0] += arrProductosIniciales["monto_a_pagar_inicial_" + indiceProductoInicial];

            arrProductosIniciales["nombre_producto_inicial_" + indiceProductoInicial] = $("#cod_producto_iniciales option:selected").text();
            arrProductosIniciales["nombre_unida_medida_inicial_" + indiceProductoInicial] = $("#cod_unidad_medida option:selected").text();
            arrProductosIniciales["nombre_tipo_semilla_inicial_" + indiceProductoInicial] = $("#cod_tipo_semilla option:selected").text();
            arrProductosIniciales["producto_activo_" + indiceProductoInicial] = 1;

            divTablaProductosIniciales.show(200);
            indiceProductoInicial = indiceProductoInicial + 1;

            trCuerpoTablaProductosIniciales.innerHTML = "";
            $("#cantidad_total_pagar").val(parseFloat(arrProductosIniciales["monto_a_pagar_total_inicial_" + 0]).toLocaleString(undefined, {
                minimumFractionDigits: 5
            }));
            for (var index = 0; index < indiceProductoInicial; index++) {
                if (arrProductosIniciales["producto_activo_" + index] == 1) {

                    trCuerpoTablaProductosIniciales.innerHTML += `
                <tr>
                    <td>${index+1}</td>
                    <td><a style="text-decoration: none; color: black; cursor: pointer;">${arrProductosIniciales['codigo_producto_inicial_'+index]}</a></td>
                    <td>${arrProductosIniciales['nombre_producto_inicial_'+index]}</td>
                    <td>${parseInt(arrProductosIniciales['cantidad_inicial_' + index]).toLocaleString()}</td>
                    <td>$ ${parseFloat(arrProductosIniciales['precio_actual_unidad_inicial_' + index]).toLocaleString(undefined, {
                        minimumFractionDigits: 5
                    })}</td>
                    <td>$ ${parseFloat(arrProductosIniciales['monto_a_pagar_inicial_' + index]).toLocaleString(undefined, {
                        minimumFractionDigits: 5
                    })}</td>
                    <td>${arrProductosIniciales['nombre_unida_medida_inicial_'+index]}</td>
                    <td>${arrProductosIniciales['nombre_tipo_semilla_inicial_'+index]}</td>
                    <td>
                        <button class="btn_eliminar_semilla_editable" onclick="eliminarSemilla(${index})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                `;
                }
            }

        } else {
            grl_mensaje('You must fill out all marked fields', 'Please check', 'warning');

        }

    });

    function eliminarSemilla(indiceSemillas) {
        arrProductosIniciales["producto_activo_" + indiceSemillas] = 0;
        dibujarTabla();
    }

    function dibujarTabla() {
        jQuery.ajaxSetup({
            async: false
        });

        // Generar la nueva tabla con los datos recibidos
        trCuerpoTablaProductosIniciales.innerHTML = '';
        arrProductosIniciales["monto_a_pagar_inicial_" + indiceProductoInicial] = 0;
        arrProductosIniciales["monto_a_pagar_total_inicial_" + 0] = 0;
        let indiciCorrelativo = 1;
        let tablaConRegistro = false;
        for (var index = 0; index < indiceProductoInicial; index++) {

            if (arrProductosIniciales["producto_activo_" + index] == 1) {
                tablaConRegistro = true;
                arrProductosIniciales["monto_a_pagar_inicial_" + index] = arrProductosIniciales['cantidad_inicial_' + index] * arrProductosIniciales['precio_actual_unidad_inicial_' + index];
                arrProductosIniciales["monto_a_pagar_total_inicial_" + 0] += arrProductosIniciales["monto_a_pagar_inicial_" + index];

                trCuerpoTablaProductosIniciales.innerHTML += `
                <tr>
                    <td>${indiciCorrelativo}</td>
                    <td><a style="text-decoration: none; color: black; cursor: pointer;">${arrProductosIniciales['codigo_producto_inicial_'+index]}</a></td>
                    <td>${arrProductosIniciales['nombre_producto_inicial_'+index]}</td>
                    <td>${parseInt(arrProductosIniciales['cantidad_inicial_' + index]).toLocaleString()}</td>
                    <td>$ ${parseFloat(arrProductosIniciales['precio_actual_unidad_inicial_' + index]).toLocaleString(undefined, {
                        minimumFractionDigits: 5
                    })}</td>
                    <td>$ ${parseFloat(arrProductosIniciales['monto_a_pagar_inicial_' + index]).toLocaleString(undefined, {
                        minimumFractionDigits: 5
                    })}</td>
                    <td>${arrProductosIniciales['nombre_unida_medida_inicial_'+index]}</td>
                    <td>${arrProductosIniciales['nombre_tipo_semilla_inicial_'+index]}</td>
                    <td>
                        <button class="btn_eliminar_semilla_editable" onclick="eliminarSemilla(${index})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                `;
                indiciCorrelativo++;
            }
        }
        $("#cantidad_total_pagar").val(parseFloat(arrProductosIniciales["monto_a_pagar_total_inicial_" + 0]).toLocaleString(undefined, {
            minimumFractionDigits: 5
        }));
        if (!tablaConRegistro) {
            $("#cantidad_total_pagar").val("0");
        }
        jQuery.ajaxSetup({
            async: true
        });
    }
</script>

<body>
    <div id="overlay_loading"></div>
    <div id="message_box"></div>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-md-12">
                <div class="panel-header">
                    <h1 class="translate" data-traducir_english="Purchase request" data-traducir_spanish="Orden de compra">Orden de compra</h1>

                    <!-- <span class="badge"><?php echo $ORDEN[0]['estado'] ?></span> -->
                </div>
            </div>
            <div class="col-md-12">
                <!-- Fila #1 -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group input-group-sm">
                            <label for="cod_info_empresa" class="translate" data-traducir_english="Ship to Location" data-traducir_spanish="Luvar de envío">Luvar de envío</label>
                            <select class="selectpicker show-menu-arrow requerido_envio cod_info_empresa" data-live-search="true" title="Select" id="cod_info_empresa" name="cod_info_empresa">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group input-group-sm">
                            <label for="cod_proveedor" class="translate" data-traducir_english="Vendor" data-traducir_spanish="Proveedor">Proveedor</label>
                            <select class="selectpicker show-menu-arrow requerido_envio cod_info_empresa" data-live-search="true" title="Select" id="cod_proveedor" name="cod_proveedor">
                            </select>
                        </div>
                    </div>
                    <div class='col-md-2'>
                        <div class="form-group">
                            <label for="fecha_orden" class="translate" data-traducir_english="PO Request Date" data-traducir_spanish="Fecha Solicitada">Fecha Solicitada</label>
                            <div class='input-group input-group-sm fecha-inicio' id='datetimepicker1'>
                                <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </span>
                                <input type='text' class="form-control input requerido_envio" id="fecha_orden" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group input-group-sm">
                            <label for="num_orden_compra" class="translate" data-traducir_english="PO #" data-traducir_spanish="No. Orden de Compra">No. Orden de Compra</label>
                            <!-- <input type="text" class="form-control letras10 input requerido_recibido" id="num_orden_compra" name="num_orden_compra"> -->
                            <input type="text" class="form-control letras10 input requerido_envio" id="num_orden_compra" name="num_orden_compra">
                        </div>
                    </div>
                    <div class='col-md-2'>
                        <div class="form-group">
                            <label for="fecha_recibido_pedido" class="translate" data-traducir_english="Final Date" data-traducir_spanish="Fecha Recibido">Fecha Recibido</label>
                            <div class='input-group input-group-sm fecha-final' id='datetimepicker2'>
                                <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </span>
                                <!-- <input type='text' class="form-control input requerido_recibido" disabled="disabled" id="fecha_recibido_pedido" /> -->
                                <input type='text' class="form-control input requerido_envio" id="fecha_recibido_pedido" />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fila #2 -->
                <div class="row">
                    <div class="col-md-12" id="">
                        <label for="observaciones" class="translate" data-traducir_english="Observations" data-traducir_spanish="Observaciones">Observaciones</label>
                        <div class="form-group show-tick">
                            <textarea maxlength="2500" align="left" style="height:100px; width:100%; resize: none;" class="form-control limpiar requerido_perdida placeholder_translate" rows="2" id="observaciones" placeholder="Observaciones" style="width: 100%" data-placeholder_en="Observations" data-placeholder_es="Observaciones"></textarea>
                        </div>
                    </div>
                </div>
                <!-- Fila #3 -->
                <div class="row">
                    <div id="container_upload_box_foto">
                        <div class="col-md-12">
                            <label class="translate" data-traducir_english="Purchase request" data-traducir_spanish="Orden de compra">Orden de compra</label>
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
                <!-- Fila #4 (Formulario oculto de ingreso de producto) -->
                <div id="formulario_productos_iniciales" hidden class="row">
                    <div class="col-md-2">
                        <div class="form-group input-group-sm">
                            <label for="cod_producto_iniciales" class="translate" data-traducir_english="Product" data-traducir_spanish="Producto">Producto</label>
                            <select class="selectpicker show-menu-arrow requerido_producto_inicial" data-live-search="true" title="Select" id="cod_producto_iniciales" name="cod_producto">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group input-group-sm">
                            <label for="cod_unidad_medida" class="translate" data-traducir_english="Unit of measure" data-traducir_spanish="Unidad medida">Unidad medida</label>
                            <select class="selectpicker show-menu-arrow requerido_unidad_medida_inicial" data-live-search="true" title="Select" id="cod_unidad_medida" name="cod_unidad_medida">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group input-group-sm" style="margin-bottom: 25px">
                            <label for="cantidad" class="translate" data-traducir_english="Quantity" data-traducir_spanish="Cantidad">Cantidad</label>
                            <input type="text" class="form-control cantidad_semillas input requerido_cantidad_inicial" id="cantidad" name="cantidad">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group input-group-sm" style="margin-bottom: 25px">
                            <label for="precio_actual_semilla" class="translate" data-traducir_english="Price" data-traducir_spanish="Precio">Precio</label>
                            <input type="text" class="form-control monto input requerido_cantidad_inicial" id="precio_actual_semilla" name="precio_actual_semilla">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group input-group-sm">
                            <label for="cod_tipo_semilla" class="translate" data-traducir_english="Seed type" data-traducir_spanish="Tipo de semilla">Tipo de semilla</label>
                            <select class="selectpicker show-menu-arrow requerido" data-live-search="true" title="Select" id="cod_tipo_semilla" name="cod_tipo_semilla">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-center" style="margin-top: 25px">
                        <button id="btn_agregar_producto_inicial" class="smooth-transition btn-select-file" style="background-color: #007944 !important; border-color: #007944 !important;">
                            <span class="translate" data-traducir_english="Add product" data-traducir_spanish="Agregar producto"> Agregar producto</span>
                        </button>
                    </div>
                </div>
                <!-- Tabla de productos sin guardar -->
                <div class="row " hidden id="div_productos_iniciales">
                    <div class="col-md-12">
                        <div class="panel panel-primary" id="panel_itemschecklist_productos_sin_guardar">
                            <div class="panel-heading btncollapsepaso" id="a_itemschecklist_productos_sin_guardar" role="button" data-toggle="collapse" href="#colapsarListaProductosIniciales" aria-expanded="true" data-parent="#accordion" aria-controls="colapsarListaProductosIniciales">
                                <h3 class="panel-title translate" data-traducir_english='Products' data-traducir_spanish='Productos'>Productos</h3>
                            </div>
                            <div class="panel-collapse collapse" id="colapsarListaProductosIniciales" role="tabpanel" aria-labelledby="colapsarListaProductosIniciales">
                                <div class="panel-body">
                                    <div class="responsive_table_container">
                                        <table class="table display row-border responsive">
                                            <thead>
                                                <tr class="active info">
                                                    <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
                                                    <th width="10%" class="translate" data-traducir_english="Code" data-traducir_spanish="Código">Código</th>
                                                    <th width="30%" class="translate" data-traducir_english="Seed" data-traducir_spanish="Nombre">Nombre</th>
                                                    <th width="10%" class="translate" data-traducir_english="Quantity" data-traducir_spanish="Cantidad">Cantidad</th>
                                                    <th width="10%" class="translate" data-traducir_english="Unit Price" data-traducir_spanish="Precio">Precio</th>
                                                    <th width="10%" class="translate" data-traducir_english="Payable" data-traducir_spanish="Monto a pagar">Monto a pagar</th>
                                                    <th width="10%" class="translate" data-traducir_english="Units" data-traducir_spanish="Unidad medida">Unidad medida</th>
                                                    <th width="10%" class="translate" data-traducir_english="Seed type" data-traducir_spanish="Tipo de semilla">Tipo de semilla</th>
                                                    <th width="5%" class="translate" data-traducir_english="" data-traducir_spanish=""></th>
                                                </tr>
                                            </thead>
                                            <tbody id="cuerpo_interno_productos_iniciales">

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tabla de productos (ya guardados) -->
                <div class="row hide" id="div_productos">
                    <div class="col-md-12">
                        <div class="panel panel-primary" id="panel_itemschecklist_productos_guardados">
                            <div class="panel-heading btncollapsepaso" id="a_itemschecklist_productos_guardados" role="button" data-toggle="collapse" href="#collapseitemschecklist_productos_guardados" aria-expanded="true" data-parent="#accordion" aria-controls="collapseitemschecklist_productos_guardados">
                                <h3 class="panel-title translate" data-traducir_english='Products' data-traducir_spanish='Productos'>Productos</h3>
                            </div>
                            <div class="panel-collapse collapse" id="collapseitemschecklist_productos_guardados" role="tabpanel" aria-labelledby="collapseitemschecklist_productos_guardados">
                                <div class="panel-body">
                                    <div class="responsive_table_container">
                                        <table class="table display row-border responsive" id="tabla_itemschecklist_productos_guardados">
                                            <thead>
                                                <tr class="active info">
                                                    <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
                                                    <th width="10%" class="translate" data-traducir_english="Code" data-traducir_spanish="Código">Código</th>
                                                    <th width="30%" class="translate" data-traducir_english="Seed" data-traducir_spanish="Nombre">Nombre</th>
                                                    <th width="10%" class="translate" data-traducir_english="Quantity" data-traducir_spanish="Cantidad">Cantidad</th>
                                                    <th width="10%" class="translate" data-traducir_english="Unit Price" data-traducir_spanish="Precio">Precio</th>
                                                    <th width="10%" class="translate" data-traducir_english="Payable" data-traducir_spanish="Monto a pagar">Monto a pagar</th>
                                                    <th width="10%" class="translate" data-traducir_english="Units" data-traducir_spanish="Unidad medida">Unidad medida</th>
                                                    <th width="10%" class="translate" data-traducir_english="Seed type" data-traducir_spanish="Tipo de semilla">Tipo de semilla</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (count($PRODUCTOS)) {
                                                    $correlativo = 1;
                                                    foreach ($PRODUCTOS as $producto) {
                                                        $cantidadTotalAPagar = $cantidadTotalAPagar +  floatval($producto['monto_pago_inmutable']);

                                                        if ($producto['flag_tipo_inventario'] == 1) //Producto Químico
                                                        {
                                                ?>
                                                            <tr>
                                                                <td><?php echo $correlativo; ?></td>
                                                                <td><a style="text-decoration: none; color: black; cursor: pointer;" onclick="cod_detalle = <?php echo utf8_encode($producto['cod_detalle']); ?>;cod_unidad_medida = <?php echo utf8_encode($producto['cod_unidad_medida']); ?>;cod_detalle_producto = <?php echo utf8_encode($producto['cod_detalle_producto']); ?>;$('#modal_detalle_entrega').modal('show');"><?php echo utf8_encode($producto['cod_quimico']); ?></a></td>
                                                                <td><?php echo utf8_encode($producto['nombre_quimico']); ?></td>
                                                                <td>Chemical - Químico</td>
                                                                <td><?php echo ($producto['cantidad']); ?></td>
                                                                <td><?php echo utf8_encode($producto['unidad_medida']); ?></td>
                                                                <td><?php echo ($producto['cantidad_recibida']); ?></td>

                                                            </tr>
                                                        <?php
                                                        }
                                                        if ($producto['flag_tipo_inventario'] == 0) //Producto Maquinaria
                                                        {
                                                        ?>
                                                            <tr>
                                                                <td><?php echo $correlativo; ?></td>
                                                                <td><a style="text-decoration: none; color: black; cursor: pointer;" onclick="cod_detalle = <?php echo utf8_encode($producto['cod_detalle']); ?>;cod_unidad_medida = <?php echo utf8_encode($producto['cod_unidad_medida']); ?>;cod_detalle_producto = <?php echo utf8_encode($producto['cod_detalle_producto']); ?>;$('#modal_detalle_entrega').modal('show');"><?php echo utf8_encode($producto['nombre_maquinaria']); ?></a></td>
                                                                <td><?php echo utf8_encode($producto['codigo_maquinaria']); ?></td>
                                                                <td>Machinery - Maquinaria</td>
                                                                <td><?php echo utf8_encode($producto['cantidad']); ?></td>
                                                                <td><?php echo utf8_encode($producto['unidad_medida']); ?></td>
                                                                <td><?php echo utf8_encode($producto['cantidad_recibida']); ?></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                        if ($producto['flag_tipo_inventario'] == 2) //Producto Semilla
                                                        {
                                                        ?>
                                                            <tr>
                                                                <td><?php echo $correlativo; ?></td>
                                                                <td><a style="text-decoration: none; color: black; cursor: pointer;" onclick="cod_detalle = <?php echo utf8_encode($producto['cod_detalle']); ?>;cod_unidad_medida = <?php echo utf8_encode($producto['cod_unidad_medida']); ?>;cod_detalle_producto = <?php echo utf8_encode($producto['cod_detalle_producto']); ?>;$('#modal_detalle_entrega').modal('show');"><?php echo utf8_encode($producto['codigo_semilla']); ?></a></td>
                                                                <td><?php echo utf8_encode($producto['nombre_semilla']); ?></td>
                                                                <td><?php echo  number_format($producto['cantidad']); ?></td>
                                                                <td>$ <?php echo  number_format($producto['precio_semilla'], 5); ?></td>
                                                                <td>$ <?php echo  number_format($producto['monto_pago_inmutable'], 5); ?></td>
                                                                <td><?php echo utf8_encode($producto['unidad_medida']); ?></td>
                                                                <td><?php echo  $producto['tipo_semilla']; ?></td>

                                                            </tr>
                                                <?php
                                                        }
                                                        $correlativo++;
                                                    }
                                                }
                                                ?>
                                                <script>
                                                    $("#cantidad_total_pagar").val('$ <?php echo number_format($cantidadTotalAPagar, 5); ?>')
                                                </script>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row nomargin row-add-content">
                                        <div class="col-xs-12 text-center">
                                            <button type="button" id="btn_add_producto" class="btn btn-sm btn-success truncated-text btn_add btn-eliminar hide" data-action="true">
                                                <i class="fa fa-plus-circle"> </i>
                                                <span class="translate" data-traducir_english="Add Product" data-traducir_spanish="Añadir Producto">Añadir Producto</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group input-group-sm">
                            <label for="cantidad_total_pagar" class="translate" data-traducir_english="Total payable" data-traducir_spanish="Total a pagar">Total a pagar</label>
                            <input value="0" type="text" disabled class="form-control " id="cantidad_total_pagar" name="cantidad_total_pagar">
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <div class="panel-footer" align="right">
            <button class="btn btn-sm btn-primary translate" data-traducir_english="New Purchase " data-traducir_spanish="Nueva Orden" type="button" style="background-color: #007944 !important; border-color: #007944 !important;" id="btn_nueva_orden">Nueva orden</button><!-- - DEV -->
            <button class="btn btn-sm btn-primary translate" data-traducir_english="Save and Send" data-traducir_spanish="Guardar y Enviar" type="button" id="btn_enviar_orden_compra">Guardar y Enviar</button><!-- - DEV -->
        </div>
    </div>

    <div class="busqueda_contenedor " style="margin-left: 10px; margin-right: 10px;">
        <div class="row" style="min-width:100px;">
            <div class="col-sm-12">
                <div class="panel panel-primary" id="panel_itemschecklist">
                    <div class="panel-heading btncollapsepaso panel_cabecera" id="a_itemschecklist" role="button" data-toggle="collapse" href="#collapseitemschecklist" data-target="#collapseitemschecklist" aria-expanded="true" data-parent="#panel_itemschecklist" aria-controls="collapseitemschecklist">
                        <h3 class="panel-title translate" data-traducir_english="Purchase Request" data-traducir_spanish="Ordenes de Compra">Ordenes de Compra</h3>
                        <div class="pull-right">
                            <span class="clickable filter" data-toggle="tooltip" title="Search" data-container="body">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </div>
                    <div class="panel-collapse collapse" id="collapseitemschecklist" role="tabpanel" aria-labelledby="collapseitemschecklist">
                        <div class="panel-body panel_cuerpo">
                            <input type="text" class="form-control" id="tabla_itemschecklist-filter" data-action="filter" data-filters="#tabla_itemschecklist" placeholder=Search />
                        </div>
                    </div>
                    <div class="responsive_table_container">
                        <table class="table display row-border table-responsive table-hover table-condensed" id="tabla_itemschecklist">
                            <thead>
                                <tr class="active info">
                                    <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
                                    <th width="10%" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</th>
                                    <th width="10%" class="translate" data-traducir_english="PO#" data-traducir_spanish="No. Orden Compra">No. Orden Compra</th>
                                    <th width="10%" class="translate" data-traducir_english="Vendor" data-traducir_spanish="Proveedor">Proveedor</th>
                                    <th width="10%" class="translate" data-traducir_english="Contact" data-traducir_spanish="Contacto">Contacto</th>
                                    <th width="10%" class="translate" data-traducir_english="Vendor e-mail" data-traducir_spanish="Correo electronico">Correo electronico</th>
                                    <th width="10%" class="translate" data-traducir_english="Date request" data-traducir_spanish="Fecha pedido">Fecha pedido</th>
                                    <th width="10%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>
                                    <th width="10%" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpo_tabla" style="cursor: pointer;">
                                <?php
                                if (count($ORDENES)) {
                                    $correlativo = 1;
                                    foreach ($ORDENES as $orden) {
                                ?>
                                        <tr>
                                            <td onclick="inv_vista_orden_compra(<?php echo utf8_encode($orden['cod_orden']); ?>)"><?php echo $orden['cod_orden']; ?></td>
                                            <td onclick="inv_vista_orden_compra(<?php echo utf8_encode($orden['cod_orden']); ?>)"><?php echo utf8_encode($orden['nombre_empresa']); ?></td>
                                            <td onclick="inv_vista_orden_compra(<?php echo utf8_encode($orden['cod_orden']); ?>)"><?php echo utf8_encode($orden['num_orden_compra']); ?></td>
                                            <td onclick="inv_vista_orden_compra(<?php echo utf8_encode($orden['cod_orden']); ?>)"><?php echo utf8_encode($orden['nombre_proveedor']); ?></td>
                                            <td onclick="inv_vista_orden_compra(<?php echo utf8_encode($orden['cod_orden']); ?>)"><?php echo utf8_encode($orden['nombre_contacto']); ?></td>
                                            <td onclick="inv_vista_orden_compra(<?php echo utf8_encode($orden['cod_orden']); ?>)"><?php echo utf8_encode($orden['correo_contacto']); ?></td>
                                            <td onclick="inv_vista_orden_compra(<?php echo utf8_encode($orden['cod_orden']); ?>)"><?php echo utf8_encode($orden['fecha_orden']); ?></td>
                                            <td onclick="inv_vista_orden_compra(<?php echo utf8_encode($orden['cod_orden']); ?>)"><?php echo utf8_encode($orden['nombre_usuario']); ?></td>
                                            <td>
                                                <div class="material-switch pull-right">
                                                    <input class="checkbox" onclick="inv_cambiar_estado_completado_orden('<?php echo $orden['cod_orden']; ?>', <?php echo ($orden['completada'] == 1 ? 0 : 1); ?>)" id="checkbox_<?php echo $orden['cod_orden']; ?>" data-id="<?php echo $orden['cod_orden']; ?>" name="checkbox_<?php echo $orden['cod_orden']; ?>" type="checkbox" <?php echo ($orden['completada'] == 1 ? 'checked="checked"' : ''); ?> />
                                                    <label for="checkbox_<?php echo $orden['cod_orden']; ?>" class=""></label>
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

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal_producto" id="modal_producto" tabindex="-1" role="dialog" aria-labelledby="ModalResponsables" aria-hidden="true">
        <div class="modal-dialog modal-success modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><span id="titulo_modal_responsables" class="translate" data-traducir_english="Product" data-traducir_spanish="Producto">Producto</span> <small class="subtitulo" id="subtitulo_modal_responsables"></small></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group input-group-sm col-xs-12">
                            <!-- <label for="cod_producto" class="translate" data-traducir_english="Product" data-traducir_spanish="Producto">Producto</label>
                            <select class="selectpicker show-menu-arrow requerido_producto" data-live-search="true" title="Select" id="cod_producto" name="cod_producto">
                            </select> -->
                        </div>
                        <div class="form-group input-group-sm col-xs-12">
                            <!-- <label for="cod_unidad_medida" class="translate" data-traducir_english="Unit of measure" data-traducir_spanish="Unidad medida">Unidad medida</label>
                            <select class="selectpicker show-menu-arrow requerido_producto" data-live-search="true" title="Select" id="cod_unidad_medida" name="cod_unidad_medida">
                            </select> -->
                        </div>
                        <div class="form-group input-group-sm col-xs-12">
                            <div class="form-group input-group-sm">
                                <!-- <label for="cantidad" class="translate" data-traducir_english="Quantity" data-traducir_spanish="Cantidad">Cantidad</label>
                                <input type="text" class="form-control monto input requerido_producto" id="cantidad" name="cantidad"> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-sm btn-primary btn_guardar translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_guardar_producto">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal_recibir_entrega" id="modal_recibir_entrega" tabindex="-1" role="dialog" aria-labelledby="ModalResponsables" aria-hidden="true">
        <div class="modal-dialog modal-success modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><span id="titulo_modal_responsables" class="translate" data-traducir_english="Product" data-traducir_spanish="Producto">Producto</span> <small class="subtitulo" id="subtitulo_modal_responsables"></small></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group input-group-sm col-xs-12">
                            <label for="cod_producto_recibido" class="translate" data-traducir_english="Product" data-traducir_spanish="Producto">Producto</label>
                            <select disabled="disabled" class="selectpicker show-menu-arrow requerido_recibir_entrega" data-live-search="true" title="Select" id="cod_producto_recibido" name="cod_producto_recibido">
                            </select>
                        </div>
                        <div class="form-group input-group-sm col-xs-12">
                            <label for="cod_unidad_medida_recibido" class="translate" data-traducir_english="Unit of measure" data-traducir_spanish="Unidad medida">Unidad medida</label>
                            <select disabled="disabled" class="selectpicker show-menu-arrow requerido_recibir_entrega" data-live-search="true" title="Select" id="cod_unidad_medida_recibido" name="cod_unidad_medida_recibido">
                            </select>
                        </div>
                        <div class="form-group input-group-sm col-xs-12">
                            <div class="form-group input-group-sm">
                                <label for="cantidad_recibido" class="translate" data-traducir_english="Quantity" data-traducir_spanish="Cantidad">Cantidad</label>
                                <input type="text" class="form-control monto input requerido_recibir_entrega" id="cantidad_recibido" name="cantidad_recibido">
                            </div>
                        </div>
                        <div class='form-group input-group-sm col-xs-12'>
                            <label for="fecha_entrega_recibido" class="translate" data-traducir_english="Received Date" data-traducir_spanish="Fecha Entrega">Fecha Entrega</label>
                            <div class='input-group input-group-sm datetime' id='datetimepicker1'>
                                <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </span>
                                <input type='text' class="form-control input requerido_recibir_entrega" id="fecha_entrega_recibido" />
                            </div>
                        </div>
                        <div class='form-group input-group-sm col-xs-12'>
                            <label for="observaciones_recibido" class="translate" data-traducir_english="Observations" data-traducir_spanish="Observaciones">Observaciones</label>
                            <div class="form-group show-tick">
                                <textarea maxlength="2500" align="left" style="height:100px; width:100%; resize: none;" class="form-control limpiar" rows="2" id="observaciones_recibido" placeholder="" style="width: 100%"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-sm btn-primary btn_guardar translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_guardar_producto_recibido">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal_detalle_entrega" id="modal_detalle_entrega" tabindex="-1" role="dialog" aria-labelledby="ModalResponsables" aria-hidden="true">
        <div class="modal-dialog modal-success modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><span id="titulo_modal_responsables" class="translate" data-traducir_english="Delivery Details" data-traducir_spanish="Detalle Entrega">Detalle Entrega</span> <small class="subtitulo" id="subtitulo_modal_responsables"></small></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group input-group-sm col-xs-12">
                            <label for="cod_producto_entregado" class="translate" data-traducir_english="Product" data-traducir_spanish="Producto">Producto</label>
                            <select disabled="disabled" class="selectpicker show-menu-arrow" data-live-search="true" title="Select" id="cod_producto_entregado" name="cod_producto_entregado">
                            </select>
                        </div>
                        <div class="form-group input-group-sm col-xs-12">
                            <label for="cod_unidad_medida_entregado" class="translate" data-traducir_english="Unit of measure" data-traducir_spanish="Unidad medida">Unidad medida</label>
                            <select disabled="disabled" class="selectpicker show-menu-arrow" data-live-search="true" title="Select" id="cod_unidad_medida_entregado" name="cod_unidad_medida_entregado">
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="responsive_table_container">
                            <table class="table display row-border responsive" id="tabla_detalle_entrega">
                                <thead>
                                    <tr class="active info">
                                        <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
                                        <th width="25%" class="translate" data-traducir_english="Quantity" data-traducir_spanish="Cantidad">Cantidad</th>
                                        <th width="25%" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</th>
                                        <th width="45%" class="translate" data-traducir_english="Observations" data-traducir_spanish="Observaciones">Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_detalle_entrega">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_confirmar_cerrar" tabindex="-1" role="dialog" aria-labelledby="modal_confirm_box" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content modal-warning">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modal_confirm_box"> Close Purchase Order - Cerrar Orden de Compra </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 msg-box-container">
                            <i class="fa fa-exclamation-triangle fa-2x"></i>
                            <span> This action will close the purchase order. Are you sure you want to close the purchase order?</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Cancel" data-traducir_spanish="Cancelar" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-sm btn-primary smooth-transition translate" data-traducir_english="Confirm" data-traducir_spanish="Confirmar" data-cod_bloque="0" id="btn_confirmar_cerrar">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_confirmar_limpiar_orden" tabindex="-1" role="dialog" aria-labelledby="modal_confirm_box" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content modal-warning">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modal_confirm_box"> Clear purchase request </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 msg-box-container">
                            <i class="fa fa-exclamation-triangle fa-2x"></i>
                            <span> Are you sure you want to start a new purchase request and discard the current one?</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Cancel" data-traducir_spanish="Cancelar" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-sm btn-primary smooth-transition translate" data-traducir_english="Confirm" data-traducir_spanish="Confirmar" data-cod_bloque="0" id="btn_confirmar_nueva_orden">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
</body>