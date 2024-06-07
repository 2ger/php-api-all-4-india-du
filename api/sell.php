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
    // die(json_encode($res));
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
$order = pdo_fetch("select o.*,s.stock_type,r.close from user_position as o left join stock as s on s.stock_gid = o.stock_gid left join real_time_data r on r.stock_gid = o.stock_gid  where o.sell_order_id is null and  o.position_sn = '" . $positionSn . "'");
if (!$order) {
    $res['status'] = 1;
    $res['msg'] = "can not find this position!";
    die(json_encode($res));
}

if($order) $user_id = $order['user_id'];

//从redis取价格
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(3);
$redData = $redis->get($order['stock_gid']);
$redData = json_decode($redData,true);
// $redData['last_done'];
if($redData['last_done']){
   $order['close'] = $redData['last_done'];
}
//从redis取价格  end
//后台， loss, win
$op = $_GPC['op'];
if($op == 'loss'){
    if(floatval($order['stop_target_price'])<1){
       $res['status'] = 1;
        $res['msg'] = "无止损设置!";
        die(json_encode($res));
    }
    $order['close'] = $order['stop_target_price'];
}
if($op == 'win'){
       if(floatval($order['profit_target_price'])<1){
   $res['status'] = 1;
    $res['msg'] = "无止盈设置!";
    die(json_encode($res));
    }
            $order['close'] = $order['profit_target_price'];
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

$user_amt = pdo_get("user", ["id" => $user_id]);

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
    "user_amt" => $user_amt["user_amt"] + $profit+ $benjin,
    "djzj" => $djzj
];
$position_up_where = ["id" => $order['id']];
$user_up_where = ["id" => $user_id];
// var_dump($benjin);
// var_dump($user_up_where);die();

pdo_begin();
try {
    pdo_update("user_position", $up_position, $position_up_where);
    pdo_update("user", $up_user, $user_up_where);
    
    
    $amt = $profit+ $benjin;
    add_cash_detail($user_amt, $order['id'],"Sell",$amt,"本金：$benjin + 收益：$profit");
    
    $res['status'] = 0;
    $res['msg'] = "sell order success";
    pdo_commit();
} catch (PDOException $exception) {
    $res['status'] = 1;
    $res['msg'] = "sell order failed";
    pdo_rollback();

}
// var_dump(pdo_debug());


function add_cash_detail($user,$position_id=0,$type,$amt,$detail){

$de_summary = "当前余额：". $user['enable_amt']." > 变更:".$amt." >后余额: ".($user['enable_amt']+$amt)." > 详情：";
$de_summary .= $detail;

    $data['user_id'] = $user['id'];
    $data['user_name'] = $user['real_name'];
    $data['agent_name'] = $user['agent_name'];
    $data['agent_id'] = $user['agent_id'];
    
    $data['position_id'] = $position_id;
    $data['de_type'] = $type;
    $data['de_amt'] = $amt;
    $data['de_summary'] = $de_summary;
    $data['add_time'] = date("Y-m-d H:i:s");
    pdo_insert("user_cash_detail", $data);
    
}

die(json_encode($res));
