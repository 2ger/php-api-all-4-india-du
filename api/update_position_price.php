<?php
//持机 更新持仓中的价格
// 挂机采集 https://trade.pgim.pro/api/update_position_price.php?op=auto
//测试采集 https://trade.pgim.pro/api/update_position_price.php?op=auto&limit=1
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';

$op = $_GPC["op"];
$limit = $_GPC["limit"];//带参数则不限制

if(!$limit){ 
//时间限制，仅8-5点执行
$hour = date("H");
// if(($hour >=9 &&$hour <12) ||($hour>=14 && $hour <17)){
//     echo $hour."点，开始采集";
// }else{
//     echo $hour."点，未开盘";
//     die();
// }

}

if($op == "auto"){
    
//持仓
    $list = pdo_fetchall("SELECT p.stock_gid,p.stock_name,p.stock_code FROM `user_position` as p  where  p.stock_gid like '%mys%' group by p.stock_code order by p.id desc");
    // if(!$list) die("无任务");
    
    
   foreach ($list as $item){
       //查网上
       $code = $item["stock_code"];
        $url = "https://marketservices.indiatimes.com/marketservices/companyshortdata?companyid=$code&companytype=equity";
        // echo $url.PHP_EOL;
        // continue;
        $data = file_get_contents($url);
        $data = json_decode($data,true);
        // var_dump($data);
        // continue;
        // $info = $data->bse;
        $info = $data['nse'];
        // print_r($info->current);
        if($info&&$info['current'] >0){
            $res=[];
            //  var_dump($info);
            $res['volume'] =  $info['volume'];
            $where['stock_code'] = $code;
            //更新real_time_data
            $data_update['close'] =   $res['close'] =  $info['current'];
            $data_update['high'] =   $res['high'] = $info['high'];
            $data_update['low'] =   $res['low'] =  $info['low'];
            $data_update['open'] =   $res['open'] =  $info['open'];
            $data_update['add_time'] =   date("Y-m-d H:i:s");
            $update=  pdo_update("real_time_data",$data_update,$where);
            //更新stock
            $stock_update['increase_ratio'] = $info['percentChange'];
            $res = pdo_update("stock",$stock_update,$where);
            
            if($res) echo  $item["stock_name"]."更新成功，最新价格：".$info['current']."\n\n";
            // die();
        }
   }

    
}
?>

