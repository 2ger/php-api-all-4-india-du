<?php
// http://www.uudes.com/page2

$merId = "751ef1c2d2884dc68dad3233853ad8d7";
$appId = "fc0cb16afc7f41e89fbf9e2cf3f40d0d";
$private_key = "488562eef7e24a4e89bc5a300e978cc5";
$gatewayUrl = 'https://pay.desles.xyz/pay/payOrder/yd/pay';


$data['machId'] = $merId; //->merId
$data['merchantOrderNo'] = $_GET['id'];
$data['amount'] = $_GET['amt']*1; //美元汇率
$data['name'] = $_GET['user_id'];//'test';
$data['phone'] = '603223442344';
$data['email'] = 'test@mail.com';
$data['returnUrl'] = "https://trade.pgim.pro/api/uudes/notify.php";
$data['successUrl'] = "https://trade.pgim.pro/wap"; 


$data = encrypt($data); 

// var_dump($data);
// die();

$dataPost = $data;
$dataPostJson = json_encode($dataPost, JSON_UNESCAPED_SLASHES );
var_dump($dataPostJson); echo '<hr>';
$header = array(
	'Content-Type: application/json',
	'Content-Length: ' . strlen($dataPostJson)
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $gatewayUrl);
//curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $dataPostJson);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//curl_setopt($ch, CURLOPT_USERAGENT, $User_Agent);
// Receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec($ch);
curl_close ($ch);

echo '$server_output: ';
var_dump($server_output);	// die;
echo '<hr>';


/**
 错误返回
 {"code":500,"message":"Signature verification failed"}

成功返回
{"code":0,"message":"执行成功","data":{"merchant":"1000009","businessCode":"100055","amount":100.00,"notifyUrl":"https://binoeo.com/api/notify/qpay","pageUrl":"https://binoeo.com/blue/assets.html","orderNo":"202304031092447036903796736","orderData":"https://api-india.deshengpay.vip/gataway/confirm/580242/2023040313543106473"}}
**/


// echo '<hr>';
    $dataRes = json_decode($server_output,true);
// var_dump($dataRes);
echo '<hr>';
if ($dataRes['code'] ==200) {
    $payUrl = $dataRes['paymentUrl'];
    echo '<a href="'.$payUrl.'" target="_blank">Go to Payment</a>';
    header("location:$payUrl");//测试时注示这行，以看单号
} else {
    echo 'faild!'.$dataRes['message'];
}


    //加密
    function encrypt($data){
        global $private_key;
        ksort($data);
        $str = '';
        foreach ($data as $k => $v){
            if(!empty($v)){
                $str .=(string) $k.'='.$v.'&';
            }
        }
        $str .= "key=".$private_key;
        // echo $str;
        // $str = rtrim($str,'&');
        $encrypted = md5($str);
   
        $data['sign']=$encrypted;
        return $data;
    }
