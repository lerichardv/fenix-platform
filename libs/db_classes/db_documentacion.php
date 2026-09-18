<?PHP

/*
* Clase para uso del módulo de documentación
* @author      Dan Urquía
* @date        2017-06-22
 */

class db_documentacion{
	public $db_conexion;
	function __construct(){
		$this->db_conexion = new db_lion();
		$this->db_conexion = $this->db_conexion->dbConnect();
	}

	/*
	 * Trae el listado de todos los tipos de documentos actualmente activos en la bd.
	 */
	function get_tipo_documento_activos(){
		//Se crea una variable que contendra la consulta a la BD
		$SQL ="SELECT
					    cod_tipo_documento,
							tipo_documento,
							descripcion,
							color_tipo
					FROM
					    doc_tipo_documentos
					WHERE
						activo = 1";
		//Se prepara el statement
	  $stmt = $this->db_conexion->prepare($SQL);
		//Se ejecuta la consulta por medio de un try-catch
		try{
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			$resultado = $e->getMessage();
		}
		//Se cierra el statement
		$stmt->closeCursor();
		//Se regresa el estado de la consulta
		return $resultado;
	}

	/*
	 * Envía los parametros ingresados para ser registrados en el repositorio de documentos.
	 */
	function send_insertar_nuevo_documento($cod_gerencia,
                                         $cod_tipo_documento,
                                         $titulo_documento,
                                         $descripcion,
                                         $palabras_claves,
                                         $nombre_PDF,
                                         $user_insert,
																				 $flag_update,
																				 $cod_documento){
		//Se crea una variable que contendra la consulta a la BD
		$SQL = "CALL doc_ingreso_nuevo_documento(:cod_gerencia,
																						 :cod_tipo_documento,
																						 :titulo_documento,
																						 :descripcion,
																						 :palabras_claves,
																						 :nombre_PDF,
																						 :user_insert,
																						 :flag_update,
																						 :cod_documento)";
		//Se prepara el statement
		$stmt = $this->db_conexion->prepare($SQL);
		//Se intrelazan los parametros
		$stmt->bindParam(":cod_gerencia",$cod_gerencia);
		$stmt->bindParam(":cod_tipo_documento",$cod_tipo_documento);
		$stmt->bindParam(":titulo_documento",$titulo_documento);
		$stmt->bindParam(":descripcion",$descripcion);
		$stmt->bindParam(":palabras_claves",$palabras_claves);
		$stmt->bindParam(":nombre_PDF",$nombre_PDF);
		$stmt->bindParam(":user_insert",$user_insert);
		$stmt->bindParam(":flag_update",$flag_update);
		$stmt->bindParam(":cod_documento",$cod_documento);
		//Se ejecuta la consulta por medio de un try-catch
		try{
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			$resultado = $e->getMessage();
		}
		//Se cierra el statement
		$stmt->closeCursor();
		//Se regresa el estado de la consulta
		return $resultado;
	}

	/*
	 * Obtiene todos los documentos en repositorio segun parametros preestablecidos.
	 */
	function get_documentos_por_parametros($arr_cod_gerencias,
																				 $arr_cod_tipo_documetos,
																				 $parametros){
		//Se crea una variable que contendra la consulta a la BD
		$SQL = "CALL doc_buscar_documento(:arr_cod_gerencias, :arr_cod_tipo_documetos, :parametros)";
		//Se prepara el statement
		$stmt = $this->db_conexion->prepare($SQL);
		//Se intrelazan los parametros
		$stmt->bindParam(":arr_cod_gerencias", $arr_cod_gerencias);
		$stmt->bindParam(":arr_cod_tipo_documetos", $arr_cod_tipo_documetos);
		$stmt->bindParam(":parametros", $parametros);
		//Se ejecuta la consulta por medio de un try-catch
		try{
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			$resultado = $e->getMessage();
		}
		//Se cierra el statement
		$stmt->closeCursor();
		//Se regresa el estado de la consulta
		return $resultado;
	}

	/*
	 * Genera de forma dinamica el total de cada uno de los tipos de documentos que se han encontrado segun parametros enviados.
	 */
	function get_total_tipo_documento(){
		//Se crea una variable que contendra la consulta a la BD
		$SQL = "CALL doc_contador_total_tipo_documentos();";
		//Se prepara el statement
		$stmt = $this->db_conexion->prepare($SQL);
		//Se ejecuta la consulta por medio de un try-catch
		try{
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			$resultado = $e->getMessage();
		}
		//Se cierra el statement
		$stmt->closeCursor();
		//Se regresa el estado de la consulta
		return $resultado;
	}

	/*
	 * Obtiene todos los documentos en repositorio segun parametros preestablecidos para autocomplete.
	 */
	function get_documentos_autocomplete($parametro){
		//Se crea una variable que contendra la consulta a la BD
		$SQL = "SELECT
						    doc_documentos_repositorio.cod_documento,
						    doc_documentos_repositorio.cod_gerencia,
								usu_gerencias.gerencia,
						    doc_documentos_repositorio.cod_tipo_documento,
						    doc_tipo_documentos.tipo_documento,
						    doc_documentos_repositorio.titulo_documento,
						    doc_documentos_repositorio.descripcion,
						    doc_documentos_repositorio.palabras_claves
						FROM
						    doc_documentos_repositorio
						INNER JOIN
						    usu_gerencias ON (usu_gerencias.cod_gerencia = doc_documentos_repositorio.cod_gerencia)
						INNER JOIN
						    doc_tipo_documentos ON (doc_tipo_documentos.cod_tipo_documento = doc_documentos_repositorio.cod_tipo_documento)
						WHERE
							doc_documentos_repositorio.titulo_documento REGEXP :parametro
						OR
							doc_documentos_repositorio.descripcion REGEXP :parametro
						OR
							doc_documentos_repositorio.palabras_claves REGEXP :parametro
						OR
							usu_gerencias.gerencia REGEXP :parametro
						OR
							doc_tipo_documentos.tipo_documento REGEXP :parametro
						ORDER BY
							doc_documentos_repositorio.titulo_documento ASC";
		//Se prepara el statement
		$stmt = $this->db_conexion->prepare($SQL);
		//Se intrelazan los parametros
		$stmt->bindParam(":parametro", $parametro);
		//Se ejecuta la consulta por medio de un try-catch
		try{
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			$resultado = $e->getMessage();
		}
		//Se cierra el statement
		$stmt->closeCursor();
		//Se regresa el estado de la consulta
		return $resultado;
	}

	/*
	 * Obtiene todos el documento en repositorio segun el parametro enviado.
	 */
	function get_info_documento($cod_documento){
		//Se crea una variable que contendra la consulta a la BD
		$SQL = "SELECT
						    doc_documentos_repositorio.cod_documento,
						    doc_documentos_repositorio.cod_gerencia,
						    doc_documentos_repositorio.cod_tipo_documento,
						    doc_documentos_repositorio.titulo_documento,
						    doc_documentos_repositorio.descripcion,
						    doc_documentos_repositorio.palabras_claves,
						    doc_documentos_repositorio.archivo_PDF,
						    doc_documentos_repositorio.contador_descargas
						FROM
						    doc_documentos_repositorio
						WHERE
							doc_documentos_repositorio.cod_documento = :cod_documento";
		//Se prepara el statement
		$stmt = $this->db_conexion->prepare($SQL);
		//Se intrelazan los parametros
		$stmt->bindParam(":cod_documento", $cod_documento);
		//Se ejecuta la consulta por medio de un try-catch
		try{
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			$resultado = $e->getMessage();
		}
		//Se cierra el statement
		$stmt->closeCursor();
		//Se regresa el estado de la consulta
		return $resultado;
	}

	/*
	 * Obtiene todos el documento en repositorio segun el parametro enviado.
	 */
	function send_insertar_historial_descarga($cod_documento,
                                            $cod_tipo_dispositivo,
                                            $user_insert){
		//Se crea una variable que contendra la consulta a la BD
		$SQL = "INSERT INTO doc_historial_descargas
							(cod_documento,
							cod_tipo_dispositivo,
							user_insert)
						VALUES
							(:cod_documento,
							:cod_tipo_dispositivo,
							:user_insert) ";
		//Se prepara el statement
		$stmt = $this->db_conexion->prepare($SQL);
		//Se intrelazan los parametros
		$stmt->bindParam(":cod_documento", $cod_documento);
		$stmt->bindParam(":cod_tipo_dispositivo", $cod_tipo_dispositivo);
		$stmt->bindParam(":user_insert", $user_insert);
		//Se ejecuta la consulta por medio de un try-catch
		try{
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			$resultado = $e->getMessage();
		}
		//Se cierra el statement
		$stmt->closeCursor();
		//Se regresa el estado de la consulta
		return $resultado;
	}

}// Fin de la clase
?>
