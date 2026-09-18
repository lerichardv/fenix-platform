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
$cod_formulario    	= $_POST['cod_formulario'];
$cod_granjas_usuario = str_replace(['[','"',']'], '', $_SESSION['cod_info_empresa']);

if (isset($fecha_final)) {
	$FORMULARIOS = $DB_CONF->conf_obtener_listado_formularios_review($fecha_inicial, $fecha_final, $cod_info_empresa, $cod_formulario);
}

?>
<script type="text/javascript">
	var selected_forms = [];
	jQuery.ajaxSetup({async:false});
	$('#modal_loading').modal('hide');
	grl_overlay_loading('');
	$(document).ready(function(){
		jQuery.ajaxSetup({async:false});
		//Inicializar la barra de botones
        init_button_bar();
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

    	$('#cod_info_empresa').change(function(event) {
    		/* Act on the event */
    		conf_constructor_listado_formularios_por_granja('cod_formulario', $(this).val());
    	});

	    $('#fecha_inicial').val('<?php echo $fecha_inicial; ?>');
    	$('#fecha_final').val('<?php echo $fecha_final; ?>');

    	$('#cod_info_empresa').selectpicker('val', "<?php echo $cod_info_empresa; ?>");
    	$('#cod_formulario').selectpicker('val', "<?php echo $cod_formulario; ?>");

    	$('.selectpicker').selectpicker('refresh');
	    <?php if (count($FORMULARIOS) && isset($fecha_final))
	    {
	    	?>
	    	//$('#excel').removeClass('hide');
	    	$('#pdf').removeClass('hide');
			$('#dev-table').DataTable({
	            "dom": 'Bfrtip',
		        "buttons": [
		            {
		                extend: 'pdfHtml5',
		                orientation: 'landscape',
		                pageSize: 'Letter',
		                title: 'Forms Review',
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
				var url = "mod_reportes/funciones/rep_reporte_costos_excel.php?x1="+x1+"&x2="+x2;
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
	                	url: 'mod_seguridad_alimenticia/ui/sega_review_forms.php',
	                	type: 'POST',
	                	dataType: 'html',
	                	data: {
	                		fecha_inicial: $('#fecha_inicial').val(),
	                		fecha_final: $('#fecha_final').val(),
	                		cod_info_empresa: $('#cod_info_empresa').val(),
	                		cod_formulario: $('#cod_formulario').val()
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
	.checkbox_2
	{
		transform: scale(2);
	}
</style>
<div id="overlay_loading"></div>
<div id="message_box"></div>
<div class="panel panel-default">
  	<div class="panel-body">
        <div class="page-header">
            <h1 class="translate" data-traducir_english="Form Review" data-traducir_spanish="Revisión de Formulario">Revisión de Formulario</h1>
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
					<select class="selectpicker show-menu-arrow requerido" id="cod_info_empresa" name="cod_info_empresa" data-live-search="true" title="Select">
					</select>
				</div>
			</div>
        	<div class="col-md-3">
				<label for="cod_formulario" class="translate" data-traducir_english="Form" data-traducir_spanish="Formulario">Formulario</label>
				<div class="form-group show-tick">
					<select class="selectpicker show-menu-arrow requerido" id="cod_formulario" name="cod_formulario" data-live-search="true" title="Select">
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
						<h3 class="panel-title translate" data-traducir_english="Forms" data-traducir_spanish="Formularios">Formularios</h3>
					</div>
					<!-- Table -->
					<div class="responsive_table_container">
						<table class="table table-responsive table-condensed" id="dev-table">
							<thead>
								<tr class="active info">
									<th width="5%" class="translate" data-traducir_english="Select" data-traducir_spanish="Seleccionar">Seleccionar</th>
									<th width="20%" class="translate" data-traducir_english="Name" data-traducir_spanish="Nombre">Nombre</th>
									<th width="15%" class="translate" data-traducir_english="Stage" data-traducir_spanish="Estado">Estado</th>
									<th width="25%" class="translate" data-traducir_english="Observation" data-traducir_spanish="Observación">Observación</th>
									<th width="15%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>
									<th width="15%" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</th>
									<th width="5%" class="translate" data-traducir_english="Actions" data-traducir_spanish="Acciones">Acciones</th>
								</tr>
							</thead>
							<tbody>
	                            <?PHP
	                            if (count($FORMULARIOS)) {
	                            	$correlativo = 1;
	                            	$array_cod_detalle_form = '[';
	                            	foreach ($FORMULARIOS as $formulario) {
	                            		?>
	                            		<tr class="estado_<?php echo utf8_encode($formulario['nombre_estado']); ?> prioridad_<?php echo utf8_encode($formulario['prioridad']); ?>">
	                            			<!-- <td><?php echo $correlativo; ?></td> -->
	                            			<td align="center">
	                            				<input onclick="conf_seleccionar_formulario('div_review_formulario',[<?php echo $formulario['cod_detalle']; ?>],document.querySelector('#add_form_<?php echo $correlativo; ?>').checked ? 1 : 0)" type="checkbox" class="checkbox_2" name="add_form_<?php echo $correlativo; ?>" id="add_form_<?php echo $correlativo; ?>">
	                            			</td>
	                            			<td><?php echo utf8_encode($formulario['nombre_formulario']); ?></td>
	                            			<td><?php echo utf8_encode($formulario['nombre_estado']); ?></td>
	                            			<td><?php echo utf8_encode($formulario['observacion']); ?></td>
	                            			<td><?php echo utf8_encode($formulario['nombre_usuario']); ?></td>
	                            			<td><?php echo $formulario['date_insert']; ?></td>
	                            			<td>
	                            				<i class="fas fa-2x fa-search" onclick="conf_cargar_vista_revisar_formulario('div_review_formulario',<?php echo $formulario['cod_detalle']; ?>)"></i>
	                            			</td>
	                            		</tr>
	                            		<?php
	                            		$correlativo++;
	                            		$array_cod_detalle_form .= $formulario['cod_detalle'].',';
	                            	}
	                            	$array_cod_detalle_form = substr($array_cod_detalle_form, 0, -1);
	                            	$array_cod_detalle_form .= ']';
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
							<tfooter>
								<tr>
									<td align="center">
										<input onclick="conf_seleccionar_formulario('div_review_formulario',<?php echo $array_cod_detalle_form; ?>,$('#add_form_all').is(':checked') ? 1 : 0); $('#add_form_all').is(':checked') ? $('.checkbox_2').prop('checked',  true):$('.checkbox_2').prop('checked',  false);" type="checkbox" class="checkbox_2" name="add_form_all" id="add_form_all">
									</td>
									<td colspan="4">Select All</td>
								</tr>
							</tfooter>
						</table>
					</div>
				</div> <!-- Row -->
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" id="div_review_formulario">
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