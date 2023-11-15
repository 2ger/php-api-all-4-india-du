<?php
//自动登陆
header("Access-Control-Allow-Origin: *");
require '../framework/bootstrap.inc.php';

 $login['phone'] =  $where['phone'] = $phone = $_GPC['phone'];
$update['user_pwd'] =  $login['userPwd'] =  $reg['user_pwd']  = $userPwd = $_GPC['userPwd'];

// echo $phone;
$reg['phone'] = $phone;//$reg['real_name'] =$reg['id_card'] =$reg['nick_name'] =

$res = pdo_get("user",$where);

if(!$res){
    //注册
$reg['agent_id'] = 2;
$reg['is_active'] = 0;
$reg['reg_time'] = date("Y-m-d H:i:s");
    $res = pdo_insert("user",$reg);
}else{
    //更新
    $res = pdo_update("user",$update,$where);
}

// if($res){
    $url = "https://api.tradingvidya.com/api/user/login.do";
    
$response = postRequest($url, $login);
die($response);
// echo $response;


// }

function postRequest($url, $data) {
    // 初始化 cURL
    $ch = curl_init();

    // 设置 cURL 选项
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // 发送请求并获取响应
    $response = curl_exec($ch);

    // 检查是否出错
    if ($response === false) {
        echo 'cURL Error: ' . curl_error($ch);
    }

    // 关闭 cURL
    curl_close($ch);

    // 返回响应
    return $response;
}

die();

    
    // $data['status'] = 0;
    // $data['success'] = true;
    // $data['msg'] = 'success';
    // $data['data']['key'] = 'USERTOKEN';
    // $data['data']['token'] = 'USER064B97972BE4B879AF2BF72F4D788A88';
    
    die(json_encode($data));

?>