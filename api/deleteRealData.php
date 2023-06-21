// <?php
// //删除昨天的数据
// // 不能直接删，无采集的股票会没有价格，导致持仓出错
// header('Access-Control-Allow-Origin:*');
// require '../framework/bootstrap.inc.php';


// // 方式一：删除前天的时间  > 不能直接删，无采集的股票会没有价格，导致持仓出错
// // $beforeYesterdayTime = date('Y-m-d H:i:s', strtotime('-1 day'));
// // $where['timestamp <'] =$beforeYesterdayTime;
// // // echo $beforeYesterdayTime;
// // $res = pdo_fetchcolumn("select count(*) as count from real_time_data where add_time < '".$beforeYesterdayTime."'");

// // echo $beforeYesterdayTime." 天以前的数据（条）:".$res;

// // $res = pdo_fetch("delete from real_time_data where add_time < '".$beforeYesterdayTime."'");

// //  方式二： 每个股票保留一条 》 执行超时

// // pdo_fetch("DELETE t1 FROM real_time_data t1, real_time_data t2 WHERE t1.id < t2.id AND t1.stock_gid = t2.stock_gid");
// // pdo_debug();

// //方式三 ，手动删除
// $op = $_GPC['op'];
// if($op == "list"){
//     $list = pdo_fetchall("select id,stock_gid, count(*) as total from real_time_data where 1 GROUP by stock_gid order by total desc, id desc");
//     $i = 1;
//     foreach ($list as $val){
//         if($val["total"] >10){
//             echo $i;
//             $i++;
//             if($_GPC['from']){
//                 echo " - ".$val["total"]."条 - ".$val["stock_gid"]."\n\r";
//             }else{
//                  echo " <a target=_blank href='https://tradingdiario.com/api/deleteRealData.php?op=del&stock_gid=".$val["stock_gid"]."'>".$val["total"]."条 - ".$val["stock_gid"]."</a><br>"; 
//             }
//         }
//     }
//     if($i == 1)  echo " 无任务 \n\r";
// }
// if($op == "del"){
//     $stock_gid = $_GPC['stock_gid'];
//     $id = pdo_fetchcolumn("select id from real_time_data where stock_gid = '".$stock_gid."' order by  id desc");
//     $delsql = "delete from real_time_data where stock_gid ='".$stock_gid."' and id < ".$id;
//     echo $delsql;
//     $wdel['stock_gid'] =$stock_gid;
//     $wdel['id <'] =$id;
//     $res = pdo_delete("real_time_data",$wdel);
//     // $res = pdo_fetch($delsql);

//     if($res) {
//         echo $stock_gid." - id:".$id;
//         echo " 删除成功";
//     }
// }
// //方法三，浏览器自动跳转删除
// if($op == "auto"){
//     $time = time();
//     echo date("H:i:s")." 开始\n";
//     $list = pdo_fetchall("select id,stock_gid, count(*) as total from real_time_data where 1 GROUP by stock_gid order by total desc, id desc limit 50");
//     $i = 1;
//     foreach ($list as $val){
//         if($val["total"] >10){
//             echo $i." - ".$val["stock_gid"]." - ".$val["total"]."条 - ";
//             $i++;
//             $stock_gid = $val['stock_gid'];
//             $id = pdo_fetchcolumn("select id from real_time_data where stock_gid = '".$stock_gid."' order by  id desc");
     
//             $wdel['stock_gid'] =$stock_gid;
//             $wdel['id <'] =$id;
//             $res = pdo_delete("real_time_data",$wdel);
//             // $res = pdo_fetch($delsql);
//             if($res) {
//                 // echo $stock_gid." - id:".$id;
//                 echo " 删除成功\n\r";
//                 // sleep(5);
//                 $url = "https://tradingdiario.com/api/deleteRealData.php?op=auto";
//                 header("location:$url");
//             }
//         }
//     }
//     if($i == 1)  echo " 无任务 \n\r";
//     echo date("H:i:s")." 结束\n";
//     $time = time() - $time;
//     echo "用时: ".$time."s \n";
// }