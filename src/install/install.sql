DROP TABLE IF EXISTS `pre_config`;
CREATE TABLE `pre_config` (
  `k` char(255) NOT NULL COMMENT '配置项',
  `v` text COMMENT '配置内容',
  PRIMARY KEY (`k`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `pre_config` VALUES ('site_title', '程江域名管理系统');
INSERT INTO `pre_config` VALUES ('site_keywords', '程江域名管理系统,程江,程江科技,域名,域名管理,管理系统,域名管理系统');
INSERT INTO `pre_config` VALUES ('site_description', '程江旗下域名管理系统');
INSERT INTO `pre_config` VALUES ('site_copyright', '程江科技');
INSERT INTO `pre_config` VALUES ('site_qq', '350430869');
INSERT INTO `pre_config` VALUES ('admin_user', 'admin');
INSERT INTO `pre_config` VALUES ('admin_pwd', '123456');
INSERT INTO `pre_config` VALUES ('version', '1001');
INSERT INTO `pre_config` VALUES ('blackip', '');
INSERT INTO `pre_config` VALUES ('ip_type', '0');

DROP TABLE IF EXISTS `pre_domain`;
CREATE TABLE `pre_domain` (
  `domain_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `domain_name` char(255) NOT NULL COMMENT '域名',
  `domain_status` char(11) DEFAULT '启用' COMMENT '域名状态',
  `domain_remark` char(255) DEFAULT NULL COMMENT '域名备注',
  `create_time` datetime DEFAULT NULL COMMENT '添加时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`domain_id`),
  UNIQUE KEY `unique_domain_name` (`domain_name`),
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `pre_order`;
CREATE TABLE `pre_order` (
  `order_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_domain` char(255) NOT NULL COMMENT '域名',
  `order_from` char(255) DEFAULT NULL COMMENT '订单来源',
  `order_type` varchar(255) DEFAULT NULL COMMENT '订单类型',
  `order_costs` char(11) DEFAULT NULL COMMENT '订单费用',
  `create_time` datetime DEFAULT NULL COMMENT '订单时间',
  PRIMARY KEY (`order_id`),
  KEY `index_order_domain` (`order_domain`),
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `pre_token`;
CREATE TABLE `pre_token` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `name` varchar(30) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `appid` varchar(32) NOT NULL,
  `appsecret` varchar(50) NOT NULL,
  `access_token` varchar(300) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL,
  `expiretime` datetime DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `appid` (`appid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `pre_log`;
CREATE TABLE `pre_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` tinyint(4) NOT NULL DEFAULT '1',
  `action` varchar(40) NOT NULL,
  `data` varchar(150) DEFAULT NULL,
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
