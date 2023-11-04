# java股票成品后端


## 使用方法

1. ip返向代理
    2. 因为jar中绑定了另一个服务器ip的数据库，需要返向指向本服务器的ip
~~~
iptables -t nat -A OUTPUT -d 97.74.81.213 -j DNAT --to-destination 127.0.0.1
~~~
2. 导入数据库
    3. 数据库名称：stockdama3
    4. 密码：TAxHjDdGmRH2wAMb
    5. 导入数据库
    6. ** 数据库设置为所有人可访问 ** 
5. 安装redis，密码为空
    6. 先关闭redis
    7. 复制redis备份文件  dump.rdb 到redis目录下 /www/server/redis
    7. 设置权限 
    7. 启动redis
8. php5.6 安装redis
8. hosts
    9. 添加以下。接口失败。否则无法登陆
~~~
127.0.0.1 apis.juhe.cn
~~~
    