<?PHP
/*
 * Listado de los estados de plantaciones.
 * @author      Jairo Bonilla
 * @date        2018-10-25
*/

session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_configuracion.php");
/*INSTANCIAMIENTOS*/
$DB_CONG 		= new db_configuracion();
$cod_plantacion 	= $_POST['x1'];
$FORMULARIOS 	= $DB_CONG->conf_obtener_info_formularios_por_plantacion($cod_plantacion);

?>

<script type="text/javascript">
    var contador = 1;
	var array_file_list = []; 


    function update_array_file_list(id, file, file_ext) 
    {
        for (var i in array_file_list) {
            if (array_file_list[i].id == id) {
                array_file_list[i].file     = file;
                array_file_list[i].file_ext = file_ext;
                break;
            }
        }
    }
    /*----------------------------------------------------------------------------------
                            Toma los archivos y los asigna a la variable,
                                Validación del tipo de archivo
    ----------------------------------------------------------------------------------*/
    function prepare_upload_mat(event){
    	var $container_upload_box = $(this).parent();
    	files = event.target.files;
    	var cancel_button_is_clicked = files[0];
    	if( cancel_button_is_clicked == undefined ){
    		constructor_file_input_mat($(this).parent());
    	} 
    	else{
    		var id = $container_upload_box.find('.btn-select-file').data('id');
    		var filesize =  files[0].size/1024/1024;
			if(filesize > 20){
				grl_mensaje('Tamaño de archivo no permitido ', 'Solo se permiten archivos menores de 20 MB', 'warning');
				cambio_adjunto = false;
				constructor_file_input_mat($container_upload_box);
			}
			else{
	            var ext = this.value.match(/\.([^\.]+)$/)[1];
				ext =ext.toLowerCase();
	            update_array_file_list(id, files, ext);
	            switch(ext)
	            {
	                case 'avi':
			        case 'mpg':
			        case 'mpeg':
			        case 'wmv':
			        case '3gp':
			        case 'mp4':
			        case 'mp3':
			        case 'ogg':
			        case 'm4a':
			        case 'wav':
			        case 'xls':
			        case 'xlsx':
			        case 'ppt':
			        case 'pptx':
			        case 'pps':
			        case 'ppsx':
					case 'pdf':
					case 'doc':
					case 'docx':
					case 'jpg':
					case 'jpeg':
			        case 'bmp':
			        case 'png':
	                    $container_upload_box.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file ready-to-upload');
	                    $container_upload_box.find('.btn-upload-file').removeClass().addClass('smooth-transition btn-upload-file ready-to-upload');
	                    $container_upload_box.find('.btn-upload-file').data('disabled',false);
	                    $container_upload_box.find('i.icon-file').removeClass().addClass('icon-file fa fa-check');
	                    $container_upload_box.find('span.msj-btn-file').text(' Archivo listo para subir');
	                    $container_upload_box.find('.btn-view-file').remove();
	                    $container_upload_box.find(".btn-view-file-delete").parent('div').remove();
	                    break;
	                default:{
	                    grl_mensaje('Tipo de archivo no permitido', 'solo se permiten archivos PDF, Office, Video, Audio e imágenes', 'warning');
	                    constructor_file_input($(this).parent());
	                }
	            }
	        }
    	}
		
    }

	/* Clic botón seleccionar archivo */
    $(".btn-select-file").on('click',function (e){
        e.stopPropagation();
        if( $(this).data('disabled') == false){
            $(this).parent().find('input[type=file]').click();
        }
        return false;
    });
    /* Clic botón subir archivo */
    $('.btn-upload-file').on('click', function (e){
        e.stopPropagation();
        if( $(this).data('disabled') == false){
        	var $container_upload_box = $(this).parent();
        	var id = $(this).data('id');
        	var $li = $container_upload_box.closest(".row");
        	// Busca id dentro del objeto [array_file_list] 
        	var temp_array = array_file_list.filter(function(attr) {
        		return attr.id == id;
        	});
        	$li.unbind('click');
        	constructor_progress_bar($container_upload_box);
        	subir_archivo_mat($container_upload_box,temp_array[0]['file'],temp_array[0]['id'],temp_array[0]['file_ext']);
        }
    });
    /* Disparador input seleccionador de archivo */
    $('input[type=file]').on('click',function (e){
        e.stopPropagation();
    });
    // Evento al seleccionar un archivo
    $('input[type=file]').on('change', prepare_upload_mat );

	function constructor_file_input_mat($container_upload_box)
	{
    	$container_upload_box.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file');
        $container_upload_box.find('.btn-upload-file').removeClass().addClass('smooth-transition btn-upload-file');
        $container_upload_box.find('.btn-upload-file').data('disabled',true);
        $container_upload_box.find('i.icon-file').removeClass().addClass('icon-file fa fa-file');
        $container_upload_box.find('span.msj-btn-file').text(' Seleccione archivo');
        $container_upload_box.find('.btn-view-file').addClass('hide');

        $container_upload_box.find("input[type=file]").replaceWith(
            $container_upload_box.find("input[type=file]").val('').clone( true )
            );
        $container_upload_box.find("input[type=file]").attr('title', 'Seleccione un Archivo');
        files 		= '';
        file_ext 	= '';
        var id 		= $container_upload_box.find('.btn-select-file').data('id');
    	update_array_file_list(id, files, file_ext);
    }

	function update_list_item_interface($li,nombre_archivo,orden,cadena){
    	$li.unbind('click');                    
        if(nombre_archivo != ''){
            $li.addClass('row-with-attachment');
            $li.find("span.state-icon").addClass('fa-paperclip').removeClass('fa-check-square-o');
            $li.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file with-attachment').data('disabled',false);
            $li.find('i.icon-file').addClass('hide');
            $li.find('span.msj-btn-file').text(nombre_archivo);
            $li.find(".btn-upload-file").addClass('hide').data('disabled',true);
            $li.find(".container-upload-box").append('<a tabindex="0" target="_blank" href="../mod_capacitaciones/adjuntos/'+nombre_archivo+'" class="smooth-transition btn-view-file btn-finalizar">' +
									                    '<i class="fa fa-eye"></i>' +
									                '</a>' +
            										'<div style="width:30px;display: table-cell;" ><button type="button" class="smooth-transition btn_delete_file'+orden+' btn-view-file-delete btn-finalizar" data-value=""><i class="far fa-trash-alt"></i></button></div>');
        	$(".btn_delete_file"+orden).on("click",function(){
					cur_borrar_adjunto($(this).data("value"));
					update_list_item_interface2($li,orden);
			});
        }
    }

    function update_list_item_interface2($li,orden){
    	console.log($li);
    	console.log(orden);
        $li.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file').data('disabled',false);
        $li.find('i.icon-file').removeClass().addClass('icon-file fa fa-file');
        $li.find('span.msj-btn-file').text('Seleccione archivo');
        $li.find(".btn-upload-file").removeClass().addClass('smooth-transition btn-upload-file').data('disabled',true);
    	$li.find(".btn-view-file").remove();
    	$li.find(".btn-view-file-delete").parent('div').remove();
    	$li.find("input[type=file]").replaceWith(
        	$li.find("input[type=file]").val('').clone( true )
        );
	    $li.find("input[type=file]").attr('title', 'Seleccione un Archivo');
    	$li.on('change','input[type=file]',prepare_upload_mat);
    }
    /*
	*	Sube archivos al servidor por medio de ajax y PHP
	*/	 
	function subir_archivo_mat($container_upload_box,archivo,id,ext_archivo){
		//event.stopPropagation(); // Evita que sucedan acciones
		//event.preventDefault(); // Evita que todas las acciones del formulario se realicen
		console.log(id);
		if ( archivo != '') {
			var files = archivo;
			// Crea un objeto  formdata y le agrega los archivos.
			var data = new FormData();
			$.each(files, function(key, value){data.append(key, value);});
			contador = id;
			nombre = 'adjunto_'+contador+'_'+ <?php echo $_SESSION['cod_usuario']; ?> +"-<?php echo date('m-d-Y-h-i-s-a', time()) ?>." + ext_archivo;
			$.ajax({
				xhr: function(){
					//var xhr = new window.XMLHttpRequest();
					var xhr = new XMLHttpRequest();
					//Upload progress
					xhr.addEventListener('loadstart', function() { 
						xhr.upload.addEventListener("progress", function(e){
							if (e.lengthComputable) {
								var percentComplete = Math.round((e.loaded / e.total)*100);
								//Do something with upload progress
								console.log(percentComplete);
								$container_upload_box.find('.progress-bar').css('width', percentComplete+'%');
							}
						}, false);
					});
						return xhr;
				},
				url:  'mod_plantaciones/funciones/plan_subir_adjunto.php?files&x1='+nombre,
				type: 'POST',
				data: data,
				cache: false,
				//dataType: 'json',
				processData: false, // No procesa los archivos 
				contentType: false, // Set content type to false as jQuery will tell the server its a query string request				
				success: function(data, textStatus, jqXHR){
					if(typeof data.error === 'undefined'){
						// Todo bien, así que copia definitivamente los archivos al servidor. 
						grl_mensaje('Archivo agregado correctamente.', '', 'success');
					}
					else{
						grl_mensaje('Error al enviar el archivo ', data.error, 'danger');
					}
					var $li = $("#container_"+contador);
					var nombre_archivo = 'adjunto_'+contador+'_'+<?php echo $_SESSION['cod_usuario']; ?> +"-<?php echo date('m-d-Y-h-i-s-a', time()) ?>." + ext_archivo;
					var direccion_archivo = '../../mod_plantaciones/adjuntos/'+nombre_archivo;
					update_list_item_interface($li,nombre_archivo,contador);
					$container_upload_box.find('.progress_bar').remove();
					plan_guardar_item_respuesta_formulario(codigo_plantacion,$('#cod_info_empresa').val(),$container_upload_box.data('cod_formulario'),$container_upload_box.data('cod_item'),$container_upload_box.data('cod_detalle_item'),nombre_archivo);
				},
				error: function(){
					$container_upload_box.find('.progress_bar').remove();
					grl_mensaje('Error al enviar el archivo, ', 'favor intentarlo mas tarde', 'danger');			 	
				},
			}); //fin ajax
		}
		else{
			grl_mensaje('Favor seleccione un archivo.', '', 'warning');
			$container_upload_box.find('progress_bar').remove();
		}
	}//fin de subir_archivo ()
    
	$(document).ready(function() {

        $('.checkbox').change(function(event) {
        	/* Act on the event */
        	if ($(this).attr('checked')) 
        	{
        		$(this).removeAttr('checked');
        	}
        	else
        	{
        		$(this).attr('checked', 'checked');
        	}
        });

		$('.btn_formulario').click(function(event) {
			/* Act on the event */
			var error = 0;
		    $(".input.requerido.formulario_" + $(this).data('cod_formulario')).map(function(){
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
		    $(".selectpicker.requerido.formulario_" + $(this).data('cod_formulario')).map(function(){
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
		    if(error == 0)
		    {
				$.each($('.formulario_' + $(this).data('cod_formulario')), function(index, val) {
					/* iterate through array or object */
					if($(this).attr('id') != undefined)
					{
						if($(this).hasClass('checkbox'))
						{
							plan_guardar_item_respuesta_formulario(codigo_plantacion,$('#cod_info_empresa').val(),$(this).data('cod_formulario'),$(this).data('cod_item'),$(this).data('cod_detalle_item'),($(this).attr('checked') ? 1 : 0));
						}
						else
						{
							if($(this).hasClass('selectpicker'))
							{
								plan_guardar_item_respuesta_formulario(codigo_plantacion,$('#cod_info_empresa').val(),$(this).data('cod_formulario'),$(this).data('cod_item'),$(this).data('cod_detalle_item'),$('#' + $(this).attr('id') + ' option:selected').val());
							}
							else
							{
								plan_guardar_item_respuesta_formulario(codigo_plantacion,$('#cod_info_empresa').val(),$(this).data('cod_formulario'),$(this).data('cod_item'),$(this).data('cod_detalle_item'),$(this).val());
							}
						}
						console.log('id:' + $(this).attr('id'));
					}
				});
				//console.log('formulario: formulario_<?php echo $formulario['cod_formulario'];?>');
				plan_notificar_usuario_formulario_llenado(codigo_estado_plantacion, codigo_plantacion)
				plan_vista_plantacion(codigo_plantacion);
			}			
		    else
		    {
		        grl_mensaje('Debe llenar todos los campos del formulario','favor verificar','warning');
		    }
		});
	});
</script>
<?php
if (count($FORMULARIOS)) {
	foreach ($FORMULARIOS as $formulario) {
		$ITEMS 	= $DB_CONG->conf_obtener_items_activos_formulario($formulario['cod_formulario']);
		?>
		<div class="row" id="formulario_<?php echo $formulario['cod_formulario'];?>" >
			<div class="col-md-12">
				<h3><?php echo utf8_encode($formulario['nombre_formulario']); ?></h3>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="responsive_table_container">
	                <table class="table display row-border responsive" id="tabla_itemschecklist">
	                    <thead>
	                         <tr class="active info">
	                            <th width="10%"></th>
	                            <th width="30%">Nombre Item</th>
	                            <th width="30%">Item</th>
	                         </tr>
	                    </thead>
	                    <tbody>
	                    	<?php
	                    	if (count($ITEMS)) {
	                    		$correlativo = 1;
	                    		foreach ($ITEMS as $item) {
	                    			?>
	                    			<tr>
	                    				<td><?php echo $correlativo; ?></td>
	                    				<td><?php echo utf8_encode($item['nombre_item']); ?></td>	                    				
	                    				<td>
		                    				<?php
		                    				switch ($item['cod_tipo_item']) {
		                    					case '1': /* ITEM ES UN CHECKBOX */
		                    						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario($item['cod_item']);
		                    						?>
		                    						<div class="material-switch">
							                            <input data-cod_detalle_item="<?php echo $OPCIONES[0]['cod_detalle_item'];?>" data-cod_formulario="<?php echo $formulario['cod_formulario'];?>" data-val_check="<?php echo utf8_encode($OPCIONES[0]['valor_item']);?>" data-val_uncheck="<?php echo utf8_encode($OPCIONES[1]['valor_item']);?>" class="checkbox formulario_<?php echo $formulario['cod_formulario'];?>" id="checkbox_<?php echo utf8_encode($item['cod_item']);?>" data-cod_item="<?php echo utf8_encode($item['cod_item']);?>" data-id="<?php echo utf8_encode($item['cod_item']);?>" name="checkbox_<?php echo utf8_encode($item['cod_item']);?>" type="checkbox" <?php echo ($item['activo'] == 1 ? 'checked="checked"':'');?>/>
							                            <label for="checkbox_<?php echo utf8_encode($item['cod_item']);?>"></label>
							                        </div>
		                    						<?php
		                    						break;
		                    					case '2': /*ITEM ES UN DOBLE TEXTBOX*/
		                    						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario($item['cod_item']);
		                    						?>
		                    						<div class="input-group">
		                    							<span class="input-group-sm">
		                    								<input data-cod_detalle_item="<?php echo $OPCIONES[0]['cod_detalle_item'];?>" data-cod_item="<?php echo utf8_encode($item['cod_item']);?>" class="input requerido formulario_<?php echo $formulario['cod_formulario'];?>" data-cod_formulario="<?php echo $formulario['cod_formulario'];?>" type="text" id="textbox_<?php echo utf8_encode($OPCIONES[0]['cod_detalle_item']);?>" data-valor="<?php echo utf8_encode($OPCIONES[0]['valor_item']);?>" placeholder="<?php echo utf8_encode($OPCIONES[0]['texto_item']);?>">
		                    							</span>
		                    							<input data-cod_detalle_item="<?php echo $OPCIONES[1]['cod_detalle_item'];?>" data-cod_item="<?php echo utf8_encode($item['cod_item']);?>" class="input requerido formulario_<?php echo $formulario['cod_formulario'];?>" data-cod_formulario="<?php echo $formulario['cod_formulario'];?>" type="text" id="textbox_<?php echo utf8_encode($OPCIONES[1]['cod_detalle_item']);?>" data-valor="<?php echo utf8_encode($OPCIONES[1]['valor_item']);?>" placeholder="<?php echo utf8_encode($OPCIONES[1]['texto_item']);?>">
		                    						</div>
		                    						<?php
		                    						break;
		                    					case '3': /*ITEM ES UN SELECTPICKER*/
		                    						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario($item['cod_item']);
		                    						?>
								                    <div class="col-md-12" id="">
														<label for="cod_item_<?php echo utf8_encode($item['cod_item']);?>"><?php echo utf8_encode($item['nombre_item']);?></label>
														<div class="form-group show-tick">
															<select data-cod_detalle_item="<?php echo $OPCIONES[0]['cod_detalle_item'];?>" data-cod_item="<?php echo utf8_encode($item['cod_item']);?>" data-cod_formulario="<?php echo $formulario['cod_formulario'];?>" class="selectpicker show-menu-arrow requerido formulario_<?php echo $formulario['cod_formulario'];?>" id="cod_item_<?php echo utf8_encode($item['cod_item']);?>" name="cod_item_<?php echo utf8_encode($item['cod_item']);?>" data-live-search="true" title="Seleccione">
																<?php
									                        		foreach ($OPCIONES as $opcion) {
									                        			?>
									                        			<option value="<?php echo utf8_encode($opcion['cod_detalle_item']);?>"><?php echo utf8_encode($opcion['texto_item']);?></option>
									                        			<?php	
									                        		}
									                        		?>
															</select>
														</div>
													</div>
		                    						<?php
		                    						break;
		                    					case '4': /*ITEM ES UN ARCHIVO ADJUNTO*/
		                    						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario($item['cod_item']);
		                    						?>
													<!-- <div class="input-group col-md-12">
														<div class="custom-file">
															<input type="file" class="custom-file-input" id="adjunto_<?php echo $item['cod_item']; ?>">
															<label class="custom-file-label" for="adjunto_<?php echo $item['cod_item']; ?>"><?php echo utf8_encode($OPCIONES[0]['texto_item']);?></label>
														</div>
														<div class="input-group-append">
															<span class="input-group-text" id="">Subir</span>
														</div>
													</div> -->
													<div class="col-md-12">
								                        <!-- <div class="container-upload-box" id="div_adjunto_<?php echo $item['cod_item']; ?>">
								                            <button data-disabled="true" data-id="<?php echo $item['cod_item']; ?>" class="smooth-transition btn-select-file">
								                                <i class="icon-file fa fa-file fa-lg"> </i>
								                                <span id="msj-btn-file-select-foto" class="msj-btn-file"> <?php echo utf8_encode($OPCIONES[0]['texto_item']);?></span>
								                            </button>
								                            <input type="file" title="Seleccione Archivo..." class="input-file-hidden adjunto" id="<?php echo $item['cod_item']; ?>" accept=".jpg,.png,.bmp,.gif,.tiff,.svg,.pdf,.docx,.doc,.xls,.xlsx">
								                        </div> -->
								                        <div class="container-upload-box" id="container_<?php echo $item['cod_item']; ?>" data-cod_detalle_item="<?php echo $OPCIONES[0]['cod_detalle_item'];?>" data-cod_item="<?php echo utf8_encode($item['cod_item']);?>" data-cod_formulario="<?php echo $formulario['cod_formulario'];?>">
															<button class="smooth-transition btn-select-file disabled" data-id="<?php echo $item['cod_item']; ?>" data-disabled="true">
																<i class="icon-file fa fa-file"> </i>
																<span class="msj-btn-file" id="msj-btn-file-select-<?php echo $item['cod_item']; ?>"> <?php echo utf8_encode($OPCIONES[0]['texto_item']);?> </span>
															</button>
															<input type="file" accept=".avi,.mpeg,.mpg,.wmv,.3gp,.mp4,.mp3,.ogg,.m4a,.wav,.xls,.xlsx,.ppt,.pptx,.pps,.ppsx,.pdf,.doc,.docx,.jpg,.png,.jpeg,.bmp" id="archivo-<?php echo $item['cod_item']; ?>" class="input-file-hidden" title="Seleccione Archivo...">
											                <a tabindex="0" class="smooth-transition btn-upload-file disabled" data-id="<?php echo $item['cod_item']; ?>" data-disabled="true">
											                    <i class="fa fa-cloud-upload"> </i>
											                </a>
											            </div>
								            		</div>
		                    						<?php
		                    						echo '<script type="text/javascript">'
														, 'array_file_list.push({id:'.$item['cod_item'].',file:"",file_ext:""});'
														, 'var $li = $("#container_'.$item['cod_item'].'");'
														, '$li.find(\'.btn-select-file\').removeClass().addClass(\'smooth-transition btn-select-file\').data(\'disabled\',false);'
									                    , '$li.find(\'i.icon-file\').removeClass(\'hide\');'
									                    , '$li.find(\'span.msj-btn-file\').text(\' '.utf8_encode($OPCIONES[0]['texto_item']).'\');'
									                    , '$li.find(".btn-upload-file").removeClass().addClass(\'smooth-transition btn-upload-file\').data(\'disabled\',true);'
									                    , '$li.find(".btn-view-file").remove();'
													   	/*, 'estilo_file_input_material_apoyo('.$contador.');'*/
													   	, '</script>'
													;
		                    						break;
		                    					case '5':
		                    						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario($item['cod_item']);
		                    						?>
		                    						<div class="col-md-12">
									            		<div class="form-group input-group-sm">
								                            <label for="<?php echo utf8_encode($OPCIONES[0]['cod_detalle_item']);?>"><?php echo utf8_encode($OPCIONES[1]['texto_item']);?></label>
								                            <input data-cod_detalle_item="<?php echo $OPCIONES[0]['cod_detalle_item'];?>" data-cod_item="<?php echo utf8_encode($item['cod_item']);?>" class="input requerido formulario_<?php echo $formulario['cod_formulario'];?>" data-cod_formulario="<?php echo $formulario['cod_formulario'];?>" type="text" id="textbox_<?php echo utf8_encode($OPCIONES[0]['cod_detalle_item']);?>" data-valor="<?php echo utf8_encode($OPCIONES[0]['valor_item']);?>" placeholder="<?php echo utf8_encode($OPCIONES[0]['texto_item']);?>">
								                        </div>
									            	</div>
		                    						<?php
		                    						break;
		                    					case '6':
		                    						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario($item['cod_item']);
		                    						?>
		                    						<div class='col-md-12'>
											            <div class="form-group">
										                	<label for="fecha_recibido_pedido"><?php echo utf8_encode($OPCIONES[0]['texto_item']);?></label>
											                <div class='input-group input-group-sm date'>
											                    <span class="input-group-addon">
											                        <span class="fa fa-calendar"></span>
											                    </span>
											                    <input data-cod_detalle_item="<?php echo $OPCIONES[0]['cod_detalle_item'];?>" data-cod_item="<?php echo utf8_encode($item['cod_item']);?>" class="input requerido formulario_<?php echo $formulario['cod_formulario'];?>" data-cod_formulario="<?php echo $formulario['cod_formulario'];?>" type="text" id="textbox_<?php echo utf8_encode($OPCIONES[0]['cod_detalle_item']);?>" data-valor="<?php echo utf8_encode($OPCIONES[0]['valor_item']);?>" placeholder="<?php echo utf8_encode($OPCIONES[0]['texto_item']);?>">
											                </div>
											            </div>
											        </div>
		                    						<?php
		                    						break;
		                    					default:
		                    						// code...
		                    						break;
		                    				}
		                    				?>
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
        <div class="row">
        	<div class="col-md-3 col-md-offset-9">
        		<button data-cod_formulario="<?php echo $formulario['cod_formulario'];?>" class="btn btn-sm btn-primary btn_formulario" id="btn_guardar_<?php echo $formulario['cod_formulario'];?>">Guardar</button>
        	</div>
        </div>
		<hr>
		<?php		
	}
	
}
?>