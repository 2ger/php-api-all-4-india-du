<?php
//批量获得阿里价格
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';
//连接到 Redis 数据库
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(3);


$list = pdo_fetchall("SELECT *  FROM stock where stock_type like '%Forex%'");
$symbols = "USDT";
foreach ($list as $val){
    $symbols = $symbols.",".$val["stock_code"];
}

    $host = "http://alirmcom2.market.alicloudapi.com";
    //或 http://alirm-com.konpn.com
    $path = "/query/comrms";
    $method = "GET";
    $appcode = "96889731946c48f3af78b44494bf2bdd";
    $headers = array();
    array_push($headers, "Authorization:APPCODE " . $appcode);
    $symbols = htmlspecialchars($symbols);
    $querys = "symbols=$symbols";
    $bodys = "";
    $url = $host . $path . "?" . $querys;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_FAILONERROR, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    if (1 == strpos("$".$host, "https://"))
    {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    }
    // var_dump(curl_exec($curl));
    
    $response = json_decode(curl_exec($curl),true);
     $response =$response['Obj'];
    $count = count($response);
    // var_dump($response);die();
    
echo $count." 个\n";
    // die();
    if($response){
        foreach($response as &$value) {
            
            $redis_data['stock_name']=     $redis_data['chinese_stock_name']=    
            $where['stock_code'] =   $redis_data['stock_code']= $real['stock_code'] =     $value['S'];    
           $redis_data['last_done']=  $value['P'];
           $redis_data['percent_change']= $stock['increase_ratio']=  $value['VF'];
        
              //1 如没有则写入stock表
              $ss = pdo_fetch("select id,stock_code  from stock where stock_code = '".$value['S']."' order by id asc");
              $id = $ss['id'];
              
              
                    echo " >> 更新 股票 成功 ".$value['S'];
                $res  =  pdo_update("stock",$stock,$where);
                 $id = $ss['id'];
              
              
           
              //2 写入redis
          $redis_data['id']= $id;
          $redis_data['created_on']= date("Y-m-d H:i:s");
          $redis_data['market']= $value['VF'];
          $redis_data['last_done']=  $redis_data['lacp']= $redis_data['buy_price']= $redis_data['sell_price']=$redis_data['high']=$redis_data['low']=$value['P'];
          $redis_data['volume']=$redis_data['buy_volume']=$redis_data['sell_volume']= $value['S'];
          $redis_data['business_balance']=  $redis_data['change']= $value['VF'];
          
        $redis->set($stock['stock_gid'], json_encode($redis_data));
        //读取redis
        //   echo $rvalue;
    
          //3写入价格表 
          if($value['P'] >0){ //有价格才写入
              
            $where['stock_code'] =      $real['stock_code'] = $value['S'];
            // $real['stock_gid'] =  $stock['stock_gid'];
             $real['stock_gid'] = "mys".$value['S'];
             $real['volume']  = $value['S'];
             $real['add_time']= date("Y-m-d H:i:s");
              $real['open']=$value['O'];
              $real['high']=$value['H'];
              $real['low']=$value['L'];
              $real['close']=$value['P'];
              
            $res  =  pdo_update("real_time_data",$real,$where);
            if(!$res){
                $res  =  pdo_insert("real_time_data",$real);  
                 if(!$res){
                    echo " >> 写入价格失败 *** ".$value['P'];
                    pdo_debug();
                    die();
                     
                 }else{
                    echo " >> 写入价格成功 ".$value['P']." -- " .$value['S'];
                 }
            }else{
                echo " 更新价格成功 ".$value['P']." -- " .$value['S'];
            }
          }
          
           
        }
    }
?>