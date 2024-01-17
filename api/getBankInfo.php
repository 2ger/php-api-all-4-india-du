<?php
header("Access-Control-Allow-Origin:*");
require '../framework/bootstrap.inc.php';

$bank_name = $_GPC['bank_name'];
$bank_no = $_GPC['bank_no'];
$bank_address = $_GPC['bank_address'];
$ifsc = $_GPC['ifsc'];

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(0);
$token = $_SERVER['HTTP_USERTOKEN'];
$user = json_decode($redis->get($token));
if (!$user) die("please login ");
$user_id = $user->id;
  $user_bank = pdo_get("user_bank",["user_id"=>$user_id],["id","bank_name","bank_no","bank_address","ifsc"]);

    $res=[
        "data"=>"",
        "status"=>1,
        "msg"=>""
        ];
if($_SERVER['REQUEST_METHOD'] == 'GET'){

    if($user_bank)
    {
    $res['data'] = $user_bank;
    $res['status'] = 0;
    $res['msg'] = "success";
    }else{
  
    $res['msg'] = "no data";
    }
    
   
}elseif($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    if(!$bank_name||!$bank_no||!$ifsc){

    $res['msg'] = "error";
        
    }elseif($user_bank){
        
        $up_where['id'] = $user_bank["id"];
        $up_data=[
            "bank_name"=>$bank_name,
            "bank_no"=>$bank_no,
            "ifsc"=>$ifsc
            ];
       $up=  pdo_update("user_bank", $up_data, $up_where);
       if($up){
           $res['status']=0;
       }
        
    }else{
         $in_data=[
            "user_id"=>$user_id,
            "bank_name"=>$bank_name,
            "bank_no"=>$bank_no,
            "bank_address"=>$bank_address?$bank_address:"123",
            "ifsc"=>$ifsc,
            "add_time"=>date("Y-m-d H:i:s",time())
            ];
        $in= pdo_insert("user_bank", $in_data);
        if($in){
            $res['status']=0;
        }
    }
    // pdo_debug();
    
}

 die(json_encode($res));



?>