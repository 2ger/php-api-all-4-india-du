<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers:*');
require '../framework/bootstrap.inc.php';

//当前用户
//连接到 Redis 数据库
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(0);
$token = $_SERVER['HTTP_USERTOKEN'];
$user = json_decode($redis->get($token));
if (!$user){
    $res['status'] = 1;
    $res['msg'] = "please login!";
    die(json_encode($res));
}
// print_r($user);
$user_id = $user->id;

$list=pdo_fetchall("select * from user_stock_subscribe where user_id=$user_id");
if(!empty($list)){
    
$data=[];
foreach ($list as $val){
    $data[]=[
            "id"=> $val['id'],
            "orderNo"=>$val['order_no'],
            "userId"=>$val['user_id'],
            "realName"=>$val['real_name'],
            "phone"=>$val['phone'],
            "agentId"=>$val['agent_id'],
            "agentName"=> $val['agent_name'],
            "newCode"=>$val['new_code'],
            "newName"=>$val['new_name'],
            "bond"=>$val['bond'],
            "buyPrice"=>$val['buy_price'],
            "applyNums"=>$val['apply_nums'],
            "applyNumber"=>$val['apply_number'],
            "type"=>$val['type'],
            "status"=>$val['status'],
            "addTime"=>$val['add_time'],
            "submitTime"=>$val['submit_time'],
            "endTime"=>$val['end_time'],
            "fixTime"=>$val['fix_time'],
            "remarks"=>$val['remarks']
        ];
    
}
    
    
     $res['status'] = 0;
    $res['msg'] = "success!";
    $res['data']=$data;
    die(json_encode($res));
}else{
    
     $res['status'] = 1;
    $res['msg'] = "no data!";
    // $res['data']=$data;
    die(json_encode($res));
}

