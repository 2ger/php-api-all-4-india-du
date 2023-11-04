
<?php
require '../../framework/bootstrap.inc.php';

$merId = "60-00000329-28643858";
$APIKey = "D62E84F5-5FC3-4243-A427-2024FBF059B9";
$mch_private_key = "0DF849BA870147AABA3EEAD101E3ECB5";
$currency = "MYR";
 
// var_dump($_GPC);
$ip_address = $_SERVER['REMOTE_ADDR'];

        $date_str = date('Y-m-d H:i:s')."\n";
        
file_put_contents('logs/pay_notify_post.log', date("Y-m-d H:i:s")."----\r\n".$ip_address."\n".json_encode($_GPC)."\r\n\r",FILE_APPEND);
// file_put_contents('payment/log/pay_notify_post.log', $date_str.file_get_contents('php://input')."\r\n",FILE_APPEND);
      
//     $REQ_BODY = file_get_contents('php://input');
    
//         $REQ_BODY =  json_decode($REQ_BODY,true);
    // // print_r($REQ_BODY);


$payResult = $_GPC['Status'];//	1：支付成功 其他:失败

$get_mer_no = $_GPC['MerchantCode'];
$mer_no = $_GPC['SerialNo'];
$payAmount = $_GPC['Amount'];
$real_amt = $payAmount;///4.4
$get_currency = $_GPC['CurrencyCode'];

// echo $get_mer_no."<br>";
// echo $payResult."<br>";
// echo $get_currency."<br>";
if($merId == $get_mer_no && $currency == $get_currency && $payResult ==1){
  
    //没有单号,
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
    die("nupay");
}

