<?php
/*
* 	Registro de información de los cuartos fríos
* 	@author 		Jairo Bonilla
* 	@date 			2019-01-10
*/
session_start();/*
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}*/
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_control_calidad.php");
/*INSTANCIAMIENTOS*/
$CONTROL = new db_control_calidad();

$cod_departamento = $_POST['cod_departamento'];
if (!isset($_POST['cod_departamento'])) {
	$cod_departamento = $_SESSION['cod_departamento'];
}
$cod_pais = $_POST['cod_pais'];
if (!isset($_POST['cod_pais'])) {
	$cod_pais = $_SESSION['cod_pais'];
}
$CUARTOS = $CONTROL->qua_graficos_control_calidad($cod_pais,$cod_departamento);
$CUARTOSF = $CONTROL->qua_listado_cuartos_frios_por_pais_departamento($cod_pais,$cod_departamento);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Nueva capacitación</title>
</head>

<script type="text/javascript">
	jQuery.ajaxSetup({async:false});
	grl_overlay_loading('');

	$(document).ready(function() {
		codigo_cuarto_frio = 0;
        $('.selectpicker').selectpicker({
            dropupAuto: 'true',
            container: 'body',
            size: '5',
            width: '100%',
            style: 'btn-sm btn-info'
        });
        //Habilita los selects para mobile
	    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	        $('.selectpicker').selectpicker('mobile');
	    }
        //$('html, body').animate({ scrollTop: 0 }, 0);
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
									var no_results = $('<tr class="filterTable_no_results"><td colspan="4">No hay resultados.</td></tr>')
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
		//Constructores
		conf_constructor_listado_granjas();
		//Máscaras
	    $('.monto').mask("#,##0.000", {reverse: true, maxlength: false});
	    $('.letras').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z-¡!¿?,.() 0-9 ÁÉÍÓÚáéúíóúÑñÄËÏÖÜäëïöüÀÈÌÒÙàèìòùÃÕãõçÇÂâÊêÎîÔôÛû]/, optional: false}}});
	    $('.telefono').mask('(999) 999-9999');
	    grl_constructor_paises();
	    $( "#cod_pais" ).change(function() {
			$("#cod_departamento").empty();
			grl_constructor_departamentos_por_pais();
		});
		$('#cod_pais').trigger('change');
	    /*----------------------------------------------------------------------------------
    								Validando listboxs
	    ----------------------------------------------------------------------------------*/
		$('.selectpicker.requerido').change(function(event) {
			var objeto = $(this);
			if (objeto.val() == null || objeto.val() == '-b' || objeto.val() == ''){
				objeto.selectpicker('setStyle', 'btn-info', 'remove');
				objeto.selectpicker('setStyle', 'btn-danger');
			} else {
				objeto.selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');
				objeto.selectpicker('setStyle', 'btn-info');
				objeto.removeClass('campo-vacio');
			}
			objeto.selectpicker('refresh');
		});
		/*----------------------------------------------------------------------------------
	    					Validación input, textarea requeridos
	    ----------------------------------------------------------------------------------*/
	    $('.input.requerido').keyup(function(event) {
	    	if( $(this).val().trim() != "" ){
				$(this).parent('div').removeClass('has-error');
			}
			else{
				$(this).parent('div').addClass('has-error');
			}
	    });
	    <?php
	    /*if (count($CUARTO))
	    {
	    	?>
	    	$('#nombre_cuarto').val("<?php echo utf8_encode($CUARTO[0]['nombre_cuarto']); ?>");
	    	$('#cod_pais').selectpicker('val',"<?php echo utf8_encode($CUARTO[0]['cod_pais']); ?>");
	    	$('#cod_pais').trigger('change');
	    	$('#cod_departamento').selectpicker('val',"<?php echo utf8_encode($CUARTO[0]['cod_departamento']); ?>");
	    	$('.selectpicker').selectpicker('refresh');
	    	<?php
	    }*/
	    ?>
	    /*codigo_cuarto_frio = <?php echo $cod_cuarto_frio;?>;*/
	    Highcharts.chart('div_grafico', {
		    chart: {
		        type: 'spline',
		        inverted: true
		    },
		    title: {
		        text: 'Dashboard Report'
		    },
		    subtitle: {
		        text: 'Rooms with time versus temperature'
		    },
		    xAxis: {
		        reversed: false,
		        title: {
		            enabled: true,
		            text: 'Time'
		        },
		        labels: {
		            format: '{value} hours'
		        },
		        maxPadding: 0.05,
		        showLastLabel: true
		    },
		    yAxis: {
		        title: {
		            text: 'Temperature'
		        },
		        labels: {
		            format: '{value}'
		        },
		        lineWidth: 2
		    },
		    legend: {
		        enabled: true
		    },
		    tooltip: {
		        headerFormat: '<b>{series.name}</b><br/>',
		        pointFormat: '{point.x} hour: {point.y} ºF'
		    },
		    plotOptions: {
		        spline: {
		            marker: {
		                enable: false
		            }
		        }
		    },
		    series: [
			    <?php
			    foreach ($CUARTOS as $cuarto) {
			    	?>
			    	{
			    		name: "<?php echo utf8_encode($cuarto['nombre_cuarto'].'-'.$cuarto['nombre_seccion']); ?>",
			    		data: [<?php echo utf8_encode($cuarto['temperatura_cuarto']); ?>],
			    	},
			    	<?php
			    }
				?>
			]
		});
		/*Highcharts.chart('div_grafico2', {
		    chart: {
		        type: 'gauge',
		        plotBackgroundColor: null,
		        plotBackgroundImage: null,
		        plotBorderWidth: 0,
		        plotShadow: false
		    },

		    title: {
		        text: 'Room Test-Seccion 1'
		    },

		    pane: {
		        startAngle: -150,
		        endAngle: 150,
		        background: [{
		            backgroundColor: {
		                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
		                stops: [
		                    [0, '#FFF'],
		                    [1, '#333']
		                ]
		            },
		            borderWidth: 0,
		            outerRadius: '109%'
		        }, {
		            backgroundColor: {
		                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
		                stops: [
		                    [0, '#333'],
		                    [1, '#FFF']
		                ]
		            },
		            borderWidth: 1,
		            outerRadius: '107%'
		        }, {
		            // default background
		        }, {
		            backgroundColor: '#DDD',
		            borderWidth: 0,
		            outerRadius: '105%',
		            innerRadius: '103%'
		        }]
		    },

		    // the value axis
		    yAxis: {
		        min: 0,
		        max: 200,

		        minorTickInterval: 'auto',
		        minorTickWidth: 1,
		        minorTickLength: 10,
		        minorTickPosition: 'inside',
		        minorTickColor: '#666',

		        tickPixelInterval: 30,
		        tickWidth: 2,
		        tickPosition: 'inside',
		        tickLength: 10,
		        tickColor: '#666',
		        labels: {
		            step: 2,
		            rotation: 'auto'
		        },
		        title: {
		            text: 'ºF'
		        },
		        plotBands: [{
		            from: 0,
		            to: 120,
		            color: '#55BF3B' // green
		        }, {
		            from: 120,
		            to: 160,
		            color: '#DDDF0D' // yellow
		        }, {
		            from: 160,
		            to: 200,
		            color: '#DF5353' // red
		        }]
		    },

		    series: [{
		        name: 'Temperature',
		        data: [80],
		        tooltip: {
		            valueSuffix: ' ºF'
		        }
		    }]

		},
		// Add some life
		function (chart) {
		    if (!chart.renderer.forExport) {
		        setInterval(function () {
		            var point = chart.series[0].points[0],
		                newVal,
		                inc = Math.round((Math.random() - 0.5) * 20);

		            newVal = point.y + inc;
		            if (newVal < 0 || newVal > 200) {
		                newVal = point.y - inc;
		            }

		            point.update(newVal);

		        }, 3000);
		    }
		});*/
		setInterval(function () {
			qua_vista_graficos(<?php echo $cod_pais,',',$cod_departamento;?>);
		},1200000);
	    $('#modal_loading').modal('hide');
	    jQuery.ajaxSetup({async:true});
	});
</script>

<body>
	<div id="overlay_loading"></div>
	<div id="message_box"></div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="panel-header">
	                <h1 class="translate" data-traducir_english="Graphics" data-traducir_spanish="Gráficos">Gráficos</h1>
	            </div>
			</div>
            <div class="col-md-12">            	
				<div class="row">
					<div class="col-md-12" id="div_grafico" align="center">
						<i class="fa fa-signal" style="font-size: 300px;"></i>
					</div>
				</div>
            </div>
            <?php
            foreach ($CUARTOSF as $cuarto) 
            {
            	$SECCIONES = $CONTROL->qua_obtener_listado_secciones_cuarto_frio($cuarto['cod_cuarto']);
            	foreach ($SECCIONES as $seccion) 
            	{
            		$TEMP = $CONTROL->qua_graficos_temperatura_actual_seccion_cuarto_frio($_SESSION['cod_pais'],$_SESSION['cod_departamento'],$cuarto['cod_cuarto'],$seccion['cod_seccion']);
            		?>
            		<div class="col-md-3">
						<div class="col-md-12" id="div_grafico_seccion<?php echo $seccion['cod_seccion'];?>" align="center">
							<i class="fa fa-signal" style="font-size: 300px;"></i>
						</div>
		            </div>
		            <script type="text/javascript">
		            	Highcharts.chart('div_grafico_seccion<?php echo $seccion['cod_seccion'];?>', {
						    chart: {
						        type: 'gauge',
						        plotBackgroundColor: null,
						        plotBackgroundImage: null,
						        plotBorderWidth: 0,
						        plotShadow: false
						    },

						    title: {
						        text: '<?php echo utf8_encode($cuarto['nombre_cuarto']."-".$seccion['nombre_seccion']);?>'
						    },

						    pane: {
						        startAngle: -150,
						        endAngle: 150,
						        background: [{
						            backgroundColor: {
						                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
						                stops: [
						                    [0, '#FFF'],
						                    [1, '#333']
						                ]
						            },
						            borderWidth: 0,
						            outerRadius: '109%'
						        }, {
						            backgroundColor: {
						                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
						                stops: [
						                    [0, '#333'],
						                    [1, '#FFF']
						                ]
						            },
						            borderWidth: 1,
						            outerRadius: '107%'
						        }, {
						            // default background
						        }, {
						            backgroundColor: '#DDD',
						            borderWidth: 0,
						            outerRadius: '105%',
						            innerRadius: '103%'
						        }]
						    },

						    // the value axis
						    yAxis: {
						        min: 0,
						        max: 200,

						        minorTickInterval: 'auto',
						        minorTickWidth: 1,
						        minorTickLength: 10,
						        minorTickPosition: 'inside',
						        minorTickColor: '#666',

						        tickPixelInterval: 30,
						        tickWidth: 2,
						        tickPosition: 'inside',
						        tickLength: 10,
						        tickColor: '#666',
						        labels: {
						            step: 2,
						            rotation: 'auto'
						        },
						        title: {
						            text: 'ºF'
						        },
						        plotBands: [{
						            from: 0,
						            to: 120,
						            color: '#55BF3B' // green
						        }, {
						            from: 120,
						            to: 160,
						            color: '#DDDF0D' // yellow
						        }, {
						            from: 160,
						            to: 200,
						            color: '#DF5353' // red
						        }]
						    },

						    series: [{
						        name: 'Temperature',
						        data: [<?php echo $TEMP[0]['temperatura_cuarto'];?>],
						        tooltip: {
						            valueSuffix: ' ºF'
						        }
						    }]

						},);
		            </script>
            		<?php            		
            	}
            }
            ?>
		</div>
		<div class="panel-footer" align="right">
			<!-- <button class="btn btn-sm btn-primary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" type="button" id="btn_guardar_cuarto_frio">Guardar</button> -->
		</div>
	</div>
</body>