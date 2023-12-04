<?php

header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';
//连接到 Redis 数据库
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(3);

$code = $_GPC['keyWords'];
$pageSize = $_GPC['pageSize'];
$pageNum = $_GPC['pageNum'];

$offset = ($pageNum-1)*$pageSize;


// if(strlen($code) >=4){
// $data['status'] = 0;
// $data['msg'] = strlen($code);
// // $data['data'] =[];
// $data = json_encode($data);
// echo $data;
// exit();
// } 


// stock_name
// stock_code
// stock_spell
// stock_type
// stock_gid
// stock_plate
    //新股上架，搜索不到，手动从详情写入数据库，再从数据库查出来
    $list = pdo_fetchall("SELECT r.*,s.*  FROM stock s left join `real_time_data` r on r.stock_code = s.stock_code WHERE (s.stock_code like '%".$code."%' or s.stock_spell like '%".$code."%' or s.stock_name like '%".$code."%') and s.stock_type like '%india%'  group by s.stock_code limit $pageSize ");//OFFSET $offset
// if($list){
//     foreach ($list as $item){
        
//      $val['name'] = $item['stock_name'];
//      $val['code'] = $item['stock_code'];
//      $val['nowPrice'] = $item['close'];
//      $val['hcrate'] = number_format(($item['high']-$item['low'])/$item['close']*100,2);
     
//     if(strlen($code) >=4){
//         //实时查
//         $url = "https://www.shareinvestor.com/prices/searchbox_prices_f.html?counter=$code.MY";  
//         $response = file_get_contents($url);
//       $pattern = '/\<td rowspan\=\"2\" class=\"sic_lastdone\"><strong>(.*?)\<\/strong\>/s';
//         preg_match($pattern, $response, $matches);
//         $content = $matches[1];
//         $content = str_replace(',','',$content);
//      $val['nowPrice']=   $content;
//       $data['open']=  $data['close']= $content;
    
//   $pattern = '/\<td\>Price Range\: \<strong\>(.*?) - /s';
//     preg_match($pattern, $response, $matches);
//     $content = $matches[1];
//     $content = str_replace(',','',$content);
//     $data['low']= $content;
//   $pattern = '/ - (.*?)\<\/strong\>/s';
//     preg_match($pattern, $response, $matches);
//     $content = $matches[1];
//     $content = str_replace(',','',$content);
//     $data['high']= $content;
    
//   $where['stock_code']=   $data['stock_code']= $code;
//     $data['stock_gid']= "mys".$code;
    
//      $data['volume']='100';// $val[5]
//      $data['timestamp']= date('Y-m-d H:i:s',time());
//      $data['add_time']=  date('Y-m-d H:i:s',time());
     
//      //先更新，没有则写入
//      $res =  pdo_update("real_time_data",$data,$where);
//      if(!$res){
//          $res =  pdo_insert("real_time_data",$data);
//      }
     
//   $val['insert']=  $data['insert']  = $res;
//     //   $id = pdo_insertid();
//     //删除多余的
//     //   pdo_fetch("delete from real_time_data where stock_code = '".$code."' and id < ".$id);
//     }
        
//     $list2[] =$val;
//     }
// }

$data['status'] = 0;
$data['data'] = $list;

// pdo_debug();

$data = json_encode($data);
echo $data;


