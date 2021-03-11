<?php
require_once('../../../../wp-load.php');
if (!isset($wpdb)){
    $msg = 'Error loading wpdb';
    echo ($msg);
    die();
}
$msg = 'Error! Unknown';
$_POST['id']=1;

$table_name = $wpdb->prefix."flightbook_settings";
$data = $_POST;

$msg = $wpdb->replace($table_name, $data);
        if($msg){ 
            $msg= "success";
        }else{
            $msg="failed";
        }

echo ($msg);