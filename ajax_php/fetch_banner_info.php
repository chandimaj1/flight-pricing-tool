<?php

require_once('../../../../wp-load.php');
if (!isset($wpdb)){
    $msg = 'Error loading wpdb';
    echo ($msg);
    die();
}

//var_dump($_POST);


function fetch_html(){
    global $wpdb;
    $msg = 'Error! Unknown';

    
        $table_name = $wpdb->prefix."hranker_settings";

        $sql = "SELECT DISTINCT banner_image1, banner_image2, banner_image3, banner_image4, banner_url1, banner_url2, banner_url3, banner_url4 FROM $table_name WHERE id=1 LIMIT 1";

        $result = $wpdb->get_results( $sql, ARRAY_N );

        if($result){ 
            $msg= "success";
        }else{
            $msg="failed";
        }

   

    $return = array(
        "msg"=>$msg, 
        "banner1"=>$result[0][0],
        "banner2"=>$result[0][1],
        "banner3"=>$result[0][2],
        "banner4"=>$result[0][3],
        "url1"=>$result[0][4],
        "url2"=>$result[0][5],
        "url3"=>$result[0][6],
        "url4"=>$result[0][7]
    );
    $return = json_encode($return);
    echo($return);
}
fetch_html();

?>