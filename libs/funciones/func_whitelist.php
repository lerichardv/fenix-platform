<?PHP
/*
 * Funciones para uso de whitelist.
 * 
 * @author      Dan Urquía
 * @date        2014-04-22     
 */
 
class db_whitelist{
	/* Patrón para verificación de código. */
	function get_codigo_patron(){
		$codigo_patron = "/[^0-9]/";
		return $codigo_patron;
	}
	/* Patrón para verificación de correo electrónico. */
	function get_email_patron(){
		$email_patron = "/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)" .
					    "|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}" .
						"|[0-9]{1,3})(\]?)$/";
		return $email_patron;
	}
	/* Patrón para verificación de cadena de letras, números y caracteres especiales. */
	function get_oracion_patron(){
		$oracion_patron = "/^[a-zA-Z0-9\. _-¡!¿?,áéíóúÁÉÍÓÚÑñ]$/";
		return $oracion_patron;
		
	}
	
	function get_oracion_usuario(){
		$oracion_usuario =  "/^([a-zA-Z0-9_\-\.])((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)" .
					    "|(([a-zA-Z0-9\]\.)+))([a-zA-Z]{2,4}" .
						"|[0-9]{1,3})(\]?)$/";
		return $oracion_usuario;	
	}
	
	
} //Fin de la clase
?>