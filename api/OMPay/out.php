<?php
// qpay https://doc.qg-pay.com/

require '../../framework/bootstrap.inc.php';
$merId = "1000149";
$mch_private_key = "MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAJh4zOqBZyeaSH+sb0yokWNz07ulmSJ1WdhVKxf6KQciw0zMqIYjY8KcR+y/tzuyAwQRn++npB9cNY4/oTsFRjJSM696F6K4xRFqYXeC9xmkiwcAHBaIUGpX0jKBpS95e2e8VMHxCK/Lfk3RZUnt/1SvKmNzUEIpFZXksqQIgFKVAgMBAAECgYAsinWKN/0zc32kVnI5GbFHNUnwMkMW7uMtMEwjd//ORth4sfy1GteEvXTBc4wKk0kQQjnPn9BxHNXEVVA7VqIXkFJPAu/DNAYfPD3/N0Q+Lk5VphedPDlfWiA29yw4n0SgcOANLUCBmCZwuU5NkRzxmE9jCnYtBAsp4y5VwNY34QJBAPvvUM1jssK9Jt6yXMWBV12w53oMtLMs5BRpsDIUdiybDOjThUm4vIHBBHF8SoLxcE1tOjALp0tmjO5AfSmxRM0CQQCa7qBdvxL1HgeHzZlaQ1eCoTbMSM8YKaQWudxggTO84yPV1pnL3HmVgKy3R7KGlGNQuv2DRm4zpchi7VGha4TpAkEAkLidsw6VbraXsI/HKRGurTxlDEBmQRMFhhBcTbhhVihPVyPCymGbr/G/6q0aZHPrLh8TsOvQ00h6ppZXD+8r9QJADwc4jP9cwmQATP73chb4JRaoLxac5/YaEY5DvySpNRg/QJ3JW0nujT7nAfw1Z/J607jfoF0zkVTyZA4rDeJVCQJAV1C8HJ6vt5ho1lnOpmWijvwJJE8VFbaqSBHTSrcVp0a6n84W4uYIrRVt2gR3CVArDNj7Axr9jYspiXVgG/BCAA==";
$gatewayUrl = 'https://api.qg-pay.com/singleOrder';


$data['merchant'] = $merId; //->merId
$data['businessCode'] = "100003"; 
$data['bankCode'] = 'BANK';

$data['phone'] = "100055"; 
$data['notifyUrl'] = "https://coindancy.com/api/notify/qpay";
$data['remake'] = 'test';


$data['province'] = $_GET['province'];
$data['orderNo'] = $_GET['orderNo'];
$data['accNo'] =  $_GET['accNo'];
$data['accName'] = $_GET['realname'];
$data['orderAmount'] = $_GET['amount']*$_W['config']['usd']['inr']; //美元汇率


//test
// $data['orderNo'] = "SKD-".rand(000000000, 999999999); //->orderId
// $data['amount'] = 100*82.42; 
// $data['bankCode'] = "USD";//银行编码

$data = encrypt($data); 

// var_dump($data);
// die();

$dataPost = $data;
$dataPostJson = json_encode($dataPost, JSON_UNESCAPED_SLASHES );
// var_dump($dataPostJson); echo '<hr>';
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
// curl_close ($ch);

die($server_output);
// echo '$server_output: ';
// var_dump($server_output);	// die;
// echo '<hr>';


/**
 错误返回
 {"code":500,"message":"Signature verification failed"}

成功返回
{"code":0,"message":"执行成功","data":{"merchant":"1000009","businessCode":"100055","amount":100.00,"notifyUrl":"https://multicurrency.capital/api/notify/qpay","pageUrl":"https://multicurrency.capital/blue/assets.html","orderNo":"202304031092447036903796736","orderData":"https://api-india.deshengpay.vip/gataway/confirm/580242/2023040313543106473"}}
**/


// echo '<hr>';
    // $dataRes = json_decode($server_output,true);
// var_dump($dataRes);
// echo '<hr>';
// if ($dataRes['code'] ==0) {
//     $payUrl = $dataRes['data']['orderData'];
//     echo '<a href="'.$payUrl.'" target="_blank">Go to Payment</a>';
//     header("location:$payUrl");//测试时注示这行，以看单号
// } else {
//     echo 'faild!'.$dataRes['message'];
// }


    //解密
    function decrypt($data){
        global $mch_public_key;
        ksort($data);
        $toSign ='';
        foreach($data as $key=>$value){
            if(strcmp($key, 'sign')!= 0  && $value!=''){
                $toSign .= $key.'='.$value.'&';
            }
        }
        $str = rtrim($toSign,'&');
        $encrypted = '';
        //替换自己的公钥
        $pem = chunk_split( $mch_public_key,64, "\n");
        $pem = "-----BEGIN PUBLIC KEY-----\n" . $pem . "-----END PUBLIC KEY-----\n";
        $publickey = openssl_pkey_get_public($pem);

        $base64=str_replace(array('-', '_'), array('+', '/'), $data['sign']);

        $crypto = '';
        foreach(str_split(base64_decode($base64), 128) as $chunk) {
            openssl_public_decrypt($chunk,$decrypted,$publickey);
            $crypto .= $decrypted;
        }

        if($str != $crypto){
            exit('sign fail');
        }

    }


    //加密
    function encrypt($data){
        global $mch_private_key;
        ksort($data);
        $str = '';
        foreach ($data as $k => $v){
            if(!empty($v)){
                $str .=(string) $k.'='.$v.'&';
            }
        }
        $str = rtrim($str,'&');
        $encrypted = '';
        //替换成自己的私钥
        $pem = chunk_split($mch_private_key, 64, "\n");
    
        $pem = "-----BEGIN PRIVATE KEY-----\n" . $pem . "-----END PRIVATE KEY-----\n";
    
        $private_key = openssl_pkey_get_private($pem);
        $crypto = '';
        foreach (str_split($str, 117) as $chunk) {
            openssl_private_encrypt($chunk, $encryptData, $private_key);
            $crypto .= $encryptData;
        }
        $encrypted = base64_encode($crypto);
        $encrypted = str_replace(array('+','/','='),array('-','_',''),$encrypted);

        $data['sign']=$encrypted;
        return $data;
    }
