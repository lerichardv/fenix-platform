<?php
/*
 * 	Listado general de todas las semillas registradas
 * 	@author 		Edwin Olivera
 * 	@date 			2023-09-12
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


$cod_info_empresa = "";
$cod_estados = "";
$cod_semillas = "";
$ticket = "";
$flag_completado = 0;

// INICIALIZANDO LAS FECHAS
$fecha_actual = "";
$fechaInicial = "";
$fecha_actual_2 = "";

$fecha_actual = date('Y-m-d 23:59:00');
$fecha_actual = strtotime($fecha_actual);
$fecha_actual = date('Y-m-d 00:00:00', strtotime("+7 day", $fecha_actual));

$fecha_actual_2 = date('Y-m-d 23:59:00');
$fecha_hace_30_dias = date('Y-m-d 00:00:00', strtotime('-30 days', strtotime($fecha_actual_2)));

$fechaInicial = $fecha_hace_30_dias;
$fechaFinal = $fecha_actual;
// ****************************************************

if (isset($_POST['fecha_inicial']) && isset($_POST['fecha_final'])) {
  $fechaInicial = $_POST['fecha_inicial'] . " 00:00:00";
  $fechaFinal = $_POST['fecha_final'] . " 23:59:00";
}

if (isset($_POST['cod_estados']) && $_POST['cod_estados'] != '') {
  $cod_estados = $_POST['cod_estados'];
  $cod_estados = implode(',', $cod_estados);
  // echo $cod_estados;
}

if (isset($_POST['cod_semillas']) && $_POST['cod_semillas'] != '') {
  $cod_semillas = $_POST['cod_semillas'];
  $cod_semillas = implode(',', $cod_semillas);
}

if (isset($_POST['ticket']) && $_POST['ticket'] != '') {
  $ticket = $_POST['ticket'];
  $ticket = implode(',', $ticket);
}

if (isset($_POST['flag_completado']) && $_POST['flag_completado'] != '') {
  $flag_completado = $_POST['flag_completado'];
}

if (isset($_POST['cod_sembradores']) && $_POST['cod_sembradores'] != '') {
  $cod_info_empresa = $_POST['cod_sembradores'];
  $cod_info_empresa = implode(',', $cod_info_empresa);
}


$TRASPLANTES = [];
$TRASPLANTES = $DB_INV->inv_listado_trasplante(
  $fechaInicial,
  $fechaFinal,
  $cod_estados,
  $cod_semillas,
  $flag_completado,
  $ticket
);

// var_dump($TRASPLANTES);
?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>Listado de trasplantes</title>

  <style>
    /*  Los estilos se colocan aquí para forzar su aplicación
        unicamente en esta página
         */
    .btnCantidadSemillas,
    .btnEdad {
      width:
        100% !important;
      text-align:
        right;
    }

    .input_cantidad_semillas {
      text-align:
        right;
    }

    .trasplante_completado {
      background-color: #aff09c !important;
    }

    .trasplante_fecha_caduca {
      background-color: #f2b0b8 !important;
    }

    .trasplante_normal_1 {
      background-color: #f2f2f2 !important;
    }

    .trasplante_normal_2 {
      background-color: #e0e0e0 !important;
    }
    .elemento_seleccionable {
      cursor: pointer;
    }
  </style>
</head>

<script type="text/javascript">
  jQuery.ajaxSetup({
    async: false
  });
  grl_overlay_loading('');

  $(document).ready(function() {
    $(".btn-group.bootstrap-select.show-tick.show-menu-arrow.requerido").remove();
    $(window).scroll(function() {
      // Obtener la posición actual de desplazamiento vertical
      var scrollPos = $(window).scrollTop();

      // Verificar si el usuario ha hecho scroll hacia abajo (puedes ajustar el valor según tus necesidades)
      if (scrollPos > 1) {
        $(".requerido").removeClass("open");
      }
    });
    $('.selectpicker').selectpicker({
      dropupAuto: 'true',
      container: 'body',
      size: '5',
      width: '100%',
      style: 'btn-sm btn-info',
      tickIcon: 'fa fa-check'
    });
    //Habilita los selects para mobile
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
      $('.selectpicker').selectpicker('mobile');
    }
    // Obtiene la fecha actual
    var fechaTreitaDiasPrevios = new Date();
    var fechaSieteDias = new Date();
    // Resta 30 días a la fecha actual
    fechaTreitaDiasPrevios.setDate(fechaTreitaDiasPrevios.getDate() - 30);
    fechaSieteDias.setDate(fechaSieteDias.getDate() + 7);
    $('#div_fecha_inicial').datetimepicker({
      defaultDate: fechaTreitaDiasPrevios,
      format: 'YYYY-MM-DD',
      icons: {
        time: "fas fa-clock",
        date: "fa fa-calendar",
        up: "fa fa-arrow-up",
        down: "fa fa-arrow-down",
        previous: 'fa fa-arrow-left',
        next: 'fa fa-arrow-right',
      }
    }).on('dp.hide', function(e) {
      $('#div_fecha_final').data("DateTimePicker").minDate(e.date);
    });
    $('#div_fecha_final').datetimepicker({
      minDate: fechaTreitaDiasPrevios,
      defaultDate: fechaSieteDias,
      format: 'YYYY-MM-DD',
      icons: {
        time: "fas fa-clock",
        date: "fa fa-calendar",
        up: "fa fa-arrow-up",
        down: "fa fa-arrow-down",
        previous: 'fa fa-arrow-left',
        next: 'fa fa-arrow-right',
      }
    }).on('dp.hide', function(e) {
      $('#div_fecha_inicial').data("DateTimePicker").maxDate(e.date);
    });

    $('#btn_filtrar').click(function(event) {
      console.log({
        cod_estados: $('#cod_estados').val()
      })
      $.ajax({
          url: 'mod_inventario/ui/inv_listado_trasplantes.php',
          type: 'POST',
          dataType: 'html',
          data: {
            fecha_inicial: $('#fecha_inicial').val(),
            fecha_final: $('#fecha_final').val(),
            cod_semillas: $('#cod_semillas').val(),
            cod_estados: $('#cod_estados').val(),
            flag_completado: $('#flag_completado').val(),
            ticket: $('#ticket').val()
          },
        })
        .done(function(data) {
          $('#div_cuerpo_menu').empty();
          $('#div_cuerpo_menu').html(data);
        })
        .fail(function() {
          console.log("error al filtrar por fecha la busqueda de semillas");
        });
    });

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
    conf_constructor_listado_estados_de_plantacion();
    inv_constructor_listado_semillas_activas('cod_semillas');
    inv_constructor_listado_tickets();

    // $('#dev-table').DataTable({
    // 	"pageLength": 50,
    // 	ordering: false,
    // 	"dom": 'Bfrtip',
    // 	"buttons": []
    // });


    /*$('.checkbox').change(function(event) {
        /* Act on the event 
        event.preventDefault();
        event.stopPropagation();
        console.log($(this).data('id'));
        inv_cambiar_estado_completado_trasplante($(this).data('id'), ($(this).attr('checked') ? 0 : 1));
    });*/

    <?php
    if (isset($_POST['fecha_inicial']) && isset($_POST['fecha_final'])) {
    ?>
      $('#fecha_inicial').val('<?php echo $_POST['fecha_inicial']; ?>');
      $('#fecha_final').val('<?php echo $_POST['fecha_final']; ?>');
    <?php
    }
    ?>

    <?php
    if (isset($_POST['cod_estados']) && $_POST['cod_estados'] != '') {
    ?>
      $('#cod_estados').val([<?php echo implode(",", $_POST['cod_estados']); ?>]);
      $('.selectpicker').selectpicker('refresh');

    <?php
    }
    ?>

    <?php
    if (isset($_POST['cod_semillas']) && $_POST['cod_semillas'] != '') {
    ?>
      $('#cod_semillas').val([<?php echo implode(',', $_POST['cod_semillas']); ?>]);
      $('.selectpicker_semillas').selectpicker('refresh');

    <?php
    }
    ?>

    <?php
    if (isset($_POST['flag_completado']) && $_POST['flag_completado'] != '') {
    ?>
      $('#flag_completado').val('<?php echo $_POST['flag_completado']; ?>');
      $('.selectpicker').selectpicker('refresh');
    <?php
    }
    ?>

    $('#modal_loading').modal('hide');
    jQuery.ajaxSetup({
      async: true
    });


  });

  /*Funcion que permite cambiar estado activo/des de todos los transplantes hijos
   */
  function cambiar_estado_trasplante(fecha_inicial, fecha_final, cod_inventario, item, flag, comp, cod_plantacion, cod_transpante) {

    $.ajax({
        url: 'mod_inventario/funciones/inv_cambiar_estado_trasplante_todo.php',
        type: 'POST',
        data: {
          x1: fecha_inicial,
          x2: fecha_final,
          x3: cod_inventario,
          x4: item,
          x5: flag,
          x6: comp,
          x7: cod_plantacion
        },
      })
      .done(function(data) {
        var info = data.split("|");
        var mensaje = info[1];
        info[0] == 1 ? tipo = 'danger' : tipo = 'success';
        grl_mensaje('', mensaje, tipo);
        console.log(`Nuevo estado del checkbox padre: ${comp ? "activo" : "inactivo"}`);
        // inv_vista_lista_transplante(0);
        // Cambiamos los checkbox al estado correspondiente y los listeners
        if (comp == 1) {
          $(`.checkbox_${cod_inventario}`).parent().parent().parent().removeClass('danger');
          $(`.checkbox_${cod_inventario}`).parent().parent().parent().addClass('success');
          $(`.checkbox_hijo_${cod_plantacion}`).prop('checked', "checked");
          $(`.checkbox_hijo_${cod_plantacion}`).parent().parent().parent().removeClass('danger');
          $(`.checkbox_hijo_${cod_plantacion}`).parent().parent().parent().addClass('success');
        } else {
          $(`.checkbox_${cod_inventario}`).parent().parent().parent().removeClass('success');
          $(`.checkbox_${cod_inventario}`).parent().parent().parent().addClass('danger');
          $(`.checkbox_hijo_${cod_plantacion}`).prop('checked', '');
          $(`.checkbox_hijo_${cod_plantacion}`).parent().parent().parent().removeClass('success');
          $(`.checkbox_hijo_${cod_plantacion}`).parent().parent().parent().addClass('danger');
        }
        $(`.checkbox_${cod_inventario}`).removeAttr('onclick').off('click').on('click', () => {
          cambiar_estado_trasplante(fecha_inicial, fecha_final, cod_inventario, item, flag, comp == 1 ? 0 : 1, cod_plantacion);
        });
        console.log(`Nuevo estado de los checkbox hijos: ${comp ? "activo" : "inactivo"}`);
        $(`.checkbox_hijo_${cod_plantacion}`).each((index, chk) => {
          // Get the onclick attribute value
          const onclickAttr = $(chk).attr('onclick');

          // Remove the function name and parentheses
          const paramsString = onclickAttr.substring(
            onclickAttr.indexOf('(') + 1,
            onclickAttr.lastIndexOf(')')
          );

          // Split the parameters by comma and trim any whitespace
          const params = paramsString.split(',').map(param => param.trim());

          // Get the parameters
          const paramCodTransplante = params[0];
          // const completed = params[1];
          const paramCodPlantacion = params[2];

          $(chk)
            .removeAttr('onclick')
            .attr('onclick', `inv_cambiar_estado_completado_trasplante(${paramCodTransplante}, ${comp == 1 ? 0 : 1}, ${paramCodPlantacion})`);
        })
        jQuery.ajaxSetup({
          async: true
        });
      })
      .fail(function() {
        grl_mensaje('An inconvenience has occurred', 'please try later', 'danger');
      })
      .always(function() {});
    return false;

  };
</script>

<body>
  <div id="overlay_loading"></div>
  <div id="message_box"></div>
  <div class="panel panel-default">
    <div class="panel-body">
      <div class="col-md-12">
        <div class="panel-header">
          <h1 class="translate" data-traducir_english="Transplant List" data-traducir_spanish="Lista de trasplantes">
            Lista de trasplantes</h1>
        </div>
      </div>
      <div class="col-md-12">
        <!-- Fila #1 -->
        <div class="row">
          <div class="col-md-3 form-group input-group-sm">
            <label for="fecha_sumar_restar" class="translate" data-traducir_english="Initial date"
              data-traducir_spanish="Fecha inicial">Fecha inicial</label>
            <div class='input-group input-group-sm fecha-planeada' id='div_fecha_inicial'>
              <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
              </span>
              <input type='text' class="form-control" id="fecha_inicial" />
            </div>
          </div>
          <div class="col-md-3 form-group input-group-sm">
            <label for="fecha_sumar_restar" class="translate" data-traducir_english="Final date"
              data-traducir_spanish="Fecha final">Fecha final</label>
            <div class='input-group input-group-sm fecha-planeada' id='div_fecha_final'>
              <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
              </span>
              <input type='text' class="form-control" id="fecha_final" />
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group input-group-sm">
              <label for="ticket" class="translate" data-traducir_english="Ticket"
                data-traducir_spanish="Ticket">Ticket</label>
              <select multiple="multiple" data-actions-box="true" data-live-search="true"
                class="selectpicker show-menu-arrow requerido" title="Select" id="ticket" name="ticket">
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group input-group-sm">
              <label for="cod_semillas" class="translate" data-traducir_english="Seed"
                data-traducir_spanish="Semilla">Semilla</label>
              <select multiple="multiple" data-actions-box="true" data-live-search="true"
                class="selectpicker show-menu-arrow requerido" title="Select" id="cod_semillas" name="cod_semillas">
              </select>
            </div>
          </div>

        </div>
      </div>
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-3">
            <div class="form-group input-group-sm">
              <label for="cod_estados" class="translate" data-traducir_english="State"
                data-traducir_spanish="Estado">Estado</label>
              <select multiple="multiple" data-actions-box="true" class="selectpicker show-menu-arrow requerido"
                title="Select" id="cod_estados" name="cod_estados">
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group input-group-sm">
              <label for="flag_completado" class="translate" data-traducir_english="Completion status"
                data-traducir_spanish="Estado de completacion">Estado de completacion</label>
              <select class="selectpicker show-menu-arrow" title="Select" id="flag_completado" name="flag_completado">
                <option value="0" selected>Not completed</option>
                <option value="1">Completed</option>
                <option value="2">Both</option>
              </select>
            </div>
          </div>

          <div class="col-md-2 " style="margin-top: 25px;">
            <button type="button" id="btn_filtrar" class="btn btn-sm btn-primary translate"
              data-traducir_english="Filter" data-traducir_spanish="Filtrar">Filtrar</button>
          </div>

        </div>

        <hr>
      </div>
    </div>



    <div class="busqueda_contenedor " style="margin-left: 10px; margin-right: 10px;">
      <div class="row" style="min-width:100px;">
        <div class="col-sm-12">
          <div class="panel panel-primary" id="panel_itemschecklist">
            <div class="panel-heading btncollapsepaso panel_cabecera" id="a_itemschecklist" role="button"
              data-toggle="collapse" href="#collapseitemschecklist" data-target="#collapseitemschecklist"
              aria-expanded="true" data-parent="#panel_itemschecklist" aria-controls="collapseitemschecklist">
              <h3 class="panel-title translate" data-traducir_english="Transplanting list"
                data-traducir_spanish="Lista de Transplantes">Transplanting list</h3>
              <div class="pull-right">
                <span class="clickable filter" data-toggle="tooltip" title="Search" data-container="body">
                  <i class="fa fa-search"></i>
                </span>
              </div>
            </div>
            <div class="panel-collapse collapse" id="collapseitemschecklist" role="tabpanel"
              aria-labelledby="collapseitemschecklist">
              <div class="panel-body panel_cuerpo">
                <input type="text" class="form-control" id="tabla_itemschecklist-filter" data-action="filter"
                  data-filters="#tabla_itemschecklist" placeholder=Search />
              </div>
            </div>
            <div class="responsive_table_container">
              <table class="table display row-border table-responsive table-hover table-condensed"
                id="tabla_itemschecklist">
                <thead>
                  <tr class="active info">
                    <th width="7%" class="translate" data-traducir_english="Order" data-traducir_spanish="Orden">Order
                    </th>
                    <th width="5%" class="translate" data-traducir_english="Item" data-traducir_spanish="Item">Item</th>
                    <th width="5%" class="translate" data-traducir_english="State" data-traducir_spanish="Estado">State
                    </th>
                    <th width="25%" class="translate" data-traducir_english="Seed" data-traducir_spanish="Semilla">Seed
                    </th>
                    <th width="8%" class="translate" data-traducir_english="Expected" data-traducir_spanish="Esperada">
                      Expected</th>
                    <th width="8%" class="translate" data-traducir_english="Last Delivery"
                      data-traducir_spanish="Ultima Entrega">Last Delivery</th>
                    <th width="10%" class="translate" data-traducir_english="Actual" data-traducir_spanish="Actual">
                      Actual</th>
                    <th width="5%" class="translate" data-traducir_english="Germ" data-traducir_spanish="Germ">Germ</th>
                    <th width="10%" class="translate" data-traducir_english="Expected Plants"
                      data-traducir_spanish="Plantas esperadas">Expected Plants</th>
                    <th width="10%" class="translate" data-traducir_english="Balance" data-traducir_spanish="Balance">
                      Balance</th>
                    <th width="5%" class="translate" data-traducir_english="" data-traducir_spanish=""></th>
                  </tr>
                </thead>
                <tbody id="cuerpo_tabla">
                  <?php

                  if (count($TRASPLANTES)) {
                    $numero_orden = $TRASPLANTES[0]['NUMERO_ORDEN'];
                    $cod_seed = $TRASPLANTES[0]['COD_SEED'];
                    $item = $TRASPLANTES[0]['ITEM'];
                    $actual_plants = $TRASPLANTES[0]['ACTUAL_PLANTAS'];
                    $exp_plants = 0; //$TRASPLANTES[0]['EXP_PLANTS'];
                    $exp_delivery = $TRASPLANTES[0]['EXP_DELIVERY'];
                    $last_delivery = $TRASPLANTES[0]['LAST_DELIVERY'];
                    $comple = $TRASPLANTES[0]['COMP'];
                    $correlativo = 0;
                    $clases = array();
                    $cuerpo_tabla = '';
                    $i = 0;

                    foreach ($TRASPLANTES as $index => $trasplante) {
                      if ($numero_orden == $trasplante['NUMERO_ORDEN']) {
                        $numero_orden = $trasplante['NUMERO_ORDEN'];
                        $estado = $trasplante['ESTADO'];
                        $seed = $trasplante['SEED'];


                        //Conocer cual es la fecha de delivery mas alta
                        if (
                          $exp_delivery < $trasplante['EXP_DELIVERY'] &&
                          $numero_orden == $TRASPLANTES[$i]['NUMERO_ORDEN'] &&
                          $cod_seed == $TRASPLANTES[$i]['COD_SEED'] &&
                          $item == $TRASPLANTES[$i]['ITEM']
                        ) {
                          $exp_delivery = $trasplante['EXP_DELIVERY'];
                        } else {
                          $exp_delivery = $exp_delivery;
                        }

                        //Conocer cual es el line item mas alto
                        if (
                          $item < $trasplante['ITEM'] &&
                          $numero_orden == $TRASPLANTES[$i]['NUMERO_ORDEN'] &&
                          $cod_seed == $TRASPLANTES[$i]['COD_SEED'] &&
                          $item == $TRASPLANTES[$i]['ITEM']
                        ) {
                          $item = $trasplante['ITEM'];
                        } else {
                          $item = $item;
                        }

                        $plantas_actuales = ($trasplante['ACTUAL_PLANTAS'] == '' || $trasplante['ACTUAL_PLANTAS'] == null) ? 0 : $trasplante['ACTUAL_PLANTAS'];
                        //Sumar la cantidad de plantas actuales por orden y semilla
                        if (
                          $numero_orden == $TRASPLANTES[$i]['NUMERO_ORDEN'] &&
                          $cod_seed == $TRASPLANTES[$i]['COD_SEED'] &&
                          $item == $TRASPLANTES[$i]['ITEM']
                        ) {
                          $actual_plants = $actual_plants + $plantas_actuales;
                        } else {
                          $actual_plants = $actual_plants;
                        }
                        // echo var_dump($actual_plants);
                        /*Sumar la cantidad de plantas actuales por orden y semilla
                                                            if (
                                                                $numero_orden == $TRASPLANTES[$i]['NUMERO_ORDEN'] &&
                                                                $cod_seed == $TRASPLANTES[$i]['COD_SEED'] &&
                                                                $item == $TRASPLANTES[$i]['ITEM']
                                                            ) {
                                                                $exp_plants  = $exp_plants + $TRASPLANTES[$i]['EXP_PLANTS'];
                                                            } else {
                                                                $exp_plants = $exp_plants;
                                                            }*/

                        if (
                          $numero_orden == $trasplante['NUMERO_ORDEN'] &&
                          $cod_seed == $trasplante['COD_SEED'] &&
                          $item == $trasplante['ITEM']
                        ) {
                          //Cambio para que se pueda ver el exp aunque no haya transplante
                          // $exp_plants = $trasplante['EXP_PLANTS'];
                          $exp_plants = $trasplante['EXP_PLANTS2'];
                        }

                        $ultima_fecha = ($trasplante['LAST_DELIVERY'] == '' || $trasplante['LAST_DELIVERY'] == null) ? "1900-01-01" : $trasplante['LAST_DELIVERY'];

                        //Verifica la ultima fecha de entrega
                        if (
                          $numero_orden == $TRASPLANTES[$i]['NUMERO_ORDEN'] &&
                          $cod_seed == $TRASPLANTES[$i]['COD_SEED'] &&
                          $item == $TRASPLANTES[$i]['ITEM']
                        ) {
                          if ($last_delivery < $trasplante['LAST_DELIVERY']) {
                            $last_delivery = $trasplante['LAST_DELIVERY'];
                          } else {
                            $last_delivery = $last_delivery;
                          }
                        } else {
                          $last_delivery = $last_delivery;
                        }

                        if ($trasplante['EXP_PLANTS'] != 0) {
                          array_push($clases, $trasplante['COMP']);
                        }
                        // Verifica la siguiente posición
                        $next_index = $index + 1;
                        if (isset($TRASPLANTES[$next_index]) && $numero_orden == $TRASPLANTES[$next_index]['NUMERO_ORDEN']) {
                          if (
                            $cod_seed == $TRASPLANTES[$next_index]['COD_SEED'] &&
                            $item == $TRASPLANTES[$next_index]['ITEM']
                          ) {
                            continue;
                          } else {

                            if (in_array(0, $clases)) {
                              $clase = "";

                              if ($exp_delivery < date("Y-m-d")) {
                                $clase = "danger";
                              }
                              $COMP = 1;
                              $CHECK = '';
                            } else {
                              $clase = "success";
                              $COMP = 0;
                              $CHECK = $trasplante['COMP_PLANTACION'] == "1" ? 'checked="checked"' : "";
                            }


                            if ($exp_plants == 0) {
                              $exp_plants = "--";
                              $germ = "--";
                              $balance = $actual_plants - 0;
                            } else {
                              $germ = ($actual_plants / $exp_plants) * 100;
                              $germ = round($germ, 2);
                              $germ = $germ . "%";
                              $balance = $actual_plants - $exp_plants;
                              $exp_plants = number_format($exp_plants);
                            }

                            $actual_plants = number_format($actual_plants);
                            $balance = number_format($balance);

                            if ($actual_plants == 0) {
                              $actual_plants = "--";
                              $balance = "--";
                              $COMP = 0;
                              $CHECK = '';
                            }

                            if ($exp_plants == 0) {
                              $exp_plants = "--";
                            }
                            if ($last_delivery == '') {
                              $last_delivery = "--";
                              if ($exp_delivery < date("Y-m-d")) {
                                $clase = "danger";
                              } else {
                                $clase = "";
                              }
                            }

                            if ($trasplante['COD_TRANS'] == 0) {
                              $toggle = "";
                            } else {
                              $toggle = "";
                              $toggle = '<div class="material-switch pull-right">
																		<input class="checkbox checkbox_' . $trasplante['COD_SEED'] . '" 
																			onclick="cambiar_estado_trasplante(\'' . $fechaInicial . '\', \''
                                . $fechaFinal . '\', '
                                . $trasplante['COD_SEED'] . ', '
                                . $trasplante['ITEM'] . ', '
                                . $flag_completado . ', '
                                . ($trasplante['COMP_PLANTACION'] == '0' ? '1' : '0') . ', '
                                . $trasplante['cod_plantacion'] . ', '
                                . $trasplante['COD_TRANS'] . ')" 
																			id="checkbox_' . $trasplante['COD_TRANS'] . '" 
																			data-id="checkbox_' . $trasplante['COD_TRANS'] . '" 
																			name="checkbox_' . $trasplante['COD_TRANS'] . '" 
																			type="checkbox" ' . $CHECK . ' />
																		<label for="checkbox_' . $trasplante['COD_TRANS'] . '" class=""></label>
																	</div>';
                            }

                            $cuerpo_tabla .= '<tr class="' . $clase . '">
																			<td><a data-toggle="collapse" href="#collapse' . $correlativo . '" style="color:black;">' . $numero_orden . '</a></td>
																			<td>' . $item . '</td>
																			<td>' . $estado . '</td>
																			<td>' . $seed . '</td>
																			<td>' . $exp_delivery . '</td> 
																			<td>' . $last_delivery . '</td>
																			<td>' . $actual_plants . '</td>
																			<td>' . $germ . '</td>
																			<td>' . $exp_plants . '</td> 
																			<td>' . $balance . '</td>
																			<td>' . $toggle . '</td>
                                    </tr>';

                            //echo $flag_completado . " test </br>";

                            if (isset($_POST['cod_estados']) && $_POST['cod_estados'] != '') {
                              $cod_estados = $_POST['cod_estados'];
                              $cod_estados = implode(',', $cod_estados);
                              $TRAS_DETALLES = $DB_INV->inv_listado_trasplante_detalle_por_estado(
                                $fechaInicial,
                                $fechaFinal,
                                $trasplante['COD_SEED'],
                                $trasplante['ITEM'],
                                $cod_estados,
                                $flag_completado
                              );
                            } else {
                              $TRAS_DETALLES = $DB_INV->inv_listado_trasplante_detalle(
                                $fechaInicial,
                                $fechaFinal,
                                $trasplante['COD_SEED'],
                                $trasplante['ITEM'],
                                $flag_completado
                              );
                            }
                            // $TRAS_DETALLES = $DB_INV->inv_listado_trasplante_detalle(
                            // 	$fechaInicial,
                            // 	$fechaFinal,
                            // 	$TRASPLANTES[$i]['COD_SEED'],
                            // 	$TRASPLANTES[$i]['ITEM'],
                            // 	$flag_completado
                            // );

                            $cuerpo_tabla_detalle = '';
                            foreach ($TRAS_DETALLES as $TRAS_DETALLE) {
                              //Propiedades de cada fila
                              $PROP = 'onclick="inv_vista_nuevo_transplante(\'' . $TRAS_DETALLE['TICKET'] . '\')" ';
                              $COMP = $TRAS_DETALLE['completado'] == 1 ? 0 : 1;
                              $CHECK = $TRAS_DETALLE['completado'] == 1 ? 'checked="checked"' : '';
                              $COLOR = $TRAS_DETALLE['completado'] == 1 ? 'class="success elemento_seleccionable"' : 'class="danger elemento_seleccionable"';


                              $cuerpo_tabla_detalle .= '<tr ' . $COLOR . '>
																							<td ' . $PROP . '>' . $TRAS_DETALLE['TICKET'] . '</td>
																							<td ' . $PROP . '>' . $TRAS_DETALLE['ESTADO'] . '</td>
																							<td ' . $PROP . '>' . $TRAS_DETALLE['FECHA_ENTREGA'] . '</td>
																							<td ' . $PROP . '>' . $TRAS_DETALLE['FECHA_DELIVERY'] . '</td>
																							<td ' . $PROP . '>' . $TRAS_DETALLE['ACTUAL_PLANTS'] . '</td>
																							<td ' . $PROP . '>' . $TRAS_DETALLE['EXP_PLANTS'] . '</td>
																							<td ' . $PROP . '>' . $TRAS_DETALLE['GERM'] . '%</td>
																							<td ' . $PROP . '>' . $TRAS_DETALLE['BALANCE'] . '</td>
																							<td>
																								<div class="material-switch pull-right">
																									<input class="checkbox checkbox_hijo_' . $TRASPLANTES[$i]['cod_plantacion'] . '" 
																										onclick="inv_cambiar_estado_completado_trasplante(' . $TRAS_DETALLE['cod_trasplante'] . ', ' . $COMP . ', ' . $TRASPLANTES[$i]['cod_plantacion'] . ')" 
																										id="checkbox_hijo_' . $TRAS_DETALLE['cod_trasplante'] . '" 
																										data-id="checkbox_hijo_' . $TRAS_DETALLE['cod_trasplante'] . '" 
																										name="checkbox_hijo_' . $TRAS_DETALLE['cod_trasplante'] . '" 
																										type="checkbox" ' . $CHECK . ' />
																									<label for="checkbox_hijo_' . $TRAS_DETALLE['cod_trasplante'] . '" class=""></label>
																								</div>
																							</td>
																						</tr>';
                            }

                            $cuerpo_tabla .= '<tr>
																		<td colspan="11" class="no-padding">
																			<div id="collapse' . $correlativo . '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
																				<div class="panel-body">
																					<div class="row">
																						<div class="col-md-12 col-sm-12" id="div_historial' . $correlativo . '">
																							<table class="table display row-border table-responsive" id="tabla_itemschecklist">

																								<thead>
																									<tr class="active info">
																										<th width="13%" class="translate" data-traducir_english="Ticket" data-traducir_spanish="Ticket">Ticket</th>
																										<th width="5%" class="translate" data-traducir_english="State" data-traducir_spanish="Estado">State</th>
																										<th width="10%" class="translate" data-traducir_english="Order Date" data-traducir_spanish="Fecha de orden">Order Date</th>
																										<th width="10%" class="translate" data-traducir_english="Last Delivery" data-traducir_spanish="Ultima entrega">Last Delivery</th>
																										<th width="10%" class="translate" data-traducir_english="Actual" data-traducir_spanish="Actual">Actual</th>
																										<th width="10%" class="translate" data-traducir_english="Exp Plants" data-traducir_spanish="Plantas esperadas">Exp Plants</th>
																										<th width="10%" class="translate" data-traducir_english="Germ" data-traducir_spanish="Germ">Germ</th>
																										<th width="10%" class="translate" data-traducir_english="Balance" data-traducir_spanish="Balance">Balance</th>
																										<th width="5%" class="translate" data-traducir_english="Completed" data-traducir_spanish="Completado">Completed</th>
																									</tr>
																								</thead>
																								<tbody id="cuerpo_tabla_detalle">
																									' . $cuerpo_tabla_detalle . '
																								</tbody>
																							</table>
																						</div>
																					</div>
																				</div>
																			</div>
																		</td>
																	</tr>';

                            $item = $TRASPLANTES[$next_index]['ITEM'];
                            $exp_delivery = $TRASPLANTES[$next_index]['EXP_DELIVERY'];
                            $numero_orden = $TRASPLANTES[$next_index]['NUMERO_ORDEN'];
                            $cod_seed = $TRASPLANTES[$next_index]['COD_SEED'];
                            $last_delivery = $TRASPLANTES[$next_index]['LAST_DELIVERY'];

                            $exp_plants = 0;
                            $actual_plants = 0;
                            $clases = array();
                            $correlativo++;
                          }
                        } else {

                          if (in_array(0, $clases)) {

                            $clase = "";

                            if ($exp_delivery < date("Y-m-d")) {
                              $clase = "danger";
                            }
                            $COMP = 1;
                            $CHECK = '';
                          } else {
                            $clase = "success";
                            $COMP = 0;
                            $CHECK = $trasplante['COMP_PLANTACION'] == "1" ? 'checked="checked"' : "";
                          }
                          if ($exp_plants == 0) {
                            $exp_plants = "--";
                            $germ = "--";
                            $balance = $actual_plants - 0;
                          } else {
                            $germ = ($actual_plants / $exp_plants) * 100;
                            $germ = round($germ, 2);
                            $germ = $germ . "%";
                            $balance = $actual_plants - $exp_plants;
                            $exp_plants = number_format($exp_plants);
                          }

                          $actual_plants = number_format($actual_plants);
                          $balance = number_format($balance);

                          if ($actual_plants == 0) {
                            $actual_plants = "--";
                            $balance = "--";
                            $COMP = 0;
                            $CHECK = '';
                          }

                          if ($last_delivery == '') {
                            $last_delivery = "--";
                            if ($exp_delivery < date("Y-m-d")) {
                              $clase = "danger";
                            } else {
                              $clase = "";
                            }
                          }

                          if ($trasplante['COD_TRANS'] == 0) {
                            $toggle = "";
                          } else {
                            $toggle = "";
                            $toggle = '<div class="material-switch pull-right">
																		<input class="checkbox" 
																		onclick="cambiar_estado_trasplante(\'' . $fechaInicial . '\', \''
                              . $fechaFinal . '\', '
                              . $trasplante['COD_SEED'] . ', '
                              . $trasplante['ITEM'] . ', '
                              . $flag_completado . ', '
                              . ($trasplante['COMP_PLANTACION'] == '0' ? '1' : '0') . ', '
                              . $trasplante['cod_plantacion'] . ', '
                              . $trasplante['COD_TRANS'] . ')" 
																			id="checkbox_' . $trasplante['COD_TRANS'] . '" 
																			data-id="checkbox_' . $trasplante['COD_TRANS'] . '" 
																			name="checkbox_' . $trasplante['COD_TRANS'] . '" 
																			type="checkbox" ' . $CHECK . ' />
																		<label for="checkbox_' . $trasplante['COD_TRANS'] . '" class=""></label>
																	</div>';
                          }

                          $cuerpo_tabla .= '<tr class="' . $clase . '">
																			<td><a data-toggle="collapse" href="#collapse' . $correlativo . '" style="color:black;">' . $numero_orden . '</a></td>
																			<td>' . $item . '</td>
																			<td>' . $estado . '</td>
																			<td>' . $seed . '</td>
																			<td>' . $exp_delivery . '</td>
																			<td>' . $last_delivery . '</td>
																			<td>' . $actual_plants . '</td>
																			<td>' . $germ . '</td>
																			<td>' . $exp_plants . '</td>
																			<td>' . $balance . '</td>
																			<td>' . $toggle . '</td>
																		</tr>';

                          if (isset($_POST['cod_estados']) && $_POST['cod_estados'] != '') {
                            $cod_estados = $_POST['cod_estados'];
                            $cod_estados = implode(',', $cod_estados);
                            $TRAS_DETALLES = $DB_INV->inv_listado_trasplante_detalle_por_estado(
                              $fechaInicial,
                              $fechaFinal,
                              $trasplante['COD_SEED'],
                              $trasplante['ITEM'],
                              $cod_estados,
                              $flag_completado
                            );
                          } else {
                            $TRAS_DETALLES = $DB_INV->inv_listado_trasplante_detalle(
                              $fechaInicial,
                              $fechaFinal,
                              $trasplante['COD_SEED'],
                              $trasplante['ITEM'],
                              $flag_completado
                            );
                          }


                          // $TRAS_DETALLES = $DB_INV->inv_listado_trasplante_detalle(
                          // 	$fechaInicial,
                          // 	$fechaFinal,
                          // 	$TRASPLANTES[$i]['COD_SEED'],
                          // 	$TRASPLANTES[$i]['ITEM'],
                          // 	$flag_completado
                          // );

                          $cuerpo_tabla_detalle = '';
                          foreach ($TRAS_DETALLES as $TRAS_DETALLE) {
                            //Propiedades de cada fila
                            $PROP = 'onclick="inv_vista_nuevo_transplante(\'' . $TRAS_DETALLE['TICKET'] . '\')" ';
                            $COMP = $TRAS_DETALLE['completado'] == 1 ? 0 : 1;
                            $CHECK = $TRAS_DETALLE['completado'] == 1 ? 'checked="checked"' : '';
                            $COLOR = $TRAS_DETALLE['completado'] == 1 ? 'class="success elemento_seleccionable"' : 'class="danger elemento_seleccionable"';


                            $cuerpo_tabla_detalle .= '<tr ' . $COLOR . '>
																							<td ' . $PROP . '>' . $TRAS_DETALLE['TICKET'] . '</td>
																							<td ' . $PROP . '>' . $TRAS_DETALLE['ESTADO'] . '</td>
																							<td ' . $PROP . '>' . $TRAS_DETALLE['FECHA_ENTREGA'] . '</td>
																							<td ' . $PROP . '>' . $TRAS_DETALLE['FECHA_DELIVERY'] . '</td>
																							<td ' . $PROP . '>' . $TRAS_DETALLE['ACTUAL_PLANTS'] . '</td>
																							<td ' . $PROP . '>' . $TRAS_DETALLE['EXP_PLANTS'] . '</td>
																							<td ' . $PROP . '>' . $TRAS_DETALLE['GERM'] . '%</td>
																							<td ' . $PROP . '>' . $TRAS_DETALLE['BALANCE'] . '</td>
																							<td>
																								<div class="material-switch pull-right">
																									<input class="checkbox checkbox_hijo_' . $TRASPLANTES[$i]['cod_plantacion'] . '" 
																										onclick="inv_cambiar_estado_completado_trasplante(' . $TRAS_DETALLE['cod_trasplante'] . ', ' . $COMP . ', ' . $TRASPLANTES[$i]['cod_plantacion'] . ')" 
																										id="checkbox_hijo_' . $TRAS_DETALLE['cod_trasplante'] . '" 
																										data-id="checkbox_hijo_' . $TRAS_DETALLE['cod_trasplante'] . '" 
																										name="checkbox_hijo_' . $TRAS_DETALLE['cod_trasplante'] . '" 
																										type="checkbox" ' . $CHECK . ' />
																									<label for="checkbox_hijo_' . $TRAS_DETALLE['cod_trasplante'] . '" class=""></label>
																								</div>
																							</td>
																						</tr>';
                          }

                          $cuerpo_tabla .= '<tr>
																		<td colspan="11" class="no-padding">
																			<div id="collapse' . $correlativo . '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
																				<div class="panel-body">
																					<div class="row">
																						<div class="col-md-12 col-sm-12" id="div_historial' . $correlativo . '">
																							<table class="table display row-border table-responsive" id="tabla_itemschecklist">

																								<thead>
																									<tr class="active info">
																										<th width="13%" class="translate" data-traducir_english="Ticket" data-traducir_spanish="Ticket">Ticket</th>
																										<th width="5%" class="translate" data-traducir_english="State" data-traducir_spanish="Estado">State</th>
																										<th width="10%" class="translate" data-traducir_english="Order Date" data-traducir_spanish="Fecha de orden">Order Date</th>
																										<th width="10%" class="translate" data-traducir_english="Last Delivery" data-traducir_spanish="Ultima entrega">Last Delivery</th>
																										<th width="10%" class="translate" data-traducir_english="Actual" data-traducir_spanish="Actual">Actual</th>
																										<th width="10%" class="translate" data-traducir_english="Exp Plants" data-traducir_spanish="Plantas esperadas">Exp Plants</th>
																										<th width="10%" class="translate" data-traducir_english="Germ" data-traducir_spanish="Germ">Germ</th>
																										<th width="10%" class="translate" data-traducir_english="Balance" data-traducir_spanish="Balance">Balance</th>
																										<th width="5%" class="translate" data-traducir_english="Completed" data-traducir_spanish="Completado">Completed</th>
																									</tr>
																								</thead>
																								<tbody id="cuerpo_tabla_detalle">
																									' . $cuerpo_tabla_detalle . '
																								</tbody>
																							</table>
																						</div>
																					</div>
																				</div>
																			</div>
																		</td>
																	</tr>';

                          $next_item_index = $index + 1;
                          if (isset($TRASPLANTES[$next_item_index])) {
                            $item = $TRASPLANTES[$next_item_index]['ITEM'];
                            $exp_delivery = $TRASPLANTES[$next_item_index]['EXP_DELIVERY'];
                            $numero_orden = $TRASPLANTES[$next_item_index]['NUMERO_ORDEN'];
                            $cod_seed = $TRASPLANTES[$next_item_index]['COD_SEED'];
                            $last_delivery = $TRASPLANTES[$next_item_index]['LAST_DELIVERY'];
                          }
                          $actual_plants = 0;
                          $exp_plants = 0;
                          $clases = array();
                          $correlativo++;
                        }
                      }
                      $i++;
                    } //foreach
                  } else
                    $cuerpo_tabla .= '<tr><td colspan="14" align="center">No data</td></tr>';
                  echo $cuerpo_tabla;
                  ?>

                </tbody>
              </table>

            </div>
          </div>

        </div>
      </div>
    </div>

  </div>
</body>