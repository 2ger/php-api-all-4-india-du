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

//1天的，加15分钟
if($time =="1D"){
  $response = json_decode($response,true);
  foreach ($response['quote_candle'] as &$val){
      $val['time'] = $val['time']+60*15;
  }
  foreach ($response['quote_price'] as &$val){
      $val['time'] = $val['time']+60*15;
  }
  foreach ($response['quote_volume'] as &$val){
      $val['time'] = $val['time']+60*15;
  }

$response['time'] = $time;
  $response = json_encode($response);
}
//加15分结束

echo $response;
