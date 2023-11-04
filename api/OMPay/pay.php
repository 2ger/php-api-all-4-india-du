<?php
require '../../framework/bootstrap.inc.php';
// ompay https://api.doitwallet.asia/
/**
 * 商户: WWWW808 MYR

入金
1. 商户 (Merchant Id): 60-00000329-28643858
2. 商户代码 (API Key): D62E84F5-5FC3-4243-A427-2024FBF059B9
3. 密钥 (Secret Key): 0DF849BA870147AABA3EEAD101E3ECB5
4. 域名: https://api.doitwallet.asia/
5. 文档: https://api.doitwallet.asia/Documents/DepositAPI.pdf

FPX / GCASH
1. 商户 (Merchant Id): 60-00000329-28643858
2. 商户代码 (API Key): D62E84F5-5FC3-4243-A427-2024FBF059B9
3. 密钥 (Secret Key): 0DF849BA870147AABA3EEAD101E3ECB5
4. 域名: https://api.doitwallet.asia/
5. 文档: https://api.doitwallet.asia/Documents/FPXAPI.pdf

下发/代付
1. 商户代码 (Agent Code): 6AA365C5-8982-41F4-A9DA-634B6BC0D503
2. 密钥 (Secret Key): 43BB6238C5D945F3A11A9F32ECA835F7
3. 域名: https://payout.doitwallet.asia/
4. 文档: https://api.doitwallet.asia/Documents/PayoutAPI.pdf

后台登入
1. 网址: https://bo.doitwallet.asia/
2. 登入账号: WWWW808MYR
3. 密码: ko3o152r
 * **/
 


$merId = "60-00000329-28643858";
$APIKey = "D62E84F5-5FC3-4243-A427-2024FBF059B9";
$mch_private_key = "0DF849BA870147AABA3EEAD101E3ECB5";
$gatewayUrl = 'https://api.doitwallet.asia/merchant/reqfpx';


$orderNo = $_GET['orderNo'];
$amount = $_GET['amount']*1; //美元汇率
$notifyUrl = "https://tradingvidya.com/api/OMPay/notify.php";
$pageUrl = "https://tradingvidya.com/wap/#/user"; 
// $data['subject'] = 'test'.$_GET['uid'];


//更新通道信息
        $up['pay_channel'] =  2;
        $where1['order_sn'] =  $orderNo;
        $re1 = pdo_update("user_recharge",$up,$where1);
        

//MD5(serialNo + {API KEY} + {Secret KEY} + amount) 
$token = MD5($orderNo.$APIKey.$mch_private_key.$amount); 


$data['merchantCode'] = $merId; //->merId
$data['serialNo'] = $_GET['orderNo'];
$data['currency'] = 'MYR';
$data['amount'] = $_GET['amount']*1; //美元汇率
$data['notifyUrl'] = "https://tradingvidya.com/api/OMPay/notifyFPX.php";
$data['returnUrl'] = "https://tradingvidya.com/wap/#/user"; 
$data['token'] = $token; //->merId


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
{"code":0,"message":"执行成功","data":{"merchant":"1000009","businessCode":"100055","amount":100.00,"notifyUrl":"https://multicurrency.capital/api/notify/qpay","pageUrl":"https://multicurrency.capital/blue/assets.html","orderNo":"202304031092447036903796736","orderData":"https://api-india.deshengpay.vip/gataway/confirm/580242/2023040313543106473"}}
**/


// echo '<hr>';
    $dataRes = json_decode($server_output,true);
var_dump($dataRes);
echo '<hr>';
if ($dataRes['success'] == true) {
    $payUrl = $dataRes['data'];
    echo '<a href="'.$payUrl.'" target="_blank">Go to Payment</a>';
    header("location:$payUrl");//测试时注示这行，以看单号
} else {
    echo 'faild!'.$dataRes['message'];
}

   
