<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
  <title>Euro-Sportring</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
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

<body style="background: #eee;">
  <div style="background-color:#eee;">
    <div style="margin:0px auto;max-width:600px;">
      <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0">
        <tbody>
          <tr>
            <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:15px;">
              <div style="margin:0px auto;max-width:600px;">
                <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0">
                  <tbody>
                    <tr>
                      <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:50px;">
                          <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                              <table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                                  <tbody></tbody>
                              </table>
                          </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div style="margin:0px auto;max-width:600px;background:#fff;">
                <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:#fff;" align="center" border="0">
                  <tbody>
                    <tr>
                      <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:20px 0px;">
                        <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                          <table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                            <tbody>
                              <tr>
                                <td style="word-wrap:break-word;font-size:0px;padding:10px 25px;" align="center">
                                  <table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0">
                                    <tbody>
                                      <tr>
                                        <td style="width:200px;"><img alt="" title="" height="auto" src="{{ asset('assets/img/logo-deskk.png') }}" style="border:none;border-radius:0px;display:block;outline:none;text-decoration:none;width:100%;height:auto;" width="200"></td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div style="margin:0px auto;max-width:600px;background:#fff;">
                <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:#fff;" align="center" border="0">
                  <tbody>
                    <tr>
                      <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:0 10px;">
                        <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:middle;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                          <table role="presentation" cellpadding="0" cellspacing="0" style="vertical-align:middle;" width="100%" border="0">
                            <tbody>
                              <tr>
                                <td style="word-wrap:break-word;font-size:0px;padding:10px 25px;padding-top:10px;padding-bottom:10px;padding-right:25px;padding-left:25px;" align="left">
                                  <div class="" style="cursor:auto;color:#555;font-family:Roboto, Helvetica, Arial, sans-serif;font-size:16px;font-weight:300;line-height:24px;text-align:left;">
                                    @foreach($email_details['usersActivities'] as $detail)
                                      <h3>{{ $detail['name'] }}</h3>
                                      @foreach($detail['websites'] as $website)
                                       <p>{{ $website['name'] }} ({{ $website['location'] }})</p>
                                       @foreach($website['activities'] as $activity)
                                         <p>{{ $activity['page'] }} - {{ $activity['section'] }}</p>
                                       @endforeach
                                      @endforeach
                                    @endforeach
                                    <p>An account has been created for you to access the Euro-Sportring Tournament Planner. Please click on the button below to verify your email address and set up your password to complete your account registration.</p>
                                    <p>Once completed you will be able to login to the Tournament Planner platform and the mobile app with the same login details.</p>
                                  </div>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div style="margin:0px auto;max-width:600px;background:#fff;">
                <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:#fff;" align="center" border="0">
                  <tbody>
                    <tr>
                      <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:10px;">
                        <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:middle;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                          <table role="presentation" cellpadding="0" cellspacing="0" style="vertical-align:middle;" width="100%" border="0">
                            <tbody>
                              <tr>
                                <td style="word-wrap:break-word;font-size:0px;padding:10px 25px;padding-top:10px;padding-bottom:10px;padding-right:25px;padding-left:25px;" align="left">
                                  <div class="" style="cursor:auto;color:#555;font-family:Roboto, Helvetica, Arial, sans-serif;font-size:16px;font-weight:300;line-height:24px;text-align:left;">
                                    <p>Regards,
                                        <br>Euro-Sportring
                                    </p>
                                  </div>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div style="margin:0px auto;max-width:600px;">
                <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0">
                  <tbody>
                    <tr>
                      <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:20px 0px;">
                        <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                          <table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                            <tbody>
                              <tr>
                                <td style="word-wrap:break-word;font-size:0px;padding:10px 25px;" align="center">
                                  <div class="" style="cursor:auto;color:#555;font-family:Roboto, Helvetica, Arial, sans-serif;font-size:16px;font-weight:300;line-height:24px;text-align:center;">
                                    <p style="line-height:14px">
                                      <small>Copyright 2017 Euro-Sportring. All rights reserved. Developer by
                                        <a href="https://aecordigital.com" style="text-decoration:none">aecor</a>.
                                      </small>
                                    </p>
                                  </div>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>