<?php
//直接查询马来市场
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';
//连接到 Redis 数据库
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(3);
$market ="india";
$code = $_GPC['keyWords'];

if($_GPC['hot'])
{
    $market ="NSE";
}
//先搜索网上，更新或采集股票再搜索数据库
if($code){
    $code2 = urlencode($code);
    //在线搜索公司名
    //私得公司名称、id
    $url = "https://economictimes.indiatimes.com/stocksearch.cms?ticker=shri%20ja";
    
    //有价格，公司名称
    $url = 'https://etsearch.indiatimes.com/etspeeds/etsearchMdata.ep?matchCompanyName=true&realstate=true&dvr=true&idr=true&trust=true&mcx=true&mf=true&crypto=true&nps=true&insideet=true&detail=false&forex=false&index=true&mecklai=true&etf=true&nonList=true&pagesize=6&outputtype=json&callback=searchResultCallback&ticker='.$code2;
    // echo $url;
    $response = file_get_contents($url);
    $response = str_replace("searchResultCallback(","",$response);
    $response = str_replace(")","",$response);
    
    // var_dump($response);die();
    
    $response = json_decode($response,true);
    // var_dump($response);die();
    
    $count = count($response);
    
    if($response){
        foreach($response as &$value) {
            if($value['lastTradedPrice']){
           $redis_data['chinese_stock_name']=    $redis_data['stock_name']=     $stock['stock_name'] =  $val['stock_name'] = $value['tagName'];
            $stock['stock_spell'] =   $value['symbol'];
            $stock['stock_plate'] =   $value['tagSeoName'];
            $where['stock_code'] =   $redis_data['stock_code']= $real['stock_code'] =     $stock['stock_code'] =  $val['stock_code'] = $value['tagId'];
           $redis_data['last_done']= $real['close'] =  $real['open'] =   $val['close'] = $value['lastTradedPrice'];
           $redis_data['percent_change']=$val['increase_ratio'] = $value['NetChange'];
        
            //写入或更新
              $s = pdo_get("stock",$where);
              $stock['stock_type'] =  "india";
             $real['stock_gid'] = $stock['stock_gid'] =  "mys".$stock['stock_code'];
              
              if(!$s){
                  $data['stock']  = pdo_insert("stock",$stock);
                  $val['stock'] =        $id = pdo_insertid();
              }else{
                   pdo_update("stock",$stock,$where);
                 $id = $s['id'];
              }
         
              // $rvalue = $redis->get($real['stock_gid']);
              // if(!$rvalue){}
              //2 写入redis
              $redis_data['id']= $id;
              $redis_data['created_on']= date("Y-m-d H:i:s");
              $redis_data['market']=  "Main MARKET";
              $redis_data['last_done']=  $redis_data['lacp']= $redis_data['buy_price']= $redis_data['sell_price']=$redis_data['high']=$redis_data['low']=$value['lastTradedPrice'];
              $redis_data['volume']=$redis_data['buy_volume']=$redis_data['sell_volume']= $value['volume'];
              $redis_data['change']= $value['NetChange'];
            $val['redis'] =   $redis->set('mys'.$value['tagId'], json_encode($redis_data));
        
              //3写入价格表 
              if($value['lastTradedPrice'] >0){ //有价格才写入
                  
                 $real['volume']  = $value['volume'];
                 $real['add_time']= date("Y-m-d H:i:s");
                  $val['real_time_data update'] =     $res =  pdo_update("real_time_data",$real,$where); 
                if(!$res){ 
                    $val['real_time_data insert'] =      $data['real_time_data']  =  pdo_insert("real_time_data",$real);
                }
              }
              
            
        //   }
        //   $list[] =$val;
        }
        }
        
        if($list){
            $data['status'] = 0;
            $data['data'] = $list;
        }else{
            $data['status'] = 1;
            $data['msg'] = "no data";
        }
            // $data = json_encode($data);
            // die($data);
    }
    
    
}

$pageSize = $_GPC['pageSize'];
$pageNum = $_GPC['pageNum'];

$offset = ($pageNum-1)*$pageSize;

$list = pdo_fetchall("SELECT r.*,s.*  FROM stock s left join `real_time_data` r on r.stock_code = s.stock_code WHERE (s.stock_code like '%".$code."%' or s.stock_spell like '%".$code."%' or s.stock_name like '%".$code."%') and s.stock_type like '%$market%'  group by s.stock_code  order by s.is_show desc,s.id limit $pageSize OFFSET $offset");//

if($list){
    $data['status'] = 0;
    $data['data'] = $list;
}else{
    $data['status'] = 1;
    $data['msg'] = "no data";
}



// pdo_debug();

$data = json_encode($data);
echo $data;




