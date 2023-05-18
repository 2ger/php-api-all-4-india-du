

### api
php 补充api

1. nullPrice.php 检查持仓，无价格手动采集
>  https://tradingdiario.com/api/nullPrice.php?op=list
2. 定时更新持仓中的价格
>  https://tradingdiario.com/api/positonPriceUpdate.php
3. 删除多余价格
    1. 浏览器自动执行  https://tradingdiario.com/api/deleteRealData.php?op=auto
    2. 手动执行 https://tradingdiario.com/api/deleteRealData.php?op=list
    3. bt显示统计 https://tradingdiario.com/api/deleteRealData.php?op=list&from=bt