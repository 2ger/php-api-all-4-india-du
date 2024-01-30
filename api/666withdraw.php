<?php
header("Access-Control-Allow-Origin: *");
require '../framework/bootstrap.inc.php';

// qpay
 $getmer = $_GPC['mer'];//手动回调
 $where['id'] = $id= $_GPC['withId'];//$_GPC['id'];
 $where['with_status'] = 0;
 
//  var_dump($_GPC['__input']['withId']);
 
// print_r($where);
 $withdraw = pdo_get("user_withdraw",$where);
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
 
//   die("SUCCESS"); //不提交支付平台，直接通过
$merId = "1000784";
$mch_private_key = "MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAKARNXYiLsUp7k6KEHV8AjB7TXOCtXJamAaQjyPu4D52B1Eh4CYQCUNhy8On5ZXmQNDW458e9/dwacJ4YHuAq3jlM8YF9S8HqGZeVY0ZjVKabuWe9blC4UGSexFPa7lnZx/wW1w1z2/G0fA+Sk9zqGv3yZgO6/+Nes4Pe8dIxO7/AgMBAAECgYALFCXXH1aNXvGXjgbFmuer41zg+dMFLzAGhErj+cybhh/LhcS13bZGa6ZmzGAfl+g0jCsFAGe5QJaRVzOWWLmFcGaHncMyj33VxDbxbw0LfZ3hzqEDLqrcZCkP/LDKzo5hDTJdZEituMaCHZDJjn6Q3wPJXSeXnWHBeo8URBqZgQJBALVLI7D2mrRU+gldMV31pB/zd4mbMQtiqfgwZjaKg/lIPYagr9rqSm0OQ6Q+v0m0BehVunA3/wy1/p6jXx/mlfkCQQDiBuIz4E+Lva4iloQHh7xTH87msKqMfJ0Tb+igvKpnu5OmfR+QR+k4HVwBRk68r1roz/M9IBezQmQxJwi2tAq3AkBdWzgh5Jt9yVSIhejqDZhaq7Eetz/mMQR9vc6kv2d+cujb7tsfzA5PYk0KwxUWCxIPtjWvm+ZG0WEwp8hQURlhAkBdGDq45S2+P7zmUBpHQ7fkgNhmGePVA0prBA/LjImfOhohW63Rblz3mNgZSk0J2CvYcjYcOgio87JysEIdhmBXAkEAqzcjahGDNAK7QdgCE+sgLaf5v0FDns9G+Xzuz8my7kwkzEY5/zNnoW1Erh8g2ajFAjV+l1+RuhKH7mp2pGBCeg==";
$gatewayUrl = 'https://api.qg-pay.com/singleOrder';

$data['merchant'] = $merId; //->merId
$data['businessCode'] = "100003"; 
$data['orderNo'] = $withdraw['id'];
$data['accName'] = trim($withdraw['with_name']);
$data['accNo'] =  trim($bank['bank_no']);
$data['orderAmount'] = $withdraw['with_amt']; //美元汇率
$data['bankCode'] = 'IDPT0007';
$data['province'] = trim($bank['bank_address']?$bank['bank_address']:$bank['ifsc']);//ifsc

$data['phone'] = "15112345678"; 
$data['notifyUrl'] = "https://trade.pgim.pro/api/qpay/notify.php";
$data['remake'] = 'test';


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

// die($server_output);
$dataRes = json_decode($server_output,true);
// var_dump($dataRes);
if ($dataRes['code'] ==0) {
   die("SUCCESS");
} else {
   die($dataRes['message']);
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
?>