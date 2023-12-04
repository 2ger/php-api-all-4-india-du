<?php
// https://tradingdiario.com/wap/#/tgkline?name=DPHARMA-C5&code=0183&if_zhishu=0
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';

$code = $_GET['code'];

//从数据库查
 $res =  pdo_fetch("select r.*,s.stock_code as code,s.stock_name as name,s.stock_gid as gid,s.id as sid,s.increase_ratio as hcrate from real_time_data r left join stock s on s.stock_code = r.stock_code  where r.stock_code = '".$code."' order by r.id desc ");
//  pdo_debug();
 if($res){
     $res['status'] =1;
    //  if(time()-strtotime($res['add_time']) < 60*30){
        $res['newPrice'] =1;
    //  }
     $res['open'] =$res['close'];
     $res['open_px'] =$res['close'];
     $res['id'] =$res['sid'];
     $res['nowPrice'] =$res['close'];
     $res['today_max'] =$res['close'];
     $res['today_min'] =$res['close'];
     $res['preclose_px'] =$res['close'];
     $res['type'] ='mys';
     $res['business_amount'] =$res['volume'];
    
      die(json_encode($res));
  }

