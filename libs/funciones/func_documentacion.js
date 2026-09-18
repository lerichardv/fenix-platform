/*
 * Funciones jQuery generales para el módulo de Documentación.
 * @author      Dan Urquía
 * @date        2017-06-23
 */

/*
 * Función que obtiene el listado de todos los tipos de documentos para repositorio.
 */
function doc_constructor_tipo_documentos(path = ''){
	$.ajax({
		type: 'POST',
		url: path + 'mod_documentacion/funciones/doc_listado_tipo_documentos_activos.php',
		dataType: 'json',
		success: function(data) {
			$('#cod_tipo_documento').empty();
			$('#cod_tipo_documento').append( new Option('Select','-b') );
			/*Verifica si el arreglo trae mas de un registro,
			  en caso contrario lo llenara automaticamente*/
			if(data.length > 1){
				$.each( data, function( i, item ) {
					$('#cod_tipo_documento').append( new Option(item.tipo_documento,item.cod_tipo_documento) );
				});
			} else {
				$('#cod_tipo_documento').append( new Option(data[0].tipo_documento,data[0].cod_tipo_documento) );
			}
			$('.selectpicker').selectpicker('refresh');
		}
	});
}

/*
 * Función que inserta registro de descarga en el historial de la biblioteca virtual.
 */
function doc_insert_historial(x1, x2, path = ''){
	$.ajax({
		type: 'POST',
		url: path + 'mod_documentacion/funciones/doc_insertar_historial_descarga.php',
		data: ({
			x1: x1,
			x2: x2
		})
	});
}
