<?php
require '../vendor/autoload.php';
require '../framework/bootstrap.inc.php';

use Ratchet\Client\Connector;
use React\EventLoop\Factory;
use React\Socket\Connector as ReactConnector;
use React\Promise\Timer\TimeoutException;

$loop = Factory::create();



//  $data['redis']= $redis->set('mys'.$code, json_encode($redis_data));
//      $data['redis_str']= $redis->get('mys'.$code);


$connector = new Connector($loop, new ReactConnector($loop, [
    'tls' => [
        'verify_peer' => false, // 视情况而定，如果需要验证对等证书，请设置为 true
        'verify_peer_name' => false,
    ]
]));

$connector('wss://ws.chatra.live:2345')
    ->then(function(Ratchet\Client\WebSocket $conn) {
        echo "Connected to WebSocket server.\n";

        $conn->on('message', function($msg) use ($conn) {
            // echo "Received: {$msg}\n";
            //  {"State":1,"Msg":{"Market":"FC","varieties":"XAU","contract":"XAU","open":"2419.64000","high":"2450.04000","low":"2407.16000","price":"2419.16000","close":"2417.52000","volume":"342065","tick":"1716211509","NV":"1","amplitude":"0.017725","marketValue":"","position":"","TTM":"","YS":"","DTE":"","MT":"1","Message":"Cba","DLP":"5","PDV":"","NAV":"","CFO":"-0.48000","POAV":"","B1":"2419.16000","B1V":"","S1":"2419.50000","S1V":"","Range":"42.88000","DOWNUP":"175","INDEX":"","VWAP":"","ChangeA":"1.64000","MRTA":"","MYTJ":"","ticks":"1716211509433","dealTransaction":"1716211509,2419.24000,1,2,,1716211509325"},"Code":"XAU","Cmd":"rm"}
            $msg = json_decode($msg,true);
            $Code = $msg['Code'];
            $price =  $msg['Msg']['price'];
            
            //连接到 Redis 数据库
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(3);
// $user = json_decode($redis->get($token));
//  $redData = $redis->get("mysXAUUSD");
          echo $Code." > ".$price."\n";
//                 echo $redData;
            $redData = $redis->get('mys'.$Code."USD");
            if($redData){
                // echo $redData;
                // {"stock_code":"XAUUSD","chinese_stock_name":"XAUUSD","stock_name":"XAUUSD","last_done":2411.6001,"percent_change":2.564,"id":"179277","created_on":"2024-05-20 19:06:05","market":2.564,"low":2411.6001,"high":2411.6001,"sell_price":2411.6001,"buy_price":2411.6001,"lacp":2411.6001,"sell_volume":"XAUUSD","buy_volume":"XAUUSD","volume":"XAUUSD","change":2.564,"business_balance":2.564}
                $redData = json_decode($redData,true);
                $redData['last_done'] =$redData['lacp'] = $price;
                 $redis->set('mys'.$Code."USD",json_encode($redData));
                
            }
            
            
            // 发送消息到服务器
            // $conn->send('Hello from client!');
        });

        $conn->on('close', function($code = null, $reason = null) {
            echo "Connection closed ({$code} - {$reason})\n";
        });

        // 发送初始消息
        $conn->send('Hello WebSocket server!');
    }, function(Exception $e) use ($loop) {
        echo "Could not connect: {$e->getMessage()}\n";
        $loop->stop();
    });

$loop->run();