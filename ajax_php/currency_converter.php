<?php
function currencyConverter($from_currency, $to_currency, $amount) {
	$from_currency = urlencode($from_currency);
	$to_currency = urlencode($to_currency);
	$get = file_get_contents("https://finance.google.com/finance/converter?a=1&from=$from_currency&to=$to_currency");
	$get = explode("<span class=bld>",$get);
	$get = explode("</span>",$get[1]);
	$converted_currency = preg_replace("/[^0-9\.]/", null, $get[0]);
	return $converted_currency;
}

$eur = currencyConverter('USD', 'EUR', 1);
$gbp = currencyConverter('USD', 'GBP', 1);
$return = array("gbp"=>$gbp, "eur"=>$eur);
$return = json_encode($return);
echo ($return);
?>