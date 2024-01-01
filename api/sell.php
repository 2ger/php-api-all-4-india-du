<?php
//用平仓
header("Access-Control-Allow-Origin: *");
require '../framework/bootstrap.inc.php';


$positionSn = $_GPC['positionSn'];
//当前用户
//连接到 Redis 数据库
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(0);
$token = $_SERVER['HTTP_USERTOKEN'];
$user = json_decode($redis->get($token));
if (!$user) die("please login ");
$user_id = $user->id;

//TODO 时间判断

//查询订单、价格、股票类型
$order = pdo_fetch("select o.*,s.stock_type,r.close from user_position as o left join stock as s on s.stock_gid = o.stock_gid left join real_time_data r on r.stock_gid = o.stock_gid  where o.position_sn = '" . $positionSn . "'");
//var_dump($order);
if (!$order) {
    $res['status'] = 1;
    $res['msg'] = "can not find this position!";
    die(json_encode($res));
}
//计算赢利 - 股票
$profit = ($order['close'] - $order['buy_order_price']) * $order['order_num'];


if ($order['position_type']) {
    $profit = ($order['buy_order_price'] - $order['close']) * $order['order_num'];
}

//计算赢利  - 指数
if ($order['stock_type'] != "india") {
    //买涨买跌 
    $profit = ($order['close']-$order['buy_order_price'])*$order['order_num']*$order['order_lever'];
    if ($order['position_type']) {
        $profit = ($order['buy_order_price']-$order['close'])*$order['order_num']*$order['order_lever'];
    }
}  
    $profit = round($profit,2);
    

$val['profitAndLose'] = $profit;
$val['allProfitAndLose'] = -$val['order_fee'] + $profit;

$now_time = date("Y-m-d H:i:s", time());
//发赢得
$buy_time = new DateTime($order['buy_order_time']);
$sel_time = new DateTime($now_time);

//更新订单
$up_position = [
    'profit_and_lose' => $val['profitAndLose'],
    'all_profit_and_lose' => $val["allProfitAndLose"],
    "sell_order_id" => date("YmdHis", time()) . rand(0, 9),
    "sell_order_price" => $order['close'],
    "sell_order_time" => $now_time,
    "order_stay_days" => floor(strtotime($now_time) - strtotime($order['buy_order_time']) / 86400),
];

// var_dump($up_position);

$enable_amt = pdo_fetchcolumn("select enable_amt from user where id = $user_id");
$up_user = [
    "enable_amt" => $enable_amt + $profit
];
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


die(json_encode($res));
