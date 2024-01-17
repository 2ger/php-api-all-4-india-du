<?php
//修复冻结资金
// https://trade.pgim.pro/api/fix_djzj.php
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';

$list = pdo_fetchall("SELECT sum(order_total_price) as djzj,user_id FROM user_position where  sell_order_id is null group by user_id ");

print_r($list);
// if($list){
    foreach ($list as $item){
        $where['id'] = $item['user_id'];
        $update['djzj'] = $item['djzj'];
        pdo_update("user",$update,$where);
    }
    
echo $data;


