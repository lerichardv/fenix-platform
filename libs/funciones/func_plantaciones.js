/*
                                                            
                                                            * Funciones jQuery para el módulo de plantaciones.
                                                            * @author      Jairo Bonilla
                                                            * @date        2018-10-08
                                                            
                                                            */



var codigo_plantacion = 0;

var codigo_estado_plantacion = 0;

var codigo_zona = 0;

var codigo_detalle = 0;

var cod_aplicacion_quimico = 0;

var site_url = window.location.protocol + '//' + window.location.hostname + '/';

var codigo_reporte = 0;



/*

* Función que permite cargar vista para ver información de un donante y actualizarlo

*/

function plan_vista_plantacion(codigo_plantacion, nombre_panel) {

    if (nombre_panel == undefined) {

        nombre_panel = null;

    }

    //toggleModuleNav();    

    $.ajax({

        type: 'POST',

        url: site_url + 'mod_plantaciones/ui/plan_nueva_plantacion.php',

        data:

        {

            cod_plantacion: codigo_plantacion,

            nombre_panel: nombre_panel

        },

        /*beforeSend: function ()

        {

            $("#loader" ).show();

            $("#cuerpo" ).hide();

        },*/

        success: function (data) {

            $('#div_cuerpo_menu').empty();

            $('#div_cuerpo_menu').html(data);

            /*$("#loader" ).hide();

            $("#cuerpo" ).show();*/

        }

    });

}

/*

* Función que permite cargar vista para ver información de un donante y actualizarlo

*/

function plan_vista_listado_plantacion(inicio, limite) {



    //toggleModuleNav();    

    $.ajax({

        type: 'POST',

        url: site_url + 'mod_plantaciones/ui/plan_listado_plantaciones.php',

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

* Función que obtiene los tipos de estados

*/

function bw_constructor_estados_plantaciones() {

    $.ajax({

        type: 'POST',

        url: site_url + 'mod_configuracion/funciones/bw_listado_estados_plantaciones.php',

        dataType: 'json',
        /*

                data: ({

                    x1: x1

                }),*/

        success: function (data) {

            $('#cod_estado_plantacion').empty();

            if (data != null) {

                if (data.length > 1) {

                    $('#cod_estado_plantacion').append(new Option('Select', '-b'));

                    $.each(data, function (i, item) {

                        $('#cod_estado_plantacion').append(new Option(item.estado_plantacion + ' - ' + item.estado_plantacion_english, item.cod_estado_plantacion));

                    });

                } else {

                    $('#cod_estado_plantacion').append(new Option(data[0].estado_plantacion + ' - ' + data[0].estado_plantacion_english, data[0].cod_estado_plantacion));

                }

            } else {

                $('#cod_estado_plantacion').append(new Option('No hay opciones', '-b'));

            }



            $('.selectpicker').selectpicker('refresh');

        }

    });

}





/*

* Función que permite cargar vista para ver información de una zona y actualizarla

*/

function bw_vista_zona(codigo_zona) {



    //toggleModuleNav();    

    $.ajax({

        type: 'POST',

        url: site_url + 'mod_plantaciones/ui/plan_zonas_bloques.php',

        data: {

            cod_zona: codigo_zona

        },

        success: function (data) {

            $('#div_cuerpo_menu').empty();

            $('#div_cuerpo_menu').html(data);

        }

    });

}



/*Funcion que permite guardar una zona con su información

*/

function bw_guardar_zona(codigo_zona) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_guardar_zona.php',

        type: 'POST',

        data: {

            x1: codigo_zona,

            x2: $('#cod_info_empresa').val(),

            x3: $('#zona').val(),

            x4: $('#abreviatura').val(),

            x5: $('#ubicacion').val(),

            x6: $('#bloque_inicial').val(),

            x7: $('#bloque_final').val(),

            x8: $('#cantidad_acres').val()

        },

    })

        .done(function (data) {

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            grl_mensaje('', info[1], tipo);

            codigo_zona = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            bw_vista_zona(codigo_zona);

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

}



/*Funcion que permite cambiar estado activo/des de una zona

*/

function bw_cambiar_estado_zona(cod_zona, valor) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_cambiar_estado_zona.php',

        type: 'POST',

        data: {

            x1: cod_zona,

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

            bw_vista_zona(0);

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

    return false;

}





/*

* Función que obtiene las zonas activas

*/

function bw_constructor_zonas(cod_info_empresa, id = 'cod_zona', flag_seleccione = 0) {

    $.ajax({

        type: 'POST',

        url: site_url + 'mod_plantaciones/funciones/plan_listado_zonas.php',

        dataType: 'json',

        data: ({

            x1: cod_info_empresa

        }),

        success: function (data) {

            $('#' + id).empty();

            if (flag_seleccione == 0) {

                $('#' + id).append(new Option('Select', '-b'))

            }

            if (data != null) {

                if (data.length > 1) {
                    ;

                    $.each(data, function (i, item) {

                        //$('#' + id).append(new Option(item.zona + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_zona));

                        $('#' + id).append('<option data-bloque_inicial="' + item.bloque_inicial + '" data-bloque_final="' + item.bloque_final + '" data-cantidad_acres="' + item.cantidad_acres + '" value="' + item.cod_zona + '" ' + (item.activo == 1 ? '' : 'disabled="disabled"') + '>' + item.zona + '</option>');

                    });

                } else {

                    //$('#' + id).append(new Option(data[0].zona + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_zona));

                    $('#' + id).append('<option data-bloque_inicial="' + data[0].bloque_inicial + '" data-bloque_final="' + data[0].bloque_final + '" data-cantidad_acres="' + data[0].cantidad_acres + '" value="' + data[0].cod_zona + '" ' + (data[0].activo == 1 ? '' : 'disabled="disabled"') + '>' + data[0].zona + '</option>');

                }

            } else {

                $('#' + id).append(new Option('No hay opciones', '-b'));

            }

            $('.selectpicker').selectpicker('refresh');

        }

    });

}

/*Funcion que permite guardar un bloque con su información

*/

function bw_guardar_bloque(codigo_zona) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_guardar_bloque.php',

        type: 'POST',

        data: {

            x1: codigo_zona,

            //x2: $('#nombre_bloque').val(),

            x2: $('#modal_bloque_inicial').val(),

            x3: $('#modal_bloque_final').val(),

            x4: $('#num_acres').val(),

            x5: $('#clave_bloque').val()

        },

    })

        .done(function (data) {

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            grl_mensaje('', info[1], tipo);

            codigo_zona = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            bw_vista_zona(codigo_zona);

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

}



/*Funcion que permite cambiar estado activo/des de un bloque

*/

function bw_cambiar_estado_bloque(cod_bloque, valor, codigo_zona) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_cambiar_estado_bloque.php',

        type: 'POST',

        data: {

            x1: cod_bloque,

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

            bw_vista_zona(codigo_zona);

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

    return false;

}





/*

* Función que obtiene los bloques activos

*/

function bw_constructor_bloques(cod_zona) {

    $.ajax({

        type: 'POST',

        url: site_url + 'mod_plantaciones/funciones/plan_listado_bloques.php',

        dataType: 'json',

        data: ({

            x1: cod_zona

        }),

        success: function (data) {

            $('#cod_bloque').empty();

            //$('#cod_bloque').append(new Option('Select', '-b'))

            if (data != null) {

                if (data.length > 1) {
                    ;

                    $.each(data, function (i, item) {

                        //$('#cod_bloque').append(new Option(item.nombre_bloque + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_bloque));

                        $('#cod_bloque').append('<option data-num_acres="' + item.num_acres + '" value="' + item.cod_bloque + '" ' + (item.activo == 1 ? '' : 'disabled="disabled"') + '>' + item.clave_bloque + (item.clave_bloque != '' ? ' - ' : '') + item.nombre_bloque + ' - ' + item.num_acres + '</option>');

                    });

                } else {

                    //$('#cod_bloque').append(new Option(data[0].nombre_bloque + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_bloque));

                    $('#cod_bloque').append('<option data-num_acres="' + data[0].num_acres + '" value="' + data[0].cod_bloque + '" ' + (data[0].activo == 1 ? '' : 'disabled="disabled"') + '>' + data[0].clave_bloque + (data[0].clave_bloque != '' ? ' - ' : '') + data[0].nombre_bloque + ' - ' + data[0].num_acres + '</option>');

                }

            } else {

                $('#cod_bloque').append(new Option('No hay opciones', '-b'));

            }



            $('.selectpicker').selectpicker('refresh');

        }

    });

}





/*Funcion que permite guardar una zona con su información

*/

function plan_guardar_plantacion(codigo_plantacion) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_guardar_plantacion.php',

        type: 'POST',

        data: {

            x1: codigo_plantacion,

            x2: $('#cod_info_empresa').val(),

            x3: $('#anio_plantacion').val(),

            x4: $('#fecha_plantacion_planeada').val(),

            x5: $('#num_plantacion').val(),

            x6: $('#acres_plantados').val(),

            x7: $('#cod_temporada').val(),

            x8: $('#fecha_plantacion_ejecutada').val()

        },

    })

        .done(function (data) {

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            grl_mensaje('', info[1], tipo);

            codigo_plantacion = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            plan_vista_plantacion(codigo_plantacion);

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

}





/*Funcion que permite guardar una zona a la plantacion

*/

function plan_agregar_zona_plantacion(codigo_plantacion) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_agregar_zona_plantacion.php',

        type: 'POST',

        data: {

            x1: codigo_plantacion,

            x2: $('#cod_zona').val(),

            x3: $('#cod_bloque').val(),

            x4: $('#cantidad_acres').val()

        },

    })

        .done(function (data) {

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            grl_mensaje('', info[1], tipo);

            codigo_plantacion = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            plan_vista_plantacion(codigo_plantacion);

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

}





/*Funcion que permite cambiar estado activo/des de un proveedor

*/

function plan_cambiar_estado_bloque_plantacion(cod_detalle, valor, motivo) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_cambiar_estado_bloque_plantacion.php',

        type: 'POST',

        data: {

            x1: cod_detalle,

            x2: valor,

            x3: motivo

        },

    })

        .done(function (data) {

            jQuery.ajaxSetup({ async: false });

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            $('#modal_loading').modal('hide');

            grl_mensaje('', info[1], tipo);

            codigo_plantacion = info[2];

            codigo_bloque = info[3];

            if (info[0] == 0) {

                plan_notificar_usuario_por_estado_bloque(valor, codigo_plantacion, codigo_bloque)



            }

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            plan_vista_plantacion(codigo_plantacion, 'a_itemschecklist');

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

    return false;

}





/*

* Función que obtiene las zonas activas

*/

function bw_constructor_bloques_plantacion(id = 'cod_bloques_plantacion', codigo_plantacion) {

    $.ajax({

        type: 'POST',

        url: site_url + 'mod_plantaciones/funciones/plan_listado_bloques_plantacion.php',

        dataType: 'json',

        data: ({

            x1: codigo_plantacion

        }),

        success: function (data) {

            $('#' + id).empty();

            if (data != null) {

                if (data.length > 1) {
                    ;

                    $.each(data, function (i, item) {

                        //$('#' + id).append(new Option(item.zona + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_zona));

                        $('#' + id).append('<option data-cantidad_acres="' + item.cantidad_acres + '" value="' + item.cod_bloque + '" ' + (item.flag_sembrado == 0 ? '' : 'disabled="disabled"') + '>' + item.clave_bloque + (item.clave_bloque != '' ? ' - ' : '') + item.nombre_bloque + ' - ' + item.zona + '</option>');

                    });

                } else {

                    //$('#' + id).append(new Option(data[0].zona + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_zona));

                    $('#' + id).append('<option data-cantidad_acres="' + data[0].cantidad_acres + '" value="' + data[0].cod_bloque + '" ' + (data[0].flag_sembrado == 0 ? '' : 'disabled="disabled"') + '>' + data[0].nombre_bloque + (data[0].clave_bloque != '' ? ' - ' : '') + data[0].nombre_bloque + ' - ' + data[0].zona + '</option>');

                }

            } else {

                $('#' + id).append(new Option('No hay opciones', '-b'));

            }



            $('.selectpicker').selectpicker('refresh');

        }

    });

}





/*

* Función que obtiene las zonas activas

*/

function bw_constructor_bloques_plantacion_todos(id = 'cod_bloques_plantacion', codigo_plantacion) {
    console.log({
        codigo_plantacion
    });
    $.ajax({

        type: 'POST',

        url: site_url + 'mod_plantaciones/funciones/plan_listado_bloques_plantacion.php',

        dataType: 'json',

        data: ({

            x1: codigo_plantacion

        }),

        success: function (data) {

            $('#' + id).empty();

            if (data != null) {
                console.log(data.length);
                console.log({ data });

                if (data.length > 1) {
                    ;
                    $.each(data, function (i, item) {

                        if (item.cod_estado_plantacion < 9)

                            //$('#' + id).append(new Option(item.zona + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_zona));

                            $('#' + id).append('<option data-cantidad_acres="' + item.cantidad_acres + '" value="' + item.cod_bloque + '" >' + item.clave_bloque + (item.clave_bloque != '' ? ' - ' : '') + item.nombre_bloque + ' - ' + item.zona + '</option>');

                    });

                } else {

                    if (data[0].cod_estado_plantacion < 9)

                        //$('#' + id).append(new Option(data[0].zona + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_zona));

                        $('#' + id).append('<option data-cantidad_acres="' + data[0].cantidad_acres + '" value="' + data[0].cod_bloque + '" >' + data[0].clave_bloque + (data[0].clave_bloque != '' ? ' - ' : '') + data[0].nombre_bloque + ' - ' + data[0].zona + '</option>');

                }

            } else {

                $('#' + id).append(new Option('No hay opciones', '-b'));

            }



            $('.selectpicker').selectpicker('refresh');

        }

    });

}





/*Funcion que permite guardar una zona a la plantacion

*/

function plan_agregar_semilla_plantacion(codigo_plantacion) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_agregar_semilla_plantacion.php',

        type: 'POST',

        data: {

            x1: codigo_plantacion,

            x2: $('#cod_bloques_plantacion').val(),

            x3: $('#cod_inventario_semilla').val(),

            x4: $('#cantidad_usada').val(),

            x5: $('#cod_inventario_maquinaria').val(),

            x6: $('#descripcion_semilla').val()

        },

    })

        .done(function (data) {

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            grl_mensaje('', info[1], tipo);

            codigo_plantacion = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            plan_vista_plantacion(codigo_plantacion);

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

}



/*Funcion que permite cambiar de flujo una plantación

*/

function bw_cambiar_flujo_plantacion(codigo_plantacion, flag_estado, flag_flujo) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_configuracion/funciones/bw_cambiar_flujo_plantacion.php',

        type: 'POST',

        async: false,

        data:

        {

            x1: codigo_plantacion,

            x2: flag_estado,

            x3: flag_flujo

        },

    })

        .done(function (data) {

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            grl_mensaje('', info[1], tipo);

            codigo_plantacion = info[2];

            cod_estado_plantacion = info[3];

            plan_notificar_usuario_por_estado_plantacion(cod_estado_plantacion, codigo_plantacion);

            plan_vista_plantacion(codigo_plantacion);

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            //usu_vista_familia(codigo_familia);

        })

        .fail(function () {

            grl_mensaje('Se ha producido un inconveniente', 'favor intentar mas tarde', 'danger');

        })

        .always(function () {

        });

}







/*Funcion que permite cambiar estado activo/des de un proveedor

*/

function plan_ingresar_calibracion_plantacion(codigo_detalle, codigo_plantacion) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_ingresar_calibracion_plantacion.php',

        type: 'POST',

        data: {

            x1: codigo_detalle,

            x2: $('#cod_fertilizante').val(),

            x3: $('#horas_aplicacion_calibrar').val(),

            x4: $('#observaciones_calibrar').val(),

            x5: codigo_plantacion,

            x6: $('#fecha_calibracion').val()

        },

    })

        .done(function (data) {

            jQuery.ajaxSetup({ async: false });

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            $('#modal_loading').modal('hide');

            grl_mensaje('', info[1], tipo);

            //codigo_plantacion = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            plan_vista_plantacion(codigo_plantacion, 'a_itemschecklist');

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

    return false;

}





/*Funcion que permite cambiar estado activo/des de un proveedor

*/

function plan_ingresar_calibracion_plantacion_offline(codigo_detalle, codigo_plantacion, cod_fertilizante, horas_aplicacion_calibrar, observaciones_calibrar, fecha_calibracion) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_ingresar_calibracion_plantacion.php',

        type: 'POST',

        data: {

            x1: codigo_detalle,

            x2: cod_fertilizante,

            x3: horas_aplicacion_calibrar,

            x4: observaciones_calibrar,

            x5: codigo_plantacion,

            x6: fecha_calibracion

        },

    })

        .done(function (data) {

            jQuery.ajaxSetup({ async: false });

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            $('#modal_loading').modal('hide');

            //grl_mensaje('', info[1], tipo);

            //codigo_plantacion = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            plan_vista_plantacion(codigo_plantacion, 'a_itemschecklist');

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

    return false;

}



function plan_cargar_historial_bloque(codigo_detalle, div) {

    jQuery.ajaxSetup({ async: false });

    grl_overlay_loading('');

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_cargar_historial_bloque.php',

        type: 'POST',

        data:

        {

            x1: codigo_detalle

        },

    })

        .done(function (data) {

            $('#' + div).empty().append(data);

            $('#modal_loading').modal('hide');

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            console.log("error");

        })

        .always(function () {

            console.log("complete");

        });



}







/*Funcion que permite cambiar estado activo/des de un proveedor

*/

function plan_ingresar_observacion_plantacion(codigo_plantacion) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_ingresar_observacion_plantacion.php',

        type: 'POST',

        data: {

            x1: codigo_plantacion,

            x2: $('#nueva_observacion').val()

        },

    })

        .done(function (data) {

            jQuery.ajaxSetup({ async: false });

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            $('#modal_loading').modal('hide');

            grl_mensaje('', info[1], tipo);

            //codigo_plantacion = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            plan_vista_plantacion(codigo_plantacion, 'a_itemschecklist');

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

    return false;

}






/**
 * Funcion que guarda la aplicacion de un quimico
 */

function plan_guardar_aplicar_quimico(cod_aplicacion_quimico, codigo_plantacion) {

    jQuery.ajaxSetup({ async: false });

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_guardar_aplicar_quimico.php',

        type: 'POST',

        data: {

            x1: cod_aplicacion_quimico,

            /*x2: $('#cod_inventario_quimico').val(),

            x3: $('#cod_inventario_quimico option:selected').data('cod_unidad_medida'),*/

            x4: $('#cod_bloques_aplicar_quimico').val(),

            /*x5: $('#cantidad_sugerida').val(),

            x6: $('#cod_unidad_medida').val(),*/

            x7: $('#fecha_aplicacion_supervisor').val(),

            //x8: $('#cantidad_aplicada').val(),

            x9: $('#cod_tipo_aplicacion').val(),

            x10: $('#cod_maquinaria').val(),

            x11: $('#fecha_aplicacion_operador').val(),

            x12: $('#hora_inicial').val(),

            x13: $('#hora_final').val(),

            x14: $('#viento').val(),

            x15: $('#temperatura').val(),

            x16: $('#descripcion_aplicar_quimico').val(),

            x17: codigo_plantacion,

            //x18: $('#cod_tipo_quimico').val(),

            x18: $('#cod_operador').val(),

            x19: $('#usuario_finca_recomendado').val()

        },

    })

        .done(function (data) {

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            $('#modal_loading').modal('hide');

            grl_mensaje('', info[1], tipo);

            //codigo_plantacion = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            plan_vista_plantacion(codigo_plantacion, 'a_itemschecklist');

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

    jQuery.ajaxSetup({ async: true });

}







/*Funcion que permite cambiar estado activo/des de un proveedor

*/

function plan_guardar_aplicar_quimico_offline(cod_aplicacion_quimico, codigo_plantacion, cod_tipo_aplicacion, cod_maquinaria, fecha_aplicacion_operador, hora_inicial, hora_final, viento, temperatura, descripcion_aplicar_quimico) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_guardar_aplicar_quimico_offline.php',

        type: 'POST',

        data: {

            x1: cod_aplicacion_quimico,

            /*x2: $('#cod_inventario_quimico').val(),

            x3: $('#cod_inventario_quimico option:selected').data('cod_unidad_medida'),*/

            //x4: $('#cod_bloques_aplicar_quimico').val(),

            /*x5: $('#cantidad_sugerida').val(),

            x6: $('#cod_unidad_medida').val(),*/

            //x7: $('#fecha_aplicacion_supervisor').val(),

            //x8: $('#cantidad_aplicada').val(),

            x9: cod_tipo_aplicacion,

            x10: cod_maquinaria,

            x11: fecha_aplicacion_operador,

            x12: hora_inicial,

            x13: hora_final,

            x14: viento,

            x15: temperatura,

            x16: descripcion_aplicar_quimico,

            x17: codigo_plantacion
            /*,

                            x18: $('#cod_tipo_quimico').val(),*/

        },

    })

        .done(function (data) {

            jQuery.ajaxSetup({ async: false });

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            $('#modal_loading').modal('hide');

            //grl_mensaje('', info[1], tipo);

            //codigo_plantacion = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            plan_vista_plantacion(codigo_plantacion, 'a_itemschecklist');

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

    return false;

}





/**
 * Funcion que guarda los datos de exploración
 */


function plan_guardar_exploracion(codigo_detalle, codigo_plantacion) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_guardar_exploracion.php',

        type: 'POST',

        data: {

            x1: codigo_detalle,

            x2: $('#etapa_crecimiento').val(),

            x3: $('#worms').val(),

            x4: $('#eggs').val(),

            x5: $('#leafhoppers').val(),

            x6: $('#aphids').val(),

            x7: $('#stink_bugs').val(),

            x8: $('#gnats').val(),

            x9: $('#flea_beetles').val(),

            x10: $('#cyclaman_mites').val(),

            x11: $('#cercospora_leaf_spot').val(),

            x12: $('#pythium').val(),

            x13: $('#rhizoctonia_aerial_blight').val(),

            x14: $('#bacteria').val(),

            x15: $('#sclerotinia').val(),

            x16: $('#alternaria_specks').val(),

            x17: $('#mildew').val(),

            x18: $('#virus').val(),

            x19: $('#dollarweed').val(),

            x20: $('#frogs_bit').val(),

            x21: $('#mud_plantain').val(),

            x22: $('#tube_weed').val(),

            x23: $('#grass').val(),

            x24: $('#damaged_leaves').val(),

            x25: $('#purple_stem').val(),

            x26: $('#watercress_rooter').val(),

            x27: $('#observaciones_exploracion').val(),

            x28: codigo_plantacion,

            x29: $('#fecha_exploracion').val(),

            x30: $('#spidermites').val(),

            x31: $('#white_rust').val(),

            x32: $('#salt_accumulation').val(),

            x33: $('#nutsedge').val(),

            x34: $('#buds').val(),

            x35: $('#zigzag_stems').val(),

            x36: $('#nutrient_deficiency').val(),

            x37: $('#round_up').val(),

            x38: $('#light_color').val(),

            x39: $('#mealybugs').val(),

            x40: $('#thrips').val()

        },

    })

        .done(function (data) {

            jQuery.ajaxSetup({ async: false });

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            $('#modal_loading').modal('hide');

            grl_mensaje('', info[1], tipo);

            //codigo_plantacion = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            plan_vista_plantacion(codigo_plantacion, 'a_itemschecklist');

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

    return false;

}





/*Funcion que permite cambiar estado activo/des de un proveedor

*/

function plan_guardar_exploracion_offline(codigo_detalle, codigo_plantacion, etapa_crecimiento, worms, eggs, leafhoppers, aphids, stink_bugs, gnats, flea_beetles, cyclaman_mites, cercospora_leaf_spot, pythium, rhizoctonia_aerial_blight, bacteria, sclerotinia, alternaria_specks, mildew, virus, dollarweed, frogs_bit, mud_plantain, tube_weed, grass, damaged_leaves, purple_stem, watercress_rooter, observaciones_exploracion, fecha_exploracion) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_guardar_exploracion.php',

        type: 'POST',

        data: {

            x1: codigo_detalle,

            x2: etapa_crecimiento,

            x3: worms,

            x4: eggs,

            x5: leafhoppers,

            x6: aphids,

            x7: stink_bugs,

            x8: gnats,

            x9: flea_beetles,

            x10: cyclaman_mites,

            x11: cercospora_leaf_spot,

            x12: pythium,

            x13: rhizoctonia_aerial_blight,

            x14: bacteria,

            x15: sclerotinia,

            x16: alternaria_specks,

            x17: mildew,

            x18: virus,

            x19: dollarweed,

            x20: frogs_bit,

            x21: mud_plantain,

            x22: tube_weed,

            x23: grass,

            x24: damaged_leaves,

            x25: purple_stem,

            x26: watercress_rooter,

            x27: observaciones_exploracion,

            x28: codigo_plantacion,

            x29: fecha_exploracion

        },

    })

        .done(function (data) {

            jQuery.ajaxSetup({ async: false });

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            $('#modal_loading').modal('hide');

            //grl_mensaje('', info[1], tipo);

            //codigo_plantacion = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            plan_vista_plantacion(codigo_plantacion, 'a_itemschecklist');

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

    return false;

}



function plan_notificar_usuario_por_estado_plantacion(cod_estado_plantacion, codigo_plantacion) {

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_notificar_usuario_por_estado_plantacion.php',

        type: 'POST',

        data:

        {

            x1: cod_estado_plantacion,

            x2: codigo_plantacion

        },

    })

        .done(function (data) {

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            $('#modal_loading').modal('hide');

            grl_mensaje('', info[1], tipo);

        })

        .fail(function () {

            console.log("error");

        })

        .always(function () {

            console.log("complete");

        });



}



function plan_notificar_usuario_por_estado_bloque(cod_estado_plantacion, codigo_plantacion, codigo_bloque) {

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_notificar_usuario_por_estado_bloque.php',

        type: 'POST',

        data:

        {

            x1: cod_estado_plantacion,

            x2: codigo_plantacion,

            x3: codigo_bloque

        },

    })

        .done(function (data) {

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            $('#modal_loading').modal('hide');

            grl_mensaje('', info[1], tipo);

        })

        .fail(function () {

            console.log("error");

        })

        .always(function () {

            console.log("complete");

        });



}





/*Funcion que permite guardar un item de un formulario con su respuesta

*/

function plan_guardar_item_respuesta_formulario(codigo_plantacion, cod_info_empresa, codigo_formulario, codigo_item, codigo_item_checklist, valor_item) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_guardar_item_respuesta_formulario.php',

        type: 'POST',

        data: {

            x1: codigo_plantacion,

            x2: cod_info_empresa,

            x3: codigo_formulario,

            x4: codigo_item,

            x5: codigo_item_checklist,

            x6: valor_item

        },

    })

        .done(function (data) {

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            //grl_mensaje('', info[1], tipo);

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            //plan_vista_plantacion(codigo_plantacion);

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

}



function plan_notificar_usuario_formulario_llenado(cod_estado_plantacion, codigo_plantacion) {

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_notificar_usuario_formulario_llenado.php',

        type: 'POST',

        data:

        {

            x1: cod_estado_plantacion,

            x2: codigo_plantacion

        },

    })

        .done(function (data) {

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            $('#modal_loading').modal('hide');

            grl_mensaje('', info[1], tipo);

        })

        .fail(function () {

            console.log("error");

        })

        .always(function () {

            console.log("complete");

        });



}



/*Funcion que permite ingresar observación de una exploración

*/

function plan_guardar_observacion_exploracion(flag_exploracion, cod_exploracion) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_guardar_observacion_exploracion.php',

        type: 'POST',

        data: {

            x1: flag_exploracion,

            x2: cod_exploracion,

            x3: $('#observacion_exploracion').val()

        },

    })

        .done(function (data) {

            jQuery.ajaxSetup({ async: false });

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            $('#modal_loading').modal('hide');

            grl_mensaje('', info[1], tipo);

            //codigo_plantacion = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            //plan_vista_plantacion(codigo_plantacion, 'a_itemschecklist');

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

    return false;

}



function plan_cargar_observaciones_exploracion(flag_exploracion, cod_exploracion, div) {

    jQuery.ajaxSetup({ async: false });

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_cargar_observaciones_exploracion.php',

        type: 'POST',

        data:

        {

            x1: flag_exploracion,

            x2: cod_exploracion

        },

    })

        .done(function (data) {

            $('#' + div).empty().append(data);

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            console.log("error");

        })

        .always(function () {

            console.log("complete");

        });



}



function plan_cargar_adjuntos_exploracion(flag_exploracion, cod_exploracion, div) {

    jQuery.ajaxSetup({ async: false });

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_cargar_adjuntos_exploracion.php',

        type: 'POST',

        data:

        {

            x1: flag_exploracion,

            x2: cod_exploracion

        },

    })

        .done(function (data) {

            $('#' + div).empty().append(data);

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            console.log("error");

        })

        .always(function () {

            console.log("complete");

        });



}



/*Funcion que permite ingresar observación de una exploración

*/

function plan_guardar_adjunto_exploracion(flag_exploracion, cod_exploracion, nombre_archivo) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_guardar_adjunto_exploracion.php',

        type: 'POST',

        data: {

            x1: flag_exploracion,

            x2: cod_exploracion,

            x3: nombre_archivo

        },

    })

        .done(function (data) {

            jQuery.ajaxSetup({ async: false });

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            $('#modal_loading').modal('hide');

            grl_mensaje('', info[1], tipo);

            //codigo_plantacion = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            //plan_vista_plantacion(codigo_plantacion, 'a_itemschecklist');

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

    return false;

}



/*Funcion que permite ingresar observación de una exploración

*/

function plan_borrar_adjunto_exploracion(flag_exploracion, cod_exploracion, cod_adjunto) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_borrar_adjunto_exploracion.php',

        type: 'POST',

        data: {

            x1: flag_exploracion,

            x2: cod_exploracion,

            x3: cod_adjunto

        },

    })

        .done(function (data) {

            jQuery.ajaxSetup({ async: false });

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            grl_mensaje('', info[1], tipo);

            //codigo_plantacion = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            //plan_vista_plantacion(codigo_plantacion, 'a_itemschecklist');

            plan_cargar_adjuntos_exploracion(flag_exploracion, cod_exploracion, 'div_adjuntos_exploracion');

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

    return false;

}





/*Función que carga modal de aplicar químico para que operador actualice los datos*/

function plan_cargar_modal_aplicar_quimico(cod_aplicacion) {



    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_cargar_modal_aplicar_quimico.php',

        type: 'POST',

        dataType: 'json',

        data: {

            x1: cod_aplicacion

        },

        /*beforeSend: function ()

        {

            $("#loader" ).show();

            $("#cuerpo" ).hide();

        }*/

    })

        .done(function (data) {

            jQuery.ajaxSetup({ async: false });

            cod_aplicacion_quimico = data[0].cod_aplicacion;

            $('#modal_aplicar_quimico').modal('show');

            $('#cod_tipo_quimico').selectpicker('val', data[0].cod_tipo_quimico);

            $('#cod_tipo_quimico').trigger('change');

            $('#cod_inventario_quimico').selectpicker('val', data[0].cod_inventario_quimico);

            $('#cod_inventario_quimico').trigger('change');

            $('#cod_bloques_aplicar_quimico').selectpicker('val', data[0].cod_bloques_aplicacion.split(','));

            $('#cantidad_sugerida').val(data[0].cantidad_sugerida);

            $('#cod_unidad_medida').selectpicker('val', data[0].cod_unidad_medida);

            $('#fecha_aplicacion_supervisor').val(data[0].fecha_aplicacion_supervisor);

            $('#cantidad_aplicada').val(data[0].cantidad_aplicada);

            $('#cod_tipo_aplicacion').selectpicker('val', data[0].cod_tipo_aplicacion);

            $('#cod_maquinaria').selectpicker('val', data[0].cod_inventario_maquinaria);

            $('#fecha_aplicacion_operador').val(data[0].fecha_aplicacion_operador);

            $('#hora_inicial').val(data[0].hora_inicial);

            $('#hora_final').val(data[0].hora_final);

            $('#viento').val(data[0].viento);

            $('#temperatura').val(data[0].temperatura);

            $('#descripcion_aplicar_quimico').val(data[0].descripcion_aplicar_quimico);

            $('#cod_operador').val(data[0].cod_operador);

            $('.selectpicker').selectpicker('refresh');

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            console.log("error");

        })

        .always(function () {

            console.log("complete");

        });



}







/*Funcion que permite cambiar estado activo/des de un proveedor

*/

function plan_guardar_formulario_limpieza(codigo_detalle, codigo_plantacion) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_guardar_formulario_limpieza.php',

        type: 'POST',

        data: {

            x1: codigo_detalle,

            x2: $('#fecha_limpieza').val(),

            x3: $('#vez_limpieza').val(),

            x4: $('#equipo_limpieza').val(),

            x5: $('#cleaning_tools option:selected').val(),

            x6: $('#cleaning_potable_water option:selected').val(),

            x7: $('#cleaning_detergent option:selected').val(),

            x8: $('#scrubbing option:selected').val(),

            x9: $('#rinse_potable_water option:selected').val(),

            x10: $('#sanitizing_chlorine option:selected').val(),

            x11: $('#post_sanitizing option:selected').val(),

            x12: codigo_plantacion

        },

    })

        .done(function (data) {

            jQuery.ajaxSetup({ async: false });

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            $('#modal_loading').modal('hide');

            grl_mensaje('', info[1], tipo);

            //codigo_plantacion = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            plan_vista_plantacion(codigo_plantacion, 'a_itemschecklist');

            plan_notificar_usuario_formulario_llenado(codigo_estado_plantacion, codigo_plantacion);

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

    return false;

}



/*Funcion que permite cambiar estado activo/des de un proveedor

*/

function plan_guardar_formulario_limpieza_offline(codigo_detalle, codigo_plantacion, fecha_limpieza, vez_limpieza, equipo_limpieza, cleaning_tools, cleaning_potable_water, cleaning_detergent, scrubbing, rinse_potable_water, sanitizing_chlorine, post_sanitizing) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_guardar_formulario_limpieza.php',

        type: 'POST',

        data: {

            x1: codigo_detalle,

            x2: fecha_limpieza,

            x3: vez_limpieza,

            x4: equipo_limpieza,

            x5: cleaning_tools,

            x6: cleaning_potable_water,

            x7: cleaning_detergent,

            x8: scrubbing,

            x9: rinse_potable_water,

            x10: sanitizing_chlorine,

            x11: post_sanitizing,

            x12: codigo_plantacion

        },

    })

        .done(function (data) {

            jQuery.ajaxSetup({ async: false });

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            $('#modal_loading').modal('hide');

            //grl_mensaje('', info[1], tipo);

            //codigo_plantacion = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            //plan_vista_plantacion(codigo_plantacion, 'a_itemschecklist');

            //plan_notificar_usuario_formulario_llenado(codigo_estado_plantacion, codigo_plantacion);

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

    return false;

}





/*Funcion que permite cambiar estado activo/des de un proveedor

*/

function plan_guardar_formulario_harvesting_worksheet(codigo_detalle, codigo_plantacion) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_guardar_formulario_harvesting_worksheet.php',

        type: 'POST',

        data: {

            x1: codigo_detalle,

            x2: $('#harvest_date').val(),

            x3: $('#phi option:selected').val(),

            x4: $('#cellos option:selected').val(),

            x5: $('#increment_bunch_cello').val(),

            x6: $('#area_finished option:selected').val(),

            x7: $('#acres_harvested').val(),

            x8: $('#orden_compra').val(),

            x9: $('#cantidad_cosechada_worksheet').val(),

            x10: $('#commments_harvesting_worksheet').val(),

            x11: codigo_plantacion,

            x12: $('#cantidad_empacada_worksheet').val(),

            x13: $('#crop_number').val(),

            x14: $('#date_packed').val(),

            x15: $('#totes_harvested').val(),

            x16: $('#totes_packed').val()

        },

    })

        .done(function (data) {

            jQuery.ajaxSetup({ async: false });

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            $('#modal_loading').modal('hide');

            grl_mensaje('', info[1], tipo);

            //codigo_plantacion = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            plan_notificar_usuario_formulario_llenado(codigo_estado_plantacion, codigo_plantacion);

            plan_vista_plantacion(codigo_plantacion, 'a_itemschecklist');

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

    return false;

}





/*Funcion que permite cambiar estado activo/des de un proveedor

*/

function plan_guardar_formulario_harvesting_worksheet_offline(codigo_detalle, codigo_plantacion, harvest_date, phi, cellos, increment_bunch_cello, area_finished, acres_harvested, orden_compra, cantidad_cosechada_worksheet, commments_harvesting_worksheet, cantidad_empacada_worksheet) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_guardar_formulario_harvesting_worksheet.php',

        type: 'POST',

        data: {

            x1: codigo_detalle,

            x2: harvest_date,

            x3: phi,

            x4: cellos,

            x5: increment_bunch_cello,

            x6: area_finished,

            x7: acres_harvested,

            x8: orden_compra,

            x9: cantidad_cosechada_worksheet,

            x10: commments_harvesting_worksheet,

            x11: codigo_plantacion,

            x12: cantidad_empacada_worksheet

        },

    })

        .done(function (data) {

            jQuery.ajaxSetup({ async: false });

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            $('#modal_loading').modal('hide');

            //grl_mensaje('', info[1], tipo);

            //codigo_plantacion = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            plan_notificar_usuario_formulario_llenado(codigo_estado_plantacion, codigo_plantacion);

            plan_vista_plantacion(codigo_plantacion, 'a_itemschecklist');

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

    return false;

}





/*Funcion que permite cambiar estado activo/des de un proveedor

*/

function plan_guardar_formulario_harvesting_checklist(codigo_detalle, codigo_plantacion) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_guardar_formulario_harvesting_checklist.php',

        type: 'POST',

        data: {

            x1: codigo_detalle,

            x2: $('#fecha_checklist').val(),

            x3: $('#loose_bunches option:selected').val(),

            x4: $('#conventional_organic option:selected').val(),

            x5: $('#question1 option:selected').val(),

            x6: $('#question2 option:selected').val(),

            x7: $('#question3 option:selected').val(),

            x8: $('#question4 option:selected').val(),

            x9: $('#question5 option:selected').val(),

            x10: $('#question6 option:selected').val(),

            x11: $('#question7 option:selected').val(),

            x12: $('#question8 option:selected').val(),

            x13: $('#question9 option:selected').val(),

            x14: $('#question10 option:selected').val(),

            x15: $('#question11 option:selected').val(),

            x16: $('#question12 option:selected').val(),

            x17: $('#question13 option:selected').val(),

            x18: $('#question14 option:selected').val(),

            x19: $('#question15 option:selected').val(),

            x20: $('#question16 option:selected').val(),

            x21: $('#question17 option:selected').val(),

            x22: $('#question18').val(),

            x23: $('#question19').val(),

            x24: $('#question20 option:selected').val(),

            x25: $('#question21 option:selected').val(),

            x26: $('#question22 option:selected').val(),

            x27: $('#actions').val(),

            x28: codigo_plantacion

        },

    })

        .done(function (data) {

            jQuery.ajaxSetup({ async: false });

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            $('#modal_loading').modal('hide');

            grl_mensaje('', info[1], tipo);

            //codigo_plantacion = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            plan_notificar_usuario_formulario_llenado(codigo_estado_plantacion, codigo_plantacion);

            plan_vista_plantacion(codigo_plantacion, 'a_itemschecklist');

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

    return false;

}





/*Funcion que permite cambiar estado activo/des de un proveedor

*/

function plan_guardar_formulario_harvesting_checklist_offline(codigo_detalle, codigo_plantacion, fecha_checklist, loose_bunches, conventional_organic, question1, question2, question3, question4, question5, question6, question7, question8, question9, question10, question11, question12, question13, question14, question15, question16, question17, question18, question19, question20, question21, question22, actions) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_guardar_formulario_harvesting_checklist.php',

        type: 'POST',

        data: {

            x1: codigo_detalle,

            x2: fecha_checklist,

            x3: loose_bunches,

            x4: conventional_organic,

            x5: question1,

            x6: question2,

            x7: question3,

            x8: question4,

            x9: question5,

            x10: question6,

            x11: question7,

            x12: question8,

            x13: question9,

            x14: question10,

            x15: question11,

            x16: question12,

            x17: question13,

            x18: question14,

            x19: question15,

            x20: question16,

            x21: question17,

            x22: question18,

            x23: question19,

            x24: question20,

            x25: question21,

            x26: question22,

            x27: actions,

            x28: codigo_plantacion

        },

    })

        .done(function (data) {

            jQuery.ajaxSetup({ async: false });

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            $('#modal_loading').modal('hide');

            //grl_mensaje('', info[1], tipo);

            //codigo_plantacion = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            plan_notificar_usuario_formulario_llenado(codigo_estado_plantacion, codigo_plantacion);

            plan_vista_plantacion(codigo_plantacion, 'a_itemschecklist');

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

    return false;

}











/*Funcion que permite cambiar estado activo/des de un proveedor

*/

function plan_agregar_quimico_aplicacion_quimico(cod_aplicacion_quimico, codigo_plantacion) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_agregar_quimico_aplicacion_quimico.php',

        type: 'POST',

        data: {

            x1: cod_aplicacion_quimico,

            x2: $('#cod_tipo_quimico').val(),

            x3: $('#cod_inventario_quimico').val(),

            x4: $('#cod_inventario_quimico option:selected').data('cod_unidad_medida'),

            x5: $('#cod_unidad_medida').val(),

            x6: $('#cantidad_sugerida').val()

        },

    })

        .done(function (data) {

            jQuery.ajaxSetup({ async: false });

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            $('#modal_loading').modal('hide');

            grl_mensaje('', info[1], tipo);

            //codigo_plantacion = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            //plan_vista_plantacion(codigo_plantacion, 'a_itemschecklist');

            plan_listado_quimicos_aplicacion_quimico(cod_aplicacion_quimico, 'tbody_quimicos');

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

    return false;

}





function plan_listado_quimicos_aplicacion_quimico(cod_aplicacion, div) {

    jQuery.ajaxSetup({ async: false });

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_cargar_listado_quimicos_aplicacion_quimico.php',

        type: 'POST',

        data:

        {

            x1: cod_aplicacion

        },

    })

        .done(function (data) {

            $('#' + div).empty().append(data);

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            console.log("error");

        })

        .always(function () {

            console.log("complete");

        });



}







/*Funcion que permite cambiar estado activo/des de una zona

*/

function plan_eliminar_quimico_aplicacion_quimico(cod_detalle, cod_aplicacion) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_eliminar_quimico_aplicacion_quimico.php',

        type: 'POST',

        data: {

            x1: cod_detalle

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

            //bw_vista_zona(0);

            plan_listado_quimicos_aplicacion_quimico(cod_aplicacion, 'tbody_quimicos');

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

    return false;

}





function plan_abrir_div_cantidad_aplicada(cod_detalle, cod_aplicacion, cantidad_sugerida, cantidad_aplicada) {

    if (cantidad_aplicada == null || cantidad_aplicada == '' || cantidad_aplicada == 0) {

        $('#div_cantidad_aplicada').removeClass('hide');

        $('#btn_agregar_cantidad_aplicada').data('cod_detalle', cod_detalle);

        codigo_detalle = cod_detalle;

        cod_aplicacion_quimico = cod_aplicacion;

        $('#cantidad_aplicada').val(cantidad_sugerida);

    } else {

        grl_mensaje('The applied amount has already been entered', 'La cantidad aplicada ya ha sido ingresada', 'warning');

    }

}



function plan_ingresar_cantidad_aplicada_quimico(cod_detalle, cantidad_aplicada) {

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_ingresar_cantidad_aplicada_quimico.php',

        type: 'POST',

        data:

        {

            x1: cod_detalle,

            x2: cantidad_aplicada

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

            //bw_vista_zona(0);

            plan_listado_quimicos_aplicacion_quimico(cod_aplicacion_quimico, 'tbody_quimicos');

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            console.log("error");

        })

        .always(function () {

            console.log("complete");

        });



}









/*Funcion que permite eliminar una aplicación de quimicos a la plantación

*/

function plan_eliminar_aplicar_quimico(cod_aplicacion, cod_plantacion) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_eliminar_aplicar_quimico.php',

        type: 'POST',

        data: {

            x1: cod_aplicacion

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

            //bw_vista_zona(0);

            //plan_listado_quimicos_aplicacion_quimico(cod_aplicacion,'tbody_quimicos');

            plan_vista_plantacion(cod_plantacion, 'a_historial_plantacion');

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

    return false;

}





/*

* Función que obtiene las zonas activas

*/

function plan_constructor_listado_plantaciones(id = 'cod_plantacion') {

    $.ajax({

        type: 'POST',

        url: site_url + 'mod_plantaciones/funciones/plan_listado_plantaciones.php',

        dataType: 'json',

        success: function (data) {

            $('#' + id).empty();

            if (data != null) {

                if (data.length > 1) {
                    ;

                    $.each(data, function (i, item) {

                        if (item.cod_estado < 9)

                            //$('#' + id).append(new Option(item.zona + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_zona));

                            $('#' + id).append('<option value="' + item.cod_plantacion + '" >' + item.nombre_empresa + '-' + item.anio_plantacion + '-' + item.num_plantacion + '-' + item.codigo_temporada + '</option>');

                    });

                } else {

                    if (data[0].cod_estado < 9)

                        //$('#' + id).append(new Option(data[0].zona + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_zona));

                        $('#' + id).append('<option value="' + data[0].cod_plantacion + '" >' + data[0].nombre_empresa + '-' + data[0].anio_plantacion + '-' + data[0].num_plantacion + '-' + data[0].codigo_temporada + '</option>');

                }

            } else {

                $('#' + id).append(new Option('No hay opciones', '-b'));

            }



            $('.selectpicker').selectpicker('refresh');

        }

    });

}





/*Funcion que permite guardar un trasplante con su información

*/

function plan_guardar_trasplantar(codigo_plantacion) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_guardar_trasplantar.php',

        type: 'POST',

        data: {

            x1: codigo_plantacion,

            //x2: $('#nombre_bloque').val(),

            x2: $('#cod_plantacion').val(),

            x3: $('#cod_bloques_plantacion2').val(),

            x4: $('#cantidad').val(),

            x5: $('#observacion_trasplantar').val(),

            x6: $('#cod_bloques_trasplante').val(),

            x7: $('#numero_carga').val(),

            x8: $('#fecha_trasplante').val()

        },

    })

        .done(function (data) {

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            grl_mensaje('', info[1], tipo);

            codigo_plantacion = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            plan_vista_plantacion(codigo_plantacion);

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

}





/*Funcion que permite guardar un trasplante con su información

*/

function plan_guardar_trasplantar_offline(codigo_plantacion, cod_plantacion, cod_bloques_plantacion, cantidad, cod_bloques_trasplante, numero_carga, fecha_trasplante, observacion_trasplantar) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_guardar_trasplantar.php',

        type: 'POST',

        data: {

            x1: codigo_plantacion,

            x2: cod_plantacion,

            x3: cod_bloques_plantacion,

            x4: cantidad,

            x5: observacion_trasplantar,

            x6: cod_bloques_trasplante,

            x7: numero_carga,

            x8: fecha_trasplante

        },

    })

        .done(function (data) {

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            grl_mensaje('', info[1], tipo);

            codigo_plantacion = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            plan_vista_plantacion(codigo_plantacion);

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

}



function plan_cargar_modal_worksheet_bloque(codigo_detalle) {

    $.ajax({

        url: 'mod_plantaciones/funciones/plan_cargar_informacion_harvesting_worksheet.php',

        type: 'POST',

        dataType: 'json',

        data: {

            codigo_detalle: codigo_detalle

        },

    })

        .done(function (data) {

            jQuery.ajaxSetup({ async: false });

            $('#codigo_harvesting_worksheet').val(data[0].cod_formulario);

            $('#actualizar_harvest_date').val(data[0].harvest_date);

            $('#actualizar_phi').selectpicker('val', data[0].phi);

            $('#actualizar_cellos').selectpicker('val', data[0].cellos);

            $('#actualizar_increment_bunch_cello').selectpicker('val', data[0].increment_bunch_cello);

            $('#actualizar_area_finished').selectpicker('val', data[0].area_finished);

            $('#actualizar_acres_harvested').val(data[0].acres_harvested);

            $('#actualizar_orden_compra').val(data[0].orden_compra);

            $('#actualizar_cantidad_cosechada_worksheet').val(data[0].cantidad_cosechada);

            $('#actualizar_cantidad_empacada_worksheet').val(data[0].cantidad_empacada);

            $('#actualizar_crop_number').val(data[0].crop_number);

            $('#actualizar_date_packed').val(data[0].date_packed);

            $('#actualizar_totes_harvested').val(data[0].totes_harvested);

            $('#actualizar_totes_packed').val(data[0].totes_packed);

            $('#actualizar_commments_harvesting_worksheet').val(data[0].commments_harvesting_worksheet);

            $('.selectpicker').selectpicker('refresh');

            $('#modal_actualizar_harvesting_worksheet').modal('show');

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            console.log("error");

        })

        .always(function () {

            console.log("complete");

        });

}



function plan_cargar_modal_worksheet_plantacion(codigo_detalle) {

    $.ajax({

        url: 'mod_plantaciones/funciones/plan_cargar_informacion_harvesting_worksheet.php',

        type: 'POST',

        dataType: 'json',

        data: {

            codigo_detalle: codigo_detalle

        },

    })

        .done(function (data) {

            $('#codigo_harvesting_worksheet').val(data[0].cod_formulario);

            $('#actualizar_harvest_date').val(data[0].harvest_date);

            $('#actualizar_phi').selectpicker('val', data[0].phi);

            $('#actualizar_cellos').selectpicker('val', data[0].cellos);

            $('#actualizar_increment_bunch_cello').selectpicker('val', data[0].increment_bunch_cello);

            $('#actualizar_area_finished').selectpicker('val', data[0].area_finished);

            $('#actualizar_acres_harvested').val(data[0].acres_harvested);

            $('#actualizar_orden_compra').val(data[0].orden_compra);

            $('#actualizar_crop_number').val(data[0].crop_number);

            $('#actualizar_cantidad_cosechada_worksheet').val(data[0].cantidad_cosechada);

            $('#actualizar_cantidad_empacada_worksheet').val(data[0].cantidad_empacada);

            $('#actualizar_commments_harvesting_worksheet').val(data[0].commments_harvesting_worksheet);

            $('.selectpicker').selectpicker('refresh');

            $('#modal_actualizar_harvesting_worksheet').modal('show');

        })

        .fail(function () {

            console.log("error");

        })

        .always(function () {

            console.log("complete");

        });

}





/*Funcion que permite guardar un trasplante con su información

*/

function plan_actualizar_harvesting_worksheet(codigo_formulario) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_actualizar_harvesting_worksheet.php',

        type: 'POST',

        data: {

            x1: $('#actualizar_harvest_date').val(),

            x2: $('#actualizar_phi option:selected').val(),

            x3: $('#actualizar_cellos option:selected').val(),

            x4: $('#actualizar_increment_bunch_cello option:selected').val(),

            x5: $('#actualizar_area_finished option:selected').val(),

            x6: $('#actualizar_acres_harvested').val(),

            x7: $('#actualizar_orden_compra').val(),

            x8: $('#actualizar_cantidad_cosechada_worksheet').val(),

            x9: $('#actualizar_cantidad_empacada_worksheet').val(),

            x10: $('#actualizar_commments_harvesting_worksheet').val(),

            x11: codigo_formulario,

            x12: $('#actualizar_crop_number').val(),

            x13: $('#actualizar_date_packed').val(),

            x14: $('#actualizar_totes_harvested').val(),

            x15: $('#actualizar_totes_packed').val()

        },

    })

        .done(function (data) {

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            grl_mensaje('', info[1], tipo);

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            plan_vista_plantacion(codigo_plantacion);

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

}









/*Funcion que permite eliminar una harvesting worksheet a la plantación

*/

function plan_eliminar_worksheet(cod_formulario) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_eliminar_worksheet.php',

        type: 'POST',

        data: {

            x1: cod_formulario

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

            //bw_vista_zona(0);

            //plan_listado_quimicos_aplicacion_quimico(cod_aplicacion,'tbody_quimicos');

            plan_vista_plantacion(codigo_plantacion);

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

    return false;

}





/*Funcion que permite cambiar estado activo/des de un proveedor

*/

function plan_guardar_load_report_plantacion(codigo_plantacion, codigo_reporte) {

    var respuesta;

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_guardar_load_report_plantacion.php',

        type: 'POST',

        data: {

            x1: codigo_plantacion,

            x2: $('#cod_bloques_load_report').val(),

            x3: $('#cod_inventario_load_report option:selected').val(),

            x4: $('#num_orden_compra').val(),

            x5: $('#fecha_orden').val(),

            x6: $('#num_lote').val(),

            x7: $('#num_lote_ranch').val(),

            x8: $('#inicial_size_harvest').val(),

            x9: $('#final_size_harvest').val(),

            x10: $('#dark_green_color option:selected').val(),

            x11: $('#yellow_leaves option:selected').val(),

            x12: $('#weeds option:selected').val(),

            x13: $('#optimal_soil_water_capacity option:selected').val(),

            x14: $('#right_density option:selected').val(),

            x15: $('#other_defects').val(),

            x16: $('#size_range_porcentage').val(),

            x17: $('#inicial_size_range').val(),

            x18: $('#final_size_range').val(),

            x19: $('#select_size_range option:selected').val(),

            x20: $('#size_range1').val(),

            x21: $('#select_size_range1 option:selected').val(),

            x22: $('#size_range2').val(),

            x23: $('#select_size_range2 option:selected').val(),

            x24: $('#other_defects_ha').val(),

            x25: $('#select_other_defects_ha option:selected').val(),

            x26: $('#dew_leaf option:selected').val(),

            x27: $('#inicial_time_harvest').val(),

            x28: $('#final_time_harvest').val(),

            x29: $('#temperature_product').val(),

            x30: $('#inicial_average_tote_weight_reported').val(),

            x31: $('#final_average_tote_weight_reported').val(),

            x32: $('#real_average_tote_weight').val(),

            x33: $('#time_receiving').val(),

            x34: $('#total_load_lbs_goal').val(),

            x35: $('#load_weight_received').val(),

            x36: $('#average_tote_weight').val(),

            x37: $('#temperature_receiving').val(),

            x38: $('#time_vacuum_cooler').val(),

            x39: $('#temperature_vacuum_cooler').val(),

            x40: $('#hydrocooling option:selected').val(),

            x41: $('#time_pickup').val(),

            x42: $('#tlc').val(),

            x43: $('#vacuum_cooler').val(),

            x44: $('#total_temperature').val(),

            x45: $('#pickup_truck_checkin').val(),

            x46: $('#number_cut option:selected').val(),

            x47: codigo_reporte

        },

    })

        .done(function (data) {

            jQuery.ajaxSetup({ async: false });

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            $('#modal_loading').modal('hide');

            grl_mensaje('', info[1], tipo);

            //codigo_plantacion = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            plan_vista_plantacion(codigo_plantacion, 'a_itemschecklist');

            //plan_notificar_usuario_formulario_llenado(codigo_estado_plantacion, codigo_plantacion);

            jQuery.ajaxSetup({ async: true });

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

    return false;

}





/*

* Función que obtiene las zonas activas

*/

function plan_constructor_listado_semillas_plantacion(id = 'cod_inventario_semilla', codigo_plantacion) {

    $.ajax({

        type: 'POST',

        url: site_url + 'mod_plantaciones/funciones/plan_listado_semillas_plantacion.php',

        dataType: 'json',

        data: ({

            x1: codigo_plantacion

        }),

        success: function (data) {

            $('#' + id).empty();

            if (data != null) {

                if (data.length > 1) {
                    ;

                    $.each(data, function (i, item) {

                        //$('#' + id).append(new Option(item.zona + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_zona));

                        $('#' + id).append('<option value="' + item.cod_inventario_semilla + '" ' + '>' + item.nombre_semilla + '</option>');

                    });

                } else {

                    //$('#' + id).append(new Option(data[0].zona + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_zona));

                    $('#' + id).append('<option value="' + data[0].cod_inventario_semilla + '" ' + '>' + data[0].nombre_semilla + '</option>');

                }

            } else {

                $('#' + id).append(new Option('No hay opciones', '-b'));

            }



            $('.selectpicker').selectpicker('refresh');

        }

    });

}





/*Función que carga modal de aplicar químico para que operador actualice los datos*/

function plan_cargar_modal_load_repor_plantacion(cod_reporte) {



    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_cargar_modal_load_repor_plantacion.php',

        type: 'POST',

        dataType: 'json',

        data: {

            x1: cod_reporte

        },

    })

        .done(function (data) {

            jQuery.ajaxSetup({ async: false });

            $('#modal_load_report').modal('show');

            $('#cod_bloques_load_report').selectpicker('val', data[0].cod_bloques_plantacion.split(','));

            $('#cod_inventario_load_report').selectpicker('val', data[0].cod_inventario_semilla);

            $('#num_orden_compra').val(data[0].num_orden_compra),

                $('#fecha_orden').val(data[0].fecha_orden),

                $('#num_lote').val(data[0].num_lote),

                $('#num_lote_ranch').val(data[0].num_lote_ranch),

                $('#inicial_size_harvest').val(data[0].inicial_size_harvest),

                $('#final_size_harvest').val(data[0].final_size_harvest),

                $('#dark_green_color').selectpicker('val', data[0].dark_green_color);

            $('#yellow_leaves').selectpicker('val', data[0].yellow_leaves);

            $('#weeds').selectpicker('val', data[0].weeds),

                $('#optimal_soil_water_capacity').selectpicker('val', data[0].optimal_soil_water_capacity);

            $('#right_density').selectpicker('val', data[0].right_density),

                $('#other_defects').val(data[0].other_defects),

                $('#size_range_porcentage').val(data[0].size_range_porcentage),

                $('#inicial_size_range').val(data[0].inicial_size_range),

                $('#final_size_range').val(data[0].final_size_range),

                $('#select_size_range').selectpicker('val', data[0].select_size_range);

            $('#size_range1').val(data[0].size_range1),

                $('#select_size_range1').selectpicker('val', data[0].select_size_range1);

            $('#size_range2').val(data[0].size_range2),

                $('#select_size_range2').selectpicker('val', data[0].select_size_range2);

            $('#other_defects_ha').val(data[0].other_defects_ha),

                $('#select_other_defects_ha').selectpicker('val', data[0].select_other_defects_ha);

            $('#dew_leaf').selectpicker('val', data[0].dew_leaf),

                $('#inicial_time_harvest').val(data[0].inicial_time_harvest),

                $('#final_time_harvest').val(data[0].final_time_harvest),

                $('#temperature_product').val(data[0].temperature_product),

                $('#inicial_average_tote_weight_reported').val(data[0].inicial_average_tote_weight_reported),

                $('#final_average_tote_weight_reported').val(data[0].final_average_tote_weight_reported),

                $('#real_average_tote_weight').val(data[0].real_average_tote_weight),

                $('#time_receiving').val(data[0].time_receiving),

                $('#total_load_lbs_goal').val(data[0].total_load_lbs_goal),

                $('#load_weight_received').val(data[0].load_weight_received),

                $('#average_tote_weight').val(data[0].average_tote_weight),

                $('#temperature_receiving').val(data[0].temperature_receiving),

                $('#time_vacuum_cooler').val(data[0].time_vacuum_cooler),

                $('#temperature_vacuum_cooler').val(data[0].temperature_vacuum_cooler),

                $('#hydrocooling').selectpicker('val', data[0].hydrocooling);

            $('#time_pickup').val(data[0].time_pickup),

                $('#tlc').val(data[0].tlc),

                $('#vacuum_cooler').val(data[0].vacuum_cooler),

                $('#total_temperature').val(data[0].total_temperature),

                $('#pickup_truck_checkin').val(data[0].pickup_truck_checkin),

                $('#number_cut').selectpicker('val', data[0].number_cut);

            $('.selectpicker').selectpicker('refresh');

            codigo_reporte = data[0].cod_reporte;

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
Funcion que permite guardar una zona de roacido a la plantacion
*/

function plan_agregar_zona_rociado_plantacion(codigo_plantacion) {
    console.log("Aqui");
    console.log($('#cod_quimico_rociadores option:selected').val());

    console.log($('#cod_zona_rociado').val());

    console.log($('#cod_bloques_rociado').val());

    console.log($('#cod_tipo_zona').val());

    console.log($('#cantidad_quimico').val());

    console.log($('#cod_unidad_medida2 option:selected').val());

    console.log($('#fecha_rociado').val());
    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_agregar_zona_rociado_plantacion.php',

        type: 'POST',

        data: {

            x1: codigo_plantacion,

            x2: $('#cod_zona_rociado').val(),

            x8: $('#cod_bloques_rociado').val(),

            x3: $('#cod_tipo_zona').val(),

            x4: $('#cod_quimico_rociadores option:selected').val(),

            x5: $('#cantidad_quimico').val(),

            x6: $('#cod_unidad_medida2 option:selected').val(),

            x7: $('#fecha_rociado').val()

        },

    })

        .done(function (data) {

            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            grl_mensaje('', info[1], tipo);

            codigo_plantacion = info[2];

            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');

            plan_vista_plantacion(codigo_plantacion);

        })

        .fail(function () {

            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');

        })

        .always(function () { });

}

/*

* Función que obtienes los usuarios recomendados por finca

*/
function plan_constructor_listado_usuarios_por_finca(cod_info_empresa, id = 'usuario_finca_recomendado', flag_seleccione = 0) {

    $.ajax({
        type: 'POST',
        url: site_url + 'mod_plantaciones/funciones/plan_listado_usuarios_por_finca.php',
        dataType: 'json',
        data: ({
            x1: cod_info_empresa
        }),
        success: function (data) {

            $('#' + id).empty();
            if (flag_seleccione == 0) {
                $('#' + id).append(new Option('Select', '-b'))
            }
            if (data != null) {
                if (data.length > 1) {
                    $.each(data, function (i, item) {
                        $('#' + id).append(new Option(item.nombre, item.cod_usuario));
                    });
                } else if (data.length > 0) {
                    $('#cod_usuario').append(new Option(data[0].nombre, data[0].cod_usuario));
                } else {
                    $('#cod_usuario').empty().append(new Option('Vacío', ''));


                }
            } else {
                $('#' + id).append(new Option('No user', '-b'));
            }
            $('.selectpicker').selectpicker('refresh');
        }
    });
}

/*
    Funcion que guarda un usuario recomendado
*/

function plan_guardar_recomendado() {

    $.ajax({

        url: site_url + 'mod_plantaciones/funciones/plan_guardar_recomendado.php',

        type: 'POST',

        data: {
            x1: $('#cod_info_empresa').val(),

            x2: $('#usuario_finca_recomendado').val(),

            x3: $('#motivo_recomendado').val(),

        },

    })

        .done(function (data) {
            var info = data.split("|");

            var mensaje = info[1];

            info[0] == 1 ? tipo = 'danger' : tipo = 'success';

            grl_mensaje('', info[1], tipo);

            $('#cod_info_empresa').append(new Option('Select', '-b'));
            $('#usuario_finca_recomendado').append(new Option('Select', '-b'));
            $('#motivo_recomendado').val(" ");

            plan_vista_recomenados()

        }).fail(function () {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        }).always(function () { });

}


/*

* Función que permite cargar vista para ver información de una zona y actualizarla

*/

function plan_vista_recomenados() {



    //toggleModuleNav();    

    $.ajax({

        type: 'POST',

        url: site_url + 'mod_plantaciones/ui/plan_recomendados.php',

        success: function (data) {

            $('#div_cuerpo_menu').empty();

            $('#div_cuerpo_menu').html(data);

        }

    });

}