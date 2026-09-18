<?PHP
/*
 * Listado de noticias reporte
 * @author      Oscar Raudales
 * @date        2015-06-18 
 */
session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/mysql_conn.php"); 
include_once("../../libs/db_classes/db_noticias.php");
include_once("../../libs/db_classes/db_general.php");
/*INSTANCIAMIENTOS*/
$DB_NOTICIAS = new db_noticia();
$NOTICIAS   = $DB_NOTICIAS->get_noticias_activas();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Listado de Noticias</title>
</head>

<script type="text/javascript">
  $(document).ready(function(){
        not_listado_grupos_tipo_eventos();
        $("#cod_tipo_evento").prop('disabled', true).val('-b');
        $("#btn_todos_eventos").prop('disabled', true)
        $("#cod_tipo_grupo_evento").change(function(event) {
					
                                        var cod_grupo = $("#cod_tipo_grupo_evento").val();
					if (cod_grupo == null || cod_grupo == "" || cod_grupo == "-b"){
						$("#cod_tipo_evento").prop('disabled', true).val('-b');
						$("#cod_tipo_evento").selectpicker('setStyle', 'btn-danger');
						$("#cod_tipo_evento").selectpicker('setStyle', 'btn-info', 'remove');
					}
					else {
						not_listado_tipo_eventos(cod_grupo);
						$("#cod_tipo_evento").prop('disabled', false);
                                                $("#btn_todos_eventos").prop('disabled', false);
                                                $("#btn_todos_eventos").attr('title', 'Seleccionar Todos').tooltip('fixTitle').tooltip('show');
                                                $('#cod_tipo_evento option').prop('selected', false);
                                                $("#cod_tipo_evento").change();
					}
					$( ".selectpicker" ).selectpicker('refresh');
				}); 
	
	//Constructor del calendario para fechas
	$('#div_fecha_inicial, #div_fecha_final').datetimepicker({
		format: 'YYYY-MM-DD',
		locale: 'es',
		icons: {
					time: "fa fa-clock-o",
					date: "fa fa-calendar",
					up: "fa fa-arrow-up",
					down: "fa fa-arrow-down"
				}
	})	
                $(".date_fi").on("dp.hide",function (e) {
                var fecha_inicial  = $( '#fecha_inicial' ).val();
                var fecha_final = $( '#fecha_final' ).val();
                if ($( "#fecha_inicial" ).val() != '') $( "#div_fecha_inicial" ).removeClass('has-error');
                if ($( "#fecha_final" ).val() != '') $( "#div_fecha_final" ).removeClass('has-error');
                $('#div_fecha_final').data('DateTimePicker').minDate(Date.parse(fecha_inicial));                
                if((Date.parse(fecha_inicial)) > (Date.parse(fecha_final))) {
                                        grl_mensaje('Las fechas final no puede ser mayor a la fecha final,', 'favor verificar', 'danger');
					$(' #fecha_inicial ' ).text('');
                                        $( '#div_fecha_inicial' ).addClass('has-error');
            		}
                });
                $(".date_ff").on("dp.hide",function (e) {
                    var fecha_inicial  = $( '#fecha_inicial' ).val();
                    var fecha_final = $( '#fecha_final' ).val();
                    if ($( "#fecha_inicial" ).val() != '') $( "#div_fecha_inicial" ).removeClass('has-error');
                    if ($( "#fecha_final" ).val() != '') $( "#div_fecha_final" ).removeClass('has-error');
                    $('#div_fecha_inicial').data('DateTimePicker').maxDate(Date.parse(fecha_final));
                    if((Date.parse(fecha_inicial)) > (Date.parse(fecha_final))) {
                                            grl_mensaje('Las fechas final no puede ser mayor a la fecha final,', 'favor verificar', 'danger');
                                            $(' #fecha_final ' ).text('');
                                            $( '#div_fecha_final' ).addClass('has-error');
                                        }
                });
		//Habilitación de listboxs
		$('.selectpicker').selectpicker({
			dropupAuto: 'true',
			container: 'body',
			size: 'auto',
			width: '100%',
			style: 'btn-sm btn-info', 
            tickIcon: 'fa fa-check'
		});
                	    $('#cod_tipo_evento').selectpicker({
			dropupAuto: 'true',
			container: 'body',
			width: '85%',
			style: 'btn-sm btn-info has-btn-all',
			tickIcon: 'fa fa-check'
		});
                //Mascaras
		$('.letras').mask('SSSSSSSSSSSSSSSSSSSSSSSSSS', {translation: {'S': {pattern: /[A-Za-z ÁÉÍÓÚáéúíóúÑñ]/, optional: true}}});
		$('.fechas_validacion').mask('0000-00-00');
		//Validar selects
		$( ".selectpicker" ).change(function() {
			var objeto = $(this);
			if (objeto.val() == null){
				objeto.selectpicker('setStyle', 'btn-info', 'remove');
				objeto.selectpicker('setStyle', 'btn-danger');
			} else {
				objeto.selectpicker('setStyle', 'btn-danger', 'remove');
				objeto.selectpicker('setStyle', 'btn-info');
			}
			objeto.selectpicker('refresh');
		});
                $( "#cod_tipo_evento" ).change(function() {
			var objeto = $(this);
			if (objeto.val() == null){
				objeto.selectpicker('setStyle', 'btn-info', 'remove');
				objeto.selectpicker('setStyle', 'btn-danger');
				$("#btn_todos_eventos").addClass('btn-danger').removeClass('btn-info allselected');
			} else {
				objeto.selectpicker('setStyle', 'btn-danger', 'remove');
				objeto.selectpicker('setStyle', 'btn-info');
				$("#btn_todos_eventos").removeClass('btn-danger').addClass('btn-info');
			}
			objeto.selectpicker('refresh');
		});
  })//fin document ready
  $( "#excel" ).click(function() {
      print();
  })
    $( "#excel_bott" ).click(function() {
      print();
  })
  
   /*
    * Clic botón seleccionar/deseleccionar todo
    */
	   	$("#btn_todos_eventos").click(function(event){
	   		event.stopPropagation();
	   		if ($(this).hasClass('allselected')) {
	   			$(this).removeClass('allselected btn-info').addClass('btn-danger');
	   			$(this).attr('title', 'Seleccionar Todos').tooltip('fixTitle').tooltip('show');
	   			$('#cod_tipo_evento option').prop('selected', false);
			    $('#cod_tipo_evento').selectpicker('setStyle', 'btn-danger');
				$('#cod_tipo_evento').selectpicker('setStyle', 'btn-info', 'remove');
	   		}
	   		else{
	   			$(this).addClass('allselected btn-info').removeClass('btn-danger');
	   			$(this).attr('title', 'Desmarcar Todos').tooltip('fixTitle').tooltip('show');
	   			$('#cod_tipo_evento option').prop('selected', true);
			    $('#cod_tipo_evento').selectpicker('setStyle', 'btn-danger', 'remove');
				$('#cod_tipo_evento').selectpicker('setStyle', 'btn-info');
	   		}
		    $('#cod_tipo_evento').selectpicker('refresh');
	   	});
  function print(){
        var fecha_inicial           = $("#fecha_inicial").val()+' 00:00'; 
	var fecha_final             = $("#fecha_final").val()+' 23:59';
        var cod_tipo_grupo_evento   = $("#cod_tipo_grupo_evento").val(); 
        var cod_tipo_evento         = $("#cod_tipo_evento").val(); 
	var error = 0;	
	if (fecha_inicial == ''){
		$( '#div_fecha_inicial' ).addClass('has-error');
		if (error != 1) error = 1;
	}
	if (fecha_final == ''){
		$( '#div_fecha_final' ).addClass('has-error');
		if (error != 1) error = 1;
	}
	 if (cod_tipo_grupo_evento == '-b'){
		$("#cod_tipo_grupo_evento").selectpicker('setStyle', 'btn-info', 'remove');
		$("#cod_tipo_grupo_evento").selectpicker('setStyle', 'btn-danger');
		if (error != 1) error = 1;
	}
        if (cod_tipo_evento == '-b' || cod_tipo_evento == '' || cod_tipo_evento == null){
		$("#cod_tipo_evento").selectpicker('setStyle', 'btn-info', 'remove');
		$("#cod_tipo_evento").selectpicker('setStyle', 'btn-danger');
                 $("#btn_todos_eventos").addClass('btn-danger').removeClass('btn-info allselected');
		if (error != 1) error = 1;
	}    
        $( ".selectpicker" ).selectpicker('refresh');
/******************************* FIN NUEVO **********************************/	        
	if (error != 1) {
                grl_overlay_loading('Cargando');
                x1 = fecha_inicial;   
                x2 = fecha_final;
                x3 = cod_tipo_grupo_evento; 
                x4 = cod_tipo_evento.toString();
                var url = "mod_noticias/reportes/not_listado_noticias_excel.php?x1="+x1+"&x2="+x2+"&x3="+x3+"&x4="+x4;    
		$('#modal_loading').modal('hide');
                $(location).attr('href',url);		
        }
        else {
                grl_mensaje('Campos vacios,', ' favor llenar los marcados', 'warning');		
	}   
};
function buscar_noticias(){
        var btn                     = $("#buscar");
	btn.button('loading');
	var fecha_inicial           = $("#fecha_inicial").val()+' 00:00'; 
	var fecha_final             = $("#fecha_final").val()+' 23:59';
        var cod_tipo_grupo_evento   = $("#cod_tipo_grupo_evento").val(); 
        var cod_tipo_evento         = $("#cod_tipo_evento").val(); 
	var error = 0;	
	if (fecha_inicial == ''){
		$( '#div_fecha_inicial' ).addClass('has-error');
		if (error != 1) error = 1;
	}
	if (fecha_final == ''){
		$( '#div_fecha_final' ).addClass('has-error');
		if (error != 1) error = 1;
	}
	 if (cod_tipo_grupo_evento == '-b'){
		$("#cod_tipo_grupo_evento").selectpicker('setStyle', 'btn-info', 'remove');
		$("#cod_tipo_grupo_evento").selectpicker('setStyle', 'btn-danger');
		if (error != 1) error = 1;
	}
        if (cod_tipo_evento == '-b' || cod_tipo_evento == '' || cod_tipo_evento == null){
		$("#cod_tipo_evento").selectpicker('setStyle', 'btn-info', 'remove');
		$("#cod_tipo_evento").selectpicker('setStyle', 'btn-danger');
                $("#btn_todos_eventos").addClass('btn-danger').removeClass('btn-info allselected');
		if (error != 1) error = 1;
	}    
        $( ".selectpicker" ).selectpicker('refresh');
/******************************* FIN NUEVO **********************************/	        
	if (error != 1) { 
                grl_overlay_loading('Cargando');
		$.ajax({
			type: 'POST',
			url: 'mod_noticias/reportes/not_listado_noticias_php.php',
			data: {     x1:fecha_inicial, 
                                    x2:fecha_final, 
                                    x3:cod_tipo_grupo_evento,  
                                    x4:cod_tipo_evento, 
				   },
			error: function(){	
				grl_mensaje('Error al enviar el contenido', 'favor intentar nuevamente', 'danger');	
			   },
			success: function (data) {
                                $('#modal_loading').modal('hide');
				$('#cuerpo_tabla_repo').empty();
                                $('#cuerpo_tabla_repo').html(data);
                                $('#modal_loading').modal('hide');
                                }
		}); //Ajax*/
	} else { 	
		grl_mensaje('Campos vacios', 'favor llenar los marcados', 'warning');		
	}
	btn.button('reset');
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
        .btn-all-stick{
		width: 15%;
		float: left;
		display: inline-block;
		border-top-right-radius: 0px;
		border-bottom-right-radius: 0px;
		padding: 7px 0px 7px 0px;
	}
	.has-btn-all{
		border-top-left-radius: 0px;
		border-bottom-left-radius: 0px;
	}
	.check-all i{
		color: rgb(255, 255, 255);
	}
	.btn-info.check-all i{
		color: rgb(173, 223, 237);
	}
	.btn-danger.check-all i{
		color: rgb(223, 133, 130);
	}
	.allSelected i{
		color: rgb(255,255,255) !important;
	}        
</style>
 
<body>
    <form enctype="multipart/form-data"  role="form" method="post" id="formulario" name="formulario">
     <div id="overlay_loading"></div>
    <div class="panel panel-default">
  	<div class="panel-body">
        <div class=" page-header" >
        	<h1>
            	Reporte de Noticias por fecha
            <small></small></h1>
        </div> <!-- fin page-header -->
                    <div class="row">
                        
                        <div class="col-md-3">
                            <label>Clasificación de evento</label>
                            <div class="form-group input-group-sm">
                                <select class="selectpicker show-menu-arrow " id="cod_tipo_grupo_evento" name="cod_tipo_grupo_evento">
                                </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Tipo de evento</label>
                        <div class="form-group  show-tick">
                            <button class="btn btn-info btn-all-stick check-all has-tooltip" id="btn_todos_eventos" title="Seleccionar todos" data-toggle="tooltip" type="button">
                                <i class="fa fa-check"></i>
                            </button>
                            <select class="selectpicker show-menu-arrow " multiple id="cod_tipo_evento" name="cod_tipo_evento" title="Seleccione">
                             </select>

                        </div>
                    </div>
                        <div class="col-md-2">
                        <div class="form-group input-group-sm date date_fi" id="div_fecha_inicial">
                        	<label for="fecha_inicial">Fecha inicial</label>
                        	<div class='input-group input-group-sm ' id='div_fecha_inicial'>
                        		<span class="input-group-addon"><span class="fa fa-calendar">
                       				</span>
                        		</span>
                        		<input type='text' class="form-control" id="fecha_inicial"/>
                        	</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group input-group-sm date date_ff" id="div_fecha_final">
                        	<label for="fecha_final">Fecha final</label>
                        	<div class='input-group input-group-sm ' id='div_fecha_final'>
                        		<span class="input-group-addon"><span class="fa fa-calendar">
                       				</span>
                        		</span>
                        		<input type='text' class="form-control" id="fecha_final"/>
                        	</div>
                        </div>
                    </div>
                        <div class="col-md-1">
                                <div class="form-group input-group-sm" id="div_buscar">
                                        <center>
                                            <label for="buscar">&nbsp;</label>
                                                <div class="form-group input-group-sm" id="div_buscar">
                                                        <button class="btn btn-info" type="button" id="buscar" name="buscar" onclick="buscar_noticias();">
                                                                    <i class="fa fa-search"></i>
                                                        </button>	
                                                </div>					
                                        </center>
                                </div>
                        </div>
                        <div class="col-md-1">
                            <label >&nbsp; </label>   
                            <div class="form-group input-group-sm">
                                <a href="#" id="excel" class="btn btn-sm btn-success" role="button">Excel</a>
                            </div>
                        </div> 
                    </div> <!-- Row --> 
                    <div class="row" id="cuerpo_detalle_tabla">
                        <div class="col-md-12">
                        <!-- Panel -->
                        <div class="panel panel-info" >
                                <div style="overflow:auto">
                                <!-- Table -->
                                    <table class="table table-hover table-condensed" id="dev-table">
                                        <thead>
                                            <tr class="active info">                       
                                                <th width="3%">Nro.</th>
                                                <th width="7%">Fecha</th>
                                                <th width="22%">Nombre del Evento</th>
                                                <th width="10%">Tipo de evento</th>
                                                <th width="15%">Organizador</th>
                                                <th width="15%">Lugar</th>
                                                <th width="5%">Participantes</th>
                                                <th width="23%">Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cuerpo_tabla_repo">
                                                <tr>
                                                    <td colspan="8" align="center">No hay registros.</td>
                                                </tr>
                                        </tbody>  
                                    </table> 
                                </div>
                        </div>
		            	
		         	</div>
		        </div> <!-- Row cuerpo detalle tabla--> 
                        </div> <!-- panel body --> 
                        <div class="panel-footer" align="right">
                                     <a href="#" id="excel_bott" class="btn btn-sm btn-success" role="button">Excel</a>
                        </div>   
            </div><!-- Fin Panel-default -->
    </form>
</body>
</html>