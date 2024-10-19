# Host: localhost  (Version: 5.7.26)
# Date: 2024-10-19 19:15:02
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "admin"
#

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=1025 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

#
# Data for table "admin"
#

/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1024,'root');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;

#
# Structure for table "commodity"
#

DROP TABLE IF EXISTS `commodity`;
CREATE TABLE `commodity` (
  `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品ID',
  `merchants_id` varchar(255) DEFAULT NULL COMMENT '商户ID',
  `commodity_title` varchar(255) DEFAULT NULL COMMENT '商品名',
  `commodity_info` varchar(255) DEFAULT NULL COMMENT '商品信息',
  `price` float(6,2) DEFAULT NULL COMMENT '价格',
  `inventory` int(11) DEFAULT NULL COMMENT '库存',
  `sold` int(11) DEFAULT NULL COMMENT '已售',
  `view` tinyint(1) DEFAULT NULL COMMENT '是否可见',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

#
# Data for table "commodity"
#

/*!40000 ALTER TABLE `commodity` DISABLE KEYS */;
INSERT INTO `commodity` VALUES (1,'1001','测试商品1','这是商品详情1',30.00,100,0,1),(2,'1002','测试商品2','这是商品详情2',30.00,100,0,1),(3,'1003','测试商品3','这是商品详情3',30.00,100,0,1);
/*!40000 ALTER TABLE `commodity` ENABLE KEYS */;

#
# Structure for table "merchants"
#

DROP TABLE IF EXISTS `merchants`;
CREATE TABLE `merchants` (
  `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商户ID',
  `name` varchar(255) DEFAULT NULL COMMENT '商户名',
  `password` varchar(255) DEFAULT NULL COMMENT '商户密码',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=1004 DEFAULT CHARSET=utf8;

#
# Data for table "merchants"
#

/*!40000 ALTER TABLE `merchants` DISABLE KEYS */;
INSERT INTO `merchants` VALUES (1001,'商家1','123456'),(1002,'商家2','123456'),(1003,'商家3','123456');
/*!40000 ALTER TABLE `merchants` ENABLE KEYS */;

#
# Structure for table "orders"
#

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单号',
  `user_id` bigint(20) DEFAULT NULL COMMENT '用户ID',
  `commodity_id` int(11) DEFAULT NULL COMMENT '商品ID',
  `commodity_title` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '商品名',
  `num` int(4) NOT NULL DEFAULT '0' COMMENT '商品数量',
  `merchants_id` int(11) DEFAULT NULL COMMENT '商户ID',
  `order_status` int(2) NOT NULL DEFAULT '0' COMMENT '订单状态',
  `order_time` datetime DEFAULT NULL COMMENT '下单时间',
  `consignee_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '收货人姓名',
  `phone_number` varchar(32) CHARACTER SET utf8 DEFAULT NULL COMMENT '收货人电话',
  `delivery_address` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '收货地址',
  `waybill_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "orders"
#

/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (35,1,1,'测试商品1',1,1001,15,'2024-10-17 13:17:56','我叫测试','1234567890','测试地址',NULL);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;

#
# Structure for table "shopping_car"
#

DROP TABLE IF EXISTS `shopping_car`;
CREATE TABLE `shopping_car` (
  `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT '购物车ID',
  `user_id` bigint(20) DEFAULT NULL COMMENT '用户ID',
  `commodity_id` int(11) DEFAULT NULL COMMENT '商品ID',
  `num` int(4) NOT NULL DEFAULT '0' COMMENT '商品数量',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "shopping_car"
#

/*!40000 ALTER TABLE `shopping_car` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopping_car` ENABLE KEYS */;

#
# Structure for table "user"
#

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `Id` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `name` varchar(255) NOT NULL DEFAULT '未命名' COMMENT '用户名',
  `password` varchar(255) DEFAULT NULL COMMENT '密码',
  `email` varchar(255) DEFAULT NULL COMMENT '邮件',
  `qq` varchar(255) DEFAULT NULL COMMENT 'QQ号',
  `weixin` varchar(255) DEFAULT NULL COMMENT '微信号',
  `consignee_name` varchar(255) DEFAULT NULL COMMENT '收货人姓名',
  `phone_number` varchar(32) DEFAULT NULL COMMENT '收货人电话',
  `delivery_address` varchar(255) DEFAULT NULL COMMENT '收货地址',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=123698746 DEFAULT CHARSET=utf8;

#
# Data for table "user"
#

/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'测试账号','test','test@test.com','1111','1111','我叫测试','1234567890','测试地址');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
