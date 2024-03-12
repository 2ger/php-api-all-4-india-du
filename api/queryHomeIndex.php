
<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers:*');
require '../framework/bootstrap.inc.php';



$list = pdo_fetchall("select s.*,r.open as price from stock2 s join real_time_data r on s.stock_gid = r.stock_gid group by  s.stock_gid order by r.open desc ");

$data['code'] = 0;
if($list) $data['code'] = 1;
$data['list'] = $list;
echo json_encode($data);
