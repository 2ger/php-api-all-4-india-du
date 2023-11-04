<?php
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
$gatewayUrl = 'https://api.doitwallet.asia/Merchant/Pay';


$orderNo = $_GET['orderNo'];
$amount = $_GET['amount']*1; //美元汇率
$notifyUrl = urlencode("https://tradingvidya.com/api/OMPay/notify.php");
$pageUrl = urlencode("https://tradingvidya.com/wap/#/user"); 
// $data['subject'] = 'test'.$_GET['uid'];

//MD5(serialNo + {API KEY} + {Secret KEY} + amount) 
$token = MD5($orderNo.$APIKey.$mch_private_key.$amount); 

$payUrl = $gatewayUrl."?merchantCode=$merId&serialNo=$orderNo&currency=MYR&amount=$amount&returnUrl=$pageUrl&notifyUrl=$notifyUrl&token=$token";


// METHOD URL (Example)
// http://<domain>/Merchant/Pay?merchantCode={Merchant Id}&serialNo={Your
// Transaction id} &currency={Currency}&amount={Amount}&returnUrl={Return URL}
// &notifyUrl&={Callback URL} &token={MD5 token}

// // echo '<hr>';
//     $dataRes = json_decode($server_output,true);
// var_dump($dataRes);
// echo '<hr>';
// if ($dataRes['code'] ==0) {
//     $payUrl = $dataRes['data']['orderData'];
    echo '<a href="'.$payUrl.'" target="_blank">Go to Payment</a>';
    header("location:$payUrl");//测试时注示这行，以看单号
// } else {
//     echo 'faild!'.$dataRes['message'];
// }

   
