<?php
//删除昨天的数据
// 不能直接删，无采集的股票会没有价格，导致持仓出错
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';


// 方式一：删除前天的时间  > 不能直接删，无采集的股票会没有价格，导致持仓出错
// $beforeYesterdayTime = date('Y-m-d H:i:s', strtotime('-1 day'));
// $where['timestamp <'] =$beforeYesterdayTime;
// // echo $beforeYesterdayTime;
// $res = pdo_fetchcolumn("select count(*) as count from real_time_data where add_time < '".$beforeYesterdayTime."'");

// echo $beforeYesterdayTime." 天以前的数据（条）:".$res;

// $res = pdo_fetch("delete from real_time_data where add_time < '".$beforeYesterdayTime."'");

//  方式二： 每个股票保留一条

pdp_fetch("DELETE t1 FROM real_time_data t1, real_time_data t2 WHERE t1.id < t2.id AND t1.stock_gid = t2.stock_gid");