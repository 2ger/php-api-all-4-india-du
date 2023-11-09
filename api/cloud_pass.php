<?php
// 写入新股 https://tradingdiario.com/api/cloud_pass.php?code=5315&new_stock=1
//
// 更新价格 https://tradingdiario.com/api/cloud_pass.php?code=0200
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';
       //连接到 Redis 数据库
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    $redis->select(3);
    
    
    
//  设置klse cookies
// https://www.klsescreener.com/v2/stocks/view/5243
$cookie_value = "Q2FrZQ%3D%3D.DECxkqRjyMjZHGf3kBKJNz%2F3HgeHOBlXQRGy2WFbQSUygwITZ%2FwOmv6qY309QfmaxbhI0Jaz3PdgmiOXmx4jNHQ%3D";
setcookie("klsescreener[User]", "$cookie_value", time() + 3600*7, "/");
    
// var_dump($_GPC);
$code = $_GPC['code'];
$new_stock = $_GPC['new_stock'];
$title = $_GPC['title'];
if($title){
     
    //写入新股
         if($new_stock){
        $redis_data['chinese_stock_name']=    $redis_data['stock_name']=      $stock['stock_spell'] =  $stock['stock_name'] =  trim($title);
          $redis_data['stock_code']=       $stock['stock_code'] =  $code;
            $stock['stock_type'] =  "mys";
            $stock['stock_gid'] =  "mys".$code;
         $res =   pdo_insert("stock",$stock);
     $data['new_stock']=    $id = pdo_insertid();
         
   
    
   $redis_data['last_done']= 1;
   $redis_data['percent_change']=0.01;

      $redis_data['id']= $id;
      $redis_data['created_on']= date("Y-m-d H:i:s");
      $redis_data['market']=  "Main MARKET";
      $redis_data['buy_price']= 1;
      

      $redis_data['high']=1;
      $redis_data['low']=1;
      $redis_data['volume']=$redis_data['buy_volume']=$redis_data['sell_volume']= 100;
      $redis_data['change']= 0.01;
     $data['redis']= $redis->set('mys'.$code, json_encode($redis_data));
     $data['redis_str']= $redis->get('mys'.$code);
    
         if($res) echo "写入新股成功";
         die(json_encode($data));
         }
}

$content = $_GPC['content'];
if($content){
    // var_dump($content);
    $val = json_decode($content,true);
    if($val[4] >0){
        $where['stock_code']=       $data['stock_code']= $code;
         $data['stock_gid']= "mys".$code;
         $data['open']= $val[4];
         $data['close']= $val[4];
         $data['high']= $val[2];
         $data['low']= $val[3];
         $data['volume']='100';// $val[5]
         $data['timestamp']= date('Y-m-d H:i:s',time());
         $data['add_time']=  date('Y-m-d H:i:s',time());
         
      
           //先更新，没有则写入
    $update= $res =  pdo_update("real_time_data",$data,$where);
     if(!$res){
    $insert=     $res =  pdo_insert("real_time_data",$data);
     }
     //  $res =  pdo_insert("real_time_data",$data);
         $data['update'] = $update;
         $data['insert'] = $insert;
    
    // 写入redis
     $redis_str = $redis->get('mys'.$code);
     $redis_data = json_decode($redis_str,true);
     $redis_data['last_done']=$val[4];
     $data['redis']= $redis->set('mys'.$code, json_encode($redis_data));
     
       $id = pdo_insertid();

         
       //删除多余的
      pdo_fetch("delete from real_time_data where stock_code = '".$code."' and id < ".$id);
     if($res){
            die(json_encode($data));
        }
    }
}



$apiUrl = "https://api.cloudbypass.com/v2/stocks/chart/$code/embedded/1m";  

?>

<html>
    <script src="https://cdn.bootcdn.net/ajax/libs/axios/1.3.6/axios.js"></script>
    <script>
        var code = "<?=$code?>"
        var new_stock = "<?=$new_stock?>"
        
        const config = {
            url: "<?=$apiUrl?>",
            method: "GET",
            headers: {
                "x-cb-apikey": "9df5b13045654eb4b03c4d5a1bdf172e",
                "x-cb-host": "www.klsescreener.com",
                "x-cb-cookie": "klsescreener[User]=<?=$cookie_value?>",
            },
        }
        axios(config).then(res => {
            // console.log(res.data);
        
        var dataText = res.data
            const pattern = /\[\d{13},\d+\.\d+,\d+\.\d+,\d+\.\d+,\d+\.\d+,\d+\]/g; // 正则表达式模式
            const matches = dataText.match(pattern); // 匹配结果数组
            
            if(new_stock ==1){
                
            const pattern2 = /TA Chart for([^]*) - KLSE Screener/;
            const matches2 = dataText.match(pattern2); // 匹配标题
                console.log(matches2[matches2.length-1])
            var str = matches2[matches2.length-1];
            var title = str.replace(code, "");
                console.log(title)
            
                //发给后端更新
                axios.post('https://tradingvidya.com/api/cloud_pass.php', "code="+code+"&title="+title+"&new_stock="+new_stock)
                axios.post('https://tradingdiario.com/api/cloud_pass.php', "code="+code+"&title="+title+"&new_stock="+new_stock)
            
            }
                
            if (matches) {
                console.log(matches[matches.length-1])
                var last_price = matches[matches.length-1];
                
                //发给后端更新
                axios.post('https://tradingvidya.com/api/cloud_pass.php', "content="+last_price+"&code="+code+"&title="+title+"&new_stock="+new_stock)
                axios.post('https://tradingdiario.com/api/cloud_pass.php', "content="+last_price+"&code="+code+"&title="+title+"&new_stock="+new_stock)
            }else{
                console.log("未获得价格，请联系技术人员")
            }
        
        }).catch(err => {
            console.log(err);
        })

    </script>
</html>
