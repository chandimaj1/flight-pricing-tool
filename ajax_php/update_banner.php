<?php
require_once('../../../../wp-load.php');
if (!isset($wpdb)){
    $msg = 'Error loading wpdb';
    echo ($msg);
    die();
}

//var_dump($_POST);

function update_banner(){
    global $wpdb;
    $msg = 'Error! Unknown';

    if( isset($_POST) ){
        $table_name = $wpdb->prefix."hranker_settings";

        
            $data = array();
            $data["banner_image1"] = $_POST['banner_image1'];
            $data["banner_image2"] = $_POST['banner_image2'];
            $data["banner_image3"] = $_POST['banner_image3'];
            $data["banner_image4"] = $_POST['banner_image4'];
            $data["banner_url1"] = $_POST['banner_url1'];
            $data["banner_url2"] = $_POST['banner_url2'];
            $data["banner_url3"] = $_POST['banner_url3'];
            $data["banner_url4"] = $_POST['banner_url4'];

            $where = array (
                'id' => 1 
            );

        
        $msg = $wpdb->update($table_name, $data, $where);
        if($msg){ 
            $msg= "success";
        }else{
            $msg="failed";
        }
    }else{
        $msg = 'Missing data sent to server !';
    }
    $msg = json_encode($msg);
    return ($msg);
}
$msg = update_banner();
echo($msg);

?> 