<?php

header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';

$code = $_GET['code'];
$time = $_GET['time'];
$insert = $_GET['insert'];


    //连接到 Redis 数据库
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    $redis->select(3);
    
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
    $content = str_replace(',','',$content);
   $data['open']=  $data['close']= $content;
    
   $pattern = '/\<td\>Price Range\: \<strong\>(.*?) - /s';
    preg_match($pattern, $response, $matches);
    $content = $matches[1];
    $content = str_replace(',','',$content);
    $data['low']= $content;
   $pattern = '/ - (.*?)\<\/strong\>/s';
    preg_match($pattern, $response, $matches);
    $content = $matches[1];
    $content = str_replace(',','',$content);
    $data['high']= $content;
    
    $data['stock_code']= $code;
    $data['stock_gid']= "mys".$code;
    
     $data['volume']='100';// $val[5]
     $data['timestamp']= date('Y-m-d H:i:s',time());
     $data['add_time']=  date('Y-m-d H:i:s',time());
     
     $res =  pdo_insert("real_time_data",$data);
     $data['insert']  = $res;
       $id = pdo_insertid();
       
       
        $redis_data['chinese_stock_name']=    $redis_data['stock_name']=  $data['stock_code'];
     $redis_data['stock_code']=     $data['stock_code'];
   $redis_data['last_done']= $data['open'];
   $redis_data['percent_change']=0.01;

      $redis_data['id']= $id;
      $redis_data['created_on']= date("Y-m-d H:i:s");
      $redis_data['market']=  "Main MARKET";
      $redis_data['buy_price']= $redis_data['sell_price']=$data['close'];
      

      $redis_data['high']=$data['high'];
      $redis_data['low']=$data['low'];
      $redis_data['volume']=$redis_data['buy_volume']=$redis_data['sell_volume']= 100;
      $redis_data['change']= 0.01;
     $data['redis']= $redis->set('mys'.$value['value'], json_encode($redis_data));
     $data['redis_str']= $redis->get('mys'.$value['value']);
     
         
    //删除多余的
      pdo_fetch("delete from real_time_data where stock_code = '".$code."' and id < ".$id);
     if($res){
            die(json_encode($data));
      }

