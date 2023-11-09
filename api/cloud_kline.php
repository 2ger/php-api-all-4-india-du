<?php
// 写入新股 https://tradingdiario.com/api/cloud_pass.php?code=5315&new_stock=1
//
// 更新价格 https://tradingdiario.com/api/cloud_pass.php?code=0200
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';
       //连接到 Redis 数据库
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    $redis->select(3);
    
    
    
//  设置klse cookies
// https://www.klsescreener.com/v2/stocks/view/5243
$cookie_value = "Q2FrZQ%3D%3D.DECxkqRjyMjZHGf3kBKJNz%2F3HgeHOBlXQRGy2WFbQSUygwITZ%2FwOmv6qY309QfmaxbhI0Jaz3PdgmiOXmx4jNHQ%3D";
setcookie("klsescreener[User]", "$cookie_value", time() + 3600*7, "/");
    
// var_dump($_GPC);
$code = $_GPC['code'];
$new_stock = $_GPC['new_stock'];
$title = $_GPC['title'];
if($title){
     
    //写入新股
         if($new_stock){
        $redis_data['chinese_stock_name']=    $redis_data['stock_name']=      $stock['stock_spell'] =  $stock['stock_name'] =  trim($title);
          $redis_data['stock_code']=       $stock['stock_code'] =  $code;
            $stock['stock_type'] =  "mys";
            $stock['stock_gid'] =  "mys".$code;
         $res =   pdo_insert("stock",$stock);
     $data['new_stock']=    $id = pdo_insertid();
         
   
    
   $redis_data['last_done']= 1;
   $redis_data['percent_change']=0.01;

      $redis_data['id']= $id;
      $redis_data['created_on']= date("Y-m-d H:i:s");
      $redis_data['market']=  "Main MARKET";
      $redis_data['buy_price']= 1;
      

      $redis_data['high']=1;
      $redis_data['low']=1;
      $redis_data['volume']=$redis_data['buy_volume']=$redis_data['sell_volume']= 100;
      $redis_data['change']= 0.01;
     $data['redis']= $redis->set('mys'.$code, json_encode($redis_data));
     $data['redis_str']= $redis->get('mys'.$code);
    
         if($res) echo "写入新股成功";
         die(json_encode($data));
         }
}

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
    $update= $res =  pdo_update("real_time_data",$data,$where);
     if(!$res){
    $insert=     $res =  pdo_insert("real_time_data",$data);
     }
     //  $res =  pdo_insert("real_time_data",$data);
         $data['update'] = $update;
         $data['insert'] = $insert;
    
    // 写入redis
     $redis_str = $redis->get('mys'.$code);
     $redis_data = json_decode($redis_str,true);
     $redis_data['last_done']=$val[4];
     $data['redis']= $redis->set('mys'.$code, json_encode($redis_data));
     
       $id = pdo_insertid();

         
       //删除多余的
      pdo_fetch("delete from real_time_data where stock_code = '".$code."' and id < ".$id);
     if($res){
            die(json_encode($data));
        }
    }
}



$apiUrl = "https://api.cloudbypass.com/v2/stocks/chart/$code/embedded/1y";  

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0061)https://www.klsescreener.com/v2/stocks/chart/7113/embedded/1y -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>
        TA Chart for TOPGLOV 7113 - KLSE Screener
	</title>
	
		<script src="../klse_kline_files/jquery.min.js"></script>
		<script src="../klse_kline_files/jquery-ui.min.js"></script>		
		
		<link rel="stylesheet" href="../klse_kline_files/jquery-ui.css" type="text/css" media="all">
		
	<style>:is([id*='google_ads_iframe'],[id*='taboola-'],.taboolaHeight,.taboola-placeholder,#credential_picker_container,#credentials-picker-container,#credential_picker_iframe,[id*='google-one-tap-iframe'],#google-one-tap-popup-container,.google-one-tap-modal-div,#amp_floatingAdDiv,#ez-content-blocker-container,#notify-adblock) {display:none!important;min-height:0!important;height:0!important;}</style></head>
<body class="embedded" style="">

<script src="../klse_kline_files/highstock.js"></script>
<script src="../klse_kline_files/exporting.js"></script>
<script src="https://cdn.bootcdn.net/ajax/libs/axios/1.3.6/axios.js"></script>

<script type="text/javascript">
    var ohlc = [],volume = [], chart;
    $(function() {
        Highcharts.theme = {
            colors: ["#7cb5ec", "#f7a35c", "#90ee7e", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee",
                "#55BF3B", "#DF5353", "#7798BF", "#aaeeee"
            ],
            chart: {
                backgroundColor: '#fff',
                style: {
                    fontFamily: "Dosis, sans-serif"
                }
            },
            title: {
                style: {
                    fontSize: '16px',
                    fontWeight: 'bold',
                    textTransform: 'uppercase'
                }
            },
            tooltip: {
                borderWidth: 0,
                //backgroundColor: 'rgba(219,219,216,0.8)',
                //shadow: false
            },
            legend: {
                itemStyle: {
                    fontWeight: 'bold',
                    fontSize: '13px'
                }
            },
            xAxis: {
                gridLineWidth: 1,
                labels: {
                    style: {
                        fontSize: '12px'
                    }
                },
                gridLineColor: '#ccc'
            },
            yAxis: {
                //minorTickInterval: 'auto',
                title: {
                    style: {
                        //textTransform: 'uppercase'
                    }
                },
                labels: {
                    style: {
                        //fontSize: '12px'
                    }
                },
                gridLineColor: '#ccc'
            },

            
            // General
            background2: '#F0F0EA'

        };
   
            var data = []
        var code = "<?=$code?>"
        var new_stock = "<?=$new_stock?>"
        
        const config = {
            url: "<?=$apiUrl?>",
            method: "GET",
            headers: {
                "x-cb-apikey": "9df5b13045654eb4b03c4d5a1bdf172e",
                "x-cb-host": "www.klsescreener.com",
                "x-cb-cookie": "klsescreener[User]=<?=$cookie_value?>",
            },
        }
        axios(config).then(res => {
            // console.log(res.data);
        
        var dataText = res.data
        

            
            var regex = /\[\d{13},\d+\.\d+,\d+\.\d+,\d+\.\d+,\d+\.\d+,\d+\]/g;
            var matches1 = dataText.match(regex);
             data = matches1.map(function(string) {
              return JSON.parse(string);
            });
            console.log('matches1',data);

 var dataLength = data.length;

        for (i = 0; i < dataLength; i++) {
            ohlc.push([
                data[i][0], // the date
                data[i][1], // open
                data[i][2], // high
                data[i][3], // low
                data[i][4] // close
            ]);

            volume.push([
                data[i][0], // the date
                data[i][5] // the volume
            ])
        }

        // set the allowed units for data grouping
        var groupingUnits = [
            [
                'day', // unit name
                [1] // allowed multiples
            ],
            [
                'month',
                [1, 2, 3, 4, 6]
            ]
        ];

        // create the chart
        //var chart = $('#chart_div').highcharts('StockChart', { 
        chart = Highcharts.stockChart('chart_div', { // 2023-10-06

            rangeSelector: {
                selected: 4,
                allButtonsEnabled: true,
            },
            chart: {
                type: 'candlestick',
                styledMode: true,
            },
            title: {
                //text: 'TOPGLOV Historical',
                floating: true,
            },

            navigator: {
                //enabled:false,
                height: 20,
            },

            exporting: {
                enabled: false,
            },

            yAxis: [{
                title: {
                    text: 'Price'
                },
                height: '75%',
                lineWidth: 1
            }, {
                title: {
                    text: 'Volume'
                },
                top: '77%',
                height: '23%',
                offset: 0,
                lineWidth: 1
            }],

            series: [{
                type: 'candlestick',
                name: 'TOPGLOV',
                data: ohlc,
                dataGrouping: {
                    units: groupingUnits
                }
            }, {
                type: 'column',
                name: 'Volume',
                data: volume,
                yAxis: 1,
                dataGrouping: {
                    units: groupingUnits
                }
            }],

        });
        
 
            //更新后台
            const pattern = /\[\d{13},\d+\.\d+,\d+\.\d+,\d+\.\d+,\d+\.\d+,\d+\]/g; // 正则表达式模式.最后一个
            const matches = dataText.match(pattern); // 匹配结果数组
            
            if(new_stock ==1){
                
            const pattern2 = /TA Chart for([^]*) - KLSE Screener/;
            const matches2 = dataText.match(pattern2); // 匹配标题
                console.log(matches2[matches2.length-1])
            var str = matches2[matches2.length-1];
            var title = str.replace(code, "");
                console.log(title)
            
                //发给后端更新
                axios.post('https://tradingvidya.com/api/cloud_pass.php', "code="+code+"&title="+title+"&new_stock="+new_stock)
                axios.post('https://tradingdiario.com/api/cloud_pass.php', "code="+code+"&title="+title+"&new_stock="+new_stock)
            
            }
                
            if (matches) {
                console.log(matches[matches.length-1])
                var last_price = matches[matches.length-1];
                
                //发给后端更新
                axios.post('https://tradingvidya.com/api/cloud_pass.php', "content="+last_price+"&code="+code+"&title="+title+"&new_stock="+new_stock)
                axios.post('https://tradingdiario.com/api/cloud_pass.php', "content="+last_price+"&code="+code+"&title="+title+"&new_stock="+new_stock)
            }else{
                console.log("未获得价格，请联系技术人员")
            }
        
        }).catch(err => {
            console.log(err);
        })
        
        
            
        
       

    });

    </script>

<style>
    @import 'https://code.highcharts.com/css/highcharts.css';

    body {
        margin: 0;
        padding: 0;
    }

    #chart_div {
        height: 99%;
        width: 100%;
        position: absolute;
    }

    .highcharts-candlestick-series .highcharts-point-up {
        fill: green;
    }

    .highcharts-candlestick-series .highcharts-point-down {
        fill: red;
    }

    .highcharts-background {
        fill: #fff;
    }

    .highcharts-xaxis-grid .highcharts-grid-line {
        stroke: #ccc;
    }

    .highcharts-yaxis-grid .highcharts-grid-line {
        stroke: #ccc;
    }

        .highcharts-label text, .highcharts-axis-labels text {
        fill: black;
    }

    .highcharts-candlestick-series .highcharts-point {
        stroke: #999999;
    }

    .highcharts-tooltip-box text {
        fill: white;
    }
    .highcharts-tooltip-box .highcharts-label-box {
        fill: #303030; /* needed for someppl.. */
    }

    </style>
    
    
<div id="chart_div"></div>


			


</body></html>
