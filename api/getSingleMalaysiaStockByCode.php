<?php
//采集单个马来股价
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';



//测试
 $url = "https://klse.i3investor.com/web/stock/overview/0200"; //返回空
//  $data = getWebsiteTitleAndContent($url);
//  $data = getStockData();

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
//   CURLOPT_POSTFIELDS =>$code,
  CURLOPT_HTTPHEADER => array(
    
    'Referer: https://klse.i3investor.com/',
  
  ),
));
$response = curl_exec($curl);
curl_close($curl);

 var_dump($response);


function getSingleStock($url) {
    $html = file_get_contents($url);
 var_dump($html);

    // 匹配网站标题
    preg_match("/<h2>(.*?)<\/h2>/i", $html, $matches);
    $title = isset($matches[1]) ? $matches[1] : '';

    // 匹配新闻内容
    preg_match_all('/<div class="content text-justify" style="font-size:16px;">(.*?)<div class="news-container-translated message-translated"><\/div>/is', $html, $matches);
    $content = isset($matches[1][0]) ? $matches[1][0] : '';

    return array('title' => $title, 'content' => $content);
}

function getStockData() {
  // 使用 CURL 获取页面 HTML 内容
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, "https://klse.i3investor.com/web/stock/overview/0200");
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $html = curl_exec($curl);
  curl_close($curl);

  
  // 使用正则表达式匹配 REVENUE GROUP BERHAD 的市值和价格数据
  preg_match('/<a href="\/web\/stock\/overview\/0200">REVENUE<\/a><br\/>GROUP BERHAD.*?
              <div class="col-xxl-3 col-md-6 col-sm-12 overview-main-col flex-grow border-bottom-light-1 pe-xxl-0">(.*?)<\/div>/s', 
             $html, $matches);
             
  // 如果没有找到匹配项，则返回空数组
  if (empty($matches)) {
    return array();
  }
  
  // 解析包含价格数据的 DIV 元素，并使用正则表达式提取价格字符串
  $price_div = $matches[1];
  preg_match('/<strong>([\d\.,]+)<\/strong>/', $price_div, $price_matches);
  $price = isset($price_matches[1]) ? floatval(str_replace(',', '', $price_matches[1])) : null;
  
  // 解析包含市值数据的 TD 元素，并使用正则表达式和 str_replace() 提取和格式化市值字符串
  $td = $matches[0];
  preg_match('/market_cap<\/i><\/div><div class="value" id="cluster_market_cap">([^<]*)/s', $td, $mc_matches);
  $market_cap = isset($mc_matches[1]) ? str_replace(array(',', ' '), '', $mc_matches[1]) : null;
  
  // 将价格数据和市值数据存储在关联数组中，并以公司名为键
  $stock_data = array(
    'REVENUE GROUP BERHAD' => array(
      'price' => $price,
      'market_cap' => $market_cap
    )
  );
  
  return $stock_data;
}