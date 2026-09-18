<?PHP
/*
 * PhP creado para ejecutar con cronjob para notificar a cada usuario que no haya llenado X formulario en el día de hoy.
 * Se obtiene el listado de usuarios de acuerdo a su cargo (2 => Operador) y a la finca que pertenecen,
 * Tomando en cuenta si no ha llenado dicho formulario el día de hoy.
 * @author      Jairo Bonilla
 * @date        2020-01-23
*/

session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_configuracion.php");
include_once("../../libs/PHPMailer-master/class.phpmailer.php");
include_once("../../libs/PHPMailer-master/class.smtp.php");
include_once("../../libs/PHPMailer-master/PHPMailerAutoload.php");
/*INSTANCIAMIENTOS*/
$DB_CONG 		= new db_configuracion();
$DESTINATARIOS 	= $DB_CONG->conf_obtener_listado_usuarios_notificar_llenado_form_por_finca();
foreach ($DESTINATARIOS as $destinatario) {
	//echo $destinatario['nombre_usuario'].'-'.$destinatario['email'];
	$cuerpo_correo='
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">

	<head>
	    <!--[if gte mso 9]><xml><o:OfficeDocumentSettings><o:AllowPNG/><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml><![endif]-->
	    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
	    <meta content="width=device-width" name="viewport" />
	    <!--[if !mso]><!-->
	    <meta content="IE=edge" http-equiv="X-UA-Compatible" />
	    <!--<![endif]-->
	    <title></title>
	    <!--[if !mso]><!-->
	    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css" />
	    <!--<![endif]-->
	    <style type="text/css">
	        body {
	            margin: 0;
	            padding: 0;
	        }

	        table,
	        td,
	        tr {
	            vertical-align: top;
	            border-collapse: collapse;
	        }

	        * {
	            line-height: inherit;
	        }

	        a[x-apple-data-detectors=true] {
	            color: inherit !important;
	            text-decoration: none !important;
	        }

	        @media (max-width: 620px) {

	            .block-grid,
	            .col {
	                min-width: 320px !important;
	                max-width: 100% !important;
	                display: block !important;
	            }

	            .block-grid {
	                width: 100% !important;
	            }

	            .col {
	                width: 100% !important;
	            }

	            .col>div {
	                margin: 0 auto;
	            }

	            img.fullwidth,
	            img.fullwidthOnMobile {
	                max-width: 100% !important;
	            }

	            .no-stack .col {
	                min-width: 0 !important;
	                display: table-cell !important;
	            }

	            .no-stack.two-up .col {
	                width: 50% !important;
	            }

	            .no-stack .col.num4 {
	                width: 33% !important;
	            }

	            .no-stack .col.num8 {
	                width: 66% !important;
	            }

	            .no-stack .col.num4 {
	                width: 33% !important;
	            }

	            .no-stack .col.num3 {
	                width: 25% !important;
	            }

	            .no-stack .col.num6 {
	                width: 50% !important;
	            }

	            .no-stack .col.num9 {
	                width: 75% !important;
	            }

	            .video-block {
	                max-width: none !important;
	            }

	            .mobile_hide {
	                min-height: 0px;
	                max-height: 0px;
	                max-width: 0px;
	                display: none;
	                overflow: hidden;
	                font-size: 0px;
	            }

	            .desktop_hide {
	                display: block !important;
	                max-height: none !important;
	            }
	        }
	    </style>
	</head>

	<body class="clean-body" style="margin: 0; padding: 0; -webkit-text-size-adjust: 100%; background-color: #e2eace;">
	    <!--[if IE]><div class="ie-browser"><![endif]-->
	    <table bgcolor="#e2eace" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e2eace; width: 100%;" valign="top" width="100%">
	        <tbody>
	            <tr style="vertical-align: top;" valign="top">
	                <td style="word-break: break-word; vertical-align: top;" valign="top">
	                    <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color:#e2eace"><![endif]-->
	                    <div style="background-color:transparent;">
	                        <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
	                            <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
	                                <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px"><tr class="layout-full-width" style="background-color:transparent"><![endif]-->
	                                <!--[if (mso)|(IE)]><td align="center" width="600" style="background-color:transparent;width:600px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:0px;"><![endif]-->
	                                <div class="col num12" style="min-width: 320px; max-width: 600px; display: table-cell; vertical-align: top; width: 600px;">
	                                    <div style="width:100% !important;">
	                                        <!--[if (!mso)&(!IE)]><!-->
	                                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
	                                            <!--<![endif]-->
	                                            <div align="center" class="img-container center autowidth" style="padding-right: 0px;padding-left: 0px;">
	                                                <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]-->
	                                                <div style="font-size:1px;line-height:25px"> </div><img align="center" alt="Image" border="0" class="center autowidth" src="cid:rounderup" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 600px; display: block;" title="Image" width="600" />
	                                                <!--[if mso]></td></tr></table><![endif]-->
	                                            </div>
	                                            <!--[if (!mso)&(!IE)]><!-->
	                                        </div>
	                                        <!--<![endif]-->
	                                    </div>
	                                </div>
	                                <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
	                                <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
	                            </div>
	                        </div>
	                    </div>
	                    <div style="background-color:transparent;">
	                        <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #FFFFFF;">
	                            <div style="border-collapse: collapse;display: table;width: 100%;background-color:#FFFFFF;">
	                                <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px"><tr class="layout-full-width" style="background-color:#FFFFFF"><![endif]-->
	                                <!--[if (mso)|(IE)]><td align="center" width="600" style="background-color:#FFFFFF;width:600px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
	                                <div class="col num12" style="min-width: 320px; max-width: 600px; display: table-cell; vertical-align: top; width: 600px;">
	                                    <div style="width:100% !important;">
	                                        <!--[if (!mso)&(!IE)]><!-->
	                                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
	                                            <!--<![endif]-->
	                                            <div align="center" class="img-container center" style="padding-right: 0px;padding-left: 0px;">
	                                                <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]--><img align="center" alt="Image" border="0" class="center" src="cid:Logo" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 150px; display: block;" title="Image" width="150" />
	                                                <!--[if mso]></td></tr></table><![endif]-->
	                                            </div>
	                                            <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
	                                            <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
	                                                <div style="font-size: 12px; line-height: 1.5; color: #555555; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 18px;">
	                                                    <p style="font-size: 14px; line-height: 1.5; text-align: center; word-break: break-word; mso-line-height-alt: 21px; margin: 0;">New B&W Farming Notification.</p>
	                                                </div>
	                                            </div>
	                                            <!--[if mso]></td></tr></table><![endif]-->
	                                            <!--[if (!mso)&(!IE)]><!-->
	                                        </div>
	                                        <!--<![endif]-->
	                                    </div>
	                                </div>
	                                <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
	                                <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
	                            </div>
	                        </div>
	                    </div>
	                    <div style="background-color:transparent;">
	                        <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #FFFFFF;">
	                            <div style="border-collapse: collapse;display: table;width: 100%;background-color:#FFFFFF;">
	                                <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px"><tr class="layout-full-width" style="background-color:#FFFFFF"><![endif]-->
	                                <!--[if (mso)|(IE)]><td align="center" width="600" style="background-color:#FFFFFF;width:600px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:0px; padding-bottom:5px;"><![endif]-->
	                                <div class="col num12" style="min-width: 320px; max-width: 600px; display: table-cell; vertical-align: top; width: 600px;">
	                                    <div style="width:100% !important;">
	                                        <!--[if (!mso)&(!IE)]><!-->
	                                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
	                                            <!--<![endif]-->
	                                            <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
	                                            <div style="color:#0D0D0D;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.2;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
	                                                <div style="font-size: 12px; line-height: 1.2; color: #0D0D0D; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14px;">
	                                                    <p style="font-size: 28px; line-height: 1.2; text-align: center; word-break: break-word; mso-line-height-alt: 34px; margin: 0;"><span style="font-size: 28px;"><strong><span style="font-size: 28px;">Hello Dear User,</span></strong></span><br /><span style="font-size: 28px;">This is a reminder that you have to fill the form "'.$destinatario['nombre_formulario'].'" for the grower - facility  '.utf8_encode($destinatario['nombre_empresa']).' today.<br /></span></p>
	                                                </div>
	                                            </div>
	                                            <!--[if mso]></td></tr></table><![endif]-->
	                                            <div align="center" class="img-container center">
	                                                <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="" align="center"><![endif]--><img align="center" alt="Image" border="0" class="center" src="cid:divider" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 318px; display: block;" title="Image" width="318" />
	                                                <!--[if mso]></td></tr></table><![endif]-->
	                                            </div>
	                                            <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
	                                            <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
	                                                <div style="font-size: 12px; line-height: 1.5; color: #555555; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 18px;">
	                                                    <p style="font-size: 14px; line-height: 1.5; text-align: center; word-break: break-word; mso-line-height-alt: 21px; margin: 0;"><span style="color: #a8bf6f; font-size: 14px;"><strong><br /></strong></span></p>
	                                                </div>
	                                            </div>
	                                            <!--[if mso]></td></tr></table><![endif]-->
	                                            <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 20px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
	                                            <div style="color:#0D0D0D;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:20px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
	                                                <div style="font-size: 12px; line-height: 1.5; color: #0D0D0D; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 18px;">
	                                                    <p style="font-size: 14px; line-height: 1.5; text-align: center; word-break: break-word; mso-line-height-alt: 21px; margin: 0;">PLEASE LOG IN TO THE SYSTEM TO SEE THE COMPLETE NOTIFICATION</p>
	                                                </div>
	                                            </div>
	                                            <!--[if mso]></td></tr></table><![endif]-->
	                                            <div align="center" class="button-container" style="padding-top:25px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
	                                                <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"><tr><td style="padding-top: 25px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px" align="center"><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="" style="height:46.5pt; width:87.75pt; v-text-anchor:middle;" arcsize="7%" stroke="false" fillcolor="#a8bf6f"><w:anchorlock/><v:textbox inset="0,0,0,0"><center style="color:#ffffff; font-family:Tahoma, sans-serif; font-size:16px"><![endif]-->
	                                                <a href="https://bw.bluelionsoft.com" style="text-decoration:none;" target="_blank"><div style="text-decoration:none;display:inline-block;color:#ffffff;background-color:#a8bf6f;border-radius:4px;-webkit-border-radius:4px;-moz-border-radius:4px;width:auto; width:auto;;border-top:1px solid #a8bf6f;border-right:1px solid #a8bf6f;border-bottom:1px solid #a8bf6f;border-left:1px solid #a8bf6f;padding-top:15px;padding-bottom:15px;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;text-align:center;mso-border-alt:none;word-break:keep-all;"><span style="padding-left:15px;padding-right:15px;font-size:16px;display:inline-block;"><span style="font-size: 16px; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;">LOG IN</span></span></div></a>
	                                                <!--[if mso]></center></v:textbox></v:roundrect></td></tr></table><![endif]-->
	                                            </div>
	                                            <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
	                                                <tbody>
	                                                    <tr style="vertical-align: top;" valign="top">
	                                                        <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 30px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
	                                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; width: 100%;" valign="top" width="100%">
	                                                                <tbody>
	                                                                    <tr style="vertical-align: top;" valign="top">
	                                                                        <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
	                                                                    </tr>
	                                                                </tbody>
	                                                            </table>
	                                                        </td>
	                                                    </tr>
	                                                </tbody>
	                                            </table>
	                                            <!--[if (!mso)&(!IE)]><!-->
	                                        </div>
	                                        <!--<![endif]-->
	                                    </div>
	                                </div>
	                                <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
	                                <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
	                            </div>
	                        </div>
	                    </div>
	                    <div style="background-color:transparent;">
	                        <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #525252;">
	                            <div style="border-collapse: collapse;display: table;width: 100%;background-color:#525252;">
	                                <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px"><tr class="layout-full-width" style="background-color:#525252"><![endif]-->
	                                <!--[if (mso)|(IE)]><td align="center" width="600" style="background-color:#525252;width:600px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
	                                <div class="col num12" style="min-width: 320px; max-width: 600px; display: table-cell; vertical-align: top; width: 600px;">
	                                    <div style="width:100% !important;">
	                                        <!--[if (!mso)&(!IE)]><!-->
	                                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
	                                            <!--<![endif]-->
	                                            <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top: 20px; padding-bottom: 0px; font-family: Tahoma, sans-serif"><![endif]-->
	                                            <div style="color:#a8bf6f;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.2;padding-top:20px;padding-right:0px;padding-bottom:0px;padding-left:0px;">
	                                                <div style="font-size: 12px; line-height: 1.2; color: #a8bf6f; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14px;">
	                                                    <p style="font-size: 12px; line-height: 1.2; text-align: center; word-break: break-word; mso-line-height-alt: 14px; margin: 0;">Email <span style="color: #ffffff; font-size: 12px;">support@bluelionsoft.com</span></p>
	                                                </div>
	                                            </div>
	                                            <!--[if mso]></td></tr></table><![endif]-->
	                                            <!--[if (!mso)&(!IE)]><!-->
	                                        </div>
	                                        <!--<![endif]-->
	                                    </div>
	                                </div>
	                                <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
	                                <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
	                            </div>
	                        </div>
	                    </div>
	                    <div style="background-color:transparent;">
	                        <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
	                            <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
	                                <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px"><tr class="layout-full-width" style="background-color:transparent"><![endif]-->
	                                <!--[if (mso)|(IE)]><td align="center" width="600" style="background-color:transparent;width:600px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:0px; padding-bottom:5px;"><![endif]-->
	                                <div class="col num12" style="min-width: 320px; max-width: 600px; display: table-cell; vertical-align: top; width: 600px;">
	                                    <div style="width:100% !important;">
	                                        <!--[if (!mso)&(!IE)]><!-->
	                                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
	                                            <!--<![endif]-->
	                                            <div align="center" class="img-container center autowidth" style="padding-right: 0px;padding-left: 0px;">
	                                                <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]--><img align="center" alt="Image" border="0" class="center autowidth" src="cid:rounderdwn" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 600px; display: block;" title="Image" width="600" />
	                                                <!--[if mso]></td></tr></table><![endif]-->
	                                            </div>
	                                            <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
	                                                <tbody>
	                                                    <tr style="vertical-align: top;" valign="top">
	                                                        <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 30px; padding-right: 30px; padding-bottom: 30px; padding-left: 30px;" valign="top">
	                                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; width: 100%;" valign="top" width="100%">
	                                                                <tbody>
	                                                                    <tr style="vertical-align: top;" valign="top">
	                                                                        <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
	                                                                    </tr>
	                                                                </tbody>
	                                                            </table>
	                                                        </td>
	                                                    </tr>
	                                                </tbody>
	                                            </table>
	                                            <!--[if (!mso)&(!IE)]><!-->
	                                        </div>
	                                        <!--<![endif]-->
	                                    </div>
	                                </div>
	                                <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
	                                <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
	                            </div>
	                        </div>
	                    </div>
	                    <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
	                </td>
	            </tr>
	        </tbody>
	    </table>
	    <!--[if (IE)]></div><![endif]-->
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

	$usuarios = explode(',', $destinatario['nombre_usuario']);
	$emails = explode(',', $destinatario['email_usuario']);
	$contador = 0;
	foreach ($usuarios as $usuario)
	{
		$mail->AddAddress($emails[$contador], utf8_encode($usuario));
		$contador++;
	}

	$mail->isHTML(true);                                  // Set email format to HTML
	$mail->Subject = 'Reminder - Form '.$destinatario['nombre_formulario'].' '.date('Y-m-d');
	//$mail->Body    = $cuerpo_correo;
	$mail->Body    = mb_convert_encoding(utf8_decode($cuerpo_correo), "UTF-8", mb_detect_encoding(utf8_decode($cuerpo_correo), "UTF-8, ISO-8859-1, ISO-8859-15", true));
	$mail->AltBody = '';
	$mail->AddEmbeddedImage('../../libs/imgs/Logo.png','Logo', 'Logo.png');
	$mail->AddEmbeddedImage('../../libs/imgs/divider.png','divider', 'divider.png');
	$mail->AddEmbeddedImage('../../libs/imgs/rounder-dwn.png','rounderdwn', 'rounder-dwn.png');
	$mail->AddEmbeddedImage('../../libs/imgs/rounder-up.png','rounderup', 'rounder-up.png');
	if(!$mail->send())
	{

	    echo ('1|Error al notificar.|'. $mail->ErrorInfo);
	    //echo ('1|Asignación de documento notificada');
	}
	else
	{
	    echo ('0|Successful notification - Notificación realizada exitosamente.');
	}
}
?>