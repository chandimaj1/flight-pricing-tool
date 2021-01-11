<?php
require_once('../../../../wp-load.php');
if (!isset($wpdb)){
    $msg = 'Error loading wpdb';
    echo ($msg);
    die();
}

//var_dump($_POST);

function save_table(){
    global $wpdb;
    $msg = 'Error! Unknown';

    if( isset($_POST['table']) && isset($_POST['device']) && isset($_POST['price']) && isset($_POST['rank'])){
        $table_name = $wpdb->prefix."hranker_".$_POST["table"];

        //Headphones
        if ($_POST["table"]=="headphones"){
            $data = array(
                'rank' => $_POST['rank'], 
                'brand' => $_POST['brand'], 
                'device' => $_POST['device'],
                'price' => $_POST['price'],
                'value'=> $_POST['value'],
                'principle'=> $_POST['principle'],
                'overall_timbre'=> $_POST['overall_timbre'],
                'summary'=> $_POST['summary'],
                'ganre_focus'=> $_POST['ganre_focus'],
            );
            $data_definitions = array (
                '%s',
                '%s',
                '%s',
                '%f',
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
            );

        //IEM OR EARBUDS
        }else if( $_POST["table"]=="iem" || $_POST["table"]=="earbuds" ){
            $data = array(
                'rank' => $_POST['rank'], 
                'brand' => $_POST['brand'], 
                'device' => $_POST['device'],
                'price' => $_POST['price'],
                'value'=> $_POST['value'],
                //'principle'=> $_POST['principle'],
                'overall_timbre'=> $_POST['overall_timbre'],
                'summary'=> $_POST['summary'],
                'ganre_focus'=> $_POST['ganre_focus'],
            );
            $data_definitions = array (
                '%s',
                '%s',
                '%s',
                '%f',
                '%d',
                //'%s',
                '%s',
                '%s',
                '%s',
            );
        }
        
        $msg = $wpdb->replace($table_name, $data, $data_definitions);
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
$msg = save_table();
echo($msg);

?> 