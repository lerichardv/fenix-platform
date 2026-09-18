<?php
/*
 * Funciones para obtener información del módulo de control de calidad de la base de datos.
 * 
 * @author      Jairo Bonilla
 * @date        2019-01-10  
 */
 
class db_control_calidad{ 
	public $db_conexion;
	
	function __construct(){
		$this->db_conexion = new db_lion();
		$this->db_conexion = $this->db_conexion->dbConnect();
	}


	/*
      * Listado de productos de acuerdo a pais y estado/departamento
      */
    function qua_listado_productos_por_pais_departamento($cod_pais,
									$cod_departamento){
        $SQL = "SELECT 
				    qua_productos.cod_producto,
				    qua_productos.nombre_producto,
				    qua_productos.cod_pais,
				    qua_productos.cod_departamento,
				    qua_productos.activo,
				    qua_productos.user_insert,
				    DATE_FORMAT(STR_TO_DATE(qua_productos.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,
				    geo_paises.pais,
				    geo_departamentos.departamento,
				    CONCAT(usu_usuarios.nombre_1,
				            ' ',
				            usu_usuarios.apellido_1) AS nombre_usuario
				FROM
				    qua_productos
				        INNER JOIN
				    geo_paises ON (geo_paises.cod_pais = qua_productos.cod_pais)
				        INNER JOIN
				    geo_departamentos ON (geo_departamentos.cod_pais = qua_productos.cod_pais
				        AND geo_departamentos.cod_departamento = qua_productos.cod_departamento)
				        INNER JOIN
				    usu_usuarios ON (usu_usuarios.cod_usuario = qua_productos.user_insert)
				WHERE
				    qua_productos.cod_pais = :cod_pais
				        AND qua_productos.cod_departamento = :cod_departamento
				ORDER BY qua_productos.nombre_producto ASC;";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_pais",  $cod_pais);
        $stmt->bindParam(":cod_departamento",  $cod_departamento);
        try{
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$resultado = $stmt;
        }
        catch(PDOException $e)
        {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

	/*
      * Muestra la información de un producto de acuerdo a su código
      */
    function qua_obtener_info_producto($cod_producto){
        $SQL = "SELECT 
				    qua_productos.cod_producto,
				    qua_productos.nombre_producto,
				    qua_productos.cod_pais,
				    qua_productos.cod_departamento,
				    qua_productos.activo,
				    qua_productos.user_insert,
				    DATE_FORMAT(STR_TO_DATE(qua_productos.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,
				    geo_paises.pais,
				    geo_departamentos.departamento,
				    CONCAT(usu_usuarios.nombre_1,
				            ' ',
				            usu_usuarios.apellido_1) AS nombre_usuario
				FROM
				    qua_productos
				        INNER JOIN
				    geo_paises ON (geo_paises.cod_pais = qua_productos.cod_pais)
				        INNER JOIN
				    geo_departamentos ON (geo_departamentos.cod_pais = qua_productos.cod_pais
				        AND geo_departamentos.cod_departamento = qua_productos.cod_departamento)
				        INNER JOIN
				    usu_usuarios ON (usu_usuarios.cod_usuario = qua_productos.user_insert)
				WHERE
				    qua_productos.cod_producto = :cod_producto
				ORDER BY qua_productos.nombre_producto ASC;";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_producto",  $cod_producto);
        try{
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$resultado = $stmt;
        }
        catch(PDOException $e)
        {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }


	/*
      * Guarda un producto
      */
    function qua_guardar_producto($codigo_producto,
										$nombre_producto,
										$cod_pais,
										$cod_departamento,
                            			$user_insert){
        $SQL = "CALL qua_guardar_producto(:codigo_producto,
										:nombre_producto,
										:cod_pais,
										:cod_departamento,
                                		:user_insert)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":codigo_producto",  $codigo_producto);
        $stmt->bindParam(":nombre_producto",  $nombre_producto);
        $stmt->bindParam(":cod_pais",  $cod_pais);
        $stmt->bindParam(":cod_departamento",  $cod_departamento);
        $stmt->bindParam(":user_insert",  $user_insert);
        try{
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$resultado = $stmt;
        } 
        catch(PDOException $e) 
        {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

	/*
      * Cambia el flag de activo de producto
      */
    function qua_cambiar_estado_producto($cod_producto,
										$flag_activo,
                            			$user_insert){
        $SQL = "CALL qua_cambiar_estado_producto(:cod_producto,
										:flag_activo,
                                		:user_insert)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_producto",  $cod_producto);
        $stmt->bindParam(":flag_activo",  $flag_activo);
        $stmt->bindParam(":user_insert",  $user_insert);
        try{
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$resultado = $stmt;
        } 
        catch(PDOException $e) 
        {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }


    /*
      * Listado de cuartos fríos de acuerdo a pais y estado/departamento
      */
    function qua_listado_cuartos_frios_por_pais_departamento($cod_pais,
                                    $cod_departamento){
        $SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->execute();
        $SQL = "SELECT 
                    qua_cuartos_frios.cod_cuarto,
                    qua_cuartos_frios.nombre_cuarto,
                    qua_cuartos_frios.cod_pais,
                    qua_cuartos_frios.cod_departamento,
                    qua_cuartos_frios.activo,
                    qua_cuartos_frios.user_insert,
                    DATE_FORMAT(STR_TO_DATE(qua_cuartos_frios.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,
                    geo_paises.pais,
                    geo_departamentos.departamento,
                    CONCAT(usu_usuarios.nombre_1,
                            ' ',
                            usu_usuarios.apellido_1) AS nombre_usuario
                FROM
                    qua_cuartos_frios
                        INNER JOIN
                    geo_paises ON (geo_paises.cod_pais = qua_cuartos_frios.cod_pais)
                        INNER JOIN
                    geo_departamentos ON (geo_departamentos.cod_pais = qua_cuartos_frios.cod_pais
                        AND geo_departamentos.cod_departamento = qua_cuartos_frios.cod_departamento)
                        INNER JOIN
                    usu_usuarios ON (usu_usuarios.cod_usuario = qua_cuartos_frios.user_insert)
                WHERE
                    qua_cuartos_frios.cod_pais = :cod_pais
                        AND qua_cuartos_frios.cod_departamento = :cod_departamento
                ORDER BY qua_cuartos_frios.nombre_cuarto ASC;";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_pais",  $cod_pais);
        $stmt->bindParam(":cod_departamento",  $cod_departamento);
        try{
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$resultado = $stmt;
        }
        catch(PDOException $e)
        {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }


    /*
      * Listado de cuartos fríos de acuerdo a pais y estado/departamento
      */
    function qua_graficos_control_calidad($cod_pais,
                                    $cod_departamento){
        $SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->execute();
        $SQL = "SELECT 
                    qua_maestro_control_calidad.cod_control_calidad,
                    DATE_FORMAT(STR_TO_DATE(qua_maestro_control_calidad.fecha,'%Y-%m-%d'),'%m-%d-%Y') as fecha,
                    qua_maestro_control_calidad.cod_pais,
                    qua_maestro_control_calidad.cod_departamento,
                    qua_maestro_control_calidad.cod_producto,
                    qua_maestro_control_calidad.num_orden_compra,
                    qua_maestro_control_calidad.tiempo,
                    qua_maestro_control_calidad.temperatura_actual,
                    qua_maestro_control_calidad.temperatura_establecida,
                    qua_maestro_control_calidad.temperatura_minima,
                    qua_maestro_control_calidad.temperatura_maxima,
                    qua_maestro_control_calidad.temperatura_media,
                    qua_detalle_control_calidad.cod_cuarto_frio,
                    qua_cuartos_frios.nombre_cuarto,
                    qua_detalle_control_calidad.cod_seccion,
                    qua_secciones_cuarto_frio.nombre_seccion,
                    qua_productos.cod_producto,
                    qua_productos.nombre_producto,
                    GROUP_CONCAT(CONCAT('[',
                                HOUR(qua_detalle_control_calidad.tiempo),
                                ',',
                                qua_detalle_control_calidad.valor,
                                ']')
                        SEPARATOR ',') AS temperatura_cuarto
                FROM
                    qua_maestro_control_calidad
                        INNER JOIN
                    qua_detalle_control_calidad ON (qua_detalle_control_calidad.cod_control_calidad = qua_maestro_control_calidad.cod_control_calidad)
                        INNER JOIN
                    qua_productos ON (qua_productos.cod_producto = qua_maestro_control_calidad.cod_producto)
                        INNER JOIN
                    qua_cuartos_frios ON (qua_cuartos_frios.cod_cuarto = qua_detalle_control_calidad.cod_cuarto_frio)
                        INNER JOIN
                    qua_secciones_cuarto_frio ON (qua_secciones_cuarto_frio.cod_seccion = qua_detalle_control_calidad.cod_seccion)
                WHERE
                    qua_maestro_control_calidad.cod_pais = :cod_pais
                        AND qua_maestro_control_calidad.cod_departamento = :cod_departamento
                        /*AND qua_maestro_control_calidad.cod_producto = 1*/
                        AND qua_maestro_control_calidad.fecha = CURDATE()
                GROUP BY qua_detalle_control_calidad.cod_seccion
                ORDER BY qua_detalle_control_calidad.tiempo DESC;";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_pais",  $cod_pais);
        $stmt->bindParam(":cod_departamento",  $cod_departamento);
        try{
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$resultado = $stmt;
        }
        catch(PDOException $e)
        {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }


	/*
      * Listado de cuartos fríos de acuerdo a pais y estado/departamento
      */
    function qua_graficos_temperatura_actual_seccion_cuarto_frio($cod_pais,
									$cod_departamento,
                                    $cod_cuarto_frio,
                                    $cod_seccion){
        $SQL = "SELECT DISTINCT
                    qua_maestro_control_calidad.cod_control_calidad,
                    DATE_FORMAT(STR_TO_DATE(qua_maestro_control_calidad.fecha,'%Y-%m-%d'),'%m-%d-%Y') as fecha,
                    qua_maestro_control_calidad.cod_pais,
                    qua_maestro_control_calidad.cod_departamento,
                    qua_maestro_control_calidad.cod_producto,
                    qua_maestro_control_calidad.num_orden_compra,
                    qua_maestro_control_calidad.tiempo,
                    qua_maestro_control_calidad.temperatura_actual,
                    qua_maestro_control_calidad.temperatura_establecida,
                    qua_maestro_control_calidad.temperatura_minima,
                    qua_maestro_control_calidad.temperatura_maxima,
                    qua_maestro_control_calidad.temperatura_media,
                    qua_detalle_control_calidad.cod_cuarto_frio,
                    qua_cuartos_frios.nombre_cuarto,
                    qua_detalle_control_calidad.cod_seccion,
                    qua_secciones_cuarto_frio.nombre_seccion,
                    qua_productos.cod_producto,
                    qua_productos.nombre_producto,
                    HOUR(qua_detalle_control_calidad.tiempo) AS tiempo_cuarto,
                    qua_detalle_control_calidad.valor AS temperatura_cuarto
                FROM
                    qua_maestro_control_calidad
                        INNER JOIN
                    qua_detalle_control_calidad ON (qua_detalle_control_calidad.cod_control_calidad = qua_maestro_control_calidad.cod_control_calidad)
                        INNER JOIN
                    qua_productos ON (qua_productos.cod_producto = qua_maestro_control_calidad.cod_producto)
                        INNER JOIN
                    qua_cuartos_frios ON (qua_cuartos_frios.cod_cuarto = qua_detalle_control_calidad.cod_cuarto_frio)
                        INNER JOIN
                    qua_secciones_cuarto_frio ON (qua_secciones_cuarto_frio.cod_seccion = qua_detalle_control_calidad.cod_seccion)
                WHERE
                    qua_maestro_control_calidad.cod_pais = :cod_pais
                        AND qua_maestro_control_calidad.cod_departamento = :cod_departamento
                        AND qua_maestro_control_calidad.fecha = CURDATE()
                        AND qua_detalle_control_calidad.cod_seccion = :cod_seccion
                ORDER BY HOUR(qua_detalle_control_calidad.tiempo) DESC
                LIMIT 1;";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_pais",  $cod_pais);
        $stmt->bindParam(":cod_departamento",  $cod_departamento);
        $stmt->bindParam(":cod_seccion",  $cod_seccion);
        try{
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$resultado = $stmt;
        }
        catch(PDOException $e)
        {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

	/*
      * Listado de cuartos fríos de acuerdo a pais y estado/departamento
      */
    function qua_obtener_info_cuarto_frio($cod_cuarto_frio){
        $SQL = "SELECT 
				    qua_cuartos_frios.cod_cuarto,
				    qua_cuartos_frios.nombre_cuarto,
				    qua_cuartos_frios.cod_pais,
				    qua_cuartos_frios.cod_departamento,
				    qua_cuartos_frios.activo,
				    qua_cuartos_frios.user_insert,
				    DATE_FORMAT(STR_TO_DATE(qua_cuartos_frios.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,
				    geo_paises.pais,
				    geo_departamentos.departamento,
				    CONCAT(usu_usuarios.nombre_1,
				            ' ',
				            usu_usuarios.apellido_1) AS nombre_usuario
				FROM
				    qua_cuartos_frios
				        INNER JOIN
				    geo_paises ON (geo_paises.cod_pais = qua_cuartos_frios.cod_pais)
				        INNER JOIN
				    geo_departamentos ON (geo_departamentos.cod_pais = qua_cuartos_frios.cod_pais
				        AND geo_departamentos.cod_departamento = qua_cuartos_frios.cod_departamento)
				        INNER JOIN
				    usu_usuarios ON (usu_usuarios.cod_usuario = qua_cuartos_frios.user_insert)
				WHERE
				    qua_cuartos_frios.cod_cuarto = :cod_cuarto_frio
				ORDER BY qua_cuartos_frios.nombre_cuarto ASC;";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_cuarto_frio",  $cod_cuarto_frio);
        try{
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$resultado = $stmt;
        }
        catch(PDOException $e)
        {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

	/*
      * Listado de cuartos fríos de acuerdo a pais y estado/departamento
      */
    function qua_obtener_listado_secciones_cuarto_frio($cod_cuarto_frio){
        $SQL = "SELECT 
				    qua_secciones_cuarto_frio.cod_seccion,
				    qua_secciones_cuarto_frio.nombre_seccion,
				    qua_secciones_cuarto_frio.activo,
				    qua_secciones_cuarto_frio.user_insert,
				    DATE_FORMAT(STR_TO_DATE(qua_secciones_cuarto_frio.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,
				    CONCAT(usu_usuarios.nombre_1,
				            ' ',
				            usu_usuarios.apellido_1) AS nombre_usuario
				FROM
				    qua_secciones_cuarto_frio
				        INNER JOIN
				    usu_usuarios ON (usu_usuarios.cod_usuario = qua_secciones_cuarto_frio.user_insert)
				WHERE
				    qua_secciones_cuarto_frio.cod_cuarto_frio = :cod_cuarto_frio
				ORDER BY qua_secciones_cuarto_frio.nombre_seccion ASC;";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_cuarto_frio",  $cod_cuarto_frio);
        try{
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$resultado = $stmt;
        }
        catch(PDOException $e)
        {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }


	/*
      * Guarda un cuarto frío
      */
    function qua_guardar_cuarto_frio($codigo_cuarto_frio,
										$nombre_cuarto,
										$cod_pais,
										$cod_departamento,
                            			$user_insert){
        $SQL = "CALL qua_guardar_cuarto_frio(:codigo_cuarto_frio,
										:nombre_cuarto,
										:cod_pais,
										:cod_departamento,
                                		:user_insert)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":codigo_cuarto_frio",  $codigo_cuarto_frio);
        $stmt->bindParam(":nombre_cuarto",  $nombre_cuarto);
        $stmt->bindParam(":cod_pais",  $cod_pais);
        $stmt->bindParam(":cod_departamento",  $cod_departamento);
        $stmt->bindParam(":user_insert",  $user_insert);
        try{
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$resultado = $stmt;
        } 
        catch(PDOException $e) 
        {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

	/*
      * Cambia el flag de activo de cuarto frío
      */
    function qua_cambiar_estado_cuarto_frio($cod_cuarto,
										$flag_activo,
                            			$user_insert){
        $SQL = "CALL qua_cambiar_estado_cuarto_frio(:cod_cuarto,
										:flag_activo,
                                		:user_insert)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_cuarto",  $cod_cuarto);
        $stmt->bindParam(":flag_activo",  $flag_activo);
        $stmt->bindParam(":user_insert",  $user_insert);
        try{
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$resultado = $stmt;
        } 
        catch(PDOException $e) 
        {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }


	/*
      * Guarda una seccion
      */
    function qua_guardar_seccion($codigo_cuarto_frio,
										$nombre_seccion,
                            			$user_insert){
        $SQL = "CALL qua_guardar_seccion(:codigo_cuarto_frio,
										:nombre_seccion,
                                		:user_insert)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":codigo_cuarto_frio",  $codigo_cuarto_frio);
        $stmt->bindParam(":nombre_seccion",  $nombre_seccion);
        $stmt->bindParam(":user_insert",  $user_insert);
        try{
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$resultado = $stmt;
        } 
        catch(PDOException $e) 
        {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

	/*
      * Cambia el flag de activo de una sección
      */
    function qua_cambiar_estado_seccion($cod_seccion,
										$flag_activo,
                            			$user_insert){
        $SQL = "CALL qua_cambiar_estado_seccion(:cod_seccion,
										:flag_activo,
                                		:user_insert)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_seccion",  $cod_seccion);
        $stmt->bindParam(":flag_activo",  $flag_activo);
        $stmt->bindParam(":user_insert",  $user_insert);
        try{
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$resultado = $stmt;
        } 
        catch(PDOException $e) 
        {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }


	/*
      * Listado de cuartos fríos de acuerdo a pais y estado/departamento
      */
    function qua_listado_control_calidad_por_pais_departamento($cod_pais,
									$cod_departamento){
        $SQL = "SELECT 
				    qua_maestro_control_calidad.cod_control_calidad,
				    DATE_FORMAT(STR_TO_DATE(qua_maestro_control_calidad.fecha,'%Y-%m-%d'),'%m-%d-%Y') as fecha,
				    qua_maestro_control_calidad.cod_pais,
				    qua_maestro_control_calidad.cod_departamento,
				    qua_maestro_control_calidad.cod_producto,
				    qua_maestro_control_calidad.num_orden_compra,
				    qua_maestro_control_calidad.tiempo,
				    qua_maestro_control_calidad.temperatura_actual,
				    qua_maestro_control_calidad.temperatura_establecida,
				    qua_maestro_control_calidad.temperatura_minima,
				    qua_maestro_control_calidad.temperatura_maxima,
                    qua_maestro_control_calidad.temperatura_media,
                    qua_maestro_control_calidad.tiempo_preshipment,
                    qua_maestro_control_calidad.num_lote,
                    qua_maestro_control_calidad.dias,
                    qua_maestro_control_calidad.middle_temp1,
                    qua_maestro_control_calidad.middle_temp2,
                    qua_maestro_control_calidad.middle_temp3,
				    qua_maestro_control_calidad.middle_temp4,
				    qua_maestro_control_calidad.activo,
				    qua_maestro_control_calidad.user_insert,
				    DATE_FORMAT(STR_TO_DATE(qua_maestro_control_calidad.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,
				    geo_paises.pais,
				    geo_departamentos.departamento,
				    qua_productos.nombre_producto,
				    CONCAT(usu_usuarios.nombre_1,
				            ' ',
				            usu_usuarios.apellido_1) AS nombre_usuario
				FROM
				    qua_maestro_control_calidad
				        INNER JOIN
				    geo_paises ON (geo_paises.cod_pais = qua_maestro_control_calidad.cod_pais)
				        INNER JOIN
				    geo_departamentos ON (geo_departamentos.cod_pais = qua_maestro_control_calidad.cod_pais
				        AND geo_departamentos.cod_departamento = qua_maestro_control_calidad.cod_departamento)
				        INNER JOIN
				    usu_usuarios ON (usu_usuarios.cod_usuario = qua_maestro_control_calidad.user_insert)
				        INNER JOIN
				    qua_productos ON (qua_productos.cod_producto = qua_maestro_control_calidad.cod_producto)
				WHERE
				    qua_maestro_control_calidad.cod_pais = :cod_pais
				        AND qua_maestro_control_calidad.cod_departamento = :cod_departamento
				ORDER BY qua_productos.date_insert DESC;";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_pais",  $cod_pais);
        $stmt->bindParam(":cod_departamento",  $cod_departamento);
        try{
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$resultado = $stmt;
        }
        catch(PDOException $e)
        {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

	/*
      * Listado de cuartos fríos de acuerdo a pais y estado/departamento
      */
    function qua_obtener_info_control_calidad($cod_control_calidad){
        $SQL = "SELECT 
				    qua_maestro_control_calidad.cod_control_calidad,
				    DATE_FORMAT(STR_TO_DATE(qua_maestro_control_calidad.fecha,'%Y-%m-%d'),'%m-%d-%Y') as fecha,
				    qua_maestro_control_calidad.cod_pais,
				    qua_maestro_control_calidad.cod_departamento,
				    qua_maestro_control_calidad.cod_producto,
				    qua_maestro_control_calidad.num_orden_compra,
				    qua_maestro_control_calidad.tiempo,
				    qua_maestro_control_calidad.temperatura_actual,
				    qua_maestro_control_calidad.temperatura_establecida,
				    qua_maestro_control_calidad.temperatura_minima,
				    qua_maestro_control_calidad.temperatura_maxima,
				    qua_maestro_control_calidad.temperatura_media,
                    qua_maestro_control_calidad.tiempo_preshipment,
                    qua_maestro_control_calidad.num_lote,
                    qua_maestro_control_calidad.dias,
                    qua_maestro_control_calidad.middle_temp1,
                    qua_maestro_control_calidad.middle_temp2,
                    qua_maestro_control_calidad.middle_temp3,
                    qua_maestro_control_calidad.middle_temp4,
				    qua_maestro_control_calidad.activo,
				    qua_maestro_control_calidad.user_insert,
				    DATE_FORMAT(STR_TO_DATE(qua_maestro_control_calidad.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,
				    geo_paises.pais,
				    geo_departamentos.departamento,
				    qua_productos.nombre_producto,
				    CONCAT(usu_usuarios.nombre_1,
				            ' ',
				            usu_usuarios.apellido_1) AS nombre_usuario
				FROM
				    qua_maestro_control_calidad
				        INNER JOIN
				    geo_paises ON (geo_paises.cod_pais = qua_maestro_control_calidad.cod_pais)
				        INNER JOIN
				    geo_departamentos ON (geo_departamentos.cod_pais = qua_maestro_control_calidad.cod_pais
				        AND geo_departamentos.cod_departamento = qua_maestro_control_calidad.cod_departamento)
				        INNER JOIN
				    usu_usuarios ON (usu_usuarios.cod_usuario = qua_maestro_control_calidad.user_insert)
				        INNER JOIN
				    qua_productos ON (qua_productos.cod_producto = qua_maestro_control_calidad.cod_producto)
				WHERE
				    qua_maestro_control_calidad.cod_control_calidad = :cod_control_calidad
				ORDER BY qua_productos.nombre_producto ASC;";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_control_calidad",  $cod_control_calidad);
        try{
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$resultado = $stmt;
        }
        catch(PDOException $e)
        {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

	/*
      * Listado de cuartos fríos de acuerdo a pais y estado/departamento
      */
    function qua_obtener_listado_detalle_control_calidad($cod_control_calidad){
        $SQL = "SELECT 
				    qua_detalle_control_calidad.cod_detalle,
				    qua_detalle_control_calidad.cod_control_calidad,
				    qua_detalle_control_calidad.cod_cuarto_frio,
				    qua_cuartos_frios.nombre_cuarto,
				    qua_detalle_control_calidad.cod_seccion,
				    qua_secciones_cuarto_frio.nombre_seccion,
				    qua_detalle_control_calidad.tiempo,
                    qua_detalle_control_calidad.valor,
				    qua_detalle_control_calidad.observaciones,
				    qua_detalle_control_calidad.activo,
				    qua_detalle_control_calidad.user_insert,
				    CONCAT(usu_usuarios.nombre_1,
				            ' ',
				            usu_usuarios.apellido_1) AS nombre_usuario,
				    DATE_FORMAT(STR_TO_DATE(qua_detalle_control_calidad.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert
				FROM
				    qua_detalle_control_calidad
				        INNER JOIN
				    qua_cuartos_frios ON (qua_cuartos_frios.cod_cuarto = qua_detalle_control_calidad.cod_cuarto_frio)
				        INNER JOIN
				    qua_secciones_cuarto_frio ON (qua_secciones_cuarto_frio.cod_seccion = qua_detalle_control_calidad.cod_seccion)
				        INNER JOIN
				    usu_usuarios ON (usu_usuarios.cod_usuario = qua_detalle_control_calidad.user_insert)
				WHERE
				    qua_detalle_control_calidad.cod_control_calidad = :cod_control_calidad
				ORDER BY qua_detalle_control_calidad.date_insert DESC;";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_control_calidad",  $cod_control_calidad);
        try{
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$resultado = $stmt;
        }
        catch(PDOException $e)
        {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }


	/*
      * Guarda un control de calidad
      */
    function qua_guardar_control_calidad($codigo_control_calidad,
										$cod_pais,
										$cod_departamento,
										$cod_producto,
										$fecha,
										$num_orden_compra,
										$tiempo,
										$temperatura_actual,
										$temperatura_establecida,
										$temperatura_minima,
										$temperatura_maxima,
										$temperatura_media,
                                        $tiempo_preshipment,
                                        $num_lote,
                                        $dias,
                                        $middle_temp1,
                                        $middle_temp2,
                                        $middle_temp3,
                                        $middle_temp4,
                            			$user_insert){
        $SQL = "CALL qua_guardar_control_calidad(:codigo_control_calidad,
										:cod_pais,
										:cod_departamento,
										:cod_producto,
										:fecha,
										:num_orden_compra,
										:tiempo,
										:temperatura_actual,
										:temperatura_establecida,
										:temperatura_minima,
										:temperatura_maxima,
										:temperatura_media,
                                        :tiempo_preshipment,
                                        :num_lote,
                                        :dias,
                                        :middle_temp1,
                                        :middle_temp2,
                                        :middle_temp3,
                                        :middle_temp4,
                                		:user_insert)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":codigo_control_calidad",  $codigo_control_calidad);
        $stmt->bindParam(":cod_pais",  $cod_pais);
        $stmt->bindParam(":cod_departamento",  $cod_departamento);
        $stmt->bindParam(":cod_producto",  $cod_producto);
        $stmt->bindParam(":fecha",  $fecha);
        $stmt->bindParam(":num_orden_compra",  $num_orden_compra);
        $stmt->bindParam(":tiempo",  $tiempo);
        $stmt->bindParam(":temperatura_actual",  $temperatura_actual);
        $stmt->bindParam(":temperatura_establecida",  $temperatura_establecida);
        $stmt->bindParam(":temperatura_minima",  $temperatura_minima);
        $stmt->bindParam(":temperatura_maxima",  $temperatura_maxima);
        $stmt->bindParam(":temperatura_media",  $temperatura_media);
        $stmt->bindParam(":tiempo_preshipment",  $tiempo_preshipment);
        $stmt->bindParam(":num_lote",  $num_lote);
        $stmt->bindParam(":dias",  $dias);
        $stmt->bindParam(":middle_temp1",  $middle_temp1);
        $stmt->bindParam(":middle_temp2",  $middle_temp2);
        $stmt->bindParam(":middle_temp3",  $middle_temp3);
        $stmt->bindParam(":middle_temp4",  $middle_temp4);
        $stmt->bindParam(":user_insert",  $user_insert);
        try{
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$resultado = $stmt;
        } 
        catch(PDOException $e) 
        {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

	/*
      * Cambia el flag de activo de control de calidad
      */
    function qua_cambiar_estado_control_calidad($cod_cuarto,
										$flag_activo,
                            			$user_insert){
        $SQL = "CALL qua_cambiar_estado_control_calidad(:cod_cuarto,
										:flag_activo,
                                		:user_insert)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_cuarto",  $cod_cuarto);
        $stmt->bindParam(":flag_activo",  $flag_activo);
        $stmt->bindParam(":user_insert",  $user_insert);
        try{
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$resultado = $stmt;
        } 
        catch(PDOException $e) 
        {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }


	/*
      * Guarda una seccion
      */
    function qua_guardar_detalle_control_calidad($codigo_control_calidad,
										$cod_cuarto_frio,
										$cod_seccion,
										$tiempo_detalle,
                                        $valor,
										$observaciones,
                            			$user_insert){
        $SQL = "CALL qua_guardar_detalle_control_calidad(:codigo_control_calidad,
										:cod_cuarto_frio,
										:cod_seccion,
										:tiempo_detalle,
                                        :valor,
										:observaciones,
                                		:user_insert)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":codigo_control_calidad",  $codigo_control_calidad);
        $stmt->bindParam(":cod_cuarto_frio",  $cod_cuarto_frio);
        $stmt->bindParam(":cod_seccion",  $cod_seccion);
        $stmt->bindParam(":tiempo_detalle",  $tiempo_detalle);
        $stmt->bindParam(":valor",  $valor);
        $stmt->bindParam(":observaciones",  $observaciones);
        $stmt->bindParam(":user_insert",  $user_insert);
        try{
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$resultado = $stmt;
        } 
        catch(PDOException $e) 
        {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }


    /*
      * Guarda/Actualiza un load report
      */
    function qua_guardar_load_report($cod_producto,
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
                                    $acres_cosechados,
                                    $comentarios,
                                    $select_yellow_leaves,
                                    $yellow_leaves2,
                                    $user_insert){
        $SQL = "CALL qua_guardar_load_report(:cod_producto,
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
                                            :acres_cosechados,
                                            :comentarios,
                                            :select_yellow_leaves,
                                            :yellow_leaves2,
                                            :user_insert)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_producto",  $cod_producto);
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
        $stmt->bindParam(":acres_cosechados",  $acres_cosechados);
        $stmt->bindParam(":comentarios",  $comentarios);
        $stmt->bindParam(":select_yellow_leaves",  $select_yellow_leaves);
        $stmt->bindParam(":yellow_leaves2",  $yellow_leaves2);
        $stmt->bindParam(":user_insert",  $user_insert);
        try{
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$resultado = $stmt;
        }
        catch(PDOException $e)
        {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }


    /*
     * Funciones para obtener listado de bloques de una plantación
     */
    function qua_listado_load_report(){
        $SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->execute();
        $SQL = "SELECT 
                    cod_reporte,
                    bw_reporte_carga.cod_producto,
                    num_orden_compra,
                    DATE_FORMAT(STR_TO_DATE(fecha_orden,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as fecha_orden,
                    num_lote,
                    num_lote_ranch,
                    inicial_size_harvest,
                    final_size_harvest,
                    dark_green_color,
                    yellow_leaves,
                    select_yellow_leaves,
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
                    acres_cosechados,
                    comentarios,
                    bw_reporte_carga.activo,
                    bw_reporte_carga.user_insert,
                    DATE_FORMAT(STR_TO_DATE(bw_reporte_carga.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,
                    qua_productos.nombre_producto,
                    CONCAT(usu_usuarios.nombre_1,
                            ' ',
                            usu_usuarios.apellido_1) AS nombre_usuario
                FROM
                    bw_reporte_carga
                        INNER JOIN
                    qua_productos ON (qua_productos.cod_producto = bw_reporte_carga.cod_producto)
                        INNER JOIN
                    usu_usuarios ON (usu_usuarios.cod_usuario = bw_reporte_carga.user_insert)
                WHERE
                    bw_reporte_carga.activo = 1
                ORDER BY bw_reporte_carga.date_insert DESC;";
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
     * Funciones para obtener listado de bloques de una plantación
     */
    function qua_listado_reporte_load_report($fecha_inicial,$fecha_final){
        $fecha_inicial .= ' 00:00:00';
        $fecha_final .= ' 23:59:59';
        $SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->execute();
        $SQL = "SELECT 
                    cod_reporte,
                    bw_reporte_carga.cod_producto,
                    num_orden_compra,
                    DATE_FORMAT(STR_TO_DATE(fecha_orden,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as fecha_orden,
                    num_lote,
                    num_lote_ranch,
                    inicial_size_harvest,
                    final_size_harvest,
                    dark_green_color,
                    yellow_leaves,
                    select_yellow_leaves,
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
                    temperature_vacuum_cooler,
                    hydrocooling,
                    DATE_FORMAT(STR_TO_DATE(time_pickup,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as time_pickup,
                    tlc,
                    vacuum_cooler,
                    DATE_FORMAT(STR_TO_DATE(pickup_truck_checkin,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as pickup_truck_checkin,
                    number_cut,
                    acres_cosechados,
                    yellow_leaves2,
                    comentarios,
                    bw_reporte_carga.activo,
                    bw_reporte_carga.user_insert,
                    bw_reporte_carga.date_insert,
                    qua_productos.nombre_producto,
                    CONCAT(usu_usuarios.nombre_1,
                            ' ',
                            usu_usuarios.apellido_1) AS nombre_usuario
                FROM
                    bw_reporte_carga
                        INNER JOIN
                    qua_productos ON (qua_productos.cod_producto = bw_reporte_carga.cod_producto)
                        INNER JOIN
                    usu_usuarios ON (usu_usuarios.cod_usuario = bw_reporte_carga.user_insert)
                WHERE
                    bw_reporte_carga.activo = 1 AND bw_reporte_carga.fecha_orden BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s');";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":fecha_inicial",  $fecha_inicial);
        $stmt->bindParam(":fecha_final",  $fecha_final);
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
     * Funciones para obtener listado de bloques de una plantación
     */
    function plan_cargar_load_repor($cod_reporte){
        $SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->execute();
        $SQL = "SELECT 
                    cod_reporte,
                    bw_reporte_carga.cod_producto,
                    num_orden_compra,
                    DATE_FORMAT(STR_TO_DATE(fecha_orden,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as fecha_orden,
                    num_lote,
                    num_lote_ranch,
                    inicial_size_harvest,
                    final_size_harvest,
                    dark_green_color,
                    yellow_leaves,
                    select_yellow_leaves,
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
                    temperature_vacuum_cooler,
                    hydrocooling,
                    DATE_FORMAT(STR_TO_DATE(time_pickup,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as time_pickup,
                    tlc,
                    vacuum_cooler,
                    DATE_FORMAT(STR_TO_DATE(pickup_truck_checkin,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as pickup_truck_checkin,
                    number_cut,
                    acres_cosechados,
                    yellow_leaves2,
                    comentarios,
                    bw_reporte_carga.activo,
                    bw_reporte_carga.user_insert,
                    DATE_FORMAT(STR_TO_DATE(bw_reporte_carga.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,
                    qua_productos.nombre_producto,
                    CONCAT(usu_usuarios.nombre_1,
                            ' ',
                            usu_usuarios.apellido_1) AS nombre_usuario
                FROM
                    bw_reporte_carga
                        INNER JOIN
                    qua_productos ON (qua_productos.cod_producto = bw_reporte_carga.cod_producto)
                        INNER JOIN
                    usu_usuarios ON (usu_usuarios.cod_usuario = bw_reporte_carga.user_insert)
                WHERE
                    bw_reporte_carga.cod_reporte = :cod_reporte;";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_reporte",  $cod_reporte);
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

}
?>