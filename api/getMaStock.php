<?php

header('Access-Control-Allow-Origin:*');



$code = $_GET['code'];
$time = $_GET['time'];



$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://www.malaysiastock.biz/GetIntradayQuotes.aspx?type='.$time,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$code,
  CURLOPT_HTTPHEADER => array(
    
    'Referer: https://www.malaysiastock.biz/',
  
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
