<?php

require '../../framework/bootstrap.inc.php';

$ip_address = $_SERVER['REMOTE_ADDR'];
file_put_contents('../logs/pay_notify_post.log', date("Y-m-d H:i:s")."----\r\n udun: ".$ip_address."\n".$_GPC."\r\n\r",FILE_APPEND);


 $REQ_BODY = $_POST;
//  var_dump($_POST);
    $REQ_BODY =  json_decode($REQ_BODY['body'],true);
    // print_r($REQ_BODY['out_trade_no']);
    //   die('222');
       	$decimals = $REQ_BODY['decimals'];
       	$amount = $REQ_BODY['amount']/pow(10,$decimals);
       	$real_amt = $amount*100;
       	$address = $REQ_BODY['address'];
       	$coinType = $REQ_BODY['coinType'];//TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t  USDT_TRC20
       	$mainCoinType = $REQ_BODY['mainCoinType'];//195
       	$status = $REQ_BODY['status'];


if( $status == 3){//$mer == $get_mer_no && $currency == $get_currency &&
    //没有单号,
    //仅处理10分钟内价格相同的订单
    $time_str = time()-60*10;
    $time = date("Y-m-d H:i:s",$time_str);
    $order = pdo_fetch("select * from user_recharge where pay_img = '".$address."' and order_status = 0  order by id desc");//and add_time > '".$time."'
    // 
    // echo $time;
    //   pdo_debug();
    if($order){
        
        $up['order_status'] =  1;
        $up['pay_time'] =  date("Y-m-d H:i:s");
        $up['pay_amt'] =  $real_amt;
        $where1['id'] =  $order['id'];
        $re1 = pdo_update("user_recharge",$up,$where1);
        
        //充值
        $where2['id'] =  $order['user_id'];
        $up2['enable_amt +='] =  $real_amt;
        $up2['user_amt +='] =  $real_amt;
        $re2 = pdo_update("user",$up2,$where2);
      
    
        if($re2 && $re1) die("SUCCESS");
    }else{
        
        die("no undone order!");
    }
      
}else{
    die("nupay");
}

       	