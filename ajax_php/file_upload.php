<?php

//var_dump($_POST);

$row_id = (int)$_POST["ac_row"];
$img_type = $_POST["img_type"];

global $wpdb;

$msg = 'Error! Unknown';

$target_dir = "../assets/images/aircrafts/";
$filename = time() . "_" . basename($_FILES["file"]["name"]);
$target_file = $target_dir . $filename;
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$msg = '';

// Allow only csv
if($imageFileType == "jpg" || $imageFileType == "jpeg" || $imageFileType == "png") {
    $uploadOk = 1;
}else{
    $msg .= "only jpeg, jpg or png files are allowed. $imageFileType is not valid. ";
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

$return = array("upload"=>$upload, "upload_msg"=>$msg, "filename"=>$filename);

$return = json_encode($return);
echo ($return);

?>