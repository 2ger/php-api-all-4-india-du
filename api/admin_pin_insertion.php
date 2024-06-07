<?php
//后台插针
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
$stock_code = $_GPC['stockCode'];
$new_price = floatval($_GPC['newPrice']);

$sql = "select o.*,s.stock_type,r.close from user_position as o left join stock as s on s.stock_gid = o.stock_gid left join real_time_data r on r.stock_gid = o.stock_gid  where o.sell_order_id is null and (o.profit_target_price >0 || o.stop_target_price >0) and o.stock_code='".$stock_code."'";
  $res['sql'] = $sql;
//查询订单、价格、股票类型
$order = pdo_fetchall($sql);
$res['num'] = count($order)."个订单";
$str = '';
foreach ($order as $val){
    $close = 0;//是否触发平仓

$user_id = $val['user_id'];
    $close_price = $new_price;
   $str .= "买入价格:".$val['buy_order_price']."\n";
   $str .= "当前价格:".$close_price."\n";
   $str .= "profit_target_price:".$val['profit_target_price']."\n";
   $str .= "stop_target_price:".$val['stop_target_price']."\n";
   
   $buy_price=floatval($val['buy_order_price']);
$order_num=floatval($val['order_num']);
$order_level=floatval($val['order_lever']);
// $aaa=[$buy_price,$close_price,$order_num,$order_level];
//计算赢利 - 股票
if ($val['position_type']) {
    
$str .=  "买跌\n";
  if($close_price < $val['profit_target_price'] && $val['profit_target_price'] >0){
        $close_price = $val['profit_target_price'];
        $close = 1;//是否触发平仓
$str .=  "profit_target_price触发\n";
        
        
    }
    if($close_price > $val['stop_target_price'] && $val['stop_target_price'] >0){
        $close_price = $val['stop_target_price'];
        $close = 1;//是否触发平仓
$str .=  "stop_target_price触发\n";
    }
    
    $profit = ($buy_price - $close_price) * $order_num;

} else {//买涨
$str .=  "买涨\n";
   
 if($close_price > $val['profit_target_price']  && $val['profit_target_price'] >0){
        $close_price = $val['profit_target_price'];
$str .=  "profit_target_price触发\n";
        $close = 1;//是否触发平仓
        
    }
    if($close_price < $val['stop_target_price']  && $val['stop_target_price'] >0){
        $close = 1;//是否触发平仓
$str .=  $close_price."<" . $val['stop_target_price']." stop_target_price触发\n";
        $close_price = $val['stop_target_price'];
    }
   
    $profit = ($close_price - $buy_price) * $order_num;
}

if($close){
    $str .=  $val['id']."触发平仓\n";
    
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

$user_amt = pdo_get("user", ["id" => $user_id]);


$djzj = $user_amt["djzj"] - $benjin;
if($djzj<0) $djzj =0;
$up_user = [
    "enable_amt" => $user_amt["enable_amt"] + $profit + $benjin,
    "user_amt" => $user_amt["user_amt"] + $profit+ $benjin,
    "djzj" => $djzj
];
// var_dump($up_position);
// var_dump($up_user);
$position_up_where = ["id" => $val['id']];
$user_up_where = ["id" => $user_id];

// echo  "n\nupdate\n";
// var_dump($up_position);
// echo  "\n where\n";
// var_dump($position_up_where);


  $res['str'] = $str; 
pdo_begin();
try {
    pdo_update("user_position", $up_position, $position_up_where);
    pdo_update("user", $up_user, $user_up_where);
    
    $amt = $profit+ $benjin;
    add_cash_detail($user_amt, $val['id'],"Sell",$amt,"本金：$benjin + 收益：$profit");
    
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

