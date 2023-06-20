<?php

header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';

$code = $_GET['code'];
$time = $_GET['time'];
$insert = $_GET['insert'];


$url = "https://www.shareinvestor.com/prices/searchbox_prices_f.html?counter=$code.MY";  
// $res = file_get_contents($url);

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_VERBOSE => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
//   CURLOPT_POSTFIELDS =>$code,
  CURLOPT_HTTPHEADER => array(
    'Referer: https://www.klsescreener.com/',
  ),
));
$response = curl_exec($curl);


// 检查是否有错误发生
if(curl_errno($curl)) {
    echo 'cURL 错误：' . curl_error($curl);
}

// var_dump($response);

  
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

