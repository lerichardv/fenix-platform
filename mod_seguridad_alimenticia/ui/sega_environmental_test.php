<?PHP
/*
 * .
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
$cod_granjas_usuario = str_replace(['[','"',']'], '', $_SESSION['cod_info_empresa']);

$codigo_environmental_test = $_POST['codigo_environmental_test'];
if (!isset($_POST['codigo_environmental_test'])) {
	$codigo_environmental_test = 0;
}
?>
<script type="text/javascript">
	var selected_forms = [];
	grl_overlay_loading('');var array_file_list = [];
	array_file_list.push({id:0,file:'',file_ext:''});
	var $container_upload_box = $("#container_upload_box");
	var files_adjunto = '';
	var nombre_adjunto = '';
	var ext_adjunto = '';

	function constructor_file_input($container_upload_box){
	    $container_upload_box.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file');
	    $container_upload_box.find('.btn-delete-file').removeClass().addClass('smooth-transition btn-delete-file ready-to-upload');
	    $container_upload_box.find('.btn-delete-file').data('disabled',false);
	    $container_upload_box.find('i.icon-file').removeClass().addClass('icon-file fa fa-file');
	    $container_upload_box.find('span.msj-btn-file').text(' Seleccione archivo');
	    $container_upload_box.find('.btn-view-file').addClass('hide');
	    $container_upload_box.find("input[type=file]").replaceWith(
	    $container_upload_box.find("input[type=file]").val('').clone( true ));
	    $container_upload_box.find("input[type=file]").attr('title', 'Seleccione un Archivo');
	    files 		= '';
	    file_ext 	= '';
	    files_adjunto 	= '';
	    var id 		= $container_upload_box.find('.btn-select-file').data('id');
	    update_array_file_list(id, files, file_ext);
	}

	function prepare_upload2(event)
	{
        var $container_upload_box = $(this).parent();
        files_adjunto = event.target.files;
        $.each(files_adjunto, function(index, adjunto) {
        	 /* iterate through array or object */
	        var cancel_button_is_clicked = adjunto;
	        if( cancel_button_is_clicked == undefined ){
	            constructor_file_input($(this).parent());
	        }
	        else
	        {
	            var id = $container_upload_box.find('.btn-select-file').data('id');
	            var filesize =  adjunto.size/1024/1024;
	       		console.log(filesize);
	            if(filesize > 10){
	                grl_mensaje('Tamaño de archivo no permitido. ', 'Solo se permiten archivos menores de 10 MB. ', 'warning');
	                cambio_adjunto = false;
	                constructor_file_input($container_upload_box);
	            }
	            else
	            {
	                ext_adjunto = adjunto.name.match(/\.([^\.]+)$/)[1];
	                ext_adjunto = ext_adjunto.toLowerCase();
	                console.log('ext: ' + ext_adjunto);
	                update_array_file_list(id, files_adjunto, ext_adjunto);
	                switch(ext_adjunto)
	                {
	                    case 'pdf':
	                    case 'doc':
	                    case 'docx':
	                    case 'xls':
	                    case 'xlsx':
	                    case 'jpg':
	                    case 'jpeg':
	                    case 'bmp':
	                    case 'png':
	                    case 'tif':
	                    case 'tiff':
	                    case 'svg':
	                        $container_upload_box.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file ready-to-upload');
	                        $container_upload_box.find('.btn-upload-file').removeClass().addClass('smooth-transition btn-upload-file ready-to-upload');
	                        $container_upload_box.find('.btn-upload-file').data('disabled',true);
	                        $container_upload_box.find('i.icon-file').removeClass().addClass('icon-file fa fa-check');
	                        $container_upload_box.find('span.msj-btn-file').text('Ready - Listo');
	                        $container_upload_box.find('.btn-view-file').remove();
	                        $container_upload_box.find(".btn-view-file-delete").parent('div').remove();
	                        break;
	                    default:{
	                        grl_mensaje('Tipo de archivo no permitido', 'solo se permiten imágenes.', 'warning');
	                        constructor_file_input($container_upload_box);
	                    }
	                }
	           }
	       	}
        });
	}

	function update_list_item_interface($li,nombre_archivo){
    	$li.unbind('click');
        if(nombre_archivo != ''){
            $li.addClass('row-with-attachment');
            $li.find("span.state-icon").addClass('fa-paperclip').removeClass('fa-check-square-o');
            $li.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file with-attachment').data('disabled',false);
            $li.find('i.icon-file').addClass('hide');
            $li.find('span.msj-btn-file').text(nombre_archivo);
            $li.find(".btn-upload-file").addClass('hide').data('disabled',true);
            $li.find(".container-upload-box").append('<a tabindex="0" target="_blank" href="/mod_seguridad_alimenticia/adjuntos/'+nombre_archivo+'" class="smooth-transition btn-view-file">' +
									                    '<i class="fa fa-eye"></i>' +
									                '</a>');
        }
    }

	function update_array_file_list(id, file, file_ext) {
        //alert(id + file_ext);
        for (var i in array_file_list) {
                if (array_file_list[i].id == id) {
                        array_file_list[i].file 	= file;
                        array_file_list[i].file_ext = file_ext;
                        break;
                }
         }
	}

    // Evento al seleccionar un archivo
	$('#archivo-adjunto').on('change', prepare_upload2 );
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
		/*Máscaras de formato de ingreso de datos*/
        $('.monto').mask("#,##0.00", {reverse: true});
        $('.letras75').mask('SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z0-9-_,]/, optional: false}}});
	    var fecha_hoy   = new Date(<?php echo time() * 1000; ?>);
	    var hoy         = (fecha_hoy.getMonth()+1) + "/" + fecha_hoy.getDate() + "/" + fecha_hoy.getFullYear();
	    //var hoy_18_anios_atras        = (fecha_hoy.getMonth()+1) + "/" + fecha_hoy.getDate() + "/" + (fecha_hoy.getFullYear()-18);
	    var hoy_default = fecha_hoy.getFullYear() + "-" + (fecha_hoy.getMonth()+1) + "-" + fecha_hoy.getDate();

	    //Construcción de fechas
	    $('.date').datetimepicker({
	        //disabledHours: true,
	        locale: 'es',
	        //minDate: hoy,
	        //keepOpen: true,
	        format: 'MM-DD-YYYY',
	        //defaultDate: hoy + ' 08:00 am',
	        //direction: 'auto',
	        icons: {
	                    time: "fa fa-clock-o",
	                    date: "fa fa-calendar",
	                    up: "fa fa-arrow-up",
	                    down: "fa fa-arrow-down",
	                    previous: 'fa fa-arrow-left',
	                    next: 'fa fa-arrow-right',
	                }
	    });
	    $('.time').datetimepicker({
	        //disabledHours: true,
	        locale: 'es',
	        //minDate: hoy,
	        //keepOpen: true,
	        format: 'HH:mm',
	        //defaultDate: '12:00',
	        //direction: 'auto',
	        icons: {
	                    time: "fa fa-clock-o",
	                    date: "fa fa-calendar",
	                    up: "fa fa-arrow-up",
	                    down: "fa fa-arrow-down",
	                    previous: 'fa fa-arrow-left',
	                    next: 'fa fa-arrow-right',
	                }
	    });

	    /* Clic botón seleccionar archivo */
        $(".btn-select-file").on('click',function (e){
            e.stopPropagation();
            $(this).parent().find('input[type=file]').click();
            return false;
        });
		/* Clic botón subir archivo */
	    $('.btn-upload-file').on('click', function (e){
	        e.stopPropagation();
	        if( $(this).data('disabled') == false){
	            var $container_upload_box = $(this).parent();
	            var id = $(this).data('id');
	            var $li = $container_upload_box.closest("li.list-group-item");
	            // Busca id dentro del objeto [array_file_list]
	            var temp_array = array_file_list.filter(function(attr) {
	                    return attr.id == id;
	            });
	            $li.unbind('click');
	        }
	    });

	    /* Disparador input seleccionador de archivo */
	    $('input[type=file]').on('click',function (e){
	        e.stopPropagation();
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
		grl_constructor_paises();
		grl_constructor_departamentos_por_pais();


		$( "#cod_pais" ).change(function() {
				$("#cod_departamento").empty();
				grl_constructor_departamentos_por_pais();
		});

		$( "#cod_departamento" ).change(function() {
				$("#cod_municipio").empty();
				grl_constructor_municipios_por_pais();
		});

	    var cod_granjas_usuario = "[<?php echo $cod_granjas_usuario; ?>]";
	    cod_granjas_usuario = JSON.parse(cod_granjas_usuario);
	    $('#cod_info_empresa > option').filter(function(index) {
    		if($.inArray(parseInt($(this).val()), cod_granjas_usuario) == '-1')
    			$(this).remove();
    	});

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
		});
        codigo_environmental_test = <?php echo $codigo_environmental_test; ?>;
		$('#modal_loading').modal('hide');
		jQuery.ajaxSetup({async:true});
	 });


	$('#btn_guardar').click(function(event) {
         /* Act on the event */
        var error = 0;
        $(".input.requerido").map(function(){
            if( !$(this).val() )
            {
                error = 1;
                $(this).parent('div').addClass('has-error');
                return false;
                console.log($(this).attr('id'));
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
                console.log($(this).attr('id'));
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
            	jQuery.ajaxSetup({ async: false });
                codigo_environmental_test = conf_guardar_environmental_test(codigo_environmental_test);
                if(files_adjunto.length)
	        	{
	        		var contador = 1;
	        		$.each(files_adjunto, function(index, adjunto)
	        		{
	        			ext_adjunto = adjunto.name.match(/\.([^\.]+)$/)[1];
	                	ext_adjunto = ext_adjunto.toLowerCase();
	        			var nombre_adjunto = codigo_environmental_test + '_attachment_' + contador + '.' + ext_adjunto;
						var data = new FormData();
		                data.append(index, adjunto);
		                $.ajax({
		                    url:  'mod_configuracion/funciones/conf_subir_adjunto_environmental_test.php?adjunto&x1='+nombre_adjunto + '&x2='+contador,
		                    type: 'POST',
		                    data: data,
		                    cache: false,
		                    dataType: 'json',
		                    processData: false, // No procesa los archivos
		                    contentType: false, // Set content type to false as jQuery will tell the server its a query string request
		                    error: function(){
		                        //$container_upload_box.find('progress_bar').remove();
		                        grl_mensaje('Lo sentimos. ', 'Ha habido un inconveniente al subir archivo, favor intentarlo más tarde.', 'danger');
																flag_foto = 1;
		                    },
		                    success: function(data, textStatus, jqXHR)
		                    {
		                        if(typeof data.error === 'undefined'){
		                            // Todo bien, así que copia definitivamente los archivos al servidor.
		                            //grl_mensaje('Archivo agregado correctamente. ', '', 'success');
		                            conf_guardar_adjunto_environmental_test(codigo_environmental_test, nombre_adjunto);
		                        }
		                        else{
		                            grl_mensaje('Lo sentimos. ', 'Ha habido un inconveniente al subir archivo, favor intentarlo más tarde.', 'danger');
																		flag_foto = 1;
		                        }
		                    }
		              	});
		              	contador++;
		            });
	        	}
	        	jQuery.ajaxSetup({ async: true });
            });
        }
        else
        {
            grl_mensaje('Debe llenar todos los campos marcados','favor verificar','warning');
        }
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
            <h1 class="translate" data-traducir_english="Environmental Test" data-traducir_spanish="Prueba Ambiental">Prueba Ambiental</h1>
        </div>
		<div class="row">
			<div class="col-md-4">
				<label for="cod_pais" class="translate" data-traducir_english="Country" data-traducir_spanish="País">País</label>
				<div class="form-group show-tick">
					<select class="selectpicker show-menu-arrow requerido" id="cod_pais" name="cod_pais" data-live-search="true" title="Seleccione">
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<label for="cod_departamento" class="translate" data-traducir_english="State" data-traducir_spanish="Estado">Estado</label>
				<div class="form-group show-tick">
					<select class="selectpicker show-menu-arrow requerido" id="cod_departamento" name="cod_departamento" data-live-search="true" title="Seleccione">
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<label for="cod_municipio" class="translate" data-traducir_english="County" data-traducir_spanish="Condado">Condado</label>
				<div class="form-group show-tick">
					<select class="selectpicker show-menu-arrow requerido" id="cod_municipio" name="cod_municipio" data-live-search="true" title="Seleccione">
					</select>
				</div>
		    </div>
		</div>
		<div class="row">
        	<div class="col-md-4">
				<label for="cod_info_empresa" class="translate" data-traducir_english="Greenhouse" data-traducir_spanish="Invernadero">Invernadero</label>
				<div class="form-group show-tick">
					<select class="selectpicker show-menu-arrow requerido" id="cod_info_empresa" name="cod_info_empresa" data-live-search="true" title="Select">
					</select>
				</div>
			</div>
        	<div class="col-md-4">
				<label for="cod_location" class="translate" data-traducir_english="Location" data-traducir_spanish="Ubicación">Ubicación</label>
				<div class="form-group show-tick">
					<select class="selectpicker show-menu-arrow requerido" id="cod_location" name="cod_location" data-live-search="true" title="Select">
						<option value="-b">Select</option>
						<option value="1">Cooler</option>
						<option value="2">Wall</option>
					</select>
				</div>
			</div>
        	<div class="col-md-4">
				<label for="cod_type_test" class="translate" data-traducir_english="Test" data-traducir_spanish="Prueba">Prueba</label>
				<div class="form-group show-tick">
					<select class="selectpicker show-menu-arrow requerido" data-container="body" id="cod_type_test" name="cod_type_test" data-live-search="true" title="Select">
						<option value="-b">Select</option>
						<optgroup label="Microbiological Analysis">
							<option value="1">Generic E. coli (MPN via Colilert)</option>
							<option value="2">Total Coliform - TC (MPN via Colilert)</option>
							<option value="3">Fecal Coliform</option>
							<option value="4">E. coli O157:H7</option>
							<option value="5">Listeria</option>
							<option value="6">L. mono (Listera monocytogenes)</option>
							<option value="7">Salmonella</option>
							<option value="8">STEC</option>
							<option value="9">Yeast</option>
							<option value="10">Total Plate Count (TPC)</option>
							<option value="11">E. coli O157:H7 (Pooled)</option>
							<option value="12">Total Coliform</option>
						</optgroup>
						<optgroup label="Pesticide / Residue Analysis">
							<option value="13">RUSH</option>
							<option value="14">Multi-Residue Screen (MRS)</option>
							<option value="15">MRS - Extended</option>
							<option value="16">MRS + Dithios</option>
							<option value="17">USDA NOP</option>
							<option value="18">MRS4</option>
							<option value="19">MRS + ON’s</option>
							<option value="20">MRS - MB</option>
							<option value="21">Long Form</option>
						</optgroup>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
        	<div class="col-md-4">
				<label for="cod_source_phase" class="translate" data-traducir_english="Source Phases" data-traducir_spanish="Tipo Superficie">Tipo Superficie</label>
				<div class="form-group show-tick">
					<select class="selectpicker show-menu-arrow requerido" id="cod_source_phase" name="cod_source_phase" data-live-search="true" title="Select">
						<option value="-b">Select</option>
						<option value="1">Non-Food Contact</option>
						<option value="2">Food Contact</option>
					</select>
				</div>
			</div>
        	<div class="col-md-4">
				<label for="cod_sample" class="translate" data-traducir_english="Sample" data-traducir_spanish="Muestra">Muestra</label>
				<div class="form-group show-tick">
					<select class="selectpicker show-menu-arrow requerido" id="cod_sample" name="cod_sample" data-live-search="true" title="Select">
						<option value="-b">Select</option>
						<option value="1">Swab</option>
						<option value="2">Sponge</option>
					</select>
				</div>
			</div>
        	<div class="col-md-4">
        		<div class="form-group input-group-sm">
                    <label for="result" class="translate" data-traducir_english="Result" data-traducir_spanish="Resultado">Resultado</label>
                    <input type="text"class="form-control monto input requerido"  id="result" name="result">
                </div>
        	</div>
		</div>
		<div class="row">
        	<div class="col-md-4">
        		<div class="form-group input-group-sm">
                    <label for="sample_id" class="translate" data-traducir_english="Sample Id" data-traducir_spanish="Id Muestra">Id Muestra</label>
                    <input type="text"class="form-control letras75 input requerido"  id="sample_id" name="sample_id">
                </div>
        	</div>
        	<div class="col-md-4">
              	<div class="form-group input-group-sm date" id="">
                	<label for="sample_date" class="translate" data-traducir_english="Sample Date" data-traducir_spanish="Fecha Muestra">Fecha Muestra</label>
                	<div class='input-group input-group-sm date'>
                		<span class="input-group-addon">
                			<span class="fa fa-calendar">
                      		</span>
                		</span>
                		<input type='text' class="form-control input requerido" id="sample_date" />
                	</div>
              	</div>
          	</div>
        	<div class="col-md-4">
              	<div class="form-group input-group-sm time" id="">
                	<label for="sample_time" class="translate" data-traducir_english="Sample Time" data-traducir_spanish="Hora Muestra">Hora Muestra</label>
                	<div class='input-group input-group-sm time'>
                		<span class="input-group-addon">
                			<span class="fa fa-clock">
                      		</span>
                		</span>
                		<input type='text' class="form-control input requerido" id="sample_time" />
                	</div>
              	</div>
          	</div>
		</div>
        <div class="row">
        	<div class="col-md-12">
    			<label class="translate" data-traducir_english="Attachments" data-traducir_spanish="Adjuntos">Adjuntos</label>
                <div class="container-upload-box">
                    <button data-disabled="true" data-id="adjunto" class="smooth-transition btn-select-file">
                        <i class="icon-file fa fa-camera fa-lg"> </i>
                        <span id="msj-btn-file-select-adjunto" class="msj-btn-file translate" data-traducir_english="Select attachments" data-traducir_spanish="Seleccione Adjuntos"> Seleccione Adjuntos</span>
                    </button>
                    <input type="file" multiple="" title="Seleccione Archivo..." class="input-file-hidden" id="archivo-adjunto" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.png,.bmp,.gif,.tiff,.svg">
                </div>
    		</div>
		</div>
    </div>
	<div class="panel-footer" align="right">
		<button class="btn btn-sm btn-primary translate" type="button" id="btn_guardar" data-traducir_english="Save" data-traducir_spanish="Guardar">Guardar</button>
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