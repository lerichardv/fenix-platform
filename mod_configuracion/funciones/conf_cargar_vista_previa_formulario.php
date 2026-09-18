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
$FORMULARIO 	= $DB_CONG->conf_obtener_info_formulario($cod_formulario);

$ITEMS 			= $DB_CONG->conf_obtener_items_activos_formulario($cod_formulario);
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
                            <th width="30%">Nombre Item</th>
                            <th width="30%">Tipo Item</th>
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
                    				<td><?php echo utf8_encode($item['tipo_item']); ?></td>
                    				<td>
	                    				<?php
	                    				switch ($item['cod_tipo_item']) {
	                    					case '1':
	                    						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario($item['cod_item']);
	                    						?>
	                    						<div class="material-switch">
						                            <input data-val_check="<?php echo utf8_encode($OPCIONES[0]['valor_item']);?>" data-val_uncheck="<?php echo utf8_encode($OPCIONES[1]['valor_item']);?>" class="checkbox" id="checkbox_<?php echo utf8_encode($item['cod_item']);?>" data-id="<?php echo utf8_encode($item['cod_item']);?>" name="checkbox_<?php echo utf8_encode($item['cod_item']);?>" type="checkbox" <?php echo ($item['activo'] == 1 ? 'checked="checked"':'');?>/>
						                            <label for="checkbox_<?php echo utf8_encode($item['cod_item']);?>"></label>
						                        </div>
	                    						<?php
	                    						break;
	                    					case '2':
	                    						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario($item['cod_item']);
	                    						?>
	                    						<div class="input-group">
	                    							<span class="input-group-sm">
	                    								<input type="text" id="<?php echo utf8_encode($OPCIONES[0]['cod_detalle_item']);?>" data-valor="<?php echo utf8_encode($OPCIONES[0]['valor_item']);?>" placeholder="<?php echo utf8_encode($OPCIONES[0]['texto_item']);?>">
	                    							</span>
	                    							<input type="text" id="<?php echo utf8_encode($OPCIONES[1]['cod_detalle_item']);?>" data-valor="<?php echo utf8_encode($OPCIONES[1]['valor_item']);?>" placeholder="<?php echo utf8_encode($OPCIONES[1]['texto_item']);?>">
	                    						</div>
	                    						<?php
	                    						break;
	                    					case '3':
	                    						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario($item['cod_item']);
	                    						?>
							                    <div class="col-md-12" id="">
													<label for="cod_item_<?php echo utf8_encode($item['cod_item']);?>"><?php echo utf8_encode($item['nombre_item']);?></label>
													<div class="form-group show-tick">
														<select class="selectpicker show-menu-arrow requerido" id="cod_item_<?php echo utf8_encode($item['cod_item']);?>" name="cod_item_<?php echo utf8_encode($item['cod_item']);?>" data-live-search="true" title="Seleccione">
															<?php
								                        		foreach ($OPCIONES as $opcion) {
								                        			?>
								                        			<option value="<?php echo utf8_encode($opcion['valor_item']);?>"><?php echo utf8_encode($opcion['texto_item']);?></option>
								                        			<?php	
								                        		}
								                        		?>
														</select>
													</div>
												</div>
	                    						<?php
	                    						break;
	                    					case '4':
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
							                        <div class="container-upload-box" id="div_fotografia_<?php echo $item['cod_item']; ?>">
							                            <button data-disabled="true" data-id="<?php echo $item['cod_item']; ?>" class="smooth-transition btn-select-file">
							                                <i class="icon-file fa fa-file fa-lg"> </i>
							                                <span id="msj-btn-file-select-foto" class="msj-btn-file"> <?php echo utf8_encode($OPCIONES[0]['texto_item']);?></span>
							                            </button>
							                            <input type="file" title="Seleccione Archivo..." class="input-file-hidden" id="<?php echo $item['cod_item']; ?>" accept=".jpg,.png,.bmp,.gif,.tiff,.svg,.pdf,.docx,.doc,.xls,.xlsx">
							                        </div>
							            		</div>
	                    						<?php
	                    						break;
	                    					case '5':
	                    						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario($item['cod_item']);
	                    						?>
	                    						<div class="col-md-12">
								            		<div class="form-group input-group-sm">
							                            <label for="<?php echo utf8_encode($OPCIONES[0]['cod_detalle_item']);?>"><?php echo utf8_encode($OPCIONES[1]['texto_item']);?></label>
							                            <input type="text" id="<?php echo utf8_encode($OPCIONES[0]['cod_detalle_item']);?>" data-valor="<?php echo utf8_encode($OPCIONES[0]['valor_item']);?>" placeholder="<?php echo utf8_encode($OPCIONES[0]['texto_item']);?>">
							                        </div>
								            	</div>
	                    						<?php
	                    						break;
	                    					case '6':
	                    						$OPCIONES = $DB_CONG->conf_obtener_opciones_items_formulario($item['cod_item']);
	                    						?>
	                    						<div class='col-md-12'>
										            <div class="form-group">
									                	<label for="fecha_recibido_pedido"><?php echo utf8_encode($OPCIONES[1]['texto_item']);?></label>
										                <div class='input-group input-group-sm date'>
										                    <span class="input-group-addon">
										                        <span class="fa fa-calendar"></span>
										                    </span>
										                    <input type='text' class="form-control input" id="<?php echo utf8_encode($OPCIONES[1]['cod_detalle_item']);?>" />
										                </div>
										            </div>
										        </div>
	                    						<?php
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