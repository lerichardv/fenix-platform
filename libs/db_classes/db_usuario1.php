<?PHP

/*
 * Clase de funciones generales para los usuarios.
 * @author      Linda Zelaya, Kevin Fúnez
 * @date        2017-03-02    2017-05-04
 */

class db_usuario
{
    public $db_conexion;
    public function __construct()
    {
        $this->db_conexion = new db_lion();
        $this->db_conexion = $this->db_conexion->dbConnect();
    }

    /*
     * Verifica que el usuario y contraseña existan en la base de datos.
     */
    public function get_login($usuario, $pass)
    {
        if (!empty($usuario) && !empty($pass)) {
            $stmt = $this->db_conexion->prepare("SELECT
                                                    cod_usuario,
                                                    usuario,
                                                    CONCAT(nombre_1, ' ', apellido_1) AS nombre,
                                                    cod_perfil
                                                FROM
                                                    usu_usuarios
                                                WHERE
                                                    activo = 1
                                                AND
                                                    usuario = :usuario
                                                AND pass = md5(:pass)");
            $stmt->bindParam(":usuario", $usuario);
            $stmt->bindParam(":pass", $pass);
            $stmt->execute();

            //Verifica que el usuario y contraseña existan en la base de
            //datos tomando como parametro que la consulta regrese mas de una fila
            if ($stmt->rowCount() == 1) {
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $stmt->closeCursor();
                return $resultado;
            } else {
                return "Incorrect username or password, please verify.";
            }
        } else {
            return "Please enter the information correctly.";
        }
    }

    /*Funcion que obtiene el login revisdando si el usuario utilizo un password temporal*/
    public function get_login_passPending($usuario, $pass)
    {
        if (!empty($usuario) && !empty($pass)) {
            $stmt = $this->db_conexion->prepare("SELECT
												  cod_usuario,
													usuario,
													CONCAT(nombre_1, ' ', apellido_1) AS nombre,
                                                    pass_pending,
													cod_perfil,
                                                    usu_usuarios.cod_cargo,
                                                    cod_pais,
                                                    cod_departamento,
                                                    cod_municipio,
													cod_info_empresa,
                                                    usu_usuarios.cod_gerencia,
                                                    usu_cargos.descripcion
												FROM
													usu_usuarios
                                                    INNER JOIN usu_cargos ON (usu_usuarios.cod_cargo = usu_cargos.cod_cargo
                                                    AND usu_cargos.cod_gerencia = usu_usuarios.cod_gerencia)
												WHERE
													usu_usuarios.activo = 1
												AND
													usuario = :usuario
												AND pass = md5(:pass)");
            $stmt->bindParam(":usuario", $usuario);
            $stmt->bindParam(":pass", $pass);
            $stmt->execute();

            //Verifica que el usuario y contraseña existan en la base de
            //datos tomando como parametro que la consulta regrese mas de una fila
            if ($stmt->rowCount() == 1) {
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $stmt->closeCursor();
                return $resultado;
            } else {
                return "Incorrect username or password, please verify.";
            }
        } else {
            return "Please enter the information correctly.";
        }
    }

    public function get_info_usuario_by_cod_usuario($cod_usuario)
    {
        $SQL = "SELECT
									usu_usuarios.cod_usuario,
									usu_usuarios.usuario,
									usu_usuarios.pass,
									CONCAT(usu_usuarios.nombre_1,' ',usu_usuarios.apellido_1) as nombre,
									usu_usuarios.email,
									usu_usuarios.flag_traducir
								FROM usu_usuarios
								WHERE
									usu_usuarios.cod_usuario = :cod_usuario;";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_usuario", $cod_usuario);
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
     * Funciones para buscar el usuario cuando olvido su contraseña por el usuario o correo electronico
     */
    public function get_info_usuario_by_usuario($usuario)
    {
        $SQL = "SELECT
									usu_usuarios.cod_usuario,
            			usu_usuarios.usuario,
									usu_usuarios.pass,
									usu_usuarios.pass_pending,
            			CONCAT(usu_usuarios.nombre_1,' ',usu_usuarios.apellido_1) as nombre,
            			usu_usuarios.email
            		FROM usu_usuarios
            		WHERE
            			usu_usuarios.usuario = :usuario or usu_usuarios.email = :usuario";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":usuario", $usuario);
        try {
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*Función que obtiene el listado de los usuarios activos actualmente en la base de datos*/
    public function get_listado_usuarios()
    {
        $SQL = "SELECT
							usu_usuarios.cod_usuario,
							CONCAT_WS(' ',usu_usuarios.nombre_1,
							usu_usuarios.nombre_2,
							usu_usuarios.apellido_1,
							usu_usuarios.apellido_2) AS nombre,
							usu_usuarios.email,
							usu_usuarios.fotografia,
							usu_usuarios.pass_pending,
							usu_usuarios.activo,
							usu_usuarios.telefono_2,
							usu_cargos.cargo,
							usu_gerencias.gerencia
			FROM
							usu_usuarios
			LEFT JOIN usu_gerencias  ON usu_usuarios.cod_gerencia = usu_gerencias.cod_gerencia
			LEFT JOIN usu_cargos ON usu_cargos.cod_cargo = usu_usuarios.cod_cargo AND usu_cargos.cod_gerencia = usu_usuarios.cod_gerencia
			ORDER BY nombre ASC; ";
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

    /*Función que obtiene el total de usuarios activos y no activos de la base de datos*/
    public function get_totales_usuarios()
    {
        $SQL = "SELECT
									(SELECT
													COUNT(usu_usuarios.cod_usuario)
											FROM
													usu_usuarios
											WHERE
													activo = 1) AS usuarios_activos,
									(SELECT
													COUNT(usu_usuarios.cod_usuario)
											FROM
													usu_usuarios
											WHERE
													activo = 0) AS usuarios_no_activos; ";

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

    /*Función que obtiene el listado de los cargos activos actualmente en la base de datos*/
    public function usu_listado_cargos($cod_gerencia)
    {
        $SQL = "SELECT
                    cod_cargo,
                    cargo
                FROM
                    usu_cargos
                WHERE
                    activo =1
								AND cod_gerencia = :cod_gerencia
                ORDER BY cargo ASC; ";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_gerencia", $cod_gerencia);
        try {
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*Función que obtiene el listado de los cargos en la base de datos*/
    public function get_listado_cargos($cod_gerencia)
    {
        $SQL = "SELECT
											cod_cargo,
											cargo,
											descripcion,
											activo
									FROM
											usu_cargos
									WHERE
								 			cod_gerencia = :cod_gerencia
									ORDER BY cargo ASC; ";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_gerencia", $cod_gerencia);
        try {
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*Función que obtiene el listado de los gerencias activos actualmente en la base de datos*/
    public function usu_listado_gerencias($flag_biblioteca)
    {
        $SQL = "SELECT
                    cod_gerencia,
                    gerencia
                FROM
                    usu_gerencias
                WHERE
                    activo =1 ";
        if ($flag_biblioteca != 1) {
            $SQL .= "AND
										flag_biblioteca = 0 ";
        }
        $SQL .= "ORDER BY gerencia ASC; ";
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

    /*Función que obtiene el listado de los gerencias  actualmente en la base de datos*/
    public function get_listado_gerencias()
    {
        $SQL = "SELECT
										cod_gerencia,
										gerencia,
										descripcion,
										activo
								FROM
										usu_gerencias
								ORDER BY gerencia ASC; ";
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

    /*Función que ingresa al historial de ingresos*/
    public function insert_historial_ingresos($cod_usuario, $ip)
    {
        $SQL = "INSERT INTO usu_historial_ingresos
						(cod_usuario,
						ip_ingreso)
						VALUES
						(:cod_usuario,
						:ip_ingreso);";

        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_usuario", $cod_usuario);
        $stmt->bindParam(":ip_ingreso", $ip);
        try {
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*Función que ingresa en intentos de login*/
    public function insert_login_intento($usuario, $ip)
    {
        $SQL = "INSERT INTO usu_login
				(usuario,
				ip_address)
				VALUES
				(:usuario,
				:ip_address);";

        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->bindParam(":ip_address", $ip);
        try {
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*Función que ingresa en intentos de login*/
    public function insert_login_bloqueo($usuario, $ip)
    {
        $SQL = "INSERT INTO usu_bitacora_bloqueados
				(usuario,
				ip_address)
				VALUES
				(:usuario,
				:ip_address);";

        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->bindParam(":ip_address", $ip);
        try {
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*Función que ingresa al historial de contraseñas*/
    public function insert_historial_password($cod_usuario, $old_pass, $new_pass)
    {
        $SQL = "INSERT INTO usu_historial_password
								(cod_usuario,
								password_anterior,
								password_nuevo)
								VALUES
								(:cod_usuario,
								:password_anterior,
								md5(:password_nuevo));";

        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_usuario", $cod_usuario);
        $stmt->bindParam(":password_anterior", $old_pass);
        $stmt->bindParam(":password_nuevo", $new_pass);
        try {
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*Función que verifica si el usuario ha utilizado la misma contraseña en los ultimos tres meses*/
    public function get_historial_password($cod_usuario, $nuevo_pass)
    {
        $SQL = "SELECT
                    password_anterior,
										password_nuevo
                FROM
                    usu_historial_password
                WHERE
                    date_cambio >= last_day(now()) + interval 1 day - interval 3 month
								AND cod_usuario = :cod_usuario
								AND ( password_anterior = md5(:nuevo_pass) OR  password_nuevo = md5(:nuevo_pass)); ";
        //Trae los passwords de los ultimos tres meses nada mas
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_usuario", $cod_usuario);
        $stmt->bindParam(":nuevo_pass", $nuevo_pass);
        try {
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*Función que verifica si el usuario ha utilizado la misma contraseña en los ultimos tres meses*/
    public function get_expired_passwords()
    {
        $SQL = "UPDATE usu_usuarios set pass_pending = '2'
								WHERE cod_usuario = (
								SELECT t3.cod_usuario FROM (
								SELECT t1.*
								FROM usu_historial_password t1
								INNER JOIN (
								    SELECT cod_usuario, MAX(date_cambio) as MaxDate
								    FROM usu_historial_password
								    GROUP BY cod_usuario
								) t2 ON t1.cod_usuario = t2.cod_usuario AND t1.date_cambio = t2.MaxDate) t3
								WHERE t3.date_cambio <= last_day(now()) + interval 1 day - interval 3 month)";
        //Trae los passwords de los ultimos tres meses nada mas
        $stmt = $this->db_conexion->prepare($SQL);
        try {
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $resultado = "0";
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*Función para reporte de ingresos al sistema*/
    public function get_ingresos_tendencia($fecha_inicio, $fecha_final)
    {
        $SQL  = "SET lc_time_names = 'es_MX'";
        $stmt = $this->db_conexion->prepare($SQL);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
        }
        $SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->execute();
        $SQL = "SELECT
								  monthname(date_ingreso) as mes,
									COUNT(1) AS ingresos,
									ROUND(((COUNT(1) / (SELECT
													COUNT(1)
									             FROM
												 		 usu_historial_ingresos
												 WHERE
													date_ingreso BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_final . "')) * 100),2) AS Porcentaje
								FROM
								    usu_historial_ingresos
								WHERE
								    usu_historial_ingresos.date_ingreso BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_final . "'
								GROUP BY MONTH(usu_historial_ingresos.date_ingreso);";
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

    /*Función para reporte de cambios de password*/
    public function get_cambios_tendencia($fecha_inicio, $fecha_final)
    {
        $SQL  = "SET lc_time_names = 'es_MX'";
        $stmt = $this->db_conexion->prepare($SQL);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
        }
        $SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->execute();
        $SQL = "SELECT
							    monthname(date_cambio) as mes,
								COUNT(1) AS cambios,
								ROUND(((COUNT(1) / (SELECT
												COUNT(1)
								             FROM
												usu_historial_password
											 WHERE
												date_cambio BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_final . "')) * 100),2) AS Porcentaje
							FROM
							    usu_historial_password
							WHERE
							    date_cambio BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_final . "'
							GROUP BY MONTH(usu_historial_password.date_cambio);";
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

    /*Función para reporte de ingresos al sistema*/
    public function get_ingresos_general($fecha_inicio, $fecha_final)
    {
        $SQL  = "SET lc_time_names = 'es_MX'";
        $stmt = $this->db_conexion->prepare($SQL);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
        }
        $SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->execute();
        $SQL = "SELECT
								    usu_historial_ingresos.cod_usuario,
								    CONCAT(usu_usuarios.nombre_1,
								            ' ',
								            usu_usuarios.apellido_1) AS nombre,
									monthname(date_ingreso) as mes,
								    COUNT(1) AS ingresos
								FROM
								    usu_historial_ingresos
								INNER JOIN
								    usu_usuarios ON usu_historial_ingresos.cod_usuario = usu_usuarios.cod_usuario
								WHERE
									usu_historial_ingresos.date_ingreso BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_final . "'
								GROUP BY MONTH(usu_historial_ingresos.date_ingreso), usu_usuarios.cod_usuario
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

    /*Función para reporte de cambios de password*/
    public function get_cambios_general($fecha_inicio, $fecha_final)
    {
        $SQL  = "SET lc_time_names = 'es_MX'";
        $stmt = $this->db_conexion->prepare($SQL);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
        }
        $SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->execute();
        $SQL = "SELECT
								    usu_historial_password.cod_usuario,
								    CONCAT(usu_usuarios.nombre_1,
								            ' ',
								            usu_usuarios.apellido_1) AS nombre,
									monthname(date_cambio) as mes,
								    COUNT(1) AS cambios
								FROM
								    usu_historial_password
								        INNER JOIN
								    usu_usuarios ON usu_historial_password.cod_usuario = usu_usuarios.cod_usuario
								WHERE
								    usu_historial_password.date_cambio BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_final . "'
								GROUP BY MONTH(usu_historial_password.date_cambio), usu_usuarios.cod_usuario
								ORDER BY nombre ASC, MONTH(usu_historial_password.date_cambio);";
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

    /*Función para reporte de ingresos al sistema*/
    public function get_ingresos_detalle($fecha_inicio, $fecha_final, $cods_usuario)
    {
        $SQL  = "SET lc_time_names = 'es_MX'";
        $stmt = $this->db_conexion->prepare($SQL);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
        }
        $SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->execute();
        $SQL = "SELECT
										usu_historial_ingresos.cod_usuario,
											monthname(date_ingreso) as mes,
										CONCAT(usu_usuarios.nombre_1,
												' ',
												usu_usuarios.apellido_1) AS nombre,
										COUNT(1) AS ingresos,
										SUBSTRING_INDEX(usu_historial_ingresos.date_ingreso,
												' ',
												1) AS fecha_ingreso,
											GROUP_CONCAT(CONCAT(TIME_FORMAT(SUBSTRING_INDEX(usu_historial_ingresos.date_ingreso,
										' ',
										- 1), '%h:%i:%s %p'),' (',ip_ingreso,')')) AS horas
								FROM
								usu_historial_ingresos
									INNER JOIN
								usu_usuarios ON usu_historial_ingresos.cod_usuario = usu_usuarios.cod_usuario
								WHERE
									usu_historial_ingresos.date_ingreso BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_final . "'
								AND usu_historial_ingresos.cod_usuario IN (" . $cods_usuario . ")
								GROUP BY SUBSTRING_INDEX(usu_historial_ingresos.date_ingreso,
									' ',
									1),ip_ingreso, cod_usuario
								ORDER BY nombre ASC; ";
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

    /*Función para reporte de ingresos al sistema*/
    public function get_cambios_detalle($fecha_inicio, $fecha_final, $cods_usuario)
    {
        $SQL  = "SET lc_time_names = 'es_MX'";
        $stmt = $this->db_conexion->prepare($SQL);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
        }
        $SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->execute();
        $SQL = "SELECT
							    usu_historial_password.cod_usuario,
							    CONCAT(usu_usuarios.nombre_1,
							            ' ',
							            usu_usuarios.apellido_1) AS nombre,
								monthname(date_cambio) as mes,
							    COUNT(1) AS cambios,
							    SUBSTRING_INDEX(usu_historial_password.date_cambio,
							            ' ',
							            1) AS fecha_cambio,
							    GROUP_CONCAT(TIME_FORMAT(SUBSTRING_INDEX(usu_historial_password.date_cambio,
							                ' ',
							                - 1), '%h:%i:%s %p')) AS horas
							FROM
							    usu_historial_password
							        INNER JOIN
							    usu_usuarios ON usu_historial_password.cod_usuario = usu_usuarios.cod_usuario
							WHERE
							    usu_historial_password.date_cambio BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_final . "'
							        AND usu_historial_password.cod_usuario IN (" . $cods_usuario . ")
							GROUP BY SUBSTRING_INDEX(usu_historial_password.date_cambio,
							        ' ',
							        1),cod_usuario
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
     * Obtiene la información general del usuario seleccionado
     */
    public function usu_get_info_usuario($cod_usuario)
    {
        $SQL = "SELECT
						CONCAT(usu_usuarios.nombre_1,' ',usu_usuarios.apellido_1) as nombre_usuario,
						CONCAT_WS(' ',usu_usuarios.nombre_1,
								usu_usuarios.nombre_2,
								usu_usuarios.apellido_1,
								usu_usuarios.apellido_2) AS nombre,
								usu_usuarios.cod_usuario,
								usu_usuarios.usuario,
								usu_usuarios.pass_pending,
						usu_usuarios.nombre_1,
						usu_usuarios.nombre_2,
						usu_usuarios.apellido_1,
						usu_usuarios.apellido_2,
						usu_usuarios.identidad,
						usu_usuarios.telefono_1,
						usu_usuarios.telefono_2,
						usu_usuarios.email,
						usu_usuarios.fotografia,
						usu_usuarios.direccion,
						usu_usuarios.date_insert,
						usu_usuarios.cod_gerencia,
						usu_gerencias.gerencia,
						usu_usuarios.cod_cargo,
						usu_cargos.cargo,
						usu_usuarios.cod_perfil,
						usu_perfiles.perfil,
						usu_usuarios.activo,
						usu_usuarios.cod_pais,
						usu_usuarios.cod_departamento,
						usu_usuarios.cod_municipio,
						usu_usuarios.cod_jefe_inmediato,
						usu_usuarios.cod_info_empresa,
						CONCAT(parent.nombre_1,' ',parent.apellido_1) as nombre_jefe
					FROM usu_usuarios
					JOIN
						usu_usuarios AS parent ON(parent.cod_usuario = usu_usuarios.cod_jefe_inmediato)
					LEFT JOIN
						usu_gerencias ON(usu_gerencias.cod_gerencia = usu_usuarios.cod_gerencia)
					LEFT JOIN
						usu_cargos ON(usu_cargos.cod_cargo = usu_usuarios.cod_cargo AND usu_cargos.cod_gerencia = usu_usuarios.cod_gerencia)
					LEFT JOIN
						usu_perfiles ON(usu_perfiles.cod_perfil = usu_usuarios.cod_perfil)
					WHERE
							usu_usuarios.cod_usuario = :cod_usuario;";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_usuario", $cod_usuario);
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
     * Obtiene la información de los usuarios
     */
    public function usu_get_info_usuarios()
    {
        $SQL = "SELECT
						CONCAT(usu_usuarios.nombre_1,' ',usu_usuarios.apellido_1) as nombre_usuario,
						CONCAT_WS(' ',usu_usuarios.nombre_1,
								usu_usuarios.nombre_2,
								usu_usuarios.apellido_1,
								usu_usuarios.apellido_2) AS nombre,
								usu_usuarios.cod_usuario,
						usu_usuarios.nombre_1,
						usu_usuarios.nombre_2,
						usu_usuarios.apellido_1,
						usu_usuarios.apellido_2,
						usu_usuarios.telefono_1,
						usu_usuarios.telefono_2,
						usu_usuarios.email,
						usu_usuarios.fotografia,
						usu_usuarios.direccion,
						usu_usuarios.date_insert,
						usu_usuarios.cod_gerencia,
						usu_gerencias.gerencia,
						usu_usuarios.cod_cargo,
						usu_cargos.cargo,
						usu_usuarios.cod_perfil,
						usu_perfiles.perfil,
						usu_usuarios.activo,
						usu_usuarios.cod_jefe_inmediato,
						CONCAT(parent.nombre_1,' ',parent.apellido_1) as nombre_jefe
					FROM usu_usuarios
					JOIN
						usu_usuarios AS parent ON(parent.cod_usuario = usu_usuarios.cod_jefe_inmediato)
					LEFT JOIN
						usu_gerencias ON(usu_gerencias.cod_gerencia = usu_usuarios.cod_gerencia)
					LEFT JOIN
						usu_cargos ON(usu_cargos.cod_cargo = usu_usuarios.cod_cargo AND usu_cargos.cod_gerencia = usu_usuarios.cod_gerencia)
					LEFT JOIN
						usu_perfiles ON(usu_perfiles.cod_perfil = usu_usuarios.cod_perfil)";
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

    /*Función que obtiene el listado de los programas activos actualmente en la base de datos*/
    public function usu_listado_programas()
    {
        $SQL = "SELECT
                    cod_programa,
                    nombre_programa as programa
                FROM
                    ug_programas
                WHERE
                    activo =1
                ORDER BY programa ASC; ";
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

    /*Función que obtiene el listado de los perfiles activos actualmente en la base de datos*/
    public function usu_listado_perfiles()
    {
        $SQL = "SELECT
                    cod_perfil,
                    perfil
                FROM
                    usu_perfiles
                WHERE
                    activo =1
                ORDER BY perfil ASC; ";
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

    /*Función que obtiene los intentos fallidos del usuario al sistema*/
    public function get_login_intentos($usuario, $ip_address)
    {
        $SQL = "SELECT
    							COUNT(*) as intento
								FROM
    							  usu_login
								WHERE
    							  usuario = :usuario
        				AND ip_address = :ip_address
								AND activo = 1
        				AND date_insert >= NOW() - INTERVAL 30 MINUTE ";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->bindParam(":ip_address", $ip_address);
        try {
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*Funcion que permite cambiar contraseña de un usuario con el usuario y el password actual*/
    public function send_cambiar_pass_by_cod_usuario($nuevo_pass, $cod_usuario)
    {
        $SQL = "UPDATE usu_usuarios
				SET
					usu_usuarios.pass = md5(:nuevo_pass),
					usu_usuarios.pass_pending = '0'
				WHERE
					usu_usuarios.cod_usuario = :cod_usuario";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":nuevo_pass", $nuevo_pass);
        $stmt->bindParam(":cod_usuario", $cod_usuario);
        try {
            $stmt->execute();
            $resultado = '1';
        } catch (PDOException $e) {
            $resultado = '0';
        }
        $stmt->closeCursor();
        //return $resultado;
        return $resultado;
    }

    /*Funcion que cambia el pass_pending del usuario para bloquear su acceso al sistema*/
    public function bloquear_usuario($usuario)
    {
        $SQL = "UPDATE usu_usuarios
				SET
					usu_usuarios.pass_pending = '3',
					usu_usuarios.date_insert = now()
				WHERE
					usu_usuarios.usuario = :usuario";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":usuario", $usuario);
        try {
            $stmt->execute();
            $resultado = '1';
        } catch (PDOException $e) {
            $resultado = '0';
        }
        $stmt->closeCursor();
        //return $resultado;
        return $resultado;
    }

    /*Funcion que desbloque al usuario y desactiva los intentos fallidos*/
    public function desbloquear_usuario($cod_usuario)
    {
        $SQL = "UPDATE usu_usuarios
				SET
					usu_usuarios.pass_pending = '0'
				WHERE
					usu_usuarios.cod_usuario = :cod_usuario";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_usuario", $cod_usuario);
        try {
            $stmt->execute();
            $resultado = '1';
        } catch (PDOException $e) {
            $resultado = '0';
        }
        $stmt->closeCursor();
        //return $resultado;
        return $resultado;
    }

    public function desbloquear_intentos_usuario($cod_usuario)
    {
        $SQL = "UPDATE usu_login
				SET
					activo = 0
				WHERE
					cod_usuario = :cod_usuario";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_usuario", $cod_usuario);
        try {
            $stmt->execute();
            $resultado = '1';
        } catch (PDOException $e) {
            $resultado = '0';
        }
        $stmt->closeCursor();
        //return $resultado;
        return $resultado;
    }

    /*Funcion que desbloque al usuario y desactiva los intentos fallidos automatico*/
    public function desbloquear_usuarios()
    {
        $SQL = "UPDATE usu_usuarios
				SET
					usu_usuarios.pass_pending = '0',
					usu_usuarios.date_insert = now()
				WHERE
					usu_usuarios.pass_pending = '3'
				AND
					usu_usuarios.date_insert <= NOW() - INTERVAL 30 MINUTE";
        $stmt = $this->db_conexion->prepare($SQL);
        try {
            $stmt->execute();
            $resultado = '1';
        } catch (PDOException $e) {
            $resultado = '0';
        }
        $stmt->closeCursor();
        //return $resultado;
        return $resultado;
    }

    public function desbloquear_intentos()
    {
        $SQL = "UPDATE usu_login
				SET
					activo = 0
				WHERE
					date_insert <= NOW() - INTERVAL 30 MINUTE";
        $stmt = $this->db_conexion->prepare($SQL);
        try {
            $stmt->execute();
            $resultado = '1';
        } catch (PDOException $e) {
            $resultado = '0';
        }
        $stmt->closeCursor();
        //return $resultado;
        return $resultado;
    }

    /*Funcion que permite cambiar contraseña de un usuario con el codigo de usuario sin validacion de contraseña*/
    public function send_cambiar_pass($cod_usuario, $nuevo_pass)
    {
        $SQL = "UPDATE usu_usuarios
				SET
					usu_usuarios.pass = md5(:nuevo_pass),
					usu_usuarios.pass_pending = '1'
				WHERE
					usu_usuarios.cod_usuario = :cod_usuario; ";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_usuario", $cod_usuario);
        $stmt->bindParam(":nuevo_pass", $nuevo_pass);
        try {
            $stmt->execute();
            $resultado = '1';
        } catch (PDOException $e) {
            $resultado = '0';
        }
        $stmt->closeCursor();
        //return $resultado;
        return $resultado;
    }

    /*Función que obtiene el listado de los usuarios activos actualmente en la base de datos*/
    public function usu_listado_usuarios()
    {
        $SQL = "SELECT
                    cod_usuario,
                    CONCAT(nombre_1,' ',apellido_1) as nombre
                FROM
                    usu_usuarios
                WHERE
                    activo =1
                ORDER BY nombre ASC; ";
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

    /*Función que obtiene el listado de los usuarios activos actualmente en la base de datos*/
    public function usu_listado_usuarios_por_gerencia($cod_gerencia)
    {
        $SQL = "SELECT
										cod_usuario,
										CONCAT(nombre_1,' ',apellido_1) as nombre
								FROM
										usu_usuarios
								WHERE
										activo =1
								AND cod_gerencia = :cod_gerencia
								ORDER BY nombre ASC; ";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_gerencia", $cod_gerencia);
        try {
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*Función que obtiene el listado de los usuarios por cargos actualmente en la base de datos
    * $cod_cargos Array Ex: [1,2,3] Arreglo de cargos
    */
    public function usu_listado_usuarios_por_cargos($cod_cargos)
    {
        $SQL = "SELECT
                                        cod_usuario,
                                        CONCAT(nombre_1,' ',apellido_1) as nombre
                                FROM
                                        usu_usuarios
                                WHERE
                                        activo =1
                                AND cod_cargo IN(" . $cod_cargos . ")
                                ORDER BY nombre ASC; ";
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
     * Inserta en la base de datos un nuevo usuario.
     */
    public function usu_crear_nuevo_usuario(
        $nombre_1,
        $nombre_2,
        $apellido_1,
        $apellido_2,
        $identidad,
        $usuario,
        $pass,
        $telefono_1,
        $telefono_2,
        $email,
        $direccion,
        $cod_gerencia,
        $cod_cargo,
        $cod_jefe_inmediato,
        $nombre_foto,
        $activo,
        $user_insert,
        $cod_pais,
        $cod_departamento,
        $cod_municipio,
        $cod_info_empresa,
        $pin,
        $qcpin,
    ) {
        $SQL = "INSERT INTO usu_usuarios(
                                    nombre_1,
                                    nombre_2,
                                    apellido_1,
                                    apellido_2,
                                    identidad,
                                    usuario,
                                    pass,
                                    telefono_1,
                                    telefono_2,
                                    email,
                                    direccion,
                                    cod_gerencia,
                                    cod_cargo,
                                    cod_jefe_inmediato,
                                    fotografia,
                                    activo,
                                    user_insert,
                                    cod_pais,
                                    cod_departamento,
                                    cod_municipio,
                                    cod_info_empresa,
                                    pin,
                                    qcpin
                                    )
                                VALUES (
                                    :nombre_1,
                                    :nombre_2,
                                    :apellido_1,
                                    :apellido_2,
                                    :identidad,
                                    :usuario,
                                    md5(:pass),
                                    :telefono_1,
                                    :telefono_2,
                                    :email,
                                    :direccion,
                                    :cod_gerencia,
                                    :cod_cargo,
                                    :cod_jefe_inmediato,
                                    :nombre_foto,
                                    :activo,
                                    :user_insert,
                                    :cod_pais,
                                    :cod_departamento,
                                    :cod_municipio,
                                    :cod_info_empresa,
                                    :pin,
                                    :qcpin)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":nombre_1", $nombre_1);
        $stmt->bindParam(":nombre_2", $nombre_2);
        $stmt->bindParam(":apellido_1", $apellido_1);
        $stmt->bindParam(":apellido_2", $apellido_2);
        $stmt->bindParam(":identidad", $identidad);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->bindParam(":pass", $pass);
        $stmt->bindParam(":telefono_1", $telefono_1);
        $stmt->bindParam(":telefono_2", $telefono_2);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":direccion", $direccion);
        $stmt->bindParam(":cod_gerencia", $cod_gerencia);
        $stmt->bindParam(":cod_cargo", $cod_cargo);
        $stmt->bindParam(":cod_jefe_inmediato", $cod_jefe_inmediato);
        $stmt->bindParam(":nombre_foto", $nombre_foto);
        $stmt->bindParam(":activo", $activo);
        $stmt->bindParam(":user_insert", $user_insert);
        $stmt->bindParam(":cod_pais", $cod_pais);
        $stmt->bindParam(":cod_departamento", $cod_departamento);
        $stmt->bindParam(":cod_municipio", $cod_municipio);
        $stmt->bindParam(":cod_info_empresa", $cod_info_empresa);

        $stmt->bindParam(":pin", $pin);
        $stmt->bindParam(":qcpin", $qcpin);
        try {
            $stmt->execute();
            $resultado = 'User successfully entered.|' . $this->db_conexion->lastInsertId();
        } catch (PDOException $e) {
            $resultado = 'An error has occurred. ' . $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*
     * Obtiene la información general de los oficiales registrados.
     */
    public function get_info_oficiales()
    {
        $SQL = "SELECT
                            dtpn_maestro_oficiales.cod_id,
                            dtpn_maestro_oficiales.nombre1,
                            IFNULL(dtpn_maestro_oficiales.nombre2,0) nombre2,
                            dtpn_maestro_oficiales.apellido1,
                            IFNULL(dtpn_maestro_oficiales.apellido2,0) apellido2,
                            dtpn_maestro_oficiales.identidad,
                            dtpn_maestro_oficiales.cod_grado,
                            dtpn_grados.grado,
                            IFNULL(dtpn_maestro_oficiales.asignacion_actual,'Sin asignación') asignacion_actual,
                            IFNULL(dtpn_maestro_oficiales.antiguedad,0) antiguedad,
                            IFNULL(dtpn_maestro_oficiales.anios_servicio,0) anios_servicio,
                            dtpn_maestro_oficiales.cod_situacion_actual,
                            dtpn_situaciones_actuales.situacion_actual,
                            IFNULL(dtpn_maestro_oficiales.cod_sexo,0) cod_sexo,
                            IFNULL(ug_sexos.sexo,'') sexo,
                            IFNULL(dtpn_maestro_oficiales.cod_estado_civil,0) cod_estado_civil,
                            IFNULL(ug_estado_civil.estado_civil,'') estado_civil,
                            IFNULL(dtpn_maestro_oficiales.observacion_general,'') observacion_general,
                            IFNULL(dtpn_maestro_oficiales.delitos_medios_comunicacion,'') delitos_medios_comunicacion,
                            IFNULL(dtpn_maestro_oficiales.rechazado_embajada,'') rechazado_embajada,
                            IFNULL(dtpn_maestro_oficiales.fecha_rechazo_embajada,'') fecha_rechazo_embajada,
                            IFNULL(dtpn_maestro_oficiales.conclusiones_acta,'') conclusiones_acta,
                            IFNULL(dtpn_maestro_oficiales.conclusiones_fecha,'') conclusiones_fecha,
                            dtpn_maestro_oficiales.fotografia,
                            IFNULL((SELECT
                                    SUM(dtpn_detalle_evaluaciones.puntaje_obtenido) / (SELECT
                                                COUNT(dtpn_organizaciones.cod_organizacion)
                                            FROM
                                                dtpn_organizaciones)
                                FROM dtpn_detalle_evaluaciones
                                WHERE dtpn_detalle_evaluaciones.cod_id = dtpn_maestro_oficiales.cod_id),'0') puntaje_total
                        FROM
                            dtpn_maestro_oficiales
                                LEFT JOIN
                            dtpn_grados ON (dtpn_grados.cod_grado = dtpn_maestro_oficiales.cod_grado)
                                LEFT JOIN
                            dtpn_situaciones_actuales ON (dtpn_situaciones_actuales.cod_situacion_actual = dtpn_maestro_oficiales.cod_situacion_actual)
                                LEFT JOIN
                            ug_sexos ON (ug_sexos.cod_sexo = dtpn_maestro_oficiales.cod_sexo)
                                LEFT JOIN
                            ug_estado_civil ON (ug_estado_civil.cod_estado_civil = dtpn_maestro_oficiales.cod_estado_civil)
                        ORDER BY cod_id ASC";
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

    /*Función que verifica si el usuario es admin*/
    public function usu_comprobar_perfil($cod_usuario)
    {
        $SQL = "SELECT
                    cod_perfil
                FROM
                    usu_usuarios
                WHERE
                    activo =1
                AND cod_usuario = :cod_usuario; ";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_usuario", $cod_usuario);
        try {
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

    public function get_listado_perfiles()
    {
        $SQL = "SELECT
										cod_perfil,
										perfil,
										descripcion,
										activo
								FROM
									usu_perfiles
								ORDER BY perfil ASC";
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
     * Obtiene el listado de los perfiles activos.
     */
    public function get_listado_perfiles_activos()
    {
        $SQL = "SELECT
										cod_perfil,
										perfil,
										descripcion,
										activo
								FROM
									usu_perfiles
								WHERE
									activo = 1
								ORDER BY perfil ASC";
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
     * Inserta un nuevo acceso en para el usuario seleccionado a la base de datos.
     */
    public function send_insertar_accesos(
        $cod_perfil,
        $cod_modulo,
        $cod_menu,
        $user_insert
    ) {
        $SQL = "INSERT INTO usu_perfil_accesos
                  (cod_perfil,
                  cod_modulo,
                  cod_menu,
                  user_insert)
                VALUES
                  (:cod_perfil,
                  :cod_modulo,
                  :cod_menu,
                  :user_insert)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_perfil", $cod_perfil);
        $stmt->bindParam(":cod_modulo", $cod_modulo);
        $stmt->bindParam(":cod_menu", $cod_menu);
        $stmt->bindParam(":user_insert", $user_insert);
        try {
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        } catch (PDOException $e) {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*
     * Inserta un nuevo acceso en para el usuario seleccionado a la base de datos.
     */
    public function send_eliminar_accesos(
        $cod_perfil,
        $cod_modulo,
        $cod_menu
    ) {
        $SQL = "DELETE FROM usu_perfil_accesos
								WHERE
									cod_perfil = :cod_perfil
								AND
									cod_modulo = :cod_modulo
								AND
									cod_menu = :cod_menu";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_perfil", $cod_perfil);
        $stmt->bindParam(":cod_modulo", $cod_modulo);
        $stmt->bindParam(":cod_menu", $cod_menu);
        try {
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        } catch (PDOException $e) {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*
     * Actualiza la información del perfil seleccionado.
     */
    public function send_actualizar_perfiles(
        $cod_perfil,
        $perfil,
        $descripcion,
        $activo,
        $user_insert
    ) {
        $SQL = "UPDATE
		                  usu_perfiles
		                SET
		                  perfil = :perfil,
		                  descripcion = :descripcion,
		                  activo = :activo,
		                  user_insert = :user_insert
		                WHERE
		                  cod_perfil = :cod_perfil";

        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_perfil", $cod_perfil);
        $stmt->bindParam(":perfil", $perfil);
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":activo", $activo);
        $stmt->bindParam(":user_insert", $user_insert);
        try {
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        } catch (PDOException $e) {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*
     * Actualiza la información de la gerencia seleccionado.
     */
    public function send_actualizar_gerencia(
        $cod_gerencia,
        $gerencia,
        $descripcion,
        $activo,
        $user_insert
    ) {
        $SQL = "UPDATE
										 usu_gerencias
									 SET
										 gerencia = :gerencia,
										 descripcion = :descripcion,
										 activo = :activo,
										 user_insert = :user_insert
									 WHERE
										 cod_gerencia = :cod_gerencia";

        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_gerencia", $cod_gerencia);
        $stmt->bindParam(":gerencia", $gerencia);
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":activo", $activo);
        $stmt->bindParam(":user_insert", $user_insert);
        try {
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        } catch (PDOException $e) {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*
     * Actualiza el perfil seleccionado al usuario.
     */
    public function send_actualizar_perfil_usuario(
        $cod_usuario,
        $cod_perfil
    ) {
        $SQL = "UPDATE
			 							 usu_usuarios
			 						 SET
			 							 cod_perfil = :cod_perfil
			 						 WHERE
			 							 cod_usuario = :cod_usuario";

        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_perfil", $cod_perfil);
        $stmt->bindParam(":cod_usuario", $cod_usuario);
        try {
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        } catch (PDOException $e) {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*
     * Inserta un nuevo perfil a la base de datos.
     */
    public function send_insertar_perfil(
        $perfil,
        $descripcion,
        $user_insert
    ) {
        $SQL = "INSERT INTO usu_perfiles
		                  (perfil,
		                   descripcion,
		                   user_insert)
		                VALUES
		                  (:perfil,
		                   :descripcion,
		                   :user_insert)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":perfil", $perfil);
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":user_insert", $user_insert);
        try {
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        } catch (PDOException $e) {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*
     * Inserta un nuevo perfil a la base de datos.
     */
    public function send_insertar_gerencia(
        $gerencia,
        $descripcion,
        $user_insert
    ) {
        $SQL = "INSERT INTO usu_gerencias
		                  (gerencia,
		                   descripcion,
											 activo,
		                   user_insert)
		                VALUES
		                  (:gerencia,
		                   :descripcion,
											 1,
		                   :user_insert)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":gerencia", $gerencia);
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":user_insert", $user_insert);
        try {
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        } catch (PDOException $e) {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*
     * Actualiza el cargo seleccionado a la gerencia.
     */
    public function send_insertar_cargo(
        $cod_gerencia,
        $cod_cargo,
        $cargo,
        $descripcion,
        $user_insert
    ) {
        $SQL = "INSERT INTO
										 usu_cargos
									 (
										 cod_gerencia,
										 cod_cargo,
										 cargo,
										 descripcion,
										 activo,
										 user_insert)
									VALUES(
										 :cod_gerencia,
										 :cod_cargo,
										 :cargo,
										 :descripcion,
										 1,
										 :user_insert);";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_gerencia", $cod_gerencia);
        $stmt->bindParam(":cod_cargo", $cod_cargo);
        $stmt->bindParam(":cargo", $cargo);
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":user_insert", $user_insert);
        try {
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        } catch (PDOException $e) {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*
     * Actualiza el cargo seleccionado a la gerencia.
     */
    public function send_actualizar_cargo(
        $cod_gerencia,
        $cod_cargo,
        $cargo,
        $descripcion,
        $activo,
        $user_insert
    ) {
        $SQL = "UPDATE
 										 usu_cargos
 									 SET
 										 cargo = :cargo,
 										 descripcion = :descripcion,
 										 activo = :activo,
 										 user_insert = :user_insert
 									 WHERE
 										 cod_gerencia = :cod_gerencia
									 AND
									 	 cod_cargo = :cod_cargo;";

        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cargo", $cargo);
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":activo", $activo);
        $stmt->bindParam(":user_insert", $user_insert);
        $stmt->bindParam(":cod_gerencia", $cod_gerencia);
        $stmt->bindParam(":cod_cargo", $cod_cargo);
        try {
            $stmt->execute();
            $resultado = '1'; //Exito en actualización
        } catch (PDOException $e) {
            $resultado = '0'; //Error en actualización
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*Función que obtiene el ultimo insert por un cod_gerencia*/
    public function get_last_cargo_inserted($cod_gerencia)
    {
        $SQL = "SELECT
											 max(cod_cargo) as id_cargo
									 FROM
											 usu_cargos
									 WHERE
										  cod_gerencia = :cod_gerencia;";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_gerencia", $cod_gerencia);
        try {
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*Función que obtiene el ultimo insert por un cod_gerencia*/
    public function get_last_user_inserted()
    {
        $SQL = "SELECT
											max(cod_usuario) as id_usuario
									FROM
											usu_usuarios";
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

    /*Función que valida que el usuario no este repetido*/
    public function usu_validar_usuario($usuario)
    {
        $SQL = "SELECT
										 count(1) as usuario
								 FROM
										 usu_usuarios
								WHERE
										 	usuario = :usuario";
        $stmt = $this->db_conexion->prepare($SQL);
        try {
            $stmt->bindParam(":usuario", $usuario);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*Función que valida que la identidad no este repetido*/
    public function usu_validar_identidad($identidad)
    {
        $SQL = "SELECT
										count(1) as usuario
								FROM
										usu_usuarios
							 WHERE
										 identidad = :identidad";
        $stmt = $this->db_conexion->prepare($SQL);
        try {
            $stmt->bindParam(":identidad", $identidad);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*Función que valida que la identidad no este repetido cuando actualiza al usuario*/
    public function usu_validar_identidad_actualizar($identidad, $cod_usuario)
    {
        $SQL = "SELECT
									 count(1) as usuario
							 FROM
									 usu_usuarios
							WHERE
									 identidad = :identidad
							AND
									cod_usuario not in (:cod_usuario)";
        $stmt = $this->db_conexion->prepare($SQL);
        try {
            $stmt->bindParam(":identidad", $identidad);
            $stmt->bindParam(":cod_usuario", $cod_usuario);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*
     * Obtiene el listado de los módulos activos.
     */
    public function get_listado_modulos()
    {
        $SQL = "SELECT
		                    cod_modulo,
                            nombre,
		                    nombre_english,
		                    descripcion,
                            activo
		                FROM
		                    ug_modulos
		                WHERE
		                	activo = 1
		                ORDER BY cod_modulo";
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
     * Obtiene el listado de los menus activos que el perfil del usuario seleccionado tiene igual que los que no tiene por módulo activo.
     */
    public function get_listado_menus_modulo_perfil($cod_usuario, $cod_modulo)
    {
        $SQL = "SELECT
		                	  usu_usuarios.cod_perfil,
		                    ug_menus.cod_modulo,
		                    ug_menus.cod_menu,
                            ug_menus.menu,
		                    ug_menus.menu_english,
		                    ug_menus.descripcion,
		                    IF((SELECT
		                                1
		                            FROM
		                                usu_perfil_accesos
		                            WHERE
		                                usu_perfil_accesos.cod_perfil = usu_usuarios.cod_perfil
		                                    AND usu_perfil_accesos.cod_modulo = ug_menus.cod_modulo
		                                    AND usu_perfil_accesos.cod_menu = ug_menus.cod_menu) = 1,
		                        1,
		                        0) AS activo
		                FROM
		                    usu_usuarios
		                        INNER JOIN
		                    ug_menus ON (ug_menus.cod_modulo = :cod_modulo)
		                WHERE
		                    usu_usuarios.cod_usuario = :cod_usuario
                            AND ug_menus.activo = 1
		                ORDER BY ug_menus.cod_modulo , ug_menus.cod_menu";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_modulo", $cod_modulo);
        $stmt->bindParam(":cod_usuario", $cod_usuario);
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
     * Obtiene el flag de si todas las opciones para este modulo estan seleccionadas
     */
    public function get_opciones_modulo_perfil($cod_modulo, $cod_usuario)
    {
        $SQL = "SELECT
										    todas_opciones
										FROM
										    usu_perfil_accesos
										WHERE
										    cod_modulo = :cod_modulo
										AND cod_perfil IN (SELECT
										            cod_perfil
										        FROM
										            usu_usuarios
										        WHERE
            								cod_usuario = :cod_usuario)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_modulo", $cod_modulo);
        $stmt->bindParam(":cod_usuario", $cod_usuario);
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
     * Actualiza el acceso del modulo para indicar que todas la opciones del modoulo estan activas para x perfil
     */
    public function actualizar_modulo_opciones($cod_modulo, $cod_usuario)
    {
        $SQL = "UPDATE
												usu_perfil_accesos
										SET
												todas_opciones = 1
										WHERE
												cod_modulo = :cod_modulo
										AND cod_perfil IN (SELECT
																cod_perfil
														FROM
																usu_usuarios
														WHERE
														cod_usuario = :cod_usuario)";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_modulo", $cod_modulo);
        $stmt->bindParam(":cod_usuario", $cod_usuario);
        try {
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*Función que obtiene el listado de los empleados activos actualmente en la base de datos*/
    public function usu_listado_empleados()
    {
        $SQL = "SELECT
                    cod_usuario,
                    CONCAT(primer_nombre,' ',primer_apellido) as nombre
                FROM
                    usu_usuarios
                WHERE
                    activo =1
                ORDER BY nombre ASC; ";
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
    /*Función que obtiene el listado de los empleados activos actualmente en la base de datos*/
    public function get_obtener_listado_empleados()
    {
        $SQL = "SELECT
										cod_empleado,
										CONCAT_WS(' ',primer_nombre,
												segundo_nombre,
												primer_apellido,
												segundo_apellido) AS nombre,
										no_identidad,
										telefono_celular,
										email,
										fotografia,
										activo
								FROM
										ug_empleados
								/*WHERE
										activo = 1*/
								ORDER BY nombre ASC; ";
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
     * Obtiene la información general de los oficiales registrados.
     */
    public function emp_get_info_empleado($cod_empleado)
    {
        $SQL = "SELECT
												CONCAT(ug_empleados.primer_nombre,' ',ug_empleados.primer_apellido) as nombre_empleado,
												CONCAT_WS(' ',primer_nombre,
														segundo_nombre,
														primer_apellido,
														segundo_apellido) AS nombre,
												ug_empleados.primer_nombre,
												ug_empleados.segundo_nombre,
												ug_empleados.primer_apellido,
												ug_empleados.segundo_apellido,
												ug_empleados.telefono_celular,
												ug_empleados.email,
												ug_empleados.cod_programa,
												ug_empleados.cod_cargo,
												ug_empleados.cod_profesion,
												ug_empleados.cod_sexo,
												ug_profesiones.nombre_profesion,
												ug_empleados.fotografia,
												ug_empleados.fecha_nacimiento,
												ug_empleados.fecha_ingreso,
												ug_empleados.no_identidad,
												ug_empleados.nro_cuenta,
												ug_empleados.salario_bruto,
												usu_cargos.cargo,
												ug_programas.nombre_programa,
												ug_empleados.colegios_profesionales,
												ug_empleados.donaciones,
												ug_empleados.ihss,
												ug_empleados.activo
										FROM ug_empleados
										LEFT JOIN
												ug_programas ON(ug_empleados.cod_programa = ug_programas.cod_programa)
										LEFT JOIN
												usu_cargos ON(ug_empleados.cod_cargo = usu_cargos.cod_cargo)
										LEFT JOIN
												ug_profesiones ON (ug_profesiones.cod_profesion = ug_empleados.cod_profesion)
										WHERE
												ug_empleados.cod_empleado = :cod_empleado;";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_empleado", $cod_empleado);
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
     * Inserta en la base de datos un nuevo usuario.
     */
    public function usu_actualizar_usuario(
        $cod_usuario,
        $nombre_1,
        $nombre_2,
        $apellido_1,
        $apellido_2,
        $identidad,
        $telefono_1,
        $telefono_2,
        $email,
        $direccion,
        $cod_gerencia,
        $cod_cargo,
        $cod_jefe_inmediato,
        $nombre_foto,
        $activo,
        $user_insert,
        $cod_pais,
        $cod_departamento,
        $cod_municipio,
        $cod_info_empresa
    ) {
        if ($nombre_foto != '') {
            $foto_query = " fotografia = :nombre_foto, ";
        } else {
            $foto_query = "";
        }

        $SQL = "UPDATE usu_usuarios
					SET nombre_1 = :nombre_1,
						nombre_2 = :nombre_2,
						apellido_1 = :apellido_1,
						apellido_2 = :apellido_2,
						identidad = :identidad,
						telefono_1 = :telefono_1,
						telefono_2 = :telefono_2,
						email = :email,
						direccion = :direccion,
						cod_gerencia = :cod_gerencia,
						cod_cargo = :cod_cargo,
						cod_jefe_inmediato = :cod_jefe_inmediato, "
            . $foto_query .
            "
			            activo = :activo,
						cod_pais = :cod_pais,
						cod_departamento = :cod_departamento,
						cod_municipio = :cod_municipio,
						cod_info_empresa = :cod_info_empresa,
			            user_insert = :user_insert
			        WHERE
			            cod_usuario = :cod_usuario";

        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":nombre_1", $nombre_1);
        $stmt->bindParam(":nombre_2", $nombre_2);
        $stmt->bindParam(":apellido_1", $apellido_1);
        $stmt->bindParam(":apellido_2", $apellido_2);
        $stmt->bindParam(":identidad", $identidad);
        $stmt->bindParam(":telefono_1", $telefono_1);
        $stmt->bindParam(":telefono_2", $telefono_2);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":direccion", $direccion);
        $stmt->bindParam(":cod_gerencia", $cod_gerencia);
        $stmt->bindParam(":cod_cargo", $cod_cargo);
        $stmt->bindParam(":cod_jefe_inmediato", $cod_jefe_inmediato);
        if ($nombre_foto != '') {
            $stmt->bindParam(":nombre_foto", $nombre_foto);
        }
        $stmt->bindParam(":activo", $activo);
        $stmt->bindParam(":cod_pais", $cod_pais);
        $stmt->bindParam(":cod_departamento", $cod_departamento);
        $stmt->bindParam(":cod_municipio", $cod_municipio);
        $stmt->bindParam(":user_insert", $user_insert);
        $stmt->bindParam(":cod_usuario", $cod_usuario);
        $stmt->bindParam(":cod_info_empresa", $cod_info_empresa);

        try {
            $stmt->execute();
            $resultado = 'Successfully updated user.';
        } catch (PDOException $e) {
            $resultado = 'An error has occurred. ' . $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*
    Función que permite ver los recibos que están pendientes de notificación*/
    public function get_listado_recibos_sin_notificar()
    {
        $SQL = "SELECT
										ug_recibos.cod_recibo,
										ug_recibos.num_recibo,
										ug_recibos.concepto_recibo,
										CONCAT(ug_empleados.primer_nombre,
														' ',
														ug_empleados.primer_apellido) AS nombre_empleado,
										ug_recibos.fecha_recibo,
										usu_cargos.cargo,
										(SELECT
														SUM(ug_detalle_ingresos.cantidad_ingreso) AS total_ingresos
												FROM
														ug_detalle_ingresos
												WHERE
														ug_detalle_ingresos.cod_recibo = ug_recibos.cod_recibo
																AND ug_detalle_ingresos.cod_empleado = ug_empleados.cod_empleado) AS total_ingresos,
										(SELECT
														SUM(ug_detalle_donantes.cantidad_donacion) AS total_donantes
												FROM
														ug_detalle_donantes
												WHERE
														ug_detalle_donantes.cod_recibo = ug_recibos.cod_recibo
																AND ug_detalle_donantes.cod_empleado = ug_empleados.cod_empleado) AS total_donantes
								FROM
										ug_recibos
												INNER JOIN
										ug_empleados ON (ug_empleados.cod_empleado = ug_recibos.cod_empleado)
												LEFT JOIN
										usu_cargos ON (usu_cargos.cod_cargo = ug_empleados.cod_cargo)
								WHERE
										cod_estado_recibo = 1
												AND (SELECT
														SUM(ug_detalle_ingresos.cantidad_ingreso) AS total_ingresos
												FROM
														ug_detalle_ingresos
												WHERE
														ug_detalle_ingresos.cod_recibo = ug_recibos.cod_recibo
																AND ug_detalle_ingresos.cod_empleado = ug_empleados.cod_empleado) = (SELECT
														SUM(ug_detalle_donantes.cantidad_donacion) AS total_donantes
												FROM
														ug_detalle_donantes
												WHERE
														ug_detalle_donantes.cod_recibo = ug_recibos.cod_recibo
																AND ug_detalle_donantes.cod_empleado = ug_empleados.cod_empleado)
								ORDER BY num_recibo ASC; ";
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
     * Obtiene la información general de un recibo.
     */
    public function emp_get_info_recibo($num_recibo)
    {
        $SQL = "SELECT
												ug_recibos.fecha_recibo,
												ug_recibos.concepto_recibo,
												ug_recibos.anio_recibo,
												ug_recibos.mes_recibo,
												ug_recibos.cod_empleado,
												ug_recibos.cod_recibo,
												CONCAT(usu_usuarios.primer_nombre,
																' ',
																usu_usuarios.primer_apellido) AS usuario_ingreso,
												CONCAT_WS(' ',ug_empleados.primer_nombre,
																ug_empleados.segundo_nombre,
																ug_empleados.primer_apellido,
																ug_empleados.segundo_apellido) AS nombre_empleado,
												ug_empleados.email AS email_empleado,
												ug_empleados.fecha_ingreso,
												ug_empleados.no_identidad,
												ug_empleados.telefono_celular,
												usu_cargos.cargo as cargo_empleado
										FROM
												ug_recibos
														INNER JOIN
												usu_usuarios ON (usu_usuarios.cod_usuario = ug_recibos.user_insert)
														INNER JOIN
												ug_empleados ON (ug_empleados.cod_empleado = ug_recibos.cod_empleado)
														LEFT JOIN
												usu_cargos ON (usu_cargos.cod_cargo = ug_empleados.cod_cargo)
										WHERE
												ug_recibos.num_recibo = :num_recibo;";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":num_recibo", $num_recibo);
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
     * Permite cambiar el estado de un recibo.
     */
    public function emp_cambiar_estado_recibo($num_recibo, $cod_estado_recibo)
    {
        $SQL = "UPDATE ug_recibos
										SET ug_recibos.cod_estado_recibo = :cod_estado_recibo
										WHERE
												ug_recibos.num_recibo = :num_recibo;";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":num_recibo", $num_recibo);
        $stmt->bindParam(":cod_estado_recibo", $cod_estado_recibo);
        try {
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*Función que obtiene el listado de los donantes activos actualmente en la base de datos*/
    public function get_obtener_listado_donantes()
    {
        $SQL = "SELECT
										cod_donante,
										nombre_donante
								FROM
										ug_donantes
								WHERE activo = 1
								ORDER BY nombre_donante ASC; ";
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

    /*Función que obtiene el listado de los donantes activos y sus fondos invertidos según fecha ingresada*/
    public function emp_get_fondos_invertidos_donantes($fecha_inicial, $cod_donantes)
    {
        $SQL = "SELECT
										ug_donantes.nombre_donante,
										SUM(cantidad_donacion) AS fondos_invertidos
								FROM
										ug_donantes
												INNER JOIN
										ug_detalle_donantes ON (ug_detalle_donantes.cod_donante = ug_donantes.cod_donante)
								WHERE
										ug_donantes.cod_donante IN (" . implode(',', $cod_donantes) . ")
												AND YEAR(ug_detalle_donantes.fecha_donacion) = YEAR(:fecha_inicial)
												AND MONTH(ug_detalle_donantes.fecha_donacion) = MONTH(:fecha_inicial)
								GROUP BY nombre_donante
								ORDER BY nombre_donante ASC; ";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":fecha_inicial", $fecha_inicial);
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
    Función que permite ver los recibos que están pendientes de notificación*/
    public function emp_get_listado_recibos_sin_notificar_ajax($fecha_inicial, $fecha_final, $cod_empleado)
    {
        $SQL = "SELECT
										ug_recibos.cod_recibo,
										ug_recibos.num_recibo,
										ug_recibos.concepto_recibo,
										CONCAT(ug_empleados.primer_nombre,
														' ',
														ug_empleados.primer_apellido) AS nombre_empleado,
										ug_recibos.fecha_recibo,
										usu_cargos.cargo,
										(SELECT
														SUM(ug_detalle_ingresos.cantidad_ingreso) AS total_ingresos
												FROM
														ug_detalle_ingresos
												WHERE
														ug_detalle_ingresos.cod_recibo = ug_recibos.cod_recibo
																AND ug_detalle_ingresos.cod_empleado = ug_empleados.cod_empleado) AS total_ingresos,
										(SELECT
														SUM(ug_detalle_donantes.cantidad_donacion) AS total_donantes
												FROM
														ug_detalle_donantes
												WHERE
														ug_detalle_donantes.cod_recibo = ug_recibos.cod_recibo
																AND ug_detalle_donantes.cod_empleado = ug_empleados.cod_empleado) AS total_donantes
								FROM
										ug_recibos
												INNER JOIN
										ug_empleados ON (ug_empleados.cod_empleado = ug_recibos.cod_empleado)
												LEFT JOIN
										usu_cargos ON (usu_cargos.cod_cargo = ug_empleados.cod_cargo)
								WHERE
										cod_estado_recibo = 1
												AND (SELECT
														SUM(ug_detalle_ingresos.cantidad_ingreso) AS total_ingresos
												FROM
														ug_detalle_ingresos
												WHERE
														ug_detalle_ingresos.cod_recibo = ug_recibos.cod_recibo
																AND ug_detalle_ingresos.cod_empleado = ug_empleados.cod_empleado) = (SELECT
														SUM(ug_detalle_donantes.cantidad_donacion) AS total_donantes
												FROM
														ug_detalle_donantes
												WHERE
														ug_detalle_donantes.cod_recibo = ug_recibos.cod_recibo
																AND ug_detalle_donantes.cod_empleado = ug_empleados.cod_empleado)
												AND ug_empleados.cod_empleado IN (" . $cod_empleado . ")
												AND ug_recibos.fecha_recibo BETWEEN :fecha_inicial AND :fecha_final
								ORDER BY num_recibo ASC; ";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":fecha_inicial", $fecha_inicial);
        $stmt->bindParam(":fecha_final", $fecha_final);
        try {
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*Función que obtiene el listado de los cargos activos actualmente en la base de datos*/
    public function usu_listado_profesiones()
    {
        $SQL = "SELECT
										cod_profesion,
										nombre_profesion
								FROM
										ug_profesiones
								WHERE
										activo =1
								ORDER BY nombre_profesion ASC; ";
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

    /*Función que obtiene el total de empleados activos y no activos de la base de datos*/
    public function get_obtener_totales_empleados()
    {
        $SQL = "SELECT
										(SELECT
														COUNT(ug_empleados.cod_empleado)
												FROM
														ug_empleados
												WHERE
														activo = 1) AS usuarios_activos,
										(SELECT
														COUNT(ug_empleados.cod_empleado)
												FROM
														ug_empleados
												WHERE
														activo = 0) AS usuarios_no_activos; ";

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
     * Funciones para buscar el usuario cuando olvido su contraseña por el usuario o correo electronico
     */
    public function get_info_usuario_by_codigo($usuario)
    {
        $SQL = "SELECT
						usu_usuarios.cod_usuario,
						usu_usuarios.usuario,
						usu_usuarios.pass,
						CONCAT_WS(' ',usu_usuarios.nombre_1,
								usu_usuarios.nombre_2,
								usu_usuarios.apellido_1,
								usu_usuarios.apellido_2) AS nombre,
						usu_usuarios.email,
						usu_usuarios.cod_cargo,
						usu_cargos.cargo,
						usu_usuarios.cod_gerencia,
						usu_gerencias.gerencia,
						usu_usuarios.cod_perfil,
						usu_perfiles.perfil,
						usu_usuarios.cod_jefe_inmediato,
						CONCAT_WS(' ',usu_usuarios_jefe.nombre_1,
								usu_usuarios_jefe.nombre_2,
								usu_usuarios_jefe.apellido_1,
								usu_usuarios_jefe.apellido_2) AS nombre_jefe,
                        usu_usuarios_jefe.email AS email_jefe
					FROM usu_usuarios
					INNER JOIN usu_perfiles  ON (usu_usuarios.cod_perfil=usu_perfiles.cod_perfil)
					INNER JOIN usu_cargos    ON (usu_usuarios.cod_gerencia=usu_cargos.cod_gerencia AND usu_usuarios.cod_cargo=usu_cargos.cod_cargo)
					INNER JOIN usu_gerencias ON (usu_usuarios.cod_gerencia=usu_gerencias.cod_gerencia)
					INNER JOIN usu_usuarios as usu_usuarios_jefe  ON (usu_usuarios.cod_jefe_inmediato=usu_usuarios_jefe.cod_usuario)
					WHERE
						usu_usuarios.cod_usuario = :usuario or usu_usuarios.email = :usuario";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":usuario", $usuario);
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
     * Obtiene el listado de los menus activos por modulo.
     */
    public function get_listado_menu_por_modulo($cod_modulo)
    {
        $SQL = "SELECT 
                    *
                FROM
                    ug_menus
                WHERE
                    cod_modulo = :cod_modulo;";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_modulo", $cod_modulo);
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
            cod_usuario, CONCAT(nombre_1, ' ', apellido_1) AS nombre
        FROM
            usu_usuarios
        WHERE
            activo = 1 AND " . $cod_info_empresa . " IN (cod_usuario.cod_info_empresa)
        ORDER BY nombre ASC
        ;";

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

    /*Función que obtiene el listado de las granjas para la ventana de registro de usuarios*/
    public function usu_listado_granjas1()
    {
        $SQL = "SELECT 
                    cod_farms, farm
                FROM
                    far_farms
                WHERE
                    activo = 1
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
}
