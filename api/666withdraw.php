<?php
header("Access-Control-Allow-Origin: *");
require '../framework/bootstrap.inc.php';

require_once  '../vendor/autoload.php';
use Udun\Dispatch\UdunDispatch;
// qpay
 $getmer = $_GPC['mer'];//手动回调
 $where['id'] = $id= $_GPC['withId'];//$_GPC['id'];
 $where['with_status'] = 0;
 
//  var_dump($_GPC['__input']['withId']);
 
 $withdraw = pdo_get("user_withdraw",$where);
// print_r($withdraw);
 
 if($withdraw['bank_name']=="USDT"){
     //USDT提现
    
    $merchant_no = "316538";
$api_key = "69051a2a4333070ddbc1acc898805543";
$gateway_address = "https://sig10.udun.io";

    $callUrl = "https://trade.pgim.pro/api/uduncloud/notify.php";
     $udunDispatch = new UdunDispatch([
            'merchant_no' => $merchant_no, //;309634, //商户号
            'api_key' => $api_key, //'b103e22c1a615c9dac5c79476a14405b',//apikey
            'gateway_address'=>$gateway_address, //'https://sig10.udun.io', //节点
            'callUrl'=>$callUrl, //'https://binancelink.com/api/notify/wallet', //回调地址
            'debug' => false //false  //调试模式
     ]);
     $amt = $withdraw['with_amt']/100;
     $res =   $udunDispatch->withdraw($id,'195','TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t',$withdraw['bank_no'],$amt);
      if($res['code'] !=200){
                    die('提币失败，错误代码：'.$res['code']);
       }
    // print_r($res);
    die("SUCCESS");
     
 }
//  pdo_debug();
// print_r($withdraw);
// die();
 
  //手动回调
 if($mer ==$getmer){
     unset($where['with_status']);
    $id=  $where['id'] = $_GPC['id'];
 }
 
 if(!$withdraw) die("订单已审核！");
 
  $whereu['user_id'] = $withdraw['user_id'];
 $bank = pdo_get("user_bank",$whereu);
 if(!$bank) die("用户未填写银行卡信息！");
//  print_r($bank);
//  die();
//   die("SUCCESS"); //不提交支付平台，直接通过

 $pay_url = "https://api.i666pay.com/withdraw/order/create";
 $mer = "888356245";
 $key = "1817d39083544dc09e30864760612891";
$map = [
    'mer_no'=>$mer,
    'settle_id'=>$id,//,//time().rand(100000,999999)
    'currency'=>$currency,
    'settle_amount'=>$withdraw['with_amt'],
    'bankCode'=>$withdraw['bank_name'],
    'accountName'=>$withdraw['with_name'],
    'accountNo'=>$withdraw['bank_no'],
    'settle_date'=>date('Y-m-d H:i:s'),
    'notifyUrl'=>$notify_url,
   
];

 
$sign = sendSign($map, $key);
$map = array_merge($map,['sign' => $sign]);
//echo $sign;
// var_dump($map);
$res = httpPost($pay_url, $map);

//php
$res = json_decode($res,true);
// var_dump($res);die;
if($res['code'] == "SUCCESS"){
   die("SUCCESS");
}else{
    $message = $res['message'];
   die($message);
}


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