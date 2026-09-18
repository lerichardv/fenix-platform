<?PHP
/*
 * Listado de los estados de plantaciones.
 * @author      Jairo Bonilla
 * @date        2018-10-25
*/

session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_plantaciones.php");
/*INSTANCIAMIENTOS*/
$DB_PLANT = new db_plantaciones();

$flag_exploracion   = trim(($_POST['x1']));
$cod_exploracion    = trim(($_POST['x2']));


$ADJUNTOS	= $DB_PLANT->plan_listado_adjuntos_exploraciones($flag_exploracion,$cod_exploracion);
$contador = 1;
?>
<script type="text/javascript">
    var contador = 1;
    var array_file_list_exploracion = []; 
    var cod_adjunto = 1;

    function update_array_file_list_exploracion(id, file, file_ext) 
    {
        for (var i in array_file_list_exploracion) {
            if (array_file_list_exploracion[i].id == id) {
                array_file_list_exploracion[i].file     = file;
                array_file_list_exploracion[i].file_ext = file_ext;
                break;
            }
        }
    }
    /*----------------------------------------------------------------------------------
                            Toma los archivos y los asigna a la variable,
                                Validación del tipo de archivo
        ----------------------------------------------------------------------------------*/
        function prepare_upload_exploracion(event){
            var $container_upload_box = $(this).parent();
            files = event.target.files;
            var cancel_button_is_clicked = files[0];
            if( cancel_button_is_clicked == undefined ){
                constructor_file_input_mat($(this).parent());
            } 
            else{
                var id = $container_upload_box.find('.btn-select-file').data('id');
                var filesize =  files[0].size/1024/1024;
                if(filesize > 20){
                    grl_mensaje('Tamaño de archivo no permitido ', 'Solo se permiten archivos menores de 20 MB', 'warning');
                    cambio_adjunto = false;
                    constructor_file_input_mat($container_upload_box);
                }
                else{
                    var ext = this.value.match(/\.([^\.]+)$/)[1];
                    ext =ext.toLowerCase();
                    update_array_file_list_exploracion(id, files, ext);
                    switch(ext)
                    {
                        case 'avi':
                        case 'mpg':
                        case 'mpeg':
                        case 'wmv':
                        case '3gp':
                        case 'mp4':
                        case 'mp3':
                        case 'ogg':
                        case 'm4a':
                        case 'wav':
                        case 'xls':
                        case 'xlsx':
                        case 'ppt':
                        case 'pptx':
                        case 'pps':
                        case 'ppsx':
                        case 'pdf':
                        case 'doc':
                        case 'docx':
                        case 'jpg':
                        case 'jpeg':
                        case 'bmp':
                        case 'png':
                            $container_upload_box.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file ready-to-upload');
                            $container_upload_box.find('.btn-upload-file').removeClass().addClass('smooth-transition btn-upload-file ready-to-upload');
                            $container_upload_box.find('.btn-upload-file').data('disabled',false);
                            $container_upload_box.find('i.icon-file').removeClass().addClass('icon-file fa fa-check');
                            $container_upload_box.find('span.msj-btn-file').text(' Archivo listo para subir');
                            $container_upload_box.find('.btn-view-file').remove();
                            $container_upload_box.find(".btn-view-file-delete").parent('div').remove();
                            break;
                        default:{
                            grl_mensaje('Tipo de archivo no permitido', 'solo se permiten archivos PDF, Office, Video, Audio e imágenes', 'warning');
                            constructor_file_input($(this).parent());
                        }
                    }
                }
            }
            
        }

        /* Clic botón seleccionar archivo */
        $(".btn-select-file.adjunto_exploracion").on('click',function (e){
            e.stopPropagation();
            if( $(this).data('disabled') == false){
                $(this).parent().find('input[type=file]').click();
            }
            return false;
        });
        /* Clic botón subir archivo */
        $('.btn-upload-file.adjunto_exploracion').on('click', function (e){
            e.stopPropagation();
            if( $(this).data('disabled') == false){
                var $container_upload_box = $(this).parent();
                var id = $(this).data('id');
                var $li = $container_upload_box.closest(".row");
                // Busca id dentro del objeto [array_file_list_exploracion] 
                var temp_array_exploracion = array_file_list_exploracion.filter(function(attr) {
                    return attr.id == id;
                });
                $li.unbind('click');
                constructor_progress_bar($container_upload_box);
                subir_archivo_exploracion($container_upload_box,temp_array_exploracion[0]['file'],temp_array_exploracion[0]['id'],temp_array_exploracion[0]['file_ext']);
            }
        });
        /* Disparador input seleccionador de archivo */
        $('input[type=file]').on('click',function (e){
            e.stopPropagation();
        });
        // Evento al seleccionar un archivo
        $('input[type=file]').on('change', prepare_upload_exploracion );

    function constructor_file_input_mat($container_upload_box)
    {
        $container_upload_box.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file');
        $container_upload_box.find('.btn-upload-file').removeClass().addClass('smooth-transition btn-upload-file');
        $container_upload_box.find('.btn-upload-file').data('disabled',true);
        $container_upload_box.find('i.icon-file').removeClass().addClass('icon-file fa fa-file');
        $container_upload_box.find('span.msj-btn-file').text(' Seleccione archivo');
        $container_upload_box.find('.btn-view-file').addClass('hide');

        $container_upload_box.find("input[type=file]").replaceWith(
            $container_upload_box.find("input[type=file]").val('').clone( true )
            );
        $container_upload_box.find("input[type=file]").attr('title', 'Seleccione un Archivo');
        files       = '';
        file_ext    = '';
        var id      = $container_upload_box.find('.btn-select-file').data('id');
        update_array_file_list_exploracion(id, files, file_ext);
    }

    function update_list_item_interface_exploracion($li,nombre_archivo,orden,cadena){
        $li.unbind('click');                    
        if(nombre_archivo != ''){
            $li.addClass('row-with-attachment');
            $li.find("span.state-icon").addClass('fa-paperclip').removeClass('fa-check-square-o');
            $li.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file with-attachment').data('disabled',false);
            $li.find('i.icon-file').addClass('hide');
            $li.find('span.msj-btn-file').text(nombre_archivo);
            $li.find(".btn-upload-file").addClass('hide').data('disabled',true);
            $li.find(".container-upload-box").append('<a tabindex="0" target="_blank" href="../mod_plantaciones/adjuntos/'+nombre_archivo+'" class="smooth-transition btn-view-file btn-finalizar">' +
                                                        '<i class="fa fa-eye"></i>' +
                                                    '</a>' +
                                                    '<div style="width:30px;display: table-cell;" ><button type="button" class="smooth-transition btn_delete_file'+orden+' btn-view-file-delete btn-finalizar" data-value=""><i class="far fa-trash-alt"></i></button></div>');
            $(".btn_delete_file"+orden).on("click",function(){
                    cur_borrar_adjunto($(this).data("value"));
                    update_list_item_interface_exploracion2($li,orden);
            });
        }
    }

    function update_list_item_interface_exploracion2($li,orden){
        console.log($li);
        console.log(orden);
        $li.find('.btn-select-file').removeClass().addClass('smooth-transition btn-select-file').data('disabled',false);
        $li.find('i.icon-file').removeClass().addClass('icon-file fa fa-file');
        $li.find('span.msj-btn-file').text('Seleccione archivo');
        $li.find(".btn-upload-file").removeClass().addClass('smooth-transition btn-upload-file').data('disabled',true);
        $li.find(".btn-view-file").remove();
        $li.find(".btn-view-file-delete").parent('div').remove();
        $li.find("input[type=file]").replaceWith(
            $li.find("input[type=file]").val('').clone( true )
        );
        $li.find("input[type=file]").attr('title', 'Seleccione un Archivo');
        $li.on('change','input[type=file]',prepare_upload_exploracion);
    }

    /*
    *   Sube archivos al servidor por medio de ajax y PHP
    */   
    function subir_archivo_exploracion($container_upload_box,archivo,id,ext_archivo){
        //event.stopPropagation(); // Evita que sucedan acciones
        //event.preventDefault(); // Evita que todas las acciones del formulario se realicen
        console.log(id);
        if ( archivo != '') {
            var files = archivo;
            // Crea un objeto  formdata y le agrega los archivos.
            var data = new FormData();
            $.each(files, function(key, value){data.append(key, value);});
            contador = id;
            nombre = 'adjunto_'+contador+'_'+ <?php echo $flag_exploracion; ?> +'_'+ <?php echo $cod_exploracion; ?> + "." + ext_archivo;
            $.ajax({
                xhr: function(){
                    //var xhr = new window.XMLHttpRequest();
                    var xhr = new XMLHttpRequest();
                    //Upload progress
                    xhr.addEventListener('loadstart', function() { 
                        xhr.upload.addEventListener("progress", function(e){
                            if (e.lengthComputable) {
                                var percentComplete = Math.round((e.loaded / e.total)*100);
                                //Do something with upload progress
                                console.log(percentComplete);
                                $container_upload_box.find('.progress-bar').css('width', percentComplete+'%');
                            }
                        }, false);
                    });
                        return xhr;
                },
                url:  'mod_plantaciones/funciones/plan_subir_adjunto.php?files&x1='+nombre,
                type: 'POST',
                data: data,
                cache: false,
                //dataType: 'json',
                processData: false, // No procesa los archivos 
                contentType: false, // Set content type to false as jQuery will tell the server its a query string request              
                success: function(data, textStatus, jqXHR){
                    if(typeof data.error === 'undefined'){
                        // Todo bien, así que copia definitivamente los archivos al servidor. 
                        grl_mensaje('Archivo agregado correctamente.', '', 'success');
                    }
                    else{
                        grl_mensaje('Error al enviar el archivo ', data.error, 'danger');
                    }
                    var $li = $("#container_"+contador);
                    var nombre_archivo = 'adjunto_'+contador+'_'+ <?php echo $flag_exploracion; ?> +'_'+ <?php echo $cod_exploracion; ?> + "." + ext_archivo;
                    var direccion_archivo = '../../mod_plantaciones/adjuntos/'+nombre_archivo;
                    update_list_item_interface_exploracion($li,nombre_archivo,contador);
                    $container_upload_box.find('.progress_bar').remove();
                    plan_guardar_adjunto_exploracion(<?php echo $flag_exploracion.','.$cod_exploracion; ?>,nombre_archivo);
                    plan_cargar_adjuntos_exploracion(<?php echo $flag_exploracion.','.$cod_exploracion; ?>, 'div_adjuntos_exploracion');
                    //plan_guardar_item_respuesta_formulario(codigo_plantacion,$('#cod_info_empresa').val(),$container_upload_box.data('cod_formulario'),$container_upload_box.data('cod_item'),$container_upload_box.data('cod_detalle_item'),nombre_archivo);
                },
                error: function(){
                    $container_upload_box.find('.progress_bar').remove();
                    grl_mensaje('Error al enviar el archivo, ', 'favor intentarlo mas tarde', 'danger');                
                },
            }); //fin ajax
        }
        else{
            grl_mensaje('Favor seleccione un archivo.', '', 'warning');
            $container_upload_box.find('progress_bar').remove();
        }
    }//fin de subir_archivo ()
</script>
<?php
if (count($ADJUNTOS)) {
	?>
    <div class="row">
        <div class="col-md-12"><h2 class="translate" data-traducir_english="Attachments" data-traducir_spanish="Adjuntos">Adjuntos</h2></div>
    </div>

    <?php
    foreach ($ADJUNTOS as $adjunto)
    {        
        ?>
        <div class="row margin-bottom-5" id="container_<?php echo $adjunto['cod_adjunto']; ?>">
            <div class="col-sm-12">
                <div class="container-upload-box">
                    <button class="smooth-transition btn-select-file adjunto_exploracion disabled" data-id="<?php echo $adjunto['cod_adjunto']; ?>" data-disabled="true">
                        <i class="icon-file fa fa-file"> </i>
                        <span class="msj-btn-file" id="msj-btn-file-select-<?php echo $adjunto['cod_adjunto']; ?>"> Seleccione Archivo </span>
                    </button>
                    <input type="file" accept=".avi,.mpeg,.mpg,.wmv,.3gp,.mp4,.mp3,.ogg,.m4a,.wav,.xls,.xlsx,.ppt,.pptx,.pps,.ppsx,.pdf,.doc,.docx,.jpg,.png,.jpeg,.bmp" id="archivo-<?php echo $adjunto['cod_adjunto']; ?>" class="input-file-hidden" title="Seleccione Archivo...">
                    <a tabindex="0" class="smooth-transition btn-upload-file adjunto_exploracion disabled" data-id="<?php echo $adjunto['cod_adjunto']; ?>" data-disabled="true">
                        <i class="fa fa-cloud-upload"> </i>
                    </a>
                </div>
            </div>
        </div>
        <?php
        echo '<script type="text/javascript">'
            , 'array_file_list_exploracion.push({id:'.$adjunto['cod_adjunto'].',file:"",file_ext:""});'
            , 'var $li = $("#container_'.$adjunto['cod_adjunto'].'");'
            , '$li.find(\'.btn-select-file\').removeClass().addClass(\'smooth-transition btn-select-file adjunto_exploracion with-attachment\').data(\'disabled\',false);'
            , '$li.find(\'i.icon-file\').addClass(\'hide\');'
            , '$li.find(\'span.msj-btn-file\').text("'.$adjunto['adjunto'].'");'
            , '$li.find(\'.btn-upload-file\').addClass(\'hide\').data(\'disabled\',true);'
            , '$li.find(".container-upload-box").append(\'<a tabindex="0" target="_blank" href="../mod_plantaciones/adjuntos/'.$adjunto['adjunto'].'" class="smooth-transition btn-view-file btn-finalizar"><i class="fa fa-eye"></i></a>\');'
            //, '$li.find(".container-upload-box").append(\'<a tabindex="0" target="_blank" onClick="cur_borrar_adjunto(',$cod_curso,',',$cod_maestro_curso,',',$cod_modulo,',',$cod_usuario,',',$orden,',',$cod_tipo_adjunto,',',$CONTRATOS[$orden]['nombre_archivo'],')" class="smooth-transition btn-view-file-delete"><i class="fa fa-trash"></i></a>\');'
            , '$li.find(".container-upload-box").append(\'<div style="width:30px;display: table-cell;" ><button type="button" class="smooth-transition btn_delete_file'.$adjunto['cod_adjunto'].' btn-view-file-delete btn-finalizar" data-value=""><i class="far fa-trash-alt"></i></button></div>\');'
            , '$(".btn_delete_file'.$adjunto['cod_adjunto'].'").on("click",function(){'
                , 'plan_borrar_adjunto_exploracion('.$flag_exploracion.','.$cod_exploracion.','.$adjunto['cod_adjunto'].');'
                , 'update_list_item_interface_exploracion2($("#container_'.$adjunto['cod_adjunto'].'"),'.$adjunto['cod_adjunto'].');'
            , '});'
            , '</script>'
            //',$cod_curso,',',$cod_maestro_curso,',',$cod_modulo,',',$cod_usuario,',',$orden,',',$cod_tipo_adjunto,',',$CONTRATOS[$orden]['nombre_archivo'],'
        ;
        $contador = $adjunto['cod_adjunto'];
    }	
    $contador++;
    
}
?>
<div class="row margin-bottom-5 btn-finalizar" id="container_<?php echo $contador; ?>">
    <div class="col-sm-12">
        <div class="container-upload-box">
            <button class="smooth-transition btn-select-file adjunto_exploracion disabled" data-id="<?php echo $contador; ?>" data-disabled="true">
                <i class="icon-file fa fa-file"> </i>
                <span class="msj-btn-file" id="msj-btn-file-select-<?php echo $contador; ?>"> Seleccione Archivo </span>
            </button>
            <input type="file" accept=".avi,.mpeg,.mpg,.wmv,.3gp,.mp4,.mp3,.ogg,.m4a,.wav,.xls,.xlsx,.ppt,.pptx,.pps,.ppsx,.pdf,.doc,.docx,.jpg,.png,.jpeg,.bmp" id="archivo-<?php echo $contador; ?>" class="input-file-hidden" title="Seleccione Archivo...">
            <a tabindex="0" class="smooth-transition btn-upload-file adjunto_exploracion disabled" data-id="<?php echo $contador; ?>" data-disabled="true">
                <i class="fa fa-cloud-upload"> </i>
            </a>
        </div>
    </div>
</div>
<?php
echo '<script type="text/javascript">'
    , 'array_file_list_exploracion.push({id:'.$contador.',file:"",file_ext:""});'
    , 'var $li = $("#container_'.$contador.'");'
    , '$li.find(\'.btn-select-file\').removeClass().addClass(\'smooth-transition btn-select-file adjunto_exploracion\').data(\'disabled\',false);'
    , '$li.find(\'i.icon-file\').removeClass(\'hide\');'
    , '$li.find(\'span.msj-btn-file\').text(\' Seleccione archivo\');'
    , '$li.find(".btn-upload-file").removeClass().addClass(\'smooth-transition btn-upload-file adjunto_exploracion\').data(\'disabled\',true);'
    , '$li.find(".btn-view-file").remove();'
    , 'cod_adjunto = '.$contador.';'
    /*, 'estilo_file_input_material_apoyo('.$contador.');'*/
    , '</script>'
;
?>