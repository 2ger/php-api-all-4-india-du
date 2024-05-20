<?php
//文章接口
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers:*');
require '../framework/bootstrap.inc.php';

$list = pdo_fetchall("select id,title,add_time from site_news  order by id desc limit 10");//where source_name= 'india'

$data['code'] = 0;
if($list) $data['code'] = 1;
$data['list'] = $list;
echo json_encode($data);