## api访问记录
- 存储记录的表是内存表，重启会导致数据丢失
- 单条记录最大长度为235字节，内存表最大限制一般为1024M(宝塔面板的mysql只用15M，可以到配置中的[mysqlid]下添加max_heap_table_size = 1024m)，那么大概能存500万条数据，如果你的并发很大请关闭这个功能
- 使用`show variables like '%heap%';`命令可以查看你当前的内存表大小限制
或者更换mongodb这样的数据库
### 图表数据
近七天访问量分布图
周一到周末访问分布图
24小时访问分布图 - 当数据量上百万后，该查询较慢
api访问量倒序表
系统分布图
ua分布图

## 自动部署脚本
自动切换环境变量文件

## 微信
### 微信公众号
1. 校验服务器接口:`http://yourhost/wechat/offical/server-validate`,校验token在`wechat`配置文件中,微信需要通过该接口验证服务器
2. 请求授权接口:`http://yourhost/wechat/offical/auth`,通过将浏览器跳转到该接口,获取用户的授权信息
3. 回调接口:`http://yourhost/wechat/offical/callback`,授权成功后,浏览器会跳转到该接口。

## 文件上传云存储
执行`php think autocouldstore`命令即可,这是一个上传脚本

## 待完成
1. 引入Swagger,参考文档：https://learnku.com/laravel/t/7430/how-to-write-api-documents-based-on-swagger-php
2. api说明清单
4. 执行file->read方法时检测当前文件是存储在本地的,要能够支持跨服务器访问


验证码凭证-》验证要保存的的数据-》保存数据