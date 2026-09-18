<?PHP
/*
 * Vista del listado de empleados.
 * @author      Linda Zelaya
 * @date        2016-07-22
*/
$cuerpo = '
	<div id="overlay_loading"></div>
	<div id="message_box"></div>
	<div id="div_login">
	  <div class="row" align="center">
				<div class="col-md-3">
						&nbsp;
				</div>
				<div class="col-md-6">
							<div class="media-body">
								<h4 class="media-heading translate" data-traducir_english="Forgot my Password" data-traducir_spanish="Olvidé mi Contraseña">Olvidé mi Contraseña</h4>
								<small class="translate" data-traducir_english="Enter your username or your email address to be able to assign a password for temporary access." data-traducir_spanish="Ingrese su usuario o su correo electronico para poderle asignar una contraseña de acceso temporal.">Ingrese su usuario o su correo electronico para poderle asignar una contraseña de acceso temporal.</small>
								&nbsp;
							</div>
				</div>
				<div class="col-md-3">
						&nbsp;
				</div>
	 </div>
	 <div class="row">
     <div class="col-md-4 col-md-offset-4"><!-- Form -->
     	<div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="nabvar-translate">
                    <label class="label-translate translate" style="color: black;" data-traducir_english="Traducir" data-traducir_spanish="Translate">Translate</label>
                    <div class="material-switch pull-right">
                        <input id="checkbox_translate" data-id="checkbox_translate" name="checkbox_translate" type="checkbox"/>
                        <label for="checkbox_translate" class=""></label>
                    </div>
                </div>
            </div>
        </div>
         <form class="form" role="form" method="post">
           <div id="div_user" class="form-group input-group-sm">
             <label for="user" style="color: black;" class="translate" data-traducir_english="Insert user or email" data-traducir_spanish="Ingrese su usuario o su correo electronico">Ingrese su usuario o su correo electronico</label>
             <input id="user" name="user" type="text"  class="form-control igualar_pass limpiar" placeholder="Ingresar usuario o correo electronico">
           </div>
					 <div class="text-right">
						 <div class="btn-group btn-group-justified">
							  <div class="btn-group">
									<button id="regresar" name="regresar" class="btn btn-sm btn-warning translate" data-traducir_english="Back" data-traducir_spanish="Regresar" type="button" data-loading-text="Regresando...">Regresar</button>
							  </div>
							  <div class="btn-group">
									<button id="enviar" name="enviar" class="btn btn-sm btn-primary translate" data-traducir_english="Send Email" data-traducir_spanish="Enviar Correo" type="button" data-loading-text="Enviando...">Enviar Correo</button>
							  </div>
							</div>
					  </div>
				 </form>
     </div>
     <div class="col-md-4">
         &nbsp;
     </div>
   </div>
 </div>

<script type="text/javascript">
  $(document).ready(function(){
		jQuery.ajaxSetup({async:false});
  	$("#div_user").removeClass("has-error").addClass("has-default");
		$("#regresar").click(function () {
				window.location.href = "../../index.php";
	 	}); //regresar Click

   $("#enviar").click(function () {
  		var btn = $("#enviar");
      user = $("#user").val();
  		var tipo = "";
  		btn.button("loading");
			grl_overlay_loading("Verificando información");
			jQuery.ajaxSetup({async:false});

			$.ajax({
				type: "POST",
		        url: "/mod_admin_usuarios/funciones/usu_validar_usuario.php",
				data: {x1: user},
				error: function(){
					grl_mensaje("No se pudo verificar el usuario para recuperar su contraseña. ", "Favor intentar mas tarde", "warning");
				},
				success: function (data) {
					var info = data.split("|");
          if (info[0] == 1){
						grl_mensaje("Se le ha enviado un correo electronico con su acceso temporal. ", "Favor verificar", "success");
						window.setTimeout(function(){
			 				window.location.href = "../../index.php";
	 					}, 5000);
            $("#modal_loading").modal("hide");
					}else{
						  grl_mensaje(info[1], "", "danger",15);
							$("#div_user").removeClass("has-success").removeClass("add-error");
              $("#modal_loading").modal("hide");
					}
				 }
				}); //Ajax
		    btn.button("reset");
  		}); //Guardar Click
  		$("#checkbox_translate").change(function(event) {
	        /* Act on the event */
	        event.preventDefault();
	        event.stopPropagation();
	        ($("#checkbox_translate").attr("checked") ? $("#checkbox_translate").removeAttr("checked") : $("#checkbox_translate").attr("checked","checked"))
	        grl_traducir_interfaz(($("#checkbox_translate").attr("checked") ? 1 : 0));
	    });
  }); // Ready Funtion
</script>
';

echo $cuerpo;

?>
