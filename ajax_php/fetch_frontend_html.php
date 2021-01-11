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

    if ( isset($_POST['table']) ){
        $table_name = $wpdb->prefix."hranker_settings";
        $column_name = $_POST['table'].'_html';
        $sql = "SELECT DISTINCT $column_name FROM $table_name WHERE id=1 LIMIT 1";

        $result = $wpdb->get_results( $sql, ARRAY_N );

        if($result){ 
            $msg= "success";
        }else{
            $msg="failed";
        }

    }else{
        $msg = "Selected table not passed";
    }

    $return = array("msg"=>$msg, "result"=>$result[0][0]);
    $return = json_encode($return);
    echo($return);
}
fetch_html();

?>