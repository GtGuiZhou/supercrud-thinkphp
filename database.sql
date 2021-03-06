-- MySQL dump 10.13  Distrib 5.6.46, for Linux (x86_64)
--
-- Host: localhost    Database: supercrud-tp
-- ------------------------------------------------------
-- Server version	5.6.46-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(16) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `root` enum('yes','no') NOT NULL DEFAULT 'no' COMMENT '超级管理员',
  `avatar` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'root123','29ad0e3fd3db681fb9f8091c756313f7','2020-01-25 10:47:33',4,'2020-03-05 05:25:14','no',''),(2,'root1234','29ad0e3fd3db681fb9f8091c756313f7','2020-01-25 15:02:16',2,'2020-03-03 12:34:42','no',''),(3,'superadmin','29ad0e3fd3db681fb9f8091c756313f7','2020-01-31 10:45:13',4,'2020-03-04 14:55:52','yes',''),(4,'egfasdfdwad','32521a85f68ae7188fbf92fb260565e8','2020-02-07 04:03:50',4,'2020-03-03 12:34:42','no',''),(5,'asfdasf','118bf449820132ae8975feb2a198354e','2020-02-07 04:04:28',1,'2020-03-03 12:34:44','no',''),(6,'dwadwa','ca6f5849d185ff842882f6f33083d1b6','2020-02-07 04:05:37',3,'2020-03-03 12:34:45','no',''),(7,'eafgjasdknfa\'lfa','05c97017de87fdd2d89a8cbc3d427e3e','2020-02-07 04:12:15',12,'2020-03-03 12:34:46','no',''),(8,'sadgdasjgaksdb','ba470eebb9a59fe644b462d053fe5c3d','2020-02-07 04:14:02',1,'2020-03-03 12:34:48','no',''),(9,'dwadwadaw','20f6c4a39f1c3c1f883dafc70653063f','2020-02-07 04:26:22',1,'2020-03-03 12:34:49','no',''),(10,'asgdsagsad','7309b7939c234360c97af0959fc0fb75','2020-02-07 04:28:11',1,'2020-03-03 12:34:50','no',''),(18,'3213','2edd6b69c0a718a2024e1c711376604b','2020-03-01 20:20:32',10,'2020-03-01 20:20:32','yes','');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_login_record`
--

DROP TABLE IF EXISTS `admin_login_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_login_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL,
  `ip` char(15) NOT NULL,
  `position` varchar(150) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COMMENT='管理员登录记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_login_record`
--

LOCK TABLES `admin_login_record` WRITE;
/*!40000 ALTER TABLE `admin_login_record` DISABLE KEYS */;
INSERT INTO `admin_login_record` VALUES (1,3,'127.0.0.1','','2020-03-04 15:28:03'),(2,3,'127.0.0.1','','2020-03-05 03:26:28'),(3,3,'127.0.0.1','','2020-03-05 05:09:08'),(4,3,'127.0.0.1','','2020-03-05 05:10:49'),(5,1,'127.0.0.1','','2020-03-05 05:11:10'),(6,3,'127.0.0.1','','2020-03-05 05:57:47'),(7,3,'127.0.0.1','','2020-03-05 06:02:31'),(8,3,'127.0.0.1','','2020-03-05 06:07:16'),(9,3,'127.0.0.1','','2020-03-05 06:08:57'),(10,3,'127.0.0.1','','2020-03-05 06:10:29'),(11,1,'127.0.0.1','','2020-03-05 06:10:43'),(12,3,'127.0.0.1','','2020-03-05 06:22:49'),(13,3,'127.0.0.1','','2020-03-05 06:24:46'),(14,3,'127.0.0.1','','2020-03-05 06:25:53'),(15,3,'127.0.0.1','','2020-03-05 07:22:31'),(16,1,'127.0.0.1','','2020-03-05 07:22:56'),(17,3,'127.0.0.1','','2020-03-05 07:32:29'),(18,3,'127.0.0.1','','2020-03-05 07:32:59'),(19,1,'127.0.0.1','','2020-03-05 07:33:11'),(20,3,'127.0.0.1','','2020-03-05 11:54:23'),(21,1,'127.0.0.1','','2020-03-05 11:54:41'),(22,3,'127.0.0.1','','2020-03-05 23:51:02'),(23,3,'127.0.0.1','','2020-03-05 23:56:45'),(24,1,'127.0.0.1','','2020-03-06 00:00:46'),(25,3,'127.0.0.1','','2020-03-06 00:34:22'),(26,3,'127.0.0.1','','2020-03-06 00:35:19'),(27,1,'127.0.0.1','','2020-03-06 01:51:32'),(28,3,'127.0.0.1','','2020-03-07 03:46:47'),(29,3,'127.0.0.1','','2020-03-07 04:06:31'),(30,3,'127.0.0.1','','2020-03-07 04:06:36'),(31,3,'127.0.0.1','','2020-03-07 04:06:46'),(32,3,'127.0.0.1','','2020-03-07 04:06:57'),(33,3,'127.0.0.1','','2020-03-07 04:08:15'),(34,3,'127.0.0.1','','2020-03-07 04:08:55'),(35,3,'127.0.0.1','','2020-03-07 04:09:27'),(36,3,'127.0.0.1','','2020-03-07 04:10:08'),(37,3,'127.0.0.1','','2020-03-07 04:10:30'),(38,3,'127.0.0.1','','2020-03-07 04:11:04'),(39,3,'127.0.0.1','','2020-03-07 04:48:39'),(40,3,'127.0.0.1','','2020-03-07 04:48:57'),(41,3,'127.0.0.1','','2020-03-07 04:50:15'),(42,3,'127.0.0.1','','2020-03-07 04:50:24'),(43,3,'127.0.0.1','','2020-03-07 04:51:01'),(44,3,'127.0.0.1','','2020-03-07 04:53:33'),(45,3,'127.0.0.1','','2020-03-07 04:54:00'),(46,3,'127.0.0.1','','2020-03-07 04:55:25'),(47,3,'127.0.0.1','','2020-03-07 05:00:52'),(48,3,'127.0.0.1','','2020-03-07 05:06:09'),(49,3,'127.0.0.1','','2020-03-07 05:09:39'),(50,3,'127.0.0.1','','2020-03-07 05:12:58'),(51,3,'127.0.0.1','','2020-03-07 11:56:36'),(52,3,'127.0.0.1','','2020-03-07 11:59:15'),(53,3,'127.0.0.1','','2020-03-07 12:00:54'),(54,3,'127.0.0.1','','2020-03-07 12:02:14'),(55,3,'127.0.0.1','','2020-03-08 02:44:44'),(56,3,'127.0.0.1','','2020-03-08 05:17:27'),(57,3,'127.0.0.1','','2020-03-08 13:13:43'),(58,3,'127.0.0.1','','2020-03-08 13:17:09'),(59,1,'127.0.0.1','','2020-03-08 13:37:09'),(60,3,'127.0.0.1','','2020-03-08 13:38:24'),(61,1,'127.0.0.1','','2020-03-08 13:38:29'),(62,3,'127.0.0.1','','2020-03-08 13:44:24'),(63,1,'127.0.0.1','','2020-03-08 13:44:49'),(64,3,'127.0.0.1','','2020-03-08 13:52:48'),(65,3,'127.0.0.1','','2020-03-08 13:54:56'),(66,1,'127.0.0.1','','2020-03-08 13:59:04'),(67,3,'127.0.0.1','','2020-03-08 14:07:03'),(68,3,'127.0.0.1','','2020-03-08 14:11:12'),(69,3,'127.0.0.1','','2020-03-09 06:07:16'),(70,3,'127.0.0.1','','2020-03-11 03:55:06'),(71,3,'127.0.0.1','','2020-03-18 09:09:40'),(72,3,'127.0.0.1','','2020-03-20 12:28:42'),(73,3,'127.0.0.1','','2020-03-20 13:53:35'),(74,3,'127.0.0.1','','2020-03-21 14:28:40'),(75,3,'127.0.0.1','','2020-03-22 01:38:10'),(76,3,'127.0.0.1','','2020-03-22 11:40:56'),(77,3,'127.0.0.1','','2020-03-23 02:17:28'),(78,3,'127.0.0.1','','2020-03-24 08:29:11');
/*!40000 ALTER TABLE `admin_login_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_role`
--

DROP TABLE IF EXISTS `admin_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '角色名称',
  `pid` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='管理员角色';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_role`
--

LOCK TABLES `admin_role` WRITE;
/*!40000 ALTER TABLE `admin_role` DISABLE KEYS */;
INSERT INTO `admin_role` VALUES (4,'三级管理员1',2),(7,'三级管理员2',4),(8,'五级管理员',4);
/*!40000 ALTER TABLE `admin_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_role_menu`
--

DROP TABLE IF EXISTS `admin_role_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_role_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `path` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uidx_role_path` (`role_id`,`path`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_role_menu`
--

LOCK TABLES `admin_role_menu` WRITE;
/*!40000 ALTER TABLE `admin_role_menu` DISABLE KEYS */;
INSERT INTO `admin_role_menu` VALUES (18,1,'/admin/admin'),(17,1,'/admin/role'),(19,1,'/admin/welcome'),(20,1,'/admin/welcome2'),(21,4,'/admin/admin');
/*!40000 ALTER TABLE `admin_role_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_role_rule`
--

DROP TABLE IF EXISTS `admin_role_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_role_rule` (
  `role_id` int(10) unsigned NOT NULL,
  `rule` varchar(50) NOT NULL,
  UNIQUE KEY `uidx_role_rule` (`role_id`,`rule`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_role_rule`
--

LOCK TABLES `admin_role_rule` WRITE;
/*!40000 ALTER TABLE `admin_role_rule` DISABLE KEYS */;
INSERT INTO `admin_role_rule` VALUES (1,'delete-admin/admin/<id>'),(1,'delete-admin/role/<id>'),(1,'get-admin/admin'),(1,'get-admin/role'),(1,'get-admin/rule'),(1,'post-admin/admin'),(1,'post-admin/role'),(1,'put-admin/admin/<id>'),(1,'put-admin/admin/<id>/root'),(1,'put-admin/role/<id>'),(1,'put-admin/role/<id>/menu'),(1,'put-admin/role/<id>/rule'),(4,'delete-admin/admin/<id>'),(4,'get-admin/admin'),(4,'get-admin/api-access-record/api'),(4,'get-admin/api-access-record/crawler'),(4,'get-admin/api-access-record/device'),(4,'get-admin/api-access-record/hour'),(4,'get-admin/api-access-record/ip'),(4,'get-admin/api-access-record/os'),(4,'get-admin/api-access-record/ua'),(4,'get-admin/api-access-record/week'),(4,'post-admin/admin'),(4,'put-admin/admin/<id>');
/*!40000 ALTER TABLE `admin_role_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `api_access_record`
--

DROP TABLE IF EXISTS `api_access_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `api_access_record` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `api` varchar(50) NOT NULL,
  `ip` char(15) NOT NULL,
  `os` varchar(50) NOT NULL DEFAULT '未知',
  `ua` varchar(50) NOT NULL DEFAULT '未知',
  `crawler` enum('yes','no') NOT NULL DEFAULT 'no' COMMENT '是否爬虫',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `device` varchar(50) NOT NULL DEFAULT '未知',
  `time` float NOT NULL DEFAULT '0' COMMENT '间隔',
  `week` tinyint(4) NOT NULL DEFAULT '0' COMMENT '星期几',
  `hour` tinyint(4) NOT NULL DEFAULT '0' COMMENT '第几小时',
  PRIMARY KEY (`id`),
  KEY `api` (`api`),
  KEY `device` (`device`),
  KEY `ip` (`ip`),
  KEY `os` (`os`),
  KEY `ua` (`ua`),
  KEY `hour` (`hour`),
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB AUTO_INCREMENT=4543091 DEFAULT CHARSET=utf8mb4 COMMENT='api访问记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `api_access_record`
--

LOCK TABLES `api_access_record` WRITE;
/*!40000 ALTER TABLE `api_access_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `api_access_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `file`
--

DROP TABLE IF EXISTS `file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `file` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `md5` char(32) NOT NULL,
  `url` char(250) NOT NULL COMMENT '保存路径',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `driver` enum('local','cloud','aliyun') NOT NULL DEFAULT 'local',
  `local_url` varchar(50) NOT NULL COMMENT '本地存储路径',
  PRIMARY KEY (`id`),
  UNIQUE KEY `md5` (`md5`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `file`
--

LOCK TABLES `file` WRITE;
/*!40000 ALTER TABLE `file` DISABLE KEYS */;
INSERT INTO `file` VALUES (6,'ce4954e0b8289e11e0e91e8a36d70977','https://bjjl.oss-cn-shenzhen.aliyuncs.com/ce4954e0b8289e11e0e91e8a36d70977.jpg','2020-03-23 05:22:19','aliyun','upload/ce/4954e0b8289e11e0e91e8a36d70977.jpg'),(7,'3d1b87c04023247c47ca13cf7cb3904b','https://bjjl.oss-cn-shenzhen.aliyuncs.com/3d1b87c04023247c47ca13cf7cb3904b.jpg','2020-03-23 05:22:26','aliyun','upload/3d/1b87c04023247c47ca13cf7cb3904b.jpg');
/*!40000 ALTER TABLE `file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pay_order`
--

DROP TABLE IF EXISTS `pay_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pay_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `body` varchar(255) NOT NULL COMMENT '下单内容',
  `out_trade_no` char(32) NOT NULL COMMENT '订单号',
  `trade_type` varchar(20) NOT NULL COMMENT '订单类型',
  `pay_type` enum('wechat','alipay') NOT NULL COMMENT '支付类型',
  `total_fee` int(10) unsigned NOT NULL COMMENT '支付金额',
  `status` enum('unpaid','paid','paid_fail') NOT NULL DEFAULT 'unpaid' COMMENT '订单状态',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uidx_user_order` (`out_trade_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='支付订单';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_order`
--

LOCK TABLES `pay_order` WRITE;
/*!40000 ALTER TABLE `pay_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `pay_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pay_refund`
--

DROP TABLE IF EXISTS `pay_refund`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pay_refund` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `out_trade_no` char(32) NOT NULL COMMENT '订单号',
  `comment` varchar(255) NOT NULL COMMENT '退款备注',
  `refund_fee` int(10) unsigned NOT NULL COMMENT '退款金额',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='退款订单';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_refund`
--

LOCK TABLES `pay_refund` WRITE;
/*!40000 ALTER TABLE `pay_refund` DISABLE KEYS */;
/*!40000 ALTER TABLE `pay_refund` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(16) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '邮箱',
  `phone` char(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `lock` enum('yes','no') NOT NULL DEFAULT 'no' COMMENT '锁定账号',
  `nickname` varchar(20) NOT NULL DEFAULT '' COMMENT '昵称',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_usernamePassword_uidx` (`username`,`password`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'test123','29ad0e3fd3db681fb9f8091c756313f7','','','2020-03-08 14:46:07','no','',''),(2,'asdfasdf','da00c473044a131e4c58e53b81187e9c','','','2020-03-21 07:01:41','no','','');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_pay_order`
--

DROP TABLE IF EXISTS `user_pay_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_pay_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `out_trade_no` char(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uidx_user_order` (`user_id`,`out_trade_no`),
  UNIQUE KEY `user_pay_order_out_trade_no_uindex` (`out_trade_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户支付订单';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_pay_order`
--

LOCK TABLES `user_pay_order` WRITE;
/*!40000 ALTER TABLE `user_pay_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_pay_order` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-03-24 19:37:56
