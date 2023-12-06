<?php
//直接查询马来市场
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';
//连接到 Redis 数据库
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(3);

$code = $_GPC['keyWords'];
if($code){
    $url = 'https://etsearch.indiatimes.com/etspeeds/etsearchMdata.ep?matchCompanyName=true&realstate=true&dvr=true&idr=true&trust=true&mcx=true&mf=true&crypto=true&nps=true&insideet=true&detail=false&forex=false&index=true&mecklai=true&etf=true&nonList=true&pagesize=6&outputtype=json&callback=searchResultCallback&ticker='.$code;
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
            if(!$value['subType']){
           $redis_data['chinese_stock_name']=    $redis_data['stock_name']=     $stock['stock_name'] =  $val['stock_name'] = $value['tagName'];
            $stock['stock_spell'] =   $value['symbol'];
            $stock['stock_plate'] =   $value['tagSeoName'];
            $where['stock_code'] =   $redis_data['stock_code']= $real['stock_code'] =     $stock['stock_code'] =  $val['stock_code'] = $value['tagId'];
           $redis_data['last_done']= $real['close'] =  $real['open'] =   $val['close'] = $value['lastTradedPrice'];
           $redis_data['percent_change']=$val['increase_ratio'] = $value['NetChange'];
        
          if($count<5){
              //1 如没有则写入stock表
              $ss = pdo_fetchall("select id,stock_code  from stock where stock_code = '".$val['stock_code']."' order by id asc");
              $count =  count($ss);
              $id = $ss[0]['id'];
              if($count >1){
                 //删除多余的
                 $where["id >"] =$id;
                 pdo_delete("stock",$where);
              }
              // echo $ss[0]['id'];
            //   var_dump($ss);die();
              $s = $ss[0];//pdo_get("stock",$where);
              $stock['stock_type'] =  "india";
             $real['stock_gid'] = $stock['stock_gid'] =  "mys".$stock['stock_code'];
              
              if(!$s){
                  $data['stock']  = pdo_insert("stock",$stock);
             $val['stock'] =        $id = pdo_insertid();
              }else{
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
            $val['redis'] =   $redis->set('mys'.$value['lastTradedPrice'], json_encode($redis_data));
        
              //3写入价格表 
              if($value['lastTradedPrice'] >0){ //有价格才写入
                  
                 $real['volume']  = $value['volume'];
                 $real['add_time']= date("Y-m-d H:i:s");
                 $val['real_time_data'] =      $data['real_time_data']  =  pdo_insert("real_time_data",$real);
              }
              
            //   //删除多余的 > 给任务定时执行
            //   $id = pdo_insertid();
            //   pdo_fetch("delete from real_time_data where stock_code = '".$value['value']."' and id < ".$id);
            
          }
          $list[] =$val;
        }
        }
    }
    
    
}


$pageSize = $_GPC['pageSize'];
$pageNum = $_GPC['pageNum'];

$offset = ($pageNum-1)*$pageSize;

$list = pdo_fetchall("SELECT r.*,s.*  FROM stock s left join `real_time_data` r on r.stock_code = s.stock_code WHERE (s.stock_code like '%".$code."%' or s.stock_spell like '%".$code."%' or s.stock_name like '%".$code."%') and s.stock_type like '%india%'  group by s.stock_code limit $pageSize ");//OFFSET $offset

$data['status'] = 0;
$data['data'] = $list;

// pdo_debug();

$data = json_encode($data);
echo $data;




