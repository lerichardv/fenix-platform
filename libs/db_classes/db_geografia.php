<?php

/*
 * Funciones para controlar la administración de la Geografía del sistema.
 * @author      Kevin Fúnez
 * @date        2017-03-26
 */

class db_geografia{
    public $db_conexion;
    function __construct(){
        $this->db_conexion = new db_lion();
        $this->db_conexion = $this->db_conexion->dbConnect();
    }
    /*
     * Obtiene el Listado de los paises.
     */
     function get_listado_paises(){
         $SQL = "SELECT
                     cod_pais,
                     pais,
                     activo
                 FROM
                     geo_paises
                 ORDER BY cod_pais ASC";
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
      * Inserta un nuevo País a la base de datos.
      */
     function send_insertar_pais($pais,
                                    $user_insert){
         $SQL = "INSERT INTO
                     geo_paises(pais, user_insert)
                 VALUES (:pais, :user_insert)";
         $stmt = $this->db_conexion->prepare($SQL);
         $stmt->bindParam(":pais",  $pais);
         $stmt->bindParam(":user_insert",  $user_insert);
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
      * Actualiza la información del País seleccionada.
      */
     function send_actualizar_pais($cod_pais,
                                      $pais,
                                      $activo,
                                      $user_insert){
         $SQL = "UPDATE
                     geo_paises
                 SET
                     pais        = :pais,
                     activo      = :activo,
                     user_insert = :user_insert,
                     date_insert = CURRENT_TIMESTAMP
                 WHERE
                     cod_pais = :cod_pais";
         $stmt = $this->db_conexion->prepare($SQL);
         $stmt->bindParam(":cod_pais",    $cod_pais);
         $stmt->bindParam(":pais",        $estado_caso);
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
     * Obtiene los paises para los casos.
     */
     function get_paises(){
                    $stmt = $this->db_conexion->prepare("SELECT
                                                                 cod_pais, pais
                                                          FROM
                                                                 geo_paises
                                                          WHERE
                                                                 activo = 1
                                                          ORDER BY pais ASC");
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
     * Obtiene los departamentos para los casos.
     */
     function get_departamentos($cod_pais){
       $SQL="SELECT
                    cod_departamento,
                    departamento
             FROM
                    geo_departamentos
             WHERE
                    cod_pais = :cod_pais
             AND    activo  = 1
             ORDER BY departamento ASC";
             $stmt = $this->db_conexion->prepare($SQL);
             $stmt->bindParam(":cod_pais", $cod_pais);
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

   	function get_municipios($cod_pais,$cod_departamento){

   		$SQL = "SELECT
                                   cod_municipio,
                                   municipio
                           FROM
                                   geo_municipios
                           WHERE
                                   activo = 1
                           AND
                                   cod_pais = :cod_pais
                           AND
                                   cod_departamento = :cod_departamento
                           ORDER BY
                                   municipio ASC";

   	    $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":cod_pais",$cod_pais);
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

   	 * Obtiene las zonas habilitados por país, departamento, municipio y ciudad saeleccionados.

   	 *

   	 * var cod_pais         int Código del país seleccionado para obtener zonas.

   	 * var cod_departamento int Código del departamento seleccionado para obtener zonas.

   	 * var cod_municipio    int Código del municipio seleccionado para obtener zonas.

   	 * var cod_ciudad       int Código de la ciudad seleccionado para obtener zonas.

   	 */

   	function get_zonas($cod_pais, $cod_departamento, $cod_municipio, $cod_ciudad){

   		$SQL = "SELECT

   					cod_zona,

   					CONCAT(zona,'|',

            acronimo) zona

   				FROM

   					geo_zonas

   				WHERE

   					activo = 1

   				AND

   					cod_pais = :cod_pais

   				AND

   					cod_departamento = :cod_departamento

   				AND

   					cod_municipio = :cod_municipio

   				AND

   					cod_ciudad = :cod_ciudad

   				ORDER BY zona ASC";

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

   	 * Obtiene las zonas habilitados por país, departamento, municipio y ciudad saeleccionados.

   	 *

   	 * var cod_pais         int Código del país seleccionado para obtener zonas.

   	 * var cod_departamento int Código del departamento seleccionado para obtener zonas.

   	 * var cod_municipio    int Código del municipio seleccionado para obtener zonas.

   	 * var cod_ciudad       int Código de la ciudad seleccionado para obtener zonas.

   	 */

   	function get_zonas_casos(){
   		$SQL = "SELECT
            cod_zona,
   					zona
   				FROM
   					geo_zonas
   				WHERE
   					activo = 1
   				ORDER BY zona ASC";
   	  $stmt = $this->db_conexion->prepare($SQL);

   	// 	$stmt->bindParam(":cod_pais",$cod_pais);
      //
   	// 	$stmt->bindParam(":cod_departamento",$cod_departamento);
      //
   	// 	$stmt->bindParam(":cod_municipio",$cod_municipio);
      //
   	// 	$stmt->bindParam(":cod_ciudad",$cod_ciudad);

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

     * Obtiene acrónimo departamento.
     * var cod_departamento int Código del departamento seleccionado para obtener acrónimo.
     */

    function get_acronimo_departamento($cod_pais,$cod_departamento){
      $SQL = "SELECT
              acronimo
              FROM
              geo_departamentos
              WHERE
              cod_pais = :cod_pais
              AND
              cod_departamento = :cod_departamento";
      $stmt = $this->db_conexion->prepare($SQL);
      $stmt->bindParam(":cod_pais",$cod_pais);
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
} /*Final de la clase*/
?>
