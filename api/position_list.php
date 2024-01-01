<?php
header("Access-Control-Allow-Origin: *");
require '../framework/bootstrap.inc.php';

//当前用户
            //连接到 Redis 数据库
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    $redis->select(0);
    $token = $_SERVER['HTTP_USERTOKEN'];
    $user = json_decode($redis->get($token));
    if(!$user) die("please login ");
    $user_id = $user->id;
    // var_dump($user_id);
    
    $where=" sell_order_id is null ";
    $state = $_GPC['state'];
    if($state==1){
        $where=" sell_order_id  >1 ";
    }
    // var_dump($where);
    

 $list = pdo_fetchall("select *,stock_name as stockName,stock_gid as stockGid,buy_order_price as buyOrderPrice,order_num as orderNum ,
 position_sn as positionSn,
 buy_order_id as buyOrderId,
 order_fee as orderFee,
 order_lever as orderLever,
 order_spread as orderSpread,
 order_total_price as orderTotalPrice
 
 from user_position where user_id = $user_id  and  $where  order by id desc");
//  var_dump($list);
// pdo_debug();
 foreach ($list as &$val){
     $val['now_price'] =  pdo_fetchcolumn("select close from real_time_data where stock_gid = '".$val['stock_gid']."'");
     $stock_type =  pdo_fetchcolumn("select stock_type from stock where stock_gid = '".$val['stock_gid']."'");
     
     
    //  pdo_debug();die();
    if($stock_type == "india"){
         $profit = ($val['now_price']-$val['buyOrderPrice'])*$val['orderNum'];
    }else{
        //买涨买跌
        $profit = ($val['now_price']-$val['buyOrderPrice'])*$val['orderNum']*$val['orderLever'];
        if ($order['position_type']) {
            $profit = ($val['buyOrderPrice']-$val['now_price'])*$val['orderNum']*$val['orderLever'];
        }

    }  
    $profit = round($profit,2);
    
    if($val['position_type']) $profit =  ($val['buyOrderPrice']-$val['now_price'])*$val['orderNum'];
     $val['profitAndLose'] = $profit;
     $val['allProfitAndLose'] =  -$val['order_fee']+$profit;
    //  $val['allProfitAndLose']=sprintf("%2f",$val['allProfitAndLose']);
     
 }
 if($list){
        $res['status'] = 0;
        $res['msg'] = "success";
        $res['data']['total'] = count($list);
        $res['data']['list'] = $list;
        die(json_encode($res));
    }else{
         $res['status'] = 1;
        $res['msg'] = "no data";
        die(json_encode($res));
    }