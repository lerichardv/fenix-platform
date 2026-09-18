/*
 * Funciones jQuery para el módulo de control de calidad.
 * @author      Jairo Bonilla
 * @date        2019-01-10
 */

var codigo_producto = 0;
var codigo_cuarto_frio = 0;
var codigo_seccion = 0;
var codigo_control_calidad = 0;
var site_url = window.location.protocol + '//' + window.location.hostname + '/';
var codigo_reporte = 0;

 /*
 * Función que permite cargar vista para ver información de un producto y actualizarlo
 */
function qua_vista_producto(codigo_producto, nombre_panel){
    if(nombre_panel == undefined)
    {
        nombre_panel = null;
    }
    //toggleModuleNav();    
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_control_calidad/ui/qua_productos.php',
        data:
        {
            cod_producto: codigo_producto,
            nombre_panel: nombre_panel
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}
 /*
 * Función que permite cargar vista para ver información de un producto y actualizarlo
 */
function qua_vista_graficos(cod_pais,cod_departamento){
    //toggleModuleNav();    
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_control_calidad/ui/qua_graficos.php',
        data:
        {
            cod_pais: cod_pais,
            cod_departamento: cod_departamento
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}

/*Funcion que permite guardar un producto con su información
 */
function qua_guardar_producto(codigo_producto) {
    var respuesta;
    $.ajax({
            url: site_url + 'mod_control_calidad/funciones/qua_guardar_producto.php',
            type: 'POST',
            data: {
                x1: codigo_producto,
                x2: $('#nombre_producto').val(),
                x3: $('#cod_pais').val(),
                x4: $('#cod_departamento').val()
            },
        })
        .done(function(data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', info[1], tipo);
            codigo_producto = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            qua_vista_producto(codigo_producto);
        })
        .fail(function() {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function() {});
}

/*Funcion que permite cambiar estado activo/des de un producto
 */
function qua_cambiar_estado_producto(cod_producto, valor) {
    var respuesta;
    $.ajax({
            url: site_url + 'mod_control_calidad/funciones/qua_cambiar_estado_producto.php',
            type: 'POST',
            data: {
                x1: cod_producto,
                x2: valor
            },
        })
        .done(function(data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', info[1], tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            qua_vista_producto(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function() {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function() {});
    return false;
}

 /*
 * Función que permite cargar vista para ver información de un cuarto frío y actualizarlo
 */
function qua_vista_cuarto_frio(codigo_cuarto_frio, nombre_panel){
    if(nombre_panel == undefined)
    {
        nombre_panel = null;
    }
    //toggleModuleNav();    
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_control_calidad/ui/qua_cuartos_frios.php',
        data:
        {
            cod_cuarto_frio: codigo_cuarto_frio,
            nombre_panel: nombre_panel
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}

/*Funcion que permite guardar un cuarto frío con su información
 */
function qua_guardar_cuarto_frio(codigo_cuarto_frio) {
    var respuesta;
    $.ajax({
            url: site_url + 'mod_control_calidad/funciones/qua_guardar_cuarto_frio.php',
            type: 'POST',
            data: {
                x1: codigo_cuarto_frio,
                x2: $('#nombre_cuarto').val(),
                x3: $('#cod_pais').val(),
                x4: $('#cod_departamento').val()
            },
        })
        .done(function(data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', info[1], tipo);
            codigo_cuarto_frio = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            qua_vista_cuarto_frio(codigo_cuarto_frio);
        })
        .fail(function() {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function() {});
}

/*Funcion que permite cambiar estado activo/des de un cuarto frío
 */
function qua_cambiar_estado_cuarto_frio(cod_cuarto, valor) {
    var respuesta;
    $.ajax({
            url: site_url + 'mod_control_calidad/funciones/qua_cambiar_estado_cuarto_frio.php',
            type: 'POST',
            data: {
                x1: cod_cuarto,
                x2: valor
            },
        })
        .done(function(data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', info[1], tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            qua_vista_cuarto_frio(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function() {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function() {});
    return false;
}

/*Funcion que permite guardar un cuarto frío con su información
 */
function qua_guardar_seccion(codigo_cuarto_frio) {
    var respuesta;
    $.ajax({
            url: site_url + 'mod_control_calidad/funciones/qua_guardar_seccion.php',
            type: 'POST',
            data: {
                x1: codigo_cuarto_frio,
                x2: $('#nombre_seccion').val()
            },
        })
        .done(function(data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', info[1], tipo);
            codigo_cuarto_frio = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            qua_vista_cuarto_frio(codigo_cuarto_frio);
        })
        .fail(function() {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function() {});
}

/*Funcion que permite cambiar estado activo/des de un cuarto frío
 */
function qua_cambiar_estado_seccion(cod_seccion, valor) {
    var respuesta;
    $.ajax({
            url: site_url + 'mod_control_calidad/funciones/qua_cambiar_estado_seccion.php',
            type: 'POST',
            data: {
                x1: cod_seccion,
                x2: valor
            },
        })
        .done(function(data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', info[1], tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            qua_vista_cuarto_frio(codigo_cuarto_frio);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function() {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function() {});
    return false;
}

 /*
 * Función que permite cargar vista para ver información de un producto y actualizarlo
 */
function qua_vista_control_calidad(codigo_control_calidad, nombre_panel){
    if(nombre_panel == undefined)
    {
        nombre_panel = null;
    }
    //toggleModuleNav();    
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_control_calidad/ui/qua_control_calidad.php',
        data:
        {
            cod_control_calidad: codigo_control_calidad,
            nombre_panel: nombre_panel
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}

/*Funcion que permite guardar un control de calidad con su información
 */
function qua_guardar_control_calidad(codigo_control_calidad) {
    var respuesta;
    $.ajax({
            url: site_url + 'mod_control_calidad/funciones/qua_guardar_control_calidad.php',
            type: 'POST',
            data: {
                x1: codigo_control_calidad,
                x2: $('#cod_pais').val(),
                x3: $('#cod_departamento').val(),
                x4: $('#cod_producto').val(),
                x5: $('#fecha').val(),
                x6: $('#num_orden_compra').val(),
                x7: $('#tiempo').val(),
                x8: $('#temperatura_actual').val(),
                x9: $('#temperatura_establecida').val(),
                x10: $('#temperatura_minima').val(),
                x11: $('#temperatura_maxima').val(),
                x12: $('#temperatura_media').val(),
                x13: $('#tiempo_preshipment').val(),
                x14: $('#num_lote').val(),
                x15: $('#dias').val(),
                x16: $('#middle_temp1').val(),
                x17: $('#middle_temp2').val(),
                x18: $('#middle_temp3').val(),
                x19: $('#middle_temp4').val()
            },
        })
        .done(function(data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', info[1], tipo);
            codigo_control_calidad = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            qua_vista_control_calidad(codigo_control_calidad);
        })
        .fail(function() {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function() {});
}

/*Funcion que permite cambiar estado activo/des de un control de calidad
 */
function qua_cambiar_estado_control_calidad(cod_control_calidad, valor) {
    var respuesta;
    $.ajax({
            url: site_url + 'mod_control_calidad/funciones/qua_cambiar_estado_control_calidad.php',
            type: 'POST',
            data: {
                x1: cod_control_calidad,
                x2: valor
            },
        })
        .done(function(data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', info[1], tipo);
            //codigo_tipo_temporada = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            qua_vista_control_calidad(0);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function() {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function() {});
    return false;
}

/*Funcion que permite guardar un control de calidad con su información
 */
function qua_guardar_detalle_control_calidad(codigo_control_calidad) {
    var respuesta;
    $.ajax({
            url: site_url + 'mod_control_calidad/funciones/qua_guardar_detalle_control_calidad.php',
            type: 'POST',
            data: {
                x1: codigo_control_calidad,
                x2: $('#cod_cuarto_frio').val(),
                x3: $('#cod_seccion').val(),
                x4: $('#tiempo_detalle').val(),
                x5: $('#valor').val(),
                x6: $('#observaciones').val()
            },
        })
        .done(function(data) {
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            grl_mensaje('', info[1], tipo);
            codigo_control_calidad = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            qua_vista_control_calidad(codigo_control_calidad);
        })
        .fail(function() {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function() {});
}



/*
 * Función que obtiene las zonas activas
 */
function qua_constructor_listado_productos_por_pais_departamento(id = 'cod_producto', cod_pais, cod_departamento) {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_control_calidad/funciones/qua_listado_productos_por_pais_departamento.php',
        dataType: 'json',
        data: ({
            x1: cod_pais,
            x2: cod_departamento
        }),
        success: function(data) {
            $('#' + id).empty().append( new Option('Select','-b') );
            if (data != null) {
                if (data.length > 1) {;
                    $.each(data, function(i, item) {
                        //$('#' + id).append(new Option(item.zona + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_zona));
                        $('#' + id).append('<option value="'+item.cod_producto+'" >'+ item.nombre_producto+'</option>');
                    });
                } else {
                    //$('#' + id).append(new Option(data[0].zona + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_zona));
                    $('#' + id).append('<option value="'+data[0].cod_producto+'" >'+ data[0].nombre_producto+'</option>');
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
function qua_constructor_listado_cuartos_frios_por_pais_departamento(id = 'cod_cuarto_frio', cod_pais, cod_departamento) {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_control_calidad/funciones/qua_listado_cuartos_frios_por_pais_departamento.php',
        dataType: 'json',
        data: ({
            x1: cod_pais,
            x2: cod_departamento
        }),
        success: function(data) {
            $('#' + id).empty().append( new Option('Select','-b') );
            if (data != null) {
                if (data.length > 1) {;
                    $.each(data, function(i, item) {
                        //$('#' + id).append(new Option(item.zona + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_zona));
                        $('#' + id).append('<option value="'+item.cod_cuarto+'" >'+ item.nombre_cuarto+'</option>');
                    });
                } else {
                    //$('#' + id).append(new Option(data[0].zona + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_zona));
                    $('#' + id).append('<option value="'+data[0].cod_cuarto+'" >'+ data[0].nombre_cuarto+'</option>');
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
function qua_constructor_listado_secciones_cuartos_frios(id = 'cod_seccion', cod_cuarto_frio) {
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_control_calidad/funciones/qua_listado_secciones_cuartos_frios.php',
        dataType: 'json',
        data: ({
            x1: cod_cuarto_frio
        }),
        success: function(data) {
            $('#' + id).empty().append( new Option('Select','-b') );
            if (data != null) {
                if (data.length > 1) {;
                    $.each(data, function(i, item) {
                        //$('#' + id).append(new Option(item.zona + ' - ' + (item.activo == 1 ? 'Activado':'Desactivado'), item.cod_zona));
                        $('#' + id).append('<option value="'+item.cod_seccion+'" >'+ item.nombre_seccion+'</option>');
                    });
                } else {
                    //$('#' + id).append(new Option(data[0].zona + ' - ' + (data[0].activo == 1 ? 'Activado':'Desactivado'), data[0].cod_zona));
                    $('#' + id).append('<option value="'+data[0].cod_seccion+'" >'+ data[0].nombre_seccion+'</option>');
                }
            } else {
                $('#' + id).append(new Option('No hay opciones', '-b'));
            }

            $('.selectpicker').selectpicker('refresh');
        }
    });
}

 /*
 * Función que permite cargar vista para ver información de un producto y actualizarlo
 */
function qua_vista_load_report(codigo_reporte, nombre_panel){
    if(nombre_panel == undefined)
    {
        nombre_panel = null;
    }
    //toggleModuleNav();    
    $.ajax({
        type: 'POST',
        url: site_url + 'mod_control_calidad/ui/qua_load_report.php',
        data:
        {
            cod_reporte: codigo_reporte,
            nombre_panel: nombre_panel
        },
        success: function (data) {
            $('#div_cuerpo_menu').empty();
            $('#div_cuerpo_menu').html(data);
        }
    });
}


/*Funcion que permite cambiar estado activo/des de un proveedor
 */
function qua_guardar_load_report(codigo_reporte) {
    var respuesta;
    $.ajax({
            url: site_url + 'mod_control_calidad/funciones/qua_guardar_load_report.php',
            type: 'POST',
            data: {
                x1: codigo_reporte,
                x3: $('#cod_producto option:selected').val(),
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
                /*x30: $('#inicial_average_tote_weight_reported').val(),
                x31: $('#final_average_tote_weight_reported').val(),
                x32: $('#real_average_tote_weight').val(),*/
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
                //x45: $('#pickup_truck_checkin').val(),
                x46: $('#number_cut option:selected').val(),
                x47: $('#acres_cosechados').val(),
                x48: $('#comentarios').val(),
                x49: $('#select_yellow_leaves').val(),
                x50: $('#yellow_leaves2').val()
            },
        })
        .done(function(data) {
            jQuery.ajaxSetup({ async: false });
            var info = data.split("|");
            var mensaje = info[1];
            info[0] == 1 ? tipo = 'danger' : tipo = 'success';
            $('#modal_loading').modal('hide');
            grl_mensaje('', info[1], tipo);
            //codigo_reporte = info[2];
            //grl_obtener_cuerpo_menu(4, '/mod_capacitaciones/ui/cap_nueva_capacitacion.php');
            qua_vista_load_report(codigo_reporte, '');
            //plan_notificar_usuario_formulario_llenado(codigo_estado_plantacion, codigo_plantacion);
            jQuery.ajaxSetup({ async: true });
        })
        .fail(function() {
            grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
        })
        .always(function() {});
    return false;
}