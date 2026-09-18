
var site_url = window.location.protocol + '//' + window.location.hostname + '/';
/*
 * Función para select de cargos
 */
function usu_constructor_cargos_por_gerencia() {
  $.ajax({
    type: 'POST',
    url: site_url + 'mod_admin_usuarios/funciones/usu_listado_cargos.php',
    dataType: 'json',
    data: ({
      x1: $("#cod_gerencia").val()
    }),
    success: function (data) {
      if (data.length > 1) {
        $('#cod_cargo').append(new Option('Select', '-b'));
        $.each(data, function (i, item) {
          $('#cod_cargo').append(new Option(item.cargo, item.cod_cargo));
        });
      } else {
        $('#cod_cargo').append(new Option(data[0].cargo, data[0].cod_cargo));
      }
      $('.selectpicker').selectpicker('refresh');
    }
  });
}

/*
 * Función que obtiene las gerencias
 */
function usu_constructor_gerencias(path = '', x1 = 0) {
  $.ajax({
    type: 'POST',
    url: path + 'mod_admin_usuarios/funciones/usu_listado_gerencias.php',
    dataType: 'json',
    data: ({
      x1: x1
    }),
    success: function (data) {
      $('#cod_gerencia').empty();
      $('#cod_gerencia').append(new Option('Select', '-b'));
      if (data.length > 1) {
        $.each(data, function (i, item) {
          $('#cod_gerencia').append(new Option(item.gerencia, item.cod_gerencia));
        });
      } else {
        $('#cod_gerencia').append(new Option(data[0].gerencia, data[0].cod_gerencia));
      }
      $('.selectpicker').selectpicker('refresh');
    }
  });
}
/*
 * Función que obtiene el listado de tipos de usuarios
 */
function usu_constructor_tipos_de_usuarios(id = "cod_tipo_usuario") {
  $.ajax({
    type: 'POST',
    url: site_url + 'mod_admin_usuarios/funciones/usu_listado_tipos_de_usuarios.php',
    dataType: 'json',
    success: function (data) {
      console.log({ data });
      $('#' + id).empty();
      if (data != null) {
        if (data.length > 1) {
          $('#' + id).append(new Option('Select', '-b'));
          $.each(data, function (i, item) {
            $('#' + id).append(new Option(item.etiqueta_english, item.cod_tipo_usuario));
          });
        } else {
          $('#' + id).append(new Option(data[0].etiqueta_english, data[0].cod_tipo_usuario));
        }
      } else {
        $('#' + id).append(new Option('No options', '-b'));
      }
      $('.selectpicker').selectpicker('refresh');
    }
  });
}

/*
 * Función que obtiene los jefes inmediatos
 */
function usu_constructor_jefe_inmediato() {
  $.ajax({
    type: 'POST',
    url: site_url + 'mod_admin_usuarios/funciones/usu_listado_usuarios.php',
    dataType: 'json',
    success: function (data) {
      $('#cod_jefe_inmediato').empty();
      if (data.length > 1) {
        $('#cod_jefe_inmediato').append(new Option('Select', '-b'));
        $.each(data, function (i, item) {
          $('#cod_jefe_inmediato').append(new Option(item.nombre, item.cod_usuario));
        });
      } else {
        $('#cod_jefe_inmediato').append(new Option(data[0].nombre, data[0].cod_usuario));
      }
      $('.selectpicker').selectpicker('refresh');
    }
  });
}

/*
 * Función que obtiene los jefes inmediatos
 */
function usu_constructor_usuarios_todos() {
  $.ajax({
    type: 'POST',
    url: site_url + 'mod_admin_usuarios/funciones/usu_listado_usuarios.php',
    dataType: 'json',
    success: function (data) {
      $('#cod_usuarios').empty();
      if (data.length > 1) {
        $.each(data, function (i, item) {
          $('#cod_usuarios').append(new Option(item.nombre, item.cod_usuario));
        });
      } else {
        $('#cod_usuarios').append(new Option(data[0].nombre, data[0].cod_usuario));
      }
      $('.selectpicker').selectpicker('refresh');
    }
  });
}

function usu_get_vista_usuario(x2) {
  //grl_mensaje('Esperando a que Lester termine su vista. ','Pronto estará disponible... espero.','warning');
  $.ajax({
    type: 'POST',
    url: site_url + 'mod_admin_usuarios/ui/usu_registro_usuarios.php',
    data: ({
      x2: x2
    }),
    error: function () {
      $('#modal_loading').modal('hide');
      //grl_mensaje('Inténtelo de nuevo', ' Ha habido un inconveniente al cargar información', 'danger');
    },
    success: function (data) { /*
			$('#modal_loading').modal('hide');
			$('#modal_loading').on('hidden.bs.modal', function () {*/
      $('#div_cuerpo_menu').empty();
      $('#div_cuerpo_menu').html(data);
      //});
    }
  });
};

/*
 * Función que obtiene el listado de los empleados activos para un listbox.
 */
function usu_constructor_usuarios_activos(id = 'cod_usuario') {
  $.ajax({
    type: 'POST',
    url: site_url + 'mod_admin_usuarios/funciones/usu_listado_usuarios_activos.php',
    dataType: 'json',
    success: function (data) {
      $('#' + id).empty();
      if (data != null) {
        /*Verifica si el arreglo trae mas de un registro,
          en caso contrario lo llenara automaticamente*/
        if (data.length > 1) {
          $('#' + id).append(new Option('Select', '-b'));
          $.each(data, function (i, item) {
            $('#' + id).append(new Option(item.nombre, item.cod_usuario));
          });
        } else {
          $('#' + id).append(new Option(data[0].nombre, data[0].cod_usuario));
        }
      }
      else {
        $('#' + id).append(new Option('No hay opciones', '-b'));
      }
      $('.selectpicker').selectpicker('refresh');
    }
  });
}

/*
 * Función que obtiene el listado de los usuarios por gerencia para listbox
 */
function usu_constructor_usuarios_por_gerencia(x1) {
  $.ajax({
    type: 'POST',
    url: site_url + 'mod_admin_usuarios/funciones/usu_listado_usuarios_por_gerencia.php',
    dataType: 'json',
    data: ({
      x1: x1
    }),
    success: function (data) {
      $('#cod_usuario').empty();
      if (data != null) {
        /*Verifica si el arreglo trae mas de un registro,
          en caso contrario lo llenara automaticamente*/
        if (data.length > 1) {
          $('#cod_usuario').append(new Option('Select', '-b'));
          $.each(data, function (i, item) {
            $('#cod_usuario').append(new Option(item.nombre, item.cod_usuario));
          });
        } else {
          $('#cod_usuario').append(new Option(data[0].nombre, data[0].cod_usuario));
        }
      }
      else {
        $('#cod_usuario').append(new Option('No hay opciones', '-b'));
      }
      $('.selectpicker').selectpicker('refresh');
    }
  });
}

/*
 * Función que obtiene los menus según el usuario.
 */
function usu_constructor_menus_por_usuario(x1) {
  $.ajax({
    type: 'POST',
    url: site_url + 'mod_admin_usuarios/funciones/usu_listado_accesos.php',
    data: ({
      x1: x1
    }),
    success: function (data) {
      if (data != null) {
        $('#listado_items').empty().append(data);
      } else {
        $('#listado_items').empty().append('No hay documentos');
      }
    }
  });
}

/*
 * Función que obtiene los cargos segun la gerencia
 */
function usu_constructor_cargos_x_gerencia(x1) {
  $.ajax({
    type: 'POST',
    url: site_url + 'mod_admin_usuarios/funciones/usu_listado_cargos_por_gerencia.php',
    data: ({
      x1: x1
    }),
    success: function (data) {
      if (data != null) {
        $('#listado_items').empty().append(data);
      } else {
        $('#listado_items').empty().append('No hay documentos');
      }
    }
  });
}
/*
 * Función que obtiene el listado de los empleados activos para un listbox.
 */
function usu_constructor_perfiles_activos() {
  $.ajax({
    type: 'POST',
    url: site_url + 'mod_admin_usuarios/funciones/usu_listado_perfiles_activos.php',
    dataType: 'json',
    success: function (data) {
      $('#cod_perfil').empty();
      if (data != null) {
        /*Verifica si el arreglo trae mas de un registro,
          en caso contrario lo llenara automaticamente*/
        if (data.length > 1) {
          $('#cod_perfil').append(new Option('Select', '-b'));
          $.each(data, function (i, item) {
            $('#cod_perfil').append(new Option(item.perfil, item.cod_perfil));
          });
        } else {
          $('#cod_perfil').append(new Option(data[0].perfil, data[0].cod_perfil));
        }
      }
      else {
        $('#cod_perfil').append(new Option('No hay opciones', '-b'));
      }
      $('.selectpicker').selectpicker('refresh');
    }
  });
}


/*
 * Función que obtiene el listado de los usuarios por gerencia para listbox
 */
function usu_constructor_usuarios_por_cargos(x1, id = 'cod_operador') {
  $.ajax({
    type: 'POST',
    url: site_url + 'mod_admin_usuarios/funciones/usu_listado_usuarios_por_cargos.php',
    async: false,
    dataType: 'json',
    data: ({
      x1: x1
    }),
    success: function (data) {
      $('#' + id).empty();
      if (data != null) {
        /*Verifica si el arreglo trae mas de un registro,
        en caso contrario lo llenara automaticamente*/
        if (data.length > 1) {
          $('#' + id).append(new Option('Select', '-b'));
          $.each(data, function (i, item) {
            $('#' + id).append(new Option(item.nombre, item.cod_usuario));
          });
        }
        else {
          $('#' + id).append(new Option(data[0].nombre, data[0].cod_usuario));
        }
      }
      else {
        $('#' + id).append(new Option('No hay opciones', '-b'));
      }
      $('.selectpicker').selectpicker('refresh');
    }
  });
}

/*
 * Función que obtiene las granjas para registro de usuarios
 */
function usu_constructor_granjas() {
  $.ajax({
    type: 'POST',
    url: site_url + 'mod_admin_usuarios/funciones/usu_listado_granjas.php',
    dataType: 'json',
    success: function (data) {
      alert(2);
      $('#cod_finca').empty();
      if (data != null) {
        /*Verifica si el arreglo trae mas de un registro,
          en caso contrario lo llenara automaticamente*/
        if (data.length > 1) {
          $('#cod_finca').append(new Option('Select', '-b'));
          $.each(data, function (i, item) {
            alert(item.farm);
            $('#cod_finca').append(new Option(item.farm, item.cod_farms));
          });
        } else {
          $('#cod_finca').append(new Option(data[0].farm, data[0].cod_farms));
        }
      }
      else {
        $('#cod_finca').append(new Option('No options', '-b'));
      }
      $('.selectpicker').selectpicker('refresh');
    }
  });
}