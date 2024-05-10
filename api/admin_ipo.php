<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers:*');
require '../framework/bootstrap.inc.php';


$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(0);
$token = $_SERVER['HTTP_ADMINTOKEN'];
$admin = json_decode($redis->get($token));
if (!$admin) die("please login ");


$new_code = $_GPC['newCode'];
$phone = $_GPC['phone'];
$apply_nums = $_GPC['applyNums'];//申购数量
$apply_number = $_GPC['applyNumber'];//中签数量
$status = intval($_GPC['status']);
$order_id = isset($_GPC['id']) ? intval($_GPC['id']) : null;
$return = [
    'status' => 1,
    'data' => [],
    'msg' => 'error'
];
$params = [
    'apply_number' => $apply_number,
    'status' => $status,
    'type' => 1
];
if ($order_id) {
    //更新
    $order = pdo_fetch("select * from `user_stock_subscribe` s where `s`.id=$order_id");
    
    if (!$order) {
        $return['msg'] = "order not exist!";
        die(json_encode($return));
    }
   
   $params['new_code']=$new_code;
   $params['apply_nums']=$apply_nums;
    
    $upUser=[];
    if ($status == 3) {
        $params['apply_number'] = $apply_number;
        $params['bond'] = $apply_number * $order['buy_price'];
        // $params['add_time'] = date("Y-m-d H:i:s", time());
        $upUser['enable_amt -=']=$params['bond'];
        $upUser['djzj +=']=$params['bond'];
        
         if($order['bond']>0){
             $upUser['enable_amt -=']=$params['bond']-$order['bond'];
             $upUser['djzj +=']=$params['bond']-$order['bond'];
        }

    }
    // $user=pdo_fetch("select * from `user` s where `s`.id=$order['user_id']");
    
    pdo_begin();
    try {
        pdo_update('user_stock_subscribe', $params, ['id' => $order['id']]);
        if(!empty($upUser)){
            pdo_update('user',$upUser,['id'=>$order['user_id']]);
        }
        
         pdo_commit();
         $return['status']=0;
         $return['msg']='success';
    }catch (PDOException $exception) {
        pdo_rollback();
        
    
    }
    die(json_encode($return));
    

} else {
    $user = pdo_fetch("select * from `user` u  where `u`.phone=$phone");
    $stock = pdo_fetch("select * from `stock_subscribe` s where `s`.code=$new_code");
    if (!$user) {
        $return['msg'] = "用户手机号不存在";
        die(json_encode($return));
    }
    if (!$stock) {
        $return['msg'] = "股票代码不正确";
    } else {
        $params['phone'] = $phone;
        $params['user_id'] = $user['id'];
        $params['agent_id'] = $user['agent_id'];
        $params['real_name'] = $user['real_name'];
        $params['agent_name'] = $user['agent_name'];
        $params['new_code'] = $new_code;
        $params['new_name'] = $stock['name'];
        $params['order_no'] = time() . rand(1000, 9999);
        $params['buy_price'] = $stock['price'];
        $params['apply_nums'] = $apply_nums;
        // $params['apply_number'] = $apply_number;
        $params['bond'] = 0;
        $params['add_time'] = date("Y-m-d H:i:s", time());
        $params['end_time'] = $stock['subscription_time'];

        $res = pdo_insert('user_stock_subscribe', $params);
        if ($res) {
            $return['status'] = 0;
            $return['msg'] = 'success';
        }
    }
}

die(json_encode($return));