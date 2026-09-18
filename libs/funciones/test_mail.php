<?php

/**
 * DESCRIPCIÓN DEL ARCHIVO:
 *  Este archivo esta diseñado para realizar pruebas rapdias de envios de correos
 *  No se debe tomar como un archivo que usaran los usuarios finales
 */

ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución

// Versión de phpmailer usado en las pruebas inciales: 6.8.0
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Cargamos el componente que inicializa todas las librerias instaladas con "Composer"
require '../../vendor/autoload.php';

$mail = new PHPMailer(true);
$casos = 1;
if ($casos == 1) {
    try {
        //Se crea una instancia de la clase PHPMailer
        $mail = new PHPMailer();
        //Se indica a PHPMailer sera usado para envio de correos directamente (no pasara ningún servidor dedicado)
        $mail->isSendmail();
        //Se indica el correo y el nombre del remitente
        $mail->setFrom('noreply@lmfdata.com', 'Prueba de Blue');
        //Se establece a cual correo podrán responder los usuarios cuando reciban el correo inicial
        $mail->addReplyTo('noreply@lmfdata.com', 'Prueba de Blue');
        //Se ingresa la dirección de correo que recibiran el mensaje. Se puede ingresar más de uno a la vez.
        $mail->addAddress('edwinolivera33@gmail.com', 'Edwin');
        // $mail->addAddress('dan.urquia@bluelionsoft.com', 'Dan Urquia');
        //Se establce el asunto del correo.
        $mail->Subject = 'Prueba de envio de correo';
        /**
         * Lee un archivo HTML y lo agrega al cuerpo del mensaje. Las imagenes tienen que tener referencia externa
         */
        // $mail->msgHTML(file_get_contents('contents.html'), __DIR__);
        $mail->msgHTML("<b>Cuerpo del mesnaje</b>");
        //Se indica que el cuerpo tiene estilos HTML
        // $mail->isHTML(true);
        // Se define el cupero principal
        $mail->Body    = 'Cuerpo de la funcion alternativa <b>¡Negrita!</b>';
        //Cuerpo alternativo, en caso que el contenido extraido de un archivo
        //HTML no sea compatible con el sistema de correos receptor
        $mail->AltBody = 'Cuerpo de mensaje alternatio';
        //De esta forma se permite adjuntar archivos al correo. En caso de no obtener existir el archiv, la clase ignorara la excepción
        // $mail->addAttachment('images/phpmailer_mini.png'); //En las pruebas iniciales este archivo no existia

        //Envío de correo y captación de errores.
        if (!$mail->send()) {
            echo 'Mailer tuvo un error. Error capturado: ' . $mail->ErrorInfo;
        } else {
            echo 'Correo enviado';
        }
    } catch (\Throwable $th) {
        //throw $th;
    }
} else {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        // $mail->isSMTP();                                            //Send using SMTP
        // $mail->Host       = 'smtp.example.com';                     //Set the SMTP server to send through
        $mail->Host       = '162.240.107.177';                     //Set the SMTP server to send through
        // $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'noreply@lmfdata.com';                     //SMTP username
        $mail->Password   = 'RFPUg9sK5.GQ';                               //SMTP password
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`


        $mail->setFrom('noreply@lmfdata.com', 'Correo PHP');
        $mail->addAddress('edwinolivera33@gmail.com', 'Edwin');     //Add a recipient
        // $mail->addAddress('dan.urquia@bluelionsoft.com', 'Dan');     //Add a recipient

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Asunto del correo';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo 'Message has been sent';
    } catch (\Throwable $th) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}. Error capturado: {$th}";
    }
}
// try {
//     //Server settings
//     $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
//     // $mail->isSMTP();                                            //Send using SMTP
//     // $mail->Host       = 'smtp.example.com';                     //Set the SMTP server to send through
//     $mail->Host       = '162.240.107.177';                     //Set the SMTP server to send through
//     // $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
//     $mail->Username   = 'noreply@lmfdata.com';                     //SMTP username
//     $mail->Password   = 'RFPUg9sK5.GQ';                               //SMTP password
//     // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
//     $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`


//     $mail->setFrom('noreply@lmfdata.com', 'Correo PHP');
//     $mail->addAddress('edwinolivera33@gmail.com', 'Edwin');     //Add a recipient
//     // $mail->addAddress('dan.urquia@bluelionsoft.com', 'Dan');     //Add a recipient

//     //Content
//     $mail->isHTML(true);                                  //Set email format to HTML
//     $mail->Subject = 'Asunto del correo';
//     $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
//     $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

//     $mail->send();
//     echo 'Message has been sent';
// } catch (\Throwable $th) {
//     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}. Error capturado: {$th}";
// }
