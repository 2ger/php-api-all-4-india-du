<?php
//印度大综商品搜索
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

$list = pdo_fetchall("SELECT r.*,s.*  FROM stock s left join `real_time_data` r on r.stock_code = s.stock_code WHERE (s.stock_code like '%".$code."%' or s.stock_spell like '%".$code."%' or s.stock_name like '%".$code."%') and s.stock_type like '%Forex%'  group by s.stock_code order by s.id limit $pageSize OFFSET $offset ");//OFFSET $offset

if($list){
    $newList = array();
    foreach ($list as &$value) {
        $newList[$value['stock_spell']] = $value;
        
        $redData = $redis->get($value['stock_gid']);
        $redData = json_decode($redData,true);
        $newList[$value['stock_spell']]['redData'] = $redData;
        if($redData['last_done']){
           $newList[$value['stock_spell']]['close'] = $redData['last_done'];
        }

        // code...
    }
    
    $data['status'] = 0;
    $data['data'] = $newList;
}else{
    $data['status'] = 1;
    $data['msg'] = "no data";
}

// pdo_debug();

$data = json_encode($data);
echo $data;




