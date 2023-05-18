<?php
//处理无价格订单问题
// https://tradingdiario.com/api/nullPrice.php?op=list
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';

$op = $_GPC["op"];
if($op == "list"){
   //持仓
    $list = pdo_fetchall("SELECT p.stock_gid,p.stock_name,p.stock_code,r.close FROM `user_position` as p left join `real_time_data` as r on p.stock_code=r.stock_code where r.close is null GROUP by stock_code  ORDER BY `p`.`stock_gid` ASC");
    // if(!$list) die("无任务");
    echo count($list)."个【持仓】无价格，请点击更新<br>";
  
    foreach ($list as $val){
        $type = substr($val["stock_gid"], 0 ,2);
        if($type != "us" && $type != "hk"){
           echo "<a href='https://tradingdiario.com/api/getMaStock.php?time=2W&insert=1&code=".$val["stock_code"]."'>".$val["stock_code"]."</a><br>"; 
        }else{
           echo "<a href='nullPrice.php?stock_code=".$val["stock_code"]."&op=".$type."'>".$val["stock_gid"]."</a><br>"; 
        }
        
    }
    //新股
      $list = pdo_fetchall("SELECT p.new_code,r.close FROM `user_stock_subscribe` as p left join `real_time_data` as r on p.new_code=r.stock_code where r.close is null GROUP by stock_code  ORDER BY `p`.`new_code` ASC");
    // if(!$list) die("无任务");
    echo count($list)."个【新股】无价格，请点击更新<br>";
  
    foreach ($list as $val){
        $type = substr($val["stock_code"], 0 ,2);
        // if($type != "us" && $type != "hk"){
           echo "<a href='https://tradingdiario.com/api/getMaStock.php?time=2W&insert=1&code=".$val["new_code"]."'>".$val["new_code"]."</a><br>"; 
        // }else{
           echo "<a href='nullPrice.php?stock_code=".$val["stock_code"]."&op=".$type."'>".$val["stock_gid"]."</a><br>"; 
        // }
        
    }
    //自选
      $list = pdo_fetchall("SELECT p.stock_code,r.close,p.stock_gid FROM `stock_option` as p left join `real_time_data` as r on p.stock_code=r.stock_code where r.close is null GROUP by stock_code  ORDER BY `p`.`stock_code` ASC");
    if(!$list) die("无任务");
    echo count($list)."个【自选】无价格，请点击更新<br>";
  
    foreach ($list as $val){
        $type = substr($val["stock_gid"], 0 ,2);
        if($type != "us" && $type != "hk"){
            
           echo "<a href='https://tradingdiario.com/api/getMaStock.php?time=2W&insert=1&code=".$val["stock_code"]."'>".$val["stock_gid"]."</a><br>"; 
        }else{
           echo "<a href='nullPrice.php?stock_code=".$val["stock_code"]."&op=".$type."'>".$val["stock_gid"]."</a><br>"; 
        }
        
    }
    die();
}

//手动写入hk\us价格
if($op == "hk" ||$op == "us"){
    $url = "https://api.my.shopro.pro/api/stock/getStock.do?pageNum=1&pageSize=15&stockPlate=&stockType=".$op."&keyWords=".$_GPC['stock_code'];
    $data0 = file_get_contents($url);
    $data0 = json_decode($data0,true);
    // print_r($data0); 
    // die();
    $val =$data0['data']['list'][0];
    
      $data['stock_code']= $val['code'];
     $data['stock_gid']= $val['gid'];
     $data['open']= $val['open_px'];
     $data['close']= $val['nowPrice'];
     $data['high']= $val['today_max'];
     $data['low']= $val['today_min'];
     $data['volume']='100';// $val[5]
     $data['timestamp']= date('Y-m-d H:i:s',time());
     $data['add_time']=  date('Y-m-d H:i:s',time());
     $res =  pdo_insert("real_time_data",$data);
       $id = pdo_insertid();
     

    if($res){
       echo "写入成功 ".$id;
    }else{
       echo "写入失败";
    }
       die();  
    
}


echo "op=list";
