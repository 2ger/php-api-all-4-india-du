

### api
php 补充api

1. nullPrice.php 检查持仓，无价格手动采集
    1. https://tradingdiario.com/api/nullPrice.php?op=list
2. 定时更新持仓中的价格
    1. https://tradingdiario.com/api/positonPriceUpdate.php
3. 删除多余价格
    1. 浏览器自动执行  https://tradingdiario.com/api/deleteRealData.php?op=auto
    2. 手动执行 https://tradingdiario.com/api/deleteRealData.php?op=list
    3. bt显示统计 https://tradingdiario.com/api/deleteRealData.php?op=list&from=bt
4. 新闻采集  https://tradingdiario.com/api/getNews.php
5. 新闻API  https://tradingdiario.com/api/getNewsList.php
5. 更新单个价格，进入详情时执行  https://tradingdiario.com/api/getMaStock.php
6. 搜索并更新价格  https://tradingdiario.com/api/searchMaStock.php
7. 首页推荐股票  https://tradingdiario.com/api/queryHomeIndex.php
8. 后台：挂单冥想
    1. 列表   https://tradingdiario.com/api/pendingOrder.php?op=list
    2. 通过   https://tradingdiario.com/api/pendingOrder.php?op=prove&positionId=xxx

### 支付api 
1. 666withdraw.php 提现
2. 666pay.php 支付api
3. 666notify.php 回调
