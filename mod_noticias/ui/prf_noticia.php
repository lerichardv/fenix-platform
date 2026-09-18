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
<title>Ingreso de Noticias</title>
</head>

	
<script type="text/javascript">
  $(document).ready(function(){
	  
	  
function seleccionado(){ 

var archivos = document.getElementById("archivos");//Damos el valor del input tipo file
var archivo = archivos.files; //Obtenemos el valor del input (los arcchivos) en modo de arreglo

/* Creamos el objeto que hara la petición AJAX al servidor, debemos de validar 
si existe el objeto “ XMLHttpRequest” ya que en internet explorer viejito no esta,
y si no esta usamos “ActiveXObject” */ 

 if(window.XMLHttpRequest) { 
 var Req = new XMLHttpRequest(); 
 }else if("ActiveXObject" in window) { 
 var Req = new ActiveXObject("Microsoft.XMLHTTP"); 
 }

//El objeto FormData nos permite crear un formulario pasandole clave/valor para poder enviarlo, 
//este tipo de objeto ya tiene la propiedad multipart/form-data para poder subir archivos
var data = new FormData();

//Como no sabemos cuantos archivos subira el usuario, iteramos la variable y al
//objeto de FormData con el metodo "append" le pasamos calve/valor, usamos el indice "i" para
//que no se repita, si no lo usamos solo tendra el valor de la ultima iteración
for(i=0; i<archivo.length; i++){
   data.append('archivo'+i,archivo[i]);
}

//Pasándole la url a la que haremos la petición
Req.open("POST", "subir.php", true);

/* Le damos un evento al request, esto quiere decir que cuando termine de hacer la petición,
se ejecutara este fragmento de código */ 

Req.onload = function(Event) {
//Validamos que el status http sea ok 
if (Req.status == 200) { 
  //Recibimos la respuesta de php
  var msg = Req.responseText;
  $("#cargados").append(msg);
} else { 
  console.log(Req.status); //Vemos que paso. 
} 
};

 //Enviamos la petición 
 Req.send(data); 
}

});//fin de subir_archivo ()

	
</script>

<body>
	<form enctype="multipart/form-data"  role="form" method="post" id="formulario" name="formulario">
    <div class="panel panel-default">
  	<div class="panel-body">
        <div class="page-header">
        	<h1>
            	Noticias
                <small>Información que visualizara el usuario en el menú principal</small>
            </h1>
        </div> <!-- fin page-header -->
        <div  class="center-block" >
        <div class="well col-md-8 col-md-offset-2" style="background-color:transparent" >
				<div class="row">
            
                    <div class="col-md-8">
                          <div class="form-group input-group-sm " id="div_titulo" >
                            <label for="titulo">Titulo</label>
                            <input type="text" class="form-control letras" id="titulo" name="titulo" placeholder="Titulo">
                          </div>
                    </div>
                </div> <!-- fin row -->    
                <div class="row">
                      <div class="col-md-12" >
                          <div class="form-group " id="div_contenido"  >
                            <label for="Contenido">Contenido</label>
                            <textarea class="form-control" rows="4" id="contenido" name="contenido" style="resize:none"/>
                          </div>
                      </div>
                </div> <!-- fin row --> 
                <div class="row">
                        <div class="col-md-8">
							<div class="form-group input-group-sm" id="div_imagen">
                                <label for="adjunto_imagen">Imagen</label><br/>
                                <input type="file"  class="btn btn-sm " data-filename-placement="inside" title="Seleccione..."id="imagen" name="imagen">
                             <!--   <button class="btn btn-sm btn-danger" id="borrar_adjunto" name="borrar_adjunto" onclick="recrear_file_input();">Eliminar</button> -->                  
	                        </div>   
                  		</div>   
                </div> <!-- fin de row-->
                
                
                   
                <div id="subir">
  <input id="archivos" type="file" name="archivos[]" multiple="multiple" onchange="seleccionado();" />
</div>
<div id="cargados">
  <!-- Aqui van los archivos cargados -->
</div>      


          
        </div> <!-- fin well -->
        </div> <!-- fin de contenedor del well -->
	</div> <!-- fin panel-body -->
    <div class="panel-footer" align="right">
        <input id="publicar" name="publicar" class="btn btn-sm btn-primary" type="submit" data-loading-text="Publicando..." value="Publicar"/>
        </div>
    </div>
    </form>
</body>

http://www.enricflorit.com/como-subir-archivos-al-servidor-con-php/#sthash.dH5JIuBT.dpbs
http://cafeconweb.net/subir-archivos-al-servidor-con-ajax-sin-plugin/