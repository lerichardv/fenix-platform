<?php
/*
* 	Registro de información de los cuartos fríos
* 	@author 		Jairo Bonilla
* 	@date 			2019-01-10
*/
session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_control_calidad.php");
/*INSTANCIAMIENTOS*/
$CONTROL = new db_control_calidad();


//$CUARTOS = $CONTROL->inv_listado_proveedores();
$CUARTOS = $CONTROL->qua_listado_cuartos_frios_por_pais_departamento($_SESSION['cod_pais'],$_SESSION['cod_departamento']);

$cod_cuarto_frio = $_POST['cod_cuarto_frio'];
if (!isset($_POST['cod_cuarto_frio'])) {
	$cod_cuarto_frio = 0;
}
$CUARTO = $CONTROL->qua_obtener_info_cuarto_frio($cod_cuarto_frio);
$SECCIONES = $CONTROL->qua_obtener_listado_secciones_cuarto_frio($cod_cuarto_frio);
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
	    if (count($CUARTO))
	    {
	    	?>
	    	$('#nombre_cuarto').val("<?php echo utf8_encode($CUARTO[0]['nombre_cuarto']); ?>");
	    	$('#cod_pais').selectpicker('val',"<?php echo utf8_encode($CUARTO[0]['cod_pais']); ?>");
	    	$('#cod_pais').trigger('change');
	    	$('#cod_departamento').selectpicker('val',"<?php echo utf8_encode($CUARTO[0]['cod_departamento']); ?>");
	    	$('.selectpicker').selectpicker('refresh');
	    	<?php
	    }
	    if (count($CUARTOS)) 
	    {
	    	?>
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
		                pageSize: 'Letter',
		                title: 'Cold Rooms',
		            },
		            'print',
		        ]
	        });
	    	<?php
	    }
	    ?>
	    codigo_cuarto_frio = <?php echo $cod_cuarto_frio;?>;
	    $('#modal_loading').modal('hide');
	    jQuery.ajaxSetup({async:true});
	});

	$('#btn_guardar_cuarto_frio').click(function(event) {
         /* Act on the event */
        var error = 0;
        $(".input.requerido").map(function(){
            if( !$(this).val() )
            {
                error = 1;
                $(this).parent('div').addClass('has-error');
                return false;
            }
            else
            {
                $(this).parent('div').removeClass('has-error');
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
                qua_guardar_cuarto_frio(codigo_cuarto_frio);
            });
        }
        else
        {
            grl_mensaje('Debe llenar todos los campos marcados','favor verificar','warning');
        }
    });

	$('.checkbox').change(function(event) {
		/* Act on the event */
		event.preventDefault();
		event.stopPropagation();
		qua_cambiar_estado_cuarto_frio($(this).data('id'),($(this).attr('checked') ? 0 : 1));
	});

	$('.checkbox_seccion').change(function(event) {
		/* Act on the event */
		event.preventDefault();
		event.stopPropagation();
		qua_cambiar_estado_seccion($(this).data('id'),($(this).attr('checked') ? 0 : 1), codigo_cuarto_frio);
	});

	$('#btn_add_seccion').click(function(event) {
		/* Act on the event */
		$('#modal_seccion').modal('show');
	});

	$('#btn_guardar_seccion').click(function(event) {
         /* Act on the event */        
        var error = 0;
        $(".input.requerido_seccion").map(function(){
            if( !$(this).val() )
            {
                error = 1;
                $(this).parent('div').addClass('has-error');
                return false;
            }
            else
            {
                $(this).parent('div').removeClass('has-error');
            }
        });
        $(".selectpicker.requerido_seccion").map(function(){
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
        	jQuery.ajaxSetup({async:false});
        	$('#modal_seccion').modal('hide');
            grl_overlay_loading('');
            $('#modal_loading').modal('hide');
            $('#modal_loading').on('hidden.bs.modal', function () {
                qua_guardar_seccion(codigo_cuarto_frio);
            });
            jQuery.ajaxSetup({async:true});
        }
        else
        {
            grl_mensaje('Debe llenar todos los campos marcados','favor verificar','warning');
        }
    });
</script>

<body>
	<div id="overlay_loading"></div>
	<div id="message_box"></div>
	<div class="busqueda_contenedor">
	    <div class="row" style="min-width:100px;">
	        <div class="col-sm-12">
	                <div class="panel panel-primary" style="overflow:auto;">
	                <div class="panel-heading panel_cabecera">
	                        <h3 class="panel-title translate" data-traducir_english="Rooms List" data-traducir_spanish="Listado de Cuartos Fríos">Listado de Cuartos Fríos</h3>
							<div class="pull-right">
								<span class="clickable filter" data-toggle="tooltip" title="Realizar busqueda" data-container="body">
									<i class="fa fa-search"></i>
								</span>
							</div>
	                </div>
					<div class="panel-body panel_cuerpo input-group-sm">
						<input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Busqueda" />
					</div>
	                <div style="overflow-x:auto;">
	                    <table class="table table-condensed display table-striped" id="dev-table" >
	                        <thead>
	                                <tr class="active info">
	                                        <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
	                                        <th width="40%" class="translate" data-traducir_english="Room Name" data-traducir_spanish="Nombre Cuarto Frío">Nombre Cuarto Frío</th>
	                                        <th width="15%" class="translate" data-traducir_english="Country" data-traducir_spanish="País">País</th>
	                                        <th width="15%" class="translate" data-traducir_english="State" data-traducir_spanish="Estado">Estado</th>
	                                        <th width="15%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>
	                                        <th width="5%" class="translate" data-traducir_english="Active" data-traducir_spanish="Activo">Activo</th>
	                                </tr>
	                        </thead>
	                        <tbody id="cuerpo_tabla">
	                            <?php
	                            if(count($CUARTOS))
	                            {
	                            	$correlativo = 1;
	                            	foreach($CUARTOS as $cuarto)
	                            	{
	                            		?>
	                            		<tr>
	                            			<!-- <td class="hide"><?php echo utf8_encode($cuarto['cod_cuarto']);?></td> -->
	                            			<td onclick="qua_vista_cuarto_frio(<?php echo utf8_encode($cuarto['cod_cuarto']);?>)"><?php echo $correlativo;?></td>
	                            			<td onclick="qua_vista_cuarto_frio(<?php echo utf8_encode($cuarto['cod_cuarto']);?>)"><?php echo utf8_encode($cuarto['nombre_cuarto']);?></td>
	                            			<td onclick="qua_vista_cuarto_frio(<?php echo utf8_encode($cuarto['cod_cuarto']);?>)"><?php echo utf8_encode($cuarto['pais']);?></td>
	                            			<td onclick="qua_vista_cuarto_frio(<?php echo utf8_encode($cuarto['cod_cuarto']);?>)"><?php echo utf8_encode($cuarto['departamento']);?></td>
	                            			<td onclick="qua_vista_cuarto_frio(<?php echo utf8_encode($cuarto['cod_cuarto']);?>)"><?php echo utf8_encode($cuarto['nombre_usuario']);?></td>
	                            			<td>
	                            				<div class="material-switch pull-right">
						                            <input class="checkbox" id="checkbox_<?php echo utf8_encode($cuarto['cod_cuarto']);?>" data-id="<?php echo utf8_encode($cuarto['cod_cuarto']);?>" name="checkbox_<?php echo utf8_encode($cuarto['cod_cuarto']);?>" type="checkbox" <?php echo ($cuarto['activo'] == 1 ? 'checked="checked"':'');?>/>
						                            <label for="checkbox_<?php echo utf8_encode($cuarto['cod_cuarto']);?>" class=""></label>
						                        </div>
	                            			</td>
	                            		</tr>
	                            		<?php
	                            		$correlativo++;
	                            	}
	                            }
	                            ?>
	                        </tbody>
	                    </table>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="panel-header">
	                <h1 class="translate" data-traducir_english="Room" data-traducir_spanish="Cuarto Frío">Cuarto Frío</h1>
	            </div>
			</div>
            <div class="col-md-12">
            	<div class="row">
	            	<div class="col-md-4">
	            		<div class="form-group input-group-sm">
                            <label for="nombre_cuarto" class="translate" data-traducir_english="Room Name" data-traducir_spanish="Nombre Cuarto">Nombre Cuarto</label>
                            <input type="text"class="form-control letras input requerido"  id="nombre_cuarto" name="nombre_cuarto">
                        </div>
	            	</div>
	            	<div class="col-md-4">
						<label for="cod_pais" class="translate" data-traducir_english="Country" data-traducir_spanish="País">País</label>
						<div class="form-group show-tick">
							<select class="selectpicker show-menu-arrow requerido" id="cod_pais" name="cod_pais" data-live-search="true" title="Select">
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<label for="cod_departamento" class="translate" data-traducir_english="State" data-traducir_spanish="Estado">Estado</label>
						<div class="form-group show-tick">
							<select class="selectpicker show-menu-arrow requerido" id="cod_departamento" name="cod_departamento" data-live-search="true" title="Select">
							</select>
						</div>
					</div>
				</div>
				<div class="row">
	            	<div class="col-md-12">
	            		<div class="panel panel-primary" id="panel_itemschecklist">
		                    <div class="panel-heading btncollapsepaso"id="a_itemschecklist" role="button" data-toggle="collapse" href="#collapseitemschecklist" aria-expanded="true" data-parent="#accordion" aria-controls="collapseitemschecklist">
		                        <h3 class="panel-title translate" data-traducir_english='Room Sections' data-traducir_spanish='Secciones del Cuarto Frío'>Secciones del Cuarto Frio</h3>
		                    </div>
		                    <div class="panel-collapse collapse" id="collapseitemschecklist" role="tabpanel" aria-labelledby="collapseitemschecklist">
		                        <div class="panel-body">
		                            <div class="responsive_table_container">
		                                <table class="table display row-border responsive" id="tabla_itemschecklist">
		                                    <thead>
		                                         <tr class="active info">
		                                            <th width="5%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
		                                            <th width="50%" class="translate" data-traducir_english="Section Name" data-traducir_spanish="Nombre Sección">Nombre Sección</th>
		                                            <th width="30%" class="translate" data-traducir_english="User" data-traducir_spanish="Usuario">Usuario</th>
		                                            <th width="10%" class="translate" data-traducir_english="Active" data-traducir_spanish="Activo">Activo</th>
		                                         </tr>
		                                    </thead>
		                                    <tbody>
				                            <?php
				                            if(count($SECCIONES))
				                            {
				                            	$correlativo = 1;
				                            	foreach($SECCIONES as $seccion)
				                            	{
				                            		?>
				                            		<tr>
				                            			<td><?php echo $correlativo;?></td>
				                            			<td><?php echo utf8_encode($seccion['nombre_seccion']);?></td>
				                            			<td><?php echo utf8_encode($seccion['nombre_usuario']);?></td>
				                            			<td>
				                            				<div class="material-switch pull-right">
									                            <input class="checkbox_seccion" id="checkboxp_<?php echo utf8_encode($seccion['cod_seccion']);?>" data-id="<?php echo utf8_encode($seccion['cod_seccion']);?>" name="checkboxp_<?php echo utf8_encode($seccion['cod_seccion']);?>" type="checkbox" <?php echo ($seccion['activo'] == 1 ? 'checked="checked"':'');?>/>
									                            <label for="checkboxp_<?php echo utf8_encode($seccion['cod_seccion']);?>" class=""></label>
									                        </div>
				                            			</td>
				                            		</tr>
				                            		<?php
				                            		$correlativo++;
				                            	}
				                            }
				                            ?>
		                                    </tbody>
		                                 </table>
		                            </div>
		                            <div class="row nomargin row-add-content">
		                                <div class="col-xs-12 text-center">
		                                    <button type="button" id="btn_add_seccion" class="btn btn-sm btn-success truncated-text btn_add" data-action="true">
		                                        <i class="fa fa-plus-circle"> </i>
		                                        <span class="translate" data-traducir_english="Add Section" data-traducir_spanish="Añadir Sección">Añadir Sección</span>
		                                    </button>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
	            	</div>
	            </div>
            </div>
		</div>
		<div class="panel-footer" align="right">
			<button class="btn btn-sm btn-primary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" type="button" id="btn_guardar_cuarto_frio">Guardar</button>
		</div>
	</div>
	<div class="modal fade modal_seccion" id="modal_seccion" tabindex="-1" role="dialog" aria-labelledby="ModalResponsables" aria-hidden="true">
		<div class="modal-dialog modal-success modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title"><span id="titulo_modal_responsables" class="translate" data-traducir_english="Section" data-traducir_spanish="Sección">Sección</span> <small class="subtitulo" id="subtitulo_modal_responsables"></small></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="form-group input-group-sm col-xs-12">
                            <label for="nombre_seccion" class="translate" data-traducir_english="Section Name" data-traducir_spanish="Nombre Sección">Nombre Sección</label>
                            <input type="text"class="form-control letras input requerido_seccion"  id="nombre_seccion" name="nombre_seccion">
		            	</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-sm btn-default translate" data-traducir_english="Close" data-traducir_spanish="Cerrar" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-sm btn-primary btn_guardar translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_guardar_seccion">Guardar</button>
				</div>
			</div>
		</div>
	</div>
</body>