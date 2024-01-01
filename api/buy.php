<?php
header("Access-Control-Allow-Origin: *");
require '../framework/bootstrap.inc.php';


$buyNum = $_GPC['buyNum'];
$lever = $_GPC['lever'];
$buyType = $_GPC['buyType'];
$stockId = $_GPC['stockId'];
$profitTarget = $_GPC['profitTarget'];
$stopLoss = $_GPC['stopLoss'];

// 判断周末不能买

// $date = date('Y-m-d'); // 获取当前日期
// if (date('N', strtotime($date)) >= 6) {
//       $res['status'] = 1;
//         $res['msg'] = "not in weekend!";
//         die(json_encode($res));
// }

// //下单时间判断
// $currentDateTime = new DateTime('now', new DateTimeZone('Indian/Comoro')); // 替换为您的时区
// $startDateTime = new DateTime('09:30', new DateTimeZone('Indian/Comoro')); // 替换为您的时区
// $endDateTime = new DateTime('13:30', new DateTimeZone('Indian/Comoro')); // 替换为您的时区

// if ($currentDateTime >= $startDateTime && $currentDateTime <= $endDateTime) {
//     // echo "当前时间在9:30到13:30之间";
//     $res['status'] = 1;
//     $res['msg'] = "not in trading hours!";
//     die(json_encode($res));
// }

//实名认证

//当前价格
$stock = pdo_fetch("select s.*,r.close from stock s left join real_time_data r on s.stock_gid= r.stock_gid where s.id = $stockId");
if (!$stock) die("no stock found ");
// var_dump($stock);die();
//当前用户
//连接到 Redis 数据库
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(0);
$token = $_SERVER['HTTP_USERTOKEN'];
$user = json_decode($redis->get($token));
if (!$user) die("please login ");
$user_id = $user->id;

//金额计算
$order_total_price = $stock['close'] * $buyNum / $lever;
$order_fee = $order_total_price / 10000;
$spread_fee = 0;
if ($stock['stock_type'] != "india") {
    //指数、外江
//    $order_total_price = $stock['close'] * $buyNum / $lever;
    $order_fee = 50* $buyNum;
    $order_total_price =1000* $buyNum;
    $spread_fee = 0;
    $position['spread_rate_price'] = 0;

}

//余额判断
$enable_amt = pdo_fetchcolumn("select enable_amt from user where id = $user_id");
if ($enable_amt < ($order_total_price + $order_fee + $spread_fee)) {
    $res['status'] = 1;
    $res['msg'] = "No Available Amount";
} else {
    $whereu['id'] = $user_id;
    $updateu["enable_amt"] = $enable_amt - ($order_total_price + $order_fee + $spread_fee);
    pdo_update("user", $updateu, $whereu);
}


//下单

$position['user_id'] = $user_id;
$position['nick_name'] = $user->nickName;
$position['agent_id'] = $user->agentId;
$position['position_type'] = $buyType;
$position['order_direction'] = $buyType ? "Buy Down" : "Buy Up";
$position['position_sn'] = time() . $user_id;
$position['order_num'] = $buyNum;
$position['order_lever'] = $lever;
$position['order_total_price'] = $order_total_price;
$position['order_fee'] = $order_fee;

$position['stock_name'] = $stock['stock_name'];
$position['stock_code'] = $stock['stock_code'];
$position['stock_gid'] = $stock['stock_gid'];
$position['stock_spell'] = $stock['stock_spell'];
$position['buy_order_price'] = $stock['close'];

$position['buy_order_id'] = date("YmdHis");
$position['buy_order_time'] = date("Y-m-d H:i:s");
$position['all_profit_and_lose'] = -$order_fee;

$insert = pdo_insert("user_position", $position);

$res['data'] = $position;
if ($insert) {
    $res['status'] = 0;
    $res['msg'] = "success";
    die(json_encode($res));
} else {
    $res['status'] = 1;
    $res['msg'] = "add order fail";
    die(json_encode($res));
}
        
        
        
        
        