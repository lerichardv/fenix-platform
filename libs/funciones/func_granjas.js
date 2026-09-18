var site_url = window.location.protocol + '//' + window.location.hostname + '/';


/*
 * Función que carga el listado los estado de plantaciones
 */
function farm_constructor_listado_estados_de_plantacion(id = 'cod_estados', valorInicial = false) {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_farms/funciones/inv_listado_estados_de_plantaciones.php',
        dataType: 'json',
        success: function (data) {
            $('#' + id).empty();
            if (valorInicial) {
                $('#' + id).append(new Option("Select", "-b"));
            }
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

/**
 * FUNCIONES PARA TEMPORADAS
*/
/*
 * Función que carga el listado de granjas activas
 */
function farm_constructor_listado_temporada(id = 'cod_temporada') {
    jQuery.ajaxSetup({
        async: false
    });
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_farms/funciones/farm_listado_temporadas_activas.php',
        dataType: 'json',
        success: function (data) {

            $('#' + id).empty();
            if (data != null) {
                $.each(data, function (i, item) {
                    $('#' + id).append(new Option(item.temporada, item.cod_temporada));
                });
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });

}
/*
 * Función que permite cargar vista de Temporadas
 */
function granj_vista_temporada(cod_temporada) {
    jQuery.ajaxSetup({
        async: false
    });

    $.ajax({
        type: 'POST',
        url: site_url + 'mod_farms/ui/farm_temporadas.php',
        data: {
            cod_temporada: cod_temporada
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
    jQuery.ajaxSetup({
        async: true
    });
}

/**
 * Crear o actualiza un registro de una Temporada
 */
function granj_guardar_temporada(codigo_temporada) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/granj_guardar_temporada.php',
        type: 'POST',
        data: {
            x1: codigo_temporada,
            x2: $('#nombre_temporada').val(),
            x3: $('#nota_temporada').val(),
            x4: ($('#activo').is(':checked') ? 1 : 0),

        },
    }).done(function (data) {
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);
        granj_vista_temporada(codigo_temporada)
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
}

/*Activa o desactiva una temporada especifica
*/
function granj_cambiar_estado_temporada(cod_temporada, valor) {

    $.ajax({
        url: site_url + 'mod_farms/funciones/granj_cambiar_estado_temporada.php',
        type: 'POST',
        data: {
            x1: cod_temporada,
            x2: valor
        },
    }).done(function (data) {
        jQuery.ajaxSetup({ async: false });
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        $('#modal_loading').modal('hide');
        grl_mensaje('', mensaje, tipo);
        // granj_vista_localizaciones(0);
        jQuery.ajaxSetup({ async: true });
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
    return false;
}

/**
 * FUNCIONES PARA GRANJAS
*/

/*
 * Función que carga el listado de granjas activas
 */
function farm_constructor_listado_granjas(id = 'cod_granja', valorInicial = false, incluir_inactivos = false) {
    console.log(incluir_inactivos);
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_farms/funciones/farm_listado_granjas.php',
        dataType: 'json',
        data: {
          x1: incluir_inactivos ? "true" : "false"
        },
        success: function (data) {
            $('#' + id).empty();
            if (valorInicial) {
                $('#' + id).append(new Option("Select", "-b"));
            }
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        $('#' + id).append(new Option(`${item.farm} ${incluir_inactivos ? (item.activo == "1" ? "(Active)" : "(Inactive)") : ""}`, item.cod_farms));
                    });
                } else {
                    $('#' + id).append(new Option(`${data[0].farm} ${incluir_inactivos ? (data[0].activo == "1" ? "(Active)" : "(Inactive)") : ""}`, data[0].cod_farms));
                }
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}

/*
 * Función que carga el listado de granjas activas e indica a cual estado pertenece
 */
function farm_constructor_listado_granjas_con_datos(id = 'cod_granja', valorInicial = false) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_listado_granjas_con_datos.php',
        type: 'POST',
        data: {
            x1: $('#cod_campo').val(),
        },
        dataType: 'json',
        success: function (data) {
            $('#' + id).empty();
            if (valorInicial) {
                $('#' + id).append(new Option("Select", "-b"));
            }
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        $('#' + id).append(new Option(item.farm, item.cod_farms));
                    });
                } else {
                    $('#' + id).append(new Option(data[0].farm, data[0].cod_farms));
                }
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}

/*
 * Función que carga el listado de granjas activas e indica a cual estado pertenece
 */
function farm_constructor_listado_granjas_por_estado_con_datos(id = 'cod_granja', valorInicial = false) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_listado_granjas_por_estado_con_datos.php',
        type: 'POST',
        data: {
            x1: $('#cod_estado').val(),
        },
        dataType: 'json',
        success: function (data) {

            $('#' + id).empty();
            if (valorInicial) {
                $('#' + id).append(new Option("Select", "-b"));
            }
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        $('#' + id).append(new Option(item.farm, item.cod_farms));
                    });
                } else {
                    $('#' + id).append(new Option(data[0].farm, data[0].cod_farms));
                }
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}
/*
 * Función que carga el listado de granjas activas e indica a cual estado pertenece
 */
function farm_constructor_listado_granjas_por_conjunto_estados_con_datos(id = 'cod_granja', resultadoSinComas, valorInicial = false) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_listado_granjas_por_estado_con_datos.php',
        type: 'POST',
        data: {
            // x1: $('#cod_estado').val(),
            x1: resultadoSinComas,
        },
        dataType: 'json',
        success: function (data) {
            $('#' + id).empty();
            if (valorInicial) {
                $('#' + id).append(new Option("Select", "-b"));
            }
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        $('#' + id).append(new Option(item.farm, item.cod_farms));
                    });
                } else {
                    $('#' + id).append(new Option(data[0].farm, data[0].cod_farms));
                }
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}

/**
 * Función que permite cargar vista de Granjas
 */
function granj_vista_granja(cod_granja) {
    jQuery.ajaxSetup({
        async: false
    });

    $.ajax({
        type: 'POST',
        url: site_url + 'mod_farms/ui/granj_granjas.php',
        data: {
            cod_granja: cod_granja
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
    jQuery.ajaxSetup({
        async: true
    });
}

/**
 * Crear o actualiza un registro de una granja
 */
function granj_guardar_granja(codigo_granja) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/granj_guardar_granja.php',
        type: 'POST',
        data: {
            x1: codigo_granja,
            x2: $('#nombre_granja').val(),
            x3: $('#cod_estado').val(),
            x4: ($('#activo').is(':checked') ? 1 : 0),

        },
    }).done(function (data) {
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);
        granj_vista_granja(codigo_granja)
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
}

/**
* Activa o desactiva una granja especifica
*/
function granj_cambiar_estado_granja(cod_granja, valor) {

    $.ajax({
        url: site_url + 'mod_farms/funciones/granj_cambiar_estado_granja.php',
        type: 'POST',
        data: {
            x1: cod_granja,
            x2: valor
        },
    }).done(function (data) {
        jQuery.ajaxSetup({ async: false });
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        $('#modal_loading').modal('hide');
        grl_mensaje('', mensaje, tipo);
        // granj_vista_localizaciones(0);
        jQuery.ajaxSetup({ async: true });
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
    return false;
}

/**
 * FUNCIONES PARA CAMPOS
*/
/*
 * Función que carga el listado de campos activos
 */
function farm_constructor_listado_campos(id = 'cod_field') {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_farms/funciones/farm_listado_campos.php',
        dataType: 'json',
        success: function (data) {
            $('#' + id).empty();
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        $('#' + id).append(new Option(item.field, item.cod_field));
                    });
                } else {
                    $('#' + id).append(new Option(data[0].field, data[0].cod_field));
                }
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}

/**
 * Función que permite cargar vista de los Campos
 */
function granj_vista_campo(cod_field) {
    jQuery.ajaxSetup({
        async: false
    });

    $.ajax({
        type: 'POST',
        url: site_url + 'mod_farms/ui/granj_campos.php',
        data: {
            cod_field: cod_field
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
    jQuery.ajaxSetup({
        async: true
    });
}

/**
 * Crear o actualiza un registro de una campo
 */
function granj_guardar_campo(codigo_field) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/granj_guardar_campo.php',
        type: 'POST',
        data: {
            x1: codigo_field,
            x2: $('#nombre_campo').val(),
            x3: $('#cod_granja').val(),
            x4: ($('#activo').is(':checked') ? 1 : 0),

        },
    }).done(function (data) {
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);
        granj_vista_campo(codigo_field)
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
}

/**
* Activa o desactiva una campo especifica
*/
function granj_cambiar_estado_campo(cod_field, valor) {

    $.ajax({
        url: site_url + 'mod_farms/funciones/granj_cambiar_estado_campo.php',
        type: 'POST',
        data: {
            x1: cod_field,
            x2: valor
        },
    }).done(function (data) {
        jQuery.ajaxSetup({ async: false });
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        $('#modal_loading').modal('hide');
        grl_mensaje('', mensaje, tipo);
        jQuery.ajaxSetup({ async: true });
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
    return false;
}

/**
 * FUNCIONES PARA BLOQUES
*/
/*
 * Función que carga el listado de bloques activos
 */
function granj_constructor_listado_bloques(id = 'cod_bloque') {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_farms/funciones/granj_listado_bloques.php',
        dataType: 'json',
        success: function (data) {
            $('#' + id).empty();
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        $('#' + id).append(new Option(item.bloque, item.cod_bloque));
                    });
                } else {
                    $('#' + id).append(new Option(data[0].bloque, data[0].cod_bloque));
                }
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}

/**
 * Función que permite cargar vista de los bloques
 */
function granj_vista_bloque(cod_bloque) {
    jQuery.ajaxSetup({
        async: false
    });

    $.ajax({
        type: 'POST',
        url: site_url + 'mod_farms/ui/granj_bloques.php',
        data: {
            cod_bloque: cod_bloque
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
    jQuery.ajaxSetup({
        async: true
    });
}

/**
 * Crear o actualiza un registro de un bloque
 */
function granj_guardar_bloque(codigo_bloque) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/granj_guardar_bloque.php',
        type: 'POST',
        data: {
            x1: codigo_bloque,
            x2: $('#nombre_bloque').val(),
            x3: $('#cod_granja').val(),
            x4: $('#cod_field').val(),
            x5: ($('#activo').is(':checked') ? 1 : 0),
        },
    }).done(function (data) {
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);
        granj_vista_bloque(codigo_bloque)
    }).fail(function (error) {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
}

/**
* Activa o desactiva un bloque especifica
*/
function granj_cambiar_estado_bloque(cod_bloque, valor) {

    $.ajax({
        url: site_url + 'mod_farms/funciones/granj_cambiar_estado_bloque.php',
        type: 'POST',
        data: {
            x1: cod_bloque,
            x2: valor
        },
    }).done(function (data) {
        jQuery.ajaxSetup({ async: false });
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        $('#modal_loading').modal('hide');
        grl_mensaje('', mensaje, tipo);
        jQuery.ajaxSetup({ async: true });
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
    return false;
}

/**
 * Guarda la cantidad de acres ingresados
 */
function granj_guardar_cantidad_acres(cod_bloque, cantidad_acres) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/granj_guardar_acres_actuales.php',
        type: 'POST',
        data: {
            x1: cod_bloque,
            x2: cantidad_acres

        },
    }).done(function (data) {
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        //grl_mensaje('', mensaje, tipo);
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
}

/**
 * FUNCIONES PARA LA GESTION DE LAS PLANTACIONES DE LAS GRANJAS
*/
/**
 * Crear o actualiza un registro de una plantación en una granja
 */
function farm_guardar_granjas_plantacion(codigo_granjas_plantacion, datosBloques, arrIndicesBloque, indiceBloque) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_guardar_granjas_plantacion.php',
        type: 'POST',
        data: {
            x1: codigo_granjas_plantacion,
            x2: $('#numero_orden').val(),
            x3: $('#cod_semilla').val(),
            x4: $('#planting_date').val(),
            x5: $('#cod_estado').val(),
            x6: $('#cod_granja').val(),
            x7: $('#cod_campo').val(),
            x8: $('#cod_bloque').val(),
            x9: $('#cod_temporada').val(),
            x10: $('#nombre_plantacion_granja').val(),
            x11: datosBloques,
            x12: arrIndicesBloque,
            x13: indiceBloque,
            x14: $("#numero_orden").find('option:selected').text(),

        },
    }).done(function (data) {

        // grl_mensaje('', data, 'danger');
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);
        farm_vista_granjas_plantacion(0)
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
}

/*
 * Función que carga el listado de campos activos
 */
function farm_constructor_listado_trasplantes_completados(id = 'numero_orden') {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_farms/funciones/farm_listado_trasplante_completados.php',
        dataType: 'json',
        success: function (data) {

            $('#' + id).empty();
            if (data != null) {
                $.each(data, function (i, item) {
                    $('#' + id).append(new Option(item.numero_orden + " - " + item.numero_ticket, item.numero_orden));
                });
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }
            $('.selectpicker').selectpicker('refresh');
            farm_listado_semillas_por_trasplante();
        }
    });
}

/*
 * Función que carga el listado de campos activos
 */
function farm_listado_trasplante_completados_por_estado(id = 'numero_orden_id_bloque', cod_estado) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_listado_trasplante_por_estado_completados.php',
        type: 'POST',
        data: {
            x1: cod_estado
        },
        dataType: 'json',
        success: function (data) {

            $('#' + id).empty();
            if (data != null) {
                $.each(data, function (i, item) {
                    $('#' + id).append(new Option(item.numero_orden + " - " + item.numero_ticket, item.numero_orden));
                });
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }
            $('.selectpicker').selectpicker('refresh');
            farm_listado_semillas_por_trasplante();
        }
    });
}
/*
 * Función que carga el listado de campos activos
 */
function farm_listado_semillas_implementadas_en_bloque(cod_bloque) {
    jQuery.ajaxSetup({ async: false });

    let respuesta = [];
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_listado_semillas_asociadas_a_bloques.php',
        type: 'POST',
        data: {
            x1: cod_bloque
        },
        dataType: 'json',
        success: function (data) {

            respuesta = data;
        }
    });
    jQuery.ajaxSetup({ async: true });

    return respuesta;
}

/*
 * Función que carga el listado de campos activos
 */
function farm_registrar_semilla_asociada_a_bloque(cod_bloque, cod_bloque_implementado, cod_inventario, cod_plantacion, cod_trasplante, acres_usados, porcentaje_acre_usado) {
    jQuery.ajaxSetup({ async: false });
    let nuevo_cod_semilla_bloque = 0;
    console.log("Enviando los datos para la creación de la semilla");
    console.log("Datos enviados");

    console.log({ x0: cod_bloque });
    console.log({ x1: cod_bloque_implementado });
    console.log({ x2: cod_inventario });
    console.log({ x3: cod_plantacion });
    console.log({ x4: cod_trasplante });
    console.log({ x5: acres_usados });
    console.log({ x6: porcentaje_acre_usado });

    $.ajax({
        async: false, // Hace que la solicitud AJAX sea síncrona.
        cache: false, // Deshabilita la caché del navegador.
        url: site_url + 'mod_farms/funciones/farm_guardar_semilla_implementada.php',
        type: 'POST',
        data: {
            x0: cod_bloque,
            x1: cod_bloque_implementado,
            x2: cod_inventario,
            x3: cod_plantacion,
            x4: cod_trasplante,
            x5: acres_usados,
            x6: porcentaje_acre_usado
        },
        dataType: 'json',
        success: function (data) {

            nuevo_cod_semilla_bloque = data;
        },
        error: function (xhr, status, error) {
            console.log({ xhr })
            console.log({ status })
            console.log({ error })

            console.log('La solicitud AJAX tardó demasiado en responder o falló.');
        },
        complete: function (xhr, status) {
            console.log({ xhr })
            console.log({ status })
            console.log('La solicitud AJAX ha sido completada, independientemente de si fue exitosa o no.');
        }
    }).fail(function (error) {
        console.log({ error })
        grl_mensaje(JSON.stringify(error), 'please try later', 'danger');
    }).always(function () { });
    console.log("Termino la ejecución");
    jQuery.ajaxSetup({ async: true });
    return nuevo_cod_semilla_bloque;
}

//---------------
/*
 * Función que carga el listado de campos activos
 */
function farm_constructor_listado_trasplantes_completados_clase(clase = 'numero_orden') {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_farms/funciones/farm_listado_trasplante_completados.php',
        dataType: 'json',
        success: function (data) {
            $('.' + clase).empty();
            if (data != null) {
                $.each(data, function (i, item) {
                    $('.' + clase).append(new Option(item.numero_orden + " - " + item.numero_ticket, item.numero_orden));
                });
            } else {
                $('.' + clase).append(new Option('No options', '-b'));
            }
            $('.selectpicker').selectpicker('refresh');
            farm_listado_semillas_por_trasplante();
        }
    });
}


/*
 * Función que carga el listado de semillas que este relacionado a un trasplante
 */
function farm_listado_semillas_por_trasplante(id = 'cod_semilla', valorInicial = false) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_listado_semillas_por_trasplante.php',
        type: 'POST',
        data: {
            x1: $('#numero_orden').val()
        },
        dataType: 'json',
        success: function (data) {
            $('#' + id).empty();
            if (valorInicial) {
                $('#' + id).append(new Option("Select", "-b"));
            }
            if (data != null) {
                $.each(data, function (i, item) {
                    $('#' + id).append(new Option(item.nombre_semilla, item.cod_inventario));
                });
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }
            $('.selectpicker').selectpicker('refresh');
        }
    }).fail(function (error) {
        grl_mensaje(JSON.stringify(error), 'please try later', 'danger');
    }).always(function () { });
}
/*
 * Función que carga el listado de semillas que este relacionado a un trasplante
 */
function farm_listado_semillas_por_numero_orden(id = 'cod_semilla', numero_de_orden, valorInicial = false) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_listado_semillas_por_numero_orden.php',
        type: 'POST',
        data: {
            x1: numero_de_orden
        },
        dataType: 'json',
        success: function (data) {

            console.log({ id })
            $('#' + id).empty();
            if (valorInicial) {
                $('#' + id).append(new Option("Select", "-b"));
            }
            if (data != null) {
                $.each(data, function (i, item) {
                    // $('#' + id).append(new Option(item.nombre_semilla, item.cod_inventario));
                    // Supongamos que 'item' es tu objeto con datos adicionales
                    // y quieres adjuntar un atributo llamado 'descripcion' a cada opción

                    // Crear una nueva opción
                    var nuevaOpcion = new Option(item.nombre_semilla, item.cod_inventario);

                    // Agregar datos adicionales a la opción
                    $(nuevaOpcion).data('edad', item.edad);

                    // Agregar la opción al elemento select con el id especificado
                    $('#' + id).append(nuevaOpcion);
                });
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }
            // $('.selectpicker').selectpicker('refresh');
        }
    }).fail(function (error) {
        grl_mensaje(JSON.stringify(error), 'please try later', 'danger');
    }).always(function () { });
}
/*
 * Función que carga el listado de semillas que este relacionado a un trasplante
 */
function farm_listado_semillasplantaciones_completadas_por_estado(id = 'cod_semilla', cod_estado, valorInicial = false) {
    jQuery.ajaxSetup({
        async: false
    });

    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_listado_semillas_plantaciones_completadas_por_estado.php',
        type: 'POST',
        data: {
            x1: cod_estado
        },
        dataType: 'json',
        success: function (data) {

            console.log({ id })
            $('#' + id).empty();
            if (valorInicial) {
                $('#' + id).append(new Option("Select", "-b"));
            }
            if (data != null) {
                $.each(data, function (i, item) {
                    // $('#' + id).append(new Option(item.nombre_semilla, item.cod_inventario));
                    // Supongamos que 'item' es tu objeto con datos adicionales
                    // y quieres adjuntar un atributo llamado 'descripcion' a cada opción

                    // Crear una nueva opción
                    var nuevaOpcion = new Option(item.datos_semilla, item.cod_inventario);

                    // Agregar datos adicionales a la opción
                    $(nuevaOpcion).data('edad', item.edad);
                    $(nuevaOpcion).data('cod_trasplante', item.cod_trasplante);
                    $(nuevaOpcion).data('cod_inventario', item.cod_inventario);
                    $(nuevaOpcion).data('cod_plantacion', item.cod_plantacion);

                    // Agregar la opción al elemento select con el id especificado
                    $('#' + id).append(nuevaOpcion);
                });
            } else {
                $('#' + id).append(new Option('No seeds found', '-b'));
            }
            // $('.selectpicker').selectpicker('refresh');
        }
    }).fail(function (error) {
        grl_mensaje(JSON.stringify(error), 'please try later', 'danger');
    }).always(function () { });
    jQuery.ajaxSetup({
        async: true
    });
}

/*
 * Función que carga el listado de los sembradores activos
 */
function farm_constructor_listado_estados_de_plantacion_por_numero_orden(id = 'cod_estados', valorInicial = false) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_listado_estados_de_plantaciones_por_numero_orden.php',
        type: 'POST',
        data: {
            x1: $('#numero_orden').val()
        },
        dataType: 'json',
        success: function (data) {
            $('#' + id).empty();
            if (valorInicial) {
                $('#' + id).append(new Option("Select", "-b"));
            }
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

/*
 * 
 */
function farm_listado_granjas_por_estados(id = 'cod_estados', indicesEstados, valorInicial = false) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_listado_granjas_por_estados.php',
        type: 'POST',
        data: {
            x1: indicesEstados
        },
        dataType: 'json',
        success: function (data) {

            $('#' + id).empty();
            if (valorInicial) {
                $('#' + id).append(new Option("Select", "-b"));
            }
            if (data != null) {
                $.each(data, function (i, item) {
                    $('#' + id).append(new Option(item.farm, item.cod_farms));
                });
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }
            $('.selectpicker').selectpicker('refresh');
        }
    }).fail(function (error) {
        grl_mensaje(JSON.stringify(error), 'please try later', 'danger');
    }).always(function () { });
}
/*
 * Función que carga el listado de semillas que este relacionado a un trasplante
 */
function farm_listado_campo_por_granja(id = 'cod_campo', indicesGranjas, valorInicial = false) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_listado_campo_por_granjas.php',
        type: 'POST',
        data: {
            x1: indicesGranjas
        },
        dataType: 'json',
        success: function (data) {

            $('#' + id).empty();
            if (valorInicial) {
                $('#' + id).append(new Option("Select", "-b"));
            }
            if (data != null) {
                $.each(data, function (i, item) {
                    $('#' + id).append(new Option(item.field, item.cod_field));
                });
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }
            $('.selectpicker').selectpicker('refresh');
        }
    }).fail(function (error) {
        grl_mensaje(JSON.stringify(error), 'please try later', 'danger');
    }).always(function () { });
}

/*
 * Función que carga el listado de semillas que este relacionado a un trasplante
 */
function farm_listado_campo_por_estado_granja(id = 'cod_campo') {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_listado_campo_por_estado_granja.php',
        type: 'POST',
        data: {
            x1: $('#cod_estado').val(),
            x2: $('#cod_granja').val()
        },
        dataType: 'json',
        success: function (data) {
            $('#' + id).empty();
            if (data != null) {
                $.each(data, function (i, item) {
                    $('#' + id).append(new Option(item.field, item.cod_field));
                });
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }
            $('.selectpicker').selectpicker('refresh');
        }
    }).fail(function (error) {
        grl_mensaje(JSON.stringify(error), 'please try later', 'danger');
    }).always(function () { });
}
/*
 * Función que carga el listado de semillas que este relacionado a un trasplante
 */
function farm_listado_campos_por_estados_granjas(id = 'cod_campo') {
    var codigosEstados = $('#cod_estado').val().join();
    var codigosEstadosSinComas = codigosEstados.toString();
    var codigosGranjas = $('#cod_granja').val().join();
    var codigosGranjasSinComas = codigosGranjas.toString();

    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_listado_campos_por_estados_granjas.php',
        type: 'POST',
        data: {
            // x1: $('#cod_estado').val(),
            x1: codigosEstadosSinComas,
            // x2: $('#cod_granja').val()
            x2: codigosGranjasSinComas
        },
        dataType: 'json',
        success: function (data) {

            $('#' + id).empty();
            if (data != null) {
                $.each(data, function (i, item) {
                    $('#' + id).append(new Option(item.field, item.cod_field));
                });
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }
            $('.selectpicker').selectpicker('refresh');
        }
    }).fail(function (error) {
        grl_mensaje(JSON.stringify(error), 'please try later', 'danger');
    }).always(function () { });
}
/*
/*Funcion que permite cambiar estado activo/des de un grupo de siembra
*/
function farm_cambiar_estado_completacion_plantacion_granja(far_crop_bloques, valor) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_cambiar_estado_completacion_plantacion_granja.php',
        type: 'POST',
        data: {
            x1: far_crop_bloques,
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
            // inv_vista_grupo_de_siembra(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function () { });
    return false;
}
/*
 * Función que carga el listado de bloques asociados a campos
 */
function farm_listado_bloques_por_campos(id = 'cod_bloque', codigosCampos, valorInicial = false) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_listado_bloques_por_campos.php',
        type: 'POST',
        data: {
            x1: codigosCampos
        },
        dataType: 'json',
        success: function (data) {

            $('#' + id).empty();
            if (valorInicial) {
                $('#' + id).append(new Option("Select", "-b"));
            }
            if (data != null) {
                $.each(data, function (i, item) {
                    $('#' + id).append(new Option(item.bloque, item.cod_bloque));
                });
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }
            $('.selectpicker').selectpicker('refresh');
        }
    }).fail(function (error) {
        grl_mensaje(JSON.stringify(error), 'please try later', 'danger');
    }).always(function () { });
}
/*
 * Función que carga el listado de bloques asociados a campos
 */
function farm_listado_bloques_para_granja_por_campos(id = 'cod_bloque', codigosCampos, valorInicial = false) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_listado_bloques_para_granja_por_campos.php',
        type: 'POST',
        data: {
            x1: codigosCampos
        },
        dataType: 'json',
        success: function (data) {

            $('#' + id).empty();
            if (valorInicial) {
                $('#' + id).append(new Option("Select", "-b"));
            }
            if (data != null) {
                $.each(data, function (i, item) {
                    $('#' + id).append(new Option(item.bloque, item.cod_bloque));
                });
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }
            $('.selectpicker').selectpicker('refresh');
        }
    }).fail(function (error) {
        grl_mensaje(JSON.stringify(error), 'please try later', 'danger');
    }).always(function () { });
}
/*
 * Función que carga el listado de bloques asociados a un grupo de campos y no tienen semillas implementadas
 */
function farm_listado_bloques_libres_por_campos(id = 'cod_bloque', codigosCampos, valorInicial = false) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_listado_bloques_libres.php',
        type: 'POST',
        data: {
            x1: codigosCampos
        },
        dataType: 'json',
        success: function (data) {

            $('#' + id).empty();
            if (valorInicial) {
                $('#' + id).append(new Option("Select", "-b"));
            }
            if (data != null) {
                $.each(data, function (i, item) {
                    $('#' + id).append(new Option(item.bloque, item.cod_bloque));
                });
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }
            $('.selectpicker').selectpicker('refresh');
        }
    }).fail(function (error) {
        grl_mensaje(JSON.stringify(error), 'please try later', 'danger');
    }).always(function () { });
}
/*
 * Función que carga el listado de bloques asociados a un grupo de campos y tienen semillas implementadas
 */
function farm_listado_bloques_usados_por_campos(id = 'cod_bloque', codigosCampos, valorInicial = false) {
    jQuery.ajaxSetup({
        async: false
    });
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_listado_bloques_usados_por_campos.php',
        type: 'POST',
        data: {
            x1: codigosCampos
        },
        dataType: 'json',
        success: function (data) {
            console.log({ data })
            $('#' + id).empty();
            if (valorInicial) {
                $('#' + id).append(new Option("Select", "-b"));
            }
            if (data != null) {
                $.each(data, function (i, item) {
                    $('#' + id).append(new Option(item.bloque, item.cod_bloque));
                });
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }
            $('.selectpicker').selectpicker('refresh');
        }
    }).fail(function (error) {
        grl_mensaje(JSON.stringify(error), 'please try later', 'danger');
    }).always(function () { });
    jQuery.ajaxSetup({
        async: true
    });
}
/*
 *  
 */
function farm_listado_bloques_implementados_por_campos(id = 'cod_bloque', codigosCampos, valorInicial = false) {
    jQuery.ajaxSetup({
        async: false
    });

    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_listado_bloques_implementados_por_campos.php',
        type: 'POST',
        data: {
            x1: codigosCampos
        },
        dataType: 'json',
        success: function (data) {

            $('#' + id).empty();
            if (valorInicial) {
                $('#' + id).append(new Option("Select", "-b"));
            }
            if (data != null) {
                $.each(data, function (i, item) {
                    $('#' + id).append(new Option(item.bloque, item.cod_bloque));
                });
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }
            $('.selectpicker').selectpicker('refresh');
        }
    }).fail(function (error) {
        grl_mensaje(JSON.stringify(error), 'please try later', 'danger');
    }).always(function () { });
    jQuery.ajaxSetup({
        async: true
    });
}



/**
 * Función que permite cargar vista de los bloques
 */
function farm_vista_granjas_plantacion(cod_rotations) {
    jQuery.ajaxSetup({
        async: false
    });

    $.ajax({
        type: 'POST',
        url: site_url + 'mod_farms/ui/farm_granjas_plantacion.php',
        data: {
            cod_rotations: cod_rotations
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
    jQuery.ajaxSetup({
        async: true
    });
}
/**
 * Guarda la cantidad de acres ingresados en el listado de granjas
 */
function farm_guardar_cantidad_acres_plantacion_granja(cod_bloque, cantidad_acres) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_guardar_cantidad_acres_plantacion_granja.php',
        type: 'POST',
        data: {
            x1: cod_bloque,
            x2: cantidad_acres
        },
    }).done(function (data) {

        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
}
/**
 * Guarda la cantidad de acres ingresados en el listado de granjas
 */
function farm_guardar_cantidad_acres_plantacion_granja_por_semilla(cod_semila_bloque, cantidad_acres, porcentaje_acres) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_guardar_cantidad_acres_plantacion_granja_por_semilla.php',
        type: 'POST',
        data: {
            x1: cod_semila_bloque,
            x2: cantidad_acres,
            x3: porcentaje_acres,
        },
    }).done(function (data) {

        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
}
/**
 * Guarda la cantidad de acres ingresados en el listado de granjas
 */
function farm_guardar_cantidad_acres_en_bloque_implementado(cod_bloque_implementado, cantidad_acres, porcentaje_acres) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_guardar_cantidad_acres_en_bloque_implementados.php',
        type: 'POST',
        data: {
            x1: cod_bloque_implementado,
            x2: cantidad_acres,
            x3: porcentaje_acres,
        },
    }).done(function (data) {

        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        // grl_mensaje('', mensaje, tipo);
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
}
/**
 * Guarda la cantidad de acres ingresados en el listado de granjas
 */
function farm_guardar_fecha_de_plantacion_granja_semilla(cod_semilla_bloque, fecha_nueva) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_guardar_fecha_de_plantacion_granja_semilla.php',
        type: 'POST',
        data: {
            x1: cod_semilla_bloque,
            x2: fecha_nueva,
        },
    }).done(function (data) {

        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        if (mensaje != undefined) {

            grl_mensaje('', mensaje, tipo);
        }
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
}
/**
 * Guarda la cantidad de acres ingresados en el listado de granjas
 */
function farm_guardar_fecha_seleccionada_plantacion_granja(cod_rotations, fecha_ingresada) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_guardar_fecha_seleccionada_plantacion_granja.php',
        type: 'POST',
        data: {
            x1: cod_rotations,
            x2: fecha_ingresada
        },
    }).done(function (data) {

        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
}
/**
 * Eliminar una plantacion especifica
 */
function farm_eliminar_plantacion_en_granja(cod_rotations, cod_crop_bloques) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_eliminar_granja_plantacion.php',
        type: 'POST',
        data: {
            x1: cod_rotations,
            x2: cod_crop_bloques,
        },
    }).done(function (data) {

        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);
        farm_vista_granjas_plantacion(0)

    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
}
/**
 * Eliminar una plantacion especifica
 */
function farm_eliminar_semilla_de_plantacion(cod_semilla_bloque, cod_inventario, cod_bloque) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_eliminar_semilla_de_plantacion.php',
        type: 'POST',
        data: {
            x1: cod_semilla_bloque,
            x2: cod_inventario,
            x3: cod_bloque,
        },
    }).done(function (data) {

        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);

    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
}


/**
 *  
 */
function farm_registrar_bloque_implementado(cod_bloque) {
    let nuevo_cod_bloque_implementado = 0;
    jQuery.ajaxSetup({
        async: false
    });
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_guardar_bloque_implementado.php',
        type: 'POST',
        data: {
            x1: cod_bloque
        },
    }).done(function (data) {
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        if (info[0] == 0) {
            nuevo_cod_bloque_implementado = info[2];
        } else {
            nuevo_cod_bloque_implementado = 0;
        }
        // grl_mensaje('', mensaje, tipo);
    }).fail(function (error) {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
    jQuery.ajaxSetup({
        async: true
    });
    return nuevo_cod_bloque_implementado;

}


/**
 * Limpia las semillas de los bloques implementados
 */
function farm_completar_bloque_implementado(cod_bloque_implementado, cod_bloque) {
    jQuery.ajaxSetup({
        async: false
    });
    let respuesta = 0;
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_completar_bloque_implementado.php',
        type: 'POST',
        data: {
            x1: cod_bloque_implementado,
            x2: cod_bloque
        },
    }).done(function (data) {

        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        if (info[0] == 1) {
            respuesta = 1;//Fallo
        } else {
            respuesta = 0;//Exito
        }
        grl_mensaje('', mensaje, tipo);
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });

    jQuery.ajaxSetup({
        async: true
    });
    return respuesta;
}

/*
 * Función que carga el listado de campos activos
 */
function farm_listado_semillas_registradas_completadas(codigos_registros, cod_unificacion, cod_semilla, cod_bloque_implementado, edad, cod_farm, codigos_bloque, codigos_bloque_implementado) {
    jQuery.ajaxSetup({ async: false });

    let respuesta = [];
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_listado_semillas_registradas_completadas.php',
        type: 'POST',
        data: {
            x1: codigos_registros,
            x2: cod_unificacion,
            x3: cod_semilla,
            x4: cod_bloque_implementado,
            x5: edad,
            x6: cod_farm,
            x7: codigos_bloque,
            x8: codigos_bloque_implementado,
        },
        dataType: 'json',
        success: function (data) {

            respuesta = data;
        }
    });
    jQuery.ajaxSetup({ async: true });

    return respuesta;
}

/*
 *
 */
function farm_listado_temporadas(id = 'cod_semilla', cod_estado, valorInicial = false) {
    jQuery.ajaxSetup({
        async: false
    });

    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_listado_semillas_plantaciones_completadas_por_estado.php',
        type: 'POST',
        data: {
            x1: cod_estado
        },
        dataType: 'json',
        success: function (data) {

            console.log({ id })
            $('#' + id).empty();
            if (valorInicial) {
                $('#' + id).append(new Option("Select", "-b"));
            }
            if (data != null) {
                $.each(data, function (i, item) {
                    // $('#' + id).append(new Option(item.nombre_semilla, item.cod_inventario));
                    // Supongamos que 'item' es tu objeto con datos adicionales
                    // y quieres adjuntar un atributo llamado 'descripcion' a cada opción

                    // Crear una nueva opción
                    var nuevaOpcion = new Option(item.datos_semilla, item.cod_inventario);

                    // Agregar datos adicionales a la opción
                    $(nuevaOpcion).data('edad', item.edad);
                    $(nuevaOpcion).data('cod_trasplante', item.cod_trasplante);
                    $(nuevaOpcion).data('cod_inventario', item.cod_inventario);
                    $(nuevaOpcion).data('cod_plantacion', item.cod_plantacion);

                    // Agregar la opción al elemento select con el id especificado
                    $('#' + id).append(nuevaOpcion);
                });
            } else {
                $('#' + id).append(new Option('No seeds found', '-b'));
            }
            // $('.selectpicker').selectpicker('refresh');
        }
    }).fail(function (error) {
        grl_mensaje(JSON.stringify(error), 'please try later', 'danger');
    }).always(function () { });
    jQuery.ajaxSetup({
        async: true
    });
}


/**
 *
 */
function farm_actualizar_temporada_seleccionada_semilla_implementada(cod_temporada, cod_semilla_bloque) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_actualizar_temporada_seleccionada_semilla_implementada.php',
        type: 'POST',
        data: {
            x1: cod_temporada,
            x2: cod_semilla_bloque
        },
    }).done(function (data) {

        // var info = data.split("|");
        // var mensaje = info[1];
        // info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        // grl_mensaje('', mensaje, tipo);
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
}


/*
 * Función que carga el listado de las locaciones en granjas
 */
function farm_constructor_listado_localizaciones_en_granja(id = 'cod_localizacion', valorInicial = false) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_listado_localizaciones_en_granja.php',
        type: 'POST',
        data: {
            x1: $('#cod_granja').val()
        },
        dataType: 'json',
        success: function (data) {

            $('#' + id).empty();
            if (valorInicial) {
                $('#' + id).append(new Option("Select", "-b"));
            }
            if (data != null) {
                $.each(data, function (i, item) {
                    $('#' + id).append(new Option(item.nombre_locacion, item.cod_location));
                });
            } else {
                $('#' + id).append(new Option('No options', '-b'));
            }
            $('.selectpicker').selectpicker('refresh');
        }
    }).fail(function (error) {
        grl_mensaje(JSON.stringify(error), 'please try later', 'danger');
    }).always(function () { });
}



/**
 * Crear o actualiza un registro de un tipo de paquete
 */
function farm_guardar_tipo_paquete(codigo_tipo_paquete) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_guardar_tipo_paquete.php',
        type: 'POST',
        data: {
            x1: codigo_tipo_paquete,
            x2: $('#cod_granja').val(),
            x3: $('#cod_localizacion').val(),
            x4: ($('#activo').is(':checked') ? 1 : 0),
            x5: $('#nombre_tipo_paquete').val(),
            x6: $('#cod_categoria').val(),
            x7: $('#precio_pieza').val(),

        },
    }).done(function (data) {
        console.log({ data });
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);
        // if (codigo_tipo_paquete == 0) {
        //     farm_vista_tipo_de_paquete()
        // }
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
}


/*
 * Función que permite cargar vista de Tipos de paquetes
 */
function farm_vista_tipo_de_paquete(codigo_tipo_paquete) {
    jQuery.ajaxSetup({
        async: false
    });

    $.ajax({
        type: 'POST',
        url: site_url + 'mod_farms/ui/farm_tipo_paquetes.php',
        data: {
            cod_tipo_pack: codigo_tipo_paquete
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
    jQuery.ajaxSetup({
        async: true
    });
}



/**
* Activa o desactiva la disponibilidad de un tipo de paquete en una granja especifica
*/
function farm_cambiar_estado_tipo_paquete_especifico(cod_asociacion, valor) {

    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_cambiar_estado_tipo_paquete_especifico.php',
        type: 'POST',
        data: {
            x1: cod_asociacion,
            x2: valor
        },
    }).done(function (data) {
        console.log({ data })
        jQuery.ajaxSetup({ async: false });
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        $('#modal_loading').modal('hide');
        grl_mensaje('', mensaje, tipo);
        jQuery.ajaxSetup({ async: true });
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
    return false;
}



//FUNCIONES PARA ACTIVIDADES DE MISCELANEOS
/**
 * Crear o actualiza un registro de una actidiad de miscelaneos
 */
function farm_guardar_actividad_miscelaneas(codigo_actividad_miscelanea) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_guardar_actividad_miscelaneas.php',
        type: 'POST',
        data: {
            x1: codigo_actividad_miscelanea,
            x2: $('#cod_granja').val(),
            x3: $('#cod_localizacion').val(),
            x4: ($('#activo').is(':checked') ? 1 : 0),
            x5: $('#codigo_identificacion_actividad').val(),
            x6: $('#nombre_actividad').val(),
            x7: $('#precio_pieza').val(),

        },
    }).done(function (data) {
        console.log({ data });
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);
        // if (codigo_actividad_miscelanea == 0) {
        //     farm_vista_actividades_miscelaneas()

        // }
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
}



/*
 * Función que permite cargar vista de Actividades de miscelaneos
 */
function farm_vista_actividades_miscelaneas(codigo_actividad_miscelanea) {
    jQuery.ajaxSetup({
        async: false
    });

    $.ajax({
        type: 'POST',
        url: site_url + 'mod_farms/ui/farm_actividades_miscelaneas.php',
        data: {
            codigo_actividad_miscelanea: codigo_actividad_miscelanea
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
    jQuery.ajaxSetup({
        async: true
    });
}


/**
* Activa o desactiva la disponibilidad de una actividad de miscelaneos en una granja especifica
*/
function farm_cambiar_estado_actividad_miscelanea(codigo_actividad_miscelanea, valor) {

    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_cambiar_estado_actividad_miscelanea.php',
        type: 'POST',
        data: {
            x1: codigo_actividad_miscelanea,
            x2: valor
        },
    }).done(function (data) {
        console.log({ data })
        jQuery.ajaxSetup({ async: false });
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        $('#modal_loading').modal('hide');
        grl_mensaje('', mensaje, tipo);
        jQuery.ajaxSetup({ async: true });
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
    return false;
}



//FUNCIONES PARA LOCACIONES
/*
 * Función que permite cargar vista de Actividades de miscelaneos
 */
function farm_vista_locaciones(codigo_locacion) {
    jQuery.ajaxSetup({
        async: false
    });

    $.ajax({
        type: 'POST',
        url: site_url + 'mod_farms/ui/farm_locaciones.php',
        data: {
            codigo_locacion: codigo_locacion
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
    jQuery.ajaxSetup({
        async: true
    });
}



/**
* Activa o desactiva la disponibilidad de una actividad de miscelaneos en una granja especifica
*/
function farm_cambiar_estado_locacion(codigo_locacion, valor) {

    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_cambiar_estado_locacion.php',
        type: 'POST',
        data: {
            x1: codigo_locacion,
            x2: valor
        },
    }).done(function (data) {
        console.log({ data })
        jQuery.ajaxSetup({ async: false });
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        $('#modal_loading').modal('hide');
        grl_mensaje('', mensaje, tipo);
        jQuery.ajaxSetup({ async: true });
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
    return false;
}

/**
 * Crear o actualiza un registro de una locación
 */
function farm_guardar_locacion(codigo_locacion, oncomplete = ()=>{}) {
    $.ajax({
        url: site_url + 'mod_farms/funciones/farm_guardar_locacion.php',
        type: 'POST',
        data: {
            x1: codigo_locacion,
            x2: $('#cod_granja').val(),
            x3: $('#abreviacion').val(),
            x4: ($('#activo').is(':checked') ? 1 : 0),
            x5: $('#nombre_locacion').val(),
            x6: $('#cost_center').val(),
            x7: $('#labor_phase').val(),

        },
    }).done(function (data) {
        console.log({ data });
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);
        oncomplete();
        // if (codigo_locacion == 0) {
        //     farm_vista_locaciones()

        // }
    }).fail(function () {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
    }).always(function () { });
}

