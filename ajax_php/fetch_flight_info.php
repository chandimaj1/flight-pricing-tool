<?php
require_once('../../../../wp-load.php');
if (!isset($wpdb)){
    $msg = 'Error loading wpdb';
    echo ($msg);
    die();
}

/*
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
*/

//var_dump($_POST);
$active_tab = $_POST["active_tab"];
$legs_fromid_and_toid = array();
//echo ($active_tab);
$route = '';
if ($active_tab=='pills-multi-leg'){

  $i = 0;
  foreach ( $_POST["legs"] as $leg ){

    //new calcuations api stuff
    $from_id = $_POST["legs"][$i]["from_id"];
    $to_id = $_POST["legs"][$i]["to_id"];
    
    array_push( $legs_fromid_and_toid, array($from_id, $to_id) );

    /*
    if ( $i==0 ){  
      $route = $_POST["legs"][$i]["from_icao"].'-'.$_POST["legs"][$i]["to_icao"];
    }else{ 
      $previousi = $i-1;
      if ( $_POST["legs"][$i]["from_icao"] !=  $_POST["legs"][$previousi]["to_icao"]){
        $route .= '-'.$_POST["legs"][$i]["from_icao"].'-'.$_POST["legs"][$i]["to_icao"];  
      }else if( isset($_POST["legs"][$i]["to_icao"]) ){
        $route .= '-'.$_POST["legs"][$i]["to_icao"];  
      }
    }
    */

    $i++;
  }
}else{
  //new calcuations api stuff
  $from_id = $_POST["legs"][0]["from_id"];
  $to_id = $_POST["legs"][0]["to_id"];

  array_push( $legs_fromid_and_toid, array($from_id, $to_id) );
  //$route = $_POST["legs"][0]["from_icao"].'-'.$_POST["legs"][0]["to_icao"];
}

$legs_arr = array();

function calculate_route($from_id, $to_id){
  global $wpdb, $legs_arr;
  $table_namex = $wpdb->prefix."flightbook_airports";
  $sqlx = "SELECT id,latitude,longitude FROM $table_namex WHERE (id=$from_id OR id=$to_id) LIMIT 2";
  $resultx = $wpdb->get_results( $sqlx );

  $latitudeFrom = $resultx[0]->latitude;
  $longitudeFrom = $resultx[0]->longitude;
  $latitudeTo = $resultx[1]->latitude;
  $longitudeTo = $resultx[1]->longitude;

  $distance = vincentyGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo);
  //To nautical miles
  $distance = $distance*0.868976;
  array_push( $legs_arr, array("distance_nm"=>$distance,"from_id"=>$from_id,"to_id"=>$to_id) );
  //echo ($distance);
}

/**
 * Calculates the great-circle distance between two points, with
 * the Vincenty formula.
 * @param float $latitudeFrom Latitude of start point in [deg decimal]
 * @param float $longitudeFrom Longitude of start point in [deg decimal]
 * @param float $latitudeTo Latitude of target point in [deg decimal]
 * @param float $longitudeTo Longitude of target point in [deg decimal]
 * @param float $earthRadius Mean earth radius in [m]
 * @return float Distance between points in [m] (same as earthRadius)
 *  Note that you get the distance back in the same unit as you pass in with the parameter $earthRadius. 
 * The default value is 6371000 meters so the result will be in [m] too. 
 * To get the result in miles, you could e.g. pass 3959 miles as $earthRadius and the result
 *  would be in [mi]. 

 */
function vincentyGreatCircleDistance(
  $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 3959)
{
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $lonDelta = $lonTo - $lonFrom;
  $a = pow(cos($latTo) * sin($lonDelta), 2) +
    pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
  $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

  $angle = atan2(sqrt($a), $b);
  return $angle * $earthRadius;
}

foreach ($legs_fromid_and_toid as $legs){
  calculate_route($legs[0], $legs[1]);
}

//var_dump ($route);
//echo ($route);
/*
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
*/

$response = array("totals"=>"success", "legs"=>$legs_arr);
$response = json_encode($response);
echo($response);

?>