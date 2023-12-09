<?php
//持机 每1分钟
// 采集印度大宗商品 https://trade.pgim.pro/api/cai_india_economictimes_list.php
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';


//连接到 Redis 数据库
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(3);

$url = 'https://mcxlivefeeds.indiatimes.com/ET_MCX/MCXLiveController?pagesize=25&pageno=1&sortorder=desc&statstype=moversbyvalue&sortby=value&callback=ajaxResponse';//

echo $url."\n";
    
    $response = file_get_contents($url);
    // var_dump($response);
    $response = str_replace("ajaxResponse(","",$response);
    $response = str_replace(")","",$response);
    
    $response = json_decode($response,true);
     $response =$response['searchresult'];
    $count = count($response);
    
echo $count." 个\n";
    // die();
    if($response){
        foreach($response as &$value) {
             $stock['stock_spell'] =   $value['Symbol'];
              $stock['stock_plate'] =   $value['Segment'];
              
            $redis_data['stock_name']=     $redis_data['chinese_stock_name']=    $stock['stock_name'] = $value['commodityname2'];
            $where['stock_code'] =   $redis_data['stock_code']= $real['stock_code'] =     $stock['stock_code'] =  $value['Symbol'];
           $redis_data['last_done']=  $value['current'];
           $redis_data['percent_change']= $stock['increase_ratio']= $value['percentagechange'];
        
              //1 如没有则写入stock表
              $ss = pdo_fetch("select id,stock_code  from stock where stock_code = '".$value['Symbol']."' order by id asc");
              $id = $ss['id'];
              
              $stock['stock_type'] =  $value['Segment'];
             $real['stock_gid'] = $stock['stock_gid'] =  "mys".$value['Symbol'];
              
              if(!$ss){
                  $data['stock']  = pdo_insert("stock",$stock);
                   $id = pdo_insertid();
                    echo "\n >> 写入 股票 成功 ".$value['Symbol'];
                }else{
                    echo "\n >> 更新 股票 成功 ".$value['Symbol'];
                $res  =  pdo_update("stock",$stock,$where);
                 $id = $ss['id'];
              }
         
              //2 写入redis
          $redis_data['id']= $id;
          $redis_data['created_on']= date("Y-m-d H:i:s");
          $redis_data['market']= $value['Segment'];
          $redis_data['last_done']=  $redis_data['lacp']= $redis_data['buy_price']= $redis_data['sell_price']=$redis_data['high']=$redis_data['low']=$value['current'];
          $redis_data['volume']=$redis_data['buy_volume']=$redis_data['sell_volume']= $value['Symbol'];
          $redis_data['business_balance']=  $redis_data['change']= $value['percentagechange'];
          
        $redis->set($stock['stock_gid'], json_encode($redis_data));
        //读取redis
        //   echo $rvalue;
    
          //3写入价格表 
          if($value['current'] >0){ //有价格才写入
              
            $where['stock_code'] =      $real['stock_code'] = $value['Symbol'];
            $real['stock_gid'] =  $stock['stock_gid'];
             $real['volume']  = $value['Symbol'];
             $real['add_time']=$value['updateddatetime'];
              $real['open']=$value['current'];
              $real['high']=$value['high'];
              $real['low']=$value['low'];
              $real['close']=$value['close'];
              
            $res  =  pdo_update("real_time_data",$real,$where);
            if(!$res){
                $res  =  pdo_insert("real_time_data",$real);  
                 if(!$res){
                    echo "\n---------- >> 写入价格失败 *** ".$value['Symbol'];
                    // pdo_debug();
                    // die();
                     
                 }else{
                    echo "\n------------- >> 写入价格成功 ".$value['Symbol']." -- " .$value['Symbol'];
                 }
            }else{
                echo " \n-------------更新价格成功 ".$value['Symbol']." -- " .$value['Symbol'];
            }
          }
          
           
        }
    }

// }
