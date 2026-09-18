<?php

/*

 * Funciones para obtener información general de la base de datos.

 *

 * @author      Jairo Bonilla

 * @date        2018-10-25

 */



class db_configuracion{

	public $db_conexion;



	function __construct(){

		$this->db_conexion = new db_lion();

		$this->db_conexion = $this->db_conexion->dbConnect();

	}





	/*

      * Guarda un item con su detalle de un formulario

      */

    function conf_guardar_item_formulario($codigo_formulario,

										$nombre_item,

										$cod_tipo_item,

										$descripcion_item,

										$opcion1,

										$opcion2,

										$opcion3,

										$opcion4,

										$opcion5,

										$opcion6,

										$opcion7,

										$opcion8,

										$opcion9,

										$opcion10,

										$valor1,

										$valor2,

										$valor3,

										$valor4,

										$valor5,

										$valor6,

										$valor7,

										$valor8,

										$valor9,

										$valor10,

                                	$user_insert){

        $SQL = "CALL conf_guardar_item_formulario(:codigo_formulario,

										:nombre_item,

										:cod_tipo_item,

										:descripcion_item,

										:opcion1,

										:opcion2,

										:opcion3,

										:opcion4,

										:opcion5,

										:opcion6,

										:opcion7,

										:opcion8,

										:opcion9,

										:opcion10,

										:valor1,

										:valor2,

										:valor3,

										:valor4,

										:valor5,

										:valor6,

										:valor7,

										:valor8,

										:valor9,

										:valor10,

                                		:user_insert)";

        $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":codigo_formulario",  $codigo_formulario);

        $stmt->bindParam(":nombre_item",  $nombre_item);

        $stmt->bindParam(":cod_tipo_item",  $cod_tipo_item);

        $stmt->bindParam(":descripcion_item",  $descripcion_item);

        $stmt->bindParam(":opcion1",  $opcion1);

        $stmt->bindParam(":opcion2",  $opcion2);

        $stmt->bindParam(":opcion3",  $opcion3);

        $stmt->bindParam(":opcion4",  $opcion4);

        $stmt->bindParam(":opcion5",  $opcion5);

        $stmt->bindParam(":opcion6",  $opcion6);

        $stmt->bindParam(":opcion7",  $opcion7);

        $stmt->bindParam(":opcion8",  $opcion8);

        $stmt->bindParam(":opcion9",  $opcion9);

        $stmt->bindParam(":opcion10",  $opcion10);

        $stmt->bindParam(":valor1",  $valor1);

        $stmt->bindParam(":valor2",  $valor2);

        $stmt->bindParam(":valor3",  $valor3);

        $stmt->bindParam(":valor4",  $valor4);

        $stmt->bindParam(":valor5",  $valor5);

        $stmt->bindParam(":valor6",  $valor6);

        $stmt->bindParam(":valor7",  $valor7);

        $stmt->bindParam(":valor8",  $valor8);

        $stmt->bindParam(":valor9",  $valor9);

        $stmt->bindParam(":valor10",  $valor10);

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

	 * Funciones para obtener listado de estados de plantaciones disponibles

	 */

	function conf_listado_tipos_items_formularios(){

            $SQL = "SELECT

					    cod_tipo_item, tipo_item

					FROM

					    bw_tipos_items

					WHERE

					    activo = 1

					ORDER BY tipo_item ASC;";

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

	 * Funciones para obtener listado de estados de plantaciones disponibles

	 */

	function conf_listado_formularios_activos(){

            $SQL = "SELECT

					    cod_formulario,

					    nombre_formulario,

					    descripcion_formulario,

					    cod_estado,

					    bw_estados_plantacion.estado_plantacion,

					    puntuacion_minima,

					    puntuacion_maxima,

					    bw_formularios.user_insert,

					    bw_formularios.activo

					FROM

					    bw_formularios

					    INNER JOIN bw_estados_plantacion ON (bw_estados_plantacion.cod_estado_plantacion = bw_formularios.cod_estado)

					;";

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

	 * Funciones para obtener listado de granjas disponibles

	 */

	function conf_listado_granjas_activas(){

        $SQL = "SELECT

				    cod_info_empresa,

				    bw_info_empresa.cod_gerencia,

				    usu_gerencias.gerencia,

				    nombre_empresa,

				    lema_empresa,

				    direccion_linea_1,

				    direccion_linea_2,

				    telefono_empresa,

				    correo_empresa,

				    fax_empresa,

				    descripcion_empresa,

				    logo_empresa,

				    bw_info_empresa.user_insert

				FROM

				    bw_info_empresa

				        INNER JOIN

				    usu_gerencias ON (usu_gerencias.cod_gerencia = bw_info_empresa.cod_gerencia)

				WHERE

				    bw_info_empresa.activo = 1

				ORDER BY gerencia , nombre_empresa ASC;";

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

	 * Funciones para obtener listado de granjas disponibles

	 */

	function conf_listado_granjas_activas_por_granjas($cod_info_empresa){

        $SQL = "SELECT

				    cod_info_empresa,

				    bw_info_empresa.cod_gerencia,

				    usu_gerencias.gerencia,

				    nombre_empresa,

				    lema_empresa,

				    direccion_linea_1,

				    direccion_linea_2,

				    telefono_empresa,

				    correo_empresa,

				    fax_empresa,

				    descripcion_empresa,

				    logo_empresa,

				    bw_info_empresa.user_insert

				FROM

				    bw_info_empresa

				        INNER JOIN

				    usu_gerencias ON (usu_gerencias.cod_gerencia = bw_info_empresa.cod_gerencia)

				WHERE

				    bw_info_empresa.activo = 1

				    AND bw_info_empresa.cod_info_empresa IN (".$cod_info_empresa.")

				ORDER BY gerencia , nombre_empresa ASC;";

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

	 * Funciones para obtener información de una granaja

	 */

	function conf_obtener_info_granja($cod_granja){

        $SQL = "SELECT

				    cod_info_empresa,

				    bw_info_empresa.cod_gerencia,

				    usu_gerencias.gerencia,

				    nombre_empresa,

				    lema_empresa,

				    direccion_linea_1,

				    direccion_linea_2,

				    telefono_empresa,

				    correo_empresa,

				    fax_empresa,

				    descripcion_empresa,

				    logo_empresa,

				    bw_info_empresa.user_insert

				FROM

				    bw_info_empresa

				        INNER JOIN

				    usu_gerencias ON (usu_gerencias.cod_gerencia = bw_info_empresa.cod_gerencia)

				WHERE

				    bw_info_empresa.activo = 1

				        AND bw_info_empresa.cod_info_empresa = :cod_granja;";

	  	$stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_granja",  $cod_granja);

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

      * Guarda una granja de una gerencia

      */

    function conf_guardar_granja($codigo_granja,

								$cod_gerencia,

								$nombre_empresa,

								$telefono_empresa,

								$correo_empresa,

								$lema_empresa,

								$fax_empresa,

								$direccion_linea_1,

								$direccion_linea_2,

								$descripcion_empresa,

								$ext_logo,

                            	$user_insert){

        $SQL = "CALL conf_guardar_granja(:codigo_granja,

										:cod_gerencia,

										:nombre_empresa,

										:telefono_empresa,

										:correo_empresa,

										:lema_empresa,

										:fax_empresa,

										:direccion_linea_1,

										:direccion_linea_2,

										:descripcion_empresa,

										:ext_logo,

                                		:user_insert)";

        $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":codigo_granja",  $codigo_granja);

        $stmt->bindParam(":cod_gerencia",  $cod_gerencia);

        $stmt->bindParam(":nombre_empresa",  $nombre_empresa);

        $stmt->bindParam(":telefono_empresa",  $telefono_empresa);

        $stmt->bindParam(":correo_empresa",  $correo_empresa);

        $stmt->bindParam(":lema_empresa",  $lema_empresa);

        $stmt->bindParam(":fax_empresa",  $fax_empresa);

        $stmt->bindParam(":direccion_linea_1",  $direccion_linea_1);

        $stmt->bindParam(":direccion_linea_2",  $direccion_linea_2);

        $stmt->bindParam(":descripcion_empresa",  $descripcion_empresa);

        $stmt->bindParam(":ext_logo",  $ext_logo);

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

	 * Funciones para obtener listado de tipos de temporada disponibles

	 */

	function conf_listado_tipos_temporada(){

            $SQL = "SELECT

					    cod_tipo_temporada, tipo_temporada, activo

					FROM

					    bw_tipo_temporada

					ORDER BY tipo_temporada ASC;";

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

      * Guarda un tipo de temporada

      */

    function conf_guardar_tipo_temporada($codigo_tipo_temporada,

										$tipo_temporada,

                            			$user_insert){

        $SQL = "CALL conf_guardar_tipo_temporada(:codigo_tipo_temporada,

										:tipo_temporada,

                                		:user_insert)";

        $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":codigo_tipo_temporada",  $codigo_tipo_temporada);

        $stmt->bindParam(":tipo_temporada",  $tipo_temporada);

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

	 * Funciones para obtener información de un tipo de temporada

	 */

	function conf_obtener_info_tipo_temporada($cod_tipo_temporada){

        $SQL = "SELECT

				    cod_tipo_temporada, tipo_temporada, activo

				FROM

				    bw_tipo_temporada

				WHERE

				    cod_tipo_temporada = :cod_tipo_temporada;";

	  	$stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_tipo_temporada",  $cod_tipo_temporada);

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

      * Cambia el flag de activo de los tipos de temporada

      */

    function conf_cambiar_estado_tipo_temporada($codigo_tipo_temporada,

										$flag_activo,

                            			$user_insert){

        $SQL = "CALL conf_cambiar_estado_tipo_temporada(:codigo_tipo_temporada,

										:flag_activo,

                                		:user_insert)";

        $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":codigo_tipo_temporada",  $codigo_tipo_temporada);

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

	 * Funciones para obtener listado de tipos de temporada disponibles

	 */

	function conf_listado_tipos_temporada_activos(){

            $SQL = "SELECT

					    cod_tipo_temporada, tipo_temporada, activo

					FROM

					    bw_tipo_temporada

					ORDER BY tipo_temporada ASC;";

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

	 * Funciones para obtener listado de tipos de temporada disponibles

	 */

	function conf_listado_temporadas(){

            $SQL = "SELECT

					    bw_temporadas.cod_temporada,

					    bw_temporadas.cod_info_empresa,

					    bw_info_empresa.nombre_empresa,

					    bw_temporadas.cod_tipo_temporada,

					    bw_tipo_temporada.tipo_temporada,

					    bw_temporadas.codigo_temporada,

					    DATE_FORMAT(STR_TO_DATE(bw_temporadas.fecha_inicio,'%Y-%m-%d'),'%m-%d-%Y') as fecha_inicio,

					    DATE_FORMAT(STR_TO_DATE(bw_temporadas.fecha_final,'%Y-%m-%d'),'%m-%d-%Y') as fecha_final,

					    bw_temporadas.activo

					FROM

					    bw_temporadas

					        INNER JOIN

					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_temporadas.cod_info_empresa)

					        INNER JOIN

					    bw_tipo_temporada ON (bw_tipo_temporada.cod_tipo_temporada = bw_temporadas.cod_tipo_temporada)

					ORDER BY fecha_inicio , fecha_final DESC, nombre_empresa ASC;";

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

	 * Funciones para obtener listado de tipos de temporada disponibles

	 */

	function conf_listado_temporadas_por_granjas($cod_info_empresa){

            $SQL = "SELECT

					    bw_temporadas.cod_temporada,

					    bw_temporadas.cod_info_empresa,

					    bw_info_empresa.nombre_empresa,

					    bw_temporadas.cod_tipo_temporada,

					    bw_tipo_temporada.tipo_temporada,

					    bw_temporadas.codigo_temporada,

					    DATE_FORMAT(STR_TO_DATE(bw_temporadas.fecha_inicio,'%Y-%m-%d'),'%m-%d-%Y') as fecha_inicio,

					    DATE_FORMAT(STR_TO_DATE(bw_temporadas.fecha_final,'%Y-%m-%d'),'%m-%d-%Y') as fecha_final,

					    bw_temporadas.activo

					FROM

					    bw_temporadas

					        INNER JOIN

					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_temporadas.cod_info_empresa)

					        INNER JOIN

					    bw_tipo_temporada ON (bw_tipo_temporada.cod_tipo_temporada = bw_temporadas.cod_tipo_temporada)

					    WHERE

					    bw_temporadas.cod_info_empresa IN (".$cod_info_empresa.")

					ORDER BY fecha_inicio , fecha_final DESC, nombre_empresa ASC;";

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

	 * Funciones para obtener información de una temporada

	 */

	function conf_obtener_info_temporada($cod_temporada){

            $SQL = "SELECT

					    bw_temporadas.cod_temporada,

					    bw_temporadas.cod_info_empresa,

					    bw_info_empresa.nombre_empresa,

					    bw_temporadas.cod_tipo_temporada,

					    bw_tipo_temporada.tipo_temporada,

					    bw_temporadas.codigo_temporada,

					    DATE_FORMAT(STR_TO_DATE(bw_temporadas.fecha_inicio,'%Y-%m-%d'),'%m-%d-%Y') as fecha_inicio,

					    DATE_FORMAT(STR_TO_DATE(bw_temporadas.fecha_final,'%Y-%m-%d'),'%m-%d-%Y') as fecha_final,

					    bw_temporadas.activo

					FROM

					    bw_temporadas

					        INNER JOIN

					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_temporadas.cod_info_empresa)

					        INNER JOIN

					    bw_tipo_temporada ON (bw_tipo_temporada.cod_tipo_temporada = bw_temporadas.cod_tipo_temporada)

					WHERE bw_temporadas.cod_temporada = :cod_temporada;";

	  	$stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_temporada",  $cod_temporada);

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

      * Guarda una temporada con su información

      */

    function conf_guardar_temporada($cod_temporada,

									$cod_info_empresa,

									$cod_tipo_temporada,

									$codigo_temporada,

									$fecha_inicio,

									$fecha_final,

                        			$user_insert){

        $SQL = "CALL conf_guardar_temporada(:cod_temporada,

										:cod_info_empresa,

										:cod_tipo_temporada,

										:codigo_temporada,

										:fecha_inicio,

										:fecha_final,

                                		:user_insert)";

        $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_temporada",  $cod_temporada);

        $stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);

        $stmt->bindParam(":cod_tipo_temporada",  $cod_tipo_temporada);

        $stmt->bindParam(":codigo_temporada",  $codigo_temporada);

        $stmt->bindParam(":fecha_inicio",  $fecha_inicio);

        $stmt->bindParam(":fecha_final",  $fecha_final);

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

      * Cambia el flag de activo de los tipos de temporada

      */

    function conf_cambiar_estado_temporada($codigo_temporada,

										$flag_activo,

                            			$user_insert){

        $SQL = "CALL conf_cambiar_estado_temporada(:codigo_temporada,

										:flag_activo,

                                		:user_insert)";

        $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":codigo_temporada",  $codigo_temporada);

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

	 * Funciones para obtener listado de periodos fiscales disponibles

	 */

	function conf_listado_periodos_fiscales(){

            $SQL = "SELECT

					    cod_periodo_fiscal,

					    anio_periodo,

					    num_periodo,

					    fecha_inicio,

					    fecha_final,

					    activo

					FROM

					    bw_periodos_fiscales

					ORDER BY anio_periodo DESC, num_periodo ASC;";

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

	 * Funciones para obtener información de un periodo fiscal

	 */

	function conf_obtener_info_periodo_fiscal($cod_periodo_fiscal){

        $SQL = "SELECT

				    cod_periodo_fiscal,

				    anio_periodo,

				    num_periodo,

				    DATE_FORMAT(STR_TO_DATE(fecha_inicio,'%Y-%m-%d'),'%m-%d-%Y') as fecha_inicio,

				    DATE_FORMAT(STR_TO_DATE(fecha_final,'%Y-%m-%d'),'%m-%d-%Y') as fecha_final,

				    activo

				FROM

				    bw_periodos_fiscales

				WHERE bw_periodos_fiscales.cod_periodo_fiscal = :cod_periodo_fiscal;";

	  	$stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_periodo_fiscal",  $cod_periodo_fiscal);

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

      * Guarda un periodo fiscal con su información

      */

    function conf_guardar_periodo_fiscal($codigo_periodo_fiscal,

										$anio_periodo,

										$num_periodo,

										$fecha_inicio,

										$fecha_final,

                        				$user_insert){

        $SQL = "CALL conf_guardar_periodo_fiscal(:codigo_periodo_fiscal,

										:anio_periodo,

										:num_periodo,

										:fecha_inicio,

										:fecha_final,

                                		:user_insert)";

        $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":codigo_periodo_fiscal",  $codigo_periodo_fiscal);

        $stmt->bindParam(":anio_periodo",  $anio_periodo);

        $stmt->bindParam(":num_periodo",  $num_periodo);

        $stmt->bindParam(":fecha_inicio",  $fecha_inicio);

        $stmt->bindParam(":fecha_final",  $fecha_final);

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

      * Cambia el flag de activo de un periodo fiscal

      */

    function conf_cambiar_estado_periodo_fiscal($cod_periodo_fiscal,

										$flag_activo,

                            			$user_insert){

        $SQL = "CALL conf_cambiar_estado_periodo_fiscal(:cod_periodo_fiscal,

										:flag_activo,

                                		:user_insert)";

        $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_periodo_fiscal",  $cod_periodo_fiscal);

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

      * Cambia el flag de activo de un año fiscal

      */

    function conf_cambiar_estado_periodo_fiscal_anio($anio,

										$flag_activo,

                            			$user_insert){

        $SQL = "CALL conf_cambiar_estado_periodo_fiscal_anio(:anio,

										:flag_activo,

                                		:user_insert)";

        $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":anio",  $anio);

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

	 * Funciones para obtener listado de estados de plantaciones disponibles

	 */

	function conf_obtener_info_formulario($cod_formulario){

        $SQL = "SELECT

					    cod_formulario,

					    nombre_formulario,

					    descripcion_formulario,

					    cod_estado,

					    bw_estados_plantacion.estado_plantacion,

					    puntuacion_minima,

					    puntuacion_maxima,

					    bw_formularios.user_insert

					FROM

					    bw_formularios

					    INNER JOIN bw_estados_plantacion ON (bw_estados_plantacion.cod_estado_plantacion = bw_formularios.cod_estado)

					WHERE

					    bw_formularios.activo = 1

                        AND bw_formularios.cod_formulario = :cod_formulario;";

	  	$stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_formulario",  $cod_formulario);

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

	 * Funciones para obtener listado de items de un formulario

	 */

	function conf_obtener_items_formulario($cod_formulario){

        $SQL = "SELECT

				    bw_items_checklist.cod_item,

				    bw_items_checklist.cod_formulario,

				    bw_items_checklist.cod_tipo_item,

				    bw_tipos_items.tipo_item,

				    bw_items_checklist.nombre_item,

				    bw_items_checklist.descripcion_item,

				    bw_items_checklist.activo,

				    (SELECT

				            GROUP_CONCAT(CONCAT(bw_detalle_items_checklist.texto_item, ' - ',bw_detalle_items_checklist.valor_item)

				                    SEPARATOR '<br>')

				        FROM

				            bw_detalle_items_checklist

				        WHERE

				            bw_detalle_items_checklist.cod_item_check_list = bw_items_checklist.cod_item) AS opciones

				FROM

				    bw_items_checklist

				        INNER JOIN

				    bw_tipos_items ON (bw_tipos_items.cod_tipo_item = bw_items_checklist.cod_tipo_item)

				WHERE

				    cod_formulario = :cod_formulario;";

	  	$stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_formulario",  $cod_formulario);

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

      * Cambia el flag de activo de un item de un formulario

      */

    function conf_cambiar_activo_item_formulario($cod_item,

										$flag_activo,

                            			$user_insert){

        $SQL = "CALL conf_cambiar_activo_item_formulario(:cod_item,

										:flag_activo,

                                		:user_insert)";

        $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_item",  $cod_item);

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

      * Cambia el flag de activo de un formulario

      */

    function conf_cambiar_activo_formulario($cod_formulario,

										$flag_activo,

                            			$user_insert){

        $SQL = "CALL conf_cambiar_activo_formulario(:cod_formulario,

										:flag_activo,

                                		:user_insert)";

        $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_formulario",  $cod_formulario);

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

	 * Funciones para obtener listado de opciones de los items de un formulario

	 */

	function conf_obtener_opciones_items_formulario($cod_item){

        $SQL = "SELECT

		            bw_detalle_items_checklist.texto_item,

		            bw_detalle_items_checklist.valor_item,

		            bw_detalle_items_checklist.cod_detalle_item

		        FROM

		            bw_detalle_items_checklist

		        WHERE

		            bw_detalle_items_checklist.cod_item_check_list = :cod_item;";

	  	$stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_item",  $cod_item);

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

	 * Funciones para obtener listado de items activos de un formulario

	 */

	function conf_obtener_items_activos_formulario($cod_formulario){

        $SQL = "SELECT

				    bw_items_checklist.cod_item,

				    bw_items_checklist.cod_formulario,

				    bw_items_checklist.cod_tipo_item,

				    bw_tipos_items.tipo_item,

				    bw_items_checklist.nombre_item,

				    bw_items_checklist.descripcion_item,

				    bw_items_checklist.activo,

				    (SELECT

				            GROUP_CONCAT(CONCAT(bw_detalle_items_checklist.texto_item, ' - ',bw_detalle_items_checklist.valor_item)

				                    SEPARATOR '<br>')

				        FROM

				            bw_detalle_items_checklist

				        WHERE

				            bw_detalle_items_checklist.cod_item_check_list = bw_items_checklist.cod_item) AS opciones

				FROM

				    bw_items_checklist

				        INNER JOIN

				    bw_tipos_items ON (bw_tipos_items.cod_tipo_item = bw_items_checklist.cod_tipo_item)

				WHERE

					bw_items_checklist.activo = 1

					AND

				    cod_formulario = :cod_formulario;";

	  	$stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_formulario",  $cod_formulario);

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

      * Cambia el flag de activo de un item de un formulario

      */

    function conf_guardar_flag_traducir($flag_traducir,

                            			$user_insert){

        $SQL = "CALL conf_guardar_flag_traducir(:flag_traducir,

                                		:user_insert)";

        $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":flag_traducir",  $flag_traducir);

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

	 * Funciones para obtener listado de estados de plantaciones disponibles

	 */

	function conf_obtener_info_formularios_por_estado($cod_estado){

        $SQL = "SELECT

					    cod_formulario,

					    nombre_formulario,

					    descripcion_formulario,

					    cod_estado,

					    bw_estados_plantacion.estado_plantacion,

					    puntuacion_minima,

					    puntuacion_maxima,

					    bw_formularios.user_insert

					FROM

					    bw_formularios

					    INNER JOIN bw_estados_plantacion ON (bw_estados_plantacion.cod_estado_plantacion = bw_formularios.cod_estado)

					WHERE

					    bw_formularios.activo = 1

                        AND bw_formularios.cod_estado = :cod_estado;";

	  	$stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_estado",  $cod_estado);

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

	 * Funciones para obtener listado de estados de plantaciones disponibles

	 */

	function conf_obtener_info_formularios_por_plantacion($cod_plantacion){

        $SQL = "SELECT DISTINCT

				    cod_formulario,

				    nombre_formulario,

				    descripcion_formulario,

				    cod_plantacion,

				    bw_estados_plantacion.estado_plantacion,

				    puntuacion_minima,

				    puntuacion_maxima,

				    bw_formularios.user_insert

				FROM

				    bw_formularios

				        INNER JOIN

				    bw_estados_plantacion ON (bw_estados_plantacion.cod_estado_plantacion = bw_formularios.cod_estado)

				        INNER JOIN

				    bw_detalle_bloques_plantaciones ON (bw_detalle_bloques_plantaciones.cod_estado_plantacion = bw_formularios.cod_estado

				        AND bw_detalle_bloques_plantaciones.cod_plantacion = :cod_plantacion)

				WHERE

				    bw_formularios.activo = 1";

	  	$stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_plantacion",  $cod_plantacion);

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

	 * Funciones para obtener listado de tipos de temporada disponibles

	 */

	function conf_listado_temporadas_por_granja($cod_info_empresa){

            $SQL = "SELECT

					    bw_temporadas.cod_temporada,

					    bw_temporadas.cod_info_empresa,

					    bw_info_empresa.nombre_empresa,

					    bw_temporadas.cod_tipo_temporada,

					    bw_tipo_temporada.tipo_temporada,

					    bw_temporadas.codigo_temporada,

					    DATE_FORMAT(STR_TO_DATE(bw_temporadas.fecha_inicio,'%Y-%m-%d'),'%m-%d-%Y') as fecha_inicio,

					    DATE_FORMAT(STR_TO_DATE(bw_temporadas.fecha_final,'%Y-%m-%d'),'%m-%d-%Y') as fecha_final,

					    bw_temporadas.activo

					FROM

					    bw_temporadas

					        INNER JOIN

					    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_temporadas.cod_info_empresa)

					        INNER JOIN

					    bw_tipo_temporada ON (bw_tipo_temporada.cod_tipo_temporada = bw_temporadas.cod_tipo_temporada)

					WHERE

					    bw_temporadas.cod_info_empresa = :cod_info_empresa

					ORDER BY fecha_inicio , fecha_final DESC , nombre_empresa ASC;";

	  $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);

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

      * Guarda un formulario

      */

    function conf_guardar_formulario($codigo_formulario,

										$nombre_formulario,

										$puntuacion_minima,

										$puntuacion_maxima,

										$cod_estado_plantacion,

										$descripcion_formulario,

                            			$user_insert){

        $SQL = "CALL conf_guardar_formulario(:codigo_formulario,

										:nombre_formulario,

										:puntuacion_minima,

										:puntuacion_maxima,

										:cod_estado_plantacion,

										:descripcion_formulario,

                                		:user_insert)";

        $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":codigo_formulario",  $codigo_formulario);

        $stmt->bindParam(":nombre_formulario",  $nombre_formulario);

        $stmt->bindParam(":puntuacion_minima",  $puntuacion_minima);

        $stmt->bindParam(":puntuacion_maxima",  $puntuacion_maxima);

        $stmt->bindParam(":cod_estado_plantacion",  $cod_estado_plantacion);

        $stmt->bindParam(":descripcion_formulario",  $descripcion_formulario);

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

      * Actualizar texto de un item de un formulario

      */

    function conf_actualizar_item_formulario($codigo_item,

										$texto_item,

                            			$user_insert){

        $SQL = "CALL conf_actualizar_item_formulario(:codigo_item,

										:texto_item,

                                		:user_insert)";

        $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":codigo_item",  $codigo_item);

        $stmt->bindParam(":texto_item",  $texto_item);

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

      * Guarda un formulario

      */

    function conf_guardar_formulario_sa($codigo_formulario_sa,

										$nombre_formulario,

										$cod_modulo,

										$cod_menu,

										$descripcion_formulario,

										$cod_info_empresa,

										$cod_periodo_notificacion,

                            			$user_insert){

        $SQL = "CALL conf_guardar_formulario_sa(:codigo_formulario_sa,

										:nombre_formulario,

										:cod_modulo,

										:cod_menu,

										:descripcion_formulario,

										:cod_info_empresa,

										:cod_periodo_notificacion,

                                		:user_insert)";

        $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":codigo_formulario_sa",  $codigo_formulario_sa);

        $stmt->bindParam(":nombre_formulario",  $nombre_formulario);

        $stmt->bindParam(":cod_modulo",  $cod_modulo);

        $stmt->bindParam(":cod_menu",  $cod_menu);

        $stmt->bindParam(":descripcion_formulario",  $descripcion_formulario);

        $stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);

        $stmt->bindParam(":cod_periodo_notificacion",  $cod_periodo_notificacion);

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

	 * Funciones para obtener listado de formularios de seguridad alimenticia

	 */

	function conf_listado_formularios_seguridad_alimenticia_activos(){

            $SQL = "SELECT

					    ug_formularios.*,

            			ug_modulos.nombre,

            			ug_modulos.nombre_english,

            			ug_menus.menu,

            			ug_menus.menu_english,

            			bw_info_empresa.nombre_empresa

					FROM

					    ug_formularios

					    INNER JOIN

					    ug_modulos ON(ug_modulos.cod_modulo = ug_formularios.cod_modulo)

					    INNER JOIN

					    ug_menus ON(ug_menus.cod_modulo = ug_formularios.cod_modulo

					    AND ug_menus.cod_menu = ug_formularios.cod_menu)

					    INNER JOIN

					    bw_info_empresa ON(bw_info_empresa.cod_info_empresa = ug_formularios.cod_info_empresa);";

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

	 * Funciones para obtener listado de estados de plantaciones disponibles

	 */

	function conf_obtener_info_formulario_sa($cod_formulario){

        $SQL = "SELECT

					    *

					FROM

					    ug_formularios

					WHERE

					    ug_formularios.activo = 1

                        AND ug_formularios.cod_formulario = :cod_formulario;";

	  	$stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_formulario",  $cod_formulario);

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

	 * Funciones para obtener listado de items activo de formularios de seguridad alimenticia

	 */

	function conf_listado_formulario_tipo_items(){

            $SQL = "SELECT

					    *

					FROM

					    ug_formularios_tipo_items

					WHERE

					    activo = 1

					ORDER BY tipo_item ASC;";

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

      * Guarda un item con su detalle de un formulario

      */

    function conf_guardar_item_formulario_sa($codigo_formulario_sa,

    									$id_item,

										$nombre_item,

										$cod_tipo_item,

										$descripcion_item,

										$codigo_item,

										$orden,

										$flag_alerta,

										$caracteres_max,

										$cod_tipo_mascara,

										$requerido,

                                		$user_insert){

        $SQL = "CALL conf_guardar_item_formulario_sa(:codigo_formulario_sa,

										:id_item,

										:nombre_item,

										:cod_tipo_item,

										:descripcion_item,

										:codigo_item,

										:orden,

										:flag_alerta,

										:caracteres_max,

										:cod_tipo_mascara,

										:requerido,

                                		:user_insert)";

        $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":codigo_formulario_sa",  $codigo_formulario_sa);

        $stmt->bindParam(":id_item",  $id_item);

        $stmt->bindParam(":nombre_item",  $nombre_item);

        $stmt->bindParam(":cod_tipo_item",  $cod_tipo_item);

        $stmt->bindParam(":descripcion_item",  $descripcion_item);

        $stmt->bindParam(":codigo_item",  $codigo_item);

        $stmt->bindParam(":orden",  $orden);

        $stmt->bindParam(":flag_alerta",  $flag_alerta);

        $stmt->bindParam(":caracteres_max",  $caracteres_max);

        $stmt->bindParam(":cod_tipo_mascara",  $cod_tipo_mascara);

        $stmt->bindParam(":requerido",  $requerido);

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

	 * Funciones para obtener listado de items de un formulario de seguridad alimenticia

	 */

	function conf_obtener_items_formulario_sa($cod_formulario_sa){

        $SQL = "SELECT

				    ug_formularios_items.*,

                    ug_formularios_tipo_items.tipo_item,

                    ug_formularios_tipo_items.max_opciones,

				    (SELECT

				            GROUP_CONCAT(CONCAT(ug_formularios_detalle_items.texto_detalle, ' - ',ug_formularios_detalle_items.valor_detalle)

				                    SEPARATOR '<br>')

				        FROM

				            ug_formularios_detalle_items

				        WHERE

				            ug_formularios_detalle_items.cod_formulario_item = ug_formularios_items.cod_formulario_item) AS opciones

				FROM

				    ug_formularios_items

				        INNER JOIN

				    ug_formularios_tipo_items ON (ug_formularios_tipo_items.cod_tipo_item = ug_formularios_items.cod_tipo_item)

				WHERE

				    cod_formulario = :cod_formulario

				    ORDER BY ug_formularios_items.orden ASC;";

	  	$stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_formulario",  $cod_formulario_sa);

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

	 * Funciones para obtener listado de items de un formulario de seguridad alimenticia

	 */

	function conf_obtener_items_activos_formulario_sa($cod_formulario_sa){

        $SQL = "SELECT

				    ug_formularios_items.*,

                    ug_formularios_tipo_items.tipo_item,

                    ug_formularios_tipo_items.max_opciones,

                    ug_formularios_items.caracteres_max,

                    ug_formularios_items.cod_tipo_mascara,

                    ug_formularios_items.requerido,

                    ug_formularios_items.flag_alerta,

                    ug_formularios_items.orden,

				    (SELECT

				            GROUP_CONCAT(CONCAT(ug_formularios_detalle_items.texto_detalle, ' - ',ug_formularios_detalle_items.valor_detalle)

				                    SEPARATOR '<br>')

				        FROM

				            ug_formularios_detalle_items

				        WHERE

				            ug_formularios_detalle_items.cod_formulario_item = ug_formularios_items.cod_formulario_item) AS opciones

				FROM

				    ug_formularios_items

				        INNER JOIN

				    ug_formularios_tipo_items ON (ug_formularios_tipo_items.cod_tipo_item = ug_formularios_items.cod_tipo_item)

				WHERE

				    cod_formulario = :cod_formulario

				    AND ug_formularios_items.activo = 1

				    ORDER BY ug_formularios_items.orden ASC;";

	  	$stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_formulario",  $cod_formulario_sa);

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

      * Guarda opción de un item de un formulario de seguridad alimenticia

      */

    function conf_guardar_opciones_item_formulario_sa($codigo_item,

										$opcion,

										$valor,

                            			$user_insert){

        $SQL = "CALL conf_guardar_opciones_item_formulario_sa(:codigo_item,

										:opcion,

										:valor,

                                		:user_insert)";

        $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":codigo_item",  $codigo_item);

        $stmt->bindParam(":opcion",  $opcion);

        $stmt->bindParam(":valor",  $valor);

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

      * Cambia el flag de activo de un item de un formulario de seguridad alimenticia

      */

    function conf_cambiar_activo_item_formulario_sa($cod_item,

										$flag_activo,

                            			$user_insert){

        $SQL = "CALL conf_cambiar_activo_item_formulario_sa(:cod_item,

										:flag_activo,

                                		:user_insert)";

        $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_item",  $cod_item);

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

      * Cambia el flag de activo de un formulario de seguridad alimenticia

      */

    function conf_cambiar_activo_formulario_sa($cod_formulario,

										$flag_activo,

                            			$user_insert){

        $SQL = "CALL conf_cambiar_activo_formulario_sa(:cod_formulario,

										:flag_activo,

                                		:user_insert)";

        $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_formulario",  $cod_formulario);

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

	 * Funciones para obtener listado de opciones de los items de un formulario

	 */

	function conf_obtener_opciones_items_formulario_sa($cod_item){

        $SQL = "SELECT

		            ug_formularios_detalle_items.texto_detalle,

		            ug_formularios_detalle_items.valor_detalle,

		            ug_formularios_detalle_items.cod_detalle_item

		        FROM

		            ug_formularios_detalle_items

		        WHERE

		            ug_formularios_detalle_items.cod_formulario_item = :cod_item;";

	  	$stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_item",  $cod_item);

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

	 * Funciones para obtener listado de formularios de seguridad alimenticia mediante codigo de modulo y menu

	 */

	function conf_obtener_info_formularios_por_menu($cod_modulo, $cod_menu, $cod_info_empresa){

        $SQL = "SELECT

					    *

					FROM

					    ug_formularios

					WHERE

					    ug_formularios.activo = 1

                        AND ug_formularios.cod_modulo = :cod_modulo

                        AND ug_formularios.cod_menu = :cod_menu

                        AND ug_formularios.cod_info_empresa IN (".$cod_info_empresa.");";

	  	$stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_modulo",  $cod_modulo);

        $stmt->bindParam(":cod_menu",  $cod_menu);

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

      * Permite guardar la respuesta de un item de un formulario de seguridad alimenticia

      */

    function conf_guardar_item_respuesta_formulario_sa($cod_formulario,

    									$cod_detalle,

										$cod_detalle_item,

										$valor_respuesta,

										$prioridad,

                            			$user_insert){

        $SQL = "CALL conf_guardar_item_respuesta_formulario_sa(:cod_formulario,

        								:cod_detalle,

										:cod_detalle_item,

										:valor_respuesta,

										:prioridad,

                                		:user_insert)";

        $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_formulario",  $cod_formulario);

        $stmt->bindParam(":cod_detalle",  $cod_detalle);

        $stmt->bindParam(":cod_detalle_item",  $cod_detalle_item);

        $stmt->bindParam(":valor_respuesta",  $valor_respuesta);

        $stmt->bindParam(":prioridad",  $prioridad);

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

	 * Funciones para obtener listado de formularios activos según código de granja/finca

	 */

	function conf_listado_formularios_por_granja($cod_info_empresa){

            $SQL = "SELECT

					    *

					FROM

					    ug_formularios

					WHERE

					    ug_formularios.cod_info_empresa = :cod_info_empresa

					        AND ug_formularios.activo = 1

					ORDER BY ug_formularios.nombre_formulario ASC;";

	  $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);

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

	 * Funciones para obtener listado de formularios activos según fechas, finca/granja y codigo de formulario

	 */

	function conf_obtener_listado_formularios_review($fecha_inicial, $fecha_final, $cod_info_empresa, $cod_formulario){

		$fecha_inicial .= ' 00:00:00';

        $fecha_final .= ' 23:59:59';

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

        $SQL = "SELECT

				    ug_formularios_detalle.cod_detalle,

				    ug_formularios.cod_formulario,

				    ug_formularios.nombre_formulario,

				    ug_formularios.descripcion_formulario,

				    ug_formularios_detalle.observacion,

				    ug_formularios_detalle.prioridad,

				    ug_formularios_estados.nombre_estado,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario,

				    DATE_FORMAT(STR_TO_DATE(ug_formularios_detalle.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert

				FROM

				    ug_formularios

				        INNER JOIN

				    ug_formularios_detalle ON (ug_formularios_detalle.cod_formulario = ug_formularios.cod_formulario)

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = ug_formularios.user_insert)

				    	INNER JOIN

				    ug_formularios_estados ON (ug_formularios_estados.cod_estado = ug_formularios_detalle.cod_estado)

				WHERE

				    ug_formularios.cod_info_empresa = :cod_info_empresa

				        AND ug_formularios.activo = 1

				        AND ug_formularios.cod_formulario = :cod_formulario

				        AND ug_formularios_detalle.date_insert BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

				ORDER BY ug_formularios.nombre_formulario ASC, ug_formularios_detalle.date_insert DESC;";

	  $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":fecha_inicial",  $fecha_inicial);

        $stmt->bindParam(":fecha_final",  $fecha_final);

        $stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);

        $stmt->bindParam(":cod_formulario",  $cod_formulario);

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

	 * Funciones para obtener listado de items de un formulario de seguridad alimenticia

	 */

	function conf_obtener_respuestas_items_activos_formulario_sa($cod_detalle){

        $SQL = "SELECT DISTINCT

				    ug_formularios_detalle.cod_detalle,

				    ug_formularios_detalle.cod_formulario,

				    ug_formularios_detalle_items.cod_detalle_item,

				    ug_formularios_items.cod_tipo_item,

				    ug_formularios_items.cod_formulario_item,

				    ug_formularios_items.id_item,

                    ug_formularios_items.flag_alerta,

				    ug_formularios_items.nombre_item,

				    ug_formularios_tipo_items.tipo_item,

				    ug_formularios_tipo_items.max_opciones,

				    ug_formularios_detalle_respuestas.observacion,

                    respuesta.texto_detalle,

                    respuesta.valor_detalle,

                    ug_formularios_items.orden

				FROM

				    ug_formularios_detalle

				        INNER JOIN

				    ug_formularios_items ON (ug_formularios_items.cod_formulario = ug_formularios_detalle.cod_formulario)

				        INNER JOIN

				    ug_formularios_tipo_items ON (ug_formularios_tipo_items.cod_tipo_item = ug_formularios_items.cod_tipo_item)

				        INNER JOIN

				    ug_formularios_detalle_items ON (ug_formularios_detalle_items.cod_formulario_item = ug_formularios_items.cod_formulario_item)

				        LEFT JOIN

				    ug_formularios_detalle_respuestas ON (ug_formularios_detalle_respuestas.cod_detalle = ug_formularios_detalle.cod_detalle

				        AND ug_formularios_detalle_respuestas.cod_detalle_item = ug_formularios_detalle_items.cod_detalle_item)

                        LEFT JOIN

					ug_formularios_detalle_items respuesta ON(respuesta.cod_detalle_item = ug_formularios_detalle_respuestas.observacion)

				WHERE

				    ug_formularios_detalle.cod_detalle = :cod_detalle

				        AND ug_formularios_items.activo = 1

				ORDER BY ug_formularios_items.orden ASC;";

	  	$stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_detalle",  $cod_detalle);

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

	 * Funciones para obtener listado de items de un formulario de seguridad alimenticia

	 */

	function conf_obtener_info_formulario_detalle_revisar($cod_detalle, $cod_estado){

        $SQL = "SELECT

				    ug_formularios.cod_formulario,

				    ug_formularios.nombre_formulario,

				    ug_formularios.descripcion_formulario,

				    ug_formularios.cod_menu,

				    ug_formularios.cod_modulo,

				    ug_formularios_detalle.cod_estado,

				    ug_formularios_detalle.observacion,

				    CONCAT(user_insert.nombre_1,

				            ' ',

				            user_insert.apellido_1) AS usuario_insert,

				    CONCAT(user_review.nombre_1,

				            ' ',

				            user_review.apellido_1) AS usuario_review,

				    ug_formularios_estados.nombre_estado

				FROM

				    ug_formularios_detalle

				        INNER JOIN

				    ug_formularios ON (ug_formularios.cod_formulario = ug_formularios_detalle.cod_formulario)

				        INNER JOIN

				    usu_usuarios user_insert ON (user_insert.cod_usuario = ug_formularios_detalle.user_insert)

				        LEFT JOIN

				    usu_usuarios user_review ON (user_review.cod_usuario = ug_formularios_detalle.user_review)

				        INNER JOIN

				    ug_formularios_estados ON (ug_formularios_estados.cod_estado = ug_formularios_detalle.cod_estado)

				WHERE

				    ug_formularios_detalle.cod_detalle = :cod_detalle

				        /*AND ug_formularios_detalle.cod_estado = :cod_estado*/;";

	  	$stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_detalle",  $cod_detalle);

        //$stmt->bindParam(":cod_estado",  $cod_estado);

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

	 * Funciones para obtener listado de items activo de formularios de seguridad alimenticia

	 */

	function conf_listado_estados_formularios_sa(){

            $SQL = "SELECT

					    ug_formularios_estados.*

					FROM

					    ug_formularios_estados

					WHERE

					    ug_formularios_estados.activo = 1

					ORDER BY ug_formularios_estados.nombre_estado ASC;";

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

      * Guarda la revisión realizada a un formulario

      */

    function conf_guardar_revision_formulario_sa($codigo_detalle,

										$cod_estado,

										$observacion,

                            			$user_insert){

        $SQL = "CALL conf_guardar_revision_formulario_sa(:codigo_detalle,

										:cod_estado,

										:observacion,

                                		:user_insert)";

        $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":codigo_detalle",  $codigo_detalle);

        $stmt->bindParam(":cod_estado",  $cod_estado);

        $stmt->bindParam(":observacion",  $observacion);

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

	 * Funciones para obtener listado de formularios activos según fechas, finca/granja y codigo de formulario

	 */

	function conf_obtener_listado_mis_formularios($fecha_inicial, $fecha_final, $cod_info_empresa, $cod_formulario, $cod_usuario){

		$fecha_inicial .= ' 00:00:00';

        $fecha_final .= ' 23:59:59';

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

		if ($cod_usuario != 0)

		{

	        $SQL = "SELECT

					    ug_formularios_detalle.cod_detalle,

					    ug_formularios.cod_formulario,

					    ug_formularios.nombre_formulario,

					    ug_formularios.descripcion_formulario,

					    CONCAT(usu_usuarios.nombre_1,

					            ' ',

					            usu_usuarios.apellido_1) AS nombre_usuario,

					    DATE_FORMAT(STR_TO_DATE(ug_formularios_detalle.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

					    ug_formularios_detalle.observacion,

					    ug_formularios_estados.nombre_estado

					FROM

					    ug_formularios

					        INNER JOIN

					    ug_formularios_detalle ON (ug_formularios_detalle.cod_formulario = ug_formularios.cod_formulario)

					        INNER JOIN

					    usu_usuarios ON (usu_usuarios.cod_usuario = ug_formularios.user_insert)

					    	INNER JOIN

					    ug_formularios_estados ON (ug_formularios_estados.cod_estado = ug_formularios_detalle.cod_estado)

					WHERE

					    ug_formularios.cod_info_empresa = :cod_info_empresa

					        AND ug_formularios.activo = 1

					        AND ug_formularios.cod_formulario = :cod_formulario

					        AND ug_formularios_detalle.date_insert BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

					        AND ug_formularios_detalle.user_insert = :cod_usuario

					ORDER BY ug_formularios.nombre_formulario ASC, ug_formularios_detalle.date_insert DESC;";

		  	$stmt = $this->db_conexion->prepare($SQL);

	        $stmt->bindParam(":fecha_inicial",  $fecha_inicial);

	        $stmt->bindParam(":fecha_final",  $fecha_final);

	        $stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);

	        $stmt->bindParam(":cod_formulario",  $cod_formulario);

	        $stmt->bindParam(":cod_usuario",  $cod_usuario);

		}

		else

		{

	        $SQL = "SELECT

					    ug_formularios_detalle.cod_detalle,

					    ug_formularios.cod_formulario,

					    ug_formularios.nombre_formulario,

					    ug_formularios.descripcion_formulario,

					    CONCAT(usu_usuarios.nombre_1,

					            ' ',

					            usu_usuarios.apellido_1) AS nombre_usuario,

					    DATE_FORMAT(STR_TO_DATE(ug_formularios_detalle.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

					    ug_formularios_detalle.observacion,

					    ug_formularios_estados.nombre_estado

					FROM

					    ug_formularios

					        INNER JOIN

					    ug_formularios_detalle ON (ug_formularios_detalle.cod_formulario = ug_formularios.cod_formulario)

					        INNER JOIN

					    usu_usuarios ON (usu_usuarios.cod_usuario = ug_formularios.user_insert)

					    	INNER JOIN

					    ug_formularios_estados ON (ug_formularios_estados.cod_estado = ug_formularios_detalle.cod_estado)

					WHERE

					    ug_formularios.cod_info_empresa = :cod_info_empresa

					        AND ug_formularios.activo = 1

					        AND ug_formularios.cod_formulario = :cod_formulario

					        AND ug_formularios_detalle.date_insert BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

					ORDER BY ug_formularios.nombre_formulario ASC, ug_formularios_detalle.date_insert DESC;";

		  	$stmt = $this->db_conexion->prepare($SQL);

	        $stmt->bindParam(":fecha_inicial",  $fecha_inicial);

	        $stmt->bindParam(":fecha_final",  $fecha_final);

	        $stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);

	        $stmt->bindParam(":cod_formulario",  $cod_formulario);

		}

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

	 * Funciones para obtener listado de formularios activos según fechas, finca/granja y codigo de formulario

	 */

	function conf_obtener_listado_mis_formularios_excel($fecha_inicial, $fecha_final, $cod_info_empresa, $cod_formulario, $cod_usuario){

		$fecha_inicial .= ' 00:00:00';

        $fecha_final .= ' 23:59:59';

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

		if ($cod_usuario != 0)

		{

			$SQL = "SELECT DISTINCT

					    ug_formularios.cod_formulario,

					    ug_formularios_detalle_respuestas.cod_respuesta,

					    ug_formularios_detalle.cod_detalle,

					    ug_formularios.nombre_formulario,

					    ug_formularios_estados.nombre_estado,

					    ug_formularios_detalle.observacion as observacion_review,

					    DATE_FORMAT(STR_TO_DATE(ug_formularios_detalle.date_insert,

					                    '%Y-%m-%d %H:%i:%s'),

					            '%m-%d-%Y %H:%i:%s') AS date_insert,

					    CONCAT(usu_usuarios.nombre_1,

					            ' ',

					            usu_usuarios.apellido_1) AS nombre_usuario,

					    ug_formularios_items.nombre_item,

					    ug_formularios_items.id_item,

					    ug_formularios_items.cod_tipo_item,

					    ug_formularios_tipo_items.tipo_item,

					    ug_formularios_detalle_respuestas.observacion,

					    ug_formularios_detalle_items.texto_detalle,

					    ug_formularios_detalle_items.valor_detalle,

    					opcion_select.texto_detalle as respuesta_select

					FROM

					    ug_formularios_detalle_respuestas

					        INNER JOIN

					    ug_formularios_detalle_items ON (ug_formularios_detalle_items.cod_detalle_item = ug_formularios_detalle_respuestas.cod_detalle_item)

					        INNER JOIN

					    ug_formularios_detalle ON (ug_formularios_detalle.cod_detalle = ug_formularios_detalle_respuestas.cod_detalle)

					        INNER JOIN

					    ug_formularios ON (ug_formularios.cod_formulario = ug_formularios_detalle.cod_formulario)

					        INNER JOIN

					    ug_formularios_items ON (ug_formularios_items.cod_formulario_item = ug_formularios_detalle_items.cod_formulario_item)

					        INNER JOIN

					    ug_formularios_estados ON (ug_formularios_estados.cod_estado = ug_formularios_detalle.cod_estado)

					        INNER JOIN

					    ug_formularios_tipo_items ON (ug_formularios_tipo_items.cod_tipo_item = ug_formularios_items.cod_tipo_item)

					        INNER JOIN

					    usu_usuarios ON (usu_usuarios.cod_usuario = ug_formularios_detalle.user_insert)

							LEFT JOIN

						ug_formularios_detalle_items opcion_select ON (opcion_select.cod_detalle_item = ug_formularios_detalle_respuestas.observacion)

					WHERE

					    ug_formularios.cod_info_empresa = :cod_info_empresa

					        AND ug_formularios.cod_formulario = :cod_formulario

					        AND ug_formularios_detalle.date_insert BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

					                    '%m-%d-%Y %H:%i:%s'),

					            '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

					                    '%m-%d-%Y %H:%i:%s'),

					            '%Y-%m-%d %H:%i:%s')

					        AND ug_formularios_detalle.user_insert = :cod_usuario

					        AND ug_formularios.activo = 1

					        AND ug_formularios_items.activo = 1

					        AND ug_formularios_detalle_items.activo = 1

					GROUP BY ug_formularios_detalle_respuestas.cod_respuesta

					ORDER BY ug_formularios.nombre_formulario ASC , ug_formularios_detalle.date_insert DESC;";

		  	$stmt = $this->db_conexion->prepare($SQL);

	        $stmt->bindParam(":fecha_inicial",  $fecha_inicial);

	        $stmt->bindParam(":fecha_final",  $fecha_final);

	        $stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);

	        $stmt->bindParam(":cod_formulario",  $cod_formulario);

	        $stmt->bindParam(":cod_usuario",  $cod_usuario);

		}

		else

		{

			$SQL = "SELECT DISTINCT

					    ug_formularios.cod_formulario,

					    ug_formularios_detalle_respuestas.cod_respuesta,

					    ug_formularios_detalle.cod_detalle,

					    ug_formularios.nombre_formulario,

					    ug_formularios_estados.nombre_estado,

					    ug_formularios_detalle.observacion as observacion_review,

					    DATE_FORMAT(STR_TO_DATE(ug_formularios_detalle.date_insert,

					                    '%Y-%m-%d %H:%i:%s'),

					            '%m-%d-%Y %H:%i:%s') AS date_insert,

					    CONCAT(usu_usuarios.nombre_1,

					            ' ',

					            usu_usuarios.apellido_1) AS nombre_usuario,

					    ug_formularios_items.nombre_item,

					    ug_formularios_items.id_item,

					    ug_formularios_items.cod_tipo_item,

					    ug_formularios_tipo_items.tipo_item,

					    ug_formularios_detalle_respuestas.observacion,

					    ug_formularios_detalle_items.texto_detalle,

					    ug_formularios_detalle_items.valor_detalle,

    					opcion_select.texto_detalle as respuesta_select

					FROM

					    ug_formularios_detalle_respuestas

					        INNER JOIN

					    ug_formularios_detalle_items ON (ug_formularios_detalle_items.cod_detalle_item = ug_formularios_detalle_respuestas.cod_detalle_item)

					        INNER JOIN

					    ug_formularios_detalle ON (ug_formularios_detalle.cod_detalle = ug_formularios_detalle_respuestas.cod_detalle)

					        INNER JOIN

					    ug_formularios ON (ug_formularios.cod_formulario = ug_formularios_detalle.cod_formulario)

					        INNER JOIN

					    ug_formularios_items ON (ug_formularios_items.cod_formulario_item = ug_formularios_detalle_items.cod_formulario_item)

					        INNER JOIN

					    ug_formularios_estados ON (ug_formularios_estados.cod_estado = ug_formularios_detalle.cod_estado)

					        INNER JOIN

					    ug_formularios_tipo_items ON (ug_formularios_tipo_items.cod_tipo_item = ug_formularios_items.cod_tipo_item)

					        INNER JOIN

					    usu_usuarios ON (usu_usuarios.cod_usuario = ug_formularios_detalle.user_insert)

							LEFT JOIN

						ug_formularios_detalle_items opcion_select ON (opcion_select.cod_detalle_item = ug_formularios_detalle_respuestas.observacion)

					WHERE

					    ug_formularios.cod_info_empresa = :cod_info_empresa

					        AND ug_formularios.cod_formulario = :cod_formulario

					        AND ug_formularios_detalle.date_insert BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

					                    '%m-%d-%Y %H:%i:%s'),

					            '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

					                    '%m-%d-%Y %H:%i:%s'),

					            '%Y-%m-%d %H:%i:%s')

					        AND ug_formularios.activo = 1

					        AND ug_formularios_items.activo = 1

					        AND ug_formularios_detalle_items.activo = 1

					GROUP BY ug_formularios_detalle_respuestas.cod_respuesta

					ORDER BY ug_formularios.nombre_formulario ASC , ug_formularios_detalle.date_insert DESC;";

		  	$stmt = $this->db_conexion->prepare($SQL);

	        $stmt->bindParam(":fecha_inicial",  $fecha_inicial);

	        $stmt->bindParam(":fecha_final",  $fecha_final);

	        $stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);

	        $stmt->bindParam(":cod_formulario",  $cod_formulario);

		}

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

	 * Funciones para obtener listado de formularios de seguridad alimenticia mediante codigo de modulo y menu

	 */

	function conf_obtener_listado_usuarios_notificar_llenado_form_por_finca(){

        $SQL = "SELECT DISTINCT

				    ug_formularios.cod_formulario,

				    ug_noticias.fecha_inicio,

				    ug_formularios.nombre_formulario,

				    ug_formularios.descripcion_formulario,

				    GROUP_CONCAT(DISTINCT CONCAT(usu_usuarios.nombre_1,

				                ' ',

				                usu_usuarios.apellido_1)

				        SEPARATOR ',') AS nombre_usuario,

				    GROUP_CONCAT(DISTINCT usu_usuarios.email

				        SEPARATOR ',') AS email_usuario,

				    bw_info_empresa.nombre_empresa,

				    ug_menus.menu_english,

				    ug_modulos.nombre_english,

				    ug_formularios_detalle.date_insert

				FROM

				    ug_noticias

				        INNER JOIN

				    ug_formularios ON (ug_formularios.cod_formulario = ug_noticias.cod_formulario)

				        INNER JOIN

				    ug_modulos ON (ug_modulos.cod_modulo = ug_formularios.cod_modulo)

				        INNER JOIN

				    ug_menus ON (ug_menus.cod_menu = ug_formularios.cod_menu)

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = ug_formularios.cod_info_empresa)

				        INNER JOIN

				    usu_usuarios ON (FIND_IN_SET(bw_info_empresa.cod_info_empresa,

				            REPLACE(REPLACE(REPLACE(usu_usuarios.cod_info_empresa,

				                        '\"',

				                        ''),

				                    '[',

				                    ''),

				                ']',

				                '')))

				        LEFT JOIN

				    ug_formularios_detalle ON (ug_formularios_detalle.cod_formulario = ug_formularios.cod_formulario

				        AND ug_formularios_detalle.user_insert = usu_usuarios.cod_usuario)

				WHERE

				    usu_usuarios.cod_cargo = 2

				        AND usu_usuarios.activo = 1

				        AND ug_formularios.activo = 1

				        AND DATE_FORMAT(ug_noticias.fecha_inicio, '%Y-%m-%d') = CURDATE()

				        AND (DATE_FORMAT(ug_formularios_detalle.date_insert,

				            '%Y-%m-%d') != CURDATE()

				        || ug_formularios_detalle.date_insert IS NULL)

				GROUP BY ug_noticias.cod_formulario;";

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

      * Guarda un nuevo environmental test

      */

    function conf_guardar_environmental_test($codigo_environmental_test,

										$cod_pais,

										$cod_departamento,

										$cod_municipio,

										$cod_info_empresa,

										$cod_location,

										$cod_type_test,

										$cod_source_phase,

										$cod_sample,

										$result,

										$sample_id,

										$sample_date,

										$sample_time,

                            			$user_insert){

        $SQL = "CALL conf_guardar_environmental_test(:codigo_environmental_test,

										:cod_pais,

										:cod_departamento,

										:cod_municipio,

										:cod_info_empresa,

										:cod_location,

										:cod_type_test,

										:cod_source_phase,

										:cod_sample,

										:result,

										:sample_id,

										:sample_date,

										:sample_time,

                                		:user_insert)";

        $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":codigo_environmental_test",  $codigo_environmental_test);

        $stmt->bindParam(":cod_pais",  $cod_pais);

        $stmt->bindParam(":cod_departamento",  $cod_departamento);

        $stmt->bindParam(":cod_municipio",  $cod_municipio);

        $stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);

        $stmt->bindParam(":cod_location",  $cod_location);

        $stmt->bindParam(":cod_type_test",  $cod_type_test);

        $stmt->bindParam(":cod_source_phase",  $cod_source_phase);

        $stmt->bindParam(":cod_sample",  $cod_sample);

        $stmt->bindParam(":result",  $result);

        $stmt->bindParam(":sample_id",  $sample_id);

        $stmt->bindParam(":sample_date",  $sample_date);

        $stmt->bindParam(":sample_time",  $sample_time);

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

      * Guarda un nuevo adjunto de environmental test

      */

    function conf_guardar_adjunto_environmental_test($codigo_environmental_test,

										$nombre_adjunto,

                            			$user_insert){

        $SQL = "CALL conf_guardar_adjunto_environmental_test(:codigo_environmental_test,

										:nombre_adjunto,

                                		:user_insert)";

        $stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":codigo_environmental_test",  $codigo_environmental_test);

        $stmt->bindParam(":nombre_adjunto",  $nombre_adjunto);

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

	 * Funciones para obtener listado de formularios activos según fechas, finca/granja y codigo de formulario

	 */

	function conf_obtener_listado_environmental_test($fecha_inicial, $fecha_final, $cod_info_empresa){

		$fecha_inicial .= ' 00:00:00';

        $fecha_final .= ' 23:59:59';

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

		$SQL = "SELECT

				    ug_environmental_test.cod_test,

				    ug_environmental_test.cod_pais,

				    ug_environmental_test.cod_departamento,

				    ug_environmental_test.cod_municipio,

				    ug_environmental_test.cod_info_empresa,

				    ug_environmental_test.cod_location,

				    ug_environmental_test.cod_type_test,

				    ug_environmental_test.cod_source_phase,

				    ug_environmental_test.cod_sample,

				    ug_environmental_test.sample_id,

				    ug_environmental_test.sample_date,

				    DATE_FORMAT(STR_TO_DATE(ug_environmental_test.sample_date,'%Y-%m-%d'),'%m-%d-%Y') as sample_date,

				    ug_environmental_test.sample_time,

				    sqrt(round( exp( sum( log( COALESCE ( ug_environmental_test.result, 0 ) ) ) ) )) as result,

				    DATE_FORMAT(STR_TO_DATE(ug_environmental_test.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    geo_paises.pais,

				    geo_departamentos.departamento,

				    geo_municipios.municipio,

				    bw_info_empresa.nombre_empresa,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario

				FROM

				    ug_environmental_test

				        INNER JOIN

				    geo_paises ON (geo_paises.cod_pais = ug_environmental_test.cod_pais)

				        INNER JOIN

				    geo_departamentos ON (geo_departamentos.cod_pais = ug_environmental_test.cod_pais

				        AND geo_departamentos.cod_departamento = ug_environmental_test.cod_departamento)

				        INNER JOIN

				    geo_municipios ON (geo_municipios.cod_pais = ug_environmental_test.cod_pais

				        AND geo_municipios.cod_departamento = ug_environmental_test.cod_departamento

				        AND geo_municipios.cod_municipio = ug_environmental_test.cod_municipio)

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = ug_environmental_test.cod_info_empresa)

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = ug_environmental_test.user_insert)

				WHERE

				    ug_environmental_test.activo = 1

				    	AND ug_environmental_test.cod_info_empresa = :cod_info_empresa

				        AND ug_environmental_test.sample_date BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

				GROUP BY ug_environmental_test.cod_info_empresa, ug_environmental_test.cod_location,

				ug_environmental_test.cod_type_test, ug_environmental_test.cod_source_phase, ug_environmental_test.cod_sample,

				ug_environmental_test.sample_date

				ORDER BY ug_environmental_test.sample_date DESC;";

	  	$stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":fecha_inicial",  $fecha_inicial);

        $stmt->bindParam(":fecha_final",  $fecha_final);

        $stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);

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

	 * Funciones para obtener listado de formularios activos según fechas, finca/granja y codigo de formulario

	 */

	function conf_obtener_listado_detallado_environmental_test($cod_info_empresa, $sample_date, $cod_location, $cod_type_test, $cod_source_phase, $cod_sample){

		// $fecha_inicial .= ' 00:00:00';

        // $fecha_final .= ' 23:59:59';

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

		$SQL = "SELECT DISTINCT

				    ug_environmental_test.cod_test,

				    ug_environmental_test.cod_pais,

				    ug_environmental_test.cod_departamento,

				    ug_environmental_test.cod_municipio,

				    ug_environmental_test.cod_info_empresa,

				    ug_environmental_test.cod_location,

				    ug_environmental_test.cod_type_test,

				    ug_environmental_test.cod_source_phase,

				    ug_environmental_test.cod_sample,

				    ug_environmental_test.sample_id,

				    ug_environmental_test.sample_date,

				    DATE_FORMAT(STR_TO_DATE(ug_environmental_test.sample_date,'%Y-%m-%d'),'%m-%d-%Y') as sample_date,

				    ug_environmental_test.sample_time,

				    ug_environmental_test.result,

				    DATE_FORMAT(STR_TO_DATE(ug_environmental_test.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    geo_paises.pais,

				    geo_departamentos.departamento,

				    geo_municipios.municipio,

				    bw_info_empresa.nombre_empresa,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario

				FROM

				    ug_environmental_test

				        INNER JOIN

				    geo_paises ON (geo_paises.cod_pais = ug_environmental_test.cod_pais)

				        INNER JOIN

				    geo_departamentos ON (geo_departamentos.cod_pais = ug_environmental_test.cod_pais

				        AND geo_departamentos.cod_departamento = ug_environmental_test.cod_departamento)

				        INNER JOIN

				    geo_municipios ON (geo_municipios.cod_pais = ug_environmental_test.cod_pais

				        AND geo_municipios.cod_departamento = ug_environmental_test.cod_departamento

				        AND geo_municipios.cod_municipio = ug_environmental_test.cod_municipio)

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = ug_environmental_test.cod_info_empresa)

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = ug_environmental_test.user_insert)

				WHERE

				    ug_environmental_test.activo = 1

				    	AND ug_environmental_test.cod_info_empresa = :cod_info_empresa

				        AND ug_environmental_test.sample_date = :sample_date

				        AND ug_environmental_test.cod_location = :cod_location

				        AND ug_environmental_test.cod_type_test = :cod_type_test

				        AND ug_environmental_test.cod_source_phase = :cod_source_phase

				        AND ug_environmental_test.cod_sample = :cod_sample

				ORDER BY ug_environmental_test.sample_date DESC;";

	  	$stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":sample_date",  $sample_date);

        $stmt->bindParam(":cod_location",  $cod_location);

        $stmt->bindParam(":cod_type_test",  $cod_type_test);

        $stmt->bindParam(":cod_source_phase",  $cod_source_phase);

        $stmt->bindParam(":cod_sample",  $cod_sample);

        $stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);

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

	 * Funciones para obtener listado de formularios activos según fechas, finca/granja y codigo de formulario

	 */

	function conf_obtener_listado_adjuntos_environmental_test($cod_test){

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

		$SQL = "SELECT 

				    ug_environmental_test_adjuntos.*

				FROM

				    ug_environmental_test_adjuntos

				WHERE

				    ug_environmental_test_adjuntos.activo = 1

				        AND ug_environmental_test_adjuntos.cod_test = :cod_test

				ORDER BY ug_environmental_test_adjuntos.date_insert ASC;";

	  	$stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":cod_test",  $cod_test);

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

	 * Funciones para obtener listado de formularios activos según fechas, finca/granja y codigo de formulario

	 */

	function conf_obtener_listado_environmental_test_excel($fecha_inicial, $fecha_final, $cod_info_empresa){

		$fecha_inicial .= ' 00:00:00';

        $fecha_final .= ' 23:59:59';

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

		$SQL = "SELECT 

				    ug_environmental_test.cod_test,

				    ug_environmental_test.cod_pais,

				    ug_environmental_test.cod_departamento,

				    ug_environmental_test.cod_municipio,

				    ug_environmental_test.cod_info_empresa,

				    ug_environmental_test.cod_location,

				    ug_environmental_test.cod_type_test,

				    ug_environmental_test.cod_source_phase,

				    ug_environmental_test.cod_sample,

				    ug_environmental_test.sample_id,

				    ug_environmental_test.sample_date,

				    DATE_FORMAT(STR_TO_DATE(ug_environmental_test.sample_date,

				                    '%Y-%m-%d'),

				            '%m-%d-%Y') AS sample_date,

				    ug_environmental_test.sample_time,

				    ug_environmental_test.result,

				    (SELECT 

				            SQRT(ROUND(EXP(SUM(LOG(COALESCE(test.result, 0))))))

				        FROM

				            ug_environmental_test test

				        WHERE

				            test.sample_date = ug_environmental_test.sample_date

				                AND test.cod_info_empresa = ug_environmental_test.cod_info_empresa

				                AND test.cod_type_test = ug_environmental_test.cod_type_test

				                AND test.cod_location = ug_environmental_test.cod_location

				                AND test.cod_source_phase = ug_environmental_test.cod_source_phase

				                AND test.cod_sample = ug_environmental_test.cod_sample

				        GROUP BY test.cod_info_empresa , test.cod_location , test.cod_type_test , test.cod_source_phase , test.cod_sample , test.sample_date) AS geometric_mean,

				    DATE_FORMAT(STR_TO_DATE(ug_environmental_test.date_insert,

				                    '%Y-%m-%d %H:%i:%s'),

				            '%m-%d-%Y %H:%i:%s') AS date_insert,

				    geo_paises.pais,

				    geo_departamentos.departamento,

				    geo_municipios.municipio,

				    bw_info_empresa.nombre_empresa,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario

				FROM

				    ug_environmental_test

				        INNER JOIN

				    geo_paises ON (geo_paises.cod_pais = ug_environmental_test.cod_pais)

				        INNER JOIN

				    geo_departamentos ON (geo_departamentos.cod_pais = ug_environmental_test.cod_pais

				        AND geo_departamentos.cod_departamento = ug_environmental_test.cod_departamento)

				        INNER JOIN

				    geo_municipios ON (geo_municipios.cod_pais = ug_environmental_test.cod_pais

				        AND geo_municipios.cod_departamento = ug_environmental_test.cod_departamento

				        AND geo_municipios.cod_municipio = ug_environmental_test.cod_municipio)

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = ug_environmental_test.cod_info_empresa)

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = ug_environmental_test.user_insert)

				WHERE

				    ug_environmental_test.activo = 1

				        AND ug_environmental_test.cod_info_empresa = :cod_info_empresa

				        AND ug_environmental_test.sample_date BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                    '%m-%d-%Y %H:%i:%s'),

				            '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                    '%m-%d-%Y %H:%i:%s'),

				            '%Y-%m-%d %H:%i:%s')

				ORDER BY ug_environmental_test.sample_date DESC;";

	  	$stmt = $this->db_conexion->prepare($SQL);

        $stmt->bindParam(":fecha_inicial",  $fecha_inicial);

        $stmt->bindParam(":fecha_final",  $fecha_final);

        $stmt->bindParam(":cod_info_empresa",  $cod_info_empresa);

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