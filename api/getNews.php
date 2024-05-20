<?php
//采集文章 > 印度
// https://etorhome.com//getNews.php
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';


$list = getArticleListUsingPregMatch();
var_dump($list);
die();


function getArticleListUsingPregMatch() {
    // 设置目标网站的 URL
    $url = 'https://www.nirmalbang.com/ajaxpages/AjaxNewsUpdates.aspx?SecID=4&SubSecID=40&pageNo=1&PageSize=10';
 $html = file_get_contents($url);

    // 匹配网站标题
    preg_match_all("/<div class='GrNewsHead'>(.*?)<\/div>/i", $html, $matches);
 
    $title = isset($matches[1]) ? $matches[1] : '';

    // 匹配新闻内容
    preg_match_all("/<div class='GrNewsDetCont close1'>(.*?)<\/div>/i", $html, $matches);
 
    $content = isset($matches[1]) ? $matches[1] : '';
    $count = count($title);
    for($i=0;$i<$count;$i++){
     
     $data = array('title' => $title[$i], 'content' => $content[$i]);
            $data['source_name'] = "india";
          $res=  pdo_insert("site_news",$data);
          if($res) echo "<br>\n\n成功采集：". $data['title'];
        }
}