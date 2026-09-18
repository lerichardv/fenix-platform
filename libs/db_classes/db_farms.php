<?php
/*
 * Gestión de la base de datos relacionadas con las granjas.
 *
 * @author      Edwin Olivera
 * @date        2024-02-28
 */

class db_farms
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
		$sql_mode = "SET sql_mode = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';";
		$stmt = $this->db_conexion->prepare($sql_mode);
		try {
			$stmt->execute();
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			return $resultado;
		}
		$stmt->closeCursor();
	}

	/**
	 * FUNCIONES PARA TEMPORADAS
	 */

	/*
	* Función para obtener listado de todas las temporadas
	*/
	function farm_listado_temporadas()
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
	function farm_listado_temporadas_activas()
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
	function farm_obtener_temporada($cod_temporada)
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
	function farm_eliminar_temporada($cod_temporada)
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
	function farm_crear_temporada($temporada, $nota, $activo, $user_insert)
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
	function farm_actualizar_temporada($cod_temporada, $temporada, $nota, $activo)
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
	function farm_cambiar_estado_temporada($cod_temporada, $activo)
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
	function farm_listado_granjas()
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
				ORDER BY cod_farms DESC;";
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
	function farm_listado_granjas_activas()
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
	* Función para obtener listado de todas las granjas activas
	*/
	function farm_listado_granjas_con_datos_activas()
	{
		$SQL = "SELECT
					far_farms.cod_farms,
					far_farms.farm AS nombre_granja,
					far_farms.cod_estado,
					bw_inventario_estados_plantaciones.nombre AS nombre_estado,
					bw_inventario_estados_plantaciones.abreviatura,
					CONCAT(bw_inventario_estados_plantaciones.abreviatura,
							' - ',
							far_farms.farm) AS farm
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
	* Función para obtener listado de todas las granjas activas
	*/
	function farm_constructor_listado_granjas_por_estado_con_datos($cod_estado)
	{
		$SQL = "SELECT
					far_farms.cod_farms,
					far_farms.farm AS nombre_granja,
					far_farms.cod_estado,
					bw_inventario_estados_plantaciones.nombre AS nombre_estado,
					bw_inventario_estados_plantaciones.abreviatura,
					CONCAT(bw_inventario_estados_plantaciones.abreviatura,
							' - ',
							far_farms.farm) AS farm
				FROM
					far_farms
						INNER JOIN
					bw_inventario_estados_plantaciones ON (bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado)
				WHERE
					far_farms.activo = 1
					AND far_farms.cod_estado = :cod_estado
				ORDER BY farm ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_estado",  $cod_estado);
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
	function farm_listado_granjas_por_estado_con_datos($codigos_estados)
	{
		$SQL = "SELECT
					far_farms.cod_farms,
					far_farms.farm AS nombre_granja,
					far_farms.cod_estado,
					bw_inventario_estados_plantaciones.nombre AS nombre_estado,
					bw_inventario_estados_plantaciones.abreviatura,
					CONCAT(bw_inventario_estados_plantaciones.abreviatura,
							' - ',
							far_farms.farm) AS farm
				FROM
					far_farms
						INNER JOIN
					bw_inventario_estados_plantaciones ON (bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado)
				WHERE
					far_farms.activo = 1
					AND far_farms.cod_estado in ('" . $codigos_estados . "')
				ORDER BY farm ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		// $stmt->bindParam(":cod_estado",  $cod_estado);
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
	function farm_obtener_granja($cod_farms)
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
	function farm_eliminar_granja($cod_farms)
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
	function farm_crear_granja($farm, $cod_estado, $activo, $user_insert)
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
	function farm_actualizar_granja($cod_farms, $farm, $cod_estado, $activo)
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
	function farm_cambiar_estado_granja($cod_farms, $activo)
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
	function farm_listado_campos()
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
	function farm_listado_campos_activos()
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
	function farm_obtener_campo($cod_field)
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
	function farm_eliminar_campo($cod_field)
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
	function farm_crear_campo($cod_farm, $field, $activo, $user_insert)
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
	function farm_actualizar_campo($cod_farm, $cod_field, $field,  $activo)
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
	function farm_cambiar_estado_campo($cod_field, $activo)
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
	function farm_listado_bloques()
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
	function farm_listado_bloques_activos()
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
	* Función para obtener listado de todos los bloques activos
	*/
	function farm_listado_bloques_activos_por_campos($codigos_campos)
	{
		$this->deshabilitarGrupoFull();
		$SQL = "SELECT
					far_bloques.cod_bloque,
					far_bloques.cod_farm,
					far_bloques.cod_field,
					CONCAT(
						far_fields.field,
						' - ',
						far_bloques.bloque,
						' - ',COALESCE(far_crop_bloques.use_acres,far_bloques.acres),' - ',
						ROUND(
							(
								COALESCE(
									far_crop_bloques.use_acres,
									far_bloques.acres
								) / far_bloques.acres
							) * 100,
							0
						),'%'
					) AS bloque,
					far_bloques.acres,
					far_farms.cod_estado,
					far_farms.farm AS nombre_granja,
					bw_inventario_estados_plantaciones.nombre AS nombre_estado
				FROM
					far_bloques
					INNER JOIN far_fields ON (far_fields.cod_field = far_bloques.cod_field)
					INNER JOIN far_farms ON (far_farms.cod_farms = far_bloques.cod_farm)
					INNER JOIN bw_inventario_estados_plantaciones ON (
						bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado
					)
					LEFT JOIN far_crop_bloques ON (
						far_crop_bloques.cod_bloque = far_bloques.cod_bloque
					)
				WHERE
					far_bloques.activo = 1 and far_bloques.cod_field in (" . $codigos_campos . ")
					GROUP BY far_bloques.cod_bloque
					ORDER BY far_bloques.cod_bloque ASC;";
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
	* Función para obtener listado de todos los bloques activos
	*/
	function farm_listado_bloques_para_granja($codigos_campos)
	{
		$this->deshabilitarGrupoFull();
		$SQL = "SELECT
					far_bloques.cod_bloque,
					far_bloques.cod_farm,
					far_bloques.cod_field,
					CONCAT(
						far_fields.field,
						' - ',
						far_bloques.bloque,
						' - ',COALESCE(far_crop_bloques_implementados.use_acres,0),' - ',
						ROUND(
							COALESCE(
								far_crop_bloques_implementados.porcentaje_acre_usado,
								0
								),
							0
						),'%'
					) AS bloque,
					far_bloques.acres,
					far_farms.cod_estado,
					far_farms.farm AS nombre_granja,
					bw_inventario_estados_plantaciones.nombre AS nombre_estado
				FROM
					far_bloques
					INNER JOIN far_fields ON (far_fields.cod_field = far_bloques.cod_field)
					INNER JOIN far_farms ON (far_farms.cod_farms = far_bloques.cod_farm)
					INNER JOIN bw_inventario_estados_plantaciones ON (
						bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado
					)
					LEFT JOIN far_crop_bloques_implementados ON (
						far_crop_bloques_implementados.cod_bloque = far_bloques.cod_bloque
					)
				WHERE
					far_bloques.activo = 1 and far_bloques.cod_field in (" . $codigos_campos . ")
					GROUP BY far_bloques.cod_bloque
					ORDER BY far_bloques.cod_bloque ASC;";
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
	* Función para obtener listado de todos los bloques que no tenga algún semilla asiganda
	*/
	function farm_listado_bloques_libres($codigos_campos, $codigosBloquesUsados)
	{
		if ($codigosBloquesUsados == null) {
			$codigosBloquesUsados = 0;
		}
		$this->deshabilitarGrupoFull();
		$SQL = "SELECT
					far_bloques.cod_bloque,
					far_bloques.cod_farm,
					far_bloques.cod_field,
					CONCAT(
						far_fields.field,
						' - ',
						far_bloques.bloque,
						' - ',COALESCE(far_crop_bloques_implementados.use_acres,0),' - ',
						ROUND(
							COALESCE(
								far_crop_bloques_implementados.porcentaje_acre_usado,
								0
							),
							0
						),'%'
					) AS bloque,
					far_bloques.acres,
					far_farms.cod_estado,
					far_farms.farm AS nombre_granja,
					bw_inventario_estados_plantaciones.nombre AS nombre_estado
				FROM
					far_bloques
					INNER JOIN far_fields ON (far_fields.cod_field = far_bloques.cod_field)
					INNER JOIN far_farms ON (far_farms.cod_farms = far_bloques.cod_farm)
					INNER JOIN bw_inventario_estados_plantaciones ON (
						bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado
					)
					LEFT JOIN far_crop_bloques ON (
						far_crop_bloques.cod_bloque = far_bloques.cod_bloque
					)
					LEFT JOIN far_crop_bloques_implementados ON (
						far_crop_bloques_implementados.cod_bloque = far_bloques.cod_bloque
					)
				WHERE
					far_bloques.activo = 1
					AND far_bloques.cod_bloque NOT IN (" . $codigosBloquesUsados . ")
					and far_bloques.cod_field in (" . $codigos_campos . ")
					GROUP BY far_bloques.cod_bloque
					ORDER BY far_bloques.cod_bloque ASC;";
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
	* Función para obtener el grupo de bloques que ya esta asociados a semillas
	*/
	function farm_bloques_asociados_a_semillas()
	{
		$SQL = "SELECT cod_bloque as bloques_usados FROM far_semillas_en_bloques where activo = 1 and completado = 0; ";
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
	* Función para obtener el grupo de bloques que ya esta asociados a semillas
	*/
	function farm_bloques_asociados_a_semillas_ya_completados()
	{
		$SQL = "SELECT cod_bloque as bloques_usados FROM far_semillas_en_bloques where completado = 1; ";
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
	* Función para obtener el grupo de bloques que ya esta asociados a semillas
	*/
	function farm_bloques_asociados_a_semillas_registrados()
	{
		$SQL = "SELECT GROUP_CONCAT(cod_bloque) as bloques_usados FROM far_semillas_en_bloques; ";
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
	* Función para obtener la cantidad de semillas asociadas a un bloque especifico
	*/
	function farm_semilla_asociada_a_bloque($cod_bloque)
	{
		$this->deshabilitarGrupoFull();

		$SQL = "SELECT
					COUNT(far_semillas_en_bloques.cod_semilla) AS cantidad_semillas,
					far_semillas_en_bloques.cod_semilla,
					far_semillas_en_bloques.cod_unificacion,
					bw_inventario_semilla.nombre_semilla
				FROM
					far_semillas_en_bloques
						INNER JOIN
					bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = far_semillas_en_bloques.cod_semilla)
				WHERE
					far_semillas_en_bloques.cod_bloque = :cod_bloque and far_semillas_en_bloques.activo = 1 AND far_semillas_en_bloques.completado = 0
				GROUP BY far_semillas_en_bloques.cod_bloque;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_bloque", $cod_bloque);

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
	* Función para calcular cuantas semillas tiene un bloque implementado
	*/
	function farm_cantidad_semillas_en_unificacion_bloque($cod_unificacion)
	{

		$SQL = "SELECT
					COUNT(cod_semilla) AS cantidad_semilla_implementadas
				FROM
					far_crop_semillas_bloques
				WHERE
					cod_unificacion = :cod_unificacion AND far_crop_semillas_bloques.completada = 0 ;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_unificacion", $cod_unificacion);

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
	function farm_obtener_bloque($cod_bloque)
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
	function farm_eliminar_bloque($cod_bloque)
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
	function farm_crear_bloque(
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
	function farm_actualizar_bloque(
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
	function farm_cambiar_estado_bloque($cod_bloque, $activo)
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
	function farm_guardar_cantidad_acres_actuales($cod_bloque, $cantidad_acres)
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

	/**
	 * FUNCIONES PARA LA GESTION DE LAS PLANTACIONES DE LAS GRANJAS
	 */

	/*
	* Función para obtener listado de todas las bloques
	*/
	function farm_listado_plantaciones_granja()
	{
		$SQL = "SELECT 
					far_crop_rotations.cod_rotations,
					far_crop_rotations.cod_plantacion,
					far_crop_rotations.cod_transplante,
					far_crop_rotations.cod_field,
					far_crop_rotations.cod_semilla,
					far_crop_rotations.cod_farm,
					far_crop_rotations.cod_temporada,
					far_crop_rotations.planting_date,
					far_crop_rotations.comentarios as nombre_plantacion,
					far_crop_rotations.completado,
					far_crop_bloques.cod_crop_bloques,
					far_crop_bloques.cod_bloque,
					far_crop_bloques.bloque AS nombre_bloque,
					far_crop_bloques.ini_acres,
					far_crop_bloques.use_acres,
					far_crop_bloques.pra_acres,
					far_crop_bloques.teo_acres,
					bw_inventario_plantaciones.numero_orden,
					DATE_FORMAT(bw_inventario_plantaciones.fecha_inicial,
							'%Y-%m-%d') AS fecha_inicial,
					bw_inventario_trasplantes.numero_orden AS numero_orden_trasplante,
					bw_inventario_trasplantes.numero_ticket,
					far_fields.field AS nombre_campo,
					bw_inventario_semilla.nombre_semilla,
					bw_inventario_semilla.abreviatura_semilla,
					far_farms.farm AS nombre_granja,
					bw_inventario_estados_plantaciones.nombre AS nombre_estado,
					bw_inventario_estados_plantaciones.abreviatura,
					far_temporadas.temporada,
					bw_info_empresa.nombre_empresa
				FROM
					far_crop_rotations
						INNER JOIN
					far_crop_bloques ON (far_crop_bloques.cod_rotations = far_crop_rotations.cod_rotations)
						INNER JOIN
					bw_inventario_plantaciones ON (bw_inventario_plantaciones.cod_plantacion = far_crop_rotations.cod_plantacion)
						INNER JOIN
					bw_inventario_trasplantes ON (bw_inventario_trasplantes.cod_trasplante = far_crop_rotations.cod_transplante)
						INNER JOIN
					far_fields ON (far_fields.cod_field = far_crop_bloques.cod_field)
						INNER JOIN
					bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = far_crop_rotations.cod_semilla)
						INNER JOIN
					far_farms ON (far_farms.cod_farms = far_crop_bloques.cod_farm)
						INNER JOIN
					bw_inventario_estados_plantaciones ON (bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado)
						INNER JOIN
					far_temporadas ON (far_temporadas.cod_temporada = far_crop_rotations.cod_temporada)
						INNER JOIN
					bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_trasplantes.cod_info_empresa)
				ORDER BY bw_inventario_trasplantes.numero_orden , far_crop_rotations.cod_semilla , bw_inventario_plantaciones.fecha_inicial ASC;";
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
	* Función para obtener listado de todas las bloques
	*/
	function farm_listado_plantaciones_granja_no_completadas()
	{
		$SQL = "SELECT
					far_crop_rotations.cod_rotations,
					far_crop_rotations.cod_plantacion,
					far_crop_rotations.cod_transplante,
					far_crop_rotations.cod_field,
					far_crop_rotations.cod_semilla,
					far_crop_rotations.cod_farm,
					far_crop_rotations.cod_temporada,
					far_crop_rotations.planting_date,
					far_crop_rotations.comentarios as nombre_plantacion,
					far_crop_rotations.completado,
					far_crop_bloques.cod_crop_bloques,
					far_crop_bloques.cod_bloque,
					far_crop_bloques.completado,
					far_crop_bloques.bloque AS nombre_bloque,
					far_crop_bloques.ini_acres,
					far_crop_bloques.use_acres,
					ROUND(
						(
							far_crop_bloques.use_acres / far_crop_bloques.ini_acres
						) * 100,
						0
					) as porcentaje_acres,
					far_crop_bloques.pra_acres,
					far_crop_bloques.teo_acres,
					bw_inventario_plantaciones.numero_orden,
					DATE_FORMAT(
						bw_inventario_plantaciones.fecha_inicial,
						'%Y-%m-%d'
					) AS fecha_inicial,
					bw_inventario_trasplantes.numero_orden AS numero_orden_trasplante,
					bw_inventario_trasplantes.numero_ticket,
					far_fields.field AS nombre_campo,
					bw_inventario_semilla.nombre_semilla,
					far_farms.farm AS nombre_granja,
					bw_inventario_estados_plantaciones.abreviatura,
					bw_info_empresa.nombre_empresa
				FROM
					far_crop_rotations
					INNER JOIN far_crop_bloques ON (
						far_crop_bloques.cod_rotations = far_crop_rotations.cod_rotations
					)
					INNER JOIN bw_inventario_plantaciones ON (
						bw_inventario_plantaciones.cod_plantacion = far_crop_rotations.cod_plantacion
					)
					INNER JOIN bw_inventario_trasplantes ON (
						bw_inventario_trasplantes.cod_trasplante = far_crop_rotations.cod_transplante
					)
					INNER JOIN far_fields ON (
						far_fields.cod_field = far_crop_bloques.cod_field
					)
					INNER JOIN bw_inventario_semilla ON (
						bw_inventario_semilla.cod_inventario = far_crop_rotations.cod_semilla
					)
					INNER JOIN far_farms ON (
						far_farms.cod_farms = far_crop_bloques.cod_farm
					)
					INNER JOIN bw_inventario_estados_plantaciones ON (
						bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado
					)
					INNER JOIN bw_info_empresa ON (
						bw_info_empresa.cod_info_empresa = bw_inventario_trasplantes.cod_info_empresa
					)
				WHERE
					far_crop_bloques.completado = 0
				ORDER BY
					bw_inventario_trasplantes.numero_orden,
					far_crop_rotations.cod_semilla,
					bw_inventario_plantaciones.fecha_inicial ASC;";
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
	* Función para obtener listado de todas las bloques
	*/
	function farm_listado_de_bloques_filtrados_por_granjas_y_campos($codigosBloques, $codigoBloquesLibres)
	{
		$SQL = "SELECT
					far_bloques.cod_bloque,
					far_bloques.cod_farm,
					far_bloques.cod_field,
					far_bloques.bloque AS nombre_bloque,
					COALESCE(
						far_crop_bloques_implementados.ini_acres,
						far_bloques.acres
					) AS acres,
					far_bloques.activo,
					far_fields.field AS nombre_campo,
					far_farms.farm AS nombre_granja_simple,
					CONCAT(
						bw_inventario_estados_plantaciones.abreviatura,
						' - ',
						far_farms.farm
					) AS nombre_granja,
					bw_inventario_estados_plantaciones.nombre AS nombre_estado,
					bw_inventario_estados_plantaciones.abreviatura,
					COALESCE(
						far_crop_bloques_implementados.ini_acres,
						far_bloques.acres
					) AS ini_acres,
					COALESCE(far_crop_bloques_implementados.use_acres, 0) AS use_acres,
					ROUND(COALESCE(far_crop_bloques_implementados.porcentaje_acre_usado, 0),0) AS porcentaje_acres_usados,
					far_crop_bloques_implementados.cod_bloque_implementado,
					bw_inventario_estados_plantaciones.cod_estado,
					COALESCE(far_crop_bloques_implementados.cod_bloque_implementado,0)
				FROM
					far_bloques
					INNER JOIN far_fields ON (far_fields.cod_field = far_bloques.cod_field)
					INNER JOIN far_farms ON (far_farms.cod_farms = far_bloques.cod_farm)
					INNER JOIN bw_inventario_estados_plantaciones ON (
						bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado
					)
					LEFT JOIN far_crop_bloques_implementados ON (
						far_crop_bloques_implementados.cod_bloque = far_bloques.cod_bloque
					)
				WHERE
					far_bloques.cod_bloque IN (" . $codigosBloques . ")
					OR far_bloques.cod_bloque IN (" . $codigoBloquesLibres . ") ORDER BY far_fields.field, CAST(bloque AS UNSIGNED) ASC;";
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
	* Función para obtener listado de todas las bloques
	*/
	function farm_listado_plantaciones_granja_filtradas(
		$cod_estado,
		$cod_granja,
		$cod_campo
	) {
		$condiciones = ' WHERE ';
		if ($cod_estado != null && $cod_estado != '') {
			$condiciones .= "bw_inventario_estados_plantaciones.cod_estado IN ($cod_estado) ";
		}
		if ($cod_granja != null && $cod_granja != '') {
			$condiciones .= " AND far_farms.cod_farms IN ($cod_granja) ";
		}
		if ($cod_campo != null && $cod_campo != '') {
			$condiciones .= " AND far_fields.cod_field IN ($cod_campo) ";
		}
		if ($condiciones == ' WHERE ') {
			$condiciones = ' ';
		}
		$this->deshabilitarGrupoFull();
		$SQL = "SELECT
					far_crop_rotations.cod_rotations,
					far_crop_rotations.cod_plantacion,
					far_crop_rotations.cod_transplante,
					far_crop_rotations.cod_field,
					far_crop_rotations.cod_semilla,
					far_crop_rotations.cod_farm,
					far_crop_rotations.cod_temporada,
					far_crop_rotations.planting_date,
					far_crop_rotations.comentarios as nombre_plantacion,
					far_crop_bloques.completado,
					GROUP_CONCAT(DISTINCT far_crop_bloques.cod_crop_bloques) AS cod_crop_bloques_agrupados,
				    GROUP_CONCAT(DISTINCT far_crop_bloques.cod_bloque) AS cod_bloque,
					far_crop_bloques.cod_bloque,
					far_crop_bloques.bloque as nombre_bloque,
					far_crop_bloques.ini_acres,
					far_crop_bloques.use_acres,
					far_crop_bloques.pra_acres,
					far_crop_bloques.teo_acres,
					bw_inventario_plantaciones.numero_orden,
					DATE_FORMAT(
						bw_inventario_plantaciones.fecha_inicial,
						'%Y-%m-%d'
					) as fecha_inicial,
					bw_inventario_trasplantes.numero_orden AS numero_orden_trasplante,
					bw_inventario_trasplantes.numero_ticket,
				GROUP_CONCAT(DISTINCT far_fields.cod_field) as cod_field,
				GROUP_CONCAT(DISTINCT far_fields.field) as nombre_campo,
					bw_inventario_semilla.nombre_semilla,
					bw_inventario_semilla.abreviatura_semilla,
					far_farms.farm as nombre_granja,
					bw_inventario_estados_plantaciones.nombre as nombre_estado,
					bw_inventario_estados_plantaciones.abreviatura,
					far_temporadas.temporada,
					bw_info_empresa.nombre_empresa
				FROM
					far_crop_rotations
					INNER JOIN far_crop_bloques ON (
						far_crop_bloques.cod_rotations = far_crop_rotations.cod_rotations
					)
					INNER JOIN bw_inventario_plantaciones ON (
						bw_inventario_plantaciones.cod_plantacion = far_crop_rotations.cod_plantacion
					)
					INNER JOIN bw_inventario_trasplantes ON (
						bw_inventario_trasplantes.cod_trasplante = far_crop_rotations.cod_transplante
					)
					INNER JOIN far_fields ON (
						far_fields.cod_field = far_crop_bloques.cod_field
					)
					INNER JOIN bw_inventario_semilla ON (
						bw_inventario_semilla.cod_inventario = far_crop_rotations.cod_semilla
					)
					INNER JOIN far_farms ON (
						far_farms.cod_farms = far_crop_rotations.cod_farm
					)
					INNER JOIN bw_inventario_estados_plantaciones ON (
						bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado
					)
					INNER JOIN far_temporadas ON (
						far_temporadas.cod_temporada = far_crop_rotations.cod_temporada
					)
					INNER JOIN bw_info_empresa ON (
						bw_info_empresa.cod_info_empresa = bw_inventario_trasplantes.cod_info_empresa
					)
					group by far_crop_rotations.cod_rotations, far_fields.cod_field
					" . $condiciones . "
				ORDER BY
					bw_inventario_trasplantes.numero_orden,
					far_crop_rotations.cod_semilla,
					bw_inventario_plantaciones.fecha_inicial ASC;";
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
	* Función para obtener listado de todos los bloques activos
	*/
	function farm_listado_plantaciones_granja_activos()
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
	* Función para obtener listado de todos los bloques activos
	*/
	function farm_listado_plantaciones_granja_activos_por_campos($codigos_campos)
	{
		$SQL = "SELECT
            far_bloques.cod_bloque,
            far_bloques.cod_farm,
            far_bloques.cod_field,
            CONCAT(far_fields.field,' - ',far_bloques.bloque,' - ',far_bloques.acres) as bloque,
            far_bloques.acres,
            far_farms.cod_estado,
            far_farms.farm AS nombre_granja,
            bw_inventario_estados_plantaciones.nombre AS nombre_estado
		FROM
            far_bloques
				INNER JOIN
			far_fields ON (far_fields.cod_field = far_bloques.cod_field)
                INNER JOIN
            far_farms ON (far_farms.cod_farms = far_bloques.cod_farm)
                INNER JOIN
            bw_inventario_estados_plantaciones ON (bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado)
          WHERE
            far_bloques.activo = 1 and far_bloques.cod_field in (" . $codigos_campos . ")
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
	function farm_obtener_plantaciones_granja($cod_rotations)
	{
		$SQL = "SELECT
					far_crop_rotations.cod_rotations,
					far_crop_rotations.cod_plantacion,
					far_crop_rotations.cod_transplante,
					far_crop_rotations.cod_field,
					far_crop_rotations.cod_semilla,
					far_crop_rotations.cod_farm,
					far_crop_rotations.cod_temporada,
					DATE_FORMAT(far_crop_rotations.planting_date,
							'%m-%d-%Y') AS planting_date,
					far_crop_rotations.comentarios,
					far_crop_rotations.completado,
					far_crop_bloques.cod_bloque,
					far_crop_bloques.bloque AS nombre_bloque,
					far_crop_bloques.ini_acres,
					far_crop_bloques.use_acres,
					far_crop_bloques.pra_acres,
					far_crop_bloques.teo_acres,
					bw_inventario_plantaciones.numero_orden,
					DATE_FORMAT(bw_inventario_plantaciones.fecha_inicial,
							'%m-%d-%Y') AS fecha_inicial,
					bw_inventario_trasplantes.numero_orden AS numero_orden_trasplante,
					bw_inventario_trasplantes.numero_ticket,
					far_fields.field AS nombre_campo,
					bw_inventario_semilla.nombre_semilla,
					bw_inventario_semilla.abreviatura_semilla,
					far_farms.farm AS nombre_granja,
					bw_inventario_estados_plantaciones.cod_estado,
					bw_inventario_estados_plantaciones.nombre AS nombre_estado,
					far_temporadas.temporada,
					bw_info_empresa.nombre_empresa
				FROM
					far_crop_rotations
						INNER JOIN
					far_crop_bloques ON (far_crop_bloques.cod_rotations = far_crop_rotations.cod_rotations)
						INNER JOIN
					bw_inventario_plantaciones ON (bw_inventario_plantaciones.cod_plantacion = far_crop_rotations.cod_plantacion)
						INNER JOIN
					bw_inventario_trasplantes ON (bw_inventario_trasplantes.cod_trasplante = far_crop_rotations.cod_transplante)
						INNER JOIN
					far_fields ON (far_fields.cod_field = far_crop_rotations.cod_field)
						INNER JOIN
					bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = far_crop_rotations.cod_semilla)
						INNER JOIN
					far_farms ON (far_farms.cod_farms = far_crop_rotations.cod_farm)
						INNER JOIN
					bw_inventario_estados_plantaciones ON (bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado)
						INNER JOIN
					far_temporadas ON (far_temporadas.cod_temporada = far_crop_rotations.cod_temporada)
						INNER JOIN
					bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_trasplantes.cod_info_empresa)
				WHERE
					far_crop_rotations.cod_rotations = :cod_rotations
				ORDER BY cod_rotations DESC";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_rotations",  $cod_rotations);
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
	function farm_eliminar_plantaciones_granja($cod_rotations, $cod_crop_bloques)
	{
		//Eliminación de datos asociados de los bloques vinculados a la plantación en granja
		$SQL = "DELETE FROM
					far_crop_bloques
				WHERE
					cod_crop_bloques = :cod_crop_bloques;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_crop_bloques",  $cod_crop_bloques);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Successful elimination";
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			$resultado = "1|Delete failed|" . $e->getMessage();
		}
		$stmt->closeCursor();

		//Eliminación del registro principal (la eliminación tendra exito sino esta asociada a ningún dato extra)
		$SQL = "DELETE FROM
					far_crop_rotations
				WHERE
					cod_rotations = :cod_rotations;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_rotations",  $cod_rotations);
		try {
			$stmt->execute();
		} catch (PDOException $e) {
			// $resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*
	* Función para eliminar una semilla especifica
	*/
	function farm_eliminar_permanentemente_semilla_de_plantacion($cod_semilla_bloque)
	{
		$SQL = "DELETE FROM
					far_crop_semillas_bloques
				WHERE
					cod_semilla_bloque = :cod_semilla_bloque;";
		// $SQL = "UPDATE
		// 			far_crop_semillas_bloques
		// 		SET
		// 			completada = 1
		// 		WHERE
		// 			cod_semilla_bloque = :cod_semilla_bloque;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_semilla_bloque",  $cod_semilla_bloque);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Successful elimination";
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			$resultado = "1|Delete failed|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*
	* Función para eliminar una semilla especifica
	*/
	function farm_eliminar_semilla_de_plantacion($cod_semilla_bloque)
	{
		// $SQL = "DELETE FROM
		// 			far_crop_semillas_bloques
		// 		WHERE
		// 			cod_semilla_bloque = :cod_semilla_bloque;";
		$SQL = "UPDATE
					far_crop_semillas_bloques
				SET
					completada = 1
				WHERE
					cod_semilla_bloque = :cod_semilla_bloque;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_semilla_bloque",  $cod_semilla_bloque);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Successful elimination";
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			$resultado = "1|Delete failed|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	function farm_eliminar_semilla_asociada($cod_semilla, $cod_bloque)
	{
		$SQL = "DELETE FROM
				far_semillas_en_bloques
			WHERE
				cod_semilla = :cod_semilla
				AND cod_bloque = :cod_bloque;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_semilla",  $cod_semilla);
		$stmt->bindParam(":cod_bloque",  $cod_bloque);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Successful elimination";
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			$resultado = "1|Delete failed|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para crear un nuevo registro de una plantacion de granja
	*/
	function farm_crear_plantaciones_granja(
		$cod_plantacion,
		$cod_transplante,
		$cod_field,
		$cod_semilla,
		$cod_farm,
		$cod_temporada,
		$planting_date,
		$comentarios,
		$user_insert
	) {
		$SQL = "INSERT INTO
					far_crop_rotations (
						cod_plantacion,
						cod_transplante,
						cod_field,
						cod_semilla,
						cod_farm,
						cod_temporada,
						planting_date,
						comentarios,
						user_insert
					)
				VALUES
					(
						:cod_plantacion,
						:cod_transplante,
						:cod_field,
						:cod_semilla,
						:cod_farm,
						:cod_temporada,
						:planting_date,
						:comentarios,
						:user_insert
					)";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_plantacion", $cod_plantacion);
		$stmt->bindParam(":cod_transplante", $cod_transplante);
		$stmt->bindParam(":cod_field", $cod_field);
		$stmt->bindParam(":cod_semilla", $cod_semilla);
		$stmt->bindParam(":cod_farm", $cod_farm);
		$stmt->bindParam(":cod_temporada", $cod_temporada);
		$stmt->bindParam(":planting_date", $planting_date);
		$stmt->bindParam(":comentarios", $comentarios);
		$stmt->bindParam(":user_insert", $user_insert);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Successful registration|" . $this->db_conexion->lastInsertId();
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			$resultado = "1|Failed to register|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*
	* Función para crear un nuevo registro los bloques usados en las plantaciones
	*/
	function farm_crear_registro_bloques_en_plantaciones_granja(
		$cod_rotations,
		$cod_farm,
		$cod_field,
		$cod_bloque,
		$bloque,
		$ini_acres,
		$use_acres,
		$pra_acres,
		$teo_acres,
		$user_insert
	) {
		$SQL = "INSERT INTO
					far_crop_bloques (
						cod_rotations,
						cod_farm,
						cod_field,
						cod_bloque,
						bloque,
						ini_acres,
						use_acres,
						pra_acres,
						teo_acres,
						user_insert
					)
				VALUES
					(
						:cod_rotations,
						:cod_farm,
						:cod_field,
						:cod_bloque,
						:bloque,
						:ini_acres,
						:use_acres,
						:pra_acres,
						:teo_acres,
						:user_insert
					);";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_rotations", $cod_rotations);
		$stmt->bindParam(":cod_farm", $cod_farm);
		$stmt->bindParam(":cod_field", $cod_field);
		$stmt->bindParam(":cod_bloque", $cod_bloque);
		$stmt->bindParam(":bloque", $bloque);
		$stmt->bindParam(":ini_acres", $ini_acres);
		$stmt->bindParam(":use_acres", $use_acres);
		$stmt->bindParam(":pra_acres", $pra_acres);
		$stmt->bindParam(":teo_acres", $teo_acres);
		$stmt->bindParam(":user_insert", $user_insert);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Successful registration|" . $this->db_conexion->lastInsertId();
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			$resultado = "1|Failed to register Blocks|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para crear un nuevo registro de los movimientos que tiene los acres en los bloques
	*/
	function farm_crear_registro_movinto_acres_en_bloque(
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
	function farm_actualizar_plantaciones_granja(
		$cod_rotations,
		$cod_plantacion,
		$cod_transplante,
		$cod_field,
		$cod_semilla,
		$cod_farm,
		$cod_temporada,
		$planting_date,
		$comentarios,
		$completado
	) {
		$SQL = "UPDATE
					far_crop_rotations
				SET
					cod_plantacion = :cod_plantacion,
					cod_transplante = :cod_transplante,
					cod_field = :cod_field,
					cod_semilla = :cod_semilla,
					cod_farm = :cod_farm,
					cod_temporada = :cod_temporada,
					planting_date = :planting_date,
					comentarios = :comentarios,
					completado = :completado
				WHERE
					cod_rotations = :cod_rotations;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_rotations", $cod_rotations);
		$stmt->bindParam(":cod_plantacion", $cod_plantacion);
		$stmt->bindParam(":cod_transplante", $cod_transplante);
		$stmt->bindParam(":cod_field", $cod_field);
		$stmt->bindParam(":cod_semilla", $cod_semilla);
		$stmt->bindParam(":cod_farm", $cod_farm);
		$stmt->bindParam(":cod_temporada", $cod_temporada);
		$stmt->bindParam(":planting_date", $planting_date);
		$stmt->bindParam(":comentarios", $comentarios);
		$stmt->bindParam(":completado", $completado);
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
	function farm_cambiar_estado_plantaciones_granja($cod_bloque, $activo)
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
	* Función para obtener el listado de los trasplantes completados
	*/
	function farm_listado_trasplantes_completados()
	{
		$SQL = "SELECT
					cod_trasplante, cod_inventario, numero_orden, numero_ticket
				FROM
					bw_inventario_trasplantes
				WHERE
					completado = 1;";
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
	* Función para obtener el listado de los trasplantes completados
	*/
	function farm_listado_trasplantes_por_estado_completados($cod_estado)
	{
		$SQL = "SELECT 
					bw_inventario_trasplantes.numero_orden,
					bw_inventario_trasplantes.numero_ticket
				FROM
					bw_inventario_plantaciones
						INNER JOIN
					bw_inventario_trasplantes ON (bw_inventario_trasplantes.cod_plantacion = bw_inventario_plantaciones.cod_plantacion)
				WHERE
					bw_inventario_plantaciones.cod_estado = :cod_estado
						AND bw_inventario_trasplantes.completado = 1;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_estado",  $cod_estado);

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
	* Función para obtener el listado de todas las semillas asociadasas a un bloque en la plantación
	*/
	function farm_listado_semillas_asociadas_a_bloques($cod_bloque)
	{
		$SQL = "SELECT
		far_crop_bloques_implementados.cod_bloque_implementado,
		far_crop_bloques_implementados.cod_bloque,
		far_crop_bloques_implementados.ini_acres,
		far_crop_bloques_implementados.acres_disponibles,
		far_crop_semillas_bloques.cod_semilla_bloque,
		far_crop_semillas_bloques.cod_semilla,
		far_crop_semillas_bloques.cod_plantacion,
		far_crop_semillas_bloques.cod_trasplante,
		far_crop_semillas_bloques.acres_usados,
		far_crop_semillas_bloques.cod_temporada,
		ROUND(far_crop_semillas_bloques.porcentaje_acre_usado,0) as porcentaje_acre_usado,
		far_crop_semillas_bloques.fecha_plantacion as fecha_plantacion_original,
		DATE_FORMAT(bw_inventario_plantaciones.fecha_final,
							'%Y-%m-%d') AS fecha_plantacion,
		DATE_FORMAT(far_crop_semillas_bloques.fecha_plantacion,
							'%Y-%m-%d') AS fecha_plantacion_registrada,
		bw_inventario_semilla.nombre_semilla,
		bw_inventario_plantaciones.cod_inventario,
		bw_inventario_plantaciones.numero_orden,
		bw_inventario_plantaciones.fecha_inicial,
		bw_inventario_plantaciones.edad,
		bw_inventario_trasplantes.numero_orden,
		bw_inventario_trasplantes.numero_ticket,
		bw_inventario_trasplantes.fecha_entrega
		FROM
			far_crop_bloques_implementados
			INNER JOIN far_crop_semillas_bloques ON (
				far_crop_semillas_bloques.cod_bloque_implementado = far_crop_bloques_implementados.cod_bloque_implementado
			)
			INNER JOIN bw_inventario_semilla ON (
				bw_inventario_semilla.cod_inventario = far_crop_semillas_bloques.cod_semilla
			)
			INNER JOIN bw_inventario_plantaciones ON (
				bw_inventario_plantaciones.cod_plantacion = far_crop_semillas_bloques.cod_plantacion
			)
			INNER JOIN bw_inventario_trasplantes ON (
				bw_inventario_trasplantes.cod_trasplante = far_crop_semillas_bloques.cod_trasplante
			)
		WHERE
		far_crop_bloques_implementados.cod_bloque = :cod_bloque AND far_crop_semillas_bloques.completada = 0;";
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
	* Función para obtener el listado de los trasplantes completados
	*/
	function farm_listado_semillas_trasplantes_completados($numero_orden)
	{
		$this->deshabilitarGrupoFull();
		$SQL = "SELECT 
					bw_inventario_trasplantes.cod_trasplante,
					bw_inventario_semilla.cod_inventario,
					bw_inventario_semilla.nombre_semilla,
					bw_inventario_plantaciones.edad
				FROM
					bw_inventario_trasplantes
						INNER JOIN
					bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_inventario_trasplantes.cod_inventario)
						LEFT JOIN
					bw_inventario_plantaciones ON (bw_inventario_plantaciones.cod_plantacion = bw_inventario_trasplantes.cod_plantacion)
				WHERE
				bw_inventario_trasplantes.numero_orden = :numero_orden
					AND bw_inventario_trasplantes.completado = 1
				GROUP BY bw_inventario_semilla.cod_inventario;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":numero_orden",  $numero_orden);
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
	* Función para obtener el listado de los trasplantes completados
	*/
	function farm_listado_semillas_trasplantes_completados_por_estado($cod_estado)
	{
		$this->deshabilitarGrupoFull();
		$SQL = "SELECT
					bw_inventario_plantaciones.cod_plantacion,
					bw_inventario_plantaciones.cod_inventario,
					bw_inventario_plantaciones.edad,
					bw_inventario_plantaciones.fecha_de_orden,
					bw_inventario_plantaciones.fecha_inicial,
					bw_inventario_trasplantes.numero_orden,
					bw_inventario_trasplantes.numero_ticket,
					CONCAT(
						bw_inventario_semilla.nombre_semilla,
						' | ',
						DATE_FORMAT(
							bw_inventario_plantaciones.fecha_final,
							'%Y-%m-%d'
						),
						' | ',
						bw_inventario_trasplantes.numero_orden,
						'-',
						bw_inventario_trasplantes.numero_ticket, ' | ',bw_inventario_plantaciones.edad
					) AS datos_semilla,
					bw_inventario_trasplantes.cod_trasplante,
					bw_inventario_semilla.nombre_semilla
				FROM
					bw_inventario_plantaciones
					INNER JOIN bw_inventario_trasplantes ON (
						bw_inventario_trasplantes.cod_plantacion = bw_inventario_plantaciones.cod_plantacion
					)
					INNER JOIN bw_inventario_semilla ON (
						bw_inventario_semilla.cod_inventario = bw_inventario_plantaciones.cod_inventario
					)
				WHERE
					bw_inventario_plantaciones.cod_estado = :cod_estado
					AND bw_inventario_plantaciones.fecha_final >= '2024-06-01'
					AND bw_inventario_trasplantes.completado = 1
					AND bw_inventario_trasplantes.implementado = 0
					ORDER BY bw_inventario_semilla.nombre_semilla, bw_inventario_plantaciones.edad ASC;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_estado",  $cod_estado);
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
	* Función para obtener el listado los campos asociados a un estado o una granja
	*/
	function farm_listado_campo_por_estado_granja($cod_estado, $cod_granja)
	{
		$this->deshabilitarGrupoFull();
		$SQL = "SELECT
					far_fields.cod_field,
					far_fields.field,
					far_farms.cod_farms,
					far_farms.farm,
					bw_inventario_estados_plantaciones.cod_estado,
					bw_inventario_estados_plantaciones.nombre AS estado
				FROM
					far_fields
					INNER JOIN far_farms ON (
						far_farms.cod_farms = far_fields.cod_farm
					)
					INNER JOIN bw_inventario_estados_plantaciones ON (
						bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado
					)
				WHERE
					far_farms.cod_farms = :cod_granja
					and far_farms.cod_estado = :cod_estado
				ORDER BY
					far_fields.field;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_estado",  $cod_estado);
		$stmt->bindParam(":cod_granja",  $cod_granja);
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
	* 
	*/
	function farm_listado_granjas_por_estados($indicesEstados)
	{
		$SQL = "SELECT
					farm, cod_farms
				FROM
					far_farms
				WHERE
					cod_estado  IN (" . $indicesEstados . ");";
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
	* Función para obtener el listado los campos asociados a un estado o una granja
	*/
	function farm_listado_campo_por_granjas($indicesGranjas)
	{
		// $this->deshabilitarGrupoFull();
		$SQL = "SELECT 
					far_fields.cod_field,
					far_fields.field
				FROM
					far_fields
				WHERE
					cod_farm IN (" . $indicesGranjas . ");";
		$stmt = $this->db_conexion->prepare($SQL);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		// $this->habilitarGrupoFull();

		return $resultado;
	}

	/*
	* Función para obtener el listado los campos asociados a un estado o una granja
	*/
	function farm_listado_campos_por_estados_granjas($cod_estado, $cod_granja)
	{
		$this->deshabilitarGrupoFull();
		$SQL = "SELECT
					far_fields.cod_field,
					far_fields.field,
					far_farms.cod_farms,
					far_farms.farm,
					bw_inventario_estados_plantaciones.cod_estado,
					bw_inventario_estados_plantaciones.nombre AS estado
				FROM
					far_fields
					INNER JOIN far_farms ON (
						far_farms.cod_farms = far_fields.cod_farm
					)
					INNER JOIN bw_inventario_estados_plantaciones ON (
						bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado
					)
				WHERE
					far_farms.cod_farms in ('" . $cod_granja . "')
					and far_farms.cod_estado in ('" . $cod_estado . "')
				ORDER BY
					far_fields.field;";
		$stmt = $this->db_conexion->prepare($SQL);
		// $stmt->bindParam(":cod_estado",  $cod_estado);
		// $stmt->bindParam(":cod_granja",  $cod_granja);
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

	// -------------Funciones para guardar plantaciones de granjas--------------------
	/*
	* Función para obtener los datos de los trasplante para la creación de registros necesarios
	*/
	function farm_buscar_datos_trasplantes(
		$numero_orden,
		$numero_ticket,
		$cod_inventario
	) {
		$SQL = "SELECT
					cod_plantacion,
					cod_trasplante
				FROM
					bw_inventario_trasplantes
				WHERE
					numero_orden = :numero_orden
						AND numero_ticket = :numero_ticket
						AND cod_inventario = :cod_inventario;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":numero_orden",  $numero_orden);
		$stmt->bindParam(":numero_ticket",  $numero_ticket);
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
	* Función para obtener los datos de los trasplante para la creación de registros necesarios
	*/
	function farm_buscar_datos_campo(
		$cod_field
	) {
		$SQL = "SELECT 
					cod_field, cod_farm
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
	* Función para obtener los datos de los trasplante para la creación de registros necesarios
	*/
	function farm_buscar_datos_bloque(
		$cod_bloque
	) {
		$SQL = "SELECT
					cod_bloque, bloque, cod_farm, cod_field, acres AS acres_originales
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
	* Función para cambiar el estados de completación de una plantación de granja especifica
	*/
	function farm_cambiar_estado_completacion_plantacion_granja($far_crop_bloques, $activo)
	{
		$SQL = "UPDATE
					far_crop_bloques
				SET
					completado = :activo
				WHERE
					cod_crop_bloques = :far_crop_bloques;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":far_crop_bloques",  $far_crop_bloques);
		$stmt->bindParam(":activo",  $activo);
		try {
			$stmt->execute();
			$resultado = "0|Update completed successfully";
		} catch (PDOException $error) {
			$resultado = "1|Update failure|" . $error->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Guarda la cantidad de acres que se ingresa en la pantalla de Adminstracion de los bloques
	*/
	function farm_guardar_cantidad_acres_en_plantacion_granja($cod_bloque, $cantidad_acres)
	{
		$SQL = "UPDATE
					far_crop_bloques
				SET
					use_acres = :cantidad_acres,
					pra_acres = ini_acres - :cantidad_acres,
					teo_acres = ini_acres - :cantidad_acres
				WHERE
					cod_crop_bloques = :cod_bloque;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_bloque",  $cod_bloque);
		$stmt->bindParam(":cantidad_acres",  $cantidad_acres);
		try {
			$stmt->execute();
			$resultado = "0|Update completed successfully";
		} catch (PDOException $e) {
			$resultado = "1|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Guarda la fecha de plantacion de una semilla especifica
	*/
	function farm_guardar_fecha_de_plantacion_granja_semilla($cod_semilla_bloque, $fecha_nueva)
	{
		$SQL = "UPDATE
					far_crop_semillas_bloques
				SET
					fecha_plantacion = :fecha_nueva
				WHERE
					cod_semilla_bloque = :cod_semilla_bloque AND completada = 0;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_semilla_bloque", $cod_semilla_bloque);
		$stmt->bindParam(":fecha_nueva", $fecha_nueva);
		try {
			$stmt->execute();
			$resultado = "0|Update completed successfully";
		} catch (PDOException $e) {
			$resultado = "1|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*
	* Guarda la cantidad de acres que se ingresa en la pantalla de Adminstracion de los bloques
	*/
	function farm_guardar_cantidad_acres_en_plantacion_granja_por_semilla(
		$cod_semila_bloque,
		$cantidad_acres,
		$porcentaje_acres
	) {
		$SQL = "UPDATE
					far_crop_semillas_bloques
				SET
					acres_usados = :cantidad_acres,
					porcentaje_acre_usado = :porcentaje_acres
				WHERE
					cod_semilla_bloque = :cod_semila_bloque AND completada = 0;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_semila_bloque",  $cod_semila_bloque);
		$stmt->bindParam(":cantidad_acres",  $cantidad_acres);
		$stmt->bindParam(":porcentaje_acres",  $porcentaje_acres);
		try {
			$stmt->execute();
			$resultado = "0|Update completed successfully";
		} catch (PDOException $e) {
			$resultado = "1|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*
	* Guarda la cantidad de acres que se ingresa en la pantalla de Adminstracion de los bloques
	*/
	function farm_guardar_cantidad_acres_en_bloque_implementado(
		$cod_bloque_implementado,
		$cantidad_acres,
		$porcentaje_acres
	) {
		$SQL = "UPDATE
					far_crop_bloques_implementados
				SET
					use_acres = :cantidad_acres,
					acres_disponibles = ini_acres - :cantidad_acres,
					porcentaje_acre_usado = :porcentaje_acres
				WHERE
					cod_bloque_implementado = :cod_bloque_implementado;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_bloque_implementado",  $cod_bloque_implementado);
		$stmt->bindParam(":cantidad_acres",  $cantidad_acres);
		$stmt->bindParam(":porcentaje_acres",  $porcentaje_acres);
		try {
			$stmt->execute();
			$resultado = "0|Update completed successfully";
		} catch (PDOException $e) {
			$resultado = "1|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*
	* Guarda la cantidad de acres que se ingresa en la pantalla de Adminstracion de los bloques
	*/
	function farm_guardar_fecha_seleccionada_plantacion_granja($cod_rotations, $fecha_ingresada)
	{
		$SQL = "UPDATE
					far_crop_rotations
				SET
					planting_date = :fecha_ingresada
				WHERE
					cod_rotations = :cod_rotations;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":fecha_ingresada",  $fecha_ingresada);
		$stmt->bindParam(":cod_rotations",  $cod_rotations);
		try {
			$stmt->execute();
			$resultado = "0|Update completed successfully";
		} catch (PDOException $e) {
			$resultado = "1|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para obtener listado de todos los estados asociados a un trasplante, por el numero de orden
	*/
	function farm_listado_estados_por_numero_orden($numero_orden)
	{
		$this->deshabilitarGrupoFull();
		$SQL = "SELECT 
					bw_inventario_trasplantes.numero_orden,
					bw_inventario_trasplantes.cod_trasplante,
					bw_inventario_plantaciones.cod_plantacion,
					bw_inventario_plantaciones.cod_estado,
					bw_inventario_estados_plantaciones.cod_estado,
					bw_inventario_estados_plantaciones.nombre,
					bw_inventario_estados_plantaciones.abreviatura
				FROM
					bw_inventario_trasplantes
						INNER JOIN
					bw_inventario_plantaciones ON (bw_inventario_plantaciones.cod_plantacion = bw_inventario_trasplantes.cod_plantacion)
						INNER JOIN
					bw_inventario_estados_plantaciones ON (bw_inventario_estados_plantaciones.cod_estado = bw_inventario_plantaciones.cod_estado)
				WHERE
					bw_inventario_trasplantes.numero_orden = :numero_orden
				GROUP BY bw_inventario_estados_plantaciones.nombre
				ORDER BY bw_inventario_estados_plantaciones.nombre;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":numero_orden",  $numero_orden);
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
	* Función para crear un nuevo registro de campo
	*/
	function farm_crear_campo_implementado(
		$cod_bloque,
		$cod_field,
		$cod_farm,
		$libre,
		$reseteado,
		$multiple_semillas,
		$ini_acres,
		$use_acres,
		$acres_disponibles,
		$porcentaje_acre_usado,
		$user_insert
	) {
		$SQL = "INSERT INTO
					far_crop_bloques_implementados (
						cod_bloque,
						cod_field,
						cod_farm,
						libre,
						reseteado,
						multiple_semillas,
						ini_acres,
						use_acres,
						acres_disponibles,
						porcentaje_acre_usado,
						user_insert
					)
				VALUES
					(
						:cod_bloque,
						:cod_field,
						:cod_farm,
						:libre,
						:reseteado,
						:multiple_semillas,
						:ini_acres,
						:use_acres,
						:acres_disponibles,
						:porcentaje_acre_usado,
						:user_insert
					);";
		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_bloque", $cod_bloque);
		$stmt->bindParam(":cod_field", $cod_field);
		$stmt->bindParam(":cod_farm", $cod_farm);
		$stmt->bindParam(":libre", $libre);
		$stmt->bindParam(":reseteado", $reseteado);
		$stmt->bindParam(":multiple_semillas", $multiple_semillas);
		$stmt->bindParam(":ini_acres", $ini_acres);
		$stmt->bindParam(":use_acres", $use_acres);
		$stmt->bindParam(":acres_disponibles", $acres_disponibles);
		$stmt->bindParam(":porcentaje_acre_usado", $porcentaje_acre_usado);
		$stmt->bindParam(":user_insert", $user_insert);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Successful registration";
			$resultado = "0|Successful registration|" . $this->db_conexion->lastInsertId();
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			$resultado = "1|Failed to register|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para crear un nuevo registro de campo
	*/
	function farm_comprobar_bloque_implementado(
		$cod_bloque
	) {
		$SQL = "SELECT cod_bloque,cod_bloque_implementado FROM far_crop_bloques_implementados where cod_bloque = :cod_bloque;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_bloque", $cod_bloque);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			$resultado = "1|Failed to register|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*
	*  
	*/
	function farm_comprobar_semilla_asocida_a_bloque_implementado(
		$cod_bloque,
		$cod_inventario
	) {
		$SQL = "SELECT 
					cod_unificacion
				FROM
					far_semillas_en_bloques
				WHERE
					cod_bloque = :cod_bloque AND cod_semilla = :cod_inventario;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_bloque", $cod_bloque);
		$stmt->bindParam(":cod_inventario", $cod_inventario);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			$resultado = "1|Failed to register|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*
	* Función para crear un nuevo registro de campo
	*/
	function farm_registrar_unificacion_semilla_bloque(
		$cod_bloque,
		$cod_inventario
	) {
		$SQL = "INSERT INTO
					far_semillas_en_bloques (cod_semilla, cod_bloque)
				VALUES
					(:cod_inventario, :cod_bloque);";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_bloque", $cod_bloque);
		$stmt->bindParam(":cod_inventario", $cod_inventario);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = $this->db_conexion->lastInsertId();
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			$resultado = "1|Failed to register|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}


	/*
	* Función para crear un nuevo registro de campo
	*/
	function farm_crear_semilla_asociada_bloque_implementado(
		$cod_bloque_implementado,
		$cod_inventario,
		$cod_plantacion,
		$cod_trasplante,
		$acres_usados,
		$porcentaje_acre_usado,
		$cod_unificacion,
		$cod_temporada,
		$user_insert
	) {
		$SQL = "INSERT INTO
					far_crop_semillas_bloques (
						cod_bloque_implementado,
						cod_semilla,
						cod_plantacion,
						cod_trasplante,
						cod_unificacion,
						acres_usados,
						porcentaje_acre_usado,
						cod_temporada,
						user_insert
					)
				VALUES
					(
						:cod_bloque_implementado,
						:cod_inventario,
						:cod_plantacion,
						:cod_trasplante,
						:cod_unificacion,
						:acres_usados,
						:porcentaje_acre_usado,
						:cod_temporada,
						:user_insert
					);";
		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_bloque_implementado", $cod_bloque_implementado);
		$stmt->bindParam(":cod_inventario", $cod_inventario);
		$stmt->bindParam(":cod_plantacion", $cod_plantacion);
		$stmt->bindParam(":cod_trasplante", $cod_trasplante);
		$stmt->bindParam(":cod_unificacion", $cod_unificacion);
		$stmt->bindParam(":acres_usados", $acres_usados);
		$stmt->bindParam(":porcentaje_acre_usado", $porcentaje_acre_usado);
		$stmt->bindParam(":cod_temporada", $cod_temporada);
		$stmt->bindParam(":user_insert", $user_insert);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Successful registration";
			$resultado = $this->db_conexion->lastInsertId();
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			$resultado = "1|Failed to register|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}


	/*
	*  
	*/
	function farm_listado_de_semillas_asociadas_a_bloques($cod_bloque_implementado)
	{
		// $this->deshabilitarGrupoFull();
		$SQL = "SELECT
					far_crop_semillas_bloques.cod_semilla_bloque,
					far_crop_semillas_bloques.cod_bloque_implementado,
					far_crop_semillas_bloques.cod_semilla AS cod_inventario,
					far_crop_semillas_bloques.cod_unificacion,
					far_crop_semillas_bloques.cod_plantacion,
					far_crop_semillas_bloques.cod_trasplante,
					far_crop_semillas_bloques.acres_usados,
					far_crop_semillas_bloques.porcentaje_acre_usado as porcentaje_acre_usado_en_semilla,
					far_crop_semillas_bloques.fecha_plantacion,
					far_crop_semillas_bloques.user_insert as user_insert_semilla_asociada,
					far_crop_semillas_bloques.date_insert as date_insert_semilla_asociada,
					far_crop_semillas_bloques.cod_temporada,
					far_crop_bloques_implementados.cod_bloque,
					far_crop_bloques_implementados.cod_field,
					far_crop_bloques_implementados.cod_farm,
					far_crop_bloques_implementados.ini_acres,
					far_crop_bloques_implementados.use_acres,
					far_crop_bloques_implementados.acres_disponibles,
					far_crop_bloques_implementados.porcentaje_acre_usado,
					far_crop_bloques_implementados.user_insert as block_creator_user,
					far_crop_bloques_implementados.date_insert as block_creation_date,
					bw_inventario_semilla.nombre_semilla,
					bw_inventario_semilla.abreviatura_semilla,
					bw_inventario_semilla.codigo_semilla,
					bw_inventario_plantaciones.numero_orden as numero_orden_plantacion,
					bw_inventario_plantaciones.edad as edad_semilla_en_plantacion,
					bw_inventario_plantaciones.fecha_inicial as fecha_inicial_plantacion,
					bw_inventario_plantaciones.fecha_final as fecha_final_plantacion,
					bw_inventario_plantaciones.fecha_de_entrega as fecha_de_entrega_plantacion,
					bw_inventario_trasplantes.numero_ticket,
					bw_inventario_trasplantes.fecha_entrega AS fecha_entrega_trasplante,
					bw_inventario_trasplantes.fecha_recibo AS fecha_recibo_trasplante,
					COALESCE(far_bloques.bloque, 'Block removed')  as nombre_bloque_usado,
					COALESCE(usu_usuarios.usuario, 'User Deleted') as usuario_registro_semilla
				FROM
					far_crop_semillas_bloques
					INNER JOIN far_crop_bloques_implementados ON (
						far_crop_bloques_implementados.cod_bloque_implementado = far_crop_semillas_bloques.cod_bloque_implementado
					)
					INNER JOIN bw_inventario_semilla ON (
						bw_inventario_semilla.cod_inventario = far_crop_semillas_bloques.cod_semilla
					)
					INNER JOIN bw_inventario_plantaciones ON (
						bw_inventario_plantaciones.cod_plantacion = far_crop_semillas_bloques.cod_plantacion
					)
					INNER JOIN bw_inventario_trasplantes ON (
						bw_inventario_trasplantes.cod_trasplante = far_crop_semillas_bloques.cod_trasplante
					)
					left JOIN far_bloques ON (
						far_bloques.cod_bloque = far_crop_bloques_implementados.cod_bloque
					)
					left JOIN usu_usuarios ON (
						usu_usuarios.cod_usuario = far_crop_semillas_bloques.user_insert
					)
				WHERE
					far_crop_semillas_bloques.cod_bloque_implementado = :cod_bloque_implementado AND far_crop_semillas_bloques.completada = 0;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_bloque_implementado",  $cod_bloque_implementado);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		// $this->habilitarGrupoFull();
		return $resultado;
	}


	/*
	*
	*/
	function farm_crear_registro_permanente_semilla_implementada(
		$cod_semilla_bloque,
		$cod_bloque_implementado,
		$cod_inventario,
		$cod_unificacion,
		$cod_plantacion,
		$cod_trasplante,
		$cod_bloque,
		$cod_field,
		$cod_farm,
		$acres_usados,
		$porcentaje_acre_usado_en_semilla,
		$fecha_plantacion,
		$user_insert_semilla_asociada,
		$usuario_registro_semilla,
		$date_insert_semilla_asociada,
		$ini_acres,
		$use_acres,
		$acres_disponibles,
		$porcentaje_acre_usado,
		$block_creator_user,
		$block_creation_date,
		$nombre_semilla,
		$abreviatura_semilla,
		$codigo_semilla,
		$numero_orden_plantacion,
		$edad_semilla_en_plantacion,
		$fecha_inicial_plantacion,
		$fecha_final_plantacion,
		$fecha_de_entrega_plantacion,
		$numero_ticket,
		$fecha_entrega_trasplante,
		$fecha_recibo_trasplante,
		$nombre_bloque_usado,
		$cod_temporada,
		$user_insert
	) {
		$SQL = "INSERT INTO
					far_crop_registro_semillas_implementadas (
						cod_registro_semilla_implementada,
						cod_semilla_bloque,
						cod_bloque_implementado,
						cod_inventario,
						cod_unificacion,
						cod_plantacion,
						cod_trasplante,
						cod_bloque,
						cod_field,
						cod_farm,
						acres_usados,
						porcentaje_acre_usado_en_semilla,
						fecha_plantacion,
						user_insert_semilla_asociada,
						usuario_registro_semilla,
						date_insert_semilla_asociada,
						ini_acres,
						use_acres,
						acres_disponibles,
						porcentaje_acre_usado,
						block_creator_user,
						block_creation_date,
						nombre_semilla,
						abreviatura_semilla,
						codigo_semilla,
						numero_orden_plantacion,
						edad_semilla_en_plantacion,
						fecha_inicial_plantacion,
						fecha_final_plantacion,
						fecha_de_entrega_plantacion,
						numero_ticket,
						fecha_entrega_trasplante,
						fecha_recibo_trasplante,
						nombre_bloque_usado,
						cod_temporada,
						user_insert
					)
				VALUES
					(
						:cod_registro_semilla_implementada,
						:cod_semilla_bloque,
						:cod_bloque_implementado,
						:cod_inventario,
						:cod_unificacion,
						:cod_plantacion,
						:cod_trasplante,
						:cod_bloque,
						:cod_field,
						:cod_farm,
						:acres_usados,
						:porcentaje_acre_usado_en_semilla,
						:fecha_plantacion,
						:user_insert_semilla_asociada,
						:usuario_registro_semilla,
						:date_insert_semilla_asociada,
						:ini_acres,
						:use_acres,
						:acres_disponibles,
						:porcentaje_acre_usado,
						:block_creator_user,
						:block_creation_date,
						:nombre_semilla,
						:abreviatura_semilla,
						:codigo_semilla,
						:numero_orden_plantacion,
						:edad_semilla_en_plantacion,
						:fecha_inicial_plantacion,
						:fecha_final_plantacion,
						:fecha_de_entrega_plantacion,
						:numero_ticket,
						:fecha_entrega_trasplante,
						:fecha_recibo_trasplante,
						:nombre_bloque_usado,
						:cod_temporada,
						:user_insert
					);";
		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_registro_semilla_implementada", $cod_registro_semilla_implementada);
		$stmt->bindParam(":cod_semilla_bloque", $cod_semilla_bloque);
		$stmt->bindParam(":cod_bloque_implementado", $cod_bloque_implementado);
		$stmt->bindParam(":cod_inventario", $cod_inventario);
		$stmt->bindParam(":cod_unificacion", $cod_unificacion);
		$stmt->bindParam(":cod_plantacion", $cod_plantacion);
		$stmt->bindParam(":cod_trasplante", $cod_trasplante);
		$stmt->bindParam(":cod_bloque", $cod_bloque);
		$stmt->bindParam(":cod_field", $cod_field);
		$stmt->bindParam(":cod_farm", $cod_farm);
		$stmt->bindParam(":acres_usados", $acres_usados);
		$stmt->bindParam(":porcentaje_acre_usado_en_semilla", $porcentaje_acre_usado_en_semilla);
		$stmt->bindParam(":fecha_plantacion", $fecha_plantacion);
		$stmt->bindParam(":user_insert_semilla_asociada", $user_insert_semilla_asociada);
		$stmt->bindParam(":usuario_registro_semilla", $usuario_registro_semilla);
		$stmt->bindParam(":date_insert_semilla_asociada", $date_insert_semilla_asociada);
		$stmt->bindParam(":ini_acres", $ini_acres);
		$stmt->bindParam(":use_acres", $use_acres);
		$stmt->bindParam(":acres_disponibles", $acres_disponibles);
		$stmt->bindParam(":porcentaje_acre_usado", $porcentaje_acre_usado);
		$stmt->bindParam(":block_creator_user", $block_creator_user);
		$stmt->bindParam(":block_creation_date", $block_creation_date);
		$stmt->bindParam(":nombre_semilla", $nombre_semilla);
		$stmt->bindParam(":abreviatura_semilla", $abreviatura_semilla);
		$stmt->bindParam(":codigo_semilla", $codigo_semilla);
		$stmt->bindParam(":numero_orden_plantacion", $numero_orden_plantacion);
		$stmt->bindParam(":edad_semilla_en_plantacion", $edad_semilla_en_plantacion);
		$stmt->bindParam(":fecha_inicial_plantacion", $fecha_inicial_plantacion);
		$stmt->bindParam(":fecha_final_plantacion", $fecha_final_plantacion);
		$stmt->bindParam(":fecha_de_entrega_plantacion", $fecha_de_entrega_plantacion);
		$stmt->bindParam(":numero_ticket", $numero_ticket);
		$stmt->bindParam(":fecha_entrega_trasplante", $fecha_entrega_trasplante);
		$stmt->bindParam(":fecha_recibo_trasplante", $fecha_recibo_trasplante);
		$stmt->bindParam(":nombre_bloque_usado", $nombre_bloque_usado);
		$stmt->bindParam(":cod_temporada", $cod_temporada);
		$stmt->bindParam(":user_insert", $user_insert);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Successful registration";
			$resultado = $this->db_conexion->lastInsertId();
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			$resultado = "1|Failed to register|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}


	/*
	*  
	*/
	function farm_actualizar_unificacion_semilla_bloque($cod_unificacion)
	{
		$SQL = "UPDATE
					far_semillas_en_bloques
				SET
					activo = '0'
				WHERE
					cod_unificacion = :cod_unificacion;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_unificacion",  $cod_unificacion);
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
	*  
	*/
	function farm_actualizar_estado_de_implementado_unificacion_semilla_bloque($cod_unificacion)
	{
		$SQL = "UPDATE
					far_semillas_en_bloques
				SET
					completado = '1'
				WHERE
					cod_unificacion = :cod_unificacion;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_unificacion",  $cod_unificacion);
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
	* Función para actualizar una temporada especifica
	*/
	function farm_activar_unificacion_semilla_bloque($cod_bloque, $cod_inventario)
	{
		$SQL = "UPDATE
					far_semillas_en_bloques
				SET
					activo = 1,
					completado = 0
				WHERE
					cod_semilla = :cod_inventario AND cod_bloque = :cod_bloque;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_bloque",  $cod_bloque);
		$stmt->bindParam(":cod_inventario",  $cod_inventario);
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
	* Actualiza la cantidad de acres disponibles en un bloque implementado
	*/
	function farm_incrementar_acres_disponibles_en_bloque_implementado(
		$cod_bloque_implementado,
		$cantidad_acres,
		$porcentaje_acres
	) {
		$SQL = "UPDATE
					far_crop_bloques_implementados
				SET
					use_acres = use_acres - :cantidad_acres,
					acres_disponibles = acres_disponibles + :cantidad_acres,
					porcentaje_acre_usado = porcentaje_acre_usado - :porcentaje_acres
				WHERE
					cod_bloque_implementado = :cod_bloque_implementado;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_bloque_implementado",  $cod_bloque_implementado);
		$stmt->bindParam(":cantidad_acres",  $cantidad_acres);
		$stmt->bindParam(":porcentaje_acres",  $porcentaje_acres);
		try {
			$stmt->execute();
			$resultado = "0|Update completed successfully";
		} catch (PDOException $e) {
			$resultado = "1|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*
	* Actualiza la cantidad de acres iniciales que tiene un bloque implementado
	*/
	function farm_actualizar_cantidad_acres_en_bloque_implementado(
		$cod_bloque_implementado,
		$cantidad_acres
	) {
		$SQL = "UPDATE
					far_crop_bloques_implementados
				SET
					ini_acres = :cantidad_acres,
					acres_disponibles = :cantidad_acres

				WHERE
					cod_bloque_implementado = :cod_bloque_implementado;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_bloque_implementado",  $cod_bloque_implementado);
		$stmt->bindParam(":cantidad_acres",  $cantidad_acres);
		try {
			$stmt->execute();
			$resultado = "0|Update completed successfully";
		} catch (PDOException $e) {
			$resultado = "1|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}


	/*
	*  
	*/
	function farm_marcar_trasplante_como_implementado($cod_trasplante)
	{
		$SQL = "UPDATE
					bw_inventario_trasplantes
				SET
					implementado = '1'
				WHERE
					cod_trasplante = :cod_trasplante;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_trasplante",  $cod_trasplante);
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
	*  
	*/
	function farm_datos_semilla_unica_asociada_bloque_implementado($cod_bloque_implementado)
	{
		$this->deshabilitarGrupoFull();

		$SQL = "SELECT
				far_crop_semillas_bloques.cod_semilla,
				far_crop_semillas_bloques.cod_plantacion,
				far_crop_semillas_bloques.cod_trasplante,
				bw_inventario_plantaciones.edad,
				CONCAT(
					bw_inventario_semilla.nombre_semilla,
					'|',
					DATE_FORMAT(
						bw_inventario_plantaciones.fecha_final,
						'%Y-%m-%d'
					),
					'|',
					bw_inventario_trasplantes.numero_orden,
					'-',
					bw_inventario_trasplantes.numero_ticket,'| age: ',bw_inventario_plantaciones.edad
				) AS datos_semilla
			FROM
				far_crop_bloques_implementados
				INNER JOIN far_crop_semillas_bloques ON (
					far_crop_semillas_bloques.cod_bloque_implementado = far_crop_bloques_implementados.cod_bloque_implementado
				)
				INNER JOIN bw_inventario_trasplantes ON (
					bw_inventario_trasplantes.cod_trasplante = far_crop_semillas_bloques.cod_trasplante
				)
				INNER JOIN bw_inventario_plantaciones ON (
					bw_inventario_plantaciones.cod_plantacion = far_crop_semillas_bloques.cod_plantacion
				)
				INNER JOIN bw_inventario_semilla ON (
					bw_inventario_semilla.cod_inventario = far_crop_semillas_bloques.cod_semilla
				)
			WHERE
				far_crop_bloques_implementados.cod_bloque_implementado = :cod_bloque_implementado AND far_crop_semillas_bloques.completada = 0;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_bloque_implementado", $cod_bloque_implementado);

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
	* 
	*/
	function farm_listado_bloques_usados_por_campos($codigos_campos, $codigosBloquesUsados)
	{
		$this->deshabilitarGrupoFull();
		$SQL = "SELECT
					far_bloques.cod_bloque,
					far_bloques.cod_farm,
					far_bloques.cod_field,
					CONCAT(
						far_fields.field,
						' - ',
						far_bloques.bloque,
						' - ',COALESCE(far_crop_bloques_implementados.use_acres,far_bloques.acres),' - ',
						ROUND(
							COALESCE(
								far_crop_bloques_implementados.porcentaje_acre_usado,
								0
							),
							0
						),'%'
					) AS bloque,
					far_bloques.acres,
					far_farms.cod_estado,
					far_farms.farm AS nombre_granja,
					bw_inventario_estados_plantaciones.nombre AS nombre_estado
				FROM
					far_bloques
					INNER JOIN far_fields ON (far_fields.cod_field = far_bloques.cod_field)
					INNER JOIN far_farms ON (far_farms.cod_farms = far_bloques.cod_farm)
					INNER JOIN bw_inventario_estados_plantaciones ON (
						bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado
					)
					LEFT JOIN far_crop_bloques ON (
						far_crop_bloques.cod_bloque = far_bloques.cod_bloque
					)
					LEFT JOIN far_crop_bloques_implementados ON (
						far_crop_bloques_implementados.cod_bloque = far_bloques.cod_bloque
					)
					LEFT JOIN far_crop_registro_semillas_implementadas ON (
						far_crop_registro_semillas_implementadas.cod_bloque = far_bloques.cod_bloque
					)
				WHERE
					far_bloques.cod_bloque IN (" . $codigosBloquesUsados . ")
					and far_bloques.cod_field in (" . $codigos_campos . ")
					GROUP BY far_bloques.cod_bloque
					ORDER BY far_bloques.cod_bloque ASC;";
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
	 *
	 */
	function farm_listado_semillas_plantadas_registro_permanente(
		$fechaInicial,
		$fechaFinal,
		$codigosEstados,
		$codigosGranjas,
		$codigosCampos,
		$codigoBloquesUsados
	) {
		$condiciones = ' ';
		if ($codigosEstados != null && $codigosEstados != '' && $codigosEstados != 0) {

			$condiciones .= " AND bw_inventario_estados_plantaciones.cod_estado in (" . $codigosEstados . ")  ";
		}
		if ($codigosGranjas != null && $codigosGranjas != '' && $codigosGranjas != 0) {

			$condiciones .= " AND far_crop_registro_semillas_implementadas.cod_farm in (" . $codigosGranjas . ")  ";
		}

		if ($codigosCampos != null && $codigosCampos != '' && $codigosCampos != 0) {

			$condiciones .= " AND far_crop_registro_semillas_implementadas.cod_field in (" . $codigosCampos . ")  ";
		}

		if ($codigoBloquesUsados != null && $codigoBloquesUsados != '' && $codigoBloquesUsados != 0) {

			$condiciones .= " AND far_crop_registro_semillas_implementadas.cod_bloque in (" . $codigoBloquesUsados . ")  ";
		}
		$this->deshabilitarGrupoFull();
		$SQL = "SELECT
					far_crop_registro_semillas_implementadas.edad_semilla_en_plantacion,
					far_crop_registro_semillas_implementadas.cod_bloque,
					far_crop_registro_semillas_implementadas.cod_unificacion,
					far_crop_registro_semillas_implementadas.cod_inventario,
					far_crop_registro_semillas_implementadas.cod_bloque_implementado,
					far_crop_registro_semillas_implementadas.nombre_bloque_usado,
					far_crop_registro_semillas_implementadas.cod_registro_semilla_implementada,
					far_crop_registro_semillas_implementadas.cod_semilla_bloque,
					GROUP_CONCAT(
						far_crop_registro_semillas_implementadas.cod_registro_semilla_implementada
					) AS codigos_registros,
					far_crop_registro_semillas_implementadas.ini_acres,
					far_crop_registro_semillas_implementadas.use_acres,
					SUM(
						far_crop_registro_semillas_implementadas.acres_usados
					) AS suma_acres_usados,
					SUM(
						far_crop_registro_semillas_implementadas.porcentaje_acre_usado_en_semilla
					) AS suma_porcentaje_usado,
					far_crop_registro_semillas_implementadas.porcentaje_acre_usado,
					far_crop_registro_semillas_implementadas.nombre_semilla,
					DATE_FORMAT(far_crop_registro_semillas_implementadas.fecha_inicial_plantacion,
									'%Y-%m-%d')as fecha_inicial_plantacion,
					far_crop_registro_semillas_implementadas.fecha_plantacion,
					CONCAT(
						bw_inventario_estados_plantaciones.abreviatura,
						' - ',
						far_farms.farm
					) AS granja_asociada,
					CONCAT(far_crop_registro_semillas_implementadas.nombre_semilla) AS datos_semilla,
					far_fields.field,
					bw_inventario_estados_plantaciones.nombre as nombre_estado,
					far_farms.cod_farms as cod_farm
				FROM
					far_crop_registro_semillas_implementadas
					INNER JOIN far_farms ON (
						far_farms.cod_farms = far_crop_registro_semillas_implementadas.cod_farm
					)
					INNER JOIN bw_inventario_estados_plantaciones ON (
						bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado
					)
					INNER JOIN far_fields ON (
						far_fields.cod_field = far_crop_registro_semillas_implementadas.cod_field
					)
				WHERE
					far_crop_registro_semillas_implementadas.fecha_plantacion BETWEEN :fechaInicial
					AND :fechaFinal
					" . $condiciones . "
				GROUP BY
					far_crop_registro_semillas_implementadas.nombre_semilla,
					far_crop_registro_semillas_implementadas.edad_semilla_en_plantacion
				ORDER BY far_crop_registro_semillas_implementadas.nombre_semilla, far_crop_registro_semillas_implementadas.edad_semilla_en_plantacion ASC;";

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
		$this->habilitarGrupoFull();

		return $resultado;
	}

	/*
	 *
	 */
	function farm_listado_semillas_plantadas_no_completadas(
		$fechaInicial,
		$fechaFinal,
		$codigosGranjas,
		$codigosCampos,
		$codigosBloquesImplementados
	) {


		$condiciones = ' ';
		// if ($codigosGranjas != null && $codigosGranjas != '' && $codigosGranjas != 0) {

		// 	$condiciones .= " AND far_crop_registro_semillas_implementadas.cod_farm in (" . $codigosGranjas . ")  ";
		// }

		// if ($codigosCampos != null && $codigosCampos != '' && $codigosCampos != 0) {

		// 	$condiciones .= " AND far_crop_registro_semillas_implementadas.cod_field in (" . $codigosCampos . ")  ";
		// }

		if ($codigosBloquesImplementados != null && $codigosBloquesImplementados != '' && $codigosBloquesImplementados != 0) {

			$condiciones .= " AND far_crop_bloques_implementados.cod_bloque in (" . $codigosBloquesImplementados . ")  ";
		}
		$this->deshabilitarGrupoFull();
		$SQL = "SELECT
					CONCAT(
						bw_inventario_semilla.nombre_semilla
					) AS datos_semilla,
					far_crop_semillas_bloques.cod_trasplante,
					SUM(
						far_crop_semillas_bloques.acres_usados
					) AS suma_acres_usados,
					far_crop_semillas_bloques.cod_semilla,
					far_crop_semillas_bloques.cod_semilla as cod_inventario,
					far_crop_semillas_bloques.cod_unificacion,
					far_crop_semillas_bloques.cod_bloque_implementado,
					GROUP_CONCAT(far_crop_semillas_bloques.cod_bloque_implementado) as codigos_bloque_implementado,
					far_crop_semillas_bloques.cod_plantacion,
					far_crop_semillas_bloques.acres_usados,
					far_crop_semillas_bloques.porcentaje_acre_usado,
					bw_inventario_plantaciones.edad as edad_semilla_en_plantacion,
					bw_inventario_trasplantes.numero_orden,
					bw_inventario_trasplantes.numero_ticket,
					bw_inventario_plantaciones.fecha_inicial,
					bw_inventario_semilla.nombre_semilla,
					bw_inventario_semilla.abreviatura_semilla,
					bw_inventario_semilla.codigo_semilla,
					COALESCE(far_bloques.bloque, 'Block removed') AS nombre_bloque_usado,
					CONCAT(
						bw_inventario_estados_plantaciones.abreviatura,
						' - ',
						far_farms.farm
					) AS granja_asociada,
					bw_inventario_estados_plantaciones.nombre as nombre_estado,
					far_fields.field,
					far_farms.cod_farms as cod_farm
				FROM
					far_crop_semillas_bloques
					INNER JOIN far_crop_bloques_implementados ON (
						far_crop_bloques_implementados.cod_bloque_implementado = far_crop_semillas_bloques.cod_bloque_implementado
					)
					INNER JOIN bw_inventario_plantaciones ON (
						bw_inventario_plantaciones.cod_plantacion = far_crop_semillas_bloques.cod_plantacion
					)
					INNER JOIN bw_inventario_trasplantes ON (
						bw_inventario_trasplantes.cod_trasplante = far_crop_semillas_bloques.cod_trasplante
					)
					INNER JOIN bw_inventario_semilla ON (
						bw_inventario_semilla.cod_inventario = far_crop_semillas_bloques.cod_semilla
					)
					LEFT JOIN far_bloques ON (
						far_bloques.cod_bloque = far_crop_bloques_implementados.cod_bloque
					)
					INNER JOIN far_farms ON (
						far_farms.cod_farms = far_crop_bloques_implementados.cod_farm
					)
					INNER JOIN bw_inventario_estados_plantaciones ON (
						bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado
					)
						INNER JOIN
					far_fields ON (far_fields.cod_field = far_crop_bloques_implementados.cod_field)
				WHERE
					far_crop_semillas_bloques.fecha_plantacion BETWEEN :fechaInicial
					AND :fechaFinal
					" . $condiciones . " AND far_crop_semillas_bloques.completada = 0
				GROUP BY
					far_farms.farm,
					bw_inventario_semilla.nombre_semilla,
					bw_inventario_plantaciones.edad
				ORDER BY far_farms.farm, bw_inventario_semilla.nombre_semilla, bw_inventario_plantaciones.edad ASC;";

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
		$this->habilitarGrupoFull();

		return $resultado;
	}

	/*
	* Función para obtener el listado de todas las semillas asociadasas a un bloque en la plantación
	*/
	function farm_listado_semillas_registradas_completadas($codigos_registros)
	{
		//
		$SQL = "SELECT
					far_crop_registro_semillas_implementadas.ini_acres,
					far_crop_registro_semillas_implementadas.acres_usados,
					far_crop_registro_semillas_implementadas.cod_bloque,
					far_crop_registro_semillas_implementadas.cod_unificacion,
					far_crop_registro_semillas_implementadas.cod_inventario,
					far_crop_registro_semillas_implementadas.cod_bloque_implementado,
					far_crop_registro_semillas_implementadas.cod_inventario AS cod_semilla_bloque,
					far_crop_registro_semillas_implementadas.cod_plantacion,
					far_crop_registro_semillas_implementadas.cod_semilla_bloque,
					far_crop_registro_semillas_implementadas.cod_trasplante,
					far_crop_registro_semillas_implementadas.cod_farm,
					far_crop_registro_semillas_implementadas.edad_semilla_en_plantacion AS edad,
					far_crop_registro_semillas_implementadas.fecha_entrega_trasplante AS fecha_entrega,
					far_crop_registro_semillas_implementadas.fecha_inicial_plantacion AS fecha_inicial,
					DATE_FORMAT(far_crop_registro_semillas_implementadas.fecha_plantacion,
								'%Y-%m-%d') as fecha_plantacion,
					DATE_FORMAT(far_crop_registro_semillas_implementadas.fecha_final_plantacion,
								'%Y-%m-%d') as fecha_final_plantacion,
					far_crop_registro_semillas_implementadas.nombre_semilla,
					far_crop_registro_semillas_implementadas.numero_orden_plantacion AS numero_orden,
					far_crop_registro_semillas_implementadas.numero_ticket,
					ROUND(far_crop_registro_semillas_implementadas.porcentaje_acre_usado,0) AS porcentaje_acre_usado_total,
					ROUND(far_crop_registro_semillas_implementadas.porcentaje_acre_usado_en_semilla,0) AS porcentaje_acre_usado,
					far_crop_registro_semillas_implementadas.nombre_bloque_usado,
					far_fields.field
				FROM
					far_crop_registro_semillas_implementadas
					INNER JOIN far_fields ON (
						far_fields.cod_field = far_crop_registro_semillas_implementadas.cod_field
					)
				WHERE
					far_crop_registro_semillas_implementadas.cod_registro_semilla_implementada IN (" . $codigos_registros . ");";
		// cod_registro_semilla_implementada IN (27 , 28, 29, 31, 32);";
		$stmt = $this->db_conexion->prepare($SQL);
		// $stmt->bindParam(":codigos_registros",  $codigos_registros);

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
	*  
	*/
	function farm_listado_semillas_implementadas_no_registro_permanente_bk($cod_unificacion, $cod_semilla, $cod_bloque_implementado)
	{

		$SQL = "SELECT
					far_crop_semillas_bloques.acres_usados,
					far_crop_semillas_bloques.porcentaje_acre_usado
				FROM
					far_crop_semillas_bloques
					INNER JOIN far_crop_bloques_implementados ON (
						far_crop_bloques_implementados.cod_bloque_implementado = far_crop_semillas_bloques.cod_bloque_implementado
					)
				WHERE
				far_crop_semillas_bloques.cod_unificacion = :cod_unificacion
					AND far_crop_semillas_bloques.completada = 0
					AND far_crop_semillas_bloques.cod_semilla = :cod_semilla
					AND far_crop_semillas_bloques.cod_bloque_implementado = :cod_bloque_implementado;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_unificacion", $cod_unificacion);
		$stmt->bindParam(":cod_semilla", $cod_semilla);
		$stmt->bindParam(":cod_bloque_implementado", $cod_bloque_implementado);

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
	*  
	*/
	function farm_listado_semillas_implementadas_no_registro_permanente($cod_unificacion, $cod_semilla, $cod_bloque_implementado, $edad, $cod_farm)
	{

		$SQL = "SELECT
					far_crop_semillas_bloques.acres_usados, far_crop_semillas_bloques.porcentaje_acre_usado
				FROM
					far_crop_semillas_bloques
					INNER JOIN far_crop_bloques_implementados ON (
						far_crop_bloques_implementados.cod_bloque_implementado = far_crop_semillas_bloques.cod_bloque_implementado
					)
					INNER JOIN
						bw_inventario_plantaciones ON (bw_inventario_plantaciones.cod_plantacion = far_crop_semillas_bloques.cod_plantacion)
				WHERE
					far_crop_semillas_bloques.cod_semilla = :cod_semilla
					AND far_crop_semillas_bloques.completada = 0
					AND  bw_inventario_plantaciones.edad = :edad
					AND far_crop_bloques_implementados.cod_farm = :cod_farm;";
		// far_crop_semillas_bloques.cod_unificacion = :cod_unificacion 
		// AND far_crop_semillas_bloques.cod_bloque_implementado = :cod_bloque_implementado 
		$stmt = $this->db_conexion->prepare($SQL);
		// $stmt->bindParam(":cod_unificacion", $cod_unificacion);
		// $stmt->bindParam(":cod_bloque_implementado", $cod_bloque_implementado);
		$stmt->bindParam(":cod_semilla", $cod_semilla);
		$stmt->bindParam(":edad", $edad);
		$stmt->bindParam(":cod_farm", $cod_farm);

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
	*  
	*/
	function farm_suma_acres_usados_en_semillas_registradas($cod_unificacion, $cod_semilla, $cod_bloque_implementado, $edad, $cod_farm)
	{

		$SQL = "SELECT
					SUM(acres_usados) AS acres_usados,
					SUM(porcentaje_acre_usado_en_semilla) AS porcentaje_acre_usado
				FROM
					far_crop_registro_semillas_implementadas
				WHERE
					cod_bloque_implementado = :cod_bloque_implementado
						AND cod_inventario = :cod_semilla
						AND cod_unificacion = :cod_unificacion
						AND edad_semilla_en_plantacion = :edad
						AND cod_farm = :cod_farm
				GROUP BY cod_inventario;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_unificacion", $cod_unificacion);
		$stmt->bindParam(":cod_semilla", $cod_semilla);
		$stmt->bindParam(":cod_bloque_implementado", $cod_bloque_implementado);
		$stmt->bindParam(":edad", $edad);
		$stmt->bindParam(":cod_farm", $cod_farm);

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
	*  
	*/
	function farm_lista_semillas_completadas_asociadas_a_bloques_no_completadas($cod_unificacion, $cod_semilla, $cod_bloque_implementado, $edad, $cod_farm)
	{
		$this->deshabilitarGrupoFull();
		$SQL = "SELECT
					SUM(far_crop_registro_semillas_implementadas.acres_usados) AS acres_usados,
					ROUND(SUM(far_crop_registro_semillas_implementadas.porcentaje_acre_usado_en_semilla),0) AS porcentaje_acre_usado,
					far_crop_registro_semillas_implementadas.nombre_bloque_usado,
					far_crop_registro_semillas_implementadas.nombre_semilla,
					DATE_FORMAT(far_crop_registro_semillas_implementadas.fecha_plantacion,
									'%Y-%m-%d') as fecha_plantacion,
					DATE_FORMAT(far_crop_registro_semillas_implementadas.fecha_final_plantacion,
									'%Y-%m-%d') as fecha_final_plantacion,
					far_crop_registro_semillas_implementadas.numero_ticket,
					bw_inventario_plantaciones.cod_inventario,
					bw_inventario_plantaciones.numero_orden,
					bw_inventario_plantaciones.fecha_inicial,
					bw_inventario_plantaciones.edad,
					far_farms.farm,
					far_fields.field
				FROM
					far_crop_registro_semillas_implementadas
					INNER JOIN far_crop_bloques_implementados ON (
						far_crop_bloques_implementados.cod_bloque_implementado = far_crop_registro_semillas_implementadas.cod_bloque_implementado
					)
					INNER JOIN bw_inventario_plantaciones ON (
						bw_inventario_plantaciones.cod_plantacion = far_crop_registro_semillas_implementadas.cod_plantacion
					)
					INNER JOIN far_farms ON (
						far_farms.cod_farms = far_crop_registro_semillas_implementadas.cod_farm
					)
					INNER JOIN bw_inventario_estados_plantaciones ON (
						bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado
					)
					INNER JOIN far_fields ON (
						far_fields.cod_field = far_crop_registro_semillas_implementadas.cod_field
					)
				WHERE
				far_crop_registro_semillas_implementadas.cod_bloque_implementado = :cod_bloque_implementado
						AND far_crop_registro_semillas_implementadas.cod_inventario = :cod_semilla
						AND far_crop_registro_semillas_implementadas.cod_unificacion = :cod_unificacion
						AND bw_inventario_plantaciones.edad = :edad
						AND far_crop_registro_semillas_implementadas.cod_farm = :cod_farm
				GROUP BY cod_inventario;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_unificacion", $cod_unificacion);
		$stmt->bindParam(":cod_semilla", $cod_semilla);
		$stmt->bindParam(":cod_bloque_implementado", $cod_bloque_implementado);
		$stmt->bindParam(":edad", $edad);
		$stmt->bindParam(":cod_farm", $cod_farm);

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
	*  
	*/
	function farm_suma_acres_usados_en_semillas_registradas_por_grupo_implementacion($cod_unificacion, $cod_semilla, $codigos_bloque_implementado)
	{

		$SQL = "SELECT
					SUM(acres_usados) AS acres_usados,
					SUM(porcentaje_acre_usado_en_semilla) AS porcentaje_acre_usado
				FROM
					far_crop_registro_semillas_implementadas
				WHERE
					cod_bloque_implementado in (" . $codigos_bloque_implementado . ")
						AND cod_inventario = :cod_semilla
						AND cod_unificacion = :cod_unificacion
				GROUP BY cod_inventario;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_unificacion", $cod_unificacion);
		$stmt->bindParam(":cod_semilla", $cod_semilla);
		$stmt->bindParam(":cod_bloque_implementado", $cod_bloque_implementado);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();

		return $resultado;
	}

	function farm_listado_datos_semillas_implementadas_no_registro_permanente($cod_unificacion, $cod_semilla, $cod_bloque_implementado)
	{

		$SQL = "SELECT
					acres_usados, porcentaje_acre_usado
				FROM
					far_crop_semillas_bloques
				WHERE
					cod_unificacion = :cod_unificacion AND cod_semilla = :cod_semilla
						AND cod_bloque_implementado = :cod_bloque_implementado AND completada = 0;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_unificacion", $cod_unificacion);
		$stmt->bindParam(":cod_semilla", $cod_semilla);
		$stmt->bindParam(":cod_bloque_implementado", $cod_bloque_implementado);

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
	*
	*/
	function farm_listado_datos_semillas_en_plantacion($cod_semilla, $edad, $cod_farm, $codigos_bloque)
	{
		$this->deshabilitarGrupoFull();
		$SQL = "SELECT
					far_crop_semillas_bloques.cod_semilla_bloque,
					far_crop_semillas_bloques.cod_semilla,
					far_crop_semillas_bloques.cod_plantacion,
					far_crop_semillas_bloques.cod_trasplante,
					far_crop_semillas_bloques.acres_usados,
					far_crop_semillas_bloques.cod_unificacion,
					far_crop_semillas_bloques.cod_semilla,
					far_crop_semillas_bloques.cod_bloque_implementado,
					ROUND(far_crop_semillas_bloques.porcentaje_acre_usado,0) as porcentaje_acre_usado,
					far_crop_semillas_bloques.fecha_plantacion as fecha_plantacion_original,
					DATE_FORMAT(far_crop_semillas_bloques.fecha_plantacion,
										'%Y-%m-%d') AS fecha_plantacion,
					DATE_FORMAT(bw_inventario_plantaciones.fecha_final,
										'%Y-%m-%d') AS fecha_final_plantacion,
					bw_inventario_semilla.nombre_semilla,
					bw_inventario_plantaciones.cod_inventario,
					bw_inventario_plantaciones.numero_orden,
					bw_inventario_plantaciones.fecha_inicial,
					bw_inventario_plantaciones.edad,
					bw_inventario_trasplantes.numero_orden,
					bw_inventario_trasplantes.numero_ticket,
					bw_inventario_trasplantes.fecha_entrega,
					far_fields.field,
					far_bloques.bloque as nombre_bloque_usado,
					far_crop_bloques_implementados.cod_farm
					FROM
						far_crop_semillas_bloques
						INNER JOIN bw_inventario_semilla ON (
							bw_inventario_semilla.cod_inventario = far_crop_semillas_bloques.cod_semilla
						)
						INNER JOIN bw_inventario_plantaciones ON (
							bw_inventario_plantaciones.cod_plantacion = far_crop_semillas_bloques.cod_plantacion
						)
						INNER JOIN bw_inventario_trasplantes ON (
							bw_inventario_trasplantes.cod_trasplante = far_crop_semillas_bloques.cod_trasplante
						)
						INNER JOIN far_crop_bloques_implementados ON (
							far_crop_bloques_implementados.cod_bloque_implementado = far_crop_semillas_bloques.cod_bloque_implementado
						)
						INNER JOIN far_fields ON (
							far_fields.cod_field = far_crop_bloques_implementados.cod_field
						)
						INNER JOIN far_bloques ON (
							far_bloques.cod_bloque = far_crop_bloques_implementados.cod_bloque
						)
					WHERE
					far_crop_semillas_bloques.cod_semilla = :cod_semilla
					AND far_crop_semillas_bloques.completada = 0
					AND bw_inventario_plantaciones.edad = :edad
					AND far_crop_bloques_implementados.cod_farm = :cod_farm
					AND far_crop_bloques_implementados.cod_bloque IN (" . $codigos_bloque . ");";
		// AND far_crop_semillas_bloques.cod_bloque_implementado = :cod_bloque_implementado
		// far_crop_semillas_bloques.cod_unificacion = :cod_unificacion

		$this->habilitarGrupoFull();

		$stmt = $this->db_conexion->prepare($SQL);
		// $stmt->bindParam(":cod_unificacion",  $cod_unificacion);
		// $stmt->bindParam(":cod_bloque_implementado",  $cod_bloque_implementado);
		$stmt->bindParam(":cod_semilla",  $cod_semilla);
		$stmt->bindParam(":edad",  $edad);
		$stmt->bindParam(":cod_farm",  $cod_farm);

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
	*
	*/
	function farm_listado_datos_semillas_en_plantacion_en_proceso(
		$cod_unificacion,
		$cod_semilla,
		$cod_bloque_implementado,
		$edad,
		$cod_farm,
		$codigos_bloque,
		$codigos_bloque_implementado
	) {
		$this->deshabilitarGrupoFull();
		$SQL = "SELECT
					far_crop_semillas_bloques.cod_semilla_bloque,
					far_crop_semillas_bloques.cod_semilla,
					far_crop_semillas_bloques.cod_plantacion,
					far_crop_semillas_bloques.cod_trasplante,
					far_crop_semillas_bloques.acres_usados,
					far_crop_semillas_bloques.cod_unificacion,
					far_crop_semillas_bloques.cod_semilla,
					far_crop_semillas_bloques.cod_bloque_implementado,
					ROUND(far_crop_semillas_bloques.porcentaje_acre_usado,0) as porcentaje_acre_usado,
					far_crop_semillas_bloques.fecha_plantacion as fecha_plantacion_original,
					DATE_FORMAT(far_crop_semillas_bloques.fecha_plantacion,
										'%Y-%m-%d') AS fecha_plantacion,
					DATE_FORMAT(bw_inventario_plantaciones.fecha_final,
										'%Y-%m-%d') AS fecha_final_plantacion,
					bw_inventario_semilla.nombre_semilla,
					bw_inventario_plantaciones.cod_inventario,
					bw_inventario_plantaciones.numero_orden,
					bw_inventario_plantaciones.fecha_inicial,
					bw_inventario_plantaciones.edad,
					bw_inventario_trasplantes.numero_orden,
					bw_inventario_trasplantes.numero_ticket,
					bw_inventario_trasplantes.fecha_entrega,
					far_fields.field,
					far_bloques.bloque as nombre_bloque_usado,
					far_crop_bloques_implementados.cod_farm
					FROM
						far_crop_semillas_bloques
						INNER JOIN bw_inventario_semilla ON (
							bw_inventario_semilla.cod_inventario = far_crop_semillas_bloques.cod_semilla
						)
						INNER JOIN bw_inventario_plantaciones ON (
							bw_inventario_plantaciones.cod_plantacion = far_crop_semillas_bloques.cod_plantacion
						)
						INNER JOIN bw_inventario_trasplantes ON (
							bw_inventario_trasplantes.cod_trasplante = far_crop_semillas_bloques.cod_trasplante
						)
						INNER JOIN far_crop_bloques_implementados ON (
							far_crop_bloques_implementados.cod_bloque_implementado = far_crop_semillas_bloques.cod_bloque_implementado
						)
						INNER JOIN far_fields ON (
							far_fields.cod_field = far_crop_bloques_implementados.cod_field
						)
						INNER JOIN far_bloques ON (
							far_bloques.cod_bloque = far_crop_bloques_implementados.cod_bloque
						)
					WHERE
					far_crop_semillas_bloques.cod_semilla = :cod_semilla
					AND far_crop_semillas_bloques.completada = 0
					AND bw_inventario_plantaciones.edad = :edad
					AND far_crop_bloques_implementados.cod_farm = :cod_farm
					AND far_crop_semillas_bloques.cod_bloque_implementado IN (" . $codigos_bloque_implementado . ");";
		// AND far_crop_semillas_bloques.cod_bloque_implementado = :cod_bloque_implementado
		// far_crop_semillas_bloques.cod_unificacion = :cod_unificacion

		$this->habilitarGrupoFull();

		$stmt = $this->db_conexion->prepare($SQL);
		// $stmt->bindParam(":cod_unificacion",  $cod_unificacion);
		// $stmt->bindParam(":cod_bloque_implementado",  $cod_bloque_implementado);
		$stmt->bindParam(":cod_semilla",  $cod_semilla);
		$stmt->bindParam(":edad",  $edad);
		$stmt->bindParam(":cod_farm",  $cod_farm);

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
	*
	*/
	function farm_actualizar_temporada_seleccionada_semilla_implementada($cod_semilla_bloque, $cod_temporada)
	{
		$SQL = "UPDATE
					far_crop_semillas_bloques
				SET
					cod_temporada = :cod_temporada
				WHERE
					cod_semilla_bloque = :cod_semilla_bloque AND far_crop_semillas_bloques.completada = 0;";

		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_semilla_bloque",  $cod_semilla_bloque);
		$stmt->bindParam(":cod_temporada",  $cod_temporada);
		try {
			$stmt->execute();
			$resultado = "0|Update completed successfully";
		} catch (PDOException $e) {
			$resultado = "1|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}


	/**
	 * FUNCIONES PARA TIPOS DE PAQUETES
	 */
	/*
	* Función para obtener los listado de todas tipos de paquetes
	*/
	function farm_listado_tipos_paquetes()
	{
		$SQL = "SELECT
					pay_tipo_packs.cod_tipo_pack,
					pay_pack_for_farm_location_category.activo,
					pay_tipo_packs.tipo_pack as tipo_de_paquete,
					pay_tipo_packs.cantidad,
					pay_tipo_packs.piece_rate,
					pay_pack_for_farm_location_category.id as cod_asociacion,
					far_farms.cod_farms,
					far_farms.farm as nombre_granja,
					far_locations.cod_location,
					far_locations.location as nombre_locacion,
					far_locations.abreviacion AS abreviatura_locacion,
					bw_inventario_categorias_semillas.cod_categoria,
					bw_inventario_categorias_semillas.nombre as nombre_categoria
				FROM
					pay_tipo_packs
						INNER JOIN
					pay_pack_for_farm_location_category ON (pay_pack_for_farm_location_category.cod_tipo_pack = pay_tipo_packs.cod_tipo_pack)
						INNER JOIN
					far_farms ON (far_farms.cod_farms = pay_pack_for_farm_location_category.cod_farm)
						INNER JOIN
					far_locations ON (far_locations.cod_location = pay_pack_for_farm_location_category.cod_location)
						INNER JOIN
					bw_inventario_categorias_semillas ON (bw_inventario_categorias_semillas.cod_categoria = pay_pack_for_farm_location_category.cod_categoria)
					WHERE pay_pack_for_farm_location_category.visible = 1
					;";
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
	* Función para obtener los datos vinculados a un tipo de paquete
	*/
	function farm_obtener_datos_un_tipo_paquete_especifico($cod_tipo_pack, $cod_farm)
	{
		$this->deshabilitarGrupoFull();

		$SQL = "SELECT 
					pay_tipo_packs.cod_tipo_pack,
					pay_tipo_packs.tipo_pack,
					pay_tipo_packs.cantidad,
					pay_tipo_packs.piece_rate,
					pay_pack_for_farm_location_category.id,
					pay_pack_for_farm_location_category.cod_farm,
					pay_pack_for_farm_location_category.activo,
					GROUP_CONCAT(pay_pack_for_farm_location_category.cod_location) as cod_location,
					GROUP_CONCAT(pay_pack_for_farm_location_category.cod_categoria) as cod_categoria
				FROM
					pay_tipo_packs
						INNER JOIN
					pay_pack_for_farm_location_category ON (pay_pack_for_farm_location_category.cod_tipo_pack = pay_tipo_packs.cod_tipo_pack)
					WHERE pay_pack_for_farm_location_category.cod_farm = :cod_farm and pay_tipo_packs.cod_tipo_pack = :cod_tipo_pack and pay_pack_for_farm_location_category.visible = 1
					GROUP BY pay_tipo_packs.tipo_pack;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_farm",  $cod_farm);
		$stmt->bindParam(":cod_tipo_pack",  $cod_tipo_pack);
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
	* Función para obtener el tipo de paquete asociado a un cod asociacion
	*/
	function farm_obtener_un_tipo_paquete_especifico($cod_asociacion)
	{
		$SQL = "SELECT 
					pay_tipo_packs.cod_tipo_pack,
					pay_tipo_packs.tipo_pack,
					pay_tipo_packs.cantidad,
					pay_tipo_packs.piece_rate,
					pay_pack_for_farm_location_category.id,
					pay_pack_for_farm_location_category.activo,
					pay_pack_for_farm_location_category.cod_farm,
					pay_pack_for_farm_location_category.cod_location,
					pay_pack_for_farm_location_category.cod_categoria
				FROM
					pay_tipo_packs
						INNER JOIN
					pay_pack_for_farm_location_category ON (pay_pack_for_farm_location_category.cod_tipo_pack = pay_tipo_packs.cod_tipo_pack)
					WHERE pay_pack_for_farm_location_category.id = :cod_asociacion and pay_pack_for_farm_location_category.visible = 1;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_asociacion",  $cod_asociacion);
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
	* Función para obtener listado de todos los tipos de paquetes activos
	*/
	function farm_listado_tipos_paquetes_activos()
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
	* Función para obtener listado de todos los tipos de paquetes activos
	*/
	function farm_listado_tipos_paquetes_activos_por_campos($codigos_campos)
	{
		$this->deshabilitarGrupoFull();
		$SQL = "SELECT
					far_bloques.cod_bloque,
					far_bloques.cod_farm,
					far_bloques.cod_field,
					CONCAT(
						far_fields.field,
						' - ',
						far_bloques.bloque,
						' - ',COALESCE(far_crop_bloques.use_acres,far_bloques.acres),' - ',
						ROUND(
							(
								COALESCE(
									far_crop_bloques.use_acres,
									far_bloques.acres
								) / far_bloques.acres
							) * 100,
							0
						),'%'
					) AS bloque,
					far_bloques.acres,
					far_farms.cod_estado,
					far_farms.farm AS nombre_granja,
					bw_inventario_estados_plantaciones.nombre AS nombre_estado
				FROM
					far_bloques
					INNER JOIN far_fields ON (far_fields.cod_field = far_bloques.cod_field)
					INNER JOIN far_farms ON (far_farms.cod_farms = far_bloques.cod_farm)
					INNER JOIN bw_inventario_estados_plantaciones ON (
						bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado
					)
					LEFT JOIN far_crop_bloques ON (
						far_crop_bloques.cod_bloque = far_bloques.cod_bloque
					)
				WHERE
					far_bloques.activo = 1 and far_bloques.cod_field in (" . $codigos_campos . ")
					GROUP BY far_bloques.cod_bloque
					ORDER BY far_bloques.cod_bloque ASC;";
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
	* Función para obtener listado de todos los tipos de paquetes activos
	*/
	function farm_listado_tipos_paquetes_para_granja($codigos_campos)
	{
		$this->deshabilitarGrupoFull();
		$SQL = "SELECT
					far_bloques.cod_bloque,
					far_bloques.cod_farm,
					far_bloques.cod_field,
					CONCAT(
						far_fields.field,
						' - ',
						far_bloques.bloque,
						' - ',COALESCE(far_crop_bloques_implementados.use_acres,0),' - ',
						ROUND(
							COALESCE(
								far_crop_bloques_implementados.porcentaje_acre_usado,
								0
								),
							0
						),'%'
					) AS bloque,
					far_bloques.acres,
					far_farms.cod_estado,
					far_farms.farm AS nombre_granja,
					bw_inventario_estados_plantaciones.nombre AS nombre_estado
				FROM
					far_bloques
					INNER JOIN far_fields ON (far_fields.cod_field = far_bloques.cod_field)
					INNER JOIN far_farms ON (far_farms.cod_farms = far_bloques.cod_farm)
					INNER JOIN bw_inventario_estados_plantaciones ON (
						bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado
					)
					LEFT JOIN far_crop_bloques_implementados ON (
						far_crop_bloques_implementados.cod_bloque = far_bloques.cod_bloque
					)
				WHERE
					far_bloques.activo = 1 and far_bloques.cod_field in (" . $codigos_campos . ")
					GROUP BY far_bloques.cod_bloque
					ORDER BY far_bloques.cod_bloque ASC;";
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
	* Función para obtener listado de todos los tipos de paquetes que no tenga algún semilla asiganda
	*/
	function farm_listado_tipos_paquetes_libres($codigos_campos, $codigosBloquesUsados)
	{
		if ($codigosBloquesUsados == null) {
			$codigosBloquesUsados = 0;
		}
		$this->deshabilitarGrupoFull();
		$SQL = "SELECT
					far_bloques.cod_bloque,
					far_bloques.cod_farm,
					far_bloques.cod_field,
					CONCAT(
						far_fields.field,
						' - ',
						far_bloques.bloque,
						' - ',COALESCE(far_crop_bloques_implementados.use_acres,0),' - ',
						ROUND(
							COALESCE(
								far_crop_bloques_implementados.porcentaje_acre_usado,
								0
							),
							0
						),'%'
					) AS bloque,
					far_bloques.acres,
					far_farms.cod_estado,
					far_farms.farm AS nombre_granja,
					bw_inventario_estados_plantaciones.nombre AS nombre_estado
				FROM
					far_bloques
					INNER JOIN far_fields ON (far_fields.cod_field = far_bloques.cod_field)
					INNER JOIN far_farms ON (far_farms.cod_farms = far_bloques.cod_farm)
					INNER JOIN bw_inventario_estados_plantaciones ON (
						bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado
					)
					LEFT JOIN far_crop_bloques ON (
						far_crop_bloques.cod_bloque = far_bloques.cod_bloque
					)
					LEFT JOIN far_crop_bloques_implementados ON (
						far_crop_bloques_implementados.cod_bloque = far_bloques.cod_bloque
					)
				WHERE
					far_bloques.activo = 1
					AND far_bloques.cod_bloque NOT IN (" . $codigosBloquesUsados . ")
					and far_bloques.cod_field in (" . $codigos_campos . ")
					GROUP BY far_bloques.cod_bloque
					ORDER BY far_bloques.cod_bloque ASC;";
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
	* Función para obtener listado de todas las locaciones en granjas que esten activas
	*/
	function farm_listado_localizaciones_en_granja($cod_farm)
	{
		$SQL = "SELECT 
					cod_location, location as nombre_locacion
				FROM
					far_locations
				WHERE
					cod_farms = :cod_farm AND activo = 1;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_farm",  $cod_farm);
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
	* Función para crear un nuevo registro de un tipo de paquete
	*/
	function farm_crear_tipo_paquete(
		$nombre_tipo_bloque,
		$activo,
		$user_insert,
    $piece_rate
	) {
		$SQL = "INSERT INTO
					pay_tipo_packs (
						tipo_pack,
						cantidad,
						activo,
            piece_rate,
						user_insert
					)
				VALUES
					(
						:nombre_tipo_bloque,
						'1',
						:activo,
            :piece_rate,
						:user_insert
					);";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":nombre_tipo_bloque", $nombre_tipo_bloque);
		$stmt->bindParam(":user_insert", $user_insert);
		$stmt->bindParam(":piece_rate", $piece_rate);
		$stmt->bindParam(":activo", $activo);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Successful registration|" . $this->db_conexion->lastInsertId();
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			$resultado = "1|Failed to register|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para crear un nuevo registro de los registros que estan asociados a un tipo de paquete
	*/
	function farm_crear_vinculaciones_con_tipo_paquete(
		$cod_tipo_pack,
		$cod_farm,
		$cod_location,
		$cod_categoria,
		$actualizar
	) {
		$SQL = "INSERT INTO
					pay_pack_for_farm_location_category (
						cod_tipo_pack,
						cod_farm,
						cod_location,
						cod_categoria
					)
				VALUES
					(
						:cod_tipo_pack,
						:cod_farm,
						:cod_location,
						:cod_categoria
					);";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_tipo_pack", $cod_tipo_pack);
		$stmt->bindParam(":cod_farm", $cod_farm);
		$stmt->bindParam(":cod_location", $cod_location);
		$stmt->bindParam(":cod_categoria", $cod_categoria);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if ($actualizar == 0) {
				$resultado = "0|Successful registration|" . $this->db_conexion->lastInsertId();
			} else {
				$resultado = "0|Successful update";;
			}
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
	function farm_actualizar_tipo_paquete_por_cod_asociacion(
		$cod_asociacion,
		$cod_farm,
		$cod_categoria,
		$cod_location,
		$tipo_pack,
		$estado_activacion
	) {
		$SQL = "UPDATE pay_pack_for_farm_location_category
						JOIN
					pay_tipo_packs ON pay_pack_for_farm_location_category.cod_tipo_pack = pay_tipo_packs.cod_tipo_pack 
				SET
					pay_pack_for_farm_location_category.cod_farm = :cod_farm,
					pay_pack_for_farm_location_category.cod_categoria = :cod_categoria,
					pay_pack_for_farm_location_category.cod_location = :cod_location,
					pay_tipo_packs.tipo_pack = :tipo_pack,
					pay_tipo_packs.activo = :estado_activacion
				WHERE
					pay_pack_for_farm_location_category.id = :cod_asociacion and pay_pack_for_farm_location_category.visible = 1;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_asociacion", $cod_asociacion);

		$stmt->bindParam(":cod_farm", $cod_farm);
		$stmt->bindParam(":cod_categoria", $cod_categoria);
		$stmt->bindParam(":cod_location", $cod_location);
		$stmt->bindParam(":tipo_pack", $tipo_pack);
		$stmt->bindParam(":estado_activacion", $estado_activacion);
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
	* Función para actualizar un tipo de paquete especifico
	*/
	function farm_actualizar_tipo_paquete(
		$cod_tipo_pack,
		$cantidad,
		$tipo_pack,
    $piece_rate
	) {
		$SQL = "UPDATE
					pay_tipo_packs
				SET
					tipo_pack = :tipo_pack,
					cantidad = :cantidad,
          piece_rate = :piece_rate
				WHERE
					cod_tipo_pack = :cod_tipo_pack;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_tipo_pack", $cod_tipo_pack);
		$stmt->bindParam(":cantidad", $cantidad);
		$stmt->bindParam(":tipo_pack", $tipo_pack);
		$stmt->bindParam(":piece_rate", $piece_rate);

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
	* Función para eliminar un categoria de semilla vinculada a un tipo de paquet
	*/
	function farm_eliminar_categoria_vinculada_tipo_paquete($cod_tipo_pack, $cod_farm, $cod_categoria)
	{
		$SQL = "UPDATE
					pay_pack_for_farm_location_category
				SET
					visible = 0
				WHERE
					cod_tipo_pack = :cod_tipo_pack
					and cod_farm = :cod_farm
					and cod_categoria = :cod_categoria;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_tipo_pack", $cod_tipo_pack);
		$stmt->bindParam(":cod_farm", $cod_farm);
		$stmt->bindParam(":cod_categoria", $cod_categoria);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Successful update";
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*
	* Función para eliminar un lacacion de semilla vinculada a un tipo de paquet
	*/
	function farm_eliminar_lacacion_vinculada_tipo_paquete($cod_tipo_pack, $cod_farm, $cod_location)
	{
		$SQL = "UPDATE
					pay_pack_for_farm_location_category
				SET
					visible = 0
				WHERE
					cod_tipo_pack = :cod_tipo_pack
					and cod_farm = :cod_farm
					and cod_location = :cod_location;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_tipo_pack", $cod_tipo_pack);
		$stmt->bindParam(":cod_farm", $cod_farm);
		$stmt->bindParam(":cod_location", $cod_location);
		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Successful update";
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para cambiar el estado de activacion una temporada especifica
	*/
	function farm_cambiar_estado_tipo_paquete_especifico($cod_asociacion, $activo)
	{
		$SQL = "UPDATE pay_pack_for_farm_location_category SET activo = :activo WHERE id = :cod_asociacion;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_asociacion",  $cod_asociacion);
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
	 * FUNCIONES PARA ACTIVIDADES
	 */
	/*
	* Función para obtener los listado de todas tipos de paquetes
	*/
	function farm_listado_actividades()
	{
		$SQL = "SELECT
				pay_activities.cod_activity,
				pay_activities.codigo,
				pay_activities.activity,
				pay_activities.piece_rate,
				pay_activities.activo,
				far_locations.location as nombre_locacion,
				bw_inventario_estados_plantaciones.abreviatura,
				CONCAT(
					bw_inventario_estados_plantaciones.abreviatura,
					' - ',
					far_farms.farm
				) AS nombre_granja
			FROM
				pay_activities
				INNER JOIN far_farms ON (
					far_farms.cod_farms = pay_activities.cod_farms
				)
				INNER JOIN far_locations ON (
					far_locations.cod_location = pay_activities.cod_location
				)
				INNER JOIN bw_inventario_estados_plantaciones ON (
					bw_inventario_estados_plantaciones.cod_estado = far_farms.cod_estado
				)
			WHERE
				pay_activities.visible = 1;";
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
	* Función para obtener una actividad miscelanea especifica
	*/
	function farm_obtener_una_actividad_miscelanea_especifica($codigo_actividad_miscelanea)
	{
		$SQL = "SELECT
					pay_activities.cod_activity,
					pay_activities.cod_farms,
					pay_activities.cod_location,
					pay_activities.codigo,
					pay_activities.activity,
					pay_activities.piece_rate,
					pay_activities.activo
				FROM
					pay_activities
				WHERE
					pay_activities.cod_activity = :codigo_actividad_miscelanea
						AND pay_activities.visible = 1;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_actividad_miscelanea",  $codigo_actividad_miscelanea);
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
	* Función para crear un nuevo registro de una actividad miscelanea
	*/
	function farm_crear_actividad_miscelaneas(
		$cod_farms,
		$cod_location,
		$codigo,
		$activity,
		$piece_rate,
		$user_insert
	) {
		$SQL = "INSERT INTO
					pay_activities (
						cod_farms,
						cod_location,
						codigo,
						activity,
						piece_rate,
						user_insert
					)
				VALUES
					(
						:cod_farms,
						:cod_location,
						:codigo,
						:activity,
						:piece_rate,
						:user_insert
					);";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_farms", $cod_farms);
		$stmt->bindParam(":cod_location", $cod_location);
		$stmt->bindParam(":codigo", $codigo);
		$stmt->bindParam(":activity", $activity);
		$stmt->bindParam(":piece_rate", $piece_rate);
		$stmt->bindParam(":user_insert", $user_insert);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Successful registration|" . $this->db_conexion->lastInsertId();
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			$resultado = "1|Failed to register|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}



	/*
	* Función para actualizar una actividad miscelena especifica
	*/
	function farm_actualizar_actividad_miscelanea(
		$cod_activity,
		$cod_farms,
		$cod_location,
		$codigo,
		$activity,
		$piece_rate
	) {
		$SQL = "UPDATE
					pay_activities
				SET
					cod_farms = :cod_farms,
					cod_location = :cod_location,
					codigo = :codigo,
					activity = :activity,
					piece_rate = :piece_rate
				WHERE
					cod_activity = :cod_activity;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_farms", $cod_farms);
		$stmt->bindParam(":cod_location", $cod_location);
		$stmt->bindParam(":codigo", $codigo);
		$stmt->bindParam(":activity", $activity);
		$stmt->bindParam(":piece_rate", $piece_rate);
		$stmt->bindParam(":cod_activity", $cod_activity);
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
	* Función para cambiar el estado de activacion una activadad miscelanea especifica
	*/
	function farm_cambiar_estado_actividad_miscelanea($codigo_actividad_miscelanea, $activo)
	{
		$SQL = "UPDATE pay_activities SET activo = :activo WHERE cod_activity = :codigo_actividad_miscelanea;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_actividad_miscelanea",  $codigo_actividad_miscelanea);
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
	 * FUNCIONES PARA LOCACIONES
	 */
	/*
	* Función para obtener los listado de todas las locaciones
	*/
	function farm_listado_locaciones()
	{
		$SQL = "SELECT
					far_locations.cod_location,
					far_locations.location as nombre_locacion,
					far_locations.abreviacion,
					far_locations.activo,
          far_locations.cost_center,
          far_locations.labor_phase,
					far_farms.farm as nombre_granja,
          far_farms.activo as granja_activa
				FROM
					far_locations
						INNER JOIN
					far_farms ON (far_farms.cod_farms = far_locations.cod_farms)
				WHERE
					far_locations.visible = 1
				ORDER BY
						far_locations.location,
						far_farms.farm;";
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
	* Función para cambiar el estado de activacion una locacion especifica
	*/
	function farm_cambiar_estado_locacion($codigo_locacion, $activo)
	{
		$SQL = "UPDATE far_locations SET activo = :activo WHERE cod_location = :codigo_locacion;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_locacion",  $codigo_locacion);
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
	* Función para obtener una locacion especifica
	*/
	function farm_obtener_una_locacion_especifica($codigo_locacion)
	{
		$SQL = "SELECT
					far_locations.cod_location,
					far_locations.location as nombre_locacion,
					far_locations.abreviacion,
					far_locations.activo,
					far_locations.cod_farms,
					far_locations.cost_center,
					far_locations.labor_phase,
					far_farms.farm as nombre_granja
				FROM
					far_locations
						INNER JOIN
					far_farms ON (far_farms.cod_farms = far_locations.cod_farms)
				WHERE
					far_locations.visible = 1 and far_locations.cod_location = :codigo_locacion
				ORDER BY
						far_locations.location,
						far_farms.farm;";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":codigo_locacion",  $codigo_locacion);
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
	* Función para crear un nuevo registro de una locación
	*/
	function farm_crear_locacion(
		$cod_farms,
		$location,
		$abreviacion,
		$user_insert
	) {
		$SQL = "INSERT INTO
					far_locations (
						cod_farms,
						location,
						abreviacion,
						user_insert
					)
				VALUES
					(
					:cod_farms,
					:location,
					:abreviacion,
					:user_insert);";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_farms", $cod_farms);
		$stmt->bindParam(":location", $location);
		$stmt->bindParam(":abreviacion", $abreviacion);
		$stmt->bindParam(":user_insert", $user_insert);


		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resultado = "0|Successful registration|" . $this->db_conexion->lastInsertId();
		} catch (PDOException $e) {
			$resultado = $e->getMessage();
			$resultado = "1|Failed to register|" . $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	* Función para actualizar una locacion especifica
	*/
	function farm_actualizar_locacion(
		$cod_farms,
		$location,
		$abreviacion,
		$cod_location,
    $cost_center,
    $labor_phase
	) {
		$SQL = "UPDATE
					far_locations
				SET
					cod_farms = :cod_farms,
					location = :location,
					abreviacion = :abreviacion,
					cost_center = :cost_center,
					labor_phase = :labor_phase
				WHERE
					(cod_location = :cod_location);";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_farms", $cod_farms);
		$stmt->bindParam(":location", $location);
		$stmt->bindParam(":abreviacion", $abreviacion);
		$stmt->bindParam(":cod_location", $cod_location);
		$stmt->bindParam(":cost_center", $cost_center);
		$stmt->bindParam(":labor_phase", $labor_phase);
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
	 * Obtiene la data para el reporte del mapa del planting.
	 */
	function get_mapa_farm_planting(
		$fechaInicial,
		$fechaFinal,
		$codigosEstados,
		$codigosGranjas,
		$codigosCampos,
		$codigoBloquesUsados
	) {
		$condiciones = ' ';
		/*if ($codigosEstados != null && $codigosEstados != '' && $codigosEstados != 0) {

			$condiciones .= " AND bw_inventario_estados_plantaciones.cod_estado in (" . $codigosEstados . ")  ";
		}*/
		if ($codigosGranjas != null && $codigosGranjas != '' && $codigosGranjas != 0) {

			$condiciones .= " AND granj.cod_farms IN (" . $codigosGranjas . ")  ";
		}

		if ($codigosCampos != null && $codigosCampos != '' && $codigosCampos != 0) {

			$condiciones .= " AND camp.cod_field IN (" . $codigosCampos . ")  ";
		}

		if ($codigoBloquesUsados != null && $codigoBloquesUsados != '' && $codigoBloquesUsados != 0) {

			$condiciones .= " AND blo.cod_bloque IN (" . $codigoBloquesUsados . ")  ";
		}
		$this->deshabilitarGrupoFull();
		$SQL = "SELECT 
					granj.farm AS FARM,
					camp.field AS FIELD,
					blo.bloque AS BLOQUE,
					bloq_imple.ini_acres AS INITIAL_ACRE,
					DATE(crop_semill_progr.fecha_plantacion) AS PLANTING_DATE,
					categ_semill.nombre AS COMMODITY,
					semill.nombre_semilla AS SEED,
					crop_semill_progr.acres_usados AS USED_ACRES,
					plant.edad AS AGE,
					DATE(regis_perma_semill.date_insert) AS TERMINATED_DATE,
					CASE
						WHEN crop_semill_progr.completada = 1 THEN 'Terminated'
						ELSE 'In Progress'
					END AS COMPLETE
				FROM
					ewvihjmy_LMF.far_crop_semillas_bloques AS crop_semill_progr
						INNER JOIN
					ewvihjmy_LMF.far_semillas_en_bloques semblo ON (semblo.cod_unificacion = crop_semill_progr.cod_unificacion
						AND (semblo.activo = 1 OR semblo.activo = 0))
						INNER JOIN
					bw_inventario_semilla AS semill ON (semill.cod_inventario = semblo.cod_semilla
						AND semill.activo = 1)
						INNER JOIN
					bw_inventario_categorias_semillas AS categ_semill ON (categ_semill.cod_categoria = semill.cod_categoria)
						INNER JOIN
					far_bloques AS blo ON (blo.cod_bloque = semblo.cod_bloque
						AND blo.activo = 1)
						INNER JOIN
					far_fields AS camp ON (camp.cod_field = blo.cod_field
						AND camp.activo = 1)
						INNER JOIN
					far_farms AS granj ON (granj.cod_farms = camp.cod_farm)
						INNER JOIN
					far_crop_bloques_implementados AS bloq_imple ON (bloq_imple.cod_bloque_implementado = crop_semill_progr.cod_bloque_implementado)
						INNER JOIN
					bw_inventario_plantaciones AS plant ON (plant.cod_plantacion = crop_semill_progr.cod_plantacion)
						LEFT JOIN
					far_crop_registro_semillas_implementadas AS regis_perma_semill ON (regis_perma_semill.cod_semilla_bloque = crop_semill_progr.cod_semilla_bloque)
				WHERE
					DATE(crop_semill_progr.fecha_plantacion) BETWEEN :fechaInicial AND :fechaFinal
					" . $condiciones . ";";
		
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
		$this->habilitarGrupoFull();

		return $resultado;
	}

}
