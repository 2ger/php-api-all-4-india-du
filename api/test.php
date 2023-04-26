<?php

header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';

$code = $_GET['code'];
$time = $_GET['time'];
$insert = $_GET['insert'];


$url = "https://www.klsescreener.com/v2/stocks/chart/5101/embedded/1y";  

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
//   CURLOPT_POSTFIELDS =>$code,
  CURLOPT_HTTPHEADER => array(
    'Referer: https://www.klsescreener.com/',
  ),
));
$response = curl_exec($curl);
curl_close($curl);
   $pattern = '/data =(.*?)\/\/ split the data set into ohlc and volume/s';
    preg_match($pattern, $response, $matches);
    $content = $matches[1];
    $content = str_replace(array("\r\n", "\r", "\n", "\t", ";"), "", $content);
    $content = str_replace(array("],        ]"), "]]", $content);
    $content = json_decode($content,true);
    echo count($content);
    $count = count($content);
    var_dump($content[$count-1]);

    // data[i][0], // the date
    // data[i][1], // open
    // data[i][2], // high
    // data[i][3], // low
    // data[i][4] // close
    // data[i][0], // the date
    // data[i][5] // the volume
