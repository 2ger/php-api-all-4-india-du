<?php
//采集文章
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';


$list = getArticleListUsingPregMatch();
// var_dump($list);

$site = 'https://www.klsescreener.com';
foreach($list as $val){
    $url = $site.$val['url'];
    $data = getWebsiteTitleAndContent($url);
    if($data){
        $data['source_name'] = "my";
      $res=  pdo_insert("site_news",$data);
      if($res) echo "<br>\n\n成功采集：". $data['title'];
    }
}

//测试
// $url = "https://www.klsescreener.com/v2/news/view/1140901/%E9%98%BF%E5%85%B9%E6%9B%BC-%E9%A2%81900%E4%B8%87%E9%A3%9F%E6%B2%B9%E8%A1%A5%E8%B4%B4%E6%B2%A1%E8%B6%85%E9%A2%9D";
// $data = getWebsiteTitleAndContent($url);
// var_dump($data);


function getWebsiteTitleAndContent($url) {
    $html = file_get_contents($url);

    // 匹配网站标题
    preg_match("/<h2>(.*?)<\/h2>/i", $html, $matches);
    $title = isset($matches[1]) ? $matches[1] : '';

    // 匹配新闻内容
    preg_match_all('/<div class="content text-justify" style="font-size:16px;">(.*?)<div class="news-container-translated message-translated"><\/div>/is', $html, $matches);
    $content = isset($matches[1][0]) ? $matches[1][0] : '';

    return array('title' => $title, 'content' => $content);
}

function getArticleListUsingPregMatch() {
    // 设置目标网站的 URL
    $url = 'https://www.klsescreener.com/v2/news';

    // 使用 cURL 初始化 HTTP 请求
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    // 获取目标网站的 HTML 内容
    $html = curl_exec($ch);

    // 关闭 cURL 请求
    curl_close($ch);

    // 使用 preg_match 函数从 HTML 中提取文章列表
    $pattern = '/<ul class="channel_list_show">(.*?)<\/ul>/s';
    preg_match($pattern, $html, $matches);
    $content = $matches[1];

    $pattern = '/<a href="(.*?)" target="_blank">(.*?)<\/a>/s';
    preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
    
    // 遍历匹配结果，提取文章的标题和 URL
    $result = array();
    foreach ($matches as $match) {
        $title = trim($match[2]);
        $url = trim($match[1]);
        if (!empty($title) && !empty($url)) {
            $result[] = array('title' => $title, 'url' => $url);
        }
    }

    // 返回文章列表数组
    return $result;
}