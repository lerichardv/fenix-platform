/*
 * Funciones jQuery para el módulo de configuracion.
 * @author      Jairo Bonilla
 * @date        2018-10-08
 */
var codigo_formulario = 0;
var codigo_formulario_sa = 0;
var codigo_granja = 0;
var codigo_tipo_temporada = 0;
var codigo_temporada = 0;
var codigo_periodo_fiscal = 0;
var codigo_item_formulario = 0;
var codigo_item = 0;
var codigo_detalle = 0;
var codigo_environmental_test = 0;
var flag_cargar_loading = true;
var site_url = window.location.protocol + '//' + window.location.hostname + '/';
/*
 * Función que obtiene los tipos de estados
 */
function conf_constructor_tipos_items_formularios() {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/funciones/bw_listado_tipos_items_formularios.php',
        dataType: 'json',
        /*
        data: ({
        x1: x1
        }),*/
        success: function (data) {
            $('#cod_tipo_item').empty();
            if (data != null) {
                if (data.length > 1) {
                    $('#cod_tipo_item').append(new Option('Select', '-b'));
                    $.each(data, function (i, item) {
                        $('#cod_tipo_item').append(new Option(item.tipo_item, item.cod_tipo_item));
                    });
                } else {
                    $('#cod_tipo_item').append(new Option(data[0].tipo_item, data[0].cod_tipo_item));
                }
            } else {
                $('#cod_tipo_item').append(new Option('No options', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}

/*Funcion que permite guardar un item de un formulario con todas sus opciones
 */
function conf_guardar_item_formulario(codigo_formulario) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_configuracion/funciones/conf_guardar_item_formulario.php',
        type: 'POST',
        data: {
            x1: codigo_formulario,
            x2: $('#nombre_item').val(),
            x3: $('#cod_tipo_item').val(),
            x4: $('#descripcion_item').val(),
            x5: $('#opcion1').val(),
            x6: $('#opcion2').val(),
            x7: $('#opcion3').val(),
            x8: $('#opcion4').val(),
            x9: $('#opcion5').val(),
            x10: $('#opcion6').val(),
            x11: $('#opcion7').val(),
            x12: $('#opcion8').val(),
            x13: $('#opcion9').val(),
            x14: $('#opcion10').val(),
            x15: $('#valor1').val(),
            x16: $('#valor2').val(),
            x17: $('#valor3').val(),
            x18: $('#valor4').val(),
            x19: $('#valor5').val(),
            x20: $('#valor6').val(),
            x21: $('#valor7').val(),
            x22: $('#valor8').val(),
            x23: $('#valor9').val(),
            x24: $('#valor10').val()
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', info[1], tipo);
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            //usu_vista_familia(codigo_familia);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
}

/*
 * Función que permite cargar vista para ver información de una granja y actualizarla
 */
function conf_vista_granja(codigo_granja) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/ui/conf_info_empresa.php',
        data: {
            cod_granja: codigo_granja
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}



/*Funcion que permite guardar una granja con su información
 */
function conf_guardar_granja(codigo_granja, ext_logo) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_configuracion/funciones/conf_guardar_granja.php',
        type: 'POST',
        data: {
            x1: codigo_granja,
            x2: $('#cod_gerencia').val(),
            x3: $('#nombre_empresa').val(),
            x4: $('#telefono_empresa').val(),
            x5: $('#correo_empresa').val(),
            x6: $('#lema_empresa').val(),
            x7: $('#fax_empresa').val(),
            x8: $('#direccion_linea_1').val(),
            x9: $('#direccion_linea_2').val(),
            x10: $('#descripcion_empresa').val(),
            x11: ext_logo
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', info[1], tipo);
            codigo_granja = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            //conf_vista_granja(codigo_granja);
            respuesta = info[3];
            return respuesta;
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return respuesta;
}

/*Funcion que permite guardar una granja con su información
 */
function conf_cambiar_estado_tipo_temporada(cod_tipo_temporada, valor) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_configuracion/funciones/conf_cambiar_estado_tipo_temporada.php',
        type: 'POST',
        data: {
            x1: cod_tipo_temporada,
            x2: valor
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', info[1], tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            conf_vista_tipo_temporada(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}

/*
 * Función que permite cargar vista para ver información de una granja y actualizarla
 */
function conf_vista_granja(codigo_granja) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/ui/conf_info_empresa.php',
        data: {
            cod_granja: codigo_granja
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}

/*
 * Función que permite cargar vista para ver información de un tipo de temporada y actualizarla
 */
function conf_vista_tipo_temporada(codigo_tipo_temporada) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/ui/conf_tipos_temporada.php',
        data: {
            cod_tipo_temporada: codigo_tipo_temporada
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}


/*Funcion que permite guardar un tipo de temporada con su información
 */
function conf_guardar_tipo_temporada(codigo_tipo_temporada) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_configuracion/funciones/conf_guardar_tipo_temporada.php',
        type: 'POST',
        data: {
            x1: codigo_tipo_temporada,
            x2: $('#tipo_temporada').val()
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', info[1], tipo);
            codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            //conf_vista_granja(codigo_granja);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
}


/*
 * Función que obtiene los tipos de temporada activos
 */
function conf_constructor_listado_tipos_temporada_activos() {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/funciones/conf_listado_tipos_temporada_activos.php',
        dataType: 'json',
        /*
        data: ({
        x1: x1
        }),*/
        success: function (data) {
            $('#cod_tipo_temporada').empty();
            $('#cod_tipo_temporada').append(new Option('Select', '-b'))
            if (data != null) {
                if (data.length > 1) {
                    ;
                    $.each(data, function (i, item) {
                        //$('#cod_tipo_temporada').append(new Option(item.tipo_temporada + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_tipo_temporada));
                        $('#cod_tipo_temporada').append('<option value="' + item.cod_tipo_temporada + '" ' + (item.activo == 1 ? '' : 'disabled="disabled"') + '>' + item.tipo_temporada + '</option>');
                    });
                } else {
                    //$('#cod_tipo_temporada').append(new Option(data[0].tipo_temporada + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_tipo_temporada));
                    $('#cod_tipo_temporada').append('<option value="' + data[0].cod_tipo_temporada + '" ' + (data[0].activo == 1 ? '' : 'disabled="disabled"') + '>' + data[0].tipo_temporada + '</option>');
                }
            } else {
                $('#cod_tipo_temporada').append(new Option('No options', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}


/*
 * Función que obtiene las granjas activas
 */
function conf_constructor_listado_granjas(id = 'cod_info_empresa', flag_seleccione = 1) {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/funciones/conf_listado_granjas.php',
        dataType: 'json',
        /*
        data: ({
        x1: x1
        }),*/
        success: function (data) {
            $('#' + id).empty();
            //if(flag_seleccione == 1) 
            //  $('#' + id).append(new Option('Select', '-b'));
            if (data != null) {
                if (data.length > 1) {
                    ;
                    $.each(data, function (i, item) {
                        $('#' + id).append(new Option(item.nombre_empresa, item.cod_info_empresa));
                    });
                } else {
                    $('#' + id).append(new Option(data[0].nombre_empresa, data[0].cod_info_empresa));
                }
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}
/*
 * Función que obtiene las granjas activas
 */
function conf_constructor_listado_granjas_sin_seleccionar(id = 'cod_info_empresa', flag_seleccione = 1) {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/funciones/conf_listado_granjas.php',
        dataType: 'json',
        /*
        data: ({
        x1: x1
        }),*/
        success: function (data) {
            $('#' + id).empty();
            if (flag_seleccione == 1) {
                $('#' + id).append(new Option('Select', '-b'));
            }
            if (data != null) {
                if (data.length > 1) {
                    ;
                    $.each(data, function (i, item) {
                        $('#' + id).append(new Option(item.nombre_empresa, item.cod_info_empresa));
                    });
                } else {
                    $('#' + id).append(new Option(data[0].nombre_empresa, data[0].cod_info_empresa));
                }
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}
/*
 * Función que obtiene las granjas activas
 */
function conf_constructor_listado_granjas_para_listados_multiples(id = 'cod_info_empresa', flag_seleccione = 1) {

    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/funciones/conf_listado_granjas.php',
        dataType: 'json',
        success: function (data) {
            $('#' + id).empty();
            if (data != null) {
                if (data.length > 1) {
                    ;
                    $.each(data, function (i, item) {
                        $('#' + id).append(new Option(item.nombre_empresa, item.cod_info_empresa));
                    });
                } else {
                    $('#' + id).append(new Option(data[0].nombre_empresa, data[0].cod_info_empresa));
                }
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}


/*Funcion que permite guardar una temporada con su información
 */
function conf_guardar_temporada(codigo_temporada) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_configuracion/funciones/conf_guardar_temporada.php',
        type: 'POST',
        data: {
            x1: codigo_temporada,
            x2: $('#cod_info_empresa').val(),
            x3: $('#cod_tipo_temporada').val(),
            x4: $('#codigo_temporada').val(),
            x5: $('#fecha_inicio').val(),
            x6: $('#fecha_final').val()
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', info[1], tipo);
            codigo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            conf_vista_temporada(codigo_temporada);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
}


/*
 * Función que permite cargar vista para ver información de una granja y actualizarla
 */
function conf_vista_temporada(codigo_temporada) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/ui/conf_temporadas.php',
        data: {
            cod_temporada: codigo_temporada
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}


/*Funcion que permite cambiar activo de una temporada
 */
function conf_cambiar_estado_temporada(cod_temporada, valor) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_configuracion/funciones/conf_cambiar_estado_temporada.php',
        type: 'POST',
        data: {
            x1: cod_temporada,
            x2: valor
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', info[1], tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            conf_vista_temporada(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}




/*Funcion que permite guardar un periodo fiscal con su información
 */
function conf_guardar_periodo_fiscal(codigo_periodo_fiscal) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_configuracion/funciones/conf_guardar_periodo_fiscal.php',
        type: 'POST',
        data: {
            x1: codigo_periodo_fiscal,
            x2: $('#anio_periodo').val(),
            x3: $('#num_periodo').val(),
            x4: $('#fecha_inicio').val(),
            x5: $('#fecha_final').val()
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', info[1], tipo);
            codigo_periodo_fiscal = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            conf_vista_periodo_fiscal(codigo_periodo_fiscal);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
}



/*
 * Función que permite cargar vista para ver información de un periodo fiscal y actualizarla
 */
function conf_vista_periodo_fiscal(codigo_periodo_fiscal) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/ui/conf_periodos_fiscales.php',
        data: {
            cod_periodo_fiscal: codigo_periodo_fiscal
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}



/*Funcion que permite cambiar activo de un año fiscal
 */
function conf_cambiar_estado_periodo_fiscal_anio(anio, valor) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_configuracion/funciones/conf_cambiar_estado_periodo_fiscal_anio.php',
        type: 'POST',
        data: {
            x1: anio,
            x2: valor
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', info[1], tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            conf_vista_periodo_fiscal(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}


/*Funcion que permite cambiar activo de un periodo fiscal
 */
function conf_cambiar_estado_periodo_fiscal(cod_periodo_fiscal, valor) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_configuracion/funciones/conf_cambiar_estado_periodo_fiscal.php',
        type: 'POST',
        data: {
            x1: cod_periodo_fiscal,
            x2: valor
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', info[1], tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            conf_vista_periodo_fiscal(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}



/*
 * Función que permite cargar vista para ver información de un formulario y actualizarla
 */
function conf_vista_formulario(codigo_formulario) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/ui/conf_formularios.php',
        data: {
            cod_formulario: codigo_formulario
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
            var array_file_list = [];
            array_file_list.push({ id: 0, file: '', file_ext: '' });
            var $container_upload_box = $("#container_upload_box");
            var files_form = '';
            var nombre_file = '';
            var ext_adjunto = '';

            function constructor_file_input($container_upload_box) {
                $container_upload_box.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file');
                $container_upload_box.find('.btn-delete-file').removeClass().addClass('smooth-transition btn-delete-file ready-to-upload');
                $container_upload_box.find('.btn-delete-file').data('disabled', false);
                $container_upload_box.find('i.icon-file').removeClass().addClass('icon-file fa fa-file');
                $container_upload_box.find('span.msj-btn-file').text(' Select archivo');
                $container_upload_box.find('.btn-view-file').addClass('hide');
                $container_upload_box.find("input[type=file]").replaceWith(
                    $container_upload_box.find("input[type=file]").val('').clone(true));
                $container_upload_box.find("input[type=file]").attr('title', 'Select un Archivo');
                files = '';
                file_ext = '';
                var id = $container_upload_box.find('.btn-select-file').data('id');
                update_array_file_list(id, files, file_ext);
            }

            function prepare_upload2(event) {
                var $container_upload_box = $(this).parent();
                files_form = event.target.files;
                var cancel_button_is_clicked = files_form[0];
                if (cancel_button_is_clicked == undefined) {
                    constructor_file_input($(this).parent());
                }
                else {
                    var id = $container_upload_box.find('.btn-select-file').data('id');
                    var filesize = files_form[0].size / 1024 / 1024;
                    if (filesize > 10) {
                        grl_mensaje('Tamaño de archivo no permitido. ', 'Solo se permiten archivos menores de 10 MB. ', 'warning');
                        cambio_adjunto = false;
                        constructor_file_input($container_upload_box);
                    }
                    else {
                        ext_adjunto = $(this).val().match(/\.([^\.]+)$/)[1];
                        ext_adjunto = ext_adjunto.toLowerCase();
                        update_array_file_list(id, files_form, ext_adjunto);
                        switch (ext_adjunto) {
                            case 'jpg':
                            case 'jpeg':
                            case 'bmp':
                            case 'png':
                            case 'tif':
                            case 'tiff':
                            case 'svg':
                            case 'pdf':
                            case 'docx':
                            case 'doc':
                            case 'xls':
                            case 'xlsx':
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
            $('.input-file-hidden').on('change', prepare_upload2);
            /* Clic botón seleccionar archivo */
            $(".btn-select-file").on('click', function (e) {
                e.stopPropagation();
                $(this).parent().find('input[type=file]').click();
                return false;
            });
            /* Clic botón subir archivo */
            $('.btn-upload-file').on('click', function (e) {
                e.stopPropagation();
                if ($(this).data('disabled') == false) {
                    var $container_upload_box = $(this).parent();
                    var id = $(this).data('id');
                    var $li = $container_upload_box.closest("li.list-group-item");
                    // Busca id dentro del objeto [array_file_list]
                    var temp_array = array_file_list.filter(function (attr) {
                        return attr.id == id;
                    });
                    $li.unbind('click');
                }
            });

            /* Disparador input seleccionador de archivo */
            $('input[type=file]').on('click', function (e) {
                e.stopPropagation();
            });
        }
    });
}


/*Funcion que permite guardar un proveedor con su información
 */
function conf_guardar_formulario(codigo_formulario) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_configuracion/funciones/conf_guardar_formulario.php',
        type: 'POST',
        data: {
            x1: codigo_formulario,
            x2: $('#nombre_formulario').val(),
            x3: $('#puntuacion_minima').val(),
            x4: $('#puntuacion_maxima').val(),
            x5: $('#cod_estado_plantacion').val(),
            x6: $('#descripcion_formulario').val()
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', info[1], tipo);
            codigo_formulario = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            conf_vista_formulario(codigo_formulario);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
}



/*Funcion que permite cambiar activo de un item de un formulario
 */
function conf_cambiar_activo_item_formulario(cod_item, valor, codigo_formulario) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_configuracion/funciones/conf_cambiar_activo_item_formulario.php',
        type: 'POST',
        data: {
            x1: cod_item,
            x2: valor
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', info[1], tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            conf_vista_formulario(codigo_formulario);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}


/*
 * Función que permite cargar vista previa de un formulario
 */
function conf_cargar_vista_previa_formulario(div, cod_formulario) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/funciones/conf_cargar_vista_previa_formulario.php',
        data: {
            x1: cod_formulario
        },
        success: function (data) {
            $('#' + div).empty();
            $('#' + div).html(data);
        }
    });
}


function grl_traducir_interfaz(flag_traducir) {
    $('.translate').map(function (index, elem) {
        if (flag_traducir == 1) {
            $(this).text($(this).data('traducir_english'));
        }
        else {
            $(this).text($(this).data('traducir_spanish'));
        }
    });
    $('.placeholder_translate').map(function (index, elem) {
        if (flag_traducir == 1) {
            $(this).attr('placeholder', $(this).data('placeholder_en'));
        }
        else {
            $(this).attr('placeholder', $(this).data('placeholder_es'));
        }
    });
    $.ajax({
        url: site_url + 'mod_configuracion/funciones/conf_guardar_flag_traducir.php',
        type: 'POST',
        data: {
            x1: flag_traducir
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            //grl_mensaje('', info[1], tipo);
        })
        .fail(function () {
            console.log("error");
        })
        .always(function () {
            console.log("complete");
        });

}



/*
 * Función que permite cargar vista previa de un formulario
 */
function conf_cargar_formularios_por_estado(div, cod_estado) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/funciones/conf_cargar_formularios_por_estado.php',
        data: {
            x1: cod_estado
        },
        success: function (data) {
            $('#' + div).empty();
            $('#' + div).html(data);
        }
    });
}


/*
 * Función que permite cargar vista previa de un formulario
 */
function conf_cargar_formularios_por_plantacion(div, codigo_plantacion) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/funciones/conf_cargar_formularios_por_plantacion.php',
        data: {
            x1: codigo_plantacion
        },
        success: function (data) {
            $('#' + div).empty();
            $('#' + div).html(data);
        }
    });
}

/*
 * Función que obtiene las temporada activas
 */
function conf_constructor_listado_temporadas(cod_info_empresa) {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/funciones/conf_listado_temporadas.php',
        dataType: 'json',
        data: ({
            x1: cod_info_empresa
        }),
        success: function (data) {
            $('#cod_temporada').empty();
            $('#cod_temporada').append(new Option('Select', '-b'))
            if (data != null) {
                if (data.length > 1) {
                    ;
                    $.each(data, function (i, item) {
                        $('#cod_temporada').append(new Option(item.codigo_temporada + ' - ' + item.tipo_temporada, item.cod_temporada));
                    });
                } else {
                    $('#cod_temporada').append(new Option(data[0].codigo_temporada + ' - ' + data[0].tipo_temporada, data[0].cod_temporada));
                }
            } else {
                $('#cod_temporada').append(new Option('No options', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}


/*Funcion que permite cambiar activo de un formulario
 */
function conf_cambiar_activo_formulario(cod_formulario, valor) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_configuracion/funciones/conf_cambiar_activo_formulario.php',
        type: 'POST',
        data: {
            x1: cod_formulario,
            x2: valor
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', info[1], tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            conf_vista_formulario(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}

/*
* Función que permite abrir modal para editar un item de un formulario
 */
function conf_editar_item_formulario(cod_item, texto_item) {
    jQuery.ajaxSetup({ async: false });
    codigo_item = cod_item;
    $('#nombre_editar_item').val(texto_item);
    $('#modal_editar_item').modal('show');
    jQuery.ajaxSetup({ async: true });
}

/*
* Función que permite abrir modal para editar un item de un formulario
 */
function conf_actualizar_item_formulario(codigo_item) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_configuracion/funciones/conf_actualizar_item_formulario.php',
        type: 'POST',
        data: {
            x1: codigo_item,
            x2: $('#nombre_editar_item').val()
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', info[1], tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            conf_vista_formulario(codigo_formulario);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;

}

/*
 * Función que obtiene los módulos activos
 */
function conf_constructor_listado_moudulos() {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/funciones/conf_listado_modulos.php',
        dataType: 'json',
        /*
        data: ({
        x1: x1
        }),*/
        success: function (data) {
            $('#cod_modulo').empty();
            $('#cod_modulo').append(new Option('Select', '-b'))
            if (data != null) {
                if (data.length > 1) {
                    ;
                    $.each(data, function (i, item) {
                        //$('#cod_modulo').append(new Option(item.nombre + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_modulo));
                        $('#cod_modulo').append('<option value="' + item.cod_modulo + '" ' + (item.activo == 1 ? '' : 'disabled="disabled"') + '>' + item.nombre_english + ' - ' + item.nombre + '</option>');
                    });
                } else {
                    //$('#cod_modulo').append(new Option(data[0].nombre + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_modulo));
                    $('#cod_modulo').append('<option value="' + data[0].cod_modulo + '" ' + (data[0].activo == 1 ? '' : 'disabled="disabled"') + '>' + data[0].nombre_english + ' - ' + data[0].nombre + '</option>');
                }
            } else {
                $('#cod_modulo').append(new Option('No options', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}

/*
 * Función que obtiene los módulos activos
 */
function conf_constructor_listado_menu_por_modulo(cod_modulo) {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/funciones/conf_listado_menu_por_modulo.php',
        dataType: 'json',
        data: ({
            x1: cod_modulo
        }),
        success: function (data) {
            $('#cod_menu').empty();
            $('#cod_menu').append(new Option('Select', '-b'))
            if (data != null) {
                if (data.length > 1) {
                    ;
                    $.each(data, function (i, item) {
                        //$('#cod_menu').append(new Option(item.menu + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_menu));
                        $('#cod_menu').append('<option value="' + item.cod_menu + '" ' + (item.activo == 1 ? '' : 'disabled="disabled"') + '>' + item.menu_english + ' - ' + item.menu + '</option>');
                    });
                } else {
                    //$('#cod_menu').append(new Option(data[0].menu + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_menu));
                    $('#cod_menu').append('<option value="' + data[0].cod_menu + '" ' + (data[0].activo == 1 ? '' : 'disabled="disabled"') + '>' + data[0].menu_english + ' - ' + data[0].menu + '</option>');
                }
            } else {
                $('#cod_menu').append(new Option('No options', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}


/*Funcion que permite guardar un proveedor con su información
 */
function conf_guardar_formulario_seguridad_alimentaria(codigo_formulario_sa) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_configuracion/funciones/conf_guardar_formulario_sa.php',
        type: 'POST',
        data: {
            x1: codigo_formulario_sa,
            x2: $('#nombre_formulario').val(),
            x3: $('#cod_modulo').val(),
            x4: $('#cod_menu').val(),
            x5: $('#descripcion_formulario').val(),
            x6: $('#cod_info_empresa').val(),
            x7: $('#cod_periodo_notificacion').val()
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', info[1], tipo);
            codigo_formulario_sa = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            conf_vista_formulario_sa(codigo_formulario_sa);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
}



/*
 * Función que permite cargar vista para ver información de un formulario y actualizarla
 */
function conf_vista_formulario_sa(codigo_formulario_sa) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/ui/conf_safety_food_forms.php',
        data: {
            codigo_formulario_sa: codigo_formulario_sa
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
            var array_file_list = [];
            array_file_list.push({ id: 0, file: '', file_ext: '' });
            var $container_upload_box = $("#container_upload_box");
            var files_form = '';
            var nombre_file = '';
            var ext_adjunto = '';

            function constructor_file_input($container_upload_box) {
                $container_upload_box.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file');
                $container_upload_box.find('.btn-delete-file').removeClass().addClass('smooth-transition btn-delete-file ready-to-upload');
                $container_upload_box.find('.btn-delete-file').data('disabled', false);
                $container_upload_box.find('i.icon-file').removeClass().addClass('icon-file fa fa-file');
                $container_upload_box.find('span.msj-btn-file').text(' Select archivo');
                $container_upload_box.find('.btn-view-file').addClass('hide');
                $container_upload_box.find("input[type=file]").replaceWith(
                    $container_upload_box.find("input[type=file]").val('').clone(true));
                $container_upload_box.find("input[type=file]").attr('title', 'Select un Archivo');
                files = '';
                file_ext = '';
                var id = $container_upload_box.find('.btn-select-file').data('id');
                update_array_file_list(id, files, file_ext);
            }

            function prepare_upload2(event) {
                var $container_upload_box = $(this).parent();
                files_form = event.target.files;
                var cancel_button_is_clicked = files_form[0];
                if (cancel_button_is_clicked == undefined) {
                    constructor_file_input($(this).parent());
                }
                else {
                    var id = $container_upload_box.find('.btn-select-file').data('id');
                    var filesize = files_form[0].size / 1024 / 1024;
                    if (filesize > 10) {
                        grl_mensaje('Tamaño de archivo no permitido. ', 'Solo se permiten archivos menores de 10 MB. ', 'warning');
                        cambio_adjunto = false;
                        constructor_file_input($container_upload_box);
                    }
                    else {
                        ext_adjunto = $(this).val().match(/\.([^\.]+)$/)[1];
                        ext_adjunto = ext_adjunto.toLowerCase();
                        update_array_file_list(id, files_form, ext_adjunto);
                        switch (ext_adjunto) {
                            case 'jpg':
                            case 'jpeg':
                            case 'bmp':
                            case 'png':
                            case 'tif':
                            case 'tiff':
                            case 'svg':
                            case 'pdf':
                            case 'docx':
                            case 'doc':
                            case 'xls':
                            case 'xlsx':
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
            $('.input-file-hidden').on('change', prepare_upload2);
            /* Clic botón seleccionar archivo */
            $(".btn-select-file").on('click', function (e) {
                e.stopPropagation();
                $(this).parent().find('input[type=file]').click();
                return false;
            });
            /* Clic botón subir archivo */
            $('.btn-upload-file').on('click', function (e) {
                e.stopPropagation();
                if ($(this).data('disabled') == false) {
                    var $container_upload_box = $(this).parent();
                    var id = $(this).data('id');
                    var $li = $container_upload_box.closest("li.list-group-item");
                    // Busca id dentro del objeto [array_file_list]
                    var temp_array = array_file_list.filter(function (attr) {
                        return attr.id == id;
                    });
                    $li.unbind('click');
                }
            });

            /* Disparador input seleccionador de archivo */
            $('input[type=file]').on('click', function (e) {
                e.stopPropagation();
            });
        }
    });
}

/*
 * Función que obtiene los tipo de items activos para formularios de seguridad alimenticia
 */
function conf_constructor_listado_tipo_items() {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/funciones/conf_listado_tipo_items.php',
        dataType: 'json',
        /*
        data: ({
        x1: x1
        }),*/
        success: function (data) {
            $('#cod_tipo_item').empty();
            $('#cod_tipo_item').append(new Option('Select', '-b'))
            if (data != null) {
                if (data.length > 1) {
                    ;
                    $.each(data, function (i, item) {
                        //$('#cod_tipo_item').append(new Option(item.nombre + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_tipo_item));
                        $('#cod_tipo_item').append('<option value="' + item.cod_tipo_item + '" data-max_opciones="' + item.max_opciones + '" ' + (item.activo == 1 ? '' : 'disabled="disabled"') + '>' + item.tipo_item + '</option>');
                    });
                } else {
                    //$('#cod_tipo_item').append(new Option(data[0].nombre + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_tipo_item));
                    $('#cod_tipo_item').append('<option value="' + data[0].cod_tipo_item + '" data-max_opciones="' + data[0].max_opciones + '" ' + (data[0].activo == 1 ? '' : 'disabled="disabled"') + '>' + data[0].tipo_item + '</option>');
                }
            } else {
                $('#cod_tipo_item').append(new Option('No options', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}

/*
*   Función que permite guardar las opciones de un item de un formulario de seguridad alimenticia
 */
function conf_guardar_opciones_item_formulario_sa(codigo_item) {
    var respuesta;
    $('#div_opciones > .opciones').each(function (index, el) {
        //console.log('opcion: ' + $(this).find('.opcion').val());
        //console.log('valor: ' + $(this).find('.valor').val());
        $.ajax({
            url: site_url + 'mod_configuracion/funciones/conf_guardar_opciones_item_formulario_sa.php',
            type: 'POST',
            data:
            {
                x1: codigo_item,
                x2: $(this).find('.opcion').val(),
                x3: $(this).find('.valor').val()
            },
        })
            .done(function (data) {
                console.log("success");
            })
            .fail(function () {
                console.log("error");
            })
            .always(function () {
                console.log("complete");
            });
    });
}



/*Funcion que permite guardar un item de un formulario de seguridad alimenticia
 */
function conf_guardar_item_formulario_sa(codigo_formulario_sa, codigo_item) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_configuracion/funciones/conf_guardar_item_formulario_sa.php',
        type: 'POST',
        data: {
            x1: codigo_formulario_sa,
            x2: $('#id_item').val(),
            x3: $('#nombre_item').val(),
            x4: $('#cod_tipo_item').val(),
            x5: $('#descripcion_item').val(),
            x6: codigo_item,
            x7: $('#orden').val(),
            x8: $('input[name=flag_alerta]').val(),
            x9: $('#caracteres_max').val(),
            x10: $('#cod_tipo_mascara').val(),
            x11: $('input[name=requerido]').val()
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', info[1], tipo);
            jQuery.ajaxSetup({ async: false });
            codigo_item = info[2];
            conf_guardar_opciones_item_formulario_sa(codigo_item);
            conf_vista_formulario_sa(codigo_formulario_sa);
            jQuery.ajaxSetup({ async: true });
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            //usu_vista_familia(codigo_familia);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
}

/*Funcion que permite cambiar activo de un item de un formulario de seguridad alimenticia
 */
function conf_cambiar_activo_item_formulario_sa(cod_item, valor, codigo_formulario_sa) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_configuracion/funciones/conf_cambiar_activo_item_formulario_sa.php',
        type: 'POST',
        data: {
            x1: cod_item,
            x2: valor
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', info[1], tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            conf_vista_formulario_sa(codigo_formulario_sa);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}


/*Funcion que permite cambiar activo de un formulario
 */
function conf_cambiar_activo_formulario_sa(codigo_formulario_sa, valor) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_configuracion/funciones/conf_cambiar_activo_formulario_sa.php',
        type: 'POST',
        data: {
            x1: codigo_formulario_sa,
            x2: valor
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', info[1], tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            conf_vista_formulario_sa(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}


/*
 * Función que permite cargar vista previa de un formulario de seguridad alimenticia
 */
function conf_cargar_vista_previa_formulario_sa(div, cod_formulario) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/funciones/conf_cargar_vista_previa_formulario_sa.php',
        data: {
            x1: cod_formulario
        },
        success: function (data) {
            $('#' + div).empty();
            $('#' + div).html(data);

            $('.selectpicker_dependent').on('change', function (event) {
                event.preventDefault();
                /* Act on the event */
                var ids = $(this).val().split(',');
                $.each(ids, function (index, val) {
                    /* iterate through array or object */
                    /*console.log('index: ' + index);
                    console.log('val: ' + val);*/
                    var id = val.substring(1);
                    /*console.log('id: ' + id);
                    console.log('type: ' + $('#' + id).attr('type'));*/
                    if (val.substring(0, 1) == '+') {
                        $('#div_' + id).removeClass('hide');
                    }
                    if (val.substring(0, 1) == '-') {
                        $('#div_' + id).addClass('hide');
                    }
                });
                $('.selectpicker').selectpicker('refresh');
            });
        }
    });
}


/*
 * Función que permite cargar vista previa de un formulario de seguridad alimenticia por codigo de módulo y menú
 */
function conf_cargar_formularios_por_menu(div, cod_modulo, cod_menu) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/funciones/conf_cargar_formularios_por_menu.php',
        data: {
            x1: cod_modulo,
            x2: cod_menu
        },
        success: function (data) {
            jQuery.ajaxSetup({ async: false });
            $('#' + div).empty();
            $('#' + div).html(data);
            $(document).ready(function () {
                init_button_bar();
                //conf_cargar_formularios_por_menu('div_contenido_form', 9, 3);
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
                $('.selectpicker').selectpicker('refresh');
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
                $('.time').datetimepicker({
                    //disabledHours: true,
                    locale: 'es',
                    //minDate: hoy,
                    //keepOpen: true,
                    format: 'hh:mm',
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
                //Habilita los selects para mobile
                if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
                    $('.selectpicker').selectpicker('mobile');
                }
                /*----------------------------------------------------------------------------------
                                            Validando listboxs
                ----------------------------------------------------------------------------------*/
                $('.selectpicker.requerido').change(function (event) {
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
                $('.input.requerido').keyup(function (event) {
                    if ($(this).val().trim() != "") {
                        $(this).parent('div').removeClass('has-error');
                    }
                    else {
                        $(this).parent('div').addClass('has-error');
                    }
                });
                $('.selectpicker_dependent').on('change', function (event) {
                    event.preventDefault();
                    /* Act on the event */
                    if ($(this).val() == '-b') {
                        $('.div_formulario').removeClass('hide');
                    }
                    else {
                        var ids = $(this).find('option:selected').data('valor').toString().split(',');
                        $.each(ids, function (index, val) {
                            /* iterate through array or object */
                            /*console.log('index: ' + index);
                            console.log('val: ' + val);*/
                            var id = val.substring(1);
                            /*console.log('id: ' + id);
                            console.log('type: ' + $('#' + id).attr('type'));*/
                            if (val.substring(0, 1) == '+') {
                                $('#div_' + id).removeClass('hide');
                            }
                            if (val.substring(0, 1) == '-') {
                                $('#div_' + id).addClass('hide');
                            }
                        });
                    }
                    $('.selectpicker').selectpicker('refresh');
                });
                grl_traducir_interfaz(($('#checkbox_translate').attr('checked') ? 1 : 0));
                jQuery.ajaxSetup({ async: true });
            });
        }
    });
}

/*Funcion que permite guardar un item de un formulario con todas sus opciones
 */
function conf_guardar_item_respuesta_formulario_sa(codigo_formulario, cod_detalle, cod_detalle_item, valor_respuesta, $cod_modulo, $cod_menu) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_configuracion/funciones/conf_guardar_item_respuesta_formulario_sa.php',
        type: 'POST',
        data: {
            x1: codigo_formulario,
            x2: cod_detalle,
            x3: cod_detalle_item,
            x4: valor_respuesta,
            x5: $('#prioridad_' + codigo_formulario).val()
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            //grl_mensaje('', info[1], tipo);
            if (codigo_detalle == 0 || codigo_detalle == null || codigo_detalle == undefined) {
                codigo_detalle = info[2];
                respuesta = info[2];
            }
            else {
                respuesta = codigo_detalle;
            }
            //console.log('info[2]: ' + info[2]);
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            //usu_vista_familia(codigo_familia);
            //console.log('respuesta: ' + respuesta);
            return respuesta;
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return respuesta;
}

/*
*   Funcion que permite notificar a jefe inmediato que un usuario ha llenado un formulario
 */
function conf_notificar_jefe_inmeadito_formulario_llenado(cod_formulario, codigo_detalle, prioridad) {
    $.ajax({
        url: site_url + 'mod_configuracion/funciones/conf_notificar_formulario_llenado.php',
        type: 'POST',
        data:
        {
            x1: cod_formulario,
            x2: codigo_detalle,
            x3: prioridad
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', info[1], tipo);
            //console.log("success");
        })
        .fail(function () {
            //console.log("error");
        })
        .always(function () {
            //console.log("complete");
        });

}

/*
 * Función que obtiene los formularios activos según la granja/finca seleccionada
 */
function conf_constructor_listado_formularios_por_granja(id = 'cod_formulario', cod_info_empresa) {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/funciones/conf_listado_formularios_por_granja.php',
        dataType: 'json',
        data: ({
            x1: cod_info_empresa
        }),
        success: function (data) {
            $('#' + id).empty();
            $('#' + id).append(new Option('Select', '-b'))
            if (data != null) {
                if (data.length > 1) {
                    ;
                    $.each(data, function (i, item) {
                        //$('#' + id).append(new Option(item.nombre + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_tipo_item));
                        $('#' + id).append('<option value="' + item.cod_formulario + '">' + item.nombre_formulario + '</option>');
                    });
                } else {
                    //$('#' + id).append(new Option(data[0].nombre + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_tipo_item));
                    $('#' + id).append('<option value="' + data[0].cod_formulario + '">' + data[0].nombre_formulario + '</option>');
                }
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}


/*
 * Función que permite cargar vista previa de un formulario de seguridad alimenticia por codigo de detalle de formulario
 */
function conf_cargar_vista_revisar_formulario(div, cod_detalle_form, flag_solo_respuestas = 0) {

    //toggleModuleNav();
    grl_overlay_loading('');
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/funciones/conf_cargar_vista_revisar_formulario.php',
        data: {
            x1: cod_detalle_form,
            x2: flag_solo_respuestas
        },
        success: function (data) {
            $('#' + div).empty();
            $('#' + div).html(data);
            $('.selectpicker').selectpicker({
                dropupAuto: 'true',
                container: 'body',
                size: '5',
                width: '100%',
                style: 'btn-sm btn-info'
            });
            $('.selectpicker').selectpicker('refresh');
            $('#modal_loading').modal('hide');
        }
    });
}

/*
 * Función que obtiene los formularios activos según la granja/finca seleccionada
 */
function conf_constructor_estados_formularios_sa(id = 'cod_estado') {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/funciones/conf_listado_estados_formularios_sa.php',
        dataType: 'json',
        success: function (data) {
            $('#' + id).empty();
            $('#' + id).append(new Option('Select', '-b'))
            if (data != null) {
                if (data.length > 1) {
                    ;
                    $.each(data, function (i, item) {
                        //$('#' + id).append(new Option(item.nombre + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_tipo_item));
                        $('#' + id).append('<option value="' + item.cod_estado + '">' + item.nombre_estado + '</option>');
                    });
                } else {
                    //$('#' + id).append(new Option(data[0].nombre + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_tipo_item));
                    $('#' + id).append('<option value="' + data[0].cod_estado + '">' + data[0].nombre_estado + '</option>');
                }
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }
            $('.selectpicker').selectpicker('refresh');
        }
    });
}


/*Funcion que permite guardar el estado y observación de un formulario de seguridad alimenticia
 */
function conf_guardar_revision_formulario_sa(codigo_detalle, index) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_configuracion/funciones/conf_guardar_revision_formulario_sa.php',
        type: 'POST',
        data: {
            x1: codigo_detalle,
            x2: $('#cod_estado').val(),
            x3: $('#observacion').val()
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            if (index == 0) {
                grl_mensaje('', info[1], tipo);
            }
            //conf_cargar_vista_revisar_formulario('div_review_formulario',codigo_detalle);
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            //usu_vista_familia(codigo_familia);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
}


/*
 * Función que permite cargar vista previa de un formulario de seguridad alimenticia por codigo de detalle de formulario
 */
function conf_cargar_vista_mi_formulario(div, cod_detalle_form) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/funciones/conf_cargar_vista_mi_formulario.php',
        data: {
            x1: cod_detalle_form
        },
        success: function (data) {
            $('#' + div).empty();
            $('#' + div).html(data);
            $('.selectpicker').selectpicker({
                dropupAuto: 'true',
                container: 'body',
                size: '5',
                width: '100%',
                style: 'btn-sm btn-info'
            });
            $('.selectpicker').selectpicker('refresh');
        }
    });
}

/*
*   Función que permite des/seleccionar un formulario para ingresar su revisión
 */
function conf_seleccionar_formulario(div, cod_detalle_form, checkbox_value) {
    jQuery.ajaxSetup({ async: false });
    grl_overlay_loading('');
    $.each(cod_detalle_form, function (index, val) {
        if (checkbox_value) {
            selected_forms.push(val);
        }
        else {
            selected_forms = jQuery.grep(selected_forms, function (n, i) {
                return n != val;
            });
        }
    });
    if (selected_forms.length > 0) {
        $('#' + div).empty().append(`<hr>
                <div class="row">
                    <div class="col-md-12">
                        <label for="cod_estado" class="translate" data-traducir_english="Stage" data-traducir_spanish="Estado">Estado</label>
                        <div class="form-group show-tick">
                            <select class="selectpicker show-menu-arrow requerido-revisar" required="" id="cod_estado" name="cod_estado" data-live-search="true" title="Select - Seleccione">
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" >
                        <div class="form-group input-group-sm">
                            <label for="observacion" class="translate" data-traducir_english="Observation (For all the items)" data-traducir_spanish="Observación (Para todos los items)">Observación (Para todos los items)</label>
                            <textarea class="form-control input requerido-revisar-selected-forms" id="observacion" maxlength="600" align="left" style="height:100px; width:100%; resize: none;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-md-offset-9">
                        <button class="btn btn-block btn-md btn-primary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_guardar_selected_forms">Guardar</button>
                    </div>
                </div>`);
        conf_constructor_estados_formularios_sa('cod_estado');
        $('.selectpicker').selectpicker({
            dropupAuto: 'true',
            container: 'body',
            size: '5',
            width: '100%',
            style: 'btn-sm btn-info'
        });
        $('.item_formulario').prop('readonly', true);
        $('.item_formulario').prop('disabled', true);
        $('#cod_estado').selectpicker('val', "<?php echo $FORMULARIO[0]['cod_estado']; ?>");
        $('#cod_estado option[value="1"]').attr('disabled', 'disabled');
        $('.selectpicker').selectpicker('refresh');
        $('#btn_guardar_selected_forms').click(function (event) {
            var error = 0;
            $(".input.requerido-revisar-selected-forms").map(function () {
                if (!$(this).val()) {
                    error = 1;
                    $(this).addClass('input-has-error campo-vacio campo-vacio-modal');
                    return false;
                }
                else {
                    $(this).removeClass('input-has-error campo-vacio campo-vacio-modal');
                }
            });
            $(".selectpicker.requerido-revisar-selected-forms").map(function () {
                if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '') {
                    $(this).selectpicker('setStyle', 'btn-info', 'remove');
                    $(this).selectpicker('setStyle', 'btn-danger');
                    error = 1;
                }
                else {
                    $(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');
                    $(this).selectpicker('setStyle', 'btn-info');
                    $(this).removeClass('campo-vacio');
                }
                $(this).selectpicker('refresh');
            });
            if (error == 0) {
                grl_overlay_loading('');
                $('#modal_loading').modal('hide');
                $('#modal_loading').on('hidden.bs.modal', function () {
                    $.each(selected_forms, function (index, val) {
                        conf_guardar_revision_formulario_sa(val, index);
                    });
                    $('#buscar').trigger('click');
                });
            }
            else {
                grl_mensaje('You must fill the empty fields', 'Debe llenar los campos vacíos', 'warning');
            }
        });
    }
    else {
        $('#' + div).empty();
    }
    $('#modal_loading').modal('hide');
    return false;
    jQuery.ajaxSetup({ async: true });
}


/*Funcion que permite guardar una granja con su información
 */
function conf_guardar_environmental_test(codigo_environmental_test) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_configuracion/funciones/conf_guardar_environmental_test.php',
        type: 'POST',
        data: {
            x1: codigo_environmental_test,
            x2: $('#cod_pais').val(),
            x3: $('#cod_departamento').val(),
            x4: $('#cod_municipio').val(),
            x5: $('#cod_info_empresa').val(),
            x6: $('#cod_location').val(),
            x7: $('#cod_type_test').val(),
            x8: $('#cod_source_phase').val(),
            x9: $('#cod_sample').val(),
            x10: $('#result').val(),
            x11: $('#sample_id').val(),
            x12: $('#sample_date').val(),
            x13: $('#sample_time').val()
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', info[1], tipo);
            codigo_environmental_test = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            //conf_vista_granja(codigo_granja);
            respuesta = info[2];
            return respuesta;
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return respuesta;
}


/*Funcion que permite guardar un adjunto de environmental test
 */
function conf_guardar_adjunto_environmental_test(codigo_environmental_test, nombre_adjunto) {
    $.ajax({
        url: site_url + 'mod_configuracion/funciones/conf_guardar_adjunto_environmental_test.php',
        type: 'POST',
        data: {
            x1: codigo_environmental_test,
            x2: nombre_adjunto
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            //grl_mensaje('', info[1], tipo);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
}



/*
 * Función que permite cargar listado detallado de registros de environmental test
 */
function conf_cargar_registros_environmental_test(div, cod_info_empresa, cod_location, cod_type_test, cod_source_phase, cod_sample, sample_date) {

    //toggleModuleNav();
    grl_overlay_loading('');
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_configuracion/funciones/conf_cargar_registros_environmental_test.php',
        data: {
            x1: cod_info_empresa,
            x2: cod_location,
            x3: cod_type_test,
            x4: cod_source_phase,
            x5: cod_sample,
            x6: sample_date
        },
        success: function (data) {
            $('#' + div).empty();
            $('#' + div).html(data);
            $('#modal_loading').modal('hide');
        }
    });
}