DROP TABLE IF EXISTS `pre_config`;
CREATE TABLE `pre_config` (
  `k` char(255) NOT NULL COMMENT '配置项',
  `v` text COMMENT '配置内容',
  PRIMARY KEY (`k`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `pre_config` VALUES ('site_name', 'DouName');
INSERT INTO `pre_config` VALUES ('site_title', '程江域名资产管理系统');
INSERT INTO `pre_config` VALUES ('site_keywords', '程江域名资产管理系统,程江,程江科技,域名,域名管理,管理系统,域名管理系统');
INSERT INTO `pre_config` VALUES ('site_description', '程江旗下域名资产管理系统');
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
  `domain_provider` char(255) NOT NULL COMMENT '域名服务商',
  `domain_registrar` char(255) NOT NULL COMMENT '域名注册商',
  `domain_status` char(11) DEFAULT '1' COMMENT '域名状态',
  `domain_icp` char(255) DEFAULT NULL COMMENT '域名ICP备案号',
  `domain_icp_agent` char(255) DEFAULT NULL COMMENT '域名ICP备案服务商',
  `domain_remark` char(255) DEFAULT NULL COMMENT '域名备注',
  `domain_create_time` datetime DEFAULT NULL COMMENT '域名注册时间',
  `domain_expire_time` datetime DEFAULT NULL COMMENT '域名到期时间',
  `create_time` datetime DEFAULT NULL COMMENT '添加时间',
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`domain_id`),
  UNIQUE KEY `unique_domain_name` (`domain_name`),
  KEY `create_time` (`update_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `pre_domain_permission`;
CREATE TABLE `pre_domain_permission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `domain` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `pre_icp`;
CREATE TABLE `pre_icp` (
  `icp_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `icp_number` varchar(255) DEFAULT NULL COMMENT '备案号',
  `icp_type` varchar(255) DEFAULT NULL COMMENT '备案服务类型',
  `icp_appname` varchar(255) DEFAULT NULL COMMENT '备案服务名称',
  `icp_appid` varchar(255) NOT NULL COMMENT '备案域名/标识/AppId',
  `icp_content` varchar(255) DEFAULT NULL COMMENT '备案内容',
  `icp_unit` varchar(255) NOT NULL COMMENT '备案主办单位',
  `icp_unit_type` varchar(255) DEFAULT NULL COMMENT '备案主体类型',
  `icp_status` varchar(11) DEFAULT '1' COMMENT '备案状态',
  `icp_agent` varchar(255) DEFAULT NULL COMMENT '备案接入商',
  `icp_remark` varchar(255) DEFAULT NULL COMMENT '备案备注',
  `icp_create_time` datetime DEFAULT NULL COMMENT '备案通过时间',
  `create_time` datetime DEFAULT NULL COMMENT '添加时间',
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`icp_id`),
  KEY `create_time` (`update_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `pre_order`;
CREATE TABLE `pre_order` (
  `order_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_domain` char(255) NOT NULL COMMENT '域名',
  `order_provider` char(255) DEFAULT NULL COMMENT '订单服务商',
  `order_type` varchar(255) DEFAULT NULL COMMENT '订单类型',
  `order_costs` char(11) DEFAULT NULL COMMENT '订单费用',
  `create_time` datetime DEFAULT NULL COMMENT '订单时间',
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '备注修改时间',
  `order_remark` char(255) DEFAULT NULL COMMENT '订单备注',
  PRIMARY KEY (`order_id`),
  KEY `index_order_domain` (`order_domain`),
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `pre_order_server`;
CREATE TABLE `pre_order_server` (
  `order_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_server_id` int(11) NOT NULL COMMENT '服务器id',
  `order_provider` varchar(255) DEFAULT NULL COMMENT '订单服务商',
  `order_type` varchar(255) DEFAULT NULL COMMENT '订单类型',
  `order_costs` char(11) DEFAULT NULL COMMENT '订单费用',
  `order_remark` char(255) DEFAULT NULL COMMENT '订单备注',
  `order_create_time` datetime DEFAULT NULL COMMENT '订单创建时间',
  `create_time` datetime DEFAULT NULL COMMENT '添加时间',
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `pre_record`;
CREATE TABLE `pre_record` (
  `record_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `record_sub_domain` char(255) NOT NULL DEFAULT '@' COMMENT '主机记录，默认@，如 www，可选',
  `record_domain` char(255) NOT NULL COMMENT '域名',
  `record_type` char(255) NOT NULL COMMENT '记录类型，比如：A、CNAME、TXT，必选',
  `record_value` char(255) NOT NULL COMMENT '记录值',
  `record_status` char(11) DEFAULT '1' COMMENT '记录状态',
  `record_ttl` int(255) DEFAULT NULL COMMENT 'TTL，范围1-604800，不同等级域名最小值不同，可选',
  `record_line` char(255) DEFAULT NULL COMMENT '记录线路，通过API记录线路获得，中文，比如：默认，必选',
  `record_note` char(255) DEFAULT NULL COMMENT '记录备注',
  `create_time` datetime DEFAULT NULL COMMENT '注册时间',
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`record_id`),
  UNIQUE KEY `unique_domain_name` (`record_sub_domain`),
  KEY `create_time` (`update_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `pre_server`;
CREATE TABLE `pre_server` (
  `server_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `server_name` char(255) NOT NULL COMMENT '主机名',
  `server_type` char(255) NOT NULL COMMENT '服务器类型:独享dedicated,共享shared',
  `server_ip` char(255) NOT NULL COMMENT '服务器IP',
  `server_ip_type` char(255) NOT NULL COMMENT '服务器IP类型:独享dedicated,共享shared',
  `server_provider` char(255) NOT NULL COMMENT '服务器提供商',
  `server_region` char(255) DEFAULT NULL COMMENT '服务器所在地区',
  `server_status` char(11) DEFAULT '1' COMMENT '服务器状态',
  `server_remark` char(255) DEFAULT NULL COMMENT '服务器备注信息',
  `server_create_time` datetime DEFAULT NULL COMMENT '服务器开通时间',
  `server_expire_time` datetime DEFAULT NULL COMMENT '服务器到期时间',
  `create_time` datetime DEFAULT NULL COMMENT '添加时间',
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`server_id`),
  KEY `server_ip` (`server_ip`)
) ENGINE=InnoDB AUTO_INCREMENT=1009 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `pre_token`;
CREATE TABLE `pre_token` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `name` varchar(30) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `appid` varchar(32) NOT NULL,
  `appsecret` varchar(50) NOT NULL,
  `access_token` varchar(300) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
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

DROP TABLE IF EXISTS `pre_user`;
CREATE TABLE `pre_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `type` varchar(20) NOT NULL,
  `openid` varchar(150) NOT NULL COMMENT 'OpenID',
  `nickname` varchar(255) NOT NULL COMMENT '用户昵称',
  `faceimg` varchar(255) DEFAULT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  `regip` varchar(20) DEFAULT NULL COMMENT '注册IP',
  `loginip` varchar(20) DEFAULT NULL COMMENT '登录IP',
  `level` tinyint(4) NOT NULL DEFAULT '0',
  `create_time` datetime NOT NULL COMMENT '添加时间',
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '备注修改时间',
  PRIMARY KEY (`uid`),
  KEY `openid` (`openid`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
