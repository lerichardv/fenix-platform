<?PHP
/*
 * Vista del listado de empleados.
 * @author      Jairo Bonilla
 * @date        2016-07-22
 */
session_start();
if (!isset($_SESSION['cod_usuario'])) {
  echo "<script>window.location.href = '" . $_SESSION['úrl_inicio'] . "';</script>"; // Usando JS para redireccionar
  // header('Location: index.php'); // Método anterior (No funcional)
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_usuario.php");
include_once("../../libs/db_classes/db_farms.php");
/*INSTANCIAMIENTOS*/
$DB_USUARIO   = new db_usuario();
$DB_FARMS   = new db_farms();
$USUARIOS      = $DB_USUARIO->get_listado_usuarios();
$TOTALES       = $DB_USUARIO->get_totales_usuarios();
$GRANJAS      = $DB_FARMS->farm_listado_granjas();
$USUARIOS_PARA_LISTPICKER = [];
foreach ($USUARIOS as $usuario) {
  array_push($USUARIOS_PARA_LISTPICKER, (object) array('id' => $usuario['cod_usuario'], 'name' => $usuario['nombre']));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
  <title>Listado Usuarios</title>
</head>
<script type="text/javascript">
  jQuery.ajaxSetup({
    async: false
  });
  var favoritosCargados = false;
  var favoritosBotonesCargados = false;
  var usuariosPrincipalesCargados = false;
  var botonesUsuariosPrincipalesCargados = false;
  var dataUsuarioFavoritos = [];
  $('#modal_loading').modal('hide');
  $('.selectpicker').selectpicker({
    dropupAuto: 'true',
    container: 'body',
    size: '10',
    width: '100%',
    style: 'btn-sm btn-info',
    tickIcon: 'fa fa-check'
  });
  grl_overlay_loading('Cargando información');
  $(document).ready(function() {


    init_button_bar();
    /*
     *	Ejecuta el evento onClick en toda la fila enfocada
     */
    var table = document.getElementById("dev-table");
    var rows = table.getElementsByTagName("tr");
    for (i = 1; i < rows.length; i++) {
      var currentRow = table.rows[i];
      if (currentRow.getElementsByTagName("td").length > 1) {
        var createClickHandler =
          function(row) {
            return function() {
              var cell = row.getElementsByTagName("td")[0];
              var cod_usuario = cell.innerHTML;
              usu_get_vista_usuario(cod_usuario);
            };
          };
        currentRow.onclick = createClickHandler(currentRow);
      }
    }

    /*
     * Función que realiza la "busqueda" dentro de la tabla con información
     */
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
                  var no_results = $('<tr class="filterTable_no_results"><td colspan="2">No hay resultados.</td></tr>')
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
    farm_constructor_listado_granjas();
    farm_constructor_listado_localizaciones_en_granja();

    $('#modal_loading').modal('hide');
    jQuery.ajaxSetup({
      async: true
    });


    // Favoritos modal functionality
    // bulkEditFavoritoModalFunctionality();

    // Pay rate modal functionality
    // bulkEditPayRateModalFunctionality();

  });


  var bulkEditFavoritoModalFunctionality = () => {
    handleSelectBulkEditFavoritoModeChange();
    if (favoritosCargados) return;
    favoritosCargados = true;
    usuariosPrincipalesCargados = false;
    window.listPickerFavorito = new ListPicker(<?= json_encode($USUARIOS_PARA_LISTPICKER) ?>, '#list-picker-wrapper_favorito');

    if (favoritosBotonesCargados) return;
    favoritosBotonesCargados = true;
    $('#select_bulk_edit_favorito_mode').on('change', handleSelectBulkEditFavoritoModeChange);
    $('#btn_process_selected_users_mode_favorito').on('click', processSelectedUsersModeFavorito);
    $('#btn_process_farm_location_mode_favorito').on('click', processFarmLocationModeFavorito);
    $('#btn_process_favorito_mode').on('click', processFavoritoMode);
    $('#btn_process_farm_location_mode_favorito').on('click', () => {});


  }

  var bulkEditPayRateModalFunctionality = () => {
    handleSelectBulkEditPayRateModeChange();
    if (usuariosPrincipalesCargados) return;
    usuariosPrincipalesCargados = true;
    favoritosCargados = false;

    window.listPicker = new ListPicker(<?= json_encode($USUARIOS_PARA_LISTPICKER) ?>);

    if (botonesUsuariosPrincipalesCargados) return;
    botonesUsuariosPrincipalesCargados = true;
    $('#select_bulk_edit_pay_rate_mode').on('change', handleSelectBulkEditPayRateModeChange);
    $('#btn_process_selected_users_mode').on('click', processSelectedUsersMode);
    $('#btn_process_farm_location_mode').on('click', processFarmLocationMode);
    $('#btn_process_category_mode').on('click', processCategoryMode);
    $('#btn_process_farm_location_mode').on('click', () => {});
  }

  var handleSelectBulkEditPayRateModeChange = () => {
    if ($('#select_bulk_edit_pay_rate_mode').val() == '0') {
      $('#modal_for_selected_users_mode_wrapper').show();
      $('#modal_for_selected_users_mode_footer_wrapper').show();
      $('#modal_by_farm_and_location_mode_wrapper').hide();
      $('#modal_by_farm_and_location_mode_footer_wrapper').hide();
      $('#modal_by_category_mode_wrapper').hide();
      $('#modal_by_category_mode_wrapper_footer').hide();
    } else if ($('#select_bulk_edit_pay_rate_mode').val() == '1') {
      $('#modal_by_farm_and_location_mode_wrapper').show();
      $('#modal_by_farm_and_location_mode_footer_wrapper').show();
      $('#modal_for_selected_users_mode_wrapper').hide();
      $('#modal_for_selected_users_mode_footer_wrapper').hide();
      $('#modal_by_category_mode_wrapper').hide();
      $('#modal_by_category_mode_wrapper_footer').hide();
    } else {
      $('#modal_by_category_mode_wrapper').show();
      $('#modal_by_category_mode_wrapper_footer').show();
      $('#modal_for_selected_users_mode_wrapper').hide();
      $('#modal_for_selected_users_mode_footer_wrapper').hide();
      $('#modal_by_farm_and_location_mode_wrapper').hide();
      $('#modal_by_farm_and_location_mode_footer_wrapper').hide();
    }
  }

  var processSelectedUsersMode = () => {

    if (window.listPicker.selected().length == 0) {
      grl_mensaje('Please select at least one person. ', '', 'warning');
      $('#list-picker-selected-items-wrapper').addClass('div-has-error');
      return false;
    } else {
      $('#list-picker-selected-items-wrapper').removeClass('div-has-error');
    }

    if ($('#input_bulk_edit_pay_rate_for_selected_users').val() == "") {
      grl_mensaje('Please assign the pay rate. ', '', 'warning');
      $('#input_bulk_edit_pay_rate_for_selected_users').addClass('input-has-error');
      return false;
    } else {
      $('#input_bulk_edit_pay_rate_for_selected_users').removeClass('input-has-error');
    }

    let confirmed = confirm("Are you sure you want to proceed?");

    if (confirmed) {
      $.ajax({
        type: "POST",
        url: "mod_admin_usuarios/funciones/usu_actualizar_pay_rates_por_usuarios.php",
        data: {
          x1: $('#input_bulk_edit_pay_rate_for_selected_users').val(),
          x2: JSON.stringify(window.listPicker.selected()),
        },
        error: (error) => {
          grl_mensaje('Something went wrong, please contact the administrator. ', '', 'warning');
          console.log(error);
        },
        success: (res) => {
          window.listPicker.reset();
          $('#input_bulk_edit_pay_rate_for_selected_users').val(0);
          grl_mensaje('Bulk edit pay rate for selected users processed succesfully. ', '', 'success');
          grl_obtener_modulo(3);
          $('#modal_bulk_edit_pay_rate').modal('hide');
        }
      });
    }
  }

  var processFarmLocationMode = () => {

    if ($('#select_farm_bulk_edit_pay_rate_by_farm_location option:selected').val() == '-b') {
      $('#select_farm_bulk_edit_pay_rate_by_farm_location').selectpicker('setStyle', 'btn-info', 'remove');
      $('#select_farm_bulk_edit_pay_rate_by_farm_location').selectpicker('setStyle', 'btn-danger btn-sm');
      grl_mensaje('Please select the location. ', '', 'warning');
      return false;
    } else {
      $('#select_farm_bulk_edit_pay_rate_by_farm_location').selectpicker('setStyle', 'btn-danger', 'remove');
      $('#select_farm_bulk_edit_pay_rate_by_farm_location').selectpicker('setStyle', 'btn-info btn-sm');
    }

    if ($('#input_bulk_edit_pay_rate_by_farm_location').val() == "") {
      grl_mensaje('Please assign the pay rate. ', '', 'warning');
      $('#input_bulk_edit_pay_rate_by_farm_location').addClass('input-has-error');
      return false;
    } else {
      $('#input_bulk_edit_pay_rate_by_farm_location').removeClass('input-has-error');
    }

    let confirmed = confirm("Are you sure you want to proceed?");

    if (confirmed) {
      $.ajax({
        type: "POST",
        url: "mod_admin_usuarios/funciones/usu_actualizar_pay_rates_por_granja.php",
        data: {
          x1: $('#input_bulk_edit_pay_rate_by_farm_location').val(),
          x2: JSON.stringify($('#select_farm_bulk_edit_pay_rate_by_farm_location').val())
        },
        error: (error) => {
          grl_mensaje('Something went wrong, please contact the administrator. ', '', 'warning');
          console.log(error);
        },
        success: (res) => {
          window.listPicker.reset();
          $('#input_bulk_edit_pay_rate_by_farm_location').val("");
          grl_mensaje('Bulk edit pay rate for selected farm processed succesfully. ', '', 'success');
          grl_obtener_modulo(3);
          $('#modal_bulk_edit_pay_rate').modal('hide');
        }
      });
    }

  }

  var processCategoryMode = () => {

    if ($('#select_category_bulk_edit_pay_rate option:selected').val() == '-b') {
      $('#select_category_bulk_edit_pay_rate').selectpicker('setStyle', 'btn-info', 'remove');
      $('#select_category_bulk_edit_pay_rate').selectpicker('setStyle', 'btn-danger btn-sm');
      grl_mensaje('Please select the category. ', '', 'warning');
      return false;
    } else {
      $('#select_category_bulk_edit_pay_rate').selectpicker('setStyle', 'btn-danger', 'remove');
      $('#select_category_bulk_edit_pay_rate').selectpicker('setStyle', 'btn-info btn-sm');
    }

    if ($('#input_pay_rate_by_category').val() == "") {
      grl_mensaje('Please assign the pay rate. ', '', 'warning');
      $('#input_pay_rate_by_category').addClass('input-has-error');
      return false;
    } else {
      $('#input_pay_rate_by_category').removeClass('input-has-error');
    }

    let confirmed = confirm("Are you sure you want to proceed?");

    if (confirmed) {
      $.ajax({
        type: "POST",
        url: "mod_admin_usuarios/funciones/usu_actualizar_pay_rates_por_categoria.php",
        data: {
          x1: $('#input_pay_rate_by_category').val(),
          x2: JSON.stringify($('#select_category_bulk_edit_pay_rate').val())
        },
        error: (error) => {
          grl_mensaje('Something went wrong, please contact the administrator. ', '', 'warning');
          console.log(error);
        },
        success: (res) => {
          window.listPicker.reset();
          $('#input_pay_rate_by_category').val("");
          grl_mensaje('Bulk edit pay rate for selected category processed succesfully. ', '', 'success');
          grl_obtener_modulo(3);
          $('#modal_bulk_edit_pay_rate').modal('hide');
        }
      });
    }

  }

  // FAVORITO MODAL FUNCTIONALITY 

  var handleSelectBulkEditFavoritoModeChange = () => {
    $('#modal_for_selected_users_mode_wrapper_favorito').show();
    $('#modal_for_selected_users_mode_footer_wrapper_favorito').show();
  }

  var processSelectedUsersModeFavorito = () => {

    let confirmed = confirm("Are you sure you want to proceed?");


    if (confirmed) {

      $.ajax({
        type: "POST",
        url: "mod_admin_usuarios/funciones/usu_actualizar_favoritos_granjas_locacion.php",
        data: {
          x1: JSON.stringify(window.listPickerFavorito.selected()),
          x2: $('#cod_granja').val(),
          x3: $('#cod_localizacion').val(),
          x4: dataUsuarioFavoritos,
        },
        error: (error) => {
          grl_mensaje('Something went wrong, please contact the administrator. ', '', 'warning');
          console.log(error);
        },
        success: (res) => {
          console.log({
            resGuardarFavoritos: res
          });
          if (res[0] == '0') {
            // window.listPickerFavorito.reset();
            grl_mensaje('Bulk edit for favorites has been successfully added. ', '', 'success');
            grl_obtener_modulo(3);

            $('#modal_bulk_edit_favoritos').modal('hide');
          } else {
            grl_mensaje('Error registering favorites.', '', 'danger');
          }

        }
      });
    }
  }

  var processFarmLocationModeFavorito = () => {

    if ($('#select_farm_bulk_edit_pay_rate_by_farm_location option:selected').val() == '-b') {
      $('#select_farm_bulk_edit_pay_rate_by_farm_location').selectpicker('setStyle', 'btn-info', 'remove');
      $('#select_farm_bulk_edit_pay_rate_by_farm_location').selectpicker('setStyle', 'btn-danger btn-sm');
      grl_mensaje('Please select the location. ', '', 'warning');
      return false;
    } else {
      $('#select_farm_bulk_edit_pay_rate_by_farm_location').selectpicker('setStyle', 'btn-danger', 'remove');
      $('#select_farm_bulk_edit_pay_rate_by_farm_location').selectpicker('setStyle', 'btn-info btn-sm');
    }

    if ($('#input_bulk_edit_pay_rate_by_farm_location').val() == "") {
      grl_mensaje('Please assign the pay rate. ', '', 'warning');
      $('#input_bulk_edit_pay_rate_by_farm_location').addClass('input-has-error');
      return false;
    } else {
      $('#input_bulk_edit_pay_rate_by_farm_location').removeClass('input-has-error');
    }

    let confirmed = confirm("Are you sure you want to proceed?");

    if (confirmed) {
      $.ajax({
        type: "POST",
        url: "mod_admin_usuarios/funciones/usu_actualizar_pay_rates_por_granja.php",
        data: {
          x1: $('#input_bulk_edit_pay_rate_by_farm_location').val(),
          x2: JSON.stringify($('#select_farm_bulk_edit_pay_rate_by_farm_location').val())
        },
        error: (error) => {
          grl_mensaje('Something went wrong, please contact the administrator. ', '', 'warning');
          console.log(error);
        },
        success: (res) => {
          window.listPicker.reset();
          $('#input_bulk_edit_pay_rate_by_farm_location').val("");
          grl_mensaje('Bulk edit pay rate for selected farm processed succesfully. ', '', 'success');
          grl_obtener_modulo(3);
          $('#modal_bulk_edit_pay_rate').modal('hide');
        }
      });
    }

  }

  var processFavoritoMode = () => {



    if ($('#select_category_bulk_edit_pay_rate option:selected').val() == '-b') {
      $('#select_category_bulk_edit_pay_rate').selectpicker('setStyle', 'btn-info', 'remove');
      $('#select_category_bulk_edit_pay_rate').selectpicker('setStyle', 'btn-danger btn-sm');
      grl_mensaje('Please select the category. ', '', 'warning');
      return false;
    } else {
      $('#select_category_bulk_edit_pay_rate').selectpicker('setStyle', 'btn-danger', 'remove');
      $('#select_category_bulk_edit_pay_rate').selectpicker('setStyle', 'btn-info btn-sm');
    }

    if ($('#input_pay_rate_by_category').val() == "") {
      grl_mensaje('Please assign the pay rate. ', '', 'warning');
      $('#input_pay_rate_by_category').addClass('input-has-error');
      return false;
    } else {
      $('#input_pay_rate_by_category').removeClass('input-has-error');
    }

    let confirmed = confirm("Are you sure you want to proceed?");

    if (confirmed) {
      $.ajax({
        type: "POST",
        url: "mod_admin_usuarios/funciones/usu_actualizar_pay_rates_por_categoria.php",
        data: {
          x1: $('#input_pay_rate_by_category').val(),
          x2: JSON.stringify($('#select_category_bulk_edit_pay_rate').val())
        },
        error: (error) => {
          grl_mensaje('Something went wrong, please contact the administrator. ', '', 'warning');
          console.log(error);
        },
        success: (res) => {
          window.listPickerFavorito.reset();
          $('#input_pay_rate_by_category').val("");
          grl_mensaje('Bulk edit pay rate for selected category processed succesfully. ', '', 'success');
          grl_obtener_modulo(3);
          $('#modal_bulk_edit_pay_rate').modal('hide');
        }
      });
    }

  }

  $("#excel").click(function() {
    var url = "../../mod_admin_usuarios/reportes/usu_listado_usuarios_excel.php";
    $(location).attr('href', url);
  });
  $("#btn_bulk_edit_pay_rate").click(function() {
    // bulkEditFavoritoModalFunctionality();
    $('#list-picker-wrapper_favorito').empty();

    bulkEditPayRateModalFunctionality();
  });

  $('#cod_granja').change(function(event) {
    document.getElementById('list-picker-wrapper').innerHTML = '';
    farm_constructor_listado_localizaciones_en_granja();

  });
  $('#btn_guardar').click(function(event) {
    document.getElementById('list-picker-wrapper').innerHTML = '';

    /* Act on the event */
    var error = 0;
    // $(".input.requerido").map(function() {
    // 	if (!$(this).val()) {
    // 		error = 1;
    // 		$(this).parent('div').addClass('has-error');
    // 		return false;
    // 	} else {
    // 		$(this).parent('div').removeClass('has-error');
    // 	}
    // });
    $(".selectpicker.requerido").map(function() {
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
      bulkEditFavoritoModalFunctionality();
      usu_listado_usuarios_favorito_granjas_locaciones();

    } else {
      favoritosCargados = false;

      grl_mensaje('You must fill out all marked fields', 'Please check', 'warning');
      $('#list-picker-wrapper_favorito').empty();
    }
  });

  /*
   * Cargar el listado de personas marcados como favoritos
   */
  function usu_listado_usuarios_favorito_granjas_locaciones() {
    $.ajax({
      url: site_url + 'mod_admin_usuarios/funciones/usu_listado_usuarios_favoritos.php',
      type: 'POST',
      data: {
        x1: $('#cod_granja').val(),
        x2: $('#cod_localizacion').val()
      },
      dataType: 'json',
      success: function(dataUsuario) {
        if (Array.isArray(dataUsuario)) {
          dataUsuarioFavoritos = dataUsuario;
          dataUsuario.forEach(function(usuario) {
            if (usuario.hasOwnProperty('cod_usuario')) {
              if (!window.listPickerFavorito.selected().some(e => e.id == usuario.cod_usuario)) {
                // window.listPickerFavorito.addItem({
                //   id: usuario.cod_usuario,
                //   name: usuario.nombre
                // });
                $('input[value="' + usuario.cod_usuario + '"]').trigger('click');
              }
            }
          });
        }

      }
    }).fail(function(error) {
      grl_mensaje(JSON.stringify(error), 'please try later', 'danger');
    }).always(function() {});
  }
</script>
<style>
  div.circle-avatar {
    /* make it responsive */
    max-width: 100%;
    width: 100%;
    height: auto;
    display: block;
    /* div height to be the same as width*/
    padding-top: 100%;

    /* make it a circle */
    border-radius: 50%;

    /* Centering on image`s center*/
    background-position-y: center;
    background-position-x: center;
    background-repeat: no-repeat;

    /* it makes the clue thing, takes smaller dimension to fill div */
    background-size: cover;

    /* it is optional, for making this div centered in parent*/
    margin: 0 auto;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
  }

  .row {
    padding: 0 10px;
  }

  .panel_cabecera div {
    margin-top: -18px;
    font-size: 15px;
  }

  .panel_cabecera div span {
    margin-left: 5px;
  }

  .panel_cuerpo {
    display: none;
  }

  #dev-table {
    font-size: 12px;
  }

  .resumen-bitacora {
    margin-right: 10px;
    display: inline-block;
    padding: 2px;
    border-radius: 5px;
  }

  .badge.bitacora {
    background: rgb(30, 30, 30);
  }

  .resumen-bitacora.nivel-3 {
    background: rgb(205, 244, 205) !important;
  }

  .resumen-bitacora.nivel-4 {
    background: rgb(255, 150, 150) !important;
  }

  .badge.bitacora.nivel-3 {
    background: rgb(76, 174, 76);
  }

  .badge.bitacora.nivel-4 {
    background: rgb(255, 105, 105);
  }

  .nivel-3 {
    border-right-color: rgb(76, 174, 76);
  }

  .nivel-4 {
    border-right-color: rgb(255, 150, 150);
  }

  #dev-table>tbody tr {
    cursor: pointer;
  }

  #dev-table>tbody>tr>td>div {
    display: flex;
    align-items: center;
    height: 80px;
  }

  .bubble-timeline {
    display: inline-block;
    background-color: rgb(77, 178, 208);
    border-radius: 50%;
    line-height: 2em;
    max-width: 100px;
    /*border: 2px solid rgb(255, 255, 255);*/
    padding: 2px;
    margin-left: 10px;
    margin-bottom: 10px;
    margin-top: 10px;
  }

  .icon-timeline-container {
    background-color: rgb(77, 178, 208);
    border: 3px solid rgb(255, 255, 255);
    border-radius: 50%;
    display: inline-block;
  }

  .img-timeline {
    width: 100%;
    /*background-color: rgb(77, 178, 208);
        border: 3px solid rgb(255, 255, 255);*/
    border-radius: 50%;
    display: inline-block;
  }
</style>

<body>
  <div id="overlay_loading"></div>


  <div class="busqueda_contenedor">
    <div class="row" style="overflow:auto; min-width:100px;">
      <div class="col-sm-12">
        <div class="panel panel-primary" style="overflow:auto;">
          <div class="panel-heading panel_cabecera">
            <h3 class="panel-title translate" data-traducir_english="Users List" data-traducir_spanish="Lista de Usuarios">Listado de Usuarios</h3>
            <div class="pull-right">
              <span data-container="body" title="" data-toggle="tooltip" class="clickable filter" data-original-title="Realizar busqueda">
                <i class="fa fa-search"></i>
              </span>
            </div>
          </div>
          <div class="panel-body panel_cuerpo input-group-sm">
            <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Busqueda" />
          </div>
          <div style="overflow-x:auto;">
            <table class="table table-condensed display" id="dev-table">
              <thead>
                <tr class="active info">
                  <th width="10%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>
                  <th width="25%" class="translate" data-traducir_english="Name" data-traducir_spanish="Nombre">Nombre</th>
                  <th width="20%" class="translate" data-traducir_english="Email" data-traducir_spanish="Correo">Correo</th>
                  <th width="10%">Pin</th>
                  <th width="10%">QCPin</th>
                  <!-- <th width="10%" class="translate" data-traducir_english="Company Name" data-traducir_spanish="Gerencia">Gerencia</th> -->
                  <!-- <th width="10%" class="translate" data-traducir_english="Position" data-traducir_spanish="Cargo">Cargo</th> -->
                  <th width="10%" class="translate" data-traducir_english="Category" data-traducir_spanish="Categoría">Category</th>
                  <th width="10%" class="translate" data-traducir_english="Active" data-traducir_spanish="Activo">Activo</th>
                </tr>
              </thead>
              <tbody>

                <?PHP
                $cuerpo_tabla = "";
                if (count($USUARIOS) > 0) {
                  $correlativo = 1;
                  foreach ($USUARIOS as $USUARIO) {
                    $bloqueado = "";
                    if ($USUARIO['pass_pending'] == 3) {
                      $bloqueado = "Bloqueado";
                    }
                    $cuerpo_tabla .= '<tr >
																<td style="display:none;">' . utf8_encode($USUARIO['cod_usuario']) . '</td>
																																<td>
																																		<div>
                                                                      <p class="bubble-timeline" style="background-color: ' . $p_color2 . ' !important">
                                                                          <span class="icon-timeline-container" style="background-color: ' . $p_color2 . ' !important">
                                                                              <img src="' . utf8_encode($USUARIO['fotografia']) . '" onerror="this.src=\'../../libs/imgs/usuario.jpg\';" class="img-circle"  width="60" height="60">
                                                                          </span>
                                                                      </p>
                                                                    </div>
																																</td>
                                                                <td>
                                                                  <div style="display:flex;flex-direction:column;justify-content:center;align-items:flex-start">
                                                                    <span style="display:block">' . utf8_encode($USUARIO['nombre']) . '</span>
                                                                    <span style="display:block;color:gray;font-weight:300">Pay rate: $' . utf8_encode($USUARIO['pay_rate']) . '</span>
                                                                  </div>
                                                                </td>
                                                                <td><div><span>' . utf8_encode($USUARIO['email']) . '</span></div></td>
                                                                <td><div><span>' . utf8_encode($USUARIO['pin']) . '</span></div></td>
                                                                <td><div><span>' . utf8_encode($USUARIO['qcpin']) . '</span></div></td>
                                                                <!--<td><div><span>' . utf8_encode($USUARIO['gerencia']) . '</span></div></td>-->
                                                                <!--<td><div><span>' . utf8_encode($USUARIO['cargo']) . '</span></div></td>-->
                                                                <td><div><span>' . utf8_encode(($USUARIO['categoria']) . '</span></div></td>
																																<td><div><span>' . utf8_encode($USUARIO['activo'] == 1 ? '<i class="fa fa-circle" aria-hidden="true" style="color: green"></i><span class="badge">' . $bloqueado . '</span>' : '<i class="fa fa-circle" aria-hidden="true" style="color: red"></i><span class="badge">' . $bloqueado . '</span>')) . '</span></div></td>
                                                              </tr>';
                    $correlativo++;
                  }
                  echo "<script type='text/javascript'>$('#modal_loading').modal('hide');</script>";
                } else { //Si no hay registros informará al usuario
                  $cuerpo_tabla .= '<tr>
																<td align="center" colspan="6">No records</td>
                                                          </tr>';
                }
                echo $cuerpo_tabla;
                ?>
              </tbody>
            </table>
          </div>
        </div><!--/panel panel-primary -->
      </div><!-- /col-12 -->
    </div>

    <div class="panel-footer-actions text-right smooth-transition actions-container-is-closed" id="panel_footer_actions">
      <div class="close-actions-container">
        <button class="btn btn-xs btn-close-actions" id="btn_close_actions">
          <i class="fa fa-chevron-circle-down fa-lg"></i>
        </button>
      </div>
      <div class="row actions">
        <div class="col-xs-10 col-md-10 col-sm-10 nopadding smooth-transition" id="div_acciones">
          <button class="btn btn-sm btn-primary btn-open-actions btn-fullwidth main-actions smooth-transition" type="button" id="btn_open_actions" name="btn_open_actions">
            <i class="fa fa-ellipsis-v"></i>
          </button>
          <div class="col-md-11 col-sm-11 col-xs-10 nopadding text-center smooth-transition" id="div_total_documentos" style="margin-top:5px!important;">
            <?php
            if (count($TOTALES)) {
              echo '<span class="resumen-bitacora nivel-3"><span class="translate" data-traducir_english="Active" data-traducir_spanish="Activos">Activos</span><span id="activos" class="badge bitacora nivel-3">' . $TOTALES[0]['usuarios_activos'] . ' </span></span>';
              echo '<span class="resumen-bitacora nivel-4"><span class="translate" data-traducir_english="Inactive" data-traducir_spanish="Inactivos">Inactivos</span><span id="inactivos" class="badge bitacora nivel-4">' . $TOTALES[0]['usuarios_no_activos'] . ' </span></span>';
              echo '<span class="resumen-bitacora"><span>Total</span><span id="total" class="badge bitacora">' . ($TOTALES[0]['usuarios_no_activos'] + $TOTALES[0]['usuarios_activos']) . ' </span></span>';
            }
            ?>
          </div>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-2 text-center nopadding smooth-transition">
          <button id="btn_bulk_edit_favorito" name="btn_bulk_edit_favorito" class="btn btn-sm btn-warning main-actions smooth-transition" type="button" data-toggle="modal" data-target="#modal_bulk_edit_favoritos"><i class="fa-solid fa-list"></i> Favorites</button>
          <button id="btn_bulk_edit_pay_rate" name="btn_bulk_edit_pay_rate" class="btn btn-sm btn-success main-actions smooth-transition" type="button" data-toggle="modal" data-target="#modal_bulk_edit_pay_rate"><i class="fa-solid fa-list"></i> Pay Rate</button>
          <!-- <button id="excel" name="excel" class="btn btn-sm btn-info main-actions smooth-transition" type="button" data-loading-text="Convirtiendo...">Excel</button> -->
        </div>

      </div>
    </div> <!-- panel-footer -->
  </div> <!-- /busqueda_contenedor -->

  <!-- PAY RATE MODAL -->
  <div class="modal" id="modal_bulk_edit_pay_rate" role="dialog" aria-labelledby="modal_bulk_edit_pay_rate_label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="modal_bulk_edit_pay_rate_label">Bulk edit Pay Rate to users</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              <label for="select_bulk_edit_pay_rate_mode">Bulk edit by</label>
              <select class="selectpicker" name="select_bulk_edit_pay_rate_mode" id="select_bulk_edit_pay_rate_mode">
                <option value="0" selected>Selected users</option>
                <option value="1">Farm</option>
                <option value="2">Category</option>
              </select>
            </div>
          </div>
          <div id="modal_for_selected_users_mode_wrapper">
            <div id="list-picker-wrapper" style="margin-bottom:20px"></div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group input-group-sm">
                  <label for="input_bulk_edit_pay_rate_for_selected_users" class="translate" data-traducir_english="Pay Rate to assign" data-traducir_spanish="Ratio de pago a asignar">Pay Rate to assign</label>
                  <input type="text" class="form-control input" id="input_bulk_edit_pay_rate_for_selected_users" placeholder="0.00">
                </div>
              </div>
            </div>
          </div>
          <div id="modal_by_farm_and_location_mode_wrapper">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group input-group-sm">
                  <label for="">Farm</label>
                  <select name="select_farm_bulk_edit_pay_rate_by_farm_location" id="select_farm_bulk_edit_pay_rate_by_farm_location" class="selectpicker" multiple>
                    <option value="0" disabled>--SELECT--</option>
                    <?php foreach ($GRANJAS as $granja): ?>
                      <option value="<?= $granja['cod_farms']; ?>"><?= $granja['farm']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group input-group-sm">
                  <label for="input_bulk_edit_pay_rate_by_farm_location" class="translate" data-traducir_english="Pay Rate to assign" data-traducir_spanish="Ratio de pago a asignar">Pay Rate to assign</label>
                  <input type="text" class="form-control input" id="input_bulk_edit_pay_rate_by_farm_location" placeholder="0.00">
                </div>
              </div>
            </div>
          </div>
          <div id="modal_by_category_mode_wrapper">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group input-group-sm">
                  <label for="select_category_bulk_edit_pay_rate">Category</label>
                  <select name="select_category_bulk_edit_pay_rate" id="select_category_bulk_edit_pay_rate" class="selectpicker" multiple>
                    <option value="-b" disabled>--SELECT--</option>
                    <option value="0">Standard</option>
                    <option value="1">Veteran</option>
                    <option value="2">H2A</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group input-group-sm">
                  <label for="input_pay_rate_by_category" class="translate" data-traducir_english="Pay Rate to assign" data-traducir_spanish="Ratio de pago a asignar">Pay Rate to assign</label>
                  <input type="text" class="form-control input" id="input_pay_rate_by_category" placeholder="0.00">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div id="modal_for_selected_users_mode_footer_wrapper">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="btn_process_selected_users_mode">Process bulk edit</button>
          </div>
          <div id="modal_by_farm_and_location_mode_footer_wrapper">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="btn_process_farm_location_mode">Process bulk edit</button>
          </div>
          <div id="modal_by_category_mode_wrapper_footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="btn_process_category_mode">Process bulk edit</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- PAY RATE MODAL -->

  <!-- FAVORITOS MODAL -->
  <div class="modal" id="modal_bulk_edit_favoritos" role="dialog" aria-labelledby="modal_bulk_edit_favorito_label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="modal_bulk_edit_favorito_label">Bulk edit Favorite</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <!-- <div class="col-md-4">
              <label for="select_bulk_edit_favorito_mode">Bulk edit by</label>
              <select class="selectpicker" name="select_bulk_edit_favorito_mode" id="select_bulk_edit_favorito_mode">
                <option value="0" selected>Selected users</option>
                <option value="1">Farm</option>
                <option value="2">Category</option>
              </select>
            </div> -->
            <div class="col-md-4">
              <div class="form-group input-group-sm">
                <label for="cod_granja" class="translate" data-traducir_english="Farm" data-traducir_spanish="Granja">Farm</label>
                <select class="selectpicker show-menu-arrow requerido" multiple="multiple" data-actions-box="true" data-live-search="true" title="Select" id="cod_granja" name="cod_granja">
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group input-group-sm">
                <label for="cod_localizacion" class="translate" data-traducir_english="Location" data-traducir_spanish="Localización">Localización</label>
                <select class="selectpicker show-menu-arrow requerido" multiple="multiple" data-actions-box="true" data-live-search="true" title="Select" id="cod_localizacion" name="cod_localizacion">
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <button class="btn btn-sm btn-primary translate" style="margin-top: 27px;" data-traducir_english="Load" data-traducir_spanish="Cargar" type="button" id="btn_guardar">Load</button>
            </div>
          </div>
          <div id="modal_for_selected_users_mode_wrapper_favorito">
            <div id="list-picker-wrapper_favorito" style="margin-bottom:20px"></div>

          </div>

        </div>
        <div class="modal-footer">
          <div id="modal_for_selected_users_mode_footer_wrapper_favorito">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="btn_process_selected_users_mode_favorito">Process bulk edit</button>
          </div>
          <!-- <div id="modal_by_farm_and_location_mode_footer_wrapper_favorito">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="btn_process_farm_location_mode_favorito">Process bulk edit</button>
          </div>
          <div id="modal_by_category_mode_wrapper_footer_favorito">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="btn_process_category_mode">Process bulk edit</button>
          </div> -->
        </div>
      </div>
    </div>
  </div>
  <!-- FAVORITOS MODAL -->


</body>

</html>