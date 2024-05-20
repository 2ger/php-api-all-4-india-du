<?php
//采集文章 > 关键词
// https://etorhome.com/api/newsapi.php
header('Access-Control-Allow-Origin:*');
require '../framework/bootstrap.inc.php';




function fetchGoldNews() {
$keyWord = "gold";
$apiKey = "9428881e0b944fe7bd40277167067002";
    $url = "https://newsapi.org/v2/top-headlines?q=$keyWord&apiKey=$apiKey";
    // echo $url;
    $response = file_get_contents($url);

    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, $url);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // $response = curl_exec($ch);
    // curl_close($ch);

    $data = json_decode($response, true);
    // var_dump($data);
    return $data['articles'];
}

$goldNews = fetchGoldNews();

echo "Current Gold Price: $goldPrice USD\n";
echo "Latest Gold News:\n";
foreach ($goldNews as $article) {
     
     $data = array('title' => $article['title'], 'content' => $article['content']);
            $data['source_name'] = $article['author'];
          $res=  pdo_insert("site_news",$data);
          if($res) echo "<br>\n\n成功采集：". $data['title'];
        }