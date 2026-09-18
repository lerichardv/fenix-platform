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
              usu_usuarios.pin,
              usu_usuarios.qcpin,
              IF(usu_usuarios.es_veterano = 0, 'Standard', IF(usu_usuarios.es_veterano = 1, 'Veteran', 'H2A')) as categoria,
							usu_cargos.cargo,
							usu_gerencias.gerencia,
              usu_usuarios.pay_rate
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
        $this->deshabilitarGrupoFull();

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
						usu_usuarios.pin,
						usu_usuarios.qcpin,
						usu_usuarios.cod_estado,
						usu_usuarios.es_veterano,
						usu_usuarios.cod_tipo_usuario,
						usu_usuarios.pay_rate,
						CONCAT(parent.nombre_1,' ',parent.apellido_1) as nombre_jefe,
                        GROUP_CONCAT(cod_granja) as granjas_vinculadas
					FROM usu_usuarios
					JOIN
						usu_usuarios AS parent ON(parent.cod_usuario = usu_usuarios.cod_jefe_inmediato)
					LEFT JOIN
						usu_gerencias ON(usu_gerencias.cod_gerencia = usu_usuarios.cod_gerencia)
					LEFT JOIN
						usu_cargos ON(usu_cargos.cod_cargo = usu_usuarios.cod_cargo AND usu_cargos.cod_gerencia = usu_usuarios.cod_gerencia)
					LEFT JOIN
						usu_perfiles ON(usu_perfiles.cod_perfil = usu_usuarios.cod_perfil)
                    LEFT JOIN
                        usu_usuario_farm ON (usu_usuario_farm.cod_usuario = usu_usuarios.cod_usuario)
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
        $this->habilitarGrupoFull();

        return $resultado;
    }
    /*
     * Obtiene la información general del usuario seleccionado
     */
    public function usu_get_granjas_vinculados_a_usuario($cod_usuario)
    {
        $SQL = "SELECT
                    cod_usuario_farm, cod_granja
                FROM
                    usu_usuario_farm
                WHERE
                    cod_usuario = :cod_usuario;";
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
     * Desvincula un usuario de una granja
     */
    public function usu_eliminar_vinculo_usuario_granja($cod_usuario_farm)
    {
        $SQL = "DELETE FROM
                    usu_usuario_farm
                WHERE
                    cod_usuario_farm = :cod_usuario_farm;";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_usuario_farm", $cod_usuario_farm);
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
        $cod_categoria_empleado,
        $cod_tipo_usuario,
        $payrate = null
    ) {
        $payrate = !empty($payrate) ? $payrate : null;
        $cod_gerencia = !empty($cod_gerencia) ? $cod_gerencia : null;
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
                                    cod_estado,
                                    cod_municipio,
                                    cod_info_empresa,
                                    pin,
                                    qcpin,
                                    es_veterano,
                                    cod_tipo_usuario,
                                    pay_rate
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
                                    :qcpin,
                                    :cod_categoria_empleado,
                                    :cod_tipo_usuario,
                                    :pay_rate
                                    )";
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
        $stmt->bindParam(":cod_categoria_empleado", $cod_categoria_empleado);
        $stmt->bindParam(":cod_tipo_usuario", $cod_tipo_usuario);
        $stmt->bindParam(":pay_rate", $payrate);
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
     * Inserta en la base de datos un nuevo registro de la vinculación de un usuario con una granja.
    */
    public function usu_crear_vincular_usuario_granja(
        $cod_usuario,
        $cod_granja
    ) {
        $SQL = "INSERT INTO usu_usuario_farm(
                                    cod_usuario,
                                    cod_granja
                                    )
                                VALUES (
                                    :cod_usuario,
                                    :cod_granja
                                        )";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_usuario", $cod_usuario);
        $stmt->bindParam(":cod_granja", $cod_granja);
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
    /*Función que valida la exitencia de un pin de usuario*/
    public function usu_validar_pin_unico($pin, $cod_usuario)
    {
        $SQL = "SELECT 
                    count(1) as usuario
                FROM
                    usu_usuarios
                WHERE
                    pin = :pin
                AND
                    cod_usuario not in (:cod_usuario);";
        $stmt = $this->db_conexion->prepare($SQL);
        try {
            $stmt->bindParam(":pin", $pin);
            $stmt->bindParam(":cod_usuario", $cod_usuario);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }
    /*Función que valida la exitencia de un pin de usuario*/
    public function usu_validar_qcpin_unico($qcpin, $cod_usuario)
    {
        $SQL = "SELECT 
                    count(1) as usuario
                FROM
                    usu_usuarios
                WHERE
                    qcpin = :qcpin
                AND
                    cod_usuario not in (:cod_usuario);";
        $stmt = $this->db_conexion->prepare($SQL);
        try {
            $stmt->bindParam(":qcpin", $qcpin);
            $stmt->bindParam(":cod_usuario", $cod_usuario);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }

    /*Función que valida la existencia de un email de usuario*/
    public function usu_validar_email_unico($email, $cod_usuario)
    {
        $cod_usuario = !empty($cod_usuario) ? $cod_usuario : 0;
        $SQL = "SELECT 
                    count(1) as usuario
                FROM
                    usu_usuarios
                WHERE
                    email = :email
                AND
                    cod_usuario not in (:cod_usuario);";
        $stmt = $this->db_conexion->prepare($SQL);
        try {
            $stmt->bindParam(":email", $email);
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
        $cod_info_empresa,
        $pin,
        $qcpin,
        $cod_categoria_empleado,
        $cod_tipo_usuario,
        $payrate = null,
    ) {

        $payrate = !empty($payrate) ? $payrate : null;
        $cod_gerencia = !empty($cod_gerencia) ? $cod_gerencia : null;
        $cod_cargo = !empty($cod_cargo) ? $cod_cargo : null;

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
						cod_estado = :cod_departamento,
						cod_municipio = :cod_municipio,
						cod_info_empresa = :cod_info_empresa,
            user_insert = :user_insert,
            pin = :pin,
            qcpin = :qcpin,
            es_veterano = :cod_categoria_empleado,
            cod_tipo_usuario = :cod_tipo_usuario,
            pay_rate = :pay_rate
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
        $stmt->bindParam(":pin", $pin);
        $stmt->bindParam(":qcpin", $qcpin);
        $stmt->bindParam(":cod_categoria_empleado", $cod_categoria_empleado);
        $stmt->bindParam(":cod_tipo_usuario", $cod_tipo_usuario);
        $stmt->bindParam(":pay_rate", $payrate);

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
    /*Función que obtiene el listado de los tipos de usuarios activos*/
    public function usu_listado_tipos_de_usuarios()
    {
        $SQL = "SELECT
                    cod_tipo_usuario, etiqueta_english
                FROM
                    usu_tipo_usuario
                WHERE
                    activo = 1
                ORDER BY etiqueta_english
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

    /**
     * Genera un token para la sesión y lo almacena en la base de datos
     * Se utiliza para persistir la sesión más allá del dominio actual o para saber si la sesión no ha expirado
     * @param $cod_usuario el usuario vinculado a la sesión
     * @param $duracion duración en segundos del token (default 3 días)
     * @return void
     */
    public function usu_generar_token_sesion($cod_usuario, $duracion = 172800): string
    {

        $fecha_actual = date_create();
        $intervalo = date_interval_create_from_date_string($duracion . ' seconds');
        $fecha_expiracion = date_add($fecha_actual, $intervalo);
        $fecha_final = $fecha_expiracion->format('Y-m-d H:i:s');

        $token = "";

        // Primero determinamos si existe un token que no esté vencido para el usuario actual
        $SQL = "SELECT * FROM 
                usu_tokens_sesion 
              WHERE 
                cod_usuario = :cod_usuario 
              AND 
                fecha_expiracion >= NOW()
              LIMIT 1";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindValue(':cod_usuario', $cod_usuario);
        try {
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Si tenemos token no expirado lo retornamos
            if (count($resultado) > 0) {
                $token = $resultado[0]['token_sesion'];
            } else {

                // Sino vamos a generar e insertar uno en la bd
                $token = $this->get_token_unico();

                $SQL = "INSERT INTO 
                    usu_tokens_sesion (
                      token_sesion,
                      cod_usuario,
                      fecha_expiracion
                    ) VALUES (
                    :token_sesion,
                    :cod_usuario,
                    :fecha_expiracion
                  );
          ";
                $stmt = $this->db_conexion->prepare($SQL);
                $stmt->bindParam(':token_sesion', $token);
                $stmt->bindParam(':cod_usuario', $cod_usuario);
                $stmt->bindParam(':fecha_expiracion', $fecha_final);
                try {
                    $stmt->execute();
                } catch (PDOException $e) {
                    error_log("Error al guardar el token en la bd: " . $e->getMessage());
                }
                $stmt->closeCursor();
            }
        } catch (PDOException $e) {
            error_log("Error al obtener el token en la bd: " . $e->getMessage());
        }
        return $token;
    }

    /**
     * Expira los tokens no vencidos del usuario dado.
     * Función usada en el logout.
     * @param mixed $cod_usuario El usuario al que se le va a expirar los tokens activos
     * @return void
     */
    public function usu_expirar_tokens_sesion_usuario($cod_usuario)
    {
        $SQL = "UPDATE usu_tokens_sesion
              SET 
                usu_tokens_sesion.fecha_expiracion = NOW()
              WHERE
                  usu_tokens_sesion.cod_usuario = :cod_usuario
              AND 
                usu_tokens_sesion.fecha_expiracion >= NOW();";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_usuario", $cod_usuario);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al expirar el token en la bd: " . $e->getMessage());
        }
        $stmt->closeCursor();
    }

    // Obtiene un token único de la bd, revisa la base de datos hasta estar seguro que no se repite
    public function get_token_unico()
    {
        $continuar = true;
        $token = "";
        do {
            $token = $this->generar_access_token();

            $SQL = "SELECT * FROM usu_tokens_sesion WHERE token_sesion = :token;";
            $stmt = $this->db_conexion->prepare($SQL);
            $stmt->bindParam('token', $token);
            try {
                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($resultado) == 0) {
                    $continuar = false;
                }
            } catch (PDOException $e) {
                error_log("Error al consultar si existe el token: " . $e->getMessage());
                break;
            }
        } while ($continuar);
        return $token;
    }

    /**
     * Actualiza para todos los usuarios dados el pay_rate
     * Los usuarios deben venir en un arreglo indexado con los ids. Ejemplo: [2,5,12,13,14,...]
     * 
     * @param mixed $pay_rate
     * @param array $usuarios
     * @return bool
     */
    function usu_actualizar_pay_rates_por_usuario($pay_rate, $usuarios)
    {
        $SQL = "UPDATE 
                usu_usuarios
              SET 
                pay_rate = :pay_rate
              WHERE 
                cod_usuario = :cod_usuario";
        $proceso_completado = true;
        $this->db_conexion->beginTransaction();
        foreach ($usuarios as $cod_usuario) {
            try {
                $stmt = $this->db_conexion->prepare($SQL);
                $stmt->bindParam(':pay_rate', $pay_rate);
                $stmt->bindParam(':cod_usuario', $cod_usuario);
                $stmt->execute();
            } catch (PDOException $e) {
                error_log("Error al actualizar el pay_rate del usuario: $cod_usuario mensaje: " . $e->getMessage());
                $this->db_conexion->rollBack();
                $proceso_completado = false;
                break;
            }
        }
        if ($proceso_completado) {
            $this->db_conexion->commit();
        }
        $stmt->closeCursor();
        return $proceso_completado;
    }

    function usu_actualizar_pay_rates_por_granjas($pay_rate, $granjas)
    {

        $proceso_completado = true;
        $this->db_conexion->beginTransaction();
        foreach ($granjas as $granja) {
            try {
                // Primero obtenemos los usuarios para asignarles el pay rate por granja
                $SQL = "SELECT 
                    cod_usuario
                  FROM 
                    usu_usuario_farm
                  WHERE
                    cod_granja = :cod_granja";
                $stmt = $this->db_conexion->prepare($SQL);
                $stmt->bindParam(':cod_granja', $granja);
                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Ahora actualizamos los usuarios obtenidos desde la bd
                $SQL = "UPDATE 
              usu_usuarios
            SET 
              pay_rate = :pay_rate
            WHERE 
              cod_usuario = :cod_usuario";

                foreach ($resultado as $usuario) {
                    $stmt = $this->db_conexion->prepare($SQL);
                    $stmt->bindParam(':pay_rate', $pay_rate);
                    $stmt->bindParam(':cod_usuario', $usuario['cod_usuario']);
                    $stmt->execute();
                }
            } catch (PDOException $e) {
                error_log("Error al obtener los usuarios asignados a la granja: $granja mensaje: " . $e->getMessage());
                $this->db_conexion->rollBack();
                $proceso_completado = false;
                break;
            }
        }

        if ($proceso_completado) {
            $this->db_conexion->commit();
        }
        $stmt->closeCursor();
        return $proceso_completado;
    }

    function usu_actualizar_pay_rates_por_categorias($pay_rate, $categorias)
    {

        $proceso_completado = true;
        $this->db_conexion->beginTransaction();
        foreach ($categorias as $categoria) {
            $SQL = "UPDATE 
                  usu_usuarios
                SET 
                  pay_rate = :pay_rate
                WHERE 
                  es_veterano = :categoria";
            try {
                $stmt = $this->db_conexion->prepare($SQL);
                $stmt->bindParam(':pay_rate', $pay_rate);
                $stmt->bindParam(':categoria', $categoria);
                $stmt->execute();
                error_log("Pay rate: $pay_rate, Categoria: $categoria");
            } catch (PDOException $e) {
                error_log("Error al actualizar el pay_rate de la categoria: $categoria mensaje: " . $e->getMessage());
                $this->db_conexion->rollBack();
                $proceso_completado = false;
                break;
            }
        }

        if ($proceso_completado) {
            $this->db_conexion->commit();
        }

        return $proceso_completado;
    }

    // Generar bytes aleatorios y convertirlos en una cadena hexadecimal
    function generar_access_token($longitud = 32)
    {
        return bin2hex(random_bytes($longitud));
    }

    /**
     * Esta funcion genera un correo electrónico con cadena aleatoria con el dominio lmfdata.com
     * Genera correo hasta que se verifica que en la columna usu_usuarios.email no existe el correo generado.
     * Cuando se comprueba que no existe retorna el correo generado.
     * @return string
     */
    function generar_email_aleatorio()
    {
        // Base para generar el correo
        $dominio = "@lmfdata.com";
        $longitud = 15; // Longitud de la cadena aleatoria

        do {
            // Generar una cadena aleatoria
            $cadenaAleatoria = $this->generar_cadena_aleatoria($longitud);
            $email = $cadenaAleatoria . $dominio;

            // Consultar si el correo existe en la base de datos
            $correoExiste = $this->verificar_email_en_base_datos($email);
        } while ($correoExiste); // Repetir hasta que no exista el correo

        return $email;
    }

    /**
     * Genera una cadena aleatoria de una longitud específica
     * @param int $longitud
     * @return string
     */
    function generar_cadena_aleatoria($longitud)
    {
        $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $cadena = '';
        $maxIndex = strlen($caracteres) - 1;

        for ($i = 0; $i < $longitud; $i++) {
            $indiceAleatorio = random_int(0, $maxIndex);
            $cadena .= $caracteres[$indiceAleatorio];
        }

        return $cadena;
    }

    /**
     * Verifica si un correo existe en la base de datos
     * @param string $email
     * @return bool
     */
    function verificar_email_en_base_datos($email)
    {

        $SQL = "SELECT COUNT(*) FROM usu_usuarios WHERE email = :email";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }




    /*
    * Cargar el listado de personas marcados como favoritos
    */
    public function usu_buscar_favoritos_granja_locacion($cod_farm, $cod_locations)
    {
        $SQL = "SELECT
                    usua.cod_usuario,
                    CONCAT_WS(' ',
                            usua.nombre_1,
                            usua.nombre_2,
                            usua.apellido_1,
                            usua.apellido_2) AS nombre
                FROM
                    usu_usuarios_favorito_granja_locacion AS favori
                        INNER JOIN
                    usu_usuarios AS usua ON (usua.cod_usuario = favori.cod_usuario)
                WHERE
                    favori.cod_farm IN (" . $cod_farm . ")
                        AND favori.cod_locacion IN (" . $cod_locations . ") GROUP by usua.cod_usuario ORDER BY nombre ASC;";
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

    /**
     * Actualiza para todos los usuarios dados el pay_rate
     * Los usuarios deben venir en un arreglo indexado con los ids. Ejemplo: [2,5,12,13,14,...]
     * 
     * @param mixed $pay_rate
     * @param array $usuarios
     * @return bool
     */
    function usu_actualizar_usuarios_favoritos_granjas_locaciones($usuarios,  $cods_farms, $cods_locations, $user_insert)
    {
        $SQL = "INSERT INTO usu_usuarios_favorito_granja_locacion (cod_farm, cod_locacion, cod_usuario, user_insert)
                SELECT :cod_farm, :cod_locacion, :cod_usuario, :user_insert
                WHERE NOT EXISTS (
                    SELECT 1
                    FROM usu_usuarios_favorito_granja_locacion
                    WHERE cod_farm = :cod_farm AND cod_locacion = :cod_locacion AND cod_usuario = :cod_usuario
                );";
        $proceso_completado = true;
        if (empty($usuarios) || empty($cods_farms) || empty($cods_locations)) {
            return true;
        }
        $this->db_conexion->beginTransaction();
        foreach ($usuarios as $cod_usuario) {
            foreach ($cods_farms as $cod_farm) {
                foreach ($cods_locations as $cod_location) {
                    try {
                        $stmt = $this->db_conexion->prepare($SQL);
                        $stmt->bindParam(':cod_farm', $cod_farm);
                        $stmt->bindParam(':cod_locacion', $cod_location);
                        $stmt->bindParam(':cod_usuario', $cod_usuario);
                        $stmt->bindParam(':user_insert', $user_insert);
                        $stmt->execute();
                    } catch (PDOException $e) {
                        error_log("Error al actualizar el pay_rate del usuario: $cod_usuario mensaje: " . $e->getMessage());
                        $this->db_conexion->rollBack();
                        $proceso_completado = false;
                        continue;
                    }
                }
            }
        }
        if ($proceso_completado) {
            $this->db_conexion->commit();
        }
        $stmt->closeCursor();
        return $proceso_completado;
    }
    /**
     * Actualiza para todos los usuarios dados el pay_rate
     * Los usuarios deben venir en un arreglo indexado con los ids. Ejemplo: [2,5,12,13,14,...]
     * 
     * @param mixed $pay_rate
     * @param array $usuarios
     * @return bool
     */
    function usu_remover_usuarios_favoritos_granjas_locaciones($usuarios, $cods_farms, $cods_locations)
    {
        $SQL = "DELETE FROM usu_usuarios_favorito_granja_locacion
                WHERE
                    cod_farm IN (" . $cods_farms . ")
                    AND cod_locacion IN (" . $cods_locations . ")
                    AND cod_usuario IN (" . $usuarios . ");";
        try {
            $stmt = $this->db_conexion->prepare($SQL);
            $stmt->execute();
            $resultado = 1;
        } catch (PDOException $e) {
            $resultado = $e->getMessage();
        }
        $stmt->closeCursor();
        return $resultado;
    }
}
