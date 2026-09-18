<?php
/*
* 	Genera el listado de todos los documentos en repositorio extraidos por medio de losaprametros enviados
* 	@author 	Dan Urquía
* 	@date 		2017-06-26
*/

session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_documentacion.php");
/*INSTANCIAMIENTOS*/
$DB_DOCUMENTOS 	= new db_documentacion();
$arr_cod_gerencias      = ($_POST['x1'] != '-b') ? $_POST['x1'] : 0;
$arr_cod_tipo_documetos = ($_POST['x2'] != '-b') ? $_POST['x2'] : 0;
$parametros             = utf8_decode(trim(($_POST['x3'])));
$DOCUMENTOS    	        = $DB_DOCUMENTOS->get_documentos_por_parametros($arr_cod_gerencias,
																																				$arr_cod_tipo_documetos,
																																				$parametros);
$cuerpo_tabla = "";
$cuerpo_tabla .= '<thead>
											<tr class="active info">
													<th class="hidden-xs translate" data-traducir_english="Grower" data-traducir_spanish="Finca" width="10%">Grower</th>
	                        <th class="hidden-xs translate" data-traducir_english="Document type" data-traducir_spanish="Tipo documento" width="10%">Document type</th>
	                        <th class="translate" data-traducir_english="Title" data-traducir_spanish="Título" width="20%">Title</th>
	                        <th class="hidden-xs translate" data-traducir_english="Description" data-traducir_spanish="Descripción" width="55%">Description</th>
	                        <th class="translate" data-traducir_english="Download" data-traducir_spanish="Descargar" width="10%">Download</th>
											</tr>
									</thead>
									<tbody>';
if(count($DOCUMENTOS) > 0) {
	$correlativo = 1;
    foreach($DOCUMENTOS as $DOCUMENTO){
      $row_color = '';
			//Verifica el flag para asignarle un color a la linea
			$row_color = ($DOCUMENTO['flag'] == 1) ? "#c0fdd0" : "#ecd8bb";
      $cuerpo_tabla .= '
												<tr style="background-color:'.$row_color.'">
													<td style="display:none;">'.utf8_encode($DOCUMENTO['cod_documento']).'</td>
										      <td class="hidden-xs">'.utf8_encode($DOCUMENTO['gerencia']).'</td>
										      <td class="hidden-xs">'.utf8_encode($DOCUMENTO['tipo_documento']).'</td>
													<td>
					                    <a role="button" data-toggle="collapse" data-parent="#table-container" href="#collapse'.$correlativo.'" aria-expanded="true" aria-controls="collapseOne">
					                        '.utf8_encode($DOCUMENTO['titulo_documento']).'
					                    </a>
					                </td>
										      <td class="hidden-xs">'.utf8_encode($DOCUMENTO['descripcion']).'</td>
													<td style="text-align:center;">
													 	<a target="_blank" href="'.utf8_encode($DOCUMENTO['PDF']).'">
															<i style="color:'.utf8_encode($DOCUMENTO['color_tipo']).';" class="fas fa-cloud-download-alt fa-2x boton_descarga" documento_num="'.utf8_encode($DOCUMENTO['cod_documento']).'" aria-hidden="true"></i>
														</a>
														<!-- <span class="badge" style="background-color:'.utf8_encode($DOCUMENTO['color_tipo']).';">'.utf8_encode($DOCUMENTO['contador_descargas']).' descargas</span> -->
														<span class="badge" style="background-color:'.utf8_encode($DOCUMENTO['color_tipo']).';"></span>
													</td>
									      </tr>


												<tr>
						                <td colspan="6" class="no-padding">
						                    <div id="collapse'.$correlativo.'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
						                        <div class="panel-body">
						                            <div class="row">
						                                <div class="col-xs-12 hidden-sm hidden-md hidden-lg">
						                                    <h4 class="translate" data-traducir_english="Program" data-traducir_spanish="Programa">Program</h4>
						                                    <p>'.utf8_encode($DOCUMENTO['gerencia']).'</p>
						                                </div>
						                                <div class="col-xs-12 col-sm-12 hidden-md hidden-lg">
						                                    <h4 class="translate" data-traducir_english="Document type" data-traducir_spanish="Tipo documento">Document type</h4>
						                                    <p>'.utf8_encode($DOCUMENTO['tipo_documento']).'</p>
						                                </div>
						                                <div class="col-xs-12 col-sm-12 hidden-md hidden-lg">
						                                    <h4 class="translate" data-traducir_english="Description" data-traducir_spanish="Descripción">Description</h4>
						                                    <p>'.utf8_encode($DOCUMENTO['descripcion']).'</p>
						                                </div>
						                                <div class="col-md-6 col-sm-12">
						                                    <h4 class="translate" data-traducir_english="Labels" data-traducir_spanish="Etiquetas">Labels</h4>
						                                    <p>'.utf8_encode($DOCUMENTO['palabras_claves']).'</p>
						                                </div>
						                            </div>
						                        </div>
						                    </div>
						                </td>
						            </tr>';

      $correlativo++;
	}
} else { //Si no hay registros informará al usuario
    $cuerpo_tabla .= '<tr>
												<td align="center" style="background-color:#F2DEDE;" colspan="6">
													<span class="fa-stack fa-lg">
														<i class="fa fa-circle fa-stack-2x"></i>
														<i class="fa fa-exclamation fa-stack-1x fa-inverse"></i>
													</span>
													<strong class="translate" data-traducir_english="No documents were found with the established parameters, please specify." data-traducir_spanish="No se encontraron documentos con los parámetros establecidos, favor especificar.">No documents were found with the established parameters, please specify.</strong>
												</td>
											</tr>';
}

$cuerpo_tabla .= "</tbody>
								</table>";
echo $cuerpo_tabla;
?>
