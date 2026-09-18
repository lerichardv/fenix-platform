
<body>
	<form enctype="multipart/form-data"  role="form" method="post" id="formulario" name="formulario">
    <div class="panel panel-default">
  	<div class="panel-body">
        <div class="page-header">
        	<h1>
            	Noticias
                <!--<small></small>-->
            </h1>
        </div> <!-- fin page-header -->
        <div  class="center-block" >
        <div class="well col-md-8 col-md-offset-2" style="background-color:transparent" >
				<div class="row">
            
                    <div class="col-md-8">
                          <div class="form-group input-group-sm " id="div_asunto" >
                            <label for="asunto">Titulo</label>
                            <input type="text" class="form-control letras" id="asunto" name="asunto" placeholder="Asunto">
                          </div>
                    </div>
                </div> <!-- fin row -->    
                <div class="row">
                      <div class="col-md-12" >
                          <div class="form-group " id="div_mensaje"  >
                            <label for="Contenido">Contenido</label>
                            <textarea class="form-control" rows="4" id="mensaje" name="mensaje" style="resize:none"/>
                          </div>
                      </div>
                </div> <!-- fin row --> 
                <div class="row">
                        <div class="col-md-8">
							<div class="form-group input-group-sm" id="div_adjunto">
                                <label for="adjunto_mensaje">Imagen</label><br/>
                                <input type="file"  class="btn btn-sm " data-filename-placement="inside" title="Seleccione..."id="adjunto" name="adjunto">
                             <!--   <button class="btn btn-sm btn-danger" id="borrar_adjunto" name="borrar_adjunto" onclick="recrear_file_input();">Eliminar</button> -->                  
	                        </div>   
                  		</div>   
                </div> <!-- fin de row-->                   
        </div> <!-- fin well -->
        </div> <!-- fin de contenedor del well -->
	</div> <!-- fin panel-body -->
    <div class="panel-footer" align="right">
        <input id="publicar" name="publicar" class="btn btn-sm btn-primary" type="submit" data-loading-text="Publicando..." value="Publicar"/>
        </div>
    </div>
    </form>
</body>