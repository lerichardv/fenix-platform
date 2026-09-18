<?PHP
/*
 * Ventana modal en donde se mira la noticia seleccionada.
 * @author      Dan Urquía
 * @date        2014-10-10 
 */
 
session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php"); 
include_once("../../libs/db_classes/db_general.php"); 
/*INSTANCIAMIENTOS*/
$DB_GENERAL     = new db_general();
$cod_noticia    = $_GET['x1'];
$NOTICIA        = $DB_GENERAL->get_noticia_por_codigo($cod_noticia);
?><head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<div class="row">                      	
  <div class="col-3 col-sm-3 col-lg-3" align="center">
    
  </div>
  <div class="col-9 col-sm-9 col-lg-9" align="justify">
    <span class="label label-success">Start <?PHP echo utf8_encode($NOTICIA[0]['fecha_inicio']);?></span>
    <span class="label label-danger">End <?PHP echo utf8_encode($NOTICIA[0]['fecha_fin']);?></span>
  </div>
</div>
</br>
<div class="row">
  <div class="col-3 col-sm-3 col-lg-3" align="center">
    <img src="<?PHP echo $NOTICIA[0]['imagen']; ?>" onerror="this.src='../../libs/imgs/Logo.png';" width="200" height="200" class="img-circle img-responsive">
  </div>
  <div class="col-9 col-sm-9 col-lg-9">
    <p class="text-justify"><?PHP echo utf8_encode($NOTICIA[0]['contenido']); ?></p>
  </div>
</div>
</br>