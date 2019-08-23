<!DOCTYPE html>
<html lang="en">
    <head>
        <meta HTTP-EQUIV="Content-Type" CONTENT="text/html;CHARSET=iso-8859-1">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Payment confirmation</title>
        <!-- <link rel="stylesheet" href="$$$TP RESOURCES URL$$$ingenico_payment_layout.css"> -->
        <link rel="stylesheet" href="{{ mix('assets/css/payment.css') }}">
    </head>
    <body>
        <div>
            <header class="w-100 header">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-5 col-md-4 pl-0">
                            <a href="#"><img src="/assets/img/easy-match-manager/emm.svg" class="" alt="Easy Match Manager"></a>
                        </div>
                        <div class="col-7 col-md-8 text-right">
                            <p class="text-uppercase mb-0">For help call <a href="tel:+44(0)1234 567 890" class="font-weight-bold ml-3">+44(0)1234 567 890</a></p>
                        </div>
                    </div>
                </div>
            </header>
            <div class="main-section" id="payment-zone">
                <div class="manage-tournament section-padding">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <h1 class="font-weight-bold">Payment</h1>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div id="payment-zone">
                                    <!-- Order overview -->
                                    <h2>Order overview</h2>
                                    <table class="ncoltable1" id="ncol_ref">
                                        <tbody>
                                            <tr>
                                                <td class="ncoltxtl" colspan="1" align="right" width="50%">
                                                    <small>
                                                        Order reference :<!--External reference-->
                                                    </small>
                                                </td>
                                                <td class="ncoltxtr" colspan="1" width="50%"><small>ORDER-5ce7b92650999-1558690086</small></td>
                                            </tr>
                                            <tr>
                                                <td class="ncoltxtl" colspan="1" align="right" width="50%">
                                                    <small>
                                                        Total charge :<!--Total to pay-->
                                                    </small>
                                                </td>
                                                <td class="ncoltxtr" colspan="1" width="50%">
                                                    <small>100.00 EUR
                                                    </small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="ncoltxtl" colspan="1" align="right">
                                                    <small>
                                                        Beneficiary :<!--Beneficiary-->
                                                    </small>
                                                </td>
                                                <td class="ncoltxtr" colspan="1"><small>Easy Match Manager</small></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <form method="POST" action="https://ogone.test.v-psp.com/ncol/test/orderstandard_UTF8.asp" name="OGONE_PM_CHOICE_FORM">
                                        <input type="hidden" name="CSRFKEY" value="4CF1942B00035570086960C93BA5E27FB6193F04">
                                        <input type="hidden" name="CSRFTS" value="20190524112808">
                                        <input type="hidden" name="CSRFSP" value="/ncol/test/orderstandard_utf8.asp">
                                        <input type="hidden" name="WIN3DS" value="">
                                        <input type="hidden" name="PMListType" value="1">
                                        <input type="hidden" name="branding" value="OGONE">
                                        <input type="hidden" name="payid" value="3049741379">
                                        <input type="hidden" name="pspid" value="EasymatchmanagerQA">
                                        <input type="hidden" name="LimitClientScriptUsage" value="False">
                                        <input type="hidden" name="hash_param" value="F1ADC2EBC8AB92A0243F5715E4E75C06EC02C12E">
                                        <input type="hidden" name="walletid" value="">
                                        <input type="hidden" name="walletalias" value="">
                                        <input type="hidden" name="wallethash" value="">
                                        <input type="hidden" name="thisstep" value="1">
                                        <input type="hidden" name="reselect" value="">
                                        <input type="hidden" name="CorrelationID" value="C3E5B1A1-AAA1-410E-B682-51C8090DB6CE">
                                        <br>
                                        <h2 style="display: inline; position: absolute; left: -1000px; top: -1000px; width: 0px; height: 0px; overflow: hidden;">Payment methods list</h2>
                                        <table class="ncoltable2" border="0" cellpadding="2" cellspacing="0" width="95%">
                                            <tbody>
                                                <tr>
                                                    <td class="ncolh1" rowspan="1" valign="top" align="center" colspan="3">
                                                        <b>
                                                            <small>
                                                                Please select a payment method by clicking on the logo.<!--Payment method-->
                                                            </small>
                                                        </b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="1" width="5%" valign="top" align="left" class="ncolline1">&nbsp;</td>
                                                    <td colspan="1" valign="top" align="right" class="ncolline1"><small><small><span class="1"> </span></small></small></td>
                                                    <td colspan="1" valign="top" align="center" class="ncolline1">
                                                        <input type="image" name="VISA_brand" src="https://ogone.test.v-psp.com/images/VISA_choice.gif" align="middle" alt="VISA" title="VISA" class="NCOLINIM" style="margin: 3px;"><input type="hidden" name="paymethod" value="CreditCard"><input type="image" name="Eurocard_brand" src="https://ogone.test.v-psp.com/images/Eurocard_choice.gif" align="middle" alt="MasterCard" title="MasterCard" class="NCOLINIM" style="margin: 3px;">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="1" width="5%" valign="top" align="left" class="ncolline1">&nbsp;</td>
                                                    <td colspan="2" align="left" class="ncolline1">
                                                        <small>
                                                            <small>
                                                                <a href="https://wot.test/checkout" target="_new">
                                                                    Merchant's terms and conditions<!--Selling conditions-->
                                                                </a>
                                                            </small>
                                                        </small>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </form>
                                    <!-- Further information / Cancel -->
                                    <h2>Further information / Cancel</h2>
                                    <table class="ncoltable3" id="ie_cc">
                                        <tbody>
                                            <tr>
                                                <td class="ncollogoc" valign="middle" align="center" width="33%"></td>
                                                <td class="ncollogoc" valign="middle" align="center" width="33%">
                                                    <a href="https://ogone.test.v-psp.com/ncol/PSPabout.asp?lang=1&amp;pspid=EasymatchmanagerQA&amp;branding=OGONE&amp;CSRFSP=%2Fncol%2Ftest%2Forderstandard%5Futf8%2Easp&amp;CSRFKEY=3C733E834A46A43765CDFC11856C292DBACB2CB1&amp;CSRFTS=20190524112808" target="_blank"><img border="0" src="https://ogone.test.v-psp.com/images/pp_Ingenico-ePayments1.gif" alt="Payment processed by Ingenico" title="Payment processed by Ingenico" vspace="2" id="NCOLPP"></a><br>
                                                    <small>
                                                        <small>
                                                            <a class="bottom" href="https://ogone.test.v-psp.com/ncol/PSPabout.asp?lang=1&amp;pspid=EasymatchmanagerQA&amp;branding=OGONE&amp;CSRFSP=%2Fncol%2Ftest%2Forderstandard%5Futf8%2Easp&amp;CSRFKEY=3C733E834A46A43765CDFC11856C292DBACB2CB1&amp;CSRFTS=20190524112808" target="_blank">About Ingenico</a> |
                                                            <a class="bottom" href="https://ogone.test.v-psp.com/ncol/security.asp?lang=1&amp;mode=STD&amp;branding=OGONE&amp;CSRFSP=%2Fncol%2Ftest%2Forderstandard%5Futf8%2Easp&amp;CSRFKEY=96B56CD7702DF18EE5EE3AD4CC9354B0559DBBFE&amp;CSRFTS=20190524112808" target="_blank">
                                                                Security<!--Security-->
                                                            </a>
                                                            | 
                                                            <a class="bottom" href="https://payment-services.ingenico.com/int/en/locations?lang=1&amp;mode=STD&amp;branding=OGONE&amp;CSRFSP=%2Fncol%2Ftest%2Forderstandard%5Futf8%2Easp&amp;CSRFKEY=226A140017822B7945F8461F7EAE28307D8F5B8A&amp;CSRFTS=20190524112808" target="_blank">
                                                                Legal info<!--Legal-->
                                                            </a>
                                                        </small>
                                                    </small>
                                                </td>
                                                <td class="ncollogoc" valign="middle" align="center" width="33%"><a href="https://sealinfo.websecurity.norton.com/splash?form_file=fdf/splash.fdf&amp;dn=ogone.test.v-psp.com&amp;lang=en" target="_blank"><img src="https://ogone.test.v-psp.com/images/norton-secured.png" alt="Norton Secured" title="Norton Secured" width="95" height="51" border="0"></a></td>
                                            </tr>
                                            <tr>
                                                <td class="ncollogoc" align="center" colspan="3">
                                                    <center>
                                                        <table border="0" cellpadding="0" cellspacing="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="ncollogoc" align="center" width="50%">
                                                                        <form method="POST" action="https://ogone.test.v-psp.com/ncol/test/Order_Cancel_UTF8.asp" id="form3" name="form3" onsubmit="return(window.confirm('Are you sure you want to cancel this transaction?'))" style="margin-bottom:0px;">
                                                                            <input type="hidden" name="CSRFKEY" value="366616462496F1F0107CCA7B45A6B4C63B1E179A">
                                                                            <input type="hidden" name="CSRFTS" value="20190524112808">
                                                                            <input type="hidden" name="CSRFSP" value="/ncol/test/orderstandard_utf8.asp">
                                                                            <input type="hidden" name="payid" value="3049741379">
                                                                            <input type="hidden" name="ownerZIP" value="">
                                                                            <input type="hidden" name="owneraddress" value="">
                                                                            <input type="hidden" name="alias" value="">
                                                                            <input type="hidden" name="aliasoperation" value="">
                                                                            <input type="hidden" name="hash_param" value="F1ADC2EBC8AB92A0243F5715E4E75C06EC02C12E">
                                                                            <input type="hidden" name="branding" value="OGONE">
                                                                            <small><input class="ncol" id="ncol_cancel" type="submit" name="cancel" value="Cancel"></small><!--Cancel-->
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </center>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <h2>Payment Data</h2>
                                    <table class="ncoltable2" border="0" cellpadding="2" cellspacing="0" width="95%">
                                        <tbody>
                                            <tr>
                                                <td class="ncoltxtl2" width="50%" valign="top" align="right">
                                                    <small>
                                                        Pay with<!--Credit card--> :
                                                    </small>
                                                </td>
                                                <td class="ncolinput" width="50%" valign="top" align="left" nowrap="">
                                                    <input type="hidden" name="card" size="1" value="VISA">
                                                    <img border="0" src="https://ogone.test.v-psp.com/images/VISA_choice.gif" align="middle" alt="VISA" title="VISA">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="ncoltxtl2" align="right">
                                                    <small>
                                                        <label for="Ecom_Payment_Card_Name">Cardholder's name*</label><!--Card holder name--> :
                                                    </small>
                                                </td>
                                                <td class="ncolinput"><small><input type="text" name="Ecom_Payment_Card_Name" id="Ecom_Payment_Card_Name" maxlength="35" size="20" value="" aria-required="true" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAAXNSR0IArs4c6QAAAfBJREFUWAntVk1OwkAUZkoDKza4Utm61iP0AqyIDXahN2BjwiHYGU+gizap4QDuegWN7lyCbMSlCQjU7yO0TOlAi6GwgJc0fT/fzPfmzet0crmD7HsFBAvQbrcrw+Gw5fu+AfOYvgylJ4TwCoVCs1ardYTruqfj8fgV5OUMSVVT93VdP9dAzpVvm5wJHZFbg2LQ2pEYOlZ/oiDvwNcsFoseY4PBwMCrhaeCJyKWZU37KOJcYdi27QdhcuuBIb073BvTNL8ln4NeeR6NRi/wxZKQcGurQs5oNhqLshzVTMBewW/LMU3TTNlO0ieTiStjYhUIyi6DAp0xbEdgTt+LE0aCKQw24U4llsCs4ZRJrYopB6RwqnpA1YQ5NGFZ1YQ41Z5S8IQQdP5laEBRJcD4Vj5DEsW2gE6s6g3d/YP/g+BDnT7GNi2qCjTwGd6riBzHaaCEd3Js01vwCPIbmWBRx1nwAN/1ov+/drgFWIlfKpVukyYihtgkXNp4mABK+1GtVr+SBhJDbBIubVw+Cd/TDgKO2DPiN3YUo6y/nDCNEIsqTKH1en2tcwA9FKEItyDi3aIh8Gl1sRrVnSDzNFDJT1bAy5xpOYGn5fP5JuL95ZjMIn1ya7j5dPGfv0A5eAnpZUY3n5jXcoec5J67D9q+VuAPM47D3XaSeL4AAAAASUVORK5CYII=&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;"></small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="ncoltxtl2" align="right">
                                                    <small>
                                                        <label for="Ecom_Payment_Card_Number">
                                                        Card number*
                                                        </label><!--Card number--> :
                                                    </small>
                                                </td>
                                                <td class="ncolinput"><small>
                                                    <input name="ChosenBrand" id="ChosenBrand" autocomplete="Off" type="hidden" value="VISA">
                                                    <input name="paymethod" id="paymethod" autocomplete="Off" type="hidden" value="CreditCard">
                                                    <input name="Ecom_Payment_Card_Number" id="Ecom_Payment_Card_Number" autocomplete="Off" maxlength="20" size="20" type="text" class="numberLtr" aria-required="true">
                                                    </small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="ncoltxtl2" align="right"><small>
                                                    <label for="Ecom_Payment_Card_ExpDate_Month"> Expiry date (mm</label>/<label for="Ecom_Payment_Card_ExpDate_Year">yyyy)*
                                                    </label> :</small>
                                                </td>
                                                <td class="ncolinput">
                                                    <small>
                                                        <select id="Ecom_Payment_Card_ExpDate_Month" name="Ecom_Payment_Card_ExpDate_Month" size="1" title=" Expiry date (mm/yyyy) (month)" onchange="document.OGONE_CC_FORM.Comp_Expirydate.value=document.OGONE_CC_FORM.Ecom_Payment_Card_ExpDate_Year.options[document.OGONE_CC_FORM.Ecom_Payment_Card_ExpDate_Year.selectedIndex].value
                                                            +document.OGONE_CC_FORM.Ecom_Payment_Card_ExpDate_Month.options[document.OGONE_CC_FORM.Ecom_Payment_Card_ExpDate_Month.selectedIndex].value;">
                                                            <option value=""></option>
                                                            <option value="01">01</option>
                                                            <option value="02">02</option>
                                                            <option value="03">03</option>
                                                            <option value="04">04</option>
                                                            <option value="05">05</option>
                                                            <option value="06">06</option>
                                                            <option value="07">07</option>
                                                            <option value="08">08</option>
                                                            <option value="09">09</option>
                                                            <option value="10">10</option>
                                                            <option value="11">11</option>
                                                            <option value="12">12</option>
                                                        </select>
                                                        /
                                                        <select id="Ecom_Payment_Card_ExpDate_Year" name="Ecom_Payment_Card_ExpDate_Year" size="1" title=" Expiry date (mm/yyyy) (year)" onchange="document.OGONE_CC_FORM.Comp_Expirydate.value=document.OGONE_CC_FORM.Ecom_Payment_Card_ExpDate_Year.options[document.OGONE_CC_FORM.Ecom_Payment_Card_ExpDate_Year.selectedIndex].value
                                                            +document.OGONE_CC_FORM.Ecom_Payment_Card_ExpDate_Month.options[document.OGONE_CC_FORM.Ecom_Payment_Card_ExpDate_Month.selectedIndex].value;">
                                                            <option value=""></option>
                                                            <option value="2019">2019</option>
                                                            <option value="2020">2020</option>
                                                            <option value="2021">2021</option>
                                                            <option value="2022">2022</option>
                                                            <option value="2023">2023</option>
                                                            <option value="2024">2024</option>
                                                            <option value="2025">2025</option>
                                                            <option value="2026">2026</option>
                                                            <option value="2027">2027</option>
                                                            <option value="2028">2028</option>
                                                            <option value="2029">2029</option>
                                                            <option value="2030">2030</option>
                                                            <option value="2031">2031</option>
                                                            <option value="2032">2032</option>
                                                            <option value="2033">2033</option>
                                                            <option value="2034">2034</option>
                                                            <option value="2035">2035</option>
                                                            <option value="2036">2036</option>
                                                            <option value="2037">2037</option>
                                                            <option value="2038">2038</option>
                                                            <option value="2039">2039</option>
                                                            <option value="2040">2040</option>
                                                            <option value="2041">2041</option>
                                                            <option value="2042">2042</option>
                                                            <option value="2043">2043</option>
                                                            <option value="2044">2044</option>
                                                        </select>
                                                    </small>
                                                    <input type="hidden" value="201801" name="Comp_Expirydate" aria-required="true">
                                                </td>
                                            </tr>
                                            <script type="text/javascript">
                                                <!--//
                                                var G_lsu;
                                                
                                                G_lsu = 0;
                                                
                                                function my_valscript(thisform)
                                                {
                                                    var RCval;
                                                    var lsu;
                                                    lsu = 0;
                                                    if (document.OGONE_CC_FORM.DCCAvailable != undefined)
                                                    {
                                                        if (document.OGONE_CC_FORM.DCCAvailable.value == '1')
                                                        {
                                                            if (document.OGONE_CC_FORM.DCCAccept != undefined)
                                                            {
                                                                if (!document.OGONE_CC_FORM.DCCAccept[0].checked && !document.OGONE_CC_FORM.DCCAccept[1].checked )
                                                                {
                                                                    if (lsu == 1)
                                                                    {
                                                                        document.getElementById("idErrMSGDCC").innerHTML = 'Please select your currency';
                                                                    }
                                                                    else
                                                                    {
                                                                        alert('Please select your currency');
                                                                    }
                                                                    return false;
                                                                }
                                                            }
                                                        }
                                                    }
                                                    
                                                    RCval = my_submitAndDisable(thisform,lsu);
                                                    return RCval;
                                                }
                                                //  -->
                                            </script>
                                            <tr id="cvc_dob_row">
                                                <td class="ncoltxtl2" align="right">
                                                    <small>
                                                    <label id="lbl_ecom_payment_card_identification" for="Ecom_Payment_Card_Verification" style="display:none">Card verification code* :</label>
                                                    <label id="lbl_cvc" for="Ecom_Payment_Card_Verification">Card verification code* :</label>
                                                    <input type="hidden" name="hd_birthdate_msgbox" id="hd_birthdate_msgbox" value="Card verification code>">
                                                    <input type="hidden" name="hd_cvc_msgbox" id="hd_cvc_msgbox" value="Card verification code">
                                                    </small>
                                                </td>
                                                <td class="ncolinput">
                                                    <small>
                                                    <input type="text" name="Ecom_Payment_Card_Verification" id="Ecom_Payment_Card_Verification" autocomplete="Off" size="10" maxlength="10" aria-required="true">
                                                    </small>&nbsp;
                                                    <input type="hidden" name="CVCFlag" id="CVCFlag" value="-1">
                                                    <small>
                                                    <small>
                                                    <a class="midncol" href="https://ogone.test.v-psp.com/ncol/test/card_verification_code.asp?lang=1&amp;ABRAND=%3BVISA&amp;CSRFSP=%2Fncol%2Ftest%2Forderstandard%5FUTF8%2Easp&amp;CSRFKEY=6B8ED9CB59CA191ABF602F2A1DFBE913785F0FF0&amp;CSRFTS=20190527104037" target="popup" onclick="window.open('','popup','width=300,height=400,left=0,top=0,scrollbars=1')">What is this?</a>
                                                    </small>
                                                    </small>
                                                </td>
                                            </tr>
                                            <input type="hidden" name="ownerZIP" value="">
                                            <input type="hidden" name="owneraddress" value="">
                                            <tr>
                                                <td colspan="2" valign="middle" align="center">
                                                    <small><small>
                                                    * Mandatory fields
                                                    <br>
                                                    </small>
                                                    </small>
                                                </td>
                                            </tr>
                                            <tr align="center">
                                                <td colspan="2" valign="middle" align="center">
                                                    <small>
                                                    <input type="submit" class="ncol" name="payment" value="Yes, I confirm my payment" id="submit3">
                                                    </small>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="ncoltable3" border="0" cellpadding="2" cellspacing="0" width="95%" id="ie_cc" style="behavior:url(#default#clientCaps)">
                                        <tbody>
                                            <tr>
                                                <td class="ncollogoc" valign="middle" align="center" width="33%"><img border="0" src="https://ogone.test.v-psp.com/images/ACQUIRER.gif" hspace="5" alt="ACQUIRER" title="ACQUIRER" id="NCOLACQ"></td>
                                                <td class="ncollogoc" valign="middle" align="center" width="33%">
                                                    <a href="https://ogone.test.v-psp.com/ncol/PSPabout.asp?lang=1&amp;pspid=EasymatchmanagerQA&amp;branding=OGONE&amp;CSRFSP=%2Fncol%2Ftest%2Forderstandard%5FUTF8%2Easp&amp;CSRFKEY=C510AD0F5B8B6490CFBAE22398C2969220EF5F90&amp;CSRFTS=20190527104037" target="_blank"><img border="0" src="https://ogone.test.v-psp.com/images/pp_Ingenico-ePayments1.gif" alt="Payment processed by Ingenico" title="Payment processed by Ingenico" vspace="2" id="NCOLPP"></a><br>
                                                    <small>
                                                        <small>
                                                            <a class="bottom" href="https://ogone.test.v-psp.com/ncol/PSPabout.asp?lang=1&amp;pspid=EasymatchmanagerQA&amp;branding=OGONE&amp;CSRFSP=%2Fncol%2Ftest%2Forderstandard%5FUTF8%2Easp&amp;CSRFKEY=C510AD0F5B8B6490CFBAE22398C2969220EF5F90&amp;CSRFTS=20190527104037" target="_blank">About Ingenico</a> |
                                                            <a class="bottom" href="https://ogone.test.v-psp.com/ncol/security.asp?lang=1&amp;mode=STD&amp;branding=OGONE&amp;CSRFSP=%2Fncol%2Ftest%2Forderstandard%5FUTF8%2Easp&amp;CSRFKEY=B1FB254D6A9E96274B88EBE45B5F9E436FC760B8&amp;CSRFTS=20190527104037" target="_blank">
                                                                Security<!--Security-->
                                                            </a>
                                                            | 
                                                            <a class="bottom" href="https://payment-services.ingenico.com/int/en/locations?lang=1&amp;mode=STD&amp;branding=OGONE&amp;CSRFSP=%2Fncol%2Ftest%2Forderstandard%5FUTF8%2Easp&amp;CSRFKEY=8D0F785D972452E0DC908D961A6C92C7B936F6F9&amp;CSRFTS=20190527104037" target="_blank">
                                                                Legal info<!--Legal-->
                                                            </a>
                                                        </small>
                                                    </small>
                                                </td>
                                                <td class="ncollogoc" valign="middle" align="center" width="33%"><a href="https://sealinfo.websecurity.norton.com/splash?form_file=fdf/splash.fdf&amp;dn=ogone.test.v-psp.com&amp;lang=en" target="_blank"><img src="https://ogone.test.v-psp.com/images/norton-secured.png" alt="Norton Secured" title="Norton Secured" width="95" height="51" border="0"></a></td>
                                            </tr>
                                            <tr>
                                                <td class="ncollogoc" align="center" colspan="3">
                                                    <center>
                                                        <table border="0" cellpadding="0" cellspacing="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="ncollogoc" align="center" width="50%">
                                                                        <form class="normal" action="https://ogone.test.v-psp.com/ncol/test/orderstandard_UTF8.asp" method="POST" id="form1" name="form1" style="margin-bottom:0px;">
                                                                            <input type="hidden" name="CSRFKEY" value="78F097190D19EFE4E3D68FB0141182AF1FA0D36A">
                                                                            <input type="hidden" name="CSRFTS" value="20190527104037">
                                                                            <input type="hidden" name="CSRFSP" value="/ncol/test/orderstandard_UTF8.asp">
                                                                            <input type="hidden" name="WIN3DS" value="">
                                                                            <input type="hidden" name="PMListType" value="1">
                                                                            <input type="hidden" name="branding" value="OGONE">
                                                                            <input type="hidden" name="payid" value="3049812361">
                                                                            <input type="hidden" name="hash_param" value="F4AC1535EEE3B2ADED6155EA8291E82175B69435">
                                                                            <input type="hidden" name="walletid" value="">
                                                                            <input type="hidden" name="walletalias" value="">
                                                                            <input type="hidden" name="wallethash" value="">
                                                                            <input type="hidden" name="CatalogURL" value="">
                                                                            <input type="hidden" name="HomeURL" value="">
                                                                            <input type="hidden" name="LimitClientScriptUsage" value="False">
                                                                            <input type="hidden" name="DEVICE" value="">
                                                                            <input type="hidden" name="TBLBGCOLOR" value="">
                                                                            <input type="hidden" name="TBLTXTCOLOR" value="">
                                                                            <input type="hidden" name="TPBTNBGCOLOR" value="">
                                                                            <input type="hidden" name="FONTTYPE" value="">
                                                                            <input type="hidden" name="BUTTONBGCOLOR" value="">
                                                                            <input type="hidden" name="BUTTONTXTCOLOR" value="">
                                                                            <small><input class="ncol" type="submit" name="reselect" value="Back" id="btn_Back"></small>
                                                                        </form>
                                                                    </td>
                                                                    <td class="ncollogoc" align="center" width="50%">
                                                                        <form method="POST" action="https://ogone.test.v-psp.com/ncol/test/Order_Cancel_UTF8.asp" id="form3" name="form3" onsubmit="return(window.confirm('Are you sure you want to cancel this transaction?'))" style="margin-bottom:0px;">
                                                                            <input type="hidden" name="CSRFKEY" value="F96C4BBDF37F04B8D17CDCAB3FF9224246DFE859">
                                                                            <input type="hidden" name="CSRFTS" value="20190527104037">
                                                                            <input type="hidden" name="CSRFSP" value="/ncol/test/orderstandard_UTF8.asp">
                                                                            <input type="hidden" name="payid" value="3049812361">
                                                                            <input type="hidden" name="ownerZIP" value="">
                                                                            <input type="hidden" name="owneraddress" value="">
                                                                            <input type="hidden" name="alias" value="">
                                                                            <input type="hidden" name="aliasoperation" value="">
                                                                            <input type="hidden" name="hash_param" value="F4AC1535EEE3B2ADED6155EA8291E82175B69435">
                                                                            <input type="hidden" name="branding" value="OGONE">
                                                                            <small><input class="ncol" id="ncol_cancel" type="submit" name="cancel" value="Cancel"></small><!--Cancel-->
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </center>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <script language="JavaScript">
                                        <!--
                                        
                                                window.setTimeout('window.location="https://comm-qa.wot.esrtmp.com/paymentfailure?orderID=ORDER-5cfa07f33d301-1559889907&currency=EUR&amount=100&PM=CreditCard&STATUS=1&TRXDATE=06%2F07%2F19&PAYID=3050228173&PAYIDSUB=0&DCC_INDICATOR=0&IP=203.88.145.142&SHASIGN=84A87D7BA9D73D7A1385294411858A72853120BCA37D8C54A85314BB92B885626C04030A2EA23D724C93AA91DC1DE9631ADC102F4431DD67E72B45C4E043FD52&STATUS_MESSAGE=Cancelled"; ',500000);
                                        
                                        
                                        function redirected(target)
                                        {
                                                window.location = target;
                                                return;
                                        }
                                        //-->
                                    </script>
                                    <!-- Hidden h1 containing the payment status report for blind users-->
                                    <h1 style="display: inline; position: absolute; left: -1000px; top: -1000px; width: 0px; height: 0px; overflow: hidden;">Cancelled</h1>
                                    <!-- Order overview -->
                                    <h2 style="display: inline; position: absolute; left: -1000px; top: -1000px; width: 0px; height: 0px; overflow: hidden;">Order overview</h2>
                                    <table class="ncoltable1" border="0" cellpadding="2" cellspacing="0" width="95%" id="ncol_ref">
                                        <tbody>
                                            <tr>
                                                <td class="ncoltxtl" colspan="1" align="right" width="50%">
                                                    <small>
                                                        Order reference :<!--External reference-->
                                                    </small>
                                                </td>
                                                <td class="ncoltxtr" colspan="1" width="50%"><small>ORDER-5cfa07f33d301-1559889907</small></td>
                                            </tr>
                                            <tr>
                                                <td class="ncoltxtl" colspan="1" align="right" width="50%">
                                                    <small>
                                                        Total charge :<!--Total to pay-->
                                                    </small>
                                                </td>
                                                <td class="ncoltxtr" colspan="1" width="50%">
                                                    <small>€100.00</small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="ncoltxtl" colspan="1" align="right">
                                                    <small>
                                                        Beneficiary :<!--Beneficiary-->
                                                    </small>
                                                </td>
                                                <td class="ncoltxtr" colspan="1"><small>Easy Match Manager</small></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <!-- Transaction status -->
                                    <h2 style="display: inline; position: absolute; left: -1000px; top: -1000px; width: 0px; height: 0px; overflow: hidden;">Cancelled</h2>
                                    <table class="ncoltable1" border="0" cellpadding="4" cellspacing="0" width="95%">
                                        <tbody>
                                            <tr align="center">
                                                <td class="ncoltxtc" colspan="2">
                                                    <h3>Cancelled</h3>
                                                    <b><small>Payment reference: </small></b><small>3050228173</small>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <input type="hidden" name="log" value="">
                                    <!-- Redirection information -->
                                    <h2 style="display: inline; position: absolute; left: -1000px; top: -1000px; width: 0px; height: 0px; overflow: hidden;">Redirection to merchant website after the payment</h2>
                                    <br>
                                    <table class="ncoltable1" border="0" cellpadding="4" cellspacing="0" width="95%">
                                        <tbody>
                                            <tr align="center">
                                                <td class="ncoltxtc">
                                                    <small>
                                                    You will now be redirected to the merchant's website. A warning message might be displayed, as you are about to leave the secure environment.
                                                    </small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="ncoltxtc" align="center">
                                                    <form action="https://comm-qa.wot.esrtmp.com/paymentfailure?orderID=ORDER-5cfa07f33d301-1559889907&amp;currency=EUR&amp;amount=100&amp;PM=CreditCard&amp;STATUS=1&amp;TRXDATE=06%2F07%2F19&amp;PAYID=3050228173&amp;PAYIDSUB=0&amp;DCC_INDICATOR=0&amp;IP=203.88.145.142&amp;SHASIGN=84A87D7BA9D73D7A1385294411858A72853120BCA37D8C54A85314BB92B885626C04030A2EA23D724C93AA91DC1DE9631ADC102F4431DD67E72B45C4E043FD52&amp;STATUS_MESSAGE=Cancelled">
                                                        <input class="ncol" type="button" value=" OK " onclick="redirected('https://comm-qa.wot.esrtmp.com/paymentfailure?orderID=ORDER-5cfa07f33d301-1559889907&amp;currency=EUR&amp;amount=100&amp;PM=CreditCard&amp;STATUS=1&amp;TRXDATE=06%2F07%2F19&amp;PAYID=3050228173&amp;PAYIDSUB=0&amp;DCC_INDICATOR=0&amp;IP=203.88.145.142&amp;SHASIGN=84A87D7BA9D73D7A1385294411858A72853120BCA37D8C54A85314BB92B885626C04030A2EA23D724C93AA91DC1DE9631ADC102F4431DD67E72B45C4E043FD52&amp;STATUS_MESSAGE=Cancelled');">
                                                    </form>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <script type="text/javascript" src="js/jquery.core/jquery-3.3.1.min.js"></script>
                                    <script type="text/javascript" src="js/jquery.plugins/jquery-migrate-1.4.1.min.js"></script>
                                    <script type="text/javascript">
                                        var OGONE = {};
                                        OGONE.jQuery = $.noConflict(true);
                                    </script>
                                    <script type="text/javascript" language="JavaScript" src="js/fp/Fp_inc.1.2.js"></script>
                                    <script type="text/javascript" language="JavaScript" src="base64_inc.js"></script>
                                    <!-- Further information / Cancel -->
                                    <h2 style="display: inline; position: absolute; left: -1000px; top: -1000px; width: 0px; height: 0px; overflow: hidden;">Further information / Cancel</h2>
                                    <table class="ncoltable3" border="0" cellpadding="2" cellspacing="0" width="95%" id="ie_cc" style="behavior:url(#default#clientCaps)">
                                        <tbody>
                                            <tr>
                                                <td class="ncollogoc" valign="middle" align="center" width="33%"></td>
                                                <td class="ncollogoc" valign="middle" align="center" width="33%">
                                                    <a href="https://ogone.test.v-psp.com/ncol/PSPabout.asp?lang=1&amp;pspid=EasymatchmanagerQA&amp;branding=OGONE&amp;CSRFSP=%2Fncol%2Ftest%2FOrder%5FCancel%5FUTF8%2Easp&amp;CSRFKEY=0BC76BA04295BB428742D622F3226F8B68A24E26&amp;CSRFTS=20190607084542" target="_blank"><img border="0" src="https://ogone.test.v-psp.com/images/pp_Ingenico-ePayments1.gif" alt="Payment processed by Ingenico" title="Payment processed by Ingenico" vspace="2" id="NCOLPP"></a><br>
                                                    <small>
                                                        <small>
                                                            <a class="bottom" href="https://ogone.test.v-psp.com/ncol/PSPabout.asp?lang=1&amp;pspid=EasymatchmanagerQA&amp;branding=OGONE&amp;CSRFSP=%2Fncol%2Ftest%2FOrder%5FCancel%5FUTF8%2Easp&amp;CSRFKEY=0BC76BA04295BB428742D622F3226F8B68A24E26&amp;CSRFTS=20190607084542" target="_blank">About Ingenico</a> |
                                                            <a class="bottom" href="https://ogone.test.v-psp.com/ncol/security.asp?lang=1&amp;mode=STD&amp;branding=OGONE&amp;CSRFSP=%2Fncol%2Ftest%2FOrder%5FCancel%5FUTF8%2Easp&amp;CSRFKEY=CECB447E6279D6E19144E01744AE6CA2827DFA49&amp;CSRFTS=20190607084542" target="_blank">
                                                                Security<!--Security-->
                                                            </a>
                                                            | 
                                                            <a class="bottom" href="https://payment-services.ingenico.com/int/en/locations?lang=1&amp;mode=STD&amp;branding=OGONE&amp;CSRFSP=%2Fncol%2Ftest%2FOrder%5FCancel%5FUTF8%2Easp&amp;CSRFKEY=B2C17BCC5138A34AF8FD721D8086E8935CC8B9B4&amp;CSRFTS=20190607084542" target="_blank">
                                                                Legal info<!--Legal-->
                                                            </a>
                                                        </small>
                                                    </small>
                                                </td>
                                                <td class="ncollogoc" valign="middle" align="center" width="33%"><a href="https://sealinfo.websecurity.norton.com/splash?form_file=fdf/splash.fdf&amp;dn=ogone.test.v-psp.com&amp;lang=en" target="_blank"><img src="https://ogone.test.v-psp.com/images/norton-secured.png" alt="Norton Secured" title="Norton Secured" width="95" height="51" border="0"></a></td>
                                            </tr>
                                            <tr>
                                                <td class="ncollogoc" align="center" colspan="3">
                                                    <center>
                                                        <table border="0" cellpadding="0" cellspacing="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="ncollogoc" align="center" width="50%">
                                                                        <form action="" style="margin-bottom:0px;" name="BackToMerchForm">
                                                                            <small><input type="button" class="ncol" value="Back to merchant site" onclick="window.location='http://usama-eurosport.dev.aecortech.com/'" id="btn_BackToMerchantHome"></small>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </center>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- 
            <div id="payment-zone">$$$PAYMENT ZONE$$$</div>
            --}}
            <footer class="section-padding footer">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <h4 class="text-uppercase text-white mb-2 font-weight-bold">Sales Enquiries</h4>
                            <ul class="list-unstyled mb-md-0">
                                <li class="d-lg-inline"><a href="mailto:sales@tournamentplanner.com" class="text-white">sales@tournamentplanner.com</a></li>
                                <li class="d-lg-inline pl-0 pl-lg-4"><a href="tel:+44(0)1234 567 890" class="text-white">+44(0)1234 567 890</a></li>
                            </ul>
                        </div>
                        <div class="col-sm-6">
                            <h4 class="text-uppercase text-white mb-2 font-weight-bold">Helpdesk</h4>
                            <ul class="list-unstyled mb-md-0">
                                <li class="d-lg-inline"><a href="mailto:help@tournamentplanner.com" class="text-white">help@tournamentplanner.com</a></li>
                                <li class="d-lg-inline pl-0 pl-lg-4"><a href="tel:+44(0)1234 567 890" class="text-white">+44(0)1234 567 890</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="section-divider my-4"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <ul class="list-unstyled text-center text-sm-left mb-md-0 policy-links">
                                <li class="d-inline small"><a href="#" class="text-white">Privacy Policy</a></li>
                                <li class="d-inline pl-4 small"><a href="#" class="text-white">Terms & Conditions</a></li>
                            </ul>
                        </div>
                        <div class="col-sm-6 text-center text-sm-right">
                            <p class="text-white small mb-0">&copy; 2019 Easy Tournament Manager</p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>