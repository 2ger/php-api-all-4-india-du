<?php
// qpay https://doc.qg-pay.com/

require '../framework/bootstrap.inc.php';
$merId = "1000784";
$mch_private_key = "MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAKARNXYiLsUp7k6KEHV8AjB7TXOCtXJamAaQjyPu4D52B1Eh4CYQCUNhy8On5ZXmQNDW458e9/dwacJ4YHuAq3jlM8YF9S8HqGZeVY0ZjVKabuWe9blC4UGSexFPa7lnZx/wW1w1z2/G0fA+Sk9zqGv3yZgO6/+Nes4Pe8dIxO7/AgMBAAECgYALFCXXH1aNXvGXjgbFmuer41zg+dMFLzAGhErj+cybhh/LhcS13bZGa6ZmzGAfl+g0jCsFAGe5QJaRVzOWWLmFcGaHncMyj33VxDbxbw0LfZ3hzqEDLqrcZCkP/LDKzo5hDTJdZEituMaCHZDJjn6Q3wPJXSeXnWHBeo8URBqZgQJBALVLI7D2mrRU+gldMV31pB/zd4mbMQtiqfgwZjaKg/lIPYagr9rqSm0OQ6Q+v0m0BehVunA3/wy1/p6jXx/mlfkCQQDiBuIz4E+Lva4iloQHh7xTH87msKqMfJ0Tb+igvKpnu5OmfR+QR+k4HVwBRk68r1roz/M9IBezQmQxJwi2tAq3AkBdWzgh5Jt9yVSIhejqDZhaq7Eetz/mMQR9vc6kv2d+cujb7tsfzA5PYk0KwxUWCxIPtjWvm+ZG0WEwp8hQURlhAkBdGDq45S2+P7zmUBpHQ7fkgNhmGePVA0prBA/LjImfOhohW63Rblz3mNgZSk0J2CvYcjYcOgio87JysEIdhmBXAkEAqzcjahGDNAK7QdgCE+sgLaf5v0FDns9G+Xzuz8my7kwkzEY5/zNnoW1Erh8g2ajFAjV+l1+RuhKH7mp2pGBCeg==";
$gatewayUrl = 'https://api.qg-pay.com/singleOrder';


$data['merchant'] = $merId; //->merId
$data['businessCode'] = "100003"; 
$data['bankCode'] = 'BANK';

$data['phone'] = "100055"; 
$data['notifyUrl'] = "https://trade.pgim.pro/api/qpay/notify.php?type=SUCCESS";
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
