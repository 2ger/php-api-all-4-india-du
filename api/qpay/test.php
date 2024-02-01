
<?php
require '../../framework/bootstrap.inc.php';

 
$notify = $_GPC['type'];
// var_dump($_GPC);
$ip_address = $_SERVER['REMOTE_ADDR'];
file_put_contents('../logs/pay_notify_post.log', date("Y-m-d H:i:s")."--$notify test--\r\n".$ip_address."\n".json_encode($_GPC)."\r\n\r",FILE_APPEND);

$post = $_GPC['__input'];
// var_dump($_GPC['__input']);
die("SUCCESS");