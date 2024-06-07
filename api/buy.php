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

//从redis取价格
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(3);
$redData = $redis->get($stock['stock_gid']);
$redData = json_decode($redData,true);
// $redData['last_done'];
if($redData['last_done']){
   $stock['close'] = $redData['last_done'];
   //更新价格
    pdo_update("real_time_data",array("stock_gid"=>$stock['stock_gid']),array("close"=>$stock['close']));
// 更新end
}
//从redis取价格  end
// echo $redData['last_done']." > ".$stock['close'];
// die();


if(!$stock['close']){
   $res['status'] = 1;
    $res['msg'] = "please try later!";
    die(json_encode($res));
}

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
$isLock = pdo_fetchcolumn("select is_lock from user where id = ".$user_id);//redis 登陆才会更新，从数据库实时查
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
if ($stock['stock_type'] == "Forex") {
    //乘以汇率
    $order_fee *= $_W['config']['usd']['inr'];
    $order_total_price *= $_W['config']['usd']['inr'];
    // die($order_fee);
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
$user_amt = pdo_get("user",["id"=>$user_id]);//,["user_amt","enable_amt","djzj"]


if ($user_amt["enable_amt"] < ($order_total_price + $order_fee + $spread_fee)) {
    $res['status'] = 1;
    $res['msg'] = "No Available Amount";
    die(json_encode($res));
} else {
    $whereu['id'] = $user_id;
    $updateu["enable_amt"] = $user_amt['enable_amt'] - ($order_total_price + $order_fee + $spread_fee);
    $updateu["user_amt"] = $user_amt['user_amt'] - ($order_total_price+$order_fee + $spread_fee);//减本金，保持一至
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

$position['profit_target_price'] =$profitTarget;
$position['stop_target_price'] =$stopLoss;


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


$res['data'] = $position;
$res['status'] = 0;
$res['msg'] = "success";
pdo_begin();
try {
    pdo_update("user", $updateu, $whereu);
    pdo_insert("user_position", $position);
    $res['insertid'] = pdo_insertid();
    
    $amt = $order_total_price + $order_fee + $spread_fee;
    add_cash_detail($user_amt, $res['insertid'],"Buy",-$amt,"总价：$order_total_price + 手续费：$order_fee");
// if(!$res['insertid']){
    // pdo_debug();
// }
    pdo_commit();
} catch (PDOException $exception) {
    pdo_rollback();
    $res['status'] = 1;
    $res['msg'] = "add order fail";
    $res['data'] = $exception->getMessage();

}

die(json_encode($res));
        
        
        
        
        