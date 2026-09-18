<?php
/*
 * Envía un correo eletrónico a cada usuario que se ha creado una orden de compra nueva.
 * @author      Jairo Bonilla
 * @date        2018-12-25
 */
ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
session_start();
if(!isset($_SESSION['cod_usuario'])){
    header('Location: index.php');
}
header('Content-Type: text/html; charset=utf-8');
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php"); 
include_once("../../libs/db_classes/db_usuario.php");
include_once("../../libs/db_classes/db_inventario.php"); 
include_once("../../libs/PHPMailer-master/class.phpmailer.php");
include_once("../../libs/PHPMailer-master/class.smtp.php");
include_once("../../libs/PHPMailer-master/PHPMailerAutoload.php");

/*----------------------------/////-------------------------------------------*/

/*INSTANCIAMIENTOS   */
$DB_USUARIO         = new db_usuario();
$DB_INV         	= new db_inventario();

$INVENTARIOS    = $DB_INV->inv_listado_inventario_quimico_cantidad_minima_alerta();


if(count($INVENTARIOS))
{
    foreach ($INVENTARIOS as $inventario)
    {
        $USUARIOS = $DB_INV->usu_listado_usuarios_por_info_empresa($inventario['cod_info_empresa']);
        $cuerpo_correo='
        <!DOCTYPE html>
        <html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

        <head>
            <title></title>
            <!--[if !mso]><!-- -->
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <!--<![endif]-->
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style type="text/css">
            #outlook a {
                padding: 0;
            }

            .ReadMsgBody {
                width: 100%;
            }

            .ExternalClass {
                width: 100%;
            }

            .ExternalClass * {
                line-height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                -webkit-text-size-adjust: 100%;
                -ms-text-size-adjust: 100%;
            }

            table,
            td {
                border-collapse: collapse;
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
            }

            img {
                border: 0;
                height: auto;
                line-height: 100%;
                outline: none;
                text-decoration: none;
                -ms-interpolation-mode: bicubic;
            }

            p {
                display: block;
                margin: 13px 0;
            }
            </style>
            <!--[if !mso]><!-->
            <style type="text/css">
            @media only screen and (max-width:480px) {
                @-ms-viewport {
                    width: 320px;
                }

                @viewport {
                    width: 320px;
                }
            }
            </style>
            <!--<![endif]-->
            <!--[if mso]><xml>  <o:OfficeDocumentSettings>    <o:AllowPNG/>    <o:PixelsPerInch>96</o:PixelsPerInch>  </o:OfficeDocumentSettings></xml><![endif]-->
            <!--[if lte mso 11]><style type="text/css">  .outlook-group-fix {    width:100% !important;  }</style><![endif]-->
            <!--[if !mso]><!-->
            <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet" type="text/css">
            <link href="https://fonts.googleapis.com/css?family=Cabin" rel="stylesheet" type="text/css">
            <style type="text/css">
            @import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);
            @import url(https://fonts.googleapis.com/css?family=Cabin);
            </style>
            <!--<![endif]-->
            <style type="text/css">
            @media only screen and (min-width:480px) {
                .mj-column-per-100 {
                    width: 100% !important;
                }
            }
            </style>
        </head>

        <body style="background: #FFFFFF;">
            <div class="mj-container" style="background-color:#FFFFFF;">
                <!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">        <tr>          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">      <![endif]-->
                <div style="margin:0px auto;max-width:600px;">
                    <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0">
                        <tbody>
                            <tr>
                                <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:9px 0px 9px 0px;">
                                    <!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0">        <tr>          <td style="vertical-align:top;width:600px;">      <![endif]-->
                                    <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                                        <table role="presentation" cellpadding="0" cellspacing="0" style="vertical-align:top;" width="100%" border="0">
                                            <tbody>
                                                <tr>
                                                    <td style="word-wrap:break-word;font-size:0px;padding:10px 10px 10px 10px;" align="center">
                                                        <table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="width:114px;"><a href="" target="_blank"><img alt="" title="" height="auto" src="cid:logoBW" style="border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;" width="114"></a></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--[if mso | IE]>      </td></tr></table>      <![endif]-->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mj-container" style="background-color:#FFFFFF;">
                <!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">        <tr>          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">      <![endif]-->
                <div style="margin:0px auto;max-width:600px;">
                    <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0">
                        <tbody>
                            <tr>
                                <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:9px 0px 9px 0px;">
                                    <!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0">        <tr>          <td style="vertical-align:top;width:600px;">      <![endif]-->
                                    <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                                        <table role="presentation" cellpadding="0" cellspacing="0" style="vertical-align:top;" width="100%" border="0">
                                            <tbody>
                                                <tr>
                                                    <td style="word-wrap:break-word;font-size:0px;padding:10px 10px 10px 10px;" align="center">
                                                        <table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="width:600px;"><a href="" target=""><img alt="" title="" height="auto" src="cid:emailBG" style="border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;" width="600"></a></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--[if mso | IE]>      </td></tr></table>      <![endif]-->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--[if mso | IE]>      </td></tr></table>      <![endif]-->
                <!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">        <tr>          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">      <![endif]-->
                
                <!--[if mso | IE]>      </td></tr></table>      <![endif]-->
                <!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">        <tr>          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">      <![endif]-->
                <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" border="0">
                    <tbody>
                        <tr>
                            <td>
                                <div style="margin:0px auto;max-width:600px;">
                                    <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0">
                                        <tbody>
                                            <tr>
                                                <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:0px 0px 0px 0px;">
                                                    <!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0">        <tr>          <td style="vertical-align:top;width:600px;">      <![endif]-->
                                                    <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                                                        <table role="presentation" cellpadding="0" cellspacing="0" style="vertical-align:top;" width="100%" border="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="word-wrap:break-word;font-size:0px;padding:19px 20px 19px 20px;" align="left">
                                                                        <div style="cursor:auto;color:#000000;font-family:Cabin, sans-serif;font-size:15px;line-height:22px;text-align:left;"><h2 style="color: #F05D22; line-height: 100%;"><span style="color:#16a085;">'.$inventario['cod_quimico'].'-'.$inventario['nombre_quimico'].' has reached the amount of alert.</span></h2>
                                                                            <p>A chemical inventory has reached the minimum amount of alert. Please go to B&W Farming to check it.</p>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="word-wrap:break-word;font-size:0px;padding:0px 0px 0px 0px;" align="center">
                                                                        <table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:separate;" align="center" border="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td style="border:none;border-radius:24px;color:#000000;cursor:auto;padding:10px 30px;" align="center" valign="middle" bgcolor="#7ED321"><a href="https://bw.bluelionsoft.com" style="text-decoration:none;background:#7ED321;color:#000000;font-family:Ubuntu, Helvetica, Arial, sans-serif, Helvetica, Arial, sans-serif;font-size:15px;font-weight:bold;line-height:120%;text-transform:none;margin:0px;" target="_blank">Go to B&W Farming</a></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <!--[if mso | IE]>      </td></tr></table>      <![endif]-->
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!--[if mso | IE]>      </td></tr></table>      <![endif]-->
                <!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">        <tr>          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">      <![endif]-->
                <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" border="0">
                    <tbody>
                        <tr>
                            <td>
                                <div style="margin:0px auto;max-width:600px;">
                                    <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0">
                                        <tbody>
                                            <tr>
                                                <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:0px 0px 0px 0px;">
                                                    <!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0">        <tr>          <td style="vertical-align:top;width:600px;">      <![endif]-->
                                                    <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                                                        <table role="presentation" cellpadding="0" cellspacing="0" style="vertical-align:top;" width="100%" border="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="word-wrap:break-word;font-size:0px;padding:0px 20px 0px 20px;" align="left">
                                                                        <div style="cursor:auto;color:#949494;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:15px;line-height:22px;text-align:left;">
                                                                            <p><em><span style="font-size:11px;">B&amp;W Quality Growers All Rights Reserved 2018.</span></em><br>&#xA0;</p>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <!--[if mso | IE]>      </td></tr></table>      <![endif]-->
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!--[if mso | IE]>      </td></tr></table>      <![endif]-->
            </div>
        </body>

        </html>';

        require_once __DIR__ . '/../../libs/funciones/func_env.php';
$mail = new PHPMailer();
$mail->SMTPDebug   = 0;
$mail->DKIM_domain = '127.0.0.1';
$mail->Debugoutput = 'html';
$mail->Host        = env('MAIL_HOST', 'localhost');
$mail->Port        = (int) env('MAIL_PORT', 465);
$mail->SMTPAuth    = true;
$mail->Username    = env('MAIL_USERNAME', 'noreply@bw.bluelionsoft.com');
$mail->Password    = env('MAIL_PASSWORD', '');
$mail->SMTPSecure  = env('MAIL_ENCRYPTION', 'ssl');
$mail->CharSet     = 'UTF-8';
$mail->setFrom(env('MAIL_FROM_ADDRESS', 'noreply@bw.bluelionsoft.com'), env('MAIL_FROM_NAME', 'B&W'));
        foreach ($USUARIOS as $usuario) 
        {
        	$mail->AddAddress($usuario['email'], utf8_encode($usuario['nombre_usuario']));
        }

        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Notification - '.$inventario['cod_quimico'].'-'.$inventario['nombre_quimico'];
        //$mail->Body    = $cuerpo_correo;
        $mail->Body    = mb_convert_encoding(utf8_decode($cuerpo_correo), "UTF-8", mb_detect_encoding(utf8_decode($cuerpo_correo), "UTF-8, ISO-8859-1, ISO-8859-15", true));
        $mail->AltBody = '';
        $mail->AddEmbeddedImage('../../libs/imgs/Logo.png','logoBW', 'Logo.png');
        $mail->AddEmbeddedImage('../../libs/imgs/bg_email.jpg','emailBG', 'bg_email.jpg');
        if(!$mail->send()) 
        {

            echo ('1|Error al notificar alerta de cantidad minima|'. $mail->ErrorInfo);
            //echo ('1|Asignación de documento notificada');
        } 
        else 
        {
            echo ('0|Cantidad minima de alerta de inventario notificada.');
        }
    }
}