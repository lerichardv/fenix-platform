<?PHP
/*
 *Cambio de contraseña
 * @author      Linda Zelaya
 * @date        2016-07-22
*/
$cuerpo = '
<div class="row" align="center">
		 <div class="col-md-3">
				 &nbsp;
		 </div>
		 <div class="col-md-6">
					 <div class="media-body">
						 <h4 class="media-heading translate" data-traducir_english="Change Password" data-traducir_spanish="Cambio de Contraseña">Cambio de Contraseña</h4>
						 <small class="translate" data-traducir_english="A temporary password was assigned. Please enter a new password to enter the B&W Farming system" data-traducir_spanish="Se te asigno una contraseña temporal. Por favor ingrese una nueva contraseña para ingresar al sistema B&W Farming">Se te asigno una contraseña temporal. Por favor ingrese una nueva contraseña para ingresar al sistema B&W Farming</small>
						 &nbsp;
					 </div>
		 </div>
			 <div class="col-md-3">
					 &nbsp;
			 </div>
 </div>
<div class="row">
	 <div class="col-md-4"><!-- Form -->
			 &nbsp;
	 </div>
	 <div class="col-md-4"><!-- Form -->
	 	<div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="nabvar-translate">
                    <label class="label-translate translate" data-traducir_english="Traducir" data-traducir_spanish="Translate">Translate</label>
                    <div class="material-switch pull-right">
                        <input id="checkbox_translate" data-id="checkbox_translate" name="checkbox_translate" type="checkbox"/>
                        <label for="checkbox_translate" class=""></label>
                    </div>
                </div>
            </div>
        </div>
		 <form class="form" role="form" method="post">
					 <div id="div_nuevo_pass" class="form-group input-group-sm">
						 <label for="nuevo_pass" style="color: black;" class="translate" data-traducir_english="Password" data-traducir_spanish="Contraseña">Contraseña</label>
						 <input id="nuevo_pass" name="nuevo_pass" type="password" class="form-control igualar_pass limpiar placeholder_translate" data-placeholder_en="New Password" data-placeholder_es="Nueva contraseña" placeholder="Nueva contraseña">
					 </div>
				 <div id="div_confirm_pass" class="form-group input-group-sm">
					 <label for="confirm_pass" style="color: black;" class="translate" data-traducir_english="Confirm Password" data-traducir_spanish="Confirmar Contraseña">Confirmar Contraseña</label>
					 <input id="confirm_pass" name="confirm_pass" type="password" class="form-control igualar_pass limpiar placeholder_translate" data-placeholder_en="Confirm Password" data-placeholder_es="Confirmar contraseña"  placeholder="Confirmar contraseña">
				 </div>
				 <button id="guardar" name="guardar" class="btn btn-sm btn-primary btn-block" type="button" class="form-control translate" data-traducir_english="Save" data-traducir_spanish="Guardar" data-loading-text="Guardando...">Guardar</button>
		 </form>
	 </div>
	 <div class="col-md-4">
			 &nbsp;
	 </div>
</div>

<script>
$(document).ready(function(){
	$( "#nuevo_pass" ).change(function() {
	 if($("#nuevo_pass").val() != ""){
			$("#div_nuevo_pass").removeClass("has-error").addClass("has-success");
			error = false;
	 }else {
		 $("#div_nuevo_pass").removeClass("has-success").addClass("has-error");
		 error = true;
	 }
	});

	$( "#confirm_pass" ).change(function() {
	if($("#confirm_pass").val() != ""){
			$("#div_confirm_pass").removeClass("has-error").addClass("has-success");
			error = false;
	}else {
		$("#div_confirm_pass").removeClass("has-success").addClass("has-error");
		error = true;
	}
	});

	function grl_verificar_contrasenia (objeto,objeto){
	 //Valida que los campos sean iguales
	 pass = $("#nuevo_pass").val();
	 passc = $("#confirm_pass").val();

	 // Valida que la contraseña no sea menor a 8 caracteres.
	 if ( pass.length > 8) {
		 $("#length").removeClass("fa-times").addClass("fa-check");
		 $("#length").css("color", "green");
		 error = false; //=0
	 } else {
		 $("#length").removeClass("fa-check").addClass("fa-times");
		 $("#length").css("color", "red");
		 error = true;
	 }

	 // Valida que exista una letra.
	 if ( pass.match(/[a-zñ]/)) {
		 $("#letter").removeClass("fa-times").addClass("fa-check");
		 $("#letter").css("color", "green");
		 error = false; //=0
	 } else {
		 $("#letter").removeClass("fa-check").addClass("fa-times");
		 $("#letter").css("color", "red");
			 error = true;
	 }

	 // Valida que exista una letra mayúscula.
	 if ( pass.match(/[A-ZÑ]/)) {
		 $("#capital").removeClass("fa-times").addClass("fa-check");
		 $("#capital").css("color", "green");
		 error = false;
	 } else {
		 $("#capital").removeClass("fa-check").addClass("fa-times");
		 $("#capital").css("color", "red");
		 error = true;
	 }

	 // Valida un número.
	 if ( pass.match(/\d/)) {
		 $("#number").removeClass("fa-times").addClass("fa-check");
		 $("#number").css("color", "green");
				 error = false;
	 } else {
		 $("#number").removeClass("fa-check").addClass("fa-times");
		 $("#number").css("color", "red");
				 error = true;
	 }

	 //Valida que los campos sean iguales
				 $("#igual").removeClass("fa-check").addClass("fa-times");
				 $("#igual").css("color", "red");
				 noigual = true;

		 if(passc.length > 8 && passc.match(/[a-zñ]/) && passc.match(/[A-ZÑ]/) && passc.match(/\d/)){
			 if(pass == passc){
				 $("#igual").removeClass("fa-times").addClass("fa-check");
				 $("#igual").css("color", "green");
				 noigual = false;
			 }else{
				 $("#igual").removeClass("fa-check").addClass("fa-times");
				 $("#igual").css("color", "red");
				 noigual = true;
			 }
		 }

	 if(pass.length > 8 && pass.match(/[a-zñ]/) && pass.match(/[A-ZÑ]/) && pass.match(/\d/)){
		 error = false;
	 }
	 else if(pass.length < 8 && !pass.match(/[a-zñ]/) && !pass.match(/[A-ZÑ]/) && !pass.match(/\d/)){
		 error = true;
	 }
	}

	$("#guardar").click(function () {
	 var btn = $("#guardar");
	 var tipo = "";
	 btn.button("loading");
	 var nuevo_pass   = $("#nuevo_pass").val();
	 var confirm_pass = $("#confirm_pass").val();
	 var pass		 = $("#nuevo_pass").val();
	 var passc 		 = $("#confirm_pass").val()

	 if ( pass.length < 8) {
		 $("#length").removeClass("fa-check").addClass("fa-times");
		 $("#length").css("color", "red");
		 error = true;
	 }

	 if(!pass.match(/[a-zñ]/)) {
		 $("#letter").removeClass("fa-check").addClass("fa-times");
		 $("#letter").css("color", "red");
		 error = true;
	 }

	 if(!pass.match(/[A-ZÑ]/)) {
		 $("#capital").removeClass("fa-check").addClass("fa-times");
		 $("#capital").css("color", "red");
		 error = true;
	 }

		if(!pass.match(/\d/)) {
		 $("#number").removeClass("fa-check").addClass("fa-times");
		 $("#number").css("color", "red");
		 error = true;
	 }

	 if(pass != passc){
		 $("#igual").removeClass("fa-check").addClass("fa-times");
		 $("#igual").css("color", "red");
		 noigual = true;
	 }
			/* En caso que no hay error, que lo guarde*/
		 if (error == false && noigual == false) {
				 $.ajax({
					 type: "POST",
							 url: "mod_admin_usuarios/funciones/usu_validar_password.php",
					 data: {x1: pass
								 },
					 error: function(){
							 alert("Se ha detectado un error");
					 },
					 success: function (data) {
						 var info = data.split("|");
						 if (info[0] == 1){
							 //la contraseña no es igual. Voy a mandar a actualizar la constraseña
							 $.ajax({
								 type: "POST",
										 url: "mod_admin_usuarios/funciones/usu_cambiar_password.php",
								 data: {x1: pass
											 },
								 error: function(){
										 alert("Se ha detectado un error");
								 },
								 success: function (data) {
									 var info = data.split("|");
									 if (info[0] == 1){
										 tipo = "danger";
										 grl_mensaje("Error al actualizar contraseña. ", "Intentar mas tarde.", "danger");
											 $("#div_confirm_pass").removeClass("has-success").removeClass("add-error");
											 $("#div_nuevo_pass").removeClass("has-success").removeClass("add-error");
									 }else{
										 grl_mensaje("Se ha actualizado la contraseña ", "Favor verificar", "success");
										 window.setTimeout(function(){
											 window.location.href = "../../index.php";
										 }, 5000);
									 }
									 $(".limpiar").val("");
									 $("#div_confirm_pass").removeClass("has-success").removeClass("has-error");
									 $("#div_nuevo_pass").removeClass("has-success").removeClass("has-error");
									 $("#confirm_pass").popover("hide");
									 btn.button("reset");
								 }
							 }); //Ajax
						 }else{
							 //la constraseña es igual
							 grl_mensaje("Ingrese una contraseña que no haya utilizado en los ultimos tres meses. ", "Favor verificar", "danger");
							 $(".limpiar").val("");
							 $("#div_confirm_pass").removeClass("has-success").removeClass("has-error");
							 $("#div_nuevo_pass").removeClass("has-success").removeClass("has-error");
							 $("#confirm_pass").popover("hide");
							 btn.button("reset");
						 }
							 btn.button("reset");
					 }
				 }); //Ajax
				 /*si no que despliegue un mensaje, viene de las funciones generales*/
			 }else if(noigual == true){
				$("#confirm_pass").popover("show");
				$("#div_confirm_pass").removeClass("has-success").addClass("has-error");
				$("#div_nuevo_pass").removeClass("has-success").addClass("has-error");
				setTimeout(function(){
					$("#confirm_pass").popover("hide");
					},5000);
				/*si no que despliegue un mensaje, viene de las funciones generales*/
			}else{
				grl_mensaje("La contraseña no cumple los requisitos", "favor verificar", "danger");
				$("#nuevo_pass").popover("show");
				grl_verificar_contrasenia(this,this);
				$("#div_confirm_pass").removeClass("has-success").addClass("add-error");
				$("#div_nuevo_pass").removeClass("has-success").addClass("add-error");
				setTimeout(function(){
					$("#nuevo_pass").popover("hide");
					},5000);
			}
		btn.button("reset");
		}); //Guardar Click

			/*
			 * Crea el popover que ira en la verificación de la contraseña.
			 */
			$("#nuevo_pass").popover({
		        placement: "top",
		        animation: "true",
		        title: "<h4 class=\'translate\' data-traducir_english=\'Password requirements\' data-traducir_spanish=\'Requisitos de contraseña\'>Password requirements</h4>",
		        content: "<div><i id=\'letter\' class=\'fa fa-check valid\'><strong class=\'translate\' data-traducir_english=\'Minimum one lower case letter\' data-traducir_spanish=\'Mínimo una letra minúscula\'>Minimum one lower case letter</strong></i><i id=\'capital\' class=\'fa fa-check\'><strong class=\'translate\' data-traducir_english=\'Minimum one capital letter\' data-traducir_spanish=\'Mínimo una letra mayúscula\'>Mínimo una letra mayúscula</strong></i><i id=\'number\' class=\'fa fa-check \'><strong class=\'translate\' data-traducir_english=\'Minimum one number\' data-traducir_spanish=\'Mínimo un número\'>Minimum one number</strong></i><i id=\'length\' class=\'fa fa-check\'><strong class=\'translate\' data-traducir_english=\'No less than 8 characters\' data-traducir_spanish=\'No menor de 8 carácteres\'>No less than 8 characters</strong></i></div>",
		        container: "body",
		        html: "true",
		        trigger: "click"
		    });

			/*
			 * Crea el popover que se visualizará si las contraseñas no son iguales.
			 */
			$("#confirm_pass").popover({
		        placement: "top",
		        animation: "true",
		        title: "<h4 class=\'translate\' data-traducir_english=\'Verify Passwords\' data-traducir_spanish=\'Verificar constraseñas\'>Verificar contraseñas</h4>",
		        content: "<div><i id=\'igual\' class=\'fa fa-check valid\'><strong class=\'translate\' data-traducir_english=\'The new password and its verification are not the same.\' data-traducir_spanish=\'La nueva contraseña y su verificación no son iguales.\'>La nueva contraseña y su verificación no son iguales.</strong>",
		        container: "body",
		        html: "true",
		        trigger: "click"
		    });

			/*
			 * Clase verifica que la contraseña y su confirmación sean iguales y que no esten vacios.
			 */
			$(".igualar_pass").on("blur", function(){
				var pass = $("#nuevo_pass").val();
				var passc = $("#confirm_pass").val();
				grl_verificar_contrasenia(this,this);

			 //Verifica que el campo tenga información y sea igual que su confirmación.
				if(pass.length > 8 && pass.match(/[a-zñ]/) && pass.match(/[A-ZÑ]/) && pass.match(/\d/)){
					error = false;
					$("#div_nuevo_pass").removeClass("has-error").addClass("has-success");
				}else{
					error = true;
				}

				if(passc.length > 8 && passc.match(/[a-zñ]/) && passc.match(/[A-ZÑ]/) && passc.match(/\d/)){
					error = false;
					$("#div_confirm_pass").removeClass("has-error").addClass("has-success");
				}else{
					error = true;
				}

				if($("#confirm_pass").val() != ""){
					 if($("#nuevo_pass").val() == $("#confirm_pass").val()){
						noigual = false;
						$("#div_confirm_pass").removeClass("has-error").addClass("has-success");
					}else{
						 $("#div_confirm_pass").removeClass("has-success").addClass("has-error");
						noigual = true;
					}
				}

				if($("#nuevo_pass").val() != ""){
					if(pass.length > 8 && pass.match(/[a-zñ]/) && pass.match(/[A-ZÑ]/) && pass.match(/\d/)){
						$("#div_nuevo_pass").removeClass("has-error").addClass("has-success");
						error = false;
					}else {
						$("#div_nuevo_pass").removeClass("has-success").addClass("has-error");
						error = true;
					}
				}
		}).on("keyup", function(){
			var pass = $("#nuevo_pass").val();
			var passc = $("#confirm_pass").val();
			grl_verificar_contrasenia(this,this);

			if($("#nuevo_pass").val() != ""){
				if(pass.length > 8 && pass.match(/[a-zñ]/) && pass.match(/[A-ZÑ]/) && pass.match(/\d/)){
					$("#div_nuevo_pass").removeClass("has-error").addClass("has-success");
					error = false;
				}else{
					error = true;
				}
			}

			if($("#confirm_pass").val() != ""){
				if( passc.length > 8){
					if(passc.match(/[a-zñ]/) && passc.match(/[A-ZÑ]/) &&  passc.match(/\d/)){
						if( $("#confirm_pass").val() == $("#nuevo_pass").val()){
							 $("#div_confirm_pass").removeClass("has-error").addClass("has-success");
							error = false;
						}else{
							 $("#div_nuevo_pass").removeClass("has-success").addClass("has-error");
							 $("#div_confirm_pass").removeClass("has-success").addClass("has-error");
							error = true;
						}
					}
				}
			}
		}).on("click", function(){
			grl_verificar_contrasenia(this,this);
		})
  		$("#checkbox_translate").change(function(event) {
	        /* Act on the event */
	        event.preventDefault();
	        event.stopPropagation();
	        ($("#checkbox_translate").attr("checked") ? $("#checkbox_translate").removeAttr("checked") : $("#checkbox_translate").attr("checked","checked"))
	        grl_traducir_interfaz(($("#checkbox_translate").attr("checked") ? 1 : 0));
	    });
	}); // Ready Funtion
 </script>

 <style>
 .popover-content {
	 font-size: 15px;
	 font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
 }
 #confirm_pass,#nuevo_pass{
	border-bottom-right-radius:5px;
	border-top-right-radius:5px;
 }
 </style>
';

echo $cuerpo;

?>
