
<?php
require '../framework/bootstrap.inc.php';

 $pay_url = "https://api.666pay.xyz/pay/order/create";
 $mer = "888458203";
 $key = "7bd5ff811951429084682738b8c9d818";
 $pay_code='1500'; //通道编码，商户后台查看
 $currency = "MYR";
 $notify_url = "https://phpapi.bitskd.pro/api/666notify.php";
 
// var_dump($_GPC);
$ip_address = $_SERVER['REMOTE_ADDR'];
file_put_contents('logs/pay_notify_post.log', date("Y-m-d H:i:s")."----\r\n".$ip_address."\n".json_encode($_GPC)."\r\n\r",FILE_APPEND);


$payResult = $_GPC['payResult'];//	1：支付成功 其他:失败
$get_mer_no = $_GPC['mer_no'];
$mer_no = $_GPC['orderNo'];
$payAmount = $_GPC['payAmount'];
$real_amt = $payAmount;///4.4
$get_currency = $_GPC['currency'];

if($mer == $get_mer_no && $currency == $get_currency && $payResult ==1){
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
    die("nupay");
}

