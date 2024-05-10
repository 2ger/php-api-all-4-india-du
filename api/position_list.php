<?php
header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:*');

require '../framework/bootstrap.inc.php';

//当前用户
//连接到 Redis 数据库
$redis = new Redis();
$redis->connect('127.0.0.1');
$redis->select(0);
$token = $_SERVER['HTTP_USERTOKEN'];
$user = json_decode($redis->get($token));
if (!$user) die("please login ");
$user_id = $user->id;

$where = " `p`.sell_order_id is null ";
$state = $_GPC['state'];
if ($state == 1) {
    $where = " `p`.sell_order_id  >1 ";
}
// var_dump($where);


$list = pdo_fetchall("select `p`.*,`p`.stock_name as stockName,`p`.stock_gid as stockGid,`p`.buy_order_price as buyOrderPrice,`p`.order_num as orderNum ,
 `p`.position_sn as positionSn,
 `p`.buy_order_id as buyOrderId,
 `p`.order_fee as orderFee,
 `p`.order_lever as orderLever,
 `p`.order_spread as orderSpread,
 `p`.order_total_price as orderTotalPrice,
 `r`.close as now_price,
 `s`.stock_type  
  from user_position p  left join real_time_data r on `r`.stock_gid=`p`.stock_gid LEFT JOIN stock s on `s`.stock_gid=`p`.stock_gid  where `p`.user_id = $user_id  and  $where  order by id desc");
//  var_dump($list);
// pdo_debug();
$profit_inr = 0;
foreach ($list as &$val) {
//    $val['now_price'] = pdo_fetchcolumn("select close from real_time_data where stock_gid = '" . $val['stock_gid'] . "' order by id desc");
//    $val['stock_type'] = pdo_fetchcolumn("select stock_type from stock where stock_gid = '" . $val['stock_gid'] . "'");

  if ($state == 1) {
        $profit = $val['profit_and_lose'];
    }
    elseif ($val['position_type']) {
        $profit = ($val['buy_order_price'] - $val['now_price']) * $val['orderNum'];
    } else {
        $profit = ($val['now_price'] - $val['buy_order_price']) * $val['orderNum'];
    }
    $val['cal11111'] = $profit;
    $val['cal111112'] = "(" . $val['now_price'] . "- " . $val['buy_order_price'] . ") * " . $val['orderNum'];
    if ($state == 0&&$val['stock_type'] != "india") {
        $profit = $profit * $val['orderLever'];
    }

  
    $profit = round($profit, 2);
    $val['profit_and_lose'] = $val['profitAndLose'] = $profit;

    //计算赢利  -外汇
    if ($val['stock_type'] == "Forex") {
        $val['orderFee'] = round(($val['orderFee'] / $_W['config']['usd']['inr']), 2);
        $val['orderTotalPrice'] = round(($val['orderTotalPrice'] / $_W['config']['usd']['inr']), 2);
        if ($state == 1) {
            $val['profitAndLose'] = round(($val['profitAndLose'] / $_W['config']['usd']['inr']), 2);
        } else {
            $val['profit_and_lose'] = round(($val['profitAndLose'] * $_W['config']['usd']['inr']), 2);
            $profit*= $_W['config']['usd']['inr'];
        }
        
    }

    $val['allProfitAndLose'] = round((-$val['orderFee'] + $val['profitAndLose']), 2);
    $val['all_profit_and_lose'] = round((-$val['order_fee'] + $profit), 2);


    $profit_inr += $profit;

    

}
if ($list) {
    $res['status'] = 0;
    $res['msg'] = "success";
    // $res['profit_inr'] = round($profit_inr * $_W['config']['usd']['inr'], 2);
    $res['profit_inr'] = $profit_inr;
    $res['data']['total'] = count($list);
    $res['data']['list'] = $list;
} else {
    $res['status'] = 1;
    $res['msg'] = "no data";
}
die(json_encode($res));