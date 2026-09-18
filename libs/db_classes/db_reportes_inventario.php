<?PHP

/*
 * Clase de funciones para reporteria de inventarios.
 * @author      Dan uruqia
 * @date        2023-10-31
 */

class db_rep_inv
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
    /*Función para reporte de siembras y transplantes*/
    public function get_siembras_transplantes($fecha_inicio, $fecha_final, $estados)
    {
        $this->deshabilitarGrupoFull();
        $SQL = "SELECT 
                    bw_inventario_plantaciones.numero_orden,
                    bw_inventario_plantaciones.cod_plantacion,
                    bw_inventario_plantaciones.item,
                    bw_inventario_grupos_siembra.nombre AS sow_group,
                    bw_inventario_semilla.nombre_semilla,
                    bw_info_empresa.nombre_empresa,
                    bw_inventario_estados_plantaciones.abreviatura AS estado,
                    DATE(bw_inventario_plantaciones.fecha_de_orden) AS fecha_de_orden,
                    FORMAT(bw_inventario_movimiento_trasplante.cantidad_reducida,
                        0) AS cantidad_2,
                    FORMAT(bw_inventario_plantaciones.cantidad,
                        0) AS cantidad,
                    bw_inventario_plantaciones.overseed,
                    FORMAT(bw_inventario_plantaciones.total,
                        0) AS exp_plants,
                    FORMAT((bw_inventario_movimiento_trasplante.cantidad_reducida + bw_inventario_movimiento_trasplante.cantidad_sobrante),
                        0) AS exp_plants_2,
                        DATE(bw_inventario_plantaciones.fecha_inicial) AS expected_sow,
                        DATE(bw_inventario_plantaciones.fecha_final) AS expected_delivery,
                    bw_inventario_trasplantes.numero_ticket AS ticket_tras,
                    bw_inventario_localizaciones_trasplantes.nombre AS location,
                    DATE(bw_inventario_trasplantes.fecha_recibo) AS fecha_entrega_trasplante,
                    ROUND(bw_inventario_trasplantes.germinacion, 2) AS germ,
                    FORMAT(bw_inventario_trasplantes.cantidad_plantas,
                        0) AS cantidad_plantas,
                    FORMAT(SUM(bw_inventario_trasplantes.cantidad_plantas),
                        0) AS cantidad_plantas_recibidas_sumadas,
                    FORMAT((bw_inventario_plantaciones.total - bw_inventario_trasplantes.cantidad_plantas),
                        0) AS balance,
                        FORMAT(((bw_inventario_movimiento_trasplante.cantidad_reducida + bw_inventario_movimiento_trasplante.cantidad_sobrante) - bw_inventario_trasplantes.cantidad_plantas),
                        0) AS balance_2,
                    bw_inventario_trasplantes.completado,
                    COUNT(bw_inventario_trasplantes.numero_ticket) as cantidad_ticket,
                    GROUP_CONCAT(
						bw_inventario_trasplantes.numero_ticket
					) AS todos_los_ticket
                FROM
                    bw_inventario_plantaciones
                        INNER JOIN
                    bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_inventario_plantaciones.cod_inventario)
                        INNER JOIN
                    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_plantaciones.cod_info_empresa)
                        INNER JOIN
                    bw_inventario_estados_plantaciones ON (bw_inventario_estados_plantaciones.cod_estado = bw_inventario_plantaciones.cod_estado)
                        LEFT JOIN
                    bw_inventario_trasplantes ON (bw_inventario_trasplantes.cod_plantacion = bw_inventario_plantaciones.cod_plantacion)
                        LEFT JOIN
                    bw_inventario_localizaciones_trasplantes ON (bw_inventario_localizaciones_trasplantes.cod_localizacion = bw_inventario_trasplantes.cod_localizacion)
                        LEFT JOIN
                    bw_inventario_movimiento_trasplante ON (bw_inventario_movimiento_trasplante.cod_trasplante = bw_inventario_trasplantes.cod_trasplante)
                    INNER JOIN
                    bw_inventario_grupos_siembra ON (bw_inventario_grupos_siembra.cod_grupo_siembra = bw_inventario_semilla.cod_grupo_siembra)
                WHERE
                    DATE(bw_inventario_plantaciones.fecha_inicial) BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_final . "'
                        AND bw_inventario_plantaciones.cod_estado IN (" . $estados . ")
                GROUP BY bw_inventario_plantaciones.cod_plantacion
                ORDER BY bw_inventario_plantaciones.numero_orden, 
                bw_inventario_plantaciones.item, 
                bw_inventario_semilla.nombre_semilla, 
                bw_inventario_trasplantes.numero_ticket ASC;";

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
    public function get_cantidad_plantacion($numero_ticket, $cod_plantacion)
    {
        $this->deshabilitarGrupoFull();
        $SQL = "SELECT 
                     COUNT(distinct cod_plantacion) as cantidad_plantaciones, FORMAT(SUM(cantidad_plantas),0) as cantidad_plantas, FORMAT(total_plantas,0)
                FROM
                    bw_inventario_trasplantes
                WHERE
                    numero_ticket = :numero_ticket

                GROUP BY numero_ticket;";

        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->bindParam(":numero_ticket",  $numero_ticket);
        // $stmt->bindParam(":cod_plantacion",  $cod_plantacion);
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
    public function get_cantidad_sumada_semillas_plantacion($cod_plantacion)
    {
        $this->deshabilitarGrupoFull();
        $SQL = "SELECT 
                    FORMAT(SUM(cantidad_plantas),0) AS cantidad_plantas
                FROM
                    bw_inventario_trasplantes
                WHERE
                    cod_plantacion = :cod_plantacion
                GROUP BY cod_plantacion;";

        $stmt = $this->db_conexion->prepare($SQL);
        // $stmt->bindParam(":numero_ticket",  $numero_ticket);
        $stmt->bindParam(":cod_plantacion",  $cod_plantacion);
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

    /*Función para reporte de siembras y transplantes*/
    public function get_resumen_seeds($fecha_inicio, $fecha_final)
    {
        $SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->execute();

        $SQL = "SELECT 
                        bw_inventario_semilla.nombre_semilla AS SEED,
                        COUNT(bw_inventario_semilla.nombre_semilla) AS SOWS,
                        MIN(DATE(bw_inventario_plantaciones.fecha_final)) AS FIRST_FELIVERY,
                        MAX(DATE(bw_inventario_plantaciones.fecha_final)) AS LAST_FELIVERY,
                        IF(FORMAT((((DATEDIFF(MAX(DATE(bw_inventario_plantaciones.fecha_de_entrega)),
                                        MIN(DATE(bw_inventario_plantaciones.fecha_de_entrega)))) / 7) / COUNT(bw_inventario_semilla.nombre_semilla)),
                                0) < 2,
                            'Weekly',
                            'Bi-Weekly') AS FREQUENCY,
                            FORMAT(MIN(bw_inventario_movimiento_trasplante.cantidad_original),
                                0) AS EXPECTED_PLANTS_2,
                            FORMAT(SUM(bw_inventario_plantaciones.total),
                                0) AS EXPECTED_PLANTS,
                            FORMAT(SUM(bw_inventario_trasplantes.cantidad_plantas),
                                0) AS DELIVERED_PLANTS,
                            FORMAT(((SUM(bw_inventario_trasplantes.cantidad_plantas) / SUM(bw_inventario_plantaciones.total)) * 100),
                                2) AS GERM,
                            FORMAT(((SUM(bw_inventario_trasplantes.cantidad_plantas) / MIN(bw_inventario_movimiento_trasplante.cantidad_original)) * 100),
                                2) AS GERM_2,
                            FORMAT((SUM(bw_inventario_plantaciones.total) * MIN(bw_inventario_semilla.semillas_por_plantaciones)),
                                0) AS TOTAL_SEED_NEEDED,
                            FORMAT((SUM(bw_inventario_movimiento_trasplante.cantidad_reducida) * MIN(bw_inventario_semilla.semillas_por_plantaciones)),
                                0) AS TOTAL_SEED_NEEDED_2
                    FROM
                        bw_inventario_plantaciones
                            INNER JOIN
                        bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_inventario_plantaciones.cod_inventario)
                            INNER JOIN
                        bw_inventario_trasplantes ON (bw_inventario_trasplantes.cod_plantacion = bw_inventario_plantaciones.cod_plantacion)
                            INNER JOIN
                        bw_inventario_movimiento_trasplante ON (bw_inventario_movimiento_trasplante.cod_trasplante = bw_inventario_trasplantes.cod_trasplante)
                    WHERE
                     DATE(bw_inventario_trasplantes.fecha_entrega) BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_final . "'
                GROUP BY bw_inventario_semilla.nombre_semilla
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

    /*Función para reporte de detalle de ordenes de compra*/
    public function get_detalle_ordenes_compra($fecha_inicio, $fecha_final, $cod_tipo_semilla, $cod_greenhouse, $cod_vendor)
    {
        $SQL = "SELECT 
         bw_info_empresa.nombre_empresa AS SHIP_TO,
         bw_proveedores.nombre_empresa AS VENDOR,
         bw_ordenes_compra.num_orden_compra PO_NUMBER,
         bw_inventario_semilla.nombre_semilla SEED,
         bw_inventario_tipo_semillas.tipo_semilla AS TIPO_SEMILLA,
         FORMAT(bw_detalle_ordenes_compra.cantidad,
             0) AS CANTIDAD,
         CONCAT('$ ',
                 FORMAT(bw_detalle_ordenes_compra.precio_semilla,
                     2)) AS PRECIO_UNIT,
         CONCAT('$ ',
                 FORMAT(bw_detalle_ordenes_compra.monto_pago,
                     2)) AS TOTAL,
         DATE(bw_ordenes_compra.fecha_orden) AS FECHA_ORDEN,
         DATE(bw_ordenes_compra.fecha_recibido_pedido) AS FECHA_RECIBIDO,
         bw_ordenes_compra.observaciones AS OBS,
         bw_ordenes_compra.completada AS COMPLETE
     FROM
         bw_ordenes_compra
             INNER JOIN
         bw_detalle_ordenes_compra ON (bw_detalle_ordenes_compra.cod_orden = bw_ordenes_compra.cod_orden)
             INNER JOIN
         bw_detalle_productos_proveedores ON (bw_detalle_productos_proveedores.cod_detalle = bw_detalle_ordenes_compra.cod_detalle_producto)
             INNER JOIN
         bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_detalle_productos_proveedores.cod_inventario)
             INNER JOIN
         bw_proveedores ON (bw_proveedores.cod_info_empresa = bw_ordenes_compra.cod_info_empresa
             AND bw_proveedores.cod_proveedor = bw_ordenes_compra.cod_proveedor)
             INNER JOIN
         bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_ordenes_compra.cod_info_empresa)
             INNER JOIN
         bw_inventario_tipo_semillas ON (bw_inventario_tipo_semillas.cod_tipo_semilla = bw_detalle_ordenes_compra.cod_tipo_semilla)
     WHERE
         bw_ordenes_compra.fecha_orden BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_final . "'
             AND bw_detalle_ordenes_compra.cod_tipo_semilla IN (" . $cod_tipo_semilla . ")
             AND bw_ordenes_compra.cod_info_empresa IN (" . $cod_greenhouse . ")
             AND bw_ordenes_compra.cod_proveedor IN (" . $cod_vendor . ")
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

    /*Función para reporte de resumen de semillas por Greenhouse y Vendor*/
    public function get_consolidado_seeds($fecha_inicio, $fecha_final, $cod_tipo_semilla, $cod_greenhouse, $cod_vendor)
    {
        $SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->execute();

        $SQL = "SELECT 
                    bw_info_empresa.nombre_empresa AS SHIP_TO,
                    bw_proveedores.nombre_empresa AS VENDOR,
                    bw_inventario_tipo_semillas.tipo_semilla AS TIPO_SEMILLA,
                    FORMAT(SUM(bw_detalle_ordenes_compra.cantidad),
                        0) AS CANTIDAD,
                    CONCAT('$ ',
                            FORMAT(SUM(bw_detalle_ordenes_compra.monto_pago),
                                2)) AS TOTAL
                FROM
                    bw_ordenes_compra
                        INNER JOIN
                    bw_detalle_ordenes_compra ON (bw_detalle_ordenes_compra.cod_orden = bw_ordenes_compra.cod_orden)
                        INNER JOIN
                    bw_detalle_productos_proveedores ON (bw_detalle_productos_proveedores.cod_detalle = bw_detalle_ordenes_compra.cod_detalle_producto)
                        INNER JOIN
                    bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_detalle_productos_proveedores.cod_inventario)
                        INNER JOIN
                    bw_proveedores ON (bw_proveedores.cod_info_empresa = bw_ordenes_compra.cod_info_empresa
                        AND bw_proveedores.cod_proveedor = bw_ordenes_compra.cod_proveedor)
                        INNER JOIN
                    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_ordenes_compra.cod_info_empresa)
                        INNER JOIN
                    bw_inventario_tipo_semillas ON (bw_inventario_tipo_semillas.cod_tipo_semilla = bw_detalle_ordenes_compra.cod_tipo_semilla)
                WHERE
                bw_ordenes_compra.fecha_orden BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_final . "'
                    AND bw_detalle_ordenes_compra.cod_tipo_semilla IN (" . $cod_tipo_semilla . ")
                    AND bw_ordenes_compra.cod_info_empresa IN (" . $cod_greenhouse . ")
                    AND bw_ordenes_compra.cod_proveedor IN (" . $cod_vendor . ")
                GROUP BY bw_proveedores.cod_proveedor , bw_detalle_ordenes_compra.cod_tipo_semilla
                ORDER BY bw_ordenes_compra.fecha_orden DESC";

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

    /*Función para reporte de siembras y transplantes*/
    public function get_resumen_greenhouse($fecha_inicio, $fecha_final, $cod_info_empresa)
    {
        $SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->execute();

        $SQL = "SELECT 
                 bw_info_empresa.nombre_empresa AS GREENHOUSE,
                bw_inventario_semilla.nombre_semilla AS SEED,
                COUNT(bw_inventario_semilla.nombre_semilla) AS SOWS,
                IF(FORMAT((((DATEDIFF(MAX(DATE(bw_inventario_plantaciones.fecha_de_entrega)),
                                MIN(DATE(bw_inventario_plantaciones.fecha_de_entrega)))) / 7) / COUNT(bw_inventario_semilla.nombre_semilla)),
                        0) < 2,
                    'Weekly',
                    'Bi-Weekly') AS FREQUENCY,
                FORMAT(SUM(bw_inventario_plantaciones.total),
                    0) AS EXPECTED_PLANTS,
                FORMAT((SUM(bw_inventario_plantaciones.total) * MIN(bw_inventario_semilla.semillas_por_plantaciones)),
                    0) AS TOTAL_SEED_NEEDED,
                FORMAT(bw_inventario_semillas_por_empresas.cantidad_semilla,
                    0) AS INVENTARIO,
                FORMAT(bw_inventario_semillas_por_empresas.cantidad_semilla - ((SUM(bw_inventario_plantaciones.total) * MIN(bw_inventario_semilla.semillas_por_plantaciones))),
                    0) AS SHORTAGESURPLUS
            FROM
                bw_inventario_plantaciones
                    INNER JOIN
                bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_inventario_plantaciones.cod_inventario)
                    INNER JOIN
                bw_inventario_semillas_por_empresas ON (bw_inventario_semillas_por_empresas.cod_inventario_semilla = bw_inventario_plantaciones.cod_inventario
                    AND bw_inventario_semillas_por_empresas.cod_empresa = '" . $cod_info_empresa . "')
                    INNER JOIN
                bw_info_empresa ON (bw_info_empresa.cod_info_empresa = '" . $cod_info_empresa . "')
                WHERE
                    DATE(bw_inventario_plantaciones.fecha_inicial) BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_final . "'
                        AND bw_inventario_plantaciones.cod_info_empresa = '" . $cod_info_empresa . "'
                GROUP BY bw_inventario_semilla.nombre_semilla
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

    /*Función para obtner la ultima fecha de actualización del inventario*/
    public function get_fecha_greenhouse($cod_greenhouse)
    {
        $SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->execute();

        $SQL = "SELECT 
                    DATE(ultima_actualizacion) AS FECHA
                FROM
                    bw_inventario_semillas_por_empresas
                WHERE
                    cod_empresa = " . $cod_greenhouse . "
                GROUP BY cod_empresa;";

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

    /*Función para consolidado de semillas por ordenes de compra*/
    public function get_resumen_semillas($fecha_inicio, $fecha_final, $cod_info_empresa, $cod_proveedor, $cod_tipo_semilla)
    {
        $SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
        $stmt = $this->db_conexion->prepare($SQL);
        $stmt->execute();

        $SQL = "SELECT 
                    -- bw_ordenes_compra.num_orden_compra AS ORDER_NUM,
                    -- bw_ordenes_compra.cod_orden,
                    -- bw_ordenes_compra.cod_info_empresa,
                    bw_info_empresa.nombre_empresa AS GREENHOUSE,
                    -- bw_ordenes_compra.cod_proveedor,
                    bw_proveedores.nombre_empresa AS VENDOR,
                    -- bw_detalle_ordenes_compra.cod_detalle_producto,
                    -- bw_detalle_productos_proveedores.cod_inventario,
                    bw_inventario_semilla.nombre_semilla AS SEED,
                    FORMAT(SUM(bw_detalle_ordenes_compra.cantidad),0) AS CANTIDAD,
                    bw_detalle_ordenes_compra.cod_tipo_semilla AS COD_TIPO_SEMILLA,
                    bw_inventario_tipo_semillas.tipo_semilla AS TIPO_SEED
                    -- bw_ordenes_compra.fecha_orden AS ORDER_DATE
                    -- bw_ordenes_compra.fecha_recibido_pedido AS DELIVERED_DATE
                FROM
                    bw_ordenes_compra
                        INNER JOIN
                    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_ordenes_compra.cod_info_empresa)
                        INNER JOIN
                    bw_proveedores ON (bw_proveedores.cod_proveedor = bw_ordenes_compra.cod_proveedor)
                        INNER JOIN
                    bw_detalle_ordenes_compra ON (bw_detalle_ordenes_compra.cod_orden = bw_ordenes_compra.cod_orden)
                        INNER JOIN
                    bw_detalle_productos_proveedores ON (bw_detalle_productos_proveedores.cod_detalle = bw_detalle_ordenes_compra.cod_detalle_producto)
                        INNER JOIN
                    bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_detalle_productos_proveedores.cod_inventario)
                        INNER JOIN
                    bw_inventario_tipo_semillas ON (bw_inventario_tipo_semillas.cod_tipo_semilla = bw_detalle_ordenes_compra.cod_tipo_semilla)
                WHERE
                    bw_ordenes_compra.fecha_orden BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_final . "'
                AND bw_ordenes_compra.cod_info_empresa IN (" . $cod_info_empresa . ")
                AND bw_ordenes_compra.cod_proveedor IN (" . $cod_proveedor . ")
                AND bw_detalle_ordenes_compra.cod_tipo_semilla IN (" . $cod_tipo_semilla . ")
                GROUP BY bw_inventario_semilla.nombre_semilla, bw_detalle_ordenes_compra.cod_tipo_semilla
                ORDER BY bw_inventario_tipo_semillas.cod_tipo_semilla, bw_info_empresa.nombre_empresa, bw_proveedores.nombre_empresa;";

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

    /*Función para reporte de siembras*/
    public function get_siembras_listado($fecha_inicio, $fecha_final, $estados)
    {
        $SQL = "SELECT 
                    bw_inventario_plantaciones.numero_orden AS NUMERO_ORDEN,
                    bw_inventario_plantaciones.item AS ITEM,
                    bw_inventario_semilla.nombre_semilla AS SEED,
                    bw_info_empresa.nombre_empresa AS GREENHOUSE,
                    bw_inventario_estados_plantaciones.abreviatura AS ESTADO,
                    FORMAT(bw_inventario_plantaciones.cantidad, 0) AS CANTIDAD,
                    CONCAT(bw_inventario_plantaciones.overseed,'%') AS OVERSEED,
                    FORMAT(bw_inventario_plantaciones.total, 0) AS TOTAL,
                    DATE(bw_inventario_plantaciones.fecha_inicial) AS EXP_SOW,
                    DATE(bw_inventario_plantaciones.fecha_final) AS EXP_DELIVERY
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
                        INNER JOIN
                    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_inventario_plantaciones.cod_info_empresa)
                WHERE
                    DATE(bw_inventario_plantaciones.fecha_inicial) BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_final . "'
                        AND bw_inventario_plantaciones.cod_estado IN (" . $estados . ")
                ORDER BY bw_inventario_plantaciones.fecha_inicial DESC;;";

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
