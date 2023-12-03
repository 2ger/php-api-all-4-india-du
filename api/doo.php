<?php
// 采集doo印度股票 https://india.qq3.bpanel.cc/api/doo.php
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';

$list = pdo_fetchall("select * from india_stocks where c_id=2");
foreach ($list as $item){
    $data = array();
    $data['stock_type'] = 'india';
    
    
    $data['stock_name'] = $item['symbolName'];
    $data['stock_code'] = $item['symbol'];
    $data['stock_spell'] = $item['name'];
    $data['stock_gid'] = $item['systexId'];
    $data['increase_ratio'] = $item['increase'];
    $data['add_time'] = date("Y-m-d H:i:s");
    
    pdo_insert('stock',$data);
}
echo "success";
// var_dump($list);