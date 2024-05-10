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
$stock_code = intval($_GPC['stockCode']);
$new_price = floatval($_GPC['newPrice']);

$stock = pdo_fetch("select * from real_time_data where stock_code=$stock_code");
if ($stock) {
    $res = pdo_update('real_time_data', ['close' => $new_price], ['id' => $stock['id']]);
    if ($res) {
        file_get_contents("https://trade.pgim.top/api/auto_sell.php");
        $return['status'] = 0;
        $return['msg'] = 'success';
    }
} else {
    $return['msg'] = "stock not exist!";
}
die(json_encode($return));

