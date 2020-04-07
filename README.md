## 使用说明
### 自动生成代码
1. 设计好数据库结构
2. 在.env文件中配置好数据库信息
3. 执行php think builder，程序会自动根据数据库表给你生成前后端代码，放在z-builder中，你可以将代码复制到你的工程中
### 编写接口


##　特性
- 微信公众号对接
- 第三方云存储
- 分布式文件(读)
- api访问记录
- 前后端代码生成
- 后端微信扫码登录

## 目录说明
```
|———— app
|       |———— command 自定义指令
|       |———— controller 控制器目录
|               |————  管理端crud控制器模块
|       |———— exceptions 异常处理目录
|               |————  ExceptionHandle.php      异常接管处理文件
|               |————  CheckException.php       常规异常，触发该异常表明向前端展示该异常的msg即可
|               |————  NoLoginException.php     未登录异常，触发该异常告诉前端当前用户未登录
|               |————  InternalException.php    未知异常，触发该异常表明服务器出现了未知错误，不能告诉前端，需要在记录在日志中，ExceptionHandle会进行日志记录
|———— z-builder 代码生成目录
|       |———— admin 后端代码文件夹
|       |———— model 后端数据库模型文件夹
|       |———— vue-pages 前端页面文件夹
|       |———— backend_router.php 后端路由文件
|       |———— frontend_router.php 前端路由文件
```

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
4. 队列

## 文件上传云存储
执行`php think autocouldstore`命令即可,这是一个上传脚本

## 待完成
1. 引入Swagger,参考文档：https://learnku.com/laravel/t/7430/how-to-write-api-documents-based-on-swagger-php
2. api说明清单
3. 执行file->read方法时检测当前文件是存储在本地的,要能够支持跨服务器访问
4. 编写测试程序
5. 微信扫码登录

验证码凭证-》验证要保存的的数据-》保存数据