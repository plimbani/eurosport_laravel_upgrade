<?php
$fdate = str_replace('/', '-', $email_details['tournament']['tournament_start_date']);
$tdate = str_replace('/', '-', $email_details['tournament']['tournament_end_date']);
$datetime1 = new DateTime($fdate);
$datetime2 = new DateTime($tdate);
$interval = $datetime1->diff($datetime2);
$days = $interval->format('%a');
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
    <body style="background: #eee;">
        <p>
            Confirmation
            Thank you for purchase. Your order number is <?php echo $email_details['paymentResponse']['orderID']; ?>
        </p>
        <p><?php echo $email_details['tournament']['tournament_max_teams']; ?> Teams licence for a <?php echo $days; ?> days tournament price is <?php echo $email_details['tournament']['total_amount']; ?> EUR</p>
    </body>
</html>