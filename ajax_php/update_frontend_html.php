<?php
require_once('../../../../wp-load.php');
if (!isset($wpdb)){
    $msg = 'Error loading wpdb';
    echo ($msg);
    die();
}

//var_dump($_POST);

function update_frontend_html(){
    global $wpdb;
    $msg = 'Error! Unknown';

    if( isset($_POST['table']) && isset($_POST['html']) ){
        $table_name = $wpdb->prefix."hranker_settings";
        $column_name = $_POST['table'].'_html';

        //Headphones
        
            $data = array();
            $data["$column_name"] = $_POST['html'];

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
$msg = update_frontend_html();
echo($msg);

?> 