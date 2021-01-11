<?php

require_once('../../../../wp-load.php');
if (!isset($wpdb)){
    $msg = 'Error loading wpdb';
    echo ($msg);
    die();
}

//var_dump($_POST);

function save_csv_in_table($target_file){
    global $wpdb;
    $msg = 'Error! Unknown';

    $totalInserted = 0;
    $totalInCSV = 0;

    $tablename = $wpdb->prefix."hranker_".$_POST['table'];;

    // Import CSV
    if( isset($target_file) && isset($_POST['table']) ){
    
       
    
        // Open file in read mode
        $csvFile = fopen($target_file, 'r');

        fgetcsv($csvFile); // Skipping header row
    
        // Read file
        while(($csvData = fgetcsv($csvFile)) !== FALSE){
            $csvData = array_map("utf8_encode", $csvData);
    
            // Row column length
            $dataLen = count($csvData);
    
            // Skip row if length != 8
           // if( !($dataLen == 8) ) continue;
    
            // Assign value to variables

            if($_POST['table']=="headphones"){
                // Skip row if length != 8
                if( !($dataLen == 9) ) continue;

                $rank = trim($csvData[0]);
                $brand = trim($csvData[1]);
                $device = trim($csvData[2]);
                $price = trim($csvData[3]);
                    $price = str_replace('$','',$price);
                    $price = str_replace(',','',$price);
                    $price = (float)$price;
                $value = trim($csvData[4]);
                    $value = (int)$value;
                $principle = trim($csvData[5]);
                $overall_timbre = trim($csvData[6]);
                $summary = trim($csvData[7]);
                $ganre_focus = trim($csvData[8]);

            }else if($_POST['table']=="iem" || $_POST['table']=="earbuds"){
                // Skip row if length != 7
                if( !($dataLen == 8) ) continue;
                
                $rank = trim($csvData[0]);
                $brand = trim($csvData[1]);
                $device = trim($csvData[2]);
                $price = trim($csvData[3]);
                    $price = str_replace('$','',$price);
                    $price = str_replace(',','',$price);
                    $price = (float)$price;
                $value = trim($csvData[4]);
                    $value = (int)$value;
                $overall_timbre = trim($csvData[5]);
                $summary = trim($csvData[6]);
                $ganre_focus = trim($csvData[7]);
            }
    
            // Check record already exists or not
            $cntSQL = "SELECT count(*) as count FROM {$tablename} WHERE (brand='".$brand."' AND device='".$device."')";
            $record = $wpdb->get_results($cntSQL, OBJECT);
    
            if($record[0]->count==0){
    
            // Check if variable is empty or not
            if( !empty($rank) && !empty($device) ) {
    
                // Insert Record
                if($_POST['table']=="headphones"){
                    $wpdb->insert($tablename, array(
                    'rank' =>$rank,
                    'brand' =>$brand,
                    'device' =>$device,
                    'price' =>(float)$price,
                    'value' => $value,
                    'principle' => $principle,
                    'overall_timbre' => $overall_timbre,
                    'summary' => $summary,
                    'ganre_focus' => $ganre_focus
                    ));
                }else if($_POST['table']=="iem" || $_POST['table']=="earbuds"){
                    $wpdb->insert($tablename, array(
                        'rank' =>$rank,
                        'brand' =>$brand,
                        'device' =>$device,
                        'price' =>(float)$price,
                        'value' => $value,
                        //'principle' => $principle,
                        'overall_timbre' => $overall_timbre,
                        'summary' => $summary,
                        'ganre_focus' => $ganre_focus
                        ));
                }


                if($wpdb->insert_id > 0){
                $totalInserted++;
                }
            }
    
            }
            $totalInCSV++;
        }
    }else{
        $totalInserted = "Target file or database table not found";
    }
  
    $return = array("totalInserted"=>$totalInserted, "totalInCSV"=>$totalInCSV);
    return ($return);
}




function upload_file_to_server(){
    $target_dir = "../assets/csv_uploads/";
    $filename = time() . "_" . basename($_FILES["file"]["name"]);
    $target_file = $target_dir . $filename;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $msg = '';
    // Check file size
    /*
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        $msg .= "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    */
    // Allow only csv
    if($imageFileType != "csv" ) {
        $msg .= "only csv files are allowed. ";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $upload = "failed";
        $msg.= "File not uploaded. ";
    // if everything is ok, try to upload file
    }else if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $msg .= "The file ". htmlspecialchars( basename( $_FILES["file"]["name"])). " has been uploaded to server.";
        $upload = "success";
        //---------- Reading CSV
        $db_return = save_csv_in_table($target_file);
        //----------
    }else{
        $upload = "failed";
        $msg .= "File was not saved in the server...";
    }

    $return = array("upload"=>$upload, "upload_msg"=>$msg, "db_msg"=>$db_return);
    return ($return);
}

$return = upload_file_to_server();




        $return = json_encode($return);
        echo ($return);

?>