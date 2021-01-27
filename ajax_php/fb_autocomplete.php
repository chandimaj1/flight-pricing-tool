<?php

$curl = curl_init();

$api_key = 'f9d503d4-ddd6-44e1-93fd-26631c2e2137';
$query = $_GET['query'];


curl_setopt_array($curl, array(
  CURLOPT_URL => "http://airlabs.co/api/v6/autocomplete?query=$query&api_key=$api_key",
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