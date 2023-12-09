<?php
//持机 每1分钟
// 采集印度指数 https://profitmarts.in/api/cai_india_index_list.php
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';


//连接到 Redis 数据库
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(3);

$url = 'https://etmarketsapis.indiatimes.com/ET_Stats/getAllIndices?exchange=nse&sortby=value&sortorder=desc&pagesize=100';//nse
$url2 = 'https://etmarketsapis.indiatimes.com/ET_Stats/getAllIndices?exchange=bse&sortby=value&sortorder=desc&pagesize=100';//bse
// getStocks($url);
// getStocks($url2);
// pdo_debug();

// function getStocks($url){
echo $url."\n";
    
    $response = file_get_contents($url);
    
    $response = json_decode($response,true);
     $response =$response['searchresult'];
    $count = count($response);
    
echo $count." 个\n";
    
    if($response){
        foreach($response as &$value) {
             $stock['stock_spell'] =   $value['scripCode1GivenByExhange'];
              $stock['stock_plate'] =   $value['exchangeId'];
              
            $redis_data['stock_name']=     $redis_data['chinese_stock_name']=    $stock['stock_name'] = $value['indexName'];
            $where['stock_code'] =   $redis_data['stock_code']= $real['stock_code'] =     $stock['stock_code'] =  $value['indexId'];
           $redis_data['last_done']=  $value['currentIndexValue'];
           $redis_data['percent_change']= $stock['increase_ratio']= $value['perChange'];
        
              //1 如没有则写入stock表
              $ss = pdo_fetch("select id,stock_code  from stock where stock_code = '".$value['indexId']."' order by id asc");
              $id = $ss['id'];
              
              $stock['stock_type'] =  $value['exchange'];
             $real['stock_gid'] = $stock['stock_gid'] =  "mys".$value['indexId'];
              
              if(!$ss){
                  $data['stock']  = pdo_insert("stock",$stock);
                   $id = pdo_insertid();
                    echo "\n >> 写入 股票 成功 ".$value['indexId'];
                }else{
                    echo " >> 更新 股票 成功 ".$value['indexId'];
                $res  =  pdo_update("stock",$stock,$where);
                 $id = $ss['id'];
              }
         
              //2 写入redis
          $redis_data['id']= $id;
          $redis_data['created_on']= date("Y-m-d H:i:s");
          $redis_data['market']= $value['exchange'];
          $redis_data['last_done']=  $redis_data['lacp']= $redis_data['buy_price']= $redis_data['sell_price']=$redis_data['high']=$redis_data['low']=$value['currentIndexValue'];
          $redis_data['volume']=$redis_data['buy_volume']=$redis_data['sell_volume']= $value['indexId'];
          $redis_data['business_balance']=  $redis_data['change']= $value['perChange'];
          
        $redis->set($stock['stock_gid'], json_encode($redis_data));
        //读取redis
        //   echo $rvalue;
    
          //3写入价格表 
          if($value['currentIndexValue'] >0){ //有价格才写入
              
            $where['stock_code'] =      $real['stock_code'] = $value['indexId'];
            $real['stock_gid'] =  $stock['stock_gid'];
             $real['volume']  = $value['indexId'];
             $real['add_time']= date("Y-m-d H:i:s");
              $real['open']=$value['openIndexValue'];
              $real['high']=$value['highIndexValue'];
              $real['low']=$value['lowIndexValue'];
              $real['close']=$value['currentIndexValue'];
              
            $res  =  pdo_update("real_time_data",$real,$where);
            if(!$res){
                $res  =  pdo_insert("real_time_data",$real);  
                 if(!$res){
                    echo " >> 写入价格失败 *** ".$value['seoName'];
                    pdo_debug();
                    die();
                     
                 }else{
                    echo " >> 写入价格成功 ".$value['seoName']." -- " .$value['indexId'];
                 }
            }else{
                echo " 更新价格成功 ".$value['seoName']." -- " .$value['indexId'];
            }
          }
          
           
        }
    }

// }

echo $url2."\n";
    
    $response = file_get_contents($url2);
    
    $response = json_decode($response,true);
     $response =$response['searchresult'];
    $count = count($response);
    
echo $count." 个\n";
    
    if($response){
        foreach($response as &$value) {
             $stock['stock_spell'] =   $value['scripCode1GivenByExhange'];
              $stock['stock_plate'] =   $value['exchangeId'];
              
            $redis_data['stock_name']=     $redis_data['chinese_stock_name']=    $stock['stock_name'] = $value['indexName'];
            $where['stock_code'] =   $redis_data['stock_code']= $real['stock_code'] =     $stock['stock_code'] =  $value['indexId'];
           $redis_data['last_done']=  $value['currentIndexValue'];
           $redis_data['percent_change']= $stock['increase_ratio']= $value['perChange'];
        
              //1 如没有则写入stock表
              $ss = pdo_fetch("select id,stock_code  from stock where stock_code = '".$value['indexId']."' order by id asc");
              $id = $ss['id'];
              
              $stock['stock_type'] =  "NSE";
             $real['stock_gid'] = $stock['stock_gid'] =  "mys".$value['indexId'];
              
              if(!$ss){
                  $data['stock']  = pdo_insert("stock",$stock);
                   $id = pdo_insertid();
                    echo "\n >> 写入 股票 成功 ".$value['indexName'];
                }else{
                    echo " >> 更新 股票 成功 ".$value['indexName'];
                $res  =  pdo_update("stock",$stock,$where);
                 $id = $ss['id'];
              }
         
                 //2 写入redis
          $redis_data['id']= $id;
          $redis_data['created_on']= date("Y-m-d H:i:s");
          $redis_data['market']= $value['exchange'];
          $redis_data['last_done']=  $redis_data['lacp']= $redis_data['buy_price']= $redis_data['sell_price']=$redis_data['high']=$redis_data['low']=$value['currentIndexValue'];
          $redis_data['volume']=$redis_data['buy_volume']=$redis_data['sell_volume']= $value['indexId'];
          $redis_data['business_balance']=  $redis_data['change']= $value['perChange'];
          
        $redis->set($stock['stock_gid'], json_encode($redis_data));
        //读取redis
        //   echo $rvalue;
    
          //3写入价格表 
          if($value['currentIndexValue'] >0){ //有价格才写入
              
            $where['stock_code'] =      $real['stock_code'] = $value['indexId'];
            $real['stock_gid'] =  $stock['stock_gid'];
             $real['volume']  = $value['indexId'];
             $real['add_time']= date("Y-m-d H:i:s");
              $real['open']=$value['openIndexValue'];
              $real['high']=$value['highIndexValue'];
              $real['low']=$value['lowIndexValue'];
              $real['close']=$value['currentIndexValue'];
              
            $res  =  pdo_update("real_time_data",$real,$where);
            if(!$res){
                $res  =  pdo_insert("real_time_data",$real);  
                 if(!$res){
                    echo " >> 写入价格失败 *** ".$value['seoName'];
                    pdo_debug();
                    die();
                     
                 }else{
                    echo " >> 写入价格成功 ".$value['seoName']." -- " .$value['indexId'];
                 }
            }else{
                echo " 更新价格成功 ".$value['seoName']." -- " .$value['indexId'];
            }
          }
          
           
        }
           
    }

