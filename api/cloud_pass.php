<?php
// 写入新股 https://tradingdiario.com/api/cloud_pass.php?code=0200&new_stock=1
//
// 更新价格 https://tradingdiario.com/api/cloud_pass.php?code=0200
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';

// var_dump($_GPC);
$code = $_GPC['code'];
$new_stock = $_GPC['new_stock'];
$title = $_GPC['title'];
if($title){
     
    //写入新股
         if($new_stock){
            $stock['stock_spell'] =  $stock['stock_name'] =  $title;
            $stock['stock_code'] =  $code;
            $stock['stock_type'] =  "mys";
            $stock['stock_gid'] =  "mys".$code;
         $res =   pdo_insert("stock",$stock);
         if($res) echo "写入新股成功";
         die();
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
     $res =  pdo_update("real_time_data",$data,$where);
     if(!$res){
         $res =  pdo_insert("real_time_data",$data);
     }
     //  $res =  pdo_insert("real_time_data",$data);
         $data['status'] = $res;
    
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
                axios.post('https://tradingdiario.com/api/cloud_pass.php', "code="+code+"&title="+title+"&new_stock="+new_stock)
            
            }
                
            if (matches) {
                console.log(matches[matches.length-1])
                var last_price = matches[matches.length-1];
                
                //发给后端更新
                axios.post('https://tradingdiario.com/api/cloud_pass.php', "content="+last_price+"&code="+code+"&title="+title+"&new_stock="+new_stock)
            }else{
                console.log("未获得价格，请联系技术人员")
            }
        
        }).catch(err => {
            console.log(err);
        })

    </script>
</html>
