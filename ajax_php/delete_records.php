    <?php
require_once('../../../../wp-load.php');
if (!isset($wpdb)){
    $msg = 'Error loading wpdb';
    echo ($msg);
    die();
}

//var_dump($_POST);


function delete_rows_from_table(){
    global $wpdb;
    $msg = 'Error! Unknown';
    $array_chunk_size = 10;

    if( isset($_POST['table']) && isset($_POST['ids']) ){
        $table_name = $wpdb->prefix."hranker_".$_POST['table'];

        $ids = $_POST['ids'];
        $ids = implode( ',', array_map( 'absint', $ids ) );
        $result = $wpdb->query( "DELETE FROM $table_name WHERE id IN($ids)" );

        if($result){ 
            $msg= "success";
        }else{
            $msg="failed";
        }
    }else{
        $msg = 'Missing data sent to server !';
    }

    return ($msg);
}
$msg = delete_rows_from_table();

$msg = json_encode($msg);
echo($msg);

?> 