<?PHP
/*
 * Reporte de consolidados de semillas
 * @author      Dan Urquia
 * @date        2023-11-15
 */

session_start();
if (!isset($_SESSION['cod_usuario'])) {
	header('Location: ../index.php');
}
?>

<script type="text/javascript">
	$(document).ready(function() {
		jQuery.ajaxSetup({
			async: false
		});
		grl_overlay_loading('Loading');
		// Obtiene la fecha actual
		var fechaTreintaDiasMas = new Date();
		// Resta 30 días a la fecha actual
		fechaTreintaDiasMas.setDate(fechaTreintaDiasMas.getDate() + 30);
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
		if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
			$('.selectpicker').selectpicker('mobile');
		}

		//Contructor selectpicker 
		//conf_constructor_listado_estados_de_plantacion();
		//Constructores
		conf_constructor_listado_granjas('cod_info_empresa');
		inv_constructor_grupo_proveedores();
		//conf_constructor_listado_sembradores();
		inv_constructor_tipo_semilla();


		$("#cod_info_empresa option[value='-b']").remove();
		$("#cod_tipo_semilla option[value='-b']").remove();
		//Construcción de fechas
		$('#div_fecha_inicial').datetimepicker({
			locale: 'en',
			format: 'YYYY-MM-DD',
			defaultDate: new Date(),
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
		})
		$('#div_fecha_final').datetimepicker({
			locale: 'en',
			format: 'YYYY-MM-DD',
			defaultDate: fechaTreintaDiasMas,
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

		$('#div_fecha_inicial').change(function() {
			$('#div_fecha_inicial').removeClass('has-error');
			$('#div_fecha_inicial').addClass('has-default');
		});
		$('#div_fecha_final').change(function() {
			$('#div_fecha_final').removeClass('has-error');
			$('#div_fecha_final').addClass('has-default');
		});
		$('#modal_loading').modal('hide');
		jQuery.ajaxSetup({
			async: true
		});
	});

	function crear_grafico() {
		//alert($('#fecha_inicial').val() + ' == ' + $('#fecha_final').val() + ' == ' + $('#cod_info_empresa').val() + ' == ' + $('#cod_proveedor').val() + ' == ' + $('#cod_tipo_semilla').val());

		$.ajax({
			url: '../../mod_inventario/reportes/inv_reporte_consolidado_seeds_ajax.php',
			type: 'POST',
			//dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
			data: {
				x1: $('#fecha_inicial').val(),
				x2: $('#fecha_final').val(),
				x3: $('#cod_info_empresa').val(),
				x4: $('#cod_proveedor').val(),
				x5: $('#cod_tipo_semilla').val()
			},
			success: function(data) {
				console.log({data})
				$('.busqueda_contenedor').empty().append(data);
			},
			error: function(dataError){
				console.log({dataError})

			}
		});
	}
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

	.hide_col {
		display: none;
	}

	.btn-all-stick {
		width: 5%;
		float: left;
		display: inline-block;
		border-top-right-radius: 0px;
		border-bottom-right-radius: 0px;
		padding: 7px 0px 7px 0px;
	}

	.has-btn-all {
		border-top-left-radius: 0px;
		border-bottom-left-radius: 0px;
	}

	.check-all i {
		color: rgb(255, 255, 255);
	}

	.btn-info.check-all i {
		color: rgb(173, 223, 237);
	}

	.btn-danger.check-all i {
		color: rgb(223, 133, 130);
	}

	.allSelected i {
		color: rgb(255, 255, 255) !important;
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
		width: 100%;
	}

	.hide_col {
		display: none;
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
				<h1 class="translate" data-traducir_english="Untreated-Organic Seed Report" data-traducir_spanish="Reporte de Semillas No Tratadas y Orgánicas">Reporte de Semillas No Tratadas y Orgánicas</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-md-2">
				<div class="form-group input-group-sm date" id="">
					<label for="fecha_inicial" class="translate" data-traducir_english="Initial order date" data-traducir_spanish="Fecha de orden inicial">Fecha de orden inicial</label>
					<div class='input-group input-group-sm date' id='div_fecha_inicial'>
						<span class="input-group-addon">
							<span class="fa fa-calendar">
							</span>
						</span>
						<input type='text' class="form-control fechas_validacion" id="fecha_inicial" />
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group input-group-sm date" id="">
					<label for="fecha_final" class="translate" data-traducir_english="Final order date" data-traducir_spanish="Fecha de orden final">Fecha de orden final</label>
					<div class="input-group input-group-sm date" id="div_fecha_final">
						<span class="input-group-addon">
							<span class="fa fa-calendar"></span>
						</span>
						<input type="text" class="form-control limpiar fecha input requerido" id="fecha_final" maxlength="10" autocomplete="off">
					</div>
				</div>
			</div>

			<div class="col-md-2">
				<div class="form-group input-group-sm">
					<label for="cod_info_empresa" class="translate" data-traducir_english="Ship to Location" data-traducir_spanish="Lugar de envío">Lugar de envío</label>
					<select class="selectpicker show-menu-arrow requerido_envio cod_info_empresa" multiple="multiple" data-actions-box="true" data-live-search="true" title="Select" id="cod_info_empresa" name="cod_info_empresa">
					</select>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group input-group-sm">
					<label for="cod_proveedor" class="translate" data-traducir_english="Vendor" data-traducir_spanish="Proveedor">Proveedor</label>
					<select class="selectpicker show-menu-arrow requerido_envio cod_info_empresa" multiple="multiple" data-actions-box="true" data-live-search="true" title="Select" id="cod_proveedor" name="cod_proveedor">
					</select>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group input-group-sm">
					<label for="cod_tipo_semilla" class="translate" data-traducir_english="Seed type" data-traducir_spanish="Tipo de semilla">Tipo de semilla</label>
					<select class="selectpicker show-menu-arrow requerido" data-live-search="true" multiple="multiple" data-actions-box="true" title="Select" id="cod_tipo_semilla" name="cod_tipo_semilla">
					</select>
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
		<div class="busqueda_contenedor">
			<div class="row" style="overflow:auto; min-width:100px;">
				<div class="col-sm-12">
					<div class="panel panel-primary" style="overflow:auto;">
						<div class="panel-heading panel_cabecera">
							<h3 class="panel-title translate" data-traducir_english="Untreated-Organic Seed List" data-traducir_spanish="Listado de Semillas No Tratadas y Orgánicas">Listado de Semillas No Tratadas y Orgánicas</h3>
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
										<th width="100%" class="translate" data-traducir_english="" data-traducir_spanish=""></th>
									</tr>
								</thead>
								<tbody id="cuerpo_tabla">
									<tr>
										<td colspan="1" align="center" class="translate" data-traducir_english="No data" data-traducir_spanish="No hay registros">No hay registros</td>
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