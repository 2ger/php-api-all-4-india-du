<?php
//uduncloud

require_once  '../../vendor/autoload.php';
require '../../framework/bootstrap.inc.php';

use Udun\Dispatch\UdunDispatch;

// ini_set('display_errors', 1); //打开错误提示
// ini_set('error_reporting', -1); //显示所有错误


$merchant_no = "316538";
$api_key = "69051a2a4333070ddbc1acc898805543";
$gateway_address = "https://sig10.udun.io";
$callUrl = "https://etormarketing.com/api/uduncloud/notify.php";

$address_type=isset($_GPC['type'])?$_GPC['type']:'trc20';

$where['id'] = $uid = $_GPC["user_id"];
if($address_type=='trc20'){
    $address = pdo_fetchcolumn("select address_trc20 from user where id = $uid");

}elseif($address_type=='erc20'){
    $address = pdo_fetchcolumn("select address_erc20 from user where id = $uid");
}


if(!$address){
    if($address_type=='trc20'){
    $address = $update['address_trc20'] = generate_udun_address($merchant_no,$api_key,$gateway_address,$callUrl);
        
    }elseif($address_type=='erc20'){
     $address = $update['address_erc20'] = generate_udun_address_erc20($merchant_no,$api_key,$gateway_address,$callUrl);
}
    pdo_update("user",$update,$where);
    echo $address;
}

$whereOrder['order_sn'] =  $_GPC["id"];
$updateOrder['pay_channel'] =  2;
$updateOrder['pay_img'] = $address;
    pdo_update("user_recharge",$updateOrder,$whereOrder);

// pdo_debug();
 //生成u盾地址
     function generate_udun_address($merchant_no,$api_key,$gateway_address,$callUrl)
    {  
        $udunDispatch = new UdunDispatch([
            'merchant_no' => $merchant_no, //;309634, //商户号
            'api_key' => $api_key, //'b103e22c1a615c9dac5c79476a14405b',//apikey
            'gateway_address'=>$gateway_address, //'https://sig10.udun.io', //节点
            'callUrl'=>$callUrl, //'https://binancelink.com/api/notify/wallet', //回调地址
            'debug' => false //false  //调试模式
        ]);
        
          $address =  $udunDispatch->createAddress('195');
        if($address['code'] ==200){
             $new_address=$address['data']['address'];
        }else{
            $new_address='un_pay_udun';
            // $new_address='TKDUEGWtj7oPcwMagZTL1sGnh8pYtfcYAf'; //test
        }
        return $new_address;
    }
      //生成u盾地址 erc20
     function generate_udun_address_erc20($merchant_no,$api_key,$gateway_address,$callUrl)
    {  
          $udunDispatch = new UdunDispatch([
            'merchant_no' => $merchant_no, //;309634, //商户号
            'api_key' => $api_key, //'b103e22c1a615c9dac5c79476a14405b',//apikey
            'gateway_address'=>$gateway_address, //'https://sig10.udun.io', //节点
            'callUrl'=>$callUrl, //'https://binancelink.com/api/notify/wallet', //回调地址
            'debug' => false //false  //调试模式
        ]);
        
          $address =  $udunDispatch->createAddress('60');
        if($address['code'] ==200){
             $new_address=$address['data']['address'];
        }else{
            $new_address='un_pay_udun';
            // $new_address='TKDUEGWtj7oPcwMagZTL1sGnh8pYtfcYAf'; //test
        }
        return $new_address;
    } //生成u盾地址 erc20
     function generate_udun_address_btc()
    {  
          $udunDispatch = new UdunDispatch([
            'merchant_no' => $merchant_no, //;309634, //商户号
            'api_key' => $api_key, //'b103e22c1a615c9dac5c79476a14405b',//apikey
            'gateway_address'=>$gateway_address, //'https://sig10.udun.io', //节点
            'callUrl'=>$callUrl, //'https://binancelink.com/api/notify/wallet', //回调地址
            'debug' => false //false 调试模式
        ]);
        
          $address =  $udunDispatch->createAddress('0');
        if($address['code'] ==200){
             $new_address=$address['data']['address'];
        }else{
            $new_address='un_pay_udun';
            // $new_address='TKDUEGWtj7oPcwMagZTL1sGnh8pYtfcYAf'; //test
        }
        return $new_address;
    }
    //检验u盾地址
     function check_udun_address($address)
    {  
        $udunDispatch = new UdunDispatch([
            'merchant_no' => $merchant_no, //;309634, //商户号
            'api_key' => $api_key, //'b103e22c1a615c9dac5c79476a14405b',//apikey
            'gateway_address'=>$gateway_address, //'https://sig10.udun.io', //节点
            'callUrl'=>$callUrl, //'https://binancelink.com/api/notify/wallet', //回调地址
            'debug' => false //false  //调试模式
        ]);
        
          $res =  $udunDispatch->existAddress('195',$address);
        
          //验证地址合法性
//var_dump($result->checkAddress('195','TEpK1aWkjDue6j8reeeMqG7hdJ5tRytyAF'));
//返回内容：  { ["code"]=> int(200) ["message"]=> string(7) "SUCCESS" ["data"]=> NULL }
//var_dump($result->existAddress('195','TEpK1aWkjDue6j8reeeMqG7hdJ5tRytyAF'));
 //返回内容： { ["code"]=> int(200) ["message"]=> string(7) "SUCCESS" ["data"]=> NULL }
 
        if($res['code'] ==200){
             $new_address=$address;
            //  $new_address='ok';
        }else{
            $new_address='address check failed';
            // $new_address='TKDUEGWtj7oPcwMagZTL1sGnh8pYtfcYAf'; //test
        }
        return $new_address;
    }
    
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta data-n-head="ssr" name="viewport" content="width=device-width,initial-scale=1">
    <title>Pay types</title>
    <script src="https://cdn.bootcdn.net/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    
</head>
<body style="text-align:center">
    
    
<div class="title">
    ---<?php echo strtoupper($address_type);?> USDT ---
</div>

<div id="qrcode" style="margin:0px auto;width:256px;"></div>
<script type="text/javascript">
new QRCode(document.getElementById("qrcode"), "<?=$address?>");
</script>
<!-- Target -->
<input  id="foo" type="text" readonly="true"  value="<?=$address?>" style="
    padding: 10px;
    margin: 10px auto;
    width: 260px;
"/>

<br/>
<button onclick="window.history.go(-1)" class="btn banks">Go Back</button>
<!-- Trigger -->
<button class="btn wallet"   style=" float: left;line-height: 0;" id="copy-btn">
   Copy address
</button>


<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
<script>
    $(document).ready(function(){
        $("#copy-btn").click(function(){
            $("#foo").select();
            document.execCommand("copy");
            console.log("ddd")
        });
    });
</script>
<style>
.title{
      margin: 50px 20px;
        color: #00112c;
        text-align: center;
        font-size: 18px;
}
    .btn{
        width:40%;
        /*display: block;*/
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
    font-weight: 700;float: left;   line-height: 0;
    }
    .wallet{
            background-color: #00112c;
    }
</style>

</body>
</html>