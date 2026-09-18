<?PHP
/*
 * Reporte de Exploraciones, muestra listado de plantaciones con los resultados de las exploraciones.
 * @author      Jairo Bonilla
 * @date        2019-01-20
 */
session_start();
if (!isset($_SESSION['cod_usuario'])) {
	header('Location: index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once "../../libs/db_classes/db_mysql_conn.php";
include_once "../../libs/db_classes/db_reportes.php";
/*INSTANCIAMIENTOS*/
$DB_REP             	= new db_reportes();
$cod_info_empresa  		= $_POST['x1'];
$fecha_inicial      	= $_POST['x2'];
$fecha_final        	= $_POST['x3'];
$cod_inventario_quimico = $_POST['x4'];

?>
<script>
	$(document).ready(function() {
		jQuery.ajaxSetup({
			async: false
		});
		//Inicializar la barra de botones
		init_button_bar();
		grl_overlay_loading('');
		//Habilitación de listboxs
		$('.selectpicker').selectpicker({
			dropupAuto: 'true',
			container: 'body',
			size: '10',
			width: '85%',
			style: 'btn-sm btn-info has-btn-all',
			tickIcon: 'fa fa-check'
		});
		//Habilita los selects para mobile
		if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
			$('.selectpicker').selectpicker('mobile');
		}
		var fecha_hoy = new Date();
		var hoy = (fecha_hoy.getMonth() + 1) + "/" + fecha_hoy.getDate() + "/" + fecha_hoy.getFullYear();
		var hoy_default = fecha_hoy.getFullYear() + "-" + (fecha_hoy.getMonth() + 1) + "-" + fecha_hoy.getDate();

		//Construcción de fechas
		$('#div_fecha_inicial').datetimepicker({
			/*pickTime: false,
			language: 'es',
			//defaultDate: '1994-12-31',
			icons: {
						time: "fa fa-clock-o",
						date: "fa fa-calendar",
						up: "fa fa-arrow-up",
						down: "fa fa-arrow-down"
					}*/
			locale: 'es',
			format: 'MM-DD-YYYY',
			ignoreReadonly: true,
			icons: {
				time: "fa fa-clock-o",
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
			/*pickTime: false,
			language: 'es',
			//defaultDate: '1994-12-31',
			icons: {
						time: "fa fa-clock-o",
						date: "fa fa-calendar",
						up: "fa fa-arrow-up",
						down: "fa fa-arrow-down"
					}*/
			locale: 'es',
			format: 'MM-DD-YYYY',
			ignoreReadonly: true,
			icons: {
				time: "fa fa-clock-o",
				date: "fa fa-calendar",
				up: "fa fa-arrow-up",
				down: "fa fa-arrow-down",
				previous: 'fa fa-arrow-left',
				next: 'fa fa-arrow-right',
			}
		}).on('dp.hide', function(e) {
			$('#div_fecha_inicial').data("DateTimePicker").maxDate(e.date);
		});
		conf_constructor_listado_granjas('cod_info_empresa', 0);

		/*----------------------------------------------------------------------------------
		                                Validando listboxs
		----------------------------------------------------------------------------------*/
		$(".selectpicker.requerido").change(function() {
			var objeto = $(this);
			id = $(this).parent().children('.check-all').attr('id');
			if (objeto.val() == null || objeto.val() == '-b' || objeto.val() == '') {
				objeto.selectpicker('setStyle', 'btn-info', 'remove');
				objeto.selectpicker('setStyle', 'btn-danger');
				$("#" + id).addClass('btn-danger').removeClass('btn-info allselected');
			} else {
				objeto.selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');
				objeto.selectpicker('setStyle', 'btn-info');
				objeto.removeClass('campo-vacio');
				$("#" + id).removeClass('btn-danger').addClass('btn-info');
			}
			objeto.selectpicker('refresh');
		});

		/*----------------------------------------------------------------------------------
		                    Validación input, textarea requeridos
		----------------------------------------------------------------------------------*/
		$(".input.requerido, .input.requerido-modal").keyup(function(event) {
			if ($(this).val().trim() != "") {
				$('#btn_registrar_denuncia').removeAttr('disabled');
				$(this).removeClass('input-has-error campo-vacio campo-vacio-modal');
			} else {
				$(this).addClass('input-has-error');
			}
		});
		$('#cod_info_empresa').change(function(event) {
			/* Act on the event */
			inv_constructor_listado_quimicos('cod_inventario_quimico', $(this).val(), 1);
		});
		$('#cod_info_empresa').val('<?php echo $cod_info_empresa; ?>');
		$('#cod_inventario_quimico').val('<?php echo $cod_inventario_quimico; ?>');
		$('#fecha_inicial').val('<?php echo $fecha_inicial; ?>');
		$('#fecha_final').val('<?php echo $fecha_final; ?>');

		$('.selectpicker').selectpicker('refresh');
		// <?php
			//  if (count($PLANTACIONES) && isset($fecha_final))
			//  {
			?>
		// 	$('#excel').removeClass('hide');
		// 	/*$('#dev-table').DataTable({
		// 		"scrollY":        "400px",
		//         "scrollCollapse": true,
		//         "paging":         false
		// 	});*/
		<?php
		// }
		?>
		$('#buscar').click(function(event) {
			/* Act on the event */
			var error = 0;
			$(".input.requerido").map(function() {
				if (!$(this).val()) {
					error = 1;
					$(this).addClass('input-has-error campo-vacio campo-vacio-modal');
					return false;
				} else {
					$(this).removeClass('input-has-error campo-vacio campo-vacio-modal');
				}
			});
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
				grl_overlay_loading('');
				var x1 = ($('#cod_info_empresa').val() != null && $('#cod_info_empresa').val() != '' ? $('#cod_info_empresa').val().toString() : null);
				var x2 = $('#fecha_inicial').val();
				var x3 = $('#fecha_final').val();
				var x4 = ($('#cod_inventario_quimico').val() != null && $('#cod_inventario_quimico').val() != '' ? $('#cod_inventario_quimico').val().toString() : null);
				var url = "mod_reportes/funciones/rep_nuevo_reporte_excel_aplicacion_quimica.php?x1=" + x1 + "&x2=" + x2 + "&x3=" + x3 + "&x4=" + x4;
				$(location).attr('href', url);
				$('#modal_loading').modal('hide');
				/*$('#modal_loading').on('hidden.bs.modal', function () {
	                $.ajax({
	                	url: 'mod_reportes/funciones/rep_nuevo_reporte_excel_aplicacion_quimica.php',
	                	type: 'GET',
	                	dataType: 'html',
	                	contentType:"application/x-www-form-urlencoded",
	                	data: {
	                		x1: x1,
	                		x2: x2,
	                		x3: x3,
	                		x4: x4
	                	},
				        beforeSend: function ()
				        {
				            $("#loader" ).show();
				            $("#cuerpo" ).hide();
				        }
	                })
	                .done(function(data) {
						$("#cuerpo" ).show();
            			$("#loader" ).hide();
            			var opResult = JSON.parse(data);
						var $a=$("<a>");
						$a.attr("href",opResult.data);
						//$a.html("LNK");
						$("body").append($a);
						$a.attr("download","Chemical Application Excel Download.xlsx");
						$a[0].click();
						$a.remove();
	                })
	                .fail(function() {
	                	console.log("error");
	                })
	                .always(function() {
	                	console.log("complete");
	                });

	                //var url = "mod_reportes/funciones/rep_reporte_excel_aplicacion_quimica.php?x1="+x1+"&x2="+x2+"&x3="+x3;
					//$(location).attr('href',url);
	            });*/
			} else {
				grl_mensaje('You must fill the empty fields', 'Debe llenar los campos vacíos', 'warning');
			}
		});
		$('#modal_loading').modal('hide');
		jQuery.ajaxSetup({
			async: true
		});
	});
</script>
<style type="text/css">
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
</style>
<div id="overlay_loading"></div>
<div id="message_box"></div>
<div class="panel panel-default">
	<div class="panel-body">
		<div class="page-header">
			<h1 class="translate" data-traducir_english="New Chemical Application Excel Download" data-traducir_spanish="Nueva Descarga Excel Aplicación Química">Nueva Descarga Excel Aplicación Química</h1>
		</div>
		<div class="row">
			<div class="col-md-2">
				<div class="form-group input-group-sm date" id="">
					<label for="fecha_inicial" class="translate" data-traducir_english="Initial Date" data-traducir_spanish="Fecha Inicial">Fecha Inicial</label>
					<div class='input-group input-group-sm date' id='div_fecha_inicial'>
						<span class="input-group-addon">
							<span class="fa fa-calendar">
							</span>
						</span>
						<input type='text' class="form-control input requerido" id="fecha_inicial" readonly="" />
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group input-group-sm date" id="">
					<label for="fecha_final" class="translate" data-traducir_english="Final Date" data-traducir_spanish="Fecha Final">Fecha Final</label>
					<div class='input-group input-group-sm date' id='div_fecha_final'>
						<span class="input-group-addon">
							<span class="fa fa-calendar">
							</span>
						</span>
						<input type='text' class="form-control input requerido" id="fecha_final" readonly="" />
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<label for="cod_info_empresa" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</label>
				<div class="form-group show-tick">
					<select class="selectpicker show-menu-arrow requerido" title="Seleccione" id="cod_info_empresa" name="cod_info_empresa">
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<label for="cod_inventario_quimico" class="translate" data-traducir_english="Chemical" data-traducir_spanish="Químico">Químico</label>
				<div class="form-group show-tick">
					<select class="selectpicker show-menu-arrow requerido" multiple="multiple" data-actions-box="true" title="Seleccione" id="cod_inventario_quimico" name="cod_inventario_quimico">
					</select>
				</div>
			</div>

			<div class="col-md-1">
				<div class="form-group input-group-sm" id="div_buscar">
					<center>
						<label for="buscar" class="translate" data-traducir_english="Search" data-traducir_spanish="Buscar">Buscar</label>
						<div class="form-group input-group-sm" id="div_buscar">
							<button class="btn btn-info" type="button" id="buscar" name="buscar">
								<i class="fa fa-search"></i>
							</button>
						</div>
					</center>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="panel-footer-actions text-right smooth-transition actions-container-is-closed" id="panel_footer_actions">
	<div class="close-actions-container">
		<button class="btn btn-xs btn-close-actions" id="btn_close_actions">
			<i class="fa fa-chevron-circle-down fa-lg"></i>
		</button>
	</div>
	<div class="row actions">
		<div class="col-xs-12 col-md-12 col-sm-12 nopadding smooth-transition" id="div_acciones">
			<button class="btn btn-sm btn-primary btn-open-actions main-actions smooth-transition" type="button" id="btn_open_actions" name="btn_open_actions">
				<i class="fa fa-ellipsis-v"></i>
			</button>
		</div>
		<div class="col-md-10 col-sm-10 col-xs-8 text-center smooth-transition">
		</div>
		<div class="col-md-2 col-sm-2 col-xs-4 text-center nopadding smooth-transition">
			<!-- <button id="excel"  name="excel" class="btn btn-sm btn-info main-actions hide smooth-transition" type="button" data-loading-text="Convirtiendo...">Excel</button> -->
		</div>
	</div>
</div> <!-- panel-footer -->