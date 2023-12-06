<?php

header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';
//连接到 Redis 数据库
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(3);

$code = $_GPC['keyWords'];
$pageSize = $_GPC['pageSize'];
$pageNum = $_GPC['pageNum'];

$offset = ($pageNum-1)*$pageSize;


// stock_name
// stock_code
// stock_spell
// stock_type
// stock_gid
// stock_plate
    //新股上架，搜索不到，手动从详情写入数据库，再从数据库查出来
    $list = pdo_fetchall("SELECT r.*,s.*  FROM stock s left join `real_time_data` r on r.stock_code = s.stock_code WHERE (s.stock_code like '%".$code."%' or s.stock_spell like '%".$code."%' or s.stock_name like '%".$code."%') and s.stock_type like '%india%'  group by s.stock_code limit $pageSize ");//OFFSET $offset


$data['status'] = 0;
$data['data'] = $list;

// pdo_debug();

$data = json_encode($data);
echo $data;
