<?PHP
/*
 * Pantalla donde se visualiza la información basica del usuario en sesión.
 * @author      Dan Urquía
 * @date        2014-02-09
 */
 
session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: ../index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once("../libs/db_classes/db_mysql_conn.php"); 
include_once("../libs/db_classes/db_rrhh.php");
/*INSTANCIAMIENTOS*/
$DB_RRHH       = new db_rrhh();
$INFO_EMPLEADO = $DB_RRHH->get_perfil_usuario($_SESSION['cod_usuario']);
//$flag_alert    = -1; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <title>Inpos: Perfil de Usuario</title>
</head>

<body>
 <form id="form1" name="form1" class="form-signin" role="form" method="post" action="">
  <div class="container">
     <div class="well well-lg">
     <!--header -->
            <div class="page-header">
                <h1>Perfil de Usuario <small>Informaci&oacuten general del usuario</small></h1>
            </div>
            
            <div class="panel panel-default">
            
            <!--body-->
             <div class="panel-body">
               <center>
                   <!--1parte-body-->
                <img src="<?PHP echo $INFO_EMPLEADO[0]['fotografia'];?>" width="200" height="150" class="img-circle img-responsive">
                   </br>      
                   <!--2parte-body-->
                  <div class="row" align="center" style="overflow:auto;"> 
                  
                   <div class="col-md-3" style="width:auto"> 
                        <label for="nombre">Nombre del usuario</label>
                        <h4><span class="label label-warning">
							 <?PHP echo ($INFO_EMPLEADO[0]['primer_nombre']).' '.
							 utf8_encode ($INFO_EMPLEADO[0]['segundo_nombre']).' '.
							 utf8_encode ($INFO_EMPLEADO[0]['primer_apellido']).' '.
							 utf8_encode ($INFO_EMPLEADO[0]['segundo_apellido']);?>
                        </span></h4>
                   </div>
                    
                    <div class="col-md-3" style="width:auto" >   
                        <label for="ID">Departamento</label>
                        <h4><span class="label label-danger" >
                        <?PHP echo $INFO_EMPLEADO[0]['departamento'];?>
                        </span></h4>
                   </div> 
                   
                   <div class="col-md-3" style="width:auto">   
                        <label for="correo" name= "correo" id= "correo">Correo electr&oacutenico </label>
                        <h4><span class="label label-info"  >
                        <?PHP echo $INFO_EMPLEADO[0]['email'];?>
                        </span></h4>
                   </div>       
               
                   <div class="col-md-3" style="width:auto">    
                        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                        <h4><span class="label label-success">
                        <?PHP echo $INFO_EMPLEADO[0]['fecha_nacimiento'];?>
                        </span></h4>
                   </div>  
                       
                
                 </div> 
                 
                  <div class="col-12 col-sm-12 col-lg-12">      
                   <hr>
                  </div>
                  
                   <div class="row" align="center" >
                    <div class="form-group" >
                     <label>Cambiar contrase&ntilde;a</label>
                   </div>
                          </div>
                  </br>
                  
                  <!--tercera parte-body-->
            
                    <!-- <div class="row" align="center">
                               
                        <!--<div class="form-group" > -->
                        <div class="col-md-3" style=" padding-bottom:5px">
                        <div id="div_pass_actual" class="input-group">
                          <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                          <input id="usuario" name="usuario"  readonly value="<?PHP echo $INFO_EMPLEADO[0]['usuario'];?>" type="text" class="form-control" placeholder="Usuario">
                        </div>
                        </div>
                  	    <!--</div> -->
                      
                        <!--<div class="form-group">-->
                        <div class="col-md-3" style="padding-bottom:5px">
                        <div id="div_pass_actual" class="input-group">
                          <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
                          <input id="pass_actual" name="pass_actual" type="password" autocomplete="off" class="form-control limpiar" placeholder="Contrase&ntilde;a actual">
                        </div>
                        </div>
                  	    <!--</div>-->
                     
                        <!--<div class="form-group"> -->
                    	<div class="col-md-3" style="padding-bottom:5px">
                        <div id="div_nuevo_pass" class="input-group">
                          <span class="input-group-addon"><i class="fa fa-unlock fa-fw"></i></span>
                          <input id="nuevo_pass" name="nuevo_pass" type="password" class="form-control igualar_pass limpiar" placeholder="Nueva contrase&ntilde;a">
                        </div>
                        </div>
                        <!--</div>-->
                        
                        <!--<div class="form-group"> -->
                        <div class="col-md-3" style="padding-bottom:5px">
                        <div id="div_confirm_pass" class="input-group">
                          <span class="input-group-addon"><i class="fa fa-lock fa-fw"></i></span>
                          <input id="confirm_pass" name="confirm_pass" type="password" class="form-control igualar_pass limpiar" placeholder="Confirmar contrase&ntilde;a">
                        </div>
                        </div>
                        <!--</div> -->
                        
            		<!--</div>-->
                    
             </center>
             </div>
        
             <!--footer -->
             <div class="panel-footer" align="right">
               <button id="guardar" name="guardar" class="btn btn-s btn-primary" type="button" class="form-control" data-loading-text="Guardando...">Guardar</button>
             </div>
        
         </div> <!-- panel default-->
          
     </div><!-- 	Well -->
   </div><!-- 	Container --> 
 </form>
</body>

<style>
.popover-content {
    color: red;
    font-size: 15px;
	font-family: "Times New Roman", Times, serif;
}
#confirm_pass,#nuevo_pass,#pass_actual,#usuario{
	border-bottom-right-radius:5px;
	border-top-right-radius:5px;
}
</style>

<script>
error = false;
noigual=false;
pass = $("#nuevo_pass").val();
passc = $("#confirm_pass").val();

 $(document).ready(function(){


	 $('#div_confirm_pass').change(function(){
       $('#div_confirm_pass').removeClass('has-error');
       $('#div_confirm_pass').addClass('has-default');
	   });
/*
 * Verifica que la contraseña cumpla con los requisitos minimos de evaluación.
 * Mas de 8 caracteres,minuscula,minuscula y números
 * var objeto char   Objeto que se esta evaluando.
 */
function grl_verificar_contrasenia (objeto,objeto){
	var pass = $("#nuevo_pass").val();
	var passc = $("#confirm_pass").val();	
	
	// Valida que la contraseña no sea menor a 8 caracteres.
	if ( pass.length > 8) {
		$('#length').removeClass('fa-times').addClass('fa-check');
		$('#length').css('color', 'green');
	 	noigual = false; //=0
	} else {
		$('#length').removeClass('fa-check').addClass('fa-times');
		$('#length').css('color', 'red');
		noigual = true;	
		}
     
	// Valida que exista una letra.
	if ( pass.match(/[a-zñ]/)) {
		$('#letter').removeClass('fa-times').addClass('fa-check');
		$('#letter').css('color', 'green');
		error = false; //=0		
	} else {
		$('#letter').removeClass('fa-check').addClass('fa-times');
		$('#letter').css('color', 'red');
	    error = true;  
	}
	
	// Valida que exista una letra mayúscula.
	if ( pass.match(/[A-ZÑ]/)) {
		$('#capital').removeClass('fa-times').addClass('fa-check');
		$('#capital').css('color', 'green');
		error = false;		
	} else {
		$('#capital').removeClass('fa-check').addClass('fa-times');
		$('#capital').css('color', 'red');
		error = true;	
	}
	
	// Valida un número.
	if ( pass.match(/\d/)) {
		$('#number').removeClass('fa-times').addClass('fa-check');
		$('#number').css('color', 'green');
        error = false;	
	} else {
		$('#number').removeClass('fa-check').addClass('fa-times');
		$('#number').css('color', 'red');
        error = true;
	}
	//Valida que los campos sean iguales
	if($('#nuevo_pass').val() == $('#confirm_pass').val()){
		error = false;
	}else{
		error = true;
	}
	
	if(pass.length > 8 && pass.match(/[a-zñ]/) && pass.match(/[A-ZÑ]/) && pass.match(/\d/)){
		error = false;
	}
	else if(pass.length < 8 && !pass.match(/[a-zñ]/) && !pass.match(/[A-ZÑ]/) && !pass.match(/\d/)){
		error = true;
	}
	
	if(passc.length > 8 && passc.match(/[a-zñ]/) && passc.match(/[A-ZÑ]/) && passc.match(/\d/)){
		error = false;
	}else if(passc.length < 8 && !passc.match(/[a-zñ]/) && !passc.match(/[A-ZÑ]/) && !passc.match(/\d/)){
		error = true;
	}	
  }

$('#guardar').click(function () {
	var btn = $("#guardar");
	var tipo = '';
	btn.button('loading');
	var usuario      = $("#usuario").val(); 
	var pass_actual  = $("#pass_actual").val(); 
	var nuevo_pass   = $("#nuevo_pass").val(); 
	var confirm_pass = $("#confirm_pass").val(); 
    var pass = $("#nuevo_pass").val();
    var passc = $("#confirm_pass").val()
  
if ( pass.length < 8) {
		$('#length').removeClass('fa-check').addClass('fa-times');
		$('#length').css('color', 'red');
		error = true;
	} 
	
if(!pass.match(/[a-zñ]/)) {
		$('#letter').removeClass('fa-check').addClass('fa-times');
		$('#letter').css('color', 'red');
	   error = true; 
	   	}	
		
if(!pass.match(/[A-ZÑ]/)) {
		$('#capital').removeClass('fa-check').addClass('fa-times');
		$('#capital').css('color', 'red');
		error = true;
 }

 if(!pass.match(/\d/)) {
		$('#number').removeClass('fa-check').addClass('fa-times');
		$('#number').css('color', 'red');
        error = true;
 }
 
/* En caso que no hay error, que lo guarde*/		
if (error == false && noigual == false) { 

			$.ajax({
				type: 'POST',
		        url: 'mod_perfil_usuario/funciones/prf_cambiar_contrasenia.php',
				data: {x1: usuario,
				       x2: pass_actual,
				       x3: nuevo_pass,
				       x4: confirm_pass
					},
				error: function(){	
					alert('Se ha detectado un error');
				   },
				success: function (data) {
					var info = data.split("|");
					info[0] == 1 ? tipo = 'danger' : tipo = 'success';
					setTimeout(function() {
						$.bootstrapGrowl(info[1], {
							type: tipo,
							align: 'center',
							width: 'auto',
							allow_dismiss: false
						});
					}, 100);	
			        
					$(".limpiar").val('');	
					$('#div_confirm_pass').removeClass('has-success').removeClass('has-error');
			        $('#div_nuevo_pass').removeClass('has-success').removeClass('has-error');
					$('#confirm_pass').popover('hide');
					btn.button('reset');
				}
			}); //Ajax*/ 
			/*si no que despliegue un mensaje, viene de las funciones generales*/
		} else{ 
		grl_mensaje('La contraseña no cumple los requerimientos', 'favor verificar', 'danger');		
		$('#confirm_pass').popover('show');
	    setTimeout(function(){
        $('#confirm_pass').popover('hide');
        },5000);
			$('#div_confirm_pass').removeClass('has-success').removeClass('add-error');
			$('#div_nuevo_pass').removeClass('has-success').removeClass('add-error');
		}
		btn.button('reset');
	}); //Guardar Click	
	
	/*
	 * Crea el popover que ira en la verificación de la contraseña.
	 */
	$("#nuevo_pass").popover({placement:'top',
							  animation:'true',
							  title:"<h4>Requisitos de contraseña</h4>",
							  content:"<div><i id='letter' style='color:red' class='fa fa-check valid'> Mínimo <strong>una letra minúscula</strong></i><i id='capital' class='fa fa-check '> Mínimo <strong>una letra mayúscula</strong></i><i id='number' class='fa fa-check '> Mínimo <strong>un número</strong></i><i id='length' class='fa fa-check '> No menor de <strong>8 carácteres</strong></i></div>",
							  container: 'body',
							  html: 'true',
							  trigger: 'focus' });
							  
	/*
	 * Crea el popover que se visualizará si las contraseñas no son iguales.
	 */
	$("#confirm_pass").popover({
							  placement:'bottom',
							  animation:'true',
							  title:"<h4>Verificar contraseñas</h4>",
							  content:"<strong>Las nueva contraseña y su verificación no son iguales.</strong>",
							  container: 'body',
							  html: 'true',
							  trigger: 'manual',
							   });
						
	/*
	 * Clase verifica que la contraseña y su confirmación sean iguales y que no esten vacios.
	 */					
	$('.igualar_pass').on('blur', function(){
		
	var pass = $("#nuevo_pass").val();
	var passc = $("#confirm_pass").val();
	
	
   //Verifica que el campo tenga información y sea igual que su confirmación.
   if(pass.length > 8 && pass.match(/[a-zñ]/) && pass.match(/[A-ZÑ]/) && pass.match(/\d/)){
		error = false;
		$('#div_nuevo_pass').removeClass('has-error').addClass('has-success');
   }else{
		error = true;
		$('#div_nuevo_pass').removeClass('has-success').addClass('has-error');
   }
    
	
	if(passc.length > 8 && passc.match(/[a-zñ]/) && passc.match(/[A-ZÑ]/) && passc.match(/\d/)){
		error = false;
		$('#div_confirm_pass').removeClass('has-error').addClass('has-success');
  	 }else{
		error = true;
		$('#div_confirm_pass').removeClass('has-success').addClass('has-error');
  	 }
	 
   
	if($('#confirm_pass').val() != ""){
    if(passc.length > 8 && passc.match(/[a-zñ]/) && passc.match(/[A-ZÑ]/) && passc.match(/\d/)){
		
		error = false;
		$('#div_confirm_pass').removeClass('has-error').addClass('has-success');
  	 }else{
		error = true;
		//$('#div_confirm_pass').removeClass('has-success').addClass('has-error');
  	 }
	 }
	
		
	if($('#nuevo_pass').val() != ""){
	if(passc.length > 8 && passc.match(/[a-zñ]/) && passc.match(/[A-ZÑ]/) && passc.match(/\d/)){

	 $('#div_nuevo_pass').removeClass('has-error').addClass('has-success');
     	error = false;
	 }else {
		//$('#div_nuevo_pass').removeClass('has-success').addClass('has-error');	
	error = true;
	  }
	  
	}
	}).on('keyup', function(){
    var pass = $("#nuevo_pass").val();
	var passc = $("#confirm_pass").val();
	grl_verificar_contrasenia(this,this);
	
	if($('#nuevo_pass').val() != ""){
 	if(pass.length > 8 && pass.match(/[a-zñ]/) && pass.match(/[A-ZÑ]/) && pass.match(/\d/)){
	 $('#div_nuevo_pass').removeClass('has-error').addClass('has-success');
		error = false;
	}
	else{
		error = true;
		//$('#div_nuevo_pass').removeClass('has-success').addClass('has-error');
		}
	}
	
	if($('#confirm_pass').val() != ""){
   if(passc.length > 8 && passc.match(/[a-zñ]/) && passc.match(/[A-ZÑ]/) && passc.match(/\d/)){
	    if( $('#confirm_pass').val() == $('#nuevo_pass').val()){
		error = false;
		$('#div_confirm_pass').removeClass('has-error').addClass('has-success');
   }else{
		error = true;
		//$('#div_confirm_pass').removeClass('has-success').addClass('has-error');
   }	
   }
	}
  /* if($('#confirm_pass').val() != ""){
	if(passc.length > 8 && passc.match(/[a-zñ]/) && passc.match(/[A-ZÑ]/) && passc.match(/\d/)){
		//$('#div_nuevo_pass').removeClass('has-error').addClass('has-success');
     	$('#div_confirm_pass').removeClass('has-error').addClass('has-success');
		error = false;
	}else {
		//$('#div_nuevo_pass').removeClass('has-success').addClass('has-error');
		$('#div_confirm_pass').removeClass('has-success').addClass('has-error');
		error = true;
	}
	}
	
	
	if($('#confirm_pass').val() != ""){
	if(passc.length > 8 && passc.match(/[a-zñ]/) && passc.match(/[A-ZÑ]/) && passc.match(/\d/)){
		if( $('#confirm_pass').val() == $('#nuevo_pass').val()){
		$('#div_nuevo_pass').removeClass('has-error').addClass('has-success');
     	$('#div_confirm_pass').removeClass('has-error').addClass('has-success');
		error = false;
	}else {
		$('#div_nuevo_pass').removeClass('has-success').addClass('has-error');
		$('#div_confirm_pass').removeClass('has-success').addClass('has-error');
		error = true;
	}
	}
	}*/
	
	
	
	/*if($('#confirm_pass').val() != ""){
		if( $('#confirm_pass').val() == $('#nuevo_pass').val()){
		$('#div_nuevo_pass').removeClass('has-error').addClass('has-success');
     	$('#div_confirm_pass').removeClass('has-error').addClass('has-success');	
		}else {
		$('#div_nuevo_pass').removeClass('has-success').addClass('has-error');
		$('#div_confirm_pass').removeClass('has-success').addClass('has-error');
		error = true;
	}
	}*/
				
	
		})
	
 }); // Ready Funtion
</script>