<?PHP
/*
 * Listado de todas las becas que estan pendientes de revisión.
 * @author      Dan Urquía
 * @date        2014-04-30
 */
session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once ("../../libs/db_classes/db_noticias.php");
/*INSTANCIAMIENTOS*/
$DB_NOTICIA = new db_noticia();
$NOTICIAS   = $DB_NOTICIA->get_noticias();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Listado de Noticias</title>
</head>
<script type="text/javascript">
  	$(document).ready(function(){
		//Habilita los selects para mobile
		if( /Android|webOS|iPhone|iPad|iPod|MacOSBlackBerry/i.test(navigator.userAgent) ) {
	    	$('.selectpicker').selectpicker('mobile');
	    }
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
                    pageSize: 'LEGAL',
                    title: 'News',
                },
                'print',
            ]
        });

	});//fin document.Ready
	//Habilita y deshabilita una noticia
	function not_cambiar_estado_noticia(cod_noticia){
		 if($('#activo_'+cod_noticia).hasClass('fas fa-check-square')) {
			 estatus = 0;
			$('#activo_'+cod_noticia).removeClass('fas fa-check-square').addClass('far fa-check-square')
			}
		else
		 {
			estatus = 1;
			$('#activo_'+cod_noticia).removeClass('far fa-check-square').addClass('fas fa-check-square')
		}
		$.ajax({
			type: 'POST',
			url: 'mod_noticias/funciones/not_cambiar_estado_noticia.php',
			dataType: 'json',
	    	data: ({
				  	x1:cod_noticia,
					x2:estatus
				  }),
			success: function(data) {

				var info = data.split("|");
				info[0] == 1 ? tipo = 'danger' : tipo = 'success';
				grl_mensaje('', info[1], tipo);
			},
			error: function(){
	   			grl_mensaje('', 'Error al actualizar el estado', 'danger');
		   }
		});//fin ajax
	}
</script>

<style>
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
	.alinear{
		overflow:auto;
		overflow-style:marquee-block;
		padding:0 2px;

	}
</style>

<body>
<div class="responsive_table_container">
    	<div class="row" style="overflow:auto; min-width:100px;">
			<div class="col-sm-12">
				<div class="panel panel-primary">
					<div class="panel-heading panel_cabecera">
						<h3 class="panel-title">Listado de Noticias</h3>
					</div>
					<div class="panel-body panel_cuerpo input-group-sm">
						<input  type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Busqueda" />
					</div>
				  	<table class="table display row-border responsive" id="dev-table">
						<thead>
							<tr class="active info">
								<th width="7%" class="translate" data-traducir_english="No." data-traducir_spanish="Nro.">Nro.</th>
								<th width="20%" class="translate" data-traducir_english="Title" data-traducir_spanish="Titulo">Titulo</th>
								<th width="43%" class="translate" data-traducir_english="Content" data-traducir_spanish="Contenido">Contenido</th>
								<th width="10%" class="translate" data-traducir_english="Image" data-traducir_spanish="Imagen">Imagen</th>
                                <th width="13%" class="translate" data-traducir_english="Date" data-traducir_spanish="Fecha">Fecha </th>
                                <th width="7%" class="translate" data-traducir_english="Active" data-traducir_spanish="Activo" align="center">Activo</th>
							</tr>
						</thead>
						<tbody>
                        <?PHP
							$cuerpo_tabla = "";
							if(count($NOTICIAS) > 0){
								$correlativo = 1;
								foreach($NOTICIAS as $NOTICIA){
									$activo='';
									if ($NOTICIA['activo']==1){
											$activo = 'fas fa-check-square';}
									else{
										$activo='far fa-check-square';};
									$cuerpo_tabla .= '<tr>
														<td>'.$correlativo.'</td>
														<td>'.utf8_encode($NOTICIA['titulo']).'</td>
														<td>'.utf8_encode($NOTICIA['contenido']).'</td>
														<td><img src="'.utf8_encode($NOTICIA['imagen']).'" onerror="this.src=\'libs/imgs/Logo.png\';" width="50" height="50" class="img-circle img-responsive" onclick="not_cambiar_estado_noticia('.$NOTICIA['cod_noticia'].')"></td>
														<td>'.utf8_encode($NOTICIA['fecha_insert']).'</td>
														<td align="center">
															<i id="activo_'.$NOTICIA['cod_noticia'].'" name="activo_'.$NOTICIA['cod_noticia'].'" class="fa-2x '.$activo.' text-info" onclick="not_cambiar_estado_noticia('.$NOTICIA['cod_noticia'].') "></i>
														</td>
													  </tr>';
									$correlativo++;
								}
							} else { //Si no hay registros informará al usuario
								$cuerpo_tabla .= '<tr>
													<td colspan="6">No hay registros.</td>
												  </tr>';
							}
							echo $cuerpo_tabla;
						?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</body>
</html>