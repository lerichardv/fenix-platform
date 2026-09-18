<?php
/*
 *  Conexión a la base de datos
 *  @author Dan Urquía
 *  @date   2017-02-22
 */
require_once __DIR__ . '/../funciones/func_env.php';

class db_lion
{
	function dbConnect()
	{
		try {
			$config['db'] = array(
				'host' 	   => env('DB_HOST', '127.0.0.1'),
				'port' 	   => env('DB_PORT', '3307'),
				'username' => env('DB_USERNAME', 'root'),
				'password' => env('DB_PASSWORD', ''),
				'dbname'   => env('DB_DATABASE', 'bayer_payroll')
			);

			//MySQL con PDO_MYSQL
			$dbh = new PDO(
				'mysql:host=' . $config['db']['host'] . ';port=' . $config['db']['port'] . ';dbname=' . $config['db']['dbname'] . '',
				$config['db']['username'],
				$config['db']['password']
			);
			return $dbh;
		} catch (PDOException $e) {
			die('ERROR: ' . $e->getMessage());
		}
	}
}
