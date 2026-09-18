<?php

/*

 * Funciones para obtener información general de la base de datos.

 *

 * @author      Jairo Bonilla

 * @date        2018-10-25

 */



class db_plantaciones
{

	public $db_conexion;



	function __construct()
	{

		$this->db_conexion = new db_lion();

		$this->db_conexion = $this->db_conexion->dbConnect();
	}





	/*

      * Cambia de flujo a una plantación

      */

	function bw_cambiar_flujo_plantacion(
		$cod_plantacion,

		$flag_estado,

		$flag_flujo,

		$user_insert
	) {

		$SQL = "CALL bw_cambiar_flujo_plantacion(:cod_plantacion,

										:flag_estado,

										:flag_flujo,

                                		:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_plantacion",  $cod_plantacion);

		$stmt->bindParam(":flag_estado",  $flag_estado);

		$stmt->bindParam(":flag_flujo",  $flag_flujo);

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

	 * Funciones para obtener listado de estados de plantaciones disponibles

	 */

	function bw_listado_estados_plantaciones()
	{

		$SQL = "SELECT

					    bw_estados_plantacion.cod_estado_plantacion,

					    bw_estados_plantacion.estado_plantacion,

					    bw_estados_plantacion.estado_plantacion_english,

					    bw_estados_plantacion.tiempo_espera,

					    bw_estados_plantacion.cod_estado_padre,

					    estados.estado_plantacion AS estado_padre

					FROM

					    bw_estados_plantacion

					        LEFT JOIN

					    bw_estados_plantacion estados ON (estados.cod_estado_plantacion = bw_estados_plantacion.cod_estado_padre)

					WHERE

					    bw_estados_plantacion.activo = 1

					ORDER BY bw_estados_plantacion.cod_estado_plantacion ASC;";

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

	 * Funciones para obtener listado de plantaciones disponibles

	 */

	function bw_listado_plantaciones()
	{

		$SQL = "SELECT

					    bw_plantaciones.cod_plantacion,

					    bw_plantaciones.cod_info_empresa,

					    bw_info_empresa.nombre_empresa,

					    bw_plantaciones.anio_plantacion,

					    bw_plantaciones.num_plantacion,

                        bw_temporadas.codigo_temporada,

					    bw_plantaciones.cod_estado,

					    bw_plantaciones.acres_plantados,

					    bw_plantaciones.user_insert

					FROM

					    bw_plantaciones

					        INNER JOIN

					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_plantaciones.cod_info_empresa)

					        INNER JOIN

					    bw_temporadas ON (bw_temporadas.cod_temporada = bw_plantaciones.cod_temporada)

					WHERE

					    bw_plantaciones.activo = 1;";

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

	 * Funciones para obtener listado de zonas disponibles

	 */

	function bw_listado_zonas()
	{

		$SQL = "SELECT

					    bw_zonas.cod_zona,

					    bw_zonas.cod_info_empresa,

					    bw_info_empresa.nombre_empresa,

					    bw_zonas.zona,

					    bw_zonas.abreviatura,

					    bw_zonas.ubicacion,

					    bw_zonas.bloque_inicial,

					    bw_zonas.bloque_final,

					    bw_zonas.cantidad_acres,

					    bw_zonas.activo

					FROM

					    bw_zonas

					        INNER JOIN

					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_zonas.cod_info_empresa)

					ORDER BY bw_zonas.zona ASC;";

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

	 * Funciones para obtener listado de zonas disponibles

	 */

	function bw_listado_zonas_por_granjas($cod_info_empresa)
	{

		$SQL = "SELECT DISTINCT

					    bw_zonas.cod_zona,

					    bw_zonas.cod_info_empresa,

					    bw_info_empresa.nombre_empresa,

					    bw_zonas.zona,

					    bw_zonas.abreviatura,

					    bw_zonas.ubicacion,

					    bw_bloques.clave_bloque,

					    bw_zonas.bloque_inicial,

					    bw_zonas.bloque_final,

					    bw_zonas.cantidad_acres,

					    bw_zonas.activo

					FROM

					    bw_zonas

					        INNER JOIN

					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_zonas.cod_info_empresa)

					    	LEFT JOIN

					    bw_bloques ON (bw_bloques.cod_zona = bw_zonas.cod_zona)

					    WHERE

					    	bw_zonas.cod_info_empresa IN (" . $cod_info_empresa . ")

					ORDER BY bw_zonas.zona ASC;";

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

	 * Funciones para obtener listado de zonas disponibles por finca

	 */

	function bw_listado_zonas_por_finca($cod_info_empresa)
	{

		$SQL = "SELECT

					    bw_zonas.cod_zona,

					    bw_zonas.cod_info_empresa,

					    bw_info_empresa.nombre_empresa,

					    bw_zonas.zona,

					    bw_zonas.abreviatura,

					    bw_zonas.ubicacion,

					    bw_zonas.bloque_inicial,

					    bw_zonas.bloque_final,

					    bw_zonas.cantidad_acres,

					    bw_zonas.activo

					FROM

					    bw_zonas

					        INNER JOIN

					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_zonas.cod_info_empresa)

					    WHERE bw_zonas.cod_info_empresa = :cod_info_empresa

					ORDER BY bw_zonas.zona ASC;";

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

	 * Funciones para obtener información de una zona

	 */

	function bw_obtener_info_zona($cod_zona)
	{

		$SQL = "SELECT

				    bw_zonas.cod_zona,

				    bw_zonas.cod_info_empresa,

				    bw_info_empresa.nombre_empresa,

				    bw_zonas.zona,

				    bw_zonas.abreviatura,

				    bw_zonas.ubicacion,

				    bw_zonas.bloque_inicial,

				    bw_zonas.bloque_final,

				    bw_zonas.cantidad_acres,

				    bw_zonas.activo

				FROM

				    bw_zonas

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_zonas.cod_info_empresa)

				WHERE

				    bw_zonas.cod_zona = :cod_zona;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_zona",  $cod_zona);

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

      * Guarda una zona

      */

	function bw_guardar_zona(
		$codigo_zona,

		$cod_info_empresa,

		$zona,

		$abreviatura,

		$ubicacion,

		$bloque_inicial,

		$bloque_final,

		$cantidad_acres,

		$user_insert
	) {

		$SQL = "CALL bw_guardar_zona(:codigo_zona,

        								:cod_info_empresa,

        								:zona,

        								:abreviatura,

        								:ubicacion,

        								:bloque_inicial,

        								:bloque_final,

        								:cantidad_acres,

                                		:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_zona",  $codigo_zona);

		$stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);

		$stmt->bindParam(":zona",  $zona);

		$stmt->bindParam(":abreviatura",  $abreviatura);

		$stmt->bindParam(":ubicacion",  $ubicacion);

		$stmt->bindParam(":bloque_inicial",  $bloque_inicial);

		$stmt->bindParam(":bloque_final",  $bloque_final);

		$stmt->bindParam(":cantidad_acres",  $cantidad_acres);

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

	function bw_cambiar_estado_zona(
		$cod_zona,

		$flag_activo,

		$user_insert
	) {

		$SQL = "CALL bw_cambiar_estado_zona(:cod_zona,

										:flag_activo,

                                		:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_zona",  $cod_zona);

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

	 * Funciones para obtener listado de bloques disponibles

	 */

	function bw_listado_bloques_zona($cod_zona)
	{

		$SQL = "SELECT

					    bw_bloques.cod_bloque,

					    bw_bloques.cod_zona,

					    bw_zonas.zona,

					    bw_bloques.nombre_bloque,

					    bw_bloques.num_acres,

					    bw_bloques.clave_bloque,

					    bw_bloques.activo

					FROM

					    bw_bloques

					        INNER JOIN

					    bw_zonas ON (bw_zonas.cod_zona = bw_bloques.cod_zona)

					    WHERE bw_bloques.cod_zona = :cod_zona

					ORDER BY bw_bloques.nombre_bloque ASC;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_zona",  $cod_zona);

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

	 * Funciones para obtener información de un bloque

	 */

	function bw_obtener_info_bloque($cod_bloque)
	{

		$SQL = "SELECT

					    bw_bloques.cod_bloque,

					    bw_bloques.cod_zona,

					    bw_zonas.zona,

					    bw_bloques.nombre_bloque,

					    bw_bloques.num_acres,

					    bw_bloques.clave_bloque,

					    bw_bloques.activo

					FROM

					    bw_bloques

					        INNER JOIN

					    bw_zonas ON (bw_zonas.cod_zona = bw_bloques.cod_zona)

					    WHERE bw_bloques.cod_bloque = :cod_bloque";

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

      * Guarda un bloque

      */

	function bw_guardar_bloque(
		$codigo_zona,

		//$nombre_bloque,

		$modal_bloque_inicial,

		$modal_bloque_final,

		$num_acres,

		$clave_bloque,

		$user_insert
	) {

		$SQL = "CALL bw_guardar_bloque(:codigo_zona,

										:modal_bloque_inicial,

										:modal_bloque_final,

										:num_acres,

										:clave_bloque,

                                		:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_zona",  $codigo_zona);

		$stmt->bindParam(":modal_bloque_inicial",  $modal_bloque_inicial);

		$stmt->bindParam(":modal_bloque_final",  $modal_bloque_final);

		$stmt->bindParam(":num_acres",  $num_acres);

		$stmt->bindParam(":clave_bloque",  $clave_bloque);

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

      * Cambia el flag de activo de los bloques

      */

	function bw_cambiar_estado_bloque(
		$cod_bloque,

		$flag_activo,

		$user_insert
	) {

		$SQL = "CALL bw_cambiar_estado_bloque(:cod_bloque,

										:flag_activo,

                                		:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_bloque",  $cod_bloque);

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

      * Guarda una plantación

      */

	function plan_guardar_plantacion(
		$codigo_plantacion,

		$cod_info_empresa,

		$anio_plantacion,

		$fecha_plantacion_planeada,

		$num_plantacion,

		$acres_plantados,

		$cod_temporada,

		$fecha_plantacion_ejecutada,

		$user_insert
	) {

		$SQL = "CALL plan_guardar_plantacion(:codigo_plantacion,

        								:cod_info_empresa,

        								:anio_plantacion,

        								:fecha_plantacion_planeada,

        								:num_plantacion,

        								:acres_plantados,

        								:cod_temporada,

        								:fecha_plantacion_ejecutada,

                                		:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

		$stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);

		$stmt->bindParam(":anio_plantacion",  $anio_plantacion);

		$stmt->bindParam(":fecha_plantacion_planeada",  $fecha_plantacion_planeada);

		$stmt->bindParam(":num_plantacion",  $num_plantacion);

		$stmt->bindParam(":acres_plantados",  $acres_plantados);

		$stmt->bindParam(":cod_temporada",  $cod_temporada);

		$stmt->bindParam(":fecha_plantacion_ejecutada",  $fecha_plantacion_ejecutada);

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

	 * Funciones para obtener información de una plantación

	 */

	function plan_obtener_info_plantacion($cod_plantacion)
	{

		$SQL = "SELECT

				    bw_plantaciones.cod_plantacion,

				    bw_plantaciones.cod_info_empresa,

				    bw_plantaciones.anio_plantacion,

				    bw_plantaciones.num_plantacion,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones.fecha_plantacion_planeada,'%Y-%m-%d'),'%m-%d-%Y') as fecha_plantacion_planeada,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones.fecha_plantacion_ejecutada,'%Y-%m-%d'),'%m-%d-%Y') as fecha_plantacion_ejecutada,

				    bw_plantaciones.acres_plantados,

				    bw_plantaciones.cod_usuario_planificacion,

				    bw_plantaciones.cod_usuario_plantacion,

				    bw_plantaciones.cod_estado,

				    bw_plantaciones.cod_temporada,

				    bw_info_empresa.nombre_empresa,

				    bw_estados_plantacion.estado_plantacion,

				    bw_temporadas.codigo_temporada

				FROM

				    bw_plantaciones

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_plantaciones.cod_info_empresa)

				        INNER JOIN

				    bw_estados_plantacion ON (bw_estados_plantacion.cod_estado_plantacion = bw_plantaciones.cod_estado)

				        INNER JOIN

				    bw_temporadas ON (bw_temporadas.cod_temporada = bw_plantaciones.cod_temporada)

				WHERE

				    bw_plantaciones.cod_plantacion = :cod_plantacion;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_plantacion",  $cod_plantacion);

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

	 * Funciones para obtener listado de plantaciones

	 */

	function plan_listado_plantaciones()
	{

		$SQL = "SELECT

				    bw_plantaciones.cod_plantacion,

				    bw_plantaciones.cod_info_empresa,

				    bw_plantaciones.anio_plantacion,

				    bw_plantaciones.num_plantacion,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones.fecha_plantacion_planeada,'%Y-%m-%d'),'%m-%d-%Y') as fecha_plantacion_planeada,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones.fecha_plantacion_ejecutada,'%Y-%m-%d'),'%m-%d-%Y') as fecha_plantacion_ejecutada,

				    bw_plantaciones.acres_plantados,

				    bw_plantaciones.cod_usuario_planificacion,

				    bw_plantaciones.cod_usuario_plantacion,

				    bw_plantaciones.cod_estado,

				    bw_plantaciones.cod_temporada,

				    bw_info_empresa.nombre_empresa,

				    bw_estados_plantacion.estado_plantacion,

				    bw_temporadas.codigo_temporada

				FROM

				    bw_plantaciones

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_plantaciones.cod_info_empresa)

				        INNER JOIN

				    bw_estados_plantacion ON (bw_estados_plantacion.cod_estado_plantacion = bw_plantaciones.cod_estado)

				        INNER JOIN

				    bw_temporadas ON (bw_temporadas.cod_temporada = bw_plantaciones.cod_temporada)

				ORDER BY bw_plantaciones.cod_info_empresa , anio_plantacion , num_plantacion ASC";

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

	 * Funciones para obtener listado de plantaciones

	 */

	function plan_listado_plantaciones_por_granjas($cod_info_empresa, $cod_estados)
	{

		$SQL = "SELECT

				    bw_plantaciones.cod_plantacion,

				    bw_plantaciones.cod_info_empresa,

				    bw_plantaciones.anio_plantacion,

				    bw_plantaciones.num_plantacion,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones.fecha_plantacion_planeada,'%Y-%m-%d'),'%m-%d-%Y') as fecha_plantacion_planeada,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones.fecha_plantacion_ejecutada,'%Y-%m-%d'),'%m-%d-%Y') as fecha_plantacion_ejecutada,

				    bw_plantaciones.acres_plantados,

				    bw_plantaciones.cod_usuario_planificacion,

				    bw_plantaciones.cod_usuario_plantacion,

				    bw_plantaciones.cod_estado,

				    bw_plantaciones.cod_temporada,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    bw_info_empresa.nombre_empresa,

				    bw_estados_plantacion.estado_plantacion,

				    bw_estados_plantacion.tiempo_espera,

				    bw_temporadas.codigo_temporada,

				    GROUP_CONCAT(DISTINCT bw_zonas.zona SEPARATOR '<br>') as zonas

				FROM

				    bw_plantaciones

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_plantaciones.cod_info_empresa)

				        INNER JOIN

				    bw_estados_plantacion ON (bw_estados_plantacion.cod_estado_plantacion = bw_plantaciones.cod_estado)

				        INNER JOIN

				    bw_temporadas ON (bw_temporadas.cod_temporada = bw_plantaciones.cod_temporada)

						LEFT JOIN

					bw_detalle_bloques_plantaciones ON (bw_detalle_bloques_plantaciones.cod_plantacion = bw_plantaciones.cod_plantacion)

						LEFT JOIN

					bw_bloques ON (bw_bloques.cod_bloque = bw_detalle_bloques_plantaciones.cod_bloque)

				    	LEFT JOIN

				    bw_zonas ON (bw_zonas.cod_zona = bw_bloques.cod_zona)

				    WHERE

				    	bw_plantaciones.cod_info_empresa IN (" . $cod_info_empresa . ")

				    AND bw_plantaciones.cod_estado IN(" . $cod_estados . ")

				GROUP BY bw_plantaciones.cod_plantacion

				ORDER BY bw_plantaciones.date_insert DESC";

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

	 * Funciones para obtener listado de plantaciones

	 */

	function plan_listado_plantaciones_por_granjas_paginacion($cod_info_empresa, $inicio, $limite)
	{

		$SQL = "SELECT

				    bw_plantaciones.cod_plantacion,

				    bw_plantaciones.cod_info_empresa,

				    bw_plantaciones.anio_plantacion,

				    bw_plantaciones.num_plantacion,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones.fecha_plantacion_planeada,'%Y-%m-%d'),'%m-%d-%Y') as fecha_plantacion_planeada,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones.fecha_plantacion_ejecutada,'%Y-%m-%d'),'%m-%d-%Y') as fecha_plantacion_ejecutada,

				    bw_plantaciones.acres_plantados,

				    bw_plantaciones.cod_usuario_planificacion,

				    bw_plantaciones.cod_usuario_plantacion,

				    bw_plantaciones.cod_estado,

				    bw_plantaciones.cod_temporada,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    bw_info_empresa.nombre_empresa,

				    bw_estados_plantacion.estado_plantacion,

				    bw_estados_plantacion.tiempo_espera,

				    bw_temporadas.codigo_temporada,

				    GROUP_CONCAT(DISTINCT bw_zonas.zona SEPARATOR '<br>') as zonas

				FROM

				    bw_plantaciones

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_plantaciones.cod_info_empresa)

				        INNER JOIN

				    bw_estados_plantacion ON (bw_estados_plantacion.cod_estado_plantacion = bw_plantaciones.cod_estado)

				        INNER JOIN

				    bw_temporadas ON (bw_temporadas.cod_temporada = bw_plantaciones.cod_temporada)

						LEFT JOIN

					bw_detalle_bloques_plantaciones ON (bw_detalle_bloques_plantaciones.cod_plantacion = bw_plantaciones.cod_plantacion)

						LEFT JOIN

					bw_bloques ON (bw_bloques.cod_bloque = bw_detalle_bloques_plantaciones.cod_bloque)

				    	LEFT JOIN

				    bw_zonas ON (bw_zonas.cod_zona = bw_bloques.cod_zona)

				    WHERE

				    	bw_plantaciones.cod_info_empresa IN (" . $cod_info_empresa . ")

				GROUP BY bw_plantaciones.cod_plantacion

				ORDER BY bw_plantaciones.date_insert DESC

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

	 * Funciones para obtener listado de plantaciones

	 */

	function plan_total_plantaciones_por_granjas($cod_info_empresa)
	{

		$SQL = "SELECT

				    COUNT(bw_plantaciones.cod_plantacion) as total

				FROM

				    bw_plantaciones

			    WHERE

			    	bw_plantaciones.cod_info_empresa IN (" . $cod_info_empresa . ");";

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

      * Agrega una zona a una plantacion

      */

	function plan_agregar_zona_plantacion(
		$codigo_plantacion,

		$cod_zona,

		$cod_bloque,

		$cantidad_acres,

		$user_insert
	) {

		$SQL = "CALL plan_agregar_zona_plantacion(:codigo_plantacion,

        								:cod_zona,

        								:cod_bloque,

        								:cantidad_acres,

                                		:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

		$stmt->bindParam(":cantidad_acres",  $cantidad_acres);

		$stmt->bindParam(":cod_zona",  $cod_zona);

		$stmt->bindParam(":cod_bloque",  $cod_bloque);

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

	 * Funciones para obtener listado de bloques de una plantación

	 */

	function plan_listado_bloques_plantacion($cod_plantacion)
	{

		$SQL = "SELECT

				    bw_detalle_bloques_plantaciones.cod_detalle,

				    bw_detalle_bloques_plantaciones.cod_plantacion,

				    bw_detalle_bloques_plantaciones.cod_bloque,

				    bw_detalle_bloques_plantaciones.cantidad_acres,

				    bw_detalle_bloques_plantaciones.activo,

				    bw_zonas.zona,

				    bw_bloques.clave_bloque,

				    bw_bloques.nombre_bloque,

				    (SELECT

				            COUNT(bw_plantaciones_semillas_bloques.cod_bloque)

				        FROM

				            bw_plantaciones_semillas_bloques

				                INNER JOIN

				            bw_plantaciones_semillas ON (bw_plantaciones_semillas.cod_detalle = bw_plantaciones_semillas_bloques.cod_detalle

				                AND bw_plantaciones_semillas.cod_plantacion = :cod_plantacion)

				        WHERE

				            bw_plantaciones_semillas_bloques.cod_bloque = bw_detalle_bloques_plantaciones.cod_bloque) as flag_sembrado,

					bw_detalle_bloques_plantaciones.cod_estado_plantacion,

                    bw_estados_plantacion.estado_plantacion,

                    bw_estados_plantacion.estado_plantacion_english,

                    bw_detalle_bloques_plantaciones.motivo_estado_plantacion

				FROM

				    bw_detalle_bloques_plantaciones

				        INNER JOIN

				    bw_bloques ON (bw_bloques.cod_bloque = bw_detalle_bloques_plantaciones.cod_bloque)

				        INNER JOIN

				    bw_zonas ON (bw_zonas.cod_zona = bw_bloques.cod_zona)

						LEFT JOIN

					bw_estados_plantacion on (bw_estados_plantacion.cod_estado_plantacion = bw_detalle_bloques_plantaciones.cod_estado_plantacion)

				WHERE

				    bw_detalle_bloques_plantaciones.cod_plantacion = :cod_plantacion

				    AND bw_detalle_bloques_plantaciones.activo = 1

				ORDER BY cod_bloque ASC;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_plantacion",  $cod_plantacion);

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

      * Cambia el flag de activo de las unidades de medida

      */

	function plan_cambiar_estado_bloque_plantacion(
		$cod_detalle,

		$flag_activo,

		$motivo,

		$user_insert
	) {

		$SQL = "CALL plan_cambiar_estado_bloque_plantacion(:cod_detalle,

										:flag_activo,

										:motivo,

                                		:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_detalle",  $cod_detalle);

		$stmt->bindParam(":flag_activo",  $flag_activo);

		$stmt->bindParam(":motivo",  $motivo);

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

      * Agrega una semilla a los bloques de una plantacion

      */

	function plan_agregar_semilla_plantacion(
		$codigo_plantacion,

		$cod_inventario_semilla,

		$cantidad_usada,

		$descripcion_semilla,

		$user_insert
	) {

		$SQL = "CALL plan_agregar_semilla_plantacion(:codigo_plantacion,

        								:cod_inventario_semilla,

        								:cantidad_usada,

										:descripcion_semilla,

                                		:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

		$stmt->bindParam(":cantidad_usada",  $cantidad_usada);

		$stmt->bindParam(":cod_inventario_semilla",  $cod_inventario_semilla);

		$stmt->bindParam(":descripcion_semilla",  $descripcion_semilla);

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

      * Agrega bloque plantado de una plantación

      */

	function plan_agregar_bloque_semilla_plantacion(
		$cod_detalle,

		$cod_bloque,

		$user_insert
	) {

		$SQL = "CALL plan_agregar_bloque_semilla_plantacion(:cod_detalle,

        								:cod_bloque,

                                		:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_detalle",  $cod_detalle);

		$stmt->bindParam(":cod_bloque",  $cod_bloque);

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

      * Agrega maquinaria usada en plantado de una plantación

      */

	function plan_agregar_maquinaria_semilla_plantacion(
		$cod_detalle,

		$cod_maquinaria,

		$user_insert
	) {

		$SQL = "CALL plan_agregar_maquinaria_semilla_plantacion(:cod_detalle,

        								:cod_maquinaria,

                                		:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_detalle",  $cod_detalle);

		$stmt->bindParam(":cod_maquinaria",  $cod_maquinaria);

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

	 * Funciones para obtener listado de bloques de una plantación

	 */

	function plan_listado_bloques_plantados_plantacion($cod_plantacion)
	{

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

		$SQL = "SELECT

				    bw_plantaciones_semillas.cod_detalle,

				    bw_plantaciones_semillas.cod_plantacion,

				    bw_plantaciones_semillas.cod_inventario_semilla,

				    (SELECT

				            SUM(bw_plantaciones_semillas.cantidad_usada)

				        FROM

				            bw_plantaciones_semillas

				        WHERE

				            cod_plantacion = :cod_plantacion) AS cantidad_usada,

				    bw_plantaciones_semillas.descripcion,

				    bw_plantaciones_semillas.activo,

				    bw_inventario_semilla.nombre_semilla,

				    bw_inventario_semilla.numero_lote,

				    GROUP_CONCAT(DISTINCT CONCAT(bw_zonas.zona,

				                ' - ',

				                CONCAT(IF(bw_bloques.clave_bloque != '',

							            CONCAT(bw_bloques.clave_bloque, '-'),

							            ''),

							        bw_bloques.nombre_bloque),

				                ' (',

				                bw_bloques.num_acres,

				                ' acres)')

				        SEPARATOR '<br>') AS bloques,

				    GROUP_CONCAT(DISTINCT bw_inventario_maquinaria.nombre_maquinaria

				        SEPARATOR '<br>') AS maquinarias

				FROM

				    bw_plantaciones_semillas

				        INNER JOIN

				    bw_plantaciones_semillas_bloques ON (bw_plantaciones_semillas_bloques.cod_detalle = bw_plantaciones_semillas.cod_detalle)

				        INNER JOIN

				    bw_bloques ON (bw_bloques.cod_bloque = bw_plantaciones_semillas_bloques.cod_bloque)

				        INNER JOIN

				    bw_zonas ON (bw_zonas.cod_zona = bw_bloques.cod_zona)

				        INNER JOIN

				    bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_plantaciones_semillas.cod_inventario_semilla)

				        LEFT JOIN

				    bw_plantaciones_semillas_maquinarias ON (bw_plantaciones_semillas_maquinarias.cod_detalle = bw_plantaciones_semillas.cod_detalle)

				        LEFT JOIN

				    bw_inventario_maquinaria ON (bw_inventario_maquinaria.cod_inventario = bw_plantaciones_semillas_maquinarias.cod_maquinaria)

				WHERE

				    bw_plantaciones_semillas.cod_plantacion = :cod_plantacion

				    AND bw_plantaciones_semillas.activo = 1

				GROUP BY bw_plantaciones_semillas.cod_plantacion;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_plantacion",  $cod_plantacion);

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

      * Actualiza acres en una plantación

      */

	function plan_actualizar_acres_plantacion($codigo_plantacion)
	{

		$SQL = "CALL plan_actualizar_acres_plantacion(:codigo_plantacion)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

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

      * Ingresa calibración de un bloque de plantación

      */

	function plan_ingresar_calibracion_plantacion(
		$codigo_detalle,

		$cod_fertilizante,

		$horas_aplicacion_calibrar,

		$observaciones_calibrar,

		$codigo_plantacion,

		$fecha_calibracion,

		$user_insert
	) {

		$SQL = "CALL plan_ingresar_calibracion_plantacion(:codigo_detalle,

										:cod_fertilizante,

										:horas_aplicacion_calibrar,

										:observaciones_calibrar,

										:codigo_plantacion,

										:fecha_calibracion,

										:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_detalle",  $codigo_detalle);

		$stmt->bindParam(":cod_fertilizante",  $cod_fertilizante);

		$stmt->bindParam(":horas_aplicacion_calibrar",  $horas_aplicacion_calibrar);

		$stmt->bindParam(":observaciones_calibrar",  $observaciones_calibrar);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

		$stmt->bindParam(":fecha_calibracion",  $fecha_calibracion);

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

      * Listado de cambios de estados de bloques

      */

	function plan_listado_cambios_estados_bloques($codigo_detalle)
	{

		$SQL = "SELECT

				    bw_detalle_bloques_plantaciones_bitacora.cod_bitacora,

				    bw_detalle_bloques_plantaciones_bitacora.cod_detalle,

				    bw_detalle_bloques_plantaciones_bitacora.cod_plantacion,

				    bw_detalle_bloques_plantaciones_bitacora.cod_bloque,

				    bw_detalle_bloques_plantaciones_bitacora.cantidad_acres,

				    bw_detalle_bloques_plantaciones_bitacora.cod_estado_plantacion,

				    bw_detalle_bloques_plantaciones_bitacora.motivo_estado_plantacion,

				    bw_detalle_bloques_plantaciones_bitacora.activo,

				    bw_detalle_bloques_plantaciones_bitacora.user_update,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS usuario_actualiza,

				    DATE_FORMAT(STR_TO_DATE(bw_detalle_bloques_plantaciones_bitacora.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    bw_detalle_bloques_plantaciones_bitacora.date_update,

				    bw_estados_plantacion.estado_plantacion

				FROM

				    bw_detalle_bloques_plantaciones_bitacora

				        INNER JOIN

				    bw_estados_plantacion ON (bw_estados_plantacion.cod_estado_plantacion = bw_detalle_bloques_plantaciones_bitacora.cod_estado_plantacion)

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_detalle_bloques_plantaciones_bitacora.user_update)

				WHERE

				    bw_detalle_bloques_plantaciones_bitacora.cod_detalle = :codigo_detalle

				ORDER BY bw_detalle_bloques_plantaciones_bitacora.date_update DESC;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_detalle",  $codigo_detalle);

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

      * Listado de calibraciones por bloque

      */

	function plan_listado_calibracion_bloques($codigo_detalle)
	{

		$SQL = "SELECT

				    bw_detalle_bloques_plantaciones_calibraciones.cod_calibracion,

				    bw_detalle_bloques_plantaciones_calibraciones.cod_detalle,

				    bw_detalle_bloques_plantaciones_calibraciones.cod_fertilizante,

				    bw_detalle_bloques_plantaciones_calibraciones.horas_aplicacion_calibrar,

				    bw_detalle_bloques_plantaciones_calibraciones.observaciones_calibrar,

				    bw_detalle_bloques_plantaciones_calibraciones.user_insert,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_ingresa,

				    DATE_FORMAT(STR_TO_DATE(bw_detalle_bloques_plantaciones_calibraciones.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    DATE_FORMAT(STR_TO_DATE(bw_detalle_bloques_plantaciones_calibraciones.fecha_calibracion,'%Y-%m-%d'),'%m-%d-%Y') as fecha_calibracion

				FROM

				    bw_detalle_bloques_plantaciones_calibraciones

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_detalle_bloques_plantaciones_calibraciones.user_insert)

				WHERE

				    bw_detalle_bloques_plantaciones_calibraciones.cod_detalle = :codigo_detalle

				ORDER BY bw_detalle_bloques_plantaciones_calibraciones.date_insert DESC;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_detalle",  $codigo_detalle);

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

      * Listado de observaciones por plantación

      */

	function plan_listado_observaciones_plantacion($codigo_plantacion)
	{

		$SQL = "SELECT

				    bw_plantaciones_observaciones.cod_observacion,

				    bw_plantaciones_observaciones.cod_plantacion,

				    bw_plantaciones_observaciones.observacion,

				    bw_plantaciones_observaciones.activo,

				    bw_plantaciones_observaciones.user_insert,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario,

				    usu_usuarios.fotografia,

				   	bw_plantaciones.cod_estado,

                    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_observaciones.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert

				FROM

				    bw_plantaciones_observaciones

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_plantaciones_observaciones.user_insert)

				    	INNER JOIN

				    bw_plantaciones ON (bw_plantaciones.cod_plantacion = bw_plantaciones_observaciones.cod_plantacion)

				WHERE

				    bw_plantaciones_observaciones.cod_plantacion = :codigo_plantacion

				ORDER BY bw_plantaciones_observaciones.date_insert DESC;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

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

      * Ingresa observacion de una plantación

      */

	function plan_ingresar_observacion_plantacion(
		$codigo_plantacion,

		$nueva_observacion,

		$user_insert
	) {

		$SQL = "CALL plan_ingresar_observacion_plantacion(:codigo_plantacion,

										:nueva_observacion,

										:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

		$stmt->bindParam(":nueva_observacion",  $nueva_observacion);

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

      * Ingresa aplicaciónd de quimico de un bloque de plantación o en la plantación completa

      */

	function plan_guardar_aplicar_quimico(
		$cod_aplicacion_quimico,

		$cod_bloques_aplicar_quimico,

		$fecha_aplicacion_supervisor,

		$cod_tipo_aplicacion,

		$cod_maquinaria,

		$fecha_aplicacion_operador,

		$hora_inicial,

		$hora_final,

		$viento,

		$temperatura,

		$descripcion_aplicar_quimico,

		$codigo_plantacion,

		$cod_operador,

		$user_insert
	) {

		$SQL = "CALL plan_guardar_aplicar_quimico(:cod_aplicacion_quimico,

										:cod_bloques_aplicar_quimico,

										:fecha_aplicacion_supervisor,

										:cod_tipo_aplicacion,

										:cod_maquinaria,

										:fecha_aplicacion_operador,

										:hora_inicial,

										:hora_final,

										:viento,

										:temperatura,

										:descripcion_aplicar_quimico,

										:codigo_plantacion,

										:cod_operador,

										:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_aplicacion_quimico",  $cod_aplicacion_quimico);

		$stmt->bindParam(":cod_tipo_aplicacion",  $cod_tipo_aplicacion);

		$stmt->bindParam(":cod_maquinaria",  $cod_maquinaria);

		$stmt->bindParam(":descripcion_aplicar_quimico",  $descripcion_aplicar_quimico);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

		$stmt->bindParam(":cod_bloques_aplicar_quimico",  $cod_bloques_aplicar_quimico);

		$stmt->bindParam(":fecha_aplicacion_supervisor",  $fecha_aplicacion_supervisor);

		$stmt->bindParam(":fecha_aplicacion_operador",  $fecha_aplicacion_operador);

		$stmt->bindParam(":hora_inicial",  $hora_inicial);

		$stmt->bindParam(":hora_final",  $hora_final);

		$stmt->bindParam(":viento",  $viento);

		$stmt->bindParam(":temperatura",  $temperatura);

		$stmt->bindParam(":cod_operador",  $cod_operador);

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

      * Ingresa aplicaciónd de quimico de un bloque de plantación o en la plantación completa

      */

	function plan_guardar_aplicar_quimico_offline(
		$cod_aplicacion_quimico,

		/*$cod_inventario_quimico,

										$cod_unidad_medida_origen,*/

		//$cod_bloques_aplicar_quimico,

		/*$cantidad_sugerida,

										$cod_unidad_medida,*/

		//$fecha_aplicacion_supervisor,

		$cod_tipo_aplicacion,

		$cod_maquinaria,

		$fecha_aplicacion_operador,

		$hora_inicial,

		$hora_final,

		$viento,

		$temperatura,

		$descripcion_aplicar_quimico,

		$codigo_plantacion,

		$user_insert
	) {

		$SQL = "CALL plan_guardar_aplicar_quimico_offline(:cod_aplicacion_quimico,

										:cod_tipo_aplicacion,

										:cod_maquinaria,

										:fecha_aplicacion_operador,

										:hora_inicial,

										:hora_final,

										:viento,

										:temperatura,

										:descripcion_aplicar_quimico,

										:codigo_plantacion,

										:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_aplicacion_quimico",  $cod_aplicacion_quimico);

		/*$stmt->bindParam(":cod_inventario_quimico",  $cod_inventario_quimico);

        $stmt->bindParam(":cod_unidad_medida_origen",  $cod_unidad_medida_origen);*/

		/*$stmt->bindParam(":cantidad_sugerida",  $cantidad_sugerida);

        $stmt->bindParam(":cod_unidad_medida",  $cod_unidad_medida);*/

		$stmt->bindParam(":cod_tipo_aplicacion",  $cod_tipo_aplicacion);

		$stmt->bindParam(":cod_maquinaria",  $cod_maquinaria);

		$stmt->bindParam(":descripcion_aplicar_quimico",  $descripcion_aplicar_quimico);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

		$stmt->bindParam(":fecha_aplicacion_operador",  $fecha_aplicacion_operador);

		$stmt->bindParam(":hora_inicial",  $hora_inicial);

		$stmt->bindParam(":hora_final",  $hora_final);

		$stmt->bindParam(":viento",  $viento);

		$stmt->bindParam(":temperatura",  $temperatura);

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

      * Listado de aplicaciones de quimicos por bloque

      */

	function plan_listado_formularios_limpieza_bloques($codigo_detalle)
	{

		$SQL = "SELECT 

				    bw_formulario_cleaning_sanitizing.cod_formulario,

				    bw_formulario_cleaning_sanitizing.cod_detalle,

				    DATE_FORMAT(STR_TO_DATE(bw_formulario_cleaning_sanitizing.fecha_limpieza,'%Y-%m-%d'),'%m-%d-%Y') as fecha_limpieza,

				    bw_formulario_cleaning_sanitizing.vez_limpieza,

				    bw_formulario_cleaning_sanitizing.equipo_limpieza,

				    bw_formulario_cleaning_sanitizing.cleaning_tools,

				    bw_formulario_cleaning_sanitizing.cleaning_potable_water,

				    bw_formulario_cleaning_sanitizing.cleaning_detergent,

				    bw_formulario_cleaning_sanitizing.scrubbing,

				    bw_formulario_cleaning_sanitizing.rinse_potable_water,

				    bw_formulario_cleaning_sanitizing.sanitizing_chlorine,

				    bw_formulario_cleaning_sanitizing.post_sanitizing,

				    bw_formulario_cleaning_sanitizing.activo,

				    bw_formulario_cleaning_sanitizing.user_insert,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario,

				    DATE_FORMAT(STR_TO_DATE(bw_formulario_cleaning_sanitizing.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert

				FROM

				    bw_formulario_cleaning_sanitizing

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_formulario_cleaning_sanitizing.user_insert)

				WHERE

				    bw_formulario_cleaning_sanitizing.cod_detalle = :codigo_detalle

				ORDER BY bw_formulario_cleaning_sanitizing.date_insert DESC;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_detalle",  $codigo_detalle);

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

      * Listado de aplicaciones de quimicos por bloque

      */

	function plan_listado_formularios_harvesting_worksheet_bloques($codigo_detalle)
	{

		$SQL = "SELECT 

				    bw_formulario_harvesting_worksheet.cod_formulario,

				    bw_formulario_harvesting_worksheet.cod_detalle,

				    bw_formulario_harvesting_worksheet.harvest_date,

				    bw_formulario_harvesting_worksheet.phi,

				    bw_formulario_harvesting_worksheet.cellos,

				    bw_formulario_harvesting_worksheet.increment_bunch_cello,

				    bw_formulario_harvesting_worksheet.area_finished,

				    bw_formulario_harvesting_worksheet.acres_harvested,

				    bw_formulario_harvesting_worksheet.commments_harvesting_worksheet,

				    bw_formulario_harvesting_worksheet.orden_compra,

				    bw_formulario_harvesting_worksheet.cantidad_cosechada,

				    bw_formulario_harvesting_worksheet.activo,

				    bw_formulario_harvesting_worksheet.user_insert,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario,

				    DATE_FORMAT(STR_TO_DATE(bw_formulario_harvesting_worksheet.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert

				FROM

				    bw_formulario_harvesting_worksheet

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_formulario_harvesting_worksheet.user_insert)

				WHERE

				    bw_formulario_harvesting_worksheet.cod_detalle = :codigo_detalle

				    AND bw_formulario_harvesting_worksheet.activo = 1

				ORDER BY bw_formulario_harvesting_worksheet.date_insert DESC;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_detalle",  $codigo_detalle);

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

      * Listado de aplicaciones de quimicos por bloque

      */

	function plan_listado_formularios_harvesting_checklist_bloques($codigo_detalle)
	{

		$SQL = "SELECT 

				    bw_formulario_harvesting_checklist.cod_formulario,

				    bw_formulario_harvesting_checklist.cod_detalle,

				    DATE_FORMAT(STR_TO_DATE(bw_formulario_harvesting_checklist.fecha_checklist,'%Y-%m-%d'),'%m-%d-%Y') as fecha_checklist,

				    bw_formulario_harvesting_checklist.loose_bunches,

				    bw_formulario_harvesting_checklist.conventional_organic,

				    bw_formulario_harvesting_checklist.question1,

				    bw_formulario_harvesting_checklist.question2,

				    bw_formulario_harvesting_checklist.question3,

				    bw_formulario_harvesting_checklist.question4,

				    bw_formulario_harvesting_checklist.question5,

				    bw_formulario_harvesting_checklist.question6,

				    bw_formulario_harvesting_checklist.question7,

				    bw_formulario_harvesting_checklist.question8,

				    bw_formulario_harvesting_checklist.question9,

				    bw_formulario_harvesting_checklist.question10,

				    bw_formulario_harvesting_checklist.question11,

				    bw_formulario_harvesting_checklist.question12,

				    bw_formulario_harvesting_checklist.question13,

				    bw_formulario_harvesting_checklist.question14,

				    bw_formulario_harvesting_checklist.question15,

				    bw_formulario_harvesting_checklist.question16,

				    bw_formulario_harvesting_checklist.question17,

				    bw_formulario_harvesting_checklist.question18,

				    bw_formulario_harvesting_checklist.question19,

				    bw_formulario_harvesting_checklist.question20,

				    bw_formulario_harvesting_checklist.question21,

				    bw_formulario_harvesting_checklist.question22,

				    bw_formulario_harvesting_checklist.actions,

				    bw_formulario_harvesting_checklist.activo,

				    bw_formulario_harvesting_checklist.user_insert,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario,

				    DATE_FORMAT(STR_TO_DATE(bw_formulario_harvesting_checklist.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert

				FROM

				    bw_formulario_harvesting_checklist

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_formulario_harvesting_checklist.user_insert)

				WHERE

				    bw_formulario_harvesting_checklist.cod_detalle = :codigo_detalle

				ORDER BY bw_formulario_harvesting_checklist.date_insert DESC;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_detalle",  $codigo_detalle);

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

      * Listado de aplicaciones de quimicos por bloque

      */

	function plan_listado_trasplantes_bloques($codigo_detalle)
	{

		$SQL = "SELECT DISTINCT

				    bw_plantaciones_trasplantes.cod_trasplante,

				    bw_plantaciones_trasplantes.cod_plantacion_envia,

				    empresa_envia.nombre_empresa AS nombre_empresa_envia,

				    plantacion_envia.anio_plantacion AS anio_plantacion_envia,

				    plantacion_envia.num_plantacion AS num_plantacion_envia,

				    temporada_envia.codigo_temporada AS codigo_temporada_envia,

				    bw_plantaciones_trasplantes.cod_plantacion_recibe,

				    empresa_recibe.nombre_empresa AS nombre_empresa_recibe,

				    plantacion_recibe.anio_plantacion AS anio_plantacion_recibe,

				    plantacion_recibe.num_plantacion AS num_plantacion_recibe,

				    temporada_recibe.codigo_temporada AS codigo_temporada_recibe,

				    bw_plantaciones_trasplantes.cod_bloques,

				    GROUP_CONCAT(CONCAT(bw_zonas.zona,

				                '-',

				                CONCAT(IF(bw_bloques.clave_bloque != '',

						            CONCAT(bw_bloques.clave_bloque, '-'),

						            ''),

						        bw_bloques.nombre_bloque))

				        SEPARATOR '</br>') AS bloques,

				    bw_plantaciones_trasplantes.cantidad,

				    bw_plantaciones_trasplantes.cod_bloques_trasplante,

				    (SELECT 

				            GROUP_CONCAT(CONCAT(zonas_trasplante.zona,

				                            '-',

				                            CONCAT(IF(bloques_trasplante.clave_bloque != '',

									            CONCAT(bloques_trasplante.clave_bloque, '-'),

									            ''),

									        bloques_trasplante.nombre_bloque))

				                    SEPARATOR '</br>')

				        FROM

				            bw_plantaciones_trasplantes p

				                LEFT JOIN

				            bw_bloques bloques_trasplante ON (FIND_IN_SET(bloques_trasplante.cod_bloque,

				                    p.cod_bloques_trasplante))

				                LEFT JOIN

				            bw_zonas zonas_trasplante ON (zonas_trasplante.cod_zona = bloques_trasplante.cod_zona)

				        WHERE

				            p.cod_trasplante = bw_plantaciones_trasplantes.cod_trasplante) AS bloques_trasplante,

				    bw_plantaciones_trasplantes.observacion,

				    bw_plantaciones_trasplantes.activo,

				    bw_plantaciones_trasplantes.user_insert,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_trasplantes.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert

				FROM

				    bw_plantaciones_trasplantes

				        INNER JOIN

				    bw_plantaciones plantacion_envia ON (plantacion_envia.cod_plantacion = bw_plantaciones_trasplantes.cod_plantacion_envia)

				        INNER JOIN

				    bw_info_empresa empresa_envia ON (empresa_envia.cod_info_empresa = plantacion_envia.cod_info_empresa)

				        INNER JOIN

				    bw_temporadas temporada_envia ON (temporada_envia.cod_temporada = plantacion_envia.cod_temporada)

				        INNER JOIN

				    bw_plantaciones plantacion_recibe ON (plantacion_recibe.cod_plantacion = bw_plantaciones_trasplantes.cod_plantacion_recibe)

				        INNER JOIN

				    bw_info_empresa empresa_recibe ON (empresa_recibe.cod_info_empresa = plantacion_recibe.cod_info_empresa)

				        INNER JOIN

				    bw_temporadas temporada_recibe ON (temporada_recibe.cod_temporada = plantacion_recibe.cod_temporada)

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_plantaciones_trasplantes.user_insert)

				        INNER JOIN

				    bw_bloques ON (FIND_IN_SET(bw_bloques.cod_bloque,

				            bw_plantaciones_trasplantes.cod_bloques))

				        INNER JOIN

				    bw_zonas ON (bw_zonas.cod_zona = bw_bloques.cod_zona)

				        INNER JOIN

				    bw_detalle_bloques_plantaciones ON (FIND_IN_SET(bw_detalle_bloques_plantaciones.cod_bloque,

				            bw_plantaciones_trasplantes.cod_bloques_trasplante)

				        OR FIND_IN_SET(bw_detalle_bloques_plantaciones.cod_bloque,

				            bw_plantaciones_trasplantes.cod_bloques))

				WHERE

				    bw_detalle_bloques_plantaciones.cod_detalle = :codigo_detalle

				GROUP BY bw_plantaciones_trasplantes.cod_trasplante

				ORDER BY bw_plantaciones_trasplantes.date_insert DESC;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_detalle",  $codigo_detalle);

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

      * Listado de aplicaciones de quimicos por plantacion

      */

	function plan_listado_formularios_limpieza_plantacion($codigo_plantacion)
	{

		$SQL = "SELECT 

				    bw_formulario_cleaning_sanitizing.cod_formulario,

				    bw_formulario_cleaning_sanitizing.cod_plantacion,

				    DATE_FORMAT(STR_TO_DATE(bw_formulario_cleaning_sanitizing.fecha_limpieza,'%Y-%m-%d'),'%m-%d-%Y') as fecha_limpieza,

				    bw_formulario_cleaning_sanitizing.vez_limpieza,

				    bw_formulario_cleaning_sanitizing.equipo_limpieza,

				    bw_formulario_cleaning_sanitizing.cleaning_tools,

				    bw_formulario_cleaning_sanitizing.cleaning_potable_water,

				    bw_formulario_cleaning_sanitizing.cleaning_detergent,

				    bw_formulario_cleaning_sanitizing.scrubbing,

				    bw_formulario_cleaning_sanitizing.rinse_potable_water,

				    bw_formulario_cleaning_sanitizing.sanitizing_chlorine,

				    bw_formulario_cleaning_sanitizing.post_sanitizing,

				    bw_formulario_cleaning_sanitizing.activo,

				    bw_formulario_cleaning_sanitizing.user_insert,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario,

				    DATE_FORMAT(STR_TO_DATE(bw_formulario_cleaning_sanitizing.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert

				FROM

				    bw_formulario_cleaning_sanitizing

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_formulario_cleaning_sanitizing.user_insert)

				WHERE

				    bw_formulario_cleaning_sanitizing.cod_plantacion = :codigo_plantacion

				ORDER BY bw_formulario_cleaning_sanitizing.date_insert DESC;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

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

      * Listado de aplicaciones de quimicos por plantacion

      */

	function plan_listado_formularios_harvesting_worksheet_plantacion($codigo_plantacion)
	{

		$SQL = "SELECT 

				    bw_formulario_harvesting_worksheet.cod_formulario,

				    bw_formulario_harvesting_worksheet.cod_plantacion,

				    DATE_FORMAT(STR_TO_DATE(bw_formulario_harvesting_worksheet.harvest_date,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as harvest_date,

				    bw_formulario_harvesting_worksheet.phi,

				    bw_formulario_harvesting_worksheet.cellos,

				    bw_formulario_harvesting_worksheet.increment_bunch_cello,

				    bw_formulario_harvesting_worksheet.area_finished,

				    bw_formulario_harvesting_worksheet.acres_harvested,

				    bw_formulario_harvesting_worksheet.commments_harvesting_worksheet,

				    bw_formulario_harvesting_worksheet.orden_compra,

				    bw_formulario_harvesting_worksheet.crop_number,

				    bw_formulario_harvesting_worksheet.cantidad_cosechada,

				    bw_formulario_harvesting_worksheet.activo,

				    bw_formulario_harvesting_worksheet.user_insert,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario,

				    DATE_FORMAT(STR_TO_DATE(bw_formulario_harvesting_worksheet.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert

				FROM

				    bw_formulario_harvesting_worksheet

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_formulario_harvesting_worksheet.user_insert)

				WHERE

				    bw_formulario_harvesting_worksheet.cod_plantacion = :codigo_plantacion

				    AND bw_formulario_harvesting_worksheet.activo = 1

				ORDER BY bw_formulario_harvesting_worksheet.date_insert DESC;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

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

      * Listado de aplicaciones de quimicos por plantacion

      */

	function plan_listado_formularios_harvesting_checklist_plantacion($codigo_plantacion)
	{

		$SQL = "SELECT 

				    bw_formulario_harvesting_checklist.cod_formulario,

				    bw_formulario_harvesting_checklist.cod_plantacion,

				    DATE_FORMAT(STR_TO_DATE(bw_formulario_harvesting_checklist.fecha_checklist,'%Y-%m-%d'),'%m-%d-%Y') as fecha_checklist,

				    bw_formulario_harvesting_checklist.loose_bunches,

				    bw_formulario_harvesting_checklist.conventional_organic,

				    bw_formulario_harvesting_checklist.question1,

				    bw_formulario_harvesting_checklist.question2,

				    bw_formulario_harvesting_checklist.question3,

				    bw_formulario_harvesting_checklist.question4,

				    bw_formulario_harvesting_checklist.question5,

				    bw_formulario_harvesting_checklist.question6,

				    bw_formulario_harvesting_checklist.question7,

				    bw_formulario_harvesting_checklist.question8,

				    bw_formulario_harvesting_checklist.question9,

				    bw_formulario_harvesting_checklist.question10,

				    bw_formulario_harvesting_checklist.question11,

				    bw_formulario_harvesting_checklist.question12,

				    bw_formulario_harvesting_checklist.question13,

				    bw_formulario_harvesting_checklist.question14,

				    bw_formulario_harvesting_checklist.question15,

				    bw_formulario_harvesting_checklist.question16,

				    bw_formulario_harvesting_checklist.question17,

				    bw_formulario_harvesting_checklist.question18,

				    bw_formulario_harvesting_checklist.question19,

				    bw_formulario_harvesting_checklist.question20,

				    bw_formulario_harvesting_checklist.question21,

				    bw_formulario_harvesting_checklist.question22,

				    bw_formulario_harvesting_checklist.actions,

				    bw_formulario_harvesting_checklist.activo,

				    bw_formulario_harvesting_checklist.user_insert,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario,

				    DATE_FORMAT(STR_TO_DATE(bw_formulario_harvesting_checklist.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert

				FROM

				    bw_formulario_harvesting_checklist

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_formulario_harvesting_checklist.user_insert)

				WHERE

				    bw_formulario_harvesting_checklist.cod_plantacion = :codigo_plantacion

				ORDER BY bw_formulario_harvesting_checklist.date_insert DESC;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

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

      * Listado de aplicaciones de quimicos por plantación
AQUI
      */

	function plan_listado_aplicaciones_quimicos_plantacion($codigo_plantacion)
	{

		$SQL = "SELECT 

				    bw_plantaciones_aplicar_quimicos.cod_aplicacion,

				    bw_plantaciones_aplicar_quimicos.cod_plantacion,

				    bw_plantaciones_aplicar_quimicos.cod_bloques_aplicacion,

				    (SELECT 

				            GROUP_CONCAT(CONCAT(bw_zonas.zona,

				                            '-',

				                            CONCAT(IF(bw_bloques.clave_bloque != '',

									            CONCAT(bw_bloques.clave_bloque, '-'),

									            ''),

									        bw_bloques.nombre_bloque))

				                    SEPARATOR '<br>')

				        FROM

				            bw_bloques

				                INNER JOIN

				            bw_zonas ON (bw_zonas.cod_zona = bw_bloques.cod_zona)

				        WHERE

				            FIND_IN_SET(bw_bloques.cod_bloque,

				                    bw_plantaciones_aplicar_quimicos.cod_bloques_aplicacion)) AS bloques,

				    bw_plantaciones_aplicar_quimicos.cod_inventario_maquinaria,

				    bw_inventario_maquinaria.nombre_maquinaria,

				    CONCAT(user_supervisor.nombre_1,

				            ' ',

				            user_supervisor.apellido_1) AS usuario_supervisor,

				    CONCAT(user_operador.nombre_1,

				            ' ',

				            user_operador.apellido_1) AS usuario_operador,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_aplicar_quimicos.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_aplicar_quimicos.fecha_aplicacion_supervisor,'%Y-%m-%d'),'%m-%d-%Y') as fecha_aplicacion_supervisor,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_aplicar_quimicos.fecha_aplicacion_operador,'%Y-%m-%d'),'%m-%d-%Y') as fecha_aplicacion_operador,

				    bw_plantaciones_aplicar_quimicos.hora_inicial,

				    bw_plantaciones_aplicar_quimicos.hora_final,

				    bw_plantaciones_aplicar_quimicos.viento,

				    bw_plantaciones_aplicar_quimicos.temperatura,

				    bw_plantaciones_aplicar_quimicos.cod_tipo_aplicacion,

				    bw_plantaciones_aplicar_quimicos.descripcion_aplicar_quimico,

				    bw_plantaciones_aplicar_quimicos.activo,

				    bw_plantaciones_aplicar_quimicos.user_insert,

				    bw_plantaciones_aplicar_quimicos.user_update,
				    
					(SELECT 
						CONCAT(nombre_1, ' ', apellido_1)
				        FROM
						usu_usuarios
				        WHERE
						bw_plantaciones_aplicar_quimicos.user_update = usu_usuarios.cod_usuario) AS usuario_actualizacion,

					DATE_FORMAT(STR_TO_DATE(bw_plantaciones_aplicar_quimicos.updated_at,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s')  AS actualizacion,
					
				    (SELECT 

				            COUNT(bw_plantaciones_detalle_aplicar_quimicos.cod_detalle)

				        FROM

				            bw_plantaciones_detalle_aplicar_quimicos

				        WHERE

				            bw_plantaciones_detalle_aplicar_quimicos.cod_aplicacion = bw_plantaciones_aplicar_quimicos.cod_aplicacion

				            AND bw_plantaciones_detalle_aplicar_quimicos.activo = 1) AS contador_quimicos,

				    (SELECT 

				            GROUP_CONCAT(bw_inventario_quimicos.nombre_quimico SEPARATOR '<br>')

				        FROM

				            bw_plantaciones_detalle_aplicar_quimicos

				            INNER JOIN

				            bw_inventario_quimicos ON(bw_inventario_quimicos.cod_inventario = bw_plantaciones_detalle_aplicar_quimicos.cod_inventario)

				        WHERE

				            bw_plantaciones_detalle_aplicar_quimicos.cod_aplicacion = bw_plantaciones_aplicar_quimicos.cod_aplicacion

				            AND bw_plantaciones_detalle_aplicar_quimicos.activo = 1) AS nombre_quimicos,

				    (SELECT 

				            GROUP_CONCAT(bw_plantaciones_detalle_aplicar_quimicos.cantidad_aplicada SEPARATOR '<br>')

				        FROM

				            bw_plantaciones_detalle_aplicar_quimicos

				        WHERE

				            bw_plantaciones_detalle_aplicar_quimicos.cod_aplicacion = bw_plantaciones_aplicar_quimicos.cod_aplicacion

				            AND bw_plantaciones_detalle_aplicar_quimicos.activo = 1) AS cantidad_quimicos

				FROM

				    bw_plantaciones_aplicar_quimicos

				        INNER JOIN

				    bw_plantaciones ON (bw_plantaciones.cod_plantacion = bw_plantaciones_aplicar_quimicos.cod_plantacion)

				        LEFT JOIN

				    bw_inventario_maquinaria ON (bw_inventario_maquinaria.cod_inventario = bw_plantaciones_aplicar_quimicos.cod_inventario_maquinaria)

				        INNER JOIN

				    usu_usuarios user_supervisor ON (user_supervisor.cod_usuario = bw_plantaciones_aplicar_quimicos.user_insert)

				        LEFT JOIN

				    usu_usuarios user_operador ON (user_operador.cod_usuario = bw_plantaciones_aplicar_quimicos.cod_operador)

				WHERE

				    bw_plantaciones_aplicar_quimicos.cod_plantacion = :codigo_plantacion

				    AND bw_plantaciones_aplicar_quimicos.activo = 1

				ORDER BY bw_plantaciones_aplicar_quimicos.date_insert DESC;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

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

      * Obtiene información de una aplicación de químicos por su código

      */

	function plan_obtener_info_aplicacion_quimicos($cod_aplicacion)
	{

		$SQL = "SELECT 

				    bw_plantaciones_aplicar_quimicos.cod_aplicacion,

				    bw_plantaciones_aplicar_quimicos.cod_plantacion,

				    bw_plantaciones_aplicar_quimicos.cod_bloques_aplicacion,

				    (SELECT 

				            GROUP_CONCAT(CONCAT(bw_zonas.zona,

				                            '-',

				                            CONCAT(IF(bw_bloques.clave_bloque != '',

									            CONCAT(bw_bloques.clave_bloque, '-'),

									            ''),

									        bw_bloques.nombre_bloque))

				                    SEPARATOR '<br>')

				        FROM

				            bw_bloques

				                INNER JOIN

				            bw_zonas ON (bw_zonas.cod_zona = bw_bloques.cod_zona)

				        WHERE

				            FIND_IN_SET(bw_bloques.cod_bloque,

                    				bw_plantaciones_aplicar_quimicos.cod_bloques_aplicacion)) AS bloques,

				    bw_plantaciones_aplicar_quimicos.cod_inventario_maquinaria,

				    bw_inventario_maquinaria.nombre_maquinaria,

				    CONCAT(user_supervisor.nombre_1,

				            ' ',

				            user_supervisor.apellido_1) AS usuario_supervisor,

				    CONCAT(user_operador.nombre_1,

				            ' ',

				            user_operador.apellido_1) AS usuario_operador,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_aplicar_quimicos.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_aplicar_quimicos.fecha_aplicacion_supervisor,'%Y-%m-%d'),'%m-%d-%Y') as fecha_aplicacion_supervisor,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_aplicar_quimicos.fecha_aplicacion_operador,'%Y-%m-%d'),'%m-%d-%Y') as fecha_aplicacion_operador,

				    bw_plantaciones_aplicar_quimicos.hora_inicial,

				    bw_plantaciones_aplicar_quimicos.hora_final,

				    bw_plantaciones_aplicar_quimicos.viento,

				    bw_plantaciones_aplicar_quimicos.temperatura,

    				bw_plantaciones_aplicar_quimicos.cod_tipo_aplicacion,

				    bw_plantaciones_aplicar_quimicos.descripcion_aplicar_quimico,

				    bw_plantaciones_aplicar_quimicos.activo,

				    bw_plantaciones_aplicar_quimicos.user_insert,

				    bw_plantaciones_aplicar_quimicos.cod_operador,

				    (SELECT 

				            COUNT(bw_plantaciones_detalle_aplicar_quimicos.cod_detalle)

				        FROM

				            bw_plantaciones_detalle_aplicar_quimicos

				        WHERE

				            bw_plantaciones_detalle_aplicar_quimicos.cod_aplicacion = bw_plantaciones_aplicar_quimicos.cod_aplicacion

				            AND bw_plantaciones_detalle_aplicar_quimicos.activo = 1) AS contador_quimicos

				FROM

				    bw_plantaciones_aplicar_quimicos

				        INNER JOIN

				    bw_plantaciones ON (bw_plantaciones.cod_plantacion = bw_plantaciones_aplicar_quimicos.cod_plantacion)

				        LEFT JOIN

				    bw_inventario_maquinaria ON (bw_inventario_maquinaria.cod_inventario = bw_plantaciones_aplicar_quimicos.cod_inventario_maquinaria)

				        INNER JOIN

				    usu_usuarios user_supervisor ON (user_supervisor.cod_usuario = bw_plantaciones_aplicar_quimicos.user_insert)

				        LEFT JOIN

				    usu_usuarios user_operador ON (user_operador.cod_usuario = bw_plantaciones_aplicar_quimicos.cod_operador)

				WHERE

				    bw_plantaciones_aplicar_quimicos.cod_aplicacion = :cod_aplicacion

				    AND bw_plantaciones_aplicar_quimicos.activo = 1;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_aplicacion",  $cod_aplicacion);

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

      * Obtiene información de una aplicación de químicos por su código

      */

	function plan_obtener_info_harvesting_worksheet($cod_formulario)
	{

		$SQL = "SELECT 

				    bw_formulario_harvesting_worksheet.cod_formulario,

				    bw_formulario_harvesting_worksheet.cod_detalle,

				    bw_formulario_harvesting_worksheet.cod_plantacion,

				    DATE_FORMAT(STR_TO_DATE(bw_formulario_harvesting_worksheet.harvest_date,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as harvest_date,

				    bw_formulario_harvesting_worksheet.phi,

				    bw_formulario_harvesting_worksheet.cellos,

				    bw_formulario_harvesting_worksheet.increment_bunch_cello,

				    bw_formulario_harvesting_worksheet.area_finished,

				    bw_formulario_harvesting_worksheet.acres_harvested,

				    bw_formulario_harvesting_worksheet.commments_harvesting_worksheet,

				    bw_formulario_harvesting_worksheet.orden_compra,

				    bw_formulario_harvesting_worksheet.crop_number,

				    bw_formulario_harvesting_worksheet.cantidad_cosechada,

				    bw_formulario_harvesting_worksheet.cantidad_empacada,

				    DATE_FORMAT(STR_TO_DATE(bw_formulario_harvesting_worksheet.date_packed,'%Y-%m-%d'),'%m-%d-%Y') as date_packed,

				    bw_formulario_harvesting_worksheet.totes_harvested,

				    bw_formulario_harvesting_worksheet.totes_packed,

				    bw_formulario_harvesting_worksheet.activo,

				    bw_formulario_harvesting_worksheet.user_insert,

				    DATE_FORMAT(STR_TO_DATE(bw_formulario_harvesting_worksheet.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert

				FROM

				    bw_formulario_harvesting_worksheet

				WHERE

				    bw_formulario_harvesting_worksheet.cod_formulario = :cod_formulario;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_formulario",  $cod_formulario);

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

      * Listado de calibraciones por plantacion

      */

	function plan_listado_calibracion_plantacion($codigo_plantacion)
	{

		$SQL = "SELECT

				    bw_plantaciones_calibraciones.cod_calibracion,

				    bw_plantaciones_calibraciones.cod_plantacion,

				    bw_plantaciones_calibraciones.cod_fertilizante,

				    bw_plantaciones_calibraciones.horas_aplicacion_calibrar,

				    bw_plantaciones_calibraciones.observaciones_calibrar,

				    bw_plantaciones_calibraciones.user_insert,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_ingresa,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_calibraciones.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_calibraciones.fecha_calibracion,'%Y-%m-%d'),'%m-%d-%Y') as fecha_calibracion

				FROM

				    bw_plantaciones_calibraciones

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_plantaciones_calibraciones.user_insert)

				WHERE

				    bw_plantaciones_calibraciones.cod_plantacion = :codigo_plantacion

				ORDER BY bw_plantaciones_calibraciones.date_insert DESC;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

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

      * Listado de calibraciones por plantacion

      */

	function plan_listado_cambios_plantacion($codigo_plantacion)
	{

		$SQL = "SELECT DISTINCT

				    bw_plantaciones_bitacora.cod_estado,

				    bw_plantaciones_bitacora.user_insert,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_bitacora.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    bw_plantaciones_bitacora.user_update,

				    bw_plantaciones_bitacora.date_update,

				    bw_estados_plantacion.estado_plantacion,

				    bw_estados_plantacion.estado_plantacion_english,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario

				FROM

				    bw_plantaciones_bitacora

				        INNER JOIN

				    bw_estados_plantacion ON (bw_estados_plantacion.cod_estado_plantacion = bw_plantaciones_bitacora.cod_estado)

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_plantaciones_bitacora.user_update)

				WHERE

				    bw_plantaciones_bitacora.cod_plantacion = :codigo_plantacion

				ORDER BY bw_plantaciones_bitacora.date_update DESC;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

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

      * Ingresa formulario de exploracion de un bloque de plantación o en la plantación completa

      */

	function plan_guardar_exploracion(
		$codigo_detalle,

		$etapa_crecimiento,

		$worms,

		$eggs,

		$leafhoppers,

		$aphids,

		$stink_bugs,

		$gnats,

		$flea_beetles,

		$cyclaman_mites,

		$cercospora_leaf_spot,

		$pythium,

		$rhizoctonia_aerial_blight,

		$bacteria,

		$sclerotinia,

		$alternaria_specks,

		$mildew,

		$virus,

		$dollarweed,

		$frogs_bit,

		$mud_plantain,

		$tube_weed,

		$grass,

		$damaged_leaves,

		$purple_stem,

		$watercress_rooter,

		$observaciones_exploracion,

		$codigo_plantacion,

		$fecha_exploracion,

		$spidermites,

		$white_rust,

		$salt_accumulation,

		$nutsedge,

		$buds,

		$zigzag_stems,

		$nutrient_deficiency,

		$round_up,

		$light_color,

		$mealybugs,

		$thrips,

		$user_insert
	) {

		$SQL = "CALL plan_guardar_exploracion(:codigo_detalle,

										:etapa_crecimiento,

										:worms,

										:eggs,

										:leafhoppers,

										:aphids,

										:stink_bugs,

										:gnats,

										:flea_beetles,

										:cyclaman_mites,

										:cercospora_leaf_spot,

										:pythium,

										:rhizoctonia_aerial_blight,

										:bacteria,

										:sclerotinia,

										:alternaria_specks,

										:mildew,

										:virus,

										:dollarweed,

										:frogs_bit,

										:mud_plantain,

										:tube_weed,

										:grass,

										:damaged_leaves,

										:purple_stem,

										:watercress_rooter,

										:observaciones_exploracion,

										:codigo_plantacion,

										:fecha_exploracion,

										:spidermites,

										:white_rust,

										:salt_accumulation,

										:nutsedge,

										:buds,

										:zigzag_stems,

										:nutrient_deficiency,

										:round_up,

										:light_color,

										:mealybugs,

										:thrips,

										:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_detalle",  $codigo_detalle);

		$stmt->bindParam(":etapa_crecimiento",  $etapa_crecimiento);

		$stmt->bindParam(":worms",  $worms);

		$stmt->bindParam(":eggs",  $eggs);

		$stmt->bindParam(":leafhoppers",  $leafhoppers);

		$stmt->bindParam(":aphids",  $aphids);

		$stmt->bindParam(":stink_bugs",  $stink_bugs);

		$stmt->bindParam(":gnats",  $gnats);

		$stmt->bindParam(":flea_beetles",  $flea_beetles);

		$stmt->bindParam(":cyclaman_mites",  $cyclaman_mites);

		$stmt->bindParam(":cercospora_leaf_spot",  $cercospora_leaf_spot);

		$stmt->bindParam(":pythium",  $pythium);

		$stmt->bindParam(":rhizoctonia_aerial_blight",  $rhizoctonia_aerial_blight);

		$stmt->bindParam(":bacteria",  $bacteria);

		$stmt->bindParam(":sclerotinia",  $sclerotinia);

		$stmt->bindParam(":alternaria_specks",  $alternaria_specks);

		$stmt->bindParam(":mildew",  $mildew);

		$stmt->bindParam(":virus",  $virus);

		$stmt->bindParam(":dollarweed",  $dollarweed);

		$stmt->bindParam(":frogs_bit",  $frogs_bit);

		$stmt->bindParam(":mud_plantain",  $mud_plantain);

		$stmt->bindParam(":tube_weed",  $tube_weed);

		$stmt->bindParam(":grass",  $grass);

		$stmt->bindParam(":damaged_leaves",  $damaged_leaves);

		$stmt->bindParam(":purple_stem",  $purple_stem);

		$stmt->bindParam(":watercress_rooter",  $watercress_rooter);

		$stmt->bindParam(":observaciones_exploracion",  $observaciones_exploracion);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

		$stmt->bindParam(":fecha_exploracion",  $fecha_exploracion);

		$stmt->bindParam(":spidermites",  $spidermites);

		$stmt->bindParam(":white_rust",  $white_rust);

		$stmt->bindParam(":salt_accumulation",  $salt_accumulation);

		$stmt->bindParam(":nutsedge",  $nutsedge);

		$stmt->bindParam(":buds",  $buds);

		$stmt->bindParam(":zigzag_stems",  $zigzag_stems);

		$stmt->bindParam(":nutrient_deficiency",  $nutrient_deficiency);

		$stmt->bindParam(":round_up",  $round_up);

		$stmt->bindParam(":light_color",  $light_color);

		$stmt->bindParam(":mealybugs",  $mealybugs);

		$stmt->bindParam(":thrips",  $thrips);

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

      * Listado de exploraciones por bloque

      */

	function plan_listado_exploraciones_bloques($codigo_detalle)
	{

		$SQL = "SELECT

				    bw_detalle_bloques_plantaciones_exploracion.cod_exploracion,

				    bw_detalle_bloques_plantaciones_exploracion.cod_detalle,

				    bw_detalle_bloques_plantaciones_exploracion.etapa_crecimiento,

				    bw_detalle_bloques_plantaciones_exploracion.gusanos,

				    bw_detalle_bloques_plantaciones_exploracion.huevos,

				    bw_detalle_bloques_plantaciones_exploracion.saltahojas,

				    bw_detalle_bloques_plantaciones_exploracion.afidos,

				    bw_detalle_bloques_plantaciones_exploracion.chinches,

				    bw_detalle_bloques_plantaciones_exploracion.moscos,

				    bw_detalle_bloques_plantaciones_exploracion.escarabajos,

				    bw_detalle_bloques_plantaciones_exploracion.acaros,

				    bw_detalle_bloques_plantaciones_exploracion.cercospora_leaf_spot,

				    bw_detalle_bloques_plantaciones_exploracion.pythium,

				    bw_detalle_bloques_plantaciones_exploracion.rhizoctonia,

				    bw_detalle_bloques_plantaciones_exploracion.bacteria,

				    bw_detalle_bloques_plantaciones_exploracion.sclerotinia,

				    bw_detalle_bloques_plantaciones_exploracion.alternaria_specks,

				    bw_detalle_bloques_plantaciones_exploracion.mildew,

				    bw_detalle_bloques_plantaciones_exploracion.virus,

				    bw_detalle_bloques_plantaciones_exploracion.dolar,

				    bw_detalle_bloques_plantaciones_exploracion.frogs_bit,

				    bw_detalle_bloques_plantaciones_exploracion.plantas_lodo,

				    bw_detalle_bloques_plantaciones_exploracion.tripa_pollo,

				    bw_detalle_bloques_plantaciones_exploracion.zacate,

				    bw_detalle_bloques_plantaciones_exploracion.hojas_danadas,

				    bw_detalle_bloques_plantaciones_exploracion.tallos_purpuras,

				    bw_detalle_bloques_plantaciones_exploracion.berro_enraizado,

				    bw_detalle_bloques_plantaciones_exploracion.observacion,

				    bw_detalle_bloques_plantaciones_exploracion.activo,

				    bw_detalle_bloques_plantaciones_exploracion.user_insert,

				    DATE_FORMAT(STR_TO_DATE(bw_detalle_bloques_plantaciones_exploracion.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    DATE_FORMAT(STR_TO_DATE(bw_detalle_bloques_plantaciones_exploracion.fecha_exploracion,'%Y-%m-%d'),'%m-%d-%Y') as fecha_exploracion,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario

				FROM

				    bw_detalle_bloques_plantaciones_exploracion

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_detalle_bloques_plantaciones_exploracion.user_insert)

				WHERE

				    bw_detalle_bloques_plantaciones_exploracion.cod_detalle = :codigo_detalle

				ORDER BY bw_detalle_bloques_plantaciones_exploracion.date_insert DESC;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_detalle",  $codigo_detalle);

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

      * Listado de exploraciones por plantacion

      */

	function plan_listado_exploraciones_plantacion($codigo_plantacion)
	{

		$SQL = "SELECT

				    bw_plantaciones_formulario_exploracion.cod_exploracion,

				    bw_plantaciones_formulario_exploracion.cod_plantacion,

				    bw_plantaciones_formulario_exploracion.etapa_crecimiento,

				    bw_plantaciones_formulario_exploracion.gusanos,

				    bw_plantaciones_formulario_exploracion.huevos,

				    bw_plantaciones_formulario_exploracion.saltahojas,

				    bw_plantaciones_formulario_exploracion.afidos,

				    bw_plantaciones_formulario_exploracion.chinches,

				    bw_plantaciones_formulario_exploracion.moscos,

				    bw_plantaciones_formulario_exploracion.escarabajos,

				    bw_plantaciones_formulario_exploracion.acaros,

				    bw_plantaciones_formulario_exploracion.cercospora_leaf_spot,

				    bw_plantaciones_formulario_exploracion.pythium,

				    bw_plantaciones_formulario_exploracion.rhizoctonia,

				    bw_plantaciones_formulario_exploracion.bacteria,

				    bw_plantaciones_formulario_exploracion.sclerotinia,

				    bw_plantaciones_formulario_exploracion.alternaria_specks,

				    bw_plantaciones_formulario_exploracion.mildew,

				    bw_plantaciones_formulario_exploracion.virus,

				    bw_plantaciones_formulario_exploracion.dolar,

				    bw_plantaciones_formulario_exploracion.frogs_bit,

				    bw_plantaciones_formulario_exploracion.plantas_lodo,

				    bw_plantaciones_formulario_exploracion.tripa_pollo,

				    bw_plantaciones_formulario_exploracion.zacate,

				    bw_plantaciones_formulario_exploracion.hojas_danadas,

				    bw_plantaciones_formulario_exploracion.tallos_purpuras,

				    bw_plantaciones_formulario_exploracion.berro_enraizado,

					bw_plantaciones_formulario_exploracion.observacion,

					bw_plantaciones_formulario_exploracion.spidermites,

					bw_plantaciones_formulario_exploracion.white_rust,

					bw_plantaciones_formulario_exploracion.salt_accumulation,

					bw_plantaciones_formulario_exploracion.nutsedge,

					bw_plantaciones_formulario_exploracion.buds,

					bw_plantaciones_formulario_exploracion.zigzag_stems,

					bw_plantaciones_formulario_exploracion.nutrient_deficiency,

					bw_plantaciones_formulario_exploracion.round_up,

					bw_plantaciones_formulario_exploracion.light_color,

					bw_plantaciones_formulario_exploracion.mealybugs,

					bw_plantaciones_formulario_exploracion.thrips,

				    bw_plantaciones_formulario_exploracion.activo,

				    bw_plantaciones_formulario_exploracion.user_insert,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_formulario_exploracion.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_formulario_exploracion.fecha_exploracion,'%Y-%m-%d'),'%m-%d-%Y') as fecha_exploracion,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario

				FROM

				    bw_plantaciones_formulario_exploracion

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_plantaciones_formulario_exploracion.user_insert)

				WHERE

				    bw_plantaciones_formulario_exploracion.cod_plantacion = :codigo_plantacion;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

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

      * Listado de usuarios a notificar por estado de plantacion

      */

	function plan_listado_usuarios_notificar_por_estado_plantacion($cod_estado_plantacion)
	{

		$SQL = "SELECT

				    bw_notificaciones_usuarios.cod_notificacion,

				    bw_notificaciones_usuarios.cod_usuario,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario,

				    usu_usuarios.email,

				    bw_notificaciones_usuarios.cod_estado,

				    bw_estados_plantacion.estado_plantacion,

    				bw_estados_plantacion.estado_plantacion_english,

				    bw_notificaciones_usuarios.activo,

				    bw_notificaciones_usuarios.user_insert

				FROM

				    bw_notificaciones_usuarios

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_notificaciones_usuarios.cod_usuario)

				        INNER JOIN

				    bw_estados_plantacion ON (bw_estados_plantacion.cod_estado_plantacion = bw_notificaciones_usuarios.cod_estado)

				WHERE

				    bw_notificaciones_usuarios.cod_estado = :cod_estado_plantacion

				        AND bw_notificaciones_usuarios.activo = 1

				ORDER BY nombre_usuario ASC;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_estado_plantacion",  $cod_estado_plantacion);

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

      * Permite guardar un item de un formulario con su respuesta.

      */

	function plan_guardar_item_respuesta_formulario(
		$codigo_plantacion,

		$cod_info_empresa,

		$codigo_formulario,

		$codigo_item,

		$codigo_item_checklist,

		$valor_item,

		$user_insert
	) {

		$SQL = "CALL plan_guardar_item_respuesta_formulario(:codigo_plantacion,

										:cod_info_empresa,

										:codigo_formulario,

										:codigo_item,

										:codigo_item_checklist,

										:valor_item,

										:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

		$stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);

		$stmt->bindParam(":codigo_formulario",  $codigo_formulario);

		$stmt->bindParam(":codigo_item",  $codigo_item);

		$stmt->bindParam(":codigo_item_checklist",  $codigo_item_checklist);

		$stmt->bindParam(":valor_item",  $valor_item);

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

      * Listado de formularios con sus respuestas por plantacion

      */

	function plan_listado_formularios_respuestas_plantacion($codigo_plantacion)
	{

		$SQL = "SELECT 

				    bw_detalle_items_respuestas.cod_detalle_item,

				    bw_detalle_items_respuestas.cod_info_empresa,

				    bw_detalle_items_respuestas.cod_item,

				    bw_detalle_items_respuestas.cod_plantacion,

				    bw_detalle_items_respuestas.cod_detalle_item_checklist,

				    bw_detalle_items_checklist.texto_item,

				    bw_detalle_items_checklist.valor_item,

				    bw_items_checklist.nombre_item,

				    bw_items_checklist.descripcion_item,

				    bw_items_checklist.cod_tipo_item,

				    bw_tipos_items.tipo_item,

				    bw_formularios.cod_formulario,

				    bw_formularios.nombre_formulario,

				    bw_detalle_items_respuestas.observacion,

                    respuesta_selectpicker.texto_item as respuesta_selectpicker,

				    bw_detalle_items_respuestas.adjunto,

				    bw_detalle_items_respuestas.user_insert,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario,

				    DATE_FORMAT(STR_TO_DATE(bw_detalle_items_respuestas.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert

				FROM

				    bw_detalle_items_respuestas

				        INNER JOIN

				    bw_detalle_items_checklist ON (bw_detalle_items_checklist.cod_detalle_item = bw_detalle_items_respuestas.cod_detalle_item_checklist

				        AND bw_detalle_items_checklist.activo = 1 AND bw_detalle_items_checklist.cod_item_check_list = bw_detalle_items_respuestas.cod_item)

				        LEFT JOIN

				    bw_detalle_items_checklist respuesta_selectpicker ON (respuesta_selectpicker.cod_detalle_item = bw_detalle_items_respuestas.observacion

				        AND bw_detalle_items_checklist.activo = 1 AND respuesta_selectpicker.cod_item_check_list = bw_detalle_items_respuestas.cod_item)

				        INNER JOIN

				    bw_items_checklist ON (bw_items_checklist.cod_item = bw_detalle_items_checklist.cod_item_check_list

				        AND bw_items_checklist.activo = 1)

				        INNER JOIN

				    bw_tipos_items ON (bw_tipos_items.cod_tipo_item = bw_items_checklist.cod_tipo_item)

				        INNER JOIN

				    bw_formularios ON (bw_formularios.cod_formulario = bw_items_checklist.cod_formulario)

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_detalle_items_respuestas.user_insert)

				WHERE

				    bw_detalle_items_respuestas.cod_plantacion = :codigo_plantacion

				ORDER BY bw_detalle_items_respuestas.date_insert ASC;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

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

      * Ingresa observacion de una exploracion

      */

	function plan_guardar_observacion_exploracion(
		$flag_exploracion,

		$cod_exploracion,

		$observacion_exploracion,

		$user_insert
	) {

		$SQL = "CALL plan_guardar_observacion_exploracion(:flag_exploracion,

										:cod_exploracion,

										:observacion_exploracion,

										:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":flag_exploracion",  $flag_exploracion);

		$stmt->bindParam(":cod_exploracion",  $cod_exploracion);

		$stmt->bindParam(":observacion_exploracion",  $observacion_exploracion);

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

      * Listado de observaciones de las exploraciones

      */

	function plan_listado_observaciones_exploraciones($flag_exploracion, $cod_exploracion)
	{

		if ($flag_exploracion == 0) {

			$SQL = "SELECT

					    bw_detalle_bloques_plantaciones_exploracion_observaciones.cod_observacion,

					    bw_detalle_bloques_plantaciones_exploracion_observaciones.cod_exploracion,

					    bw_detalle_bloques_plantaciones_exploracion_observaciones.observacion,

					    bw_detalle_bloques_plantaciones_exploracion_observaciones.user_insert,

					    CONCAT(usu_usuarios.nombre_1,

					            ' ',

					            usu_usuarios.apellido_1) AS nombre_usuario,

					    usu_usuarios.fotografia,

					    DATE_FORMAT(STR_TO_DATE(bw_detalle_bloques_plantaciones_exploracion_observaciones.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert

					FROM

					    bw_detalle_bloques_plantaciones_exploracion_observaciones

					        INNER JOIN

					    usu_usuarios ON (usu_usuarios.cod_usuario = bw_detalle_bloques_plantaciones_exploracion_observaciones.user_insert)

					WHERE

					    bw_detalle_bloques_plantaciones_exploracion_observaciones.cod_exploracion = :cod_exploracion;";
		} else {

			$SQL = "SELECT

					    bw_plantaciones_formulario_exploracion_observaciones.cod_observacion,

					    bw_plantaciones_formulario_exploracion_observaciones.cod_exploracion,

					    bw_plantaciones_formulario_exploracion_observaciones.observacion,

					    bw_plantaciones_formulario_exploracion_observaciones.user_insert,

					    CONCAT(usu_usuarios.nombre_1,

					            ' ',

					            usu_usuarios.apellido_1) AS nombre_usuario,

					    usu_usuarios.fotografia,

					    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_formulario_exploracion_observaciones.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert

					FROM

					    bw_plantaciones_formulario_exploracion_observaciones

					        INNER JOIN

					    usu_usuarios ON (usu_usuarios.cod_usuario = bw_plantaciones_formulario_exploracion_observaciones.user_insert)

					WHERE

					    bw_plantaciones_formulario_exploracion_observaciones.cod_exploracion = :cod_exploracion;";
		}

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_exploracion",  $cod_exploracion);

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

      * Listado de adjuntos de las exploraciones

      */

	function plan_listado_adjuntos_exploraciones($flag_exploracion, $cod_exploracion)
	{

		if ($flag_exploracion == 0) {

			$SQL = "SELECT

					    bw_detalle_bloques_plantaciones_exploracion_adjuntos.cod_adjunto,

					    bw_detalle_bloques_plantaciones_exploracion_adjuntos.cod_exploracion,

					    bw_detalle_bloques_plantaciones_exploracion_adjuntos.adjunto,

					    bw_detalle_bloques_plantaciones_exploracion_adjuntos.user_insert

					FROM

					    bw_detalle_bloques_plantaciones_exploracion_adjuntos

					WHERE

					    bw_detalle_bloques_plantaciones_exploracion_adjuntos.cod_exploracion = :cod_exploracion;";
		} else {

			$SQL = "SELECT

					    bw_plantaciones_formulario_exploracion_adjuntos.cod_adjunto,

					    bw_plantaciones_formulario_exploracion_adjuntos.cod_exploracion,

					    bw_plantaciones_formulario_exploracion_adjuntos.adjunto,

					    bw_plantaciones_formulario_exploracion_adjuntos.user_insert

					FROM

					    bw_plantaciones_formulario_exploracion_adjuntos

					WHERE

					    bw_plantaciones_formulario_exploracion_adjuntos.cod_exploracion = :cod_exploracion;";
		}

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_exploracion",  $cod_exploracion);

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

      * Ingresa adjunto de una exploracion

      */

	function plan_guardar_adjunto_exploracion(
		$flag_exploracion,

		$cod_exploracion,

		$nombre_archivo,

		$user_insert
	) {

		$SQL = "CALL plan_guardar_adjunto_exploracion(:flag_exploracion,

										:cod_exploracion,

										:nombre_archivo,

										:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":flag_exploracion",  $flag_exploracion);

		$stmt->bindParam(":cod_exploracion",  $cod_exploracion);

		$stmt->bindParam(":nombre_archivo",  $nombre_archivo);

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

      * Elimina adjunto de una exploracion

      */

	function plan_borrar_adjunto_exploracion(
		$flag_exploracion,

		$cod_exploracion,

		$cod_adjunto,

		$user_insert
	) {

		$SQL = "CALL plan_borrar_adjunto_exploracion(:flag_exploracion,

										:cod_exploracion,

										:cod_adjunto,

										:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":flag_exploracion",  $flag_exploracion);

		$stmt->bindParam(":cod_exploracion",  $cod_exploracion);

		$stmt->bindParam(":cod_adjunto",  $cod_adjunto);

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

      * Ingresa formulario de limpieza de un bloque de plantación

      */

	function plan_guardar_formulario_limpieza(
		$codigo_detalle,

		$fecha_limpieza,

		$vez_limpieza,

		$equipo_limpieza,

		$cleaning_tools,

		$cleaning_potable_water,

		$cleaning_detergent,

		$scrubbing,

		$rinse_potable_water,

		$sanitizing_chlorine,

		$post_sanitizing,

		$codigo_plantacion,

		$user_insert
	) {

		$SQL = "CALL plan_guardar_formulario_limpieza(:codigo_detalle,

										:fecha_limpieza,

										:vez_limpieza,

										:equipo_limpieza,

										:cleaning_tools,

										:cleaning_potable_water,

										:cleaning_detergent,

										:scrubbing,

										:rinse_potable_water,

										:sanitizing_chlorine,

										:post_sanitizing,

										:codigo_plantacion,

										:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_detalle",  $codigo_detalle);

		$stmt->bindParam(":fecha_limpieza",  $fecha_limpieza);

		$stmt->bindParam(":vez_limpieza",  $vez_limpieza);

		$stmt->bindParam(":equipo_limpieza",  $equipo_limpieza);

		$stmt->bindParam(":cleaning_tools",  $cleaning_tools);

		$stmt->bindParam(":cleaning_potable_water",  $cleaning_potable_water);

		$stmt->bindParam(":cleaning_detergent",  $cleaning_detergent);

		$stmt->bindParam(":scrubbing",  $scrubbing);

		$stmt->bindParam(":rinse_potable_water",  $rinse_potable_water);

		$stmt->bindParam(":sanitizing_chlorine",  $sanitizing_chlorine);

		$stmt->bindParam(":post_sanitizing",  $post_sanitizing);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

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

      * Ingresa formulario de limpieza de un bloque de plantación

      */

	function plan_guardar_formulario_harvesting_worksheet(
		$codigo_detalle,

		$harvest_date,

		$phi,

		$cellos,

		$increment_bunch_cello,

		$area_finished,

		$acres_harvested,

		$orden_compra,

		$cantidad_cosechada,

		$commments_harvesting_worksheet,

		$codigo_plantacion,

		$cantidad_empacada,

		$crop_number,

		$date_packed,

		$totes_harvested,

		$totes_packed,

		$user_insert
	) {

		$SQL = "CALL plan_guardar_formulario_harvesting_worksheet(:codigo_detalle,

																:harvest_date,

																:phi,

																:cellos,

																:increment_bunch_cello,

																:area_finished,

																:acres_harvested,

																:orden_compra,

																:cantidad_cosechada,

																:commments_harvesting_worksheet,

																:codigo_plantacion,

																:cantidad_empacada,

																:crop_number,

																:date_packed,

																:totes_harvested,

																:totes_packed,

																:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_detalle",  $codigo_detalle);

		$stmt->bindParam(":harvest_date",  $harvest_date);

		$stmt->bindParam(":phi",  $phi);

		$stmt->bindParam(":cellos",  $cellos);

		$stmt->bindParam(":increment_bunch_cello",  $increment_bunch_cello);

		$stmt->bindParam(":area_finished",  $area_finished);

		$stmt->bindParam(":acres_harvested",  $acres_harvested);

		$stmt->bindParam(":orden_compra",  $orden_compra);

		$stmt->bindParam(":cantidad_cosechada",  $cantidad_cosechada);

		$stmt->bindParam(":commments_harvesting_worksheet",  $commments_harvesting_worksheet);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

		$stmt->bindParam(":cantidad_empacada",  $cantidad_empacada);

		$stmt->bindParam(":crop_number",  $crop_number);

		$stmt->bindParam(":date_packed",  $date_packed);

		$stmt->bindParam(":totes_harvested",  $totes_harvested);

		$stmt->bindParam(":totes_packed",  $totes_packed);

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

      * Ingresa formulario de limpieza de un bloque de plantación

      */

	function plan_guardar_formulario_harvesting_checklist(
		$codigo_detalle,

		$fecha_checklist,

		$loose_bunches,

		$conventional_organic,

		$question1,

		$question2,

		$question3,

		$question4,

		$question5,

		$question6,

		$question7,

		$question8,

		$question9,

		$question10,

		$question11,

		$question12,

		$question13,

		$question14,

		$question15,

		$question16,

		$question17,

		$question18,

		$question19,

		$question20,

		$question21,

		$question22,

		$actions,

		$codigo_plantacion,

		$user_insert
	) {

		$SQL = "CALL plan_guardar_formulario_harvesting_checklist(:codigo_detalle,

																:fecha_checklist,

																:loose_bunches,

																:conventional_organic,

																:question1,

																:question2,

																:question3,

																:question4,

																:question5,

																:question6,

																:question7,

																:question8,

																:question9,

																:question10,

																:question11,

																:question12,

																:question13,

																:question14,

																:question15,

																:question16,

																:question17,

																:question18,

																:question19,

																:question20,

																:question21,

																:question22,

																:actions,

																:codigo_plantacion,

																:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_detalle",  $codigo_detalle);

		$stmt->bindParam(":fecha_checklist",  $fecha_checklist);

		$stmt->bindParam(":loose_bunches",  $loose_bunches);

		$stmt->bindParam(":conventional_organic",  $conventional_organic);

		$stmt->bindParam(":question1",  $question1);

		$stmt->bindParam(":question2",  $question2);

		$stmt->bindParam(":question3",  $question3);

		$stmt->bindParam(":question4",  $question4);

		$stmt->bindParam(":question5",  $question5);

		$stmt->bindParam(":question6",  $question6);

		$stmt->bindParam(":question7",  $question7);

		$stmt->bindParam(":question8",  $question8);

		$stmt->bindParam(":question9",  $question9);

		$stmt->bindParam(":question10",  $question10);

		$stmt->bindParam(":question11",  $question11);

		$stmt->bindParam(":question12",  $question12);

		$stmt->bindParam(":question13",  $question13);

		$stmt->bindParam(":question14",  $question14);

		$stmt->bindParam(":question15",  $question15);

		$stmt->bindParam(":question16",  $question16);

		$stmt->bindParam(":question17",  $question17);

		$stmt->bindParam(":question18",  $question18);

		$stmt->bindParam(":question19",  $question19);

		$stmt->bindParam(":question20",  $question20);

		$stmt->bindParam(":question21",  $question21);

		$stmt->bindParam(":question22",  $question22);

		$stmt->bindParam(":actions",  $actions);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

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

      * Permite guardar un item de un formulario con su respuesta.

      */

	function plan_agregar_quimico_aplicacion_quimico(
		$cod_aplicacion_quimico,

		$cod_tipo_quimico,

		$cod_inventario_quimico,

		$cod_unidad_medida_origen,

		$cod_unidad_medida_destino,

		$cantidad_sugerida,

		$user_insert
	) {

		$SQL = "CALL plan_agregar_quimico_aplicacion_quimico(:cod_aplicacion_quimico,

										:cod_tipo_quimico,

										:cod_inventario_quimico,

										:cod_unidad_medida_origen,

										:cod_unidad_medida_destino,

										:cantidad_sugerida,

										:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_aplicacion_quimico",  $cod_aplicacion_quimico);

		$stmt->bindParam(":cod_tipo_quimico",  $cod_tipo_quimico);

		$stmt->bindParam(":cod_inventario_quimico",  $cod_inventario_quimico);

		$stmt->bindParam(":cod_unidad_medida_origen",  $cod_unidad_medida_origen);

		$stmt->bindParam(":cod_unidad_medida_destino",  $cod_unidad_medida_destino);

		$stmt->bindParam(":cantidad_sugerida",  $cantidad_sugerida);

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

	 * Funciones para obtener listado de bloques de una plantación

	 */

	function plan_listado_quimicos_aplicacion_quimico($cod_aplicacion)
	{

		$SQL = "SELECT 

				    bw_plantaciones_detalle_aplicar_quimicos.cod_detalle,

				    bw_plantaciones_detalle_aplicar_quimicos.cod_aplicacion,

				    bw_plantaciones_detalle_aplicar_quimicos.cod_tipo_quimico,

				    bw_plantaciones_detalle_aplicar_quimicos.cod_inventario,

				    bw_plantaciones_detalle_aplicar_quimicos.cod_unidad_medida,

				    bw_plantaciones_detalle_aplicar_quimicos.cantidad_sugerida,

				    bw_plantaciones_detalle_aplicar_quimicos.cantidad_aplicada,

				    bw_plantaciones_detalle_aplicar_quimicos.activo,

				    bw_plantaciones_detalle_aplicar_quimicos.user_insert,

				    CONCAT(usu_usuarios.nombre_1,' ',usu_usuarios.apellido_1) as nombre_usuario,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_detalle_aplicar_quimicos.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    bw_tipo_quimico.tipo_quimico,

				    bw_inventario_quimicos.nombre_quimico,

				    bw_inventario_quimicos.razon_aplicacion,

				    bw_inventario_quimicos.dosis_minima,

				    bw_inventario_quimicos.dosis_maxima,

				    ug_unidades_medida.unidad_medida

				FROM

				    bw_plantaciones_detalle_aplicar_quimicos

				        INNER JOIN

				    bw_tipo_quimico ON (bw_tipo_quimico.cod_tipo_quimico = bw_plantaciones_detalle_aplicar_quimicos.cod_tipo_quimico)

				        INNER JOIN

				    bw_inventario_quimicos ON (bw_inventario_quimicos.cod_inventario = bw_plantaciones_detalle_aplicar_quimicos.cod_inventario)

				        INNER JOIN

				    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_plantaciones_detalle_aplicar_quimicos.cod_unidad_medida)

						INNER JOIN

					usu_usuarios ON (usu_usuarios.cod_usuario = bw_plantaciones_detalle_aplicar_quimicos.user_insert)

				WHERE

				    bw_plantaciones_detalle_aplicar_quimicos.cod_aplicacion = :cod_aplicacion

				        AND bw_plantaciones_detalle_aplicar_quimicos.activo = 1

				ORDER BY bw_plantaciones_detalle_aplicar_quimicos.date_insert DESC;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_aplicacion",  $cod_aplicacion);

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

      * Permite guardar un item de un formulario con su respuesta.

      */

	function plan_eliminar_quimico_aplicacion_quimico(
		$cod_detalle,

		$user_insert
	) {

		$SQL = "CALL plan_eliminar_quimico_aplicacion_quimico(:cod_detalle,

    															:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_detalle",  $cod_detalle);

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

      * Permite guardar un item de un formulario con su respuesta.

      */

	function plan_ingresar_cantidad_aplicada_quimico(
		$cod_detalle,

		$cantidad_aplicada,

		$user_insert
	) {

		$SQL = "CALL plan_ingresar_cantidad_aplicada_quimico(:cod_detalle,

    															:cantidad_aplicada,

    															:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_detalle",  $cod_detalle);

		$stmt->bindParam(":cantidad_aplicada",  $cantidad_aplicada);

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

      * Permite eliminar una aplicación de químicos de una plantación.

      */

	function plan_eliminar_aplicar_quimico(
		$cod_aplicacion,

		$user_insert
	) {

		$SQL = "CALL plan_eliminar_aplicar_quimico(:cod_aplicacion,

    												:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_aplicacion",  $cod_aplicacion);

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

      * Guarda un trasplante

      */

	function plan_guardar_trasplantar(
		$codigo_plantacion,

		$cod_plantacion,

		$cod_bloques_plantacion2,

		$cantidad,

		$observacion_trasplantar,

		$cod_bloques_trasplante,

		$numero_carga,

		$fecha_trasplante,

		$user_insert
	) {

		$SQL = "CALL plan_guardar_trasplantar(:codigo_plantacion,

										:cod_plantacion,

										:cod_bloques_plantacion2,

										:cantidad,

										:observacion_trasplantar,

										:cod_bloques_trasplante,

										:numero_carga,

										:fecha_trasplante,

                                		:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

		$stmt->bindParam(":cod_plantacion",  $cod_plantacion);

		$stmt->bindParam(":cod_bloques_plantacion2",  $cod_bloques_plantacion2);

		$stmt->bindParam(":cantidad",  $cantidad);

		$stmt->bindParam(":observacion_trasplantar",  $observacion_trasplantar);

		$stmt->bindParam(":cod_bloques_trasplante",  $cod_bloques_trasplante);

		$stmt->bindParam(":numero_carga",  $numero_carga);

		$stmt->bindParam(":fecha_trasplante",  $fecha_trasplante);

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

      * Listado de trasplantes de plantaciones

      */

	function plan_listado_trasplantes_plantaciones($codigo_plantacion)
	{

		$SQL = "SELECT DISTINCT

				    bw_plantaciones_trasplantes.cod_trasplante,

				    bw_plantaciones_trasplantes.cod_plantacion_envia,

				    empresa_envia.nombre_empresa AS nombre_empresa_envia,

				    plantacion_envia.anio_plantacion AS anio_plantacion_envia,

				    plantacion_envia.num_plantacion AS num_plantacion_envia,

				    temporada_envia.codigo_temporada AS codigo_temporada_envia,

				    bw_plantaciones_trasplantes.cod_plantacion_recibe,

				    empresa_recibe.nombre_empresa AS nombre_empresa_recibe,

				    plantacion_recibe.anio_plantacion AS anio_plantacion_recibe,

				    plantacion_recibe.num_plantacion AS num_plantacion_recibe,

				    temporada_recibe.codigo_temporada AS codigo_temporada_recibe,

				    bw_plantaciones_trasplantes.cod_bloques,

				    GROUP_CONCAT(CONCAT(bw_zonas.zona,

				                '-',

				                CONCAT(IF(bw_bloques.clave_bloque != '',

						            CONCAT(bw_bloques.clave_bloque, '-'),

						            ''),

						        bw_bloques.nombre_bloque))

				        SEPARATOR '</br>') AS bloques,

				    bw_plantaciones_trasplantes.cantidad,

				    bw_plantaciones_trasplantes.cod_bloques_trasplante,

				    (SELECT 

				            GROUP_CONCAT(CONCAT(zonas_trasplante.zona,

				                            '-',

				                            bloques_trasplante.nombre_bloque)

				                    SEPARATOR '</br>')

				        FROM

				            bw_plantaciones_trasplantes p

				                LEFT JOIN

				            bw_bloques bloques_trasplante ON (FIND_IN_SET(bloques_trasplante.cod_bloque,

				                    p.cod_bloques_trasplante))

				                LEFT JOIN

				            bw_zonas zonas_trasplante ON (zonas_trasplante.cod_zona = bloques_trasplante.cod_zona)

				        WHERE

				            p.cod_trasplante = bw_plantaciones_trasplantes.cod_trasplante) AS bloques_trasplante,

				    bw_plantaciones_trasplantes.numero_carga,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_trasplantes.fecha_trasplante,'%Y-%m-%d'),'%m-%d-%Y') as fecha_trasplante,

				    bw_plantaciones_trasplantes.observacion,

				    bw_plantaciones_trasplantes.activo,

				    bw_plantaciones_trasplantes.user_insert,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_trasplantes.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert

				FROM

				    bw_plantaciones_trasplantes

				        INNER JOIN

				    bw_plantaciones plantacion_envia ON (plantacion_envia.cod_plantacion = bw_plantaciones_trasplantes.cod_plantacion_envia)

				        INNER JOIN

				    bw_info_empresa empresa_envia ON (empresa_envia.cod_info_empresa = plantacion_envia.cod_info_empresa)

				        INNER JOIN

				    bw_temporadas temporada_envia ON (temporada_envia.cod_temporada = plantacion_envia.cod_temporada)

				        INNER JOIN

				    bw_plantaciones plantacion_recibe ON (plantacion_recibe.cod_plantacion = bw_plantaciones_trasplantes.cod_plantacion_recibe)

				        INNER JOIN

				    bw_info_empresa empresa_recibe ON (empresa_recibe.cod_info_empresa = plantacion_recibe.cod_info_empresa)

				        INNER JOIN

				    bw_temporadas temporada_recibe ON (temporada_recibe.cod_temporada = plantacion_recibe.cod_temporada)

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_plantaciones_trasplantes.user_insert)

				        INNER JOIN

				    bw_bloques ON (FIND_IN_SET(bw_bloques.cod_bloque,

				            bw_plantaciones_trasplantes.cod_bloques))

				        INNER JOIN

				    bw_zonas ON (bw_zonas.cod_zona = bw_bloques.cod_zona)

				WHERE

				    bw_plantaciones_trasplantes.cod_plantacion_envia = :codigo_plantacion

				        OR bw_plantaciones_trasplantes.cod_plantacion_recibe = :codigo_plantacion

				GROUP BY bw_plantaciones_trasplantes.cod_trasplante

				ORDER BY bw_plantaciones_trasplantes.date_insert DESC;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

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

      * Listado de aplicaciones quimicas de bloques

      */

	function plan_listado_aplicaciones_quimicos_bloques($codigo_detalle)
	{

		/*$SQL = "SELECT 

				    bw_plantaciones_aplicar_quimicos.cod_aplicacion,

				    bw_plantaciones_aplicar_quimicos.cod_plantacion,

				    bw_plantaciones_aplicar_quimicos.cod_bloques_aplicacion,

				    (SELECT 

				            GROUP_CONCAT(CONCAT(bw_zonas.zona,

				                            '-',

				                            bw_bloques.nombre_bloque)

				                    SEPARATOR '<br>')

				        FROM

				            bw_bloques

				                INNER JOIN

				            bw_zonas ON (bw_zonas.cod_zona = bw_bloques.cod_zona)

				        WHERE

				            FIND_IN_SET(bw_bloques.cod_bloque,

				                    bw_plantaciones_aplicar_quimicos.cod_bloques_aplicacion)) AS bloques,

				    bw_plantaciones_aplicar_quimicos.cod_inventario_maquinaria,

				    bw_inventario_maquinaria.nombre_maquinaria,

				    CONCAT(user_supervisor.nombre_1,

				            ' ',

				            user_supervisor.apellido_1) AS usuario_supervisor,

				    CONCAT(user_operador.nombre_1,

				            ' ',

				            user_operador.apellido_1) AS usuario_operador,

				    bw_plantaciones_aplicar_quimicos.date_insert,

				    bw_plantaciones_aplicar_quimicos.fecha_aplicacion_supervisor,

				    bw_plantaciones_aplicar_quimicos.fecha_aplicacion_operador,

				    bw_plantaciones_aplicar_quimicos.hora_inicial,

				    bw_plantaciones_aplicar_quimicos.hora_final,

				    bw_plantaciones_aplicar_quimicos.viento,

				    bw_plantaciones_aplicar_quimicos.temperatura,

				    bw_plantaciones_aplicar_quimicos.cod_tipo_aplicacion,

				    bw_plantaciones_aplicar_quimicos.descripcion_aplicar_quimico,

				    bw_plantaciones_aplicar_quimicos.activo,

				    bw_plantaciones_aplicar_quimicos.user_insert,

				    (SELECT 

				            COUNT(bw_plantaciones_detalle_aplicar_quimicos.cod_detalle)

				        FROM

				            bw_plantaciones_detalle_aplicar_quimicos

				        WHERE

				            bw_plantaciones_detalle_aplicar_quimicos.cod_aplicacion = bw_plantaciones_aplicar_quimicos.cod_aplicacion

				            AND bw_plantaciones_detalle_aplicar_quimicos.activo = 1) AS contador_quimicos,

					GROUP_CONCAT(CONCAT(bw_inventario_quimicos.nombre_quimico, ' - ', bw_plantaciones_detalle_aplicar_quimicos.cantidad_aplicada)

                    SEPARATOR '<br>') as quimicos

				FROM

				    bw_plantaciones_aplicar_quimicos

				        INNER JOIN

				    bw_plantaciones ON (bw_plantaciones.cod_plantacion = bw_plantaciones_aplicar_quimicos.cod_plantacion)

				        LEFT JOIN

				    bw_inventario_maquinaria ON (bw_inventario_maquinaria.cod_inventario = bw_plantaciones_aplicar_quimicos.cod_inventario_maquinaria)

				        INNER JOIN

				    usu_usuarios user_supervisor ON (user_supervisor.cod_usuario = bw_plantaciones_aplicar_quimicos.user_insert)

				        LEFT JOIN

				    usu_usuarios user_operador ON (user_operador.cod_usuario = bw_plantaciones_aplicar_quimicos.cod_operador)

						INNER JOIN

					bw_detalle_bloques_plantaciones ON (bw_detalle_bloques_plantaciones.cod_plantacion = bw_plantaciones_aplicar_quimicos.cod_plantacion

                    AND FIND_IN_SET(bw_detalle_bloques_plantaciones.cod_bloque,bw_plantaciones_aplicar_quimicos.cod_bloques_aplicacion))

						LEFT JOIN

					bw_plantaciones_detalle_aplicar_quimicos ON (bw_plantaciones_detalle_aplicar_quimicos.cod_aplicacion = bw_plantaciones_aplicar_quimicos.cod_aplicacion)

						LEFT JOIN

					bw_inventario_quimicos ON (bw_inventario_quimicos.cod_inventario = bw_plantaciones_detalle_aplicar_quimicos.cod_inventario)

                WHERE

				    bw_detalle_bloques_plantaciones.cod_detalle = :codigo_detalle

				    AND bw_plantaciones_aplicar_quimicos.activo = 1

                GROUP BY bw_plantaciones_aplicar_quimicos.cod_aplicacion

				ORDER BY bw_plantaciones_aplicar_quimicos.date_insert DESC;";*/

		$SQL = "SELECT 

				    bw_plantaciones_aplicar_quimicos.cod_aplicacion,

				    bw_plantaciones_aplicar_quimicos.cod_plantacion,

				    bw_plantaciones_aplicar_quimicos.cod_bloques_aplicacion,

				    (SELECT 

				            GROUP_CONCAT(CONCAT(bw_zonas.zona,

				                            '-',

				                            CONCAT(IF(bw_bloques.clave_bloque != '',

									            CONCAT(bw_bloques.clave_bloque, '-'),

									            ''),

									        bw_bloques.nombre_bloque))

				                    SEPARATOR '<br>')

				        FROM

				            bw_bloques

				                INNER JOIN

				            bw_zonas ON (bw_zonas.cod_zona = bw_bloques.cod_zona)

				        WHERE

				            FIND_IN_SET(bw_bloques.cod_bloque,

				                    bw_plantaciones_aplicar_quimicos.cod_bloques_aplicacion)) AS bloques,

				    (SELECT 

				            GROUP_CONCAT(CONCAT(bw_zonas.zona,

				                            '-',

				                            bw_bloques.nombre_bloque)

				                    SEPARATOR '<br>')

				        FROM

				            bw_bloques

				                INNER JOIN

				            bw_zonas ON (bw_zonas.cod_zona = bw_bloques.cod_zona)

				                INNER JOIN

				            bw_detalle_bloques_plantaciones ON (bw_detalle_bloques_plantaciones.cod_detalle = :codigo_detalle)

				        WHERE

				            bw_bloques.cod_bloque = bw_detalle_bloques_plantaciones.cod_bloque) AS bloque,

				    bw_plantaciones_aplicar_quimicos.cod_inventario_maquinaria,

				    bw_inventario_maquinaria.nombre_maquinaria,

				    CONCAT(user_supervisor.nombre_1,

				            ' ',

				            user_supervisor.apellido_1) AS usuario_supervisor,

				    CONCAT(user_operador.nombre_1,

				            ' ',

				            user_operador.apellido_1) AS usuario_operador,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_aplicar_quimicos.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_aplicar_quimicos.fecha_aplicacion_supervisor,'%Y-%m-%d'),'%m-%d-%Y') as fecha_aplicacion_supervisor,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_aplicar_quimicos.fecha_aplicacion_operador,'%Y-%m-%d'),'%m-%d-%Y') as fecha_aplicacion_operador,

				    bw_plantaciones_aplicar_quimicos.hora_inicial,

				    bw_plantaciones_aplicar_quimicos.hora_final,

				    bw_plantaciones_aplicar_quimicos.viento,

				    bw_plantaciones_aplicar_quimicos.temperatura,

				    bw_plantaciones_aplicar_quimicos.cod_tipo_aplicacion,

				    bw_plantaciones_aplicar_quimicos.descripcion_aplicar_quimico,

				    bw_plantaciones_aplicar_quimicos.activo,

				    bw_plantaciones_aplicar_quimicos.user_insert,

				    (SELECT 

				            COUNT(bw_plantaciones_detalle_aplicar_quimicos.cod_detalle)

				        FROM

				            bw_plantaciones_detalle_aplicar_quimicos

				        WHERE

				            bw_plantaciones_detalle_aplicar_quimicos.cod_aplicacion = bw_plantaciones_aplicar_quimicos.cod_aplicacion

				                AND bw_plantaciones_detalle_aplicar_quimicos.activo = 1) AS contador_quimicos,

				    GROUP_CONCAT(CONCAT(bw_inventario_quimicos.nombre_quimico,

				                ' - ',

				                bw_plantaciones_detalle_aplicar_quimicos.cantidad_aplicada)

				        SEPARATOR '<br>') AS quimicos

				FROM

				    bw_plantaciones_aplicar_quimicos

				        INNER JOIN

				    bw_plantaciones ON (bw_plantaciones.cod_plantacion = bw_plantaciones_aplicar_quimicos.cod_plantacion)

				        LEFT JOIN

				    bw_inventario_maquinaria ON (bw_inventario_maquinaria.cod_inventario = bw_plantaciones_aplicar_quimicos.cod_inventario_maquinaria)

				        INNER JOIN

				    usu_usuarios user_supervisor ON (user_supervisor.cod_usuario = bw_plantaciones_aplicar_quimicos.user_insert)

				        LEFT JOIN

				    usu_usuarios user_operador ON (user_operador.cod_usuario = bw_plantaciones_aplicar_quimicos.cod_operador)

				        INNER JOIN

				    bw_detalle_bloques_plantaciones ON (bw_detalle_bloques_plantaciones.cod_plantacion = bw_plantaciones_aplicar_quimicos.cod_plantacion

				        AND FIND_IN_SET(bw_detalle_bloques_plantaciones.cod_bloque,

				            bw_plantaciones_aplicar_quimicos.cod_bloques_aplicacion))

				        LEFT JOIN

				    bw_plantaciones_detalle_aplicar_quimicos ON (bw_plantaciones_detalle_aplicar_quimicos.cod_aplicacion = bw_plantaciones_aplicar_quimicos.cod_aplicacion)

				        LEFT JOIN

				    bw_inventario_quimicos ON (bw_inventario_quimicos.cod_inventario = bw_plantaciones_detalle_aplicar_quimicos.cod_inventario)

				WHERE

				    bw_detalle_bloques_plantaciones.cod_detalle = :codigo_detalle

				        AND bw_plantaciones_aplicar_quimicos.activo = 1

				        AND bw_plantaciones_detalle_aplicar_quimicos.activo = 1

                        AND (SELECT 

				            COUNT(bw_bloques.nombre_bloque)

				        FROM

				            bw_bloques

				                INNER JOIN

				            bw_zonas ON (bw_zonas.cod_zona = bw_bloques.cod_zona)

				        WHERE

				            FIND_IN_SET(bw_bloques.cod_bloque,

				                    bw_plantaciones_aplicar_quimicos.cod_bloques_aplicacion)) = 1

				GROUP BY bw_plantaciones_aplicar_quimicos.cod_aplicacion

				ORDER BY bw_plantaciones_aplicar_quimicos.date_insert DESC;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_detalle",  $codigo_detalle);

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

      * Listado de todos los bloques por plantacion

      */

	function plan_listado_bloques_plantaciones()
	{

		$SQL = "SELECT DISTINCT

					    bw_plantaciones.cod_plantacion,

					    bw_plantaciones.cod_info_empresa,

					    bw_plantaciones.anio_plantacion,

					    bw_plantaciones.num_plantacion,

					    bw_plantaciones.cod_estado,

					    bw_plantaciones.cod_temporada,

					    bw_info_empresa.nombre_empresa,

					    estado_plantacion.estado_plantacion,

					    bw_temporadas.codigo_temporada,

					    bw_detalle_bloques_plantaciones.cod_detalle,

					    bw_detalle_bloques_plantaciones.cod_bloque,

					    bw_detalle_bloques_plantaciones.cantidad_acres,

					    bw_detalle_bloques_plantaciones.activo,

					    bw_zonas.zona,

					    bw_bloques.clave_bloque,

					    bw_bloques.nombre_bloque,

					    bw_detalle_bloques_plantaciones.cod_estado_plantacion,

					    bw_estados_plantacion.estado_plantacion,

					    bw_estados_plantacion.estado_plantacion_english,

					    bw_detalle_bloques_plantaciones.motivo_estado_plantacion

					FROM

					    bw_detalle_bloques_plantaciones

					        INNER JOIN

					    bw_bloques ON (bw_bloques.cod_bloque = bw_detalle_bloques_plantaciones.cod_bloque)

					        INNER JOIN

					    bw_zonas ON (bw_zonas.cod_zona = bw_bloques.cod_zona)

					        LEFT JOIN

					    bw_estados_plantacion ON (bw_estados_plantacion.cod_estado_plantacion = bw_detalle_bloques_plantaciones.cod_estado_plantacion)

					        INNER JOIN

					    bw_plantaciones ON (bw_plantaciones.cod_plantacion = bw_detalle_bloques_plantaciones.cod_plantacion)

					        INNER JOIN

					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_plantaciones.cod_info_empresa)

					        INNER JOIN

					    bw_estados_plantacion estado_plantacion ON (estado_plantacion.cod_estado_plantacion = bw_plantaciones.cod_estado)

					        INNER JOIN

					    bw_temporadas ON (bw_temporadas.cod_temporada = bw_plantaciones.cod_temporada)

					WHERE

					    bw_detalle_bloques_plantaciones.activo = 1

					        AND bw_detalle_bloques_plantaciones.cod_estado_plantacion < 9

					        AND bw_plantaciones.cod_estado < 9

					ORDER BY bw_plantaciones.cod_plantacion ASC;";

		$stmt = $this->db_conexion->prepare($SQL);

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

      * Ingresa formulario de limpieza de un bloque de plantación

      */

	function plan_actualizar_formulario_harvesting_worksheet(
		$codigo_formulario,

		$harvest_date,

		$phi,

		$cellos,

		$increment_bunch_cello,

		$area_finished,

		$acres_harvested,

		$orden_compra,

		$cantidad_cosechada,

		$cantidad_empacada,

		$commments_harvesting_worksheet,

		$crop_number,

		$date_packed,

		$totes_harvested,

		$totes_packed,

		$user_insert
	) {

		$SQL = "CALL plan_actualizar_formulario_harvesting_worksheet(:codigo_formulario,

																:harvest_date,

																:phi,

																:cellos,

																:increment_bunch_cello,

																:area_finished,

																:acres_harvested,

																:orden_compra,

																:cantidad_cosechada,

																:cantidad_empacada,

																:commments_harvesting_worksheet,

																:crop_number,

																:date_packed,

																:totes_harvested,

																:totes_packed,

																:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_formulario",  $codigo_formulario);

		$stmt->bindParam(":harvest_date",  $harvest_date);

		$stmt->bindParam(":phi",  $phi);

		$stmt->bindParam(":cellos",  $cellos);

		$stmt->bindParam(":increment_bunch_cello",  $increment_bunch_cello);

		$stmt->bindParam(":area_finished",  $area_finished);

		$stmt->bindParam(":acres_harvested",  $acres_harvested);

		$stmt->bindParam(":orden_compra",  $orden_compra);

		$stmt->bindParam(":cantidad_cosechada",  $cantidad_cosechada);

		$stmt->bindParam(":cantidad_empacada",  $cantidad_empacada);

		$stmt->bindParam(":commments_harvesting_worksheet",  $commments_harvesting_worksheet);

		$stmt->bindParam(":crop_number",  $crop_number);

		$stmt->bindParam(":date_packed",  $date_packed);

		$stmt->bindParam(":totes_harvested",  $totes_harvested);

		$stmt->bindParam(":totes_packed",  $totes_packed);

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

      * Permite eliminar una harvesting worksheet de una plantación.

      */

	function plan_eliminar_worksheet(
		$cod_formulario,

		$user_insert
	) {

		$SQL = "CALL plan_eliminar_worksheet(:cod_formulario,

    												:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_formulario",  $cod_formulario);

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

      * Guarda/Actualiza un load report de plantación

      */

	function plan_guardar_load_report_plantacion(
		$codigo_plantacion,

		$cod_bloques_load_report,

		$cod_inventario_load_report,

		$num_orden_compra,

		$fecha_orden,

		$num_lote,

		$num_lote_ranch,

		$inicial_size_harvest,

		$final_size_harvest,

		$dark_green_color,

		$yellow_leaves,

		$weeds,

		$optimal_soil_water_capacity,

		$right_density,

		$other_defects,

		$size_range_porcentage,

		$inicial_size_range,

		$final_size_range,

		$select_size_range,

		$size_range1,

		$select_size_range1,

		$size_range2,

		$select_size_range2,

		$other_defects_ha,

		$select_other_defects_ha,

		$dew_leaf,

		$inicial_time_harvest,

		$final_time_harvest,

		$temperature_product,

		$inicial_average_tote_weight_reported,

		$final_average_tote_weight_reported,

		$real_average_tote_weight,

		$time_receiving,

		$total_load_lbs_goal,

		$load_weight_received,

		$average_tote_weight,

		$temperature_receiving,

		$time_vacuum_cooler,

		$temperature_vacuum_cooler,

		$hydrocooling,

		$time_pickup,

		$tlc,

		$vacuum_cooler,

		$total_temperature,

		$pickup_truck_checkin,

		$number_cut,

		$codigo_reporte,

		$user_insert
	) {

		$SQL = "CALL plan_guardar_load_report_plantacion(:codigo_plantacion,

														:cod_bloques_load_report,

														:cod_inventario_load_report,

														:num_orden_compra,

														:fecha_orden,

														:num_lote,

														:num_lote_ranch,

														:inicial_size_harvest,

														:final_size_harvest,

														:dark_green_color,

														:yellow_leaves,

														:weeds,

														:optimal_soil_water_capacity,

														:right_density,

														:other_defects,

														:size_range_porcentage,

														:inicial_size_range,

														:final_size_range,

														:select_size_range,

														:size_range1,

														:select_size_range1,

														:size_range2,

														:select_size_range2,

														:other_defects_ha,

														:select_other_defects_ha,

														:dew_leaf,

														:inicial_time_harvest,

														:final_time_harvest,

														:temperature_product,

														:inicial_average_tote_weight_reported,

														:final_average_tote_weight_reported,

														:real_average_tote_weight,

														:time_receiving,

														:total_load_lbs_goal,

														:load_weight_received,

														:average_tote_weight,

														:temperature_receiving,

														:time_vacuum_cooler,

														:temperature_vacuum_cooler,

														:hydrocooling,

														:time_pickup,

														:tlc,

														:vacuum_cooler,

														:total_temperature,

														:pickup_truck_checkin,

														:number_cut,

														:codigo_reporte,

                                						:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

		$stmt->bindParam(":cod_bloques_load_report",  $cod_bloques_load_report);

		$stmt->bindParam(":cod_inventario_load_report",  $cod_inventario_load_report);

		$stmt->bindParam(":num_orden_compra",  $num_orden_compra);

		$stmt->bindParam(":fecha_orden",  $fecha_orden);

		$stmt->bindParam(":num_lote",  $num_lote);

		$stmt->bindParam(":num_lote_ranch",  $num_lote_ranch);

		$stmt->bindParam(":inicial_size_harvest",  $inicial_size_harvest);

		$stmt->bindParam(":final_size_harvest",  $final_size_harvest);

		$stmt->bindParam(":dark_green_color",  $dark_green_color);

		$stmt->bindParam(":yellow_leaves",  $yellow_leaves);

		$stmt->bindParam(":weeds",  $weeds);

		$stmt->bindParam(":optimal_soil_water_capacity",  $optimal_soil_water_capacity);

		$stmt->bindParam(":right_density",  $right_density);

		$stmt->bindParam(":other_defects",  $other_defects);

		$stmt->bindParam(":size_range_porcentage",  $size_range_porcentage);

		$stmt->bindParam(":inicial_size_range",  $inicial_size_range);

		$stmt->bindParam(":final_size_range",  $final_size_range);

		$stmt->bindParam(":select_size_range",  $select_size_range);

		$stmt->bindParam(":size_range1",  $size_range1);

		$stmt->bindParam(":select_size_range1",  $select_size_range1);

		$stmt->bindParam(":size_range2",  $size_range2);

		$stmt->bindParam(":select_size_range2",  $select_size_range2);

		$stmt->bindParam(":other_defects_ha",  $other_defects_ha);

		$stmt->bindParam(":select_other_defects_ha",  $select_other_defects_ha);

		$stmt->bindParam(":dew_leaf",  $dew_leaf);

		$stmt->bindParam(":inicial_time_harvest",  $inicial_time_harvest);

		$stmt->bindParam(":final_time_harvest",  $final_time_harvest);

		$stmt->bindParam(":temperature_product",  $temperature_product);

		$stmt->bindParam(":inicial_average_tote_weight_reported",  $inicial_average_tote_weight_reported);

		$stmt->bindParam(":final_average_tote_weight_reported",  $final_average_tote_weight_reported);

		$stmt->bindParam(":real_average_tote_weight",  $real_average_tote_weight);

		$stmt->bindParam(":time_receiving",  $time_receiving);

		$stmt->bindParam(":total_load_lbs_goal",  $total_load_lbs_goal);

		$stmt->bindParam(":load_weight_received",  $load_weight_received);

		$stmt->bindParam(":average_tote_weight",  $average_tote_weight);

		$stmt->bindParam(":temperature_receiving",  $temperature_receiving);

		$stmt->bindParam(":time_vacuum_cooler",  $time_vacuum_cooler);

		$stmt->bindParam(":temperature_vacuum_cooler",  $temperature_vacuum_cooler);

		$stmt->bindParam(":hydrocooling",  $hydrocooling);

		$stmt->bindParam(":time_pickup",  $time_pickup);

		$stmt->bindParam(":tlc",  $tlc);

		$stmt->bindParam(":vacuum_cooler",  $vacuum_cooler);

		$stmt->bindParam(":total_temperature",  $total_temperature);

		$stmt->bindParam(":pickup_truck_checkin",  $pickup_truck_checkin);

		$stmt->bindParam(":number_cut",  $number_cut);

		$stmt->bindParam(":codigo_reporte",  $codigo_reporte);

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

	 * Funciones para obtener listado de bloques de una plantación

	 */

	function plan_listado_reporte_carga_plantacion($cod_plantacion)
	{

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

		$SQL = "SELECT 

				    cod_reporte,

				    cod_plantacion,

				    cod_bloques_plantacion,

				    cod_inventario_semilla,

				    num_orden_compra,

                    DATE_FORMAT(STR_TO_DATE(fecha_orden,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as fecha_orden,

                    num_lote,

                    num_lote_ranch,

                    inicial_size_harvest,

                    final_size_harvest,

                    dark_green_color,

                    yellow_leaves,

                    weeds,

                    optimal_soil_water_capacity,

                    right_density,

                    other_defects,

                    size_range_porcentage,

                    inicial_size_range,

                    final_size_range,

                    select_size_range,

                    size_range1,

                    select_size_range1,

                    size_range2,

                    select_size_range2,

                    other_defects_ha,

                    select_other_defects_ha,

                    dew_leaf,

                    DATE_FORMAT(STR_TO_DATE(inicial_time_harvest,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as inicial_time_harvest,

                    DATE_FORMAT(STR_TO_DATE(final_time_harvest,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as final_time_harvest,

                    temperature_product,

                    inicial_average_tote_weight_reported,

                    final_average_tote_weight_reported,

                    real_average_tote_weight,

                    DATE_FORMAT(STR_TO_DATE(time_receiving,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as time_receiving,

                    total_load_lbs_goal,

                    load_weight_received,

                    average_tote_weight,

                    temperature_receiving,

                    DATE_FORMAT(STR_TO_DATE(time_vacuum_cooler,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as time_vacuum_cooler,

                    DATE_FORMAT(STR_TO_DATE(temperature_vacuum_cooler,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as temperature_vacuum_cooler,

                    hydrocooling,

                    DATE_FORMAT(STR_TO_DATE(time_pickup,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as time_pickup,

                    tlc,

                    vacuum_cooler,

                    DATE_FORMAT(STR_TO_DATE(pickup_truck_checkin,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as pickup_truck_checkin,

				    number_cut,

				    bw_reporte_carga_plantacion.activo,

				    bw_reporte_carga_plantacion.user_insert,

				    DATE_FORMAT(STR_TO_DATE(bw_reporte_carga_plantacion.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    bw_inventario_semilla.nombre_semilla,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario

				FROM

				    bw_reporte_carga_plantacion

				        INNER JOIN

				    bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_reporte_carga_plantacion.cod_inventario_semilla)

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_reporte_carga_plantacion.user_insert)

				WHERE

				    bw_reporte_carga_plantacion.cod_plantacion = :cod_plantacion;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_plantacion",  $cod_plantacion);

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

	 * Funciones para obtener listado de bloques de una plantación

	 */

	function plan_cargar_modal_load_repor_plantacion($cod_reporte)
	{

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

		$SQL = "SELECT 

				    cod_reporte,

				    cod_plantacion,

				    cod_bloques_plantacion,

				    cod_inventario_semilla,

				    num_orden_compra,

				    DATE_FORMAT(STR_TO_DATE(fecha_orden,'%Y-%m-%d'),'%m-%d-%Y') as fecha_orden,

				    num_lote,

				    num_lote_ranch,

				    inicial_size_harvest,

				    final_size_harvest,

				    dark_green_color,

				    yellow_leaves,

				    weeds,

				    optimal_soil_water_capacity,

				    right_density,

				    other_defects,

				    size_range_porcentage,

				    inicial_size_range,

				    final_size_range,

				    select_size_range,

				    size_range1,

				    select_size_range1,

				    size_range2,

				    select_size_range2,

				    other_defects_ha,

				    select_other_defects_ha,

				    dew_leaf,

				    inicial_time_harvest,

				    final_time_harvest,

				    temperature_product,

				    inicial_average_tote_weight_reported,

				    final_average_tote_weight_reported,

				    real_average_tote_weight,

				    time_receiving,

				    total_load_lbs_goal,

				    load_weight_received,

				    average_tote_weight,

				    temperature_receiving,

				    time_vacuum_cooler,

				    temperature_vacuum_cooler,

				    hydrocooling,

				    time_pickup,

				    tlc,

				    vacuum_cooler,

				    pickup_truck_checkin,

				    number_cut,

				    bw_reporte_carga_plantacion.activo,

				    bw_reporte_carga_plantacion.user_insert,

				    DATE_FORMAT(STR_TO_DATE(bw_reporte_carga_plantacion.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    bw_inventario_semilla.nombre_semilla,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario

				FROM

				    bw_reporte_carga_plantacion

				        INNER JOIN

				    bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_reporte_carga_plantacion.cod_inventario_semilla)

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_reporte_carga_plantacion.user_insert)

				WHERE

				    bw_reporte_carga_plantacion.cod_reporte = :cod_reporte;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_reporte",  $cod_reporte);

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

      * Agrega una zona de rociado a una plantacion

      */

	function plan_agregar_zona_rociado_plantacion(
		$codigo_plantacion,

		$cod_zona_rociado,

		$cod_bloques_rociado,

		$cod_tipo_zona,

		$cod_quimico_rociadores,

		$cantidad_quimico,

		$cod_unidad_medida2,

		$fecha_rociado,

		$user_insert
	) {

		$SQL = "CALL plan_agregar_zona_rociado_plantacion(
										:codigo_plantacion,

										:cod_zona_rociado,

										:cod_bloques_rociado,

										:cod_tipo_zona,

										:cod_quimico_rociadores,

										:cantidad_quimico,

										:cod_unidad_medida2,

										:fecha_rociado,

										:user_insert)";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":codigo_plantacion",  $codigo_plantacion);

		$stmt->bindParam(":cod_zona_rociado",  $cod_zona_rociado);

		$stmt->bindParam(":cod_bloques_rociado",  $cod_bloques_rociado);

		$stmt->bindParam(":cod_tipo_zona",  $cod_tipo_zona);

		$stmt->bindParam(":cod_quimico_rociadores",  $cod_quimico_rociadores);

		$stmt->bindParam(":cantidad_quimico",  $cantidad_quimico);

		$stmt->bindParam(":cod_unidad_medida2",  $cod_unidad_medida2);

		$stmt->bindParam(":fecha_rociado",  $fecha_rociado);

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

	 * Funciones para obtener listado de zonas rociadas de una plantación

	 */

	function plan_listado_zonas_rociado_plantacion($cod_plantacion)
	{

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

		$SQL = "SELECT 

				    bw_plantaciones_rociado.cod_rociado,

				    bw_plantaciones_rociado.cod_zona,

				    bw_plantaciones_rociado.cod_tipo_zona,

				    bw_plantaciones_rociado.cod_inventario_quimico,

				    bw_plantaciones_rociado.cantidad_quimico,

				    bw_plantaciones_rociado.cod_unidad_medida,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_rociado.fecha_rociado,'%Y-%m-%d'),'%m-%d-%Y') as fecha_rociado,

				    bw_plantaciones_rociado.user_insert,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_rociado.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    GROUP_CONCAT(DISTINCT bw_zonas.zona

				        SEPARATOR '<br>') AS zonas,

				    (SELECT 

				            GROUP_CONCAT(CONCAT(bw_zonas.zona,

				                            '-',

				                            CONCAT(IF(bw_bloques.clave_bloque != '',

									            CONCAT(bw_bloques.clave_bloque, '-'),

									            ''),

									        bw_bloques.nombre_bloque))

				                    SEPARATOR '<br>')

				        FROM

				            bw_bloques

				                INNER JOIN

				            bw_zonas ON (bw_zonas.cod_zona = bw_bloques.cod_zona)

				        WHERE

				            FIND_IN_SET(bw_bloques.cod_bloque,

				                    bw_plantaciones_rociado.cod_bloques)) AS bloques,

				    GROUP_CONCAT(DISTINCT bw_tipo_zona_rociado.tipo_zona

				        SEPARATOR '<br>') AS zonas_rociado,

				    bw_inventario_quimicos.nombre_quimico,

				    ug_unidades_medida.unidad_medida,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario

				FROM

				    bw_plantaciones_rociado

				        INNER JOIN

				    bw_zonas ON ( FIND_IN_SET(bw_zonas.cod_zona,bw_plantaciones_rociado.cod_zona))

				        INNER JOIN

				    bw_tipo_zona_rociado ON ( FIND_IN_SET(bw_tipo_zona_rociado.cod_tipo_zona,bw_plantaciones_rociado.cod_tipo_zona))

				        INNER JOIN

				    bw_inventario_quimicos ON (bw_inventario_quimicos.cod_inventario = bw_plantaciones_rociado.cod_inventario_quimico)

				        INNER JOIN

				    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_plantaciones_rociado.cod_unidad_medida)

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_plantaciones_rociado.user_insert)

				WHERE

				    bw_plantaciones_rociado.activo = 1

				        AND bw_plantaciones_rociado.cod_plantacion = :cod_plantacion

				GROUP BY bw_plantaciones_rociado.cod_plantacion, bw_plantaciones_rociado.cod_rociado

				ORDER BY bw_plantaciones_rociado.date_insert ASC;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_plantacion",  $cod_plantacion);

		try {

			$stmt->execute();

			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {

			$resultado = $e->getMessage();
		}

		$stmt->closeCursor();

		return $resultado;
	}

	/* Funcion que retorna los usuarios especifico de una finca (Empresa) especifica*/
	public function usu_listado_usuarios_por_empresa($cod_info_empresa)
	{


		$SQL = "SELECT 
					cod_usuario,
					cod_info_empresa,
					CONCAT(nombre_1, ' ', apellido_1) AS nombre
				FROM
					usu_usuarios
				WHERE
					activo = 1
				AND cod_info_empresa LIKE '%" . $cod_info_empresa . "%' 
				AND cod_usuario NOT IN (SELECT 
					bw_plantaciones_recomendados.cod_usuario
				FROM
					bw_plantaciones_recomendados
				where cod_info_empresa = :cod_info_empresa
				);";

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

      * Guarda a un usuario recomendado

      */

	function plan_guardar_recomendado(
		$cod_info_empresa,
		$usuario_finca_recomendado,
		$motivo_recomendado,
		$user_insert
	) {

		$SQL = "INSERT INTO bw_plantaciones_recomendados
							(cod_info_empresa,
							cod_usuario,
							motivo,
							user_insert)
							VALUES
							(:cod_info_empresa,
							:usuario_finca_recomendado,
							:motivo_recomendado,
							:user_insert);";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);

		$stmt->bindParam(":usuario_finca_recomendado",  $usuario_finca_recomendado);

		$stmt->bindParam(":motivo_recomendado",  $motivo_recomendado);

		$stmt->bindParam(":user_insert",  $user_insert);

		try {

			$stmt->execute();

			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$resultado = "0|Recommended successfully saved.|" . $this->db_conexion->lastInsertId();

			//$resultado = $stmt;

		} catch (PDOException $e) {

			$resultado = $e->getMessage();
		}

		$stmt->closeCursor();

		return $resultado;
	}


	/* Funcion que retorna los usuarios especifico de una finca (Empresa) especifica*/
	public function usu_cod_info_empres_todos_usuario()
	{
		$SQL = "SELECT 
            cod_info_empresa
        FROM
            usu_usuarios
        WHERE
            activo = 1";

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

	 * Funciones que retorna todos los usuarios que han sido recomendado

	 */

	function plan_listado_recomendado()
	{
		$SQL = "SELECT 
		bw_plantaciones_recomendados.cod_info_empresa,
		bw_plantaciones_recomendados.cod_usuario,
		bw_plantaciones_recomendados.motivo,
		bw_plantaciones_recomendados.activo,
		bw_plantaciones_recomendados.cod_recomendado,
			(SELECT 
					CONCAT(nombre_1, ' ', apellido_1)
				FROM
					usu_usuarios
				WHERE
					bw_plantaciones_recomendados.cod_usuario = usu_usuarios.cod_usuario) AS nombre_usuario_recomendado,
			(SELECT 
					nombre_empresa
				FROM
					bw_info_empresa
				WHERE
					bw_plantaciones_recomendados.cod_info_empresa = bw_info_empresa.cod_info_empresa) AS finca
			FROM
				bw_plantaciones_recomendados
			ORDER BY nombre_usuario_recomendado ASC;
		";

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

      * Cambia el flag de activo de los proveedores

      */

	function plan_cambiar_estado_recomendado($cod_recomendado, $flag_activo)
	{

		$SQL = "UPDATE bw_plantaciones_recomendados
					SET activo = :flag_activo
				WHERE bw_plantaciones_recomendados.cod_recomendado = :cod_recomendado;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_recomendado",  $cod_recomendado);

		$stmt->bindParam(":flag_activo",  $flag_activo);


		try {

			$stmt->execute();

			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$resultado = "0|Recommended successfully updated.|" . $this->db_conexion->lastInsertId();

			//$resultado = $stmt;

		} catch (PDOException $e) {

			$resultado = $e->getMessage();
		}

		$stmt->closeCursor();

		return $resultado;
	}

	/*

	 * Funciones que retorna todos los usuarios que han sido recomendado para una finca especifica

	 */

	function plan_listado_recomendado_por_finca($cod_info_empresa)
	{
		// Descomentar para hacer una prueba rapid
		// Usar la siguiente url para las pruebas: https://bwfarming.com/mod_plantaciones/funciones/plan_listado_usuarios_por_finca.php
		// $cod_info_empresa = "1";
		$SQL = "SELECT 
		bw_plantaciones_recomendados.cod_recomendado,
		bw_plantaciones_recomendados.cod_info_empresa,
		bw_plantaciones_recomendados.cod_usuario,
		bw_plantaciones_recomendados.motivo,
		bw_plantaciones_recomendados.activo,
		bw_plantaciones_recomendados.cod_recomendado,
			(SELECT 
					CONCAT(nombre_1, ' ', apellido_1)
				FROM
					usu_usuarios
				WHERE
					bw_plantaciones_recomendados.cod_usuario = usu_usuarios.cod_usuario) AS nombre,
			(SELECT 
					nombre_empresa
				FROM
					bw_info_empresa
				WHERE
					bw_plantaciones_recomendados.cod_info_empresa = bw_info_empresa.cod_info_empresa) AS finca
			FROM
				bw_plantaciones_recomendados
			WHERE bw_plantaciones_recomendados.activo = 1 AND bw_plantaciones_recomendados.cod_info_empresa = :cod_info_empresa 
			ORDER BY nombre ASC;
		";

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
}
