var codigo_variedad = 0;
var codigo_tipo_quimico = 0;
var codigo_ingrediente_activo = 0;
var codigo_inventario_semilla = 0;
var codigo_inventario_quimico = 0;
var codigo_inventario_maquinaria = 0;
var codigo_unidad_medida = 0;
var codigo_proveedor = 0;
var codigo_tipo_periodo = 0;
var codigo_tipo_aplicacion = 0;
var codigo_movimiento = 0;
var codigo_inventario_vario = 0;
var codigo_orden_compra = 0;
var site_url = window.location.protocol + '//' + window.location.hostname + '/';

/*
 * Función que permite cargar vista para ver información de una variedad de semilla y actualizarla
 */
function inv_vista_variedad_semilla(codigo_variedad) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_variedad_sembradora.php',
        data: {
            cod_variedad: codigo_variedad
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}
/*
* Función que permite cargar vista para ver información de un donante y actualizarlo
*/
function inv_vista_listado_semillas(inicio, limite) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_inventario_sembradora.php',
        data:
        {
            inicio: inicio,
            limite: limite
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}
/*
* Función que permite cargar vista para ver información de un donante y actualizarlo
*/
function inv_vista_listado_quimicos(inicio, limite) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_inventario_quimico.php',
        data:
        {
            inicio: inicio,
            limite: limite
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}
/*
* Función que permite cargar vista para ver información de un donante y actualizarlo
*/
function inv_vista_listado_maquinaria(inicio, limite) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_inventario_maquinaria.php',
        data:
        {
            inicio: inicio,
            limite: limite
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}
/*
* Función que permite cargar vista para ver información de un donante y actualizarlo
*/
function inv_vista_listado_varios(inicio, limite) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_inventario_varios.php',
        data:
        {
            inicio: inicio,
            limite: limite
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}

/*Funcion que permite guardar una variedad de información con su información
 */
function inv_guardar_variedad_semilla(codigo_variedad) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_variedad_semilla.php',
        type: 'POST',
        data: {
            x1: codigo_variedad,
            x2: $('#variedad_producto').val()
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', mensaje, tipo);
            codigo_variedad = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_variedad_semilla(codigo_variedad);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
}

/*Funcion que permite cambiar estado activo/des de una variedad de producto
 */
function inv_cambiar_estado_variedad_producto(cod_variedad, valor) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_cambiar_estado_variedad_producto.php',
        type: 'POST',
        data: {
            x1: cod_variedad,
            x2: valor
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', mensaje, tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_variedad_semilla(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}


/*
 * Función que permite cargar vista para ver información de un tipo de quimico y actualizarla
 */
function inv_vista_tipo_quimico(codigo_tipo_quimico) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_tipos_quimicos.php',
        data: {
            cod_tipo_quimico: codigo_tipo_quimico
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}

/*Funcion que permite guardar una variedad de información con su información
 */
function inv_guardar_tipo_quimico(codigo_tipo_quimico) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_tipo_quimico.php',
        type: 'POST',
        data: {
            x1: codigo_tipo_quimico,
            x2: $('#tipo_quimico').val()
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', mensaje, tipo);
            codigo_tipo_quimico = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_tipo_quimico(codigo_tipo_quimico);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
}

/*Funcion que permite cambiar estado activo/des de una variedad de producto
 */
function inv_cambiar_estado_tipo_quimico(cod_tipo_quimico, valor) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_cambiar_estado_tipo_quimico.php',
        type: 'POST',
        data: {
            x1: cod_tipo_quimico,
            x2: valor
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', mensaje, tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_tipo_quimico(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}



/*
 * Función que permite cargar vista para ver información de un ingrediente activo y actualizarla
 */
function inv_vista_ingrediente_activo(codigo_ingrediente_activo) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_ingredientes_activos.php',
        data: {
            cod_ingrediente_activo: codigo_ingrediente_activo
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}

/*Funcion que permite guardar un ingrediente activo con su información
 */
function inv_guardar_ingrediente_activo(codigo_ingrediente_activo) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_ingrediente_activo.php',
        type: 'POST',
        data: {
            x1: codigo_ingrediente_activo,
            x2: $('#ingrediente_activo').val()
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', mensaje, tipo);
            codigo_ingrediente_activo = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_ingrediente_activo(codigo_ingrediente_activo);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
}

/*Funcion que permite cambiar estado activo/des de un ingrediente activo
 */
function inv_cambiar_estado_ingrediente_activo(cod_ingrediente, valor) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_cambiar_estado_ingrediente_activo.php',
        type: 'POST',
        data: {
            x1: cod_ingrediente,
            x2: valor
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', mensaje, tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_ingrediente_activo(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}


/*
 * Función que obtiene los tipos de quimicos activos
 */
function inv_constructor_listado_tipos_quimicos(id = 'cod_tipo_quimico') {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_tipos_quimicos.php',
        dataType: 'json',
        /*
        data: ({
        x1: x1
        }),*/
        success: function (data) {
            $('#' + id).empty();
            $('#' + id).append(new Option('Select', '-b'))
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        //$('#' + id).append(new Option(item.tipo_quimico + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_tipo_quimico));
                        $('#' + id).append('<option value="' + item.cod_tipo_quimico + '" ' + (item.activo == 1 ? '' : 'disabled="disabled"') + '>' + item.tipo_quimico + '</option>');
                    });
                } else {
                    //$('#' + id).append(new Option(data[0].tipo_quimico + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_tipo_quimico));
                    $('#' + id).append('<option value="' + data[0].cod_tipo_quimico + '" ' + (data[0].activo == 1 ? '' : 'disabled="disabled"') + '>' + data[0].tipo_quimico + '</option>');
                }
            } else {
                $('#' + id).append(new Option('No hay opciones', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}


/*
 * Función que obtiene los ingredientes activos
 */
function inv_constructor_listado_ingredientes_activos() {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_ingredientes_activos.php',
        dataType: 'json',
        /*
        data: ({
        x1: x1
        }),*/
        success: function (data) {
            $('#cod_ingrediente_activo').empty();
            $('#cod_ingrediente_activo').append(new Option('Select', '-b'))
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        //$('#cod_ingrediente_activo').append(new Option(item.ingrediente_activo + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_ingrediente_activo));
                        $('#cod_ingrediente_activo').append('<option value="' + item.cod_ingrediente_activo + '" ' + (item.activo == 1 ? '' : 'disabled="disabled"') + '>' + item.ingrediente_activo + '</option>');
                    });
                } else {
                    //$('#cod_ingrediente_activo').append(new Option(data[0].ingrediente_activo + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_ingrediente_activo));
                    $('#cod_ingrediente_activo').append('<option value="' + data[0].cod_ingrediente_activo + '" ' + (data[0].activo == 1 ? '' : 'disabled="disabled"') + '>' + data[0].cod_ingrediente_activo + '</option>');
                }
            } else {
                $('#cod_ingrediente_activo').append(new Option('No hay opciones', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}



/*
 * Función que obtiene las variedad de producto activas
 */
function inv_constructor_listado_variedades_productos() {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_variedades_productos.php',
        dataType: 'json',
        /*
        data: ({
        x1: x1
        }),*/
        success: function (data) {
            $('#cod_variedad').empty();
            $('#cod_variedad').append(new Option('Select', '-b'))
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        //$('#cod_variedad').append(new Option(item.variedad_producto + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_variedad));
                        $('#cod_variedad').append('<option value="' + item.cod_variedad + '" ' + (item.activo == 1 ? '' : 'disabled="disabled"') + '>' + item.variedad_producto + '</option>');
                    });
                } else {
                    $('#cod_variedad').append('<option value="' + data[0].cod_variedad + '" ' + (data[0].activo == 1 ? '' : 'disabled="disabled"') + '>' + data[0].variedad_producto + '</option>');
                }
            } else {
                $('#cod_variedad').append(new Option('No hay opciones', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}





/*
 * Función que permite cargar vista para ver información de una unidad de medida y actualizarla
 */
function inv_vista_unidad_medida(codigo_unidad_medida) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_unidades_medida.php',
        data: {
            cod_unidad_medida: codigo_unidad_medida
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}

/*Funcion que permite guardar una unidad de medida con su información
 */
function inv_guardar_unidad_medida(codigo_unidad_medida) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_unidad_medida.php',
        type: 'POST',
        data: {
            x1: codigo_unidad_medida,
            x2: $('#unidad_medida').val(),
            x3: $('#abreviatura_medida').val()
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', mensaje, tipo);
            codigo_unidad_medida = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_unidad_medida(codigo_unidad_medida);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
}

/*Funcion que permite cambiar estado activo/des de una unidad de medida
 */
function inv_cambiar_estado_unidad_medida(cod_unidad_medida, valor) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_cambiar_estado_unidad_medida.php',
        type: 'POST',
        data: {
            x1: cod_unidad_medida,
            x2: valor
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', mensaje, tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_unidad_medida(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}

/*
 * Función que obtiene las unidades de medida activas
 */
function inv_constructor_listado_unidades_medida(id = 'cod_unidad_medida') {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_unidades_medida.php',
        dataType: 'json',
        /*
        data: ({
        x1: x1
        }),*/
        success: function (data) {
            $('#' + id).empty();
            $('#' + id).append(new Option('Select', '-b'))
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        //$('#' + id).append(new Option(item.unidad_medida + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_unidad_medida));
                        $('#' + id).append('<option value="' + item.cod_unidad_medida + '" ' + (item.activo == 1 ? '' : 'disabled="disabled"') + '>' + item.unidad_medida + '</option>');
                    });
                } else {
                    //$('#' + id).append(new Option(data[0].unidad_medida + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_unidad_medida));
                    $('#' + id).append('<option value="' + data[0].cod_unidad_medida + '" ' + (data[0].activo == 1 ? '' : 'disabled="disabled"') + '>' + data[0].unidad_medida + '</option>');
                }
            } else {
                $('#' + id).append(new Option('No hay opciones', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}





/*
 * Función que permite cargar vista para ver información de una semilla y actualizarla
 */
function inv_vista_inventario_semilla(codigo_inventario_semilla) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_inventario_sembradora.php',
        data: {
            cod_semilla: codigo_inventario_semilla
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}

/*Funcion que permite guardar una semilla con su información
 */
function inv_guardar_inventario_semilla(codigo_inventario_semilla) {

    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_inventario_semilla.php',
        type: 'POST',
        data: {
            x1: codigo_inventario_semilla,
            x2: $('#cod_info_empresa').val(),
            x3: $('#codigo_semilla').val(),
            x4: $('#nombre_semilla').val(),
            x5: $('#cod_variedad').val(),
            x6: $('#abreviatura_semilla').val(),
            x7: $('#cantidad_semilla').val(),
            // x7: $('#semillas_por_plantaciones').val(), // TODO: buscar una alternativa y modificar todas las áreas donde sea implementado
            x8: $('#cod_unidad_medida').val(),
            x9: $('#cantidad_fisica_semilla').val(),
            // x9: 50,// TODO: buscar una alternativa y modificar todas las áreas donde sea implementado
            x10: $('#precio_unidad').val(),
            x11: $('#numero_lote').val(),
            // x12: ($('#flag_watercress').attr('checked') ? 0 : 1),
            x12: 0,// TODO: buscar una alternativa y modificar todas las áreas donde sea implementado
            x13: $('#cantidad_sumar').val(),
            x14: $('#cantidad_restar').val(),
            x15: $('#razon_sumar_restar').val(),
            x16: $('#fecha_sumar_restar').val(),
            x17: $('#cod_categoria').val(),
            x18: $('#cod_grupo_siembra').val(),
            x19: $('#cod_rasgo').val(),
            x20: $('#plants_acre').val(),
            x21: $('#cod_familia').val(),
            x23: $('#red_zone').val(),
            x24: $('#over_seed').val(),
            x25: $('#semillas_por_plantaciones').val(),
            x26: ($('#paletizado').is(':checked') ? 1 : 0),
            x27: ($('#semilla_activa').is(':checked') ? 1 : 0),
            x28: $('#notas').val(),
            x29: $('#og_supply').val(),
            x30: $('#cod_vendedores').val(),
            // x31: ($('#flag_germinacion_automatica').is(':checked') ? 1 : 0)
            x31: 1

        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', mensaje, tipo);
            if (codigo_inventario_semilla == 0) {

                inv_vista_inventario_semilla(0);
            } else {
                codigo_inventario_semilla = info[2];
                // inv_vista_inventario_semilla(codigo_inventario_semilla);
                inv_vista_inventario_semilla(0);

            }
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
}

/*Funcion que permite cambiar estado activo/des de una unidad de medida
 */
function inv_cambiar_estado_inventario_semilla(cod_inventario_semilla, valor) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_cambiar_estado_inventario_semilla.php',
        type: 'POST',
        data: {
            x1: cod_inventario_semilla,
            x2: valor
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', mensaje, tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_inventario_semilla(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}


/*
 * Función que obtiene las semillas activas
 */
function inv_constructor_listado_semillas(id = 'cod_inventario_semilla', cod_info_empresa) {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_semillas.php',
        dataType: 'json',
        data: ({
            x1: cod_info_empresa
        }),
        success: function (data) {
            $('#' + id).empty();
            $('#' + id).append(new Option('Select', '-b'))
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        //$('#' + id).append(new Option(item.nombre_semilla + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_inventario));
                        $('#' + id).append('<option data-cantidad_semilla="' + item.cantidad_semilla + '" value="' + item.cod_inventario + '" ' + (item.activo == 1 ? '' : 'disabled="disabled"') + '>' + item.nombre_semilla + ' - ' + item.cantidad_semilla + ' ' + item.unidad_medida + (item.numero_lote > 0 ? ' Lote ' + item.numero_lote : ' Sin Lote') + '</option>');
                    });
                } else {
                    //$('#' + id).append(new Option(data[0].nombre_semilla + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_inventario));
                    $('#' + id).append('<option data-cantidad_semilla="' + data[0].cantidad_semilla + '" value="' + data[0].cod_inventario + '" ' + (data[0].activo == 1 ? '' : 'disabled="disabled"') + '>' + data[0].nombre_semilla + ' - ' + data[0].cantidad_semilla + ' ' + data[0].unidad_medida + (data[0].numero_lote > 0 ? ' Lote ' + data[0].numero_lote : ' Sin Lote') + '</option>');
                }
            } else {
                $('#' + id).append(new Option('No hay opciones', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}




/*
 * Función que permite cargar vista para ver información de un tipo de periodo y actualizarla
 */
function inv_vista_tipo_periodo(codigo_tipo_periodo) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_tipos_periodo.php',
        data: {
            cod_tipo_periodo: codigo_tipo_periodo
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}

/*Funcion que permite guardar una variedad de información con su información
 */
function inv_guardar_tipo_periodo(codigo_tipo_periodo) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_tipo_periodo.php',
        type: 'POST',
        data: {
            x1: codigo_tipo_periodo,
            x2: $('#tipo_periodo').val()
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', mensaje, tipo);
            codigo_tipo_periodo = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_tipo_periodo(codigo_tipo_periodo);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
}

/*Funcion que permite cambiar estado activo/des de un tipo de periodo
 */
function inv_cambiar_estado_tipo_periodo(cod_tipo_periodo, valor) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_cambiar_estado_tipo_periodo.php',
        type: 'POST',
        data: {
            x1: cod_tipo_periodo,
            x2: valor
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', mensaje, tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_tipo_periodo(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}



/*
 * Función que obtiene los tipos de periodo activos
 */
function inv_constructor_listado_tipos_periodo(id) {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_tipos_periodo.php',
        dataType: 'json',
        /*
        data: ({
        x1: x1
        }),*/
        success: function (data) {
            $('#' + id).empty();
            $('#' + id).append(new Option('Select', '-b'))
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        //$('#'+id).append(new Option(item.tipo_periodo + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_tipo_periodo));
                        $('#' + id).append('<option value="' + item.cod_tipo_periodo + '" ' + (item.activo == 1 ? '' : 'disabled="disabled"') + '>' + item.tipo_periodo + '</option>');
                    });
                } else {
                    //$('#'+id).append(new Option(data[0].tipo_periodo + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_tipo_periodo));
                    $('#' + id).append('<option value="' + data[0].cod_tipo_periodo + '" ' + (data[0].activo == 1 ? '' : 'disabled="disabled"') + '>' + data[0].tipo_periodo + '</option>');
                }
            } else {
                $('#' + id).append(new Option('No hay opciones', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}




/*
 * Función que permite cargar vista para ver información de un quimico y actualizarla
 */
function inv_vista_inventario_quimico(codigo_inventario_quimico) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_inventario_quimico.php',
        data: {
            cod_quimico: codigo_inventario_quimico
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}

/*Funcion que permite guardar un quimico con su información
 */
function inv_guardar_inventario_quimico(codigo_inventario_quimico, ext_adjunto, ext_label) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_inventario_quimico.php',
        type: 'POST',
        data: {
            x1: codigo_inventario_quimico,
            x2: $('#cod_info_empresa').val(),
            x3: $('#cod_quimico').val(),
            x4: $('#nombre_quimico').val(),
            x5: $('#cod_unidad_medida').val(),
            x6: $('#cantidad_quimico').val(),
            x7: $('#cantidad_fisica_quimico').val(),
            x8: $('#precio_quimico').val(),
            x9: $('#cod_ingrediente_activo').val(),
            x10: $('#registro_ambiental').val(),
            x11: $('#periodo_reingreso').val(),
            x12: $('#cod_tipo_periodo_reingreso').val(),
            x13: $('#periodo_precosecha').val(),
            x14: $('#cod_tipo_periodo_precosecha').val(),
            x15: $('#dosis_minima').val(),
            x16: $('#dosis_maxima').val(),
            x17: $('#cod_tipo_quimico').val(),
            x18: $('#cantidad_minima_alerta').val(),
            x19: $('#razon_aplicacion').val(),
            /*x20: $('#etiqueta').val(),*/
            x21: ext_adjunto,
            x22: ext_label,
            x23: $('#cantidad_sumar').val(),
            x24: $('#cantidad_restar').val(),
            x25: $('#razon_sumar_restar').val(),
            x26: $('#fecha_sumar_restar').val()
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', mensaje, tipo);
            codigo_inventario_quimico = info[2];
            respuesta = codigo_inventario_quimico;
            return codigo_inventario_quimico;
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            //inv_vista_inventario_quimico(codigo_inventario_quimico);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return respuesta;
}

/*Funcion que permite cambiar estado activo/des de un quimico
 */
function inv_cambiar_estado_inventario_quimico(cod_inventario_quimico, valor) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_cambiar_estado_inventario_quimico.php',
        type: 'POST',
        data: {
            x1: cod_inventario_quimico,
            x2: valor
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', mensaje, tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_inventario_quimico(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}


/*
 * Función que obtiene los quimicos activas
 */
function inv_constructor_listado_quimicos(id = 'cod_inventario_quimico', cod_info_empresa, flag_select = 0) {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_quimicos.php',
        dataType: 'json',
        data: ({
            x1: cod_info_empresa
        }),
        success: function (data) {
            $('#' + id).empty();
            if (flag_select == 0)
                $('#' + id).append(new Option('Select', '-b'))
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        //$('#' + id).append(new Option(item.nombre_quimico + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_inventario));
                        $('#' + id).append('<option data-dosis_maxima="' + item.dosis_maxima + '" data-dosis_minima="' + item.dosis_minima + '" data-cod_unidad_medida="' + item.cod_unidad_medida + '" value="' + item.cod_inventario + '" ' + (item.activo == 1 ? '' : 'disabled="disabled"') + '>' + item.nombre_quimico + ' - ' + item.cod_quimico + '(' + item.cantidad_quimico + ' ' + item.unidad_medida + ')</option>');
                    });
                } else {
                    //$('#' + id).append(new Option(data[0].nombre_quimico + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_inventario));
                    $('#' + id).append('<option data-dosis_maxima="' + data[0].dosis_maxima + '" data-dosis_minima="' + data[0].dosis_minima + '" data-cod_unidad_medida="' + data[0].cod_unidad_medida + '" value="' + data[0].cod_inventario + '" ' + (data[0].activo == 1 ? '' : 'disabled="disabled"') + '>' + data[0].nombre_quimico + ' - ' + data[0].cod_quimico + '(' + data[0].cantidad_quimico + ' ' + data[0].unidad_medida + ')</option>');
                }
            } else {
                $('#' + id).append(new Option('No hay opciones', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}

/*
 * Función que obtiene los quimicos activas
 */
function inv_constructor_listado_quimicos_por_tipo_quimico_granja(id = 'cod_inventario_quimico', cod_info_empresa, cod_tipo_quimico) {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_quimicos_por_tipo_quimico_granja.php',
        dataType: 'json',
        data: ({
            x1: cod_info_empresa,
            x2: cod_tipo_quimico
        }),
        success: function (data) {
            $('#' + id).empty();
            $('#' + id).append(new Option('Select', '-b'))
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        //$('#' + id).append(new Option(item.nombre_quimico + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_inventario));
                        $('#' + id).append('<option data-dosis_maxima="' + item.dosis_maxima + '" data-dosis_minima="' + item.dosis_minima + '" data-cod_unidad_medida="' + item.cod_unidad_medida + '" value="' + item.cod_inventario + '" ' + (item.activo == 1 ? '' : 'disabled="disabled"') + '>' + item.nombre_quimico + ' - ' + item.cod_quimico + '(' + item.cantidad_quimico + ' ' + item.unidad_medida + ')</option>');
                    });
                } else {
                    //$('#' + id).append(new Option(data[0].nombre_quimico + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_inventario));
                    $('#' + id).append('<option data-dosis_maxima="' + data[0].dosis_maxima + '" data-dosis_minima="' + data[0].dosis_minima + '" data-cod_unidad_medida="' + data[0].cod_unidad_medida + '" value="' + data[0].cod_inventario + '" ' + (data[0].activo == 1 ? '' : 'disabled="disabled"') + '>' + data[0].nombre_quimico + ' - ' + data[0].cod_quimico + '(' + data[0].cantidad_quimico + ' ' + data[0].unidad_medida + ')</option>');
                }
            } else {
                $('#' + id).append(new Option('No hay opciones', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}


/*
 * Función que permite cargar vista para ver información de un tipo de aplicacion y actualizarla
 */
function inv_vista_tipo_aplicacion(codigo_tipo_aplicacion) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_tipos_aplicacion.php',
        data: {
            cod_tipo_aplicacion: codigo_tipo_aplicacion
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}

/*Funcion que permite guardar una variedad de información con su información
 */
function inv_guardar_tipo_aplicacion(codigo_tipo_aplicacion) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_tipo_aplicacion.php',
        type: 'POST',
        data: {
            x1: codigo_tipo_aplicacion,
            x2: $('#tipo_aplicacion').val()
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', mensaje, tipo);
            codigo_tipo_aplicacion = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_tipo_aplicacion(codigo_tipo_aplicacion);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
}

/*Funcion que permite cambiar estado activo/des de un tipo de aplicacion
 */
function inv_cambiar_estado_tipo_aplicacion(cod_tipo_aplicacion, valor) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_cambiar_estado_tipo_aplicacion.php',
        type: 'POST',
        data: {
            x1: cod_tipo_aplicacion,
            x2: valor
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', mensaje, tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_tipo_aplicacion(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}



/*
 * Función que obtiene los tipos de aplicacion activos
 */
function inv_constructor_listado_tipos_aplicacion(id) {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_tipos_aplicacion.php',
        dataType: 'json',
        /*
        data: ({
        x1: x1
        }),*/
        success: function (data) {
            $('#' + id).empty();
            $('#' + id).append(new Option('Select', '-b'))
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        //$('#'+id).append(new Option(item.tipo_aplicacion + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_tipo_aplicacion));
                        $('#' + id).append('<option value="' + item.cod_tipo_aplicacion + '" ' + (item.activo == 1 ? '' : 'disabled="disabled"') + '>' + item.tipo_aplicacion + '</option>');
                    });
                } else {
                    //$('#'+id).append(new Option(data[0].tipo_aplicacion + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_tipo_aplicacion));
                    $('#' + id).append('<option value="' + data[0].cod_tipo_aplicacion + '" ' + (data[0].activo == 1 ? '' : 'disabled="disabled"') + '>' + data[0].tipo_aplicacion + '</option>');
                }
            } else {
                $('#' + id).append(new Option('No hay opciones', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}





/*
 * Función que permite cargar vista para ver información de una maquinaria y actualizarla
 */
function inv_vista_inventario_maquinaria(codigo_inventario_maquinaria) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_inventario_maquinaria.php',
        data: {
            cod_maquinaria: codigo_inventario_maquinaria
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}

/*Funcion que permite guardar una maquinaria con su información
 */
function inv_guardar_inventario_maquinaria(codigo_inventario_maquinaria) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_inventario_maquinaria.php',
        type: 'POST',
        data: {
            x1: codigo_inventario_maquinaria,
            x2: $('#cod_info_empresa').val(),
            x3: $('#codigo_maquinaria').val(),
            x4: $('#nombre_maquinaria').val(),
            x5: $('#cod_tipo_aplicacion').val(),
            x6: $('#precio_unidad').val(),
            x7: $('#anio_vencimiento').val(),
            x8: $('#cod_estado_plantacion').val()
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', mensaje, tipo);
            codigo_inventario_maquinaria = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_inventario_maquinaria(codigo_inventario_maquinaria);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
}

/*Funcion que permite cambiar estado activo/des de una unidad de medida
 */
function inv_cambiar_estado_inventario_maquinaria(cod_inventario_maquinaria, valor) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_cambiar_estado_inventario_maquinaria.php',
        type: 'POST',
        data: {
            x1: cod_inventario_maquinaria,
            x2: valor
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', mensaje, tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_inventario_maquinaria(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}


/*
 * Función que obtiene las maquinarias activas
 */
function inv_constructor_listado_maquinarias(id = 'cod_inventario_maquinaria', cod_info_empresa) {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_maquinarias.php',
        dataType: 'json',
        data: ({
            x1: cod_info_empresa
        }),
        success: function (data) {
            $('#' + id).empty();
            $('#' + id).append(new Option('Select', '-b'))
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        //$('#' + id).append(new Option(item.nombre_maquinaria + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_inventario));
                        $('#' + id).append('<option value="' + item.cod_inventario + '" ' + (item.activo == 1 ? '' : 'disabled="disabled"') + '>' + item.nombre_maquinaria + '</option>');
                    });
                } else {
                    //$('#' + id).append(new Option(data[0].nombre_maquinaria + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_inventario));
                    $('#' + id).append('<option value="' + data[0].cod_inventario + '" ' + (data[0].activo == 1 ? '' : 'disabled="disabled"') + '>' + data[0].nombre_maquinaria + '</option>');
                }
            } else {
                $('#' + id).append(new Option('No hay opciones', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}



/*
 * Función que obtiene las maquinarias activas
 */
function inv_constructor_listado_maquinarias_estado(id = 'cod_inventario_maquinaria', cod_info_empresa, cod_estado = 1) {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_maquinarias_estado.php',
        dataType: 'json',
        data: ({
            x1: cod_info_empresa,
            x2: cod_estado
        }),
        success: function (data) {
            $('#' + id).empty();
            //$('#' + id).append(new Option('Select', '-b'))
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        //$('#' + id).append(new Option(item.nombre_maquinaria + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_inventario));
                        $('#' + id).append('<option value="' + item.cod_inventario + '" ' + (item.activo == 1 ? '' : 'disabled="disabled"') + '>' + item.nombre_maquinaria + '</option>');
                    });
                } else {
                    //$('#' + id).append(new Option(data[0].nombre_maquinaria + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_inventario));
                    $('#' + id).append('<option value="' + data[0].cod_inventario + '" ' + (data[0].activo == 1 ? '' : 'disabled="disabled"') + '>' + data[0].nombre_maquinaria + '</option>');
                }
            } else {
                $('#' + id).append(new Option('No hay opciones', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}





/*
 * Función que permite cargar vista para ver información de un proveedor y actualizarla
 */
function inv_vista_proveedor(codigo_proveedor) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_proveedores.php',
        data: {
            cod_proveedor: codigo_proveedor
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}

/*Funcion que permite guardar un proveedor con su información
 */
function inv_guardar_proveedor(codigo_proveedor) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_proveedor.php',
        type: 'POST',
        data: {
            x1: codigo_proveedor,
            x2: $('#cod_info_empresa').val(),
            x3: $('#nombre_empresa').val(),
            x4: $('#nombre_contacto').val(),
            x5: $('#correo_contacto').val(),
            x6: $('#telefono_contacto').val(),
            x7: $('#observaciones').val()
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', mensaje, tipo);
            codigo_proveedor = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_proveedor(codigo_proveedor);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
}

/*Funcion que permite cambiar estado activo/des de un proveedor
 */
function inv_cambiar_estado_proveedor(cod_proveedor, valor) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_cambiar_estado_proveedor.php',
        type: 'POST',
        data: {
            x1: cod_proveedor,
            x2: valor
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', mensaje, tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_proveedor(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}


/*
 * Función que obtiene los provedores activos
 */
function inv_constructor_listado_proveedores(cod_info_empresa) {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_proveedores.php',
        dataType: 'json',

        data: ({
            x1: cod_info_empresa
        }),
        success: function (data) {
            $('#cod_proveedor').empty();
            $('#cod_proveedor').append(new Option('Select', '-b'))
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        //$('#cod_proveedor').append(new Option(item.nombre_contacto + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_proveedor));
                        $('#cod_proveedor').append('<option value="' + item.cod_proveedor + '" ' + (item.activo == 1 ? '' : 'disabled="disabled"') + '>' + item.nombre_empresa + ' - ' + item.nombre_contacto + '</option>');
                    });
                } else {
                    //$('#cod_proveedor').append(new Option(data[0].nombre_contacto + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_proveedor));
                    $('#cod_proveedor').append('<option value="' + data[0].cod_proveedor + '" ' + (data[0].activo == 1 ? '' : 'disabled="disabled"') + '>' + data[0].nombre_empresa + ' - ' + data[0].nombre_contacto + '</option>');
                }
            } else {
                $('#cod_proveedor').append(new Option('No hay opciones', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}


/*Funcion que permite guardar un producto con su información
 */
function inv_guardar_producto(codigo_proveedor) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_producto.php',
        type: 'POST',
        data: {
            x1: codigo_proveedor,
            x2: $('#cod_inventario_quimico').val(),
            x3: $('#cod_inventario_maquinaria').val(),
            x4: $('#cod_inventario_semilla').val()
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', mensaje, tipo);
            codigo_proveedor = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_proveedor(codigo_proveedor);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
}

/*Funcion que permite cambiar estado activo/des de un producto
 */
function inv_cambiar_estado_producto(cod_producto, valor, codigo_proveedor) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_cambiar_estado_producto.php',
        type: 'POST',
        data: {
            x1: cod_producto,
            x2: valor
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', mensaje, tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_proveedor(codigo_proveedor);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}


/*
 * Función que permite cargar vista para ver información de una semilla y actualizarla
 */
function inv_vista_movimiento_inventario(codigo_movimiento) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_movimientos_inventario.php',
        data: {
            cod_movimiento: codigo_movimiento
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}

/*Funcion que permite guardar una semilla con su información
 */
function inv_guardar_movimiento_inventario(codigo_movimiento) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_movimiento_inventario.php',
        type: 'POST',
        data: {
            x1: codigo_movimiento,
            x2: $('#cod_info_empresa_envia').val(),
            x3: $('#cod_info_empresa_recibe').val(),
            x4: $('#fecha_envia').val(),
            x5: $('#fecha_recibe').val(),
            x6: $('#cod_tipo_inventario').val(),
            x7: $('#cod_inventario').val(),
            x8: $('#cod_unidad_medida').val(),
            x9: $('#num_lote').val(),
            x10: $('#cantidad_enviada').val(),
            x11: $('#cantidad_recibida').val(),
            x12: $('#motivo_perdida').val(),
            x13: $('#cantidad_perdida').val()
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', mensaje, tipo);
            codigo_movimiento = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_movimiento_inventario(codigo_movimiento);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
}



/*
 * Función que permite cargar vista para ver información de un articulo vario y actualizarla
 */
function inv_vista_inventario_vario(codigo_inventario_vario) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_inventario_varios.php',
        data: {
            cod_vario: codigo_inventario_vario
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}

/*Funcion que permite guardar un proveedor con su información
 */
function inv_guardar_inventario_vario(codigo_inventario_vario) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_inventario_vario.php',
        type: 'POST',
        data: {
            x1: codigo_inventario_vario,
            x2: $('#cod_info_empresa').val(),
            x3: $('#codigo_producto').val(),
            x4: $('#nombre_producto').val(),
            x5: $('#cantidad_producto').val(),
            x6: $('#cod_unidad_medida').val(),
            x7: $('#orden_compra').val(),
            x8: $('#fecha_inventario').val()
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', mensaje, tipo);
            codigo_inventario_vario = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_inventario_vario(codigo_inventario_vario);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
}


/*
 * Función que permite cargar vista para ver información de un articulo vario y actualizarla
 */
function inv_vista_orden_compra(codigo_orden_compra, inicio, limite) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_ordenes_compra.php',
        data: {
            cod_orden_compra: codigo_orden_compra,
            inicio: inicio,
            limite: limite
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}

/*Funcion que permite guardar una orden de compra nueva o actualizar una existente*/
function inv_guardar_orden_compra(codigo_orden_compra, ext_adjunto, flag_orden_compra) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_orden_compra.php',
        type: 'POST',
        data: {
            x1: codigo_orden_compra,
            x2: $('#cod_info_empresa').val(),
            // x2: $('#cod_sembradores').val(),
            x3: $('#cod_proveedor').val(),
            x4: $('#fecha_orden').val(),
            x5: $('#fecha_recibido_pedido').val(),
            x6: $('#observaciones').val(),
            x7: ext_adjunto,
            x8: flag_orden_compra,
            x9: $('#num_orden_compra').val()
        },
    }).done(function (data) {
        jQuery.ajaxSetup({ async: false });
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        // grl_mensaje('', mensaje, tipo);
        codigo_orden_compra = info[2];
        respuesta = codigo_orden_compra;
        jQuery.ajaxSetup({ async: true });
        return codigo_orden_compra;
        // if (flag_orden_compra == 3 && info[0] == 0) {
        //     inv_notificar_orden_de_compra_aprobada(codigo_orden_compra, $('#cod_info_empresa').val());
        // }
        //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
        //inv_vista_orden_compra(codigo_orden_compra);
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
    return respuesta;
}



/*
 * Función que obtiene los provedores activos
 */
function inv_constructor_listado_productos_proveedor(cod_proveedor, id = 'cod_producto') {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_productos_proveedor.php',
        dataType: 'json',
        data: ({
            x1: cod_proveedor
        }),
        success: function (data) {
            $('#' + id).empty();
            $('#' + id).append(new Option('Select', '-b'))
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        //$('#' + id).append(new Option(item.nombre_contacto + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_proveedor));
                        $('#' + id).append('<option value="' + item.cod_detalle + '" data-flag_tipo_inventario="' + item.flag_tipo_inventario + '">' + item.nombre_producto + ' (' + item.codigo_producto + ')</option>');
                    });
                } else {
                    //$('#' + id).append(new Option(data[0].nombre_contacto + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_proveedor));
                    $('#' + id).append('<option value="' + data[0].cod_detalle + '" data-flag_tipo_inventario="' + data[0].flag_tipo_inventario + '">' + data[0].nombre_producto + ' (' + data[0].codigo_producto + ')</option>');
                }
            } else {
                $('#' + id).append(new Option('No hay opciones', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}


/*Funcion que permite guardar un proveedor con su información
 */
function inv_guardar_producto_orden_compra(codigo_orden_compra, cod_detalle) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_producto_orden_compra.php',
        type: 'POST',
        data: {
            x1: codigo_orden_compra,
            x2: $('#cod_producto').val(),
            x3: $('#cantidad').val(),
            x4: cod_detalle,
            x5: $('#cod_unidad_medida').val(),
        },
    }).done(function (data) {
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);
        codigo_orden_compra = info[2];
        //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
        inv_vista_orden_compra(codigo_orden_compra);
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
    return respuesta;
}



/*Funcion que permite desactivar un producto de orden de compra
 */
function inv_eliminar_producto_orden_compra(cod_producto, codigo_orden_compra) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_eliminar_producto_orden_compra.php',
        type: 'POST',
        data: {
            x1: cod_producto
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', mensaje, tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_orden_compra(codigo_orden_compra);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}

function inv_notificar_orden_de_compra(codigo_orden_compra, cod_info_empresa) {
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_notificar_orden_de_compra.php',
        type: 'POST',
        data:
        {
            x1: codigo_orden_compra,
            x2: cod_info_empresa
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', mensaje, tipo);
        })
        .fail(function () {
            console.log("error");
        })
        .always(function () {
            console.log("complete");
        });

}


/*
 * Función que obtiene las unidades de medida activas
 */
function inv_constructor_listado_unidades_medida_relacionadas(cod_unidad_medida, id = 'cod_unidad_medida') {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_unidades_medida_relacionadas.php',
        dataType: 'json',
        data: ({
            x1: cod_unidad_medida
        }),
        success: function (data) {
            $('#' + id).empty();
            $('#' + id).append(new Option('Select', '-b'))
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        //$('#' + id).append(new Option(item.unidad_medida + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_unidad_medida));
                        $('#' + id).append('<option value="' + item.cod_unidad_medida + '" ' + (item.activo == 1 ? '' : 'disabled="disabled"') + '>' + item.unidad_medida + '</option>');
                    });
                } else {
                    //$('#' + id).append(new Option(data[0].unidad_medida + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_unidad_medida));
                    $('#' + id).append('<option value="' + data[0].cod_unidad_medida + '" ' + (data[0].activo == 1 ? '' : 'disabled="disabled"') + '>' + data[0].unidad_medida + '</option>');
                }
            } else {
                $('#' + id).append(new Option('No hay opciones', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}

function inv_notificar_orden_de_compra_aprobada(codigo_orden_compra, cod_info_empresa) {
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_notificar_orden_de_compra_aprobada.php',
        type: 'POST',
        data:
        {
            x1: codigo_orden_compra,
            x2: cod_info_empresa
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', mensaje, tipo);
        })
        .fail(function () {
            console.log("error");
        })
        .always(function () {
            console.log("complete");
        });

}

function inv_verificar_notificar_inventario_alerta() {
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_verificar_notificar_inventario_alerta.php',
        type: 'POST',
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            //grl_mensaje('', mensaje, tipo);
        })
        .fail(function () {
            console.log("error");
        })
        .always(function () {
            console.log("complete");
        });

}


function inv_cargar_info_producto_orden_compra(cod_detalle) {
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_cargar_info_producto_orden_compra.php',
        type: 'POST',
        dataType: 'json',
        data: {
            x1: cod_detalle
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            inv_constructor_listado_productos_proveedor($('#cod_proveedor').val());
            inv_constructor_listado_unidades_medida();
            $('#cod_producto').selectpicker('val', data[0].cod_detalle_producto);
            $('#cod_unidad_medida').selectpicker('val', data[0].cod_unidad_medida);
            $('#cantidad').val(data[0].cantidad);
            $('.selectpicker').selectpicker('refresh');
            $('#modal_producto').modal('show');
            console.log("cod_detalle_producto: " + data[0].cod_detalle_producto);
            console.log("cod_unidad_medida: " + data[0].cod_unidad_medida);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            console.log("error");
        })
        .always(function () {
            console.log("complete");
        });

}

function inv_crear_excel_orden_compra(codigo_orden_compra) {
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_crear_excel_orden_compra.php',
        type: 'POST',
        data:
        {
            x1: codigo_orden_compra
        },
    })
        .done(function () {
            console.log("success");
        })
        .fail(function () {
            console.log("error");
        })
        .always(function () {
            console.log("complete");
        });
}


/*
 * Función que obtiene las maquinarias activas
 */
function inv_constructor_listado_maquinarias_tipo_aplicacion(id = 'cod_inventario_maquinaria', cod_info_empresa, cod_tipo_aplicacion = 1) {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_maquinarias_tipo_aplicacion.php',
        dataType: 'json',
        data: ({
            x1: cod_info_empresa,
            x2: cod_tipo_aplicacion
        }),
        success: function (data) {
            $('#' + id).empty();
            //$('#' + id).append(new Option('Select', '-b'))
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        //$('#' + id).append(new Option(item.nombre_maquinaria + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_inventario));
                        $('#' + id).append('<option value="' + item.cod_inventario + '" ' + (item.activo == 1 ? '' : 'disabled="disabled"') + '>' + item.nombre_maquinaria + '</option>');
                    });
                } else {
                    //$('#' + id).append(new Option(data[0].nombre_maquinaria + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_inventario));
                    $('#' + id).append('<option value="' + data[0].cod_inventario + '" ' + (data[0].activo == 1 ? '' : 'disabled="disabled"') + '>' + data[0].nombre_maquinaria + '</option>');
                }
            } else {
                $('#' + id).append(new Option('No hay opciones', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}

/*
* Función que permite copiar un item de inventario de semilla a varias fincas más.
 */

function inv_copiar_inventario_semilla(codigo_inventario_semilla, cod_info_empresa) {
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_copiar_inventario_semilla.php',
        type: 'POST',
        data:
        {
            x1: codigo_inventario_semilla,
            x2: cod_info_empresa
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            $('#modal_copiar_inventario').modal('hide');
            console.log("success");
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', mensaje, tipo);
            codigo_inventario_semilla = info[2];
            inv_vista_inventario_semilla(codigo_inventario_semilla);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            console.log("error");
        })
        .always(function () {
            console.log("complete");
        });

}

/*
* Función que permite copiar un item de inventario de quimico a varias fincas más.
 */

function inv_copiar_inventario_quimico(codigo_inventario_quimico, cod_info_empresa) {
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_copiar_inventario_quimico.php',
        type: 'POST',
        data:
        {
            x1: codigo_inventario_quimico,
            x2: cod_info_empresa
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            $('#modal_copiar_inventario').modal('hide');
            console.log("success");
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', mensaje, tipo);
            codigo_inventario_quimico = info[2];
            inv_vista_inventario_quimico(codigo_inventario_quimico);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            console.log("error");
        })
        .always(function () {
            console.log("complete");
        });

}


/*Funcion que permite guardar un proveedor con su información
 */
function inv_guardar_producto_recibido_orden_compra(codigo_orden_compra, cod_detalle) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_producto_recibido_orden_compra.php',
        type: 'POST',
        data: {
            x1: codigo_orden_compra,
            x2: cod_detalle,
            x3: $('#cod_producto_recibido').val(),
            x4: $('#cod_unidad_medida_recibido').val(),
            x5: $('#cantidad_recibido').val(),
            x6: $('#fecha_entrega_recibido').val(),
            x7: $('#observaciones_recibido').val(),
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', mensaje, tipo);
            codigo_orden_compra = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_orden_compra(codigo_orden_compra);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return respuesta;
}

function inv_cargar_detalle_entrega_producto(cod_detalle) {
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_cargar_detalle_entrega_producto.php',
        type: 'POST',
        data:
        {
            x1: cod_detalle
        },
    })
        .done(function (data) {
            $('#tbody_detalle_entrega').empty().append(data);
        })
        .fail(function () {
            console.log("error");
        })
        .always(function () {
            console.log("complete");
        });

}

// NUEVO

/*
 * Función que carga todas las categorías
 */
function conf_constructor_listado_categorias(id = 'cod_categoria', flag_seleccione = 1) {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_categorias_semillas.php',
        dataType: 'json',

        success: function (data) {
            $('#' + id).empty();
            if (flag_seleccione == 1)
                $('#' + id).append(new Option('Select', '-b'));
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        $('#' + id).append(new Option(item.nombre_categoria, item.cod_categoria));
                    });
                } else {
                    $('#' + id).append(new Option(data[0].nombre_categoria, data[0].cod_categoria));
                }
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}
/*
 * Función que carga todos los grupos de siembrea disponibles
 */
function conf_constructor_listado_grupo_de_siembra(id = 'cod_grupo_siembra', flag_seleccione = 1) {

    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_grupo_de_siembra.php',
        dataType: 'json',
        success: function (data) {
            $('#' + id).empty();
            if (flag_seleccione == 1)
                $('#' + id).append(new Option('Select', '-b'));
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        $('#' + id).append(new Option(item.nombre_grupo_siembra, item.cod_grupo_siembra));
                    });
                } else {
                    $('#' + id).append(new Option(data[0].nombre_grupo_siembra, data[0].cod_grupo_siembra));
                }
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}

/*
 * Función que carga el listado de los ragos disponibles para las semillas
 */
function conf_constructor_listado_rasgo(id = 'cod_rasgo', flag_seleccione = 1) {

    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_rasgo.php',
        dataType: 'json',
        success: function (data) {
            $('#' + id).empty();
            if (flag_seleccione == 1)
                $('#' + id).append(new Option('Select', '-b'));
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        $('#' + id).append(new Option(item.nombre_rasgo, item.cod_rasgo));
                    });
                } else {
                    $('#' + id).append(new Option(data[0].nombre_rasgo, data[0].cod_rasgo));
                }
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}
/*
 * Función que carga el listado de los ragos disponibles para las semillas
 */
function conf_constructor_listado_familias_de_semillas(id = 'cod_familia', flag_seleccione = 1) {

    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_familia_de_semillas.php',
        dataType: 'json',
        success: function (data) {
            $('#' + id).empty();
            if (flag_seleccione == 1)
                $('#' + id).append(new Option('Select', '-b'));
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        $('#' + id).append(new Option(item.nombre_familia, item.cod_familia));
                    });
                } else {
                    $('#' + id).append(new Option(data[0].nombre_familia, data[0].cod_familia));
                }
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}

/*
 * Función que carga el listado de los vendores sin flitro alguno (Desactivado)
 */
function conf_constructor_listado_venedores(id = 'cod_vendedores', flag_seleccione = 1) {
    dataEjemplo = [
        {
            nombre_vendedor: "Vendor #1",
            cod_vendedor: "1",
        },
        {
            nombre_vendedor: "Vendor #2",
            cod_vendedor: "2",
        },
        {
            nombre_vendedor: "Vendor #3",
            cod_vendedor: "3",
        },
        {
            nombre_vendedor: "Vendor #4",
            cod_vendedor: "4",
        },
        {
            nombre_vendedor: "Vendor #5",
            cod_vendedor: "5",
        },
        {
            nombre_vendedor: "Vendor #6",
            cod_vendedor: "6",
        },
    ];

    $('#' + id).empty();
    $('#' + id).append(new Option('Select', '-b'));

    dataEjemplo.forEach(function (element) {
        // $('#' + id).append(new Option(element.nombre_vendedor, element.cod_vendedor));
    });

}

// FUNCIONES PARA EL MÓDULO DE CATEGORÍAS
/*Funcion que permite guardar una categoría de semilla
 */
function inv_guardar_categoria_semilla(codigo_categoria) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_categoria.php',
        type: 'POST',
        data: {
            x1: codigo_categoria,
            x2: $('#nombre_categoria').val(),
            x3: $('#descripcion_categoria').val(),
            x4: ($('#activo').is(':checked') ? 1 : 0),

        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', mensaje, tipo);
            codigo_categoria = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_categoria_semillas(codigo_categoria);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
}

/*Funcion que permite cambiar estado activo/des de una categoría de semilla
 */
function inv_cambiar_estado_categoria_semillas(cod_categoria, valor) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_cambiar_estado_categoria_semilla.php',
        type: 'POST',
        data: {
            x1: cod_categoria,
            x2: valor
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', mensaje, tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_categoria_semillas(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}
/*
 * Función que permite cargar vista para ver información de una categoría de semillas
 */
function inv_vista_categoria_semillas(codigo_categoria) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_categorias.php',
        data: {
            cod_categoria: codigo_categoria
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}

// FUNCIONES PARA EL MÓDULO DE RASGOS
//Funcion que permite guardar/actualizar un rasgo especifico
function inv_guardar_rasgo(codigo_rasgo) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_rasgo.php',
        type: 'POST',
        data: {
            x1: codigo_rasgo,
            x2: $('#nombre_rasgo').val(),
            x3: $('#descripcion_rasgo').val(),
            x4: ($('#activo').is(':checked') ? 1 : 0),

        },
    }).done(function (data) {
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);
        codigo_rasgo = info[2];
        //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
        inv_vista_rasgos(codigo_rasgo);
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
}

/*Funcion que permite cambiar estado activo/des de un rasgo
 */
function inv_cambiar_estado_rasgos(cod_rasgo, valor) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_cambiar_estado_rasgo.php',
        type: 'POST',
        data: {
            x1: cod_rasgo,
            x2: valor
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', mensaje, tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_rasgos(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}
/*
 * Función que permite cargar vista para ver información de un rasgos
 */
function inv_vista_rasgos(codigo_rasgo) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_rasgos.php',
        data: {
            cod_rasgo: codigo_rasgo
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}

// FUNCIONES PARA EL MÓDULO DE FAMILIAS
//Funcion que permite guardar/actualizar una familia especifica
function inv_guardar_familia(codigo_familia) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_familia.php',
        type: 'POST',
        data: {
            x1: codigo_familia,
            x2: $('#nombre_familia').val(),
            x3: $('#descripcion_familia').val(),
            x4: ($('#activo').is(':checked') ? 1 : 0),

        },
    }).done(function (data) {
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);
        codigo_familia = info[2];
        //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
        inv_vista_familia(codigo_familia);
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
}

/*Funcion que permite cambiar estado activo/des de una familia*/
function inv_cambiar_estado_familia(cod_familia, valor) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_cambiar_estado_familia.php',
        type: 'POST',
        data: {
            x1: cod_familia,
            x2: valor
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', mensaje, tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_familia(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}
/*
 * Función que permite cargar vista para ver información de un rasgos
 */
function inv_vista_familia(codigo_familia) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_familias.php',
        data: {
            cod_familia: codigo_familia
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}

// FUNCIONES PARA EL MÓDULO DE GRUPO DE SIEMBRA
//Funcion que permite guardar/actualizar un grupo de siembrea especifico
function inv_guardar_grupo_de_siembra(codigo_grupo_siembra) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_grupo_de_siembra.php',
        type: 'POST',
        data: {
            x1: codigo_grupo_siembra,
            x2: $('#nombre_rasgo').val(),
            x3: $('#descripcion_rasgo').val(),
            x4: ($('#activo').is(':checked') ? 1 : 0),

        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', mensaje, tipo);
            codigo_grupo_siembra = info[2];
            inv_vista_grupo_de_siembra(codigo_grupo_siembra);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
}

/*Funcion que permite cambiar estado activo/des de un grupo de siembra
 */
function inv_cambiar_estado_grupo_de_siembra(cod_grupo_siembra, valor) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_cambiar_estado_grupo_de_siembra.php',
        type: 'POST',
        data: {
            x1: cod_grupo_siembra,
            x2: valor
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', mensaje, tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            inv_vista_grupo_de_siembra(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}
/*
 * Función que permite cargar vista para ver información de un grupo de siembra
 */
function inv_vista_grupo_de_siembra(codigo_grupo_siembra) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_grupos_de_siembra.php',
        data: {
            cod_grupo_siembra: codigo_grupo_siembra
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}


// FUNCIONES PARA EL LISTADO GENERAL DE LAS SEMILLAS
/*Funcion que permite cambiar estado activo/des de una semilla*/
function inv_cambiar_estado_semilla(cod_inventario_semilla, valor) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_cambiar_estado_inventario_semilla.php',
        type: 'POST',
        data: {
            x1: cod_inventario_semilla,
            x2: valor
        },
    })
        .done(function (data) {

            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', mensaje, tipo);

            // inv_vista_listado_semillas(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}

/*
 * Función que permite cargar vista para ver el listado general de semillas
 */
function inv_vista_listado_semillas(codigo_inventario_semilla) {

    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_listado_semillas.php',
        data: {
            cod_semilla: codigo_inventario_semilla
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}
/**
 * Función para registrar las adiciones o sustracciones que se hagan
 * en el listado general de las semillas
 */
function inv_guardar_los_cambios_de_cantidades_de_semillas(codigo_inventario_semilla, cantidadSumar, cantidadRestar, razon, fechaActual, cantidadIngresada) {

    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_adicion_o_sustraccion_semillas.php',
        type: 'POST',
        data: {
            x1: codigo_inventario_semilla,
            x2: cantidadSumar,
            x3: cantidadRestar,
            x4: razon,
            x5: fechaActual,
            x6: cantidadIngresada,

        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', mensaje, tipo);
            codigo_inventario_semilla = info[2];
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
}


/*Funcion que permite guardar un proveedor con su información
 */
function inv_guardar_productos_orden_compra(codigo_orden_compra, cod_detalle = 0, datosProductos) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_productos_orden_compra.php',
        type: 'POST',
        data: {
            x1: codigo_orden_compra,
            x2: $('#cod_producto').val(),
            x3: $('#cantidad').val(),
            x4: cod_detalle,
            x5: $('#cod_unidad_medida').val(),
            x6: datosProductos,
        },
    }).done(function (data) {
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);
        codigo_orden_compra = info[2];
        //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
        // inv_vista_orden_compra(codigo_orden_compra);
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
    return respuesta;
}

/*Funcion que guarda todos los productos de una orden de compra */
function inv_guardar_productos_recibido_orden_compra(codigo_orden_compra, cod_detalle, datosProductos) {
    // Obtener la fecha actual
    const fechaActual = new Date();

    // Obtener los componentes de la fecha
    const año = fechaActual.getFullYear();
    const mes = String(fechaActual.getMonth() + 1).padStart(2, '0'); // Sumar 1 al mes porque en JavaScript los meses van de 0 a 11
    const dia = String(fechaActual.getDate()).padStart(2, '0');
    const horas = String(fechaActual.getHours()).padStart(2, '0');
    const minutos = String(fechaActual.getMinutes()).padStart(2, '0');
    const segundos = String(fechaActual.getSeconds()).padStart(2, '0');

    // Formatear la fecha en el formato deseado
    const fechaFormateada = `${mes}-${dia}-${año} ${horas}:${minutos}:${segundos}`;

    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_productos_recibido_orden_compra.php',
        type: 'POST',
        data: {
            x1: codigo_orden_compra,
            x2: cod_detalle,
            x3: $('#cod_producto_recibido').val(),
            x4: $('#cod_unidad_medida_recibido').val(),
            x5: $('#cantidad_recibido').val(),
            x6: fechaFormateada, //$('#fecha_entrega_recibido').val(),
            x7: "--", //Observaciones,
            x8: datosProductos,
            x9: $('#cod_info_empresa').val(),
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', mensaje, tipo);
            codigo_orden_compra = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            // inv_vista_orden_compra(codigo_orden_compra);
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return respuesta;
}

/*Funcion que permite guardar una orden de compra nueva o actualizar una existente*/
function inv_guardar_y_registrar_orden_compra(codigo_orden_compra, ext_adjunto, flag_orden_compra, datosProductos) {
    var respuesta;
    // Obtener la fecha actual
    const fechaActual = new Date();

    // Obtener los componentes de la fecha
    const año = fechaActual.getFullYear();
    const mes = String(fechaActual.getMonth() + 1).padStart(2, '0'); // Sumar 1 al mes porque en JavaScript los meses van de 0 a 11
    const dia = String(fechaActual.getDate()).padStart(2, '0');
    const horas = String(fechaActual.getHours()).padStart(2, '0');
    const minutos = String(fechaActual.getMinutes()).padStart(2, '0');
    const segundos = String(fechaActual.getSeconds()).padStart(2, '0');

    // Formatear la fecha en el formato deseado
    const fechaFormateada = `${mes}-${dia}-${año} ${horas}:${minutos}:${segundos}`;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_y_registrar_orden_compra.php',
        type: 'POST',
        data: {
            x1: codigo_orden_compra,
            x2: $('#cod_info_empresa').val(),
            x3: $('#cod_proveedor').val(),
            x4: $('#fecha_orden').val(),
            x5: $('#fecha_recibido_pedido').val(),
            x6: $('#observaciones').val(),
            x7: ext_adjunto,
            x8: flag_orden_compra,
            x9: $('#num_orden_compra').val(),

            x11: cod_detalle,
            x12: $('#cod_producto_recibido').val(),
            x13: $('#cod_unidad_medida_recibido').val(),
            x14: $('#cantidad_recibido').val(),
            x15: fechaFormateada, //$('#fecha_entrega_recibido').val(),
            x16: "--", //Observaciones,
            x17: datosProductos,
            x18: $('#cod_info_empresa').val(),
        },
    }).done(function (data) {
        jQuery.ajaxSetup({ async: false });
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);
        codigo_orden_compra = info[2];
        respuesta = codigo_orden_compra;
        jQuery.ajaxSetup({ async: true });
        return codigo_orden_compra;
        // if (flag_orden_compra == 3 && info[0] == 0) {
        //     inv_notificar_orden_de_compra_aprobada(codigo_orden_compra, $('#cod_info_empresa').val());
        // }
        //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
        //inv_vista_orden_compra(codigo_orden_compra);
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
    return respuesta;
}


// FUNCIONES PARA LAS PLANTACIONES NUEVAS
/*
 * Función que carga el listado de los sembradores activos
 */
function conf_constructor_listado_sembradores(id = 'cod_sembradores') {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_sembradores.php',
        dataType: 'json',
        success: function (data) {
            $('#' + id).empty();
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        $('#' + id).append(new Option(item.nombre, item.cod_sembrador));
                    });
                } else {
                    $('#' + id).append(new Option(data[0].nombre, data[0].cod_sembrador));
                }
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}
/*
 * Función que carga el listado de los sembradores activos
 */
function inv_constructor_listado_estados_de_plantacion(id = 'cod_estados') {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_estados_de_plantaciones.php',
        dataType: 'json',
        success: function (data) {
            $('#' + id).empty();
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        $('#' + id).append(new Option(item.abreviatura + " - " + item.nombre, item.cod_estado));
                    });
                } else {
                    $('#' + id).append(new Option(data[0].abreviatura + " - " + data[0].nombre, data[0].cod_estado));
                }
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}
function inv_constructor_listado_localizaciones(id = 'cod_localizacion') {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_localizaciones.php',
        dataType: 'json',
        success: function (data) {
            $('#' + id).empty();
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        $('#' + id).append(new Option(item.nombre, item.cod_localizacion));
                    });
                } else {
                    $('#' + id).append(new Option(data[0].nombre, data[0].cod_localizacion));
                }
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}

/*
* Función que obtiene las semillas activas
*/
function inv_constructor_listado_semillas_activas(id = 'cod_semillas') {
    jQuery.ajaxSetup({ async: false });

    var arrDatosDeSemilla = [];
    var indiceSemilla = 1;
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_semilla_activas.php',
        dataType: 'json',
        success: function (data) {
            $('#' + id).empty();
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        arrDatosDeSemilla["nombre_semilla_" + item.cod_inventario] = item.nombre;
                        arrDatosDeSemilla["cantidad_semilla_" + item.cod_inventario] = item.cantidad_semilla_sin_formato;
                        arrDatosDeSemilla["id_semilla_" + item.cod_inventario] = item.cod_inventario;
                        arrDatosDeSemilla["semillas_pre_plantadas_" + item.cod_inventario] = item.semillas_por_plantaciones;

                        const nuevaOpcion = $('<option>', {
                            text: item.nombre + " (" + item.cantidad_semilla + " " + item.abreviatura_medida + ")",
                            value: item.cod_inventario,
                            class: parseFloat(item.cantidad_semilla) > 0 ? 'cantidad_positiva' : 'cantidad_negativa',
                        });

                        let datosSemilla = new Option(item.nombre + " - " + item.cantidad_semilla + " " + item.abreviatura_medida, item.cod_inventario);
                        // datosSemilla.addClass("cantidad_negativa");
                        $('#' + id).append(nuevaOpcion);
                        indiceSemilla++;
                    });
                } else {
                    arrDatosDeSemilla["nombre_semilla_" + indiceSemilla] = data[0].nombre;
                    arrDatosDeSemilla["cantidad_semilla_" + indiceSemilla] = data[0].cantidad_semilla;
                    arrDatosDeSemilla["id_semilla_" + indiceSemilla] = data[0].cod_inventario;
                    arrDatosDeSemilla["semillas_pre_plantadas_" + indiceSemilla] = data[0].semillas_por_plantaciones;
                    $('#' + id).append(new Option(data[0].nombre + " - " + data[0].cantidad_semilla + " " + data[0].abreviatura_medida, data[0].cod_inventario));
                }
            } else {
                $('#' + id).append(new Option('No hay opciones', '-b'));
            }

            // Agregar la nueva opción al elemento select
            $('.selectpicker_semillas').selectpicker('refresh');
            jQuery.ajaxSetup({ async: true });

        }
    });
    return arrDatosDeSemilla;
}
/*
* Función que obtiene las semillas activas
*/
function inv_constructor_listado_semillas_por_invernadero_activas(cod_info_empresa, id = 'cod_semillas') {
    jQuery.ajaxSetup({ async: false });

    var arrDatosDeSemilla = [];
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_semilla_por_invernadero_activas.php',
        data: {
            x1: cod_info_empresa
        },
        dataType: 'json',
        success: function (data) {
            $('#' + id).empty();
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        arrDatosDeSemilla["nombre_semilla_" + item.cod_inventario] = item.nombre;
                        arrDatosDeSemilla["cantidad_semilla_" + item.cod_inventario] = item.cantidad_semilla_sin_formato;
                        arrDatosDeSemilla["id_semilla_" + item.cod_inventario] = item.cod_inventario;
                        arrDatosDeSemilla["semillas_pre_plantadas_" + item.cod_inventario] = item.semillas_por_plantaciones;
                        arrDatosDeSemilla["over_seed_" + item.cod_inventario] = item.over_seed;

                        const nuevaOpcion = $('<option>', {
                            text: item.nombre + " (" + item.cantidad_semilla + " " + item.abreviatura_medida + ")",
                            value: item.cod_inventario,
                            class: parseFloat(item.cantidad_semilla) > 0 ? 'cantidad_positiva' : 'cantidad_negativa',
                        });

                        let datosSemilla = new Option(item.nombre + " - " + item.cantidad_semilla + " " + item.abreviatura_medida, item.cod_inventario);
                        // datosSemilla.addClass("cantidad_negativa");
                        $('#' + id).append(nuevaOpcion);
                    });
                } else {
                    arrDatosDeSemilla["nombre_semilla_" + data[0].cod_inventario] = data[0].nombre;
                    arrDatosDeSemilla["cantidad_semilla_" + data[0].cod_inventario] = data[0].cantidad_semilla;
                    arrDatosDeSemilla["id_semilla_" + data[0].cod_inventario] = data[0].cod_inventario;
                    arrDatosDeSemilla["semillas_pre_plantadas_" + data[0].cod_inventario] = data[0].semillas_por_plantaciones;
                    arrDatosDeSemilla["over_seed_" + data[0].cod_inventario] = data[0].over_seed;
                    $('#' + id).append(new Option(data[0].nombre + " - " + data[0].cantidad_semilla + " " + data[0].abreviatura_medida, data[0].cod_inventario));
                }
            } else {
                $('#' + id).append(new Option('No hay opciones', '-b'));
            }

            // Agregar la nueva opción al elemento select
            $('.selectpicker_semillas').selectpicker('refresh');
            jQuery.ajaxSetup({ async: true });

        }
    });
    return arrDatosDeSemilla;
}

/*
* Función que carga el listado todos los invernaderos asociados a una semilla especifica
*/
function inv_lisatod_invernaderos_por_codigo_de_inventario(codigo_inventario_semilla_para_modal, id = 'cod_info_empresa2') {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_invernaderos_por_semilla.php',
        dataType: 'json',
        data: {
            x1: codigo_inventario_semilla_para_modal,
        },
        success: function (data) {
            $('#' + id).empty();
            $('#' + id).append(new Option('Select', '-b'))
            try {


                if (data != null) {
                    if (data.length > 1) {
                        $.each(data, function (i, item) {
                            $('#' + id).append(new Option(item.nombre_empresa, item.cod_info_empresa));
                        });
                    } else {
                        $('#' + id).append(new Option(data[0].nombre_empresa, data[0].cod_info_empresa));
                    }
                } else {
                    $('#' + id).append(new Option('There was an error loading the greenhouses', '-b'));
                }

                $('.selectpicker').selectpicker('refresh');
            } catch (error) {
                console.error({ error });
            }
        },

    });
}
/*
* Función que carga el listado todos los invernaderos asociados a una semilla especifica
*/
function inv_lisatod_invernaderos_por_nombre_de_semilla(nombre_semilla, id = 'cod_info_empresa2') {
    let arrDatosInvernadero = {};

    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_invernaderos_por_nombre_semilla.php',
        dataType: 'json',
        data: {
            x1: nombre_semilla,
        },
        success: function (data) {
            $('#' + id).empty();
            $('#' + id).append(new Option('Select', '-b'))
            try {


                if (data != null) {
                    if (data.length > 1) {
                        $.each(data, function (i, item) {
                            arrDatosInvernadero["cantidad_semilla_" + item.cod_inventario] = item.cantidad_individual;
                            arrDatosInvernadero["ids_de_empresa_para_listado_en_tabla_" + item.cod_inventario] = item.cod_inventario + "_" + item.cod_empresa;
                            $('#' + id).append(new Option(item.nombre_empresa, item.cod_inventario));
                        });
                    } else {
                        arrDatosInvernadero["cantidad_semilla_" + data[0].cod_inventario] = data[0].cantidad_individual;
                        arrDatosInvernadero["ids_de_empresa_para_listado_en_tabla_" + data[0].cod_inventario] = data[0].cod_inventario + "_" + data[0].cod_empresa;

                        $('#' + id).append(new Option(data[0].nombre_empresa, data[0].cod_inventario));
                    }
                } else {
                    $('#' + id).append(new Option('There was an error loading the greenhouses', '-b'));
                }

                // $('.selectpicker').selectpicker('refresh');
                $('.cod_info_empresa2').selectpicker('refresh');
            } catch (error) {
                console.error({ error });
            }
        },
    });
    return arrDatosInvernadero;
}

/*
    * Función que verifica la cantidad maxima de semillas, tomando en cuenta la cantidad de semillas 
    * ingresadas en el formulario de "nueva plantación"
*/
function inv_comprabar_disponibilidad_maxima_de_semilla(cod_semilla) {
    // TODO: REALIZAR LOGICA COMPLETA
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_semilla_activas.php',
        dataType: 'json',
        success: function (data) {
            // $('#' + id).empty();
            // if (data != null) {
            //     if (data.length > 1) {
            //         $.each(data, function (i, item) {
            //             $('#' + id).append(new Option(item.nombre, item.cod_inventario));
            //         });
            //     } else {
            //         $('#' + id).append(new Option(data[0].nombre, data[0].cod_inventario));
            //     }
            // } else {
            //     $('#' + id).append(new Option('No hay opciones', '-b'));
            // }

            $('.selectpicker_semillas').selectpicker('refresh');
        }
    });
}

/*Funcion que guarda una plantación*/

function inv_guardar_plantacion(datosSemillas, arrIndiceDatosSemillas) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_plantacion.php',
        type: 'POST',
        data: {
            x1: $('#numero_orden').val(),
            x2: $('#cod_info_empresa').val(),
            x3: $('#fecha_orden').val(),
            x4: datosSemillas,
            x5: arrIndiceDatosSemillas,
            x6: $('#cod_estados').val(),

        },
    }).done(function (data) {

        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);
        codigo_orden_compra = info[2];
        inv_vista_nueva_plantacion(0);
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
    return respuesta;
}
/*Funcion que elimina una plantación especifica*/

function inv_eliminar_plantacion(numeroOrden_previo) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_eliminar_plantacion.php',
        type: 'POST',
        data: {
            x1: numeroOrden_previo

        },
    }).done(function (data) {

        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);
        codigo_orden_compra = info[2];
        inv_vista_listado_plantaciones(0);
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
    return respuesta;
}
/*
Funcion que elimina un transplante
Se usa el numero de ticket para la eliminación
*/

function inv_eliminar_trasplante(pNumeroTicket) {
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_eliminar_trasplante.php',
        type: 'POST',
        data: {
            x1: pNumeroTicket

        },
    }).done(function (data) {

        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);
        codigo_orden_compra = info[2];
        inv_vista_listado_trasplantes();
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
}
/*Funcion que guarda una plantación*/

function inv_actualizar_plantacion(numeroOrden_previo, datosSemillas, arrIndiceDatosSemillas) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_actualizar_plantacion.php',
        type: 'POST',
        data: {
            x0: numeroOrden_previo,
            x1: $('#numero_orden').val(),
            x2: $('#cod_sembradores').val(),
            x3: $('#fecha_orden').val(),
            x4: datosSemillas,
            x5: arrIndiceDatosSemillas,
            x6: $('#cod_estados').val(),
            x7: $('#cod_info_empresa').val()
        },
    }).done(function (data) {

        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);
        codigo_orden_compra = info[2];
        inv_vista_listado_plantaciones()
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
    return respuesta;
}

function inv_buscar_plantacion(cod_plantacion, numero_orden) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_buscar_plantacion.php',
        type: 'POST',
        data: {
            x1: cod_plantacion,
            x2: numero_orden,

        },
    }).done(function (data) {

        respuesta = data;
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
    return respuesta;
}


/*
 * Función que permite cargar vista para ver la sección de plantacion nueva
 */
function inv_vista_nueva_plantacion(cod_plantacion, numero_orden) {

    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_nueva_plantacion.php',
        data: {
            cod_plantacion: cod_plantacion,
            numero_orden: numero_orden,
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}

/*
 * Función que permite cargar vista para el listado de las plantaciones
 */
function inv_vista_listado_plantaciones() {

    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_listado_plantaciones.php',

        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}


// FUNCIONES PARA LAS PLANTACIONES NUEVAS
/*
 * Función que carga el listado de los números de orden activos
 */
function inv_constructor_listado_numero_de_orden(id = 'cod_numeros_de_orden') {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_numero_orden_activos.php',
        dataType: 'json',
        success: function (data) {
            $('#' + id).empty();
            $('#' + id).append(new Option('Select', '-b'))
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        $('#' + id).append(new Option(item.numero_orden, item.numero_orden));
                    });
                } else {
                    $('#' + id).append(new Option(data[0].numero_orden, data[0].numero_orden));
                }
            } else {
                $('#' + id).append(new Option('There are no registered numbers', '-b'));
            }

            $('.selectpicker_numero_orden').selectpicker('refresh');
        }
    });
}
/*
 * Función que carga el listado de las semillas asociadas a número de orden especifico, y se asocia a su "Line item"
 */
function inv_listado_de_semillas_con_line_item(id = 'cod_line_item') {
    let arrDatosDePlantas = {};
    let cantidadTotal = 0;
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/funciones/inv_listado_semillas_por_numero_de_orden.php',
        data: {
            x1: $("#cod_numeros_de_orden").val()

        },
        dataType: 'json',
        success: function (data) {
            $('#' + id).empty();

            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        arrDatosDePlantas["nombre_semilla_" + item.cod_plantacion] = item.nombre_semilla;
                        arrDatosDePlantas["line_item_semilla_" + item.cod_plantacion] = item.item;
                        arrDatosDePlantas["cod_inventario_" + item.cod_plantacion] = item.cod_inventario;
                        arrDatosDePlantas["cantidad_" + item.cod_plantacion] = item.cantidad;
                        arrDatosDePlantas["germinacion_automatica_" + item.cod_plantacion] = item.germinacion_automatica;
                        arrDatosDePlantas["overseed_" + item.cod_plantacion] = item.overseed;
                        arrDatosDePlantas["fecha_final_" + item.cod_plantacion] = item.fecha_final;

                        cantidadTotal = item.cantidad * ((item.overseed / 100) + 1);
                        arrDatosDePlantas["cantidad_total_" + item.cod_plantacion] = cantidadTotal;

                        $('#' + id).append(new Option("(" + item.fecha_final + ") " + item.item + "- " + item.nombre_semilla + " (" + parseInt(cantidadTotal).toLocaleString() + ")", item.cod_plantacion));
                    });
                } else {
                    arrDatosDePlantas["nombre_semilla_" + data[0].cod_plantacion] = data[0].nombre_semilla;
                    arrDatosDePlantas["line_item_semilla_" + data[0].cod_plantacion] = data[0].item;
                    arrDatosDePlantas["cod_inventario_" + data[0].cod_plantacion] = data[0].cod_inventario;
                    arrDatosDePlantas["cantidad_" + data[0].cod_plantacion] = data[0].cantidad;
                    arrDatosDePlantas["germinacion_automatica_" + data[0].cod_plantacion] = data[0].germinacion_automatica;
                    arrDatosDePlantas["overseed_" + data[0].cod_plantacion] = data[0].overseed;
                    arrDatosDePlantas["fecha_final_" + data[0].cod_plantacion] = data[0].fecha_final;

                    cantidadTotal = data[0].cantidad * ((data[0].overseed / 100) + 1);
                    arrDatosDePlantas["cantidad_total_" + data[0].cod_plantacion] = cantidadTotal;

                    $('#' + id).append(new Option("(" + data[0].fecha_final + ") " + data[0].item + "- " + data[0].nombre_semilla + " (" + parseInt(cantidadTotal).toLocaleString() + ")", data[0].cod_plantacion));
                }
            } else {
                $('#' + id).append(new Option('There are no seeds registered to the selected number', '-b'));
            }

            $('.selectpicker_semillas').selectpicker('refresh');
        }

    });
    return arrDatosDePlantas;
}



/*Funcion que guarda una plantación*/

function inv_guardar_trasplante(datosSemillas, arrIndiceDatosSemillas) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_trasplante.php',
        type: 'POST',
        data: {
            x1: $('#numero_ticket').val(),
            x2: $('#cod_info_empresa').val(),
            x3: $('#fecha_entrega').val(),
            x4: datosSemillas,
            x5: arrIndiceDatosSemillas,
            x6: $('#cod_localizacion').val(),
            x7: $('#fecha_recibo').val(),
        },
    }).done(function (data) {
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);
        inv_vista_nuevo_transplante(0);
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
    return respuesta;
}

/*
 * Función que permite cargar vista para ver la sección de plantacion nueva
 */
function inv_vista_nuevo_transplante(numero_ticket) {

    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_nuevo_transplante.php',
        data: {
            numero_ticket: numero_ticket
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}
/*
 * Función que permite cargar vista para ver la sección de plantacion nueva
 */
function inv_vista_listado_trasplantes() {

    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_listado_trasplantes.php',

        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}


/*Funcion que permite cambiar estado activo/des de una unidad de medida
 */
function inv_cambiar_estado_completado_trasplante(cod_trasplante, valor) {
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_cambiar_estado_completado_trasplante.php',
        type: 'POST',
        data: {
            x1: cod_trasplante,
            x2: valor
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', mensaje, tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            // inv_vista_unidad_medida(0);
            inv_vista_lista_transplante(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}

/*Funcion que permite cambiar estado activo/des de todos los transplantes hijos
 */
function inv_cambiar_estado_trasplante(fecha_inicial, fecha_final, cod_inventario, item, flag, comp) {
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_cambiar_estado_trasplante_todo.php',
        type: 'POST',
        data: {
            x1: fecha_inicial,
            x2: fecha_final,
            x3: cod_inventario,
            x4: item,
            x5: flag,
            x6: comp
        },
    })
        .done(function (data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', mensaje, tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            // inv_vista_unidad_medida(0);
            inv_vista_lista_transplante(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}

/*
 * Función que permite cargar vista para ver la sección de plantacion nueva
 */
function inv_vista_lista_transplante(cod_trasplante) {

    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_listado_trasplantes.php',
        data: {
            cod_trasplante: cod_trasplante,
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}
function inv_buscar_trasplante(numero_ticket) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_buscar_trasplante.php',
        type: 'POST',
        data: {
            x1: numero_ticket
        },
    }).done(function (data) {

        respuesta = data;
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
    return respuesta;
}
/*Funcion que guarda una plantación*/

function inv_actualizar_trasplante(numero_ticket_previo, datosSemillas, arrIndiceDatosSemillas) {
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_actualizar_trasplante.php',
        type: 'POST',
        data: {
            x0: numero_ticket_previo,
            x1: $('#numero_ticket').val(),
            x2: $('#cod_info_empresa').val(),
            x3: $('#fecha_entrega').val(),
            x4: datosSemillas,
            x5: arrIndiceDatosSemillas,
            x6: $('#cod_localizacion').val(),
            x7: $('#fecha_recibo').val(),
        },
    }).done(function (data) {
        console.log({ data });
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        // grl_mensaje('', mensaje, tipo);
        grl_mensaje('', "It has been update successfully", 'success');
        inv_vista_lista_transplante(0)
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
}


// MODULO LOCALIZACIONES
function inv_guardar_localizacion(codigo_localizacion) {
    var respuesta;
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_guardar_localizacion.php',
        type: 'POST',
        data: {
            x1: codigo_localizacion,
            x2: $('#nombre_localizacion').val(),
            x3: $('#abreviatura_localizacion').val(),
            x4: $('#descripcion_localizacion').val(),
            x5: ($('#activo').is(':checked') ? 1 : 0),

        },
    }).done(function (data) {
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);
        codigo_localizacion = info[2];
        inv_vista_localizaciones(codigo_localizacion)
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
}
/*Funcion que permite cambiar estado activo/des de un rasgo
 */
function inv_cambiar_estado_localizacion(cod_localizacion, valor, event) {
    event.preventDefault();
    $.ajax({
        url: site_url + 'mod_inventario/funciones/inv_cambiar_estado_localizacion.php',
        type: 'POST',
        data: {
            x1: cod_localizacion,
            x2: valor
        },
    })
        .done(function (data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', mensaje, tipo);
            // inv_vista_localizaciones(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}

/*
 * Función que permite cargar vista para ver información de un rasgos
 */
function inv_vista_localizaciones(cod_localizacion) {

    //toggleModuleNav();
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_inventario/ui/inv_localizaciones.php',
        data: {
            cod_localizacion: cod_localizacion
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}
