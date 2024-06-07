<?php
// 获得k线历史数据

//大宗  https://economictimes.indiatimes.com/marketstats/pageno-1,pid-102,sortby-volume,sortorder-desc.cms

require '../framework/bootstrap.inc.php';
header('Access-Control-Allow-Origin: *');

$period = $_GET['period'];
$symbol = $_GET['symbol'];
$systexId = $_GET['systexId'];
$c_id = $_GET['c_id'];
$name = $_GET['name'];
$symbol = str_replace('usdt','',$symbol);
// $symbol = str_replace(' ','%20',$symbol);
$name = urlencode($name);
// $symbol = strtolower($symbol);
// echo $symbol;
// echo $period;
    $today = date("Y-m-d");

            //连接本地的 Redis 服务
//   $redis = new Redis();
//   $redis->connect('127.0.0.1', 6379);
         //查看服务是否运行
//   echo "Server is running: " . $redis->ping();

    $data['time_set'] = date("Y-m-d");
    $data['from'] = "web";
    // $data_str = $redis->get($name.$period);
    // if($data_str){
    //     $redis_data = json_decode($data_str,true);
    //     if(time() - $redis_data['time_sec']  < 60 ){//60秒更新
    //     // if($today = $redis_data['time_set']){ //当天不更新
    //         $redis_data['from'] = ' redis';
    //         $res =json_encode($redis_data);
    //         // die($res);//测试就从关闭，不从redis取
    //     }
    // }

        $time   =   time();
   
    //指数
        if($c_id==5){
        // echo $period;
            $exchangeid=50;
            $bse = array('BANKEX','SENSEX','SENSEX50','SNSX50');
            if(in_array($name,$bse)){
                $exchangeid=47;
            }
            if($period == "1D"){
                //天
                $url = "https://etelection.indiatimes.com/ET_Charts/GetCompanyPriceInformation?scripcode=$name&exchangeid=$exchangeid&datatype=eod&filtertype=eod&tagId=&firstreceivedataid=$today&lastreceivedataid=&directions=back&callback=serviceHit.chartResultCallback&scripcodetype=index&uptodataid=2022-10-28";
            }else{
                $period2 = $period.'MIN';
                $url = "https://ettechcharts.indiatimes.com/ETLiveFeedChartRead/livefeeddata?scripcode=$name&exchangeid=$exchangeid&datatype=intraday&filtertype=$period2&tagId=&firstreceivedataid=&lastreceivedataid=&directions=all&callback=serviceHit.chartResultCallback&scripcodetype=index";
            
            }
            
         
                $res = file_get_contents($url); 
                $res = str_replace("serviceHit.chartResultCallback(",'',$res);
                $res = str_replace(")",'',$res);
                $res = json_decode($res,true);
                   $list  =$res['query']['results']['quote'];
                foreach ($list as &$item){
                    $item['timestamp'] =  strtotime($item['Date']);
                }
            $data['data'] = $list;

            // }
        
        // echo $url;
   }
        if($c_id==2){//股票
            //股票
  
            //klin3 2023-11-14
            $url = "https://etelection.indiatimes.com/ET_Charts/india-market/stock/history?symbol=$name&resolution=$period&from=1697276888&to=$time&countback=330";
            // $url = "https://etelection.indiatimes.com/ET_Charts/india-market/stock/history?symbol=$name&resolution=5&from=1699575537&to=$time&countback=301";
            // echo $url;
            $res = file_get_contents($url); 
            $res = json_decode($res,true);
            $count  =count($res['c']);
            for($i=$count-1;$i>0;$i--){
                // $date = date('H:i', $res['t'][$i]); // 将时间戳转换为小时和分钟
                // if($date == '00:00') break;
                $new['timestamp'] = $res['t'][$i];
                $new['Open'] = $res['o'][$i];
                $new['High'] = $res['h'][$i];
                $new['Low'] = $res['l'][$i];
                $new['Close'] = $res['c'][$i];
                $new['Volume'] = $res['v'][$i];
                $new['Date'] =  date("Y-m-d",$new['timestamp']);
                
                $data['data'][]=$new;
            }
     }
        if($c_id==4){//大宗商品
      //印度，非实时，一天一更新
             if($period <15) $period =15;
             $time = time();
             $url = "https://etelection.indiatimes.com/ET_Charts/india-market/mcx-commodity/history?symbol=$name&resolution=$period&from=1673654400&to=$time&countback=330&expirydate=$expirydate&servicetype=fno";
            $res = file_get_contents($url); 
            $res = json_decode($res,true);
            $count  =count($res['c']);
            for($i=$count-1;$i>0;$i--){
                // $date = date('H:i', $res['t'][$i]); // 将时间戳转换为小时和分钟
                // if($date == '00:00') break;
                $new['timestamp'] = $res['t'][$i];
                $new['Open'] = $res['o'][$i];
                $new['High'] = $res['h'][$i];
                $new['Low'] = $res['l'][$i];
                $new['Close'] = $res['c'][$i];
                $new['Volume'] = $res['v'][$i];
                $new['Date'] =  date("Y-m-d",$new['timestamp']);
                
                $data['data'][]=$new;
            }
        
        }
         if($c_id==6){//外汇
        if($name=="XAG" || $name=="XAU" ){
            
        //  汇通
          if($period == "1D")      $period = "D";
        //     if($period >0) $period = $period."M";
            $url = "https://gold.fx678.com/ajax/history.html?symbol=$name&limit=100&resolution=$period&codeType=5c00&st=0.3888213642888383";
              
            $res = file_get_contents($url); 
            $res = json_decode($res,true);
            $count  =count($res['c']);
             for($i=0;$i<$count-1;$i++){
                // $date = date('H:i', $res['t'][$i]); // 将时间戳转换为小时和分钟
                // if($date == '00:00') break;
                $new['timestamp'] = $res['t'][$i];
                $new['Open'] = $res['o'][$i];
                $new['High'] = $res['h'][$i];
                $new['Low'] = $res['l'][$i];
                $new['Close'] = $res['c'][$i];
                $new['Volume'] = $res['v'][$i];
                $new['Date'] =  date("Y-m-d",$new['timestamp']);
                
                $data['data'][]=$new;
            }
         
        }else{
            
      //阿里云
            if($period == "1D")      $period = "D";
            if($period >0) $period = $period."M";
              $host = "http://alirmcom2.market.alicloudapi.com";
            //或 http://alirm-com.konpn.com
            $path = "/query/comkm";
            $method = "GET";
            $appcode = "96889731946c48f3af78b44494bf2bdd";
            $headers = array();
            array_push($headers, "Authorization:APPCODE " . $appcode);
            $querys = "period=$period&pidx=1&psize=300&symbol=$name&withlast=1";
            $bodys = "";
            $url = $host . $path . "?" . $querys;
        
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_FAILONERROR, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            if (1 == strpos("$".$host, "https://"))
            {
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            }
            // var_dump(curl_exec($curl));
             $res = curl_exec($curl);
            $res = json_decode($res,true);
            $count  =count($res['Obj']);
            foreach ($res['Obj'] as $val){
                // $date = date('H:i', $res['t'][$i]); // 将时间戳转换为小时和分钟
                // if($date == '00:00') break;
                $new['timestamp'] = $val['Tick'];
                $new['Open'] = $val['O'];
                $new['High'] = $val['H'];
                $new['Low'] = $val['L'];
                $new['Close'] = $val['C'];
                $new['Volume'] = $val['V'];
                $new['Date'] = $val['D'];
                
                $data['data'][]=$new;
            }
        }
        }

//设置 redis 字符串数据
$data['time_set'] = date("Y-m-d");
$data['time_sec'] = time();
$data['from'] = "web";
// $redis->set($name.$period, json_encode($data));

$res =json_encode($data);
die($res);
?>