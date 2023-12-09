
<?php
require '../../framework/bootstrap.inc.php';

$REQ_BODY = file_get_contents('php://input');
 
// var_dump($_GPC);
$ip_address = $_SERVER['REMOTE_ADDR'];
file_put_contents('../logs/pay_notify_post.log', date("Y-m-d H:i:s")."----\r\n".$ip_address."\n".json_encode($REQ_BODY)."\r\n\r",FILE_APPEND);
// $REQ_BODY = $_POST;
$REQ_BODY =  json_decode($REQ_BODY,true);
// print_r($REQ_BODY);die();
// print_r($REQ_BODY['out_trade_no']);
//   die('222');
   	$status = $REQ_BODY['status'];
   	$amount = $REQ_BODY['payMoney']/100;
   	$orderNo = $REQ_BODY['merchantOrderNo'];
       	
$payResult = $REQ_BODY['status'];//	1：支付成功 其他:失败
$mer_no = $REQ_BODY['merchantOrderNo'];
$payAmount = $REQ_BODY['payMoney'];
$real_amt = $payAmount;///4.4
// $get_currency = $REQ_BODY['currency'];
// $get_mer_no = $_GPC['mer_no'];
// $mer == $get_mer_no && $currency == $get_currency && 
if($payResult ==2){
    //没有单号,
    //仅处理10分钟内价格相同的订单
    $time_str = time()-60*10;
    $time = date("Y-m-d H:i:s",$time_str);
    $order = pdo_fetch("select * from user_recharge where order_sn = '".$mer_no."' and order_status = 0");
    // and add_time > '".$time."' order by id desc
    // echo $time;
    //   pdo_debug();
    if($order){
        
        $up['order_status'] =  1;
        $up['pay_time'] =  date("Y-m-d H:i:s");
        $where1['id'] =  $order['id'];
        $re1 = pdo_update("user_recharge",$up,$where1);
        
        //充值
        $where2['id'] =  $order['user_id'];
        $up2['enable_amt +='] =  $real_amt;
        $up2['user_amt +='] =  $real_amt;
        $re2 = pdo_update("user",$up2,$where2);
      
 
    
        if($re2 && $re1) die("success");
    }else{
        
        die("no undone order!");
    }
      
}else{
    die($mer_no." nupay");
}

