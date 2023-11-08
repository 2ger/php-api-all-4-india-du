<?php
//持机 更新持仓中的价格
// 挂机采集 https://tradingvidya.com/api/cloud_pass_auto.php?op=auto
//测试采集 https://tradingvidya.com/api/cloud_pass_auto.php?op=auto&limit=1
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';

$op = $_GPC["op"];
$limit = $_GPC["limit"];//带参数则不限制

if(!$limit){ 
//时间限制，仅8-5点执行
$hour = date("H");
if(($hour >=9 &&$hour <12) ||($hour>=14 && $hour <17)){
    echo $hour."点，开始采集";
}else{
    echo $hour."点，未开盘";
    die();
}

}

if($op == "auto"){
    
//持仓
    $list = pdo_fetchall("SELECT p.stock_gid,p.stock_name,p.stock_code FROM `user_position` as p  where  p.stock_gid like '%mys%' group by p.stock_code order by p.id desc");
    // if(!$list) die("无任务");
    
    $max = count($list)-1;
    echo $max."个【持仓】 正在更新，请不要关闭浏览器<br>";
    $i=0;
    if($_GPC["i"]) $i = $_GPC["i"];
    if($i>$max)$i =0;
    
    // var_dump($list[$i]);
     
    $end = $i+5;
    for($i;$i<$end;$i++){
        if($i<=$max){
            
       echo $i." - ".$list[$i]['stock_code']."<br>";
      echo "<iframe src='cloud_pass.php?code=".$list[$i]["stock_code"]."' style='display:none'>".$list[$i]["stock_code"]."</iframe><br>"; 
        }
       
    }
    // sleep(10);
    $url = "https://tradingvidya.com/api/cloud_pass_auto.php?op=auto&i=".$i."&limit=".$limit;
    // header("location:$url");
    
}
?>

<html>
    <body>
    <H1>
        
    </H1>
    </body>
    <script>
        var url = "<?=$url?>"
        window.setTimeout(function(){
            
            window.location.href = url;
        },10000)

    </script>
</html>
