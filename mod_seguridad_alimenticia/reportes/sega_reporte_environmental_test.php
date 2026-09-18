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
include_once "../../libs/db_classes/db_configuracion.php";
/*INSTANCIAMIENTOS*/
$DB_CONF 			= new db_configuracion();
$fecha_inicial      = $_POST['fecha_inicial'];
$fecha_final        = $_POST['fecha_final'];
$cod_info_empresa   = $_POST['cod_info_empresa'];
$cod_granjas_usuario = str_replace(['[','"',']'], '', $_SESSION['cod_info_empresa']);

if (isset($fecha_final)) {
	$FORMULARIOS = $DB_CONF->conf_obtener_listado_environmental_test($fecha_inicial, $fecha_final, $cod_info_empresa);
}


/*
* Arrays de codigos no administrables mediante bdd
 */
$cod_location = ['','Cooler','Wall'];
$cod_type_test = ['',
				'Generic E. coli (MPN via Colilert)',
				'Total Coliform - TC (MPN via Colilert)',
				'Fecal Coliform',
				'E. coli O157:H7',
				'Listeria',
				'L. mono (Listera monocytogenes)',
				'Salmonella',
				'STEC',
				'Yeast',
				'Total Plate Count (TPC)',
				'E. coli O157:H7 (Pooled)',
				'Total Coliform',
				'RUSH',
				'Multi-Residue Screen (MRS)',
				'MRS - Extended',
				'MRS + Dithios',
				'USDA NOP',
				'MRS4',
				'MRS + ON’s',
				'MRS - MB',
				'Long Form'
			];
$cod_source_phase = ['','Non-Food Contact','Food Contact'];
$cod_sample = ['','Swab','Sponge'];
?>
<script>
	$(document).ready(function(){
		jQuery.ajaxSetup({async:false});
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
	    conf_constructor_listado_granjas();

	    var cod_granjas_usuario = "[<?php echo $cod_granjas_usuario; ?>]";
	    cod_granjas_usuario = JSON.parse(cod_granjas_usuario);
	    $('#cod_info_empresa > option').filter(function(index) {
    		if($.inArray(parseInt($(this).val()), cod_granjas_usuario) == '-1')
    			$(this).remove();
    	});

	    $('#fecha_inicial').val('<?php echo $fecha_inicial; ?>');
    	$('#fecha_final').val('<?php echo $fecha_final; ?>');

    	$('#cod_info_empresa').selectpicker('val', "<?php echo $cod_info_empresa; ?>");

    	$('.selectpicker').selectpicker('refresh');
	    <?php if (count($FORMULARIOS) && isset($fecha_final))
	    {
	    	?>
	    	$('#excel').removeClass('hide');
	    	/*$('#pdf').removeClass('hide');
			$('#dev-table').DataTable({
	            "dom": 'Bfrtip',
		        "buttons": [
		            {
		                extend: 'pdfHtml5',
		                orientation: 'landscape',
		                pageSize: 'Letter',
		                title: 'Forms Report',
		            },
		            'print',
		        ]
	        });*/
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
			x1 = $('#fecha_inicial').val();
			x2 = $('#fecha_final').val();
			x3 = $('#cod_info_empresa').val();
			var url = "mod_seguridad_alimenticia/reportes/sega_reporte_environmental_test_excel.php?x1="+x1+"&x2="+x2+"&x3="+x3;
			$(location).attr('href',url);
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
	                	url: 'mod_seguridad_alimenticia/reportes/sega_reporte_environmental_test.php',
	                	type: 'POST',
	                	dataType: 'html',
	                	data: {
	                		fecha_inicial: $('#fecha_inicial').val(),
	                		fecha_final: $('#fecha_final').val(),
	                		cod_info_empresa: $('#cod_info_empresa').val()
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
    #dev-table > tbody tr td .fa-search
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
            <h1 class="translate" data-traducir_english="Environmental Test Report" data-traducir_spanish="Reporte de Pruebas Ambientales">Reporte de Pruebas Ambientales</h1>
        </div>
		<div class="row">
			<div class="col-md-3">
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
          	<div class="col-md-3">
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
        	<div class="col-md-4">
				<label for="cod_info_empresa" class="translate" data-traducir_english="Grower" data-traducir_spanish="Finca">Finca</label>
				<div class="form-group show-tick">
					<select class="selectpicker show-menu-arrow requerido" id="cod_info_empresa" name="cod_info_empresa" data-live-search="true" title="Select">
					</select>
				</div>
			</div>
          	<div class="col-md-2">
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
						<h3 class="panel-title translate" data-traducir_english="Environmental Tests" data-traducir_spanish="Pruebas Ambientales">Pruebas Ambientales</h3>
					</div>
					<!-- Table -->
					<div class="responsive_table_container">
						<table class="table table-responsive table-condensed table-striped" id="dev-table">
							<thead>
								<tr class="active info">
									<th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
									<th width="15%" class="translate" data-traducir_english="Grower" data-traducir_spanish="Finca">Finca</th>
									<th width="15%" class="translate" data-traducir_english="Location" data-traducir_spanish="Ubicación">Ubicación</th>
									<th width="15%" class="translate" data-traducir_english="Test" data-traducir_spanish="Prueba">Prueba</th>
									<th width="15%" class="translate" data-traducir_english="Sample" data-traducir_spanish="Muestra">Muestra</th>
									<th width="15%" class="translate" data-traducir_english="Sample Date" data-traducir_spanish="Fecha Muestra">Fecha Muestra</th>
									<th width="15%" class="translate" data-traducir_english="Geometric Mean" data-traducir_spanish="Calculo Geometrico">Calculo Geometrico</th>
								</tr>
							</thead>
							<tbody>
	                            <?PHP
	                            if (count($FORMULARIOS)) {
	                            	$correlativo = 1;
	                            	foreach ($FORMULARIOS as $formulario) {
	                            		?>
	                            		<tr>
	                            			<td><?php echo $correlativo; ?></td>
	                            			<td><a role="button" onclick="conf_cargar_registros_environmental_test('div_formulario<?php echo $correlativo; ?>',<?php echo $formulario['cod_info_empresa']; ?>, <?php echo $formulario['cod_location']; ?>, <?php echo $formulario['cod_type_test']; ?>, <?php echo $formulario['cod_source_phase']; ?>, <?php echo $formulario['cod_sample']; ?>, '<?php echo $formulario['sample_date']; ?>')" data-toggle="collapse" data-parent="#table-container" href="#collapse<?php echo $correlativo; ?>" aria-expanded="true" aria-controls="collapseOne" style="color: #000 !important;"><?php echo utf8_encode($formulario['nombre_empresa']); ?></a></td>
	                            			<td><?php echo utf8_encode($cod_location[$formulario['cod_location']]); ?></td>
	                            			<td><?php echo utf8_encode($cod_type_test[$formulario['cod_type_test']]); ?></td>
	                            			<!-- <td><?php echo utf8_encode($cod_source_phase[$formulario['cod_source_phase']]); ?></td> -->
	                            			<td><?php echo utf8_encode($cod_sample[$formulario['cod_sample']]); ?></td>
	                            			<td><?php echo utf8_encode($formulario['sample_date']); ?></td>
	                            			<td><?php echo round($formulario['result'], 2); ?></td>
	                            			<!-- <td><?php echo utf8_encode($formulario['nombre_usuario']); ?></td>
	                            			<td><?php echo $formulario['date_insert']; ?></td> -->
	                            		</tr>
                                        <tr>
                                            <td colspan="7" class="no-padding">
                                                <div id="collapse<?php echo $correlativo; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-md-12 col-sm-12" id="div_formulario<?php echo $correlativo; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
	                            		<?php
	                            		$correlativo++;
	                            	}
	                            }
	                            else
	                            {
	                            	?>
	                            	<tr>
	                            		<td colspan="5">No records</td>
	                            	</tr>
	                            	<?php
	                            }
	                            ?>
							</tbody>
						</table>
					</div>
				</div> <!-- Row -->
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" id="div_mi_formulario">
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
            <button id="excel"  name="excel" class="btn btn-sm btn-info main-actions hide smooth-transition" type="button" data-loading-text="Convirtiendo...">Excel</button>
        </div>
    </div>
</div> <!-- panel-footer -->