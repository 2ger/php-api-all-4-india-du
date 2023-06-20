<?php
//更新单个马股价格 
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';

$code = $_GET['code'];

$url = "https://www.klsescreener.com/v2/stocks/chart/$code/embedded/1y";  

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
   $pattern = '/data = (.*?)\/\/ split the data set into ohlc and volume/s';
    preg_match($pattern, $response, $matches);
    $content = $matches[1];
    $content = str_replace(array("\r\n", "\r", "\n", "\t", ";"), "", $content);
    $content = str_replace(array("],        ]"), "]]", $content);
    
    var_dump($response);die();
    $content = json_decode($content,true);
    // echo count($content);
    $count = count($content);
    $val = $content[$count-1];
    if($val[4] >0){
         $data['stock_code']= $code;
         $data['stock_gid']= "mys".$code;
         $data['open']= $val[4];
         $data['close']= $val[4];
         $data['high']= $val[2];
         $data['low']= $val[3];
         $data['volume']='100';// $val[5]
         $data['timestamp']= date('Y-m-d H:i:s',time());
         $data['add_time']=  date('Y-m-d H:i:s',time());
         
         $res =  pdo_insert("real_time_data",$data);
         $data['status'] = $res;
    
    //连接到 Redis 数据库
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    $redis->select(3);
 //2 写入redis
 
    //  "id": 20491091,
    // "created_on": "2023-05-09 08:31:19",
    // "stock_name": "CEB",
    // "market": "Main MARKET",
    // "rem": "",
    // "stock_code": "5311",
    // "last_done": 1.31,
    // "lacp": 1.32,
    // "change": -0.01,
    // "percent_change": -0.76,
    // "volume": 12948,
    // "buy_volume": 933,
    // "sell_volume": 200,
    // "buy_price": 1.3,
    // "sell_price": 1.32,
    // "high": 1.33,
    // "low": 1.29
    
    
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
       
    }
            
// if($id) echo "更新成功";
    // data[i][0], // the date
    // data[i][1], // open
    // data[i][2], // high
    // data[i][3], // low
    // data[i][4] // close
    // data[i][0], // the date
    // data[i][5] // the volume
