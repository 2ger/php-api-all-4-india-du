<?php
require '../vendor/autoload.php';
require '../framework/bootstrap.inc.php';

use Ratchet\Client\Connector;
use React\EventLoop\Factory;
use React\Socket\Connector as ReactConnector;
use React\Promise\Timer\TimeoutException;

$loop = Factory::create();


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
        
            $msg = json_decode($msg,true);
            $Code = $msg['Code'];
            $price =  $msg['Msg']['price'];
            
            //连接到 Redis 数据库
            $redis = new Redis();
            $redis->connect('127.0.0.1', 6379);
            $redis->select(3);
            // $user = json_decode($redis->get($token));
            //  $redData = $redis->get("mysXAUUSD");
            
            if (strpos($Code, 'USD') === false) {
                // 如果不包含 'USD'，则将 ' USD' 追加到 $string1
                $Code .= 'USD';
            }

          echo $Code." > ".$price."\n";
//                 echo $redData;

            $redData = $redis->get('mys'.$Code);
            if($redData){
                $redData = json_decode($redData,true);
                $redData['last_done'] =$redData['lacp'] = $price;
                 $redis->set('mys'.$Code,json_encode($redData));
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



if ($argc < 2) {
    echo "Usage: php xxx.php [start|stop]\n";
    exit(1);
}
$command = $argv[1];
switch ($command) {
    case 'start':
        // 处理启动操作的代码
        echo "Starting the process...\n";
        // 这里添加启动操作的代码
        $loop->run();
        break;

    case 'stop':
        // 处理停止操作的代码
        echo "Stopping the process...\n";
        // 这里添加停止操作的代码
          $loop->stop();
        break;
    default:
       
        exit(1);
}
