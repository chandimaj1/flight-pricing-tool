<?php

require_once('../../../../wp-load.php');
if (!isset($wpdb)){
    $msg = 'Error loading wpdb';
    echo ($msg);
    die();
}

$table_name = $wpdb->prefix."flightbook_settings";
$sql = "SELECT fixer_api_key,fixer_api_host FROM $table_name WHERE id=1";
$result = $wpdb->get_results( $sql );

if ($result){
  $fixer_api_key = $result[0]->fixer_api_key;
  $fixer_api_host = $result[0]->fixer_api_host;
}else{
  $fixer_api_key = '1495fc83ad76e1ecfdd2e8773e9af9a2';
  $fixer_api_host = 'http://data.fixer.io/api/latest';
}

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "http://data.fixer.io/api/latest?access_key=$fixer_api_key",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;

?>