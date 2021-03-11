<?php

require_once('../../../../wp-load.php');
if (!isset($wpdb)){
    $msg = 'Error loading wpdb';
    echo ($msg);
    die();
}

$table_name = $wpdb->prefix."flightbook_settings";
$sql = "SELECT inquiry_email FROM $table_name WHERE id=1";
$result = $wpdb->get_results( $sql );

if ($result){
  $send_email = $result[0]->inquiry_email;
}else{
    $send_email = 'charter@veloxaircharter.com';
}

//var_dump($_POST);


    $legs = '';
    foreach ($_POST["legs"] as $leg){
        $hasreturn = false;
        if (!isset($leg["return_date"]) && $leg["return_date"]==''){
            $hasreturn = true;
        }

        if ($hasreturn){
            $returnhtml = '<td>'.$leg["return_date"].'</td>';
        }else{
            $returnhtml = '';
        }

        $html = '
            <tr>
                <td>'.$leg["from_iata"].'</td>
                <td>'.$leg["from_icao"].'</td>
                <td>'.$leg["to_iata"].'</td>
                <td>'.$leg["to_icao"].'</td>
                <td>'.$leg["departure_date"].'</td>
                '.$returnhtml.'
                <td>'.$leg["pax"].'</td>
            </tr>
        ';

        if ( isset($leg["departure_date"]) && $leg["departure_date"]!='' && $leg["departure_date"]!='undefined' ){
            $legs .= $html;
        }
    }

    if ( $_POST["active_tab"]=="pills-round-trip"){
        $returnhtml = '<th>Return</th>';
    }else{
        $returnhtml = '';
    }

    $message = '
    <h4>New Inbound Charter Request</h4>
    <hr>
    <h5>Contact Information:</h5>
    <table width="99%" border="1" cellpadding="0" cellspacing="0">
        <tr>
           <th>Contact Name:</th>
           <th>Contact Email:</th>
           <th>Contact Phone:</th>
        </tr>
        <tr>
            <td>'.$_POST["contact"]["contact_name"].'</td>
           <td>'.$_POST["contact"]["contact_email"].'</td>
           <td>'.$_POST["contact"]["contact_phone"].'</td>
        </tr>
        <tr>
            <th colspan="3">Additional Requirements:</th>
        </tr>
        <tr>
            <td colspan="3">'.$_POST["contact"]["contact_requirements"].'</td>
        </tr>
    </table>

    <br>
    <h4>Inquiry Details:</h4>
    <table width="99%" border="1" cellpadding="0" cellspacing="0">
        <tr>
           <th>Category:</th>
           <th>Aircraft Type:</th>
           <th>Estimated Amount:</th>
        </tr>
        <tr>
            <td>'.$_POST["active_tab"].'</td>
            <td>'.$_POST["aircraft"].'</td>
            <td>'.$_POST["total"].'</td>
        </tr>
    </table>

    <br>
    <h4>Charter Details:</h4>
    <table width="99%" border="1" cellpadding="0" cellspacing="0">
        <tr>
           <th>From IATA</th>
           <th>From ICAO</th>
           <th>To IATA</th>
           <th>To ICAO</th>
           <th>Departure</th>
           '.$returnhtml.'
           <th>Pax</th>
        </tr>
        '.$legs.'        
    </table>
    ';

$to = $send_email;
$subject = "New Inbound Charter Request";

//add_filter( 'wp_mail_content_type', create_function( '', 'return "text/html";' ) );
$headers = array('Content-Type: text/html; charset=UTF-8');
 
wp_mail( $to, $subject, $message, $headers );

echo ("Inquiry sent to:".$send_email);
?>

