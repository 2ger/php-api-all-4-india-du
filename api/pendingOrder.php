<?php
header("Access-Control-Allow-Origin: *");
require '../framework/bootstrap.inc.php';

$op = $_GPC['op'];
$id = $_GPC['id'];

if($op == "list"){
    
    $where['lever'] = 1;
    $where['status'] = 0; //仅未成交
    if($_GPC['userId'])$where['user_id'] = $_GPC['userId'];
    if($_GPC['stock_id'])$where['stock_id'] = $_GPC['stock_id'];
    if($_GPC['id'])$where['id'] = $_GPC['positionSn'];
    
    $list = pdo_getall("user_pendingorder",$where);

//会得到其他表的数据
// $wherestr = " p.lever = 1 and p.status =0  and  s.stock_name IS NOT NULL and u.real_name  IS NOT NULL and r.close  IS NOT NULL ";
// if($_GPC['user_id']) $wherestr .= ' and p.user_id = '.$_GPC['userId'];
// if($_GPC['stock_id'])$wherestr .= ' and p.stock_id = '.$_GPC['stock_id'];
// if($_GPC['id'])$wherestr .= ' and p.id = '.$_GPC['positionSn'];
// $sql = "SELECT p.*,s.stock_name as indexName,u.real_name as realName,r.close as now_price FROM `user_pendingorder` p INNER  join stock s on s.stock_code =p.stock_id INNER  join user u on u.id = p.user_id INNER  join real_time_data r on r.stock_code = p.stock_id where $wherestr order by p.id desc";
//   $list = pdo_fetchall($sql);

    //   pdo_debug();
    foreach ($list as &$value) {
        $stock_name = pdo_fetchcolumn("select stock_name from stock where stock_code = '".$value['stock_id']."'");
        $value['indexName'] = $stock_name." ( ".$value['stock_id']." ) ";
         $value['realName'] =    pdo_fetchcolumn("select real_name from user where id = '".$value['user_id']."'");
         $value['userId'] =   $value['user_id'];
         $value['orderDirection'] =   $value['buy_type']?"卖出":"买入";
         $value['buyOrderPrice'] =   $value['target_price'];
         $value['now_price'] =   pdo_fetchcolumn("select close from real_time_data where volume = 100 and stock_code = '".$value['stock_id']."' order by id desc");
      
        
    }
    //   pdo_debug();
    $data['data'] =$list;
    die(json_encode($data));
}

if($op == "prove"){
    // var_dump($_GPC);
    $id = $_GPC['positionId'];
    $status = $_GPC['state'];
    if($status ==2){
        //拒绝
         //更新订单
        $update['status'] = 2;
        $update['update_time'] = date("Y-m-d H:i:s");
        $where['id'] = $id;
        $res2 =   pdo_update("user_pendingorder",$update,$where);
    //  pdo_debug();
         if($res2){
             $resp['status'] = 0;
             $resp['msg'] = "操作成功";
         }else{
             $resp['status'] = 1;
             $resp['msg'] = "操作失败";
         }
        die(json_encode($resp));
    }
    // echo $id;
    $order = pdo_fetch("SELECT * FROM user_pendingorder where id= ".$id);
    $user =  pdo_fetch("select * from user where id = '".$order['user_id']."'");
    $stock = pdo_fetch("select * from stock where stock_code = '".$order['stock_id']."'");
    
    $position['position_type'] = $order['buy_type'];
    $position['position_sn'] = time().$order['id'];
    $position['user_id'] = $order['user_id'];
    $position['nick_name'] =    $user['nick_name'];
    $position['agent_id'] = $user['agent_id'];
    $position['stock_name'] = $stock['stock_name'];
    $position['stock_code'] = $stock['stock_code'];
    $position['profit_target_price'] = $position['profit_target'];
    $position['stop_target_price'] = $position['stop_target'];

    $position['stock_gid'] = $stock['stock_gid'];
    $position['stock_spell'] = $stock['stock_spell'];
    $position['buy_order_id'] = date("YmdHis").$order['id'];
    $position['buy_order_time'] = date("Y-m-d H:i:s");
    $position['buy_order_price'] = $order['target_price'];
    $position['order_direction'] =   $order['buy_type']?"买跌":"买涨";
    $position['order_num'] = $order['buy_num'];
    $position['order_lever'] = 1;
    $position['profit_target_price'] = $order["profit_target"];
    $position['stop_target_price'] = $order["stop_target"];
    $position['order_total_price'] = $order['target_price']*$order['buy_num']/$order['lever'];
    $position['order_fee'] = $order['target_price']*$order['buy_num']/$order['lever']*0.0001;
    $position['order_spread'] = 0;//$order['target_price']*0.1;
    $position['all_profit_and_lose'] = -$position['order_fee']-$position['order_spread'];
    
    if($user['enable_amt'] < $position['order_total_price']){
             $resp['status'] = 1;
             $resp['msg'] = "用户余额不足,所需金额:".$position['order_total_price'].",用户可用金额：".$user['enable_amt'];
             die(json_encode($resp));
    }else{
      
        // pdo_debug();
        // die();
        
    }
    // var_dump($order);
    // var_dump($position);die();
    $res = pdo_insert("user_position",$position);
    $insert_id = pdo_insertid();
    if($insert_id >0){
        //减余额
        $userupdate['djzj +='] = $position['order_total_price'];
        $userupdate['enable_amt -='] = $position['order_total_price']+$position['order_fee'];
        $userwhere['id'] = $position['user_id'];
        pdo_update("user",$userupdate,$userwhere);
        
        //更新订单
        $update['status'] = 1;
        $update['update_time'] = date("Y-m-d H:i:s");
        $update['position_id'] = $insert_id;
        $where['id'] = $id;
     $res2 =   pdo_update("user_pendingorder",$update,$where);
     
    //  pdo_debug();
         if($res2){
             $resp['status'] = 0;
             $resp['msg'] = "操作成功";
         }else{
             $resp['status'] = 1;
             $resp['msg'] = "操作失败";
         }
    }else{
         $resp['status'] = 1;
         $resp['msg'] = "创建持仓失败";
    }
    // pdo_debug();
    die(json_encode($resp));
    
// 完整内容	
// 
// 
// 用户
// stock_id
// 股票或者指数id
// 
// 数量
// buy_type
// 类型
// lever
// 杠杆
// profit_target
// 止盈
// stop_target
// 止损
// now_price
// 现价
// 
// 目标时间

// 完整内容	
// profit_target_price
// stop_target_price
// 
// 
// all_profit_and_lose
    // die(json_encode($list));
}
die("none op!");