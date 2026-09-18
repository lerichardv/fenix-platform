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
$DB_CONG = new db_configuracion();
$cod_formulario = $_POST['x1'];
$FORMULARIO 	= $DB_CONG->conf_obtener_info_formulario_sa($cod_formulario);

$ITEMS 			= $DB_CONG->conf_obtener_items_activos_formulario_sa($cod_formulario);
if (count($FORMULARIO)) {
	?>
	<div class="row">
		<div class="col-md-12">
			<h3><?php echo utf8_encode($FORMULARIO[0]['nombre_formulario']); ?></h3>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="responsive_table_container">
                <table class="table display row-border responsive" id="tabla_itemschecklist">
                    <thead>
                         <tr class="active info">
                            <th width="10%"></th>
                            <th width="20%">ID Item</th>
                            <th width="25%">Nombre Item</th>
                            <th width="20%">Tipo Item</th>
                            <th width="25%">Item</th>
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
                    				<td><?php echo utf8_encode($item['id_item']); ?></td>
                    				<td><?php echo utf8_encode($item['nombre_item']); ?></td>
                    				<td><?php echo utf8_encode($item['tipo_item']); ?></td>
                    				<td>
	                    				<?php
	                    				switch ($item['cod_tipo_item']) {
	                    					case '1': //checkbox
	                    						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario_sa($item['cod_formulario_item']);
	                    						?>
	                    						<div class="material-switch" id="div_<?php echo utf8_encode($item['id_item']);?>">
						                            <input data-val_check="<?php echo utf8_encode($OPCIONES[0]['valor_detalle']);?>" data-val_uncheck="<?php echo utf8_encode($OPCIONES[0]['valor_detalle']);?>" class="checkbox" id="<?php echo utf8_encode($item['id_item']);?>" data-id="<?php echo utf8_encode($item['cod_formulario_item']);?>" name="checkbox_<?php echo utf8_encode($item['cod_formulario_item']);?>" type="checkbox" <?php echo ($item['activo'] == 1 ? 'checked="checked"':'');?>/>
						                            <label for="checkbox_<?php echo utf8_encode($item['cod_formulario_item']);?>"></label>
						                        </div>
	                    						<?php
	                    						break;
	                    					case '2': //textbox double
	                    						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario_sa($item['cod_formulario_item']);
	                    						?>
	                    						<div class="input-group" id="div_<?php echo utf8_encode($item['id_item']);?>">
	                    							<span class="input-group-sm">
	                    								<input type="text" id="<?php echo utf8_encode($OPCIONES[0]['id_item']);?>" data-valor="<?php echo utf8_encode($OPCIONES[0]['valor_detalle']);?>" placeholder="<?php echo utf8_encode($OPCIONES[0]['texto_detalle']);?>">
	                    							</span>
	                    							<input type="text" id="<?php echo utf8_encode($OPCIONES[1]['id_item']);?>" data-valor="<?php echo utf8_encode($OPCIONES[1]['valor_detalle']);?>" placeholder="<?php echo utf8_encode($OPCIONES[1]['texto_detalle']);?>">
	                    						</div>
	                    						<?php
	                    						break;
	                    					case '3': //selectpicker
	                    						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario_sa($item['cod_formulario_item']);
	                    						?>
							                    <div class="col-md-12" id="div_<?php echo utf8_encode($item['id_item']);?>">
													<label for="cod_item_<?php echo utf8_encode($item['cod_formulario_item']);?>"><?php echo utf8_encode($item['nombre_item']);?></label>
													<div class="form-group show-tick">
														<select class="selectpicker show-menu-arrow" id="<?php echo utf8_encode($item['id_item']);?>" name="cod_item_<?php echo utf8_encode($item['cod_formulario_item']);?>" data-live-search="true" title="Seleccione">
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
	                    						<?php
	                    						break;
	                    					case '4': //adjunto
	                    						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario_sa($item['cod_formulario_item']);
	                    						?>
												<div class="col-md-12" id="div_<?php echo utf8_encode($item['id_item']);?>">
							                        <div class="container-upload-box" id="<?php echo $item['id_item']; ?>">
							                            <button data-disabled="true" data-id="<?php echo $item['cod_formulario_item']; ?>" class="smooth-transition btn-select-file">
							                                <i class="icon-file fa fa-file fa-lg"> </i>
							                                <span id="msj-btn-file-select-foto" class="msj-btn-file"> <?php echo utf8_encode($OPCIONES[0]['texto_detalle']);?></span>
							                            </button>
							                            <input type="file" title="Seleccione Archivo..." class="input-file-hidden" id="<?php echo $item['cod_formulario_item']; ?>" accept=".jpg,.png,.bmp,.gif,.tiff,.svg,.pdf,.docx,.doc,.xls,.xlsx">
							                        </div>
							            		</div>
	                    						<?php
	                    						break;
	                    					case '5': //textbox simple
	                    						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario_sa($item['cod_formulario_item']);
	                    						?>
	                    						<div class="col-md-12" id="div_<?php echo utf8_encode($item['id_item']);?>">
								            		<div class="form-group input-group-sm">
							                            <label for="<?php echo utf8_encode($OPCIONES[0]['cod_detalle_item']);?>"><?php echo utf8_encode($OPCIONES[0]['texto_detalle']);?></label>
							                            <input type="text" id="<?php echo utf8_encode($OPCIONES[0]['id_item']);?>" data-valor="<?php echo utf8_encode($OPCIONES[0]['valor_detalle']);?>" placeholder="<?php echo utf8_encode($OPCIONES[0]['texto_detalle']);?>">
							                        </div>
								            	</div>
	                    						<?php
	                    						break;
	                    					case '6': //date
	                    						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario_sa($item['cod_formulario_item']);
	                    						?>
	                    						<div class='col-md-12' id="div_<?php echo utf8_encode($item['id_item']);?>">
										            <div class="form-group">
									                	<label for="fecha_recibido_pedido"><?php echo utf8_encode($OPCIONES[0]['texto_detalle']);?></label>
										                <div class='input-group input-group-sm date'>
										                    <span class="input-group-addon">
										                        <span class="fa fa-calendar"></span>
										                    </span>
										                    <input type='text' class="form-control input" id="<?php echo utf8_encode($OPCIONES[0]['id_item']);?>" />
										                </div>
										            </div>
										        </div>
	                    						<?php
	                    						break;
	                    					case '7': //textarea
	                    						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario_sa($item['cod_formulario_item']);
	                    						?>
	                    						<div class="col-md-12" id="div_<?php echo utf8_encode($item['id_item']);?>">
	                    							<div class="form-group input-group-sm">
							                            <label for="<?php echo utf8_encode($OPCIONES[0]['cod_detalle_item']);?>"><?php echo utf8_encode($OPCIONES[0]['texto_detalle']);?></label>
							                            <textarea class="form-control" id="<?php echo utf8_encode($item['id_item']);?>" maxlength="600" align="left" style="height:100px; width:100%; resize: none;"></textarea>
							                        </div>
	                    						</div>
	                    						<?php
	                    						break;
	                    					case '8': //selectpicker/div dependibles
	                    						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario_sa($item['cod_formulario_item']);
	                    						?>
							                    <div class="col-md-12" id="div_<?php echo utf8_encode($item['id_item']);?>">
													<label for="cod_item_<?php echo utf8_encode($item['cod_formulario_item']);?>"><?php echo utf8_encode($item['nombre_item']);?></label>
													<div class="form-group show-tick">
														<select class="selectpicker show-menu-arrow selectpicker_dependent" id="<?php echo utf8_encode($item['id_item']);?>" name="cod_item_<?php echo utf8_encode($item['cod_formulario_item']);?>" data-live-search="true" title="Seleccione">
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
	                    						<?php
	                    						break;
	                    					case '9': //radiobutton
	                    						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario_sa($item['cod_formulario_item']);
	                    						?>
	                    						<div id="div_<?php echo utf8_encode($item['id_item']);?>">
		                    						<?php
		                    						foreach ($OPCIONES as $opcion) {
			                    						?>
			                    						<div class="col-md-3">
			                    							<div class="form-group">
			                    								<input type="radio" name="<?php echo $item['id_item']; ?>" id="<?php echo $item['id_item']; ?>" value="<?php echo utf8_encode($opcion['valor_detalle']); ?>"><?php echo utf8_encode($opcion['texto_detalle']); ?>
			                    							</div>
			                    						</div>
			                    						<?php
		                    						}
		                    						?>
	                    						</div>
	                    						<?php
	                    						break;
	                    					case '10': //textbox min - max
	                    						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario_sa($item['cod_formulario_item']);
	                    						?>
	                    						<div class="col-md-12" id="div_<?php echo utf8_encode($item['id_item']);?>">
								            		<div class="form-group input-group-sm">
							                            <label for="<?php echo utf8_encode($OPCIONES[0]['cod_detalle_item']);?>"><?php echo utf8_encode($item['nombre_item'].' ('.$OPCIONES[0]['valor_detalle'].' - '.$OPCIONES[1]['valor_detalle'].')');?></label>
							                            <input type="text" id="<?php echo utf8_encode($OPCIONES[0]['id_item']);?>" data-valor="<?php echo utf8_encode($OPCIONES[0]['valor_detalle']);?>" placeholder="<?php echo utf8_encode($OPCIONES[0]['texto_detalle']);?>">
							                        </div>
								            	</div>
	                    						<?php
	                    						break;
	                    					case '11': //time
	                    						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario_sa($item['cod_formulario_item']);
	                    						?>
	                    						<div class='col-md-12' id="div_<?php echo utf8_encode($item['id_item']);?>">
										            <div class="form-group">
									                	<label for="fecha_recibido_pedido"><?php echo utf8_encode($OPCIONES[0]['texto_detalle']);?></label>
										                <div class='input-group input-group-sm time'>
										                    <span class="input-group-addon">
										                        <span class="fa fa-calendar"></span>
										                    </span>
										                    <input type='text' class="form-control input" id="<?php echo utf8_encode($OPCIONES[0]['id_item']);?>" />
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
	<?php

}
?>