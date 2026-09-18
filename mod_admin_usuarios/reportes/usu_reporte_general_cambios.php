<?PHP
/*
 * Reporte general de cambios al sistema
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
	function crear_grafico(){
            $.ajax({
                    url: '../../mod_admin_usuarios/reportes/usu_reporte_general_cambios_ajax.php',
                    type: 'POST',
                    //dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
                    data:{
                            x1: $('#fecha_inicial').val(),
                            x2: $('#fecha_final').val()
                    },
                    success:function(data){
                            $('.busqueda_contenedor').empty().append(data);
                    }
            });
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
				<h1  class="translate" data-traducir_english="General Report of Password Changes per Month" data-traducir_spanish="Reporte General de Cambios de Contraseña Mensual">Reporte General de Cambios de Contraseña Mensual</h1>
			</div>
		</div>
		<div class="row">
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
										<button class="btn btn-info" type="button" id="buscar" name="buscar" onclick="crear_grafico();">
											<i class="fa fa-search"></i>
										</button>
									</div>
								</center>
							</div>
						</div>
		</div>
		<div class="row" id="cuerpo_detalle">
					<div class="col-md-12" style="overflow-x:auto;">
								<div id="container1" style="min-width: 310px; height: 380px; margin: 0 auto;" align="center">
									<i class="fa fa-signal" style="; font-size:300px"></i>
								</div>
					</div>
		 </div>
		<div class="busqueda_contenedor">
	    	<div class="row" style="overflow:auto; min-width:100px;">
				<div class="col-sm-12">
					<div class="panel panel-primary" style="overflow:auto;">
						<div class="panel-heading panel_cabecera">
							<h3 class="panel-title translate" data-traducir_english="General List of Password Changes" data-traducir_spanish="Listado General de Cambios de Contraseñas">Listado General de Cambios de Contraseñas</h3>
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
	                                    <th width="10%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
	                                    <th width="30%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>
	                                    <th width="30%" class="translate" data-traducir_english="Month" data-traducir_spanish="Mes">Mes</th>
	                                    <th width="30%" class="translate" data-traducir_english="Changes" data-traducir_spanish="Cambios">Cambios</th>
	                                </tr>
	                            </thead>
															<tbody id="cuerpo_tabla">
																	<tr>
																			<td colspan="4" align="center" class="translate" data-traducir_english="No data" data-traducir_spanish="No hay registros">No hay registros</td>
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
