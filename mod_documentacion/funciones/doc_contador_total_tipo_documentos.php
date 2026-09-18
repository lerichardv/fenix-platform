<?php
/*
* 	Genera de forma dinamica el total de cada uno de los tipos de documentos que se han encontrado segun parametros enviados
* 	@author 	Dan Urquía
* 	@date 		2017-06-27


session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}*/
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_documentacion.php");
/*INSTANCIAMIENTOS*/
$DB_DOCUMENTOS = new db_documentacion();
$DOCUMENTOS    = $DB_DOCUMENTOS->get_total_tipo_documento();
echo 123;
echo var_dump($DOCUMENTOS);
$totales = 0;
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
if(count($DOCUMENTOS) > 0){
	foreach($DOCUMENTOS as $DOCUMENTO){
		echo '</i><span class="badge" style="background-color:'.$DOCUMENTO['color_tipo'].';">'.$DOCUMENTO['tipo_documento']. ' '.$DOCUMENTO['total'].'</span> ';
		$totales += $DOCUMENTO['total'];
	}
	echo '</i><span class="badge" style="background-color:#1E1E1E;">Total '.$totales.'</span> ';
} else {
	echo '</i><span class="badge" style="background-color:#FF9696;">No se encontro información</span> ';
}
?>
