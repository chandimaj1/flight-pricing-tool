<?php
require_once('../../../../wp-load.php');
if (!isset($wpdb)){
    $msg = 'Error loading wpdb';
    echo ($msg);
    die();
}
$msg = 'Error! Unknown';
//var_dump($_POST);

$table_name = $wpdb->prefix."flightbook_aircrafts";
$data = array(
    'id' => (int)$_POST['id'], 
    'ac_name' => $_POST['ac_name'],
    'ac_desc' => $_POST['ac_desc'],
    'ac_pax_min' => (int)$_POST['ac_pax_min'],
    'ac_pax_max' => (int)$_POST['ac_pax_max'],
    'ac_range' => (float)$_POST['ac_range'],
    'ac_speed' => (float)$_POST['ac_speed'],
    'ac_per_hr_fee' => (float)$_POST['ac_per_hr_fee'],
    'ac_per_landing_fee' => (float)$_POST['ac_per_landing_fee'],
    'ac_additions' => (float)$_POST['ac_additions'],
    'ac_ground_mins' => (float)$_POST['ac_ground_mins'],
    'ac_interior_img' => $_POST['ac_interior_img'],
    'ac_exterior_img' => $_POST['ac_exterior_img'],
    'ac_status' => 1,
);
$data_definitions = array (
    '%d',
    '%s',
    '%s',
    '%d',
    '%d',
    '%f',
    '%f',
    '%f',
    '%f',
    '%f',
    '%f',
    '%s',
    '%s',
    '%d',
);

$msg = $wpdb->replace($table_name, $data, $data_definitions);
        if($msg){ 
            $msg= "success";
        }else{
            $msg="failed";
        }

echo ($msg);