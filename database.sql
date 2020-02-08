-- MySQL dump 10.13  Distrib 5.6.46, for Linux (x86_64)
--
-- Host: localhost    Database: supercrud-tp
-- ------------------------------------------------------
-- Server version	5.6.46-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
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
  `role_id` int(10) unsigned NOT NULL COMMENT '角色',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `idx_role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'root123','cs123456','2020-01-25 10:47:33',2,'2020-02-02 05:00:45'),(2,'root1234','29ad0e3fd3db681fb9f8091c756313f7','2020-01-25 15:02:16',2,'2020-02-07 07:31:33'),(3,'superadmin','29ad0e3fd3db681fb9f8091c756313f7','2020-01-31 10:45:13',1,'2020-02-02 05:00:45'),(4,'egfasdfdwad','32521a85f68ae7188fbf92fb260565e8','2020-02-07 04:03:50',4,'2020-02-07 08:39:41'),(5,'asfdasf','118bf449820132ae8975feb2a198354e','2020-02-07 04:04:28',1,'2020-02-07 04:04:28'),(6,'dwadwa','ca6f5849d185ff842882f6f33083d1b6','2020-02-07 04:05:37',3,'2020-02-07 04:05:37'),(7,'eafgjasdknfa\'lfa','05c97017de87fdd2d89a8cbc3d427e3e','2020-02-07 04:12:15',12,'2020-02-07 04:12:15'),(8,'sadgdasjgaksdb','ba470eebb9a59fe644b462d053fe5c3d','2020-02-07 04:14:02',1,'2020-02-07 04:14:02'),(9,'dwadwadaw','20f6c4a39f1c3c1f883dafc70653063f','2020-02-07 04:26:22',1,'2020-02-07 04:26:22'),(10,'asgdsagsad','7309b7939c234360c97af0959fc0fb75','2020-02-07 04:28:11',1,'2020-02-07 04:28:11');
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COMMENT='管理员角色';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_role`
--

LOCK TABLES `admin_role` WRITE;
/*!40000 ALTER TABLE `admin_role` DISABLE KEYS */;
INSERT INTO `admin_role` VALUES (1,'超级管理员',0),(2,'二级管理员1',1),(3,'二级管理员2',1),(4,'三级管理员1',2),(5,'三级管理员2',3),(7,'三级管理员2',4),(8,'五级管理员',4),(12,'test',1);
/*!40000 ALTER TABLE `admin_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_role_rule`
--

DROP TABLE IF EXISTS `admin_role_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_role_rule` (
  `role_id` int(10) unsigned NOT NULL,
  `rule_id` int(10) unsigned NOT NULL,
  UNIQUE KEY `uidx_roleId_ruleId` (`role_id`,`rule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_role_rule`
--

LOCK TABLES `admin_role_rule` WRITE;
/*!40000 ALTER TABLE `admin_role_rule` DISABLE KEYS */;
INSERT INTO `admin_role_rule` VALUES (2,84),(2,90),(2,91),(2,92),(2,93),(2,94),(12,49),(12,50),(12,51),(12,52),(12,53),(13,51),(13,82),(13,85),(13,86),(13,87),(13,88),(13,89);
/*!40000 ALTER TABLE `admin_role_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_rule`
--

DROP TABLE IF EXISTS `admin_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rule` varchar(125) NOT NULL COMMENT '鉴权规则/菜单路由',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父规则',
  `is_menu` enum('yes','no') NOT NULL DEFAULT 'no' COMMENT '是否是菜单',
  `icon` varchar(125) DEFAULT NULL COMMENT '菜单图标',
  `name` varchar(25) NOT NULL COMMENT '规则名称',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`rule`),
  KEY `role_pid_idx` (`pid`),
  KEY `idx_rule` (`rule`),
  KEY `idx_pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_rule`
--

LOCK TABLES `admin_rule` WRITE;
/*!40000 ALTER TABLE `admin_rule` DISABLE KEYS */;
INSERT INTO `admin_rule` VALUES (82,'admin/rule',0,'yes','el-icon-s-unfold','规则管理'),(83,'admin/role',0,'yes','el-icon-user','角色管理'),(84,'admin/admin',0,'yes','el-icon-user-solid','管理员管理'),(85,'admin.rule/insert',82,'no',NULL,'增加'),(86,'admin.rule/delete',82,'no',NULL,'删除'),(87,'admin.rule/read',82,'no',NULL,'指定读'),(88,'admin.rule/index',82,'no',NULL,'批量读'),(89,'admin.rule/update',82,'no',NULL,'修改'),(90,'admin.childrenadmin/insert',84,'no',NULL,'增加'),(91,'admin.childrenadmin/delete',84,'no',NULL,'删除'),(92,'admin.childrenadmin/read',84,'no',NULL,'指定读'),(93,'admin.childrenadmin/index',84,'no',NULL,'批量读'),(94,'admin.childrenadmin/update',84,'no',NULL,'修改'),(95,'admin.childrenrole/insert',83,'no',NULL,'增加'),(96,'admin.childrenrole/delete',83,'no',NULL,'删除'),(97,'admin.childrenrole/read',83,'no',NULL,'指定读'),(98,'admin.childrenrole/index',83,'no',NULL,'批量读'),(99,'admin.childrenrole/update',83,'no',NULL,'修改'),(100,'admin.childrenrole/rulestree',83,'no','','获取角色的规则(树型)'),(101,'admin.childrenrole/ruleslist',83,'no','','获取角色规则(列表)');
/*!40000 ALTER TABLE `admin_rule` ENABLE KEYS */;
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
INSERT INTO `user` VALUES (1,'root123','e10adc3949ba59abbe56e057f20f883e','2020-01-24 16:12:36'),(2,'root1234','29ad0e3fd3db681fb9f8091c756313f7','2020-01-25 10:34:48'),(3,'root12345','29ad0e3fd3db681fb9f8091c756313f7','2020-01-25 10:35:32'),(4,'root123456','e10adc3949ba59abbe56e057f20f883e','2020-01-25 10:36:23');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-02-08 14:00:18
