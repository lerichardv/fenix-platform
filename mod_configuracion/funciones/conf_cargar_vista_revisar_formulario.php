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
$DB_CONG 			    = new db_configuracion();
$cod_detalle_form       = $_POST['x1'];
$flag_solo_respuestas   = $_POST['x2'];
$FORMULARIO 		    = $DB_CONG->conf_obtener_info_formulario_detalle_revisar($cod_detalle_form, 1);

?>

<script type="text/javascript">
	$(document).ready(function() {
		jQuery.ajaxSetup({async:false});
		conf_constructor_estados_formularios_sa('cod_estado');
        $('.selectpicker').selectpicker({
            dropupAuto: 'true',
            container: 'body',
            size: '5',
            width: '100%',
            style: 'btn-sm btn-info'
        });
        $('.item_formulario').prop('readonly', true);
        $('.item_formulario').prop('disabled', true);
        $('#cod_estado').selectpicker('val',"<?php echo $FORMULARIO[0]['cod_estado']; ?>");
        $('.selectpicker').selectpicker('refresh');
		jQuery.ajaxSetup({async:true});
	});
	$('#btn_guardar').click(function(event) {
		var error = 0;
        $(".input.requerido-revisar").map(function(){
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
        $(".selectpicker.requerido-revisar").map(function(){
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
            	conf_guardar_revision_formulario_sa(<?php echo $cod_detalle_form; ?>);
			});
    	}
        else
        {
            grl_mensaje('You must fill the empty fields','Debe llenar los campos vacíos','warning');
        }
	});
</script>
<?php
if (count($FORMULARIO)) {
	foreach ($FORMULARIO as $formulario) {
		$ITEMS 	= $DB_CONG->conf_obtener_respuestas_items_activos_formulario_sa($cod_detalle_form);
		if (count($ITEMS) > 0)
		{
			?>
			<div class="row" id="formulario_<?php echo $formulario['cod_formulario'];?>" >
				<div class="col-md-12">
					<h3><?php echo utf8_encode($formulario['nombre_formulario']); ?></h3>
				</div>
			</div>
			<div class="row">
            	<?php
            	if (count($ITEMS)) {
            		$correlativo = 1;
            		$cols = 0;
            		foreach ($ITEMS as $item) {
        				switch ($item['cod_tipo_item'])
        				{
        					case '1': /* ITEM ES UN CHECKBOX */
        						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario_sa($item['cod_formulario_item']);
        						$cols += 3;
        						if($cols > 12)
        						{
        							$cols = 3;
        							?>
        							</div>
        							<div class="row">
        							<?php
        						}
        						?>
        						<div class="col-md-3 div_formulario" id="div_<?php echo utf8_encode($item['id_item']);?>" cols="div_<?php echo $cols; ?>">
        							<label for="<?php echo utf8_encode($item['id_item']);?>"><?php echo utf8_encode($item['nombre_item']);?></label>
        							<div class="form-group input-group-sm">

                						<!-- <div class="material-switch"> -->
				                        <input data-cod_detalle_item="<?php echo $OPCIONES[0]['cod_detalle_item'];?>" data-cod_formulario="<?php echo $formulario['cod_formulario'];?>" data-val_check="<?php echo utf8_encode($OPCIONES[0]['valor_detalle']);?>" data-val_uncheck="<?php echo utf8_encode($OPCIONES[1]['valor_detalle']);?>" class="form-control item_formulario checkbox formulario_<?php echo $formulario['cod_formulario'];?>" id="<?php echo utf8_encode($item['id_item']);?>" data-cod_item="<?php echo utf8_encode($item['cod_formulario_item']);?>" data-id="<?php echo utf8_encode($item['cod_formulario_item']);?>" name="checkbox_<?php echo utf8_encode($item['cod_formulario_item']);?>" type="checkbox" <?php echo ($item['activo'] == 1 ? 'checked="checked"':'');?>/>
				                            <!-- <label for="<?php echo utf8_encode($item['id_item']);?>"></label> -->
				                        <!-- </div> -->
				                    </div>
			                    </div>
								<script type="text/javascript">
									$('#<?php echo utf8_encode($item['id_item']);?>').prop('checked', "<?php echo ($item['observacion'] != 0 ? true:false); ?>");
								</script>
        						<?php
        						break;
        					case '2': /*ITEM ES UN DOBLE TEXTBOX*/
        						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario_sa($item['cod_formulario_item']);
        						$cols += 3;
        						if($cols > 12)
        						{
        							$cols = 3;
        							?>
        							</div>
        							<div class="row">
        							<?php
        						}
        						?>
        						<div class="col-md-3 div_formulario" id="div_<?php echo utf8_encode($item['id_item']);?>" cols="div_<?php echo $cols; ?>">
            						<div class="input-group">
            							<span class="input-group-sm">
            								<input data-cod_detalle_item="<?php echo $OPCIONES[0]['cod_detalle_item'];?>" data-cod_item="<?php echo utf8_encode($item['cod_formulario_item']);?>" class="input item_formulario formulario_<?php echo $formulario['cod_formulario'];?>" data-cod_formulario="<?php echo $formulario['cod_formulario'];?>" type="text" id="textbox_<?php echo utf8_encode($OPCIONES[0]['cod_detalle_item']);?>" data-valor="<?php echo utf8_encode($OPCIONES[0]['valor_detalle']);?>" placeholder="<?php echo utf8_encode($OPCIONES[0]['texto_detalle']);?>">
            							</span>
            							<input data-cod_detalle_item="<?php echo $OPCIONES[1]['cod_detalle_item'];?>" data-cod_item="<?php echo utf8_encode($item['cod_formulario_item']);?>" class="input item_formulario formulario_<?php echo $formulario['cod_formulario'];?>" data-cod_formulario="<?php echo $formulario['cod_formulario'];?>" type="text" id="textbox_<?php echo utf8_encode($OPCIONES[1]['cod_detalle_item']);?>" data-valor="<?php echo utf8_encode($OPCIONES[1]['valor_detalle']);?>" placeholder="<?php echo utf8_encode($OPCIONES[1]['texto_detalle']);?>">
            						</div>
            					</div>
        						<?php
        						break;
        					case '3': /*ITEM ES UN SELECTPICKER*/
        						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario_sa($item['cod_formulario_item']);
        						$cols += 3;
        						if($cols > 12)
        						{
        							$cols = 3;
        							?>
        							</div>
        							<div class="row">
        							<?php
        						}
        						?>
			                    <div class="col-md-3 div_formulario" id="div_<?php echo utf8_encode($item['id_item']);?>" cols="div_<?php echo $cols; ?>">
									<label for="cod_item_<?php echo utf8_encode($item['cod_formulario_item']);?>"><?php echo utf8_encode($item['nombre_item']);?></label>
									<div class="form-group show-tick">
										<select data-cod_detalle_item="<?php echo $OPCIONES[0]['cod_detalle_item'];?>" data-cod_item="<?php echo utf8_encode($item['cod_formulario_item']);?>" data-cod_formulario="<?php echo $formulario['cod_formulario'];?>" class="selectpicker item_formulario show-menu-arrow formulario_<?php echo $formulario['cod_formulario'];?>" id="<?php echo utf8_encode($item['id_item']);?>" name="cod_item_<?php echo utf8_encode($item['cod_formulario_item']);?>" data-live-search="true" title="Seleccione">
											<?php
				                        		foreach ($OPCIONES as $opcion) {
				                        			?>
				                        			<option value="<?php echo utf8_encode($opcion['cod_detalle_item']);?>"><?php echo utf8_encode($opcion['texto_detalle']);?></option>
				                        			<?php
				                        		}
				                        		?>
										</select>
									</div>
								</div>
								<script type="text/javascript">
									$('#<?php echo utf8_encode($item['id_item']);?>').selectpicker('val',"<?php echo $item['valor_detalle']; ?>");
									$('.selectpicker').selectpicker('refresh');
								</script>
        						<?php
        						break;
        					case '4': /*ITEM ES UN ARCHIVO ADJUNTO*/
        						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario_sa($item['cod_formulario_item']);
        						$cols += 6;
        						if($cols > 12)
        						{
        							$cols = 6;
        							?>
        							</div>
        							<div class="row">
        							<?php
        						}
        						?>
								<div class="col-md-6 div_formulario" id="div_<?php echo utf8_encode($item['id_item']);?>" cols="div_<?php echo $cols; ?>">
									<label><?php echo utf8_encode($item['nombre_item']);?></label>
									<div class="form-group input-group-sm">
				                        <a class="btn btn-sm btn-primary btn-block" href="../../mod_plantaciones/adjuntos/<?php echo $item['observacion']; ?>" target="_blank"><?php echo $item['observacion']; ?></a>
							        </div>
			            		</div>
								<script type="text/javascript">
									//update_list_item_interface($("#container_<?php echo $item['cod_formulario_item']; ?>"), "<?php echo $item['observacion']; ?>");
								</script>
        						<?php
        						break;
        					case '5'://Textbox simple
        						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario_sa($item['cod_formulario_item']);
        						$cols += 3;
        						if($cols > 12)
        						{
        							$cols = 3;
        							?>
        							</div>
        							<div class="row">
        							<?php
        						}
        						?>
        						<div class="col-md-3 div_formulario" id="div_<?php echo utf8_encode($item['id_item']);?>" cols="div_<?php echo $cols; ?>">
        							<label for="<?php echo utf8_encode($OPCIONES[0]['cod_detalle_item']);?>"><?php echo utf8_encode($OPCIONES[0]['texto_detalle']);?></label>
				            		<div class="form-group input-group-sm">
			                            <input data-cod_detalle_item="<?php echo $OPCIONES[0]['cod_detalle_item'];?>" data-cod_item="<?php echo utf8_encode($item['cod_formulario_item']);?>" class="form-control input item_formulario formulario_<?php echo $formulario['cod_formulario'];?>" data-cod_formulario="<?php echo $formulario['cod_formulario'];?>" type="text" id="<?php echo utf8_encode($item['id_item']);?>" data-valor="<?php echo utf8_encode($OPCIONES[0]['valor_detalle']);?>" placeholder="<?php echo utf8_encode($OPCIONES[0]['texto_detalle']);?>">
			                        </div>
				            	</div>
								<script type="text/javascript">
									$('#<?php echo utf8_encode($item['id_item']);?>').val("<?php echo utf8_encode($item['observacion']); ?>");
								</script>
        						<?php
        						break;
        					case '6'://Date
        						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario_sa($item['cod_formulario_item']);
        						$cols += 3;
        						if($cols > 12)
        						{
        							$cols = 3;
        							?>
        							</div>
        							<div class="row">
        							<?php
        						}
        						?>
        						<div class='col-md-3 div_formulario' id="div_<?php echo utf8_encode($item['id_item']);?>" cols="div_<?php echo $cols; ?>">
						            <div class="form-group">
					                	<label for="fecha_recibido_pedido"><?php echo utf8_encode($OPCIONES[0]['texto_detalle']);?></label>
						                <div class='input-group input-group-sm date'>
						                    <span class="input-group-addon">
						                        <span class="fa fa-calendar"></span>
						                    </span>
						                    <input data-cod_detalle_item="<?php echo $OPCIONES[0]['cod_detalle_item'];?>" data-cod_item="<?php echo utf8_encode($item['cod_formulario_item']);?>" class="form-control input item_formulario formulario_<?php echo $formulario['cod_formulario'];?>" data-cod_formulario="<?php echo $formulario['cod_formulario'];?>" type="text" id="<?php echo utf8_encode($item['id_item']);?>" data-valor="<?php echo utf8_encode($OPCIONES[0]['valor_detalle']);?>" placeholder="<?php echo utf8_encode($OPCIONES[0]['texto_detalle']);?>">
						                </div>
						            </div>
						        </div>
								<script type="text/javascript">
									$('#<?php echo utf8_encode($item['id_item']);?>').val("<?php echo $item['observacion']; ?>");
								</script>
        						<?php
        						break;
        					case '7': //textarea
        						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario_sa($item['cod_formulario_item']);
        						$cols += 6;
        						if($cols > 12)
        						{
        							$cols = 6;
        							?>
        							</div>
        							<div class="row">
        							<?php
        						}
        						?>
        						<div class="col-md-6 div_formulario" id="div_<?php echo utf8_encode($item['id_item']);?>" cols="div_<?php echo $cols; ?>">
        							<div class="form-group input-group-sm">
			                            <label for="<?php echo utf8_encode($OPCIONES[0]['cod_detalle_item']);?>"><?php echo utf8_encode($OPCIONES[0]['texto_detalle']);?></label>
			                            <textarea data-cod_detalle_item="<?php echo $OPCIONES[0]['cod_detalle_item'];?>" data-cod_item="<?php echo utf8_encode($item['cod_formulario_item']);?>" class="form-control input item_formulario formulario_<?php echo $formulario['cod_formulario'];?>  data-cod_formulario="<?php echo $formulario['cod_formulario'];?>" id="<?php echo utf8_encode($item['id_item']);?>" maxlength="600" align="left" data-valor="<?php echo utf8_encode($OPCIONES[0]['valor_detalle']);?>" style="height:100px; width:100%; resize: none;"></textarea>
			                        </div>
        						</div>
								<script type="text/javascript">
									$('#<?php echo utf8_encode($item['id_item']);?>').text("<?php echo $item['observacion']; ?>");
								</script>
        						<?php
        						break;
        					case '8': //selectpicker/div dependibles
        						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario_sa($item['cod_formulario_item']);
        						$cols += 3;
        						if($cols > 12)
        						{
        							$cols = 3;
        							?>
        							</div>
        							<div class="row">
        							<?php
        						}
        						?>
			                    <div class="col-md-3 div_formulario" id="div_<?php echo utf8_encode($item['id_item']);?>" cols="div_<?php echo $cols; ?>">
									<label for="cod_item_<?php echo utf8_encode($item['cod_formulario_item']);?>"><?php echo utf8_encode($item['nombre_item']);?></label>
									<div class="form-group show-tick">
										<select data-cod_detalle_item="<?php echo $OPCIONES[0]['cod_detalle_item'];?>" data-cod_item="<?php echo utf8_encode($item['cod_formulario_item']);?>" class="selectpicker item_formulario show-menu-arrow formulario_<?php echo $formulario['cod_formulario'];?> selectpicker_dependent" data-cod_formulario="<?php echo $formulario['cod_formulario'];?>" id="<?php echo utf8_encode($item['id_item']);?>" name="cod_item_<?php echo utf8_encode($item['cod_formulario_item']);?>" data-live-search="true" title="Seleccione">
											<option value="-b">Select</option>
											<?php
				                        		foreach ($OPCIONES as $opcion) {
				                        			?>
				                        			<option value="<?php echo utf8_encode($opcion['valor_detalle']);?>"><?php echo utf8_encode($opcion['texto_detalle']);?></option>
				                        			<?php
				                        		}
				                        		?>
										</select>
									</div>
								</div>
								<script type="text/javascript">
									$('#<?php echo utf8_encode($item['id_item']);?>').selectpicker('val',"<?php echo $item['valor_detalle']; ?>");
									$('.selectpicker').selectpicker('refresh');
								</script>
        						<?php
        						break;
        					case '9': //radiobutton
        						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario_sa($item['cod_formulario_item']);
        						$cols += 3;
        						if($cols > 12)
        						{
        							$cols = 3;
        							?>
        							</div>
        							<div class="row">
        							<?php
        						}
        						?>
        						<div class="col-md-3 radio_button div_formulario formulario_<?php echo $formulario['cod_formulario'];?>" id="div_<?php echo utf8_encode($item['id_item']);?>" data-name="<?php echo utf8_encode($item['id_item']);?>">
        							<label><?php echo utf8_encode($item['nombre_item']);?></label> <br>
            						<?php
            						foreach ($OPCIONES as $opcion) {
                						?>
                						<div class="col-md-4">
                							<div class="form-group">
                								<input data-cod_detalle_item="<?php echo $opcion['cod_detalle_item'];?>" data-cod_item="<?php echo utf8_encode($item['cod_formulario_item']);?>" type="radio" name="<?php echo $item['id_item']; ?>" id="<?php echo $opcion['cod_detalle_item']; ?>" value="<?php echo utf8_encode($opcion['valor_detalle']); ?>" class=" item_formulario" data-cod_formulario="<?php echo $formulario['cod_formulario'];?>"><?php echo utf8_encode($opcion['texto_detalle']); ?>
                							</div>
                						</div>
                						<?php
            						}
            						?>
        						</div>
								<script type="text/javascript">
									$('input[name="<?php echo utf8_encode($item['id_item']);?>"][value="<?php echo $item['valor_detalle']; ?>"]').prop('checked', true);
								</script>
        						<?php
        						break;
                            case '10'://Textbox min - max
                                $OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario_sa($item['cod_formulario_item']);
                                $cols += 3;
                                if($cols > 12)
                                {
                                    $cols = 3;
                                    ?>
                                    </div>
                                    <div class="row">
                                    <?php
                                }
                                if ($item['observacion'] >= $OPCIONES[0]['valor_detalle'] && $item['observacion'] <= $OPCIONES[1]['valor_detalle']) {
                                    $clase = 'text-success';
                                    $label = 'Pass';
                                }
                                else
                                {
                                    $clase = 'text-danger';
                                    $label = 'Fail';
                                }
                                ?>
                                <div class="col-md-3 div_formulario" id="div_<?php echo utf8_encode($item['id_item']);?>" cols="div_<?php echo $cols; ?>">
                                    <label for="<?php echo utf8_encode($OPCIONES[0]['cod_detalle_item']);?>"><?php echo utf8_encode($item['nombre_item'].' ('.$OPCIONES[0]['valor_detalle'].' - '.$OPCIONES[1]['valor_detalle'].')');?> <span class="<?php echo $clase; ?>"><?php echo $label; ?></span></label>
                                    <div class="form-group input-group-sm">
                                        <input data-cod_detalle_item="<?php echo $OPCIONES[0]['cod_detalle_item'];?>" data-cod_item="<?php echo utf8_encode($item['cod_formulario_item']);?>" class="form-control input item_formulario formulario_<?php echo $formulario['cod_formulario'];?>" data-cod_formulario="<?php echo $formulario['cod_formulario'];?>" type="text" id="<?php echo utf8_encode($item['id_item']);?>" data-valor="<?php echo utf8_encode($OPCIONES[0]['valor_detalle']);?>" placeholder="<?php echo utf8_encode($item['descripcion_item']);?>">
                                    </div>
                                </div>
                                <script type="text/javascript">
                                    $('#<?php echo utf8_encode($item['id_item']);?>').val("<?php echo utf8_encode($item['observacion']); ?>");
                                </script>
                                <?php
                                break;
        					default:
        						// code...
        						break;
        				}
            			$correlativo++;
            		}
            	}
            	?>
			</div>
            <?php if ($flag_solo_respuestas == 0) 
            {
                ?>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <label for="cod_estado" class="translate" data-traducir_english="Stage" data-traducir_spanish="Estado">Estado</label>
                        <div class="form-group show-tick">
                            <select class="selectpicker show-menu-arrow requerido-revisar" required="" id="cod_estado" name="cod_estado" data-live-search="true" title="Select - Seleccione">
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" >
                        <div class="form-group input-group-sm">
                            <label for="observacion" class="translate" data-traducir_english="Observation" data-traducir_spanish="Observación">Observación</label>
                            <textarea class="form-control input requerido-revisar" required="" data-cod_formulario="<?php echo $formulario['cod_formulario'];?>" data-cod_detalle="<?php echo $formulario['cod_detalle'];?>" id="observacion" maxlength="600" align="left" style="height:100px; width:100%; resize: none;"><?php echo utf8_encode($formulario['observacion']); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-md-offset-9">
                        <button class="btn btn-block btn-md btn-primary translate" data-traducir_english="Save" data-traducir_spanish="Guardar" id="btn_guardar">Guardar</button>
                    </div>
                </div>
                <hr>
                <?php
            }
		}
	}

}
?>