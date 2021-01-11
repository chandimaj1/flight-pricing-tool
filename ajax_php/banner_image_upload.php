<?php

require_once('../../../../wp-load.php');
if (!isset($wpdb)){
    $msg = 'Error loading wpdb';
    echo ($msg);
    die();
}

//var_dump($_POST);

function upload_file_to_server(){
    $target_dir = "../assets/img/";
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
    // Allow only jpg or png
    if($imageFileType != "jpg" && $imageFileType != "png") {
        $msg .= "only jpg or png files are allowed. ";
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

    }else{
        $upload = "failed";
        $msg .= "File was not saved in the server...";
    }

    $return = array("upload"=>$upload, "upload_msg"=>$msg, "file_name"=>$filename);
    return ($return);
}

$return = upload_file_to_server();




        $return = json_encode($return);
        echo ($return);

?>