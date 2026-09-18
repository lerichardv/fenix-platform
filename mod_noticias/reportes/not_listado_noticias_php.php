<?PHP
/*
 * Listado de noticias en formato de excel
 * @author      Oscar Raudales
 * @date        2015-06-29 
 */
session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}

include_once("../../libs/db_classes/mysql_conn.php"); 
include_once("../../libs/db_classes/db_noticias.php");
/*INSTANCIAMIENTOS*/
$DB_NOTICIAS = new db_noticia();
$fecha_inicio          = $_POST['x1'];
$fecha_fin            = $_POST['x2'];
$cod_tipo_grupo_evento  = $_POST['x3'];
$cod_tipo_evento        = implode(',', $_POST['x4']) ;
$NOTICIAS   = $DB_NOTICIAS->get_noticias_por_fecha_tipo($fecha_inicio, $fecha_fin, $cod_tipo_grupo_evento, $cod_tipo_evento);
/****************************************** */
if(count($NOTICIAS) > 0){
    $cuerpo_tabla = '';
    foreach($NOTICIAS as $NOTICIA){									
       $cuerpo_tabla.='<tr> 
                    <td>'.$NOTICIA['cod_noticia'].'</td>
                    <td>'.$NOTICIA['fecha_inicio'].'</td>
                    <td>'.utf8_encode($NOTICIA['titulo']).'</td>
                    <td>'.utf8_encode($NOTICIA['tipo_evento']).'</td>
                    <td>'.utf8_encode($NOTICIA['unidad_academica']).'</td>
                    <td>'.utf8_encode($NOTICIA['lugar']).'</td>
                    <td>'.utf8_encode($NOTICIA['cantidad_participantes']).'</td>
                    <td>'.utf8_encode($NOTICIA['observaciones']).'</td>'
               . '</tr>'
               ;
	}
}
else{
    $cuerpo_tabla='No hay registros';
}
echo $cuerpo_tabla;
?>
