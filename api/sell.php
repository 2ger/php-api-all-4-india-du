<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers:*');
require '../framework/bootstrap.inc.php';


$positionSn = $_GPC['positionSn'];
//当前用户
//连接到 Redis 数据库
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(0);
$token = $_SERVER['HTTP_USERTOKEN'];
$user = json_decode($redis->get($token));
if (!$user){
    $res['status'] = 1;
    $res['msg'] = "please login!";
    die(json_encode($res));
}
// print_r($user);
$user_id = $user->id;
$isLock = $user->isLock;

if($isLock){
    $res['status'] = 1;
    $res['msg'] = "Your are locked for trading!";
 //   die(json_encode($res));
}

//TODO 时间判断

//查询订单、价格、股票类型
$order = pdo_fetch("select o.*,s.stock_type,r.close from user_position as o left join stock as s on s.stock_gid = o.stock_gid left join real_time_data r on r.stock_gid = o.stock_gid  where o.position_sn = '" . $positionSn . "'");

if (!$order) {
    $res['status'] = 1;
    $res['msg'] = "can not find this position!";
    die(json_encode($res));
}
if(floatval($order['close'])<1){
   $res['status'] = 1;
    $res['msg'] = "please try later!";
    die(json_encode($res));
}

$buy_price=floatval($order['buy_order_price']);
$close_price=floatval($order['close']);
$order_num=floatval($order['order_num']);
$order_level=floatval($order['order_lever']);
// $aaa=[$buy_price,$close_price,$order_num,$order_level];
//计算赢利 - 股票
if ($order['position_type']) {
    $profit = ($buy_price - $close_price) * $order_num;

} else {
    $profit = ($close_price - $buy_price) * $order_num;

}
$benjin = $order['order_total_price'];

//计算赢利  - 指数
if ($order['stock_type'] != "india") {
    //买涨买跌
    $profit = $profit * $order_level;
}

//计算赢利  -外汇
if ($order['stock_type'] == "Forex") {
    //买涨买跌
    $profit *=$_W['config']['usd']['inr'];
}


$val['profitAndLose'] = $profit;
$val['allProfitAndLose'] = -$order['order_fee'] + $profit;

$now_time = date("Y-m-d H:i:s", time());
//发赢得

//更新订单
$up_position = [
    'profit_and_lose' => $val['profitAndLose'],
    'all_profit_and_lose' => $val["allProfitAndLose"],
    "sell_order_id" => date("YmdHis", time()) . rand(0, 9),
    "sell_order_price" => $order['close'],
    "sell_order_time" => $now_time,
    "order_stay_days" => floor((strtotime($now_time) - strtotime($order['buy_order_time'])) / 86400),
];

$user_amt = pdo_get("user", ["id" => $user_id], ["user_amt", "enable_amt", 'djzj']);

// //下单时间判断
if ($order['stock_type'] != "Forex") {
  
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
$position_up_where = ["id" => $order['id']];
$user_up_where = ["id" => $user_id];

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
// var_dump(pdo_debug());

die(json_encode($res));
