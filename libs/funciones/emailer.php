<?PHP
/*
	 * Llama a la función que aprobaráa la propuesta
	 * @author 	Linda Zelaya, Kevin Fúnez
	 * @date 	2017-04-19
	 */
// include("../../libs/PHPMailer-master/class.phpmailer.php"); // Versión previa a la actualización
// include("../../libs/PHPMailer-master/class.smtp.php"); // Versión previa a la actualización

require("../../libs/PHPMailer-master/src/PHPMailer.php");
require("../../libs/PHPMailer-master/src/SMTP.php");
require("../../libs/PHPMailer-master/src/Exception.php");
// include("../../libs/PHPMailer-master/PHPMailerAutoload.php");
include_once("../../libs/db_classes/db_email.php");

ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Cargamos el componente que inicializa todas las librerias instaladas con "Composer"
require '../../vendor/autoload.php';
class emailer
{
	public $db_conexion;
	function __construct()
	{
		$this->db_conexion = new db_lion();
		$this->db_conexion = $this->db_conexion->dbConnect();
	}

	function parse_html($subject, array $variables, $escapeChar = '@', $errPlaceholder = null)
	{
		$esc = preg_quote($escapeChar);
		$expr = "/
		        $esc$esc(?=$esc*+{)
		      | $esc{
		      | {(\w+)}
		    /x";

		$callback = function ($match) use ($variables, $escapeChar, $errPlaceholder) {
			switch ($match[0]) {
				case $escapeChar . $escapeChar:
					return $escapeChar;
				case $escapeChar . '{':
					return '{';
				default:
					if (isset($variables[$match[1]])) {
						return $variables[$match[1]];
					}
					return isset($errPlaceholder) ? $errPlaceholder : $match[0];
			}
		};
		return preg_replace_callback($expr, $callback, $subject);
	}

	function enviar_mail_password($user_user, $user_nombre, $user_email, $randomPassword, $cod_modulo, $cod_email)
	{
		/*Ir a base de datos a traer el subject, el mensaje y el sender*/
		$DB_EMAIL 			= new db_email();
		$error_envio_correo = false;
		$result 			= $DB_EMAIL->email_construct($cod_modulo, $cod_email); //codigo de envio de password temporal
		$mensaje 			= utf8_encode($result[0]['mensaje']);
		$sistema = $result[0]['sistema'];
		$correo_consulta = $result[0]['correo_consulta'];
		$cuerpo_correo = $result[0]['cuerpo_correo'];
		$pairs = array(
			'randomPassword' => $randomPassword,
			'user_nombre' => utf8_encode($user_nombre),
			'user_user' => utf8_encode($user_user),
			'correo_consulta' => $correo_consulta,
			'sistema' => $sistema,
		);
		$cuerpo_correo = $this->parse_html($cuerpo_correo, $pairs);

		try {

			$mailNuevo = new PHPMailer(true);
			$mailNuevo->isSendmail();
			$mailNuevo->setFrom('noreply@lmfdata.com', 'LMF');
			$mailNuevo->addReplyTo('noreply@lmfdata.com', 'LMF');
			$mailNuevo->addAddress($user_email, $user_nombre);
			$mailNuevo->Subject = $result[0]['subject'];
			
			$mailNuevo->isHTML(true); // Set email format to HTML
			// $mailNuevo->msgHTML("<b>Cuerpo del mesnaje</b>");
			$mailNuevo->Body    = mb_convert_encoding(utf8_decode($cuerpo_correo), "UTF-8", mb_detect_encoding(utf8_decode($cuerpo_correo), "UTF-8, ISO-8859-1, ISO-8859-15", true));
			// $mailNuevo->Body    = 'Cuerpo de la funcion alternativa <b>¡Negrita!</b>';
			$mailNuevo->AltBody = 'Cuerpo de mensaje alternatio';
			// $mailNuevo->addAttachment('images/phpmailer_mini.png'); //En las pruebas iniciales este archivo no existia
			$mailNuevo->AddEmbeddedImage('../../libs/imgs/Logo.png', 'logoLMF', 'Logo.png');
		
			if (!$mailNuevo->send()) {
				echo 'Mailer tuvo un error. Error capturado: ' . $mailNuevo->ErrorInfo;
			} else {
				echo $mensaje;
			}


			// $mail = new PHPMailer(true);


			// $mail = new PHPMailer(true);

			// $mail->SMTPDebug   = 0;
			// $mail->DKIM_domain = $result[0]['dk_domain'];
			// //Ask for HTML-friendly debug output
			// $mail->Debugoutput = 'html';
			// //Set the hostname of the mail server
			// $mail->Host = $result[0]['host']; // Specify main and backup SMTP servers
			// //Set the SMTP port number - likely to be 25, 465 or 587
			// $mail->Port = $result[0]['port']; //465;
			// //Whether to use SMTP authentication
			// $mail->SMTPAuth    = true;
			// //Username to use for SMTP authentication
			// $mail->Username = $result[0]['username']; // SMTP username
			// //Password to use for SMTP authentication
			// // $mail->Password = $result[0]['password']; // SMTP password
			// $mail->Password = "xwcsdnuemstmdbdl"; // SMTP password Nueva entregada por Google

			// $mail->SMTPSecure  = 'ssl';
			// $mail->CharSet     = 'UTF-8';
			// //Set who the message is to be sent from
			// // $mail->setFrom("$result[0]['fromAddress']", $result[0]['fromName']);
			// $mail->setFrom("edwinolivera33@gmail.com", $result[0]['fromName']);
			// $mail->AddAddress($user_email, $user_nombre);
			// $mail->isHTML(true); // Set email format to HTML
			// $mail->Subject = $result[0]['subject']; //'Proyecto de beca asignado para '.$tipo_evaluacion[0]['titulo'];
			// //$mail->Body    = $cuerpo_correo;
			// $mail->Body    = mb_convert_encoding(utf8_decode($cuerpo_correo), "UTF-8", mb_detect_encoding(utf8_decode($cuerpo_correo), "UTF-8, ISO-8859-1, ISO-8859-15", true));
			// $mail->AltBody = '';
			// $mail->AddEmbeddedImage('../../libs/imgs/Logo.png', 'logoLMF', 'Logo.png');
			// if (!$mail->send()) {
			// 	echo '0|Error al enviar el correo electronico con su acceso temporal. Por favor intentarlo nuevamente.' . $mail->ErrorInfo;
			// } else {
			// 	echo $mensaje;
			// }
		} catch (\Throwable $th) {
			//throw $th;
			// throw new Exception("¡Esto es un error simulado!");
			echo $th;
			die();
		}
	}

	function enviar_mail_password_NUEVA_VERSION()
	{
		try {
			$mail = new PHPMailer(true);
			$mail->isSendmail();
			$mail->setFrom('noreply@lmfdata.com', 'Prueba de Blue');
			$mail->addReplyTo('noreply@lmfdata.com', 'Prueba de Blue');
			$mail->addAddress('edwinolivera33@gmail.com', 'Edwin');
			$mail->Subject = 'Prueba de envio de correo';

			$mail->msgHTML("<b>Cuerpo del mesnaje</b>");
			$mail->Body    = 'Cuerpo de la funcion alternativa <b>¡Negrita!</b>';
			$mail->AltBody = 'Cuerpo de mensaje alternatio';
			// $mail->addAttachment('images/phpmailer_mini.png'); //En las pruebas iniciales este archivo no existia
			if (!$mail->send()) {
				echo 'Mailer tuvo un error. Error capturado: ' . $mail->ErrorInfo;
			} else {
				echo 'Correo enviado';
			}
		} catch (\Throwable $th) {
			throw $th;
		}
	}

	/*
 	 * Envia Correo de Caso asignado
 	 * @author 	Kevin Fúnez
 	 * @date 	2017-04-18
 	 */
	function enviar_mail_caso($nombre, $email, $cod_modulo, $cod_email)
	{
		/*Ir a base de datos a traer el subject, el mensaje y el sender*/
		$DB_EMAIL 			     = new db_email();
		$error_envio_correo = false;
		$result 			       = $DB_EMAIL->email_construct($cod_modulo, $cod_email);
		$mensaje 		       = utf8_encode($result[0]['mensaje']);
		$sistema            = utf8_encode($result[0]['sistema']);
		$correo_consulta    = utf8_encode($result[0]['correo_consulta']);
		$cuerpo_correo      = utf8_encode($result[0]['cuerpo_correo']);
		$pairs = array(
			'nombre' => $nombre,
			'correo_consulta' => $correo_consulta,
			'sistema' => $sistema,
		);
		$cuerpo_correo = $this->parse_html($cuerpo_correo, $pairs);
		$mail = new PHPMailer();
		$mail->CharSet = 'UTF-8';
		$mail->Encoding = 'quoted-printable';
		//$mail->isSMTP();// Set mailer to use SMTP
		$mail->SMTPAuth = true; // Enable SMTP authentication
		$mail->SMTPSecure = 'ssl';
		$mail->Host = $result[0]['host']; //'smtp.gmail.com';// Specify main and backup SMTP servers
		$mail->Port = $result[0]['port']; //465;
		$mail->Username = $result[0]['username']; // 'cientificayposgrado.unah.inpos@gmail.com';// SMTP username
		$mail->Password = $result[0]['password']; // 'INPOS.C0rre0.UNAH';// SMTP password
		$mail->From = $result[0]['fromAddress']; //'cientificayposgrado.unah.inpos@gmail.com';
		$mail->FromName = $result[0]['fromName']; // 'DICyP';
		$mail->addAddress($email, $nombre);
		$mail->isHTML(true); // Set email format to HTML
		$mail->Subject = $result[0]['subject']; //'Proyecto de beca asignado para '.$tipo_evaluacion[0]['titulo'];
		//$mail->Body = $cuerpo_correo;
		$mail->Body    = mb_convert_encoding(utf8_decode($cuerpo_correo), "UTF-8", mb_detect_encoding(utf8_decode($cuerpo_correo), "UTF-8, ISO-8859-1, ISO-8859-15", true));
		//$mail->Body    = mb_convert_encoding(utf8_decode($cuerpo_correo), "UTF-8", mb_detect_encoding(utf8_decode($cuerpo_correo), "UTF-8, ISO-8859-1, ISO-8859-15", true));
		$mail->AltBody = '';
		//$mail->AddEmbeddedImage($result[0]['image']);//('../../libs/imgs/logo_email.png','logoUNAH', 'LogoUNAH.png')
		$mail->AddEmbeddedImage('../../libs/imgs/Logo.png', 'logoBW', 'Logo.png');
		// Fin del correo
		if (!$mail->send()) {
			$error_envio_correo = true;
		} else {
			$error_envio_correo = false;
		}

		if ($error_envio_correo) {
			echo '0|No fue Enviado|' . $mail->ErrorInfo . '|' . $cod_modulo; //.''.!extension_loaded('openssl')?"Not Available":"Available";
		} else {
			echo $mensaje;
		}
	}
	/*
	* Envia Correo de Observación de caso
	* @author 	Kevin Fúnez
	* @date 	2017-04-21
	*/
	function enviar_mail_observacion($nombre, $email, $cod_modulo, $cod_email)
	{
		/*Ir a base de datos a traer el subject, el mensaje y el sender*/
		$DB_EMAIL 			     = new db_email();
		$error_envio_correo = false;
		$result 			       = $DB_EMAIL->email_construct($cod_modulo, $cod_email);
		$mensaje 		       = utf8_encode($result[0]['mensaje']);
		$sistema            = $result[0]['sistema'];
		$correo_consulta    = $result[0]['correo_consulta'];
		$cuerpo_correo      = $result[0]['cuerpo_correo'];
		$pairs = array(
			'nombre' => $nombre,
			'correo_consulta' => $correo_consulta,
			'sistema' => $sistema,
		);
		$cuerpo_correo = $this->parse_html($cuerpo_correo, $pairs);
		$mail = new PHPMailer();
		$mail->CharSet = 'UTF-8';
		$mail->Encoding = 'quoted-printable';
		//$mail->isSMTP();// Set mailer to use SMTP
		$mail->SMTPAuth = true; // Enable SMTP authentication
		$mail->SMTPSecure = 'ssl';
		$mail->Host = $result[0]['host']; //'smtp.gmail.com';// Specify main and backup SMTP servers
		$mail->Port = $result[0]['port']; //465;
		$mail->Username = $result[0]['username']; // 'cientificayposgrado.unah.inpos@gmail.com';// SMTP username
		$mail->Password = $result[0]['password']; // 'INPOS.C0rre0.UNAH';// SMTP password
		$mail->From = $result[0]['fromAddress']; //'cientificayposgrado.unah.inpos@gmail.com';
		$mail->FromName = $result[0]['fromName']; // 'DICyP';
		$mail->addAddress($email, $nombre);
		$mail->isHTML(true); // Set email format to HTML
		$mail->Subject = $result[0]['subject']; //'Proyecto de beca asignado para '.$tipo_evaluacion[0]['titulo'];
		//$mail->Body = $cuerpo_correo;
		$mail->Body    = mb_convert_encoding(utf8_decode($cuerpo_correo), "UTF-8", mb_detect_encoding(utf8_decode($cuerpo_correo), "UTF-8, ISO-8859-1, ISO-8859-15", true));
		//$mail->Body    = mb_convert_encoding(utf8_decode($cuerpo_correo), "UTF-8", mb_detect_encoding(utf8_decode($cuerpo_correo), "UTF-8, ISO-8859-1, ISO-8859-15", true));
		$mail->AltBody = '';
		//$mail->AddEmbeddedImage($result[0]['image']);//('../../libs/imgs/logo_email.png','logoUNAH', 'LogoUNAH.png')
		$mail->AddEmbeddedImage('../../libs/imgs/Logo.png', 'logoBW', 'Logo.png');
		// Fin del correo
		if (!$mail->send()) {
			$error_envio_correo = true;
		} else {
			$error_envio_correo = false;
		}

		if ($error_envio_correo) {
			echo '0|No fue Enviado|' . $mail->ErrorInfo . '|' . $cod_modulo; //.''.!extension_loaded('openssl')?"Not Available":"Available";
		} else {
			echo $mensaje;
		}
	}

	/*
 * Envia Correo de Observación de caso
 * @author 	Linda Zelaya
 * @date 	2017-04-21
 */
	function enviar_mail_responsable($nombre, $email, $cod_modulo, $cod_email, $auditoria, $instituto)
	{
		/*Ir a base de datos a traer el subject, el mensaje y el sender*/
		$DB_EMAIL 			     = new db_email();
		$error_envio_correo  = false;
		$result 			       = $DB_EMAIL->email_construct($cod_modulo, $cod_email);
		$mensaje 		         = utf8_encode($result[0]['mensaje']);
		$sistema             = $result[0]['sistema'];
		$correo_consulta     = $result[0]['correo_consulta'];
		$cuerpo_correo       = $result[0]['cuerpo_correo'];
		$pairs = array(
			'nombre' => $nombre,
			'correo_consulta' => $correo_consulta,
			'sistema' => $sistema,
			'auditoria' => $auditoria,
			'nombre_instituto' => $instituto,
		);
		$cuerpo_correo = $this->parse_html($cuerpo_correo, $pairs);
		$mail = new PHPMailer();
		$mail->CharSet = 'UTF-8';
		$mail->Encoding = 'quoted-printable';
		//$mail->isSMTP();// Set mailer to use SMTP
		$mail->SMTPAuth = true; // Enable SMTP authentication
		$mail->SMTPSecure = 'ssl';
		$mail->Host = $result[0]['host']; //'smtp.gmail.com';// Specify main and backup SMTP servers
		$mail->Port = $result[0]['port']; //465;
		$mail->Username = $result[0]['username']; // 'cientificayposgrado.unah.inpos@gmail.com';// SMTP username
		$mail->Password = $result[0]['password']; // 'INPOS.C0rre0.UNAH';// SMTP password
		$mail->From = $result[0]['fromAddress']; //'cientificayposgrado.unah.inpos@gmail.com';
		$mail->FromName = $result[0]['fromName']; // 'DICyP';
		$mail->addAddress($email, $nombre);
		$mail->isHTML(true); // Set email format to HTML
		$mail->Subject = $result[0]['subject']; //'Proyecto de beca asignado para '.$tipo_evaluacion[0]['titulo'];
		//$mail->Body = $cuerpo_correo;
		$mail->Body    = mb_convert_encoding(utf8_decode($cuerpo_correo), "UTF-8", mb_detect_encoding(utf8_decode($cuerpo_correo), "UTF-8, ISO-8859-1, ISO-8859-15", true));
		//$mail->Body    = mb_convert_encoding(utf8_decode($cuerpo_correo), "UTF-8", mb_detect_encoding(utf8_decode($cuerpo_correo), "UTF-8, ISO-8859-1, ISO-8859-15", true));
		$mail->AltBody = '';
		//$mail->AddEmbeddedImage($result[0]['image']);//('../../libs/imgs/logo_email.png','logoUNAH', 'LogoUNAH.png')
		$mail->AddEmbeddedImage('../../libs/imgs/Logo.png', 'logoBW', 'Logo.png');
		// Fin del correo
		if (!$mail->send()) {
			$error_envio_correo = true;
		} else {
			$error_envio_correo = false;
		}
	}

	function enviar_mail_observacion_th($nombre, $email, $cod_modulo, $cod_email, $auditoria, $instituto, $observacion)
	{
		/*Ir a base de datos a traer el subject, el mensaje y el sender*/
		$DB_EMAIL 			     = new db_email();
		$error_envio_correo  = false;
		$result 			       = $DB_EMAIL->email_construct($cod_modulo, $cod_email);
		$mensaje 		         = utf8_encode($result[0]['mensaje']);
		$sistema             = $result[0]['sistema'];
		$correo_consulta     = $result[0]['correo_consulta'];
		$cuerpo_correo       = $result[0]['cuerpo_correo'];
		$pairs = array(
			'nombre' => $nombre,
			'correo_consulta' => $correo_consulta,
			'sistema' => $sistema,
			'auditoria' => $auditoria,
			'nombre_instituto' => $instituto,
			'observacion' => $observacion,
		);
		$cuerpo_correo = $this->parse_html($cuerpo_correo, $pairs);
		$mail = new PHPMailer();
		$mail->CharSet = 'UTF-8';
		$mail->Encoding = 'quoted-printable';
		//$mail->isSMTP();// Set mailer to use SMTP
		$mail->SMTPAuth = true; // Enable SMTP authentication
		$mail->SMTPSecure = 'ssl';
		$mail->Host = $result[0]['host']; //'smtp.gmail.com';// Specify main and backup SMTP servers
		$mail->Port = $result[0]['port']; //465;
		$mail->Username = $result[0]['username']; // 'cientificayposgrado.unah.inpos@gmail.com';// SMTP username
		$mail->Password = $result[0]['password']; // 'INPOS.C0rre0.UNAH';// SMTP password
		$mail->From = $result[0]['fromAddress']; //'cientificayposgrado.unah.inpos@gmail.com';
		$mail->FromName = $result[0]['fromName']; // 'DICyP';
		$mail->addAddress($email, $nombre);
		$mail->isHTML(true); // Set email format to HTML
		$mail->Subject = $result[0]['subject']; //'Proyecto de beca asignado para '.$tipo_evaluacion[0]['titulo'];
		//$mail->Body = $cuerpo_correo;
		$mail->Body    = mb_convert_encoding(utf8_decode($cuerpo_correo), "UTF-8", mb_detect_encoding(utf8_decode($cuerpo_correo), "UTF-8, ISO-8859-1, ISO-8859-15", true));
		//$mail->Body    = mb_convert_encoding(utf8_decode($cuerpo_correo), "UTF-8", mb_detect_encoding(utf8_decode($cuerpo_correo), "UTF-8, ISO-8859-1, ISO-8859-15", true));
		$mail->AltBody = '';
		//$mail->AddEmbeddedImage($result[0]['image']);//('../../libs/imgs/logo_email.png','logoUNAH', 'LogoUNAH.png')
		$mail->AddEmbeddedImage('../../libs/imgs/Logo.png', 'logoBW', 'Logo.png');
		// Fin del correo
		if (!$mail->send()) {
			$error_envio_correo = true;
		} else {
			$error_envio_correo = false;
		}
	}

	/*
 * Envia Correo de Diligencia de caso
 * @author 	Kevin Fúnez
 * @date 	2017-04-21
 */
	function enviar_mail_diligencia($nombre, $diligencia, $fecha, $expediente, $email, $cod_modulo, $cod_email)
	{
		/*Ir a base de datos a traer el subject, el mensaje y el sender*/
		$DB_EMAIL 			     = new db_email();
		$error_envio_correo = false;
		$result 			       = $DB_EMAIL->email_construct($cod_modulo, $cod_email);
		$mensaje 		       = utf8_encode($result[0]['mensaje']);
		$sistema            = $result[0]['sistema'];
		$correo_consulta    = $result[0]['correo_consulta'];
		$cuerpo_correo      = $result[0]['cuerpo_correo'];
		$pairs = array(
			'nombre' => $nombre,
			'diligencia' => $diligencia,
			'fecha' => $fecha,
			'expediente' => $expediente,
			'correo_consulta' => $correo_consulta,
			'sistema' => $sistema,
		);
		$cuerpo_correo = $this->parse_html($cuerpo_correo, $pairs);
		$mail = new PHPMailer();
		$mail->CharSet = 'UTF-8';
		$mail->Encoding = 'quoted-printable';
		//$mail->isSMTP();// Set mailer to use SMTP
		$mail->SMTPAuth = true; // Enable SMTP authentication
		$mail->SMTPSecure = 'ssl';
		$mail->Host = $result[0]['host']; //'smtp.gmail.com';// Specify main and backup SMTP servers
		$mail->Port = $result[0]['port']; //465;
		$mail->Username = $result[0]['username']; // 'cientificayposgrado.unah.inpos@gmail.com';// SMTP username
		$mail->Password = $result[0]['password']; // 'INPOS.C0rre0.UNAH';// SMTP password
		$mail->From = $result[0]['fromAddress']; //'cientificayposgrado.unah.inpos@gmail.com';
		$mail->FromName = $result[0]['fromName']; // 'DICyP';
		$mail->addAddress($email, $nombre);
		$mail->isHTML(true); // Set email format to HTML
		$mail->Subject = $result[0]['subject']; //'Proyecto de beca asignado para '.$tipo_evaluacion[0]['titulo'];
		//$mail->Body = $cuerpo_correo;
		$mail->Body    = mb_convert_encoding(utf8_decode($cuerpo_correo), "UTF-8", mb_detect_encoding(utf8_decode($cuerpo_correo), "UTF-8, ISO-8859-1, ISO-8859-15", true));
		//$mail->Body    = mb_convert_encoding(utf8_decode($cuerpo_correo), "UTF-8", mb_detect_encoding(utf8_decode($cuerpo_correo), "UTF-8, ISO-8859-1, ISO-8859-15", true));
		$mail->AltBody = '';
		//$mail->AddEmbeddedImage($result[0]['image']);//('../../libs/imgs/logo_email.png','logoUNAH', 'LogoUNAH.png')
		$mail->AddEmbeddedImage('../../libs/imgs/Logo.png', 'logoBW', 'Logo.png');
		// Fin del correo
		if (!$mail->send()) {
			$error_envio_correo = true;
		} else {
			$error_envio_correo = false;
		}

		if ($error_envio_correo) {
			echo '0|No fue Enviado|' . $mail->ErrorInfo . '|' . $cod_modulo; //.''.!extension_loaded('openssl')?"Not Available":"Available";
		} else {
			echo $mensaje;
		}
	}

	/*
* Envia Correo Asignacion Denuncia
* @author 	Rafael Cruz
* @date 	2017-10-02
*/
	function enviar_mail_denuncia($nombre, $email, $cod_modulo, $cod_email, $cod_denuncia, $fecha, $denuncia)
	{
		/*Ir a base de datos a traer el subject, el mensaje y el sender*/
		$DB_EMAIL 			     = new db_email();
		$error_envio_correo = false;
		$result 			       = $DB_EMAIL->email_construct($cod_modulo, $cod_email);
		$mensaje 		       = utf8_encode($result[0]['mensaje']);
		$sistema            = $result[0]['sistema'];
		$correo_consulta    = $result[0]['correo_consulta'];
		$cuerpo_correo      = $result[0]['cuerpo_correo'];
		$pairs = array(
			'nombre' => $nombre,
			'correo_consulta' => $correo_consulta,
			'sistema' => $sistema,
			'fecha' => $fecha,
			'cod_denuncia' => $cod_denuncia,
			'denuncia' => $denuncia,
		);
		$cuerpo_correo = $this->parse_html($cuerpo_correo, $pairs);
		$mail = new PHPMailer();
		$mail->CharSet = 'UTF-8';
		$mail->Encoding = 'quoted-printable';
		//$mail->isSMTP();// Set mailer to use SMTP
		$mail->SMTPAuth = true; // Enable SMTP authentication
		$mail->SMTPSecure = 'ssl';
		$mail->Host = $result[0]['host']; //'smtp.gmail.com';// Specify main and backup SMTP servers
		$mail->Port = $result[0]['port']; //465;
		$mail->Username = $result[0]['username']; // 'cientificayposgrado.unah.inpos@gmail.com';// SMTP username
		$mail->Password = $result[0]['password']; // 'INPOS.C0rre0.UNAH';// SMTP password
		$mail->From = $result[0]['fromAddress']; //'cientificayposgrado.unah.inpos@gmail.com';
		$mail->FromName = $result[0]['fromName']; // 'DICyP';
		$mail->addAddress($email, $nombre);
		$mail->isHTML(true); // Set email format to HTML
		$mail->Subject = utf8_encode($result[0]['subject']); //'Proyecto de beca asignado para '.$tipo_evaluacion[0]['titulo'];
		//$mail->Body = $cuerpo_correo;
		$mail->Body    = utf8_encode($cuerpo_correo);
		//$mail->Body    = mb_convert_encoding(utf8_decode($cuerpo_correo), "UTF-8", mb_detect_encoding(utf8_decode($cuerpo_correo), "UTF-8, ISO-8859-1, ISO-8859-15", true));
		$mail->AltBody = '';
		//$mail->AddEmbeddedImage($result[0]['image']);//('../../libs/imgs/logo_email.png','logoUNAH', 'LogoUNAH.png')
		$mail->AddEmbeddedImage('../../libs/imgs/Logo.png', 'logoBW', 'Logo.png');
		// Fin del correo
		if (!$mail->send()) {
			$error_envio_correo = true;
		} else {
			$error_envio_correo = false;
		}

		if ($error_envio_correo) {
			echo '0|No fue Enviado|' . $mail->ErrorInfo . '|' . $cod_modulo; //.''.!extension_loaded('openssl')?"Not Available":"Available";
		} else {
			echo $mensaje;
		}
	}

	/*
* Envia Correo de Observación de denuncia
* @author 	Rafael Cruz
* @date 	2017-10-02
*/
	function enviar_mail_observacion_denuncia($nombre, $email, $cod_modulo, $cod_email, $cod_denuncia, $fecha, $usuario_obs, $observacion)
	{
		/*Ir a base de datos a traer el subject, el mensaje y el sender*/
		$DB_EMAIL 			     = new db_email();
		$error_envio_correo = false;
		$result 			       = $DB_EMAIL->email_construct($cod_modulo, $cod_email);
		$mensaje 		       = utf8_encode($result[0]['mensaje']);
		$sistema            = utf8_encode($result[0]['sistema']);
		$correo_consulta    = utf8_encode($result[0]['correo_consulta']);
		$cuerpo_correo      = $result[0]['cuerpo_correo'];
		$pairs = array(
			'nombre' => $nombre,
			'correo_consulta' => $correo_consulta,
			'sistema' => $sistema,
			'cod_denuncia' => $cod_denuncia,
			'fecha' => $fecha,
			'usuario' => $usuario_obs,
			'observacion' => utf8_decode($observacion),
		);
		$cuerpo_correo = $this->parse_html($cuerpo_correo, $pairs);
		$mail = new PHPMailer();
		$mail->CharSet = 'UTF-8';
		$mail->Encoding = 'quoted-printable';
		//$mail->isSMTP();// Set mailer to use SMTP
		$mail->SMTPAuth = true; // Enable SMTP authentication
		$mail->SMTPSecure = 'ssl';
		$mail->Host = $result[0]['host']; //'smtp.gmail.com';// Specify main and backup SMTP servers
		$mail->Port = $result[0]['port']; //465;
		$mail->Username = $result[0]['username']; // 'cientificayposgrado.unah.inpos@gmail.com';// SMTP username
		$mail->Password = $result[0]['password']; // 'INPOS.C0rre0.UNAH';// SMTP password
		$mail->From = $result[0]['fromAddress']; //'cientificayposgrado.unah.inpos@gmail.com';
		$mail->FromName = $result[0]['fromName']; // 'DICyP';
		$mail->addAddress($email, $nombre);
		$mail->isHTML(true); // Set email format to HTML
		$mail->Subject = utf8_encode($result[0]['subject']); //'Proyecto de beca asignado para '.$tipo_evaluacion[0]['titulo'];
		//$mail->Body = $cuerpo_correo;
		$mail->Body    = utf8_encode($cuerpo_correo);
		//$mail->Body    = mb_convert_encoding(utf8_decode($cuerpo_correo), "UTF-8", mb_detect_encoding(utf8_decode($cuerpo_correo), "UTF-8, ISO-8859-1, ISO-8859-15", true));
		$mail->AltBody = '';
		//$mail->AddEmbeddedImage($result[0]['image']);//('../../libs/imgs/logo_email.png','logoUNAH', 'LogoUNAH.png')
		$mail->AddEmbeddedImage('../../libs/imgs/Logo.png', 'logoBW', 'Logo.png');
		// Fin del correo
		if (!$mail->send()) {
			$error_envio_correo = true;
		} else {
			$error_envio_correo = false;
		}

		if ($error_envio_correo) {
			echo '0|No fue Enviado|' . $mail->ErrorInfo . '|' . $cod_modulo; //.''.!extension_loaded('openssl')?"Not Available":"Available";
		} else {
			echo $mensaje;
		}
	}

	/*
* Envia Correo de recordatorio de denuncia
* @author 	Rafael Cruz
* @date 	2017-10-02
*/
	function enviar_mail_recordatorio_denuncia($nombre, $email, $cod_modulo, $cod_email, $cod_denuncia, $recordatorio, $fecha_recordatorio)
	{
		/*Ir a base de datos a traer el subject, el mensaje y el sender*/
		$DB_EMAIL 			     = new db_email();
		$error_envio_correo = false;
		$result 			       = $DB_EMAIL->email_construct($cod_modulo, $cod_email);
		$mensaje 		       = utf8_encode($result[0]['mensaje']);
		$sistema            = utf8_encode($result[0]['sistema']);
		$correo_consulta    = utf8_encode($result[0]['correo_consulta']);
		$cuerpo_correo      = $result[0]['cuerpo_correo'];
		$pairs = array(
			'nombre' => $nombre,
			'correo_consulta' => $correo_consulta,
			'sistema' => $sistema,
			'cod_denuncia' => $cod_denuncia,
			'recordatorio' => utf8_decode($recordatorio),
			'fecha_recordatorio' => $fecha_recordatorio,
		);
		$cuerpo_correo = $this->parse_html($cuerpo_correo, $pairs);
		$mail = new PHPMailer();
		$mail->CharSet = 'UTF-8';
		$mail->Encoding = 'quoted-printable';
		//$mail->isSMTP();// Set mailer to use SMTP
		$mail->SMTPAuth = true; // Enable SMTP authentication
		$mail->SMTPSecure = 'ssl';
		$mail->Host = $result[0]['host']; //'smtp.gmail.com';// Specify main and backup SMTP servers
		$mail->Port = $result[0]['port']; //465;
		$mail->Username = $result[0]['username']; // 'cientificayposgrado.unah.inpos@gmail.com';// SMTP username
		$mail->Password = $result[0]['password']; // 'INPOS.C0rre0.UNAH';// SMTP password
		$mail->From = $result[0]['fromAddress']; //'cientificayposgrado.unah.inpos@gmail.com';
		$mail->FromName = $result[0]['fromName']; // 'DICyP';
		$mail->addAddress($email, $nombre);
		$mail->isHTML(true); // Set email format to HTML
		$mail->Subject = utf8_encode($result[0]['subject']); //'Proyecto de beca asignado para '.$tipo_evaluacion[0]['titulo'];
		//$mail->Body = $cuerpo_correo;
		$mail->Body    = utf8_encode($cuerpo_correo);
		//$mail->Body    = mb_convert_encoding(utf8_decode($cuerpo_correo), "UTF-8", mb_detect_encoding(utf8_decode($cuerpo_correo), "UTF-8, ISO-8859-1, ISO-8859-15", true));
		$mail->AltBody = '';
		//$mail->AddEmbeddedImage($result[0]['image']);//('../../libs/imgs/logo_email.png','logoUNAH', 'LogoUNAH.png')
		$mail->AddEmbeddedImage('../../libs/imgs/Logo.png', 'logoBW', 'Logo.png');
		// Fin del correo
		if (!$mail->send()) {
			$error_envio_correo = true;
		} else {
			$error_envio_correo = false;
		}

		if ($error_envio_correo) {
			echo '0|No fue Enviado|' . $mail->ErrorInfo . '|' . $cod_modulo; //.''.!extension_loaded('openssl')?"Not Available":"Available";
		} else {
			echo $mensaje;
		}
	}
}
