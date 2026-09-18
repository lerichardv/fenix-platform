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
include_once("../../libs/db_classes/db_general.php");
/*INSTANCIAMIENTOS*/
$DB_GENERAL = new db_general();
$NOTICIAS   = $DB_GENERAL->get_noticia_activa();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Listado de Noticias</title>
</head>

<script type="text/javascript">
  $(document).ready(function(){
	  	/*
		 *	Ejecuta el evento onClick en toda la fila enfocada
		 */
	/*	var table = document.getElementById("dev-table");
		var rows = table.getElementsByTagName("tr");
		for (i = 0; i < rows.length; i++) {
			var currentRow = table.rows[i];
			var createClickHandler = 
				function(row) 
				{
					return function() { 
									   	var cell = row.getElementsByTagName("td")[0];
										var id = cell.innerHTML;
										if ($.isNumeric( id )){
											per_get_vista_permisos(id,2);//Segundo parametro es el tipo de usuario
										}
									  };
				};
	
			currentRow.onclick = createClickHandler(currentRow);
		}	
		
		/*
		 * Función que realiza la "busqueda" dentro de la tabla con información
		 */
		/*(function(){
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
									var no_results = $('<tr class="filterTable_no_results"><td colspan="2">No hay resultados.</td></tr>')
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
		})*/
  })
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
</style>
 
<body>
<div class="busqueda_contenedor">
    	<div class="row" style="overflow:auto; min-width:100px;">
			<div class="col-sm-12">
				<div class="panel panel-primary">
					<div class="panel-heading panel_cabecera">
						<h3 class="panel-title">Listado de Noticias</h3>
						<div class="pull-right">
							<span class="clickable filter" data-toggle="tooltip" title="Realizar busqueda" data-container="body">
								<i class="fa fa-search"></i>
							</span>
						</div>
					</div>
					<div class="panel-body panel_cuerpo input-group-sm">
						<input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Busqueda" />
					</div>
					<table class="table table-hover" id="dev-table">
						<thead>
							<tr class="active info">
								<th width="10%">Nro.</th>
								<th width="20%">Titulo</th>
								<th width="34%">Contenido</th>
								<th width="10%">Imagen</th>
                                <th width="13%">Fecha </th>
                                <th width="13%">Activo</th>
							</tr>
						</thead>
						<tbody>
                        <?PHP
							$cuerpo_tabla = "";
							if(count($NOTICIAS) > 0){
								foreach($NOTICIAS as $NOTICIA){
								
									$cuerpo_tabla .= '<tr>
														<td>'.$NOTICIA['cod_noticia'].'</td>
														<td>'.utf8_encode($NOTICIA['titulo']).'</td>
														<td>'.utf8_encode($NOTICIA['contenido']).'</td>
														<td>'.utf8_encode($NOTICIA['imagen']).'</td>
														<td>'.utf8_encode($NOTICIA['fecha_insert']).'</td>
														<td> 
														<input type="checkbox" name="activo" value="checked" > 
														</td>	
													  </tr>';
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