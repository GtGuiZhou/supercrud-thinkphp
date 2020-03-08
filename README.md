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


## 待完成
1. 用户数据更新，其他登录失效
2. 引入Swagger,参考文档：https://learnku.com/laravel/t/7430/how-to-write-api-documents-based-on-swagger-php



验证码凭证-》验证要保存的的数据-》保存数据