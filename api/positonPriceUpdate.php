<?php
// 定时更新持仓中的价格 https://tradingdiario.com/api/positonPriceUpdate.php
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';

$op = $_GPC["op"];
// if($op == "list"){
   //持
    $list = pdo_fetchall("SELECT * FROM `user_position` WHERE `sell_order_time` is null and user_id >0 GROUP by stock_code");
    if(!$list) die("无任务");
    echo count($list)."个持仓,开始更新<br>";
//   die();
    foreach ($list as $val){
        echo "<br>\n".$val["stock_gid"]."<br>\n";
        $type = substr($val["stock_gid"], 0 ,2);
        if($type != "us" && $type != "hk"){
           $url = "https://tradingdiario.com/api/getMaStock.php?time=2W&insert=1&code=".$val["stock_code"];
        }else{
           $url = "https://tradingdiario.com/api/nullPrice.php?stock_code=".$val["stock_code"]."&op=".$type;
        }
        echo file_get_contents($url);
        sleep(1);
    }
 
    die();
// }
