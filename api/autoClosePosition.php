<?php
//自动平仓， 每1分钟
// https://etorhome.com/api/autoClosePosition.php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers:*');
require '../framework/bootstrap.inc.php';


//连接到 Redis 数据库
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(3);


//查询订单、价格、股票类型
$order = pdo_fetchall("select o.*,s.stock_type,r.close from user_position as o left join stock as s on s.stock_gid = o.stock_gid left join real_time_data r on r.stock_gid = o.stock_gid  where o.sell_order_id is null and (o.profit_target_price >0 || o.stop_target_price >0)");
echo count($order)."个订单";
foreach ($order as $val){
    $close = 0;//是否触发平仓
    //从redis取价格
$redData = $redis->get($val['stock_gid']);
$redData = json_decode($redData,true);
// var_dump($redData);

$close_price= $redData->last_done;//floatval($val['close']);
if(!$close_price) $close_price=$redData['last_done'];//floatval($val['close']);
   echo $val['stock_code']." 当前价格:".$close_price."\n";
   
if($close_price>0){
    $close_price = $redData['last_done'];
   echo "买入价格:".$val['buy_order_price']."\n";
   echo "profit_target_price:".$val['profit_target_price']."\n";
   echo "stop_target_price:".$val['stop_target_price']."\n";
   
   $buy_price=floatval($val['buy_order_price']);
$order_num=floatval($val['order_num']);
$order_level=floatval($val['order_lever']);
// $aaa=[$buy_price,$close_price,$order_num,$order_level];
//计算赢利 - 股票
if ($val['position_type']) {
    
echo  "买跌\n";
  if($close_price < $val['profit_target_price'] && $val['profit_target_price']>0){
        $close_price = $val['profit_target_price'];
        $close = 1;//是否触发平仓
echo  "profit_target_price触发\n";
        
        
    }
    if($close_price > $val['stop_target_price'] && $val['stop_target_price']>0){
        $close_price = $val['stop_target_price'];
        $close = 1;//是否触发平仓
echo  "stop_target_price触发\n";
    }
    
    $profit = ($buy_price - $close_price) * $order_num;

} else {//买涨
echo  "买涨\n";
   
 if($close_price > $val['profit_target_price'] && $val['profit_target_price']>0){
        $close_price = $val['profit_target_price'];
echo  "profit_target_price触发\n";
        $close = 1;//是否触发平仓
        
    }
    if($close_price < $val['stop_target_price'] && $val['stop_target_price']>0){
        $close = 1;//是否触发平仓
echo  $close_price."<" . $val['stop_target_price']." stop_target_price触发\n";
        $close_price = $val['stop_target_price'];
    }
   
    $profit = ($close_price - $buy_price) * $order_num;
}

if($close){
    echo  $val['id']."触发平仓\n";
    
$benjin = $val['order_total_price'];

//计算赢利  - 指数
if ($val['stock_type'] != "india") {
    //买涨买跌
    $profit = $profit * $order_level;
}

//计算赢利  -外汇
if ($val['stock_type'] == "Forex") {
    //买涨买跌
    $profit *=$_W['config']['usd']['inr'];
}


$val['profitAndLose'] = $profit;
$val['allProfitAndLose'] = -$val['order_fee'] + $profit;

$now_time = date("Y-m-d H:i:s", time());
//发赢得

//更新订单
$up_position = [
    'profit_and_lose' => $val['profitAndLose'],
    'all_profit_and_lose' => $val["allProfitAndLose"],
    "sell_order_id" => date("YmdHis", time()) . rand(0, 9),
    "sell_order_price" => $close_price,
    "sell_order_time" => $now_time,
    "order_stay_days" => floor((strtotime($now_time) - strtotime($val['buy_order_time'])) / 86400),
];

$user_amt = pdo_get("user", ["id" => $user_id], ["user_amt", "enable_amt", 'djzj']);

// //下单时间判断
if ($val['stock_type'] != "Forex") {
  
//已在config.php定义时区
    $begin_time=strtotime("09:15:00");
    $end_time=strtotime("15:30:00");
    
     if($begin_time>time()||$end_time<time()){
        $res=[
            'status'=>1,
            'msg'=>'not during the deal time',
            'data'=>''
            ];
        die(json_encode($res));
    }
}
$djzj = $user_amt["djzj"] - $benjin;
if($djzj<0) $djzj =0;
$up_user = [
    "enable_amt" => $user_amt["enable_amt"] + $profit + $benjin,
    "user_amt" => $user_amt["user_amt"] + $profit,
    "djzj" => $djzj
];
// var_dump($up_user);
$position_up_where = ["id" => $val['id']];
$user_up_where = ["id" => $user_id];

// echo  "n\nupdate\n";
// var_dump($up_position);
// echo  "\n where\n";
// var_dump($position_up_where);


pdo_begin();
try {
    pdo_update("user_position", $up_position, $position_up_where);
    pdo_update("user", $up_user, $user_up_where);
    $res['status'] = 0;
    $res['msg'] = "sell order success";
    pdo_commit();
} catch (PDOException $exception) {
    $res['status'] = 1;
    $res['msg'] = "sell order failed";
    pdo_rollback();

}
    
}

}

    
}



die();
