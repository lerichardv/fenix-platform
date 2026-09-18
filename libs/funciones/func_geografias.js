/*
 * Funciones jQuery para el módulo de geografía.
 * @author      Kevin Fúnez
 * @date        2017-03-26
 */
var site_url = window.location.protocol + '//' + window.location.hostname + '/';
/*
 * Función que obtiene los paises disponibles.
 */
function geo_constructor_paises(){
	$.ajax({
		type: 'POST',
		url: site_url + 'mod_geografia/funciones/geo_listado_paises.php',
		dataType: 'json',
		success: function(data) {
			/*Verifica si el arreglo trae mas de un registro,
			  en caso contrario lo llenara automaticamente*/
			if (data != null) {
			if(data.length > 1){
					$('#cod_pais').empty().append( new Option('Select','-b') );
					$('#cod_departamento').empty().append( new Option('Select','-b') ).selectpicker('refresh');
					$('#cod_municipio').empty().append( new Option('Select','-b') ).selectpicker('refresh');
					$('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
					$('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
					$.each( data, function( i, item ) {
						$('#cod_pais').append( new Option(item.pais,item.cod_pais) );
					});
				} else {
					$('#cod_pais').empty().append( new Option(data[0].pais,data[0].cod_pais) ).change();
				}
			}
			$('.selectpicker').selectpicker('refresh');
		}
	});
}
function grl_constructor_paises(){
	$.ajax({
		type: 'POST',
		url: site_url + 'mod_geografia/funciones/geo_listado_paises.php',
		dataType: 'json',
		success: function(data) {
			/*Verifica si el arreglo trae mas de un registro,
			  en caso contrario lo llenara automaticamente*/
			if (data != null) {
				if(data.length > 1){
					$('#cod_pais').empty().append( new Option('Select','-b') );
					$('#cod_departamento').empty().append( new Option('Select','-b') ).selectpicker('refresh');
					$('#cod_municipio').empty().append( new Option('Select','-b') ).selectpicker('refresh');
					$('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
					$('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
					$.each( data, function( i, item ) {
						$('#cod_pais').append( new Option(item.pais,item.cod_pais) );
					});
				} else {
					$('#cod_pais').empty().append( new Option(data[0].pais,data[0].cod_pais) ).change();
				}
			}
			$('.selectpicker').selectpicker('refresh');
		}
	});
}
/*
 * Función que obtiene los departamentos disponibles.
 *
 */
function geo_constructor_departamentospais(){
	//Limpia los listboxs relacionados al país
	$('#cod_departamento').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$('#cod_municipio').empty().append( new Option('Select','-b') ).selectpicker('refresh');
  $('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
  $('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$.ajax({
		type: 'POST',
		url: site_url + 'mod_geografia/funciones/geo_listado_departamentos.php',
    data: ({
      x1: pais//$("#cod_pais").val(),
      }),
		error: function(){
					$('#cod_departamento').empty().append( new Option('Select','-b') ).selectpicker('refresh');
					$('#cod_municipio').empty().append( new Option('Select','-b') ).selectpicker('refresh');
			   },
		dataType: 'json',
		success: function(data) {
			/*Verifica si el arreglo trae mas de un registro,
			  en caso contrario lo llenara automaticamente*/
			if (data != null) {
				if (data.length > 1) {
					$('#cod_departamento').empty().append(new Option('Select', '-b'));
					$.each(data, function(i, item) {
						$('#cod_departamento').append(new Option(item.departamento, item.cod_departamento));
					});
				} else {
					$('#cod_departamento').empty().append(new Option(data[0].departamento, data[0].cod_departamento)).change();
				}
			}
			$('.selectpicker').selectpicker('refresh');
		}
	});
}
/*
 * Función que obtiene los departamentos disponibles.
 *
 */
function geo_constructor_departamentos(){
	//Limpia los listboxs relacionados al país
	$('#cod_departamento').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$('#cod_municipio').empty().append( new Option('Select','-b') ).selectpicker('refresh');
  $('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
  $('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$.ajax({
		type: 'POST',
		url: site_url + 'mod_geografia/funciones/geo_listado_departamentos.php',
    data: ({
      x1: 1//$("#cod_pais").val(),
      }),
		error: function(){
					$('#cod_departamento').empty().append( new Option('Select','-b') ).selectpicker('refresh');
					$('#cod_municipio').empty().append( new Option('Select','-b') ).selectpicker('refresh');
			   },
		dataType: 'json',
		success: function(data) {
			/*Verifica si el arreglo trae mas de un registro,
			  en caso contrario lo llenara automaticamente*/
			if (data != null) {
				if(data.length > 1){
					$('#cod_departamento').empty().append( new Option('Select','-b') );
					$.each( data, function( i, item ) {
						$('#cod_departamento').append( new Option(item.departamento,item.cod_departamento) );
					});
				} else {
					$('#cod_departamento').empty().append( new Option(data[0].departamento,data[0].cod_departamento) ).change();
				}
			}
			$('.selectpicker').selectpicker('refresh');
		}
	});
}
function grl_constructor_departamentos(){
	//Limpia los listboxs relacionados al país
	$('#cod_departamento').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$('#cod_municipio').empty().append( new Option('Select','-b') ).selectpicker('refresh');
  $('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
  $('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$.ajax({
		type: 'POST',
		url: site_url + 'mod_geografia/funciones/geo_listado_departamentos.php',
    data: ({
      x1: 1//$("#cod_pais").val(),
      }),
		error: function(){
					$('#cod_departamento').empty().append( new Option('Select','-b') ).selectpicker('refresh');
					$('#cod_municipio').empty().append( new Option('Select','-b') ).selectpicker('refresh');
			   },
		dataType: 'json',
		success: function(data) {
			/*Verifica si el arreglo trae mas de un registro,
			  en caso contrario lo llenara automaticamente*/
			if (data != null) {
				if(data.length > 1){
					$('#cod_departamento').empty().append( new Option('Select','-b') );
					$.each( data, function( i, item ) {
						$('#cod_departamento').append( new Option(item.departamento,item.cod_departamento) );
					});
				} else {
					$('#cod_departamento').empty().append( new Option(data[0].departamento,data[0].cod_departamento) ).change();
				}
			}
			$('.selectpicker').selectpicker('refresh');
		}
	});
}
/*
 * Función que obtiene los departamentos disponibles por pais
 *
 */
function geo_constructor_departamentos_por_pais(){
	//Limpia los listboxs relacionados al país
	$('#cod_departamento').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$('#cod_municipio').empty().append( new Option('Select','-b') ).selectpicker('refresh');
  $('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
  $('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$.ajax({
		type: 'POST',
		url: site_url + 'mod_geografia/funciones/geo_listado_departamentos.php',
    data: ({
      x1: $("#cod_pais").val(),
      }),
		error: function(){
					$('#cod_departamento').empty().append( new Option('Select','-b') ).selectpicker('refresh');
					$('#cod_municipio').empty().append( new Option('Select','-b') ).selectpicker('refresh');
			   },
		dataType: 'json',
		success: function(data) {
			/*Verifica si el arreglo trae mas de un registro,
			  en caso contrario lo llenara automaticamente*/
			if (data != null) {
				if(data.length > 1){
					$('#cod_departamento').empty().append( new Option('Select','-b') );
					$.each( data, function( i, item ) {
						$('#cod_departamento').append( new Option(item.departamento,item.cod_departamento) );
					});
				} else {
					$('#cod_departamento').empty().append( new Option(data[0].departamento,data[0].cod_departamento) ).change();
				}
			}
			$('.selectpicker').selectpicker('refresh');
		}
	});
}
function grl_constructor_departamentos_por_pais(){
	//Limpia los listboxs relacionados al país
	$('#cod_departamento').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$('#cod_municipio').empty().append( new Option('Select','-b') ).selectpicker('refresh');
  $('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
  $('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$.ajax({
		type: 'POST',
		url: site_url + 'mod_geografia/funciones/geo_listado_departamentos.php',
    data: ({
      x1: $("#cod_pais").val(),
      }),
		error: function(){
					$('#cod_departamento').empty().append( new Option('Select','-b') ).selectpicker('refresh');
					$('#cod_municipio').empty().append( new Option('Select','-b') ).selectpicker('refresh');
			   },
		dataType: 'json',
		success: function(data) {
			/*Verifica si el arreglo trae mas de un registro,
			  en caso contrario lo llenara automaticamente*/
			if (data != null) {
				if(data.length > 1){
					$('#cod_departamento').empty().append( new Option('Select','-b') );
					$.each( data, function( i, item ) {
						$('#cod_departamento').append( new Option(item.departamento,item.cod_departamento) );
					});
				} else {
					$('#cod_departamento').empty().append( new Option(data[0].departamento,data[0].cod_departamento) ).change();
				}
			}
			$('.selectpicker').selectpicker('refresh');
		}
	});
}
/*
 * Función que obtiene los municipios disponibles y departamento seleccionados.
 *
 * var x1 int  Código del departamento seleccionado.
 */
function geo_constructor_municipios(){
	//Limpia los listboxs relacionados al departamento
	$('#cod_municipio').empty().append( new Option('Select','-b') ).selectpicker('refresh');
  $('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
  $('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$.ajax({
		type: 'POST',
		url: site_url + 'mod_geografia/funciones/geo_listado_municipios.php',
    	data: ({
        x1: 1,//$("#cod_pais").val(),
				x2: $("#cod_departamento").val()
			  }),
		error: function(){
				$(".alert").alert().show();
			   	$('#cod_municipio').empty().append( new Option('Select','-b') ).selectpicker('refresh');
          $('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
			   },
		dataType: 'json',
		success: function(data) {
			/*Verifica si el arreglo trae mas de un registro,
			  en caso contrario lo llenara automaticamente*/
			if (data != null) {
				if (data.length > 1) {
					$('#cod_municipio').empty().append(new Option('Select', '-b'));
					$.each(data, function(i, item) {
						$('#cod_municipio').append(new Option(item.municipio, item.cod_municipio));
					});
				} else {
					$('#cod_municipio').empty().append(new Option(data[0].municipio, data[0].cod_municipio)).change();
				}
			}
			$('.selectpicker').selectpicker('refresh');
		}
	});
}
function grl_constructor_municipios(){
	//Limpia los listboxs relacionados al departamento
	$('#cod_municipio').empty().append( new Option('Select','-b') ).selectpicker('refresh');
  $('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
  $('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$.ajax({
		type: 'POST',
		url: site_url + 'mod_geografia/funciones/geo_listado_municipios.php',
    	data: ({
        x1: 1,//$("#cod_pais").val(),
				x2: $("#cod_departamento").val()
			  }),
		error: function(){
				$(".alert").alert().show();
			   	$('#cod_municipio').empty().append( new Option('Select','-b') ).selectpicker('refresh');
          $('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
			   },
		dataType: 'json',
		success: function(data) {
			/*Verifica si el arreglo trae mas de un registro,
			  en caso contrario lo llenara automaticamente*/
			if (data != null) {
				if (data.length > 1) {
					$('#cod_municipio').empty().append(new Option('Select', '-b'));
					$.each(data, function(i, item) {
						$('#cod_municipio').append(new Option(item.municipio, item.cod_municipio));
					});
				} else {
					$('#cod_municipio').empty().append(new Option(data[0].municipio, data[0].cod_municipio)).change();
				}
			}
			$('.selectpicker').selectpicker('refresh');
		}
	});
}
/*
 * Función que obtiene los municipios disponibles y departamento seleccionados.
 *
 * var x1 int  Código del departamento seleccionado.
 */
function geo_constructor_municipiospais(){
	//Limpia los listboxs relacionados al departamento
	$('#cod_municipio').empty().append( new Option('Select','-b') ).selectpicker('refresh');
  $('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
  $('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$.ajax({
		type: 'POST',
		url: site_url + 'mod_geografia/funciones/geo_listado_municipios.php',
    	data: ({
        x1: pais,//$("#cod_pais").val(),
				x2: $("#cod_departamento").val()
			  }),
		error: function(){
				$(".alert").alert().show();
			   	$('#cod_municipio').empty().append( new Option('Select','-b') ).selectpicker('refresh');
          $('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
			   },
		dataType: 'json',
		success: function(data) {
			/*Verifica si el arreglo trae mas de un registro,
			  en caso contrario lo llenara automaticamente*/
			if (data != null) {
				if (data.length > 1) {
					$('#cod_municipio').empty().append(new Option('Select', '-b'));
					$.each(data, function(i, item) {
						$('#cod_municipio').append(new Option(item.municipio, item.cod_municipio));
					});
				} else {
					$('#cod_municipio').empty().append(new Option(data[0].municipio, data[0].cod_municipio)).change();
				}
			}
			$('.selectpicker').selectpicker('refresh');
		}
	});
}
/*
 * Función que obtiene los municipios disponibles y departamento seleccionados por pais
 *
 * var x1 int  Código del departamento seleccionado.
 */
function geo_constructor_municipios_por_pais(){
	//Limpia los listboxs relacionados al departamento
	$('#cod_municipio').empty().append( new Option('Select','-b') ).selectpicker('refresh');
  $('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
  $('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$.ajax({
		type: 'POST',
		url: site_url + 'mod_geografia/funciones/geo_listado_municipios.php',
    	data: ({
        x1: $("#cod_pais").val(),
				x2: $("#cod_departamento").val()
			  }),
		error: function(){
				$(".alert").alert().show();
			   	$('#cod_municipio').empty().append( new Option('Select','-b') ).selectpicker('refresh');
          $('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
			   },
		dataType: 'json',
		success: function(data) {
			/*Verifica si el arreglo trae mas de un registro,
			  en caso contrario lo llenara automaticamente*/
			if (data != null) {
				if(data.length > 1){
					$('#cod_municipio').empty().append( new Option('Select','-b') );
					$.each( data, function( i, item ) {
						$('#cod_municipio').append( new Option(item.municipio,item.cod_municipio) );
					});
				} else {
					$('#cod_municipio').empty().append( new Option(data[0].municipio,data[0].cod_municipio) ).change();
				}
			}
			$('.selectpicker').selectpicker('refresh');
		}
	});
}
function grl_constructor_municipios_por_pais(){
	//Limpia los listboxs relacionados al departamento
	$('#cod_municipio').empty().append( new Option('Select','-b') ).selectpicker('refresh');
  $('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
  $('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$.ajax({
		type: 'POST',
		url: site_url + 'mod_geografia/funciones/geo_listado_municipios.php',
    	data: ({
        x1: $("#cod_pais").val(),
				x2: $("#cod_departamento").val()
			  }),
		error: function(){
				$(".alert").alert().show();
			   	$('#cod_municipio').empty().append( new Option('Select','-b') ).selectpicker('refresh');
          $('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
			   },
		dataType: 'json',
		success: function(data) {
			/*Verifica si el arreglo trae mas de un registro,
			  en caso contrario lo llenara automaticamente*/
			if (data != null) {
				if (data.length > 1) {
					$('#cod_municipio').empty().append(new Option('Select', '-b'));
					$.each(data, function(i, item) {
						$('#cod_municipio').append(new Option(item.municipio, item.cod_municipio));
					});
				} else {
					$('#cod_municipio').empty().append(new Option(data[0].municipio, data[0].cod_municipio)).change();
				}
			}
			$('.selectpicker').selectpicker('refresh');
		}
	});
}
/*
 * Función que obtiene las ciudades disponibles por país, departamento y municipio seleccionados.
 *
 * var x1 int  Código del país seleccionado.
 * var x2 int  Código del departamento seleccionado.
 * var x3 int  Código del municipio seleccionado.
 */
function geo_constructor_ciudades(){
	//Limpia los listboxs relacionados al municipio
	$('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$.ajax({
		type: 'POST',
		url: site_url + 'mod_geografia/funciones/geo_listado_ciudades.php',
    	data: ({
			 	x1: 1,//$("#cod_pais").val(),
				x2: $("#cod_departamento").val(),
				x3: $("#cod_municipio").val()
			  }),
		error: function(){
			   	$('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
				  $('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
			   },
		dataType: 'json',
		success: function(data) {
			/*Verifica si el arreglo trae mas de un registro,
			  en caso contrario lo llenara automaticamente*/
			if (data != null) {
				if (data.length > 1) {
					$('#cod_ciudad').empty().append(new Option('Select', '-b'));
					$.each(data, function(i, item) {
						$('#cod_ciudad').append(new Option(item.ciudad, item.cod_ciudad));
					});
				} else {
					$('#cod_ciudad').empty().append(new Option(data[0].ciudad, data[0].cod_ciudad)).change();
				}
			}
			$('.selectpicker').selectpicker('refresh');
		}
	});
}
function grl_constructor_ciudades(){
	//Limpia los listboxs relacionados al municipio
	$('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$.ajax({
		type: 'POST',
		url: site_url + 'mod_geografia/funciones/geo_listado_ciudades.php',
    	data: ({
			 	x1: 1,//$("#cod_pais").val(),
				x2: $("#cod_departamento").val(),
				x3: $("#cod_municipio").val()
			  }),
		error: function(){
			   	$('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
				  $('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
			   },
		dataType: 'json',
		success: function(data) {
			/*Verifica si el arreglo trae mas de un registro,
			  en caso contrario lo llenara automaticamente*/
			if (data != null) {
				if (data.length > 1) {
					$('#cod_ciudad').empty().append(new Option('Select', '-b'));
					$.each(data, function(i, item) {
						$('#cod_ciudad').append(new Option(item.ciudad, item.cod_ciudad));
					});
				} else {
					$('#cod_ciudad').empty().append(new Option(data[0].ciudad, data[0].cod_ciudad)).change();
				}
			}
			$('.selectpicker').selectpicker('refresh');
		}
	});
}
/*
 * Función que obtiene las ciudades disponibles por país, departamento y municipio seleccionados.
 *
 * var x1 int  Código del país seleccionado.
 * var x2 int  Código del departamento seleccionado.
 * var x3 int  Código del municipio seleccionado.
 */
function geo_constructor_ciudadespais(){
	//Limpia los listboxs relacionados al municipio
	$('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$.ajax({
		type: 'POST',
		url: site_url + 'mod_geografia/funciones/geo_listado_ciudades.php',
    	data: ({
			 	x1: pais,//$("#cod_pais").val(),
				x2: $("#cod_departamento").val(),
				x3: $("#cod_municipio").val()
			  }),
		error: function(){
			   	$('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
				  $('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
			   },
		dataType: 'json',
		success: function(data) {
			/*Verifica si el arreglo trae mas de un registro,
			  en caso contrario lo llenara automaticamente*/
			if (data != null) {
				if (data.length > 1) {
					$('#cod_ciudad').empty().append(new Option('Select', '-b'));
					$.each(data, function(i, item) {
						$('#cod_ciudad').append(new Option(item.ciudad, item.cod_ciudad));
					});
				} else {
					$('#cod_ciudad').empty().append(new Option(data[0].ciudad, data[0].cod_ciudad)).change();
				}
			}
			$('.selectpicker').selectpicker('refresh');
		}
	});
}
/*
 * Función que obtiene las ciudades disponibles por país, departamento y municipio seleccionados por pais
 *
 * var x1 int  Código del país seleccionado.
 * var x2 int  Código del departamento seleccionado.
 * var x3 int  Código del municipio seleccionado.
 */
function geo_constructor_ciudades_por_pais(){
	//Limpia los listboxs relacionados al municipio
	$('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$.ajax({
		type: 'POST',
		url: site_url + 'mod_geografia/funciones/geo_listado_ciudades.php',
    	data: ({
			 	x1: $("#cod_pais").val(),
				x2: $("#cod_departamento").val(),
				x3: $("#cod_municipio").val()
			  }),
		error: function(){
			   	$('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
				  $('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
			   },
		dataType: 'json',
		success: function(data) {
			/*Verifica si el arreglo trae mas de un registro,
			  en caso contrario lo llenara automaticamente*/
			if (data != null) {
				if (data.length > 1) {
					$('#cod_ciudad').empty().append(new Option('Select', '-b'));
					$.each(data, function(i, item) {
						$('#cod_ciudad').append(new Option(item.ciudad, item.cod_ciudad));
					});
				} else {
					$('#cod_ciudad').empty().append(new Option(data[0].ciudad, data[0].cod_ciudad)).change();
				}
			}
			$('.selectpicker').selectpicker('refresh');
		}
	});
}
function grl_constructor_ciudades_por_pais(){
	//Limpia los listboxs relacionados al municipio
	$('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$.ajax({
		type: 'POST',
		url: site_url + 'mod_geografia/funciones/geo_listado_ciudades.php',
    	data: ({
			 	x1: $("#cod_pais").val(),
				x2: $("#cod_departamento").val(),
				x3: $("#cod_municipio").val()
			  }),
		error: function(){
			   	$('#cod_ciudad').empty().append( new Option('Select','-b') ).selectpicker('refresh');
				  $('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
			   },
		dataType: 'json',
		success: function(data) {
			/*Verifica si el arreglo trae mas de un registro,
			  en caso contrario lo llenara automaticamente*/
			if (data != null) {
				if (data.length > 1) {
					$('#cod_ciudad').empty().append(new Option('Select', '-b'));
					$.each(data, function(i, item) {
						$('#cod_ciudad').append(new Option(item.ciudad, item.cod_ciudad));
					});
				} else {
					$('#cod_ciudad').empty().append(new Option(data[0].ciudad, data[0].cod_ciudad)).change();
				}
			}
			$('.selectpicker').selectpicker('refresh');
		}
	});
}

/*
 * Función que obtiene las Zonas disponibles por país, departamento, municipio y ciudad seleccionados.
 *
 * var x1 int  Código del país seleccionado.
 * var x2 int  Código del departamento seleccionado.
 * var x3 int  Código del municipio seleccionado.
 * var x4 int  Código de la ciudad seleccionado.
 */
function geo_constructor_zonas(){
	//Limpia los listboxs relacionados a la ciudad
	$('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$.ajax({
		type: 'POST',
		url: site_url + 'mod_geografia/funciones/geo_listado_zonas.php',
    	data: ({
			  x1: 1,//$("#cod_pais").val(),
				x2: $("#cod_departamento").val(),
				x3: $("#cod_municipio").val(),
				x4: $("#cod_ciudad").val()
			  }),
		error: function(){
			   	$('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
			   },
		dataType: 'json',
		success: function(data) {
			/*Verifica si el arreglo trae mas de un registro,
			  en caso contrario lo llenara automaticamente*/
			if (data != null) {
				if (data.length > 1) {
					$('#cod_zona').empty().append(new Option('Select', '-b'));
					$.each(data, function(i, item) {
						var info = item.zona.split("|");
						$('#cod_zona').append($('<option>', { // new Option(info[0],item.cod_zona) );
							value: item.cod_zona,
							text: info[0],
							data: {
								content: info[0] + "<span class='label label-success'>" + info[1] + "</span>"
							}
						}));
					});
				} else {
					var info = data[0].zona.split("|");
					$('#cod_zona').empty().append($('<option>', {//new Option(info[0],data[0].cod_zona) );
						value: data[0].cod_zona,
						text: info[0],
						data: {
							content: info[0] + "<span class='label label-success'>" + info[1] + "</span>"
						}
					}));
				}
			}
			$('.selectpicker').selectpicker('refresh');
		}
	});
}
function grl_constructor_zonas(){
	//Limpia los listboxs relacionados a la ciudad
	$('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$.ajax({
		type: 'POST',
		url: site_url + 'mod_geografia/funciones/geo_listado_zonas.php',
    	data: ({
			  x1: 1,//$("#cod_pais").val(),
				x2: $("#cod_departamento").val(),
				x3: $("#cod_municipio").val(),
				x4: $("#cod_ciudad").val()
			  }),
		error: function(){
			   	$('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
			   },
		dataType: 'json',
		success: function(data) {
			/*Verifica si el arreglo trae mas de un registro,
			  en caso contrario lo llenara automaticamente*/
			if (data != null) {
				if (data.length > 1) {
					$('#cod_zona').empty().append(new Option('Select', '-b'));
					$.each(data, function(i, item) {
						var info = item.zona.split("|");
						$('#cod_zona').append($('<option>', { // new Option(info[0],item.cod_zona) );
							value: item.cod_zona,
							text: info[0],
							data: {
								content: info[0] + "<span class='label label-success'>" + info[1] + "</span>"
							}
						}));
					});
				} else {
					var info = data[0].zona.split("|");
					$('#cod_zona').empty().append($('<option>', {//new Option(info[0],data[0].cod_zona) );
						value: data[0].cod_zona,
						text: info[0],
						data: {
							content: info[0] + "<span class='label label-success'>" + info[1] + "</span>"
						}
					}));
				}
			}
			$('.selectpicker').selectpicker('refresh');
		}
	});
}
/*
 * Función que obtiene las Zonas disponibles por país, departamento, municipio y ciudad seleccionados.
 *
 * var x1 int  Código del país seleccionado.
 * var x2 int  Código del departamento seleccionado.
 * var x3 int  Código del municipio seleccionado.
 * var x4 int  Código de la ciudad seleccionado.
 */
function geo_constructor_zonaspais(){
	//Limpia los listboxs relacionados a la ciudad
	$('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$.ajax({
		type: 'POST',
		url: site_url + 'mod_geografia/funciones/geo_listado_zonas.php',
    	data: ({
			  x1: pais,//$("#cod_pais").val(),
				x2: $("#cod_departamento").val(),
				x3: $("#cod_municipio").val(),
				x4: $("#cod_ciudad").val()
			  }),
		error: function(){
			   	$('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
			   },
		dataType: 'json',
		success: function(data) {
			/*Verifica si el arreglo trae mas de un registro,
			  en caso contrario lo llenara automaticamente*/
			if (data != null) {
				if (data.length > 1) {
					$('#cod_zona').empty().append(new Option('Select', '-b'));
					$.each(data, function(i, item) {
						var info = item.zona.split("|");
						$('#cod_zona').append($('<option>', { // new Option(info[0],item.cod_zona) );
							value: item.cod_zona,
							text: info[0],
							data: {
								content: info[0] + "<span class='label label-success'>" + info[1] + "</span>"
							}
						}));
					});
				} else {
					var info = data[0].zona.split("|");
					$('#cod_zona').empty().append($('<option>', {//new Option(info[0],data[0].cod_zona) );
						value: data[0].cod_zona,
						text: info[0],
						data: {
							content: info[0] + "<span class='label label-success'>" + info[1] + "</span>"
						}
					}));
				}
			}
			$('.selectpicker').selectpicker('refresh');
		}
	});
}
function geo_constructor_zonas_casos(){
	//Limpia los listboxs relacionados a la ciudad
	//$('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
	$.ajax({
		type: 'POST',
		url: site_url + 'mod_geografia/funciones/geo_listado_zonas_casos.php',
    	data: ({
			  x1: 1//$("#cod_pais").val(),
				//x2: $("#cod_departamento").val(),
				//x3: //$("#cod_municipio").val(),
				//x4: //$("#cod_ciudad").val()
			  }),
		error: function(){
			   	$('#cod_zona').empty().append( new Option('Select','-b') ).selectpicker('refresh');
			   },
		dataType: 'json',
		success: function(data) {
			/*Verifica si el arreglo trae mas de un registro,
			  en caso contrario lo llenara automaticamente*/
			if (data != null) {
				if (data.length > 1) {
					//$('#cod_zona').empty().append( new Option('Select','-b') );
					$.each(data, function(i, item) {
						$('#cod_zona').append(new Option(item.zona, item.cod_zona));
					});
				} else {
					$('#cod_zona').empty().append(new Option(data[0].zona, data[0].cod_zona));
				}
			}
			$('.selectpicker').selectpicker('refresh');
		}
	});
}
/*
 * Valida que el correo eletrónico este correctamente escrito.
 */
function geo_validarLatitudLongitud(latitud_longitud) {
	var latlngVal = /^-?([0-8]?[0-9]|90)\.[0-9]{1,7},-?((1?[0-7]?|[0-9]?)[0-9]|180)\.[0-9]{1,7}$/;
	if(!latlngVal.test(latitud_longitud)) {
		return false;
	} else {
		return true;
	}
}
