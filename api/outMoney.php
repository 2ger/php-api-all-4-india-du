<?php
header("Access-Control-Allow-Origin:*");
require '../framework/bootstrap.inc.php';

$amt = $_GPC['amt'];
$amt = $amt*$_W['config']['usd']['inr'];

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(0);
$token = $_SERVER['HTTP_USERTOKEN'];
$user = json_decode($redis->get($token));
if (!$user) die("please login ");
$user_id = $user->id;
$user = pdo_get("user",["id"=>$user_id],["id","is_lock","agent_id","nick_name","enable_amt"]);
$user_bank = pdo_get("user_bank",["user_id"=>$user_id],["id","bank_img","bank_name","bank_no","bank_address"]);

$isLock = $user['is_lock'];
if($isLock){
    $res['status'] = 1;
    $res['msg'] = "Your are locked for trading!";
    die(json_encode($res));
}
if(!$user_bank["bank_img"]){
    $res['status'] = 1;
    $res['msg'] = "Please input your wallet_address!";
    die(json_encode($res));
}

    $res=[
        "data"=>"",
        "status"=>1,
        "msg"=>""
        ];
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  
    if(!$amt|| $amt > ($user['enable_amt'])){

    $res['msg'] = "Insufficient balance";
        
    }else{
         $in_data=[
            "user_id"=>$user_id,
            "nick_name"=>$user["nick_name"],
            "agent_id"=>$user["agent_id"],
            "with_amt"=>$amt,
            "apply_time"=>date("Y-m-d H:i:s",time()),
            "bank_name"=>"USDT",
            "bank_no"=>$user_bank["bank_img"]
            ];
            $in= pdo_insert("user_withdraw", $in_data);
        if($in){
            $update["enable_amt -="] = $amt;
            pdo_update("user",$update,["id"=>$user_id]);
            $res['status']=0;
            $res['data']=$in_data;
            $res['msg']="success";
        }
    }
    // pdo_debug();
    
}

 die(json_encode($res));



?>