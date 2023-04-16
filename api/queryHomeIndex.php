
<?php
require '../framework/bootstrap.inc.php';
set_time_limit(0);
header("ALLOW-CONTROL-ALLOW-ORIGIN:*");
header('Access-Control-Allow-Method:POST,GET');//允许访问的方式

// echo "ddd";
//stock_name as name,s.stock_code as symbol,s.stock_type as stocktype,r.low as range,r.high as pnum,s.stocktype as market
$list = pdo_fetchall("select s.*,r.open as price from stock2 s join real_time_data r on s.stock_gid = r.stock_gid group by  s.stock_gid order by r.add_time ");

// $data = array ('msg'=>null,"status"=>0,"success"=>true,"random"=>0);
// $data['result']['data'] =$list;

// var_dump($data);
die(json_encode($list));
// data
// : 
// {result: {status: {code: 0, msg: "ok"},…}, random: 1}
// msg
// : 
// null
// status
// : 
// 0
// success
// : 
// true