<?php
// https://tradingdiario.com/wap/#/tgkline?name=DPHARMA-C5&code=0183&if_zhishu=0
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';

$code = $_GET['code'];
$time = $_GET['time'];
$insert = $_GET['insert'];


//从数据库查
 $res =  pdo_fetch("select * from real_time_data where stock_code = '".$code."' order by id desc ");
 if($res){
     $res['status'] =1;
     $res['open'] =$res['close'];
     
      die(json_encode($res));
  }

