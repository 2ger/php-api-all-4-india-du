<?php
// 修复价格0卖出订单
// https://trade.pgim.pro/api/doo.php
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';
// $list = pdo_fetchall("SELECT * FROM `user_position` WHERE sell_order_id is NOT null AND sell_order_price =0 and profit_and_lose <0");
// foreach ($list as){}
// die();
//卖出价格为0
// $list = pdo_fetchall("SELECT * FROM `user_position` WHERE sell_order_id is NOT null AND id >1990 and profit_and_lose <0 and id in (2141,2131)");
//买入价格为0
$list = pdo_fetchall("SELECT * FROM `user_position` WHERE sell_order_id is NOT null AND id >1990 and  id in (2141,2131)");
$ids = "(";
foreach ($list as $item){
    $ids = $ids.$item['id'].",";
    
    echo "<hr>".$item['id']." 用户 ".$item['nick_name']." 当前盈亏".$item['profit_and_lose'];
    $profit = ($item['sell_order_price']-$item['buy_order_price'])*$item['order_num'];
    $profit_all = $profit-$item['order_fee'];
    echo "》 应为".$profit." > ".$profit_all;
    $bu = $profit_all-$item['profit_and_lose'];
    echo   "》 应补 ".$bu;
    
    $update['profit_and_lose'] = $profit;
    $update['all_profit_and_lose'] = $profit_all;
    $where['id'] = $item['id'];
    
    
    $update2['user_amt +='] = $bu;
    $update2['enable_amt +='] = $bu;
    $where2['id'] = $item['user_id'];
    // pdo_update("user_position",$update,$where);
    // pdo_update("user",$update2,$where2);
    
}
echo "<Hr>";
echo $ids.")";
// echo "success";
// var_dump($list);