<?PHP
/*
 * Clase de funciones del módulo de Soporte
 * @author      Oscar Raudales
 * @date        2014-09-24
 */

class db_noticia{
	public $db_conexion;

	function __construct(){
		$this->db_conexion = new db_lion();
		$this->db_conexion = $this->db_conexion->dbConnect();
	}

	/*
	 * 	Inserta los mensajes enviados desde el módulo de Contáctenos.
	 *	$cod_usuario 		int código de usuario que envía el mensaje
	 *	$cod_tipo_mensaje  	int código del tipo de mensaje
	 *	$asunto 			varchar(60) indica el asunto del mensjae
	 *	$adjunto			varchar(200) indica la ruta de un archivo adjunto
	 *	$cuerpo				(TEXT) es el cuerpo del mensaje
	 *
	 */
	function send_insert_nueva_noticia(	$cod_usuario,
										$titulo,
										$adjunto,
										$noticia,
										$fecha_inicial,
										$fecha_final,
										$cod_info_empresa,
										$clase){
			$SQL=	"CALL not_nueva_noticia(
											:cod_usuario,
											:titulo,
											:adjunto,
											:noticia,
											:fecha_inicial,
											:fecha_final,
											:cod_info_empresa,
											:clase
											);";
			$stmt = $this->db_conexion->prepare($SQL);
			$stmt->bindParam(":cod_usuario",$cod_usuario);
			$stmt->bindParam(":titulo",$titulo);
			$stmt->bindParam(":adjunto",$adjunto);
			$stmt->bindParam(":noticia",$noticia);
			$stmt->bindParam(":fecha_inicial",$fecha_inicial);
			$stmt->bindParam(":fecha_final",$fecha_final);
			$stmt->bindParam(":cod_info_empresa",$cod_info_empresa);
			$stmt->bindParam(":clase",$clase);
			try{
				$stmt->execute();
				$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
			catch(PDOException $e)
			{
				$resultado = $e->getMessage();
			}
			$stmt->closeCursor();
			return $resultado;

	} //fin de la función
	/*
	 * Obtiene la noticia activa que se visualizara en el menú principal.
	 */
	function get_noticias(){
		$SQL = "SELECT
					ug_noticias.cod_noticia,
					ug_noticias.titulo,
					ug_noticias.contenido,
					ug_noticias.imagen,
					ug_noticias.fecha_insert,
					ug_noticias.activo
				FROM
					ug_noticias
				ORDER BY ug_noticias.cod_noticia DESC";
	    $stmt = $this->db_conexion->prepare($SQL);
		try{
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e)
		{
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*
	 * 	Inserta los mensajes enviados desde el módulo de Contáctenos.
	 *	$cod_noticia 	int código identificador de la noticia
	 *	$estado  		int define el estado de la nocicia: 1 para activo, 0 para inactivo
	 */
	function send_update_estado_noticia($cod_noticia,
										$estado){
			$SQL=	"CALL not_cambiar_estado_noticia(:cod_noticia, :estado);";
			$stmt = $this->db_conexion->prepare($SQL);
			$stmt->bindParam(":cod_noticia",$cod_noticia);
			$stmt->bindParam(":estado",$estado);
			try{
				$stmt->execute();
				$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
			catch(PDOException $e)
			{
				$resultado = $e->getMessage();
			}
			$stmt->closeCursor();
			return $resultado;

	} //fin de la función

} //Fin de la clase
?>



