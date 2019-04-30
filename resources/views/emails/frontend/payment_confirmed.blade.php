<?php
$fdate = str_replace('/', '-', $email_details['tournament']['tournament_start_date']);
$tdate = str_replace('/', '-', $email_details['tournament']['tournament_end_date']);
$datetime1 = new DateTime($fdate);
$datetime2 = new DateTime($tdate);
$interval = $datetime1->diff($datetime2);
$days = $interval->format('%a') + 1;
?>
<!doctype html>
<!--<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
    <body style="background: #eee;">
        <p>
            Confirmation
            Thank you for purchase. Your order number is <?php echo $email_details['paymentResponse']['orderID']; ?>
        </p>
        <p><?php echo $email_details['tournament']['tournament_max_teams']; ?> Teams licence for a <?php echo $days; ?> days tournament price is <?php echo $email_details['tournament']['total_amount']; ?> EUR</p>
    </body>
</html>-->
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

    <head>
        <title>Euro Sporting</title>
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
                    <!--[if mso]>
                  <xml>
                    <o:OfficeDocumentSettings>
                      <o:AllowPNG/>
                      <o:PixelsPerInch>96</o:PixelsPerInch>
                    </o:OfficeDocumentSettings>
                  </xml>
                  <![endif]-->
                    <!--[if lte mso 11]>
                  <style type="text/css">
                    .outlook-group-fix {
                      width:100% !important;
                    }
                  </style>
                  <![endif]-->

                    <!--[if !mso]><!-->
                    <link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet" type="text/css">
                        <style type="text/css">
                            @import url(https://fonts.googleapis.com/css?family=Roboto:300);
                        </style>
                        <!--<![endif]-->
                        <style type="text/css">
                            @media only screen and (min-width:480px) {
                                .mj-column-per-100 {
                                    width: 100%!important;
                                }
                            }
                        </style>
                        </head>
<body style="background-color:#eee;"><div style="background-color:#eee;">
<!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
        <div style="Margin:0px auto;max-width:600px;">
            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                <tbody>
                    <tr>
                        <td style="direction: ltr; font-size: 0px; padding: 15px; text-align: center; vertical-align: top;" align="center" valign="top">
<!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><![endif]--><!-- header starts --><!--[if mso | IE]><tr><td class="" width="600px" ><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:570px;" width="570" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                            <div style="Margin:0px auto;max-width:570px;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="direction: ltr; font-size: 0px; padding: 20px 0; text-align: center; vertical-align: middle;" align="center" valign="middle">
                                            <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:570px;" ><![endif]--><div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                                    </table>
                                                </div><!--[if mso | IE]></td></tr></table><![endif]-->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--[if mso | IE]></td></tr></table></td></tr><![endif]--> <!-- header ends --><!-- body starts --><!--[if mso | IE]><tr><td class="" width="600px" ><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:570px;" width="570" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                                                                                                                                                                                <div style="background:#fff;background-color:#fff;Margin:0px auto;border-radius:4px 4px 0 0;max-width:570px;"><table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#fff;background-color:#fff;width:100%;border-radius:4px 4px 0 0;"><tbody><tr><td style="direction: ltr; font-size: 0px; padding: 20px 0; text-align: center; vertical-align: top;" align="center" valign="top"><!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:570px;" ><![endif]-->
                                                <div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                                        <tr>
                                                            <td align="right" style="text-align: right; font-size: 0px; padding: 10px 25px; word-break: break-word;">
                                                                <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="text-align: right; width: 200px;" width="200" align="right">
                                                                                <img height="auto" src="https://i.imgur.com/rttk6GO.png" style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;" width="200">
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <!--[if mso | IE]></td></tr></table><![endif]-->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                      <!--[if mso | IE]></td></tr></table></td></tr><tr><td class="" width="600px" ><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:570px;" width="570" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                            <div style="background:#fff;background-color:#fff;Margin:0px auto;max-width:570px;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#fff;background-color:#fff;width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="direction: ltr; font-size: 0px; padding: 20px 0; padding-bottom: 0; text-align: center; vertical-align: top;" align="center" valign="top">
                                            <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:570px;" ><![endif]-->
                                                <div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                                        <tr>
                                                            <td align="right" style="text-align: right; font-size: 0px; padding: 10px 25px; word-break: break-word;">
                                                                <div style="font-family:Roboto, Helvetica, Arial, sans-serif;font-size:16px;font-weight:300;line-height:24px;text-align:left;color:#555;">
                                                                    <p>Hello <?php echo (!empty($email_details['user']->first_name)) ? $email_details['user']->first_name. " ". $email_details['user']->last_name : 'There';?>,</p>
                                                                    <p>Thank you for the purchase. Your order number is #<?php echo $email_details['paymentResponse']['orderID']; ?>.</p>
                                                                    <p>
                                                                        <!--<a href="#" style="color: #2196F3; text-decoration: none;">Print receipt</a>-->
                                                                    </p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <!--[if mso | IE]></td></tr></table><![endif]-->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--[if mso | IE]></td></tr></table></td></tr><tr><td class="" width="600px" ><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:570px;" width="570" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                            <div style="background:#fff;background-color:#fff;Margin:0px auto;max-width:570px;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#fff;background-color:#fff;width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="direction: ltr; font-size: 0px; padding: 25px; text-align: center; vertical-align: top;" align="center" valign="top">
<!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:520px;" ><![endif]-->
                                                <div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%"><tr><td align="right" style="text-align: right; background: #fff; font-size: 0px; padding: 0; word-break: break-word;">
                                                                <table cellpadding="0" cellspacing="0" width="100%" border="0" style="cellspacing:0;color:#000000;font-family:Arial, Helvetica, sans-serif;font-size:13px;line-height:22px;table-layout:auto;width:100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th colspan="2" style="border-bottom: 3px solid rgba(33, 150, 243,0.1); text-align: left; font-size: 20px; padding: 10px;" align="left">Reciept</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <th style="text-align: left; font-weight: normal; padding: 10px;" align="left"><?php 
																			if(isset($email_details['is_manage_license']) && $email_details['is_manage_license'] == 1)
																			{
																				echo $email_details['tournament']['tournament_max_teams']; ?> (+ <?php echo $email_details['tournament']['teamDifference']; ?>) Team license for a <?php echo $days; ?> (+ <?php echo $email_details['tournament']['dayDifference']; ?>) day tournament
																			<?php }
																			else{
																				echo $email_details['tournament']['tournament_max_teams']; ?> Team license for a <?php echo $email_details['tournament']['dayDifference']; ?> day tournament
                                                                            <?php } ?>
																			</th>
                                                                            <td style="text-align: right; padding: 10px;" align="right">
                                                                                <?php if($email_details['paymentResponse']['currency'] == "GBP") {echo "&#163;";} else { echo "&#x20AC;"; }
                                                                                ?>
                                                                                <?php echo $email_details['paymentResponse']['amount']; ?></td>
                                                                        </tr>
                                                                    </tbody>
                                                                    <thead class="footer">
                                                                        <tr>
                                                                            <th colspan="2" style="border-bottom: 0px solid #57697E; background-color: rgba(33, 150, 243,0.1); text-align: right; font-size: 16px; padding: 10px;" bgcolor="rgba(33, 150, 243,0.1)" align="right">
                                                                                <?php if($email_details['paymentResponse']['currency'] == "GBP") {echo "&#163;";} else { echo "&#x20AC;"; }
                                                                                ?>
                                                                                <?php echo $email_details['paymentResponse']['amount']; ?>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <!--[if mso | IE]></td></tr></table><![endif]-->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--[if mso | IE]></td></tr></table></td></tr><tr><td class="" width="600px" ><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:570px;" width="570" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                            <div style="background:#fff;background-color:#fff;Margin:0px auto;max-width:570px;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#fff;background-color:#fff;width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="direction: ltr; font-size: 0px; padding: 20px 0; padding-bottom: 0; padding-top: 0; text-align: center; vertical-align: top;" align="center" valign="top">
                                            <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:570px;" ><![endif]-->
                                                <div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                                        <tr>
                                                            <td align="right" style="text-align: right; font-size: 0px; padding: 10px 25px; padding-top: 0; padding-bottom: 0; word-break: break-word;">
                                                                <div style="font-family:Roboto, Helvetica, Arial, sans-serif;font-size:16px;font-weight:300;line-height:24px;text-align:left;color:#555;">
                                                                    <p>You may now proceed to your dashboard and begin adding your tournament details.</p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div><!--[if mso | IE]></td></tr></table><![endif]-->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--[if mso | IE]></td></tr></table></td></tr><tr><td class="" width="600px" ><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:570px;" width="570" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                            <div style="background:#fff;background-color:#fff;Margin:0px auto;max-width:570px;">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#fff;background-color:#fff;width:100%;"><tbody><tr><td style="direction: ltr; font-size: 0px; padding: 20px 0; padding-bottom: 0; padding-top: 0; text-align: center; vertical-align: top;" align="center" valign="top"><!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:570px;" ><![endif]--><div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%"><tr><td align="right" vertical-align="middle" style="text-align: right; font-size: 0px; padding: 10px 25px; word-break: break-word;"><table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:separate;line-height:100%;">
                                                                    <tr>
                                                                        <td align="right" bgcolor="#2196F3" role="presentation" style="text-align: right; border: none; border-radius: 3px; cursor: auto; padding: 10px 25px;" valign="middle">
                                                                            <a href="<?php echo env('APP_URL');?>/admin" style="background: #2196F3; color: #ffffff; font-family: Roboto, Helvetica, Arial, sans-serif; font-size: 13px; font-weight: 300; line-height: 120%; Margin: 0; text-decoration: none; text-transform: none;" target="_blank">Get started</a>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <!--[if mso | IE]></td></tr></table><![endif]-->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--[if mso | IE]></td></tr></table></td></tr><tr><td class="" width="600px" ><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:570px;" width="570" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                            <div style="background:#fff;background-color:#fff;Margin:0px auto;border-radius:0 0 4px 4px;max-width:570px;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#fff;background-color:#fff;width:100%;border-radius:0 0 4px 4px;">
                                    <tbody>
                                        <tr>
                                            <td style="direction: ltr; font-size: 0px; padding: 20px 0; padding-top: 0; text-align: center; vertical-align: top;" align="center" valign="top">
                                            <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:570px;" ><![endif]-->
                                                <div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                                        <tr>
                                                            <td align="right" style="text-align: right; font-size: 0px; padding: 10px 25px; word-break: break-word;">
                                                                <div style="font-family:Roboto, Helvetica, Arial, sans-serif;font-size:16px;font-weight:300;line-height:24px;text-align:left;color:#555;">
                                                                    <p>Regards,
                                                                        <br>Easy Match Manager
                                                                    </p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                          <!--[if mso | IE]></td></tr></table><![endif]--></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table></td></tr><![endif]--> <!-- body ends --><!-- footer starts --><!--[if mso | IE]><tr><td class="" width="600px" ><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:570px;" width="570" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]--><div style="Margin:0px auto;max-width:570px;"><table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="direction: ltr; font-size: 0px; padding: 20px 0; text-align: center; vertical-align: top;" align="center" valign="top">
<!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:570px;" ><![endif]-->
                                                <div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                                        <tr>
                                                            <td align="right" style="text-align: right; font-size: 0px; padding: 10px 25px; word-break: break-word;">
                                                                <div style="font-family:Roboto, Helvetica, Arial, sans-serif;font-size:16px;font-weight:300;line-height:24px;text-align:center;color:#555;">&copy; 2019 TMP Applications BV. Developer by
                                                                    <a href="aecordigital.com" style="color: #2196F3; text-decoration: none;">aecor</a>.
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div><!--[if mso | IE]></td></tr></table><![endif]-->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--[if mso | IE]></td></tr></table></td></tr><![endif]--><!-- footer ends --><!--[if mso | IE]></table><![endif]-->
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!--[if mso | IE]></td></tr></table><![endif]-->
    </div>
</body>
</html>
