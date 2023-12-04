<?php
//持机 每1分钟
// 采集印度股票 https://profitmarts.in/api/cai_india_stock_list.php
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';


//连接到 Redis 数据库
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(3);

$page = 1;
if($_GPC['page']) $page = $_GPC['page'];
// $url = 'https://etmarketsapis.indiatimes.com/ET_Stats/gainers?pageno='.$page.'&pagesize=1000&sortby=percentchange&sortorder=desc&sort=intraday&exchange=nse&marketcap=largecap%2Cmidcap&duration=1d';//462
// $url = 'https://etmarketsapis.indiatimes.com/ET_Stats/losers?pageno='.$page.'&pagesize=1000&sortby=percentchange&sortorder=asc&sort=intraday&exchange=nse&marketcap=largecap%2Cmidcap&duration=1d';//140
// $url = 'https://etmarketsapis.indiatimes.com/ET_Stats/hourlygainers?pageno='.$page.'&pagesize=1000&sortby=percentchange&sortorder=desc&service=gainers&exchange=nse&marketcap=largecap%2Cmidcap';//322
// $url = 'https://etmarketsapis.indiatimes.com/ET_Stats/hourlylosers?pageno='.$page.'&pagesize=1000&sortby=percentchange&sortorder=asc&service=losers&exchange=nse&marketcap=largecap%2Cmidcap';//301

$url = 'https://etmarketsapis.indiatimes.com/ET_Stats/moversvolume?pageno='.$page.'&pagesize=1000&sortby=volume&sortorder=desc&service=volumemovers&exchange=nse&marketcap=largecap%2Cmidcap';//609
// $url = 'https://etmarketsapis.indiatimes.com/ET_Stats/moversvalue?pageno='.$page.'&pagesize=1000&sortby=value&sortorder=desc&service=valuemovers&exchange=nse&marketcap=largecap%2Cmidcap'; //609

// $url = 'https://etmarketsapis.indiatimes.com/ET_Stats/onlybuyer?pageno='.$page.'&pagesize=1000&sortby=bestBuyQty&sortorder=desc&service=buyers&exchange=nse';
// $url = 'https://etmarketsapis.indiatimes.com/ET_Stats/onlyseller?pageno='.$page.'&pagesize=251&sortby=bestSellQty&sortorder=desc&service=sellers&exchange=nse';
// $url = 'https://etmarketsapis.indiatimes.com/ET_Stats/new52weekshigh?pageno='.$page.'&pagesize=251&sortby=percentchange&sortorder=desc&exchange=nse&marketcap=largecap%2Cmidcap';
// $url = 'https://etmarketsapis.indiatimes.com/ET_Stats/new52weekslow?pageno'.$page.'&pagesize=251&sortby=percentchange&sortorder=asc&exchange=nse&marketcap=largecap%2Cmidcap';
// $url = 'https://etmarketsapis.indiatimes.com/ET_Stats/near52weekshigh?pageno='.$page.'&pagesize=251&sortby=highPercentGap&sortorder=asc&exchange=nse&marketcap=largecap%2Cmidcap';
// $url = 'https://etmarketsapis.indiatimes.com/ET_Stats/fallfromhigh?pageno='.$page.'&pagesize=251&sortby=belowDaysHighPerChange&sortorder=asc&service=intradayhigh&exchange=nse&marketcap=largecap%2Cmidcap';
echo $url;

$response = file_get_contents($url);

$response = json_decode($response,true);
 $response =$response['searchresult'];
$count = count($response);
echo "\n 一共".$count."个 \n";
if($response){
    foreach($response as &$value) {
       $redis_data['stock_name']=    $stock['stock_spell'] =   $value['nseScripCode'];
       $redis_data['chinese_stock_name']=    $stock['stock_name'] = $value['companyName'];
        $where['stock_code'] =   $redis_data['stock_code']= $real['stock_code'] =     $stock['stock_code'] =  $value['companyId'];
       $redis_data['last_done']=  $value['current'];
       $redis_data['percent_change']= $stock['increase_ratio']= $value['aboveDaysLowPerChange'];
    
          //1 如没有则写入stock表
          $ss = pdo_fetch("select id,stock_code  from stock where stock_code = '".$value['companyId']."' order by id asc");
          $id = $ss['id'];
          
          $stock['stock_type'] =  "india";
         $real['stock_gid'] = $stock['stock_gid'] =  "mys".$value['companyId'];
          
          if(!$ss){
              $data['stock']  = pdo_insert("stock",$stock);
               $id = pdo_insertid();
                echo "\n >> 写入 股票 成功 ".$value['companyName'];
            }else{
                echo "\n 更新 股票 成功 ".$value['companyName'];
            $res  =  pdo_update("stock",$stock,$where);
             $id = $ss['id'];
          }
     
         
          // if(!$rvalue){}
          //2 写入redis
          $redis_data['id']= $id;
          $redis_data['created_on']= date("Y-m-d H:i:s");
          $redis_data['market']=  "Main MARKET";
          $redis_data['last_done']=  $redis_data['lacp']= $redis_data['buy_price']= $redis_data['sell_price']=$redis_data['high']=$redis_data['low']=$value['current'];
          $redis_data['volume']=$redis_data['buy_volume']=$redis_data['sell_volume']= $value['volume'];
          $redis_data['business_balance']=  $redis_data['change']= $value['aboveDaysLowPerChange'];
          
        $redis->set($stock['stock_gid'], json_encode($redis_data));
        //读取redis
        //   $rvalue = $redis->get($value['companyId']);
        //   echo $rvalue;
    
          //3写入价格表 
          if($value['current'] >0){ //有价格才写入
              
            $where['stock_code'] =      $real['stock_code'] = $value['companyId'];
            $real['stock_gid'] = "mys".$value['companyId'];
             $real['volume']  = $value['volume'];
             $real['add_time']= date("Y-m-d H:i:s");
              $real['open']=$value['open'];
              $real['high']=$value['high'];
              $real['low']=$value['low'];
              $real['close']=$value['current'];
              
            $res  =  pdo_update("real_time_data",$real,$where);
            if(!$res){
                $res  =  pdo_insert("real_time_data",$real);  
                 if(!$res){
                    echo " >> 写入价格失败 *** ".$value['seoName'];
                    pdo_debug();
                    die();
                     
                 }else{
                    echo " >> 写入价格成功 ".$value['seoName']." -- " .$value['companyId'];
                 }
            }else{
                echo " 更新价格成功 ".$value['seoName']." -- " .$value['companyId'];
            }
          }
          
        //   //删除多余的 > 给任务定时执行
        //   $id = pdo_insertid();
        //   pdo_fetch("delete from real_time_data where stock_code = '".$value['seoName']."' and id < ".$id);
       
    }
}