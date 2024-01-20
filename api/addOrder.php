<?php
header("Access-Control-Allow-Origin: *");
require '../framework/bootstrap.inc.php';


$buyNum = $_GPC['buyNum'];
$lever = $_GPC['lever'];
$buyType = $_GPC['buyType'];
$stockId = $_GPC['stockId'];
$profitTarget = $_GPC['profitTarget'];
$stopLoss = $_GPC['stopLoss'];
$targetPrice = $_GPC['targetPrice'];
$stopTarget = $_GPC['stopTarget'];


//实名认证


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
    die(json_encode($res));
}

//金额计算
$order_total_price = $targetPrice * $buyNum / $lever;
$order_fee = $order_total_price / 10000;


//余额判断
$enable_amt = pdo_fetchcolumn("select enable_amt from user where id = $user_id");
if ($enable_amt < ($order_total_price + $order_fee)) {
    $res['status'] = 1;
    $res['msg'] = "No Available Amount";
} else {
    $whereu['id'] = $user_id;
    $updateu["enable_amt"] = $enable_amt - ($order_total_price + $order_fee);

}

//下单

$pending['user_id'] = $user_id;
$pending['stock_id'] = $stockId;
$pending['buy_num'] = $buyNum;
$pending['buy_type'] = $buyType;
$pending['lever'] = $lever;
$pending['profit_target'] = $profitTarget > 0 ? $profitTarget : null;
$pending['stop_target'] = $stopTarget > 0 ? $stopTarget : null;
$pending['now_price'] = 0;
$pending['target_price'] = $targetPrice;
$pending['add_time'] = date("Y-m-d H:i:s", time());
//$pending['update_time'] = "";
$pending['status'] = 0;
//$pending['position_id'] = null;


$res['data'] = $pending;
$res['status'] = 0;
$res['msg'] = "success";
pdo_begin();
try {
    // pdo_update("user", $updateu, $whereu);//挂单不扣，通过扣
    pdo_insert("user_pendingorder", $pending);
    pdo_commit();
} catch (PDOException $exception) {
    pdo_rollback();
    $res['status'] = 1;
    $res['msg'] = "add order fail";
    $res['data'] = $exception->getMessage();

}
die(json_encode($res));




