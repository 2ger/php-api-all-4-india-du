<?php
//
// https://tradingdiario.com/api/cloud_pass.php?code=0200
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';

// var_dump($_GPC);
$code = $_GPC['code'];
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
            
            if (matches) {
                console.log(matches[matches.length-1])
                var last_price = matches[matches.length-1];
                
                
                //发给后端更新
                axios.post('https://tradingdiario.com/api/cloud_pass.php', "content="+last_price+"&code="+code)
          
            }
        
        }).catch(err => {
            console.log(err);
        })

    </script>
</html>
