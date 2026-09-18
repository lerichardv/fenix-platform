<?PHP

/*

 * Clase de funciones para los reportes.

 * @author  Jairo Bonilla

 * @date    2019-01-20

 */



class db_reportes{

	public $db_conexion;



	function __construct(){

		$this->db_conexion = new db_lion();

		$this->db_conexion = $this->db_conexion->dbConnect();

	}



	/*

	 * Permite obtener listado de plantaciones dentro de un rango de fechas con sus costos.

	 */

	function rep_reporte_costos($fecha_inicial,$fecha_final,$cod_info_empresa){

        $fecha_inicial .= ' 00:00:00';

        $fecha_final .= ' 23:59:59';

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

        $SQL = "SELECT DISTINCT

				    bw_plantaciones.anio_plantacion,

				    bw_plantaciones.num_plantacion,

				    bw_temporadas.codigo_temporada,

				    bw_info_empresa.nombre_empresa,

				    GROUP_CONCAT(DISTINCT bw_zonas.zona

				        SEPARATOR '</br>') AS zonas,

				    GROUP_CONCAT(DISTINCT CONCAT(IF(bw_bloques.clave_bloque != '',

				                    CONCAT(bw_bloques.clave_bloque, '-'),

				                    ''),

				                bw_bloques.nombre_bloque)

				        SEPARATOR '</br>') AS bloques,

				    GROUP_CONCAT(DISTINCT bw_tipo_quimico.tipo_quimico

				        SEPARATOR '</br>') AS tipo_quimico,

				    GROUP_CONCAT(DISTINCT bw_inventario_quimicos.nombre_quimico

				        SEPARATOR '</br>') AS quimicos,

				    GROUP_CONCAT(DISTINCT ug_unidades_medida.unidad_medida

				        SEPARATOR '</br>') AS unidades,

				    GROUP_CONCAT(DISTINCT bw_plantaciones_detalle_aplicar_quimicos.cantidad_aplicada

				        SEPARATOR '</br>') AS cantidad_aplicada,

				    GROUP_CONCAT(DISTINCT bw_inventario_quimicos.precio_quimico

				        SEPARATOR '</br>') AS costos,

				    IFNULL((SELECT SUM(bw_inventario_quimicos.precio_quimico)),

				            0) AS total_costos,

					IFNULL(bw_inventario_semilla.flag_watercress,0) as flag_watercress,

					DATE_FORMAT(STR_TO_DATE(bw_plantaciones.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert

				FROM

				    bw_plantaciones

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_plantaciones.cod_info_empresa)

				        INNER JOIN

				    bw_temporadas ON (bw_temporadas.cod_temporada = bw_plantaciones.cod_temporada)

				        LEFT JOIN

				    bw_detalle_bloques_plantaciones ON (bw_detalle_bloques_plantaciones.cod_plantacion = bw_plantaciones.cod_plantacion)

				        LEFT JOIN

				    bw_bloques ON (bw_detalle_bloques_plantaciones.cod_bloque = bw_bloques.cod_bloque)

				        LEFT JOIN

				    bw_zonas ON (bw_bloques.cod_zona = bw_zonas.cod_zona)

				        LEFT JOIN

				    bw_plantaciones_aplicar_quimicos ON (bw_plantaciones_aplicar_quimicos.cod_plantacion = bw_plantaciones.cod_plantacion)

				        LEFT JOIN

				    bw_plantaciones_detalle_aplicar_quimicos ON (bw_plantaciones_detalle_aplicar_quimicos.cod_aplicacion = bw_plantaciones_aplicar_quimicos.cod_aplicacion

				    AND bw_plantaciones_detalle_aplicar_quimicos.cantidad_aplicada > 0)

				        LEFT JOIN

				    bw_inventario_quimicos ON (bw_inventario_quimicos.cod_inventario = bw_plantaciones_detalle_aplicar_quimicos.cod_inventario)

				        LEFT JOIN

				    bw_tipo_quimico ON (bw_tipo_quimico.cod_tipo_quimico = bw_plantaciones_detalle_aplicar_quimicos.cod_tipo_quimico)

				        LEFT JOIN

				    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_plantaciones_detalle_aplicar_quimicos.cod_unidad_medida)

						LEFT JOIN

					bw_plantaciones_semillas ON (bw_plantaciones_semillas.cod_plantacion = bw_plantaciones.cod_plantacion)

						LEFT JOIN

					bw_inventario_semilla ON(bw_inventario_semilla.cod_inventario = bw_plantaciones_semillas.cod_inventario_semilla)

				WHERE

				    bw_plantaciones.fecha_plantacion_planeada BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

				        AND bw_plantaciones.date_insert BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

						AND bw_info_empresa.cod_info_empresa IN (".$cod_info_empresa.")

				GROUP BY bw_plantaciones.cod_plantacion";

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

	 * Permite obtener listado de plantaciones dentro de un rango de fechas con sus costos.

	 */

	function rep_reporte_cosecha($fecha_inicial,$fecha_final,$cod_info_empresa){

        $fecha_inicial .= ' 00:00:00';

        $fecha_final .= ' 23:59:59';

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

        $SQL = "SELECT DISTINCT

				    '1' AS tipo_consulta,

				    bw_formulario_harvesting_worksheet.cod_formulario,

				    bw_plantaciones.cod_plantacion,

				    bw_plantaciones.anio_plantacion,

				    bw_plantaciones.num_plantacion,

				    bw_temporadas.codigo_temporada,

				    bw_info_empresa.nombre_empresa,

				    bw_zonas.zona AS zonas,

				    CONCAT(IF(bw_bloques.clave_bloque != '',

				                CONCAT(bw_bloques.clave_bloque, '-'),

				                ''),

				            MIN(bw_bloques.nombre_bloque)) AS min_bloque,

				    CONCAT(IF(bw_bloques.clave_bloque != '',

				                CONCAT(bw_bloques.clave_bloque, '-'),

				                ''),

				            MAX(bw_bloques.nombre_bloque)) AS max_bloque,

				    GROUP_CONCAT(DISTINCT CONCAT(IF(bw_bloques.clave_bloque != '',

				                    CONCAT(bw_bloques.clave_bloque, '-'),

				                    ''),

				                bw_bloques.nombre_bloque)

				        SEPARATOR '<br>') AS bloques,

				    IFNULL(bw_inventario_semilla.flag_watercress, 0) AS flag_watercress,

				    bw_inventario_semilla.nombre_semilla,

				    bw_formulario_harvesting_worksheet.phi,

				    bw_formulario_harvesting_worksheet.cellos AS loose,

				    bw_formulario_harvesting_worksheet.increment_bunch_cello,

				    bw_formulario_harvesting_worksheet.area_finished,

				    bw_formulario_harvesting_worksheet.acres_harvested,

				    bw_formulario_harvesting_worksheet.commments_harvesting_worksheet,

				    bw_formulario_harvesting_worksheet.orden_compra,

				    bw_formulario_harvesting_worksheet.cantidad_cosechada,

				    bw_formulario_harvesting_worksheet.cantidad_empacada,

				    bw_formulario_harvesting_worksheet.crop_number,

				    DATE_FORMAT(STR_TO_DATE(bw_formulario_harvesting_worksheet.date_packed,'%Y-%m-%d'),'%m-%d-%Y') as date_packed,

				    bw_formulario_harvesting_worksheet.totes_harvested,

				    bw_formulario_harvesting_worksheet.totes_packed,

				    DATE_FORMAT(STR_TO_DATE(bw_formulario_harvesting_worksheet.harvest_date,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as harvest_date,

				    DATE_FORMAT(STR_TO_DATE(bw_formulario_harvesting_worksheet.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    (SELECT

				            COUNT(bw_detalle_bloques_plantaciones.cod_detalle)

				        FROM

				            bw_detalle_bloques_plantaciones

				        WHERE

				            bw_detalle_bloques_plantaciones.cod_detalle = bloque_worksheet.cod_detalle) AS cantidad_bloques,

					GROUP_CONCAT(DISTINCT bw_inventario_semilla.nombre_semilla SEPARATOR '<br>') as semillas

				FROM

				    bw_formulario_harvesting_worksheet

				        INNER JOIN

				    bw_detalle_bloques_plantaciones bloque_worksheet ON (bw_formulario_harvesting_worksheet.cod_detalle = bloque_worksheet.cod_detalle)

				        INNER JOIN

				    bw_plantaciones ON (bloque_worksheet.cod_plantacion = bw_plantaciones.cod_plantacion)

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_plantaciones.cod_info_empresa)

				        INNER JOIN

				    bw_temporadas ON (bw_temporadas.cod_temporada = bw_plantaciones.cod_temporada)

				        LEFT JOIN

				    bw_bloques ON (bloque_worksheet.cod_bloque = bw_bloques.cod_bloque)

				        LEFT JOIN

				    bw_zonas ON (bw_bloques.cod_zona = bw_zonas.cod_zona)

				        LEFT JOIN

				    bw_plantaciones_semillas ON (bw_plantaciones_semillas.cod_plantacion = bw_plantaciones.cod_plantacion)

				        LEFT JOIN

				    bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_plantaciones_semillas.cod_inventario_semilla)

				WHERE

				    (bw_plantaciones.fecha_plantacion_planeada BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

				        OR bw_plantaciones.date_insert BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

				        OR bw_formulario_harvesting_worksheet.harvest_date BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s'))

				        AND bw_formulario_harvesting_worksheet.activo = 1

				        AND bw_info_empresa.cod_info_empresa IN (".$cod_info_empresa.")

				GROUP BY bw_formulario_harvesting_worksheet.cod_formulario

				UNION ALL SELECT DISTINCT

				    '2' AS tipo_consulta,

				    bw_formulario_harvesting_worksheet.cod_formulario,

				    bw_plantaciones.cod_plantacion,

				    bw_plantaciones.anio_plantacion,

				    bw_plantaciones.num_plantacion,

				    bw_temporadas.codigo_temporada,

				    bw_info_empresa.nombre_empresa,

				    bw_zonas.zona AS zonas,

				    CONCAT(IF(bw_bloques.clave_bloque != '',

				                CONCAT(bw_bloques.clave_bloque, '-'),

				                ''),

				            MIN(bw_bloques.nombre_bloque)) AS min_bloque,

				    CONCAT(IF(bw_bloques.clave_bloque != '',

				                CONCAT(bw_bloques.clave_bloque, '-'),

				                ''),

				            MAX(bw_bloques.nombre_bloque)) AS max_bloque,

				    GROUP_CONCAT(DISTINCT CONCAT(IF(bw_bloques.clave_bloque != '',

				                    CONCAT(bw_bloques.clave_bloque, '-'),

				                    ''),

				                bw_bloques.nombre_bloque)

				        SEPARATOR '<br>') AS bloques,

				    IFNULL(bw_inventario_semilla.flag_watercress, 0) AS flag_watercress,

				    bw_inventario_semilla.nombre_semilla,

				    bw_formulario_harvesting_worksheet.phi,

				    bw_formulario_harvesting_worksheet.cellos AS loose,

				    bw_formulario_harvesting_worksheet.increment_bunch_cello,

				    bw_formulario_harvesting_worksheet.area_finished,

				    bw_formulario_harvesting_worksheet.acres_harvested,

				    bw_formulario_harvesting_worksheet.commments_harvesting_worksheet,

				    bw_formulario_harvesting_worksheet.orden_compra,

				    bw_formulario_harvesting_worksheet.cantidad_cosechada,

				    bw_formulario_harvesting_worksheet.cantidad_empacada,

				    bw_formulario_harvesting_worksheet.crop_number,

				    DATE_FORMAT(STR_TO_DATE(bw_formulario_harvesting_worksheet.date_packed,'%Y-%m-%d'),'%m-%d-%Y') as date_packed,

				    bw_formulario_harvesting_worksheet.totes_harvested,

				    bw_formulario_harvesting_worksheet.totes_packed,

				    DATE_FORMAT(STR_TO_DATE(bw_formulario_harvesting_worksheet.harvest_date,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as harvest_date,

				    DATE_FORMAT(STR_TO_DATE(bw_formulario_harvesting_worksheet.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    (SELECT

				            COUNT(bw_detalle_bloques_plantaciones.cod_detalle)

				        FROM

				            bw_detalle_bloques_plantaciones

				        WHERE

				            bw_detalle_bloques_plantaciones.cod_plantacion = bw_plantaciones.cod_plantacion) AS cantidad_bloques,

					GROUP_CONCAT(DISTINCT bw_inventario_semilla.nombre_semilla SEPARATOR '<br>') as semillas

				FROM

				    bw_formulario_harvesting_worksheet

				        INNER JOIN

				    bw_plantaciones ON (bw_formulario_harvesting_worksheet.cod_plantacion = bw_plantaciones.cod_plantacion)

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_plantaciones.cod_info_empresa)

				        INNER JOIN

				    bw_temporadas ON (bw_temporadas.cod_temporada = bw_plantaciones.cod_temporada)

				        INNER JOIN

				    bw_detalle_bloques_plantaciones ON (bw_plantaciones.cod_plantacion = bw_detalle_bloques_plantaciones.cod_plantacion)

				        LEFT JOIN

				    bw_bloques ON (bw_detalle_bloques_plantaciones.cod_bloque = bw_bloques.cod_bloque)

				        LEFT JOIN

				    bw_zonas ON (bw_bloques.cod_zona = bw_zonas.cod_zona)

				        LEFT JOIN

				    bw_plantaciones_semillas ON (bw_plantaciones_semillas.cod_plantacion = bw_plantaciones.cod_plantacion)

				        LEFT JOIN

				    bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_plantaciones_semillas.cod_inventario_semilla)

				WHERE

				    (bw_plantaciones.fecha_plantacion_planeada BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

				        OR bw_plantaciones.date_insert BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

				        OR bw_formulario_harvesting_worksheet.harvest_date BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s'))

				        AND bw_formulario_harvesting_worksheet.activo = 1

				        AND bw_info_empresa.cod_info_empresa IN (".$cod_info_empresa.")

				GROUP BY bw_formulario_harvesting_worksheet.cod_formulario";

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

	 * Permite obtener listado de plantaciones dentro de un rango de fechas con sus resultados de exploraciones.

	 */

	function rep_reporte_exploradoras($fecha_inicial,$fecha_final,$cod_info_empresa){

        $fecha_inicial .= ' 00:00:00';

        $fecha_final .= ' 23:59:59';

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

        $SQL = "SELECT

				    cod_exploracion,

				    fecha_exploracion,

				    date_insert,

				    cod_plantacion,

				    anio_plantacion,

				    num_plantacion,

				    codigo_temporada,

				    crop_number,

				    flag_watercress,

				    nombre_empresa,

				    zonas,

				    bloques,

				    SUM(insectos) AS insectos,

				    SUM(enfermedades) AS enfermedades,

				    SUM(hierbas) AS hierbas,

				    SUM(otros_danos) AS otros_danos,

				    SUM(gusanos) AS gusanos,

				    SUM(huevos) AS huevos,

				    SUM(saltahojas) AS saltahojas,

				    SUM(afidos) AS afidos,

				    SUM(chinches) AS chinches,

				    SUM(moscos) AS moscos,

				    SUM(escarabajos) AS escarabajos,

				    SUM(acaros) AS acaros,

				    SUM(cercospora_leaf_spot) AS cercospora_leaf_spot,

				    SUM(pythium) AS pythium,

				    SUM(rhizoctonia) AS rhizoctonia,

				    SUM(bacteria) AS bacteria,

				    SUM(alternaria_specks) AS alternaria_specks,

				    SUM(sclerotinia) AS sclerotinia,

				    SUM(mildew) AS mildew,

				    SUM(virus) AS virus,

				    SUM(dolar) AS dolar,

				    SUM(frogs_bit) AS frogs_bit,

				    SUM(plantas_lodo) AS plantas_lodo,

				    SUM(tripa_pollo) AS tripa_pollo,

				    SUM(zacate) AS zacate,

				    SUM(hojas_danadas) AS hojas_danadas,

				    SUM(tallos_purpuras) AS tallos_purpuras,

				    SUM(berro_enraizado) AS berro_enraizado,

					SUM(spidermites) AS spidermites,

					SUM(white_rust) AS white_rust,

					SUM(salt_accumulation) AS salt_accumulation,

					SUM(nutsedge) AS nutsedge,

					SUM(buds) AS buds,

					SUM(zigzag_stems) AS zigzag_stems,

					SUM(nutrient_deficiency) AS nutrient_deficiency,

					SUM(round_up) AS round_up,

					SUM(light_color) AS light_color,
					SUM(mealybugs) AS mealybugs,
					SUM(thrips) AS thrips

				FROM

				    (SELECT DISTINCT

				        bw_plantaciones_formulario_exploracion.cod_exploracion,

				            DATE_FORMAT(STR_TO_DATE(bw_plantaciones_formulario_exploracion.fecha_exploracion,'%Y-%m-%d'),'%m-%d-%Y') as fecha_exploracion,

				            DATE_FORMAT(STR_TO_DATE(bw_plantaciones_formulario_exploracion.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				            bw_plantaciones.cod_plantacion,

				            bw_plantaciones.anio_plantacion,

				            bw_plantaciones.num_plantacion,

				            bw_temporadas.codigo_temporada,

				            bw_formulario_harvesting_worksheet.crop_number,

				            IFNULL(bw_inventario_semilla.flag_watercress, 0) AS flag_watercress,

				            bw_info_empresa.nombre_empresa,

				            GROUP_CONCAT(DISTINCT bw_zonas.zona

				                SEPARATOR '</br>') AS zonas,

				            GROUP_CONCAT(CONCAT(IF(bw_bloques.clave_bloque != '', CONCAT(bw_bloques.clave_bloque, '-'), ''), bw_bloques.nombre_bloque)

				                SEPARATOR '</br>') AS bloques,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.gusanos + bw_plantaciones_formulario_exploracion.huevos + bw_plantaciones_formulario_exploracion.saltahojas + bw_plantaciones_formulario_exploracion.afidos + bw_plantaciones_formulario_exploracion.chinches + bw_plantaciones_formulario_exploracion.moscos + bw_plantaciones_formulario_exploracion.escarabajos + bw_plantaciones_formulario_exploracion.acaros)), 0) AS insectos,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.cercospora_leaf_spot + bw_plantaciones_formulario_exploracion.pythium + bw_plantaciones_formulario_exploracion.rhizoctonia + bw_plantaciones_formulario_exploracion.bacteria + bw_plantaciones_formulario_exploracion.sclerotinia + bw_plantaciones_formulario_exploracion.alternaria_specks + bw_plantaciones_formulario_exploracion.mildew + bw_plantaciones_formulario_exploracion.virus)), 0) AS enfermedades,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.dolar + bw_plantaciones_formulario_exploracion.frogs_bit + bw_plantaciones_formulario_exploracion.plantas_lodo + bw_plantaciones_formulario_exploracion.tripa_pollo + bw_plantaciones_formulario_exploracion.zacate)), 0) AS hierbas,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.hojas_danadas + bw_plantaciones_formulario_exploracion.tallos_purpuras + bw_plantaciones_formulario_exploracion.berro_enraizado)), 0) AS otros_danos,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.gusanos)), 0) AS gusanos,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.huevos)), 0) AS huevos,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.saltahojas)), 0) AS saltahojas,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.afidos)), 0) AS afidos,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.chinches)), 0) AS chinches,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.moscos)), 0) AS moscos,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.escarabajos)), 0) AS escarabajos,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.acaros)), 0) AS acaros,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.cercospora_leaf_spot)), 0) AS cercospora_leaf_spot,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.pythium)), 0) AS pythium,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.rhizoctonia)), 0) AS rhizoctonia,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.bacteria)), 0) AS bacteria,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.sclerotinia)), 0) AS sclerotinia,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.alternaria_specks)), 0) AS alternaria_specks,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.mildew)), 0) AS mildew,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.virus)), 0) AS virus,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.dolar)), 0) AS dolar,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.frogs_bit)), 0) AS frogs_bit,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.plantas_lodo)), 0) AS plantas_lodo,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.tripa_pollo)), 0) AS tripa_pollo,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.zacate)), 0) AS zacate,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.hojas_danadas)), 0) AS hojas_danadas,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.tallos_purpuras)), 0) AS tallos_purpuras,

				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.spidermites)), 0) AS spidermites,
				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.white_rust)), 0) AS white_rust,
				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.salt_accumulation)), 0) AS salt_accumulation,
				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.nutsedge)), 0) AS nutsedge,
				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.buds)), 0) AS buds,
				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.zigzag_stems)), 0) AS zigzag_stems,
				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.nutrient_deficiency)), 0) AS nutrient_deficiency,
				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.round_up)), 0) AS round_up,
				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.light_color)), 0) AS light_color,
				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.mealybugs)), 0) AS mealybugs,
				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.thrips)), 0) AS thrips,
				            IFNULL((SELECT SUM(bw_plantaciones_formulario_exploracion.berro_enraizado)), 0) AS berro_enraizado

				    FROM

				        bw_plantaciones

				    INNER JOIN bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_plantaciones.cod_info_empresa)

				    INNER JOIN bw_temporadas ON (bw_temporadas.cod_temporada = bw_plantaciones.cod_temporada)

				    INNER JOIN bw_plantaciones_formulario_exploracion ON (bw_plantaciones_formulario_exploracion.cod_plantacion = bw_plantaciones.cod_plantacion)

				    INNER JOIN bw_detalle_bloques_plantaciones ON (bw_detalle_bloques_plantaciones.cod_plantacion = bw_plantaciones.cod_plantacion)

				    LEFT JOIN bw_bloques ON (bw_detalle_bloques_plantaciones.cod_bloque = bw_bloques.cod_bloque)

				    LEFT JOIN bw_zonas ON (bw_bloques.cod_zona = bw_zonas.cod_zona)

				    LEFT JOIN bw_plantaciones_semillas ON (bw_plantaciones_semillas.cod_plantacion = bw_plantaciones.cod_plantacion)

				    LEFT JOIN bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_plantaciones_semillas.cod_inventario_semilla)

                    	LEFT JOIN

                    bw_formulario_harvesting_worksheet ON (bw_formulario_harvesting_worksheet.cod_plantacion = bw_plantaciones.cod_plantacion)

				    WHERE

				        bw_plantaciones_formulario_exploracion.fecha_exploracion BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

				    	AND bw_info_empresa.cod_info_empresa IN (".$cod_info_empresa.")

				    GROUP BY bw_plantaciones_formulario_exploracion.cod_exploracion) x

				GROUP BY cod_plantacion

				UNION ALL SELECT DISTINCT

				    bw_detalle_bloques_plantaciones_exploracion.cod_exploracion,

				    DATE_FORMAT(STR_TO_DATE(bw_detalle_bloques_plantaciones_exploracion.fecha_exploracion,'%Y-%m-%d'),'%m-%d-%Y') as fecha_exploracion,

				    DATE_FORMAT(STR_TO_DATE(bw_detalle_bloques_plantaciones_exploracion.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    bw_formulario_harvesting_worksheet.crop_number,

				    bw_plantaciones.cod_plantacion,

				    bw_plantaciones.anio_plantacion,

				    bw_plantaciones.num_plantacion,

				    bw_temporadas.codigo_temporada,

				    IFNULL(bw_inventario_semilla.flag_watercress, 0) AS flag_watercress,

				    bw_info_empresa.nombre_empresa,

				    bw_zonas.zona AS zonas,

				    CONCAT(IF(bw_bloques.clave_bloque != '',

				                CONCAT(bw_bloques.clave_bloque, '-'),

				                ''),

				            bw_bloques.nombre_bloque) AS bloques,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.gusanos + bw_detalle_bloques_plantaciones_exploracion.huevos + bw_detalle_bloques_plantaciones_exploracion.saltahojas + bw_detalle_bloques_plantaciones_exploracion.afidos + bw_detalle_bloques_plantaciones_exploracion.chinches + bw_detalle_bloques_plantaciones_exploracion.moscos + bw_detalle_bloques_plantaciones_exploracion.escarabajos + bw_detalle_bloques_plantaciones_exploracion.acaros)),

				            0) AS insectos,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.cercospora_leaf_spot + bw_detalle_bloques_plantaciones_exploracion.pythium + bw_detalle_bloques_plantaciones_exploracion.rhizoctonia + bw_detalle_bloques_plantaciones_exploracion.bacteria + bw_detalle_bloques_plantaciones_exploracion.sclerotinia + bw_detalle_bloques_plantaciones_exploracion.alternaria_specks + bw_detalle_bloques_plantaciones_exploracion.mildew + bw_detalle_bloques_plantaciones_exploracion.virus)),

				            0) AS enfermedades,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.dolar + bw_detalle_bloques_plantaciones_exploracion.frogs_bit + bw_detalle_bloques_plantaciones_exploracion.plantas_lodo + bw_detalle_bloques_plantaciones_exploracion.tripa_pollo + bw_detalle_bloques_plantaciones_exploracion.zacate)),

				            0) AS hierbas,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.hojas_danadas + bw_detalle_bloques_plantaciones_exploracion.tallos_purpuras + bw_detalle_bloques_plantaciones_exploracion.berro_enraizado)),

				            0) AS otros_danos,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.gusanos)),

				            0) AS gusanos,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.huevos)),

				            0) AS huevos,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.saltahojas)),

				            0) AS saltahojas,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.afidos)),

				            0) AS afidos,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.chinches)),

				            0) AS chinches,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.moscos)),

				            0) AS moscos,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.escarabajos)),

				            0) AS escarabajos,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.acaros)),

				            0) AS acaros,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.cercospora_leaf_spot)),

				            0) AS cercospora_leaf_spot,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.pythium)),

				            0) AS pythium,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.rhizoctonia)),

				            0) AS rhizoctonia,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.bacteria)),

				            0) AS bacteria,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.sclerotinia)),

				            0) AS sclerotinia,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.alternaria_specks)),

				            0) AS alternaria_specks,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.mildew)),

				            0) AS mildew,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.virus)),

				            0) AS virus,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.dolar)),

				            0) AS dolar,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.frogs_bit)),

				            0) AS frogs_bit,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.plantas_lodo)),

				            0) AS plantas_lodo,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.tripa_pollo)),

				            0) AS tripa_pollo,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.zacate)),

				            0) AS zacate,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.hojas_danadas)),

				            0) AS hojas_danadas,

				    IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.tallos_purpuras)),

				            0) AS tallos_purpuras,

					IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.spidermites)),

							0) AS spidermites,
					IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.white_rust)),

							0) AS white_rust,
					IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.salt_accumulation)),

							0) AS salt_accumulation,
					IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.nutsedge)),

							0) AS nutsedge,
					IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.buds)),

							0) AS buds,
					IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.zigzag_stems)),

							0) AS zigzag_stems,
					IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.nutrient_deficiency)),

							0) AS nutrient_deficiency,
					IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.round_up)),

							0) AS round_up,
					IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.light_color)),

							0) AS light_color,
					IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.mealybugs)),

							0) AS mealybugs,
					IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.thrips)),

							0) AS thrips,

					IFNULL((SELECT SUM(bw_detalle_bloques_plantaciones_exploracion.berro_enraizado)),

				            0) AS berro_enraizado

				FROM

				    bw_plantaciones

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_plantaciones.cod_info_empresa)

				        INNER JOIN

				    bw_temporadas ON (bw_temporadas.cod_temporada = bw_plantaciones.cod_temporada)

				        LEFT JOIN

				    bw_detalle_bloques_plantaciones ON (bw_detalle_bloques_plantaciones.cod_plantacion = bw_plantaciones.cod_plantacion)

				        LEFT JOIN

				    bw_bloques ON (bw_detalle_bloques_plantaciones.cod_bloque = bw_bloques.cod_bloque)

				        LEFT JOIN

				    bw_zonas ON (bw_bloques.cod_zona = bw_zonas.cod_zona)

				        LEFT JOIN

				    bw_detalle_bloques_plantaciones_exploracion ON (bw_detalle_bloques_plantaciones_exploracion.cod_detalle = bw_detalle_bloques_plantaciones.cod_detalle)

				        LEFT JOIN

				    bw_plantaciones_semillas ON (bw_plantaciones_semillas.cod_plantacion = bw_plantaciones.cod_plantacion)

				        LEFT JOIN

				    bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_plantaciones_semillas.cod_inventario_semilla)

                    	LEFT JOIN

                    bw_formulario_harvesting_worksheet ON (bw_formulario_harvesting_worksheet.cod_plantacion = bw_plantaciones.cod_plantacion)

				WHERE

				    bw_detalle_bloques_plantaciones_exploracion.fecha_exploracion BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

					AND bw_info_empresa.cod_info_empresa IN (".$cod_info_empresa.")

				GROUP BY bw_detalle_bloques_plantaciones_exploracion.cod_exploracion LIMIT 2500";

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

	 * Permite obtener listado de proveedores dentro de un rango de fechas con sus productos y precios.

	 */

	function rep_reporte_quimicos_proveedor($fecha_inicial,$fecha_final){

        $fecha_inicial .= ' 00:00:00';

        $fecha_final .= ' 23:59:59';

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

        $SQL = "SELECT DISTINCT

				    bw_proveedores.cod_info_empresa,

				    bw_proveedores.nombre_empresa,

				    bw_proveedores.nombre_contacto,

				    bw_proveedores.correo_contacto,

				    bw_proveedores.telefono_contacto,

				    bw_proveedores.observaciones,

				    bw_info_empresa.nombre_empresa AS finca,

				    GROUP_CONCAT(bw_tipo_quimico.tipo_quimico

				        SEPARATOR '</br> ') AS tipo_quimicos,

				    GROUP_CONCAT(bw_inventario_quimicos.nombre_quimico

				        SEPARATOR '</br> ') AS quimicos,

				    GROUP_CONCAT(ug_unidades_medida.unidad_medida

				        SEPARATOR '</br> ') AS unidades_medida,

				    GROUP_CONCAT(bw_inventario_quimicos.precio_quimico

				        SEPARATOR '</br> ') AS precios,

					(SELECT COUNT(bw_detalle_productos_proveedores.cod_detalle)) as num_productos,

					(SELECT SUM(bw_inventario_quimicos.precio_quimico)) as total_precios,

					DATE_FORMAT(STR_TO_DATE(bw_proveedores.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert

				FROM

				    bw_proveedores

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_proveedores.cod_info_empresa)

				        INNER JOIN

				    bw_detalle_productos_proveedores ON (bw_detalle_productos_proveedores.cod_proveedor = bw_proveedores.cod_proveedor)

				        INNER JOIN

				    bw_inventario_quimicos ON (bw_inventario_quimicos.cod_inventario = bw_detalle_productos_proveedores.cod_inventario

				        AND bw_detalle_productos_proveedores.flag_tipo_inventario = 1)

				        INNER JOIN

				    bw_tipo_quimico ON (bw_tipo_quimico.cod_tipo_quimico = bw_inventario_quimicos.cod_tipo_quimico)

				        INNER JOIN

				    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = bw_inventario_quimicos.cod_unidad_medida)

				WHERE

				    bw_proveedores.date_insert BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

				GROUP BY bw_proveedores.cod_proveedor;";

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

	 * Permite obtener listado de plantaciones con sus acres en producción.

	 */

	function rep_reporte_acres_produccion($fecha_inicial,$fecha_final,$cod_info_empresa){

        $fecha_inicial .= ' 00:00:00';

        $fecha_final .= ' 23:59:59';

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

        $SQL = "SELECT DISTINCT

				    bw_plantaciones.anio_plantacion,

				    bw_plantaciones.num_plantacion,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones.fecha_plantacion_planeada,'%Y-%m-%d'),'%m-%d-%Y') as fecha_plantacion_planeada,

				    bw_temporadas.codigo_temporada,

				    bw_info_empresa.nombre_empresa,

                    IFNULL(bw_inventario_semilla.flag_watercress,0) as flag_watercress,

				    GROUP_CONCAT(DISTINCT bw_zonas.zona

				        SEPARATOR '</br>') AS zonas,

				    GROUP_CONCAT(DISTINCT CONCAT(IF(bw_bloques.clave_bloque != '',

				                    CONCAT(bw_bloques.clave_bloque, '-'),

				                    ''),

				                bw_bloques.nombre_bloque)

				        SEPARATOR '</br>') AS bloques,

					bw_bloques.num_acres,

				    GROUP_CONCAT(DISTINCT CONCAT(IF(bloque_plantado.clave_bloque != '',

				                    CONCAT(bloque_plantado.clave_bloque, '-'),

				                    ''),

				                bloque_plantado.nombre_bloque)

				        SEPARATOR '</br>') AS plantados,

				    GROUP_CONCAT(DISTINCT CONCAT(IF(bloque_podado.clave_bloque != '',

				                    CONCAT(bloque_podado.clave_bloque, '-'),

				                    ''),

				                bloque_podado.nombre_bloque)

				        SEPARATOR '</br>') AS podados,

				    GROUP_CONCAT(DISTINCT CONCAT(IF(bloque_crecimiento.clave_bloque != '',

				                    CONCAT(bloque_crecimiento.clave_bloque, '-'),

				                    ''),

				                bloque_crecimiento.nombre_bloque)

				        SEPARATOR '</br>') AS crecimiento,

				    GROUP_CONCAT(DISTINCT CONCAT(IF(bloque_listo_cosecha.clave_bloque != '',

				                    CONCAT(bloque_listo_cosecha.clave_bloque, '-'),

				                    ''),

				                bloque_listo_cosecha.nombre_bloque)

				        SEPARATOR '</br>') AS listo_cosechar,

				    GROUP_CONCAT(DISTINCT CONCAT(IF(bloque_cosechado.clave_bloque != '',

				                    CONCAT(bloque_cosechado.clave_bloque, '-'),

				                    ''),

				                bloque_cosechado.nombre_bloque)

				        SEPARATOR '</br>') AS cosechado,

				    IFNULL((SELECT DISTINCT

				            SUM(bloque_plantado.num_acres)

				        FROM

				            bw_bloques bloque_plantado

				                LEFT JOIN

				            bw_detalle_bloques_plantaciones plantado ON (plantado.cod_bloque = bloque_plantado.cod_bloque)

				        WHERE

				            plantado.cod_plantacion = bw_plantaciones.cod_plantacion

				                AND plantado.cod_estado_plantacion = 2),0) AS cantidad_plantados,

				    IFNULL((SELECT DISTINCT

				            SUM(bloque_podado.num_acres)

				        FROM

				            bw_bloques bloque_podado

				                LEFT JOIN

				            bw_detalle_bloques_plantaciones podado ON (podado.cod_bloque = bloque_podado.cod_bloque)

				        WHERE

				            podado.cod_plantacion = bw_plantaciones.cod_plantacion

				                AND podado.cod_estado_plantacion = 3),0) AS cantidad_podados,

				    IFNULL((SELECT DISTINCT

				            SUM(bloque_crecimiento.num_acres)

				        FROM

				            bw_bloques bloque_crecimiento

				                LEFT JOIN

				            bw_detalle_bloques_plantaciones crecimiento ON (crecimiento.cod_bloque = bloque_crecimiento.cod_bloque)

				        WHERE

				            crecimiento.cod_plantacion = bw_plantaciones.cod_plantacion

				                AND crecimiento.cod_estado_plantacion = 4),0) AS cantidad_crecimiento,

				    IFNULL((SELECT DISTINCT

				            SUM(bloque_listo_cosecha.num_acres)

				        FROM

				            bw_bloques bloque_listo_cosecha

				                LEFT JOIN

				            bw_detalle_bloques_plantaciones listo_cosecha ON (listo_cosecha.cod_bloque = bloque_listo_cosecha.cod_bloque)

				        WHERE

				            listo_cosecha.cod_plantacion = bw_plantaciones.cod_plantacion

				                AND listo_cosecha.cod_estado_plantacion = 5),0) AS cantidad_listo_cosechar,

				    IFNULL((SELECT DISTINCT

				            SUM(bloque_cosechado.num_acres)

				        FROM

				            bw_bloques bloque_cosechado

				                LEFT JOIN

				            bw_detalle_bloques_plantaciones cosechado ON (cosechado.cod_bloque = bloque_cosechado.cod_bloque)

				        WHERE

				            cosechado.cod_plantacion = bw_plantaciones.cod_plantacion

				                AND cosechado.cod_estado_plantacion = 6),0) AS cantidad_cosechados,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    (SELECT DATE_FORMAT(STR_TO_DATE(bw_detalle_bloques_plantaciones_bitacora.date_update,'%Y-%m-%d'),'%m-%d-%Y') FROM bw_detalle_bloques_plantaciones_bitacora

				    WHERE bw_detalle_bloques_plantaciones_bitacora.cod_detalle = bw_detalle_bloques_plantaciones.cod_detalle

				    ORDER BY bw_detalle_bloques_plantaciones_bitacora.date_update DESC LIMIT 1) as date_update,

				    bw_formulario_harvesting_worksheet.crop_number

				FROM

				    bw_plantaciones

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_plantaciones.cod_info_empresa)

				        INNER JOIN

				    bw_temporadas ON (bw_temporadas.cod_temporada = bw_plantaciones.cod_temporada)

				        LEFT JOIN

				    bw_detalle_bloques_plantaciones ON (bw_detalle_bloques_plantaciones.cod_plantacion = bw_plantaciones.cod_plantacion

				        AND bw_detalle_bloques_plantaciones.cod_estado_plantacion < 9)

				        LEFT JOIN

				    bw_bloques ON (bw_detalle_bloques_plantaciones.cod_bloque = bw_bloques.cod_bloque)

				        LEFT JOIN

				    bw_zonas ON (bw_bloques.cod_zona = bw_zonas.cod_zona)

				        LEFT JOIN

				    bw_detalle_bloques_plantaciones plantado ON (plantado.cod_plantacion = bw_plantaciones.cod_plantacion

				        AND plantado.cod_estado_plantacion = 2)

				        LEFT JOIN

				    bw_bloques bloque_plantado ON (plantado.cod_bloque = bloque_plantado.cod_bloque)

				        LEFT JOIN

				    bw_detalle_bloques_plantaciones podado ON (podado.cod_plantacion = bw_plantaciones.cod_plantacion

				        AND podado.cod_estado_plantacion = 3)

				        LEFT JOIN

				    bw_bloques bloque_podado ON (podado.cod_bloque = bloque_podado.cod_bloque)

				        LEFT JOIN

				    bw_detalle_bloques_plantaciones crecimiento ON (crecimiento.cod_plantacion = bw_plantaciones.cod_plantacion

				        AND crecimiento.cod_estado_plantacion = 4)

				        LEFT JOIN

				    bw_bloques bloque_crecimiento ON (crecimiento.cod_bloque = bloque_crecimiento.cod_bloque)

				        LEFT JOIN

				    bw_detalle_bloques_plantaciones listo_cosecha ON (listo_cosecha.cod_plantacion = bw_plantaciones.cod_plantacion

				        AND listo_cosecha.cod_estado_plantacion = 5)

				        LEFT JOIN

				    bw_bloques bloque_listo_cosecha ON (listo_cosecha.cod_bloque = bloque_listo_cosecha.cod_bloque)

				        LEFT JOIN

				    bw_detalle_bloques_plantaciones cosechado ON (cosechado.cod_plantacion = bw_plantaciones.cod_plantacion

				        AND cosechado.cod_estado_plantacion = 6)

				        LEFT JOIN

				    bw_bloques bloque_cosechado ON (cosechado.cod_bloque = bloque_cosechado.cod_bloque)

						LEFT JOIN

					bw_plantaciones_semillas ON (bw_plantaciones_semillas.cod_plantacion = bw_plantaciones.cod_plantacion)

                    	LEFT JOIN

                    bw_inventario_semilla  ON(bw_inventario_semilla.cod_inventario = bw_plantaciones_semillas.cod_inventario_semilla)

                    	LEFT JOIN

                    bw_formulario_harvesting_worksheet ON (bw_formulario_harvesting_worksheet.cod_plantacion = bw_plantaciones.cod_plantacion)

				WHERE

				    bw_plantaciones.date_insert BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

				    AND bw_plantaciones.activo = 1 AND bw_plantaciones.cod_estado < 9 AND bw_plantaciones.cod_estado > 1

				    AND bw_info_empresa.cod_info_empresa IN (".$cod_info_empresa.")

				GROUP BY bw_detalle_bloques_plantaciones.cod_bloque;";

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

	 * Permite obtener listado de plantaciones con sus acres eliminados.

	 */

	function rep_reporte_acres_eliminados($fecha_inicial,$fecha_final,$cod_info_empresa){

        $fecha_inicial .= ' 00:00:00';

        $fecha_final .= ' 23:59:59';

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

        $SQL = "SELECT DISTINCT

					bw_detalle_bloques_plantaciones_bitacora.cod_bitacora,

				    bw_plantaciones.cod_plantacion,

				    bw_plantaciones.anio_plantacion,

				    bw_plantaciones.num_plantacion,

				    bw_temporadas.codigo_temporada,

				    bw_info_empresa.nombre_empresa,

				    IFNULL(bw_inventario_semilla.flag_watercress, 0) AS flag_watercress,

				    bw_zonas.zona AS zonas,

				    CONCAT(IF(bw_bloques.clave_bloque != '',

				                CONCAT(bw_bloques.clave_bloque, '-'),

				                ''),

				            bw_bloques.nombre_bloque) AS bloques,

				    bw_bloques.num_acres AS acres,

				    bw_detalle_bloques_plantaciones.motivo_estado_plantacion AS razones,

				    (SELECT

				            SUM(bw_bloques.num_acres)

				        FROM

				            bw_bloques

				                INNER JOIN

				            bw_detalle_bloques_plantaciones ON (bw_detalle_bloques_plantaciones.cod_bloque = bw_bloques.cod_bloque

				                AND bw_detalle_bloques_plantaciones.cod_estado_plantacion = 9)

				        WHERE

				            bw_detalle_bloques_plantaciones.cod_plantacion = bw_plantaciones.cod_plantacion) AS total_acres,

				    bw_detalle_bloques_plantaciones_bitacora.date_update,

				   	DATE_FORMAT(STR_TO_DATE(bw_plantaciones.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				   	bw_formulario_harvesting_worksheet.crop_number

				FROM

				    bw_plantaciones

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_plantaciones.cod_info_empresa)

				        INNER JOIN

				    bw_temporadas ON (bw_temporadas.cod_temporada = bw_plantaciones.cod_temporada)

				        INNER JOIN

				    bw_detalle_bloques_plantaciones ON (bw_detalle_bloques_plantaciones.cod_plantacion = bw_plantaciones.cod_plantacion

				        AND bw_detalle_bloques_plantaciones.cod_estado_plantacion = 9)

				        INNER JOIN

				    bw_detalle_bloques_plantaciones_bitacora ON (bw_detalle_bloques_plantaciones_bitacora.cod_plantacion = bw_plantaciones.cod_plantacion

				        AND bw_detalle_bloques_plantaciones_bitacora.cod_estado_plantacion = 9)

				        INNER JOIN

				    bw_bloques ON (bw_detalle_bloques_plantaciones_bitacora.cod_bloque = bw_bloques.cod_bloque)

				        INNER JOIN

				    bw_zonas ON (bw_bloques.cod_zona = bw_zonas.cod_zona)

				        LEFT JOIN

				    bw_plantaciones_semillas ON (bw_plantaciones_semillas.cod_plantacion = bw_plantaciones.cod_plantacion)

				        LEFT JOIN

				    bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_plantaciones_semillas.cod_inventario_semilla

				        AND bw_inventario_semilla.flag_watercress = 1)

                    	LEFT JOIN

                    bw_formulario_harvesting_worksheet ON (bw_formulario_harvesting_worksheet.cod_plantacion = bw_plantaciones.cod_plantacion)

				WHERE

				    bw_detalle_bloques_plantaciones_bitacora.date_update BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

					AND bw_info_empresa.cod_info_empresa IN (".$cod_info_empresa.")

				GROUP BY cod_bitacora";

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

	 * Permite obtener listado de plantaciones con sus registros de cultivos de tierra.

	 */

	function rep_reporte_plantaciones_tierra($fecha_inicial,$fecha_final,$cod_info_empresa){

        $fecha_inicial .= ' 00:00:00';

        $fecha_final .= ' 23:59:59';

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

        $SQL = "SELECT DISTINCT

				    GROUP_CONCAT(DISTINCT bw_plantaciones_semillas.cod_detalle

				        SEPARATOR '<br>') AS cod_detalle,

				    bw_plantaciones.cod_plantacion,

				    bw_plantaciones.anio_plantacion,

				    bw_plantaciones.num_plantacion,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones.fecha_plantacion_planeada,'%Y-%m-%d'),'%m-%d-%Y') as fecha_plantacion_planeada,

				    bw_temporadas.codigo_temporada,

				    bw_info_empresa.nombre_empresa,

				    GROUP_CONCAT(DISTINCT bw_zonas.zona

				        SEPARATOR '</br>') AS zonas,

				    CONCAT(IF(bw_bloques.clave_bloque != '',

				                CONCAT(bw_bloques.clave_bloque, '-'),

				                ''),

				            MIN(bw_bloques.nombre_bloque)) AS min_bloque,

				    CONCAT(IF(bw_bloques.clave_bloque != '',

				                CONCAT(bw_bloques.clave_bloque, '-'),

				                ''),

				            MAX(bw_bloques.nombre_bloque)) AS max_bloque,

				    GROUP_CONCAT(DISTINCT CONCAT(IF(bw_bloques.clave_bloque != '',

				                    CONCAT(bw_bloques.clave_bloque, '-'),

				                    ''),

				                bw_bloques.nombre_bloque)

				        SEPARATOR '<br>') AS bloques,

				    GROUP_CONCAT(bw_bloques.num_acres

				        SEPARATOR '</br>') AS acres,

				    (SELECT SUM(bw_bloques.num_acres)) AS total_acres,

				    (SELECT

				            GROUP_CONCAT(bw_plantaciones_semillas.cantidad_usada

				                    SEPARATOR '</br>')

				        FROM

				            bw_plantaciones_semillas

				        WHERE

				            bw_plantaciones_semillas.cod_plantacion = bw_plantaciones.cod_plantacion) AS cantidad_semillas,

				    GROUP_CONCAT(DISTINCT bw_inventario_semilla.nombre_semilla SEPARATOR '</br>') as semillas,

				    (SELECT

				            GROUP_CONCAT(bw_inventario_semilla.numero_lote

				                    SEPARATOR '</br>')

				        FROM

				            bw_inventario_semilla

				                INNER JOIN

				            bw_plantaciones_semillas ON (bw_plantaciones_semillas.cod_inventario_semilla = bw_inventario_semilla.cod_inventario)

				        WHERE

				            bw_plantaciones_semillas.cod_plantacion = bw_plantaciones.cod_plantacion) AS lotes,

				    (SELECT DISTINCT

				            SUM(bw_plantaciones_semillas.cantidad_usada)

				        FROM

				            bw_plantaciones_semillas

				        WHERE

				            bw_plantaciones_semillas.cod_plantacion = bw_plantaciones.cod_plantacion) AS total_cantidad_semilla,

				    (SELECT

				            GROUP_CONCAT(bw_inventario_maquinaria.codigo_maquinaria

				                    SEPARATOR '</br>')

				        FROM

				            bw_inventario_maquinaria

				                INNER JOIN

				            bw_plantaciones_semillas_maquinarias ON (bw_plantaciones_semillas_maquinarias.cod_maquinaria = bw_inventario_maquinaria.cod_inventario)

				                INNER JOIN

				            bw_plantaciones_semillas ON (bw_plantaciones_semillas.cod_detalle = bw_plantaciones_semillas_maquinarias.cod_detalle)

				        WHERE

				            bw_plantaciones_semillas.cod_plantacion = bw_plantaciones.cod_plantacion) AS codigos_maquinaria,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    usu_gerencias.gerencia,

				    bw_formulario_harvesting_worksheet.crop_number

				FROM

				    bw_plantaciones

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_plantaciones.cod_info_empresa)

				        INNER JOIN

				    bw_temporadas ON (bw_temporadas.cod_temporada = bw_plantaciones.cod_temporada)

				        INNER JOIN

				    bw_plantaciones_semillas ON (bw_plantaciones_semillas.cod_plantacion = bw_plantaciones.cod_plantacion)

				        LEFT JOIN

				    bw_plantaciones_semillas_bloques ON (bw_plantaciones_semillas_bloques.cod_detalle = bw_plantaciones_semillas.cod_detalle)

				        LEFT JOIN

				    bw_bloques ON (bw_plantaciones_semillas_bloques.cod_bloque = bw_bloques.cod_bloque)

				        LEFT JOIN

				    bw_zonas ON (bw_bloques.cod_zona = bw_zonas.cod_zona)

				        INNER JOIN

				    bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_plantaciones_semillas.cod_inventario_semilla

				        AND bw_inventario_semilla.flag_watercress = 0)

				        LEFT JOIN

				    bw_plantaciones_semillas_maquinarias ON (bw_plantaciones_semillas_maquinarias.cod_detalle = bw_plantaciones_semillas.cod_detalle)

				        LEFT JOIN

				    bw_inventario_maquinaria ON (bw_inventario_maquinaria.cod_inventario = bw_plantaciones_semillas_maquinarias.cod_maquinaria)

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_plantaciones_semillas.user_insert)

				        INNER JOIN

				    usu_gerencias ON (usu_gerencias.cod_gerencia = bw_info_empresa.cod_gerencia)

                    	LEFT JOIN

                    bw_formulario_harvesting_worksheet ON (bw_formulario_harvesting_worksheet.cod_plantacion = bw_plantaciones.cod_plantacion)

				WHERE

				    bw_plantaciones.fecha_plantacion_planeada BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

					AND bw_info_empresa.cod_info_empresa IN (".$cod_info_empresa.")

				GROUP BY bw_plantaciones.cod_plantacion , bw_plantaciones_semillas.cod_detalle";

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

	 * Permite obtener listado de plantaciones con sus registros de cultivos de tierra.

	 */

	function rep_reporte_plantaciones_tierra_totales($fecha_inicial,$fecha_final,$cod_info_empresa){

        $fecha_inicial .= ' 00:00:00';

        $fecha_final .= ' 23:59:59';

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

        $SQL = "SELECT DISTINCT

				    bw_plantaciones.cod_plantacion,

				    bw_plantaciones.anio_plantacion,

				    bw_plantaciones.num_plantacion,

				    bw_temporadas.codigo_temporada,

				    bw_info_empresa.nombre_empresa,

				    (SELECT SUM(bw_bloques.num_acres)) AS total_acres,

				    usu_gerencias.gerencia

				FROM

				    bw_plantaciones

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_plantaciones.cod_info_empresa)

				        INNER JOIN

				    bw_temporadas ON (bw_temporadas.cod_temporada = bw_plantaciones.cod_temporada)

				        INNER JOIN

				    bw_plantaciones_semillas ON (bw_plantaciones_semillas.cod_plantacion = bw_plantaciones.cod_plantacion)

				        LEFT JOIN

				    bw_plantaciones_semillas_bloques ON (bw_plantaciones_semillas_bloques.cod_detalle = bw_plantaciones_semillas.cod_detalle)

				        LEFT JOIN

				    bw_bloques ON (bw_plantaciones_semillas_bloques.cod_bloque = bw_bloques.cod_bloque)

				        LEFT JOIN

				    bw_zonas ON (bw_bloques.cod_zona = bw_zonas.cod_zona)

				        INNER JOIN

				    bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_plantaciones_semillas.cod_inventario_semilla

				        AND bw_inventario_semilla.flag_watercress = 0)

				        INNER JOIN

				    usu_gerencias ON (usu_gerencias.cod_gerencia = bw_info_empresa.cod_gerencia)

				WHERE

				    bw_plantaciones.date_insert BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

					AND bw_info_empresa.cod_info_empresa IN (".$cod_info_empresa.")

				GROUP BY bw_plantaciones.cod_plantacion";

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

	 * Permite obtener listado de control de calidad con sus tiempos y temperaturas en cuartos fríos y secciones.

	 */

	function rep_reporte_dashboard($fecha_inicial,$fecha_final){

        $fecha_inicial .= ' 00:00:00';

        $fecha_final .= ' 23:59:59';

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

        $SQL = "SELECT DISTINCT

					qua_maestro_control_calidad.cod_control_calidad,

					DATE_FORMAT(STR_TO_DATE(qua_maestro_control_calidad.fecha,'%Y-%m-%d'),'%m-%d-%Y') as fecha,

				    qua_maestro_control_calidad.cod_pais,

				    qua_maestro_control_calidad.cod_departamento,

				    geo_paises.pais,

				    geo_departamentos.departamento,

				    GROUP_CONCAT(CONCAT(qua_cuartos_frios.nombre_cuarto, '-',

				                qua_secciones_cuarto_frio.nombre_seccion)

				        SEPARATOR '</br>') AS cuartos,

					GROUP_CONCAT(qua_detalle_control_calidad.tiempo

				        SEPARATOR '</br>') AS tiempos,

					GROUP_CONCAT(qua_detalle_control_calidad.valor

				        SEPARATOR '</br>') AS temperaturas,

					GROUP_CONCAT(qua_detalle_control_calidad.observaciones

				        SEPARATOR '</br>') AS observaciones

				FROM qua_maestro_control_calidad

					INNER JOIN geo_paises ON (geo_paises.cod_pais = qua_maestro_control_calidad.cod_pais)

				    INNER JOIN geo_departamentos ON (geo_departamentos.cod_pais = qua_maestro_control_calidad.cod_pais

				    AND geo_departamentos.cod_departamento = qua_maestro_control_calidad.cod_departamento)

				    INNER JOIN qua_detalle_control_calidad ON (qua_detalle_control_calidad.cod_control_calidad = qua_maestro_control_calidad.cod_control_calidad)

				    INNER JOIN qua_cuartos_frios ON (qua_cuartos_frios.cod_cuarto = qua_detalle_control_calidad.cod_cuarto_frio)

				    INNER JOIN qua_secciones_cuarto_frio ON (qua_secciones_cuarto_frio.cod_seccion = qua_detalle_control_calidad.cod_seccion)

				WHERE qua_maestro_control_calidad.fecha BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

				GROUP BY qua_maestro_control_calidad.cod_control_calidad;";

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

	 * Permite obtener listado de plantaciones con sus calibraciones.

	 */

	function rep_reporte_calibracion($fecha_inicial,$fecha_final,$cod_info_empresa){

        $fecha_inicial .= ' 00:00:00';

        $fecha_final .= ' 23:59:59';

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

        $SQL = "SELECT DISTINCT

				    bw_plantaciones.anio_plantacion,

				    bw_plantaciones.num_plantacion,

				    bw_temporadas.codigo_temporada,

				    bw_info_empresa.nombre_empresa,

				    bw_plantaciones.cod_plantacion,

				    bw_plantaciones_calibraciones.cod_calibracion,

				    bw_plantaciones_calibraciones.cod_fertilizante,

				    bw_plantaciones_calibraciones.horas_aplicacion_calibrar,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_calibraciones.fecha_calibracion,'%Y-%m-%d'),'%m-%d-%Y') as fecha_calibracion,

				    bw_plantaciones_calibraciones.observaciones_calibrar,

				    bw_plantaciones_calibraciones.user_insert,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_calibraciones.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario,

				    (SELECT 'All Planting Zones') AS zonas,

				    (SELECT 'All Planting Blocks') AS bloques,

				    IFNULL(bw_inventario_semilla.flag_watercress, 0) AS flag_watercress,

				    (SELECT

				            COUNT(calibraciones.cod_calibracion)

				        FROM

				            bw_plantaciones_calibraciones calibraciones

				        WHERE

				            calibraciones.cod_plantacion = bw_plantaciones.cod_plantacion) AS total_calibraciones

				FROM

				    bw_plantaciones

				        INNER JOIN

				    bw_plantaciones_calibraciones ON (bw_plantaciones_calibraciones.cod_plantacion = bw_plantaciones.cod_plantacion)

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_plantaciones_calibraciones.user_insert)

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_plantaciones.cod_info_empresa)

				        INNER JOIN

				    bw_temporadas ON (bw_temporadas.cod_temporada = bw_plantaciones.cod_temporada)

				        LEFT JOIN

				    bw_plantaciones_semillas ON (bw_plantaciones_semillas.cod_plantacion = bw_plantaciones.cod_plantacion)

				        LEFT JOIN

				    bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_plantaciones_semillas.cod_inventario_semilla)

				WHERE

				    bw_plantaciones_calibraciones.fecha_calibracion BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

					AND bw_info_empresa.cod_info_empresa IN (".$cod_info_empresa.")

				UNION ALL SELECT DISTINCT

				    bw_plantaciones.anio_plantacion,

				    bw_plantaciones.num_plantacion,

				    bw_temporadas.codigo_temporada,

				    bw_info_empresa.nombre_empresa,

				    bw_plantaciones.cod_plantacion,

				    bw_detalle_bloques_plantaciones_calibraciones.cod_calibracion,

				    bw_detalle_bloques_plantaciones_calibraciones.cod_fertilizante,

				    bw_detalle_bloques_plantaciones_calibraciones.horas_aplicacion_calibrar,

				    DATE_FORMAT(STR_TO_DATE(bw_detalle_bloques_plantaciones_calibraciones.fecha_calibracion,'%Y-%m-%d'),'%m-%d-%Y') as fecha_calibracion,

				    bw_detalle_bloques_plantaciones_calibraciones.observaciones_calibrar,

				    bw_detalle_bloques_plantaciones_calibraciones.user_insert,

				    DATE_FORMAT(STR_TO_DATE(bw_detalle_bloques_plantaciones_calibraciones.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario,

				    bw_zonas.zona AS zonas,

				    CONCAT(IF(bw_bloques.clave_bloque != '',

				                CONCAT(bw_bloques.clave_bloque, '-'),

				                ''),

				            bw_bloques.nombre_bloque) AS bloques,

				    IFNULL(bw_inventario_semilla.flag_watercress, 0) AS flag_watercress,

				    (SELECT

				            COUNT(calibraciones.cod_detalle)

				        FROM

				            bw_detalle_bloques_plantaciones_calibraciones calibraciones

				                INNER JOIN

				            bw_detalle_bloques_plantaciones ON (calibraciones.cod_detalle = bw_detalle_bloques_plantaciones.cod_detalle)

				        WHERE

				            bw_detalle_bloques_plantaciones.cod_plantacion = bw_plantaciones.cod_plantacion) AS total_calibraciones

				FROM

				    bw_plantaciones

				        INNER JOIN

				    bw_detalle_bloques_plantaciones ON (bw_detalle_bloques_plantaciones.cod_plantacion = bw_plantaciones.cod_plantacion)

				        INNER JOIN

				    bw_detalle_bloques_plantaciones_calibraciones ON (bw_detalle_bloques_plantaciones_calibraciones.cod_detalle = bw_detalle_bloques_plantaciones.cod_detalle)

				        INNER JOIN

				    bw_bloques ON (bw_bloques.cod_bloque = bw_detalle_bloques_plantaciones.cod_bloque)

				        INNER JOIN

				    bw_zonas ON (bw_zonas.cod_zona = bw_bloques.cod_zona)

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_detalle_bloques_plantaciones_calibraciones.user_insert)

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_plantaciones.cod_info_empresa)

				        INNER JOIN

				    bw_temporadas ON (bw_temporadas.cod_temporada = bw_plantaciones.cod_temporada)

				        LEFT JOIN

				    bw_plantaciones_semillas ON (bw_plantaciones_semillas.cod_plantacion = bw_plantaciones.cod_plantacion)

				        LEFT JOIN

				    bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_plantaciones_semillas.cod_inventario_semilla)

				WHERE

				    bw_detalle_bloques_plantaciones_calibraciones.fecha_calibracion BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

				    AND bw_info_empresa.cod_info_empresa IN (".$cod_info_empresa.");";

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

	 * Permite obtener listado de plantaciones con plantación watercress.

	 */

	function rep_reporte_plantaciones_watercress($fecha_inicial,$fecha_final,$cod_info_empresa){

        $fecha_inicial .= ' 00:00:00';

        $fecha_final .= ' 23:59:59';

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

        $SQL = "SELECT DISTINCT

				    plantacion_envia.anio_plantacion AS anio_plantacion_envia,

				    plantacion_envia.num_plantacion AS num_plantacion_envia,

				    temporadas_envia.codigo_temporada AS codigo_temporada_envia,

				    DATE_FORMAT(STR_TO_DATE(plantacion_envia.fecha_plantacion_planeada,'%Y-%m-%d'),'%m-%d-%Y') AS fecha_planeada_envia,

				    finca_envia.nombre_empresa AS nombre_empresa_envia,

				    GROUP_CONCAT(DISTINCT zona_envia.zona

				        SEPARATOR '</br>') AS zonas_envia,

				    GROUP_CONCAT(DISTINCT CONCAT(IF(bloques_envia.clave_bloque != '',

				                    CONCAT(bloques_envia.clave_bloque, '-'),

				                    ''),

				                bloques_envia.nombre_bloque)

				        SEPARATOR '</br>') AS bloques_envia,

				    (SELECT SUM(bloques_envia.num_acres)) AS total_acres_envia,

				    plantacion_recibe.anio_plantacion AS anio_plantacion_recibe,

				    plantacion_recibe.num_plantacion AS num_plantacion_recibe,

				    temporadas_recibe.codigo_temporada AS codigo_temporada_recibe,

				    DATE_FORMAT(STR_TO_DATE(plantacion_recibe.fecha_plantacion_planeada,'%Y-%m-%d'),'%m-%d-%Y') AS fecha_planeada_recibe,

				    finca_recibe.nombre_empresa AS nombre_empresa_recibe,

				    GROUP_CONCAT(DISTINCT zona_recibe.zona

				        SEPARATOR '</br>') AS zonas_recibe,

				    GROUP_CONCAT(DISTINCT CONCAT(IF(bloques_recibe.clave_bloque != '',

				                    CONCAT(bloques_recibe.clave_bloque, '-'),

				                    ''),

				                bloques_recibe.nombre_bloque)

				        SEPARATOR '</br>') AS bloques_recibe,

				    bw_plantaciones_trasplantes.cantidad,

				    (SELECT SUM(bloques_recibe.num_acres)) AS total_acres_recibe,

				    bw_plantaciones_trasplantes.observacion,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_trasplantes.fecha_trasplante,'%Y-%m-%d'),'%m-%d-%Y') AS fecha_trasplante,

				    bw_plantaciones_trasplantes.user_insert,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_trasplantes.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    gerencia_envia.gerencia as gerencia_envia,

				    gerencia_recibe.gerencia as gerencia_recibe

				FROM

				    bw_plantaciones_trasplantes

				        INNER JOIN

				    bw_plantaciones plantacion_envia ON (plantacion_envia.cod_plantacion = bw_plantaciones_trasplantes.cod_plantacion_envia)

				        INNER JOIN

				    bw_plantaciones plantacion_recibe ON (plantacion_recibe.cod_plantacion = bw_plantaciones_trasplantes.cod_plantacion_recibe)

				        INNER JOIN

				    bw_bloques bloques_envia ON FIND_IN_SET(bloques_envia.cod_bloque,

				            bw_plantaciones_trasplantes.cod_bloques)

				        INNER JOIN

				    bw_zonas zona_envia ON (zona_envia.cod_zona = bloques_envia.cod_zona)

				        INNER JOIN

				    bw_bloques bloques_recibe ON FIND_IN_SET(bloques_recibe.cod_bloque,

				            bw_plantaciones_trasplantes.cod_bloques_trasplante)

				        INNER JOIN

				    bw_zonas zona_recibe ON (zona_recibe.cod_zona = bloques_recibe.cod_zona)

				        INNER JOIN

				    bw_temporadas temporadas_envia ON (temporadas_envia.cod_temporada = plantacion_envia.cod_temporada)

				        INNER JOIN

				    bw_temporadas temporadas_recibe ON (temporadas_recibe.cod_temporada = plantacion_recibe.cod_temporada)

				        INNER JOIN

				    bw_info_empresa finca_envia ON (finca_envia.cod_info_empresa = plantacion_envia.cod_info_empresa)

				        INNER JOIN

				    bw_info_empresa finca_recibe ON (finca_recibe.cod_info_empresa = plantacion_recibe.cod_info_empresa)

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_plantaciones_trasplantes.user_insert)

				        INNER JOIN

				    usu_gerencias gerencia_envia ON (gerencia_envia.cod_gerencia = finca_envia.cod_gerencia)

				        INNER JOIN

				    usu_gerencias gerencia_recibe ON (gerencia_recibe.cod_gerencia = finca_recibe.cod_gerencia)

				WHERE

				    bw_plantaciones_trasplantes.date_insert BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

					AND (plantacion_envia.cod_info_empresa IN (".$cod_info_empresa.") OR plantacion_recibe.cod_info_empresa IN (".$cod_info_empresa."))

				GROUP BY bw_plantaciones_trasplantes.cod_trasplante";

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

	 * Permite obtener listado de plantaciones con plantación watercress.

	 */

	function rep_reporte_plantaciones_watercress_totales($fecha_inicial,$fecha_final,$cod_info_empresa){

        $fecha_inicial .= ' 00:00:00';

        $fecha_final .= ' 23:59:59';

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

        $SQL = "SELECT DISTINCT

				    bw_plantaciones_trasplantes.cod_trasplante,

				    plantacion_envia.anio_plantacion AS anio_plantacion_envia,

				    plantacion_envia.num_plantacion AS num_plantacion_envia,

				    temporadas_envia.codigo_temporada AS codigo_temporada_envia,

				    finca_envia.nombre_empresa AS nombre_empresa_envia,

				    GROUP_CONCAT(zona_envia.zona

				        SEPARATOR '</br>') AS zonas_envia,

				    GROUP_CONCAT(CONCAT(IF(bloques_envia.clave_bloque != '',

				                    CONCAT(bloques_envia.clave_bloque, '-'),

				                    ''),

				                bloques_envia.nombre_bloque)

				        SEPARATOR '</br>') AS bloques_envia,

				    plantacion_recibe.anio_plantacion AS anio_plantacion_recibe,

				    plantacion_recibe.num_plantacion AS num_plantacion_recibe,

				    temporadas_recibe.codigo_temporada AS codigo_temporada_recibe,

				    finca_recibe.nombre_empresa AS nombre_empresa_recibe,

				    bw_plantaciones_trasplantes.cantidad,

				    (SELECT SUM(bloques_envia.num_acres)) AS total_acres,

				    (SELECT SUM(bloques_recibe.num_acres)) AS total_acres_recibe,

				    bw_plantaciones_trasplantes.observacion,

				    bw_plantaciones_trasplantes.user_insert,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario

				FROM

				    bw_plantaciones_trasplantes

				        INNER JOIN

				    bw_plantaciones plantacion_envia ON (plantacion_envia.cod_plantacion = bw_plantaciones_trasplantes.cod_plantacion_envia)

				        INNER JOIN

				    bw_plantaciones plantacion_recibe ON (plantacion_recibe.cod_plantacion = bw_plantaciones_trasplantes.cod_plantacion_recibe)

				        INNER JOIN

				    bw_bloques bloques_envia ON FIND_IN_SET(bloques_envia.cod_bloque,

				            bw_plantaciones_trasplantes.cod_bloques)

				        INNER JOIN

				    bw_bloques bloques_recibe ON FIND_IN_SET(bloques_recibe.cod_bloque,

				            bw_plantaciones_trasplantes.cod_bloques_trasplante)

				        INNER JOIN

				    bw_zonas zona_envia ON (zona_envia.cod_zona = bloques_envia.cod_zona)

				        INNER JOIN

				    bw_temporadas temporadas_envia ON (temporadas_envia.cod_temporada = plantacion_envia.cod_temporada)

				        INNER JOIN

				    bw_temporadas temporadas_recibe ON (temporadas_recibe.cod_temporada = plantacion_recibe.cod_temporada)

				        INNER JOIN

				    bw_info_empresa finca_envia ON (finca_envia.cod_info_empresa = plantacion_envia.cod_info_empresa)

				        INNER JOIN

				    bw_info_empresa finca_recibe ON (finca_recibe.cod_info_empresa = plantacion_recibe.cod_info_empresa)

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_plantaciones_trasplantes.user_insert)

				WHERE

				    bw_plantaciones_trasplantes.date_insert BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

					AND (plantacion_envia.cod_info_empresa IN (".$cod_info_empresa.") OR plantacion_recibe.cod_info_empresa IN (".$cod_info_empresa."))

				GROUP BY bw_plantaciones_trasplantes.cod_plantacion_recibe";

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

	 * Permite obtener listado de plantaciones con aplicaciones químicas.

	 */

	function rep_reporte_aplicaciones_quimicas($fecha_inicial,$fecha_final){

        $fecha_inicial .= ' 00:00:00';

        $fecha_final .= ' 23:59:59';

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

        $SQL = "SELECT DISTINCT

				    bw_plantaciones_aplicar_quimicos.cod_aplicacion,

				    bw_plantaciones.anio_plantacion,

				    bw_plantaciones.num_plantacion,

				    bw_temporadas.codigo_temporada,

				    bw_info_empresa.nombre_empresa,

				    bw_plantaciones.cod_plantacion,

				    CONCAT(usu_usuarios.nombre_1,

				            ' ',

				            usu_usuarios.apellido_1) AS nombre_usuario,

				    GROUP_CONCAT(DISTINCT bw_zonas.zona

				        SEPARATOR '</br>') AS zonas,

				    GROUP_CONCAT(CONCAT(IF(bw_bloques.clave_bloque != '',

				                    CONCAT(bw_bloques.clave_bloque, '-'),

				                    ''),

				                bw_bloques.nombre_bloque)

				        SEPARATOR '</br>') AS bloques,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_aplicar_quimicos.fecha_aplicacion_supervisor,'%Y-%m-%d'),'%m-%d-%Y') as fecha_aplicacion_supervisor,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones_aplicar_quimicos.fecha_aplicacion_operador,'%Y-%m-%d'),'%m-%d-%Y') as fecha_aplicacion_operador,

				    bw_plantaciones_aplicar_quimicos.hora_inicial,

				    bw_plantaciones_aplicar_quimicos.hora_final,

				    bw_plantaciones_aplicar_quimicos.viento,

				    bw_plantaciones_aplicar_quimicos.temperatura,

				    DATE_FORMAT(STR_TO_DATE(bw_plantaciones.fecha_plantacion_planeada,'%Y-%m-%d'),'%m-%d-%Y') as fecha_plantacion_planeada

				FROM

				    bw_plantaciones

				        INNER JOIN

				    bw_plantaciones_aplicar_quimicos ON (bw_plantaciones_aplicar_quimicos.cod_plantacion = bw_plantaciones.cod_plantacion)

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_plantaciones.cod_info_empresa)

				        INNER JOIN

				    bw_bloques ON (FIND_IN_SET(bw_bloques.cod_bloque,

				            bw_plantaciones_aplicar_quimicos.cod_bloques_aplicacion))

				        INNER JOIN

				    bw_zonas ON (bw_zonas.cod_zona = bw_bloques.cod_zona)

				        INNER JOIN

				    bw_temporadas ON (bw_temporadas.cod_temporada = bw_plantaciones.cod_temporada)

				        INNER JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_plantaciones_aplicar_quimicos.user_insert)

				WHERE

				    bw_plantaciones_aplicar_quimicos.activo = 1

				        AND bw_plantaciones_aplicar_quimicos.fecha_aplicacion_supervisor BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

				GROUP BY bw_plantaciones_aplicar_quimicos.cod_aplicacion;";

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

	 * Permite obtener listado de inventario químico con sus movimientos y montos actuales.

	 */

	function rep_reporte_inventario($fecha_inicial,$fecha_final,$cod_info_empresa){

        $fecha_inicial .= ' 00:00:00';

        $fecha_final .= ' 23:59:59';

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

        $SQL = "SELECT DISTINCT

				    inventario_quimico.cod_inventario,

				    inventario_quimico.cod_info_empresa,

				    bw_info_empresa.nombre_empresa,

				    inventario_quimico.cod_quimico,

				    bw_tipo_quimico.tipo_quimico,

				    inventario_quimico.nombre_quimico,

				    inventario_quimico.cod_unidad_medida,

				    ug_unidades_medida.unidad_medida,

				    inventario_quimico.cantidad_quimico,

				    inventario_quimico.cantidad_fisica_quimico,

				    inventario_quimico.registro_ambiental,

				    IFNULL((SELECT SUM(movimientos_envia.cantidad_enviada) * conversion_movimientos.conversion),

				            0) AS cantidad_enviada,

				    IFNULL((SELECT SUM(movimientos_recibe.cantidad_recibida) * conversion_movimientos.conversion),

				            0) AS cantidad_recibida,

				    IFNULL((SELECT SUM(bw_plantaciones_detalle_aplicar_quimicos.cantidad_aplicada) * conversion_aplicacion.conversion),

				            0) AS cantidad_aplicada,

				    IFNULL((SELECT SUM(bw_plantaciones_detalle_aplicar_quimicos.cantidad_sugerida) * conversion_aplicacion.conversion),

				            0) AS cantidad_sugerida,

				    IFNULL(conversion_movimientos.conversion, 0) AS conversion_movimientos,

				    IFNULL(conversion_aplicacion.conversion, 0) AS conversion_aplicacion,

				    IFNULL((SELECT

				                    SUM(bw_detalle_ordenes_compra.cantidad) * conversion_orden_compra.conversion

				                FROM

				                    bw_detalle_productos_proveedores

				                        INNER JOIN

				                    bw_detalle_ordenes_compra ON (bw_detalle_productos_proveedores.cod_detalle = bw_detalle_ordenes_compra.cod_detalle_producto)

				                        INNER JOIN

				                    bw_conversiones_unidades_medida conversion_orden_compra ON (conversion_orden_compra.cod_unidad_medida_origen = bw_detalle_ordenes_compra.cod_unidad_medida

				                        AND conversion_orden_compra.cod_unidad_medida_destino = bw_detalle_ordenes_compra.cod_unidad_medida)

				                        INNER JOIN

				                    bw_ordenes_compra ON (bw_ordenes_compra.cod_orden = bw_detalle_ordenes_compra.cod_orden)

				                WHERE

				                    bw_detalle_productos_proveedores.cod_inventario = inventario_quimico.cod_inventario

				                        AND bw_ordenes_compra.fecha_recibido_pedido BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

				                        AND bw_detalle_productos_proveedores.flag_tipo_inventario = 1),

				            0) AS cantidad_orden_compra

				FROM

				    bw_inventario_quimicos inventario_quimico

				        LEFT JOIN

				    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = inventario_quimico.cod_unidad_medida)

				        INNER JOIN

				    bw_tipo_quimico ON (bw_tipo_quimico.cod_tipo_quimico = inventario_quimico.cod_tipo_quimico)

				        LEFT JOIN

				    bw_movimientos_inventario movimientos_envia ON (movimientos_envia.cod_inventario = inventario_quimico.cod_inventario

				        AND movimientos_envia.fecha_envia BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

				        AND movimientos_envia.cod_tipo_inventario = 2 AND movimientos_envia.activo = 1)

				        LEFT JOIN

				    bw_movimientos_inventario movimientos_recibe ON (movimientos_recibe.cod_inventario = inventario_quimico.cod_inventario

				        AND movimientos_recibe.fecha_recibe BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

				        AND movimientos_recibe.cod_tipo_inventario = 2 AND movimientos_recibe.activo = 1)

				        LEFT JOIN

				    bw_plantaciones_detalle_aplicar_quimicos ON (bw_plantaciones_detalle_aplicar_quimicos.cod_inventario = inventario_quimico.cod_inventario

				    AND bw_plantaciones_detalle_aplicar_quimicos.activo = 1)

				    	LEFT JOIN

				    bw_plantaciones_aplicar_quimicos ON (bw_plantaciones_aplicar_quimicos.cod_aplicacion = bw_plantaciones_detalle_aplicar_quimicos.cod_aplicacion

				    AND (bw_plantaciones_aplicar_quimicos.fecha_aplicacion_operador BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')

				    /*OR bw_plantaciones_aplicar_quimicos.fecha_aplicacion_supervisor BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,'%m-%d-%Y %H:%i:%s'),'%Y-%m-%d %H:%i:%s')*/)

				    AND bw_plantaciones_aplicar_quimicos.activo = 1)

				        LEFT JOIN

				    bw_conversiones_unidades_medida conversion_movimientos ON (conversion_movimientos.cod_unidad_medida_origen = inventario_quimico.cod_unidad_medida

				        AND conversion_movimientos.cod_unidad_medida_destino = movimientos_envia.cod_unidad_medida)

				        LEFT JOIN

				    bw_conversiones_unidades_medida conversion_aplicacion ON (conversion_aplicacion.cod_unidad_medida_origen = inventario_quimico.cod_unidad_medida

				        AND conversion_aplicacion.cod_unidad_medida_destino = bw_plantaciones_detalle_aplicar_quimicos.cod_unidad_medida)

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = inventario_quimico.cod_info_empresa)

				WHERE

				    inventario_quimico.activo = 1 AND bw_info_empresa.cod_info_empresa IN (".$cod_info_empresa.")

				GROUP BY inventario_quimico.cod_inventario;";

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

	 * Permite obtener listado de inventario químico con sus movimientos y montos actuales.

	 */

	function rep_nuevo_reporte_inventario($fecha_inicial,$fecha_final,$cod_info_empresa){

        $fecha_inicial .= ' 00:00:00';

        $fecha_final .= ' 23:59:59';

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));SET SQL_BIG_SELECTS=1;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

        $SQL = "SELECT DISTINCT

				    inventario_quimico.cod_inventario,

				    inventario_quimico.cod_info_empresa,

				    bw_info_empresa.nombre_empresa,

				    inventario_quimico.cod_quimico,

				    bw_tipo_quimico.tipo_quimico,

				    inventario_quimico.nombre_quimico,

				    inventario_quimico.cod_unidad_medida,

				    ug_unidades_medida.unidad_medida,

                    movimientos_recibe.cod_unidad_medida as cod_unidad_medida_recibe,

                    movimientos_envia.cod_unidad_medida as cod_unidad_medida_envia,

				    inventario_quimico.cantidad_quimico,

				    inventario_quimico.cantidad_fisica_quimico,

				    inventario_quimico.registro_ambiental,

				    IFNULL((SELECT SUM(movimientos_envia.cantidad_enviada) * conversion_movimientos_envia.conversion),

				            0) AS cantidad_enviada,

				    IFNULL((SELECT SUM(movimientos_recibe.cantidad_recibida) * conversion_movimientos_recibe.conversion),

				            0) AS cantidad_recibida,

				    (SELECT

				            SUM(bw_plantaciones_detalle_aplicar_quimicos.cantidad_aplicada) * bw_conversiones_unidades_medida.conversion

				        FROM

				            bw_plantaciones_detalle_aplicar_quimicos

				                INNER JOIN

				            bw_inventario_quimicos ON (bw_plantaciones_detalle_aplicar_quimicos.cod_inventario = bw_inventario_quimicos.cod_inventario)

				                INNER JOIN

				            bw_plantaciones_aplicar_quimicos ON (bw_plantaciones_aplicar_quimicos.cod_aplicacion = bw_plantaciones_detalle_aplicar_quimicos.cod_aplicacion

				                AND (bw_plantaciones_aplicar_quimicos.fecha_aplicacion_operador BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s')

				                OR bw_plantaciones_aplicar_quimicos.fecha_aplicacion_supervisor BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s'))

				                AND bw_plantaciones_aplicar_quimicos.activo = 1)

				                INNER JOIN

				            bw_conversiones_unidades_medida ON (bw_conversiones_unidades_medida.cod_unidad_medida_origen = bw_inventario_quimicos.cod_unidad_medida

				                AND bw_conversiones_unidades_medida.cod_unidad_medida_destino = bw_plantaciones_detalle_aplicar_quimicos.cod_unidad_medida)

				        WHERE

				            bw_inventario_quimicos.cod_inventario = inventario_quimico.cod_inventario) AS cantidad_aplicada,

				    (SELECT

				            SUM(bw_plantaciones_detalle_aplicar_quimicos.cantidad_sugerida) * bw_conversiones_unidades_medida.conversion

				        FROM

				            bw_plantaciones_detalle_aplicar_quimicos

				                INNER JOIN

				            bw_inventario_quimicos ON (bw_plantaciones_detalle_aplicar_quimicos.cod_inventario = bw_inventario_quimicos.cod_inventario)

				                INNER JOIN

				            bw_plantaciones_aplicar_quimicos ON (bw_plantaciones_aplicar_quimicos.cod_aplicacion = bw_plantaciones_detalle_aplicar_quimicos.cod_aplicacion

				                AND (bw_plantaciones_aplicar_quimicos.fecha_aplicacion_operador BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s')

				                OR bw_plantaciones_aplicar_quimicos.fecha_aplicacion_supervisor BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s'))

				                AND bw_plantaciones_aplicar_quimicos.activo = 1)

				                INNER JOIN

				            bw_conversiones_unidades_medida ON (bw_conversiones_unidades_medida.cod_unidad_medida_origen = bw_inventario_quimicos.cod_unidad_medida

				                AND bw_conversiones_unidades_medida.cod_unidad_medida_destino = bw_plantaciones_detalle_aplicar_quimicos.cod_unidad_medida)

				        WHERE

				            bw_inventario_quimicos.cod_inventario = inventario_quimico.cod_inventario) AS cantidad_sugerida,

				    IFNULL(conversion_movimientos_envia.conversion, 0) AS conversion_movimientos_envia,

				    IFNULL(conversion_movimientos_recibe.conversion, 0) AS conversion_movimientos_recibe,

                    conversion_movimientos_recibe.cod_unidad_medida_destino,

                    conversion_movimientos_recibe.cod_unidad_medida_origen,

				    IF(inventario_suma.fecha_sumar_restar BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s'), IFNULL(SUM(inventario_suma.cantidad_sumar_restar),

				            0),0) AS cantidad_sumar_bitacora,

				    IF(inventario_resta.fecha_sumar_restar BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s'), IFNULL(SUM(inventario_resta.cantidad_sumar_restar),

				            0),0) AS cantidad_restar_bitacora,

				    IF(inventario_quimico.fecha_sumar_restar BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s'), IF(inventario_quimico.sumar_restar = 1,

				        IFNULL(SUM(inventario_quimico.cantidad_sumar_restar),

				                0),

				        0),0) AS cantidad_sumar,

				    IF(inventario_quimico.fecha_sumar_restar BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s'),IF(inventario_quimico.sumar_restar = 0,

				        IFNULL(SUM(inventario_quimico.cantidad_sumar_restar),

				                0),

				        0),0) AS cantidad_restar,

				    IFNULL((SELECT

				                    SUM(bw_detalle_ordenes_compra.cantidad) * conversion_orden_compra.conversion

				                FROM

				                    bw_detalle_productos_proveedores

				                        INNER JOIN

				                    bw_detalle_ordenes_compra ON (bw_detalle_productos_proveedores.cod_detalle = bw_detalle_ordenes_compra.cod_detalle_producto)

				                        INNER JOIN

				                    bw_conversiones_unidades_medida conversion_orden_compra ON (conversion_orden_compra.cod_unidad_medida_origen = bw_detalle_ordenes_compra.cod_unidad_medida

				                        AND conversion_orden_compra.cod_unidad_medida_destino = bw_detalle_ordenes_compra.cod_unidad_medida)

				                        INNER JOIN

				                    bw_ordenes_compra ON (bw_ordenes_compra.cod_orden = bw_detalle_ordenes_compra.cod_orden)

				                WHERE

				                    bw_detalle_productos_proveedores.cod_inventario = inventario_quimico.cod_inventario

				                        AND bw_ordenes_compra.fecha_recibido_pedido BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                                    '%m-%d-%Y %H:%i:%s'),

				                            '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                                    '%m-%d-%Y %H:%i:%s'),

				                            '%Y-%m-%d %H:%i:%s')

				                        AND bw_detalle_productos_proveedores.flag_tipo_inventario = 1),

				            0) AS cantidad_orden_compra,

				    (SELECT

				            GROUP_CONCAT(CONCAT(usu_usuarios.nombre_1,

				                            ' ',

				                            usu_usuarios.apellido_1)

				                    SEPARATOR '<br>')

				        FROM

				            usu_usuarios

				                INNER JOIN

				            bw_inventario_quimicos_bitacora ON (bw_inventario_quimicos_bitacora.user_update = usu_usuarios.cod_usuario)

				        WHERE

				            bw_inventario_quimicos_bitacora.cod_inventario = inventario_quimico.cod_inventario

				                AND bw_inventario_quimicos_bitacora.date_update BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s')

				                AND bw_inventario_quimicos_bitacora.cantidad_sumar_restar > 0

                                AND usu_usuarios.activo = 1) AS nombre_usuario,

				    (SELECT

				            GROUP_CONCAT(bw_inventario_quimicos_bitacora.razon_sumar_restar

				                    SEPARATOR '<br>')

				        FROM

				            bw_inventario_quimicos_bitacora

				        WHERE

				            bw_inventario_quimicos_bitacora.cod_inventario = inventario_quimico.cod_inventario

				                AND bw_inventario_quimicos_bitacora.fecha_sumar_restar BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s')

				                AND bw_inventario_quimicos_bitacora.cantidad_sumar_restar > 0) AS razon_sumar_restar_bitacora,

				    IF(inventario_quimico.fecha_sumar_restar BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                        '%m-%d-%Y %H:%i:%s'),

				                '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                        '%m-%d-%Y %H:%i:%s'),

				                '%Y-%m-%d %H:%i:%s'),

				        inventario_quimico.razon_sumar_restar,

				        NULL) AS razon_sumar_restar,

				    (SELECT

				            GROUP_CONCAT(DATE_FORMAT(STR_TO_DATE(bw_inventario_quimicos_bitacora.fecha_sumar_restar,

				                                    '%Y-%m-%d %H:%i:%s'),

				                            '%m-%d-%Y %H:%i:%s')

				                    SEPARATOR '<br>')

				        FROM

				            bw_inventario_quimicos_bitacora

				        WHERE

				            bw_inventario_quimicos_bitacora.cod_inventario = inventario_quimico.cod_inventario

				                AND bw_inventario_quimicos_bitacora.fecha_sumar_restar BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s')

				                AND bw_inventario_quimicos_bitacora.cantidad_sumar_restar > 0) AS fecha_sumar_restar_bitacora,

				    IF(inventario_quimico.fecha_sumar_restar BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                        '%m-%d-%Y %H:%i:%s'),

				                '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                        '%m-%d-%Y %H:%i:%s'),

				                '%Y-%m-%d %H:%i:%s'),

				        DATE_FORMAT(STR_TO_DATE(inventario_quimico.fecha_sumar_restar,

				                        '%Y-%m-%d %H:%i:%s'),

				                '%m-%d-%Y %H:%i:%s'),

				        NULL) AS fecha_sumar_restar,

				    (SELECT

				            GROUP_CONCAT(DATE_FORMAT(STR_TO_DATE(bw_inventario_quimicos_bitacora.date_update,

				                                    '%Y-%m-%d %H:%i:%s'),

				                            '%m-%d-%Y %H:%i:%s')

				                    SEPARATOR '<br>')

				        FROM

				            bw_inventario_quimicos_bitacora

				        WHERE

				            bw_inventario_quimicos_bitacora.cod_inventario = inventario_quimico.cod_inventario

				                AND bw_inventario_quimicos_bitacora.date_update BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s')

				                AND bw_inventario_quimicos_bitacora.cantidad_sumar_restar > 0) AS date_update,

				    IF((SELECT

				                bw_inventario_quimicos_bitacora.cantidad_quimico

				            FROM

				                bw_inventario_quimicos_bitacora

				            WHERE

				                bw_inventario_quimicos_bitacora.cod_inventario = inventario_quimico.cod_inventario

				                    AND bw_inventario_quimicos_bitacora.date_update BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                                '%m-%d-%Y %H:%i:%s'),

				                        '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                                '%m-%d-%Y %H:%i:%s'),

				                        '%Y-%m-%d %H:%i:%s')

				            ORDER BY bw_inventario_quimicos_bitacora.date_update ASC

				            LIMIT 1) IS NULL,

				        (SELECT

				                bw_inventario_quimicos_bitacora.cantidad_quimico

				            FROM

				                bw_inventario_quimicos_bitacora

				            WHERE

				                bw_inventario_quimicos_bitacora.cod_inventario = inventario_quimico.cod_inventario

				                    AND bw_inventario_quimicos_bitacora.date_update < DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                                '%m-%d-%Y %H:%i:%s'),

				                        '%Y-%m-%d %H:%i:%s')

				            ORDER BY bw_inventario_quimicos_bitacora.date_update DESC

				            LIMIT 1),

				        (SELECT

				                bw_inventario_quimicos_bitacora.cantidad_quimico

				            FROM

				                bw_inventario_quimicos_bitacora

				            WHERE

				                bw_inventario_quimicos_bitacora.cod_inventario = inventario_quimico.cod_inventario

				                    AND bw_inventario_quimicos_bitacora.date_update BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                                '%m-%d-%Y %H:%i:%s'),

				                        '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                                '%m-%d-%Y %H:%i:%s'),

				                        '%Y-%m-%d %H:%i:%s')

				            ORDER BY bw_inventario_quimicos_bitacora.date_update ASC

				            LIMIT 1)) AS cantidad_quimico_bitacora,

				    (SELECT

				            bw_inventario_quimicos_bitacora.cantidad_fisica_quimico

				        FROM

				            bw_inventario_quimicos_bitacora

				        WHERE

				            bw_inventario_quimicos_bitacora.cod_inventario = inventario_quimico.cod_inventario

				                AND bw_inventario_quimicos_bitacora.date_update BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s')

				        ORDER BY bw_inventario_quimicos_bitacora.date_update DESC

				        LIMIT 1) AS cantidad_fisica_quimico_bitacora,

				    (SELECT

				            IFNULL(SUM(bw_inventario_quimicos_sumar_restar.cantidad_sumar),

				                        0)

				        FROM

				            bw_inventario_quimicos_sumar_restar

				        WHERE

				            bw_inventario_quimicos_sumar_restar.cod_inventario = inventario_quimico.cod_inventario

				                AND bw_inventario_quimicos_sumar_restar.fecha BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s')) AS nueva_cantidad_sumar,

				    (SELECT

				            IFNULL(SUM(bw_inventario_quimicos_sumar_restar.cantidad_restar),

				                        0)

				        FROM

				            bw_inventario_quimicos_sumar_restar

				        WHERE

				            bw_inventario_quimicos_sumar_restar.cod_inventario = inventario_quimico.cod_inventario

				                AND bw_inventario_quimicos_sumar_restar.fecha BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s')) AS nueva_cantidad_restar,

					IFNULL(SUM(bw_plantaciones_rociado.cantidad_quimico) * ifnull(conversion_rociado.conversion,0),0) as cantidad_quimico_rociado

				FROM

				    bw_inventario_quimicos inventario_quimico

				        LEFT JOIN

				    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = inventario_quimico.cod_unidad_medida)

				        INNER JOIN

				    bw_tipo_quimico ON (bw_tipo_quimico.cod_tipo_quimico = inventario_quimico.cod_tipo_quimico)

				        LEFT JOIN

				    bw_movimientos_inventario movimientos_envia ON (movimientos_envia.cod_inventario = inventario_quimico.cod_inventario

				        AND movimientos_envia.fecha_envia BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                    '%m-%d-%Y %H:%i:%s'),

				            '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                    '%m-%d-%Y %H:%i:%s'),

				            '%Y-%m-%d %H:%i:%s')

				        AND movimientos_envia.cod_tipo_inventario = 2

                        AND movimientos_envia.cod_info_empresa_envia = inventario_quimico.cod_info_empresa

				        AND movimientos_envia.activo = 1)

				        LEFT JOIN

				    bw_movimientos_inventario movimientos_recibe ON (movimientos_recibe.cod_inventario = inventario_quimico.cod_inventario

				        AND movimientos_recibe.fecha_recibe BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                    '%m-%d-%Y %H:%i:%s'),

				            '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                    '%m-%d-%Y %H:%i:%s'),

				            '%Y-%m-%d %H:%i:%s')

				        AND movimientos_recibe.cod_tipo_inventario = 2

                        AND movimientos_recibe.cod_info_empresa_recibe = inventario_quimico.cod_info_empresa

				        AND movimientos_recibe.activo = 1)

				        LEFT JOIN

				    bw_conversiones_unidades_medida conversion_movimientos_envia ON (conversion_movimientos_envia.cod_unidad_medida_origen = movimientos_envia.cod_unidad_medida

				        AND conversion_movimientos_envia.cod_unidad_medida_destino = inventario_quimico.cod_unidad_medida)

				        LEFT JOIN

				    bw_conversiones_unidades_medida conversion_movimientos_recibe ON (conversion_movimientos_recibe.cod_unidad_medida_origen = movimientos_recibe.cod_unidad_medida

				        AND conversion_movimientos_recibe.cod_unidad_medida_destino = inventario_quimico.cod_unidad_medida)

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = inventario_quimico.cod_info_empresa)

				        LEFT JOIN

				    bw_inventario_quimicos_bitacora inventario_suma ON (inventario_suma.cod_inventario = inventario_quimico.cod_inventario

				        AND inventario_suma.date_update BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                    '%m-%d-%Y %H:%i:%s'),

				            '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                    '%m-%d-%Y %H:%i:%s'),

				            '%Y-%m-%d %H:%i:%s')

				        AND inventario_suma.sumar_restar = 1)

				        LEFT JOIN

				    bw_inventario_quimicos_bitacora inventario_resta ON (inventario_resta.cod_inventario = inventario_quimico.cod_inventario

				        AND inventario_resta.date_update BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                    '%m-%d-%Y %H:%i:%s'),

				            '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                    '%m-%d-%Y %H:%i:%s'),

				            '%Y-%m-%d %H:%i:%s')

				        AND inventario_resta.sumar_restar = 0)

				        LEFT JOIN

				    bw_inventario_quimicos_bitacora ON (bw_inventario_quimicos_bitacora.cod_inventario = inventario_quimico.cod_inventario

				        AND bw_inventario_quimicos_bitacora.fecha_sumar_restar BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                    '%m-%d-%Y %H:%i:%s'),

				            '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                    '%m-%d-%Y %H:%i:%s'),

				            '%Y-%m-%d %H:%i:%s'))

				        LEFT JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_inventario_quimicos_bitacora.user_update)

						LEFT JOIN

					bw_plantaciones_rociado ON (bw_plantaciones_rociado.cod_inventario_quimico = inventario_quimico.cod_inventario

                    AND bw_plantaciones_rociado.fecha_rociado BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                    '%m-%d-%Y %H:%i:%s'),

				            '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                    '%m-%d-%Y %H:%i:%s'),

				            '%Y-%m-%d %H:%i:%s'))

						LEFT JOIN

					bw_conversiones_unidades_medida conversion_rociado ON (conversion_rociado.cod_unidad_medida_origen = bw_plantaciones_rociado.cod_unidad_medida

                    AND conversion_rociado.cod_unidad_medida_destino = inventario_quimico.cod_unidad_medida)

				WHERE

				    inventario_quimico.activo = 1

					AND inventario_quimico.activo = 1 AND bw_info_empresa.cod_info_empresa IN (".$cod_info_empresa.")

				GROUP BY inventario_quimico.cod_inventario;";

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

	 * Permite obtener listado de inventario semilla con sus movimientos y montos actuales.

	 */

	function rep_reporte_inventario_semilla($fecha_inicial,$fecha_final,$cod_info_empresa){

        $fecha_inicial .= ' 00:00:00';

        $fecha_final .= ' 23:59:59';

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));SET SQL_BIG_SELECTS=1;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

        $SQL = "SELECT DISTINCT

				    inventario_semilla.cod_inventario,

				    inventario_semilla.cod_info_empresa,

				    bw_info_empresa.nombre_empresa,

				    inventario_semilla.codigo_semilla,

				    bw_variedad_sembradora.variedad_producto as variedad_semilla,

				    inventario_semilla.nombre_semilla,

				    inventario_semilla.cod_unidad_medida,

				    ug_unidades_medida.unidad_medida,

                    movimientos_recibe.cod_unidad_medida as cod_unidad_medida_recibe,

                    movimientos_envia.cod_unidad_medida as cod_unidad_medida_envia,

				    inventario_semilla.cantidad_semilla,

				    inventario_semilla.cantidad_fisica_semilla,

				    IFNULL((SELECT SUM(movimientos_envia.cantidad_enviada) * conversion_movimientos_envia.conversion),

				            0) AS cantidad_enviada,

				    IFNULL((SELECT SUM(movimientos_recibe.cantidad_recibida) * conversion_movimientos_recibe.conversion),

				            0) AS cantidad_recibida,

				    IFNULL(conversion_movimientos_envia.conversion, 0) AS conversion_movimientos_envia,

				    IFNULL(conversion_movimientos_recibe.conversion, 0) AS conversion_movimientos_recibe,

                    conversion_movimientos_recibe.cod_unidad_medida_destino,

                    conversion_movimientos_recibe.cod_unidad_medida_origen,

				    IF(inventario_suma.fecha_sumar_restar BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s'), IFNULL(SUM(inventario_suma.cantidad_sumar_restar),

				            0),0) AS cantidad_sumar_bitacora,

				    IF(inventario_resta.fecha_sumar_restar BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s'), IFNULL(SUM(inventario_resta.cantidad_sumar_restar),

				            0),0) AS cantidad_restar_bitacora,

				    IF(inventario_semilla.fecha_sumar_restar BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s'), IF(inventario_semilla.sumar_restar = 1,

				        IFNULL(SUM(inventario_semilla.cantidad_sumar_restar),

				                0),

				        0),0) AS cantidad_sumar,

				    IF(inventario_semilla.fecha_sumar_restar BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s'),IF(inventario_semilla.sumar_restar = 0,

				        IFNULL(SUM(inventario_semilla.cantidad_sumar_restar),

				                0),

				        0),0) AS cantidad_restar,

				    IFNULL((SELECT

				                    SUM(bw_detalle_ordenes_compra.cantidad) * conversion_orden_compra.conversion

				                FROM

				                    bw_detalle_productos_proveedores

				                        INNER JOIN

				                    bw_detalle_ordenes_compra ON (bw_detalle_productos_proveedores.cod_detalle = bw_detalle_ordenes_compra.cod_detalle_producto)

				                        INNER JOIN

				                    bw_conversiones_unidades_medida conversion_orden_compra ON (conversion_orden_compra.cod_unidad_medida_origen = bw_detalle_ordenes_compra.cod_unidad_medida

				                        AND conversion_orden_compra.cod_unidad_medida_destino = bw_detalle_ordenes_compra.cod_unidad_medida)

				                        INNER JOIN

				                    bw_ordenes_compra ON (bw_ordenes_compra.cod_orden = bw_detalle_ordenes_compra.cod_orden)

				                WHERE

				                    bw_detalle_productos_proveedores.cod_inventario = inventario_semilla.cod_inventario

				                        AND bw_ordenes_compra.fecha_recibido_pedido BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                                    '%m-%d-%Y %H:%i:%s'),

				                            '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                                    '%m-%d-%Y %H:%i:%s'),

				                            '%Y-%m-%d %H:%i:%s')

				                        AND bw_detalle_productos_proveedores.flag_tipo_inventario = 2),

				            0) AS cantidad_orden_compra,

				    (SELECT

				            GROUP_CONCAT(CONCAT(usu_usuarios.nombre_1,

				                            ' ',

				                            usu_usuarios.apellido_1)

				                    SEPARATOR '<br>')

				        FROM

				            usu_usuarios

				                INNER JOIN

				            bw_inventario_semilla_bitacora ON (bw_inventario_semilla_bitacora.user_update = usu_usuarios.cod_usuario)

				        WHERE

				            bw_inventario_semilla_bitacora.cod_inventario = inventario_semilla.cod_inventario

				                AND bw_inventario_semilla_bitacora.date_update BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s')

				                AND bw_inventario_semilla_bitacora.cantidad_sumar_restar > 0

                                AND usu_usuarios.activo = 1) AS nombre_usuario,

				    (SELECT

				            GROUP_CONCAT(bw_inventario_semilla_bitacora.razon_sumar_restar

				                    SEPARATOR '<br>')

				        FROM

				            bw_inventario_semilla_bitacora

				        WHERE

				            bw_inventario_semilla_bitacora.cod_inventario = inventario_semilla.cod_inventario

				                AND bw_inventario_semilla_bitacora.fecha_sumar_restar BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s')

				                AND bw_inventario_semilla_bitacora.cantidad_sumar_restar > 0) AS razon_sumar_restar_bitacora,

				    IF(inventario_semilla.fecha_sumar_restar BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                        '%m-%d-%Y %H:%i:%s'),

				                '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                        '%m-%d-%Y %H:%i:%s'),

				                '%Y-%m-%d %H:%i:%s'),

				        inventario_semilla.razon_sumar_restar,

				        NULL) AS razon_sumar_restar,

				    (SELECT

				            GROUP_CONCAT(DATE_FORMAT(STR_TO_DATE(bw_inventario_semilla_bitacora.fecha_sumar_restar,

				                                    '%Y-%m-%d %H:%i:%s'),

				                            '%m-%d-%Y %H:%i:%s')

				                    SEPARATOR '<br>')

				        FROM

				            bw_inventario_semilla_bitacora

				        WHERE

				            bw_inventario_semilla_bitacora.cod_inventario = inventario_semilla.cod_inventario

				                AND bw_inventario_semilla_bitacora.fecha_sumar_restar BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s')

				                AND bw_inventario_semilla_bitacora.cantidad_sumar_restar > 0) AS fecha_sumar_restar_bitacora,

				    IF(inventario_semilla.fecha_sumar_restar BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                        '%m-%d-%Y %H:%i:%s'),

				                '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                        '%m-%d-%Y %H:%i:%s'),

				                '%Y-%m-%d %H:%i:%s'),

				        DATE_FORMAT(STR_TO_DATE(inventario_semilla.fecha_sumar_restar,

				                        '%Y-%m-%d %H:%i:%s'),

				                '%m-%d-%Y %H:%i:%s'),

				        NULL) AS fecha_sumar_restar,

				    (SELECT

				            GROUP_CONCAT(DATE_FORMAT(STR_TO_DATE(bw_inventario_semilla_bitacora.date_update,

				                                    '%Y-%m-%d %H:%i:%s'),

				                            '%m-%d-%Y %H:%i:%s')

				                    SEPARATOR '<br>')

				        FROM

				            bw_inventario_semilla_bitacora

				        WHERE

				            bw_inventario_semilla_bitacora.cod_inventario = inventario_semilla.cod_inventario

				                AND bw_inventario_semilla_bitacora.date_update BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s')

				                AND bw_inventario_semilla_bitacora.cantidad_sumar_restar > 0) AS date_update,

				    IF((SELECT

				                bw_inventario_semilla_bitacora.cantidad_semilla

				            FROM

				                bw_inventario_semilla_bitacora

				            WHERE

				                bw_inventario_semilla_bitacora.cod_inventario = inventario_semilla.cod_inventario

				                    AND bw_inventario_semilla_bitacora.date_update BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                                '%m-%d-%Y %H:%i:%s'),

				                        '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                                '%m-%d-%Y %H:%i:%s'),

				                        '%Y-%m-%d %H:%i:%s')

				            ORDER BY bw_inventario_semilla_bitacora.date_update ASC

				            LIMIT 1) IS NULL,

				        (SELECT

				                bw_inventario_semilla_bitacora.cantidad_semilla

				            FROM

				                bw_inventario_semilla_bitacora

				            WHERE

				                bw_inventario_semilla_bitacora.cod_inventario = inventario_semilla.cod_inventario

				                    AND bw_inventario_semilla_bitacora.date_update < DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                                '%m-%d-%Y %H:%i:%s'),

				                        '%Y-%m-%d %H:%i:%s')

				            ORDER BY bw_inventario_semilla_bitacora.date_update DESC

				            LIMIT 1),

				        (SELECT

				                bw_inventario_semilla_bitacora.cantidad_semilla

				            FROM

				                bw_inventario_semilla_bitacora

				            WHERE

				                bw_inventario_semilla_bitacora.cod_inventario = inventario_semilla.cod_inventario

				                    AND bw_inventario_semilla_bitacora.date_update BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                                '%m-%d-%Y %H:%i:%s'),

				                        '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                                '%m-%d-%Y %H:%i:%s'),

				                        '%Y-%m-%d %H:%i:%s')

				            ORDER BY bw_inventario_semilla_bitacora.date_update ASC

				            LIMIT 1)) AS cantidad_semilla_bitacora,

				    (SELECT

				            bw_inventario_semilla_bitacora.cantidad_fisica_semilla

				        FROM

				            bw_inventario_semilla_bitacora

				        WHERE

				            bw_inventario_semilla_bitacora.cod_inventario = inventario_semilla.cod_inventario

				                AND bw_inventario_semilla_bitacora.date_update BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s')

				        ORDER BY bw_inventario_semilla_bitacora.date_update DESC

				        LIMIT 1) AS cantidad_fisica_semilla_bitacora,

				    (SELECT

				            IFNULL(SUM(bw_inventario_semilla_sumar_restar.cantidad_sumar),

				                        0)

				        FROM

				            bw_inventario_semilla_sumar_restar

				        WHERE

				            bw_inventario_semilla_sumar_restar.cod_inventario = inventario_semilla.cod_inventario

				                AND bw_inventario_semilla_sumar_restar.fecha BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s')) AS nueva_cantidad_sumar,

				    (SELECT

				            IFNULL(SUM(bw_inventario_semilla_sumar_restar.cantidad_restar),

				                        0)

				        FROM

				            bw_inventario_semilla_sumar_restar

				        WHERE

				            bw_inventario_semilla_sumar_restar.cod_inventario = inventario_semilla.cod_inventario

				                AND bw_inventario_semilla_sumar_restar.fecha BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s')) AS nueva_cantidad_restar,

				    (SELECT IFNULL(SUM(bw_plantaciones_semillas.cantidad_usada),0)

				    	FROM bw_plantaciones_semillas

				    	WHERE

				            bw_plantaciones_semillas.cod_inventario_semilla = inventario_semilla.cod_inventario

				                AND bw_plantaciones_semillas.date_insert BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                            '%m-%d-%Y %H:%i:%s'),

				                    '%Y-%m-%d %H:%i:%s')) as cantidad_plantada

				FROM

				    bw_inventario_semilla inventario_semilla

				        LEFT JOIN

				    ug_unidades_medida ON (ug_unidades_medida.cod_unidad_medida = inventario_semilla.cod_unidad_medida)

				        INNER JOIN

				    bw_variedad_sembradora ON (bw_variedad_sembradora.cod_variedad = inventario_semilla.cod_variedad)

				        LEFT JOIN

				    bw_movimientos_inventario movimientos_envia ON (movimientos_envia.cod_inventario = inventario_semilla.cod_inventario

				        AND movimientos_envia.fecha_envia BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                    '%m-%d-%Y %H:%i:%s'),

				            '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                    '%m-%d-%Y %H:%i:%s'),

				            '%Y-%m-%d %H:%i:%s')

				        AND movimientos_envia.cod_tipo_inventario = 1

                        AND movimientos_envia.cod_info_empresa_envia = inventario_semilla.cod_info_empresa

				        AND movimientos_envia.activo = 1)

				        LEFT JOIN

				    bw_movimientos_inventario movimientos_recibe ON (movimientos_recibe.cod_inventario = inventario_semilla.cod_inventario

				        AND movimientos_recibe.fecha_recibe BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                    '%m-%d-%Y %H:%i:%s'),

				            '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                    '%m-%d-%Y %H:%i:%s'),

				            '%Y-%m-%d %H:%i:%s')

				        AND movimientos_recibe.cod_tipo_inventario = 1

                        AND movimientos_recibe.cod_info_empresa_recibe = inventario_semilla.cod_info_empresa

				        AND movimientos_recibe.activo = 1)

				        LEFT JOIN

				    bw_conversiones_unidades_medida conversion_movimientos_envia ON (conversion_movimientos_envia.cod_unidad_medida_origen = movimientos_envia.cod_unidad_medida

				        AND conversion_movimientos_envia.cod_unidad_medida_destino = inventario_semilla.cod_unidad_medida)

				        LEFT JOIN

				    bw_conversiones_unidades_medida conversion_movimientos_recibe ON (conversion_movimientos_recibe.cod_unidad_medida_origen = movimientos_recibe.cod_unidad_medida

				        AND conversion_movimientos_recibe.cod_unidad_medida_destino = inventario_semilla.cod_unidad_medida)

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = inventario_semilla.cod_info_empresa)

				        LEFT JOIN

				    bw_inventario_semilla_bitacora inventario_suma ON (inventario_suma.cod_inventario = inventario_semilla.cod_inventario

				        AND inventario_suma.date_update BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                    '%m-%d-%Y %H:%i:%s'),

				            '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                    '%m-%d-%Y %H:%i:%s'),

				            '%Y-%m-%d %H:%i:%s')

				        AND inventario_suma.sumar_restar = 1)

				        LEFT JOIN

				    bw_inventario_semilla_bitacora inventario_resta ON (inventario_resta.cod_inventario = inventario_semilla.cod_inventario

				        AND inventario_resta.date_update BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                    '%m-%d-%Y %H:%i:%s'),

				            '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                    '%m-%d-%Y %H:%i:%s'),

				            '%Y-%m-%d %H:%i:%s')

				        AND inventario_resta.sumar_restar = 0)

				        LEFT JOIN

				    bw_inventario_semilla_bitacora ON (bw_inventario_semilla_bitacora.cod_inventario = inventario_semilla.cod_inventario

				        AND bw_inventario_semilla_bitacora.fecha_sumar_restar BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial,

				                    '%m-%d-%Y %H:%i:%s'),

				            '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(STR_TO_DATE(:fecha_final,

				                    '%m-%d-%Y %H:%i:%s'),

				            '%Y-%m-%d %H:%i:%s'))

				        LEFT JOIN

				    usu_usuarios ON (usu_usuarios.cod_usuario = bw_inventario_semilla_bitacora.user_update)

				WHERE

				    inventario_semilla.activo = 1

					AND inventario_semilla.activo = 1 AND bw_info_empresa.cod_info_empresa IN (".$cod_info_empresa.")

				GROUP BY inventario_semilla.cod_inventario;";

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

	 * Permite obtener listado de aplicaciones químicas asignadas o realizadas por usuario(s).

	 */

	function rep_reporte_excel_aplicacion_quimica($cod_usuario,$fecha_inicial,$fecha_final,$cod_info_empresa){

        $fecha_inicial .= ' 00:00:00';

        $fecha_final .= ' 23:59:59';

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));SET SQL_BIG_SELECTS=1;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

        $SQL = "SELECT DISTINCT

				    aplicar_quimicos.cod_aplicacion,

				    bw_plantaciones_detalle_aplicar_quimicos.cod_detalle,

				    bw_plantaciones.cod_plantacion,

				    CONCAT(usuario_supervisor.nombre_1,

				            ' ',

				            usuario_supervisor.apellido_1) AS nombre_supervisor,

				    CONCAT(usuario_operador.nombre_1,

				            ' ',

				            usuario_operador.apellido_1) AS nombre_operador,

				    bw_plantaciones.anio_plantacion,

				    bw_plantaciones.num_plantacion,

				    bw_temporadas.codigo_temporada,

				    bw_info_empresa.nombre_empresa,

				    GROUP_CONCAT(DISTINCT bw_zonas.zona

				        SEPARATOR '<br>') AS zonas,

				    CONCAT(IF(bw_bloques.clave_bloque != '',

				                CONCAT(bw_bloques.clave_bloque, '-'),

				                ''),

				            MIN(bw_bloques.nombre_bloque)) AS min_bloque,

				    CONCAT(IF(bw_bloques.clave_bloque != '',

				                CONCAT(bw_bloques.clave_bloque, '-'),

				                ''),

				            MAX(bw_bloques.nombre_bloque)) AS max_bloque,

				    GROUP_CONCAT(DISTINCT CONCAT(IF(bw_bloques.clave_bloque != '',

				                    CONCAT(bw_bloques.clave_bloque, '-'),

				                    ''),

				                bw_bloques.nombre_bloque)

				        SEPARATOR '<br>') AS bloques,

				    GROUP_CONCAT(DISTINCT bw_bloques.cod_bloque

				        SEPARATOR ',') AS bloques_coma,

				    aplicar_quimicos.cod_bloques_aplicacion,

				    (SELECT

				            SUM(bloque.num_acres)

				        FROM

				            bw_bloques bloque

				        WHERE

				            FIND_IN_SET(bloque.cod_bloque,

				                    GROUP_CONCAT(DISTINCT bw_bloques.cod_bloque

				                        SEPARATOR ','))) AS acres,

				    bw_inventario_semilla.nombre_semilla,

				    DATE_FORMAT(STR_TO_DATE(aplicar_quimicos.fecha_aplicacion_supervisor,'%Y-%m-%d'),'%m-%d-%Y') as fecha_aplicacion_supervisor,

				    DATE_FORMAT(STR_TO_DATE(aplicar_quimicos.fecha_aplicacion_operador,'%Y-%m-%d'),'%m-%d-%Y') as fecha_aplicacion_operador,

				    aplicar_quimicos.hora_inicial,

				    aplicar_quimicos.hora_final,

				    bw_inventario_quimicos.cod_quimico AS codigo_productos,

				    GROUP_CONCAT(DISTINCT bw_inventario_quimicos.nombre_quimico

				        SEPARATOR '<br>') AS quimicos,

				    GROUP_CONCAT(DISTINCT bw_inventario_quimicos.registro_ambiental

				        SEPARATOR '<br>') AS registro_ambiental_epa,

				    GROUP_CONCAT(DISTINCT bw_ingredientes_activos.ingrediente_activo

				        SEPARATOR '<br>') AS ingredientes_activos,

				    GROUP_CONCAT(DISTINCT bw_tipo_quimico.tipo_quimico

				        SEPARATOR '<br>') AS tipo_quimicos,

				    GROUP_CONCAT(DISTINCT bw_tipo_aplicacion_maquinaria.tipo_aplicacion

				        SEPARATOR '<br>') AS tipo_aplicacion_maquinaria,

				    GROUP_CONCAT(DISTINCT bw_inventario_maquinaria.nombre_maquinaria

				        SEPARATOR '<br>') AS nombre_maquinaria,

				    GROUP_CONCAT(DISTINCT CONCAT(bw_inventario_quimicos.dosis_minima,

				                ' - ',

				                bw_inventario_quimicos.dosis_maxima)

				        SEPARATOR '<br>') AS application_rate,

				    GROUP_CONCAT(DISTINCT unidad_medida_rate.unidad_medida

				        SEPARATOR '<br>') AS application_rate_unidad_medida,

				    GROUP_CONCAT(DISTINCT bw_plantaciones_detalle_aplicar_quimicos.cantidad_aplicada

				        SEPARATOR '<br>') AS cantidad_aplicada,

				    GROUP_CONCAT(DISTINCT unidad_medida_aplicada.unidad_medida

				        SEPARATOR '<br>') AS cantidad_aplicada_unidad_medida,

				    GROUP_CONCAT(DISTINCT bw_inventario_quimicos.razon_aplicacion

				        SEPARATOR '<br>') AS razon_aplicacion,

				    GROUP_CONCAT(DISTINCT bw_inventario_quimicos.periodo_reingreso

				        SEPARATOR '<br>') AS periodo_reingreso,

				    GROUP_CONCAT(DISTINCT periodo_reingreso.tipo_periodo

				        SEPARATOR '<br>') AS tipo_periodo_reingreso,

				    GROUP_CONCAT(DISTINCT bw_inventario_quimicos.periodo_precosecha

				        SEPARATOR '<br>') AS periodo_precosecha,

				    GROUP_CONCAT(DISTINCT periodo_precosecha.tipo_periodo

				        SEPARATOR '<br>') AS tipo_periodo_precosecha,

				    aplicar_quimicos.viento,

				    aplicar_quimicos.temperatura,

				    DATE_FORMAT(STR_TO_DATE(aplicar_quimicos.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    IF(COUNT(bw_plantaciones_trasplantes.cod_trasplante) = 0,

				        IFNULL(bw_inventario_semilla.flag_watercress, 0),

				        1) AS flag_watercress,

				    GROUP_CONCAT(DISTINCT DATE_FORMAT(STR_TO_DATE(bw_formulario_harvesting_worksheet.harvest_date,'%Y-%m-%d'),'%m-%d-%Y')

				        SEPARATOR '<br>') AS harvest_date

				FROM

				    bw_plantaciones_aplicar_quimicos aplicar_quimicos

				        INNER JOIN

				    bw_plantaciones_detalle_aplicar_quimicos ON (bw_plantaciones_detalle_aplicar_quimicos.cod_aplicacion = aplicar_quimicos.cod_aplicacion)

				        LEFT JOIN

				    bw_formulario_harvesting_worksheet ON (bw_formulario_harvesting_worksheet.cod_plantacion = aplicar_quimicos.cod_plantacion)

				        INNER JOIN

				    bw_plantaciones ON (bw_plantaciones.cod_plantacion = aplicar_quimicos.cod_plantacion)

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_plantaciones.cod_info_empresa)

				        INNER JOIN

				    bw_temporadas ON (bw_temporadas.cod_temporada = bw_plantaciones.cod_temporada)

				        INNER JOIN

				    bw_detalle_bloques_plantaciones ON (bw_detalle_bloques_plantaciones.cod_plantacion = bw_plantaciones.cod_plantacion)

				        INNER JOIN

				    bw_bloques ON (FIND_IN_SET(bw_bloques.cod_bloque,

				            aplicar_quimicos.cod_bloques_aplicacion))

				        INNER JOIN

				    bw_zonas ON (bw_zonas.cod_zona = bw_bloques.cod_zona)

				        LEFT JOIN

				    bw_inventario_quimicos ON (bw_inventario_quimicos.cod_inventario = bw_plantaciones_detalle_aplicar_quimicos.cod_inventario)

				        LEFT JOIN

				    bw_tipo_quimico ON (bw_tipo_quimico.cod_tipo_quimico = bw_plantaciones_detalle_aplicar_quimicos.cod_tipo_quimico)

				        LEFT JOIN

				    ug_unidades_medida unidad_medida_rate ON (unidad_medida_rate.cod_unidad_medida = bw_inventario_quimicos.cod_unidad_medida)

				        LEFT JOIN

				    ug_unidades_medida unidad_medida_aplicada ON (unidad_medida_aplicada.cod_unidad_medida = bw_plantaciones_detalle_aplicar_quimicos.cod_unidad_medida)

				        LEFT JOIN

				    bw_tipos_periodos periodo_reingreso ON (periodo_reingreso.cod_tipo_periodo = bw_inventario_quimicos.cod_tipo_periodo_reingreso)

				        LEFT JOIN

				    bw_tipos_periodos periodo_precosecha ON (periodo_precosecha.cod_tipo_periodo = bw_inventario_quimicos.cod_tipo_periodo_precosecha)

				        LEFT JOIN

				    usu_usuarios usuario_supervisor ON (usuario_supervisor.cod_usuario = aplicar_quimicos.user_insert)

				        LEFT JOIN

				    usu_usuarios usuario_operador ON (usuario_operador.cod_usuario = aplicar_quimicos.cod_operador)

				        LEFT JOIN

				    bw_plantaciones_semillas_bloques ON (bw_plantaciones_semillas_bloques.cod_bloque = bw_bloques.cod_bloque)

				        LEFT JOIN

				    bw_plantaciones_semillas ON (bw_plantaciones_semillas.cod_plantacion = bw_plantaciones.cod_plantacion

				        AND bw_plantaciones_semillas.cod_detalle = bw_plantaciones_semillas_bloques.cod_detalle)

				        LEFT JOIN

				    bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_plantaciones_semillas.cod_inventario_semilla)

				        LEFT JOIN

				    bw_ingredientes_activos ON (bw_ingredientes_activos.cod_ingrediente_activo = bw_inventario_quimicos.cod_ingrediente_activo)

				        LEFT JOIN

				    bw_inventario_maquinaria ON (bw_inventario_maquinaria.cod_inventario = aplicar_quimicos.cod_inventario_maquinaria)

				        LEFT JOIN

				    bw_tipo_aplicacion_maquinaria ON (bw_tipo_aplicacion_maquinaria.cod_tipo_aplicacion = aplicar_quimicos.cod_tipo_aplicacion)

				        LEFT JOIN

				    bw_plantaciones_trasplantes ON (bw_plantaciones_trasplantes.cod_plantacion_recibe = bw_plantaciones.cod_plantacion)

				WHERE

				    (aplicar_quimicos.user_insert IN (".$cod_usuario.")

				        OR aplicar_quimicos.cod_operador IN (".$cod_usuario."))

				        AND aplicar_quimicos.activo = 1

				        AND bw_plantaciones_detalle_aplicar_quimicos.activo = 1

				        AND (aplicar_quimicos.fecha_aplicacion_supervisor BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial, '%m-%d-%Y'),

				            '%Y-%m-%d') AND DATE_FORMAT(STR_TO_DATE(:fecha_final, '%m-%d-%Y'),

				            '%Y-%m-%d')

				        OR aplicar_quimicos.fecha_aplicacion_operador BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial, '%m-%d-%Y'),

				            '%Y-%m-%d') AND DATE_FORMAT(STR_TO_DATE(:fecha_final, '%m-%d-%Y'),

				            '%Y-%m-%d'))

						AND bw_info_empresa.cod_info_empresa IN (".$cod_info_empresa.")

				GROUP BY aplicar_quimicos.cod_aplicacion , bw_plantaciones_detalle_aplicar_quimicos.cod_inventario , bw_plantaciones_trasplantes.cod_trasplante

				ORDER BY aplicar_quimicos.cod_aplicacion ASC

				LIMIT 500;";

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

	 * Permite obtener listado de aplicaciones químicas asignadas o realizadas por fincas y químicos.

	 */

	function rep_nuevo_reporte_excel_aplicacion_quimica($cod_info_empresa,$fecha_inicial,$fecha_final,$cod_inventario_quimico){

        $fecha_inicial .= ' 00:00:00';

        $fecha_final .= ' 23:59:59';

		$SQL = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));SET SQL_BIG_SELECTS=1;";

		$stmt = $this->db_conexion->prepare($SQL);

		$stmt->execute();

		if($cod_info_empresa != '' && $cod_info_empresa != null && $cod_info_empresa != 'null')

		{

			$sql_empresas = " AND bw_info_empresa.cod_info_empresa IN (".$cod_info_empresa.") ";

		}

		else

		{

			$sql_empresas = "";

		}

		if($cod_inventario_quimico != '' && $cod_inventario_quimico != null && $cod_inventario_quimico != 'null')

		{

			$sql_quimicos = " AND bw_inventario_quimicos.cod_inventario IN (".$cod_inventario_quimico.") ";

		}

		else

		{

			$sql_quimicos = "";

		}

        $SQL = "SELECT DISTINCT

				    aplicar_quimicos.cod_aplicacion,

				    bw_plantaciones_detalle_aplicar_quimicos.cod_detalle,

				    bw_plantaciones.cod_plantacion,

				    CONCAT(usuario_supervisor.nombre_1,

				            ' ',

				            usuario_supervisor.apellido_1) AS nombre_supervisor,

				    CONCAT(usuario_operador.nombre_1,

				            ' ',

				            usuario_operador.apellido_1) AS nombre_operador,

				    bw_plantaciones.anio_plantacion,

				    bw_plantaciones.num_plantacion,

				    bw_temporadas.codigo_temporada,

				    bw_info_empresa.nombre_empresa,

				    GROUP_CONCAT(DISTINCT bw_zonas.zona

				        SEPARATOR '<br>') AS zonas,

				    CONCAT(IF(bw_bloques.clave_bloque != '',

				                CONCAT(bw_bloques.clave_bloque, '-'),

				                ''),

				            MIN(bw_bloques.nombre_bloque)) AS min_bloque,

				    CONCAT(IF(bw_bloques.clave_bloque != '',

				                CONCAT(bw_bloques.clave_bloque, '-'),

				                ''),

				            MAX(bw_bloques.nombre_bloque)) AS max_bloque,

				    GROUP_CONCAT(DISTINCT CONCAT(IF(bw_bloques.clave_bloque != '',

				                    CONCAT(bw_bloques.clave_bloque, '-'),

				                    ''),

				                bw_bloques.nombre_bloque)

				        SEPARATOR '<br>') AS bloques,

				    GROUP_CONCAT(DISTINCT bw_bloques.cod_bloque

				        SEPARATOR ',') AS bloques_coma,

				    aplicar_quimicos.cod_bloques_aplicacion,

				    (SELECT

				            SUM(bloque.num_acres)

				        FROM

				            bw_bloques bloque

				        WHERE

				            FIND_IN_SET(bloque.cod_bloque,

				                    GROUP_CONCAT(DISTINCT bw_bloques.cod_bloque

				                        SEPARATOR ','))) AS acres,

				    bw_inventario_semilla.nombre_semilla,

				    DATE_FORMAT(STR_TO_DATE(aplicar_quimicos.fecha_aplicacion_supervisor,'%Y-%m-%d'),'%m-%d-%Y') as fecha_aplicacion_supervisor,

				    DATE_FORMAT(STR_TO_DATE(aplicar_quimicos.fecha_aplicacion_operador,'%Y-%m-%d'),'%m-%d-%Y') as fecha_aplicacion_operador,

				    aplicar_quimicos.hora_inicial,

				    aplicar_quimicos.hora_final,

				    bw_inventario_quimicos.cod_quimico AS codigo_productos,

				    GROUP_CONCAT(DISTINCT bw_inventario_quimicos.nombre_quimico

				        SEPARATOR '<br>') AS quimicos,

				    GROUP_CONCAT(DISTINCT bw_inventario_quimicos.registro_ambiental

				        SEPARATOR '<br>') AS registro_ambiental_epa,

				    GROUP_CONCAT(DISTINCT bw_ingredientes_activos.ingrediente_activo

				        SEPARATOR '<br>') AS ingredientes_activos,

				    GROUP_CONCAT(DISTINCT bw_tipo_quimico.tipo_quimico

				        SEPARATOR '<br>') AS tipo_quimicos,

				    GROUP_CONCAT(DISTINCT bw_tipo_aplicacion_maquinaria.tipo_aplicacion

				        SEPARATOR '<br>') AS tipo_aplicacion_maquinaria,

				    GROUP_CONCAT(DISTINCT bw_inventario_maquinaria.nombre_maquinaria

				        SEPARATOR '<br>') AS nombre_maquinaria,

				    GROUP_CONCAT(DISTINCT CONCAT(bw_inventario_quimicos.dosis_minima,

				                ' - ',

				                bw_inventario_quimicos.dosis_maxima)

				        SEPARATOR '<br>') AS application_rate,

				    GROUP_CONCAT(DISTINCT unidad_medida_rate.unidad_medida

				        SEPARATOR '<br>') AS application_rate_unidad_medida,

				    GROUP_CONCAT(DISTINCT bw_plantaciones_detalle_aplicar_quimicos.cantidad_aplicada

				        SEPARATOR '<br>') AS cantidad_aplicada,

				    GROUP_CONCAT(DISTINCT unidad_medida_aplicada.unidad_medida

				        SEPARATOR '<br>') AS cantidad_aplicada_unidad_medida,

				    GROUP_CONCAT(DISTINCT bw_inventario_quimicos.razon_aplicacion

				        SEPARATOR '<br>') AS razon_aplicacion,

				    GROUP_CONCAT(DISTINCT bw_inventario_quimicos.periodo_reingreso

				        SEPARATOR '<br>') AS periodo_reingreso,

				    GROUP_CONCAT(DISTINCT periodo_reingreso.tipo_periodo

				        SEPARATOR '<br>') AS tipo_periodo_reingreso,

				    GROUP_CONCAT(DISTINCT bw_inventario_quimicos.periodo_precosecha

				        SEPARATOR '<br>') AS periodo_precosecha,

				    GROUP_CONCAT(DISTINCT periodo_precosecha.tipo_periodo

				        SEPARATOR '<br>') AS tipo_periodo_precosecha,

				    aplicar_quimicos.viento,

				    aplicar_quimicos.temperatura,

				    DATE_FORMAT(STR_TO_DATE(aplicar_quimicos.date_insert,'%Y-%m-%d %H:%i:%s'),'%m-%d-%Y %H:%i:%s') as date_insert,

				    IF(COUNT(bw_plantaciones_trasplantes.cod_trasplante) = 0,

				        IFNULL(bw_inventario_semilla.flag_watercress, 0),

				        1) AS flag_watercress,

				    GROUP_CONCAT(DISTINCT DATE_FORMAT(STR_TO_DATE(bw_formulario_harvesting_worksheet.harvest_date,'%Y-%m-%d'),'%m-%d-%Y')

				        SEPARATOR '<br>') AS harvest_date

				FROM

				    bw_plantaciones_aplicar_quimicos aplicar_quimicos

				        INNER JOIN

				    bw_plantaciones_detalle_aplicar_quimicos ON (bw_plantaciones_detalle_aplicar_quimicos.cod_aplicacion = aplicar_quimicos.cod_aplicacion)

				        LEFT JOIN

				    bw_formulario_harvesting_worksheet ON (bw_formulario_harvesting_worksheet.cod_plantacion = aplicar_quimicos.cod_plantacion)

				        INNER JOIN

				    bw_plantaciones ON (bw_plantaciones.cod_plantacion = aplicar_quimicos.cod_plantacion)

				        INNER JOIN

				    bw_info_empresa ON (bw_info_empresa.cod_info_empresa = bw_plantaciones.cod_info_empresa)

				        INNER JOIN

				    bw_temporadas ON (bw_temporadas.cod_temporada = bw_plantaciones.cod_temporada)

				        INNER JOIN

				    bw_detalle_bloques_plantaciones ON (bw_detalle_bloques_plantaciones.cod_plantacion = bw_plantaciones.cod_plantacion)

				        INNER JOIN

				    bw_bloques ON (FIND_IN_SET(bw_bloques.cod_bloque,

				            aplicar_quimicos.cod_bloques_aplicacion))

				        INNER JOIN

				    bw_zonas ON (bw_zonas.cod_zona = bw_bloques.cod_zona)

				        LEFT JOIN

				    bw_inventario_quimicos ON (bw_inventario_quimicos.cod_inventario = bw_plantaciones_detalle_aplicar_quimicos.cod_inventario)

				        LEFT JOIN

				    bw_tipo_quimico ON (bw_tipo_quimico.cod_tipo_quimico = bw_plantaciones_detalle_aplicar_quimicos.cod_tipo_quimico)

				        LEFT JOIN

				    ug_unidades_medida unidad_medida_rate ON (unidad_medida_rate.cod_unidad_medida = bw_inventario_quimicos.cod_unidad_medida)

				        LEFT JOIN

				    ug_unidades_medida unidad_medida_aplicada ON (unidad_medida_aplicada.cod_unidad_medida = bw_plantaciones_detalle_aplicar_quimicos.cod_unidad_medida)

				        LEFT JOIN

				    bw_tipos_periodos periodo_reingreso ON (periodo_reingreso.cod_tipo_periodo = bw_inventario_quimicos.cod_tipo_periodo_reingreso)

				        LEFT JOIN

				    bw_tipos_periodos periodo_precosecha ON (periodo_precosecha.cod_tipo_periodo = bw_inventario_quimicos.cod_tipo_periodo_precosecha)

				        LEFT JOIN

				    usu_usuarios usuario_supervisor ON (usuario_supervisor.cod_usuario = aplicar_quimicos.user_insert)

				        LEFT JOIN

				    usu_usuarios usuario_operador ON (usuario_operador.cod_usuario = aplicar_quimicos.cod_operador)

				        LEFT JOIN

				    bw_plantaciones_semillas_bloques ON (bw_plantaciones_semillas_bloques.cod_bloque = bw_bloques.cod_bloque)

				        LEFT JOIN

				    bw_plantaciones_semillas ON (bw_plantaciones_semillas.cod_plantacion = bw_plantaciones.cod_plantacion

				        AND bw_plantaciones_semillas.cod_detalle = bw_plantaciones_semillas_bloques.cod_detalle)

				        LEFT JOIN

				    bw_inventario_semilla ON (bw_inventario_semilla.cod_inventario = bw_plantaciones_semillas.cod_inventario_semilla)

				        LEFT JOIN

				    bw_ingredientes_activos ON (bw_ingredientes_activos.cod_ingrediente_activo = bw_inventario_quimicos.cod_ingrediente_activo)

				        LEFT JOIN

				    bw_inventario_maquinaria ON (bw_inventario_maquinaria.cod_inventario = aplicar_quimicos.cod_inventario_maquinaria)

				        LEFT JOIN

				    bw_tipo_aplicacion_maquinaria ON (bw_tipo_aplicacion_maquinaria.cod_tipo_aplicacion = aplicar_quimicos.cod_tipo_aplicacion)

				        LEFT JOIN

				    bw_plantaciones_trasplantes ON (bw_plantaciones_trasplantes.cod_plantacion_recibe = bw_plantaciones.cod_plantacion)

				WHERE

				        aplicar_quimicos.activo = 1

				        AND bw_plantaciones_detalle_aplicar_quimicos.activo = 1

				        AND (aplicar_quimicos.fecha_aplicacion_supervisor BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial, '%m-%d-%Y'),

				            '%Y-%m-%d') AND DATE_FORMAT(STR_TO_DATE(:fecha_final, '%m-%d-%Y'),

				            '%Y-%m-%d')

				        OR aplicar_quimicos.fecha_aplicacion_operador BETWEEN DATE_FORMAT(STR_TO_DATE(:fecha_inicial, '%m-%d-%Y'),

				            '%Y-%m-%d') AND DATE_FORMAT(STR_TO_DATE(:fecha_final, '%m-%d-%Y'),

				            '%Y-%m-%d'))

						".$sql_empresas."

						".$sql_quimicos."

				GROUP BY aplicar_quimicos.cod_aplicacion , bw_plantaciones_detalle_aplicar_quimicos.cod_inventario , bw_plantaciones_trasplantes.cod_trasplante

				ORDER BY aplicar_quimicos.cod_aplicacion ASC

				LIMIT 500;";

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

}
