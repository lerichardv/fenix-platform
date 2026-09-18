<?php

/*
 * Funciones para obtener información general de la base de datos.
 *
 * @author      Kevin Fúnez
 * @date        2017-06-14
 */

class db_general{
	public $db_conexion;
	function __construct(){
		$this->db_conexion = new db_lion();
		$this->db_conexion = $this->db_conexion->dbConnect();
	}

  /*
   * Obtiene el tipo de módulo y que este activo.
   */
	function get_tipos_modulos(){
		$SQL = "SELECT
              ug_tipo_modulos.cod_tipo_modulo,
					    ug_tipo_modulos.tipo_modulo,
					    ug_tipo_modulos.module_type
				FROM
              ug_tipo_modulos
				WHERE
              ug_tipo_modulos.activo = 1";

	    $stmt = $this->db_conexion->prepare($SQL);
		try{
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}

	/*
	 * Obtiene los módulos a los que tiene acceso el perfil del empleado que inicio sesión.
	 */
	function get_modulos_por_perfil_por_tipo($cod_perfil, $cod_usuario, $tipo_modulo){
		$SQL = "SELECT DISTINCT
              usu_perfiles.cod_perfil,
    					usu_perfiles.perfil,
    					ug_modulos.cod_modulo,
    					ug_modulos.nombre AS modulo,
                        ug_modulos.nombre_english AS modulo_english,
    					ug_modulos.descripcion,
    					ug_modulos.ruta
    				FROM usu_usuarios
    				INNER JOIN usu_perfiles
    					ON ( usu_perfiles.cod_perfil = usu_usuarios.cod_perfil
    						AND usu_perfiles.activo =1 )
    				INNER JOIN usu_perfil_accesos
    					ON ( usu_perfil_accesos.cod_perfil = usu_perfiles.cod_perfil )
    				INNER JOIN ug_modulos
    					ON ( ug_modulos.cod_modulo = usu_perfil_accesos.cod_modulo
    						AND ug_modulos.activo =1 )
    				WHERE usu_usuarios.activo =1
    					AND usu_perfiles.cod_perfil = :cod_perfil
    					AND usu_usuarios.cod_usuario = :cod_usuario
    					AND ug_modulos.cod_tipo_modulo = :tipo_modulo
    					ORDER BY modulo ASC ";

	  $stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_perfil",$cod_perfil);
		$stmt->bindParam(":cod_usuario",$cod_usuario);
		$stmt->bindParam(":tipo_modulo",$tipo_modulo);
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
	 * Obtiene los módulos a los que tiene acceso el perfil del empleado que inicio sesión.
	 */
	function get_modulos_por_perfil($cod_perfil, $cod_usuario){
		$SQL = "SELECT DISTINCT
                usu_perfiles.cod_perfil,
      					usu_perfiles.perfil,
      					ug_modulos.cod_modulo,
      					ug_modulos.nombre AS modulo,
      					ug_modulos.descripcion,
      					ug_modulos.ruta
    				FROM
              usu_usuarios
    				INNER JOIN
    					usu_perfiles ON ( usu_perfiles.cod_perfil = usu_usuarios.cod_perfil
    						AND usu_perfiles.activo =1 )
    				INNER JOIN
    					usu_perfil_accesos ON ( usu_perfil_accesos.cod_perfil = usu_perfiles.cod_perfil )
    				INNER JOIN
    					ug_modulos ON ( ug_modulos.cod_modulo = usu_perfil_accesos.cod_modulo
    							AND ug_modulos.activo =1 )
    				WHERE usu_usuarios.activo =1
    					AND usu_perfiles.cod_perfil = :cod_perfil
    					AND usu_usuarios.cod_usuario = :cod_usuario
    					ORDER BY usu_perfiles.perfil ASC ";
	  $stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_perfil",$cod_perfil);
		$stmt->bindParam(":cod_usuario",$cod_usuario);

		try {
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			$resultado = $e->getMessage();
		}
		$stmt->closeCursor();
		return $resultado;
	}



	/*
	 * Obtiene los menús a los que el usuario tiene acceso según el módulo seleccionado.
	 */

	function get_menus_por_perfil($cod_perfil, $cod_usuario, $cod_modulo){
		$SQL = "SELECT
					usu_perfiles.cod_perfil,
					usu_perfiles.perfil,
					ug_modulos.cod_modulo,
					ug_modulos.nombre as modulo,
					ug_modulos.descripcion,
					ug_modulos.ruta,
					ug_menus.cod_menu,
					ug_menus.menu,
                    ug_menus.menu_english,
					ug_menus.descripcion AS descripcion_menu,
					ug_menus.ruta,
					ug_menus.principal,
					ug_menus.sub_menu,
					ug_menus.cod_modulo_depende,
					ug_menus.cod_menu_depende
				FROM
					usu_usuarios
						INNER JOIN
					usu_perfiles ON (usu_perfiles.cod_perfil = usu_usuarios.cod_perfil
						AND usu_perfiles.activo = 1)
						INNER JOIN
					usu_perfil_accesos ON (usu_perfil_accesos.cod_perfil = usu_perfiles.cod_perfil)
						INNER JOIN
					ug_modulos ON (ug_modulos.cod_modulo = usu_perfil_accesos.cod_modulo
						AND ug_modulos.activo = 1)
						INNER JOIN
					ug_menus ON (ug_menus.cod_modulo = usu_perfil_accesos.cod_modulo
						AND ug_menus.cod_menu = usu_perfil_accesos.cod_menu
						AND ug_menus.activo = 1)
				WHERE
					usu_usuarios.activo = 1
						AND usu_perfiles.cod_perfil = :cod_perfil
						AND usu_usuarios.cod_usuario = :cod_usuario
						AND ug_modulos.cod_modulo = :cod_modulo
						AND ug_menus.sub_menu <= 1
				ORDER BY ug_menus.orden ASC";

	    $stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_perfil",$cod_perfil);
		$stmt->bindParam(":cod_usuario",$cod_usuario);
		$stmt->bindParam(":cod_modulo",$cod_modulo);
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

	 * Obtiene los sub menús a los que el usuario tiene acceso según el módulo seleccionado y menú configurado.

	 */

	function get_sub_menus_por_perfil($cod_perfil, $cod_usuario, $cod_modulo, $cod_menu){

		$SQL = "SELECT

					ug_menus.cod_menu,
					ug_menus.sub_menu,
					ug_menus.menu,
                    ug_menus.menu_english,
					ug_menus.descripcion AS descripcion_menu,
					ug_menus.ruta
				FROM
					usu_usuarios
						INNER JOIN
					usu_perfiles ON (usu_perfiles.cod_perfil = usu_usuarios.cod_perfil
						AND usu_perfiles.activo = 1)
						INNER JOIN
					usu_perfil_accesos ON (usu_perfil_accesos.cod_perfil = usu_perfiles.cod_perfil)
						INNER JOIN
					ug_modulos ON (ug_modulos.cod_modulo = usu_perfil_accesos.cod_modulo
						AND ug_modulos.activo = 1)
						INNER JOIN
					ug_menus ON (ug_menus.cod_modulo = usu_perfil_accesos.cod_modulo
						AND ug_menus.cod_menu = usu_perfil_accesos.cod_menu
						AND ug_menus.activo = 1)
				WHERE
					usu_usuarios.activo = 1
						AND usu_perfiles.cod_perfil      = :cod_perfil
						AND usu_usuarios.cod_usuario     = :cod_usuario
						AND ug_menus.cod_modulo_depende = :cod_modulo
						AND ug_menus.cod_menu_depende   = :cod_menu
				ORDER BY ug_menus.orden ASC";
	    $stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_perfil",$cod_perfil);
		$stmt->bindParam(":cod_usuario",$cod_usuario);
		$stmt->bindParam(":cod_modulo",$cod_modulo);
		$stmt->bindParam(":cod_menu",$cod_menu);
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

	 * Obtiene los paises habilitados.

	 */

	function get_paises(){

		$SQL = "SELECT

					cod_pais,

					pais

				FROM

					geo_paises

				WHERE

					activo = 1

				ORDER BY pais ASC";

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
	 * Obtiene los departamentos habilitados por país seleccionado.
	 */
	function get_departamentos(){

		$SQL = "SELECT
                                cod_departamento,
                                departamento
                        FROM
                                geo_departamentos
                        WHERE
                                activo = 1
                        ORDER BY
                                departamento ASC";

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
	 * Obtiene los municipios habilitados por país y departamento saeleccionados.
         *
	 * var cod_departamento int Código del departamento seleccionado para obtener municipios.
	 */

	function get_municipios($cod_departamento){

		$SQL = "SELECT
                                cod_municipio,
                                municipio
                        FROM
                                geo_municipios
                        WHERE
                                activo = 1
                        AND
                                cod_departamento = :cod_departamento
                        ORDER BY
                                municipio ASC";

	    $stmt = $this->db_conexion->prepare($SQL);
            $stmt->bindParam(":cod_departamento",$cod_departamento);

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

	 * Obtiene las ciudades habilitadas por país, departamento y municipio saeleccionados.

	 *

	 * var cod_pais         int Código del país seleccionado para obtener ciudades.

	 * var cod_departamento int Código del departamento seleccionado para obtener ciudades.

	 * var cod_municipio    int Código del municipio seleccionado para obtener ciudades.

	 */

	function get_ciudades($cod_pais, $cod_departamento, $cod_municipio){

		$SQL = "SELECT

					cod_ciudad,

					ciudad

				FROM

					geo_ciudades

				WHERE

					activo = 1

				AND

					cod_pais = :cod_pais

				AND

					cod_departamento = :cod_departamento

				AND

					cod_municipio = :cod_municipio

				ORDER BY ciudad ASC";

	    $stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_pais",$cod_pais);

		$stmt->bindParam(":cod_departamento",$cod_departamento);

		$stmt->bindParam(":cod_municipio",$cod_municipio);

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

	 * Obtiene los centros regionales habilitados por país, departamento, municipio y ciudad saeleccionados.

	 *

	 * var cod_pais         int Código del país seleccionado para obtener centros regionales.

	 * var cod_departamento int Código del departamento seleccionado para obtener centros regionales.

	 * var cod_municipio    int Código del municipio seleccionado para obtener centros regionales.

	 * var cod_ciudad       int Código de la ciudad seleccionado para obtener centros regionales.

	 */

	function get_centros_regionales($cod_pais, $cod_departamento, $cod_municipio, $cod_ciudad){

		$SQL = "SELECT

					car_ubicacion_centros_regionales.cod_centro_regional,

					car_centros_regionales.centro_regional,

					car_centros_regionales.descripcion

				FROM

					car_ubicacion_centros_regionales

					INNER JOIN car_centros_regionales

						ON (car_ubicacion_centros_regionales.cod_centro_regional = car_centros_regionales.cod_centro_regional)

				WHERE

					car_ubicacion_centros_regionales.activo = 1

				AND

					car_ubicacion_centros_regionales.cod_pais = :cod_pais

				AND

					car_ubicacion_centros_regionales.cod_departamento = :cod_departamento

				AND

					car_ubicacion_centros_regionales.cod_municipio = :cod_municipio

				AND

					car_ubicacion_centros_regionales.cod_ciudad = :cod_ciudad

				ORDER BY car_centros_regionales.centro_regional ASC";

	    $stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_pais",$cod_pais);

		$stmt->bindParam(":cod_departamento",$cod_departamento);

		$stmt->bindParam(":cod_municipio",$cod_municipio);

		$stmt->bindParam(":cod_ciudad",$cod_ciudad);

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

	 * Obtiene el flujo por de una solicitud por módulo.

	 */

	function get_flujo_solicitud_por_modulo($cod_modulo, $cod_usuario){

		$SQL = "SELECT

					ug_flujos.cod_modulo,

					ug_flujos.cod_flujo,

					ug_flujos.flujo,

					ug_flujos.descripcion,

					ug_flujos.paso_obligatorio,

					ug_flujos.orden,

					usu_usuarios.cod_usuario,

					CONCAT(usu_usuarios.nombre_1,

							' ',

							usu_usuarios.apellido_1) AS autorizador,

					usu_usuarios.fotografia

				FROM

					ug_flujos

						INNER JOIN

					ug_autorizadores ON (ug_autorizadores.cod_modulo = ug_flujos.cod_modulo

						AND ug_autorizadores.cod_flujo = ug_flujos.cod_flujo

        				AND ug_autorizadores.activo = 1)

						INNER JOIN

					usu_usuarios ON (usu_usuarios.cod_usuario = ug_autorizadores.cod_autorizador

						AND usu_usuarios.activo = 1)

						LEFT JOIN

					ug_departamentos_usuarios ON (usu_usuarios.cod_departamento = ug_departamentos_usuarios.cod_departamento

						AND ug_departamentos_usuarios.activo = 1)

				WHERE

					ug_flujos.activo = 1

						AND ug_flujos.cod_modulo = :cod_modulo

						AND ug_autorizadores.cod_usuario = :cod_usuario

				ORDER BY ug_flujos.orden ASC";

	    $stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_modulo",$cod_modulo);

		$stmt->bindParam(":cod_usuario",$cod_usuario);

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





	/*---------------------------------------------------------*/

	function get_flujo_solicitud_por_modulo_por_departamento($cod_modulo, $cod_usuario,$cod_flujo,$cod_departamento){

		$SQL = "SELECT

					ug_flujos.cod_modulo,

					ug_flujos.cod_flujo,

					ug_flujos.flujo,

					ug_flujos.descripcion,

					ug_flujos.paso_obligatorio,

					ug_flujos.orden,

					usu_usuarios.cod_usuario,

					ug_departamentos_usuarios.departamento AS departamento,

					CONCAT(usu_usuarios.nombre_1,

							' ',

							usu_usuarios.apellido_1) AS autorizador,

					usu_usuarios.fotografia

				FROM

					ug_flujos

						INNER JOIN

					ug_autorizadores ON (ug_autorizadores.cod_modulo = ug_flujos.cod_modulo

						AND ug_autorizadores.cod_flujo = ug_flujos.cod_flujo

        				AND ug_autorizadores.activo = 1)

						INNER JOIN

					usu_usuarios ON (usu_usuarios.cod_usuario = ug_autorizadores.cod_autorizador

						AND usu_usuarios.activo = 1)

						LEFT JOIN

					ug_departamentos_usuarios ON (usu_usuarios.cod_departamento = ug_departamentos_usuarios.cod_departamento

						AND ug_departamentos_usuarios.activo = 1)

				WHERE

					ug_flujos.activo = 1

						AND ug_flujos.cod_modulo = 5

						AND ug_autorizadores.cod_usuario = 2

				ORDER BY ug_flujos.orden ASC";

	    $stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_modulo",$cod_modulo);

		$stmt->bindParam(":cod_usuario",$cod_usuario);

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

	/*******************************************************************/







	/*

	 * Obtiene los módulos a los que tiene acceso el perfil del empleado que inicio sesión.

	 */

	function get_menus_por_perfil_todos($cod_perfil, $cod_usuario, $cod_modulo){

		$SQL = "SELECT

					usu_perfiles.cod_perfil,

					usu_perfiles.perfil,

					ug_modulos.cod_modulo,

					ug_modulos.nombre as modulo,

					ug_modulos.descripcion,

					ug_modulos.ruta,

					ug_menus.cod_menu,

					ug_menus.menu,

					ug_menus.descripcion AS descripcion_menu,

					ug_menus.ruta,

					ug_menus.principal,

					ug_menus.sub_menu/*,

					ug_menus.cod_modulo_depende,

					ug_menus.cod_menu_depende*/

				FROM

					usu_usuarios

						INNER JOIN

					usu_perfiles ON (usu_perfiles.cod_perfil = usu_usuarios.cod_perfil

						AND usu_perfiles.activo = 1)

						INNER JOIN

					usu_perfil_accesos ON (usu_perfil_accesos.cod_perfil = usu_perfiles.cod_perfil)

						INNER JOIN

					ug_modulos ON (ug_modulos.cod_modulo = usu_perfil_accesos.cod_modulo)

						INNER JOIN

					ug_menus ON (ug_menus.cod_modulo = usu_perfil_accesos.cod_modulo

						AND ug_menus.cod_menu = usu_perfil_accesos.cod_menu)

				WHERE

					usu_usuarios.activo = 1

						AND usu_perfiles.cod_perfil = :cod_perfil

						AND usu_usuarios.cod_usuario = :cod_usuario

						AND ug_modulos.cod_modulo = :cod_modulo

				ORDER BY ug_menus.orden ASC";

	    $stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_perfil",$cod_perfil);

		$stmt->bindParam(":cod_usuario",$cod_usuario);

		$stmt->bindParam(":cod_modulo",$cod_modulo);

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

	 * Obtiene los módulos a los que tiene acceso el perfil del empleado que inicio sesión.

	 *

	 * var cod_modulo int Código del módulo en el que se esta trabajando.

	 * var cod_flujo  int Código del flujo actual en proceso.

	 */

	function get_acciones_por_flujo($cod_modulo, $cod_flujo){

		$SQL = "SELECT

					cod_modulo,

					cod_flujo,

					cod_accion,

					accion,

					titulo,

					nombre_elemento,

					descripcion,

					clase

				FROM

					ug_acciones

				WHERE

					activo = 1

				AND

					cod_modulo = :cod_modulo

				AND

					cod_flujo = :cod_flujo

				ORDER BY cod_accion ASC";

	    $stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_modulo",$cod_modulo);

		$stmt->bindParam(":cod_flujo",$cod_flujo);

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

	 * Obtiene los módulos a los que tiene acceso el perfil del empleado que inicio sesión.

	 *

	 * var cod_modulo     int Código del módulo en el que se esta trabajando.

	 * var cod_solicitud  int Código de la solicitud a la que se le buscará el flujo siguiente.

	 * var flag_flujo     int Verifica si el flujo necesita usuario o será general para módulo.

	 * var flag_usuario   int Variable que valida si debe seguir el flujo normal o uno ya preestablecido

								0 - Sigue el flujo normal

								1 - Sigue un flujo preestablecido

	 * var flag_obligatorio int Indica si debe tomarse en cuenta el autorizador que no es ogbligatorio.

	 */

	function get_siguiente_flujo($cod_modulo, $cod_solicitud, $flag_flujo, $flag_usuario, $flag_obligatorio){

		$SQL = "CALL ug_flujo_autorizadores(:cod_modulo,

											:cod_solicitud,

											:flag_flujo,

											:flag_usuario,

											:flag_obligatorio)";

	    $stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_modulo",$cod_modulo);

		$stmt->bindParam(":cod_solicitud",$cod_solicitud);

		$stmt->bindParam(":flag_flujo",$flag_flujo);

		$stmt->bindParam(":flag_usuario",$flag_usuario);

		$stmt->bindParam(":flag_obligatorio",$flag_obligatorio);

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

	 * Obtiene el primer autorizador activo en el flujo de un módulo.

	 *

	 * var cod_modulo  int Código del módulo en el que se esta trabajando.

	 * var cod_usuario int Código del dueño de la solicitud.

	 */

	function get_primer_autorizador($cod_modulo, $cod_usuario){

		$SQL = "SELECT

					ug_flujos.cod_modulo,

					ug_flujos.cod_flujo,

					ug_flujos.flujo,

					ug_flujos.descripcion,

					ug_flujos.paso_obligatorio,

					ug_flujos.orden,

					ug_autorizadores.cod_autorizador,

					CONCAT(usu_usuarios.nombre_1,

							' ',

							usu_usuarios.apellido_1) AS autorizador,

					usu_usuarios.email

				FROM

					ug_flujos

						INNER JOIN

					ug_autorizadores ON (ug_autorizadores.cod_modulo = ug_flujos.cod_modulo

						AND ug_autorizadores.cod_flujo = ug_flujos.cod_flujo

						AND ug_autorizadores.activo = 1)

						INNER JOIN

					usu_usuarios ON (usu_usuarios.cod_usuario = ug_autorizadores.cod_autorizador)

				WHERE

					ug_flujos.activo = 1

						AND ug_flujos.cod_modulo = :cod_modulo

						AND ug_autorizadores.cod_usuario = :cod_usuario

				ORDER BY ug_flujos.cod_flujo ASC

				LIMIT 1";

	    $stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_modulo",$cod_modulo);

		$stmt->bindParam(":cod_usuario",$cod_usuario);

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

	 * Obtiene el siguiente autorizador por la suma del flujo actual de la solicitud.

	 *

	 * var cod_modulo  int Código del módulo en el que se esta trabajando.

	 * var cod_flujo   int Código del flujo a buscar.

	 * var cod_usuario int Código del usuario.

	 */

	function get_siguiente_autorizador($cod_modulo, $cod_flujo, $cod_usuario){

		$SQL = "SELECT

					cod_modulo,

					cod_flujo,

					cod_usuario,

					grupo_autorizadores,

					cod_autorizador

				FROM

					ug_autorizadores

				WHERE

					activo = 1

				AND

					cod_modulo = :cod_modulo

				AND

					cod_flujo > :cod_flujo

				AND

					cod_usuario = :cod_usuario

				ORDER BY cod_flujo ASC";

	    $stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_modulo",$cod_modulo);

		$stmt->bindParam(":cod_flujo",$cod_flujo);

		$stmt->bindParam(":cod_usuario",$cod_usuario);

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

	 * Obtiene el listado de empleados por jefe inmediato.

	 */

	function get_empleados_por_jefe($cod_jefe){

		$SQL = "SELECT

					usu_usuarios.cod_usuario,

					CONCAT(usu_usuarios.nombre_1,

							' ',

							usu_usuarios.apellido_1) AS nombre

				FROM

					usu_usuarios

				WHERE

					activo = 1

				AND

					cod_jefe = :cod_jefe

				ORDER BY

					nombre ASC";

	    $stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_jefe",$cod_jefe);

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

	 * Obtiene los departamentos laborables activos.

	 */

	function get_departamentos_empleados(){

		$SQL = "SELECT

					cod_departamento,

					departamento

				FROM

					ug_departamentos_usuarios

				WHERE

					activo = 1

				ORDER BY departamento ASC";

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

	 * Obtiene el  autorizador actual del flujo actual de la solicitud.

	 *

	 * var cod_modulo  int Código del módulo en el que se esta trabajando.

	 * var cod_flujo   int Código del flujo a buscar.

	 * var cod_usuario int Código del usuario.

	 */

	function get_autorizador_actual($cod_modulo, $cod_flujo, $cod_usuario){

		$SQL = "SELECT

					cod_modulo,

					cod_flujo,

					cod_usuario,

					grupo_autorizadores,

					cod_autorizador

				FROM

					ug_autorizadores

				WHERE

					activo = 1

				AND

					cod_modulo = :cod_modulo

				AND

					cod_flujo = :cod_flujo

				AND

					cod_usuario = :cod_usuario

				ORDER BY cod_flujo ASC";

	    $stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_modulo",$cod_modulo);

		$stmt->bindParam(":cod_flujo",$cod_flujo);

		$stmt->bindParam(":cod_usuario",$cod_usuario);

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

	 * Obtiene los módulos a los que tiene acceso el perfil del empleado que inicio sesión.

	 *

	 * var cod_modulo int Código del módulo en el que se esta trabajando.

	 * var cod_flujo  int Código del flujo actual en proceso.

	 */

	function get_acciones_por_flujo_flag_alterno($cod_modulo, $cod_flujo){

		$SQL = "SELECT

					cod_modulo,

					cod_flujo,

					cod_accion,

					accion,

					titulo,

					nombre_elemento,

					descripcion,

					clase

				FROM

					ug_acciones

				WHERE

					activo = 1

				AND

					cod_modulo = :cod_modulo

				AND

					cod_flujo = :cod_flujo

				ORDER BY cod_accion ASC";

	    $stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_modulo",$cod_modulo);

		$stmt->bindParam(":cod_flujo",$cod_flujo);

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

	 * Obtiene los módulos a los que tiene acceso el perfil del empleado que inicio sesión.

	 *

	 * var cod_modulo int Código del módulo en el que se esta trabajando.

	 * var cod_flujo  int Código del flujo actual en proceso.

	 */

	function get_acciones_por_flujo_especiales($cod_modulo, $cod_flujo){

		$SQL = "SELECT

					cod_modulo,

					cod_flujo,

					cod_accion,

					accion,

					titulo,

					nombre_elemento,

					descripcion,

					clase

				FROM

					ug_acciones

				WHERE

					activo = 1

				AND

					cod_modulo = :cod_modulo

				AND

					cod_flujo = :cod_flujo

				AND

					cod_tipo_objeto = 2

				ORDER BY cod_accion ASC";

	    $stmt = $this->db_conexion->prepare($SQL);

		$stmt->bindParam(":cod_modulo",$cod_modulo);

		$stmt->bindParam(":cod_flujo",$cod_flujo);

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



	/*----------------------------------------------------------------------------------

	 			Obtiene todos los géneros <<ug_sexos>>

	----------------------------------------------------------------------------------*/

	function get_sexos(){

		$SQL = "SELECT ug_sexos.cod_sexo,

						ug_sexos.sexo

				FROM ug_sexos

				WHERE ug_sexos.activo = 1

				ORDER BY sexo ASC";

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



	/*----------------------------------------------------------------------------------

	  Obtiene todos los tipos de identificación <<ug_tipos_documentos_identificacion>>

	----------------------------------------------------------------------------------*/

	function get_tipos_documentos_identificacion(){

		$SQL = "SELECT ug_tipos_documentos_identificacion.cod_tipo_documento_identificacion,

						ug_tipos_documentos_identificacion.tipo_documento_identificacion

				FROM ug_tipos_documentos_identificacion

				WHERE ug_tipos_documentos_identificacion.activo = 1

				ORDER BY ug_tipos_documentos_identificacion.cod_tipo_documento_identificacion ASC";

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

	/*----------------------------------------------------------------------------------

	  Obtiene todas las nacionalidades <<ug_nacionalidades>>

	----------------------------------------------------------------------------------*/

	function get_nacionalidades(){

		$SQL = "SELECT ug_nacionalidades.cod_nacionalidad,

						ug_nacionalidades.nacionalidad

				FROM ug_nacionalidades

				WHERE ug_nacionalidades.activo = 1

				ORDER BY ug_nacionalidades.nacionalidad ASC";

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

	/*----------------------------------------------------------------------------------

	  Obtiene las máscaras y placeholder de los campos de número de identidad y pasaporte.

	  <<geo_paises_mascaras>>

	----------------------------------------------------------------------------------*/

	function grl_obtener_paises_mascaras_nacionalidad($cod_nacionalidad)

	{

		$SQL = "SELECT

				    geo_paises.cod_pais,

				    geo_paises.pais,

				    ug_nacionalidades.cod_nacionalidad,

				    ug_nacionalidades.nacionalidad,

				    geo_paises_mascaras.mascara_identidad,

				    geo_paises_mascaras.marcador_identidad,

				    geo_paises_mascaras.mascara_pasaporte,

				    geo_paises_mascaras.marcador_pasaporte

				FROM

				    geo_paises_mascaras

				        INNER JOIN

				    geo_paises ON (geo_paises.cod_pais = geo_paises_mascaras.cod_pais)

				        INNER JOIN

				    ug_nacionalidades ON (ug_nacionalidades.cod_nacionalidad = geo_paises_mascaras.cod_nacionalidad)

				    WHERE

				    	geo_paises_mascaras.cod_nacionalidad = :cod_nacionalidad";

			$stmt = $this->db_conexion->prepare($SQL);

			$stmt->bindParam(":cod_nacionalidad",$cod_nacionalidad);

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



	/*----------------------------------------------------------------------------------

	  Obtiene las máscaras y placeholder de los campos de número de teléfono fijo y celular.

	  <<geo_paises_mascaras>>

	----------------------------------------------------------------------------------*/

	function grl_obtener_paises_mascaras_paises($cod_pais)

	{

		$SQL = "SELECT

				    geo_paises.cod_pais,

				    geo_paises.pais,

				    ug_nacionalidades.cod_nacionalidad,

				    ug_nacionalidades.nacionalidad,

				    geo_paises_mascaras.mascara_telefono_celular,

				    geo_paises_mascaras.marcador_telefono_celular,

				    geo_paises_mascaras.mascara_telefono_fijo,

				    geo_paises_mascaras.marcador_telefono_fijo

				FROM

				    geo_paises_mascaras

				        INNER JOIN

				    geo_paises ON (geo_paises.cod_pais = geo_paises_mascaras.cod_pais)

				        INNER JOIN

				    ug_nacionalidades ON (ug_nacionalidades.cod_nacionalidad = geo_paises_mascaras.cod_nacionalidad)

				    WHERE

				    	geo_paises_mascaras.cod_pais = :cod_pais";

			$stmt = $this->db_conexion->prepare($SQL);

			$stmt->bindParam(":cod_pais",$cod_pais);

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

	 * Obtiene el listado de todos los empleados.

	 */

	function get_listado_empleados_activos(){

		$SQL = "SELECT

					usu_usuarios.cod_usuario,

					CONCAT(usu_usuarios.nombre_1,

							' ',

                                        usu_usuarios.apellido_1) AS nombre

				FROM

					usu_usuarios

				WHERE

					activo = 1

				ORDER BY

					nombre ASC";

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
	function get_info_areas_evaluacion($cod_tipo_evaluacion){
		  $SQL = "CALL ug_areas_evaluacion_listado(:cod_tipo_evaluacion)";
			$stmt = $this->db_conexion->prepare($SQL);
			$stmt->bindParam(":cod_tipo_evaluacion", $cod_tipo_evaluacion);
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
	function get_info_indicadores_evaluacion($cod_area_evaluacion){
		  $SQL = "CALL ug_indicadores_evaluacion_listado(:cod_area_evaluacion)";
			$stmt = $this->db_conexion->prepare($SQL);
			$stmt->bindParam(":cod_area_evaluacion", $cod_area_evaluacion);
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
	function get_info_niveles_superacion($cod_tipo_evaluacion){
		  $SQL = "CALL ug_niveles_superacion_evaluacion_listado(:cod_tipo_evaluacion)";
			$stmt = $this->db_conexion->prepare($SQL);
			$stmt->bindParam(":cod_tipo_evaluacion", $cod_tipo_evaluacion);
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
	* Obtiene parámetro evaluación psicológica.
	*/
	function get_evaluacion_psicologica($cod_modulo){
			$SQL="SELECT ug_evaluacion_psicologica(:cod_modulo) AS valor";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_modulo", $cod_modulo);
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
	* Obtiene Listado Evaluaciones.
	*/
	function evaluaciones_listado($cod_modulo,$cod_tipo_evaluacion,$responsable,$fecha_inicio,$fecha_final,$inicio,$limite){
		   $fecha_inicio = ($fecha_inicio == '' ? NULL : $fecha_inicio);
		   $fecha_final  = ($fecha_final == '' ? NULL : $fecha_final);
		   $SQL="CALL ug_evaluaciones_listado(:cod_modulo,:cod_tipo_evaluacion,:responsable,:fecha_inicio,:fecha_final,:inicio,:limite)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_modulo", $cod_modulo);
			 $stmt->bindParam(":cod_tipo_evaluacion", $cod_tipo_evaluacion);
			 $stmt->bindParam(":responsable", $responsable);
			 $stmt->bindParam(":fecha_inicio", $fecha_inicio);
			 $stmt->bindParam(":fecha_final", $fecha_final);
			 $stmt->bindParam(":inicio", $inicio);
			 $stmt->bindParam(":limite", $limite);
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
	* Obtiene Listado Evaluaciones Totales.
	*/
	function evaluaciones_listado_totales($cod_modulo,$cod_tipo_evaluacion,$responsable,$fecha_inicio,$fecha_final){
		   $fecha_inicio = ($fecha_inicio == '' ? NULL : $fecha_inicio);
		   $fecha_final  = ($fecha_final == '' ? NULL : $fecha_final);
		   $SQL="CALL ug_evaluaciones_listado_totales(:cod_modulo,:cod_tipo_evaluacion,:responsable,:fecha_inicio,:fecha_final)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_modulo", $cod_modulo);
			 $stmt->bindParam(":cod_tipo_evaluacion", $cod_tipo_evaluacion);
			 $stmt->bindParam(":responsable", $responsable);
			 $stmt->bindParam(":fecha_inicio", $fecha_inicio);
			 $stmt->bindParam(":fecha_final", $fecha_final);
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
	* Obtiene eevaluaciones.
	*/
	function buscar_evaluacion($cod_modulo,$cod_tipo_evaluacion,$cod_caso,$cod_tipo_persona,$cod_persona,$cod_evaluacion){
			$SQL="CALL ug_buscar_evaluacion(:cod_modulo,:cod_tipo_evaluacion,:cod_caso,:cod_tipo_persona,:cod_persona,:cod_evaluacion)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_modulo", $cod_modulo);
			 $stmt->bindParam(":cod_tipo_evaluacion", $cod_tipo_evaluacion);
			 $stmt->bindParam(":cod_caso", $cod_caso);
			 $stmt->bindParam(":cod_tipo_persona", $cod_tipo_persona);
			 $stmt->bindParam(":cod_persona", $cod_persona);
			 $stmt->bindParam(":cod_evaluacion", $cod_evaluacion);
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
	* Obtiene eevaluaciones.
	*/
	function ug_detalles_evaluacion_listado($cod_modulo,$cod_tipo_evaluacion,$cod_caso,$cod_tipo_persona,$cod_persona,$cod_evaluacion,$cod_area){
			$SQL="CALL ug_detalles_evaluacion_listado(:cod_modulo,:cod_tipo_evaluacion,:cod_caso,:cod_tipo_persona,:cod_persona,:cod_evaluacion,:cod_area)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_modulo", $cod_modulo);
			 $stmt->bindParam(":cod_tipo_evaluacion", $cod_tipo_evaluacion);
			 $stmt->bindParam(":cod_caso", $cod_caso);
			 $stmt->bindParam(":cod_tipo_persona", $cod_tipo_persona);
			 $stmt->bindParam(":cod_persona", $cod_persona);
			 $stmt->bindParam(":cod_evaluacion", $cod_evaluacion);
			 $stmt->bindParam(":cod_area", $cod_area);
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
	* Guarda eevaluaciones.
	*/
	function ug_guardar_evaluacion($cod_modulo,$cod_tipo_evaluacion,$cod_caso,$cod_tipo_persona,$cod_persona,$cod_evaluacion,$observacion,$flag,$bloqueado,$activo,$usuario){
			$SQL="CALL ug_guardar_evaluacion(:cod_modulo,:cod_tipo_evaluacion,:cod_caso,:cod_tipo_persona,:cod_persona,:cod_evaluacion, :observacion, :flag, :bloqueado, :activo, :usuario)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_modulo", $cod_modulo);
			 $stmt->bindParam(":cod_tipo_evaluacion", $cod_tipo_evaluacion);
			 $stmt->bindParam(":cod_caso", $cod_caso);
			 $stmt->bindParam(":cod_tipo_persona", $cod_tipo_persona);
			 $stmt->bindParam(":cod_persona", $cod_persona);
			 $stmt->bindParam(":cod_evaluacion", $cod_evaluacion);
			 $stmt->bindParam(":observacion", $observacion);
			 $stmt->bindParam(":flag", $flag);
			 $stmt->bindParam(":bloqueado", $bloqueado);
			 $stmt->bindParam(":activo", $activo);
			 $stmt->bindParam(":usuario", $usuario);
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
	* Guarda Detalle Evaluación.
	*/
	function ug_guardar_detalle_evaluacion($cod_modulo,$cod_tipo_evaluacion,$cod_caso,$cod_tipo_persona,$cod_persona,$cod_evaluacion,$cod_area, $cod_indicador,$cod_nivel,$puntuacion,$observacion,$activo,$usuario){
		  $cod_nivel = (($cod_nivel == 0 || $cod_nivel == '') ? NULL : $cod_nivel);
			$SQL="CALL ug_guardar_detalle_evaluacion(:cod_modulo,:cod_tipo_evaluacion,:cod_caso,:cod_tipo_persona,:cod_persona,:cod_evaluacion,:cod_area,:cod_indicador,:cod_nivel,:puntuacion,:observacion,:activo,:usuario)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_modulo", $cod_modulo);
			 $stmt->bindParam(":cod_tipo_evaluacion", $cod_tipo_evaluacion);
			 $stmt->bindParam(":cod_caso", $cod_caso);
			 $stmt->bindParam(":cod_tipo_persona", $cod_tipo_persona);
			 $stmt->bindParam(":cod_persona", $cod_persona);
			 $stmt->bindParam(":cod_evaluacion", $cod_evaluacion);
			 $stmt->bindParam(":cod_area", $cod_area);
			 $stmt->bindParam(":cod_indicador", $cod_indicador);
			 $stmt->bindParam(":cod_nivel", $cod_nivel);
			 $stmt->bindParam(":puntuacion", $puntuacion);
			 $stmt->bindParam(":observacion", $observacion);
			 $stmt->bindParam(":activo", $activo);
			 $stmt->bindParam(":usuario", $usuario);
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
	* Obtiene Listado Evaluadores.
	*/
	function get_evaluadores_evaluacion($cod_modulo,$cod_tipo_evaluacion,$cod_caso,$cod_tipo_persona,$cod_persona){
			 $SQL="CALL ug_obtener_evaluadores_evaluacion(:cod_modulo,:cod_tipo_evaluacion,:cod_caso,:cod_tipo_persona,:cod_persona)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_modulo", $cod_modulo);
			 $stmt->bindParam(":cod_tipo_evaluacion", $cod_tipo_evaluacion);
			 $stmt->bindParam(":cod_caso", $cod_caso);
			 $stmt->bindParam(":cod_tipo_persona", $cod_tipo_persona);
			 $stmt->bindParam(":cod_persona", $cod_persona);
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
	* Obtiene Resumen Evaluaciones.
	*/
	function get_resumen_evaluaciones($cod_modulo,$cod_tipo_evaluacion,$cod_caso,$cod_tipo_persona,$cod_persona,$cod_usuario){
			 $SQL="CALL ug_obtener_resumen_evaluaciones(:cod_modulo,:cod_tipo_evaluacion,:cod_caso,:cod_tipo_persona,:cod_persona,:cod_usuario)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_modulo", $cod_modulo);
			 $stmt->bindParam(":cod_tipo_evaluacion", $cod_tipo_evaluacion);
			 $stmt->bindParam(":cod_caso", $cod_caso);
			 $stmt->bindParam(":cod_tipo_persona", $cod_tipo_persona);
			 $stmt->bindParam(":cod_persona", $cod_persona);
			 $stmt->bindParam(":cod_usuario", $cod_usuario);
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
	* Obtiene parámetro ficha psicosocial.
	*/
	function ficha_psicosocial($cod_modulo){
			$SQL="SELECT ug_ficha_psicosocial(:cod_modulo) AS valor";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_modulo", $cod_modulo);
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
	* Guarda Fichas.
	*/
	function guardar_ficha($cod_modulo,$cod_tipo_ficha,$cod_caso,$cod_tipo_persona,$cod_persona,$drogas,$ingresos,$egresos,$observacion,$flag,$usuario){
			$SQL="CALL ug_guardar_ficha(:cod_modulo,:cod_tipo_ficha,:cod_caso,:cod_tipo_persona,:cod_persona,:drogas,:ingresos,:egresos,:observacion,:flag,:usuario)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_modulo", $cod_modulo);
			 $stmt->bindParam(":cod_tipo_ficha", $cod_tipo_ficha);
			 $stmt->bindParam(":cod_caso", $cod_caso);
			 $stmt->bindParam(":cod_tipo_persona", $cod_tipo_persona);
			 $stmt->bindParam(":cod_persona", $cod_persona);
			 $stmt->bindParam(":drogas", $drogas);
			 $stmt->bindParam(":ingresos", $ingresos);
			 $stmt->bindParam(":egresos", $egresos);
			 $stmt->bindParam(":observacion", $observacion);
			 $stmt->bindParam(":flag", $flag);
			 $stmt->bindParam(":usuario", $usuario);
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
	* Guarda Fichas Enfermedades.
	*/
	function guardar_fichas_enfermedades($cod_modulo,$cod_tipo_ficha,$cod_caso,$cod_tipo_persona,$cod_persona,$cod_enfermedad,$cod_tipo_enfermedad,$tiempo,$medicamento,$observacion,$flag,$usuario){
		  $tiempo.='-01';
			$SQL="CALL ug_guardar_fichas_enfermedades(:cod_modulo,:cod_tipo_ficha,:cod_caso,:cod_tipo_persona,:cod_persona,:cod_enfermedad,:cod_tipo_enfermedad,:tiempo,:medicamento,:observacion,:flag,:usuario)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_modulo", $cod_modulo);
			 $stmt->bindParam(":cod_tipo_ficha", $cod_tipo_ficha);
			 $stmt->bindParam(":cod_caso", $cod_caso);
			 $stmt->bindParam(":cod_tipo_persona", $cod_tipo_persona);
			 $stmt->bindParam(":cod_persona", $cod_persona);
			 $stmt->bindParam(":cod_enfermedad", $cod_enfermedad);
			 $stmt->bindParam(":cod_tipo_enfermedad", $cod_tipo_enfermedad);
			 $stmt->bindParam(":tiempo", $tiempo);
			 $stmt->bindParam(":medicamento", $medicamento);
			 $stmt->bindParam(":observacion", $observacion);
			 $stmt->bindParam(":flag", $flag);
			 $stmt->bindParam(":usuario", $usuario);
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
	* Guarda Fichas Familiares.
	*/
	function guardar_fichas_familiares($cod_modulo,$cod_tipo_ficha,$cod_caso,$cod_tipo_persona,$cod_persona,$cod_persona_familiar,$cod_parentesco,$direccion,$telefono,$flag,$usuario){
			$SQL="CALL ug_guardar_fichas_familiares(:cod_modulo,:cod_tipo_ficha,:cod_caso,:cod_tipo_persona,:cod_persona,:cod_persona_familiar,:cod_parentesco,:direccion,:telefono,:flag,:usuario)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_modulo", $cod_modulo);
			 $stmt->bindParam(":cod_tipo_ficha", $cod_tipo_ficha);
			 $stmt->bindParam(":cod_caso", $cod_caso);
			 $stmt->bindParam(":cod_tipo_persona", $cod_tipo_persona);
			 $stmt->bindParam(":cod_persona", $cod_persona);
			 $stmt->bindParam(":cod_persona_familiar", $cod_persona_familiar);
			 $stmt->bindParam(":cod_parentesco", $cod_parentesco);
			 $stmt->bindParam(":direccion", $direccion);
			 $stmt->bindParam(":telefono", $telefono);
			 $stmt->bindParam(":flag", $flag);
			 $stmt->bindParam(":usuario", $usuario);
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
	* Guarda Fichas Grupos Sociales.
	*/
	function guardar_fichas_grupos_sociales($cod_modulo,$cod_tipo_ficha,$cod_caso,$cod_tipo_persona,$cod_persona,$cod_grupo_social,$tiempo,$observaciones,$flag,$usuario){
		  $tiempo.='-01';
			$SQL="CALL ug_guardar_fichas_grupos_sociales(:cod_modulo,:cod_tipo_ficha,:cod_caso,:cod_tipo_persona,:cod_persona,:cod_grupo_social,:tiempo,:observaciones,:flag,:usuario)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_modulo", $cod_modulo);
			 $stmt->bindParam(":cod_tipo_ficha", $cod_tipo_ficha);
			 $stmt->bindParam(":cod_caso", $cod_caso);
			 $stmt->bindParam(":cod_tipo_persona", $cod_tipo_persona);
			 $stmt->bindParam(":cod_persona", $cod_persona);
			 $stmt->bindParam(":cod_grupo_social", $cod_grupo_social);
			 $stmt->bindParam(":tiempo", $tiempo);
			 $stmt->bindParam(":observaciones", $observaciones);
			 $stmt->bindParam(":flag", $flag);
			 $stmt->bindParam(":usuario", $usuario);
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
    * Guarda Fichas Datos Financieros.
    */
    function guardar_fichas_datos_financieros($cod_modulo,$cod_tipo_ficha,$cod_caso,$cod_tipo_persona,$cod_persona,$cod_institucion_economica,$cod_periocidad,$monto,$observaciones,$flag,$usuario){
        $monto = ($monto == '' ? NULL : $monto);
        $SQL="CALL ug_guardar_fichas_economicas(:cod_modulo,:cod_tipo_ficha,:cod_caso,:cod_tipo_persona,:cod_persona,:cod_institucion_economica,:cod_periocidad,:monto,:observaciones,:flag,:usuario)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_modulo", $cod_modulo);
        $stmt->bindParam(":cod_tipo_ficha", $cod_tipo_ficha);
        $stmt->bindParam(":cod_caso", $cod_caso);
        $stmt->bindParam(":cod_tipo_persona", $cod_tipo_persona);
        $stmt->bindParam(":cod_persona", $cod_persona);
        $stmt->bindParam(":cod_institucion_economica", $cod_institucion_economica);
        $stmt->bindParam(":cod_periocidad", $cod_periocidad);
        $stmt->bindParam(":monto", $monto);
        $stmt->bindParam(":observaciones", $observaciones);
        $stmt->bindParam(":flag", $flag);
        $stmt->bindParam(":usuario", $usuario);
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
    * Guarda Fichas Datos Financieros Áreas de Apoyo.
    */
    function guardar_fichas_datos_financieros_area_apoyo($cod_modulo,$cod_tipo_ficha,$cod_caso,$cod_tipo_persona,$cod_persona,$cod_institucion_economica,$cod_area_apoyo,$flag,$usuario){
        $SQL="CALL ug_guardar_fichas_economicas_areas_apoyo(:cod_modulo,:cod_tipo_ficha,:cod_caso,:cod_tipo_persona,:cod_persona,:cod_institucion_economica,:cod_area_apoyo,:flag,:usuario)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_modulo", $cod_modulo);
        $stmt->bindParam(":cod_tipo_ficha", $cod_tipo_ficha);
        $stmt->bindParam(":cod_caso", $cod_caso);
        $stmt->bindParam(":cod_tipo_persona", $cod_tipo_persona);
        $stmt->bindParam(":cod_persona", $cod_persona);
        $stmt->bindParam(":cod_institucion_economica", $cod_institucion_economica);
        $stmt->bindParam(":cod_area_apoyo", $cod_area_apoyo);
        $stmt->bindParam(":flag", $flag);
        $stmt->bindParam(":usuario", $usuario);
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
    * Guarda Fichas Datos Financieros Áreas de Apoyo.
    */
    function eliminar_fichas_datos_financieros_area_apoyo($cod_modulo,$cod_tipo_ficha,$cod_caso,$cod_tipo_persona,$cod_persona,$cod_institucion_economica,$flag,$usuario){
        $SQL = "UPDATE
                     ug_fichas_economicas_areas_apoyo
                 SET
                     activo        = :flag,
                     user_insert   = :usuario,
                     date_insert   = CURRENT_TIMESTAMP
                 WHERE  cod_modulo=:cod_modulo
                 AND cod_tipo_ficha=:cod_tipo_ficha
                 AND cod_caso=:cod_caso
                 AND cod_tipo_persona=:cod_tipo_persona
                 AND cod_persona=:cod_persona
                 AND cod_institucion_economica=:cod_institucion_economica";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_modulo", $cod_modulo);
        $stmt->bindParam(":cod_tipo_ficha", $cod_tipo_ficha);
        $stmt->bindParam(":cod_caso", $cod_caso);
        $stmt->bindParam(":cod_tipo_persona", $cod_tipo_persona);
        $stmt->bindParam(":cod_persona", $cod_persona);
        $stmt->bindParam(":cod_institucion_economica", $cod_institucion_economica);
        $stmt->bindParam(":flag", $flag);
        $stmt->bindParam(":usuario", $usuario);
        try{
            $stmt->execute();
            if ($stmt->rowCount() > 0){
                $resultado = 1; //Exito en actualización
            }
        }
        catch(PDOException $e)
        {
            $resultado = 0; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }
	/*
	* Listado Fichas.
	*/
	function fichas_listado($modulo,$tipo_ficha,$responsable,$fecha_inicio,$fecha_final,$inicio,$limite){
		   $fecha_inicio = ($fecha_inicio == '' ? NULL : $fecha_inicio);
			 $fecha_final  = ($fecha_final == '' ? NULL : $fecha_final);
			 $SQL="CALL ug_fichas_listado(:modulo,:tipo_ficha,:responsable,:fecha_inicio,:fecha_final,:inicio,:limite)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":modulo",       $modulo);
			 $stmt->bindParam(":tipo_ficha",   $tipo_ficha);
			 $stmt->bindParam(":responsable",  $responsable);
			 $stmt->bindParam(":fecha_inicio", $fecha_inicio);
			 $stmt->bindParam(":fecha_final",  $fecha_final);
			 $stmt->bindParam(":inicio", $inicio);
			 $stmt->bindParam(":limite", $limite);
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
	* Totales Fichas.
	*/
	function fichas_listado_totales($modulo,$tipo_ficha,$responsable,$fecha_inicio,$fecha_final){
		   $fecha_inicio = ($fecha_inicio == '' ? NULL : $fecha_inicio);
			 $fecha_final  = ($fecha_final == '' ? NULL : $fecha_final);
			 $SQL="CALL ug_fichas_listado_totales(:modulo,:tipo_ficha,:responsable,:fecha_inicio,:fecha_final)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":modulo",       $modulo);
			 $stmt->bindParam(":tipo_ficha",   $tipo_ficha);
			 $stmt->bindParam(":responsable",  $responsable);
			 $stmt->bindParam(":fecha_inicio", $fecha_inicio);
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
	* Listado Fichas Enfermedades.
	*/
	function fichas_enfermedades_listado($cod_modulo,$cod_tipo_ficha,$cod_caso,$cod_tipo_persona,$cod_persona){
			$SQL="CALL ug_fichas_enfermedades_listado(:cod_modulo,:cod_tipo_ficha,:cod_caso,:cod_tipo_persona,:cod_persona)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_modulo", $cod_modulo);
			 $stmt->bindParam(":cod_tipo_ficha", $cod_tipo_ficha);
			 $stmt->bindParam(":cod_caso", $cod_caso);
			 $stmt->bindParam(":cod_tipo_persona", $cod_tipo_persona);
			 $stmt->bindParam(":cod_persona", $cod_persona);
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
	* Listado Fichas Familiares.
	*/
	function fichas_familiares_listado($cod_modulo,$cod_tipo_ficha,$cod_caso,$cod_tipo_persona,$cod_persona){
			$SQL="CALL ug_fichas_familiares_listado(:cod_modulo,:cod_tipo_ficha,:cod_caso,:cod_tipo_persona,:cod_persona)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_modulo", $cod_modulo);
			 $stmt->bindParam(":cod_tipo_ficha", $cod_tipo_ficha);
			 $stmt->bindParam(":cod_caso", $cod_caso);
			 $stmt->bindParam(":cod_tipo_persona", $cod_tipo_persona);
			 $stmt->bindParam(":cod_persona", $cod_persona);
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
	* Listado Fichas Grupos Sociales.
	*/
	function fichas_grupos_sociales_listado($cod_modulo,$cod_tipo_ficha,$cod_caso,$cod_tipo_persona,$cod_persona){
			$SQL="CALL ug_fichas_grupos_sociales_listado(:cod_modulo,:cod_tipo_ficha,:cod_caso,:cod_tipo_persona,:cod_persona)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_modulo", $cod_modulo);
			 $stmt->bindParam(":cod_tipo_ficha", $cod_tipo_ficha);
			 $stmt->bindParam(":cod_caso", $cod_caso);
			 $stmt->bindParam(":cod_tipo_persona", $cod_tipo_persona);
			 $stmt->bindParam(":cod_persona", $cod_persona);
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
    * Listado Fichas Datos FInancieros.
    */
    function fichas_datos_financieros_listado($cod_modulo,$cod_tipo_ficha,$cod_caso,$cod_tipo_persona,$cod_persona){
        $SQL="CALL ug_fichas_economicas_listado(:cod_modulo,:cod_tipo_ficha,:cod_caso,:cod_tipo_persona,:cod_persona)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_modulo", $cod_modulo);
        $stmt->bindParam(":cod_tipo_ficha", $cod_tipo_ficha);
        $stmt->bindParam(":cod_caso", $cod_caso);
        $stmt->bindParam(":cod_tipo_persona", $cod_tipo_persona);
        $stmt->bindParam(":cod_persona", $cod_persona);
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
	* Listado Enfermedades.
	*/
	function get_enfermedades($listado){
			$SQL="CALL ug_get_enfermedades(:listado)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":listado", $listado);
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
	* Listado Grupos Sociales.
	*/
	function get_grupos_sociales($listado){
			$SQL="CALL ug_get_grupos_sociales(:listado)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":listado", $listado);
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
	* Listado Tipos Enfermedades.
	*/
	function get_tipos_enfermedades($listado){
			$SQL="CALL ug_get_tipos_enfermedades(:listado)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":listado", $listado);
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
	* Listado Parentescos.
	*/
	function get_parentescos($listado){
			$SQL="CALL ug_get_parentescos(:listado)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":listado", $listado);
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
	* Listado Ficha.
	*/
	function get_ficha($cod_modulo,$cod_tipo_ficha,$cod_caso,$cod_tipo_persona,$cod_persona){
		$SQL="CALL ug_get_ficha(:cod_modulo,:cod_tipo_ficha,:cod_caso,:cod_tipo_persona,:cod_persona)";
		 $stmt = $this->db_conexion->prepare($SQL);
		 $stmt->bindParam(":cod_modulo", $cod_modulo);
		 $stmt->bindParam(":cod_tipo_ficha", $cod_tipo_ficha);
		 $stmt->bindParam(":cod_caso", $cod_caso);
		 $stmt->bindParam(":cod_tipo_persona", $cod_tipo_persona);
		 $stmt->bindParam(":cod_persona", $cod_persona);
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
	* Obtiene parámetro paginado.
	*/
	function get_paginado($cod_modulo){
			$SQL="SELECT ug_paginado(:cod_modulo) AS valor";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_modulo", $cod_modulo);
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
	* Obtiene parámetro país.
	*/
	function get_pais($cod_modulo){
			$SQL="SELECT ug_pais(:cod_modulo) AS valor";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_modulo", $cod_modulo);
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
	* Obtiene parámetro asj.
	*/
	function get_asj($cod_modulo){
			$SQL="SELECT ug_asj(:cod_modulo) AS valor";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_modulo", $cod_modulo);
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
	* Obtiene parámetro get_autoridad.
	*/
	function get_autoridad($cod_modulo){
			$SQL="SELECT ug_autoridad(:cod_modulo) AS valor";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_modulo", $cod_modulo);
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
	* Obtiene parámetro get_autoridad.
	*/
	function get_modulo($cod_modulo){
			$SQL="SELECT ug_modulo(:cod_modulo) AS valor";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_modulo", $cod_modulo);
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
    * Obtiene parámetro get_autoridad.
    */
    function get_gerencia($cod_modulo){
        $SQL="SELECT ug_gerencia_modulo(:cod_modulo) AS valor";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_modulo", $cod_modulo);
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
	* Guarda Empresas.
	*/
	function guardar_empresa($cod_modulo,$id,$empresa,$rtn,$web,$direccion,$telefono,$observacion,$flag,$usuario){
			$SQL="CALL ug_guardar_empresa(:cod_modulo,:id,:empresa,:rtn,:web,:direccion,:telefono,:observacion,:flag,:usuario)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_modulo", $cod_modulo);
			 $stmt->bindParam(":id", $id);
			 $stmt->bindParam(":empresa", $empresa);
			 $stmt->bindParam(":rtn", $rtn);
			 $stmt->bindParam(":web", $web);
			 $stmt->bindParam(":direccion", $direccion);
			 $stmt->bindParam(":telefono", $telefono);
			 $stmt->bindParam(":observacion", $observacion);
			 $stmt->bindParam(":flag", $flag);
			 $stmt->bindParam(":usuario", $usuario);
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
	* Guarda Empresas contactos.
	*/
	function guardar_empresas_contactos($cod_empresa,$id,$contacto,$telefono1,$telefono2,$correo,$observacion,$flag,$usuario){
			$SQL="CALL ug_guardar_empresas_contactos(:cod_empresa,:id,:contacto,:telefono1,:telefono2,:correo,:observacion,:flag,:usuario)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_empresa", $cod_empresa);
			 $stmt->bindParam(":id", $id);
			 $stmt->bindParam(":contacto", $contacto);
			 $stmt->bindParam(":telefono1", $telefono1);
			 $stmt->bindParam(":telefono2", $telefono2);
			 $stmt->bindParam(":correo", $correo);
			 $stmt->bindParam(":observacion", $observacion);
			 $stmt->bindParam(":flag", $flag);
			 $stmt->bindParam(":usuario", $usuario);
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
	* Listado Empresas.
	*/
	function empresas_listado($modulo,$responsable,$fecha_inicio,$fecha_final,$inicio,$limite){
		   $fecha_inicio = ($fecha_inicio == '' ? NULL : $fecha_inicio);
			 $fecha_final  = ($fecha_final == '' ? NULL : $fecha_final);
			 $SQL="CALL ug_empresas_listado(:modulo,:responsable,:fecha_inicio,:fecha_final,:inicio,:limite)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":modulo",       $modulo);
			 $stmt->bindParam(":responsable",  $responsable);
			 $stmt->bindParam(":fecha_inicio", $fecha_inicio);
			 $stmt->bindParam(":fecha_final",  $fecha_final);
			 $stmt->bindParam(":inicio", $inicio);
			 $stmt->bindParam(":limite", $limite);
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
	* Totales Empresas.
	*/
	function empresas_listado_totales($modulo,$responsable,$fecha_inicio,$fecha_final){
		   $fecha_inicio = ($fecha_inicio == '' ? NULL : $fecha_inicio);
			 $fecha_final  = ($fecha_final == '' ? NULL : $fecha_final);
			 $SQL="CALL ug_empresas_listado_totales(:modulo,:responsable,:fecha_inicio,:fecha_final)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":modulo",       $modulo);
			 $stmt->bindParam(":responsable",  $responsable);
			 $stmt->bindParam(":fecha_inicio", $fecha_inicio);
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
	* Listado Empresas.
	*/
	function get_empresas($cod_empresa){
		$SQL="CALL ug_get_empresa(:cod_empresa)";
		 $stmt = $this->db_conexion->prepare($SQL);
		 $stmt->bindParam(":cod_empresa", $cod_empresa);
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
	* Listado Fichas Enfermedades.
	*/
	function empresas_contactos_listado($cod_empresa){
			$SQL="CALL ug_empresas_contactos_listado(:cod_empresa)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_empresa", $cod_empresa);
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
	* Obtiene la información de la contacto por empresa.
	*/
	function get_info_empresa_contacto($cod_contacto){
								 $SQL="SELECT
															ug_contactos.cod_empresa,
															ug_contactos.contacto,
															ug_contactos.telefono1,
															ug_contactos.telefono2,
															ug_contactos.correo,
															ug_contactos.observacion
											 FROM   ug_contactos
											 WHERE  ug_contactos.cod_contacto = :cod_contacto";
											 $stmt = $this->db_conexion->prepare($SQL);
											 $stmt->bindParam(":cod_contacto", $cod_contacto);
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
	* Actualiza personas por orden de captura.
	*/
	function ug_actualizar_contacto_empresa($cod_empresa,$cod_contacto,$contacto,$telefono1,$telefono2,
																	 $correo,$observacion,$flag,$cod_usuario){

		$SQL = "UPDATE
								ug_contactos
						SET
								cod_empresa       = :cod_empresa,
								contacto          = :contacto,
								telefono1         = :telefono1,
								telefono2         = :telefono2,
								correo            = :correo,
								observacion       = :observacion,
								user_insert       = :cod_usuario,
								activo            = :flag,
								date_insert       = CURRENT_TIMESTAMP
						WHERE
								cod_contacto = :cod_contacto";
		$stmt = $this->db_conexion->prepare($SQL);
		$stmt->bindParam(":cod_empresa",$cod_empresa);
		$stmt->bindParam(":contacto",$contacto);
		$stmt->bindParam(":telefono1",$telefono1);
		$stmt->bindParam(":telefono2",$telefono2);
		$stmt->bindParam(":correo",$correo);
		$stmt->bindParam(":observacion",$observacion);
		$stmt->bindParam(":cod_usuario",$cod_usuario);
		$stmt->bindParam(":flag",$flag);
		$stmt->bindParam(":cod_contacto",$cod_contacto);
		try{
				$stmt->execute();
				if ($stmt->rowCount() > 0){
				$resultado = 1; //Exito en actualización
			}
		}
		catch(PDOException $e)
		{
				$resultado = 0; //Error en actualización
		}
		$stmt->closeCursor();
		return $resultado;
	}
	/*
	* Obtiene la información de la familiar por psicosocial.
	*/
	function get_info_familiar_psicosocial($cod_modulo,$cod_tipo_ficha,$cod_caso,
	                  $cod_tipo_persona,$cod_persona,$cod_familiar){
								 $SQL="SELECT
															ug_fichas_familiares.cod_persona_familiar,
															ug_fichas_familiares.cod_parentesco,
															ug_fichas_familiares.direccion,
															ug_fichas_familiares.telefono
											 FROM   ug_fichas_familiares
											 WHERE  ug_fichas_familiares.cod_modulo = :cod_modulo
											 AND    ug_fichas_familiares.cod_tipo_ficha = :cod_tipo_ficha
											 AND    ug_fichas_familiares.cod_caso = :cod_caso
											 AND    ug_fichas_familiares.cod_tipo_persona = :cod_tipo_persona
											 AND    ug_fichas_familiares.cod_persona = :cod_persona
											 AND    ug_fichas_familiares.cod_persona_familiar = :cod_persona_familiar";
											 $stmt = $this->db_conexion->prepare($SQL);
											 $stmt->bindParam(":cod_modulo", $cod_modulo);
											 $stmt->bindParam(":cod_tipo_ficha", $cod_tipo_ficha);
											 $stmt->bindParam(":cod_caso", $cod_caso);
											 $stmt->bindParam(":cod_tipo_persona", $cod_tipo_persona);
											 $stmt->bindParam(":cod_persona", $cod_persona);
											 $stmt->bindParam(":cod_persona_familiar", $cod_familiar);
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
	* Obtiene la información de la enfermedad por psicosocial.
	*/
	function get_info_enfermedad_psicosocial($cod_modulo,$cod_tipo_ficha,$cod_caso,
	                  $cod_tipo_persona,$cod_persona,$cod_enfermedad){
								 $SQL="SELECT
															ug_fichas_enfermedades.cod_enfermedad,
															ug_fichas_enfermedades.cod_tipo_enfermedad,
															ug_fichas_enfermedades.tiempo_padecimiento,
															ug_fichas_enfermedades.medicamentos,
															ug_fichas_enfermedades.observaciones
											 FROM   ug_fichas_enfermedades
											 WHERE  ug_fichas_enfermedades.cod_modulo = :cod_modulo
											 AND    ug_fichas_enfermedades.cod_tipo_ficha = :cod_tipo_ficha
											 AND    ug_fichas_enfermedades.cod_caso = :cod_caso
											 AND    ug_fichas_enfermedades.cod_tipo_persona = :cod_tipo_persona
											 AND    ug_fichas_enfermedades.cod_persona = :cod_persona
											 AND    ug_fichas_enfermedades.cod_enfermedad = :cod_enfermedad";
											 $stmt = $this->db_conexion->prepare($SQL);
											 $stmt->bindParam(":cod_modulo", $cod_modulo);
											 $stmt->bindParam(":cod_tipo_ficha", $cod_tipo_ficha);
											 $stmt->bindParam(":cod_caso", $cod_caso);
											 $stmt->bindParam(":cod_tipo_persona", $cod_tipo_persona);
											 $stmt->bindParam(":cod_persona", $cod_persona);
											 $stmt->bindParam(":cod_enfermedad", $cod_enfermedad);
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
	* Obtiene la información de la enfermedad por psicosocial.
	*/
	function get_info_gruposocial_psicosocial($cod_modulo,$cod_tipo_ficha,$cod_caso,
	                  $cod_tipo_persona,$cod_persona,$cod_grupo_social){
								 $SQL="SELECT
															ug_fichas_grupos_sociales.cod_grupo_social,
															ug_fichas_grupos_sociales.tiempo_permanencia,
															ug_fichas_grupos_sociales.observacion
											 FROM   ug_fichas_grupos_sociales
											 WHERE  ug_fichas_grupos_sociales.cod_modulo = :cod_modulo
											 AND    ug_fichas_grupos_sociales.cod_tipo_ficha = :cod_tipo_ficha
											 AND    ug_fichas_grupos_sociales.cod_caso = :cod_caso
											 AND    ug_fichas_grupos_sociales.cod_tipo_persona = :cod_tipo_persona
											 AND    ug_fichas_grupos_sociales.cod_persona = :cod_persona
											 AND    ug_fichas_grupos_sociales.cod_grupo_social = :cod_grupo_social";
											 $stmt = $this->db_conexion->prepare($SQL);
											 $stmt->bindParam(":cod_modulo", $cod_modulo);
											 $stmt->bindParam(":cod_tipo_ficha", $cod_tipo_ficha);
											 $stmt->bindParam(":cod_caso", $cod_caso);
											 $stmt->bindParam(":cod_tipo_persona", $cod_tipo_persona);
											 $stmt->bindParam(":cod_persona", $cod_persona);
											 $stmt->bindParam(":cod_grupo_social", $cod_grupo_social);
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
    * Obtiene la información de la datofinanciero por psicosocial.
    */
    function get_info_datofinanciero_psicosocial($cod_modulo,$cod_tipo_ficha,$cod_caso,
                                              $cod_tipo_persona,$cod_persona,$cod_institucion_economica){
        $SQL="SELECT
					ug_fichas_economicas.cod_institucion_economica,
					ug_fichas_economicas.cod_periocidad,
					ug_fichas_economicas.monto,
					ug_fichas_economicas.observacion,
            (SELECT        GROUP_CONCAT(ug_fichas_economicas_areas_apoyo.cod_area_apoyo SEPARATOR ',')
            FROM        ug_fichas_economicas_areas_apoyo
            WHERE ug_fichas_economicas_areas_apoyo.cod_modulo=ug_fichas_economicas.cod_modulo
            AND ug_fichas_economicas_areas_apoyo.cod_tipo_ficha=ug_fichas_economicas.cod_tipo_ficha
            AND ug_fichas_economicas_areas_apoyo.cod_caso=ug_fichas_economicas.cod_caso
            AND ug_fichas_economicas_areas_apoyo.cod_tipo_persona=ug_fichas_economicas.cod_tipo_persona
            AND ug_fichas_economicas_areas_apoyo.cod_persona=ug_fichas_economicas.cod_persona
            AND ug_fichas_economicas_areas_apoyo.cod_institucion_economica=ug_fichas_economicas.cod_institucion_economica)
            as cod_area_apoyo
			 FROM   ug_fichas_economicas
			 WHERE  ug_fichas_economicas.cod_modulo = :cod_modulo
			 AND    ug_fichas_economicas.cod_tipo_ficha = :cod_tipo_ficha
			 AND    ug_fichas_economicas.cod_caso = :cod_caso
			 AND    ug_fichas_economicas.cod_tipo_persona = :cod_tipo_persona
			 AND    ug_fichas_economicas.cod_persona = :cod_persona
			 AND    ug_fichas_economicas.cod_institucion_economica = :cod_institucion_economica;";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_modulo", $cod_modulo);
        $stmt->bindParam(":cod_tipo_ficha", $cod_tipo_ficha);
        $stmt->bindParam(":cod_caso", $cod_caso);
        $stmt->bindParam(":cod_tipo_persona", $cod_tipo_persona);
        $stmt->bindParam(":cod_persona", $cod_persona);
        $stmt->bindParam(":cod_institucion_economica", $cod_institucion_economica);
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
	* Listado Marcas.
	*/
	function get_marcas($listado){
			$SQL="CALL ug_get_marcas(:listado)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":listado", $listado);
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
	* Listado Modelos.
	*/
	function get_modelos($cod_marca){
			$SQL="CALL ug_get_modelos(:cod_marca)";
			 $stmt = $this->db_conexion->prepare($SQL);
			 $stmt->bindParam(":cod_marca", $cod_marca);
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
	* Inserta una nuevo marca a la base de datos.
	*/
    function send_insertar_marca($marca,
                                    $user_insert){
        $SQL = "INSERT INTO
                        ug_marcas(marca, user_insert)
                    VALUES (:marca, :user_insert)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":marca", $marca);
        $stmt->bindParam(":user_insert", $user_insert);
        try{
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        } catch(PDOException $e) {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }
    /*
    * Actualiza la información del Marca seleccionada.
    */
    function send_actualizar_marca($cod_marca,
                                      $marca,
                                      $activo,
                                      $user_insert){
        $SQL = "UPDATE
                        ug_marcas
                    SET
                        marca       = :marca,
                        activo      = :activo,
                        user_insert = :user_insert,
                        date_insert = CURRENT_TIMESTAMP
                    WHERE
                        cod_marca = :cod_marca";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_marca",   $cod_marca);
        $stmt->bindParam(":marca",       $marca);
        $stmt->bindParam(":activo",      $activo);
        $stmt->bindParam(":user_insert", $user_insert);
        try{
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        }
        catch(PDOException $e)
        {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }
    /*
	* Inserta un nuevo Modelo a la base de datos.
	*/
    function send_insertar_modelo($modelo,$cod_marca,
                                   $user_insert){
        $SQL = "INSERT INTO
                           ug_modelos(modelo,cod_marca, user_insert)
                       VALUES (:modelo, :cod_marca, :user_insert)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":modelo",      $modelo);
        $stmt->bindParam(":cod_marca",   $cod_marca);
        $stmt->bindParam(":user_insert", $user_insert);
        try{
            $stmt->execute();
            $resultado = $this->db_conexion->lastInsertId();
        } catch(PDOException $e) {
            $resultado = 0; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }
    /*
     * Actualiza la información del Modelo seleccionado.
     */
    function send_actualizar_modelo($cod_modelo,
                                     $modelo,
                                     $cod_marca,
                                     $activo,
                                     $user_insert){
        $SQL = "UPDATE
                           ug_modelos
                       SET
                           modelo           = :modelo,
                           cod_marca        = :cod_marca,
                           activo           = :activo,
                           user_insert      = :user_insert,
                           date_insert      = CURRENT_TIMESTAMP
                       WHERE
                           cod_modelo = :cod_modelo";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_modelo",      $cod_modelo);
        $stmt->bindParam(":modelo",          $modelo);
        $stmt->bindParam(":cod_marca",       $cod_marca);
        $stmt->bindParam(":activo",          $activo);
        $stmt->bindParam(":user_insert",     $user_insert);
        try{
            $stmt->execute();
            if ($stmt->rowCount() > 0){
                $resultado = 1; //Exito en actualización
            }
        }
        catch(PDOException $e)
        {
            $resultado = 0; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }
    /*
	* Inserta una nuevo grupo social a la base de datos.
	*/
    function send_insertar_grupo_social($grupo_social,
                                 $user_insert){
        $SQL = "INSERT INTO
                        ug_grupos_sociales(grupo_social, user_insert)
                    VALUES (:grupo_social, :user_insert)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":grupo_social", $grupo_social);
        $stmt->bindParam(":user_insert", $user_insert);
        try{
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        } catch(PDOException $e) {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }
    /*
    * Actualiza la información del grupo social seleccionada.
    */
    function send_actualizar_grupo_social($cod_grupo_social,
                                   $grupo_social,
                                   $activo,
                                   $user_insert){
        $SQL = "UPDATE
                        ug_grupos_sociales
                    SET
                        grupo_social  = :grupo_social,
                        activo        = :activo,
                        user_insert   = :user_insert,
                        date_insert   = CURRENT_TIMESTAMP
                    WHERE
                        cod_grupo_social = :cod_grupo_social";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_grupo_social", $cod_grupo_social);
        $stmt->bindParam(":grupo_social",     $grupo_social);
        $stmt->bindParam(":activo",           $activo);
        $stmt->bindParam(":user_insert",      $user_insert);
        try{
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        }
        catch(PDOException $e)
        {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }
    /*
   * Inserta una nuevo enfermedad a la base de datos.
   */
    function send_insertar_enfermedad($enfermedad,
                                 $user_insert){
        $SQL = "INSERT INTO
                        ug_enfermedades(enfermedad, user_insert)
                    VALUES (:enfermedad, :user_insert)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":enfermedad", $enfermedad);
        $stmt->bindParam(":user_insert", $user_insert);
        try{
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        } catch(PDOException $e) {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }
    /*
    * Actualiza la información del Enfermedad seleccionada.
    */
    function send_actualizar_enfermedad($cod_enfermedad,
                                   $enfermedad,
                                   $activo,
                                   $user_insert){
        $SQL = "UPDATE
                        ug_enfermedades
                    SET
                        enfermedad  = :enfermedad,
                        activo      = :activo,
                        user_insert = :user_insert,
                        date_insert = CURRENT_TIMESTAMP
                    WHERE
                        cod_enfermedad = :cod_enfermedad";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_enfermedad",    $cod_enfermedad);
        $stmt->bindParam(":enfermedad",     $enfermedad);
        $stmt->bindParam(":activo",       $activo);
        $stmt->bindParam(":user_insert",  $user_insert);
        try{
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        }
        catch(PDOException $e)
        {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }
    /*
	* Inserta una nuevo tipo enfermedad a la base de datos.
	*/
    function send_insertar_tipo_enfermedad($tipo_enfermedad,
                                        $user_insert){
        $SQL = "INSERT INTO
                        ug_tipos_enfermedades(tipo_enfermedad, user_insert)
                    VALUES (:grupo_social, :user_insert)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":tipo_enfermedad", $tipo_enfermedad);
        $stmt->bindParam(":user_insert", $user_insert);
        try{
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        } catch(PDOException $e) {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }
    /*
    * Actualiza la información del tipo enfermedad seleccionada.
    */
    function send_actualizar_tipo_enfermedad($cod_tipo_enfermedad,
                                          $tipo_enfermedad,
                                          $activo,
                                          $user_insert){
        $SQL = "UPDATE
                        ug_tipos_enfermedades
                    SET
                        tipo_enfermedad  = :tipo_enfermedad,
                        activo        = :activo,
                        user_insert   = :user_insert,
                        date_insert   = CURRENT_TIMESTAMP
                    WHERE
                        cod_tipo_enfermedad = :cod_tipo_enfermedad";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_tipo_enfermedad", $cod_tipo_enfermedad);
        $stmt->bindParam(":tipo_enfermedad",     $tipo_enfermedad);
        $stmt->bindParam(":activo",           $activo);
        $stmt->bindParam(":user_insert",      $user_insert);
        try{
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        }
        catch(PDOException $e)
        {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }
    /*
   * Inserta una nuevo parentesco a la base de datos.
   */
    function send_insertar_parentesco($parentesco,
                                      $user_insert){
        $SQL = "INSERT INTO
                        ug_parentescos(parentesco, user_insert)
                    VALUES (:parentesco, :user_insert)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":parentesco", $parentesco);
        $stmt->bindParam(":user_insert", $user_insert);
        try{
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        } catch(PDOException $e) {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }
    /*
    * Actualiza la información del Parentesco seleccionada.
    */
    function send_actualizar_parentesco($cod_parentesco,
                                        $parentesco,
                                        $activo,
                                        $user_insert){
        $SQL = "UPDATE
                        ug_parentescos
                    SET
                        parentesco  = :parentesco,
                        activo      = :activo,
                        user_insert = :user_insert,
                        date_insert = CURRENT_TIMESTAMP
                    WHERE
                        cod_enfermedad = :cod_enfermedad";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_parentesco",    $cod_parentesco);
        $stmt->bindParam(":parentesco",     $parentesco);
        $stmt->bindParam(":activo",       $activo);
        $stmt->bindParam(":user_insert",  $user_insert);
        try{
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        }
        catch(PDOException $e)
        {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }
    /*
	* Listado Instituciones Económicas.
	*/
    function get_instituciones_economicas($listado){
        $SQL="CALL ug_get_instituciones_economicas(:listado)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":listado", $listado);
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
	* Listado Áreas de Apoyo.
	*/
    function get_areas_apoyo($listado){
        $SQL="CALL ug_get_areas_apoyo(:listado)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":listado", $listado);
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
	* Listado Periocidades.
	*/
    function get_periocidades($listado){
        $SQL="CALL ug_get_periocidades(:listado)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":listado", $listado);
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
	* Inserta una nuevo institucion económica a la base de datos.
	*/
    function send_insertar_institucion_economica($institucion_economica,
                                        $user_insert){
        $SQL = "INSERT INTO
                        ug_instituciones_economicas(institucion_economica, user_insert)
                    VALUES (:institucion_economica, :user_insert)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":institucion_economica", $institucion_economica);
        $stmt->bindParam(":user_insert",           $user_insert);
        try{
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        } catch(PDOException $e) {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }
    /*
    * Actualiza la información del institución económica seleccionada.
    */
    function send_actualizar_institucion_economica($cod_institucion_economica,
                                                   $institucion_economica,
                                                   $activo,
                                                   $user_insert){
        $SQL = "UPDATE
                        ug_instituciones_economicas
                    SET
                        institucion_economica  = :institucion_economica,
                        activo                 = :activo,
                        user_insert            = :user_insert,
                        date_insert            = CURRENT_TIMESTAMP
                    WHERE
                        cod_institucion_economica = :cod_institucion_economica";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_institucion_economica", $cod_institucion_economica);
        $stmt->bindParam(":institucion_economica",     $institucion_economica);
        $stmt->bindParam(":activo",                    $activo);
        $stmt->bindParam(":user_insert",               $user_insert);
        try{
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        }
        catch(PDOException $e)
        {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }
    /*
   * Inserta una nuevo periocidad a la base de datos.
   */
    function send_insertar_periocidad($periocidad,
                                      $user_insert){
        $SQL = "INSERT INTO
                        ug_periocidades(periocidad, user_insert)
                    VALUES (:periocidad, :user_insert)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":periocidad",  $periocidad);
        $stmt->bindParam(":user_insert", $user_insert);
        try{
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        } catch(PDOException $e) {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }
    /*
    * Actualiza la información del Periocidad seleccionada.
    */
    function send_actualizar_periocidad($cod_periocidad,
                                        $periocidad,
                                        $activo,
                                        $user_insert){
        $SQL = "UPDATE
                        ug_periocidades
                    SET
                        periocidad  = :periocidad,
                        activo      = :activo,
                        user_insert = :user_insert,
                        date_insert = CURRENT_TIMESTAMP
                    WHERE
                        cod_periocidad = :cod_periocidad";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_periocidad", $cod_periocidad);
        $stmt->bindParam(":periocidad",     $periocidad);
        $stmt->bindParam(":activo",         $activo);
        $stmt->bindParam(":user_insert",    $user_insert);
        try{
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        }
        catch(PDOException $e)
        {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }
    /*
	* Inserta una nuevo área de apoyo a la base de datos.
	*/
    function send_insertar_area_apoyo($area_apoyo,
                                      $user_insert){
        $SQL = "INSERT INTO
                        ug_areas_apoyo(area_apoyo, user_insert)
                    VALUES (:area_apoyo, :user_insert)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":area_apoyo",  $area_apoyo);
        $stmt->bindParam(":user_insert", $user_insert);
        try{
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        } catch(PDOException $e) {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }
    /*
    * Actualiza la información del área de apoyo seleccionada.
    */
    function send_actualizar_area_apoyo($cod_area_apoyo,
                                        $area_apoyo,
                                        $activo,
                                        $user_insert){
        $SQL = "UPDATE
                        ug_areas_apoyo
                    SET
                        area_apoyo    = :area_apoyo,
                        activo        = :activo,
                        user_insert   = :user_insert,
                        date_insert   = CURRENT_TIMESTAMP
                    WHERE
                        cod_area_apoyo = :cod_area_apoyo";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_area_apoyo", $cod_area_apoyo);
        $stmt->bindParam(":area_apoyo",     $area_apoyo);
        $stmt->bindParam(":activo",         $activo);
        $stmt->bindParam(":user_insert",    $user_insert);
        try{
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        }
        catch(PDOException $e)
        {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*
     * Obtiene la noticia activa que se visualizara en el menú principal.
     */
    function get_noticia_activa($cod_info_empresa){
        $SQL = "SELECT
                    ug_noticias.cod_noticia,
                    ug_noticias.titulo,
                    ug_noticias.contenido,
                    ug_noticias.imagen,
                    ug_noticias.fecha_inicio,
                    ug_noticias.fecha_fin,
                    ug_noticias.clase,
                    ug_noticias.fecha_insert,
                    ug_noticias.activo
                FROM
                    ug_noticias
                WHERE
                    ug_noticias.activo=1
                    AND FIND_IN_SET(ug_noticias.cod_info_empresa,
				            REPLACE(REPLACE(REPLACE(:cod_info_empresa,
				                        '\"',
				                        ''),
				                    '[',
				                    ''),
				                ']',
				                ''))
				    AND ug_noticias.cod_formulario IS NULL
                ORDER BY ug_noticias.fecha_inicio DESC";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_info_empresa", $cod_info_empresa);
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
     * Obtiene la noticia activa que se visualizara en el menú principal.
     */
    function get_noticia_activa_formularios($cod_info_empresa){
        $SQL = "SELECT
                    ug_noticias.cod_noticia,
                    ug_noticias.titulo,
                    ug_noticias.contenido,
                    ug_noticias.imagen,
                    ug_noticias.fecha_inicio,
                    ug_noticias.fecha_fin,
                    ug_noticias.clase,
                    ug_noticias.fecha_insert,
                    ug_noticias.activo
                FROM
                    ug_noticias
                    INNER JOIN 
                    ug_formularios ON (ug_formularios.cod_formulario = ug_noticias.cod_formulario)
                WHERE
                    ug_noticias.activo=1
                    AND FIND_IN_SET(ug_noticias.cod_info_empresa,
				            REPLACE(REPLACE(REPLACE(:cod_info_empresa,
				                        '\"',
				                        ''),
				                    '[',
				                    ''),
				                ']',
				                ''))
				    AND ug_formularios.activo = 1
                ORDER BY ug_noticias.fecha_inicio DESC";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_info_empresa", $cod_info_empresa);
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
     * Obtiene el listaod de tipos de noticias que pueden verse.
     */
    function get_tipo_noticias(){
        $SQL = "SELECT
                    cod_tipo_noticia,
                    tipo_noticia,
                    clase
                FROM
                    ug_tipo_noticias
                WHERE
                    activo = 1
                ORDER BY tipo_noticia ASC";
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
     * Obtiene el listado de noticias que se acoplen a la fecha enviada.
     */
    function get_noticias_por_fecha($fecha){
        $SQL = "SELECT 
				    ug_noticias.cod_noticia,
				    ug_noticias.titulo,
				    ug_noticias.contenido,
				    ug_noticias.imagen,
				    ug_noticias.fecha_inicio,
				    ug_noticias.fecha_fin,
				    ug_noticias.clase,
				    ug_noticias.fecha_insert,
				    ug_noticias.activo
				FROM
				    ug_noticias
				WHERE
				    ug_noticias.activo = 1
				        AND :fecha BETWEEN ug_noticias.fecha_inicio AND ug_noticias.fecha_fin
				        AND ug_noticias.cod_formulario IS NULL 
				UNION SELECT 
				    ug_noticias.cod_noticia,
				    ug_noticias.titulo,
				    ug_noticias.contenido,
				    ug_noticias.imagen,
				    ug_noticias.fecha_inicio,
				    ug_noticias.fecha_fin,
				    ug_noticias.clase,
				    ug_noticias.fecha_insert,
				    ug_noticias.activo
				FROM
				    ug_noticias
				        INNER JOIN
				    ug_formularios ON (ug_formularios.cod_formulario = ug_noticias.cod_formulario)
				WHERE
				    ug_noticias.activo = 1
				        AND :fecha BETWEEN ug_noticias.fecha_inicio AND ug_noticias.fecha_fin
				        AND ug_formularios.activo = 1";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":fecha",$fecha);
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
     * Obtiene la noticia activa que se visualizara en el menú principal.
     */
    function get_noticia_por_codigo($cod_noticia){
        $SQL = "SELECT
                    ug_noticias.cod_noticia,
                    ug_noticias.titulo,
                    ug_noticias.contenido,
                    ug_noticias.imagen,
                    ug_noticias.fecha_inicio,
                    ug_noticias.fecha_fin,
                    ug_noticias.clase,
                    ug_noticias.fecha_insert,
                    ug_noticias.activo
                FROM
                    ug_noticias
                WHERE
                    ug_noticias.activo=1
                AND
                    ug_noticias.cod_noticia = :cod_noticia
                ORDER BY ug_noticias.fecha_inicio DESC";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_noticia",$cod_noticia);
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
     * Obtiene la información del sistema.
     */
    function get_info_sistema(){
        $SQL = "SELECT
                    ug_info_sistema.*
                FROM ug_info_sistema
                WHERE ug_info_sistema.activo = 1";
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
} /*Final de la clase*/

?>
