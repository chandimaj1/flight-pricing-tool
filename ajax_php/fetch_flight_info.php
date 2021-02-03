<?php

//var_dump($_POST);
$active_tab = $_POST["active_tab"];
//echo ($active_tab);
$route = '';
if ($active_tab=='pills-one-way'){
    $route = $_POST["legs"][0]["from_icao"].'-'.$_POST["legs"][0]["to_icao"];
}
//echo ($route);
function curl_results($route){
    $curl = curl_init();

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
        'x-rapidapi-key: 0dade7188emsh9333ccd18ebfa18p1df4a7jsn3e15a17341bb',
        'x-rapidapi-host: greatcirclemapper.p.rapidapi.com'
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    return $response;
}
$response = curl_results($route);
echo ($response);

?>