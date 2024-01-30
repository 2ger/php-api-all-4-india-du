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


//实名认证

//当前价格
$stock = pdo_fetch("select s.*,r.close from stock s left join real_time_data r on s.stock_gid= r.stock_gid where s.id = $stockId");
if (!$stock) die("no stock found ");

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
$order_total_price = $stock['close'] * $buyNum / $lever;
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

// //下单时间判断
if ($stock['stock_type'] != "Forex") {
  
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


//余额判断
// $enable_amt = pdo_fetchcolumn("select enable_amt from user where id = $user_id");
$user_amt = pdo_get("user",["id"=>$user_id],["user_amt","enable_amt","djzj"]);

if ($stock['stock_type'] == "Forex") {
    //外汇使用美元汇率 *83
    // $order_total_price *=83;
    // $order_fee *=83;
    // $spread_fee *=83;
}
if ($user_amt["enable_amt"] < ($order_total_price + $order_fee + $spread_fee)) {
    $res['status'] = 1;
    $res['msg'] = "No Available Amount";
    die(json_encode($res));
} else {
    $whereu['id'] = $user_id;
    $updateu["enable_amt"] = $user_amt['enable_amt'] - ($order_total_price + $order_fee + $spread_fee);
    $updateu["user_amt"] = $user_amt['user_amt'] - ($order_fee + $spread_fee);
    $updateu["djzj"] =$user_amt['djzj']+$order_total_price;
}


//下单

$position['user_id'] = $user_id;
$position['nick_name'] = $user->nickName;
$position['agent_id'] = $user->agentId;
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
$position['buy_order_price'] = $stock['close'];

$position['buy_order_id'] = date("YmdHis");
$position['buy_order_time'] = date("Y-m-d H:i:s");
$position['all_profit_and_lose'] = -$order_fee;


$res['data'] = $position;
$res['status'] = 0;
$res['msg'] = "success";
pdo_begin();
try {
    pdo_update("user", $updateu, $whereu);
    pdo_insert("user_position", $position);
    pdo_commit();
} catch (PDOException $exception) {
    pdo_rollback();
    $res['status'] = 1;
    $res['msg'] = "add order fail";
    $res['data'] = $exception->getMessage();

}
die(json_encode($res));
        
        
        
        
        