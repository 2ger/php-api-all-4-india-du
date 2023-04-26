<?php
//K线的数据
//有缓存

header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';

$code = $_GET['code'];
$time = $_GET['time'];
$insert = $_GET['insert'];



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
  $count = count($response['quote_candle']);
  $half = $count/2;
  $k=0;
  foreach ($response['quote_candle'] as &$val){
      $k++;
      if($k>$half ){
         $val['time'] = $val['time']+60*15;
         
         //   最后一条写入数据库,保持k线与下单价格一样
         if($count ==$k) {
            //  var_dump($val);
             $data['stock_code']= $code;
             $data['stock_gid']= "msy".$code;
             $data['open']= $val['close'];
             $data['close']= $val['close'];
             $data['high']= $val['high'];
             $data['low']= $val['low'];
             $data['volume']= '100';
             $data['timestamp']= date('Y-m-d H:i:s',time());
             $data['add_time']=  date('Y-m-d H:i:s',time());
             $res =  pdo_insert("real_time_data",$data);
             $data['status'] = $res;
            if($insert && $res){
                // echo $data['add_time']."\n";
                // echo $data['open']."\n";
            // var_dump($data);
                die(json_encode($data));
            }
         }
      }
  }


  $k=0;
  foreach ($response['quote_price'] as &$val){
     $k++;
      if($k>$half ){
         $val['time'] = $val['time']+60*15;
      }
  }
  $k=0;
  foreach ($response['quote_volume'] as &$val){
        $k++;
      if($k>$half ){
         $val['time'] = $val['time']+60*15;
      }
  }

$response['update'] = $res;
$response['time'] = $time;
$response = json_encode($response);
}
//加15分结束

//写入最新价格到k线
if($time =="2W"){
  $response = json_decode($response,true);
  $count = count($response['quote_candle']);
  $half = $count/2;
  $k=0;
  foreach ($response['quote_candle'] as &$val){
      $k++;
         //   最后一条写入数据库,保持k线与下单价格一样
         if($count ==$k) {
            //  var_dump($val);
             $data['stock_code']= $code;
             $data['stock_gid']= "msy".$code;
             $data['open']= $val['close'];
             $data['close']= $val['close'];
             $data['high']= $val['high'];
             $data['low']= $val['low'];
             $data['volume']= '100';
             $data['timestamp']= date('Y-m-d H:i:s',time());
             $data['add_time']=  date('Y-m-d H:i:s',time());
             $res =  pdo_insert("real_time_data",$data);
             $data['status'] = $res;
            if($insert && $res){
                die(json_encode($data));
            }
         }
      }
$response['update'] = $res;
$response = json_encode($response);
}


echo $response;
