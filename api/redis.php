<?php

//连接到 Redis 数据库
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(3);
// $redis->set('key', 'value');

//从 Redis 中读取数据
$value = $redis->get('mys0105');

var_dump($value);
echo $value;