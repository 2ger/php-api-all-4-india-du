<?php
// https://tradingdiario.com/api/test.php
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';

$code = $_GET['code'];
$time = $_GET['time'];
$insert = $_GET['insert'];


$apiUrl = "https://www.klsescreener.com/v2/stocks/chart/$code/embedded/1y";  

// $url = "https://klse.i3investor.com/web/stock/overview/0183";  
// $url = "https://www.bursamarketplace.com/index.php?tpl=stock_ajax&type=gettixdetail&code=SALU";
// $apiUrl = "https://www.klsescreener.com/v2/stocks/chart/0183/embedded/1y";    
// $res = file_get_contents($url);


// $apiUrl = 'https://api.cloudbypass.com/v2/stocks/chart/0183/embedded/1y';
$apiKey = '9df5b13045654eb4b03c4d5a1bdf172e';
$externalHost = 'www.klsescreener.com';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$headers = [
    'x-cb-apikey: '.$apiKey,
    'x-cb-host: '.$externalHost,
];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error: '.curl_error($ch);
} else {
    echo $result;
}

curl_close($ch);

var_dump($result);die();

  
   $pattern = '/\<td rowspan\=\"2\" class=\"sic_lastdone\"><strong>(.*?)\<\/strong\>/s';
    preg_match($pattern, $response, $matches);
    $content = $matches[1];
   $data['open']=  $data['close']= $content;
    
   $pattern = '/\<td\>Price Range\: \<strong\>(.*?) - /s';
    preg_match($pattern, $response, $matches);
    $content = $matches[1];
    $data['low']= $content;
   $pattern = '/ - (.*?)\<\/strong\>/s';
    preg_match($pattern, $response, $matches);
    $content = $matches[1];
    $data['high']= $content;
    
    $data['stock_code']= $code;
    $data['stock_gid']= "mys".$code;
    
     $data['volume']='100';// $val[5]
     $data['timestamp']= date('Y-m-d H:i:s',time());
     $data['add_time']=  date('Y-m-d H:i:s',time());
     
     $res =  pdo_insert("real_time_data",$data);
     $data['insert']  = $res;
       $id = pdo_insertid();
         
    //删除多余的
      pdo_fetch("delete from real_time_data where stock_code = '".$code."' and id < ".$id);
     if($res){
            die(json_encode($data));
      }

