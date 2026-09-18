
<?php
/*
 * Funciones para obtener información general de la base de datos.
 *
 * @author      Jairo Bonilla
 * @date        2018-10-25
 */

class db_inventario
{
	public $db_conexion;

	function __construct()
	{
		$this->db_conexion = new db_lion();
		$this->db_conexion = $this->db_conexion->dbConnect();
	}

	function habilitarGrupoFull()
	{
		// HABILITAMOS EL MODO "ONLY_FULL_GROUP_BY" PARA QUE TODA LA CONFIGURACIÓN DE LA BASE DE DATOS ESTE SEGURA
		$sql_mode = "SET sql_mode = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION'";
		$stmt = $this->db_conexion->prepare($sql_mode);
		try {
			$stmt->execute();
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			return $resultado;
		}
		$stmt->closeCursor();
	}

	function deshabilitarGrupoFull()
	{
		// DESHABILITAMOS EL MODO "ONLY_FULL_GROUP_BY" PARA PODER AGRUPAR ADECUADAMENTE LOS NOMRBES DE SEMILLAS
		$sql_mode = "SET sql_mode = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION'";
		$stmt = $this->db_conexion->prepare($sql_mode);
		try {
			$stmt->execute();
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			return $resultado;
		}
		$stmt->closeCursor();
	}

	/*
	 * Funciones para obtener listado de variedades de semillas disponibles
	 */
	function inv_listado_variedad_semillas()
	{
		$SQL = "SELECT
					    cod_variedad, variedad_producto, activo
					FROM
					    bw_variedad_sembradora
					ORDER BY variedad_producto ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener información de una granaja
	 */
	function inv_obtener_info_variedad_semilla($cod_variedad)
	{
		$SQL = "SELECT
					    cod_variedad, variedad_producto, activo
					FROM
					    bw_variedad_sembradora
				WHERE
				    bw_variedad_sembradora.cod_variedad = :cod_variedad;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_variedad",  $cod_variedad);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Guarda una variedad de producto
      */
	function inv_guardar_variedad_producto(
		$codigo_variedad,
		$variedad_producto,
		$user_insert
	) {
		$SQL = "CALL inv_guardar_variedad_producto(:codigo_variedad,
										:variedad_producto,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_variedad",  $codigo_variedad);
		$stmt->bindParam(":variedad_producto",  $variedad_producto);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Cambia el flag de activo de las variedades de producto
      */
	function inv_cambiar_estado_variedad_producto(
		$cod_variedad,
		$flag_activo,
		$user_insert
	) {
		$SQL = "CALL inv_cambiar_estado_variedad_producto(:cod_variedad,
										:flag_activo,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_variedad",  $cod_variedad);
		$stmt->bindParam(":flag_activo",  $flag_activo);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*------------------------------------------------------------*/
	/*
	 * Funciones para obtener listado de tipos de quimicos disponibles
	 */
	function inv_listado_tipo_quimicos()
	{
		$SQL = "SELECT
					    cod_tipo_quimico, tipo_quimico, activo
					FROM
					    bw_tipo_quimico
					ORDER BY tipo_quimico ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener información de un tipo de quimico
	 */
	function inv_obtener_info_tipo_quimico($cod_tipo_quimico)
	{
		$SQL = "SELECT
				    cod_tipo_quimico, tipo_quimico, activo
				FROM
				    bw_tipo_quimico
				WHERE
				    cod_tipo_quimico = :cod_tipo_quimico;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_tipo_quimico",  $cod_tipo_quimico);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Guarda un tipo de quimico
      */
	function inv_guardar_tipo_quimico(
		$codigo_tipo_quimico,
		$tipo_quimico,
		$user_insert
	) {
		$SQL = "CALL inv_guardar_tipo_quimico(:codigo_tipo_quimico,
										:tipo_quimico,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_tipo_quimico",  $codigo_tipo_quimico);
		$stmt->bindParam(":tipo_quimico",  $tipo_quimico);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Cambia el flag de activo de las variedades de producto
      */
	function inv_cambiar_estado_tipo_quimico(
		$cod_tipo_quimico,
		$flag_activo,
		$user_insert
	) {
		$SQL = "CALL inv_cambiar_estado_tipo_quimico(:cod_tipo_quimico,
										:flag_activo,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_tipo_quimico",  $cod_tipo_quimico);
		$stmt->bindParam(":flag_activo",  $flag_activo);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*------------------------------------------------------------*/
	/*
	 * Funciones para obtener listado de ingredientes activos disponibles
	 */
	function inv_listado_ingredientes_activos()
	{
		$SQL = "SELECT
					    cod_ingrediente_activo, ingrediente_activo, activo
					FROM
					    bw_ingredientes_activos
					ORDER BY ingrediente_activo ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener información de un ingrediente activo
	 */
	function inv_obtener_info_ingrediente_activo($cod_ingrediente)
	{
		$SQL = "SELECT
				    cod_ingrediente_activo, ingrediente_activo, activo
				FROM
				    bw_ingredientes_activos
				WHERE
				    cod_ingrediente_activo = :cod_ingrediente;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_ingrediente",  $cod_ingrediente);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Guarda un tipo de quimico
      */
	function inv_guardar_ingrediente_activo(
		$codigo_ingrediente,
		$ingrediente_activo,
		$user_insert
	) {
		$SQL = "CALL inv_guardar_ingrediente_activo(:codigo_ingrediente,
										:ingrediente_activo,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_ingrediente",  $codigo_ingrediente);
		$stmt->bindParam(":ingrediente_activo",  $ingrediente_activo);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Cambia el flag de activo de las variedades de producto
      */
	function inv_cambiar_estado_ingrediente_activo(
		$cod_ingrediente_activo,
		$flag_activo,
		$user_insert
	) {
		$SQL = "CALL inv_cambiar_estado_ingrediente_activo(:cod_ingrediente_activo,
										:flag_activo,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_ingrediente_activo",  $cod_ingrediente_activo);
		$stmt->bindParam(":flag_activo",  $flag_activo);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*------------------------------------------------------------*/
	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_listado_semillas()
	{
		$SQL = "SELECT
					    bw_inventario_semilla.cod_inventario,
					    bw_inventario_semilla.cod_info_empresa,
					    bw_info_empresa.nombre_empresa,
					    bw_inventario_semilla.codigo_semilla,
					    bw_inventario_semilla.nombre_semilla,
					    bw_inventario_semilla.cod_variedad,
					    bw_variedad_sembradora.variedad_producto,
					    bw_inventario_semilla.abreviatura_semilla,
					    bw_inventario_semilla.cantidad_semilla,
					    bw_inventario_semilla.cod_unidad_medida,
					    ug_unidades_medida.unidad_medida,
					    bw_inventario_semilla.cantidad_fisica_semilla,
					    bw_inventario_semilla.precio_unidad,
					    bw_inventario_semilla.numero_lote,
					    bw_inventario_semilla.activo
					FROM
					    bw_inventario_semilla
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_semilla.cod_info_empresa)
					        INNER JOIN
					    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_semilla.cod_unidad_medida)
					        INNER JOIN
					    bw_variedad_sembradora ON (bw_variedad_sembradora.cod_variedad = bw_inventario_semilla.cod_variedad)
					ORDER BY bw_inventario_semilla.nombre_semilla ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_listado_semillas_por_granjas($cod_info_empresa)
	{
		$this->deshabilitarGrupoFull();
		$SQL = "SELECT
					bw_inventario_semilla.cod_inventario,
					bw_inventario_semilla.cod_info_empresa,
					bw_inventario_semilla.codigo_semilla,
					bw_inventario_semilla.nombre_semilla,
					bw_inventario_semilla.cod_variedad,
					bw_inventario_semilla.abreviatura_semilla,
					bw_inventario_semilla.cantidad_semilla,
					bw_inventario_semilla.cod_unidad_medida,
					bw_inventario_semilla.cantidad_fisica_semilla,
					bw_inventario_semilla.precio_unidad,
					bw_inventario_semilla.numero_lote,
					bw_inventario_semilla.cod_categoria,
					bw_inventario_semilla.cod_grupo_siembra,
					bw_inventario_semilla.cod_rasgo,
					bw_inventario_semilla.plants_acre,
					bw_inventario_semilla.cod_familia,
					bw_inventario_semilla.red_zone,
					bw_inventario_semilla.over_seed,
					bw_inventario_semilla.semillas_por_plantaciones,
					bw_inventario_semilla.paletizado,
					bw_inventario_semilla.semilla_activa,
					bw_inventario_semilla.activo,
					bw_info_empresa.nombre_empresa,
					bw_variedad_sembradora.variedad_producto,
					ug_unidades_medida.unidad_medida,
					bw_inventario_semillas_por_empresas.cod_inventario_semilla,
					bw_inventario_semillas_por_empresas.cantidad_semilla as cantidad_semillas_individual,
					SUM(bw_inventario_semillas_por_empresas.cantidad_semilla) as suma_cantidad_semillas_individual,
					GROUP_CONCAT(
						CONCAT(
							'',
							bw_info_empresa.nombre_empresa,
							' - ',
							FORMAT(bw_inventario_semillas_por_empresas.cantidad_semilla,0,'es_MX')
						),'</br>' SEPARATOR ''
					) AS nombres_empresas_vinculadas
				FROM
					bw_inventario_semilla
					INNER JOIN
					bw_inventario_semillas_por_empresas ON (bw_inventario_semillas_por_empresas.cod_inventario_semilla = bw_inventario_semilla.cod_inventario)
						INNER JOIN
					bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_semillas_por_empresas.cod_empresa)
						INNER JOIN
					ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_semilla.cod_unidad_medida)
				
						INNER JOIN
					bw_variedad_sembradora ON (bw_variedad_sembradora.cod_variedad = bw_inventario_semilla.cod_variedad)
				WHERE
					bw_inventario_semilla.cod_info_empresa IN(" . $cod_info_empresa . ")
				GROUP BY
					bw_inventario_semillas_por_empresas.cod_inventario_semilla
				ORDER BY bw_inventario_semilla.nombre_semilla ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();

		$this->habilitarGrupoFull();

		return $resultado;
	}

	/*
	 * Funciones para obtener listado bitacora de semillas activos disponibles
	 */
	function inv_listado_semillas_bitacora_por_granjas($cod_inventario)
	{
		$SQL = "SELECT
					    bw_inventario_semilla_bitacora.cod_inventario,
					    bw_inventario_semilla_bitacora.cod_info_empresa,
					    bw_info_empresa.nombre_empresa,
					    bw_inventario_semilla_bitacora.codigo_semilla,
					    bw_inventario_semilla_bitacora.nombre_semilla,
					    bw_inventario_semilla_bitacora.cod_variedad,
					    bw_variedad_sembradora.variedad_producto,
					    bw_inventario_semilla_bitacora.abreviatura_semilla,
					    bw_inventario_semilla_bitacora.cantidad_semilla,
					    bw_inventario_semilla_bitacora.cod_unidad_medida,
					    ug_unidades_medida.unidad_medida,
					    bw_inventario_semilla_bitacora.cantidad_fisica_semilla,
					    bw_inventario_semilla_bitacora.precio_unidad,
					    bw_inventario_semilla_bitacora.numero_lote,
					    bw_inventario_semilla_bitacora.activo
					FROM
					    bw_inventario_semilla_bitacora
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_semilla_bitacora.cod_info_empresa)
					        INNER JOIN
					    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_semilla_bitacora.cod_unidad_medida)
					        INNER JOIN
					    bw_variedad_sembradora ON (bw_variedad_sembradora.cod_variedad = bw_inventario_semilla_bitacora.cod_variedad)
					    WHERE
					    bw_inventario_semilla_bitacora.cod_inventario = " . $cod_inventario . "
					ORDER BY bw_inventario_semilla_bitacora.date_insert DESC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_total_semillas_por_granjas($cod_info_empresa)
	{
		$SQL = "SELECT
						COUNT(bw_inventario_semilla.cod_inventario) as total
					FROM
						bw_inventario_semilla
							INNER JOIN
						bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_semilla.cod_info_empresa)
							INNER JOIN
						ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_semilla.cod_unidad_medida)
							INNER JOIN
						bw_variedad_sembradora ON (bw_variedad_sembradora.cod_variedad = bw_inventario_semilla.cod_variedad)
					WHERE
						bw_inventario_semilla.cod_info_empresa IN(" . $cod_info_empresa . ")
					ORDER BY bw_inventario_semilla.date_insert DESC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_listado_semillas_por_granjas_paginacion($cod_info_empresa, $inicio, $limite)
	{
		$SQL = "SELECT
					    bw_inventario_semilla.cod_inventario,
					    bw_inventario_semilla.cod_info_empresa,
					    bw_info_empresa.nombre_empresa,
					    bw_inventario_semilla.codigo_semilla,
					    bw_inventario_semilla.nombre_semilla,
					    bw_inventario_semilla.cod_variedad,
					    bw_variedad_sembradora.variedad_producto,
					    bw_inventario_semilla.abreviatura_semilla,
					    bw_inventario_semilla.cantidad_semilla,
					    bw_inventario_semilla.cod_unidad_medida,
					    ug_unidades_medida.unidad_medida,
					    bw_inventario_semilla.cantidad_fisica_semilla,
					    bw_inventario_semilla.precio_unidad,
					    bw_inventario_semilla.numero_lote,
					    bw_inventario_semilla.activo
					FROM
					    bw_inventario_semilla
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_semilla.cod_info_empresa)
					        INNER JOIN
					    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_semilla.cod_unidad_medida)
					        INNER JOIN
					    bw_variedad_sembradora ON (bw_variedad_sembradora.cod_variedad = bw_inventario_semilla.cod_variedad)
					    WHERE
					    bw_inventario_semilla.cod_info_empresa IN(" . $cod_info_empresa . ")
					ORDER BY bw_inventario_semilla.date_insert DESC
					LIMIT " . $inicio . " , " . $limite . ";";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_listado_semillas_por_finca($cod_info_empresa)
	{
		$SQL = "SELECT
					    bw_inventario_semilla.cod_inventario,
					    bw_inventario_semilla.cod_info_empresa,
					    bw_info_empresa.nombre_empresa,
					    bw_inventario_semilla.codigo_semilla,
					    bw_inventario_semilla.nombre_semilla,
					    bw_inventario_semilla.cod_variedad,
					    bw_variedad_sembradora.variedad_producto,
					    bw_inventario_semilla.abreviatura_semilla,
					    bw_inventario_semilla.cantidad_semilla,
					    bw_inventario_semilla.cod_unidad_medida,
					    ug_unidades_medida.unidad_medida,
					    bw_inventario_semilla.cantidad_fisica_semilla,
					    bw_inventario_semilla.precio_unidad,
					    bw_inventario_semilla.numero_lote,
					    bw_inventario_semilla.activo
					FROM
					    bw_inventario_semilla
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_semilla.cod_info_empresa)
					        INNER JOIN
					    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_semilla.cod_unidad_medida)
					        INNER JOIN
					    bw_variedad_sembradora ON (bw_variedad_sembradora.cod_variedad = bw_inventario_semilla.cod_variedad)
							INNER JOIN
						bw_inventario_semillas_por_empresas ON (bw_inventario_semillas_por_empresas.cod_inventario_semilla = bw_inventario_semilla.cod_inventario)
					    WHERE bw_inventario_semillas_por_empresas.cod_empresa = :cod_info_empresa
					ORDER BY bw_inventario_semilla.nombre_semilla ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener información de una semilla
	 */
	function inv_obtener_info_inventario_semilla($cod_inventario)
	{
		$this->deshabilitarGrupoFull();

		$SQL = "SELECT
				    bw_inventario_semilla.cod_inventario,
				    bw_inventario_semilla.cod_info_empresa,
				    bw_inventario_semilla.codigo_semilla,
				    bw_inventario_semilla.nombre_semilla,
				    bw_inventario_semilla.cod_variedad,
				    bw_inventario_semilla.abreviatura_semilla,
				    bw_inventario_semilla.cantidad_semilla,
				    bw_inventario_semilla.cod_unidad_medida,
				    bw_inventario_semilla.cantidad_fisica_semilla,
				    bw_inventario_semilla.precio_unidad,
					bw_inventario_semilla.numero_lote,
					bw_inventario_semilla.flag_watercress,

					bw_inventario_semilla.cod_categoria,
					bw_inventario_semilla.cod_grupo_siembra,
					bw_inventario_semilla.cod_rasgo,
					bw_inventario_semilla.plants_acre,
					bw_inventario_semilla.cod_familia,
					bw_inventario_semilla.red_zone,
					bw_inventario_semilla.over_seed,
					bw_inventario_semilla.semillas_por_plantaciones,
					bw_inventario_semilla.paletizado,
					bw_inventario_semilla.semilla_activa,
					bw_inventario_semilla.notas,
					bw_inventario_semilla.og_supply,
					bw_inventario_semilla.cod_vendedores,
					bw_inventario_semilla.germinacion_automatica,
					bw_inventario_semilla.cod_tipo_semilla,

					bw_inventario_semilla.activo,
				    bw_info_empresa.nombre_empresa,
				    bw_variedad_sembradora.variedad_producto,
				    ug_unidades_medida.unidad_medida,
					SUM(bw_inventario_semillas_por_empresas.cantidad_semilla) as suma_cantidad_semillas_individual,
					GROUP_CONCAT(bw_inventario_semillas_por_empresas.cod_empresa) AS cod_empresa_asociados
				FROM
				    bw_inventario_semilla
				        INNER JOIN
				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_semilla.cod_info_empresa)
				        INNER JOIN
				    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_semilla.cod_unidad_medida)
				        INNER JOIN
				    bw_variedad_sembradora ON (bw_variedad_sembradora.cod_variedad = bw_inventario_semilla.cod_variedad)
						INNER JOIN
					bw_inventario_semillas_por_empresas ON (bw_inventario_semillas_por_empresas.cod_inventario_semilla = bw_inventario_semilla.cod_inventario)
				WHERE
					bw_inventario_semilla.cod_inventario = :cod_inventario
				GROUP BY
					bw_inventario_semillas_por_empresas.cod_inventario_semilla
				;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_inventario",  $cod_inventario);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();

		$this->habilitarGrupoFull();

		return $resultado;
	}

	/*
	 * Funciones para obtener información de una semilla
	 */
	function inv_obtener_info_invernaderos_asociados($cod_empresas, $cod_semilla)
	{

		$SQL = "SELECT
					bw_inventario_semillas_por_empresas.cod_inventario,
					bw_inventario_semillas_por_empresas.cantidad_semilla,
					bw_inventario_semillas_por_empresas.cod_empresa,
					bw_info_empresa.nombre_empresa
				FROM
					bw_inventario_semillas_por_empresas
					INNER JOIN
				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_semillas_por_empresas.cod_empresa)
				WHERE
				bw_inventario_semillas_por_empresas.cod_empresa IN (" . $cod_empresas . ") 
				AND bw_inventario_semillas_por_empresas.cod_inventario_semilla = :cod_semilla;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_semilla",  $cod_semilla);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener información de una semilla
	 */
	function inv_obtener_codigo_invernaderos_asociados($cod_semilla)
	{

		$SQL = "SELECT
					bw_inventario_semillas_por_empresas.cod_inventario,
					bw_inventario_semillas_por_empresas.cantidad_semilla,
					bw_inventario_semillas_por_empresas.cod_empresa
				FROM
					bw_inventario_semillas_por_empresas
				WHERE
					bw_inventario_semillas_por_empresas.cod_inventario_semilla = :cod_semilla;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_semilla",  $cod_semilla);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_eliminar_semillas_por_empresas(
		$cod_inventario

	) {
		$SQL = "DELETE FROM bw_inventario_semillas_por_empresas WHERE (cod_inventario = :cod_inventario);";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_inventario", $cod_inventario);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|It has been successfully removed.";
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Guarda un tipo de quimico
      */
	function inv_guardar_inventario_semilla(
		$codigo_inventario_semilla,
		$cod_info_empresa,
		$codigo_semilla,
		$nombre_semilla,
		$cod_variedad,
		$abreviatura_semilla,
		$cantidad_semilla,
		$cod_unidad_medida,
		$cantidad_fisica_semilla,
		$precio_unidad,
		$numero_lote,
		$flag_watercress,
		$cod_categoria,
		$cod_grupo_siembra,
		$cod_rasgo,
		$plants_acre,
		$cod_familia,
		$red_zone,
		$over_seed,
		$semillas_por_plantaciones,
		$paletizado,
		$semilla_activa,
		$notas,
		$og_supply,
		$cod_vendedores,
		$flag_germinacion_automatica,
		$cod_tipo_semilla,
		$user_insert
	) {
		$SQL = "CALL inv_guardar_inventario_semilla(:codigo_inventario_semilla,
										:cod_info_empresa,
										:codigo_semilla,
										:nombre_semilla,
										:cod_variedad,
										:abreviatura_semilla,
										:cantidad_semilla,
										:cod_unidad_medida,
										:cantidad_fisica_semilla,
										:precio_unidad,
										:numero_lote,
										:flag_watercress,
										:cod_categoria,
										:cod_grupo_siembra,
										:cod_rasgo,
										:plants_acre,
										:cod_familia,
										:red_zone,
										:over_seed,
										:semillas_por_plantaciones,
										:paletizado,
										:semilla_activa,
										:notas,
										:og_supply,
										:cod_vendedores,
										:flag_germinacion_automatica,
										:cod_tipo_semilla,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_inventario_semilla",  $codigo_inventario_semilla);
		$stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);
		$stmt->bindParam(":codigo_semilla",  $codigo_semilla);
		$stmt->bindParam(":nombre_semilla",  $nombre_semilla);
		$stmt->bindParam(":cod_variedad",  $cod_variedad);
		$stmt->bindParam(":abreviatura_semilla",  $abreviatura_semilla);
		$stmt->bindParam(":cantidad_semilla",  $cantidad_semilla);
		$stmt->bindParam(":cod_unidad_medida",  $cod_unidad_medida);
		$stmt->bindParam(":cantidad_fisica_semilla",  $cantidad_fisica_semilla);
		$stmt->bindParam(":precio_unidad",  $precio_unidad);
		$stmt->bindParam(":numero_lote",  $numero_lote);
		$stmt->bindParam(":flag_watercress",  $flag_watercress);

		$stmt->bindParam(":cod_categoria", $cod_categoria);
		$stmt->bindParam(":cod_grupo_siembra", $cod_grupo_siembra);
		$stmt->bindParam(":cod_rasgo", $cod_rasgo);
		$stmt->bindParam(":plants_acre", $plants_acre);
		$stmt->bindParam(":cod_familia", $cod_familia);
		$stmt->bindParam(":red_zone", $red_zone);
		$stmt->bindParam(":over_seed", $over_seed);
		$stmt->bindParam(":semillas_por_plantaciones", $semillas_por_plantaciones);
		$stmt->bindParam(":paletizado", $paletizado);
		$stmt->bindParam(":semilla_activa", $semilla_activa);
		$stmt->bindParam(":notas", $notas);
		$stmt->bindParam(":og_supply", $og_supply);
		$stmt->bindParam(":cod_vendedores", $cod_vendedores);
		$stmt->bindParam(":flag_germinacion_automatica", $flag_germinacion_automatica);
		$stmt->bindParam(":cod_tipo_semilla", $cod_tipo_semilla);

		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Cambia el flag de activo de las variedades de producto
      */
	function inv_cambiar_estado_inventario_semilla(
		$cod_ingrediente_activo,
		$flag_activo,
		$user_insert
	) {
		$SQL = "CALL inv_cambiar_estado_inventario_semilla(:cod_ingrediente_activo,
										:flag_activo,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_ingrediente_activo",  $cod_ingrediente_activo);
		$stmt->bindParam(":flag_activo",  $flag_activo);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*------------------------------------------------------------*/
	/*
	 * Funciones para obtener listado de unidades de medida activos disponibles
	 */
	function inv_listado_unidades_medida()
	{
		$SQL = "SELECT
					cod_unidad_medida, unidad_medida, abreviatura_medida, activo
				FROM
					ug_unidades_medida
				WHERE
					activo = 1
				ORDER BY unidad_medida ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener información de una unidad de medida
	 */
	function inv_obtener_info_unidad_medida($cod_unidad_medida)
	{
		$SQL = "SELECT
				    cod_unidad_medida, unidad_medida, abreviatura_medida, activo
				FROM
				    ug_unidades_medida
				WHERE cod_unidad_medida = :cod_unidad_medida;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_unidad_medida",  $cod_unidad_medida);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Guarda una unidad de medida
      */
	function inv_guardar_unidad_medida(
		$codigo_unidad_medida,
		$unidad_medida,
		$abreviatura_medida,
		$user_insert
	) {
		$SQL = "CALL inv_guardar_unidad_medida(:codigo_unidad_medida,
										:unidad_medida,
										:abreviatura_medida,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_unidad_medida",  $codigo_unidad_medida);
		$stmt->bindParam(":unidad_medida",  $unidad_medida);
		$stmt->bindParam(":abreviatura_medida",  $abreviatura_medida);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Cambia el flag de activo de las unidades de medida
      */
	function inv_cambiar_estado_unidad_medida(
		$cod_unidad_medida,
		$flag_activo,
		$user_insert
	) {
		$SQL = "CALL inv_cambiar_estado_unidad_medida(:cod_unidad_medida,
										:flag_activo,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_unidad_medida",  $cod_unidad_medida);
		$stmt->bindParam(":flag_activo",  $flag_activo);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*------------------------------------------------------------*/

	/*
	 * Funciones para obtener listado de tipos de periodos disponibles
	 */
	function inv_listado_tipo_periodos()
	{
		$SQL = "SELECT
					    cod_tipo_periodo, tipo_periodo, activo
					FROM
					    bw_tipos_periodos
					ORDER BY tipo_periodo ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener información de un tipo de periodo
	 */
	function inv_obtener_info_tipo_periodo($cod_tipo_periodo)
	{
		$SQL = "SELECT
				    cod_tipo_periodo, tipo_periodo, activo
				FROM
				    bw_tipos_periodos
				WHERE
				    cod_tipo_periodo = :cod_tipo_periodo;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_tipo_periodo",  $cod_tipo_periodo);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Guarda un tipo de periodo
      */
	function inv_guardar_tipo_periodo(
		$codigo_tipo_periodo,
		$tipo_periodo,
		$user_insert
	) {
		$SQL = "CALL inv_guardar_tipo_periodo(:codigo_tipo_periodo,
										:tipo_periodo,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_tipo_periodo",  $codigo_tipo_periodo);
		$stmt->bindParam(":tipo_periodo",  $tipo_periodo);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Cambia el flag de activo de los tipos de periodo
      */
	function inv_cambiar_estado_tipo_periodo(
		$cod_tipo_periodo,
		$flag_activo,
		$user_insert
	) {
		$SQL = "CALL inv_cambiar_estado_tipo_periodo(:cod_tipo_periodo,
										:flag_activo,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_tipo_periodo",  $cod_tipo_periodo);
		$stmt->bindParam(":flag_activo",  $flag_activo);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*------------------------------------------------------------*/
	/*
	 * Funciones para obtener listado de quimicos disponibles
	 */
	function inv_listado_quimicos()
	{
		$SQL = "SELECT
					    bw_inventario_quimicos.cod_inventario,
					    bw_inventario_quimicos.cod_info_empresa,
					    bw_info_empresa.nombre_empresa,
					    bw_inventario_quimicos.cod_quimico,
					    bw_inventario_quimicos.nombre_quimico,
					    bw_inventario_quimicos.cod_unidad_medida,
					    ug_unidades_medida.unidad_medida,
					    bw_inventario_quimicos.cantidad_quimico,
					    bw_inventario_quimicos.cantidad_fisica_quimico,
					    bw_inventario_quimicos.cod_ingrediente_activo,
					    bw_ingredientes_activos.ingrediente_activo,
					    bw_inventario_quimicos.registro_ambiental,
					    bw_inventario_quimicos.periodo_reingreso,
					    bw_inventario_quimicos.cod_tipo_periodo_reingreso,
					    reingreso.tipo_periodo,
					    bw_inventario_quimicos.periodo_precosecha,
					    bw_inventario_quimicos.cod_tipo_periodo_precosecha,
					    precosecha.tipo_periodo,
					    bw_inventario_quimicos.dosis_minima,
					    bw_inventario_quimicos.dosis_maxima,
					    bw_inventario_quimicos.cantidad_minima_alerta,
					    bw_inventario_quimicos.precio_quimico,
					    bw_inventario_quimicos.cod_tipo_quimico,
					    bw_tipo_quimico.tipo_quimico,
					    bw_inventario_quimicos.activo
					FROM
					    bw_inventario_quimicos
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_quimicos.cod_info_empresa)
					        INNER JOIN
					    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_quimicos.cod_unidad_medida)
					        INNER JOIN
					    bw_ingredientes_activos ON (bw_ingredientes_activos.cod_ingrediente_activo = bw_inventario_quimicos.cod_ingrediente_activo)
					        LEFT JOIN
					    bw_tipos_periodos precosecha ON (precosecha.cod_tipo_periodo = bw_inventario_quimicos.cod_tipo_periodo_precosecha)
					        LEFT JOIN
					    bw_tipos_periodos reingreso ON (reingreso.cod_tipo_periodo = bw_inventario_quimicos.cod_tipo_periodo_reingreso)
					        INNER JOIN
					    bw_tipo_quimico ON (bw_tipo_quimico.cod_tipo_quimico = bw_inventario_quimicos.cod_tipo_quimico)
					ORDER BY nombre_quimico ASC;";
		$stmt = $this->db_conexion->prepare($SQL);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de quimicos disponibles
	 */
	function inv_listado_quimicos_por_granjas($cod_info_empresa)
	{
		$SQL = "SELECT
					    bw_inventario_quimicos.cod_inventario,
					    bw_inventario_quimicos.cod_info_empresa,
					    bw_info_empresa.nombre_empresa,
					    bw_inventario_quimicos.cod_quimico,
					    bw_inventario_quimicos.nombre_quimico,
					    bw_inventario_quimicos.cod_unidad_medida,
					    ug_unidades_medida.unidad_medida,
					    bw_inventario_quimicos.cantidad_quimico,
					    bw_inventario_quimicos.cantidad_fisica_quimico,
					    bw_inventario_quimicos.cod_ingrediente_activo,
					    bw_ingredientes_activos.ingrediente_activo,
					    bw_inventario_quimicos.registro_ambiental,
					    bw_inventario_quimicos.periodo_reingreso,
					    bw_inventario_quimicos.cod_tipo_periodo_reingreso,
					    reingreso.tipo_periodo as tipo_periodo_reingreso,
					    bw_inventario_quimicos.periodo_precosecha,
					    bw_inventario_quimicos.cod_tipo_periodo_precosecha,
					    precosecha.tipo_periodo as tipo_periodo_precosecha,
					    bw_inventario_quimicos.dosis_minima,
					    bw_inventario_quimicos.dosis_maxima,
					    bw_inventario_quimicos.cantidad_minima_alerta,
					    bw_inventario_quimicos.precio_quimico,
					    bw_inventario_quimicos.cod_tipo_quimico,
					    bw_tipo_quimico.tipo_quimico,
					    bw_inventario_quimicos.activo,
					    bw_inventario_quimicos.razon_aplicacion,
					    bw_inventario_quimicos.etiqueta,
					    bw_inventario_quimicos.hoja_seguridad
					FROM
					    bw_inventario_quimicos
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_quimicos.cod_info_empresa)
					        INNER JOIN
					    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_quimicos.cod_unidad_medida)
					        INNER JOIN
					    bw_ingredientes_activos ON (bw_ingredientes_activos.cod_ingrediente_activo = bw_inventario_quimicos.cod_ingrediente_activo)
					        LEFT JOIN
					    bw_tipos_periodos precosecha ON (precosecha.cod_tipo_periodo = bw_inventario_quimicos.cod_tipo_periodo_precosecha)
					        LEFT JOIN
					    bw_tipos_periodos reingreso ON (reingreso.cod_tipo_periodo = bw_inventario_quimicos.cod_tipo_periodo_reingreso)
					        INNER JOIN
					    bw_tipo_quimico ON (bw_tipo_quimico.cod_tipo_quimico = bw_inventario_quimicos.cod_tipo_quimico)
					    	WHERE
					    bw_inventario_quimicos.cod_info_empresa IN (" . $cod_info_empresa . ")
					ORDER BY bw_inventario_quimicos.date_insert DESC;";
		$stmt = $this->db_conexion->prepare($SQL);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de quimicos disponibles
	 */
	function inv_listado_quimicos_por_granjas_paginacion($cod_info_empresa, $inicio, $limite)
	{
		$SQL = "SELECT
					    bw_inventario_quimicos.cod_inventario,
					    bw_inventario_quimicos.cod_info_empresa,
					    bw_info_empresa.nombre_empresa,
					    bw_inventario_quimicos.cod_quimico,
					    bw_inventario_quimicos.nombre_quimico,
					    bw_inventario_quimicos.cod_unidad_medida,
					    ug_unidades_medida.unidad_medida,
					    bw_inventario_quimicos.cantidad_quimico,
					    bw_inventario_quimicos.cantidad_fisica_quimico,
					    bw_inventario_quimicos.cod_ingrediente_activo,
					    bw_ingredientes_activos.ingrediente_activo,
					    bw_inventario_quimicos.registro_ambiental,
					    bw_inventario_quimicos.periodo_reingreso,
					    bw_inventario_quimicos.cod_tipo_periodo_reingreso,
					    reingreso.tipo_periodo,
					    bw_inventario_quimicos.periodo_precosecha,
					    bw_inventario_quimicos.cod_tipo_periodo_precosecha,
					    precosecha.tipo_periodo,
					    bw_inventario_quimicos.dosis_minima,
					    bw_inventario_quimicos.dosis_maxima,
					    bw_inventario_quimicos.cantidad_minima_alerta,
					    bw_inventario_quimicos.precio_quimico,
					    bw_inventario_quimicos.cod_tipo_quimico,
					    bw_tipo_quimico.tipo_quimico,
					    bw_inventario_quimicos.activo,
					    bw_inventario_quimicos.razon_aplicacion,
					    bw_inventario_quimicos.etiqueta,
					    bw_inventario_quimicos.hoja_seguridad
					FROM
					    bw_inventario_quimicos
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_quimicos.cod_info_empresa)
					        INNER JOIN
					    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_quimicos.cod_unidad_medida)
					        INNER JOIN
					    bw_ingredientes_activos ON (bw_ingredientes_activos.cod_ingrediente_activo = bw_inventario_quimicos.cod_ingrediente_activo)
					        LEFT JOIN
					    bw_tipos_periodos precosecha ON (precosecha.cod_tipo_periodo = bw_inventario_quimicos.cod_tipo_periodo_precosecha)
					        LEFT JOIN
					    bw_tipos_periodos reingreso ON (reingreso.cod_tipo_periodo = bw_inventario_quimicos.cod_tipo_periodo_reingreso)
					        INNER JOIN
					    bw_tipo_quimico ON (bw_tipo_quimico.cod_tipo_quimico = bw_inventario_quimicos.cod_tipo_quimico)
					    	WHERE
					    bw_inventario_quimicos.cod_info_empresa IN (" . $cod_info_empresa . ")
					ORDER BY bw_inventario_quimicos.date_insert DESC
					LIMIT " . $inicio . " , " . $limite . ";";
		$stmt = $this->db_conexion->prepare($SQL);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de quimicos disponibles
	 */
	function inv_total_quimicos_por_granjas($cod_info_empresa)
	{
		$SQL = "SELECT
					    COUNT(bw_inventario_quimicos.cod_inventario) as total
					FROM
					    bw_inventario_quimicos
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_quimicos.cod_info_empresa)
					        INNER JOIN
					    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_quimicos.cod_unidad_medida)
					        INNER JOIN
					    bw_ingredientes_activos ON (bw_ingredientes_activos.cod_ingrediente_activo = bw_inventario_quimicos.cod_ingrediente_activo)
					        LEFT JOIN
					    bw_tipos_periodos precosecha ON (precosecha.cod_tipo_periodo = bw_inventario_quimicos.cod_tipo_periodo_precosecha)
					        LEFT JOIN
					    bw_tipos_periodos reingreso ON (reingreso.cod_tipo_periodo = bw_inventario_quimicos.cod_tipo_periodo_reingreso)
					        INNER JOIN
					    bw_tipo_quimico ON (bw_tipo_quimico.cod_tipo_quimico = bw_inventario_quimicos.cod_tipo_quimico)
					    	WHERE
					    bw_inventario_quimicos.cod_info_empresa IN (" . $cod_info_empresa . ")
					ORDER BY bw_inventario_quimicos.date_insert DESC;";
		$stmt = $this->db_conexion->prepare($SQL);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de quimicos disponibles
	 */
	function inv_listado_quimicos_por_finca($cod_info_empresa)
	{
		$SQL = "SELECT
					    bw_inventario_quimicos.cod_inventario,
					    bw_inventario_quimicos.cod_info_empresa,
					    bw_info_empresa.nombre_empresa,
					    bw_inventario_quimicos.cod_quimico,
					    bw_inventario_quimicos.nombre_quimico,
					    bw_inventario_quimicos.cod_unidad_medida,
					    ug_unidades_medida.unidad_medida,
					    bw_inventario_quimicos.cantidad_quimico,
					    bw_inventario_quimicos.cantidad_fisica_quimico,
					    bw_inventario_quimicos.cod_ingrediente_activo,
					    bw_ingredientes_activos.ingrediente_activo,
					    bw_inventario_quimicos.registro_ambiental,
					    bw_inventario_quimicos.periodo_reingreso,
					    bw_inventario_quimicos.cod_tipo_periodo_reingreso,
					    reingreso.tipo_periodo,
					    bw_inventario_quimicos.periodo_precosecha,
					    bw_inventario_quimicos.cod_tipo_periodo_precosecha,
					    precosecha.tipo_periodo,
					    bw_inventario_quimicos.dosis_minima,
					    bw_inventario_quimicos.dosis_maxima,
					    bw_inventario_quimicos.cantidad_minima_alerta,
					    bw_inventario_quimicos.precio_quimico,
					    bw_inventario_quimicos.cod_tipo_quimico,
					    bw_tipo_quimico.tipo_quimico,
					    bw_inventario_quimicos.activo
					FROM
					    bw_inventario_quimicos
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_quimicos.cod_info_empresa)
					        INNER JOIN
					    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_quimicos.cod_unidad_medida)
					        INNER JOIN
					    bw_ingredientes_activos ON (bw_ingredientes_activos.cod_ingrediente_activo = bw_inventario_quimicos.cod_ingrediente_activo)
					        LEFT JOIN
					    bw_tipos_periodos precosecha ON (precosecha.cod_tipo_periodo = bw_inventario_quimicos.cod_tipo_periodo_precosecha)
					        LEFT JOIN
					    bw_tipos_periodos reingreso ON (reingreso.cod_tipo_periodo = bw_inventario_quimicos.cod_tipo_periodo_reingreso)
					        INNER JOIN
					    bw_tipo_quimico ON (bw_tipo_quimico.cod_tipo_quimico = bw_inventario_quimicos.cod_tipo_quimico)
					    WHERE bw_inventario_quimicos.cod_info_empresa = :cod_info_empresa
					ORDER BY nombre_quimico ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de quimicos disponibles
	 */
	function inv_listado_quimicos_por_tipo_quimico_finca($cod_info_empresa, $cod_tipo_quimico)
	{
		$SQL = "SELECT
					    bw_inventario_quimicos.cod_inventario,
					    bw_inventario_quimicos.cod_info_empresa,
					    bw_info_empresa.nombre_empresa,
					    bw_inventario_quimicos.cod_quimico,
					    bw_inventario_quimicos.nombre_quimico,
					    bw_inventario_quimicos.cod_unidad_medida,
					    ug_unidades_medida.unidad_medida,
					    bw_inventario_quimicos.cantidad_quimico,
					    bw_inventario_quimicos.cantidad_fisica_quimico,
					    bw_inventario_quimicos.cod_ingrediente_activo,
					    bw_ingredientes_activos.ingrediente_activo,
					    bw_inventario_quimicos.registro_ambiental,
					    bw_inventario_quimicos.periodo_reingreso,
					    bw_inventario_quimicos.cod_tipo_periodo_reingreso,
					    reingreso.tipo_periodo,
					    bw_inventario_quimicos.periodo_precosecha,
					    bw_inventario_quimicos.cod_tipo_periodo_precosecha,
					    precosecha.tipo_periodo,
					    bw_inventario_quimicos.dosis_minima,
					    bw_inventario_quimicos.dosis_maxima,
					    bw_inventario_quimicos.cantidad_minima_alerta,
					    bw_inventario_quimicos.precio_quimico,
					    bw_inventario_quimicos.cod_tipo_quimico,
					    bw_tipo_quimico.tipo_quimico,
					    bw_inventario_quimicos.activo
					FROM
					    bw_inventario_quimicos
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_quimicos.cod_info_empresa)
					        INNER JOIN
					    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_quimicos.cod_unidad_medida)
					        INNER JOIN
					    bw_ingredientes_activos ON (bw_ingredientes_activos.cod_ingrediente_activo = bw_inventario_quimicos.cod_ingrediente_activo)
					        LEFT JOIN
					    bw_tipos_periodos precosecha ON (precosecha.cod_tipo_periodo = bw_inventario_quimicos.cod_tipo_periodo_precosecha)
					        LEFT JOIN
					    bw_tipos_periodos reingreso ON (reingreso.cod_tipo_periodo = bw_inventario_quimicos.cod_tipo_periodo_reingreso)
					        INNER JOIN
					    bw_tipo_quimico ON (bw_tipo_quimico.cod_tipo_quimico = bw_inventario_quimicos.cod_tipo_quimico)
					    WHERE bw_inventario_quimicos.cod_info_empresa = :cod_info_empresa
					    AND bw_inventario_quimicos.cod_tipo_quimico = :cod_tipo_quimico
					ORDER BY nombre_quimico ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);
		$stmt->bindParam(":cod_tipo_quimico",  $cod_tipo_quimico);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener información de un tipo de periodo
	 */
	function inv_obtener_info_quimico($cod_quimico)
	{
		$SQL = "SELECT
				    bw_inventario_quimicos.cod_inventario,
				    bw_inventario_quimicos.cod_info_empresa,
				    bw_info_empresa.nombre_empresa,
				    bw_inventario_quimicos.cod_quimico,
				    bw_inventario_quimicos.nombre_quimico,
				    bw_inventario_quimicos.cod_unidad_medida,
				    ug_unidades_medida.unidad_medida,
				    bw_inventario_quimicos.cantidad_quimico,
				    bw_inventario_quimicos.cantidad_fisica_quimico,
				    bw_inventario_quimicos.cod_ingrediente_activo,
				    bw_ingredientes_activos.ingrediente_activo,
				    bw_inventario_quimicos.registro_ambiental,
				    bw_inventario_quimicos.periodo_reingreso,
				    bw_inventario_quimicos.cod_tipo_periodo_reingreso,
				    reingreso.tipo_periodo,
				    bw_inventario_quimicos.periodo_precosecha,
				    bw_inventario_quimicos.cod_tipo_periodo_precosecha,
				    precosecha.tipo_periodo,
				    bw_inventario_quimicos.dosis_minima,
				    bw_inventario_quimicos.dosis_maxima,
				    bw_inventario_quimicos.cantidad_minima_alerta,
				    bw_inventario_quimicos.precio_quimico,
				    bw_inventario_quimicos.cod_tipo_quimico,
				    bw_tipo_quimico.tipo_quimico,
				    bw_inventario_quimicos.activo,
				    bw_inventario_quimicos.razon_aplicacion,
				    bw_inventario_quimicos.etiqueta,
				    bw_inventario_quimicos.hoja_seguridad
				FROM
				    bw_inventario_quimicos
				        INNER JOIN
				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_quimicos.cod_info_empresa)
				        INNER JOIN
				    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_quimicos.cod_unidad_medida)
				        INNER JOIN
				    bw_ingredientes_activos ON (bw_ingredientes_activos.cod_ingrediente_activo = bw_inventario_quimicos.cod_ingrediente_activo)
				        LEFT JOIN
				    bw_tipos_periodos precosecha ON (precosecha.cod_tipo_periodo = bw_inventario_quimicos.cod_tipo_periodo_precosecha)
				        LEFT JOIN
				    bw_tipos_periodos reingreso ON (reingreso.cod_tipo_periodo = bw_inventario_quimicos.cod_tipo_periodo_reingreso)
				        INNER JOIN
				    bw_tipo_quimico ON (bw_tipo_quimico.cod_tipo_quimico = bw_inventario_quimicos.cod_tipo_quimico)
				WHERE bw_inventario_quimicos.cod_inventario = :cod_quimico;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_quimico",  $cod_quimico);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Guarda un quimico
      */
	function inv_guardar_inventario_quimico(
		$codigo_inventario_quimico,
		$cod_info_empresa,
		$cod_quimico,
		$nombre_quimico,
		$cod_unidad_medida,
		$cantidad_quimico,
		$cantidad_fisica_quimico,
		$precio_quimico,
		$cod_ingrediente_activo,
		$registro_ambiental,
		$periodo_reingreso,
		$cod_tipo_periodo_reingreso,
		$periodo_precosecha,
		$cod_tipo_periodo_precosecha,
		$dosis_minima,
		$dosis_maxima,
		$cod_tipo_quimico,
		$cantidad_minima_alerta,
		$razon_aplicacion,
		$ext_adjunto,
		$ext_label,
		$user_insert
	) {
		$SQL = "CALL inv_guardar_inventario_quimico(:codigo_inventario_quimico,
										:cod_info_empresa,
										:cod_quimico,
										:nombre_quimico,
										:cod_unidad_medida,
										:cantidad_quimico,
										:cantidad_fisica_quimico,
										:precio_quimico,
										:cod_ingrediente_activo,
										:registro_ambiental,
										:periodo_reingreso,
										:cod_tipo_periodo_reingreso,
										:periodo_precosecha,
										:cod_tipo_periodo_precosecha,
										:dosis_minima,
										:dosis_maxima,
										:cod_tipo_quimico,
										:cantidad_minima_alerta,
										:razon_aplicacion,
										:ext_adjunto,
										:ext_label,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_inventario_quimico",  $codigo_inventario_quimico);
		$stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);
		$stmt->bindParam(":cod_quimico",  $cod_quimico);
		$stmt->bindParam(":nombre_quimico",  $nombre_quimico);
		$stmt->bindParam(":cod_unidad_medida",  $cod_unidad_medida);
		$stmt->bindParam(":cantidad_quimico",  $cantidad_quimico);
		$stmt->bindParam(":cantidad_fisica_quimico",  $cantidad_fisica_quimico);
		$stmt->bindParam(":precio_quimico",  $precio_quimico);
		$stmt->bindParam(":cod_ingrediente_activo",  $cod_ingrediente_activo);
		$stmt->bindParam(":registro_ambiental",  $registro_ambiental);
		$stmt->bindParam(":periodo_reingreso",  $periodo_reingreso);
		$stmt->bindParam(":cod_tipo_periodo_reingreso",  $cod_tipo_periodo_reingreso);
		$stmt->bindParam(":periodo_precosecha",  $periodo_precosecha);
		$stmt->bindParam(":cod_tipo_periodo_precosecha",  $cod_tipo_periodo_precosecha);
		$stmt->bindParam(":dosis_minima",  $dosis_minima);
		$stmt->bindParam(":dosis_maxima",  $dosis_maxima);
		$stmt->bindParam(":cod_tipo_quimico",  $cod_tipo_quimico);
		$stmt->bindParam(":cantidad_minima_alerta",  $cantidad_minima_alerta);
		$stmt->bindParam(":razon_aplicacion",  $razon_aplicacion);
		$stmt->bindParam(":ext_adjunto",  $ext_adjunto);
		$stmt->bindParam(":ext_label",  $ext_label);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Cambia el flag de activo de los tipos de periodo
      */
	function inv_cambiar_estado_inventario_quimico(
		$cod_quimico,
		$flag_activo,
		$user_insert
	) {
		$SQL = "CALL inv_cambiar_estado_inventario_quimico(:cod_quimico,
										:flag_activo,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_quimico",  $cod_quimico);
		$stmt->bindParam(":flag_activo",  $flag_activo);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*------------------------------------------------------------*/
	/*
	 * Funciones para obtener listado de maquinaria disponible
	 */
	function inv_listado_maquinaria()
	{
		$SQL = "SELECT
					    bw_inventario_maquinaria.cod_inventario,
					    bw_inventario_maquinaria.cod_info_empresa,
					    bw_info_empresa.nombre_empresa,
					    bw_inventario_maquinaria.codigo_maquinaria,
					    bw_inventario_maquinaria.nombre_maquinaria,
					    bw_inventario_maquinaria.cod_tipo_aplicacion,
					    bw_tipo_aplicacion_maquinaria.tipo_aplicacion,
					    bw_inventario_maquinaria.precio_unidad,
					    bw_inventario_maquinaria.anio_vencimiento,
				    	bw_inventario_maquinaria.cod_estado_plantacion,
					    bw_inventario_maquinaria.activo
					FROM
					    bw_inventario_maquinaria
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_maquinaria.cod_info_empresa)
					        INNER JOIN
					    bw_tipo_aplicacion_maquinaria ON (bw_tipo_aplicacion_maquinaria.cod_tipo_aplicacion = bw_inventario_maquinaria.cod_tipo_aplicacion)
					ORDER BY bw_inventario_maquinaria.nombre_maquinaria ASC;";
		$stmt = $this->db_conexion->prepare($SQL);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de maquinaria disponible
	 */
	function inv_listado_maquinaria_por_granjas($cod_info_empresa)
	{
		$SQL = "SELECT
					    bw_inventario_maquinaria.cod_inventario,
					    bw_inventario_maquinaria.cod_info_empresa,
					    bw_info_empresa.nombre_empresa,
					    bw_inventario_maquinaria.codigo_maquinaria,
					    bw_inventario_maquinaria.nombre_maquinaria,
					    bw_inventario_maquinaria.cod_tipo_aplicacion,
					    bw_tipo_aplicacion_maquinaria.tipo_aplicacion,
					    bw_inventario_maquinaria.precio_unidad,
					    bw_inventario_maquinaria.anio_vencimiento,
				    	bw_inventario_maquinaria.cod_estado_plantacion,
					    bw_inventario_maquinaria.activo
					FROM
					    bw_inventario_maquinaria
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_maquinaria.cod_info_empresa)
					        INNER JOIN
					    bw_tipo_aplicacion_maquinaria ON (bw_tipo_aplicacion_maquinaria.cod_tipo_aplicacion = bw_inventario_maquinaria.cod_tipo_aplicacion)
					    	WHERE
					    bw_inventario_maquinaria.cod_info_empresa IN(" . $cod_info_empresa . ")

					ORDER BY bw_inventario_maquinaria.date_insert DESC;";
		$stmt = $this->db_conexion->prepare($SQL);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de maquinaria disponible
	 */
	function inv_listado_maquinaria_por_granjas_paginacion($cod_info_empresa, $inicio, $limite)
	{
		$SQL = "SELECT
					    bw_inventario_maquinaria.cod_inventario,
					    bw_inventario_maquinaria.cod_info_empresa,
					    bw_info_empresa.nombre_empresa,
					    bw_inventario_maquinaria.codigo_maquinaria,
					    bw_inventario_maquinaria.nombre_maquinaria,
					    bw_inventario_maquinaria.cod_tipo_aplicacion,
					    bw_tipo_aplicacion_maquinaria.tipo_aplicacion,
					    bw_inventario_maquinaria.precio_unidad,
					    bw_inventario_maquinaria.anio_vencimiento,
				    	bw_inventario_maquinaria.cod_estado_plantacion,
					    bw_inventario_maquinaria.activo
					FROM
					    bw_inventario_maquinaria
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_maquinaria.cod_info_empresa)
					        INNER JOIN
					    bw_tipo_aplicacion_maquinaria ON (bw_tipo_aplicacion_maquinaria.cod_tipo_aplicacion = bw_inventario_maquinaria.cod_tipo_aplicacion)
					    	WHERE
					    bw_inventario_maquinaria.cod_info_empresa IN(" . $cod_info_empresa . ")

					ORDER BY bw_inventario_maquinaria.date_insert DESC
					LIMIT " . $inicio . " , " . $limite . ";";
		$stmt = $this->db_conexion->prepare($SQL);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de maquinaria disponible
	 */
	function inv_total_maquinaria_por_granjas($cod_info_empresa)
	{
		$SQL = "SELECT
					    COUNT(bw_inventario_maquinaria.cod_inventario) as total
					FROM
					    bw_inventario_maquinaria
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_maquinaria.cod_info_empresa)
					        INNER JOIN
					    bw_tipo_aplicacion_maquinaria ON (bw_tipo_aplicacion_maquinaria.cod_tipo_aplicacion = bw_inventario_maquinaria.cod_tipo_aplicacion)
					    	WHERE
					    bw_inventario_maquinaria.cod_info_empresa IN(" . $cod_info_empresa . ")

					ORDER BY bw_inventario_maquinaria.date_insert DESC;";
		$stmt = $this->db_conexion->prepare($SQL);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de maquinaria disponible
	 */
	function inv_listado_maquinaria_por_finca($cod_info_empresa)
	{
		$SQL = "SELECT
					    bw_inventario_maquinaria.cod_inventario,
					    bw_inventario_maquinaria.cod_info_empresa,
					    bw_info_empresa.nombre_empresa,
					    bw_inventario_maquinaria.codigo_maquinaria,
					    bw_inventario_maquinaria.nombre_maquinaria,
					    bw_inventario_maquinaria.cod_tipo_aplicacion,
					    bw_tipo_aplicacion_maquinaria.tipo_aplicacion,
					    bw_inventario_maquinaria.precio_unidad,
					    bw_inventario_maquinaria.anio_vencimiento,
				    	bw_inventario_maquinaria.cod_estado_plantacion,
					    bw_inventario_maquinaria.activo
					FROM
					    bw_inventario_maquinaria
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_maquinaria.cod_info_empresa)
					        INNER JOIN
					    bw_tipo_aplicacion_maquinaria ON (bw_tipo_aplicacion_maquinaria.cod_tipo_aplicacion = bw_inventario_maquinaria.cod_tipo_aplicacion)
					    WHERE bw_inventario_maquinaria.cod_info_empresa = :cod_info_empresa
					ORDER BY bw_inventario_maquinaria.nombre_maquinaria ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de maquinaria disponible por estado
	 */
	function inv_listado_maquinaria_por_estado($cod_info_empresa, $cod_estado)
	{
		$SQL = "SELECT
					    bw_inventario_maquinaria.cod_inventario,
					    bw_inventario_maquinaria.cod_info_empresa,
					    bw_info_empresa.nombre_empresa,
					    bw_inventario_maquinaria.codigo_maquinaria,
					    bw_inventario_maquinaria.nombre_maquinaria,
					    bw_inventario_maquinaria.cod_tipo_aplicacion,
					    bw_tipo_aplicacion_maquinaria.tipo_aplicacion,
					    bw_inventario_maquinaria.precio_unidad,
					    bw_inventario_maquinaria.anio_vencimiento,
				    	bw_inventario_maquinaria.cod_estado_plantacion,
					    bw_inventario_maquinaria.activo
					FROM
					    bw_inventario_maquinaria
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_maquinaria.cod_info_empresa)
					        INNER JOIN
					    bw_tipo_aplicacion_maquinaria ON (bw_tipo_aplicacion_maquinaria.cod_tipo_aplicacion = bw_inventario_maquinaria.cod_tipo_aplicacion)
					    WHERE bw_inventario_maquinaria.cod_estado_plantacion = :cod_estado
					    AND bw_inventario_maquinaria.cod_info_empresa = :cod_info_empresa
					ORDER BY bw_inventario_maquinaria.nombre_maquinaria ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_estado",  $cod_estado);
		$stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener información de una maquinaria
	 */
	function inv_obtener_info_maquinaria($cod_maquinaria)
	{
		$SQL = "SELECT
				    bw_inventario_maquinaria.cod_inventario,
				    bw_inventario_maquinaria.cod_info_empresa,
				    bw_info_empresa.nombre_empresa,
				    bw_inventario_maquinaria.codigo_maquinaria,
				    bw_inventario_maquinaria.nombre_maquinaria,
				    bw_inventario_maquinaria.cod_tipo_aplicacion,
				    bw_tipo_aplicacion_maquinaria.tipo_aplicacion,
				    bw_inventario_maquinaria.precio_unidad,
				    bw_inventario_maquinaria.anio_vencimiento,
				    bw_inventario_maquinaria.cod_estado_plantacion,
				    bw_inventario_maquinaria.activo
				FROM
				    bw_inventario_maquinaria
				        INNER JOIN
				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_maquinaria.cod_info_empresa)
				        INNER JOIN
				    bw_tipo_aplicacion_maquinaria ON (bw_tipo_aplicacion_maquinaria.cod_tipo_aplicacion = bw_inventario_maquinaria.cod_tipo_aplicacion)
				WHERE
				    bw_inventario_maquinaria.cod_inventario = :cod_maquinaria;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_maquinaria",  $cod_maquinaria);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Guarda una maquinaria
      */
	function inv_guardar_inventario_maquinaria(
		$codigo_inventario_maquinaria,
		$cod_info_empresa,
		$codigo_maquinaria,
		$nombre_maquinaria,
		$cod_tipo_aplicacion,
		$precio_unidad,
		$anio_vencimiento,
		$cod_estado_plantacion,
		$user_insert
	) {
		$SQL = "CALL inv_guardar_inventario_maquinaria(:codigo_inventario_maquinaria,
										:cod_info_empresa,
										:codigo_maquinaria,
										:nombre_maquinaria,
										:cod_tipo_aplicacion,
										:precio_unidad,
										:anio_vencimiento,
										:cod_estado_plantacion,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_inventario_maquinaria",  $codigo_inventario_maquinaria);
		$stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);
		$stmt->bindParam(":codigo_maquinaria",  $codigo_maquinaria);
		$stmt->bindParam(":nombre_maquinaria",  $nombre_maquinaria);
		$stmt->bindParam(":cod_tipo_aplicacion",  $cod_tipo_aplicacion);
		$stmt->bindParam(":precio_unidad",  $precio_unidad);
		$stmt->bindParam(":anio_vencimiento",  $anio_vencimiento);
		$stmt->bindParam(":cod_estado_plantacion",  $cod_estado_plantacion);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Cambia el flag de activo de la maquinaria
      */
	function inv_cambiar_estado_inventario_maquinaria(
		$cod_maquinaria,
		$flag_activo,
		$user_insert
	) {
		$SQL = "CALL inv_cambiar_estado_inventario_maquinaria(:cod_maquinaria,
										:flag_activo,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_maquinaria",  $cod_maquinaria);
		$stmt->bindParam(":flag_activo",  $flag_activo);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*------------------------------------------------------------*/
	/*
	 * Funciones para obtener listado de tipos de periodos disponibles
	 */
	function inv_listado_tipo_aplicacion()
	{
		$SQL = "SELECT
					    cod_tipo_aplicacion, tipo_aplicacion, activo
					FROM
					    bw_tipo_aplicacion_maquinaria
					ORDER BY tipo_aplicacion ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener información de un tipo de periodo
	 */
	function inv_obtener_info_tipo_aplicacion($cod_tipo_aplicacion)
	{
		$SQL = "SELECT
				    cod_tipo_aplicacion, tipo_aplicacion, activo
				FROM
				    bw_tipo_aplicacion_maquinaria
				WHERE
				    cod_tipo_aplicacion = :cod_tipo_aplicacion;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_tipo_aplicacion",  $cod_tipo_aplicacion);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Guarda un tipo de aplicacion
      */
	function inv_guardar_tipo_aplicacion(
		$codigo_tipo_aplicacion,
		$tipo_aplicacion,
		$user_insert
	) {
		$SQL = "CALL inv_guardar_tipo_aplicacion(:codigo_tipo_aplicacion,
										:tipo_aplicacion,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_tipo_aplicacion",  $codigo_tipo_aplicacion);
		$stmt->bindParam(":tipo_aplicacion",  $tipo_aplicacion);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Cambia el flag de activo de los tipos de aplicacion
      */
	function inv_cambiar_estado_tipo_aplicacion(
		$cod_tipo_aplicacion,
		$flag_activo,
		$user_insert
	) {
		$SQL = "CALL inv_cambiar_estado_tipo_aplicacion(:cod_tipo_aplicacion,
										:flag_activo,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_tipo_aplicacion",  $cod_tipo_aplicacion);
		$stmt->bindParam(":flag_activo",  $flag_activo);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*------------------------------------------------------------*/
	/*
	 * Funciones para obtener listado de proveedores disponibles
	 */
	function inv_listado_proveedores()
	{
		$SQL = "SELECT
					    bw_proveedores.cod_proveedor,
					    bw_proveedores.cod_info_empresa,
					    bw_info_empresa.nombre_empresa AS nombre_finca,
					    bw_proveedores.nombre_empresa,
					    bw_proveedores.nombre_contacto,
					    bw_proveedores.correo_contacto,
					    bw_proveedores.telefono_contacto,
					    bw_proveedores.observaciones,
					    bw_proveedores.activo
					FROM
					    bw_proveedores
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_proveedores.cod_info_empresa)
					ORDER BY bw_proveedores.nombre_empresa , bw_proveedores.nombre_contacto ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de proveedores disponibles
	 */
	function inv_listado_proveedores_por_granjas($cod_info_empresa)
	{
		$SQL = "SELECT
					    bw_proveedores.cod_proveedor,
					    bw_proveedores.cod_info_empresa,
					    bw_info_empresa.nombre_empresa AS nombre_finca,
					    bw_proveedores.nombre_empresa,
					    bw_proveedores.nombre_contacto,
					    bw_proveedores.correo_contacto,
					    bw_proveedores.telefono_contacto,
					    bw_proveedores.observaciones,
					    bw_proveedores.activo
					FROM
					    bw_proveedores
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_proveedores.cod_info_empresa)
					    	WHERE
					    bw_proveedores.cod_info_empresa IN (" . $cod_info_empresa . ")
					ORDER BY bw_proveedores.nombre_empresa , bw_proveedores.nombre_contacto ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener información de un proveedor
	 */
	function inv_obtener_info_proveedor($cod_proveedor)
	{
		$SQL = "SELECT
				    bw_proveedores.cod_proveedor,
				    bw_proveedores.cod_info_empresa,
				    bw_info_empresa.nombre_empresa AS nombre_finca,
				    bw_proveedores.nombre_empresa,
				    bw_proveedores.nombre_contacto,
				    bw_proveedores.correo_contacto,
				    bw_proveedores.telefono_contacto,
				    bw_proveedores.observaciones,
				    bw_proveedores.activo
				FROM
				    bw_proveedores
				        INNER JOIN
				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_proveedores.cod_info_empresa)
				WHERE
				    bw_proveedores.cod_proveedor = :cod_proveedor;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_proveedor",  $cod_proveedor);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Guarda un proveedor
      */
	function inv_guardar_proveedor(
		$codigo_proveedor,
		$cod_info_empresa,
		$nombre_empresa,
		$nombre_contacto,
		$correo_contacto,
		$telefono_contacto,
		$observaciones,
		$user_insert
	) {
		$SQL = "CALL inv_guardar_proveedor(:codigo_proveedor,
        								:cod_info_empresa,
        								:nombre_empresa,
        								:nombre_contacto,
        								:correo_contacto,
        								:telefono_contacto,
        								:observaciones,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_proveedor",  $codigo_proveedor);
		$stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);
		$stmt->bindParam(":nombre_empresa",  $nombre_empresa);
		$stmt->bindParam(":nombre_contacto",  $nombre_contacto);
		$stmt->bindParam(":correo_contacto",  $correo_contacto);
		$stmt->bindParam(":telefono_contacto",  $telefono_contacto);
		$stmt->bindParam(":observaciones",  $observaciones);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Cambia el flag de activo de los proveedores
      */
	function inv_cambiar_estado_proveedor(
		$cod_proveedor,
		$flag_activo,
		$user_insert
	) {
		$SQL = "CALL inv_cambiar_estado_proveedor(:cod_proveedor,
										:flag_activo,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_proveedor",  $cod_proveedor);
		$stmt->bindParam(":flag_activo",  $flag_activo);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de proveedores disponibles
	 */
	function inv_listado_proveedores_por_finca($cod_info_empresa)
	{
		$SQL = "SELECT
					    bw_proveedores.cod_proveedor,
					    bw_proveedores.cod_info_empresa,
					    bw_info_empresa.nombre_empresa AS nombre_finca,
					    bw_proveedores.nombre_empresa,
					    bw_proveedores.nombre_contacto,
					    bw_proveedores.correo_contacto,
					    bw_proveedores.telefono_contacto,
					    bw_proveedores.observaciones,
					    bw_proveedores.activo
					FROM
					    bw_proveedores
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_proveedores.cod_info_empresa)
					WHERE
					    bw_proveedores.cod_info_empresa = :cod_info_empresa
					ORDER BY bw_proveedores.nombre_empresa , bw_proveedores.nombre_contacto ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de proveedores en grupo
	 */
	function inv_listado_proveedores_grupos()
	{
		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->execute();

		$SQL = "SELECT 
					GROUP_CONCAT(bw_proveedores.cod_proveedor) AS cod_proveedor,
					bw_proveedores.nombre_empresa AS proveedor
				FROM
					bw_proveedores
						INNER JOIN
					bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_proveedores.cod_info_empresa)
				GROUP BY bw_proveedores.nombre_empresa
				ORDER BY bw_proveedores.nombre_empresa ASC;";

		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*------------------------------------------------------------*/
	/*
	 * Funciones para obtener listado de productos disponibles
	 */
	function inv_obtener_listado_productos_proveedor($cod_proveedor)
	{
		// $SQL = "SELECT
		// 			    bw_detalle_productos_proveedores.cod_detalle,
		// 			    bw_detalle_productos_proveedores.cod_proveedor,
		// 			    bw_detalle_productos_proveedores.cod_inventario,
		// 			    bw_inventario_quimicos.cod_quimico,
		// 			    bw_inventario_quimicos.nombre_quimico,
		// 			    bw_inventario_maquinaria.codigo_maquinaria,
		// 			    bw_inventario_maquinaria.nombre_maquinaria,
		// 			    bw_inventario_semilla.codigo_semilla,
		// 			    bw_inventario_semilla.nombre_semilla,
		// 			    bw_detalle_productos_proveedores.flag_tipo_inventario,
		// 			    bw_detalle_productos_proveedores.activo
		// 			FROM
		// 			    bw_detalle_productos_proveedores
		// 			        LEFT JOIN
		// 			    bw_inventario_quimicos ON (bw_inventario_quimicos.cod_inventario = bw_detalle_productos_proveedores.cod_inventario)
		// 			        LEFT JOIN
		// 			    bw_inventario_maquinaria ON (bw_inventario_maquinaria.cod_inventario = bw_detalle_productos_proveedores.cod_inventario)
		// 			        LEFT JOIN
		// 			    bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_detalle_productos_proveedores.cod_inventario)
		// 			WHERE
		// 			    bw_detalle_productos_proveedores.cod_proveedor = :cod_proveedor
		// 			ORDER BY bw_inventario_quimicos.nombre_quimico , bw_inventario_maquinaria.nombre_maquinaria ASC, bw_inventario_semilla.nombre_semilla ASC;";
		$SQL = "SELECT
					    bw_detalle_productos_proveedores.cod_detalle,
					    bw_detalle_productos_proveedores.cod_proveedor,
					    bw_detalle_productos_proveedores.cod_inventario,
					    bw_inventario_semilla.codigo_semilla,
					    bw_inventario_semilla.nombre_semilla,
					    bw_detalle_productos_proveedores.flag_tipo_inventario,
					    bw_detalle_productos_proveedores.activo
					FROM
					    bw_detalle_productos_proveedores
					        LEFT JOIN
					    bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_detalle_productos_proveedores.cod_inventario)
					WHERE
					    bw_detalle_productos_proveedores.cod_proveedor = :cod_proveedor
					ORDER BY  bw_inventario_semilla.nombre_semilla ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_proveedor",  $cod_proveedor);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener información de un proveedor
	 */
	function inv_obtener_info_producto($cod_producto)
	{
		// $SQL = "SELECT
		// 		    bw_detalle_productos_proveedores.cod_detalle,
		// 		    bw_detalle_productos_proveedores.cod_proveedor,
		// 		    bw_detalle_productos_proveedores.cod_inventario,
		// 		    bw_inventario_quimicos.cod_quimico,
		// 		    bw_inventario_quimicos.nombre_quimico,
		// 		    bw_inventario_maquinaria.codigo_maquinaria,
		// 		    bw_inventario_maquinaria.nombre_maquinaria,
		// 		    bw_detalle_productos_proveedores.flag_tipo_inventario,
		// 		    bw_detalle_productos_proveedores.activo
		// 		FROM
		// 		    bw_detalle_productos_proveedores
		// 		        LEFT JOIN
		// 		    bw_inventario_quimicos ON (bw_inventario_quimicos.cod_inventario = bw_detalle_productos_proveedores.cod_inventario)
		// 		        LEFT JOIN
		// 		    bw_inventario_maquinaria ON (bw_inventario_maquinaria.cod_inventario = bw_detalle_productos_proveedores.cod_inventario)
		// 		WHERE
		// 		    bw_detalle_productos_proveedores.cod_detalle = :cod_producto;";
		$SQL = "SELECT
				    bw_detalle_productos_proveedores.cod_detalle,
				    bw_detalle_productos_proveedores.cod_proveedor,
				    bw_detalle_productos_proveedores.cod_inventario,

				    bw_inventario_maquinaria.codigo_maquinaria,
				    bw_inventario_maquinaria.nombre_maquinaria,
				    bw_detalle_productos_proveedores.flag_tipo_inventario,
				    bw_detalle_productos_proveedores.activo
				FROM
				    bw_detalle_productos_proveedores
				        LEFT JOIN
				    bw_inventario_quimicos ON (bw_inventario_quimicos.cod_inventario = bw_detalle_productos_proveedores.cod_inventario)
				        LEFT JOIN
				    bw_inventario_maquinaria ON (bw_inventario_maquinaria.cod_inventario = bw_detalle_productos_proveedores.cod_inventario)
				WHERE
				    bw_detalle_productos_proveedores.cod_detalle = :cod_producto;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_producto",  $cod_producto);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Guarda un producto
      */
	function inv_guardar_producto(
		$codigo_proveedor,
		$cod_inventario_quimico,
		$cod_inventario_maquinaria,
		$cod_inventario_semilla,
		$user_insert
	) {
		$SQL = "CALL inv_guardar_producto(:codigo_proveedor,
        								:cod_inventario_quimico,
        								:cod_inventario_maquinaria,
        								:cod_inventario_semilla,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_proveedor",  $codigo_proveedor);
		$stmt->bindParam(":cod_inventario_quimico",  $cod_inventario_quimico);
		$stmt->bindParam(":cod_inventario_maquinaria",  $cod_inventario_maquinaria);
		$stmt->bindParam(":cod_inventario_semilla",  $cod_inventario_semilla);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Cambia el flag de activo de los productos
      */
	function inv_cambiar_estado_producto(
		$cod_prodcuto,
		$flag_activo,
		$user_insert
	) {
		$SQL = "CALL inv_cambiar_estado_producto(:cod_prodcuto,
										:flag_activo,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_prodcuto",  $cod_prodcuto);
		$stmt->bindParam(":flag_activo",  $flag_activo);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Guarda un movimiento de inventario
      */
	function inv_guardar_movimiento_inventario(
		$codigo_movimiento,
		$cod_info_empresa_envia,
		$cod_info_empresa_recibe,
		$fecha_envia,
		$fecha_recibe,
		$cod_tipo_inventario,
		$cod_inventario,
		$cod_unidad_medida,
		$num_lote,
		$cantidad_enviada,
		$cantidad_recibida,
		$motivo_perdida,
		$cantidad_perdida,
		$user_insert
	) {
		$SQL = "CALL inv_guardar_movimiento_inventario(:codigo_movimiento,
										:cod_info_empresa_envia,
										:cod_info_empresa_recibe,
										:fecha_envia,
										:fecha_recibe,
										:cod_tipo_inventario,
										:cod_inventario,
										:cod_unidad_medida,
										:num_lote,
										:cantidad_enviada,
										:cantidad_recibida,
										:motivo_perdida,
										:cantidad_perdida,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_movimiento",  $codigo_movimiento);
		$stmt->bindParam(":cod_info_empresa_envia",  $cod_info_empresa_envia);
		$stmt->bindParam(":cod_info_empresa_recibe",  $cod_info_empresa_recibe);
		$stmt->bindParam(":fecha_envia",  $fecha_envia);
		$stmt->bindParam(":cod_tipo_inventario",  $cod_tipo_inventario);
		$stmt->bindParam(":cod_inventario",  $cod_inventario);
		$stmt->bindParam(":cantidad_enviada",  $cantidad_enviada);
		$stmt->bindParam(":fecha_recibe",  $fecha_recibe);
		$stmt->bindParam(":cod_unidad_medida",  $cod_unidad_medida);
		$stmt->bindParam(":num_lote",  $num_lote);
		$stmt->bindParam(":cantidad_recibida",  $cantidad_recibida);
		$stmt->bindParam(":motivo_perdida",  $motivo_perdida);
		$stmt->bindParam(":cantidad_perdida",  $cantidad_perdida);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*------------------------------------------------------------*/
	/*
	 * Funciones para obtener listado de movimientos de inventario
	 */
	function inv_listado_movimientos_inventario()
	{
		$SQL = "SELECT
					    bw_movimientos_inventario.cod_movimiento,
					    bw_movimientos_inventario.cod_info_empresa_envia,
					    empresa_envia.nombre_empresa as nombre_empresa_envia,
					    bw_movimientos_inventario.cod_info_empresa_recibe,
					    empresa_recibe.nombre_empresa as nombre_empresa_recibe,
					    bw_movimientos_inventario.cod_inventario,
					    bw_movimientos_inventario.cod_tipo_inventario,
					    bw_movimientos_inventario.cantidad_enviada,
					    bw_movimientos_inventario.cantidad_recibida,
					    bw_movimientos_inventario.cod_unidad_medida,
					    ug_unidades_medida.unidad_medida,
					    bw_movimientos_inventario.num_lote,
					    bw_movimientos_inventario.motivo_perdida,
					    DATE_FORMAT(STR_TO_DATE(bw_movimientos_inventario.fecha_envia,'%Y-%m-%d'),'%m-%d-%Y') as fecha_envia,
					    DATE_FORMAT(STR_TO_DATE(bw_movimientos_inventario.fecha_recibe,'%Y-%m-%d'),'%m-%d-%Y') as fecha_recibe,
					    bw_movimientos_inventario.user_envia,
					    CONCAT(user_envia.nombre_1,
					            ' ',
					            user_envia.apellido_1) AS nombre_user_envia,
					    bw_movimientos_inventario.user_recibe,
					    CONCAT(user_recibe.nombre_1,
					            ' ',
					            user_recibe.apellido_1) AS nombre_user_recibe,
					    bw_movimientos_inventario.activo
					FROM
					    bw_movimientos_inventario
					        INNER JOIN
					    bw_info_empresa empresa_envia ON (empresa_envia.cod_info_empresa = bw_movimientos_inventario.cod_info_empresa_envia)
					        LEFT JOIN
					    bw_info_empresa empresa_recibe ON (empresa_recibe.cod_info_empresa = bw_movimientos_inventario.cod_info_empresa_recibe)
					        INNER JOIN
					    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_movimientos_inventario.cod_unidad_medida)
					        INNER JOIN
					    usu_usuarios user_envia ON (user_envia.cod_usuario = bw_movimientos_inventario.user_envia)
					        LEFT JOIN
					    usu_usuarios user_recibe ON (user_recibe.cod_usuario = bw_movimientos_inventario.user_recibe)
					WHERE
					    bw_movimientos_inventario.activo = 1
					ORDER BY empresa_envia.nombre_empresa ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de movimientos de inventario
	 */
	function inv_listado_movimientos_inventario_por_granjas($cod_info_empresa)
	{
		$SQL = "SELECT
					    bw_movimientos_inventario.cod_movimiento,
					    bw_movimientos_inventario.cod_info_empresa_envia,
					    empresa_envia.nombre_empresa as nombre_empresa_envia,
					    bw_movimientos_inventario.cod_info_empresa_recibe,
					    empresa_recibe.nombre_empresa as nombre_empresa_recibe,
					    bw_movimientos_inventario.cod_inventario,
					    bw_movimientos_inventario.cod_tipo_inventario,
					    bw_movimientos_inventario.cantidad_enviada,
					    bw_movimientos_inventario.cantidad_recibida,
					    bw_movimientos_inventario.cod_unidad_medida,
					    ug_unidades_medida.unidad_medida,
					    bw_movimientos_inventario.num_lote,
					    bw_movimientos_inventario.motivo_perdida,
					    DATE_FORMAT(STR_TO_DATE(bw_movimientos_inventario.fecha_envia,'%Y-%m-%d'),'%m-%d-%Y') as fecha_envia,
					    DATE_FORMAT(STR_TO_DATE(bw_movimientos_inventario.fecha_recibe,'%Y-%m-%d'),'%m-%d-%Y') as fecha_recibe,
					    bw_movimientos_inventario.user_envia,
					    CONCAT(user_envia.nombre_1,
					            ' ',
					            user_envia.apellido_1) AS nombre_user_envia,
					    bw_movimientos_inventario.user_recibe,
					    CONCAT(user_recibe.nombre_1,
					            ' ',
					            user_recibe.apellido_1) AS nombre_user_recibe,
					    bw_movimientos_inventario.activo
					FROM
					    bw_movimientos_inventario
					        INNER JOIN
					    bw_info_empresa empresa_envia ON (empresa_envia.cod_info_empresa = bw_movimientos_inventario.cod_info_empresa_envia)
					        LEFT JOIN
					    bw_info_empresa empresa_recibe ON (empresa_recibe.cod_info_empresa = bw_movimientos_inventario.cod_info_empresa_recibe)
					        INNER JOIN
					    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_movimientos_inventario.cod_unidad_medida)
					        INNER JOIN
					    usu_usuarios user_envia ON (user_envia.cod_usuario = bw_movimientos_inventario.user_envia)
					        LEFT JOIN
					    usu_usuarios user_recibe ON (user_recibe.cod_usuario = bw_movimientos_inventario.user_recibe)
					WHERE
					    bw_movimientos_inventario.activo = 1
					    AND bw_movimientos_inventario.cod_info_empresa_envia IN(" . $cod_info_empresa . ")
					    OR bw_movimientos_inventario.cod_info_empresa_recibe IN(" . $cod_info_empresa . ")
					ORDER BY bw_movimientos_inventario.date_insert DESC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener información de un movimiento de inventario
	 */
	function inv_obtener_info_movimiento_inventario($cod_movimiento)
	{
		$SQL = "SELECT
				    bw_movimientos_inventario.cod_movimiento,
				    bw_movimientos_inventario.cod_info_empresa_envia,
				    empresa_envia.nombre_empresa as nombre_empresa_envia,
				    bw_movimientos_inventario.cod_info_empresa_recibe,
				    empresa_recibe.nombre_empresa as nombre_empresa_recibe,
				    bw_movimientos_inventario.cod_inventario,
				    bw_movimientos_inventario.cod_tipo_inventario,
				    bw_movimientos_inventario.cantidad_enviada,
				    bw_movimientos_inventario.cantidad_recibida,
				    bw_movimientos_inventario.cod_unidad_medida,
				    ug_unidades_medida.unidad_medida,
				    bw_movimientos_inventario.num_lote,
				    bw_movimientos_inventario.motivo_perdida,
				    DATE_FORMAT(STR_TO_DATE(bw_movimientos_inventario.fecha_envia,'%Y-%m-%d'),'%m-%d-%Y') as fecha_envia,
				    DATE_FORMAT(STR_TO_DATE(bw_movimientos_inventario.fecha_recibe,'%Y-%m-%d'),'%m-%d-%Y') as fecha_recibe,
				    bw_movimientos_inventario.user_envia,
				    CONCAT(user_envia.nombre_1,
				            ' ',
				            user_envia.apellido_1) AS nombre_user_envia,
				    bw_movimientos_inventario.user_recibe,
				    CONCAT(user_recibe.nombre_1,
				            ' ',
				            user_recibe.apellido_1) AS nombre_user_recibe,
				    bw_movimientos_inventario.activo
				FROM
				    bw_movimientos_inventario
				        INNER JOIN
				    bw_info_empresa empresa_envia ON (empresa_envia.cod_info_empresa = bw_movimientos_inventario.cod_info_empresa_envia)
				        LEFT JOIN
				    bw_info_empresa empresa_recibe ON (empresa_recibe.cod_info_empresa = bw_movimientos_inventario.cod_info_empresa_recibe)
				        INNER JOIN
				    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_movimientos_inventario.cod_unidad_medida)
				        INNER JOIN
				    usu_usuarios user_envia ON (user_envia.cod_usuario = bw_movimientos_inventario.user_envia)
				        LEFT JOIN
				    usu_usuarios user_recibe ON (user_recibe.cod_usuario = bw_movimientos_inventario.user_recibe)
				WHERE
				    bw_movimientos_inventario.activo = 1
				        AND bw_movimientos_inventario.cod_movimiento = :cod_movimiento
				ORDER BY empresa_envia.nombre_empresa ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_movimiento",  $cod_movimiento);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Guarda un tipo de aplicacion
      */
	function inv_guardar_movivimiento_inventario(
		$codigo_tipo_aplicacion,
		$tipo_aplicacion,
		$user_insert
	) {
		$SQL = "CALL inv_guardar_movivimiento_inventario(:codigo_tipo_aplicacion,
										:tipo_aplicacion,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_tipo_aplicacion",  $codigo_tipo_aplicacion);
		$stmt->bindParam(":tipo_aplicacion",  $tipo_aplicacion);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Cambia el flag de activo de los tipos de aplicacion
      */
	function inv_cambiar_estado_movimiento_inventario(
		$cod_movimiento,
		$flag_activo,
		$user_insert
	) {
		$SQL = "CALL inv_cambiar_estado_movimiento_inventario(:cod_movimiento,
										:flag_activo,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_movimiento",  $cod_movimiento);
		$stmt->bindParam(":flag_activo",  $flag_activo);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*------------------------------------------------------------*/
	/*
	 * Funciones para obtener listado de articulos varios disponible
	 */
	function inv_listado_articulos_varios()
	{
		$SQL = "SELECT
					    bw_inventario_otros.cod_inventario,
					    bw_inventario_otros.cod_info_empresa,
					    bw_info_empresa.nombre_empresa,
    					bw_inventario_otros.codigo_producto,
					    bw_inventario_otros.nombre_producto,
					    bw_inventario_otros.cantidad_producto,
					    bw_inventario_otros.cod_unidad_medida,
					    ug_unidades_medida.unidad_medida,
					    bw_inventario_otros.activo
					FROM
					    bw_inventario_otros
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_otros.cod_info_empresa)
					        INNER JOIN
					    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_otros.cod_unidad_medida)
					WHERE
					    bw_inventario_otros.activo = 1
					ORDER BY bw_inventario_otros.nombre_producto ASC;";
		$stmt = $this->db_conexion->prepare($SQL);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de articulos varios disponible
	 */
	function inv_listado_articulos_varios_por_granjas($cod_info_empresa)
	{
		$SQL = "SELECT
					    bw_inventario_otros.cod_inventario,
					    bw_inventario_otros.cod_info_empresa,
					    bw_info_empresa.nombre_empresa,
    					bw_inventario_otros.codigo_producto,
					    bw_inventario_otros.nombre_producto,
					    bw_inventario_otros.cantidad_producto,
					    bw_inventario_otros.cod_unidad_medida,
					    ug_unidades_medida.unidad_medida,
					    bw_inventario_otros.activo
					FROM
					    bw_inventario_otros
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_otros.cod_info_empresa)
					        INNER JOIN
					    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_otros.cod_unidad_medida)
					WHERE
					    bw_inventario_otros.activo = 1
					    AND bw_inventario_otros.cod_info_empresa IN (" . $cod_info_empresa . ")
					ORDER BY bw_inventario_otros.date_insert DESC;";
		$stmt = $this->db_conexion->prepare($SQL);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de articulos varios disponible
	 */
	function inv_listado_articulos_varios_por_granjas_paginacion($cod_info_empresa, $inicio, $limite)
	{
		$SQL = "SELECT
					    bw_inventario_otros.cod_inventario,
					    bw_inventario_otros.cod_info_empresa,
					    bw_info_empresa.nombre_empresa,
    					bw_inventario_otros.codigo_producto,
					    bw_inventario_otros.nombre_producto,
					    bw_inventario_otros.cantidad_producto,
					    bw_inventario_otros.cod_unidad_medida,
					    ug_unidades_medida.unidad_medida,
					    bw_inventario_otros.activo,
					    DATE_FORMAT(STR_TO_DATE(bw_inventario_otros.fecha_inventario,'%Y-%m-%d'),'%m-%d-%Y') as fecha_inventario,
					    bw_inventario_otros.orden_compra
					FROM
					    bw_inventario_otros
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_otros.cod_info_empresa)
					        INNER JOIN
					    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_otros.cod_unidad_medida)
					WHERE
					    bw_inventario_otros.activo = 1
					    AND bw_inventario_otros.cod_info_empresa IN (" . $cod_info_empresa . ")
					ORDER BY bw_inventario_otros.date_insert DESC
					LIMIT " . $inicio . " , " . $limite . ";";
		$stmt = $this->db_conexion->prepare($SQL);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de articulos varios disponible
	 */
	function inv_total_articulos_varios_por_granjas($cod_info_empresa)
	{
		$SQL = "SELECT
					    COUNT(bw_inventario_otros.cod_inventario) as total
					FROM
					    bw_inventario_otros
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_otros.cod_info_empresa)
					        INNER JOIN
					    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_otros.cod_unidad_medida)
					WHERE
					    bw_inventario_otros.activo = 1
					    AND bw_inventario_otros.cod_info_empresa IN (" . $cod_info_empresa . ")
					ORDER BY bw_inventario_otros.date_insert DESC;";
		$stmt = $this->db_conexion->prepare($SQL);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de articulos varios disponible
	 */
	function inv_listado_articulo_vario_por_finca($cod_info_empresa)
	{
		$SQL = "SELECT
					    bw_inventario_otros.cod_inventario,
					    bw_inventario_otros.cod_info_empresa,
					    bw_info_empresa.nombre_empresa,
    					bw_inventario_otros.codigo_producto,
					    bw_inventario_otros.nombre_producto,
					    bw_inventario_otros.cantidad_producto,
					    bw_inventario_otros.cod_unidad_medida,
					    ug_unidades_medida.unidad_medida,
					    bw_inventario_otros.activo
					FROM
					    bw_inventario_otros
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_otros.cod_info_empresa)
					        INNER JOIN
					    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_otros.cod_unidad_medida)
					WHERE
					    bw_inventario_otros.activo = 1
					        AND bw_inventario_otros.cod_info_empresa = :cod_info_empresa
					ORDER BY bw_inventario_otros.nombre_producto ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener información de un articulo
	 */
	function inv_obtener_info_articulo_vario($cod_inventario)
	{
		$SQL = "SELECT
				    bw_inventario_otros.cod_inventario,
				    bw_inventario_otros.cod_info_empresa,
				    bw_info_empresa.nombre_empresa,
    				bw_inventario_otros.codigo_producto,
				    bw_inventario_otros.nombre_producto,
				    bw_inventario_otros.cantidad_producto,
				    bw_inventario_otros.cod_unidad_medida,
				    ug_unidades_medida.unidad_medida,
				    bw_inventario_otros.activo,
				    DATE_FORMAT(STR_TO_DATE(bw_inventario_otros.fecha_inventario,'%Y-%m-%d'),'%m-%d-%Y') as fecha_inventario,
				    bw_inventario_otros.orden_compra
				FROM
				    bw_inventario_otros
				        INNER JOIN
				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_otros.cod_info_empresa)
				        INNER JOIN
				    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_otros.cod_unidad_medida)
				WHERE
				    bw_inventario_otros.activo = 1
				        AND bw_inventario_otros.cod_inventario = :cod_inventario;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_inventario",  $cod_inventario);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Guarda un articulo vario
      */
	function inv_guardar_inventario_vario(
		$codigo_inventario_vario,
		$cod_info_empresa,
		$codigo_producto,
		$nombre_producto,
		$cantidad_producto,
		$cod_unidad_medida,
		$orden_compra,
		$fecha_inventario,
		$user_insert
	) {
		$SQL = "CALL inv_guardar_inventario_vario(:codigo_inventario_vario,
										:cod_info_empresa,
										:codigo_producto,
										:nombre_producto,
										:cantidad_producto,
										:cod_unidad_medida,
										:orden_compra,
										:fecha_inventario,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_inventario_vario",  $codigo_inventario_vario);
		$stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);
		$stmt->bindParam(":codigo_producto",  $codigo_producto);
		$stmt->bindParam(":nombre_producto",  $nombre_producto);
		$stmt->bindParam(":cantidad_producto",  $cantidad_producto);
		$stmt->bindParam(":cod_unidad_medida",  $cod_unidad_medida);
		$stmt->bindParam(":orden_compra",  $orden_compra);
		$stmt->bindParam(":fecha_inventario",  $fecha_inventario);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Cambia el flag de activo de la maquinaria
      */
	function inv_cambiar_estado_inventario_vario(
		$cod_maquinaria,
		$flag_activo,
		$user_insert
	) {
		$SQL = "CALL inv_cambiar_estado_inventario_vario(:cod_maquinaria,
										:flag_activo,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_maquinaria",  $cod_maquinaria);
		$stmt->bindParam(":flag_activo",  $flag_activo);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*
	 * Funciones para obtener información de un articulo
	 */
	function inv_obtener_info_articulo_vario_bitacora($cod_inventario)
	{
		$SQL = "SELECT
				    bw_inventario_otros_bitacora.cod_inventario,
				    bw_inventario_otros_bitacora.cod_info_empresa,
				    bw_inventario_otros_bitacora.codigo_producto,
				    bw_info_empresa.nombre_empresa,
				    bw_inventario_otros_bitacora.nombre_producto,
				    bw_inventario_otros_bitacora.cantidad_producto,
				    bw_inventario_otros_bitacora.cod_unidad_medida,
				    ug_unidades_medida.unidad_medida,
				    CONCAT(usu_usuarios.nombre_1,
				            ' ',
				            usu_usuarios.apellido_1) AS usuario_actualizo,
				    bw_inventario_otros_bitacora.activo,
				    DATE_FORMAT(STR_TO_DATE(bw_inventario_otros_bitacora.fecha_inventario,'%Y-%m-%d'),'%m-%d-%Y') as fecha_inventario
				FROM
				    bw_inventario_otros_bitacora
				        INNER JOIN
				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_otros_bitacora.cod_info_empresa)
				        INNER JOIN
				    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_otros_bitacora.cod_unidad_medida)
				        INNER JOIN
				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_inventario_otros_bitacora.user_update)
				WHERE
				    bw_inventario_otros_bitacora.activo = 1
				        AND bw_inventario_otros_bitacora.cod_inventario = :cod_inventario;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_inventario",  $cod_inventario);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*------------------------------------------------------------*/
	/*
	 * Funciones para obtener listado de ordenes de compra
	 */
	function inv_listado_ordenes_compra()
	{
		$SQL = "SELECT
				    bw_ordenes_compra.cod_orden,
				    bw_ordenes_compra.cod_info_empresa,
				    bw_info_empresa.nombre_empresa,
				    bw_ordenes_compra.cod_proveedor,
				    bw_proveedores.nombre_empresa as nombre_proveedor,
				    bw_proveedores.nombre_contacto,
				    bw_proveedores.correo_contacto,
				    bw_proveedores.telefono_contacto,
				    DATE_FORMAT(STR_TO_DATE(bw_ordenes_compra.fecha_orden,'%Y-%m-%d'),'%m-%d-%Y') as fecha_orden,
				    bw_ordenes_compra.fecha_estimada_entrega,
				    bw_ordenes_compra.adjunto_orden_compra,
				    DATE_FORMAT(STR_TO_DATE(bw_ordenes_compra.fecha_recibido_pedido,'%Y-%m-%d'),'%m-%d-%Y') as fecha_recibido_pedido,
				    bw_ordenes_compra.observaciones,
				    bw_ordenes_compra.activo,
				    CONCAT(usu_usuarios.nombre_1,
				            ' ',
				            usu_usuarios.apellido_1) AS nombre_usuario
				FROM
				    bw_ordenes_compra
				        INNER JOIN
				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_ordenes_compra.cod_info_empresa)
				        INNER JOIN
				    bw_proveedores ON (bw_proveedores.cod_proveedor = bw_ordenes_compra.cod_proveedor)
				        INNER JOIN
				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_ordenes_compra.user_insert)
				WHERE
				    bw_ordenes_compra.activo = 1
				ORDER BY bw_ordenes_compra.fecha_orden ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de ordenes de compra
	 */
	function inv_listado_ordenes_compra_por_granjas($cod_info_empresa)
	{
		$SQL = "SELECT
				    bw_ordenes_compra.cod_orden,
				    bw_ordenes_compra.completada,
				    bw_ordenes_compra.cod_info_empresa,
				    bw_info_empresa.nombre_empresa,
				    bw_ordenes_compra.cod_proveedor,
				    bw_proveedores.nombre_empresa as nombre_proveedor,
				    bw_proveedores.nombre_contacto,
				    bw_proveedores.correo_contacto,
				    bw_proveedores.telefono_contacto,
				    DATE_FORMAT(STR_TO_DATE(bw_ordenes_compra.fecha_orden,'%Y-%m-%d'),'%m-%d-%Y') as fecha_orden,
				    bw_ordenes_compra.fecha_estimada_entrega,
				    bw_ordenes_compra.adjunto_orden_compra,
				    bw_ordenes_compra.num_orden_compra,
				    DATE_FORMAT(STR_TO_DATE(bw_ordenes_compra.fecha_recibido_pedido,'%Y-%m-%d'),'%m-%d-%Y') as fecha_recibido_pedido,
				    bw_ordenes_compra.observaciones,
				    ug_estados_ordenes_compra.estado,
				    bw_ordenes_compra.activo,
				    CONCAT(usu_usuarios.nombre_1,
				            ' ',
				            usu_usuarios.apellido_1) AS nombre_usuario
				FROM
				    bw_ordenes_compra
				        INNER JOIN
				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_ordenes_compra.cod_info_empresa)
				        INNER JOIN
				    bw_proveedores ON (bw_proveedores.cod_proveedor = bw_ordenes_compra.cod_proveedor)
				        INNER JOIN
				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_ordenes_compra.user_insert)
				   		INNER JOIN
				   	ug_estados_ordenes_compra ON (ug_estados_ordenes_compra.cod_estado_orden_compra = bw_ordenes_compra.cod_estado_orden_compra)
				WHERE
				    bw_ordenes_compra.activo = 1
				    AND bw_ordenes_compra.cod_info_empresa IN (" . $cod_info_empresa . ")
				ORDER BY bw_ordenes_compra.fecha_orden DESC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de ordenes de compra
	 */
	function inv_total_ordenes_compra_por_granjas($cod_info_empresa)
	{
		$SQL = "SELECT
				    COUNT(bw_ordenes_compra.cod_orden) as total
				FROM
				    bw_ordenes_compra
				        INNER JOIN
				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_ordenes_compra.cod_info_empresa)
				        INNER JOIN
				    bw_proveedores ON (bw_proveedores.cod_proveedor = bw_ordenes_compra.cod_proveedor)
				        INNER JOIN
				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_ordenes_compra.user_insert)
				WHERE
				    bw_ordenes_compra.activo = 1
				    AND bw_ordenes_compra.cod_info_empresa IN (" . $cod_info_empresa . ")
				ORDER BY bw_ordenes_compra.fecha_orden DESC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de ordenes de compra
	 */
	function inv_listado_ordenes_compra_por_granjas_paginacion($cod_info_empresa, $inicio, $limite)
	{
		$SQL = "SELECT
				    bw_ordenes_compra.cod_orden,
				    bw_ordenes_compra.cod_info_empresa,
				    bw_info_empresa.nombre_empresa,
				    bw_ordenes_compra.cod_proveedor,
				    bw_proveedores.nombre_empresa as nombre_proveedor,
				    bw_proveedores.nombre_contacto,
				    bw_proveedores.correo_contacto,
				    bw_proveedores.telefono_contacto,
				    DATE_FORMAT(STR_TO_DATE(bw_ordenes_compra.fecha_orden,'%Y-%m-%d'),'%m-%d-%Y') as fecha_orden,
				    bw_ordenes_compra.fecha_estimada_entrega,
				    bw_ordenes_compra.adjunto_orden_compra,
				    bw_ordenes_compra.num_orden_compra,
				    DATE_FORMAT(STR_TO_DATE(bw_ordenes_compra.fecha_recibido_pedido,'%Y-%m-%d'),'%m-%d-%Y') as fecha_recibido_pedido,
				    bw_ordenes_compra.observaciones,
				    bw_ordenes_compra.activo,
				    CONCAT(usu_usuarios.nombre_1,
				            ' ',
				            usu_usuarios.apellido_1) AS nombre_usuario
				FROM
				    bw_ordenes_compra
				        INNER JOIN
				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_ordenes_compra.cod_info_empresa)
				        INNER JOIN
				    bw_proveedores ON (bw_proveedores.cod_proveedor = bw_ordenes_compra.cod_proveedor)
				        INNER JOIN
				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_ordenes_compra.user_insert)
				WHERE
				    bw_ordenes_compra.activo = 1
				    AND bw_ordenes_compra.cod_info_empresa IN (" . $cod_info_empresa . ")
				ORDER BY bw_ordenes_compra.fecha_orden DESC
				LIMIT " . $inicio . " , " . $limite . ";";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener información de una orden de compra
	 */
	function inv_obtener_info_orden_compra($cod_orden_compra)
	{
		$SQL = "SELECT
				    bw_ordenes_compra.cod_orden,
				    bw_ordenes_compra.cod_info_empresa,
				    bw_info_empresa.nombre_empresa,
				    bw_ordenes_compra.cod_proveedor,
				    bw_proveedores.nombre_empresa as nombre_proveedor,
				    bw_proveedores.nombre_contacto,
				    bw_proveedores.correo_contacto,
				    bw_proveedores.telefono_contacto,
				    DATE_FORMAT(STR_TO_DATE(bw_ordenes_compra.fecha_orden,'%Y-%m-%d'),'%m-%d-%Y') as fecha_orden,
				    bw_ordenes_compra.fecha_estimada_entrega,
				    bw_ordenes_compra.adjunto_orden_compra,
				    bw_ordenes_compra.num_orden_compra,
				    DATE_FORMAT(STR_TO_DATE(bw_ordenes_compra.fecha_recibido_pedido,'%Y-%m-%d'),'%m-%d-%Y') as fecha_recibido_pedido,
				    bw_ordenes_compra.observaciones,
				    bw_ordenes_compra.cod_estado_orden_compra,
				    ug_estados_ordenes_compra.estado,
				    bw_ordenes_compra.activo,
				    bw_ordenes_compra.user_insert,
				    CONCAT(usu_usuarios.nombre_1,
				            ' ',
				            usu_usuarios.apellido_1) AS nombre_usuario
				FROM
				    bw_ordenes_compra
				        INNER JOIN
				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_ordenes_compra.cod_info_empresa)
				        INNER JOIN
				    bw_proveedores ON (bw_proveedores.cod_proveedor = bw_ordenes_compra.cod_proveedor)
				        INNER JOIN
				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_ordenes_compra.user_insert)
				    	INNER JOIN
				    ug_estados_ordenes_compra ON (ug_estados_ordenes_compra.cod_estado_orden_compra = bw_ordenes_compra.cod_estado_orden_compra)
				WHERE
				    bw_ordenes_compra.activo = 1
				        AND bw_ordenes_compra.cod_orden = :cod_orden_compra
				ORDER BY bw_ordenes_compra.fecha_orden ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_orden_compra",  $cod_orden_compra);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Guarda una orden de compra
      */
	function inv_guardar_orden_compra(
		$codigo_orden_compra,
		$cod_info_empresa,
		$cod_proveedor,
		$fecha_orden,
		$fecha_recibido_pedido,
		$observaciones,
		$ext_adjunto,
		$flag_orden_compra,
		$num_orden_compra,
		$user_insert
	) {
		$SQL = "CALL inv_guardar_orden_compra(:codigo_orden_compra,
										:cod_info_empresa,
										:cod_proveedor,
										:fecha_orden,
										:fecha_recibido_pedido,
										:observaciones,
										:ext_adjunto,
										:flag_orden_compra,
										:num_orden_compra,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_orden_compra",  $codigo_orden_compra);
		$stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);
		$stmt->bindParam(":cod_proveedor",  $cod_proveedor);
		$stmt->bindParam(":fecha_orden",  $fecha_orden);
		$stmt->bindParam(":fecha_recibido_pedido",  $fecha_recibido_pedido);
		$stmt->bindParam(":observaciones",  $observaciones);
		$stmt->bindParam(":ext_adjunto",  $ext_adjunto);
		$stmt->bindParam(":flag_orden_compra",  $flag_orden_compra);
		$stmt->bindParam(":num_orden_compra",  $num_orden_compra);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Cambia el flag de activo de una orden de compra
      */
	function inv_cambiar_estado_orden_compra(
		$cod_orden_compra,
		$flag_activo,
		$user_insert
	) {
		$SQL = "CALL inv_cambiar_estado_orden_compra(:cod_orden_compra,
										:flag_activo,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_orden_compra",  $cod_orden_compra);
		$stmt->bindParam(":flag_activo",  $flag_activo);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*------------------------------------------------------------*/
	/*
	 * Funciones para obtener listado de productos de ordenes de compra
	 */
	function inv_listado_ordenes_compra_productos($cod_orden_compra)
	{
		$SQL = "SELECT
				    bw_detalle_ordenes_compra.cod_detalle,
				    bw_detalle_ordenes_compra.cod_orden,
				    bw_detalle_ordenes_compra.cod_detalle_producto,
				    bw_detalle_ordenes_compra.cantidad,
				    bw_detalle_ordenes_compra.activo,
				    bw_detalle_ordenes_compra.user_insert,
					bw_detalle_ordenes_compra.cod_tipo_semilla,
					bw_detalle_ordenes_compra.precio_semilla,
					bw_detalle_ordenes_compra.monto_pago,
					(bw_detalle_ordenes_compra.precio_semilla * bw_detalle_ordenes_compra.cantidad) as monto_pago_inmutable,
				    bw_detalle_productos_proveedores.flag_tipo_inventario,
				    bw_inventario_maquinaria.nombre_maquinaria,
				    bw_inventario_quimicos.nombre_quimico,
				    bw_inventario_maquinaria.codigo_maquinaria,
				    bw_inventario_quimicos.cod_quimico,
				    bw_inventario_quimicos.precio_quimico,
				    bw_inventario_semilla.codigo_semilla,
				    bw_inventario_semilla.nombre_semilla,
				    bw_inventario_semilla.precio_unidad,

                    ug_unidades_medida.cod_unidad_medida,
                    ug_unidades_medida.unidad_medida,

                    IFNULL(SUM(bw_detalle_entrega_ordenes_compra.cantidad),0) as cantidad_recibida,
					bw_inventario_tipo_semillas.tipo_semilla
				FROM
				    bw_detalle_ordenes_compra
				        INNER JOIN
				    bw_ordenes_compra ON (bw_ordenes_compra.cod_orden = bw_detalle_ordenes_compra.cod_orden)
				        INNER JOIN
				    bw_inventario_tipo_semillas ON (bw_inventario_tipo_semillas.cod_tipo_semilla = bw_detalle_ordenes_compra.cod_tipo_semilla)
				        INNER JOIN
				    bw_detalle_productos_proveedores ON (bw_detalle_productos_proveedores.cod_detalle = bw_detalle_ordenes_compra.cod_detalle_producto)
				        LEFT JOIN
				    bw_inventario_maquinaria ON (bw_inventario_maquinaria.cod_inventario = bw_detalle_productos_proveedores.cod_inventario
				        AND bw_detalle_productos_proveedores.flag_tipo_inventario = 0)
				        LEFT JOIN
				    bw_inventario_quimicos ON (bw_inventario_quimicos.cod_inventario = bw_detalle_productos_proveedores.cod_inventario
				        AND bw_detalle_productos_proveedores.flag_tipo_inventario = 1)
				        LEFT JOIN
				    bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_detalle_productos_proveedores.cod_inventario
				        AND bw_detalle_productos_proveedores.flag_tipo_inventario = 2)
						INNER JOIN
					ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_detalle_ordenes_compra.cod_unidad_medida)
						LEFT JOIN
					bw_detalle_entrega_ordenes_compra ON (bw_detalle_entrega_ordenes_compra.cod_detalle = bw_detalle_ordenes_compra.cod_detalle)
				WHERE
				    bw_detalle_ordenes_compra.cod_orden = :cod_orden_compra
				        AND bw_detalle_ordenes_compra.activo = 1
				GROUP BY bw_detalle_ordenes_compra.cod_detalle
				ORDER BY bw_detalle_ordenes_compra.date_insert ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_orden_compra",  $cod_orden_compra);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener información de un producto de una orden de compra
	 */
	function inv_obtener_info_producto_orden_compra($cod_producto)
	{
		$SQL = "SELECT
				    bw_detalle_ordenes_compra.cod_detalle,
				    bw_detalle_ordenes_compra.cod_orden,
				    bw_detalle_ordenes_compra.cod_detalle_producto,
				    bw_detalle_ordenes_compra.cantidad,
				    bw_detalle_ordenes_compra.activo,
				    bw_detalle_ordenes_compra.user_insert,
				    bw_detalle_productos_proveedores.flag_tipo_inventario,
				    bw_inventario_maquinaria.nombre_maquinaria,
				    bw_inventario_quimicos.nombre_quimico,
				    bw_inventario_maquinaria.codigo_maquinaria,
				    bw_inventario_quimicos.cod_quimico,
                    ug_unidades_medida.unidad_medida,
                    ug_unidades_medida.cod_unidad_medida
				FROM
				    bw_detalle_ordenes_compra
				        INNER JOIN
				    bw_ordenes_compra ON (bw_ordenes_compra.cod_orden = bw_detalle_ordenes_compra.cod_orden)
				        INNER JOIN
				    bw_detalle_productos_proveedores ON (bw_detalle_productos_proveedores.cod_detalle = bw_detalle_ordenes_compra.cod_detalle_producto)
				        LEFT JOIN
				    bw_inventario_maquinaria ON (bw_inventario_maquinaria.cod_inventario = bw_detalle_productos_proveedores.cod_inventario
				        AND bw_detalle_productos_proveedores.flag_tipo_inventario = 0)
				        LEFT JOIN
				    bw_inventario_quimicos ON (bw_inventario_quimicos.cod_inventario = bw_detalle_productos_proveedores.cod_inventario
				        AND bw_detalle_productos_proveedores.flag_tipo_inventario = 1)
						INNER JOIN
					ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_detalle_ordenes_compra.cod_unidad_medida)
				WHERE
				    bw_detalle_ordenes_compra.cod_detalle = :cod_producto
				        AND bw_detalle_ordenes_compra.activo = 1
				ORDER BY bw_detalle_ordenes_compra.date_insert ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_producto",  $cod_producto);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Guarda un producto de una orden de compra
      */
	function inv_guardar_producto_orden_compra(
		$codigo_orden_compra,
		$cod_producto,
		$cantidad,
		$cod_detalle,
		$cod_unidad_medida,
		$precio_semilla = 1,
		$cod_tipo_semilla = 1,
		$monto_pago = 1,
		$user_insert=1,
	) {
		$SQL = "CALL inv_guardar_producto_orden_compra(:codigo_orden_compra,
										:cod_producto,
										:cantidad,
										:cod_detalle,
										:cod_unidad_medida,
										:precio_semilla,
										:cod_tipo_semilla,
										:monto_pago,
                                		:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_orden_compra",  $codigo_orden_compra);
		$stmt->bindParam(":cod_producto",  $cod_producto);
		$stmt->bindParam(":cantidad",  $cantidad);
		$stmt->bindParam(":cod_detalle",  $cod_detalle);
		$stmt->bindParam(":cod_unidad_medida",  $cod_unidad_medida);
		$stmt->bindParam(":precio_semilla",  $precio_semilla);
		$stmt->bindParam(":cod_tipo_semilla",  $cod_tipo_semilla);
		$stmt->bindParam(":monto_pago",  $monto_pago);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Cambia el flag de activo de un producto de una orden de compra
      */
	function inv_cambiar_estado_producto_orden_compra(
		$cod_producto,
		$flag_activo,
		$user_insert
	) {
		$SQL = "CALL inv_cambiar_estado_producto_orden_compra(:cod_producto,
										:flag_activo,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_producto",  $cod_producto);
		$stmt->bindParam(":flag_activo",  $flag_activo);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de productos disponibles
	 */
	function inv_obtener_listado_productos_por_proveedor($cod_proveedor)
	{
		$SQL = "SELECT
					    bw_detalle_productos_proveedores.cod_detalle,
					    bw_detalle_productos_proveedores.cod_proveedor,
					    bw_detalle_productos_proveedores.cod_inventario,
					    bw_detalle_productos_proveedores.flag_tipo_inventario,
					    bw_detalle_productos_proveedores.activo,
					    bw_detalle_productos_proveedores.user_insert,
					    bw_inventario_semilla.precio_unidad,
					    IF(flag_tipo_inventario = 1,
					        bw_inventario_quimicos.nombre_quimico,
					        IF(flag_tipo_inventario = 2,
					            bw_inventario_semilla.nombre_semilla,
					            bw_inventario_maquinaria.nombre_maquinaria)) AS nombre_producto,
					    IF(flag_tipo_inventario = 1,
					        bw_inventario_quimicos.cod_quimico,
					        IF(flag_tipo_inventario = 2,
					            bw_inventario_semilla.codigo_semilla,
					            bw_inventario_maquinaria.codigo_maquinaria)) AS codigo_producto
					FROM
					    bw_detalle_productos_proveedores
					        LEFT JOIN
					    bw_inventario_quimicos ON (bw_inventario_quimicos.cod_inventario = bw_detalle_productos_proveedores.cod_inventario
					        AND bw_detalle_productos_proveedores.flag_tipo_inventario = 1
					        AND bw_inventario_quimicos.activo = 1)
					        LEFT JOIN
					    bw_inventario_maquinaria ON (bw_inventario_maquinaria.cod_inventario = bw_detalle_productos_proveedores.cod_inventario
					        AND bw_detalle_productos_proveedores.flag_tipo_inventario = 0
					        AND bw_inventario_maquinaria.activo = 1)
					        LEFT JOIN
					    bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_detalle_productos_proveedores.cod_inventario
					        AND bw_detalle_productos_proveedores.flag_tipo_inventario = 2
					        AND bw_inventario_semilla.activo = 1)
					WHERE
					    bw_detalle_productos_proveedores.cod_proveedor = :cod_proveedor
					        AND bw_detalle_productos_proveedores.activo = 1
					ORDER BY bw_detalle_productos_proveedores.flag_tipo_inventario , nombre_producto ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_proveedor",  $cod_proveedor);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de usuarios a notificar por ingreso de nueva orden de compra
	 */
	function inv_obtener_listado_usuarios_notificar_orden_compra($cod_info_empresa)
	{
		$SQL = "SELECT
					    bw_notificaciones_ordenes_compra.cod_notificacion,
					    bw_notificaciones_ordenes_compra.cod_usuario,
					    bw_notificaciones_ordenes_compra.activo,
					    CONCAT(usu_usuarios.nombre_1,
					            ' ',
					            usu_usuarios.apellido_1) AS nombre_usuario,
					    usu_usuarios.email
					FROM
					    bw_notificaciones_ordenes_compra
					        INNER JOIN
					    usu_usuarios ON (usu_usuarios.cod_usuario = bw_notificaciones_ordenes_compra.cod_usuario)
					WHERE
					    bw_notificaciones_ordenes_compra.cod_info_empresa = :cod_info_empresa
					        AND bw_notificaciones_ordenes_compra.activo = 1
					ORDER BY nombre_usuario ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de unidades de medida activos disponibles
	 */
	function inv_listado_unidades_medida_relacionadas($cod_unidad_medida)
	{
		$SQL = "SELECT
					    ug_unidades_medida.cod_unidad_medida,
					    ug_unidades_medida.unidad_medida,
					    ug_unidades_medida.abreviatura_medida,
					    ug_unidades_medida.activo
					FROM
					    ug_unidades_medida
					        INNER JOIN
					    bw_conversiones_unidades_medida ON (bw_conversiones_unidades_medida.cod_unidad_medida_destino = ug_unidades_medida.cod_unidad_medida
					        AND bw_conversiones_unidades_medida.cod_unidad_medida_origen = :cod_unidad_medida)
					ORDER BY unidad_medida ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_unidad_medida",  $cod_unidad_medida);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Función que permite obtener listado de inventario de quimicos con cantidad minima de alerta
	 */
	function inv_listado_inventario_quimico_cantidad_minima_alerta()
	{
		$SQL = "SELECT
					    bw_inventario_quimicos.cod_inventario,
					    bw_inventario_quimicos.cod_info_empresa,
					    bw_inventario_quimicos.cod_quimico,
					    bw_inventario_quimicos.nombre_quimico,
					    bw_inventario_quimicos.cantidad_quimico,
					    bw_inventario_quimicos.cantidad_minima_alerta
					FROM
					    bw_inventario_quimicos
					WHERE
					    bw_inventario_quimicos.cantidad_quimico <= bw_inventario_quimicos.cantidad_minima_alerta;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Función que permite obtener listado de inventario de quimicos con cantidad minima de alerta
	 */
	function usu_listado_usuarios_por_info_empresa($cod_info_empresa)
	{
		$SQL = "SELECT
				    bw_notificaciones_alerta_inventario.cod_notificacion,
				    bw_notificaciones_alerta_inventario.cod_info_empresa,
				    bw_notificaciones_alerta_inventario.cod_usuario,
				    CONCAT(usu_usuarios.nombre_1,
				            ' ',
				            usu_usuarios.apellido_1) AS nombre_usuario,
				    usu_usuarios.email,
				    bw_notificaciones_alerta_inventario.activo
				FROM
				    bw_notificaciones_alerta_inventario
				        INNER JOIN
				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_notificaciones_alerta_inventario.cod_usuario)
				WHERE
				    bw_notificaciones_alerta_inventario.cod_info_empresa = :cod_info_empresa
				ORDER BY nombre_usuario ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_info_empresa", $cod_info_empresa);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de maquinaria disponible por estado
	 */
	function inv_listado_maquinaria_por_tipo_aplicacion($cod_info_empresa, $cod_tipo_aplicacion)
	{
		$SQL = "SELECT
					    bw_inventario_maquinaria.cod_inventario,
					    bw_inventario_maquinaria.cod_info_empresa,
					    bw_info_empresa.nombre_empresa,
					    bw_inventario_maquinaria.codigo_maquinaria,
					    bw_inventario_maquinaria.nombre_maquinaria,
					    bw_inventario_maquinaria.cod_tipo_aplicacion,
					    bw_tipo_aplicacion_maquinaria.tipo_aplicacion,
					    bw_inventario_maquinaria.precio_unidad,
					    bw_inventario_maquinaria.anio_vencimiento,
				    	bw_inventario_maquinaria.cod_estado_plantacion,
					    bw_inventario_maquinaria.activo
					FROM
					    bw_inventario_maquinaria
					        INNER JOIN
					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_maquinaria.cod_info_empresa)
					        INNER JOIN
					    bw_tipo_aplicacion_maquinaria ON (bw_tipo_aplicacion_maquinaria.cod_tipo_aplicacion = bw_inventario_maquinaria.cod_tipo_aplicacion)
					    WHERE bw_inventario_maquinaria.cod_tipo_aplicacion = :cod_tipo_aplicacion
					    AND bw_inventario_maquinaria.cod_info_empresa = :cod_info_empresa
					ORDER BY bw_inventario_maquinaria.nombre_maquinaria ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_tipo_aplicacion",  $cod_tipo_aplicacion);
		$stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Permite copiar un inventario de semilla en otra finca/granja
      */
	function inv_copiar_inventario_semilla(
		$codigo_inventario_semilla,
		$cod_info_empresa,
		$user_insert
	) {
		$SQL = "CALL inv_copiar_inventario_semilla(:codigo_inventario_semilla,
										:cod_info_empresa,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_inventario_semilla",  $codigo_inventario_semilla);
		$stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Permite copiar un inventario de quimico en otra finca/granja
      */
	function inv_copiar_inventario_quimico(
		$codigo_inventario_quimico,
		$cod_info_empresa,
		$user_insert
	) {
		$SQL = "CALL inv_copiar_inventario_quimico(:codigo_inventario_quimico,
										:cod_info_empresa,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_inventario_quimico",  $codigo_inventario_quimico);
		$stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Guarda la cantidad a sumar o restar de un químico
      */
	function inv_guardar_inventario_quimico_sumar_restar(
		$codigo_inventario_quimico,
		$fecha_sumar_restar,
		$cantidad_sumar,
		$cantidad_restar,
		$razon_sumar_restar,
		$user_insert
	) {
		$SQL = "CALL inv_guardar_inventario_quimico_sumar_restar(:codigo_inventario_quimico,
										:fecha_sumar_restar,
										:cantidad_sumar,
										:cantidad_restar,
										:razon_sumar_restar,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_inventario_quimico",  $codigo_inventario_quimico);
		$stmt->bindParam(":fecha_sumar_restar",  $fecha_sumar_restar);
		$stmt->bindParam(":cantidad_sumar",  $cantidad_sumar);
		$stmt->bindParam(":cantidad_restar",  $cantidad_restar);
		$stmt->bindParam(":razon_sumar_restar",  $razon_sumar_restar);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Guarda la cantidad a sumar o restar de una semilla
      */
	function inv_guardar_inventario_semilla_sumar_restar(
		$codigo_inventario_semilla,
		$fecha_sumar_restar,
		$cantidad_sumar,
		$cantidad_restar,
		$razon_sumar_restar,
		$user_insert
	) {
		$SQL = "CALL inv_guardar_inventario_semilla_sumar_restar(:codigo_inventario_semilla,
										:fecha_sumar_restar,
										:cantidad_sumar,
										:cantidad_restar,
										:razon_sumar_restar,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_inventario_semilla",  $codigo_inventario_semilla);
		$stmt->bindParam(":fecha_sumar_restar",  $fecha_sumar_restar);
		$stmt->bindParam(":cantidad_sumar",  $cantidad_sumar);
		$stmt->bindParam(":cantidad_restar",  $cantidad_restar);
		$stmt->bindParam(":razon_sumar_restar",  $razon_sumar_restar);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Guarda la cantidad recibida de un producto de una orden de compra
      */
	function inv_guardar_producto_recibido_orden_compra(
		$codigo_orden_compra,
		$cod_detalle,
		$cod_producto_recibido,
		$cod_unidad_medida_recibido,
		$cantidad_recibido,
		$fecha_entrega_recibido,
		$observaciones_recibido,
		$user_insert
	) {
		$SQL = "CALL inv_guardar_producto_recibido_orden_compra(:codigo_orden_compra,
										:cod_detalle,
										:cod_producto_recibido,
										:cod_unidad_medida_recibido,
										:cantidad_recibido,
										:fecha_entrega_recibido,
										:observaciones_recibido,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_orden_compra",  $codigo_orden_compra);
		$stmt->bindParam(":cod_detalle",  $cod_detalle);
		$stmt->bindParam(":cod_producto_recibido",  $cod_producto_recibido);
		$stmt->bindParam(":cod_unidad_medida_recibido",  $cod_unidad_medida_recibido);
		$stmt->bindParam(":cantidad_recibido",  $cantidad_recibido);
		$stmt->bindParam(":fecha_entrega_recibido",  $fecha_entrega_recibido);
		$stmt->bindParam(":observaciones_recibido",  $observaciones_recibido);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado del detalle de entrega de productos de ordenes de compra
	 */
	function inv_cargar_detalle_entrega_producto($cod_detalle)
	{
		$SQL = "SELECT 
				    cod_entrega,
				    cantidad,
				    ug_unidades_medida.cod_unidad_medida,
				    ug_unidades_medida.unidad_medida,
				    fecha_entrega,
				    observaciones,
				    bw_detalle_entrega_ordenes_compra.activo,
				    bw_detalle_entrega_ordenes_compra.user_insert,
				    bw_detalle_entrega_ordenes_compra.date_insert
				FROM
				    bw_detalle_entrega_ordenes_compra
				        INNER JOIN
				    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_detalle_entrega_ordenes_compra.cod_unidad_medida)
				WHERE
				    bw_detalle_entrega_ordenes_compra.cod_detalle = :cod_detalle
				ORDER BY bw_detalle_entrega_ordenes_compra.date_insert ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_detalle",  $cod_detalle);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	// NUEVO
	/*
	 * Funciones para obtener listado de todas las categorías activas de semillas
	 */
	function inv_listado_categorias_activas()
	{
		$SQL = "SELECT
						bw_inventario_categorias_semillas.cod_categoria,
						bw_inventario_categorias_semillas.nombre as nombre_categoria,
						bw_inventario_categorias_semillas.descripcion
				FROM
						bw_inventario_categorias_semillas
				WHERE
						bw_inventario_categorias_semillas.activo = 1
				ORDER BY
					bw_inventario_categorias_semillas.nombre ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de todos los grupos de siembras que esten activos
	 */
	function inv_listado_grupo_de_siembra_activos()
	{
		$SQL = "SELECT
						bw_inventario_grupos_siembra.cod_grupo_siembra,
						bw_inventario_grupos_siembra.nombre as nombre_grupo_siembra,
						bw_inventario_grupos_siembra.descripcion
				FROM
						bw_inventario_grupos_siembra
				WHERE
						bw_inventario_grupos_siembra.activo = 1
				ORDER BY
					bw_inventario_grupos_siembra.nombre ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de todos los rasgos de semillas activos
	 */
	function inv_listado_rasgo_activos()
	{
		$SQL = "SELECT
						bw_inventario_rasgos.cod_rasgo,
						bw_inventario_rasgos.nombre as nombre_rasgo,
						bw_inventario_rasgos.descripcion
				FROM
						bw_inventario_rasgos
				WHERE
						bw_inventario_rasgos.activo = 1
				ORDER BY
					bw_inventario_rasgos.nombre ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de los tipos de semillas
	 */
	function inv_listado_tipo_semillas()
	{
		$SQL = "SELECT
					cod_tipo_semilla,
					tipo_semilla as nombre_tipo_semilla
				FROM
					bw_inventario_tipo_semillas
				WHERE
					activo = 1
				ORDER BY
					cod_tipo_semilla ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de todos los rasgos de semillas activos
	 */
	function inv_listado_familia_de_semillas_activas()
	{
		$SQL = "SELECT
						bw_inventario_familias_semillas.cod_familia,
						bw_inventario_familias_semillas.nombre as nombre_familia,
						bw_inventario_familias_semillas.descripcion
				FROM
						bw_inventario_familias_semillas
				WHERE
						bw_inventario_familias_semillas.activo = 1
				ORDER BY
					bw_inventario_familias_semillas.nombre ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de proveedores disponibles
	 */
	function inv_listado_proveedores_activos()
	{
		$SQL = "SELECT
					bw_proveedores.cod_proveedor,
					bw_proveedores.cod_info_empresa,
					bw_info_empresa.nombre_empresa AS nombre_finca,
					bw_proveedores.nombre_empresa,
					bw_proveedores.nombre_contacto,
					bw_proveedores.correo_contacto,
					bw_proveedores.telefono_contacto,
					bw_proveedores.observaciones,
					bw_proveedores.activo
				FROM
					bw_proveedores
						INNER JOIN
					bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_proveedores.cod_info_empresa)
					ORDER BY bw_proveedores.nombre_empresa , bw_proveedores.nombre_contacto ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	// ***************************************************************
	// FUNCIONES PARA EL MÓDULO DE CATEGORÍAS
	/*
	 * Funciones para obtener la información de una categoría de semilla
	 */
	function inv_obtener_info_categoria_simillas($cod_categoria)
	{
		$SQL = "SELECT
					cod_categoria, nombre,descripcion, activo, cantidadDiasEspera
				FROM
					bw_inventario_categorias_semillas
				WHERE
					bw_inventario_categorias_semillas.cod_categoria = :cod_categoria;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_categoria",  $cod_categoria);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de categorias de semillas disponibles
	 */
	function inv_listado_categorias_semillas()
	{
		$SQL = "SELECT
					cod_categoria, nombre,descripcion, activo, cantidadDiasEspera
				FROM
					bw_inventario_categorias_semillas
				ORDER BY nombre ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Guarda una Categoría de semillas
      */
	function inv_guardar_categoria_semillas(
		$codigo_categoria,
		$nombre,
		$descripcion,
		$activo,
		$cantidadDiasEspera,
		$user_insert
	) {
		$SQL = "CALL inv_guardar_categoria_semillas(:codigo_categoria,
									:nombre,
									:descripcion,
									:activo,
									:cantidadDiasEspera,
									:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		// $codigo_categoria = 12;
		$stmt->bindParam(":codigo_categoria", 	$codigo_categoria);
		$stmt->bindParam(":nombre",  			$nombre);
		$stmt->bindParam(":descripcion",  		$descripcion);
		$stmt->bindParam(":activo",  			$activo);
		$stmt->bindParam(":cantidadDiasEspera", $cantidadDiasEspera);
		$stmt->bindParam(":user_insert",  		$user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Cambia el flag de activo de una categoría especifica
      */
	function inv_cambiar_estado_categoria_semillas(
		$cod_categoria,
		$flag_activo,
		$user_insert
	) {
		$SQL = "CALL inv_cambiar_estado_categoria_semillas(:cod_categoria,
										:flag_activo,
                                		:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_categoria",  $cod_categoria);
		$stmt->bindParam(":flag_activo",  $flag_activo);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	// ***************************************************************
	// FUNCIONES PARA EL MÓDULO DE RASGOS
	/*
	 * Funciones para obtener la información de un rasgo especifico
	 */
	function inv_obtener_info_rasgo($cod_rasgo)
	{
		$SQL = "SELECT
					cod_rasgo, nombre, descripcion, activo
				FROM
					bw_inventario_rasgos
				WHERE
					bw_inventario_rasgos.cod_rasgo = :cod_rasgo;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_rasgo",  $cod_rasgo);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de rasgos disponibles
	 */
	function inv_listado_ragos()
	{
		$SQL = "SELECT
					cod_rasgo, nombre,descripcion, activo
				FROM
					bw_inventario_rasgos
				ORDER BY nombre ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Guarda una Categoría de semillas
      */
	function inv_guardar_rasgos(
		$codigo_rasgo,
		$nombre,
		$descripcion,
		$activo,
		$user_insert
	) {
		$SQL = "CALL inv_guardar_rasgo(:codigo_rasgo,
									:nombre,
									:descripcion,
									:activo,
									:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		// $codigo_rasgo = 12;
		$stmt->bindParam(":codigo_rasgo",     $codigo_rasgo);
		$stmt->bindParam(":nombre",              $nombre);
		$stmt->bindParam(":descripcion",          $descripcion);
		$stmt->bindParam(":activo",              $activo);
		$stmt->bindParam(":user_insert",          $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Cambia el flag de activo de una categoría especifica
      */
	function inv_cambiar_estado_rasgo(
		$cod_rasgo,
		$flag_activo,
		$user_insert
	) {
		$SQL = "CALL inv_cambiar_estado_rasgo(:cod_rasgo,
							:flag_activo,
							:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_rasgo",  $cod_rasgo);
		$stmt->bindParam(":flag_activo",  $flag_activo);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	// ***************************************************************
	// FUNCIONES PARA EL MÓDULO DE FAMILIAS
	/*
	 * Funciones para obtener la información de una familia especifica
	 */
	function inv_obtener_info_familia($cod_familia)
	{
		$SQL = "SELECT
					cod_familia, nombre, descripcion, activo
				FROM
					bw_inventario_familias_semillas
				WHERE
					bw_inventario_familias_semillas.cod_familia = :cod_familia;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_familia",  $cod_familia);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de familias disponibles
	 */
	function inv_listado_familias()
	{
		$SQL = "SELECT
					cod_familia, nombre,descripcion, activo
				FROM
					bw_inventario_familias_semillas
				ORDER BY nombre ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Guarda una Familia
      */
	function inv_guardar_familias(
		$codigo_familia,
		$nombre,
		$descripcion,
		$activo,
		$user_insert
	) {
		$SQL = "CALL inv_guardar_familia(:codigo_familia,
									:nombre,
									:descripcion,
									:activo,
									:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		// $codigo_familia = 12;
		$stmt->bindParam(":codigo_familia",     $codigo_familia);
		$stmt->bindParam(":nombre",              $nombre);
		$stmt->bindParam(":descripcion",          $descripcion);
		$stmt->bindParam(":activo",              $activo);
		$stmt->bindParam(":user_insert",          $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Cambia el flag de activo de una familia
      */
	function inv_cambiar_estado_familia(
		$cod_familia,
		$flag_activo,
		$user_insert
	) {
		$SQL = "CALL inv_cambiar_estado_familia(:cod_familia,
							:flag_activo,
							:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_familia",  $cod_familia);
		$stmt->bindParam(":flag_activo",  $flag_activo);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	// ***************************************************************
	// FUNCIONES PARA EL MÓDULO DE GRUPO DE SIEMBRAS
	/*
	 * Funciones para obtener la información de un grupo de siembra especifico
	 */
	function inv_obtener_info_grupo_de_siembra($cod_grupo_siembra)
	{
		$SQL = "SELECT
						cod_grupo_siembra, nombre, descripcion, activo
					FROM
						bw_inventario_grupos_siembra
					WHERE
						bw_inventario_grupos_siembra.cod_grupo_siembra = :cod_grupo_siembra;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_grupo_siembra",  $cod_grupo_siembra);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Funciones para obtener listado de familias disponibles
	*/
	function inv_listado_grupos_de_siembra()
	{
		$SQL = "SELECT
						cod_grupo_siembra, nombre,descripcion, activo
					FROM
						bw_inventario_grupos_siembra
					ORDER BY nombre ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Guarda un Grupo de Siembra
	*/
	function inv_guardar_grupo_de_siembra(
		$codigo_grupo_siembra,
		$nombre,
		$descripcion,
		$activo,
		$user_insert
	) {
		$SQL = "CALL inv_guardar_grupo_siembra(:codigo_grupo_siembra,
										:nombre,
										:descripcion,
										:activo,
										:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		// $codigo_grupo_siembra = 12;
		$stmt->bindParam(":codigo_grupo_siembra",     $codigo_grupo_siembra);
		$stmt->bindParam(":nombre",              $nombre);
		$stmt->bindParam(":descripcion",          $descripcion);
		$stmt->bindParam(":activo",              $activo);
		$stmt->bindParam(":user_insert",          $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Cambia el flag de activo de un Grupo de Siembra
	*/
	function inv_cambiar_estado_grupo_de_siembra(
		$cod_grupo_siembra,
		$flag_activo,
		$user_insert
	) {
		$SQL = "CALL inv_cambiar_estado_grupo_de_siembra(:cod_grupo_siembra,
								:flag_activo,
								:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_grupo_siembra",  $cod_grupo_siembra);
		$stmt->bindParam(":flag_activo",  $flag_activo);
		$stmt->bindParam(":user_insert",  $user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_listado_semillas_por_granjas_filtrado_por_fecha(

		$cod_info_empresa,
		$cod_variedad,
		$flag_activacion
	) {

		$this->deshabilitarGrupoFull();
		$condiciones = '';
		if ($flag_activacion == 2) {

			$condiciones .= " (bw_inventario_semilla.activo = 1 OR bw_inventario_semilla.activo = 0) ";
		} else {

			$condiciones .= " bw_inventario_semilla.activo = $flag_activacion ";
		}
		if ($cod_info_empresa != null && $cod_info_empresa != '') {

			$condiciones .= " AND bw_info_empresa.cod_info_empresa IN ($cod_info_empresa) ";
		}
		if ($cod_variedad != null && $cod_variedad != '') {

			$condiciones .= " AND bw_inventario_semilla.cod_variedad IN ($cod_variedad) ";
		}

		$SQL = "SELECT
					bw_inventario_semilla.cod_inventario,
					bw_inventario_semilla.cod_info_empresa,
					bw_inventario_semilla.codigo_semilla,
					bw_inventario_semilla.nombre_semilla,
					bw_inventario_semilla.abreviatura_semilla,
					bw_inventario_semilla.cantidad_semilla,
					bw_inventario_semilla.semilla_activa,
					bw_inventario_semilla.activo,
					bw_sembradores.cod_sembrador,
					bw_sembradores.nombre_contacto as nombre_greenhouse,
					bw_info_empresa.nombre_empresa,
					bw_variedad_sembradora.variedad_producto,
					ug_unidades_medida.unidad_medida,
					SUM(bw_inventario_semillas_por_empresas.cantidad_semilla) as suma_cantidad_semillas_individual,
					GROUP_CONCAT(CONCAT('<span class=\"fecha_empresa_actualizacion_',
									bw_inventario_semillas_por_empresas.cod_empresa,
									'\">',
									DATE_FORMAT(bw_inventario_semillas_por_empresas.ultima_actualizacion,
											'%Y/%m/%d')),
							'</br>',
							'</span>'
							SEPARATOR '') AS fechas_de_actualizaciones,
					GROUP_CONCAT(
						CONCAT(
							'<span id=cant_empresa_unico_',bw_inventario_semillas_por_empresas.cod_inventario,'_',bw_inventario_semillas_por_empresas.cod_empresa,'>',
							bw_info_empresa.nombre_empresa,
							' -  <span class=\"numerico\">',
							FORMAT(bw_inventario_semillas_por_empresas.cantidad_semilla,0,'es_MX'),'</span>'
						),'</br>','</span>' SEPARATOR ''
					) AS nombres_empresas_vinculadas
				FROM
						bw_inventario_semilla
						INNER JOIN
						bw_inventario_semillas_por_empresas ON (bw_inventario_semillas_por_empresas.cod_inventario_semilla = bw_inventario_semilla.cod_inventario)
						INNER JOIN bw_info_empresa ON (
							bw_info_empresa.cod_info_empresa = bw_inventario_semillas_por_empresas.cod_empresa
						)
					INNER JOIN ug_unidades_medida
						ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_semilla.cod_unidad_medida)
					INNER JOIN bw_variedad_sembradora
						ON (bw_variedad_sembradora.cod_variedad = bw_inventario_semilla.cod_variedad)
					INNER JOIN bw_sembradores
						ON(bw_sembradores.cod_sembrador = bw_inventario_semilla.cod_sembrador)
					
				WHERE
					" . $condiciones . "
				GROUP BY
					bw_inventario_semilla.nombre_semilla
				ORDER BY
					bw_inventario_semilla.nombre_semilla ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		// $stmt->bindParam(":fechaInicial",  $fechaInicial);
		// $stmt->bindParam(":fechaFinal",  $fechaFinal);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();

		$this->habilitarGrupoFull();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_listado_de_ordenes_de_compra_semillas(
		$fechaInicial,
		$fechaFinal,
		$cod_info_empresa,
		$cod_variedad,
		$flag_activacion
	) {
		$condiciones = '';
		if ($cod_info_empresa != null && $cod_info_empresa != '') {

			$condiciones .= " AND bw_info_empresa.cod_info_empresa IN ($cod_info_empresa) ";
		}
		if ($cod_variedad != null && $cod_variedad != '') {

			$condiciones .= " AND bw_inventario_semilla.cod_variedad IN ($cod_variedad) ";
		}

		$condiciones .= " AND bw_inventario_semilla.activo = $flag_activacion ";
		// TODO: ACTUALIZAR LAS REFERENCIAS DE ESTRACCIÓN DE INFORMACIÓN
		$SQL = "SELECT
		bw_inventario_semilla.cod_inventario,
		bw_inventario_semilla.cod_info_empresa,
		bw_inventario_semilla.codigo_semilla,
		bw_inventario_semilla.nombre_semilla,
		bw_inventario_semilla.cod_variedad,
		bw_inventario_semilla.abreviatura_semilla,
		bw_inventario_semilla.cantidad_semilla, -- Agregar función de agregación
		bw_inventario_semilla.cod_unidad_medida,
		SUM(bw_inventario_semilla.cantidad_fisica_semilla) AS suma_cantidad_fisica_semilla, -- Agregar función de agregación
		SUM(bw_inventario_semilla.precio_unidad) AS suma_precio_unidad, -- Agregar función de agregación
		bw_inventario_semilla.numero_lote,
		bw_inventario_semilla.cod_categoria,
		bw_inventario_semilla.cod_grupo_siembra,
		bw_inventario_semilla.cod_rasgo,
		SUM(bw_inventario_semilla.plants_acre) AS suma_plants_acre, -- Agregar función de agregación
		bw_inventario_semilla.cod_familia,
		bw_inventario_semilla.red_zone,
		bw_inventario_semilla.over_seed,
		SUM(bw_inventario_semilla.semillas_por_plantaciones) AS suma_semillas_por_plantaciones, -- Agregar función de agregación
		bw_inventario_semilla.paletizado,
		bw_inventario_semilla.semilla_activa,
		bw_inventario_semilla.activo,
		bw_info_empresa.nombre_empresa,
		bw_variedad_sembradora.variedad_producto,
		ug_unidades_medida.unidad_medida
	FROM
		bw_detalle_entrega_ordenes_compra
		INNER JOIN bw_detalle_ordenes_compra ON (
			bw_detalle_ordenes_compra.cod_detalle = bw_detalle_entrega_ordenes_compra.cod_detalle
		)
		INNER JOIN bw_detalle_productos_proveedores ON (
			bw_detalle_productos_proveedores.cod_detalle = bw_detalle_ordenes_compra.cod_detalle_producto
		)
		INNER JOIN bw_inventario_semilla ON(
			bw_inventario_semilla.cod_inventario = bw_detalle_productos_proveedores.cod_inventario
		)
		INNER JOIN bw_info_empresa ON (
			bw_info_empresa.cod_info_empresa = bw_inventario_semilla.cod_info_empresa
		)
		INNER JOIN bw_variedad_sembradora ON (
			bw_variedad_sembradora.cod_variedad = bw_inventario_semilla.cod_variedad
		)
		INNER JOIN ug_unidades_medida ON (
			ug_unidades_medida.cod_unidad_medida = bw_inventario_semilla.cod_unidad_medida
		)
	WHERE
		bw_inventario_semilla.activo  = 1
		" . $condiciones . "
	GROUP BY
		bw_inventario_semilla.cod_inventario,
		bw_inventario_semilla.cod_info_empresa,
		bw_inventario_semilla.codigo_semilla,
		bw_inventario_semilla.nombre_semilla,
		bw_inventario_semilla.cod_variedad,
		bw_inventario_semilla.abreviatura_semilla,
		bw_inventario_semilla.cod_unidad_medida,
		bw_inventario_semilla.numero_lote,
		bw_inventario_semilla.cod_categoria,
		bw_inventario_semilla.cod_grupo_siembra,
		bw_inventario_semilla.cod_rasgo,
		bw_inventario_semilla.cod_familia,
		bw_inventario_semilla.red_zone,
		bw_inventario_semilla.over_seed,
		bw_inventario_semilla.paletizado,
		bw_inventario_semilla.semilla_activa,
		bw_inventario_semilla.activo,
		bw_info_empresa.nombre_empresa,
		bw_variedad_sembradora.variedad_producto,
		ug_unidades_medida.unidad_medida
	ORDER BY
		bw_inventario_semilla.cod_inventario;";
		$stmt = $this->db_conexion->prepare($SQL);
		// $stmt->bindParam(":fechaInicial",  $fechaInicial);
		// $stmt->bindParam(":fechaFinal",  $fechaFinal);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	// ***************************************************************
	// FUNCIONES PARA EL MÓDULO DE NUEVAS PLANTACIONES
	/*
	* Funciones para obtener listado de todos los sembradores activos
	 */
	function inv_listado_sembradores()
	{
		$SQL = "SELECT
					cod_sembrador, nombre_contacto as nombre, telefono_contacto, correo_contacto
				FROM
					bw_sembradores
				WHERE
					activo = 1
				ORDER BY nombre_contacto ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_listado_semillas_activas()
	{
		$SQL = "SELECT
					bw_inventario_semilla.cod_inventario,
					bw_inventario_semilla.codigo_semilla,
					bw_inventario_semilla.cod_unidad_medida,
					bw_inventario_semilla.nombre_semilla AS nombre,
					bw_inventario_semilla.cantidad_semilla,
					bw_inventario_semilla.semillas_por_plantaciones,
					ug_unidades_medida.abreviatura_medida,
					ug_unidades_medida.unidad_medida
				FROM
					bw_inventario_semilla
						INNER JOIN
					ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_semilla.cod_unidad_medida)
				WHERE
					bw_inventario_semilla.activo = 1
				ORDER BY bw_inventario_semilla.nombre_semilla ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_listado_semillas_por_invernadero_activas($cod_info_empresa)
	{
		$SQL = "SELECT
					bw_inventario_semilla.cod_inventario,
					bw_inventario_semilla.codigo_semilla,
					bw_inventario_semilla.cod_unidad_medida,
					bw_inventario_semilla.nombre_semilla AS nombre,
					bw_inventario_semilla.cantidad_semilla as cantidad_semillas_de_inventario,
					bw_inventario_semilla.semillas_por_plantaciones,
					bw_inventario_semilla.over_seed,
					ug_unidades_medida.abreviatura_medida,
					ug_unidades_medida.unidad_medida,
					bw_inventario_semillas_por_empresas.cantidad_semilla
				FROM
					bw_inventario_semilla
						INNER JOIN
					ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_semilla.cod_unidad_medida)
						INNER JOIN
					bw_inventario_semillas_por_empresas ON (bw_inventario_semillas_por_empresas.cod_inventario_semilla = bw_inventario_semilla.cod_inventario)
				WHERE
					bw_inventario_semilla.activo = 1
				AND
				bw_inventario_semillas_por_empresas.cod_empresa = :cod_info_empresa
				ORDER BY bw_inventario_semilla.nombre_semilla ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_guardar_plantacion(
		$cod_inventario,
		$numero_orden,
		$cod_info_empresa,
		$cantidad,
		$overseed,
		$total,
		$per_planting,
		$fecha_de_orden,
		$item,
		$fecha_inicial,
		$fecha_final,
		$edad,
		$cod_estado,
		$user_insert
	) {
		$SQL = "INSERT
				INTO bw_inventario_plantaciones
					(
					cod_inventario,
					numero_orden,
					cod_info_empresa,
					cantidad,
					overseed,
					total,
					per_planting,
					fecha_de_orden,
					item,
					fecha_inicial,
					fecha_final,
					edad,
					cod_estado,
					user_insert
					)
				VALUES
					(
					:cod_inventario,
					:numero_orden,
					:cod_info_empresa,
					:cantidad,
					:overseed,
					:total,
					:per_planting,
					:fecha_de_orden,
					:item,
					:fecha_inicial,
					:fecha_final,
					:edad,
					:cod_estado,
					:user_insert
					);";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_inventario", $cod_inventario);
		$stmt->bindParam(":numero_orden", $numero_orden);
		$stmt->bindParam(":cod_info_empresa", $cod_info_empresa);
		$stmt->bindParam(":cantidad", $cantidad);
		$stmt->bindParam(":overseed", $overseed);
		$stmt->bindParam(":total", $total);
		$stmt->bindParam(":per_planting", $per_planting);
		$stmt->bindParam(":fecha_de_orden", $fecha_de_orden);
		$stmt->bindParam(":item", $item);
		$stmt->bindParam(":fecha_inicial", $fecha_inicial);
		$stmt->bindParam(":fecha_final", $fecha_final);
		$stmt->bindParam(":edad", $edad);
		$stmt->bindParam(":cod_estado", $cod_estado);
		$stmt->bindParam(":user_insert", $user_insert);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|It has been entered successfully.|" . $this->db_conexion->lastInsertId();
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_buscar_cod_plantacion_por_numero_de_orden(
		$numero_orden
	) {
		$SQL = "SELECT cod_plantacion FROM  bw_inventario_plantaciones where numero_orden = :numero_orden;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":numero_orden", $numero_orden);


		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*
	 * Funciones para obtener listado de las plantaciones
	 */
	function inv_buscar_plantacion_por_numero_orden(
		$numero_orden
	) {
		$SQL = "SELECT 
				cod_plantacion, cod_inventario, numero_orden, fecha_inicial, fecha_final, cod_inventario, edad
			FROM
				bw_inventario_plantaciones
			WHERE
				numero_orden = :numero_orden
				ORDER BY
					numero_orden,
					cod_inventario,
					fecha_final ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":numero_orden", $numero_orden);


		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*
	 * Funciones para obtener listado de las plantaciones
	 */
	function inv_listado_plantacion_para_actualizar_edad()
	{
		$SQL = "SELECT
				cod_plantacion,
				cod_inventario,
				numero_orden,
				fecha_inicial,
				fecha_final,
				cod_inventario
			FROM
				bw_inventario_plantaciones
				ORDER BY
					numero_orden,
					cod_inventario,
					fecha_inicial ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*
	 * Funciones para obtener listado de las plantaciones
	 */
	function inv_guardar_edad_plantacion(
		$cod_plantacion,
		$edad
	) {
		$SQL = "UPDATE
					bw_inventario_plantaciones
				SET
					edad = :edad
				WHERE
					cod_plantacion = :cod_plantacion;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_plantacion", $cod_plantacion);
		$stmt->bindParam(":edad", $edad);


		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*
	 * Funciones para obtener listado de las plantaciones
	 */
	function inv_guardar_edad_plantacion_en_registro_permanente(
		$cod_plantacion,
		$edad
	) {
		$SQL = "UPDATE
					far_crop_registro_semillas_implementadas
				SET
					edad_semilla_en_plantacion = :edad
				WHERE
					cod_plantacion = :cod_plantacion;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_plantacion", $cod_plantacion);
		$stmt->bindParam(":edad", $edad);


		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_eliminar_plantacion(
		$cod_plantacion

	) {
		$SQL = "DELETE FROM bw_inventario_plantaciones WHERE (cod_plantacion = :cod_plantacion);";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_plantacion", $cod_plantacion);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|It has been successfully removed.";
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_actualizar_plantacion(
		$numero_orden_previo,
		$cod_plantacion,
		$cod_inventario,
		$numero_orden,
		$cod_info_empresa,
		$cantidad,
		$overseed,
		$total,
		$per_planting,
		$fecha_de_orden,
		$item,
		$fecha_inicial,
		$fecha_final,
		$edad,
		$cod_estado
	) {
		$SQL = "UPDATE
					bw_inventario_plantaciones
				SET
					cantidad = :cantidad,
					overseed = :overseed,
					total = :total,
					per_planting = :per_planting,
					item = :item,
					fecha_inicial = :fecha_inicial,
					fecha_final = :fecha_final,
					edad = :edad,
					cod_estado = :cod_estado
				WHERE
					numero_orden = :numero_orden_previo
					AND cod_inventario = :cod_inventario
					AND cod_plantacion = :cod_plantacion;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cantidad", $cantidad);
		$stmt->bindParam(":overseed", $overseed);
		$stmt->bindParam(":total", $total);
		$stmt->bindParam(":per_planting", $per_planting);
		$stmt->bindParam(":item", $item);
		$stmt->bindParam(":fecha_inicial", $fecha_inicial);
		$stmt->bindParam(":fecha_final", $fecha_final);
		$stmt->bindParam(":edad", $edad);
		$stmt->bindParam(":cod_estado", $cod_estado);
		$stmt->bindParam(":numero_orden_previo", $numero_orden_previo);
		$stmt->bindParam(":cod_inventario", $cod_inventario);
		$stmt->bindParam(":cod_plantacion", $cod_plantacion);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|It has been update successfully.|" . $this->db_conexion->lastInsertId();
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para buscar un producto especifico de una plantacion
	 */
	function inv_buscar_producto_en_plantacion(
		$numero_orden,
		$cod_inventario,
		$cod_plantacion
	) {
		$SQL = "SELECT
					bw_inventario_plantaciones.cod_plantacion,
					bw_inventario_plantaciones.cod_plantacion as cantidad
				FROM
					bw_inventario_plantaciones
				WHERE
					bw_inventario_plantaciones.numero_orden = :numero_orden
					AND bw_inventario_plantaciones.cod_inventario = :cod_inventario 
					AND bw_inventario_plantaciones.cod_plantacion = :cod_plantacion;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_inventario", $cod_inventario);
		$stmt->bindParam(":numero_orden", $numero_orden);
		$stmt->bindParam(":cod_plantacion", $cod_plantacion);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para buscar un producto especifico de una plantacion
	 */
	function inv_datos_plantancion(
		$cod_plantacion
	) {
		$SQL = "SELECT
					bw_inventario_plantaciones.cod_plantacion,
					bw_inventario_plantaciones.total
				FROM
					bw_inventario_plantaciones
				WHERE
				bw_inventario_plantaciones.cod_plantacion = :cod_plantacion;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_plantacion", $cod_plantacion);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para buscar una plantacion especifica
	 */
	function inv_borrar_plantacion(
		$numero_orden,
		$cod_inventario,
		$cod_plantacion
	) {
		$SQL = "DELETE
					FROM bw_inventario_plantaciones
				WHERE
					bw_inventario_plantaciones.numero_orden = :numero_orden
					AND bw_inventario_plantaciones.cod_inventario = :cod_inventario
					AND bw_inventario_plantaciones.cod_plantacion = :cod_plantacion;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_inventario", $cod_inventario);
		$stmt->bindParam(":numero_orden", $numero_orden);
		$stmt->bindParam(":cod_plantacion", $cod_plantacion);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|The product has been successfully deleted.";
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_buscar_plantacion_por_numero_de_orden($numero_orden)
	{
		$SQL = "SELECT
						bw_inventario_plantaciones.cod_plantacion,
						bw_inventario_plantaciones.cod_inventario,
						bw_inventario_plantaciones.cod_estado,
						bw_inventario_plantaciones.cod_info_empresa,
						bw_inventario_plantaciones.numero_orden,
						bw_inventario_plantaciones.cantidad,
						bw_inventario_plantaciones.overseed,
						bw_inventario_plantaciones.total,
						bw_inventario_plantaciones.per_planting,
						DATE_FORMAT(bw_inventario_plantaciones.fecha_de_orden, '%m-%d-%Y') as fecha_de_orden,
						bw_inventario_plantaciones.item,
						DATE_FORMAT(bw_inventario_plantaciones.fecha_inicial, '%m-%d-%Y') as fecha_inicial,
						DATE_FORMAT(bw_inventario_plantaciones.fecha_final, '%m-%d-%Y') as fecha_final,
            bw_inventario_plantaciones.edad,
						bw_info_empresa.nombre_empresa,
						bw_inventario_semilla.nombre_semilla,
						bw_inventario_semilla.cantidad_semilla,
						CONCAT(bw_inventario_semilla.nombre_semilla, ' ', bw_inventario_semilla.cantidad_semilla, ' Units') AS datos_semillas
				FROM
					bw_inventario_plantaciones
				INNER JOIN
					bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_inventario_plantaciones.cod_inventario)
				INNER JOIN
					bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_plantaciones.cod_info_empresa)
				INNER JOIN
					bw_inventario_estados_plantaciones ON (bw_inventario_estados_plantaciones.cod_estado = bw_inventario_plantaciones.cod_estado)
				WHERE
					bw_inventario_plantaciones.numero_orden = :numero_orden
				ORDER BY  CAST(bw_inventario_plantaciones.item AS UNSIGNED) ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":numero_orden", $numero_orden);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener el listado de plantaciones
	 */
	function inv_listado_plantaciones(
		$fechaInicial,
		$fechaFinal,
		$cod_info_empresa,
		$cod_estados,
		$cod_semillas
	) {
		$condiciones = ' ';
		if ($cod_info_empresa != null && $cod_info_empresa != '') {

			$condiciones .= " AND bw_inventario_plantaciones.cod_info_empresa = $cod_info_empresa ";
		}
		if ($cod_estados != null && $cod_estados != '') {
			$condiciones .= " AND bw_inventario_plantaciones.cod_estado IN ($cod_estados) ";
		}
		if ($cod_semillas != null && $cod_semillas != '') {
			$condiciones .= " AND bw_inventario_plantaciones.cod_inventario IN ($cod_semillas) ";
		}

		// if ($condiciones == ' WHERE ') {
		// 	$condiciones = '';
		// }

		$SQL = "SELECT
		bw_inventario_plantaciones.cod_plantacion,
		bw_inventario_plantaciones.cod_info_empresa,
		bw_inventario_plantaciones.numero_orden,
		bw_inventario_plantaciones.cantidad,
		bw_inventario_plantaciones.overseed,
		bw_inventario_plantaciones.total,
		bw_inventario_plantaciones.per_planting,
		bw_inventario_plantaciones.fecha_de_orden,
		DATE(bw_inventario_plantaciones.fecha_inicial) AS fecha_inicial,
		DATE(bw_inventario_plantaciones.fecha_final) AS fecha_final,
		bw_inventario_plantaciones.item,
		DATE_FORMAT(STR_TO_DATE(bw_inventario_plantaciones.date_insert,'%Y-%m-%d'),'%m-%d-%Y') as fecha_de_registro,
		bw_inventario_semilla.nombre_semilla,
		bw_inventario_estados_plantaciones.abreviatura,
		bw_inventario_estados_plantaciones.nombre,
		bw_info_empresa.cod_info_empresa,
		bw_info_empresa.nombre_empresa
	FROM
		bw_inventario_plantaciones
		INNER JOIN bw_inventario_semilla ON(
			bw_inventario_semilla.cod_inventario = bw_inventario_plantaciones.cod_inventario
		)
		INNER JOIN bw_info_empresa ON(
			bw_info_empresa.cod_info_empresa = bw_inventario_plantaciones.cod_info_empresa
		)
		INNER JOIN bw_inventario_estados_plantaciones ON(
			bw_inventario_estados_plantaciones.cod_estado = bw_inventario_plantaciones.cod_estado
		)
		WHERE
			bw_inventario_plantaciones.fecha_inicial  BETWEEN :fechaInicial AND :fechaFinal
		" . $condiciones . "
		order by
		bw_inventario_plantaciones.fecha_inicial desc";

		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":fechaInicial",  $fechaInicial);
		$stmt->bindParam(":fechaFinal",  $fechaFinal);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			// $resultado = $e->getMessage();
			$resultado = [];
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de plantaciones activas
	 */
	function inv_listado_estados_de_plantaciones_activos()
	{
		$SQL = "SELECT
					cod_estado,
					nombre,
					abreviatura,
					descripcion
				FROM
					bw_inventario_estados_plantaciones
				WHERE
					activo = 1
				ORDER BY abreviatura ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funcione para cargar el listado de localizaciones activas. Usadas para los trasnplantes
	 */
	function inv_listado_localizaciones()
	{
		$SQL = "SELECT
					cod_localizacion,
					nombre,
					abreviatura,
					descripcion,
					activo
				FROM
					bw_inventario_localizaciones_trasplantes
				ORDER BY nombre ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funcione para cargar el listado de localizaciones activas. Usadas para los trasnplantes
	 */
	function inv_listado_localizaciones_activas()
	{
		$SQL = "SELECT
					cod_localizacion,
					nombre,
					abreviatura,
					descripcion
				FROM
					bw_inventario_localizaciones_trasplantes
				WHERE
					activo = 1
				ORDER BY nombre ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	// ***************************************************************
	// FUNCIONES PARA EL MÓDULO DE ORDENES DE COMPRA
	/*
      * Guarda la cantidad recibida de un producto de una orden de compra
      */
	function inv_buscar_cod_detalle_de_producto_orden_de_compra(
		$cod_orden,
		$cod_detalle_producto,
		$cod_unidad_medida
	) {
		$SQL = "SELECT
					cod_detalle
				FROM
					bw_detalle_ordenes_compra
				where
					cod_orden = :cod_orden
					and cod_detalle_producto = :cod_detalle_producto
					and cod_unidad_medida = :cod_unidad_medida;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_orden",  $cod_orden);
		$stmt->bindParam(":cod_detalle_producto",  $cod_detalle_producto);
		$stmt->bindParam(":cod_unidad_medida",  $cod_unidad_medida);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	// ***************************************************************
	// FUNCIONES PARA EL MÓDULO DE TRASPLANTES
	function inv_listado_numero_de_orden_activos()
	{
		$SQL = "SELECT
					numero_orden
				FROM
					bw_inventario_plantaciones
				WHERE
					activo = 1
				group by
					numero_orden
				order by
					numero_orden";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	function inv_codigos_plantaciones_completadas()
	{
		$SQL = "SELECT
					IFNULL(GROUP_CONCAT(DISTINCT cod_plantacion),0) as cod_plantacion
            -- DISTINCT cod_plantacion as cod_plantacion
					FROM
						bw_inventario_trasplantes
					WHERE
						completado = 1
					ORDER BY cod_plantacion";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	function inv_listado_numero_de_orden_no_completados($codigos_plantaciones_completadas)
	{
		$SQL = "SELECT
					bw_inventario_plantaciones.numero_orden
				FROM
					bw_inventario_plantaciones
					INNER JOIN
						bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_inventario_plantaciones.cod_inventario)
				WHERE
					bw_inventario_plantaciones.activo = 1
					AND bw_inventario_plantaciones.cod_plantacion NOT IN (" . $codigos_plantaciones_completadas . ")
				group by
					bw_inventario_plantaciones.numero_orden
				order by
					bw_inventario_plantaciones.numero_orden;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	function inv_listado_semillas_por_numero_de_orden($numero_orden, $codigos_plantaciones)
	{
		$SQL = "SELECT
				bw_inventario_plantaciones.cod_plantacion,
				bw_inventario_plantaciones.item,
				bw_inventario_plantaciones.cod_inventario,
				bw_inventario_plantaciones.cantidad,
				bw_inventario_plantaciones.overseed,
				bw_inventario_plantaciones.total as inicial,
				DATE_FORMAT(
					STR_TO_DATE(
						bw_inventario_plantaciones.fecha_final,
						'%Y-%m-%d'
					),
					'%m-%d-%Y'
				) as fecha_final,
				bw_inventario_semilla.nombre_semilla,
				bw_inventario_semilla.germinacion_automatica,
				COALESCE(
					(
						SELECT
							SUM(cantidad_reducida)
						FROM
							bw_inventario_movimiento_trasplante
						WHERE
							bw_inventario_movimiento_trasplante.cod_plantacion = bw_inventario_plantaciones.cod_plantacion
					),
					0
				) AS suma_cantidad_reducida,
				bw_inventario_plantaciones.total - COALESCE(
					(
						SELECT
							SUM(cantidad_reducida)
						FROM
							bw_inventario_movimiento_trasplante
						WHERE
							bw_inventario_movimiento_trasplante.cod_plantacion = bw_inventario_plantaciones.cod_plantacion
					),
					0
				) AS total
				FROM
					bw_inventario_plantaciones
				INNER JOIN
					bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_inventario_plantaciones.cod_inventario)
				WHERE
				bw_inventario_plantaciones.numero_orden = :numero_orden
					AND bw_inventario_plantaciones.cod_plantacion NOT IN (" . $codigos_plantaciones . ")
					order by bw_inventario_semilla.nombre_semilla;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":numero_orden", $numero_orden);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	function inv_codigos_plantaciones_en_trasplante($numero_orden)
	{
		$this->deshabilitarGrupoFull();

		$SQL = "SELECT 
					GROUP_CONCAT(cod_plantacion) AS codigos_plantaciones
				FROM
					bw_inventario_trasplantes
				WHERE
					numero_orden = :numero_orden
					AND completado = 1
				GROUP BY numero_orden;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":numero_orden", $numero_orden);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		$this->habilitarGrupoFull();

		return $resultado;
	}

	/*
	 * Funciones para guardar el registro de una nueva trasplante
	 */
	function inv_guardar_trasplante(
		$cod_inventario,
		$cod_plantacion,
		$cod_localizacion,
		$cod_info_empresa,
		$fecha_entrega,
		$numero_ticket,
		$tray,
		$cantidad_plantas,
		$germinacion,
		$total_trays,
		$total_plantas,
		$numero_orden,

		$user_insert
	) {

		$SQL = "INSERT
				INTO bw_inventario_trasplantes
					(
					cod_inventario,
					cod_plantacion,
					cod_localizacion,
					cod_info_empresa,
					numero_orden,
					fecha_entrega,
					numero_ticket,
					tray,
					cantidad_plantas,
					germinacion,
					total_trays,
					total_plantas,
					user_insert
					)
				VALUES
					(
					:cod_inventario,
					:cod_plantacion,
					:cod_localizacion,
					:cod_info_empresa,
					:numero_orden,
					:fecha_entrega,
					:numero_ticket,
					:tray,
					:cantidad_plantas,
					:germinacion,
					:total_trays,
					:total_plantas,
					:user_insert
					);";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_inventario", $cod_inventario);
		$stmt->bindParam(":cod_plantacion", $cod_plantacion);
		$stmt->bindParam(":cod_localizacion", $cod_localizacion);
		$stmt->bindParam(":cod_info_empresa", $cod_info_empresa);
		$stmt->bindParam(":numero_orden", $numero_orden);
		$stmt->bindParam(":fecha_entrega", $fecha_entrega);
		$stmt->bindParam(":numero_ticket", $numero_ticket);
		$stmt->bindParam(":tray", $tray);
		$stmt->bindParam(":cantidad_plantas", $cantidad_plantas);
		$stmt->bindParam(":germinacion", $germinacion);
		$stmt->bindParam(":total_trays", $total_trays);
		$stmt->bindParam(":total_plantas", $total_plantas);
		$stmt->bindParam(":user_insert", $user_insert);


		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|It has been entered successfully.|" . $this->db_conexion->lastInsertId();
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}


	/*
	 * Se registra la cantidades que se ingresaron en el trasplante
	 */
	function inv_datos_ultimo_movimiento_trasplante(
		$cod_plantacion,
		$cod_inventario
	) {
		$SQL = "SELECT 
					cantidad_sobrante
				FROM
					bw_inventario_movimiento_trasplante
				WHERE
					cod_plantacion = :cod_plantacion
					AND cod_inventario = :cod_inventario 
					ORDER BY cod_movimiento DESC LIMIT 1;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_plantacion", $cod_plantacion);
		$stmt->bindParam(":cod_inventario", $cod_inventario);


		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	function inv_datos_movimiento_trasplante_especifico(
		$cod_trasplante,
		$cod_plantacion,
		$cod_inventario
	) {
		$SQL = "SELECT
					cod_movimiento,
					cantidad_sobrante,
					cantidad_reducida
				FROM
					bw_inventario_movimiento_trasplante
				WHERE
					cod_plantacion = :cod_plantacion
					AND cod_inventario = :cod_inventario 
					AND cod_trasplante = :cod_trasplante 
					ORDER BY cod_movimiento DESC LIMIT 1;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_plantacion", $cod_plantacion);
		$stmt->bindParam(":cod_inventario", $cod_inventario);
		$stmt->bindParam(":cod_trasplante", $cod_trasplante);


		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*
	 * Se registra la cantidades que se ingresaron en el trasplante
	 */
	function inv_guardar_movimiento_trasplante(
		$cod_trasplante,
		$cod_plantacion,
		$cod_inventario,
		$numero_orden,
		$cantidad_original,
		$cantidad_reducida,
		$cantidad_sobrante,
		$user_insert
	) {

		$SQL = "INSERT
				INTO bw_inventario_movimiento_trasplante
					(
						cod_trasplante,
						cod_plantacion,
						cod_inventario,
						numero_orden,
						cantidad_original,
						cantidad_reducida,
						cantidad_sobrante,
						user_insert
					)
				VALUES
					(
						:cod_trasplante,
						:cod_plantacion,
						:cod_inventario,
						:numero_orden,
						:cantidad_original,
						:cantidad_reducida,
						:cantidad_sobrante,
						:user_insert
					);";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_trasplante", $cod_trasplante);
		$stmt->bindParam(":cod_plantacion", $cod_plantacion);
		$stmt->bindParam(":cod_inventario", $cod_inventario);
		$stmt->bindParam(":numero_orden", $numero_orden);
		$stmt->bindParam(":cantidad_original", $cantidad_original);
		$stmt->bindParam(":cantidad_reducida", $cantidad_reducida);
		$stmt->bindParam(":cantidad_sobrante", $cantidad_sobrante);
		$stmt->bindParam(":user_insert", $user_insert);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|It has been entered successfully.|" . $this->db_conexion->lastInsertId();
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Se actualiza las cantidades que esten registradas en los movimientos de los trasplantes
	 */
	function inv_actualizar_movimiento_trasplante(
		$cod_movimiento,
		$cod_trasplante,
		$cod_inventario,
		$cod_plantacion,
		$cantidad_reducida,
		$cantidad_sobrante
	) {

		$SQL = "UPDATE
					bw_inventario_movimiento_trasplante
				SET
					cantidad_reducida = :cantidad_reducida,
					cantidad_sobrante = :cantidad_sobrante
				WHERE
					cod_movimiento = :cod_movimiento;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_movimiento", $cod_movimiento);
		// $stmt->bindParam(":cod_trasplante", $cod_trasplante);
		// $stmt->bindParam(":cod_inventario", $cod_inventario);
		// $stmt->bindParam(":cod_plantacion", $cod_plantacion);
		$stmt->bindParam(":cantidad_reducida", $cantidad_reducida);
		$stmt->bindParam(":cantidad_sobrante", $cantidad_sobrante);


		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|It has been entered successfully.|" . $this->db_conexion->lastInsertId();
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Se eliminant todos los movimientos hechos por un trasplante. (esta acción es irreversible y no se tiene ningún registro)
	 */
	function inv_buscar_codigos_movimientos_trasplantes(
		$cod_trasplante
	) {
		$SQL = "SELECT 
					GROUP_CONCAT(cod_movimiento) AS codigos_movimientos
				FROM
					bw_inventario_movimiento_trasplante
				WHERE
				cod_trasplante = :cod_trasplante;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_trasplante", $cod_trasplante);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Se eliminant todos los movimientos hechos por un trasplante. (esta acción es irreversible y no se tiene ningún registro)
	 */
	function inv_eliminar_movimientos_trasplante(
		$codigos_trasplantes
	) {

		$SQL = "DELETE FROM
					bw_inventario_movimiento_trasplante
				WHERE
					cod_movimiento IN (" . $codigos_trasplantes . ");";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para actualizar el numero de item de una semilla en la tabla de plantaciones
	 */
	function inv_actulizar_line_item(
		$cod_inventario,
		$cod_plantacion,
		$numero_orden,
		$item

	) {
		$SQL = "UPDATE
					bw_inventario_plantaciones
				SET
					item = :item
				WHERE
					numero_orden = :numero_orden
					AND cod_inventario = :cod_inventario
					AND cod_plantacion = :cod_plantacion;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":item", $item);
		$stmt->bindParam(":numero_orden", $numero_orden);
		$stmt->bindParam(":cod_inventario", $cod_inventario);
		$stmt->bindParam(":cod_plantacion", $cod_plantacion);

		// $stmt->bindParam(":cantidad", $cantidad);
		// $stmt->bindParam(":overseed", $overseed);
		// $stmt->bindParam(":total", $total);
		// $stmt->bindParam(":per_planting", $per_planting);
		// $stmt->bindParam(":fecha_inicial", $fecha_inicial);
		// $stmt->bindParam(":fecha_final", $fecha_final);
		// $stmt->bindParam(":cod_estado", $cod_estado);


		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_listado_trasplante(
		$fechaInicial,
		$fechaFinal,
		$cod_estados,
		$cod_inventario,
		$flag_completado,
		$ticket
	) {
		$condiciones = '';
		if ($cod_estados != null && $cod_estados != '') {

			$condiciones .= " AND bw_inventario_plantaciones.cod_estado IN (" . $cod_estados . ") ";
		}

		if ($cod_inventario != null && $cod_inventario != '') {

			$condiciones .= " AND bw_inventario_trasplantes.cod_inventario IN (" . $cod_inventario . ") ";
		}

		if ($ticket != null && $ticket != '') {

			$condiciones .= " AND bw_inventario_trasplantes.numero_ticket IN (" . $ticket . ") ";
		}

		if ($flag_completado == 2) {

			$condiciones .= " AND IFNULL(bw_inventario_trasplantes.completado, 0) IN (0, 1) ";
		} else {

			$condiciones .= " AND IFNULL(bw_inventario_trasplantes.completado, 0) IN (" . $flag_completado . ") ";
		}

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->execute();

		$SQL = "SELECT 
					bw_inventario_plantaciones.numero_orden AS NUMERO_ORDEN,
					bw_inventario_plantaciones.item AS ITEM,
					bw_inventario_estados_plantaciones.abreviatura AS ESTADO,
					bw_inventario_plantaciones.cod_inventario AS COD_SEED,
					bw_inventario_semilla.nombre_semilla AS SEED,
					DATE(bw_inventario_plantaciones.fecha_final) AS EXP_DELIVERY,
					DATE(bw_inventario_trasplantes.fecha_recibo) AS LAST_DELIVERY,
					bw_inventario_trasplantes.tray AS TRAY,
					bw_inventario_movimiento_trasplante.cantidad_reducida AS ACTUAL_PLANTAS,
					IF(IFNULL(bw_inventario_movimiento_trasplante.cantidad_reducida,'0') = 0,'0',bw_inventario_plantaciones.total) AS EXP_PLANTS,
    				bw_inventario_plantaciones.total AS EXP_PLANTS2,
					bw_inventario_plantaciones.cod_plantacion,
					bw_inventario_plantaciones.completado AS COMP_PLANTACION,
					bw_inventario_trasplantes.completado AS COMP,
					IF(IFNULL(bw_inventario_trasplantes.cod_trasplante, 0) = 0,
					0,
					bw_inventario_trasplantes.cod_trasplante) AS COD_TRANS
				FROM
					bw_inventario_plantaciones
						INNER JOIN
					bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_inventario_plantaciones.cod_inventario)
						INNER JOIN
					bw_inventario_estados_plantaciones ON (bw_inventario_estados_plantaciones.cod_estado = bw_inventario_plantaciones.cod_estado)
						LEFT JOIN
					bw_inventario_trasplantes ON (bw_inventario_plantaciones.cod_plantacion = bw_inventario_trasplantes.cod_plantacion
					AND bw_inventario_plantaciones.cod_inventario = bw_inventario_trasplantes.cod_inventario)
						LEFT JOIN
					bw_inventario_movimiento_trasplante ON (bw_inventario_movimiento_trasplante.cod_trasplante = bw_inventario_trasplantes.cod_trasplante)
					WHERE
					bw_inventario_plantaciones.fecha_final BETWEEN :fechaInicial AND :fechaFinal
					" . $condiciones . "
				ORDER BY bw_inventario_plantaciones.numero_orden, bw_inventario_plantaciones.cod_inventario, bw_inventario_plantaciones.item DESC;";


		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":fechaInicial",  $fechaInicial);
		$stmt->bindParam(":fechaFinal",  $fechaFinal);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();

		return $resultado;
	}

	/*Función que trae el listado de todos los tickets de transplantes*/
	public function get_listado_tickets()
	{
		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->execute();

		$SQL = "SELECT 
					bw_inventario_trasplantes.cod_trasplante,
					bw_inventario_trasplantes.cod_plantacion,
					bw_inventario_trasplantes.cod_inventario,
					bw_inventario_trasplantes.numero_orden,
					CONCAT('\'',bw_inventario_trasplantes.numero_ticket,'\'') AS numero_ticket,
					CONCAT(bw_inventario_trasplantes.numero_orden, ' - ',bw_inventario_trasplantes.numero_ticket, ' | ', bw_inventario_semilla.nombre_semilla) AS ORDER_TICKET_SEED,
					CONCAT(bw_inventario_trasplantes.numero_orden, ' - ',bw_inventario_trasplantes.numero_ticket) AS order_ticket,
					bw_inventario_trasplantes.completado
				FROM
					bw_inventario_trasplantes
				INNER JOIN bw_inventario_semilla ON
					(bw_inventario_semilla.cod_inventario = bw_inventario_trasplantes.cod_inventario)
				GROUP BY bw_inventario_trasplantes.numero_orden, bw_inventario_trasplantes.numero_ticket
				ORDER BY bw_inventario_trasplantes.numero_orden, bw_inventario_trasplantes.numero_ticket ASC;";

		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_listado_trasplante_detalle($fechaInicial, $fechaFinal, $cod_inventario, $item, $flag_completado)
	{
		$condiciones = '';

		if ($flag_completado == 2) {
			$condiciones .= " AND IFNULL(bw_inventario_trasplantes.completado, 0) IN (0, 1) ";
		} else {

			$condiciones .= " AND IFNULL(bw_inventario_trasplantes.completado, 0) IN (" . $flag_completado . ") ";
		}

		$SQL = "SELECT 
					bw_inventario_plantaciones.numero_orden AS NUMERO_ORDEN,
					bw_inventario_trasplantes.numero_ticket AS TICKET,
					bw_inventario_plantaciones.item AS ITEM,
					bw_inventario_estados_plantaciones.abreviatura AS ESTADO,
					bw_inventario_semilla.nombre_semilla AS SEED,
					DATE(bw_inventario_trasplantes.fecha_recibo) AS FECHA_ENTREGA,
					DATE(bw_inventario_trasplantes.fecha_recibo) AS FECHA_DELIVERY,
					bw_inventario_trasplantes.tray AS TRAY,
					FORMAT(bw_inventario_movimiento_trasplante.cantidad_reducida,
						0) AS ACTUAL_PLANTS,
					ROUND(bw_inventario_trasplantes.germinacion, 2) AS GERM,
					FORMAT(bw_inventario_plantaciones.cantidad,
						0) AS CANTIDAD,
					bw_inventario_plantaciones.overseed AS OVERSEED,
					FORMAT((bw_inventario_movimiento_trasplante.cantidad_reducida + bw_inventario_movimiento_trasplante.cantidad_sobrante),
						0) AS EXP_PLANTS,
					FORMAT(((bw_inventario_movimiento_trasplante.cantidad_reducida + bw_inventario_movimiento_trasplante.cantidad_sobrante) - bw_inventario_trasplantes.cantidad_plantas),
						0) AS BALANCE,
					bw_inventario_trasplantes.completado,
					bw_inventario_trasplantes.cod_trasplante,
					bw_inventario_trasplantes.cod_inventario,
					bw_inventario_trasplantes.cod_plantacion,
					bw_inventario_trasplantes.cod_localizacion,
					bw_inventario_trasplantes.cod_info_empresa,
					bw_info_empresa.nombre_empresa,
					DATE(bw_inventario_plantaciones.fecha_de_orden) AS fecha_de_orden,
					bw_inventario_localizaciones_trasplantes.nombre AS location
				FROM
					bw_inventario_trasplantes
						INNER JOIN
					bw_inventario_plantaciones ON (bw_inventario_trasplantes.cod_plantacion = bw_inventario_plantaciones.cod_plantacion)
						INNER JOIN
					bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_inventario_trasplantes.cod_inventario)
						INNER JOIN
					bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_plantaciones.cod_info_empresa)
						INNER JOIN
					bw_inventario_estados_plantaciones ON (bw_inventario_estados_plantaciones.cod_estado = bw_inventario_plantaciones.cod_estado)
						INNER JOIN
					bw_inventario_localizaciones_trasplantes ON (bw_inventario_localizaciones_trasplantes.cod_localizacion = bw_inventario_trasplantes.cod_localizacion)
						INNER JOIN
					bw_inventario_movimiento_trasplante ON (bw_inventario_movimiento_trasplante.cod_trasplante = bw_inventario_trasplantes.cod_trasplante)
				WHERE
					DATE(bw_inventario_trasplantes.fecha_recibo) BETWEEN :fechaInicial AND :fechaFinal
						AND bw_inventario_trasplantes.cod_inventario = :cod_inventario
        				AND bw_inventario_plantaciones.item = :item
						" . $condiciones . "
				ORDER BY bw_inventario_trasplantes.completado = 1 , bw_inventario_plantaciones.fecha_final < CURRENT_DATE DESC , bw_inventario_trasplantes.cod_trasplante , bw_inventario_trasplantes.cod_inventario , bw_inventario_semilla.nombre_semilla , bw_inventario_trasplantes.numero_ticket;";

		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":fechaInicial",  $fechaInicial);
		$stmt->bindParam(":fechaFinal",  $fechaFinal);
		$stmt->bindParam(":cod_inventario",  $cod_inventario);
		$stmt->bindParam(":item",  $item);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();

		return $resultado;
	}
	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_listado_trasplante_detalle_por_estado($fechaInicial, $fechaFinal, $cod_inventario, $item, $codigos_estados, $flag_completado)
	{
		$condiciones = '';

		if ($flag_completado == 2) {
			$condiciones .= " AND IFNULL(bw_inventario_trasplantes.completado, 0) IN (0, 1) ";
		} else {

			$condiciones .= " AND IFNULL(bw_inventario_trasplantes.completado, 0) IN (" . $flag_completado . ") ";
		}

		$SQL = "SELECT 
					bw_inventario_plantaciones.numero_orden AS NUMERO_ORDEN,
					bw_inventario_trasplantes.numero_ticket AS TICKET,
					bw_inventario_plantaciones.item AS ITEM,
					bw_inventario_estados_plantaciones.abreviatura AS ESTADO,
					bw_inventario_semilla.nombre_semilla AS SEED,
					DATE(bw_inventario_trasplantes.fecha_recibo) AS FECHA_ENTREGA,
					DATE(bw_inventario_trasplantes.fecha_recibo) AS FECHA_DELIVERY,
					bw_inventario_trasplantes.tray AS TRAY,
					FORMAT(bw_inventario_movimiento_trasplante.cantidad_reducida,
						0) AS ACTUAL_PLANTS,
					ROUND(bw_inventario_trasplantes.germinacion, 2) AS GERM,
					FORMAT(bw_inventario_plantaciones.cantidad,
						0) AS CANTIDAD,
					bw_inventario_plantaciones.overseed AS OVERSEED,
					FORMAT((bw_inventario_movimiento_trasplante.cantidad_reducida + bw_inventario_movimiento_trasplante.cantidad_sobrante),
						0) AS EXP_PLANTS,
					FORMAT(((bw_inventario_movimiento_trasplante.cantidad_reducida + bw_inventario_movimiento_trasplante.cantidad_sobrante) - bw_inventario_trasplantes.cantidad_plantas),
						0) AS BALANCE,
					bw_inventario_trasplantes.completado,
					bw_inventario_trasplantes.cod_trasplante,
					bw_inventario_trasplantes.cod_inventario,
					bw_inventario_trasplantes.cod_plantacion,
					bw_inventario_trasplantes.cod_localizacion,
					bw_inventario_trasplantes.cod_info_empresa,
					bw_info_empresa.nombre_empresa,
					DATE(bw_inventario_plantaciones.fecha_de_orden) AS fecha_de_orden,
					bw_inventario_localizaciones_trasplantes.nombre AS location
				FROM
					bw_inventario_trasplantes
						INNER JOIN
					bw_inventario_plantaciones ON (bw_inventario_trasplantes.cod_plantacion = bw_inventario_plantaciones.cod_plantacion)
						INNER JOIN
					bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_inventario_trasplantes.cod_inventario)
						INNER JOIN
					bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_plantaciones.cod_info_empresa)
						INNER JOIN
					bw_inventario_estados_plantaciones ON (bw_inventario_estados_plantaciones.cod_estado = bw_inventario_plantaciones.cod_estado)
						INNER JOIN
					bw_inventario_localizaciones_trasplantes ON (bw_inventario_localizaciones_trasplantes.cod_localizacion = bw_inventario_trasplantes.cod_localizacion)
						INNER JOIN
					bw_inventario_movimiento_trasplante ON (bw_inventario_movimiento_trasplante.cod_trasplante = bw_inventario_trasplantes.cod_trasplante)
				WHERE
					DATE(bw_inventario_trasplantes.fecha_recibo) BETWEEN :fechaInicial AND :fechaFinal
						AND bw_inventario_trasplantes.cod_inventario = :cod_inventario
        				AND bw_inventario_plantaciones.item = :item
						AND bw_inventario_estados_plantaciones.cod_estado in (" . $codigos_estados . ")
						" . $condiciones . "
				ORDER BY bw_inventario_trasplantes.completado = 1 , bw_inventario_plantaciones.fecha_final < CURRENT_DATE DESC , bw_inventario_trasplantes.cod_trasplante , bw_inventario_trasplantes.cod_inventario , bw_inventario_semilla.nombre_semilla , bw_inventario_trasplantes.numero_ticket;";

		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":fechaInicial",  $fechaInicial);
		$stmt->bindParam(":fechaFinal",  $fechaFinal);
		$stmt->bindParam(":cod_inventario",  $cod_inventario);
		$stmt->bindParam(":item",  $item);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();

		return $resultado;
	}

	/*
	 * Funciones para obtener un arreglo de transplantes 
	 */
	function inv_arr_transplantes($fechaInicial, $fechaFinal, $cod_inventario, $item, $flag_completado)
	{
		$condiciones = '';

		if ($flag_completado == 2) {
			$condiciones .= " AND IFNULL(bw_inventario_trasplantes.completado, 0) IN (0, 1) ";
		} else {

			$condiciones .= " AND IFNULL(bw_inventario_trasplantes.completado, 0) IN (" . $flag_completado . ") ";
		}

		$SQL = "SELECT 
					GROUP_CONCAT(bw_inventario_trasplantes.cod_trasplante) AS TRANSPLANTES
				FROM
				bw_inventario_trasplantes
					INNER JOIN
				bw_inventario_plantaciones ON (bw_inventario_trasplantes.cod_plantacion = bw_inventario_plantaciones.cod_plantacion)
				WHERE
					DATE(bw_inventario_trasplantes.fecha_recibo) BETWEEN :fechaInicial AND :fechaFinal
						AND bw_inventario_trasplantes.cod_inventario = :cod_inventario
        				AND bw_inventario_plantaciones.item = :item
						" . $condiciones . "";

		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":fechaInicial",  $fechaInicial);
		$stmt->bindParam(":fechaFinal",  $fechaFinal);
		$stmt->bindParam(":cod_inventario",  $cod_inventario);
		$stmt->bindParam(":item",  $item);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();

		return $resultado;
	}

	/*
      * Cambia el flag de activo de las variedades de producto
      */
	function inv_cambiar_estado_trasplante(
		$cod_trasplante,
		$flag_activo
	) {
		$SQL = "UPDATE
					bw_inventario_trasplantes
				SET
					activo = :activo
				WHERE
					cod_trasplante = :cod_trasplante";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_trasplante", $cod_trasplante);
		$stmt->bindParam(":activo", $flag_activo);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Cambia el flag de activo de las variedades de producto
      */
	function inv_cambiar_estado_trasplante_varios(
		$cod_estado,
		$arr_transplantes
	) {
		$SQL = "UPDATE bw_inventario_trasplantes 
				SET 
					completado = " . $cod_estado . "
				WHERE
					cod_trasplante IN (" . $arr_transplantes . ");";

		$stmt = $this->db_conexion->prepare($SQL);

		try {
			$stmt->execute();
			$resultado = "0|Successful update.";
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	

  function inv_cambiar_estado_trasplante_en_plantacion(
    $cod_estado, 
    $cod_plantacion
  ){
    error_log("Updating $cod_plantacion with state $cod_estado");
    $SQL = "UPDATE bw_inventario_plantaciones
            SET 
            completado = :completado
            WHERE cod_plantacion = :cod_plantacion ";

    $stmt = $this->db_conexion->prepare($SQL);
    $stmt->bindParam(":completado", $cod_estado);
    $stmt->bindParam(":cod_plantacion", $cod_plantacion);
		try {
			$stmt->execute();
			$resultado = "0|Registry updated succesfully.";
      error_log($resultado);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
      error_log($e->getMessage());
		}
		$stmt->closeCursor();
		return $resultado;
  }

	/*
      * Cambia el flag de activo de las variedades de producto
      */
	function inv_cambiar_estado_de_completado_trasplante(
		$cod_trasplante,
		$flag_activo
	) {

		$SQL = "UPDATE
					bw_inventario_trasplantes
				SET
				completado = :completado
				WHERE
				cod_trasplante = :cod_trasplante;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_trasplante", $cod_trasplante);
		$stmt->bindParam(":completado", $flag_activo);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Successful update.";
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Cambia el flag de activo de las variedades de producto
      */
	function inv_cambiar_estado_de_completado_orden(
		$cod_orden,
		$flag_activo
	) {

		$SQL = "UPDATE
					bw_ordenes_compra
				SET
				completada = :completada
				WHERE
				cod_orden = :cod_orden;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_orden", $cod_orden);
		$stmt->bindParam(":completada", $flag_activo);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Successful update.";
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_buscar_trasplante($numero_ticket)
	{
		$SQL = "SELECT
						bw_inventario_trasplantes.cod_trasplante,
						bw_inventario_trasplantes.cod_inventario,
						bw_inventario_trasplantes.cod_plantacion,
						bw_inventario_trasplantes.cod_localizacion,
						bw_inventario_trasplantes.cod_info_empresa,
						bw_inventario_trasplantes.numero_orden,
						DATE_FORMAT(bw_inventario_trasplantes.fecha_entrega, '%m-%d-%Y') as fecha_entrega,
						bw_inventario_trasplantes.numero_ticket,
						bw_inventario_trasplantes.tray,
						bw_inventario_trasplantes.cantidad_plantas,
						bw_inventario_trasplantes.germinacion,
						bw_inventario_trasplantes.total_trays,
						bw_inventario_trasplantes.total_plantas,
						bw_inventario_trasplantes.completado,

						bw_inventario_plantaciones.cod_plantacion,
						bw_inventario_plantaciones.cantidad,
						bw_inventario_plantaciones.overseed,
						DATE_FORMAT(STR_TO_DATE(bw_inventario_plantaciones.fecha_final,'%Y-%m-%d'),'%m-%d-%Y') as fecha_final,

						bw_info_empresa.nombre_empresa,
						bw_inventario_semilla.nombre_semilla,
						bw_inventario_semilla.cantidad_semilla,
						bw_inventario_plantaciones.item,
						CONCAT(bw_inventario_plantaciones.item,'- ',bw_inventario_semilla.nombre_semilla) AS datos_semillas,
						bw_inventario_plantaciones.total - COALESCE((SELECT 
								SUM(cantidad_reducida)
							FROM
								bw_inventario_movimiento_trasplante
							WHERE
								bw_inventario_movimiento_trasplante.cod_plantacion = bw_inventario_plantaciones.cod_plantacion),
						0) AS total,
						COALESCE((SELECT 
								SUM(cantidad_reducida)+ SUM(cantidad_sobrante)
							FROM
								bw_inventario_movimiento_trasplante
							WHERE
								bw_inventario_movimiento_trasplante.cod_plantacion = bw_inventario_plantaciones.cod_plantacion
                                AND bw_inventario_movimiento_trasplante.cod_trasplante = bw_inventario_trasplantes.cod_trasplante
                                AND bw_inventario_movimiento_trasplante.cod_plantacion = bw_inventario_plantaciones.cod_plantacion),
						0) AS total_del_momento
				FROM
				bw_inventario_trasplantes
						INNER JOIN
					bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_inventario_trasplantes.cod_inventario)
						INNER JOIN
					bw_inventario_plantaciones ON (bw_inventario_plantaciones.cod_plantacion = bw_inventario_trasplantes.cod_plantacion)
						INNER JOIN
					bw_inventario_localizaciones_trasplantes ON (bw_inventario_localizaciones_trasplantes.cod_localizacion = bw_inventario_trasplantes.cod_localizacion)
						INNER JOIN
					bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_trasplantes.cod_info_empresa)

				WHERE
					bw_inventario_trasplantes.numero_ticket = :numero_ticket
				ORDER BY bw_inventario_trasplantes.cod_plantacion DESC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":numero_ticket", $numero_ticket);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para buscar un producto especifico de una plantacion
	 */
	function inv_buscar_producto_en_trasplante(
		$numero_ticket,
		$numero_orden,
		$cod_inventario,
		$cod_plantacion,
		$cod_trasplante
	) {
		$SQL = "SELECT
					bw_inventario_trasplantes.cod_trasplante AS existe_registro
				FROM
					bw_inventario_trasplantes
				WHERE
					numero_ticket = :numero_ticket
						AND numero_orden = 		:numero_orden
						AND cod_inventario = 	:cod_inventario
						AND cod_plantacion = 	:cod_plantacion
						AND cod_trasplante = 	:cod_trasplante;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":numero_ticket", 	$numero_ticket);
		$stmt->bindParam(":numero_orden", 	$numero_orden);
		$stmt->bindParam(":cod_inventario", $cod_inventario);
		$stmt->bindParam(":cod_plantacion", $cod_plantacion);
		$stmt->bindParam(":cod_trasplante", $cod_trasplante);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_actualizar_trasplante(
		$tray,
		$cantidad_plantas,
		$germinacion,
		$total_trays,
		$total_plantas,
		$fecha_recibo,
		$cod_trasplante
	) {
		$SQL = "UPDATE
					bw_inventario_trasplantes
				SET
					tray 				= :tray,
					cantidad_plantas 	= :cantidad_plantas,
					germinacion 		= :germinacion,
					total_trays 		= :total_trays,
					total_plantas 		= :total_plantas,
					fecha_recibo 		= :fecha_recibo
				WHERE
					cod_trasplante = :cod_trasplante";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":tray", $tray);
		$stmt->bindParam(":cantidad_plantas", $cantidad_plantas);
		$stmt->bindParam(":germinacion", $germinacion);
		$stmt->bindParam(":total_trays", $total_trays);
		$stmt->bindParam(":total_plantas", $total_plantas);
		$stmt->bindParam(":fecha_recibo", $fecha_recibo);
		$stmt->bindParam(":cod_trasplante", $cod_trasplante);


		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|It has been update successfully.|" . $this->db_conexion->lastInsertId();
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_buscar_cod_trasplante_por_numero_ticket(
		$numero_ticket
	) {
		$SQL = "SELECT cod_trasplante FROM  bw_inventario_trasplantes where numero_ticket = :numero_ticket;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":numero_ticket", $numero_ticket);


		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_eliminar_trasplante(
		$cod_trasplante

	) {
		$SQL = "DELETE
					FROM bw_inventario_trasplantes
				WHERE
					bw_inventario_trasplantes.cod_trasplante = :cod_trasplante;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_trasplante", $cod_trasplante);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Transplant deleted successfully.|";
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para buscar una plantacion especifica
	 */
	function inv_borrar_trasplanacion(
		$cod_trasplante
	) {
		$SQL = "DELETE
					FROM bw_inventario_trasplantes
				WHERE
				bw_inventario_trasplantes.cod_trasplante = :cod_trasplante;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_trasplante", $cod_trasplante);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|The product has been successfully deleted.";
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para buscar un producto especifico de una plantacion
	 */
	function inv_buscar_cantidad_semillas(
		$cod_inventario
	) {
		$SQL = "SELECT
					bw_inventario_semilla.cantidad_semilla
				FROM
					bw_inventario_semilla
				WHERE
					bw_inventario_semilla.cod_inventario = :cod_inventario;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_inventario", $cod_inventario);
		// $stmt->bindParam(":numero_ticket", 	$numero_ticket);
		// $stmt->bindParam(":numero_orden", 	$numero_orden);
		// $stmt->bindParam(":cod_plantacion", $cod_plantacion);
		// $stmt->bindParam(":cod_trasplante", $cod_trasplante);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para buscar un producto especifico de una plantacion
	 */
	function inv_reducir_cantidad_semillas(
		$cod_inventario,
		$cantidad
	) {
		$SQL = "UPDATE bw_inventario_semilla 
				SET
					cantidad_semilla = cantidad_semilla - :cantidad
				WHERE
					bw_inventario_semilla.cod_inventario = :cod_inventario;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cantidad", $cantidad);
		$stmt->bindParam(":cod_inventario", $cod_inventario);


		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para buscar un producto especifico de una plantacion
	 */
	function inv_bitacora_de_movimientos_de_cantidad_de_semillas(
		$cod_inventario,
		$fecha,
		$cantidad_sumar,
		$cantidad_restar,
		$razon,
		$user_insert
	) {
		$SQL = "INSERT
				INTO bw_inventario_semilla_sumar_restar
						(cod_inventario,
						fecha,
						cantidad_sumar,
						cantidad_restar,
						razon,
						user_insert)
				VALUES
						(
						:cod_inventario,
						:fecha,
						:cantidad_sumar,
						:cantidad_restar,
						:razon,
						:user_insert);";
		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_inventario", $cod_inventario);
		$stmt->bindParam(":fecha", $fecha);
		$stmt->bindParam(":cantidad_sumar", $cantidad_sumar);
		$stmt->bindParam(":cantidad_restar", $cantidad_restar);
		$stmt->bindParam(":razon", $razon);
		$stmt->bindParam(":user_insert", $user_insert);


		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	// MODULO LOCALIZACIONES
	/*
      * Guarda una Categoría de semillas
      */
	function inv_guardar_localizacion(
		$codigo_localizacion,
		$nombre,
		$abreviacion,
		$descripcion,
		$activo,
		$user_insert
	) {
		$SQL = "CALL inv_guardar_localizacion(:codigo_localizacion,
									:nombre,
									:abreviacion,
									:descripcion,
									:activo,
									:user_insert)";
		$stmt = $this->db_conexion->prepare($SQL);
		// $codigo_localizacion = 12;
		$stmt->bindParam(":codigo_localizacion",	$codigo_localizacion);
		$stmt->bindParam(":nombre",			$nombre);
		$stmt->bindParam(":abreviacion",	$abreviacion);
		$stmt->bindParam(":descripcion",	$descripcion);
		$stmt->bindParam(":activo",			$activo);
		$stmt->bindParam(":user_insert",	$user_insert);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//$resultado = $stmt;
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener la información de un rasgo especifico
	 */
	function inv_obtener_info_localizacion($cod_localizacion)
	{
		$SQL = "SELECT
					cod_localizacion, nombre, abreviatura, descripcion, activo
				FROM
					bw_inventario_localizaciones_trasplantes
				WHERE
					bw_inventario_localizaciones_trasplantes.cod_localizacion = :cod_localizacion;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_localizacion",  $cod_localizacion);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
      * Cambia el estado de activacion de una localizacion especifica
      */
	function inv_cambiar_estado_localizaciones(
		$cod_localizacion,
		$flag_activo
	) {
		// UPDATE ewvihjmy_LMF`.`bw_inventario_localizaciones_trasplantes` SET `activo` = '0' WHERE (`cod_localizacion` = '1');
		// UPDATE ewvihjmy_LMF`.`bw_inventario_localizaciones_trasplantes` SET `activo` = '0' WHERE (`cod_localizacion` = '1');

		$SQL = "UPDATE
					bw_inventario_localizaciones_trasplantes
				SET
					activo = :flag_activo
				WHERE
					cod_localizacion = :cod_localizacion;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_localizacion",  $cod_localizacion);
		$stmt->bindParam(":flag_activo",  $flag_activo);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			// $resultado = $stmt->queryString;
			$resultado = "0|Location activation has been changed";
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	function inv_listado_invernaderos_por_semilla($cod_inventario)
	{
		$SQL = "SELECT
					bw_inventario_semilla.cod_inventario,
					bw_info_empresa.cod_info_empresa,
					bw_info_empresa.nombre_empresa
				FROM
					bw_inventario_semilla
				INNER JOIN
					bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_semilla.cod_info_empresa)
				WHERE
					bw_inventario_semilla.cod_inventario = :cod_inventario
					order by bw_info_empresa.nombre_empresa;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_inventario", $cod_inventario);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	function inv_listado_invernaderos_por_nombre_semilla($nombre_semilla)
	{
		$stmt = $this->db_conexion->prepare("SET CHARACTER SET utf8;");
		$stmt->execute();
		$SQL = "SELECT
					bw_inventario_semilla.cod_inventario as cod_inventario_semilla,
					bw_inventario_semillas_por_empresas.cod_inventario,
					bw_inventario_semillas_por_empresas.cod_empresa,
					bw_info_empresa.cod_info_empresa,
					bw_info_empresa.nombre_empresa AS nombre_viejo,
					bw_inventario_semillas_por_empresas.cantidad_semilla AS cantidad_individual,

					CONCAT(bw_info_empresa.nombre_empresa,
					            ' - (',
								FORMAT( ROUND(bw_inventario_semillas_por_empresas.cantidad_semilla,2),0,'es_MX'), ')') AS nombre_empresa
				FROM
					bw_inventario_semilla
				INNER JOIN
					bw_inventario_semillas_por_empresas ON (bw_inventario_semillas_por_empresas.cod_inventario_semilla = bw_inventario_semilla.cod_inventario)
				INNER JOIN
					bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_semillas_por_empresas.cod_empresa)
				WHERE
					bw_inventario_semilla.nombre_semilla = :nombre_semilla
					order by bw_info_empresa.nombre_empresa;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":nombre_semilla", $nombre_semilla);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();

		return $resultado;
	}

	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_ingresar_semilla_por_empresa(
		$cod_inventario_semilla,
		$cod_empresa,
		$cantidad_semilla,
		$user_insert
	) {
		$SQL = "INSERT
				INTO bw_inventario_semillas_por_empresas
					(
						cod_inventario_semilla,
						cod_empresa,
						cantidad_semilla,
						user_insert
					)
				VALUES
					(
						:cod_inventario_semilla,
						:cod_empresa,
						:cantidad_semilla,
						:user_insert
					);";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_inventario_semilla", $cod_inventario_semilla);
		$stmt->bindParam(":cod_empresa", $cod_empresa);
		$stmt->bindParam(":cantidad_semilla", $cantidad_semilla);
		$stmt->bindParam(":user_insert", $user_insert);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|It has been entered successfully.|" . $this->db_conexion->lastInsertId();
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_actualizar_fecha_semilla_por_empresa(
		$codigos_inventario,
		$ultima_actualizacion
	) {

		$SQL = "UPDATE
					bw_inventario_semillas_por_empresas
				SET
					ultima_actualizacion = :ultima_actualizacion
				WHERE
				cod_inventario IN (" . $codigos_inventario . ");";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":ultima_actualizacion", $ultima_actualizacion);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|It has been update successfully.|" . $this->db_conexion->lastInsertId();
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_actualizar_semilla_por_empresa_fecha_actualizacion(
		$cod_inventario,
		$cantidad_semilla,
		$ultima_actualizacion
	) {

		$SQL = "UPDATE
					bw_inventario_semillas_por_empresas
				SET
					cantidad_semilla = :cantidad_semilla,
					ultima_actualizacion = :ultima_actualizacion
				WHERE
					cod_inventario = :cod_inventario;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":ultima_actualizacion", $ultima_actualizacion);
		$stmt->bindParam(":cantidad_semilla", $cantidad_semilla);
		$stmt->bindParam(":cod_inventario", $cod_inventario);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|It has been update successfully.|" . $this->db_conexion->lastInsertId();
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_actualizar_semilla_por_empresa(
		$cod_inventario,
		$cantidad_semilla
	) {

		$SQL = "UPDATE
					bw_inventario_semillas_por_empresas
				SET
					cantidad_semilla = :cantidad_semilla
				WHERE
					cod_inventario = :cod_inventario;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cantidad_semilla", $cantidad_semilla);
		$stmt->bindParam(":cod_inventario", $cod_inventario);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|It has been update successfully.|" . $this->db_conexion->lastInsertId();
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_actualizar_semilla_por_empresa_en_orden_de_compra(
		$cod_inventario,
		$cod_info_empresa,
		$cantidad_semilla
	) {

		$SQL = "UPDATE
					bw_inventario_semillas_por_empresas
				SET
					cantidad_semilla = :cantidad_semilla + cantidad_semilla
				WHERE
					cod_inventario_semilla = :cod_inventario
					AND cod_empresa = :cod_info_empresa;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cantidad_semilla", $cantidad_semilla);
		$stmt->bindParam(":cod_inventario", $cod_inventario);
		$stmt->bindParam(":cod_info_empresa", $cod_info_empresa);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|It has been update successfully.|" . $this->db_conexion->lastInsertId();
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de variedades de semillas disponibles
	 */
	function inv_datos_semilla_por_empresa()
	{
		$SQL = "SELECT
					cod_inventario, cod_inventario_semilla, cod_empresa, cantidad_semilla
				FROM
					bw_inventario_semillas_por_empresas
				ORDER BY cod_inventario ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de variedades de semillas disponibles
	 */
	function inv_codigos_invetario($cod_empresa)
	{

		$this->deshabilitarGrupoFull();


		$SQL = "SELECT
					GROUP_CONCAT(cod_inventario) as codigos_inventario
				FROM
					bw_inventario_semillas_por_empresas
				WHERE
				cod_empresa = :cod_empresa
				GROUP BY cod_empresa;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_empresa", $cod_empresa);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		$this->habilitarGrupoFull();

		return $resultado;
	}
	/*
	 * Funciones para obtener listado de variedades de semillas disponibles
	 */
	function inv_datos_semilla_de_empresa_por_codigo_de_inventario($cod_inventario)
	{
		$SQL = "SELECT
					 cod_inventario_semilla, cod_empresa, cantidad_semilla
				FROM
					bw_inventario_semillas_por_empresas
				WHERE
				cod_inventario = :cod_inventario
				ORDER BY cod_inventario ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_inventario", $cod_inventario);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de variedades de semillas disponibles
	 */
	function inv_cantidad_total_semilla_por_cod_inventario_semilla($cod_inventario_semilla)
	{
		$SQL = "SELECT
					SUM(cantidad_semilla) as cantidad_semilla_consolidad
				FROM
					bw_inventario_semillas_por_empresas
				WHERE
					cod_inventario_semilla = :cod_inventario_semilla
				GROUP BY cod_inventario_semilla;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_inventario_semilla", $cod_inventario_semilla);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de semillas activos disponibles
	 */
	function inv_actualizar_cantidad_semilla_consolidad(
		$cod_inventario,
		$cantidad_semilla
	) {
		$SQL = "UPDATE
					bw_inventario_semilla
				SET
					cantidad_semilla = :cantidad_semilla
				WHERE
					cod_inventario = :cod_inventario;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cantidad_semilla", $cantidad_semilla);
		$stmt->bindParam(":cod_inventario", $cod_inventario);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|It has been update successfully.|" . $this->db_conexion->lastInsertId();
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de variedades de semillas disponibles
	 */
	function inv_buscar_cod_inventario_en_productos_proveedores($cod_detalle)
	{
		$SQL = "SELECT
					cod_inventario
				FROM
					bw_detalle_productos_proveedores
				WHERE
				cod_detalle = :cod_detalle;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_detalle", $cod_detalle);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Funciones para obtener listado de variedades de semillas disponibles
	 */
	function inv_buscar_cod_inventario_en_inventario_semilla_empresa($cod_inventario_semilla, $cod_empresa)
	{
		$SQL = "SELECT
					cod_inventario
				FROM
					bw_inventario_semillas_por_empresas
				WHERE
				cod_inventario_semilla = :cod_inventario_semilla
				AND cod_empresa = :cod_empresa;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_inventario_semilla", $cod_inventario_semilla);
		$stmt->bindParam(":cod_empresa", $cod_empresa);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}


	/**
	 * FUNCIONES PARA TEMPORADAS
	 */

	/*
	* Función para obtener listado de todas las temporadas
	*/
	function inv_listado_temporadas()
	{
		$SQL = "SELECT
					cod_temporada, temporada, nota, activo
				FROM
					far_temporadas
				ORDER BY cod_temporada DESC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*
	* Función para obtener listado de todas las temporadas activas
	*/
	function inv_listado_temporadas_activas()
	{
		$SQL = "SELECT
					cod_temporada, temporada, nota
				FROM
					far_temporadas
				WHERE 
					activo = 1
				ORDER BY temporada ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para obtener una temporada especifica
	*/
	function inv_obtener_temporada($cod_temporada)
	{
		$SQL = "SELECT
					cod_temporada, temporada, nota, activo
				FROM
					far_temporadas
				WHERE 
				cod_temporada = :cod_temporada;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_temporada",  $cod_temporada);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para eliminar una temporada especifica
	*/
	function inv_eliminar_temporada($cod_temporada)
	{
		$SQL = "DELETE FROM far_temporadas WHERE cod_temporada = :cod_temporada;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_temporada",  $cod_temporada);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para crear un nuevo registro de Temporada
	*/
	function inv_crear_temporada($temporada, $nota, $activo, $user_insert)
	{
		$SQL = "INSERT INTO
					far_temporadas (
						temporada,
						nota,
						activo,
						user_insert
					)
				VALUES
					(
						:temporada,
						:nota,
						:activo,
						:user_insert
					)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":temporada",  $temporada);
		$stmt->bindParam(":nota",  $nota);
		$stmt->bindParam(":activo",  $activo);
		$stmt->bindParam(":user_insert",  $user_insert);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Successful registration";
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			$resultado = "1|Failed to register|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para actualizar una temporada especifica
	*/
	function inv_actualizar_temporada($cod_temporada, $temporada, $nota, $activo)
	{
		$SQL = "UPDATE far_temporadas SET temporada = :temporada, nota = :nota, activo = :activo WHERE cod_temporada = :cod_temporada;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_temporada",  $cod_temporada);
		$stmt->bindParam(":temporada",  $temporada);
		$stmt->bindParam(":nota",  $nota);
		$stmt->bindParam(":activo",  $activo);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Successful update";
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			$resultado = "1|Update failed|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para cambiar el estado de activacion una temporada especifica
	*/
	function inv_cambiar_estado_temporada($cod_temporada, $activo)
	{
		$SQL = "UPDATE far_temporadas SET activo = :activo WHERE cod_temporada = :cod_temporada;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_temporada",  $cod_temporada);
		$stmt->bindParam(":activo",  $activo);
		try {
			$stmt->execute();
			$resultado = "0|Update completed successfully";
		} catch (PDOException $e) {
			$resultado = "1|Update failure";
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/**
	 * FUNCIONES PARA GRANJAS
	 */
	/*
	* Función para obtener listado de todas las granjas
	*/
	function inv_listado_granjas()
	{
		$SQL = "SELECT
					far_farms.cod_farms,
					far_farms.farm,
					far_farms.cod_estado,
					far_farms.activo,
					nombre AS nombre_estado
				FROM
					far_farms
						INNER JOIN
					bw_inventario_estados_plantaciones ON (bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado)
				ORDER BY cod_farms, activo DESC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*
	* Función para obtener listado de todas las granjas activas
	*/
	function inv_listado_granjas_activas()
	{
		$SQL = "SELECT
					far_farms.cod_farms,
					far_farms.farm,
					far_farms.cod_estado,
					nombre AS nombre_estado
				FROM
					far_farms
						INNER JOIN
					bw_inventario_estados_plantaciones ON (bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado)
				WHERE
					far_farms.activo = 1
				ORDER BY farm ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para obtener una granja especifica
	*/
	function inv_obtener_granja($cod_farms)
	{
		$SQL = "SELECT
					cod_farms, farm, cod_estado, activo
				FROM
					far_farms
				WHERE 
				cod_farms = :cod_farms;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_farms",  $cod_farms);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para eliminar una granja especifica
	*/
	function inv_eliminar_granja($cod_farms)
	{
		$SQL = "DELETE FROM far_farms WHERE cod_farms = :cod_farms;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_farms",  $cod_farms);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para crear un nuevo registro de granja
	*/
	function inv_crear_granja($farm, $cod_estado, $activo, $user_insert)
	{
		$SQL = "INSERT INTO
					far_farms (
						farm,
						cod_estado,
						activo,
						user_insert
					)
				VALUES
					(
						:farm,
						:cod_estado,
						:activo,
						:user_insert
					)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":farm",  $farm);
		$stmt->bindParam(":cod_estado",  $cod_estado);
		$stmt->bindParam(":activo",  $activo);
		$stmt->bindParam(":user_insert",  $user_insert);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Successful registration";
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			$resultado = "1|Failed to register|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para actualizar una farm especifica
	*/
	function inv_actualizar_granja($cod_farms, $farm, $cod_estado, $activo)
	{
		$SQL = "UPDATE
					far_farms
				SET
					farm = :farm,
					cod_estado = :cod_estado,
					activo = :activo
				WHERE
					cod_farms = :cod_farms;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_farms",  $cod_farms);
		$stmt->bindParam(":farm",  $farm);
		$stmt->bindParam(":cod_estado",  $cod_estado);
		$stmt->bindParam(":activo",  $activo);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Successful update";
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			$resultado = "1|Update failed|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para cambiar el estado de activacion una farm especifica
	*/
	function inv_cambiar_estado_granja($cod_farms, $activo)
	{
		$SQL = "UPDATE far_farms SET activo = :activo WHERE cod_farms = :cod_farms;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_farms",  $cod_farms);
		$stmt->bindParam(":activo",  $activo);
		try {
			$stmt->execute();
			$resultado = "0|Update completed successfully";
		} catch (PDOException $e) {
			$resultado = "1|Update failure";
		}
		$stmt->closeCursor();
		return $resultado;
	}


	/**
	 * FUNCIONES PARA CAMPOS
	 */

	/*
	* Función para obtener listado de todas las campos
	*/
	function inv_listado_campos()
	{
		$SQL = "SELECT
					far_fields.cod_field,
					far_fields.cod_farm,
					far_fields.field,
					far_fields.activo,
					far_farms.cod_estado,
					far_farms.farm AS nombre_granja,
					bw_inventario_estados_plantaciones.nombre AS nombre_estado
				FROM
					far_fields
						INNER JOIN
					far_farms ON (far_farms.cod_farms = far_fields.cod_farm)
                    INNER JOIN
					bw_inventario_estados_plantaciones ON (bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado)
				ORDER BY cod_field DESC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para obtener listado de todas las campos activos
	*/
	function inv_listado_campos_activos()
	{
		$SQL = "SELECT
                    far_fields.cod_field,
                    far_fields.cod_farm,
                    far_fields.field,
                    far_fields.activo,
                    far_farms.cod_estado,
                    far_farms.farm AS nombre_granja,
                    bw_inventario_estados_plantaciones.nombre AS nombre_estado
                FROM
                    far_fields
                        INNER JOIN
                    far_farms ON (far_farms.cod_farms = far_fields.cod_farm)
                    INNER JOIN
                    bw_inventario_estados_plantaciones ON (bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado)
                    WHERE
                        far_fields.activo = 1
                ORDER BY cod_field DESC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para obtener una campo especifico
	*/
	function inv_obtener_campo($cod_field)
	{
		$SQL = "SELECT
					cod_field, field, cod_farm, activo
				FROM
					far_fields
				WHERE 
				cod_field = :cod_field;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_field",  $cod_field);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para eliminar una campo especifico
	*/
	function inv_eliminar_campo($cod_field)
	{
		$SQL = "DELETE FROM far_fields WHERE cod_field = :cod_field;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_field",  $cod_field);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para crear un nuevo registro de campo
	*/
	function inv_crear_campo($cod_farm, $field, $activo, $user_insert)
	{
		$SQL = "INSERT INTO
					far_fields (
                        cod_farm,
						field,
						activo,
						user_insert
					)
				VALUES
					(
						:cod_farm,
						:field,
						:activo,
						:user_insert
					)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_farm",  $cod_farm);
		$stmt->bindParam(":field",  $field);
		$stmt->bindParam(":activo",  $activo);
		$stmt->bindParam(":user_insert",  $user_insert);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Successful registration";
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			$resultado = "1|Failed to register|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para actualizar una campo especifico
	*/
	function inv_actualizar_campo($cod_farm, $cod_field, $field,  $activo)
	{
		$SQL = "UPDATE
					far_fields
				SET
					cod_farm = :cod_farm,
					field = :field,
					activo = :activo
				WHERE
					cod_field = :cod_field;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_field",  $cod_field);
		$stmt->bindParam(":field",  $field);
		$stmt->bindParam(":cod_farm",  $cod_farm);
		$stmt->bindParam(":activo",  $activo);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Successful update";
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			$resultado = "1|Update failed|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para cambiar el estado de activacion una campo especifico
	*/
	function inv_cambiar_estado_campo($cod_field, $activo)
	{
		$SQL = "UPDATE far_fields
                SET
                    activo = :activo
                WHERE
                    cod_field = :cod_field;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_field",  $cod_field);
		$stmt->bindParam(":activo",  $activo);
		try {
			$stmt->execute();
			$resultado = "0|Update completed successfully";
		} catch (PDOException $e) {
			$resultado = "1|Update failure";
		}
		$stmt->closeCursor();
		return $resultado;
	}


	/**
	 * FUNCIONES PARA BLOQUES
	 */
	/*
	* Función para obtener listado de todas las bloques
	*/
	function inv_listado_bloques()
	{
		$SQL = "SELECT 
					far_bloques.cod_bloque,
					far_bloques.cod_farm,
					far_bloques.cod_field,
					far_bloques.bloque,
					far_bloques.acres,
					far_bloques.activo,
					far_farms.cod_estado,
					far_farms.farm AS nombre_granja,
					bw_inventario_estados_plantaciones.nombre AS nombre_estado,
					far_fields.field AS nombre_campo
				FROM
					far_bloques
						INNER JOIN
					far_fields ON (far_fields.cod_field = far_bloques.cod_field)
						INNER JOIN
					far_farms ON (far_farms.cod_farms = far_bloques.cod_farm)
						INNER JOIN
					bw_inventario_estados_plantaciones ON (bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado)
				ORDER BY cod_bloque DESC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para obtener listado de todos los bloques activos
	*/
	function inv_listado_bloques_activos()
	{
		$SQL = "SELECT
            far_bloques.cod_bloque,
            far_bloques.cod_farm,
            far_bloques.cod_field,
            far_bloques.bloque,
            far_bloques.acres,
            far_farms.cod_estado,
            far_farms.farm AS nombre_granja,
            bw_inventario_estados_plantaciones.nombre AS nombre_estado
          FROM
            far_bloques
                INNER JOIN
            far_farms ON (far_farms.cod_farms = far_bloques.cod_farm)
                INNER JOIN
            bw_inventario_estados_plantaciones ON (bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado)
          WHERE
            far_bloques.activo = 1
          ORDER BY cod_bloque DESC;";
		$stmt = $this->db_conexion->prepare($SQL);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para obtener una campo especifico
	*/
	function inv_obtener_bloque($cod_bloque)
	{
		$SQL = "SELECT
					cod_bloque,
					bloque,
					cod_farm,
					cod_field,
					activo
				FROM
					far_bloques
				WHERE
				cod_bloque = :cod_bloque;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_bloque",  $cod_bloque);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para eliminar una campo especifico
	*/
	function inv_eliminar_bloque($cod_bloque)
	{
		$SQL = "DELETE FROM far_bloques WHERE cod_bloque = :cod_bloque;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_bloque",  $cod_bloque);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para crear un nuevo registro de campo
	*/
	function inv_crear_bloque(
		$cod_farm,
		$cod_field,
		$bloque,
		$acres,
		$activo,
		$user_insert
	) {
		$SQL = "INSERT INTO
					far_bloques (
            			cod_farm,
            			cod_field,
						bloque,
						acres,
						activo,
						user_insert
					)
				VALUES
					(
						:cod_farm,
						:cod_field,
						:bloque,
						:acres,
						:activo,
						:user_insert
					)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_farm",  $cod_farm);
		$stmt->bindParam(":cod_field",  $cod_field);
		$stmt->bindParam(":bloque",  $bloque);
		$stmt->bindParam(":acres",  $acres);
		$stmt->bindParam(":activo",  $activo);
		$stmt->bindParam(":user_insert",  $user_insert);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Successful registration";
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			$resultado = "1|Failed to register|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para actualizar un bloque especifico
	*/
	function inv_actualizar_bloque(
		$cod_bloque,
		$cod_farm,
		$cod_field,
		$bloque,
		//$acres,
		$activo
	) {
		$SQL = "UPDATE
					far_bloques
				SET
					cod_farm = :cod_farm,
					cod_field = :cod_field,
					bloque = :bloque,
					activo = :activo
				WHERE
					cod_bloque = :cod_bloque;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_farm",  $cod_farm);
		$stmt->bindParam(":cod_field",  $cod_field);
		$stmt->bindParam(":bloque",  $bloque);
		//$stmt->bindParam(":acres",  $acres);
		$stmt->bindParam(":activo",  $activo);
		$stmt->bindParam(":cod_bloque",  $cod_bloque);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Successful update";
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			$resultado = "1|Update failed|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para cambiar el estado de activacion una campo especifico
	*/
	function inv_cambiar_estado_bloque($cod_bloque, $activo)
	{
		$SQL = "UPDATE far_bloques
                SET
                    activo = :activo
                WHERE
                    cod_bloque = :cod_bloque;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_bloque",  $cod_bloque);
		$stmt->bindParam(":activo",  $activo);
		try {
			$stmt->execute();
			$resultado = "0|Update completed successfully";
		} catch (PDOException $e) {
			$resultado = "1|Update failure";
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*
	* Guarda la cantidad de acres que se ingresa en la pantalla de Adminstracion de los bloques
	*/
	function inv_guardar_cantidad_acres_actuales($cod_bloque, $cantidad_acres)
	{
		$SQL = "UPDATE far_bloques
				SET
					acres = :cantidad_acres
                WHERE
                    cod_bloque = :cod_bloque;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_bloque",  $cod_bloque);
		$stmt->bindParam(":cantidad_acres",  $cantidad_acres);
		try {
			$stmt->execute();
			$resultado = "0|Update completed successfully";
		} catch (PDOException $e) {
			$resultado = "1|Update failure";
		}
		$stmt->closeCursor();
		return $resultado;
	}
}
