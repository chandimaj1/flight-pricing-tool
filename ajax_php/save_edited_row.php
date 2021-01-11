<?php
require_once('../../../../wp-load.php');
if (!isset($wpdb)){
    $msg = 'Error loading wpdb';
    echo ($msg);
    die();
}

function save_settings_table($row){
    global $wpdb;
    $msg = 'Error! Unknown';

    if( isset($row['table']) && isset($row['id'])  && isset($row['device']) && isset($row['price']) && isset($row['rank'])){
        $table_name = $wpdb->prefix."hranker_".$row['table'];

        //Headphones
        if($row['table']=="headphones"){
            $data = array(
                'id' => (int)$row['id'], 
                'rank' => $row['rank'], 
                'brand' => $row['brand'], 
                'device' => $row['device'],
                'price' => $row['price'],
                'value'=> $row['value'],
                'principle'=> $row['principle'],
                'overall_timbre'=> $row['overall_timbre'],
                'summary'=> $row['summary'],
                'ganre_focus'=> $row['ganre_focus'],
            );
            $data_definitions = array (
                '%d',
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
        }else if( $row['table']=="iem" || $row['table']=="earbuds" ){
            $data = array(
                'id' => (int)$row['id'], 
                'rank' => $row['rank'], 
                'brand' => $row['brand'], 
                'device' => $row['device'],
                'price' => $row['price'],
                'value'=> $row['value'],
                //'principle'=> $row['principle'],
                'overall_timbre'=> $row['overall_timbre'],
                'summary'=> $row['summary'],
                'ganre_focus'=> $row['ganre_focus'],
            );
            $data_definitions = array (
                '%d',
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

    $return = array("rowid"=>$row['id'], "msg"=>$msg);
    return ($return);
}



// Save all edited rows
$edit_rows = $_POST["data"];
//var_dump($edit_rows[0]);
$row_results = array();
$i=1;
foreach ( $edit_rows as $row){
    $row_results[$i] = save_settings_table($row);
    $i++;
};

$return = json_encode($row_results);
echo($return);

?> 