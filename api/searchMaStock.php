<?php

header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';
//连接到 Redis 数据库
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(3);

$code = $_GPC['keyWords'];
$url = 'https://www.klsescreener.com/v2/stocks/all.json?term='.$code;
// echo $url;


$response = file_get_contents($url);
$response = json_decode($response,true);

$count = count($response);

if($response){
    
foreach($response as &$value) {
   $redis_data['chinese_stock_name']=    $redis_data['stock_name']=    $stock['stock_spell'] =  $stock['stock_name'] =  $val['name'] = $value['label'];
 $where['stock_code'] =   $redis_data['stock_code']= $real['stock_code'] =       $stock['stock_code'] =  $val['code'] = $value['value'];
   $redis_data['last_done']= $real['close'] =  $real['open'] =   $val['nowPrice'] = $value['price'];
   $redis_data['percent_change']=$val['hcrate'] = number_format($value['price_change']/$val['nowPrice']*100,2);
   $list[] =$val;

   if($count<5){
   
      //1 如没有则写入stock表
      $s = pdo_get("stock",$where);
      $stock['stock_type'] =  "msy";
      $real['stock_gid'] = $stock['stock_gid'] =  "msy".$stock['stock_code'];
      
      if(!$s){
          $data['stock']  = pdo_insert("stock",$stock);
          $id = pdo_insertid();
      }else{
         $id = $s['id'];
      }
 
      // $rvalue = $redis->get($real['stock_gid']);
      // if(!$rvalue){}
      //2 写入redis
      $redis_data['id']= $id;
      $redis_data['created_on']= date("Y-m-d H:i:s");
      $redis_data['market']=  "Main MARKET";
      $redis_data['last_done']=  $redis_data['lacp']= $redis_data['buy_price']= $redis_data['sell_price']=$redis_data['high']=$redis_data['low']=$value['price'];
      $redis_data['volume']=$redis_data['buy_volume']=$redis_data['sell_volume']= 100;
      $redis_data['change']= $value['price_change'];
      $redis->set('mys'.$value['value'], json_encode($redis_data));

      //3写入价格表
       $real['volume']  = 100;
       $real['add_time']= date("Y-m-d H:i:s");
       $data['real_time_data']  =  pdo_insert("real_time_data",$real);
   }

}

$data['status'] = 0;
$data['data'] = $list;

// pdo_debug();

$data = json_encode($data);
echo $data;

}

