欢迎访问Solr中国项目地址！

Solr中国官方网站：http://www.solr.cc

Solr中国官方QQ群：[187670960](http://wp.qq.com/wpa/qunwpa?idkey=ef0fbce14b32d502930c2a58527609390767139815d3382cc2b1279820589e58)

Solr中国微信公共频道：solrcn


微信扫描下面的二维码关注我们


[![](http://www.solr.cc/weixin/weixin.jpg)](http://www.solr.cc/blog/)


我们做了一些工作:



Solr4.0

1.解决集群中某台机器宕机后自动更新zookeeper信息，将宕机的服务器对应的shard信息从zookeeper中自动删除

2.修改solrj合并多个core代码实现

3.修改手工删除core后更新zookeeper中shard信息和collections节点下leader和leader\_elect节点内容

4.solrcloud集群中某个shard完全宕机后,继续往存活的shard中分发数据

5.优化solrcloud索引速度

Solr4.1

1.优化solrcloud索引速度

2.扩展Cache到Berkeley DB

3.在HDFS上建立索引