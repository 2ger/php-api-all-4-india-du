<?php
// qpay https://doc.qg-pay.com/

$merId = "1000214";
$mch_private_key = "MIICeAIBADANBgkqhkiG9w0BAQEFAASCAmIwggJeAgEAAoGBAJ4viHwPr+Qzz3cd53jYhOlnFLE1PnMjCMLZCatcr9VB8mkpAIEs0YTE0AE0JuX8ZZoKNW9kOpQgNxYgHUSc55oApsFbDEZwPI+EFaIAuuB/bztU1rnZ3FjY/yg53QUKnan2DdI2T1kvILcGasco0eN0xI1ZsPzSQTc4cPLefi0lAgMBAAECgYBRqEn1eQecbt5vKCHAcU0TS6IT5F9rgi9Ynj9ulXczSErB3GelRySVPCAALxLRcFxmi1SQPxY6NvMY2dUiATlQp6lnbVpyq01Pt+6vRJTQJzDzZdMENbLfAHb7Sdh5f+K93jb1Aj6REw0w4qFvsxXi/4Ik6JEEjZyX79QkKW1XwQJBAM4ELk/JfhkF0Xo6AfG3RxN/hMXNkstK1PT4rNmNiXhViNbT39nq9gkf7vXqdbIoebZO+PURiJm+FwpTamXpXVECQQDEkI5/f7ZLk24//UY2nodQTvJ9w8FQFzYusmQ3AP6ymUPPPoXopm/8Kbg2gBqVYSPIqofRoFxyrcbg5K6eoc2VAkEAmxnStAcChFw5BUg+xngxbqCGmVTXu22vPm8p0vAYGxxqVTJWSX33lY8RHNtTmLmYSITUMczthrHyLrf2VpuGwQJBAIANRCefRVy6sMoS71jJsZEJvUNc8WSBmpSVNl/2z/X9joVGT+od8zos24r1rVu/KpahMVXIyDEVMEh18aP5+HkCQQC1tqloMu6tRtxfajucLCuk8O46Y0lAGQv2IB44hgyRzLkZQdklZGwmOoLvUoIOOO+0hKEsx+nmFARS1e1cmwcU";
$gatewayUrl = 'https://api.qg-pay.com/singleOrder';


$data['merchant'] = $merId; //->merId
$data['businessCode'] = "100003"; 
$data['bankCode'] = 'BANK';

$data['phone'] = "100055"; 
$data['notifyUrl'] = "https://binuno.com/api/notify/qpay";
$data['remake'] = 'test';


$data['province'] = $_GET['province'];
$data['orderNo'] = $_GET['orderNo'];
$data['accNo'] =  $_GET['accNo'];
$data['accName'] = $_GET['realname'];
$data['orderAmount'] = $_GET['amount']*100; //美元汇率


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
{"code":0,"message":"执行成功","data":{"merchant":"1000009","businessCode":"100055","amount":100.00,"notifyUrl":"https://binoeo.com/api/notify/qpay","pageUrl":"https://binoeo.com/blue/assets.html","orderNo":"202304031092447036903796736","orderData":"https://api-india.deshengpay.vip/gataway/confirm/580242/2023040313543106473"}}
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
