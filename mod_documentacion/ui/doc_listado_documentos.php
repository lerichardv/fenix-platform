<?PHP
/*
 * Vista del listado de todos los documentos en el repositorio.
 * @author      Dan Urquía
 * @date        2017-06-23
 */
session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<title>Listado de documentos</title>
</head>
<script type="text/javascript">
	jQuery.ajaxSetup({async:false});
	$('#modal_loading').modal('hide');
	grl_overlay_loading('Cargando información');
 	$(document).ready(function() {
		/*Constructores de selectpickers*/
	  //usu_constructor_gerencias('../../');
	  //doc_constructor_tipo_documentos();
	  //Habilitación de listboxs
	  $('.selectpicker').selectpicker({
	    dropupAuto: 'true',
	    container: 'body',
	    size: '10',
	    width: '100%',
	    style: 'btn-sm btn-info'
	  });
		$('.selectpicker').selectpicker('refresh');

 		init_button_bar();

		/*
		 * Función que realiza la "busqueda" dentro de la tabla con información
		 */
		(function(){
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
		});

		$('#modal_loading').modal('hide');
		jQuery.ajaxSetup({async:true});
	}); //Fin ready

	//Evento onclick
$( "#buscar" ).click(function() {
	error = 0;
	if (//$("#cod_gerencia").val() == null || $("#cod_gerencia").val() == '-b'
	//&& $("#cod_tipo_documento").val() == null || $("#cod_tipo_documento").val() == '-b' &&
	$("#parametros").val() == '') {
		error = 1;
	}

	if(error == 0) {
		doc_llenado_tabla_documentos(//$('#cod_gerencia option:selected').val(),
																 //$('#cod_tipo_documento option:selected').val(),
																 $('#parametros').val());
	} else {
		grl_mensaje('No options selected, please verify. - ', 'No hay opciones seleccionadas, favor verificar.', 'warning');
	}
});

$('#parametros').on('keydown', function(e) {
    if (e.which == 13) {
			error = 0;
			if (//$("#cod_gerencia").val() == null || $("#cod_gerencia").val() == '-b'
			//&& $("#cod_tipo_documento").val() == null || $("#cod_tipo_documento").val() == '-b' &&
			$("#parametros").val() == '') {
				error = 1;
			}

			if(error == 0) {
				doc_llenado_tabla_documentos(//$('#cod_gerencia option:selected').val(),
																		 //$('#cod_tipo_documento option:selected').val(),
																		 $('#parametros').val());
			} else {
				grl_mensaje('No options selected, please verify. - ', 'No hay opciones seleccionadas, favor verificar.', 'warning');
			}
    }
});

/*
 * Función que limpia la tabla de docuemntos y la llena segun la variable seleccionada.
 *
 * var x1 int Código del o los juzgados seleccionados
 */
function doc_llenado_tabla_documentos(x3){
    jQuery.ajaxSetup({async:false});
    $.ajax({
      type: 'POST',
      url: 'mod_documentacion/funciones/doc_llenar_listado_documentos_parametros.php',
      data: ({
              x1: '-b',
							x2: '-b',
							x3: x3
            }),
      success: function(data) {
        $("#dev-table").empty();
        $("#dev-table").append(data);
				$( ".boton_descarga" ).addClass( "descargar_doc" );
        $.ajax({
          url: 'mod_documentacion/funciones/doc_contador_total_tipo_documentos.php',
          type: 'POST',
          success: function(data) {
              $("#div_total_documentos").empty();
              $("#div_total_documentos").append(data);
							//Clase que hace click en el documento a descargar para validar el numero de descargas
							$( ".descargar_doc" ).click(function() {
								var device = 1;
								if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
							     device = 2;
							  }
								doc_insert_historial($(this).attr( "documento_num" ),
																		device);
							});
          }
        })
        .done(function() {
            console.log("success");
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
      }
    });
    jQuery.ajaxSetup({async:true});
}


</script>
<style>

div.circle-avatar{
	/* make it responsive */
	max-width: 100%;
	width:100%;
	height:auto;
	display:block;
	/* div height to be the same as width*/
	padding-top:100%;

	/* make it a circle */
	border-radius:50%;

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
.row{
	padding: 0 10px;
}
.panel_cabecera div {
	margin-top: -18px;
	font-size: 15px;
}
.panel_cabecera div span{
	margin-left:5px;
}
.panel_cuerpo{
	display: none;
		}
#dev-table {
	font-size: 12px;
}

.resumen-bitacora{
			margin-right: 10px;
			display: inline-block;
			padding: 2px;
			border-radius: 5px;
	}

.badge.bitacora{
			background: rgb(30,30,30);
	}
	.resumen-bitacora.nivel-3{
			background: rgb(205, 244, 205) !important;
	}
	.resumen-bitacora.nivel-4{
			background: rgb(255,150,150) !important;
	}

	.badge.bitacora.nivel-3{
			background: rgb(76,174,76);
	}
	.badge.bitacora.nivel-4{
			background: rgb(255,105,105);
	}

	.nivel-3{
			border-right-color: rgb(76,174,76);
	}
	.nivel-4{
			border-right-color: rgb(255,150,150);
	}
	#dev-table > tbody tr
	{
		cursor: pointer;
	}
		.bubble-timeline{
        display: inline-block;
        background-color: rgb(77, 178, 208);
        border-radius: 50%;
        line-height: 2em;
        max-width: 150px;
        /*border: 2px solid rgb(255, 255, 255);*/
        padding: 2px;
        margin-bottom: 15px;
        margin-top: 15px;
    }
    .icon-timeline-container{
        background-color: rgb(77, 178, 208);
        border: 3px solid rgb(255, 255, 255);
        border-radius: 50%;
        display: inline-block;
    }
    .img-timeline{
        width: 100%;
        /*background-color: rgb(77, 178, 208);
        border: 3px solid rgb(255, 255, 255);*/
        border-radius: 50%;
        display: inline-block;
    }

		.no-padding {
		    padding: 0px !important;
		}

		.no-margin {
		    margin: 0px !important;
		}
		h4 {
		    color: #01579B !important;
		}
</style>
<body>
	<div id="overlay_loading"></div>

	<div class="row">
    <div class="col-md-12">
      <div class="page-header">
        <h1 class="translate" data-traducir_english="Virtual library" data-traducir_spanish="Biblioteca virtual">Biblioteca virtual <small>Búsqueda de documentos</small></h1>
      </div>
    </div>
  </div>
  <div class="row">
    <!-- <div class="col-md-3">
      <label for="cod_gerencia">Programa</label>
        <div class="form-group show-tick">
        <select class="selectpicker show-menu-arrow requerido" data-size="auto" id="cod_gerencia" name="cod_gerencia"></select>
      </div>
    </div>
      <div class="col-md-3">
        <label for="cod_tipo_documento">Tipo de documento</label>
          <div class="form-group show-tick">
          <select class="selectpicker show-menu-arrow requerido" data-size="auto" id="cod_tipo_documento" name="cod_tipo_documento"></select>
        </div>
      </div> -->
      <div class="col-md-12">
				<label  class="translate" data-traducir_english="Search parameters" data-traducir_spanish="Parámetros de busqueda" for="cod_tipo_documento">Parámetros de busqueda</label>
        <div class="input-group input-group-sm">
          <input type="text" class="form-control input requerido placeholder_translate" data-placeholder_en="Enter the parameters to perform virtual library search" data-placeholder_es="Ingrese los parámetros para realizar búsqueda en biblioteca virtual" id="parametros" placeholder="Ingrese los parámetros para realizar búsqueda en biblioteca virtual">
		      <span class="input-group-btn">
		        <button id="buscar" class="btn btn-info" type="button"><i class="fa fa-search"></i></button>
		      </span>
        </div>
      </div>
  </div>
	<div class="row">
      <div class="col-md-12">
				&nbsp;
      </div>
  </div>
<div class="row">
	<div class="busqueda_contenedor">
    	<div class="row" style="overflow:auto; min-width:100px;">
			<div class="col-sm-12">
				<div class="panel panel-primary" style="overflow:auto;">
					<div class="panel-heading panel_cabecera">
						<h3 class="panel-title translate" data-traducir_english="Documents in library" data-traducir_spanish="Documentos en biblioteca">Documentos en biblioteca</h3>
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
						<table class="table table-condensed display no-margin" id="dev-table">
                <thead>
                    <tr class="active info">
                        <th class="translate" data-traducir_english="Program" data-traducir_spanish="Programa" width="10%">Programa</th>
                        <th class="translate" data-traducir_english="Document type" data-traducir_spanish="Tipo documento" width="10%">Tipo documento</th>
                        <th class="translate" data-traducir_english="Title" data-traducir_spanish="Título" width="20%">Título</th>
                        <th class="translate" data-traducir_english="Description" data-traducir_spanish="Descripción" width="55%">Descripción</th>
                        <th class="translate" data-traducir_english="Download" data-traducir_spanish="Descargar" width="10%">Descargar</th>
                    </tr>
                </thead>
                <tbody>
										<tr>
                      <td align="center" style="background-color:#EC971F;" colspan="6">
												<span class="fa-stack fa-lg">
												  <i class="fa fa-circle fa-stack-2x"></i>
												  <i class="fa fa-exclamation fa-stack-1x fa-inverse"></i>
												</span>
											  <strong
													class="translate"
													data-traducir_english="Please indicate the parameters in the filters to perform a virtual library search."
													data-traducir_spanish="Favor indicar los parámetros en los filtros para realizar búsqueda en biblioteca virtual.">Favor indicar los parámetros en los filtros para realizar búsqueda en biblioteca virtual.</strong>
											</td>
                    </tr>
                </tbody>
              </table>
	        </div>
				</div><!--/panel panel-primary -->
			</div><!-- /col-12 -->
		</div>
		<!--<div class="panel-footer-actions text-right smooth-transition actions-container-is-closed" id="panel_footer_actions">
			<div class="close-actions-container">
				<button class="btn btn-xs btn-close-actions" id="btn_close_actions">
					<i class="fa fa-chevron-circle-down fa-lg"></i>
				</button>
			</div>
			<div class="row actions">
	        	<div class="col-xs-10 col-md-11 col-sm-11 nopadding smooth-transition" id="div_acciones">
	        		<button class="btn btn-sm btn-primary btn-open-actions btn-fullwidth main-actions smooth-transition" type="button" id="btn_open_actions" name="btn_open_actions">
		                <i class="fa fa-ellipsis-v"></i>
		            </button>
		            <div class="col-md-11 col-sm-11 col-xs-10 nopadding text-center smooth-transition" id="div_total_documentos" style="margin-top:5px!important;">
			        	<?php
			        	if(count($TOTALES))
			        	{
							//echo '</i><span class="badge" style="background-color:#1D9F75;">Políticas 1</span> ';
							//echo ' </i><span class="badge" style="background-color:#1E1E1E;">Total 1</span>';
				        }
				        ?>
			        </div>
			    </div>
			</div>
		</div>  panel-footer -->
	</div> <!-- /busqueda_contenedor -->
</div>
</body>
</html>
