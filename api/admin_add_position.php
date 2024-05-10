<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers:*');
require '../framework/bootstrap.inc.php';


$redis = new Redis();
$redis->connect('127.0.0.1');
$redis->select(0);
$token = $_SERVER['HTTP_ADMINTOKEN'];
$admin = json_decode($redis->get($token));
$return = [
    'status' => 1,
    'data' => [],
    'msg' => 'error'
];
if (!$admin) {
    $return['msg'] = 'please login';
    die(json_encode($return));
}

$order_id=intval($_GPC['id']);

$order = pdo_fetch("select * from `user_stock_subscribe` s where `s`.id=$order_id");
// var_dump($order);

$stock = pdo_fetch("select s.* from stock s  where s.stock_spell = '".$order['new_code']."'");
// pdo_debug();
if (!$stock){
   $return['msg'] = 'stock not found';
    die(json_encode($return));
} 

$user_id=$order['user_id'];

$userData = pdo_fetch("select * from user where id = ".$user_id);//redis 登陆才会更新，从数据库实时查
if($userData['is_lock']){
    $return['msg'] = "user account id locked for trading!";
    die(json_encode($return));
}


$buyNum=$order['apply_number'];
$buy_price=$order['buy_price'];
$lever=1;

//金额计算
$order_total_price = $order['bond'];
$order_fee = $order_total_price / 10000;
$spread_fee = 0;
if ($stock['stock_type'] != "india") {
    //指数、大宗
//    $order_total_price = $stock['close'] * $buyNum / $lever;
    $order_fee = 50 * $buyNum;
    $order_total_price = 1000 * $buyNum;
    $spread_fee = 0;
    $position['spread_rate_price'] = 0;

}
if ($stock['stock_type'] == "Forex") {
    //乘以汇率
    $order_fee *= $_W['config']['usd']['inr'];
    $order_total_price *= $_W['config']['usd']['inr'];
    // die($order_fee);
}




//余额判断
// $enable_amt = pdo_fetchcolumn("select enable_amt from user where id = $user_id");


if ($userData["enable_amt"]<0||$userData["enable_amt"] < (  $order_fee + $spread_fee)) {
    
    $return['msg'] = "No Available Amount";
    die(json_encode($return));
} else {
    $whereu['id'] = $user_id;
    $updateu["enable_amt"] = $userData['enable_amt'] - (  $order_fee + $spread_fee);
    $updateu["user_amt"] = $userData['user_amt'] - ($order_fee + $spread_fee);
    
}


//下单

$position['user_id'] = $user_id;
$position['nick_name'] = $userData['nick_name'];
$position['agent_id'] = $userData['agent_id'];
$position['position_type'] = $buyType;
$position['order_direction'] = $buyType ? "Buy Down" : "Buy Up";
$position['position_sn'] = time() . $user_id;
$position['order_num'] = floatval($buyNum);
$position['order_lever'] = $lever;
$position['order_total_price'] = $order_total_price;
$position['order_fee'] = $order_fee;

$position['stock_name'] = $stock['stock_name'];
$position['stock_code'] = $stock['stock_code'];
$position['stock_gid'] = $stock['stock_gid'];
$position['stock_spell'] = $stock['stock_spell'];
$position['buy_order_price'] = $buy_price;

$position['buy_order_id'] = date("YmdHis");
$position['buy_order_time'] = date("Y-m-d H:i:s");
$position['all_profit_and_lose'] = -$order_fee;

$position['profit_target_price'] =$profitTarget;
$position['stop_target_price'] =$stopLoss;




pdo_begin();
try {
    pdo_update("user", $updateu, $whereu);
    pdo_insert("user_position", $position);
    pdo_update("user_stock_subscribe", ['status'=>5],['id'=>$order_id],);
    // $res['insertid'] = pdo_insertid();
    pdo_commit();
    $return['status']=0;
    $return['msg']="success";
} catch (PDOException $exception) {
    pdo_rollback();
    $return['msg'] = "add order fail";
    $return['data'] = $exception->getMessage();

}
die(json_encode($return));

