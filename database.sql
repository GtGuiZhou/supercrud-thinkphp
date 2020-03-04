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
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `idx_role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'root123','cs123456','2020-01-25 10:47:33',1,'2020-03-03 13:07:59','no'),(2,'root1234','29ad0e3fd3db681fb9f8091c756313f7','2020-01-25 15:02:16',2,'2020-03-03 12:34:42','no'),(3,'superadmin','29ad0e3fd3db681fb9f8091c756313f7','2020-01-31 10:45:13',1,'2020-02-02 05:00:45','yes'),(4,'egfasdfdwad','32521a85f68ae7188fbf92fb260565e8','2020-02-07 04:03:50',4,'2020-03-03 12:34:42','no'),(5,'asfdasf','118bf449820132ae8975feb2a198354e','2020-02-07 04:04:28',1,'2020-03-03 12:34:44','no'),(6,'dwadwa','ca6f5849d185ff842882f6f33083d1b6','2020-02-07 04:05:37',3,'2020-03-03 12:34:45','no'),(7,'eafgjasdknfa\'lfa','05c97017de87fdd2d89a8cbc3d427e3e','2020-02-07 04:12:15',12,'2020-03-03 12:34:46','no'),(8,'sadgdasjgaksdb','ba470eebb9a59fe644b462d053fe5c3d','2020-02-07 04:14:02',1,'2020-03-03 12:34:48','no'),(9,'dwadwadaw','20f6c4a39f1c3c1f883dafc70653063f','2020-02-07 04:26:22',1,'2020-03-03 12:34:49','no'),(10,'asgdsagsad','7309b7939c234360c97af0959fc0fb75','2020-02-07 04:28:11',1,'2020-03-03 12:34:50','no'),(18,'3213','2edd6b69c0a718a2024e1c711376604b','2020-03-01 20:20:32',10,'2020-03-01 20:20:32','yes');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
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
INSERT INTO `admin_role` VALUES (1,'超级管理员',0),(4,'三级管理员1',2),(7,'三级管理员2',4),(8,'五级管理员',4);
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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_role_menu`
--

LOCK TABLES `admin_role_menu` WRITE;
/*!40000 ALTER TABLE `admin_role_menu` DISABLE KEYS */;
INSERT INTO `admin_role_menu` VALUES (18,1,'/admin/admin'),(17,1,'/admin/role'),(19,1,'/admin/welcome'),(20,1,'/admin/welcome2');
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
INSERT INTO `admin_role_rule` VALUES (1,'delete-admin/admin/<id>'),(1,'delete-admin/role/<id>'),(1,'get-admin/admin'),(1,'get-admin/role'),(1,'get-admin/rule'),(1,'post-admin/admin'),(1,'post-admin/role'),(1,'put-admin/admin/<id>'),(1,'put-admin/admin/<id>/root'),(1,'put-admin/role/<id>'),(1,'put-admin/role/<id>/menu'),(1,'put-admin/role/<id>/rule');
/*!40000 ALTER TABLE `admin_role_rule` ENABLE KEYS */;
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
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `avatar` varchar(255) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `user_usernamePassword_uidx` (`username`,`password`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'root123','e10adc3949ba59abbe56e057f20f883e','2020-01-24 16:12:36','',''),(2,'root1234','29ad0e3fd3db681fb9f8091c756313f7','2020-01-25 10:34:48','',''),(3,'root12345','29ad0e3fd3db681fb9f8091c756313f7','2020-01-25 10:35:32','',''),(4,'root123456','e10adc3949ba59abbe56e057f20f883e','2020-01-25 10:36:23','','');
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

-- Dump completed on 2020-03-04 11:25:59
