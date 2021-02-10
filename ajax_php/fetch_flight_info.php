<?php
require_once('../../../../wp-load.php');
if (!isset($wpdb)){
    $msg = 'Error loading wpdb';
    echo ($msg);
    die();
}

$table_name = $wpdb->prefix."flightbook_settings";
$sql = "SELECT greatcircle_api_key,greatcircle_api_host FROM $table_name WHERE id=1";
$result = $wpdb->get_results( $sql );

if ($result){
  $greatcircle_api_key = $result[0]->greatcircle_api_key;
  $greatcircle_api_host = $result[0]->greatcircle_api_host;
}else{
  $greatcircle_api_key = "0dade7188emsh9333ccd18ebfa18p1df4a7jsn3e15a17341bb";
  $greatcircle_api_host = "greatcirclemapper.p.rapidapi.com";
}

//var_dump($_POST);
$active_tab = $_POST["active_tab"];
//echo ($active_tab);
$route = '';
if ($active_tab=='pills-multi-leg'){

  $i = 0;
  foreach ( $_POST["legs"] as $leg ){

    if ( $i==0 ){  
      $route = $_POST["legs"][$i]["from_icao"].'-'.$_POST["legs"][$i]["to_icao"];
    }else{ 
      $previousi = $i-1;
      if ( $_POST["legs"][$i]["from_icao"] !=  $_POST["legs"][$previousi]["to_icao"]){
        $route .= '-'.$_POST["legs"][$i]["from_icao"].'-'.$_POST["legs"][$i]["to_icao"];  
      }else{
        $route .= '-'.$_POST["legs"][$i]["to_icao"];  
      }
    }

    $i++;
  }
}else{
  $route = $_POST["legs"][0]["from_icao"].'-'.$_POST["legs"][0]["to_icao"];
}

//var_dump ($route);
//echo ($route);
function curl_results($route){
    $curl = curl_init();
    global $greatcircle_api_key;
    global $greatcircle_api_host;

    $api_key_string = "x-rapidapi-key: $greatcircle_api_key";
    $api_host_string = "x-rapidapi-host: $greatcircle_api_host";

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://greatcirclemapper.p.rapidapi.com/airports/route/$route/400",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'content-type: text/html;charset=UTF-8',
        'vary: Accept-Encoding',
        "x-rapidapi-key: $greatcircle_api_key",
        "x-rapidapi-host: $greatcircle_api_host"
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    return $response;
}
$response = curl_results($route);
echo ($response);

?>