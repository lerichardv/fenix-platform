<?PHP
/*
 * Reporte general de ingresos al sistema
 * @author      Linda Zelaya
 * @date        2016-06-22
 */

session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: ../index.php');
}
?>

<script type="text/javascript">
	$(document).ready(function() {
		jQuery.ajaxSetup({async:false});
		grl_overlay_loading('Cargando información');

      var fecha_hoy   = new Date(<?php echo time()*1000; ?>);
	    var hoy         = new Date((fecha_hoy.getMonth()+1) + "/" + fecha_hoy.getDate() + "/" + fecha_hoy.getFullYear());
	    var hoy_18_anios_atras 		= new Date((fecha_hoy.getMonth()+1) + "/" + fecha_hoy.getDate() + "/" + (fecha_hoy.getFullYear()-18));
	    var hoy_default = new Date(fecha_hoy.getFullYear() + "-" + (fecha_hoy.getMonth()+1) + "-" + fecha_hoy.getDate());
		//Habilitación de listboxs
		$('.selectpicker').selectpicker({
			dropupAuto: 'true',
			container: 'body',
			size: '10',
			width: '85%',
			style: 'btn-sm btn-info has-btn-all',
            tickIcon: 'fa fa-check'
		});
		$(".selectpicker").selectpicker('refresh');

		//Habilita los selects para mobile
		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
				$('.selectpicker').selectpicker('mobile');
		}
		//constructor selectpicker
 	 usu_constructor_usuarios_todos();

	 /* Clic botón seleccionar/deseleccionar todos los estados*/
    	$("#btn_todos_usuarios").click(function(event){
    		event.stopPropagation();
    		if ($(this).hasClass('allselected')) {
    			$(this).removeClass('allselected btn-info').addClass('btn-danger');
    			$('#cod_usuarios option').prop('selected', false);
 		    $('#cod_usuarios').selectpicker('setStyle', 'btn-danger');
 			$('#cod_usuarios').selectpicker('setStyle', 'btn-info', 'remove');
    		}
    		else{
    			$(this).addClass('allselected btn-info').removeClass('btn-danger');
    			$('#cod_usuarios option').prop('selected', true);
 		    $('#cod_usuarios').selectpicker('setStyle', 'btn-danger', 'remove');
 			$('#cod_usuarios').selectpicker('setStyle', 'btn-info');
    		}
 	    $('#cod_usuarios').selectpicker('refresh');
    	});
 	/*----------------------------------------------------------------------------------
                                     Validando listboxs
     ----------------------------------------------------------------------------------*/
     $( "#cod_usuarios" ).change(function() {
 		var objeto = $(this);
 		if (objeto.val() == null || objeto.val() == '-b' || objeto.val() == ''){
 			objeto.selectpicker('setStyle', 'btn-info', 'remove');
 			objeto.selectpicker('setStyle', 'btn-danger');
 			$("#btn_todos_usuarios").addClass('btn-danger').removeClass('btn-info allselected');
 		} else {
 			objeto.selectpicker('setStyle', 'btn-danger', 'remove');
 			objeto.selectpicker('setStyle', 'btn-info');
 			$("#btn_todos_usuarios").removeClass('btn-danger').addClass('btn-info');
 		}
 		objeto.selectpicker('refresh');
 	});

		//Construcción de fechas
    $('#div_fecha_inicial').datetimepicker({
      locale: 'es',
      format: 'YYYY-MM',
      defaultDate: hoy,
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
     })
     $('#div_fecha_final').datetimepicker({
        locale: 'es',
        format: 'YYYY-MM',
        defaultDate: hoy,
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

		$('#div_fecha_inicial').change(function(){
                    $('#div_fecha_inicial').removeClass('has-error');
                    $('#div_fecha_inicial').addClass('has-default');
	  });
    	$('#div_fecha_final').change(function(){
                    $('#div_fecha_final').removeClass('has-error');
                    $('#div_fecha_final').addClass('has-default');
	   });
	    $('#modal_loading').modal('hide');
	    jQuery.ajaxSetup({async:true});
});

    /*función que crea el listado y el gráfico comparativo de los donantes y sus fondos invertidos*/
	function llenar_tabla(){
						var todo_bien = 0;
						if($('#cod_usuarios').val() != null && $('#cod_usuarios').val() != '-b'){
							todo_bien = 0;
						} else {
							todo_bien = 1;
						}

						if(todo_bien == 0)
						{
							jQuery.ajaxSetup({async:false});
							var cods_user = '';
							$('#cod_usuarios :selected').each(function(i, sel){
									if (cods_user == ''){
										cods_user = $(sel).val();
									}else{
										cods_user = cods_user + ',' + $(sel).val();
									}
							});
	            $.ajax({
	                    url: '../../mod_admin_usuarios/reportes/usu_reporte_detallado_ingresos_ajax.php',
	                    type: 'POST',
	                    //dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
	                    data:{
	                            x1: $('#fecha_inicial').val(),
	                            x2: $('#fecha_final').val(),
															x3: cods_user
	                    },
	                    success:function(data){
	                            $('.busqueda_contenedor').empty().append(data);
	                    }
	            });
						}
						else
						{
							grl_mensaje('Debe llenar los campos marcados. ','Favor verificar.','warning');
						}
	}
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
    .hide_col {
        display:none;
    }
	.btn-all-stick{
		width: 5%;
		float: left;
		display: inline-block;
		border-top-right-radius: 0px;
		border-bottom-right-radius: 0px;
		padding: 7px 0px 7px 0px;
	}
	.has-btn-all{
		border-top-left-radius: 0px;
		border-bottom-left-radius: 0px;
	}
	.check-all i{
		color: rgb(255, 255, 255);
	}
	.btn-info.check-all i{
		color: rgb(173, 223, 237);
	}
	.btn-danger.check-all i{
		color: rgb(223, 133, 130);
	}
	.allSelected i{
		color: rgb(255,255,255) !important;
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
		width: 100%;
	}
    .hide_col {
        display:none;
    }
</style>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <title></title>
</head>
<body>
	<div id="overlay_loading"></div>
	<div class="row">
		<div class="panel-body">
			<div class="page-header">
				<h1 class="translate" data-traducir_english="Detailed Report of Logins to the System by User" data-traducir_spanish="Reporte Detallado de Ingresos al Sistema por Usuario">Reporte Detallado de Ingresos al Sistema por Usuario</h1>
			</div>
		</div>
		<div class="row">
						<div class="col-md-3">
								<label class="translate" data-traducir_english="Users" data-traducir_spanish="Usuarios">Usuarios</label>
								<div class="form-group show-tick">
									<select class="selectpicker show-menu-arrow" multiple="multiple" data-live-search="true" id="cod_usuarios" name="cod_usuarios" title='Seleccione'>
									</select>
									<button class="btn btn-info btn-all-stick check-all has-tooltip" id="btn_todos_usuarios" title="Seleccionar todos" data-toggle="tooltip" type="button">
										<i class="fa fa-check"></i>
									</button>
								</div>
						</div>
						<div class="col-md-3">
								<div class="form-group input-group-sm date" id="">
												<label for="fecha_inicial" class="translate" data-traducir_english="Initial date" data-traducir_spanish="Fecha inicial">Fecha inicial</label>
												<div class='input-group input-group-sm date' id='div_fecha_inicial'>
																<span class="input-group-addon">
																				<span class="fa fa-calendar">
																</span>
																</span>
														<input type='text' class="form-control fechas_validacion"  id="fecha_inicial" />
												</div>
								</div>
						</div>
						<div class="col-md-3">
								<div class="form-group input-group-sm date" id="">
										<label for="fecha_final" class="translate" data-traducir_english="Final date" data-traducir_spanish="Fecha final">Fecha final</label>
										<div class="input-group input-group-sm date" id="div_fecha_final">
														<span class="input-group-addon">
																		<span class="fa fa-calendar"></span>
														</span>
														<input type="text" class="form-control limpiar fecha input requerido" id="fecha_final"  maxlength="10" autocomplete="off">
										</div>
								</div>
						</div>
						<div class="col-md-1">
							<div class="form-group input-group-sm" id="div_buscar">
								<center>
									<label for="buscar" class="translate" data-traducir_english="Search" data-traducir_spanish="Buscar">Buscar</label>
									<div class="form-group input-group-sm" id="div_buscar">
										<button class="btn btn-info" type="button" id="buscar" name="buscar" onclick="llenar_tabla();">
											<i class="fa fa-search"></i>
										</button>
									</div>
								</center>
							</div>
						</div>
		</div>
		<div class="busqueda_contenedor">
	    	<div class="row" style="overflow:auto; min-width:100px;">
				<div class="col-sm-12">
					<div class="panel panel-primary" style="overflow:auto;">
						<div class="panel-heading panel_cabecera">
							<h3 class="panel-title translate" data-traducir_english="Detailed List of System Login" data-traducir_spanish="Listado Detallado de Ingresos al Sistema">Listado Detallado de Ingresos al Sistema</h3>
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
																		<th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
																		<th width="10%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>
																		<th width="5%" class="translate" data-traducir_english="Month" data-traducir_spanish="Mes">Mes</th>
																		<th width="10%" class="translate" data-traducir_english="Logins" data-traducir_spanish="Ingresos">Ingresos</th>
																		<th width="10%" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha</th>
																		<th width="60%" class="translate" data-traducir_english="Hours and Terminals" data-traducir_spanish="Horas y Terminales">Horas y Terminales</th>
																</tr>
	                            </thead>
															<tbody id="cuerpo_tabla">
																	<tr>
																			<td colspan="6" align="center" class="translate" data-traducir_english="No data" data-traducir_spanish="No hay registros">No hay registros</td>
																	</tr>
															</tbody>
	                        </table>
		                </div>
					</div><!--/panel panel-primary -->
				</div><!-- /col-12 -->
			</div>
		</div> <!-- /busqueda_contenedor -->
	</div>
</body>
</html>
