<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
        <meta data-n-head="ssr" name="viewport" content="width=device-width,initial-scale=1">
    <title>Pay types</title>
</head>
<body>
    
<?php
header("Access-Control-Allow-Origin: *");

 $pay_url = "https://api.666pay.xyz/pay/order/create";
 $mer = "888458208";
 $key = "a545103df14340038a9f59df25463a78";
//   $mer = "888458203";
//  $key = "7bd5ff811951429084682738b8c9d818";

//  $pay_code='1500'; //通道编码，商户后台查看 1501 钱包  1500 银行卡
 $currency = "MYR";
 $notify_url = "https://tradingvidya.com/api/666notify.php";
 
 $pay_code = $_GET['pay_code'];
 $amt = $_GET['amt'];
 $id = $_GET['id'];
 $user_id = $_GET['user_id'];
 

     
$currentURL = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']."&pay_code=";

$OMPay = "https://" . $_SERVER['HTTP_HOST'] ."/api/OMPay/pay.php?amt=$amt&id=$id&user_id=$user_id";
$uudes = "https://" . $_SERVER['HTTP_HOST'] ."/api/uudes/pay.php?amt=$amt&id=$id&user_id=$user_id";
$qpay = "https://" . $_SERVER['HTTP_HOST'] ."/api/qpay/pay.php?amt=$amt&id=$id&user_id=$user_id";

?>

<div class="title">
    --- Choose a Pay Type ---
</div>

<!--<a href="<?=$currentURL?>1500" class="btn banks">via Banks</a>-->
<!--<a href="<?=$currentURL?>1501" class="btn wallet">via Wallet</a>-->
<!--<a href="<?=$OMPay?>" class="btn wallet">via Wallet</a>-->

<a href="<?=$qpay?>" class="btn banks">via UPI</a>

<a href="<?=$uudes?>" class="btn wallet">via Wallet App</a>


<a href="https://app.chatra.live/index/index/home?visiter_id=&visiter_name=&avatar=&groupid=0&business_id=25" class="btn banks">Customer Service</a>

<style>
.title{
      margin: 50px 20px;
        color: #00112c;
        text-align: center;
        font-size: 18px;
}
    .btn{
        display: block;
        /*border: 1px solid #ccc;*/
        /*border-radius: 10px;*/
        margin: 20px;
        background: #0abf53;
        color: #fff;
        text-align: center;
        text-decoration: none;
        padding: 10px;
        border: 2px solid rgba(0,0,0,0);
    border-radius: 8px;
        height: 44px;
    /*margin: 3px;*/
    padding: 9px 24px;
        font-size: 16px;
    font-weight: 700;
    line-height: 44px;
    }
    .wallet{
            background-color: #00112c;
    }
</style>
<?
 if(!$pay_code){
     
die();
 }


$map = [
    'mer_no'=>$mer,
    'notifyUrl'=>$notify_url,
    'order_no'=>$id,//,//time().rand(100000,999999)
    'pay_code'=>$pay_code,
    'currency'=>$currency,
    'order_amount'=>$amt,
    'order_date'=>date('Y-m-d H:i:s'),
    'payer_name'=>$user_id,
     'payer_email'=>'ns@gmail.com',
     'payer_mobile'=>'9599420552',
     'payer_accno'=>'',
     'attch'=>'',
    'payer_ip'=>'127.0.0.1',
];

 
$sign = sendSign($map, $key);
$map = array_merge($map,['sign' => $sign]);
//echo $sign;
//var_dump($map);die;
$res0 = $res = httpPost($pay_url, $map);
// var_dump($res);die;

//php
$res = json_decode($res,true);
    $url = $res['pay_url'];
    
if($res['code'] == "SUCCESS" && $url != "null"){
    echo "<a href='".$url."'> go to pay</a>";
    header("location:$url");
}else{
    $message = $res['message'];
    // echo "<script>alert('$message');history.go(-1)</script>";
   echo "Pay error: \n<br>" ;
var_dump(json_encode($map));
 echo "Pay error return: \n<br>" ;
die($res0);die();
    // var_dump($res); 
}

// die($res); //json
die;



 function httpPost($url, $data) {
     
    $postData= http_build_query($data); //重要！！！
    $ch = curl_init();
    // 设置选项，包括URL
    curl_setopt($ch, CURLOPT_URL, $url);
    $header = array ();
    $header [] = 'User-Agent: ozilla/5.0 (X11; Linux i686) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.835.186 Safari/535.1';
    $header [] = 'Accept-Charset: UTF-8,utf-8;q=0.7,*;q=0.3';
    $header [] = 'Content-Type:application/x-www-form-urlencoded';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);    // 对证书来源的检查
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);    // 从证书中检查SSL加密算法是否存在
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);    // 使用自动跳转
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);       // 自动设置Referer
    curl_setopt($ch, CURLOPT_POST, 1);      // 发送一个 常规的Post请求
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    // Post提交的数据包
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);      // 设置超时限制防止死循环
    curl_setopt($ch, CURLOPT_HEADER, 0);        // 显示返回的Header区域内容
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    //获取的信息以文件流的形式返回
    
     $output = curl_exec($ch);
    if(curl_errno($ch))
    {
        echo "Errno".curl_error($ch);   // 捕抓异常
    }
    curl_close($ch);    // 关闭CURL
    return $output;
}

function sendSign($params, $appsecret)
{
    ksort($params);
    $signStr = '';
    foreach($params as $key => $val){
        if($val != null){
            $signStr .= $key .'='.$val.'&';            
        }
    }
    $signStr .= 'key='.$appsecret;
    // echo $signStr;
    return strtolower(md5($signStr));
}
?>

</body>
</html>