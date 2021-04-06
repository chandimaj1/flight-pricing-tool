<?php
require_once('../../../../wp-load.php');
if (!isset($wpdb)){
    $msg = 'Error loading wpdb';
    echo ($msg);
    die();
}
$msg = 'Error! Unknown';
//var_dump($_POST);

$table_name = $wpdb->prefix."flightbook_airports";
$data = array(
    'id' => (int)$_POST['id'], 
    'airport_name' => $_POST['airport_name'],
    'airport_city' => $_POST['airport_city'],
    'country_name' => $_POST['country_name'],
    'airport_iata' => $_POST['airport_iata'],
    'airport_icao' => $_POST['airport_icao'],
    'gmt' => $_POST['gmt'],
    'country_code' => $_POST['country_code'],
    'latitude' => $_POST['latitude'],
    'longitude' => $_POST['longitude'],
    'status' => $_POST['status']
   
);
$data_definitions = array (
    '%d',
    '%s',
    '%s',
    '%s',
    '%s',
    '%s',
    '%f',
    '%s',
    '%f',
    '%f',
    '%d'
);

$msg = $wpdb->replace($table_name, $data, $data_definitions);
        if($msg){ 
            $msg= "success";
        }else{
            $msg="failed";
        }

echo ($msg);