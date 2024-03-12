<?php
$str1="23930.950";
$str2="1.0";
var_dump(floatval($str2));
var_dump(round("0.22999999999993",2));


require '../framework/bootstrap.inc.php';

// 输出所有配置
print_r($_W);

// 汇率
echo $_W['config']['usd']['inr'];
?>