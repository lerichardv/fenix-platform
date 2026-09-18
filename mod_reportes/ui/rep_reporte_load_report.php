<?PHP
/*
 * Reporte de Costos, muestra listado de plantaciones y el costo que generan en químicos aplicados.
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
$DB_REP             = new db_reportes();
$fecha_inicial       = $_POST['fecha_inicial'];
$fecha_final         = $_POST['fecha_final'];
$PLANTACIONES = [];

if (isset($fecha_final)) {
	$PLANTACIONES = $DB_REP->qua_listado_reporte_load_report($fecha_inicial,$fecha_final);
}
$loose_bunches = [ 'Bunch',
            'Loose'];

?>
<script>
	$(document).ready(function(){
		jQuery.ajaxSetup({async:false});
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
	    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	        $('.selectpicker').selectpicker('mobile');
	    }
	    var fecha_hoy   = new Date();
		var hoy 		= (fecha_hoy.getMonth()+1) + "/" + fecha_hoy.getDate() + "/" + fecha_hoy.getFullYear();
		var hoy_default = fecha_hoy.getFullYear() + "-" + (fecha_hoy.getMonth()+1) + "-" + fecha_hoy.getDate();

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
		}).on('dp.hide',function (e){
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
		}).on('dp.hide',function (e){
			$('#div_fecha_inicial').data("DateTimePicker").maxDate(e.date);
		});


	    /*----------------------------------------------------------------------------------
	                                    Validando listboxs
	    ----------------------------------------------------------------------------------*/
	    $( ".selectpicker.requerido" ).change(function() {
	        var objeto = $(this);
	        id = $(this).parent().children('.check-all').attr('id');
	        if (objeto.val() == null || objeto.val() == '-b' || objeto.val() == ''){
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
	        if( $(this).val().trim() != "" ){
                $('#btn_registrar_denuncia').removeAttr('disabled');
	            $(this).removeClass('input-has-error campo-vacio campo-vacio-modal');
	        } else {
	            $(this).addClass('input-has-error');
	        }
	    });
	    $('#fecha_inicial').val('<?php echo $fecha_inicial; ?>');
    	$('#fecha_final').val('<?php echo $fecha_final; ?>');

    	$('.selectpicker').selectpicker('refresh');
	    <?php if (count($PLANTACIONES) && isset($fecha_final))
	    {
	    	?>
	    	$('#excel').removeClass('hide');
			$('#dev-table').DataTable({
	            /*"language":
	            {
	                "sProcessing":     "Procesando...",
	                "sLengthMenu":     "Mostrar _MENU_ registros",
	                "sZeroRecords":    "No se encontraron resultados",
	                "sEmptyTable":     "Ningún dato disponible en esta tabla",
	                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
	                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
	                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
	                "sInfoPostFix":    "",
	                "sSearch":         "Buscar:",
	                "sUrl":            "",
	                "sInfoThousands":  ",",
	                "sLoadingRecords": "Cargando...",
	                "oPaginate": {
	                    "sFirst":    "Primero",
	                    "sLast":     "Último",
	                    "sNext":     "Siguiente",
	                    "sPrevious": "Anterior"
	                },
	                "oAria": {
	                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
	                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
	                }
	            },*/
	            "dom": 'Bfrtip',
		        "buttons": [
		            {
		                extend: 'pdfHtml5',
		                orientation: 'landscape',
		                pageSize: 'A0',
		                title: 'Load Report',
		            },
		            'print',
		        ]
	        });
	    	<?php
	    }
	    ?>

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
									var no_results = $('<tr class="filterTable_no_results"><td colspan="7">No hay resultados.</td></tr>')
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
		})
		$(".check-all").click(function(event){
		    event.stopPropagation();
	   		id = $(this).parent().children('.selectpicker').attr('id');
	   		if ($(this).hasClass('allselected')) {
	   			$(this).removeClass('allselected btn-info').addClass('btn-danger');
	   			$('#'+id+' option').prop('selected', false);
			    $('#'+id).selectpicker('setStyle', 'btn-danger');
				$('#'+id).selectpicker('setStyle', 'btn-info', 'remove');
	   		}
	   		else{
	   			$(this).addClass('allselected btn-info').removeClass('btn-danger');
	   			$('#'+id+' option').prop('selected', true);
			    $('#'+id).selectpicker('setStyle', 'btn-danger', 'remove');
				$('#'+id).selectpicker('setStyle', 'btn-info');
	   		}
		    $('#'+id).selectpicker('refresh');
	   	});
		$( "#excel" ).click(function() {
			var error = 0;
			x1 = "<?php echo $fecha_inicial; ?>";
			x2 = "<?php echo $fecha_final; ?>";

			//Validación de los inputs
			if ($("#fecha_inicial").val() == '' && x1 ==''){
				$( '#div_fecha_inicial' ).addClass('has-error');
				if (error != 1) error = 1;
			}
			else
			$( '#div_fecha_inicial' ).removeClass('has-error');
			if ($("#fecha_final").val() == '' && x2 ==''){
				$( '#div_fecha_final' ).addClass('has-error');
				if (error != 1) error = 1;
			}
			else
				$( '#div_fecha_final' ).removeClass('has-error');

			if (error != 1) {
				//x1 = $("#fecha_inicial").val()+" 00:00:00";
				//x2 = $("#fecha_final").val()+" 23:59:59";
				//x3 = $("#estado").val();
				//x4 = $("#tipo_documento").val();
				var url = "mod_control_calidad/funciones/qua_report_load_report_excel.php?x1="+x1+"&x2="+x2;
				$(location).attr('href',url);
			} else {
				grl_mensaje('You must fill the empty fields','Debe llenar los campos vacíos','warning');
			}
		});
		$('#buscar').click(function(event) {
			/* Act on the event */
	        var error = 0;
	        $(".input.requerido").map(function(){
	            if( !$(this).val() )
	            {
	                error = 1;
	                $(this).addClass('input-has-error campo-vacio campo-vacio-modal');
	                return false;
	            }
	            else
	            {
	                $(this).removeClass('input-has-error campo-vacio campo-vacio-modal');
	            }
	        });
	        $(".selectpicker.requerido").map(function(){
	            if ($(this).val() == null || $(this).val() == '-b' || $(this).val() == '')
	            {
	                $(this).selectpicker('setStyle', 'btn-info', 'remove');
	                $(this).selectpicker('setStyle', 'btn-danger');
	                error = 1;
	            }
	            else
	            {
	                $(this).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');
	                $(this).selectpicker('setStyle', 'btn-info');
	                $(this).removeClass('campo-vacio');
	            }
	            $(this).selectpicker('refresh');
	        });
	        if (error == 0)
	        {
	            grl_overlay_loading('');
	            $('#modal_loading').modal('hide');
	            $('#modal_loading').on('hidden.bs.modal', function () {
	                $.ajax({
	                	url: 'mod_control_calidad/ui/qua_report_load_report.php',
	                	type: 'POST',
	                	dataType: 'html',
	                	data: {
	                		fecha_inicial: $('#fecha_inicial').val(),
	                		fecha_final: $('#fecha_final').val()
	                	},
	                })
	                .done(function(data) {
	                	$('#div_cuerpo_menu').empty();
						$('#div_cuerpo_menu').html(data);
	                })
	                .fail(function() {
	                	console.log("error");
	                })
	                .always(function() {
	                	console.log("complete");
	                });

	            });
	        }
	        else
	        {
	            grl_mensaje('You must fill the empty fields','Debe llenar los campos vacíos','warning');
	        }
		});
		$('#modal_loading').modal('hide');
		jQuery.ajaxSetup({async:true});
	 });
</script>
<style type="text/css">
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
    .donacion_0
    {
        background-color: #D6858F;
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
	.tr_finalizada
	{
		background-color: rgb(76,174,76);
	}
	.tr_encurso
	{
		background-color: #fff;
	}
</style>
<div id="overlay_loading"></div>
<div id="message_box"></div>
<div class="panel panel-default">
  	<div class="panel-body">
        <div class="page-header">
            <h1 class="translate" data-traducir_english="Load Order Assessment" data-traducir_spanish="Evaluación de Orden de Carga">Evaluación de Orden de Carga</h1>
        </div>
		<div class="row">
			<div class="col-md-4">
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
          	<div class="col-md-4">
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
          	<div class="col-md-1 col-md-offset-3">
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
		<div class="row" style="overflow:auto; min-width:100px;">
			<div class="col-md-12">
				<div class="panel panel-info" >
					<div class="panel-heading">
						<h3 class="panel-title translate" data-traducir_english="Product List" data-traducir_spanish="Listado de Productos">Listado de Productos</h3>
					</div>
					<!-- Table -->
					<div class="responsive_table_container">
						<table class="table table-responsive table-condensed table-striped" id="dev-table">
							<thead>
								<tr class="active info">
									<th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
									<th width="15%" class="translate" data-traducir_english="Product" data-traducir_spanish="Product">Product</th>
									<th width="15%" class="translate" data-traducir_english="Order #" data-traducir_spanish="Order #">Order #</th>
									<th width="15%" class="translate" data-traducir_english="Order Date" data-traducir_spanish="Order Date">Order Date</th>
									<th width="15%" class="translate" data-traducir_english="Lot code" data-traducir_spanish="Lot code">Lot code</th>
									<th width="15%" class="translate" data-traducir_english="Ranch lot code" data-traducir_spanish="Ranch lot code">Ranch lot code</th>
									<th width="15%" class="translate" data-traducir_english="Size at harvest" data-traducir_spanish="Size at harvest">Size at harvest</th>
									<th width="15%" class="translate" data-traducir_english="Dark green color" data-traducir_spanish="Dark green color">Dark green color</th>
									<th width="15%" class="translate" data-traducir_english="Yellow leaves" data-traducir_spanish="Yellow leaves">Yellow leaves</th>
									<th width="15%" class="translate" data-traducir_english="Weeds" data-traducir_spanish="Weeds">Weeds</th>
									<th width="15%" class="translate" data-traducir_english="Optimal soil water capacity" data-traducir_spanish="Optimal soil water capacity">Optimal soil water capacity</th>
									<th width="15%" class="translate" data-traducir_english="Right density" data-traducir_spanish="Right density">Right density</th>
									<th width="15%" class="translate" data-traducir_english="Other defects" data-traducir_spanish="Other defects">Other defects</th>
									<th width="15%" class="translate" data-traducir_english="Size range" data-traducir_spanish="Size range">Size range</th>
									<th width="15%" class="translate" data-traducir_english="Size range" data-traducir_spanish="Size range">Size range</th>
									<th width="15%" class="translate" data-traducir_english="Size range" data-traducir_spanish="Size range">Size range</th>
									<th width="25%" class="translate" data-traducir_english="Other defects" data-traducir_spanish="Other defects">Other defects</th>
									<th width="15%" class="translate" data-traducir_english="Dew of leaf" data-traducir_spanish="Dew of leaf">Dew of leaf</th>
									<th width="15%" class="translate" data-traducir_english="Time of harvest" data-traducir_spanish="Time of harvest">Time of harvest</th>
									<th width="15%" class="translate" data-traducir_english="Temperature of product" data-traducir_spanish="Temperature of product">Temperature of product</th>
									<!-- <th width="15%" class="translate" data-traducir_english="Average tote weight reported" data-traducir_spanish="Average tote weight reported">Average tote weight reported</th>
									<th width="15%" class="translate" data-traducir_english="Real average tote weight" data-traducir_spanish="Real average tote weight">Real average tote weight</th> -->
									<th width="15%" class="translate" data-traducir_english="Time of receiving" data-traducir_spanish="Time of receiving">Time of receiving</th>
									<th width="15%" class="translate" data-traducir_english="Total load lbs goal" data-traducir_spanish="Total load lbs goal">Total load lbs goal</th>
									<th width="15%" class="translate" data-traducir_english="Load weight receiving" data-traducir_spanish="Load weight receiving">Load weight receiving</th>
									<th width="15%" class="translate" data-traducir_english="Average tote weight" data-traducir_spanish="Average tote weight">Average tote weight</th>
									<th width="15%" class="translate" data-traducir_english="Temperature of receiving" data-traducir_spanish="Temperature of receiving">Temperature of receiving</th>
									<th width="15%" class="translate" data-traducir_english="Time of VC" data-traducir_spanish="Time of VC">Time of VC</th>
									<th width="15%" class="translate" data-traducir_english="Temperature of VC" data-traducir_spanish="Temperature of VC">Temperature of VC</th>
									<th width="15%" class="translate" data-traducir_english="Hydrocooling" data-traducir_spanish="Hydrocooling">Hydrocooling</th>
									<th width="15%" class="translate" data-traducir_english="Time of pick Up" data-traducir_spanish="Time of pick Up">Time of pick Up</th>
									<th width="15%" class="translate" data-traducir_english="TLC" data-traducir_spanish="TLC">TLC</th>
									<th width="15%" class="translate" data-traducir_english="Vacuum Cooler" data-traducir_spanish="Vacuum Cooler">Vacuum Cooler</th>
									<th width="15%" class="translate" data-traducir_english="Total" data-traducir_spanish="Total">Total</th>
									<!-- <th width="15%" class="translate" data-traducir_english="Pickup truck checkin" data-traducir_spanish="Pickup truck checkin">Pickup truck checkin</th> -->
									<th width="15%" class="translate" data-traducir_english="Cut" data-traducir_spanish="Cut">Cut</th>
									<th width="15%" class="translate" data-traducir_english="Acres Harvested" data-traducir_spanish="Acres Harvested">Acres Harvested</th>
									<th width="10%" class="translate" data-traducir_english="Comments" data-traducir_spanish="Comentarios">Comentarios</th>
									<th width="15%" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</th>
								</tr>
							</thead>
							<tbody>
	                            <?PHP
	                            	$cuerpo_tabla = '';
	                            	if (count($PLANTACIONES)) {
	                            		$correlativo = 1;
	                            		$spec = ['% on spec', '% out of spec'];
	                            		$cut = ['','First Cut', 'Second Cut', 'Third Cut'];
	                            		foreach ($PLANTACIONES as $plantacion) {
	                            			$cuerpo_tabla .= '<tr>
	                            									<td>'.$correlativo.'</td>
	                            									<td>'.utf8_encode($plantacion['nombre_producto']).'</td>
	                            									<td>'.utf8_encode($plantacion['num_orden_compra']).'</td>
	                            									<td>'.utf8_encode($plantacion['fecha_orden']).'</td>
	                            									<td>'.utf8_encode($plantacion['num_lote']).'</td>
	                            									<td>'.utf8_encode($plantacion['num_lote_ranch']).'</td>
	                            									<td>'.utf8_encode($plantacion['inicial_size_harvest'].'-'.$plantacion['final_size_harvest'].'" inches').'</td>
	                            									<td>'.utf8_encode($plantacion['dark_green_color'] == 1 ? 'Yes':'No').'</td>
	                            									<td>'.utf8_encode($plantacion['yellow_leaves'] == 1 ? 'Yes':'No').'</td>
	                            									<td>'.utf8_encode($plantacion['weeds'] == 1 ? 'Yes':'No').'</td>
	                            									<td>'.utf8_encode($plantacion['optimal_soil_water_capacity'] == 1 ? 'Yes':'No').'</td>
	                            									<td>'.utf8_encode($plantacion['right_density'] == 1 ? 'Yes':'No').'</td>
	                            									<td>'.utf8_encode($plantacion['other_defects']).'</td>
	                            									<td>'.utf8_encode($plantacion['size_range_porcentage'].'% '.$plantacion['inicial_size_range'].'-'.$plantacion['final_size_range'].'" '.$spec[$plantacion['select_size_range']]).'</td>
	                            									<td>'.utf8_encode($plantacion['size_range1'].' '.$spec[$plantacion['select_size_range1']]).'</td>
	                            									<td>'.utf8_encode($plantacion['size_range2'].' '.$spec[$plantacion['select_size_range2']]).'</td>
	                            									<td>'.utf8_encode($plantacion['other_defects_ha'].' '.$spec[$plantacion['select_other_defects_ha']]).'</td>
	                            									<td>'.utf8_encode($plantacion['dew_leaf'] == 1 ? 'Yes':'No').'</td>
	                            									<td>'.utf8_encode($plantacion['inicial_time_harvest'].'-'.$plantacion['final_time_harvest']).'</td>
	                            									<td>'.utf8_encode($plantacion['temperature_product']).'</td>
	                            									<td>'.utf8_encode($plantacion['time_receiving']).'</td>
	                            									<td>'.utf8_encode($plantacion['total_load_lbs_goal']).'</td>
	                            									<td>'.utf8_encode($plantacion['load_weight_received']).'</td>
	                            									<td>'.utf8_encode($plantacion['average_tote_weight']).'</td>
	                            									<td>'.utf8_encode($plantacion['temperature_receiving']).'</td>
	                            									<td>'.utf8_encode($plantacion['time_vacuum_cooler']).'</td>
	                            									<td>'.utf8_encode($plantacion['temperature_vacuum_cooler']).'</td>
	                            									<td>'.utf8_encode($plantacion['hydrocooling'] == 1 ? 'Yes':'No').'</td>
	                            									<td>'.utf8_encode($plantacion['time_pickup']).'</td>
	                            									<td>'.utf8_encode($plantacion['tlc']).'</td>
	                            									<td>'.utf8_encode($plantacion['vacuum_cooler']).'</td>
	                            									<td>'.utf8_encode($plantacion['tlc'] + $plantacion['vacuum_cooler']).'</td>
	                            									<td>'.utf8_encode($cut[$plantacion['number_cut']]).'</td>
	                            									<td>'.utf8_encode($plantacion['acres_cosechados']).'</td>
	                            									<td>'.utf8_encode($plantacion['comentarios']).'</td>
	                            									<td>'.utf8_encode($plantacion['date_insert']).'</td>'
	                            							.'</tr>';
	                       					$correlativo++;
	                            		}
	                                }
	                                else
	                                { //Si no hay registros informará al diligencia
	                                    $cuerpo_tabla .= '<tr>
																<td align="center" colspan="8">No records</td>
	                                                      </tr>';
	                                }
	                                echo $cuerpo_tabla;
	                            ?>
							</tbody>
						</table>
					</div>
				</div> <!-- Row -->
			</div>
		</div>
		<!-- <div class="row">
			<div class="col-md-12" id="div_grafico" align="center">
				<i class="fa fa-signal" style="font-size: 300px;"></i>
			</div>
		</div> -->
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
            <button id="excel"  name="excel" class="btn btn-sm btn-info main-actions hide smooth-transition" type="button" data-loading-text="Convirtiendo...">Excel</button>
        </div>
    </div>
</div> <!-- panel-footer -->
<script type="text/javascript">
	<?php
	/*if (count($PLANTACIONES)) {
		?>
		Highcharts.chart('div_grafico', {
		    chart: {
		        type: 'column'
		    },
		    title: {
		        text: 'Load Order Assessment'
		    },
		    subtitle: {
		        text: 'Load Report List'
		    },
		    xAxis: {
		        categories: [
					        	<?php
			        				foreach ($PLANTACIONES as $plantacion) 
			    					{
			    						echo '"'.utf8_encode($plantacion['fecha_orden'].'-'.$plantacion['nombre_producto'].($plantacion['num_orden_compra'] == NULL || $plantacion['num_orden_compra'] == '' ? '':'-'.$plantacion['num_orden_compra'])).'",';
			    					}
			        			?>
        					],
		        title: {
		            text: null
		        }
		    },
		    yAxis: {
		        min: 0,
		        title: {
		            text: 'Quantity º Celsius',
		            align: 'high'
		        },
		        labels: {
		            overflow: 'justify'
		        }
		    },
		    tooltip: {
		        valueSuffix: ' '
		    },
		    plotOptions: {
		        bar: {
		            dataLabels: {
		                enabled: true
		            }
		        }
		    },
		    legend: {
		        layout: 'vertical',
		        align: 'right',
		        verticalAlign: 'top',
		        x: -40,
		        y: 80,
		        floating: true,
		        borderWidth: 1,
		        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
		        shadow: true
		    },
		    credits: {
		        enabled: false
		    },
		    series: [
		    <?php
		    $tlc = '';
		    $vc = '';
		    $total = '';
        	foreach ($PLANTACIONES as $plantacion) {        		
        		if($tlc != '') $tlc .= ',';
        		$tlc .= ($plantacion['tlc'] == NULL ? 0:$plantacion['tlc']);
        		if($vc != '') $vc .= ',';
        		$vc .= ($plantacion['vacuum_cooler'] == NULL ? 0:$plantacion['vacuum_cooler']);
        		if($total != '') $total .= ',';
        		$total .= ($plantacion['tlc']+$plantacion['vacuum_cooler']);
        	}
        	echo "{name: 'TLC', data: [".$tlc."]},{name: 'VC', data: [".$vc."]},{name: 'Total', data: [".$total."]}";
        	?>]
		});
		<?php
	}*/
	?>
</script>