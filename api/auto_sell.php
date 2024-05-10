<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers:*');
require '../framework/bootstrap.inc.php';

$orders = pdo_fetchall("select p.*,s.stock_type,r.close from user_position p left join real_time_data r on r.stock_gid=p.stock_gid  left join stock s on s.stock_gid=p.stock_gid where  p.sell_order_id IS NULL and (p.profit_target_price >0  or p.stop_target_price >0)");


foreach ($orders as $order) {

    if ($order['close'] <= 0) {
        continue;
    }
    if ($order['position_type'] == 0 && $order['stop_target_price'] < $order['close'] && $order['profit_target_price'] > $order['close']) {
        //做多
        continue;
    }
    if ($order['position_type'] == 1 && $order['stop_target_price'] > $order['close'] && $order['profit_target_price'] < $order['close']) {
        //做空
        continue;
    }

    echo $order['stock_name'] . '@target_price:' . $order['profit_target_price'] . " now_price:" . $order['close'] . PHP_EOL;
    $buy_price = floatval($order['buy_order_price']);
    $close_price = floatval($order['close']);
    $order_num = floatval($order['order_num']);
    $order_level = floatval($order['order_lever']);
    $profit = 0;


    if ($order['position_type']) {
        $profit = ($buy_price - $close_price) * $order_num;
    } else {
        $profit = ($close_price - $buy_price) * $order_num;
    }
    $benjin = $order['order_total_price'];

//黄金stock_code=XAUUSD
    //计算赢利  - 指数
    if ($order['stock_type'] != "india") {
        //买涨买跌
        $profit = $profit * $order_level;
    }

    //计算赢利  -外汇
    if ($order['stock_type'] == "Forex") {
        //买涨买跌
        $profit = $profit * $_W['config']['usd']['inr'];
    }

    $profit = round($profit, 2);
    $now_time = date("Y-m-d H:i:s", time());
//更新订单
    $up_position = [
        'profit_and_lose' => $profit,
        'all_profit_and_lose' => $profit - $order['order_fee'],
        "sell_order_id" => date("YmdHis", time()) . rand(0, 9),
        "sell_order_price" => $order['close'],
        "sell_order_time" => $now_time,
        "order_stay_days" => floor((strtotime($now_time) - strtotime($order['buy_order_time'])) / 86400),
    ];

    $user_amt = pdo_get("user", ["id" => $order['user_id']], ["user_amt", "enable_amt", 'djzj']);

    $djzj = $user_amt["djzj"] - $benjin;
    if ($djzj < 0) $djzj = 0;
    $up_user = [
        "enable_amt" => $user_amt["enable_amt"] + $profit + $benjin,
        "user_amt" => $user_amt["user_amt"] + $profit,
        "djzj" => $djzj
    ];
    // var_dump($up_user);
    $position_up_where = ["id" => $order['id']];
    $user_up_where = ["id" => $order['user_id']];
    pdo_begin();
    try {
        pdo_update("user_position", $up_position, $position_up_where);
        pdo_update("user", $up_user, $user_up_where);
        pdo_commit();
    } catch (PDOException $exception) {
        pdo_rollback();

    }

}


