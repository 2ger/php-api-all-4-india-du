<?php

// 新股没有k线 通过搜索 更新价格 https://tradingdiario.com/api/cloud_pass_search.php?keyWords=5315

header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';
//连接到 Redis 数据库
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(3);

$code = $_GPC['keyWords'];
// echo $url;


$response = $_GPC['response'];

// $response = '[{"label":"SKYWLD","value":"5315","desc":"SKYWORLD DEVELOPMENT BERHAD","price":"0.800","price_change":"-0.000","volume":"0"}]';


if($response){
    $response = str_replace('&quot;','"',$response);
// var_dump($response);
$response = json_decode($response,true);

// var_dump($response);die();

$count = count($response);
    foreach($response as &$value) {
       $redis_data['chinese_stock_name']=    $redis_data['stock_name']=    $stock['stock_spell'] =  $stock['stock_name'] =  $val['name'] = $value['label'];
        $where['stock_code'] =   $redis_data['stock_code']= $real['stock_code'] =     $stock['stock_code'] =  $val['code'] = $value['value'];
       $redis_data['last_done']= $real['close'] =  $real['open'] =   $val['nowPrice'] = $value['price'];
       $redis_data['percent_change']=$val['hcrate'] = number_format($value['price_change']/$val['nowPrice']*100,2);
    
      if($count<5){
          //1 如没有则写入stock表
          $ss = pdo_fetchall("select id,stock_code  from stock where stock_code = '".$val['code']."' order by id asc");
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
          $stock['stock_type'] =  "mys";
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
          $redis_data['last_done']=  $redis_data['lacp']= $redis_data['buy_price']= $redis_data['sell_price']=$redis_data['high']=$redis_data['low']=$value['price'];
          $redis_data['volume']=$redis_data['buy_volume']=$redis_data['sell_volume']= 100;
          $redis_data['change']= $value['price_change'];
        $val['redis'] =   $redis->set('mys'.$value['value'], json_encode($redis_data));
    
          //3写入价格表 
          if($value['price'] >0){ //有价格才写入
              
             $real['volume']  = 100;
             $real['add_time']= date("Y-m-d H:i:s");
             $val['real_time_data'] =      $data['real_time_data']  =  pdo_insert("real_time_data",$real);
          }
          
        //   //删除多余的 > 给任务定时执行
          $id = pdo_insertid();
          pdo_fetch("delete from real_time_data where stock_code = '".$value['value']."' and id < ".$id);
        
      }
      $list[] =$val;
    }
$data['status'] = 0;
$data['data'] = $list;

$data = json_encode($data);
die($data);
}

// if(!$list){
//     //新股上架，搜索不到，手动从详情写入数据库，再从数据库查出来
//     $item = pdo_fetch("SELECT r.*,s.stock_name  FROM `real_time_data` r left join stock s on r.stock_code = s.stock_code WHERE r.stock_code= '".$code."' ");
//      $val['name'] = $item['stock_name'];
//      $val['code'] = $item['stock_code'];
//      $val['nowPrice'] = $item['close'];
//      $val['hcrate'] = number_format(($item['high']-$item['low'])/$item['close']*100,2);
//     $list[] =$val;
// }



$apiUrl = 'https://api.cloudbypass.com/v2/stocks/all.json?term='.$code;

?>

<html>
    <script src="https://cdn.bootcdn.net/ajax/libs/axios/1.3.6/axios.js"></script>
    <script>
        var code = "<?=$code?>"
        
        const config = {
            url: "<?=$apiUrl?>",
            method: "GET",
            headers: {
                "x-cb-apikey": "9df5b13045654eb4b03c4d5a1bdf172e",
                "x-cb-host": "www.klsescreener.com",
            },
        }
        axios(config).then(res => {
            // console.log(res.data);
        
        var dataText = res.data
        console.log(dataText[0]);
        dataText = JSON.stringify(dataText);
                //发给后端更新
        axios.post('https://tradingdiario.com/api/cloud_pass_search.php', "response="+dataText)
         
      
        
        }).catch(err => {
            console.log(err);
        })

    </script>
</html>


