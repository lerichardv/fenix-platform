/*
 * Funciones jQuery generales para el Sistema ASJ.
 * @author      Kevin Fúnez/Dan Urquía
 * @date        2017-06-14/2015-28-05
 */
var site_url = window.location.protocol + '//' + window.location.hostname + '/';

/*
 * Regresa al menú principal.
 */
error = false;

function regresar_home(){
	window.location.href = "index.php";
}
/*
 * Función que verifica que el campo no este vacio.
 *
 * var objeto char   Objeto que se esta evaluando.
 */
function grl_campo_no_vacio(objeto){
	//Verifica que el campo tenga información.
	if($(objeto).val() == ''){
		$(objeto).parent().removeClass('has-success').addClass('has-error');
		return false;
	} else {
		$(objeto).parent().removeClass('has-error');
		return true;
	}
};
/*
 * Verifica que la contraseña cumpla con los requisitos mñinimos de evaluación.
 *
 * var objeto char   Objeto que se esta evaluando.
 */
 function grl_verificar_contrasenia (objeto,objeto){
	//var pass = $("#nuevo_pass").val();

	// Valida que la contraseña no sea menor a 8 caracteres.
	if ( pass.length < 8 ) {
		$('#length').removeClass('fa-check').addClass('fa-times');
		$('#length').css('color', 'red');
		error = true;
	if ( passc.length < 8 ) {
		$('#length').removeClass('fa-check').addClass('fa-times');
		$('#length').css('color', 'red');
		error = true;
	} }
	else {
		$('#length').removeClass('fa-times').addClass('fa-check');
		$('#length').css('color', 'green');
	  error = false; //=0
	}

	// Valida que exista una letra.
	if ( pass.match(/[a-z]/) ) {
		$('#letter').removeClass('fa-times').addClass('fa-check');
		$('#letter').css('color', 'green');
		error = false; //=0
	}
	if ( passc.match(/[a-z]/) ) {
		$('#letter').removeClass('fa-times').addClass('fa-check');
		$('#letter').css('color', 'green');
		error = false; //=0
	} else {
		$('#letter').removeClass('fa-check').addClass('fa-times');
		$('#letter').css('color', 'red');
	   error = true;
	}

	// Valida que exista una letra mayúscula.
	if ( pass.match(/[A-Z]/) ) {
		$('#capital').removeClass('fa-times').addClass('fa-check');
		$('#capital').css('color', 'green');
		error = false;
	} if ( passc.match(/[A-Z]/) ) {
		$('#capital').removeClass('fa-times').addClass('fa-check');
		$('#capital').css('color', 'green');
		error = false;
	}else {
		$('#capital').removeClass('fa-check').addClass('fa-times');
		$('#capital').css('color', 'red');
		error = true;
	}

	// Valida un número.
	if ( pass.match(/\d/) ) {
		$('#number').removeClass('fa-times').addClass('fa-check');
		$('#number').css('color', 'green');
        error = false;
	}if ( passc.match(/[A-Z]/) ) {
		$('#capital').removeClass('fa-times').addClass('fa-check');
		$('#capital').css('color', 'green');
		error = false;
	} else {
		$('#number').removeClass('fa-check').addClass('fa-times');
		$('#number').css('color', 'red');
        error = true;
	}
}

/*
 * Función que obtiene la interfaz de usuario para el módulo seleccionado.
 *
 * var cod_modulo int  Código del módulo seleccionado.
 */
function grl_obtener_modulo(cod_modulo){
	$.ajax({
		type: 'POST',
		url: site_url + 'modulo.php',
		data: {x1: cod_modulo},
		success: function (data) {
			$('#div_cuerpo').empty();
			$('#div_cuerpo').html(data);
		}
	});
}
/*
 * Función que obtiene el cuerpo que tendra el menú del módulo, en dado caso no tener
 * un menú principal, se desplegará cortina_cuerpo.php.
 *
 * var cod_modulo int     Código del módulo seleccionado.
 * var ruta       string  Dirección en donde se encuentra el archivo que se cargará.
 */
function grl_obtener_cuerpo_menu(cod_modulo, ruta, parametro, div, cod_menu){
	// loader.show();
	//toggleModuleNav();
	if(ruta == ''){ ruta = "cortina_cuerpo.php"; }
		$.ajax({
			type: 'POST',
			url: ruta,
			data:
			{
				x1: cod_modulo,
				x20: parametro,
			},
			success: function (data) {
				jQuery.ajaxSetup({ async: false });
				$('#div_cuerpo_menu').empty();
				$('#div_cuerpo_menu').html(data);
				$('#modal_loading').modal('hide');
				conf_cargar_formularios_por_menu(div, cod_modulo, cod_menu);
				jQuery.ajaxSetup({ async: true });
				// loader.hide();
			}
		});
}

/*
 * Función que obtiene la interfaz de usuario para ventanas especiales.
 *
 * var cod_usuario int  Código del usuario en sesión.
 */
function grl_obtener_perfil_usuario(){
	$.ajax({
		type: 'POST',
		url: site_url + 'mod_admin_usuarios/ui/usu_perfil_usuario.php',
		success: function (data) {
			$('#div_cuerpo').empty();
			$('#div_cuerpo').html(data);
		}
	});
}



/*
 * Función para llenar un listbox con la cantidad de años indicada.
 *
 * var elemento    string  Nombre del elemento a llenar.
 * var anio_inicio int     Año desde el que comenzará el llenado.
 * var anio_fin    int     Año en el que terminará el llenado.
 */
function grl_llenado_anios(elemento, anio_inicio, anio_fin){
	for(i = anio_inicio; i >= anio_fin; i--) {
		$(elemento).append( new Option(i,i) );
	}
	$('.selectpicker').selectpicker('refresh');
}

/*
 * Contructor del listado de chequeo.
 */
function grl_constructor_checklist(){
	$('.list-group.checked-list-box .list-group-item').each(function () {

		// Configuración
		var $widget = $(this),
			$checkbox = $('<input type="checkbox" class="hidden" />'),
			color = ($widget.data('color') ? $widget.data('color') : "primary")
			style = ($widget.data('style') == "button" ? "btn-" : "list-group-item-"),
			settings = {
				on: {
					icon: 'fa fa-check-square-o'
				},
				off: {
					icon: 'fa fa-square-o'
				}
			};

		$widget.css('cursor', 'pointer')
		$widget.append($checkbox);

		// Manejador de eventos
		$widget.on('click', function () {
			$checkbox.prop('checked', !$checkbox.is(':checked'));
			$checkbox.triggerHandler('change');
			updateDisplay();
		});
		$checkbox.on('change', function () {
			updateDisplay();
		});


		// Acciones
		function updateDisplay() {
			var isChecked = $checkbox.is(':checked');

			// Pone el estado del boton
			$widget.data('state', (isChecked) ? "on" : "off");

			// Pone el icono del boton
			$widget.find('.state-icon')
				.removeClass()
				.addClass('state-icon ' + settings[$widget.data('state')].icon);

			// Actualiza el color del boton
			if (isChecked) {
				$widget.removeClass('btn-danger');
				$widget.addClass(style + color + ' active');
			} else {
				$widget.removeClass(style + color + ' active');
			}
		}

		// Inicializacion
		function init() {

			if ($widget.data('checked') == true) {
				$checkbox.prop('checked', !$checkbox.is(':checked'));
			}

			updateDisplay();

			// Inyecta el icono, si es aplicable
			if ($widget.find('.state-icon').length == 0) {
				$widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '">&nbsp;</span>');
			}
		}
		init();
	});

	$('#get-checked-data').on('click', function(event) {
		event.preventDefault();
		var checkedItems = {}, counter = 0;
		$("#check-list-box li.active").each(function(idx, li) {
			checkedItems[counter] = $(li).text();
			counter++;
		});
		$('#display-json').html(JSON.stringify(checkedItems, null, '\t'));
	});
	//----Final checklist ----
}

/*
 * Valida que el correo eletrónico este correctamente escrito.
 */
function grl_validarEmail(email) {
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	if( !emailReg.test( email ) ) {
		return false;
	} else {
		return true;
	}
}

/*
 * Función para mostrar un mensaje para el usuario.
 *
 * var error_titulo       char  Parte del mensaje que ira resaltado en negritas.
 * var error_descripcion  char  Parte del mensaje que ira sin negritas.
 * var tipo_mensaje       char  Clase que se le agregara al aspecto del mensaje.
 */
function grl_mensaje_original(error_titulo, error_descripcion, tipo_mensaje){
	setTimeout(function() {
		$.bootstrapGrowl('<strong>' + error_titulo + '</strong> ' + error_descripcion, {
			type: tipo_mensaje,
			align: 'center',
			width: 'auto',
			allow_dismiss: false,
			delay: 4000
		});
	}, 100);
}

/*
 * Función para mostrar un mensaje para el usuario.
 *
 * var error_titulo       char  Parte del mensaje que ira resaltado en negritas.
 * var error_descripcion  char  Parte del mensaje que ira sin negritas.
 * var tipo_mensaje       char  Clase que se le agregara al aspecto del mensaje.
 * var tiempo_mensaje  	  int 	Tiempo que estará activo el mensaje en pantalla en segundos.
 */
function grl_mensaje(error_titulo, error_descripcion, tipo_mensaje, tiempo_mensaje)
{
	if(tiempo_mensaje == null || tiempo_mensaje == 0)
		tiempo_mensaje = 4000;
	else
		tiempo_mensaje = tiempo_mensaje * 1000;
	setTimeout(function() {
		$.bootstrapGrowl('<strong>' + error_titulo + '</strong> ' + error_descripcion, {
			type: tipo_mensaje,
			align: 'center',
			width: 'auto',
			allow_dismiss: false,
			delay: tiempo_mensaje
		});
	}, 100);
}

/*
 * Reccorre la columna indicada de una tabla y almacena los valores en celdas dentro de un arreglo.
 */
function grl_arreglo_columa(tabla, col_id) {
	var elementos = new Array();
	$("#" + tabla + " > tbody > tr > td:nth-child(" + col_id + ")").each(function( index, value ) {
		elementos[index] = $(value).text();
		elementos[index] = elementos[index].replace(/\u00e9/g,"&eacute;");
		elementos[index] = elementos[index].replace(/\u00f3/g,"&oacute;");
		elementos[index] = elementos[index].replace(/\u00e1/g,"&aacute;");
		elementos[index] = elementos[index].replace(/\u00ed/g,"&iacute;");
		elementos[index] = elementos[index].replace(/\u00e4/g,"&auml;");
		elementos[index] = elementos[index].replace(/\u00fc/g,"&uuml;");
		elementos[index] = elementos[index].replace(/\u00f6/g,"&ouml;");
		elementos[index] = elementos[index].replace(/\u00e3/g,"&atilde;");
		elementos[index] = elementos[index].replace(/\u00f8/g,"&oslash;");
		elementos[index] = elementos[index].replace(/\u00d8/g,"&Oslash;");
		elementos[index] = elementos[index].replace(/\u00f5/g,"&otilde;");
		elementos[index] = elementos[index].replace(/\u00f1/g,"&ntilde;");
		elementos[index] = elementos[index].replace(/\u00d1/g,"&Ntilde;");
		elementos[index] = elementos[index].replace(/\u00f4/g,"&ocirc;");
		elementos[index] = elementos[index].replace(/\u00e6/g,"&aelig;");
		elementos[index] = elementos[index].replace(/\u00c5/g,"&Aring;");
		elementos[index] = elementos[index].replace(/\u00e8/g,"&egrave;");
		elementos[index] = elementos[index].replace(/\u00ec/g,"&igrave;");
		elementos[index] = elementos[index].replace(/\u00c1/g,"&Aacute;");
		elementos[index] = elementos[index].replace(/\u00c9/g,"&Eacute;");
		elementos[index] = elementos[index].replace(/\u00cd/g,"&Iacute;");
		elementos[index] = elementos[index].replace(/\u00d3/g,"&Oacute;");
		elementos[index] = elementos[index].replace(/\u00da/g,"&Uacute;");
		elementos[index] = elementos[index].replace(/\u00fa/g,"&uacute;");
		elementos[index] = elementos[index].replace(/\u00fc/g,"&uuml;");
		elementos[index] = elementos[index].replace(/\u00dc/g,"&Uuml;");
		elementos[index] = elementos[index].replace(/\u00d1/g,"&Ntilde");
		elementos[index] = elementos[index].replace(/\u00bf/g,"&iquest;");
		elementos[index] = elementos[index].replace(/,/g, "|");
	});

	return elementos;
}

/*
 * Función que obtiene los elementos que se utilizaran en acciones para el usuario.
 *
 * var x1  int  Código del módulo.
 * var x2  int  Código del flujo actual.
 * var x3  int  Tipo de usuario.
 */
function grl_acciones_por_modulo(x1, x2, x3){
	$.ajax({
		type: 'POST',
		url: site_url + 'libs/funciones/func_obtener_acciones.php',
		data: ({
			  	x1: x1,
				x2: x2,
				x3: x3
			  }),
		error: function(){
				grl_mensaje('Error al traer acciones de usuario,', ' favor intentar de nuevo', 'danger');
			   },
		success: function(data) {
			/*Verifica si el arreglo trae mas de un registro,
			  en caso contrario lo llenara automaticamente*/
			if(data != 0){
				$('#div_acciones').append( data );
			} else {
				//grl_mensaje('No hay acciones,', ' favor consultar su flujo de autorizaciones', 'danger');
			}
		}
	});
}

/*
 * Función que obtiene el listado de tipos de noticias que pueden verse.
 */
function grl_constructor_tipo_noticias(){
	$.ajax({
		type: 'POST',
		url: site_url + 'mod_noticias/funciones/not_listado_tipo_noticias.php',
		dataType: 'json',
		success: function(data) {
			$('#cod_tipo_noticia').empty();
			/*Verifica si el arreglo trae mas de un registro,
			  en caso contrario lo llenara automaticamente*/
			if(data.length > 1){
				$('#cod_tipo_noticia').append( new Option('Select','-b') );
				$.each( data, function( i, item ) {
					$('#cod_tipo_noticia').append( new Option(item.tipo_noticia,item.cod_tipo_noticia) );
				});
			} else {
				$('#cod_tipo_noticia').append( new Option(data[0].tipo_noticia,data[0].cod_tipo_noticia) );
			}
			$('.selectpicker').selectpicker('refresh');
		}
	});
}

/*
* Recorre el nombre del input indicado y almacena los valores en celdas dentro de un arreglo
*/
function grl_arreglo_input(nombre_input)
{
	var elementos = new Array();
	$('input[name="'+nombre_input+'[]"]').each(function(index, value ){
		elementos[index] = $(this).val();
		elementos[index] = elementos[index].replace(/\u00e9/g,"&eacute;");
		elementos[index] = elementos[index].replace(/\u00f3/g,"&oacute;");
		elementos[index] = elementos[index].replace(/\u00e1/g,"&aacute;");
		elementos[index] = elementos[index].replace(/\u00ed/g,"&iacute;");
		elementos[index] = elementos[index].replace(/\u00e4/g,"&auml;");
		elementos[index] = elementos[index].replace(/\u00fc/g,"&uuml;");
		elementos[index] = elementos[index].replace(/\u00f6/g,"&ouml;");
		elementos[index] = elementos[index].replace(/\u00e3/g,"&atilde;");
		elementos[index] = elementos[index].replace(/\u00f8/g,"&oslash;");
		elementos[index] = elementos[index].replace(/\u00d8/g,"&Oslash;");
		elementos[index] = elementos[index].replace(/\u00f5/g,"&otilde;");
		elementos[index] = elementos[index].replace(/\u00f1/g,"&ntilde;");
		elementos[index] = elementos[index].replace(/\u00d1/g,"&Ntilde;");
		elementos[index] = elementos[index].replace(/\u00f4/g,"&ocirc;");
		elementos[index] = elementos[index].replace(/\u00e6/g,"&aelig;");
		elementos[index] = elementos[index].replace(/\u00c5/g,"&Aring;");
		elementos[index] = elementos[index].replace(/\u00e8/g,"&egrave;");
		elementos[index] = elementos[index].replace(/\u00ec/g,"&igrave;");
		elementos[index] = elementos[index].replace(/\u00c1/g,"&Aacute;");
		elementos[index] = elementos[index].replace(/\u00c9/g,"&Eacute;");
		elementos[index] = elementos[index].replace(/\u00cd/g,"&Iacute;");
		elementos[index] = elementos[index].replace(/\u00d3/g,"&Oacute;");
		elementos[index] = elementos[index].replace(/\u00da/g,"&Uacute;");
		elementos[index] = elementos[index].replace(/\u00fa/g,"&uacute;");
		elementos[index] = elementos[index].replace(/\u00fc/g,"&uuml;");
		elementos[index] = elementos[index].replace(/\u00dc/g,"&Uuml;");
		elementos[index] = elementos[index].replace(/\u00d1/g,"&Ntilde");
		elementos[index] = elementos[index].replace(/\u00bf/g,"&iquest;");
		elementos[index] = elementos[index].replace(/,/g, "|");
		i=i+1;
	});
	return elementos;
}

/*
* Recorre el nombre de un textarea indicado y almacena los valores en celdas dentro de un arreglo
*/
function grl_arreglo_textarea(id_textarea)
{
	var elementos = new Array();
	$('textarea#'+id_textarea).each(function(index, value ){
		elementos[index] = $(this).val();
		elementos[index] = elementos[index].replace(/\u00e9/g,"&eacute;");
		elementos[index] = elementos[index].replace(/\u00f3/g,"&oacute;");
		elementos[index] = elementos[index].replace(/\u00e1/g,"&aacute;");
		elementos[index] = elementos[index].replace(/\u00ed/g,"&iacute;");
		elementos[index] = elementos[index].replace(/\u00e4/g,"&auml;");
		elementos[index] = elementos[index].replace(/\u00fc/g,"&uuml;");
		elementos[index] = elementos[index].replace(/\u00f6/g,"&ouml;");
		elementos[index] = elementos[index].replace(/\u00e3/g,"&atilde;");
		elementos[index] = elementos[index].replace(/\u00f8/g,"&oslash;");
		elementos[index] = elementos[index].replace(/\u00d8/g,"&Oslash;");
		elementos[index] = elementos[index].replace(/\u00f5/g,"&otilde;");
		elementos[index] = elementos[index].replace(/\u00f1/g,"&ntilde;");
		elementos[index] = elementos[index].replace(/\u00d1/g,"&Ntilde;");
		elementos[index] = elementos[index].replace(/\u00f4/g,"&ocirc;");
		elementos[index] = elementos[index].replace(/\u00e6/g,"&aelig;");
		elementos[index] = elementos[index].replace(/\u00c5/g,"&Aring;");
		elementos[index] = elementos[index].replace(/\u00e8/g,"&egrave;");
		elementos[index] = elementos[index].replace(/\u00ec/g,"&igrave;");
		elementos[index] = elementos[index].replace(/\u00c1/g,"&Aacute;");
		elementos[index] = elementos[index].replace(/\u00c9/g,"&Eacute;");
		elementos[index] = elementos[index].replace(/\u00cd/g,"&Iacute;");
		elementos[index] = elementos[index].replace(/\u00d3/g,"&Oacute;");
		elementos[index] = elementos[index].replace(/\u00da/g,"&Uacute;");
		elementos[index] = elementos[index].replace(/\u00fa/g,"&uacute;");
		elementos[index] = elementos[index].replace(/\u00fc/g,"&uuml;");
		elementos[index] = elementos[index].replace(/\u00dc/g,"&Uuml;");
		elementos[index] = elementos[index].replace(/\u00d1/g,"&Ntilde");
		elementos[index] = elementos[index].replace(/\u00bf/g,"&iquest;");
		elementos[index] = elementos[index].replace(/,/g, "|");
	});
	return elementos;
}

/*
* Recorre el nombre de un select indicado y almacena los valores en celdas dentro de un arreglo
*/
function grl_arreglo_select(nombre_select)
{
	var elementos = new Array();
	$('select[name="'+nombre_select+'"]').each(function(index, value ){
		elementos[index] = $(this).val();
		elementos[index] = elementos[index].replace(/\u00e9/g,"&eacute;");
		elementos[index] = elementos[index].replace(/\u00f3/g,"&oacute;");
		elementos[index] = elementos[index].replace(/\u00e1/g,"&aacute;");
		elementos[index] = elementos[index].replace(/\u00ed/g,"&iacute;");
		elementos[index] = elementos[index].replace(/\u00e4/g,"&auml;");
		elementos[index] = elementos[index].replace(/\u00fc/g,"&uuml;");
		elementos[index] = elementos[index].replace(/\u00f6/g,"&ouml;");
		elementos[index] = elementos[index].replace(/\u00e3/g,"&atilde;");
		elementos[index] = elementos[index].replace(/\u00f8/g,"&oslash;");
		elementos[index] = elementos[index].replace(/\u00d8/g,"&Oslash;");
		elementos[index] = elementos[index].replace(/\u00f5/g,"&otilde;");
		elementos[index] = elementos[index].replace(/\u00f1/g,"&ntilde;");
		elementos[index] = elementos[index].replace(/\u00d1/g,"&Ntilde;");
		elementos[index] = elementos[index].replace(/\u00f4/g,"&ocirc;");
		elementos[index] = elementos[index].replace(/\u00e6/g,"&aelig;");
		elementos[index] = elementos[index].replace(/\u00c5/g,"&Aring;");
		elementos[index] = elementos[index].replace(/\u00e8/g,"&egrave;");
		elementos[index] = elementos[index].replace(/\u00ec/g,"&igrave;");
		elementos[index] = elementos[index].replace(/\u00c1/g,"&Aacute;");
		elementos[index] = elementos[index].replace(/\u00c9/g,"&Eacute;");
		elementos[index] = elementos[index].replace(/\u00cd/g,"&Iacute;");
		elementos[index] = elementos[index].replace(/\u00d3/g,"&Oacute;");
		elementos[index] = elementos[index].replace(/\u00da/g,"&Uacute;");
		elementos[index] = elementos[index].replace(/\u00fa/g,"&uacute;");
		elementos[index] = elementos[index].replace(/\u00fc/g,"&uuml;");
		elementos[index] = elementos[index].replace(/\u00dc/g,"&Uuml;");
		elementos[index] = elementos[index].replace(/\u00d1/g,"&Ntilde");
		elementos[index] = elementos[index].replace(/\u00bf/g,"&iquest;");
		elementos[index] = elementos[index].replace(/,/g, "|");
	});
	return elementos;
}

function utf8_encode(argString) {
  //  discuss at: http://phpjs.org/functions/\utf8_encode/
  // original by: Webtoolkit.info (http://www.webtoolkit.info/)
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: sowberry
  // improved by: Jack
  // improved by: Yves Sucaet
  // improved by: kirilloid
  // bugfixed by: Onno Marsman
  // bugfixed by: Onno Marsman
  // bugfixed by: Ulrich
  // bugfixed by: Rafal Kukawski
  // bugfixed by: kirilloid
  //   example 1: utf8_encode('Kevin van Zonneveld');
  //   returns 1: 'Kevin van Zonneveld'

  if (argString === null || typeof argString === 'undefined') {
    return '';
  }

  var string = (argString + ''); // .replace(/\r\n/g, "\n").replace(/\r/g, "\n");
  var utftext = '',
    start, end, stringl = 0;

  start = end = 0;
  stringl = string.length;
  for (var n = 0; n < stringl; n++) {
    var c1 = string.charCodeAt(n);
    var enc = null;

    if (c1 < 128) {
      end++;
    } else if (c1 > 127 && c1 < 2048) {
      enc = String.fromCharCode(
        (c1 >> 6) | 192, (c1 & 63) | 128
      );
    } else if ((c1 & 0xF800) != 0xD800) {
      enc = String.fromCharCode(
        (c1 >> 12) | 224, ((c1 >> 6) & 63) | 128, (c1 & 63) | 128
      );
    } else { // surrogate pairs
      if ((c1 & 0xFC00) != 0xD800) {
        throw new RangeError('Unmatched trail surrogate at ' + n);
      }
      var c2 = string.charCodeAt(++n);
      if ((c2 & 0xFC00) != 0xDC00) {
        throw new RangeError('Unmatched lead surrogate at ' + (n - 1));
      }
      c1 = ((c1 & 0x3FF) << 10) + (c2 & 0x3FF) + 0x10000;
      enc = String.fromCharCode(
        (c1 >> 18) | 240, ((c1 >> 12) & 63) | 128, ((c1 >> 6) & 63) | 128, (c1 & 63) | 128
      );
    }
    if (enc !== null) {
      if (end > start) {
        utftext += string.slice(start, end);
      }
      utftext += enc;
      start = end = n + 1;
    }
  }

  if (end > start) {
    utftext += string.slice(start, stringl);
  }

  return utftext;
}

function utf8_decode(str_data) {
  //  discuss at: http://phpjs.org/functions/\utf8_decode/
  // original by: Webtoolkit.info (http://www.webtoolkit.info/)
  //    input by: Aman Gupta
  //    input by: Brett Zamir (http://brett-zamir.me)
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Norman "zEh" Fuchs
  // bugfixed by: hitwork
  // bugfixed by: Onno Marsman
  // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // bugfixed by: kirilloid
  //   example 1: utf8_decode('Kevin van Zonneveld');
  //   returns 1: 'Kevin van Zonneveld'

  var tmp_arr = [],
    i = 0,
    ac = 0,
    c1 = 0,
    c2 = 0,
    c3 = 0,
    c4 = 0;

  str_data += '';

  while (i < str_data.length) {
    c1 = str_data.charCodeAt(i);
    if (c1 <= 191) {
      tmp_arr[ac++] = String.fromCharCode(c1);
      i++;
    } else if (c1 <= 223) {
      c2 = str_data.charCodeAt(i + 1);
      tmp_arr[ac++] = String.fromCharCode(((c1 & 31) << 6) | (c2 & 63));
      i += 2;
    } else if (c1 <= 239) {
      // http://en.wikipedia.org/wiki/\uTF-8#Codepage_layout
      c2 = str_data.charCodeAt(i + 1);
      c3 = str_data.charCodeAt(i + 2);
      tmp_arr[ac++] = String.fromCharCode(((c1 & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
      i += 3;
    } else {
      c2 = str_data.charCodeAt(i + 1);
      c3 = str_data.charCodeAt(i + 2);
      c4 = str_data.charCodeAt(i + 3);
      c1 = ((c1 & 7) << 18) | ((c2 & 63) << 12) | ((c3 & 63) << 6) | (c4 & 63);
      c1 -= 0x10000;
      tmp_arr[ac++] = String.fromCharCode(0xD800 | ((c1 >> 10) & 0x3FF));
      tmp_arr[ac++] = String.fromCharCode(0xDC00 | (c1 & 0x3FF));
      i += 4;
    }
  }

  return tmp_arr.join('');
}

function html_entity_decode(string, quote_style) {
  //  discuss at: http://phpjs.org/functions/html_entity_decode/
  // original by: john (http://www.jd-tech.net)
  //    input by: ger
  //    input by: Ratheous
  //    input by: Nick Kolosov (http://sammy.ru)
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: marc andreu
  //  revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //  revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // bugfixed by: Onno Marsman
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  // bugfixed by: Fox
  //  depends on: get_html_translation_table
  //   example 1: html_entity_decode('Kevin &amp; van Zonneveld');
  //   returns 1: 'Kevin & van Zonneveld'
  //   example 2: html_entity_decode('&amp;lt;');
  //   returns 2: '&lt;'

  var hash_map = {},
    symbol = '',
    tmp_str = '',
    entity = '';
  tmp_str = string.toString();

  if (false === (hash_map = this.get_html_translation_table('HTML_ENTITIES', quote_style))) {
    return false;
  }

  // fix &amp; problem
  // http://phpjs.org/functions/get_html_translation_table:416#comment_97660
  delete(hash_map['&']);
  hash_map['&'] = '&amp;';

  for (symbol in hash_map) {
    entity = hash_map[symbol];
    tmp_str = tmp_str.split(entity)
      .join(symbol);
  }
  tmp_str = tmp_str.split('&#039;')
    .join("'");

  return tmp_str;
}
function get_html_translation_table(table, quote_style) {
  //  discuss at: http://phpjs.org/functions/get_html_translation_table/
  // original by: Philip Peterson
  //  revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // bugfixed by: noname
  // bugfixed by: Alex
  // bugfixed by: Marco
  // bugfixed by: madipta
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  // bugfixed by: T.Wild
  // improved by: KELAN
  // improved by: Brett Zamir (http://brett-zamir.me)
  //    input by: Frank Forte
  //    input by: Ratheous
  //        note: It has been decided that we're not going to add global
  //        note: dependencies to php.js, meaning the constants are not
  //        note: real constants, but strings instead. Integers are also supported if someone
  //        note: chooses to create the constants themselves.
  //   example 1: get_html_translation_table('HTML_SPECIALCHARS');
  //   returns 1: {'"': '&quot;', '&': '&amp;', '<': '&lt;', '>': '&gt;'}

  var entities = {},
    hash_map = {},
    decimal;
  var constMappingTable = {},
    constMappingQuoteStyle = {};
  var useTable = {},
    useQuoteStyle = {};

  // Translate arguments
  constMappingTable[0] = 'HTML_SPECIALCHARS';
  constMappingTable[1] = 'HTML_ENTITIES';
  constMappingQuoteStyle[0] = 'ENT_NOQUOTES';
  constMappingQuoteStyle[2] = 'ENT_COMPAT';
  constMappingQuoteStyle[3] = 'ENT_QUOTES';

  useTable = !isNaN(table) ? constMappingTable[table] : table ? table.toUpperCase() : 'HTML_SPECIALCHARS';
  useQuoteStyle = !isNaN(quote_style) ? constMappingQuoteStyle[quote_style] : quote_style ? quote_style.toUpperCase() :
    'ENT_COMPAT';

  if (useTable !== 'HTML_SPECIALCHARS' && useTable !== 'HTML_ENTITIES') {
    throw new Error('Table: ' + useTable + ' not supported');
    // return false;
  }

  entities['38'] = '&amp;';
  if (useTable === 'HTML_ENTITIES') {
    entities['160'] = '&nbsp;';
    entities['161'] = '&iexcl;';
    entities['162'] = '&cent;';
    entities['163'] = '&pound;';
    entities['164'] = '&curren;';
    entities['165'] = '&yen;';
    entities['166'] = '&brvbar;';
    entities['167'] = '&sect;';
    entities['168'] = '&uml;';
    entities['169'] = '&copy;';
    entities['170'] = '&ordf;';
    entities['171'] = '&laquo;';
    entities['172'] = '&not;';
    entities['173'] = '&shy;';
    entities['174'] = '&reg;';
    entities['175'] = '&macr;';
    entities['176'] = '&deg;';
    entities['177'] = '&plusmn;';
    entities['178'] = '&sup2;';
    entities['179'] = '&sup3;';
    entities['180'] = '&acute;';
    entities['181'] = '&micro;';
    entities['182'] = '&para;';
    entities['183'] = '&middot;';
    entities['184'] = '&cedil;';
    entities['185'] = '&sup1;';
    entities['186'] = '&ordm;';
    entities['187'] = '&raquo;';
    entities['188'] = '&frac14;';
    entities['189'] = '&frac12;';
    entities['190'] = '&frac34;';
    entities['191'] = '&iquest;';
    entities['192'] = '&Agrave;';
    entities['193'] = '&Aacute;';
    entities['194'] = '&Acirc;';
    entities['195'] = '&Atilde;';
    entities['196'] = '&Auml;';
    entities['197'] = '&Aring;';
    entities['198'] = '&AElig;';
    entities['199'] = '&Ccedil;';
    entities['200'] = '&Egrave;';
    entities['201'] = '&Eacute;';
    entities['202'] = '&Ecirc;';
    entities['203'] = '&Euml;';
    entities['204'] = '&Igrave;';
    entities['205'] = '&Iacute;';
    entities['206'] = '&Icirc;';
    entities['207'] = '&Iuml;';
    entities['208'] = '&ETH;';
    entities['209'] = '&Ntilde;';
    entities['210'] = '&Ograve;';
    entities['211'] = '&Oacute;';
    entities['212'] = '&Ocirc;';
    entities['213'] = '&Otilde;';
    entities['214'] = '&Ouml;';
    entities['215'] = '&times;';
    entities['216'] = '&Oslash;';
    entities['217'] = '&Ugrave;';
    entities['218'] = '&Uacute;';
    entities['219'] = '&Ucirc;';
    entities['220'] = '&Uuml;';
    entities['221'] = '&Yacute;';
    entities['222'] = '&THORN;';
    entities['223'] = '&szlig;';
    entities['224'] = '&agrave;';
    entities['225'] = '&aacute;';
    entities['226'] = '&acirc;';
    entities['227'] = '&atilde;';
    entities['228'] = '&auml;';
    entities['229'] = '&aring;';
    entities['230'] = '&aelig;';
    entities['231'] = '&ccedil;';
    entities['232'] = '&egrave;';
    entities['233'] = '&eacute;';
    entities['234'] = '&ecirc;';
    entities['235'] = '&euml;';
    entities['236'] = '&igrave;';
    entities['237'] = '&iacute;';
    entities['238'] = '&icirc;';
    entities['239'] = '&iuml;';
    entities['240'] = '&eth;';
    entities['241'] = '&ntilde;';
    entities['242'] = '&ograve;';
    entities['243'] = '&oacute;';
    entities['244'] = '&ocirc;';
    entities['245'] = '&otilde;';
    entities['246'] = '&ouml;';
    entities['247'] = '&divide;';
    entities['248'] = '&oslash;';
    entities['249'] = '&ugrave;';
    entities['250'] = '&uacute;';
    entities['251'] = '&ucirc;';
    entities['252'] = '&uuml;';
    entities['253'] = '&yacute;';
    entities['254'] = '&thorn;';
    entities['255'] = '&yuml;';
  }

  if (useQuoteStyle !== 'ENT_NOQUOTES') {
    entities['34'] = '&quot;';
  }
  if (useQuoteStyle === 'ENT_QUOTES') {
    entities['39'] = '&#39;';
  }
  entities['60'] = '&lt;';
  entities['62'] = '&gt;';

  // ascii decimals to real symbols
  for (decimal in entities) {
    if (entities.hasOwnProperty(decimal)) {
      hash_map[String.fromCharCode(decimal)] = entities[decimal];
    }
  }

  return hash_map;
}
function htmlentities(string, quote_style, charset, double_encode) {
  //  discuss at: http://phpjs.org/functions/htmlentities/
  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //  revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //  revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: nobbler
  // improved by: Jack
  // improved by: Rafał Kukawski (http://blog.kukawski.pl)
  // improved by: Dj (http://phpjs.org/functions/htmlentities:425#comment_134018)
  // bugfixed by: Onno Marsman
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  //    input by: Ratheous
  //  depends on: get_html_translation_table
  //   example 1: htmlentities('Kevin & van Zonneveld');
  //   returns 1: 'Kevin &amp; van Zonneveld'
  //   example 2: htmlentities("foo'bar","ENT_QUOTES");
  //   returns 2: 'foo&#039;bar'

  var hash_map = this.get_html_translation_table('HTML_ENTITIES', quote_style),
    symbol = '';
  string = string == null ? '' : string + '';

  if (!hash_map) {
    return false;
  }

  if (quote_style && quote_style === 'ENT_QUOTES') {
    hash_map["'"] = '&#039;';
  }

  if ( !! double_encode || double_encode == null) {
    for (symbol in hash_map) {
      if (hash_map.hasOwnProperty(symbol)) {
        string = string.split(symbol)
          .join(hash_map[symbol]);
      }
    }
  } else {
    string = string.replace(/([\s\S]*?)(&(?:#\d+|#x[\da-f]+|[a-zA-Z][\da-z]*);|$)/g, function(ignore, text, entity) {
      for (symbol in hash_map) {
        if (hash_map.hasOwnProperty(symbol)) {
          text = text.split(symbol)
            .join(hash_map[symbol]);
        }
      }

      return text + entity;
    });
  }
  return string;
}

/*----------------------------------------------------------------------------------
					Función para crear un overlay de carga
----------------------------------------------------------------------------------*/
function grl_overlay_loading(texto){
	var modal = '<div class="modal fade" id="modal_loading" tabindex="-1" role="dialog" aria-hidden="true">' +
					'<div class="modal-dialog only-overlay">' +
						'<div class="modal-content only-overlay">' +
							'<div class="modal-body">' +
								'<div class="row content-loader">' +
									'<div class="col-xs-12 text-center nopadding">' +
										'<div class="loader-circle-md"></div>' +
										'<div class="loader-text" id="loader-text">' + texto + '</div>' +
									'</div>' +
								'</div>' +
							'</div>' +
						'</div>' +
					'</div>' +
				'</div>';
	$("#overlay_loading").empty();
	$("#overlay_loading").append(modal);

	$('#modal_loading').modal({
		keyboard: false,
		backdrop: 'static',
		show: false
	});
	$("#modal_loading").modal("show");
}

/*----------------------------------------------------------------------------------
					Función para crear un modal como una alerta
----------------------------------------------------------------------------------*/
function msg_box(title, msj){
	var modal = '<div class="modal fade" id="confirm_box" tabindex="-1" role="dialog" aria-labelledby="modal_confirm_box" aria-hidden="true">' +
					'<div class="modal-dialog">' +
						'<div class="modal-content modal-warning">' +
							'<div class="modal-header">' +
								'<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
								'<h4 class="modal-title" id="modal_confirm_box">' + title + '</h4>' +
							'</div>' +
							'<div class="modal-body">' +
								'<div class="row">' +
									'<div class="col-xs-12 msg-box-container">' +
										'<i class="fa fa-exclamation-triangle fa-2x"></i>' +
										'<span>' + msj + '</span>' +
									'</div>' +
								'</div>' +
							'</div>' +
							'<div class="modal-footer">' +
								'<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancelar</button>' +
								'<button type="button" class="btn btn-sm btn-primary smooth-transition" id="btn_ok" >Aceptar</button>' +
							'</div>' +
						'</div>' +
					'</div>' +
				'</div>';
	$("#message_box").empty();
	$("#message_box").append(modal);
	$("#confirm_box").modal("show");
}

/**
 * Función para verificar si los campos requeridos están vacios o llenos
 */
function grl_campos_requeridos_incompletos(){
	var respuesta = false;
	$(".requerido").each(function(index) {
		if( $(this).val() == null || $(this).val() == '' || $(this).val() =='-b' ){
			if( $(this).get(0).nodeName != 'DIV' && !($(this).hasClass('oculto')) ){
				$(this).addClass('campo-vacio');
				respuesta = true;
			}
		}
		else{
			if( $(this).hasClass('campo-vacio') ){
				//wrong_format = true;
				respuesta = true;
			}
		}
	});
	return respuesta;
}
/*----------------------------------------------------------------------------------
				Función para reiniciar los campos requeridos
----------------------------------------------------------------------------------*/
function grl_reiniciar_campos_requeridos(){
	$(".requerido").removeClass('campo-vacio input-has-error input-is-ok');
	$(".input-group-addon").removeClass('input-has-error input-is-ok');
	$('.requerido option').prop('selected', false);
    $('.requerido').selectpicker('setStyle', 'btn-info');
	$('.requerido').selectpicker('setStyle', 'btn-danger', 'remove');
	$('input.requerido').val('');
	if( $('select.requerido').attr('multiple') == false ){
		$('select.requerido').val('-b');
	}
	$('.requerido').selectpicker('refresh');
}

function str_replace(search, replace, subject, count) {
  //  discuss at: http://phpjs.org/functions/str_replace/
  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Gabriel Paderni
  // improved by: Philip Peterson
  // improved by: Simon Willison (http://simonwillison.net)
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Onno Marsman
  // improved by: Brett Zamir (http://brett-zamir.me)
  //  revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  // bugfixed by: Anton Ongson
  // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // bugfixed by: Oleg Eremeev
  //    input by: Onno Marsman
  //    input by: Brett Zamir (http://brett-zamir.me)
  //    input by: Oleg Eremeev
  //        note: The count parameter must be passed as a string in order
  //        note: to find a global variable in which the result will be given
  //   example 1: str_replace(' ', '.', 'Kevin van Zonneveld');
  //   returns 1: 'Kevin.van.Zonneveld'
  //   example 2: str_replace(['{name}', 'l'], ['hello', 'm'], '{name}, lars');
  //   returns 2: 'hemmo, mars'

  var i = 0,
    j = 0,
    temp = '',
    repl = '',
    sl = 0,
    fl = 0,
    f = [].concat(search),
    r = [].concat(replace),
    s = subject,
    ra = Object.prototype.toString.call(r) === '[object Array]',
    sa = Object.prototype.toString.call(s) === '[object Array]';
  s = [].concat(s);
  if (count) {
    this.window[count] = 0;
  }

  for (i = 0, sl = s.length; i < sl; i++) {
    if (s[i] === '') {
      continue;
    }
    for (j = 0, fl = f.length; j < fl; j++) {
      temp = s[i] + '';
      repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
      s[i] = (temp)
        .split(f[j])
        .join(repl);
      if (count && s[i] !== temp) {
        this.window[count] += (temp.length - s[i].length) / f[j].length;
      }
    }
  }
  console.log('return: '+ sa ? s : s[0]);
  return sa ? s : s[0];
}

/* */
function toggleModuleNav(){
	var navIsVisible = ( !$('.cd-dropdown').hasClass('dropdown-is-active') ) ? true : false;
	$('.cd-dropdown').toggleClass('dropdown-is-active', navIsVisible);
	$('.cd-dropdown-trigger').toggleClass('dropdown-is-active', navIsVisible);
	if( !navIsVisible ) {
		$('.cd-dropdown').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend',function(){
			$('.has-children ul').addClass('is-hidden');
			$('.move-out').removeClass('move-out');
			$('.is-active').removeClass('is-active');
			$('.cd-dropdown-wrapper').removeClass('dropdown-is-active');
			console.log('tmn_Removed...');
			$('body').removeClass('content-overflow-hidden-mobile');
		});
	}
	else{
		$('.cd-dropdown-wrapper').addClass('dropdown-is-active').focus();
		console.log('tmn_Added...');
		$('body').addClass('content-overflow-hidden-mobile');
	}
}

function closeModuleNav(){
	//var navIsVisible = ( !$('.cd-dropdown').hasClass('dropdown-is-active') ) ? true : false;
	$('.cd-dropdown').removeClass('dropdown-is-active');
	$('.cd-dropdown-trigger').removeClass('dropdown-is-active');

	$('.cd-dropdown.desktop').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend',function(){
		$('.has-children ul').addClass('is-hidden');
		$('.move-out').removeClass('move-out');
		$('.is-active').removeClass('is-active');
		$('.cd-dropdown-wrapper').removeClass('dropdown-is-active');
		console.log('Cmn_Removed...');
		$('body').removeClass('content-overflow-hidden-mobile');
	});
}


	/*
   * Validación de los inputs en la forma
	 */
  function grl_validar_inputs(objetos){
    var flag = 0;
		//Descompone el string del parametro y crea un arreglo
    arr_objetos = objetos.split("|");
		//Recorre el arreglo validadndo cada objeto en la forma
    $.each( arr_objetos, function( key, value ) {
  		if($('#' + value).val() == '' || $('#' + value).val() == null){
  			flag = 1;
  			$('#' + value).addClass('input-has-error');
  		} else {
  			$('#' + value).removeClass('input-has-error');
  		}
    });
		//Regresa el flag validando si esta correcta o no la forma
    return flag;
  };

	/*
   * Validación de los listboxs en la forma
	 */
  function grl_validar_listboxs(objetos){
    var flag = 0;
		//Descompone el string del parametro y crea un arreglo
    arr_objetos = objetos.split("|");
		//Recorre el arreglo validadndo cada objeto en la forma
    $.each( arr_objetos, function( key, value ) {
      if($('#' + value + ' option:selected').val() == '' || $('#' + value + ' option:selected').val() == '-b' || $('#' + value + ' option:selected').val() == null) {
        flag = 1;
        $('#' + value).selectpicker('setStyle', 'btn-info', 'remove');
        $('#' + value).selectpicker('setStyle', 'btn-danger');
      } else {
        $('#' + value).selectpicker('setStyle', 'btn-danger campo-vacio', 'remove');
        $('#' + value).selectpicker('setStyle', 'btn-info');
        $('#' + value).removeClass('campo-vacio');
      }
    });
    $('.selectpicker').selectpicker('refresh');
		//Regresa el flag validando si esta correcta o no la forma
    return flag;
  };
	/**
	 * Inicializa el nuevo loader
	 */
	(function(){
		
		loader = {
			visible: false
		};
	
		loader.show = function(mensaje){
			if(mensaje == undefined)
				mensaje = "";
			if(!loader.visible){
				loader.visible = true;
				$('body').append(`
					<div id="newloadercontainer" class="notransition" style="display: none;"></div>
				`);
				$('#newloadercontainer').append(`
					<div class="newloader backoverlay">
						<div class="newloader body">
							<div class="newloader content">
								<div class="loader"></div>
								<h2 class="loadertext">`+mensaje+`</h2>
							</div>
						</div>
					</div>
				`);
				$('#newloadercontainer').fadeIn(200);
			}else{
				console.log('Loader: Esta invocando un loader mientras ya hay uno visible.');
			}
		}
	
		loader.hide = function(){
			$('#newloadercontainer').fadeOut(200, function(){
				$('#newloadercontainer').remove();
				loader.visible = false;
			});
		}
	
	})();

function init_button_bar(){
	 $("#btn_open_actions").on('click',function (){
	    var $menu_actions = $("#panel_footer_actions");
	    var $container_back_button = $("#container_back_button");
	    var $div_acciones = $("#div_acciones");
	    $menu_actions.removeClass('actions-container-is-closed').addClass('actions-container-is-opened');
	    $container_back_button.removeClass('col-xs-9').addClass('col-xs-12');
	    $div_acciones.removeClass('col-xs-3').addClass('col-xs-12');
	});

	$("#btn_close_actions").on('click',function (){
	    var $menu_actions = $("#panel_footer_actions");
	    var $container_back_button = $("#container_back_button");
	    var $div_acciones = $("#div_acciones");
	    $menu_actions.removeClass('actions-container-is-opened').addClass('actions-container-is-closed');
	    $container_back_button.removeClass('col-xs-12').addClass('col-xs-9');
	    $div_acciones.removeClass('col-xs-12');//.addClass('col-xs-3');
	    if (!$div_acciones.hasClass('col-xs-10'))
	    {
	    	$div_acciones.addClass('col-xs-3');
	    }
	});
}
function constructor_progress_bar($parent){
	$parent.prepend('<div class="progress_bar progress-bar-container">'+
				        '<div class="progress checklist">'+
				            '<div class="progress-bar progress-bar-succes progress-bar-striped active completed" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">'+
				            '</div>'+
				        '</div>'+
				    '</div>');
}